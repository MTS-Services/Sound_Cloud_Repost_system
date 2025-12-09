<section>
    <x-slot name="page_slug">{{ user()->urn == $user->urn ? 'my-account' : '' }}</x-slot>
    @push('cs')
        <style>
            .activity-chart-container [data-tooltip="true"]:hover {
                transform: scale(1.2);
            }

            .grid-cols-53 {
                grid-template-columns: repeat(53, minmax(12px, 1fr));
            }

            @media (max-width: 640px) {
                .grid-cols-53 {
                    grid-template-columns: repeat(53, 10px);
                }

                .activity-chart-container .w-3 {
                    width: 10px;
                    height: 10px;
                }
            }
        </style>
    @endpush

    <section class="flex-1 overflow-auto">
        <div class="min-h-screen">
            {{-- Header Section --}}
            <div class="bg-gradient-to-r from-gray-100 to-gray-200 dark:from-slate-800 dark:to-slate-700 relative">
                <div class="absolute inset-0 dark:to-slate-700 bg-opacity-10 dark:bg-opacity-30"></div>
                <div class="relative p-4 sm:p-6">
                    <div class="flex flex-col sm:flex-row items-start space-y-4 sm:space-y-0 sm:space-x-6">
                        {{-- Avatar --}}
                        <div class="flex-shrink-0 mx-auto sm:mx-0">
                            <img src="{{ soundcloud_image($user->avatar) }}" alt="{{ $user->name ?? 'name' }}"
                                class="w-24 h-24 sm:w-32 sm:h-32 rounded-full border-4 border-white dark:border-gray-300 shadow-lg" />
                        </div>

                        {{-- User Info --}}
                        <div class="flex-1 text-center w-full md:text-left ml-0 sm:ml-4">
                            <div
                                class="flex flex-col md:flex-row items-center md:space-x-4 mb-2 text-center md:text-left">
                                <h1 class="text-xl md:text-2xl lg:text-3xl font-bold text-gray-900 dark:text-white">
                                    {{ $user->name ?? 'name' }}
                                </h1>

                                @if (proUser($user->urn))
                                    <p
                                        class="mt-1 sm:mt-0 text-xs md:text-sm lg:text-base badge badge-soft badge-warning rounded-full font-semibold">
                                        {{ userPlanName($user->urn) }}
                                    </p>
                                @else
                                    <p
                                        class="mt-1 sm:mt-0 text-xs md:text-sm lg:text-base badge badge-soft badge-info rounded-full font-semibold">
                                        {{ userPlanName($user->urn) }}
                                    </p>
                                @endif
                            </div>

                            <div class="flex items-center justify-center md:justify-start gap-3 mb-4">
                                <p class="text-base md:text-lg lg:text-xl text-gray-600 dark:text-slate-200">
                                    {{ $user->userInfo->followers_count ?? '0' }} Followers
                                </p>
                                @if (user()->urn !== $user->urn)
                                    <button x-on:click="Livewire.dispatch('starMarkUser', { userUrn: '{{ $user->urn }}' })">
                                        <x-lucide-star
                                            class="w-5 h-5 relative {{ $user->starredUsers?->contains('follower_urn', user()->urn)
                                                ? 'text-orange-300 '
                                                : 'text-gray-400 dark:text-gray-500' }}"
                                            fill="{{ $user->starredUsers?->contains('follower_urn', user()->urn) ? 'orange ' : 'none' }}" />
                                    </button>
                                @endif
                            </div>

                            <div
                                class="flex flex-col items-center lg:flex-row space-y-2 md:space-y-0 md:space-x-2 xs:space-y-1 lg:space-y-0 text-gray-500 dark:text-slate-300">

                                {{-- MEMBER SINCE --}}
                                <div class="flex items-center space-x-1">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="inline w-4 h-4 md:w-5 md:h-5 lg:w-6 lg:h-6" fill="none"
                                        stroke="#5c6b80" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <rect x="3" y="5" width="18" height="16" rx="2" />
                                        <path d="M16 3v4M8 3v4M3 9h18" />
                                    </svg>
                                    <span class="text-[10px] md:text-xs lg:text-sm block">
                                        MEMBER SINCE {{ $user->created_at->diffForHumans() }}
                                    </span>
                                </div>

                                {{-- CITY, COUNTRY --}}
                                <div class="flex items-center space-x-1">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="w-4 h-4 md:w-5 md:h-5 lg:w-5 lg:h-5 text-gray-500 dark:text-slate-300 flex-shrink-0"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path>
                                        <circle cx="12" cy="10" r="3"></circle>
                                    </svg>
                                    <span class="text-[10px] md:text-xs lg:text-sm block">
                                        {{ $user->userInfo->city ?? 'City' }},
                                        {{ $user->userInfo->country ?? 'Country' }}
                                    </span>
                                </div>


                            </div>


                        </div>

                        {{-- Buttons --}}
                        <div
                            class="flex flex-col sm:flex-row sm:items-center space-y-2 sm:space-y-0 sm:space-x-3 w-full sm:w-auto">
                            <x-gabutton variant="primary" target="_blank"
                                href="{{ $user->soundcloud_permalink_url ?? '#' }}" class="whitespace-nowrap">
                                <x-lucide-external-link class="w-5 h-5 md:w-5 md:h-5 lg:w-6 lg:h-6 mr-1 text-gray-50" />
                                <span class="text-sm md:text-xs lg:text-sm">
                                    Visit on Soundcloud
                                </span>
                            </x-gabutton>

                            @if (user()->urn == $user->urn)
                                {{-- <x-gbutton variant="primary" wire:click="profileUpdated({{ user()->id }})">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="w-3 h-3 sm:w-4 sm:h-4">
                                            <path d="M12 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                            <path d="M18.375 2.625a2.121 2.121 0 1 1 3 3L12 15l-4 1 1-4Z"></path>
                                        </svg>
                                        <span class="ms-1">Edit</span>
                                    </x-gbutton> --}}
                                <x-gabutton variant="primary" wire:navigate href="{{ route('user.settings') }}">
                                    <x-lucide-edit class="w-4 h-4 mr-1" />
                                    <span class="ms-1">Edit</span>
                                </x-gabutton>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="pt-4 sm:pt-6">
                <div class="grid grid-cols-1 lg:grid-cols-4 gap-4 sm:gap-6">
                    {{-- Sidebar --}}
                    <div class="lg:col-span-1 space-y-4 sm:space-y-6">
                        <div
                            class="bg-gray-100 dark:bg-slate-800 rounded-lg p-4 sm:p-6 border border-gray-200 dark:border-slate-700">
                            <h3 class="text-gray-900 dark:text-white font-semibold mb-3 sm:mb-4">About</h3>
                            <p class="text-gray-600 dark:text-slate-300 text-xs sm:text-sm mb-3 sm:mb-4">
                                {{ $user->userInfo->description ?? 'description' }}
                            </p>
                            <p class="text-gray-500 dark:text-slate-400 text-xs">Bio from
                                <span class="text-orange-500 dark:text-orange-400">SOUNDCLOUD</span>
                            </p>
                        </div>
                        @if ($socialLink)
                            <div
                                class="bg-gray-100 dark:bg-slate-800 rounded-lg p-4 sm:p-6 border border-gray-200 dark:border-slate-700">
                                <h3 class="text-gray-900 dark:text-white font-semibold mb-3 sm:mb-4">Social Links
                                </h3>
                                {{-- social icons --}}
                                <div class="flex items-center gap-3">
                                    @if ($instagram)
                                        <a href="https://www.instagram.com/{{ $instagram }}" target="_blank">
                                            <div
                                                class="w-6 h-6 bg-gradient-to-br from-purple-500 to-pink-500 rounded-md flex items-center justify-center">
                                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
                                                    <path
                                                        d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" />
                                                </svg>
                                            </div>
                                        </a>
                                    @endif
                                    @if ($twitter)
                                        <a href="https://twitter.com/{{ $twitter }}" target="_blank">
                                            <div
                                                class="w-6 h-6 bg-blue-400 rounded-md flex items-center justify-center">
                                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
                                                    <path
                                                        d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z" />
                                                </svg>
                                            </div>
                                        </a>
                                    @endif
                                    @if ($facebook)
                                        <a href="https://www.facebook.com/{{ $facebook }}" target="_blank">
                                            <div
                                                class="w-6 h-6 bg-blue-600 rounded-md flex items-center justify-center">
                                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
                                                    <path
                                                        d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                                                </svg>
                                            </div>
                                        </a>
                                    @endif
                                    @if ($youtube)
                                        <a href="https://www.youtube.com/channel/{{ $youtube }}" target="_blank">
                                            <div class="w-6 h-6 bg-red-600 rounded-md flex items-center justify-center">
                                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
                                                    <path
                                                        d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z" />
                                                </svg>
                                            </div>
                                        </a>
                                    @endif
                                    @if ($tiktok)
                                        <a href="https://www.tiktok.com/@{{ $tiktok }}" target="_blank">
                                            <div class="w-6 h-6 bg-black rounded-md flex items-center justify-center">
                                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
                                                    <path
                                                        d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z" />
                                                </svg>
                                            </div>
                                        </a>
                                    @endif
                                    @if ($spotify)
                                        <a href="{{ $spotify }}" target="_blank">
                                            <div
                                                class="w-6 h-6 bg-green-500 rounded-md flex items-center justify-center">
                                                <svg class="w-4 h-4 text-white" fill="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path
                                                        d="M12 0C5.4 0 0 5.4 0 12s5.4 12 12 12 12-5.4 12-12S18.66 0 12 0zm5.521 17.34c-.24.359-.66.48-1.021.24-2.82-1.74-6.36-2.101-10.561-1.141-.418.122-.779-.179-.899-.539-.12-.421.18-.78.54-.9 4.56-1.021 8.52-.6 11.64 1.32.42.18.479.659.301 1.02zm1.44-3.3c-.301.42-.841.6-1.262.3-3.239-1.98-8.159-2.58-11.939-1.38-.479.12-1.02-.12-1.14-.6-.12-.48.12-1.021.6-1.141C9.6 9.9 15 10.561 18.72 12.84c.361.181.54.78.241 1.2zm.12-3.36C15.24 8.4 8.82 8.16 5.16 9.301c-.6.179-1.2-.181-1.38-.721-.18-.601.18-1.2.72-1.381 4.26-1.26 11.28-1.02 15.721 1.621.539.3.719 1.02.419 1.56-.299.421-1.02.599-1.559.3z" />
                                                </svg>
                                            </div>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <div
                            class="bg-gray-100 dark:bg-slate-800 rounded-lg p-4 sm:p-6 border border-gray-200 dark:border-slate-700">
                            <h3 class="text-gray-900 dark:text-white font-semibold mb-3 sm:mb-4">Genres</h3>
                            <div class="grid grid-cols-2 sm:grid-cols-1 gap-2">
                                @foreach ($user->genres as $genre)
                                    <div class="bg-gray-200 dark:bg-slate-700 px-3 py-2 rounded-lg">
                                        <span
                                            class="text-gray-600 dark:text-slate-300 text-xs sm:text-sm">{{ $genre->genre }}</span>
                                    </div>
                                @endforeach


                            </div>
                        </div>

                        <div
                            class="bg-gray-100 dark:bg-slate-800 rounded-lg p-4 sm:p-6 border border-gray-200 dark:border-slate-700">
                            <h3 class="text-gray-900 dark:text-white font-semibold mb-3 sm:mb-4">Badges Awarded
                            </h3>
                            <div class="grid grid-cols-2 gap-2 sm:gap-3">
                                <div class="bg-gray-200 dark:bg-slate-700 rounded-lg p-3 sm:p-4 text-center">
                                    <div class="text-xl sm:text-2xl mb-1 sm:mb-2">👥</div>
                                    <h4 class="text-gray-900 dark:text-white font-medium text-xs">Quality Followers
                                    </h4>
                                </div>
                                <div class="bg-gray-200 dark:bg-slate-700 rounded-lg p-3 sm:p-4 text-center">
                                    <div class="text-xl sm:text-2xl mb-1 sm:mb-2">⭐</div>
                                    <h4 class="text-gray-900 dark:text-white font-medium text-xs">Top Follower</h4>
                                </div>
                                <div class="bg-gray-200 dark:bg-slate-700 rounded-lg p-3 sm:p-4 text-center">
                                    <div class="text-xl sm:text-2xl mb-1 sm:mb-2">📅</div>
                                    <h4 class="text-gray-900 dark:text-white font-medium text-xs">Be Da Regular
                                    </h4>
                                </div>
                                <div class="bg-gray-200 dark:bg-slate-700 rounded-lg p-3 sm:p-4 text-center">
                                    <div class="text-xl sm:text-2xl mb-1 sm:mb-2">⚡</div>
                                    <h4 class="text-gray-900 dark:text-white font-medium text-xs">Power User</h4>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Main Content --}}
                    <div class="lg:col-span-3 space-y-4 sm:space-y-6">
                        <div x-data="{ activeTab: @entangle('activeTab').live }">
                            {{-- Tab Navigation --}}
                            <div
                                class="flex overflow-x-auto pb-1 sm:pb-0 space-x-4 sm:space-x-8 border-b border-gray-200 dark:border-slate-700">
                                <a href="{{ route('user.my-account.user', !empty($user->name) ? $user->name : $user->urn) }}?tab=insights"
                                    wire:navigate
                                    class="tab-btn pb-3 sm:pb-4 px-1 text-xs sm:text-sm font-medium transition-colors @if ($activeTab === 'insights') text-orange-500 border-b-2 border-orange-500 dark:text-orange-400 dark:border-orange-400 @else text-gray-500 border-transparent dark:text-slate-400 @endif">
                                    Insights
                                </a>

                                <a href="{{ route('user.my-account.user', !empty($user->name) ? $user->name : $user->urn) }}?tab=tracks"
                                    wire:navigate
                                    class="tab-btn pb-3 sm:pb-4 px-1 text-xs sm:text-sm font-medium transition-colors @if ($activeTab === 'tracks') text-orange-500 border-b-2 border-orange-500 dark:text-orange-400 dark:border-orange-400 @else text-gray-500 border-transparent dark:text-slate-400 @endif">
                                    Tracks
                                </a>

                                <a href="{{ route('user.my-account.user', !empty($user->name) ? $user->name : $user->urn) }}?tab=playlists"
                                    wire:navigate
                                    class="tab-btn pb-3 sm:pb-4 px-1 text-xs sm:text-sm font-medium transition-colors @if ($activeTab === 'playlists') text-orange-500 border-b-2 border-orange-500 dark:text-orange-400 dark:border-orange-400 @else text-gray-500 border-transparent dark:text-slate-400 @endif">
                                    Playlists
                                </a>

                                <a href="{{ route('user.my-account.user', !empty($user->name) ? $user->name : $user->urn) }}?tab=reposts"
                                    wire:navigate
                                    class="tab-btn pb-3 sm:pb-4 px-1 text-xs sm:text-sm font-medium transition-colors @if ($activeTab === 'reposts') text-orange-500 border-b-2 border-orange-500 dark:text-orange-400 dark:border-orange-400 @else text-gray-500 border-transparent dark:text-slate-400 @endif">
                                    Recent reposts
                                </a>

                                @if (user()->urn == $user->urn)
                                    <a href="{{ route('user.my-account.user', !empty($user->name) ? $user->name : $user->urn) }}?tab=transaction"
                                        wire:navigate
                                        class="tab-btn pb-3 sm:pb-4 px-1 text-xs sm:text-sm font-medium transition-colors @if ($activeTab === 'transaction') text-orange-500 border-b-2 border-orange-500 dark:text-orange-400 dark:border-orange-400 @else text-gray-500 border-transparent dark:text-slate-400 @endif">
                                        Transaction
                                    </a>
                                @endif
                            </div>

                            {{-- Tab Panels --}}
                            <div>
                                {{-- Credibility Insights --}}
                                <div x-show="activeTab === 'insights'" class="tab-panel mt-4" x-transition>
                                    <div
                                        class="bg-gray-100 dark:bg-slate-800 rounded-lg p-4 sm:p-6 border border-gray-200 dark:border-slate-700">
                                        <div
                                            class="flex flex-col sm:flex-row sm:items-center justify-between mb-4 sm:mb-6">
                                            <h3
                                                class="text-gray-900 dark:text-white text-base sm:text-lg font-semibold mb-2 sm:mb-0">
                                                Credibility Insights
                                            </h3>
                                            <div class="flex items-center space-x-2">
                                                <div class="w-2 h-2 sm:w-3 sm:h-3 bg-green-500 rounded-full"></div>
                                                <span
                                                    class="text-green-600 dark:text-green-400 text-xs sm:text-sm font-medium">
                                                    {{ number_format($user->real_followers_percentage, 2) ?? 100.0 }}%
                                                    Real
                                                    Followers
                                                </span>
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-6">
                                            <div>
                                                <div class="flex items-center justify-between mb-1 sm:mb-2">
                                                    <span
                                                        class="text-gray-600 dark:text-slate-300 text-xs sm:text-sm">Follower
                                                        Growth</span>
                                                    <span
                                                        class="text-gray-900 dark:text-white font-semibold text-xs sm:text-sm">{{ number_format($follower_growth, 2) }}%</span>
                                                </div>
                                                <div
                                                    class="w-full bg-gray-300 dark:bg-slate-700 rounded-full h-1 sm:h-2">
                                                    <div class="bg-orange-500 h-1 sm:h-2 rounded-full transition-all duration-300"
                                                        style="width: {{ $follower_growth }}%;"></div>
                                                </div>
                                            </div>

                                            <div>
                                                <div class="flex items-center justify-between mb-1 sm:mb-2">
                                                    <span
                                                        class="text-gray-600 dark:text-slate-300 text-xs sm:text-sm">Repost
                                                        Efficiency</span>
                                                    <span
                                                        class="text-gray-900 dark:text-white font-semibold text-xs sm:text-sm">{{ number_format($repost_effieciency, 2) }}%</span>
                                                </div>
                                                <div
                                                    class="w-full bg-gray-300 dark:bg-slate-700 rounded-full h-1 sm:h-2">
                                                    <div class="bg-green-500 h-1 sm:h-2 rounded-full transition-all duration-300"
                                                        style="width: {{ $repost_effieciency }}%;"></div>
                                                </div>
                                                <p class="text-gray-500 dark:text-slate-400 text-xxs sm:text-xs mt-1">
                                                    Above average for your followers range
                                                </p>
                                            </div>

                                            <div>
                                                <div class="flex items-center justify-between mb-1 sm:mb-2">
                                                    <span
                                                        class="text-gray-600 dark:text-slate-300 text-xs sm:text-sm">Activity
                                                        Score</span>
                                                    <span
                                                        class="text-gray-900 dark:text-white font-semibold text-xs sm:text-sm">{{ number_format($activities_score, 2) }}%</span>
                                                </div>
                                                <div
                                                    class="w-full bg-gray-300 dark:bg-slate-700 rounded-full h-1 sm:h-2">
                                                    <div class="bg-blue-500 h-1 sm:h-2 rounded-full transition-all duration-300"
                                                        style="width: {{ $activities_score }}%;"></div>
                                                </div>
                                            </div>

                                            <div class="md:col-span-3 mt-3 sm:mt-4 overflow-hidden">
                                                <h4
                                                    class="text-gray-900 dark:text-white font-medium mb-2 sm:mb-3 text-sm sm:text-base">
                                                    Activity Score
                                                </h4>

                                                <!-- GitHub-style contribution chart -->
                                                <div class="activity-chart-container h-auto">
                                                    <div class="h-[7rem] overflow-x-auto">
                                                        <div class="inline-block min-w-full h-auto">
                                                            @php
                                                                use Carbon\Carbon;
                                                                // Get today's date
$today = now();

// Calculate the start date (1 year ago from today)
$endDate = $today->copy();
$startDate = $today->copy()->subYear()->addDay();

// Find the Sunday on or before start date (GitHub weeks start on Sunday)
$gridStartDate = $startDate->copy();
while ($gridStartDate->dayOfWeek !== Carbon::SUNDAY) {
    $gridStartDate->subDay();
}

// Find the Saturday on or after end date
$gridEndDate = $endDate->copy();
while ($gridEndDate->dayOfWeek !== Carbon::SATURDAY) {
    $gridEndDate->addDay();
}

// Calculate weeks needed
$totalDays =
    $gridStartDate->diffInDays($gridEndDate) + 1;
$weeksCount = ceil($totalDays / 7);

// Create lookup array for data
$dataByDate = [];
foreach ($chart_data as $item) {
    if (isset($item['date'])) {
        $dataByDate[$item['date']] = $item;
    }
}

// Generate month labels
$monthLabels = [];
$currentMonthLabel = null;

for ($week = 0; $week < $weeksCount; $week++) {
    $weekDate = $gridStartDate->copy()->addWeeks($week);
    $monthLabel = $weekDate->format('M');

    if ($monthLabel !== $currentMonthLabel) {
        $monthLabels[$week] = $monthLabel;
        $currentMonthLabel = $monthLabel;
    } else {
        $monthLabels[$week] = '';
                                                                    }
                                                                }
                                                            @endphp

                                                            <div class="flex gap-2">
                                                                <!-- Day labels column -->
                                                                <div class="flex flex-col text-[11px] text-gray-500 dark:text-slate-400"
                                                                    style="padding-top: 18px;">
                                                                    <div class="h-[10px] leading-[10px] mb-[2px]">Sun
                                                                    </div>
                                                                    <div class="h-[10px] leading-[10px] mb-[2px]">Mon
                                                                    </div>
                                                                    <div class="h-[10px] leading-[10px] mb-[2px]">Tue
                                                                    </div>
                                                                    <div class="h-[10px] leading-[10px] mb-[2px]">Wed
                                                                    </div>
                                                                    <div class="h-[10px] leading-[10px] mb-[2px]">Thu
                                                                    </div>
                                                                    <div class="h-[10px] leading-[10px] mb-[2px]">Fri
                                                                    </div>
                                                                    <div class="h-[10px] leading-[10px]">Sat</div>
                                                                </div>

                                                                <!-- Chart area -->
                                                                <div class="w-full">
                                                                    <!-- Month labels row -->
                                                                    <div class="flex gap-[2px] mb-1 text-[11px] text-gray-500 dark:text-slate-400 justify-between w-full"
                                                                        style="height: 15px;">
                                                                        @foreach ($monthLabels as $label)
                                                                            <div style="width: 10px;">
                                                                                {{ $label }}
                                                                            </div>
                                                                        @endforeach
                                                                    </div>

                                                                    <!-- Weeks grid (each column is a week) -->
                                                                    <div class="flex gap-[2px]">
                                                                        @for ($week = 0; $week < $weeksCount; $week++)
                                                                            <div
                                                                                class="flex flex-col gap-[2px] justify-between w-full">
                                                                                @for ($day = 0; $day < 7; $day++)
                                                                                    @php
                                                                                        $currentDate = $gridStartDate
                                                                                            ->copy()
                                                                                            ->addWeeks($week)
                                                                                            ->addDays($day);
                                                                                        $dateKey = $currentDate->format(
                                                                                            'Y-m-d',
                                                                                        );

                                                                                        // Check if date is within actual range
                                                                                        $isInRange =
                                                                                            $currentDate >=
                                                                                                $startDate &&
                                                                                            $currentDate <= $endDate;

                                                                                        // Get data for this day
                                                                                        $activityRate = 0;
                                                                                        $hasData = false;

                                                                                        if (
                                                                                            $isInRange &&
                                                                                            isset($dataByDate[$dateKey])
                                                                                        ) {
                                                                                            $activityRate =
                                                                                                $dataByDate[$dateKey][
                                                                                                    'avg_activities_rate'
                                                                                                ] ?? 0;
                                                                                            $hasData = true;
                                                                                        }

                                                                                        // Determine color class
                                                                                        if (!$isInRange) {
                                                                                            $colorClass =
                                                                                                'bg-transparent';
                                                                                        } elseif ($activityRate > 75) {
                                                                                            $colorClass =
                                                                                                'bg-orange-600 dark:bg-orange-500';
                                                                                        } elseif ($activityRate > 50) {
                                                                                            $colorClass =
                                                                                                'bg-orange-500 dark:bg-orange-400';
                                                                                        } elseif ($activityRate > 25) {
                                                                                            $colorClass =
                                                                                                'bg-orange-400 dark:bg-orange-300';
                                                                                        } elseif ($activityRate > 0) {
                                                                                            $colorClass =
                                                                                                'bg-orange-200 dark:bg-orange-600';
                                                                                        } else {
                                                                                            $colorClass =
                                                                                                'bg-gray-100 dark:bg-slate-800 border border-gray-200 dark:border-slate-700';
                                                                                        }

                                                                                        $formattedDate = $currentDate->format(
                                                                                            'M j, Y',
                                                                                        );
                                                                                        $dayOfWeek = $currentDate->format(
                                                                                            'D',
                                                                                        );
                                                                                        $tooltipText = $hasData
                                                                                            ? number_format(
                                                                                                    $activityRate,
                                                                                                    1,
                                                                                                ) .
                                                                                                '% activity on ' .
                                                                                                $dayOfWeek .
                                                                                                ', ' .
                                                                                                $formattedDate
                                                                                            : ($isInRange
                                                                                                ? 'No activity on ' .
                                                                                                    $dayOfWeek .
                                                                                                    ', ' .
                                                                                                    $formattedDate
                                                                                                : '');
                                                                                    @endphp

                                                                                    @if ($isInRange)
                                                                                        <div class="w-[10px] h-[10px] rounded-sm {{ $colorClass }} transition-all duration-200 hover:scale-125 hover:ring-1 hover:ring-orange-400 cursor-pointer"
                                                                                            data-tooltip="true"
                                                                                            data-date="{{ $formattedDate }}"
                                                                                            data-day="{{ $dayOfWeek }}"
                                                                                            data-activity="{{ $hasData ? number_format($activityRate, 1) . '%' : 'No data' }}"
                                                                                            title="{{ $tooltipText }}">
                                                                                        </div>
                                                                                    @else
                                                                                        <div class="w-[10px] h-[10px]">
                                                                                        </div>
                                                                                    @endif
                                                                                @endfor
                                                                            </div>
                                                                        @endfor
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Legend and info -->
                                                <div
                                                    class="flex items-center justify-between text-xs text-gray-500 dark:text-slate-400 mt-3 sm:mt-4">
                                                    <span class="text-[11px] sm:text-xs">Last 12 months
                                                        ({{ $startDate->format('M j, Y') }} -
                                                        {{ $endDate->format('M j, Y') }})</span>
                                                    <div class="flex items-center gap-2">
                                                        <span class="hidden sm:inline text-[11px]">Less</span>
                                                        <div class="flex items-center gap-[3px]">
                                                            <div
                                                                class="w-[10px] h-[10px] bg-gray-100 dark:bg-slate-800 rounded-sm border border-gray-200 dark:border-slate-600">
                                                            </div>
                                                            <div
                                                                class="w-[10px] h-[10px] bg-orange-200 dark:bg-orange-600 rounded-sm">
                                                            </div>
                                                            <div
                                                                class="w-[10px] h-[10px] bg-orange-400 dark:bg-orange-300 rounded-sm">
                                                            </div>
                                                            <div
                                                                class="w-[10px] h-[10px] bg-orange-500 dark:bg-orange-400 rounded-sm">
                                                            </div>
                                                            <div
                                                                class="w-[10px] h-[10px] bg-orange-600 dark:bg-orange-500 rounded-sm">
                                                            </div>
                                                        </div>
                                                        <span class="hidden sm:inline text-[11px]">More</span>
                                                    </div>
                                                </div>

                                                <!-- Enhanced tooltip -->
                                                <div id="activity-tooltip"
                                                    class="fixed z-50 px-3 py-2 text-xs text-white bg-gray-900 dark:bg-slate-800 rounded-lg shadow-lg pointer-events-none opacity-0 transition-opacity duration-200"
                                                    style="transform: translate(-50%, -100%); margin-top: -8px;">
                                                    <div class="font-medium whitespace-nowrap" id="tooltip-content">
                                                    </div>
                                                    <div class="absolute top-full left-1/2 transform -translate-x-1/2 w-0 h-0 border-l-4 border-r-4 border-t-4 border-l-transparent border-r-transparent border-t-gray-900 dark:border-t-slate-800"
                                                        style="margin-top: -1px;"></div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                {{-- Tracks Tab --}}
                                <div class="tab-panel mt-4" x-show="activeTab === 'tracks'" x-transition>
                                    <div
                                        class="flex flex-col sm:flex-row sm:items-center justify-between mb-4 sm:mb-6">
                                        <h2
                                            class="text-gray-900 dark:text-white text-lg sm:text-xl font-semibold mb-2 sm:mb-0">
                                            New & popular tracks
                                        </h2>
                                        <a href="#"
                                            class="text-orange-500 dark:text-orange-400 hover:text-orange-400/90 dark:hover:text-orange-300 text-xs sm:text-sm flex items-center space-x-1">
                                            <span>Show all tracks on Soundcloud</span>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-external-link w-4 h-4">
                                                <path d="M15 3h6v6"></path>
                                                <path d="M10 14 21 3"></path>
                                                <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6">
                                                </path>
                                            </svg>
                                        </a>
                                    </div>

                                    <div class="space-y-3 sm:space-y-4">
                                        @foreach ($tracks as $track)
                                            <div
                                                class="bg-gray-100 dark:bg-slate-800 rounded-lg border border-gray-200 dark:border-slate-700">
                                                <div id="soundcloud-player-track-{{ $track->id }}"
                                                    class="track-card player-card"
                                                    data-campaign-id="{{ $track->id }}" wire:ignore>
                                                    <x-sound-cloud.sound-cloud-player :track="$track"
                                                        :visual="false" />
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    {{-- Pagination: tracks --}}
                                    @if ($tracks && $tracks instanceof \Illuminate\Contracts\Pagination\Paginator)
                                        <div class="mt-6">
                                            {{ $tracks->onEachSide(1)->links('components.pagination.wire-navigate', [
                                                'pageName' => 'tracksPage',
                                                'keep' => ['tab' => 'tracks'],
                                            ]) }}
                                        </div>
                                    @endif
                                </div>

                                {{-- Playlists Tab --}}
                                <div class="tab-panel mt-4" x-show="activeTab === 'playlists'" x-transition>
                                    @if (!$showPlaylistTracks)
                                        {{-- Playlists Grid View --}}
                                        <div
                                            class="flex flex-col sm:flex-row sm:items-center justify-between mb-4 sm:mb-6">
                                            <h2
                                                class="text-gray-900 dark:text-white text-lg sm:text-xl font-semibold mb-2 sm:mb-0">
                                                New & popular playlists
                                            </h2>
                                            <a href="#"
                                                class="text-orange-500 dark:text-orange-400 hover:text-orange-400/90 dark:hover:text-orange-300 text-xs sm:text-sm flex items-center space-x-1">
                                                <span>Show all playlists on Soundcloud</span>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="lucide lucide-external-link w-4 h-4">
                                                    <path d="M15 3h6v6"></path>
                                                    <path d="M10 14 21 3"></path>
                                                    <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6">
                                                    </path>
                                                </svg>
                                            </a>
                                        </div>

                                        <div
                                            class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                                            @foreach ($playlists as $playlist)
                                                <div class="group bg-white dark:bg-slate-800 rounded-lg shadow-sm hover:shadow-md transition-shadow cursor-pointer border border-gray-100 dark:border-slate-700"
                                                    wire:click="selectPlaylist({{ $playlist->id }})">
                                                    <div class="relative">
                                                        <img src="{{ soundcloud_image($playlist->artwork_url) ?? '#' }}"
                                                            alt="Playlist cover"
                                                            class="w-full h-48 object-cover rounded-t-lg" />
                                                        <button
                                                            class="absolute bottom-3 right-3 bg-orange-500 text-white w-12 h-12 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity shadow-lg hover:bg-orange-600">
                                                            <svg class="w-5 h-5" fill="currentColor"
                                                                viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd"
                                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z"
                                                                    clip-rule="evenodd"></path>
                                                            </svg>
                                                        </button>
                                                    </div>
                                                    <div class="p-4">
                                                        <h3
                                                            class="font-semibold text-gray-900 dark:text-white mb-1 truncate">
                                                            {{ $playlist->title }}
                                                        </h3>
                                                        <p
                                                            class="text-gray-500 dark:text-slate-400 text-sm mb-2 line-clamp-2">
                                                            {{ $playlist->description }}
                                                        </p>
                                                        <div
                                                            class="flex items-center justify-between text-xs text-gray-400 dark:text-slate-500">
                                                            <span>{{ $playlist->track_count }} tracks</span>
                                                            <span>{{ $playlist->genre }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>

                                        {{-- Pagination: playlists --}}
                                        @if ($playlists && $playlists instanceof \Illuminate\Contracts\Pagination\Paginator)
                                            <div class="mt-6">
                                                {{ $playlists->onEachSide(1)->links('components.pagination.wire-navigate', [
                                                    'pageName' => 'playlistsPage',
                                                    'keep' => ['tab' => 'playlists'],
                                                ]) }}
                                            </div>
                                        @endif
                                    @else
                                        {{-- Playlist Tracks View --}}
                                        <div class="mb-6">
                                            {{-- Back Button and Playlist Header --}}
                                            <div class="flex items-center mb-4">
                                                <button wire:click="backToPlaylists"
                                                    class="flex items-center text-orange-500 dark:text-orange-400 hover:text-orange-600 dark:hover:text-orange-300 transition-colors mr-4">
                                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                                                        </path>
                                                    </svg>
                                                    Back to Playlists
                                                </button>
                                            </div>

                                            {{-- Playlist Info Header --}}
                                            <div
                                                class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-lg p-6 mb-6">
                                                <div class="flex flex-col sm:flex-row items-start gap-4">
                                                    <img src="{{ soundcloud_image($selectedPlaylist->artwork_url) }}"
                                                        alt="{{ $selectedPlaylist->title }}"
                                                        class="w-24 h-24 sm:w-32 sm:h-32 rounded-lg shadow-lg object-cover" />
                                                    <div class="flex-1 text-white">
                                                        <h1 class="text-2xl sm:text-3xl font-bold mb-2">
                                                            {{ $selectedPlaylist->title }}
                                                        </h1>
                                                        <p class="text-orange-100 mb-2">
                                                            {{ $selectedPlaylist->description }}
                                                        </p>
                                                        <div class="flex items-center gap-4 text-sm text-orange-200">
                                                            <span>{{ $selectedPlaylist->track_count }}
                                                                tracks</span>
                                                            <span>{{ $selectedPlaylist->genre }}</span>
                                                            <span>Created
                                                                {{ $selectedPlaylist->created_at->diffForHumans() }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Playlist Tracks Header --}}
                                        <div
                                            class="flex flex-col sm:flex-row sm:items-center justify-between mb-4 sm:mb-6">
                                            <h2
                                                class="text-gray-900 dark:text-white text-lg sm:text-xl font-semibold mb-2 sm:mb-0">
                                                Tracks from playlist
                                                <span
                                                    class="text-orange-500 dark:text-orange-400 hover:text-orange-400/90 dark:hover:text-orange-300">
                                                    {{ $selectedPlaylist->title }}
                                                </span>
                                            </h2>
                                            <a href="{{ $selectedPlaylist->permalink_url ?? '#' }}" target="_blank"
                                                class="text-orange-500 dark:text-orange-400 hover:text-orange-400/90 dark:hover:text-orange-300 text-xs sm:text-sm flex items-center space-x-1">
                                                <span>Open playlist on Soundcloud</span>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="lucide lucide-external-link w-4 h-4">
                                                    <path d="M15 3h6v6"></path>
                                                    <path d="M10 14 21 3"></path>
                                                    <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6">
                                                    </path>
                                                </svg>
                                            </a>
                                        </div>

                                        {{-- Playlist Tracks List --}}
                                        <div class="space-y-3 sm:space-y-4">
                                            @if ($playlistTracks && $playlistTracks->count() > 0)
                                                @foreach ($playlistTracks as $track)
                                                    <div
                                                        class="playlist-track-card player-card bg-gray-100 dark:bg-slate-800 rounded-lg border border-gray-200 dark:border-slate-700">
                                                        <div id="soundcloud-player-playlist-track-{{ $track->id }}"
                                                            data-campaign-id="{{ $track->id }}" wire:ignore>
                                                            <x-sound-cloud.sound-cloud-player :track="$track"
                                                                :visual="false" />
                                                        </div>
                                                    </div>
                                                @endforeach

                                                {{-- Pagination: playlist tracks --}}
                                                <div class="mt-6">
                                                    {{ $playlistTracks->onEachSide(1)->links('components.pagination.wire-navigate', [
                                                        'pageName' => 'playlistTracksPage',
                                                        'keep' => [
                                                            'tab' => 'playlists',
                                                            'selectedPlaylistId' => $selectedPlaylist->id,
                                                        ],
                                                    ]) }}
                                                </div>
                                            @else
                                                <div class="text-center py-8">
                                                    <div class="text-gray-400 dark:text-slate-500 mb-2">
                                                        <svg class="w-12 h-12 mx-auto mb-4" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3">
                                                            </path>
                                                        </svg>
                                                    </div>
                                                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">
                                                        No tracks found</h3>
                                                    <p class="text-gray-500 dark:text-slate-400">This playlist
                                                        doesn't have any tracks yet.</p>
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                </div>

                                {{-- Reposts Tab --}}
                                <div class="tab-panel mt-4" x-show="activeTab === 'reposts'" x-transition>
                                    <h2 class="text-gray-900 dark:text-white text-lg sm:text-xl font-semibold mb-4">
                                        Recent Reposts
                                    </h2>
                                    @foreach ($reposts as $repost)
                                        <div
                                            class="bg-gray-200 mb-2 dark:bg-slate-800 rounded-lg border border-gray-200 dark:border-slate-700 sm:flex space-y-4 sm:space-y-0">
                                            {{-- SoundCloud Player --}}
                                            <div class="sm:w-1/2 w-full">
                                                @if ($repost)
                                                    <div id="soundcloud-player-repost-{{ $repost->source_id }}"
                                                        class="repost-card player-card"
                                                        data-campaign-id="{{ $repost->source_id }}" wire:ignore>
                                                        <x-sound-cloud.sound-cloud-player :track="$repost->source"
                                                            :visual="false" />
                                                    </div>
                                                @else
                                                    <p class="text-sm text-gray-500 dark:text-gray-400">No track
                                                        available</p>
                                                @endif
                                            </div>

                                            {{-- Info Block --}}
                                            <div class="w-full sm:w-1/2">
                                                <div
                                                    class="flex flex-col sm:flex-row sm:items-center p-4 sm:justify-between gap-4 h-full bg-gray-100/70 dark:bg-slate-800 border border-gray-200 dark:border-slate-700">
                                                    <div class="flex-1 grid sm:grid-cols-2 gap-4 relative">
                                                        {{-- Left: Track Info --}}
                                                        <div class="flex flex-col items-start gap-2">
                                                            {{-- Avatar --}}
                                                            <div class="flex items-center gap-2">
                                                                <img src="{{ soundcloud_image($repost?->source?->artwork_url) }}"
                                                                    class="w-12 h-12 rounded-full object-cover"
                                                                    alt="Track artwork" />
                                                                <div x-data="{ open: false }"
                                                                    class=" inline-block text-left">
                                                                    <div class="flex items-center gap-1 cursor-pointer"
                                                                        @click="open = !open"
                                                                        @click.outside="open = false">
                                                                        <span
                                                                            class="text-slate-700 dark:text-gray-300 font-medium">
                                                                            {{ $repost->source->user->name ?? 'Unknown User' }}
                                                                        </span>
                                                                        <svg class="w-4 h-4 text-gray-500"
                                                                            fill="currentColor" viewBox="0 0 20 20">
                                                                            <path fill-rule="evenodd"
                                                                                d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.25a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z"
                                                                                clip-rule="evenodd" />
                                                                        </svg>
                                                                    </div>

                                                                    {{-- Rating Stars --}}
                                                                    <div class="flex items-center mt-1">
                                                                        @for ($i = 1; $i <= 5; $i++)
                                                                            <svg class="w-4 h-4 {{ $i <= ($repost->source->user->rating ?? 4) ? 'text-yellow-400' : 'text-gray-300' }}"
                                                                                fill="currentColor"
                                                                                viewBox="0 0 20 20">
                                                                                <path
                                                                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.783-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                                                            </svg>
                                                                        @endfor
                                                                    </div>

                                                                    {{-- Dropdown Menu --}}
                                                                    <div x-show="open" x-transition.opacity
                                                                        class="absolute left-0 mt-2 w-56 z-50 shadow-lg bg-gray-900 text-white text-sm p-2 space-y-2"
                                                                        x-cloak>
                                                                        <a target="_blank"
                                                                            href="{{ $repost->source->user->soundcloud_url ?? '#' }}"
                                                                            class="block hover:bg-gray-800 px-3 py-1 rounded">
                                                                            Visit SoundCloud Profile
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="flex-1">
                                                                {{-- Track Title --}}
                                                                <h3
                                                                    class="text-base font-semibold text-gray-900 dark:text-white mt-1 truncate">
                                                                    <a href="javascript:void(0)"
                                                                        class="hover:text-orange-500 dark:hover:text-orange-400 line-clamp-1">
                                                                        {{ Str::limit($repost->source?->title, 50) ?? 'Untitled Track' }}
                                                                    </a>
                                                                </h3>
                                                                {{-- Genre --}}
                                                                <div class="flex flex-wrap gap-2 mt-1">
                                                                    <span
                                                                        class="inline-block bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm font-medium px-3 py-1.5 rounded-md shadow-sm">
                                                                        {{ !empty($repost->source?->genre) ? $repost->source?->genre : 'Unknown Genre' }}
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        {{-- Right: Status Info --}}
                                                        <div
                                                            class="flex flex-col sm:items-end gap-2 text-xs text-gray-400 dark:text-slate-500">
                                                            <p>{{ $repost->source_type }}</p>
                                                            <p>
                                                                💰 Credits Earned:
                                                                <span
                                                                    class="font-semibold text-green-500">{{ $repost->credits_earned }}</span>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                @if (user()->urn == $user->urn)
                                    {{-- Transaction Tab --}}
                                    <div class="tab-panel mt-4" x-show="activeTab === 'transaction'" x-transition>
                                        <div class="w-full overflow-x-auto">
                                            <table
                                                class="min-w-[900px] w-full table-fixed text-sm text-left divide-y divide-gray-200 dark:divide-gray-700 rounded overflow-hidden">
                                                <thead
                                                    class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-white text-xs sm:text-sm">
                                                    <tr>
                                                        <th class="w-10 px-2 py-3">ID</th>
                                                        <th class="w-28 px-2 py-3">Sender Name</th>
                                                        <th class="w-20 px-2 py-3">Amount</th>
                                                        <th class="w-20 px-2 py-3">Credits</th>
                                                        <th class="w-24 px-2 py-3">Type</th>
                                                        <th class="w-20 px-2 py-3">Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody
                                                    class="bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-200">
                                                    @forelse ($transactions as $transaction)
                                                        <tr class="border-b border-gray-200 dark:border-gray-700">
                                                            <td class="px-2 py-2">{{ $loop->iteration }}</td>
                                                            <td class="px-2 py-2">
                                                                {{ $transaction->sender?->name ?? 'System' }}
                                                            </td>
                                                            <td class="px-2 py-2">
                                                                {{ $transaction->amount ?? '0.00' }}
                                                            </td>
                                                            <td class="px-2 py-2">{{ $transaction->credits }}</td>
                                                            <td class="px-2 py-2">
                                                                {{ $transaction->transaction_type_name }}</td>
                                                            <td class="px-2 py-2 text-green-600 font-semibold">
                                                                <span
                                                                    class="badge badge-soft badge-{{ $transaction->status_color }}">{{ $transaction->status_label }}</span>
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr class="border-b border-gray-200 dark:border-gray-700">
                                                            <td colspan="6"
                                                                class="px-2 py-2 text-center font-medium">
                                                                No transactions found.
                                                            </td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @push('js')
        <script>
            function initializeSoundCloudWidgets() {
                if (typeof SC === 'undefined') {
                    setTimeout(initializeSoundCloudWidgets, 500);
                    console.log('SC undefined');
                    var script = document.createElement('script');
                    script.src = 'https://w.soundcloud.com/player/api.js';
                    script.async = true; // Load asynchronously
                    document.body.appendChild(script);
                    console.log('SoundCloud Widget API loaded.');

                    script.onload = function() {
                        console.log('SoundCloud Widget API loaded.');
                        initSoundCloudWidget();
                    };
                    script.onerror = function() {
                        console.error('Failed to load SoundCloud Widget API.');
                    };

                    return;
                }

                const playerLists = [
                    '.track-card', // from the $tracks loop
                    '.playlist-track-card', // from the $playlistTracks loop
                    '.repost-card' // from the $reposts loop
                ];

                playerLists.forEach(listSelector => {
                    const containers = document.querySelectorAll(listSelector);

                    if (containers.length === 0) {
                        return; // Skip if no players are found for this selector
                    }
                    containers.forEach((container, index) => {

                        // The container variable is now the outer div (e.g., the one with class="track-card")
                        // The iframe is nested a level deeper within the <div id="soundcloud-player-...">

                        const iframe = container.querySelector('iframe');

                        if (!iframe) {
                            console.warn(
                                `Iframe not found inside container for ${listSelector} at index ${index}`);
                            return;
                        }

                        const currentIframeId = iframe.id;

                        // 3. Find the NEXT player using the current list's index
                        let nextIframe = null;
                        let nextIframeId = null;

                        if (index < containers.length - 1) {
                            // Get the next container card from the current list (containers NodeList)
                            const nextContainerCard = containers[index + 1];

                            // Query for the iframe inside the next card
                            nextIframe = nextContainerCard.querySelector('iframe');

                            if (nextIframe) {
                                nextIframeId = nextIframe.id;
                            }
                        }

                        // 4. Bind SoundCloud Widget events
                        const widget = SC.Widget(iframe);
                        widget.bind(SC.Widget.Events.FINISH, () => {
                            if (nextIframe) {
                                const nextWidget = SC.Widget(nextIframe);
                                nextWidget.play();
                            }
                        });
                    });
                });

            }

            document.addEventListener('DOMContentLoaded', function() {
                const tooltip = document.getElementById('activity-tooltip');
                const tooltipContent = document.getElementById('tooltip-content');
                const activitySquares = document.querySelectorAll('[data-tooltip="true"]');

                activitySquares.forEach(square => {
                    square.addEventListener('mouseenter', function(e) {
                        const date = e.target.getAttribute('data-date');
                        const activity = e.target.getAttribute('data-activity');

                        tooltipContent.innerHTML =
                            `<strong>${activity} activity</strong><br>${date}`;

                        const rect = e.target.getBoundingClientRect();
                        const tooltipRect = tooltip.getBoundingClientRect();

                        tooltip.style.left =
                            `${rect.left + rect.width / 2 - tooltipRect.width / 2}px`;
                        tooltip.style.top = `${rect.top - 10}px`;
                        tooltip.classList.remove('opacity-0');
                        tooltip.classList.add('opacity-100');
                    });

                    square.addEventListener('mouseleave', function() {
                        tooltip.classList.remove('opacity-100');
                        tooltip.classList.add('opacity-0');
                    });
                });
            });

            document.addEventListener('livewire:initialized', function() {
                initializeSoundCloudWidgets();
            });
            document.addEventListener('livewire:navigated', function() {
                initializeSoundCloudWidgets();
            });
            document.addEventListener('livewire:load', function() {
                initializeSoundCloudWidgets();
            });
            document.addEventListener('livewire:updated', function() {
                initializeSoundCloudWidgets();
            });
            document.addEventListener('DOMContentLoaded', function() {
                initializeSoundCloudWidgets();
            });
        </script>
    @endpush

</section>
