<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SMK Assalaam Bandung - Sistem Manajemen Project</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <!-- Navbar -->
    <nav class="bg-white border-b border-gray-200 sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-600 to-blue-800 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-lg font-bold text-gray-900">SMK Assalaam</h1>
                        <p class="text-xs text-gray-500">Bandung</p>
                    </div>
                </div>

                <!-- Nav Links -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#features" class="text-gray-700 hover:text-blue-600 font-medium transition">Fitur</a>
                    <a href="#tentang" class="text-gray-700 hover:text-blue-600 font-medium transition">Tentang</a>
                    <a href="#kontak" class="text-gray-700 hover:text-blue-600 font-medium transition">Kontak</a>
                </div>

                <!-- Auth Buttons -->
                <div class="flex items-center space-x-4">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="px-5 py-2 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg hover:shadow-lg transition font-medium">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="px-5 py-2 text-blue-600 hover:text-blue-800 font-medium transition">
                            Masuk
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="px-5 py-2 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg hover:shadow-lg transition font-medium">
                                Daftar
                            </a>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative overflow-hidden bg-gradient-to-br from-blue-600 via-blue-700 to-blue-800">
        <!-- Decorative Background -->
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-yellow-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob"></div>
            <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-blue-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-2000"></div>
            <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-80 h-80 bg-yellow-300 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-4000"></div>
        </div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 md:py-32">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <!-- Left Content -->
                <div class="text-white">
                    <div class="inline-flex items-center space-x-2 bg-yellow-400 bg-opacity-20 backdrop-blur-sm px-4 py-2 rounded-full mb-6">
                        <span class="w-2 h-2 bg-yellow-400 rounded-full animate-pulse"></span>
                        <span class="text-yellow-100 text-sm font-medium">Sistem Manajemen Project Terpadu</span>
                    </div>

                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-6 leading-tight">
                        Kelola Project <span class="text-yellow-400">Lebih Mudah</span> & Terorganisir
                    </h1>

                    <p class="text-xl text-blue-100 mb-8 leading-relaxed">
                        Platform manajemen project khusus untuk siswa dan guru SMK Assalaam Bandung. Pantau progress, kelola tugas, dan kolaborasi dengan tim secara efektif.
                    </p>

                    <div class="flex flex-wrap gap-4">
                        @guest
                            <a href="{{ route('register') }}" class="px-8 py-4 bg-yellow-400 text-blue-900 rounded-lg font-bold hover:bg-yellow-300 transition transform hover:scale-105 shadow-xl">
                                Mulai Sekarang
                            </a>
                            <a href="{{ route('login') }}" class="px-8 py-4 bg-white bg-opacity-10 backdrop-blur-sm text-white border-2 border-white rounded-lg font-bold hover:bg-opacity-20 transition">
                                Login
                            </a>
                        @else
                            <a href="{{ url('/dashboard') }}" class="px-8 py-4 bg-yellow-400 text-blue-900 rounded-lg font-bold hover:bg-yellow-300 transition transform hover:scale-105 shadow-xl">
                                Buka Dashboard
                            </a>
                        @endguest
                    </div>

                    <!-- Stats -->
                    <div class="grid grid-cols-3 gap-6 mt-12 pt-12 border-t border-blue-500 border-opacity-30">
                        <div>
                            <div class="text-3xl font-bold text-yellow-400">500+</div>
                            <div class="text-blue-200 text-sm">Siswa Aktif</div>
                        </div>
                        <div>
                            <div class="text-3xl font-bold text-yellow-400">50+</div>
                            <div class="text-blue-200 text-sm">Guru</div>
                        </div>
                        <div>
                            <div class="text-3xl font-bold text-yellow-400">100+</div>
                            <div class="text-blue-200 text-sm">Project Selesai</div>
                        </div>
                    </div>
                </div>

                <!-- Right Image/Illustration -->
                <div class="relative hidden lg:block">
                    <div class="relative z-10">
                        <!-- Dashboard Preview Card -->
                        <div class="bg-white rounded-2xl shadow-2xl p-6 transform rotate-2 hover:rotate-0 transition-all duration-300">
                            <div class="flex items-center space-x-3 mb-4">
                                <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                                <div class="w-3 h-3 bg-yellow-400 rounded-full"></div>
                                <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                            </div>
                            <div class="space-y-3">
                                <div class="h-4 bg-gray-200 rounded w-3/4"></div>
                                <div class="h-4 bg-gray-200 rounded"></div>
                                <div class="h-4 bg-gray-200 rounded w-5/6"></div>
                                <div class="grid grid-cols-2 gap-3 mt-6">
                                    <div class="h-24 bg-gradient-to-br from-blue-100 to-blue-200 rounded-lg p-3">
                                        <div class="h-2 bg-blue-400 rounded w-12 mb-2"></div>
                                        <div class="h-3 bg-blue-600 rounded w-16"></div>
                                    </div>
                                    <div class="h-24 bg-gradient-to-br from-yellow-100 to-yellow-200 rounded-lg p-3">
                                        <div class="h-2 bg-yellow-400 rounded w-12 mb-2"></div>
                                        <div class="h-3 bg-yellow-600 rounded w-16"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-20 bg-gradient-to-br from-green-50 via-emerald-50 to-teal-50 relative overflow-hidden">
        <!-- Decorative Background -->
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute -top-20 right-20 w-80 h-80 bg-green-300 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob"></div>
            <div class="absolute top-40 -left-20 w-80 h-80 bg-teal-300 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-2000"></div>
            <div class="absolute bottom-20 right-1/3 w-80 h-80 bg-emerald-300 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-4000"></div>
            <!-- Wave Pattern -->
            <div class="absolute inset-0 opacity-10" style="background-image: url('data:image/svg+xml,%3Csvg width=&quot;60&quot; height=&quot;60&quot; viewBox=&quot;0 0 60 60&quot; xmlns=&quot;http://www.w3.org/2000/svg&quot;%3E%3Cpath d=&quot;M54.627 0l.83.828-1.415 1.415L51.8 0h2.827zM5.373 0l-.83.828L5.96 2.243 8.2 0H5.374zM48.97 0l3.657 3.657-1.414 1.414L46.143 0h2.828zM11.03 0L7.372 3.657 8.787 5.07 13.857 0H11.03zm32.284 0L49.8 6.485 48.384 7.9l-7.9-7.9h2.83zM16.686 0L10.2 6.485 11.616 7.9l7.9-7.9h-2.83zm20.97 0l9.315 9.314-1.414 1.414L34.828 0h2.83zM22.344 0L13.03 9.314l1.414 1.414L25.172 0h-2.83zM32 0l12.142 12.142-1.414 1.414L30 .828 17.272 13.556 15.858 12.14 28 0zm-6.485 0L13.03 12.485l1.414 1.414L28 .828 25.515 0zm-15.7 0L.5 9.314 1.914 10.728 13.8 0h-3.986zM51.8 60H48.97L54.627 60l-.83-.828-1.415-1.415L51.8 60zM5.373 60H8.2L5.96 57.757l-1.417 1.415-.83.828zM48.97 60h2.827l-3.657-3.657-1.414 1.414L48.97 60zm-37.94 0h2.83l-7.9-7.9-1.415 1.415L11.03 60zm26.284 0h2.83l-9.314-9.314-1.414 1.414L37.314 60zm-14.628 0h2.83l-7.9-7.9-1.414 1.414L22.686 60zM32 60l12.142-12.142-1.414-1.414L30 59.172 17.272 46.444l-1.414 1.414L28 60zm6.485 0L51.5 47.015l-1.414-1.414L28 59.172 38.485 60zm15.7 0H51.2l-12.485-12.485-1.414 1.414L54.185 60z&quot; fill=&quot;%2310b981&quot; fill-opacity=&quot;0.4&quot; fill-rule=&quot;evenodd&quot;/%3E%3C/svg%3E');"></div>
        </div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Fitur Unggulan</h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">Semua yang Anda butuhkan untuk mengelola project dengan lebih efisien</p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="group p-8 bg-gradient-to-br from-blue-50 to-white rounded-2xl border-2 border-transparent hover:border-blue-300 transition-all duration-300 hover:shadow-xl">
                    <div class="w-14 h-14 bg-gradient-to-br from-blue-600 to-blue-700 rounded-xl flex items-center justify-center mb-5 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Manajemen Project</h3>
                    <p class="text-gray-600">Kelola semua project Anda dalam satu platform. Pantau progress, deadline, dan tim dengan mudah.</p>
                </div>

                <!-- Feature 2 -->
                <div class="group p-8 bg-gradient-to-br from-yellow-50 to-white rounded-2xl border-2 border-transparent hover:border-yellow-300 transition-all duration-300 hover:shadow-xl">
                    <div class="w-14 h-14 bg-gradient-to-br from-yellow-400 to-yellow-500 rounded-xl flex items-center justify-center mb-5 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Task Management</h3>
                    <p class="text-gray-600">Buat, assign, dan tracking task dengan sistem yang intuitif. Tidak ada lagi tugas yang terlewat.</p>
                </div>

                <!-- Feature 3 -->
                <div class="group p-8 bg-gradient-to-br from-blue-50 to-white rounded-2xl border-2 border-transparent hover:border-blue-300 transition-all duration-300 hover:shadow-xl">
                    <div class="w-14 h-14 bg-gradient-to-br from-blue-600 to-blue-700 rounded-xl flex items-center justify-center mb-5 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Kolaborasi Tim</h3>
                    <p class="text-gray-600">Bekerja sama dengan tim Anda. Diskusi, berbagi file, dan koordinasi dalam satu tempat.</p>
                </div>

                <!-- Feature 4 -->
                <div class="group p-8 bg-gradient-to-br from-yellow-50 to-white rounded-2xl border-2 border-transparent hover:border-yellow-300 transition-all duration-300 hover:shadow-xl">
                    <div class="w-14 h-14 bg-gradient-to-br from-yellow-400 to-yellow-500 rounded-xl flex items-center justify-center mb-5 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Laporan & Analytics</h3>
                    <p class="text-gray-600">Dapatkan insight tentang performa project dan tim melalui dashboard analytics yang lengkap.</p>
                </div>

                <!-- Feature 5 -->
                <div class="group p-8 bg-gradient-to-br from-blue-50 to-white rounded-2xl border-2 border-transparent hover:border-blue-300 transition-all duration-300 hover:shadow-xl">
                    <div class="w-14 h-14 bg-gradient-to-br from-blue-600 to-blue-700 rounded-xl flex items-center justify-center mb-5 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Deadline Reminder</h3>
                    <p class="text-gray-600">Notifikasi otomatis untuk deadline yang akan datang. Selalu tepat waktu dalam menyelesaikan tugas.</p>
                </div>

                <!-- Feature 6 -->
                <div class="group p-8 bg-gradient-to-br from-yellow-50 to-white rounded-2xl border-2 border-transparent hover:border-yellow-300 transition-all duration-300 hover:shadow-xl">
                    <div class="w-14 h-14 bg-gradient-to-br from-yellow-400 to-yellow-500 rounded-xl flex items-center justify-center mb-5 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Dokumentasi</h3>
                    <p class="text-gray-600">Upload dan kelola dokumen project dengan aman. Akses file kapan saja, di mana saja.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="tentang" class="py-20 bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 relative overflow-hidden">
        <!-- Decorative Background -->
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute top-20 left-10 w-72 h-72 bg-blue-300 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob"></div>
            <div class="absolute top-40 right-20 w-72 h-72 bg-purple-300 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-2000"></div>
            <div class="absolute -bottom-20 left-40 w-72 h-72 bg-indigo-300 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-4000"></div>
            <!-- Grid Pattern -->
            <div class="absolute inset-0" style="background-image: radial-gradient(circle at 1px 1px, rgb(229 231 235 / 0.3) 1px, transparent 0); background-size: 40px 40px;"></div>
        </div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <!-- Left Content -->
                <div>
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6">Tentang Sistem Kami</h2>
                    <div class="space-y-4 text-gray-600">
                        <p class="text-lg leading-relaxed">
                            <strong class="text-blue-600">Tracking Project Management System</strong> adalah platform digital yang dikembangkan khusus untuk <strong>SMK Assalaam Bandung</strong> dalam mengelola project akhir siswa secara terstruktur dan efisien.
                        </p>
                        <p class="text-lg leading-relaxed">
                            Sistem ini memfasilitasi kolaborasi antara <span class="text-blue-600 font-semibold">Admin</span>, <span class="text-green-600 font-semibold">Guru Pembimbing</span>, <span class="text-indigo-600 font-semibold">Guru Penguji</span>, dan <span class="text-yellow-600 font-semibold">Siswa</span> dalam satu platform terpadu.
                        </p>
                        <div class="bg-blue-50 border-l-4 border-blue-600 p-6 rounded-lg mt-6">
                            <h3 class="font-bold text-blue-900 mb-3">🎯 Visi Kami</h3>
                            <p class="text-blue-800">
                                Menjadi platform terdepan dalam mendukung pembelajaran berbasis project yang terorganisir, kolaboratif, dan efektif untuk menghasilkan lulusan SMK yang kompeten dan siap kerja.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Right Stats & Features -->
                <div class="space-y-6">
                    <!-- Stats Cards -->
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-gradient-to-br from-blue-500 to-blue-700 p-6 rounded-2xl shadow-xl text-white">
                            <div class="text-4xl font-bold mb-2">500+</div>
                            <div class="text-blue-100">👨‍🎓 Siswa Aktif</div>
                        </div>
                        <div class="bg-gradient-to-br from-green-500 to-green-700 p-6 rounded-2xl shadow-xl text-white">
                            <div class="text-4xl font-bold mb-2">50+</div>
                            <div class="text-green-100">👨‍🏫 Guru</div>
                        </div>
                        <div class="bg-gradient-to-br from-yellow-400 to-yellow-600 p-6 rounded-2xl shadow-xl text-white">
                            <div class="text-4xl font-bold mb-2">100+</div>
                            <div class="text-yellow-100">📁 Project Selesai</div>
                        </div>
                        <div class="bg-gradient-to-br from-purple-500 to-purple-700 p-6 rounded-2xl shadow-xl text-white">
                            <div class="text-4xl font-bold mb-2">15+</div>
                            <div class="text-purple-100">🏆 Jurusan</div>
                        </div>
                    </div>

                    <!-- Key Features -->
                    <div class="bg-white p-8 rounded-2xl shadow-lg border-2 border-gray-100">
                        <h3 class="text-xl font-bold text-gray-900 mb-4">✨ Keunggulan Platform</h3>
                        <ul class="space-y-3">
                            <li class="flex items-start space-x-3">
                                <svg class="w-6 h-6 text-green-500 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-gray-700">Real-time progress tracking</span>
                            </li>
                            <li class="flex items-start space-x-3">
                                <svg class="w-6 h-6 text-green-500 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-gray-700">Sistem notifikasi otomatis</span>
                            </li>
                            <li class="flex items-start space-x-3">
                                <svg class="w-6 h-6 text-green-500 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-gray-700">Kolaborasi tim yang efektif</span>
                            </li>
                            <li class="flex items-start space-x-3">
                                <svg class="w-6 h-6 text-green-500 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-gray-700">Dashboard analytics lengkap</span>
                            </li>
                            <li class="flex items-start space-x-3">
                                <svg class="w-6 h-6 text-green-500 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-gray-700">Dokumentasi terpusat & aman</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="kontak" class="py-20 bg-gradient-to-br from-yellow-50 via-orange-50 to-red-50 relative overflow-hidden">
        <!-- Decorative Background -->
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute top-10 right-10 w-96 h-96 bg-yellow-300 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob"></div>
            <div class="absolute bottom-20 left-10 w-96 h-96 bg-orange-300 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-2000"></div>
            <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-red-300 rounded-full mix-blend-multiply filter blur-3xl opacity-10 animate-blob animation-delay-4000"></div>
            <!-- Dot Pattern -->
            <div class="absolute inset-0" style="background-image: radial-gradient(circle at 2px 2px, rgb(251 146 60 / 0.15) 2px, transparent 0); background-size: 50px 50px;"></div>
        </div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Hubungi Kami</h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">Punya pertanyaan? Kami siap membantu Anda</p>
            </div>

            <div class="grid md:grid-cols-2 gap-12 items-start">
                <!-- Contact Info -->
                <div class="space-y-8">
                    <!-- Address -->
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-blue-600 to-blue-700 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 mb-1">📍 Alamat</h3>
                            <p class="text-gray-600">Jl. Situtarate - Terusan Cibaduyut<br>Kab. Bandung - Jawa Barat, Indonesia</p>
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-yellow-400 to-yellow-500 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 mb-1">📧 Email</h3>
                            <p class="text-gray-600">info@smkassalaambandung.sch.id<br>admin@smkassalaambandung.sch.id</p>
                        </div>
                    </div>

                    <!-- Phone -->
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 mb-1">📞 Telepon</h3>
                            <p class="text-gray-600">(022) 1234-5678<br>+62 812-3456-7890</p>
                        </div>
                    </div>

                    <!-- Social Media -->
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 mb-1">🌐 Social Media</h3>
                            <div class="flex space-x-3 mt-2">
                                <a href="#" class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center text-white hover:bg-blue-700 transition">
                                    <span class="font-bold text-sm">f</span>
                                </a>
                                <a href="#" class="w-10 h-10 bg-pink-600 rounded-lg flex items-center justify-center text-white hover:bg-pink-700 transition">
                                    <span class="font-bold text-sm">IG</span>
                                </a>
                                <a href="#" class="w-10 h-10 bg-red-600 rounded-lg flex items-center justify-center text-white hover:bg-red-700 transition">
                                    <span class="font-bold text-sm">YT</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Form -->
                <div class="bg-gradient-to-br from-blue-50 to-white p-8 rounded-2xl border-2 border-blue-100 shadow-xl">
                    <h3 class="text-2xl font-bold text-gray-900 mb-6">💬 Kirim Pesan</h3>
                    <form class="space-y-5">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap</label>
                            <input type="text" placeholder="Masukkan nama Anda" class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                            <input type="email" placeholder="nama@email.com" class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Subjek</label>
                            <input type="text" placeholder="Topik pesan" class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Pesan</label>
                            <textarea rows="4" placeholder="Tulis pesan Anda di sini..." class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition resize-none"></textarea>
                        </div>
                        <button type="submit" class="w-full px-6 py-4 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg font-bold hover:shadow-xl hover:scale-105 transition transform">
                            Kirim Pesan 📤
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 bg-gradient-to-br from-blue-600 via-blue-700 to-blue-800 relative overflow-hidden">
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute top-0 right-0 w-96 h-96 bg-yellow-400 rounded-full mix-blend-multiply filter blur-3xl opacity-10"></div>
            <div class="absolute bottom-0 left-0 w-96 h-96 bg-blue-400 rounded-full mix-blend-multiply filter blur-3xl opacity-10"></div>
        </div>

        <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-6">Siap Untuk Memulai?</h2>
            <p class="text-xl text-blue-100 mb-8 max-w-2xl mx-auto">
                Bergabunglah dengan ratusan siswa dan guru SMK Assalaam yang sudah merasakan kemudahan mengelola project
            </p>
            @guest
                <a href="{{ route('register') }}" class="inline-block px-8 py-4 bg-yellow-400 text-blue-900 rounded-lg font-bold hover:bg-yellow-300 transition transform hover:scale-105 shadow-xl">
                    Daftar Sekarang - Gratis!
                </a>
            @else
                <a href="{{ url('/dashboard') }}" class="inline-block px-8 py-4 bg-yellow-400 text-blue-900 rounded-lg font-bold hover:bg-yellow-300 transition transform hover:scale-105 shadow-xl">
                    Buka Dashboard
                </a>
            @endguest
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-300 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-4 gap-8">
                <!-- About -->
                <div class="col-span-2">
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-600 to-blue-800 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-white">SMK Assalaam Bandung</h3>
                            <p class="text-sm text-gray-400">Sistem Manajemen Project</p>
                        </div>
                    </div>
                    <p class="text-gray-400 mb-4">Platform manajemen project terpadu untuk mendukung pembelajaran berbasis project di SMK Assalaam Bandung.</p>
                </div>

                <!-- Quick Links -->
                <div>
                    <h4 class="text-white font-bold mb-4">Quick Links</h4>
                    <ul class="space-y-2">
                        <li><a href="#features" class="hover:text-yellow-400 transition">Fitur</a></li>
                        <li><a href="#tentang" class="hover:text-yellow-400 transition">Tentang</a></li>
                        <li><a href="#kontak" class="hover:text-yellow-400 transition">Kontak</a></li>
                        <li><a href="{{ route('login') }}" class="hover:text-yellow-400 transition">Login</a></li>
                    </ul>
                </div>

                <!-- Contact -->
                <div>
                    <h4 class="text-white font-bold mb-4">Kontak</h4>
                    <ul class="space-y-2">
                        <li class="flex items-start space-x-2">
                            <svg class="w-5 h-5 text-yellow-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span class="text-sm">Jl. Situtarate - terusan cibaduyut, Kab. Bandung - Jawa Barat</span>
                        </li>
                        <li class="flex items-center space-x-2">
                            <svg class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <span class="text-sm">info@smkassalaambandung.sch.id</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-sm text-gray-400">
                <p>&copy; {{ date('Y') }} SMK Assalaam Bandung. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <style>
        @keyframes blob {
            0%, 100% { transform: translate(0, 0) scale(1); }
            33% { transform: translate(30px, -50px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.9); }
        }
        .animate-blob { animation: blob 7s infinite; }
        .animation-delay-2000 { animation-delay: 2s; }
        .animation-delay-4000 { animation-delay: 4s; }

        /* Smooth Scroll Reveal Animation */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .scroll-reveal {
            animation: fadeInUp 0.8s ease-out forwards;
        }

        /* Smooth scroll behavior untuk semua browser */
        html {
            scroll-behavior: smooth;
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 10px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, #2563EB, #1E40AF);
            border-radius: 5px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(180deg, #1E40AF, #1E3A8A);
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Smooth scroll dengan offset untuk navbar
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    const targetId = this.getAttribute('href');
                    if (targetId === '#') return;

                    const targetElement = document.querySelector(targetId);
                    if (targetElement) {
                        const navbarHeight = 64; // Height of navbar
                        const targetPosition = targetElement.getBoundingClientRect().top + window.pageYOffset - navbarHeight;

                        window.scrollTo({
                            top: targetPosition,
                            behavior: 'smooth'
                        });
                    }
                });
            });

            // Intersection Observer untuk scroll reveal animation
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver(function(entries) {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('scroll-reveal');
                    }
                });
            }, observerOptions);

            // Observe semua section
            document.querySelectorAll('section').forEach(section => {
                observer.observe(section);
            });
        });
    </script>
</body>
</html>
