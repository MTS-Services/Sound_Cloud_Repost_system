<aside class="transition-all duration-300 ease-in-out z-50 max-h-screen py-2 pl-2"
    :class="{
        // 'relative': desktop,
        'w-72': desktop && sidebar_expanded,
        'w-20': desktop && !sidebar_expanded,
        'fixed top-0 left-0 h-full': !desktop,
        'w-72 translate-x-0': !desktop && mobile_menu_open,
        'w-72 -translate-x-full': !desktop && !mobile_menu_open,
    }">

    <div class="sidebar-glass-card h-full custom-scrollbar rounded-xl overflow-y-auto">
        <!-- Sidebar Header -->
        <a href="{{ route('admin.dashboard') }}" class="p-3 border-b border-white/10 inline-block">
            <div class="flex items-center gap-4">
                <div
                    class="w-10 h-10 glass-card shadow inset-shadow-lg bg-bg-white dark:bg-bg-black p-0 rounded-xl flex items-center justify-center">
                    @if (app_setting('favicon') && app_setting('favicon_dark'))
                        <img src="{{ storage_url(app_setting('favicon')) }}" alt="{{ config('app.name') }}"
                            class="w-full h-full dark:hidden">
                        <img src="{{ storage_url(app_setting('favicon_dark')) }}" alt="{{ config('app.name') }}"
                            class="w-full h-full hidden dark:block">
                    @else
                        <img src="{{ asset('assets/favicons/fav icon 1.svg') }}" alt="{{ config('app.name') }}"
                            class="w-full h-full dark:hidden">
                        <img src="{{ asset('assets/favicons/fav icon 2 (1).svg') }}" alt="{{ config('app.name') }}"
                            class="w-full h-full hidden dark:block">
                    @endif
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
        <!-- Navigation Menu -->
        <nav class="p-2 space-y-2">
            <!-- Dashboard -->

            {{-- 1. SINGLE NAVLINK (replaces your original single-navlink) --}}
            <x-admin.navlink type="single" icon="layout-dashboard" name="Dashboard" :route="route('admin.dashboard')"
                active="admin-dashboard" :page_slug="$active" permission="" />

            {{-- <x-admin.navlink type="single" icon="layout-dashboard" name="Button UI" :route="route('button-ui')"
                active="button-ui" :page_slug="$active" /> --}}


            <x-admin.navlink type="dropdown" icon="users" name="Admin Management" :page_slug="$active"
                :items="[
                    [
                        'name' => 'Admin',
                        'route' => route('am.admin.index'),
                        'icon' => 'user',
                        'active' => 'admin',
                        'permission' => 'admin-list',
                    ],
                    [
                        'name' => 'Role',
                        'route' => route('am.role.index'),
                        'icon' => 'shield',
                        'active' => 'role',
                        'permission' => 'role-list',
                    ],
                    [
                        'name' => 'Permission',
                        'route' => route('am.permission.index'),
                        'icon' => 'shield-check',
                        'active' => 'permission',
                        'permission' => 'permission-list',
                    ],
                ]" />

            <x-admin.navlink type="dropdown" icon="users" name="User Management" :page_slug="$active"
                :items="[
                    [
                        'name' => 'Users',
                        'route' => route('um.user.index'),
                        'icon' => 'user',
                        'active' => 'user',
                        'permission' => 'user-list',
                    ],
                
                    [
                        'name' => 'User Plan',
                        'route' => route('um.user-plane.index'),
                        'icon' => 'user',
                        'active' => 'users_plane',
                        'permission' => 'user-plane-list',
                    ],
                    // [
                    //     'name' => 'Top Reposters',
                    //     'route' => '#',
                    //     'icon' => 'user',
                    //     'active' => '',
                    //     'permission' => 'user-list',
                    // ],
                    // [
                    //     'name' => 'Banned Users',
                    //     'route' => '#',
                    //     'icon' => 'user',
                    //     'active' => 'admin-users',
                    //     'permission' => 'user-list',
                    // ],
                ]" />

            <x-admin.navlink type="dropdown" icon="credit-card" name="Package Management" :page_slug="$active"
                :items="[
                    [
                        'name' => 'Features',
                        'route' => route('pm.feature.index'),
                        'icon' => 'megaphone',
                        'active' => 'feature',
                        'permission' => 'features-list',
                    ],
                    [
                        'name' => 'Plans',
                        'route' => route('pm.plan.index'),
                        'icon' => 'shopping-bag',
                        'active' => 'plan',
                        'permission' => 'plan-list',
                    ],
                    [
                        'name' => 'Credit',
                        'route' => route('pm.credit.index'),
                        'icon' => 'credit-card',
                        'active' => 'credit',
                        'permission' => 'credit-list',
                    ],
                ]" />
            <x-admin.navlink type="dropdown" icon="megaphone" name="Campaign Management" :page_slug="$active"
                :items="[
                    [
                        'name' => 'Campaigns',
                        'route' => route('cm.campaign.index'),
                        'icon' => 'megaphone',
                        'active' => 'campaign',
                        'permission' => 'campaign-list',
                    ],
                ]" />
            <x-admin.navlink type="dropdown" icon="shopping-cart" name="Order Management" :page_slug="$active"
                :items="[
                    [
                        'name' => 'Orders',
                        'route' => route('om.order.index'),
                        'icon' => 'megaphone',
                        'active' => 'order',
                        'permission' => 'order-list',
                    ],
                    // [
                    //     'name' => 'Purchase History',
                    //     'route' => route('om.credit-transaction.purchase'),
                    //     'icon' => 'megaphone',
                    //     'active' => 'purchase',
                    //     'permission' => 'purchase-list',
                    // ],
                ]" />
            <x-admin.navlink type="dropdown" icon="rocket" name="Repost Request Tracking" :page_slug="$active"
                :items="[
                    [
                        'name' => 'Repost Request',
                        'route' => route('rrm.request.index'),
                        'icon' => 'megaphone',
                        'active' => 'repost_request',
                        'permission' => 'request-list',
                    ],
                    [
                        'name' => 'Repost',
                        'route' => route('rm.repost.index'),
                        'icon' => 'megaphone',
                        'active' => 'repost',
                        'permission' => 'repost-list',
                    ],
                ]" />
            <x-admin.navlink type="single" icon="dollar-sign" name="Payments" :route="route('om.credit-transaction.payments')" active="payment"
                :page_slug="$active" permission="payment-list" />
            <x-admin.navlink type="single" icon="credit-card" name="Credit Transactions" :route="route('om.credit-transaction.index')"
                active="credit-transaction" :page_slug="$active" permission="transaction-list" />

            <x-admin.navlink type="dropdown" icon="rocket" name="Faq Management" :page_slug="$active"
                :items="[
                    [
                        'name' => 'Faq-Category',
                        'route' => route('fm.faq-category.index'),
                        'icon' => 'megaphone',
                        'active' => 'faq_category',
                        'permission' => 'faqCategory-list',
                    ],
                    [
                        'name' => 'Faq',
                        'route' => route('fm.faq.index'),
                        'icon' => 'megaphone',
                        'active' => 'faq',
                        'permission' => 'faq-list',
                    ],
                ]" />
            <x-admin.navlink icon="settings" name="Settings" :page_slug="$active" :items="[
                [
                    'name' => 'General Settings',
                    'route' => route('app-settings.general'),
                    'icon' => 'sliders',
                    'active' => 'app-general-settings',
                    'permission' => 'application-setting-general',
                ],
                [
                    'name' => 'Database Settings',
                    'route' => route('app-settings.database'),
                    'icon' => 'database',
                    'active' => 'app-database-settings',
                    'permission' => 'application-setting-database',
                ],
                [
                    'name' => 'Email Settings',
                    'route' => route('app-settings.smtp'),
                    'icon' => 'server',
                    'active' => 'app-smtp-settings',
                    'permission' => 'application-setting-smtp',
                ],
                [
                    'name' => 'Payment Gateway Setup',
                    'route' => route('app-settings.payment_setup'),
                    'icon' => 'credit-card',
                    'active' => 'app-payment-gateway-setup',
                    'permission' => 'application-setting-payment-setup',
                ],
            ]" />
        </nav>
    </div>
</aside>
