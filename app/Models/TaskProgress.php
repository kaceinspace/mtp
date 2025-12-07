<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaskProgress extends Model
{
    protected $table = 'task_progress';

    protected $fillable = [
        'task_id',
        'updated_by',
        'progress_percentage',
        'planned_percentage',
        'actual_percentage',
        'planned_weight',
        'actual_weight',
        'week_start_date',
        'week_end_date',
        'actual_start_date',
        'actual_end_date',
        'status',
        'notes',
        'issues',
        'proposed_solutions',
        'deviation_percentage',
        'deviation_days',
    ];

    protected $casts = [
        'progress_percentage' => 'decimal:2',
        'planned_percentage' => 'decimal:2',
        'actual_percentage' => 'decimal:2',
        'planned_weight' => 'decimal:2',
        'actual_weight' => 'decimal:2',
        'week_start_date' => 'date',
        'week_end_date' => 'date',
        'actual_start_date' => 'date',
        'actual_end_date' => 'date',
        'deviation_percentage' => 'decimal:2',
        'deviation_days' => 'integer',
    ];

    /**
     * Task relationship
     */
    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    /**
     * User who updated the progress
     */
    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Calculate deviation
     */
    public function calculateDeviation(): void
    {
        $this->deviation_percentage = $this->planned_percentage - $this->actual_percentage;

        // Calculate schedule deviation based on dates
        if ($this->actual_end_date && $this->week_end_date) {
            $this->deviation_days = $this->week_end_date->diffInDays($this->actual_end_date, false);
        }
    }

    /**
     * Determine status based on deviation
     */
    public function determineStatus(): void
    {
        if ($this->progress_percentage >= 100) {
            $this->status = 'completed';
        } elseif ($this->deviation_percentage > 20) {
            $this->status = 'delayed';
        } elseif ($this->deviation_percentage > 10) {
            $this->status = 'at-risk';
        } else {
            $this->status = 'on-track';
        }
    }

    /**
     * Check if behind schedule
     */
    public function isBehindSchedule(): bool
    {
        return $this->deviation_percentage > 0 || $this->deviation_days > 0;
    }

    /**
     * Get status color
     */
    public function getStatusColor(): string
    {
        return match($this->status) {
            'completed' => 'green',
            'on-track' => 'blue',
            'at-risk' => 'yellow',
            'delayed' => 'red',
            default => 'gray',
        };
    }

    /**
     * Scope for specific week
     */
    public function scopeForWeek($query, $startDate)
    {
        return $query->where('week_start_date', $startDate);
    }

    /**
     * Scope for date range
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('week_start_date', [$startDate, $endDate]);
    }

    /**
     * Scope for behind schedule
     */
    public function scopeBehindSchedule($query)
    {
        return $query->where(function($q) {
            $q->where('deviation_percentage', '>', 0)
              ->orWhere('deviation_days', '>', 0);
        });
    }
}
