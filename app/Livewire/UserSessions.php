<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use DeviceDetector\DeviceDetector;
use DeviceDetector\Parser\Device\AbstractDeviceParser;

class UserSessions extends Component
{
    public $sessions;
    public $activeSessions;
    public $totalDataUsed;
    public $activeDevices;
    public $hasNewSchema = false;

    public function mount()
    {
        // Check if the new schema exists
        $this->hasNewSchema = Schema::hasColumn('user_sessions', 'device_id');
        $this->loadSessions();
    }

    public function loadSessions()
    {
        // Prioritize RADIUS accounting table (radacct)
        $this->sessions = DB::table('radacct')
            ->where('username', Auth::user()->email)
            ->orderBy('acctstarttime', 'desc')
            ->get()
            ->map(function ($session) {
                // Transform radacct data to match existing session structure
                return (object)[
                    'id' => $session->radacctid,
                    'device_id' => $session->callingstationid,
                    'ip_address' => $session->framedipaddress,
                    'connection_type' => $session->nasporttype,
                    'connection_speed' => null, // RADIUS may not directly provide this
                    'data_used' => $session->acctinputoctets + $session->acctoutputoctets,
                    'started_at' => $session->acctstarttime,
                    'last_activity' => $session->acctupdatetime,
                    'ended_at' => $session->acctstoptime,
                    'is_active' => $session->acctstoptime === null,
                    'user_agent' => $session->connectinfo_start ? 
                        json_decode($session->connectinfo_start, true)['user_agent'] ?? null : null
                ];
            });

        // Filter active sessions
        $this->activeSessions = $this->sessions->where('is_active', true);
        
        // Calculate total data used (in bytes)
        $this->totalDataUsed = $this->sessions->sum('data_used');
        
        // Count active devices
        $this->activeDevices = $this->activeSessions->count();
    }

    public function endSession($sessionId)
    {
        // End session in RADIUS accounting table
        DB::table('radacct')
            ->where('radacctid', $sessionId)
            ->update([
                'acctstoptime' => now(),
                'acctsessiontime' => DB::raw('TIMESTAMPDIFF(SECOND, acctstarttime, NOW())'),
                'acctterminatecause' => 'User-Request'
            ]);

        // Reload sessions after ending
        $this->loadSessions();
        
        session()->flash('message', 'Session ended successfully.');
    }

    public function renameDevice($sessionId, $newName)
    {
        // Since RADIUS tables don't directly support device naming,
        // we'll store this in a custom mapping table
        DB::table('device_mappings')->updateOrInsert(
            [
                'user_id' => Auth::id(),
                'device_id' => $sessionId
            ],
            [
                'device_name' => $newName,
                'updated_at' => now()
            ]
        );

        $this->loadSessions();
        session()->flash('message', 'Device renamed successfully.');
    }

    public function render()
    {
        return view('livewire.dashboard.user-sessions');
    }
}