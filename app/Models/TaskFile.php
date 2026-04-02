<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_id',
        'file_path',
    ];

    // File belongs to a task
    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}