<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WbsTemplate extends Model
{
    protected $fillable = [
        'name',
        'user_id',
        'project_id',
        'structure',
        'description',
    ];

    protected $casts = [
        'structure' => 'array',
    ];

    /**
     * Get the user that owns the template.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the project this template is associated with (optional).
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
