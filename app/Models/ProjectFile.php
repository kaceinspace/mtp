<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectFile extends Model
{
    protected $fillable = [
        'project_id',
        'user_id',
        'file_name',
        'file_path',
        'file_type',
        'file_size',
        'mime_type',
        'description',
        'download_count',
    ];

    protected $casts = [
        'download_count' => 'integer',
        'file_size' => 'integer',
    ];

    // File belongs to a project
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    // File uploaded by user
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Get formatted file size
    public function getFormattedSizeAttribute(): string
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];
        $i = 0;

        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }

    // Get file icon based on type
    public function getFileIconAttribute(): string
    {
        $icons = [
            // Documents
            'pdf' => '📄',
            'doc' => '📝',
            'docx' => '📝',
            'txt' => '📝',
            'odt' => '📝',

            // Spreadsheets
            'xls' => '📊',
            'xlsx' => '📊',
            'csv' => '📊',
            'ods' => '📊',

            // Presentations
            'ppt' => '📽️',
            'pptx' => '📽️',
            'odp' => '📽️',

            // Images
            'jpg' => '🖼️',
            'jpeg' => '🖼️',
            'png' => '🖼️',
            'gif' => '🖼️',
            'svg' => '🖼️',
            'webp' => '🖼️',

            // Archives
            'zip' => '📦',
            'rar' => '📦',
            '7z' => '📦',
            'tar' => '📦',
            'gz' => '📦',

            // Code
            'php' => '💻',
            'js' => '💻',
            'html' => '💻',
            'css' => '💻',
            'json' => '💻',
            'xml' => '💻',
            'py' => '💻',
            'java' => '💻',

            // Video
            'mp4' => '🎥',
            'avi' => '🎥',
            'mov' => '🎥',
            'mkv' => '🎥',

            // Audio
            'mp3' => '🎵',
            'wav' => '🎵',
            'ogg' => '🎵',
        ];

        return $icons[strtolower($this->file_type)] ?? '📎';
    }

    // Check if file is image
    public function isImage(): bool
    {
        return in_array(strtolower($this->file_type), ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp']);
    }

    // Check if file is document
    public function isDocument(): bool
    {
        return in_array(strtolower($this->file_type), ['pdf', 'doc', 'docx', 'txt', 'odt']);
    }

    // Check if file can be previewed
    public function canPreview(): bool
    {
        $previewable = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'pdf', 'txt'];
        return in_array(strtolower($this->file_type), $previewable);
    }
}
