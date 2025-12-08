<div>
    <x-slot name="page_slug">favourite-member</x-slot>
    <!-- Title Section -->
    <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">
        @if ($starred == 'favourite')
            {{ __("Members you've starred") }}
        @elseif($starred == 'favourited')
            {{ __('Members who starred you') }}
        @endif
    </h2>
    <div>
        @if ($starred == 'favourited')
            @forelse ($favouriteUsers as $favouriteUser)
                <div
                    class="p-6 mt-4 bg-card-blue rounded-lg p-6 bg-white dark:bg-gray-800 shadow-lg dark:shadow-[0_4px_20px_rgba(0,0,0,0.8)] flex justify-between items-center space-y-2 md:space-y-0 flex-col sm:flex-row">
                    <!-- Member Card -->
                    <div class="flex items-center space-x-4 mb-4 sm:mb-0">
                        <div class="w-16 h-16 bg-gray-300 rounded-full flex items-center justify-center">
                            <img src="{{ auth_storage_url($favouriteUser->follower?->avatar) }}" alt="Member Avatar"
                                class="w-16 h-16 rounded-full object-cover">
                        </div>
                        <div class="relative mt-2 sm:mt-0">
                            <div x-data="{ open: false }" class="inline-block text-left">
                                <div class="flex flex-col sm:flex-row  sm:items-center gap-3">
                                    <div @click="open = !open" @click.outside="open = false"
                                        class="flex items-center gap-1 cursor-pointer">
                                        <span
                                            class="text-slate-700 dark:text-gray-300 font-medium">{{ $favouriteUser->follower?->name }}</span>
                                        <svg class="w-4 h-4 text-gray-600 dark:text-gray-400" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </div>
                                    @if (proUser($favouriteUser->follower?->urn))
                                        <span
                                            class="text-sm badge badge-soft badge-warning rounded-full font-semibold">{{ userPlanName($favouriteUser->follower?->urn) }}</span>
                                    @else
                                        <span
                                            class="text-sm badge badge-soft badge-info rounded-full font-semibold">{{ userPlanName($favouriteUser->follower?->urn) }}</span>
                                    @endif
                                </div>
                                <div x-show="open" x-transition.opacity
                                    class="absolute left-0 mt-2 w-56 z-50 shadow-lg bg-gray-900 text-white text-sm p-2 space-y-2"
                                    x-cloak>
                                    <a href="{{ $favouriteUser->follower?->soundcloud_permalink_url }}" target="_blank"
                                        class="block hover:bg-gray-800 px-3 py-1 rounded">Visit SoundCloud Profile</a>
                                    <a href="{{ route('user.my-account.user', !empty($favouriteUser->follower?->name) ? $favouriteUser->follower?->name : $favouriteUser->follower_urn) }}"
                                        wire:navigate class="block hover:bg-gray-800 px-3 py-1 rounded">Visit
                                        RepostChain Profile</a>
                                </div>
                            </div>
                            <div class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                                {{ $favouriteUser->follower?->created_at->format('M d, Y') }}</div>
                        </div>
                    </div>

                    <!-- Tags Section -->
                    <div class="flex space-x-2 mt-4 flex-wrap">
                        @foreach ($favouriteUser->follower?->genres as $genre)
                            <span
                                class="bg-gray-600 text-white text-xs px-2 py-1 rounded-full">{{ $genre->genre }}</span>
                        @endforeach
                    </div>

                    <!-- Request Button -->
                    <div class="mt-4 sm:mt-0 text-right">
                        @if (requestReceiveable($favouriteUser->follower_urn))
                            <x-gbutton variant="primary" :full-width="false"
                                wire:click="openModal('{{ $favouriteUser->follower_urn }}')">Request</x-gbutton>
                        @else
                            <x-gbutton variant="primary" :full-width="false" disabled
                                class="!cursor-not-allowed !py-3">Request Later</x-gbutton>
                        @endif
                    </div>
                </div>
            @empty
                <div
                    class="flex items-center p-4 bg-white dark:bg-gray-800 shadow-lg dark:shadow-[0_4px_20px_rgba(0,0,0,0.8)] space-x-4 mt-4 flex-col sm:flex-row">
                    <!-- Avatar placeholder -->
                    <div class="w-12 h-12 rounded-full bg-gray-200 animate-pulse"></div>
                    <div class="flex flex-col gap-2">
                        <div class="w-48 h-3 bg-gray-300 animate-pulse"></div>
                        <div class="w-40 h-3 bg-gray-200 animate-pulse"></div>
                    </div>

                    <!-- Text -->
                    <p class="flex-1 text-gray-400 text-sm select-none lg:ms-10 text-center sm:text-left">
                        Seems like you haven't starred any members yet
                    </p>

                    <!-- Discover Members button -->
                    <a href="{{ route('user.members') }}" wire:navigate
                        class="inline-block px-5 py-2 text-white bg-orange-600 hover:bg-orange-700 rounded transition mt-4 sm:mt-0">
                        Discover Members
                    </a>
                </div>
            @endforelse
        @elseif ($starred == 'favourite')
            @forelse ($favouriteUsers as $favouriteUser)
                <div
                    class="p-6 mt-4 bg-card-blue rounded-lg p-6 bg-white dark:bg-gray-800 shadow-lg dark:shadow-[0_4px_20px_rgba(0,0,0,0.8)] flex justify-between items-center space-y-2 md:space-y-0 flex-col sm:flex-row">
                    <!-- Member Card -->
                    <div class="flex items-center space-x-4 mb-4 sm:mb-0">
                        <div class="w-16 h-16 bg-gray-300 rounded-full flex items-center justify-center">
                            <img src="{{ auth_storage_url($favouriteUser->following?->avatar) }}" alt="Member Avatar"
                                class="w-16 h-16 rounded-full object-cover">
                        </div>
                        <div class="relative mt-2 sm:mt-0">
                            <div x-data="{ open: false }" class="inline-block text-left">
                                <div class="flex flex-col sm:flex-row sm:items-center gap-3">
                                    <div @click="open = !open" @click.outside="open = false"
                                        class="flex items-center gap-1 cursor-pointer">
                                        <span
                                            class="text-slate-700 dark:text-gray-300 font-medium">{{ $favouriteUser->following?->name }}</span>
                                        <svg class="w-4 h-4 text-gray-600 dark:text-gray-400" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </div>
                                    @if (proUser($favouriteUser->following?->urn))
                                        <span
                                            class="text-sm badge badge-soft badge-warning rounded-full font-semibold">{{ userPlanName($favouriteUser->following?->urn) }}</span>
                                    @else
                                        <span
                                            class="text-sm badge badge-soft badge-info rounded-full font-semibold">{{ userPlanName($favouriteUser->following?->urn) }}</span>
                                    @endif
                                </div>
                                <div x-show="open" x-transition.opacity
                                    class="absolute left-0 mt-2 w-56 z-50 shadow-lg bg-gray-900 text-white text-sm p-2 space-y-2"
                                    x-cloak>
                                    <a href="{{ $favouriteUser->following?->soundcloud_permalink_url }}"
                                        target="_blank" class="block hover:bg-gray-800 px-3 py-1 rounded">Visit
                                        SoundCloud Profile</a>
                                    <a href="{{ route('user.my-account.user', !empty($favouriteUser->following?->name) ? $favouriteUser->following?->name : $favouriteUser->following_urn) }}"
                                        wire:navigate class="block hover:bg-gray-800 px-3 py-1 rounded">Visit
                                        RepostChain Profile</a>
                                </div>
                            </div>
                            <div class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                                {{ $favouriteUser->following?->created_at->format('M d, Y') }}</div>
                        </div>
                    </div>

                    <!-- Tags Section -->
                    <div class="flex space-x-2 mt-4 flex-wrap">
                        @foreach ($favouriteUser->following?->genres as $genre)
                            <span
                                class="bg-gray-600 text-white text-xs px-2 py-1 rounded-full">{{ $genre->genre }}</span>
                        @endforeach
                    </div>

                    <!-- Request Button -->
                    <div class="mt-4 sm:mt-0">
                        @if (requestReceiveable($favouriteUser->following_urn))
                            <x-gbutton variant="primary" :full-width="false"
                                wire:click="openModal('{{ $favouriteUser->following_urn }}')">Request</x-gbutton>
                        @else
                            <x-gbutton variant="primary" :full-width="false" disabled
                                class="!cursor-not-allowed !py-3">Request Later</x-gbutton>
                        @endif
                    </div>
                </div>
            @empty
                <div
                    class="flex items-center p-4 bg-white dark:bg-gray-800 shadow-lg dark:shadow-[0_4px_20px_rgba(0,0,0,0.8)] space-x-4 mt-4 flex-col sm:flex-row">
                    <!-- Avatar placeholder -->
                    <div class="w-12 h-12 rounded-full bg-gray-200 animate-pulse"></div>
                    <div class="flex flex-col gap-2">
                        <div class="w-48 h-3 bg-gray-300 animate-pulse"></div>
                        <div class="w-40 h-3 bg-gray-200 animate-pulse"></div>
                    </div>

                    <!-- Text -->
                    <p class="flex-1 text-gray-400 text-sm select-none lg:ms-10 text-center sm:text-left">
                        Seems like you haven't starred any members yet
                    </p>

                    <!-- Discover Members button -->
                    <a href="{{ route('user.members') }}" wire:navigate
                        class="inline-block px-5 py-2 text-white bg-orange-600 hover:bg-orange-700 rounded transition mt-4 sm:mt-0">
                        Discover Members
                    </a>
                </div>
            @endforelse

        @endif
    </div>

    <div x-data="{ showModal: @entangle('showModal').live }" x-show="showModal" x-cloak x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4 z-50">

        <div
            class="w-full max-w-3xl mx-auto rounded-2xl shadow-2xl bg-white dark:bg-slate-800 border border-gray-200 dark:border-gray-700 flex flex-col max-h-[80vh] overflow-hidden">

            {{-- HEADER --}}
            <div
                class="flex justify-between items-center p-6 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-orange-50 to-orange-100 dark:from-orange-900/20 dark:to-orange-800/20">
                <div class="flex items-center gap-3">
                    <div>
                        @if (app_setting('favicon') && app_setting('favicon_dark'))
                            <img src="{{ storage_url(app_setting('favicon')) }}" alt="{{ config('app.name') }}"
                                class="w-12 dark:hidden" />
                            <img src="{{ storage_url(app_setting('favicon_dark')) }}" alt="{{ config('app.name') }}"
                                class="w-12 hidden dark:block" />
                        @else
                            <img src="{{ asset('assets/favicons/fav icon 1.svg') }}" alt="{{ config('app.name') }}"
                                class="w-12 dark:hidden" />
                            <img src="{{ asset('assets/favicons/fav icon 2 (1).svg') }}"
                                alt="{{ config('app.name') }}" class="w-12 hidden dark:block" />
                        @endif
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ __('Choose a track or playlist') }}
                    </h2>
                </div>
                <button x-on:click="showModal = false"
                    class="w-10 h-10 rounded-xl bg-white dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-all duration-200 flex items-center justify-center border border-gray-200 dark:border-gray-600">
                    <x-heroicon-s-x-mark class="w-5 h-5" />
                </button>
            </div>

            @if ($showModal)
                {{-- TABS --}}
                <div class="flex border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                    <button wire:click="setActiveTab('tracks')"
                        class="cursor-pointer flex-1 py-4 px-6 text-center font-semibold text-base transition-all duration-300 ease-in-out border-b-2 hover:bg-white dark:hover:bg-gray-700 {{ $activeTab === 'tracks' ? 'border-orange-500 text-orange-600 bg-white dark:bg-gray-700' : 'border-transparent text-gray-600 dark:text-gray-400' }}">
                        <div class="flex items-center justify-center gap-2">
                            <i data-lucide="music" class="w-4 h-4"></i>
                            {{ __('Tracks') }}
                        </div>
                    </button>
                    <button wire:click="setActiveTab('playlists')"
                        class="cursor-pointer flex-1 py-4 px-6 text-center font-semibold text-base transition-all duration-300 ease-in-out border-b-2 hover:bg-white dark:hover:bg-gray-700 {{ $activeTab === 'playlists' ? 'border-orange-500 text-orange-600 bg-white dark:bg-gray-700' : 'border-transparent text-gray-600 dark:text-gray-400' }}">
                        <div class="flex items-center justify-center gap-2">
                            <i data-lucide="list-music" class="w-4 h-4"></i>
                            {{ __('Playlists') }}
                        </div>
                    </button>
                </div>

                {{-- SEARCH & CONTENT --}}
                <div class="w-full max-w-2xl mx-auto mt-6 flex flex-col overflow-hidden">
                    {{-- @if ($activeTab === 'tracks') --}}
                    {{-- SEARCH BAR --}}
                    <div class="p-1">
                        <label for="track-link-search" class="text-xl font-semibold text-gray-700 dark:text-gray-200">
                            @if ($activeTab === 'tracks')
                                Paste a SoundCloud profile or track link
                            @else
                                Paste a SoundCloud playlist link
                            @endif
                        </label>
                        {{-- <div class="flex w-full mt-2">
                                    <input wire:model="searchQuery" type="text" id="track-link-search"
                                        placeholder="{{ $activeTab === 'tracks' ? 'Paste a SoundCloud profile or track link' : 'Paste a SoundCloud playlist link' }}"
                                        class="flex-grow p-3 text-gray-700 dark:text-gray-200 bg-white dark:bg-slate-700 placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-orange-500 transition-colors duration-200 border border-gray-300 dark:border-gray-600 ">
                                    <button wire:click="searchSoundcloud" type="button"
                                        class="bg-orange-500 text-white p-3 w-14 flex items-center justify-center hover:bg-orange-600 transition-colors duration-200 ">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                        </svg>
                                    </button>
                                </div> --}}
                        <form wire:submit.prevent="searchSoundcloud">
                            <div class="flex w-full mt-2">
                                <input wire:model="searchQuery" type="text" id="track-link-search"
                                    placeholder="{{ $activeTab === 'tracks' ? 'Paste a SoundCloud track link' : 'Paste a SoundCloud playlist link' }}"
                                    class="flex-grow p-3 text-gray-700 dark:text-gray-200 bg-white dark:bg-slate-700 placeholder-gray-400  dark:placeholder-gray-500 focus:outline-none focus:ring-0 focus:border-orange-500 transition-colors duration-200 border-2 border-r-0 border-gray-300 dark:border-gray-600">

                                <!-- Button content changes during loading -->
                                <button type="submit"
                                    class="bg-orange-500 text-white p-3 w-14 flex items-center justify-center hover:bg-orange-600 transition-colors duration-200">

                                    <span wire:loading.remove wire:target="searchSoundcloud">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                        </svg>
                                    </span>

                                    <span wire:loading wire:target="searchSoundcloud">
                                        <!-- Loading Spinner -->
                                        <svg class="animate-spin h-5 w-5 text-white"
                                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10"
                                                stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor"
                                                d="M4 12a8 8 0 018-8v4l3-3-3-3v4a8 8 0 100 16v-4l-3 3 3 3v-4a8 8 0 01-8-8z">
                                            </path>
                                        </svg>
                                    </span>
                                </button>
                            </div>
                        </form>
                    </div>
                    {{-- @endif --}}


                    {{-- TRACKS OR PLAYLISTS --}}
                    <div class="h-full overflow-y-auto px-4 pb-6 space-y-1">
                        @if ($activeTab === 'tracks' || $playListTrackShow == true)
                            @forelse ($tracks as $track_)
                                <div wire:click="openRepostsModal( 'track',{{ $track_->id }})"
                                    class="p-2 flex items-center space-x-4 cursor-pointer hover:bg-gray-100 dark:hover:bg-slate-700/50 rounded-md transition-colors duration-200">
                                    <div class="flex-shrink-0">
                                        <img class="h-12 w-12 rounded object-cover shadow"
                                            src="{{ soundcloud_image($track_->artwork_url) }}"
                                            alt="{{ $track_->title }}" />
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm text-gray-500 dark:text-gray-400 truncate">
                                            {{ $track_->type }} • {{ $track_->author_username }}
                                        </p>
                                        <p class="font-semibold text-gray-800 dark:text-white truncate">
                                            {{ $track_->title }}
                                        </p>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <i data-lucide="chevron-right"
                                            class="w-5 h-5 text-gray-400 group-hover:text-orange-500 transition-colors"></i>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-16 text-gray-500 dark:text-gray-400">
                                    <div
                                        class="w-16 h-16 bg-orange-100 dark:bg-orange-900/20 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <x-lucide-music class="w-8 h-8 text-orange-500" />
                                    </div>
                                    <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                        {{ __('No tracks found') }}
                                    </h3>
                                    <p class="text-gray-500 dark:text-gray-400">
                                        {{ __('Add one to get started with campaigns.') }}
                                    </p>
                                </div>
                            @endforelse

                            @if (count($tracks) < count($allTracks))
                                <div class="text-center mt-6">
                                    <x-gbutton variant="primary" size="sm" wire:click="loadMoreTracks">Load
                                        more</x-gbutton>
                                </div>
                            @endif
                        @elseif($activeTab === 'playlists')
                            @forelse ($playlists as $playlist_)
                                <div wire:click="openRepostsModal( 'playlist',{{ $playlist_->id }})"
                                    class="p-2 flex items-center space-x-4 cursor-pointer hover:bg-gray-100 dark:hover:bg-slate-700/50 rounded-md transition-colors duration-200">
                                    <div class="flex-shrink-0">
                                        <img class="h-12 w-12 rounded object-cover shadow"
                                            src="{{ soundcloud_image($playlist_->artwork_url) }}"
                                            alt="{{ $playlist_->title }}" />
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="font-semibold text-gray-800 dark:text-white truncate">
                                            {{ $playlist_->title }}
                                        </p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 truncate">
                                            {{ $playlist_->track_count }} {{ __('tracks') }}
                                        </p>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <i data-lucide="chevron-right"
                                            class="w-5 h-5 text-gray-400 group-hover:text-orange-500 transition-colors"></i>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-16 text-gray-500 dark:text-gray-400">
                                    <div
                                        class="w-16 h-16 bg-orange-100 dark:bg-orange-900/20 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <x-lucide-list-music class="w-8 h-8 text-orange-500" />
                                    </div>
                                    <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                        {{ __('No playlists found') }}
                                    </h3>
                                    <p class="text-gray-500 dark:text-gray-400">
                                        {{ __('Add one to get started with campaigns.') }}
                                    </p>
                                </div>
                            @endforelse

                            @if (count($playlists) < count($allPlaylists))
                                <div class="text-center mt-6">
                                    <x-gbutton variant="primary" size="sm" wire:click="loadMorePlaylists">Load
                                        more</x-gbutton>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
    {{-- Low Credit Warning Modal --}}
    <div x-data="{ showLowCreditWarningModal: @entangle('showLowCreditWarningModal').live }" x-show="showLowCreditWarningModal" x-cloak
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
        class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4 z-50">

        <div
            class="w-full max-w-md mx-auto rounded-2xl shadow-2xl bg-white dark:bg-slate-800 border border-gray-200 dark:border-gray-700 flex flex-col overflow-hidden">
            <div
                class="flex justify-between items-center p-6 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-red-50 to-red-100 dark:from-red-900/20 dark:to-red-800/20">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-red-500 rounded-xl flex items-center justify-center">
                        <x-lucide-triangle-alert class="w-5 h-5 text-white" />
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ __('Low Credit Warning') }}
                    </h2>
                </div>
                <button x-on:click="showLowCreditWarningModal = false"
                    class="w-10 h-10 rounded-xl bg-white dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-all duration-200 flex items-center justify-center border border-gray-200 dark:border-gray-600">
                    <x-lucide-x class="w-5 h-5" />
                </button>
            </div>

            <div class="p-6 text-center">
                <div
                    class="w-20 h-20 bg-red-100 dark:bg-red-900/20 rounded-full flex items-center justify-center mx-auto mb-6">
                    <x-lucide-wallet class="w-10 h-10 text-red-600 dark:text-red-400" />
                </div>
                <p class="text-lg text-gray-700 dark:text-gray-300 mb-4">
                    {{ __('You need a minimum of 50 credits to send a direct request.') }}
                </p>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">
                    {{ __('Please add more credits to your account to proceed with direct request.') }}
                </p>
                {{-- <a href="{{ route('user.add-credits') }}" wire:navigate
                    class="inline-flex items-center justify-center w-full bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white font-bold py-3 px-6 rounded-xl transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                    <x-lucide-plus class="w-5 h-5 inline mr-2" />
                    {{ __('Buy Credits Now') }}
                </a> --}}
                <x-gbutton :full-width="true" variant="primary" wire:navigate href="{{ route('user.add-credits') }}"
                    class="mb-2">{{ __('Buy Credits Now') }}</x-gbutton>
            </div>
        </div>
    </div>
    {{-- Reposts Modal --}}
    <div x-data="{ showRepostsModal: @entangle('showRepostsModal').live }" x-show="showRepostsModal" x-cloak
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
        class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4 z-50">

        <div
            class="w-full max-w-3xl mx-auto rounded-2xl shadow-2xl bg-white dark:bg-slate-800 border border-gray-200 dark:border-gray-700 flex flex-col max-h-[80vh] overflow-hidden">

            <div
                class="flex justify-between items-center p-6 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-orange-50 to-orange-100 dark:from-orange-900/20 dark:to-orange-800/20">
                <div class="flex items-center gap-3">
                    <div>
                        @if (app_setting('favicon') && app_setting('favicon_dark'))
                            <img src="{{ storage_url(app_setting('favicon')) }}" alt="{{ config('app.name') }}"
                                class="w-12 dark:hidden" />
                            <img src="{{ storage_url(app_setting('favicon_dark')) }}" alt="{{ config('app.name') }}"
                                class="w-12 hidden dark:block" />
                        @else
                            <img src="{{ asset('assets/favicons/fav icon 1.svg') }}" alt="{{ config('app.name') }}"
                                class="w-12 dark:hidden" />
                            <img src="{{ asset('assets/favicons/fav icon 2 (1).svg') }}"
                                alt="{{ config('app.name') }}" class="w-12 hidden dark:block" />
                        @endif
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ __('Requesting Reposts') }}
                    </h2>
                </div>
                <button x-on:click="showRepostsModal= false"
                    class="w-10 h-10 rounded-xl bg-white dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-all duration-200 flex items-center justify-center border border-gray-200 dark:border-gray-600">
                    <x-heroicon-s-x-mark class="w-5 h-5" />
                </button>
            </div>

            <div class="flex-1 overflow-y-auto">
                <div class="p-6">
                    @if ($music)
                        {{-- User Info --}}
                        <div class="flex justify-between space-x-4 items-center  border border-transparent mb-4">
                            <div class="flex gap-3 items-center">
                                <div class="flex-shrink-0">
                                    <img class="h-14 w-14 rounded-full object-cover shadow-md"
                                        src="{{ auth_storage_url($user->avatar) }}" alt="{{ $user->name }}" />
                                </div>

                                <p
                                    class="text-base font-normal text-orange-500 dark:text-white truncate group-hover:text-orange-600 dark:group-hover:text-orange-400 transition-colors">
                                    {{ $user->name }}
                                </p>
                            </div>
                            <div class="flex items-center justify-between mt-4">
                                <span class="dark:text-white font-medium">Cost</span>
                                <div class="flex items-center gap-2 ml-1">
                                    {{-- <span
                                                class="text-gray-800 dark:text-orange-400 font-bold">{{ repostPrice($user) > 1 ? repostPrice($user) . ' Credits' : repostPrice($user) . ' Credit' }}</span> --}}
                                    <span
                                        class="text-gray-800 dark:text-orange-400 font-bold">{{ $user->repost_price > 1 ? $user->repost_price . ' Credits' : $user->repost_price . ' Credit' }}</span>
                                </div>
                            </div>

                        </div>
                        <div
                            class=" flex items-center space-x-4 cursor-pointer  border border-gray-200 dark:border-gray-700  bg-gray-50 dark:bg-slate-700 rounded-md  p-2">
                            <div class="flex-shrink-0">
                                <img class="h-18 w-18 rounded-xl object-cover shadow-md"
                                    src="{{ storage_url($music->artwork_url) }}" alt="{{ $music->title }}" />
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm text-gray-500 dark:text-gray-400 truncate">
                                    <span class="ml-2 text-xs text-gray-400">{{ $music->genre ?? 'No Genre' }}.</span>
                                    <strong class="text-orange-600 dark:text-orange-400">{{ $music->title }}</strong>
                                </p>
                                <span
                                    class="inline-block bg-gray-100 dark:bg-slate-600 text-xs px-3 py-1 rounded-full text-gray-700 dark:text-gray-300 mt-2 font-mono">{{ $music->isrc }}</span>
                            </div>
                            <div class="flex-shrink-0">
                                <i data-lucide="chevron-right"
                                    class="w-5 h-5 text-gray-400 group-hover:text-orange-500 transition-colors"></i>
                            </div>
                        </div>
                        {{-- add personal message optional --}}
                        <div class="space-y-6 mt-4">
                            <div>
                                <label for="description"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Add personal message (optional)
                                </label>
                                <div class="relative mt-1">
                                    <textarea id="description" name="description" rows="4" wire:model.live="description"
                                        class="block w-full rounded-md dark:bg-gray-800 border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:text-gray-200"
                                        placeholder="Say hi and introduce your track"></textarea>
                                    <div class="pointer-events-none absolute bottom-2 right-2 text-xs text-gray-400">
                                        {{ strlen($description) }} / 200
                                    </div>
                                </div>
                                @error('description')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-500">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div class="relative flex items-start group">
                                <div class="flex h-5 items-center">
                                    @if (!$alreadyFollowing)
                                        <input id="following" name="following" type="checkbox"
                                            wire:model="following"
                                            class="h-4 w-4 rounded border-gray-300 text-orange-600 focus:ring-red-500">
                                    @endif
                                </div>
                                <div class="ml-3 text-sm">
                                    <label class="text-sm text-gray-800 dark:text-gray-200">
                                        {{ $alreadyFollowing ? 'You are already following' : 'Follow' }}
                                        <span class="font-semibold text-orange-500">{{ $user->name }}</span></label>
                                </div>
                            </div>

                            <div
                                class="rounded-md border border-gray-200 bg-gray-50 px-4 py-3 dark:border-gray-700 dark:bg-gray-800">
                                <div
                                    class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                    COMMENT PLUS
                                </div>
                                <div class="mt-2 flex items-center justify-between">
                                    <div class="flex items-center">
                                        <input id="commentable" name="commentable" type="checkbox"
                                            wire:model.live="commentable"
                                            class="h-4 w-4 rounded border-gray-300 text-orange-600 focus:ring-red-500" />
                                        <label for="commentable"
                                            class="ml-3 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                            Offer credits to incentivise comment
                                        </label>
                                    </div>
                                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Cost 2
                                        credits</span>
                                </div>
                            </div>

                            <div
                                class="rounded-md border border-gray-200 bg-gray-50 px-4 py-3 dark:border-gray-700 dark:bg-gray-800">
                                <div class="flex items-center justify-between">
                                    <div
                                        class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                        LIKE PLUS <span
                                            class="text-xs font-normal text-gray-400 dark:text-gray-500">BETA</span>
                                    </div>
                                </div>
                                <div class="mt-2 flex items-center justify-between">
                                    <div class="flex items-center">
                                        <input id="likeable" name="likeable" type="checkbox"
                                            wire:model.live="likeable"
                                            class="h-4 w-4 rounded border-gray-300 text-red-600 focus:ring-red-500" />
                                        <label for="likeable"
                                            class="ml-3 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                            Offer credits to incentivise likes
                                        </label>
                                    </div>
                                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Cost 2
                                        credits</span>
                                </div>
                            </div>
                            {{-- @error('credits') --}}
                            <div class="text-sm text-red-600 mt-2">
                                {{ $errors->first('credits') }}
                            </div>
                            {{-- @enderror --}}
                        </div>
                        <!-- Confirm Button -->
                        <div class="mt-6 flex justify-center gap-3">
                            <x-gbutton variant="secondary" wire:click="closeRepostModal">Cancel</x-gbutton>
                            @if (($blockMismatchGenre && $userMismatchGenre) || !$blockMismatchGenre)
                                <x-gbutton :disabled="$errors->first('credits')" variant="primary" wire:click="createRepostsRequest">
                                    <span>
                                        <svg class="w-6 h-6 text-white" width="26" height="18"
                                            viewBox="0 0 26 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <rect x="1" y="1" width="24" height="16" rx="3"
                                                fill="none" stroke="currentColor" stroke-width="2" />
                                            <circle cx="8" cy="9" r="3" fill="none"
                                                stroke="currentColor" stroke-width="2" />
                                        </svg>
                                    </span>
                                    <span {{-- class="px-2">{{ repostPrice($user) + ($likeable ? 2 : 0) + ($commentable ? 2 : 0) }}</span> --}}
                                        class="px-2">{{ repostPrice($user->repost_price, $commentable, $likeable) }}</span>
                                    Send Request</x-gbutton>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
