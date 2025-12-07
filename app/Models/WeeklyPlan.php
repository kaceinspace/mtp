<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class WeeklyPlan extends Model
{
    protected $fillable = [
        'project_id',
        'created_by',
        'week_start_date',
        'week_end_date',
        'week_number',
        'year',
        'objectives',
        'key_activities',
        'planned_weight_total',
        'status',
        'submitted_at',
        'approved_at',
        'approved_by',
        'actual_weight_total',
        'achievements',
        'major_problems',
        'next_week_plan',
        'remarks',
        'attachments',
    ];

    protected $casts = [
        'week_start_date' => 'date',
        'week_end_date' => 'date',
        'week_number' => 'integer',
        'year' => 'integer',
        'planned_weight_total' => 'decimal:2',
        'actual_weight_total' => 'decimal:2',
        'submitted_at' => 'datetime',
        'approved_at' => 'datetime',
        'attachments' => 'array',
    ];

    /**
     * Project relationship
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Creator relationship
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Approver relationship
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Task progress entries for this week
     */
    public function taskProgress(): HasMany
    {
        return $this->hasMany(TaskProgress::class, 'week_start_date', 'week_start_date')
                    ->whereHas('task', function($q) {
                        $q->where('project_id', $this->project_id);
                    });
    }

    /**
     * Create from date
     */
    public static function createFromDate(Carbon $date, int $projectId, int $userId): self
    {
        $weekStart = $date->copy()->startOfWeek();
        $weekEnd = $date->copy()->endOfWeek();

        return self::create([
            'project_id' => $projectId,
            'created_by' => $userId,
            'week_start_date' => $weekStart,
            'week_end_date' => $weekEnd,
            'week_number' => $weekStart->weekOfYear,
            'year' => $weekStart->year,
        ]);
    }

    /**
     * Submit plan for approval
     */
    public function submit(): void
    {
        $this->status = 'submitted';
        $this->submitted_at = now();
        $this->save();
    }

    /**
     * Approve plan
     */
    public function approve(int $approverId): void
    {
        $this->status = 'approved';
        $this->approved_at = now();
        $this->approved_by = $approverId;
        $this->save();
    }

    /**
     * Start execution
     */
    public function startExecution(): void
    {
        $this->status = 'in-progress';
        $this->save();
    }

    /**
     * Complete plan
     */
    public function complete(): void
    {
        $this->status = 'completed';
        $this->save();
    }

    /**
     * Calculate completion percentage
     */
    public function getCompletionPercentage(): float
    {
        if ($this->planned_weight_total == 0) {
            return 0;
        }

        return round(($this->actual_weight_total / $this->planned_weight_total) * 100, 2);
    }

    /**
     * Get deviation
     */
    public function getDeviation(): float
    {
        return round($this->planned_weight_total - $this->actual_weight_total, 2);
    }

    /**
     * Check if behind schedule
     */
    public function isBehindSchedule(): bool
    {
        return $this->getDeviation() > 0;
    }

    /**
     * Scope for current week
     */
    public function scopeCurrentWeek($query)
    {
        $now = now();
        return $query->where('week_start_date', '<=', $now)
                     ->where('week_end_date', '>=', $now);
    }

    /**
     * Scope for specific project
     */
    public function scopeForProject($query, int $projectId)
    {
        return $query->where('project_id', $projectId);
    }

    /**
     * Scope for year
     */
    public function scopeForYear($query, int $year)
    {
        return $query->where('year', $year);
    }
}
