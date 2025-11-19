<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserRole
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // For now, we'll check if user email belongs to a team member with required role
        $user = Auth::user();
        $teamMember = \App\Models\TeamMember::where('email', $user->email)->first();

        if (!$teamMember || !in_array($teamMember->role, $roles)) {
            abort(403, 'You do not have permission to access this resource.');
        }

        return $next($request);
    }
}