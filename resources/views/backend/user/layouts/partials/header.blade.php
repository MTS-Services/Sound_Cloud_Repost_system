<header class="bg-white dark:bg-slate-800 z-41 border-b border-gray-100 dark:border-slate-700 px-6 py-1 sticky top-0">
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-2">
            <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden p-2 rounded-md text-gray-900 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-slate-700">
                <i data-lucide="menu" class="w-6 h-6"></i>
            </button>
            <a class="flex items-center space-x-2" href="/" data-discover="true">
                <div class="w-8 h-8 bg-orange-500 rounded-lg flex items-center justify-center">
                    <span class="text-slate-800 dark:text-white font-bold text-lg">R</span>
                </div>
                <span class="text-slate-800 dark:text-white font-bold text-xl hidden sm:block">
                    REPOST<span class="text-orange-500">CHAIN</span>
                </span>
            </a>
        </div>
        <div class="flex-1 flex justify-center px-4 lg:px-0">
            <form class="relative w-full max-w-md items-center hidden sm:flex">
                <span class="absolute inset-y-0 left-0 flex items-center pl-4">
                    <i data-lucide="search" class="w-5 h-5 text-slate-800 dark:text-slate-300"></i>
                </span>
                <input type="search" placeholder="Search..."
                    class="w-full pl-12 pr-4 py-2 rounded-lg bg-white dark:bg-slate-800 border-2 border-gray-200 dark:border-slate-600
                    focus:border-[#F54A00] dark:focus:border-[#F54A00]
                    text-gray-900 dark:text-gray-200 placeholder:text-slate-800 dark:placeholder:text-slate-300
                    dark:shadow-sm outline-none transition" />
            </form>
        </div>
        <div class="flex items-center space-x-4">
            <nav class="hidden lg:flex items-center space-x-6 text-sm">
                <a class="text-orange-500 hover:text-orange-400 font-medium" href="/upgrade"
                    data-discover="true">Upgrade My Plan</a>
                <a class="text-slate-800 hover:text-gray-900 dark:text-slate-300 " href="/charts"
                    data-discover="true">Charts</a>
                <a class="text-slate-800 hover:text-gray-900 dark:text-slate-300 " href="/blog"
                    data-discover="true">Blog</a>
            </nav>
            <div class="hidden sm:block text-sm text-gray-900 dark:text-slate-300">
                <span class="font-semibold">{{ $totalCredits }}</span> Credits
            </div>
            <div class="relative">
                <i data-lucide="bell" class="w-6 h-6 text-gray-800 dark:text-slate-300"></i>
                <span
                    class="absolute -top-2 -right-1 bg-red-500 text-white text-xs rounded-full w-4 h-4 flex items-center justify-center">1</span>
            </div>
            <!-- Theme Toggle Button (Only Light/Dark) -->
            <button @click="$store.theme.toggleTheme()"
                class="p-2 rounded-xl hover:bg-black/10 dark:hover:bg-white/10 transition-colors"
                data-tooltip="Toggle theme"
                :title="$store.theme.current.charAt(0).toUpperCase() + $store.theme.current.slice(1) + ' mode'">
                <i data-lucide="sun" x-show="!$store.theme.darkMode"
                    class="w-5 h-5 text-text-light-primary dark:text-text-white"></i>
                <i data-lucide="moon" x-show="$store.theme.darkMode"
                    class="w-5 h-5 text-text-light-primary dark:text-text-white"></i>
            </button>
            <div class="dropdown dropdown-end">
                <div tabindex="0" role="button" class="flex items-center space-x-2 avatar">
                    <div class="w-10 h-10 rounded-full">
                        <img src="{{ user()->avatar }}" alt="{{ user()->name ?? 'name' }}" />
                    </div>
                    <div class="text-sm hidden sm:block pt-5">
                        <span class="font-semibold text-gray-900 dark:text-gray-200">{{ user()->name ?? 'name' }}</span>
                        <div class="text-green-500 text-xs">● Online</div>
                    </div>
                </div>
                <ul tabindex="0"
                    class="menu menu-sm dropdown-content bg-white dark:bg-slate-800 rounded-box z-1 mt-3 w-52 shadow py-2">
                    <li>
                        <div
                            class="flex items-center space-x-2 border-b border-gray-100 dark:border-slate-700 px-6 py-4 w-full">
                            <img src="{{ user()->avatar }}" alt="{{ user()->name ?? 'name' }}"
                                class="w-8 h-8 rounded-full">
                            <div class="text-sm">
                                <span
                                    class="font-semibold text-gray-900 dark:text-gray-200">{{ user()->name ?? 'name' }}</span>
                                <div class="text-green-500 text-xs">● Online</div>
                            </div>
                        </div>
                    </li>
                    <li>
                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-responsive-nav-link :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                                Log Out
                            </x-responsive-nav-link>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</header>
