@extends('layouts.dashboard')

@section('title', $team->name)
@section('page-title', 'Team Details')

@section('content')
<div class="space-y-6">
    <!-- Team Header -->
    <div class="bg-gradient-to-r from-primary-600 to-primary-800 dark:from-primary-800 dark:to-primary-950 rounded-lg shadow-lg p-6 text-white">
        <div class="flex items-start justify-between">
            <div class="flex-1">
                <div class="flex items-center space-x-3 mb-2">
                    <h1 class="text-3xl font-bold">{{ $team->name }}</h1>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                        {{ $team->status === 'active' ? 'bg-green-500/20 text-green-100' : 'bg-gray-500/20 text-gray-100' }}">
                        {{ ucfirst($team->status) }}
                    </span>
                </div>
                @if($team->description)
                <p class="text-primary-100 dark:text-primary-200 text-lg">{{ $team->description }}</p>
                @endif
                <div class="flex items-center space-x-6 mt-4 text-sm text-primary-100 dark:text-primary-200">
                    @if($team->department)
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        {{ $team->department }}
                    </div>
                    @endif
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        {{ $team->members->count() }} members
                    </div>
                </div>
            </div>

            <div class="flex items-center space-x-2">
                @if(Gate::allows('admin') || (Gate::allows('team_lead') && $team->team_lead_id === auth()->id()))
                <a href="{{ route('teams.edit', $team) }}"
                    class="px-4 py-2 bg-white/10 hover:bg-white/20 text-white rounded-lg transition flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit
                </a>
                @endif
                <a href="{{ route('teams.index') }}"
                    class="px-4 py-2 bg-white/10 hover:bg-white/20 text-white rounded-lg transition">
                    Back to Teams
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Team Lead Section -->
            @if($team->teamLead)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Team Lead</h3>
                <div class="flex items-center space-x-4">
                    <div class="flex-shrink-0">
                        <div class="h-16 w-16 rounded-full bg-primary-100 dark:bg-primary-900/30 flex items-center justify-center">
                            <span class="text-xl font-medium text-primary-700 dark:text-primary-300">
                                {{ substr($team->teamLead->name, 0, 2) }}
                            </span>
                        </div>
                    </div>
                    <div class="flex-1">
                        <h4 class="text-lg font-medium text-gray-900 dark:text-white">{{ $team->teamLead->name }}</h4>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $team->teamLead->email }}</p>
                        @if($team->teamLead->phone)
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $team->teamLead->phone }}</p>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            <!-- Team Members List -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Team Members ({{ $team->members->count() }})</h3>

                @if($team->members->count() > 0)
                <div class="space-y-3">
                    @foreach($team->members as $member)
                    <div class="flex items-center justify-between p-4 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                        <div class="flex items-center space-x-4">
                            <div class="flex-shrink-0">
                                <div class="h-12 w-12 rounded-full bg-accent-100 dark:bg-accent-900/30 flex items-center justify-center">
                                    <span class="text-sm font-medium text-accent-700 dark:text-accent-300">
                                        {{ substr($member->name, 0, 2) }}
                                    </span>
                                </div>
                            </div>
                            <div class="flex-1">
                                <h4 class="text-sm font-medium text-gray-900 dark:text-white">{{ $member->name }}</h4>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $member->email }}</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-100 dark:bg-primary-900/30 text-primary-800 dark:text-primary-300">
                                {{ ucfirst(str_replace('_', ' ', $member->user_type)) }}
                            </span>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-8">
                    <svg class="w-12 h-12 text-gray-400 dark:text-gray-600 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">No team members yet</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Team Info Card -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Team Information</h3>

                <div class="space-y-4">
                    <!-- Status -->
                    <div>
                        <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider block mb-2">Status</label>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                            {{ $team->status === 'active' ? 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300' : 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300' }}">
                            {{ ucfirst($team->status) }}
                        </span>
                    </div>

                    <!-- Department -->
                    @if($team->department)
                    <div>
                        <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider block mb-2">Department</label>
                        <p class="text-sm text-gray-900 dark:text-white">{{ $team->department }}</p>
                    </div>
                    @endif

                    <!-- Member Count -->
                    <div>
                        <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider block mb-2">Total Members</label>
                        <p class="text-sm text-gray-900 dark:text-white">{{ $team->members->count() }} members</p>
                    </div>

                    <!-- Timestamps -->
                    <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                        <div class="space-y-2 text-xs text-gray-500 dark:text-gray-400">
                            <div class="flex justify-between">
                                <span>Created:</span>
                                <span>{{ $team->created_at->format('M d, Y') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Updated:</span>
                                <span>{{ $team->updated_at->format('M d, Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Quick Stats</h3>

                <div class="space-y-3">
                    <div class="flex items-center justify-between p-3 bg-primary-50 dark:bg-primary-900/10 rounded-lg">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-primary-600 dark:text-primary-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            <span class="text-sm text-gray-700 dark:text-gray-300">Team Leads</span>
                        </div>
                        <span class="text-sm font-semibold text-primary-600 dark:text-primary-400">
                            {{ $team->members->where('user_type', 'team_lead')->count() }}
                        </span>
                    </div>

                    <div class="flex items-center justify-between p-3 bg-accent-50 dark:bg-accent-900/10 rounded-lg">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-accent-600 dark:text-accent-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            <span class="text-sm text-gray-700 dark:text-gray-300">Team Members</span>
                        </div>
                        <span class="text-sm font-semibold text-accent-600 dark:text-accent-400">
                            {{ $team->members->where('user_type', 'team_member')->count() }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
