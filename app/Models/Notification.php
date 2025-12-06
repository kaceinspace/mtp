<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'title',
        'message',
        'action_url',
        'related_user_id',
        'project_id',
        'is_read',
        'read_at',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
        'created_at' => 'datetime',
    ];

    /**
     * Get the user who receives the notification.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the user who triggered the notification.
     */
    public function relatedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'related_user_id');
    }

    /**
     * Get the related project.
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get notification icon based on type.
     */
    public function getIconAttribute(): string
    {
        return match($this->type) {
            'task_assigned' => '📋',
            'task_updated' => '🔄',
            'task_completed' => '✅',
            'task_due_soon' => '⏰',
            'project_created' => '📁',
            'project_updated' => '✏️',
            'project_member_added' => '👤',
            'file_uploaded' => '📤',
            'file_commented' => '💬',
            'discussion_created' => '💬',
            'discussion_replied' => '💭',
            'discussion_mentioned' => '@️⃣',
            'deadline_approaching' => '⚠️',
            'status_changed' => '🔔',
            default => '🔔',
        };
    }

    /**
     * Get notification color class based on type.
     */
    public function getColorClassAttribute(): string
    {
        return match($this->type) {
            'task_assigned', 'project_created', 'project_member_added', 'file_uploaded' => 'bg-green-100 text-green-600',
            'task_updated', 'project_updated', 'discussion_created' => 'bg-blue-100 text-blue-600',
            'task_completed' => 'bg-purple-100 text-purple-600',
            'task_due_soon', 'deadline_approaching' => 'bg-orange-100 text-orange-600',
            'discussion_replied', 'file_commented', 'discussion_mentioned' => 'bg-yellow-100 text-yellow-600',
            'status_changed' => 'bg-indigo-100 text-indigo-600',
            default => 'bg-gray-100 text-gray-600',
        };
    }

    /**
     * Scope for unread notifications.
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Scope for read notifications.
     */
    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }

    /**
     * Scope for user's notifications.
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Mark notification as read.
     */
    public function markAsRead(): void
    {
        if (!$this->is_read) {
            $this->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
        }
    }

    /**
     * Mark notification as unread.
     */
    public function markAsUnread(): void
    {
        $this->update([
            'is_read' => false,
            'read_at' => null,
        ]);
    }

    /**
     * Create a new notification.
     */
    public static function send(array $data): self
    {
        return self::create([
            'user_id' => $data['user_id'],
            'type' => $data['type'],
            'title' => $data['title'],
            'message' => $data['message'],
            'action_url' => $data['action_url'] ?? null,
            'related_user_id' => $data['related_user_id'] ?? auth()->id(),
            'project_id' => $data['project_id'] ?? null,
        ]);
    }
}

