<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LoginHistory extends Component
{
    public $logins;
    public $totalAttempts;
    public $successfulAttempts;
    public $failedAttempts;
    
    public function mount()
    {
        $this->loadLoginHistory();
    }

    public function loadLoginHistory()
    {
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