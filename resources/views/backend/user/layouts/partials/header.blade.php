<header class="bg-bg-light-primary dark:bg-bg-dark-primary shadow-lg px-6 py-4 sticky top-0 z-30 w-full">
    <div class="flex justify-between items-center">
        <!-- Search Bar Added -->
        <form class="flex-1 flex justify-center ">
            <div class="relative w-full max-w-md items-center">
                <span class="absolute inset-y-0 left-0 flex items-center pl-4">
                    <i data-lucide="search" class="w-5 h-5 text-text-light-muted dark:text-text-dark-muted"></i>
                </span>
                <input type="search" placeholder="Search..."
                    class="w-full pl-12 pr-4 py-3 rounded-lg bg-bg-light-primary dark:bg-bg-dark-tertiary border-2 border-bg-light-muted dark:border-bg-dark-muted
                    focus:border-[#F54A00]! dark:focus:border-[#F54A00]!
                    text-text-light-primary dark:text-text-dark-secondary
                    placeholder:text-text-light-muted dark:placeholder:text-text-dark-muted shadow-sm outline-none transition" />
            </div>
        </form>
        
        <div class="flex items-center space-x-4 ml-8">
            <div class="text-sm text-text-light-primary dark:text-text-dark-secondary">
                <span class="font-semibold">75</span> {{ __('Credits') }}
            </div>
            <div class="relative">
                <i data-lucide="bell" class="w-6 h-6 text-text-light-primary dark:text-text-dark-muted"></i>
                <span class="absolute -top-2 -right-1 bg-red-500  text-white text-xs rounded-full w-4 h-4 flex items-center justify-center">1</span>
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
                        <img src="" alt="name" />
                    </div>
                    <div class="text-sm">
                        <span
                            class="font-semibold text-text-light-secondary dark:text-text-dark-secondary">{{ auth()->user()->name }}</span>
                        <div class="text-[#1CA577] dark:text-text-dark-active text-xs">● Online</div>
                    </div>
                </div>
                <ul tabindex="0"
                    class="menu menu-sm dropdown-content bg-bg-light-tertiary dark:bg-bg-dark-tertiary rounded-box z-1 mt-3 w-52 shadow py-2">
                    <li>
                        <div
                            class="flex items-center space-x-2 border-b border-bg-light-muted dark:border-bg-dark-muted px-6 py-4 w-full">
                            <img src="{{ auth()->user()->avatar }}" alt="{{ auth()->user()->name }} "
                                class="w-8 h-8 rounded-full">
                            <div class="text-sm">
                                <span
                                    class="font-semibold text-text-light-secondary dark:text-text-dark-secondary">{{ auth()->user()->name }}</span>
                                <div class="text-[#1CA577] dark:text-text-dark-active text-xs">● Online</div>
                            </div>
                        </div>
                    </li>
                    <li>
                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-responsive-nav-link :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-responsive-nav-link>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</header>
