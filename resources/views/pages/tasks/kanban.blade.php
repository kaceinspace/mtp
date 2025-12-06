@extends('layouts.dashboard')

@section('title', 'Kanban Board')
@section('page-title', 'Task Kanban Board')

@section('content')
<div class="space-y-6" x-data="kanbanBoard()">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                @if(Gate::allows('admin') || Gate::allows('team_lead'))
                    All Tasks - Kanban View
                @else
                    My Tasks - Kanban View
                @endif
            </h2>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Drag and drop tasks to update their status
            </p>
        </div>

        <div class="flex items-center gap-3 mt-4 sm:mt-0">
            <!-- View Toggle -->
            <div class="flex bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-1">
                <a href="{{ route('tasks.index') }}" class="px-4 py-2 text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white rounded-md transition">
                    <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                    </svg>
                    List
                </a>
                <button class="px-4 py-2 text-sm font-medium text-white bg-primary-600 rounded-md">
                    <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/>
                    </svg>
                    Kanban
                </button>
                <a href="{{ route('tasks.wbs') }}" class="px-4 py-2 text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white rounded-md transition">
                    <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                    </svg>
                    WBS
                </a>
            </div>

            @if(Gate::allows('admin') || Gate::allows('team_lead'))
            <a href="{{ route('tasks.create') }}" class="inline-flex items-center px-4 py-2 bg-primary-600 hover:bg-primary-700 dark:bg-primary-500 dark:hover:bg-primary-600 text-white font-medium rounded-lg transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Create New Task
            </a>
            @endif
        </div>
    </div>

    <!-- Filters Section -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Filter by Project -->
            <div>
                <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2 block">Filter by Project</label>
                <select x-model="filterProject" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    <option value="all">All Projects</option>
                    @foreach($projects as $project)
                    <option value="{{ $project->id }}">{{ $project->title }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Filter by Priority -->
            <div>
                <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2 block">Filter by Priority</label>
                <select x-model="filterPriority" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    <option value="all">All Priorities</option>
                    <option value="low">Low</option>
                    <option value="medium">Medium</option>
                    <option value="high">High</option>
                    <option value="critical">Critical</option>
                </select>
            </div>

            <!-- Filter by Assignee -->
            <div>
                <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2 block">Filter by Assignee</label>
                <select x-model="filterAssignee" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    <option value="all">All Members</option>
                    @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <!-- Kanban Board -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6"
         @dragover.prevent
         @drop.prevent>

        <!-- To Do Column -->   <div data-status="todo" class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                    <span class="w-3 h-3 bg-gray-500 rounded-full"></span>
                    To Do
                    <span class="text-xs bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-400 px-2 py-1 rounded-full">
                        {{ $tasksByStatus['todo']->count() }}
                    </span>
                </h3>
            </div>
            <div class="space-y-3 min-h-[300px] drop-zone" data-drop-status="todo">
                @forelse($tasksByStatus['todo'] as $task)
                <div class="task-card bg-white dark:bg-gray-700 rounded-lg p-4 shadow-sm hover:shadow-md transition-all duration-300 cursor-grab active:cursor-grabbing border-l-4 border-gray-500"
                     draggable="true"
                     data-task-id="{{ $task->id }}"
                     data-project-id="{{ $task->project_id ?? 'null' }}"
                     data-priority="{{ $task->priority }}"
                     data-assignee-id="{{ $task->assigned_to ?? 'null' }}"
                     x-show="filterTask({{ $task->project_id ?? 'null' }}, '{{ $task->priority }}', {{ $task->assigned_to ?? 'null' }})"
                     x-transition>

                    <div class="flex items-start justify-between mb-2">
                        <h4 class="font-medium text-gray-900 dark:text-white text-sm leading-tight flex-1">
                            {{ $task->title }}
                        </h4>
                        @if($task->priority === 'critical')
                        <span class="text-xs bg-red-100 text-red-600 dark:bg-red-900 dark:text-red-300 px-2 py-1 rounded-full font-medium ml-2">
                            Critical
                        </span>
                        @elseif($task->priority === 'high')
                        <span class="text-xs bg-orange-100 text-orange-600 dark:bg-orange-900 dark:text-orange-300 px-2 py-1 rounded-full font-medium ml-2">
                            High
                        </span>
                        @elseif($task->priority === 'medium')
                        <span class="text-xs bg-yellow-100 text-yellow-600 dark:bg-yellow-900 dark:text-yellow-300 px-2 py-1 rounded-full font-medium ml-2">
                            Medium
                        </span>
                        @else
                        <span class="text-xs bg-green-100 text-green-600 dark:bg-green-900 dark:text-green-300 px-2 py-1 rounded-full font-medium ml-2">
                            Low
                        </span>
                        @endif
                    </div>

                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-3">
                        <svg class="w-3 h-3 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/>
                        </svg>
                        {{ $task->project->name ?? 'No Project' }}
                    </p>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            @if($task->assignee)
                            <div class="w-6 h-6 bg-primary-600 text-white rounded-full flex items-center justify-center text-xs font-medium" title="{{ $task->assignee->name }}">
                                {{ strtoupper(substr($task->assignee->name, 0, 2)) }}
                            </div>
                            <span class="text-xs text-gray-600 dark:text-gray-400">{{ $task->assignee->name }}</span>
                            @else
                            <span class="text-xs text-gray-400 dark:text-gray-500">Unassigned</span>
                            @endif
                        </div>
                        @if($task->due_date)
                        <span class="text-xs {{ \Carbon\Carbon::parse($task->due_date)->isPast() && $task->status !== 'completed' ? 'text-red-600 dark:text-red-400 font-medium' : 'text-gray-500 dark:text-gray-400' }} flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            {{ \Carbon\Carbon::parse($task->due_date)->format('M d') }}
                        </span>
                        @endif
                    </div>

                    <div class="mt-3 pt-3 border-t border-gray-200 dark:border-gray-600">
                        <a href="{{ route('tasks.show', $task) }}" class="text-xs text-primary-600 dark:text-primary-400 hover:underline">
                            View Details →
                        </a>
                    </div>
                </div>
                @empty
                <div class="empty-state text-center text-gray-400 dark:text-gray-500 text-sm py-12">
                    <svg class="w-12 h-12 mx-auto mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                    </svg>
                    No tasks to do
                </div>
                @endforelse
            </div>
        </div>

            <!-- In Progress Column -->
            <div class="bg-blue-50 dark:bg-gray-800 rounded-lg p-4">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                        <span class="w-3 h-3 bg-blue-500 rounded-full"></span>
                        In Progress
                        <span class="text-xs bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-300 px-2 py-1 rounded-full">
                            {{ $tasksByStatus['in-progress']->count() }}
                        </span>
                    </h3>
                </div>
                <div class="space-y-3 min-h-[200px] drop-zone" data-drop-status="in-progress">
                    @forelse($tasksByStatus['in-progress'] as $task)
                    <div class="task-card bg-white dark:bg-gray-700 rounded-lg p-4 shadow-sm hover:shadow-md transition-all duration-300 cursor-grab active:cursor-grabbing border-l-4 border-blue-500"
                         draggable="true"
                         data-task-id="{{ $task->id }}"
                         data-project-id="{{ $task->project_id }}"
                         data-priority="{{ $task->priority }}"
                         data-assignee-id="{{ $task->assigned_to }}">

                        <div class="flex items-start justify-between mb-2">
                            <h4 class="font-medium text-gray-900 dark:text-white text-sm leading-tight flex-1">
                                {{ $task->title }}
                            </h4>
                            @if($task->priority === 'critical')
                            <span class="text-xs bg-red-100 text-red-600 dark:bg-red-900 dark:text-red-300 px-2 py-1 rounded-full font-medium">
                                {{ ucfirst($task->priority) }}
                            </span>
                            @elseif($task->priority === 'high')
                            <span class="text-xs bg-orange-100 text-orange-600 dark:bg-orange-900 dark:text-orange-300 px-2 py-1 rounded-full font-medium">
                                {{ ucfirst($task->priority) }}
                            </span>
                            @elseif($task->priority === 'medium')
                            <span class="text-xs bg-yellow-100 text-yellow-600 dark:bg-yellow-900 dark:text-yellow-300 px-2 py-1 rounded-full font-medium">
                                {{ ucfirst($task->priority) }}
                            </span>
                            @else
                            <span class="text-xs bg-green-100 text-green-600 dark:bg-green-900 dark:text-green-300 px-2 py-1 rounded-full font-medium">
                                {{ ucfirst($task->priority) }}
                            </span>
                            @endif
                        </div>

                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-3">
                            {{ $task->project->name ?? 'No Project' }}
                        </p>

                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                @if($task->assignee)
                                <div class="w-6 h-6 bg-primary-600 text-white rounded-full flex items-center justify-center text-xs font-medium" title="{{ $task->assignee->name }}">
                                    {{ strtoupper(substr($task->assignee->name, 0, 2)) }}
                                </div>
                                @endif
                            </div>
                            @if($task->due_date)
                            <span class="text-xs text-gray-500 dark:text-gray-400 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                {{ \Carbon\Carbon::parse($task->due_date)->format('M d') }}
                            </span>
                            @endif
                        </div>

                        <div class="mt-3 pt-3 border-t border-gray-200 dark:border-gray-600">
                            <a href="{{ route('tasks.show', $task) }}" class="text-xs text-primary-600 dark:text-primary-400 hover:underline">
                                View Details →
                            </a>
                        </div>
                    </div>
                    @empty
                    <div class="empty-state text-center text-gray-400 dark:text-gray-500 text-sm py-8">
                        No tasks in progress
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- In Review Column -->
            <div class="bg-yellow-50 dark:bg-gray-800 rounded-lg p-4 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                        <span class="w-3 h-3 bg-yellow-500 rounded-full"></span>
                        Review
                        <span class="text-xs bg-yellow-100 dark:bg-yellow-900 text-yellow-600 dark:text-yellow-300 px-2 py-1 rounded-full">
                            {{ $tasksByStatus['review']->count() }}
                        </span>
                    </h3>
                </div>
                <div class="space-y-3 min-h-[200px] drop-zone" data-drop-status="review">
                    @forelse($tasksByStatus['review'] as $task)
                    <div class="task-card bg-white dark:bg-gray-700 rounded-lg p-4 shadow-sm hover:shadow-md transition-all duration-300 cursor-grab active:cursor-grabbing border-l-4 border-yellow-500"
                         draggable="true"
                         data-task-id="{{ $task->id }}"
                         data-project-id="{{ $task->project_id ?? 'null' }}"
                         data-priority="{{ $task->priority }}"
                         data-assignee-id="{{ $task->assigned_to ?? 'null' }}"
                         x-show="filterTask({{ $task->project_id ?? 'null' }}, '{{ $task->priority }}', {{ $task->assigned_to ?? 'null' }})"
                         x-transition>
                        <div class="flex items-start justify-between mb-2">
                            <h4 class="font-medium text-gray-900 dark:text-white text-sm leading-tight flex-1">
                                {{ $task->title }}
                            </h4>
                            @if($task->priority === 'critical')
                            <span class="text-xs bg-red-100 text-red-600 dark:bg-red-900 dark:text-red-300 px-2 py-1 rounded-full font-medium">
                                {{ ucfirst($task->priority) }}
                            </span>
                            @elseif($task->priority === 'high')
                            <span class="text-xs bg-orange-100 text-orange-600 dark:bg-orange-900 dark:text-orange-300 px-2 py-1 rounded-full font-medium">
                                {{ ucfirst($task->priority) }}
                            </span>
                            @elseif($task->priority === 'medium')
                            <span class="text-xs bg-yellow-100 text-yellow-600 dark:bg-yellow-900 dark:text-yellow-300 px-2 py-1 rounded-full font-medium">
                                {{ ucfirst($task->priority) }}
                            </span>
                            @else
                            <span class="text-xs bg-green-100 text-green-600 dark:bg-green-900 dark:text-green-300 px-2 py-1 rounded-full font-medium">
                                {{ ucfirst($task->priority) }}
                            </span>
                            @endif
                        </div>

                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-3">
                            {{ $task->project->name ?? 'No Project' }}
                        </p>

                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                @if($task->assignee)
                                <div class="w-6 h-6 bg-primary-600 text-white rounded-full flex items-center justify-center text-xs font-medium" title="{{ $task->assignee->name }}">
                                    {{ strtoupper(substr($task->assignee->name, 0, 2)) }}
                                </div>
                                @endif
                            </div>
                            @if($task->due_date)
                            <span class="text-xs text-gray-500 dark:text-gray-400 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                {{ \Carbon\Carbon::parse($task->due_date)->format('M d') }}
                            </span>
                            @endif
                        </div>

                        <div class="mt-3 pt-3 border-t border-gray-200 dark:border-gray-600">
                            <a href="{{ route('tasks.show', $task) }}" class="text-xs text-primary-600 dark:text-primary-400 hover:underline">
                                View Details →
                            </a>
                        </div>
                    </div>
                    @empty
                    <div class="empty-state text-center text-gray-400 dark:text-gray-500 text-sm py-8">
                        No tasks in review
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Completed Column -->
            <div class="bg-green-50 dark:bg-gray-800 rounded-lg p-4 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                        <span class="w-3 h-3 bg-green-500 rounded-full"></span>
                        Completed
                        <span class="text-xs bg-green-100 dark:bg-green-900 text-green-600 dark:text-green-300 px-2 py-1 rounded-full">
                            {{ $tasksByStatus['completed']->count() }}
                        </span>
                    </h3>
                </div>
                <div class="space-y-3 min-h-[200px] drop-zone" data-drop-status="completed">
                    @forelse($tasksByStatus['completed'] as $task)
                    <div class="task-card bg-white dark:bg-gray-700 rounded-lg p-4 shadow-sm hover:shadow-md transition-all duration-300 cursor-grab active:cursor-grabbing border-l-4 border-green-500"
                         draggable="true"
                         data-task-id="{{ $task->id }}"
                         data-project-id="{{ $task->project_id ?? 'null' }}"
                         data-priority="{{ $task->priority }}"
                         data-assignee-id="{{ $task->assigned_to ?? 'null' }}"
                         x-show="filterTask({{ $task->project_id ?? 'null' }}, '{{ $task->priority }}', {{ $task->assigned_to ?? 'null' }})"
                         x-transition>
                        <div class="flex items-start justify-between mb-2">
                            <h4 class="font-medium text-gray-900 dark:text-white text-sm leading-tight flex-1">
                                {{ $task->title }}
                            </h4>
                            @if($task->priority === 'critical')
                            <span class="text-xs bg-red-100 text-red-600 dark:bg-red-900 dark:text-red-300 px-2 py-1 rounded-full font-medium">
                                {{ ucfirst($task->priority) }}
                            </span>
                            @elseif($task->priority === 'high')
                            <span class="text-xs bg-orange-100 text-orange-600 dark:bg-orange-900 dark:text-orange-300 px-2 py-1 rounded-full font-medium">
                                {{ ucfirst($task->priority) }}
                            </span>
                            @elseif($task->priority === 'medium')
                            <span class="text-xs bg-yellow-100 text-yellow-600 dark:bg-yellow-900 dark:text-yellow-300 px-2 py-1 rounded-full font-medium">
                                {{ ucfirst($task->priority) }}
                            </span>
                            @else
                            <span class="text-xs bg-green-100 text-green-600 dark:bg-green-900 dark:text-green-300 px-2 py-1 rounded-full font-medium">
                                {{ ucfirst($task->priority) }}
                            </span>
                            @endif
                        </div>

                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-3">
                            {{ $task->project->name ?? 'No Project' }}
                        </p>

                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                @if($task->assignee)
                                <div class="w-6 h-6 bg-primary-600 text-white rounded-full flex items-center justify-center text-xs font-medium" title="{{ $task->assignee->name }}">
                                    {{ strtoupper(substr($task->assignee->name, 0, 2)) }}
                                </div>
                                @endif
                            </div>
                            @if($task->completed_at)
                            <span class="text-xs text-green-600 dark:text-green-400 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                {{ \Carbon\Carbon::parse($task->completed_at)->format('M d') }}
                            </span>
                            @endif
                        </div>

                        <div class="mt-3 pt-3 border-t border-gray-200 dark:border-gray-600">
                            <a href="{{ route('tasks.show', $task) }}" class="text-xs text-primary-600 dark:text-primary-400 hover:underline">
                                View Details →
                            </a>
                        </div>
                    </div>
                    @empty
                    <div class="empty-state text-center text-gray-400 dark:text-gray-500 text-sm py-8">
                        No completed tasks
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>
</div>

@push('scripts')
<script>
function kanbanBoard() {
    return {
        filterProject: 'all',
        filterPriority: 'all',
        filterAssignee: 'all',

        filterTask(projectId, priority, assigneeId) {
            const projectMatch = this.filterProject === 'all' || this.filterProject == projectId;
            const priorityMatch = this.filterPriority === 'all' || this.filterPriority === priority;
            const assigneeMatch = this.filterAssignee === 'all' || this.filterAssignee == assigneeId;
            return projectMatch && priorityMatch && assigneeMatch;
        }
    };
}

// Vanilla JavaScript Drag & Drop (no Alpine.js dependency)
document.addEventListener('DOMContentLoaded', function() {
    let draggedElement = null;
    let draggedTaskId = null;

    // Setup drag events for all task cards
    document.querySelectorAll('.task-card').forEach(card => {
        card.addEventListener('dragstart', function(e) {
            draggedElement = this;
            draggedTaskId = this.getAttribute('data-task-id');
            // Don't use opacity - use different visual feedback
            this.style.cursor = 'grabbing';
            // Add a subtle scale effect
            this.style.transform = 'scale(0.95)';
            console.log('Drag started:', draggedTaskId);

            // Set drag image to prevent default ghost
            e.dataTransfer.effectAllowed = 'move';
        });

        card.addEventListener('dragend', function(e) {
            this.style.cursor = 'grab';
            this.style.transform = 'scale(1)';
        });
    });

    // Setup drop zones for all columns with better positioning
    document.querySelectorAll('.drop-zone').forEach(dropZone => {
        dropZone.addEventListener('dragover', function(e) {
            e.preventDefault();
            e.dataTransfer.dropEffect = 'move';
        });

        dropZone.addEventListener('dragenter', function(e) {
            e.preventDefault();
        });

        dropZone.addEventListener('drop', function(e) {
            e.preventDefault();
            e.stopPropagation();

            const newStatus = this.getAttribute('data-drop-status');
            const dropZoneElement = this;

            if (!draggedTaskId || !draggedElement) {
                console.log('No task to drop');
                return;
            }

            console.log('Dropping task:', draggedTaskId, 'to status:', newStatus);

            // Reset styles
            draggedElement.style.cursor = 'grab';
            draggedElement.style.transform = 'scale(1)';

            // Add smooth transition
            draggedElement.style.transition = 'all 0.3s ease';

            // Update task status via AJAX
            fetch(`/tasks/${draggedTaskId}/update-status`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ status: newStatus })
            })
            .then(response => {
                console.log('Response status:', response.status);
                if (!response.ok) {
                    return response.text().then(text => {
                        console.error('Response text:', text);
                        throw new Error(`HTTP error! status: ${response.status}`);
                    });
                }
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);
                if (data.success) {
                    // Get position to insert based on mouse Y position
                    const afterElement = getDragAfterElement(dropZoneElement, e.clientY);

                    // Move element to correct position in new column
                    if (afterElement == null) {
                        dropZoneElement.appendChild(draggedElement);
                    } else {
                        dropZoneElement.insertBefore(draggedElement, afterElement);
                    }

                    // Update border color with smooth transition
                    draggedElement.classList.remove('border-gray-500', 'border-blue-500', 'border-yellow-500', 'border-green-500');
                    if (newStatus === 'todo') {
                        draggedElement.classList.add('border-gray-500');
                    } else if (newStatus === 'in-progress') {
                        draggedElement.classList.add('border-blue-500');
                    } else if (newStatus === 'review') {
                        draggedElement.classList.add('border-yellow-500');
                    } else if (newStatus === 'completed') {
                        draggedElement.classList.add('border-green-500');
                    }

                    // Force update counters after DOM manipulation
                    setTimeout(() => {
                        updateAllCounters();
                    }, 50);

                    // Show success toast
                    showToast('Task moved successfully!', 'success');

                    // Reset
                    draggedTaskId = null;
                    draggedElement = null;
                } else {
                    draggedTaskId = null;
                    draggedElement = null;
                    showToast('Error: ' + (data.error || 'Unknown error'), 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                draggedTaskId = null;
                draggedElement = null;
                showToast('Error updating task: ' + error.message, 'error');
            });
        });
    });    // Get element to insert after based on Y position
    function getDragAfterElement(container, y) {
        const draggableElements = [...container.querySelectorAll('.task-card:not(.dragging)')];

        return draggableElements.reduce((closest, child) => {
            const box = child.getBoundingClientRect();
            const offset = y - box.top - box.height / 2;

            if (offset < 0 && offset > closest.offset) {
                return { offset: offset, element: child };
            } else {
                return closest;
            }
        }, { offset: Number.NEGATIVE_INFINITY }).element;
    }

    function updateAllCounters() {
        const columns = ['todo', 'in-progress', 'review', 'completed'];
        columns.forEach(status => {
            const column = document.querySelector(`[data-status="${status}"]`);
            if (column) {
                const dropZone = column.querySelector('.drop-zone');
                const taskCount = dropZone ? dropZone.querySelectorAll('.task-card').length : 0;
                const badge = column.querySelector('.text-xs.px-2.py-1.rounded-full');
                if (badge) {
                    badge.textContent = taskCount;
                }

                // Toggle empty state visibility
                const emptyState = dropZone ? dropZone.querySelector('.empty-state') : null;
                if (emptyState) {
                    if (taskCount > 0) {
                        emptyState.style.display = 'none';
                    } else {
                        emptyState.style.display = 'block';
                    }
                }
            }
        });
    }

    function showToast(message, type = 'success') {
        const bgColor = type === 'success' ? 'bg-green-500' : 'bg-red-500';
        const toast = document.createElement('div');
        toast.className = `fixed top-4 right-4 ${bgColor} text-white px-6 py-3 rounded-lg shadow-xl z-50 flex items-center gap-2`;
        toast.innerHTML = `
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            <span>${message}</span>
        `;
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 2000);
    }
});
</script>
@endpush
</div>
@endsection
