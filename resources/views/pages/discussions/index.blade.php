@extends('layouts.dashboard')

@section('title', __('messages.discussion') . ' - ' . $project->title)

@section('content')
<div class="container mx-auto px-4 py-6" x-data="discussionBoard()">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between mb-2">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ __('messages.team_discussion') }}</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">{{ $project->title }}</p>
            </div>
            <a href="{{ route('projects.show', $project) }}"
               class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                {{ __('messages.back_to_project') }}
            </a>
        </div>
    </div>

    <!-- New Messages Notification Banner -->
    <div id="new-messages-banner" class="hidden bg-blue-500 text-white rounded-lg shadow-lg p-4 mb-4 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <svg class="w-6 h-6 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
            </svg>
            <span><span class="message-count font-semibold">0</span> {{ __('messages.new_messages') }}</span>
        </div>
        <button @click="reloadMessages()"
                class="px-4 py-2 bg-white text-blue-600 rounded-lg hover:bg-blue-50 transition font-medium">
            {{ __('messages.refresh') }}
        </button>
    </div>

    <!-- New Message Form -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('messages.post_message') }}</h3>
        <form action="{{ route('discussions.store', $project) }}" method="POST" enctype="multipart/form-data" @submit="submitMessage">
            @csrf
            <div class="mb-4">
                <textarea name="message" rows="4" required
                          class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 dark:bg-gray-700 dark:text-white resize-none"
                          placeholder="{{ __('messages.type_message_here') }}"></textarea>
                @error('message')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between">
                <div>
                    <label class="inline-flex items-center px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg cursor-pointer hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                        </svg>
                        {{ __('messages.attach_files') }}
                        <input type="file" name="attachments[]" multiple class="hidden" accept="image/*,.pdf,.doc,.docx,.xls,.xlsx,.zip">
                    </label>
                </div>
                <button type="submit"
                        class="px-6 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-lg transition flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                    </svg>
                    {{ __('messages.post_message') }}
                </button>
            </div>
        </form>
    </div>

    <!-- Discussion Messages -->
    <div id="discussions-container" class="space-y-4">
        @forelse($discussions as $discussion)
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
                        {{ __('messages.pinned') }}
                    </span>
                    @endif
                </div>

                <!-- Actions Menu -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"/>
                        </svg>
                    </button>
                    <div x-show="open" @click.away="open = false"
                         class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-700 rounded-lg shadow-lg border border-gray-200 dark:border-gray-600 z-10">
                        @can('admin')
                        <form action="{{ route('discussions.togglePin', $discussion) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600">
                                {{ $discussion->is_pinned ? __('messages.unpin_message') : __('messages.pin_message') }}
                            </button>
                        </form>
                        @endcan
                        @if(auth()->id() === $discussion->user_id || auth()->user()->user_type === 'admin')
                        <button @click="editMessage({{ $discussion->id }}, '{{ addslashes($discussion->message) }}')"
                                class="w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600">
                            {{ __('messages.edit_message') }}
                        </button>
                        <form action="{{ route('discussions.destroy', $discussion) }}" method="POST"
                              onsubmit="return confirm('{{ __('messages.confirm_delete_message') }}');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-gray-100 dark:hover:bg-gray-600">
                                {{ __('messages.delete_message') }}
                            </button>
                        </form>
                        @endif
                    </div>
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
            <button @click="toggleReply({{ $discussion->id }})"
                    class="text-sm text-primary-600 dark:text-primary-400 hover:underline flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                </svg>
                {{ __('messages.reply') }}
            </button>

            <!-- Reply Form -->
            <div x-show="replyTo === {{ $discussion->id }}"
                 x-transition
                 class="mt-4 pl-12 border-l-2 border-gray-200 dark:border-gray-600">
                <form action="{{ route('discussions.store', $project) }}" method="POST">
                    @csrf
                    <input type="hidden" name="parent_id" value="{{ $discussion->id }}">
                    <textarea name="message" rows="3" required
                              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 dark:bg-gray-700 dark:text-white resize-none mb-2"
                              placeholder="{{ __('messages.write_reply') }}"></textarea>
                    <div class="flex gap-2">
                        <button type="submit"
                                class="px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-lg text-sm transition">
                            {{ __('messages.post_reply') }}
                        </button>
                        <button type="button" @click="replyTo = null"
                                class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-700 rounded-lg text-sm transition">
                            {{ __('messages.cancel') }}
                        </button>
                    </div>
                </form>
            </div>

            <!-- Replies -->
            @if($discussion->replies->count() > 0)
            <div class="mt-4 pl-12 space-y-4 border-l-2 border-gray-200 dark:border-gray-600">
                @foreach($discussion->replies as $reply)
                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                    <div class="flex items-start justify-between mb-2">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-full bg-primary-500 flex items-center justify-center text-white text-sm font-semibold">
                                {{ strtoupper(substr($reply->user->name, 0, 2)) }}
                            </div>
                            <div>
                                <h5 class="font-semibold text-sm text-gray-900 dark:text-white">{{ $reply->user->name }}</h5>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $reply->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        @if(auth()->id() === $reply->user_id || auth()->user()->user_type === 'admin')
                        <form action="{{ route('discussions.destroy', $reply) }}" method="POST" class="inline"
                              onsubmit="return confirm('{{ __('messages.confirm_delete_reply') }}');">
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
                    <p class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $reply->message }}</p>
                </div>
                @endforeach
            </div>
            @endif
        </div>
        @empty
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-12 text-center">
            <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
            </svg>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">{{ __('messages.no_discussions_yet') }}</h3>
            <p class="text-gray-600 dark:text-gray-400">{{ __('messages.be_first_start_conversation') }}</p>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($discussions->hasPages())
    <div class="mt-6">
        {{ $discussions->links() }}
    </div>
    @endif
</div>

@push('scripts')
<script>
function discussionBoard() {
    return {
        replyTo: null,
        editingMessage: null,
        lastMessageId: {{ $discussions->first()->id ?? 0 }},
        isPolling: true,
        newMessagesCount: 0,

        init() {
            // Start polling for new messages every 5 seconds
            if (this.isPolling) {
                setInterval(() => {
                    this.checkNewMessages();
                }, 5000);
            }
        },

        async checkNewMessages() {
            try {
                const response = await fetch(`/projects/{{ $project->id }}/discussions/check-new?last_id=${this.lastMessageId}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    }
                });

                if (response.ok) {
                    const data = await response.json();
                    if (data.has_new_messages) {
                        this.newMessagesCount = data.count;
                        this.showNewMessageNotification(data.count);
                    }
                }
            } catch (error) {
                console.error('Error checking new messages:', error);
            }
        },

        showNewMessageNotification(count) {
            // Show notification banner
            const banner = document.getElementById('new-messages-banner');
            if (banner) {
                banner.classList.remove('hidden');
                banner.querySelector('.message-count').textContent = count;
            }
        },

        reloadMessages() {
            window.location.reload();
        },

        toggleReply(discussionId) {
            this.replyTo = this.replyTo === discussionId ? null : discussionId;
        },

        editMessage(discussionId, currentMessage) {
            const newMessage = prompt('Edit your message:', currentMessage);
            if (newMessage && newMessage !== currentMessage) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/discussions/${discussionId}`;
                form.innerHTML = `
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="message" value="${newMessage}">
                `;
                document.body.appendChild(form);
                form.submit();
            }
        },

        async submitMessage(event) {
            event.preventDefault();
            const form = event.target;
            const formData = new FormData(form);

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    }
                });

                if (response.ok) {
                    const data = await response.json();

                    // Add new message to DOM instantly
                    if (data.html) {
                        const messagesContainer = document.getElementById('discussions-container');
                        const tempDiv = document.createElement('div');
                        tempDiv.innerHTML = data.html;

                        if (data.is_reply) {
                            // Add to replies section
                            const parentMessage = document.querySelector(`[data-discussion-id="${data.parent_id}"]`);
                            if (parentMessage) {
                                const repliesContainer = parentMessage.querySelector('.replies-container') || this.createRepliesContainer(parentMessage);
                                repliesContainer.appendChild(tempDiv.firstElementChild);
                            }
                        } else {
                            // Add as new main message
                            messagesContainer.insertBefore(tempDiv.firstElementChild, messagesContainer.firstElementChild);
                        }

                        // Update last message ID
                        this.lastMessageId = data.id;

                        // Clear form
                        form.reset();
                        this.replyTo = null;

                        // Show success toast
                        this.showToast('Message posted!', 'success');

                        // Scroll to new message
                        const newMessage = document.querySelector(`[data-discussion-id="${data.id}"]`);
                        if (newMessage) {
                            newMessage.scrollIntoView({ behavior: 'smooth', block: 'center' });
                            newMessage.classList.add('highlight-new');
                            setTimeout(() => newMessage.classList.remove('highlight-new'), 2000);
                        }
                    }
                }
            } catch (error) {
                console.error('Error posting message:', error);
                this.showToast('Error posting message', 'error');
            }
        },

        createRepliesContainer(parentElement) {
            const container = document.createElement('div');
            container.className = 'mt-4 pl-12 space-y-4 border-l-2 border-gray-200 dark:border-gray-600 replies-container';
            parentElement.appendChild(container);
            return container;
        },

        showToast(message, type = 'success') {
            const bgColor = type === 'success' ? 'bg-green-500' : 'bg-red-500';
            const toast = document.createElement('div');
            toast.className = `fixed top-4 right-4 ${bgColor} text-white px-6 py-3 rounded-lg shadow-xl z-50 flex items-center gap-2 animate-fade-in`;
            toast.innerHTML = `
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <span>${message}</span>
            `;
            document.body.appendChild(toast);
            setTimeout(() => toast.remove(), 3000);
        }
    };
}
</script>

<style>
@keyframes highlight {
    0%, 100% { background-color: transparent; }
    50% { background-color: rgba(59, 130, 246, 0.1); }
}

.highlight-new {
    animation: highlight 2s ease-in-out;
}

.animate-fade-in {
    animation: fadeIn 0.3s ease-in-out;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>
@endpush
@endsection
