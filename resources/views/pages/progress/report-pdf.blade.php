<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Weekly Progress Report - {{ $project->title }}</title>
    <style>
        @page {
            margin: 15mm 20mm;
            @bottom-right {
                content: "Page " counter(page) " of " counter(pages);
                font-size: 9pt;
                color: #718096;
            }
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 9pt;
            line-height: 1.5;
            color: #2d3748;
        }

        /* Header Styling */
        .header {
            text-align: center;
            margin-bottom: 25px;
            padding: 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 8px;
            color: white;
        }

        .header h1 {
            margin: 0 0 10px 0;
            font-size: 22pt;
            font-weight: bold;
            letter-spacing: -0.5px;
        }

        .header .project-info {
            font-size: 10pt;
            opacity: 0.95;
            line-height: 1.6;
        }

        .header .project-info strong {
            font-weight: 600;
        }

        /* Section Styling */
        .section {
            margin-bottom: 25px;
            page-break-inside: avoid;
        }

        .section-title {
            background: linear-gradient(90deg, #4a5568 0%, #718096 100%);
            color: white;
            padding: 10px 15px;
            font-size: 12pt;
            font-weight: bold;
            margin-bottom: 12px;
            border-radius: 4px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        /* Summary Cards Grid */
        .summary-grid {
            display: table;
            width: 100%;
            margin-bottom: 20px;
            border-spacing: 8px;
        }

        .summary-row {
            display: table-row;
        }

        .summary-cell {
            display: table-cell;
            width: 25%;
            padding: 15px;
            text-align: center;
            border-radius: 6px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.12);
        }

        .summary-cell.completed {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            border: 2px solid #28a745;
        }

        .summary-cell.planned {
            background: linear-gradient(135deg, #cfe2ff 0%, #b6d4fe 100%);
            border: 2px solid #0d6efd;
        }

        .summary-cell.actual {
            background: linear-gradient(135deg, #fff3cd 0%, #ffe69c 100%);
            border: 2px solid #ffc107;
        }

        .summary-cell.deviation {
            background: linear-gradient(135deg, #f8d7da 0%, #f1aeb5 100%);
            border: 2px solid #dc3545;
        }

        .summary-cell .label {
            font-size: 8pt;
            color: #495057;
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
        }

        .summary-cell .value {
            font-size: 20pt;
            font-weight: bold;
            color: #212529;
            margin: 8px 0;
        }

        .summary-cell .sublabel {
            font-size: 7pt;
            color: #6c757d;
            margin-top: 4px;
        }

        /* Status Grid Styling */
        .status-grid {
            display: table;
            width: 100%;
            margin-bottom: 15px;
            border-spacing: 6px;
        }

        .status-row {
            display: table-row;
        }

        .status-cell {
            display: table-cell;
            width: 25%;
            padding: 12px;
            text-align: center;
            border-radius: 6px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        .status-completed {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            border: 2px solid #28a745;
        }

        .status-ontrack {
            background: linear-gradient(135deg, #cfe2ff 0%, #b6d4fe 100%);
            border: 2px solid #0d6efd;
        }

        .status-atrisk {
            background: linear-gradient(135deg, #fff3cd 0%, #ffe69c 100%);
            border: 2px solid #ffc107;
        }

        .status-delayed {
            background: linear-gradient(135deg, #f8d7da 0%, #f1aeb5 100%);
            border: 2px solid #dc3545;
        }

        .status-cell .count {
            font-size: 22pt;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .status-completed .count { color: #155724; }
        .status-ontrack .count { color: #084298; }
        .status-atrisk .count { color: #997404; }
        .status-delayed .count { color: #721c24; }

        .status-cell .label {
            font-size: 9pt;
            font-weight: 600;
            margin-top: 5px;
            color: #495057;
        }

        /* Table Styling */
        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin-bottom: 15px;
            font-size: 8.5pt;
            border-radius: 6px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        table th {
            background: linear-gradient(180deg, #4a5568 0%, #2d3748 100%);
            color: white;
            border: none;
            padding: 10px 8px;
            text-align: left;
            font-weight: 600;
            font-size: 9pt;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        table td {
            border-bottom: 1px solid #e2e8f0;
            border-right: 1px solid #f7fafc;
            padding: 8px;
            vertical-align: middle;
        }

        table td:last-child {
            border-right: none;
        }

        table tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        table tr:hover {
            background-color: #e9ecef;
        }

        /* Badge Styling */
        .badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 7.5pt;
            font-weight: 600;
            color: white;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .badge-success {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            box-shadow: 0 2px 4px rgba(40, 167, 69, 0.3);
        }
        .badge-info {
            background: linear-gradient(135deg, #0d6efd 0%, #0dcaf0 100%);
            box-shadow: 0 2px 4px rgba(13, 110, 253, 0.3);
        }
        .badge-warning {
            background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
            box-shadow: 0 2px 4px rgba(255, 193, 7, 0.3);
        }
        .badge-danger {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            box-shadow: 0 2px 4px rgba(220, 53, 69, 0.3);
        }
        .badge-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            box-shadow: 0 2px 4px rgba(102, 126, 234, 0.3);
        }

        /* Info Box Styling */
        .info-box {
            border: 2px solid #cbd5e0;
            padding: 15px;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            margin-bottom: 12px;
            border-radius: 6px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        .info-box h3 {
            margin: 0 0 10px 0;
            font-size: 11pt;
            color: #2d3748;
            font-weight: 600;
            border-bottom: 2px solid #cbd5e0;
            padding-bottom: 5px;
        }

        .info-table {
            width: 100%;
            border: none;
        }

        .info-table td {
            padding: 6px 8px;
            border: none;
            background: transparent;
        }

        .info-table td:first-child {
            font-weight: 600;
            width: 40%;
            color: #4a5568;
        }

        /* Problems & Solutions Styling */
        .problems-solutions {
            display: table;
            width: 100%;
            border-spacing: 10px;
        }

        .ps-cell {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }

        .ps-box {
            border: 3px solid;
            padding: 15px;
            min-height: 120px;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }

        .ps-box.problems {
            border-color: #dc3545;
            background: linear-gradient(135deg, #fff5f5 0%, #ffe5e8 100%);
        }

        .ps-box.solutions {
            border-color: #28a745;
            background: linear-gradient(135deg, #f0fff4 0%, #d4edda 100%);
        }

        .ps-box h3 {
            margin: 0 0 12px 0;
            font-size: 11pt;
            font-weight: bold;
            padding-bottom: 6px;
        }

        .ps-box.problems h3 {
            color: #721c24;
            border-bottom: 2px solid #dc3545;
        }

        .ps-box.solutions h3 {
            color: #155724;
            border-bottom: 2px solid #28a745;
        }

        .ps-box ul {
            margin: 0;
            padding-left: 22px;
            line-height: 1.6;
        }

        .ps-box li {
            margin-bottom: 8px;
            font-size: 9pt;
        }

        .next-week {
            border: 3px solid #0d6efd;
            border-radius: 8px;
            padding: 15px;
            background: linear-gradient(to bottom, #e7f3ff, #d0e9ff);
            box-shadow: 0 2px 6px rgba(13, 110, 253, 0.15);
            margin-top: 15px;
        }

        .next-week h3 {
            margin: 0 0 12px 0;
            color: #0a3d8f;
            font-size: 11pt;
            font-weight: bold;
            border-bottom: 2px solid #0d6efd;
            padding-bottom: 6px;
        }

        .next-week ul {
            margin: 0;
            padding-left: 22px;
            line-height: 1.6;
        }

        .next-week li {
            margin-bottom: 8px;
            font-size: 9pt;
            color: #1e40af;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 8pt;
            color: #64748b;
            border-top: 2px solid #e2e8f0;
            padding-top: 15px;
            background: linear-gradient(to right, #f8fafc, #f1f5f9, #f8fafc);
            border-radius: 4px;
        }

        .footer strong {
            color: #475569;
        }

        .text-success { color: #38a169; }
        .text-warning { color: #d69e2e; }
        .text-danger { color: #e53e3e; }
        .text-muted { color: #718096; }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>Weekly Progress Report</h1>
        <div class="project-info">
            <strong>Project:</strong> {{ $project->title }}<br>
            <strong>Week:</strong> {{ $weekStartDate->format('M d, Y') }} - {{ $weekEndDate->format('M d, Y') }}
            (Week {{ $weekStartDate->weekOfYear }}, {{ $weekStartDate->year }})<br>
            <strong>Generated:</strong> {{ now()->format('M d, Y H:i') }}
        </div>
    </div>

    <!-- Summary Section -->
    <div class="section">
        <div class="section-title">Summary</div>
        <div class="summary-grid">
            <div class="summary-row">
                <div class="summary-cell">
                    <div class="label">Total Tasks</div>
                    <div class="value">{{ $summary['total_tasks'] }}</div>
                    <div class="sublabel">Activities</div>
                </div>
                <div class="summary-cell">
                    <div class="label">Planned Weight</div>
                    <div class="value">{{ number_format($summary['planned_weight'], 2) }}%</div>
                    <div class="sublabel">Target</div>
                </div>
                <div class="summary-cell">
                    <div class="label">Actual Weight</div>
                    <div class="value {{ $summary['actual_weight'] >= $summary['planned_weight'] ? 'text-success' : 'text-warning' }}">
                        {{ number_format($summary['actual_weight'], 2) }}%
                    </div>
                    <div class="sublabel">Achieved</div>
                </div>
                <div class="summary-cell">
                    <div class="label">Deviation</div>
                    <div class="value {{ $summary['deviation_weight'] >= 0 ? 'text-danger' : 'text-success' }}">
                        {{ $summary['deviation_weight'] > 0 ? '-' : '+' }}{{ number_format(abs($summary['deviation_weight']), 2) }}%
                    </div>
                    <div class="sublabel">{{ $summary['deviation_weight'] >= 0 ? 'Behind' : 'Ahead' }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Distribution -->
    <div class="section">
        <div class="section-title">Status Distribution</div>
        <div class="status-grid">
            <div class="status-row">
                <div class="status-cell status-completed">
                    <div class="count">{{ $summary['completed'] }}</div>
                    <div class="label">Completed</div>
                </div>
                <div class="status-cell status-ontrack">
                    <div class="count">{{ $summary['on_track'] }}</div>
                    <div class="label">On Track</div>
                </div>
                <div class="status-cell status-atrisk">
                    <div class="count">{{ $summary['at_risk'] }}</div>
                    <div class="label">At Risk</div>
                </div>
                <div class="status-cell status-delayed">
                    <div class="count">{{ $summary['delayed'] }}</div>
                    <div class="label">Delayed</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Weekly Plan -->
    @if($weeklyPlan)
    <div class="section">
        <div class="section-title">Weekly Plan</div>
        <div class="info-box">
            <h3>Objectives</h3>
            <div>{!! nl2br(e($weeklyPlan->objectives ?? 'No objectives set')) !!}</div>
        </div>
        <table class="info-table">
            <tr>
                <td>Status:</td>
                <td>
                    <span class="badge badge-{{ $weeklyPlan->status === 'completed' ? 'success' : 'primary' }}">
                        {{ ucfirst($weeklyPlan->status) }}
                    </span>
                </td>
            </tr>
            <tr>
                <td>Planned Weight Total:</td>
                <td>{{ number_format($weeklyPlan->planned_weight_total ?? 0, 2) }}%</td>
            </tr>
            <tr>
                <td>Actual Weight Total:</td>
                <td>{{ number_format($weeklyPlan->actual_weight_total ?? 0, 2) }}%</td>
            </tr>
            <tr>
                <td>Completion Rate:</td>
                <td>
                    <strong class="{{ $weeklyPlan->getCompletionPercentage() >= 100 ? 'text-success' : 'text-warning' }}">
                        {{ number_format($weeklyPlan->getCompletionPercentage(), 1) }}%
                    </strong>
                </td>
            </tr>
        </table>
    </div>
    @endif

    <!-- Task Progress Details -->
    <div class="section">
        <div class="section-title">Task Progress Details</div>
        <table>
            <thead>
                <tr>
                    <th style="width: 25%;">Task</th>
                    <th style="width: 12%;">Assignee</th>
                    <th style="width: 8%;">Weight</th>
                    <th style="width: 8%;">Plan %</th>
                    <th style="width: 8%;">Actual %</th>
                    <th style="width: 10%;">Progress</th>
                    <th style="width: 10%;">Deviation</th>
                    <th style="width: 10%;">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($progressEntries as $progress)
                <tr>
                    <td>
                        <strong>{{ $progress->task->wbs_code }}</strong><br>
                        {{ $progress->task->title }}
                    </td>
                    <td>
                        @if($progress->task->assignee)
                            {{ $progress->task->assignee->name }}
                        @else
                            <span class="text-muted">Unassigned</span>
                        @endif
                    </td>
                    <td>{{ number_format($progress->task->weight ?? 0, 2) }}%</td>
                    <td>{{ number_format($progress->planned_percentage ?? 0, 1) }}%</td>
                    <td>{{ number_format($progress->actual_percentage ?? 0, 1) }}%</td>
                    <td>{{ number_format($progress->progress_percentage, 0) }}%</td>
                    <td>
                        @if($progress->deviation_percentage !== null)
                            <span class="badge badge-{{ abs($progress->deviation_percentage) < 10 ? 'success' : (abs($progress->deviation_percentage) < 20 ? 'warning' : 'danger') }}">
                                {{ $progress->deviation_percentage > 0 ? '-' : '+' }}{{ number_format(abs($progress->deviation_percentage), 1) }}%
                            </span>
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        @php
                            $statusColors = [
                                'on-track' => 'info',
                                'at-risk' => 'warning',
                                'delayed' => 'danger',
                                'completed' => 'success'
                            ];
                        @endphp
                        <span class="badge badge-{{ $statusColors[$progress->status] ?? 'primary' }}">
                            {{ ucfirst(str_replace('-', ' ', $progress->status)) }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align: center; color: #718096; padding: 20px;">No progress data available for this week</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Problems & Solutions -->
    <div class="section">
        <div class="section-title">Problems & Solutions</div>
        <div class="problems-solutions">
            <div class="ps-cell">
                <div class="ps-box problems">
                    <h3>Major Problems</h3>
                    @if(!empty($problems) && count($problems) > 0)
                        <ul>
                            @foreach($problems as $problem)
                                <li>{{ $problem }}</li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted">No major problems reported this week</p>
                    @endif
                </div>
            </div>
            <div class="ps-cell">
                <div class="ps-box solutions">
                    <h3>Proposed Solutions</h3>
                    @if(!empty($solutions) && count($solutions) > 0)
                        <ul>
                            @foreach($solutions as $solution)
                                <li>{{ $solution }}</li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted">No solutions documented</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Next Week Actions -->
    @if($weeklyPlan && $weeklyPlan->next_week_plan)
    <div class="section">
        <div class="section-title">Program & Actions for Next Week</div>
        <div class="next-week">
            {!! nl2br(e($weeklyPlan->next_week_plan)) !!}
        </div>
    </div>
    @endif

    <!-- Remarks & Attachments -->
    @if($weeklyPlan && ($weeklyPlan->remarks || $weeklyPlan->attachments))
    <div class="section">
        <div class="section-title">Remarks & Attachments</div>
        <div class="info-box">
            @if($weeklyPlan->remarks)
            <h3>Remarks</h3>
            <p>{!! nl2br(e($weeklyPlan->remarks)) !!}</p>
            @endif

            @if($weeklyPlan->attachments && count($weeklyPlan->attachments) > 0)
            <h3 style="margin-top: 15px;">Attachments</h3>
            <ul>
                @foreach($weeklyPlan->attachments as $attachment)
                    <li>{{ $attachment['name'] ?? 'Attachment' }}</li>
                @endforeach
            </ul>
            @endif
        </div>
    </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        <p>This report was automatically generated by the Tracking Project Management System</p>
        <p>© {{ now()->year }} - Project: {{ $project->title }}</p>
    </div>
</body>
</html>
