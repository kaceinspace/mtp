<aside class="fixed inset-y-0 left-0 w-64 bg-gradient-to-b from-primary-600 to-primary-800 dark:from-gray-900 dark:to-gray-800 text-white shadow-2xl transform transition-transform duration-300 ease-in-out z-50 lg:translate-x-0"
       :class="{ '-translate-x-full': !sidebarOpen, 'translate-x-0': sidebarOpen }">

    <!-- Sidebar Header -->
    <div class="flex items-center justify-between h-16 px-6 border-b border-primary-500/30 dark:border-gray-700">
        <div class="flex items-center space-x-3">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/>
            </svg>
            <div>
                <div class="text-lg font-bold">SIMPRO</div>
                <div class="text-xs text-primary-200 dark:text-gray-400">ASSALAAM</div>
            </div>
        </div>
        <button @click="sidebarOpen = false" class="lg:hidden text-white hover:bg-primary-700 dark:hover:bg-gray-700 p-1 rounded">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    <!-- Navigation Menu -->
    <nav class="flex-1 overflow-y-auto px-4 py-6 space-y-2">
        @if(auth()->check() && auth()->user()->user_type === 'admin')
            <!-- Admin Menu -->
            <a href="{{ route('dashboard.admin') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('dashboard.admin') ? 'bg-white/10 text-white' : 'text-white/80 hover:bg-white/10 hover:text-white' }} transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <span class="font-medium">{{ __('messages.dashboard') }}</span>
            </a>

            <a href="{{ route('admin.users.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('admin.users.*') ? 'bg-white/10 text-white' : 'text-white/80 hover:bg-white/10 hover:text-white' }} transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                <span>{{ __('messages.user_management') }}</span>
            </a>

            <a href="{{ route('projects.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('projects.*') ? 'bg-white/10 text-white' : 'text-white/80 hover:bg-white/10 hover:text-white' }} transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/>
                </svg>
                <span>{{ __('messages.all_projects') }}</span>
            </a>

            <a href="{{ route('tasks.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('tasks.*') ? 'bg-white/10 text-white' : 'text-white/80 hover:bg-white/10 hover:text-white' }} transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                </svg>
                <span>{{ __('messages.all_tasks') }}</span>
            </a>

            <a href="{{ route('teams.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('teams.*') ? 'bg-white/10 text-white' : 'text-white/80 hover:bg-white/10 hover:text-white' }} transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                <span>{{ __('messages.all_teams') }}</span>
            </a>

            <div class="pt-4 pb-2">
                <p class="px-4 text-xs font-semibold text-white/50 uppercase tracking-wider">{{ __('messages.management') }}</p>
            </div>

            <a href="#" class="flex items-center space-x-3 px-4 py-3 rounded-lg text-white/80 hover:bg-white/10 hover:text-white transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                <span>{{ __('messages.reports') }}</span>
            </a>

            <a href="#" class="flex items-center space-x-3 px-4 py-3 rounded-lg text-white/80 hover:bg-white/10 hover:text-white transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <span>{{ __('messages.settings') }}</span>
            </a>

        @elseif(auth()->check() && auth()->user()->user_type === 'team_lead')
            <!-- Team Lead Menu -->
            <a href="{{ route('dashboard.team_lead') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('dashboard.team_lead') ? 'bg-white/10 text-white' : 'text-white/80 hover:bg-white/10 hover:text-white' }} transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <span class="font-medium">Dashboard</span>
            </a>

            <a href="{{ route('projects.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('projects.*') ? 'bg-white/10 text-white' : 'text-white/80 hover:bg-white/10 hover:text-white' }} transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/>
                </svg>
                <span>My Projects</span>
            </a>

            <a href="{{ route('tasks.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('tasks.*') ? 'bg-white/10 text-white' : 'text-white/80 hover:bg-white/10 hover:text-white' }} transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                </svg>
                <span>My Tasks</span>
            </a>

            <a href="{{ route('teams.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('teams.*') ? 'bg-white/10 text-white' : 'text-white/80 hover:bg-white/10 hover:text-white' }} transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                <span>{{ __('messages.my_teams') }}</span>
            </a>

            <div class="pt-4 pb-2">
                <p class="px-4 text-xs font-semibold text-white/50 uppercase tracking-wider">{{ __('messages.management') }}</p>
            </div>

            <a href="#" class="flex items-center space-x-3 px-4 py-3 rounded-lg text-white/80 hover:bg-white/10 hover:text-white transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                <span>Reports</span>
            </a>

        @elseif(auth()->check() && auth()->user()->user_type === 'team_member')
            <!-- Team Member Menu -->
            <a href="{{ route('dashboard.team_member') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg bg-white/10 text-white">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <span class="font-medium">Dashboard</span>
            </a>

            <a href="{{ route('projects.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('projects.*') ? 'bg-white/10 text-white' : 'text-white/80 hover:bg-white/10 hover:text-white' }} transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/>
                </svg>
                <span>{{ __('messages.my_projects') }}</span>
            </a>

            <a href="{{ route('tasks.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('tasks.*') ? 'bg-white/10 text-white' : 'text-white/80 hover:bg-white/10 hover:text-white' }} transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                </svg>
                <span>{{ __('messages.my_tasks') }}</span>
            </a>

            <a href="{{ route('teams.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('teams.*') ? 'bg-white/10 text-white' : 'text-white/80 hover:bg-white/10 hover:text-white' }} transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                <span>{{ __('messages.my_teams') }}</span>
            </a>

            <div class="pt-4 pb-2">
                <p class="px-4 text-xs font-semibold text-white/50 uppercase tracking-wider">{{ __('messages.collaboration') }}</p>
            </div>

            <a href="#" class="flex items-center space-x-3 px-4 py-3 rounded-lg text-white/80 hover:bg-white/10 hover:text-white transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                </svg>
                <span>{{ __('phase2.chat_discussion') }}</span>
            </a>

            <a href="#" class="flex items-center space-x-3 px-4 py-3 rounded-lg text-white/80 hover:bg-white/10 hover:text-white transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                </svg>
                <span>{{ __('messages.documents') }}</span>
            </a>

            <a href="#" class="flex items-center space-x-3 px-4 py-3 rounded-lg text-white/80 hover:bg-white/10 hover:text-white transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                </svg>
                <span>{{ __('messages.my_achievements') }}</span>
            </a>
        @else
            <!-- Default Menu -->
            <a href="#" class="flex items-center space-x-3 px-4 py-3 rounded-lg bg-white/10 text-white">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <span class="font-medium">Dashboard</span>
            </a>
        @endif
    </nav>

    <!-- Sidebar Footer - User Profile -->
    <div class="p-4 border-t border-primary-500/30 dark:border-gray-700">
        <div class="flex items-center space-x-3 p-3 bg-white/10 dark:bg-white/5 rounded-lg">
            <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name ?? 'User') }}&background=3b82f6&color=fff" alt="User" class="w-10 h-10 rounded-full">
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-white truncate">{{ auth()->user()->name ?? 'User Name' }}</p>
                <p class="text-xs text-primary-200 dark:text-gray-400 truncate">{{ ucfirst(auth()->user()->user_type ?? 'User') }}</p>
            </div>
        </div>
    </div>
</aside>

<!-- Overlay for mobile -->
<div x-show="sidebarOpen" @click="sidebarOpen = false"
     class="fixed inset-0 bg-black/50 z-40 lg:hidden"
     x-transition:enter="transition-opacity ease-linear duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition-opacity ease-linear duration-300"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     style="display: none;">
</div>

<!-- Main content wrapper with left margin for sidebar -->
<div class="lg:ml-64">
    <!-- Content will be injected here by the layout -->
</div>
