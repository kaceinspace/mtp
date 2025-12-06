<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaskDependency extends Model
{
    protected $fillable = [
        'task_id',
        'depends_on_task_id',
        'dependency_type',
        'lag_days',
    ];

    protected $casts = [
        'lag_days' => 'integer',
    ];

    /**
     * The task that has the dependency
     */
    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class, 'task_id');
    }

    /**
     * The task that is depended upon
     */
    public function dependsOnTask(): BelongsTo
    {
        return $this->belongsTo(Task::class, 'depends_on_task_id');
    }

    /**
     * Get human readable dependency type
     */
    public function getDependencyTypeLabel(): string
    {
        return match($this->dependency_type) {
            'finish-to-start' => 'Finish to Start (FS)',
            'start-to-start' => 'Start to Start (SS)',
            'finish-to-finish' => 'Finish to Finish (FF)',
            'start-to-finish' => 'Start to Finish (SF)',
            default => $this->dependency_type,
        };
    }

    /**
     * Get lag description
     */
    public function getLagDescription(): string
    {
        if ($this->lag_days == 0) {
            return 'No lag';
        } elseif ($this->lag_days > 0) {
            return "+{$this->lag_days} days lag";
        } else {
            return abs($this->lag_days) . " days lead";
        }
    }
}

