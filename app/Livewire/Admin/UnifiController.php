<?php

namespace App\Livewire\Admin;

use Livewire\Attributes\Layout;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use UniFi_API\Client;

#[Layout('layouts.app')]
class UnifiController extends Component
{
    public $unifiUsername;
    public $unifiPassword;
    public $unifiControllerUrl;  // e.g., for the Unifi controller instance
    public $apiResults = [];  // Property to store API results for the view

    public function mount()
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized');
        }
    }

    public function render()
    {
        return view('livewire.admin.unifi-controller');
    }

    public function loginToUnifi()
    {
        $this->validate([
            'unifiUsername' => 'required',
            'unifiPassword' => 'required',
            'unifiControllerUrl' => 'required|url',
        ]);

        $unifi_connection = new Client(
            $this->unifiUsername,
            $this->unifiPassword,
            $this->unifiControllerUrl,
            'default',  // Site ID; adjust as needed, e.g., from config or user input
            'v5_12_55',  // Controller version; adjust based on your setup
            true  // Debug mode; set to false in production
        );

        $login = $unifi_connection->login();

        if ($login) {  // Check if login was successful
            $this->apiResults = $unifi_connection->list_alarms();  // Fetch and store results
            session()->flash('message', 'Successfully logged in and fetched alarms.');
        } else {
            $this->apiResults = [];  // Clear or set error state
            session()->flash('error', 'Login to Unifi Controller failed.');
        }
    }
}