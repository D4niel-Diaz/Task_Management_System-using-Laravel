<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'status',
        'assigned_to',
        'created_by',
        'due_date',
        'priority',
    ];

    protected $casts = [
        'due_date' => 'date',
    ];

    // Task belongs to assigned user
    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    // Task belongs to creator
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Task has many files
    public function files()
    {
        return $this->hasMany(TaskFile::class, 'task_id');
    }

    // Scope: filter by status
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    // Scope: filter by assigned user
    public function scopeAssignedTo($query, $userId)
    {
        return $query->where('assigned_to', $userId);
    }

    // Status badge color helper
    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'pending'     => 'secondary',
            'in_progress' => 'warning',
            'completed'   => 'success',
            default       => 'secondary',
        };
    }

    // Priority badge color helper
    public function getPriorityColorAttribute(): string
    {
        return match ($this->priority ?? 'medium') {
            'low'    => 'info',
            'medium' => 'primary',
            'high'   => 'danger',
            default  => 'primary',
        };
    }
}
