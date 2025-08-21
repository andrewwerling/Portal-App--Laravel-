<?php

use App\Http\Controllers\Auth\OAuthController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::middleware('guest')->group(function () {
    Volt::route('register', 'pages.auth.register')
        ->name('register');

    Volt::route('login', 'pages.auth.login')
        ->name('login');

    Volt::route('forgot-password', 'pages.auth.forgot-password')
        ->name('password.request');

    Volt::route('reset-password/{token}', 'pages.auth.reset-password')
        ->name('password.reset');

    // OAuth Routes
    Route::get('auth/{provider}', [OAuthController::class, 'redirectToProvider'])
        ->name('oauth.redirect');

    Route::get('auth/{provider}/callback', [OAuthController::class, 'handleProviderCallback'])
        ->name('oauth.callback');

    // Facebook Deauthorization Callback - must be exempt from CSRF protection
    Route::post('auth/facebook/deauthorize', [OAuthController::class, 'handleFacebookDeauthorize'])
        ->name('oauth.facebook.deauthorize')
        ->middleware('web')
        ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);
});

Route::middleware('auth')->group(function () {
    Volt::route('verify-email', 'pages.auth.verify-email')
        ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Volt::route('confirm-password', 'pages.auth.confirm-password')
        ->name('password.confirm');
});
