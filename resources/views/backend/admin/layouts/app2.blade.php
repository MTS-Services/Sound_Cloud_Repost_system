<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>
        {{ isset($title) ? $title . ' - ' : '' }}
        {{ config('app.name', 'Dashboard Setup') }}
    </title>

    {{-- Theme selector && Theme store --}}
    <script>
        // On page load, immediately apply theme from localStorage to prevent flash
        (function() {
            let theme = localStorage.getItem('theme') || 'system';

            // Apply theme immediately
            if (theme === 'system') {
                const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                document.documentElement.classList.toggle('dark', systemPrefersDark);
                document.documentElement.setAttribute('data-theme', systemPrefersDark ? 'dark' : 'light');
            } else {
                document.documentElement.classList.toggle('dark', theme === 'dark');
                document.documentElement.setAttribute('data-theme', theme);
            }
        })();
    </script>
    <script src="{{ asset('assets/js/theme-toggle.js') }}"></script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
        }

        /* Modern gradient backgrounds */
        .bg-gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .bg-gradient-dark {
            background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
        }

        .bg-gradient-card {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0.05) 100%);
        }

        /* Custom scrollbar */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 3px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 3px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.5);
        }

        /* Sidebar hover effects */
        .sidebar-item {
            position: relative;
            overflow: hidden;
        }

        .sidebar-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 3px;
            background: linear-gradient(to bottom, #3b82f6, #8b5cf6);
            transform: scaleY(0);
            transition: transform 0.2s ease;
        }

        .sidebar-item:hover::before,
        .sidebar-item.active::before {
            transform: scaleY(1);
        }

        /* glass-card morphism effect */
        .glass-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .glass-card-dark {
            background: rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        /* Animated background */
        .animated-bg {
            background: linear-gradient(-45deg, #667eea, #764ba2, #f093fb, #f5576c);
            background-size: 400% 400%;
            animation: gradientBG 15s ease infinite;
        }

        @keyframes gradientBG {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        /* Floating animation */
        .float {
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-10px);
            }
        }

        /* Pulse animation */
        .pulse-slow {
            animation: pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        /* Notification badge */
        .notification-badge {
            animation: bounce 2s infinite;
        }

        /* Modern button styles */
        .btn-primary {
            background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-primary::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .btn-primary:hover::before {
            left: 100%;
        }

        /* Card hover effects */
        .card-hover {
            transition: all 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
        }
    </style>
    @vite(['resources/css/admin-dashboard.css', 'resources/js/app.js'])
</head>

<body x-data="{
    sidebar_expanded: true,
    mobile_menu_open: false,
    mobile: false,
    tablet: false,
    desktop: false,
    init() {
        this.updateScreenStates();
        window.addEventListener('resize', () => {
            this.updateScreenStates();
        });
        // Initialize Lucide icons
        lucide.createIcons();
    },
    updateScreenStates() {
        const width = window.innerWidth;
        this.mobile = width < 768;
        this.tablet = width >= 768 && width < 1024;
        this.desktop = width >= 1024;

        // Reset states based on screen size
        if (this.desktop) {
            // Desktop: sidebar expanded by default, no mobile menu
            this.sidebar_expanded = true;
            this.mobile_menu_open = false;
        } else {
            // Mobile/Tablet: sidebar collapsed by default
            this.mobile_menu_open = false;
        }
    },
    toggleSidebar() {
        if (this.desktop) {
            // Desktop: toggle between expanded and collapsed
            this.sidebar_expanded = !this.sidebar_expanded;
        } else {
            // Mobile/Tablet: toggle mobile menu
            this.mobile_menu_open = !this.mobile_menu_open;
        }
    },
    closeMobileMenu() {
        if (!this.desktop) {
            this.mobile_menu_open = false;
        }
    }
}" class="bg-gradient-theme min-h-screen">

    <!-- Mobile/Tablet Overlay -->
    <div x-show="mobile_menu_open && !desktop" x-transition:enter="transition-opacity duration-300"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity duration-300" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" @click="closeMobileMenu()"
        class="fixed inset-0 z-40 sidebar-overlay lg:hidden">
    </div>

    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside class="sidebar-transition z-50"
            :class="{
                // Desktop styles
                'relative': desktop,
                'w-64': desktop && sidebar_expanded,
                'w-16': desktop && !sidebar_expanded,
            
                // Mobile/Tablet styles
                'fixed top-0 left-0 h-full': !desktop,
                'w-64 translate-x-0': !desktop && mobile_menu_open,
                'w-64 -translate-x-full': !desktop && !mobile_menu_open,
            }">
            <div class="bg-white/10 backdrop-blur-lg h-full shadow-lg border-r border-white/20">
                <!-- Sidebar Header -->
                <div class="flex items-center gap-3 p-4 border-b border-white/20">
                    <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center">
                        <i data-lucide="home" class="w-5 h-5 text-white"></i>
                    </div>
                    <span x-show="(desktop && sidebar_expanded) || (!desktop && mobile_menu_open)"
                        x-transition:enter="transition-opacity duration-300 delay-75"
                        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                        x-transition:leave="transition-opacity duration-200" x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0" class="font-semibold text-white text-lg">
                        Dashboard
                    </span>
                </div>

                <!-- Navigation Menu -->
                <nav class="p-4 space-y-2">
                    <!-- Home -->
                    <a href="#"
                        class="flex items-center gap-3 p-3 rounded-lg hover:bg-white/10 text-white transition-colors">
                        <i data-lucide="home" class="w-5 h-5 flex-shrink-0"></i>
                        <span x-show="(desktop && sidebar_expanded) || (!desktop && mobile_menu_open)"
                            x-transition:enter="transition-opacity duration-300 delay-75"
                            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                            x-transition:leave="transition-opacity duration-200" x-transition:leave-start="opacity-100"
                            x-transition:leave-end="opacity-0">
                            Home
                        </span>
                    </a>

                    <!-- Analytics -->
                    <a href="#"
                        class="flex items-center gap-3 p-3 rounded-lg hover:bg-white/10 text-white transition-colors">
                        <i data-lucide="bar-chart-3" class="w-5 h-5 flex-shrink-0"></i>
                        <span x-show="(desktop && sidebar_expanded) || (!desktop && mobile_menu_open)"
                            x-transition:enter="transition-opacity duration-300 delay-75"
                            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                            x-transition:leave="transition-opacity duration-200" x-transition:leave-start="opacity-100"
                            x-transition:leave-end="opacity-0">
                            Analytics
                        </span>
                    </a>

                    <!-- Users -->
                    <a href="#"
                        class="flex items-center gap-3 p-3 rounded-lg hover:bg-white/10 text-white transition-colors">
                        <i data-lucide="users" class="w-5 h-5 flex-shrink-0"></i>
                        <span x-show="(desktop && sidebar_expanded) || (!desktop && mobile_menu_open)"
                            x-transition:enter="transition-opacity duration-300 delay-75"
                            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                            x-transition:leave="transition-opacity duration-200" x-transition:leave-start="opacity-100"
                            x-transition:leave-end="opacity-0">
                            Users
                        </span>
                    </a>

                    <!-- Settings -->
                    <a href="#"
                        class="flex items-center gap-3 p-3 rounded-lg hover:bg-white/10 text-white transition-colors">
                        <i data-lucide="settings" class="w-5 h-5 flex-shrink-0"></i>
                        <span x-show="(desktop && sidebar_expanded) || (!desktop && mobile_menu_open)"
                            x-transition:enter="transition-opacity duration-300 delay-75"
                            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                            x-transition:leave="transition-opacity duration-200" x-transition:leave-start="opacity-100"
                            x-transition:leave-end="opacity-0">
                            Settings
                        </span>
                    </a>
                </nav>

                <!-- Sidebar Footer -->
                <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-white/20">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-8 h-8 bg-gradient-to-r from-blue-400 to-purple-500 rounded-full flex items-center justify-center">
                            <i data-lucide="user" class="w-4 h-4 text-white"></i>
                        </div>
                        <div x-show="(desktop && sidebar_expanded) || (!desktop && mobile_menu_open)"
                            x-transition:enter="transition-opacity duration-300 delay-75"
                            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                            x-transition:leave="transition-opacity duration-200" x-transition:leave-start="opacity-100"
                            x-transition:leave-end="opacity-0" class="text-white">
                            <p class="text-sm font-medium">John Doe</p>
                            <p class="text-xs opacity-75">Admin</p>
                        </div>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col min-h-screen">
            <!-- Header -->
            <header class="bg-white/10 backdrop-blur-lg border-b border-white/20 sticky top-0 z-30">
                <div class="flex items-center justify-between p-4">
                    <div class="flex items-center gap-4">
                        <!-- Menu Toggle Button -->
                        <button @click="toggleSidebar()"
                            class="p-2 rounded-lg hover:bg-white/10 text-white transition-colors focus:outline-none focus:ring-2 focus:ring-white/20"
                            :aria-label="desktop ? (sidebar_expanded ? 'Collapse sidebar' : 'Expand sidebar') : (mobile_menu_open ?
                                'Close menu' : 'Open menu')">
                            <i data-lucide="menu" class="w-5 h-5"></i>
                        </button>

                        <h1 class="text-xl font-semibold text-white">Dashboard</h1>
                    </div>

                    <!-- Status Indicators (for debugging) -->
                    <div class="flex items-center gap-4 text-sm text-white/75">
                        <span x-show="mobile" class="bg-red-500/20 px-2 py-1 rounded">Mobile</span>
                        <span x-show="tablet" class="bg-yellow-500/20 px-2 py-1 rounded">Tablet</span>
                        <span x-show="desktop" class="bg-green-500/20 px-2 py-1 rounded">Desktop</span>
                        <span x-show="desktop && sidebar_expanded"
                            class="bg-blue-500/20 px-2 py-1 rounded">Expanded</span>
                        <span x-show="desktop && !sidebar_expanded"
                            class="bg-blue-500/20 px-2 py-1 rounded">Collapsed</span>
                        <span x-show="!desktop && mobile_menu_open" class="bg-purple-500/20 px-2 py-1 rounded">Menu
                            Open</span>
                    </div>
                </div>
            </header>

            <!-- Main Content Area -->
            <main class="flex-1 p-6 overflow-y-auto">
                <div class="max-w-4xl mx-auto space-y-6">
                    <!-- Welcome Card -->
                    <div class="bg-white/10 backdrop-blur-lg rounded-xl p-6 border border-white/20">
                        <h2 class="text-2xl font-bold text-white mb-2">Welcome Back!</h2>
                        <p class="text-white/75">Here's what's happening with your dashboard today.</p>
                    </div>

                    <!-- Stats Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div class="bg-white/10 backdrop-blur-lg rounded-xl p-6 border border-white/20">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-blue-500/20 rounded-lg flex items-center justify-center">
                                    <i data-lucide="users" class="w-6 h-6 text-blue-400"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-white">1,234</h3>
                                    <p class="text-white/75 text-sm">Total Users</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white/10 backdrop-blur-lg rounded-xl p-6 border border-white/20">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-green-500/20 rounded-lg flex items-center justify-center">
                                    <i data-lucide="trending-up" class="w-6 h-6 text-green-400"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-white">$45,678</h3>
                                    <p class="text-white/75 text-sm">Revenue</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white/10 backdrop-blur-lg rounded-xl p-6 border border-white/20">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-purple-500/20 rounded-lg flex items-center justify-center">
                                    <i data-lucide="shopping-bag" class="w-6 h-6 text-purple-400"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-white">892</h3>
                                    <p class="text-white/75 text-sm">Orders</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Content Sections -->
                    <div class="space-y-6">
                        <div class="bg-white/10 backdrop-blur-lg rounded-xl p-6 border border-white/20">
                            <h3 class="text-xl font-semibold text-white mb-4">Recent Activity</h3>
                            <div class="space-y-4">
                                <div class="flex items-center gap-4 p-4 bg-white/5 rounded-lg">
                                    <div class="w-8 h-8 bg-blue-500/20 rounded-full flex items-center justify-center">
                                        <i data-lucide="user-plus" class="w-4 h-4 text-blue-400"></i>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-white text-sm">New user registered</p>
                                        <p class="text-white/50 text-xs">2 minutes ago</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-4 p-4 bg-white/5 rounded-lg">
                                    <div class="w-8 h-8 bg-green-500/20 rounded-full flex items-center justify-center">
                                        <i data-lucide="shopping-cart" class="w-4 h-4 text-green-400"></i>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-white text-sm">New order received</p>
                                        <p class="text-white/50 text-xs">5 minutes ago</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-4 p-4 bg-white/5 rounded-lg">
                                    <div
                                        class="w-8 h-8 bg-yellow-500/20 rounded-full flex items-center justify-center">
                                        <i data-lucide="alert-triangle" class="w-4 h-4 text-yellow-400"></i>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-white text-sm">System maintenance scheduled</p>
                                        <p class="text-white/50 text-xs">1 hour ago</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white/10 backdrop-blur-lg rounded-xl p-6 border border-white/20">
                            <h3 class="text-xl font-semibold text-white mb-4">Performance Overview</h3>
                            <p class="text-white/75 mb-4">This section demonstrates how the sidebar adapts to different
                                screen sizes while maintaining a consistent user experience.</p>

                            <div class="space-y-4">
                                <div class="bg-white/5 p-4 rounded-lg">
                                    <h4 class="text-white font-medium mb-2">Desktop Behavior</h4>
                                    <p class="text-white/75 text-sm">On desktop screens (≥1024px), the sidebar toggles
                                        between expanded (full width with text) and collapsed (icon-only) states.</p>
                                </div>

                                <div class="bg-white/5 p-4 rounded-lg">
                                    <h4 class="text-white font-medium mb-2">Mobile & Tablet Behavior</h4>
                                    <p class="text-white/75 text-sm">On smaller screens (&lt;1024px), the sidebar
                                        slides in from the left as an overlay with a backdrop blur effect.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    {{-- <script>
        // Initialize Lucide icons after Alpine has loaded
        document.addEventListener('alpine:init', () => {
            setTimeout(() => {
                lucide.createIcons();
            }, 100);
        });
    </script> --}}

    <script src="{{ asset('assets/js/lucide-icon.js') }}"></script>
    <script>
        lucide.createIcons();
    </script>
</body>

</html>
