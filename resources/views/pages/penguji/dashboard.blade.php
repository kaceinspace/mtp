@extends('layouts.dashboard')

@section('title', 'Dashboard Guru Penguji')

@section('header')
    <h2 class="text-2xl font-bold text-gray-800">Dashboard Guru Penguji</h2>
    <p class="text-gray-600 mt-1">Kelola penilaian dan ujian project siswa</p>
@endsection

@section('content')
<!-- Welcome Banner -->
<div class="bg-gradient-to-r from-purple-600 to-indigo-700 rounded-2xl p-8 mb-8 text-white relative overflow-hidden">
    <div class="absolute top-0 right-0 opacity-10">
        <svg class="w-64 h-64" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
        </svg>
    </div>
    <div class="relative">
        <h1 class="text-3xl font-bold mb-2">Selamat Datang, {{ auth()->user()->name }}! 🎯</h1>
        <p class="text-purple-100">Jurusan: {{ auth()->user()->jurusan ?? 'Belum diatur' }}</p>
    </div>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Project Untuk Dinilai -->
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium">Perlu Dinilai</p>
                <h3 class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['projects_to_review'] ?? 0 }}</h3>
                <p class="text-yellow-600 text-sm mt-2">
                    <span class="font-medium">Menunggu</span>
                </p>
            </div>
            <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Penilaian Selesai -->
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium">Sudah Dinilai</p>
                <h3 class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['completed_reviews'] ?? 0 }}</h3>
                <p class="text-green-600 text-sm mt-2">
                    <span class="font-medium">Selesai</span>
                </p>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Presentasi Terjadwal -->
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium">Jadwal Presentasi</p>
                <h3 class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['scheduled_presentations'] ?? 0 }}</h3>
                <p class="text-blue-600 text-sm mt-2">
                    <span class="font-medium">Terjadwal</span>
                </p>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Rata-rata Nilai -->
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium">Rata-rata Nilai</p>
                <h3 class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['average_score'] ?? 0 }}</h3>
                <p class="text-purple-600 text-sm mt-2">
                    <span class="font-medium">Dari 100</span>
                </p>
            </div>
            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                </svg>
            </div>
        </div>
    </div>
</div>

<div class="grid lg:grid-cols-3 gap-8">
    <!-- Project Untuk Dinilai -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="p-6 border-b border-gray-100">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-bold text-gray-900">Project Perlu Penilaian</h3>
                    <a href="#" class="text-purple-600 hover:text-purple-700 text-sm font-medium">Lihat Semua →</a>
                </div>
            </div>
            <div class="p-6">
                @forelse($projects_to_review ?? [] as $project)
                    <div class="p-4 border border-gray-200 rounded-lg hover:border-purple-300 transition mb-4 last:mb-0">
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex-1">
                                <h4 class="text-base font-semibold text-gray-900 mb-1">{{ $project->title }}</h4>
                                <p class="text-sm text-gray-600 mb-2">Tim: {{ $project->team }}</p>
                                <div class="flex items-center space-x-4 text-xs text-gray-500">
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                        Pembimbing: {{ $project->supervisor }}
                                    </span>
                                    <span>Submitted: {{ $project->submitted_at }}</span>
                                </div>
                            </div>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700">
                                Perlu Dinilai
                            </span>
                        </div>
                        <div class="flex space-x-2">
                            <button class="flex-1 px-4 py-2 bg-purple-600 text-white rounded-lg text-sm font-medium hover:bg-purple-700 transition">
                                Mulai Penilaian
                            </button>
                            <button class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-200 transition">
                                Detail
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Semua sudah dinilai</h3>
                        <p class="mt-1 text-sm text-gray-500">Tidak ada project yang menunggu penilaian</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Penilaian Selesai -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 mt-8">
            <div class="p-6 border-b border-gray-100">
                <h3 class="text-lg font-bold text-gray-900">Penilaian Terkini</h3>
            </div>
            <div class="p-6">
                @forelse($completed_reviews ?? [] as $review)
                    <div class="flex items-center justify-between p-4 hover:bg-gray-50 rounded-lg transition mb-3 last:mb-0">
                        <div class="flex-1">
                            <h4 class="text-sm font-semibold text-gray-900">{{ $review->title }}</h4>
                            <p class="text-xs text-gray-500 mt-1">{{ $review->team }}</p>
                        </div>
                        <div class="flex items-center space-x-3">
                            <div class="text-right">
                                <div class="text-lg font-bold text-purple-600">{{ $review->score }}</div>
                                <div class="text-xs text-gray-500">{{ $review->graded_at }}</div>
                            </div>
                            <button class="text-purple-600 hover:text-purple-700">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <p class="text-gray-500">Belum ada penilaian yang selesai</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Jadwal Presentasi -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="p-6 border-b border-gray-100">
                <h3 class="text-lg font-bold text-gray-900">Jadwal Presentasi</h3>
            </div>
            <div class="p-6">
                @forelse($scheduled_presentations ?? [] as $presentation)
                    <div class="mb-4 last:mb-0 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                        <div class="flex items-start space-x-2 mb-2">
                            <svg class="w-5 h-5 text-blue-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <div class="flex-1">
                                <h4 class="text-sm font-semibold text-gray-900">{{ $presentation->title }}</h4>
                                <p class="text-xs text-gray-600 mt-1">{{ $presentation->team }}</p>
                                <p class="text-xs text-blue-700 mt-2 font-medium">
                                    📅 {{ $presentation->date }} • {{ $presentation->time }}
                                </p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <svg class="mx-auto h-10 w-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <p class="mt-2 text-sm text-gray-500">Belum ada jadwal</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-gradient-to-br from-purple-600 to-indigo-700 rounded-xl shadow-sm p-6 text-white">
            <h3 class="text-lg font-bold mb-4">Quick Actions</h3>
            <div class="space-y-3">
                <a href="#" class="flex items-center justify-between p-3 bg-white bg-opacity-20 hover:bg-opacity-30 rounded-lg transition">
                    <span class="text-sm font-medium">Semua Project</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
                <a href="#" class="flex items-center justify-between p-3 bg-white bg-opacity-20 hover:bg-opacity-30 rounded-lg transition">
                    <span class="text-sm font-medium">Jadwal Presentasi</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
                <a href="#" class="flex items-center justify-between p-3 bg-white bg-opacity-20 hover:bg-opacity-30 rounded-lg transition">
                    <span class="text-sm font-medium">Rubrik Penilaian</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
                <a href="#" class="flex items-center justify-between p-3 bg-white bg-opacity-20 hover:bg-opacity-30 rounded-lg transition">
                    <span class="text-sm font-medium">Laporan Nilai</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
        </div>

        <!-- Reminder -->
        <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-6">
            <div class="flex items-start space-x-3">
                <svg class="w-6 h-6 text-yellow-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <div>
                    <h4 class="text-sm font-semibold text-yellow-900 mb-1">Reminder</h4>
                    <p class="text-xs text-yellow-700">Jangan lupa berikan feedback yang konstruktif dan detail untuk setiap project yang dinilai.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
