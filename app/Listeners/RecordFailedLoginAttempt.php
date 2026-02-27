<?php

namespace App\Listeners;

use DeviceDetector\DeviceDetector;
use DeviceDetector\Parser\Device\AbstractDeviceParser;
use Illuminate\Auth\Events\Failed;
use Illuminate\Support\Facades\DB;

class RecordFailedLoginAttempt
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Failed $event): void
    {
        // Set abstraction for better performance
        AbstractDeviceParser::setVersionTruncation(AbstractDeviceParser::VERSION_TRUNCATION_NONE);

        // Initialize DeviceDetector
        $dd = new DeviceDetector(request()->userAgent());
        $dd->parse();

        // Gather device fingerprinting data
        $deviceInfo = [
            'hardware' => $dd->getDeviceName() ?: null,
            'browser' => $dd->getClient('name').' '.$dd->getClient('version') ?: null,
            'os' => $dd->getOs('name').' '.$dd->getOs('version') ?: null,
            'screen_resolution' => null, // Requires JavaScript
            'battery_usage' => null, // Requires JavaScript
            'device_memory' => null, // Requires JavaScript
            'browser_plugins' => null, // Requires JavaScript
            'browser_settings' => [
                'language' => request()->header('Accept-Language') ?: null,
                'timezone' => null, // Requires JavaScript
            ],
            'webgl_parameters' => null, // Requires JavaScript
        ];

        // Skip recording if we can't find a user
        if (! $event->user) {
            return;
        }

        // Record failed login attempt to radpostauth (same table used by
        // LogAppLoginToRadpostauth for successful logins) - BVSS LORD | 2026-02-27
        try {
            DB::table('radpostauth')->insert([
                'username' => $event->user->email,
                'pass' => 'Reject',
                'reply' => 'Authentication Failed (Laravel App)',
                'authdate' => now(),
                'extra' => json_encode([
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                    'provider' => 'laravel_app_login',
                ]),
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to record failed login attempt to radpostauth: '.$e->getMessage());
        }
    }
}
