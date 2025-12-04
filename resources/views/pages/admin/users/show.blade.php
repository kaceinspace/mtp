@extends('layouts.dashboard')

@section('title', 'Detail User')

@section('header')
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600">
                👁️ Detail User
            </h2>
            <p class="text-gray-600 mt-1">Informasi lengkap user {{ $user->name }}</p>
        </div>
        <a href="{{ route('admin.users.index') }}" class="group px-6 py-3 bg-gray-200 text-gray-700 rounded-xl hover:bg-gray-300 hover:shadow-lg transition-all duration-300 font-semibold inline-flex items-center space-x-2">
            <svg class="w-5 h-5 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            <span>Kembali</span>
        </a>
    </div>
@endsection

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <!-- User Profile Header Card -->
    <div class="bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden">
        <div class="h-48 bg-gradient-to-r from-blue-500 via-blue-600 to-indigo-700 relative overflow-hidden">
            <div class="absolute inset-0 bg-black/10"></div>
            <div class="absolute top-4 right-4">
                @if($user->is_active)
                    <span class="px-4 py-2 bg-green-500/90 backdrop-blur-sm text-white rounded-xl font-bold shadow-xl animate-pulse">
                        ✅ Aktif
                    </span>
                @else
                    <span class="px-4 py-2 bg-red-500/90 backdrop-blur-sm text-white rounded-xl font-bold shadow-xl">
                        ❌ Nonaktif
                    </span>
                @endif
            </div>
        </div>
        <div class="px-8 pb-8">
            <div class="flex flex-col md:flex-row md:items-end md:space-x-8 -mt-20">
                <div class="flex-shrink-0 mb-4 md:mb-0">
                    <div class="w-40 h-40 rounded-3xl bg-white border-8 border-white shadow-2xl flex items-center justify-center overflow-hidden group hover:scale-105 transition-transform duration-300">
                        @if($user->profile?->avatar)
                            <img src="{{ asset('storage/' . $user->profile->avatar) }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-blue-500 via-blue-600 to-indigo-700 flex items-center justify-center">
                                <span class="text-6xl font-bold text-white">{{ substr($user->name, 0, 1) }}</span>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="flex-1">
                    <h1 class="text-4xl font-extrabold text-gray-900 mb-2">{{ $user->name }}</h1>
                    <p class="text-lg text-gray-600 flex items-center mb-3">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        {{ $user->email }}
                    </p>
                    <div class="flex flex-wrap gap-2">
                        @if($user->user_type === 'admin')
                            <span class="px-4 py-2 bg-gradient-to-r from-purple-500 to-purple-700 text-white rounded-xl font-bold shadow-lg shadow-purple-500/50">
                                🛡️ Admin
                            </span>
                        @elseif($user->user_type === 'guru')
                            <span class="px-4 py-2 bg-gradient-to-r from-green-500 to-emerald-700 text-white rounded-xl font-bold shadow-lg shadow-green-500/50">
                                📚 Guru Pembimbing
                            </span>
                        @elseif($user->user_type === 'guru_penguji')
                            <span class="px-4 py-2 bg-gradient-to-r from-indigo-500 to-purple-700 text-white rounded-xl font-bold shadow-lg shadow-indigo-500/50">
                                ✅ Guru Penguji
                            </span>
                        @else
                            <span class="px-4 py-2 bg-gradient-to-r from-blue-500 to-cyan-600 text-white rounded-xl font-bold shadow-lg shadow-blue-500/50">
                                🎓 Siswa
                            </span>
                        @endif

                        @if($user->jurusan)
                            <span class="px-4 py-2 bg-gradient-to-r from-yellow-500 to-orange-600 text-white rounded-xl font-bold shadow-lg shadow-yellow-500/50">
                                🎯 {{ $user->jurusan }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-6">
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('admin.users.edit', $user) }}" class="group px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-700 text-white rounded-xl hover:shadow-2xl hover:shadow-blue-500/50 hover:-translate-y-0.5 transition-all duration-300 font-bold inline-flex items-center space-x-2">
                <svg class="w-5 h-5 group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                <span>Edit User</span>
            </a>

            @if($user->id !== auth()->id())
                <form action="{{ route('admin.users.toggle-status', $user) }}" method="POST" class="inline-block">
                    @csrf
                    @if($user->is_active)
                        <button type="submit" class="group px-6 py-3 bg-gradient-to-r from-yellow-500 to-orange-600 text-white rounded-xl hover:shadow-2xl hover:shadow-yellow-500/50 hover:-translate-y-0.5 transition-all duration-300 font-bold inline-flex items-center space-x-2">
                            <svg class="w-5 h-5 group-hover:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                            </svg>
                            <span>Nonaktifkan</span>
                        </button>
                    @else
                        <button type="submit" class="group px-6 py-3 bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-xl hover:shadow-2xl hover:shadow-green-500/50 hover:-translate-y-0.5 transition-all duration-300 font-bold inline-flex items-center space-x-2">
                            <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>Aktifkan</span>
                        </button>
                    @endif
                </form>

                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline-block" onsubmit="return confirm('⚠️ Apakah Anda yakin ingin menghapus user ini? Tindakan ini tidak dapat dibatalkan!')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="group px-6 py-3 bg-gradient-to-r from-red-500 to-pink-600 text-white rounded-xl hover:shadow-2xl hover:shadow-red-500/50 hover:-translate-y-0.5 transition-all duration-300 font-bold inline-flex items-center space-x-2">
                        <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        <span>Hapus User</span>
                    </button>
                </form>
            @endif
        </div>
    </div>

    <!-- User Information Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Basic Information -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-bold text-gray-900 flex items-center">
                    <svg class="w-6 h-6 mr-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Informasi Dasar
                </h3>
            </div>
            <div class="p-6 space-y-4">
                <div class="flex items-start">
                    <div class="w-32 text-sm text-gray-500 font-medium">Nama Lengkap</div>
                    <div class="flex-1 text-gray-900 font-bold">{{ $user->name }}</div>
                </div>
                <div class="flex items-start">
                    <div class="w-32 text-sm text-gray-500 font-medium">Email</div>
                    <div class="flex-1 text-gray-900 font-bold">{{ $user->email }}</div>
                </div>
                <div class="flex items-start">
                    <div class="w-32 text-sm text-gray-500 font-medium">No. Telepon</div>
                    <div class="flex-1 text-gray-900 font-bold">{{ $user->phone ?: '-' }}</div>
                </div>
                <div class="flex items-start">
                    <div class="w-32 text-sm text-gray-500 font-medium">Tipe User</div>
                    <div class="flex-1 text-gray-900 font-bold">
                        @if($user->user_type === 'admin')
                            🛡️ Admin
                        @elseif($user->user_type === 'guru')
                            📚 Guru Pembimbing
                        @elseif($user->user_type === 'guru_penguji')
                            ✅ Guru Penguji
                        @else
                            🎓 Siswa
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Account Details -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-purple-50 to-pink-50 px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-bold text-gray-900 flex items-center">
                    <svg class="w-6 h-6 mr-3 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/>
                    </svg>
                    Detail Akun
                </h3>
            </div>
            <div class="p-6 space-y-4">
                @if(in_array($user->user_type, ['admin', 'guru', 'guru_penguji']))
                    <div class="flex items-start">
                        <div class="w-32 text-sm text-gray-500 font-medium">NIP</div>
                        <div class="flex-1 text-gray-900 font-bold">{{ $user->nip ?: '-' }}</div>
                    </div>
                @endif

                @if($user->user_type === 'siswa')
                    <div class="flex items-start">
                        <div class="w-32 text-sm text-gray-500 font-medium">NISN</div>
                        <div class="flex-1 text-gray-900 font-bold">{{ $user->nisn ?: '-' }}</div>
                    </div>
                @endif

                <div class="flex items-start">
                    <div class="w-32 text-sm text-gray-500 font-medium">Jurusan</div>
                    <div class="flex-1 text-gray-900 font-bold">{{ $user->jurusan ?: '-' }}</div>
                </div>

                @if($user->user_type === 'siswa')
                    <div class="flex items-start">
                        <div class="w-32 text-sm text-gray-500 font-medium">Kelas</div>
                        <div class="flex-1 text-gray-900 font-bold">{{ $user->kelas ?: '-' }}</div>
                    </div>
                @endif

                <div class="flex items-start">
                    <div class="w-32 text-sm text-gray-500 font-medium">Status</div>
                    <div class="flex-1">
                        @if($user->is_active)
                            <span class="px-3 py-1 bg-green-100 text-green-700 rounded-lg font-bold">● Aktif</span>
                        @else
                            <span class="px-3 py-1 bg-red-100 text-red-700 rounded-lg font-bold">● Nonaktif</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Profile Information (if exists) -->
    @if($user->profile)
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
        <div class="bg-gradient-to-r from-green-50 to-emerald-50 px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-bold text-gray-900 flex items-center">
                <svg class="w-6 h-6 mr-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                Informasi Profile
            </h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="flex items-start">
                    <div class="w-40 text-sm text-gray-500 font-medium">Tempat Lahir</div>
                    <div class="flex-1 text-gray-900 font-bold">{{ $user->profile->tempat_lahir ?: '-' }}</div>
                </div>
                <div class="flex items-start">
                    <div class="w-40 text-sm text-gray-500 font-medium">Tanggal Lahir</div>
                    <div class="flex-1 text-gray-900 font-bold">
                        {{ $user->profile->tanggal_lahir ? \Carbon\Carbon::parse($user->profile->tanggal_lahir)->format('d F Y') : '-' }}
                    </div>
                </div>
                <div class="flex items-start">
                    <div class="w-40 text-sm text-gray-500 font-medium">Jenis Kelamin</div>
                    <div class="flex-1 text-gray-900 font-bold">
                        @if($user->profile->jenis_kelamin === 'L')
                            👨 Laki-laki
                        @elseif($user->profile->jenis_kelamin === 'P')
                            👩 Perempuan
                        @else
                            -
                        @endif
                    </div>
                </div>
                <div class="flex items-start">
                    <div class="w-40 text-sm text-gray-500 font-medium">Agama</div>
                    <div class="flex-1 text-gray-900 font-bold">{{ $user->profile->agama ?: '-' }}</div>
                </div>
                <div class="md:col-span-2 flex items-start">
                    <div class="w-40 text-sm text-gray-500 font-medium">Alamat</div>
                    <div class="flex-1 text-gray-900 font-bold">{{ $user->profile->alamat ?: '-' }}</div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Timestamps -->
    <div class="bg-gradient-to-r from-gray-900 to-gray-800 rounded-2xl shadow-2xl overflow-hidden text-white">
        <div class="bg-white/10 backdrop-blur-sm px-6 py-4 border-b border-white/20">
            <h3 class="text-lg font-bold flex items-center">
                <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Informasi Waktu
            </h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <p class="text-sm text-gray-300 mb-1">📅 Terdaftar Sejak</p>
                    <p class="text-xl font-bold">{{ $user->created_at->format('d F Y') }}</p>
                    <p class="text-sm text-gray-400">{{ $user->created_at->format('H:i') }} WIB</p>
                </div>
                <div>
                    <p class="text-sm text-gray-300 mb-1">🔄 Terakhir Diupdate</p>
                    <p class="text-xl font-bold">{{ $user->updated_at->format('d F Y') }}</p>
                    <p class="text-sm text-gray-400">{{ $user->updated_at->format('H:i') }} WIB</p>
                </div>
                <div>
                    <p class="text-sm text-gray-300 mb-1">✉️ Email Verification</p>
                    @if($user->email_verified_at)
                        <p class="text-xl font-bold text-green-400">✓ Terverifikasi</p>
                        <p class="text-sm text-gray-400">{{ $user->email_verified_at->format('d M Y') }}</p>
                    @else
                        <p class="text-xl font-bold text-yellow-400">⚠️ Belum Verifikasi</p>
                        <p class="text-sm text-gray-400">Menunggu konfirmasi</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
