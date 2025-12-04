@extends('layouts.dashboard')

@section('title', 'User Management')

@section('header')
    <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
        <div class="space-y-1">
            <h2 class="text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600">
                👥 User Management
            </h2>
            <p class="text-gray-600">Kelola semua pengguna sistem dengan mudah dan efisien</p>
        </div>
        <a href="{{ route('admin.users.create') }}"
           class="group px-6 py-3 bg-gradient-to-r from-blue-600 via-blue-700 to-indigo-700 text-white rounded-xl hover:shadow-2xl hover:shadow-blue-500/50 hover:-translate-y-0.5 transition-all duration-300 font-semibold inline-flex items-center space-x-2">
            <svg class="w-5 h-5 group-hover:rotate-90 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            <span>Tambah User Baru</span>
        </a>
    </div>
@endsection

@section('content')
<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
    <div class="group bg-gradient-to-br from-purple-500 to-purple-700 rounded-2xl p-6 text-white hover:shadow-2xl hover:shadow-purple-500/50 hover:-translate-y-1 transition-all duration-300">
        <div class="flex items-center justify-between mb-2">
            <div class="p-3 bg-white/20 backdrop-blur-sm rounded-xl group-hover:rotate-12 transition-transform duration-300">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
            </div>
            <span class="text-3xl font-bold">{{ $users->where('user_type', 'admin')->count() }}</span>
        </div>
        <p class="text-purple-100 font-medium">Admin</p>
    </div>

    <div class="group bg-gradient-to-br from-green-500 to-emerald-700 rounded-2xl p-6 text-white hover:shadow-2xl hover:shadow-green-500/50 hover:-translate-y-1 transition-all duration-300">
        <div class="flex items-center justify-between mb-2">
            <div class="p-3 bg-white/20 backdrop-blur-sm rounded-xl group-hover:rotate-12 transition-transform duration-300">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
            </div>
            <span class="text-3xl font-bold">{{ $users->where('user_type', 'guru')->count() }}</span>
        </div>
        <p class="text-green-100 font-medium">Guru Pembimbing</p>
    </div>

    <div class="group bg-gradient-to-br from-indigo-500 to-purple-700 rounded-2xl p-6 text-white hover:shadow-2xl hover:shadow-indigo-500/50 hover:-translate-y-1 transition-all duration-300">
        <div class="flex items-center justify-between mb-2">
            <div class="p-3 bg-white/20 backdrop-blur-sm rounded-xl group-hover:rotate-12 transition-transform duration-300">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <span class="text-3xl font-bold">{{ $users->where('user_type', 'guru_penguji')->count() }}</span>
        </div>
        <p class="text-indigo-100 font-medium">Guru Penguji</p>
    </div>

    <div class="group bg-gradient-to-br from-blue-500 to-cyan-600 rounded-2xl p-6 text-white hover:shadow-2xl hover:shadow-blue-500/50 hover:-translate-y-1 transition-all duration-300">
        <div class="flex items-center justify-between mb-2">
            <div class="p-3 bg-white/20 backdrop-blur-sm rounded-xl group-hover:rotate-12 transition-transform duration-300">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
            </div>
            <span class="text-3xl font-bold">{{ $users->where('user_type', 'siswa')->count() }}</span>
        </div>
        <p class="text-blue-100 font-medium">Siswa</p>
    </div>
</div>

<!-- Filter & Search -->
<div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-6 mb-6 backdrop-blur-sm"
<div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-6 mb-6 backdrop-blur-sm">
    <div class="flex items-center mb-6">
        <div class="p-3 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-xl">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
            </svg>
        </div>
        <div class="ml-4">
            <h3 class="text-lg font-bold text-gray-900">Filter & Pencarian</h3>
            <p class="text-sm text-gray-500">Temukan user dengan cepat</p>
        </div>
    </div>

    <form method="GET" action="{{ route('admin.users.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <!-- Search -->
        <div class="group">
            <label class="block text-sm font-semibold text-gray-700 mb-2">🔍 Cari User</label>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Nama, email, NISN, NIP..."
                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all group-hover:border-blue-300">
        </div>

        <!-- User Type Filter -->
        <div class="group">
            <label class="block text-sm font-semibold text-gray-700 mb-2">👤 Tipe User</label>
            <select name="user_type" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all group-hover:border-blue-300">
                <option value="">Semua Tipe</option>
                <option value="admin" {{ request('user_type') == 'admin' ? 'selected' : '' }}>🛡️ Admin</option>
                <option value="guru" {{ request('user_type') == 'guru' ? 'selected' : '' }}>📚 Guru Pembimbing</option>
                <option value="guru_penguji" {{ request('user_type') == 'guru_penguji' ? 'selected' : '' }}>✅ Guru Penguji</option>
                <option value="siswa" {{ request('user_type') == 'siswa' ? 'selected' : '' }}>🎓 Siswa</option>
            </select>
        </div>

        <!-- Jurusan Filter -->
        <div class="group">
            <label class="block text-sm font-semibold text-gray-700 mb-2">🎯 Jurusan</label>
            <select name="jurusan" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all group-hover:border-blue-300">
                <option value="">Semua Jurusan</option>
                <option value="RPL" {{ request('jurusan') == 'RPL' ? 'selected' : '' }}>💻 RPL</option>
                <option value="TKJ" {{ request('jurusan') == 'TKJ' ? 'selected' : '' }}>🌐 TKJ</option>
                <option value="MM" {{ request('jurusan') == 'MM' ? 'selected' : '' }}>🎨 MM</option>
            </select>
        </div>

        <!-- Status Filter -->
        <div class="group">
            <label class="block text-sm font-semibold text-gray-700 mb-2">⚡ Status</label>
            <select name="is_active" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all group-hover:border-blue-300">
                <option value="">Semua Status</option>
                <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>✅ Aktif</option>
                <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>❌ Nonaktif</option>
            </select>
        </div>

        <div class="md:col-span-4 flex items-center space-x-3">
            <button type="submit" class="group px-8 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl hover:shadow-xl hover:shadow-blue-500/50 hover:-translate-y-0.5 transition-all duration-300 font-semibold inline-flex items-center space-x-2">
                <svg class="w-5 h-5 group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <span>Terapkan Filter</span>
            </button>
            <a href="{{ route('admin.users.index') }}" class="px-8 py-3 bg-gray-200 text-gray-700 rounded-xl hover:bg-gray-300 hover:shadow-lg transition-all font-semibold inline-flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                <span>Reset</span>
            </a>
        </div>
    </form>
</div>

<!-- Alert Messages -->
@if(session('success'))
    <div class="bg-gradient-to-r from-green-50 to-emerald-50 border-2 border-green-200 rounded-2xl p-6 mb-6 animate-fade-in">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <svg class="w-8 h-8 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-green-800 font-semibold">{{ session('success') }}</p>
            </div>
        </div>
    </div>
@endif

@if(session('error'))
    <div class="bg-gradient-to-r from-red-50 to-pink-50 border-2 border-red-200 rounded-2xl p-6 mb-6 animate-fade-in">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <svg class="w-8 h-8 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-red-800 font-semibold">{{ session('error') }}</p>
            </div>
        </div>
    </div>
@endif

<!-- Users Table -->
<div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden backdrop-blur-sm">
    <div class="px-6 py-4 bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="p-2 bg-blue-100 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Daftar User</h3>
                    <p class="text-sm text-gray-500">Total {{ $users->total() }} user terdaftar</p>
                </div>
            </div>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gradient-to-r from-gray-50 to-blue-50">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">User</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Tipe</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Kontak</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Jurusan/Kelas</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-right text-xs font-bold text-gray-600 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @forelse($users as $user)
                    <tr class="hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 transition-all duration-200">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-12 w-12">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=random&bold=true&size=128"
                                         alt="{{ $user->name }}"
                                         class="h-12 w-12 rounded-xl ring-2 ring-offset-2 ring-blue-500 object-cover hover:scale-110 transition-transform duration-300">
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-bold text-gray-900">{{ $user->name }}</div>
                                    <div class="text-sm text-gray-500 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                        </svg>
                                        {{ $user->email }}
                                    </div>
                                    @if($user->nisn)
                                        <div class="text-xs text-gray-400 flex items-center mt-1">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                            </svg>
                                            NISN: {{ $user->nisn }}
                                        </div>
                                    @elseif($user->nip)
                                        <div class="text-xs text-gray-400 flex items-center mt-1">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                            </svg>
                                            NIP: {{ $user->nip }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($user->user_type === 'admin')
                                <span class="px-3 py-1.5 inline-flex text-xs leading-5 font-bold rounded-xl bg-gradient-to-r from-purple-500 to-purple-700 text-white shadow-lg shadow-purple-500/50">
                                    🛡️ Admin
                                </span>
                            @elseif($user->user_type === 'guru')
                                <span class="px-3 py-1.5 inline-flex text-xs leading-5 font-bold rounded-xl bg-gradient-to-r from-green-500 to-emerald-700 text-white shadow-lg shadow-green-500/50">
                                    📚 Guru
                                </span>
                            @elseif($user->user_type === 'guru_penguji')
                                <span class="px-3 py-1.5 inline-flex text-xs leading-5 font-bold rounded-xl bg-gradient-to-r from-indigo-500 to-purple-700 text-white shadow-lg shadow-indigo-500/50">
                                    ✅ Penguji
                                </span>
                            @else
                                <span class="px-3 py-1.5 inline-flex text-xs leading-5 font-bold rounded-xl bg-gradient-to-r from-blue-500 to-cyan-600 text-white shadow-lg shadow-blue-500/50">
                                    🎓 Siswa
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 font-medium">
                            @if($user->phone)
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                    </svg>
                                    {{ $user->phone }}
                                </div>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($user->jurusan)
                                <div class="text-sm font-bold text-gray-900">{{ $user->jurusan }}</div>
                                @if($user->kelas)
                                    <div class="text-xs text-gray-500 font-medium">{{ $user->kelas }}</div>
                                @endif
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($user->is_active)
                                <span class="px-3 py-1.5 inline-flex text-xs leading-5 font-bold rounded-xl bg-gradient-to-r from-green-500 to-emerald-600 text-white shadow-lg shadow-green-500/30 animate-pulse">
                                    ✅ Aktif
                                </span>
                            @else
                                <span class="px-3 py-1.5 inline-flex text-xs leading-5 font-bold rounded-xl bg-gradient-to-r from-red-500 to-pink-600 text-white shadow-lg shadow-red-500/30">
                                    ❌ Nonaktif
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex items-center justify-end space-x-2">
                                <a href="{{ route('admin.users.show', $user) }}"
                                   class="p-2 text-blue-600 hover:text-white hover:bg-blue-600 rounded-xl hover:shadow-lg hover:shadow-blue-500/50 transition-all duration-300"
                                   title="Lihat Detail">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>
                                <a href="{{ route('admin.users.edit', $user) }}"
                                   class="p-2 text-yellow-600 hover:text-white hover:bg-yellow-600 rounded-xl hover:shadow-lg hover:shadow-yellow-500/50 transition-all duration-300"
                                   title="Edit">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>

                                @if($user->id !== auth()->id())
                                    <form action="{{ route('admin.users.toggle-status', $user) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit"
                                                class="p-2 {{ $user->is_active ? 'text-orange-600 hover:bg-orange-600' : 'text-green-600 hover:bg-green-600' }} hover:text-white rounded-xl hover:shadow-lg transition-all duration-300"
                                                title="{{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                            @if($user->is_active)
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                                </svg>
                                            @else
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                            @endif
                                        </button>
                                    </form>

                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline"
                                          onsubmit="return confirm('⚠️ Yakin ingin menghapus user ini? Tindakan ini tidak dapat dibatalkan!')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="p-2 text-red-600 hover:text-white hover:bg-red-600 rounded-xl hover:shadow-lg hover:shadow-red-500/50 transition-all duration-300"
                                                title="Hapus">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="p-6 bg-gray-100 rounded-full mb-4">
                                    <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-bold text-gray-900 mb-2">Tidak ada data user</h3>
                                <p class="text-sm text-gray-500 mb-6">Belum ada user yang terdaftar atau sesuai dengan filter pencarian.</p>
                                <a href="{{ route('admin.users.create') }}" class="px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl hover:shadow-xl hover:shadow-blue-500/50 transition-all font-semibold">
                                    Tambah User Pertama
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($users->hasPages())
        <div class="bg-gradient-to-r from-gray-50 to-blue-50 px-6 py-4 border-t border-gray-200">
            {{ $users->links() }}
        </div>
    @endif
</div>
@endsection
