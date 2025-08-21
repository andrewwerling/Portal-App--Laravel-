<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
            @if(config('app.env') !== 'production')
            <span class="ml-2 text-sm text-gray-500 dark:text-gray-400">({{ auth()->user()->account_level }})</span>
            @endif
        </h2>
    </x-slot>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-12 pb-16">
        <!-- Standard Dashboard Content (visible to all) -->
        <div class="grid lg:grid-cols-2 gap-8">
            <!-- Left Column -->
            <div class="grid gap-8 auto-rows-min">
                <x-card title="Purchase History" :collapsed="true">
                    <livewire:purchase-history />
                </x-card>

                <x-card title="User Sessions" :collapsed="true">
                    <livewire:user-sessions />
                </x-card>

                <x-card title="Your Profile" :collapsed="true">
                    <livewire:guest-info />
                </x-card>
            </div>

            <!-- Right Column -->
            <div class="grid gap-8 auto-rows-min">
                <x-card title="Active Devices" :collapsed="true">
                    <livewire:active-devices />
                </x-card>

                <x-card title="Login History/Attempts" :collapsed="true">
                    <livewire:login-history />
                </x-card>
            </div>
        </div>
    </div>
</x-app-layout>
