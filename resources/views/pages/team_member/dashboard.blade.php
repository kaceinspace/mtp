@extends('layouts.dashboard')

@section('title', 'Siswa Dashboard')
@section('page-title', 'My Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Welcome Section -->
    <div class="bg-gradient-to-r from-blue-600 to-cyan-600 rounded-lg shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold mb-2">Selamat Datang, {{ auth()->user()->name }}! 🎓</h2>
                <p class="text-blue-100">Kelas: {{ auth()->user()->kelas }} • Jurusan: {{ auth()->user()->jurusan }}</p>
            </div>
            <div class="hidden md:block">
                <div class="bg-white/20 rounded-full p-4">
                    <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- My Projects -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">My Projects</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['my_projects'] ?? 0 }}</p>
                    <p class="text-xs text-gray-500 mt-2">
                        <span class="text-blue-600">{{ $stats['active_projects'] ?? 0 }} Active</span>
                    </p>
                </div>
                <div class="bg-blue-100 p-3 rounded-lg">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Active Tasks -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Active Tasks</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['active_tasks'] ?? 0 }}</p>
                    <p class="text-xs text-gray-500 mt-2">
                        <span class="text-orange-600">{{ $stats['overdue_tasks'] ?? 0 }} Overdue</span>
                    </p>
                </div>
                <div class="bg-yellow-100 p-3 rounded-lg">
                    <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Completed Tasks -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Completed</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['completed_tasks'] ?? 0 }}</p>
                    <p class="text-xs text-gray-500 mt-2">
                        <span class="text-green-600">This month</span>
                    </p>
                </div>
                <div class="bg-green-100 p-3 rounded-lg">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Achievements -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Achievements</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['achievements'] ?? 0 }}</p>
                    <p class="text-xs text-gray-500 mt-2">
                        <span class="text-purple-600">{{ $stats['total_points'] ?? 0 }} Points</span>
                    </p>
                </div>
                <div class="bg-purple-100 p-3 rounded-lg">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- My Projects -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Active Projects -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">My Projects</h3>
                    <button class="text-sm text-indigo-600 hover:text-indigo-700 font-medium">View All</button>
                </div>
                <div class="divide-y divide-gray-200">
                    @forelse($projects ?? [] as $project)
                    <div class="p-6 hover:bg-gray-50 transition">
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex-1">
                                <h4 class="text-sm font-semibold text-gray-900">{{ $project->title }}</h4>
                                <p class="text-xs text-gray-500 mt-1">{{ $project->description }}</p>
                            </div>
                            <span class="ml-4 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($project->status == 'completed') bg-green-100 text-green-800
                                @elseif($project->status == 'in_progress') bg-blue-100 text-blue-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ ucfirst(str_replace('_', ' ', $project->status)) }}
                            </span>
                        </div>
                        <!-- Progress Bar -->
                        <div class="mb-3">
                            <div class="flex items-center justify-between text-xs mb-1">
                                <span class="text-gray-600">Progress</span>
                                <span class="font-medium text-gray-900">{{ $project->progress ?? 0 }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-indigo-600 h-2 rounded-full transition-all" style="width: {{ $project->progress ?? 0 }}%"></div>
                            </div>
                        </div>
                        <div class="flex items-center justify-between text-xs">
                            <div class="flex items-center space-x-3 text-gray-500">
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    {{ $project->created_at->diffForHumans() }}
                                </span>
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                    {{ $project->members_count ?? 0 }} Members
                                </span>
                            </div>
                            <a href="#" class="text-indigo-600 hover:text-indigo-700 font-medium">View Details →</a>
                        </div>
                    </div>
                    @empty
                    <div class="p-12 text-center">
                        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                        </svg>
                        <p class="text-gray-500 text-sm mb-4">Belum ada project</p>
                        <button class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 transition">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Create New Project
                        </button>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Recent Activities -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Recent Activities</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @forelse($activities ?? [] as $activity)
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm text-gray-900">{{ $activity->description }}</p>
                                <p class="text-xs text-gray-500 mt-1">{{ $activity->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        @empty
                        <p class="text-center text-gray-500 text-sm py-8">No recent activities</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Upcoming Deadlines -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Upcoming Deadlines</h3>
                <div class="space-y-3">
                    @forelse($deadlines ?? [] as $deadline)
                    <div class="flex items-start space-x-3 p-3 bg-red-50 rounded-lg border border-red-100">
                        <svg class="w-5 h-5 text-red-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900">{{ $deadline->title }}</p>
                            <p class="text-xs text-red-600 mt-1">Due: {{ $deadline->due_date->format('d M Y') }}</p>
                        </div>
                    </div>
                    @empty
                    <p class="text-center text-gray-500 text-sm py-4">No upcoming deadlines</p>
                    @endforelse
                </div>
            </div>

            <!-- Team Members -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">My Team</h3>
                <div class="space-y-3">
                    @forelse($team_members ?? [] as $member)
                    <div class="flex items-center space-x-3">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($member->name) }}&background=6366f1&color=fff"
                             alt="{{ $member->name }}"
                             class="w-10 h-10 rounded-full">
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 truncate">{{ $member->name }}</p>
                            <p class="text-xs text-gray-500">{{ $member->role }}</p>
                        </div>
                        <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                    </div>
                    @empty
                    <p class="text-center text-gray-500 text-sm py-4">No team members yet</p>
                    @endforelse
                </div>
            </div>

            <!-- Quick Links -->
            <div class="bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg shadow-lg p-6 text-white">
                <h3 class="text-lg font-semibold mb-4">Need Help?</h3>
                <p class="text-sm text-indigo-100 mb-4">Hubungi pembimbing atau admin untuk bantuan project</p>
                <button class="w-full bg-white text-indigo-600 hover:bg-indigo-50 font-medium py-2 px-4 rounded-lg transition">
                    Contact Support
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
