<?php

namespace App\Providers;

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
        // Define authorization gates for user roles
        Gate::define('team_member', function ($user) {
            return $user->user_type === 'team_member';
        });

        Gate::define('team_lead', function ($user) {
            return $user->user_type === 'team_lead';
        });

        Gate::define('siswa', function ($user) {
            return $user->user_type === 'siswa';
        });
    }
}
