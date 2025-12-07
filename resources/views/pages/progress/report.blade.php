@extends('layouts.dashboard')

@section('title', 'Weekly Report - ' . $project->title)
@section('page-title', 'Weekly Progress Report')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <div class="flex justify-between items-start">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Weekly Progress Report</h2>
                <div class="text-sm text-gray-600 dark:text-gray-400 space-y-1">
                    <p><span class="font-semibold">Project:</span> {{ $project->title }}</p>
                    <p><span class="font-semibold">Week:</span> {{ $weekStartDate->format('M d, Y') }} - {{ $weekEndDate->format('M d, Y') }}
                        <span class="text-gray-500">(Week {{ $weekStartDate->weekOfYear }}, {{ $weekStartDate->year }})</span>
                    </p>
                </div>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('projects.progress.index', $project) }}?week_start={{ $weekStartDate->format('Y-m-d') }}"
                   class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition inline-flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i> Back to Progress
                </a>
                <a href="{{ route('projects.progress.export.excel', $project) }}?week_start={{ $weekStartDate->format('Y-m-d') }}"
                   class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition inline-flex items-center">
                    <i class="fas fa-file-excel mr-2"></i> Export Excel
                </a>
                <a href="{{ route('projects.progress.export.pdf', $project) }}?week_start={{ $weekStartDate->format('Y-m-d') }}"
                   class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition inline-flex items-center" target="_blank">
                    <i class="fas fa-file-pdf mr-2"></i> Export PDF
                </a>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Total Tasks</p>
                    <h3 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $summary['total_tasks'] }}</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Activities this week</p>
                </div>
                <div class="p-3 bg-blue-100 dark:bg-blue-900/30 rounded-lg">
                    <i class="fas fa-tasks text-2xl text-blue-600 dark:text-blue-400"></i>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Planned Weight</p>
                    <h3 class="text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($summary['planned_weight'], 2) }}%</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Target completion</p>
                </div>
                <div class="p-3 bg-purple-100 dark:bg-purple-900/30 rounded-lg">
                    <i class="fas fa-bullseye text-2xl text-purple-600 dark:text-purple-400"></i>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Actual Weight</p>
                    <h3 class="text-3xl font-bold {{ $summary['actual_weight'] >= $summary['planned_weight'] ? 'text-green-600 dark:text-green-400' : 'text-yellow-600 dark:text-yellow-400' }}">
                        {{ number_format($summary['actual_weight'], 2) }}%
                    </h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Actual progress</p>
                </div>
                <div class="p-3 {{ $summary['actual_weight'] >= $summary['planned_weight'] ? 'bg-green-100 dark:bg-green-900/30' : 'bg-yellow-100 dark:bg-yellow-900/30' }} rounded-lg">
                    <i class="fas fa-chart-line text-2xl {{ $summary['actual_weight'] >= $summary['planned_weight'] ? 'text-green-600 dark:text-green-400' : 'text-yellow-600 dark:text-yellow-400' }}"></i>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Deviation</p>
                    <h3 class="text-3xl font-bold {{ $summary['deviation_weight'] >= 0 ? 'text-red-600 dark:text-red-400' : 'text-green-600 dark:text-green-400' }}">
                        {{ $summary['deviation_weight'] > 0 ? '-' : '+' }}{{ number_format(abs($summary['deviation_weight']), 2) }}%
                    </h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $summary['deviation_weight'] >= 0 ? 'Behind schedule' : 'Ahead of schedule' }}</p>
                </div>
                <div class="p-3 {{ $summary['deviation_weight'] >= 0 ? 'bg-red-100 dark:bg-red-900/30' : 'bg-green-100 dark:bg-green-900/30' }} rounded-lg">
                    <i class="fas fa-exclamation-triangle text-2xl {{ $summary['deviation_weight'] >= 0 ? 'text-red-600 dark:text-red-400' : 'text-green-600 dark:text-green-400' }}"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Distribution -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Status Distribution</h3>
        </div>
        <div class="p-6">
            @php
                $total = $summary['total_tasks'] ?: 1;
                $completedPct = ($summary['completed'] / $total) * 100;
                $onTrackPct = ($summary['on_track'] / $total) * 100;
                $atRiskPct = ($summary['at_risk'] / $total) * 100;
                $delayedPct = ($summary['delayed'] / $total) * 100;
            @endphp

            <!-- Status Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <div class="relative overflow-hidden rounded-xl bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-900/30 border border-green-200 dark:border-green-800 p-5 group hover:shadow-lg transition-all duration-300">
                    <div class="flex items-center justify-between mb-3">
                        <div class="p-3 bg-green-500 dark:bg-green-600 rounded-lg shadow-lg">
                            <i class="fas fa-check-circle text-2xl text-white"></i>
                        </div>
                        <div class="text-right">
                            <div class="text-3xl font-bold text-green-700 dark:text-green-300">{{ $summary['completed'] }}</div>
                            <div class="text-xs font-medium text-green-600 dark:text-green-400">{{ round($completedPct, 1) }}%</div>
                        </div>
                    </div>
                    <div class="text-sm font-semibold text-green-700 dark:text-green-300">Completed</div>
                    <div class="mt-2 bg-green-200 dark:bg-green-900/50 rounded-full h-2 overflow-hidden">
                        <div class="bg-green-500 dark:bg-green-600 h-full transition-all duration-500" style="width: {{ $completedPct }}%"></div>
                    </div>
                </div>

                <div class="relative overflow-hidden rounded-xl bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-900/30 border border-blue-200 dark:border-blue-800 p-5 group hover:shadow-lg transition-all duration-300">
                    <div class="flex items-center justify-between mb-3">
                        <div class="p-3 bg-blue-500 dark:bg-blue-600 rounded-lg shadow-lg">
                            <i class="fas fa-arrow-up text-2xl text-white"></i>
                        </div>
                        <div class="text-right">
                            <div class="text-3xl font-bold text-blue-700 dark:text-blue-300">{{ $summary['on_track'] }}</div>
                            <div class="text-xs font-medium text-blue-600 dark:text-blue-400">{{ round($onTrackPct, 1) }}%</div>
                        </div>
                    </div>
                    <div class="text-sm font-semibold text-blue-700 dark:text-blue-300">On Track</div>
                    <div class="mt-2 bg-blue-200 dark:bg-blue-900/50 rounded-full h-2 overflow-hidden">
                        <div class="bg-blue-500 dark:bg-blue-600 h-full transition-all duration-500" style="width: {{ $onTrackPct }}%"></div>
                    </div>
                </div>

                <div class="relative overflow-hidden rounded-xl bg-gradient-to-br from-yellow-50 to-yellow-100 dark:from-yellow-900/20 dark:to-yellow-900/30 border border-yellow-200 dark:border-yellow-800 p-5 group hover:shadow-lg transition-all duration-300">
                    <div class="flex items-center justify-between mb-3">
                        <div class="p-3 bg-yellow-500 dark:bg-yellow-600 rounded-lg shadow-lg">
                            <i class="fas fa-exclamation-circle text-2xl text-white"></i>
                        </div>
                        <div class="text-right">
                            <div class="text-3xl font-bold text-yellow-700 dark:text-yellow-300">{{ $summary['at_risk'] }}</div>
                            <div class="text-xs font-medium text-yellow-600 dark:text-yellow-400">{{ round($atRiskPct, 1) }}%</div>
                        </div>
                    </div>
                    <div class="text-sm font-semibold text-yellow-700 dark:text-yellow-300">At Risk</div>
                    <div class="mt-2 bg-yellow-200 dark:bg-yellow-900/50 rounded-full h-2 overflow-hidden">
                        <div class="bg-yellow-500 dark:bg-yellow-600 h-full transition-all duration-500" style="width: {{ $atRiskPct }}%"></div>
                    </div>
                </div>

                <div class="relative overflow-hidden rounded-xl bg-gradient-to-br from-red-50 to-red-100 dark:from-red-900/20 dark:to-red-900/30 border border-red-200 dark:border-red-800 p-5 group hover:shadow-lg transition-all duration-300">
                    <div class="flex items-center justify-between mb-3">
                        <div class="p-3 bg-red-500 dark:bg-red-600 rounded-lg shadow-lg">
                            <i class="fas fa-times-circle text-2xl text-white"></i>
                        </div>
                        <div class="text-right">
                            <div class="text-3xl font-bold text-red-700 dark:text-red-300">{{ $summary['delayed'] }}</div>
                            <div class="text-xs font-medium text-red-600 dark:text-red-400">{{ round($delayedPct, 1) }}%</div>
                        </div>
                    </div>
                    <div class="text-sm font-semibold text-red-700 dark:text-red-300">Delayed</div>
                    <div class="mt-2 bg-red-200 dark:bg-red-900/50 rounded-full h-2 overflow-hidden">
                        <div class="bg-red-500 dark:bg-red-600 h-full transition-all duration-500" style="width: {{ $delayedPct }}%"></div>
                    </div>
                </div>
            </div>

            <!-- Combined Progress Bar -->
            <div class="bg-gray-100 dark:bg-gray-900 rounded-lg p-4">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">Overall Distribution</span>
                    <span class="text-sm text-gray-600 dark:text-gray-400">{{ $total }} Total Tasks</span>
                </div>
                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-8 flex overflow-hidden shadow-inner">
                    @if($summary['completed'] > 0)
                        <div class="bg-green-500 dark:bg-green-600 flex items-center justify-center text-white text-xs font-bold hover:bg-green-600 dark:hover:bg-green-500 transition-colors"
                             style="width: {{ $completedPct }}%"
                             title="Completed: {{ $summary['completed'] }} ({{ round($completedPct, 1) }}%)">
                            @if($completedPct > 8)
                                <span>{{ round($completedPct) }}%</span>
                            @endif
                        </div>
                    @endif
                    @if($summary['on_track'] > 0)
                        <div class="bg-blue-500 dark:bg-blue-600 flex items-center justify-center text-white text-xs font-bold hover:bg-blue-600 dark:hover:bg-blue-500 transition-colors"
                             style="width: {{ $onTrackPct }}%"
                             title="On Track: {{ $summary['on_track'] }} ({{ round($onTrackPct, 1) }}%)">
                            @if($onTrackPct > 8)
                                <span>{{ round($onTrackPct) }}%</span>
                            @endif
                        </div>
                    @endif
                    @if($summary['at_risk'] > 0)
                        <div class="bg-yellow-500 dark:bg-yellow-600 flex items-center justify-center text-white text-xs font-bold hover:bg-yellow-600 dark:hover:bg-yellow-500 transition-colors"
                             style="width: {{ $atRiskPct }}%"
                             title="At Risk: {{ $summary['at_risk'] }} ({{ round($atRiskPct, 1) }}%)">
                            @if($atRiskPct > 8)
                                <span>{{ round($atRiskPct) }}%</span>
                            @endif
                        </div>
                    @endif
                    @if($summary['delayed'] > 0)
                        <div class="bg-red-500 dark:bg-red-600 flex items-center justify-center text-white text-xs font-bold hover:bg-red-600 dark:hover:bg-red-500 transition-colors"
                             style="width: {{ $delayedPct }}%"
                             title="Delayed: {{ $summary['delayed'] }} ({{ round($delayedPct, 1) }}%)">
                            @if($delayedPct > 8)
                                <span>{{ round($delayedPct) }}%</span>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Weekly Plan -->
    @if($weeklyPlan)
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Weekly Plan</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Objectives</h4>
                    <div class="bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg p-4 text-sm text-gray-700 dark:text-gray-300">
                        {!! nl2br(e($weeklyPlan->objectives ?? 'No objectives set')) !!}
                    </div>
                </div>
                <div>
                    <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Plan Details</h4>
                    <div class="space-y-2">
                        <div class="flex justify-between py-2 border-b border-gray-200 dark:border-gray-700">
                            <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Status:</span>
                            <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $weeklyPlan->status === 'completed' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400' }}">
                                {{ ucfirst($weeklyPlan->status) }}
                            </span>
                        </div>
                        <div class="flex justify-between py-2 border-b border-gray-200 dark:border-gray-700">
                            <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Planned Weight:</span>
                            <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ number_format($weeklyPlan->planned_weight_total ?? 0, 2) }}%</span>
                        </div>
                        <div class="flex justify-between py-2 border-b border-gray-200 dark:border-gray-700">
                            <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Actual Weight:</span>
                            <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ number_format($weeklyPlan->actual_weight_total ?? 0, 2) }}%</span>
                        </div>
                        <div class="flex justify-between py-2">
                            <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Completion Rate:</span>
                            <span class="text-sm font-bold {{ $weeklyPlan->getCompletionPercentage() >= 100 ? 'text-green-600 dark:text-green-400' : 'text-yellow-600 dark:text-yellow-400' }}">
                                {{ number_format($weeklyPlan->getCompletionPercentage(), 1) }}%
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Task Progress Details -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Task Progress Details</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-900">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Task</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Assignee</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Weight</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Planned %</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actual %</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Progress</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Deviation</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($taskProgress as $progress)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                        <td class="px-6 py-4">
                            <div class="text-sm">
                                <span class="font-semibold text-gray-900 dark:text-white">{{ $progress->task->wbs_code }}</span>
                                <span class="text-gray-700 dark:text-gray-300">{{ $progress->task->title }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">
                            @if($progress->task->assignee)
                                {{ $progress->task->assignee->name }}
                            @else
                                <span class="text-gray-400">Unassigned</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">{{ number_format($progress->task->weight ?? 0, 2) }}%</td>
                        <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">{{ number_format($progress->planned_percentage ?? 0, 1) }}%</td>
                        <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">{{ number_format($progress->actual_percentage ?? 0, 1) }}%</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-5 mr-2">
                                    <div class="bg-blue-600 dark:bg-blue-500 h-5 rounded-full flex items-center justify-center text-white text-xs font-semibold" style="width: {{ $progress->progress_percentage }}%">
                                        <span class="px-1">{{ number_format($progress->progress_percentage, 0) }}%</span>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @if($progress->deviation_percentage !== null)
                                <span class="px-2 py-1 text-xs font-semibold rounded-full {{ abs($progress->deviation_percentage) < 10 ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : (abs($progress->deviation_percentage) < 20 ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400' : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400') }}">
                                    {{ $progress->deviation_percentage > 0 ? '-' : '+' }}{{ number_format(abs($progress->deviation_percentage), 1) }}%
                                </span>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $statusConfig = [
                                    'on-track' => ['bg' => 'bg-blue-100 dark:bg-blue-900/30', 'text' => 'text-blue-800 dark:text-blue-400', 'label' => 'On Track'],
                                    'at-risk' => ['bg' => 'bg-yellow-100 dark:bg-yellow-900/30', 'text' => 'text-yellow-800 dark:text-yellow-400', 'label' => 'At Risk'],
                                    'delayed' => ['bg' => 'bg-red-100 dark:bg-red-900/30', 'text' => 'text-red-800 dark:text-red-400', 'label' => 'Delayed'],
                                    'completed' => ['bg' => 'bg-green-100 dark:bg-green-900/30', 'text' => 'text-green-800 dark:text-green-400', 'label' => 'Completed']
                                ];
                                $status = $statusConfig[$progress->status] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'label' => $progress->status];
                            @endphp
                            <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $status['bg'] }} {{ $status['text'] }}">
                                {{ $status['label'] }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">No progress data available for this week</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Problems & Solutions -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-red-200 dark:border-red-800">
            <div class="px-6 py-4 bg-red-50 dark:bg-red-900/20 border-b border-red-200 dark:border-red-800 rounded-t-lg">
                <h3 class="text-lg font-semibold text-red-900 dark:text-red-300 flex items-center">
                    <i class="fas fa-exclamation-triangle mr-2"></i> Major Problems
                </h3>
            </div>
            <div class="p-6">
                @if(!empty($problems) && count($problems) > 0)
                    <ul class="space-y-3">
                        @foreach($problems as $problem)
                            <li class="flex items-start">
                                <i class="fas fa-dot-circle text-red-500 mt-1 mr-3"></i>
                                <div class="text-sm">
                                    @if(is_array($problem))
                                        <strong class="text-gray-900 dark:text-gray-100">{{ $problem['task'] }}:</strong>
                                        <span class="text-gray-700 dark:text-gray-300">{{ $problem['issue'] }}</span>
                                    @else
                                        <span class="text-gray-700 dark:text-gray-300">{{ $problem }}</span>
                                    @endif
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-sm text-gray-500 dark:text-gray-400">No major problems reported this week</p>
                @endif
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-green-200 dark:border-green-800">
            <div class="px-6 py-4 bg-green-50 dark:bg-green-900/20 border-b border-green-200 dark:border-green-800 rounded-t-lg">
                <h3 class="text-lg font-semibold text-green-900 dark:text-green-300 flex items-center">
                    <i class="fas fa-lightbulb mr-2"></i> Proposed Solutions
                </h3>
            </div>
            <div class="p-6">
                @if(!empty($solutions) && count($solutions) > 0)
                    <ul class="space-y-3">
                        @foreach($solutions as $solution)
                            <li class="flex items-start">
                                <i class="fas fa-check-circle text-green-500 mt-1 mr-3"></i>
                                <div class="text-sm">
                                    @if(is_array($solution))
                                        <strong class="text-gray-900 dark:text-gray-100">{{ $solution['task'] }}:</strong>
                                        <span class="text-gray-700 dark:text-gray-300">{{ $solution['solution'] }}</span>
                                    @else
                                        <span class="text-gray-700 dark:text-gray-300">{{ $solution }}</span>
                                    @endif
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-sm text-gray-500 dark:text-gray-400">No solutions documented</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Next Week Actions -->
    @if($weeklyPlan && $weeklyPlan->next_week_plan)
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-blue-200 dark:border-blue-800">
        <div class="px-6 py-4 bg-blue-50 dark:bg-blue-900/20 border-b border-blue-200 dark:border-blue-800 rounded-t-lg">
            <h3 class="text-lg font-semibold text-blue-900 dark:text-blue-300 flex items-center">
                <i class="fas fa-calendar-check mr-2"></i> Program & Actions for Next Week
            </h3>
        </div>
        <div class="p-6">
            <div class="border-l-4 border-blue-500 pl-4 text-sm text-gray-700 dark:text-gray-300">
                {!! nl2br(e($weeklyPlan->next_week_plan)) !!}
            </div>
        </div>
    </div>
    @endif

    <!-- Remarks & Attachments -->
    @if($weeklyPlan && ($weeklyPlan->remarks || $weeklyPlan->attachments))
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
        <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900/20 border-b border-gray-200 dark:border-gray-700 rounded-t-lg">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-300 flex items-center">
                <i class="fas fa-paperclip mr-2"></i> Remarks & Attachments
            </h3>
        </div>
        <div class="p-6 space-y-4">
            @if($weeklyPlan->remarks)
            <div>
                <h4 class="font-semibold text-sm text-gray-700 dark:text-gray-300 mb-2">Remarks:</h4>
                <div class="text-sm text-gray-600 dark:text-gray-400 bg-gray-50 dark:bg-gray-900/50 p-4 rounded-lg">
                    {!! nl2br(e($weeklyPlan->remarks)) !!}
                </div>
            </div>
            @endif

            @if($weeklyPlan->attachments && count($weeklyPlan->attachments) > 0)
            <div>
                <h4 class="font-semibold text-sm text-gray-700 dark:text-gray-300 mb-2">Attachments:</h4>
                <ul class="space-y-2">
                    @foreach($weeklyPlan->attachments as $attachment)
                        <li class="flex items-center text-sm">
                            <i class="fas fa-file text-gray-400 mr-2"></i>
                            <a href="{{ $attachment['url'] ?? '#' }}" target="_blank" class="text-blue-600 dark:text-blue-400 hover:underline">
                                {{ $attachment['name'] ?? 'Attachment' }}
                            </a>
                            @if(isset($attachment['size']))
                                <span class="text-gray-400 text-xs ml-2">({{ $attachment['size'] }})</span>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
            @endif
        </div>
    </div>
    @endif
</div>
@endsection
