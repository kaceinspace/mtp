@extends('layouts.dashboard')

@section('title', 'Notifications')

@section('header')
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">🔔 Notifications</h2>
            <p class="text-gray-600 dark:text-gray-400 mt-1">Stay updated with your project activities</p>
        </div>
        <div class="flex items-center space-x-3">
            @if($unreadCount > 0)
                <form action="{{ route('notifications.markAllAsRead') }}" method="POST">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        Mark All as Read
                    </button>
                </form>
            @endif
        </div>
    </div>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Filter Tabs -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
        <div class="flex border-b border-gray-200 dark:border-gray-700">
            <a href="{{ route('notifications.index', ['filter' => 'all']) }}"
               class="px-6 py-3 text-sm font-medium transition {{ $filter === 'all' ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200' }}">
                All Notifications
            </a>
            <a href="{{ route('notifications.index', ['filter' => 'unread']) }}"
               class="px-6 py-3 text-sm font-medium transition {{ $filter === 'unread' ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200' }}">
                Unread
                @if($unreadCount > 0)
                    <span class="ml-2 px-2 py-0.5 bg-red-500 text-white text-xs font-bold rounded-full">{{ $unreadCount }}</span>
                @endif
            </a>
            <a href="{{ route('notifications.index', ['filter' => 'read']) }}"
               class="px-6 py-3 text-sm font-medium transition {{ $filter === 'read' ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200' }}">
                Read
            </a>
        </div>
    </div>

    <!-- Notifications List -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
        <div class="divide-y divide-gray-200 dark:divide-gray-700">
            @forelse($notifications as $notification)
                <div class="p-6 hover:bg-gray-50 dark:hover:bg-gray-750 transition {{ !$notification->is_read ? 'bg-blue-50 dark:bg-blue-900/20' : '' }}">
                    <div class="flex items-start space-x-4">
                        <!-- Icon -->
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 rounded-lg {{ $notification->color_class }} flex items-center justify-center text-xl">
                                {{ $notification->icon }}
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <h4 class="text-base font-semibold text-gray-900 dark:text-white">
                                        {{ $notification->title }}
                                        @if(!$notification->is_read)
                                            <span class="ml-2 w-2 h-2 bg-blue-500 rounded-full inline-block"></span>
                                        @endif
                                    </h4>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                        {{ $notification->message }}
                                    </p>

                                    <!-- Meta Info -->
                                    <div class="flex items-center space-x-4 mt-3 text-xs text-gray-500 dark:text-gray-400">
                                        @if($notification->relatedUser)
                                            <span class="flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                </svg>
                                                {{ $notification->relatedUser->name }}
                                            </span>
                                        @endif
                                        @if($notification->project)
                                            <span class="flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                                                </svg>
                                                {{ $notification->project->title }}
                                            </span>
                                        @endif
                                        <span class="flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            {{ $notification->created_at->diffForHumans() }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Actions -->
                                <div class="flex items-center space-x-2 ml-4">
                                    @if($notification->action_url)
                                        <a href="{{ $notification->action_url }}"
                                           onclick="markAsRead({{ $notification->id }})"
                                           class="p-2 text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/30 rounded-lg transition"
                                           title="View">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                            </svg>
                                        </a>
                                    @endif

                                    @if(!$notification->is_read)
                                        <form action="{{ route('notifications.markAsRead', $notification) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit"
                                                    class="p-2 text-green-600 hover:bg-green-50 dark:hover:bg-green-900/30 rounded-lg transition"
                                                    title="Mark as read">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                </svg>
                                            </button>
                                        </form>
                                    @endif

                                    <form action="{{ route('notifications.destroy', $notification) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                onclick="return confirm('Delete this notification?')"
                                                class="p-2 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-lg transition"
                                                title="Delete">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-12 text-center">
                    <svg class="w-16 h-16 text-gray-400 dark:text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                    </svg>
                    <p class="text-gray-500 dark:text-gray-400 font-medium">
                        @if($filter === 'unread')
                            No unread notifications
                        @elseif($filter === 'read')
                            No read notifications
                        @else
                            No notifications yet
                        @endif
                    </p>
                    <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">Notifications will appear here as they arrive</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($notifications->hasPages())
            <div class="p-6 border-t border-gray-200 dark:border-gray-700">
                {{ $notifications->links() }}
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
function markAsRead(notificationId) {
    fetch(`/notifications/${notificationId}/mark-read`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
        }
    }).catch(error => console.error('Error:', error));
}
</script>
@endpush
@endsection
