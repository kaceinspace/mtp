<?php

namespace App\Exports;

use App\Models\Project;
use App\Models\WeeklyPlan;
use App\Models\TaskProgress;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class WeeklyProgressReportExport implements FromCollection, WithHeadings, WithTitle, WithStyles, WithColumnWidths
{
    protected $project;
    protected $weekStartDate;
    protected $weekEndDate;
    protected $weeklyPlan;
    protected $summary;

    public function __construct(Project $project, Carbon $weekStartDate, $summary)
    {
        $this->project = $project;
        $this->weekStartDate = $weekStartDate;
        $this->weekEndDate = $weekStartDate->copy()->endOfWeek();
        $this->summary = $summary;

        $this->weeklyPlan = WeeklyPlan::forProject($project->id)
            ->where('week_start_date', $weekStartDate)
            ->first();
    }

    public function collection()
    {
        $data = collect();

        // Header Information
        $data->push([
            'PROJECT WEEKLY PROGRESS REPORT',
            '',
            '',
            '',
            '',
            '',
            '',
            ''
        ]);

        $data->push(['']); // Empty row

        $data->push([
            'Project:',
            $this->project->title,
            '',
            'Week:',
            'Week ' . $this->weekStartDate->weekOfYear . ', ' . $this->weekStartDate->year,
            '',
            '',
            ''
        ]);

        $data->push([
            'Period:',
            $this->weekStartDate->format('M d, Y') . ' - ' . $this->weekEndDate->format('M d, Y'),
            '',
            'Report Date:',
            now()->format('M d, Y'),
            '',
            '',
            ''
        ]);

        $data->push(['']); // Empty row

        // Summary Section
        $data->push([
            'WEEKLY SUMMARY',
            '',
            '',
            '',
            '',
            '',
            '',
            ''
        ]);

        $data->push([
            'Metric',
            'Value',
            '',
            '',
            '',
            '',
            '',
            ''
        ]);

        $data->push(['Total Tasks', $this->summary['total_tasks']]);
        $data->push(['Planned Weight (%)', $this->summary['planned_weight']]);
        $data->push(['Actual Weight (%)', $this->summary['actual_weight']]);
        $data->push(['Deviation (%)', $this->summary['deviation_weight']]);
        $data->push(['Completion Rate (%)', $this->summary['completion_rate']]);
        $data->push(['Avg Progress (%)', $this->summary['avg_progress']]);

        $data->push(['']); // Empty row

        // Status Distribution
        $data->push([
            'STATUS DISTRIBUTION',
            '',
            '',
            '',
            '',
            '',
            '',
            ''
        ]);

        $data->push(['Completed', $this->summary['completed']]);
        $data->push(['On Track', $this->summary['on_track']]);
        $data->push(['At Risk', $this->summary['at_risk']]);
        $data->push(['Delayed', $this->summary['delayed']]);

        $data->push(['']); // Empty row

        // Planning Progress
        if ($this->weeklyPlan) {
            $data->push([
                'PLANNING & OBJECTIVES',
                '',
                '',
                '',
                '',
                '',
                '',
                ''
            ]);

            $data->push(['Objectives:', $this->weeklyPlan->objectives ?? 'N/A']);
            $data->push(['Key Activities:', $this->weeklyPlan->key_activities ?? 'N/A']);

            $data->push(['']); // Empty row
        }

        // Task Details Header
        $data->push(['']); // Empty row
        $data->push([
            'TASK PROGRESS DETAILS',
            '',
            '',
            '',
            '',
            '',
            '',
            ''
        ]);

        // This will be followed by the actual headings row

        return $data;
    }

    public function headings(): array
    {
        // Count header rows (before task details)
        $headerRows = 24; // Approximate, will be adjusted

        return [
            'WBS Code',
            'Task Title',
            'Assignee',
            'Weight (%)',
            'Planned (%)',
            'Actual (%)',
            'Progress (%)',
            'Status'
        ];
    }

    public function title(): string
    {
        return 'Week ' . $this->weekStartDate->weekOfYear . ' Report';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 16],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
            6 => ['font' => ['bold' => true, 'size' => 12]],
            13 => ['font' => ['bold' => true, 'size' => 12]],
            19 => ['font' => ['bold' => true, 'size' => 12]],
            24 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 15,
            'B' => 35,
            'C' => 20,
            'D' => 12,
            'E' => 12,
            'F' => 12,
            'G' => 12,
            'H' => 15,
        ];
    }
}
