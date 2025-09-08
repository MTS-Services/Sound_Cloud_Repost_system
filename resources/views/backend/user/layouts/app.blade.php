<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @if (app_setting('favicon') && app_setting('favicon_dark'))
        <link id="favicon" rel="icon" href="{{ storage_url(app_setting('favicon')) }}" type="image/x-icon">
    @else
        <link id="favicon" rel="icon" href="{{ asset('assets/favicons/fav-icon-B.jpg') }}" type="image/x-icon">
    @endif

    <script>
        function setFavicon(dark = false) {
            let favicon = document.getElementById('favicon');
            favicon.href = dark ?
                "{{ storage_url(app_setting('favicon_dark')) }}" :
                "{{ storage_url(app_setting('favicon')) }}";
        }
        if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
            setFavicon(true);
        }
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
            setFavicon(e.matches);
        });
    </script>


    <title>
        {{ isset($title) ? $title . ' - ' : '' }}
        {{ config('app.name', 'RepostChain') }}
    </title>

    <script>
        (function() {
            function applyThemeImmediately() {
                const theme = localStorage.getItem('theme') || 'light';
                const isDark = theme === 'dark';

                document.documentElement.classList.toggle('dark', isDark);
                document.documentElement.setAttribute('data-theme', isDark ? 'dark' : 'light');
            }

            // Apply theme immediately on script execution
            applyThemeImmediately();

            // Apply theme before Livewire navigation starts
            document.addEventListener('livewire:navigating', function() {
                applyThemeImmediately();
            });

            // Apply theme immediately after navigation (backup)
            document.addEventListener('livewire:navigated', function() {
                applyThemeImmediately();

                // Refresh icons after navigation
                setTimeout(() => {
                    if (window.lucide && lucide.createIcons) {
                        lucide.createIcons();
                    }
                }, 10);
            });
        })();
    </script>
    <link rel="stylesheet" href="{{ asset('assets/frontend/css/custome.css') }}">
    <!-- Scripts -->
    <script src="{{ asset('assets/js/toggle-theme-3.js') }}"></script>

    @vite(['resources/css/user-dashboard.css', 'resources/js/user-dashboard.js', 'resources/css/frontend.css'])


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
            document.addEventListener('livewire:initialized', function() {
                Livewire.on('alert', (event) => {
                    showAlert(event.type, event.message);
                });
            });
        });
    </script>

    @stack('cs')
    @livewireStyles()

</head>

<body class="bg-gray-50 dark:bg-gray-900 font-sans text-black overflow-x-hidden! relative" x-data="{ sidebarOpen: false, mobileSearchOpen: false }">

    @auth
        <livewire:user-heartbeat />
    @endauth
    <div id="notification-toast"
        class="absolute hidden top-5 right-5 w-72 z-50 rounded-2xl shadow-2xl bg-white text-black transition-all duration-500 ease-in-out transform translate-x-full opacity-0">
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

    @if (auth()->guard('web')->check() && Route::is('user.*'))
        @include('backend.user.layouts.partials.header')
        @include('backend.user.layouts.partials.sidebar')
    @else
        @include('backend.user.layouts.partials.f_header')
    @endif


    <!-- Main Content -->
    @if (auth()->guard('web')->check() && Route::is('user.*'))
        <div class="ml-auto lg:w-[calc(100%-15%)]">
            <div class="p-4 md:p-6 min-h-[calc(100vh-64px)]">
    @endif
    {{ $slot }}
    @if (auth()->guard('web')->check() && Route::is('user.*'))
        </div>
        </div>
    @else
        @include('backend.user.layouts.partials.f_footer')
    @endif


    <script src="{{ asset('assets/js/lucide-icon.js') }}"></script>
    @livewireScripts()
    <script>
        document.addEventListener('livewire:initialized', () => {
            lucide.createIcons();


            window.Echo.channel('users')
                .listen('.notification.sent', (e) => {
                    console.log(e);
                    showNotification('New message received.');
                    Livewire.dispatch('notification-updated');
                });

            if ('{{ auth()->check() }}') {
                window.Echo.private('user.{{ auth()->id() }}')
                    .listen('.notification.sent', (e) => {
                        console.log(e);
                        showNotification('New message received.');
                        Livewire.dispatch('notification-updated');
                    });
            }


            function showNotification(message) {
                const toast = document.getElementById('notification-toast');
                const messageElement = document.getElementById('notification-message');
                const closeButton = document.getElementById('close-notification-btn');

                if (!toast || !messageElement || !closeButton) {
                    console.error('Notification elements not found.');
                    return;
                }

                // Set the message
                messageElement.textContent = message;

                // Show the notification with animation
                toast.classList.remove('hidden');
                toast.classList.remove('translate-x-full', 'opacity-0');
                toast.classList.add('translate-x-0', 'opacity-100');

                // Automatic dismissal after 3 seconds
                const timeoutId = setTimeout(() => {
                    hideNotification();
                }, 3000); // 3 seconds

                // Manual dismissal on click
                closeButton.addEventListener('click', () => {
                    clearTimeout(timeoutId); // Clear the auto-dismiss timer
                    hideNotification();
                }, {
                    once: true
                }); // Ensure the event listener is removed after first use
            }

            function hideNotification() {
                const toast = document.getElementById('notification-toast');
                if (toast) {
                    toast.classList.remove('translate-x-0', 'opacity-100');
                    toast.classList.add('translate-x-full', 'opacity-0');
                    toast.classList.add('hidden');
                }
            }

        });
    </script>
    @stack('js')


</body>

</html>
