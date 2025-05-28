<aside class="transition-all duration-300 ease-in-out z-50 max-h-screen"
    :class="{
        'relative': desktop,
        'w-72': desktop && sidebar_expanded,
        'w-20': desktop && !sidebar_expanded,
        'fixed top-0 left-0 h-full': !desktop,
        'w-72 translate-x-0': !desktop && mobile_menu_open,
        'w-72 -translate-x-full': !desktop && !mobile_menu_open,
    }">

    <div class="glass-card h-full custom-scrollbar overflow-y-auto my-2 ml-2 mr-1 rounded-xl">
        <!-- Sidebar Header -->
        <a href="{{ route('admin.dashboard') }}" class="p-3 border-b border-white/10 inline-block">
            <div class="flex items-center gap-4">
                <div
                    class="w-10 h-10 glass-card shadow inset-shadow-lg bg-bg-white dark:bg-bg-black p-0 rounded-xl flex items-center justify-center">
                    <i data-lucide="zap" class="!w-4 !h-4"></i>
                </div>
                <div x-show="(desktop && sidebar_expanded) || (!desktop && mobile_menu_open)"
                    x-transition:enter="transition-all duration-300 delay-75"
                    x-transition:enter-start="opacity-0 translate-x-4"
                    x-transition:enter-end="opacity-100 translate-x-0" x-transition:leave="transition-all duration-200"
                    x-transition:leave-start="opacity-100 translate-x-0"
                    x-transition:leave-end="opacity-0 -translate-x-4">
                    <h1 class="text-xl font-bold text-text-light-primary dark:text-text-white">Dashboard</h1>
                    <p class="text-text-light-secondary dark:text-text-dark-primary text-sm">Dashboard Pro</p>
                </div>
            </div>
        </a>

        <!-- Quick Stats (Collapsed Sidebar) -->
        {{-- <div x-show="desktop && !sidebar_expanded" x-transition:enter="transition-all duration-300 delay-100"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            class="p-4 border-b border-white/10">
            <div class="space-y-3">
                <div class="w-8 h-8 bg-green-500/20 rounded-lg flex items-center justify-center relative "
                    data-tooltip="Active Users">
                    <i data-lucide="activity" class="w-4 h-4 text-green-400"></i>
                    <div class="absolute -top-1 -right-1 w-3 h-3 bg-green-400 rounded-full notification-badge">
                    </div>
                </div>
                <div class="w-8 h-8 bg-blue-500/20 rounded-lg flex items-center justify-center "
                    data-tooltip="Total Users">
                    <i data-lucide="users" class="w-4 h-4 text-blue-400"></i>
                </div>
                <div class="w-8 h-8 bg-purple-500/20 rounded-lg flex items-center justify-center "
                    data-tooltip="Revenue">
                    <i data-lucide="trending-up" class="w-4 h-4 text-purple-400"></i>
                </div>
            </div>
        </div> --}}

        <!-- Navigation Menu -->
        <nav class="p-2 space-y-2">
            <!-- Dashboard -->
            <x-admin.single-navlink active="admin-dashboard" icon="layout-dashboard" :page_slug="$active" name="Dashboard" :route="route('admin.dashboard')" />
            <x-admin.single-navlink active="admin-dashboard-2" icon="layout-dashboard" :page_slug="$active" name="Dashboard 2nd" :route="route('admin.dashboard.2')" />

            <!-- Analytics -->
            {{-- <a href="#" @click="setActiveTab('analytics')" :class="{ 'active': activeTab === 'analytics' }"
                class="sidebar-item flex items-center gap-4 p-3 rounded-xl hover:bg-white/10 text-text-white transition-all duration-200 group">
                <div
                    class="w-8 h-8 bg-green-500/20 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform relative">
                    <i data-lucide="bar-chart-3" class="w-5 h-5 text-green-400 flex-shrink-0"></i>
                    <div class="absolute -top-1 -right-1 w-3 h-3 bg-red-400 rounded-full notification-badge">
                    </div>
                </div>
                <span x-show="(desktop && sidebar_expanded) || (!desktop && mobile_menu_open)"
                    x-transition:enter="transition-all duration-300 delay-75"
                    x-transition:enter-start="opacity-0 translate-x-4"
                    x-transition:enter-end="opacity-100 translate-x-0" x-transition:leave="transition-all duration-200"
                    x-transition:leave-start="opacity-100 translate-x-0"
                    x-transition:leave-end="opacity-0 -translate-x-4" class="font-medium">Analytics</span>
                <div x-show="(desktop && sidebar_expanded) || (!desktop && mobile_menu_open)"
                    class="ml-auto bg-red-500 text-text-white text-xs px-2 py-1 rounded-full">New</div>
            </a>

            <!-- Users -->
            <a href="#" @click="setActiveTab('users')" :class="{ 'active': activeTab === 'users' }"
                class="sidebar-item flex items-center gap-4 p-3 rounded-xl hover:bg-white/10 text-text-white transition-all duration-200 group">
                <div
                    class="w-8 h-8 bg-purple-500/20 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i data-lucide="users" class="w-5 h-5 text-purple-400 flex-shrink-0"></i>
                </div>
                <span x-show="(desktop && sidebar_expanded) || (!desktop && mobile_menu_open)"
                    x-transition:enter="transition-all duration-300 delay-75"
                    x-transition:enter-start="opacity-0 translate-x-4"
                    x-transition:enter-end="opacity-100 translate-x-0" x-transition:leave="transition-all duration-200"
                    x-transition:leave-start="opacity-100 translate-x-0"
                    x-transition:leave-end="opacity-0 -translate-x-4" class="font-medium">Users</span>
                <div x-show="(desktop && sidebar_expanded) || (!desktop && mobile_menu_open)"
                    class="ml-auto text-text-dark-primary text-sm">1.2k</div>
            </a>

            <!-- Projects -->
            <a href="#" @click="setActiveTab('projects')" :class="{ 'active': activeTab === 'projects' }"
                class="sidebar-item flex items-center gap-4 p-3 rounded-xl hover:bg-white/10 text-text-white transition-all duration-200 group">
                <div
                    class="w-8 h-8 bg-yellow-500/20 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i data-lucide="folder" class="w-5 h-5 text-yellow-400 flex-shrink-0"></i>
                </div>
                <span x-show="(desktop && sidebar_expanded) || (!desktop && mobile_menu_open)"
                    x-transition:enter="transition-all duration-300 delay-75"
                    x-transition:enter-start="opacity-0 translate-x-4"
                    x-transition:enter-end="opacity-100 translate-x-0" x-transition:leave="transition-all duration-200"
                    x-transition:leave-start="opacity-100 translate-x-0"
                    x-transition:leave-end="opacity-0 -translate-x-4" class="font-medium">Projects</span>
            </a>

            <!-- Messages -->
            <a href="#" @click="setActiveTab('messages')" :class="{ 'active': activeTab === 'messages' }"
                class="sidebar-item flex items-center gap-4 p-3 rounded-xl hover:bg-white/10 text-text-white transition-all duration-200 group">
                <div
                    class="w-8 h-8 bg-pink-500/20 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform relative">
                    <i data-lucide="message-circle" class="w-5 h-5 text-pink-400 flex-shrink-0"></i>
                    <div class="absolute -top-1 -right-1 w-3 h-3 bg-pink-400 rounded-full notification-badge">
                    </div>
                </div>
                <span x-show="(desktop && sidebar_expanded) || (!desktop && mobile_menu_open)"
                    x-transition:enter="transition-all duration-300 delay-75"
                    x-transition:enter-start="opacity-0 translate-x-4"
                    x-transition:enter-end="opacity-100 translate-x-0" x-transition:leave="transition-all duration-200"
                    x-transition:leave-start="opacity-100 translate-x-0"
                    x-transition:leave-end="opacity-0 -translate-x-4" class="font-medium">Messages</span>
                <div x-show="(desktop && sidebar_expanded) || (!desktop && mobile_menu_open)"
                    class="ml-auto bg-pink-500 text-text-white text-xs px-2 py-1 rounded-full">3</div>
            </a> --}}

            <!-- Divider -->
            <div class="my-4 border-t border-white/10"></div>

            <!-- Settings -->
            <a href="#"
                class="sidebar-item flex items-center gap-4 p-3 rounded-xl hover:bg-white/10 text-text-white transition-all duration-200 group">
                <div
                    class="w-8 h-8 bg-gray-500/20 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i data-lucide="settings" class="w-5 h-5 text-gray-400 flex-shrink-0"></i>
                </div>
                <span x-show="(desktop && sidebar_expanded) || (!desktop && mobile_menu_open)"
                    x-transition:enter="transition-all duration-300 delay-75"
                    x-transition:enter-start="opacity-0 translate-x-4"
                    x-transition:enter-end="opacity-100 translate-x-0" x-transition:leave="transition-all duration-200"
                    x-transition:leave-start="opacity-100 translate-x-0"
                    x-transition:leave-end="opacity-0 -translate-x-4" class="font-medium">Settings</span>
            </a>

            <!-- Help -->
            <a href="#"
                class="sidebar-item flex items-center gap-4 p-3 rounded-xl hover:bg-white/10 text-text-white transition-all duration-200 group">
                <div
                    class="w-8 h-8 bg-indigo-500/20 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i data-lucide="help-circle" class="w-5 h-5 text-indigo-400 flex-shrink-0"></i>
                </div>
                <span x-show="(desktop && sidebar_expanded) || (!desktop && mobile_menu_open)"
                    x-transition:enter="transition-all duration-300 delay-75"
                    x-transition:enter-start="opacity-0 translate-x-4"
                    x-transition:enter-end="opacity-100 translate-x-0" x-transition:leave="transition-all duration-200"
                    x-transition:leave-start="opacity-100 translate-x-0"
                    x-transition:leave-end="opacity-0 -translate-x-4" class="font-medium">Help &
                    Support</span>
            </a>
        </nav>

        <!-- User Profile - Bottom -->
        <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-white/10 glass-card">
            <div class="flex items-center gap-3">
                <div class="relative">
                    <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=40&h=40&fit=crop&crop=face&auto=format"
                        alt="Profile" class="w-10 h-10 rounded-xl object-cover ring-2 ring-white/20">
                    <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-400 rounded-full border-2 border-white/20">
                    </div>
                </div>
                <div x-show="(desktop && sidebar_expanded) || (!desktop && mobile_menu_open)"
                    x-transition:enter="transition-all duration-300 delay-75"
                    x-transition:enter-start="opacity-0 translate-x-4"
                    x-transition:enter-end="opacity-100 translate-x-0" x-transition:leave="transition-all duration-200"
                    x-transition:leave-start="opacity-100 translate-x-0"
                    x-transition:leave-end="opacity-0 -translate-x-4" class="flex-1">
                    <p class="text-text-white text-sm font-medium">Alex Johnson</p>
                    <p class="text-text-dark-primary text-xs">Product Manager</p>
                </div>
                <button x-show="(desktop && sidebar_expanded) || (!desktop && mobile_menu_open)"
                    class="w-8 h-8 rounded-lg hover:bg-white/10 flex items-center justify-center transition-colors">
                    <i data-lucide="more-horizontal" class="w-4 h-4 text-text-dark-primary"></i>
                </button>
            </div>
        </div>
    </div>
</aside>
