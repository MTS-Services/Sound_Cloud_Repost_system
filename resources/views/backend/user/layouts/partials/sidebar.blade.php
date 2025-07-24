

<div class="w-74 bg-white dark:bg-slate-800 border-r border-gray-100 dark:border-slate-700 flex flex-col h-full">
    <!-- Profile Section -->
    <div class="p-4 border-b border-gray-100 dark:border-slate-700">
        <div class="flex items-center space-x-3 mb-4">
            <img src="./RepostChain Web App_files/pexels-photo-2379004.jpeg"
                alt="Bhathiya Udara" class="w-10 h-10 rounded-full">
            <div>
                <h3 class="text-slate-800 dark:text-white font-medium text-base">Bhathiya Udara</h3>
                <p class="text-slate-400 text-xs">117 Credits</p>
            </div>
        </div>
        <a href="{{ route('user.add-credits') }}"
            class="w-full bg-orange-500 hover:bg-orange-600 text-white text-base font-medium py-2 px-4 rounded-lg transition-colors flex items-center justify-center space-x-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                stroke-linejoin="round" class="lucide lucide-plus w-4 h-4">
                <path d="M5 12h14"></path>
                <path d="M12 5v14"></path>
            </svg>
            <span>Add Credits</span>
        </a>
    </div>

    <!-- Nav Section -->
    <nav class="flex-1 overflow-y-auto px-3">
        <ul class="space-y-1">
            <li>
                <a href="{{ route('user.dashboard') }}"
                   class="sidebar-item transition-colors flex items-center px-3 py-3 rounded-lg cursor-pointer font-medium
                   @if ($page_slug == 'dashboard') active @endif
                   hover:text-orange-600 hover:bg-slate-100 dark:hover:text-orange-600 dark:text-white dark:hover:bg-slate-700"
                   data-page="dashboard">
                    <i data-lucide="home" class="w-4 h-4 mr-3"></i>
                    <span>{{ __('Dashboard') }}</span>
                </a>
            </li>
            <li>
                <a href="{{ route('user.promote') }}"
                   class="sidebar-item transition-colors  flex items-center px-3 py-3 rounded-lg cursor-pointer font-medium
                   @if ($page_slug == 'promote') active @endif
                   hover:text-orange-600 hover:bg-slate-100 dark:hover:text-orange-600 dark:text-white dark:hover:bg-slate-700"
                   data-page="promote">
                    <i data-lucide="radio-tower" class="w-4 h-4 mr-3"></i>
                    <span>{{ __('Promote') }}</span>
                </a>
            </li>
            <li>
                <a href="{{ route('user.campaign.feed') }}"
                   class="sidebar-item transition-colors flex items-center px-3 py-3 rounded-lg cursor-pointer font-medium
                   @if ($page_slug == 'campaign-feed') active @endif
                   hover:text-orange-600 hover:bg-slate-100 dark:hover:text-orange-600 dark:text-white dark:hover:bg-slate-700"
                   data-page="campaign-feed">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-3" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <circle cx="8" cy="18" r="4"></circle>
                        <path d="M12 18V2l7 4"></path>
                    </svg>
                    <span>{{ __('Campaign Feed') }}</span>
                </a>
            </li>
            <li>
                <a href="{{ route('user.cm.campaigns.index') }}"
                   class="sidebar-item transition-colors flex items-center px-3 py-3 rounded-lg cursor-pointer font-medium
                   @if ($page_slug == 'campains') active @endif
                   hover:text-orange-600 hover:bg-slate-100 dark:hover:text-orange-600 dark:text-white dark:hover:bg-slate-700"
                   data-page="campaigns">
                    <i data-lucide="megaphone" class="w-4 h-4 mr-3"></i>
                    <span>{{ __('My Campaigns') }}</span>
                </a>
            </li>
            <li>
                <a href="{{ route('user.profile') }}"
                   class="sidebar-item transition-colors flex items-center px-3 py-3 rounded-lg cursor-pointer font-medium
                   @if ($page_slug == 'profile') active @endif
                   hover:text-orange-600 hover:bg-slate-100 dark:hover:text-orange-600 dark:text-white dark:hover:bg-slate-700"
                   data-page="profile">
                    <i data-lucide="user" class="w-4 h-4 mr-3"></i>
                    <span>{{ __('Profile') }}</span>
                </a>
            </li>
        </ul>

        <!-- Account Links -->
        <div class="border-t border-gray-100 dark:border-slate-700 my-4"></div>
        <div class="px-2 space-y-1">
            <h4 class="text-slate-400 dark:text-slate-400 text-xs font-semibold uppercase tracking-wider mb-3">My Account</h4>
            <a href="{{ route('user.analytics') }}"
               class="flex items-center space-x-3 px-3 py-2 text-base font-medium rounded-lg transition-colors text-slate-500 dark:text-slate-300 hover:text-orange-600 hover:bg-slate-100 dark:hover:text-orange-600 dark:text-white dark:hover:bg-slate-700">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" class="lucide lucide-bar-chart3 w-5 h-5">
                    <path d="M3 3v18h18"></path>
                    <path d="M18 17V9"></path>
                    <path d="M13 17V5"></path>
                    <path d="M8 17v-3"></path>
                </svg>
                <span>Analytics</span>
            </a>
            <a href="{{ route('user.profile') }}"
               class="flex items-center space-x-3 px-3 py-2 text-base font-medium rounded-lg transition-colors text-slate-500 dark:text-slate-300 hover:text-orange-600 hover:bg-slate-100 dark:hover:text-orange-600 dark:text-white dark:hover:bg-slate-700">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" class="lucide lucide-user w-5 h-5">
                    <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path>
                    <circle cx="12" cy="7" r="4"></circle>
                </svg>
                <span>My Account</span>
            </a>
            <a href="#"
               class="flex items-center space-x-3 px-3 py-2 text-base font-medium rounded-lg transition-colors text-slate-500 dark:text-slate-300 hover:text-orange-600 hover:bg-slate-100 dark:hover:text-orange-600 dark:text-white dark:hover:bg-slate-700">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-settings w-5 h-5">
                    <path
                        d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15-.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z">
                    </path>
                    <circle cx="12" cy="12" r="3"></circle>
                </svg>
                <span>Settings</span>
            </a>
            <a href="#"
               class="flex items-center space-x-3 px-3 py-2 text-base font-medium rounded-lg transition-colors text-slate-500 dark:text-slate-300 hover:text-orange-600 hover:bg-slate-100 dark:hover:text-orange-600 dark:text-white dark:hover:bg-slate-700">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-help-circle w-5 h-5">
                    <circle cx="12" cy="12" r="10"></circle>
                    <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path>
                    <path d="M12 17h.01"></path>
                </svg>
                <span>Help & Support</span>
            </a>
        </div>
    </nav>

    <!-- Bottom Card -->
    <div class="p-4 border-t border-gray-100 dark:border-slate-700">
        <div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-lg p-4 text-white dark:from-orange-600 dark:to-orange-800">
            <div class="flex items-center space-x-2 mb-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24"
                    height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round"
                    class="lucide lucide-crown w-5 h-5 text-yellow-300">
                    <path d="m2 4 3 12h14l3-12-6 7-4-7-4 7-6-7zm3 16h14"></path>
                </svg>
                <span class="font-semibold text-base">Premium Plan</span>
            </div>
            <p class="text-xs text-orange-100 mb-3">Get your music in front of more people</p>
            <button class="w-full bg-white text-orange-600 text-base font-semibold py-2 px-4 rounded-lg hover:bg-orange-50 transition-colors">Upgrade</button>
        </div>
    </div>
</div>
