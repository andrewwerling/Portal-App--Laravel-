<div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <div class="font-medium text-sm text-gray-500"><strong class="font-semibold text-lg text-gray-800 dark:text-gray-200">Name:</strong> {{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</div>
            <div class="font-medium text-sm text-gray-500"><strong class="font-semibold text-lg text-gray-800 dark:text-gray-200">Email:</strong> {{ auth()->user()->email }}</div>
            <div class="font-medium text-sm text-gray-500"><strong class="font-semibold text-lg text-gray-800 dark:text-gray-200">Tel:</strong> {{ auth()->user()->phone }}</div>
            <div class="font-medium text-sm text-gray-500"><strong class="font-semibold text-lg text-gray-800 dark:text-gray-200">DOB:</strong> {{ auth()->user()->birthday ? \Carbon\Carbon::parse(auth()->user()->birthday)->format('F j, Y') : 'Not provided' }}</div>
            <div class="font-medium text-sm text-gray-500"><strong class="font-semibold text-lg text-gray-800 dark:text-gray-200">Gender:</strong> {{ auth()->user()->gender }}</div>
            <div class="font-medium text-sm text-gray-500"><strong class="font-semibold text-lg text-gray-800 dark:text-gray-200">Job:</strong> {{ auth()->user()->occupation }}</div>
            <div class="font-medium text-sm text-gray-500"><strong class="font-semibold text-lg text-gray-800 dark:text-gray-200">Mailing Address:</strong><br>{{ auth()->user()->mailing_address ? implode(', ', array_filter([auth()->user()->mailing_address['street'] ?? null, auth()->user()->mailing_address['city'] ?? null, auth()->user()->mailing_address['state'] ?? null, auth()->user()->mailing_address['postal_code'] ?? null, auth()->user()->mailing_address['country'] ?? null])) : 'Not provided' }}</div>
            <div class="font-medium text-sm text-gray-500"><strong class="font-semibold text-lg text-gray-800 dark:text-gray-200">Billing Address:</strong><br>{{ auth()->user()->mailing_address ? implode(', ', array_filter([auth()->user()->billing_address['street'] ?? null, auth()->user()->billing_address['city'] ?? null, auth()->user()->billing_address['state'] ?? null, auth()->user()->billing_address['postal_code'] ?? null, auth()->user()->billing_address['country'] ?? null])) : 'Not provided' }}</div>
        </div>
        <div>
            <div class="font-medium text-sm text-gray-500"><strong class="font-semibold text-lg text-gray-800 dark:text-gray-200">Social Links:</strong>
                <div class="overflow-scroll">
                    {{ auth()->user()->social_media ? implode(', ', array_filter([auth()->user()->social_media['instagram'] ?? null, auth()->user()->social_media['tiktok'] ?? null, auth()->user()->social_media['facebook'] ?? null, auth()->user()->social_media['linkedin'] ?? null, auth()->user()->social_media['twitter'] ?? null])) : 'Not provided' }}
                </div>
            </div>
            <div class="font-medium text-sm text-gray-500"><strong class="font-semibold text-lg text-gray-800 dark:text-gray-200">Bio:</strong> {{ auth()->user()->bio }}</div>
        </div>
    </div>
    <a href="{{ route('profile') }}" class="mt-4 w-full px-5 py-2.5 inline-block text-center bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">Edit Profile</a>
</div>
