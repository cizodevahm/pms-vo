<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SuperAdminController extends Controller
{
    public function dashboard()
    {
        // Check if user is Super Admin
        if (!Auth::user()->hasRole('Super Admin')) {
            abort(403, 'You don\'t have permission to access this page.');
        }

        // Get all Project Managers with detailed project and task information
        $projectManagers = User::where('role', 'Project Manager')
            ->with(['managedProjects.tasks', 'tasks'])
            ->withCount(['tasks'])
            ->get()
            ->map(function ($user) {
                // Get all projects managed by this user
                $managedProjects = $user->managedProjects->map(function ($project) {
                    $project->completed_tasks = $project->tasks()->where('status', 'Completed')->count();
                    $project->pending_tasks = $project->tasks()->where('status', 'Pending')->count();
                    $project->in_progress_tasks = $project->tasks()->where('status', 'In Progress')->count();
                    $project->total_tasks = $project->tasks()->count();

                    // Get detailed task information
                    $project->tasks_details = $project->tasks()->with('user')->get()->groupBy('status');

                    return $project;
                });

                // Get projects where user has assigned tasks (but doesn't manage)
                $projectsWithTasks = Project::whereHas('tasks', function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                })->whereNotIn('id', $managedProjects->pluck('id'))
                    ->with('tasks')
                    ->get()
                    ->map(function ($project) use ($user) {
                    // Show ALL tasks in the project, not just manager-assigned tasks
                    $project->completed_tasks = $project->tasks()->where('status', 'Completed')->count();
                    $project->pending_tasks = $project->tasks()->where('status', 'Pending')->count();
                    $project->in_progress_tasks = $project->tasks()->where('status', 'In Progress')->count();
                    $project->total_tasks = $project->tasks()->count();

                    // Get detailed task information for ALL tasks in the project
                    $project->tasks_details = $project->tasks()->with('user')->get()->groupBy('status');

                    $project->is_assigned_only = true; // Flag to indicate this is not a managed project
                    return $project;
                });

                // Combine managed projects and assigned projects
                $user->all_projects = $managedProjects->concat($projectsWithTasks);

                // Calculate overall statistics
                $user->managed_projects_count = $managedProjects->count();
                $user->assigned_projects_count = $projectsWithTasks->count();
                $user->total_projects_count = $user->all_projects->count();

                // Overall task statistics
                $user->completed_tasks_count = $user->tasks()->where('status', 'Completed')->count();
                $user->pending_tasks_count = $user->tasks()->where('status', 'Pending')->count();
                $user->in_progress_tasks_count = $user->tasks()->where('status', 'In Progress')->count();

                return $user;
            });

        // Get overall statistics
        $totalProjects = Project::count();
        $totalTasks = Task::count();
        $totalUsers = User::count();
        $completedTasks = Task::where('status', 'Completed')->count();

        return view('super-admin.dashboard', compact(
            'projectManagers',
            'totalProjects',
            'totalTasks',
            'totalUsers',
            'completedTasks'
        ));
    }
}
