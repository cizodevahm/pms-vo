<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TeamMemberController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/access-denied', function () {
    return view('access-denied');
})->name('access.denied');

Route::middleware(['auth', 'verified', 'company.email'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Super Admin routes
    Route::middleware('super.admin')->group(function () {
        Route::get('/super-admin/dashboard', [SuperAdminController::class, 'dashboard'])->name('super-admin.dashboard');
    });

    Route::resource('projects', ProjectController::class);

    // Team Members routes - custom routes for User-based show method
    Route::get('/team-members', [TeamMemberController::class, 'index'])->name('team-members.index');
    Route::get('/team-members/user/{user}', [TeamMemberController::class, 'show'])->name('team-members.show');
    Route::get('/team-members/{teamMember}/edit', [TeamMemberController::class, 'edit'])->name('team-members.edit');
    Route::patch('/team-members/{teamMember}', [TeamMemberController::class, 'update'])->name('team-members.update');
    Route::delete('/team-members/{teamMember}', [TeamMemberController::class, 'destroy'])->name('team-members.destroy');

    Route::resource('tasks', TaskController::class);
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';