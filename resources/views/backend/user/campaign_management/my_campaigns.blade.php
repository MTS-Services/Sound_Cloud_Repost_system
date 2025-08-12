<section>

    <x-slot name="page_slug">campaigns</x-slot>

    <div class="p-6">
        <div class="flex justify-between items-start mb-5">
            <div>
                <h1 class="text-xl text-black dark:text-gray-100 font-bold">{{ __('My Campaigns') }}</h1>
            </div>
            <button wire:click="toggleCampaignsModal" x-on:click="showCampaignsModal = true"
                class="bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white px-4 py-2 rounded-xl flex items-center gap-3 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                <x-lucide-plus class="w-5 h-5" />
                {{ __('Start a new campaign') }}
            </button>
        </div>

        <div class="mb-8">
            <div class="border-b border-gray-200 dark:border-gray-700">
                <nav class="-mb-px flex space-x-8">
                    <button
                        class="tab-button @if ($activeMainTab === 'all') active border-b-2 border-orange-500 text-orange-600 @else border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 @endif py-3 px-2 text-sm font-semibold transition-all duration-200"
                        wire:click="setActiveTab('all')">
                        {{ __('All Campaigns') }}
                    </button>
                    <button
                        class="tab-button @if ($activeMainTab === 'active') active border-b-2 border-orange-500 text-orange-600 @else border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 @endif py-3 px-2 text-sm font-semibold transition-all duration-200"
                        wire:click="setActiveTab('active')">
                        {{ __('Active') }}
                    </button>
                    <button
                        class="tab-button @if ($activeMainTab === 'completed') active border-b-2 border-orange-500 text-orange-600 @else border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 @endif py-3 px-2 text-sm font-semibold transition-all duration-200"
                        wire:click="setActiveTab('completed')">
                        {{ __('Completed') }}
                    </button>
                </nav>
            </div>
        </div>

        <div class="space-y-6" id="campaigns-list">

            <div class="flex flex-col lg:flex-row justify-between gap-6 px-4">
                <!-- Main Content -->
                <div class="w-full flex flex-col gap-6">
                    @forelse ($campaigns as $campaig_)
                        <div class="rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                            <div class="p-4 sm:p-6">
                                <div class="flex flex-col sm:flex-row sm:justify-between gap-4">
                                    <div class="flex flex-col sm:flex-row gap-4">
                                        <img src="{{ soundcloud_image($campaig_->music?->artwork_url) }}"
                                            alt="Sample Track 3" class="w-20 h-20 rounded-lg mx-auto sm:mx-0">
                                        <div class="flex-1">
                                            <div
                                                class="flex flex-col sm:flex-row sm:items-center sm:space-x-3 mb-2 text-center sm:text-left">
                                                <h3 class="text-black dark:text-gray-100 font-semibold text-lg">
                                                    {{ $campaig_->music?->title }}
                                                </h3>
                                                <span>
                                                    <!-- Pencil Icon -->
                                                    <svg class="w-5 h-5 inline-block text-gray-500 dark:text-gray-100"
                                                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                        fill="none" stroke="currentColor" stroke-width="2"
                                                        stroke-linecap="round" stroke-linejoin="round">
                                                        <path
                                                            d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                                        <path
                                                            d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                                                    </svg>
                                                </span>
                                            </div>

                                            <div class="mb-4 text-sm text-center sm:text-left text-slate-400">
                                                Budget used: {{ number_format($campaig_->credits_spent) }} /
                                                {{ number_format($campaig_->buget_credits) }}credits
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Right Stats Block -->
                                    <div class="text-center sm:text-right">
                                        <div class="flex items-center justify-center sm:justify-end">
                                            <x-lucide-trending-up class="m-2 w-5 h-5  text-green-600" />
                                            <span class=" text-green-600 dark:text-gray-100"> Running</span>
                                        </div>
                                        <p class="text-slate-400 text-sm">{{ $campaig_->created_at_formatted }}</p>
                                        <div class="flex flex-wrap justify-center sm:justify-end items-center mt-2">
                                            <x-lucide-ban class="w-5 h-5 m-2 dark:text-white text-gray-500" />
                                            <span class="text-slate-500">Stop</span>
                                            <x-lucide-square-pen class="w-5 h-5 m-2 dark:text-white text-gray-500" />
                                            <span class=" font-medium cursor-pointer">Edit</span>
                                        </div>
                                    </div>
                                </div>

                                <hr class="my-6 border-gray-300 dark:border-gray-600" />

                                <!-- Stats -->
                                <div class="flex justify-between gap-6 mb-2">
                                    <div class="flex gap-6">
                                        <div class="text-center">
                                            <div class="flex items-center justify-center mb-2">

                                                <x-lucide-repeat
                                                    class="text-gray-500 w-5 h-5 m-2 dark:text-white text-black" />
                                                <span class=" text-black dark:text-white">22</span>
                                            </div>

                                        </div>
                                        <!-- Repeat block with different data -->
                                        <div class="text-center">
                                            <div class="flex items-center justify-center mb-2">
                                                <x-lucide-user-plus
                                                    class="text-gray-500 w-5 h-5 m-2 dark:text-white text-black" />
                                                <span class=" text-black dark:text-gray-100">8</span>
                                            </div>

                                        </div>
                                        <div class="text-center">
                                            <div class="flex items-center justify-center mb-2">
                                                <x-lucide-heart
                                                    class="text-gray-500 w-5 h-5 m-2 dark:text-white text-black" />
                                                <span class=" text-black dark:text-gray-100">17</span>
                                            </div>

                                        </div>
                                        <div class="text-center">
                                            <div class="flex items-center justify-center mb-2">
                                                <x-lucide-mail
                                                    class="text-gray-500 w-5 h-5 m-2 dark:text-white text-black" />
                                                <span class=" text-black dark:text-gray-100">6</span>
                                            </div>

                                        </div>
                                        <div class="text-center">
                                            <div class="flex items-center justify-center mb-2">
                                                <x-lucide-smile
                                                    class="text-gray-500 w-5 h-5 m-2 dark:text-white text-black" />
                                                <span class=" text-black dark:text-gray-100">0</span>
                                            </div>

                                        </div>
                                        <div class="text-center">
                                            <div class="flex items-center justify-center mb-2">

                                                <span class="text-orange-500 items-end font-medium mt-2">Show
                                                    All</span>
                                            </div>

                                        </div>
                                        <!-- Add more blocks if needed -->
                                    </div>

                                    <div>
                                        <p class="text-slate-400 text-sm">-.- avg. rating</p>
                                    </div>
                                </div>

                                <div class="flex flex-col sm:flex-row sm:justify-between items-center gap-4">
                                    <span></span>

                                    <div class="flex flex-wrap justify-center sm:justify-end gap-4">
                                        <button
                                            class="bg-white border border-gray-300 text-gray-700 py-2 px-2 rounded-sm text-sm font-semibold hover:bg-gray-100 transition-colors">
                                            Set featured
                                        </button>
                                        {{-- <button
                                            class="bg-red-500 text-white py-2 px-4 rounded-sm text-sm font-semibold shadow-sm hover:bg-red-600 transition-colors">
                                            Boost campaign
                                        </button> --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        @if ($activeMainTab === 'all')
                            <div
                                class="flex flex-col items-center justify-center py-20 text-center bg-white dark:bg-gray-800 rounded-2xl shadow-lg">
                                <div
                                    class="w-20 h-20 bg-gradient-to-br from-orange-100 to-orange-200 dark:from-orange-900/20 dark:to-orange-800/20 rounded-full flex items-center justify-center mb-6">
                                    <x-lucide-megaphone class="w-10 h-10 text-orange-600 dark:text-orange-400" />
                                </div>
                                <h3 class="text-xl font-bold text-gray-800 dark:text-gray-100 mb-3">
                                    {{ __('No active campaigns found') }}
                                </h3>

                                <p class="text-gray-500 dark:text-gray-400 mb-6 max-w-md">
                                    {{ __('Looks like there are no active campaigns right now. Why not start a new one and watch it grow?') }}
                                </p>
                                <button wire:click="toggleCampaignsModal" x-on:click="showCampaignsModal = true"
                                    class="bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white px-6 py-3 rounded-xl flex items-center gap-2 transition-all duration-300 shadow-lg hover:shadow-xl">
                                    <x-lucide-plus class="w-5 h-5" />
                                    {{ __('Create Your First Campaign') }}
                                </button>
                            </div>
                        @elseif ($activeMainTab === 'active')
                            <div
                                class="flex flex-col items-center justify-center py-20 text-center bg-white dark:bg-gray-800 rounded-2xl shadow-lg">
                                <div
                                    class="w-20 h-20 bg-gradient-to-br from-orange-100 to-orange-200 dark:from-orange-900/20 dark:to-orange-800/20 rounded-full flex items-center justify-center mb-6">
                                    <x-lucide-megaphone class="w-10 h-10 text-orange-600 dark:text-orange-400" />
                                </div>
                                <h3 class="text-xl font-bold text-gray-800 dark:text-gray-100 mb-3">
                                    {{ __('No active campaigns found') }}
                                </h3>
                                <p class="text-gray-500 dark:text-gray-400 mb-6 max-w-md">
                                    {{ __("You haven't started any active campaigns yet. You can create new campaigns or view active campaigns.") }}
                                </p>
                            </div>
                        @elseif ($activeMainTab === 'completed')
                            <div
                                class="flex flex-col items-center justify-center py-20 text-center bg-white dark:bg-gray-800 rounded-2xl shadow-lg">
                                <div
                                    class="w-20 h-20 bg-gradient-to-br from-orange-100 to-orange-200 dark:from-orange-900/20 dark:to-orange-800/20 rounded-full flex items-center justify-center mb-6">
                                    <x-lucide-megaphone class="w-10 h-10 text-orange-600 dark:text-orange-400" />
                                </div>
                                <h3 class="text-xl font-bold text-gray-800 dark:text-gray-100 mb-3">
                                    {{ __('Oops! No completed campaigns yet.') }}
                                </h3>
                                <p class="text-gray-500 dark:text-gray-400 mb-6 max-w-md">
                                    {{ __('It looks like there are no completed campaigns at the moment. Start a campaign today and track your progress!') }}
                                </p>
                            </div>
                        @endif
                    @endforelse
                    @if ($campaigns->hasPages())
                        <div class="mt-6">
                            {{ $campaigns->links('components.pagination.wire-navigate', [
                                'pageName' => $activeMainTab . 'Page',
                                'keep' => ['tab' => $activeMainTab],
                            ]) }}
                        </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <aside class="lg:w-1/3 space-y-6 bg-white dark:bg-slate-800 rounded-sm lg:mt-0">
                    <!-- Tools Section -->
                    <div class=" dark:bg-slate-800 rounded-sm p-6">
                        <h2 class="text-lg font-bold text-gray-800 mb-4 text-black dark:text-gray-100">Tools and
                            FAQs</h2>
                        {{-- <ul class="space-y-3">
                            <li><a href="#" class="text-red-500 hover:underline">What is a campaign?</a>
                            </li>
                            <li><a href="#" class="text-red-500 hover:underline">How do I get the best out
                                    of my campaigns?</a></li>
                            <li><a href="#" class="text-red-500 hover:underline">Why did my campaign finish
                                    so quickly?</a></li>
                            <li><a href="#" class="text-red-500 hover:underline">Why is my campaign running
                                    slowly?</a></li>
                        </ul> --}}
                        <ul class="space-y-3">
                            @foreach ($faqs as $faq) 
                                <li><a href="{{ route('user.faq')}}#faq-{{ $faq->id }}" class="text-red-500 hover:underline">{{ $faq->question }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </div> 

                    <!-- Reach More Section -->
                    <div class="dark:bg-slate-800 rounded-sm p-6 text-black dark:text-gray-100">
                        <h2 class="text-lg font-bold text-gray-800 mb-4 dark:text-gray-100">Reach more
                            people</h2>
                        <hr class="text-red-500 mb-4">
                        <div class="flex flex-col sm:flex-row items-center gap-4 mb-4">
                            <img src="https://i1.sndcdn.com/artworks-ZmemRDWSfmXwMCWO-xZp0wA-t500x500.jpg"
                                alt="Reach people icon" class="w-16 h-16 rounded-lg object-cover">
                            <div>
                                <p class="font-bold text-gray-800  dark:text-gray-100">Feature your
                                    campaign and reach more people</p>
                                <p class="text-gray-500 text-sm">ALL_BLACK_.75</p>
                            </div>
                        </div>
                        <button
                            class="mx-auto block bg-red-500 text-white py-2 px-4 rounded-sm font-semibold hover:bg-red-600 transition-colors">
                            Get featured
                        </button>
                    </div>
                </aside>
            </div>
        </div>
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
                class="flex justify-between items-center p-6 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-orange-50 to-orange-100 dark:from-orange-900/20 dark:to-orange-800/20">
                <div class="flex items-center gap-3">
                    <div class="w-7 h-7 md:w-8 md:h-8 bg-orange-500 rounded-lg flex items-center justify-center">
                        <span class="text-slate-800 dark:text-white font-bold text-md md:text-lg">R</span>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ __('Choose a track or playlist') }}
                    </h2>
                </div>
                <button x-on:click="showCampaignsModal = false"
                    class="w-10 h-10 rounded-xl bg-white dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-all duration-200 flex items-center justify-center border border-gray-200 dark:border-gray-600">
                    <x-lucide-x class="w-5 h-5" />
                </button>
            </div>

            @if ($showCampaignsModal)
                <div class="flex border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                    <button wire:click="selectModalTab('tracks')"
                        class="flex-1 py-4 px-6 text-center font-semibold text-base transition-all duration-300 ease-in-out border-b-2 hover:bg-white dark:hover:bg-gray-700 {{ $activeModalTab === 'tracks' ? 'border-orange-500 text-orange-600 bg-white dark:bg-gray-700' : 'border-transparent text-gray-600 dark:text-gray-400' }}">
                        <div class="flex items-center justify-center gap-2">
                            <x-lucide-music class="w-4 h-4" />
                            {{ __('Tracks') }}
                        </div>
                    </button>
                    <button wire:click="selectModalTab('playlists')"
                        class="flex-1 py-4 px-6 text-center font-semibold text-base transition-all duration-300 ease-in-out border-b-2 hover:bg-white dark:hover:bg-gray-700 {{ $activeModalTab === 'playlists' ? 'border-orange-500 text-orange-600 bg-white dark:bg-gray-700' : 'border-transparent text-gray-600 dark:text-gray-400' }}">
                        <div class="flex items-center justify-center gap-2">
                            <x-lucide-list-music class="w-4 h-4" />
                            {{ __('Playlists') }}
                        </div>
                    </button>
                </div>

                <div class="flex-grow overflow-y-auto p-4">
                    <div class="p-1">
                        <label for="track-link-search" class="text-xl font-semibold text-gray-700 dark:text-gray-200">
                            @if ($activeModalTab === 'tracks')
                                Paste a SoundCloud track link
                            @else
                                Paste a SoundCloud playlist link
                            @endif
                        </label>
                        <div class="flex w-full mt-2">
                            <input wire:model="searchQuery" type="text" id="track-link-search"
                                placeholder="{{ $activeModalTab === 'tracks' ? 'Paste a SoundCloud track link' : 'Paste a SoundCloud playlist link' }}"
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
                    @if ($activeModalTab === 'tracks' || $playListTrackShow == true)
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
                    @elseif($activeModalTab === 'playlists')
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

    {{-- Low Credit Warning Modal --}}
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

            <div class="flex-grow overflow-y-auto p-6">
                <form wire:submit.prevent="createCampaign" class="space-y-6">
                    <!-- Selected Track -->
                    @if ($track)
                        <div>
                            <div class="flex items-center justify-between mb-3">
                                <h3 class="text-md font-medium text-gray-900">Selected Track</h3>
                                <button x-on:click="showSubmitModal = false"
                                    class="bg-gray-100 dark:bg-slate-700 py-1.5 px-3 rounded-xl text-orange-500 text-sm font-medium hover:text-orange-600">Edit</button>
                            </div>
                            <div
                                class="p-4 flex items-center space-x-4 cursor-pointer hover:bg-gray-50 dark:hover:bg-slate-700 rounded-xl transition-all duration-200 border border-transparent hover:border-orange-200 dark:hover:border-orange-800 group">
                                @if ($track)
                                    <img src="{{ soundcloud_image($track->artwork_url) }}" alt="Album cover"
                                        class="w-12 h-12 rounded">
                                @endif
                                <div>
                                    <p class="text-sm text-gray-600">{{ $track->type }} -
                                        {{ $track->author_username }}</p>
                                    <p class="text-sm font-medium text-gray-900">{{ $track->title }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Set Budget -->
                    <div>
                        <div class="flex items-center space-x-2 mb-2">
                            <h3 class="text-sm font-medium text-gray-900">Set budget</h3>
                            <div class="w-4 h-4 bg-gray-400 rounded-full flex items-center justify-center">
                                <span class="text-white text-xs">i</span>
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 mb-4">A potential 10,000 people reached per campaign</p>

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
                                class="w-full h-2  cursor-pointer">
                        </div>


                    </div>

                    <!-- Enable CommentPlus -->
                    <div>
                        <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mt-4">Campaign Settings</h2>
                        <p class="text-sm text-gray-700 mb-4 mt-2">Select amount of credits to be spent</p>
                        <div class="flex items-start space-x-3">
                            <input type="checkbox" wire:model="commentable"
                                class="mt-1 w-4 h-4 text-orange-500 border-gray-300 rounded focus:ring-orange-500">
                            <div>
                                <h4 class="text-sm font-medium text-gray-900">Activate Feedback</h4>
                                <p class="text-xs text-gray-500">Encourage listeners to comment on your track (2
                                    credits
                                    per comment).</p>
                            </div>
                        </div>
                    </div>

                    <!-- Enable LikePlus -->
                    <div class="flex items-start space-x-3">
                        <input type="checkbox" wire:model="likeable"
                            class="mt-1 w-4 h-4 text-orange-500 border-gray-300 rounded focus:ring-orange-500">
                        <div>
                            <h4 class="text-sm font-medium text-gray-900">Activate HeartPush</h4>
                            <p class="text-xs text-gray-500">Motivate real users to like your track (2 credits per
                                like).</p>
                        </div>
                    </div>

                    <!-- Enable Campaign Accelerator -->
                    <div class="flex items-start space-x-3">
                        <input type="checkbox" wire:click="profeature( {{ $proFeatureValue }} )"
                            class="mt-1 w-4 h-4 text-orange-500 border-gray-300 rounded focus:ring-orange-500">
                        <div>
                            <div class="flex items-center space-x-2">
                                <h4 class="text-sm font-medium text-gray-900">{{ __('Turn on Momentum+ (') }}
                                    <span class="text-md font-semibold">PRO</span>{{ __(')') }}
                                </h4>
                                <div class="w-4 h-4 bg-gray-400 rounded-full flex items-center justify-center">
                                    <span class="text-white text-xs">i</span>
                                </div>
                            </div>
                            <p class="text-xs text-gray-500">Use Campaign Accelerator (+50 credits)</p>
                        </div>
                    </div>
                    <div x-data="{ showOptions: false }" class="flex flex-col space-y-2">
                        <!-- Checkbox + Label -->
                        <div class="flex items-start space-x-3">
                            <input type="checkbox" @change="showOptions = !showOptions"
                                class="mt-1 w-4 h-4 text-orange-500 border-gray-300 rounded focus:ring-orange-500">

                            <div class="flex items-center space-x-2">
                                <span class="text-sm text-gray-700">Limit to users with max follower
                                    count</span>
                                {{-- <div class="w-4 h-4 bg-gray-400 rounded-full flex items-center justify-center">
                                            <span class="text-white text-xs">i</span>
                                        </div> --}}
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

                    <!-- Campaign Targeting -->
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class=" mb-4">
                            <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-200">
                                {{ __('Audience Filtering (PRO Feature)') }}</h4>
                            <p class="text-sm text-gray-700 mb-4 mt-2">Fine-tune who can support your track:</p>
                        </div>

                        <div class="space-y-3 ml-4">
                            <div x-data="{ showOptions: false }" class="flex flex-col space-y-2">
                                <div class="flex items-start space-x-3">
                                    <input type="checkbox" @change="showOptions = !showOptions"
                                        class="mt-1 w-4 h-4 text-orange-500 border-gray-300 rounded focus:ring-orange-500">
                                    <div class="flex items-center space-x-2">
                                        <span class="text-sm text-gray-700">Exclude users who repost too often (last
                                            24h)</span>
                                    </div>
                                </div>
                                <div x-show="showOptions" x-transition class="p-3">
                                    <div class="flex justify-between items-center gap-4">
                                        <div class="w-full relative">
                                            <input type="range" x-data
                                                x-on:input="$wire.set('maxRepostLast24h', $event.target.value)"
                                                min="0" max="50" value="{{ $maxRepostLast24h }}"
                                                class="w-full h-2  cursor-pointer">
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
                                        class="mt-1 w-4 h-4 text-orange-500 border-gray-300 rounded focus:ring-orange-500">
                                    <div class="flex items-center space-x-2">
                                        <span class="text-sm text-gray-700">Limit average repost frequency per
                                            day</span>
                                    </div>
                                </div>
                                <div x-show="showRepostPerDay" x-transition class="p-3">
                                    <div class="flex justify-between items-center gap-4">
                                        <div class="w-full relative">
                                            <input type="range" x-data
                                                x-on:input="$wire.set('maxRepostsPerDay', $event.target.value)"
                                                min="0" max="100" value="{{ $maxRepostsPerDay }}"
                                                class="w-full h-2  cursor-pointer">
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
                    <div class="border border-gray-200 rounded-lg p-4">
                        <!-- Genre Selection -->
                        <div class="mt-6">
                            <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Genre Preferences for
                                Sharers</h2>
                            <p class="text-sm text-gray-700 mb-3 mt-2">Reposters must have the following genres:</p>
                            <div class="space-y-2 ml-4">
                                <div class="flex items-center space-x-2">
                                    <input type="radio" name="genre" value="anyGenre"
                                        @click="showGenreRadios = false" wire:model="anyGenre"
                                        class="w-4 h-4 text-orange-500 border-gray-300 focus:ring-orange-500">
                                    <span class="text-sm text-gray-700">Open to all music types</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <input type="radio" name="genre" value="trackGenre"
                                        @click="showGenreRadios = false" wire:model="trackGenre"
                                        class="w-4 h-4 text-orange-500 border-gray-300 focus:ring-orange-500">
                                    <span class="text-sm text-gray-700">Match track genre – Hip-hop & Rap</span>
                                </div>
                                <div x-data="{ showGenreRadios: false }" class="space-y-3">

                                    <!-- Toggle Checkbox -->
                                    <div class="flex items-center space-x-2">
                                        <input type="radio" name="genre"
                                            @click="showGenreRadios = !showGenreRadios" wire:click="getAllGenres"
                                            class="w-4 h-4 text-orange-500 border-gray-300 focus:ring-orange-500">
                                        <span class="text-sm text-gray-700">Match one of your profile’s chosen
                                            genres</span>
                                    </div>

                                    <!-- Radio Options (Toggle area) -->
                                    <div x-show="showGenreRadios" x-transition class="ml-6 space-y-2">
                                        @forelse ($genres as $genre)
                                            <div class="flex items-center space-x-2">
                                                <input type="radio" name="genre" wire:model="targetGenre"
                                                    value="{{ $genre }}"
                                                    class="w-4 h-4 text-orange-500 border-gray-300 focus:ring-orange-500">
                                                <span class="text-sm text-gray-700">{{ $genre }}</span>
                                            </div>
                                        @empty
                                            <div class="">
                                                <span class="text-sm text-gray-700">No genres found</span>
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

    {{-- Add Credit Modal --}}
    <div x-data="{ showAddCreditModal: @entangle('showAddCreditModal').live }" x-show="showAddCreditModal" x-cloak
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
        class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4 z-50">

        <div
            class="w-full max-w-xl mx-auto rounded-2xl shadow-2xl bg-white dark:bg-slate-800 border border-gray-200 dark:border-gray-700 flex flex-col overflow-hidden">
            <div
                class="flex justify-between items-center p-6 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-orange-50 to-orange-100 dark:from-orange-900/20 dark:to-orange-800/20">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-orange-500 rounded-xl flex items-center justify-center">
                        <x-lucide-wallet class="w-5 h-5 text-white" />
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ __('Add Credits to Campaign') }}
                    </h2>
                </div>
                <button x-on:click="showAddCreditModal = false"
                    class="w-10 h-10 rounded-xl bg-white dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-all duration-200 flex items-center justify-center border border-gray-200 dark:border-gray-600">
                    <x-lucide-x class="w-5 h-5" />
                </button>
            </div>

            <div class="flex-grow overflow-y-auto p-6">
                <form wire:submit.prevent="addCreditsToCampaign" class="space-y-6">
                    <div class="space-y-2">
                        <label for="add_credit_cost_per_repost"
                            class="text-sm font-semibold text-gray-700 dark:text-gray-300 flex items-center gap-2">
                            <x-lucide-coins class="w-4 h-4 text-orange-500" />
                            {{ __('New Cost per Repost (credits)') }}
                        </label>
                        <input type="number" id="add_credit_cost_per_repost"
                            wire:model.live="addCreditCostPerRepost" min="1"
                            class="w-full rounded-xl border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-3 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-200"
                            placeholder="{{ __('Enter new cost per repost') }}">
                        @error('addCreditCostPerRepost')
                            <div class="flex items-center gap-2 text-red-600 dark:text-red-400 text-sm">
                                <x-lucide-alert-circle class="w-4 h-4" />
                                <span>{{ $message }}</span>
                            </div>
                        @enderror
                    </div>

                    {{-- Budget Warning Display --}}
                    @if ($showBudgetWarning)
                        <div
                            class="bg-gradient-to-r from-red-50 to-red-100 dark:from-red-900/20 dark:to-red-800/20 rounded-xl p-4 border border-red-200 dark:border-red-800">
                            <div class="flex items-center gap-3 flex-col">
                                <div class="flex items-center gap-3">
                                    <x-lucide-alert-triangle class="w-5 h-5 text-red-600 dark:text-red-400" />
                                    <div>
                                        <p class="text-sm font-semibold text-red-900 dark:text-red-100">
                                            {{ __('Budget Warning') }}
                                        </p>
                                        <p class="text-sm text-red-600 dark:text-red-400">
                                            {{ $budgetWarningMessage }}
                                        </p>
                                    </div>
                                </div>
                                @if (str_contains($budgetWarningMessage, 'need') && str_contains($budgetWarningMessage, 'more credits'))
                                    <a href="{{ route('user.add-credits') }}" wire:navigate
                                        class="ml-auto bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded-lg text-xs font-medium transition-colors">
                                        {{ __('Buy Credits') }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endif

                    {{-- Budget Display --}}
                    @if (
                        $addCreditCostPerRepost &&
                            $addCreditTargetReposts &&
                            $addCreditCostPerRepost > 0 &&
                            $addCreditTargetReposts > 0 &&
                            !$showBudgetWarning)
                        <div
                            class="bg-gradient-to-r from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 rounded-xl p-4 border border-blue-200 dark:border-blue-800">
                            <div class="flex items-center gap-3">
                                <x-lucide-calculator class="w-5 h-5 text-blue-600 dark:text-blue-400" />
                                <div>
                                    <p class="text-sm font-semibold text-blue-900 dark:text-blue-100">
                                        {{ __('New Total Campaign Budget') }}
                                    </p>
                                    <p class="text-lg font-bold text-blue-600 dark:text-blue-400">
                                        {{ number_format($addCreditCostPerRepost * $addCreditTargetReposts) }}
                                        {{ __('credits') }}
                                    </p>
                                    @if ($addCreditCreditsNeeded > 0)
                                        <p class="text-sm text-blue-600 dark:text-blue-400">
                                            {{ __('Additional credits needed:') }}
                                            {{ number_format($addCreditCreditsNeeded) }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                        <button type="submit"
                            class="w-full transition-all duration-300 flex items-center justify-center gap-3 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 font-bold py-4 px-6 rounded-xl {{ $canSubmit ? 'bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white' : 'bg-gray-300 dark:bg-gray-600 text-gray-500 dark:text-gray-400 cursor-not-allowed' }}"
                            wire:loading.attr="disabled" @if (!$canSubmit) disabled @endif>
                            <x-lucide-plus class="w-4 h-4" />
                            <span wire:loading.remove wire:target="addCreditsToCampaign">
                                {{ __('Add Credits') }}
                            </span>
                            <span wire:loading wire:target="addCreditsToCampaign">
                                {{ __('Adding...') }}
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Edit Campaign Modal --}}
    <div x-data="{ showEditCampaignModal: @entangle('showEditCampaignModal').live }" x-show="showEditCampaignModal" x-cloak
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
        class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4 z-50">

        <div
            class="w-full max-w-4xl mx-auto rounded-2xl shadow-2xl bg-white dark:bg-slate-800 border border-gray-200 dark:border-gray-700 flex flex-col max-h-[85vh] overflow-hidden">
            <div
                class="flex justify-between items-center p-6 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-orange-50 to-orange-100 dark:from-orange-900/20 dark:to-orange-800/20">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-orange-500 rounded-xl flex items-center justify-center">
                        <x-lucide-edit class="w-5 h-5 text-white" />
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ __('Edit Campaign') }}
                    </h2>
                </div>
                <button x-on:click="showEditCampaignModal = false"
                    class="w-10 h-10 rounded-xl bg-white dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-all duration-200 flex items-center justify-center border border-gray-200 dark:border-gray-600">
                    <x-lucide-x class="w-5 h-5" />
                </button>
            </div>

            <div class="flex-grow overflow-y-auto p-6">
                <form wire:submit.prevent="updateCampaign" class="space-y-6">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label for="edit_campaign_title"
                                class="text-sm font-semibold text-gray-700 dark:text-gray-300 flex items-center gap-2">
                                <x-lucide-type class="w-4 h-4 text-orange-500" />
                                {{ __('Campaign name') }}
                            </label>
                            <input type="text" id="edit_campaign_title" wire:model.live="editTitle"
                                class="w-full rounded-xl border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-3 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-200"
                                placeholder="{{ __('Enter campaign name') }}">
                            @error('editTitle')
                                <div class="flex items-center gap-2 text-red-600 dark:text-red-400 text-sm">
                                    <x-lucide-alert-circle class="w-4 h-4" />
                                    <span>{{ $message }}</span>
                                </div>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="edit_campaign_end_date"
                                class="text-sm font-semibold text-gray-700 dark:text-gray-300 flex items-center gap-2">
                                <x-lucide-calendar class="w-4 h-4 text-orange-500" />
                                {{ __('Campaign expiration date') }}
                            </label>
                            <input type="date" id="edit_campaign_end_date" wire:model.live="editEndDate"
                                class="w-full rounded-xl border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-3 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-200">
                            @error('editEndDate')
                                <div class="flex items-center gap-2 text-red-600 dark:text-red-400 text-sm">
                                    <x-lucide-alert-circle class="w-4 h-4" />
                                    <span>{{ $message }}</span>
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label for="edit_campaign_description"
                            class="text-sm font-semibold text-gray-700 dark:text-gray-300 flex items-center gap-2">
                            <x-lucide-file-text class="w-4 h-4 text-orange-500" />
                            {{ __('Campaign description') }}
                        </label>
                        <textarea id="edit_campaign_description" wire:model.live="editDescription" rows="4"
                            class="w-full rounded-xl border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-3 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-200 resize-none"
                            placeholder="{{ __('Describe your campaign goals and target audience...') }}"></textarea>
                        @error('editDescription')
                            <div class="flex items-center gap-2 text-red-600 dark:text-red-400 text-sm">
                                <x-lucide-alert-circle class="w-4 h-4" />
                                <span>{{ $message }}</span>
                            </div>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label for="edit_campaign_cost_per_repost"
                                class="text-sm font-semibold text-gray-700 dark:text-gray-300 flex items-center gap-2">
                                <x-lucide-coins class="w-4 h-4 text-orange-500" />
                                {{ __('Cost per repost (credits)') }}
                            </label>
                            <input type="number" id="edit_campaign_cost_per_repost"
                                wire:model.live="editCostPerRepost" min="1"
                                class="w-full rounded-xl border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-3 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-200"
                                placeholder="{{ __('Enter cost per repost') }}">
                            @error('editCostPerRepost')
                                <div class="flex items-center gap-2 text-red-600 dark:text-red-400 text-sm">
                                    <x-lucide-alert-circle class="w-4 h-4" />
                                    <span>{{ $message }}</span>
                                </div>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="edit_campaign_target_reposts"
                                class="text-sm font-semibold text-gray-700 dark:text-gray-300 flex items-center gap-2">
                                <x-lucide-target class="w-4 h-4 text-orange-500" />
                                {{ __('Campaign target repost count') }}
                            </label>
                            <input type="number" id="edit_campaign_target_reposts"
                                wire:model.live="editTargetReposts" min="1"
                                class="w-full rounded-xl border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-3 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-200"
                                placeholder="{{ __('Enter target reposts') }}">
                            @error('editTargetReposts')
                                <div class="flex items-center gap-2 text-red-600 dark:text-red-400 text-sm">
                                    <x-lucide-alert-circle class="w-4 h-4" />
                                    <span>{{ $message }}</span>
                                </div>
                            @enderror
                        </div>
                    </div>

                    {{-- Budget Warning Display --}}
                    @if ($showBudgetWarning)
                        <div
                            class="bg-gradient-to-r from-red-50 to-red-100 dark:from-red-900/20 dark:to-red-800/20 rounded-xl p-4 border border-red-200 dark:border-red-800">
                            <div class="flex items-center gap-3">
                                <x-lucide-alert-triangle class="w-5 h-5 text-red-600 dark:text-red-400" />
                                <div>
                                    <p class="text-sm font-semibold text-red-900 dark:text-red-100">
                                        {{ __('Budget Warning') }}
                                    </p>
                                    <p class="text-sm text-red-600 dark:text-red-400">
                                        {{ $budgetWarningMessage }}
                                    </p>
                                </div>
                                @if (str_contains($budgetWarningMessage, 'need') && str_contains($budgetWarningMessage, 'more credits'))
                                    <a href="{{ route('user.add-credits') }}" wire:navigate
                                        class="ml-auto bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded-lg text-xs font-medium transition-colors">
                                        {{ __('Buy Credits') }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endif

                    {{-- Budget Display --}}
                    @if ($editCostPerRepost && $editTargetReposts && $editCostPerRepost > 0 && $editTargetReposts > 0 && !$showBudgetWarning)
                        <div
                            class="bg-gradient-to-r from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 rounded-xl p-4 border border-blue-200 dark:border-blue-800">
                            <div class="flex items-center gap-3">
                                <x-lucide-calculator class="w-5 h-5 text-blue-600 dark:text-blue-400" />
                                <div>
                                    <p class="text-sm font-semibold text-blue-900 dark:text-blue-100">
                                        {{ __('New Total Campaign Budget') }}
                                    </p>
                                    <p class="text-lg font-bold text-blue-600 dark:text-blue-400">
                                        {{ number_format($editCostPerRepost * $editTargetReposts) }}
                                        {{ __('credits') }}
                                    </p>
                                    @if ($editOriginalBudget && $editCostPerRepost * $editTargetReposts > $editOriginalBudget)
                                        <p class="text-sm text-blue-600 dark:text-blue-400">
                                            {{ __('Additional credits needed:') }}
                                            {{ number_format($editCostPerRepost * $editTargetReposts - $editOriginalBudget) }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                        <button type="submit"
                            class="w-full transition-all duration-300 flex items-center justify-center gap-3 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 font-bold py-4 px-6 rounded-xl {{ $canSubmit ? 'bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white' : 'bg-gray-300 dark:bg-gray-600 text-gray-500 dark:text-gray-400 cursor-not-allowed' }}"
                            wire:loading.attr="disabled" @if (!$canSubmit) disabled @endif>
                            <span wire:loading.remove wire:target="updateCampaign">
                                <x-lucide-save class="w-5 h-5" />
                            </span>
                            <span wire:loading wire:target="updateCampaign">
                                <svg class="animate-spin w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                            </span>
                            <span wire:loading.remove wire:target="updateCampaign">
                                {{ __('Save Changes') }}
                            </span>
                            <span wire:loading wire:target="updateCampaign">{{ __('Saving...') }}</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Cancel Warning Modal --}}
    <div x-data="{ showCancelWarningModal: @entangle('showCancelWarningModal').live }" x-show="showCancelWarningModal" x-cloak
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
        class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4 z-50">

        <div
            class="w-full max-w-lg mx-auto rounded-2xl shadow-2xl bg-white dark:bg-slate-800 border border-gray-200 dark:border-gray-700 flex flex-col overflow-hidden">
            <div
                class="flex justify-between items-center p-6 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-red-50 to-red-100 dark:from-red-900/20 dark:to-red-800/20">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-red-500 rounded-xl flex items-center justify-center">
                        <x-lucide-alert-triangle class="w-5 h-5 text-white" />
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ __('Confirm Cancellation') }}
                    </h2>
                </div>
                <button x-on:click="showCancelWarningModal = false"
                    class="w-10 h-10 rounded-xl bg-white dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-all duration-200 flex items-center justify-center border border-gray-200 dark:border-gray-600">
                    <x-lucide-x class="w-5 h-5" />
                </button>
            </div>

            <div class="p-6 text-center">
                <div
                    class="w-20 h-20 bg-red-100 dark:bg-red-900/20 rounded-full flex items-center justify-center mx-auto mb-6">
                    <x-lucide-trash-2 class="w-10 h-10 text-red-600 dark:text-red-400" />
                </div>
                <p class="text-lg text-gray-700 dark:text-gray-300 mb-4">
                    {{ __('Are you sure you want to cancel this campaign? You will not be able to recover it.') }}
                </p>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">
                    {{ __('If you cancel this campaign, you will receive a 50% refund of the remaining budget: :amount credits.', ['amount' => number_format($refundAmount)]) }}
                </p>
                <div class="flex justify-center gap-4">
                    <button type="button" x-on:click="showCancelWarningModal = false"
                        class="flex-1 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 font-bold py-3 px-6 rounded-xl transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        {{ __('Keep Campaign') }}
                    </button>
                    <button wire:click="cancelCampaign"
                        class="flex-1 bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white font-bold py-3 px-6 rounded-xl transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5"
                        wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="cancelCampaign">{{ __('Confirm') }}</span>
                        <span wire:loading wire:target="cancelCampaign">{{ __('Cancelling...') }}</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Already Cancel Warning Modal --}}
    <div x-data="{ showAlreadyCancelledModal: @entangle('showAlreadyCancelledModal').live }" x-show="showAlreadyCancelledModal" x-cloak
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
        class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4 z-50">

        <div
            class="w-full max-w-lg mx-auto rounded-2xl shadow-2xl bg-white dark:bg-slate-800 border border-gray-200 dark:border-gray-700 flex flex-col overflow-hidden">
            <div
                class="flex justify-between items-center p-6 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-red-50 to-red-100 dark:from-red-900/20 dark:to-red-800/20">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-red-500 rounded-xl flex items-center justify-center">
                        <x-lucide-alert-triangle class="w-5 h-5 text-white" />
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ __('Campaign Cancelled') }}
                    </h2>
                </div>
                <button x-on:click="showAlreadyCancelledModal = false"
                    class="w-10 h-10 rounded-xl bg-white dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-all duration-200 flex items-center justify-center border border-gray-200 dark:border-gray-600">
                    <x-lucide-x class="w-5 h-5" />
                </button>
            </div>

            <div class="p-6 text-center">
                <div
                    class="w-20 h-20 bg-red-100 dark:bg-red-900/20 rounded-full flex items-center justify-center mx-auto mb-6">
                    <x-lucide-alert-triangle class="w-10 h-10 text-red-600 dark:text-red-400" />
                </div>
                <p class="text-lg text-gray-700 dark:text-gray-300 mb-4">
                    {{ __('This campaign has already been cancelled.') }}
                </p>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">
                    {{ __('This campaign has already been cancelled. You cannot make any changes to this campaign.') }}
                </p>
            </div>
        </div>
    </div>

    {{-- Details Modal --}}
    <div x-data="{ showDetailsModal: @entangle('showDetailsModal').live }" x-show="showDetailsModal" x-cloak
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
        class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4 z-50">

        <div
            class="w-full max-w-5xl mx-auto rounded-2xl shadow-2xl bg-white dark:bg-slate-800 border border-gray-200 dark:border-gray-700 flex flex-col overflow-hidden">

            @if ($showDetailsModal)
                <div
                    class="flex justify-between items-center p-6 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-red-50 to-red-100 dark:from-red-900/20 dark:to-red-800/20">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-orange-500 rounded-xl flex items-center justify-center">
                            <x-lucide-info class="w-5 h-5 text-white" />
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                            {{ __('Campaign Details') }}
                        </h2>
                    </div>
                    <button wire:click="closeViewDetailsModal()"
                        class="w-10 h-10 rounded-xl bg-white dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-all duration-200 flex items-center justify-center border border-gray-200 dark:border-gray-600">
                        <x-lucide-x class="w-5 h-5" />
                    </button>
                </div>

                <div class="p-6 text-center max-h-[80vh] overflow-y-auto">
                    <div class="flex flex-col md:flex-row gap-6">
                        <div class="w-full md:w-1/3 max-w-56 rounded-xl shadow-lg overflow-hidden">
                            <img class="w-full h-full object-cover"
                                src="{{ soundcloud_image($campaign->music?->artwork_url) }}">
                        </div>

                        <div class="flex-1">
                            <h2 class="text-3xl font-bold mb-2 text-gray-900 dark:text-white">
                                {{ $campaign->title ?? $campaign->music?->title }}</h2>
                            <p class="text-orange-500 mb-2">by {{ $campaign->user?->name ?? 'Unknown' }}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                                Genre: <span
                                    class="text-black dark:text-white">{{ $campaign->music?->genre ?? 'Unknown' }}</span>
                            </p>
                            <p class="text-gray-700 dark:text-gray-300 text-sm mb-4">
                                {{ $campaign->description ?? 'No description provided' }}
                            </p>

                            {{-- <div class="bg-gray-200 dark:bg-gray-800 p-4 rounded-lg">
                                <p class="text-gray-600 dark:text-gray-400 text-sm mb-2">Repost Progress:</p>
                                <div class="w-full h-3 bg-gray-300 dark:bg-gray-700 rounded-full">
                                    <div class="h-3 bg-orange-500 rounded-full"
                                        style="width: {{ ($campaign->completed_reposts / $campaign->target_reposts) * 100 }}%">
                                    </div>
                                </div>
                            </div> --}}
                        </div>
                    </div>

                    <!-- Campaign Stats -->
                    <div class="mt-10 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                        <div class="bg-gray-100 dark:bg-slate-700 p-5 rounded-lg shadow">
                            <h4 class="text-gray-600 dark:text-gray-400 text-sm">Completed Reposts</h4>
                            <p class="text-xl font-bold text-black dark:text-white">
                                {{ $campaign->completed_reposts }}
                            </p>
                        </div>
                        <div class="bg-gray-100 dark:bg-slate-700 p-5 rounded-lg shadow">
                            <h4 class="text-gray-600 dark:text-gray-400 text-sm">Playback Count</h4>
                            <p class="text-xl font-bold text-black dark:text-white">{{ $campaign->playback_count }}
                            </p>
                        </div>
                        <div class="bg-gray-100 dark:bg-slate-700 p-5 rounded-lg shadow">
                            <h4 class="text-gray-600 dark:text-gray-400 text-sm">Budget</h4>
                            <p class="text-xl font-bold text-black dark:text-white">
                                {{ number_format($campaign->budget) }}</p>
                        </div>
                        <div class="bg-gray-100 dark:bg-slate-700 p-5 rounded-lg shadow">
                            <h4 class="text-gray-600 dark:text-gray-400 text-sm">Credits Spent</h4>
                            <p class="text-xl font-bold text-black dark:text-white">
                                {{ number_format($campaign->credits_spent) }}</p>
                        </div>
                    </div>

                    <!-- Campaign Info -->
                    <div class="mt-10 bg-gray-100 dark:bg-slate-700 p-6 rounded-xl shadow">
                        <h3 class="text-xl font-bold mb-4 text-gray-900 dark:text-white">Campaign Settings</h3>
                        <div
                            class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 text-sm text-gray-700 dark:text-gray-300">
                            <p><span class="font-medium text-black dark:text-white">Status:</span>
                                {{ $campaign->status_label }}</p>
                            <p><span class="font-medium text-black dark:text-white">Start Date:</span>
                                {{ $campaign->start_date_formatted }}</p>
                            <p><span class="font-medium text-black dark:text-white">End Date:</span>
                                {{ $campaign->end_date_formatted }}</p>
                            <p><span class="font-medium text-black dark:text-white">Featured:</span>
                                {{ $campaign->feature_label }}</p>
                        </div>
                    </div>
                    <div
                        class="mt-10 w-full mx-auto bg-gray-100 dark:bg-gradient-to-r dark:from-zinc-700 dark:to-zinc-900 p-4 rounded-lg">
                        <div id="soundcloud-player-{{ $campaign->id }}" data-campaign-id="{{ $campaign->id }}"
                            wire:ignore>
                            <x-sound-cloud.sound-cloud-player :track="$campaign->music" :height="166" :visual="false" />
                        </div>
                    </div>

                </div>
            @endif
        </div>
    </div>
</section>
