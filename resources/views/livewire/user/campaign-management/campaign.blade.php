<div wire:poll.1s="updatePlayingTimes">
    <x-slot name="page_slug">campaign-feed</x-slot>

    <!-- Header Section -->
    <div class="w-full mt-6 relative">
        <!-- Header Tabs & Button -->
        <div
            class="flex flex-col sm:flex-row items-center justify-between px-2 sm:px-4 pt-3 border-b border-b-gray-200 dark:border-b-gray-700  gap-2 sm:gap-0">
            <div class="">
                <div class="">
                    <nav class="-mb-px flex space-x-8">
                        <button
                            class="tab-button @if ($activeMainTab === 'recommended_pro') active border-b-2 border-orange-500 text-orange-600 @else border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 @endif py-3 pb-1 px-2 text-md font-semibold transition-all duration-200"
                            wire:click="setActiveMainTab('recommended_pro')">
                            {{ __('Recommended Pro') }} <span
                                class="text-xs ml-2 text-orange-500">{{ $totalRecommendedPro }}</span>
                        </button>
                        <button
                            class="tab-button @if ($activeMainTab === 'recommended') active border-b-2 border-orange-500 text-orange-600 @else border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 @endif py-3 pb-1 px-2 text-md font-semibold transition-all duration-200"
                            wire:click="setActiveMainTab('recommended')">
                            {{ __('Recommended') }}<span
                                class="text-xs ml-2 text-orange-500">{{ $totalRecommended }}</span>
                        </button>
                        <button
                            class="tab-button @if ($activeMainTab === 'all') active border-b-2 border-orange-500 text-orange-600 @else border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 @endif py-3 pb-1 px-2 text-md font-semibold transition-all duration-200"
                            wire:click="setActiveMainTab('all')">
                            {{ __('All') }}<span class="text-xs ml-2 text-orange-500">{{ $totalCampaign }}</span>
                        </button>
                    </nav>
                </div>
            </div>

            <x-gbutton variant="primary" wire:click="toggleCampaignsModal" class="mb-2">
                <span><x-lucide-plus class="w-5 h-5 mr-1" /></span>
                {{ __('Start a new campaign') }}</x-gbutton>
        </div>
    </div>

    <div x-data ="{ openFilterByTrack: false, openFilterByGenre: false }"
        class="flex items-center justify-start gap-4 mt-4 mb-2 relative">
        @if ($activeMainTab !== 'all')
            <div class="relative">
                <button @click="openFilterByTrack = !openFilterByTrack , openFilterByGenre = false"
                    wire:click="getAllTrackTypes" @click.outside="openFilterByTrack = false"
                    class="bg-orange-100 !hover:bg-orange-400 text-orange-600 px-4 py-2 rounded-md flex items-center gap-2 text-sm font-medium transition-colors cursor-pointer">
                    Filter by track type /all
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-chevron-down-icon lucide-chevron-down">
                        <path d="m6 9 6 6 6-6" />
                    </svg>
                </button>

                @if (!empty($selectedTrackTypes) && $activeMainTab !== 'all')
                    <div x-show="openFilterByTrack" x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="transform opacity-0 scale-95"
                        x-transition:enter-end="transform opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="transform opacity-100 scale-100"
                        x-transition:leave-end="transform opacity-0 scale-95"
                        class="absolute right-0 mt-2 w-56 rounded-md shadow-lg z-100">
                        <div class="rounded-md shadow-xs bg-white dark:bg-slate-800 ">
                            <div class="py-1">
                                <button wire:click="filterByTrackType('all')"
                                    class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 border-b border-gray-100 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-700 w-full text-left">
                                    All
                                </button>
                                <button wire:click="filterByTrackType('{{ Track::class }}')"
                                    class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 border-b border-gray-100 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-700 w-full text-left">
                                    Tacks
                                </button>
                                <button wire:click="filterByTrackType('{{ Playlist::class }}')"
                                    class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 w-full text-left">
                                    Playlists
                                </button>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Filter by genre dropdown -->
            <div class="relative">
                <button @click="openFilterByGenre = !openFilterByGenre, openFilterByTrack = false"
                    class="bg-orange-100 hover:bg-orange-300 text-orange-600 px-4 py-2 rounded-md flex items-center gap-2 text-sm font-medium transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-music-icon lucide-music">
                        <path d="M9 18V5l12-2v13" />
                        <circle cx="6" cy="18" r="3" />
                        <circle cx="18" cy="16" r="3" />
                    </svg>
                    Filter by genre / {{ count($selectedGenres) }}
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-chevron-down-icon lucide-chevron-down">
                        <path d="m6 9 6 6 6-6" />
                    </svg>
                </button>
                @if (!empty(AllGenres()))
                    <div x-show="openFilterByGenre" x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="transform opacity-0 scale-95"
                        x-transition:enter-end="transform opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="transform opacity-100 scale-100"
                        x-transition:leave-end="transform opacity-0 scale-95"
                        class="absolute left-0 mt-2 w-96 rounded-md shadow-lg z-100">
                        <div
                            class="rounded-md shadow-xs bg-white dark:bg-slate-800 "@click.outside="openFilterByGenre = false">
                            <div class="flex flex-wrap gap-2 p-2">
                                {{-- @foreach ($genres as $genre)
                                    <span wire:click="filterByGenre('{{ $genre }}')"
                                        class="px-3 py-2 text-sm text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-md cursor-pointer">
                                        {{ $genre }}
                                    </span>
                                @endforeach --}}
                                @foreach (AllGenres() as $genre)
                                    <span wire:click="toggleGenre('{{ $genre }}')"
                                        class="px-3 py-2 text-sm rounded-md cursor-pointer
                                            {{ in_array($genre, $selectedGenres) ? 'bg-orange-500 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300' }}">
                                        {{ $genre }}
                                    </span>
                                @endforeach

                            </div>
                        </div>
                    </div>
                @endif
            </div>
        @endif
        {{-- Search --}}
        <div x-data="{ showInput: false }"
            class="w-64 relative flex items-center text-gray-600 dark:text-gray-400 border border-gray-300 dark:border-gray-600 rounded">
            <svg class="w-4 h-4 absolute left-2 top-1/2 -translate-y-1/2 text-gray-500 dark:text-slate-300 pointer-events-none"
                fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M21 21l-4.35-4.35M17 10a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>

            <div x-show="!showInput" @click="showInput = true" wire:click ="getAllTags"
                class="pl-7 pr-2 py-2 cursor-pointer whitespace-nowrap dark:text-slate-300">
                <span>{{ $search ? $search : 'Type to search tags...' }}</span>
            </div>

            <div x-show="showInput" x-cloak>
                <input type="text" wire:model.debounce.300ms="search" wire:focus="$set('showSuggestions', true)"
                    wire:blur="hideSuggestions" placeholder="{{ $search ? $search : 'Type to search tags...' }}"
                    class="w-64 border py-2 border-red-500 pl-7 dark:text-slate-300 dark:border-red-400 dark:bg-gray-800 pr-2 rounded focus:outline-none focus:ring-1 focus:ring-red-400 mr-20"
                    @click.outside="showInput = false" x-ref="searchInput" x-init="$watch('showInput', (value) => { if (value) { $nextTick(() => $refs.searchInput.focus()) } })"
                    autocomplete="off" />
                {{-- <input type="text" wire:model.debounce.300ms="search" wire:focus="$set('showSuggestions', true)"
                        wire:blur="hideSuggestions" placeholder="Type to search tags..."
                        class="flex-1 min-w-0 border-0 outline-none focus:ring-0 p-1" autocomplete="off"> --}}
            </div>
            <div x-show="showInput" x-transition:enter="transition ease-out duration-100"
                x-transition:enter-start="transform opacity-0 scale-95"
                x-transition:enter-end="transform opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-75"
                x-transition:leave-start="transform opacity-100 scale-100"
                x-transition:leave-end="transform opacity-0 scale-95"
                class="absolute left-0 mt-12 w-56 rounded-md shadow-lg z-100">
                <!-- Suggestions Dropdown -->
                @if ($showSuggestions && !empty($suggestedTags))
                    <div
                        class="w-96 flex flex-wrap gap-2 absolute left-0 top-full z-50 bg-white dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg max-h-60 overflow-y-auto py-2">
                        @foreach ($suggestedTags as $index => $tag)
                            <span wire:click="selectTag('{{ $tag }}')"
                                class="inline-flex items-center px-3 py-1 rounded-sm text-sm font-medium bg-blue-100 text-blue-800 border border-blue-200 ml-2 cursor-default">
                                {{ $tag }}
                                <button type="button"
                                    class=" text-blue-600 hover:text-blue-800 focus:outline-none cursor-pointer"
                                    onclick="event.stopPropagation(); @this.call('removeTag', {{ $index }})">
                                    {{-- <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg> --}}
                                </button>
                            </span>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>


    @forelse ($campaigns as $campaign_)
        <div class="bg-white dark:bg-gray-800 border border-gray-200 mb-4 dark:border-gray-700 shadow-sm">
            <div class="flex flex-col lg:flex-row" wire:key="featured-{{ $campaign_->id }}">
                <!-- Left Column - Track Info -->
                <div
                    class="w-full lg:w-1/2 border-b lg:border-b-0 lg:border-r border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700">
                    <div class="flex flex-col md:flex-row gap-4">
                        <!-- Track Details -->
                        <div class="flex-1 flex flex-col justify-between relative">
                            <!-- Your Original SoundCloud Player -->
                            <div id="soundcloud-player-{{ $campaign_->id }}" data-campaign-id="{{ $campaign_->id }}"
                                wire:ignore>
                                <x-sound-cloud.sound-cloud-player :track="$campaign_->music" :height="166"
                                    :visual="false" />
                            </div>
                            <div class="absolute top-2 left-2 flex items-center space-x-2">
                                @if (!featuredAgain($campaign_->id) && $campaign_->is_featured)
                                    <div
                                        class="bg-orange-500 text-white text-xs font-semibold px-2 py-0.5 rounded shadow z-10 tracking-wide">
                                        FEATURED
                                    </div>
                                @endif
                                @if (!boostAgain($campaign_->id) && $campaign_->is_boost)
                                    <div
                                        class="bg-orange-500 text-white text-xs font-semibold px-2 py-0.5 rounded shadow z-10 tracking-wide">
                                        {{ __('Boosted') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Campaign Info -->
                <div class="w-full lg:w-1/2 p-4">
                    <div class="flex flex-col h-full justify-between">
                        <!-- Avatar + Title + Icon -->
                        <div
                            class="flex flex-col sm:flex-row relative items-start sm:items-center justify-between gap-4 mb-4">
                            <div class="flex items-center gap-3">
                                <img class="w-14 h-14 rounded-full object-cover"
                                    src="{{ auth_storage_url($campaign_?->music?->user?->avatar) }}"
                                    alt="Audio Cure avatar">
                                <div x-data="{ open: false }" class="inline-block text-left">
                                    <div @click="open = !open" class="flex items-center gap-1 cursor-pointer">
                                        <span
                                            class="text-slate-700 dark:text-gray-300 font-medium">{{ $campaign_?->music?->user?->name }}</span>
                                        <svg class="w-4 h-4 text-gray-600 dark:text-gray-400" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </div>
                                    <svg class="w-5 h-5 text-gray-400 dark:text-gray-500 mt-1" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.783-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z">
                                        </path>
                                    </svg>

                                    <div x-show="open" x-transition.opacity
                                        class="absolute left-0 mt-2 w-56 z-50 shadow-lg bg-gray-900 text-white text-sm p-2 space-y-2"
                                        x-cloak>
                                        <a href="{{ $campaign_?->music?->user?->userInfo?->soundcloud_permalink_url }}"
                                            target="_blank" class="block hover:bg-gray-800 px-3 py-1 rounded">Visit
                                            SoundCloud
                                            Profile</a>
                                        <a href="{{ route('user.my-account', $campaign_->user_urn) }}" wire:navigate
                                            class="block hover:bg-gray-800 px-3 py-1 rounded">Visit
                                            RepostChain Profile</a>
                                        {{-- <button
                                                class="block w-full text-left hover:bg-gray-800 px-3 py-1 rounded">Hide
                                                all content from this
                                                member</button>
                                            <button
                                                class="block w-full text-left hover:bg-gray-800 px-3 py-1 rounded">Hide
                                                this track</button> --}}
                                    </div>
                                </div>
                            </div>
                            <!-- Stats and Repost Button -->
                            <div class="flex items-center gap-4 sm:gap-8">
                                <div
                                    class="flex flex-col items-center sm:items-start text-gray-600 dark:text-gray-400">
                                    <div class="flex items-center gap-1.5">
                                        <svg width="26" height="18" viewBox="0 0 26 18" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <rect x="1" y="1" width="24" height="16" rx="3"
                                                fill="none" stroke="currentColor" stroke-width="2" />
                                            <circle cx="8" cy="9" r="3" fill="none"
                                                stroke="currentColor" stroke-width="2" />
                                        </svg>
                                        <span
                                            class="text-sm sm:text-base">{{ $campaign_->budget_credits - $campaign_->credits_spent }}</span>
                                    </div>
                                    <span class="text-xs text-gray-500 dark:text-gray-500 mt-1">REMAINING</span>
                                </div>
                                <div class="relative">
                                    <!-- Repost Button -->
                                    <button wire:click="confirmRepost('{{ $campaign_->id }}')"
                                        @class([
                                            'flex items-center gap-2 py-2 px-4 sm:px-5 sm:pl-8 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 rounded-lg shadow-sm text-sm sm:text-base transition-colors',
                                            'bg-orange-600 dark:bg-orange-500 hover:bg-orange-700 dark:hover:bg-orange-400 text-white dark:text-gray-300 cursor-pointer' => $this->canRepost(
                                                $campaign_->id),
                                            'bg-gray-300 dark:bg-gray-600 text-gray-500 dark:text-gray-400 cursor-not-allowed' => !$this->canRepost(
                                                $campaign_->id),
                                        ]) @disabled(!$this->canRepost($campaign_->id))>
                                        <svg width="26" height="18" viewBox="0 0 26 18" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <rect x="1" y="1" width="24" height="16" rx="3"
                                                fill="none" stroke="currentColor" stroke-width="2" />
                                            <circle cx="8" cy="9" r="3" fill="none"
                                                stroke="currentColor" stroke-width="2" />
                                        </svg>
                                        <span>{{ repostPrice() }}
                                            Repost</span>
                                    </button>
                                    @if (in_array($campaign_->id, $this->repostedCampaigns))
                                        <div
                                            class="absolute -top-8 left-1/2 transform -translate-x-1/2 bg-green-600 text-white text-xs px-2 py-1 rounded shadow-lg whitespace-nowrap">
                                            Reposted! ✓
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Genre Badge -->
                        <div class="mt-auto">
                            <span
                                class="inline-block bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm font-medium px-3 py-1.5 rounded-md shadow-sm">
                                {{ !empty($campaign_->music?->genre) ? $campaign_->music?->genre : 'Unknown Genre' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="text-center text-gray-500 dark:text-gray-400 mt-6">
            <p>No campaigns available at the moment.</p>
        </div>
    @endforelse

    @if (isset($campaigns) && method_exists($campaigns, 'hasPages') && $campaigns->hasPages())
        <div class="mt-6">
            {{ $campaigns->links('components.pagination.wire-navigate', [
                'pageName' => $activeMainTab . 'Page',
                'keep' => ['tab' => $activeMainTab],
            ]) }}
        </div>
    @endif

    {{-- ================================ Modals ================================ --}}

    {{-- Choose a track or playlist Modal --}}
    <div x-data="{ showCampaignsModal: @entangle('showCampaignsModal').live }" x-show="showCampaignsModal" x-cloak
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
        class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4 z-50">

        <div
            class="w-full max-w-3xl mx-auto rounded-2xl shadow-2xl bg-white dark:bg-slate-800 border border-gray-200 dark:border-gray-700 flex flex-col max-h-[80vh] overflow-hidden">
            <div
                class="flex justify-between items-center p-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-orange-50 to-orange-100 dark:from-orange-900/20 dark:to-orange-800/20">
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
                <button x-on:click="showCampaignsModal = false"
                    class="cursor-pointer w-10 h-10 rounded-xl bg-white dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-all duration-200 flex items-center justify-center border border-gray-200 dark:border-gray-600">
                    <x-lucide-x class="w-5 h-5" />
                </button>
            </div>

            @if ($showCampaignsModal)
                <div class="flex border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                    <button wire:click="selectModalTab('tracks')"
                        class="cursor-pointer flex-1 py-4 px-6 text-center font-semibold text-base transition-all duration-300 ease-in-out border-b-2 hover:bg-white dark:hover:bg-gray-700 {{ $activeTab === 'tracks' ? 'border-orange-500 text-orange-600 bg-white dark:bg-gray-700' : 'border-transparent text-gray-600 dark:text-gray-400' }}">
                        <div class="flex items-center justify-center gap-2">
                            <x-lucide-music class="w-4 h-4" />
                            {{ __('Tracks') }}
                        </div>
                    </button>
                    <button wire:click="selectModalTab('playlists')"
                        class="cursor-pointer flex-1 py-4 px-6 text-center font-semibold text-base transition-all duration-300 ease-in-out border-b-2 hover:bg-white dark:hover:bg-gray-700 {{ $activeTab === 'playlists' ? 'border-orange-500 text-orange-600 bg-white dark:bg-gray-700' : 'border-transparent text-gray-600 dark:text-gray-400' }}">
                        <div class="flex items-center justify-center gap-2">
                            <x-lucide-list-music class="w-4 h-4" />
                            {{ __('Playlists') }}
                        </div>
                    </button>
                </div>

                <div class="flex-grow overflow-y-auto p-4">
                    <div class="p-1">
                        <label for="track-link-search" class="text-xl font-semibold text-gray-700 dark:text-gray-200">
                            @if ($activeTab === 'tracks')
                                Paste a SoundCloud track link
                            @else
                                Paste a SoundCloud playlist link
                            @endif
                        </label>
                        <div class="flex w-full mt-2">
                            <input wire:model="searchQuery" type="text" id="track-link-search"
                                placeholder="{{ $activeTab === 'tracks' ? 'Paste a SoundCloud track link' : 'Paste a SoundCloud playlist link' }}"
                                class="flex-grow p-3 text-gray-700 dark:text-gray-200 bg-white dark:bg-slate-700 placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-orange-500 transition-colors duration-200 border border-gray-300 dark:border-gray-600 ">
                            <button wire:click="searchSoundcloud" type="button"
                                class="bg-orange-500 text-white p-3 w-14 flex items-center justify-center hover:bg-orange-600 transition-colors duration-200 ">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    @if ($activeTab === 'tracks' || $playListTrackShow == true)
                        <div class="space-y-3">
                            @forelse ($tracks as $track_)
                                <div wire:click="toggleSubmitModal('track', {{ $track_->id }})"
                                    class="p-2 flex items-center space-x-4 cursor-pointer hover:bg-gray-50 dark:hover:bg-slate-700 rounded-xl transition-all duration-200 border border-transparent hover:border-orange-200 dark:hover:border-orange-800 group">
                                    <div class="flex-shrink-0">
                                        <img class="h-14 w-14 rounded-xl object-cover shadow-md"
                                            src="{{ soundcloud_image($track_->artwork_url) }}"
                                            alt="{{ $track_->title }}" />
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p
                                            class="text-base font-semibold text-gray-900 dark:text-white truncate group-hover:text-orange-600 dark:group-hover:text-orange-400 transition-colors">
                                            {{ $track_->title }}
                                        </p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 truncate">
                                            {{ __('by') }}
                                            <strong
                                                class="text-orange-600 dark:text-orange-400">{{ $track_->author_username }}</strong>
                                            <span class="ml-2 text-xs text-gray-400">{{ $track_->genre }}</span>
                                        </p>
                                        <span
                                            class="bg-gray-100 dark:bg-slate-600 text-xs px-3 py-1 rounded-full text-gray-700 dark:text-gray-300 mt-2 font-mono flex items-start justify-center w-fit gap-3">
                                            <x-lucide-audio-lines class="w-4 h-4" />
                                            {{ $track_->playback_count }}</span>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <x-lucide-chevron-right
                                            class="w-5 h-5 text-gray-400 group-hover:text-orange-500 transition-colors" />
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

                            {{-- Load More Button for Tracks --}}
                            @if ($hasMoreTracks)
                                <div class="text-center mt-4">
                                    <button wire:click="loadMoreTracks" wire:loading.attr="disabled"
                                        class="bg-orange-500 text-white font-semibold px-3 py-1.5 text-sm rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 hover:bg-orange-600 transition-colors duration-200 disabled:bg-orange-300 disabled:cursor-not-allowed">
                                        <span wire:loading.remove wire:target="loadMoreTracks">
                                            Load More
                                        </span>
                                        <span wire:loading wire:target="loadMoreTracks">
                                            Loading...
                                        </span>
                                    </button>
                                </div>
                            @endif
                        </div>
                    @elseif($activeTab === 'playlists')
                        <div class="space-y-3">
                            @forelse ($playlists as $playlist_)
                                <div wire:click="showPlaylistTracks({{ $playlist_->id }})"
                                    class="p-4 flex items-center space-x-4 cursor-pointer hover:bg-gray-50 dark:hover:bg-slate-700 rounded-xl transition-all duration-200 border border-transparent hover:border-orange-200 dark:hover:border-orange-800 group">
                                    <div class="flex-shrink-0">
                                        <img class="h-14 w-14 rounded-xl object-cover shadow-md"
                                            src="{{ soundcloud_image($playlist_->artwork_url) }}"
                                            alt="{{ $playlist_->title }}" />
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p
                                            class="text-base font-semibold text-gray-900 dark:text-white truncate group-hover:text-orange-600 dark:group-hover:text-orange-400 transition-colors">
                                            {{ $playlist_->title }}
                                        </p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 truncate">
                                            {{ $playlist_->track_count }} {{ __('tracks') }}
                                        </p>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <x-lucide-chevron-right
                                            class="w-5 h-5 text-gray-400 group-hover:text-orange-500 transition-colors" />
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

                            {{-- Load More Button for Playlists --}}
                            @if ($hasMorePlaylists)
                                <div class="text-center mt-4">
                                    <button wire:click="loadMorePlaylists" wire:loading.attr="disabled"
                                        class="bg-orange-500 text-white font-semibold px-3 py-1.5 text-sm rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 hover:bg-orange-600 transition-colors duration-200 disabled:bg-orange-300 disabled:cursor-not-allowed">
                                        <span wire:loading.remove wire:target="loadMorePlaylists">
                                            Load More
                                        </span>
                                        <span wire:loading wire:target="loadMorePlaylists">
                                            Loading...
                                        </span>
                                    </button>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>
    {{-- Create campaign (submit) Modal --}}
    @include('backend.user.includes.campaign-create-modal')
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
                    {{ __('You need a minimum of 50 credits to create a campaign.') }}
                </p>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">
                    {{ __('Please add more credits to your account to proceed with campaign creation.') }}
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
    {{-- Repost Confirmation Modal --}}
    @include('backend.user.includes.repost-confirmation-modal')
</div>
<script>
    function initializeSoundCloudWidgets() {
        if (typeof SC === 'undefined') {
            setTimeout(initializeSoundCloudWidgets, 500);
            return;
        }

        const playerContainers = document.querySelectorAll('[id^="soundcloud-player-"]');

        playerContainers.forEach(container => {
            const campaignId = container.dataset.campaignId;
            const iframe = container.querySelector('iframe');

            if (iframe && campaignId) {
                const widget = SC.Widget(iframe);

                widget.bind(SC.Widget.Events.PLAY, () => {
                    @this.call('handleAudioPlay', campaignId);
                });

                widget.bind(SC.Widget.Events.PAUSE, () => {
                    @this.call('handleAudioPause', campaignId);
                });

                widget.bind(SC.Widget.Events.FINISH, () => {
                    @this.call('handleAudioEnded', campaignId);
                });

                widget.bind(SC.Widget.Events.PLAY_PROGRESS, (data) => {
                    const currentTime = data.currentPosition / 1000;
                    @this.call('handleAudioTimeUpdate', campaignId, currentTime);
                });
            }
        });
    }
    document.addEventListener('livewire:navigated', function() {
        initializeSoundCloudWidgets();
    });
</script>
</div>
