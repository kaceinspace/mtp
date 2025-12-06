<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Discussion extends Model
{
    protected $fillable = [
        'project_id',
        'user_id',
        'message',
        'parent_id',
        'attachments',
        'is_pinned',
    ];

    protected $casts = [
        'attachments' => 'array',
        'is_pinned' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Discussion belongs to a project
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    // Discussion belongs to a user (author)
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Parent message (for threading)
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Discussion::class, 'parent_id');
    }

    // Replies to this message
    public function replies(): HasMany
    {
        return $this->hasMany(Discussion::class, 'parent_id')->orderBy('created_at', 'asc');
    }

    // Check if this is a reply
    public function isReply(): bool
    {
        return !is_null($this->parent_id);
    }
}
