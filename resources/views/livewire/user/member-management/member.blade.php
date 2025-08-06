<div x-data="{
    showModal: @entangle('showModal').live,
    showRepostsModal: @entangle('showRepostsModal').live
}">

    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-2xl md:text-3xl font-bold mb-2 dark:text-white">Browse Members</h1>
        <p class="text-text-gray text-sm md:text-base dark:text-white">Search, filter or browse the list of
            recommended members that can repost your music.</p>
    </div>

    <!-- Search and Filters -->
    <div class="mb-8 flex flex-col lg:flex-row gap-4">
        <!-- Search Input -->
        <div class="flex-1 relative">
            <svg class="absolute left-4 top-1/2 transform -translate-y-1/2 w-5 h-5 text-text-gray dark:text-gray-400 "
                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
            <input type="text" placeholder="Name or sub-genre" wire:model.live="search"
                class="w-full bg-card-blue border border-gray-600 rounded-lg pl-10 pr-4 py-3 text-gray-900 dark:text-white dark:bg-gray-900 placeholder-text-gray focus:outline-none focus:border-orange-500">
        </div>

        <!-- Filter Buttons -->
        <div class="flex flex-col sm:flex-row gap-4">
            <button
                class="bg-card-blue border border-gray-600 rounded-lg px-4 py-3 text-text-gray dark:text-white hover:border-orange-500 transition-colors flex items-center gap-2 min-w-[160px] justify-between">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z">
                    </path>
                </svg>
                Filter by track
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>

            <select wire:model.live="genreFilter"
                class="bg-card-blue border border-gray-600 dark:bg-gray-900 dark:text-white rounded-lg px-4 py-3 text-text-gray hover:border-orange-500 transition-colors min-w-[160px] focus:outline-none focus:border-orange-500">
                <option value="">Filter by genre</option>
                <option value="electronic">Electronic</option>
                <option value="hip-hop">Hip-Hop</option>
                <option value="pop">Pop</option>
                <option value="rock">Rock</option>
            </select>

            <select wire:model.live="costFilter"
                class="bg-card-blue border border-gray-600 dark:text-white dark:bg-gray-900 rounded-lg px-4 py-3 text-text-gray hover:border-orange-500 transition-colors min-w-[160px] focus:outline-none focus:border-orange-500">
                <option value="">Filter by cost</option>
                <option value="low-high">Low to High</option>
                <option value="high-low">High to Low</option>
            </select>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if (session()->has('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
            {{ session('error') }}
        </div>
    @endif

    <!-- Member Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 xl:grid-cols-3 gap-6">
        @forelse ($users as $user_)
            <div class="bg-card-blue rounded-lg p-6 border border-gray-600">
                <!-- Profile Header -->
                <div class="flex items-center gap-3 mb-6">
                    <div class="relative">
                        <img src="{{ auth_storage_url($user_->avatar) }}" alt="{{ $user_->name }}"
                            class="w-12 h-12 rounded-full">
                        <div
                            class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-500 rounded-full border-2 border-card-blue">
                        </div>
                    </div>
                    <div>
                        <h3 class="font-semibold text-lg dark:text-white">{{ $user_->name }}</h3>
                        <p class="text-text-gray text-sm dark:text-white">{{ $user_->created_at->format('M d, Y') }}</p>
                    </div>
                </div>

                <!-- Genre Tags -->
                <div class="flex flex-wrap gap-2 mb-4">
                    <span class="bg-gray-600 text-white text-xs px-2 py-1 rounded">Ambient</span>
                    <span class="bg-gray-600 text-white text-xs px-2 py-1 rounded">Dubstep</span>
                    <span class="bg-gray-600 text-white text-xs px-2 py-1 rounded">Electronic</span>
                    <span class="bg-gray-600 text-white text-xs px-2 py-1 rounded">Techno</span>
                </div>

                <!-- Repost Price -->
                @php
                    $followerCount = $userinfo ? $userinfo->count() : 0;
                    $credit = max(1, floor($followerCount / 100));
                @endphp

                <div class="flex justify-between items-center w-full mb-4">
                    <p class="text-text-gray text-sm dark:text-white">Repost price:</p>
                    <p class="text-sm font-medium dark:text-white">
                        {{ $credit }} Credit{{ $credit > 1 ? 's' : '' }}
                    </p>
                </div>

                <!-- Stats -->
                <div class="grid grid-cols-3 gap-4 mb-6">
                    <div class="text-center">
                        <p class="text-text-gray text-xs mb-1 dark:text-white">Credibility</p>
                        <p class="text-green-400 font-bold">83%</p>
                    </div>
                    <div class="text-center">
                        <p class="text-text-gray text-xs mb-1 dark:text-white">Efficiency</p>
                        <p class="text-orange-500 font-bold">92%</p>
                    </div>
                    <div class="text-center">
                        <p class="text-text-gray text-xs mb-1 dark:text-white">Total Reposts</p>
                        <p class="text-black font-bold dark:text-white">156</p>
                    </div>
                </div>

                <!-- Request Button -->
                <button wire:click="openModal('{{ $user_->urn }}')"
                    class="w-full max-w-xs bg-orange-500 hover:bg-orange-600 text-white font-semibold py-3 rounded-lg transition-colors dark:text-white">
                    Request
                </button>
            </div>
        @empty
            <div class="col-span-full text-center py-8">
                <p class="text-text-gray dark:text-white">No members found.</p>
            </div>
        @endforelse
    </div>

    <div x-data="{ showModal: @entangle('showModal').live }" x-show="showModal" x-cloak x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4 z-50">

        <div
            class="w-full max-w-3xl mx-auto rounded-2xl shadow-2xl bg-white dark:bg-slate-800 border border-gray-200 dark:border-gray-700 flex flex-col max-h-[80vh] overflow-hidden">

            {{-- HEADER --}}
            <div
                class="flex justify-between items-center p-6 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-orange-50 to-orange-100 dark:from-orange-900/20 dark:to-orange-800/20">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-orange-500 rounded-xl flex items-center justify-center">
                        <i data-lucide="music" class="w-5 h-5 text-white"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ __('Choose a track or playlist') }}
                    </h2>
                </div>
                <button x-on:click="showModal = false"
                    class="w-10 h-10 rounded-xl bg-white dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-all duration-200 flex items-center justify-center border border-gray-200 dark:border-gray-600">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            @if ($showModal)
                {{-- TABS --}}
                <div class="flex border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                    <button wire:click="setActiveTab('tracks')"
                        class="flex-1 py-4 px-6 text-center font-semibold text-base transition-all duration-300 ease-in-out border-b-2 hover:bg-white dark:hover:bg-gray-700 {{ $activeTab === 'tracks' ? 'border-orange-500 text-orange-600 bg-white dark:bg-gray-700' : 'border-transparent text-gray-600 dark:text-gray-400' }}">
                        <div class="flex items-center justify-center gap-2">
                            <i data-lucide="music" class="w-4 h-4"></i>
                            {{ __('Tracks') }}
                        </div>
                    </button>
                    <button wire:click="setActiveTab('playlists')"
                        class="flex-1 py-4 px-6 text-center font-semibold text-base transition-all duration-300 ease-in-out border-b-2 hover:bg-white dark:hover:bg-gray-700 {{ $activeTab === 'playlists' ? 'border-orange-500 text-orange-600 bg-white dark:bg-gray-700' : 'border-transparent text-gray-600 dark:text-gray-400' }}">
                        <div class="flex items-center justify-center gap-2">
                            <i data-lucide="list-music" class="w-4 h-4"></i>
                            {{ __('Playlists') }}
                        </div>
                    </button>
                </div>

                {{-- SEARCH --}}
                <div class="w-full max-w-2xl mx-auto mt-6 flex flex-col overflow-hidden">
                    <div class="p-4">
                        <label for="track-link-search" class="text-xl font-semibold text-gray-700 dark:text-gray-200">
                            Paste a SoundCloud profile or track link
                        </label>
                        <div class="flex w-full mt-2">
                            <input type="text" id="track-link-search"
                                placeholder="Paste a SoundCloud profile or track link"
                                class="flex-grow p-3 text-gray-700 dark:text-gray-200 bg-white dark:bg-slate-700 placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-orange-500 transition-colors duration-200 border border-gray-300 dark:border-gray-600 rounded-l-md">
                            <button type="submit"
                                class="bg-orange-500 text-white p-3 w-14 flex items-center justify-center hover:bg-orange-600 transition-colors duration-200 rounded-r-md">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- CONTENT LIST --}}
                    <div class="h-full overflow-y-auto px-4 pb-6 space-y-1">
                        @if ($activeTab === 'tracks')
                            @forelse ($tracks as $track_)
                                <div wire:click="openRepostsModal({{ $track_->id }})"
                                    class="p-2 flex items-center space-x-4 cursor-pointer hover:bg-gray-100 dark:hover:bg-slate-700/50 rounded-md transition-colors duration-200">
                                    <div class="flex-shrink-0">
                                        <img class="h-12 w-12 rounded object-cover shadow"
                                            src="{{ soundcloud_image($track_->artwork_url) }}"
                                            alt="{{ $track_->title }}" />
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm text-gray-500 dark:text-gray-400 truncate">
                                            {{ $track_->type }} • {{ $track_->author_username }}
                                        </p>
                                        <p class="font-semibold text-gray-800 dark:text-white truncate">
                                            {{ $track_->title }}
                                        </p>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <i data-lucide="chevron-right"
                                            class="w-5 h-5 text-gray-400 group-hover:text-orange-500 transition-colors"></i>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-12 text-gray-500 dark:text-gray-400">
                                    <i data-lucide="music"
                                        class="w-12 h-12 text-gray-400 dark:text-gray-500 mx-auto mb-3"></i>
                                    <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300">No tracks found
                                    </h3>
                                    <p>No matching tracks found for your search.</p>
                                </div>
                            @endforelse

                            @if (count($tracks) < count($allTracks))
                                <div class="text-center mt-6">
                                    <button wire:click="loadMoreTracks"
                                        class="font-semibold text-orange-500 hover:text-orange-600 dark:text-orange-400 dark:hover:text-orange-300 transition-colors duration-200">
                                        Load more
                                    </button>
                                </div>
                            @endif
                        @else
                            {{-- PLAYLISTS TAB --}}
                            @forelse ($playlists as $playlist_)
                                <div wire:click="openPlaylistTracksModal({{ $playlist_->id }})"
                                    class="p-2 flex items-center space-x-4 cursor-pointer hover:bg-gray-100 dark:hover:bg-slate-700/50 rounded-md transition-colors duration-200">
                                    <div class="flex-shrink-0">
                                        <img class="h-12 w-12 rounded object-cover shadow"
                                            src="{{ soundcloud_image($playlist_->artwork_url) }}"
                                            alt="{{ $playlist_->title }}" />
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="font-semibold text-gray-800 dark:text-white truncate">
                                            {{ $playlist_->title }}
                                        </p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 truncate">
                                            {{ $playlist_->track_count }} {{ __('tracks') }}
                                        </p>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <i data-lucide="chevron-right"
                                            class="w-5 h-5 text-gray-400 group-hover:text-orange-500 transition-colors"></i>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-12 text-gray-500 dark:text-gray-400">
                                    <i data-lucide="list-music"
                                        class="w-12 h-12 text-gray-400 dark:text-gray-500 mx-auto mb-3"></i>
                                    <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300">No playlists
                                        found</h3>
                                    <p>No matching playlists found for your search.</p>
                                </div>
                            @endforelse
                            @if (count($playlists) < count($allPlaylists))
                                <div class="text-center mt-6">
                                    <button wire:click="loadMorePlaylists"
                                        class="font-semibold text-orange-500 hover:text-orange-600 dark:text-orange-400 dark:hover:text-orange-300 transition-colors duration-200">
                                        Load more
                                    </button>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            @endif

        </div>
    </div>

    {{-- Playlist Tracks Modal --}}
    <div x-data="{ showPlaylistTracksModal: @entangle('showPlaylistTracksModal').live }" x-show="showPlaylistTracksModal" x-cloak
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
        class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4 z-50">

        <div wire:click.away="showRepostsModal = false"
            class="w-full max-w-3xl mx-auto rounded-2xl shadow-2xl bg-white dark:bg-slate-800 border border-gray-200 dark:border-gray-700 flex flex-col max-h-[80vh] overflow-hidden">

            <div
                class="flex justify-between items-center p-6 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-orange-50 to-orange-100 dark:from-orange-900/20 dark:to-orange-800/20">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-orange-500 rounded-xl flex items-center justify-center">
                        <i data-lucide="music" class="w-5 h-5 text-white"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ __('Playlist Tracks') }}
                    </h2>
                </div>
                <button x-on:click="showPlaylistTracksModal= false"
                    class="w-10 h-10 rounded-xl bg-white dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-all duration-200 flex items-center justify-center border border-gray-200 dark:border-gray-600">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <div class="flex-1 overflow-y-auto">
                <div class="p-6">
                    @if ($playlistTracks)
                        @foreach ($playlistTracks as $playlistTrack)
                            <div wire:click="openRepostsModal({{ $playlistTrack->id }})"
                                class="p-4 flex items-center space-x-4 cursor-pointer hover:bg-gray-50 dark:hover:bg-slate-700 rounded-xl transition-all duration-200 border border-transparent hover:border-orange-200 dark:hover:border-orange-800 group">
                                <div class="flex-shrink-0">
                                    <img class="h-14 w-14 rounded-xl object-cover shadow-md"
                                        src="{{ storage_url($playlistTrack->artwork_url) }}"
                                        alt="{{ $playlistTrack->title }}" />
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p
                                        class="text-base font-semibold text-gray-900 dark:text-white truncate group-hover:text-orange-600 dark:group-hover:text-orange-400 transition-colors">
                                        {{ $playlistTrack->title }}
                                    </p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 truncate">
                                        by
                                        <strong
                                            class="text-orange-600 dark:text-orange-400">{{ $playlistTrack->author_username }}</strong>
                                        <span class="ml-2 text-xs text-gray-400">{{ $playlistTrack->genre }}</span>
                                    </p>
                                    <span
                                        class="inline-block bg-gray-100 dark:bg-slate-600 text-xs px-3 py-1 rounded-full text-gray-700 dark:text-gray-300 mt-2 font-mono">{{ $playlistTrack->isrc }}</span>
                                </div>
                                <div class="flex-shrink-0">
                                    <i data-lucide="chevron-right"
                                        class="w-5 h-5 text-gray-400 group-hover:text-orange-500 transition-colors"></i>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
    {{-- Reposts Modal --}}
    <div x-data="{ showRepostsModal: @entangle('showRepostsModal').live }" x-show="showRepostsModal" x-cloak
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
        class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4 z-50">

        <div
            class="w-full max-w-3xl mx-auto rounded-2xl shadow-2xl bg-white dark:bg-slate-800 border border-gray-200 dark:border-gray-700 flex flex-col max-h-[80vh] overflow-hidden">

            <div
                class="flex justify-between items-center p-6 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-orange-50 to-orange-100 dark:from-orange-900/20 dark:to-orange-800/20">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-orange-500 rounded-xl flex items-center justify-center">
                        <i data-lucide="music" class="w-5 h-5 text-white"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ __('Requesting Reposts') }}
                    </h2>
                </div>
                <button x-on:click="showRepostsModal= false"
                    class="w-10 h-10 rounded-xl bg-white dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-all duration-200 flex items-center justify-center border border-gray-200 dark:border-gray-600">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <div class="flex-1 overflow-y-auto">
                <div class="p-6">
                    @if ($track)
                        <div
                            class="p-4 flex items-center space-x-4 cursor-pointer bg-gray-50 dark:bg-slate-700 rounded-xl border border-transparent ">
                            <div class="flex-shrink-0">
                                <img class="h-14 w-14 rounded-xl object-cover shadow-md"
                                    src="{{ storage_url($track->artwork_url) }}" alt="{{ $track->title }}" />
                            </div>
                            <div class="flex-1 min-w-0">
                                <p
                                    class="text-base font-semibold text-gray-900 dark:text-white truncate group-hover:text-orange-600 dark:group-hover:text-orange-400 transition-colors">
                                    {{ $track->title }}
                                </p>
                                <p class="text-sm text-gray-500 dark:text-gray-400 truncate">
                                    by
                                    <strong
                                        class="text-orange-600 dark:text-orange-400">{{ $track->author_username }}</strong>
                                    <span class="ml-2 text-xs text-gray-400">{{ $track->genre }}</span>
                                </p>
                                <span
                                    class="inline-block bg-gray-100 dark:bg-slate-600 text-xs px-3 py-1 rounded-full text-gray-700 dark:text-gray-300 mt-2 font-mono">{{ $track->isrc }}</span>
                            </div>
                            <div class="flex-shrink-0">
                                <i data-lucide="chevron-right"
                                    class="w-5 h-5 text-gray-400 group-hover:text-orange-500 transition-colors"></i>
                            </div>
                        </div>
                        {{-- User Info --}}
                        <div
                            class="p-4 flex justify-between space-x-4  rounded-xl border border-gray-200 dark:border-gray-700 mt-4">
                            <div class="flex gap-3">
                                <div class="flex-shrink-0">
                                    <img class="h-14 w-14 rounded-full object-cover shadow-md"
                                        src="{{ auth_storage_url($user->avatar) }}" alt="{{ $user->name }}" />
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p
                                        class="text-base font-semibold text-gray-900 dark:text-white truncate group-hover:text-orange-600 dark:group-hover:text-orange-400 transition-colors">
                                        {{ $user->name }}
                                    </p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 truncate">
                                        {{ $user->email }}
                                    </p>

                                </div>
                            </div>
                            <div class="grid grid-cols-1 gap-3 text-right">
                                <div class="flex items-center gap-2 justify-end">
                                    <i data-lucide="users" class="w-4 h-4 text-gray-500"></i>
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Followers:</span>
                                    <span
                                        class="text-orange-500 dark:text-orange-400 font-bold">{{ $user->userInfo?->followers_count ?? 0 }}</span>
                                </div>
                                <div class="flex items-center gap-2 justify-end">
                                    <i data-lucide="repeat" class="w-4 h-4 text-gray-500"></i>
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Reposts:</span>
                                    <span
                                        class="text-orange-500 dark:text-orange-400 font-bold">{{ $user->userInfo?->reposts_count ?? 0 }}</span>
                                </div>
                                <div class="flex items-center gap-2 justify-end">
                                    <i data-lucide="heart" class="w-4 h-4 text-gray-500"></i>
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Likes:</span>
                                    <span
                                        class="text-green-500 dark:text-green-400 font-bold">{{ $user->userInfo?->like_count ?? 0 }}</span>
                                </div>
                            </div>

                        </div>
                        <div class="flex items-center justify-between mt-4">
                            <span class="dark:text-white font-medium">Repost price:</span>
                            <div class="flex items-center gap-2">
                                <div class="text-gray-500 dark:text-gray-400">
                                    <!-- Repost Icon -->
                                    <svg width="26" height="18" viewBox="0 0 26 18" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <rect x="1" y="1" width="24" height="16" rx="3"
                                            fill="none" stroke="currentColor" stroke-width="2" />
                                        <circle cx="8" cy="9" r="3" fill="none"
                                            stroke="currentColor" stroke-width="2" />
                                    </svg>
                                </div>
                                <span class="text-orange-500 dark:text-orange-400 font-bold">1 Credit</span>
                            </div>
                        </div>
                        <div class="p-4 bg-gray-50 dark:bg-slate-700 rounded-xl border-l-4 border-orange-500 mt-4">
                            <p class="text-gray-400 dark:text-gray-300 text-sm leading-relaxed">
                                Your track will be shared across our network of 50K+ followers on SoundCloud,. Expected
                                reach: 10-15K impressions within 48 hours.
                            </p>
                        </div>
                        <!-- Confirm Button -->
                        <div class="mt-6 flex justify-center gap-3">
                            <button wire:click="closeRepostModal"
                                class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">
                                Cancel
                            </button>
                            <button wire:click="createRepostsRequest()"
                                class="px-4 py-2 bg-orange-600 text-white rounded hover:bg-orange-700">
                                Send Request
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
