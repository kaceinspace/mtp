@extends('layouts.dashboard')

@section('title', __('phase3_4.scurve') . ' & ' . __('phase3_4.dashboard_analytics') . ' - ' . $project->title)

@section('content')
<div class="container mx-auto px-4 py-6 max-w-7xl">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ __('phase3_4.scurve') }} & {{ __('phase3_4.dashboard_analytics') }}</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">
                    <a href="{{ route('projects.show', $project) }}" class="hover:text-blue-600">{{ $project->title }}</a>
                    <span class="mx-2">•</span>
                    {{ __('phase3_4.performance_analysis_forecasting') }}
                </p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('projects.progress.index', $project) }}"
                   class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition">
                    <i class="fas fa-arrow-left mr-2"></i>{{ __('phase3_4.back_to_progress') }}
                </a>
            </div>
        </div>
    </div>

    <!-- Performance Metrics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <!-- SPI Card -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border-l-4
            {{ $performanceMetrics['spi'] >= 1 ? 'border-green-500' : ($performanceMetrics['spi'] >= 0.8 ? 'border-yellow-500' : 'border-red-500') }}">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ __('phase3_4.spi') }}</p>
                    <p class="text-3xl font-bold mt-2
                        {{ $performanceMetrics['spi'] >= 1 ? 'text-green-600' : ($performanceMetrics['spi'] >= 0.8 ? 'text-yellow-600' : 'text-red-600') }}">
                        {{ $performanceMetrics['spi'] }}
                    </p>
                    <p class="text-xs text-gray-500 mt-1">
                        @if($performanceMetrics['spi'] >= 1)
                            <i class="fas fa-check-circle text-green-500"></i> {{ __('phase3_4.on_schedule') }}
                        @elseif($performanceMetrics['spi'] >= 0.8)
                            <i class="fas fa-exclamation-triangle text-yellow-500"></i> {{ __('phase3_4.minor_delay') }}
                        @else
                            <i class="fas fa-times-circle text-red-500"></i> {{ __('phase3_4.major_delay') }}
                        @endif
                    </p>
                </div>
                <div class="text-4xl opacity-20">
                    <i class="fas fa-chart-line"></i>
                </div>
            </div>
        </div>

        <!-- CPI Card -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border-l-4
            {{ $performanceMetrics['cpi'] >= 1 ? 'border-blue-500' : ($performanceMetrics['cpi'] >= 0.8 ? 'border-orange-500' : 'border-red-500') }}">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ __('phase3_4.cpi') }}</p>
                    <p class="text-3xl font-bold mt-2
                        {{ $performanceMetrics['cpi'] >= 1 ? 'text-blue-600' : ($performanceMetrics['cpi'] >= 0.8 ? 'text-orange-600' : 'text-red-600') }}">
                        {{ $performanceMetrics['cpi'] }}
                    </p>
                    <p class="text-xs text-gray-500 mt-1">
                        @if($performanceMetrics['cpi'] >= 1)
                            <i class="fas fa-check-circle text-blue-500"></i> {{ __('phase3_4.under_budget') }}
                        @elseif($performanceMetrics['cpi'] >= 0.8)
                            <i class="fas fa-exclamation-triangle text-orange-500"></i> {{ __('phase3_4.minor_overrun') }}
                        @else
                            <i class="fas fa-times-circle text-red-500"></i> {{ __('phase3_4.major_overrun') }}
                        @endif
                    </p>
                </div>
                <div class="text-4xl opacity-20">
                    <i class="fas fa-dollar-sign"></i>
                </div>
            </div>
        </div>

        <!-- Completion Card -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ __('phase3_4.project_completion') }}</p>
                    <p class="text-3xl font-bold text-purple-600 mt-2">{{ $performanceMetrics['completion'] }}%</p>
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2 mt-2">
                        <div class="bg-purple-600 h-2 rounded-full transition-all duration-500"
                             style="width: {{ $performanceMetrics['completion'] }}%"></div>
                    </div>
                </div>
                <div class="text-4xl opacity-20">
                    <i class="fas fa-tasks"></i>
                </div>
            </div>
        </div>

        <!-- Risk Level Card -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border-l-4
            {{ $forecast['risk_level'] === 'low' ? 'border-green-500' : ($forecast['risk_level'] === 'medium' ? 'border-yellow-500' : 'border-red-500') }}">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ __('phase3_4.risk_level') }}</p>
                    <p class="text-2xl font-bold mt-2 capitalize
                        {{ $forecast['risk_level'] === 'low' ? 'text-green-600' : ($forecast['risk_level'] === 'medium' ? 'text-yellow-600' : 'text-red-600') }}">
                        {{ __('phase3_4.' . $forecast['risk_level'] . '_risk') }}
                    </p>
                    <p class="text-xs text-gray-500 mt-1">
                        {{ __('phase3_4.on_time_probability') }}: {{ $forecast['on_time_probability'] }}%
                    </p>
                </div>
                <div class="text-4xl opacity-20">
                    <i class="fas fa-{{ $forecast['risk_level'] === 'low' ? 'shield-alt' : ($forecast['risk_level'] === 'medium' ? 'exclamation-triangle' : 'exclamation-circle') }}"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- S-Curve Chart -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">
                <i class="fas fa-chart-area text-blue-500 mr-2"></i>{{ __('phase3_4.scurve_analysis') }} ({{ __('phase3_4.planned_vs_actual') }})
            </h2>
            <div class="flex gap-4 text-sm">
                <div class="flex items-center">
                    <span class="w-3 h-3 bg-blue-500 rounded-full mr-2"></span>
                    <span class="text-gray-600 dark:text-gray-400">{{ __('phase3_4.planned') }}</span>
                </div>
                <div class="flex items-center">
                    <span class="w-3 h-3 bg-green-500 rounded-full mr-2"></span>
                    <span class="text-gray-600 dark:text-gray-400">{{ __('phase3_4.actual') }}</span>
                </div>
            </div>
        </div>
        <div class="h-96">
            <canvas id="sCurveChart"></canvas>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- SPI Trend Chart -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">
                <i class="fas fa-chart-line text-purple-500 mr-2"></i>{{ __('phase3_4.spi_trend_over_time') }}
            </h2>
            <div class="h-64">
                <canvas id="spiTrendChart"></canvas>
            </div>
        </div>

        <!-- Completion Trend Chart -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">
                <i class="fas fa-percentage text-indigo-500 mr-2"></i>{{ __('phase3_4.weekly_completion_rate') }}
            </h2>
            <div class="h-64">
                <canvas id="completionTrendChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Earned Value Management Details -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- EVM Metrics -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">
                <i class="fas fa-calculator text-blue-500 mr-2"></i>{{ __('phase3_4.earned_value_management') }}
            </h2>
            <div class="space-y-4">
                <div class="flex justify-between items-center border-b border-gray-200 dark:border-gray-700 pb-3">
                    <span class="text-gray-600 dark:text-gray-400">{{ __('phase3_4.pv') }}</span>
                    <span class="font-semibold text-gray-900 dark:text-white">{{ number_format($performanceMetrics['pv'], 2) }}%</span>
                </div>
                <div class="flex justify-between items-center border-b border-gray-200 dark:border-gray-700 pb-3">
                    <span class="text-gray-600 dark:text-gray-400">{{ __('phase3_4.ev') }}</span>
                    <span class="font-semibold text-gray-900 dark:text-white">{{ number_format($performanceMetrics['ev'], 2) }}%</span>
                </div>
                <div class="flex justify-between items-center border-b border-gray-200 dark:border-gray-700 pb-3">
                    <span class="text-gray-600 dark:text-gray-400">{{ __('phase3_4.ac') }}</span>
                    <span class="font-semibold text-gray-900 dark:text-white">{{ number_format($performanceMetrics['ac'], 2) }}%</span>
                </div>
                <div class="flex justify-between items-center border-b border-gray-200 dark:border-gray-700 pb-3">
                    <span class="text-gray-600 dark:text-gray-400">{{ __('phase3_4.bac') }}</span>
                    <span class="font-semibold text-gray-900 dark:text-white">{{ number_format($performanceMetrics['bac'], 2) }}%</span>
                </div>
                <div class="flex justify-between items-center border-b border-gray-200 dark:border-gray-700 pb-3">
                    <span class="text-gray-600 dark:text-gray-400">{{ __('phase3_4.sv') }}</span>
                    <span class="font-semibold {{ $performanceMetrics['sv'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                        {{ $performanceMetrics['sv'] > 0 ? '+' : '' }}{{ number_format($performanceMetrics['sv'], 2) }}%
                    </span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600 dark:text-gray-400">{{ __('phase3_4.cv') }}</span>
                    <span class="font-semibold {{ $performanceMetrics['cv'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                        {{ $performanceMetrics['cv'] > 0 ? '+' : '' }}{{ number_format($performanceMetrics['cv'], 2) }}%
                    </span>
                </div>
            </div>
        </div>

        <!-- Forecast & Projections -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">
                <i class="fas fa-crystal-ball text-purple-500 mr-2"></i>{{ __('phase3_4.forecast_projections') }}
            </h2>
            <div class="space-y-4">
                <div class="flex justify-between items-center border-b border-gray-200 dark:border-gray-700 pb-3">
                    <span class="text-gray-600 dark:text-gray-400">{{ __('phase3_4.eac') }}</span>
                    <span class="font-semibold text-gray-900 dark:text-white">{{ number_format($performanceMetrics['eac'], 2) }}%</span>
                </div>
                <div class="flex justify-between items-center border-b border-gray-200 dark:border-gray-700 pb-3">
                    <span class="text-gray-600 dark:text-gray-400">{{ __('phase3_4.etc') }}</span>
                    <span class="font-semibold text-gray-900 dark:text-white">{{ number_format($performanceMetrics['etc'], 2) }}%</span>
                </div>
                <div class="flex justify-between items-center border-b border-gray-200 dark:border-gray-700 pb-3">
                    <span class="text-gray-600 dark:text-gray-400">{{ __('phase3_4.vac') }}</span>
                    <span class="font-semibold {{ $performanceMetrics['vac'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                        {{ $performanceMetrics['vac'] > 0 ? '+' : '' }}{{ number_format($performanceMetrics['vac'], 2) }}%
                    </span>
                </div>
                @if($forecast['forecast_completion_date'])
                <div class="flex justify-between items-center border-b border-gray-200 dark:border-gray-700 pb-3">
                    <span class="text-gray-600 dark:text-gray-400">{{ __('phase3_4.forecast_completion_date') }}</span>
                    <span class="font-semibold text-gray-900 dark:text-white">
                        {{ \Carbon\Carbon::parse($forecast['forecast_completion_date'])->format('M d, Y') }}
                    </span>
                </div>
                @endif
                @if($forecast['forecast_delay_days'] !== null)
                <div class="flex justify-between items-center border-b border-gray-200 dark:border-gray-700 pb-3">
                    <span class="text-gray-600 dark:text-gray-400">{{ __('phase3_4.expected_delay_advance') }}</span>
                    <span class="font-semibold {{ $forecast['forecast_delay_days'] >= 0 ? 'text-red-600' : 'text-green-600' }}">
                        {{ abs($forecast['forecast_delay_days']) }} {{ __('phase3_4.days') }} {{ $forecast['forecast_delay_days'] >= 0 ? __('phase3_4.delay') : __('phase3_4.ahead') }}
                    </span>
                </div>
                @endif
                <div class="flex justify-between items-center">
                    <span class="text-gray-600 dark:text-gray-400">{{ __('phase3_4.remaining_work') }}</span>
                    <span class="font-semibold text-gray-900 dark:text-white">{{ number_format($forecast['remaining_work_percentage'], 2) }}%</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Deviation Trend Chart -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
        <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">
            <i class="fas fa-wave-square text-orange-500 mr-2"></i>{{ __('phase3_4.weekly_deviation_trend') }}
        </h2>
        <div class="h-64">
            <canvas id="deviationTrendChart"></canvas>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Chart.js configuration
    Chart.defaults.color = document.documentElement.classList.contains('dark') ? '#9ca3af' : '#4b5563';
    Chart.defaults.borderColor = document.documentElement.classList.contains('dark') ? '#374151' : '#e5e7eb';

    // S-Curve Chart
    const sCurveCtx = document.getElementById('sCurveChart').getContext('2d');
    const sCurveChart = new Chart(sCurveCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($sCurveData['labels']) !!}.map(date => {
                const d = new Date(date);
                return d.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
            }),
            datasets: [
                {
                    label: 'Planned',
                    data: {!! json_encode($sCurveData['planned']) !!},
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4
                },
                {
                    label: 'Actual',
                    data: {!! json_encode($sCurveData['actual']) !!},
                    borderColor: 'rgb(34, 197, 94)',
                    backgroundColor: 'rgba(34, 197, 94, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': ' + context.parsed.y.toFixed(2) + '%';
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Cumulative Progress (%)'
                    },
                    ticks: {
                        callback: function(value) {
                            return value + '%';
                        }
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Week'
                    }
                }
            },
            interaction: {
                mode: 'nearest',
                axis: 'x',
                intersect: false
            }
        }
    });

    // SPI Trend Chart
    const spiTrendCtx = document.getElementById('spiTrendChart').getContext('2d');
    const spiTrendChart = new Chart(spiTrendCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($trendData['labels']) !!},
            datasets: [{
                label: 'SPI',
                data: {!! json_encode($trendData['spi']) !!},
                borderColor: 'rgb(168, 85, 247)',
                backgroundColor: 'rgba(168, 85, 247, 0.1)',
                borderWidth: 2,
                fill: true,
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                annotation: {
                    annotations: {
                        line1: {
                            type: 'line',
                            yMin: 1,
                            yMax: 1,
                            borderColor: 'rgb(34, 197, 94)',
                            borderWidth: 2,
                            borderDash: [5, 5],
                            label: {
                                content: 'Target (1.0)',
                                enabled: true
                            }
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'SPI Value'
                    }
                }
            }
        }
    });

    // Completion Trend Chart
    const completionTrendCtx = document.getElementById('completionTrendChart').getContext('2d');
    const completionTrendChart = new Chart(completionTrendCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($trendData['labels']) !!},
            datasets: [{
                label: 'Completion Rate',
                data: {!! json_encode($trendData['completion']) !!},
                backgroundColor: 'rgba(99, 102, 241, 0.7)',
                borderColor: 'rgb(99, 102, 241)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100,
                    title: {
                        display: true,
                        text: 'Completion (%)'
                    },
                    ticks: {
                        callback: function(value) {
                            return value + '%';
                        }
                    }
                }
            }
        }
    });

    // Deviation Trend Chart
    const deviationTrendCtx = document.getElementById('deviationTrendChart').getContext('2d');
    const deviationTrendChart = new Chart(deviationTrendCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($trendData['labels']) !!},
            datasets: [{
                label: 'Deviation',
                data: {!! json_encode($trendData['deviation']) !!},
                backgroundColor: function(context) {
                    const value = context.parsed.y;
                    return value >= 0 ? 'rgba(34, 197, 94, 0.7)' : 'rgba(239, 68, 68, 0.7)';
                },
                borderColor: function(context) {
                    const value = context.parsed.y;
                    return value >= 0 ? 'rgb(34, 197, 94)' : 'rgb(239, 68, 68)';
                },
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    title: {
                        display: true,
                        text: 'Deviation (%)'
                    },
                    ticks: {
                        callback: function(value) {
                            return value + '%';
                        }
                    }
                }
            }
        }
    });
</script>
@endpush
@endsection
