@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'p-2.5 border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-zinc-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-gray-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm']) }}>
