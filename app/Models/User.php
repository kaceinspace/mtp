<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'nisn',
        'nip',
        'phone',
        'avatar',
        'bio',
        'user_type',
        'jurusan',
        'kelas',
        'tahun_ajaran',
        'dark_mode',
        'email_notifications',
        'wa_notifications',
        'wa_number',
        'is_active',
        'last_activity',
        'settings',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_activity' => 'datetime',
            'dark_mode' => 'boolean',
            'email_notifications' => 'boolean',
            'wa_notifications' => 'boolean',
            'is_active' => 'boolean',
            'settings' => 'array',
        ];
    }

    // Relationships

    public function profile()
    {
        return $this->hasOne(UserProfile::class);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user');
    }

    public function createdProjects()
    {
        return $this->hasMany(Project::class, 'created_by');
    }

    public function projectMemberships()
    {
        return $this->belongsToMany(Project::class, 'project_members')
            ->withPivot('role', 'responsibilities', 'is_active')
            ->withTimestamps();
    }

    public function assignedTasks()
    {
        return $this->hasMany(Task::class, 'assigned_to');
    }

    public function createdTasks()
    {
        return $this->hasMany(Task::class, 'created_by');
    }

    public function documents()
    {
        return $this->hasMany(Document::class, 'uploaded_by');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function sentNotifications()
    {
        return $this->hasMany(Notification::class, 'from_user_id');
    }

    public function chatRoomMemberships()
    {
        return $this->belongsToMany(ChatRoom::class, 'chat_room_members')
            ->withPivot('is_admin', 'last_read_at', 'unread_count')
            ->withTimestamps();
    }

    public function chatMessages()
    {
        return $this->hasMany(ChatMessage::class);
    }

    public function achievements()
    {
        return $this->belongsToMany(Achievement::class, 'user_achievements')
            ->withPivot('earned_at', 'notes')
            ->withTimestamps();
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }

    public function workSessions()
    {
        return $this->hasMany(WorkSession::class);
    }

    public function productivityMetrics()
    {
        return $this->hasMany(ProductivityMetric::class);
    }

    public function grades()
    {
        return $this->hasMany(FinalGrade::class, 'student_id');
    }

    // Helper Methods

    public function hasRole($role)
    {
        return $this->roles()->where('name', $role)->exists();
    }

    public function hasPermission($permission)
    {
        return $this->roles()
            ->whereHas('permissions', function ($query) use ($permission) {
                $query->where('name', $permission);
            })
            ->exists();
    }

    public function isAdmin()
    {
        return $this->user_type === 'admin';
    }

    public function isGuru()
    {
        return in_array($this->user_type, ['guru', 'guru_penguji']);
    }

    public function isSiswa()
    {
        return $this->user_type === 'siswa';
    }

    public function getAvatarUrlAttribute()
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }

        // Default avatar based on user type
        $avatarMap = [
            'admin' => 'default-admin.png',
            'guru' => 'default-guru.png',
            'guru_penguji' => 'default-penguji.png',
            'siswa' => 'default-siswa.png',
        ];

        return asset('images/avatars/' . ($avatarMap[$this->user_type] ?? 'default.png'));
    }
}

