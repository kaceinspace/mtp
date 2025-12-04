@extends('layouts.dashboard')

@section('title', 'Dashboard Guru')

@section('header')
    <h2 class="text-2xl font-bold text-gray-800">Dashboard Guru Pembimbing</h2>
    <p class="text-gray-600 mt-1">Kelola dan monitor project siswa bimbingan Anda</p>
@endsection

@section('content')
<!-- Welcome Banner -->
<div class="bg-gradient-to-r from-green-600 to-emerald-700 rounded-2xl p-8 mb-8 text-white relative overflow-hidden">
    <div class="absolute top-0 right-0 opacity-10">
        <svg class="w-64 h-64" fill="currentColor" viewBox="0 0 20 20">
            <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"/>
        </svg>
    </div>
    <div class="relative">
        <h1 class="text-3xl font-bold mb-2">Selamat Datang, {{ auth()->user()->name }}! 👨‍🏫</h1>
        <p class="text-green-100">Jurusan: {{ auth()->user()->jurusan ?? 'Belum diatur' }}</p>
    </div>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Siswa Bimbingan -->
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium">Siswa Bimbingan</p>
                <h3 class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['my_students'] ?? 0 }}</h3>
                <p class="text-green-600 text-sm mt-2">
                    <span class="font-medium">Aktif</span>
                </p>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Project Yang Dibimbing -->
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium">Project Bimbingan</p>
                <h3 class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['supervised_projects'] ?? 0 }}</h3>
                <p class="text-blue-600 text-sm mt-2">
                    <span class="font-medium">Aktif</span>
                </p>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Review Pending -->
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium">Perlu Review</p>
                <h3 class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['pending_reviews'] ?? 0 }}</h3>
                <p class="text-yellow-600 text-sm mt-2">
                    <span class="font-medium">Menunggu</span>
                </p>
            </div>
            <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Project Selesai -->
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium">Project Selesai</p>
                <h3 class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['completed_projects'] ?? 0 }}</h3>
                <p class="text-green-600 text-sm mt-2">
                    <span class="font-medium">100% Complete</span>
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
    <!-- Project Yang Dibimbing -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="p-6 border-b border-gray-100">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-bold text-gray-900">Project Bimbingan</h3>
                    <a href="#" class="text-green-600 hover:text-green-700 text-sm font-medium">Lihat Semua →</a>
                </div>
            </div>
            <div class="p-6">
                @forelse($supervised_projects ?? [] as $project)
                    <div class="flex items-start space-x-4 p-4 hover:bg-gray-50 rounded-lg transition mb-4 last:mb-0">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-lg flex items-center justify-center text-white font-bold">
                                {{ substr($project->title, 0, 1) }}
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-base font-semibold text-gray-900 mb-1">{{ $project->title }}</h4>
                            <p class="text-sm text-gray-600 mb-2">{{ $project->team }}</p>
                            <div class="flex items-center space-x-4 text-xs text-gray-500">
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                    </svg>
                                    {{ $project->members_count }} anggota
                                </span>
                                <span>{{ $project->progress }}% selesai</span>
                            </div>
                            <!-- Progress Bar -->
                            <div class="mt-3 w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-green-600 h-2 rounded-full transition-all" style="width: {{ $project->progress }}%"></div>
                            </div>
                        </div>
                        <div>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                @if($project->status === 'in_progress') bg-blue-100 text-blue-700
                                @elseif($project->status === 'review') bg-yellow-100 text-yellow-700
                                @else bg-green-100 text-green-700
                                @endif">
                                @if($project->status === 'in_progress') Berlangsung
                                @elseif($project->status === 'review') Review
                                @else Selesai
                                @endif
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada project</h3>
                        <p class="mt-1 text-sm text-gray-500">Project yang Anda bimbing akan muncul di sini</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Siswa Bimbingan -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 mt-8">
            <div class="p-6 border-b border-gray-100">
                <h3 class="text-lg font-bold text-gray-900">Siswa Bimbingan</h3>
            </div>
            <div class="p-6">
                @forelse($students ?? [] as $student)
                    <div class="flex items-center justify-between p-4 hover:bg-gray-50 rounded-lg transition mb-3 last:mb-0">
                        <div class="flex items-center space-x-3">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($student->name) }}&background=10b981&color=fff"
                                 alt="{{ $student->name }}"
                                 class="w-10 h-10 rounded-full">
                            <div>
                                <h4 class="text-sm font-semibold text-gray-900">{{ $student->name }}</h4>
                                <p class="text-xs text-gray-500">{{ $student->kelas }}</p>
                            </div>
                        </div>
                        <a href="#" class="text-green-600 hover:text-green-700 text-sm font-medium">Detail →</a>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <p class="text-gray-500">Belum ada siswa bimbingan</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Review Pending -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="p-6 border-b border-gray-100">
                <h3 class="text-lg font-bold text-gray-900">Perlu Review</h3>
            </div>
            <div class="p-6">
                @forelse($pending_reviews ?? [] as $review)
                    <div class="mb-4 last:mb-0 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <h4 class="text-sm font-semibold text-gray-900 mb-1">{{ $review->title }}</h4>
                        <p class="text-xs text-gray-600 mb-2">{{ $review->student }}</p>
                        <p class="text-xs text-yellow-700">Menunggu sejak {{ $review->submitted_at }}</p>
                        <button class="mt-3 w-full px-3 py-2 bg-yellow-400 text-yellow-900 rounded-lg text-xs font-medium hover:bg-yellow-500 transition">
                            Lihat & Review
                        </button>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <svg class="mx-auto h-10 w-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="mt-2 text-sm text-gray-500">Semua sudah direview</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-gradient-to-br from-green-600 to-emerald-700 rounded-xl shadow-sm p-6 text-white">
            <h3 class="text-lg font-bold mb-4">Quick Actions</h3>
            <div class="space-y-3">
                <a href="#" class="flex items-center justify-between p-3 bg-white bg-opacity-20 hover:bg-opacity-30 rounded-lg transition">
                    <span class="text-sm font-medium">Lihat Semua Project</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
                <a href="#" class="flex items-center justify-between p-3 bg-white bg-opacity-20 hover:bg-opacity-30 rounded-lg transition">
                    <span class="text-sm font-medium">Daftar Siswa</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
                <a href="#" class="flex items-center justify-between p-3 bg-white bg-opacity-20 hover:bg-opacity-30 rounded-lg transition">
                    <span class="text-sm font-medium">Buat Laporan</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
        </div>

        <!-- Tips -->
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-6">
            <div class="flex items-start space-x-3">
                <svg class="w-6 h-6 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>
                    <h4 class="text-sm font-semibold text-blue-900 mb-1">Tips Bimbingan</h4>
                    <p class="text-xs text-blue-700">Berikan feedback konstruktif dan tepat waktu untuk membantu siswa berkembang lebih baik.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
