@extends('layouts.dashboard')

@section('title', 'Team Member Dashboard')

@section('header')
    <h2 class="text-2xl font-bold text-gray-800">My Dashboard</h2>
    <p class="text-gray-600 mt-1">Track your projects and tasks</p>
@endsection

@section('content')
<div class="space-y-8">
    <!-- Welcome Banner -->
    <div class="bg-gradient-to-r from-blue-500 via-cyan-500 to-teal-600 rounded-2xl p-8 text-white relative overflow-hidden shadow-2xl">
        <div class="absolute top-0 right-0 opacity-10 transform rotate-12">
            <svg class="w-80 h-80" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"/>
            </svg>
        </div>
        <div class="relative z-10">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-16 h-16 bg-white/20 backdrop-blur rounded-2xl flex items-center justify-center">
                    <i class="fas fa-user text-white text-3xl"></i>
                </div>
                <div>
                    <h1 class="text-4xl font-black mb-1">Welcome back, {{ auth()->user()->name }}!</h1>
                    <p class="text-blue-100 text-lg flex items-center gap-2">
                        <i class="fas fa-briefcase"></i>
                        {{ auth()->user()->department ?? 'Not set' }} • Team Member
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- My Projects -->
        <div class="group relative overflow-hidden bg-gradient-to-br from-white to-blue-50 dark:from-gray-800 dark:to-blue-900/20 rounded-2xl shadow-lg border-2 border-blue-200 dark:border-blue-800 hover:shadow-2xl hover:scale-105 transition-all duration-300">
            <div class="absolute top-0 right-0 w-32 h-32 bg-blue-500/10 rounded-full -mr-16 -mt-16"></div>
            <div class="relative p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-project-diagram text-white text-2xl"></i>
                    </div>
                    <span class="px-3 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 text-xs font-bold rounded-full">PROJECTS</span>
                </div>
                <h3 class="text-4xl font-black text-gray-900 dark:text-white mb-1">{{ $stats['my_projects'] }}</h3>
                <p class="text-sm font-semibold text-blue-600 dark:text-blue-400">{{ $stats['active_projects'] }} Active</p>
            </div>
        </div>

        <!-- Active Tasks -->
        <div class="group relative overflow-hidden bg-gradient-to-br from-white to-yellow-50 dark:from-gray-800 dark:to-yellow-900/20 rounded-2xl shadow-lg border-2 border-yellow-200 dark:border-yellow-800 hover:shadow-2xl hover:scale-105 transition-all duration-300">
            <div class="absolute top-0 right-0 w-32 h-32 bg-yellow-500/10 rounded-full -mr-16 -mt-16"></div>
            <div class="relative p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-14 h-14 bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-tasks text-white text-2xl"></i>
                    </div>
                    <span class="px-3 py-1 bg-yellow-100 dark:bg-yellow-900/30 text-yellow-600 dark:text-yellow-400 text-xs font-bold rounded-full">ACTIVE</span>
                </div>
                <h3 class="text-4xl font-black text-gray-900 dark:text-white mb-1">{{ $stats['active_tasks'] }}</h3>
                <p class="text-sm font-semibold text-orange-600 dark:text-orange-400">{{ $stats['overdue_tasks'] }} Overdue</p>
            </div>
        </div>

        <!-- Completed Tasks -->
        <div class="group relative overflow-hidden bg-gradient-to-br from-white to-green-50 dark:from-gray-800 dark:to-green-900/20 rounded-2xl shadow-lg border-2 border-green-200 dark:border-green-800 hover:shadow-2xl hover:scale-105 transition-all duration-300">
            <div class="absolute top-0 right-0 w-32 h-32 bg-green-500/10 rounded-full -mr-16 -mt-16"></div>
            <div class="relative p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-14 h-14 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-check-circle text-white text-2xl"></i>
                    </div>
                    <span class="px-3 py-1 bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400 text-xs font-bold rounded-full">DONE</span>
                </div>
                <h3 class="text-4xl font-black text-gray-900 dark:text-white mb-1">{{ $stats['completed_tasks'] }}</h3>
                <p class="text-sm font-semibold text-green-600 dark:text-green-400">This Month</p>
            </div>
        </div>

        <!-- Total Tasks -->
        <div class="group relative overflow-hidden bg-gradient-to-br from-white to-indigo-50 dark:from-gray-800 dark:to-indigo-900/20 rounded-2xl shadow-lg border-2 border-indigo-200 dark:border-indigo-800 hover:shadow-2xl hover:scale-105 transition-all duration-300">
            <div class="absolute top-0 right-0 w-32 h-32 bg-indigo-500/10 rounded-full -mr-16 -mt-16"></div>
            <div class="relative p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-14 h-14 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-list text-white text-2xl"></i>
                    </div>
                    <span class="px-3 py-1 bg-indigo-100 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 text-xs font-bold rounded-full">TOTAL</span>
                </div>
                <h3 class="text-4xl font-black text-gray-900 dark:text-white mb-1">{{ $stats['total_tasks'] }}</h3>
                <p class="text-sm font-semibold text-indigo-600 dark:text-indigo-400">All Time</p>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid lg:grid-cols-3 gap-8">
        <!-- My Projects & Activities -->
        <div class="lg:col-span-2 space-y-6">
            <!-- My Projects -->
            <div class="bg-gradient-to-br from-white to-gray-50 dark:from-gray-800 dark:to-gray-900 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="bg-gradient-to-r from-blue-500 to-cyan-500 p-6">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-white/20 backdrop-blur rounded-xl flex items-center justify-center">
                                <i class="fas fa-project-diagram text-white text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-2xl font-black text-white">My Projects</h3>
                                <p class="text-blue-100 text-sm">Active project assignments</p>
                            </div>
                        </div>
                        <a href="{{ route('projects.index') }}" class="px-4 py-2 bg-white/20 backdrop-blur hover:bg-white/30 text-white rounded-lg text-sm font-bold transition flex items-center gap-2">
                            View All <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
                <div class="p-6">
                    @forelse($projects as $project)
                        <div class="group hover:scale-[1.01] transition-transform duration-200 mb-4 last:mb-0">
                            <div class="p-5 rounded-xl border-2 border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 hover:border-blue-400 hover:shadow-lg transition-all">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <h4 class="text-lg font-bold text-gray-900 dark:text-white mb-2 group-hover:text-blue-600 transition-colors">
                                            {{ $project->title }}
                                        </h4>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-3 line-clamp-2">
                                            {{ $project->description }}
                                        </p>
                                        <div class="flex items-center gap-4 text-xs text-gray-500 dark:text-gray-400">
                                            <span class="flex items-center gap-1">
                                                <i class="fas fa-users"></i>
                                                {{ $project->members->count() }} members
                                            </span>
                                            <span class="flex items-center gap-1">
                                                <i class="fas fa-clock"></i>
                                                {{ $project->created_at->diffForHumans() }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="ml-4">
                                        @php
                                            $statusColors = [
                                                'planning' => 'from-gray-400 to-gray-500',
                                                'ongoing' => 'from-blue-500 to-cyan-500',
                                                'on-hold' => 'from-yellow-500 to-orange-500',
                                                'completed' => 'from-green-500 to-emerald-500',
                                                'cancelled' => 'from-red-500 to-rose-500',
                                            ];
                                        @endphp
                                        <span class="px-3 py-1 rounded-lg text-xs font-bold bg-gradient-to-r {{ $statusColors[$project->status] ?? 'from-gray-400 to-gray-500' }} text-white shadow-md">
                                            {{ strtoupper($project->status) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-16">
                            <div class="w-24 h-24 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-project-diagram text-4xl text-gray-400"></i>
                            </div>
                            <p class="text-gray-600 dark:text-gray-400 font-semibold">No projects yet</p>
                            <p class="text-sm text-gray-500 dark:text-gray-500 mt-1">You'll see projects you're assigned to here</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Recent Activities -->
            <div class="bg-gradient-to-br from-white to-gray-50 dark:from-gray-800 dark:to-gray-900 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="bg-gradient-to-r from-purple-500 to-pink-500 p-6">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-white/20 backdrop-blur rounded-xl flex items-center justify-center">
                            <i class="fas fa-history text-white text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-2xl font-black text-white">Recent Activities</h3>
                            <p class="text-purple-100 text-sm">Your latest updates</p>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <div class="space-y-3">
                        @forelse($activities->take(5) as $task)
                            <div class="p-4 rounded-xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 hover:border-purple-400 hover:shadow-md transition-all">
                                <div class="flex items-start gap-3">
                                    <div class="flex-shrink-0">
                                        @php
                                            $statusIcons = [
                                                'completed' => ['from-green-500 to-emerald-500', 'fa-check-circle'],
                                                'in-progress' => ['from-blue-500 to-cyan-500', 'fa-spinner'],
                                                'todo' => ['from-gray-400 to-gray-500', 'fa-circle'],
                                            ];
                                            $icon = $statusIcons[$task->status] ?? $statusIcons['todo'];
                                        @endphp
                                        <div class="w-10 h-10 bg-gradient-to-br {{ $icon[0] }} rounded-lg flex items-center justify-center shadow-md">
                                            <i class="fas {{ $icon[1] }} text-white"></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-bold text-gray-900 dark:text-white">{{ $task->title }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                            <i class="fas fa-clock mr-1"></i>
                                            {{ $task->updated_at->diffForHumans() }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-12">
                                <div class="w-20 h-20 bg-purple-100 dark:bg-purple-900/20 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-history text-3xl text-purple-500"></i>
                                </div>
                                <p class="text-gray-600 dark:text-gray-400 font-semibold">No recent activities</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Upcoming Deadlines -->
            <div class="bg-gradient-to-br from-white to-gray-50 dark:from-gray-800 dark:to-gray-900 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="bg-gradient-to-r from-orange-500 to-red-500 p-6">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-white/20 backdrop-blur rounded-xl flex items-center justify-center">
                            <i class="fas fa-clock text-white text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-2xl font-black text-white">Deadlines</h3>
                            <p class="text-orange-100 text-sm">Upcoming & overdue</p>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <div class="space-y-3">
                        @forelse($deadlines as $task)
                            <div class="p-4 rounded-xl border-l-4 {{ $task->due_date < now() ? 'border-red-500 bg-gradient-to-r from-red-50 to-red-50/50 dark:from-red-900/20 dark:to-red-900/5' : 'border-orange-500 bg-gradient-to-r from-orange-50 to-orange-50/50 dark:from-orange-900/20 dark:to-orange-900/5' }} shadow-sm hover:shadow-md transition-shadow">
                                <div class="flex items-start gap-3">
                                    <div class="w-10 h-10 bg-gradient-to-br {{ $task->due_date < now() ? 'from-red-500 to-red-600' : 'from-orange-500 to-orange-600' }} rounded-lg flex items-center justify-center shadow-md flex-shrink-0">
                                        <i class="fas {{ $task->due_date < now() ? 'fa-exclamation-triangle' : 'fa-clock' }} text-white"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-bold text-gray-900 dark:text-white">{{ $task->title }}</p>
                                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">
                                            <i class="fas fa-project-diagram mr-1"></i>
                                            {{ $task->project->title }}
                                        </p>
                                        <p class="text-xs {{ $task->due_date < now() ? 'text-red-600 dark:text-red-400' : 'text-orange-600 dark:text-orange-400' }} mt-2 font-bold">
                                            {{ $task->due_date < now() ? '⚠️ Overdue' : '📅 Due' }}: {{ \Carbon\Carbon::parse($task->due_date)->format('M d, Y') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-12">
                                <div class="w-20 h-20 bg-green-100 dark:bg-green-900/20 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-check-double text-3xl text-green-500"></i>
                                </div>
                                <p class="text-gray-600 dark:text-gray-400 font-semibold">All caught up!</p>
                                <p class="text-sm text-gray-500 dark:text-gray-500 mt-1">No upcoming deadlines</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Team Members -->
            <div class="bg-gradient-to-br from-white to-gray-50 dark:from-gray-800 dark:to-gray-900 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="bg-gradient-to-r from-indigo-500 to-purple-500 p-6">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-white/20 backdrop-blur rounded-xl flex items-center justify-center">
                            <i class="fas fa-users text-white text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-2xl font-black text-white">My Team</h3>
                            <p class="text-indigo-100 text-sm">{{ count($team_members) }} members</p>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <div class="space-y-3">
                        @forelse($team_members as $member)
                            <div class="group hover:scale-[1.01] transition-transform duration-200">
                                <div class="p-4 rounded-xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 hover:border-indigo-400 hover:shadow-md transition-all">
                                    <div class="flex items-center gap-3">
                                        <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-500 rounded-full flex items-center justify-center text-white font-bold text-lg shadow-md">
                                            {{ substr($member->name, 0, 1) }}
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h4 class="font-bold text-gray-900 dark:text-white text-sm truncate">{{ $member->name }}</h4>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $member->department ?? 'Team Member' }}</p>
                                        </div>
                                        @if($member->user_type === 'team_lead')
                                            <span class="px-3 py-1 bg-gradient-to-r from-yellow-400 to-yellow-500 text-white text-xs font-bold rounded-lg shadow-md flex items-center gap-1">
                                                <i class="fas fa-crown"></i> Lead
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-12">
                                <div class="w-20 h-20 bg-indigo-100 dark:bg-indigo-900/20 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-users text-3xl text-indigo-500"></i>
                                </div>
                                <p class="text-gray-600 dark:text-gray-400 font-semibold">No team members</p>
                                <p class="text-sm text-gray-500 dark:text-gray-500 mt-1">Your teammates will appear here</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Analytics Section -->
    @if(isset($projectHealthData) || isset($progressTrends) || isset($riskIndicators))
    <div class="mt-8">
        <div class="flex items-center gap-4 mb-6">
            <div class="h-1 flex-1 bg-gradient-to-r from-blue-500 via-purple-500 to-pink-500 rounded-full"></div>
            <h2 class="text-3xl font-black text-gray-900 dark:text-white flex items-center gap-3">
                <i class="fas fa-chart-line text-blue-600"></i>
                Project Analytics
            </h2>
            <div class="h-1 flex-1 bg-gradient-to-r from-pink-500 via-purple-500 to-blue-500 rounded-full"></div>
        </div>

        @include('partials.dashboard-analytics', [
            'projectHealthData' => $projectHealthData ?? null,
            'progressTrends' => $progressTrends ?? null,
            'teamPerformance' => null,
            'riskIndicators' => $riskIndicators ?? null
        ])
    </div>
    @endif

    <!-- Recent Activities -->
    <div class="mt-8">
        <x-activity-feed :activities="$recent_activities ?? collect([])" title="Project Activities" />
    </div>
</div>
@endsection
