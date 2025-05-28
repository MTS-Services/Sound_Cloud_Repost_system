@props([
    'route' => '',
    'icon' => '',
    'name' => 'Single Navlink',
    'boxicon' => false,
    'active' => '',
    'page_slug' => '',
])
<a href="{{ $route }}"
    class="sidebar-item flex items-center gap-4 p-3 rounded-xl hover:bg-bg-black/10 dark:hover:bg-bg-white/10 text-text-white transition-all duration-200 group {{ $page_slug == $active ? 'active' : '' }}">
    <div
        class="w-8 h-8 bg-bg-black/10 dark:bg-bg-white/10 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
        @if ($boxicon)
            <i class="{{ $icon }} text-blue"></i>
        @else
            <i data-lucide="{{ $icon }}" class="w-5 h-5 stroke-bg-black dark:stroke-bg-white flex-shrink-0"></i>
        @endif
    </div>
    <span x-show="(desktop && sidebar_expanded) || (!desktop && mobile_menu_open)"
        x-transition:enter="transition-all duration-300 delay-75" x-transition:enter-start="opacity-0 translate-x-4"
        x-transition:enter-end="opacity-100 translate-x-0" x-transition:leave="transition-all duration-200"
        x-transition:leave-start="opacity-100 translate-x-0" x-transition:leave-end="opacity-0 -translate-x-4"
        class="font-medium {{ $page_slug == $active ? 'text-text-black dark:text-text-white' : 'text-text-light-secondary dark:text-text-dark-primary' }}">{{ __($name) }}</span>
    <div x-show="(desktop && sidebar_expanded) || (!desktop && mobile_menu_open)"
        class="ml-auto {{ $page_slug == $active ? 'block' : 'hidden' }}">
        <div class="w-2 h-2 bg-violet-400 dark:bg-violet-300 rounded-full animate-pulse"></div>
    </div>
</a>
