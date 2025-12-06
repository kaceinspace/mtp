@props(['activities', 'title' => 'Recent Activities'])

<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
        <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ $title }}</h3>
    </div>

    <div class="divide-y divide-gray-100 dark:divide-gray-700">
        @forelse($activities as $activity)
            <div class="p-4 hover:bg-gray-50 dark:hover:bg-gray-750 transition">
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 rounded-lg {{ $activity->color_class }} flex items-center justify-center text-lg">
                            {{ $activity->icon }}
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <p class="text-sm text-gray-900 dark:text-white font-medium">
                                    {{ $activity->user->name }}
                                </p>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                    {{ $activity->description }}
                                </p>
                                @if($activity->project)
                                    <a href="{{ route('projects.show', $activity->project) }}"
                                       class="text-xs text-primary-600 dark:text-primary-400 hover:underline mt-1 inline-block">
                                        {{ $activity->project->title }}
                                    </a>
                                @endif
                            </div>
                            <div class="flex-shrink-0 ml-2">
                                <span class="text-xs text-gray-500 dark:text-gray-400" title="{{ $activity->created_at->format('M d, Y H:i') }}">
                                    {{ $activity->created_at->diffForHumans() }}
                                </span>
                            </div>
                        </div>

                        @if($activity->metadata)
                            <div class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                                @if(isset($activity->metadata['old_status']) && isset($activity->metadata['new_status']))
                                    <span class="inline-flex items-center space-x-2">
                                        <span class="px-2 py-1 bg-gray-100 dark:bg-gray-700 rounded">{{ ucfirst($activity->metadata['old_status']) }}</span>
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                        </svg>
                                        <span class="px-2 py-1 bg-gray-100 dark:bg-gray-700 rounded">{{ ucfirst($activity->metadata['new_status']) }}</span>
                                    </span>
                                @endif

                                @if(isset($activity->metadata['file_type']))
                                    <span class="inline-flex items-center px-2 py-1 bg-gray-100 dark:bg-gray-700 rounded">
                                        {{ strtoupper($activity->metadata['file_type']) }}
                                        @if(isset($activity->metadata['file_size']))
                                            • {{ number_format($activity->metadata['file_size'] / 1024, 2) }} KB
                                        @endif
                                    </span>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="p-12 text-center">
                <svg class="w-16 h-16 text-gray-400 dark:text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <p class="text-gray-500 dark:text-gray-400 font-medium">No recent activities</p>
                <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">Activities will appear here as they happen</p>
            </div>
        @endforelse
    </div>

    @if($activities->count() > 0)
        <div class="p-4 bg-gray-50 dark:bg-gray-750 border-t border-gray-200 dark:border-gray-700">
            <p class="text-xs text-gray-500 dark:text-gray-400 text-center">
                Showing {{ $activities->count() }} recent {{ Str::plural('activity', $activities->count()) }}
            </p>
        </div>
    @endif
</div>
