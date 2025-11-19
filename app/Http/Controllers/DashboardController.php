<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use App\Models\TeamMember;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_projects' => Project::count(),
            'total_tasks' => Task::count(),
            'pending_tasks' => Task::where('status', 'Pending')->count(),
            'completed_tasks' => Task::where('status', 'Completed')->count(),
        ];

        return view('dashboard', compact('stats'));
    }
}