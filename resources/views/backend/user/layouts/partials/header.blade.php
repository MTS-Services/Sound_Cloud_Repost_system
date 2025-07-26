<header class="bg-white dark:bg-slate-800 z-41 border-b border-gray-100 dark:border-slate-700 px-6 py-5 sticky top-0">
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-2">
            <button @click="sidebarOpen = !sidebarOpen"
                class="lg:hidden p-2 rounded-md text-gray-900 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-slate-700">
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
        <div class="flex-1 flex justify-center px-4 lg:px-0 lg:ml-8">
            <form class="relative w-full max-w-md items-center hidden sm:flex">
                <span class="absolute inset-y-0 left-0 flex items-center pl-4">
                    <i data-lucide="search" class="w-5 h-5 text-slate-800 dark:text-slate-300"></i>
                </span>
                <input type="search" placeholder="Search..."
                    class="w-full pl-12  placeholder-slate-400 pr-4 py-2 rounded-lg bg-white dark:bg-slate-700 border-2 border-gray-200 dark:border-slate-600
                    focus:border-[#F54A00]! dark:focus:border-[#F54A00]!
                    text-gray-900 dark:text-gray-200  dark:placeholder:text-slate-300
                    dark:shadow-sm outline-none transition" />
            </form>
        </div>
        <div class="flex items-center space-x-2">
            <nav class="hidden lg:flex items-center space-x-4 text-sm">
                <a class="text-orange-500 hover:text-orange-400 font-medium" href="/upgrade"
                    data-discover="true">Upgrade My Plan</a>
                <a class="text-slate-800 hover:text-gray-900 dark:text-slate-300 dark:hover:text-slate-50 "
                    href="/charts" data-discover="true">Charts</a>
                <a class="text-slate-800 hover:text-gray-900 dark:text-slate-300 dark:hover:text-slate-50"
                    href="/blog" data-discover="true">Blog</a>
                <button class="text-slate-800 hover:text-gray-900 dark:text-slate-300 dark:hover:text-slate-50 flex items-center space-x-1"><span>Help</span><svg
                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="lucide lucide-chevron-down w-4 h-4">
                        <path d="m6 9 6 6 6-6"></path>
                    </svg>
                </button>
            </nav>
            {{-- <div class="relative">

            </div> --}}

            <div class="relative ml-1.5">
                <i data-lucide="bell" class="w-5 h-5 text-gray-800 dark:text-slate-300"></i>
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
                <div tabindex="0" role="button" class="flex items-center space-x-2 ">
                    {{-- <div class="w-10 h-10 rounded-full">
                       
                    </div> --}}
                    <img src="{{ user()->avatar }}" alt="{{ user()->name ?? 'name' }}" class="w-8 h-8 rounded-full">

                    <span class="text-sm font-medium dark:text-slate-300">{{ user()->name ?? 'name' }}</span>
                    <svg class="dark:text-slate-300" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="lucide lucide-chevron-down w-4 h-4">
                        <path d="m6 9 6 6 6-6"></path>
                    </svg>

                    {{-- <div class="text-sm hidden sm:block items-center">
                        <span class="font-semibold text-gray-900 dark:text-gray-200">{{ user()->name ?? 'name' }}</span>
                    </div> --}}
                </div>
                <div class="relative"><button class="flex items-center space-x-2 text-slate-300 hover:text-white">

                    </button>
                </div>
                <ul tabindex="0"
                    class="menu menu-sm dropdown-content bg-white dark:bg-slate-800 text-slate-800 dark:text-white rounded-lg shadow-lg z-10 mt-3 w-64 py-2 space-y-1">

                    <!-- View Profile -->
                    <li>
                        <a href="{{ route('user.profile') }}"
                            class="flex items-center px-4 py-2 hover:bg-gray-100 dark:hover:bg-slate-700 rounded-md text-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
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
                            <span class="font-semibold text-slate-800 dark:text-white">Free Plan</span>
                            <a href="#" class="text-orange-500 text-xs hover:underline">View All Plans</a>
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
                        <a href="#"
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
                                class="w-full text-left  py-2  dark:hover:bg-slate-700 rounded-md flex items-center text-sm">
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
