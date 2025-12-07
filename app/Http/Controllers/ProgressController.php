<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use App\Models\TaskProgress;
use App\Models\WeeklyPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProgressController extends Controller
{
    /**
     * Show weekly progress tracking view
     */
    public function index(Request $request, Project $project)
    {
        $user = auth()->user();
        $this->authorizeProjectAccess($user, $project);

        // Get week from parameter or use current week
        if ($request->has('week_start')) {
            $currentWeekStart = Carbon::parse($request->get('week_start'))->startOfWeek();
        } else {
            $currentWeekStart = now()->startOfWeek();
        }
        $currentWeekEnd = $currentWeekStart->copy()->endOfWeek();

        // Get or create weekly plan for current week
        $weeklyPlan = WeeklyPlan::forProject($project->id)
                        ->where('week_start_date', $currentWeekStart)
                        ->first();

        if (!$weeklyPlan) {
            $weeklyPlan = WeeklyPlan::createFromDate(now(), $project->id, $user->id);
        }

        // Get tasks with progress for current week
        $tasks = Task::where('project_id', $project->id)
                    ->with(['latestProgress', 'assignee'])
                    ->whereNotNull('estimated_duration')
                    ->get();

        // Get progress entries for current week
        $progressEntries = TaskProgress::whereIn('task_id', $tasks->pluck('id'))
                            ->forWeek($currentWeekStart)
                            ->with(['task', 'updatedBy'])
                            ->get();

        // Calculate summary
        $summary = $this->calculateWeeklySummary($project, $currentWeekStart);

        return view('pages.progress.index', compact(
            'project',
            'weeklyPlan',
            'tasks',
            'progressEntries',
            'summary',
            'currentWeekStart',
            'currentWeekEnd'
        ));
    }

    /**
     * Get weekly plan view
     */
    public function getWeeklyPlan(Request $request, Project $project)
    {
        $this->authorizeProjectAccess(auth()->user(), $project);

        $weekStart = $request->get('week_start', now()->startOfWeek()->format('Y-m-d'));
        $weekStartDate = Carbon::parse($weekStart);

        $weeklyPlan = WeeklyPlan::forProject($project->id)
                        ->where('week_start_date', $weekStartDate)
                        ->first();

        if (!$weeklyPlan) {
            $weeklyPlan = WeeklyPlan::createFromDate($weekStartDate, $project->id, auth()->id());
        }

        return response()->json([
            'success' => true,
            'plan' => $weeklyPlan,
        ]);
    }

    /**
     * Update weekly plan
     */
    public function updateWeeklyPlan(Request $request, Project $project, WeeklyPlan $plan)
    {
        $this->authorizeProjectAccess(auth()->user(), $project);

        // Verify plan belongs to project
        if ($plan->project_id !== $project->id) {
            return response()->json([
                'success' => false,
                'message' => 'Plan does not belong to this project',
            ], 403);
        }

        $validated = $request->validate([
            'objectives' => 'nullable|string',
            'key_activities' => 'nullable|string',
            'planned_weight_total' => 'nullable|numeric|min:0',
        ]);

        $plan->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Weekly plan updated successfully',
            'plan' => $plan,
        ]);
    }

    /**
     * Update task progress
     */
    public function updateProgress(Request $request, Project $project, Task $task)
    {
        $user = auth()->user();
        $this->authorizeProjectAccess($user, $project);

        // Verify task belongs to project
        if ($task->project_id !== $project->id) {
            return response()->json([
                'success' => false,
                'message' => 'Task does not belong to this project',
            ], 403);
        }

        $validated = $request->validate([
            'week_start_date' => 'required|date',
            'progress_percentage' => 'required|numeric|min:0|max:100',
            'planned_percentage' => 'nullable|numeric|min:0|max:100',
            'actual_percentage' => 'nullable|numeric|min:0|max:100',
            'actual_start_date' => 'nullable|date',
            'actual_end_date' => 'nullable|date|after_or_equal:actual_start_date',
            'notes' => 'nullable|string',
            'issues' => 'nullable|string',
            'proposed_solutions' => 'nullable|string',
        ]);

        $weekStartDate = Carbon::parse($validated['week_start_date']);
        $weekEndDate = $weekStartDate->copy()->endOfWeek();

        DB::beginTransaction();
        try {
            // Find or create progress entry
            $progress = TaskProgress::where('task_id', $task->id)
                            ->where('week_start_date', $weekStartDate)
                            ->first();

            if (!$progress) {
                $progress = new TaskProgress([
                    'task_id' => $task->id,
                    'week_start_date' => $weekStartDate,
                    'week_end_date' => $weekEndDate,
                ]);
            }

            // Update fields
            $progress->fill($validated);
            $progress->updated_by = auth()->id();

            // Calculate weight progress
            if ($task->weight) {
                $progress->planned_weight = ($validated['planned_percentage'] ?? 0) * $task->weight / 100;
                $progress->actual_weight = ($validated['actual_percentage'] ?? 0) * $task->weight / 100;
            }

            // Calculate deviation and status
            $progress->calculateDeviation();
            $progress->determineStatus();

            $progress->save();

            // Update task status if completed
            if ($progress->progress_percentage >= 100 && $task->status !== 'completed') {
                $task->update([
                    'status' => 'completed',
                    'completed_at' => now(),
                ]);
            }

            // Update weekly plan totals
            $this->updateWeeklyPlanTotals($project, $weekStartDate);

            // Check for deviation alerts
            if ($progress->isBehindSchedule()) {
                $this->sendDeviationAlert($project, $task, $progress);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Progress updated successfully',
                'progress' => $progress->load(['task', 'updatedBy']),
                'deviation_alert' => $progress->isBehindSchedule(),
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to update progress: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get progress for specific task and week
     */
    public function getTaskProgress(Request $request, Project $project, Task $task)
    {
        $this->authorizeProjectAccess(auth()->user(), $project);

        // Verify task belongs to project
        if ($task->project_id !== $project->id) {
            return response()->json([
                'success' => false,
                'message' => 'Task does not belong to this project',
            ], 403);
        }

        $weekStart = $request->get('week_start', now()->startOfWeek()->format('Y-m-d'));
        $weekStartDate = Carbon::parse($weekStart);

        $progress = TaskProgress::where('task_id', $task->id)
                        ->where('week_start_date', $weekStartDate)
                        ->with(['updatedBy'])
                        ->first();

        return response()->json([
            'success' => true,
            'progress' => $progress,
            'task' => $task->load(['assignee']),
        ]);
    }

    /**
     * Get weekly summary
     */
    public function getWeeklySummary(Request $request, Project $project)
    {
        $this->authorizeProjectAccess(auth()->user(), $project);

        $weekStart = $request->get('week_start', now()->startOfWeek()->format('Y-m-d'));
        $weekStartDate = Carbon::parse($weekStart);

        $summary = $this->calculateWeeklySummary($project, $weekStartDate);

        return response()->json([
            'success' => true,
            'summary' => $summary,
        ]);
    }

    /**
     * Get deviation alerts
     */
    public function getDeviationAlerts(Project $project)
    {
        $this->authorizeProjectAccess(auth()->user(), $project);

        $weekStartDate = now()->startOfWeek();

        $alerts = TaskProgress::whereHas('task', function($q) use ($project) {
                        $q->where('project_id', $project->id);
                    })
                    ->forWeek($weekStartDate)
                    ->behindSchedule()
                    ->with(['task.assignee', 'updatedBy'])
                    ->get();

        return response()->json([
            'success' => true,
            'alerts' => $alerts,
            'count' => $alerts->count(),
        ]);
    }

    /**
     * Show weekly report view
     */
    public function showReport(Request $request, Project $project)
    {
        $this->authorizeProjectAccess(auth()->user(), $project);

        // Get week from parameter or use current week
        if ($request->has('week_start')) {
            $weekStartDate = Carbon::parse($request->get('week_start'))->startOfWeek();
        } else {
            $weekStartDate = now()->startOfWeek();
        }
        $weekEndDate = $weekStartDate->copy()->endOfWeek();

        // Get weekly plan
        $weeklyPlan = WeeklyPlan::forProject($project->id)
                        ->where('week_start_date', $weekStartDate)
                        ->first();

        // Get all progress entries for the week
        $progressEntries = TaskProgress::whereHas('task', function($q) use ($project) {
                                $q->where('project_id', $project->id);
                            })
                            ->forWeek($weekStartDate)
                            ->with(['task.assignee', 'updatedBy'])
                            ->get();

        // Get summary
        $summary = $this->calculateWeeklySummary($project, $weekStartDate);

        // Get major problems and solutions
        $problems = $progressEntries->filter(function($entry) {
            return !empty($entry->issues);
        })->map(function($entry) {
            return [
                'task' => $entry->task->title,
                'issue' => $entry->issues
            ];
        })->values();

        $solutions = $progressEntries->filter(function($entry) {
            return !empty($entry->proposed_solutions);
        })->map(function($entry) {
            return [
                'task' => $entry->task->title,
                'solution' => $entry->proposed_solutions
            ];
        })->values();

        return view('pages.progress.report', compact(
            'project',
            'weeklyPlan',
            'summary',
            'problems',
            'solutions',
            'weekStartDate',
            'weekEndDate'
        ))->with('taskProgress', $progressEntries);
    }

    /**
     * Export weekly report to Excel
     */
    public function exportExcel(Request $request, Project $project)
    {
        $this->authorizeProjectAccess(auth()->user(), $project);

        $weekStart = $request->get('week_start', now()->startOfWeek()->format('Y-m-d'));
        $weekStartDate = Carbon::parse($weekStart);

        $summary = $this->calculateWeeklySummary($project, $weekStartDate);

        $fileName = 'weekly_report_' . $project->slug . '_week' . $weekStartDate->weekOfYear . '_' . $weekStartDate->year . '.xlsx';

        return \Excel::download(
            new \App\Exports\WeeklyProgressReportExport($project, $weekStartDate, $summary),
            $fileName
        );
    }

    /**
     * Export weekly report to PDF
     */
    public function exportPdf(Request $request, Project $project)
    {
        $this->authorizeProjectAccess(auth()->user(), $project);

        $weekStart = $request->get('week_start', now()->startOfWeek()->format('Y-m-d'));
        $weekStartDate = Carbon::parse($weekStart);
        $weekEndDate = $weekStartDate->copy()->endOfWeek();

        // Get weekly plan
        $weeklyPlan = WeeklyPlan::forProject($project->id)
                        ->where('week_start_date', $weekStartDate)
                        ->first();

        // Get all progress entries
        $progressEntries = TaskProgress::whereHas('task', function($q) use ($project) {
                                $q->where('project_id', $project->id);
                            })
                            ->forWeek($weekStartDate)
                            ->with(['task.assignee', 'updatedBy'])
                            ->get();

        // Get summary
        $summary = $this->calculateWeeklySummary($project, $weekStartDate);

        // Get problems and solutions
        $problems = $progressEntries->filter(function($entry) {
            return !empty($entry->issues);
        })->map(function($entry) {
            return $entry->issues;
        })->values();

        $solutions = $progressEntries->filter(function($entry) {
            return !empty($entry->proposed_solutions);
        })->map(function($entry) {
            return $entry->proposed_solutions;
        })->values();

        $pdf = \PDF::loadView('pages.progress.report-pdf', compact(
            'project',
            'weeklyPlan',
            'progressEntries',
            'summary',
            'problems',
            'solutions',
            'weekStartDate',
            'weekEndDate'
        ));

        $fileName = 'weekly_report_' . $project->slug . '_week' . $weekStartDate->weekOfYear . '_' . $weekStartDate->year . '.pdf';

        return $pdf->download($fileName);
    }

    /**
     * Calculate weekly summary for reporting
     */
    private function calculateWeeklySummary(Project $project, Carbon $weekStartDate)
    {
        $progressEntries = TaskProgress::whereHas('task', function($q) use ($project) {
                                $q->where('project_id', $project->id);
                            })
                            ->forWeek($weekStartDate)
                            ->get();

        $totalPlannedWeight = $progressEntries->sum('planned_weight');
        $totalActualWeight = $progressEntries->sum('actual_weight');
        $deviationWeight = $totalPlannedWeight - $totalActualWeight;

        $onTrack = $progressEntries->where('status', 'on-track')->count();
        $atRisk = $progressEntries->where('status', 'at-risk')->count();
        $delayed = $progressEntries->where('status', 'delayed')->count();
        $completed = $progressEntries->where('status', 'completed')->count();

        return [
            'total_tasks' => $progressEntries->count(),
            'planned_weight' => round($totalPlannedWeight, 2),
            'actual_weight' => round($totalActualWeight, 2),
            'deviation_weight' => round($deviationWeight, 2),
            'completion_rate' => $totalPlannedWeight > 0
                ? round(($totalActualWeight / $totalPlannedWeight) * 100, 2)
                : 0,
            'on_track' => $onTrack,
            'at_risk' => $atRisk,
            'delayed' => $delayed,
            'completed' => $completed,
            'avg_progress' => round($progressEntries->avg('progress_percentage'), 2),
        ];
    }

    /**
     * Update weekly plan totals
     */
    private function updateWeeklyPlanTotals(Project $project, Carbon $weekStartDate)
    {
        $weeklyPlan = WeeklyPlan::forProject($project->id)
                        ->where('week_start_date', $weekStartDate)
                        ->first();

        if ($weeklyPlan) {
            $progressEntries = TaskProgress::whereHas('task', function($q) use ($project) {
                                    $q->where('project_id', $project->id);
                                })
                                ->forWeek($weekStartDate)
                                ->get();

            $weeklyPlan->actual_weight_total = $progressEntries->sum('actual_weight');
            $weeklyPlan->save();
        }
    }

    /**
     * Send deviation alert
     */
    private function sendDeviationAlert(Project $project, Task $task, TaskProgress $progress)
    {
        // TODO: Implement notification system
        // This will be expanded in Phase 4.2 with proper notifications

        // For now, just log the deviation
        \Log::info('Deviation Alert', [
            'project' => $project->title,
            'task' => $task->title,
            'deviation_percentage' => $progress->deviation_percentage,
            'deviation_days' => $progress->deviation_days,
            'status' => $progress->status,
        ]);
    }

    /**
     * Authorize project access
     */
    private function authorizeProjectAccess($user, $project)
    {
        if (!$user) {
            abort(401, 'Unauthorized');
        }

        // Check if user is admin
        if ($user->role === 'admin') {
            return true;
        }

        // Check if user created the project
        if ($project->created_by === $user->id) {
            return true;
        }

        // Check if user is team lead of the project's team
        if ($user->role === 'team_lead' || $user->leadingTeams()->count() > 0) {
            if ($project->team) {
                $teamLeadTeams = $user->leadingTeams()->pluck('id');
                if ($teamLeadTeams->contains($project->team)) {
                    return true;
                }
            }
        }

        // Check if user is project member
        $isMember = $project->members()->where('users.id', $user->id)->exists();

        if (!$isMember) {
            abort(403, 'You do not have access to this project');
        }

        return true;
    }
}
