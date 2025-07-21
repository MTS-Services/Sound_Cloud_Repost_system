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

    @vite(['resources/css/user-dashboard.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 font-sans text-black">
    {{-- Sidebar --}}
    @include('backend.user.layouts.partials.sidebar')

    <!-- Main Content -->
    <div class="lg:ml-64 min-h-screen " x-data>
        {{-- Header --}}
        @include('backend.user.layouts.partials.header')

        {{-- Main Content --}}
        <div class="p-6">
             {{ $slot }}
        </div>
    </div>
    {{-- Custom JS --}}
    <script src="{{ asset('assets/frontend/js/custome.js') }}"></script>
    @stack('js')
</body>

</html>
