<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use App\Models\TaskDependency;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class WbsController extends Controller
{
    /**
     * Display WBS view for a project.
     */
    public function index(Project $project)
    {
        $user = auth()->user();
        $this->authorizeProjectAccess($user, $project);

        // Get root level tasks with all children
        $tasks = Task::rootTasks($project->id);
        $tasks->load(['children.children.children', 'assignee']); // Load 3 levels deep

        // Get all project members for assignment dropdown
        $members = $project->members;

        return view('pages.wbs.index', compact('project', 'tasks', 'members'));
    }

    /**
     * Get task tree structure as JSON (for AJAX).
     */
    public function tree(Project $project)
    {
        $user = auth()->user();
        $this->authorizeProjectAccess($user, $project);

        $tasks = Task::rootTasks($project->id);
        $tree = $tasks->map(fn($task) => $task->toTree());

        return response()->json([
            'success' => true,
            'tree' => $tree,
        ]);
    }

    /**
     * Store a new task in WBS.
     */
    public function store(Request $request, Project $project)
    {
        $user = auth()->user();
        $this->authorizeProjectAccess($user, $project);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:tasks,id',
            'assigned_to' => 'nullable|exists:users,id',
            'priority' => 'required|in:low,medium,high,critical',
            'due_date' => 'nullable|date',
            'estimated_duration' => 'nullable|integer|min:0',
        ]);

        DB::beginTransaction();
        try {
            // Get next order number for this level
            $query = Task::where('project_id', $project->id);

            if ($validated['parent_id'] ?? null) {
                $query->where('parent_id', $validated['parent_id']);
            } else {
                $query->whereNull('parent_id');
            }

            $maxOrder = $query->max('order') ?? -1;

            $task = Task::create([
                'project_id' => $project->id,
                'parent_id' => $validated['parent_id'] ?? null,
                'title' => $validated['title'],
                'description' => $validated['description'] ?? null,
                'assigned_to' => $validated['assigned_to'] ?? null,
                'priority' => $validated['priority'],
                'due_date' => $validated['due_date'] ?? null,
                'estimated_duration' => $validated['estimated_duration'] ?? null,
                'status' => 'todo',
                'order' => $maxOrder + 1,
            ]);

            // Update WBS codes for all tasks in project
            $this->regenerateWbsCodes($project);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Task created successfully',
                'task' => $task->fresh(['assignee', 'children']),
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to create task: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update task order (for drag & drop reordering).
     */
    public function reorder(Request $request, Project $project)
    {
        $user = auth()->user();
        $this->authorizeProjectAccess($user, $project);

        $validated = $request->validate([
            'task_id' => 'required|exists:tasks,id',
            'new_parent_id' => 'nullable|exists:tasks,id',
            'new_order' => 'required|integer|min:0',
        ]);

        DB::beginTransaction();
        try {
            $task = Task::findOrFail($validated['task_id']);

            // Verify task belongs to this project
            if ($task->project_id !== $project->id) {
                throw new \Exception('Task does not belong to this project');
            }

            $oldParentId = $task->parent_id;
            $newParentId = $validated['new_parent_id'];

            // Update task parent and order
            $task->parent_id = $newParentId;
            $task->order = $validated['new_order'];
            $task->save();

            // Reorder siblings in old parent
            if ($oldParentId !== $newParentId) {
                $this->reorderSiblings($project->id, $oldParentId);
            }

            // Reorder siblings in new parent
            $this->reorderSiblings($project->id, $newParentId);

            // Regenerate WBS codes
            $this->regenerateWbsCodes($project);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Task reordered successfully',
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to reorder task: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update task details inline.
     */
    public function update(Request $request, Project $project, Task $task)
    {
        $user = auth()->user();
        $this->authorizeProjectAccess($user, $project);

        if ($task->project_id !== $project->id) {
            abort(403, 'Task does not belong to this project');
        }

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'assigned_to' => 'nullable|exists:users,id',
            'priority' => 'sometimes|in:low,medium,high,critical',
            'status' => 'sometimes|in:todo,in-progress,review,completed',
            'due_date' => 'nullable|date',
        ]);

        $task->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Task updated successfully',
            'task' => $task->fresh(['assignee']),
        ]);
    }

    /**
     * Delete a task and its children.
     */
    public function destroy(Project $project, Task $task)
    {
        $user = auth()->user();
        $this->authorizeProjectAccess($user, $project);

        if ($task->project_id !== $project->id) {
            abort(403, 'Task does not belong to this project');
        }

        DB::beginTransaction();
        try {
            $parentId = $task->parent_id;

            // Delete task (cascade will handle children)
            $task->delete();

            // Reorder remaining siblings
            $this->reorderSiblings($project->id, $parentId);

            // Regenerate WBS codes
            $this->regenerateWbsCodes($project);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Task deleted successfully',
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete task: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Helper: Reorder siblings after insert/delete/move.
     */
    private function reorderSiblings($projectId, $parentId)
    {
        $query = Task::where('project_id', $projectId);

        if ($parentId) {
            $query->where('parent_id', $parentId);
        } else {
            $query->whereNull('parent_id');
        }

        $siblings = $query->orderBy('order')->get();

        foreach ($siblings as $index => $sibling) {
            $sibling->order = $index;
            $sibling->saveQuietly();
        }
    }

    /**
     * Helper: Regenerate WBS codes for entire project.
     */
    private function regenerateWbsCodes(Project $project)
    {
        $rootTasks = Task::rootTasks($project->id);

        foreach ($rootTasks as $task) {
            $task->updateWbsCode();
        }
    }

    /**
     * Helper: Check if user can access project.
     */
    private function authorizeProjectAccess($user, $project)
    {
        if (Gate::allows('admin')) {
            return true;
        }

        if (Gate::allows('team_lead')) {
            $teamLeadTeams = $user->leadingTeams->pluck('id');
            if (!$teamLeadTeams->contains($project->team)) {
                abort(403, 'Unauthorized access to this project.');
            }
            return true;
        }

        // Team member must be assigned to project
        if (!$project->members->contains($user->id)) {
            abort(403, 'You are not a member of this project.');
        }

        return true;
    }

    /**
     * Add dependency between tasks.
     */
    public function addDependency(Request $request, Project $project)
    {
        $user = auth()->user();
        $this->authorizeProjectAccess($user, $project);

        $validated = $request->validate([
            'task_id' => 'required|exists:tasks,id',
            'depends_on_task_id' => 'required|exists:tasks,id|different:task_id',
            'dependency_type' => 'required|in:finish-to-start,start-to-start,finish-to-finish,start-to-finish',
            'lag_days' => 'nullable|integer',
        ]);

        $task = Task::findOrFail($validated['task_id']);
        $dependsOnTask = Task::findOrFail($validated['depends_on_task_id']);

        // Verify both tasks belong to this project
        if ($task->project_id !== $project->id || $dependsOnTask->project_id !== $project->id) {
            return response()->json([
                'success' => false,
                'message' => 'Tasks must belong to the same project',
            ], 422);
        }

        // Check for circular dependencies
        if ($this->wouldCreateCircularDependency($task->id, $dependsOnTask->id)) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot create dependency: Would create circular dependency',
            ], 422);
        }

        try {
            $dependency = TaskDependency::create([
                'task_id' => $validated['task_id'],
                'depends_on_task_id' => $validated['depends_on_task_id'],
                'dependency_type' => $validated['dependency_type'],
                'lag_days' => $validated['lag_days'] ?? 0,
            ]);

            // Recalculate dates for affected tasks
            $this->recalculateTaskDates($project->id);

            return response()->json([
                'success' => true,
                'message' => 'Dependency added successfully',
                'dependency' => $dependency->load(['task', 'dependsOnTask']),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add dependency: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove dependency between tasks.
     */
    public function removeDependency(Request $request, Project $project, TaskDependency $dependency)
    {
        $user = auth()->user();
        $this->authorizeProjectAccess($user, $project);

        // Verify dependency belongs to tasks in this project
        if ($dependency->task->project_id !== $project->id) {
            abort(403, 'Dependency does not belong to this project');
        }

        try {
            $dependency->delete();

            // Recalculate dates for affected tasks
            $this->recalculateTaskDates($project->id);

            return response()->json([
                'success' => true,
                'message' => 'Dependency removed successfully',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to remove dependency: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get dependencies for a task.
     */
    public function getDependencies(Project $project, Task $task)
    {
        $user = auth()->user();
        $this->authorizeProjectAccess($user, $project);

        if ($task->project_id !== $project->id) {
            abort(403, 'Task does not belong to this project');
        }

        $dependencies = $task->dependencies()->with('dependsOnTask')->get();
        $dependents = $task->dependents()->with('task')->get();

        // Get all tasks from the project except the current task
        // Also exclude tasks that would create circular dependencies
        $availableTasks = Task::where('project_id', $project->id)
            ->where('id', '!=', $task->id)
            ->orderBy('wbs_code')
            ->get(['id', 'title', 'wbs_code'])
            ->filter(function ($availableTask) use ($task) {
                // Filter out tasks that would create circular dependency
                return !$this->wouldCreateCircularDependency($task->id, $availableTask->id);
            })
            ->values(); // Reindex array after filtering

        return response()->json([
            'success' => true,
            'task' => [
                'id' => $task->id,
                'title' => $task->title,
                'wbs_code' => $task->wbs_code,
            ],
            'dependencies' => $dependencies,
            'dependents' => $dependents,
            'available_tasks' => $availableTasks,
            'can_start' => $task->canStart(),
        ]);
    }

    /**
     * Check if adding dependency would create circular dependency.
     */
    private function wouldCreateCircularDependency($taskId, $dependsOnTaskId, $visited = [])
    {
        if (in_array($taskId, $visited)) {
            return true;
        }

        $visited[] = $taskId;

        $dependencies = TaskDependency::where('depends_on_task_id', $taskId)->pluck('task_id');

        foreach ($dependencies as $depTaskId) {
            if ($depTaskId == $dependsOnTaskId) {
                return true;
            }

            if ($this->wouldCreateCircularDependency($depTaskId, $dependsOnTaskId, $visited)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Recalculate task dates based on dependencies.
     */
    private function recalculateTaskDates($projectId)
    {
        $tasks = Task::where('project_id', $projectId)
            ->whereNull('parent_id')
            ->orderBy('order')
            ->get();

        foreach ($tasks as $task) {
            $this->calculateTaskAndChildren($task);
        }
    }

    /**
     * Recursively calculate task and its children dates.
     */
    private function calculateTaskAndChildren(Task $task)
    {
        $task->calculateDates();

        foreach ($task->children as $child) {
            $this->calculateTaskAndChildren($child);
        }
    }

    /**
     * Calculate and get critical path for the project.
     */
    public function calculateCriticalPath(Project $project)
    {
        $user = auth()->user();
        $this->authorizeProjectAccess($user, $project);

        DB::beginTransaction();
        try {
            // Get all tasks for the project
            $tasks = Task::where('project_id', $project->id)
                ->with(['dependencies.dependsOnTask', 'dependents.task'])
                ->get();

            if ($tasks->isEmpty()) {
                return response()->json([
                    'success' => true,
                    'message' => 'No tasks found',
                    'critical_path' => [],
                    'project_duration' => 0,
                ]);
            }

            // Reset all critical path fields
            Task::where('project_id', $project->id)->update([
                'early_start' => null,
                'early_finish' => null,
                'late_start' => null,
                'late_finish' => null,
                'total_float' => null,
                'is_critical' => false,
            ]);

            // Reload tasks after reset
            $tasks = Task::where('project_id', $project->id)
                ->with(['dependencies.dependsOnTask', 'dependents.task'])
                ->get();

            // Step 1: Forward Pass - Calculate Early Start and Early Finish
            // Start with tasks that have no dependencies
            $tasksWithoutDependencies = $tasks->filter(fn($task) => !$task->hasDependencies());

            foreach ($tasksWithoutDependencies as $task) {
                if ($task->estimated_duration) {
                    $task->calculateForwardPass();
                }
            }

            // Calculate for tasks with dependencies (topological order)
            $processed = $tasksWithoutDependencies->pluck('id')->toArray();
            $maxIterations = $tasks->count() * 2; // Prevent infinite loop
            $iterations = 0;

            while (count($processed) < $tasks->count() && $iterations < $maxIterations) {
                foreach ($tasks as $task) {
                    if (in_array($task->id, $processed)) {
                        continue;
                    }

                    // Check if all dependencies are processed
                    $allDepsProcessed = true;
                    foreach ($task->dependencies as $dep) {
                        if (!in_array($dep->depends_on_task_id, $processed)) {
                            $allDepsProcessed = false;
                            break;
                        }
                    }

                    if ($allDepsProcessed && $task->estimated_duration) {
                        $task->calculateForwardPass();
                        $processed[] = $task->id;
                    }
                }
                $iterations++;
            }

            // Get project finish time (maximum early finish)
            $projectFinish = Task::getProjectDuration($project->id);

            if ($projectFinish === 0) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot calculate critical path. Ensure all tasks have estimated duration.',
                ]);
            }

            // Step 2: Backward Pass - Calculate Late Start and Late Finish
            // Start with tasks that have no dependents
            $tasksWithoutDependents = $tasks->filter(fn($task) => !$task->hasDependents());

            foreach ($tasksWithoutDependents as $task) {
                $task->calculateBackwardPass($projectFinish);
            }

            // Calculate for tasks with dependents (reverse topological order)
            $processed = $tasksWithoutDependents->pluck('id')->toArray();
            $iterations = 0;

            while (count($processed) < $tasks->count() && $iterations < $maxIterations) {
                foreach ($tasks as $task) {
                    if (in_array($task->id, $processed)) {
                        continue;
                    }

                    // Check if all dependents are processed
                    $allDepsProcessed = true;
                    foreach ($task->dependents as $dep) {
                        if (!in_array($dep->task_id, $processed)) {
                            $allDepsProcessed = false;
                            break;
                        }
                    }

                    if ($allDepsProcessed) {
                        $task->calculateBackwardPass($projectFinish);
                        $processed[] = $task->id;
                    }
                }
                $iterations++;
            }

            DB::commit();

            // Get critical path tasks
            $criticalPath = Task::where('project_id', $project->id)
                ->where('is_critical', true)
                ->orderBy('early_start')
                ->with(['assignee'])
                ->get()
                ->map(function ($task) {
                    return [
                        'id' => $task->id,
                        'wbs_code' => $task->wbs_code,
                        'title' => $task->title,
                        'estimated_duration' => $task->estimated_duration,
                        'early_start' => $task->early_start,
                        'early_finish' => $task->early_finish,
                        'late_start' => $task->late_start,
                        'late_finish' => $task->late_finish,
                        'total_float' => $task->total_float,
                        'assignee' => $task->assignee ? $task->assignee->name : 'Unassigned',
                    ];
                });

            DB::commit();

            return redirect()->route('projects.wbs.critical-path', $project)
                ->with('success', 'Critical path calculated successfully! Found ' . $criticalPath->count() . ' critical tasks with project duration of ' . $projectFinish . ' days.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('projects.wbs.critical-path', $project)
                ->with('error', 'Failed to calculate critical path: ' . $e->getMessage());
        }
    }

    /**
     * Get critical path view.
     */
    public function showCriticalPath(Project $project)
    {
        $user = auth()->user();
        $this->authorizeProjectAccess($user, $project);

        // Get all tasks with critical path data
        $tasks = Task::where('project_id', $project->id)
            ->with(['assignee', 'dependencies.dependsOnTask', 'dependents.task'])
            ->orderBy('early_start')
            ->get();

        // Get critical path tasks
        $criticalPathTasks = $tasks->where('is_critical', true);

        // Calculate project duration (max late finish)
        $projectDuration = $tasks->where('late_finish', '!=', null)->max('late_finish') ?? 0;

        return view('pages.wbs.critical-path', [
            'project' => $project,
            'tasks' => $tasks,
            'criticalPath' => $criticalPathTasks->map(function ($task) {
                return [
                    'id' => $task->id,
                    'wbs_code' => $task->wbs_code,
                    'title' => $task->title,
                    'estimated_duration' => $task->estimated_duration,
                    'early_start' => $task->early_start,
                    'early_finish' => $task->early_finish,
                    'late_start' => $task->late_start,
                    'late_finish' => $task->late_finish,
                    'total_float' => $task->total_float,
                    'assignee' => $task->assignee ? $task->assignee->name : 'Unassigned',
                ];
            }),
            'projectDuration' => $projectDuration,
        ]);
    }
}

