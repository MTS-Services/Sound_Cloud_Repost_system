<aside class="transition-all duration-300 ease-in-out z-50 max-h-screen py-2"
    :class="{
        'relative': desktop,
        'w-72': desktop && sidebar_expanded,
        'w-20': desktop && !sidebar_expanded,
        'fixed top-0 left-0 h-full': !desktop,
        'w-72 translate-x-0': !desktop && mobile_menu_open,
        'w-72 -translate-x-full': !desktop && !mobile_menu_open,
    }">

    <div class="glass-card h-full custom-scrollbar overflow-y-auto ml-2 mr-1 rounded-xl">
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

            {{-- 1. SINGLE NAVLINK (replaces your original single-navlink) --}}
            <x-admin.navlink type="single" icon="layout-dashboard" name="Dashboard" :route="route('admin.dashboard')"
                active="admin-dashboard" :page_slug="$active" />

            {{-- 2. SIMPLE DROPDOWN (multiple items under one parent) --}}

            <x-admin.navlink type="dropdown" icon="users" name="Admin Management" :page_slug="$active"
                :items="[
                    [
                        'name' => 'Admin',
                        'route' => route('am.admin.index'),
                        'icon' => 'user',
                        'active' => 'admin',
                    ],
                    [
                        'name' => 'Role',
                        'route' => '#',
                        'icon' => 'shield-alert',
                        'active' => 'admin-users-add',
                    ],
                    [
                        'name' => 'Permission',
                        'route' => '#',
                        'icon' => 'shield',
                        'active' => 'admin-users-roles',
                    ],
                ]" />

            <x-admin.navlink type="dropdown" icon="users" name="User Management" :page_slug="$active"
                :items="[
                    [
                        'name' => 'All Users',
                        'route' => '#',
                        'icon' => 'user',
                        'active' => 'admin-users',
                    ],
                    [
                        'name' => 'Add User',
                        'route' => '#',
                        'icon' => 'user-plus',
                        'active' => 'admin-users-add',
                    ],
                    [
                        'name' => 'User Roles',
                        'route' => '#',
                        'icon' => 'shield',
                        'active' => 'admin-users-roles',
                    ],
                ]" />

            {{-- 3. MIXED NAVIGATION (Single items + Dropdowns in one parent) --}}
            <x-admin.navlink type="dropdown" icon="shopping-cart" name="E-commerce" :page_slug="$active"
                :items="[
                    [
                        'type' => 'single',
                        'name' => 'Dashboard',
                        'route' => '#',
                        'icon' => 'bar-chart-3',
                        'active' => 'admin-ecommerce-dashboard',
                    ],
                    [
                        'name' => 'Products',
                        'icon' => 'package',
                        'subitems' => [
                            [
                                'name' => 'All Products',
                                'route' => '#',
                                'icon' => 'list',
                                'active' => 'admin-products-index',
                            ],
                            [
                                'name' => 'Add Product',
                                'route' => '#',
                                'icon' => 'plus',
                                'active' => 'admin-products-create',
                            ],
                            [
                                'name' => 'Categories',
                                'route' => '#',
                                'icon' => 'tag',
                                'active' => 'admin-products-categories',
                            ],
                        ],
                    ],
                    [
                        'type' => 'single',
                        'name' => 'Inventory',
                        'route' => '#',
                        'icon' => 'warehouse',
                        'active' => 'admin-inventory-index',
                    ],
                    [
                        'name' => 'Orders',
                        'icon' => 'shopping-bag',
                        'subitems' => [
                            [
                                'name' => 'All Orders',
                                'route' => '#',
                                'icon' => 'list',
                                'active' => 'admin-orders-index',
                            ],
                            [
                                'name' => 'Pending Orders',
                                'route' => '#',
                                'icon' => 'clock',
                                'active' => 'admin-orders-pending',
                            ],
                        ],
                    ],
                    [
                        'type' => 'single',
                        'name' => 'Reports',
                        'route' => '#',
                        'icon' => 'file-text',
                        'active' => 'admin-ecommerce-reports',
                    ],
                ]" />

            {{-- Mixed Dropdown (Single + Multi items in same dropdown) --}}
            <x-admin.navlink icon="settings" name="Settings" :page_slug="$active" :items="[
                [
                    'name' => 'General Settings',
                    'route' => '#',
                    'icon' => 'sliders',
                    'active' => 'admin-settings-general',
                ],
                [
                    'name' => 'Email Settings',
                    'icon' => 'mail',
                    'subitems' => [
                        [
                            'name' => 'SMTP Config',
                            'route' => '#',
                            'icon' => 'server',
                            'active' => 'admin-settings-email-smtp',
                        ],
                        [
                            'name' => 'Email Templates',
                            'route' => '#',
                            'icon' => 'file-text',
                            'active' => 'admin-settings-email-templates',
                        ],
                    ],
                ],
                [
                    'name' => 'Security',
                    'route' => '#',
                    'icon' => 'lock',
                    'active' => 'admin-settings-security',
                ],
            ]" />

            {{-- Using with Boxicons instead of Lucide --}}
            <x-admin.navlink icon="monitor-cog" name="System" :page_slug="$active" :items="[
                [
                    'name' => 'Cache Management',
                    'route' => '#',
                    'icon' => 'bx bx-data',
                    'boxicon' => true,
                    'active' => 'admin-system-cache',
                ],
                [
                    'name' => 'Logs',
                    'route' => '#',
                    'icon' => 'bx bx-file',
                    'boxicon' => true,
                    'active' => 'admin-system-logs',
                ],
            ]" />

            <x-admin.navlink type="single" icon="help-circle" name="Help &
                    Support"
                :page_slug="$active" />

        </nav>
    </div>
</aside>
