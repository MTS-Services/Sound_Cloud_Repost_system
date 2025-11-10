<main>
    <x-slot name="page_slug">campaign-feed</x-slot>
    <x-slot name="title">Campaign Feed</x-slot>
    <!-- Dashboard Section -->
    <section class="flex flex-col lg:flex-row gap-6 p-6">
        <!-- LEFT SIDE -->
        <div class="w-full">
            <!-- Header Section -->
            <div class="w-full mt-4 relative">
                <div
                    class="flex flex-col-reverse 2xl:flex-row justify-between border-b border-gray-200 dark:border-gray-700 gap-2">

                    <nav class="-mb-px flex space-x-8">
                        <a href="{{ route('user.cm.campaigns2', ['tab' => 'recommendedPro']) }}" wire:navigate
                            class="py-3 px-2 font-semibold {{ $activeTab == 'recommendedPro' ?'border-b-2 border-orange-500 text-orange-600':'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300' }}">
                            Recommended Pro <span class="text-xs ml-2 text-orange-500">12</span>
                        </a>
                        <a href="{{ route('user.cm.campaigns2', ['tab' => 'recommended']) }}" wire:navigate
                            class="py-3 px-2 font-semibold {{ $activeTab == 'recommended' ?'border-b-2 border-orange-500 text-orange-600':'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300' }}">
                            Recommended <span class="text-xs ml-2 text-orange-500">8</span>
                        </a>
                        <a href="{{ route('user.cm.campaigns2', ['tab' => 'all']) }}" wire:navigate
                            class="py-3 px-2 font-semibold {{ $activeTab == 'all' ?'border-b-2 border-orange-500 text-orange-600':'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300' }}">
                            All <span class="text-xs ml-2 text-orange-500">25</span>
                        </a>
                    </nav>
                    <!-- Stats Panel -->

                    <div id="statsPanel" class="hidden my-6 2xl:hidden">
                        <x-dashboard-summary :earnings="user()->repost_price" :dailyRepostCurrent="1" :dailyRepostMax="20" :responseRate="user()->responseRate()"
                            :pendingRequests="10" :requestLimit="25" :credits="userCredits()" :campaigns="10" :campaignLimit="proUser() ? 10 : 2" />
                    </div>

                    <!-- Start Campaign & Toggle Stats -->
                    <div class="flex justify-between items-center">
                        <x-gabutton variant="primary" class="mb-2">
                            <span class="mr-1">＋</span> Start a new campaign
                        </x-gabutton>
                        <button id="toggleStatsBtn"
                            class="flex items-center gap-1 text-sm text-orange-500 ml-4 2xl:hidden">
                            <span id="toggleText">Show Stats</span>
                            <svg xmlns="http://www.w3.org/2000/svg" id="toggleIcon" class="w-4 h-4" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="m6 9 6 6 6-6" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div x-data="{ openFilterByTrack: false, openFilterByGenre: false }"
                class="flex flex-col sm:flex-row sm:items-center sm:justify-start gap-4 mt-4 mb-2 relative">

                <!-- Filters wrapper (track + genre side by side even on mobile) -->
                <div class="flex w-full sm:w-auto gap-2 order-1">
                    <!-- Filter by track -->
                    <div class="relative flex-1 sm:flex-none">
                        <button @click="openFilterByTrack = !openFilterByTrack , openFilterByGenre = false"
                            wire:click="getAllTrackTypes" @click.outside="openFilterByTrack = false"
                            class="bg-orange-100 !hover:bg-orange-400 text-orange-600 px-4 py-2 rounded-md flex items-center gap-2 text-sm font-medium transition-colors cursor-pointer w-full sm:w-auto">
                            Filter by track type /pop
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="m6 9 6 6 6-6" />
                            </svg>
                        </button>

                        <div x-show="openFilterByTrack" x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="transform opacity-100 scale-100"
                            x-transition:leave-end="transform opacity-0 scale-95"
                            class="absolute right-0 mt-2 w-56 rounded-md shadow-lg z-100">
                            <div class="rounded-md shadow-xs bg-white dark:bg-slate-800">
                                <div class="py-1">
                                    <button wire:click="filterByTrackType('all')"
                                        class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 border-b border-gray-100 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-700 w-full text-left">
                                        All
                                    </button>
                                    <button wire:click="filterByTrackType('{{ Track::class }}')"
                                        class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 border-b border-gray-100 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-700 w-full text-left">
                                        Tracks
                                    </button>
                                    <button wire:click="filterByTrackType('{{ Playlist::class }}')"
                                        class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 w-full text-left">
                                        Playlists
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filter by genre -->
                    <div class="relative flex-1 sm:flex-none">
                        <button @click="openFilterByGenre = !openFilterByGenre, openFilterByTrack = false"
                            class="{{ true ? ' bg-orange-100 ' : ' bg-transparent border border-gray-300 dark:border-gray-600 ' }} hover:bg-orange-300 text-orange-600 px-4 py-2 rounded-md flex items-center gap-2 text-sm font-medium transition-colors w-full sm:w-auto">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M9 18V5l12-2v13" />
                                <circle cx="6" cy="18" r="3" />
                                <circle cx="18" cy="16" r="3" />
                            </svg>
                            Filter by genre / all
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="m6 9 6 6 6-6" />
                            </svg>
                        </button>

                        <div x-show="openFilterByGenre" x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="transform opacity-100 scale-100"
                            x-transition:leave-end="transform opacity-0 scale-95"
                            class="absolute left-0 mt-2 w-96 rounded-md shadow-lg z-100">
                            <div class="rounded-md shadow-xs bg-white dark:bg-slate-800"
                                @click.outside="openFilterByGenre = false">
                                <div class="flex flex-wrap gap-2 p-2">
                                    @foreach (AllGenres() as $genre)
                                        <span @click="$wire.toggleGenre('{{ $genre }}');"
                                            class="px-3 py-2 text-sm rounded-md cursor-pointer
                                {{ true ? 'bg-orange-500 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300' }}">
                                            {{ $genre }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Search box (always below on mobile) -->
                <div x-data="{ showInput: false }"
                    class="w-full flex-1 relative flex items-center text-gray-600 dark:text-gray-400 border border-gray-300 dark:border-gray-600 rounded mt-3 sm:mt-0 order-2">
                    <svg class="w-4 h-4 absolute left-2 top-1/2 -translate-y-1/2 text-gray-500 dark:text-slate-300 pointer-events-none"
                        fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21 21l-4.35-4.35M17 10a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>

                    <div x-data="{ showInput: false }"
                        class="w-full relative flex items-center text-gray-600 dark:text-gray-400 rounded">
                        <div x-show="!showInput" {{-- @click="showInput = true" wire:click="getAllTags" --}}
                            class="pl-7 pr-2 py-2 cursor-pointer whitespace-nowrap dark:text-slate-300 w-full">
                            <span>{{ $search ? $search : 'Type to search tags...' }}</span>
                        </div>

                        <div x-show="showInput" x-cloak class="w-full">
                            <input type="text" wire:model.debounce.300ms="search"
                                wire:focus="$set('showSuggestions', true)" wire:blur="hideSuggestions"
                                placeholder="{{ $search ? $search : 'Type to search tags...' }}"
                                class="w-full py-2 pl-7 pr-2 dark:text-slate-300 dark:border-red-400 dark:bg-gray-800 rounded focus:outline-none focus:ring-1 focus:ring-red-400"
                                @click.outside="showInput = false" x-ref="searchInput" x-init="$watch('showInput', (value) => { if (value) { $nextTick(() => $refs.searchInput.focus()) } })"
                                autocomplete="off" />
                        </div>

                        <div x-show="showInput"
                            class="absolute left-0 mt-12 w-full sm:w-80 rounded-md shadow-lg z-50">
                            @if ($showSuggestions && !empty($suggestedTags))
                                <div
                                    class="w-full flex flex-wrap gap-2 absolute left-0 top-full z-50 bg-white dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg max-h-60 overflow-y-auto py-2">
                                    @foreach ($suggestedTags as $index => $tag)
                                        <span wire:click="selectTag('{{ $tag }}')"
                                            class="inline-flex items-center px-3 py-1 rounded-sm text-sm font-medium bg-blue-100 text-blue-800 border border-blue-200 ml-2 cursor-default">
                                            {{ $tag }}
                                            <button type="button"
                                                class=" text-blue-600 hover:text-blue-800 focus:outline-none cursor-pointer"
                                                onclick="event.stopPropagation(); @this.call('removeTag', {{ $index }})">
                                            </button>
                                        </span>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <!-- Campaign Cards -->
            @switch($activeTab)
                @case('all')
                    <livewire:user.campaign-management.all-campaign />
                @break

                @case('recommended')
                    <livewire:user.campaign-management.recommanded-campaign />
                @break

                @default
                    <livewire:user.campaign-management.recommanded-pro-campaign />
            @endswitch
        </div>
        <div class="max-w-[400px] hidden 2xl:block" x-cloak x-transition>
            <x-dashboard-summary :earnings="user()->repost_price" :dailyRepostCurrent="1" :dailyRepostMax="20" :responseRate="user()->responseRate()"
                :pendingRequests="10" :requestLimit="25" :credits="userCredits()" :campaigns="10" :campaignLimit="proUser() ? 10 : 2" />
        </div>
    </section>

    <script>
        // Stats toggle
        document.getElementById('toggleStatsBtn').addEventListener('click', () => {
            const stats = document.getElementById('statsPanel');
            const text = document.getElementById('toggleText');
            stats.classList.toggle('hidden');
            text.textContent = stats.classList.contains('hidden') ? 'Show Stats' : 'Hide Stats';
        });
    </script>
</main>
