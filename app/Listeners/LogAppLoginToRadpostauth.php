<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request; // Added to get IP and User Agent

class LogAppLoginToRadpostauth
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function handle(Login $event)
    {
        // Check if the user object and user's email are available
        if ($event->user && $event->user->email) {
            DB::table('radpostauth')->insert([
                'username' => $event->user->email,
                'pass' => 'Accept', // Assuming successful login if this event is fired
                'reply' => 'Authentication Successful (Laravel App)',
                'authdate' => now(),
                'extra' => json_encode([
                    'ip_address' => $this->request->ip(),
                    'user_agent' => $this->request->userAgent(),
                    'provider' => 'laravel_app_login' // Differentiating the source
                ])
            ]);
        }
    }
}
