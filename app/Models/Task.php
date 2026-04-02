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
    ];

    protected $casts = [
        'due_date' => 'date',
    ];

    // Task belongs to the user it was assigned to
    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    // Task belongs to the user who created it
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Task can have many files
    public function files()
    {
        return $this->hasMany(TaskFile::class);
    }
}