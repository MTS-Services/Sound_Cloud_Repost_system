<x-admin::layout>
    <x-slot name="title">{{ __('Track Detail') }}</x-slot>
    <x-slot name="breadcrumb">{{ __('Track Detail') }}</x-slot>
    <x-slot name="page_slug">user</x-slot>

    <div class="max-w-8xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div
            class="bg-white dark:bg-bg-dark-primary rounded-2xl shadow-sm p-6 mb-6 border border-gray-200 dark:border-gray-700">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('Track Details') }}</h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        {{ __('Comprehensive information about this track') }}</p>
                </div>
                <x-button href="{{ session('back_route', url()->previous()) }}" permission="credit-create"
                    class="flex items-center gap-2 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 border-0 transition-colors duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    {{ __('Back') }}
                </x-button>

            </div>
        </div>

        <!-- Main Content Card -->
        <div
            class="bg-white dark:bg-bg-dark-primary rounded-2xl shadow-sm overflow-hidden border border-gray-200 dark:border-gray-700">
            <!-- Track Header -->
            <div class="p-8 border-b border-gray-200 dark:border-gray-700">
                <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
                    <div class="flex-1">
                        <div class="flex items-center gap-4 mb-3">
                            <div class="p-3 bg-indigo-100 dark:bg-indigo-900/40 rounded-xl">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="h-7 w-7 text-indigo-600 dark:text-indigo-400" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" />
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">
                                    {{ $tracklists->title ?? 'Untitled Track' }}</h2>
                                <p class="text-gray-500 dark:text-gray-400 mt-1">By:
                                    {{ $tracklists->user?->name ?? 'Unknown Artist' }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div
                            class="px-4 py-2 bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400 rounded-full text-sm font-medium">
                            {{ $tracklists->genre ?? 'No Genre' }}
                        </div>
                        <div
                            class="px-4 py-2 bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400 rounded-full text-sm font-medium">
                            {{ $tracklists->kind ?? 'N/A' }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="p-8">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    {{ __('Track Information') }}
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
                    <!-- Duration Card -->
                    <div
                        class="bg-white dark:bg-gray-800 p-5 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-md transition-shadow duration-200">
                        <div class="flex items-center gap-4">
                            <div class="p-3 bg-blue-100 dark:bg-blue-900/30 rounded-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600 dark:text-blue-400"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Duration') }}</p>
                                <p class="text-xl font-semibold text-gray-900 dark:text-white">
                                    {{ $tracklists->duration ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- BPM Card -->
                    <div
                        class="bg-white dark:bg-gray-800 p-5 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-md transition-shadow duration-200">
                        <div class="flex items-center gap-4">
                            <div class="p-3 bg-green-100 dark:bg-green-900/30 rounded-lg">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="h-6 w-6 text-green-600 dark:text-green-400" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('BPM') }}</p>
                                <p class="text-xl font-semibold text-gray-900 dark:text-white">
                                    {{ $tracklists->bpm ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Key Signature Card -->
                    <div
                        class="bg-white dark:bg-gray-800 p-5 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-md transition-shadow duration-200">
                        <div class="flex items-center gap-4">
                            <div class="p-3 bg-purple-100 dark:bg-purple-900/30 rounded-lg">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="h-6 w-6 text-purple-600 dark:text-purple-400" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Key Signature') }}</p>
                                <p class="text-xl font-semibold text-gray-900 dark:text-white">
                                    {{ $tracklists->key_signature ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Release Date Card -->
                    <div
                        class="bg-white dark:bg-gray-800 p-5 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-md transition-shadow duration-200">
                        <div class="flex items-center gap-4">
                            <div class="p-3 bg-amber-100 dark:bg-amber-900/30 rounded-lg">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="h-6 w-6 text-amber-600 dark:text-amber-400" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Release Date') }}</p>
                                <p class="text-sm font-semibold text-gray-900 dark:text-white">
                                    {{ $tracklists->release_month ?? 'N/A' }}/{{ $tracklists->release_year ?? 'N/A' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Engagement Stats -->
            <div class="p-8 border-t border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M14 10h4.764a2 2 极速赛车开奖直播 极速赛车开奖结果 极速赛车公众号飞飞 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h5" />
                    </svg>
                    {{ __('Engagement Statistics') }}
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    <!-- Playback Count -->
                    <div
                        class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg border border-gray-200 dark:border-gray-600">
                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">
                            {{ __('Playback Count') }}</p>
                        <p class="text-lg font-bold text-gray-900 dark:text-white">
                            {{ $tracklists->playback_count ?? 'N/A' }}</p>
                    </div>

                    <!-- Likes Count -->
                    <div
                        class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg border border-gray-200 dark:border-gray-600">
                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">
                            {{ __('Likes Count') }}</p>
                        <p class="text-lg font-bold text-gray-900 dark:text-white">
                            {{ $tracklists->favoritings_count ?? 'N/A' }}</p>
                    </div>

                    <!-- Comment Count -->
                    <div
                        class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg border border-gray-200 dark:border-gray-600">
                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">
                            {{ __('Comment Count') }}</p>
                        <p class="text-lg font-bold text-gray-900 dark:text-white">
                            {{ $tracklists->comment_count ?? 'N/A' }}</p>
                    </div>

                    <!-- Download Count -->
                    <div
                        class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg border border-gray-200 dark:border-gray-600">
                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">
                            {{ __('Download Count') }}</p>
                        <p class="text-lg font-bold text-gray-900 dark:text-white">
                            {{ $tracklists->download_count ?? 'N/A' }}</p>
                    </div>

                    <!-- Reposts Count -->
                    <div
                        class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg border border-gray-200 dark:border-gray-600">
                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">
                            {{ __('Reposts Count') }}</p>
                        <p class="text-lg font-bold text-gray-900 dark:text-white">
                            {{ $tracklists->reposts_count ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <!-- Additional Information -->
            <div class="p-8 border-t border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 极速赛车开奖直播 极速赛车开奖结果 极速赛车公众号飞飞 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    {{ __('Additional Information') }}
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Description -->
                    <div
                        class="bg-gray-50 dark:bg-gray-800 p-5 rounded-xl border border-gray-200 dark:border-gray-600">
                        <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                viewBox="0 0 24 极速赛车开奖直播 极速赛车开奖结果 极速赛车公众号飞飞" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2极速赛车开奖直播 极速赛车开奖结果 极速赛车公众号飞飞 2h-3l-4 4z" />
                            </svg>
                            {{ __('Description') }}
                        </h4>
                        <p class="text-gray-900 dark:text-white mt-2">
                            {{ $tracklists->description ?? 'No description available' }}</p>
                    </div>

                    <!-- SoundCloud Information -->
                    <div
                        class="bg-gray-50 dark:bg-gray-800 p-5 rounded-xl border border-gray-200 dark:border-gray-600">
                        <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-3">
                            {{ __('SoundCloud Information') }}</h4>

                        <div class="space-y-3">
                            <div
                                class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-600">
                                <span class="text-gray-600 dark:text-gray-400">{{ __('SoundCloud ID') }}:</span>
                                <span
                                    class="font-medium text-gray-900 dark:text-white">{{ $tracklists->soundcloud_track_id ?? 'N/A' }}</span>
                            </div>
                            <div
                                class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-600">
                                <span class="text-gray-600 dark:text-gray-400">{{ __('License') }}:</span>
                                <span
                                    class="font-medium text-gray-900 dark:text-white">{{ $tracklists->license ?? 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between items-center py-2">
                                <span class="text-gray-600 dark:text-gray-400">{{ __('Permalink URL') }}:</span>
                                <a href="{{ $tracklists->permalink_url }}"
                                    class="font-medium text-blue-600 dark:text-blue-400 truncate max-w-xs hover:underline">
                                    {{ $tracklists->permalink_url ?? 'N/A' }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Technical Details -->
            <div class="p-8 border-t border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                    </svg>
                    {{ __('Technical Details') }}
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    <!-- Streamable -->
                    <div class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg">
                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">
                            {{ __('Streamable') }}</p>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                            {{ $tracklists->streamable ? 'Yes' : 'No' }}</p>
                    </div>

                    <!-- Downloadable -->
                    <div class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg">
                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">
                            {{ __('Downloadable') }}</p>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                            {{ $tracklists->downloadable ? 'Yes' : 'No' }}</p>
                    </div>

                    <!-- Commentable -->
                    <div class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg">
                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">
                            {{ __('Commentable') }}</p>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                            {{ $tracklists->commentable ? 'Yes' : 'No' }}</p>
                    </div>

                    <!-- ISRC -->
                    <div class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg">
                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">
                            {{ __('ISRC') }}</p>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $tracklists->isrc ?? 'N/A' }}
                        </p>
                    </div>

                    <!-- Access -->
                    <div class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg">
                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">
                            {{ __('Access') }}</p>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                            {{ $tracklists->access ?? 'N/A' }}</p>
                    </div>

                    <!-- Policy -->
                    <div class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg">
                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">
                            {{ __('Policy') }}</p>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                            {{ $tracklists->policy ?? 'N/A' }}</p>
                    </div>

                    <!-- Monetization Model -->
                    <div class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg">
                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">
                            {{ __('Monetization Model') }}</p>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                            {{ $tracklists->monetization_model ?? 'N/A' }}</p>
                    </div>

                    <!-- Created At -->
                    <div class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg">
                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">
                            {{ __('Created At') }}</p>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                            {{ $tracklists->created_at ?? 'N/A' }}</p>
                    </div>

                    <!-- Updated At -->
                    <div class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg">
                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">
                            {{ __('Updated At') }}</p>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                            {{ $tracklists->updated_at ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <!-- URLs Section -->
            <div class="p-8 border-t border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                    </svg>
                    {{ __('URLs & Links') }}
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Artwork URL -->
                    <div class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg">
                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-2">
                            {{ __('Artwork URL') }}</p>
                        <a target="_blank" href="{{ $tracklists->artwork_url }}"
                            class="text-sm font-medium text-blue-600 dark:text-blue-400 hover:underline truncate block">
                            {{ $tracklists->artwork_url ?? 'N/A' }}
                        </a>
                    </div>

                    <!-- Stream URL -->
                    <div class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg">
                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-2">
                            {{ __('Stream URL') }}</p>
                        <a target="_blank" href="{{ $tracklists->stream_url }}"
                            class="text-sm font-medium text-blue-600 dark:text-blue-400 hover:underline truncate block">
                            {{ $tracklists->stream_url ?? 'N/A' }}
                        </a>
                    </div>

                    <!-- Download URL -->
                    <div class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg">
                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-2">
                            {{ __('Download URL') }}</p>
                        <a target="_blank" href="{{ $tracklists->download_url }}"
                            class="text-sm font-medium text-blue-600 dark:text-blue-400 hover:underline truncate block">
                            {{ $tracklists->download_url ?? 'N/A' }}
                        </a>
                    </div>

                    <!-- Waveform URL -->
                    <div class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg">
                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-2">
                            {{ __('Waveform URL') }}</p>
                        <a target="_blank" href="{{ $tracklists->waveform_url }}"
                            class="text-sm font-medium text-blue-600 dark:text-blue-400 hover:underline truncate block">
                            {{ $tracklists->waveform_url ?? 'N/A' }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin::layout>
