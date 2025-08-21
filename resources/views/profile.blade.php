<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">{{ __('Profile') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-3">
            <div class="grid lg:grid-cols-2 gap-8">
                <!-- Left Column -->
                <div class="space-y-6">
                    <livewire:profile.update-profile-information-form />
                </div>

                <!-- Right Column -->
                <div class="space-y-6 max-w-7xl px-5">
                    <livewire:profile.update-password-form />
                    <livewire:profile.delete-user-form />
                </div>
            </div>
        </div>
    </div>
</x-app-layout>