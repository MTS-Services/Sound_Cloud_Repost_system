<main x-data="trackPlaybackManager()" @clearCampaignTracking.window="trackPlaybackManager().clearAllTracking()">
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
                                <button wire:click="$set('trackType', '{{ $value }}')"
                                    @class([
                                        'block w-full text-left px-4 py-2 text-sm border-b border-gray-100 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-700',
                                        'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300' =>
                                            $trackType === $value,
                                        'text-gray-700 dark:text-gray-300' => $trackType !== $value,
                                    ])>
                                    {{ $label }}
                                </button>
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
                                <button wire:click="toggleGenre('{{ $genre }}')"
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
                                </button>
                            @endforeach
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

                                        <!-- Repost Button with Tracking -->
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

                                            <!-- Countdown Tooltip -->
                                            <div x-show="!isReposted(campaignId) && !isEligibleForRepost(campaignId) && getPlayTime(campaignId) > 0"
                                                x-transition:enter="transition ease-out duration-200"
                                                x-transition:enter-start="opacity-0 transform scale-95"
                                                x-transition:enter-end="opacity-100 transform scale-100"
                                                class="absolute -top-10 left-1/2 transform -translate-x-1/2 bg-gray-900 text-white text-xs font-medium px-3 py-1.5 rounded-lg shadow-lg whitespace-nowrap z-20 pointer-events-none">
                                                <span
                                                    x-text="Math.max(0, Math.ceil(2 - getPlayTime(campaignId))).toString() + 's remaining'"></span>
                                                <div
                                                    class="absolute top-full left-1/2 transform -translate-x-1/2 -mt-px">
                                                    <div class="border-4 border-transparent border-t-gray-900"></div>
                                                </div>
                                            </div>

                                            <!-- Ready Tooltip -->
                                            <div x-show="!isReposted(campaignId) && isEligibleForRepost(campaignId) && (showReadyTooltip || $el.parentElement.querySelector('.repost-button').matches(':hover'))"
                                                x-transition:enter="transition ease-out duration-200"
                                                x-transition:enter-start="opacity-0 transform scale-95"
                                                x-transition:enter-end="opacity-100 transform scale-100"
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
                                            <button :data-campaign-id="campaignId"
                                                x-bind:disabled="!isEligibleForRepost(campaignId) || isReposted(campaignId)"
                                                @click="handleRepost(campaignId)"
                                                class="repost-button relative overflow-hidden flex items-center gap-2 py-2 px-4 sm:px-5 sm:pl-8 focus:outline-none focus:ring-2 focus:ring-offset-2 rounded-lg shadow-sm text-sm sm:text-base transition-all duration-200"
                                                :class="{
                                                    'cursor-not-allowed bg-gray-300 dark:bg-gray-600 text-white dark:text-gray-300':
                                                        !isEligibleForRepost(campaignId) && !isReposted(campaignId),
                                                    'cursor-pointer hover:shadow-lg bg-gray-300 dark:bg-gray-600 text-white': isEligibleForRepost(
                                                        campaignId) && !isReposted(campaignId),
                                                    'bg-green-500 text-white cursor-not-allowed': isReposted(
                                                        campaignId),
                                                    'focus:ring-orange-500': !isReposted(campaignId),
                                                    'focus:ring-green-500': isReposted(campaignId)
                                                }">

                                                <!-- Animated fill background -->
                                                <div x-show="!isReposted(campaignId)"
                                                    class="absolute inset-0 bg-gradient-to-r from-orange-600 to-orange-500 transition-all duration-300 ease-out z-0"
                                                    :style="`width: ${getPlayTimePercentage(campaignId)}%`">
                                                </div>

                                                <!-- Button content -->
                                                <div class="relative z-10 flex items-center gap-2">
                                                    <template x-if="!isReposted(campaignId)">
                                                        <div class="flex items-center gap-2">
                                                            <svg width="26" height="18" viewBox="0 0 26 18"
                                                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <rect x="1" y="1" width="24" height="16"
                                                                    rx="3" fill="none"
                                                                    stroke="currentColor" stroke-width="2" />
                                                                <circle cx="8" cy="9" r="3"
                                                                    fill="none" stroke="currentColor"
                                                                    stroke-width="2" />
                                                            </svg>
                                                            <span>{{ user()->repost_price }} Repost</span>
                                                        </div>
                                                    </template>

                                                    <template x-if="isReposted(campaignId)">
                                                        <div class="flex items-center gap-2">
                                                            <span>✓</span>
                                                            <span>Reposted</span>
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

    {{-- <script>
        // Enhanced Alpine.js component for track playback management
        function trackPlaybackManager() {
            return {
                tracks: {},
                updateInterval: null,
                widgetInitQueue: [],
                isProcessingQueue: false,

                init() {
                    console.log('🎵 TrackManager: Initializing...');

                    // Always load persisted data
                    this.loadPersistedTrackingData();

                    // Initialize widgets with delay to ensure DOM is ready
                    this.$nextTick(() => {
                        setTimeout(() => {
                            this.initializeSoundCloudWidgets();
                            this.startUpdateLoop();
                        }, 100);
                    });

                    // Listen for repost success events
                    window.addEventListener('repost-success', (event) => {
                        const campaignId = event.detail.campaignId;
                        if (this.tracks[campaignId]) {
                            this.tracks[campaignId].reposted = true;
                            this.saveTrackingData();
                        }
                    });

                    // Enhanced Livewire navigation handling
                    Livewire.hook('morph.updated', ({
                        component
                    }) => {
                        console.log('🔄 Livewire morphed, preserving state...');

                        // Preserve current state
                        const currentTracks = JSON.parse(JSON.stringify(this.tracks));

                        // Wait for DOM to stabilize
                        setTimeout(() => {
                            console.log('🔄 Restoring state and reinitializing widgets...');

                            // Restore all tracking data first
                            Object.keys(currentTracks).forEach(campaignId => {
                                if (!this.tracks[campaignId]) {
                                    this.tracks[campaignId] = currentTracks[campaignId];
                                } else {
                                    // Merge preserved data with new entry
                                    this.tracks[campaignId].actualPlayTime = currentTracks[
                                        campaignId].actualPlayTime;
                                    this.tracks[campaignId].isEligible = currentTracks[campaignId]
                                        .isEligible;
                                    this.tracks[campaignId].reposted = currentTracks[campaignId]
                                        .reposted;
                                    this.tracks[campaignId].lastPosition = currentTracks[campaignId]
                                        .lastPosition;
                                    // Don't preserve widget or widgetId - let them rebind
                                    this.tracks[campaignId].widget = null;
                                    this.tracks[campaignId].widgetId = null;
                                }
                            });

                            // Reinitialize widgets for new DOM elements
                            this.initializeSoundCloudWidgets();

                            console.log('✅ State restored:', this.tracks);
                        }, 250);
                    });

                    // Handle component updates
                    this.$watch('$wire.__instance.fingerprint', () => {
                        console.log('🔄 Component updated, reinitializing...');
                        this.$nextTick(() => {
                            setTimeout(() => {
                                this.initializeSoundCloudWidgets();
                            }, 150);
                        });
                    });
                },

                loadPersistedTrackingData() {
                    console.log('💾 Loading persisted tracking data...');
                    const stored = localStorage.getItem('campaign_tracking_data');

                    if (stored) {
                        try {
                            const data = JSON.parse(stored);
                            Object.keys(data).forEach(campaignId => {
                                // Don't override existing data, only fill missing entries
                                if (!this.tracks[campaignId]) {
                                    this.tracks[campaignId] = {
                                        isPlaying: false,
                                        actualPlayTime: parseFloat(data[campaignId].actualPlayTime) || 0,
                                        isEligible: data[campaignId].isEligible || false,
                                        lastPosition: parseFloat(data[campaignId].lastPosition) || 0,
                                        playStartTime: null,
                                        seekDetected: false,
                                        widget: null,
                                        reposted: data[campaignId].reposted || false,
                                        boundEvents: false, // Track if events are bound
                                    };
                                }
                            });
                            console.log('✅ Loaded tracking data:', this.tracks);
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

                    this.updateInterval = setInterval(() => {
                        this.updateAllTrackTimes();
                    }, 100);
                },

                updateAllTrackTimes() {
                    // This is primarily for UI reactivity
                    Object.keys(this.tracks).forEach(campaignId => {
                        const track = this.tracks[campaignId];
                        if (track.isPlaying && track.playStartTime) {
                            // Force Alpine reactivity update
                            this.tracks = {
                                ...this.tracks
                            };
                        }
                    });
                },

                initializeSoundCloudWidgets() {
                    if (typeof SC === 'undefined') {
                        console.warn('⚠️ SoundCloud API not loaded yet, retrying...');
                        setTimeout(() => this.initializeSoundCloudWidgets(), 500);
                        return;
                    }

                    console.log('🎵 Initializing SoundCloud widgets...');

                    const playerContainers = document.querySelectorAll('[id^="soundcloud-player-"]');
                    console.log(`📦 Found ${playerContainers.length} player containers`);

                    playerContainers.forEach((container, index) => {
                        const campaignId = container.dataset.campaignId;
                        const iframe = container.querySelector('iframe');

                        if (!iframe || !campaignId) {
                            console.warn('⚠️ Missing iframe or campaignId for container:', container);
                            return;
                        }

                        // Initialize track data if not exists, preserve existing data
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
                                widgetId: null, // Track which iframe this widget is bound to
                            };
                        }

                        const track = this.tracks[campaignId];
                        const currentIframeId = iframe.getAttribute('src'); // Use src as unique identifier

                        // Check if widget is bound to THIS specific iframe
                        if (track.widget && track.widgetId === currentIframeId) {
                            console.log(`✅ Widget already initialized for campaign ${campaignId}`);
                            // Update widget reference to current iframe (in case it changed)
                            track.widget = SC.Widget(iframe);
                            return;
                        }

                        // Wait for iframe to be ready
                        if (!iframe.contentWindow) {
                            console.log(`⏳ Waiting for iframe to be ready: ${campaignId}`);
                            setTimeout(() => this.initializeSoundCloudWidgets(), 300);
                            return;
                        }

                        try {
                            // Unbind old events if widget exists
                            if (track.widget) {
                                console.log(`🔄 Unbinding old widget for campaign ${campaignId}`);
                                try {
                                    track.widget.unbind(SC.Widget.Events.PLAY);
                                    track.widget.unbind(SC.Widget.Events.PAUSE);
                                    track.widget.unbind(SC.Widget.Events.FINISH);
                                    track.widget.unbind(SC.Widget.Events.PLAY_PROGRESS);
                                    track.widget.unbind(SC.Widget.Events.SEEK);
                                } catch (e) {
                                    console.warn('Could not unbind old widget:', e);
                                }
                            }

                            const widget = SC.Widget(iframe);

                            // Wait for widget to be ready
                            widget.bind(SC.Widget.Events.READY, () => {
                                console.log(`✅ Widget ready for campaign ${campaignId}`);

                                track.widget = widget;
                                track.widgetId = currentIframeId;

                                // Bind events
                                this.bindWidgetEvents(campaignId, widget, container);
                                console.log(`🔗 Events bound for campaign ${campaignId}`);
                            });
                        } catch (error) {
                            console.error(`❌ Error initializing widget for campaign ${campaignId}:`, error);
                        }
                    });

                    // Force Alpine reactivity
                    this.tracks = {
                        ...this.tracks
                    };
                },

                bindWidgetEvents(campaignId, widget, container) {
                    const track = this.tracks[campaignId];

                    // Function to find next campaign (will be called at FINISH time)
                    const findNextCampaign = () => {
                        const currentCampaignCard = document.querySelector(`[data-campaign-id="${campaignId}"]`);
                        const nextCampaignCard = currentCampaignCard?.nextElementSibling;

                        if (nextCampaignCard?.classList.contains('campaign-card')) {
                            return nextCampaignCard.dataset.campaignId;
                        }
                        return null;
                    };

                    // PLAY event
                    widget.bind(SC.Widget.Events.PLAY, () => {
                        console.log(`▶️ PLAY: ${campaignId}`);
                        track.isPlaying = true;
                        track.playStartTime = Date.now();
                        this.syncToBackend(campaignId, 'play');
                    });

                    // PAUSE event
                    widget.bind(SC.Widget.Events.PAUSE, () => {
                        console.log(`⏸️ PAUSE: ${campaignId}`);
                        track.isPlaying = false;
                        track.playStartTime = null;
                        this.syncToBackend(campaignId, 'pause');
                        this.saveTrackingData();
                    });

                    // FINISH event
                    widget.bind(SC.Widget.Events.FINISH, () => {
                        console.log(`⏹️ FINISH: ${campaignId}`);
                        track.isPlaying = false;
                        track.playStartTime = null;
                        this.syncToBackend(campaignId, 'finish');
                        this.saveTrackingData();

                        // Find and auto-play next track (at finish time)
                        const nextCampaignId = findNextCampaign();
                        if (nextCampaignId) {
                            console.log(`⏭️ Auto-playing next: ${nextCampaignId}`);

                            // Wait a bit, then get fresh widget reference and play
                            setTimeout(() => {
                                const nextPlayerContainer = document.querySelector(
                                    `#soundcloud-player-${nextCampaignId}`);
                                const nextIframe = nextPlayerContainer?.querySelector('iframe');

                                if (nextIframe) {
                                    try {
                                        const nextWidget = SC.Widget(nextIframe);
                                        nextWidget.play();
                                        console.log(`✅ Started playing next: ${nextCampaignId}`);
                                    } catch (e) {
                                        console.error(`❌ Error auto-playing next track:`, e);
                                    }
                                } else {
                                    console.warn(`⚠️ Next iframe not found for campaign ${nextCampaignId}`);
                                }
                            }, 500);
                        }
                    });

                    // PLAY_PROGRESS event - Critical for accurate tracking
                    widget.bind(SC.Widget.Events.PLAY_PROGRESS, (data) => {
                        const currentPosition = data.currentPosition / 1000;
                        const positionDiff = Math.abs(currentPosition - track.lastPosition);

                        // Detect seeking
                        if (positionDiff > 1.5 && track.lastPosition > 0) {
                            console.log(`⏩ SEEK detected: ${campaignId}`);
                            track.seekDetected = true;
                            track.lastPosition = currentPosition;
                            return;
                        }

                        if (track.isPlaying && !track.seekDetected) {
                            const increment = currentPosition - track.lastPosition;

                            if (increment > 0 && increment < 2) {
                                track.actualPlayTime += increment;

                                // Check eligibility
                                if (track.actualPlayTime >= 2 && !track.isEligible) {
                                    console.log(`✅ ELIGIBLE: ${campaignId} (${track.actualPlayTime.toFixed(2)}s)`);
                                    track.isEligible = true;
                                    this.syncToBackend(campaignId, 'eligible');
                                    this.saveTrackingData();
                                }

                                // Periodic save
                                if (Math.floor(track.actualPlayTime) % 2 === 0) {
                                    this.saveTrackingData();
                                }
                            }
                        }

                        track.lastPosition = currentPosition;
                        track.seekDetected = false;
                    });

                    // SEEK event
                    widget.bind(SC.Widget.Events.SEEK, (data) => {
                        console.log(`🔀 SEEK event: ${campaignId}`);
                        track.seekDetected = true;
                        track.lastPosition = data.currentPosition / 1000;
                    });
                },

                syncToBackend(campaignId, action) {
                    const track = this.tracks[campaignId];
                    if (!track) return;

                    // Check if API endpoint exists before calling
                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
                    if (!csrfToken) {
                        console.warn('⚠️ CSRF token not found, skipping backend sync');
                        return;
                    }

                    fetch('/api/campaign/track-playback', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken,
                            },
                            body: JSON.stringify({
                                campaignId: campaignId,
                                actualPlayTime: track.actualPlayTime,
                                isEligible: track.isEligible,
                                action: action
                            })
                        })
                        .then(response => {
                            if (!response.ok) {
                                console.warn(
                                    `⚠️ Backend sync failed (${response.status}), continuing with localStorage only`
                                );
                            }
                            return response.json().catch(() => null);
                        })
                        .catch(err => {
                            console.warn('⚠️ Backend sync unavailable, using localStorage only:', err.message);
                        });
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
                        console.warn('⚠️ Cannot repost:', {
                            eligible: this.isEligibleForRepost(campaignId),
                            reposted: this.isReposted(campaignId)
                        });
                        return;
                    }

                    console.log('🔁 Reposting:', campaignId);
                    Livewire.dispatch('confirmRepost', {
                        campaignId: campaignId
                    });
                },

                clearAllTracking() {
                    console.log('🗑️ Clearing all tracking data...');

                    // Stop all playing tracks
                    Object.keys(this.tracks).forEach(campaignId => {
                        const track = this.tracks[campaignId];
                        if (track.widget && track.isPlaying) {
                            track.widget.pause();
                        }
                    });

                    // Clear data
                    this.tracks = {};
                    localStorage.removeItem('campaign_tracking_data');

                    if (this.updateInterval) {
                        clearInterval(this.updateInterval);
                    }
                }
            };
        }

        // Initialize on Livewire events
        document.addEventListener('livewire:initialized', function() {
            console.log('🚀 Livewire initialized, registering Alpine component');
            Alpine.data('trackPlaybackManager', trackPlaybackManager);
        });

        document.addEventListener('livewire:navigated', function() {
            console.log('🧭 Livewire navigated');
            setTimeout(() => {
                const mainElement = document.querySelector('main[x-data*="trackPlaybackManager"]');
                if (mainElement?.__x?.$data?.initializeSoundCloudWidgets) {
                    console.log('🔄 Re-initializing widgets after navigation');
                    mainElement.__x.$data.initializeSoundCloudWidgets();
                }
            }, 100);
        });

        // Clean up on page leave
        window.addEventListener('beforeunload', function() {
            const mainElement = document.querySelector('main[x-data*="trackPlaybackManager"]');
            if (mainElement?.__x?.$data?.saveTrackingData) {
                mainElement.__x.$data.saveTrackingData();
            }
        });

        // Custom event for manual reinitialization
        window.addEventListener('reInitializeTracking', () => {
            console.log('🔄 Manual reinitialization requested');
            const mainElement = document.querySelector('main[x-data*="trackPlaybackManager"]');
            if (mainElement?.__x?.$data?.initializeSoundCloudWidgets) {
                mainElement.__x.$data.initializeSoundCloudWidgets();
            }
        });
    </script> --}}

    <script>
        // Enhanced Alpine.js component for track playback management - OPTIMIZED
        function trackPlaybackManager() {
            return {
                tracks: {},
                updateInterval: null,
                lastSaveTime: 0,
                saveThrottle: 5000, // Save every 5 seconds max

                init() {
                    console.log('🎵 TrackManager: Initializing...');

                    // Load persisted data
                    this.loadPersistedTrackingData();

                    // Initialize widgets with delay
                    this.$nextTick(() => {
                        setTimeout(() => {
                            this.initializeSoundCloudWidgets();
                            this.startUpdateLoop();
                        }, 100);
                    });

                    // Listen for repost success
                    window.addEventListener('repost-success', (event) => {
                        const campaignId = event.detail.campaignId;
                        if (this.tracks[campaignId]) {
                            this.tracks[campaignId].reposted = true;
                            this.saveTrackingData();
                        }
                    });

                    // Enhanced Livewire navigation handling
                    Livewire.hook('morph.updated', ({
                        component
                    }) => {
                        console.log('🔄 Livewire morphed, preserving state...');
                        const currentTracks = JSON.parse(JSON.stringify(this.tracks));

                        setTimeout(() => {
                            console.log('🔄 Restoring state and reinitializing widgets...');
                            Object.keys(currentTracks).forEach(campaignId => {
                                if (!this.tracks[campaignId]) {
                                    this.tracks[campaignId] = currentTracks[campaignId];
                                } else {
                                    this.tracks[campaignId].actualPlayTime = currentTracks[
                                        campaignId].actualPlayTime;
                                    this.tracks[campaignId].isEligible = currentTracks[campaignId]
                                        .isEligible;
                                    this.tracks[campaignId].reposted = currentTracks[campaignId]
                                        .reposted;
                                    this.tracks[campaignId].lastPosition = currentTracks[campaignId]
                                        .lastPosition;
                                    this.tracks[campaignId].widget = null;
                                    this.tracks[campaignId].widgetId = null;
                                }
                            });

                            this.initializeSoundCloudWidgets();
                            console.log('✅ State restored:', this.tracks);
                        }, 250);
                    });
                },

                loadPersistedTrackingData() {
                    console.log('💾 Loading persisted tracking data...');
                    const stored = localStorage.getItem('campaign_tracking_data');

                    if (stored) {
                        try {
                            const data = JSON.parse(stored);
                            Object.keys(data).forEach(campaignId => {
                                if (!this.tracks[campaignId]) {
                                    this.tracks[campaignId] = {
                                        isPlaying: false,
                                        actualPlayTime: parseFloat(data[campaignId].actualPlayTime) || 0,
                                        isEligible: data[campaignId].isEligible || false,
                                        lastPosition: parseFloat(data[campaignId].lastPosition) || 0,
                                        playStartTime: null,
                                        seekDetected: false,
                                        widget: null,
                                        reposted: data[campaignId].reposted || false,
                                        boundEvents: false,
                                    };
                                }
                            });
                            console.log('✅ Loaded tracking data:', this.tracks);
                        } catch (e) {
                            console.error('❌ Error loading tracking data:', e);
                        }
                    }
                },

                saveTrackingData() {
                    // Throttle saves to prevent excessive writes
                    const now = Date.now();
                    if (now - this.lastSaveTime < this.saveThrottle) {
                        return;
                    }
                    this.lastSaveTime = now;

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
                    // Clear any existing interval
                    if (this.updateInterval) {
                        clearInterval(this.updateInterval);
                    }

                    // CRITICAL FIX: Update every 1 second instead of 100ms (90% less calls)
                    this.updateInterval = setInterval(() => {
                        let needsUpdate = false;

                        Object.keys(this.tracks).forEach(campaignId => {
                            const track = this.tracks[campaignId];
                            if (track.isPlaying && track.playStartTime) {
                                needsUpdate = true;
                            }
                        });

                        // Only trigger reactivity if something is actually playing
                        if (needsUpdate) {
                            this.tracks = {
                                ...this.tracks
                            };
                        }
                    }, 1000); // Changed from 100ms to 1000ms
                },

                initializeSoundCloudWidgets() {
                    if (typeof SC === 'undefined') {
                        console.warn('⚠️ SoundCloud API not loaded yet, retrying...');
                        setTimeout(() => this.initializeSoundCloudWidgets(), 500);
                        return;
                    }

                    console.log('🎵 Initializing SoundCloud widgets...');

                    const playerContainers = document.querySelectorAll('[id^="soundcloud-player-"]');
                    console.log(`📦 Found ${playerContainers.length} player containers`);

                    playerContainers.forEach((container) => {
                        const campaignId = container.dataset.campaignId;
                        const iframe = container.querySelector('iframe');

                        if (!iframe || !campaignId) {
                            console.warn('⚠️ Missing iframe or campaignId for container:', container);
                            return;
                        }

                        // Initialize track data if not exists
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
                                widgetId: null,
                            };
                        }

                        const track = this.tracks[campaignId];
                        const currentIframeId = iframe.getAttribute('src');

                        // Check if widget is already bound
                        if (track.widget && track.widgetId === currentIframeId) {
                            console.log(`✅ Widget already initialized for campaign ${campaignId}`);
                            track.widget = SC.Widget(iframe);
                            return;
                        }

                        if (!iframe.contentWindow) {
                            console.log(`⏳ Waiting for iframe to be ready: ${campaignId}`);
                            setTimeout(() => this.initializeSoundCloudWidgets(), 300);
                            return;
                        }

                        try {
                            // Unbind old events if widget exists
                            if (track.widget) {
                                console.log(`🔄 Unbinding old widget for campaign ${campaignId}`);
                                try {
                                    track.widget.unbind(SC.Widget.Events.PLAY);
                                    track.widget.unbind(SC.Widget.Events.PAUSE);
                                    track.widget.unbind(SC.Widget.Events.FINISH);
                                    track.widget.unbind(SC.Widget.Events.PLAY_PROGRESS);
                                    track.widget.unbind(SC.Widget.Events.SEEK);
                                } catch (e) {
                                    console.warn('Could not unbind old widget:', e);
                                }
                            }

                            const widget = SC.Widget(iframe);

                            widget.bind(SC.Widget.Events.READY, () => {
                                console.log(`✅ Widget ready for campaign ${campaignId}`);
                                track.widget = widget;
                                track.widgetId = currentIframeId;
                                this.bindWidgetEvents(campaignId, widget, container);
                                console.log(`🔗 Events bound for campaign ${campaignId}`);
                            });
                        } catch (error) {
                            console.error(`❌ Error initializing widget for campaign ${campaignId}:`, error);
                        }
                    });

                    // Force Alpine reactivity
                    this.tracks = {
                        ...this.tracks
                    };
                },

                bindWidgetEvents(campaignId, widget, container) {
                    const track = this.tracks[campaignId];

                    // Find next campaign
                    const findNextCampaign = () => {
                        const currentCampaignCard = document.querySelector(`[data-campaign-id="${campaignId}"]`);
                        const nextCampaignCard = currentCampaignCard?.nextElementSibling;

                        if (nextCampaignCard?.classList.contains('campaign-card')) {
                            return nextCampaignCard.dataset.campaignId;
                        }
                        return null;
                    };

                    // PLAY event
                    widget.bind(SC.Widget.Events.PLAY, () => {
                        console.log(`▶️ PLAY: ${campaignId}`);
                        track.isPlaying = true;
                        track.playStartTime = Date.now();
                    });

                    // PAUSE event
                    widget.bind(SC.Widget.Events.PAUSE, () => {
                        console.log(`⏸️ PAUSE: ${campaignId}`);
                        track.isPlaying = false;
                        track.playStartTime = null;
                        this.saveTrackingData();
                    });

                    // FINISH event
                    widget.bind(SC.Widget.Events.FINISH, () => {
                        console.log(`⏹️ FINISH: ${campaignId}`);
                        track.isPlaying = false;
                        track.playStartTime = null;
                        this.saveTrackingData();

                        // Auto-play next track
                        const nextCampaignId = findNextCampaign();
                        if (nextCampaignId) {
                            console.log(`⏭️ Auto-playing next: ${nextCampaignId}`);
                            setTimeout(() => {
                                const nextPlayerContainer = document.querySelector(
                                    `#soundcloud-player-${nextCampaignId}`);
                                const nextIframe = nextPlayerContainer?.querySelector('iframe');

                                if (nextIframe) {
                                    try {
                                        const nextWidget = SC.Widget(nextIframe);
                                        nextWidget.play();
                                        console.log(`✅ Started playing next: ${nextCampaignId}`);
                                    } catch (e) {
                                        console.error(`❌ Error auto-playing next track:`, e);
                                    }
                                }
                            }, 500);
                        }
                    });

                    // PLAY_PROGRESS event - OPTIMIZED
                    let lastProgressUpdate = 0;
                    widget.bind(SC.Widget.Events.PLAY_PROGRESS, (data) => {
                        // CRITICAL FIX: Throttle progress updates to once per second
                        const now = Date.now();
                        if (now - lastProgressUpdate < 1000) {
                            return;
                        }
                        lastProgressUpdate = now;

                        const currentPosition = data.currentPosition / 1000;
                        const positionDiff = Math.abs(currentPosition - track.lastPosition);

                        // Detect seeking
                        if (positionDiff > 1.5 && track.lastPosition > 0) {
                            console.log(`⏩ SEEK detected: ${campaignId}`);
                            track.seekDetected = true;
                            track.lastPosition = currentPosition;
                            return;
                        }

                        if (track.isPlaying && !track.seekDetected) {
                            const increment = currentPosition - track.lastPosition;

                            if (increment > 0 && increment < 2) {
                                track.actualPlayTime += increment;

                                // Check eligibility
                                if (track.actualPlayTime >= 2 && !track.isEligible) {
                                    console.log(`✅ ELIGIBLE: ${campaignId} (${track.actualPlayTime.toFixed(2)}s)`);
                                    track.isEligible = true;
                                    this.saveTrackingData();
                                }
                            }
                        }

                        track.lastPosition = currentPosition;
                        track.seekDetected = false;
                    });

                    // SEEK event
                    widget.bind(SC.Widget.Events.SEEK, (data) => {
                        console.log(`🔀 SEEK event: ${campaignId}`);
                        track.seekDetected = true;
                        track.lastPosition = data.currentPosition / 1000;
                    });
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
                        console.warn('⚠️ Cannot repost:', {
                            eligible: this.isEligibleForRepost(campaignId),
                            reposted: this.isReposted(campaignId)
                        });
                        return;
                    }

                    console.log('📮 Reposting:', campaignId);
                    Livewire.dispatch('confirmRepost', {
                        campaignId: campaignId
                    });
                },

                clearAllTracking() {
                    console.log('🗑️ Clearing all tracking data...');

                    // Stop all playing tracks
                    Object.keys(this.tracks).forEach(campaignId => {
                        const track = this.tracks[campaignId];
                        if (track.widget && track.isPlaying) {
                            track.widget.pause();
                        }
                    });

                    // Clear data
                    this.tracks = {};
                    localStorage.removeItem('campaign_tracking_data');

                    if (this.updateInterval) {
                        clearInterval(this.updateInterval);
                    }
                }
            };
        }

        // Initialize on Livewire events
        document.addEventListener('livewire:initialized', function() {
            console.log('🚀 Livewire initialized, registering Alpine component');
            Alpine.data('trackPlaybackManager', trackPlaybackManager);
        });

        document.addEventListener('livewire:navigated', function() {
            console.log('🧭 Livewire navigated');
            setTimeout(() => {
                const mainElement = document.querySelector('main[x-data*="trackPlaybackManager"]');
                if (mainElement?.__x?.$data?.initializeSoundCloudWidgets) {
                    console.log('🔄 Re-initializing widgets after navigation');
                    mainElement.__x.$data.initializeSoundCloudWidgets();
                }
            }, 100);
        });

        // Clean up on page leave
        window.addEventListener('beforeunload', function() {
            const mainElement = document.querySelector('main[x-data*="trackPlaybackManager"]');
            if (mainElement?.__x?.$data?.saveTrackingData) {
                mainElement.__x.$data.saveTrackingData();
            }
        });

        // Custom event for manual reinitialization
        window.addEventListener('reInitializeTracking', () => {
            console.log('🔄 Manual reinitialization requested');
            const mainElement = document.querySelector('main[x-data*="trackPlaybackManager"]');
            if (mainElement?.__x?.$data?.initializeSoundCloudWidgets) {
                mainElement.__x.$data.initializeSoundCloudWidgets();
            }
        });
    </script>
</main>
