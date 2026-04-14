@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'px-3 py-1 bg-slate-100 dark:bg-gray-900 border-gray-300 dark:border-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm']) }}>
