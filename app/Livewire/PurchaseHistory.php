<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Livewire\Component;

class PurchaseHistory extends Component
{
    public $purchases;

    /**
     * Load recent purchases for the authenticated user.
     * Gracefully returns an empty collection if the purchases table
     * has not been created yet (feature not deployed).
     * - BVSS LORD | 2026-02-27
     */
    public function mount(): void
    {
        if (! Schema::hasTable('purchases')) {
            $this->purchases = collect();

            return;
        }

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
