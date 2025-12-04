<nav class="bg-white shadow-sm border-b border-gray-200" x-data="{ mobileMenuOpen: false }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <!-- Logo & Brand -->
            <div class="flex items-center">
                <a href="{{ route('home') ?? '/' }}" class="flex items-center space-x-3">
                    <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/>
                    </svg>
                    <span class="text-xl font-bold text-gray-900">Project Tracker</span>
                </a>

                <!-- Desktop Navigation -->
                <div class="hidden md:flex md:ml-10 md:space-x-8">
                    <a href="#features" class="text-gray-700 hover:text-indigo-600 px-3 py-2 text-sm font-medium transition">Features</a>
                    <a href="#about" class="text-gray-700 hover:text-indigo-600 px-3 py-2 text-sm font-medium transition">About</a>
                    <a href="#contact" class="text-gray-700 hover:text-indigo-600 px-3 py-2 text-sm font-medium transition">Contact</a>
                </div>
            </div>

            <!-- Right Side Actions -->
            <div class="flex items-center space-x-4">
                @auth
                    <a href="{{ route('dashboard') ?? '#' }}" class="hidden md:inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 transition">
                        Go to Dashboard
                    </a>
                @else
                    <a href="{{ route('login') ?? '#' }}" class="hidden md:inline-flex text-gray-700 hover:text-indigo-600 px-3 py-2 text-sm font-medium transition">
                        Login
                    </a>
                    <a href="{{ route('register') ?? '#' }}" class="hidden md:inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 transition">
                        Get Started
                    </a>
                @endauth

                <!-- Mobile menu button -->
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden inline-flex items-center justify-center p-2 rounded-lg text-gray-700 hover:text-indigo-600 hover:bg-gray-100 transition">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        <path x-show="mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div x-show="mobileMenuOpen"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="md:hidden border-t border-gray-200"
         style="display: none;">
        <div class="px-2 pt-2 pb-3 space-y-1">
            <a href="#features" class="block px-3 py-2 rounded-lg text-base font-medium text-gray-700 hover:text-indigo-600 hover:bg-gray-50 transition">Features</a>
            <a href="#about" class="block px-3 py-2 rounded-lg text-base font-medium text-gray-700 hover:text-indigo-600 hover:bg-gray-50 transition">About</a>
            <a href="#contact" class="block px-3 py-2 rounded-lg text-base font-medium text-gray-700 hover:text-indigo-600 hover:bg-gray-50 transition">Contact</a>

            @auth
                <a href="{{ route('dashboard') ?? '#' }}" class="block px-3 py-2 rounded-lg text-base font-medium text-white bg-indigo-600 hover:bg-indigo-700 transition">
                    Go to Dashboard
                </a>
            @else
                <a href="{{ route('login') ?? '#' }}" class="block px-3 py-2 rounded-lg text-base font-medium text-gray-700 hover:text-indigo-600 hover:bg-gray-50 transition">Login</a>
                <a href="{{ route('register') ?? '#' }}" class="block px-3 py-2 rounded-lg text-base font-medium text-white bg-indigo-600 hover:bg-indigo-700 transition">Get Started</a>
            @endauth
        </div>
    </div>
</nav>
