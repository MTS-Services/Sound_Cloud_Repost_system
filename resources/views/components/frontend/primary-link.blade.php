<a
    {{ $attributes->merge(['href' => '', 'title' => '', 'target' => '_self', 'class' => 'inline-flex items-center gap-2 px-8 py-2 btn-primary border border-transparent rounded-md font-semibold text-xs text-white capitalize tracking-widest bg-bg-primary hover:bg-bg-primary-hover dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-hidden focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</a>
