<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'start_date',
        'end_date',
        'logo',
        'manager_id'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }
}