<?php

namespace App\Observers;

use App\Models\Activity;
use App\Models\Task;

class TaskObserver
{
    /**
     * Handle the Task "created" event.
     */
    public function created(Task $task): void
    {
        Activity::log([
            'user_id' => $task->created_by,
            'project_id' => $task->project_id,
            'activity_type' => 'task_created',
            'subject_type' => Task::class,
            'subject_id' => $task->id,
            'description' => "Created task: {$task->title}",
        ]);
    }

    /**
     * Handle the Task "updated" event.
     */
    public function updated(Task $task): void
    {
        // Log status change to completed
        if ($task->isDirty('status') && $task->status === 'completed') {
            Activity::log([
                'user_id' => auth()->id() ?? $task->created_by,
                'project_id' => $task->project_id,
                'activity_type' => 'task_completed',
                'subject_type' => Task::class,
                'subject_id' => $task->id,
                'description' => "Completed task: {$task->title}",
            ]);
        } elseif ($task->isDirty(['status', 'priority', 'assigned_to'])) {
            Activity::log([
                'user_id' => auth()->id() ?? $task->created_by,
                'project_id' => $task->project_id,
                'activity_type' => 'task_updated',
                'subject_type' => Task::class,
                'subject_id' => $task->id,
                'description' => "Updated task: {$task->title}",
            ]);
        }
    }

    /**
     * Handle the Task "deleted" event.
     */
    public function deleted(Task $task): void
    {
        // Don't log deletion
    }

    /**
     * Handle the Task "restored" event.
     */
    public function restored(Task $task): void
    {
        //
    }

    /**
     * Handle the Task "force deleted" event.
     */
    public function forceDeleted(Task $task): void
    {
        //
    }
}
