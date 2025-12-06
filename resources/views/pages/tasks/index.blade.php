@extends('layouts.dashboard')

@section('title', 'Tasks')
@section('page-title', 'Tasks Management')

@section('content')
<div class="space-y-6" x-data="{ activeStatus: 'all', activePriority: 'all' }">
    <!-- Header with Action Button -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                @if(Gate::allows('admin') || Gate::allows('team_lead'))
                    All Tasks
                @else
                    My Tasks
                @endif
            </h2>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Manage and track all your tasks</p>
        </div>
        <div class="flex items-center gap-3">
            <!-- View Toggle -->
            <div class="flex bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-1">
                <button class="px-4 py-2 text-sm font-medium text-white bg-primary-600 rounded-md">
                    <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                    </svg>
                    List
                </button>
                <a href="{{ route('tasks.kanban') }}" class="px-4 py-2 text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white rounded-md transition">
                    <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/>
                    </svg>
                    Kanban
                </a>
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
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Create New Task
            </a>
            @endif
        </div>
    </div>

    <!-- Filter Tabs -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4">
        <div class="space-y-4">
            <!-- Status Filter -->
            <div>
                <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2 block">Filter by Status</label>
                <div class="flex flex-wrap gap-2">
                    <button @click="activeStatus = 'all'"
                            :class="activeStatus === 'all' ? 'bg-primary-100 dark:bg-primary-900/30 text-primary-700 dark:text-primary-300' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600'"
                            class="px-4 py-2 rounded-lg font-medium transition text-sm">
                        All Tasks
                    </button>
                    <button @click="activeStatus = 'todo'"
                            :class="activeStatus === 'todo' ? 'bg-primary-100 dark:bg-primary-900/30 text-primary-700 dark:text-primary-300' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600'"
                            class="px-4 py-2 rounded-lg font-medium transition text-sm">
                        To Do
                    </button>
                    <button @click="activeStatus = 'in-progress'"
                            :class="activeStatus === 'in-progress' ? 'bg-primary-100 dark:bg-primary-900/30 text-primary-700 dark:text-primary-300' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600'"
                            class="px-4 py-2 rounded-lg font-medium transition text-sm">
                        In Progress
                    </button>
                    <button @click="activeStatus = 'review'"
                            :class="activeStatus === 'review' ? 'bg-primary-100 dark:bg-primary-900/30 text-primary-700 dark:text-primary-300' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600'"
                            class="px-4 py-2 rounded-lg font-medium transition text-sm">
                        Review
                    </button>
                    <button @click="activeStatus = 'completed'"
                            :class="activeStatus === 'completed' ? 'bg-primary-100 dark:bg-primary-900/30 text-primary-700 dark:text-primary-300' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600'"
                            class="px-4 py-2 rounded-lg font-medium transition text-sm">
                        Completed
                    </button>
                </div>
            </div>

            <!-- Priority Filter -->
            <div>
                <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2 block">Filter by Priority</label>
                <div class="flex flex-wrap gap-2">
                    <button @click="activePriority = 'all'"
                            :class="activePriority === 'all' ? 'bg-primary-100 dark:bg-primary-900/30 text-primary-700 dark:text-primary-300' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600'"
                            class="px-4 py-2 rounded-lg font-medium transition text-sm">
                        All Priorities
                    </button>
                    <button @click="activePriority = 'critical'"
                            :class="activePriority === 'critical' ? 'bg-primary-100 dark:bg-primary-900/30 text-primary-700 dark:text-primary-300' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600'"
                            class="px-4 py-2 rounded-lg font-medium transition text-sm">
                        Critical
                    </button>
                    <button @click="activePriority = 'high'"
                            :class="activePriority === 'high' ? 'bg-primary-100 dark:bg-primary-900/30 text-primary-700 dark:text-primary-300' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600'"
                            class="px-4 py-2 rounded-lg font-medium transition text-sm">
                        High
                    </button>
                    <button @click="activePriority = 'medium'"
                            :class="activePriority === 'medium' ? 'bg-primary-100 dark:bg-primary-900/30 text-primary-700 dark:text-primary-300' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600'"
                            class="px-4 py-2 rounded-lg font-medium transition text-sm">
                        Medium
                    </button>
                    <button @click="activePriority = 'low'"
                            :class="activePriority === 'low' ? 'bg-primary-100 dark:bg-primary-900/30 text-primary-700 dark:text-primary-300' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600'"
                            class="px-4 py-2 rounded-lg font-medium transition text-sm">
                        Low
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Tasks Table -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Task</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Project</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Assigned To</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Priority</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Due Date</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($tasks as $task)
                    <tr x-show="(activeStatus === 'all' || activeStatus === '{{ $task->status }}') && (activePriority === 'all' || activePriority === '{{ $task->priority }}')"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0"
                        x-transition:enter-end="opacity-100"
                        class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                        <td class="px-6 py-4">
                            <div class="flex flex-col">
                                <a href="{{ route('tasks.show', $task) }}" class="text-sm font-medium text-gray-900 dark:text-white hover:text-primary-600 dark:hover:text-primary-400">
                                    {{ $task->title }}
                                </a>
                                @if($task->description)
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 line-clamp-1">
                                    {{ $task->description }}
                                </p>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <a href="{{ route('projects.show', $task->project) }}" class="text-sm text-primary-600 dark:text-primary-400 hover:underline">
                                {{ $task->project->title }}
                            </a>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($task->assignee)
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-8 w-8">
                                        <div class="h-8 w-8 rounded-full bg-primary-100 dark:bg-primary-900/30 flex items-center justify-center">
                                            <span class="text-xs font-medium text-primary-700 dark:text-primary-300">
                                                {{ substr($task->assignee->name, 0, 2) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $task->assignee->name }}
                                        </div>
                                    </div>
                                </div>
                            @else
                                <span class="text-sm text-gray-500 dark:text-gray-400">Unassigned</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($task->status === 'completed') bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300
                                @elseif($task->status === 'in-progress') bg-primary-100 dark:bg-primary-900/30 text-primary-800 dark:text-primary-300
                                @elseif($task->status === 'review') bg-accent-100 dark:bg-accent-900/30 text-accent-800 dark:text-accent-300
                                @else bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300
                                @endif">
                                {{ ucfirst(str_replace('-', ' ', $task->status)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($task->priority === 'critical') bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300
                                @elseif($task->priority === 'high') bg-orange-100 dark:bg-orange-900/30 text-orange-800 dark:text-orange-300
                                @elseif($task->priority === 'medium') bg-accent-100 dark:bg-accent-900/30 text-accent-800 dark:text-accent-300
                                @else bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300
                                @endif">
                                {{ ucfirst($task->priority) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                            @if($task->due_date)
                                @php
                                    $isOverdue = $task->due_date->isPast() && $task->status !== 'completed';
                                @endphp
                                <span class="{{ $isOverdue ? 'text-red-600 dark:text-red-400 font-medium' : '' }}">
                                    {{ $task->due_date->format('M d, Y') }}
                                    @if($isOverdue)
                                        <span class="text-xs">(Overdue)</span>
                                    @endif
                                </span>
                            @else
                                <span class="text-gray-500 dark:text-gray-400">No due date</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                            <a href="{{ route('tasks.show', $task) }}" class="text-primary-600 dark:text-primary-400 hover:text-primary-900 dark:hover:text-primary-300">
                                View
                            </a>
                            @if(Gate::allows('admin') || Gate::allows('team_lead') || $task->assigned_to === auth()->id())
                            <a href="{{ route('tasks.edit', $task) }}" class="text-accent-600 dark:text-accent-400 hover:text-accent-900 dark:hover:text-accent-300">
                                Edit
                            </a>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <svg class="w-12 h-12 text-gray-400 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                                <p class="text-gray-500 dark:text-gray-400 text-lg font-medium">No tasks found</p>
                                <p class="text-gray-400 dark:text-gray-500 text-sm mt-1">Get started by creating a new task</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($tasks->hasPages())
    <div class="flex justify-center">
        {{ $tasks->links() }}
    </div>
    @endif
</div>
@endsection
