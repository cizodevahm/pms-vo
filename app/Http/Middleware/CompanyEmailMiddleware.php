<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CompanyEmailMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();
            if (!str_ends_with($user->email, '@company.com')) {
                return redirect()->route('access.denied');
            }
        }

        return $next($request);
    }
}