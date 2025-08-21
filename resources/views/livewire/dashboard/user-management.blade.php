<div>
    <div class="mb-6 grid grid-cols-1 md:grid-cols-5 gap-4">
        <div class="bg-white dark:bg-zinc-900 p-4 rounded-lg shadow">
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Users</h3>
            <p class="text-2xl font-bold text-purple-600 dark:text-purple-400">{{ $totalUsers }}</p>
        </div>
        <div class="bg-white dark:bg-zinc-900 p-4 rounded-lg shadow">
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Super Admins</h3>
            <p class="text-2xl font-bold text-purple-600 dark:text-purple-400">{{ $accountLevels['super-admin'] }}</p>
        </div>
        <div class="bg-white dark:bg-zinc-900 p-4 rounded-lg shadow">
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Admins</h3>
            <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $accountLevels['admin'] }}</p>
        </div>
        <div class="bg-white dark:bg-zinc-900 p-4 rounded-lg shadow">
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Managers</h3>
            <p class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $accountLevels['manager'] }}</p>
        </div>
        <div class="bg-white dark:bg-zinc-900 p-4 rounded-lg shadow">
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Regular Users</h3>
            <p class="text-2xl font-bold text-gray-600 dark:text-gray-400">{{ $accountLevels['user'] + $accountLevels['guest'] }}</p>
        </div>
    </div>

    <div class="bg-white dark:bg-zinc-900 p-6 rounded-lg shadow mb-6">
        <div class="flex flex-col md:flex-row justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4 md:mb-0">User Management</h3>
            <div class="flex flex-col md:flex-row space-y-2 md:space-y-0 md:space-x-2 w-full md:w-auto">
                <div class="relative">
                    <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search users..." 
                           class="p-2 pl-8 w-full md:w-64 border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-zinc-700 dark:text-gray-300 focus:border-purple-500 dark:focus:border-purple-600 focus:ring-purple-500 dark:focus:ring-purple-600 rounded-md shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
                <select wire:model.live="accountLevel" class="p-2 border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-zinc-700 dark:text-gray-300 focus:border-purple-500 dark:focus:border-purple-600 focus:ring-purple-500 dark:focus:ring-purple-600 rounded-md shadow-sm">
                    <option value="">All Levels</option>
                    <option value="super-admin">Super Admin</option>
                    <option value="admin">Admin</option>
                    <option value="manager">Manager</option>
                    <option value="user">User</option>
                    <option value="guest">Guest</option>
                </select>
                <select wire:model.live="perPage" class="p-2 border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-zinc-700 dark:text-gray-300 focus:border-purple-500 dark:focus:border-purple-600 focus:ring-purple-500 dark:focus:ring-purple-600 rounded-md shadow-sm">
                    <option value="10">10 per page</option>
                    <option value="25">25 per page</option>
                    <option value="50">50 per page</option>
                    <option value="100">100 per page</option>
                </select>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-zinc-800">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">User</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Email</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Account Level</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Verified</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-zinc-900 divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach ($users as $user)
                        <tr wire:key="{{ $user->id }}">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                                        <span class="text-gray-500 dark:text-gray-300 font-medium">{{ substr($user->first_name, 0, 1) . substr($user->last_name, 0, 1) }}</span>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $user->first_name }} {{ $user->last_name }}</div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">ID: {{ $user->id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-gray-100">{{ $user->email }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $user->account_level === 'super-admin' ? 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200' : '' }}
                                    {{ $user->account_level === 'admin' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' : '' }}
                                    {{ $user->account_level === 'manager' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : '' }}
                                    {{ $user->account_level === 'user' ? 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200' : '' }}
                                    {{ $user->account_level === 'guest' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : '' }}
                                ">
                                    {{ ucfirst($user->account_level) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $user->email_verified_at ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                                    {{ $user->email_verified_at ? 'Yes' : 'No' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <button class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 mr-3">Edit</button>
                                <button class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">Delete</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $users->links() }}
        </div>
    </div>
</div>
