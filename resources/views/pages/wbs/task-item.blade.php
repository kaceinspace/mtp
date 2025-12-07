@php
    $indentClass = 'ml-' . ($level * 6);
    $hasChildren = $task->children->count() > 0;
    $isCritical = $task->is_critical ?? false;

    // Border color for hierarchy levels
    $levelBorderColor = match($level % 4) {
        0 => 'border-l-primary-500',
        1 => 'border-l-blue-500',
        2 => 'border-l-purple-500',
        3 => 'border-l-indigo-500',
        default => 'border-l-gray-400',
    };

    $bgClass = $isCritical
        ? 'bg-red-50 dark:bg-red-900/10 border-l-4 border-l-red-600'
        : ($level > 0 ? 'bg-white dark:bg-gray-800 border-l-2 ' . $levelBorderColor : 'bg-white dark:bg-gray-800');
@endphp

<div class="task-item" data-task-id="{{ $task->id }}" data-level="{{ $level }}">
    <!-- Task Row -->
    <div class="flex items-stretch {{ $indentClass }} {{ $bgClass }} rounded-lg hover:shadow-md transition-all duration-200 group border border-gray-200 dark:border-gray-700 overflow-hidden mb-2">

        <!-- Left Section: Checkbox + Drag Handle + Expand + WBS Code + Title -->
        <div class="flex items-center flex-grow min-w-0 p-3 gap-3">
            <!-- Bulk Selection Checkbox -->
            <div class="flex-shrink-0">
                <input type="checkbox"
                       class="task-checkbox w-4 h-4 text-primary-600 rounded border-gray-300 dark:border-gray-600 focus:ring-primary-500"
                       data-task-id="{{ $task->id }}"
                       onchange="updateBulkActions()">
            </div>

            <!-- Drag Handle -->
            <div class="drag-handle cursor-move flex-shrink-0 opacity-0 group-hover:opacity-100 transition-opacity">
                <i class="fas fa-grip-vertical text-gray-400 dark:text-gray-500 text-sm"></i>
            </div>

            <!-- Expand/Collapse Button -->
            <div class="w-6 flex-shrink-0">
                @if($task->isParent())
                    <button onclick="toggleTaskLazy({{ $task->id }})"
                            class="w-6 h-6 flex items-center justify-center rounded hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-500 dark:text-gray-400 transition">
                        <i id="icon-{{ $task->id }}" data-expand-icon class="fas fa-chevron-right text-xs"></i>
                    </button>
                @else
                    <div class="w-6 h-6 flex items-center justify-center">
                        <div class="w-1.5 h-1.5 rounded-full bg-gray-300 dark:bg-gray-600"></div>
                    </div>
                @endif
            </div>

            <!-- WBS Code Badge -->
            <div class="flex-shrink-0">
                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-mono font-bold bg-primary-100 text-primary-700 dark:bg-primary-900/30 dark:text-primary-400 border border-primary-200 dark:border-primary-800">
                    {{ $task->wbs_code ?? '-' }}
                </span>
            </div>

            <!-- Task Title & Metadata -->
            <div class="flex-grow min-w-0">
                <div class="flex items-center gap-2 flex-wrap">
                    <h4 class="font-semibold text-gray-900 dark:text-white text-sm">
                        {{ $task->title }}
                    </h4>

                    <!-- Add Subtask Button (Always Visible, Modern Design) -->
                    <button onclick="openAddSubtask({id: {{ $task->id }}, title: '{{ addslashes($task->title) }}'})"
                            class="flex items-center gap-1 px-2 py-1 bg-gradient-to-r from-primary-500 to-primary-600 hover:from-primary-600 hover:to-primary-700 text-white rounded-md text-xs font-medium shadow-sm transition-all duration-200 hover:shadow-md"
                            title="Add Subtask">
                        <i class="fas fa-plus text-xs"></i>
                        <span class="hidden sm:inline">Sub</span>
                    </button>

                    <!-- Badges Row -->
                    <div class="flex items-center gap-1.5">
                        @if($isCritical)
                            <span class="inline-flex items-center px-2 py-0.5 bg-red-600 text-white text-xs font-bold rounded shadow-sm animate-pulse">
                                <i class="fas fa-exclamation-triangle mr-1"></i>CRITICAL
                            </span>
                        @endif

                        @if($task->hasDependencies())
                            <span class="inline-flex items-center px-2 py-0.5 bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400 text-xs font-medium rounded border border-purple-200 dark:border-purple-800" title="Has dependencies">
                                <i class="fas fa-link mr-1"></i>{{ $task->dependencies->count() }}
                            </span>
                        @endif

                        @if($task->hasDependents())
                            <span class="inline-flex items-center px-2 py-0.5 bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400 text-xs font-medium rounded border border-blue-200 dark:border-blue-800" title="Other tasks depend on this">
                                <i class="fas fa-share-nodes mr-1"></i>{{ $task->dependents->count() }}
                            </span>
                        @endif

                        @if($task->description)
                            <button class="text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300 transition" title="{{ $task->description }}">
                                <i class="fas fa-info-circle text-xs"></i>
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Section: Metadata & Actions -->
        <div class="flex items-center gap-2 pr-3 pl-2 bg-gray-50/50 dark:bg-gray-900/30 border-l border-gray-200 dark:border-gray-700">

            <!-- Assigned To -->
            <div class="flex-shrink-0">
                @if($task->assignee)
                    <div class="flex items-center gap-1.5">
                        <div class="w-7 h-7 bg-gradient-to-br from-primary-500 to-primary-600 rounded-full flex items-center justify-center text-white text-xs font-bold shadow-sm">
                            {{ substr($task->assignee->name, 0, 1) }}
                        </div>
                        <span class="text-xs text-gray-700 dark:text-gray-300 font-medium hidden xl:inline max-w-[80px] truncate">
                            {{ $task->assignee->name }}
                        </span>
                    </div>
                @else
                    <div class="w-7 h-7 bg-gray-200 dark:bg-gray-700 rounded-full flex items-center justify-center" title="Unassigned">
                        <i class="fas fa-user text-xs text-gray-400 dark:text-gray-500"></i>
                    </div>
                @endif
            </div>

            <!-- Status Badge -->
            <div class="flex-shrink-0">
                @php
                    $statusConfig = [
                        'todo' => ['bg' => 'bg-gray-100 dark:bg-gray-700', 'text' => 'text-gray-700 dark:text-gray-300', 'icon' => 'circle', 'border' => 'border-gray-300 dark:border-gray-600'],
                        'in-progress' => ['bg' => 'bg-blue-100 dark:bg-blue-900/30', 'text' => 'text-blue-700 dark:text-blue-400', 'icon' => 'spinner', 'border' => 'border-blue-300 dark:border-blue-700'],
                        'review' => ['bg' => 'bg-yellow-100 dark:bg-yellow-900/30', 'text' => 'text-yellow-700 dark:text-yellow-400', 'icon' => 'eye', 'border' => 'border-yellow-300 dark:border-yellow-700'],
                        'completed' => ['bg' => 'bg-green-100 dark:bg-green-900/30', 'text' => 'text-green-700 dark:text-green-400', 'icon' => 'check-circle', 'border' => 'border-green-300 dark:border-green-700'],
                    ];
                    $config = $statusConfig[$task->status] ?? $statusConfig['todo'];
                @endphp
                <span class="inline-flex items-center px-2.5 py-1 text-xs font-semibold rounded border {{ $config['bg'] }} {{ $config['text'] }} {{ $config['border'] }}">
                    <i class="fas fa-{{ $config['icon'] }} mr-1 text-xs"></i>
                    <span class="hidden lg:inline">{{ ucfirst(str_replace('-', ' ', $task->status)) }}</span>
                </span>
            </div>

            <!-- Priority Badge -->
            <div class="flex-shrink-0">
                @php
                    $priorityConfig = [
                        'low' => ['bg' => 'bg-gray-100 dark:bg-gray-700', 'text' => 'text-gray-600 dark:text-gray-400', 'icon' => 'arrow-down', 'border' => 'border-gray-300 dark:border-gray-600'],
                        'medium' => ['bg' => 'bg-blue-100 dark:bg-blue-900/30', 'text' => 'text-blue-600 dark:text-blue-400', 'icon' => 'minus', 'border' => 'border-blue-300 dark:border-blue-700'],
                        'high' => ['bg' => 'bg-orange-100 dark:bg-orange-900/30', 'text' => 'text-orange-600 dark:text-orange-400', 'icon' => 'arrow-up', 'border' => 'border-orange-300 dark:border-orange-700'],
                        'critical' => ['bg' => 'bg-red-100 dark:bg-red-900/30', 'text' => 'text-red-600 dark:text-red-400', 'icon' => 'exclamation', 'border' => 'border-red-300 dark:border-red-700'],
                    ];
                    $pConfig = $priorityConfig[$task->priority] ?? $priorityConfig['medium'];
                @endphp
                <span class="inline-flex items-center px-2.5 py-1 text-xs font-semibold rounded border {{ $pConfig['bg'] }} {{ $pConfig['text'] }} {{ $pConfig['border'] }}">
                    <i class="fas fa-{{ $pConfig['icon'] }} mr-1 text-xs"></i>
                    <span class="hidden lg:inline">{{ ucfirst($task->priority) }}</span>
                </span>
            </div>

            <!-- Weight Badge with Inline Edit -->
            <div class="flex-shrink-0 group/weight relative">
                @php
                    $weightColor = 'gray';
                    $weightBg = 'bg-gray-100 dark:bg-gray-700';
                    $weightText = 'text-gray-700 dark:text-gray-300';
                    $weightBorder = 'border-gray-300 dark:border-gray-600';
                    $weightHover = 'hover:bg-gray-200 dark:hover:bg-gray-600';

                    if ($task->weight > 0) {
                        if ($task->weight_percentage >= 30) {
                            $weightBg = 'bg-purple-100 dark:bg-purple-900/30';
                            $weightText = 'text-purple-700 dark:text-purple-400';
                            $weightBorder = 'border-purple-300 dark:border-purple-700';
                            $weightHover = 'hover:bg-purple-200 dark:hover:bg-purple-900/50';
                        } elseif ($task->weight_percentage >= 20) {
                            $weightBg = 'bg-blue-100 dark:bg-blue-900/30';
                            $weightText = 'text-blue-700 dark:text-blue-400';
                            $weightBorder = 'border-blue-300 dark:border-blue-700';
                            $weightHover = 'hover:bg-blue-200 dark:hover:bg-blue-900/50';
                        } elseif ($task->weight_percentage >= 10) {
                            $weightBg = 'bg-indigo-100 dark:bg-indigo-900/30';
                            $weightText = 'text-indigo-700 dark:text-indigo-400';
                            $weightBorder = 'border-indigo-300 dark:border-indigo-700';
                            $weightHover = 'hover:bg-indigo-200 dark:hover:bg-indigo-900/50';
                        }
                    }
                @endphp
                <div class="flex items-center gap-1">
                    <button onclick="openWeightEditor({{ $task->id }}, {{ $task->weight }}, {{ $task->is_weight_locked ? 'true' : 'false' }})"
                            class="inline-flex items-center px-2.5 py-1 text-xs font-semibold rounded border {{ $weightBg }} {{ $weightText }} {{ $weightBorder }} {{ $weightHover }} transition cursor-pointer"
                            title="Weight: {{ $task->weight }}% ({{ $task->weight_percentage }}% of siblings)">
                        <i class="fas fa-weight-hanging mr-1 text-xs"></i>
                        <span>{{ $task->weight }}%</span>
                        @if($task->is_weight_locked)
                            <i class="fas fa-lock ml-1 text-xs"></i>
                        @endif
                    </button>
                </div>
            </div>

            <!-- Due Date -->
            <div class="flex-shrink-0 hidden xl:block">
                @if($task->due_date)
                    <div class="flex items-center gap-1 text-xs text-gray-600 dark:text-gray-400">
                        <i class="fas fa-calendar text-xs"></i>
                        <span>{{ $task->due_date->format('M d') }}</span>
                    </div>
                @else
                    <div class="flex items-center gap-1 text-xs text-gray-400 dark:text-gray-500">
                        <i class="fas fa-calendar-times text-xs"></i>
                    </div>
                @endif
            </div>

            <!-- Action Buttons -->
            <div class="flex-shrink-0 flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                <button onclick="openDependencies({{ $task->id }})"
                        class="w-7 h-7 flex items-center justify-center rounded hover:bg-purple-100 dark:hover:bg-purple-900/30 text-gray-500 hover:text-purple-600 dark:text-gray-400 dark:hover:text-purple-400 transition"
                        title="Manage Dependencies">
                    <i class="fas fa-project-diagram text-xs"></i>
                </button>
                <a href="{{ route('tasks.edit', $task) }}"
                   class="w-7 h-7 flex items-center justify-center rounded hover:bg-blue-100 dark:hover:bg-blue-900/30 text-gray-500 hover:text-blue-600 dark:text-gray-400 dark:hover:text-blue-400 transition"
                   title="Edit Task">
                    <i class="fas fa-edit text-xs"></i>
                </a>
                <button onclick="deleteTask({{ $task->id }})"
                        class="w-7 h-7 flex items-center justify-center rounded hover:bg-red-100 dark:hover:bg-red-900/30 text-gray-500 hover:text-red-600 dark:text-gray-400 dark:hover:text-red-400 transition"
                        title="Delete Task">
                    <i class="fas fa-trash text-xs"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Children Tasks Container (Lazy Loaded) -->
    @if($task->isParent())
        <div id="children-{{ $task->id }}"
             data-task-collapse
             data-children-container
             data-parent-id="{{ $task->id }}"
             data-loaded="false"
             style="display: none;"
             class="mt-2 space-y-2">
            <!-- Children will be loaded via AJAX -->
            <div class="flex items-center justify-center py-4">
                <i class="fas fa-spinner fa-spin text-primary-600 mr-2"></i>
                <span class="text-sm text-gray-500">Loading subtasks...</span>
            </div>
        </div>
    @endif
</div>
