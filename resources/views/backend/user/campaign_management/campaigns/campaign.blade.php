<section>
    <x-slot name="page_slug">campaign-feed</x-slot>

    <!-- Header Section -->
    <div class=" w-full  mt-6">
        <!-- Header Tabs & Button -->
        <div
            class="flex flex-col sm:flex-row items-center justify-between px-2 sm:px-4 pt-3 border-b border-b-gray-200 gap-2 sm:gap-0">

            <div x-data="{ showInput: false }"
                class="flex items-center w-full sm:w-auto justify-between sm:justify-start space-x-2 text-gray-600">
                <div @click="showInput = !showInput"
                    :class="!showInput ? 'flex items-center space-x-2 px-2 sm:px-7 cursor-pointer whitespace-nowrap' : 'hidden'">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21 21l-4.35-4.35M17 10a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <span>Search by tag</span>
                </div>

                <div x-show="showInput" class="relative w-full sm:w-auto">
                    <input type="text" placeholder="Search by tag"
                        class="border py-2 border-red-500 pl-7 pr-2 rounded focus:outline-none focus:ring-1 focus:ring-red-400 w-full"
                        @click.outside="showInput = false" autofocus />
                    <svg class="w-4 h-4 absolute left-2 top-3 text-gray-500" fill="none" stroke="currentColor"
                        stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21 21l-4.35-4.35M17 10a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </div>

            <button
                class="bg-orange-600 text-white px-3 sm:px-5 py-2 mb-2 rounded hover:bg-orange-700 transition w-full sm:w-auto text-center">
                Start a new campaign
            </button>
        </div>
    </div>

    <div class="container mx-auto px-4 py-6" x-data="campaignManager()" x-init="init()">
        <!-- Featured Campaign Section -->
        @if (count($featuredCampaigns) > 0)
            <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Featured campaigns</h2>

            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm ">
                @foreach ($featuredCampaigns as $campaign)
                    <div class="flex flex-col lg:flex-row" x-data="{ campaignId: '{{ $campaign->id }}' }">
                        <!-- Left Column - Track Info -->
                        <div
                            class="w-full lg:w-1/2  border-b lg:border-b-0 lg:border-r border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700">
                            <div class="flex flex-col md:flex-row gap-4">
                                <!-- Track Details -->
                                <div class="flex-1 flex flex-col justify-between p-2 relative">
                                    <div id="soundcloud-player-{{ $campaign->id }}"
                                        data-campaign-id="{{ $campaign->id }}">
                                        <x-sound-cloud.sound-cloud-player :track="$campaign->music" :height="166"
                                            :visual="false" />
                                    </div>
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
                                        <div x-data="{ open: false }" class=" inline-block text-left">
                                            <!-- Trigger -->
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
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                    d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.783-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z">
                                                </path>
                                            </svg>

                                            <!-- Dropdown Menu -->
                                            <div x-show="open" @click.outside="open = false" x-transition
                                                class="absolute left-0 mt-2 w-56 z-50 shadow-lg bg-gray-900 gr text-white text-sm p-2 space-y-2">
                                                <a href="#"
                                                    class="block hover:bg-gray-800 px-3 py-1 rounded">Visit
                                                    SoundCloud Profile</a>
                                                <a href="#"
                                                    class="block hover:bg-gray-800 px-3 py-1 rounded">Visit
                                                    RepostExchange Profile</a>
                                                <button
                                                    class="block w-full text-left hover:bg-gray-800 px-3 py-1 rounded">Hide
                                                    all content from this member</button>
                                                <button
                                                    class="block w-full text-left hover:bg-gray-800 px-3 py-1 rounded">Hide
                                                    this track</button>
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
                                                    class="text-sm sm:text-base">{{ $campaign->budget_credits - $campaign->credits_spent }}</span>
                                            </div>
                                            <span class="text-xs text-gray-500 dark:text-gray-500 mt-1">REMAINING</span>
                                        </div>

                                        <div class="relative">
                                            <!-- Repost Button -->
                                            <button :disabled="!canRepost(campaignId)"
                                                :class="canRepost(campaignId) ?
                                                    'bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 cursor-pointer' :
                                                    'bg-gray-300 dark:bg-gray-600 text-gray-500 dark:text-gray-400 cursor-not-allowed'"
                                                class="flex items-center gap-2 py-2 px-3 sm:px-5 sm:pl-8 rounded-md shadow-sm text-sm sm:text-base transition-colors"
                                                @click="repost(campaignId)">
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
                                            <div x-show="!canRepost(campaignId) && isPlaying(campaignId) && getPlayTime(campaignId) < 5"
                                                class="absolute -top-8 left-1/2 transform -translate-x-1/2 bg-orange-600 text-white text-xs px-2 py-1 rounded shadow-lg whitespace-nowrap">
                                                Listen for <span
                                                    x-text="Math.ceil(5 - getPlayTime(campaignId))"></span>s more
                                            </div>
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
                @endforeach
            </div>
        @endif

        @if (count($campaigns) > 0)
            <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200 my-4">Recommended Campaigns</h2>

            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm ">
                @foreach ($campaigns as $campaign)
                    <div class="flex flex-col lg:flex-row" x-data="{ campaignId: '{{ $campaign->id }}' }">
                        <!-- Left Column - Track Info -->
                        <div
                            class="w-full lg:w-1/2  border-b lg:border-b-0 lg:border-r border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700">
                            <div class="flex flex-col md:flex-row gap-4">
                                <!-- Track Details -->
                                <div class="flex-1 flex flex-col justify-between p-2">
                                    <div id="soundcloud-player-{{ $campaign->id }}"
                                        data-campaign-id="{{ $campaign->id }}">
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
                                            src="{{ asset('default_img/other.png') }}" alt="Audio Cure avatar">
                                        <div x-data="{ open: false }" class=" inline-block text-left">
                                            <!-- Trigger -->
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

                                            <!-- Dropdown Menu -->
                                            <div x-show="open" @click.outside="open = false" x-transition
                                                class="absolute left-0 mt-2 w-56 z-50 shadow-lg bg-gray-900 gr text-white text-sm p-2 space-y-2">
                                                <a href="{{ $campaign?->music?->user?->userInfo?->soundcloud_permalink }}"
                                                    class="block hover:bg-gray-800 px-3 py-1 rounded">Visit
                                                    SoundCloud Profile</a>
                                                <a href="#"
                                                    class="block hover:bg-gray-800 px-3 py-1 rounded">Visit
                                                    RepostChain Profile</a>
                                                <button
                                                    class="block w-full text-left hover:bg-gray-800 px-3 py-1 rounded">Hide
                                                    all content from this member</button>
                                                <button
                                                    class="block w-full text-left hover:bg-gray-800 px-3 py-1 rounded">Hide
                                                    this track</button>
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
                                            <button :disabled="!canRepost(campaignId)"
                                                :class="canRepost(campaignId) ?
                                                    'bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 cursor-pointer' :
                                                    'bg-gray-300 dark:bg-gray-600 text-gray-500 dark:text-gray-400 cursor-not-allowed'"
                                                class="flex items-center gap-2 py-2 px-3 sm:px-5 sm:pl-8 rounded-md shadow-sm text-sm sm:text-base transition-colors"
                                                @click="repost(campaignId)">
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
                                            <div x-show="!canRepost(campaignId) && isPlaying(campaignId) && getPlayTime(campaignId) < 5"
                                                class="absolute -top-8 left-1/2 transform -translate-x-1/2 bg-orange-600 text-white text-xs px-2 py-1 rounded shadow-lg whitespace-nowrap">
                                                Listen for <span
                                                    x-text="Math.ceil(5 - getPlayTime(campaignId))"></span>s more
                                            </div>
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
                @endforeach
            </div>
        @endif

        @if (count($campaigns) == 0 && count($featuredCampaigns) == 0)
            <div class="text-center text-gray-500 dark:text-gray-400 mt-6">
                <p>No campaigns available at the moment.</p>
            </div>
        @endif
    </div>

    @push('js')
        <script>
            function campaignManager() {
                return {
                    // Object to store state for each campaign
                    campaigns: {},
                    widgets: {},

                    // Initialize campaign state
                    initCampaign(campaignId) {
                        if (!this.campaigns[campaignId]) {
                            this.campaigns[campaignId] = {
                                isPlaying: false,
                                playTime: 0,
                                canRepost: false,
                                playTimer: null,
                                hasReposted: false,
                                widget: null
                            };
                        }
                        return this.campaigns[campaignId];
                    },

                    // Initialize SoundCloud widgets
                    init() {
                        // Wait for SoundCloud Widget API to be ready
                        this.initializeSoundCloudWidgets();
                    },

                    initializeSoundCloudWidgets() {
                        // Check if SC is available
                        if (typeof SC === 'undefined') {
                            console.log('SoundCloud Widget API not loaded yet, retrying...');
                            setTimeout(() => this.initializeSoundCloudWidgets(), 500);
                            return;
                        }

                        console.log('Initializing SoundCloud widgets...');

                        // Find all SoundCloud player containers
                        const playerContainers = document.querySelectorAll('[id^="soundcloud-player-"]');

                        playerContainers.forEach(container => {
                            const campaignId = container.dataset.campaignId;
                            const iframe = container.querySelector('iframe');

                            if (iframe && campaignId) {
                                console.log(`Setting up widget for campaign: ${campaignId}`);

                                // Create widget instance
                                const widget = SC.Widget(iframe);
                                this.widgets[campaignId] = widget;

                                // Initialize campaign
                                const campaign = this.initCampaign(campaignId);
                                campaign.widget = widget;

                                // Bind events
                                widget.bind(SC.Widget.Events.PLAY, () => {
                                    console.log(`Play event for campaign: ${campaignId}`);
                                    this.handlePlay(campaignId);
                                });

                                widget.bind(SC.Widget.Events.PAUSE, () => {
                                    console.log(`Pause event for campaign: ${campaignId}`);
                                    this.handlePause(campaignId);
                                });

                                widget.bind(SC.Widget.Events.FINISH, () => {
                                    console.log(`Finish event for campaign: ${campaignId}`);
                                    this.handlePause(campaignId);
                                });

                                // Set up position tracking
                                widget.bind(SC.Widget.Events.PLAY_PROGRESS, (data) => {
                                    const currentTime = data.currentPosition / 1000; // Convert to seconds
                                    this.handleTimeUpdate(campaignId, currentTime);
                                });
                            }
                        });
                    },

                    // Play handler
                    handlePlay(campaignId) {
                        const campaign = this.initCampaign(campaignId);
                        campaign.isPlaying = true;
                        this.startTimer(campaignId);
                    },

                    // Pause handler
                    handlePause(campaignId) {
                        const campaign = this.initCampaign(campaignId);
                        campaign.isPlaying = false;
                        this.stopTimer(campaignId);
                    },

                    // Time update handler
                    handleTimeUpdate(campaignId, currentTime) {
                        const campaign = this.initCampaign(campaignId);
                        if (currentTime && currentTime > 0) {
                            campaign.playTime = currentTime;
                            if (campaign.playTime >= 5) {
                                campaign.canRepost = true;
                                this.stopTimer(campaignId);
                            }
                        }
                    },

                    // Start timer for a campaign
                    startTimer(campaignId) {
                        const campaign = this.initCampaign(campaignId);

                        // Clear existing timer if any
                        this.stopTimer(campaignId);

                        campaign.playTimer = setInterval(() => {
                            if (campaign.isPlaying) {
                                campaign.playTime += 0.1;
                                if (campaign.playTime >= 5 && !campaign.canRepost) {
                                    campaign.canRepost = true;
                                    this.stopTimer(campaignId);
                                    console.log(`Repost enabled for campaign: ${campaignId}`);
                                }
                            }
                        }, 100);
                    },

                    // Stop timer for a campaign
                    stopTimer(campaignId) {
                        const campaign = this.campaigns[campaignId];
                        if (campaign && campaign.playTimer) {
                            clearInterval(campaign.playTimer);
                            campaign.playTimer = null;
                        }
                    },

                    // Helper methods for template
                    canRepost(campaignId) {
                        const campaign = this.campaigns[campaignId];
                        return campaign ? campaign.canRepost && !campaign.hasReposted : false;
                    },

                    isPlaying(campaignId) {
                        const campaign = this.campaigns[campaignId];
                        return campaign ? campaign.isPlaying : false;
                    },

                    getPlayTime(campaignId) {
                        const campaign = this.campaigns[campaignId];
                        return campaign ? campaign.playTime : 0;
                    },

                    // Repost function
                    repost(campaignId) {
                        const campaign = this.initCampaign(campaignId);

                        if (!campaign.canRepost || campaign.hasReposted) {
                            console.log(`Repost not allowed for campaign: ${campaignId}`, {
                                canRepost: campaign.canRepost,
                                hasReposted: campaign.hasReposted
                            });
                            return;
                        }

                        console.log(`Attempting repost for campaign: ${campaignId}`);

                        // Mark as reposted to prevent multiple reposts
                        campaign.hasReposted = true;

                        // Make API call to your Laravel backend
                        fetch(`/repost/${campaignId}`, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute(
                                        'content') || '',
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify({
                                    campaign_id: campaignId
                                })
                            })
                            .then(response => response.json())
                            .then(data => {
                                console.log('Repost response:', data);
                                if (data.success) {
                                    this.showNotification('Track reposted successfully!', 'success');
                                } else {
                                    // Reset hasReposted on failure
                                    campaign.hasReposted = false;
                                    this.showNotification(data.message || 'Failed to repost track', 'error');
                                }
                            })
                            .catch(error => {
                                console.error('Repost failed:', error);
                                // Reset hasReposted on error
                                campaign.hasReposted = false;
                                this.showNotification('Failed to repost track. Please try again.', 'error');
                            });
                    },

                    // Simple notification system
                    showNotification(message, type = 'info') {
                        if (type === 'success') {
                            alert('✅ ' + message);
                        } else if (type === 'error') {
                            alert('❌ ' + message);
                        } else {
                            alert(message);
                        }
                    }
                }
            }

            // Initialize when DOM is ready
            document.addEventListener('DOMContentLoaded', function() {
                console.log('DOM loaded, waiting for SoundCloud API...');
            });
        </script>
    @endpush
</section>
