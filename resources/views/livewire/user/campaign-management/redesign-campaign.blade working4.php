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

        <div class="flex flex-col space-y-4">
            @forelse ($campaigns as $campaign_)
                <div class="campaign-card bg-white dark:bg-gray-800 border border-gray-200 mb-4 dark:border-gray-700 shadow-sm"
                    data-campaign-id="{{ $campaign_->id }}" data-permalink="{{ $campaign_->music->permalink_url }}">
                    <div class="flex flex-col lg:flex-row" wire:key="featured-{{ $campaign_->id }}">
                        <!-- Left Column - Track Info -->
                        <div
                            class="w-full lg:w-1/2 border-b lg:border-b-0 lg:border-r border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700">
                            <div class="flex flex-col md:flex-row gap-4">
                                <div class="flex-1 flex flex-col justify-between relative">
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
                                <div
                                    class="flex flex-col sm:flex-row relative items-start sm:items-center justify-between gap-4 mb-4">
                                    <div class="flex items-center gap-3 w-full">
                                        <img class="w-14 h-14 rounded-full object-cover"
                                            src="{{ auth_storage_url($campaign_?->music?->user?->avatar) }}"
                                            alt="Avatar">
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
                                                    class="block hover:bg-gray-800 px-3 py-1 rounded">Visit SoundCloud
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

                                        {{-- <div class="relative">
                                            <!-- Repost Button with animated fill effect -->
                                            <button :data-campaign-id="{{ $campaign_->id }}"
                                                x-bind:disabled="!isEligibleForRepost('{{ $campaign_->id }}') || isReposted(
                                                    '{{ $campaign_->id }}')"
                                                @click="handleRepost('{{ $campaign_->id }}')"
                                                class="repost-button relative overflow-hidden flex items-center gap-2 py-2 px-4 sm:px-5 sm:pl-8 focus:outline-none focus:ring-2 focus:ring-offset-2 rounded-lg shadow-sm text-sm sm:text-base transition-all duration-200"
                                                :class="{
                                                    'cursor-not-allowed bg-gray-300 dark:bg-gray-600 text-white dark:text-gray-300':
                                                        !isEligibleForRepost('{{ $campaign_->id }}') && !isReposted(
                                                            '{{ $campaign_->id }}'),
                                                    'cursor-pointer hover:shadow-lg bg-gray-300 dark:bg-gray-600 text-white': isEligibleForRepost(
                                                        '{{ $campaign_->id }}') && !isReposted(
                                                        '{{ $campaign_->id }}'),
                                                    'bg-green-500 text-white cursor-not-allowed': isReposted(
                                                        '{{ $campaign_->id }}'),
                                                    'focus:ring-orange-500': !isReposted('{{ $campaign_->id }}'),
                                                    'focus:ring-green-500': isReposted('{{ $campaign_->id }}')
                                                }">

                                                <!-- Animated orange fill background (only show if not reposted) -->
                                                <div x-show="!isReposted('{{ $campaign_->id }}')"
                                                    class="absolute inset-0 bg-gradient-to-r from-orange-600 to-orange-500 transition-all duration-300 ease-out"
                                                    :style="`width: ${getPlayTimePercentage('{{ $campaign_->id }}')}%`">
                                                </div>

                                                <!-- Button content (stays on top) -->
                                                <div class="relative z-10 flex items-center gap-2">
                                                    <template x-if="!isReposted('{{ $campaign_->id }}')">
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

                                                    <template x-if="isReposted('{{ $campaign_->id }}')">
                                                        <div class="flex items-center gap-2">
                                                            <span>✔️</span>
                                                            <span>Reposted</span>
                                                        </div>
                                                    </template>
                                                </div>
                                            </button>
                                        </div> --}}

                                        {{-- <div class="relative">
                                            <!-- Countdown Tooltip -->
                                            <div x-show="!isReposted('{{ $campaign_->id }}') && !isEligibleForRepost('{{ $campaign_->id }}') && getPlayTime('{{ $campaign_->id }}') > 0"
                                                x-transition:enter="transition ease-out duration-200"
                                                x-transition:enter-start="opacity-0 transform scale-95"
                                                x-transition:enter-end="opacity-100 transform scale-100"
                                                class="absolute -top-10 left-1/2 transform -translate-x-1/2 bg-gray-900 text-white text-xs font-medium px-3 py-1.5 rounded-lg shadow-lg whitespace-nowrap z-20">
                                                <span
                                                    x-text="Math.max(0, Math.ceil(15 - getPlayTime('{{ $campaign_->id }}'))).toString() + 's remaining'"></span>
                                                <!-- Tooltip arrow -->
                                                <div
                                                    class="absolute top-full left-1/2 transform -translate-x-1/2 -mt-px">
                                                    <div class="border-4 border-transparent border-t-gray-900"></div>
                                                </div>
                                            </div>

                                            <!-- Repost Button with animated fill effect -->
                                            <button :data-campaign-id="{{ $campaign_->id }}"
                                                x-bind:disabled="!isEligibleForRepost('{{ $campaign_->id }}') || isReposted(
                                                    '{{ $campaign_->id }}')"
                                                @click="handleRepost('{{ $campaign_->id }}')"
                                                class="repost-button relative overflow-hidden flex items-center gap-2 py-2 px-4 sm:px-5 sm:pl-8 focus:outline-none focus:ring-2 focus:ring-offset-2 rounded-lg shadow-sm text-sm sm:text-base transition-all duration-200"
                                                :class="{
                                                    'cursor-not-allowed bg-gray-300 dark:bg-gray-600 text-white dark:text-gray-300':
                                                        !isEligibleForRepost('{{ $campaign_->id }}') && !isReposted(
                                                            '{{ $campaign_->id }}'),
                                                    'cursor-pointer hover:shadow-lg bg-gray-300 dark:bg-gray-600 text-white': isEligibleForRepost(
                                                        '{{ $campaign_->id }}') && !isReposted(
                                                        '{{ $campaign_->id }}'),
                                                    'bg-green-500 text-white cursor-not-allowed': isReposted(
                                                        '{{ $campaign_->id }}'),
                                                    'focus:ring-orange-500': !isReposted('{{ $campaign_->id }}'),
                                                    'focus:ring-green-500': isReposted('{{ $campaign_->id }}')
                                                }">

                                                <!-- Animated orange fill background (only show if not reposted) -->
                                                <div x-show="!isReposted('{{ $campaign_->id }}')"
                                                    class="absolute inset-0 bg-gradient-to-r from-orange-600 to-orange-500 transition-all duration-300 ease-out"
                                                    :style="`width: ${getPlayTimePercentage('{{ $campaign_->id }}')}%`">
                                                </div>

                                                <!-- Button content (stays on top) -->
                                                <div class="relative z-10 flex items-center gap-2">
                                                    <template x-if="!isReposted('{{ $campaign_->id }}')">
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

                                                    <template x-if="isReposted('{{ $campaign_->id }}')">
                                                        <div class="flex items-center gap-2">
                                                            <span>✔️</span>
                                                            <span>Reposted</span>
                                                        </div>
                                                    </template>
                                                </div>
                                            </button>
                                        </div> --}}

                                        <div class="relative" x-data="{ showReadyTooltip: false, justBecameEligible: false }" x-init="$watch('isEligibleForRepost(\'{{ $campaign_->id }}\')', (value, oldValue) => {
                                            if (value && !oldValue && !isReposted('{{ $campaign_->id }}')) {
                                                justBecameEligible = true;
                                                showReadyTooltip = true;
                                                setTimeout(() => {
                                                    showReadyTooltip = false;
                                                    justBecameEligible = false;
                                                }, 3000);
                                            }
                                        })">

                                            <!-- Countdown Tooltip - Shows remaining time -->
                                            <div x-show="!isReposted('{{ $campaign_->id }}') && !isEligibleForRepost('{{ $campaign_->id }}') && getPlayTime('{{ $campaign_->id }}') > 0"
                                                x-transition:enter="transition ease-out duration-200"
                                                x-transition:enter-start="opacity-0 transform scale-95"
                                                x-transition:enter-end="opacity-100 transform scale-100"
                                                class="absolute -top-10 left-1/2 transform -translate-x-1/2 bg-gray-900 text-white text-xs font-medium px-3 py-1.5 rounded-lg shadow-lg whitespace-nowrap z-20 pointer-events-none">
                                                <span
                                                    x-text="Math.max(0, Math.ceil(15 - getPlayTime('{{ $campaign_->id }}'))).toString() + 's remaining'"></span>
                                                <!-- Tooltip arrow -->
                                                <div
                                                    class="absolute top-full left-1/2 transform -translate-x-1/2 -mt-px">
                                                    <div class="border-4 border-transparent border-t-gray-900"></div>
                                                </div>
                                            </div>

                                            <!-- Ready Tooltip - Shows when eligible (auto-hide after 3s, show on hover) -->
                                            <div x-show="!isReposted('{{ $campaign_->id }}') && isEligibleForRepost('{{ $campaign_->id }}') && (showReadyTooltip || $el.parentElement.querySelector('.repost-button').matches(':hover'))"
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
                                                <!-- Tooltip arrow -->
                                                <div
                                                    class="absolute top-full left-1/2 transform -translate-x-1/2 -mt-px">
                                                    <div class="border-4 border-transparent border-t-green-600"></div>
                                                </div>
                                            </div>

                                            <!-- Repost Button with animated fill effect -->
                                            <button :data-campaign-id="{{ $campaign_->id }}"
                                                x-bind:disabled="!isEligibleForRepost('{{ $campaign_->id }}') || isReposted(
                                                    '{{ $campaign_->id }}')"
                                                @click="handleRepost('{{ $campaign_->id }}')"
                                                class="repost-button relative overflow-hidden flex items-center gap-2 py-2 px-4 sm:px-5 sm:pl-8 focus:outline-none focus:ring-2 focus:ring-offset-2 rounded-lg shadow-sm text-sm sm:text-base transition-all duration-200"
                                                :class="{
                                                    'cursor-not-allowed bg-gray-300 dark:bg-gray-600 text-white dark:text-gray-300':
                                                        !isEligibleForRepost('{{ $campaign_->id }}') && !isReposted(
                                                            '{{ $campaign_->id }}'),
                                                    'cursor-pointer hover:shadow-lg bg-gray-300 dark:bg-gray-600 text-white': isEligibleForRepost(
                                                        '{{ $campaign_->id }}') && !isReposted(
                                                        '{{ $campaign_->id }}'),
                                                    'bg-green-500 text-white cursor-not-allowed': isReposted(
                                                        '{{ $campaign_->id }}'),
                                                    'focus:ring-orange-500': !isReposted('{{ $campaign_->id }}'),
                                                    'focus:ring-green-500': isReposted('{{ $campaign_->id }}')
                                                }">

                                                <!-- Animated orange fill background (only show if not reposted) -->
                                                <div x-show="!isReposted('{{ $campaign_->id }}')"
                                                    class="absolute inset-0 bg-gradient-to-r from-orange-600 to-orange-500 transition-all duration-300 ease-out z-0"
                                                    :style="`width: ${Math.min((getPlayTime('{{ $campaign_->id }}') / 15) * 100, 100)}%`">
                                                </div>

                                                <!-- Button content (stays on top) -->
                                                <div class="relative z-10 flex items-center gap-2">
                                                    <template x-if="!isReposted('{{ $campaign_->id }}')">
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

                                                    <template x-if="isReposted('{{ $campaign_->id }}')">
                                                        <div class="flex items-center gap-2">
                                                            <span>✔️</span>
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
                    {{ $campaigns->links('components.pagination.wire-navigate', [
                        'keep' => ['tab' => $activeMainTab, 'selectedGenres' => $selectedGenres],
                    ]) }}
                </div>
            @endif
        </div>
    </section>

    @if ($showCampaignCreator)
        @livewire('user.campaign-management.campaign-creator')
    @endif

    <script>
        // Alpine.js component for track playback management
        function trackPlaybackManager() {
            return {
                tracks: {},
                updateInterval: null,
                isInitialized: false,

                init() {
                    // Only load persisted data on first initialization (not on mount)
                    if (!this.isInitialized) {
                        this.loadPersistedTrackingData();
                        this.isInitialized = true;
                    }

                    this.initializeSoundCloudWidgets();
                    this.startUpdateLoop();

                    // Listen for repost success events
                    window.addEventListener('repost-success', (event) => {
                        const campaignId = event.detail.campaignId;
                        if (this.tracks[campaignId]) {
                            this.tracks[campaignId].reposted = true;
                        }
                    });

                    // Listen for Livewire navigation completed
                    Livewire.hook('morph.updated', ({
                        component
                    }) => {
                        // Preserve current tracking state
                        const currentTracks = JSON.parse(JSON.stringify(this.tracks));

                        setTimeout(() => {
                            // Re-initialize widgets for new DOM elements
                            this.initializeSoundCloudWidgets();

                            // Restore ALL tracking data (even for campaigns not currently on page)
                            Object.keys(currentTracks).forEach(campaignId => {
                                if (this.tracks[campaignId]) {
                                    // Campaign is on current page - merge data
                                    this.tracks[campaignId].actualPlayTime = currentTracks[
                                        campaignId].actualPlayTime;
                                    this.tracks[campaignId].isEligible = currentTracks[campaignId]
                                        .isEligible;
                                    this.tracks[campaignId].reposted = currentTracks[campaignId]
                                        .reposted;
                                    this.tracks[campaignId].lastPosition = currentTracks[campaignId]
                                        .lastPosition;
                                } else {
                                    // Campaign not on page but preserve data
                                    this.tracks[campaignId] = currentTracks[campaignId];
                                }
                            });
                        }, 150);
                    });
                },

                loadPersistedTrackingData() {
                    // Load from localStorage (browser-side persistence)
                    const stored = localStorage.getItem('campaign_tracking_data');
                    if (stored) {
                        try {
                            const data = JSON.parse(stored);
                            Object.keys(data).forEach(campaignId => {
                                this.tracks[campaignId] = {
                                    isPlaying: false,
                                    actualPlayTime: parseFloat(data[campaignId].actualPlayTime) || 0,
                                    isEligible: data[campaignId].isEligible || false,
                                    lastPosition: parseFloat(data[campaignId].lastPosition) || 0,
                                    playStartTime: null,
                                    seekDetected: false,
                                    widget: null,
                                    reposted: data[campaignId].reposted || false,
                                };
                            });
                        } catch (e) {
                            console.error('Error loading tracking data:', e);
                        }
                    }
                },

                saveTrackingData() {
                    // Save to localStorage
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
                    // Update UI every 100ms for smooth animation
                    if (this.updateInterval) {
                        clearInterval(this.updateInterval);
                    }

                    this.updateInterval = setInterval(() => {
                        this.updateAllTrackTimes();
                    }, 100);
                },

                updateAllTrackTimes() {
                    Object.keys(this.tracks).forEach(campaignId => {
                        const track = this.tracks[campaignId];

                        if (track.isPlaying && track.playStartTime) {
                            const elapsed = (Date.now() - track.playStartTime) / 1000;

                            // Only update if increment is valid (not seeking)
                            if (elapsed > 0 && elapsed < 2 && !track.seekDetected) {
                                // This is for smooth UI updates only
                                // Actual tracking happens in PLAY_PROGRESS event
                            }
                        }
                    });
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

                        // Initialize tracking for this campaign (preserve existing data if available)
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

                        // Skip re-binding if widget already exists
                        const iframe = container.querySelector('iframe');
                        if (!iframe || !campaignId) return;

                        // Check if widget is already bound
                        if (this.tracks[campaignId].widget) {
                            // Widget exists, just update reference
                            const widget = SC.Widget(iframe);
                            this.tracks[campaignId].widget = widget;
                            return;
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

                        // PLAY event
                        widget.bind(SC.Widget.Events.PLAY, () => {
                            const track = this.tracks[campaignId];
                            track.isPlaying = true;
                            track.playStartTime = Date.now();

                            this.syncToBackend(campaignId, 'play');
                        });

                        // PAUSE event
                        widget.bind(SC.Widget.Events.PAUSE, () => {
                            const track = this.tracks[campaignId];
                            track.isPlaying = false;
                            track.playStartTime = null;

                            this.syncToBackend(campaignId, 'pause');
                            this.saveTrackingData();
                        });

                        // FINISH event
                        widget.bind(SC.Widget.Events.FINISH, () => {
                            const track = this.tracks[campaignId];
                            track.isPlaying = false;
                            track.playStartTime = null;

                            this.syncToBackend(campaignId, 'finish');
                            this.saveTrackingData();

                            // Auto-play next track
                            if (nextCampaignId && nextIframe) {
                                const nextWidget = SC.Widget(nextIframe);
                                setTimeout(() => nextWidget.play(), 100);
                            }
                        });

                        // PLAY_PROGRESS event - Critical for accurate tracking
                        widget.bind(SC.Widget.Events.PLAY_PROGRESS, (data) => {
                            const currentPosition = data.currentPosition / 1000; // Convert to seconds
                            const track = this.tracks[campaignId];

                            // Detect seeking (user manually moved timeline)
                            const positionDiff = Math.abs(currentPosition - track.lastPosition);

                            if (positionDiff > 1.5 && track.lastPosition > 0) {
                                // User seeked - mark as detected and don't count this time
                                track.seekDetected = true;
                                track.lastPosition = currentPosition;
                                return;
                            }

                            if (track.isPlaying && !track.seekDetected) {
                                // Valid continuous playback
                                const increment = currentPosition - track.lastPosition;

                                // Only count valid increments (between 0 and 2 seconds)
                                if (increment > 0 && increment < 2) {
                                    track.actualPlayTime += increment;

                                    // Check if eligible (15 seconds actual play time)
                                    if (track.actualPlayTime >= 15 && !track.isEligible) {
                                        track.isEligible = true;
                                        this.syncToBackend(campaignId, 'eligible');
                                        this.saveTrackingData();
                                    }

                                    // Save periodically
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
                            const track = this.tracks[campaignId];
                            track.seekDetected = true;
                            track.lastPosition = data.currentPosition / 1000;
                        });
                    });
                },

                syncToBackend(campaignId, action) {
                    const track = this.tracks[campaignId];
                    if (!track) return;

                    // Use fetch to avoid Livewire re-render
                    fetch('/api/campaign/track-playback', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        },
                        body: JSON.stringify({
                            campaignId: campaignId,
                            actualPlayTime: track.actualPlayTime,
                            isEligible: track.isEligible,
                            action: action
                        })
                    }).catch(err => {
                        console.error('Failed to sync tracking:', err);
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
                    const percentage = Math.min((playTime / 5) * 100, 100);
                    return percentage.toFixed(2);
                },

                handleRepost(campaignId) {
                    if (!this.isEligibleForRepost(campaignId) || this.isReposted(campaignId)) {
                        return;
                    }

                    // Dispatch repost action using Livewire event
                    Livewire.dispatch('confirmRepost', {
                        campaignId: campaignId
                    });
                },

                clearAllTracking() {
                    // Stop all playing tracks
                    Object.keys(this.tracks).forEach(campaignId => {
                        const track = this.tracks[campaignId];
                        if (track.widget && track.isPlaying) {
                            track.widget.pause();
                        }
                    });

                    // Clear tracking data and localStorage
                    this.tracks = {};
                    localStorage.removeItem('campaign_tracking_data');

                    // Clear update interval
                    if (this.updateInterval) {
                        clearInterval(this.updateInterval);
                    }
                }
            };
        }

        // Initialize on Livewire events
        document.addEventListener('livewire:initialized', function() {
            Alpine.data('trackPlaybackManager', trackPlaybackManager);
        });

        document.addEventListener('livewire:navigated', function() {
            // Reinitialize widgets after navigation
            setTimeout(() => {
                const mainElement = document.querySelector('main[x-data*="trackPlaybackManager"]');
                if (mainElement && mainElement.__x) {
                    const trackManager = mainElement.__x.$data;
                    if (trackManager && trackManager.initializeSoundCloudWidgets) {
                        trackManager.initializeSoundCloudWidgets();
                    }
                }
            }, 100);
        });

        // Clean up on page leave
        window.addEventListener('beforeunload', function() {
            const mainElement = document.querySelector('main[x-data*="trackPlaybackManager"]');
            if (mainElement && mainElement.__x) {
                const trackManager = mainElement.__x.$data;
                if (trackManager && trackManager.saveTrackingData) {
                    trackManager.saveTrackingData();
                }
            }
        });
    </script>
</main>
