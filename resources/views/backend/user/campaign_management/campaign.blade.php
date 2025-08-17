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
                            {{ __('Recommended Pro') }} <span class="text-xs ml-2 text-orange-500">{{ $totalRecommendedPro}}</span>
                        </button>
                        <button
                            class="tab-button @if ($activeMainTab === 'recommended') active border-b-2 border-orange-500 text-orange-600 @else border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 @endif py-3 pb-1 px-2 text-md font-semibold transition-all duration-200"
                            wire:click="setActiveMainTab('recommended')">
                            {{ __('Recommended') }}<span class="text-xs ml-2 text-orange-500">{{ $totalRecommended }}</span>
                        </button>
                        <button
                            class="tab-button @if ($activeMainTab === 'all') active border-b-2 border-orange-500 text-orange-600 @else border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 @endif py-3 pb-1 px-2 text-md font-semibold transition-all duration-200"
                            wire:click="setActiveMainTab('all')">
                            {{ __('All') }}<span class="text-xs ml-2 text-orange-500">{{ $totalCampaign }}</span>
                        </button>
                    </nav>
                </div>
            </div>

            <buuton wire:click="toggleCampaignsModal" x-on:click="showCampaignsModal = true"
                class="bg-orange-600 text-white px-3 sm:px-5 py-2 mb-2 cursor-pointer rounded hover:bg-orange-700 transition w-full sm:w-auto text-center">
                {{ __('Start a new campaign') }}
            </buuton>
        </div>
    </div>

    <div x-data ="{ openFilterByTrack: false, openFilterByGenre: false }"
        class="flex items-center justify-start gap-4 mt-4 mb-2 relative">
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

            @if (!empty($selectedTrackTypes))
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
                                class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 w-full text-left">
                                All
                            </button>
                            @forelse ($selectedTrackTypes as $type)
                                <button wire:click="filterByTrackType('{{ $type }}')"
                                    class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 w-full text-left">
                                    {{-- Type Capitalize --}}
                                    {{ ucfirst($type) }}
                                </button>
                            @empty
                                <span class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300">
                                    {{ __('No track types available') }}
                                </span>
                            @endforelse
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Filter by genre dropdown -->
        <div class="relative">
            <button @click="openFilterByGenre = !openFilterByGenre, openFilterByTrack = false" wire:click="getAllGenres"
                @click.outside="openFilterByGenre = false"
                class="bg-orange-100 hover:bg-orange-300 text-orange-600 px-4 py-2 rounded-md flex items-center gap-2 text-sm font-medium transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="lucide lucide-music-icon lucide-music">
                    <path d="M9 18V5l12-2v13" />
                    <circle cx="6" cy="18" r="3" />
                    <circle cx="18" cy="16" r="3" />
                </svg>
                Filter by genre / {{ __('2') }}
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="lucide lucide-chevron-down-icon lucide-chevron-down">
                    <path d="m6 9 6 6 6-6" />
                </svg>
            </button>
            @if (!empty($genres))
                <div x-show="openFilterByGenre" x-transition:enter="transition ease-out duration-100"
                    x-transition:enter-start="transform opacity-0 scale-95"
                    x-transition:enter-end="transform opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-75"
                    x-transition:leave-start="transform opacity-100 scale-100"
                    x-transition:leave-end="transform opacity-0 scale-95"
                    class="absolute right-0 mt-2 w-56 rounded-md shadow-lg z-100">
                    <div class="rounded-md shadow-xs bg-white dark:bg-slate-800 ">
                        <div class="py-1">
                            @foreach ($genres as $genre)
                                <button wire:click="filterByGenre('{{ $genre }}')"
                                    class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 w-full text-left">
                                    {{ $genre }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>
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

    <div class="container mx-auto px-4 py-6">
        <!-- Flash Messages -->
        @if (session()->has('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') ?? '' }}
            </div>
        @endif

        @if (session()->has('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') ?? '' }}
            </div>
        @endif

        <!-- Featured Campaign Section -->
        {{-- @if (count($campaigns) > 0) --}}

        {{-- <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Featured campaigns</h2> --}}
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
                                <div id="soundcloud-player-{{ $campaign_->id }}"
                                    data-campaign-id="{{ $campaign_->id }}" wire:ignore>
                                    <x-sound-cloud.sound-cloud-player :track="$campaign_->music" :height="166"
                                        :visual="false" />
                                </div>
                                @if ($campaign_->is_featured)
                                    <div
                                        class="absolute top-2 left-2 bg-orange-500 text-white text-xs font-semibold px-2 py-0.5 rounded shadow z-10 tracking-wide">
                                        FEATURED
                                    </div>
                                @endif
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
                                                target="_blank"
                                                class="block hover:bg-gray-800 px-3 py-1 rounded">Visit SoundCloud
                                                Profile</a>
                                            <a href="{{ route('user.profile') }}" wire:navigate
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
                                                'flex items-center gap-2 py-2 px-3 sm:px-5 sm:pl-8 rounded-md shadow-sm text-sm sm:text-base transition-colors',
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
        {{-- @endif --}}

        <!-- Recommended Campaigns Section -->
        {{-- @if (count($campaigns) > 0)
            <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200 my-4">Recommended Campaigns</h2>

            @foreach ($campaigns as $campaign)
                <div class="bg-white dark:bg-gray-800 border border-gray-200 mb-4 dark:border-gray-700 shadow-sm">
                    <div class="flex flex-col lg:flex-row" wire:key="campaign-{{ $campaign->id }}">
                        <!-- Left Column - Track Info -->
                        <div
                            class="w-full lg:w-1/2 border-b lg:border-b-0 lg:border-r border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700">
                            <div class="flex flex-col md:flex-row gap-4">
                                <!-- Track Details -->
                                <div class="flex-1 flex flex-col justify-between">
                                    <!-- Your Original SoundCloud Player -->
                                    <div id="soundcloud-player-{{ $campaign->id }}"
                                        data-campaign-id="{{ $campaign->id }}" wire:ignore>
                                        <x-sound-cloud.sound-cloud-player :track="$campaign->music" :height="166"
                                            :visual="false" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column - Campaign Info -->
                        <div class="w-full lg:w-1/2 p-4">
                            <div class="flex flex-col h-full justify-between">
                                <!-- Avatar + Title + Icon -->
                                <div
                                    class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-4 relative">
                                    <div class="flex items-center gap-3">
                                        <img class="w-14 h-14 rounded-full object-cover"
                                            src="{{ auth_storage_url($campaign?->music?->user?->avatar) }}"
                                            alt="Audio Cure avatar">
                                        <div x-data="{ open: false }" class="inline-block text-left">
                                            <div @click="open = !open" class="flex items-center gap-1 cursor-pointer">
                                                <span
                                                    class="text-slate-700 dark:text-gray-300 font-medium">{{ $campaign?->music?->user?->name }}</span>
                                                <svg class="w-4 h-4 text-gray-600 dark:text-gray-400" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                </svg>
                                            </div>
                                            <svg class="w-5 h-5 text-gray-400 dark:text-gray-500 mt-1" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="1.5"
                                                    d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.783-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z">
                                                </path>
                                            </svg>

                                            <div x-show="open" x-transition.opacity
                                                class="absolute left-0 mt-2 w-56 z-50 shadow-lg bg-gray-900 text-white text-sm p-2 space-y-2"
                                                x-cloak>
                                                <a href="{{ $campaign?->music?->user?->userInfo?->soundcloud_permalink_url }}"
                                                    target="_blank"
                                                    class="block hover:bg-gray-800 px-3 py-1 rounded">Visit SoundCloud
                                                    Profile</a>
                                                <a href="{{ route('user.profile') }}" wire:navigate
                                                    class="block hover:bg-gray-800 px-3 py-1 rounded">Visit
                                                    RepostChain Profile</a>

                                            </div>
                                        </div>
                                    </div>

                                    <!-- Stats and Repost Button -->
                                    <div class="flex items-center gap-4 sm:gap-8">
                                        <div
                                            class="flex flex-col items-center sm:items-start text-gray-600 dark:text-gray-400">
                                            <div class="flex items-center gap-1.5">
                                                <svg width="26" height="18" viewBox="0 0 26 18"
                                                    fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <rect x="1" y="1" width="24" height="16" rx="3"
                                                        fill="none" stroke="currentColor" stroke-width="2" />
                                                    <circle cx="8" cy="9" r="3" fill="none"
                                                        stroke="currentColor" stroke-width="2" />
                                                </svg>
                                                <span
                                                    class="text-sm sm:text-base">{{ $campaign->budget_credits - $campaign->credits_spent }}</span>
                                            </div>
                                            <span
                                                class="text-xs text-gray-500 dark:text-gray-500 mt-1">REMAINING</span>
                                        </div>

                                        <div class="relative">
                                            <!-- Repost Button -->
                                            <button wire:click="repost('{{ $campaign->id }}')"
                                                @class([
                                                    'flex items-center gap-2 py-2 px-3 sm:px-5 sm:pl-8 rounded-md shadow-sm text-sm sm:text-base transition-colors',
                                                    'bg-orange-600 dark:bg-orange-500 hover:bg-orange-700 dark:hover:bg-orange-400 text-white dark:text-gray-300 cursor-pointer' => $this->canRepost(
                                                        $campaign->id),
                                                    'bg-gray-300 dark:bg-gray-600 text-gray-500 dark:text-gray-400 cursor-not-allowed' => !$this->canRepost(
                                                        $campaign->id),
                                                ]) @disabled(!$this->canRepost($campaign->id))>
                                                <svg width="26" height="18" viewBox="0 0 26 18"
                                                    fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <rect x="1" y="1" width="24" height="16" rx="3"
                                                        fill="none" stroke="currentColor" stroke-width="2" />
                                                    <circle cx="8" cy="9" r="3" fill="none"
                                                        stroke="currentColor" stroke-width="2" />
                                                </svg>
                                                <span>
                                                    {{ repostPrice() }}
                                                    Repost</span>
                                            </button>


                                        </div>
                                    </div>
                                </div>

                                <!-- Genre Badge -->
                                <div class="mt-auto">
                                    <span
                                        class="inline-block bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm font-medium px-3 py-1.5 rounded-md shadow-sm">
                                        {{ !empty($campaign->music?->genre) ? $campaign->music?->genre : 'Unknown Genre' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif --}}

        {{-- @if ($campaigns->isEmpty())
            <div class="text-center text-gray-500 dark:text-gray-400 mt-6">
                <p>No campaigns available at the moment.</p>
            </div>
        @endif --}}
        @if (isset($campaigns) && method_exists($campaigns, 'hasPages') && $campaigns->hasPages())
            <div class="mt-6">
                {{ $campaigns->links('components.pagination.wire-navigate', [
                    'pageName' => $activeMainTab . 'Page',
                    'keep' => ['tab' => $activeMainTab],
                ]) }}
            </div>
        @endif
    </div>
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
                    <div class="w-7 h-7 md:w-8 md:h-8 bg-orange-500 rounded-lg flex items-center justify-center">
                        <span class="text-slate-800 dark:text-white font-bold text-md md:text-lg">R</span>
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
                                        class="bg-orange-500 text-white font-semibold py-2 px-4 rounded-lg hover:bg-orange-600 transition-colors duration-200 disabled:bg-orange-300 disabled:cursor-not-allowed">
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
                                        class="bg-orange-500 text-white font-semibold py-2 px-4 rounded-lg hover:bg-orange-600 transition-colors duration-200 disabled:bg-orange-300 disabled:cursor-not-allowed">
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
    <div x-data="{ showSubmitModal: @entangle('showSubmitModal').live }" x-show="showSubmitModal" x-cloak
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
        class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4 z-50">

        <div
            class="w-full max-w-4xl mx-auto rounded-2xl shadow-2xl bg-white dark:bg-slate-800 border border-gray-200 dark:border-gray-700 flex flex-col max-h-[85vh] overflow-hidden">
            <div
                class="flex justify-between items-center p-6 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-orange-50 to-orange-100 dark:from-orange-900/20 dark:to-orange-800/20">
                <div class="flex items-center gap-3">
                    <div class="w-7 h-7 md:w-8 md:h-8 bg-orange-500 rounded-lg flex items-center justify-center">
                        <span class="text-slate-800 dark:text-white font-bold text-md md:text-lg">R</span>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ __('Create a campaign') }}
                    </h2>
                </div>
                <button x-on:click="showSubmitModal = false"
                    class="w-10 h-10 rounded-xl bg-white dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-all duration-200 flex items-center justify-center border border-gray-200 dark:border-gray-600">
                    <x-lucide-x class="w-5 h-5" />
                </button>
            </div>

            <div x-data="{ momentumEnabled: false }" class="flex-grow overflow-y-auto p-6">
                <!-- Selected Track -->
                @if ($track)
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="text-md font-medium text-gray-900 dark:text-white">Selected Track</h3>
                            <button x-on:click="showSubmitModal = false"
                                class="bg-gray-100 dark:bg-slate-700 py-1.5 px-3 rounded-xl text-orange-500 text-sm font-medium hover:text-orange-600">Edit</button>
                        </div>
                        <div
                            class="p-4 flex items-center space-x-4 dark:bg-slate-700 rounded-xl transition-all duration-200 border  border-orange-200 ">
                            @if ($track)
                                <img src="{{ soundcloud_image($track->artwork_url) }}" alt="Album cover"
                                    class="w-12 h-12 rounded">
                            @endif
                            <div>
                                <p class="text-sm text-gray-600 dark:text-white">{{ $track->type }} -
                                    {{ $track->author_username }}</p>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $track->title }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endif
                <form wire:submit.prevent="createCampaign" class="space-y-6">
                    <!-- Set Budget -->
                    <div class="mt-4">
                        <div class="flex items-center space-x-2 mb-2">
                            <h3 class="text-sm font-medium text-gray-900 dark:text-white">Set budget</h3>
                            <div class="w-4 h-4 bg-gray-400 rounded-full  flex items-center justify-center">
                                <span class="text-white text-xs">i</span>
                            </div>
                        </div>
                        <p class="text-xs text-gray-700 dark:text-gray-400 mb-4">A potential 10,000 people reached per
                            campaign</p>

                        <!-- Budget Display -->
                        <div class="flex items-center justify-center space-x-2 mb-4">
                            <svg class="w-8 h-8 text-orange-500" width="26" height="18" viewBox="0 0 26 18"
                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect x="1" y="1" width="24" height="16" rx="3" fill="none"
                                    stroke="currentColor" stroke-width="2" />
                                <circle cx="8" cy="9" r="3" fill="none" stroke="currentColor"
                                    stroke-width="2" />
                            </svg>
                            <span class="text-2xl font-bold text-orange-500">{{ $credit }}</span>
                        </div>
                        {{-- Error Message --}}
                        @if ($errors->has('credit'))
                            <p class="text-xs text-red-500 mb-4">
                                {{ $errors->first('credit') }}
                            </p>
                        @endif

                        <!-- Slider -->
                        <div class="relative">
                            <input type="range" x-data x-on:input="$wire.set('credit', $event.target.value)"
                                min="0" max="500" value="{{ $credit }}"
                                class="w-full h-2 border-0 cursor-pointer">
                        </div>
                    </div>

                    <!-- Enable CommentPlus -->
                    <div>
                        <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mt-4">Campaign Settings</h2>
                        <p class="text-sm text-gray-700 dark:text-gray-400 mb-4 mt-2">Select amount of credits to be
                            spent</p>
                        <div class="flex items-start space-x-3">
                            <input type="checkbox" wire:model="commentable" checked
                                class="mt-1 w-4 h-4 text-orange-500 border-gray-300 rounded focus:ring-orange-500">
                            <div>
                                <h4 class="text-sm font-medium text-gray-900 dark:text-white">Activate Feedback</h4>
                                <p class="text-xs text-gray-700 dark:text-gray-400">Encourage listeners to comment on
                                    your track (2
                                    credits
                                    per comment).</p>
                            </div>
                        </div>
                    </div>

                    <!-- Enable LikePlus -->
                    <div class="flex items-start space-x-3">
                        <input type="checkbox" wire:model="likeable" checked
                            class="mt-1 w-4 h-4 text-orange-500 border-gray-300 rounded focus:ring-orange-500">
                        <div>
                            <h4 class="text-sm font-medium text-gray-900 dark:text-white">Activate HeartPush</h4>
                            <p class="text-xs text-gray-700 dark:text-gray-400">Motivate real users to like your track
                                (2 credits per
                                like).</p>
                        </div>
                    </div>

                    <div x-data="{ showOptions: false }" class="flex flex-col space-y-2">
                        <!-- Checkbox + Label -->
                        <div class="flex items-start space-x-3">
                            <input type="checkbox" @change="showOptions = !showOptions"
                                class="mt-1 w-4 h-4 text-orange-500 border-gray-300 rounded focus:ring-orange-500">

                            <div class="flex items-center space-x-2">
                                <span class="text-sm font-medium text-gray-900 dark:text-white">Limit to users with max
                                    follower
                                    count</span>
                                <div class="w-4 h-4 bg-gray-400 rounded-full flex items-center justify-center">
                                    <span class="text-white text-xs">i</span>
                                </div>
                            </div>
                        </div>

                        <!-- Toggle Options (Hidden by default) -->
                        <div x-show="showOptions" x-transition class="p-3">
                            <div class="flex justify-between items-center gap-4">
                                <div class="w-full relative">
                                    <input type="range" x-data
                                        x-on:input="$wire.set('maxFollower', $event.target.value)" min="0"
                                        max="500" value="{{ $maxFollower }}"
                                        class="w-full h-2  cursor-pointer">
                                </div>
                                <div
                                    class="w-14 h-8 border border-gray-200 dark:border-gray-700 rounded-md flex items-center justify-center">
                                    <span>{{ $maxFollower }}</span>
                                </div>
                                @error('maxFollower')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Enable Campaign Accelerator -->
                    <div class="flex items-start space-x-3 {{ $user?->status != App\Models\User::STATUS_ACTIVE ? 'opacity-30' : '' }}">
                        <input type="checkbox" wire:click="profeature( {{ $proFeatureValue }} )"
                            x-model="momentumEnabled" {{ $user?->status != App\Models\User::STATUS_ACTIVE ? 'disabled' : '' }}
                            class="mt-1 w-4 h-4 text-orange-500 border-gray-300 rounded focus:ring-orange-500 {{ $user?->status != App\Models\User::STATUS_ACTIVE ? 'cursor-not-allowed' : 'cursor-pointer' }}">
                        <div>
                            <div class="flex items-center space-x-2">
                                <h4 class="text-sm font-medium text-dark dark:text-white">
                                    {{ __('Turn on Momentum+ (') }}
                                    <span class="text-md font-semibold">PRO</span>{{ __(')') }}
                                </h4>
                                <div
                                    class="w-4 h-4 text-gray-700 dark:text-gray-400 rounded-full flex items-center justify-center">
                                    <span class="text-white text-xs">i</span>
                                </div>
                            </div>
                            <p class="text-xs text-gray-700 dark:text-gray-400">Use Campaign Accelerator (+50 credits)
                            </p>
                        </div>
                    </div>


                    <!-- Campaign Targeting -->
                    <div class="border border-gray-200 dark:border-gray-700 bg-gray-200 dark:bg-gray-900 rounded-lg p-4"
                        :class="momentumEnabled ? 'opacity-100' : 'opacity-30 border-opacity-10'">
                        <div class=" mb-4">
                            <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-200">
                                {{ __('Audience Filtering (PRO Feature)') }}</h4>
                            <p class="text-sm  text-gray-700 dark:text-gray-400 mb-4 mt-2">Fine-tune who can support
                                your track:</p>
                        </div>

                        <div class="space-y-3 ml-4">
                            <div x-data="{ showOptions: false }" class="flex flex-col space-y-2">
                                <div class="flex items-start space-x-3">
                                    <input type="checkbox" @change="showOptions = !showOptions"
                                        :disabled="!momentumEnabled"
                                        class="mt-1 w-4 h-4 text-orange-500 border-gray-300 rounded focus:ring-orange-500"
                                        :class="momentumEnabled ? 'cursor-pointer' : 'cursor-not-allowed'">
                                    <div class="flex items-center space-x-2">
                                        <span class="text-sm text-gray-700 dark:text-gray-400">Exclude users who repost
                                            too often (last
                                            24h)</span>
                                    </div>
                                </div>
                                <div x-show="showOptions" x-transition class="p-3">
                                    <div class="flex justify-between items-center gap-4">
                                        <div class="w-full relative">
                                            <input type="range" x-data :disabled="!momentumEnabled"
                                                x-on:input="$wire.set('maxRepostLast24h', $event.target.value)"
                                                min="0" max="50" value="{{ $maxRepostLast24h }}"
                                                class="w-full h-2  cursor-pointer"
                                                :class="momentumEnabled ? 'cursor-pointer' : 'cursor-not-allowed'">
                                        </div>
                                        <div
                                            class="w-14 h-8 border border-gray-200 dark:border-gray-700 rounded-md flex items-center justify-center">
                                            <span>{{ $maxRepostLast24h }}</span>
                                        </div>
                                        @error('maxRepostLast24h')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div x-data="{ showRepostPerDay: false }" class="flex flex-col space-y-2">
                                <div class="flex items-start space-x-3">
                                    <input type="checkbox" @click="showRepostPerDay = !showRepostPerDay"
                                        :disabled="!momentumEnabled"
                                        class="mt-1 w-4 h-4 text-orange-500 border-gray-300 rounded focus:ring-orange-500"
                                        :class="momentumEnabled ? 'cursor-pointer' : 'cursor-not-allowed'">
                                    <div class="flex items-center space-x-2">
                                        <span class="text-sm text-gray-700 dark:text-gray-400">Limit average repost
                                            frequency per
                                            day</span>
                                    </div>
                                </div>
                                <div x-show="showRepostPerDay" x-transition class="p-3">
                                    <div class="flex justify-between items-center gap-4">
                                        <div class="w-full relative">
                                            <input type="range" x-data :disabled="!momentumEnabled"
                                                x-on:input="$wire.set('maxRepostsPerDay', $event.target.value)"
                                                min="0" max="100" value="{{ $maxRepostsPerDay }}"
                                                class="w-full h-2  cursor-pointer"
                                                :class="momentumEnabled ? 'cursor-pointer' : 'cursor-not-allowed'">
                                        </div>
                                        <div
                                            class="w-14 h-8 border border-gray-200 dark:border-gray-700 rounded-md flex items-center justify-center">
                                            <span>{{ $maxRepostsPerDay }}</span>
                                        </div>
                                        @error('maxRepostsPerDay')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="border border-gray-200 dark:border-gray-700 bg-gray-200 dark:bg-gray-900 rounded-lg p-4">
                        <!-- Genre Selection -->
                        <div class="">
                            <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Genre Preferences for
                                Sharers</h2>
                            <p class="text-sm text-gray-700 dark:text-gray-400 mb-3 mt-2">Reposters must have the
                                following genres:</p>
                            <div class="space-y-2 ml-4">
                                <div class="flex items-center space-x-2">
                                    <input type="radio" name="genre" value="anyGenre"
                                        @click="showGenreRadios = false" wire:model="anyGenre"
                                        class="w-4 h-4 text-orange-500 border-gray-300 focus:ring-orange-500">
                                    <span class="text-sm text-gray-700 dark:text-gray-400">Open to all music
                                        types</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <input type="radio" name="genre" value="trackGenre"
                                        @click="showGenreRadios = false" wire:model="trackGenre"
                                        class="w-4 h-4 text-orange-500 border-gray-300 focus:ring-orange-500">
                                    <span class="text-sm text-gray-700 dark:text-gray-400">Match track genre – Hip-hop
                                        & Rap</span>
                                </div>
                                <div x-data="{ showGenreRadios: false }" class="space-y-3">

                                    <!-- Toggle Checkbox -->
                                    <div class="flex items-center space-x-2">
                                        <input type="radio" name="genre"
                                            @click="showGenreRadios = !showGenreRadios" wire:click="getAllGenres"
                                            class="w-4 h-4 text-orange-500 border-gray-300 focus:ring-orange-500">
                                        <span class="text-sm text-gray-700 dark:text-gray-400">Match one of your
                                            profile’s chosen
                                            genres</span>
                                    </div>

                                    <!-- Radio Options (Toggle area) -->
                                    <div x-show="showGenreRadios" x-transition class="ml-6 space-y-2">
                                        @forelse ($genres as $genre)
                                            <div class="flex items-center space-x-2">
                                                <input type="radio" name="genre" wire:model="targetGenre"
                                                    value="{{ $genre }}"
                                                    class="w-4 h-4 text-orange-500 border-gray-300 focus:ring-orange-500">
                                                <span
                                                    class="text-sm text-gray-700 dark:text-gray-400">{{ $genre }}</span>
                                            </div>
                                        @empty
                                            <div class="">
                                                <span class="text-sm text-gray-700 dark:text-gray-400">No genres
                                                    found</span>
                                            </div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- submit button here --}}
                    <div class="pt-4">
                        <button type="submit"
                            class="w-full transition-all duration-300 flex items-center justify-center gap-3 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 font-bold py-4 px-6 rounded-xl {{ !$canSubmit ? 'bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white' : 'bg-gray-300 dark:bg-gray-600 text-gray-500 dark:text-gray-400 cursor-not-allowed' }}">
                            <span>
                                <svg class="w-8 h-8 text-white" width="26" height="18" viewBox="0 0 26 18"
                                    fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <rect x="1" y="1" width="24" height="16" rx="3" fill="none"
                                        stroke="currentColor" stroke-width="2" />
                                    <circle cx="8" cy="9" r="3" fill="none" stroke="currentColor"
                                        stroke-width="2" />
                                </svg>
                            </span>

                            <span>{{ $proFeatureEnabled ? $credit * 1.5 : $credit }}</span>
                            <span wire:loading.remove wire:target="createCampaign">
                                {{ __('Create Campaign') }}
                            </span>
                            <span wire:loading wire:target="createCampaign">
                                {{ __('Creating...') }}
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
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
                    {{ __('You need a minimum of 100 credits to create a campaign.') }}
                </p>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">
                    {{ __('Please add more credits to your account to proceed with campaign creation.') }}
                </p>
                <a href="{{ route('user.add-credits') }}" wire:navigate
                    class="inline-flex items-center justify-center w-full bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white font-bold py-3 px-6 rounded-xl transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                    <x-lucide-plus class="w-5 h-5 inline mr-2" />
                    {{ __('Buy Credits Now') }}
                </a>
            </div>
        </div>
    </div>
    {{-- Repost Confirmation Modal --}}
    <div x-data="{ showRepostConfirmationModal: @entangle('showRepostConfirmationModal').live }" x-show="showRepostConfirmationModal" x-cloak
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
        class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4 z-50">
        @if ($campaign)
            <div
                class="w-full max-w-md mx-auto rounded-2xl shadow-2xl bg-white dark:bg-slate-800 border border-gray-200 dark:border-gray-700 flex flex-col overflow-hidden">
                <div
                    class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-red-50 to-red-100 dark:from-red-900/20 dark:to-red-800/20">
                    <div class="flex items-center gap-3">
                        <div class="w-7 h-7 md:w-8 md:h-8 bg-orange-500 rounded-lg flex items-center justify-center">
                            <span class="text-slate-800 dark:text-white font-bold text-md md:text-lg">R</span>
                        </div>
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white">
                            {{ __('Repost Confirmation') }}
                        </h2>
                    </div>
                    <button x-on:click="showRepostConfirmationModal = false"
                        class="cursor-pointer w-8 h-8 rounded-xl bg-white dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-all duration-200 flex items-center justify-center border border-gray-200 dark:border-gray-600">
                        <x-lucide-x class="w-5 h-5" />
                    </button>
                </div>
                {{-- Track Information --}}

                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center gap-2">
                        <img src="{{ soundcloud_image($campaign->music->artwork_url) }}" alt="Album cover"
                            class="w-12 h-12 rounded">
                        <div>
                            <p class="text-sm text-gray-600 dark:text-white">{{ $campaign->music->type }} -
                                {{ $campaign->music->author_username }}</p>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $campaign->music->title }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="p-6">
                    <div class="space-y-2 mb-2">
                        <label for="commented" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            {{ __('Comment:') }}
                            <input name="commented" id="repostDescription" wire:model.live="commented"
                                class="w-full h-16 px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-md focus:border-orange-500 focus:ring-0 transition-colors duration-200 bg-gray-50 dark:bg-slate-800 focus:bg-white dark:focus:bg-slate-800 resize-none" />
                        </label>
                    </div>
                    <div class="space-y-2 mb-4">
                        <label for="liked" class="flex items-center gap-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                            <input type="checkbox" id="liked" class="w-4 h-4 text-orange-500 border-gray-300 rounded focus:ring-orange-500" wire:model.live="liked">
                            {{ __('Activate HeartPush') }}
                        </label>
                    </div>
                    <div class="flex justify-center gap-4">
                        <button @click="showRepostConfirmationModal = false"
                            wire:click="repost('{{ $campaign->id }}')"
                            class="w-full flex items-center justify-center gap-2 bg-orange-500 hover:bg-orange-600 text-white py-2 px-4 rounded-xl transition-all duration-200">
                            <svg width="26" height="18" viewBox="0 0 26 18" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <rect x="1" y="1" width="24" height="16" rx="3" fill="none"
                                    stroke="currentColor" stroke-width="2" />
                                <circle cx="8" cy="9" r="3" fill="none" stroke="currentColor"
                                    stroke-width="2" />
                            </svg>
                            <span>{{ repostPrice()+ ($liked ? 2 : 0) + ($commented ? 2 : 0) }}</span>
                            {{ __('Repost') }}
                        </button>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize SoundCloud Widget API integration with Livewire
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

                    // Track play events and call Livewire methods
                    widget.bind(SC.Widget.Events.PLAY, () => {
                        @this.call('handleAudioPlay', campaignId);
                    });

                    widget.bind(SC.Widget.Events.PAUSE, () => {
                        @this.call('handleAudioPause', campaignId);
                    });

                    widget.bind(SC.Widget.Events.FINISH, () => {
                        @this.call('handleAudioEnded', campaignId);
                    });

                    // Track position updates
                    widget.bind(SC.Widget.Events.PLAY_PROGRESS, (data) => {
                        const currentTime = data.currentPosition / 1000;
                        @this.call('handleAudioTimeUpdate', campaignId, currentTime);
                    });
                }
            });
        }

        // Initialize widgets
        initializeSoundCloudWidgets();
    });
</script>
</div>
