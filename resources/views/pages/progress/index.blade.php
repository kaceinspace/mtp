@extends('layouts.dashboard')

@section('title', 'Weekly Progress - ' . $project->title)
@section('page-title', 'Weekly Progress Tracking')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                Weekly Progress Tracking - {{ $project->title }}
            </h2>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                Week {{ $currentWeekStart->format('W, Y') }}
                ({{ $currentWeekStart->format('M d') }} - {{ $currentWeekEnd->format('M d, Y') }})
            </p>
        </div>
        <div class="flex gap-2">
            <button type="button" onclick="previousWeek()"
                    class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition">
                <i class="fas fa-chevron-left mr-2"></i>Previous Week
            </button>
            <button type="button" onclick="nextWeek()"
                    class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition">
                Next Week<i class="fas fa-chevron-right ml-2"></i>
            </button>
            <button type="button" onclick="openWeeklyPlanModal()"
                    class="px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white rounded-lg transition">
                <i class="fas fa-calendar-week mr-2"></i>Weekly Plan
            </button>

            <!-- Report & Export Buttons -->
            <div class="relative group">
                <button type="button"
                        class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg transition flex items-center">
                    <i class="fas fa-file-download mr-2"></i>Reports
                    <i class="fas fa-chevron-down ml-2 text-xs"></i>
                </button>
                <div class="absolute right-0 mt-2 w-56 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                    <a href="{{ route('projects.progress.report', $project) }}?week_start={{ $currentWeekStart->format('Y-m-d') }}"
                       class="flex items-center px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                        <i class="fas fa-eye text-blue-600 w-5"></i>
                        <span class="ml-3 text-gray-900 dark:text-white">View Report</span>
                    </a>
                    <a href="{{ route('projects.progress.export.excel', $project) }}?week_start={{ $currentWeekStart->format('Y-m-d') }}"
                       class="flex items-center px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700 transition border-t border-gray-100 dark:border-gray-700">
                        <i class="fas fa-file-excel text-green-600 w-5"></i>
                        <span class="ml-3 text-gray-900 dark:text-white">Export Excel</span>
                    </a>
                    <a href="{{ route('projects.progress.export.pdf', $project) }}?week_start={{ $currentWeekStart->format('Y-m-d') }}"
                       target="_blank"
                       class="flex items-center px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700 transition border-t border-gray-100 dark:border-gray-700 rounded-b-lg">
                        <i class="fas fa-file-pdf text-red-600 w-5"></i>
                        <span class="ml-3 text-gray-900 dark:text-white">Export PDF</span>
                    </a>
                </div>
            </div>

            <a href="{{ route('projects.analytics.index', $project) }}"
               class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition">
                <i class="fas fa-chart-line mr-2"></i>Analytics
            </a>

            <a href="{{ route('projects.wbs', $project) }}"
               class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition">
                <i class="fas fa-arrow-left mr-2"></i>Back to WBS
            </a>
        </div>
    </div>

    <!-- Weekly Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Planned Weight -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Bobot Planned</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1" id="plannedWeight">
                        {{ $summary['planned_weight'] }}%
                    </p>
                </div>
                <div class="p-3 bg-blue-100 dark:bg-blue-900/30 rounded-lg">
                    <i class="fas fa-chart-line text-2xl text-blue-600"></i>
                </div>
            </div>
        </div>

        <!-- Actual Weight -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Bobot Actual</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1" id="actualWeight">
                        {{ $summary['actual_weight'] }}%
                    </p>
                </div>
                <div class="p-3 bg-green-100 dark:bg-green-900/30 rounded-lg">
                    <i class="fas fa-check-circle text-2xl text-green-600"></i>
                </div>
            </div>
        </div>

        <!-- Deviation -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Deviation</p>
                    <p class="text-2xl font-bold mt-1" id="deviationWeight"
                       data-deviation="{{ $summary['deviation_weight'] }}">
                        {{ $summary['deviation_weight'] }}%
                    </p>
                </div>
                <div class="p-3 rounded-lg" id="deviationIcon">
                    <i class="fas fa-exclamation-triangle text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Completion Rate -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Completion Rate</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1" id="completionRate">
                        {{ $summary['completion_rate'] }}%
                    </p>
                </div>
                <div class="p-3 bg-purple-100 dark:bg-purple-900/30 rounded-lg">
                    <i class="fas fa-percentage text-2xl text-purple-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Distribution -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Task Status Distribution</h3>
        <div class="grid grid-cols-4 gap-4">
            <div class="text-center p-4 bg-green-50 dark:bg-green-900/20 rounded-lg">
                <p class="text-3xl font-bold text-green-600" id="completedCount">{{ $summary['completed'] }}</p>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Completed</p>
            </div>
            <div class="text-center p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                <p class="text-3xl font-bold text-blue-600" id="onTrackCount">{{ $summary['on_track'] }}</p>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">On Track</p>
            </div>
            <div class="text-center p-4 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg">
                <p class="text-3xl font-bold text-yellow-600" id="atRiskCount">{{ $summary['at_risk'] }}</p>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">At Risk</p>
            </div>
            <div class="text-center p-4 bg-red-50 dark:bg-red-900/20 rounded-lg">
                <p class="text-3xl font-bold text-red-600" id="delayedCount">{{ $summary['delayed'] }}</p>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Delayed</p>
            </div>
        </div>
    </div>

    <!-- Deviation Alerts -->
    @if($summary['delayed'] > 0 || $summary['at_risk'] > 0)
    <div class="bg-red-50 dark:bg-red-900/20 border-l-4 border-red-500 rounded-lg p-4">
        <div class="flex items-center">
            <i class="fas fa-exclamation-circle text-red-500 text-xl mr-3"></i>
            <div>
                <p class="font-semibold text-red-800 dark:text-red-400">Deviation Alert</p>
                <p class="text-sm text-red-700 dark:text-red-300 mt-1">
                    {{ $summary['delayed'] }} task(s) delayed, {{ $summary['at_risk'] }} task(s) at risk.
                    <a href="#alerts" class="underline hover:text-red-900">View details</a>
                </p>
            </div>
        </div>
    </div>
    @endif

    <!-- Task Progress Table -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Task Progress Details</h3>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Task</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Assignee</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Weight</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Planned %</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Actual %</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Progress</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Status</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700" id="progressTableBody">
                        @forelse($tasks as $task)
                        @php
                            $progress = $task->latestProgress;
                        @endphp
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                            <td class="px-4 py-3">
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white">{{ $task->wbs_code }}</p>
                                    <p class="text-xs text-gray-600 dark:text-gray-400">{{ $task->title }}</p>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <span class="text-gray-700 dark:text-gray-300">
                                    {{ $task->assignee?->name ?? 'Unassigned' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="font-semibold text-gray-900 dark:text-white">
                                    {{ number_format($task->weight, 2) }}%
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="text-blue-600 dark:text-blue-400 font-medium">
                                    {{ $progress ? number_format($progress->planned_percentage, 2) : '0.00' }}%
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="text-green-600 dark:text-green-400 font-medium">
                                    {{ $progress ? number_format($progress->actual_percentage, 2) : '0.00' }}%
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <div class="flex-1 bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                        <div class="bg-blue-600 h-2 rounded-full"
                                             style="width: {{ $progress ? $progress->progress_percentage : 0 }}%"></div>
                                    </div>
                                    <span class="text-xs font-medium text-gray-700 dark:text-gray-300 w-12 text-right">
                                        {{ $progress ? number_format($progress->progress_percentage, 0) : 0 }}%
                                    </span>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-center">
                                @if($progress)
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full
                                        @if($progress->status === 'completed') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400
                                        @elseif($progress->status === 'on-track') bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400
                                        @elseif($progress->status === 'at-risk') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400
                                        @elseif($progress->status === 'delayed') bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400
                                        @endif">
                                        {{ ucfirst($progress->status) }}
                                    </span>
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                        Not Started
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center">
                                <button onclick="openProgressModal({{ $task->id }}, '{{ $task->wbs_code }}', '{{ $task->title }}')"
                                        class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                    <i class="fas fa-edit"></i> Update
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                No tasks found for this week
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Weekly Plan Modal -->
<div id="weeklyPlanModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50" style="display: none;">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-4xl w-full mx-4 max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <div class="flex justify-between items-center">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white">Weekly Plan</h3>
                <button onclick="closeWeeklyPlanModal()" class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
        </div>

        <form id="weeklyPlanForm" class="p-6 space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Objectives</label>
                <textarea name="objectives" rows="3"
                          class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                          placeholder="Main objectives for this week...">{{ $weeklyPlan->objectives ?? '' }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Key Activities</label>
                <textarea name="key_activities" rows="4"
                          class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                          placeholder="Key activities planned...">{{ $weeklyPlan->key_activities ?? '' }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Total Planned Weight (%)</label>
                <input type="number" step="0.01" name="planned_weight_total"
                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                       value="{{ $weeklyPlan->planned_weight_total ?? 0 }}">
            </div>

            <div class="flex justify-end gap-2 pt-4">
                <button type="button" onclick="closeWeeklyPlanModal()"
                        class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition">
                    Cancel
                </button>
                <button type="submit"
                        class="px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white rounded-lg transition">
                    <i class="fas fa-save mr-2"></i>Save Plan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Progress Update Modal -->
<div id="progressModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50" style="display: none;">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-4xl w-full mx-4 max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white" id="progressModalTitle">Update Progress</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1" id="progressModalSubtitle"></p>
                </div>
                <button onclick="closeProgressModal()" class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
        </div>

        <form id="progressForm" class="p-6 space-y-4">
            <input type="hidden" id="progressTaskId" name="task_id">
            <input type="hidden" name="week_start_date" value="{{ $currentWeekStart->format('Y-m-d') }}">

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Overall Progress (%)</label>
                    <input type="number" step="0.01" min="0" max="100" name="progress_percentage"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                           required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Planned This Week (%)</label>
                    <input type="number" step="0.01" min="0" max="100" name="planned_percentage"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Actual Completed (%)</label>
                    <input type="number" step="0.01" min="0" max="100" name="actual_percentage"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                </div>

                <div class="flex items-end">
                    <div class="p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg w-full">
                        <p class="text-xs text-gray-600 dark:text-gray-400">Deviation</p>
                        <p class="text-lg font-bold text-blue-600" id="deviationPreview">0%</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Actual Start Date</label>
                    <input type="date" name="actual_start_date"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Actual End Date</label>
                    <input type="date" name="actual_end_date"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Notes / Progress Update</label>
                <textarea name="notes" rows="3"
                          class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                          placeholder="Describe progress made this week..."></textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Issues / Problems</label>
                <textarea name="issues" rows="2"
                          class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                          placeholder="Any problems encountered..."></textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Proposed Solutions</label>
                <textarea name="proposed_solutions" rows="2"
                          class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                          placeholder="Solutions to address problems..."></textarea>
            </div>

            <div class="flex justify-end gap-2 pt-4">
                <button type="button" onclick="closeProgressModal()"
                        class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition">
                    Cancel
                </button>
                <button type="submit"
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
                    <i class="fas fa-save mr-2"></i>Update Progress
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    let currentWeekStart = '{{ $currentWeekStart->format("Y-m-d") }}';
    const projectId = {{ $project->id }};

    // Update deviation display color
    document.addEventListener('DOMContentLoaded', function() {
        updateDeviationDisplay();
    });

    function updateDeviationDisplay() {
        const deviationEl = document.getElementById('deviationWeight');
        const deviationIconEl = document.getElementById('deviationIcon');
        const deviation = parseFloat(deviationEl.dataset.deviation);

        if (deviation > 10) {
            deviationEl.classList.add('text-red-600');
            deviationIconEl.classList.add('bg-red-100', 'dark:bg-red-900/30');
            deviationIconEl.querySelector('i').classList.add('text-red-600');
        } else if (deviation > 5) {
            deviationEl.classList.add('text-yellow-600');
            deviationIconEl.classList.add('bg-yellow-100', 'dark:bg-yellow-900/30');
            deviationIconEl.querySelector('i').classList.add('text-yellow-600');
        } else {
            deviationEl.classList.add('text-green-600');
            deviationIconEl.classList.add('bg-green-100', 'dark:bg-green-900/30');
            deviationIconEl.querySelector('i').classList.remove('fa-exclamation-triangle');
            deviationIconEl.querySelector('i').classList.add('fa-check-circle', 'text-green-600');
        }
    }

    function openWeeklyPlanModal() {
        document.getElementById('weeklyPlanModal').style.display = 'flex';
    }

    function closeWeeklyPlanModal() {
        document.getElementById('weeklyPlanModal').style.display = 'none';
    }

    async function openProgressModal(taskId, wbsCode, title) {
        document.getElementById('progressTaskId').value = taskId;
        document.getElementById('progressModalTitle').textContent = `Update Progress: ${wbsCode}`;
        document.getElementById('progressModalSubtitle').textContent = title;

        // Load existing progress data
        try {
            const response = await fetch(`/projects/${projectId}/progress/task/${taskId}?week_start=${currentWeekStart}`);
            const data = await response.json();

            if (data.success && data.progress) {
                const form = document.getElementById('progressForm');
                form.progress_percentage.value = data.progress.progress_percentage || 0;
                form.planned_percentage.value = data.progress.planned_percentage || 0;
                form.actual_percentage.value = data.progress.actual_percentage || 0;
                form.actual_start_date.value = data.progress.actual_start_date || '';
                form.actual_end_date.value = data.progress.actual_end_date || '';
                form.notes.value = data.progress.notes || '';
                form.issues.value = data.progress.issues || '';
                form.proposed_solutions.value = data.progress.proposed_solutions || '';

                updateDeviationPreview();
            }
        } catch (error) {
            console.error('Error loading progress:', error);
        }

        document.getElementById('progressModal').style.display = 'flex';
    }

    function closeProgressModal() {
        document.getElementById('progressModal').style.display = 'none';
        document.getElementById('progressForm').reset();
    }

    // Real-time deviation preview
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('progressForm');
        if (form) {
            form.planned_percentage?.addEventListener('input', updateDeviationPreview);
            form.actual_percentage?.addEventListener('input', updateDeviationPreview);
        }
    });

    function updateDeviationPreview() {
        const form = document.getElementById('progressForm');
        const planned = parseFloat(form.planned_percentage?.value || 0);
        const actual = parseFloat(form.actual_percentage?.value || 0);
        const deviation = planned - actual;

        const preview = document.getElementById('deviationPreview');
        preview.textContent = deviation.toFixed(2) + '%';

        preview.classList.remove('text-blue-600', 'text-green-600', 'text-yellow-600', 'text-red-600');
        if (deviation > 10) {
            preview.classList.add('text-red-600');
        } else if (deviation > 5) {
            preview.classList.add('text-yellow-600');
        } else if (deviation < 0) {
            preview.classList.add('text-green-600');
        } else {
            preview.classList.add('text-blue-600');
        }
    }

    // Handle weekly plan form submission
    const weeklyPlanId = {{ $weeklyPlan->id ?? 'null' }};

    document.getElementById('weeklyPlanForm')?.addEventListener('submit', async function(e) {
        e.preventDefault();
        const formData = new FormData(e.target);
        const data = Object.fromEntries(formData);

        if (!weeklyPlanId) {
            alert('⚠ Weekly plan not found. Please refresh the page.');
            return;
        }

        try {
            const response = await fetch(`/projects/${projectId}/progress/weekly-plan/${weeklyPlanId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(data)
            });

            const result = await response.json();
            if (result.success) {
                alert('✓ Weekly plan saved successfully!');
                closeWeeklyPlanModal();
            } else {
                alert('Failed to save: ' + result.message);
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Failed to save weekly plan');
        }
    });

    // Handle progress form submission
    document.getElementById('progressForm')?.addEventListener('submit', async function(e) {
        e.preventDefault();
        const formData = new FormData(e.target);
        const data = Object.fromEntries(formData);
        const taskId = document.getElementById('progressTaskId').value;

        try {
            const response = await fetch(`/projects/${projectId}/progress/task/${taskId}`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(data)
            });

            const result = await response.json();
            if (result.success) {
                if (result.deviation_alert) {
                    alert('⚠ Progress updated! Note: Task is behind schedule.');
                } else {
                    alert('✓ Progress updated successfully!');
                }
                closeProgressModal();
                window.location.reload();
            } else {
                alert('Failed to update: ' + result.message);
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Failed to update progress');
        }
    });

    function previousWeek() {
        const date = new Date(currentWeekStart);
        date.setDate(date.getDate() - 7);
        window.location.href = `?week_start=${date.toISOString().split('T')[0]}`;
    }

    function nextWeek() {
        const date = new Date(currentWeekStart);
        date.setDate(date.getDate() + 7);
        window.location.href = `?week_start=${date.toISOString().split('T')[0]}`;
    }
</script>
@endpush
@endsection
