<?php

namespace App\Providers;
use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Carbon::setLocale(app()->getLocale());
        // ❌ এখানে locale সেট করার কিছু থাকবে না

        Gate::define('admin-only', function($user){
            return $user->hasRole('admin');
        });

        Gate::define('staff-only', function(User $user){
            return $user->hasRole('staff');
        });

        Gate::define('admin-or-staff', function($user) {
            return $user->hasAnyRole(['admin','staff']);
        });
    }
}
