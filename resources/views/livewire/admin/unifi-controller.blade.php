<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Unifi Controller') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-zinc-800 overflow-hidden shadow-sm sm:rounded-lg grid grid-cols-1 md:grid-cols-2 gap-6 p-6">
                <!-- First Column: Input Form -->
                <div class="p-6 bg-white dark:bg-zinc-900 rounded-lg shadow">
                    <h2 class="text-2xl font-bold mb-4 text-gray-800 dark:text-gray-200">Unifi Controller Login</h2>
                    <form wire:submit.prevent="loginToUnifi">
                        <div class="mb-4">
                            <label for="unifiUsername" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Username</label>
                            <input type="text" wire:model="unifiUsername" id="unifiUsername" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500 dark:bg-zinc-800 dark:text-white">
                            @error('unifiUsername') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-4">
                            <label for="unifiPassword" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Password</label>
                            <input type="password" wire:model="unifiPassword" id="unifiPassword" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500 dark:bg-zinc-800 dark:text-white">
                            @error('unifiPassword') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-4">
                            <label for="unifiControllerUrl" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Controller URL</label>
                            <input type="text" wire:model="unifiControllerUrl" id="unifiControllerUrl" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500 dark:bg-zinc-800 dark:text-white">
                            @error('unifiControllerUrl') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <button type="submit" class="bg-purple-600 text-white py-2 px-4 rounded-md hover:bg-purple-700">Login to Unifi Controller</button>
                    </form>
                </div>
                <!-- Second Column: Output Display -->
                <div class="p-6 bg-white dark:bg-zinc-900 rounded-lg shadow">
                    <h2 class="text-2xl font-bold mb-4 text-gray-800 dark:text-gray-200">API Output</h2>
                    @if(session()->has('message'))
                        <div class="mb-4 p-4 bg-green-100 dark:bg-green-900 rounded">
                            {{ session('message') }}
                        </div>
                    @endif
                    @if(session()->has('error'))
                        <div class="mb-4 p-4 bg-red-100 dark:bg-red-900 rounded">
                            {{ session('error') }}
                        </div>
                    @endif
                    @if($apiResults && count($apiResults) > 0)
                        <div class="overflow-x-auto">
                            <pre class="bg-gray-100 dark:bg-zinc-800 p-4 rounded">{{ json_encode($apiResults, JSON_PRETTY_PRINT) }}</pre>
                        </div>
                    @else
                        <p class="text-gray-600 dark:text-gray-400">No results yet. Submit the form to fetch data.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>