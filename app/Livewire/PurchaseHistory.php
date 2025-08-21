<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PurchaseHistory extends Component
{
    public $purchases;

    public function mount()
    {
        $this->purchases = DB::table('purchases')
            ->where('user_id', Auth::id())
            ->latest('purchased_at')
            ->take(5)
            ->get(['id', 'package_name', 'amount', 'purchased_at', 'payment_status']);
    }

    public function render()
    {
        return view('livewire.dashboard.purchase-history');
    }
}