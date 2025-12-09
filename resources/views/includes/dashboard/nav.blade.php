<header class="h-16 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 shadow-sm transition-colors duration-200">
    <div class="h-full px-6 flex items-center justify-between">
        <!-- Left: Mobile Menu Button & Breadcrumb -->
        <div class="flex items-center space-x-4">
            <!-- Mobile Menu Toggle -->
            <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            <!-- Page Title -->
            <div>
                <h1 class="text-xl font-semibold text-gray-800 dark:text-gray-100">@yield('page-title', 'Dashboard')</h1>
            </div>
        </div>

        <!-- Right: Search, Dark Mode, Notifications, User Menu -->
        <div class="flex items-center space-x-3">
            <!-- Search Bar (Hidden on mobile) -->
            <div class="hidden md:block relative">
                <input type="text" placeholder="{{ __('messages.search') }}..." class="w-64 pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent text-sm transition-colors duration-200">
                <svg class="w-5 h-5 text-gray-400 dark:text-gray-500 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>

            <!-- Dark Mode Toggle -->
            <button @click="darkMode = !darkMode; localStorage.setItem('darkMode', darkMode); darkMode ? document.documentElement.classList.add('dark') : document.documentElement.classList.remove('dark')"
                    class="p-2 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition">
                <svg x-show="!darkMode" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                </svg>
                <svg x-show="darkMode" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
            </button>

            <!-- Notifications -->
            <div x-data="notificationDropdown()" x-init="init()" class="relative">
                <button @click="open = !open; if(open) loadNotifications()" class="relative p-2 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    <span x-show="unreadCount > 0" x-text="unreadCount" class="absolute -top-1 -right-1 min-w-[20px] h-5 bg-red-500 text-white text-xs font-bold rounded-full flex items-center justify-center px-1.5"></span>
                </button>

                <!-- Notification Dropdown -->
                <div x-show="open" @click.away="open = false"
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     class="absolute right-0 mt-2 w-80 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 z-50"
                     style="display: none;">
                    <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                        <h3 class="font-semibold text-gray-800 dark:text-gray-100">{{ __('phase2.notifications') }}</h3>
                        <button @click="markAllAsRead()" x-show="unreadCount > 0" class="text-xs text-primary-600 dark:text-primary-400 hover:underline">
                            {{ __('phase2.mark_all_read') }}
                        </button>
                    </div>
                    <div class="max-h-96 overflow-y-auto">
                        <template x-if="loading">
                            <div class="p-8 text-center">
                                <svg class="animate-spin h-8 w-8 text-primary-600 mx-auto" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </div>
                        </template>

                        <template x-if="!loading && notifications.length === 0">
                            <div class="p-8 text-center">
                                <svg class="w-12 h-12 text-gray-400 dark:text-gray-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                </svg>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('phase2.no_notifications') }}</p>
                            </div>
                        </template>

                        <template x-for="notification in notifications" :key="notification.id">
                            <a :href="notification.action_url || '#'" @click="markAsRead(notification.id)"
                               class="block p-4 hover:bg-gray-50 dark:hover:bg-gray-700 border-b border-gray-100 dark:border-gray-700 transition"
                               :class="{'bg-blue-50 dark:bg-blue-900/20': !notification.is_read}">
                                <div class="flex items-start space-x-3">
                                    <div class="flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center text-lg"
                                         :class="getColorClass(notification.type)">
                                        <span x-text="getIcon(notification.type)"></span>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100" x-text="notification.title"></p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400" x-text="notification.message"></p>
                                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-1" x-text="formatTime(notification.created_at)"></p>
                                    </div>
                                    <template x-if="!notification.is_read">
                                        <div class="w-2 h-2 bg-blue-500 rounded-full flex-shrink-0 mt-2"></div>
                                    </template>
                                </div>
                            </a>
                        </template>
                    </div>
                    <div class="p-3 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('notifications.index') }}" class="block text-center text-sm text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 font-medium">{{ __('phase2.view_all_notifications') }}</a>
                    </div>
                </div>
            </div>

            <!-- Language Switcher -->
            <x-language-switcher />

            <!-- User Menu -->
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" class="flex items-center space-x-3 p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name ?? 'User') }}&background=3b82f6&color=fff" alt="User" class="w-8 h-8 rounded-full">
                    <div class="hidden md:block text-left">
                        <p class="text-sm font-medium text-gray-700 dark:text-gray-200">{{ auth()->user()->name ?? 'User Name' }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ ucfirst(auth()->user()->user_type ?? 'User') }}</p>
                    </div>
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

                <!-- User Dropdown -->
                <div x-show="open" @click.away="open = false"
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 py-1 z-50"
                     style="display: none;">
                    <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        {{ __('messages.profile') }}
                    </a>
                    <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        {{ __('messages.settings') }}
                    </a>
                    <hr class="my-1 border-gray-200 dark:border-gray-700">
                    <form method="POST" action="{{ route('logout') ?? '#' }}">
                        @csrf
                        <button type="submit" class="flex items-center w-full px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition">
                            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            {{ __('messages.logout') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>

<script>
function notificationDropdown() {
    return {
        open: false,
        loading: false,
        notifications: [],
        unreadCount: 0,

        init() {
            this.loadUnreadCount();
            // Poll for new notifications every 30 seconds
            setInterval(() => {
                this.loadUnreadCount();
            }, 30000);
        },

        async loadNotifications() {
            this.loading = true;
            try {
                const response = await fetch('{{ route("notifications.recent") }}');
                const data = await response.json();
                this.notifications = data.notifications;
                this.unreadCount = data.unread_count;
            } catch (error) {
                console.error('Error loading notifications:', error);
            } finally {
                this.loading = false;
            }
        },

        async loadUnreadCount() {
            try {
                const response = await fetch('{{ route("notifications.unreadCount") }}');
                const data = await response.json();
                this.unreadCount = data.count;
            } catch (error) {
                console.error('Error loading unread count:', error);
            }
        },

        async markAsRead(notificationId) {
            try {
                await fetch(`/notifications/${notificationId}/mark-read`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    }
                });
                this.loadNotifications();
            } catch (error) {
                console.error('Error marking notification as read:', error);
            }
        },

        async markAllAsRead() {
            try {
                await fetch('{{ route("notifications.markAllAsRead") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    }
                });
                this.loadNotifications();
            } catch (error) {
                console.error('Error marking all as read:', error);
            }
        },

        getIcon(type) {
            const icons = {
                'task_assigned': '📋',
                'task_updated': '🔄',
                'task_completed': '✅',
                'task_due_soon': '⏰',
                'project_created': '📁',
                'project_updated': '✏️',
                'project_member_added': '👤',
                'file_uploaded': '📤',
                'discussion_created': '💬',
                'discussion_replied': '💭',
                'discussion_mentioned': '@️⃣',
                'deadline_approaching': '⚠️',
                'status_changed': '🔔',
            };
            return icons[type] || '🔔';
        },

        getColorClass(type) {
            const colors = {
                'task_assigned': 'bg-green-100 text-green-600',
                'task_updated': 'bg-blue-100 text-blue-600',
                'task_completed': 'bg-purple-100 text-purple-600',
                'task_due_soon': 'bg-orange-100 text-orange-600',
                'project_created': 'bg-green-100 text-green-600',
                'project_updated': 'bg-blue-100 text-blue-600',
                'project_member_added': 'bg-green-100 text-green-600',
                'file_uploaded': 'bg-green-100 text-green-600',
                'discussion_created': 'bg-blue-100 text-blue-600',
                'discussion_replied': 'bg-yellow-100 text-yellow-600',
                'discussion_mentioned': 'bg-yellow-100 text-yellow-600',
                'deadline_approaching': 'bg-orange-100 text-orange-600',
                'status_changed': 'bg-indigo-100 text-indigo-600',
            };
            return colors[type] || 'bg-gray-100 text-gray-600';
        },

        formatTime(timestamp) {
            const date = new Date(timestamp);
            const now = new Date();
            const diffMs = now - date;
            const diffMins = Math.floor(diffMs / 60000);
            const diffHours = Math.floor(diffMs / 3600000);
            const diffDays = Math.floor(diffMs / 86400000);

            if (diffMins < 1) return 'Just now';
            if (diffMins < 60) return `${diffMins} minute${diffMins > 1 ? 's' : ''} ago`;
            if (diffHours < 24) return `${diffHours} hour${diffHours > 1 ? 's' : ''} ago`;
            if (diffDays < 7) return `${diffDays} day${diffDays > 1 ? 's' : ''} ago`;
            return date.toLocaleDateString();
        }
    }
}
</script>
