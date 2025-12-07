<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Project;
use App\Models\Task;
use App\Models\TaskProgress;
use App\Models\WeeklyPlan;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function admin()
    {
        $totalUsers = User::count();
        $totalProjects = Project::count();
        $totalTasks = Task::count();
        $completedProjects = Project::where('status', 'completed')->count();

        // Get statistics for admin dashboard
        $stats = [
            'total_users' => $totalUsers,
            'active_users' => User::where('is_active', true)->count(),
            'total_projects' => $totalProjects,
            'ongoing_projects' => Project::where('status', 'ongoing')->count(),
            'completed_projects' => $completedProjects,
            'total_tasks' => $totalTasks,
            'pending_tasks' => Task::where('status', 'todo')->count(),
            'completion_rate' => $totalProjects > 0 ? round(($completedProjects / $totalProjects) * 100) : 0,
        ];

        // Get project health indicators
        $projectHealthData = $this->getProjectHealthIndicators();

        // Get progress trends
        $progressTrends = $this->getProgressTrends();

        // Get team performance metrics
        $teamPerformance = $this->getTeamPerformanceMetrics();

        // Get risk indicators
        $riskIndicators = $this->getRiskIndicators();

        // Get recent projects
        $recent_projects = Project::with('creator')
            ->latest()
            ->take(5)
            ->get();

        // Get user distribution with counts and percentages
        $teamMemberCount = User::where('user_type', 'team_member')->count();
        $teamLeadCount = User::where('user_type', 'team_lead')->count();
        $adminCount = User::where('user_type', 'admin')->count();

        $stats['team_member_count'] = $teamMemberCount;
        $stats['team_lead_count'] = $teamLeadCount;
        $stats['admin_count'] = $adminCount;

        $stats['team_member_percentage'] = $totalUsers > 0 ? round(($teamMemberCount / $totalUsers) * 100) : 0;
        $stats['team_lead_percentage'] = $totalUsers > 0 ? round(($teamLeadCount / $totalUsers) * 100) : 0;
        $stats['admin_percentage'] = $totalUsers > 0 ? round(($adminCount / $totalUsers) * 100) : 0;

        // Get recent activities (all activities for admin)
        $recent_activities = Activity::with(['user', 'project'])
            ->latest()
            ->take(15)
            ->get();

        return view('pages.admin.dashboard', compact(
            'stats',
            'recent_projects',
            'recent_activities',
            'projectHealthData',
            'progressTrends',
            'teamPerformance',
            'riskIndicators'
        ));
    }

    public function teamMember()
    {
        $user = auth()->user();

        // Get projects where user is a member
        $myProjectIds = $user->projects()->pluck('projects.id');

        // Get assigned tasks
        $myTasks = Task::where('assigned_to', $user->id);
        $activeTasks = (clone $myTasks)->whereIn('status', ['todo', 'in-progress'])->get();
        $completedTasks = (clone $myTasks)->where('status', 'completed')->count();
        $overdueTasks = (clone $myTasks)
            ->whereIn('status', ['todo', 'in-progress'])
            ->where('due_date', '<', now())
            ->count();

        // Get statistics
        $stats = [
            'my_projects' => $myProjectIds->count(),
            'active_projects' => $user->projects()->whereIn('status', ['planning', 'ongoing'])->count(),
            'active_tasks' => $activeTasks->count(),
            'overdue_tasks' => $overdueTasks,
            'completed_tasks' => $completedTasks,
            'total_tasks' => Task::where('assigned_to', $user->id)->count(),
        ];

        // Get my projects with details
        $projects = Project::whereIn('id', $myProjectIds)
            ->with(['creator', 'members'])
            ->latest()
            ->take(5)
            ->get();

        // Get upcoming deadlines
        $deadlines = Task::where('assigned_to', $user->id)
            ->whereIn('status', ['todo', 'in-progress'])
            ->whereNotNull('due_date')
            ->where('due_date', '>=', now())
            ->orderBy('due_date', 'asc')
            ->take(5)
            ->get();

        // Get recent activities from my tasks
        $activities = Task::where('assigned_to', $user->id)
            ->latest('updated_at')
            ->take(10)
            ->get();

        // Get recent activities from my projects
        $recent_activities = Activity::with(['user', 'project'])
            ->whereIn('project_id', $myProjectIds)
            ->latest()
            ->take(15)
            ->get();

        // Get team members from my projects
        $team_members = User::whereHas('projects', function($query) use ($myProjectIds) {
            $query->whereIn('projects.id', $myProjectIds);
        })
        ->where('id', '!=', $user->id)
        ->with('profile')
        ->take(10)
        ->get();

        // Get analytics for my projects
        $projectHealthData = $this->getProjectHealthIndicators($myProjectIds);
        $progressTrends = $this->getProgressTrends($myProjectIds);
        $riskIndicators = $this->getRiskIndicators($myProjectIds);

        return view('pages.team_member.dashboard', compact('stats', 'projects', 'activities', 'deadlines', 'team_members', 'recent_activities', 'projectHealthData', 'progressTrends', 'riskIndicators'));
    }

    public function teamLead()
    {
        $user = auth()->user();

        // Get teams where user is a team lead
        $myTeamIds = $user->leadingTeams->pluck('id');

        // Get projects for my teams
        $myProjects = Project::whereIn('team', $myTeamIds)->with(['members', 'tasks']);

        // Get statistics
        $stats = [
            'my_teams' => $myTeamIds->count(),
            'total_projects' => (clone $myProjects)->count(),
            'ongoing_projects' => (clone $myProjects)->where('status', 'ongoing')->count(),
            'completed_projects' => (clone $myProjects)->where('status', 'completed')->count(),
            'total_tasks' => Task::whereHas('project', function($query) use ($myTeamIds) {
                $query->whereIn('team', $myTeamIds);
            })->count(),
            'pending_tasks' => Task::whereHas('project', function($query) use ($myTeamIds) {
                $query->whereIn('team', $myTeamIds);
            })->where('status', 'todo')->count(),
        ];

        // Get projects for display
        $projects = Project::whereIn('team', $myTeamIds)
            ->with(['creator', 'members', 'teamInfo'])
            ->latest()
            ->take(6)
            ->get();

        // Get team members
        $team_members = User::whereHas('teams', function($query) use ($myTeamIds) {
            $query->whereIn('teams.id', $myTeamIds);
        })
        ->where('id', '!=', $user->id)
        ->with('profile')
        ->get();

        // Get pending/high priority tasks
        $pending_tasks = Task::whereHas('project', function($query) use ($myTeamIds) {
            $query->whereIn('team', $myTeamIds);
        })
        ->whereIn('status', ['todo', 'in-progress'])
        ->where('priority', 'critical')
        ->orWhere(function($query) use ($myTeamIds) {
            $query->whereHas('project', function($q) use ($myTeamIds) {
                $q->whereIn('team', $myTeamIds);
            })
            ->where('due_date', '<', now()->addDays(3));
        })
        ->with(['project', 'assignedTo'])
        ->orderBy('priority', 'desc')
        ->orderBy('due_date', 'asc')
        ->take(10)
        ->get();

        // Get recent activities from my teams' projects
        $recent_activities = Activity::with(['user', 'project'])
            ->whereHas('project', function($query) use ($myTeamIds) {
                $query->whereIn('team', $myTeamIds);
            })
            ->latest()
            ->take(15)
            ->get();

        // Get project IDs for my teams
        $myProjectIds = Project::whereIn('team', $myTeamIds)->pluck('id');

        // Get analytics for my team's projects
        $projectHealthData = $this->getProjectHealthIndicators($myProjectIds);
        $progressTrends = $this->getProgressTrends($myProjectIds);
        $teamPerformance = $this->getTeamPerformanceMetrics($myTeamIds);
        $riskIndicators = $this->getRiskIndicators($myProjectIds);

        return view('pages.team_lead.dashboard', compact('stats', 'projects', 'team_members', 'pending_tasks', 'recent_activities', 'projectHealthData', 'progressTrends', 'teamPerformance', 'riskIndicators'));
    }

    public function index()
    {
        // Redirect to appropriate dashboard based on user type
        $user = auth()->user();

        return match($user->user_type) {
            'admin' => redirect()->route('dashboard.admin'),
            'team_lead' => redirect()->route('dashboard.team_lead'),
            'team_member' => redirect()->route('dashboard.team_member'),
            default => redirect()->route('dashboard.team_member'),
        };
    }

    /**
     * Get project health indicators
     */
    private function getProjectHealthIndicators($projectIds = null)
    {
        $query = Project::with(['tasks', 'weeklyPlans']);

        if ($projectIds) {
            $query->whereIn('id', $projectIds);
        }

        $projects = $query->get();

        $healthyCount = 0;
        $atRiskCount = 0;
        $criticalCount = 0;
        $projectsWithHealth = [];

        foreach ($projects as $project) {
            $health = $this->calculateProjectHealth($project);

            if ($health['status'] === 'healthy') {
                $healthyCount++;
            } elseif ($health['status'] === 'at-risk') {
                $atRiskCount++;
            } else {
                $criticalCount++;
            }

            $projectsWithHealth[] = [
                'project' => $project,
                'health' => $health
            ];
        }

        // Sort by health score (worst first)
        usort($projectsWithHealth, function($a, $b) {
            return $a['health']['score'] <=> $b['health']['score'];
        });

        return [
            'healthy' => $healthyCount,
            'at_risk' => $atRiskCount,
            'critical' => $criticalCount,
            'total' => $projects->count(),
            'projects' => array_slice($projectsWithHealth, 0, 10) // Top 10 worst projects
        ];
    }

    /**
     * Calculate individual project health
     */
    private function calculateProjectHealth($project)
    {
        $score = 100;
        $issues = [];

        // Check schedule (30 points)
        if ($project->end_date) {
            $today = now();
            $endDate = Carbon::parse($project->end_date);

            if ($endDate->isPast() && $project->status !== 'completed') {
                $score -= 30;
                $issues[] = 'Overdue';
            } elseif ($endDate->diffInDays($today) <= 7 && $project->status !== 'completed') {
                $score -= 15;
                $issues[] = 'Due soon';
            }
        }

        // Check task completion (30 points)
        $totalTasks = $project->tasks->count();
        if ($totalTasks > 0) {
            $completedTasks = $project->tasks->where('status', 'completed')->count();
            $completionRate = ($completedTasks / $totalTasks) * 100;

            if ($completionRate < 30) {
                $score -= 30;
                $issues[] = 'Low completion';
            } elseif ($completionRate < 60) {
                $score -= 15;
                $issues[] = 'Below target';
            }
        }

        // Check SPI if available (25 points)
        $latestPlan = $project->weeklyPlans()->latest('week_start_date')->first();
        if ($latestPlan) {
            $spi = $latestPlan->planned_weight_total > 0
                ? $latestPlan->actual_weight_total / $latestPlan->planned_weight_total
                : 0;

            if ($spi < 0.7) {
                $score -= 25;
                $issues[] = 'Major delays';
            } elseif ($spi < 0.9) {
                $score -= 12;
                $issues[] = 'Minor delays';
            }
        }

        // Check overdue tasks (15 points)
        $overdueTasks = $project->tasks()
            ->where('status', '!=', 'completed')
            ->where('due_date', '<', now())
            ->count();

        if ($overdueTasks > 5) {
            $score -= 15;
            $issues[] = 'Many overdue tasks';
        } elseif ($overdueTasks > 0) {
            $score -= 7;
            $issues[] = 'Some overdue tasks';
        }

        // Determine status
        $status = 'healthy';
        if ($score < 50) {
            $status = 'critical';
        } elseif ($score < 75) {
            $status = 'at-risk';
        }

        return [
            'score' => max(0, $score),
            'status' => $status,
            'issues' => $issues
        ];
    }

    /**
     * Get progress trends
     */
    private function getProgressTrends($projectIds = null)
    {
        $weeks = [];
        $completionRates = [];
        $tasks = [];

        // Get last 8 weeks of data
        for ($i = 7; $i >= 0; $i--) {
            $weekStart = now()->subWeeks($i)->startOfWeek();
            $weekEnd = $weekStart->copy()->endOfWeek();

            $weeks[] = $weekStart->format('M d');

            // Get completion rate for the week
            $plansQuery = WeeklyPlan::whereBetween('week_start_date', [$weekStart, $weekEnd]);

            if ($projectIds) {
                $plansQuery->whereIn('project_id', $projectIds);
            }

            $plans = $plansQuery->get();
            $avgCompletion = $plans->avg(function($plan) {
                return $plan->planned_weight_total > 0
                    ? ($plan->actual_weight_total / $plan->planned_weight_total) * 100
                    : 0;
            });

            $completionRates[] = round($avgCompletion ?? 0, 1);

            // Get task completion count
            $completedTasksQuery = TaskProgress::whereBetween('created_at', [$weekStart, $weekEnd])
                ->where('status', 'completed');

            $totalTasksQuery = TaskProgress::whereBetween('created_at', [$weekStart, $weekEnd]);

            if ($projectIds) {
                $completedTasksQuery->whereHas('task', function($q) use ($projectIds) {
                    $q->whereIn('project_id', $projectIds);
                });
                $totalTasksQuery->whereHas('task', function($q) use ($projectIds) {
                    $q->whereIn('project_id', $projectIds);
                });
            }

            $completedTasks = $completedTasksQuery->count();
            $totalTasks = $totalTasksQuery->count();

            $tasks[] = [
                'completed' => $completedTasks,
                'total' => $totalTasks
            ];
        }

        // Calculate prediction (simple linear regression)
        $prediction = $this->predictNextWeek($completionRates);

        return [
            'weeks' => $weeks,
            'completion_rates' => $completionRates,
            'tasks' => $tasks,
            'prediction' => $prediction,
            'trend' => $this->calculateTrend($completionRates)
        ];
    }

    /**
     * Get team performance metrics
     */
    private function getTeamPerformanceMetrics($teamIds = null)
    {
        $query = DB::table('teams')
            ->leftJoin('projects', 'teams.id', '=', 'projects.team')
            ->leftJoin('tasks', 'projects.id', '=', 'tasks.project_id')
            ->select(
                'teams.id',
                'teams.name',
                DB::raw('COUNT(DISTINCT projects.id) as project_count'),
                DB::raw('COUNT(tasks.id) as total_tasks'),
                DB::raw('SUM(CASE WHEN tasks.status = "completed" THEN 1 ELSE 0 END) as completed_tasks')
            );

        if ($teamIds) {
            $query->whereIn('teams.id', $teamIds);
        }

        $teams = $query->groupBy('teams.id', 'teams.name')
            ->get();

        $teamData = [];
        foreach ($teams as $team) {
            $completionRate = $team->total_tasks > 0
                ? round(($team->completed_tasks / $team->total_tasks) * 100, 1)
                : 0;

            // Calculate team SPI
            $teamProjects = Project::where('team', $team->id)->pluck('id');
            $avgSpi = WeeklyPlan::whereIn('project_id', $teamProjects)
                ->where('week_start_date', '>=', now()->subWeeks(4))
                ->get()
                ->avg(function($plan) {
                    return $plan->planned_weight_total > 0
                        ? $plan->actual_weight_total / $plan->planned_weight_total
                        : 0;
                });

            $teamData[] = [
                'name' => $team->name,
                'projects' => $team->project_count,
                'tasks' => $team->total_tasks,
                'completion_rate' => $completionRate,
                'spi' => round($avgSpi ?? 0, 2),
                'performance' => $avgSpi >= 0.9 ? 'excellent' : ($avgSpi >= 0.7 ? 'good' : 'needs-improvement')
            ];
        }

        // Sort by SPI
        usort($teamData, function($a, $b) {
            return $b['spi'] <=> $a['spi'];
        });

        return $teamData;
    }

    /**
     * Get risk indicators
     */
    private function getRiskIndicators($projectIds = null)
    {
        $query = Project::with(['tasks', 'weeklyPlans']);

        if ($projectIds) {
            $query->whereIn('id', $projectIds);
        }

        $projects = $query->get();

        $risks = [
            'high' => [],
            'medium' => [],
            'low' => []
        ];

        foreach ($projects as $project) {
            $riskLevel = $this->calculateRiskLevel($project);
            $risks[$riskLevel['level']][] = [
                'project' => $project,
                'risk' => $riskLevel
            ];
        }

        return [
            'high_count' => count($risks['high']),
            'medium_count' => count($risks['medium']),
            'low_count' => count($risks['low']),
            'high_risks' => array_slice($risks['high'], 0, 5),
            'medium_risks' => array_slice($risks['medium'], 0, 5),
        ];
    }

    /**
     * Calculate risk level for a project
     */
    private function calculateRiskLevel($project)
    {
        $riskFactors = [];
        $riskScore = 0;

        // Schedule risk
        if ($project->end_date) {
            $daysToDeadline = now()->diffInDays(Carbon::parse($project->end_date), false);
            if ($daysToDeadline < 0) {
                $riskScore += 30;
                $riskFactors[] = 'Overdue project';
            } elseif ($daysToDeadline <= 7) {
                $riskScore += 15;
                $riskFactors[] = 'Approaching deadline';
            }
        }

        // Task completion risk
        $totalTasks = $project->tasks->count();
        if ($totalTasks > 0) {
            $completedTasks = $project->tasks->where('status', 'completed')->count();
            $completionRate = ($completedTasks / $totalTasks) * 100;

            if ($completionRate < 40) {
                $riskScore += 25;
                $riskFactors[] = 'Low completion rate';
            }
        }

        // Performance risk (SPI)
        $latestPlan = $project->weeklyPlans()->latest('week_start_date')->first();
        if ($latestPlan) {
            $spi = $latestPlan->planned_weight_total > 0
                ? $latestPlan->actual_weight_total / $latestPlan->planned_weight_total
                : 0;

            if ($spi < 0.7) {
                $riskScore += 25;
                $riskFactors[] = 'Poor performance (SPI)';
            } elseif ($spi < 0.9) {
                $riskScore += 10;
                $riskFactors[] = 'Below target performance';
            }
        }

        // Overdue tasks risk
        $overdueTasks = $project->tasks()
            ->where('status', '!=', 'completed')
            ->where('due_date', '<', now())
            ->count();

        if ($overdueTasks > 5) {
            $riskScore += 20;
            $riskFactors[] = 'Multiple overdue tasks';
        } elseif ($overdueTasks > 0) {
            $riskScore += 10;
            $riskFactors[] = 'Has overdue tasks';
        }

        // Determine risk level
        $level = 'low';
        if ($riskScore >= 50) {
            $level = 'high';
        } elseif ($riskScore >= 25) {
            $level = 'medium';
        }

        return [
            'level' => $level,
            'score' => $riskScore,
            'factors' => $riskFactors
        ];
    }

    /**
     * Predict next week performance
     */
    private function predictNextWeek($data)
    {
        if (count($data) < 2) return null;

        // Simple linear regression
        $n = count($data);
        $sumX = 0;
        $sumY = 0;
        $sumXY = 0;
        $sumX2 = 0;

        for ($i = 0; $i < $n; $i++) {
            $sumX += $i;
            $sumY += $data[$i];
            $sumXY += $i * $data[$i];
            $sumX2 += $i * $i;
        }

        $slope = ($n * $sumXY - $sumX * $sumY) / ($n * $sumX2 - $sumX * $sumX);
        $intercept = ($sumY - $slope * $sumX) / $n;

        return round($slope * $n + $intercept, 1);
    }

    /**
     * Calculate trend direction
     */
    private function calculateTrend($data)
    {
        if (count($data) < 2) return 'stable';

        $recent = array_slice($data, -3);
        $older = array_slice($data, 0, min(3, count($data) - 3));

        $recentAvg = array_sum($recent) / count($recent);
        $olderAvg = count($older) > 0 ? array_sum($older) / count($older) : $recentAvg;

        $diff = $recentAvg - $olderAvg;

        if ($diff > 5) return 'improving';
        if ($diff < -5) return 'declining';
        return 'stable';
    }
}
