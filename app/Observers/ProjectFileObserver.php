<?php

namespace App\Observers;

use App\Models\Activity;
use App\Models\ProjectFile;

class ProjectFileObserver
{
    /**
     * Handle the ProjectFile "created" event.
     */
    public function created(ProjectFile $projectFile): void
    {
        Activity::log([
            'user_id' => $projectFile->user_id,
            'project_id' => $projectFile->project_id,
            'activity_type' => 'file_uploaded',
            'subject_type' => ProjectFile::class,
            'subject_id' => $projectFile->id,
            'description' => "Uploaded file: {$projectFile->file_name}",
            'metadata' => [
                'file_type' => $projectFile->file_type,
                'file_size' => $projectFile->file_size,
            ],
        ]);
    }

    /**
     * Handle the ProjectFile "updated" event.
     */
    public function updated(ProjectFile $projectFile): void
    {
        // Log download count increase
        if ($projectFile->isDirty('download_count')) {
            Activity::log([
                'user_id' => auth()->id(),
                'project_id' => $projectFile->project_id,
                'activity_type' => 'file_downloaded',
                'subject_type' => ProjectFile::class,
                'subject_id' => $projectFile->id,
                'description' => "Downloaded file: {$projectFile->file_name}",
            ]);
        }
    }

    /**
     * Handle the ProjectFile "deleted" event.
     */
    public function deleted(ProjectFile $projectFile): void
    {
        Activity::log([
            'user_id' => auth()->id(),
            'project_id' => $projectFile->project_id,
            'activity_type' => 'file_deleted',
            'subject_type' => ProjectFile::class,
            'subject_id' => $projectFile->id,
            'description' => "Deleted file: {$projectFile->file_name}",
        ]);
    }

    /**
     * Handle the ProjectFile "restored" event.
     */
    public function restored(ProjectFile $projectFile): void
    {
        //
    }

    /**
     * Handle the ProjectFile "force deleted" event.
     */
    public function forceDeleted(ProjectFile $projectFile): void
    {
        //
    }
}
