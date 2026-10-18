<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;

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
        View::composer('*', function ($view) {
            $user = Auth::user();
            $view->with('authUser', $user);

            if ($user) {
                $name = $user->name ?? 'Người dùng';
                $parts = preg_split('/\s+/', trim($name));
                $initials = count($parts) >= 2
                    ? mb_substr($parts[0], 0, 1) . mb_substr($parts[count($parts) - 1], 0, 1)
                    : mb_substr($name, 0, 2);
                $view->with('authInitials', mb_strtoupper($initials));
            } else {
                $view->with('authInitials', 'NT');
            }
        });
    }
}
