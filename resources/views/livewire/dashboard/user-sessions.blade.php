<div>
    @if (session('message'))
        <div class="bg-green-500 text-white p-2 rounded mb-4">{{ session('message') }}</div>
    @endif

    @if($hasNewSchema)
        <div class="mb-6 grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white dark:bg-zinc-900 p-4 rounded-lg shadow">
                <h3 class="text-lg font-semibold mb-2 text-gray-800 dark:text-gray-200">Active Sessions</h3>
                <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $activeDevices }}</p>
            </div>
            <div class="bg-white dark:bg-zinc-900 p-4 rounded-lg shadow">
                <h3 class="text-lg font-semibold mb-2 text-gray-800 dark:text-gray-200">Total Data Used</h3>
                <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ number_format($totalDataUsed / 1024 / 1024, 2) }} MB</p>
            </div>
            <div class="bg-white dark:bg-zinc-900 p-4 rounded-lg shadow">
                <h3 class="text-lg font-semibold mb-2 text-gray-800 dark:text-gray-200">Total Sessions</h3>
                <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $sessions->count() }}</p>
            </div>
        </div>
    @endif

    @if ($sessions->isEmpty())
        <p class="text-sm text-gray-500 dark:text-gray-400">No sessions found.</p>
    @else
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-100 dark:bg-zinc-800">
                    <th class="text-left p-2 text-gray-800 dark:text-gray-200">Device</th>
                    <th class="text-left p-2 text-gray-800 dark:text-gray-200">IP Address</th>
                    @if($hasNewSchema)
                        <th class="text-left p-2 text-gray-800 dark:text-gray-200">Connection</th>
                        <th class="text-left p-2 text-gray-800 dark:text-gray-200">Data Used</th>
                    @endif
                    <th class="text-left p-2 text-gray-800 dark:text-gray-200">Last Activity</th>
                    <th class="text-left p-2 text-gray-800 dark:text-gray-200">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($sessions as $session)
                    <tr wire:key="{{ $session->id }}" class="border-b dark:border-zinc-700">
                        <td class="p-2">
                            @if($hasNewSchema)
                                <div class="font-medium text-gray-900 dark:text-gray-100">{{ $session->device_name ?? 'Unnamed Device' }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">{{ $session->device_id }}</div>
                            @else
                                <div class="text-xs text-gray-500 dark:text-gray-400">{{ $session->user_agent }}</div>
                            @endif
                        </td>
                        <td class="p-2 text-gray-900 dark:text-gray-100">{{ $session->ip_address }}</td>
                        @if($hasNewSchema)
                            <td class="p-2">
                                <div class="text-gray-900 dark:text-gray-100">{{ $session->connection_type ?? 'Unknown' }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">{{ $session->connection_speed ?? 'N/A' }}</div>
                            </td>
                            <td class="p-2 text-gray-900 dark:text-gray-100">{{ number_format($session->data_used / 1024 / 1024, 2) }} MB</td>
                        @endif
                        <td class="p-2 text-gray-900 dark:text-gray-100">{{ \Carbon\Carbon::parse($session->last_activity)->diffForHumans() }}</td>
                        <td class="p-2">
                            <button wire:click="endSession('{{ $session->id }}')" 
                                    class="bg-red-500 text-white px-3 py-1 rounded text-sm hover:bg-red-600 dark:hover:bg-red-700">
                                End Session
                            </button>
                            @if($hasNewSchema)
                                <button wire:click="$dispatch('open-modal', { component: 'rename-device', sessionId: '{{ $session->id }}' })"
                                        class="bg-blue-500 text-white px-3 py-1 rounded text-sm hover:bg-blue-600 dark:hover:bg-blue-700 ml-2">
                                    Rename
                                </button>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>