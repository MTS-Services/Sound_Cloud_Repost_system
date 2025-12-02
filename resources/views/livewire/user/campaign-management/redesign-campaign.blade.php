<div x-data="trackPlaybackManager();" @clearCampaignTracking.window="clearAllTracking()" {{-- @reset-widget-initiallized.window="resetForFilterChange()"> --}}
    @reset-widget-initiallized.window="$data.init()">

    <x-slot name="page_slug">campaign-feed</x-slot>
    <x-slot name="title">Campaign Feed</x-slot>

    <section>
        {{-- Header Tab section --}}
        <div class="flex items-center justify-between border-b border-gray-200 dark:border-gray-700">
            <div class="flex gap-1 sm:space-x-8">
                @foreach (['recommendedPro' => 'Recommended Pro', 'recommended' => 'Recommended', 'all' => 'All'] as $tab => $label)
                    <a href="{{ route('user.cm.campaigns2', ['tab' => $tab]) }}" wire:navigate
                        @class([
                            'tab-button py-3 pb-1 px-2 text-md lg:text-sm xl:text-base font-semibold transition-all duration-200 border-b-2',
                            'border-orange-500 text-orange-600' => $activeMainTab === $tab,
                            'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' =>
                                $activeMainTab !== $tab,
                        ])>
                        {{ __($label) }}
                        <span
                            class="text-xs lg:text-[10px] xl:text-xs ml-2 text-orange-500">{{ $totalCounts[$tab] }}</span>
                    </a>
                @endforeach
            </div>
            <div>
                <x-gbutton variant="primary" class="mb-2"
                    @click="$dispatch('open-campaign-modal'); $wire.set('showCampaignCreator', true);">
                    <x-lucide-plus class="w-5 h-5 mr-1 lg:w-4 lg:h-4 xl:w-5 xl:h-5" />
                    <span class="text-base lg:text-sm xl:text-base">
                        {{ __('Start a new campaign') }}
                    </span>
                </x-gbutton>
            </div>
        </div>

        {{-- Filter options --}}
        <div x-data="{ openFilterByTrack: false, openFilterByGenre: false }" class="flex flex-col sm:flex-row sm:items-center gap-4 mt-4 mb-2">
            <div class="flex w-full sm:w-auto gap-2">
                {{-- Filter by track --}}
                <div class="relative flex-1 sm:flex-none">
                    <button @click="openFilterByTrack = !openFilterByTrack; openFilterByGenre = false"
                        @click.outside="openFilterByTrack = false"
                        class="w-full sm:w-auto bg-orange-100 hover:bg-orange-400 text-orange-600 px-4 py-2 rounded-md flex items-center gap-2 text-sm font-medium transition-colors">
                        Filter by track type / {{ $trackType }}
                        <x-lucide-chevron-down class="w-4 h-4" />
                    </button>

                    <div x-show="openFilterByTrack" x-transition x-cloak
                        class="absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white dark:bg-slate-800 z-50">
                        <div class="py-1">
                            @foreach (['all' => 'All', Track::class => 'Tracks', Playlist::class => 'Playlists'] as $value => $label)
                                <a wire:navigate href="{{ request()->fullUrlWithQuery(['trackType' => $value]) }}"
                                    @class([
                                        'block w-full text-left px-4 py-2 text-sm border-b border-gray-100 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-700',
                                        'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300' =>
                                            $trackType === $value,
                                        'text-gray-700 dark:text-gray-300' => $trackType !== $value,
                                    ])>
                                    {{ $label }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Filter by genre --}}
                <div class="relative flex-1 sm:flex-none">
                    <button @click="openFilterByGenre = !openFilterByGenre; openFilterByTrack = false"
                        @class([
                            'w-full sm:w-auto hover:bg-orange-300 text-orange-600 px-4 py-2 rounded-md flex items-center gap-2 text-sm font-medium transition-colors',
                            'bg-orange-100' => count($selectedGenres) > 0,
                            'bg-transparent border border-gray-300 dark:border-gray-600' =>
                                count($selectedGenres) === 0,
                        ])>
                        <x-lucide-music class="w-4 h-4" />
                        Filter by genre / {{ count(array_diff($selectedGenres, ['all'])) }}
                        <x-lucide-chevron-down class="w-4 h-4" />
                    </button>

                    <div x-show="openFilterByGenre" x-transition x-cloak @click.outside="openFilterByGenre = false"
                        class="absolute left-0 mt-2 w-96 rounded-md shadow-lg bg-white dark:bg-slate-800 z-50">
                        <div class="flex flex-wrap gap-2 p-2">
                            @foreach (AllGenres() as $genre)
                                {{-- <button wire:click="toggleGenre('{{ $genre }}')"
                                    wire:loading.class="opacity-50 cursor-wait" wire:loading.attr="disabled"
                                    wire:target="toggleGenre('{{ $genre }}')" @class([
                                        'inline-flex items-center gap-1.5 px-3 py-2 text-sm rounded-md transition-all duration-200',
                                        'bg-orange-500 text-white' => in_array($genre, $selectedGenres),
                                        'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' => !in_array(
                                            $genre,
                                            $selectedGenres),
                                    ])>
                                    <span>{{ $genre }}</span>
                                    <svg wire:loading wire:target="toggleGenre('{{ $genre }}')"
                                        class="animate-spin h-3.5 w-3.5 ml-1" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10"
                                            stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                        </path>
                                    </svg>
                                </button> --}}
                                <button wire:click="toggleGenre('{{ $genre }}')"
                                    wire:loading.class="opacity-50 cursor-wait" wire:loading.attr="disabled"
                                    wire:target="toggleGenre" x-data="{ clicked: false }"
                                    @click="if (clicked) return; clicked = true; setTimeout(() => clicked = false, 1000)"
                                    :disabled="clicked" @class([
                                        'inline-flex items-center gap-1.5 px-3 py-2 text-sm rounded-md transition-all duration-200',
                                        'bg-orange-500 text-white' => in_array($genre, $selectedGenres),
                                        'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' => !in_array(
                                            $genre,
                                            $selectedGenres),
                                    ])>
                                    <span>{{ $genre }}</span>
                                    <svg wire:loading wire:target="toggleGenre('{{ $genre }}')"
                                        class="animate-spin h-3.5 w-3.5 ml-1" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10"
                                            stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                        </path>
                                    </svg>
                                </button>
                            @endforeach
                            {{-- @foreach (AllGenres() as $genre)
                                @php
                                    // Get current URL query params
                                    $currentQuery = request()->query();

                                    // Get existing selectedGenres array or empty
                                    $selectedGenres = $currentQuery['selectedGenres'] ?? [];

                                    // Toggle genre in the array
                                    if (in_array($genre, $selectedGenres)) {
                                        // Remove genre if already selected
                                        $newSelected = array_values(array_diff($selectedGenres, [$genre]));
                                    } else {
                                        // Add genre if not selected
                                        $newSelected = array_merge($selectedGenres, [$genre]);
                                    }

                                    // Build the new URL with merged query
                                    $url =
                                        request()->url() .
                                        '?' .
                                        http_build_query(
                                            array_merge($currentQuery, ['selectedGenres' => $newSelected]),
                                        );
                                @endphp

                                <a href="{{ $url }}" wire:navigate @class([
                                    'inline-flex items-center gap-1.5 px-3 py-2 text-sm rounded-md transition-all duration-200',
                                    'bg-orange-500 text-white' => in_array($genre, $selectedGenres),
                                    'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' => !in_array(
                                        $genre,
                                        $selectedGenres),
                                ])>
                                    {{ $genre }}
                                </a>
                            @endforeach --}}
                        </div>
                    </div>
                </div>
            </div>

            {{-- Search box --}}
            <div x-data="{ showInput: false }" class="w-full flex-1 relative">
                <div
                    class="relative flex items-center text-gray-600 dark:text-gray-400 border border-gray-300 dark:border-gray-600 rounded">
                    <x-lucide-search
                        class="w-4 h-4 absolute left-2 text-gray-500 dark:text-slate-300 pointer-events-none" />

                    <div x-show="!showInput" @click="showInput = true; $wire.call('getAllTags')"
                        class="w-full pl-8 pr-2 py-2 cursor-pointer dark:text-slate-300">
                        <span>{{ $search ?: 'Type to search tags...' }}</span>
                    </div>

                    <input x-show="showInput" x-cloak type="text" wire:model.live.debounce.300ms="search"
                        placeholder="Type to search tags..." @click.outside="showInput = false" x-ref="searchInput"
                        x-init="$watch('showInput', value => value && $nextTick(() => $refs.searchInput.focus()))"
                        class="w-full py-2 pl-8 pr-2 dark:text-slate-300 dark:bg-gray-800 border-0 rounded focus:outline-none focus:ring-1 focus:ring-orange-400"
                        autocomplete="off" />

                    @if (!empty($suggestedTags))
                        <div x-show="showInput" x-cloak
                            class="absolute left-0 top-full mt-2 w-full sm:w-80 bg-white dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg max-h-60 overflow-y-auto py-2 z-50">
                            <div class="flex flex-wrap gap-2 px-2">
                                @foreach ($suggestedTags as $tag)
                                    <button wire:click="selectTag('{{ $tag }}')"
                                        class="px-3 py-1 rounded-sm text-sm font-medium bg-blue-100 text-blue-800 border border-blue-200 hover:bg-blue-200 transition-colors">
                                        {{ $tag }}
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Enhanced campaign cards section with proper wire:key -->
        <div class="flex flex-col space-y-4">
            @forelse ($campaigns as $campaign_)
                <div class="campaign-card bg-white dark:bg-gray-800 border border-gray-200 mb-4 dark:border-gray-700 shadow-sm"
                    data-campaign-id="{{ $campaign_->id }}" data-permalink="{{ $campaign_->music->permalink_url }}"
                    wire:key="campaign-{{ $campaign_->id }}-{{ $activeMainTab }}-{{ $trackType }}">

                    <div class="flex flex-col lg:flex-row">
                        <!-- Left Column - Track Info -->
                        <div
                            class="w-full lg:w-1/2 border-b lg:border-b-0 lg:border-r border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700">
                            <div class="flex flex-col md:flex-row gap-4">
                                <div class="flex-1 flex flex-col justify-between relative">
                                    <!-- Unique wire:key for each player -->
                                    <div id="soundcloud-player-{{ $campaign_->id }}"
                                        data-campaign-id="{{ $campaign_->id }}" wire:ignore
                                        wire:key="player-{{ $campaign_->id }}">
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
                                <div
                                    class="flex flex-col sm:flex-row relative items-start sm:items-center justify-between gap-4 mb-4">
                                    <div class="flex items-center gap-3 w-full">
                                        <img class="w-14 h-14 rounded-full object-cover"
                                            src="{{ auth_storage_url($campaign_?->music?->user?->avatar) }}"
                                            alt="Avatar">

                                        <div x-data="{ open: false }" class="inline-block text-left">
                                            <div @click="open = !open" class="flex items-center gap-1 cursor-pointer">
                                                <span class="text-slate-700 dark:text-gray-300 font-medium">
                                                    {{ $campaign_?->music?->user?->name }}
                                                </span>
                                                <svg class="w-4 h-4 text-gray-600 dark:text-gray-400" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                </svg>
                                            </div>

                                            <button
                                                x-on:click="Livewire.dispatch('starMarkUser', { userUrn: '{{ $campaign_?->music?->user?->urn }}' })">
                                                <x-lucide-star
                                                    class="w-5 h-5 mt-1 relative {{ $campaign_->user?->starredUsers?->contains('follower_urn', user()->urn) ? 'text-orange-300' : 'text-gray-400 dark:text-gray-500' }}"
                                                    fill="{{ $campaign_->user?->starredUsers?->contains('follower_urn', user()->urn) ? 'orange' : 'none' }}" />
                                            </button>

                                            <div x-show="open" x-transition.opacity @click.outside="open = false"
                                                class="absolute left-0 mt-2 w-56 z-50 shadow-lg bg-gray-900 text-white text-sm p-2 space-y-2"
                                                x-cloak>
                                                <a href="{{ $campaign_?->music?->user?->soundcloud_permalink_url }}"
                                                    target="_blank" class="block hover:bg-gray-800 px-3 py-1 rounded">
                                                    Visit SoundCloud Profile
                                                </a>
                                                @if ($campaign_->user)
                                                    <a href="{{ route('user.my-account.user', !empty($campaign_->user?->name) ? $campaign_->user?->name : $campaign_->user?->urn) }}"
                                                        wire:navigate
                                                        class="block hover:bg-gray-800 px-3 py-1 rounded">
                                                        Visit RepostChain Profile
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="flex items-center justify-between gap-4 w-full">
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
                                                <span class="text-sm sm:text-base">
                                                    {{ $campaign_->budget_credits - $campaign_->credits_spent }}
                                                </span>
                                            </div>
                                            <span
                                                class="text-xs text-gray-500 dark:text-gray-500 mt-1">REMAINING</span>
                                        </div>

                                        <!-- Repost Button Container -->
                                        <!-- Replace your entire repost button section with this -->
                                        <div class="relative" x-data="{
                                            showReadyTooltip: false,
                                            justBecameEligible: false,
                                            campaignId: '{{ $campaign_->id }}'
                                        }" x-init="$watch('isEligibleForRepost(campaignId)', (value, oldValue) => {
                                            if (value && !oldValue && !isReposted(campaignId)) {
                                                justBecameEligible = true;
                                                showReadyTooltip = true;
                                                setTimeout(() => {
                                                    showReadyTooltip = false;
                                                    justBecameEligible = false;
                                                }, 3000);
                                            }
                                        })">

                                            <!-- Debug Box - Remove after testing -->
                                            {{-- <div
                                                class="absolute -top-20 left-0 bg-yellow-100 p-2 text-xs rounded w-40 z-50 shadow">
                                                <div>ID: {{ $campaign_->id }}</div>
                                                <div>Time: <span x-text="getPlayTime(campaignId).toFixed(2)"></span>s
                                                </div>
                                                <div>%: <span x-text="getPlayTimePercentage(campaignId)"></span>%</div>
                                                <div>Eligible: <span
                                                        x-text="isEligibleForRepost(campaignId) ? '✅' : '❌'"></span>
                                                </div>
                                                <div>Reposted: <span
                                                        x-text="isReposted(campaignId) ? '✅' : '❌'"></span></div>
                                            </div> --}}

                                            <!-- Countdown Tooltip -->
                                            <div x-show="!isReposted(campaignId) && !isEligibleForRepost(campaignId) && getPlayTime(campaignId) > 0"
                                                x-transition
                                                class="absolute -top-10 left-1/2 transform -translate-x-1/2 bg-gray-900 text-white text-xs font-medium px-3 py-1.5 rounded-lg shadow-lg whitespace-nowrap z-20 pointer-events-none">
                                                <span
                                                    x-text="Math.max(0, Math.ceil(2 - getPlayTime(campaignId))) + 's remaining'"></span>
                                                <div
                                                    class="absolute top-full left-1/2 transform -translate-x-1/2 -mt-px">
                                                    <div class="border-4 border-transparent border-t-gray-900"></div>
                                                </div>
                                            </div>

                                            <!-- Ready Tooltip -->
                                            <div x-show="!isReposted(campaignId) && isEligibleForRepost(campaignId) && showReadyTooltip"
                                                x-transition
                                                class="absolute -top-10 left-1/2 transform -translate-x-1/2 bg-green-600 text-white text-xs font-medium px-3 py-1.5 rounded-lg shadow-lg whitespace-nowrap z-20 pointer-events-none"
                                                :class="{ 'animate-pulse': justBecameEligible }">
                                                <div class="flex items-center gap-1.5">
                                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                    <span>Ready</span>
                                                </div>
                                                <div
                                                    class="absolute top-full left-1/2 transform -translate-x-1/2 -mt-px">
                                                    <div class="border-4 border-transparent border-t-green-600"></div>
                                                </div>
                                            </div>

                                            <!-- Repost Button -->
                                            <button type="button" :data-campaign-id="campaignId"
                                                x-bind:disabled="!isEligibleForRepost(campaignId) || isReposted(campaignId)"
                                                @click="handleRepost(campaignId)"
                                                class="repost-button relative overflow-hidden flex items-center gap-2 py-2 px-4 sm:px-5 focus:outline-none focus:ring-2 focus:ring-offset-2 rounded-lg shadow-sm text-sm sm:text-base transition-all duration-200 ">
                                                {{-- :class="{
                                                    'cursor-not-allowed bg-gray-300 dark:bg-gray-600 text-white dark:text-gray-300':
                                                        !isEligibleForRepost(campaignId) && !isReposted(campaignId),
                                                    'cursor-pointer hover:shadow-lg bg-orange-400 dark:bg-orange-500 text-white hover:bg-orange-500': isEligibleForRepost(
                                                        campaignId) && !isReposted(campaignId),
                                                    'bg-green-500 text-white cursor-not-allowed': isReposted(
                                                        campaignId),
                                                    'focus:ring-orange-500': !isReposted(campaignId),
                                                    'focus:ring-green-500': isReposted(campaignId)
                                                }"> --}}

                                                {{-- Disabled background --}}
                                                <span
                                                    x-show="!isEligibleForRepost(campaignId) && !isReposted(campaignId)"
                                                    class="absolute top-0 left-0 w-full h-full cursor-not-allowed bg-gray-300 dark:bg-gray-600 text-white dark:text-gray-300"></span>

                                                {{-- Reposted background --}}
                                                <span x-show="isReposted(campaignId)"
                                                    class="absolute top-0 left-0 w-full h-full cursor-pointer hover:shadow-lg bg-orange-400 dark:bg-orange-500 text-white hover:bg-orange-5000 focus:ring-green-500"></span>

                                                {{-- Eligible background --}}
                                                <span
                                                    x-show="isEligibleForRepost(campaignId) && !isReposted(campaignId)"
                                                    class="absolute top-0 left-0 w-full h-full cursor-pointer hover:shadow-lg bg-orange-400 dark:bg-orange-500 text-white hover:bg-orange-5000 focus:ring-orange-500"></span>

                                                <!-- Animated fill background -->
                                                <div x-show="!isReposted(campaignId)"
                                                    class="absolute inset-0 bg-gradient-to-r from-orange-600 to-orange-500 transition-all duration-300 ease-out z-0"
                                                    :style="`width: ${getPlayTimePercentage(campaignId)}%`">
                                                </div>

                                                <!-- Button content -->
                                                <div class="relative z-10 flex items-center gap-2">
                                                    <template x-if="!isReposted(campaignId)">
                                                        <div class="flex items-center gap-2 text-white!">
                                                            <svg width="26" height="18" viewBox="0 0 26 18"
                                                                :class="{ 'stroke-white': isEligibleForRepost(campaignId) }"
                                                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <rect x="1" y="1" width="24" height="16"
                                                                    rx="3" fill="none"
                                                                    stroke="currentColor" stroke-width="2" />
                                                                <circle cx="8" cy="9" r="3"
                                                                    fill="none" stroke="currentColor"
                                                                    stroke-width="2" />
                                                            </svg>
                                                            <span
                                                                :class="{ 'text-white': isEligibleForRepost(campaignId) }">{{ user()->repost_price }}
                                                                Repost</span>
                                                        </div>
                                                    </template>

                                                    <template x-if="isReposted(campaignId)">
                                                        <div class="flex items-center gap-2 text-white!">
                                                            <svg class="w-5 h-5" fill="currentColor"
                                                                class="stroke-white"
                                                                viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd"
                                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                                    clip-rule="evenodd" />
                                                            </svg>
                                                            <span class="text-white">Reposted</span>
                                                        </div>
                                                    </template>
                                                </div>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-auto">
                                    <span
                                        class="inline-block bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm font-medium px-3 py-1.5 rounded-md shadow-sm">
                                        {{ !empty($campaign_->target_genre) ? ($campaign_->target_genre != 'anyGenre' ? $campaign_->target_genre : 'Any Genre') : 'Unknown Genre' }}
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
                    {{ $campaigns->links('components.pagination.wire-navigate2', [
                        'pageName' => $activeMainTab . 'Page',
                        'keep' => [
                            'tab' => $activeMainTab,
                            'selectedGenres' => $selectedGenres,
                            'search' => $search ?: null,
                            'trackType' => $trackType,
                        ],
                    ]) }}
                </div>
            @endif
        </div>
    </section>

    @if ($showCampaignCreator)
        @livewire('user.campaign-management.campaign-creator')
    @endif

    <livewire:user.repost />


    <script>
        window.sessionData = @json(session('campaign_playback_tracking', []));
        window.trackPlaybackRoute = "{{ route('user.api.campaign.track-playback') }}";
        window.clearSessionRoute = "{{ route('user.api.campaign.clear-tracking') }}";

        function trackPlaybackManager() {
            return {
                tracks: {},
                updateInterval: null,
                isInitialized: false,
                sessionSyncInProgress: false,
                widgetCleanupTimeout: null,

                init() {
                    console.log('🎵 Initializing trackPlaybackManager');

                    if (!this.isInitialized) {
                        this.clearSessionData();
                        localStorage.removeItem('campaign_tracking_data');
                        this.isInitialized = true;
                    }

                    this.loadFromSession();
                    this.loadPersistedTrackingData();
                    this.initializeSoundCloudWidgets();
                    this.startUpdateLoop();

                    // Listen for repost success
                    window.addEventListener('repost-success', (event) => {
                        const campaignId = event.detail.campaignId;
                        if (this.tracks[campaignId]) {
                            this.tracks[campaignId].reposted = true;
                            this.saveTrackingData();
                        }
                    });

                    // Handle Livewire morphs with debouncing
                    Livewire.hook('morph.updated', () => {
                        if (this.widgetCleanupTimeout) {
                            clearTimeout(this.widgetCleanupTimeout);
                        }

                        this.widgetCleanupTimeout = setTimeout(() => {
                            this.preserveStateOnMorph();
                        }, 150);
                    });
                },

                preserveStateOnMorph() {
                    const currentTracks = JSON.parse(JSON.stringify(this.tracks));

                    this.initializeSoundCloudWidgets();

                    Object.keys(currentTracks).forEach(campaignId => {
                        if (!this.tracks[campaignId]) {
                            this.tracks[campaignId] = currentTracks[campaignId];
                            this.tracks[campaignId].widget = null;
                            this.tracks[campaignId].isPlaying = false;
                        } else {
                            this.tracks[campaignId].actualPlayTime = currentTracks[campaignId].actualPlayTime;
                            this.tracks[campaignId].isEligible = currentTracks[campaignId].isEligible;
                            this.tracks[campaignId].reposted = currentTracks[campaignId].reposted;
                            this.tracks[campaignId].lastPosition = currentTracks[campaignId].lastPosition;
                        }
                    });

                    this.saveTrackingData();
                },

                resetForFilterChange() {
                    this.syncAllTracksToBackend();

                    // Pause and cleanup all widgets
                    Object.keys(this.tracks).forEach(campaignId => {
                        const track = this.tracks[campaignId];
                        if (track.widget && track.isPlaying) {
                            try {
                                track.widget.pause();
                            } catch (e) {
                                console.warn('Widget already destroyed:', campaignId);
                            }
                        }
                        track.widget = null;
                        track.isPlaying = false;
                        track.playStartTime = null;
                    });

                    this.saveTrackingData();

                    setTimeout(() => {
                        this.loadFromSession();
                        this.initializeSoundCloudWidgets();
                    }, 100);
                },

                syncAllTracksToBackend() {
                    if (this.sessionSyncInProgress) return;

                    this.sessionSyncInProgress = true;

                    const syncPromises = Object.keys(this.tracks).map(campaignId => {
                        const track = this.tracks[campaignId];
                        if (track.actualPlayTime > 0) {
                            return this.syncToBackend(campaignId, 'sync');
                        }
                        return Promise.resolve();
                    });

                    Promise.all(syncPromises).finally(() => {
                        this.sessionSyncInProgress = false;
                    });
                },

                loadFromSession() {
                    const sessionData = typeof window.sessionData !== 'undefined' ? window.sessionData : {};

                    if (sessionData && Object.keys(sessionData).length > 0) {
                        Object.keys(sessionData).forEach(campaignId => {
                            if (!this.tracks[campaignId]) {
                                this.tracks[campaignId] = {
                                    isPlaying: false,
                                    actualPlayTime: 0,
                                    isEligible: false,
                                    lastPosition: 0,
                                    playStartTime: null,
                                    seekDetected: false,
                                    widget: null,
                                    reposted: false,
                                };
                            }

                            this.tracks[campaignId].actualPlayTime = parseFloat(sessionData[campaignId]
                                .actual_play_time) || 0;
                            this.tracks[campaignId].isEligible = sessionData[campaignId].is_eligible || false;
                            this.tracks[campaignId].reposted = sessionData[campaignId].reposted || false;
                        });

                        this.saveTrackingData();
                    }
                },

                loadPersistedTrackingData() {
                    const stored = localStorage.getItem('campaign_tracking_data');

                    if (stored) {
                        try {
                            const data = JSON.parse(stored);

                            Object.keys(data).forEach(campaignId => {
                                if (!this.tracks[campaignId]) {
                                    this.tracks[campaignId] = {
                                        isPlaying: false,
                                        actualPlayTime: 0,
                                        isEligible: false,
                                        lastPosition: 0,
                                        playStartTime: null,
                                        seekDetected: false,
                                        widget: null,
                                        reposted: false,
                                    };
                                }

                                if (data[campaignId].actualPlayTime > this.tracks[campaignId].actualPlayTime) {
                                    this.tracks[campaignId].actualPlayTime = parseFloat(data[campaignId]
                                        .actualPlayTime) || 0;
                                    this.tracks[campaignId].isEligible = data[campaignId].isEligible || false;
                                    this.tracks[campaignId].lastPosition = parseFloat(data[campaignId]
                                        .lastPosition) || 0;
                                }

                                this.tracks[campaignId].reposted = data[campaignId].reposted || this.tracks[
                                    campaignId].reposted;
                            });
                        } catch (e) {
                            console.error('❌ Error loading tracking data:', e);
                        }
                    }
                },

                saveTrackingData() {
                    const dataToSave = {};
                    Object.keys(this.tracks).forEach(campaignId => {
                        dataToSave[campaignId] = {
                            actualPlayTime: this.tracks[campaignId].actualPlayTime,
                            isEligible: this.tracks[campaignId].isEligible,
                            lastPosition: this.tracks[campaignId].lastPosition,
                            reposted: this.tracks[campaignId].reposted,
                        };
                    });

                    localStorage.setItem('campaign_tracking_data', JSON.stringify(dataToSave));
                },

                startUpdateLoop() {
                    if (this.updateInterval) {
                        clearInterval(this.updateInterval);
                    }

                    // Force reactivity every 500ms
                    this.updateInterval = setInterval(() => {
                        this.tracks = Object.assign({}, this.tracks);
                    }, 500);
                },

                initializeSoundCloudWidgets() {
                    if (typeof SC === 'undefined') {
                        setTimeout(() => this.initializeSoundCloudWidgets(), 500);
                        return;
                    }

                    const playerContainers = document.querySelectorAll('[id^="soundcloud-player-"]');

                    playerContainers.forEach(container => {
                        const campaignId = container.dataset.campaignId;
                        const currentCampaignCard = container.closest('.campaign-card');

                        if (!currentCampaignCard) return;

                        if (!this.tracks[campaignId]) {
                            this.tracks[campaignId] = {
                                isPlaying: false,
                                actualPlayTime: 0,
                                isEligible: false,
                                lastPosition: 0,
                                playStartTime: null,
                                seekDetected: false,
                                widget: null,
                                reposted: false,
                            };
                        }

                        const iframe = container.querySelector('iframe');
                        if (!iframe || !campaignId) return;

                        // Check if widget is still valid
                        if (this.tracks[campaignId].widget) {
                            try {
                                this.tracks[campaignId].widget.isPaused((isPaused) => {
                                    // Widget is still valid
                                });
                                return;
                            } catch (e) {
                                // Widget is stale, rebind
                                this.tracks[campaignId].widget = null;
                            }
                        }

                        const nextCampaignCard = currentCampaignCard.nextElementSibling;
                        let nextIframe = null;
                        let nextCampaignId = null;

                        if (nextCampaignCard && nextCampaignCard.classList.contains('campaign-card')) {
                            const nextPlayerContainer = nextCampaignCard.querySelector(
                                '[id^="soundcloud-player-"]');
                            if (nextPlayerContainer) {
                                nextIframe = nextPlayerContainer.querySelector('iframe');
                                nextCampaignId = nextPlayerContainer.dataset.campaignId;
                            }
                        }

                        const widget = SC.Widget(iframe);
                        this.tracks[campaignId].widget = widget;

                        // Bind events
                        this.bindWidgetEvents(widget, campaignId, nextIframe, nextCampaignId);
                    });
                },

                bindWidgetEvents(widget, campaignId, nextIframe, nextCampaignId) {
                    widget.bind(SC.Widget.Events.PLAY, () => {
                        const track = this.tracks[campaignId];
                        track.isPlaying = true;
                        track.playStartTime = Date.now();
                        this.syncToBackend(campaignId, 'play');
                    });

                    widget.bind(SC.Widget.Events.PAUSE, () => {
                        const track = this.tracks[campaignId];
                        track.isPlaying = false;
                        track.playStartTime = null;
                        this.syncToBackend(campaignId, 'pause');
                        this.saveTrackingData();
                    });

                    widget.bind(SC.Widget.Events.FINISH, () => {
                        const track = this.tracks[campaignId];
                        track.isPlaying = false;
                        track.playStartTime = null;
                        this.syncToBackend(campaignId, 'finish');
                        this.saveTrackingData();

                        if (nextCampaignId && nextIframe) {
                            const nextWidget = SC.Widget(nextIframe);
                            setTimeout(() => nextWidget.play(), 100);
                        }
                    });

                    widget.bind(SC.Widget.Events.PLAY_PROGRESS, (data) => {
                        const currentPosition = data.currentPosition / 1000;
                        const track = this.tracks[campaignId];

                        const positionDiff = Math.abs(currentPosition - track.lastPosition);

                        if (positionDiff > 1.5 && track.lastPosition > 0) {
                            track.seekDetected = true;
                            track.lastPosition = currentPosition;
                            return;
                        }

                        if (track.isPlaying && !track.seekDetected) {
                            const increment = currentPosition - track.lastPosition;

                            if (increment > 0 && increment < 2) {
                                track.actualPlayTime += increment;

                                if (track.actualPlayTime >= 2 && !track.isEligible) {
                                    track.isEligible = true;
                                    this.syncToBackend(campaignId, 'eligible');
                                    this.saveTrackingData();
                                }

                                if (Math.floor(track.actualPlayTime) % 2 === 0) {
                                    this.saveTrackingData();
                                }
                            }
                        }

                        track.lastPosition = currentPosition;
                        track.seekDetected = false;
                    });

                    widget.bind(SC.Widget.Events.SEEK, (data) => {
                        const track = this.tracks[campaignId];
                        track.seekDetected = true;
                        track.lastPosition = data.currentPosition / 1000;
                    });
                },

                syncToBackend(campaignId, action) {
                    const track = this.tracks[campaignId];
                    if (!track) return Promise.resolve();

                    const csrfToken = document.querySelector('meta[name="csrf-token"]');
                    if (!csrfToken) {
                        console.error('❌ CSRF token not found');
                        return Promise.resolve();
                    }

                    return fetch(window.trackPlaybackRoute || '/api/campaign/track-playback', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken.content,
                            },
                            body: JSON.stringify({
                                campaignId: campaignId,
                                actualPlayTime: track.actualPlayTime,
                                isEligible: track.isEligible,
                                reposted: track.reposted,
                                action: action
                            })
                        })
                        .then(response => response.json())
                        .catch(err => {
                            console.error('❌ Failed to sync:', err);
                        });
                },

                clearSessionData() {
                    const csrfToken = document.querySelector('meta[name="csrf-token"]');
                    if (!csrfToken) return;

                    fetch(window.clearSessionRoute || '/api/campaign/clear-tracking', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken.content,
                            }
                        })
                        .then(() => console.log('✅ Session cleared'))
                        .catch(err => console.error('❌ Failed to clear session:', err));
                },

                isEligibleForRepost(campaignId) {
                    return this.tracks[campaignId]?.isEligible || false;
                },

                isReposted(campaignId) {
                    return this.tracks[campaignId]?.reposted || false;
                },

                getPlayTime(campaignId) {
                    return this.tracks[campaignId]?.actualPlayTime || 0;
                },

                getPlayTimePercentage(campaignId) {
                    const playTime = this.getPlayTime(campaignId);
                    const percentage = Math.min((playTime / 2) * 100, 100);
                    return percentage.toFixed(2);
                },

                handleRepost(campaignId) {
                    if (!this.isEligibleForRepost(campaignId) || this.isReposted(campaignId)) {
                        return;
                    }

                    console.log('🔄 Initiating repost for:', campaignId);

                    window.dispatchEvent(new CustomEvent('repost-action-modal-show'));

                    // Use setTimeout to ensure modal shows first
                    setTimeout(() => {
                        Livewire.dispatch('callRepostAction', {
                            campaignId: campaignId
                        });
                    }, 50);
                },

                clearAllTracking() {
                    Object.keys(this.tracks).forEach(campaignId => {
                        const track = this.tracks[campaignId];
                        if (track.widget && track.isPlaying) {
                            try {
                                track.widget.pause();
                            } catch (e) {
                                console.warn('Widget already destroyed');
                            }
                        }
                    });

                    this.tracks = {};
                    localStorage.removeItem('campaign_tracking_data');

                    if (this.updateInterval) {
                        clearInterval(this.updateInterval);
                    }

                    this.clearSessionData();
                }
            };
        }

        document.addEventListener('livewire:initialized', function() {
            // console.log('🚀 Livewire initialized');
            Alpine.data('trackPlaybackManager', trackPlaybackManager);
        });

        document.addEventListener('livewire:navigated', function() {
            // console.log('🔄 Livewire navigated');
            setTimeout(() => {
                const mainElement = document.querySelector('div[x-data*="trackPlaybackManager"]');
                if (mainElement && mainElement.__x) {
                    const trackManager = mainElement.__x.$data;
                    if (trackManager && trackManager.initializeSoundCloudWidgets) {
                        trackManager.initializeSoundCloudWidgets();
                    }
                }
            }, 100);
        });

        window.addEventListener('beforeunload', function() {
            const mainElement = document.querySelector('div[x-data*="trackPlaybackManager"]');
            if (mainElement && mainElement.__x) {
                const trackManager = mainElement.__x.$data;
                if (trackManager) {
                    trackManager.syncAllTracksToBackend();
                    trackManager.saveTrackingData();
                }
            }
        });
    </script>
</div>
