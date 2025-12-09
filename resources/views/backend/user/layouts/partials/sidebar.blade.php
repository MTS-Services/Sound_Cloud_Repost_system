<div x-show="sidebarOpen" @click.away="sidebarOpen = false" class="fixed z-30 lg:hidden" x-cloak></div>
<div :class="{ 'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen }"
    class="fixed left-0 lg:top-20 top-17 w-64 lg:w-[15%]  min-h-[90vh] lg:h-[calc(100vh-4rem)] bg-white dark:bg-slate-800 border-r border-gray-100 dark:border-slate-700 flex flex-col z-40 transform transition-transform duration-300 ease-in-out lg:translate-x-0">

    <!-- Profile Section -->
    <div class="p-3 md:p-4 border-b border-gray-100 dark:border-slate-700">
        <div class="flex items-center space-x-3 mb-3 md:mb-4">
            <img src="{{ soundcloud_image(user()->avatar) }}" alt="{{ user()->name ?? 'name' }}"
                class="w-8 h-8 md:w-10 md:h-10 rounded-full">
            <div>
                <h3 class="text-slate-800 dark:text-white text-md md:text-lg font-semibold">{{ user()->name ?? 'name' }}</h3>
                <div class="flex items-center space-x-2 mt-2 dark:text-white text-black ">
                    <img src="{{ asset('frontend/user/credit-icon.png') }}" alt="" class="w-8 ">
                    <h6 class="font-medium">{{ userCredits() . ' ' . __('Credits') }} </h6>
                </div>
            </div>
        </div>
    </div>

    <!-- Nav Section -->
    <nav class="flex-1 overflow-y-auto px-2 md:px-3 py-2 md:py-4">
        <ul class="space-y-0.5 md:space-y-1">
            <li>
                <a href="{{ route('user.dashboard') }}" wire:navigate
                    class="sidebar-item flex items-center px-2 py-1.5 md:px-3 md:py-2.5 rounded-lg transition-colors text-slate-700 dark:text-slate-300 hover:text-orange-600 hover:bg-slate-100 dark:hover:text-slate-50 dark:hover:bg-slate-700 @if ($page_slug == 'dashboard') active @endif">
                    <x-heroicon-o-home class="w-4 h-4 md:w-5 md:h-5 mr-2 md:mr-3" />
                    <span class="text-xs md:text-sm">{{ __('Home') }}</span>
                </a>
            </li>
            <li>
                <a href="{{ route('user.cm.campaigns') }}" wire:navigate
                    class="sidebar-item flex items-center px-2 py-1.5 md:px-3 md:py-2.5 rounded-lg transition-colors
                    text-slate-700 dark:text-slate-300 hover:text-orange-600 hover:bg-slate-100 dark:hover:text-slate-50 dark:hover:bg-slate-700 @if ($page_slug == 'campaign-feed') active @endif">
                    <x-heroicon-o-clipboard class="w-4 h-4 md:w-5 md:h-5 mr-2 md:mr-3" />
                    <span class="text-xs md:text-sm">{{ __('Campaigns') }}</span>
                </a>
            </li>
            <li>
                <a href="{{ route('user.members') }}" wire:navigate
                    class="sidebar-item flex items-center px-2 py-1.5 md:px-3 md:py-2.5 rounded-lg transition-colors
                    text-slate-700 dark:text-slate-300 hover:text-orange-600 hover:bg-slate-100 dark:hover:text-slate-50 dark:hover:bg-slate-700 @if ($page_slug == 'members') active @endif">
                    <x-lucide-users class="w-4 h-4 md:w-5 md:h-5 mr-2 md:mr-3" />
                    <span class="text-xs md:text-sm">{{ __('Members') }}</span>
                </a>
            </li>
            <li>
                <a href="{{ route('user.reposts-request') }}" wire:navigate
                    class="sidebar-item flex items-center px-2 py-1.5 md:px-3 md:py-2.5 rounded-lg transition-colors
                    text-slate-700 dark:text-slate-300 hover:text-orange-600 hover:bg-slate-100 dark:hover:text-slate-50 dark:hover:bg-slate-700 @if ($page_slug == 'request') active @endif">
                    <x-heroicon-o-user-plus class="w-4 h-4 md:w-5 md:h-5 mr-2 md:mr-3" />
                    <span class="text-xs md:text-sm">{{ __('Requests') }}<span wire:poll.visible.10s
                            class="ml-1 inline-flex items-center rounded-full bg-orange-500 px-2 py-1 text-xs font-semibold text-white @if ($page_slug == 'request') !text-orange-500
!bg-white @endif">{{ App\Models\RepostRequest::incoming()->pending()->count() }}</span></span>
                </a>
            </li>
            <li>
                <a href="{{ route('user.charts') }}" wire:navigate
                    class="sidebar-item flex items-center px-2 py-1.5 md:px-3 md:py-2.5 rounded-lg transition-colors
                    text-slate-700 dark:text-slate-300 hover:text-orange-600 hover:bg-slate-100 dark:hover:text-slate-50 dark:hover:bg-slate-700 @if ($page_slug == 'chart') active @endif">
                    <x-heroicon-o-chart-bar class="w-4 h-4 md:w-5 md:h-5 mr-2 md:mr-3" />
                    <span class="text-xs md:text-sm">{{ __('Charts') }}</span>
                </a>
            </li>
        </ul>

        <!-- Divider -->
        <div class="border-t border-gray-100 dark:border-slate-700 my-2 md:my-4"></div>

        <div class="space-y-0.5 md:space-y-1">
            <h4
                class="text-slate-400 dark:text-slate-400 text-[10px] md:text-xs font-semibold uppercase tracking-wider mb-1 md:mb-3">
                {{ __('My Account') }}</h4>

            <a href="{{ route('user.cm.my-campaigns') }}" wire:navigate
                class="sidebar-item flex items-center px-2 py-1.5 md:px-3 md:py-2.5 rounded-lg transition-colors
                text-slate-700 dark:text-slate-300 hover:text-orange-600 hover:bg-slate-100 dark:hover:text-slate-50 dark:hover:bg-slate-700 @if ($page_slug == 'campaigns') active @endif">
                <x-heroicon-o-cube class="w-4 h-4 md:w-5 md:h-5 mr-2 md:mr-3" />
                <span class="text-xs md:text-sm">{{ __('My Campaigns') }}</span>
            </a>

            <a href="{{ route('user.analytics') }}"wire:navigate
                class="flex items-center px-2 py-1.5 md:px-3 md:py-2 rounded-lg transition-colors
                text-slate-500 dark:text-slate-300 hover:text-orange-600 hover:bg-slate-100 dark:hover:text-slate-50 dark:hover:bg-slate-700 @if ($page_slug == 'analytics') active sidebar-item @endif">
                <x-heroicon-o-presentation-chart-line class="w-4 h-4 md:w-5 md:h-5 mr-2 md:mr-3" />
                <span class="text-xs md:text-sm">{{ __('Analytics') }}</span>
            </a>

            <a href="{{ route('user.my-account') }}"wire:navigate
                class="sidebar-item flex items-center px-2 py-1.5 md:px-3 md:py-2.5 rounded-lg transition-colors
                text-slate-700 dark:text-slate-300 hover:text-orange-600 hover:bg-slate-100 dark:hover:text-slate-50 dark:hover:bg-slate-700 @if ($page_slug == 'my-account') active @endif">
                <x-heroicon-o-user class="w-4 h-4 md:w-5 md:h-5 mr-2 md:mr-3" />
                <span class="text-xs md:text-sm">{{ __('My Account') }}</span>
            </a>

            <a href="{{ route('user.settings') }}" wire:navigate
                class="flex items-center px-2 py-1.5 md:px-3 md:py-2 rounded-lg transition-colors
                text-slate-500 dark:text-slate-300 hover:text-orange-600 hover:bg-slate-100 dark:hover:text-slate-50 dark:hover:bg-slate-700 @if ($page_slug == 'settings') active sidebar-item @endif">
                <x-heroicon-o-cog-6-tooth class="w-4 h-4 md:w-5 md:h-5 mr-2 md:mr-3" />
                <span class="text-xs md:text-sm">{{ __('Settings') }}</span>
            </a>

            <a href="{{ route('user.help-support') }}" wire:navigate
                class="flex items-center px-2 py-1.5 md:px-3 md:py-2 rounded-lg transition-colors
                text-slate-500 dark:text-slate-300 hover:text-orange-600 hover:bg-slate-100 dark:hover:text-slate-50 dark:hover:bg-slate-700 @if ($page_slug == 'help') active sidebar-item @endif">
                <x-heroicon-o-question-mark-circle class="w-4 h-4 md:w-5 md:h-5 mr-2 md:mr-3" />
                <span class="text-xs md:text-sm">{{ __('Help & Support') }}</span>
            </a>
        </div>
    </nav>

    <!-- Bottom Card (pinned) -->
    @if (!proUser())
        <div class="p-3 md:p-4 border-t border-gray-100 dark:border-slate-700">
            <div
                class="bg-gradient-to-r from-orange-500 to-orange-600 dark:from-orange-600 dark:to-orange-800 rounded-lg p-3 md:p-4 text-white">
                <div class="flex items-center space-x-1 md:space-x-2 mb-1 md:mb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 md:w-5 md:h-5 text-yellow-300" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="m2 4 3 12h14l3-12-6 7-4-7-4 7-6-7zm3 16h14"></path>
                    </svg>
                    <span class="font-semibold text-sm md:text-base">{{ __('Premium Plan') }}</span>
                </div>
                <p class="text-xs md:text-sm text-orange-100 mb-2 md:mb-3">
                    {{ __('Get your music in front of more people') }}</p>
                <a href="{{ route('user.plans') }}" wire:navigate
                    class="w-full! bg-white text-orange-600 text-sm md:text-base font-semibold py-1.5 md:py-2 px-3 md:px-4 rounded-lg hover:bg-orange-50 transition-colors">{{ __('Upgrade') }}</a>
            </div>
        </div>
    @endif
</div>
