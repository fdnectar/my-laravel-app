<?php

namespace App\Providers;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Auth\Middleware\RedirectIfAuthenticated;

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
        // Redirect an authenticated user to dashboard
        RedirectIfAuthenticated::redirectUsing(function() {
            return route('admin.dashboard');
        });

        //Redirect to login if not authenticated
        Authenticate::redirectUsing(function() {
            Session::flash('fail', 'You must login first');
            return route('admin.login');
        });
    }
}
