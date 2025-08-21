<?php

namespace App\Listeners;

use App\Events\LoginAttemptRecorded;
use Illuminate\Support\Facades\DB;

class LogRadiusEventToRadpostauth
{
    public function handle(LoginAttemptRecorded $event)
    {
        DB::table('radpostauth')->insert([
            'username' => $event->loginData['email'],
            'pass' => $event->loginData['success'] ? 'Accept' : 'Reject',
            'reply' => $event->loginData['success'] 
                ? 'Authentication Successful' 
                : 'Authentication Failed',
            'authdate' => now(),
            'extra' => json_encode([
                'ip_address' => $event->loginData['ip'] ?? null,
                'user_agent' => $event->loginData['user_agent'] ?? null,
                'provider' => $event->loginData['provider'] ?? null
            ])
        ]);
    }
}