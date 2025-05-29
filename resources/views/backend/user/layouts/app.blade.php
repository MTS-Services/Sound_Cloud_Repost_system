<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- csrf --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Sound Cloud') }}</title>
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
            {{-- @yield('content') --}}
             {{ $slot }}
        </div>
    </div>

    <script>
        lucide.createIcons();
        // Mobile menu functionality
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const closeMenu = document.getElementById('close_menu');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('overlay');

        mobileMenuBtn.addEventListener('click', () => {
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
            mobileMenuBtn.classList.remove('block')
            mobileMenuBtn.classList.add('hidden')
        });
        closeMenu.addEventListener('click', () => {
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
            mobileMenuBtn.classList.add('block', )
            mobileMenuBtn.classList.remove('hidden')
        });


        overlay.addEventListener('click', () => {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
        });

        // Sidebar navigation with proper ul/li structure
        const sidebarItems = document.querySelectorAll('.sidebar-item');

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
    </script>
    @stack('js')
</body>

</html>
