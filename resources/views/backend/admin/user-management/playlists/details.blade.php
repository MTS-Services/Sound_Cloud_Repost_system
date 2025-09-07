<x-admin::layout>
    <x-slot name="title">{{ __('Playlist Detail') }}</x-slot>
    <x-slot name="breadcrumb">{{ __('Playlist Detail') }}</x-slot>
    <x-slot name="page_slug">user</x-slot>

    <div class="max-w-8xl mx-auto px-4 sm:px-4 lg:px-4">
        <!-- Header Section -->
        <div
            class="bg-white dark:bg-bg-dark-primary rounded-2xl shadow-sm p-6 mb-6 border border-gray-200 dark:border-gray-700">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('Playlist Details') }}</h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        {{ __('Comprehensive information about this playlist') }}</p>
                </div>
                <x-button href="{{ route('um.user.playlist', $playlists->id) }}" permission="credit-create" 
                          class="flex items-center gap-2   hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 border-0 transition-colors duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    {{ __('Back to List') }}
                </x-button>
             
            </div>
        </div>

        <!-- Main Content Card -->
        <div
            class="bg-white dark:bg-bg-dark-primary rounded-2xl shadow-sm overflow-hidden border border-gray-200 dark:border-gray-700">
            <!-- Playlist Header -->
            <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
                    <div class="flex-1">
                        <div class="flex items-center gap-4 mb-3">
                            <div class="p-3 bg-indigo-100 dark:bg-indigo-900/40 rounded-xl">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="h-7 w-7 text-indigo-600 dark:text-indigo-400" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z" />
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">
                                    {{ $playlists->title ?? 'Untitled Playlist' }}</h2>
                                <p class="text-gray-500 dark:text-gray-400 mt-1">By:
                                    {{ $playlists->user?->name ?? 'Unknown Artist' }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div
                            class="px-4 py-2 bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400 rounded-full text-sm font-medium">
                            {{ $playlists->track_count }} Tracks
                        </div>
                        <div
                            class="px-4 py-2 bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400 rounded-full text-sm font-medium">
                            {{ $playlists->playlist_type }}
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
                    {{ __('Playlist Information') }}
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
                                    {{ $playlists->duration ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Genre Card -->
                    <div
                        class="bg-white dark:bg-gray-800 p-5 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-md transition-shadow duration-200">
                        <div class="flex items-center gap-4">
                            <div class="p-3 bg-green-100 dark:bg-green-900/30 rounded-lg">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="h-6 w-6 text-green-600 dark:text-green-400" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 4V2m10 2V2M5 6h14M5 18h14M5 12h14" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Genre') }}</p>
                                <p class="text-xl font-semibold text-gray-900 dark:text-white">
                                    {{ $playlists->genre ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Label Card -->
                    <div
                        class="bg-white dark:bg-gray-800 p-5 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-md transition-shadow duration-200">
                        <div class="flex items-center gap-4">
                            <div class="p-3 bg-purple-100 dark:bg-purple-900/30 rounded-lg">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="h-6 w-6 text-purple-600 dark:text-purple-400" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-4m-4 0H9m4 0V9a2 2 0 00-2-2H9a2 2 0 00-2 2v12m4 0V9" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Label') }}</p>
                                <p class="text-xl font-semibold text-gray-900 dark:text-white">
                                    {{ $playlists->label_name ?? ($playlists->label ?? 'N/A') }}</p>
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
                                    {{ $playlists->release_day }}/{{ $playlists->release_month }}/{{ $playlists->release_year }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Information -->
            <div class="p-8 border-t border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    {{ __('Additional Information') }}
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Description -->
                    <div
                        class="bg-gray-50 dark:bg-gray-700 p-5 rounded-xl border border-gray-200 dark:border-gray-600">
                        <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400  mb-2 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                            </svg>
                            {{ __('Description') }}
                        </h4>
                        <p class="text-gray-900 dark:text-white mt-2">
                            {{ $playlists->description ?? 'No description available' }}</p>
                    </div>

                    <!-- SoundCloud Information -->
                    <div
                        class="bg-gray-50 dark:bg-gray-700 p-5 rounded-xl border border-gray-200 dark:border-gray-600">
                        <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-3">
                            {{ __('SoundCloud Information') }}</h4>

                        <div class="space-y-3">
                            <div
                                class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-600">
                                <span class="text-gray-600 dark:text-gray-400">{{ __('SoundCloud ID') }}:</span>
                                <span
                                    class="font-medium text-gray-900 dark:text-white">{{ $playlists->soundcloud_id ?? 'N/A' }}</span>
                            </div>
                            <div
                                class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-600">
                                <span class="text-gray-600 dark:text-gray-400">{{ __('Likes Count') }}:</span>
                                <span
                                    class="font-medium text-gray-900 dark:text-white">{{ $playlists->likes_count ?? 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between items-center py-2">
                                <span class="text-gray-600 dark:text-gray-400">{{ __('Permalink URL') }}:</span>
                                <a class="font-medium text-blue-600 dark:text-blue-400 truncate max-w-xs hover:underline duration-200"
                                    target="_blank" href="{{ $playlists->permalink_url ?? '#' }}">
                                    {{ $playlists->permalink_url ?? 'N/A' }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detailed Information Grid -->
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
                    <!-- URI -->
                    {{-- <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">
                            {{ __('URI') }}</p>
                        <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                            {{ $playlists->uri ?? 'N/A' }}</p>
                    </div> --}}

                    <!-- Tags -->
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">
                            {{ __('Tags') }}</p>
                        <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                            {{ $playlists->tags ?? ($playlists->tag_list ?? 'N/A') }}</p>
                    </div>

                    <!-- License -->
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">
                            {{ __('License') }}</p>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                            {{ $playlists->license ?? 'N/A' }}</p>
                    </div>

                    <!-- Type -->
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">
                            {{ __('Type') }}</p>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $playlists->type ?? 'N/A' }}
                        </p>
                    </div>

                    <!-- Streamable -->
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">
                            {{ __('Streamable') }}</p>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                            {{ $playlists->streamable ? 'Yes' : 'No' }}</p>
                    </div>

                    <!-- Downloadable -->
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">
                            {{ __('Downloadable') }}</p>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                            {{ $playlists->downloadable ? 'Yes' : 'No' }}</p>
                    </div>

                    <!-- Embeddable By -->
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">
                            {{ __('Embeddable By') }}</p>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                            {{ $playlists->embeddable_by ?? 'N/A' }}</p>
                    </div>

                    <!-- Sharing -->
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">
                            {{ __('Sharing') }}</p>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                            {{ $playlists->sharing ?? 'N/A' }}</p>
                    </div>

                    <!-- EAN -->
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">
                            {{ __('EAN') }}</p>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $playlists->ean ?? 'N/A' }}
                        </p>
                    </div>

                    <!-- Last Modified -->
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">
                            {{ __('Last Modified') }}</p>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                            {{ $playlists->last_modified ?? 'N/A' }}</p>
                    </div>

                    <!-- Created At -->
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">
                            {{ __('Created At') }}</p>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                            {{ $playlists->created_at ?? 'N/A' }}</p>
                    </div>

                    <!-- Updated At -->
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">
                            {{ __('Updated At') }}</p>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                            {{ $playlists->updated_at ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin::layout>
