<div>
    <div class="mb-6 grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-zinc-900 p-4 rounded-lg shadow">
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Users</h3>
            <p class="text-2xl font-bold text-purple-600 dark:text-purple-400">{{ $userCount }}</p>
        </div>
        <div class="bg-white dark:bg-zinc-900 p-4 rounded-lg shadow">
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Active Sessions</h3>
            <p class="text-2xl font-bold text-purple-600 dark:text-purple-400">{{ $activeSessionsCount }}</p>
        </div>
        <div class="bg-white dark:bg-zinc-900 p-4 rounded-lg shadow">
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Database Size</h3>
            <p class="text-2xl font-bold text-purple-600 dark:text-purple-400">{{ $databaseSize }}</p>
        </div>
        <div class="bg-white dark:bg-zinc-900 p-4 rounded-lg shadow">
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Environment</h3>
            <p class="text-2xl font-bold text-purple-600 dark:text-purple-400">{{ $environment }}</p>
        </div>
    </div>

    <div class="bg-white dark:bg-zinc-900 p-6 rounded-lg shadow mb-6">
        <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-200">System Information</h3>
        <div class="grid md:grid-cols-2 gap-4">
            <div class="p-4 bg-gray-50 dark:bg-zinc-800 rounded-lg">
                <div class="flex justify-between items-center">
                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Laravel Version</span>
                    <span class="text-sm font-medium text-gray-800 dark:text-gray-200">{{ $appVersion }}</span>
                </div>
            </div>
            <div class="p-4 bg-gray-50 dark:bg-zinc-800 rounded-lg">
                <div class="flex justify-between items-center">
                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">PHP Version</span>
                    <span class="text-sm font-medium text-gray-800 dark:text-gray-200">{{ $phpVersion }}</span>
                </div>
            </div>
            <div class="p-4 bg-gray-50 dark:bg-zinc-800 rounded-lg">
                <div class="flex justify-between items-center">
                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Debug Mode</span>
                    <span class="text-sm font-medium {{ $debugMode ? 'text-yellow-600 dark:text-yellow-400' : 'text-green-600 dark:text-green-400' }}">
                        {{ $debugMode ? 'Enabled' : 'Disabled' }}
                    </span>
                </div>
            </div>
            <div class="p-4 bg-gray-50 dark:bg-zinc-800 rounded-lg">
                <div class="flex justify-between items-center">
                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Cache Driver</span>
                    <span class="text-sm font-medium text-gray-800 dark:text-gray-200">{{ config('cache.default') }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-zinc-900 p-6 rounded-lg shadow">
        <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-200">Server Status</h3>
        <div class="flex items-center p-4 bg-green-50 dark:bg-green-900/20 rounded-lg">
            <div class="flex-shrink-0 w-3 h-3 bg-green-500 rounded-full mr-3"></div>
            <div>
                <p class="text-sm font-medium text-green-800 dark:text-green-200">All systems operational</p>
                <p class="text-xs text-green-700 dark:text-green-300 mt-1">Last checked: {{ now()->format('M d, Y H:i') }}</p>
            </div>
        </div>
    </div>
</div>
