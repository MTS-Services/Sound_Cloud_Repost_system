<section>
    <div>
        <x-slot name="page_slug">chart</x-slot>
        <div class="py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div
                    class="bg-gradient-to-r bg-gray-100 dark:bg-black text-gray-900 dark:text-white p-8 rounded-2xl mb-8 border dark:border-gray-800">
                    <div class="max-w-4xl mx-auto">
                        <div class="flex items-center justify-between flex-wrap gap-4">
                            <div class="flex items-center gap-3">
                                <div class="bg-orange-500 p-3 rounded-xl flex justify-center sm:justify-start ">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-trophy w-6 h-6 sm:w-8 sm:h-8 sm:mt-0">
                                        <path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"></path>
                                        <path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"></path>
                                        <path d="M4 22h16"></path>
                                        <path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20.24 7 22"></path>
                                        <path d="M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20.24 17 22"></path>
                                        <path d="M18 2H6v7a6 6 0 0 0 12 0V2Z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h1
                                        class="text-base sm:text-3xl md:text-4xl font-bold mb-2 text-gray-900 dark:text-white">
                                        Weekly Top {{ $topSources->count() }} / 20 Chart
                                    </h1>
                                    <!-- ✅ Responsive fix -->
                                    <div
                                        class="flex flex-col sm:flex-row sm:items-center sm:gap-6 gap-2 text-gray-500 dark:text-gray-300">
                                        <div class="flex items-center gap-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-calendar w-4 h-4">
                                                <path d="M8 2v4"></path>
                                                <path d="M16 2v4"></path>
                                                <rect width="18" height="18" x="3" y="4" rx="2"></rect>
                                                <path d="M3 10h18"></path>
                                            </svg>
                                            <span>Week of
                                                {{ isset($period['start']) ? $period['start']->format('M d, Y') : 'N/A' }}</span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-clock w-4 h-4">
                                                <circle cx="12" cy="12" r="10"></circle>
                                                <polyline points="12 6 12 12 16 14"></polyline>
                                            </svg>
                                            <span>Updated
                                                {{ isset($period['end']) ? $period['end']->format('M d, Y') : 'N/A' }}</span>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="flex items-center gap-2">
                                {{-- <a href="{{ route('user.charts') }}" wire:navigate
                                    class="flex items-center gap-2 bg-gray-200 hover:bg-white dark:bg-gray-800 dark:hover:bg-gray-700 px-4 py-2 rounded-xl transition-all duration-200 font-medium border dark:border-gray-700 text-gray-900 dark:text-white">

                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-refresh-cw w-4 h-4">
                                        <path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"></path>
                                        <path d="M21 3v5h-5"></path>
                                        <path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16"></path>
                                        <path d="M8 16H3v5"></path>
                                    </svg>
                                    <span class="hidden sm:inline" wire:loading.remove
                                        wire:target="refresh">Refresh</span>
                                </a> --}}

                                <button wire:click="refresh()"
                                    class="flex items-center gap-2 bg-gray-200 hover:bg-white dark:bg-gray-800 dark:hover:bg-gray-700 px-4 py-2 rounded-xl transition-all duration-200 font-medium border dark:border-gray-700 text-gray-900 dark:text-white">
                                    <span wire:loading.remove wire:target="refresh" class="hidden sm:inline">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="lucide lucide-refresh-cw w-4 h-4">
                                            <path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"></path>
                                            <path d="M21 3v5h-5"></path>
                                            <path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16"></path>
                                            <path d="M8 16H3v5"></path>
                                        </svg>
                                    </span>
                                    <span wire:loading wire:target="refresh" class="animate-spin ">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="lucide lucide-refresh-cw w-4 h-4">
                                            <path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"></path>
                                            <path d="M21 3v5h-5"></path>
                                            <path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16"></path>
                                            <path d="M8 16H3v5"></path>
                                        </svg>
                                    </span>
                                    <span wire:target="refresh">Refresh</span>
                                </button>
                                <button
                                    class="flex items-center gap-2 bg-orange-500 hover:bg-orange-600 px-4 py-2 rounded-xl transition-all duration-200 font-medium">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-share2 w-4 h-4">
                                        <circle cx="18" cy="5" r="3"></circle>
                                        <circle cx="6" cy="12" r="3"></circle>
                                        <circle cx="18" cy="19" r="3"></circle>
                                        <line x1="8.59" x2="15.42" y1="13.51" y2="17.49"></line>
                                        <line x1="15.41" x2="8.59" y1="6.51" y2="10.49"></line>
                                    </svg>
                                    <span class="hidden sm:inline">Share</span>
                                </button>
                            </div>
                        </div>
                        <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div
                                class="bg-gray-200 dark:bg-gray-800  backdrop-blur-sm p-4 rounded-xl border border-gray-200 dark:border-gray-700">
                                <div class="text-base sm:text-2xl font-bold">{{ $topSources->count() }}</div>

                                <div class="text-gray-700 dark:text-gray-300 text-sm">Top Tracks / Playlists</div>
                            </div>
                            <div
                                class="bg-gray-200 dark:bg-gray-800  backdrop-blur-sm p-4 rounded-xl border border-gray-200 dark:border-gray-700">
                                <div class="ext-base sm:text-2xl font-bold">Weekly</div>
                                <div class="text-gray-700 dark:text-gray-300 text-sm">Updates</div>
                            </div>
                            <div
                                class="bg-gray-200 dark:bg-gray-800  backdrop-blur-sm p-4 rounded-xl border border-gray-200 dark:border-gray-700">
                                <div class="ext-base sm:text-2xl font-bold">Live</div>
                                <div class="text-gray-700 dark:text-gray-300 text-sm">Engagement</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div x-data="{ activeTab: @entangle('activeTab').live, playing: @entangle('playing').live }">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-4">
                            <h2 class="text-xl font-bold text-gray-800 dark:text-white">{{ $topSources->count() }}
                                Tracks / Playlists
                            </h2>
                        </div>
                        <div
                            class="flex items-center gap-1 vg-gray-200 dark:bg-gray-800 rounded-xl p-1 border border-gray-200 dark:border-gray-700">
                            <button @click="activeTab = 'listView'" wire:click="activeTab = 'listView'"
                                class="flex items-center gap-2 px-3 py-2 rounded-lg transition-all duration-200"
                                :class="{ 'bg-orange-500 text-white shadow-lg': activeTab === 'listView', 'text-gray-700 dark:text-gray-400 dark:hover:text-white hover:bg-gray-200 dark:hover:bg-gray-700': activeTab !== 'listView' }"
                                title="List View">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="lucide lucide-list w-4 h-4">
                                    <line x1="8" x2="21" y1="6" y2="6"></line>
                                    <line x1="8" x2="21" y1="12" y2="12"></line>
                                    <line x1="8" x2="21" y1="18" y2="18"></line>
                                    <line x1="3" x2="3.01" y1="6" y2="6"></line>
                                    <line x1="3" x2="3.01" y1="12" y2="12"></line>
                                    <line x1="3" x2="3.01" y1="18" y2="18"></line>
                                </svg>
                                <span class="hidden sm:inline text-sm font-medium">List View</span>
                            </button>
                            <button @click="activeTab = 'gridView'" wire:click="activeTab = 'gridView'"
                                class="flex items-center gap-2 px-3 py-2 rounded-lg transition-all duration-200"
                                :class="{ 'bg-orange-500 text-white shadow-lg': activeTab === 'gridView', 'text-gray-700 dark:text-gray-400 dark:hover:text-white hover:bg-gray-200 dark:hover:bg-gray-700': activeTab !== 'gridView' }"
                                title="Grid View">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="lucide lucide-grid3x3 w-4 h-4">
                                    <rect width="18" height="18" x="3" y="3" rx="2"></rect>
                                    <path d="M3 9h18"></path>
                                    <path d="M3 15h18"></path>
                                    <path d="M9 3v18"></path>
                                    <path d="M15 3v18"></path>
                                </svg>
                                <span class="hidden sm:inline text-sm font-medium">Grid View</span>
                            </button>
                            <button @click="activeTab = 'compactView'" wire:click="activeTab = 'compactView'"
                                class="flex items-center gap-2 px-3 py-2 rounded-lg transition-all duration-200"
                                :class="{ 'bg-orange-500 text-white shadow-lg': activeTab === 'compactView', 'text-gray-700 dark:text-gray-400 dark:hover:text-white hover:bg-gray-200 dark:hover:bg-gray-700': activeTab !== 'compactView' }"
                                title="Compact View">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="lucide lucide-bar-chart3 w-4 h-4">
                                    <path d="M3 3v18h18"></path>
                                    <path d="M18 17V9"></path>
                                    <path d="M13 17V5"></path>
                                    <path d="M8 17v-3"></path>
                                </svg>
                                <span class="hidden sm:inline text-sm font-medium">Compact View</span>
                            </button>
                        </div>
                    </div>
                    {{-- Compact view --}}
                    <div x-show="activeTab === 'compactView'"
                        class="transition-all duration-500 opacity-100 scale-100">

                        {{-- Responsive wrapper --}}
                        <div class="space-y-3 md:space-y-0 w-full">

                            {{-- Desktop/Tablet Table --}}
                            <div class="hidden md:block">
                                <div
                                    class="w-full lg:min-w-[768px] bg-gray-200 dark:bg-gray-800 rounded-2xl border border-gray-300 dark:border-gray-700 overflow-hidden lg:overflow-x-auto">

                                    {{-- Table Header --}}
                                    <div
                                        class="grid grid-cols-12 gap-4 p-4 bg-gray-300 dark:bg-gray-700 text-sm font-semibold text-gray-800 dark:text-gray-300 border-b border-gray-300 dark:border-gray-600">
                                        <div class="col-span-1">#</div>
                                        <div class="col-span-4">Track / Playlist</div>
                                        <div class="col-span-2 text-center">Score</div>
                                        <div class="col-span-2 text-center">Reach</div>
                                        <div class="col-span-2 text-center">Reposts</div>
                                        <div class="col-span-1 text-center">Actions</div>
                                    </div>

                                    {{-- Table Rows --}}
                                    @forelse ($topSources as $source)
                                        <div
                                            class="grid grid-cols-12 gap-4 p-4 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200 border-b border-gray-300 dark:border-gray-700">

                                            {{-- Rank / Badge --}}
                                            <div class="col-span-1 flex items-center justify-center">
                                                @if (proUser($source['source']?->user_urn) && $source['actionable']->is_featured)
                                                    <div
                                                        class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold bg-gradient-to-br from-orange-400 to-orange-600 text-white">
                                                        <x-lucide-crown class="w-3 h-3" />
                                                    </div>
                                                @elseif (proUser($source['source']?->user_urn))
                                                    <div
                                                        class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold bg-gradient-to-br from-gray-400 to-gray-600 text-white">
                                                        <x-lucide-crown class="w-3 h-3" />
                                                    </div>
                                                @else
                                                    <div
                                                        class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold bg-gray-700 text-gray-300">
                                                        {{ $loop->iteration }}
                                                    </div>
                                                @endif
                                            </div>

                                            {{-- Track / Playlist --}}
                                            <div class="col-span-4 flex items-center gap-3">
                                                <div class="relative group">
                                                    <img src="{{ soundcloud_image($source['source']?->artwork_url ?? null) }}"
                                                        alt="{{ $source['source']?->title ?? 'Unknown' }}"
                                                        class="w-12 h-12 rounded-lg object-cover transition-transform duration-300 group-hover:scale-105">
                                                    <a href="{{ $source['source']?->permalink_url ?? '#' }}"
                                                        target="_blank"
                                                        class="absolute inset-0 shadow group-hover:bg-gray-950/20 rounded-lg transition-all duration-300 flex items-center justify-center">
                                                        <x-lucide-external-link
                                                            class="w-4 h-4 text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300" />
                                                    </a>
                                                </div>
                                                <div class="min-w-0 flex-1">
                                                    <a href="{{ $source['source']?->permalink_url ?? '#' }}"
                                                        target="_blank"
                                                        class="font-semibold text-gray-900 dark:text-white truncate hover:text-orange-400 block w-full">
                                                        {{ Str::limit($source['source']?->title ?? 'Unknown', 30, '...') }}
                                                    </a>
                                                    @php
                                                        $user = $source['source']?->user;
                                                        $slug = $user?->name ?? $user?->urn;
                                                        $userActive = $user?->status == 1 ? true : false;
                                                        $link =
                                                            $slug && $userActive
                                                                ? route('user.my-account.user', $slug)
                                                                : 'javascript:void(0)';
                                                    @endphp
                                                    {{-- <a href="{{ route('user.my-account.user', !empty($source['source']?->user?->name) ? $source['source']?->user?->name : $source['source']?->user?->urn) }}" --}}
                                                    <a href="{{ $link }}"
                                                        class="text-sm text-gray-600 dark:text-gray-400 truncate hover:text-orange-400 block">
                                                        {{ $user?->name ?? 'Unknown' }}
                                                    </a>
                                                </div>
                                            </div>

                                            {{-- Score --}}
                                            <div
                                                class="col-span-2 flex items-center justify-center font-bold text-orange-400">
                                                {{ $source['engagement_score'] }}/10
                                            </div>

                                            {{-- Reach --}}
                                            <div
                                                class="col-span-2 flex items-center justify-center text-gray-500 dark:text-gray-300">
                                                {{ number_shorten($source['track_reach'] ?? 0) }}
                                            </div>

                                            {{-- Reposts --}}
                                            <div
                                                class="col-span-2 flex items-center justify-center text-gray-500 dark:text-gray-300">
                                                {{ number_shorten($source['streams']) }}
                                            </div>

                                            {{-- Actions --}}
                                            <div class="col-span-1 flex items-center justify-center space-x-1">
                                                @if (isset($source['actionable']))
                                                    <button
                                                        wire:click="likeSource('{{ encrypt($source['actionable']->id) }}','{{ encrypt($source['source']?->id) }}')"
                                                        class="w-8 h-8 rounded-full flex items-center justify-center transition duration-200 {{ $source['like'] ? 'bg-red-500 text-white shadow-lg' : 'bg-gray-700 text-gray-300' }} hover:bg-red-500 hover:text-white">
                                                        <x-lucide-heart class="w-3 h-3" />
                                                    </button>
                                                    <button
                                                        wire:click="repostSource('{{ encrypt($source['actionable']->id) }}','{{ encrypt($source['source']?->id) }}')"
                                                        class="w-8 h-8 rounded-full flex items-center justify-center transition duration-200 {{ $source['repost'] ? 'bg-green-500 text-white shadow-lg' : 'bg-gray-700 text-gray-300' }} hover:bg-green-500 hover:text-white">
                                                        <x-lucide-rotate-ccw class="w-3 h-3" />
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    @empty
                                        <p class="text-center text-gray-500 dark:text-gray-400 py-8">No tracks found.
                                        </p>
                                    @endforelse
                                </div>
                            </div>

                            {{-- Mobile: Card Mode --}}
                            <div class="md:hidden flex flex-col space-y-3">
                                @forelse($topSources as $source)
                                    <div
                                        class="bg-gray-200 dark:bg-gray-800 p-4 rounded-2xl border border-gray-300 dark:border-gray-700 flex flex-col gap-3">

                                        {{-- Top Row: Rank + Image + Title --}}
                                        <div class="flex items-center gap-3">
                                            {{-- Rank --}}
                                            <div
                                                class="w-10 h-10 rounded-full bg-gray-700 text-gray-300 flex items-center justify-center font-bold text-sm">
                                                {{ $loop->iteration }}
                                            </div>

                                            {{-- Track Image --}}
                                            <div class="relative group">
                                                <img src="{{ soundcloud_image($source['source']?->artwork_url ?? null) }}"
                                                    alt="{{ $source['source']?->title ?? 'Unknown' }}"
                                                    class="w-12 h-12 rounded-lg object-cover transition-transform duration-300 group-hover:scale-105">
                                                <a href="{{ $source['source']?->permalink_url ?? '#' }}"
                                                    target="_blank"
                                                    class="absolute inset-0 shadow group-hover:bg-gray-950/20 rounded-lg transition-all duration-300 flex items-center justify-center">
                                                    <x-lucide-external-link
                                                        class="w-4 h-4 text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300" />
                                                </a>
                                            </div>

                                            {{-- Title + User --}}
                                            <div class="flex-1 min-w-0">
                                                <p class="font-semibold text-gray-900 dark:text-white truncate">
                                                    {{ Str::limit($source['source']?->title ?? 'Unknown', 30, '...') }}
                                                </p>
                                                <p class="text-sm text-gray-600 dark:text-gray-400 truncate">
                                                    {{ $source['source']?->user?->name ?? 'Unknown' }}
                                                </p>
                                            </div>
                                        </div>

                                        {{-- Metrics --}}
                                        <div class="flex justify-between text-gray-500 dark:text-gray-300 text-sm">
                                            <span>Reach:
                                                {{ number_shorten($source['track_reach'] ?? 0) }}</span>
                                            <div class="flex items-center space-x-2">
                                                <span class="font-bold text-orange-400">
                                                    {{ $source['engagement_score'] }}/10
                                                </span>
                                            </div>
                                            <span>Reposts:
                                                {{ number_shorten($source['streams']) }}</span>
                                        </div>

                                        {{-- Actions --}}
                                        <div class="flex items-center justify-center space-x-2">
                                            @if (isset($source['source']))
                                                <button
                                                    wire:click="likeSource('{{ encrypt($source['actionable']->id) }}','{{ encrypt($source['source']?->id) }}')"
                                                    class="w-8 h-8 rounded-full flex items-center justify-center {{ $source['like'] ? 'bg-red-500 text-white shadow-lg' : 'bg-gray-700 text-gray-300' }} hover:bg-red-500 hover:text-white">
                                                    <x-lucide-heart class="w-3 h-3" />
                                                </button>
                                                <button
                                                    wire:click="repostSource('{{ encrypt($source['actionable']->id) }}','{{ encrypt($source['source']?->id) }}')"
                                                    class="w-8 h-8 rounded-full flex items-center justify-center {{ $source['repost'] ? 'bg-green-500 text-white shadow-lg' : 'bg-gray-700 text-gray-300' }} hover:bg-green-500 hover:text-white">
                                                    <x-lucide-rotate-ccw class="w-3 h-3" />
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-center text-gray-500 dark:text-gray-400 py-8">No tracks found.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>





                    {{-- Grid view --}}
                    <div x-show="activeTab === 'gridView'" class="transition-all duration-500 opacity-100 scale-100">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                            @forelse ($topSources as $source)
                                <div
                                    class="bg-gray-200 dark:bg-gray-800 rounded-2xl p-4 border transition-all duration-300 hover:scale-105 hover:shadow-xl shadow-lg {{ proUser($source['source']?->user_urn) && $source['actionable']->is_featured
                                        ? 'shadow-orange-500/20 border-orange-300 dark:border-orange-500'
                                        : (proUser($source['source']?->user_urn)
                                            ? 'shadow-gray-500/20 border-gray-500'
                                            : 'shadow-gray-700/20 border-gray-300 dark:border-gray-700') }}">
                                    <div class="relative mb-4">

                                        @if (proUser($source['source']?->user_urn) && $source['actionable']->is_featured)
                                            <div
                                                class="absolute -top-2 -right-2 w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold z-10 bg-gradient-to-br from-orange-400 to-orange-600 text-white">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="lucide lucide-crown w-4 h-4">
                                                    <path d="m2 4 3 12h14l3-12-6 7-4-7-4 7-6-7zm3 16h14"></path>
                                                </svg>
                                            </div>
                                        @elseif (proUser($source['source']?->user_urn))
                                            <div
                                                class="absolute -top-2 -right-2 w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold z-10 bg-gradient-to-br from-gray-400 to-gray-600 text-white">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="lucide lucide-crown w-4 h-4">
                                                    <path d="m2 4 3 12h14l3-12-6 7-4-7-4 7-6-7zm3 16h14"></path>
                                                </svg>
                                            </div>
                                        @else
                                            <div
                                                class="absolute -top-2 -right-2 w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold z-10 bg-gray-700 text-gray-300">
                                                {{ $loop->iteration }}</div>
                                        @endif

                                        <div class="relative group cursor-pointer">
                                            <img src="{{ soundcloud_image($source['source']?->artwork_url ?? null) }}"
                                                alt="{{ $source['source']?->title ?? 'Unknown' }}"
                                                class="w-full aspect-square rounded-xl object-cover transition-transform duration-300 group-hover:scale-105">
                                            <a href="{{ $source['source']?->permalink_url ?? '#' }}" target="_blank"
                                                class="absolute inset-0 bg-gray-950 bg-gray-950/0 group-hover:bg-gray-950/30 rounded-xl transition-all duration-300 flex items-center justify-center">
                                                <x-lucide-external-link
                                                    class="w-6 h-6 text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300" />

                                            </a>
                                        </div>
                                    </div>
                                    <div class="mb-4">
                                        <a href="{{ $source['source']?->permalink_url ?? '#' }}" target="_blank"
                                            class="font-bold text-black dark:text-white mb-1 cursor-pointer hover:text-orange-400 transition-colors truncate block w-full">
                                            {{ Str::limit($source['source']?->title ?? 'Unknown', 20, '...') }}</a>
                                        {{-- <a href="{{ route('user.my-account.user', !empty($source['source']?->user?->name) ? $source['source']?->user?->name : $source['source']?->user?->urn) }}" --}}
                                        @php
                                            $user = $source['source']?->user;
                                            $slug = $user?->name ?? $user?->urn;
                                            $userActive = $user?->status == 1 ? true : false;
                                            $link =
                                                $slug && $userActive
                                                    ? route('user.my-account.user', $slug)
                                                    : 'javascript:void(0)';
                                        @endphp

                                        <a href="{{ $link }}"
                                            class="text-gray-600 dark:text-gray-300 text-sm mb-2 truncate">
                                            {{ $user?->name ?? 'Unknown' }}
                                        </a>
                                        <span
                                            class="inline-block bg-gray-300 dark:bg-gray-700 px-2 py-1 rounded-full text-xs text-gray-700 dark:text-gray-300 border border-gray-300 dark:border-gray-600">
                                            {{ $source['source']?->genre ?? 'Unknown' }}
                                        </span>
                                    </div>
                                    <div class="mb-4">
                                        <div
                                            class="text-center cursor-pointer hover:bg-gray-300 dark:hover:bg-gray-700 rounded-lg p-2 transition-colors">
                                            <div class="text-xl font-bold text-orange-400">
                                                {{ $source['engagement_score'] }}/10</div>
                                            <div class="text-xs text-gray-800 dark:text-gray-400">Engagement Score
                                            </div>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-3 gap-2 mb-4 text-xs text-gray-800 dark:text-gray-400">
                                        <div class="text-center">
                                            <div class="font-semibold text-black dark:text-white">
                                                {{ number_shorten($source['track_reach'] ?? 0) }}
                                            </div>
                                            <div>Reach</div>
                                        </div>
                                        <div class="text-center">
                                            <div class="font-semibold text-black dark:text-white">
                                                {{ number_shorten($source['reposts']) }}
                                            </div>
                                            <div>Reposts</div>
                                        </div>
                                        <div class="text-center">
                                            <div class="font-semibold text-black dark:text-white">
                                                {{ number_shorten($source['likes']) }}
                                            </div>
                                            <div>Plays</div>
                                        </div>
                                    </div>
                                    <div class="flex items-center justify-center gap-2">
                                        {{-- <button @click="playing('{{ $source['source']->urn }}')"
                                            class="flex items-center justify-center w-10 h-10 rounded-full transition-all duration-200 bg-gray-700 text-gray-300 hover:bg-orange-500 hover:text-white hover:shadow-lg border border-gray-600 play-btn"
                                            :class="{ 'bg-orange-500 text-white': playing === '{{ $source['source']->urn }}' }"
                                            data-title="{{ $source['source']->title }}"
                                            data-artist="{{ $source['source']->author_username }}"
                                            data-cover="{{ $source['source']->artwork_url }}"
                                            data-src="{{ $source['source']->stream_url }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-play w-4 h-4 ml-0.5">
                                                <polygon points="5 3 19 12 5 21 5 3"></polygon>
                                            </svg>
                                        </button> --}}
                                        @if (isset($source['source']))
                                            <button
                                                wire:click="likeSource('{{ encrypt($source['actionable']->id) }}','{{ encrypt($source['source']?->id) }}')"
                                                class="flex items-center justify-center w-10 h-10 rounded-full transition-all duration-200 bg-gray-700 text-gray-300 hover:bg-red-500 hover:text-white hover:shadow-lg border border-gray-600 {{ $source['like'] ? 'bg-red-500 text-white shadow-lg' : '' }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="lucide lucide-heart w-4 h-4 ">
                                                    <path
                                                        d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z">
                                                    </path>
                                                </svg>
                                            </button>
                                            <button
                                                wire:click="repostSource('{{ encrypt($source['actionable']->id) }}','{{ encrypt($source['source']?->id) }}')"
                                                class="flex items-center justify-center w-10 h-10 rounded-full transition-all duration-200 {{ $source['repost'] ? 'bg-green-500 text-white shadow-lg' : 'bg-gray-700 text-gray-300' }} hover:bg-green-500 hover:text-white hover:shadow-lg border border-gray-600"><svg
                                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="lucide lucide-rotate-ccw w-4 h-4">
                                                    <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"></path>
                                                    <path d="M3 3v5h5"></path>
                                                </svg>
                                            </button>
                                        @else
                                            <button
                                                class="flex items-center justify-center w-10 h-10 rounded-full transition-all duration-200 bg-gray-700 text-gray-300 hover:bg-red-500 hover:text-white hover:shadow-lg border border-gray-600">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="lucide lucide-heart w-4 h-4">
                                                    <path
                                                        d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z">
                                                    </path>
                                                </svg>
                                            </button>
                                            <button
                                                class="flex items-center justify-center w-10 h-10 rounded-full transition-all duration-200 bg-gray-700 text-gray-300 hover:bg-green-500 hover:text-white hover:shadow-lg border border-gray-600">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="lucide lucide-rotate-ccw w-4 h-4">
                                                    <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"></path>
                                                    <path d="M3 3v5h5"></path>
                                                </svg>
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="text-center text-gray-500 dark:text-gray-400 py-8 col-span-full">No tracks
                                    found</div>
                            @endforelse
                        </div>
                    </div>
                    <div x-show="activeTab === 'listView'" class="transition-all duration-500 opacity-100 scale-100">
                        <div class="space-y-3">

                            @forelse ($topSources as $source)
                                <div
                                    class="relative border rounded-2xl p-4 transition-all duration-300 hover:shadow-xl hover:scale-[1.02] bg-gradient-to-r from-gray-200 to-gray-200 dark:from-gray-900 dark:to-gray-800 shadow-lg {{ proUser($source['source']?->user_urn) && $source['actionable']->is_featured
                                        ? 'shadow-orange-500/20 border-orange-300 dark:border-orange-500'
                                        : (proUser($source['source']?->user_urn)
                                            ? 'shadow-gray-500/20 border-gray-500'
                                            : 'shadow-gray-700/20 border-gray-300 dark:border-gray-700') }}">

                                    @if (proUser($source['source']?->user_urn) && $source['actionable']->is_featured)
                                        <div class="absolute -top-2 -right-2 z-10">
                                            <div
                                                class="w-8 h-8 rounded-full flex items-center justify-center bg-gradient-to-br from-orange-400 to-orange-600 text-white shadow-lg">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="lucide lucide-crown w-4 h-4">
                                                    <path d="m2 4 3 12h14l3-12-6 7-4-7-4 7-6-7zm3 16h14"></path>
                                                </svg>
                                            </div>
                                        </div>
                                    @elseif (proUser($source['source']?->user_urn))
                                        <div class="absolute -top-2 -right-2 z-10">
                                            <div
                                                class="w-8 h-8 rounded-full flex items-center justify-center bg-gradient-to-br from-gray-400 to-gray-600 text-white shadow-lg">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="lucide lucide-crown w-4 h-4">
                                                    <path d="m2 4 3 12h14l3-12-6 7-4-7-4 7-6-7zm3 16h14"></path>
                                                </svg>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="flex flex-col md:flex-row md:items-center gap-4">
                                        <div class="flex items-center gap-4">
                                            <div
                                                class="w-12 h-12 rounded-full flex items-center justify-center font-bold bg-gradient-to-br shadow-lg text-2xl {{ proUser($source['source']?->user_urn) && $source['actionable']->is_featured
                                                    ? 'from-orange-400 to-orange-600 text-white'
                                                    : (proUser($source['source']?->user_urn)
                                                        ? 'from-gray-400 to-gray-600 text-white'
                                                        : 'bg-gray-700 text-gray-300 border border-gray-300 dark:border-gray-600') }}">
                                                {{ $loop->iteration }}
                                            </div>
                                            <div class="relative group cursor-pointer w-20 h-20">
                                                <img src="{{ soundcloud_image($source['source']?->artwork_url ?? null) }}"
                                                    alt="{{ $source['source']?->title ?? 'Unknown' }}"
                                                    class="rounded-xl object-cover transition-transform duration-300 group-hover:scale-105 w-full h-full">
                                                <a href="{{ $source['source']?->permalink_url ?? '#' }}"
                                                    target="_blank"
                                                    class="absolute inset-0 shadow group-hover:bg-gray-950/30 rounded-xl transition-all duration-300 flex items-center justify-center">
                                                    <x-lucide-external-link
                                                        class="w-6 h-6 text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300" />
                                                </a>
                                            </div>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-start justify-between gap-2">
                                                <div class="flex-1 min-w-0">
                                                    <a href="{{ $source['source']?->permalink_url ?? '#' }}"
                                                        target="_blank"
                                                        class="font-bold text-black dark:text-white truncate text-lg hover:text-orange-400 transition-colors cursor-pointer block w-full">
                                                        {{ Str::limit($source['source']?->title ?? 'Unknown', 30, '...') }}
                                                    </a>
                                                    {{-- <a href="{{ route('user.my-account.user', !empty($source['source']?->user?->name) ? $source['source']?->user?->name : $source['source']?->user?->urn) }}" --}}
                                                    @php
                                                        $user = $source['source']?->user;
                                                        $slug = $user?->name ?? $user?->urn;
                                                        $userActive = $user?->status == 1 ? true : false;
                                                        $link =
                                                            $slug && $userActive
                                                                ? route('user.my-account.user', $slug)
                                                                : 'javascript:void(0)';
                                                    @endphp

                                                    <a href="{{ $link }}"
                                                        class="text-gray-600 dark:text-gray-300 text-sm mb-2 truncate">
                                                        {{ $user?->name ?? 'Unknown' }}
                                                    </a>
                                                    <span
                                                        class="inline-block mt-1 bg-gray-300 dark:bg-gray-700 px-2 py-1 rounded-full text-xs text-gray-600 dark:text-gray-300 border border-gray-300 dark:border-gray-600">{{ $source['source']?->genre ?? 'Unknown' }}</span>
                                                </div>
                                                <div class="text-right cursor-pointer">
                                                    <div class="text-lg font-bold text-black dark:text-white">
                                                        {{ $source['engagement_score'] }}/10</div>
                                                    <div class="text-xs text-gray-800 dark:text-gray-400">score</div>
                                                </div>
                                            </div>
                                            <div class="flex flex-wrap gap-1 mt-2">
                                                @if ($source['actionable']->is_featured)
                                                    <span
                                                        class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium border bg-orange-900 text-orange-300 border-orange-700"><svg
                                                            xmlns="http://www.w3.org/2000/svg" width="24"
                                                            height="24" viewBox="0 0 24 24" fill="none"
                                                            stroke="currentColor" stroke-width="2"
                                                            stroke-linecap="round" stroke-linejoin="round"
                                                            class="lucide lucide-award w-3 h-3">
                                                            <circle cx="12" cy="8" r="6"></circle>
                                                            <path d="M15.477 12.89 17 22l-5-3-5 3 1.523-9.11"></path>
                                                        </svg>
                                                        Featured
                                                    </span>
                                                @endif
                                                @if (proUser($source['source']?->user_urn))
                                                    <span
                                                        class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium border bg-blue-900 text-blue-300 border-blue-700">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                            height="24" viewBox="0 0 24 24" fill="none"
                                                            stroke="currentColor" stroke-width="2"
                                                            stroke-linecap="round" stroke-linejoin="round"
                                                            class="lucide lucide-star w-3 h-3">
                                                            <polygon
                                                                points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                                                            </polygon>
                                                        </svg>
                                                        Pro Artist
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="mt-3 mb-3">
                                                <div
                                                    class="flex items-center justify-between text-xs text-gray-800 dark:text-gray-400 mb-1">
                                                    <span>Engagement</span><span>{{ number_format($source['engagement_rate'], 2) }}%</span>
                                                </div>
                                                <div class="w-full bg-gray-700 rounded-full h-2">
                                                    <div class="h-full rounded-full transition-all duration-500 bg-gradient-to-r from-orange-400 to-orange-600"
                                                        style="width: {{ $source['engagement_rate'] }}%;"></div>
                                                </div>
                                            </div>
                                            <div class="flex items-center justify-between">
                                                <div
                                                    class="flex items-center gap-4 text-xs text-gray-800 dark:text-gray-400">
                                                    <span>{{ number_shorten($source['reposts']) }}
                                                        reposts</span>
                                                    <span>{{ number_shorten($source['track_reach'] ?? 0) }}
                                                        reach</span>
                                                    <span>{{ number_shorten($source['streams']) }}
                                                        plays</span>
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    {{-- <button @click="playing('{{ $source['source']->urn }}')"
                                    class="flex items-center justify-center w-10 h-10 rounded-full transition-all duration-200 bg-gray-700 text-gray-300 hover:bg-orange-500 hover:text-white hover:shadow-lg border border-gray-300 dark:border-gray-600 play-btn"
                                    :class="{ 'bg-orange-500 text-white': playing === '{{ $source['source']->urn }}' }"
                                    title="Play"
                                    data-title="{{ $source['source']->title }}"
                                    data-artist="{{ $source['source']->author_username }}"
                                    data-cover="{{ $source['source']->artwork_url }}"
                                    data-src="https://api-v2.soundcloud.com/media/soundcloud:tracks:1252113682/0622321d-02e6-4b77-86aa-a54b4fc4d82d/stream/hls">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                        height="24" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-play w-4 h-4 ml-0.5">
                                        <polygon points="5 3 19 12 5 21 5 3"></polygon>
                                    </svg>
                                </button> --}}
                                                    @if (isset($source['source']))
                                                        <button
                                                            wire:click="likeSource('{{ encrypt($source['actionable']->id) }}','{{ encrypt($source['source']?->id) }}')"
                                                            class="flex items-center justify-center w-10 h-10 rounded-full transition-all duration-200 bg-gray-700 text-gray-300 {{ $source['like'] ? 'bg-red-500 text-white shadow-lg' : '' }} hover:bg-red-500 hover:text-white hover:shadow-lg border border-gray-300 dark:border-gray-600"
                                                            title="Like">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                height="24" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="lucide lucide-heart w-4 h-4 ">
                                                                <path
                                                                    d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z">
                                                                </path>
                                                            </svg>
                                                        </button>
                                                        <button
                                                            wire:click="repostSource('{{ encrypt($source['actionable']->id) }}','{{ encrypt($source['source']?->id) }}')"
                                                            class="flex items-center justify-center w-10 h-10 rounded-full transition-all duration-200 {{ $source['repost'] ? 'bg-green-500 text-white shadow-lg' : 'bg-gray-700 text-gray-300' }} hover:bg-green-500 hover:text-white hover:shadow-lg border border-gray-300 dark:border-gray-600"
                                                            title="Repost">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                height="24" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="lucide lucide-rotate-ccw w-4 h-4">
                                                                <path
                                                                    d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8">
                                                                </path>
                                                                <path d="M3 3v5h5"></path>
                                                            </svg>
                                                        </button>
                                                    @else
                                                        <button
                                                            class="flex items-center justify-center w-10 h-10 rounded-full transition-all duration-200 bg-gray-700 text-gray-300 hover:bg-red-500 hover:text-white hover:shadow-lg border border-gray-300 dark:border-gray-600"
                                                            title="Like">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                height="24" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="lucide lucide-heart w-4 h-4">
                                                                <path
                                                                    d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z">
                                                                </path>
                                                            </svg>
                                                        </button>
                                                        <button
                                                            class="flex items-center justify-center w-10 h-10 rounded-full transition-all duration-200 bg-gray-700 text-gray-300 hover:bg-green-500 hover:text-white hover:shadow-lg border border-gray-300 dark:border-gray-600"
                                                            title="Repost">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                height="24" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="lucide lucide-rotate-ccw w-4 h-4">
                                                                <path
                                                                    d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8">
                                                                </path>
                                                                <path d="M3 3v5h5">
                                                                </path>
                                                            </svg>
                                                        </button>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="flex items-center justify-center w-full h-full">
                                    <div class="flex flex-col items-center">
                                        <div
                                            class="w-8 h-8 rounded-full flex items-center justify-center bg-gradient-to-br from-orange-400 to-orange-600 text-white shadow-lg">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-crown w-4 h-4">
                                                <path d="m2 4 3 12h14l3-12-6 7-4-7-4 7-6-7zm3 16h14"></path>
                                            </svg>
                                        </div>
                                        <div class="mt-4 text-sm text-gray-600 dark:text-gray-400">No content found
                                        </div>
                                    </div>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <div class="mt-12 text-center">
                        <div
                            class="bg-gray-200 dark:bg-gray-800 rounded-2xl p-8 shadow-lg border border-gray-200 dark:border-gray-700">
                            <h3 class="text-xl font-bold dark:text-white text-gray-800 mb-4">Want to see your track
                                here?
                            </h3>
                            <p class="text-gray-500 dark:text-gray-300 mb-6">Join our repost campaigns and boost your
                                engagement to climb
                                the charts!</p>
                            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                                <x-gabutton variant="primary" wire:navigate
                                    href="{{ route('user.cm.my-campaigns') }}">Start Campaign</x-gabutton>
                                <x-gabutton variant="secondary" wire:navigate href="{{ route('user.faq') }}">Learn
                                    More</x-gabutton>
                            </div>
                        </div>
                    </div>
                    <div class="mt-8 text-center text-gray-400 text-sm">
                        <p>Chart updates every Monday at 10:00 AM UTC</p>
                        <p class="mt-1">Based on 7-day engagement metrics</p>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <style>
        .player-hidden {
            /* transform: translateY(100%); */
            opacity: 0;
            display: none;
        }

        .player-visible {
            /* transform: translateY(0); */
            opacity: 1;
            display: block;
        }

        .progress-bar {
            transition: width 0.1s ease-out;
        }

        .volume-slider {
            background: linear-gradient(to right, #f97316 0%, #f97316 var(--volume), #374151 var(--volume), #374151 100%);
        }
    </style>
    <div id="bottomPlayer"
        class="sticky bottom-0 left-0 right-0 bg-gray-800 border-t border-gray-700  py-3 transform transition-transform duration-300 player-hidden z-50">
        <div class="flex items-center justify-between max-w-7xl mx-auto">
            <!-- Track Info -->
            <div class="flex items-center gap-3 min-w-0 flex-1">
                <img id="playerCover" src="" alt="" class="w-12 h-12 rounded-lg object-cover">
                <div class="min-w-0 flex-1">
                    <h4 id="playerTitle" class="font-semibold text-white truncate">Track Title</h4>
                    <p id="playerArtist" class="text-sm text-gray-400 truncate">Artist Name</p>
                </div>
                <!-- <button id="heartBtn" class="text-gray-400 hover:text-red-500 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                </button> -->
            </div>

            <!-- Playback Controls -->
            <div class="flex flex-col items-center gap-2 flex-1 max-w-md mx-8">
                <div class="flex items-center gap-4">
                    <!-- <button id="shuffleBtn" class="text-gray-400 hover:text-white transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4l6 6m0 0l6-6m-6 6L4 16m6-6l6 6M16 4h4v4M20 20h-4v-4"></path>
                        </svg>
                    </button> -->
                    <button id="prevBtn" class="text-gray-400 hover:text-white transition-colors">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M6 6h2v12H6zm3.5 6l8.5 6V6z" />
                        </svg>
                    </button>
                    <button id="playPauseBtn"
                        class="bg-white text-black p-2 rounded-full hover:scale-105 transition-transform">
                        <svg id="playIcon" class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M8 5v14l11-7z" />
                        </svg>
                        <svg id="pauseIcon" class="w-6 h-6 hidden" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z" />
                        </svg>
                    </button>
                    <button id="nextBtn" class="text-gray-400 hover:text-white transition-colors">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M6 18l8.5-6L6 6v12zM16 6v12h2V6h-2z" />
                        </svg>
                    </button>
                    <!-- <button id="repeatBtn" class="text-gray-400 hover:text-white transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                    </button> -->
                </div>

                <!-- Progress Bar -->
                <div class="flex items-center gap-2 w-full">
                    <span id="currentTime" class="text-xs text-gray-400 w-10">0:00</span>
                    <div class="flex-1 bg-gray-600 rounded-full h-1 cursor-pointer" id="progressContainer">
                        <div id="progressBar" class="bg-orange-500 h-1 rounded-full progress-bar" style="width: 0%">
                        </div>
                    </div>
                    <span id="totalTime" class="text-xs text-gray-400 w-10">0:00</span>
                </div>
            </div>

            <!-- Volume and Additional Controls -->
            <div class="flex items-center gap-3 flex-1 justify-end">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M3 9v6h4l5 5V4L7 9H3zm13.5 3c0-1.77-1.02-3.29-2.5-4.03v8.05c1.48-.73 2.5-2.25 2.5-4.02zM14 3.23v2.06c2.89.86 5 3.54 5 6.71s-2.11 5.85-5 6.71v2.06c4.01-.91 7-4.49 7-8.77s-2.99-7.86-7-8.77z" />
                    </svg>
                    <input type="range" id="volumeSlider" min="0" max="100" value="50"
                        class="w-full h-1 bg-gray-600 rounded-lg appearance-none cursor-pointer volume-slider">
                </div>
                <button id="closeBtn" class="text-gray-400 hover:text-white transition-colors ml-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <audio id="audioPlayer" preload="metadata"></audio>

    <script>
        class MusicPlayer {
            constructor() {
                this.audio = document.getElementById('audioPlayer');
                this.bottomPlayer = document.getElementById('bottomPlayer');
                this.playPauseBtn = document.getElementById('playPauseBtn');
                this.playIcon = document.getElementById('playIcon');
                this.pauseIcon = document.getElementById('pauseIcon');
                this.progressBar = document.getElementById('progressBar');
                this.progressContainer = document.getElementById('progressContainer');
                this.currentTimeSpan = document.getElementById('currentTime');
                this.totalTimeSpan = document.getElementById('totalTime');
                this.volumeSlider = document.getElementById('volumeSlider');
                this.playerCover = document.getElementById('playerCover');
                this.playerTitle = document.getElementById('playerTitle');
                this.playerArtist = document.getElementById('playerArtist');
                this.closeBtn = document.getElementById('closeBtn');

                this.currentTrack = null;
                this.isPlaying = false;

                this.initEventListeners();
                this.setVolume(0.5);
            }

            initEventListeners() {
                // Play buttons from track list
                document.querySelectorAll('.play-btn').forEach(btn => {
                    btn.addEventListener('click', (e) => {
                        const button = e.currentTarget;
                        this.loadTrack({
                            title: button.dataset.title,
                            artist: button.dataset.artist,
                            cover: button.dataset.cover,
                            src: button.dataset.src
                        });
                    });
                });

                // Main play/pause button
                this.playPauseBtn.addEventListener('click', () => {
                    this.togglePlayPause();
                });

                // Progress bar click
                this.progressContainer.addEventListener('click', (e) => {
                    const rect = this.progressContainer.getBoundingClientRect();
                    const clickX = e.clientX - rect.left;
                    const width = rect.width;
                    const percentage = clickX / width;
                    this.audio.currentTime = percentage * this.audio.duration;
                });

                // Volume slider
                this.volumeSlider.addEventListener('input', (e) => {
                    this.setVolume(e.target.value / 100);
                });

                // Close player
                this.closeBtn.addEventListener('click', () => {
                    this.closePlayer();
                });

                // Audio events
                this.audio.addEventListener('loadedmetadata', () => {
                    this.totalTimeSpan.textContent = this.formatTime(this.audio.duration);
                });

                this.audio.addEventListener('timeupdate', () => {
                    this.updateProgress();
                });

                this.audio.addEventListener('ended', () => {
                    this.isPlaying = false;
                    this.updatePlayButton();
                });
            }

            loadTrack(track) {
                this.currentTrack = track;
                this.audio.src = track.src;
                this.playerCover.src = track.cover;
                this.playerTitle.textContent = track.title;
                this.playerArtist.textContent = track.artist;

                this.showPlayer();
                this.play();
            }

            play() {
                this.audio.play().then(() => {
                    this.isPlaying = true;
                    this.updatePlayButton();
                }).catch(error => {
                    console.error('Error playing audio:', error);
                });
            }

            pause() {
                this.audio.pause();
                this.isPlaying = false;
                this.updatePlayButton();
            }

            togglePlayPause() {
                if (this.isPlaying) {
                    this.pause();
                } else {
                    this.play();
                }
            }

            updatePlayButton() {
                if (this.isPlaying) {
                    this.playIcon.classList.add('hidden');
                    this.pauseIcon.classList.remove('hidden');
                } else {
                    this.playIcon.classList.remove('hidden');
                    this.pauseIcon.classList.add('hidden');
                }
            }

            updateProgress() {
                if (this.audio.duration) {
                    const percentage = (this.audio.currentTime / this.audio.duration) * 100;
                    this.progressBar.style.width = percentage + '%';
                    this.currentTimeSpan.textContent = this.formatTime(this.audio.currentTime);
                }
            }

            setVolume(volume) {
                this.audio.volume = volume;
                this.volumeSlider.style.setProperty('--volume', (volume * 100) + '%');
            }

            showPlayer() {
                this.bottomPlayer.classList.remove('player-hidden');
                this.bottomPlayer.classList.add('player-visible');
            }

            closePlayer() {
                this.pause();
                this.bottomPlayer.classList.add('player-hidden');
                this.bottomPlayer.classList.remove('player-visible');
            }

            formatTime(seconds) {
                if (isNaN(seconds)) return '0:00';
                const minutes = Math.floor(seconds / 60);
                const remainingSeconds = Math.floor(seconds % 60);
                return `${minutes}:${remainingSeconds.toString().padStart(2, '0')}`;
            }
        }

        // Initialize the music player when the page loads
        document.addEventListener('DOMContentLoaded', () => {
            new MusicPlayer();
        });
    </script>
</section>
