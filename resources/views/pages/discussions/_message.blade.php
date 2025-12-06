@if($isReply ?? false)
<!-- Reply Message -->
<div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
    <div class="flex items-start justify-between mb-2">
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-full bg-primary-500 flex items-center justify-center text-white text-sm font-semibold">
                {{ strtoupper(substr($discussion->user->name, 0, 2)) }}
            </div>
            <div>
                <h5 class="font-semibold text-sm text-gray-900 dark:text-white">{{ $discussion->user->name }}</h5>
                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $discussion->created_at->diffForHumans() }}</p>
            </div>
        </div>
        @if(auth()->id() === $discussion->user_id || auth()->user()->user_type === 'admin')
        <form action="{{ route('discussions.destroy', $discussion) }}" method="POST" class="inline"
              onsubmit="return confirm('Delete this reply?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="text-red-500 hover:text-red-700 text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
            </button>
        </form>
        @endif
    </div>
    <p class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $discussion->message }}</p>
</div>
@else
<!-- Main Discussion Message -->
<div data-discussion-id="{{ $discussion->id }}" class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 {{ $discussion->is_pinned ? 'ring-2 ring-yellow-500' : '' }}">
    <!-- Message Header -->
    <div class="flex items-start justify-between mb-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-primary-600 flex items-center justify-center text-white font-semibold">
                {{ strtoupper(substr($discussion->user->name, 0, 2)) }}
            </div>
            <div>
                <h4 class="font-semibold text-gray-900 dark:text-white">{{ $discussion->user->name }}</h4>
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $discussion->created_at->diffForHumans() }}</p>
            </div>
            @if($discussion->is_pinned)
            <span class="text-xs bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200 px-2 py-1 rounded-full flex items-center gap-1">
                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 2a1 1 0 011 1v1.323l3.954 1.582 1.599-.8a1 1 0 01.894 1.79l-1.233.616 1.738 5.42a1 1 0 01-.285 1.05A3.989 3.989 0 0115 15a3.989 3.989 0 01-2.667-1.019 1 1 0 01-.285-1.05l1.738-5.42-1.233-.617a1 1 0 01.894-1.788l1.599.799L11 4.323V3a1 1 0 011-1z"/>
                </svg>
                Pinned
            </span>
            @endif
        </div>
    </div>

    <!-- Message Content -->
    <div class="prose dark:prose-invert max-w-none mb-4">
        <p class="text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $discussion->message }}</p>
    </div>

    <!-- Attachments -->
    @if($discussion->attachments)
    <div class="mb-4 flex flex-wrap gap-2">
        @foreach($discussion->attachments as $attachment)
        <a href="{{ Storage::url($attachment['path']) }}" target="_blank"
           class="flex items-center gap-2 px-3 py-2 bg-gray-100 dark:bg-gray-700 rounded-lg text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
            </svg>
            {{ $attachment['name'] }}
        </a>
        @endforeach
    </div>
    @endif

    <!-- Reply Button -->
    <button onclick="window.dispatchEvent(new CustomEvent('toggle-reply', { detail: {{ $discussion->id }} }))"
            class="text-sm text-primary-600 dark:text-primary-400 hover:underline flex items-center gap-1">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
        </svg>
        Reply ({{ $discussion->replies->count() }})
    </button>
</div>
@endif
