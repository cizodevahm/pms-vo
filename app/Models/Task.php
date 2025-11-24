<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'user_id',
        'title',
        'description',
        'status',
        'due_date',
        'created_by'
    ];

    protected $casts = [
        'due_date' => 'date',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Keep backward compatibility
    public function teamMember()
    {
        return $this->user();
    }

    // Check if user can edit this task
    public function canBeEditedBy($user)
    {
        if (!$user) {
            return false;
        }

        // Allow all authenticated users to edit any task
        return true;
    }
}