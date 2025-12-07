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

        // Get root level tasks only (lazy loading implementation)
        $tasks = Task::rootTasks($project->id);
        $tasks->load(['assignee']); // Don't eager load children, use lazy loading instead

        // Get all project members for assignment dropdown
        $members = $project->members;

        return view('pages.wbs.index', compact('project', 'tasks', 'members'));
    }

    /**
     * Get children tasks for lazy loading (AJAX).
     */
    public function getChildren(Project $project, Task $task)
    {
        $user = auth()->user();
        $this->authorizeProjectAccess($user, $project);

        if ($task->project_id !== $project->id) {
            abort(403, 'Task does not belong to this project');
        }

        $children = $task->children()->with(['assignee'])->orderBy('order')->get();

        $html = view('pages.wbs.children-list', [
            'tasks' => $children,
            'level' => $task->level + 1
        ])->render();

        return response()->json([
            'success' => true,
            'html' => $html,
            'count' => $children->count()
        ]);
    }

    /**
     * Show Gantt chart view for the project.
     */
    public function showGantt(Project $project)
    {
        $user = auth()->user();
        $this->authorizeProjectAccess($user, $project);

        // Get all tasks with start and end dates
        $tasks = Task::where('project_id', $project->id)
            ->orderBy('order')
            ->get();

        // Format tasks for Gantt chart
        $ganttTasks = $tasks->map(function ($task) {
            // Build dependencies string (comma-separated WBS codes)
            $dependencies = $task->dependencies()
                ->with('dependsOnTask')
                ->get()
                ->filter(function($dep) {
                    return $dep->dependency_type === 'finish-to-start';
                })
                ->pluck('dependsOnTask.wbs_code')
                ->join(',');

            return [
                'id' => $task->id,
                'wbs_code' => $task->wbs_code,
                'title' => $task->wbs_code . ' ' . $task->title,
                'start_date' => $task->start_date ?? now()->format('Y-m-d'),
                'end_date' => $task->end_date ?? now()->addDays(7)->format('Y-m-d'),
                'status' => $task->status,
                'dependencies' => $dependencies,
            ];
        });

        // Get critical path task IDs if available
        $criticalPathIds = [];
        $criticalPathData = $project->tasks()
            ->whereNotNull('early_start')
            ->get();

        foreach ($criticalPathData as $task) {
            if ($task->early_start == $task->late_start &&
                $task->early_finish == $task->late_finish) {
                $criticalPathIds[] = $task->id;
            }
        }

        return view('pages.wbs.gantt', compact('project', 'ganttTasks', 'criticalPathIds'));
    }

    /**
     * Get weight distribution across timeline.
     */
    public function getWeightTimeline(Project $project)
    {
        $user = auth()->user();
        $this->authorizeProjectAccess($user, $project);

        $tasks = Task::where('project_id', $project->id)
            ->whereNotNull('due_date')
            ->where('weight', '>', 0)
            ->orderBy('due_date')
            ->get();

        // Group by month
        $timeline = [];
        $cumulativeWeight = 0;

        foreach ($tasks as $task) {
            $month = $task->due_date->format('Y-m');

            if (!isset($timeline[$month])) {
                $timeline[$month] = [
                    'month' => $task->due_date->format('M Y'),
                    'total_weight' => 0,
                    'cumulative_weight' => 0,
                    'tasks' => [],
                ];
            }

            $timeline[$month]['total_weight'] += $task->weight;
            $timeline[$month]['tasks'][] = [
                'id' => $task->id,
                'wbs_code' => $task->wbs_code,
                'title' => $task->title,
                'weight' => $task->weight,
                'status' => $task->status,
            ];
        }

        // Calculate cumulative
        foreach ($timeline as $month => &$data) {
            $cumulativeWeight += $data['total_weight'];
            $data['cumulative_weight'] = round($cumulativeWeight, 2);
        }

        return response()->json([
            'success' => true,
            'timeline' => array_values($timeline),
        ]);
    }

    /**
     * Get weight distribution by status.
     */
    public function getWeightByStatus(Project $project)
    {
        $user = auth()->user();
        $this->authorizeProjectAccess($user, $project);

        $distribution = Task::where('project_id', $project->id)
            ->where('weight', '>', 0)
            ->selectRaw('status, SUM(weight) as total_weight, COUNT(*) as task_count')
            ->groupBy('status')
            ->get();

        $totalWeight = $distribution->sum('total_weight');

        $result = $distribution->map(function ($item) use ($totalWeight) {
            return [
                'status' => $item->status,
                'weight' => round($item->total_weight, 2),
                'percentage' => $totalWeight > 0 ? round(($item->total_weight / $totalWeight) * 100, 2) : 0,
                'task_count' => $item->task_count,
            ];
        });

        return response()->json([
            'success' => true,
            'distribution' => $result,
            'total_weight' => round($totalWeight, 2),
        ]);
    }

    /**
     * Update task weight.
     */
    public function updateWeight(Request $request, Project $project, Task $task)
    {
        $user = auth()->user();
        $this->authorizeProjectAccess($user, $project);

        if ($task->project_id !== $project->id) {
            abort(403, 'Task does not belong to this project');
        }

        $validated = $request->validate([
            'weight' => 'required|numeric|min:0|max:100',
        ]);

        try {
            $task->update(['weight' => $validated['weight']]);

            // Recalculate weight percentages for siblings
            $this->recalculateWeightPercentages($project->id, $task->parent_id);

            // Get validation status
            $validation = $this->validateWeightGroup($project->id, $task->parent_id);

            return response()->json([
                'success' => true,
                'message' => 'Weight updated successfully',
                'task' => $task->fresh(),
                'validation' => $validation,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update weight: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Auto-distribute weight equally among siblings.
     */
    public function autoDistributeWeight(Request $request, Project $project)
    {
        $user = auth()->user();
        $this->authorizeProjectAccess($user, $project);

        $validated = $request->validate([
            'parent_id' => 'nullable|exists:tasks,id',
        ]);

        DB::beginTransaction();
        try {
            $parentId = $validated['parent_id'] ?? null;

            // Get all siblings
            $allSiblings = Task::where('project_id', $project->id)
                ->where('parent_id', $parentId)
                ->get();

            // Get locked and unlocked tasks
            $lockedTasks = $allSiblings->where('is_weight_locked', true);
            $unlockedTasks = $allSiblings->where('is_weight_locked', false);

            if ($unlockedTasks->isEmpty()) {
                throw new \Exception('No unlocked tasks to distribute weight');
            }

            // Calculate remaining weight after locked tasks
            $lockedWeight = $lockedTasks->sum('weight');
            $remainingWeight = 100 - $lockedWeight;

            if ($remainingWeight < 0) {
                throw new \Exception('Locked tasks already exceed 100%. Please adjust locked weights first.');
            }

            if ($remainingWeight == 0) {
                throw new \Exception('No remaining weight to distribute. Locked tasks total 100%.');
            }

            // Distribute remaining weight among unlocked tasks
            $taskCount = $unlockedTasks->count();
            $baseWeight = floor(($remainingWeight / $taskCount) * 100) / 100; // Round down
            $totalAssigned = $baseWeight * $taskCount;
            $remainder = round($remainingWeight - $totalAssigned, 2);

            // Distribute base weight to all unlocked tasks
            $index = 0;
            foreach ($unlockedTasks as $task) {
                // Give remainder to last task to ensure exactly 100% total
                $weight = ($index === $taskCount - 1) ? round($baseWeight + $remainder, 2) : $baseWeight;
                $task->update(['weight' => $weight]);
                $index++;
            }

            $this->recalculateWeightPercentages($project->id, $parentId);

            // Get validation status
            $validation = $this->validateWeightGroup($project->id, $parentId);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Weight distributed successfully',
                'validation' => $validation,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to distribute weight: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Lock/unlock task weight.
     */
    public function toggleWeightLock(Request $request, Project $project, Task $task)
    {
        $user = auth()->user();
        $this->authorizeProjectAccess($user, $project);

        if ($task->project_id !== $project->id) {
            abort(403, 'Task does not belong to this project');
        }

        try {
            $task->update(['is_weight_locked' => !$task->is_weight_locked]);

            return response()->json([
                'success' => true,
                'message' => 'Weight lock toggled successfully',
                'task' => $task->fresh(),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to toggle lock: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get weight distribution summary.
     */
    public function getWeightSummary(Project $project)
    {
        $user = auth()->user();
        $this->authorizeProjectAccess($user, $project);

        $tasks = Task::where('project_id', $project->id)->with('parent')->get();

        // Group by parent
        $summary = $tasks->groupBy('parent_id')->map(function ($siblings, $parentId) {
            $totalWeight = $siblings->sum('weight');
            $isValid = abs($totalWeight - 100) < 0.01; // Allow 0.01 tolerance

            // Get parent info
            $parent = $siblings->first()->parent;
            $parentInfo = $parent ? [
                'id' => $parent->id,
                'wbs_code' => $parent->wbs_code,
                'title' => $parent->title,
            ] : null;

            return [
                'parent' => $parentInfo,
                'total_weight' => round($totalWeight, 2),
                'is_valid' => $isValid,
                'task_count' => $siblings->count(),
                'tasks' => $siblings->map(function ($task) {
                    return [
                        'id' => $task->id,
                        'wbs_code' => $task->wbs_code,
                        'title' => $task->title,
                        'weight' => $task->weight,
                        'weight_percentage' => $task->weight_percentage,
                        'is_locked' => $task->is_weight_locked,
                    ];
                }),
            ];
        });

        return response()->json([
            'success' => true,
            'summary' => $summary,
        ]);
    }

    /**
     * Recalculate weight percentages for siblings.
     */
    private function recalculateWeightPercentages($projectId, $parentId)
    {
        $siblings = Task::where('project_id', $projectId)
            ->where('parent_id', $parentId)
            ->get();

        $totalWeight = $siblings->sum('weight');

        foreach ($siblings as $task) {
            $percentage = $totalWeight > 0 ? round(($task->weight / $totalWeight) * 100, 2) : 0;
            $task->update(['weight_percentage' => $percentage]);
        }
    }

    /**
     * Validate weight group totals.
     */
    private function validateWeightGroup($projectId, $parentId)
    {
        $siblings = Task::where('project_id', $projectId)
            ->where('parent_id', $parentId)
            ->get();

        $totalWeight = $siblings->sum('weight');
        $isValid = abs($totalWeight - 100) < 0.01;
        $difference = round(100 - $totalWeight, 2);

        return [
            'is_valid' => $isValid,
            'total_weight' => round($totalWeight, 2),
            'difference' => $difference,
            'task_count' => $siblings->count(),
            'locked_count' => $siblings->where('is_weight_locked', true)->count(),
            'status' => $this->getValidationStatus($totalWeight),
        ];
    }

    /**
     * Get validation status message.
     */
    private function getValidationStatus($totalWeight)
    {
        if (abs($totalWeight - 100) < 0.01) {
            return 'perfect';
        } elseif ($totalWeight > 100) {
            return 'over';
        } elseif ($totalWeight < 100) {
            return 'under';
        }
        return 'unknown';
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
     * Bulk update task status.
     */
    public function bulkUpdate(Request $request, Project $project)
    {
        $user = auth()->user();
        $this->authorizeProjectAccess($user, $project);

        $validated = $request->validate([
            'task_ids' => 'required|array',
            'task_ids.*' => 'required|integer|exists:tasks,id',
            'status' => 'required|in:todo,in-progress,review,completed',
        ]);

        DB::beginTransaction();
        try {
            $tasks = Task::whereIn('id', $validated['task_ids'])
                ->where('project_id', $project->id)
                ->get();

            if ($tasks->count() !== count($validated['task_ids'])) {
                throw new \Exception('Some tasks do not belong to this project');
            }

            foreach ($tasks as $task) {
                $task->update(['status' => $validated['status']]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Tasks updated successfully',
                'count' => $tasks->count(),
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to update tasks: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Bulk assign tasks to user.
     */
    public function bulkAssign(Request $request, Project $project)
    {
        $user = auth()->user();
        $this->authorizeProjectAccess($user, $project);

        $validated = $request->validate([
            'task_ids' => 'required|array',
            'task_ids.*' => 'required|integer|exists:tasks,id',
            'assigned_to' => 'required|integer|exists:users,id',
        ]);

        DB::beginTransaction();
        try {
            $tasks = Task::whereIn('id', $validated['task_ids'])
                ->where('project_id', $project->id)
                ->get();

            if ($tasks->count() !== count($validated['task_ids'])) {
                throw new \Exception('Some tasks do not belong to this project');
            }

            // Verify assigned user is a project member
            $assignee = User::findOrFail($validated['assigned_to']);
            if (!$project->members->contains($assignee)) {
                throw new \Exception('User is not a project member');
            }

            foreach ($tasks as $task) {
                $task->update(['assigned_to' => $validated['assigned_to']]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Tasks assigned successfully',
                'count' => $tasks->count(),
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to assign tasks: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Bulk delete tasks.
     */
    public function bulkDelete(Request $request, Project $project)
    {
        $user = auth()->user();
        $this->authorizeProjectAccess($user, $project);

        $validated = $request->validate([
            'task_ids' => 'required|array',
            'task_ids.*' => 'required|integer|exists:tasks,id',
        ]);

        DB::beginTransaction();
        try {
            $tasks = Task::whereIn('id', $validated['task_ids'])
                ->where('project_id', $project->id)
                ->get();

            if ($tasks->count() !== count($validated['task_ids'])) {
                throw new \Exception('Some tasks do not belong to this project');
            }

            // Group tasks by parent_id for reordering
            $parentsToReorder = [];
            foreach ($tasks as $task) {
                $parentsToReorder[$task->parent_id] = true;
                $task->delete();
            }

            // Reorder siblings for each affected parent
            foreach (array_keys($parentsToReorder) as $parentId) {
                $this->reorderSiblings($project->id, $parentId);
            }

            // Regenerate WBS codes
            $this->regenerateWbsCodes($project);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Tasks deleted successfully',
                'count' => $tasks->count(),
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete tasks: ' . $e->getMessage(),
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
     * Uses queue for large projects (>50 tasks).
     */
    private function regenerateWbsCodes(Project $project)
    {
        $taskCount = Task::where('project_id', $project->id)->count();

        // For large projects, use queue to avoid timeout
        if ($taskCount > 50) {
            \App\Jobs\RegenerateWbsCodesJob::dispatch($project->id);
            return;
        }

        // For small projects, regenerate synchronously
        $rootTasks = Task::rootTasks($project->id);

        foreach ($rootTasks as $task) {
            $task->updateWbsCode();
        }
    }

    /**
     * Save current WBS structure as a template.
     */
    public function saveTemplate(Request $request, Project $project)
    {
        $user = auth()->user();
        $this->authorizeProjectAccess($user, $project);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        try {
            $tasks = Task::where('project_id', $project->id)
                ->orderBy('parent_id')
                ->orderBy('order')
                ->get();

            $structure = $this->buildTemplateStructure($tasks);

            $template = \App\Models\WbsTemplate::create([
                'name' => $validated['name'],
                'description' => $validated['description'] ?? null,
                'user_id' => $user->id,
                'project_id' => $project->id,
                'structure' => $structure,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Template saved successfully',
                'template' => $template,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to save template: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Load a template and apply it to the project.
     */
    public function loadTemplate(Request $request, Project $project)
    {
        $user = auth()->user();
        $this->authorizeProjectAccess($user, $project);

        $validated = $request->validate([
            'template_id' => 'required|exists:wbs_templates,id',
        ]);

        DB::beginTransaction();
        try {
            $template = \App\Models\WbsTemplate::findOrFail($validated['template_id']);

            if ($template->user_id !== $user->id && $template->project_id !== null) {
                throw new \Exception('You do not have permission to use this template');
            }

            Task::where('project_id', $project->id)->delete();

            $this->createTasksFromTemplate($project->id, $template->structure, null);

            $this->regenerateWbsCodes($project);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Template loaded successfully',
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to load template: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * List all available templates for the user.
     */
    public function listTemplates(Request $request, Project $project)
    {
        $user = auth()->user();
        $this->authorizeProjectAccess($user, $project);

        $templates = \App\Models\WbsTemplate::where('user_id', $user->id)
            ->orWhereNull('project_id')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'templates' => $templates,
        ]);
    }

    /**
     * Delete a template.
     */
    public function deleteTemplate(Request $request, Project $project, $templateId)
    {
        $user = auth()->user();
        $this->authorizeProjectAccess($user, $project);

        try {
            $template = \App\Models\WbsTemplate::findOrFail($templateId);

            if ($template->user_id !== $user->id) {
                throw new \Exception('You do not have permission to delete this template');
            }

            $template->delete();

            return response()->json([
                'success' => true,
                'message' => 'Template deleted successfully',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete template: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Build template structure from tasks.
     */
    private function buildTemplateStructure($tasks, $parentId = null)
    {
        $result = [];

        foreach ($tasks as $task) {
            if ($task->parent_id === $parentId) {
                $taskData = [
                    'title' => $task->title,
                    'description' => $task->description,
                    'status' => $task->status,
                    'priority' => $task->priority,
                    'estimated_hours' => $task->estimated_hours,
                    'children' => $this->buildTemplateStructure($tasks, $task->id),
                ];
                $result[] = $taskData;
            }
        }

        return $result;
    }

    /**
     * Create tasks from template structure.
     */
    private function createTasksFromTemplate($projectId, $structure, $parentId, $order = 0)
    {
        foreach ($structure as $index => $taskData) {
            $task = Task::create([
                'project_id' => $projectId,
                'parent_id' => $parentId,
                'title' => $taskData['title'],
                'description' => $taskData['description'] ?? null,
                'status' => $taskData['status'] ?? 'todo',
                'priority' => $taskData['priority'] ?? 'medium',
                'estimated_hours' => $taskData['estimated_hours'] ?? null,
                'order' => $order + $index,
                'level' => $parentId ? Task::find($parentId)->level + 1 : 0,
                'wbs_code' => '',
            ]);

            if (!empty($taskData['children'])) {
                $this->createTasksFromTemplate($projectId, $taskData['children'], $task->id);
            }
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

