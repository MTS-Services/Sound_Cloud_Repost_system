<div>
    <x-slot name="page_slug">dashboard</x-slot>
    <div id="content-dashboard" class="page-content py-2 px-2">
        <div
            class="tablet:px-2 px-0.5 py-4 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 sm:gap-0 w-full">
            <!-- Left: Title and subtitle -->
            <div class="w-full sm:w-auto">
                <h2 class="text-2xl text-black dark:text-white font-semibold mb-2">Dashboard</h2>
                <p class="dark:text-slate-300 text-gray-600">
                    Welcome back!
                    <span class="font-semibold">{{ auth()->user()->name ?? '' }}</span>.
                    Here's an overview of your RepostChain activity.
                </p>
            </div>
            <!-- Right: Buttons group -->
            <div class="flex flex-col lg:flex-row gap-3 sm:gap-2 w-full sm:w-auto">
                <!-- Earn Credits -->
                <x-gbutton variant="secondary" wire:navigate href="{{ route('user.cm.campaigns') }}">
                    <span>💰</span>{{ __('Earn Credits') }}
                </x-gbutton>
                <!-- Submit Track -->
                <x-gbutton variant="primary" wire:click="toggleCampaignsModal">
                    <span>
                        <x-lucide-plus class="inline-block text-center h-4 w-4 text-white mr-1" />
                    </span>{{ __('Start a new campaign') }}
                </x-gbutton>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
            <div
                class="bg-white dark:bg-slate-800 rounded-lg shadow-sm p-6 hover:-translate-y-2 transition-all duration-500 ease-in-out">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-gray-500 dark:text-white text-sm font-medium">Available Credits</h3>
                    <div class="p-2 rounded-lg bg-yellow-500/20 text-yellow-500">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="lucide lucide-zap w-5 h-5">
                            <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon>
                        </svg>
                    </div>
                </div>
                <div class="space-y-2">
                    <p class="text-2xl font-bold text-slate-700 dark:text-white">{{ userCredits() }}</p>
                    @if ($creditPercentage >= 0)
                        <p class="text-sm flex items-center space-x-1 text-green-400">
                            <span>+{{ $creditPercentage }}% from last week</span>
                        </p>
                    @elseif($creditPercentage < 0)
                        <p class="text-sm flex items-center space-x-1 text-red-400">
                            <span>{{ $creditPercentage }}% from last week</span>
                        </p>
                    @else
                        <p class="text-sm flex items-center space-x-1 text-gray-400">
                            <span>0% from last week</span>
                        </p>
                    @endif
                </div>
            </div>

            <div
                class="bg-white dark:bg-slate-800 rounded-lg shadow-sm p-6 hover:-translate-y-2 transition-all duration-500 ease-in-out">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-gray-500 dark:text-white text-sm font-medium">Active Campaigns</h3>
                    <div class="p-2 rounded-lg bg-blue-500/20 text-blue-500">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="lucide lucide-music2 w-5 h-5">
                            <circle cx="8" cy="18" r="4"></circle>
                            <path d="M12 18V2l7 4"></path>
                        </svg>
                    </div>
                </div>
                <div class="space-y-2">
                    <p class="text-2xl font-bold text-slate-700 dark:text-white">{{ $totalCams }}</p>
                    @if ($campaignPercentage >= 0)
                        <p class="text-sm flex items-center space-x-1 text-green-400">
                            <span>+{{ $campaignPercentage }}% from last week</span>
                        </p>
                    @elseif($campaignPercentage < 0)
                        <p class="text-sm flex items-center space-x-1 text-red-400">
                            <span>{{ $campaignPercentage }}% from last week</span>
                        </p>
                    @else
                        <p class="text-sm flex items-center space-x-1 text-gray-400">
                            <span>0% from last week</span>
                        </p>
                    @endif
                </div>
            </div>

            <div
                class="bg-white dark:bg-slate-800 rounded-lg shadow-sm p-6 hover:-translate-y-2 transition-all duration-500 ease-in-out">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-gray-500 dark:text-white text-sm font-medium">Reposts Received</h3>
                    <div class="p-2 rounded-lg bg-green-500/20 text-green-500">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="lucide lucide-repeat2 w-5 h-5">
                            <path d="m2 9 3-3 3 3"></path>
                            <path d="M13 18H7a2 2 0 0 1-2-2V6"></path>
                            <path d="m22 15-3 3-3-3"></path>
                            <path d="M11 6h6a2 2 0 0 1 2 2v10"></path>
                        </svg>
                    </div>
                </div>
                <div class="space-y-2">
                    <p class="text-2xl font-bold text-slate-700 dark:text-white">{{ $totalCount }}</p>
                    @if ($repostRequestPercentage >= 0)
                        <p class="text-sm flex items-center space-x-1 text-green-400">
                            <span>+{{ $repostRequestPercentage }}% from last week</span>
                        </p>
                    @elseif($repostRequestPercentage < 0)
                        <p class="text-sm flex items-center space-x-1 text-red-400">
                            <span>{{ $repostRequestPercentage }}% from last week</span>
                        </p>
                    @else
                        <p class="text-sm flex items-center space-x-1 text-gray-400">
                            <span>0% from last week</span>
                        </p>
                    @endif
                </div>
            </div>

            <div
                class="bg-white dark:bg-slate-800 rounded-lg shadow-sm p-6 hover:-translate-y-2 transition-all duration-500 ease-in-out">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-gray-500 dark:text-white text-sm font-medium">Credibility Score</h3>
                    <div class="p-2 rounded-lg bg-purple-500/20 text-purple-500">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="lucide lucide-award w-5 h-5">
                            <circle cx="12" cy="8" r="6"></circle>
                            <path d="M15.477 12.89 17 22l-5-3-5 3 1.523-9.11"></path>
                        </svg>
                    </div>
                </div>
                <div class="space-y-2">
                    <p class="text-2xl font-bold text-slate-700 dark:text-white">82%</p>
                    <p class="text-sm flex items-center space-x-1 text-green-400"><span>+3% from last week</span></p>
                </div>
            </div>
        </div>
    </div>

    <div class="p-2">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 dark:text-white">
            <!-- Left Section -->
            <div class="lg:col-span-2 rounded-lg p-4 shadow-sm dark:bg-slate-800">
                <div class="flex flex-col sm:flex-row justify-between sm:items-center p-2">
                    <div>
                        <h2 class="dark:text-white text-lg font-semibold">Performance Overview</h2>
                        <p class="text-sm text-slate-400">Track the impact of your campaigns</p>
                    </div>
                    <a href="#"
                        class="text-orange-400 text-sm font-medium mt-4 sm:mt-0 hover:text-orange-300 transition-colors">
                        View all →
                    </a>
                </div>
                <div class="h-80 sm:h-96">
                    <canvas id="campaignChart" width="961" height="384"></canvas>
                </div>
            </div>

            <!-- Right Section -->
            <div class="rounded-lg p-4 sm:p-6 shadow-sm dark:bg-slate-800">
                <h3 class="dark:text-white text-lg font-semibold">Genre Distribution</h3>
                <p class="text-slate-400 text-sm mb-2">What your audience listens to</p>
                <div class="h-60 sm:h-96 flex flex-col justify-between">
                    <div class="flex-grow flex items-center justify-center my-4">
                        <div
                            class="bg-slate-700/50 rounded-full w-36 h-36 sm:w-40 sm:h-40 flex items-center justify-center">
                            <img src="https://imgs.search.brave.com/2rHUZ109YlFZLs4tiya8jxlxjLsE_WEUoUMpvFfZANQ/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9tLm1l/ZGlhLWFtYXpvbi5j/b20vaW1hZ2VzL0kv/NzFRTnBFbDhjckwu/anBn"
                                alt="">
                        </div>
                    </div>
                    <div class="flex flex-wrap justify-center gap-x-2 gap-y-2 text-xs">
                        @foreach (user()->genres as $genre)
                            <div class="flex items-center gap-2">
                                <span class="text-slate-400">{{ $genre->genre }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Left Section -->
            <div class="lg:col-span-2 rounded-lg p-4 shadow-sm dark:bg-slate-800">
                <div class="flex items-center justify-between p-4">
                    <div>
                        <h3 class="text-lg font-semibold">Recent Tracks</h3>
                        <p class="text-slate-400 text-sm">Your latest submissions</p>
                    </div>
                    @if ($recentTracks->count() > 0)
                        <a class="text-orange-500 hover:text-orange-400 text-sm font-medium" wire:navigate
                            href="{{ route('user.my-account', ['tab' => 'tracks']) }}">View all →</a>
                    @endif
                </div>
                <div class="space-y-4">
                    @foreach ($recentTracks as $recentTrack)
                        <x-sound-cloud.sound-cloud-player :track="$recentTrack" :visual="false" />
                    @endforeach
                </div>
                <div class="text-center py-8 ">
                    <div class="w-16 h-16 mx-auto mb-4 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-slate-400" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path d="M5 12h14M12 5v14" />
                        </svg>
                    </div>
                    <h4 class="font-medium mb-2">No upcoming campaigns scheduled</h4>
                    <p class="text-slate-400 text-sm mb-4">Submit a track to start a new campaign</p>
                    <x-gbutton variant="primary" wire:navigate href="{{ route('user.cm.my-campaigns') }}">
                        <span><x-lucide-plus class="inline-block text-center h-4 w-4 text-white mr-1" /></span>
                        Create Campaign
                    </x-gbutton>
                </div>
            </div>

            <!-- Right Section -->
            <div class="rounded-lg shadow-sm p-4 dark:bg-slate-800">
                <div class="flex items-center justify-between p-2">
                    <div>
                        <h3 class="text-lg font-semibold">Latest Repost Requests</h3>
                    </div>
                    @if ($repostRequests->count() > 0)
                        <a class="text-orange-500 hover:text-orange-400 text-sm font-medium"
                            href="{{ route('user.reposts-request') }}">View all →</a>
                    @endif
                </div>
                @foreach ($repostRequests as $request)
                    <div class="space-y-4">
                        <div class="shadow-sm rounded-lg p-4">
                            <div class="flex items-start space-x-3 mb-3">
                                <img src="https://images.pexels.com/photos/1040881/pexels-photo-1040881.jpeg"
                                    class="w-8 h-8 rounded-full" alt="">
                                <div class="flex-1">
                                    <h4 class="text-sm font-medium">{{ $request?->requester?->name }}</h4>
                                    <p class="text-slate-400 text-xs">by {{ $request?->requester?->email }}</p>
                                </div>
                                <span
                                    class="text-orange-500 font-semibold text-sm">+{{ $request->credits_spent ?? '0' }}
                                    credits</span>
                            </div>
                            <div class="flex space-x-2">
                                <div class="flex-1">
                                    <x-gbutton variant="secondary" full-width="true"
                                        wire:click="declineRepost('{{ encrypt($request->id) }}')">Decline</x-gbutton>
                                </div>
                                <div class="flex-1">
                                    <x-gbutton variant="primary" full-width="true"
                                        wire:click="directRepost('{{ encrypt($request->id) }}')">Repost</x-gbutton>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-6 pt-6  p-2">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center space-x-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-orange-500"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <polyline points="22 7 13.5 15.5 8.5 10.5 2 17" />
                                    <polyline points="16 7 22 7 22 13" />
                                </svg>
                                <span class="text-sm font-medium">Trending</span>
                            </div>
                            <a class="text-orange-500 hover:text-orange-400 text-sm" href="/charts">View
                                charts</a>
                        </div>
                        <div class="space-y-2">
                            <div class="flex justify-between text-sm">
                                <div class="flex items-center space-x-2">
                                    <span class="text-orange-500 font-bold">#1</span>
                                    <span class="text-sm">Why Do I?</span>
                                </div>
                                <span
                                    class="text-slate-400">{{ $request?->track?->embeddable_by ?? 'Unknown' }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <div class="flex items-center space-x-2">
                                    <span class="text-slate-400 font-bold">#2</span>
                                    <span class="text-slate-400 text-sm">The Strength Of Love</span>
                                </div>
                                <span class="text-slate-400">Constellation Lyra</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

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

            <div x-data="{ momentumEnabled: @js(proUser()), showGenreRadios: false, showRepostPerDay: false, showOptions: false }" class="flex-grow overflow-y-auto p-6">
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
                                min="50" step="10" max="{{ userCredits() }}"
                                value="{{ $credit }}" class="w-full h-2 border-0 cursor-pointer">
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
                    <!-- Max Follower Limit -->
                    <div x-data="{ showOptions: {{ $maxFollower >= 100 ? 'true' : 'false' }} }" class="flex flex-col space-y-2">
                        <div class="flex items-start space-x-3">
                            <input type="checkbox" @change="showOptions = true"
                                {{ $maxFollower > 0 ? 'checked' : '' }}
                                class="mt-1 w-4 h-4 text-orange-500 border-gray-300 rounded focus:ring-orange-500">
                            <div class="flex items-center space-x-2">
                                <span class="text-sm font-medium text-gray-900 dark:text-white">Limit to users with
                                    max follower
                                    count</span>
                            </div>
                        </div>
                        <div x-show="showOptions" x-transition class="p-3">
                            <div class="flex justify-between items-center gap-4">
                                <div class="w-full relative">
                                    <input type="range" x-data
                                        x-on:input="$wire.set('maxFollower', $event.target.value)" min="100"
                                        max="{{ $followersLimit }}"
                                        value="{{ $followersLimit < $maxFollower ? $followersLimit : $maxFollower }}"
                                        class="w-full h-2 cursor-pointer">
                                </div>
                                <div
                                    class="full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-md flex items-center justify-center">
                                    <span
                                        class="text-sm font-medium text-gray-900 dark:text-white">{{ $maxFollower > $followersLimit ? $followersLimit : $maxFollower }}</span>
                                </div>
                            </div>
                            @error('maxFollower')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Enable Campaign Accelerator -->
                    <div class="flex items-start space-x-3 {{ !proUser() ? 'opacity-30' : '' }}">
                        <input type="checkbox" wire:click="profeature( {{ $proFeatureValue }} )"
                            {{ !proUser() ? 'disabled' : '' }}
                            class="mt-1 w-4 h-4 text-orange-500 border-gray-300 rounded focus:ring-orange-500 {{ !proUser() ? 'cursor-not-allowed' : 'cursor-pointer' }}">
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
                            <div class="flex flex-col space-y-2">
                                <div class="flex items-start space-x-3">
                                    <input type="checkbox" :disabled="!momentumEnabled"
                                        class="mt-1 w-4 h-4 text-orange-500 border-gray-300 rounded focus:ring-orange-500"
                                        :class="momentumEnabled ? 'cursor-pointer' : 'cursor-not-allowed'">
                                    <div class="flex items-center space-x-2">
                                        <span class="text-sm text-gray-700 dark:text-gray-400">Exclude users who repost
                                            too often (last
                                            24h)</span>
                                    </div>
                                </div>
                            </div>

                            <div class="flex flex-col space-y-2">
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
                        <!-- Genre Selection -->
                        <div class="mt-4">
                            <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Genre
                                Preferences for
                                Sharers</h2>
                            <p class="text-sm text-gray-700 dark:text-gray-400 mb-3 mt-2">Reposters must have
                                the
                                following genres:</p>
                            <div class="space-y-2 ml-4">
                                <div class="flex items-center space-x-2">
                                    <input type="radio" name="genre" value="anyGenre" checked
                                        @click="showGenreRadios = false" wire:model="anyGenre"
                                        :disabled="!momentumEnabled"
                                        class="w-4 h-4 text-orange-500 border-gray-300 focus:ring-orange-500"
                                        :class="momentumEnabled ? 'cursor-pointer' : 'cursor-not-allowed'">
                                    <span class="text-sm text-gray-700 dark:text-gray-400">Open to all music
                                        types</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <input type="radio" name="genre" value="trackGenre"
                                        @click="showGenreRadios = false" wire:model="trackGenre"
                                        :disabled="!momentumEnabled"
                                        class="w-4 h-4 text-orange-500 border-gray-300 focus:ring-orange-500"
                                        :class="momentumEnabled ? 'cursor-pointer' : 'cursor-not-allowed'">
                                    <span class="text-sm text-gray-700 dark:text-gray-400">Match track genre –
                                        Hip-hop
                                        & Rap</span>
                                </div>
                                <div class="space-y-3">
                                    <div class="flex items-center space-x-2">
                                        <input type="radio" name="genre"
                                            @click="showGenreRadios = !showGenreRadios" wire:click="getAllGenres"
                                            :disabled="!momentumEnabled"
                                            class="w-4 h-4 text-orange-500 border-gray-300 focus:ring-orange-500"
                                            :class="momentumEnabled ? 'cursor-pointer' : 'cursor-not-allowed'">
                                        <span class="text-sm text-gray-700 dark:text-gray-400">Match one of
                                            your
                                            profile's chosen
                                            genres</span>
                                    </div>
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
                            class="w-full transition-all duration-300 flex items-center justify-center gap-3 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 font-bold py-2 px-4 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 rounded-lg {{ !$canSubmit ? 'bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white' : 'bg-gray-300 dark:bg-gray-600 text-gray-500 dark:text-gray-400 cursor-not-allowed' }}">
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

    {{-- JavaScript for Chart --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-annotation@1.0.0"></script>

    <script>
        // Define a function to create the chart
        function createCampaignChart() {
            const ctx = document.getElementById('campaignChart');

            // Check if the canvas element exists before trying to create a chart
            if (ctx) {
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                        datasets: [{
                            label: 'Performance',
                            data: [950, 1700, 2300, 2850, 2700, 3800],
                            borderColor: '#f97316',
                            backgroundColor: 'rgba(249, 115, 22, 0.1)',
                            pointBackgroundColor: '#f97316',
                            pointBorderColor: '#ffffff',
                            pointHoverRadius: 7,
                            pointHoverBorderWidth: 2,
                            pointRadius: 5,
                            borderWidth: 2.5,
                            tension: 0.4,
                            fill: true,
                        }, {
                            label: 'Baseline',
                            data: [100, 150, 120, 180, 250, 200],
                            borderColor: '#22c55e',
                            backgroundColor: 'transparent',
                            pointBackgroundColor: '#22c55e',
                            borderWidth: 2,
                            pointRadius: 5,
                            tension: 0.4,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    color: '#94a3b8',
                                    font: {
                                        size: 10
                                    }
                                },
                                grid: {
                                    color: '#334155',
                                    drawBorder: false,
                                },
                            },
                            x: {
                                ticks: {
                                    color: '#94a3b8',
                                    font: {
                                        size: 10
                                    }
                                },
                                grid: {
                                    display: false,
                                },
                            }
                        },
                        plugins: {
                            legend: {
                                position: 'top',
                                align: 'end',
                                labels: {
                                    color: '#e2e8f0',
                                    boxWidth: 12,
                                    font: {
                                        size: 12
                                    }
                                }
                            },
                            tooltip: {
                                backgroundColor: '#0f172a',
                                titleColor: '#ffffff',
                                bodyColor: '#cbd5e1',
                                borderColor: '#334155',
                                borderWidth: 1,
                                padding: 12,
                                cornerRadius: 8,
                            }
                        },
                        interaction: {
                            intersect: false,
                            mode: 'index',
                        },
                    }
                });
            }
        }

        // Listen for the livewire:navigated event to re-initialize the chart
        document.addEventListener('livewire:navigated', () => {
            createCampaignChart();
        });
    </script>
</div>
