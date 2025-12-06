<?php

namespace App\Observers;

use App\Models\Activity;
use App\Models\Discussion;

class DiscussionObserver
{
    /**
     * Handle the Discussion "created" event.
     */
    public function created(Discussion $discussion): void
    {
        Activity::log([
            'user_id' => $discussion->user_id,
            'project_id' => $discussion->project_id,
            'activity_type' => 'discussion_created',
            'subject_type' => Discussion::class,
            'subject_id' => $discussion->id,
            'description' => "Started discussion: {$discussion->title}",
        ]);
    }

    /**
     * Handle the Discussion "updated" event.
     */
    public function updated(Discussion $discussion): void
    {
        // Log pin/unpin
        if ($discussion->isDirty('is_pinned') && $discussion->is_pinned) {
            Activity::log([
                'user_id' => auth()->id(),
                'project_id' => $discussion->project_id,
                'activity_type' => 'discussion_pinned',
                'subject_type' => Discussion::class,
                'subject_id' => $discussion->id,
                'description' => "Pinned discussion: {$discussion->title}",
            ]);
        }
    }

    /**
     * Handle the Discussion "deleted" event.
     */
    public function deleted(Discussion $discussion): void
    {
        // Don't log deletion
    }

    /**
     * Handle the Discussion "restored" event.
     */
    public function restored(Discussion $discussion): void
    {
        //
    }

    /**
     * Handle the Discussion "force deleted" event.
     */
    public function forceDeleted(Discussion $discussion): void
    {
        //
    }
}
