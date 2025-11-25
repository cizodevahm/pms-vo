<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
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
        ];
    }

    // Add these new methods
    public function teamMember()
    {
        return $this->hasOne(TeamMember::class, 'email', 'email');
    }

    public function hasRole($roles)
    {
        // Check role directly from User model first
        if ($this->role) {
            $roles = is_array($roles) ? $roles : [$roles];
            return in_array($this->role, $roles);
        }

        // Fallback to teamMember relationship
        $teamMember = $this->teamMember;
        if (!$teamMember) {
            return false;
        }

        $roles = is_array($roles) ? $roles : [$roles];
        return in_array($teamMember->role, $roles);
    }

    // Helper method to get user's role
    public function getRole()
    {
        return $this->role ?? $this->teamMember?->role ?? 'No Role';
    }

    // Check if user can manage projects
    public function canManageProjects()
    {
        return $this->hasRole(['Project Manager', 'Super Admin']);
    }

    // Tasks relationship
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    // Projects managed by this user
    public function managedProjects()
    {
        return $this->hasMany(Project::class, 'manager_id');
    }
}