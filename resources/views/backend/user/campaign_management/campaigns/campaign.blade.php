<div wire:poll.1s="updatePlayingTimes">
    <x-slot name="page_slug">campaign-feed</x-slot>

    <!-- Header Section -->
    <div class="w-full mt-6">
        <!-- Header Tabs & Button -->
        <div
            class="flex flex-col sm:flex-row items-center justify-between px-2 sm:px-4 pt-3 border-b border-b-gray-200 gap-2 sm:gap-0">
            <div class="flex items-center w-full sm:w-auto justify-between sm:justify-start space-x-2 text-gray-600">
                <div class="flex items-center space-x-2 px-2 sm:px-7 cursor-pointer whitespace-nowrap">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21 21l-4.35-4.35M17 10a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <span>Search by tag</span>
                </div>
            </div>

            <button
                class="bg-orange-600 text-white px-3 sm:px-5 py-2 mb-2 rounded hover:bg-orange-700 transition w-full sm:w-auto text-center">
                Start a new campaign
            </button>
        </div>
    </div>

    <div class="container mx-auto px-4 py-6">
        <!-- Flash Messages -->
        @if (session()->has('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if (session()->has('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <!-- Featured Campaign Section -->
        @if (count($featuredCampaigns) > 0)
            <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Featured campaigns</h2>
            @foreach ($featuredCampaigns as $campaign)
                <div class="bg-white dark:bg-gray-800 border border-gray-200 mb-4 dark:border-gray-700 shadow-sm">
                    <div class="flex flex-col lg:flex-row" wire:key="featured-{{ $campaign->id }}">
                        <!-- Left Column - Track Info -->
                        <div
                            class="w-full lg:w-1/2 border-b lg:border-b-0 lg:border-r border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700">
                            <div class="flex flex-col md:flex-row gap-4">
                                <!-- Track Details -->
                                <div class="flex-1 flex flex-col justify-between p-2 relative">
                                    <!-- Your Original SoundCloud Player -->
                                    <div id="soundcloud-player-{{ $campaign->id }}"
                                        data-campaign-id="{{ $campaign->id }}" wire:ignore>
                                        <x-sound-cloud.sound-cloud-player :track="$campaign->music" :height="166"
                                            :visual="false" />
                                    </div>

                                    <!-- Livewire Control Buttons (for testing) -->
                                    {{-- <div class="mt-2 flex gap-2">
                                            @if ($this->isPlaying($campaign->id))
                                                <button wire:click="stopPlaying('{{ $campaign->id }}')"
                                                    class="text-xs bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded">
                                                    Stop
                                                </button>
                                            @else
                                                <button wire:click="startPlaying('{{ $campaign->id }}')"
                                                    class="text-xs bg-green-500 hover:bg-green-600 text-white px-2 py-1 rounded">
                                                    Play
                                                </button>
                                            @endif

                                            <!-- Test Button for development -->
                                            <button wire:click="simulateAudioProgress('{{ $campaign->id }}', 1)"
                                                class="text-xs bg-gray-500 hover:bg-gray-600 text-white px-2 py-1 rounded">
                                                +1s Test
                                            </button>

                                            <!-- Progress Display -->
                                            <span class="text-xs text-gray-600 dark:text-gray-400 px-2 py-1">
                                                {{ $this->getPlayTime($campaign->id) }}s / 5s
                                            </span>
                                            </div> --}}


                                    <div
                                        class="absolute top-2 left-2 bg-cyan-600 text-white text-xs font-semibold px-2 py-0.5 rounded shadow z-10 tracking-wide">
                                        FEATURED
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
                                            src="{{ auth_storage_url($campaign?->music?->user?->avatar) }}"
                                            alt="Audio Cure avatar">
                                        <div class="inline-block text-left">
                                            <!-- Trigger -->
                                            <div class="flex items-center gap-1">
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
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                    d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.783-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z">
                                                </path>
                                            </svg>
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
                                                    class="text-sm sm:text-base">{{ $campaign->budget_credits - $campaign->credits_spent }}</span>
                                            </div>
                                            <span class="text-xs text-gray-500 dark:text-gray-500 mt-1">REMAINING</span>
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
                                                <svg width="26" height="18" viewBox="0 0 26 18" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <rect x="1" y="1" width="24" height="16" rx="3"
                                                        fill="none" stroke="currentColor" stroke-width="2" />
                                                    <circle cx="8" cy="9" r="3" fill="none"
                                                        stroke="currentColor" stroke-width="2" />
                                                </svg>
                                                <span>{{ ceil(user()?->userInfo?->followers_count / 100) }}
                                                    Repost</span>
                                            </button>

                                            <!-- Timer Display -->
                                            {{-- @if (!$this->canRepost($campaign->id) && !in_array($campaign->id, $this->playedCampaigns))
                                                <div
                                                    class="absolute -top-8 left-1/2 transform -translate-x-1/2 bg-orange-600 text-white text-xs px-2 py-1 rounded shadow-lg whitespace-nowrap">
                                                    @if ($this->getPlayTime($campaign->id) > 0)
                                                        Listen for {{ $this->getRemainingTime($campaign->id) }}s more
                                                    @else
                                                        Listen for 5 seconds to enable repost
                                                    @endif
                                                </div>
                                            @endif --}}

                                            @if (in_array($campaign->id, $this->repostedCampaigns))
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
                                        {{ $campaign->music->genre ?? 'Unknown Genre' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif

        <!-- Recommended Campaigns Section -->
        @if (count($campaigns) > 0)
            <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200 my-4">Recommended Campaigns</h2>

            @foreach ($campaigns as $campaign)
                <div class="bg-white dark:bg-gray-800 border border-gray-200 mb-4 dark:border-gray-700 shadow-sm">
                    <div class="flex flex-col lg:flex-row" wire:key="campaign-{{ $campaign->id }}">
                        <!-- Left Column - Track Info -->
                        <div
                            class="w-full lg:w-1/2 border-b lg:border-b-0 lg:border-r border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700">
                            <div class="flex flex-col md:flex-row gap-4">
                                <!-- Track Details -->
                                <div class="flex-1 flex flex-col justify-between p-2">
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
                                        <div class="inline-block text-left">
                                            <!-- Trigger -->
                                            <div class="flex items-center gap-1">
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

                                            <!-- Dropdown Menu (you can add Alpine.js back if needed) -->
                                            {{--
                                            <div class="absolute left-0 mt-2 w-56 z-50 shadow-lg bg-gray-900 text-white text-sm p-2 space-y-2 hidden">
                                                <a href="{{ $campaign?->music?->user?->userInfo?->soundcloud_permalink }}"
                                                    class="block hover:bg-gray-800 px-3 py-1 rounded">Visit SoundCloud Profile</a>
                                                <a href="#" class="block hover:bg-gray-800 px-3 py-1 rounded">Visit RepostChain Profile</a>
                                                <button class="block w-full text-left hover:bg-gray-800 px-3 py-1 rounded">Hide all content from this member</button>
                                                <button class="block w-full text-left hover:bg-gray-800 px-3 py-1 rounded">Hide this track</button>
                                            </div>
                                            --}}
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
                                                <span>{{ ceil($campaign?->music?->user?->userInfo?->followers_count / 100) }}
                                                    Repost</span>
                                            </button>

                                            <!-- Timer Display -->
                                            {{-- @if (!$this->canRepost($campaign->id) && !in_array($campaign->id, $this->playedCampaigns))
                                            <div
                                                class="absolute -top-8 left-1/2 transform -translate-x-1/2 bg-orange-600 text-white text-xs px-2 py-1 rounded shadow-lg whitespace-nowrap">
                                                @if ($this->getPlayTime($campaign->id) > 0)
                                                    Listen for {{ $this->getRemainingTime($campaign->id) }}s more
                                                @else
                                                    Listen for 5 seconds to enable repost
                                                @endif
                                            </div>
                                        @endif --}}

                                            @if (in_array($campaign->id, $this->repostedCampaigns))
                                                <div
                                                    class="absolute -top-8 left-1/2 transform -translate-x-1/2 bg-green-600 text-white text-white text-xs px-2 py-1 rounded shadow-lg whitespace-nowrap">
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
                                        {{ $campaign->music->genre ?? 'Unknown Genre' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif

        @if (count($campaigns) == 0 && count($featuredCampaigns) == 0)
            <div class="text-center text-gray-500 dark:text-gray-400 mt-6">
                <p>No campaigns available at the moment.</p>
            </div>
        @endif
    </div>

    <!-- Simple Livewire-SoundCloud Bridge Script -->
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
