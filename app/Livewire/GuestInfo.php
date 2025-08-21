<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class GuestInfo extends Component
{
    public $user;

    public function mount()
    {
        $this->user = Auth::user() ?? new \App\Models\User([
            'first_name' => 'Guest',
            'last_name' => '',
            'email' => 'N/A',
            'bio' => 'Not provided',
            'phone' => 'Not provided',
            'birthday' => null,
            'gender' => 'Not provided',
            'occupation' => 'Not provided',
            'mailing_address' => [],
            'billing_address' => [],
            'social_media' => [],
        ]);
    }

    public function render()
    {
        return view('livewire.dashboard.guest-info');
    }
}