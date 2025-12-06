@extends('layouts.dashboard')

@section('title', 'Teams')
@section('page-title', 'Teams Management')

@section('content')
<div class="space-y-6" x-data="{ activeStatus: 'all' }">
    <!-- Header with Action Button -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                @if(Gate::allows('admin'))
                    All Teams
                @else
                    My Teams
                @endif
            </h2>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Manage and collaborate with your teams</p>
        </div>
        @if(Gate::allows('admin') || Gate::allows('team_lead'))
        <a href="{{ route('teams.create') }}" class="inline-flex items-center px-4 py-2 bg-primary-600 hover:bg-primary-700 dark:bg-primary-500 dark:hover:bg-primary-600 text-white font-medium rounded-lg transition">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Create New Team
        </a>
        @endif
    </div>

    <!-- Filter Tabs -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4">
        <div class="flex flex-wrap gap-2">
            <button @click="activeStatus = 'all'"
                    :class="activeStatus === 'all' ? 'bg-primary-100 dark:bg-primary-900/30 text-primary-700 dark:text-primary-300' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600'"
                    class="px-4 py-2 rounded-lg font-medium transition">
                All Teams
            </button>
            <button @click="activeStatus = 'active'"
                    :class="activeStatus === 'active' ? 'bg-primary-100 dark:bg-primary-900/30 text-primary-700 dark:text-primary-300' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600'"
                    class="px-4 py-2 rounded-lg font-medium transition">
                Active
            </button>
            <button @click="activeStatus = 'inactive'"
                    :class="activeStatus === 'inactive' ? 'bg-primary-100 dark:bg-primary-900/30 text-primary-700 dark:text-primary-300' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600'"
                    class="px-4 py-2 rounded-lg font-medium transition">
                Inactive
            </button>
        </div>
    </div>

    <!-- Teams Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($teams as $team)
        <div x-show="activeStatus === 'all' || activeStatus === '{{ $team->status }}'"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 transform scale-95"
             x-transition:enter-end="opacity-100 transform scale-100"
             class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-md transition overflow-hidden">

            <!-- Team Header -->
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-start justify-between mb-3">
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">
                            <a href="{{ route('teams.show', $team) }}" class="hover:text-primary-600 dark:hover:text-primary-400">
                                {{ $team->name }}
                            </a>
                        </h3>
                        @if($team->description)
                        <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-2">
                            {{ $team->description }}
                        </p>
                        @endif
                    </div>

                    <!-- Status Badge -->
                    <span class="ml-3 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                        {{ $team->status === 'active' ? 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300' : 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300' }}">
                        {{ ucfirst($team->status) }}
                    </span>
                </div>

                @if($team->department)
                <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    {{ $team->department }}
                </div>
                @endif
            </div>

            <!-- Team Info -->
            <div class="p-6 space-y-3">
                <!-- Team Lead -->
                @if($team->teamLead)
                <div class="flex items-center">
                    <div class="flex-shrink-0 h-10 w-10">
                        <div class="h-10 w-10 rounded-full bg-primary-100 dark:bg-primary-900/30 flex items-center justify-center">
                            <span class="text-sm font-medium text-primary-700 dark:text-primary-300">
                                {{ substr($team->teamLead->name, 0, 2) }}
                            </span>
                        </div>
                    </div>
                    <div class="ml-3">
                        <p class="text-xs text-gray-500 dark:text-gray-400">Team Lead</p>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $team->teamLead->name }}</p>
                    </div>
                </div>
                @endif

                <!-- Team Members -->
                <div class="flex items-center justify-between pt-3 border-t border-gray-200 dark:border-gray-700">
                    <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        {{ $team->members->count() }} members
                    </div>

                    <!-- Member Avatars -->
                    @if($team->members->count() > 0)
                    <div class="flex -space-x-2">
                        @foreach($team->members->take(3) as $member)
                        <div class="h-8 w-8 rounded-full bg-accent-100 dark:bg-accent-900/30 border-2 border-white dark:border-gray-800 flex items-center justify-center"
                             title="{{ $member->name }}">
                            <span class="text-xs font-medium text-accent-700 dark:text-accent-300">
                                {{ substr($member->name, 0, 2) }}
                            </span>
                        </div>
                        @endforeach
                        @if($team->members->count() > 3)
                        <div class="h-8 w-8 rounded-full bg-gray-100 dark:bg-gray-700 border-2 border-white dark:border-gray-800 flex items-center justify-center">
                            <span class="text-xs font-medium text-gray-600 dark:text-gray-400">
                                +{{ $team->members->count() - 3 }}
                            </span>
                        </div>
                        @endif
                    </div>
                    @endif
                </div>
            </div>

            <!-- Actions -->
            <div class="px-6 py-3 bg-gray-50 dark:bg-gray-900/50 flex items-center justify-between">
                <a href="{{ route('teams.show', $team) }}"
                   class="text-sm text-primary-600 dark:text-primary-400 hover:text-primary-900 dark:hover:text-primary-300 font-medium">
                    View Details →
                </a>
                @if(Gate::allows('admin') || (Gate::allows('team_lead') && $team->team_lead_id === auth()->id()))
                <a href="{{ route('teams.edit', $team) }}"
                   class="text-sm text-accent-600 dark:text-accent-400 hover:text-accent-900 dark:hover:text-accent-300 font-medium">
                    Edit
                </a>
                @endif
            </div>
        </div>
        @empty
        <div class="col-span-full">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-12">
                <div class="flex flex-col items-center justify-center">
                    <svg class="w-16 h-16 text-gray-400 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <p class="text-gray-500 dark:text-gray-400 text-lg font-medium">No teams found</p>
                    <p class="text-gray-400 dark:text-gray-500 text-sm mt-1">Get started by creating a new team</p>
                </div>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($teams->hasPages())
    <div class="flex justify-center">
        {{ $teams->links() }}
    </div>
    @endif
</div>
@endsection
