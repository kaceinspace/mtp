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
            <a href="{{ route('projects.wbs.critical-path', $project) }}"
               class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg transition z-10 relative">
                <i class="fas fa-route mr-2"></i>Critical Path
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

            <!-- Task Tree -->
            <div class="space-y-2">
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
    </script>
    @endpush
@endsection
