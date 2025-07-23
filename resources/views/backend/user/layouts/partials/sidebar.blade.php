<!-- Mobile menu button -->
<div class="lg:hidden fixed top-4 left-4 z-35">
    <button id="mobile-menu-btn"
        class="p-2 rounded-md bg-white dark:bg-[#1A1C20] shadow-sm hover:text-black dark:hover:text-white">
        <i data-lucide="menu" class="w-6 h-6 text-gray-500 dark:text-gray-300"></i>
    </button>
</div>

<!-- Sidebar -->
<div id="sidebar"
    class="fixed left-0 top-0 h-screen overflow-y-auto w-64 bg-white dark:bg-[#1A1C20] shadow-lg transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out z-40 justify-between">
    <div class="p-6 flex justify-between">
        <h1 class="text-xl font-bold text-orange-600 dark:text-orange-400">{{ __('RepostChain') }}</h1>
        <span class="p-2 rounded-md bg-white dark:bg-[#1A1C20] shadow-sm lg:hidden" id="close_menu">
            <i data-lucide="x" class="w-4 h-4 text-gray-500 dark:text-gray-300"></i>
        </span>
    </div>

    <!-- Add Credits Button -->
    <div class="px-6 mb-6">
        <a href="{{ route('user.add-credits') }}"
            class="w-full bg-orange-600 dark:bg-orange-500 text-white py-2 px-4 rounded-lg hover:bg-orange-700 dark:hover:bg-orange-600 transition-colors">
            <i data-lucide="plus" class="w-4 h-4 inline mr-2"></i>
            {{ __('Add Credits') }}
    </a>
    </div>

    <!-- Credits -->
    <div class="px-6 mb-6 border-y py-2 border-gray-100 dark:border-gray-800">
        <div class="flex justify-between items-center text-sm">
            <span class="text-gray-600 dark:text-gray-300">{{ __('Available Credits') }}</span>
            <span class="font-bold text-orange-600 dark:text-orange-400">75</span>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="px-3">
        <ul class="space-y-1">
            <li>
                <a href="{{ route('user.dashboard') }}"
                    class="@if ($page_slug == 'dashboard') active @endif sidebar-item dark:hover:text-gray-800 flex items-center px-3 py-3 rounded-lg cursor-pointer text-gray-700 dark:text-gray-200"
                    data-page="dashboard">
                    <i data-lucide="home" class="w-5 h-5 mr-3"></i>
                    <span>{{ __('Dashboard') }}</span>
                </a>
            </li>
            <li>
                <a href="{{ route('user.promote') }}"
                    class="@if ($page_slug == 'promote') active @endif sidebar-item flex items-center px-3 py-3 rounded-lg cursor-pointer text-gray-700 dark:text-gray-100 hover:bg-bg-light-active dark:hover:bg-bg-dark-tertiary hover:text-[#EF4444] dark:hover:text-[#EF4444]"
                    data-page="promote">
                    <i data-lucide="radio-tower" class="w-5 h-5 mr-3"></i>
                    <span>{{ __('Promote') }}</span>
                </a>
            </li>
            <li>
                <a href="{{ route('user.repost-feed') }}"
                    class="@if ($page_slug == 'repost-feed') active @endif sidebar-item dark:hover:text-gray-800  flex items-center px-3 py-3 rounded-lg cursor-pointer text-gray-700 dark:text-gray-200"
                    data-page="repost-feed">
                    <i data-lucide="repeat" class="w-5 h-5 mr-3"></i>
                    <span>{{ __('Repost Feed') }}</span>
                </a>
            </li>
            <li>
                <a href="{{ route('user.cm.campaigns.index') }}"
                    class="@if ($page_slug == 'campains') active @endif sidebar-item dark:hover:text-gray-800  flex items-center px-3 py-3 rounded-lg cursor-pointer text-gray-700 dark:text-gray-200"
                    data-page="campaigns">
                    <i data-lucide="megaphone" class="w-5 h-5 mr-3"></i>
                    <span>{{ __('My Campaigns') }}</span>
                </a>
            </li>
            <li>
                <a href="{{ route('user.analytics') }}"
                    class="@if ($page_slug == 'analytics') active @endif sidebar-item dark:hover:text-gray-800  flex items-center px-3 py-3 rounded-lg cursor-pointer text-gray-700 dark:text-gray-200"
                    data-page="analytics">
                    <i data-lucide="bar-chart-3" class="w-5 h-5 mr-3"></i>
                    <span>{{ __('Analytics') }}</span>
                </a>
            </li>
            <li>
                <a href="{{ route('user.profile') }}"
                    class="@if ($page_slug == 'profile') active @endif sidebar-item dark:hover:text-gray-800   flex items-center px-3 py-3 rounded-lg cursor-pointer text-gray-700 dark:text-gray-200"
                    data-page="profile">
                    <i data-lucide="user" class="w-5 h-5 mr-3"></i>
                    <span>{{ __('Profile') }}</span>
                </a>
            </li>
        </ul>

        <!-- Support Section -->
        <div class="text-xs text-gray-500 dark:text-gray-300 uppercase tracking-wide px-3 mb-3 mt-8">
            {{ __('Support') }}</div>
        <ul class="space-y-1">
            <li>
                <a href="#"
                    class="sidebar-item dark:hover:text-gray-800  flex items-center px-3 py-3 rounded-lg cursor-pointer text-gray-700 dark:text-gray-200"
                    data-page="settings">
                    <i data-lucide="settings" class="w-5 h-5 mr-3"></i>
                    <span>{{ __('Settings') }}</span>
                </a>
            </li>
            <li>
                <a href="#"
                    class="sidebar-item dark:hover:text-gray-800  flex items-center px-3 py-3 rounded-lg cursor-pointer text-gray-700 dark:text-gray-200"
                    data-page="help">
                    <i data-lucide="help-circle" class="w-5 h-5 mr-3"></i>
                    <span>{{ __('Help & Support') }}</span>
                </a>
            </li>
        </ul>
    </nav>
    <div class="flex items-center space-x-2 border-t border-gray-100 px-6 py-4 bg-white fixed bottom-0 w-full">
        <a href="{{ route('user.profile') }}">
            <img src="{{ user()->avatar }}" alt="{{ user()->name ?? 'Name' }}" class="w-8 h-8 rounded-full">
        </a>
        <div class="text-sm">
            <span class="font-semibold text-black">{{ user()->name ?? 'Name' }}</span>
            <div class="text-green-500 text-xs">● Online</div>
        </div>
    </div>
</div>


<div
    class="flex items-center space-x-2 border-t border-gray-100 dark:border-gray-800 px-6 py-4 bg-white dark:bg-[#1A1C20] fixed bottom-0 w-full">
    <a href="{{ route('user.profile') }}">
        <img src="{{ user()->avatar }}" alt="{{ user()->name ?? 'Name' }}" class="w-8 h-8 rounded-full">
    </a>
    <div class="text-sm">
        <span class="font-semibold text-black dark:text-white">{{ user()->name ?? 'Name' }}</span>
        <div class="text-green-500 dark:text-green-400 text-xs">● Online</div>
    </div>
</div>
</div>

<!-- Overlay for mobile -->
<div id="overlay" class="fixed inset-0 bg-black bg-opacity-50 z-30 hidden lg:hidden"></div>


@include('backend.user.campaign_management.includes.audio_player')
