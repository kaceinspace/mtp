<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    protected $fillable = [
        'title',
        'description',
        'department',
        'team',
        'start_date',
        'end_date',
        'status',
        'priority',
        'created_by',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    // Project creator relationship
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Project members (many-to-many)
    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'project_members')
            ->withTimestamps();
    }

    // Project tasks
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    // Project team relationship
    public function teamInfo(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'team');
    }

    // Project discussions
    public function discussions(): HasMany
    {
        return $this->hasMany(Discussion::class);
    }

    // Project files
    public function files(): HasMany
    {
        return $this->hasMany(ProjectFile::class);
    }

    // Project holidays
    public function holidays(): HasMany
    {
        return $this->hasMany(ProjectHoliday::class);
    }

    // Project working days configuration
    public function workingDays(): HasMany
    {
        return $this->hasMany(ProjectWorkingDay::class);
    }

    // Project weekly plans
    public function weeklyPlans(): HasMany
    {
        return $this->hasMany(WeeklyPlan::class);
    }
}
