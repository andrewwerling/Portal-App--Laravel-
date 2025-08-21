<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class SystemOverview extends Component
{
    public $userCount;
    public $activeSessionsCount;
    public $databaseSize;
    public $appVersion;
    public $phpVersion;
    public $environment;
    public $debugMode;
    
    public function mount()
    {
        $this->userCount = User::count();
        $this->activeSessionsCount = DB::table('user_sessions')
            ->where('is_active', true)
            ->count();
        $this->databaseSize = '~25.4 MB'; // This would typically be calculated dynamically
        $this->appVersion = app()->version();
        $this->phpVersion = phpversion();
        $this->environment = app()->environment();
        $this->debugMode = config('app.debug');
    }

    public function render()
    {
        return view('livewire.dashboard.system-overview');
    }
}
