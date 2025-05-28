 <!-- Mobile menu button -->
    <div class="lg:hidden fixed top-4 left-4 z-50">
        <button id="mobile-menu-btn" class="p-2 rounded-md bg-white shadow-md">
            <i data-lucide="menu" class="w-6 h-6"></i>
        </button>
    </div>

    <!-- Sidebar -->
    <div id="sidebar" class="fixed left-0 top-0 h-full w-64 bg-white shadow-lg transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out z-40">
        <div class="p-6">
            <h1 class="text-xl font-bold text-orange-600">{{ __('RepostChain') }}</h1>
        </div>
        
        <!-- Submit Track Button -->
        <div class="px-6 mb-6">
            <button class="w-full bg-orange-600 text-white py-2 px-4 rounded-lg hover:bg-orange-700 transition-colors">
                <i data-lucide="plus" class="w-4 h-4 inline mr-2"></i>
                {{ __('Submit Track') }}
            </button>
        </div>

        <!-- Credits -->
        <div class="px-6 mb-6 border-y py-2 border-gray-100">
            <div class="flex justify-between items-center text-sm">
                <span class="text-gray-600">{{ __('Available Credits') }}</span>
                <span class="font-bold text-orange-600">75</span>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="px-3">
            <ul class="space-y-1">
                <li>
                    <a href="{{ route('user.dashboard') }}" class="@if ($page_slug == 'dashboard') active @endif sidebar-item flex items-center px-3 py-3 rounded-lg cursor-pointer text-gray-700" data-page="dashboard">
                        <i data-lucide="home" class="w-5 h-5 mr-3"></i>
                        <span>{{ __('Dashboard') }}</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="@if ($page_slug == '') active @endif sidebar-item flex items-center px-3 py-3 rounded-lg cursor-pointer text-gray-700" data-page="repost-feed">
                        <i data-lucide="repeat" class="w-5 h-5 mr-3"></i>
                        <span>{{ __('Repost Feed') }}</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="@if ($page_slug == '') active @endif sidebar-item flex items-center px-3 py-3 rounded-lg cursor-pointer text-gray-700" data-page="campaigns">
                        <i data-lucide="megaphone" class="w-5 h-5 mr-3"></i>
                        <span>{{ __('My Campaigns') }}</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="@if ($page_slug == '') active @endif sidebar-item flex items-center px-3 py-3 rounded-lg cursor-pointer text-gray-700" data-page="analytics">
                        <i data-lucide="bar-chart-3" class="w-5 h-5 mr-3"></i>
                        <span>{{ __('Analytics') }}</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('user.profile') }}" class="@if ($page_slug == 'profile') active @endif sidebar-item  flex items-center px-3 py-3 rounded-lg cursor-pointer text-gray-700" data-page="profile">
                        <i data-lucide="user" class="w-5 h-5 mr-3"></i>
                        <span>{{ __('Profile') }}</span>
                    </a>
                </li>
            </ul>
            
            <!-- Support Section -->
            <div class="text-xs text-gray-500 uppercase tracking-wide px-3 mb-3 mt-8">{{ __('Support') }}</div>
            <ul class="space-y-1">
                <li>
                    <a href="#" class="sidebar-item flex items-center px-3 py-3 rounded-lg cursor-pointer text-gray-700" data-page="settings">
                        <i data-lucide="settings" class="w-5 h-5 mr-3"></i>
                        <span>{{ __('Settings') }}</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="sidebar-item flex items-center px-3 py-3 rounded-lg cursor-pointer text-gray-700" data-page="help">
                        <i data-lucide="help-circle" class="w-5 h-5 mr-3"></i>
                        <span>{{ __('Help & Support') }}</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>

    <!-- Overlay for mobile -->
    <div id="overlay" class="fixed inset-0 bg-black bg-opacity-50 z-30 hidden lg:hidden"></div>