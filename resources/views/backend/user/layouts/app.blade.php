<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- csrf --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Sound Cloud') }}</title>
    {{-- <script src="https://cdn.tailwindcss.com"></script> --}}
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    {{-- Custom CSS --}}
    <link rel="stylesheet" href="{{ asset('assets/frontend/css/custome.css') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 font-sans">
    {{-- Sidebar --}}
    @include('backend.user.layouts.partials.sidebar')

    <!-- Main Content -->
    <div class="lg:ml-64 min-h-screen">
        {{-- Header --}}
        @include('backend.user.layouts.partials.header')

        {{-- Main Content --}}
        <div class="p-6">
            @yield('content')


            <!-- Repost Feed Content -->
            <div id="content-repost-feed" class="page-content hidden">
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="text-center py-12">
                        <i data-lucide="repeat" class="w-12 h-12 text-gray-400 mx-auto mb-4"></i>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Repost Feed</h3>
                        <p class="text-gray-600">Latest tracks available for reposting will appear here.</p>
                    </div>
                </div>
            </div>

            <!-- Campaigns Content -->
            <div id="content-campaigns" class="page-content hidden">
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="text-center py-12">
                        <i data-lucide="megaphone" class="w-12 h-12 text-gray-400 mx-auto mb-4"></i>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">My Campaigns</h3>
                        <p class="text-gray-600">Your active and past campaigns will appear here.</p>
                    </div>
                </div>
            </div>

            <!-- Analytics Content -->
            <div id="content-analytics" class="page-content hidden">
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="text-center py-12">
                        <i data-lucide="bar-chart-3" class="w-12 h-12 text-gray-400 mx-auto mb-4"></i>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Analytics</h3>
                        <p class="text-gray-600">Detailed analytics and insights will appear here.</p>
                    </div>
                </div>
            </div>

            <!-- Settings Content -->
            <div id="content-settings" class="page-content hidden">
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="text-center py-12">
                        <i data-lucide="settings" class="w-12 h-12 text-gray-400 mx-auto mb-4"></i>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Settings</h3>
                        <p class="text-gray-600">Account settings and preferences will appear here.</p>
                    </div>
                </div>
            </div>

            <!-- Help Content -->
            <div id="content-help" class="page-content hidden">
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="text-center py-12">
                        <i data-lucide="help-circle" class="w-12 h-12 text-gray-400 mx-auto mb-4"></i>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Help & Support</h3>
                        <p class="text-gray-600">Help documentation and support options will appear here.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        lucide.createIcons();
        // Mobile menu functionality
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('overlay');

        mobileMenuBtn.addEventListener('click', () => {
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        });

        overlay.addEventListener('click', () => {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
        });

        // Sidebar navigation with proper ul/li structure
        const sidebarItems = document.querySelectorAll('.sidebar-item');
        const pageContents = document.querySelectorAll('.page-content');
        const pageTitle = document.getElementById('page-title');
        const pageDescription = document.getElementById('page-description');

        // Function to set active sidebar item
        function setActiveSidebarItem(targetPage) {
            // Remove active class from all items
            sidebarItems.forEach(item => {
                item.classList.remove('active');
                item.classList.add('text-gray-700');
            });

            // Add active class to clicked item
            const activeItem = document.querySelector(`[data-page="${targetPage}"]`);
            if (activeItem) {
                activeItem.classList.add('active');
                activeItem.classList.remove('text-gray-700');
            }
        }

        // // Add click event listeners to sidebar items
        // sidebarItems.forEach(item => {
        //     item.addEventListener('click', (e) => {
        //         e.preventDefault();
        //         const targetPage = item.getAttribute('data-page');

        //         // Set active sidebar item
        //         setActiveSidebarItem(targetPage);

        //         // Show corresponding page content
        //         showPageContent(targetPage);

        //         // Close mobile menu if open
        //         if (!sidebar.classList.contains('-translate-x-full')) {
        //             sidebar.classList.add('-translate-x-full');
        //             overlay.classList.add('hidden');
        //         }

        //         // Add visual feedback
        //         item.style.transform = 'scale(0.98)';
        //         setTimeout(() => {
        //             item.style.transform = '';
        //         }, 150);
        //     });

        //     // Add hover effects
        //     item.addEventListener('mouseenter', () => {
        //         if (!item.classList.contains('active')) {
        //             item.style.backgroundColor = '#f9fafb';
        //         }
        //     });

        //     item.addEventListener('mouseleave', () => {
        //         if (!item.classList.contains('active')) {
        //             item.style.backgroundColor = '';
        //         }
        //     });
        // });

        // Add hover effects to cards
        const cards = document.querySelectorAll('.bg-orange-50, .bg-blue-50, .bg-green-50, .bg-yellow-50');
        cards.forEach(card => {
            card.addEventListener('mouseenter', () => {
                card.style.transform = 'translateY(-2px)';
                card.style.transition = 'transform 0.2s ease-in-out';
            });

            card.addEventListener('mouseleave', () => {
                card.style.transform = 'translateY(0)';
            });
        });

        // Keyboard navigation for accessibility
        // document.addEventListener('keydown', (e) => {
        //     if (e.key === 'Escape') {
        //         sidebar.classList.add('-translate-x-full');
        //         overlay.classList.add('hidden');
        //     }
        // });
    </script>
    @stack('js')
</body>

</html>
