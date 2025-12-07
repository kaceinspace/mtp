<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends Model
{
    protected $fillable = [
        'project_id',
        'parent_id',
        'title',
        'description',
        'assigned_to',
        'status',
        'priority',
        'due_date',
        'completed_at',
        'order',
        'level',
        'wbs_code',
        'estimated_duration',
        'calculated_start_date',
        'calculated_end_date',
        'early_start',
        'early_finish',
        'late_start',
        'late_finish',
        'total_float',
        'is_critical',
        'weight',
        'weight_percentage',
        'is_weight_locked',
    ];

    protected $casts = [
        'due_date' => 'date',
        'completed_at' => 'datetime',
        'calculated_start_date' => 'date',
        'calculated_end_date' => 'date',
        'estimated_duration' => 'integer',
        'early_start' => 'integer',
        'early_finish' => 'integer',
        'late_start' => 'integer',
        'late_finish' => 'integer',
        'total_float' => 'integer',
        'is_critical' => 'boolean',
        'weight' => 'decimal:2',
        'weight_percentage' => 'decimal:2',
        'is_weight_locked' => 'boolean',
    ];

    // Task belongs to a project
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    // Task assigned to a user
    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    // Alias for assignee (for compatibility)
    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    // Parent task relationship (for WBS hierarchy)
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Task::class, 'parent_id');
    }

    // Children tasks (subtasks)
    public function children(): HasMany
    {
        return $this->hasMany(Task::class, 'parent_id')->orderBy('order');
    }

    // Get all descendants recursively
    public function descendants(): HasMany
    {
        return $this->children()->with('descendants');
    }

    // Get all ancestors up to root
    public function ancestors()
    {
        $ancestors = collect([]);
        $parent = $this->parent;

        while ($parent) {
            $ancestors->prepend($parent);
            $parent = $parent->parent;
        }

        return $ancestors;
    }

    // Check if task is a parent (has children)
    public function isParent(): bool
    {
        return $this->children()->exists();
    }

    // Check if task is root level (no parent)
    public function isRoot(): bool
    {
        return is_null($this->parent_id);
    }

    // Get root tasks for a project
    public static function rootTasks($projectId)
    {
        return static::where('project_id', $projectId)
            ->whereNull('parent_id')
            ->orderBy('order')
            ->get();
    }

    // Update WBS code based on hierarchy
    public function updateWbsCode(): void
    {
        if ($this->isRoot()) {
            // Root level: 1, 2, 3...
            $this->wbs_code = (string)($this->order + 1);
            $this->level = 0;
        } else {
            // Child level: parent_code.order (e.g., 1.1, 1.2, 2.1.3)
            $parentCode = $this->parent->wbs_code ?? '0';
            $this->wbs_code = $parentCode . '.' . ($this->order + 1);
            $this->level = $this->parent->level + 1;
        }

        $this->saveQuietly(); // Save without triggering events

        // Update children recursively
        foreach ($this->children as $child) {
            $child->updateWbsCode();
        }
    }

    // Task dependencies (tasks that this task depends on)
    public function dependencies(): HasMany
    {
        return $this->hasMany(TaskDependency::class, 'task_id');
    }

    // Tasks that depend on this task
    public function dependents(): HasMany
    {
        return $this->hasMany(TaskDependency::class, 'depends_on_task_id');
    }

    // Get tasks this task depends on
    public function dependsOnTasks()
    {
        return $this->belongsToMany(Task::class, 'task_dependencies', 'task_id', 'depends_on_task_id')
            ->withPivot(['dependency_type', 'lag_days'])
            ->withTimestamps();
    }

    // Get tasks that depend on this task
    public function dependentTasks()
    {
        return $this->belongsToMany(Task::class, 'task_dependencies', 'depends_on_task_id', 'task_id')
            ->withPivot(['dependency_type', 'lag_days'])
            ->withTimestamps();
    }

    // Progress tracking entries
    public function progressEntries(): HasMany
    {
        return $this->hasMany(TaskProgress::class)->orderBy('week_start_date', 'desc');
    }

    // Get latest progress
    public function latestProgress()
    {
        return $this->hasOne(TaskProgress::class)->latestOfMany('week_start_date');
    }

    // Get progress for specific week
    public function getProgressForWeek($weekStartDate)
    {
        return $this->progressEntries()
                    ->where('week_start_date', $weekStartDate)
                    ->first();
    }

    // Check if task has any dependencies
    public function hasDependencies(): bool
    {
        return $this->dependencies()->exists();
    }

    // Check if task has any dependents
    public function hasDependents(): bool
    {
        return $this->dependents()->exists();
    }

    // Check if task can start (all dependencies completed)
    public function canStart(): bool
    {
        if (!$this->hasDependencies()) {
            return true;
        }

        // Check if all dependencies are completed
        foreach ($this->dependsOnTasks as $dependency) {
            if ($dependency->status !== 'completed') {
                return false;
            }
        }

        return true;
    }

    // Calculate dates based on dependencies
    public function calculateDates(): void
    {
        // Use WorkingDayCalculator if available for the project
        $useWorkingDays = $this->project->workingDays()->exists();
        $calculator = $useWorkingDays ? new \App\Services\WorkingDayCalculator() : null;

        if (!$this->hasDependencies()) {
            // No dependencies, use project start date or today
            $startDate = $this->project->start_date ?? now();
            $this->calculated_start_date = $startDate;

            // Calculate end date using working days if configured
            if ($calculator && $this->estimated_duration > 0) {
                $this->calculated_end_date = $calculator->addWorkingDays(
                    $this->project,
                    $startDate->copy(),
                    $this->estimated_duration - 1
                );
            } else {
                $this->calculated_end_date = $startDate->copy()->addDays($this->estimated_duration - 1);
            }
        } else {
            // Calculate based on dependencies
            $latestEndDate = null;

            foreach ($this->dependencies as $dep) {
                $dependsOnTask = $dep->dependsOnTask;

                // Ensure dependency has calculated dates
                if (!$dependsOnTask->calculated_end_date) {
                    $dependsOnTask->calculateDates();
                    $dependsOnTask->save();
                }

                $baseDate = match($dep->dependency_type) {
                    'finish-to-start' => $dependsOnTask->calculated_end_date->copy()->addDays(1),
                    'start-to-start' => $dependsOnTask->calculated_start_date,
                    'finish-to-finish' => $dependsOnTask->calculated_end_date,
                    'start-to-finish' => $dependsOnTask->calculated_start_date,
                    default => $dependsOnTask->calculated_end_date->copy()->addDays(1),
                };

                // Apply lag/lead time (use working days if configured)
                if ($calculator && $dep->lag_days != 0) {
                    $calculatedDate = $calculator->addWorkingDays($this->project, $baseDate, $dep->lag_days);
                } else {
                    $calculatedDate = $baseDate->addDays($dep->lag_days);
                }

                if (!$latestEndDate || $calculatedDate > $latestEndDate) {
                    $latestEndDate = $calculatedDate;
                }
            }

            $this->calculated_start_date = $latestEndDate;

            // Calculate end date using working days if configured
            if ($calculator && $this->estimated_duration > 0) {
                $this->calculated_end_date = $calculator->addWorkingDays(
                    $this->project,
                    $latestEndDate->copy(),
                    $this->estimated_duration - 1
                );
            } else {
                $this->calculated_end_date = $latestEndDate->copy()->addDays($this->estimated_duration - 1);
            }
        }

        $this->saveQuietly();
    }

    // Get task with all nested children
    public function toTree()
    {
        $this->load('children');

        return [
            'id' => $this->id,
            'title' => $this->title,
            'wbs_code' => $this->wbs_code,
            'status' => $this->status,
            'priority' => $this->priority,
            'level' => $this->level,
            'order' => $this->order,
            'assigned_to' => $this->assigned_to,
            'assignee' => $this->assignee,
            'due_date' => $this->due_date,
            'children' => $this->children->map(fn($child) => $child->toTree())->toArray(),
        ];
    }

    /**
     * Calculate forward pass for critical path (Early Start & Early Finish)
     */
    public function calculateForwardPass(): void
    {
        if (!$this->estimated_duration || $this->estimated_duration <= 0) {
            $this->estimated_duration = 1; // Default to 1 day
        }

        if (!$this->hasDependencies()) {
            // No dependencies, can start at day 0
            $this->early_start = 0;
            $this->early_finish = $this->early_start + $this->estimated_duration;
        } else {
            // Start after all dependencies finish
            $maxEarlyFinish = 0;

            foreach ($this->dependencies as $dep) {
                $dependsOnTask = $dep->dependsOnTask;

                // Ensure dependency has been calculated
                if ($dependsOnTask->early_finish === null) {
                    $dependsOnTask->calculateForwardPass();
                    $dependsOnTask->saveQuietly();
                }

                $dependencyFinish = $dependsOnTask->early_finish + ($dep->lag_days ?? 0);

                if ($dependencyFinish > $maxEarlyFinish) {
                    $maxEarlyFinish = $dependencyFinish;
                }
            }

            $this->early_start = $maxEarlyFinish;
            $this->early_finish = $this->early_start + $this->estimated_duration;
        }

        $this->saveQuietly();
    }

    /**
     * Calculate backward pass for critical path (Late Start & Late Finish)
     */
    public function calculateBackwardPass(int $projectFinish): void
    {
        if (!$this->hasDependents()) {
            // No dependents, late finish is project finish
            $this->late_finish = $projectFinish;
            $this->late_start = $this->late_finish - $this->estimated_duration;
        } else {
            // Must finish before any dependent starts
            $minLateStart = PHP_INT_MAX;

            foreach ($this->dependents as $dep) {
                $dependentTask = $dep->task;

                // Ensure dependent has been calculated
                if ($dependentTask->late_start === null) {
                    $dependentTask->calculateBackwardPass($projectFinish);
                    $dependentTask->saveQuietly();
                }

                $requiredFinish = $dependentTask->late_start - ($dep->lag_days ?? 0);

                if ($requiredFinish < $minLateStart) {
                    $minLateStart = $requiredFinish;
                }
            }

            $this->late_finish = $minLateStart;
            $this->late_start = $this->late_finish - $this->estimated_duration;
        }

        // Calculate total float (slack time)
        $this->total_float = $this->late_start - $this->early_start;

        // Task is critical if total float is 0
        $this->is_critical = ($this->total_float === 0);

        $this->saveQuietly();
    }

    /**
     * Get critical path tasks for the project
     */
    public static function getCriticalPath(int $projectId): array
    {
        return static::where('project_id', $projectId)
            ->where('is_critical', true)
            ->orderBy('early_start')
            ->get()
            ->toArray();
    }

    /**
     * Get project duration (max early finish)
     */
    public static function getProjectDuration(int $projectId): int
    {
        $maxFinish = static::where('project_id', $projectId)
            ->whereNotNull('early_finish')
            ->max('early_finish');

        return $maxFinish ?? 0;
    }
}
