@extends('layouts.dashboard')

@section('title', 'Edit User')

@section('header')
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-yellow-600 to-orange-600">
                ✏️ Edit User
            </h2>
            <p class="text-gray-600 mt-1">Update informasi user <span class="font-semibold text-blue-600">{{ $user->name }}</span></p>
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
<div class="max-w-5xl mx-auto">
    <!-- User Preview Card -->
    <div class="bg-gradient-to-r from-blue-500 via-blue-600 to-indigo-700 rounded-2xl shadow-2xl p-6 mb-6 text-white">
        <div class="flex items-center space-x-4">
            <div class="w-20 h-20 rounded-2xl bg-white/20 backdrop-blur-sm flex items-center justify-center ring-4 ring-white/30">
                <span class="text-3xl font-bold">{{ substr($user->name, 0, 1) }}</span>
            </div>
            <div class="flex-1">
                <h3 class="text-2xl font-bold">{{ $user->name }}</h3>
                <p class="text-blue-100">{{ $user->email }}</p>
                <div class="flex items-center space-x-2 mt-2">
                    @if($user->user_type === 'admin')
                        <span class="px-3 py-1 bg-white/20 backdrop-blur-sm rounded-full text-xs font-bold">🛡️ Admin</span>
                    @elseif($user->user_type === 'guru')
                        <span class="px-3 py-1 bg-white/20 backdrop-blur-sm rounded-full text-xs font-bold">📚 Guru</span>
                    @elseif($user->user_type === 'guru_penguji')
                        <span class="px-3 py-1 bg-white/20 backdrop-blur-sm rounded-full text-xs font-bold">✅ Penguji</span>
                    @else
                        <span class="px-3 py-1 bg-white/20 backdrop-blur-sm rounded-full text-xs font-bold">🎓 Siswa</span>
                    @endif
                    @if($user->is_active)
                        <span class="px-3 py-1 bg-green-500/50 backdrop-blur-sm rounded-full text-xs font-bold">✅ Aktif</span>
                    @else
                        <span class="px-3 py-1 bg-red-500/50 backdrop-blur-sm rounded-full text-xs font-bold">❌ Nonaktif</span>
                    @endif
                </div>
            </div>
            <div class="text-right">
                <p class="text-sm text-blue-100">Bergabung sejak</p>
                <p class="text-lg font-bold">{{ $user->created_at->format('d M Y') }}</p>
            </div>
        </div>
    </div>

    <form action="{{ route('admin.users.update', $user) }}" method="POST" class="bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden backdrop-blur-sm">
        @csrf
        @method('PUT')

        <div class="p-8 space-y-8">
            <!-- User Type Selection -->
            <div>
                <div class="flex items-center mb-4">
                    <div class="p-3 bg-gradient-to-r from-yellow-500 to-orange-600 rounded-xl">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <label class="block text-lg font-bold text-gray-900">Tipe User <span class="text-red-500">*</span></label>
                        <p class="text-sm text-gray-500">Ubah peran user dalam sistem</p>
                    </div>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <label class="group relative flex flex-col items-center justify-center p-6 border-3 border-gray-300 rounded-2xl cursor-pointer hover:border-purple-500 hover:shadow-xl hover:shadow-purple-500/30 hover:-translate-y-1 transition-all duration-300 {{ old('user_type', $user->user_type) == 'admin' ? 'border-purple-500 bg-gradient-to-br from-purple-50 to-purple-100 shadow-xl shadow-purple-500/30' : '' }}">
                        <input type="radio" name="user_type" value="admin" class="sr-only" {{ old('user_type', $user->user_type) == 'admin' ? 'checked' : '' }} onchange="updateFormFields()">
                        <div class="p-4 bg-gradient-to-br from-purple-500 to-purple-700 rounded-2xl mb-3 group-hover:scale-110 transition-transform">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                        <span class="text-sm font-bold text-gray-900">🛡️ Admin</span>
                    </label>

                    <label class="group relative flex flex-col items-center justify-center p-6 border-3 border-gray-300 rounded-2xl cursor-pointer hover:border-green-500 hover:shadow-xl hover:shadow-green-500/30 hover:-translate-y-1 transition-all duration-300 {{ old('user_type', $user->user_type) == 'guru' ? 'border-green-500 bg-gradient-to-br from-green-50 to-emerald-100 shadow-xl shadow-green-500/30' : '' }}">
                        <input type="radio" name="user_type" value="guru" class="sr-only" {{ old('user_type', $user->user_type) == 'guru' ? 'checked' : '' }} onchange="updateFormFields()">
                        <div class="p-4 bg-gradient-to-br from-green-500 to-emerald-700 rounded-2xl mb-3 group-hover:scale-110 transition-transform">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        </div>
                        <span class="text-sm font-bold text-gray-900">📚 Guru</span>
                    </label>

                    <label class="group relative flex flex-col items-center justify-center p-6 border-3 border-gray-300 rounded-2xl cursor-pointer hover:border-indigo-500 hover:shadow-xl hover:shadow-indigo-500/30 hover:-translate-y-1 transition-all duration-300 {{ old('user_type', $user->user_type) == 'guru_penguji' ? 'border-indigo-500 bg-gradient-to-br from-indigo-50 to-purple-100 shadow-xl shadow-indigo-500/30' : '' }}">
                        <input type="radio" name="user_type" value="guru_penguji" class="sr-only" {{ old('user_type', $user->user_type) == 'guru_penguji' ? 'checked' : '' }} onchange="updateFormFields()">
                        <div class="p-4 bg-gradient-to-br from-indigo-500 to-purple-700 rounded-2xl mb-3 group-hover:scale-110 transition-transform">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <span class="text-sm font-bold text-gray-900">✅ Penguji</span>
                    </label>

                    <label class="group relative flex flex-col items-center justify-center p-6 border-3 border-gray-300 rounded-2xl cursor-pointer hover:border-blue-500 hover:shadow-xl hover:shadow-blue-500/30 hover:-translate-y-1 transition-all duration-300 {{ old('user_type', $user->user_type) == 'siswa' ? 'border-blue-500 bg-gradient-to-br from-blue-50 to-cyan-100 shadow-xl shadow-blue-500/30' : '' }}">
                        <input type="radio" name="user_type" value="siswa" class="sr-only" {{ old('user_type', $user->user_type) == 'siswa' ? 'checked' : '' }} onchange="updateFormFields()">
                        <div class="p-4 bg-gradient-to-br from-blue-500 to-cyan-600 rounded-2xl mb-3 group-hover:scale-110 transition-transform">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <span class="text-sm font-bold text-gray-900">🎓 Siswa</span>
                    </label>
                </div>
                @error('user_type')
                    <p class="mt-3 text-sm text-red-600 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div class="border-t-2 border-dashed border-gray-200"></div>

            <!-- Basic Info -->
            <div>
                <div class="flex items-center mb-6">
                    <div class="p-3 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-xl">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-bold text-gray-900">Informasi Dasar</h3>
                        <p class="text-sm text-gray-500">Update data pribadi user</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="group">
                        <label class="block text-sm font-bold text-gray-700 mb-2">👤 Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                               class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 group-hover:border-blue-300 transition-all @error('name') border-red-500 @enderror">
                        @error('name')
                            <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="group">
                        <label class="block text-sm font-bold text-gray-700 mb-2">📧 Email <span class="text-red-500">*</span></label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                               class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 group-hover:border-blue-300 transition-all @error('email') border-red-500 @enderror">
                        @error('email')
                            <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="group">
                        <label class="block text-sm font-bold text-gray-700 mb-2">🔒 Password Baru</label>
                        <input type="password" name="password" placeholder="Kosongkan jika tidak ingin mengubah"
                               class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 group-hover:border-blue-300 transition-all @error('password') border-red-500 @enderror">
                        @error('password')
                            <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                        <p class="mt-2 text-xs text-gray-500 flex items-center">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Kosongkan jika tidak ingin mengubah password
                        </p>
                    </div>

                    <div class="group">
                        <label class="block text-sm font-bold text-gray-700 mb-2">🔒 Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" placeholder="Ketik ulang password baru"
                               class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 group-hover:border-blue-300 transition-all">
                    </div>

                    <div class="group">
                        <label class="block text-sm font-bold text-gray-700 mb-2">📱 No. Telepon</label>
                        <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                               class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 group-hover:border-blue-300 transition-all @error('phone') border-red-500 @enderror">
                        @error('phone')
                            <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Conditional Fields -->
            <div id="nip-field" class="hidden">
                <div class="p-6 bg-gradient-to-r from-yellow-50 to-orange-50 rounded-2xl border-2 border-yellow-200">
                    <div class="group">
                        <label class="block text-sm font-bold text-gray-700 mb-2">🆔 NIP</label>
                        <input type="text" name="nip" value="{{ old('nip', $user->nip) }}"
                               class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 group-hover:border-blue-300 transition-all @error('nip') border-red-500 @enderror">
                        @error('nip')
                            <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div id="nisn-field" class="hidden">
                <div class="p-6 bg-gradient-to-r from-blue-50 to-cyan-50 rounded-2xl border-2 border-blue-200">
                    <div class="group">
                        <label class="block text-sm font-bold text-gray-700 mb-2">🆔 NISN</label>
                        <input type="text" name="nisn" value="{{ old('nisn', $user->nisn) }}"
                               class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 group-hover:border-blue-300 transition-all @error('nisn') border-red-500 @enderror">
                        @error('nisn')
                            <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div id="jurusan-kelas-fields" class="hidden">
                <div class="p-6 bg-gradient-to-r from-purple-50 to-pink-50 rounded-2xl border-2 border-purple-200">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="group">
                            <label class="block text-sm font-bold text-gray-700 mb-2">🎯 Jurusan</label>
                            <select name="jurusan" class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 group-hover:border-blue-300 transition-all">
                                <option value="">Pilih Jurusan</option>
                                <option value="RPL" {{ old('jurusan', $user->jurusan) == 'RPL' ? 'selected' : '' }}>💻 RPL</option>
                                <option value="TKJ" {{ old('jurusan', $user->jurusan) == 'TKJ' ? 'selected' : '' }}>🌐 TKJ</option>
                                <option value="MM" {{ old('jurusan', $user->jurusan) == 'MM' ? 'selected' : '' }}>🎨 MM</option>
                            </select>
                        </div>

                        <div id="kelas-field" class="group">
                            <label class="block text-sm font-bold text-gray-700 mb-2">🏫 Kelas</label>
                            <input type="text" name="kelas" value="{{ old('kelas', $user->kelas) }}" placeholder="XII RPL 1"
                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 group-hover:border-blue-300 transition-all">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Status -->
            <div class="p-6 bg-gradient-to-r from-green-50 to-emerald-50 rounded-2xl border-2 border-green-200">
                <div class="flex items-center space-x-3">
                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $user->is_active) ? 'checked' : '' }}
                           class="w-5 h-5 text-green-600 border-gray-300 rounded focus:ring-green-500 cursor-pointer">
                    <label for="is_active" class="text-sm font-bold text-gray-700 cursor-pointer flex items-center">
                        <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        User aktif
                    </label>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="bg-gradient-to-r from-gray-50 to-yellow-50 px-8 py-6 border-t-2 border-gray-200 flex items-center justify-end space-x-4">
            <a href="{{ route('admin.users.index') }}" class="px-8 py-3 bg-gray-200 text-gray-700 rounded-xl hover:bg-gray-300 hover:shadow-lg transition-all font-semibold inline-flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                <span>Batal</span>
            </a>
            <button type="submit" class="group px-8 py-3 bg-gradient-to-r from-yellow-600 via-yellow-700 to-orange-700 text-white rounded-xl hover:shadow-2xl hover:shadow-yellow-500/50 hover:-translate-y-0.5 transition-all duration-300 font-bold inline-flex items-center space-x-2">
                <svg class="w-5 h-5 group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <span>Update User</span>
            </button>
        </div>
    </form>
</div>

<script>
function updateFormFields() {
    const userType = document.querySelector('input[name="user_type"]:checked')?.value;
    const nipField = document.getElementById('nip-field');
    const nisnField = document.getElementById('nisn-field');
    const jurusanKelasFields = document.getElementById('jurusan-kelas-fields');
    const kelasField = document.getElementById('kelas-field');

    // Hide all conditional fields first
    nipField.classList.add('hidden');
    nisnField.classList.add('hidden');
    jurusanKelasFields.classList.add('hidden');

    // Show relevant fields based on user type
    if (userType === 'admin' || userType === 'guru' || userType === 'guru_penguji') {
        setTimeout(() => {
            nipField.classList.remove('hidden');
            jurusanKelasFields.classList.remove('hidden');
            kelasField.classList.add('hidden');
        }, 100);
    } else if (userType === 'siswa') {
        setTimeout(() => {
            nisnField.classList.remove('hidden');
            jurusanKelasFields.classList.remove('hidden');
            kelasField.classList.remove('hidden');
        }, 100);
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', updateFormFields);
</script>
@endsection
