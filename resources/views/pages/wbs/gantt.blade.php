@extends('layouts.dashboard')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">
                <i class="fas fa-chart-gantt mr-2"></i>{{ __('phase3_4.gantt_chart_view') }}
            </h1>
            <p class="text-gray-600 dark:text-gray-400">{{ $project->name }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('projects.wbs', $project) }}"
               class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition">
                <i class="fas fa-arrow-left mr-2"></i>{{ __('phase3_4.back_to_wbs') }}
            </a>
            <a href="{{ route('projects.wbs', $project) }}"
               class="px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white rounded-lg transition">
                <i class="fas fa-calendar-alt mr-2"></i>{{ __('messages.calendar_settings') }}
            </a>
        </div>
    </div>

    <!-- Info Panel -->
    <div class="mb-4 bg-teal-50 dark:bg-teal-900/20 border border-teal-200 dark:border-teal-800 rounded-lg p-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div>
                    <span class="text-sm text-gray-600 dark:text-gray-400">{{ __('phase3_4.project_duration') }}:</span>
                    <span class="ml-2 font-semibold text-gray-900 dark:text-white" id="projectDuration">{{ __('phase3_4.calculating') }}...</span>
                </div>
                <div>
                    <span class="text-sm text-gray-600 dark:text-gray-400">{{ __('phase3_4.working_days') }}:</span>
                    <span class="ml-2 font-semibold text-teal-600" id="workingDaysInfo">{{ __('phase3_4.calculating') }}...</span>
                </div>
            </div>
            <a href="{{ route('projects.wbs', $project) }}#calendar" class="text-sm text-teal-600 hover:text-teal-700">
                <i class="fas fa-cog mr-1"></i>{{ __('phase3_4.configure_calendar') }}
            </a>
        </div>
    </div>

    <!-- View Mode Selector -->
    <div class="mb-4 flex gap-2">
        <button onclick="changeViewMode('Day')" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
            {{ __('phase3_4.day') }}
        </button>
        <button onclick="changeViewMode('Week')" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
            {{ __('phase3_4.week') }}
        </button>
        <button onclick="changeViewMode('Month')" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
            {{ __('phase3_4.month') }}
        </button>
        <button onclick="changeViewMode('Year')" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
            {{ __('phase3_4.year') }}
        </button>
    </div>

    <!-- Critical Path Toggle -->
    <div class="mb-4">
        <label class="inline-flex items-center cursor-pointer">
            <input type="checkbox" id="highlightCriticalPath" class="form-checkbox h-5 w-5 text-red-600" checked>
            <span class="ml-2 text-gray-700 dark:text-gray-300">{{ __('phase3_4.highlight_critical_path') }}</span>
        </label>
    </div>

    <!-- Gantt Chart Container -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
        <div id="gantt"></div>
    </div>

    <!-- Legend -->
    <div class="mt-4 bg-white dark:bg-gray-800 rounded-lg shadow-lg p-4">
        <h3 class="font-bold text-gray-900 dark:text-white mb-2">{{ __('phase3_4.legend') }}:</h3>
        <div class="flex gap-4 flex-wrap">
            <div class="flex items-center">
                <div class="w-4 h-4 bg-blue-500 rounded mr-2"></div>
                <span class="text-sm text-gray-700 dark:text-gray-300">{{ __('phase3_4.regular_task') }}</span>
            </div>
            <div class="flex items-center">
                <div class="w-4 h-4 bg-red-500 rounded mr-2"></div>
                <span class="text-sm text-gray-700 dark:text-gray-300">{{ __('phase3_4.critical_path') }}</span>
            </div>
            <div class="flex items-center">
                <div class="w-4 h-4 bg-green-500 rounded mr-2"></div>
                <span class="text-sm text-gray-700 dark:text-gray-300">{{ __('messages.completed') }}</span>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/frappe-gantt@0.6.1/dist/frappe-gantt.css">
<script src="https://cdn.jsdelivr.net/npm/frappe-gantt@0.6.1/dist/frappe-gantt.min.js"></script>

<script>
    const tasks = @json($ganttTasks);
    const criticalPathTaskIds = @json($criticalPathIds ?? []);
    let ganttChart;

    function initGantt() {
        // Check if Gantt library is loaded
        if (typeof Gantt === 'undefined') {
            console.error('Frappe Gantt library not loaded');
            document.getElementById('gantt').innerHTML = '<p class="text-red-500 text-center py-8">Error: Gantt chart library failed to load. Please refresh the page.</p>';
            return;
        }

        // Prepare tasks for Frappe Gantt
        const ganttData = tasks.map(task => {
            const isCritical = criticalPathTaskIds.includes(task.id);

            return {
                id: task.wbs_code,
                name: task.title,
                start: task.start_date || new Date().toISOString().split('T')[0],
                end: task.end_date || new Date(Date.now() + 7 * 24 * 60 * 60 * 1000).toISOString().split('T')[0],
                progress: task.status === 'completed' ? 100 : (task.status === 'in-progress' ? 50 : 0),
                dependencies: task.dependencies || '',
                custom_class: isCritical && document.getElementById('highlightCriticalPath').checked ? 'critical-path' : ''
            };
        });

        try {
            ganttChart = new Gantt("#gantt", ganttData, {
                view_mode: 'Week',
                bar_height: 30,
                bar_corner_radius: 3,
                arrow_curve: 5,
                padding: 18,
                date_format: 'YYYY-MM-DD',
                custom_popup_html: function(task) {
                    return `
                        <div class="details-container">
                            <h5>${task.name}</h5>
                            <p>Progress: ${task.progress}%</p>
                            <p>${task.start.toDateString()} - ${task.end.toDateString()}</p>
                        </div>
                    `;
                }
            });
        } catch (error) {
            console.error('Error initializing Gantt chart:', error);
            document.getElementById('gantt').innerHTML = '<p class="text-red-500 text-center py-8">Error: ' + error.message + '</p>';
        }
    }

    function changeViewMode(mode) {
        if (ganttChart) {
            ganttChart.change_view_mode(mode);
        }
    }

    document.getElementById('highlightCriticalPath').addEventListener('change', function() {
        initGantt(); // Reinitialize to apply critical path highlighting
    });

    // Initialize Gantt chart with delay to ensure library loads
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(initGantt, 100);
        });
    } else {
        setTimeout(initGantt, 100);
    }
</script>

<style>
    .gantt .bar-wrapper .critical-path .bar {
        fill: #ef4444 !important;
    }

    .gantt .bar-wrapper .bar-progress {
        fill: #10b981;
    }
</style>
@endpush
@endsection
