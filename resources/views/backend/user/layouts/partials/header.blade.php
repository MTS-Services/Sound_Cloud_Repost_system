<header
    class="bg-white dark:bg-slate-800 z-41 border-b border-gray-100 dark:border-slate-700 px-4 md:px-6 py-3 md:py-5 sticky top-0"
    x-data="{
        searchModalOpen: false,
        searchQuery: '',
        selectedIndex: -1,
        suggestions: {{ searchableRoutes() }},
        filteredSuggestions: [],
    
        init() {
            // No initial filtering. The list will be empty by default.
        },
    
        filterSuggestions() {
            if (!Array.isArray(this.suggestions)) {
                console.error('Suggestions data is not a valid array.');
                return;
            }
    
            const query = this.searchQuery.trim().toLowerCase();
    
            // ✅ Only filter if the query is not empty.
            if (query === '') {
                this.filteredSuggestions = [];
            } else {
                this.filteredSuggestions = this.suggestions.filter(item => {
                    if (!item || !item.title || !item.keywords) return false;
    
                    // Check if the query matches the title
                    const titleMatch = item.title.toLowerCase().includes(query);
    
                    // Check if the query matches any of the keywords
                    const keywordMatch = item.keywords.some(keyword =>
                        keyword.toLowerCase().includes(query)
                    );
    
                    return titleMatch || keywordMatch;
                });
            }
            this.selectedIndex = -1;
        },
    
        selectSuggestion(index) {
            this.selectedIndex = index;
        },
    
        handleKeydown(event) {
            if (event.key === 'ArrowDown') {
                event.preventDefault();
                this.selectedIndex = Math.min(this.selectedIndex + 1, this.filteredSuggestions.length - 1);
            } else if (event.key === 'ArrowUp') {
                event.preventDefault();
                this.selectedIndex = Math.max(this.selectedIndex - 1, -1);
            } else if (event.key === 'Enter') {
                event.preventDefault();
                if (this.selectedIndex >= 0) {
                    const selectedRoute = this.filteredSuggestions[this.selectedIndex].route;
                    window.location.href = selectedRoute; // Fallback for `wire:navigate`
                }
            } else if (event.key === 'Escape') {
                this.searchModalOpen = false;
            }
        },
    
        // `performSearch` is no longer needed since we are using <a> tags
        performSearch(url) {
            this.searchModalOpen = false;
        }
    }">
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-2">
            <button @click="sidebarOpen = !sidebarOpen"
                class="lg:hidden p-1 md:p-2 rounded-md text-gray-900 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-slate-700">
                <x-lucide-menu class="w-5 h-5 md:w-6 md:h-6" />
            </button>
            <a class="flex items-center space-x-2" href="{{ route('user.dashboard') }}" data-discover="true" wire:navigate>
                <div class="w-7 h-7 md:w-8 md:h-8 bg-orange-500 rounded-lg flex items-center justify-center">
                    <span class="text-slate-800 dark:text-white font-bold text-md md:text-lg">R</span>
                </div>
                <span class="text-slate-800 dark:text-white font-bold text-lg md:text-xl hidden sm:block">
                    REPOST<span class="text-orange-500">CHAIN</span>
                </span>
            </a>
        </div>
        <div class="flex-1 flex justify-center px-2 md:px-4 lg:px-0 lg:ml-8 md:mr-3">
            <button @click="searchModalOpen = true"
                class="relative w-full max-w-md items-center hidden lg:flex bg-white dark:bg-slate-700 border-2 border-gray-200 dark:border-slate-600 rounded-lg px-4 py-2 hover:border-orange-500 dark:hover:border-orange-500 transition-colors group">
                <x-lucide-search
                    class="w-5 h-5 text-slate-800 dark:text-slate-300 group-hover:text-orange-500 transition-colors" />
                <span class="ml-3 text-slate-400 dark:text-slate-300 text-left flex-1">Search...</span>
                {{-- <kbd
                    class="hidden md:inline-flex items-center px-2 py-1 text-xs font-medium text-slate-400 bg-slate-100 dark:bg-slate-600 dark:text-slate-300 rounded border">
                    ⌘K
                </kbd> --}}
            </button>
        </div>
        <div class="flex items-center space-x-1 md:space-x-2">
            <nav class="hidden lg:flex items-center space-x-2 md:space-x-4 text-sm" x-data="{ activeButton: '' }">
                <a class="text-orange-500 hover:text-orange-400 font-medium" href="{{ route('user.pkm.pricing') }}"
                    wire:navigate data-discover="true">{{ __('Upgrade My Plan') }}</a>
                <a x-bind:class="{ 'text-orange-500': activeButton === 'charts', 'hover:text-orange-400': activeButton !== 'charts' }"
                    class="text-slate-800 hover:text-gray-900 dark:text-slate-300 dark:hover:text-slate-50"
                    wire:navigate href="{{ route('charts') }}" data-discover="true" @click="activeButton = 'charts'">
                    Charts
                </a>
                <a x-bind:class="{ 'text-orange-500': activeButton === 'blog', 'hover:text-orange-400': activeButton !== 'blog' }"
                    class="text-slate-800 hover:text-gray-900 dark:text-slate-300 dark:hover:text-slate-50"
                    wire:navigate href="{{ route('page') }}" data-discover="true" @click="activeButton = 'blog'">
                    Blog
                </a>
                <div x-data="{ open: false }" class="relative text-left rounded-lg flex justify-center">
                    <button @click="open = !open"
                        x-bind:class="{ 'text-orange-500': activeButton === 'help', 'hover:text-orange-400': activeButton !== 'help' }"
                        class="text-slate-800 hover:text-gray-900 dark:text-slate-300 dark:hover:text-slate-50 flex items-center space-x-1"
                        @click="activeButton = 'help'">
                        <span>Help</span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="lucide lucide-chevron-down w-4 h-4">
                            <path d="m6 9 6 6 6-6"></path>
                        </svg>
                    </button>
                    <div x-show="open" @click.outside="open = false" x-transition x-cloak
                        class="absolute right-2 mt-5 w-44 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-md shadow-lg z-50">
                        <ul class="p-0 text-sm text-gray-700 dark:text-gray-200">
                            <li>
                                <a href="{{ route('settings') }}" wire:navigate
                                    class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700">{{ __('Getting Started') }}</a>
                            </li>
                            <li>
                                <a href="{{ route('settings') }}"
                                    class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700">{{ __('Contact Support') }}</a>
                            </li>
                            <li>
                                <a href="{{ route('settings') }}"
                                    class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700">{{ __('Help Center') }}</a>
                            </li>
                            <li>
                                <a href="{{ route('user.pkm.pricing') }}" wire:navigate
                                    class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700">{{ __('Pricing') }}</a>
                            </li>
                            <li>
                                <a href="{{ route('user.faq') }}"
                                    class="block px-4 py-2  hover:bg-red-100 dark:hover:bg-gray-700">{{ __('FAQs') }}</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
            <button @click="searchModalOpen = true"
                class="lg:hidden p-1 md:p-2 rounded-md text-gray-900 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-slate-700 hover:text-orange-500 transition-colors">
                <x-lucide-search class="w-5 h-5" />
            </button>
            <livewire:user.notification.notification-panel />
            <button @click="$store.theme.toggleTheme()"
                class="p-2 rounded-xl hover:bg-black/10 dark:hover:bg-white/10 transition-colors"
                data-tooltip="Toggle theme"
                :title="$store.theme.current.charAt(0).toUpperCase() + $store.theme.current.slice(1) + ' mode'">
                <x-heroicon-o-sun x-show="!$store.theme.darkMode"
                    class="w-5 h-5 text-text-light-primary dark:text-text-white" />
                <x-heroicon-o-moon x-show="$store.theme.darkMode"
                    class="w-5 h-5 text-text-light-primary dark:text-text-white" />
            </button>
            {{-- <button @click="$store.theme.toggleTheme()"
                class="p-2 rounded-xl hover:bg-black/10 dark:hover:bg-white/10 transition-colors"
                data-tooltip="Toggle theme"
                :title="$store.theme.current.charAt(0).toUpperCase() + $store.theme.current.slice(1) + ' mode'">
                <!-- Sun icon for light mode -->
                <i data-lucide="sun" x-show="!$store.theme.darkMode" x-cloak
                    class="w-5 h-5 text-text-light-primary dark:text-text-white"></i>

                <!-- Moon icon for dark mode -->
                <i data-lucide="moon" x-show="$store.theme.darkMode" x-cloak
                    class="w-5 h-5 text-text-light-primary dark:text-text-white"></i>
            </button> --}}
            <div class="dropdown dropdown-end">
                <div tabindex="0" role="button" class="flex items-center space-x-1 md:space-x-2">
                    <img src="{{ auth_storage_url(user()->avatar) }}" alt="{{ user()->name ?? 'name' }}"
                        class="w-7 h-7 md:w-8 md:h-8 rounded-full">
                    <span
                        class="text-xs md:text-sm font-medium dark:text-slate-300 hidden sm:block">{{ user()->name ?? 'name' }}</span>
                    <svg class="dark:text-slate-300 w-3 h-3 md:w-4 md:h-4" xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="m6 9 6 6 6-6"></path>
                    </svg>
                </div>
                <ul tabindex="0"
                    class="menu menu-sm dropdown-content bg-white dark:bg-slate-800 text-slate-800 dark:text-white rounded-lg shadow-lg z-10 mt-3 w-64 py-2 space-y-1">
                    <li>
                        <a href="{{ route('user.profile') }}" wire:navigate
                            class="flex items-center px-4 py-2 hover:bg-gray-100 dark:hover:bg-slate-700 rounded-md text-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5.121 17.804A4 4 0 017 16h10a4 4 0 011.879.496M15 11a3 3 0 10-6 0 3 3 0 006 0z" />
                            </svg>
                            View Profile
                        </a>
                    </li>
                    <li class="px-4 py-2 border-t border-gray-200 dark:border-slate-700">
                        <div class="text-xs flex justify-between items-center text-gray-500 dark:text-gray-300 mb-0.5">
                            <span>Current Plan</span>
                        </div>
                        <div class="text-sm flex justify-between items-center">
                            <span class="font-semibold text-slate-800 dark:text-white">
                                @if (empty(user()->activePlan()) || user()->activePlan()->price == 0)
                                    {{ __('Free Plan') }}
                                @else
                                    {{ user()->activePlan()->plan?->name }}
                                @endif
                            </span>
                            <a href="{{ route('user.pkm.pricing') }}" wire:navigate
                                class="text-orange-500 text-xs hover:underline">{{ __('View All Plans') }}</a>
                        </div>
                    </li>
                    <li>
                        <a href="#"
                            class="px-4 py-2 hover:bg-gray-100 dark:hover:bg-slate-700 rounded-md text-sm block">
                            Settings & Preferences
                        </a>
                    </li>
                    <li class="border-t border-gray-200 dark:border-slate-700 pt-2">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="w-full text-left py-2 dark:hover:bg-slate-700 rounded-md flex items-center text-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1m0-9V4m-4 12H7a4 4 0 01-4-4V7a4 4 0 014-4h4" />
                                </svg>
                                Log Out
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div x-show="searchModalOpen" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" @click.self="searchModalOpen = false"
        @keydown.escape.window="searchModalOpen = false"
        class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-start justify-center pt-16 md:pt-24 hidden"
        :class="{ 'hidden': !searchModalOpen }">
        <div x-show="searchModalOpen" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95 translate-y-4"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100 translate-y-0"
            x-transition:leave-end="opacity-0 scale-95 translate-y-4"
            class="w-full max-w-2xl mx-4 bg-white dark:bg-slate-800 rounded-xl shadow-2xl border border-gray-200 dark:border-slate-700 overflow-hidden">
            <div class="p-4 border-b border-gray-200 dark:border-slate-700">
                <div class="relative">
                    <x-lucide-search
                        class="absolute left-4 top-1/2 transform -translate-y-1/2 w-5 h-5 text-orange-500" />
                    <input x-model="searchQuery" @input="filterSuggestions()" @keydown="handleKeydown($event)"
                        x-ref="searchInput" type="text" placeholder="Search anything..."
                        class="w-full pl-12 pr-4 py-3 text-lg bg-transparent border-2 border-gray-200 rounded-lg focus:border-orange-500 dark:focus:border-orange-500 focus:outline-none text-slate-800 dark:text-white placeholder-slate-400 dark:placeholder-slate-300 focus-within:outline-none transition-colors">
                    <button @click="searchModalOpen = false"
                        class="absolute right-4 top-1/2 transform -translate-y-1/2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
            <div class="max-h-96 overflow-y-auto">
                {{-- ✅ Show results only when searchQuery is not empty --}}
                <template x-if="searchQuery !== ''">
                    <template x-if="filteredSuggestions.length > 0">
                        <div class="p-2">
                            <div
                                class="text-xs font-medium text-slate-500 dark:text-slate-400 px-3 py-2 uppercase tracking-wide">
                                <span x-text="`Showing ${filteredSuggestions.length} results`"></span>
                            </div>
                            <template x-for="(suggestion, index) in filteredSuggestions" :key="index">
                                <a x-bind:href="suggestion.route" wire:navigate @mouseenter="selectSuggestion(index)"
                                    :class="selectedIndex === index ?
                                        'bg-orange-50 dark:bg-orange-900/20 border-orange-200 dark:border-orange-800' :
                                        'hover:bg-gray-50 dark:hover:bg-slate-700'"
                                    class="w-full flex items-center gap-3 px-3 py-3 rounded-lg border border-transparent transition-all duration-150 text-left group">
                                    <x-lucide-link class="w-5 h-5 text-sm" />
                                    <div class="flex-1 min-w-0">
                                        <div class="font-medium text-slate-800 dark:text-white group-hover:text-orange-600 dark:group-hover:text-orange-400 transition-colors"
                                            x-text="suggestion.title"></div>
                                    </div>
                                    <svg class="w-4 h-4 text-slate-400 group-hover:text-orange-500 transition-colors opacity-0 group-hover:opacity-100"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </a>
                            </template>
                        </div>
                    </template>
                    <template x-if="filteredSuggestions.length === 0 && searchQuery !== ''">
                        <div class="p-8 text-center">
                            <div class="text-slate-400 dark:text-slate-500 mb-2">
                                <svg class="w-12 h-12 mx-auto mb-4" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <p class="text-slate-600 dark:text-slate-300 font-medium">No results found</p>
                            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Try searching for something else
                            </p>
                        </div>
                    </template>
                </template>
                {{-- ✅ New Section: Default State when search bar is empty --}}
                <template x-if="searchQuery === ''">
                    <div class="p-8 text-center">
                        <div class="text-slate-400 dark:text-slate-500 mb-2">
                            <svg class="w-12 h-12 mx-auto mb-4" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <p class="text-slate-600 dark:text-slate-300 font-medium">Start typing to search...</p>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Search campaigns, pages, and more.
                        </p>
                    </div>
                </template>
            </div>
            <div class="px-4 py-3 bg-gray-50 dark:bg-slate-700/50 border-t border-gray-200 dark:border-slate-700">
                <div class="flex items-center justify-center text-xs text-slate-500 dark:text-slate-400">
                    <div class="flex items-center gap-4">
                        <span class="flex items-center gap-1">
                            <kbd class="px-2 py-1 bg-white dark:bg-slate-600 rounded border text-xs">↑↓</kbd>
                            Navigate
                        </span>
                        <span class="flex items-center gap-1">
                            <kbd class="px-2 py-1 bg-white dark:bg-slate-600 rounded border text-xs">Enter</kbd>
                            Select
                        </span>
                        <span class="flex items-center gap-1">
                            <kbd class="px-2 py-1 bg-white dark:bg-slate-600 rounded border text-xs">Esc</kbd>
                            Close
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div x-init="$watch('searchModalOpen', value => { if (value) { $nextTick(() => $refs.searchInput?.focus()) } })"></div>
</header>
