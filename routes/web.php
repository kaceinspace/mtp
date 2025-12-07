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
    Route::get('/tasks-wbs', [App\Http\Controllers\TaskWbsController::class, 'index'])->name('tasks.wbs');
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

    // Work Breakdown Structure (WBS)
    Route::get('/projects/{project}/wbs', [App\Http\Controllers\WbsController::class, 'index'])->name('projects.wbs');
    Route::get('/projects/{project}/wbs/gantt', [App\Http\Controllers\WbsController::class, 'showGantt'])->name('projects.wbs.gantt');
    Route::get('/projects/{project}/wbs/tree', [App\Http\Controllers\WbsController::class, 'tree'])->name('projects.wbs.tree');
    Route::get('/projects/{project}/wbs/{task}/children', [App\Http\Controllers\WbsController::class, 'getChildren'])->name('projects.wbs.children');
    Route::post('/projects/{project}/wbs', [App\Http\Controllers\WbsController::class, 'store'])->name('projects.wbs.store');
    Route::post('/projects/{project}/wbs/reorder', [App\Http\Controllers\WbsController::class, 'reorder'])->name('projects.wbs.reorder');
    Route::post('/projects/{project}/wbs/bulk-update', [App\Http\Controllers\WbsController::class, 'bulkUpdate'])->name('projects.wbs.bulk-update');
    Route::post('/projects/{project}/wbs/bulk-assign', [App\Http\Controllers\WbsController::class, 'bulkAssign'])->name('projects.wbs.bulk-assign');
    Route::post('/projects/{project}/wbs/bulk-delete', [App\Http\Controllers\WbsController::class, 'bulkDelete'])->name('projects.wbs.bulk-delete');
    Route::patch('/projects/{project}/wbs/{task}', [App\Http\Controllers\WbsController::class, 'update'])->name('projects.wbs.update');
    Route::delete('/projects/{project}/wbs/{task}', [App\Http\Controllers\WbsController::class, 'destroy'])->name('projects.wbs.destroy');

    // Task Dependencies
    Route::post('/projects/{project}/wbs/dependencies', [App\Http\Controllers\WbsController::class, 'addDependency'])->name('projects.wbs.dependencies.add');
    Route::delete('/projects/{project}/wbs/dependencies/{dependency}', [App\Http\Controllers\WbsController::class, 'removeDependency'])->name('projects.wbs.dependencies.remove');
    Route::get('/projects/{project}/wbs/{task}/dependencies', [App\Http\Controllers\WbsController::class, 'getDependencies'])->name('projects.wbs.dependencies.get');

    // Critical Path
    Route::post('/projects/{project}/wbs/critical-path/calculate', [App\Http\Controllers\WbsController::class, 'calculateCriticalPath'])->name('projects.wbs.critical-path.calculate');
    Route::get('/projects/{project}/wbs/critical-path', [App\Http\Controllers\WbsController::class, 'showCriticalPath'])->name('projects.wbs.critical-path');

    // WBS Templates
    Route::post('/projects/{project}/wbs/templates/save', [App\Http\Controllers\WbsController::class, 'saveTemplate'])->name('projects.wbs.templates.save');
    Route::post('/projects/{project}/wbs/templates/load', [App\Http\Controllers\WbsController::class, 'loadTemplate'])->name('projects.wbs.templates.load');
    Route::get('/projects/{project}/wbs/templates', [App\Http\Controllers\WbsController::class, 'listTemplates'])->name('projects.wbs.templates.list');
    Route::delete('/projects/{project}/wbs/templates/{templateId}', [App\Http\Controllers\WbsController::class, 'deleteTemplate'])->name('projects.wbs.templates.delete');

    // Weight Management
    Route::patch('/projects/{project}/wbs/{task}/weight', [App\Http\Controllers\WbsController::class, 'updateWeight'])->name('projects.wbs.weight.update');
    Route::post('/projects/{project}/wbs/weight/auto-distribute', [App\Http\Controllers\WbsController::class, 'autoDistributeWeight'])->name('projects.wbs.weight.auto-distribute');
    Route::patch('/projects/{project}/wbs/{task}/weight/toggle-lock', [App\Http\Controllers\WbsController::class, 'toggleWeightLock'])->name('projects.wbs.weight.toggle-lock');
    Route::get('/projects/{project}/wbs/weight/summary', [App\Http\Controllers\WbsController::class, 'getWeightSummary'])->name('projects.wbs.weight.summary');
    Route::get('/projects/{project}/wbs/weight/timeline', [App\Http\Controllers\WbsController::class, 'getWeightTimeline'])->name('projects.wbs.weight.timeline');
    Route::get('/projects/{project}/wbs/weight/by-status', [App\Http\Controllers\WbsController::class, 'getWeightByStatus'])->name('projects.wbs.weight.by-status');

    // Calendar & Scheduling
    Route::get('/projects/{project}/wbs/calendar/settings', [App\Http\Controllers\WbsController::class, 'getCalendarSettings'])->name('projects.wbs.calendar.settings');
    Route::put('/projects/{project}/wbs/calendar/working-days', [App\Http\Controllers\WbsController::class, 'updateWorkingDays'])->name('projects.wbs.calendar.working-days');
    Route::post('/projects/{project}/wbs/calendar/holidays', [App\Http\Controllers\WbsController::class, 'addHoliday'])->name('projects.wbs.calendar.holidays.add');
    Route::delete('/projects/{project}/wbs/calendar/holidays/{holidayId}', [App\Http\Controllers\WbsController::class, 'deleteHoliday'])->name('projects.wbs.calendar.holidays.delete');
    Route::post('/projects/{project}/wbs/calendar/calculate-working-days', [App\Http\Controllers\WbsController::class, 'calculateWorkingDays'])->name('projects.wbs.calendar.calculate');
    Route::get('/projects/{project}/wbs/calendar/planning', [App\Http\Controllers\WbsController::class, 'getPlanningView'])->name('projects.wbs.calendar.planning');

    // Progress Tracking (Phase 4.1)
    Route::get('/projects/{project}/progress', [App\Http\Controllers\ProgressController::class, 'index'])->name('projects.progress.index');
    Route::get('/projects/{project}/progress/weekly-plan', [App\Http\Controllers\ProgressController::class, 'getWeeklyPlan'])->name('projects.progress.weekly-plan');
    Route::put('/projects/{project}/progress/weekly-plan/{plan}', [App\Http\Controllers\ProgressController::class, 'updateWeeklyPlan'])->name('projects.progress.weekly-plan.update');
    Route::patch('/projects/{project}/progress/task/{task}', [App\Http\Controllers\ProgressController::class, 'updateProgress'])->name('projects.progress.update');
    Route::get('/projects/{project}/progress/task/{task}', [App\Http\Controllers\ProgressController::class, 'getTaskProgress'])->name('projects.progress.task');
    Route::get('/projects/{project}/progress/summary', [App\Http\Controllers\ProgressController::class, 'getWeeklySummary'])->name('projects.progress.summary');
    Route::get('/projects/{project}/progress/deviation-alerts', [App\Http\Controllers\ProgressController::class, 'getDeviationAlerts'])->name('projects.progress.deviation-alerts');

    // Progress Reports (Phase 4.2)
    Route::get('/projects/{project}/progress/report', [App\Http\Controllers\ProgressController::class, 'showReport'])->name('projects.progress.report');
    Route::get('/projects/{project}/progress/export/excel', [App\Http\Controllers\ProgressController::class, 'exportExcel'])->name('projects.progress.export.excel');
    Route::get('/projects/{project}/progress/export/pdf', [App\Http\Controllers\ProgressController::class, 'exportPdf'])->name('projects.progress.export.pdf');

    // Analytics & S-Curve (Phase 4.3)
    Route::get('/projects/{project}/analytics', [App\Http\Controllers\AnalyticsController::class, 'index'])->name('projects.analytics.index');
    Route::get('/projects/{project}/analytics/scurve-data', [App\Http\Controllers\AnalyticsController::class, 'getSCurveData'])->name('projects.analytics.scurve');

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Notifications
    Route::get('/notifications', [App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/recent', [App\Http\Controllers\NotificationController::class, 'recent'])->name('notifications.recent');
    Route::get('/notifications/unread-count', [App\Http\Controllers\NotificationController::class, 'unreadCount'])->name('notifications.unreadCount');
    Route::post('/notifications/mark-all-read', [App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');
    Route::post('/notifications/{notification}/mark-read', [App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
    Route::delete('/notifications/{notification}', [App\Http\Controllers\NotificationController::class, 'destroy'])->name('notifications.destroy');
});

require __DIR__.'/auth.php';
