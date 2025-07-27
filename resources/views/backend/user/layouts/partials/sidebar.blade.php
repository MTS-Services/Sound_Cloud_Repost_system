<div x-show="sidebarOpen" @click.away="sidebarOpen = false" class="fixed z-30 lg:hidden" x-cloak></div>
<div :class="{ 'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen }"
    class="fixed left-0 lg:top-20 top-17 w-64 lg:w-[15%]  h-[92vh] lg:h-[calc(100vh-9vh)] bg-white dark:bg-slate-800 border-r border-gray-100 dark:border-slate-700 flex flex-col z-40 transform transition-transform duration-300 ease-in-out lg:translate-x-0">

    <!-- Profile Section -->
    <div class="p-3 md:p-4 border-b border-gray-100 dark:border-slate-700">
        <div class="flex items-center space-x-3 mb-3 md:mb-4">
            <img src="{{ auth_storage_url(user()->avatar) }}" alt="{{ user()->name ?? 'name' }}"
                class="w-8 h-8 md:w-10 md:h-10 rounded-full">
            <div>
                <h3 class="text-slate-800 dark:text-white text-xs md:text-sm">{{ user()->name ?? 'name' }}</h3>
                <p class="text-slate-400 text-[10px] md:text-xs">{{ $totalCredits }} Credits</p>
            </div>
        </div>
        <a href="{{ route('user.add-credits') }}" wire:navigate
            class="w-full bg-orange-500 hover:bg-orange-600 text-white text-sm md:text-base py-1.5 md:py-2 px-3 md:px-4 rounded-lg flex items-center justify-center space-x-2 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor"
                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="lucide lucide-plus w-4 h-4 md:w-5 md:h-5">
                <path d="M5 12h14"></path>
                <path d="M12 5v14"></path>
            </svg>
            <span class="text-xs md:text-sm">Add Credits</span>
        </a>
    </div>

    <!-- Nav Section -->
    <nav class="flex-1 overflow-y-auto px-2 md:px-3 py-2 md:py-4">
        <ul class="space-y-0.5 md:space-y-1">
            <li>
                <a href="{{ route('user.dashboard') }}" wire:navigate
                    class="sidebar-item flex items-center px-2 py-1.5 md:px-3 md:py-2.5 rounded-lg transition-colors text-slate-700 dark:text-slate-300 hover:text-orange-600 hover:bg-slate-100 dark:hover:text-slate-50 dark:hover:bg-slate-700 @if ($page_slug == 'dashboard') active @endif">
                    <i data-lucide="home" class="w-4 h-4 md:w-5 md:h-5 mr-2 md:mr-3"></i>
                    <span class="text-xs md:text-sm">{{ __('Home') }}</span>
                </a>
            </li>
            <li>
                <a href="{{ route('user.promote') }}" wire:navigate
                    class="sidebar-item flex items-center px-2 py-1.5 md:px-3 md:py-2.5 rounded-lg transition-colors text-slate-700 dark:text-slate-300 hover:text-orange-600 hover:bg-slate-100 dark:hover:text-slate-50 dark:hover:bg-slate-700 @if ($page_slug == 'promote') active @endif">
                    <i data-lucide="radio-tower" class="w-4 h-4 md:w-5 md:h-5 mr-2 md:mr-3"></i>
                    <span class="text-xs md:text-sm">{{ __('Promote') }}</span>
                </a>
            </li>
            <li>
                <a href="{{ route('user.cm.campaigns') }}" wire:navigate
                    class="sidebar-item flex items-center px-2 py-1.5 md:px-3 md:py-2.5 rounded-lg transition-colors
                    text-slate-700 dark:text-slate-300 hover:text-orange-600 hover:bg-slate-100 dark:hover:text-slate-50 dark:hover:bg-slate-700 @if ($page_slug == 'campaign-feed') active @endif">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 md:w-5 md:h-5 mr-2 md:mr-3"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <circle cx="8" cy="18" r="4"></circle>
                        <path d="M12 18V2l7 4"></path>
                    </svg>
                    <span class="text-xs md:text-sm">{{ __('Campaigns') }}</span>
                </a>
            </li>
            <li>
                <a href="{{ route('user.mm.members.index') }}" wire:navigate
                    class="sidebar-item flex items-center px-2 py-1.5 md:px-3 md:py-2.5 rounded-lg transition-colors
                    text-slate-700 dark:text-slate-300 hover:text-orange-600 hover:bg-slate-100 dark:hover:text-slate-50 dark:hover:bg-slate-700 @if ($page_slug == 'members') active @endif">
                    <i data-lucide="users" class="w-4 h-4 md:w-5 md:h-5 mr-2 md:mr-3"></i>
                    <span class="text-xs md:text-sm">{{ __('Members') }}</span>
                </a>
            </li>
            <li>
                <a href="{{ route('user.mm.members.request') }}" wire:navigate
                    class="sidebar-item flex items-center px-2 py-1.5 md:px-3 md:py-2.5 rounded-lg transition-colors
                    text-slate-700 dark:text-slate-300 hover:text-orange-600 hover:bg-slate-100 dark:hover:text-slate-50 dark:hover:bg-slate-700 @if ($page_slug == 'request') active @endif">
                    <i data-lucide="users" class="w-4 h-4 md:w-5 md:h-5 mr-2 md:mr-3"></i>
                    <span class="text-xs md:text-sm">{{ __('Requests') }}</span>
                </a>
            </li>
            <li>
                <a href="{{ route('user.profile') }}" wire:navigate
                    class="sidebar-item flex items-center px-2 py-1.5 md:px-3 md:py-2.5 rounded-lg transition-colors
                    text-slate-700 dark:text-slate-300 hover:text-orange-600 hover:bg-slate-100 dark:hover:text-slate-50 dark:hover:bg-slate-700 @if ($page_slug == 'profile') active @endif">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 md:w-5 md:h-5 mr-2 md:mr-3"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="lucide lucide-trending-up">
                        <polyline points="22 7 13.5 15.5 8.5 10.5 2 17"></polyline>
                        <polyline points="16 7 22 7 22 13"></polyline>
                    </svg>
                    <span class="text-xs md:text-sm">{{ __('Chart') }}</span>
                </a>
            </li>
        </ul>

        <!-- Divider -->
        <div class="border-t border-gray-100 dark:border-slate-700 my-2 md:my-4"></div>

        <div class="space-y-0.5 md:space-y-1">
            <h4
                class="text-slate-400 dark:text-slate-400 text-[10px] md:text-xs font-semibold uppercase tracking-wider mb-1 md:mb-3">
                My Account</h4>

            <a href="{{ route('user.cm.my-campaigns') }}" wire:navigate
                class="sidebar-item flex items-center px-2 py-1.5 md:px-3 md:py-2.5 rounded-lg transition-colors
                text-slate-700 dark:text-slate-300 hover:text-orange-600 hover:bg-slate-100 dark:hover:text-slate-50 dark:hover:bg-slate-700 @if ($page_slug == 'campains') active @endif">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 md:w-5 md:h-5 mr-2 md:mr-3" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round">
                    <circle cx="8" cy="18" r="4"></circle>
                    <path d="M12 18V2l7 4"></path>
                </svg>
                <span class="text-xs md:text-sm">{{ __('My Campaigns') }}</span>
            </a>

            <a href="{{ route('user.analytics') }}"wire:navigate
                class="flex items-center px-2 py-1.5 md:px-3 md:py-2 rounded-lg transition-colors
                text-slate-500 dark:text-slate-300 hover:text-orange-600 hover:bg-slate-100 dark:hover:text-slate-50 dark:hover:bg-slate-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 md:w-5 md:h-5 mr-2 md:mr-3" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 3v18h18"></path>
                    <path d="M18 17V9"></path>
                    <path d="M13 17V5"></path>
                    <path d="M8 17v-3"></path>
                </svg>
                <span class="text-xs md:text-sm">Analytics</span>
            </a>

            <a href="{{ route('user.pm.my-account') }}"wire:navigate
                class="sidebar-item flex items-center px-2 py-1.5 md:px-3 md:py-2.5 rounded-lg transition-colors
                text-slate-700 dark:text-slate-300 hover:text-orange-600 hover:bg-slate-100 dark:hover:text-slate-50 dark:hover:bg-slate-700 @if ($page_slug == 'my-account') active @endif">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 md:w-5 md:h-5 mr-2 md:mr-3" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path>
                    <circle cx="12" cy="7" r="4"></circle>
                </svg>
                <span class="text-xs md:text-sm">My Account</span>
            </a>

            <a href="#" wire:navigate
                class="flex items-center px-2 py-1.5 md:px-3 md:py-2 rounded-lg transition-colors
                text-slate-500 dark:text-slate-300 hover:text-orange-600 hover:bg-slate-100 dark:hover:text-slate-50 dark:hover:bg-slate-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 md:w-5 md:h-5 mr-2 md:mr-3" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path
                        d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15-.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z">
                    </path>
                    <circle cx="12" cy="12" r="3"></circle>
                </svg>
                <span class="text-xs md:text-sm">Settings</span>
            </a>

            <a href="#" wire:navigate
                class="flex items-center px-2 py-1.5 md:px-3 md:py-2 rounded-lg transition-colors
                text-slate-500 dark:text-slate-300 hover:text-orange-600 hover:bg-slate-100 dark:hover:text-slate-50 dark:hover:bg-slate-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 md:w-5 md:h-5 mr-2 md:mr-3" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path>
                    <path d="M12 17h.01"></path>
                </svg>
                <span class="text-xs md:text-sm">Help & Support</span>
            </a>
        </div>
    </nav>

    <!-- Bottom Card (pinned) -->
    <div class="p-3 md:p-4 border-t border-gray-100 dark:border-slate-700">
        <div
            class="bg-gradient-to-r from-orange-500 to-orange-600 dark:from-orange-600 dark:to-orange-800 rounded-lg p-3 md:p-4 text-white">
            <div class="flex items-center space-x-1 md:space-x-2 mb-1 md:mb-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 md:w-5 md:h-5 text-yellow-300" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round">
                    <path d="m2 4 3 12h14l3-12-6 7-4-7-4 7-6-7zm3 16h14"></path>
                </svg>
                <span class="font-semibold text-sm md:text-base">Premium Plan</span>
            </div>
            <p class="text-xs md:text-sm text-orange-100 mb-2 md:mb-3">Get your music in front of more people</p>
            <button
                class="w-full bg-white text-orange-600 text-sm md:text-base font-semibold py-1.5 md:py-2 px-3 md:px-4 rounded-lg hover:bg-orange-50 transition-colors">Upgrade</button>
        </div>
    </div>
</div>
