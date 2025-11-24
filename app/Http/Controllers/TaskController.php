<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use App\Models\TeamMember;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $currentUser = auth()->user();

        // Get user's assigned tasks
        $userTasksQuery = Task::with(['project', 'user', 'creator'])
            ->where('user_id', $currentUser->id);

        // Filter user tasks by project if specified
        if ($request->filled('user_project_id')) {
            $userTasksQuery->where('project_id', $request->user_project_id);
        }

        // Filter user tasks by status if specified
        if ($request->filled('user_status')) {
            $userTasksQuery->where('status', $request->user_status);
        }

        $userTasks = $userTasksQuery->get();

        // Get all tasks (for managers/admins)
        $query = Task::with(['project', 'user', 'creator']);

        // Filter by project
        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $tasks = $query->paginate(10);
        $projects = Project::all();

        // Get projects where user has tasks (for user task filters)
        $userProjects = Project::whereIn('id', $userTasks->pluck('project_id')->unique())->get();

        return view('tasks.index', compact('tasks', 'projects', 'userTasks', 'userProjects'));
    }

    public function create()
    {
        $projects = Project::all();
        $users = User::all();
        return view('tasks.create', compact('projects', 'users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|in:Pending,In Progress,Completed',
            'due_date' => 'required|date|after_or_equal:today',
        ]);

        $validated['created_by'] = auth()->id();
        Task::create($validated);

        return redirect()->route('tasks.index')->with('success', 'Task created successfully!');
    }

    public function show(Task $task)
    {
        $task->load(['project', 'user', 'creator']);
        return view('tasks.show', compact('task'));
    }

    public function edit(Task $task)
    {
        // Check if user can edit this task
        if (!$task->canBeEditedBy(auth()->user())) {
            abort(403, 'You don\'t have permission to edit this task.');
        }

        $projects = Project::all();
        $users = User::all();
        return view('tasks.edit', compact('task', 'projects', 'users'));
    }

    public function update(Request $request, Task $task)
    {
        // Check if user can edit this task
        if (!$task->canBeEditedBy(auth()->user())) {
            abort(403, 'You don\'t have permission to edit this task.');
        }

        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|in:Pending,In Progress,Completed',
            'due_date' => 'required|date',
        ]);

        $task->update($validated);

        return redirect()->route('tasks.index')->with('success', 'Task updated successfully!');
    }

    public function destroy(Task $task)
    {
        // Only Project Managers and Super Admins can delete tasks
        if (!auth()->user()->hasRole(['Project Manager', 'Super Admin'])) {
            abort(403, 'You don\'t have permission to delete tasks.');
        }

        $task->delete();
        return redirect()->route('tasks.index')->with('success', 'Task deleted successfully!');
    }
}