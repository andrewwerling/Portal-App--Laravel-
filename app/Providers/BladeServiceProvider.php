<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class BladeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Role-based directives
        Blade::directive('superadmin', function () {
            return "<?php if(auth()->check() && auth()->user()->isSuperAdmin()): ?>";
        });

        Blade::directive('endsuperadmin', function () {
            return "<?php endif; ?>";
        });

        Blade::directive('admin', function () {
            return "<?php if(auth()->check() && auth()->user()->isAdmin()): ?>";
        });

        Blade::directive('endadmin', function () {
            return "<?php endif; ?>";
        });

        Blade::directive('manager', function () {
            return "<?php if(auth()->check() && auth()->user()->isManager()): ?>";
        });

        Blade::directive('endmanager', function () {
            return "<?php endif; ?>";
        });

        Blade::directive('regularuser', function () {
            return "<?php if(auth()->check() && auth()->user()->isUser()): ?>";
        });

        Blade::directive('endregularuser', function () {
            return "<?php endif; ?>";
        });

        Blade::directive('guest', function () {
            return "<?php if(auth()->check() && auth()->user()->isGuest()): ?>";
        });

        Blade::directive('endguest', function () {
            return "<?php endif; ?>";
        });

        // Level-based directive
        Blade::directive('accountlevel', function ($level) {
            return "<?php if(auth()->check() && auth()->user()->hasAccountLevel($level)): ?>";
        });

        Blade::directive('endaccountlevel', function () {
            return "<?php endif; ?>";
        });
    }
}
