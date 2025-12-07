<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use App\Models\TaskProgress;
use App\Models\WeeklyPlan;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    /**
     * Show S-Curve and analytics dashboard
     */
    public function index(Request $request, Project $project)
    {
        $this->authorizeProjectAccess(auth()->user(), $project);

        // Get project date range
        $projectStart = Carbon::parse($project->start_date);
        $projectEnd = Carbon::parse($project->end_date);

        // Get S-Curve data
        $sCurveData = $this->generateSCurveData($project, $projectStart, $projectEnd);

        // Calculate performance indices
        $performanceMetrics = $this->calculatePerformanceIndices($project);

        // Get trend analysis
        $trendData = $this->getTrendAnalysis($project);

        // Get forecast
        $forecast = $this->generateForecast($project, $performanceMetrics);

        return view('pages.analytics.index', compact(
            'project',
            'sCurveData',
            'performanceMetrics',
            'trendData',
            'forecast'
        ));
    }

    /**
     * Get S-Curve data for charts
     */
    public function getSCurveData(Request $request, Project $project)
    {
        $this->authorizeProjectAccess(auth()->user(), $project);

        $projectStart = Carbon::parse($project->start_date);
        $projectEnd = Carbon::parse($project->end_date);

        $data = $this->generateSCurveData($project, $projectStart, $projectEnd);

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    /**
     * Generate S-Curve data (Planned vs Actual)
     */
    private function generateSCurveData(Project $project, Carbon $start, Carbon $end)
    {
        $weeks = [];
        $plannedCumulative = [];
        $actualCumulative = [];

        $currentWeek = $start->copy()->startOfWeek();
        $endWeek = $end->copy()->endOfWeek();
        $today = now();

        $cumulativePlanned = 0;
        $cumulativeActual = 0;

        while ($currentWeek->lte($endWeek)) {
            $weekEnd = $currentWeek->copy()->endOfWeek();

            // Get weekly plan for this week
            $weeklyPlan = WeeklyPlan::forProject($project->id)
                ->where('week_start_date', $currentWeek)
                ->first();

            // Calculate planned weight for this week
            $weekPlanned = $weeklyPlan ? $weeklyPlan->planned_weight_total : 0;

            // Calculate actual weight for this week (only for past weeks)
            $weekActual = 0;
            if ($weekEnd->lte($today)) {
                $weekActual = $weeklyPlan ? $weeklyPlan->actual_weight_total : 0;
            }

            $cumulativePlanned += $weekPlanned;
            $cumulativeActual += $weekActual;

            $weeks[] = $currentWeek->format('Y-m-d');
            $plannedCumulative[] = round($cumulativePlanned, 2);
            $actualCumulative[] = round($cumulativeActual, 2);

            $currentWeek->addWeek();
        }

        return [
            'labels' => $weeks,
            'planned' => $plannedCumulative,
            'actual' => $actualCumulative,
            'totalPlanned' => !empty($plannedCumulative) ? end($plannedCumulative) : 0,
            'totalActual' => !empty($actualCumulative) ? end($actualCumulative) : 0
        ];
    }

    /**
     * Calculate Schedule Performance Index (SPI) and Cost Performance Index (CPI)
     */
    private function calculatePerformanceIndices(Project $project)
    {
        $today = now();

        // Get all completed and in-progress tasks
        $tasks = Task::where('project_id', $project->id)->get();

        // Calculate Planned Value (PV) - should be completed by now
        $pv = 0;
        foreach ($tasks as $task) {
            if ($task->end_date && Carbon::parse($task->end_date)->lte($today)) {
                $pv += $task->weight ?? 0;
            }
        }

        // Calculate Earned Value (EV) - actually completed
        $ev = TaskProgress::whereHas('task', function($q) use ($project) {
                $q->where('project_id', $project->id);
            })
            ->where('task_progress.status', 'completed')
            ->join('tasks', 'task_progress.task_id', '=', 'tasks.id')
            ->sum('tasks.weight');

        // Calculate Actual Cost (AC) - actual work done (use actual weight total)
        $ac = WeeklyPlan::forProject($project->id)
            ->sum('actual_weight_total');

        // Budget at Completion (BAC)
        $bac = $tasks->sum('weight');

        // Calculate indices
        $spi = $pv > 0 ? round($ev / $pv, 3) : 0;
        $cpi = $ac > 0 ? round($ev / $ac, 3) : 0;

        // Calculate variances
        $sv = round($ev - $pv, 2); // Schedule Variance
        $cv = round($ev - $ac, 2); // Cost Variance

        // Estimate at Completion (EAC)
        $eac = $cpi > 0 ? round($bac / $cpi, 2) : $bac;

        // Estimate to Complete (ETC)
        $etc = round($eac - $ac, 2);

        // Variance at Completion (VAC)
        $vac = round($bac - $eac, 2);

        // Completion percentage
        $completionPercentage = $bac > 0 ? round(($ev / $bac) * 100, 2) : 0;

        return [
            'pv' => round($pv, 2),
            'ev' => round($ev, 2),
            'ac' => round($ac, 2),
            'bac' => round($bac, 2),
            'spi' => $spi,
            'cpi' => $cpi,
            'sv' => $sv,
            'cv' => $cv,
            'eac' => $eac,
            'etc' => $etc,
            'vac' => $vac,
            'completion' => $completionPercentage,
            'spi_status' => $spi >= 1 ? 'on-schedule' : ($spi >= 0.8 ? 'minor-delay' : 'major-delay'),
            'cpi_status' => $cpi >= 1 ? 'under-budget' : ($cpi >= 0.8 ? 'minor-overrun' : 'major-overrun')
        ];
    }

    /**
     * Get trend analysis data
     */
    private function getTrendAnalysis(Project $project)
    {
        // Get weekly performance over time
        $weeklyPlans = WeeklyPlan::forProject($project->id)
            ->orderBy('week_start_date')
            ->get();

        $weeks = [];
        $spiTrend = [];
        $completionTrend = [];
        $deviationTrend = [];

        foreach ($weeklyPlans as $plan) {
            $weeks[] = $plan->week_start_date->format('M d');

            // Calculate weekly SPI
            $weekSpi = $plan->planned_weight_total > 0
                ? round($plan->actual_weight_total / $plan->planned_weight_total, 3)
                : 0;
            $spiTrend[] = $weekSpi;

            // Calculate completion rate
            $completionRate = $plan->getCompletionPercentage();
            $completionTrend[] = round($completionRate, 1);

            // Calculate deviation
            $deviation = $plan->actual_weight_total - $plan->planned_weight_total;
            $deviationTrend[] = round($deviation, 2);
        }

        return [
            'labels' => $weeks,
            'spi' => $spiTrend,
            'completion' => $completionTrend,
            'deviation' => $deviationTrend
        ];
    }

    /**
     * Generate forecast based on current performance
     */
    private function generateForecast(Project $project, array $metrics)
    {
        $projectEnd = Carbon::parse($project->end_date);
        $today = now();

        $spi = $metrics['spi'];
        $completionPercentage = $metrics['completion'];

        // Calculate remaining work
        $remainingWork = 100 - $completionPercentage;

        // Forecast completion date based on SPI
        $projectStart = Carbon::parse($project->start_date);
        $originalDuration = $projectStart->diffInDays($projectEnd);
        $elapsedDays = $projectStart->diffInDays($today);
        $remainingDays = $today->diffInDays($projectEnd, false); // Can be negative if overdue

        if ($spi > 0 && $remainingWork > 0) {
            // Calculate forecast remaining days based on SPI
            $forecastRemainingDays = round($remainingDays / $spi);
            $forecastCompletionDate = $today->copy()->addDays($forecastRemainingDays);

            // Calculate delay (positive = delayed, negative = ahead)
            $delayDays = $forecastCompletionDate->diffInDays($projectEnd, false);
        } else {
            $forecastCompletionDate = null;
            $delayDays = null;
        }

        // Calculate probability of on-time completion
        $onTimeProbability = 0;
        if ($spi >= 1.0) {
            $onTimeProbability = 90;
        } elseif ($spi >= 0.9) {
            $onTimeProbability = 70;
        } elseif ($spi >= 0.8) {
            $onTimeProbability = 50;
        } elseif ($spi >= 0.7) {
            $onTimeProbability = 30;
        } else {
            $onTimeProbability = 10;
        }

        return [
            'forecast_completion_date' => $forecastCompletionDate ? $forecastCompletionDate->format('Y-m-d') : null,
            'forecast_delay_days' => $delayDays,
            'remaining_work_percentage' => round($remainingWork, 2),
            'on_time_probability' => $onTimeProbability,
            'risk_level' => $spi >= 0.9 ? 'low' : ($spi >= 0.7 ? 'medium' : 'high')
        ];
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
