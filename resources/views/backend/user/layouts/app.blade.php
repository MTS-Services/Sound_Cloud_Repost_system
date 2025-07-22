<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- csrf --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Sound Cloud') }}</title>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
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
    {{-- Custom CSS --}}
    <link rel="stylesheet" href="{{ asset('assets/frontend/css/custome.css') }}">

    @vite(['resources/css/user-dashboard.css', 'resources/js/app.js'])

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
</head>

<body class="bg-gray-50 dark:bg-gray-900 font-sans text-black" x-data>
    {{-- Sidebar --}}
    @include('backend.user.layouts.partials.sidebar')

    <!-- Main Content -->
    <div class="lg:ml-64 min-h-screen " >
        {{-- Header --}}
        @include('backend.user.layouts.partials.header')

        {{-- Main Content --}}
        <div class="p-6">
             {{ $slot }}
        </div>
    </div>
    {{-- Custom JS --}}
    <script src="{{ asset('assets/frontend/js/custome.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    @stack('js')
</body>

</html>
