<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Failed;
use Illuminate\Support\Facades\DB;
use DeviceDetector\DeviceDetector;
use DeviceDetector\Parser\Device\AbstractDeviceParser;

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
            'browser' => $dd->getClient('name') . ' ' . $dd->getClient('version') ?: null,
            'os' => $dd->getOs('name') . ' ' . $dd->getOs('version') ?: null,
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

        // Try to find user by email from credentials if user object not available
        $userId = null;
        if ($event->user) {
            $userId = $event->user->id;
        } else {
            // Try to find user by email from credentials
            $credentials = $event->credentials;
            if (isset($credentials['email'])) {
                $user = DB::table('users')->where('email', $credentials['email'])->first();
                if ($user) {
                    $userId = $user->id;
                }
            }
        }

        // Skip if we still can't find a user (e.g., email doesn't exist)
        if (!$userId) {
            return;
        }

        // Record failed login attempt
        try {
            DB::table('login_attempts')->insert([
                'user_id' => $userId,
                'session_id' => session()->getId(),
                'ip_address' => request()->ip(),
                'attempted_at' => now(),
                'successful' => false,
                'user_agent' => request()->userAgent(),
                'hardware' => $deviceInfo['hardware'],
                'browser' => $deviceInfo['browser'],
                'os' => $deviceInfo['os'],
                'screen_resolution' => $deviceInfo['screen_resolution'],
                'battery_usage' => $deviceInfo['battery_usage'],
                'device_memory' => $deviceInfo['device_memory'],
                'browser_plugins' => $deviceInfo['browser_plugins'] ? json_encode($deviceInfo['browser_plugins']) : null,
                'browser_settings' => json_encode($deviceInfo['browser_settings']),
                'webgl_parameters' => $deviceInfo['webgl_parameters'] ? json_encode($deviceInfo['webgl_parameters']) : null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Exception $e) {
            // Log the error but don't prevent login
            \Log::error('Failed to record failed login attempt: ' . $e->getMessage());
        }
    }
}