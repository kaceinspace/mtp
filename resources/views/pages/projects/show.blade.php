@extends('layouts.dashboard')

@section('title', $project->title)
@section('page-title', 'Project Details')

@section('content')
<div class="space-y-6">
    <!-- Project Header -->
    <div class="bg-gradient-to-r from-primary-600 to-primary-800 dark:from-primary-800 dark:to-primary-950 rounded-lg shadow-lg p-6 text-white">
        <div class="flex items-start justify-between">
            <div class="flex-1">
                <div class="flex items-center space-x-3 mb-2">
                    <h1 class="text-3xl font-bold">{{ $project->title }}</h1>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                        @if($project->status === 'completed') bg-green-500/20 text-green-100
                        @elseif($project->status === 'ongoing') bg-blue-500/20 text-blue-100
                        @elseif($project->status === 'on-hold') bg-red-500/20 text-red-100
                        @else bg-gray-500/20 text-gray-100
                        @endif">
                        {{ ucfirst(str_replace('-', ' ', $project->status)) }}
                    </span>
                </div>
                <p class="text-primary-100 dark:text-primary-200 text-lg">{{ $project->description ?? 'No description provided' }}</p>
                <div class="flex items-center space-x-6 mt-4 text-sm text-primary-100 dark:text-primary-200">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        Created by {{ $project->creator->name }}
                    </div>
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        {{ $project->start_date ? $project->start_date->format('M d, Y') : 'No start date' }} -
                        {{ $project->end_date ? $project->end_date->format('M d, Y') : 'No end date' }}
                    </div>
                </div>
            </div>
            <div class="flex items-center space-x-2">
                <a href="{{ route('projects.wbs', $project) }}"
                   class="inline-flex items-center px-4 py-2 bg-white/10 hover:bg-white/20 text-white font-medium rounded-lg transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                    </svg>
                    WBS
                </a>
                <a href="{{ route('projects.files.index', $project) }}"
                   class="inline-flex items-center px-4 py-2 bg-white/10 hover:bg-white/20 text-white font-medium rounded-lg transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                    Files
                </a>
                <a href="{{ route('discussions.index', $project) }}"
                   class="inline-flex items-center px-4 py-2 bg-white/10 hover:bg-white/20 text-white font-medium rounded-lg transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                    Discussion
                </a>
                @if(Gate::allows('admin') || Gate::allows('team_lead'))
                <a href="{{ route('projects.edit', $project) }}" class="inline-flex items-center px-4 py-2 bg-white/10 hover:bg-white/20 text-white font-medium rounded-lg transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit Project
                </a>
                @endif
                <a href="{{ route('projects.index') }}" class="inline-flex items-center px-4 py-2 bg-white/10 hover:bg-white/20 text-white font-medium rounded-lg transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Project Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Total Tasks</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $project->tasks->count() }}</p>
                        </div>
                        <div class="bg-primary-100 dark:bg-primary-900/30 p-3 rounded-lg">
                            <svg class="w-6 h-6 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Completed</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $project->tasks->where('status', 'completed')->count() }}</p>
                        </div>
                        <div class="bg-green-100 dark:bg-green-900/30 p-3 rounded-lg">
                            <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Team Size</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $project->members->count() }}</p>
                        </div>
                        <div class="bg-accent-100 dark:bg-accent-900/30 p-3 rounded-lg">
                            <svg class="w-6 h-6 text-accent-600 dark:text-accent-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tasks List -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Tasks</h2>
                    @if(Gate::allows('admin') || Gate::allows('team_lead'))
                    <a href="{{ route('tasks.create', ['project_id' => $project->id]) }}" class="inline-flex items-center px-3 py-1.5 bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium rounded-lg transition">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Add Task
                    </a>
                    @endif
                </div>

                <div class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($project->tasks as $task)
                    <div class="p-6 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center space-x-3">
                                    <h3 class="text-base font-semibold text-gray-900 dark:text-white">{{ $task->title }}</h3>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($task->status === 'completed') bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300
                                        @elseif($task->status === 'in-progress') bg-primary-100 dark:bg-primary-900/30 text-primary-800 dark:text-primary-300
                                        @elseif($task->status === 'review') bg-accent-100 dark:bg-accent-900/30 text-accent-800 dark:text-accent-300
                                        @else bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300
                                        @endif">
                                        {{ ucfirst(str_replace('-', ' ', $task->status)) }}
                                    </span>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($task->priority === 'critical') bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300
                                        @elseif($task->priority === 'high') bg-orange-100 dark:bg-orange-900/30 text-orange-800 dark:text-orange-300
                                        @elseif($task->priority === 'medium') bg-accent-100 dark:bg-accent-900/30 text-accent-800 dark:text-accent-300
                                        @else bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300
                                        @endif">
                                        {{ ucfirst($task->priority) }}
                                    </span>
                                </div>
                                @if($task->description)
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">{{ $task->description }}</p>
                                @endif
                                <div class="flex items-center space-x-4 mt-3 text-sm text-gray-600 dark:text-gray-400">
                                    @if($task->assignee)
                                    <div class="flex items-center">
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($task->assignee->name) }}&background=3b82f6&color=fff"
                                             alt="{{ $task->assignee->name }}"
                                             class="w-5 h-5 rounded-full mr-2">
                                        {{ $task->assignee->name }}
                                    </div>
                                    @endif
                                    @if($task->due_date)
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        Due: {{ $task->due_date->format('M d, Y') }}
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="p-12 text-center">
                        <svg class="w-16 h-16 text-gray-300 dark:text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        <p class="text-gray-500 dark:text-gray-400">No tasks yet</p>
                        @if(Gate::allows('admin') || Gate::allows('team_lead'))
                        <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">Create your first task to get started</p>
                        @endif
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Project Info -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Project Info</h3>
                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Priority</p>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium mt-1
                            @if($project->priority === 'critical') bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300
                            @elseif($project->priority === 'high') bg-orange-100 dark:bg-orange-900/30 text-orange-800 dark:text-orange-300
                            @elseif($project->priority === 'medium') bg-accent-100 dark:bg-accent-900/30 text-accent-800 dark:text-accent-300
                            @else bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300
                            @endif">
                            {{ ucfirst($project->priority) }}
                        </span>
                    </div>

                    @if($project->department)
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Department</p>
                        <p class="text-base font-medium text-gray-900 dark:text-white mt-1">{{ $project->department }}</p>
                    </div>
                    @endif

                    @if($project->teamInfo)
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Team</p>
                        <p class="text-base font-medium text-gray-900 dark:text-white mt-1">{{ $project->teamInfo->name }}</p>
                    </div>
                    @endif

                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Created</p>
                        <p class="text-base font-medium text-gray-900 dark:text-white mt-1">{{ $project->created_at->format('M d, Y') }}</p>
                    </div>
                </div>
            </div>

            <!-- Team Members -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Team Members ({{ $project->members->count() }})</h3>
                <div class="space-y-3">
                    @forelse($project->members as $member)
                    <div class="flex items-center space-x-3">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($member->name) }}&background=3b82f6&color=fff"
                             alt="{{ $member->name }}"
                             class="w-10 h-10 rounded-full">
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ $member->name }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ ucfirst($member->user_type) }}</p>
                        </div>
                    </div>
                    @empty
                    <p class="text-sm text-gray-500 dark:text-gray-400 text-center py-4">No team members assigned</p>
                    @endforelse
                </div>
            </div>

            <!-- Progress -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Progress</h3>
                @php
                    $totalTasks = $project->tasks->count();
                    $completedTasks = $project->tasks->where('status', 'completed')->count();
                    $progress = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;
                @endphp
                <div class="space-y-2">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600 dark:text-gray-400">Overall Progress</span>
                        <span class="font-semibold text-gray-900 dark:text-white">{{ $progress }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3">
                        <div class="bg-primary-600 dark:bg-primary-500 h-3 rounded-full transition-all" style="width: {{ $progress }}%"></div>
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $completedTasks }} of {{ $totalTasks }} tasks completed</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
