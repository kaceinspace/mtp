<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard') - {{ config('app.name', 'Project Tracker') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    @stack('styles')

    <script>
        // Dark mode initialization
        if (localStorage.getItem('darkMode') === 'true' || (!('darkMode' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
</head>
<body class="bg-gray-50 dark:bg-gray-900 font-sans antialiased transition-colors duration-200" x-data="{ sidebarOpen: false, darkMode: localStorage.getItem('darkMode') === 'true' }">
    <div class="flex h-screen overflow-hidden">{
        <!-- Sidebar -->
        @include('includes.dashboard.side')

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Impersonate Banner -->
            @if(auth()->check() && auth()->user()->isImpersonated())
                <div class="bg-gradient-to-r from-orange-500 to-red-600 text-white shadow-lg z-50">
                    <div class="px-4 py-3 sm:px-6 lg:px-8">
                        <div class="flex items-center justify-between flex-wrap gap-3">
                            <div class="flex items-center flex-1 min-w-0">
                                <span class="flex p-2 rounded-lg bg-white/20 backdrop-blur-sm flex-shrink-0">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                                    </svg>
                                </span>
                                <p class="ml-3 font-semibold text-sm sm:text-base truncate">
                                    🎭 Login sebagai: <span class="font-bold underline">{{ auth()->user()->name }}</span>
                                    <span class="text-xs sm:text-sm opacity-90 hidden sm:inline">({{ auth()->user()->email }})</span>
                                </p>
                            </div>
                            <div class="flex-shrink-0">
                                <form action="{{ route('impersonate.leave') }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="flex items-center justify-center px-4 py-2 border-2 border-white rounded-lg text-xs sm:text-sm font-bold text-white hover:bg-white hover:text-red-600 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 17l-4 4m0 0l-4-4m4 4V3"/>
                                        </svg>
                                        <span class="hidden sm:inline">Kembali ke Admin</span>
                                        <span class="sm:hidden">Exit</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Top Navbar -->
            @include('includes.dashboard.nav')

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto bg-gray-50 dark:bg-gray-900 p-6 transition-colors duration-200">
                <!-- Page Header -->
                @if(isset($header) || View::hasSection('header'))
                <div class="mb-6">
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-200 dark:border-gray-700 transition-colors duration-200">
                        {{ $header ?? '' }}
                        @yield('header')
                    </div>
                </div>
                @endif

                <!-- Alerts -->
                @if(session('success'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                     class="mb-6 bg-green-50 dark:bg-green-900/20 border-l-4 border-green-500 p-4 rounded-lg transition-colors duration-200">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <p class="text-sm font-medium text-green-800 dark:text-green-200">{{ session('success') }}</p>
                    </div>
                </div>
                @endif

                @if(session('error'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                     class="mb-6 bg-red-50 dark:bg-red-900/20 border-l-4 border-red-500 p-4 rounded-lg transition-colors duration-200">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-red-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        <p class="text-sm font-medium text-red-800 dark:text-red-200">{{ session('error') }}</p>
                    </div>
                </div>
                @endif

                <!-- Main Content -->
                @yield('content')
            </main>

            <!-- Footer -->
            @include('includes.dashboard.footer')
        </div>
    </div>

    @livewireScripts
    @stack('scripts')
</body>
</html>
