<div>
    <div class="mb-6 grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white dark:bg-zinc-900 p-4 rounded-lg shadow">
            <h3 class="text-lg font-semibold mb-2 text-gray-800 dark:text-gray-200">Total Login Attempts</h3>
            <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $totalAttempts }}</p>
        </div>
        <div class="bg-white dark:bg-zinc-900 p-4 rounded-lg shadow">
            <h3 class="text-lg font-semibold mb-2 text-gray-800 dark:text-gray-200">Successful Logins</h3>
            <p class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $successfulAttempts }}</p>
        </div>
        <div class="bg-white dark:bg-zinc-900 p-4 rounded-lg shadow">
            <h3 class="text-lg font-semibold mb-2 text-gray-800 dark:text-gray-200">Failed Attempts</h3>
            <p class="text-2xl font-bold text-red-600 dark:text-red-400">{{ $failedAttempts }}</p>
        </div>
    </div>

    @if ($logins->isEmpty())
        <p class="text-sm text-gray-500 dark:text-gray-400">No login attempts found.</p>
    @else
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-100 dark:bg-zinc-800">
                    <th class="text-left p-2 text-gray-800 dark:text-gray-200">Device</th>
                    <th class="text-left p-2 text-gray-800 dark:text-gray-200">IP Address</th>
                    <th class="text-left p-2 text-gray-800 dark:text-gray-200">Attempted</th>
                    <th class="text-left p-2 text-gray-800 dark:text-gray-200">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($logins as $login)
                    <tr wire:key="{{ $login->id }}" class="border-b dark:border-zinc-700">
                        <td class="p-2">
                            <div class="font-medium text-gray-900 dark:text-gray-100">{{ $login->hardware ?? 'Unknown Device' }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                {{ $login->os ?? 'Unknown OS' }}, {{ $login->browser ?? 'Unknown Browser' }}
                            </div>
                        </td>
                        <td class="p-2 text-gray-900 dark:text-gray-100">{{ $login->ip_address }}</td>
                        <td class="p-2 text-gray-900 dark:text-gray-100">{{ \Carbon\Carbon::parse($login->attempted_at)->format('M j, Y H:i') }}</td>
                        <td class="p-2">
                            <span class="px-2 py-1 rounded-full text-xs {{ $login->successful ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' }}">
                                {{ $login->successful ? 'Success' : 'Failed' }}
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>