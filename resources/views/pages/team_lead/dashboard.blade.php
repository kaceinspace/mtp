@extends('layouts.dashboard')

@section('title', 'Team Lead Dashboard')

@section('header')
    <h2 class="text-2xl font-bold text-gray-800">Team Lead Dashboard</h2>
    <p class="text-gray-600 mt-1">Monitor and manage your team's projects and tasks</p>
@endsection

@section('content')
<!-- Welcome Banner -->
<div class="bg-gradient-to-r from-green-600 to-emerald-700 rounded-2xl p-8 mb-8 text-white relative overflow-hidden">
    <div class="absolute top-0 right-0 opacity-10">
        <svg class="w-64 h-64" fill="currentColor" viewBox="0 0 20 20">
            <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/>
        </svg>
    </div>
    <div class="relative">
        <h1 class="text-3xl font-bold mb-2">Welcome, {{ auth()->user()->name }}! 👑</h1>
        <p class="text-green-100">Department: {{ auth()->user()->department ?? 'Not set' }}</p>
    </div>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Teams -->
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium">My Teams</p>
                <h3 class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['my_teams'] }}</h3>
                <p class="text-blue-600 text-sm mt-2">
                    <span class="font-medium">Leading</span>
                </p>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Total Projects -->
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium">Total Projects</p>
                <h3 class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['total_projects'] }}</h3>
                <p class="text-green-600 text-sm mt-2">
                    <span class="font-medium">{{ $stats['ongoing_projects'] }} Ongoing</span>
                </p>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Total Tasks -->
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium">Total Tasks</p>
                <h3 class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['total_tasks'] }}</h3>
                <p class="text-yellow-600 text-sm mt-2">
                    <span class="font-medium">{{ $stats['pending_tasks'] }} Pending</span>
                </p>
            </div>
            <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Completed Projects -->
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium">Completed Projects</p>
                <h3 class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['completed_projects'] }}</h3>
                <p class="text-purple-600 text-sm mt-2">
                    <span class="font-medium">Successfully Done</span>
                </p>
            </div>
            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
    </div>
</div>

<div class="grid lg:grid-cols-3 gap-8">
    <!-- Team Projects -->
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-gray-900">Team Projects</h3>
                <a href="{{ route('projects.index') }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                    View All →
                </a>
            </div>

            @forelse($projects as $project)
                <div class="border-b border-gray-100 pb-4 mb-4 last:border-0 last:pb-0 last:mb-0 hover:bg-gray-50 p-4 rounded-lg transition">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <h4 class="font-bold text-gray-900 mb-2">{{ $project->title }}</h4>
                            <p class="text-sm text-gray-600 mb-3">{{ Str::limit($project->description, 100) }}</p>

                            <div class="flex items-center space-x-4 text-xs text-gray-500">
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                    {{ $project->members->count() }} members
                                </span>
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                    </svg>
                                    {{ $project->tasks->count() }} tasks
                                </span>
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    {{ $project->created_at->diffForHumans() }}
                                </span>
                            </div>
                        </div>

                        <div class="ml-4 flex flex-col items-end space-y-2">
                            @php
                                $statusColors = [
                                    'planning' => 'bg-gray-100 text-gray-700',
                                    'ongoing' => 'bg-blue-100 text-blue-700',
                                    'on-hold' => 'bg-yellow-100 text-yellow-700',
                                    'completed' => 'bg-green-100 text-green-700',
                                    'cancelled' => 'bg-red-100 text-red-700',
                                ];
                                $priorityColors = [
                                    'low' => 'bg-gray-100 text-gray-600',
                                    'medium' => 'bg-blue-100 text-blue-600',
                                    'high' => 'bg-orange-100 text-orange-600',
                                    'critical' => 'bg-red-100 text-red-600',
                                ];
                            @endphp
                            <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $statusColors[$project->status] ?? 'bg-gray-100 text-gray-700' }}">
                                {{ ucfirst($project->status) }}
                            </span>
                            <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $priorityColors[$project->priority] ?? 'bg-gray-100 text-gray-600' }}">
                                {{ ucfirst($project->priority) }}
                            </span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-12">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                    </svg>
                    <p class="text-gray-500 font-medium">No projects yet</p>
                    <p class="text-sm text-gray-400 mt-1">Create your first project to get started</p>
                </div>
            @endforelse
        </div>

        <!-- Priority Tasks -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-gray-900">Priority Tasks</h3>
                <a href="{{ route('tasks.index') }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                    View All →
                </a>
            </div>

            @forelse($pending_tasks as $task)
                <div class="border-b border-gray-100 pb-3 mb-3 last:border-0 last:pb-0 last:mb-0">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <h4 class="font-semibold text-gray-900 text-sm mb-1">{{ $task->title }}</h4>
                            <p class="text-xs text-gray-500 mb-2">{{ $task->project->title }}</p>
                            <div class="flex items-center space-x-3 text-xs">
                                @if($task->assignedTo)
                                    <span class="text-gray-600">
                                        👤 {{ $task->assignedTo->name }}
                                    </span>
                                @endif
                                @if($task->due_date)
                                    <span class="text-orange-600">
                                        ⏰ {{ \Carbon\Carbon::parse($task->due_date)->format('M d, Y') }}
                                    </span>
                                @endif
                            </div>
                        </div>
                        @php
                            $priorityColors = [
                                'low' => 'bg-gray-100 text-gray-600',
                                'medium' => 'bg-blue-100 text-blue-600',
                                'high' => 'bg-orange-100 text-orange-600',
                                'critical' => 'bg-red-100 text-red-600',
                            ];
                        @endphp
                        <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $priorityColors[$task->priority] ?? 'bg-gray-100 text-gray-600' }}">
                            {{ ucfirst($task->priority) }}
                        </span>
                    </div>
                </div>
            @empty
                <div class="text-center py-8">
                    <svg class="w-12 h-12 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-gray-500 text-sm">No priority tasks</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Team Members & Quick Actions -->
    <div class="space-y-6">
        <!-- Quick Actions -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-xl font-bold text-gray-900 mb-4">Quick Actions</h3>
            <div class="space-y-3">
                <a href="{{ route('projects.create') }}" class="flex items-center p-3 rounded-lg bg-blue-50 hover:bg-blue-100 transition group">
                    <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center group-hover:scale-110 transition">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                    </div>
                    <span class="ml-3 font-semibold text-blue-700">Create New Project</span>
                </a>

                <a href="{{ route('tasks.kanban') }}" class="flex items-center p-3 rounded-lg bg-purple-50 hover:bg-purple-100 transition group">
                    <div class="w-10 h-10 bg-purple-600 rounded-lg flex items-center justify-center group-hover:scale-110 transition">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/>
                        </svg>
                    </div>
                    <span class="ml-3 font-semibold text-purple-700">Kanban Board</span>
                </a>

                <a href="{{ route('teams.index') }}" class="flex items-center p-3 rounded-lg bg-green-50 hover:bg-green-100 transition group">
                    <div class="w-10 h-10 bg-green-600 rounded-lg flex items-center justify-center group-hover:scale-110 transition">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <span class="ml-3 font-semibold text-green-700">Manage Teams</span>
                </a>
            </div>
        </div>

        <!-- Team Members -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-gray-900">Team Members</h3>
                <span class="text-sm text-gray-500">{{ $team_members->count() }} members</span>
            </div>

            <div class="space-y-3 max-h-96 overflow-y-auto">
                @forelse($team_members as $member)
                    <div class="flex items-center p-3 rounded-lg hover:bg-gray-50 transition">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($member->name) }}&background=random"
                             alt="{{ $member->name }}"
                             class="w-10 h-10 rounded-full">
                        <div class="ml-3 flex-1">
                            <h4 class="text-sm font-semibold text-gray-900">{{ $member->name }}</h4>
                            <p class="text-xs text-gray-500">{{ $member->department ?? 'Team Member' }}</p>
                        </div>
                        @if($member->user_type === 'team_lead')
                            <span class="px-2 py-1 bg-yellow-100 text-yellow-700 text-xs font-semibold rounded">Lead</span>
                        @endif
                    </div>
                @empty
                    <div class="text-center py-8">
                        <svg class="w-12 h-12 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        <p class="text-gray-500 text-sm">No team members</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="mt-6">
        <x-activity-feed :activities="$recent_activities ?? collect([])" title="Team Activities" />
    </div>
</div>
@endsection
