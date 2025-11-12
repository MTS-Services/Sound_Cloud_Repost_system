<div x-data="campaignCreatorModal()">
    {{-- Choose a track or playlist Modal --}}
    <div x-show="showCampaignsModal" x-cloak x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
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
                            <img src="{{ asset('assets/favicons/fav icon 2 (1).svg') }}" alt="{{ config('app.name') }}"
                                class="w-12 hidden dark:block" />
                        @endif
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ __('Choose a track or playlist') }}
                    </h2>
                </div>
                <button @click="closeAllModals()"
                    class="cursor-pointer w-10 h-10 rounded-xl bg-white dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-all duration-200 flex items-center justify-center border border-gray-200 dark:border-gray-600">
                    <x-lucide-x class="w-5 h-5" />
                </button>
            </div>

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

            <div class="flex-grow overflow-y-auto">
                <div class="p-5 sticky top-0 bg-white dark:bg-slate-800 z-10">
                    <label for="track-link-search" class="text-xl font-semibold text-gray-700 dark:text-gray-200">
                        @if ($activeTab === 'tracks')
                            Paste a SoundCloud track link
                        @else
                            Paste a SoundCloud playlist link
                        @endif
                    </label>
                    <form wire:submit.prevent="searchSoundcloud">
                        <div class="flex w-full mt-2">
                            <input wire:model="searchQuery" type="text" id="track-link-search"
                                placeholder="{{ $activeTab === 'tracks' ? 'Paste a SoundCloud track link' : 'Paste a SoundCloud playlist link' }}"
                                class="flex-grow p-3 text-gray-700 dark:text-gray-200 bg-white dark:bg-slate-700 placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-0 focus:border-orange-500 transition-colors duration-200 border-2 border-r-0 border-gray-300 dark:border-gray-600">

                            <button type="submit"
                                class="bg-orange-500 text-white p-3 w-14 flex items-center justify-center hover:bg-orange-600 transition-colors duration-200">
                                <span wire:loading.remove wire:target="searchSoundcloud">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </span>
                                <span wire:loading wire:target="searchSoundcloud">
                                    <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10"
                                            stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor"
                                            d="M4 12a8 8 0 018-8v4l3-3-3-3v4a8 8 0 100 16v-4l-3 3 3 3v-4a8 8 0 01-8-8z">
                                        </path>
                                    </svg>
                                </span>
                            </button>
                        </div>
                    </form>
                </div>

                <div class="flex-grow overflow-y-auto p-4">
                    @if ($activeTab === 'tracks' || $playListTrackShow == true)
                        <div class="space-y-3" wire:loading.remove wire:target="searchSoundcloud">
                            @forelse ($tracks as $track_)
                                <div wire:click="selectTrackOrPlaylist('track', {{ $track_->id }})"
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
                                            {{ $track_->playback_count }}
                                        </span>
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

                            @if ($hasMoreTracks)
                                <div class="text-center mt-4">
                                    <button wire:click="loadMoreTracks" wire:loading.attr="disabled"
                                        class="bg-orange-500 text-white font-semibold px-3 py-1.5 text-sm rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 hover:bg-orange-600 transition-colors duration-200 disabled:bg-orange-300 disabled:cursor-not-allowed">
                                        <span wire:loading.remove wire:target="loadMoreTracks">Load More</span>
                                        <span wire:loading wire:target="loadMoreTracks">Loading...</span>
                                    </button>
                                </div>
                            @endif
                        </div>

                        <div wire:loading wire:target="searchSoundcloud"
                            class="w-full flex justify-center items-center">
                            <div class="text-center py-16 text-orange-600">
                                <div
                                    class="w-16 h-16 bg-orange-100 dark:bg-orange-900/20 rounded-full flex items-center justify-center mx-auto mb-4 animate-spin">
                                    <svg class="w-8 h-8 text-orange-500" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10"
                                            stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor"
                                            d="M4 12a8 8 0 018-8v4l3-3-3-3v4a8 8 0 100 16v-4l-3 3 3 3v-4a8 8 0 01-8-8z" />
                                    </svg>
                                </div>
                                <p class="text-sm font-medium">Searching Track...</p>
                            </div>
                        </div>
                    @elseif($activeTab === 'playlists')
                        <div class="space-y-3" wire:loading.remove wire:target="searchSoundcloud">
                            @forelse ($playlists as $playlist_)
                                <div wire:click="selectTrackOrPlaylist('playlist', {{ $playlist_->id }})"
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

                            @if ($hasMorePlaylists)
                                <div class="text-center mt-4">
                                    <button wire:click="loadMorePlaylists" wire:loading.attr="disabled"
                                        class="bg-orange-500 text-white font-semibold px-3 py-1.5 text-sm rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 hover:bg-orange-600 transition-colors duration-200 disabled:bg-orange-300 disabled:cursor-not-allowed">
                                        <span wire:loading.remove wire:target="loadMorePlaylists">Load More</span>
                                        <span wire:loading wire:target="loadMorePlaylists">Loading...</span>
                                    </button>
                                </div>
                            @endif
                        </div>
                        <div wire:loading wire:target="searchSoundcloud"
                            class="w-full flex justify-center items-center">
                            <div class="text-center py-16 text-orange-600">
                                <div
                                    class="w-16 h-16 bg-orange-100 dark:bg-orange-900/20 rounded-full flex items-center justify-center mx-auto mb-4 animate-spin">
                                    <svg class="w-8 h-8 text-orange-500" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10"
                                            stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor"
                                            d="M4 12a8 8 0 018-8v4l3-3-3-3v4a8 8 0 100 16v-4l-3 3 3 3v-4a8 8 0 01-8-8z" />
                                    </svg>
                                </div>
                                <p class="text-sm font-medium">Searching Playlist...</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Create campaign (submit) Modal --}}
    @include('livewire.user.campaign-management.campaign-submit-modal')

    {{-- Low Credit Warning Modal --}}
    <div x-show="showLowCreditWarningModal" x-cloak x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
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
                <button @click="showLowCreditWarningModal = false"
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
                <x-gbutton :full-width="true" variant="primary" wire:navigate href="{{ route('user.add-credits') }}"
                    class="mb-2">
                    {{ __('Buy Credits Now') }}
                </x-gbutton>
            </div>
        </div>
    </div>

    <script>
        function campaignCreatorModal() {
            return {
                showCampaignsModal: false,
                showSubmitModal: false,
                showLowCreditWarningModal: false,

                init() {
                    // Listen for events from Livewire
                    Livewire.on('campaign-modal-opened', () => {
                        this.showCampaignsModal = true;
                        this.showSubmitModal = false;
                        this.showLowCreditWarningModal = false;
                    });

                    Livewire.on('show-submit-modal', () => {
                        this.showCampaignsModal = false;
                        this.showSubmitModal = true;
                    });

                    Livewire.on('show-low-credit-modal', () => {
                        this.showCampaignsModal = false;
                        this.showSubmitModal = false;
                        this.showLowCreditWarningModal = true;
                    });

                    Livewire.on('close-all-modals', () => {
                        this.closeAllModals();
                    });
                },

                openCampaignsModal() {
                    @this.call('openCampaignModal');
                },

                closeAllModals() {
                    this.showCampaignsModal = false;
                    this.showSubmitModal = false;
                    this.showLowCreditWarningModal = false;
                }
            }
        }
    </script>
</div>
