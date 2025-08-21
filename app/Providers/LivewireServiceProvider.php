<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use App\Livewire\Admin\UserManagement;
use App\Livewire\Admin\BatteryInfo;

class LivewireServiceProvider extends ServiceProvider
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
        // Register Livewire components
        Livewire::component('admin.user-management', UserManagement::class);
        Livewire::component('admin.battery-info', BatteryInfo::class);
        // Livewire::component('admin.activity-logs', ActivityLogs::class);
    }
}
