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

    <title>
        {{ isset($title) ? $title . ' - ' : '' }}
        {{ config('app.name', 'Dashboard Setup') }}
    </title>

    {{-- Theme selector && Theme store --}}
    <script>
        (function() {
            let theme = localStorage.getItem('theme') || 'system';

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
    <script src="{{ asset('assets/js/toggle-theme.js') }}"></script>

    {{-- BoxIcon  --}}
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    @vite(['resources/css/dashboard.css', 'resources/js/app.js'])
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}">

    <script>
        const content_image_upload_url = '{{ route('file.ci_upload') }}';
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if (session('success'))
                showAlert('success', "{!! session('success') !!}");
            @endif

            @if (session('error'))
                showAlert('error', "{!! session('error') !!}");
            @endif

            @if (session('warning'))
                showAlert('warning', "{!! session('warning') !!}");
            @endif
        });
    </script>
    {{-- Custom CSS  --}}
    @stack('css')

</head>

<body x-data="dashboardData()" class="animated-bg overflow-x-hidden">

    <!-- Mobile/Tablet Overlay -->
    <div x-show="mobile_menu_open && !desktop" x-transition:enter="transition-all duration-300 ease-out"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition-all duration-300 ease-in" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" @click="closeMobileMenu()" class="fixed inset-0 z-40 glass-card lg:hidden">
    </div>


    <div class="flex h-screen">
        <!-- Sidebar -->

        <x-admin::side-bar :active="$page_slug" />

        <!-- Main Content -->
        <div class="flex-1 flex flex-col custom-scrollbar overflow-y-auto">
            <!-- Header -->

            <x-admin::header :breadcrumb="$breadcrumb" />

            <!-- Main Content Area -->
            <main class="flex-1 p-4 lg:p-6">
                <div class="mx-auto space-y-6">
                    {{ $slot }}
                </div>
            </main>
        </div>
    </div>

    <x-admin::notification />

    <div id="loading-overlay"
        class="fixed inset-0 bg-black/20 backdrop-blur-sm z-50 flex items-center justify-center hidden">
        <div class="glass-card rounded-2xl py-6 flex items-center gap-3 px-10">
            <div class="w-6 h-6 border-2 border-orange-500 border-t-transparent rounded-full animate-spin">
            </div>
            <span class="text-black dark:text-white font-medium">Loading...</span>
        </div>
    </div>

    <div id="notification-toast"
        class="absolute top-5 right-5 w-72 z-[100] rounded-2xl shadow-2xl bg-white text-black transition-all duration-500 ease-in-out transform translate-x-full opacity-0">
        <div class="p-4 flex items-center justify-between gap-4">
            <div class="flex items-center gap-3 flex-grow">
                <x-heroicon-o-information-circle class="w-6 h-6 text-blue-500 flex-shrink-0" />
                <p id="notification-message" class="text-sm leading-snug font-normal"></p>
            </div>
            <button id="close-notification-btn"
                class="flex-shrink-0 p-1 rounded-full hover:bg-gray-100 transition-colors duration-200">
                <x-heroicon-o-x-mark class="w-5 h-5" />
            </button>
        </div>
    </div>

    {{-- Include the reusable notification manager --}}
    <script src="{{ asset('assets/js/notification-manager.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const loadingOverlay = document.getElementById('loading-overlay');
            const links = document.querySelectorAll(
                'a:not([target="_blank"])'); // Select all links except those that open in a new tab

            links.forEach(link => {
                link.addEventListener('click', () => {
                    // Show the loading overlay
                    loadingOverlay.classList.remove('hidden');
                });
            });

            // Hide the loading overlay when the new page has fully loaded
            window.addEventListener('load', () => {
                loadingOverlay.classList.add('hidden');
            });
        });
    </script>



    <script src="{{ asset('assets/js/lucide-icon.js') }}"></script>
    <script>
        function dashboardData() {
            return {
                // Responsive state
                desktop: window.innerWidth >= 1024,
                mobile: window.innerWidth <= 768,
                tablet: window.innerWidth < 1024,
                sidebar_expanded: window.innerWidth >= 1024,
                mobile_menu_open: false,
                darkMode: true,
                showNotifications: false,

                // Methods
                init() {
                    this.handleResize();
                    window.addEventListener('resize', () => this.handleResize());

                    // Keyboard shortcuts
                    document.addEventListener('keydown', (e) => {
                        if ((e.metaKey || e.ctrlKey) && e.key === 'k') {
                            e.preventDefault();
                            this.focusSearch();
                        }
                    });
                },

                handleResize() {
                    this.desktop = window.innerWidth >= 1024;
                    if (this.desktop) {
                        this.mobile_menu_open = false;
                        this.sidebar_expanded = true;
                    } else {
                        this.sidebar_expanded = false;
                    }
                },

                toggleSidebar() {
                    if (this.desktop) {
                        this.sidebar_expanded = !this.sidebar_expanded;
                    } else {
                        this.mobile_menu_open = !this.mobile_menu_open;
                    }
                },

                closeMobileMenu() {
                    if (!this.desktop) {
                        this.mobile_menu_open = false;
                    }
                },

                toggleNotifications() {
                    this.showNotifications = !this.showNotifications;
                },
            }
        }

        // Initialize Lucide icons after DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            // if (typeof lucide !== 'undefined') {
            lucide.createIcons();
            // }
        });

        // Smooth scrolling for anchor links
        document.addEventListener('click', function(e) {
            if (e.target.matches('a[href^="#"]')) {
                e.preventDefault();
                const target = document.querySelector(e.target.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            }
        });

        // Add loading states for interactive elements
        document.addEventListener('click', function(e) {
            if (e.target.matches('.btn-primary') || e.target.closest('.btn-primary')) {
                const btn = e.target.matches('.btn-primary') ? e.target : e.target.closest('.btn-primary');
                btn.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    btn.style.transform = '';
                }, 150);
            }
        });

        // Add ripple effect to interactive cards
        document.addEventListener('click', function(e) {
            if (e.target.matches('.interactive-card') || e.target.closest('.interactive-card')) {
                const card = e.target.matches('.interactive-card') ? e.target : e.target.closest(
                    '.interactive-card');
                const rect = card.getBoundingClientRect();
                const ripple = document.createElement('span');
                const size = Math.max(rect.width, rect.height);
                const x = e.clientX - rect.left - size / 2;
                const y = e.clientY - rect.top - size / 2;

                ripple.style.cssText = `
                    position: absolute;
                    width: ${size}px;
                    height: ${size}px;
                    left: ${x}px;
                    top: ${y}px;
                    background: rgba(255, 255, 255, 0.1);
                    border-radius: 50%;
                    transform: scale(0);
                    animation: ripple 0.6s ease-out;
                    pointer-events: none;
                `;

                card.style.position = 'relative';
                card.style.overflow = 'hidden';
                card.appendChild(ripple);

                setTimeout(() => {
                    ripple.remove();
                }, 600);
            }
        });

        // Add CSS for ripple animation
        const style = document.createElement('style');
        style.textContent = `
            @keyframes ripple {
            to {
            transform: scale(2);
            opacity: 0;
            }
            }
        `;
        document.head.appendChild(style);
    </script>
    {{-- Custom JS --}}
    <script src="{{ asset('assets/js/password.js') }}"></script>
    @stack('js')

</body>

</html>
