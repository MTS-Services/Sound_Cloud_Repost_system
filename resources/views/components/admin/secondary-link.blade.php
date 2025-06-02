@props(['error' => false])
<a
    {{ $attributes->merge(['href' => '', 'title' => '', 'target' => '_self', 'class' => 'inline-flex items-center px-4 py-2 btn-secondary border border-transparent rounded-md font-semibold text-xs uppercase tracking-widest dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-hidden focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150 ' . ($error ? 'btn btn-sm btn-secondary' : 'btn-primary bg-gradient-to-l')]) }}>
    {{ $slot }}
</a>
