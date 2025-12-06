<?php

namespace App\Observers;

use App\Models\Activity;
use App\Models\Task;
use App\Services\NotificationService;

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

        // Notify assigned user
        if ($task->assigned_to) {
            NotificationService::taskAssigned($task, $task->assignedTo);
        }
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

            // Notify project creator and team lead
            $notifyUsers = [$task->project->created_by];
            if ($task->project->teamInfo && $task->project->teamInfo->team_lead_id) {
                $notifyUsers[] = $task->project->teamInfo->team_lead_id;
            }
            NotificationService::taskCompleted($task, array_unique($notifyUsers));
        } elseif ($task->isDirty('assigned_to') && $task->assigned_to) {
            // Notify newly assigned user
            NotificationService::taskAssigned($task, $task->assignedTo);
        } elseif ($task->isDirty(['status', 'priority'])) {
            Activity::log([
                'user_id' => auth()->id() ?? $task->created_by,
                'project_id' => $task->project_id,
                'activity_type' => 'task_updated',
                'subject_type' => Task::class,
                'subject_id' => $task->id,
                'description' => "Updated task: {$task->title}",
            ]);

            // Notify assigned user about updates
            if ($task->assigned_to) {
                NotificationService::taskUpdated($task);
            }
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
