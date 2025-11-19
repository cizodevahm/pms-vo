<?php

namespace App\Http\Controllers;

use App\Models\TeamMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TeamMemberController extends Controller
{
    public function index()
    {
        $teamMembers = TeamMember::withCount('tasks')->paginate(10);
        return view('team-members.index', compact('teamMembers'));
    }

    public function create()
    {
        return view('team-members.create');
    }

    public function store(Request $request)
    {
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

    public function show(TeamMember $teamMember)
    {
        $teamMember->load(['tasks.project']);
        return view('team-members.show', compact('teamMember'));
    }

    public function edit(TeamMember $teamMember)
    {
        return view('team-members.edit', compact('teamMember'));
    }

    public function update(Request $request, TeamMember $teamMember)
    {
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
        if ($teamMember->profile_photo) {
            Storage::disk('public')->delete($teamMember->profile_photo);
        }

        $teamMember->delete();

        return redirect()->route('team-members.index')->with('success', 'Team member deleted successfully!');
    }
}