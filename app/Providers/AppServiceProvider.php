<?php

namespace App\Providers;

use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;

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
        Vite::prefetch(concurrency: 3);
        Inertia::share([
            'auth' => function () {
                return [
                    'user' => auth()->user(),
                    'can' => [
                        'manageUsers' => auth()->user()?->can('manage users'),
                        'manageRoles' => auth()->user()?->can('manage roles'),
                        'manageProjects' => auth()->user()?->can('manage posts'),
                    ],
                ];
            },
        ]);
    
    }
}
