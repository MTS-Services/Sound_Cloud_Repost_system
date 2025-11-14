<main>
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
                        Filter by track type / {{ $trackTypeFilter }}
                        <x-lucide-chevron-down class="w-4 h-4" />
                    </button>

                    <div x-show="openFilterByTrack" x-transition x-cloak
                        class="absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white dark:bg-slate-800 z-50">
                        <div class="py-1">
                            @foreach (['all' => 'All', Track::class => 'Tracks', Playlist::class => 'Playlists'] as $value => $label)
                                <button wire:click="$set('trackTypeFilter', '{{ $value }}')"
                                    @class([
                                        'block w-full text-left px-4 py-2 text-sm border-b border-gray-100 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-700',
                                        'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300' =>
                                            $trackTypeFilter === $value,
                                        'text-gray-700 dark:text-gray-300' => $trackTypeFilter !== $value,
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
                                {{-- 🔥 UPDATED: Added loading indicator with proper wire:loading directive --}}
                                <button wire:click="toggleGenre('{{ $genre }}')"
                                    wire:loading.class="opacity-50 cursor-wait" wire:loading.attr="disabled"
                                    wire:target="toggleGenre('{{ $genre }}')" @class([
                                        'inline-flex items-center gap-1.5 px-3 py-2 text-sm rounded-md transition-all duration-200',
                                        'bg-orange-500 text-white' => in_array($genre, $selectedGenres),
                                        'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' => !in_array(
                                            $genre,
                                            $selectedGenres),
                                    ])>
                                    {{-- Genre text --}}
                                    <span>{{ $genre }}</span>

                                    {{-- 🔥 UPDATED: Compact loading spinner (hidden by default, shown on loading) --}}
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

        <div class="flex flex-col space-y-4">

            @forelse ($campaigns as $campaign_)
                <div class="campaign-card bg-white dark:bg-gray-800 border border-gray-200 mb-4 dark:border-gray-700 shadow-sm"
                    data-permalink="{{ $campaign_->music->permalink_url }}">
                    <div class="flex flex-col lg:flex-row" wire:key="featured-{{ $campaign_->id }}">
                        <!-- Left Column - Track Info -->
                        <div
                            class="w-full lg:w-1/2 border-b lg:border-b-0 lg:border-r border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700">
                            <div class="flex flex-col md:flex-row gap-4">
                                <!-- Track Details -->
                                <div class="flex-1 flex flex-col justify-between relative">
                                    {{-- 💡 IMPORTANT: Only render the actual player for the first item --}}
                                    <div id="soundcloud-player-{{ $campaign_->id }}"
                                        data-campaign-id="{{ $campaign_->id }}" wire:ignore>
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
                                    <div class="flex items-center gap-3 w-full">
                                        <img class="w-14 h-14 rounded-full object-cover"
                                            src="{{ auth_storage_url($campaign_?->music?->user?->avatar) }}"
                                            alt="Audio Cure avatar">
                                        <div x-data="{ open: false }" class="inline-block text-left">
                                            <div @click="open = !open" class="flex items-center gap-1 cursor-pointer">
                                                <span
                                                    class="text-slate-700 dark:text-gray-300 font-medium">{{ $campaign_?->music?->user?->name }}</span>
                                                <svg class="w-4 h-4 text-gray-600 dark:text-gray-400" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                </svg>
                                            </div>
                                            <button
                                                x-on:click="Livewire.dispatch('starMarkUser', { userUrn: '{{ $campaign_?->music?->user?->urn }}' })">
                                                <x-lucide-star
                                                    class="w-5 h-5 mt-1 relative {{ $campaign_->user?->starredUsers?->contains('follower_urn', user()->urn)
                                                        ? 'text-orange-300 '
                                                        : 'text-gray-400 dark:text-gray-500' }}"
                                                    fill="{{ $campaign_->user?->starredUsers?->contains('follower_urn', user()->urn) ? 'orange ' : 'none' }}" />
                                            </button>

                                            <div x-show="open" x-transition.opacity
                                                class="absolute left-0 mt-2 w-56 z-50 shadow-lg bg-gray-900 text-white text-sm p-2 space-y-2"
                                                x-cloak>
                                                <a href="{{ $campaign_?->music?->user?->soundcloud_permalink_url }}"
                                                    target="_blank"
                                                    class="block hover:bg-gray-800 px-3 py-1 rounded">Visit
                                                    SoundCloud
                                                    Profile</a>
                                                @if ($campaign_->user)
                                                    <a href="{{ route('user.my-account.user', !empty($campaign_->user?->name) ? $campaign_->user?->name : $campaign_->user?->urn) }}"
                                                        wire:navigate
                                                        class="block hover:bg-gray-800 px-3 py-1 rounded">Visit
                                                        RepostChain Profile</a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Stats and Repost Button -->
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
                                                <span
                                                    class="text-sm sm:text-base">{{ $campaign_->budget_credits - $campaign_->credits_spent }}</span>
                                            </div>
                                            <span
                                                class="text-xs text-gray-500 dark:text-gray-500 mt-1">REMAINING</span>
                                        </div>
                                        <div class="relative">
                                            <!-- Repost Button -->
                                            <button
                                                @if (session()->get('repostedId') != $campaign_->id) wire:click="confirmRepost('{{ $campaign_->id }}')" @endif
                                                class="flex items-center gap-2 py-2 px-4 sm:px-5 sm:pl-8 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 rounded-lg shadow-sm text-sm sm:text-base transition-colors bg-orange-600 dark:bg-orange-500 hover:bg-orange-700 dark:hover:bg-orange-400 text-white dark:text-gray-300 cursor-pointer">

                                                <svg width="26" height="18" viewBox="0 0 26 18"
                                                    fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <rect x="1" y="1" width="24" height="16" rx="3"
                                                        fill="none" stroke="currentColor" stroke-width="2" />
                                                    <circle cx="8" cy="9" r="3" fill="none"
                                                        stroke="currentColor" stroke-width="2" />
                                                </svg>
                                                <span>{{ user()->repost_price }} Repost</span>
                                            </button>
                                            {{-- @if (in_array($campaign_->id, $this->repostedCampaigns))
                                                <div
                                                    class="absolute -top-8 left-1/2 transform -translate-x-1/2 bg-green-600 text-white text-xs px-2 py-1 rounded shadow-lg whitespace-nowrap">
                                                    Reposted! ✓
                                                </div>
                                            @endif --}}
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
                        'keep' => ['tab' => $activeMainTab, 'selectedGenres' => $selectedGenres],
                    ]) }}
                </div>
            @endif

        </div>
    </section>
    @if ($showCampaignCreator)
        @livewire('user.campaign-management.campaign-creator')
    @endif


    @script
        <script>
            function initializeSoundCloudWidgets() {
                if (typeof SC === 'undefined') {
                    setTimeout(initializeSoundCloudWidgets, 500);
                    return;
                }
                const playerContainers = document.querySelectorAll('[id^="soundcloud-player-"]');
                console.log('playerContainers', playerContainers);

                playerContainers.forEach(container => {
                    const campaignId = container.dataset.campaignId;


                    let currentCampaignCard = container.closest('.campaign-card');
                    console.log('currentCampaignCard', currentCampaignCard);

                    // Safety check - make sure we found the card
                    if (!currentCampaignCard) {
                        console.error('Could not find the parent campaign-card for campaignId', campaignId);
                        return;
                    }

                    // 2. Find the next campaign-card sibling
                    const nextCampaignCard = currentCampaignCard.nextElementSibling;
                    console.log('nextCampaignCard', nextCampaignCard);

                    // 3. Find the iframe inside the NEXT campaign card
                    let nextIframe = null;
                    let nextCampaignId = null;

                    if (nextCampaignCard && nextCampaignCard.classList.contains('campaign-card')) {
                        // Find the iframe inside the next card
                        const nextPlayerContainer = nextCampaignCard.querySelector('[id^="soundcloud-player-"]');

                        if (nextPlayerContainer) {
                            nextIframe = nextPlayerContainer.querySelector('iframe');
                            nextCampaignId = nextPlayerContainer.dataset.campaignId;
                        }
                    }
                    const iframe = container.querySelector('iframe');

                    if (iframe && campaignId) {
                        const widget = SC.Widget(iframe);

                        widget.bind(SC.Widget.Events.PLAY, () => {
                            console.log('PLAY event fired for campaignId', campaignId);
                        });

                        widget.bind(SC.Widget.Events.PAUSE, () => {
                            console.log('PAUSE event fired for campaignId', campaignId);
                        });

                        widget.bind(SC.Widget.Events.FINISH, () => {
                            console.log('FINISH event fired for campaignId', campaignId);
                            if (nextCampaignId && nextIframe) {
                                console.log('nextIframe', nextIframe);
                                const nextWidget = SC.Widget(nextIframe);
                                nextWidget.play();
                            }
                        });

                        widget.bind(SC.Widget.Events.PLAY_PROGRESS, (data) => {
                            const currentTime = data.currentPosition / 1000;
                            @this.call('handleAudioTimeUpdate', campaignId, currentTime);
                        });
                    }
                });
            }
            document.addEventListener('livewire:initialized', function() {
                initializeSoundCloudWidgets();
            });
            document.addEventListener('livewire:navigated', function() {
                initializeSoundCloudWidgets();
                // @this.call('forgetRepostedId');
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

            document.addEventListener('livewire:dispatched', (event) => {
                if (event.detail.event === 'soundcloud-widgets-reinitialize') {
                    initializeSoundCloudWidgets();
                }
            });
        </script>
    @endscript

</main>
