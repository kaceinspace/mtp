@extends('layouts.dashboard')

@section('title', 'Detail User')

@section('header')
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Detail User</h2>
            <p class="text-gray-600 mt-1">Informasi lengkap user</p>
        </div>
        <a href="{{ route('admin.users.index') }}" class="px-5 py-2.5 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition font-medium inline-flex items-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            <span>Kembali</span>
        </a>
    </div>
@endsection

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- User Header Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 h-32"></div>
        <div class="px-6 pb-6">
            <div class="flex items-start space-x-6 -mt-16">
                <div class="flex-shrink-0">
                    <div class="w-32 h-32 rounded-2xl bg-white border-4 border-white shadow-lg flex items-center justify-center">
                        @if($user->profile?->avatar)
                            <img src="{{ asset('storage/' . $user->profile->avatar) }}" alt="{{ $user->name }}" class="w-full h-full object-cover rounded-2xl">
                        @else
                            <div class="w-full h-full rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center">
                                <span class="text-4xl font-bold text-white">{{ substr($user->name, 0, 1) }}</span>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="flex-1 pt-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h1>
                            <p class="text-gray-600 mt-1">{{ $user->email }}</p>
                            <div class="flex items-center space-x-3 mt-3">
                                @if($user->user_type === 'admin')
                                    <span class="px-3 py-1 bg-purple-100 text-purple-700 rounded-full text-sm font-medium">Admin</span>
                                @elseif($user->user_type === 'guru')
                                    <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm font-medium">Guru Pembimbing</span>
                                @elseif($user->user_type === 'guru_penguji')
                                    <span class="px-3 py-1 bg-indigo-100 text-indigo-700 rounded-full text-sm font-medium">Guru Penguji</span>
                                @else
                                    <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm font-medium">Siswa</span>
                                @endif

                                @if($user->is_active)
                                    <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm font-medium flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        Aktif
                                    </span>
                                @else
                                    <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-sm font-medium flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                        </svg>
                                        Nonaktif
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('admin.users.edit', $user) }}" class="px-5 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg hover:shadow-lg transition font-medium inline-flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                <span>Edit User</span>
            </a>

            @if($user->id !== auth()->id())
                <form action="{{ route('admin.users.toggle-status', $user) }}" method="POST" class="inline-block">
                    @csrf
                    @if($user->is_active)
                        <button type="submit" class="px-5 py-2.5 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition font-medium inline-flex items-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                            </svg>
                            <span>Nonaktifkan</span>
                        </button>
                    @else
                        <button type="submit" class="px-5 py-2.5 bg-green-500 text-white rounded-lg hover:bg-green-600 transition font-medium inline-flex items-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>Aktifkan</span>
                        </button>
                    @endif
                </form>

                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus user ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-5 py-2.5 bg-red-500 text-white rounded-lg hover:bg-red-600 transition font-medium inline-flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        <span>Hapus User</span>
                    </button>
                </form>
            @endif
        </div>
    </div>

    <!-- User Information -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Basic Information -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Informasi Dasar
            </h3>
            <div class="space-y-4">
                <div>
                    <label class="text-sm text-gray-500">Nama Lengkap</label>
                    <p class="text-gray-900 font-medium">{{ $user->name }}</p>
                </div>
                <div>
                    <label class="text-sm text-gray-500">Email</label>
                    <p class="text-gray-900 font-medium">{{ $user->email }}</p>
                </div>
                <div>
                    <label class="text-sm text-gray-500">No. Telepon</label>
                    <p class="text-gray-900 font-medium">{{ $user->phone ?: '-' }}</p>
                </div>
                <div>
                    <label class="text-sm text-gray-500">Tipe User</label>
                    <p class="text-gray-900 font-medium">
                        @if($user->user_type === 'admin')
                            Admin
                        @elseif($user->user_type === 'guru')
                            Guru Pembimbing
                        @elseif($user->user_type === 'guru_penguji')
                            Guru Penguji
                        @else
                            Siswa
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <!-- Account Details -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/>
                </svg>
                Detail Akun
            </h3>
            <div class="space-y-4">
                @if(in_array($user->user_type, ['admin', 'guru', 'guru_penguji']))
                    <div>
                        <label class="text-sm text-gray-500">NIP</label>
                        <p class="text-gray-900 font-medium">{{ $user->nip ?: '-' }}</p>
                    </div>
                @endif

                @if($user->user_type === 'siswa')
                    <div>
                        <label class="text-sm text-gray-500">NISN</label>
                        <p class="text-gray-900 font-medium">{{ $user->nisn ?: '-' }}</p>
                    </div>
                @endif

                <div>
                    <label class="text-sm text-gray-500">Jurusan</label>
                    <p class="text-gray-900 font-medium">{{ $user->jurusan ?: '-' }}</p>
                </div>

                @if($user->user_type === 'siswa')
                    <div>
                        <label class="text-sm text-gray-500">Kelas</label>
                        <p class="text-gray-900 font-medium">{{ $user->kelas ?: '-' }}</p>
                    </div>
                @endif

                <div>
                    <label class="text-sm text-gray-500">Status</label>
                    <p class="text-gray-900 font-medium">
                        @if($user->is_active)
                            <span class="text-green-600">● Aktif</span>
                        @else
                            <span class="text-red-600">● Nonaktif</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Profile Information -->
    @if($user->profile)
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            Informasi Profile
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="text-sm text-gray-500">Tempat Lahir</label>
                <p class="text-gray-900 font-medium">{{ $user->profile->tempat_lahir ?: '-' }}</p>
            </div>
            <div>
                <label class="text-sm text-gray-500">Tanggal Lahir</label>
                <p class="text-gray-900 font-medium">{{ $user->profile->tanggal_lahir ? \Carbon\Carbon::parse($user->profile->tanggal_lahir)->format('d F Y') : '-' }}</p>
            </div>
            <div>
                <label class="text-sm text-gray-500">Jenis Kelamin</label>
                <p class="text-gray-900 font-medium">
                    @if($user->profile->jenis_kelamin === 'L')
                        Laki-laki
                    @elseif($user->profile->jenis_kelamin === 'P')
                        Perempuan
                    @else
                        -
                    @endif
                </p>
            </div>
            <div>
                <label class="text-sm text-gray-500">Agama</label>
                <p class="text-gray-900 font-medium">{{ $user->profile->agama ?: '-' }}</p>
            </div>
            <div class="md:col-span-2">
                <label class="text-sm text-gray-500">Alamat</label>
                <p class="text-gray-900 font-medium">{{ $user->profile->alamat ?: '-' }}</p>
            </div>
        </div>
    </div>
    @endif

    <!-- Timestamps -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Informasi Waktu
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <label class="text-sm text-gray-500">Dibuat</label>
                <p class="text-gray-900 font-medium">{{ $user->created_at->format('d F Y, H:i') }}</p>
            </div>
            <div>
                <label class="text-sm text-gray-500">Terakhir Diupdate</label>
                <p class="text-gray-900 font-medium">{{ $user->updated_at->format('d F Y, H:i') }}</p>
            </div>
            <div>
                <label class="text-sm text-gray-500">Email Terverifikasi</label>
                <p class="text-gray-900 font-medium">
                    @if($user->email_verified_at)
                        <span class="text-green-600">✓ {{ $user->email_verified_at->format('d F Y') }}</span>
                    @else
                        <span class="text-yellow-600">Belum terverifikasi</span>
                    @endif
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
