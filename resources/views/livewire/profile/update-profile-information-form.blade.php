<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;

new class extends Component
{
    public string $first_name = '';
    public string $last_name = '';
    public string $email = '';
    public ?string $phone = null;
    public ?string $birthday = null;
    public ?string $gender = null;
    public ?string $bio = null;
    public array $social_media = [];
    public ?string $occupation = null;
    public array $mailing_address = [];
    public array $billing_address = [];

    public function mount(): void
    {
        $user = Auth::user();
        $this->first_name = $user->first_name;
        $this->last_name = $user->last_name;
        $this->email = $user->email;
        $this->phone = $user->phone;
        $this->birthday = $user->birthday?->format('Y-m-d');
        $this->gender = $user->gender;
        $this->bio = $user->bio;
        $this->social_media = $user->social_media ?? [];
        $this->occupation = $user->occupation;
        $this->mailing_address = $user->mailing_address ?? [];
        $this->billing_address = $user->billing_address ?? [];
    }

    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:20'],
            'birthday' => ['nullable', 'date'],
            'gender' => ['nullable', 'in:male,female,non-binary,other,prefer-not-to-say'],
            'bio' => ['nullable', 'string', 'max:500'],
            'social_media.twitter' => ['nullable', 'url', 'max:255'],
            'social_media.facebook' => ['nullable', 'url', 'max:255'],
            'social_media.instagram' => ['nullable', 'url', 'max:255'],
            'social_media.tiktok' => ['nullable', 'url', 'max:255'],
            'social_media.linkedin' => ['nullable', 'url', 'max:255'],
            'occupation' => ['nullable', 'string', 'max:255'],
            'mailing_address.street' => ['nullable', 'string', 'max:255'],
            'mailing_address.city' => ['nullable', 'string', 'max:255'],
            'mailing_address.state' => ['nullable', 'string', 'max:10'],
            'mailing_address.postal_code' => ['nullable', 'string', 'max:10'],
            'mailing_address.country' => ['nullable', 'string', 'max:2'],
            'billing_address.street' => ['nullable', 'string', 'max:255'],
            'billing_address.city' => ['nullable', 'string', 'max:255'],
            'billing_address.state' => ['nullable', 'string', 'max:10'],
            'billing_address.postal_code' => ['nullable', 'string', 'max:10'],
            'billing_address.country' => ['nullable', 'string', 'max:2'],
        ]);

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        $this->dispatch('profile-updated', 
            first_name: $user->first_name,
            last_name: $user->last_name
        );
    }

    public function sendVerification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));
            return;
        }

        $user->sendEmailVerificationNotification();
        Session::flash('status', 'verification-link-sent');
    }
}; ?>

<section class="max-w-7xl mx-auto px-5">
    <header class="flex flex-col items-start gap-6 bg-white dark:bg-zinc-900 p-4 sm:p-6 rounded-lg shadow-[0px_14px_34px_0px_rgba(0,0,0,0.08)] ring-1 ring-white/[0.05] dark:ring-zinc-800">
        <div class="flex items-center mt-3 text-xl font-black text-gray-800 dark:text-gray-200 leading-tight">
            {{ auth()->user()->first_name }} {{ auth()->user()->last_name }}'s {{ __('Profile Information') }}
        </div>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ __("Update your account's information here.") }}</p>
    </header>

    <form wire:submit="updateProfileInformation" class="mt-6">
        <div class="space-y-8">
            <!-- About Section -->
            <x-card title="{{ __('About') }}" :collapsed="true">
                <x-profile.about-section
                    :first_name="$first_name"
                    :last_name="$last_name"
                    :birthday="$birthday"
                    :gender="$gender"
                    :occupation="$occupation"
                    :bio="$bio"
                />
                <div class="flex items-center gap-4 mt-6">
                    <x-primary-button>{{ __('Save') }}</x-primary-button>
                    <x-action-message class="me-3" on="profile-updated">{{ __('Saved.') }}</x-action-message>
                </div>
            </x-card>

            <!-- Contact Section -->
            <x-card title="{{ __('Contact') }}" :collapsed="true">
                <x-profile.contact-section
                    :email="$email"
                    :phone="$phone"
                    :social_media="$social_media"
                />
                <div class="flex items-center gap-4 mt-6">
                    <x-primary-button>{{ __('Save') }}</x-primary-button>
                    <x-action-message class="me-3" on="profile-updated">{{ __('Saved.') }}</x-action-message>
                </div>
            </x-card>

            <!-- Mailing Address Section -->
            <x-card title="Mailing {{ __('Address') }}" :collapsed="true">
                <x-profile.mailing-address-section :mailing_address="$mailing_address" />
                <div class="flex items-center gap-4 mt-6">
                    <x-primary-button>{{ __('Save') }}</x-primary-button>
                    <x-action-message class="me-3" on="profile-updated">{{ __('Saved.') }}</x-action-message>
                </div>
            </x-card>

            <!-- Billing Address Section -->
            <x-card title="Billing {{ __('Address') }}" :collapsed="true">
                <x-profile.billing-address-section :billing_address="$billing_address" />
                <div class="flex items-center gap-4 mt-6">
                    <x-primary-button>{{ __('Save') }}</x-primary-button>
                    <x-action-message class="me-3" on="profile-updated">{{ __('Saved.') }}</x-action-message>
                </div>
            </x-card>
        </div>
    </form>
</section>