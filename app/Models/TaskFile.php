<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class TaskFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_id',
        'file_path',
        'original_name',
        'mime_type',
        'size',
        'uploaded_by',
    ];

    // File belongs to a task
    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    // File uploaded by a user
    public function uploadedBy()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    // Human-readable file size
    public function getFormattedSizeAttribute(): string
    {
        $bytes = $this->size;
        if ($bytes >= 1048576) {
            return round($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return round($bytes / 1024, 2) . ' KB';
        }
        return $bytes . ' B';
    }

    // File icon based on mime type
    public function getIconAttribute(): string
    {
        $mime = $this->mime_type ?? '';
        if (str_contains($mime, 'image')) return 'bi-file-image';
        if (str_contains($mime, 'pdf'))   return 'bi-file-pdf';
        if (str_contains($mime, 'word') || str_contains($mime, 'document')) return 'bi-file-word';
        if (str_contains($mime, 'excel') || str_contains($mime, 'spreadsheet')) return 'bi-file-excel';
        if (str_contains($mime, 'zip') || str_contains($mime, 'compressed')) return 'bi-file-zip';
        return 'bi-file-earmark';
    }

    // Check if file exists on disk
    public function existsOnDisk(): bool
    {
        return Storage::disk('local')->exists($this->file_path);
    }
}
