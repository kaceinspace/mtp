<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectWorkingDay extends Model
{
    protected $fillable = [
        'project_id',
        'monday',
        'tuesday',
        'wednesday',
        'thursday',
        'friday',
        'saturday',
        'sunday',
        'work_start_time',
        'work_end_time',
        'hours_per_day',
    ];

    protected $casts = [
        'monday' => 'boolean',
        'tuesday' => 'boolean',
        'wednesday' => 'boolean',
        'thursday' => 'boolean',
        'friday' => 'boolean',
        'saturday' => 'boolean',
        'sunday' => 'boolean',
        'hours_per_day' => 'decimal:2',
    ];

    /**
     * Get the project that owns the working days config.
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Check if a given date is a working day.
     */
    public function isWorkingDay(\Carbon\Carbon $date): bool
    {
        $dayName = strtolower($date->format('l'));
        return $this->{$dayName} ?? false;
    }

    /**
     * Get working days as array.
     */
    public function getWorkingDaysArray(): array
    {
        return [
            'monday' => $this->monday,
            'tuesday' => $this->tuesday,
            'wednesday' => $this->wednesday,
            'thursday' => $this->thursday,
            'friday' => $this->friday,
            'saturday' => $this->saturday,
            'sunday' => $this->sunday,
        ];
    }
}
