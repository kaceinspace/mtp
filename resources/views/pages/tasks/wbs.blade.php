@extends('layouts.dashboard')

@section('title', 'WBS View - Tasks')
@section('page-title', 'Work Breakdown Structure')

@section('content')
<div class="space-y-6" x-data="wbsManager()">
    <!-- Header with Action Button -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                Work Breakdown Structure
            </h2>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Hierarchical task breakdown and planning</p>
        </div>
        <div class="flex items-center gap-3">
            <!-- View Toggle -->
            <div class="flex bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-1">
                <a href="{{ route('tasks.index') }}" class="px-4 py-2 text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white rounded-md transition">
                    <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                    </svg>
                    List
                </a>
                <a href="{{ route('tasks.kanban') }}" class="px-4 py-2 text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white rounded-md transition">
                    <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/>
                    </svg>
                    Kanban
                </a>
                <button class="px-4 py-2 text-sm font-medium text-white bg-primary-600 rounded-md">
                    <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                    </svg>
                    WBS
                </button>
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

    <!-- WBS Tree View -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
        <div class="p-6">
            <!-- Header Controls -->
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Task Hierarchy</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                        Click to expand/collapse • Hover for actions
                    </p>
                </div>
                <div class="flex gap-2">
                    <button @click="expandAll()" class="text-sm px-3 py-1.5 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                        <i class="fas fa-expand-alt mr-1"></i>Expand All
                    </button>
                    <button @click="collapseAll()" class="text-sm px-3 py-1.5 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                        <i class="fas fa-compress-alt mr-1"></i>Collapse All
                    </button>
                </div>
            </div>

            <!-- Task Tree -->
            <div class="space-y-2">
                @forelse($tasks as $task)
                    @include('pages.tasks.wbs-item', ['task' => $task, 'level' => 0])
                @empty
                    <div class="text-center py-12">
                        <i class="fas fa-sitemap text-5xl text-gray-400 dark:text-gray-600 mb-4"></i>
                        <p class="text-gray-600 dark:text-gray-400">No tasks yet</p>
                        <p class="text-sm text-gray-500 dark:text-gray-500 mt-2">Create your first task to start building the WBS</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function wbsManager() {
        return {
            expandAll() {
                document.querySelectorAll('[data-task-collapse]').forEach(el => {
                    el.style.display = 'block';
                });
                document.querySelectorAll('[data-expand-icon]').forEach(el => {
                    el.classList.remove('fa-chevron-right');
                    el.classList.add('fa-chevron-down');
                });
            },

            collapseAll() {
                document.querySelectorAll('[data-task-collapse]').forEach(el => {
                    el.style.display = 'none';
                });
                document.querySelectorAll('[data-expand-icon]').forEach(el => {
                    el.classList.remove('fa-chevron-down');
                    el.classList.add('fa-chevron-right');
                });
            }
        }
    }

    // Toggle task children visibility
    function toggleTask(taskId) {
        const children = document.getElementById(`children-${taskId}`);
        const icon = document.getElementById(`icon-${taskId}`);

        if (children.style.display === 'none' || children.style.display === '') {
            children.style.display = 'block';
            icon.classList.remove('fa-chevron-right');
            icon.classList.add('fa-chevron-down');
        } else {
            children.style.display = 'none';
            icon.classList.remove('fa-chevron-down');
            icon.classList.add('fa-chevron-right');
        }
    }
</script>
@endpush
@endsection
