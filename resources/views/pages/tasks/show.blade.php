@extends('layouts.dashboard')

@section('title', $task->title)
@section('page-title', __('messages.task_details'))

@section('content')
<div class="space-y-6">
    <!-- Task Header -->
    <div class="bg-gradient-to-r from-primary-600 to-primary-800 dark:from-primary-800 dark:to-primary-950 rounded-lg shadow-lg p-6 text-white">
        <div class="flex items-start justify-between">
            <div class="flex-1">
                <div class="flex items-center space-x-3 mb-2">
                    <h1 class="text-3xl font-bold">{{ $task->title }}</h1>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                        @if($task->status === 'completed') bg-green-500/20 text-green-100
                        @elseif($task->status === 'in-progress') bg-blue-500/20 text-blue-100
                        @elseif($task->status === 'review') bg-yellow-500/20 text-yellow-100
                        @else bg-gray-500/20 text-gray-100
                        @endif">
                        {{ ucfirst(str_replace('-', ' ', $task->status)) }}
                    </span>
                </div>

                <div class="flex items-center space-x-6 mt-4 text-sm text-primary-100 dark:text-primary-200">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/>
                        </svg>
                        {{ __('messages.project') }}: <a href="{{ route('projects.show', $task->project) }}" class="font-medium hover:underline ml-1">{{ $task->project->title }}</a>
                    </div>
                    @if($task->assignee)
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        {{ __('messages.assigned_to') }} {{ $task->assignee->name }}
                    </div>
                    @endif
                </div>
            </div>

            <div class="flex items-center space-x-2">
                @if(Gate::allows('admin') || Gate::allows('team_lead') || $task->assigned_to === auth()->id())
                <a href="{{ route('tasks.edit', $task) }}"
                    class="px-4 py-2 bg-white/10 hover:bg-white/20 text-white rounded-lg transition flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    {{ __('messages.edit') }}
                </a>
                @endif
                <a href="{{ route('tasks.index') }}"
                    class="px-4 py-2 bg-white/10 hover:bg-white/20 text-white rounded-lg transition">
                    {{ __('messages.back_to_tasks') }}
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Description -->
            @if($task->description)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('messages.description') }}</h3>
                <div class="prose dark:prose-invert max-w-none">
                    <p class="text-gray-700 dark:text-gray-300 whitespace-pre-line">{{ $task->description }}</p>
                </div>
            </div>
            @endif

            <!-- Activity Timeline (Placeholder for future feature) -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('messages.activity_timeline') }}</h3>
                <div class="space-y-4">
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 rounded-full bg-primary-100 dark:bg-primary-900/30 flex items-center justify-center">
                                <svg class="w-4 h-4 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm text-gray-900 dark:text-white font-medium">{{ __('messages.task_created') }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $task->created_at->diffForHumans() }}</p>
                        </div>
                    </div>

                    @if($task->completed_at)
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center">
                                <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm text-gray-900 dark:text-white font-medium">{{ __('messages.task_completed') }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $task->completed_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Task Details Card -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('messages.task_details') }}</h3>

                <div class="space-y-4">
                    <!-- Priority -->
                    <div>
                        <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider block mb-2">{{ __('messages.priority') }}</label>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                            @if($task->priority === 'critical') bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300
                            @elseif($task->priority === 'high') bg-orange-100 dark:bg-orange-900/30 text-orange-800 dark:text-orange-300
                            @elseif($task->priority === 'medium') bg-accent-100 dark:bg-accent-900/30 text-accent-800 dark:text-accent-300
                            @else bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300
                            @endif">
                            {{ ucfirst($task->priority) }}
                        </span>
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider block mb-2">{{ __('messages.status') }}</label>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                            @if($task->status === 'completed') bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300
                            @elseif($task->status === 'in-progress') bg-primary-100 dark:bg-primary-900/30 text-primary-800 dark:text-primary-300
                            @elseif($task->status === 'review') bg-accent-100 dark:bg-accent-900/30 text-accent-800 dark:text-accent-300
                            @else bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300
                            @endif">
                            {{ ucfirst(str_replace('-', ' ', $task->status)) }}
                        </span>
                    </div>

                    <!-- Due Date -->
                    @if($task->due_date)
                    <div>
                        <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider block mb-2">{{ __('messages.due_date') }}</label>
                        @php
                            $isOverdue = $task->due_date->isPast() && $task->status !== 'completed';
                        @endphp
                        <div class="flex items-center text-sm {{ $isOverdue ? 'text-red-600 dark:text-red-400' : 'text-gray-900 dark:text-white' }}">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span class="font-medium">{{ $task->due_date->format('M d, Y') }}</span>
                        </div>
                        @if($isOverdue)
                            <p class="text-xs text-red-600 dark:text-red-400 mt-1">⚠️ {{ __('messages.overdue_by', ['time' => $task->due_date->diffForHumans()]) }}</p>
                        @elseif(!$task->completed_at)
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ __('messages.due') }} {{ $task->due_date->diffForHumans() }}</p>
                        @endif
                    </div>
                    @endif

                    <!-- Assigned To -->
                    <div>
                        <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider block mb-2">{{ __('messages.assigned_to') }}</label>
                        @if($task->assignee)
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-full bg-primary-100 dark:bg-primary-900/30 flex items-center justify-center">
                                        <span class="text-sm font-medium text-primary-700 dark:text-primary-300">
                                            {{ substr($task->assignee->name, 0, 2) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $task->assignee->name }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ ucfirst(str_replace('_', ' ', $task->assignee->user_type)) }}</p>
                                </div>
                            </div>
                        @else
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('messages.unassigned') }}</p>
                        @endif
                    </div>

                    <!-- Timestamps -->
                    <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                        <div class="space-y-2 text-xs text-gray-500 dark:text-gray-400">
                            <div class="flex justify-between">
                                <span>{{ __('messages.created') }}:</span>
                                <span>{{ $task->created_at->format('M d, Y H:i') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>{{ __('messages.updated') }}:</span>
                                <span>{{ $task->updated_at->format('M d, Y H:i') }}</span>
                            </div>
                            @if($task->completed_at)
                            <div class="flex justify-between">
                                <span>{{ __('messages.completed') }}:</span>
                                <span>{{ $task->completed_at->format('M d, Y H:i') }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Project Info Card -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('messages.project') }}</h3>
                <a href="{{ route('projects.show', $task->project) }}" class="block group">
                    <div class="space-y-2">
                        <h4 class="text-sm font-medium text-gray-900 dark:text-white group-hover:text-primary-600 dark:group-hover:text-primary-400">
                            {{ $task->project->title }}
                        </h4>
                        @if($task->project->description)
                        <p class="text-xs text-gray-600 dark:text-gray-400 line-clamp-2">
                            {{ $task->project->description }}
                        </p>
                        @endif
                        <div class="flex items-center justify-between pt-2">
                            <span class="text-xs text-gray-500 dark:text-gray-400">
                                {{ $task->project->tasks->count() }} {{ __('messages.tasks') }}
                            </span>
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                @if($task->project->status === 'completed') bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300
                                @elseif($task->project->status === 'ongoing') bg-primary-100 dark:bg-primary-900/30 text-primary-800 dark:text-primary-300
                                @else bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300
                                @endif">
                                {{ ucfirst($task->project->status) }}
                            </span>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
