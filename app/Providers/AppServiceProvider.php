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
        Gate::define('admin', function ($user) {
            return $user->user_type === 'admin';
        });

        Gate::define('guru', function ($user) {
            return $user->user_type === 'guru';
        });

        Gate::define('penguji', function ($user) {
            return $user->user_type === 'guru_penguji';
        });

        Gate::define('siswa', function ($user) {
            return $user->user_type === 'siswa';
        });
    }
}
