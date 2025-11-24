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

        // Get user's actual role
        $userRole = $user->getRole();

        // Project Managers and Super Admins can edit any task
        if ($userRole === 'Project Manager' || $userRole === 'Super Admin') {
            return true;
        }

        // Other roles can only edit tasks they created (only if created_by is set and matches)
        if ($this->created_by && $this->created_by === $user->id) {
            return true;
        }

        // Default: no permission for any other case
        return false;
    }
}