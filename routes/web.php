<?php

use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Dashboard routes (requires authentication)
Route::middleware(['auth', 'verified'])->group(function () {
    // Main dashboard route - redirects based on user type
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Role-specific dashboards
    Route::get('/admin/dashboard', [DashboardController::class, 'admin'])
        ->name('dashboard.admin')
        ->middleware('can:admin');

    Route::get('/team-lead/dashboard', [DashboardController::class, 'teamLead'])
        ->name('dashboard.team_lead')
        ->middleware('can:team_lead');

    Route::get('/team-member/dashboard', [DashboardController::class, 'teamMember'])
        ->name('dashboard.team_member')
        ->middleware('can:team_member');

    // Admin Routes
    Route::middleware('can:admin')->prefix('admin')->name('admin.')->group(function () {
        // User Management
        Route::resource('users', AdminUserController::class);
        Route::post('users/{user}/toggle-status', [AdminUserController::class, 'toggleStatus'])
            ->name('users.toggle-status');
    });

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
