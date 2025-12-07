@extends('layouts.dashboard')

@section('title', 'WBS - ' . $project->title)
@section('page-title', 'Work Breakdown Structure')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                Work Breakdown Structure - {{ $project->title }}
            </h2>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Hierarchical task breakdown and planning</p>
        </div>
        <div class="flex gap-2">
            <button type="button"
                    onclick="openSaveTemplateModal()"
                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition z-10 relative cursor-pointer">
                <i class="fas fa-save mr-2"></i>Save as Template
            </button>
            <button type="button"
                    onclick="openLoadTemplateModal()"
                    class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition z-10 relative cursor-pointer">
                <i class="fas fa-download mr-2"></i>Load Template
            </button>
            <a href="{{ route('projects.wbs.gantt', $project) }}"
               class="px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white rounded-lg transition z-10 relative">
                <i class="fas fa-chart-gantt mr-2"></i>Gantt View
            </a>
            <button type="button"
                    onclick="openWeightManagerModal()"
                    class="px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white rounded-lg transition z-10 relative cursor-pointer">
                <i class="fas fa-weight-hanging mr-2"></i>Weight Manager
            </button>
            <button type="button"
                    onclick="openCalendarSettingsModal()"
                    class="px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white rounded-lg transition z-10 relative cursor-pointer">
                <i class="fas fa-calendar-alt mr-2"></i>Calendar Settings
            </button>
            <a href="{{ route('projects.wbs.critical-path', $project) }}"
               class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg transition z-10 relative">
                <i class="fas fa-route mr-2"></i>Critical Path
            </a>
            <a href="{{ route('projects.progress.index', $project) }}"
               class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition z-10 relative">
                <i class="fas fa-chart-bar mr-2"></i>Progress Tracking
            </a>
            <a href="{{ route('projects.show', $project) }}"
               class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition z-10 relative">
                <i class="fas fa-arrow-left mr-2"></i>Back to Project
            </a>
            @if(Gate::allows('admin') || Gate::allows('team_lead'))
            <button type="button"
                    onclick="openAddRootTaskModal()"
                    class="px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-lg transition z-10 relative cursor-pointer">
                <i class="fas fa-plus mr-2"></i>Add Root Task
            </button>
            @endif
        </div>
    </div>

    <!-- WBS Tree View -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
        <div class="p-6">
            <!-- Header -->
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Task Hierarchy</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                        Drag and drop tasks to reorganize • Click to view details
                    </p>
                </div>
                <div class="flex gap-2">
                    <button onclick="expandAll()" class="text-sm px-3 py-1.5 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded hover:bg-gray-300 dark:hover:bg-gray-600">
                        <i class="fas fa-expand-alt mr-1"></i>Expand All
                    </button>
                    <button onclick="collapseAll()" class="text-sm px-3 py-1.5 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded hover:bg-gray-300 dark:hover:bg-gray-600">
                        <i class="fas fa-compress-alt mr-1"></i>Collapse All
                    </button>
                </div>
            </div>

            <!-- Bulk Actions Toolbar (Hidden by default) -->
            <div id="bulkActionsToolbar" style="display: none;" class="mb-4 p-4 bg-primary-50 dark:bg-primary-900/20 rounded-lg border border-primary-200 dark:border-primary-800">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                            <span id="selectedCount">0</span> task(s) selected
                        </span>
                        <label class="flex items-center gap-2">
                            <input type="checkbox" id="selectAll" onchange="toggleSelectAll(this)" class="w-4 h-4 text-primary-600 rounded">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Select All</span>
                        </label>
                    </div>
                    <div class="flex gap-2">
                        <button onclick="bulkChangeStatus()" class="text-sm px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded transition">
                            <i class="fas fa-tasks mr-1"></i>Change Status
                        </button>
                        <button onclick="bulkAssign()" class="text-sm px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white rounded transition">
                            <i class="fas fa-user-plus mr-1"></i>Assign To
                        </button>
                        <button onclick="bulkDelete()" class="text-sm px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white rounded transition">
                            <i class="fas fa-trash mr-1"></i>Delete
                        </button>
                        <button onclick="clearSelection()" class="text-sm px-3 py-1.5 bg-gray-600 hover:bg-gray-700 text-white rounded transition">
                            <i class="fas fa-times mr-1"></i>Clear
                        </button>
                    </div>
                </div>
            </div>

            <!-- Task Tree -->
            <div id="taskTree" class="space-y-2">
                @forelse($tasks as $task)
                    @include('pages.wbs.task-item', ['task' => $task, 'level' => 0])
                @empty
                    <div class="text-center py-12">
                        <i class="fas fa-sitemap text-5xl text-gray-400 dark:text-gray-600 mb-4"></i>
                        <p class="text-gray-600 dark:text-gray-400">No tasks yet</p>
                        <p class="text-sm text-gray-500 dark:text-gray-500 mt-2">Create your first task to start building the WBS</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Add/Edit Task Modal -->
    <div id="addTaskModal" style="display: none;"
         class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
         onclick="if(event.target === this) closeAddTaskModal()">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-2xl mx-4 max-h-[90vh] overflow-y-auto"
             onclick="event.stopPropagation()">
            <!-- Modal Header -->
            <div class="flex justify-between items-center p-6 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                    <span id="modalTitle">Add New Task</span>
                </h3>
                <button onclick="closeAddTaskModal()" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <!-- Modal Body -->
            <form id="taskForm" onsubmit="submitTask(event)" class="p-6 space-y-4">
                <!-- Task Title -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Task Title <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           id="taskTitle"
                           name="title"
                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                           placeholder="Enter task title"
                           required>
                    </div>

                <!-- Description -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Description
                    </label>
                    <textarea id="taskDescription"
                              name="description"
                              rows="3"
                              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                              placeholder="Task description (optional)"></textarea>
                </div>

                <!-- Parent Task (if adding subtask) -->
                <input type="hidden" id="taskParentId" name="parent_id">
                <div id="parentTaskInfo" style="display: none;">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Parent Task
                    </label>
                    <input type="text"
                           id="parentTaskTitle"
                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white"
                           disabled>
                </div>

                <!-- Assigned To & Priority -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Assign To
                        </label>
                        <select id="taskAssignedTo"
                                name="assigned_to"
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                            <option value="">-- Unassigned --</option>
                            @foreach($members as $member)
                                <option value="{{ $member->id }}">{{ $member->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Priority <span class="text-red-500">*</span>
                        </label>
                        <select id="taskPriority"
                                name="priority"
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                                required>
                            <option value="low">Low</option>
                            <option value="medium" selected>Medium</option>
                            <option value="high">High</option>
                            <option value="critical">Critical</option>
                        </select>
                    </div>
                </div>

                <!-- Due Date & Estimated Duration -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Due Date
                        </label>
                        <input type="date"
                               id="taskDueDate"
                               name="due_date"
                               class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Estimated Duration (days)
                        </label>
                        <input type="number"
                               id="taskEstimatedDuration"
                               name="estimated_duration"
                               min="0"
                               class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                               placeholder="0">
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <button type="button"
                            onclick="closeAddTaskModal()"
                            class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-lg">
                        <span id="submitButtonText">Create Task</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Save Template Modal -->
    <div id="saveTemplateModal" style="display: none;"
         class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
         onclick="if(event.target === this) closeSaveTemplateModal()">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white">Save as Template</h3>
                <button onclick="closeSaveTemplateModal()" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="saveTemplateForm" onsubmit="saveTemplate(event)">
                <div class="mb-4">
                    <label class="block text-gray-700 dark:text-gray-300 mb-2">Template Name</label>
                    <input type="text" name="name" required class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 dark:text-gray-300 mb-2">Description (Optional)</label>
                    <textarea name="description" rows="3" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"></textarea>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeSaveTemplateModal()" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">Save Template</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Load Template Modal -->
    <div id="loadTemplateModal" style="display: none;"
         class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
         onclick="if(event.target === this) closeLoadTemplateModal()">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-2xl w-full p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white">Load Template</h3>
                <button onclick="closeLoadTemplateModal()" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="mb-4 text-sm text-red-600 dark:text-red-400">
                <i class="fas fa-exclamation-triangle mr-2"></i>Warning: Loading a template will replace all existing tasks in this project.
            </div>
            <div id="templatesList" class="space-y-2 mb-4 max-h-96 overflow-y-auto">
                <!-- Templates will be loaded here -->
            </div>
            <div class="flex justify-end">
                <button onclick="closeLoadTemplateModal()" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg">Close</button>
            </div>
        </div>
    </div>

    <!-- Weight Manager Modal -->
    <div id="weightManagerModal" style="display: none;"
         class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
         onclick="if(event.target === this) closeWeightManagerModal()">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-6xl w-full max-h-[90vh] overflow-hidden">
            <div class="flex justify-between items-center p-6 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white">
                    <i class="fas fa-weight-hanging mr-2"></i>Weight Distribution Manager
                </h3>
                <button onclick="closeWeightManagerModal()" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <!-- Tabs -->
            <div class="border-b border-gray-200 dark:border-gray-700">
                <nav class="flex gap-4 px-6" aria-label="Tabs">
                    <button onclick="switchWeightTab('summary')" id="tab-summary"
                            class="weight-tab py-3 px-4 text-sm font-medium border-b-2 border-amber-600 text-amber-600">
                        <i class="fas fa-list mr-2"></i>Summary
                    </button>
                    <button onclick="switchWeightTab('timeline')" id="tab-timeline"
                            class="weight-tab py-3 px-4 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
                        <i class="fas fa-chart-line mr-2"></i>Timeline
                    </button>
                    <button onclick="switchWeightTab('status')" id="tab-status"
                            class="weight-tab py-3 px-4 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
                        <i class="fas fa-chart-pie mr-2"></i>By Status
                    </button>
                </nav>
            </div>

            <!-- Tab Content -->
            <div class="p-6 overflow-y-auto max-h-[calc(90vh-200px)]">
                <div id="weightSummaryContent" class="weight-tab-content">
                    <div class="text-center py-8 text-gray-500">Loading...</div>
                </div>
                <div id="weightTimelineContent" class="weight-tab-content hidden">
                    <div class="text-center py-8 text-gray-500">Loading...</div>
                </div>
                <div id="weightStatusContent" class="weight-tab-content hidden">
                    <div class="text-center py-8 text-gray-500">Loading...</div>
                </div>
            </div>

            <div class="flex justify-end gap-2 p-6 border-t border-gray-200 dark:border-gray-700">
                <button onclick="closeWeightManagerModal()" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg">Close</button>
            </div>
        </div>
    </div>

    <!-- Weight Editor Modal (Quick Edit) -->
    <div id="weightEditorModal" style="display: none;"
         class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
         onclick="if(event.target === this) closeWeightEditorModal()">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white">Edit Weight</h3>
                <button onclick="closeWeightEditorModal()" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="weightEditorForm" onsubmit="saveWeight(event)">
                <input type="hidden" id="weightTaskId" name="task_id">
                <div class="mb-4">
                    <label class="block text-gray-700 dark:text-gray-300 mb-2">
                        Weight (%)
                    </label>
                    <input type="number" id="weightInput" name="weight" min="0" max="100" step="0.01" required
                           class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-amber-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                           oninput="previewValidation()">
                    <!-- Validation Preview -->
                    <div id="validationPreview" class="mt-2 text-sm hidden"></div>
                </div>
                <div class="mb-4">
                    <label class="inline-flex items-center cursor-pointer">
                        <input type="checkbox" id="weightLockCheckbox" class="form-checkbox h-5 w-5 text-amber-600">
                        <span class="ml-2 text-gray-700 dark:text-gray-300">
                            <i class="fas fa-lock mr-1"></i>Lock weight (prevent auto-distribution)
                        </span>
                    </label>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeWeightEditorModal()" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white rounded-lg">Save Weight</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Calendar Settings Modal -->
    <div id="calendarSettingsModal" style="display: none;"
         class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
         onclick="if(event.target === this) closeCalendarSettingsModal()">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-4xl mx-4 max-h-[90vh] overflow-hidden"
             onclick="event.stopPropagation()">
            <!-- Modal Header -->
            <div class="flex justify-between items-center p-6 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white">
                    <i class="fas fa-calendar-alt mr-2"></i>Calendar & Working Days Settings
                </h3>
                <button onclick="closeCalendarSettingsModal()" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <!-- Tabs -->
            <div class="border-b border-gray-200 dark:border-gray-700">
                <nav class="flex gap-4 px-6" aria-label="Tabs">
                    <button onclick="switchCalendarTab('working-days')" id="tab-working-days"
                            class="calendar-tab py-3 px-4 text-sm font-medium border-b-2 border-teal-600 text-teal-600">
                        <i class="fas fa-business-time mr-2"></i>Working Days
                    </button>
                    <button onclick="switchCalendarTab('holidays')" id="tab-holidays"
                            class="calendar-tab py-3 px-4 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
                        <i class="fas fa-umbrella-beach mr-2"></i>Holidays
                    </button>
                    <button onclick="switchCalendarTab('planning')" id="tab-planning"
                            class="calendar-tab py-3 px-4 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
                        <i class="fas fa-chart-line mr-2"></i>Weekly Planning
                    </button>
                </nav>
            </div>

            <!-- Tab Content -->
            <div class="p-6 overflow-y-auto max-h-[calc(90vh-200px)]">
                <!-- Working Days Tab -->
                <div id="workingDaysContent" class="calendar-tab-content">
                    <div class="space-y-6">
                        <div>
                            <h4 class="font-semibold text-gray-900 dark:text-white mb-4">Select Working Days</h4>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                <label class="flex items-center p-3 border border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <input type="checkbox" id="monday" class="working-day-checkbox form-checkbox h-5 w-5 text-teal-600">
                                    <span class="ml-2 text-gray-700 dark:text-gray-300">Monday</span>
                                </label>
                                <label class="flex items-center p-3 border border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <input type="checkbox" id="tuesday" class="working-day-checkbox form-checkbox h-5 w-5 text-teal-600">
                                    <span class="ml-2 text-gray-700 dark:text-gray-300">Tuesday</span>
                                </label>
                                <label class="flex items-center p-3 border border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <input type="checkbox" id="wednesday" class="working-day-checkbox form-checkbox h-5 w-5 text-teal-600">
                                    <span class="ml-2 text-gray-700 dark:text-gray-300">Wednesday</span>
                                </label>
                                <label class="flex items-center p-3 border border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <input type="checkbox" id="thursday" class="working-day-checkbox form-checkbox h-5 w-5 text-teal-600">
                                    <span class="ml-2 text-gray-700 dark:text-gray-300">Thursday</span>
                                </label>
                                <label class="flex items-center p-3 border border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <input type="checkbox" id="friday" class="working-day-checkbox form-checkbox h-5 w-5 text-teal-600">
                                    <span class="ml-2 text-gray-700 dark:text-gray-300">Friday</span>
                                </label>
                                <label class="flex items-center p-3 border border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <input type="checkbox" id="saturday" class="working-day-checkbox form-checkbox h-5 w-5 text-teal-600">
                                    <span class="ml-2 text-gray-700 dark:text-gray-300">Saturday</span>
                                </label>
                                <label class="flex items-center p-3 border border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <input type="checkbox" id="sunday" class="working-day-checkbox form-checkbox h-5 w-5 text-teal-600">
                                    <span class="ml-2 text-gray-700 dark:text-gray-300">Sunday</span>
                                </label>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-gray-700 dark:text-gray-300 mb-2">Work Start Time</label>
                                <input type="time" id="work_start_time" class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            </div>
                            <div>
                                <label class="block text-gray-700 dark:text-gray-300 mb-2">Work End Time</label>
                                <input type="time" id="work_end_time" class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            </div>
                            <div>
                                <label class="block text-gray-700 dark:text-gray-300 mb-2">Hours Per Day</label>
                                <input type="number" id="hours_per_day" step="0.5" min="1" max="24" class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <button onclick="saveWorkingDays()" class="px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white rounded-lg">
                                <i class="fas fa-save mr-2"></i>Save Working Days
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Holidays Tab -->
                <div id="holidaysContent" class="calendar-tab-content hidden">
                    <div class="space-y-4">
                        <!-- Add Holiday Form -->
                        <div class="bg-gray-50 dark:bg-gray-900/50 p-4 rounded-lg">
                            <h4 class="font-semibold text-gray-900 dark:text-white mb-3">Add New Holiday</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-gray-700 dark:text-gray-300 mb-2">Date</label>
                                    <input type="date" id="holiday_date" class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                </div>
                                <div>
                                    <label class="block text-gray-700 dark:text-gray-300 mb-2">Holiday Name</label>
                                    <input type="text" id="holiday_name" placeholder="e.g., New Year's Day" class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                </div>
                                <div>
                                    <label class="block text-gray-700 dark:text-gray-300 mb-2">Type</label>
                                    <select id="holiday_type" class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                        <option value="holiday">Public Holiday</option>
                                        <option value="non-working-day">Non-Working Day</option>
                                        <option value="custom">Custom</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="flex items-center mt-8">
                                        <input type="checkbox" id="is_recurring" class="form-checkbox h-5 w-5 text-teal-600">
                                        <span class="ml-2 text-gray-700 dark:text-gray-300">Recurring Annually</span>
                                    </label>
                                </div>
                            </div>
                            <div class="mt-4">
                                <label class="block text-gray-700 dark:text-gray-300 mb-2">Description (Optional)</label>
                                <textarea id="holiday_description" rows="2" class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"></textarea>
                            </div>
                            <div class="mt-4 flex justify-end">
                                <button onclick="addHoliday()" class="px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white rounded-lg">
                                    <i class="fas fa-plus mr-2"></i>Add Holiday
                                </button>
                            </div>
                        </div>

                        <!-- Holidays List -->
                        <div id="holidaysList" class="space-y-2">
                            <div class="text-center py-8 text-gray-500">Loading holidays...</div>
                        </div>
                    </div>
                </div>

                <!-- Weekly Planning Tab -->
                <div id="planningContent" class="calendar-tab-content hidden">
                    <div class="text-center py-8 text-gray-500">Loading planning view...</div>
                </div>
            </div>

            <div class="flex justify-end gap-2 p-6 border-t border-gray-200 dark:border-gray-700">
                <button onclick="closeCalendarSettingsModal()" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg">Close</button>
            </div>
        </div>
    </div>

    <!-- Dependencies Modal -->
    <div id="dependenciesModal" style="display: none;"
         class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
         onclick="if(event.target === this) closeDependenciesModal()">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-3xl mx-4 max-h-[90vh] overflow-y-auto"
             onclick="event.stopPropagation()">
            <!-- Modal Header -->
            <div class="flex justify-between items-center p-6 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                    Manage Task Dependencies
                </h3>
                <button onclick="closeDependenciesModal()" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="p-6 space-y-6">
                <!-- Current Task Info -->
                <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-lg">
                    <h4 class="font-medium text-gray-900 dark:text-white mb-2">Current Task</h4>
                    <p class="text-sm text-gray-700 dark:text-gray-300" id="dependencyCurrentTaskTitle"></p>
                </div>

                <!-- Add New Dependency -->
                <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                    <h4 class="font-medium text-gray-900 dark:text-white mb-4">Add Dependency</h4>
                    <form id="addDependencyForm" onsubmit="return submitDependency(event)" class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    This task depends on <span class="text-red-500">*</span>
                                </label>
                                <select id="dependsOnTaskId" name="depends_on_task_id"
                                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                                        required>
                                    <option value="">-- Select Task --</option>
                                    <!-- Options will be populated dynamically -->
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Dependency Type <span class="text-red-500">*</span>
                                </label>
                                <select id="dependencyType" name="dependency_type"
                                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                                        required>
                                    <option value="finish-to-start">Finish to Start (FS)</option>
                                    <option value="start-to-start">Start to Start (SS)</option>
                                    <option value="finish-to-finish">Finish to Finish (FF)</option>
                                    <option value="start-to-finish">Start to Finish (SF)</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Lag Time (days)
                                <span class="text-xs text-gray-500">Positive = delay, Negative = lead time</span>
                            </label>
                            <input type="number" id="lagDays" name="lag_days" value="0"
                                   class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                                   placeholder="0">
                        </div>

                        <div class="flex justify-end">
                            <button type="submit"
                                    class="px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-lg">
                                <i class="fas fa-plus mr-2"></i>Add Dependency
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Existing Dependencies -->
                <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                    <h4 class="font-medium text-gray-900 dark:text-white mb-4">Current Dependencies</h4>

                    <!-- Tasks this depends on -->
                    <div class="mb-6">
                        <h5 class="text-sm font-medium text-gray-700 dark:text-gray-400 mb-3">
                            <i class="fas fa-arrow-left mr-2"></i>This task depends on:
                        </h5>
                        <div id="dependenciesList" class="space-y-2">
                            <!-- Will be populated dynamically -->
                        </div>
                    </div>

                    <!-- Tasks that depend on this -->
                    <div>
                        <h5 class="text-sm font-medium text-gray-700 dark:text-gray-400 mb-3">
                            <i class="fas fa-arrow-right mr-2"></i>Tasks that depend on this:
                        </h5>
                        <div id="dependentsList" class="space-y-2">
                            <!-- Will be populated dynamically -->
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="flex justify-end gap-3 p-6 border-t border-gray-200 dark:border-gray-700">
                <button type="button"
                        onclick="closeDependenciesModal()"
                        class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600">
                    Close
                </button>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Simple vanilla JS functions for modal management
        function openAddRootTaskModal() {
            resetTaskForm();
            document.getElementById('modalTitle').textContent = 'Add New Task';
            document.getElementById('submitButtonText').textContent = 'Create Task';
            document.getElementById('taskParentId').value = '';
            document.getElementById('parentTaskInfo').style.display = 'none';
            document.getElementById('addTaskModal').style.display = 'flex';
        }

        function closeAddTaskModal() {
            document.getElementById('addTaskModal').style.display = 'none';
            resetTaskForm();
        }

        function resetTaskForm() {
            document.getElementById('taskForm').reset();
            document.getElementById('taskPriority').value = 'medium';
        }

        function openAddSubtask(parentTask) {
            resetTaskForm();
            document.getElementById('modalTitle').textContent = 'Add Subtask';
            document.getElementById('submitButtonText').textContent = 'Create Task';
            document.getElementById('taskParentId').value = parentTask.id;
            document.getElementById('parentTaskTitle').value = parentTask.title;
            document.getElementById('parentTaskInfo').style.display = 'block';
            document.getElementById('addTaskModal').style.display = 'flex';
        }

        async function submitTask(event) {
            event.preventDefault();

            const formData = {
                title: document.getElementById('taskTitle').value,
                description: document.getElementById('taskDescription').value,
                parent_id: document.getElementById('taskParentId').value || null,
                assigned_to: document.getElementById('taskAssignedTo').value || null,
                priority: document.getElementById('taskPriority').value,
                due_date: document.getElementById('taskDueDate').value || null,
                estimated_duration: document.getElementById('taskEstimatedDuration').value || null,
            };

            try {
                const url = '{{ route("projects.wbs.store", $project) }}';
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(formData)
                });

                const data = await response.json();

                if (data.success) {
                    window.location.reload();
                } else {
                    alert(data.message || 'Failed to create task');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Failed to create task');
            }
        }

        async function deleteTask(taskId) {
            if (!confirm('Are you sure you want to delete this task? All subtasks will also be deleted.')) {
                return;
            }

            try {
                const url = `/projects/{{ $project->id }}/wbs/${taskId}`;
                const response = await fetch(url, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                const data = await response.json();

                if (data.success) {
                    window.location.reload();
                } else {
                    alert(data.message || 'Failed to delete task');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Failed to delete task');
            }
        }

        function expandAll() {
            document.querySelectorAll('[data-task-collapse]').forEach(el => {
                el.style.display = 'block';
            });
            document.querySelectorAll('[data-expand-icon]').forEach(el => {
                el.classList.remove('fa-chevron-right');
                el.classList.add('fa-chevron-down');
            });
        }

        function collapseAll() {
            document.querySelectorAll('[data-task-collapse]').forEach(el => {
                el.style.display = 'none';
            });
            document.querySelectorAll('[data-expand-icon]').forEach(el => {
                el.classList.remove('fa-chevron-down');
                el.classList.add('fa-chevron-right');
            });
        }

        // Toggle task children visibility
        function toggleTask(taskId) {
            const children = document.getElementById(`children-${taskId}`);
            const icon = document.getElementById(`icon-${taskId}`);

            if (children.style.display === 'none') {
                children.style.display = 'block';
                icon.classList.remove('fa-chevron-right');
                icon.classList.add('fa-chevron-down');
            } else {
                children.style.display = 'none';
                icon.classList.remove('fa-chevron-down');
                icon.classList.add('fa-chevron-right');
            }
        }

        // Dependencies Modal Management (using Alpine.js from parent)
        let dependenciesModalData = {
            showDependenciesModal: false,
            currentTaskId: null,
            currentTaskTitle: '',
            currentDependencies: [],
            currentDependents: [],
            availableTasks: [],
            dependencyForm: {
                depends_on_task_id: '',
                dependency_type: 'finish-to-start',
                lag_days: 0
            }
        };

        async function openDependencies(taskId) {
            dependenciesModalData.currentTaskId = taskId;

            try {
                const url = `/projects/{{ $project->id }}/wbs/${taskId}/dependencies`;
                const response = await fetch(url, {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                const data = await response.json();

                if (data.success) {
                    dependenciesModalData.currentTaskTitle = data.task.title;
                    dependenciesModalData.currentDependencies = data.dependencies || [];
                    dependenciesModalData.currentDependents = data.dependents || [];
                    dependenciesModalData.availableTasks = data.available_tasks || [];

                    // Update UI
                    document.getElementById('dependencyCurrentTaskTitle').textContent = data.task.title;

                    // Populate available tasks dropdown
                    const select = document.getElementById('dependsOnTaskId');
                    select.innerHTML = '<option value="">-- Select Task --</option>';
                    data.available_tasks.forEach(task => {
                        const option = document.createElement('option');
                        option.value = task.id;
                        option.textContent = `${task.wbs_code} - ${task.title}`;
                        select.appendChild(option);
                    });

                    // Render dependencies list
                    renderDependenciesList(data.dependencies || []);

                    // Render dependents list
                    renderDependentsList(data.dependents || []);

                    // Show modal
                    document.getElementById('dependenciesModal').style.display = 'flex';
                } else {
                    alert(data.message || 'Failed to load dependencies');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Failed to load dependencies');
            }
        }

        function renderDependenciesList(dependencies) {
            const container = document.getElementById('dependenciesList');

            if (dependencies.length === 0) {
                container.innerHTML = '<div class="text-sm text-gray-500 dark:text-gray-400 text-center py-4">No dependencies</div>';
                return;
            }

            container.innerHTML = dependencies.map(dep => `
                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                    <div class="flex-grow">
                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                            ${dep.depends_on_task.wbs_code} - ${dep.depends_on_task.title}
                        </p>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">
                            ${getDependencyTypeLabel(dep.dependency_type)}
                            ${dep.lag_days != 0 ? ` • ${dep.lag_days > 0 ? '+' + dep.lag_days + ' days lag' : Math.abs(dep.lag_days) + ' days lead'}` : ''}
                        </p>
                    </div>
                    <button onclick="removeDependency(${dep.id})"
                            class="ml-4 p-2 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `).join('');
        }

        function renderDependentsList(dependents) {
            const container = document.getElementById('dependentsList');

            if (dependents.length === 0) {
                container.innerHTML = '<div class="text-sm text-gray-500 dark:text-gray-400 text-center py-4">No dependent tasks</div>';
                return;
            }

            container.innerHTML = dependents.map(dep => `
                <div class="flex items-center p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                    <div class="flex-grow">
                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                            ${dep.task.wbs_code} - ${dep.task.title}
                        </p>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">
                            ${getDependencyTypeLabel(dep.dependency_type)}
                            ${dep.lag_days != 0 ? ` • ${dep.lag_days > 0 ? '+' + dep.lag_days + ' days lag' : Math.abs(dep.lag_days) + ' days lead'}` : ''}
                        </p>
                    </div>
                </div>
            `).join('');
        }

        function getDependencyTypeLabel(type) {
            const labels = {
                'finish-to-start': 'Finish to Start (FS)',
                'start-to-start': 'Start to Start (SS)',
                'finish-to-finish': 'Finish to Finish (FF)',
                'start-to-finish': 'Start to Finish (SF)'
            };
            return labels[type] || type;
        }

        async function submitDependency(event) {
            event.preventDefault();

            const formData = {
                depends_on_task_id: document.getElementById('dependsOnTaskId').value,
                dependency_type: document.getElementById('dependencyType').value,
                lag_days: parseInt(document.getElementById('lagDays').value) || 0
            };

            try {
                const response = await fetch(`/projects/{{ $project->id }}/wbs/${dependenciesModalData.currentTaskId}/dependencies`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(formData)
                });

                const data = await response.json();

                if (data.success) {
                    // Reset form
                    document.getElementById('addDependencyForm').reset();

                    // Reload dependencies
                    await openDependencies(dependenciesModalData.currentTaskId);

                    alert('Dependency added successfully!');
                } else {
                    alert(data.message || 'Failed to add dependency');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Failed to add dependency');
            }

            return false;
        }

        async function removeDependency(dependencyId) {
            if (!confirm('Are you sure you want to remove this dependency?')) {
                return;
            }

            try {
                const response = await fetch(`/projects/{{ $project->id }}/wbs/dependencies/${dependencyId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                const data = await response.json();

                if (data.success) {
                    // Reload dependencies
                    await openDependencies(dependenciesModalData.currentTaskId);
                    alert('Dependency removed successfully!');
                } else {
                    alert(data.message || 'Failed to remove dependency');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Failed to remove dependency');
            }
        }

        function closeDependenciesModal() {
            document.getElementById('dependenciesModal').style.display = 'none';
        }

        // OLD Alpine.js code - keeping for dependencies modal (will convert later if needed)
        function wbsManager() {
            return {
                showAddTaskModal: false,
                showDependenciesModal: false,
                editingTask: null,
                selectedParent: null,
                currentTaskId: null,
                currentTaskTitle: '',
                currentDependencies: [],
                currentDependents: [],
                availableTasks: [],
                taskForm: {
                    title: '',
                    description: '',
                    parent_id: null,
                    assigned_to: '',
                    priority: 'medium',
                    due_date: '',
                    estimated_duration: null
                },
                dependencyForm: {
                    depends_on_task_id: '',
                    dependency_type: 'finish-to-start',
                    lag_days: 0
                },

                async openDependencies(taskId) {
                    this.currentTaskId = taskId;

                    try {
                        const url = `/projects/{{ $project->id }}/wbs/${taskId}/dependencies`;
                        const response = await fetch(url, {
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            }
                        });

                        const data = await response.json();

                        if (data.success) {
                            this.currentTaskTitle = data.task.title;
                            this.currentDependencies = data.dependencies || [];
                            this.currentDependents = data.dependents || [];
                            this.availableTasks = data.available_tasks || [];
                            this.showDependenciesModal = true;
                            this.resetDependencyForm();
                        } else {
                            alert(data.message || 'Failed to load dependencies');
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        alert('Failed to load dependencies');
                    }
                },

                async addDependency() {
                    if (!this.dependencyForm.depends_on_task_id) {
                        alert('Please select a task');
                        return;
                    }

                    try {
                        const url = '/projects/{{ $project->id }}/wbs/dependencies';
                        const response = await fetch(url, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({
                                task_id: this.currentTaskId,
                                ...this.dependencyForm
                            })
                        });

                        const data = await response.json();

                        if (data.success) {
                            // Reload dependencies
                            await this.openDependencies(this.currentTaskId);
                        } else {
                            alert(data.message || 'Failed to add dependency');
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        alert('Failed to add dependency');
                    }
                },

                async removeDependency(dependencyId) {
                    if (!confirm('Are you sure you want to remove this dependency?')) {
                        return;
                    }

                    try {
                        const url = `/projects/{{ $project->id }}/wbs/dependencies/${dependencyId}`;
                        const response = await fetch(url, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            }
                        });

                        const data = await response.json();

                        if (data.success) {
                            // Reload dependencies
                            await this.openDependencies(this.currentTaskId);
                        } else {
                            alert(data.message || 'Failed to remove dependency');
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        alert('Failed to remove dependency');
                    }
                },

                getDependencyTypeLabel(type) {
                    const labels = {
                        'finish-to-start': 'Finish to Start (FS)',
                        'start-to-start': 'Start to Start (SS)',
                        'finish-to-finish': 'Finish to Finish (FF)',
                        'start-to-finish': 'Start to Finish (SF)'
                    };
                    return labels[type] || type;
                },

                resetDependencyForm() {
                    this.dependencyForm = {
                        depends_on_task_id: '',
                        dependency_type: 'finish-to-start',
                        lag_days: 0
                    };
                },

                openAddSubtask(parentTask) {
                    this.selectedParent = parentTask;
                    this.taskForm.parent_id = parentTask.id;
                    this.editingTask = null;
                    this.resetForm();
                    this.showAddTaskModal = true;
                },

                resetForm() {
                    this.taskForm = {
                        title: '',
                        description: '',
                        parent_id: this.selectedParent ? this.selectedParent.id : null,
                        assigned_to: '',
                        priority: 'medium',
                        due_date: '',
                        estimated_duration: null
                    };
                },

                async submitTask() {
                    try {
                        const url = '{{ route("projects.wbs.store", $project) }}';
                        const response = await fetch(url, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify(this.taskForm)
                        });

                        const data = await response.json();

                        if (data.success) {
                            window.location.reload();
                        } else {
                            alert(data.message || 'Failed to create task');
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        alert('Failed to create task');
                    }
                },

                async deleteTask(taskId) {
                    if (!confirm('Are you sure you want to delete this task? All subtasks will also be deleted.')) {
                        return;
                    }

                    try {
                        const url = `/projects/{{ $project->id }}/wbs/${taskId}`;
                        const response = await fetch(url, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            }
                        });

                        const data = await response.json();

                        if (data.success) {
                            window.location.reload();
                        } else {
                            alert(data.message || 'Failed to delete task');
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        alert('Failed to delete task');
                    }
                },

                expandAll() {
                    document.querySelectorAll('[data-task-collapse]').forEach(el => {
                        el.style.display = 'block';
                    });
                    document.querySelectorAll('[data-expand-icon]').forEach(el => {
                        el.classList.remove('fa-chevron-right');
                        el.classList.add('fa-chevron-down');
                    });
                },

                collapseAll() {
                    document.querySelectorAll('[data-task-collapse]').forEach(el => {
                        el.style.display = 'none';
                    });
                    document.querySelectorAll('[data-expand-icon]').forEach(el => {
                        el.classList.remove('fa-chevron-down');
                        el.classList.add('fa-chevron-right');
                    });
                }
            }
        }

        // Lazy Loading Toggle for Tasks
        async function toggleTaskLazy(taskId) {
            const children = document.getElementById(`children-${taskId}`);
            const icon = document.getElementById(`icon-${taskId}`);
            const isLoaded = children.dataset.loaded === 'true';

            if (children.style.display === 'none') {
                // Expanding - load children if not loaded yet
                children.style.display = 'block';
                icon.classList.remove('fa-chevron-right');
                icon.classList.add('fa-chevron-down');

                if (!isLoaded) {
                    // Load children via AJAX
                    try {
                        const response = await fetch(`/projects/{{ $project->id }}/wbs/${taskId}/children`, {
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            }
                        });

                        const data = await response.json();

                        if (data.success) {
                            children.innerHTML = data.html;
                            children.dataset.loaded = 'true';

                            // Reinitialize drag & drop for newly loaded children
                            initDragDrop();
                        } else {
                            children.innerHTML = '<div class="text-center py-4 text-red-600">Failed to load subtasks</div>';
                        }
                    } catch (error) {
                        console.error('Error loading children:', error);
                        children.innerHTML = '<div class="text-center py-4 text-red-600">Error loading subtasks</div>';
                    }
                }
            } else {
                // Collapsing
                children.style.display = 'none';
                icon.classList.remove('fa-chevron-down');
                icon.classList.add('fa-chevron-right');
            }
        }

        // Legacy toggleTask for backwards compatibility
        function toggleTask(taskId) {
            toggleTaskLazy(taskId);
        }

        // Drag & Drop Functionality
        function initDragDrop() {
            // Initialize sortable for root level tasks
            const taskTree = document.getElementById('taskTree');
            if (taskTree) {
                new Sortable(taskTree, {
                    animation: 150,
                    handle: '.drag-handle',
                    ghostClass: 'bg-primary-100',
                    dragClass: 'opacity-50',
                    onEnd: function(evt) {
                        const taskId = evt.item.dataset.taskId;
                        const newIndex = evt.newIndex;
                        const parentId = null; // Root level

                        reorderTask(taskId, parentId, newIndex);
                    }
                });
            }

            // Initialize sortable for each children container
            document.querySelectorAll('[data-children-container]').forEach(container => {
                new Sortable(container, {
                    animation: 150,
                    handle: '.drag-handle',
                    ghostClass: 'bg-primary-100',
                    dragClass: 'opacity-50',
                    onEnd: function(evt) {
                        const taskId = evt.item.dataset.taskId;
                        const newIndex = evt.newIndex;
                        const parentId = container.dataset.parentId;

                        reorderTask(taskId, parentId, newIndex);
                    }
                });
            });
        }

        async function reorderTask(taskId, parentId, newOrder) {
            try {
                const response = await fetch('{{ route("projects.wbs.reorder", $project) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        task_id: taskId,
                        new_parent_id: parentId,
                        new_order: newOrder
                    })
                });

                const data = await response.json();

                if (data.success) {
                    // Reload page to show updated WBS codes
                    window.location.reload();
                } else {
                    alert(data.message || 'Failed to reorder task');
                    window.location.reload(); // Revert changes
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Failed to reorder task');
                window.location.reload(); // Revert changes
            }
        }

        // Bulk Operations
        function updateBulkActions() {
            const checkboxes = document.querySelectorAll('.task-checkbox:checked');
            const toolbar = document.getElementById('bulkActionsToolbar');
            const countSpan = document.getElementById('selectedCount');

            countSpan.textContent = checkboxes.length;
            toolbar.style.display = checkboxes.length > 0 ? 'block' : 'none';
        }

        function toggleSelectAll(checkbox) {
            document.querySelectorAll('.task-checkbox').forEach(cb => {
                cb.checked = checkbox.checked;
            });
            updateBulkActions();
        }

        function getSelectedTaskIds() {
            const checkboxes = document.querySelectorAll('.task-checkbox:checked');
            return Array.from(checkboxes).map(cb => cb.dataset.taskId);
        }

        function clearSelection() {
            document.querySelectorAll('.task-checkbox').forEach(cb => {
                cb.checked = false;
            });
            document.getElementById('selectAll').checked = false;
            updateBulkActions();
        }

        async function bulkChangeStatus() {
            const taskIds = getSelectedTaskIds();
            if (taskIds.length === 0) return;

            const status = prompt('Enter new status (todo, in-progress, review, completed):');
            if (!status || !['todo', 'in-progress', 'review', 'completed'].includes(status)) {
                alert('Invalid status');
                return;
            }

            try {
                const response = await fetch('{{ route("projects.wbs.bulk-update", $project) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        task_ids: taskIds,
                        status: status
                    })
                });

                const data = await response.json();
                if (data.success) {
                    window.location.reload();
                } else {
                    alert(data.message || 'Failed to update tasks');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Failed to update tasks');
            }
        }

        async function bulkAssign() {
            const taskIds = getSelectedTaskIds();
            if (taskIds.length === 0) return;

            const userId = prompt('Enter user ID to assign tasks:');
            if (!userId) return;

            try {
                const response = await fetch('{{ route("projects.wbs.bulk-assign", $project) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        task_ids: taskIds,
                        assigned_to: parseInt(userId)
                    })
                });

                const data = await response.json();
                if (data.success) {
                    window.location.reload();
                } else {
                    alert(data.message || 'Failed to assign tasks');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Failed to assign tasks');
            }
        }

        async function bulkDelete() {
            const taskIds = getSelectedTaskIds();
            if (taskIds.length === 0) return;

            if (!confirm(`Are you sure you want to delete ${taskIds.length} task(s)? This will also delete all subtasks.`)) {
                return;
            }

            try {
                const response = await fetch('{{ route("projects.wbs.bulk-delete", $project) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        task_ids: taskIds
                    })
                });

                const data = await response.json();
                if (data.success) {
                    window.location.reload();
                } else {
                    alert(data.message || 'Failed to delete tasks');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Failed to delete tasks');
            }
        }

        // Template Functions
        function openSaveTemplateModal() {
            document.getElementById('saveTemplateModal').style.display = 'flex';
        }

        function closeSaveTemplateModal() {
            document.getElementById('saveTemplateModal').style.display = 'none';
            document.getElementById('saveTemplateForm').reset();
        }

        async function saveTemplate(event) {
            event.preventDefault();
            const formData = new FormData(event.target);
            const data = Object.fromEntries(formData);

            try {
                const response = await fetch('{{ route("projects.wbs.templates.save", $project) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();
                if (result.success) {
                    alert('Template saved successfully!');
                    closeSaveTemplateModal();
                } else {
                    alert(result.message || 'Failed to save template');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Failed to save template');
            }
        }

        async function openLoadTemplateModal() {
            document.getElementById('loadTemplateModal').style.display = 'flex';
            await loadTemplates();
        }

        function closeLoadTemplateModal() {
            document.getElementById('loadTemplateModal').style.display = 'none';
        }

        async function loadTemplates() {
            try {
                const response = await fetch('{{ route("projects.wbs.templates.list", $project) }}');
                const data = await response.json();

                const list = document.getElementById('templatesList');
                if (data.templates.length === 0) {
                    list.innerHTML = '<p class="text-gray-500 text-center py-4">No templates available</p>';
                    return;
                }

                list.innerHTML = data.templates.map(template => `
                    <div class="border dark:border-gray-700 rounded-lg p-4 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <h4 class="font-semibold text-gray-900 dark:text-white">${template.name}</h4>
                                ${template.description ? `<p class="text-sm text-gray-600 dark:text-gray-400 mt-1">${template.description}</p>` : ''}
                                <p class="text-xs text-gray-500 mt-2">Created: ${new Date(template.created_at).toLocaleDateString()}</p>
                            </div>
                            <div class="flex gap-2">
                                <button onclick="applyTemplate(${template.id})" class="px-3 py-1 bg-indigo-600 hover:bg-indigo-700 text-white rounded text-sm">
                                    <i class="fas fa-download mr-1"></i>Load
                                </button>
                                <button onclick="deleteTemplate(${template.id})" class="px-3 py-1 bg-red-600 hover:bg-red-700 text-white rounded text-sm">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                `).join('');
            } catch (error) {
                console.error('Error:', error);
                document.getElementById('templatesList').innerHTML = '<p class="text-red-500 text-center py-4">Failed to load templates</p>';
            }
        }

        async function applyTemplate(templateId) {
            if (!confirm('This will replace all existing tasks. Continue?')) return;

            try {
                const response = await fetch('{{ route("projects.wbs.templates.load", $project) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ template_id: templateId })
                });

                const data = await response.json();
                if (data.success) {
                    alert('Template loaded successfully!');
                    window.location.reload();
                } else {
                    alert(data.message || 'Failed to load template');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Failed to load template');
            }
        }

        async function deleteTemplate(templateId) {
            if (!confirm('Delete this template?')) return;

            try {
                const response = await fetch(`/projects/{{ $project->id }}/wbs/templates/${templateId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                const data = await response.json();
                if (data.success) {
                    await loadTemplates(); // Refresh list
                } else {
                    alert(data.message || 'Failed to delete template');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Failed to delete template');
            }
        }

        // Weight Management Functions
        function openWeightManagerModal() {
            document.getElementById('weightManagerModal').style.display = 'flex';
            switchWeightTab('summary');
        }

        function closeWeightManagerModal() {
            document.getElementById('weightManagerModal').style.display = 'none';
        }

        function switchWeightTab(tab) {
            // Update tab buttons
            document.querySelectorAll('.weight-tab').forEach(btn => {
                btn.classList.remove('border-amber-600', 'text-amber-600');
                btn.classList.add('border-transparent', 'text-gray-500');
            });
            document.getElementById(`tab-${tab}`).classList.remove('border-transparent', 'text-gray-500');
            document.getElementById(`tab-${tab}`).classList.add('border-amber-600', 'text-amber-600');

            // Hide all content
            document.querySelectorAll('.weight-tab-content').forEach(content => {
                content.classList.add('hidden');
            });

            // Show selected content and load data
            const contentMap = {
                'summary': 'weightSummaryContent',
                'timeline': 'weightTimelineContent',
                'status': 'weightStatusContent'
            };

            document.getElementById(contentMap[tab]).classList.remove('hidden');

            // Load data
            if (tab === 'summary') loadWeightSummary();
            else if (tab === 'timeline') loadWeightTimeline();
            else if (tab === 'status') loadWeightByStatus();
        }

        async function loadWeightTimeline() {
            const container = document.getElementById('weightTimelineContent');
            container.innerHTML = '<div class="text-center py-8 text-gray-500"><i class="fas fa-spinner fa-spin mr-2"></i>Loading timeline...</div>';

            try {
                const response = await fetch('{{ route("projects.wbs.weight.timeline", $project) }}');
                const data = await response.json();

                if (!data.success || data.timeline.length === 0) {
                    container.innerHTML = '<p class="text-gray-500 text-center py-8">No timeline data available. Add due dates to tasks with weights.</p>';
                    return;
                }

                let html = '<div class="space-y-6">';

                // Timeline chart
                html += '<div class="bg-gradient-to-r from-amber-50 to-orange-50 dark:from-amber-900/20 dark:to-orange-900/20 rounded-lg p-6">';
                html += '<h4 class="text-lg font-bold text-gray-900 dark:text-white mb-4"><i class="fas fa-chart-area mr-2"></i>Cumulative Weight Distribution</h4>';
                html += '<div class="space-y-3">';

                const maxCumulative = Math.max(...data.timeline.map(t => t.cumulative_weight));

                data.timeline.forEach((period, index) => {
                    const barWidth = (period.cumulative_weight / maxCumulative) * 100;
                    const isLast = index === data.timeline.length - 1;

                    html += `
                        <div class="space-y-2">
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">${period.month}</span>
                                <div class="flex items-center gap-3">
                                    <span class="text-xs text-gray-600 dark:text-gray-400">+${period.total_weight}%</span>
                                    <span class="text-sm font-bold ${isLast ? 'text-green-600' : 'text-amber-600'}">${period.cumulative_weight}%</span>
                                </div>
                            </div>
                            <div class="relative h-8 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
                                <div class="absolute inset-0 bg-gradient-to-r from-amber-400 via-orange-400 to-amber-500 transition-all duration-500"
                                     style="width: ${barWidth}%">
                                    <div class="absolute inset-0 flex items-center justify-center text-xs font-bold text-white">
                                        ${period.tasks.length} task(s)
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                });

                html += '</div></div>';

                // Task details by month
                html += '<div class="space-y-4">';
                html += '<h4 class="text-lg font-bold text-gray-900 dark:text-white"><i class="fas fa-tasks mr-2"></i>Tasks by Month</h4>';

                data.timeline.forEach(period => {
                    html += `
                        <div class="border dark:border-gray-700 rounded-lg p-4">
                            <div class="flex justify-between items-center mb-3">
                                <h5 class="font-semibold text-gray-900 dark:text-white">${period.month}</h5>
                                <span class="px-3 py-1 bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400 rounded-full text-sm font-medium">
                                    ${period.total_weight}% weight
                                </span>
                            </div>
                            <div class="space-y-2">
                                ${period.tasks.map(task => {
                                    const statusColors = {
                                        'todo': 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300',
                                        'in-progress': 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
                                        'review': 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400',
                                        'completed': 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400'
                                    };
                                    const statusClass = statusColors[task.status] || statusColors.todo;

                                    return `
                                        <div class="flex items-center justify-between p-2 bg-gray-50 dark:bg-gray-900/50 rounded">
                                            <div class="flex items-center gap-2">
                                                <span class="text-xs font-mono text-gray-600 dark:text-gray-400">${task.wbs_code}</span>
                                                <span class="text-sm text-gray-900 dark:text-white">${task.title}</span>
                                                <span class="px-2 py-0.5 ${statusClass} rounded text-xs font-medium">
                                                    ${task.status.replace('-', ' ')}
                                                </span>
                                            </div>
                                            <span class="text-sm font-semibold text-amber-600">${task.weight}%</span>
                                        </div>
                                    `;
                                }).join('')}
                            </div>
                        </div>
                    `;
                });

                html += '</div></div>';
                container.innerHTML = html;

            } catch (error) {
                console.error('Error:', error);
                container.innerHTML = '<p class="text-red-500 text-center py-8">Failed to load timeline data</p>';
            }
        }

        async function loadWeightByStatus() {
            const container = document.getElementById('weightStatusContent');
            container.innerHTML = '<div class="text-center py-8 text-gray-500"><i class="fas fa-spinner fa-spin mr-2"></i>Loading status distribution...</div>';

            try {
                const response = await fetch('{{ route("projects.wbs.weight.by-status", $project) }}');
                const data = await response.json();

                if (!data.success || data.distribution.length === 0) {
                    container.innerHTML = '<p class="text-gray-500 text-center py-8">No weight distribution by status</p>';
                    return;
                }

                const statusConfig = {
                    'todo': { color: 'gray', icon: 'circle', label: 'To Do' },
                    'in-progress': { color: 'blue', icon: 'spinner', label: 'In Progress' },
                    'review': { color: 'yellow', icon: 'eye', label: 'Review' },
                    'completed': { color: 'green', icon: 'check-circle', label: 'Completed' }
                };

                let html = '<div class="space-y-6">';

                // Status cards
                html += '<div class="grid grid-cols-1 md:grid-cols-2 gap-4">';

                data.distribution.forEach(item => {
                    const config = statusConfig[item.status] || statusConfig.todo;
                    const barWidth = (item.weight / data.total_weight) * 100;

                    // Status-specific classes (fully defined to avoid Tailwind JIT issues)
                    const statusClasses = {
                        'todo': {
                            border: 'border-gray-200 dark:border-gray-800',
                            bg: 'bg-gray-50 dark:bg-gray-900/20',
                            icon: 'text-gray-600 dark:text-gray-400',
                            badge: 'bg-gray-100 dark:bg-gray-900/50 text-gray-700 dark:text-gray-400',
                            text: 'text-gray-600 dark:text-gray-400',
                            gradient: 'from-gray-400 to-gray-500'
                        },
                        'in-progress': {
                            border: 'border-blue-200 dark:border-blue-800',
                            bg: 'bg-blue-50 dark:bg-blue-900/20',
                            icon: 'text-blue-600 dark:text-blue-400',
                            badge: 'bg-blue-100 dark:bg-blue-900/50 text-blue-700 dark:text-blue-400',
                            text: 'text-blue-600 dark:text-blue-400',
                            gradient: 'from-blue-400 to-blue-500'
                        },
                        'review': {
                            border: 'border-yellow-200 dark:border-yellow-800',
                            bg: 'bg-yellow-50 dark:bg-yellow-900/20',
                            icon: 'text-yellow-600 dark:text-yellow-400',
                            badge: 'bg-yellow-100 dark:bg-yellow-900/50 text-yellow-700 dark:text-yellow-400',
                            text: 'text-yellow-600 dark:text-yellow-400',
                            gradient: 'from-yellow-400 to-yellow-500'
                        },
                        'completed': {
                            border: 'border-green-200 dark:border-green-800',
                            bg: 'bg-green-50 dark:bg-green-900/20',
                            icon: 'text-green-600 dark:text-green-400',
                            badge: 'bg-green-100 dark:bg-green-900/50 text-green-700 dark:text-green-400',
                            text: 'text-green-600 dark:text-green-400',
                            gradient: 'from-green-400 to-green-500'
                        }
                    };

                    const classes = statusClasses[item.status] || statusClasses.todo;

                    html += `
                        <div class="border-2 ${classes.border} rounded-lg p-4 ${classes.bg}">
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-${config.icon} ${classes.icon}"></i>
                                    <span class="font-semibold text-gray-900 dark:text-white">${config.label}</span>
                                </div>
                                <span class="px-3 py-1 ${classes.badge} rounded-full text-sm font-bold">
                                    ${item.task_count} task(s)
                                </span>
                            </div>
                            <div class="mb-2">
                                <div class="flex justify-between text-sm mb-1">
                                    <span class="text-gray-600 dark:text-gray-400">Weight</span>
                                    <span class="font-bold ${classes.text}">${item.weight}% (${item.percentage}%)</span>
                                </div>
                                <div class="h-3 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
                                    <div class="h-full bg-gradient-to-r ${classes.gradient} transition-all duration-500"
                                         style="width: ${barWidth}%"></div>
                                </div>
                            </div>
                        </div>
                    `;
                });

                html += '</div>';

                // Summary
                html += `
                    <div class="bg-gradient-to-r from-purple-50 to-indigo-50 dark:from-purple-900/20 dark:to-indigo-900/20 rounded-lg p-6 border-2 border-purple-200 dark:border-purple-800">
                        <div class="flex items-center justify-between">
                            <div>
                                <h4 class="text-lg font-bold text-gray-900 dark:text-white mb-1">Total Weighted Tasks</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Across all statuses</p>
                            </div>
                            <div class="text-right">
                                <div class="text-4xl font-bold text-purple-600 dark:text-purple-400">${data.total_weight}%</div>
                                <div class="text-sm text-gray-600 dark:text-gray-400">${data.distribution.reduce((sum, item) => sum + item.task_count, 0)} tasks</div>
                            </div>
                        </div>
                    </div>
                `;

                html += '</div>';
                container.innerHTML = html;

            } catch (error) {
                console.error('Error:', error);
                container.innerHTML = '<p class="text-red-500 text-center py-8">Failed to load status distribution</p>';
            }
        }

        async function loadWeightSummary() {
            try {
                const response = await fetch('{{ route("projects.wbs.weight.summary", $project) }}');
                const data = await response.json();

                const container = document.getElementById('weightSummaryContent');

                if (!data.success || Object.keys(data.summary).length === 0) {
                    container.innerHTML = '<p class="text-gray-500 text-center py-8">No tasks with weights assigned</p>';
                    return;
                }

                let html = '<div class="space-y-6">';

                Object.entries(data.summary).forEach(([parentId, group]) => {
                    const parentName = group.parent
                        ? `${group.parent.wbs_code} - ${group.parent.title}`
                        : 'Root Level Tasks';
                    const statusBg = group.is_valid ? 'bg-green-100 dark:bg-green-900/30' : 'bg-red-100 dark:bg-red-900/30';
                    const statusText = group.is_valid ? 'text-green-700 dark:text-green-400' : 'text-red-700 dark:text-red-400';

                    html += `
                        <div class="border dark:border-gray-700 rounded-lg p-4">
                            <div class="flex justify-between items-center mb-3">
                                <h4 class="font-semibold text-gray-900 dark:text-white">${parentName}</h4>
                                <div class="flex items-center gap-3">
                                    <span class="text-sm px-3 py-1 rounded-full ${statusBg} ${statusText} font-medium">
                                        Total: ${group.total_weight}%
                                        ${group.is_valid ? '<i class="fas fa-check ml-1"></i>' : '<i class="fas fa-exclamation-triangle ml-1"></i>'}
                                    </span>
                                    <button onclick="autoDistribute('${parentId}')" class="px-3 py-1 bg-amber-600 hover:bg-amber-700 text-white rounded text-sm">
                                        <i class="fas fa-magic mr-1"></i>Auto Distribute
                                    </button>
                                </div>
                            </div>
                            <div class="space-y-2">
                                ${group.tasks.map(task => `
                                    <div class="flex items-center justify-between p-2 bg-gray-50 dark:bg-gray-900/50 rounded">
                                        <div class="flex items-center gap-2">
                                            <span class="text-xs font-mono text-gray-600 dark:text-gray-400">${task.wbs_code}</span>
                                            <span class="text-sm text-gray-900 dark:text-white">${task.title}</span>
                                            ${task.is_locked ? '<i class="fas fa-lock text-amber-600 text-xs"></i>' : ''}
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <span class="text-sm font-semibold text-gray-900 dark:text-white">${task.weight}%</span>
                                            <span class="text-xs text-gray-500">(${task.weight_percentage}% of group)</span>
                                            <button onclick="openWeightEditor(${task.id}, ${task.weight}, ${task.is_locked})" class="px-2 py-1 bg-blue-600 hover:bg-blue-700 text-white rounded text-xs">
                                                Edit
                                            </button>
                                        </div>
                                    </div>
                                `).join('')}
                            </div>
                        </div>
                    `;
                });

                html += '</div>';
                container.innerHTML = html;
            } catch (error) {
                console.error('Error:', error);
                document.getElementById('weightSummaryContent').innerHTML = '<p class="text-red-500 text-center py-8">Failed to load weight summary</p>';
            }
        }

        function openWeightEditor(taskId, currentWeight, isLocked) {
            document.getElementById('weightTaskId').value = taskId;
            document.getElementById('weightInput').value = currentWeight;
            document.getElementById('weightLockCheckbox').checked = isLocked;
            document.getElementById('weightEditorModal').style.display = 'flex';
        }

        function closeWeightEditorModal() {
            document.getElementById('weightEditorModal').style.display = 'none';
            document.getElementById('weightEditorForm').reset();
        }

        async function saveWeight(event) {
            event.preventDefault();
            const formData = new FormData(event.target);
            const taskId = formData.get('task_id');
            const weight = formData.get('weight');
            const isLocked = document.getElementById('weightLockCheckbox').checked;

            try {
                // Update weight
                const response = await fetch(`/projects/{{ $project->id }}/wbs/${taskId}/weight`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ weight: parseFloat(weight) })
                });

                const data = await response.json();
                if (!data.success) {
                    throw new Error(data.message);
                }

                // Update lock if changed
                if (data.task.is_weight_locked !== isLocked) {
                    await fetch(`/projects/{{ $project->id }}/wbs/${taskId}/weight/toggle-lock`, {
                        method: 'PATCH',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    });
                }

                closeWeightEditorModal();

                // Show validation feedback
                if (data.validation) {
                    showValidationToast(data.validation);
                }

                // Refresh page or summary
                if (document.getElementById('weightManagerModal').style.display === 'flex') {
                    loadWeightSummary();
                } else {
                    window.location.reload();
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Failed to save weight: ' + error.message);
            }
        }

        // Real-time validation preview
        let currentTaskSiblings = [];

        function previewValidation() {
            const input = document.getElementById('weightInput');
            const preview = document.getElementById('validationPreview');
            const value = input.value;
            const newWeight = parseFloat(value);

            // Reset preview
            preview.classList.add('hidden');
            preview.className = 'mt-2 text-sm';

            // Check if empty or not a number
            if (value === '' || isNaN(newWeight)) {
                preview.className += ' text-gray-600 dark:text-gray-400';
                preview.innerHTML = '<i class="fas fa-info-circle mr-1"></i>Enter a weight value (0-100)';
                preview.classList.remove('hidden');
                return;
            }

            // Check range
            if (newWeight > 100) {
                preview.className += ' text-red-600 dark:text-red-400';
                preview.innerHTML = '<i class="fas fa-exclamation-triangle mr-1"></i>Weight cannot exceed 100%';
                preview.classList.remove('hidden');
            } else if (newWeight < 0) {
                preview.className += ' text-red-600 dark:text-red-400';
                preview.innerHTML = '<i class="fas fa-exclamation-triangle mr-1"></i>Weight cannot be negative';
                preview.classList.remove('hidden');
            } else if (newWeight === 0) {
                preview.className += ' text-yellow-600 dark:text-yellow-400';
                preview.innerHTML = '<i class="fas fa-info-circle mr-1"></i>Setting weight to 0 (task will not count in total)';
                preview.classList.remove('hidden');
            } else {
                // Valid weight
                preview.className += ' text-green-600 dark:text-green-400';
                preview.innerHTML = '<i class="fas fa-check-circle mr-1"></i>Valid weight: ' + newWeight.toFixed(2) + '%';
                preview.classList.remove('hidden');
            }
        }

        function showValidationToast(validation) {
            const toast = document.createElement('div');
            toast.className = 'fixed top-4 right-4 z-50 max-w-md p-4 rounded-lg shadow-lg transform transition-all duration-300 translate-x-0';

            let bgColor, icon, message;

            if (validation.status === 'perfect') {
                bgColor = 'bg-green-100 border-l-4 border-green-500 text-green-800 dark:bg-green-900/30 dark:text-green-400';
                icon = '<i class="fas fa-check-circle text-green-500 mr-2"></i>';
                message = `✓ Perfect! Total weight = 100%`;
            } else if (validation.status === 'over') {
                bgColor = 'bg-red-100 border-l-4 border-red-500 text-red-800 dark:bg-red-900/30 dark:text-red-400';
                icon = '<i class="fas fa-exclamation-triangle text-red-500 mr-2"></i>';
                message = `⚠ Over by ${Math.abs(validation.difference)}% (Total: ${validation.total_weight}%)`;
            } else if (validation.status === 'under') {
                bgColor = 'bg-yellow-100 border-l-4 border-yellow-500 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400';
                icon = '<i class="fas fa-info-circle text-yellow-500 mr-2"></i>';
                message = `ℹ Under by ${Math.abs(validation.difference)}% (Total: ${validation.total_weight}%)`;
            }

            toast.className += ' ' + bgColor;
            toast.innerHTML = `
                <div class="flex items-center">
                    ${icon}
                    <div class="flex-1">
                        <p class="font-semibold">${message}</p>
                        <p class="text-sm mt-1">${validation.task_count} tasks (${validation.locked_count} locked)</p>
                    </div>
                    <button onclick="this.parentElement.parentElement.remove()" class="ml-3 text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;

            document.body.appendChild(toast);

            // Auto-remove after 5 seconds
            setTimeout(() => {
                toast.style.transform = 'translateX(400px)';
                setTimeout(() => toast.remove(), 300);
            }, 5000);
        }

        async function autoDistribute(parentId) {
            if (!confirm('Distribute weight equally among all unlocked sibling tasks?')) return;

            try {
                const response = await fetch('{{ route("projects.wbs.weight.auto-distribute", $project) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        parent_id: parentId === 'null' ? null : parseInt(parentId)
                    })
                });

                const data = await response.json();
                if (data.success) {
                    // Show validation feedback
                    if (data.validation) {
                        showValidationToast(data.validation);
                    }
                    loadWeightSummary();
                } else {
                    alert(data.message || 'Failed to distribute weight');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Failed to distribute weight');
            }
        }

        // Calendar Settings Functions
        function openCalendarSettingsModal() {
            document.getElementById('calendarSettingsModal').style.display = 'flex';
            switchCalendarTab('working-days');
            loadCalendarSettings();
        }

        function closeCalendarSettingsModal() {
            document.getElementById('calendarSettingsModal').style.display = 'none';
        }

        function switchCalendarTab(tab) {
            // Update tab buttons
            document.querySelectorAll('.calendar-tab').forEach(btn => {
                btn.classList.remove('border-teal-600', 'text-teal-600');
                btn.classList.add('border-transparent', 'text-gray-500');
            });
            document.getElementById(`tab-${tab}`).classList.remove('border-transparent', 'text-gray-500');
            document.getElementById(`tab-${tab}`).classList.add('border-teal-600', 'text-teal-600');

            // Hide all content
            document.querySelectorAll('.calendar-tab-content').forEach(content => {
                content.classList.add('hidden');
            });

            // Show selected content and load data
            const contentMap = {
                'working-days': 'workingDaysContent',
                'holidays': 'holidaysContent',
                'planning': 'planningContent'
            };

            const contentId = contentMap[tab];
            if (contentId) {
                document.getElementById(contentId).classList.remove('hidden');

                if (tab === 'holidays') {
                    loadHolidays();
                } else if (tab === 'planning') {
                    loadWeeklyPlanning();
                }
            }
        }

        async function loadCalendarSettings() {
            try {
                const response = await fetch('{{ route("projects.wbs.calendar.settings", $project) }}');
                const data = await response.json();

                if (data.success) {
                    // Populate working days
                    const workingDays = data.working_days;
                    ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'].forEach(day => {
                        const checkbox = document.getElementById(day);
                        if (checkbox) {
                            checkbox.checked = workingDays[day] ?? false;
                        }
                    });

                    document.getElementById('work_start_time').value = workingDays.work_start_time || '09:00';
                    document.getElementById('work_end_time').value = workingDays.work_end_time || '17:00';
                    document.getElementById('hours_per_day').value = workingDays.hours_per_day || 8;
                }
            } catch (error) {
                console.error('Error:', error);
            }
        }

        async function saveWorkingDays() {
            const data = {
                monday: document.getElementById('monday').checked,
                tuesday: document.getElementById('tuesday').checked,
                wednesday: document.getElementById('wednesday').checked,
                thursday: document.getElementById('thursday').checked,
                friday: document.getElementById('friday').checked,
                saturday: document.getElementById('saturday').checked,
                sunday: document.getElementById('sunday').checked,
                work_start_time: document.getElementById('work_start_time').value,
                work_end_time: document.getElementById('work_end_time').value,
                hours_per_day: parseFloat(document.getElementById('hours_per_day').value)
            };

            // Validate at least one working day is selected
            const hasWorkingDay = Object.keys(data).some(key =>
                ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'].includes(key) && data[key]
            );

            if (!hasWorkingDay) {
                alert('⚠ Please select at least one working day');
                return;
            }

            // Validate work times
            if (data.work_start_time && data.work_end_time) {
                if (data.work_start_time >= data.work_end_time) {
                    alert('⚠ Work end time must be after start time');
                    return;
                }
            }

            // Validate hours per day
            if (isNaN(data.hours_per_day) || data.hours_per_day < 1 || data.hours_per_day > 24) {
                alert('⚠ Hours per day must be between 1 and 24');
                return;
            }

            try {
                const response = await fetch('{{ route("projects.wbs.calendar.working-days", $project) }}', {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();
                if (result.success) {
                    alert('✓ Working days saved successfully!');
                } else {
                    alert('Failed to save: ' + result.message);
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Failed to save working days');
            }
        }

        async function loadHolidays() {
            const container = document.getElementById('holidaysList');
            container.innerHTML = '<div class="text-center py-8 text-gray-500"><i class="fas fa-spinner fa-spin mr-2"></i>Loading holidays...</div>';

            try {
                const response = await fetch('{{ route("projects.wbs.calendar.settings", $project) }}');
                const data = await response.json();

                if (!data.success || data.holidays.length === 0) {
                    container.innerHTML = '<div class="text-center py-8 text-gray-500">No holidays added yet</div>';
                    return;
                }

                let html = '';
                data.holidays.forEach(holiday => {
                    const date = new Date(holiday.date);
                    const typeColors = {
                        'holiday': 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
                        'non-working-day': 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300',
                        'custom': 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400'
                    };
                    const typeClass = typeColors[holiday.type] || typeColors.custom;

                    html += `
                        <div class="flex items-center justify-between p-3 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg">
                            <div class="flex-1">
                                <div class="flex items-center gap-2">
                                    <span class="font-semibold text-gray-900 dark:text-white">${holiday.name}</span>
                                    <span class="px-2 py-0.5 ${typeClass} rounded text-xs">${holiday.type}</span>
                                    ${holiday.is_recurring ? '<i class="fas fa-sync-alt text-teal-600 text-xs" title="Recurring annually"></i>' : ''}
                                </div>
                                <div class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                    <i class="fas fa-calendar mr-1"></i>${date.toLocaleDateString('en-US', { weekday: 'short', year: 'numeric', month: 'short', day: 'numeric' })}
                                </div>
                                ${holiday.description ? `<div class="text-sm text-gray-500 dark:text-gray-400 mt-1">${holiday.description}</div>` : ''}
                            </div>
                            <button onclick="deleteHoliday(${holiday.id})" class="ml-3 px-3 py-1 bg-red-600 hover:bg-red-700 text-white rounded text-sm">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    `;
                });

                container.innerHTML = html;
            } catch (error) {
                console.error('Error:', error);
                container.innerHTML = '<div class="text-center py-8 text-red-500">Failed to load holidays</div>';
            }
        }

        async function addHoliday() {
            const data = {
                date: document.getElementById('holiday_date').value,
                name: document.getElementById('holiday_name').value,
                type: document.getElementById('holiday_type').value,
                description: document.getElementById('holiday_description').value,
                is_recurring: document.getElementById('is_recurring').checked
            };

            if (!data.date || !data.name) {
                alert('Please fill in date and name');
                return;
            }

            try {
                const response = await fetch('{{ route("projects.wbs.calendar.holidays.add", $project) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();
                if (result.success) {
                    // Clear form
                    document.getElementById('holiday_date').value = '';
                    document.getElementById('holiday_name').value = '';
                    document.getElementById('holiday_description').value = '';
                    document.getElementById('is_recurring').checked = false;

                    // Reload holidays list
                    loadHolidays();
                    alert('✓ Holiday added successfully!');
                } else {
                    alert('Failed to add holiday: ' + result.message);
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Failed to add holiday');
            }
        }

        async function deleteHoliday(holidayId) {
            if (!confirm('Delete this holiday?')) return;

            try {
                const response = await fetch(`/projects/{{ $project->id }}/wbs/calendar/holidays/${holidayId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                const result = await response.json();
                if (result.success) {
                    loadHolidays();
                } else {
                    alert('Failed to delete: ' + result.message);
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Failed to delete holiday');
            }
        }

        async function loadWeeklyPlanning() {
            const container = document.getElementById('planningContent');
            container.innerHTML = '<div class="text-center py-8 text-gray-500"><i class="fas fa-spinner fa-spin mr-2"></i>Loading planning...</div>';

            try {
                const response = await fetch('{{ route("projects.wbs.calendar.planning", $project) }}?view=week');
                const data = await response.json();

                if (!data.success) {
                    container.innerHTML = '<div class="text-center py-8 text-gray-500">No planning data available</div>';
                    return;
                }

                let html = `
                    <div class="space-y-6">
                        <div class="bg-gradient-to-r from-teal-50 to-blue-50 dark:from-teal-900/20 dark:to-blue-900/20 rounded-lg p-6">
                            <h4 class="text-lg font-bold text-gray-900 dark:text-white mb-2">
                                Week Overview: ${data.start_date} to ${data.end_date}
                            </h4>
                            <div class="grid grid-cols-3 gap-4 mt-4">
                `;

                data.breakdown.forEach(period => {
                    html += `
                        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg border border-gray-200 dark:border-gray-700">
                            <div class="text-sm text-gray-600 dark:text-gray-400">${period.period}</div>
                            <div class="text-2xl font-bold text-teal-600 mt-2">${period.working_days}</div>
                            <div class="text-xs text-gray-500">working days</div>
                            ${period.holidays > 0 ? `<div class="text-xs text-red-600 mt-1">${period.holidays} holiday(s)</div>` : ''}
                        </div>
                    `;
                });

                html += '</div></div>';

                // Tasks breakdown by date
                if (Object.keys(data.tasks).length > 0) {
                    html += '<div class="space-y-3"><h4 class="font-semibold text-gray-900 dark:text-white mb-3">Tasks This Week</h4>';

                    Object.entries(data.tasks).forEach(([date, tasks]) => {
                        const dateObj = new Date(date);
                        html += `
                            <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                                <div class="font-semibold text-gray-900 dark:text-white mb-2">
                                    ${dateObj.toLocaleDateString('en-US', { weekday: 'short', month: 'short', day: 'numeric' })}
                                </div>
                                <div class="space-y-2">
                                    ${tasks.map(task => `
                                        <div class="flex items-center justify-between p-2 bg-gray-50 dark:bg-gray-900/50 rounded">
                                            <div>
                                                <span class="text-xs font-mono text-gray-600 dark:text-gray-400 mr-2">${task.wbs_code}</span>
                                                <span class="text-sm text-gray-900 dark:text-white">${task.title}</span>
                                            </div>
                                            <span class="text-xs text-gray-500">${task.assignee}</span>
                                        </div>
                                    `).join('')}
                                </div>
                            </div>
                        `;
                    });
                    html += '</div>';
                } else {
                    html += '<div class="text-center py-8 text-gray-500">No tasks scheduled this week</div>';
                }

                html += '</div>';
                container.innerHTML = html;
            } catch (error) {
                console.error('Error:', error);
                container.innerHTML = '<div class="text-center py-8 text-red-500">Failed to load planning data</div>';
            }
        }

        // Initialize drag & drop after page loads
        document.addEventListener('DOMContentLoaded', function() {
            initDragDrop();
        });
    </script>

    <!-- SortableJS Library for Drag & Drop -->
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    @endpush
@endsection
