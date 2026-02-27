<?php

namespace App\Livewire;

use DeviceDetector\DeviceDetector;
use DeviceDetector\Parser\Device\AbstractDeviceParser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class LoginHistory extends Component
{
    public $logins;

    public $totalAttempts;

    public $successfulAttempts;

    public $failedAttempts;

    public function mount(): void
    {
        $this->loadLoginHistory();
    }

    /**
     * Load login history from the radpostauth table for the
     * authenticated user. Matches on the user's email (username column).
     * Parses the extra JSON column for ip_address / user_agent and
     * uses DeviceDetector to extract device, OS, and browser info.
     * - BVSS LORD | 2026-02-27
     */
    public function loadLoginHistory(): void
    {
        $email = Auth::user()->email;

        // --- Fetch the 10 most recent entries for this user ---
        $rawLogins = DB::table('radpostauth')
            ->where('username', $email)
            ->latest('authdate')
            ->take(10)
            ->get();

        // --- Map radpostauth rows to the shape the blade template expects ---
        AbstractDeviceParser::setVersionTruncation(AbstractDeviceParser::VERSION_TRUNCATION_NONE);

        $this->logins = $rawLogins->map(function ($row) {
            $extra = json_decode($row->extra ?? '{}', true) ?: [];
            $userAgent = $extra['user_agent'] ?? null;

            // Parse device info from the stored user agent string - BVSS LORD | 2026-02-27
            $hardware = 'Unknown Device';
            $os = 'Unknown OS';
            $browser = 'Unknown Browser';

            if ($userAgent) {
                $dd = new DeviceDetector($userAgent);
                $dd->parse();
                $hardware = $dd->getDeviceName() ?: 'Unknown Device';
                $os = trim(($dd->getOs('name') ?: '').' '.($dd->getOs('version') ?: '')) ?: 'Unknown OS';
                $browser = trim(($dd->getClient('name') ?: '').' '.($dd->getClient('version') ?: '')) ?: 'Unknown Browser';
            }

            return (object) [
                'id' => $row->id,
                'ip_address' => $extra['ip_address'] ?? $row->ip_address ?? 'N/A',
                'attempted_at' => $row->authdate,
                'successful' => strtolower($row->pass) === 'accept',
                'user_agent' => $userAgent,
                'hardware' => $hardware,
                'os' => $os,
                'browser' => $browser,
            ];
        });

        // --- Summary counts across ALL rows, not just top 10 - BVSS LORD | 2026-02-27 ---
        $this->totalAttempts = DB::table('radpostauth')
            ->where('username', $email)
            ->count();

        $this->successfulAttempts = DB::table('radpostauth')
            ->where('username', $email)
            ->where('pass', 'Accept')
            ->count();

        $this->failedAttempts = $this->totalAttempts - $this->successfulAttempts;
    }

    public function render()
    {
        return view('livewire.dashboard.login-history');
    }
}
