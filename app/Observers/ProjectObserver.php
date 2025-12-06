<?php

namespace App\Observers;

use App\Models\Activity;
use App\Models\Project;

class ProjectObserver
{
    /**
     * Handle the Project "created" event.
     */
    public function created(Project $project): void
    {
        Activity::log([
            'user_id' => $project->created_by,
            'project_id' => $project->id,
            'activity_type' => 'project_created',
            'subject_type' => Project::class,
            'subject_id' => $project->id,
            'description' => "Created project: {$project->title}",
        ]);
    }

    /**
     * Handle the Project "updated" event.
     */
    public function updated(Project $project): void
    {
        // Log status change
        if ($project->isDirty('status')) {
            $activityType = $project->status === 'completed' ? 'project_completed' : 'project_updated';
            $description = $project->status === 'completed'
                ? "Completed project: {$project->title}"
                : "Updated project: {$project->title}";

            Activity::log([
                'user_id' => auth()->id() ?? $project->created_by,
                'project_id' => $project->id,
                'activity_type' => $activityType,
                'subject_type' => Project::class,
                'subject_id' => $project->id,
                'description' => $description,
                'metadata' => [
                    'old_status' => $project->getOriginal('status'),
                    'new_status' => $project->status,
                ],
            ]);
        }
    }

    /**
     * Handle the Project "deleted" event.
     */
    public function deleted(Project $project): void
    {
        // Don't log deletion as it will cascade delete activities
    }

    /**
     * Handle the Project "restored" event.
     */
    public function restored(Project $project): void
    {
        //
    }

    /**
     * Handle the Project "force deleted" event.
     */
    public function forceDeleted(Project $project): void
    {
        //
    }
}
