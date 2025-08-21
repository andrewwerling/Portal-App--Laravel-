<?php
use App\Livewire\Dashboard;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

// OAuth Test Route
Route::get('/oauth-test', function () {
    return view('oauth-test');
})->name('oauth.test');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Admin routes
Route::middleware(['auth', 'verified', 'account.level:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
    
    Route::get('/users', App\Livewire\Admin\UserManagement::class)->name('admin.users');
    
    // Battery information endpoint
    Route::get('/battery-info', App\Livewire\Admin\BatteryInfo::class)->name('admin.battery-info');
});

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
