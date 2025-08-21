<?php

namespace App\Livewire\Profile;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use App\Models\User;

class DeleteUserForm extends Component
{
    public bool $confirmingUserDeletion = false;
    public string $password = '';

    public function confirmUserDeletion(): void
    {
        $this->confirmingUserDeletion = true;
    }

    public function deleteUser(): void
    {
        $this->validate([
            'password' => ['required', 'string', 'current_password'],
        ]);

        $userId = Auth::id();
        Auth::logout();
        User::destroy($userId);

        $this->redirect('/', navigate: true);
    }

    public function render()
    {
        return view('livewire.profile.delete-user-form');
    }
} 