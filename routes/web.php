<?php

use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DiscussionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TeamController;
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

        // Impersonate
        Route::post('users/{user}/impersonate', [AdminUserController::class, 'impersonate'])
            ->name('users.impersonate');
    });

    // Leave Impersonation (available to all authenticated users)
    Route::post('impersonate/leave', [AdminUserController::class, 'leaveImpersonate'])
        ->name('impersonate.leave');

    // Project Management (accessible by admin and team_lead)
    Route::resource('projects', ProjectController::class);

    // Task Management (accessible by admin, team_lead, and assigned team_member)
    Route::resource('tasks', TaskController::class);
    Route::get('/tasks-kanban', [TaskController::class, 'kanban'])->name('tasks.kanban');
    Route::patch('/tasks/{task}/update-status', [TaskController::class, 'updateStatus'])->name('tasks.updateStatus');

    // Team Management (accessible by admin, team_lead, and team_member)
    Route::resource('teams', TeamController::class);

    // Discussion/Chat for Projects
    Route::get('/projects/{project}/discussions', [DiscussionController::class, 'index'])->name('discussions.index');
    Route::post('/projects/{project}/discussions', [DiscussionController::class, 'store'])->name('discussions.store');
    Route::get('/projects/{project}/discussions/check-new', [DiscussionController::class, 'checkNewMessages'])->name('discussions.checkNew');
    Route::patch('/discussions/{discussion}', [DiscussionController::class, 'update'])->name('discussions.update');
    Route::delete('/discussions/{discussion}', [DiscussionController::class, 'destroy'])->name('discussions.destroy');
    Route::patch('/discussions/{discussion}/toggle-pin', [DiscussionController::class, 'togglePin'])->name('discussions.togglePin');

    // Project Files
    Route::get('/projects/{project}/files', [App\Http\Controllers\ProjectFileController::class, 'index'])->name('projects.files.index');
    Route::post('/projects/{project}/files', [App\Http\Controllers\ProjectFileController::class, 'store'])->name('projects.files.store');
    Route::get('/files/{file}/download', [App\Http\Controllers\ProjectFileController::class, 'download'])->name('files.download');
    Route::delete('/files/{file}', [App\Http\Controllers\ProjectFileController::class, 'destroy'])->name('files.destroy');

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
