<?php

namespace App\Providers;

use App\Models\Admin;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\SocialLink;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // CRITICAL: This checks the AUTHENTICATED user, not a passed parameter
        Gate::before(function ($user, $ability) {
            // If user is an Admin with superadmin role, grant ALL permissions
            if ($user instanceof Admin && $user->hasRole('superadmin')) {
                return true;
            }

            // Otherwise, let Spatie handle permission checks normally
            return null; // Important: return null, not false
        });

        View::composer('*', function ($view) {
            $socialLinks = SocialLink::all();
            $view->with('socialLinks', $socialLinks);
        });
    }
}
