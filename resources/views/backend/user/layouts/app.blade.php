<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Sound Cloud') }}</title>

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
    <link rel="stylesheet" href="{{ asset('assets/frontend/css/custome.css') }}">
    <!-- Scripts -->
    <script src="{{ asset('assets/js/toggle-theme.js') }}"></script>

    @vite(['resources/css/user-dashboard.css', 'resources/js/user-dashboard.js'])


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

    @stack('cs')
    @livewireStyles()
</head>

<body class="bg-gray-50 dark:bg-gray-900 font-sans text-black overflow-x-hidden!" x-data="{ sidebarOpen: false, mobileSearchOpen: false }">

    @include('backend.user.layouts.partials.header')
    @include('backend.user.layouts.partials.sidebar')

    <!-- Main Content -->
    <div class="ml-auto lg:w-[calc(100%-15%)] w-full">
        <div class="p-4 md:p-6 h-[calc(100vh-9vh)]  overflow-y-auto">
            {{ $slot }}
        </div>
    </div>

    <script src="{{ asset('assets/js/lucide-icon.js') }}"></script>
    {{-- <script src="{{ asset('assets/frontend/js/custome.js') }}"></script> --}}
    <script>
        document.addEventListener('alpine:init', () => {
            lucide.createIcons();
        })
    </script>
    @livewireScripts()
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            window.Echo.private('user.{{ auth()->id() }}')
                .listen('.private-message.sent', (e) => {
                    console.log('New private message received:', e);
                    console.log(e);
                    Livewire.dispatch('notification-updated');
                });

        });
    </script>
    @stack('js')


</body>

</html>
