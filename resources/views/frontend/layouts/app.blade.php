<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{ asset('default_img/logo.svg') }}" type="image/x-icon">
    <link id="favicon" rel="icon" href="{{ storage_url(app_setting('favicon')) }}" type="image/x-icon">
    <title>
        {{ isset($title) ? $title . ' - ' : '' }}
        {{ config('app.name', 'RepostChain') }}
    </title>

    {{-- Theme selector && Theme store --}}
    {{-- <script>
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
    <script src="{{ asset('assets/js/theme-toggle.js') }}"></script> --}}

    {{-- End theme selector && Theme store --}}

    {{-- FontAwesome  --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />

    <script src="{{ asset('assets/frontend/js/jquery.js') }}"></script>
    @vite(['resources/css/frontend.css', 'resources/js/app.js'])
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if (session('success'))
                showAlert('success', '{{ session('success') }}');
            @endif

            @if (session('error'))
                showAlert('error', '{{ session('error') }}');
            @endif

            @if (session('warning'))
                showAlert('warning', '{{ session('warning') }}');
            @endif
        });
    </script>
    <script>
        const content_image_upload_url = '{{ route('file.ci_upload') }}';
    </script>

    @stack('css')
</head>

<body class="font-poppins">

    {{-- Header --}}
    <x-frontend::header :page="$page_slug" />

    {{ $slot }}

    @stack('js')
</body>

</html>
