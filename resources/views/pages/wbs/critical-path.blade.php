@extends('layouts.dashboard')

@section('title', 'Critical Path - ' . $project->title)
@section('page-title', 'Critical Path Analysis')

@section('content')
<div class="space-y-6">
    <!-- Flash Messages -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                Critical Path Analysis - {{ $project->title }}
            </h2>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                Identify the longest path of dependent tasks that determines project duration
            </p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('projects.wbs', $project) }}"
               class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition">
                <i class="fas fa-arrow-left mr-2"></i>Back to WBS
            </a>
            <form action="{{ route('projects.wbs.critical-path.calculate', $project) }}" method="POST" class="inline">
                @csrf
                <button type="submit"
                        class="px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-lg transition">
                    <i class="fas fa-calculator mr-2"></i>Recalculate
                </button>
            </form>
        </div>
    </div>

    <!-- Project Summary -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Project Duration</p>
                    <p class="text-3xl font-bold text-primary-600 dark:text-primary-400 mt-2">
                        {{ $projectDuration }} <span class="text-sm font-normal">days</span>
                    </p>
                </div>
                <div class="p-3 bg-primary-100 dark:bg-primary-900/20 rounded-lg">
                    <i class="fas fa-clock text-2xl text-primary-600 dark:text-primary-400"></i>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Critical Tasks</p>
                    <p class="text-3xl font-bold text-red-600 dark:text-red-400 mt-2">
                        {{ count($criticalPath) }}
                    </p>
                </div>
                <div class="p-3 bg-red-100 dark:bg-red-900/20 rounded-lg">
                    <i class="fas fa-exclamation-triangle text-2xl text-red-600 dark:text-red-400"></i>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Total Tasks</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">
                        {{ $tasks->count() }}
                    </p>
                </div>
                <div class="p-3 bg-gray-100 dark:bg-gray-700 rounded-lg">
                    <i class="fas fa-tasks text-2xl text-gray-600 dark:text-gray-400"></i>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Critical %</p>
                    <p class="text-3xl font-bold text-orange-600 dark:text-orange-400 mt-2">
                        {{ $tasks->count() > 0 ? round((count($criticalPath) / $tasks->count()) * 100, 1) : 0 }}%
                    </p>
                </div>
                <div class="p-3 bg-orange-100 dark:bg-orange-900/20 rounded-lg">
                    <i class="fas fa-percentage text-2xl text-orange-600 dark:text-orange-400"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Critical Path Tasks -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                <i class="fas fa-route text-red-600 mr-2"></i>Critical Path Tasks
            </h3>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                Tasks with zero slack time that directly impact project completion date
            </p>
        </div>
        <div class="p-6">
            @if(count($criticalPath) > 0)
                <div class="space-y-3">
                    @foreach($criticalPath as $index => $task)
                        <div class="flex items-center p-4 bg-red-50 dark:bg-red-900/10 border border-red-200 dark:border-red-800 rounded-lg">
                            <!-- Sequence Number -->
                            <div class="flex-shrink-0 w-12 h-12 bg-red-600 rounded-full flex items-center justify-center text-white font-bold">
                                {{ $index + 1 }}
                            </div>

                            <!-- Task Info -->
                            <div class="flex-grow ml-4">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="text-xs font-mono font-semibold text-red-600 dark:text-red-400">
                                        {{ $task['wbs_code'] }}
                                    </span>
                                    <h4 class="font-semibold text-gray-900 dark:text-white">
                                        {{ $task['title'] }}
                                    </h4>
                                </div>
                                <div class="flex items-center gap-4 text-sm text-gray-600 dark:text-gray-400">
                                    <span>
                                        <i class="fas fa-user-circle mr-1"></i>{{ $task['assignee'] }}
                                    </span>
                                    <span>
                                        <i class="fas fa-hourglass-half mr-1"></i>{{ $task['estimated_duration'] }} days
                                    </span>
                                    <span>
                                        <i class="fas fa-play-circle mr-1"></i>Start: Day {{ $task['early_start'] }}
                                    </span>
                                    <span>
                                        <i class="fas fa-flag-checkered mr-1"></i>Finish: Day {{ $task['early_finish'] }}
                                    </span>
                                </div>
                            </div>

                            <!-- Critical Badge -->
                            <div class="flex-shrink-0">
                                <span class="px-3 py-1 bg-red-600 text-white text-xs font-semibold rounded-full">
                                    <i class="fas fa-exclamation mr-1"></i>CRITICAL
                                </span>
                            </div>
                        </div>

                        @if($index < count($criticalPath) - 1)
                            <div class="flex justify-center">
                                <i class="fas fa-arrow-down text-2xl text-red-400"></i>
                            </div>
                        @endif
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <i class="fas fa-info-circle text-5xl text-gray-400 dark:text-gray-600 mb-4"></i>
                    <p class="text-gray-600 dark:text-gray-400">No critical path calculated</p>
                    <p class="text-sm text-gray-500 dark:text-gray-500 mt-2">
                        Click "Recalculate" to analyze the critical path
                    </p>
                </div>
            @endif
        </div>
    </div>

    <!-- All Tasks with Slack Time -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                <i class="fas fa-list-check text-primary-600 mr-2"></i>All Tasks Analysis
            </h3>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                Complete task schedule with slack time calculations
            </p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-700/50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">WBS</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Task</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Duration</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">ES</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">EF</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">LS</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">LF</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Slack</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($tasks->sortBy('early_start') as $task)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 {{ $task->is_critical ? 'bg-red-50 dark:bg-red-900/10' : '' }}">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-mono font-semibold {{ $task->is_critical ? 'text-red-600 dark:text-red-400' : 'text-primary-600 dark:text-primary-400' }}">
                                {{ $task->wbs_code ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                                <div class="flex items-center gap-2">
                                    {{ $task->title }}
                                    @if($task->is_critical)
                                        <span class="px-2 py-0.5 bg-red-600 text-white text-xs font-semibold rounded">CRITICAL</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                                {{ $task->estimated_duration ?? 0 }} days
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                                {{ $task->early_start ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                                {{ $task->early_finish ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                                {{ $task->late_start ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                                {{ $task->late_finish ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @if($task->total_float !== null)
                                    <span class="px-2 py-1 rounded {{ $task->total_float == 0 ? 'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300' : 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300' }}">
                                        {{ $task->total_float }} days
                                    </span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @php
                                    $statusColors = [
                                        'todo' => 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300',
                                        'in-progress' => 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300',
                                        'review' => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900 dark:text-yellow-300',
                                        'completed' => 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300',
                                    ];
                                @endphp
                                <span class="px-2 py-1 text-xs font-medium rounded {{ $statusColors[$task->status] ?? $statusColors['todo'] }}">
                                    {{ ucfirst($task->status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                No tasks found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Legend -->
    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-6">
        <h4 class="font-semibold text-gray-900 dark:text-white mb-3">
            <i class="fas fa-info-circle mr-2"></i>Understanding Critical Path
        </h4>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-700 dark:text-gray-300">
            <div>
                <p class="font-medium mb-2">Terms:</p>
                <ul class="space-y-1 ml-4">
                    <li><strong>ES</strong> - Early Start: Earliest time a task can begin</li>
                    <li><strong>EF</strong> - Early Finish: Earliest time a task can complete</li>
                    <li><strong>LS</strong> - Late Start: Latest time a task can begin without delaying project</li>
                    <li><strong>LF</strong> - Late Finish: Latest time a task can complete without delaying project</li>
                </ul>
            </div>
            <div>
                <p class="font-medium mb-2">Key Concepts:</p>
                <ul class="space-y-1 ml-4">
                    <li><strong>Slack/Float</strong> - Amount of time a task can be delayed without impacting project</li>
                    <li><strong>Critical Task</strong> - Tasks with zero slack that directly impact project duration</li>
                    <li><strong>Critical Path</strong> - Longest sequence of critical tasks determining minimum project duration</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
