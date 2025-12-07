<?php

namespace App\Services;

use App\Models\Project;
use App\Models\ProjectHoliday;
use App\Models\ProjectWorkingDay;
use Carbon\Carbon;

class WorkingDayCalculator
{
    /**
     * Calculate the number of working days between two dates.
     */
    public function calculateWorkingDays(Project $project, Carbon $startDate, Carbon $endDate): int
    {
        $workingDaysConfig = $this->getWorkingDaysConfig($project);
        $holidays = $this->getHolidays($project, $startDate, $endDate);

        $workingDays = 0;
        $currentDate = $startDate->copy();

        while ($currentDate->lte($endDate)) {
            if ($this->isWorkingDay($currentDate, $workingDaysConfig, $holidays)) {
                $workingDays++;
            }
            $currentDate->addDay();
        }

        return $workingDays;
    }

    /**
     * Add working days to a start date.
     */
    public function addWorkingDays(Project $project, Carbon $startDate, int $daysToAdd): Carbon
    {
        $workingDaysConfig = $this->getWorkingDaysConfig($project);
        $currentDate = $startDate->copy();
        $daysAdded = 0;

        while ($daysAdded < $daysToAdd) {
            $currentDate->addDay();

            // Get holidays for this specific date (efficient query)
            $holidays = $this->getHolidays($project, $currentDate, $currentDate);

            if ($this->isWorkingDay($currentDate, $workingDaysConfig, $holidays)) {
                $daysAdded++;
            }
        }

        return $currentDate;
    }

    /**
     * Calculate end date based on start date and duration in working days.
     */
    public function calculateEndDate(Project $project, Carbon $startDate, int $durationInDays): Carbon
    {
        if ($durationInDays <= 0) {
            return $startDate->copy();
        }

        return $this->addWorkingDays($project, $startDate, $durationInDays - 1);
    }

    /**
     * Check if a specific date is a working day.
     */
    public function isWorkingDay(Carbon $date, ProjectWorkingDay $config, $holidays): bool
    {
        // Check if it's a configured working day of week
        if (!$config->isWorkingDay($date)) {
            return false;
        }

        // Check if it's a holiday
        $dateString = $date->format('Y-m-d');
        foreach ($holidays as $holiday) {
            if ($holiday->date->format('Y-m-d') === $dateString) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get working days configuration for a project.
     */
    public function getWorkingDaysConfig(Project $project): ProjectWorkingDay
    {
        $config = $project->workingDays()->first();

        // Create default config if doesn't exist
        if (!$config) {
            $config = ProjectWorkingDay::create([
                'project_id' => $project->id,
                'monday' => true,
                'tuesday' => true,
                'wednesday' => true,
                'thursday' => true,
                'friday' => true,
                'saturday' => false,
                'sunday' => false,
                'work_start_time' => '09:00:00',
                'work_end_time' => '17:00:00',
                'hours_per_day' => 8.00,
            ]);
        }

        return $config;
    }

    /**
     * Get holidays for a date range.
     */
    public function getHolidays(Project $project, Carbon $startDate, Carbon $endDate)
    {
        return ProjectHoliday::where('project_id', $project->id)
            ->dateRange($startDate, $endDate)
            ->get();
    }

    /**
     * Calculate hours between two dates considering working hours.
     */
    public function calculateWorkingHours(Project $project, Carbon $startDate, Carbon $endDate): float
    {
        $workingDays = $this->calculateWorkingDays($project, $startDate, $endDate);
        $config = $this->getWorkingDaysConfig($project);

        return $workingDays * $config->hours_per_day;
    }

    /**
     * Get next working day from a given date.
     */
    public function getNextWorkingDay(Project $project, Carbon $date): Carbon
    {
        $workingDaysConfig = $this->getWorkingDaysConfig($project);
        $nextDay = $date->copy()->addDay();

        while (true) {
            $holidays = $this->getHolidays($project, $nextDay, $nextDay);

            if ($this->isWorkingDay($nextDay, $workingDaysConfig, $holidays)) {
                return $nextDay;
            }

            $nextDay->addDay();
        }
    }

    /**
     * Get previous working day from a given date.
     */
    public function getPreviousWorkingDay(Project $project, Carbon $date): Carbon
    {
        $workingDaysConfig = $this->getWorkingDaysConfig($project);
        $prevDay = $date->copy()->subDay();

        while (true) {
            $holidays = $this->getHolidays($project, $prevDay, $prevDay);

            if ($this->isWorkingDay($prevDay, $workingDaysConfig, $holidays)) {
                return $prevDay;
            }

            $prevDay->subDay();
        }
    }

    /**
     * Get working days breakdown by week/month.
     */
    public function getWorkingDaysBreakdown(Project $project, Carbon $startDate, Carbon $endDate, string $period = 'week'): array
    {
        $breakdown = [];
        $currentDate = $startDate->copy();

        while ($currentDate->lte($endDate)) {
            $periodKey = $period === 'week'
                ? $currentDate->format('Y-W')
                : $currentDate->format('Y-m');

            if (!isset($breakdown[$periodKey])) {
                $breakdown[$periodKey] = [
                    'period' => $period === 'week'
                        ? 'Week ' . $currentDate->format('W, Y')
                        : $currentDate->format('M Y'),
                    'start_date' => $currentDate->copy(),
                    'working_days' => 0,
                    'non_working_days' => 0,
                    'holidays' => 0,
                ];
            }

            $workingDaysConfig = $this->getWorkingDaysConfig($project);
            $holidays = $this->getHolidays($project, $currentDate, $currentDate);

            if ($this->isWorkingDay($currentDate, $workingDaysConfig, $holidays)) {
                $breakdown[$periodKey]['working_days']++;
            } else {
                if (count($holidays) > 0) {
                    $breakdown[$periodKey]['holidays']++;
                } else {
                    $breakdown[$periodKey]['non_working_days']++;
                }
            }

            $breakdown[$periodKey]['end_date'] = $currentDate->copy();
            $currentDate->addDay();
        }

        return array_values($breakdown);
    }
}
