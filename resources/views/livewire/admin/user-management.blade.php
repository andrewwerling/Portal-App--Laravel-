
<div>
    @if (session('message'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
            <p>{{ session('message') }}</p>
        </div>
    @endif

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-zinc-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="mb-4 flex flex-col md:flex-row justify-between items-start md:items-center gap-2">
                        <div class="w-full md:w-1/3">
                            <div class="flex flex-col space-y-2">
                                <div class="flex items-center">
                                    <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search users..."
                                           class="w-full p-2.5 border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-zinc-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-gray-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                    <button wire:click="toggleAdvancedSearch" class="ml-2 p-2.5 bg-gray-200 dark:bg-zinc-600 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-300 dark:hover:bg-zinc-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                                        </svg>
                                    </button>
                                </div>
                                
                                @if($advancedSearch)
                                <div class="bg-gray-50 dark:bg-zinc-700 p-3 rounded-md text-sm">
                                    <h4 class="font-medium mb-2 text-gray-700 dark:text-gray-300">Advanced Search Tips:</h4>
                                    <ul class="list-disc pl-5 text-gray-600 dark:text-gray-400 space-y-1">
                                        <li>Use <span class="font-mono bg-gray-200 dark:bg-zinc-600 px-1 rounded">-term</span> to exclude results containing "term"</li>
                                        <li>Use <span class="font-mono bg-gray-200 dark:bg-zinc-600 px-1 rounded">"exact phrase"</span> to search for exact phrases</li>
                                        <li>Search dates by <span class="font-mono bg-gray-200 dark:bg-zinc-600 px-1 rounded">year</span> (e.g., 2023), <span class="font-mono bg-gray-200 dark:bg-zinc-600 px-1 rounded">month</span> (e.g., January), or <span class="font-mono bg-gray-200 dark:bg-zinc-600 px-1 rounded">full date</span></li>
                                        <li>Multiple terms will search for records containing ALL terms</li>
                                        <li>Example: <span class="font-mono bg-gray-200 dark:bg-zinc-600 px-1 rounded">"John Doe" -manager 2023</span></li>
                                    </ul>
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open" class="p-2.5 bg-gray-200 dark:bg-zinc-600 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-300 dark:hover:bg-zinc-500 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-1.065-2.572c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-2.573-1.066c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.065-2.573c.94-1.543.826-3.31 2.37-2.37a1.724 1.724 0 002.572-1.065zM12 9v2m0 4h.01" />
                                    </svg>
                                    <span>Columns</span>
                                </button>
                                <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white dark:bg-zinc-800 rounded-md shadow-lg z-10">
                                    <div class="p-2 max-h-96 overflow-y-auto">
                                        <div class="mb-2 flex justify-between items-center">
                                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Toggle Columns</span>
                                            <button wire:click="resetColumnVisibility" class="text-xs text-blue-600 dark:text-blue-400 hover:underline">
                                                Reset
                                            </button>
                                        </div>
                                        <hr class="my-1 border-gray-200 dark:border-gray-700">
                                        
                                        <label class="flex items-center space-x-2 py-1">
                                            <input type="checkbox" wire:model.live="visibleColumns.id" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                            <span class="text-sm text-gray-700 dark:text-gray-300">ID</span>
                                        </label>
                                        <label class="flex items-center space-x-2 py-1">
                                            <input type="checkbox" wire:model.live="visibleColumns.name" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                            <span class="text-sm text-gray-700 dark:text-gray-300">Name</span>
                                        </label>
                                        <label class="flex items-center space-x-2 py-1">
                                            <input type="checkbox" wire:model.live="visibleColumns.email" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                            <span class="text-sm text-gray-700 dark:text-gray-300">Email</span>
                                        </label>
                                        <label class="flex items-center space-x-2 py-1">
                                            <input type="checkbox" wire:model.live="visibleColumns.phone" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                            <span class="text-sm text-gray-700 dark:text-gray-300">Phone</span>
                                        </label>
                                        <label class="flex items-center space-x-2 py-1">
                                            <input type="checkbox" wire:model.live="visibleColumns.birthday" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                            <span class="text-sm text-gray-700 dark:text-gray-300">Birthday</span>
                                        </label>
                                        <label class="flex items-center space-x-2 py-1">
                                            <input type="checkbox" wire:model.live="visibleColumns.gender" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                            <span class="text-sm text-gray-700 dark:text-gray-300">Gender</span>
                                        </label>
                                        <label class="flex items-center space-x-2 py-1">
                                            <input type="checkbox" wire:model.live="visibleColumns.bio" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                            <span class="text-sm text-gray-700 dark:text-gray-300">Bio</span>
                                        </label>
                                        <label class="flex items-center space-x-2 py-1">
                                            <input type="checkbox" wire:model.live="visibleColumns.social_media" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                            <span class="text-sm text-gray-700 dark:text-gray-300">Social Media</span>
                                        </label>
                                        <label class="flex items-center space-x-2 py-1">
                                            <input type="checkbox" wire:model.live="visibleColumns.occupation" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                            <span class="text-sm text-gray-700 dark:text-gray-300">Occupation</span>
                                        </label>
                                        <label class="flex items-center space-x-2 py-1">
                                            <input type="checkbox" wire:model.live="visibleColumns.mailing_address" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                            <span class="text-sm text-gray-700 dark:text-gray-300">Mailing Address</span>
                                        </label>
                                        <label class="flex items-center space-x-2 py-1">
                                            <input type="checkbox" wire:model.live="visibleColumns.billing_address" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                            <span class="text-sm text-gray-700 dark:text-gray-300">Billing Address</span>
                                        </label>
                                        <label class="flex items-center space-x-2 py-1">
                                            <input type="checkbox" wire:model.live="visibleColumns.email_verified_at" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                            <span class="text-sm text-gray-700 dark:text-gray-300">Email Verified</span>
                                        </label>
                                        <label class="flex items-center space-x-2 py-1">
                                            <input type="checkbox" wire:model.live="visibleColumns.account_level" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                            <span class="text-sm text-gray-700 dark:text-gray-300">Account Level</span>
                                        </label>
                                        <label class="flex items-center space-x-2 py-1">
                                            <input type="checkbox" wire:model.live="visibleColumns.provider" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                            <span class="text-sm text-gray-700 dark:text-gray-300">Provider</span>
                                        </label>
                                        <label class="flex items-center space-x-2 py-1">
                                            <input type="checkbox" wire:model.live="visibleColumns.provider_avatar" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                            <span class="text-sm text-gray-700 dark:text-gray-300">Provider Avatar</span>
                                        </label>
                                        <label class="flex items-center space-x-2 py-1">
                                            <input type="checkbox" wire:model.live="visibleColumns.created_at" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                            <span class="text-sm text-gray-700 dark:text-gray-300">Created At</span>
                                        </label>
                                        <label class="flex items-center space-x-2 py-1">
                                            <input type="checkbox" wire:model.live="visibleColumns.updated_at" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                            <span class="text-sm text-gray-700 dark:text-gray-300">Updated At</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <select wire:model.live="perPage" class="p-2.5 border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-zinc-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-gray-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                <option value="10">10 per page</option>
                                <option value="25">25 per page</option>
                                <option value="50">50 per page</option>
                                <option value="100">100 per page</option>
                            </select>
                        </div>
                    </div>

                    <!-- Bulk Actions -->
                    <div class="mb-4 flex items-center space-x-2">
                        <div class="relative inline-block text-left">
                            <select wire:model.live="bulkAction" class="p-2.5 border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-zinc-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-gray-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                <option value="">Bulk Actions</option>
                                <option value="account_level">Change Account Level</option>
                                <option value="delete">Delete Selected</option>
                            </select>
                        </div>

                        @if($bulkAction === 'account_level')
                            <div class="relative inline-block text-left">
                                <select wire:model.live="bulkAccountLevel" class="p-2.5 border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-zinc-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-gray-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                    <option value="">Select Level</option>
                                    <option value="guest">Guest</option>
                                    <option value="user">User</option>
                                    <option value="manager">Manager</option>
                                    <option value="admin">Admin</option>
                                    <option value="super-admin">Super Admin</option>
                                </select>
                            </div>
                        @endif

                        <button 
                            wire:click="applyBulkAction" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50"
                            @if(empty($selectedUsers) || (empty($bulkAction) || ($bulkAction === 'account_level' && empty($bulkAccountLevel)))) disabled @endif
                        >
                            Apply
                        </button>

                        <span class="text-sm text-gray-500 dark:text-gray-400">
                            {{ count($selectedUsers) }} users selected
                        </span>
                    </div>

                    <div class="overflow-x-auto bg-white dark:bg-zinc-800 rounded-lg shadow">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-zinc-700">
                                <tr>
                                    <!-- Checkbox column -->
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300">
                                        <input type="checkbox" wire:model.live="selectAll" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800">
                                    </th>
                                    
                                    <!-- ID column -->
                                    @if($visibleColumns['id'])
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer" wire:click="sortBy('id')">
                                        ID
                                        @if ($sortField === 'id')
                                            <span>{!! $sortDirection === 'asc' ? '&#8593;' : '&#8595;' !!}</span>
                                        @endif
                                    </th>
                                    @endif
                                    
                                    <!-- Name column (combines first_name and last_name) -->
                                    @if($visibleColumns['name'])
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer" wire:click="sortBy('first_name')">
                                        Name
                                        @if ($sortField === 'first_name')
                                            <span>{!! $sortDirection === 'asc' ? '&#8593;' : '&#8595;' !!}</span>
                                        @endif
                                    </th>
                                    @endif
                                    
                                    <!-- Email column -->
                                    @if($visibleColumns['email'])
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer" wire:click="sortBy('email')">
                                        Email
                                        @if ($sortField === 'email')
                                            <span>{!! $sortDirection === 'asc' ? '&#8593;' : '&#8595;' !!}</span>
                                        @endif
                                    </th>
                                    @endif
                                    
                                    <!-- Phone column -->
                                    @if($visibleColumns['phone'])
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer" wire:click="sortBy('phone')">
                                        Phone
                                        @if ($sortField === 'phone')
                                            <span>{!! $sortDirection === 'asc' ? '&#8593;' : '&#8595;' !!}</span>
                                        @endif
                                    </th>
                                    @endif
                                    
                                    <!-- Birthday column -->
                                    @if($visibleColumns['birthday'])
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer" wire:click="sortBy('birthday')">
                                        Birthday
                                        @if ($sortField === 'birthday')
                                            <span>{!! $sortDirection === 'asc' ? '&#8593;' : '&#8595;' !!}</span>
                                        @endif
                                    </th>
                                    @endif
                                    
                                    <!-- Gender column -->
                                    @if($visibleColumns['gender'])
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer" wire:click="sortBy('gender')">
                                        Gender
                                        @if ($sortField === 'gender')
                                            <span>{!! $sortDirection === 'asc' ? '&#8593;' : '&#8595;' !!}</span>
                                        @endif
                                    </th>
                                    @endif
                                    
                                    <!-- Bio column -->
                                    @if($visibleColumns['bio'])
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer" wire:click="sortBy('bio')">
                                        Bio
                                        @if ($sortField === 'bio')
                                            <span>{!! $sortDirection === 'asc' ? '&#8593;' : '&#8595;' !!}</span>
                                        @endif
                                    </th>
                                    @endif
                                    
                                    <!-- Social Media column -->
                                    @if($visibleColumns['social_media'])
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Social Media
                                    </th>
                                    @endif
                                    
                                    <!-- Occupation column -->
                                    @if($visibleColumns['occupation'])
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer" wire:click="sortBy('occupation')">
                                        Occupation
                                        @if ($sortField === 'occupation')
                                            <span>{!! $sortDirection === 'asc' ? '&#8593;' : '&#8595;' !!}</span>
                                        @endif
                                    </th>
                                    @endif
                                    
                                    <!-- Mailing Address column -->
                                    @if($visibleColumns['mailing_address'])
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Mailing Address
                                    </th>
                                    @endif
                                    
                                    <!-- Billing Address column -->
                                    @if($visibleColumns['billing_address'])
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Billing Address
                                    </th>
                                    @endif
                                    
                                    <!-- Email Verified column -->
                                    @if($visibleColumns['email_verified_at'])
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer" wire:click="sortBy('email_verified_at')">
                                        Email Verified
                                        @if ($sortField === 'email_verified_at')
                                            <span>{!! $sortDirection === 'asc' ? '&#8593;' : '&#8595;' !!}</span>
                                        @endif
                                    </th>
                                    @endif
                                    
                                    <!-- Account Level column -->
                                    @if($visibleColumns['account_level'])
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer" wire:click="sortBy('account_level')">
                                        Account Level
                                        @if ($sortField === 'account_level')
                                            <span>{!! $sortDirection === 'asc' ? '&#8593;' : '&#8595;' !!}</span>
                                        @endif
                                    </th>
                                    @endif
                                    
                                    <!-- Provider column -->
                                    @if($visibleColumns['provider'])
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer" wire:click="sortBy('provider')">
                                        Provider
                                        @if ($sortField === 'provider')
                                            <span>{!! $sortDirection === 'asc' ? '&#8593;' : '&#8595;' !!}</span>
                                        @endif
                                    </th>
                                    @endif
                                    
                                    <!-- Provider Avatar column -->
                                    @if($visibleColumns['provider_avatar'])
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Avatar
                                    </th>
                                    @endif
                                    
                                    <!-- Created At column -->
                                    @if($visibleColumns['created_at'])
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer" wire:click="sortBy('created_at')">
                                        Created
                                        @if ($sortField === 'created_at')
                                            <span>{!! $sortDirection === 'asc' ? '&#8593;' : '&#8595;' !!}</span>
                                        @endif
                                    </th>
                                    @endif
                                    
                                    <!-- Updated At column -->
                                    @if($visibleColumns['updated_at'])
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer" wire:click="sortBy('updated_at')">
                                        Updated
                                        @if ($sortField === 'updated_at')
                                            <span>{!! $sortDirection === 'asc' ? '&#8593;' : '&#8595;' !!}</span>
                                        @endif
                                    </th>
                                    @endif
                                    
                                    <!-- Actions column -->
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-zinc-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse ($users as $user)
                                    <tr wire:key="{{ $user->id }}">
                                        <!-- Checkbox column -->
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            <input type="checkbox" wire:model.live="selectedUsers" value="{{ $user->id }}" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800">
                                        </td>
                                        
                                        <!-- ID column -->
                                        @if($visibleColumns['id'])
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            {{ $user->id }}
                                        </td>
                                        @endif
                                        
                                        <!-- Name column (combines first_name and last_name) -->
                                        @if($visibleColumns['name'])
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            {{ $user->first_name }} {{ $user->last_name }}
                                        </td>
                                        @endif
                                        
                                        <!-- Email column -->
                                        @if($visibleColumns['email'])
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            {{ $user->email }}
                                        </td>
                                        @endif
                                        
                                        <!-- Phone column -->
                                        @if($visibleColumns['phone'])
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            {{ $user->phone ?? 'N/A' }}
                                        </td>
                                        @endif
                                        
                                        <!-- Birthday column -->
                                        @if($visibleColumns['birthday'])
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            {{ $user->birthday ? $user->birthday->format('M j, Y') : 'N/A' }}
                                        </td>
                                        @endif
                                        
                                        <!-- Gender column -->
                                        @if($visibleColumns['gender'])
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            {{ $user->gender ? ucfirst($user->gender) : 'N/A' }}
                                        </td>
                                        @endif
                                        
                                        <!-- Bio column -->
                                        @if($visibleColumns['bio'])
                                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100 max-w-xs truncate">
                                            {{ $user->bio ?? 'N/A' }}
                                        </td>
                                        @endif
                                        
                                        <!-- Social Media column -->
                                        @if($visibleColumns['social_media'])
                                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                            <div class="flex space-x-2">
                                                @if(isset($user->social_media['instagram']) && !empty($user->social_media['instagram']))
                                                    <a href="{{ $user->social_media['instagram'] }}" target="_blank" rel="noopener noreferrer" class="text-pink-600 hover:text-pink-800 dark:text-pink-400 dark:hover:text-pink-300" title="Instagram">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                                                        </svg>
                                                    </a>
                                                @endif
                                                
                                                @if(isset($user->social_media['facebook']) && !empty($user->social_media['facebook']))
                                                    <a href="{{ $user->social_media['facebook'] }}" target="_blank" rel="noopener noreferrer" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300" title="Facebook">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                                            <path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z"/>
                                                        </svg>
                                                    </a>
                                                @endif
                                                
                                                @if(isset($user->social_media['twitter']) && !empty($user->social_media['twitter']))
                                                    <a href="{{ $user->social_media['twitter'] }}" target="_blank" rel="noopener noreferrer" class="text-blue-400 hover:text-blue-600 dark:text-blue-300 dark:hover:text-blue-200" title="Twitter/X">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                                            <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                                                        </svg>
                                                    </a>
                                                @endif
                                                
                                                @if(isset($user->social_media['linkedin']) && !empty($user->social_media['linkedin']))
                                                    <a href="{{ $user->social_media['linkedin'] }}" target="_blank" rel="noopener noreferrer" class="text-blue-700 hover:text-blue-900 dark:text-blue-500 dark:hover:text-blue-400" title="LinkedIn">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                                            <path d="M4.98 3.5c0 1.381-1.11 2.5-2.48 2.5s-2.48-1.119-2.48-2.5c0-1.38 1.11-2.5 2.48-2.5s2.48 1.12 2.48 2.5zm.02 4.5h-5v16h5v-16zm7.982 0h-4.968v16h4.969v-8.399c0-4.67 6.029-5.052 6.029 0v8.399h4.988v-10.131c0-7.88-8.922-7.593-11.018-3.714v-2.155z"/>
                                                        </svg>
                                                    </a>
                                                @endif
                                                
                                                @if(isset($user->social_media['tiktok']) && !empty($user->social_media['tiktok']))
                                                    <a href="{{ $user->social_media['tiktok'] }}" target="_blank" rel="noopener noreferrer" class="text-black hover:text-gray-700 dark:text-white dark:hover:text-gray-300" title="TikTok">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                                            <path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/>
                                                        </svg>
                                                    </a>
                                                @endif
                                                
                                                @if(!isset($user->social_media) || (is_array($user->social_media) && empty(array_filter($user->social_media))))
                                                    <span class="text-gray-500 dark:text-gray-400">N/A</span>
                                                @endif
                                            </div>
                                        </td>
                                        @endif
                                        
                                        <!-- Occupation column -->
                                        @if($visibleColumns['occupation'])
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            {{ $user->occupation ?? 'N/A' }}
                                        </td>
                                        @endif
                                        
                                        <!-- Mailing Address column -->
                                        @if($visibleColumns['mailing_address'])
                                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100 max-w-xs truncate">
                                            {{ $user->mailing_address ? $this->formatJsonData($user->mailing_address) : 'N/A' }}
                                        </td>
                                        @endif
                                        
                                        <!-- Billing Address column -->
                                        @if($visibleColumns['billing_address'])
                                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100 max-w-xs truncate">
                                            {{ $user->billing_address ? $this->formatJsonData($user->billing_address) : 'N/A' }}
                                        </td>
                                        @endif
                                        
                                        <!-- Email Verified column -->
                                        @if($visibleColumns['email_verified_at'])
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            {{ $user->email_verified_at ? $user->email_verified_at->format('M j, Y') : 'Not Verified' }}
                                        </td>
                                        @endif
                                        
                                        <!-- Account Level column -->
                                        @if($visibleColumns['account_level'])
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                {{ $user->account_level === 'super-admin' ? 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200' : '' }}
                                                {{ $user->account_level === 'admin' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : '' }}
                                                {{ $user->account_level === 'manager' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : '' }}
                                                {{ $user->account_level === 'user' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : '' }}
                                                {{ $user->account_level === 'guest' ? 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200' : '' }}
                                            ">
                                                {{ ucfirst($user->account_level) }}
                                            </span>
                                        </td>
                                        @endif
                                        
                                        <!-- Provider column -->
                                        @if($visibleColumns['provider'])
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            @if($user->provider)
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                                    {{ ucfirst($user->provider) }}
                                                </span>
                                            @else
                                                <span class="text-gray-500 dark:text-gray-400">Email</span>
                                            @endif
                                        </td>
                                        @endif
                                        
                                        <!-- Provider Avatar column -->
                                        @if($visibleColumns['provider_avatar'])
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            @if($user->provider_avatar)
                                                <img src="{{ $user->provider_avatar }}" alt="Avatar" class="h-8 w-8 rounded-full">
                                            @else
                                                <span class="text-gray-500 dark:text-gray-400">N/A</span>
                                            @endif
                                        </td>
                                        @endif
                                        
                                        <!-- Created At column -->
                                        @if($visibleColumns['created_at'])
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            {{ $user->created_at->format('M j, Y') }}
                                        </td>
                                        @endif
                                        
                                        <!-- Updated At column -->
                                        @if($visibleColumns['updated_at'])
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            {{ $user->updated_at->format('M j, Y') }}
                                        </td>
                                        @endif
                                        
                                        <!-- Action buttons -->
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex justify-end space-x-2">
                                                <button wire:click="editUser({{ $user->id }})" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                                    </svg>
                                                </button>
                                                <button wire:click="confirmUserDeletion({{ $user->id }})" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="20" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-center">
                                            No users found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $users->links() }}
                    </div>

                    <!-- Edit User Modal -->
                    <x-modal name="edit-user-modal" :show="$isEditing" focusable maxWidth="2xl">
                        <form wire:submit.prevent="updateUser" class="p-6">
                            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                Edit User
                            </h2>

                            <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <x-input-label for="first_name" value="First Name" />
                                    <x-text-input wire:model="first_name" id="first_name" class="block mt-1 w-full" type="text" />
                                    <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="last_name" value="Last Name" />
                                    <x-text-input wire:model="last_name" id="last_name" class="block mt-1 w-full" type="text" />
                                    <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
                                </div>
                            </div>

                            <div class="mt-4">
                                <x-input-label for="email" value="Email" />
                                <x-text-input wire:model="email" id="email" class="block mt-1 w-full" type="email" />
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>

                            <div class="mt-4">
                                <x-input-label for="phone" value="Phone" />
                                <x-text-input wire:model="phone" id="phone" class="block mt-1 w-full" type="text" />
                                <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                            </div>

                            <div class="mt-4">
                                <x-input-label for="account_level" value="Account Level" />
                                <select wire:model="account_level" id="account_level" class="block mt-1 w-full p-2.5 border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-zinc-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-gray-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                    <option value="">Select Account Level</option>
                                    <option value="guest">Guest</option>
                                    <option value="user">User</option>
                                    <option value="manager">Manager</option>
                                    <option value="admin">Admin</option>
                                    <option value="super-admin">Super Admin</option>
                                </select>
                                <x-input-error :messages="$errors->get('account_level')" class="mt-2" />
                            </div>

                            <div class="mt-6 flex justify-end">
                                <x-secondary-button wire:click="resetForm" class="mr-3">
                                    Cancel
                                </x-secondary-button>

                                <x-primary-button>
                                    Save Changes
                                </x-primary-button>
                            </div>
                        </form>
                    </x-modal>

                    <!-- Delete User Modal -->
                    <x-modal name="delete-user-modal" focusable maxWidth="md">
                        <form wire:submit.prevent="deleteUser" class="p-6">
                            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                Delete User
                            </h2>

                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                Are you sure you want to delete this user? All of their data will be permanently removed.
                            </p>

                            @if ($deletingUser)
                                <div class="mt-4 bg-gray-100 dark:bg-zinc-700 p-4 rounded-md">
                                    <p class="text-sm text-gray-800 dark:text-gray-200">
                                        <strong>Name:</strong> {{ $deletingUser->first_name }} {{ $deletingUser->last_name }}<br>
                                        <strong>Email:</strong> {{ $deletingUser->email }}<br>
                                        <strong>Account Level:</strong> {{ ucfirst($deletingUser->account_level) }}
                                    </p>
                                </div>
                            @endif

                            <div class="mt-4">
                                <x-input-label for="adminPassword" value="Enter Your Password to Confirm" />
                                <x-text-input wire:model="adminPassword" id="adminPassword" class="block mt-1 w-full" type="password" />
                                <x-input-error :messages="$errors->get('adminPassword')" class="mt-2" />
                            </div>

                            <div class="mt-6 flex justify-end">
                                <x-secondary-button wire:click="resetDeleteForm" class="mr-3">
                                    Cancel
                                </x-secondary-button>

                                <x-danger-button type="submit">
                                    Delete User
                                </x-danger-button>
                            </div>
                        </form>
                    </x-modal>

                    <!-- Bulk Delete Confirmation Modal -->
                    <x-modal name="bulk-delete-modal" focusable maxWidth="md">
                        <form wire:submit.prevent="bulkDeleteConfirmed" class="p-6">
                            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                Delete Selected Users
                            </h2>

                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                Are you sure you want to delete the selected users? This action cannot be undone.
                            </p>

                            <div class="mt-4 bg-gray-100 dark:bg-zinc-700 p-4 rounded-md">
                                <p class="text-sm text-gray-800 dark:text-gray-200">
                                    <strong>Number of users to delete:</strong> {{ count($selectedUsers) }}
                                </p>
                            </div>

                            <div class="mt-4">
                                <x-input-label for="bulkDeletePassword" value="Enter Your Password to Confirm" />
                                <x-text-input wire:model="bulkDeletePassword" id="bulkDeletePassword" class="block mt-1 w-full" type="password" />
                                <x-input-error :messages="$errors->get('bulkDeletePassword')" class="mt-2" />
                            </div>

                            <div class="mt-6 flex justify-end">
                                <x-secondary-button wire:click="$dispatch('close-modal', 'bulk-delete-modal')" class="mr-3">
                                    Cancel
                                </x-secondary-button>

                                <x-danger-button type="submit">
                                    Delete Users
                                </x-danger-button>
                            </div>
                        </form>
                    </x-modal>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Search for any other instances of confirmDeleteUser -->
<script>
    document.addEventListener('livewire:initialized', () => {
        // Replace any JavaScript references to confirmDeleteUser
        window.confirmDeleteUser = function(userId) {
            Livewire.dispatch('confirmUserDeletion', { userId: userId });
        };
    });
</script>
