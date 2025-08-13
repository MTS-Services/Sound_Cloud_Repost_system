<style>
    .gsc-control-cse {
        background: transparent;
        padding: 5px !important;
        width: 500px;
        border-radius: 8px;
        /* box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1); */
    }

    .gsc-control-cse .gsc-input {
        background: #f0e7e7;
        padding: 0 !important;
        margin: 0 !important;
    }

    .gsc-control-cse .gsib_a {
        background: #fdfdfd;

    }

    #gs_tti50 {
        border: none !important;
        /* padding: 0 !important; */
    }

    .gsc-control-cse .gsc-search-button {
        background: rgb(224, 81, 62);
        border: none;
        border-radius: 4px;
        padding: 0.5rem 0.75rem;
        color: #000 !important;
    }

    .gsc-control-cse .gsib_a {
        padding: 0.5rem !important;
    }

    #gsc-i-id1 {
        background: #ffffff;
        background-image: none !important;

        transition: all 0.3s ease-in-out;
        margin: 0 !important outline: none !important;
    }

    #gsc-i-id1:focus {
        box-shadow: 0 0 0 3px rgba(255, 165, 0, 0.5);
        border-color: orange !important;
    }
</style>

<header
    class="bg-white h-[9vh] dark:bg-slate-800 z-41 border-b border-gray-100 dark:border-slate-700 px-4 md:px-6 py-3 md:py-5 sticky top-0">
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-2">
            <button @click="sidebarOpen = !sidebarOpen"
                class="lg:hidden p-1 md:p-2 rounded-md text-gray-900 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-slate-700">
                <x-lucide-menu class="w-5 h-5 md:w-6 md:h-6" />
            </button>
            <a class="flex items-center space-x-2" href="/dashboard" data-discover="true" wire:navigate>
                <div class="w-7 h-7 md:w-8 md:h-8 bg-orange-500 rounded-lg flex items-center justify-center">
                    <span class="text-slate-800 dark:text-white font-bold text-md md:text-lg">R</span>
                </div>
                <span class="text-slate-800 dark:text-white font-bold text-lg md:text-xl hidden sm:block">
                    REPOST<span class="text-orange-500">CHAIN</span>
                </span>
            </a>
        </div>

        <div class="flex-1 flex justify-center px-2 md:px-4 lg:px-0 lg:ml-8 ">
            {{-- <form class="relative w-full max-w-md items-center hidden sm:flex">
                <span class="absolute inset-y-0 left-0 flex items-center pl-4">

                    <x-lucide-search class="w-5 h-5 text-slate-800 dark:text-slate-300" />
                </span>
                <input type="search" placeholder="Search..."
                    class="w-full pl-12 placeholder-slate-400 pr-4 py-2 rounded-lg bg-white dark:bg-slate-700 border-2 border-gray-200 dark:border-slate-600
                    focus:border-[#F54A00]! dark:focus:border-[#F54A00]!
                    text-gray-900 dark:text-gray-200 dark:placeholder:text-slate-300
                    dark:shadow-sm outline-none transition" />
            </form> --}}
            <div class="gcse-search"></div>
        </div>

        <div class="flex items-center space-x-1 md:space-x-2">
            <!-- Navigation items - hide on mobile -->
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

                <div x-data="{ open: false }" class="relative text-left  rounded-lg  flex justify-center">
                    <!-- Trigger Button -->
                    {{-- <button @click="open = !open"
                        class="p-2 text-slate-800 hover:text-gray-900 dark:text-slate-300 dark:hover:text-slate-50 flex px-3 aline-center py-2 rounded-lg   focus:outline-none focus:ring-offset-2    left-4">
                        <span class="m-2">help</span>

                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="lucide lucide-chevron-down w-5 h-5">
                            <path d="m6 9 6 6 6-6"></path>
                        </svg>
                    </button> --}}
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

                    <!-- Dropdown Menu -->
                    <div x-show="open" @click.outside="open = false" x-transition x-cloak
                        class="absolute right-2 mt-5 w-44 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-md shadow-lg z-50">
                        <ul class="p-0 text-sm text-gray-700 dark:text-gray-200">
                            <li>
                                <a href="#"
                                    class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700">{{ __('Getting Started') }}</a>
                            </li>
                            <li>
                                <a href="#"
                                    class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700">{{ __('Contact Support') }}</a>
                            </li>
                            <li>
                                <a href="#"
                                    class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700">{{ __('Help Center') }}</a>
                            </li>
                            <li>
                                <a href="{{ route('user.pkm.pricing') }}" wire:navigate
                                    class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700">{{ __('Pricing') }}</a>
                            </li>
                            <li>
                                <a href="{{ route('user.faq') }}"
                                    class="block px-4 py-2   hover:bg-red-100  dark:hover:bg-gray-700">{{ __('FAQs') }}</a>
                            </li>
                        </ul>
                    </div>

                </div>
            </nav>



            <!-- Mobile search button -->
            <button @click="mobileSearchOpen = !mobileSearchOpen"
                class="lg:hidden p-1 md:p-2 rounded-md text-gray-900 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-slate-700">
                <x-lucide-search class="w-5 h-5 text-slate-800 dark:text-slate-300" />
            </button>

            <!-- Mobile search overlay -->
            <div x-show="mobileSearchOpen" @click.away="mobileSearchOpen = false"
                class="fixed inset-0 bg-black bg-opacity-50 z-50 lg:hidden" x-cloak>
                <div class="bg-white dark:bg-slate-800 p-4">
                    <form class="relative w-full">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                            <x-lucide-search class="w-5 h-5 text-slate-800 dark:text-slate-300" />
                        </span>
                        <input type="search" placeholder="Search..."
                            class="w-full pl-10 placeholder-slate-400 pr-4 py-2 rounded-lg bg-white dark:bg-slate-700 border-2 border-gray-200 dark:border-slate-600
                            focus:border-[#F54A00]! dark:focus:border-[#F54A00]!
                            text-gray-900 dark:text-gray-200 dark:placeholder:text-slate-300
                            dark:shadow-sm outline-none transition" />
                    </form>
                </div>
            </div>

            <!-- Notification -->
            {{-- <div class="relative ml-1 md:ml-1.5">
                <x-lucide-bell class="w-5 h-5 text-gray-800 dark:text-slate-300" />
                <span
                    class="absolute -top-1.5 -right-1 bg-red-500 text-white text-[10px] md:text-xs rounded-full w-3 h-3 md:w-4 md:h-4 flex items-center justify-center">1</span>
            </div> --}}
            {{-- Notification Panel --}}
            <livewire:user.notification.notification-panel />

            <!-- Theme toggle -->
            <button @click="$store.theme.toggleTheme()"
                class="p-2 rounded-xl hover:bg-black/10 dark:hover:bg-white/10 transition-colors"
                data-tooltip="Toggle theme"
                :title="$store.theme.current.charAt(0).toUpperCase() + $store.theme.current.slice(1) + ' mode'">
                <x-heroicon-o-sun x-show="!$store.theme.darkMode"
                    class="w-5 h-5 text-text-light-primary dark:text-text-white" />
                <x-heroicon-o-moon x-show="$store.theme.darkMode"
                    class="w-5 h-5 text-text-light-primary dark:text-text-white" />
            </button>
            <!-- User dropdown -->
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

                    <!-- View Profile -->
                    <li>
                        <a href="{{ route('user.profile') }}"wire:navigate
                            class="flex items-center px-4 py-2 hover:bg-gray-100 dark:hover:bg-slate-700 rounded-md text-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5.121 17.804A4 4 0 017 16h10a4 4 0 011.879.496M15 11a3 3 0 10-6 0 3 3 0 006 0z" />
                            </svg>
                            View Profile
                        </a>
                    </li>

                    <!-- Current Plan -->
                    <li class="px-4 py-2 border-t border-gray-200 dark:border-slate-700">
                        <div class="text-xs flex justify-between items-center text-gray-500 dark:text-gray-300 mb-0.5">
                            <span>Current Plan</span>
                        </div>
                        <div class="text-sm flex justify-between items-center">
                            <span class="font-semibold text-slate-800 dark:text-white">{{ __('Free Plan') }}</span>
                            <a href="{{ route('user.pkm.pricing') }}" wire:navigate
                                class="text-orange-500 text-xs hover:underline">{{ __('View All Plans') }}</a>
                        </div>
                    </li>

                    <!-- Settings & Preferences -->
                    <li>
                        <a href="#"
                            class="px-4 py-2 hover:bg-gray-100 dark:hover:bg-slate-700 rounded-md text-sm block">
                            Settings & Preferences
                        </a>
                    </li>

                    <!-- Purchase History -->
                    <li>
                        <a href="#" wire:navigate
                            class="px-2 py-2 hover:bg-gray-100 dark:hover:bg-slate-700 rounded-md text-sm flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 " fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-1 2h13m-6 4a1 1 0 100-2 1 1 0 000 2zm-6 0a1 1 0 100-2 1 1 0 000 2z" />
                            </svg>
                            Purchase History
                        </a>
                    </li>

                    <!-- Logout -->
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
</header>


{{-- <div class="gsc-control-cse gsc-control-cse-en">
    <div class="gsc-control-wrapper-cse" dir="ltr">
        <form class="gsc-search-box gsc-search-box-tools" accept-charset="utf-8">
            <table cellspacing="0" cellpadding="0" role="presentation" class="gsc-search-box">
                <tbody>
                    <tr>
                        <td class="gsc-input">
                            <div class="gsc-input-box" id="gsc-iw-id1">
                                <table cellspacing="0" cellpadding="0" role="presentation" id="gs_id50"
                                    class="gstl_50 gsc-input" style="width: 100%; padding: 0px;">
                                    <tbody>
                                        <tr>
                                            <td id="gs_tti50" class="gsib_a"><input autocomplete="off"
                                                    type="text" size="10" class="gsc-input" name="search"
                                                    title="search" aria-label="search" id="gsc-i-id1"
                                                    style="width: 100%; padding: 0px; border: none; margin: 0px; height: auto; background: url(&quot;https://www.google.com/cse/static/images/1x/en/branding.png&quot;) left center no-repeat rgb(255, 255, 255); outline: none;"
                                                    dir="ltr" spellcheck="false"></td>
                                            <td class="gsib_b">
                                                <div class="gsst_b" id="gs_st50" dir="ltr"><a class="gsst_a"
                                                        href="javascript:void(0)" title="Clear search box"
                                                        role="button" style="display: none;"><span class="gscb_a"
                                                            id="gs_cb50" aria-hidden="true">×</span></a></div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </td>
                        <td class="gsc-search-button"><button class="gsc-search-button gsc-search-button-v2"><svg
                                    width="13" height="13" viewBox="0 0 13 13">
                                    <title>search</title>
                                    <path
                                        d="m4.8495 7.8226c0.82666 0 1.5262-0.29146 2.0985-0.87438 0.57232-0.58292 0.86378-1.2877 0.87438-2.1144 0.010599-0.82666-0.28086-1.5262-0.87438-2.0985-0.59352-0.57232-1.293-0.86378-2.0985-0.87438-0.8055-0.010599-1.5103 0.28086-2.1144 0.87438-0.60414 0.59352-0.8956 1.293-0.87438 2.0985 0.021197 0.8055 0.31266 1.5103 0.87438 2.1144 0.56172 0.60414 1.2665 0.8956 2.1144 0.87438zm4.4695 0.2115 3.681 3.6819-1.259 1.284-3.6817-3.7 0.0019784-0.69479-0.090043-0.098846c-0.87973 0.76087-1.92 1.1413-3.1207 1.1413-1.3553 0-2.5025-0.46363-3.4417-1.3909s-1.4088-2.0686-1.4088-3.4239c0-1.3553 0.4696-2.4966 1.4088-3.4239 0.9392-0.92727 2.0864-1.3969 3.4417-1.4088 1.3553-0.011889 2.4906 0.45771 3.406 1.4088 0.9154 0.95107 1.379 2.0924 1.3909 3.4239 0 1.2126-0.38043 2.2588-1.1413 3.1385l0.098834 0.090049z">
                                    </path>
                                </svg></button></td>
                        <td class="gsc-clear-button">
                            <div class="gsc-clear-button" title="clear results">&nbsp;</div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </form>
        <div class="gsc-results-wrapper-overlay">
            <div class="gsc-results-close-btn" tabindex="0"></div>
            <div class="gsc-positioningWrapper">
                <div class="gsc-tabsAreaInvisible">
                    <div aria-label="refinement" role="tab"
                        class="gsc-tabHeader gsc-inline-block gsc-tabhActive">Web</div><span class="gs-spacer">
                    </span>
                    <div tabindex="0" aria-label="refinement" role="tab"
                        class="gsc-tabHeader gsc-tabhInactive gsc-inline-block">Image</div><span class="gs-spacer">
                    </span>
                </div>
            </div>
            <div class="gsc-positioningWrapper">
                <div class="gsc-refinementsAreaInvisible"></div>
            </div>
            <div class="gsc-above-wrapper-area-invisible">
                <div class="gsc-above-wrapper-area-backfill-container"></div>
                <table cellspacing="0" cellpadding="0" role="presentation" class="gsc-above-wrapper-area-container">
                    <tbody>
                        <tr>
                            <td class="gsc-result-info-container">
                                <div class="gsc-result-info-invisible"></div>
                            </td>
                            <td class="gsc-orderby-container">
                                <div class="gsc-orderby-invisible">
                                    <div class="gsc-orderby-label gsc-inline-block">Sort by:</div>
                                    <div class="gsc-option-menu-container gsc-inline-block">
                                        <div class="gsc-selected-option-container gsc-inline-block">
                                            <div class="gsc-selected-option">Relevance</div>
                                            <div class="gsc-option-selector"></div>
                                        </div>
                                        <div class="gsc-option-menu-invisible">
                                            <div class="gsc-option-menu-item gsc-option-menu-item-highlighted">
                                                <div class="gsc-option">Relevance</div>
                                            </div>
                                            <div class="gsc-option-menu-item">
                                                <div class="gsc-option">Date</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="gsc-adBlockInvisible"></div>
            <div class="gsc-wrapper">
                <div class="gsc-adBlockInvisible"></div>
                <div class="gsc-resultsbox-invisible">
                    <div class="gsc-resultsRoot gsc-tabData gsc-tabdActive">
                        <div>
                            <div class="gsc-expansionArea"></div>
                        </div>
                    </div>
                    <div class="gsc-resultsRoot gsc-tabData gsc-tabdInactive">
                        <div>
                            <div class="gsc-expansionArea"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="gsc-modal-background-image" tabindex="0"></div>
    </div>
</div> --}}
