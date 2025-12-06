<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;

class NotificationService
{
    /**
     * Send notification when task is assigned to user.
     */
    public static function taskAssigned(Task $task, User $assignee): void
    {
        if ($assignee->id === auth()->id()) {
            return; // Don't notify if user assigns to themselves
        }

        Notification::send([
            'user_id' => $assignee->id,
            'type' => 'task_assigned',
            'title' => 'New Task Assigned',
            'message' => "You have been assigned to task: {$task->title}",
            'action_url' => route('tasks.show', $task),
            'project_id' => $task->project_id,
        ]);
    }

    /**
     * Send notification when task is updated.
     */
    public static function taskUpdated(Task $task): void
    {
        if (!$task->assigned_to || $task->assigned_to === auth()->id()) {
            return;
        }

        Notification::send([
            'user_id' => $task->assigned_to,
            'type' => 'task_updated',
            'title' => 'Task Updated',
            'message' => "Task '{$task->title}' has been updated",
            'action_url' => route('tasks.show', $task),
            'project_id' => $task->project_id,
        ]);
    }

    /**
     * Send notification when task is completed.
     */
    public static function taskCompleted(Task $task, array $notifyUserIds): void
    {
        foreach ($notifyUserIds as $userId) {
            if ($userId === auth()->id()) {
                continue; // Skip the user who completed the task
            }

            Notification::send([
                'user_id' => $userId,
                'type' => 'task_completed',
                'title' => 'Task Completed',
                'message' => "{$task->title} has been marked as completed",
                'action_url' => route('tasks.show', $task),
                'project_id' => $task->project_id,
            ]);
        }
    }

    /**
     * Send notification when task deadline is approaching.
     */
    public static function taskDueSoon(Task $task): void
    {
        if (!$task->assigned_to) {
            return;
        }

        Notification::send([
            'user_id' => $task->assigned_to,
            'type' => 'task_due_soon',
            'title' => 'Task Deadline Approaching',
            'message' => "Task '{$task->title}' is due on {$task->due_date->format('M d, Y')}",
            'action_url' => route('tasks.show', $task),
            'project_id' => $task->project_id,
        ]);
    }

    /**
     * Send notification when user is added to project.
     */
    public static function projectMemberAdded(Project $project, User $member): void
    {
        Notification::send([
            'user_id' => $member->id,
            'type' => 'project_member_added',
            'title' => 'Added to Project',
            'message' => "You have been added to project: {$project->title}",
            'action_url' => route('projects.show', $project),
            'project_id' => $project->id,
        ]);
    }

    /**
     * Send notification when project is updated.
     */
    public static function projectUpdated(Project $project, array $notifyUserIds): void
    {
        foreach ($notifyUserIds as $userId) {
            if ($userId === auth()->id()) {
                continue;
            }

            Notification::send([
                'user_id' => $userId,
                'type' => 'project_updated',
                'title' => 'Project Updated',
                'message' => "Project '{$project->title}' has been updated",
                'action_url' => route('projects.show', $project),
                'project_id' => $project->id,
            ]);
        }
    }

    /**
     * Send notification when file is uploaded to project.
     */
    public static function fileUploaded(Project $project, string $fileName, array $notifyUserIds): void
    {
        foreach ($notifyUserIds as $userId) {
            if ($userId === auth()->id()) {
                continue;
            }

            Notification::send([
                'user_id' => $userId,
                'type' => 'file_uploaded',
                'title' => 'New File Uploaded',
                'message' => "{$fileName} has been uploaded to {$project->title}",
                'action_url' => route('projects.files.index', $project),
                'project_id' => $project->id,
            ]);
        }
    }

    /**
     * Send notification when discussion is created.
     */
    public static function discussionCreated(Project $project, string $discussionTitle, array $notifyUserIds): void
    {
        foreach ($notifyUserIds as $userId) {
            if ($userId === auth()->id()) {
                continue;
            }

            Notification::send([
                'user_id' => $userId,
                'type' => 'discussion_created',
                'title' => 'New Discussion',
                'message' => "New discussion started: {$discussionTitle}",
                'action_url' => route('discussions.index', $project),
                'project_id' => $project->id,
            ]);
        }
    }

    /**
     * Send notification when discussion receives a reply.
     */
    public static function discussionReplied(Project $project, string $discussionTitle, User $originalPoster, string $replyUrl): void
    {
        if ($originalPoster->id === auth()->id()) {
            return;
        }

        Notification::send([
            'user_id' => $originalPoster->id,
            'type' => 'discussion_replied',
            'title' => 'New Reply on Discussion',
            'message' => "Someone replied to your discussion: {$discussionTitle}",
            'action_url' => $replyUrl,
            'project_id' => $project->id,
        ]);
    }

    /**
     * Send notification when status changes.
     */
    public static function statusChanged(string $entityType, string $entityName, string $oldStatus, string $newStatus, User $user, string $actionUrl, ?int $projectId = null): void
    {
        if ($user->id === auth()->id()) {
            return;
        }

        Notification::send([
            'user_id' => $user->id,
            'type' => 'status_changed',
            'title' => ucfirst($entityType) . ' Status Changed',
            'message' => "{$entityName} status changed from {$oldStatus} to {$newStatus}",
            'action_url' => $actionUrl,
            'project_id' => $projectId,
        ]);
    }

    /**
     * Send bulk notifications.
     */
    public static function sendBulk(array $notifications): void
    {
        foreach ($notifications as $notification) {
            Notification::send($notification);
        }
    }

    /**
     * Get unread count for user.
     */
    public static function getUnreadCount(int $userId): int
    {
        return Notification::forUser($userId)->unread()->count();
    }

    /**
     * Mark all notifications as read for user.
     */
    public static function markAllAsRead(int $userId): void
    {
        Notification::forUser($userId)->unread()->update([
            'is_read' => true,
            'read_at' => now(),
        ]);
    }
}
