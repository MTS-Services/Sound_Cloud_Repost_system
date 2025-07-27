<section wire:poll.1s="updatePlayingTimes">
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

            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm">
                @foreach ($featuredCampaigns as $campaign)
                    <div class="flex flex-col lg:flex-row" wire:key="featured-{{ $campaign->id }}">
                        <!-- Left Column - Track Info -->
                        <div
                            class="w-full lg:w-1/2 border-b lg:border-b-0 lg:border-r border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700">
                            <div class="flex flex-col md:flex-row gap-4">
                                <!-- Track Details -->
                                <div class="flex-1 flex flex-col justify-between p-2 relative">
                                    <!-- Audio Player Simulation -->
                                    <div class="bg-white dark:bg-gray-600 p-4 rounded-lg">
                                        <div class="flex items-center justify-between mb-4">
                                            <h3 class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                                {{ $campaign->music->title ?? 'Track Title' }}
                                            </h3>
                                            <span class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ gmdate('i:s', $this->getPlayTime($campaign->id)) }} / 05:00
                                            </span>
                                        </div>

                                        <!-- Play Controls -->
                                        <div class="flex items-center gap-4">
                                            @if ($this->isPlaying($campaign->id))
                                                <button wire:click="stopPlaying('{{ $campaign->id }}')"
                                                    class="bg-red-500 hover:bg-red-600 text-white p-2 rounded-full transition-colors">
                                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M6 4h4v16H6V4zm8 0h4v16h-4V4z" />
                                                    </svg>
                                                </button>
                                            @else
                                                <button wire:click="startPlaying('{{ $campaign->id }}')"
                                                    class="bg-green-500 hover:bg-green-600 text-white p-2 rounded-full transition-colors">
                                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M8 5v14l11-7z" />
                                                    </svg>
                                                </button>
                                            @endif

                                            <!-- Progress Bar -->
                                            <div class="flex-1 bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                                <div class="bg-blue-500 h-2 rounded-full transition-all duration-300"
                                                    style="width: {{ min(($this->getPlayTime($campaign->id) / 300) * 100, 100) }}%">
                                                </div>
                                            </div>

                                            <!-- Test Button (for development) -->
                                            <button wire:click="simulateAudioProgress('{{ $campaign->id }}', 1)"
                                                class="text-xs bg-gray-500 hover:bg-gray-600 text-white px-2 py-1 rounded transition-colors">
                                                +1s
                                            </button>
                                        </div>

                                        <!-- Play Status -->
                                        <div class="mt-2 text-xs text-gray-600 dark:text-gray-400">
                                            @if ($this->isPlaying($campaign->id))
                                                <span class="text-green-600 font-medium">▶ Playing...</span>
                                            @else
                                                <span class="text-gray-500">⏸ Paused</span>
                                            @endif

                                            @if (in_array($campaign->id, $this->playedCampaigns))
                                                <span class="ml-2 text-green-600 font-medium">✓ Ready to repost</span>
                                            @elseif ($this->getPlayTime($campaign->id) > 0)
                                                <span class="ml-2 text-orange-600 font-medium">
                                                    {{ $this->getRemainingTime($campaign->id) }}s remaining
                                                </span>
                                            @else
                                                <span class="ml-2 text-gray-500">Click play to start</span>
                                            @endif
                                        </div>
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
                                        <img class="w-14 h-14 rounded-full object-cover border-2 border-gray-200 dark:border-gray-600"
                                            src="{{ auth_storage_url($campaign?->music?->user?->avatar) }}"
                                            alt="{{ $campaign?->music?->user?->name }} avatar">

                                        <div class="inline-block text-left">
                                            <div class="flex items-center gap-1">
                                                <span class="text-slate-700 dark:text-gray-300 font-medium">
                                                    {{ $campaign?->music?->user?->name }}
                                                </span>
                                                <svg class="w-4 h-4 text-gray-600 dark:text-gray-400" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                </svg>
                                            </div>
                                            <div class="flex items-center mt-1">
                                                <svg class="w-5 h-5 text-yellow-400" fill="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path
                                                        d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.783-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                                </svg>
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
                                                <span class="text-sm sm:text-base font-medium">
                                                    {{ $campaign->budget_credits - $campaign->credits_spent }}
                                                </span>
                                            </div>
                                            <span
                                                class="text-xs text-gray-500 dark:text-gray-500 mt-1 font-medium">REMAINING</span>
                                        </div>

                                        <div class="relative">
                                            <!-- Repost Button -->
                                            <button wire:click="repost('{{ $campaign->id }}')"
                                                @class([
                                                    'flex items-center gap-2 py-2 px-3 sm:px-5 sm:pl-8 rounded-md shadow-sm text-sm sm:text-base transition-all duration-200',
                                                    'bg-green-100 dark:bg-green-700 hover:bg-green-200 dark:hover:bg-green-600 text-green-700 dark:text-green-300 cursor-pointer border border-green-300 dark:border-green-600' => $this->canRepost(
                                                        $campaign->id),
                                                    'bg-gray-300 dark:bg-gray-600 text-gray-500 dark:text-gray-400 cursor-not-allowed border border-gray-400 dark:border-gray-500' => !$this->canRepost(
                                                        $campaign->id),
                                                ]) @disabled(!$this->canRepost($campaign->id))>
                                                <svg width="16" height="16" viewBox="0 0 26 18" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <rect x="1" y="1" width="24" height="16" rx="3"
                                                        fill="none" stroke="currentColor" stroke-width="2" />
                                                    <circle cx="8" cy="9" r="3" fill="none"
                                                        stroke="currentColor" stroke-width="2" />
                                                </svg>
                                                <span
                                                    class="font-medium">{{ ceil(user()?->userInfo?->followers_count / 100) }}
                                                    Repost</span>
                                            </button>

                                            <!-- Status Messages -->
                                            @if (!$this->canRepost($campaign->id) && !in_array($campaign->id, $this->playedCampaigns))
                                                <div
                                                    class="absolute -top-10 left-1/2 transform -translate-x-1/2 bg-orange-600 text-white text-xs px-3 py-2 rounded-md shadow-lg whitespace-nowrap z-20">
                                                    @if ($this->getPlayTime($campaign->id) > 0)
                                                        Listen for {{ $this->getRemainingTime($campaign->id) }}s more
                                                    @else
                                                        Click play and listen for 5 seconds
                                                    @endif
                                                    <div
                                                        class="absolute top-full left-1/2 transform -translate-x-1/2 w-0 h-0 border-l-4 border-r-4 border-t-4 border-transparent border-t-orange-600">
                                                    </div>
                                                </div>
                                            @endif

                                            @if (in_array($campaign->id, $this->repostedCampaigns))
                                                <div
                                                    class="absolute -top-10 left-1/2 transform -translate-x-1/2 bg-green-600 text-white text-xs px-3 py-2 rounded-md shadow-lg whitespace-nowrap z-20">
                                                    Successfully Reposted! ✓
                                                    <div
                                                        class="absolute top-full left-1/2 transform -translate-x-1/2 w-0 h-0 border-l-4 border-r-4 border-t-4 border-transparent border-t-green-600">
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- Genre Badge -->
                                <div class="mt-auto">
                                    <span
                                        class="inline-block bg-gradient-to-r from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-600 text-gray-700 dark:text-gray-300 text-sm font-medium px-4 py-2 rounded-full shadow-sm border border-gray-200 dark:border-gray-600">
                                        {{ $campaign->music->genre ?? 'Unknown Genre' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <!-- Recommended Campaigns Section -->
        @if (count($campaigns) > 0)
            <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200 my-6">Recommended Campaigns</h2>

            <div
                class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm rounded-lg overflow-hidden">
                @foreach ($campaigns as $campaign)
                    <div class="flex flex-col lg:flex-row border-b border-gray-200 dark:border-gray-700 last:border-b-0"
                        wire:key="campaign-{{ $campaign->id }}">
                        <!-- Left Column - Track Info -->
                        <div
                            class="w-full lg:w-1/2 border-b lg:border-b-0 lg:border-r border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700">
                            <div class="flex flex-col md:flex-row gap-4">
                                <!-- Track Details -->
                                <div class="flex-1 flex flex-col justify-between p-4">
                                    <!-- Audio Player Simulation -->
                                    <div class="bg-white dark:bg-gray-600 p-4 rounded-lg shadow-sm">
                                        <div class="flex items-center justify-between mb-4">
                                            <h3
                                                class="text-sm font-medium text-gray-900 dark:text-white truncate pr-2">
                                                {{ $campaign->music->title ?? 'Track Title' }}
                                            </h3>
                                            <span class="text-xs text-gray-500 dark:text-gray-400 font-mono">
                                                {{ gmdate('i:s', $this->getPlayTime($campaign->id)) }} / 05:00
                                            </span>
                                        </div>

                                        <!-- Play Controls -->
                                        <div class="flex items-center gap-4">
                                            @if ($this->isPlaying($campaign->id))
                                                <button wire:click="stopPlaying('{{ $campaign->id }}')"
                                                    class="bg-red-500 hover:bg-red-600 text-white p-2 rounded-full transition-colors shadow-md">
                                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M6 4h4v16H6V4zm8 0h4v16h-4V4z" />
                                                    </svg>
                                                </button>
                                            @else
                                                <button wire:click="startPlaying('{{ $campaign->id }}')"
                                                    class="bg-green-500 hover:bg-green-600 text-white p-2 rounded-full transition-colors shadow-md">
                                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M8 5v14l11-7z" />
                                                    </svg>
                                                </button>
                                            @endif

                                            <!-- Progress Bar -->
                                            <div
                                                class="flex-1 bg-gray-200 dark:bg-gray-700 rounded-full h-3 shadow-inner">
                                                <div class="bg-gradient-to-r from-blue-400 to-blue-600 h-3 rounded-full transition-all duration-300 shadow-sm"
                                                    style="width: {{ min(($this->getPlayTime($campaign->id) / 300) * 100, 100) }}%">
                                                </div>
                                            </div>

                                            <!-- Test Button (for development) -->
                                            <button wire:click="simulateAudioProgress('{{ $campaign->id }}', 1)"
                                                class="text-xs bg-gray-500 hover:bg-gray-600 text-white px-3 py-1 rounded-md transition-colors shadow-sm">
                                                +1s
                                            </button>
                                        </div>

                                        <!-- Play Status -->
                                        <div
                                            class="mt-3 text-xs text-gray-600 dark:text-gray-400 flex items-center justify-between">
                                            <div class="flex items-center">
                                                @if ($this->isPlaying($campaign->id))
                                                    <span class="text-green-600 font-medium flex items-center">
                                                        <svg class="w-3 h-3 mr-1" fill="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path d="M8 5v14l11-7z" />
                                                        </svg>
                                                        Playing...
                                                    </span>
                                                @else
                                                    <span class="text-gray-500 flex items-center">
                                                        <svg class="w-3 h-3 mr-1" fill="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path d="M6 4h4v16H6V4zm8 0h4v16h-4V4z" />
                                                        </svg>
                                                        Paused
                                                    </span>
                                                @endif
                                            </div>

                                            <div>
                                                @if (in_array($campaign->id, $this->playedCampaigns))
                                                    <span class="text-green-600 font-medium flex items-center">
                                                        <svg class="w-3 h-3 mr-1" fill="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                        Ready to repost
                                                    </span>
                                                @elseif ($this->getPlayTime($campaign->id) > 0)
                                                    <span class="text-orange-600 font-medium">
                                                        {{ $this->getRemainingTime($campaign->id) }}s remaining
                                                    </span>
                                                @else
                                                    <span class="text-gray-500">Click play to start</span>
                                                @endif
                                            </div>
                                        </div>
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
                                        <img class="w-14 h-14 rounded-full object-cover border-2 border-gray-200 dark:border-gray-600 shadow-md"
                                            src="{{ asset('default_img/other.png') }}"
                                            alt="{{ $campaign?->music?->user?->name }} avatar">

                                        <div class="inline-block text-left">
                                            <div class="flex items-center gap-1">
                                                <span class="text-slate-700 dark:text-gray-300 font-medium">
                                                    {{ $campaign?->music?->user?->name }}
                                                </span>
                                                <svg class="w-4 h-4 text-gray-600 dark:text-gray-400" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                </svg>
                                            </div>
                                            <div class="flex items-center mt-1">
                                                <svg class="w-5 h-5 text-yellow-400" fill="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path
                                                        d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.783-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                                </svg>
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
                                                <span class="text-sm sm:text-base font-medium">
                                                    {{ $campaign->budget_credits - $campaign->credits_spent }}
                                                </span>
                                            </div>
                                            <span
                                                class="text-xs text-gray-500 dark:text-gray-500 mt-1 font-medium">REMAINING</span>
                                        </div>

                                        <div class="relative">
                                            <!-- Repost Button -->
                                            <button wire:click="repost('{{ $campaign->id }}')"
                                                @class([
                                                    'flex items-center gap-2 py-2 px-3 sm:px-5 sm:pl-8 rounded-md shadow-sm text-sm sm:text-base transition-all duration-200',
                                                    'bg-green-100 dark:bg-green-700 hover:bg-green-200 dark:hover:bg-green-600 text-green-700 dark:text-green-300 cursor-pointer border border-green-300 dark:border-green-600' => $this->canRepost(
                                                        $campaign->id),
                                                    'bg-gray-300 dark:bg-gray-600 text-gray-500 dark:text-gray-400 cursor-not-allowed border border-gray-400 dark:border-gray-500' => !$this->canRepost(
                                                        $campaign->id),
                                                ]) @disabled(!$this->canRepost($campaign->id))>
                                                <svg width="16" height="16" viewBox="0 0 26 18"
                                                    fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <rect x="1" y="1" width="24" height="16" rx="3"
                                                        fill="none" stroke="currentColor" stroke-width="2" />
                                                    <circle cx="8" cy="9" r="3" fill="none"
                                                        stroke="currentColor" stroke-width="2" />
                                                </svg>
                                                <span
                                                    class="font-medium">{{ ceil($campaign?->music?->user?->userInfo?->followers_count / 100) }}
                                                    Repost</span>
                                            </button>

                                            <!-- Status Messages -->
                                            @if (!$this->canRepost($campaign->id) && !in_array($campaign->id, $this->playedCampaigns))
                                                <div
                                                    class="absolute -top-10 left-1/2 transform -translate-x-1/2 bg-orange-600 text-white text-xs px-3 py-2 rounded-md shadow-lg whitespace-nowrap z-20">
                                                    @if ($this->getPlayTime($campaign->id) > 0)
                                                        Listen for {{ $this->getRemainingTime($campaign->id) }}s more
                                                    @else
                                                        Click play and listen for 5 seconds
                                                    @endif
                                                    <div
                                                        class="absolute top-full left-1/2 transform -translate-x-1/2 w-0 h-0 border-l-4 border-r-4 border-t-4 border-transparent border-t-orange-600">
                                                    </div>
                                                </div>
                                            @endif

                                            @if (in_array($campaign->id, $this->repostedCampaigns))
                                                <div
                                                    class="absolute -top-10 left-1/2 transform -translate-x-1/2 bg-green-600 text-white text-xs px-3 py-2 rounded-md shadow-lg whitespace-nowrap z-20">
                                                    Successfully Reposted! ✓
                                                    <div
                                                        class="absolute top-full left-1/2 transform -translate-x-1/2 w-0 h-0 border-l-4 border-r-4 border-t-4 border-transparent border-t-green-600">
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- Genre Badge -->
                                <div class="mt-auto">
                                    <span
                                        class="inline-block bg-gradient-to-r from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-600 text-gray-700 dark:text-gray-300 text-sm font-medium px-4 py-2 rounded-full shadow-sm border border-gray-200 dark:border-gray-600">
                                        {{ $campaign->music->genre ?? 'Unknown Genre' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <!-- No Campaigns Message -->
        @if (count($campaigns) == 0 && count($featuredCampaigns) == 0)
            <div class="text-center text-gray-500 dark:text-gray-400 mt-12 py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v3m0 0v3m0-3h3m-3 0H9m6-6a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="text-lg font-semibold">No campaigns available at the moment.</p>
                <p class="mt-2">Please check back later or create a new campaign.</p>
            </div>
        @endif










    </div>


</section>
