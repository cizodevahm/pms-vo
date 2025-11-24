<?php

namespace App\Http\Controllers;

use App\Models\TeamMember;
use App\Models\User;
use App\Models\Task;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class TeamMemberController extends Controller
{
    public function index()
    {
        $currentUser = Auth::user();

        // Check if user has access to team members
        if (!$currentUser->hasRole(['Project Manager', 'Super Admin'])) {
            abort(403, 'You don\'t have permission to view team members.');
        }

        if ($currentUser->hasRole('Super Admin')) {
            // Super Admin can see all users
            $users = User::withCount('tasks')->paginate(10);
            $canManage = true;
        } else {
            // Project Manager can only see users assigned to tasks
            // Get users who have at least one task assigned
            $userIds = Task::distinct()->pluck('user_id')->filter();

            $users = User::whereIn('id', $userIds)->withCount('tasks')->paginate(10);
            $canManage = false;
        }

        return view('team-members.index', compact('users', 'canManage'));
    }

    public function create()
    {
        // Only Super Admin can create team members
        if (!Auth::user()->hasRole('Super Admin')) {
            abort(403, 'You don\'t have permission to create team members.');
        }

        return view('team-members.create');
    }

    public function store(Request $request)
    {
        // Only Super Admin can create team members
        if (!Auth::user()->hasRole('Super Admin')) {
            abort(403, 'You don\'t have permission to create team members.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:team_members,email',
            'role' => 'required|in:Developer,Designer,QA,Project Manager,Super Admin',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('profile_photo')) {
            $validated['profile_photo'] = $request->file('profile_photo')->store('team-members', 'public');
        }

        TeamMember::create($validated);

        return redirect()->route('team-members.index')->with('success', 'Team member created successfully!');
    }

    public function show(User $user)
    {
        $currentUser = Auth::user();

        // Check if user has access
        if (!$currentUser->hasRole(['Project Manager', 'Super Admin'])) {
            abort(403, 'You don\'t have permission to view team member details.');
        }

        // Load user's tasks with projects
        $user->load(['tasks.project']);
        $user->tasks_count = $user->tasks->count();

        $canManage = $currentUser->hasRole('Super Admin');

        return view('team-members.show', compact('user', 'canManage'));
    }

    public function edit(TeamMember $teamMember)
    {
        // Only Super Admin can edit team members
        if (!Auth::user()->hasRole('Super Admin')) {
            abort(403, 'You don\'t have permission to edit team members.');
        }

        return view('team-members.edit', compact('teamMember'));
    }

    public function update(Request $request, TeamMember $teamMember)
    {
        // Only Super Admin can update team members
        if (!Auth::user()->hasRole('Super Admin')) {
            abort(403, 'You don\'t have permission to update team members.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:team_members,email,' . $teamMember->id,
            'role' => 'required|in:Developer,Designer,QA,Project Manager,Super Admin',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('profile_photo')) {
            if ($teamMember->profile_photo) {
                Storage::disk('public')->delete($teamMember->profile_photo);
            }
            $validated['profile_photo'] = $request->file('profile_photo')->store('team-members', 'public');
        }

        $teamMember->update($validated);

        return redirect()->route('team-members.index')->with('success', 'Team member updated successfully!');
    }

    public function destroy(TeamMember $teamMember)
    {
        // Only Super Admin can delete team members
        if (!Auth::user()->hasRole('Super Admin')) {
            abort(403, 'You don\'t have permission to delete team members.');
        }

        if ($teamMember->profile_photo) {
            Storage::disk('public')->delete($teamMember->profile_photo);
        }

        $teamMember->delete();

        return redirect()->route('team-members.index')->with('success', 'Team member deleted successfully!');
    }
}