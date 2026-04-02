<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // A user can be assigned many tasks
    public function assignedTasks()
    {
        return $this->hasMany(Task::class, 'assigned_to');
    }

    // A user can create many tasks
    public function createdTasks()
    {
        return $this->hasMany(Task::class, 'created_by');
    }

    // Check if user is admin
    public function isAdmin()
    {
        return $this->role === 'admin';
    }
}