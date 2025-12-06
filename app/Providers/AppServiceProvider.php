<?php

namespace App\Providers;

use App\Models\Discussion;
use App\Models\Project;
use App\Models\ProjectFile;
use App\Models\Task;
use App\Observers\DiscussionObserver;
use App\Observers\ProjectFileObserver;
use App\Observers\ProjectObserver;
use App\Observers\TaskObserver;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register observers
        Project::observe(ProjectObserver::class);
        Task::observe(TaskObserver::class);
        ProjectFile::observe(ProjectFileObserver::class);
        Discussion::observe(DiscussionObserver::class);

        // Define authorization gates for user roles
        Gate::define('admin', function ($user) {
            return $user->user_type === 'admin';
        });

        Gate::define('team_lead', function ($user) {
            return $user->user_type === 'team_lead';
        });

        Gate::define('team_member', function ($user) {
            return $user->user_type === 'team_member';
        });
    }
}
