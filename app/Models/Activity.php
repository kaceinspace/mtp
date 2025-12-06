<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Activity extends Model
{
    protected $fillable = [
        'user_id',
        'project_id',
        'activity_type',
        'subject_type',
        'subject_id',
        'description',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
        'created_at' => 'datetime',
    ];

    /**
     * Get the user who performed the activity.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the project related to the activity.
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the subject (polymorphic).
     */
    public function subject(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get activity icon based on type.
     */
    public function getIconAttribute(): string
    {
        return match($this->activity_type) {
            'project_created' => '📁',
            'project_updated' => '✏️',
            'project_completed' => '✅',
            'task_created' => '📝',
            'task_updated' => '🔄',
            'task_completed' => '✔️',
            'file_uploaded' => '📤',
            'file_downloaded' => '📥',
            'file_deleted' => '🗑️',
            'discussion_created' => '💬',
            'discussion_replied' => '💭',
            'discussion_pinned' => '📌',
            'member_added' => '👤',
            'member_removed' => '👋',
            default => '📋',
        };
    }

    /**
     * Get activity color class based on type.
     */
    public function getColorClassAttribute(): string
    {
        return match($this->activity_type) {
            'project_created', 'task_created', 'file_uploaded', 'discussion_created', 'member_added' => 'text-green-600 bg-green-50',
            'project_updated', 'task_updated' => 'text-blue-600 bg-blue-50',
            'project_completed', 'task_completed' => 'text-purple-600 bg-purple-50',
            'file_deleted', 'member_removed' => 'text-red-600 bg-red-50',
            'discussion_replied', 'discussion_pinned', 'file_downloaded' => 'text-yellow-600 bg-yellow-50',
            default => 'text-gray-600 bg-gray-50',
        };
    }

    /**
     * Scope for recent activities.
     */
    public function scopeRecent($query, $limit = 10)
    {
        return $query->latest()->limit($limit);
    }

    /**
     * Scope for user's activities.
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope for project's activities.
     */
    public function scopeForProject($query, $projectId)
    {
        return $query->where('project_id', $projectId);
    }

    /**
     * Log a new activity.
     */
    public static function log(array $data): self
    {
        return self::create([
            'user_id' => $data['user_id'] ?? auth()->id(),
            'project_id' => $data['project_id'] ?? null,
            'activity_type' => $data['activity_type'],
            'subject_type' => $data['subject_type'] ?? null,
            'subject_id' => $data['subject_id'] ?? null,
            'description' => $data['description'],
            'metadata' => $data['metadata'] ?? null,
        ]);
    }
}

