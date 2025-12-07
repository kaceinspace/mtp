{{-- Dashboard Analytics Section --}}
<div class="space-y-6">
    {{-- Project Health Indicators --}}
    @if(isset($projectHealthData) && $projectHealthData)
    <div class="bg-gradient-to-br from-white to-gray-50 dark:from-gray-800 dark:to-gray-900 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="bg-gradient-to-r from-red-500 to-pink-500 p-6">
            <div class="flex items-center justify-between flex-wrap gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-white/20 backdrop-blur rounded-lg flex items-center justify-center">
                        <i class="fas fa-heartbeat text-white text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-white">Project Health Overview</h3>
                        <p class="text-red-100 text-sm">Real-time health monitoring across all projects</p>
                    </div>
                </div>
                <div class="flex gap-3 text-sm">
                    <div class="flex items-center gap-2 bg-white/20 backdrop-blur px-3 py-2 rounded-lg">
                        <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
                        <span class="text-white font-medium">{{ $projectHealthData['healthy'] }} Healthy</span>
                    </div>
                    <div class="flex items-center gap-2 bg-white/20 backdrop-blur px-3 py-2 rounded-lg">
                        <span class="w-2 h-2 bg-yellow-400 rounded-full animate-pulse"></span>
                        <span class="text-white font-medium">{{ $projectHealthData['at_risk'] }} At Risk</span>
                    </div>
                    <div class="flex items-center gap-2 bg-white/20 backdrop-blur px-3 py-2 rounded-lg">
                        <span class="w-2 h-2 bg-red-400 rounded-full animate-pulse"></span>
                        <span class="text-white font-medium">{{ $projectHealthData['critical'] }} Critical</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="group relative overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-green-400 to-emerald-500 opacity-10 group-hover:opacity-20 transition-opacity"></div>
                    <div class="relative text-center p-6 bg-white dark:bg-gray-800 rounded-xl border-2 border-green-500 shadow-sm hover:shadow-md transition-shadow">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-green-100 dark:bg-green-900/30 rounded-full mb-3">
                            <i class="fas fa-check-circle text-3xl text-green-600 dark:text-green-400"></i>
                        </div>
                        <div class="text-4xl font-black text-green-600 dark:text-green-400 mb-1">{{ $projectHealthData['healthy'] }}</div>
                        <div class="text-sm font-semibold text-gray-600 dark:text-gray-400">Healthy Projects</div>
                        <div class="mt-2 text-xs text-green-600 dark:text-green-400">✓ On Track</div>
                    </div>
                </div>
                <div class="group relative overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-yellow-400 to-orange-500 opacity-10 group-hover:opacity-20 transition-opacity"></div>
                    <div class="relative text-center p-6 bg-white dark:bg-gray-800 rounded-xl border-2 border-yellow-500 shadow-sm hover:shadow-md transition-shadow">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-yellow-100 dark:bg-yellow-900/30 rounded-full mb-3">
                            <i class="fas fa-exclamation-triangle text-3xl text-yellow-600 dark:text-yellow-400"></i>
                        </div>
                        <div class="text-4xl font-black text-yellow-600 dark:text-yellow-400 mb-1">{{ $projectHealthData['at_risk'] }}</div>
                        <div class="text-sm font-semibold text-gray-600 dark:text-gray-400">At Risk Projects</div>
                        <div class="mt-2 text-xs text-yellow-600 dark:text-yellow-400">⚠ Needs Attention</div>
                    </div>
                </div>
                <div class="group relative overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-red-400 to-rose-500 opacity-10 group-hover:opacity-20 transition-opacity"></div>
                    <div class="relative text-center p-6 bg-white dark:bg-gray-800 rounded-xl border-2 border-red-500 shadow-sm hover:shadow-md transition-shadow">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-red-100 dark:bg-red-900/30 rounded-full mb-3">
                            <i class="fas fa-times-circle text-3xl text-red-600 dark:text-red-400"></i>
                        </div>
                        <div class="text-4xl font-black text-red-600 dark:text-red-400 mb-1">{{ $projectHealthData['critical'] }}</div>
                        <div class="text-sm font-semibold text-gray-600 dark:text-gray-400">Critical Projects</div>
                        <div class="mt-2 text-xs text-red-600 dark:text-red-400">✗ Urgent Action Required</div>
                    </div>
                </div>
            </div>

            @if(count($projectHealthData['projects']) > 0)
            <div class="space-y-3">
                <div class="flex items-center gap-2 mb-4">
                    <div class="h-1 w-1 bg-gray-400 rounded-full"></div>
                    <h4 class="text-base font-bold text-gray-900 dark:text-white">Projects Requiring Immediate Attention</h4>
                    <div class="h-1 flex-1 bg-gradient-to-r from-gray-300 to-transparent dark:from-gray-600"></div>
                </div>
                @foreach($projectHealthData['projects'] as $item)
                <div class="group hover:scale-[1.02] transition-transform duration-200">
                    <div class="flex items-start justify-between p-4 rounded-xl border-l-4 shadow-sm hover:shadow-md transition-shadow
                        @if($item['health']['status'] === 'critical')
                            border-red-500 bg-gradient-to-r from-red-50 to-red-50/50 dark:from-red-900/20 dark:to-red-900/5
                        @elseif($item['health']['status'] === 'at-risk')
                            border-yellow-500 bg-gradient-to-r from-yellow-50 to-yellow-50/50 dark:from-yellow-900/20 dark:to-yellow-900/5
                        @else
                            border-green-500 bg-gradient-to-r from-green-50 to-green-50/50 dark:from-green-900/20 dark:to-green-900/5
                        @endif">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <span class="flex items-center justify-center w-6 h-6 rounded-full
                                    @if($item['health']['status'] === 'critical') bg-red-500
                                    @elseif($item['health']['status'] === 'at-risk') bg-yellow-500
                                    @else bg-green-500
                                    @endif">
                                    <i class="fas
                                        @if($item['health']['status'] === 'critical') fa-times
                                        @elseif($item['health']['status'] === 'at-risk') fa-exclamation
                                        @else fa-check
                                        @endif text-white text-xs"></i>
                                </span>
                                <span class="text-lg font-bold text-gray-900 dark:text-white">{{ $item['project']->title }}</span>
                                <span class="px-3 py-1 rounded-full text-xs font-bold
                                    @if($item['health']['status'] === 'critical') bg-red-500 text-white
                                    @elseif($item['health']['status'] === 'at-risk') bg-yellow-500 text-white
                                    @else bg-green-500 text-white
                                    @endif">
                                    {{ strtoupper($item['health']['status']) }}
                                </span>
                            </div>
                            @if(count($item['health']['issues']) > 0)
                            <div class="mt-3 flex flex-wrap gap-2">
                                @foreach($item['health']['issues'] as $issue)
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-600 shadow-sm">
                                    <i class="fas fa-exclamation-circle text-orange-500"></i>
                                    {{ $issue }}
                                </span>
                                @endforeach
                            </div>
                            @endif
                        </div>
                        <div class="ml-6 text-center">
                            <div class="relative inline-flex items-center justify-center w-20 h-20">
                                <svg class="w-20 h-20 transform -rotate-90">
                                    <circle cx="40" cy="40" r="36" stroke="currentColor" stroke-width="8" fill="transparent"
                                        class="text-gray-200 dark:text-gray-700" />
                                    <circle cx="40" cy="40" r="36" stroke="currentColor" stroke-width="8" fill="transparent"
                                        stroke-dasharray="{{ 2 * 3.14159 * 36 }}"
                                        stroke-dashoffset="{{ 2 * 3.14159 * 36 * (1 - $item['health']['score'] / 100) }}"
                                        class="@if($item['health']['status'] === 'critical') text-red-500
                                        @elseif($item['health']['status'] === 'at-risk') text-yellow-500
                                        @else text-green-500
                                        @endif transition-all duration-1000"
                                        stroke-linecap="round" />
                                </svg>
                                <div class="absolute inset-0 flex flex-col items-center justify-center">
                                    <span class="text-2xl font-black
                                        @if($item['health']['status'] === 'critical') text-red-600 dark:text-red-400
                                        @elseif($item['health']['status'] === 'at-risk') text-yellow-600 dark:text-yellow-400
                                        @else text-green-600 dark:text-green-400
                                        @endif">
                                        {{ $item['health']['score'] }}
                                    </span>
                                    <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Score</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>
    @endif

    {{-- Progress Trends & Predictions --}}
    @if(isset($progressTrends) && $progressTrends)
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-gradient-to-br from-white to-blue-50 dark:from-gray-800 dark:to-blue-900/20 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="bg-gradient-to-r from-blue-500 to-cyan-500 p-5">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-white/20 backdrop-blur rounded-lg flex items-center justify-center">
                            <i class="fas fa-chart-line text-white text-lg"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-white">Progress Trends</h3>
                            <p class="text-blue-100 text-xs">8-week performance analysis</p>
                        </div>
                    </div>
                    @if($progressTrends['trend'] === 'improving')
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold bg-green-500 text-white shadow-lg">
                            <i class="fas fa-arrow-up"></i> Improving
                        </span>
                    @elseif($progressTrends['trend'] === 'declining')
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold bg-red-500 text-white shadow-lg">
                            <i class="fas fa-arrow-down"></i> Declining
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold bg-gray-500 text-white shadow-lg">
                            <i class="fas fa-minus"></i> Stable
                        </span>
                    @endif
                </div>
            </div>
            <div class="p-6">
                <canvas id="progressTrendChart" class="w-full" style="height: 250px;"></canvas>
                @if($progressTrends['prediction'])
                <div class="mt-4 p-4 bg-gradient-to-r from-blue-500/10 to-cyan-500/10 dark:from-blue-500/20 dark:to-cyan-500/20 rounded-xl border-2 border-blue-500/30 dark:border-blue-500/50">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center">
                                <i class="fas fa-crystal-ball text-white"></i>
                            </div>
                            <div>
                                <span class="text-sm font-semibold text-gray-900 dark:text-white">AI Prediction</span>
                                <p class="text-xs text-gray-600 dark:text-gray-400">Next week forecast</p>
                            </div>
                        </div>
                        <span class="text-3xl font-black text-blue-600 dark:text-blue-400">{{ $progressTrends['prediction'] }}%</span>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <div class="bg-gradient-to-br from-white to-purple-50 dark:from-gray-800 dark:to-purple-900/20 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="bg-gradient-to-r from-purple-500 to-pink-500 p-5">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white/20 backdrop-blur rounded-lg flex items-center justify-center">
                        <i class="fas fa-tasks text-white text-lg"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-white">Task Completion</h3>
                        <p class="text-purple-100 text-xs">Weekly completion breakdown</p>
                    </div>
                </div>
            </div>
            <div class="p-6">
                <canvas id="taskCompletionChart" class="w-full" style="height: 250px;"></canvas>
            </div>
        </div>
    </div>
    @endif

    {{-- Team Performance & Risk Indicators --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Team Performance --}}
        @if(isset($teamPerformance) && $teamPerformance && count($teamPerformance) > 0)
        <div class="bg-gradient-to-br from-white to-indigo-50 dark:from-gray-800 dark:to-indigo-900/20 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="bg-gradient-to-r from-indigo-500 to-purple-500 p-5">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white/20 backdrop-blur rounded-lg flex items-center justify-center">
                        <i class="fas fa-users text-white text-lg"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-white">Team Performance</h3>
                        <p class="text-indigo-100 text-xs">Performance metrics by team</p>
                    </div>
                </div>
            </div>
            <div class="p-6">
                <div class="space-y-3">
                    @foreach($teamPerformance as $team)
                    <div class="group hover:scale-[1.02] transition-transform duration-200">
                        <div class="p-4 rounded-xl bg-white dark:bg-gray-800 border-2 border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-md transition-shadow">
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 bg-gradient-to-br from-indigo-500 to-purple-500 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-users text-white text-xs"></i>
                                    </div>
                                    <span class="font-bold text-gray-900 dark:text-white">{{ $team['name'] }}</span>
                                </div>
                                <span class="px-3 py-1 rounded-full text-xs font-bold shadow-sm
                                    @if($team['performance'] === 'excellent') bg-gradient-to-r from-green-500 to-emerald-500 text-white
                                    @elseif($team['performance'] === 'good') bg-gradient-to-r from-blue-500 to-cyan-500 text-white
                                    @else bg-gradient-to-r from-orange-500 to-red-500 text-white
                                    @endif">
                                    @if($team['performance'] === 'excellent')
                                        <i class="fas fa-star mr-1"></i>
                                    @elseif($team['performance'] === 'good')
                                        <i class="fas fa-thumbs-up mr-1"></i>
                                    @else
                                        <i class="fas fa-chart-line mr-1"></i>
                                    @endif
                                    {{ ucfirst(str_replace('-', ' ', $team['performance'])) }}
                                </span>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="text-center p-3 bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 rounded-lg">
                                    <div class="text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1">SPI</div>
                                    <div class="text-2xl font-black text-blue-600 dark:text-blue-400">{{ $team['spi'] }}</div>
                                </div>
                                <div class="text-center p-3 bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 rounded-lg">
                                    <div class="text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1">Completion</div>
                                    <div class="text-2xl font-black text-green-600 dark:text-green-400">{{ $team['completion_rate'] }}%</div>
                                </div>
                            </div>
                            <div class="mt-3 pt-3 border-t-2 border-gray-200 dark:border-gray-700 flex justify-between">
                                <span class="text-sm font-medium text-gray-600 dark:text-gray-400">
                                    <i class="fas fa-project-diagram text-indigo-500 mr-1"></i>
                                    {{ $team['projects'] }} Projects
                                </span>
                                <span class="text-sm font-medium text-gray-600 dark:text-gray-400">
                                    <i class="fas fa-tasks text-purple-500 mr-1"></i>
                                    {{ $team['tasks'] }} Tasks
                                </span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        {{-- Risk Indicators --}}
        @if(isset($riskIndicators) && $riskIndicators)
        <div class="bg-gradient-to-br from-white to-orange-50 dark:from-gray-800 dark:to-orange-900/20 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="bg-gradient-to-r from-orange-500 to-red-500 p-5">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white/20 backdrop-blur rounded-lg flex items-center justify-center">
                        <i class="fas fa-exclamation-triangle text-white text-lg"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-white">Risk Indicators</h3>
                        <p class="text-orange-100 text-xs">Real-time risk monitoring</p>
                    </div>
                </div>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-3 gap-3 mb-6">
                    <div class="group hover:scale-105 transition-transform">
                        <div class="text-center p-4 bg-gradient-to-br from-red-50 to-red-100 dark:from-red-900/20 dark:to-red-800/20 rounded-xl border-2 border-red-500">
                            <div class="inline-flex items-center justify-center w-12 h-12 bg-red-500 rounded-full mb-2">
                                <i class="fas fa-fire text-white"></i>
                            </div>
                            <div class="text-3xl font-black text-red-600 dark:text-red-400">{{ $riskIndicators['high_count'] }}</div>
                            <div class="text-xs font-bold text-gray-700 dark:text-gray-300 mt-1">High Risk</div>
                        </div>
                    </div>
                    <div class="group hover:scale-105 transition-transform">
                        <div class="text-center p-4 bg-gradient-to-br from-yellow-50 to-yellow-100 dark:from-yellow-900/20 dark:to-yellow-800/20 rounded-xl border-2 border-yellow-500">
                            <div class="inline-flex items-center justify-center w-12 h-12 bg-yellow-500 rounded-full mb-2">
                                <i class="fas fa-exclamation text-white"></i>
                            </div>
                            <div class="text-3xl font-black text-yellow-600 dark:text-yellow-400">{{ $riskIndicators['medium_count'] }}</div>
                            <div class="text-xs font-bold text-gray-700 dark:text-gray-300 mt-1">Medium Risk</div>
                        </div>
                    </div>
                    <div class="group hover:scale-105 transition-transform">
                        <div class="text-center p-4 bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 rounded-xl border-2 border-green-500">
                            <div class="inline-flex items-center justify-center w-12 h-12 bg-green-500 rounded-full mb-2">
                                <i class="fas fa-check text-white"></i>
                            </div>
                            <div class="text-3xl font-black text-green-600 dark:text-green-400">{{ $riskIndicators['low_count'] }}</div>
                            <div class="text-xs font-bold text-gray-700 dark:text-gray-300 mt-1">Low Risk</div>
                        </div>
                    </div>
                </div>

                <div class="space-y-3 max-h-[400px] overflow-y-auto custom-scrollbar">
                    @if(count($riskIndicators['high_risks']) > 0)
                    <div class="flex items-center gap-2 mb-2">
                        <div class="h-1 w-1 bg-red-500 rounded-full"></div>
                        <h4 class="text-sm font-bold text-red-600 dark:text-red-400">High Risk Projects</h4>
                        <div class="h-px flex-1 bg-red-300"></div>
                    </div>
                    @foreach($riskIndicators['high_risks'] as $item)
                    <div class="group hover:scale-[1.02] transition-transform duration-200">
                        <div class="p-4 rounded-xl border-l-4 border-red-500 bg-gradient-to-r from-red-50 to-red-50/50 dark:from-red-900/20 dark:to-red-900/5 shadow-sm hover:shadow-md transition-shadow">
                            <div class="flex items-center gap-2 mb-2">
                                <div class="w-6 h-6 bg-red-500 rounded-full flex items-center justify-center">
                                    <i class="fas fa-exclamation text-white text-xs"></i>
                                </div>
                                <span class="font-bold text-gray-900 dark:text-white">{{ $item['project']->title }}</span>
                            </div>
                            <div class="flex flex-wrap gap-1.5">
                                @foreach($item['risk']['factors'] as $factor)
                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded-lg text-xs font-medium bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 border border-red-200 dark:border-red-800">
                                    <i class="fas fa-warning text-red-500 text-xs"></i>
                                    {{ $factor }}
                                </span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endforeach
                    @else
                    <div class="text-center py-6">
                        <i class="fas fa-check-circle text-4xl text-green-500 mb-2"></i>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">No high risk projects</p>
                    </div>
                    @endif

                    @if(count($riskIndicators['medium_risks']) > 0)
                    <div class="flex items-center gap-2 mb-2 mt-4">
                        <div class="h-1 w-1 bg-yellow-500 rounded-full"></div>
                        <h4 class="text-sm font-bold text-yellow-600 dark:text-yellow-400">Medium Risk Projects</h4>
                        <div class="h-px flex-1 bg-yellow-300"></div>
                    </div>
                    @foreach($riskIndicators['medium_risks'] as $item)
                    <div class="group hover:scale-[1.02] transition-transform duration-200">
                        <div class="p-3 rounded-xl border-l-4 border-yellow-500 bg-gradient-to-r from-yellow-50 to-yellow-50/50 dark:from-yellow-900/20 dark:to-yellow-900/5 shadow-sm hover:shadow-md transition-shadow">
                            <div class="flex items-center gap-2 mb-1">
                                <div class="w-5 h-5 bg-yellow-500 rounded-full flex items-center justify-center">
                                    <i class="fas fa-minus text-white text-xs"></i>
                                </div>
                                <span class="font-semibold text-gray-900 dark:text-white text-sm">{{ $item['project']->title }}</span>
                            </div>
                            <div class="text-xs text-gray-600 dark:text-gray-400 ml-7">
                                {{ implode(', ', $item['risk']['factors']) }}
                            </div>
                        </div>
                    </div>
                    @endforeach
                    @endif
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

@push('styles')
<style>
    /* Custom Scrollbar */
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: rgba(0, 0, 0, 0.05);
        border-radius: 10px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: linear-gradient(to bottom, #f59e0b, #ef4444);
        border-radius: 10px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(to bottom, #d97706, #dc2626);
    }

    /* Smooth animations */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .space-y-6 > * {
        animation: fadeInUp 0.5s ease-out backwards;
    }

    .space-y-6 > *:nth-child(1) { animation-delay: 0.1s; }
    .space-y-6 > *:nth-child(2) { animation-delay: 0.2s; }
    .space-y-6 > *:nth-child(3) { animation-delay: 0.3s; }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Check if dark mode is enabled
    const isDarkMode = document.documentElement.classList.contains('dark');
    const gridColor = isDarkMode ? '#374151' : '#e5e7eb';
    const textColor = isDarkMode ? '#9ca3af' : '#6b7280';

    // Chart.js default colors
    Chart.defaults.color = textColor;
    Chart.defaults.borderColor = gridColor;

    @if(isset($progressTrends) && isset($progressTrends['weeks']) && count($progressTrends['weeks']) > 0)
    // Progress Trends Chart
    const progressCtx = document.getElementById('progressTrendChart');
    if (progressCtx) {
        new Chart(progressCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($progressTrends['weeks']) !!},
                datasets: [{
                    label: 'Completion Rate',
                    data: {!! json_encode($progressTrends['completion_rates']) !!},
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.4,
                    fill: true,
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: isDarkMode ? '#1f2937' : '#ffffff',
                        titleColor: isDarkMode ? '#f3f4f6' : '#111827',
                        bodyColor: isDarkMode ? '#d1d5db' : '#374151',
                        borderColor: gridColor,
                        borderWidth: 1,
                        callbacks: {
                            label: function(context) {
                                return 'Completion: ' + context.parsed.y.toFixed(1) + '%';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100,
                        ticks: {
                            callback: function(value) {
                                return value + '%';
                            }
                        },
                        grid: {
                            color: gridColor
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    }

    // Task Completion Trends Chart
    const taskCtx = document.getElementById('taskCompletionChart');
    if (taskCtx) {
        new Chart(taskCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($progressTrends['weeks']) !!},
                datasets: [{
                    label: 'Completed Tasks',
                    data: {!! json_encode(array_map(function($tasks) {
                        return $tasks['completed'] ?? 0;
                    }, $progressTrends['tasks'])) !!},
                    backgroundColor: '#10b981',
                    borderRadius: 6
                }, {
                    label: 'Pending Tasks',
                    data: {!! json_encode(array_map(function($tasks) {
                        return ($tasks['total'] ?? 0) - ($tasks['completed'] ?? 0);
                    }, $progressTrends['tasks'])) !!},
                    backgroundColor: '#f59e0b',
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 15,
                            usePointStyle: true
                        }
                    },
                    tooltip: {
                        backgroundColor: isDarkMode ? '#1f2937' : '#ffffff',
                        titleColor: isDarkMode ? '#f3f4f6' : '#111827',
                        bodyColor: isDarkMode ? '#d1d5db' : '#374151',
                        borderColor: gridColor,
                        borderWidth: 1
                    }
                },
                scales: {
                    x: {
                        stacked: true,
                        grid: {
                            display: false
                        }
                    },
                    y: {
                        stacked: true,
                        beginAtZero: true,
                        grid: {
                            color: gridColor
                        }
                    }
                }
            }
        });
    }
    @endif
});
</script>
@endpush
