<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-zinc-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-4">Admin Controls</h3>
                    
                    <div class="grid md:grid-cols-3 gap-6">
                        <a href="{{ route('admin.users') }}" class="bg-blue-50 dark:bg-blue-900 p-4 rounded-lg shadow hover:bg-blue-100 dark:hover:bg-blue-800 transition-colors">
                            <h4 class="font-medium text-blue-700 dark:text-blue-300 mb-2">User Management</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-300">Manage user accounts and permissions</p>
                        </a>
                        
                        <a href="{{ route('admin.battery-info') }}" class="bg-green-50 dark:bg-green-900 p-4 rounded-lg shadow hover:bg-green-100 dark:hover:bg-green-800 transition-colors">
                            <h4 class="font-medium text-green-700 dark:text-green-300 mb-2">Battery Information</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-300">View battery status from equipment</p>
                        </a>
                        
                        <div class="bg-purple-50 dark:bg-purple-900 p-4 rounded-lg shadow">
                            <h4 class="font-medium text-purple-700 dark:text-purple-300 mb-2">System Settings</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-300">Configure application settings</p>
                        </div>
                    </div>
                    
                    <div class="mt-8">
                        <h3 class="text-lg font-semibold mb-4">System Overview</h3>
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg shadow">
                            <p class="text-gray-600 dark:text-gray-300">This is the admin dashboard. Only users with admin or super-admin account levels can access this page.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
