<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $query = Project::with('manager')->withCount('tasks');

        // Filter by project manager if specified
        if ($request->filled('manager_id')) {
            $query->where('manager_id', $request->manager_id);
        }

        $projects = $query->paginate(10)->withQueryString();
        $canManageProjects = Auth::user()->canManageProjects();

        // Get all project managers for the filter dropdown
        $projectManagers = \App\Models\User::where('role', 'Project Manager')
            ->whereHas('managedProjects')
            ->orderBy('name')
            ->get();

        return view('projects.index', compact('projects', 'canManageProjects', 'projectManagers'));
    }

    public function create()
    {
        // Check if user can manage projects
        if (!Auth::user()->canManageProjects()) {
            abort(403, 'You don\'t have permission to create projects.');
        }

        return view('projects.create');
    }

    public function store(Request $request)
    {
        // Check if user can manage projects
        if (!Auth::user()->canManageProjects()) {
            abort(403, 'You don\'t have permission to create projects.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('projects', 'public');
        }

        // Set the current user as the project manager
        $validated['manager_id'] = Auth::id();

        Project::create($validated);

        return redirect()->route('projects.index')->with('success', 'Project created successfully!');
    }

    public function show(Project $project)
    {
        $project->load(['tasks.teamMember']);
        return view('projects.show', compact('project'));
    }

    public function edit(Project $project)
    {
        // Check if user can manage projects
        if (!Auth::user()->canManageProjects()) {
            abort(403, 'You don\'t have permission to edit projects.');
        }

        return view('projects.edit', compact('project'));
    }

    public function update(Request $request, Project $project)
    {
        // Check if user can manage projects
        if (!Auth::user()->canManageProjects()) {
            abort(403, 'You don\'t have permission to update projects.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('logo')) {
            if ($project->logo) {
                Storage::disk('public')->delete($project->logo);
            }
            $validated['logo'] = $request->file('logo')->store('projects', 'public');
        }

        $project->update($validated);

        return redirect()->route('projects.index')->with('success', 'Project updated successfully!');
    }

    public function destroy(Project $project)
    {
        // Check if user can manage projects
        if (!Auth::user()->canManageProjects()) {
            abort(403, 'You don\'t have permission to delete projects.');
        }

        if ($project->logo) {
            Storage::disk('public')->delete($project->logo);
        }

        $project->delete();

        return redirect()->route('projects.index')->with('success', 'Project deleted successfully!');
    }
}