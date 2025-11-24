<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'role',
        'profile_photo'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'email', 'email');
    }

    public function tasks()
    {
        // Get tasks through the user relationship
        return $this->hasManyThrough(Task::class, User::class, 'email', 'user_id', 'email', 'id');
    }
}