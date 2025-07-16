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
        <!-- Navigation Menu -->
        <nav class="p-2 space-y-2">
            <!-- Dashboard -->

            {{-- 1. SINGLE NAVLINK (replaces your original single-navlink) --}}
            <x-admin.navlink type="single" icon="layout-dashboard" name="Dashboard" :route="route('admin.dashboard')"
                active="admin-dashboard" :page_slug="$active" permission="" />

            <x-admin.navlink type="single" icon="layout-dashboard" name="Button UI" :route="route('button-ui')"
                active="button-ui" :page_slug="$active" />


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
                        'name' => 'All Users',
                        'route' => '#',
                        'icon' => 'user',
                        'active' => 'admin-users',
                        'permission' => 'user-list',
                    ],
                    [
                        'name' => 'Top Reposters',
                        'route' => '#',
                        'icon' => 'user',
                        'active' => 'admin-users',
                        'permission' => 'user-list',
                    ],
                    [
                        'name' => 'Banned Users',
                        'route' => '#',
                        'icon' => 'user',
                        'active' => 'admin-users',
                        'permission' => 'user-list',
                    ],
                ]" />

            <x-admin.navlink type="dropdown" icon="users" name="Package Management" :page_slug="$active"
                :items="[
                    [
                        'name' => 'Credit',
                        'route' => route('pm.credit.index'),
                        'icon' => 'user',
                        'active' => 'credit',
                        'permission' => 'credit-list',
                    ],
                ]" />
        </nav>
    </div>
</aside>
