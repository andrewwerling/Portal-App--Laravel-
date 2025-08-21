<?php

namespace App\Livewire\Forms;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Validate;
use Livewire\Form;
use App\Services\RadiusAuthService;
use App\Models\User;

class LoginForm extends Form
{
    #[Validate('required|string|email')]
    public string $email = '';

    #[Validate('required|string')]
    public string $password = '';

    #[Validate('boolean')]
    public bool $remember = false;

    protected RadiusAuthService $radiusAuthService;

    public function boot(RadiusAuthService $radiusAuthService)
    {
        $this->radiusAuthService = $radiusAuthService;
    }

    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        // Attempt authentication via RadiusAuthService
        $radUser = $this->radiusAuthService->authenticate($this->email, $this->password);

        if (!$radUser) {
            RateLimiter::hit($this->throttleKey());

            // Record failed login attempt
            Log::info('Login Attempt Failed', [
                'email' => $this->email,
                'ip' => request()->ip()
            ]);

            throw ValidationException::withMessages([
                'form.email' => trans('auth.failed'),
            ]);
        }

        // Find or create a Laravel User model based on the RadUser
        $user = User::firstOrCreate(
            ['email' => $this->email],
            [
                'name' => $this->email,
                'password' => $this->password,
                'account_level' => $radUser->isInGroup('super-admin') ? 'super-admin' : 
                                   ($radUser->isInGroup('admin') ? 'admin' : 
                                   ($radUser->isInGroup('manager') ? 'manager' : 
                                   ($radUser->isInGroup('user') ? 'user' : 'guest')))
            ]
        );

        // Manually log in the user
        Auth::login($user, $this->remember);

        // Start a network session
        $sessionId = $this->radiusAuthService->startNetworkSession($radUser, 'Laravel-Portal');

        // Add session ID to user's session
        session(['radius_session_id' => $sessionId]);

        // Log successful login
        Log::info('Login Attempt Successful', [
            'email' => $this->email,
            'ip' => request()->ip()
        ]);

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the authentication request is not rate limited.
     */
    protected function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout(request()));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'form.email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the authentication rate limiting throttle key.
     */
    protected function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->email).'|'.request()->ip());
    }
}