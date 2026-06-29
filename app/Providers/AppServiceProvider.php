<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        View::composer('*', function ($view) {
            if (auth()->check()) {
                $role = auth()->user()->role;
                $routePrefix = ($role === 'pengelola') ? 'pengelola' : 'admin';
                $view->with('routePrefix', $routePrefix);
            }
        });
    }
}