@php
    $indentClass = 'ml-' . ($level * 6);
    $hasChildren = $task->children->count() > 0;
@endphp

<div class="task-item" data-task-id="{{ $task->id }}" data-level="{{ $level }}">
    <!-- Task Row -->
    <div class="flex items-center p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition group {{ $indentClass }}">
        <!-- Expand/Collapse Button -->
        <div class="w-6 flex-shrink-0">
            @if($hasChildren)
                <button onclick="toggleTask({{ $task->id }})" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200">
                    <i id="icon-{{ $task->id }}" data-expand-icon class="fas fa-chevron-down text-xs"></i>
                </button>
            @endif
        </div>

        <!-- WBS Code -->
        <div class="w-16 flex-shrink-0">
            <span class="text-xs font-mono font-semibold text-primary-600 dark:text-primary-400">
                {{ $task->wbs_code ?? '-' }}
            </span>
        </div>

        <!-- Task Title -->
        <div class="flex-grow min-w-0">
            <a href="{{ route('tasks.show', $task) }}" class="flex items-center gap-2 hover:text-primary-600 dark:hover:text-primary-400">
                <h4 class="font-medium text-gray-900 dark:text-white truncate">
                    {{ $task->title }}
                </h4>
            </a>
        </div>

        <!-- Project Badge -->
        <div class="w-40 flex-shrink-0">
            <a href="{{ route('projects.show', $task->project) }}" class="inline-flex items-center px-2 py-1 text-xs font-medium bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300 rounded hover:bg-blue-200 dark:hover:bg-blue-800">
                <i class="fas fa-folder mr-1"></i>
                {{ Str::limit($task->project->title, 20) }}
            </a>
        </div>

        <!-- Assigned To -->
        <div class="w-32 flex-shrink-0">
            @if($task->assignee)
                <div class="flex items-center gap-2">
                    <div class="w-6 h-6 bg-primary-600 rounded-full flex items-center justify-center text-white text-xs font-semibold">
                        {{ substr($task->assignee->name, 0, 1) }}
                    </div>
                    <span class="text-sm text-gray-700 dark:text-gray-300 truncate">
                        {{ Str::limit($task->assignee->name, 12) }}
                    </span>
                </div>
            @else
                <span class="text-xs text-gray-400 dark:text-gray-500">{{ __('messages.unassigned') }}</span>
            @endif
        </div>

        <!-- Status Badge -->
        <div class="w-24 flex-shrink-0">
            @php
                $statusColors = [
                    'todo' => 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300',
                    'in-progress' => 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300',
                    'review' => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900 dark:text-yellow-300',
                    'completed' => 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300',
                ];
            @endphp
            <span class="px-2 py-1 text-xs font-medium rounded {{ $statusColors[$task->status] ?? $statusColors['todo'] }}">
                {{ ucfirst($task->status) }}
            </span>
        </div>

        <!-- Priority Badge -->
        <div class="w-20 flex-shrink-0">
            @php
                $priorityColors = [
                    'low' => 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400',
                    'medium' => 'bg-blue-100 text-blue-600 dark:bg-blue-900 dark:text-blue-400',
                    'high' => 'bg-orange-100 text-orange-600 dark:bg-orange-900 dark:text-orange-400',
                    'critical' => 'bg-red-100 text-red-600 dark:bg-red-900 dark:text-red-400',
                ];
            @endphp
            <span class="px-2 py-1 text-xs font-medium rounded {{ $priorityColors[$task->priority] ?? $priorityColors['medium'] }}">
                {{ ucfirst($task->priority) }}
            </span>
        </div>

        <!-- Due Date -->
        <div class="w-28 flex-shrink-0 text-sm text-gray-600 dark:text-gray-400">
            @if($task->due_date)
                {{ $task->due_date->format('M d, Y') }}
            @else
                <span class="text-gray-400 dark:text-gray-500">-</span>
            @endif
        </div>

        <!-- Actions (visible on hover) -->
        <div class="flex-shrink-0 opacity-0 group-hover:opacity-100 transition-opacity flex gap-1">
            <a href="{{ route('tasks.show', $task) }}"
               class="p-1.5 text-gray-500 hover:text-blue-600 dark:text-gray-400 dark:hover:text-blue-400"
               :title="__('messages.view_details')">
                <i class="fas fa-eye text-xs"></i>
            </a>
            @if(Gate::allows('admin') || Gate::allows('team_lead'))
            <a href="{{ route('tasks.edit', $task) }}"
               class="p-1.5 text-gray-500 hover:text-yellow-600 dark:text-gray-400 dark:hover:text-yellow-400"
               :title="__('messages.edit_task')">
                <i class="fas fa-edit text-xs"></i>
            </a>
            @endif
        </div>
    </div>

    <!-- Children Tasks (Recursive) -->
    @if($hasChildren)
        <div id="children-{{ $task->id }}" data-task-collapse class="mt-2 space-y-2">
            @foreach($task->children as $child)
                @include('pages.tasks.wbs-item', ['task' => $child, 'level' => $level + 1])
            @endforeach
        </div>
    @endif
</div>
