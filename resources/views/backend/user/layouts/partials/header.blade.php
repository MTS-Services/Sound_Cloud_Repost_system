<header class="bg-white shadow-lg px-6 py-4 sticky top-0 z-30">
    <div class="flex justify-between items-center">
        <div>
            {{-- <h1 id="page-title" class="text-2xl font-bold text-gray-900">My Profile</h1> --}}
        </div>
        <div class="flex items-center space-x-4">
            <div class="text-sm text-gray-600">
                <span class="font-semibold">75</span> {{ __('Credits') }}
            </div>
            <div class="relative">
                <i data-lucide="bell" class="w-6 h-6 text-gray-600"></i>
                <span
                    class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-4 h-4 flex items-center justify-center">1</span>
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
                        <img src="{{ auth()->user()->avatar }}" alt="{{ auth()->user()->name }}" />
                    </div>
                    <div class="text-sm">
                        <span class="font-semibold text-black">{{ auth()->user()->name }}</span>
                        <div class="text-green-500 text-xs">● Online</div>
                    </div>
                </div>
                <ul tabindex="0"
                    class="menu menu-sm dropdown-content bg-gray-50 rounded-box z-1 mt-3 w-52 shadow py-2">
                    <li>
                        <div class="flex items-center space-x-2 border-b border-gray-100 px-6 py-4 w-full">
                            <img src="{{ auth()->user()->avatar }}" alt="{{ auth()->user()->name }}"
                                class="w-8 h-8 rounded-full">
                            <div class="text-sm">
                                <span class="font-semibold text-black">{{ auth()->user()->name }}</span>
                                <div class="text-green-500 text-xs">● Online</div>
                            </div>
                        </div>
                    </li>
                    <li>
                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-responsive-nav-link :href="route('logout')"
                                onclick="event.preventDefault();
                                        this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-responsive-nav-link>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</header>
