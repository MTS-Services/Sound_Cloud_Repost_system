<x-admin::layout>
    <x-slot name="title">{{ __('User Detail') }}</x-slot>
    <x-slot name="breadcrumb">{{ __('User Detail') }}</x-slot>
    <x-slot name="page_slug">user</x-slot>

    <div class="max-w-8xl mx-auto px-4 sm:px-6 md:px-8">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4 py-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">User Details</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Comprehensive information about {{ $user->name }}
                </p>
            </div>
            <x-button href="{{ route('um.user.index') }}" permission="credit-create" class="flex items-center gap-2">
                <x-lucide-arrow-left class="w-4 h-4" />
                {{ __('Back to Users') }}
            </x-button>
        </div>

        <!-- Main Content -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <!-- Details Card -->
            <div class="lg:col-span-3">
                <div
                    class="bg-white dark:bg-bg-dark-primary rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">

                    <!-- Card Header -->
                    <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-700">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                            <x-lucide-info class="w-5 h-5 text-blue-500" />
                            User Information
                        </h2>
                    </div>

                    <!-- Card Body -->
                    <div class="p-6 space-y-6">
                        <!-- User Info -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Profile Card -->
                            <div
                                class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                                <div class="p-6 flex flex-col items-center text-center space-y-4">
                                    <div class="relative">
                                        <img class="w-32 h-32 rounded-full object-cover border-4 border-white dark:border-gray-800 shadow-lg"
                                            src="{{ auth_storage_url($user->avatar) }}" alt="{{ $user->name }}">
                                    </div>
                                    <div>
                                        <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ $user->name }}
                                        </h2>
                                        <p class="text-sm text-orange-500 font-medium">{{ $user->email }}</p>
                                    </div>

                                    <div class="w-full space-y-2">
                                        <div class="flex items-center justify-between text-sm">
                                            <span class="text-gray-500 dark:text-gray-400">Status</span>
                                            <span
                                                class="px-2 py-1 rounded-full text-xs font-semibold badge badge-soft {{ $user->status_color }}">
                                                {{ $user?->status_label }}
                                            </span>
                                        </div>
                                        <div class="flex items-center justify-between text-sm">
                                            <span class="text-gray-500 dark:text-gray-400">Member since</span>
                                            <span
                                                class="text-gray-900 dark:text-white">{{ $userinfo->created_at_formatted ?? 'N/A' }}</span>
                                        </div>
                                    </div>

                                    <!-- Genres -->
                                    <div class="border-t border-gray-100 dark:border-gray-700 pt-4 mt-4 w-full">
                                        <h3
                                            class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2 flex items-center gap-2">
                                            <x-lucide-music class="w-5 h-5 text-orange-500" />
                                            Genres
                                        </h3>
                                        <div class="flex flex-wrap gap-2">
                                            @forelse ($user->genres as $index => $genre)
                                                @php
                                                    $colors = [
                                                        'bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-300',
                                                        'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300',
                                                        'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300',
                                                        'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300',
                                                        'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300',
                                                    ];
                                                    $colorClass = $colors[$index % count($colors)];
                                                @endphp
                                                <span
                                                    class="{{ $colorClass }} px-3 py-1.5 rounded-full text-xs font-medium">
                                                    {{ $genre->genre }}
                                                </span>
                                            @empty
                                                <span class="text-gray-500 dark:text-gray-400 text-sm">No genres
                                                    specified</span>
                                            @endforelse
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Info Cards -->
                            <div class="grid grid-cols-1 gap-4">
                                @foreach (['Full Name' => $userinfo->full_name ?? 'N/A', 'Last Name' => $userinfo->last_name ?? 'N/A', 'Username' => $userinfo->username ?? 'N/A', 'Country' => $userinfo->country ?? 'N/A', 'City' => $userinfo->city ?? 'N/A'] as $label => $value)
                                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase mb-1">
                                            {{ $label }}
                                        </p>
                                        <p class="text-sm font-semibold text-gray-900 dark:text-white">
                                            {{ !empty($value) ? $value : 'N/A' }}
                                        </p>
                                    </div>
                                @endforeach
                            </div>

                        </div>

                        <!-- SoundCloud Details -->
                        <h3 class="text-md font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                            <x-lucide-cloud class="w-5 h-5 text-orange-500" />
                            SoundCloud Details
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach (['SoundCloud ID' => $userinfo->soundcloud_id ?? 'N/A', 'SoundCloud URN' => $userinfo->soundcloud_urn ?? 'N/A', 'Plan' => $userinfo->plan ?? 'N/A', 'Followers' => $userinfo->followers_count ?? 'N/A'] as $label => $value)
                                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase mb-1">
                                        {{ $label }}</p>
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white">
                                        {{ $value ?? 'N/A' }}</p>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-4 grid grid-cols-1 gap-4">
                            @foreach (['SoundCloud URI' => $userinfo->soundcloud_uri ?? 'N/A', 'Permalink URL' => $userinfo->soundcloud_permalink_url ?? 'N/A'] as $label => $value)
                                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase mb-1">
                                        {{ $label }}</p>
                                    <a href="{{ $value ?? '#' }}"
                                        class="text-sm font-semibold text-blue-600 dark:text-blue-400 hover:underline break-all">
                                        {{ $value ?? 'N/A' }}
                                    </a>
                                </div>
                            @endforeach
                        </div>

                        <!-- Statistics -->
                        <h3 class="text-md font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                            <x-lucide-bar-chart class="w-5 h-5 text-green-500" />
                            Statistics
                        </h3>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            @foreach (['Tracks' => $userinfo->track_count ?? 0, 'Playlists' => $userinfo->playlist_count ?? 0, 'Favorites' => $userinfo->public_favorites_count ?? 0, 'Reposts' => $userinfo->reposts_count ?? 0] as $label => $value)
                                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg text-center">
                                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $value ?? '0' }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $label }}</p>
                                </div>
                            @endforeach
                        </div>

                        <!-- Additional Details -->
                        <h3 class="text-md font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                            <x-lucide-more-horizontal class="w-5 h-5 text-purple-500" />
                            Additional Details
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase mb-1">Website
                                </p>
                                <a href="{{ $userinfo->website ?? '#' }}"
                                    class="text-sm font-semibold text-blue-600 dark:text-blue-400 hover:underline">
                                    {{ $userinfo->website_title ?? ($userinfo->website ?? 'N/A') }}
                                </a>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase mb-1">
                                    Description</p>
                                <p class="text-sm text-gray-900 dark:text-white line-clamp-2">
                                    {{ $userinfo->description ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
</x-admin::layout>
