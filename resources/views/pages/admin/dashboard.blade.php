@extends('layouts.dashboard')

@section('title', __('messages.admin') . ' ' . __('messages.dashboard'))
@section('page-title', __('messages.admin') . ' ' . __('messages.dashboard'))

@section('content')
<div class="space-y-6">
    <!-- Welcome Section -->
    <div class="bg-gradient-to-r from-primary-600 to-primary-800 dark:from-primary-800 dark:to-primary-950 rounded-lg shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold mb-2">{{ __('messages.welcome') }}, {{ auth()->user()->name }}! 👋</h2>
                <p class="text-primary-100 dark:text-primary-200">{{ __('messages.manage_monitor_activities') }}</p>
            </div>
            <div class="hidden md:block">
                <svg class="w-24 h-24 text-white opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Users -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ __('messages.total_users') }}</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $stats['total_users'] ?? 0 }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                        <span class="text-green-600 dark:text-green-400">{{ $stats['active_users'] ?? 0 }} {{ __('messages.active') }}</span>
                    </p>
                </div>
                <div class="bg-primary-100 dark:bg-primary-900/30 p-3 rounded-lg">
                    <svg class="w-8 h-8 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Projects -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ __('messages.total_projects') }}</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $stats['total_projects'] ?? 0 }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                        <span class="text-accent-600 dark:text-accent-400">{{ $stats['ongoing_projects'] ?? 0 }} {{ __('messages.ongoing') }}</span>
                    </p>
                </div>
                <div class="bg-green-100 dark:bg-green-900/30 p-3 rounded-lg">
                    <svg class="w-8 h-8 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Tasks -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ __('messages.total_tasks') }}</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $stats['total_tasks'] ?? 0 }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                        <span class="text-blue-600 dark:text-blue-400">{{ $stats['completed_tasks'] ?? 0 }} {{ __('messages.completed') }}</span>
                    </p>
                </div>
                <div class="bg-accent-100 dark:bg-accent-900/30 p-3 rounded-lg">
                    <svg class="w-8 h-8 text-accent-600 dark:text-accent-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Completion Rate -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ __('messages.completion_rate') }}</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $stats['completion_rate'] ?? 0 }}%</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                        <span class="text-green-600 dark:text-green-400">{{ $stats['completed_projects'] ?? 0 }} {{ __('messages.completed') }}</span>
                    </p>
                </div>
                <div class="bg-green-100 dark:bg-green-900/30 p-3 rounded-lg">
                    <svg class="w-8 h-8 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Phase 4.4: Dashboard Analytics -->

    <!-- Project Health Indicators -->
    @if(isset($projectHealthData))
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                    <i class="fas fa-heartbeat text-red-500 mr-2"></i>
                    {{ __('messages.project_health_indicators') }}
                </h3>
                <div class="flex gap-4 text-sm">
                    <div class="flex items-center">
                        <span class="w-3 h-3 bg-green-500 rounded-full mr-2"></span>
                        <span class="text-gray-600 dark:text-gray-400">{{ $projectHealthData['healthy'] }} {{ __('messages.healthy') }}</span>
                    </div>
                    <div class="flex items-center">
                        <span class="w-3 h-3 bg-yellow-500 rounded-full mr-2"></span>
                        <span class="text-gray-600 dark:text-gray-400">{{ $projectHealthData['at_risk'] }} {{ __('messages.at_risk') }}</span>
                    </div>
                    <div class="flex items-center">
                        <span class="w-3 h-3 bg-red-500 rounded-full mr-2"></span>
                        <span class="text-gray-600 dark:text-gray-400">{{ $projectHealthData['critical'] }} {{ __('messages.critical') }}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="text-center p-4 bg-green-50 dark:bg-green-900/20 rounded-lg border-2 border-green-500">
                    <div class="text-3xl font-bold text-green-600 dark:text-green-400">{{ $projectHealthData['healthy'] }}</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ __('messages.healthy_projects') }}</div>
                </div>
                <div class="text-center p-4 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg border-2 border-yellow-500">
                    <div class="text-3xl font-bold text-yellow-600 dark:text-yellow-400">{{ $projectHealthData['at_risk'] }}</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ __('messages.at_risk_projects') }}</div>
                </div>
                <div class="text-center p-4 bg-red-50 dark:bg-red-900/20 rounded-lg border-2 border-red-500">
                    <div class="text-3xl font-bold text-red-600 dark:text-red-400">{{ $projectHealthData['critical'] }}</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ __('messages.critical_projects') }}</div>
                </div>
            </div>

            @if(count($projectHealthData['projects']) > 0)
            <div class="space-y-3">
                <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">{{ __('messages.projects_needing_attention') }}</h4>
                @foreach($projectHealthData['projects'] as $item)
                <div class="flex items-center justify-between p-3 rounded-lg border
                    @if($item['health']['status'] === 'critical') border-red-200 dark:border-red-800 bg-red-50 dark:bg-red-900/10
                    @elseif($item['health']['status'] === 'at-risk') border-yellow-200 dark:border-yellow-800 bg-yellow-50 dark:bg-yellow-900/10
                    @else border-green-200 dark:border-green-800 bg-green-50 dark:bg-green-900/10
                    @endif">
                    <div class="flex-1">
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full
                                @if($item['health']['status'] === 'critical') bg-red-500
                                @elseif($item['health']['status'] === 'at-risk') bg-yellow-500
                                @else bg-green-500
                                @endif"></span>
                            <span class="font-medium text-gray-900 dark:text-white">{{ $item['project']->title }}</span>
                        </div>
                        @if(count($item['health']['issues']) > 0)
                        <div class="mt-1 flex flex-wrap gap-1">
                            @foreach($item['health']['issues'] as $issue)
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                                <i class="fas fa-exclamation-triangle text-orange-500 mr-1 text-xs"></i>
                                {{ $issue }}
                            </span>
                            @endforeach
                        </div>
                        @endif
                    </div>
                    <div class="ml-4 text-right">
                        <div class="text-2xl font-bold
                            @if($item['health']['status'] === 'critical') text-red-600 dark:text-red-400
                            @elseif($item['health']['status'] === 'at-risk') text-yellow-600 dark:text-yellow-400
                            @else text-green-600 dark:text-green-400
                            @endif">
                            {{ $item['health']['score'] }}
                        </div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ __('messages.health_score') }}</div>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>
    @endif

    <!-- Progress Trends & Predictions -->
    @if(isset($progressTrends))
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                    <i class="fas fa-chart-line text-blue-500 mr-2"></i>
                    {{ __('messages.progress_trends') }}
                    @if($progressTrends['trend'] === 'improving')
                        <span class="ml-3 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300">
                            <i class="fas fa-arrow-up mr-1"></i> {{ __('messages.improving') }}
                        </span>
                    @elseif($progressTrends['trend'] === 'declining')
                        <span class="ml-3 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300">
                            <i class="fas fa-arrow-down mr-1"></i> {{ __('messages.declining') }}
                        </span>
                    @else
                        <span class="ml-3 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300">
                            <i class="fas fa-minus mr-1"></i> {{ __('messages.stable') }}
                        </span>
                    @endif
                </h3>
            </div>
            <div class="p-6">
                <canvas id="progressTrendChart" class="w-full" style="height: 250px;"></canvas>
                @if($progressTrends['prediction'])
                <div class="mt-4 p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-700 dark:text-gray-300">
                            <i class="fas fa-crystal-ball text-blue-500 mr-2"></i>
                            {{ __('messages.predicted_next_week') }}:
                        </span>
                        <span class="text-lg font-bold text-blue-600 dark:text-blue-400">{{ $progressTrends['prediction'] }}%</span>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                    <i class="fas fa-tasks text-purple-500 mr-2"></i>
                    {{ __('messages.task_completion_trends') }}
                </h3>
            </div>
            <div class="p-6">
                <canvas id="taskCompletionChart" class="w-full" style="height: 250px;"></canvas>
            </div>
        </div>
    </div>
    @endif

    <!-- Team Performance & Risk Indicators -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Team Performance -->
        @if(isset($teamPerformance))
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                    <i class="fas fa-users text-indigo-500 mr-2"></i>
                    {{ __('messages.team_performance_metrics') }}
                </h3>
            </div>
            <div class="p-6">
                @if(count($teamPerformance) > 0)
                <div class="space-y-4">
                    @foreach($teamPerformance as $team)
                    <div class="p-4 rounded-lg border border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between mb-2">
                            <span class="font-semibold text-gray-900 dark:text-white">{{ $team['name'] }}</span>
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($team['performance'] === 'excellent') bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300
                                @elseif($team['performance'] === 'good') bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300
                                @else bg-orange-100 dark:bg-orange-900/30 text-orange-800 dark:text-orange-300
                                @endif">
                                {{ ucfirst(str_replace('-', ' ', $team['performance'])) }}
                            </span>
                        </div>
                        <div class="grid grid-cols-2 gap-4 mt-3">
                            <div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">{{ __('messages.spi') }}</div>
                                <div class="text-lg font-bold text-gray-900 dark:text-white">{{ $team['spi'] }}</div>
                            </div>
                            <div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">{{ __('messages.completion') }}</div>
                                <div class="text-lg font-bold text-gray-900 dark:text-white">{{ $team['completion_rate'] }}%</div>
                            </div>
                        </div>
                        <div class="mt-3 pt-3 border-t border-gray-200 dark:border-gray-700 flex justify-between text-sm text-gray-600 dark:text-gray-400">
                            <span>{{ $team['projects'] }} {{ __('messages.projects') }}</span>
                            <span>{{ $team['tasks'] }} {{ __('messages.tasks') }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <p class="text-center text-gray-500 dark:text-gray-400 py-8">{{ __('messages.no_team_data') }}</p>
                @endif
            </div>
        </div>
        @endif

        <!-- Risk Indicators -->
        @if(isset($riskIndicators))
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                    <i class="fas fa-exclamation-triangle text-orange-500 mr-2"></i>
                    {{ __('messages.risk_indicators') }}
                </h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-3 gap-4 mb-6">
                    <div class="text-center p-3 bg-red-50 dark:bg-red-900/20 rounded-lg">
                        <div class="text-2xl font-bold text-red-600 dark:text-red-400">{{ $riskIndicators['high_count'] }}</div>
                        <div class="text-xs text-gray-600 dark:text-gray-400 mt-1">{{ __('messages.high_risk') }}</div>
                    </div>
                    <div class="text-center p-3 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg">
                        <div class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">{{ $riskIndicators['medium_count'] }}</div>
                        <div class="text-xs text-gray-600 dark:text-gray-400 mt-1">{{ __('messages.medium_risk') }}</div>
                    </div>
                    <div class="text-center p-3 bg-green-50 dark:bg-green-900/20 rounded-lg">
                        <div class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $riskIndicators['low_count'] }}</div>
                        <div class="text-xs text-gray-600 dark:text-gray-400 mt-1">{{ __('messages.low_risk') }}</div>
                    </div>
                </div>

                <div class="space-y-3">
                    <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">{{ __('messages.high_risk_projects') }}</h4>
                    @forelse($riskIndicators['high_risks'] as $item)
                    <div class="p-3 rounded-lg border-l-4 border-red-500 bg-red-50 dark:bg-red-900/10">
                        <div class="font-medium text-gray-900 dark:text-white">{{ $item['project']->title }}</div>
                        <div class="mt-1 flex flex-wrap gap-1">
                            @foreach($item['risk']['factors'] as $factor)
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                                {{ $factor }}
                            </span>
                            @endforeach
                        </div>
                    </div>
                    @empty
                    <p class="text-sm text-gray-500 dark:text-gray-400 text-center py-4">{{ __('messages.no_high_risk_projects') }}</p>
                    @endforelse

                    @if(count($riskIndicators['medium_risks']) > 0)
                    <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2 mt-4">{{ __('messages.medium_risk_projects') }}</h4>
                    @foreach($riskIndicators['medium_risks'] as $item)
                    <div class="p-3 rounded-lg border-l-4 border-yellow-500 bg-yellow-50 dark:bg-yellow-900/10">
                        <div class="font-medium text-gray-900 dark:text-white">{{ $item['project']->title }}</div>
                        <div class="mt-1 text-xs text-gray-600 dark:text-gray-400">
                            {{ implode(', ', $item['risk']['factors']) }}
                        </div>
                    </div>
                    @endforeach
                    @endif
                </div>
            </div>
        </div>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Recent Projects -->
        <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('messages.recent_projects') }}</h3>
            </div>
            <div class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($recent_projects ?? [] as $project)
                <div class="p-6 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <h4 class="text-sm font-semibold text-gray-900 dark:text-white">{{ $project->title }}</h4>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ __('messages.by') }}: {{ $project->creator->name }}</p>
                            <div class="flex items-center space-x-4 mt-3">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-100 dark:bg-primary-900/30 text-primary-800 dark:text-primary-300">
                                    {{ ucfirst($project->status) }}
                                </span>
                                <span class="text-xs text-gray-500 dark:text-gray-400">
                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    {{ $project->created_at->diffForHumans() }}
                                </span>
                            </div>
                        </div>
                        <a href="#" class="ml-4 text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 text-sm font-medium">
                            {{ __('messages.view') }} →
                        </a>
                    </div>
                </div>
                @empty
                <div class="p-12 text-center">
                    <svg class="w-16 h-16 text-gray-300 dark:text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                    </svg>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">{{ __('messages.no_projects_yet') }}</p>
                </div>
                @endforelse
            </div>
            <div class="p-4 border-t border-gray-200 dark:border-gray-700">
                <a href="#" class="text-sm text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 font-medium">{{ __('messages.view_all_projects') }} →</a>
            </div>
        </div>

        <!-- Quick Stats & Actions -->
        <div class="space-y-6">
            <!-- User Distribution -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('messages.user_distribution') }}</h3>
                <div class="space-y-3">
                    <div>
                        <div class="flex items-center justify-between text-sm mb-1">
                            <span class="text-gray-600 dark:text-gray-400">👥 {{ __('messages.team_members') }}</span>
                            <span class="font-medium text-gray-900 dark:text-white">{{ $stats['team_member_count'] ?? 0 }}</span>
                        </div>
                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                            <div class="bg-primary-600 dark:bg-primary-500 h-2 rounded-full" style="width: {{ $stats['team_member_percentage'] ?? 0 }}%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex items-center justify-between text-sm mb-1">
                            <span class="text-gray-600 dark:text-gray-400">👑 {{ __('messages.team_leads') }}</span>
                            <span class="font-medium text-gray-900 dark:text-white">{{ $stats['team_lead_count'] ?? 0 }}</span>
                        </div>
                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                            <div class="bg-green-600 dark:bg-green-500 h-2 rounded-full" style="width: {{ $stats['team_lead_percentage'] ?? 0 }}%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex items-center justify-between text-sm mb-1">
                            <span class="text-gray-600 dark:text-gray-400">🛡️ {{ __('messages.admins') }}</span>
                            <span class="font-medium text-gray-900 dark:text-white">{{ $stats['admin_count'] ?? 0 }}</span>
                        </div>
                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                            <div class="bg-accent-600 dark:bg-accent-500 h-2 rounded-full" style="width: {{ $stats['admin_percentage'] ?? 0 }}%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('messages.quick_actions') }}</h3>
                <div class="space-y-2">
                    <a href="{{ route('admin.users.create') }}" class="flex items-center p-3 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700/50 transition group">
                        <div class="bg-primary-100 dark:bg-primary-900/30 group-hover:bg-primary-200 dark:group-hover:bg-primary-900/50 p-2 rounded-lg transition">
                            <svg class="w-5 h-5 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                        </div>
                        <span class="ml-3 text-sm font-medium text-gray-700 dark:text-gray-300 group-hover:text-gray-900 dark:group-hover:text-white">{{ __('messages.add_new_user') }}</span>
                    </a>
                    <a href="#" class="flex items-center p-3 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700/50 transition group">
                        <div class="bg-green-100 dark:bg-green-900/30 group-hover:bg-green-200 dark:group-hover:bg-green-900/50 p-2 rounded-lg transition">
                            <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/>
                            </svg>
                        </div>
                        <span class="ml-3 text-sm font-medium text-gray-700 dark:text-gray-300 group-hover:text-gray-900 dark:group-hover:text-white">{{ __('messages.view_all_projects') }}</span>
                    </a>
                    <a href="#" class="flex items-center p-3 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700/50 transition group">
                        <div class="bg-accent-100 dark:bg-accent-900/30 group-hover:bg-accent-200 dark:group-hover:bg-accent-900/50 p-2 rounded-lg transition">
                            <svg class="w-5 h-5 text-accent-600 dark:text-accent-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                        </div>
                        <span class="ml-3 text-sm font-medium text-gray-700 dark:text-gray-300 group-hover:text-gray-900 dark:group-hover:text-white">{{ __('messages.generate_report') }}</span>
                    </a>
                    <a href="#" class="flex items-center p-3 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700/50 transition group">
                        <div class="bg-primary-100 dark:bg-primary-900/30 group-hover:bg-primary-200 dark:group-hover:bg-primary-900/50 p-2 rounded-lg transition">
                            <svg class="w-5 h-5 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <span class="ml-3 text-sm font-medium text-gray-700 dark:text-gray-300 group-hover:text-gray-900 dark:group-hover:text-white">{{ __('messages.system_settings') }}</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="mt-6">
        <x-activity-feed :activities="$recent_activities ?? collect([])" :title="__('messages.recent_activities')" />
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Check if dark mode is enabled
    const isDarkMode = document.documentElement.classList.contains('dark');
    const gridColor = isDarkMode ? '#374151' : '#e5e7eb';
    const textColor = isDarkMode ? '#9ca3af' : '#6b7280';

    // Chart.js default colors
    Chart.defaults.color = textColor;
    Chart.defaults.borderColor = gridColor;

    @if(isset($progressTrends) && isset($progressTrends['weeks']) && count($progressTrends['weeks']) > 0)
    // Progress Trends Chart
    const progressCtx = document.getElementById('progressTrendChart');
    if (progressCtx) {
        new Chart(progressCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($progressTrends['weeks']) !!},
                datasets: [{
                    label: '{{ __('messages.completion_rate') }}',
                    data: {!! json_encode($progressTrends['completion_rates']) !!},
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.4,
                    fill: true,
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: isDarkMode ? '#1f2937' : '#ffffff',
                        titleColor: isDarkMode ? '#f3f4f6' : '#111827',
                        bodyColor: isDarkMode ? '#d1d5db' : '#374151',
                        borderColor: gridColor,
                        borderWidth: 1,
                        callbacks: {
                            label: function(context) {
                                return '{{ __('messages.completion') }}: ' + context.parsed.y.toFixed(1) + '%';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100,
                        ticks: {
                            callback: function(value) {
                                return value + '%';
                            }
                        },
                        grid: {
                            color: gridColor
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    }

    // Task Completion Trends Chart
    const taskCtx = document.getElementById('taskCompletionChart');
    if (taskCtx) {
        new Chart(taskCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($progressTrends['weeks']) !!},
                datasets: [{
                    label: '{{ __('messages.completed_tasks') }}',
                    data: {!! json_encode(array_map(function($tasks) {
                        return $tasks['completed'] ?? 0;
                    }, $progressTrends['tasks'])) !!},
                    backgroundColor: '#10b981',
                    borderRadius: 6
                }, {
                    label: '{{ __('messages.pending_tasks') }}',
                    data: {!! json_encode(array_map(function($tasks) {
                        return ($tasks['total'] ?? 0) - ($tasks['completed'] ?? 0);
                    }, $progressTrends['tasks'])) !!},
                    backgroundColor: '#f59e0b',
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 15,
                            usePointStyle: true
                        }
                    },
                    tooltip: {
                        backgroundColor: isDarkMode ? '#1f2937' : '#ffffff',
                        titleColor: isDarkMode ? '#f3f4f6' : '#111827',
                        bodyColor: isDarkMode ? '#d1d5db' : '#374151',
                        borderColor: gridColor,
                        borderWidth: 1
                    }
                },
                scales: {
                    x: {
                        stacked: true,
                        grid: {
                            display: false
                        }
                    },
                    y: {
                        stacked: true,
                        beginAtZero: true,
                        grid: {
                            color: gridColor
                        }
                    }
                }
            }
        });
    }
    @endif
});
</script>
@endpush
