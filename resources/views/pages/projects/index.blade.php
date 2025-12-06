@extends('layouts.dashboard')

@section('title', 'Projects')
@section('page-title', 'Projects Management')

@section('content')
<div class="space-y-6" x-data="{ activeStatus: 'all' }">
    <!-- Header with Action Button -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">All Projects</h2>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Manage and track all your projects</p>
        </div>
        @if(Gate::allows('admin') || Gate::allows('team_lead'))
        <a href="{{ route('projects.create') }}" class="inline-flex items-center px-4 py-2 bg-primary-600 hover:bg-primary-700 dark:bg-primary-500 dark:hover:bg-primary-600 text-white font-medium rounded-lg transition">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Create New Project
        </a>
        @endif
    </div>

    <!-- Filter Tabs -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4">
        <div class="flex flex-wrap gap-2">
            <button @click="activeStatus = 'all'"
                    :class="activeStatus === 'all' ? 'bg-primary-100 dark:bg-primary-900/30 text-primary-700 dark:text-primary-300' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600'"
                    class="px-4 py-2 rounded-lg font-medium transition">
                All Projects
            </button>
            <button @click="activeStatus = 'planning'"
                    :class="activeStatus === 'planning' ? 'bg-primary-100 dark:bg-primary-900/30 text-primary-700 dark:text-primary-300' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600'"
                    class="px-4 py-2 rounded-lg font-medium transition">
                Planning
            </button>
            <button @click="activeStatus = 'ongoing'"
                    :class="activeStatus === 'ongoing' ? 'bg-primary-100 dark:bg-primary-900/30 text-primary-700 dark:text-primary-300' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600'"
                    class="px-4 py-2 rounded-lg font-medium transition">
                Ongoing
            </button>
            <button @click="activeStatus = 'completed'"
                    :class="activeStatus === 'completed' ? 'bg-primary-100 dark:bg-primary-900/30 text-primary-700 dark:text-primary-300' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600'"
                    class="px-4 py-2 rounded-lg font-medium transition">
                Completed
            </button>
            <button @click="activeStatus = 'on-hold'"
                    :class="activeStatus === 'on-hold' ? 'bg-primary-100 dark:bg-primary-900/30 text-primary-700 dark:text-primary-300' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600'"
                    class="px-4 py-2 rounded-lg font-medium transition">
                On Hold
            </button>
        </div>
    </div>

    <!-- Projects Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($projects as $project)
        <div x-show="activeStatus === 'all' || activeStatus === '{{ $project->status }}'"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 transform scale-95"
             x-transition:enter-end="opacity-100 transform scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 transform scale-100"
             x-transition:leave-end="opacity-0 transform scale-95"
             class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-md transition overflow-hidden">
            <!-- Project Header -->
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                            <a href="{{ route('projects.show', $project) }}" class="hover:text-primary-600 dark:hover:text-primary-400">
                                {{ $project->title }}
                            </a>
                        </h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-2">
                            {{ $project->description ?? 'No description provided' }}
                        </p>
                    </div>

                    <!-- Priority Badge -->
                    <span class="ml-3 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                        @if($project->priority === 'critical') bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300
                        @elseif($project->priority === 'high') bg-orange-100 dark:bg-orange-900/30 text-orange-800 dark:text-orange-300
                        @elseif($project->priority === 'medium') bg-accent-100 dark:bg-accent-900/30 text-accent-800 dark:text-accent-300
                        @else bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300
                        @endif">
                        {{ ucfirst($project->priority) }}
                    </span>
                </div>

                <!-- Status Badge -->
                <div class="mt-3">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                        @if($project->status === 'completed') bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300
                        @elseif($project->status === 'ongoing') bg-primary-100 dark:bg-primary-900/30 text-primary-800 dark:text-primary-300
                        @elseif($project->status === 'on-hold') bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300
                        @else bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300
                        @endif">
                        {{ ucfirst(str_replace('-', ' ', $project->status)) }}
                    </span>
                </div>
            </div>

            <!-- Project Info -->
            <div class="p-6 space-y-3">
                @if($project->department || $project->teamInfo)
                <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    {{ $project->department ?? 'No department' }} - {{ $project->teamInfo->name ?? 'No team' }}
                </div>
                @endif

                <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    {{ $project->start_date ? $project->start_date->format('M d, Y') : 'No start date' }} -
                    {{ $project->end_date ? $project->end_date->format('M d, Y') : 'No end date' }}
                </div>

                <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    Created by {{ $project->creator->name }}
                </div>

                <!-- Team Members -->
                <div class="flex items-center justify-between pt-3 border-t border-gray-200 dark:border-gray-700">
                    <div class="flex -space-x-2">
                        @forelse($project->members->take(3) as $member)
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($member->name) }}&background=3b82f6&color=fff"
                             alt="{{ $member->name }}"
                             class="w-8 h-8 rounded-full border-2 border-white dark:border-gray-800"
                             title="{{ $member->name }}">
                        @empty
                        <span class="text-sm text-gray-500 dark:text-gray-400">No members</span>
                        @endforelse
                        @if($project->members->count() > 3)
                        <div class="w-8 h-8 rounded-full bg-gray-200 dark:bg-gray-700 border-2 border-white dark:border-gray-800 flex items-center justify-center">
                            <span class="text-xs font-medium text-gray-600 dark:text-gray-400">+{{ $project->members->count() - 3 }}</span>
                        </div>
                        @endif
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center space-x-2">
                        <a href="{{ route('projects.show', $project) }}" class="p-2 text-gray-600 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition" title="View">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </a>
                        @if(Gate::allows('admin') || Gate::allows('team_lead'))
                        <a href="{{ route('projects.edit', $project) }}" class="p-2 text-gray-600 dark:text-gray-400 hover:text-accent-600 dark:hover:text-accent-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition" title="Edit">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-12 text-center">
                <svg class="w-16 h-16 text-gray-300 dark:text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No projects yet</h3>
                <p class="text-gray-600 dark:text-gray-400 mb-4">Get started by creating your first project</p>
                @if(Gate::allows('admin') || Gate::allows('team_lead'))
                <a href="{{ route('projects.create') }}" class="inline-flex items-center px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white font-medium rounded-lg transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Create Project
                </a>
                @endif
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($projects->hasPages())
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4">
        {{ $projects->links() }}
    </div>
    @endif
</div>
@endsection
