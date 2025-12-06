@extends('layouts.dashboard')

@section('title', 'Team Member Dashboard')

@section('header')
    <h2 class="text-2xl font-bold text-gray-800">My Dashboard</h2>
    <p class="text-gray-600 mt-1">Track your projects and tasks</p>
@endsection

@section('content')
<!-- Welcome Banner -->
<div class="bg-gradient-to-r from-blue-600 to-cyan-600 rounded-2xl p-8 mb-8 text-white relative overflow-hidden">
    <div class="absolute top-0 right-0 opacity-10">
        <svg class="w-64 h-64" fill="currentColor" viewBox="0 0 20 20">
            <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"/>
        </svg>
    </div>
    <div class="relative">
        <h1 class="text-3xl font-bold mb-2">Welcome, {{ auth()->user()->name }}! 🎯</h1>
        <p class="text-blue-100">Department: {{ auth()->user()->department ?? 'Not set' }}</p>
    </div>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- My Projects -->
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium">My Projects</p>
                <h3 class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['my_projects'] }}</h3>
                <p class="text-blue-600 text-sm mt-2">
                    <span class="font-medium">{{ $stats['active_projects'] }} Active</span>
                </p>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Active Tasks -->
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium">Active Tasks</p>
                <h3 class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['active_tasks'] }}</h3>
                <p class="text-orange-600 text-sm mt-2">
                    <span class="font-medium">{{ $stats['overdue_tasks'] }} Overdue</span>
                </p>
            </div>
            <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Completed Tasks -->
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium">Completed</p>
                <h3 class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['completed_tasks'] }}</h3>
                <p class="text-green-600 text-sm mt-2">
                    <span class="font-medium">This month</span>
                </p>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
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
                <p class="text-purple-600 text-sm mt-2">
                    <span class="font-medium">All time</span>
                </p>
            </div>
            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- My Projects & Activities -->
    <div class="lg:col-span-2 space-y-6">
        <!-- My Projects -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-gray-900">My Projects</h3>
                <a href="{{ route('projects.index') }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                    View All →
                </a>
            </div>

            @forelse($projects as $project)
                <div class="border-b border-gray-100 pb-4 mb-4 last:border-0 last:pb-0 last:mb-0 hover:bg-gray-50 p-4 rounded-lg transition">
                    <div class="flex items-start justify-between mb-3">
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
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    {{ $project->created_at->diffForHumans() }}
                                </span>
                            </div>
                        </div>

                        <div class="ml-4">
                            @php
                                $statusColors = [
                                    'planning' => 'bg-gray-100 text-gray-700',
                                    'ongoing' => 'bg-blue-100 text-blue-700',
                                    'on-hold' => 'bg-yellow-100 text-yellow-700',
                                    'completed' => 'bg-green-100 text-green-700',
                                    'cancelled' => 'bg-red-100 text-red-700',
                                ];
                            @endphp
                            <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $statusColors[$project->status] ?? 'bg-gray-100 text-gray-700' }}">
                                {{ ucfirst($project->status) }}
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
                    <p class="text-sm text-gray-400 mt-1">You'll see projects you're assigned to here</p>
                </div>
            @endforelse
        </div>

        <!-- Recent Activities -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-gray-900">Recent Activities</h3>
            </div>

            <div class="space-y-4">
                @forelse($activities->take(5) as $task)
                    <div class="flex items-start space-x-3 p-3 hover:bg-gray-50 rounded-lg transition">
                        <div class="flex-shrink-0">
                            @php
                                $statusIcons = [
                                    'completed' => ['bg-green-100', 'text-green-600', 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                                    'in-progress' => ['bg-blue-100', 'text-blue-600', 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                                    'todo' => ['bg-gray-100', 'text-gray-600', 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
                                ];
                                $icon = $statusIcons[$task->status] ?? $statusIcons['todo'];
                            @endphp
                            <div class="w-8 h-8 {{ $icon[0] }} rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 {{ $icon[1] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon[2] }}"/>
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm text-gray-900 font-medium">{{ $task->title }}</p>
                            <p class="text-xs text-gray-500 mt-1">{{ $task->updated_at->diffForHumans() }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-gray-500 text-sm py-8">No recent activities</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Upcoming Deadlines -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-xl font-bold text-gray-900 mb-4">Upcoming Deadlines</h3>
            <div class="space-y-3">
                @forelse($deadlines as $task)
                    <div class="p-3 {{ $task->due_date < now() ? 'bg-red-50 border border-red-100' : 'bg-yellow-50 border border-yellow-100' }} rounded-lg">
                        <div class="flex items-start space-x-3">
                            <svg class="w-5 h-5 {{ $task->due_date < now() ? 'text-red-600' : 'text-yellow-600' }} flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-900">{{ $task->title }}</p>
                                <p class="text-xs text-gray-600 mt-1">{{ $task->project->title }}</p>
                                <p class="text-xs {{ $task->due_date < now() ? 'text-red-600' : 'text-yellow-600' }} mt-1 font-medium">
                                    {{ $task->due_date < now() ? 'Overdue' : 'Due' }}: {{ \Carbon\Carbon::parse($task->due_date)->format('M d, Y') }}
                                </p>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-gray-500 text-sm py-4">No upcoming deadlines</p>
                @endforelse
            </div>
        </div>

        <!-- Team Members -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-xl font-bold text-gray-900 mb-4">My Team</h3>
            <div class="space-y-3">
                @forelse($team_members as $member)
                    <div class="flex items-center space-x-3 p-2 hover:bg-gray-50 rounded-lg transition">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($member->name) }}&background=random"
                             alt="{{ $member->name }}"
                             class="w-10 h-10 rounded-full">
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-gray-900 truncate">{{ $member->name }}</p>
                            <p class="text-xs text-gray-500">{{ $member->department ?? 'Team Member' }}</p>
                        </div>
                        @if($member->user_type === 'team_lead')
                            <span class="px-2 py-1 bg-yellow-100 text-yellow-700 text-xs font-semibold rounded">Lead</span>
                        @endif
                    </div>
                @empty
                    <p class="text-center text-gray-500 text-sm py-4">No team members yet</p>
                @endforelse
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-gradient-to-br from-blue-600 to-cyan-700 rounded-xl shadow-lg p-6 text-white">
            <h3 class="text-lg font-semibold mb-4">Quick Actions</h3>
            <div class="space-y-3">
                <a href="{{ route('projects.index') }}" class="flex items-center p-3 bg-white bg-opacity-20 hover:bg-opacity-30 rounded-lg transition">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/>
                    </svg>
                    <span class="text-sm font-medium">View All Projects</span>
                </a>
                <a href="{{ route('tasks.kanban') }}" class="flex items-center p-3 bg-white bg-opacity-20 hover:bg-opacity-30 rounded-lg transition">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                    </svg>
                    <span class="text-sm font-medium">Kanban Board</span>
                </a>
                <a href="{{ route('teams.index') }}" class="flex items-center p-3 bg-white bg-opacity-20 hover:bg-opacity-30 rounded-lg transition">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <span class="text-sm font-medium">View Teams</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="mt-6">
        <x-activity-feed :activities="$recent_activities ?? collect([])" title="Project Activities" />
    </div>
</div>
@endsection
