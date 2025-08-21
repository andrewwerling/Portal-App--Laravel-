<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>OAuth Test</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <!-- Basic Styles -->
    <style>
        body {
            font-family: 'Figtree', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f3f4f6;
            color: #1f2937;
        }
        .dark body {
            background-color: #111827;
            color: #f3f4f6;
        }
        .min-h-screen {
            min-height: 100vh;
        }
        .flex {
            display: flex;
        }
        .flex-col {
            flex-direction: column;
        }
        .items-center {
            align-items: center;
        }
        .justify-center {
            justify-content: center;
        }
        .pt-6 {
            padding-top: 1.5rem;
        }
        .w-full {
            width: 100%;
        }
        .max-w-md {
            max-width: 28rem;
        }
        .mt-6 {
            margin-top: 1.5rem;
        }
        .px-6 {
            padding-left: 1.5rem;
            padding-right: 1.5rem;
        }
        .py-4 {
            padding-top: 1rem;
            padding-bottom: 1rem;
        }
        .bg-white {
            background-color: white;
        }
        .shadow-md {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        .rounded-lg {
            border-radius: 0.5rem;
        }
        .text-2xl {
            font-size: 1.5rem;
        }
        .font-bold {
            font-weight: 700;
        }
        .mb-6 {
            margin-bottom: 1.5rem;
        }
        .text-center {
            text-align: center;
        }
        .mb-4 {
            margin-bottom: 1rem;
        }
        .p-2 {
            padding: 0.5rem;
        }
        .bg-gray-100 {
            background-color: #f3f4f6;
        }
        .text-xs {
            font-size: 0.75rem;
        }
        .rounded {
            border-radius: 0.25rem;
        }
        .space-y-2 > * + * {
            margin-top: 0.5rem;
        }
        .flex {
            display: flex;
        }
        .items-center {
            align-items: center;
        }
        .justify-center {
            justify-content: center;
        }
        .px-4 {
            padding-left: 1rem;
            padding-right: 1rem;
        }
        .py-2 {
            padding-top: 0.5rem;
            padding-bottom: 0.5rem;
        }
        .border {
            border-width: 1px;
        }
        .border-gray-300 {
            border-color: #d1d5db;
        }
        .rounded-md {
            border-radius: 0.375rem;
        }
        .shadow-sm {
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        }
        .text-sm {
            font-size: 0.875rem;
        }
        .font-medium {
            font-weight: 500;
        }
        .text-gray-700 {
            color: #374151;
        }
        .bg-white {
            background-color: white;
        }
        .hover\:bg-gray-50:hover {
            background-color: #f9fafb;
        }
        .h-5 {
            height: 1.25rem;
        }
        .w-5 {
            width: 1.25rem;
        }
        .mr-2 {
            margin-right: 0.5rem;
        }
        .mt-6 {
            margin-top: 1.5rem;
        }
        .text-gray-600 {
            color: #4b5563;
        }
        .hover\:text-gray-900:hover {
            color: #111827;
        }
        a {
            color: #4b5563;
            text-decoration: none;
        }
        a:hover {
            color: #111827;
        }
    </style>
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100 dark:bg-gray-900">
        <div class="w-3/4 sm:max-w-md mt-6 px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg">
            <h1 class="text-2xl font-bold mb-6 text-center text-gray-900 dark:text-gray-100">OAuth Test Page</h1>

            @if(config('app.env') !== 'production')
            <div class="mb-4 p-2 bg-gray-100 dark:bg-gray-700 text-xs rounded">
                <h2 class="font-bold mb-2">Debug Information:</h2>
                <p>App Key: {{ config('app.key') ? 'Set ✓' : 'Not set ✗' }}</p>
                <p>Google ID: {{ config('services.google.client_id') ? 'Set ✓' : 'Not set ✗' }}</p>
                <p>Google Secret: {{ config('services.google.client_secret') ? 'Set ✓' : 'Not set ✗' }}</p>
                <p>Facebook ID: {{ config('services.facebook.client_id') ? 'Set ✓' : 'Not set ✗' }}</p>
                <p>Facebook Secret: {{ config('services.facebook.client_secret') ? 'Set ✓' : 'Not set ✗' }}</p>
                <p>GitHub ID: {{ config('services.github.client_id') ? 'Set ✓' : 'Not set ✗' }}</p>
                <p>GitHub Secret: {{ config('services.github.client_secret') ? 'Set ✓' : 'Not set ✗' }}</p>
                <p>OAuth Routes: {{ route('oauth.redirect', ['provider' => 'google']) ? 'Registered ✓' : 'Not registered ✗' }}</p>
            </div>
            @endif

            <div class="mb-6">
                <div class="flex flex-col space-y-2">
                    <a href="{{ route('oauth.redirect', ['provider' => 'google']) }}" class="flex items-center justify-center px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700">
                        <svg class="h-5 w-5 mr-2" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12.545,10.239v3.821h5.445c-0.712,2.315-2.647,3.972-5.445,3.972c-3.332,0-6.033-2.701-6.033-6.032s2.701-6.032,6.033-6.032c1.498,0,2.866,0.549,3.921,1.453l2.814-2.814C17.503,2.988,15.139,2,12.545,2C7.021,2,2.543,6.477,2.543,12s4.478,10,10.002,10c8.396,0,10.249-7.85,9.426-11.748L12.545,10.239z"/>
                        </svg>
                        {{ __('Continue with Google') }}
                    </a>

                    <a href="{{ route('oauth.redirect', ['provider' => 'facebook']) }}" class="flex items-center justify-center px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700">
                        <svg class="h-5 w-5 mr-2" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                        </svg>
                        {{ __('Continue with Facebook') }}
                    </a>

                    <a href="{{ route('oauth.redirect', ['provider' => 'github']) }}" class="flex items-center justify-center px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700">
                        <svg class="h-5 w-5 mr-2" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 .297c-6.63 0-12 5.373-12 12 0 5.303 3.438 9.8 8.205 11.385.6.113.82-.258.82-.577 0-.285-.01-1.04-.015-2.04-3.338.724-4.042-1.61-4.042-1.61C4.422 18.07 3.633 17.7 3.633 17.7c-1.087-.744.084-.729.084-.729 1.205.084 1.838 1.236 1.838 1.236 1.07 1.835 2.809 1.305 3.495.998.108-.776.417-1.305.76-1.605-2.665-.3-5.466-1.332-5.466-5.93 0-1.31.465-2.38 1.235-3.22-.135-.303-.54-1.523.105-3.176 0 0 1.005-.322 3.3 1.23.96-.267 1.98-.399 3-.405 1.02.006 2.04.138 3 .405 2.28-1.552 3.285-1.23 3.285-1.23.645 1.653.24 2.873.12 3.176.765.84 1.23 1.91 1.23 3.22 0 4.61-2.805 5.625-5.475 5.92.42.36.81 1.096.81 2.22 0 1.606-.015 2.896-.015 3.286 0 .315.21.69.825.57C20.565 22.092 24 17.592 24 12.297c0-6.627-5.373-12-12-12"/>
                        </svg>
                        {{ __('Continue with GitHub') }}
                    </a>
                </div>
            </div>

            <div class="mt-6">
                <a href="{{ route('login') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                    {{ __('Back to Login') }}
                </a>
            </div>
        </div>
    </div>
</body>
</html>
