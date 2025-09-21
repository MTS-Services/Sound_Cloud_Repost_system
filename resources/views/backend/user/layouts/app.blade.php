<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Permissions-Policy" content="encrypted-media=()">
    <link id="favicon" rel="icon" href="{{ storage_url(app_setting('favicon')) }}" type="image/x-icon">

    <script>
        function setFavicon(dark = false) {
            let favicon = document.getElementById('favicon');
            favicon.href = dark ?
                "{{ app_setting('favicon_dark') ? storage_url(app_setting('favicon_dark')) : asset('assets/favicons/fav icon 2 (1).svg') }}" :
                "{{ app_setting('favicon') ? storage_url(app_setting('favicon')) : asset('assets/favicons/fav icon 1.svg') }}";
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
        document.addEventListener('livewire:navigated', function() {
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

    <style>
        @keyframes bounce-dot {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-20px);
            }
        }

        /* :root {
            --livewire-progress_bar_color: var(--color-orange-500);
        }

        #nprogress .bar {
            background: var(--livewire-progress_bar_color) !important;
        }

        #nprogress .peg {
            box-shadow: 0 0 10px var(--livewire-progress_bar_color), 0 0 5px var(--livewire-progress_bar_color) !important;
        }

        #nprogress .spinner-icon {
            border-top-color: var(--livewire-progress_bar_color) !important;
            border-left-color: var(--livewire-progress_bar_color) !important;
        } */
    </style>


    @stack('cs')
    @livewireStyles()

</head>

<body class="bg-gray-50 dark:bg-gray-900 font-sans text-black overflow-x-hidden! relative" x-data="{ sidebarOpen: false, mobileSearchOpen: false, open: true }">

    <div id="navigation-loader" x-transition.opacity
        class="fixed inset-0 z-[99999] flex items-center justify-center bg-black/50 dark:bg-white/50 backdrop-blur-md">

        {{-- <div class="inline-flex items-center transition ease-in-out duration-150 animate-bounce">
            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="10" stroke="#ea580c" stroke-width="4">
                </circle>
                <path fill="#fed7aa"
                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                </path>
            </svg>
            <img src="{{ app_setting('app_logo') ? asset('storage/' . app_setting('app_logo')) : asset('assets/logo/rc-logo-black.png') }}"
                alt="{{ config('app.name') }}" class="w-36 lg:w-48 dark:hidden" />
            <img src="{{ app_setting('app_logo_dark') ? asset('storage/' . app_setting('app_logo_dark')) : asset('assets/logo/rc-logo-white.png') }}"
                alt="{{ config('app.name') }}" class="w-36 lg:w-48 hidden dark:block" />
        </div> --}}

        {{-- <div class="flex space-x-2">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-orange-500 p-2">
                <span class="w-2 h-2 bg-orange-500 rounded-full animate-ping"></span>
            </div>

            <img src="{{ app_setting('app_logo') ? asset('storage/' . app_setting('app_logo')) : asset('assets/logo/rc-logo-black.png') }}"
                alt="{{ config('app.name') }}" class="w-36 lg:w-48 dark:hidden animate-bounce" />
            <img src="{{ app_setting('app_logo_dark') ? asset('storage/' . app_setting('app_logo_dark')) : asset('assets/logo/rc-logo-white.png') }}"
                alt="{{ config('app.name') }}" class="w-36 lg:w-48 hidden dark:block animate-bounce" />
        </div> --}}
        <div class="flex space-x-2">
            <div class="w-4 h-4 rounded-full bg-orange-500 animate-[bounce-dot_1.2s_infinite]"
                style="animation-delay: -0.8s;"></div>
            <div class="w-4 h-4 rounded-full bg-orange-500 animate-[bounce-dot_1.2s_infinite]"
                style="animation-delay: -0.4s;"></div>
            <div class="w-4 h-4 rounded-full bg-orange-500 animate-[bounce-dot_1.2s_infinite]"></div>
        </div>
    </div>


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
    @if (auth()->guard('web')->check())
        @if (!user()->email_verified_at)
            <div x-show="open" x-transition.opacity.duration.300ms
                class=" top-0  mb-8 max-w-8xl mx-auto  bg-gray-50 dark:bg-gray-800 border-l-4 border-orange-500 text-black dark:text-white  p-4 shadow-sm flex items-center justify-center z-1 rounded-md relative"
                role="alert">
                <div class="flex justify-center items-center gap-1">
                    <p class="text-sm text-gray-600 dark:text-gray-300">
                        Please confirm your email address to unlock core platform features.
                    <form x-data="{ loading: false }" x-ref="form" method="POST"
                        action="{{ route('user.email.resend.verification') }}"
                        @submit.prevent="loading = true; $refs.submitButton.disabled = true; $refs.form.submit();">
                        @csrf
                        <button type="submit" x-ref="submitButton" :disabled="loading"
                            class="text-sm font-semibold text-orange-600 hover:underline">
                            <template x-if="!loading">
                                <span>Resend confirmation</span>
                            </template>
                            <template x-if="loading">
                                <span>Sending...</span>
                            </template>
                        </button>
                    </form>
                    </p>
                    <button
                        class="absolute top-1/2 right-4 transform -translate-y-1/2 transition-colors text-gray-500 dark:text-gray-400 hover:text-gray-700 flex-shrink-0"
                        @click="open = false">
                        <x-lucide-x class="w-5 h-5" />
                    </button>
                </div>
            </div>
        @endif
    @endif
    {{ $slot }}
    @if (auth()->guard('web')->check() && Route::is('user.*'))
        </div>
        </div>
    @else
        @include('backend.user.layouts.partials.f_footer')
    @endif

    <script src="{{ asset('assets/js/lucide-icon.js') }}"></script>
    <script src="https://w.soundcloud.com/player/api.js"></script>
    @livewireScripts()
    <script>
        document.addEventListener('livewire:navigate', (event) => {
            document.getElementById('navigation-loader').classList.remove('hidden');
        });

        document.addEventListener('livewire:navigating', () => {
            document.getElementById('navigation-loader').classList.remove('hidden');
        });

        document.addEventListener('livewire:navigated', () => {
            document.getElementById('navigation-loader').classList.add('hidden');
        });

        document.addEventListener('livewire:initialized', () => {
            lucide.createIcons();

            window.Echo.channel('users')
                .listen('.notification.sent', (e) => {
                    // console.log(e);
                    showNotification(e.title || 'New message received.');
                    Livewire.dispatch('notification-updated');
                });

            if ('{{ auth()->check() }}') {
                window.Echo.private('user.{{ auth()->id() }}')
                    .listen('.notification.sent', (e) => {
                        // console.log(e);
                        showNotification(e.title || 'New message received.');
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
