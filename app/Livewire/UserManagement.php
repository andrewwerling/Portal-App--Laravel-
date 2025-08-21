<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use Livewire\WithPagination;

class UserManagement extends Component
{
    use WithPagination;
    
    public $search = '';
    public $accountLevel = '';
    public $perPage = 10;
    
    public function updatingSearch()
    {
        $this->resetPage();
    }
    
    public function updatingAccountLevel()
    {
        $this->resetPage();
    }
    
    public function mount()
    {
        // Initialize component
    }
    
    public function getUsers()
    {
        $query = User::query();
        
        if ($this->search) {
            $query->where(function($q) {
                $q->where('first_name', 'like', '%' . $this->search . '%')
                  ->orWhere('last_name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%');
            });
        }
        
        if ($this->accountLevel) {
            $query->where('account_level', $this->accountLevel);
        }
        
        return $query->orderBy('id', 'desc')->paginate($this->perPage);
    }
    
    public function render()
    {
        return view('livewire.dashboard.user-management', [
            'users' => $this->getUsers(),
            'totalUsers' => User::count(),
            'accountLevels' => [
                'super-admin' => User::where('account_level', 'super-admin')->count(),
                'admin' => User::where('account_level', 'admin')->count(),
                'manager' => User::where('account_level', 'manager')->count(),
                'user' => User::where('account_level', 'user')->count(),
                'guest' => User::where('account_level', 'guest')->count(),
            ]
        ]);
    }
}
