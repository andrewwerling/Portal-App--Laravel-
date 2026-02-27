<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
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
     * Load login history for the authenticated user.
     * Gracefully returns empty data if the login_attempts table
     * has not been created yet (feature not deployed).
     * - BVSS LORD | 2026-02-27
     */
    public function loadLoginHistory(): void
    {
        if (! Schema::hasTable('login_attempts')) {
            $this->logins = collect();
            $this->totalAttempts = 0;
            $this->successfulAttempts = 0;
            $this->failedAttempts = 0;

            return;
        }

        $this->logins = DB::table('login_attempts')
            ->where('user_id', Auth::id())
            ->latest('attempted_at')
            ->take(10)
            ->get();

        $this->totalAttempts = DB::table('login_attempts')
            ->where('user_id', Auth::id())
            ->count();

        $this->successfulAttempts = DB::table('login_attempts')
            ->where('user_id', Auth::id())
            ->where('successful', true)
            ->count();

        $this->failedAttempts = $this->totalAttempts - $this->successfulAttempts;
    }

    public function render()
    {
        return view('livewire.dashboard.login-history');
    }
}
