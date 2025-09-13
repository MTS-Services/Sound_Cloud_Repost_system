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
                                <div class="bg-orange-500 p-3 rounded-xl">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-trophy w-8 h-8">
                                        <path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"></path>
                                        <path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"></path>
                                        <path d="M4 22h16"></path>
                                        <path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20.24 7 22"></path>
                                        <path d="M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20.24 17 22">
                                        </path>
                                        <path d="M18 2H6v7a6 6 0 0 0 12 0V2Z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h1 class="text-3xl md:text-4xl font-bold mb-2 text-gray-900 dark:text-white">Weekly
                                        Top
                                        {{ $topTracks->count() }} / 20 Chart</h1>
                                    <div class="flex items-center gap-4 text-gray-500 dark:text-gray-300">
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
                                            {{-- <span>Week of January 13, 2025</span> --}}
                                            <span>Week of {{ date('F j, Y', strtotime(now()->subDays(7))) }}</span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-clock w-4 h-4">
                                                <circle cx="12" cy="12" r="10"></circle>
                                                <polyline points="12 6 12 12 16 14"></polyline>
                                            </svg>
                                            {{-- <span>Updated 1/13/2025</span> --}}
                                            <span>Updated {{ date('F j, Y', strtotime(now())) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <button wire:click="refresh"
                                    class="flex items-center gap-2 bg-gray-200 hover:bg-white dark:bg-gray-800 dark:hover:bg-gray-700 px-4 py-2 rounded-xl transition-all duration-200 font-medium border dark:border-gray-700 text-gray-900 dark:text-white">
                                    <span wire:loading.remove>
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
                                    <span wire:loading class="animate-spin">
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
                                    <span class="hidden sm:inline" wire:loading.remove>Refresh</span>
                                    <span class="hidden sm:inline" wire:loading>Refreshing...</span>

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
                                <div class="text-2xl font-bold">{{ $topTracks->count() }}</div>
                                <div class="text-gray-700 dark:text-gray-300 text-sm">Top Tracks</div>
                            </div>
                            <div
                                class="bg-gray-200 dark:bg-gray-800  backdrop-blur-sm p-4 rounded-xl border border-gray-200 dark:border-gray-700">
                                <div class="text-2xl font-bold">Weekly</div>
                                <div class="text-gray-700 dark:text-gray-300 text-sm">Updates</div>
                            </div>
                            <div
                                class="bg-gray-200 dark:bg-gray-800  backdrop-blur-sm p-4 rounded-xl border border-gray-200 dark:border-gray-700">
                                <div class="text-2xl font-bold">Live</div>
                                <div class="text-gray-700 dark:text-gray-300 text-sm">Engagement</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div x-data="{ activeTab: @entangle('activeTab').live }">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-4">
                            <h2 class="text-xl font-bold text-gray-800 dark:text-white">{{ $topTracks->count() }}
                                Tracks
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
                        <div
                            class="bg-gray-200 dark:bg-gray-800 rounded-2xl border border-gray-300 dark:border-gray-700 overflow-hidden">
                            <div
                                class="grid grid-cols-12 gap-4 p-4 bg-gray-300 dark:bg-gray-700 text-sm font-semibold text-gray-800 dark:text-gray-300 border-b border-gray-300 dark:border-gray-600">
                                <div class="col-span-1">#</div>
                                <div class="col-span-4">Track</div>
                                <div class="col-span-2 text-center">Score</div>
                                <div class="col-span-2 text-center">Reach</div>
                                <div class="col-span-2 text-center">Reposts</div>
                                <div class="col-span-1 text-center">Actions</div>
                            </div>

                            @forelse ($topTracks as $track)
                                <div
                                    class="grid grid-cols-12 gap-4 p-4 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200 border-b border-gray-300 dark:border-gray-700">
                                    {{-- @dd($track); --}}
                                    @if (proUser($track['track_details']->user_urn) && $track['action_details']->is_featured)
                                        <div class="col-span-1 flex items-center">
                                            <div
                                                class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold bg-gradient-to-br from-orange-400 to-orange-600 text-white">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="lucide lucide-crown w-3 h-3">
                                                    <path d="m2 4 3 12h14l3-12-6 7-4-7-4 7-6-7zm3 16h14"></path>
                                                </svg>
                                            </div>
                                        </div>
                                    @elseif (proUser($track['track_details']->user_urn))
                                        <div class="col-span-1 flex items-center">
                                            <div
                                                class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold bg-gradient-to-br from-gray-400 to-gray-600 text-white">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="lucide lucide-crown w-3 h-3">
                                                    <path d="m2 4 3 12h14l3-12-6 7-4-7-4 7-6-7zm3 16h14"></path>
                                                </svg>
                                            </div>
                                        </div>
                                    @else
                                        <div class="col-span-1 flex items-center">
                                            <div
                                                class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold bg-gray-700 text-gray-300">
                                                {{ $loop->iteration }}
                                            </div>
                                        </div>
                                    @endif

                                    <div class="col-span-4 flex items-center gap-3">
                                        <div class="relative group">
                                            <img src="{{ soundcloud_image($track['track_details']->artwork_url) }}"
                                                alt="{{ $track['track_details']->title }}"
                                                class="w-12 h-12 rounded-lg object-cover transition-transform duration-300 group-hover:scale-105">
                                            <a href="{{ $track['track_details']->permalink_url }}" target="_blank"
                                                class="absolute inset-0 shadow group-hover:bg-gray-950/20 rounded-lg transition-all duration-300 flex items-center justify-center">
                                                <x-lucide-external-link
                                                    class="w-4 h-4 text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300" />
                                            </a>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <a href="{{ $track['track_details']->permalink_url }}" target="_blank"
                                                class="font-semibold text-gray-900 dark:text-white truncate cursor-pointer hover:text-orange-400 transition-colors block w-full">
                                                {{ Str::limit($track['track_details']->title, 30, '...') }}
                                            </a>
                                            <a href="{{ route('user.my-account', $track['track_details']->user->urn) }}"
                                                class="text-sm text-gray-600 dark:text-gray-400 truncate hover:text-orange-400 transition-colors block">
                                                {{ $track['track_details']->user->name }}</a>
                                        </div>
                                    </div>

                                    <div
                                        class="col-span-2 flex items-center justify-center cursor-pointer hover:text-orange-400 transition-colors">
                                        <span
                                            class="font-bold text-orange-400">{{ $track['engagement_score'] }}/10</span>
                                    </div>

                                    <div class="col-span-2 flex items-center justify-center"><span
                                            class="text-gray-500 dark:text-gray-300">{{ number_shorten($track['metrics']['total_views']['current_total']) }}</span>
                                    </div>

                                    <div class="col-span-2 flex items-center justify-center"><span
                                            class="text-gray-500 dark:text-gray-300">{{ number_shorten($track['metrics']['total_plays']['current_total']) }}</span>
                                    </div>

                                    <div class="col-span-1 flex items-center justify-center">
                                        <div class="flex items-center gap-1">
                                            <button
                                                class="flex items-center justify-center w-8 h-8 rounded-full transition-all duration-200 bg-gray-700 text-gray-300 hover:bg-orange-500 hover:text-white"><svg
                                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="lucide lucide-play w-3 h-3 ml-0.5">
                                                    <polygon points="5 3 19 12 5 21 5 3"></polygon>
                                                </svg>
                                            </button>
                                            <button
                                                wire:click="likeTrack('{{ encrypt($track['action_details']->id) }}','{{ encrypt($track['track_details']->urn) }}')"
                                                class="flex items-center justify-center w-8 h-8 rounded-full transition-all duration-200 bg-gray-700 text-gray-300 hover:bg-red-500 hover:text-white"><svg
                                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="lucide lucide-heart w-3 h-3 ">
                                                    <path
                                                        d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z">
                                                    </path>
                                                </svg>
                                            </button>
                                            <button
                                                wire:click="repostTrack('{{ encrypt($track['action_details']->id) }}','{{ encrypt($track['track_details']->urn) }}')"
                                                class="flex items-center justify-center w-8 h-8 rounded-full transition-all duration-200 {{ $track['repost'] ? 'bg-green-500 text-white shadow-lg' : 'bg-gray-700 text-gray-300' }} hover:bg-green-500 hover:text-white "><svg
                                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="lucide lucide-rotate-ccw w-3 h-3">
                                                    <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"></path>
                                                    <path d="M3 3v5h5"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <p class="text-center text-gray-500 dark:text-gray-400 py-8">No tracks found.</p>
                            @endforelse


                            {{-- <div
                            class="grid grid-cols-12 gap-4 p-4 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200 border-b border-gray-300 dark:border-gray-700">
                            <div class="col-span-1 flex items-center">
                                <div
                                    class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold bg-gradient-to-br from-orange-400 to-orange-600 text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-crown w-3 h-3">
                                        <path d="m2 4 3 12h14l3-12-6 7-4-7-4 7-6-7zm3 16h14"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="col-span-4 flex items-center gap-3">
                                <div class="relative group cursor-pointer"><img
                                        src="https://images.pexels.com/photos/1763075/pexels-photo-1763075.jpeg?auto=compress&amp;cs=tinysrgb&amp;w=300"
                                        alt="Midnight Dreams"
                                        class="w-12 h-12 rounded-lg object-cover transition-transform duration-300 group-hover:scale-105">
                                    <div
                                        class="absolute inset-0 bg-gray-950 bg-gray-950/0 group-hover:bg-gray-950/30 rounded-lg transition-all duration-300 flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            class="lucide lucide-external-link w-3 h-3 text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                            <path d="M15 3h6v6"></path>
                                            <path d="M10 14 21 3"></path>
                                            <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <h3
                                        class="font-semibold text-gray-900 dark:text-white truncate cursor-pointer hover:text-orange-400 transition-colors">
                                        Midnight Dreams</h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 truncate">Luna Waves</p>
                                </div>
                            </div>
                            <div
                                class="col-span-2 flex items-center justify-center cursor-pointer hover:text-orange-400 transition-colors">
                                <span class="font-bold text-orange-400">10/10</span>
                            </div>
                            <div class="col-span-2 flex items-center justify-center"><span
                                    class="text-gray-500 dark:text-gray-300">3.2K</span></div>
                            <div class="col-span-2 flex items-center justify-center"><span
                                    class="text-gray-500 dark:text-gray-300">1.3K</span></div>
                            <div class="col-span-1 flex items-center justify-center">
                                <div class="flex items-center gap-1">
                                    <button
                                        class="flex items-center justify-center w-8 h-8 rounded-full transition-all duration-200 bg-gray-700 text-gray-300 hover:bg-orange-500 hover:text-white"><svg
                                            xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            class="lucide lucide-play w-3 h-3 ml-0.5">
                                            <polygon points="5 3 19 12 5 21 5 3"></polygon>
                                        </svg>
                                    </button>
                                    <button
                                        class="flex items-center justify-center w-8 h-8 rounded-full transition-all duration-200 bg-gray-700 text-gray-300 hover:bg-red-500 hover:text-white"><svg
                                            xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            class="lucide lucide-heart w-3 h-3 ">
                                            <path
                                                d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z">
                                            </path>
                                        </svg>
                                    </button>
                                    <button
                                        class="flex items-center justify-center w-8 h-8 rounded-full transition-all duration-200 bg-gray-700 text-gray-300 hover:bg-green-500 hover:text-white"><svg
                                            xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            class="lucide lucide-rotate-ccw w-3 h-3">
                                            <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"></path>
                                            <path d="M3 3v5h5"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div
                            class="grid grid-cols-12 gap-4 p-4 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200 border-b border-gray-300 dark:border-gray-700">
                            <div class="col-span-1 flex items-center">
                                <div
                                    class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold bg-gradient-to-br from-gray-400 to-gray-600 text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-crown w-3 h-3">
                                        <path d="m2 4 3 12h14l3-12-6 7-4-7-4 7-6-7zm3 16h14"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="col-span-4 flex items-center gap-3">
                                <div class="relative group cursor-pointer"><img
                                        src="https://images.pexels.com/photos/1190297/pexels-photo-1190297.jpeg?auto=compress&amp;cs=tinysrgb&amp;w=300"
                                        alt="Urban Pulse"
                                        class="w-12 h-12 rounded-lg object-cover transition-transform duration-300 group-hover:scale-105">
                                    <div
                                        class="absolute inset-0 bg-gray-950 bg-gray-950/0 group-hover:bg-gray-950/30 rounded-lg transition-all duration-300 flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            class="lucide lucide-external-link w-3 h-3 text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                            <path d="M15 3h6v6"></path>
                                            <path d="M10 14 21 3"></path>
                                            <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <h3
                                        class="font-semibold text-gray-900 dark:text-white truncate cursor-pointer hover:text-orange-400 transition-colors">
                                        Urban Pulse</h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 truncate">Metro Vibes</p>
                                </div>
                            </div>
                            <div
                                class="col-span-2 flex items-center justify-center cursor-pointer hover:text-orange-400 transition-colors">
                                <span class="font-bold text-orange-400">9.4/10</span>
                            </div>
                            <div class="col-span-2 flex items-center justify-center"><span
                                    class="text-gray-500 dark:text-gray-300">2.9K</span></div>
                            <div class="col-span-2 flex items-center justify-center"><span
                                    class="text-gray-500 dark:text-gray-300">1.2K</span></div>
                            <div class="col-span-1 flex items-center justify-center">
                                <div class="flex items-center gap-1"><button
                                        class="flex items-center justify-center w-8 h-8 rounded-full transition-all duration-200 bg-gray-700 text-gray-300 hover:bg-orange-500 hover:text-white"><svg
                                            xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            class="lucide lucide-play w-3 h-3 ml-0.5">
                                            <polygon points="5 3 19 12 5 21 5 3"></polygon>
                                        </svg></button><button
                                        class="flex items-center justify-center w-8 h-8 rounded-full transition-all duration-200 bg-red-500 text-white"><svg
                                            xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            class="lucide lucide-heart w-3 h-3 fill-current">
                                            <path
                                                d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z">
                                            </path>
                                        </svg></button><button
                                        class="flex items-center justify-center w-8 h-8 rounded-full transition-all duration-200 bg-gray-700 text-gray-300 hover:bg-green-500 hover:text-white"><svg
                                            xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            class="lucide lucide-rotate-ccw w-3 h-3">
                                            <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"></path>
                                            <path d="M3 3v5h5"></path>
                                        </svg></button></div>
                            </div>
                        </div>
                        <div
                            class="grid grid-cols-12 gap-4 p-4 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200 border-b border-gray-300 dark:border-gray-700">
                            <div class="col-span-1 flex items-center">
                                <div
                                    class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold bg-gradient-to-br from-orange-300 to-orange-500 text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-crown w-3 h-3">
                                        <path d="m2 4 3 12h14l3-12-6 7-4-7-4 7-6-7zm3 16h14"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="col-span-4 flex items-center gap-3">
                                <div class="relative group cursor-pointer"><img
                                        src="https://images.pexels.com/photos/2102587/pexels-photo-2102587.jpeg?auto=compress&amp;cs=tinysrgb&amp;w=300"
                                        alt="Cosmic Flow"
                                        class="w-12 h-12 rounded-lg object-cover transition-transform duration-300 group-hover:scale-105">
                                    <div
                                        class="absolute inset-0 bg-gray-950 bg-gray-950/0 group-hover:bg-gray-950/30 rounded-lg transition-all duration-300 flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            class="lucide lucide-external-link w-3 h-3 text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                            <path d="M15 3h6v6"></path>
                                            <path d="M10 14 21 3"></path>
                                            <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <h3
                                        class="font-semibold text-gray-900 dark:text-white truncate cursor-pointer hover:text-orange-400 transition-colors">
                                        Cosmic Flow</h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 truncate">Stellar Sound</p>
                                </div>
                            </div>
                            <div
                                class="col-span-2 flex items-center justify-center cursor-pointer hover:text-orange-400 transition-colors">
                                <span class="font-bold text-orange-400">9.1/10</span>
                            </div>
                            <div class="col-span-2 flex items-center justify-center"><span
                                    class="text-gray-500 dark:text-gray-300">2.6K</span></div>
                            <div class="col-span-2 flex items-center justify-center"><span
                                    class="text-gray-500 dark:text-gray-300">1.1K</span></div>
                            <div class="col-span-1 flex items-center justify-center">
                                <div class="flex items-center gap-1"><button
                                        class="flex items-center justify-center w-8 h-8 rounded-full transition-all duration-200 bg-gray-700 text-gray-300 hover:bg-orange-500 hover:text-white"><svg
                                            xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            class="lucide lucide-play w-3 h-3 ml-0.5">
                                            <polygon points="5 3 19 12 5 21 5 3"></polygon>
                                        </svg></button><button
                                        class="flex items-center justify-center w-8 h-8 rounded-full transition-all duration-200 bg-gray-700 text-gray-300 hover:bg-red-500 hover:text-white"><svg
                                            xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            class="lucide lucide-heart w-3 h-3 ">
                                            <path
                                                d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z">
                                            </path>
                                        </svg></button><button
                                        class="flex items-center justify-center w-8 h-8 rounded-full transition-all duration-200 bg-green-500 text-white"><svg
                                            xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            class="lucide lucide-rotate-ccw w-3 h-3">
                                            <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"></path>
                                            <path d="M3 3v5h5"></path>
                                        </svg></button></div>
                            </div>
                        </div>
                        <div
                            class="grid grid-cols-12 gap-4 p-4 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200 border-b border-gray-300 dark:border-gray-700">
                            <div class="col-span-1 flex items-center">
                                <div
                                    class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold bg-gray-700 text-gray-300">
                                    4</div>
                            </div>
                            <div class="col-span-4 flex items-center gap-3">
                                <div class="relative group cursor-pointer"><img
                                        src="https://images.pexels.com/photos/1629236/pexels-photo-1629236.jpeg?auto=compress&amp;cs=tinysrgb&amp;w=300"
                                        alt="Neon Nights"
                                        class="w-12 h-12 rounded-lg object-cover transition-transform duration-300 group-hover:scale-105">
                                    <div
                                        class="absolute inset-0 bg-gray-950 bg-gray-950/0 group-hover:bg-gray-950/30 rounded-lg transition-all duration-300 flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            class="lucide lucide-external-link w-3 h-3 text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                            <path d="M15 3h6v6"></path>
                                            <path d="M10 14 21 3"></path>
                                            <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <h3
                                        class="font-semibold text-gray-900 dark:text-white truncate cursor-pointer hover:text-orange-400 transition-colors">
                                        Neon Nights</h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 truncate">Synth Paradise</p>
                                </div>
                            </div>
                            <div
                                class="col-span-2 flex items-center justify-center cursor-pointer hover:text-orange-400 transition-colors">
                                <span class="font-bold text-orange-400">8.3/10</span>
                            </div>
                            <div class="col-span-2 flex items-center justify-center"><span
                                    class="text-gray-500 dark:text-gray-300">2.3K</span></div>
                            <div class="col-span-2 flex items-center justify-center"><span
                                    class="text-gray-500 dark:text-gray-300">920</span></div>
                            <div class="col-span-1 flex items-center justify-center">
                                <div class="flex items-center gap-1"><button
                                        class="flex items-center justify-center w-8 h-8 rounded-full transition-all duration-200 bg-gray-700 text-gray-300 hover:bg-orange-500 hover:text-white"><svg
                                            xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            class="lucide lucide-play w-3 h-3 ml-0.5">
                                            <polygon points="5 3 19 12 5 21 5 3"></polygon>
                                        </svg></button><button
                                        class="flex items-center justify-center w-8 h-8 rounded-full transition-all duration-200 bg-gray-700 text-gray-300 hover:bg-red-500 hover:text-white"><svg
                                            xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            class="lucide lucide-heart w-3 h-3 ">
                                            <path
                                                d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z">
                                            </path>
                                        </svg></button><button
                                        class="flex items-center justify-center w-8 h-8 rounded-full transition-all duration-200 bg-gray-700 text-gray-300 hover:bg-green-500 hover:text-white"><svg
                                            xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            class="lucide lucide-rotate-ccw w-3 h-3">
                                            <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"></path>
                                            <path d="M3 3v5h5"></path>
                                        </svg></button></div>
                            </div>
                        </div>
                        <div
                            class="grid grid-cols-12 gap-4 p-4 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200 border-b border-gray-300 dark:border-gray-700">
                            <div class="col-span-1 flex items-center">
                                <div
                                    class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold bg-gray-700 text-gray-300">
                                    5</div>
                            </div>
                            <div class="col-span-4 flex items-center gap-3">
                                <div class="relative group cursor-pointer"><img
                                        src="https://images.pexels.com/photos/1671325/pexels-photo-1671325.jpeg?auto=compress&amp;cs=tinysrgb&amp;w=300"
                                        alt="Ocean Breeze"
                                        class="w-12 h-12 rounded-lg object-cover transition-transform duration-300 group-hover:scale-105">
                                    <div
                                        class="absolute inset-0 bg-gray-950 bg-gray-950/0 group-hover:bg-gray-950/30 rounded-lg transition-all duration-300 flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            class="lucide lucide-external-link w-3 h-3 text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                            <path d="M15 3h6v6"></path>
                                            <path d="M10 14 21 3"></path>
                                            <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <h3
                                        class="font-semibold text-gray-900 dark:text-white truncate cursor-pointer hover:text-orange-400 transition-colors">
                                        Ocean Breeze</h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 truncate">Coastal Harmony</p>
                                </div>
                            </div>
                            <div
                                class="col-span-2 flex items-center justify-center cursor-pointer hover:text-orange-400 transition-colors">
                                <span class="font-bold text-orange-400">8/10</span>
                            </div>
                            <div class="col-span-2 flex items-center justify-center"><span
                                    class="text-gray-500 dark:text-gray-300">2.1K</span></div>
                            <div class="col-span-2 flex items-center justify-center"><span
                                    class="text-gray-500 dark:text-gray-300">850</span></div>
                            <div class="col-span-1 flex items-center justify-center">
                                <div class="flex items-center gap-1"><button
                                        class="flex items-center justify-center w-8 h-8 rounded-full transition-all duration-200 bg-gray-700 text-gray-300 hover:bg-orange-500 hover:text-white"><svg
                                            xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            class="lucide lucide-play w-3 h-3 ml-0.5">
                                            <polygon points="5 3 19 12 5 21 5 3"></polygon>
                                        </svg></button><button
                                        class="flex items-center justify-center w-8 h-8 rounded-full transition-all duration-200 bg-red-500 text-white"><svg
                                            xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            class="lucide lucide-heart w-3 h-3 fill-current">
                                            <path
                                                d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z">
                                            </path>
                                        </svg></button><button
                                        class="flex items-center justify-center w-8 h-8 rounded-full transition-all duration-200 bg-green-500 text-white"><svg
                                            xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            class="lucide lucide-rotate-ccw w-3 h-3">
                                            <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"></path>
                                            <path d="M3 3v5h5"></path>
                                        </svg></button></div>
                            </div>
                        </div> --}}

                        </div>
                    </div>
                    {{-- Grid view --}}
                    <div x-show="activeTab === 'gridView'" class="transition-all duration-500 opacity-100 scale-100">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                            @forelse ($topTracks as $track)
                                <div
                                    class="bg-gray-200 dark:bg-gray-800 rounded-2xl p-4 border transition-all duration-300 hover:scale-105 hover:shadow-xl shadow-lg {{ proUser($track['track_details']->user_urn) && $track['action_details']->is_featured
                                        ? 'shadow-orange-500/20 border-orange-300 dark:border-orange-500'
                                        : (proUser($track['track_details']->user_urn)
                                            ? 'shadow-gray-500/20 border-gray-500'
                                            : 'shadow-gray-700/20 border-gray-300 dark:border-gray-700') }}">
                                    <div class="relative mb-4">

                                        @if (proUser($track['track_details']->user_urn) && $track['action_details']->is_featured)
                                            <div
                                                class="absolute -top-2 -right-2 w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold z-10 bg-gradient-to-br from-orange-400 to-orange-600 text-white">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="lucide lucide-crown w-4 h-4">
                                                    <path d="m2 4 3 12h14l3-12-6 7-4-7-4 7-6-7zm3 16h14"></path>
                                                </svg>
                                            </div>
                                        @elseif (proUser($track['track_details']->user_urn))
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
                                            <img src="{{ soundcloud_image($track['track_details']->artwork_url) }}"
                                                alt="{{ $track['track_details']->title }}"
                                                class="w-full aspect-square rounded-xl object-cover transition-transform duration-300 group-hover:scale-105">
                                            <a href="{{ $track['track_details']->permalink_url }}" target="_blank"
                                                class="absolute inset-0 bg-gray-950 bg-gray-950/0 group-hover:bg-gray-950/30 rounded-xl transition-all duration-300 flex items-center justify-center">
                                                <x-lucide-external-link
                                                    class="w-6 h-6 text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300" />

                                            </a>
                                        </div>
                                    </div>
                                    <div class="mb-4">
                                        <a href="{{ $track['track_details']->permalink_url }}" target="_blank"
                                            class="font-bold text-black dark:text-white mb-1 cursor-pointer hover:text-orange-400 transition-colors truncate block w-full">
                                            {{ Str::limit($track['track_details']->title, 20, '...') }}</a>
                                        <a href="{{ route('user.my-account', $track['track_details']->user->urn) }}"
                                            class="text-gray-600 dark:text-gray-300 text-sm mb-2 truncate">
                                            {{ $track['track_details']->user?->name }}</a>
                                        <span
                                            class="inline-block bg-gray-300 dark:bg-gray-700 px-2 py-1 rounded-full text-xs text-gray-700 dark:text-gray-300 border border-gray-300 dark:border-gray-600">
                                            {{ $track['track_details']->genre ?? 'Unknown' }}
                                        </span>
                                    </div>
                                    <div class="mb-4">
                                        <div
                                            class="text-center cursor-pointer hover:bg-gray-300 dark:hover:bg-gray-700 rounded-lg p-2 transition-colors">
                                            <div class="text-xl font-bold text-orange-400">
                                                {{ $track['engagement_score'] }}/10</div>
                                            <div class="text-xs text-gray-800 dark:text-gray-400">Engagement Score
                                            </div>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-3 gap-2 mb-4 text-xs text-gray-800 dark:text-gray-400">
                                        <div class="text-center">
                                            <div class="font-semibold text-black dark:text-white">
                                                {{ number_shorten($track['metrics']['total_views']['current_total']) }}
                                            </div>
                                            <div>Reach</div>
                                        </div>
                                        <div class="text-center">
                                            <div class="font-semibold text-black dark:text-white">
                                                {{ number_shorten($track['metrics']['total_reposts']['current_total']) }}
                                            </div>
                                            <div>Reposts</div>
                                        </div>
                                        <div class="text-center">
                                            <div class="font-semibold text-black dark:text-white">
                                                {{ number_shorten($track['metrics']['total_likes']['current_total']) }}
                                            </div>
                                            <div>Plays</div>
                                        </div>
                                    </div>
                                    <div class="flex items-center justify-center gap-2">
                                        <button
                                            class="flex items-center justify-center w-10 h-10 rounded-full transition-all duration-200 bg-gray-700 text-gray-300 hover:bg-orange-500 hover:text-white hover:shadow-lg border border-gray-600">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-play w-4 h-4 ml-0.5">
                                                <polygon points="5 3 19 12 5 21 5 3"></polygon>
                                            </svg>
                                        </button>
                                        <button
                                            wire:click="likeTrack('{{ encrypt($track['action_details']->id) }}','{{ encrypt($track['track_details']->urn) }}')"
                                            class="flex items-center justify-center w-10 h-10 rounded-full transition-all duration-200 bg-gray-700 text-gray-300 hover:bg-red-500 hover:text-white hover:shadow-lg border border-gray-600">
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
                                            wire:click="repostTrack('{{ encrypt($track['action_details']->id) }}','{{ encrypt($track['track_details']->urn) }}')"
                                            class="flex items-center justify-center w-10 h-10 rounded-full transition-all duration-200 {{ $track['repost'] ? 'bg-green-500 text-white shadow-lg' : 'bg-gray-700 text-gray-300' }} hover:bg-green-500 hover:text-white hover:shadow-lg border border-gray-600"><svg
                                                xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-rotate-ccw w-4 h-4">
                                                <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"></path>
                                                <path d="M3 3v5h5"></path>
                                            </svg>
                                        </button>
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

                            @forelse ($topTracks as $track)
                            {{-- @dd($track); --}}
                                <div
                                    class="relative border rounded-2xl p-4 transition-all duration-300 hover:shadow-xl hover:scale-[1.02] bg-gradient-to-r from-gray-200 to-gray-200 dark:from-gray-900 dark:to-gray-800  shadow-lg {{ proUser($track['track_details']->user_urn) && $track['action_details']->is_featured
                                        ? 'shadow-orange-500/20 border-orange-300 dark:border-orange-500'
                                        : (proUser($track['track_details']->user_urn)
                                            ? 'shadow-gray-500/20 border-gray-500'
                                            : 'shadow-gray-700/20 border-gray-300 dark:border-gray-700') }}">

                                    @if (proUser($track['track_details']->user_urn) && $track['action_details']->is_featured)
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
                                    @elseif (proUser($track['track_details']->user_urn))
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
                                    <div class="flex items-center gap-4">
                                        <div
                                            class="w-12 h-12 rounded-full flex items-center justify-center font-bold bg-gradient-to-br shadow-lg text-2xl {{ proUser($track['track_details']->user_urn) && $track['action_details']->is_featured
                                                ? 'from-orange-400 to-orange-600 text-white'
                                                : (proUser($track['track_details']->user_urn)
                                                    ? 'from-gray-400 to-gray-600 text-white'
                                                    : 'bg-gray-700 text-gray-300 border border-gray-300 dark:border-gray-600') }}">
                                            {{ $loop->iteration }}
                                        </div>
                                        <div class="relative group cursor-pointer">
                                            <img src="{{ soundcloud_image($track['track_details']->artwork_url) }}"
                                                alt="{{ $track['track_details']->title }}"
                                                class="rounded-xl object-cover transition-transform duration-300 group-hover:scale-105 w-20 h-20">
                                            <a href="{{ $track['track_details']->permalink_url }}" target="_blank"
                                                class="absolute inset-0 shadow group-hover:bg-gray-950/30 rounded-xl transition-all duration-300 flex items-center justify-center">
                                                <x-lucide-external-link
                                                    class="w-6 h-6 text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300" />
                                            </a>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-start justify-between gap-2">
                                                <div class="flex-1 min-w-0">
                                                    <a href="{{ $track['track_details']->permalink_url }}"
                                                        target="_blank"
                                                        class="font-bold text-black dark:text-white truncate text-lg hover:text-orange-400 transition-colors cursor-pointer block w-full">
                                                        {{ Str::limit($track['track_details']->title, 30, '...') }}
                                                    </a>
                                                    <a href="{{ route('user.my-account', $track['track_details']->user->urn) }}"
                                                        class="text-gray-600 dark:text-gray-300 truncate">
                                                        {{ $track['track_details']->user->name }}</a>
                                                    <span
                                                        class="inline-block mt-1 bg-gray-300 dark:bg-gray-700 px-2 py-1 rounded-full text-xs text-gray-600 dark:text-gray-700 border border-gray-300 dark:border-gray-600">{{ $track['track_details']->genre ?? 'Unknown' }}</span>
                                                </div>
                                                <div class="text-right cursor-pointer">
                                                    <div class="text-lg font-bold text-black dark:text-white">
                                                        {{ $track['engagement_score'] }}/10</div>
                                                    <div class="text-xs text-gray-800 dark:text-gray-400">score</div>
                                                </div>
                                            </div>
                                            <div class="flex flex-wrap gap-1 mt-2">
                                                @if ($track['action_details']->is_featured)
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
                                                @if (proUser($track['track_details']->user_urn))
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
                                                    <span>Engagement</span><span>{{ number_format($track['engagement_rate'], 2) }}%</span>
                                                </div>
                                                <div class="w-full bg-gray-700 rounded-full h-2">
                                                    <div class="h-full rounded-full transition-all duration-500 bg-gradient-to-r from-orange-400 to-orange-600"
                                                        style="width: {{ $track['engagement_rate'] }}%;"></div>
                                                </div>
                                            </div>
                                            <div class="flex items-center justify-between">
                                                <div
                                                    class="flex items-center gap-4 text-xs text-gray-800 dark:text-gray-400">
                                                    <span>{{ number_shorten($track['metrics']['total_reposts']['current_total']) }}
                                                        reposts</span>
                                                    <span>{{ number_shorten($track['metrics']['total_views']['current_total']) }}
                                                        reach</span>
                                                    <span>{{ number_shorten($track['metrics']['total_plays']['current_total']) }}
                                                        plays</span>
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    <button
                                                        class="flex items-center justify-center w-10 h-10 rounded-full transition-all duration-200 bg-gray-700 text-gray-300 hover:bg-orange-500 hover:text-white hover:shadow-lg border border-gray-300 dark:border-gray-600 play-btn"
                                                        title="Play" data-title="{{ $track['track_details']->title }}"
                                                        data-artist="{{ $track['track_details']->author_username }}"
                                                        data-cover="{{ $track['track_details']->artwork_url }}"
                                                        data-src="{{ $track['track_details']->permalink_url }}">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                            height="24" viewBox="0 0 24 24" fill="none"
                                                            stroke="currentColor" stroke-width="2"
                                                            stroke-linecap="round" stroke-linejoin="round"
                                                            class="lucide lucide-play w-4 h-4 ml-0.5">
                                                            <polygon points="5 3 19 12 5 21 5 3"></polygon>
                                                        </svg>
                                                    </button>
                                                    <button
                                                        wire:click="likeTrack('{{ encrypt($track['action_details']->id) }}','{{ encrypt($track['track_details']->urn) }}')"
                                                        class="flex items-center justify-center w-10 h-10 rounded-full transition-all duration-200 bg-gray-700 text-gray-300 hover:bg-red-500 hover:text-white hover:shadow-lg border border-gray-300 dark:border-gray-600"
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
                                                        wire:click="repostTrack('{{ encrypt($track['action_details']->id) }}','{{ encrypt($track['track_details']->urn) }}')"
                                                        class="flex items-center justify-center w-10 h-10 rounded-full transition-all duration-200 {{ $track['repost'] ? 'bg-green-500 text-white shadow-lg' : 'bg-gray-700 text-gray-300' }}  hover:bg-green-500 hover:text-white hover:shadow-lg border border-gray-300 dark:border-gray-600"
                                                        title="Repost">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                            height="24" viewBox="0 0 24 24" fill="none"
                                                            stroke="currentColor" stroke-width="2"
                                                            stroke-linecap="round" stroke-linejoin="round"
                                                            class="lucide lucide-rotate-ccw w-4 h-4">
                                                            <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8">
                                                            </path>
                                                            <path d="M3 3v5h5"></path>
                                                        </svg>
                                                    </button>
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

        {{-- <div class="max-w-6xl mx-auto px-4 py-6">
            <!-- Header -->
            <div class="text-center mb-8">
                <h1
                    class="text-4xl md:text-5xl font-bold mb-3 bg-gradient-to-r from-orange-400 to-cyan-400 bg-clip-text text-transparent">
                    My Playlist
                </h1>
                <p class="text-gray-300 text-lg">Discover your favorite music</p>
            </div>

            <!-- Playlist Container -->
            <div class="bg-white/10 backdrop-blur-lg rounded-2xl p-6 shadow-2xl border border-white/20">
                <!-- Playlist Header -->
                <div class="flex justify-between items-center mb-6 pb-4 border-b border-white/20">
                    <div>
                        <h2 class="text-2xl font-semibold text-white">Recently Played</h2>
                        <p class="text-gray-400 text-sm mt-1">Your music collection</p>
                    </div>
                    <div class="text-right">
                        <span class="text-gray-300 text-sm" id="songCount">12 songs</span>
                        <div class="text-xs text-gray-500 mt-1">Total: 42:18</div>
                    </div>
                </div>

                <!-- Song List -->
                <div class="space-y-3" id="songList">
                    <!-- Song items will be populated by JavaScript -->
                </div>
            </div>
        </div>

        <!-- Footer Player -->
        <div class="fixed bottom-0 left-0 right-0 bg-gray-900/95 backdrop-blur-xl border-t border-gray-700/50 p-4 hidden shadow-2xl"
            id="footerPlayer">
            <div class="max-w-7xl mx-auto">
                <!-- Mobile Layout -->
                <div class="block md:hidden space-y-3">
                    <!-- Current Song Info -->
                    <div class="flex items-center space-x-3">
                        <div
                            class="w-12 h-12 bg-gradient-to-br from-orange-500 to-cyan-500 rounded-lg flex items-center justify-center text-xl animate-pulse-slow">
                            <x-lucide-music class="w-8 h-8 text-white" />
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="font-semibold text-white truncate" id="currentTitle">Feel Alone</div>
                            <div class="text-sm text-gray-400 truncate" id="currentArtist">Dilip Wannigamage</div>
                        </div>
                        <button
                            class="w-10 h-10 bg-gradient-to-r from-orange-500 to-cyan-500 rounded-full flex items-center justify-center text-white font-bold hover:scale-110 transition-transform duration-200 shadow-lg"
                            id="mainPlayBtn">
                            ▶
                        </button>
                    </div>

                    <!-- Progress Bar -->
                    <div class="flex items-center space-x-2 text-xs text-gray-400">
                        <span id="currentTime">0:36</span>
                        <div class="flex-1 h-1 bg-gray-600 rounded-full cursor-pointer group" id="progressBar">
                            <div class="h-full bg-gradient-to-r from-orange-500 to-cyan-500 rounded-full relative transition-all duration-300 group-hover:h-1.5"
                                style="width: 100%" id="progressFill">
                                <div
                                    class="absolute right-0 top-1/2 transform -translate-y-1/2 w-3 h-3 bg-white rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-200 shadow-lg">
                                </div>
                            </div>
                        </div>
                        <span id="totalTime">2:19</span>
                    </div>

                    <!-- Controls -->
                    <div class="flex items-center justify-center space-x-6">
                        <button
                            class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-white transition-colors duration-200"
                            id="prevBtn">
                            ⏮
                        </button>
                        <button
                            class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-white transition-colors duration-200"
                            id="nextBtn">
                            ⏭
                        </button>
                    </div>
                </div>

                <!-- Desktop Layout -->
                <div class="hidden md:flex items-center space-x-6">
                    <!-- Current Song Info -->
                    <div class="flex items-center space-x-4 min-w-0 w-80">
                        <div
                            class="w-14 h-14 bg-gradient-to-br from-orange-500 to-cyan-500 rounded-xl flex items-center justify-center text-2xl animate-pulse-slow shadow-lg">
                            <x-lucide-music class="w-8 h-8 text-white" />
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="font-semibold text-white truncate text-lg" id="currentTitleDesktop">Feel Alone
                            </div>
                            <div class="text-sm text-gray-400 truncate" id="currentArtistDesktop">Dilip Wannigamage
                            </div>
                        </div>
                    </div>

                    <!-- Main Controls -->
                    <div class="flex-1 max-w-2xl mx-8">
                        <div class="flex items-center justify-center space-x-6 mb-2">
                            <button
                                class="w-10 h-10 flex items-center justify-center text-gray-400 hover:text-white transition-all duration-200 hover:scale-110"
                                id="prevBtnDesktop">
                                ⏮
                            </button>
                            <button
                                class="w-12 h-12 bg-gradient-to-r from-orange-500 to-cyan-500 rounded-full flex items-center justify-center text-white font-bold hover:scale-110 transition-transform duration-200 shadow-lg hover:shadow-orange-500/25"
                                id="mainPlayBtnDesktop">
                                ▶
                            </button>
                            <button
                                class="w-10 h-10 flex items-center justify-center text-gray-400 hover:text-white transition-all duration-200 hover:scale-110"
                                id="nextBtnDesktop">
                                ⏭
                            </button>
                        </div>

                        <!-- Progress Bar -->
                        <div class="flex items-center space-x-3 text-sm text-gray-400">
                            <span class="min-w-10 text-right" id="currentTimeDesktop">0:00</span>
                            <div class="flex-1 h-1 bg-gray-600 rounded-full cursor-pointer group"
                                id="progressBarDesktop">
                                <div class="h-full bg-gradient-to-r from-orange-500 to-cyan-500 rounded-full relative transition-all duration-300 group-hover:h-1.5"
                                    style="width: 30%" id="progressFillDesktop">
                                    <div
                                        class="absolute right-0 top-1/2 transform -translate-y-1/2 w-3 h-3 bg-white rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-200 shadow-lg">
                                    </div>
                                </div>
                            </div>
                            <span class="min-w-10" id="totalTimeDesktop">2:19</span>
                        </div>
                    </div>

                    <!-- Volume & Additional Controls -->
                    <div class="flex items-center space-x-4 w-48">
                        <button
                            class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-white transition-colors duration-200"
                            id="volumeBtn">
                            <x-lucide-volume-2 class="w-6 h-6" />
                        </button>
                        <div class="flex-1 h-1 bg-gray-600 rounded-full cursor-pointer group" id="volumeBar">
                            <div class="h-full bg-gradient-to-r from-orange-500 to-cyan-500 rounded-full transition-all duration-300"
                                style="width: 70%" id="volumeFill"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            class MusicPlayer {
                constructor() {
                    this.currentSong = null;
                    this.isPlaying = false;
                    this.currentIndex = 0;
                    this.shuffle = false;
                    this.repeat = 'none'; // 'none', 'one', 'all'
                    this.volume = 0.7;
                    this.currentTime = 0;
                    this.duration = 0;
                    this.songs = [{
                            title: "Feel Alone",
                            artist: "Dilip Wannigamage",
                            duration: "2:19",
                            id: 1
                        },
                        {
                            title: "Midnight Dreams",
                            artist: "Luna Artist",
                            duration: "3:45",
                            id: 2
                        },
                        {
                            title: "Ocean Waves",
                            artist: "Nature Sounds",
                            duration: "4:12",
                            id: 3
                        },
                        {
                            title: "City Lights",
                            artist: "Urban Beats",
                            duration: "3:28",
                            id: 4
                        },
                        {
                            title: "Peaceful Mind",
                            artist: "Meditation Music",
                            duration: "5:30",
                            id: 5
                        },
                        {
                            title: "Summer Vibes",
                            artist: "Chill Collective",
                            duration: "3:55",
                            id: 6
                        },
                        {
                            title: "Electric Pulse",
                            artist: "Synth Masters",
                            duration: "4:08",
                            id: 7
                        },
                        {
                            title: "Acoustic Soul",
                            artist: "Folk Harmony",
                            duration: "3:22",
                            id: 8
                        },
                        {
                            title: "Digital Dreams",
                            artist: "Cyber Beats",
                            duration: "4:45",
                            id: 9
                        },
                        {
                            title: "Jazz Nights",
                            artist: "Smooth Collective",
                            duration: "5:12",
                            id: 10
                        },
                        {
                            title: "Mountain Echo",
                            artist: "Nature Symphony",
                            duration: "6:18",
                            id: 11
                        },
                        {
                            title: "Urban Rhythm",
                            artist: "Street Sounds",
                            duration: "3:33",
                            id: 12
                        }
                    ];
                    this.favorites = new Set();
                    this.initializePlayer();
                }

                initializePlayer() {
                    this.renderSongList();
                    this.setupEventListeners();
                    this.startProgressSimulation();
                }

                renderSongList() {
                    const songList = document.getElementById('songList');
                    songList.innerHTML = '';

                    this.songs.forEach((song, index) => {
                        const songElement = document.createElement('div');
                        songElement.className = `flex items-center p-4 rounded-xl bg-white/5 hover:bg-white/10 transition-all duration-300 cursor-pointer group hover:shadow-lg hover:-translate-y-0.5 ${
                        this.currentIndex === index && this.currentSong ? 'bg-gradient-to-r from-orange-500/20 to-cyan-500/20 border border-orange-500/30' : ''
                    }`;

                        songElement.innerHTML = `
                        <button class="w-12 h-12 bg-gradient-to-br from-orange-500 to-cyan-500 rounded-full flex items-center justify-center text-white font-bold hover:scale-110 transition-all duration-200 mr-4 shadow-lg group-hover:shadow-orange-500/25 play-btn" data-index="${index}">
                            ${this.currentIndex === index && this.isPlaying ? '⏸' : '▶'}
                        </button>
                        <div class="flex-1 min-w-0">
                            <div class="font-semibold text-white mb-1 truncate text-lg">${song.title}</div>
                            <div class="text-gray-400 text-sm truncate">${song.artist}</div>
                        </div>
                        <div class="flex items-center space-x-3">
                            <button class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-orange-400 transition-colors duration-200 favorite-btn" data-id="${song.id}">
                                ${this.favorites.has(song.id) ? '❤️' : '🤍'}
                            </button>
                            <button class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-white transition-colors duration-200 more-btn">
                                ⋮
                            </button>
                            <span class="text-gray-400 text-sm min-w-12 text-right">${song.duration}</span>
                        </div>
                    `;

                        songList.appendChild(songElement);
                    });

                    this.updateSongCount();
                }

                setupEventListeners() {
                    // Play buttons in song list
                    document.addEventListener('click', (e) => {
                        if (e.target.classList.contains('play-btn')) {
                            const index = parseInt(e.target.dataset.index);
                            this.playSong(index);
                        }

                        if (e.target.classList.contains('favorite-btn')) {
                            const songId = parseInt(e.target.dataset.id);
                            this.toggleFavorite(songId);
                        }
                    });

                    // Mobile controls
                    document.getElementById('mainPlayBtn')?.addEventListener('click', () => this.togglePlay());
                    document.getElementById('prevBtn')?.addEventListener('click', () => this.previousSong());
                    document.getElementById('nextBtn')?.addEventListener('click', () => this.nextSong());
                    document.getElementById('shuffleBtn')?.addEventListener('click', () => this.toggleShuffle());
                    document.getElementById('repeatBtn')?.addEventListener('click', () => this.toggleRepeat());

                    // Desktop controls
                    document.getElementById('mainPlayBtnDesktop')?.addEventListener('click', () => this.togglePlay());
                    document.getElementById('prevBtnDesktop')?.addEventListener('click', () => this.previousSong());
                    document.getElementById('nextBtnDesktop')?.addEventListener('click', () => this.nextSong());
                    document.getElementById('shuffleBtnDesktop')?.addEventListener('click', () => this.toggleShuffle());
                    document.getElementById('repeatBtnDesktop')?.addEventListener('click', () => this.toggleRepeat());
                    document.getElementById('favoriteBtn')?.addEventListener('click', () => this.toggleCurrentFavorite());
                    document.getElementById('volumeBtn')?.addEventListener('click', () => this.toggleMute());

                    // Progress bars
                    document.getElementById('progressBar')?.addEventListener('click', (e) => this.seekTo(e, 'mobile'));
                    document.getElementById('progressBarDesktop')?.addEventListener('click', (e) => this.seekTo(e,
                        'desktop'));

                    // Volume bar
                    document.getElementById('volumeBar')?.addEventListener('click', (e) => this.setVolume(e));
                }

                playSong(index) {
                    this.currentIndex = index;
                    this.currentSong = this.songs[index];
                    this.isPlaying = true;
                    this.currentTime = 0;
                    this.duration = this.parseTime(this.currentSong.duration);

                    this.showFooterPlayer();
                    this.updateFooterPlayer();
                    this.renderSongList(); // Re-render to update active states
                    this.updatePlayButtons();
                }

                togglePlay() {
                    if (!this.currentSong) {
                        this.playSong(0);
                        return;
                    }

                    this.isPlaying = !this.isPlaying;
                    this.updatePlayButtons();
                    this.renderSongList();
                }

                previousSong() {
                    let newIndex;
                    if (this.shuffle) {
                        newIndex = Math.floor(Math.random() * this.songs.length);
                    } else {
                        newIndex = this.currentIndex > 0 ? this.currentIndex - 1 : this.songs.length - 1;
                    }
                    this.playSong(newIndex);
                }

                nextSong() {
                    let newIndex;
                    if (this.shuffle) {
                        newIndex = Math.floor(Math.random() * this.songs.length);
                    } else {
                        newIndex = this.currentIndex < this.songs.length - 1 ? this.currentIndex + 1 : 0;
                    }
                    this.playSong(newIndex);
                }

                toggleShuffle() {
                    this.shuffle = !this.shuffle;
                    this.updateShuffleButtons();
                }

                toggleRepeat() {
                    const modes = ['none', 'all', 'one'];
                    const currentIndex = modes.indexOf(this.repeat);
                    this.repeat = modes[(currentIndex + 1) % modes.length];
                    this.updateRepeatButtons();
                }

                toggleFavorite(songId) {
                    if (this.favorites.has(songId)) {
                        this.favorites.delete(songId);
                    } else {
                        this.favorites.add(songId);
                    }
                    this.renderSongList();
                    this.updateFooterPlayer();
                }

                toggleCurrentFavorite() {
                    if (this.currentSong) {
                        this.toggleFavorite(this.currentSong.id);
                    }
                }

                toggleMute() {
                    this.volume = this.volume > 0 ? 0 : 0.7;
                    this.updateVolumeDisplay();
                }

                showFooterPlayer() {
                    const footerPlayer = document.getElementById('footerPlayer');
                    footerPlayer.classList.remove('hidden');
                    footerPlayer.classList.add('flex');
                }

                updateFooterPlayer() {
                    if (!this.currentSong) return;

                    // Update mobile
                    document.getElementById('currentTitle').textContent = this.currentSong.title;
                    document.getElementById('currentArtist').textContent = this.currentSong.artist;
                    document.getElementById('totalTime').textContent = this.currentSong.duration;

                    // Update desktop
                    document.getElementById('currentTitleDesktop').textContent = this.currentSong.title;
                    document.getElementById('currentArtistDesktop').textContent = this.currentSong.artist;
                    document.getElementById('totalTimeDesktop').textContent = this.currentSong.duration;

                    // Update favorite button
                    const favoriteBtn = document.getElementById('favoriteBtn');
                    if (favoriteBtn) {
                        favoriteBtn.textContent = this.favorites.has(this.currentSong.id) ? '❤️' : '🤍';
                    }
                }

                updatePlayButtons() {
                    const playText = this.isPlaying ? '⏸' : '▶';
                    document.getElementById('mainPlayBtn').textContent = playText;
                    document.getElementById('mainPlayBtnDesktop').textContent = playText;
                }

                updateShuffleButtons() {
                    const shuffleClass = this.shuffle ? 'text-orange-400' : 'text-gray-400';
                    document.getElementById('shuffleBtn').className = document.getElementById('shuffleBtn').className
                        .replace(/text-\w+-400/, shuffleClass);
                    document.getElementById('shuffleBtnDesktop').className = document.getElementById('shuffleBtnDesktop')
                        .className.replace(/text-\w+-400/, shuffleClass);
                }

                seekTo(e, type) {
                    const progressBar = type === 'mobile' ? document.getElementById('progressBar') : document
                        .getElementById('progressBarDesktop');
                    const rect = progressBar.getBoundingClientRect();
                    const percent = Math.max(0, Math.min(1, (e.clientX - rect.left) / rect.width));

                    if (this.currentSong) {
                        this.currentTime = Math.floor(this.duration * percent);
                        this.updateProgressDisplay();
                    }
                }

                setVolume(e) {
                    const volumeBar = document.getElementById('volumeBar');
                    const rect = volumeBar.getBoundingClientRect();
                    const percent = Math.max(0, Math.min(1, (e.clientX - rect.left) / rect.width));
                    this.volume = percent;
                    this.updateVolumeDisplay();
                }

                updateProgressDisplay() {
                    const percent = this.duration > 0 ? (this.currentTime / this.duration) * 100 : 0;
                    const timeString = this.formatTime(this.currentTime);

                    // Update mobile
                    document.getElementById('progressFill').style.width = percent + '%';
                    document.getElementById('currentTime').textContent = timeString;

                    // Update desktop
                    document.getElementById('progressFillDesktop').style.width = percent + '%';
                    document.getElementById('currentTimeDesktop').textContent = timeString;
                }

                updateVolumeDisplay() {
                    const volumePercent = this.volume * 100;
                    document.getElementById('volumeFill').style.width = volumePercent + '%';

                    const volumeIcon = this.volume === 0 ? '🔇' : this.volume < 0.5 ? '🔉' : '🔊';
                    document.getElementById('volumeBtn').textContent = volumeIcon;
                }

                updateSongCount() {
                    document.getElementById('songCount').textContent = `${this.songs.length} songs`;
                }

                startProgressSimulation() {
                    setInterval(() => {
                        if (this.isPlaying && this.currentSong) {
                            this.currentTime += 1;
                            if (this.currentTime >= this.duration) {
                                this.currentTime = 0;
                                if (this.repeat === 'one') {
                                    // Restart current song
                                } else if (this.repeat === 'all' || this.currentIndex < this.songs.length - 1) {
                                    this.nextSong();
                                } else {
                                    this.isPlaying = false;
                                    this.updatePlayButtons();
                                }
                            }
                            this.updateProgressDisplay();
                        }
                    }, 1000);
                }

                parseTime(timeString) {
                    const [minutes, seconds] = timeString.split(':').map(Number);
                    return minutes * 60 + seconds;
                }

                formatTime(seconds) {
                    const mins = Math.floor(seconds / 60);
                    const secs = seconds % 60;
                    return `${mins}:${secs.toString().padStart(2, '0')}`;
                }
            }

            // Initialize the music player when the page loads
            document.addEventListener('DOMContentLoaded', () => {
                new MusicPlayer();
            });
        </script> --}}




        {{-- <div class="max-w-6xl mx-auto px-4 py-6">
            <!-- Header -->
            <div class="text-center mb-8">
                <h1
                    class="text-4xl md:text-5xl font-bold mb-3 bg-gradient-to-r from-orange-400 to-cyan-400 bg-clip-text text-transparent">
                    My Playlist
                </h1>
                <p class="text-gray-300 text-lg">Discover your favorite music</p>
            </div>

            <!-- Playlist -->
            <div class="bg-white/10 backdrop-blur-lg rounded-2xl p-6 shadow-2xl border border-white/20">
                <div class="flex justify-between items-center mb-6 pb-4 border-b border-white/20">
                    <div>
                        <h2 class="text-2xl font-semibold text-white">Recently Played</h2>
                        <p class="text-gray-400 text-sm mt-1">Your music collection</p>
                    </div>
                    <span class="text-gray-300 text-sm">{{ count($songs) }} songs</span>
                </div>

                <!-- Song List -->
                <div class="space-y-3">
                    @foreach ($songs as $index => $song)
                        <div
                            class="flex items-center p-4 rounded-xl bg-white/5 hover:bg-white/10 transition-all cursor-pointer {{ $currentIndex === $index ? 'border border-orange-400 bg-gradient-to-r from-orange-500/20 to-cyan-500/20' : '' }}">
                            <button wire:click="playSong({{ $index }})"
                                class="w-12 h-12 bg-gradient-to-br from-orange-500 to-cyan-500 rounded-full flex items-center justify-center text-white font-bold hover:scale-110 transition mr-4">
                                {{ $currentIndex === $index && $isPlaying ? '⏸' : '▶' }}
                            </button>
                            <div class="flex-1 min-w-0">
                                <div class="font-semibold text-white mb-1 truncate">{{ $song['title'] }}</div>
                                <div class="text-gray-400 text-sm truncate">{{ $song['artist'] }}</div>
                            </div>
                            <span class="text-gray-400 text-sm">{{ $song['duration'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Footer Player -->
            @if ($currentIndex !== null)
                <div
                    class="fixed bottom-0 left-0 right-0 bg-gray-900/95 backdrop-blur-xl border-t border-gray-700/50 p-4 shadow-2xl">
                    <div class="max-w-7xl mx-auto flex items-center justify-between">
                        <div>
                            <div class="text-white font-semibold">{{ $songs[$currentIndex]['title'] }}</div>
                            <div class="text-gray-400 text-sm">{{ $songs[$currentIndex]['artist'] }}</div>
                        </div>
                        <div class="flex items-center space-x-6">
                            <button wire:click="prevSong" class="text-gray-400 hover:text-white">⏮</button>
                            <button wire:click="togglePlay" class="text-white text-lg">
                                {{ $isPlaying ? '⏸' : '▶' }}
                            </button>
                            <button wire:click="nextSong" class="text-gray-400 hover:text-white">⏭</button>
                        </div>
                    </div>
                </div>
                <div class="fixed bottom-0 left-0 right-0 bg-gray-900/95 backdrop-blur-xl border-t border-gray-700/50 p-4  shadow-2xl"
                    id="footerPlayer">
                    <div class="max-w-7xl mx-auto">
                        <!-- Mobile Layout -->
                        <div class="block md:hidden space-y-3">
                            <!-- Current Song Info -->
                            <div class="flex items-center space-x-3">
                                <div
                                    class="w-12 h-12 bg-gradient-to-br from-orange-500 to-cyan-500 rounded-lg flex items-center justify-center text-xl animate-pulse-slow">
                                    <x-lucide-music class="w-8 h-8 text-white" />
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="font-semibold text-white truncate" id="currentTitle">{{ $songs[$currentIndex]['title'] }}</div>
                                    <div class="text-sm text-gray-400 truncate" id="currentArtist">{{ $songs[$currentIndex]['artist'] }}</div>
                                </div>
                                <button
                                    class="w-10 h-10 bg-gradient-to-r from-orange-500 to-cyan-500 rounded-full flex items-center justify-center text-white font-bold hover:scale-110 transition-transform duration-200 shadow-lg"
                                    id="mainPlayBtn">
                                    {{ $isPlaying ? '⏸' : '▶' }}
                                </button>
                            </div>

                            <!-- Progress Bar -->
                            <div class="flex items-center space-x-2 text-xs text-gray-400">
                                <span id="currentTime">0:36</span>
                                <div class="flex-1 h-1 bg-gray-600 rounded-full cursor-pointer group"
                                    id="progressBar">
                                    <div class="h-full bg-gradient-to-r from-orange-500 to-cyan-500 rounded-full relative transition-all duration-300 group-hover:h-1.5"
                                        style="width: 100%" id="progressFill">
                                        <div
                                            class="absolute right-0 top-1/2 transform -translate-y-1/2 w-3 h-3 bg-white rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-200 shadow-lg">
                                        </div>
                                    </div>
                                </div>
                                <span id="totalTime">2:19</span>
                            </div>

                            <!-- Controls -->
                            <div class="flex items-center justify-center space-x-6">
                                <button wire:click="prevSong"
                                    class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-white transition-colors duration-200"
                                    id="prevBtn">
                                    ⏮
                                </button>
                                <button wire:click="nextSong"
                                    class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-white transition-colors duration-200"
                                    id="nextBtn">
                                    ⏭
                                </button>
                            </div>
                        </div>

                        <!-- Desktop Layout -->
                        <div class="hidden md:flex items-center space-x-6">
                            <!-- Current Song Info -->
                            <div class="flex items-center space-x-4 min-w-0 w-80">
                                <div
                                    class="w-14 h-14 bg-gradient-to-br from-orange-500 to-cyan-500 rounded-xl flex items-center justify-center text-2xl animate-pulse-slow shadow-lg">
                                    <x-lucide-music class="w-8 h-8 text-white" />
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="font-semibold text-white truncate text-lg" id="currentTitleDesktop">
                                        {{ $songs[$currentIndex]['title'] }}
                                    </div>
                                    <div class="text-sm text-gray-400 truncate" id="currentArtistDesktop">Dilip
                                        {{ $songs[$currentIndex]['artist'] }}
                                    </div>
                                </div>
                            </div>

                            <!-- Main Controls -->
                            <div class="flex-1 max-w-2xl mx-8">
                                <div class="flex items-center justify-center space-x-6 mb-2">
                                    <button wire:click="prevSong"
                                        class="w-10 h-10 flex items-center justify-center text-gray-400 hover:text-white transition-all duration-200 hover:scale-110"
                                        id="prevBtnDesktop">
                                        ⏮
                                    </button>
                                    <button
                                        class="w-12 h-12 bg-gradient-to-r from-orange-500 to-cyan-500 rounded-full flex items-center justify-center text-white font-bold hover:scale-110 transition-transform duration-200 shadow-lg hover:shadow-orange-500/25"
                                        id="mainPlayBtnDesktop">
                                        {{ $isPlaying ? '⏸' : '▶' }}
                                    </button>
                                    <button wire:click="nextSong"
                                        class="w-10 h-10 flex items-center justify-center text-gray-400 hover:text-white transition-all duration-200 hover:scale-110"
                                        id="nextBtnDesktop">
                                        ⏭
                                    </button>
                                </div>

                                <!-- Progress Bar -->
                                <div class="flex items-center space-x-3 text-sm text-gray-400">
                                    <span class="min-w-10 text-right" id="currentTimeDesktop">0:00</span>
                                    <div class="flex-1 h-1 bg-gray-600 rounded-full cursor-pointer group"
                                        id="progressBarDesktop">
                                        <div class="h-full bg-gradient-to-r from-orange-500 to-cyan-500 rounded-full relative transition-all duration-300 group-hover:h-1.5"
                                            style="width: 30%" id="progressFillDesktop">
                                            <div
                                                class="absolute right-0 top-1/2 transform -translate-y-1/2 w-3 h-3 bg-white rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-200 shadow-lg">
                                            </div>
                                        </div>
                                    </div>
                                    <span class="min-w-10" id="totalTimeDesktop">2:19</span>
                                </div>
                            </div>

                            <!-- Volume & Additional Controls -->
                            <div class="flex items-center space-x-4 w-48">
                                <button
                                    class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-white transition-colors duration-200"
                                    id="volumeBtn">
                                    <x-lucide-volume-2 class="w-6 h-6" />
                                </button>
                                <div class="flex-1 h-1 bg-gray-600 rounded-full cursor-pointer group" id="volumeBar">
                                    <div class="h-full bg-gradient-to-r from-orange-500 to-cyan-500 rounded-full transition-all duration-300"
                                        style="width: 70%" id="volumeFill"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div> --}}



        {{-- <div class="bg-gradient-to-br from-blue-900 via-purple-900 to-indigo-900 text-white min-h-screen pb-20">

            <div class="max-w-6xl mx-auto px-4 py-6">
                <!-- Header -->
                <div class="text-center mb-8">
                    <h1
                        class="text-4xl md:text-5xl font-bold mb-3 bg-gradient-to-r from-orange-400 to-cyan-400 bg-clip-text text-transparent">
                        My Playlist
                    </h1>
                    <p class="text-gray-300 text-lg">Discover your favorite music</p>
                </div>

                <!-- Playlist Container -->
                <div class="bg-white/10 backdrop-blur-lg rounded-2xl p-6 shadow-2xl border border-white/20">
                    <!-- Playlist Header -->
                    <div class="flex justify-between items-center mb-6 pb-4 border-b border-white/20">
                        <div>
                            <h2 class="text-2xl font-semibold text-white">Recently Played</h2>
                            <p class="text-gray-400 text-sm mt-1">Your music collection</p>
                        </div>
                        <div class="text-right">
                            <span class="text-gray-300 text-sm">{{ count($songs) }} songs</span>
                            <div class="text-xs text-gray-500 mt-1">Total: 24:32</div>
                        </div>
                    </div>

                    <!-- Song List -->
                    <div class="space-y-3">
                        @foreach ($songs as $index => $song)
                            <div
                                class="flex items-center p-4 rounded-xl bg-white/5 hover:bg-white/10 transition-all duration-300 cursor-pointer group hover:shadow-lg hover:-translate-y-0.5 
                    {{ $currentIndex === $index && $currentSong ? 'bg-gradient-to-r from-orange-500/20 to-cyan-500/20 border border-orange-500/30' : '' }}">

                                <button wire:click="playSong({{ $index }})"
                                    class="w-12 h-12 bg-gradient-to-br from-orange-500 to-cyan-500 rounded-full flex items-center justify-center text-white font-bold hover:scale-110 transition-all duration-200 mr-4 shadow-lg group-hover:shadow-orange-500/25">
                                    {{ $currentIndex === $index && $isPlaying ? '⏸' : '▶' }}
                                </button>

                                <div class="flex-1 min-w-0" wire:click="playSong({{ $index }})">
                                    <div class="font-semibold text-white mb-1 truncate text-lg">{{ $song['title'] }}
                                    </div>
                                    <div class="text-gray-400 text-sm truncate">{{ $song['artist'] }}</div>
                                </div>

                                <div class="flex items-center space-x-3">
                                    <span
                                        class="text-gray-400 text-sm min-w-12 text-right">{{ $song['duration'] }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Footer Player -->
            @if ($currentSong)
                <div
                    class="fixed bottom-0 left-0 right-0 bg-gray-900/95 backdrop-blur-xl border-t border-gray-700/50 p-4 shadow-2xl">
                    <div class="max-w-7xl mx-auto">
                        <!-- Mobile Layout -->
                        <div class="block md:hidden space-y-3">
                            <!-- Current Song Info -->
                            <div class="flex items-center space-x-3">
                                <div
                                    class="w-12 h-12 bg-gradient-to-br from-orange-500 to-cyan-500 rounded-lg flex items-center justify-center text-xl animate-pulse">
                                    🎵
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="font-semibold text-white truncate">{{ $currentSong['title'] }}</div>
                                    <div class="text-sm text-gray-400 truncate">{{ $currentSong['artist'] }}</div>
                                </div>
                                <button wire:click="togglePlay"
                                    class="w-10 h-10 bg-gradient-to-r from-orange-500 to-cyan-500 rounded-full flex items-center justify-center text-white font-bold hover:scale-110 transition-transform duration-200 shadow-lg">
                                    {{ $isPlaying ? '⏸' : '▶' }}
                                </button>
                            </div>

                            <!-- Progress Bar -->
                            <div class="flex items-center space-x-2 text-xs text-gray-400">
                                <span>{{ $this->formatTime($currentTime) }}</span>
                                <div class="flex-1 h-1 bg-gray-600 rounded-full cursor-pointer group"
                                    x-data="{ seeking: false }"
                                    @click="
                            let rect = $el.getBoundingClientRect();
                            let percent = Math.max(0, Math.min(100, ((event.clientX - rect.left) / rect.width) * 100));
                            $wire.seekTo(percent);
                         ">
                                    <div class="h-full bg-gradient-to-r from-orange-500 to-cyan-500 rounded-full relative transition-all duration-300 group-hover:h-1.5"
                                        style="width: {{ $progressPercent }}%">
                                        <div
                                            class="absolute right-0 top-1/2 transform -translate-y-1/2 w-3 h-3 bg-white rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-200 shadow-lg">
                                        </div>
                                    </div>
                                </div>
                                <span>{{ $currentSong['duration'] }}</span>
                            </div>

                            <!-- Controls -->
                            <div class="flex items-center justify-center space-x-6">
                                <button wire:click="previousSong"
                                    class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-white transition-colors duration-200">
                                    ⏮
                                </button>
                                <button wire:click="nextSong"
                                    class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-white transition-colors duration-200">
                                    ⏭
                                </button>
                            </div>
                        </div>

                        <!-- Desktop Layout -->
                        <div class="hidden md:flex items-center space-x-6">
                            <!-- Current Song Info -->
                            <div class="flex items-center space-x-4 min-w-0 w-80">
                                <div
                                    class="w-14 h-14 bg-gradient-to-br from-orange-500 to-cyan-500 rounded-xl flex items-center justify-center text-2xl animate-pulse shadow-lg">
                                    🎵
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="font-semibold text-white truncate text-lg">{{ $currentSong['title'] }}
                                    </div>
                                    <div class="text-sm text-gray-400 truncate">{{ $currentSong['artist'] }}</div>
                                </div>
                            </div>

                            <!-- Main Controls -->
                            <div class="flex-1 max-w-2xl mx-8">
                                <div class="flex items-center justify-center space-x-6 mb-2">
                                    <button wire:click="previousSong"
                                        class="w-10 h-10 flex items-center justify-center text-gray-400 hover:text-white transition-all duration-200 hover:scale-110">
                                        ⏮
                                    </button>
                                    <button wire:click="togglePlay"
                                        class="w-12 h-12 bg-gradient-to-r from-orange-500 to-cyan-500 rounded-full flex items-center justify-center text-white font-bold hover:scale-110 transition-transform duration-200 shadow-lg hover:shadow-orange-500/25">
                                        {{ $isPlaying ? '⏸' : '▶' }}
                                    </button>
                                    <button wire:click="nextSong"
                                        class="w-10 h-10 flex items-center justify-center text-gray-400 hover:text-white transition-all duration-200 hover:scale-110">
                                        ⏭
                                    </button>
                                </div>

                                <!-- Progress Bar -->
                                <div class="flex items-center space-x-3 text-sm text-gray-400">
                                    <span class="min-w-10 text-right">{{ $this->formatTime($currentTime) }}</span>
                                    <div class="flex-1 h-1 bg-gray-600 rounded-full cursor-pointer group" x-data
                                        @click="
                                let rect = $el.getBoundingClientRect();
                                let percent = Math.max(0, Math.min(100, ((event.clientX - rect.left) / rect.width) * 100));
                                $wire.seekTo(percent);
                             ">
                                        <div class="h-full bg-gradient-to-r from-orange-500 to-cyan-500 rounded-full relative transition-all duration-300 group-hover:h-1.5"
                                            style="width: {{ $progressPercent }}%">
                                            <div
                                                class="absolute right-0 top-1/2 transform -translate-y-1/2 w-3 h-3 bg-white rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-200 shadow-lg">
                                            </div>
                                        </div>
                                    </div>
                                    <span class="min-w-10">{{ $currentSong['duration'] }}</span>
                                </div>
                            </div>

                            <!-- Volume Control -->
                            <div class="flex items-center space-x-4 w-48">
                                <button
                                    class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-white transition-colors duration-200">
                                    🔊
                                </button>
                                <div class="flex-1 h-1 bg-gray-600 rounded-full cursor-pointer group" x-data
                                    @click="
                            let rect = $el.getBoundingClientRect();
                            let percent = Math.max(0, Math.min(100, ((event.clientX - rect.left) / rect.width) * 100));
                            $wire.setVolume(percent);
                         ">
                                    <div class="h-full bg-gradient-to-r from-orange-500 to-cyan-500 rounded-full transition-all duration-300"
                                        style="width: {{ $volume }}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Auto-update progress (only when playing) -->
            @if ($isPlaying)
                <div x-data x-init="setInterval(() => {
                    $wire.updateProgress();
                }, 1000)"></div>
            @endif
        </div>

        @push('scripts')
            <script>
                // Listen for Livewire events
                window.addEventListener('song-changed', event => {
                    console.log('Song changed:', event.detail[0].song);
                });

                window.addEventListener('play-toggled', event => {
                    console.log('Play toggled:', event.detail[0].isPlaying);
                });

                window.addEventListener('seek-to', event => {
                    console.log('Seek to:', event.detail[0]);
                });

                window.addEventListener('volume-changed', event => {
                    console.log('Volume changed:', event.detail[0].volume);
                });
            </script>
        @endpush --}}





        {{-- <div class=" text-white min-h-screen pb-20">

            <div class="max-w-6xl mx-auto px-4 py-6">
                <!-- Header -->
                <div class="text-center mb-8">
                    <h1
                        class="text-4xl md:text-5xl font-bold mb-3 bg-gradient-to-r from-orange-400 to-cyan-400 bg-clip-text text-transparent">
                        My Playlist
                    </h1>
                    <p class="text-gray-300 text-lg">Discover your favorite music</p>
                </div>

                <!-- Playlist Container -->
                <div class="bg-white/10 backdrop-blur-lg rounded-2xl p-6 shadow-2xl border border-white/20">
                    <!-- Playlist Header -->
                    <div class="flex justify-between items-center mb-6 pb-4 border-b border-white/20">
                        <div>
                            <h2 class="text-2xl font-semibold text-white">Recently Played</h2>
                            <p class="text-gray-400 text-sm mt-1">Your music collection</p>
                        </div>
                        <div class="text-right">
                            <span class="text-gray-300 text-sm">{{ count($songs) }} songs</span>
                            <div class="text-xs text-gray-500 mt-1">Total: 24:32</div>
                        </div>
                    </div>

                    <!-- Song List -->
                    <div class="space-y-3">
                        @foreach ($songs as $index => $song)
                            <div
                                class="flex items-center p-4 rounded-xl bg-white/5 hover:bg-white/10 transition-all duration-300 cursor-pointer group hover:shadow-lg hover:-translate-y-0.5 
                    {{ $currentIndex === $index && $currentSong ? 'bg-gradient-to-r from-orange-500/20 to-cyan-500/20 border border-orange-500/30' : '' }}">

                                <button wire:click="playSong({{ $index }})"
                                    class="w-12 h-12 bg-gradient-to-br from-orange-500 to-cyan-500 rounded-full flex items-center justify-center text-white font-bold hover:scale-110 transition-all duration-200 mr-4 shadow-lg group-hover:shadow-orange-500/25">
                                    {{ $currentIndex === $index && $isPlaying ? '⏸' : '▶' }}
                                </button>

                                <div class="flex-1 min-w-0" wire:click="playSong({{ $index }})">
                                    <div class="font-semibold text-white mb-1 truncate text-lg">{{ $song['title'] }}
                                    </div>
                                    <div class="text-gray-400 text-sm truncate">{{ $song['artist'] }}</div>
                                </div>

                                <div class="flex items-center space-x-3">
                                    <span
                                        class="text-gray-400 text-sm min-w-12 text-right">{{ $song['duration'] }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Footer Player -->
            @if ($currentSong)
                <div
                    class="fixed bottom-0 left-0 right-0 bg-gray-900/95 backdrop-blur-xl border-t border-gray-700/50 p-4 shadow-2xl">
                    <div class="max-w-7xl mx-auto">
                        <!-- Mobile Layout -->
                        <div class="block md:hidden space-y-3">
                            <!-- Current Song Info -->
                            <div class="flex items-center space-x-3">
                                <div
                                    class="w-12 h-12 bg-gradient-to-br from-orange-500 to-cyan-500 rounded-lg flex items-center justify-center text-xl animate-pulse">
                                    🎵
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="font-semibold text-white truncate">{{ $currentSong['title'] }}</div>
                                    <div class="text-sm text-gray-400 truncate">{{ $currentSong['artist'] }}</div>
                                </div>
                                <button wire:click="togglePlay"
                                    class="w-10 h-10 bg-gradient-to-r from-orange-500 to-cyan-500 rounded-full flex items-center justify-center text-white font-bold hover:scale-110 transition-transform duration-200 shadow-lg">
                                    {{ $isPlaying ? '⏸' : '▶' }}
                                </button>
                            </div>

                            <!-- Progress Bar -->
                            <div class="flex items-center space-x-2 text-xs text-gray-400">
                                <span>{{ $this->formatTime($currentTime) }}</span>
                                <div class="flex-1 h-1 bg-gray-600 rounded-full cursor-pointer group"
                                    x-data="{ seeking: false }"
                                    @click="
                            let rect = $el.getBoundingClientRect();
                            let percent = Math.max(0, Math.min(100, ((event.clientX - rect.left) / rect.width) * 100));
                            $wire.seekTo(percent);
                         ">
                                    <div class="h-full bg-gradient-to-r from-orange-500 to-cyan-500 rounded-full relative transition-all duration-300 group-hover:h-1.5"
                                        style="width: {{ $progressPercent }}%">
                                        <div
                                            class="absolute right-0 top-1/2 transform -translate-y-1/2 w-3 h-3 bg-white rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-200 shadow-lg">
                                        </div>
                                    </div>
                                </div>
                                <span>{{ $currentSong['duration'] }}</span>
                            </div>

                            <!-- Controls -->
                            <div class="flex items-center justify-center space-x-6">
                                <button wire:click="previousSong"
                                    class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-white transition-colors duration-200">
                                    ⏮
                                </button>
                                <button wire:click="nextSong"
                                    class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-white transition-colors duration-200">
                                    ⏭
                                </button>
                            </div>
                        </div>

                        <!-- Desktop Layout -->
                        <div class="hidden md:flex items-center space-x-6">
                            <!-- Current Song Info -->
                            <div class="flex items-center space-x-4 min-w-0 w-80">
                                <div
                                    class="w-14 h-14 bg-gradient-to-br from-orange-500 to-cyan-500 rounded-xl flex items-center justify-center text-2xl animate-pulse shadow-lg">
                                    🎵
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="font-semibold text-white truncate text-lg">{{ $currentSong['title'] }}
                                    </div>
                                    <div class="text-sm text-gray-400 truncate">{{ $currentSong['artist'] }}</div>
                                </div>
                            </div>

                            <!-- Main Controls -->
                            <div class="flex-1 max-w-2xl mx-8">
                                <div class="flex items-center justify-center space-x-6 mb-2">
                                    <button wire:click="previousSong"
                                        class="w-10 h-10 flex items-center justify-center text-gray-400 hover:text-white transition-all duration-200 hover:scale-110">
                                        ⏮
                                    </button>
                                    <button wire:click="togglePlay"
                                        class="w-12 h-12 bg-gradient-to-r from-orange-500 to-cyan-500 rounded-full flex items-center justify-center text-white font-bold hover:scale-110 transition-transform duration-200 shadow-lg hover:shadow-orange-500/25">
                                        {{ $isPlaying ? '⏸' : '▶' }}
                                    </button>
                                    <button wire:click="nextSong"
                                        class="w-10 h-10 flex items-center justify-center text-gray-400 hover:text-white transition-all duration-200 hover:scale-110">
                                        ⏭
                                    </button>
                                </div>

                                <!-- Progress Bar -->
                                <div class="flex items-center space-x-3 text-sm text-gray-400">
                                    <span class="min-w-10 text-right">{{ $this->formatTime($currentTime) }}</span>
                                    <div class="flex-1 h-1 bg-gray-600 rounded-full cursor-pointer group" x-data
                                        @click="
                                let rect = $el.getBoundingClientRect();
                                let percent = Math.max(0, Math.min(100, ((event.clientX - rect.left) / rect.width) * 100));
                                $wire.seekTo(percent);
                             ">
                                        <div class="h-full bg-gradient-to-r from-orange-500 to-cyan-500 rounded-full relative transition-all duration-300 group-hover:h-1.5"
                                            style="width: {{ $progressPercent }}%">
                                            <div
                                                class="absolute right-0 top-1/2 transform -translate-y-1/2 w-3 h-3 bg-white rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-200 shadow-lg">
                                            </div>
                                        </div>
                                    </div>
                                    <span class="min-w-10">{{ $currentSong['duration'] }}</span>
                                </div>
                            </div>

                            <!-- Volume Control -->
                            <div class="flex items-center space-x-4 w-48">
                                <button
                                    class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-white transition-colors duration-200">
                                    🔊
                                </button>
                                <div class="flex-1 h-1 bg-gray-600 rounded-full cursor-pointer group" x-data
                                    @click="
                            let rect = $el.getBoundingClientRect();
                            let percent = Math.max(0, Math.min(100, ((event.clientX - rect.left) / rect.width) * 100));
                            $wire.setVolume(percent);
                         ">
                                    <div class="h-full bg-gradient-to-r from-orange-500 to-cyan-500 rounded-full transition-all duration-300"
                                        style="width: {{ $volume }}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Auto-update progress (only when playing) -->
            @if ($isPlaying)
                <div x-data x-init="setInterval(() => {
                    $wire.updateProgress();
                }, 1000)"></div>
            @endif
        </div>

        <!-- Hidden Audio Element -->
        <audio id="audioPlayer" preload="auto">
            Your browser does not support the audio element.
        </audio>

        @push('scripts')
            <script>
                // Audio Player Management
                const audioPlayer = document.getElementById('audioPlayer');
                let currentSongUrl = '';

                // Demo music URLs (free samples)
                const demoSongs = [
                    'https://www.soundjay.com/misc/sounds/bell-ringing-05.mp3',
                    'https://www.soundjay.com/buttons/sounds/button-09.mp3',
                    'https://www.soundjay.com/buttons/sounds/button-10.mp3',
                    'https://www.soundjay.com/buttons/sounds/button-3.mp3',
                    'https://www.soundjay.com/buttons/sounds/button-4.mp3',
                    'https://www.soundjay.com/buttons/sounds/button-5.mp3'
                ];

                // Set volume on load
                audioPlayer.volume = 0.7;

                // Audio event listeners
                audioPlayer.addEventListener('loadedmetadata', function() {
                    console.log('Audio loaded, duration:', audioPlayer.duration);
                });

                audioPlayer.addEventListener('timeupdate', function() {
                    if (audioPlayer.duration) {
                        const percent = (audioPlayer.currentTime / audioPlayer.duration) * 100;
                        const currentTime = Math.floor(audioPlayer.currentTime);

                        // Update Livewire component
                        @this.set('progressPercent', percent);
                        @this.set('currentTime', currentTime);
                    }
                });

                audioPlayer.addEventListener('ended', function() {
                    @this.call('nextSong');
                });

                // Listen for Livewire events
                window.addEventListener('song-changed', event => {
                    const song = event.detail[0].song;
                    const isPlaying = event.detail[0].isPlaying;

                    // Use demo URL or provided URL
                    const songIndex = @this.currentIndex;
                    currentSongUrl = demoSongs[songIndex] || song.url || demoSongs[0];

                    audioPlayer.src = currentSongUrl;
                    audioPlayer.load();

                    if (isPlaying) {
                        audioPlayer.play().catch(e => {
                            console.log('Autoplay prevented:', e);
                        });
                    }
                });

                window.addEventListener('play-toggled', event => {
                    const isPlaying = event.detail[0].isPlaying;

                    if (isPlaying) {
                        audioPlayer.play().catch(e => {
                            console.log('Play failed:', e);
                            // Reset playing state if play fails
                            @this.set('isPlaying', false);
                        });
                    } else {
                        audioPlayer.pause();
                    }
                });

                window.addEventListener('seek-to', event => {
                    const percent = event.detail[0].percent;
                    const currentTime = event.detail[0].currentTime;

                    if (audioPlayer.duration) {
                        audioPlayer.currentTime = (percent / 100) * audioPlayer.duration;
                    }
                });

                window.addEventListener('volume-changed', event => {
                    const volume = event.detail[0].volume;
                    audioPlayer.volume = volume / 100;
                });

                // Initialize demo message
                console.log('🎵 Demo Music Player Ready!');
                console.log('Available demo tracks:', demoSongs.length);
            </script>
        @endpush --}}


        <style>
            .player-hidden {
                transform: translateY(100%);
            }

            .player-visible {
                transform: translateY(0);
            }

            .progress-bar {
                transition: width 0.1s ease-out;
            }

            .volume-slider {
                background: linear-gradient(to right, #f97316 0%, #f97316 var(--volume), #374151 var(--volume), #374151 100%);
            }
        </style>
        <!-- Sample track list (simplified version of your template) -->
        <div class="container mx-auto px-4 py-8">
            <h1 class="text-3xl font-bold mb-8 text-center">Weekly Top Tracks</h1>

            <div class="space-y-4">
                <!-- Track 1 -->
                <div
                    class="bg-gray-800 rounded-lg p-4 flex items-center justify-between hover:bg-gray-700 transition-colors">
                    <div class="flex items-center gap-4">
                        <img src="https://images.unsplash.com/photo-1493225457124-a3eb161ffa5f?w=300&h=300&fit=crop&crop=center"
                            alt="Midnight Dreams" class="w-16 h-16 rounded-lg object-cover">
                        <div>
                            <h3 class="font-bold">Midnight Dreams</h3>
                            <p class="text-gray-400">Luna Waves</p>
                        </div>
                    </div>
                    <button class="play-btn bg-orange-500 hover:bg-orange-600 p-3 rounded-full transition-colors"
                        data-title="Midnight Dreams" data-artist="Luna Waves"
                        data-cover="https://images.unsplash.com/photo-1493225457124-a3eb161ffa5f?w=300&h=300&fit=crop&crop=center"
                        data-src="https://www.soundhelix.com/examples/mp3/SoundHelix-Song-1.mp3">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M8 5v14l11-7z" />
                        </svg>
                    </button>
                </div>

                <!-- Track 2 -->
                <div
                    class="bg-gray-800 rounded-lg p-4 flex items-center justify-between hover:bg-gray-700 transition-colors">
                    <div class="flex items-center gap-4">
                        <img src="https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4?w=300&h=300&fit=crop&crop=center"
                            alt="Urban Pulse" class="w-16 h-16 rounded-lg object-cover">
                        <div>
                            <h3 class="font-bold">Urban Pulse</h3>
                            <p class="text-gray-400">Metro Vibes</p>
                        </div>
                    </div>
                    <button class="play-btn bg-orange-500 hover:bg-orange-600 p-3 rounded-full transition-colors"
                        data-title="Urban Pulse" data-artist="Metro Vibes"
                        data-cover="https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4?w=300&h=300&fit=crop&crop=center"
                        data-src="https://www.soundhelix.com/examples/mp3/SoundHelix-Song-2.mp3">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M8 5v14l11-7z" />
                        </svg>
                    </button>
                </div>

                <!-- Track 3 -->
                <div
                    class="bg-gray-800 rounded-lg p-4 flex items-center justify-between hover:bg-gray-700 transition-colors">
                    <div class="flex items-center gap-4">
                        <img src="https://images.unsplash.com/photo-1470225620780-dba8ba36b745?w=300&h=300&fit=crop&crop=center"
                            alt="Cosmic Flow" class="w-16 h-16 rounded-lg object-cover">
                        <div>
                            <h3 class="font-bold">Cosmic Flow</h3>
                            <p class="text-gray-400">Stellar Sound</p>
                        </div>
                    </div>
                    <button class="play-btn bg-orange-500 hover:bg-orange-600 p-3 rounded-full transition-colors"
                        data-title="Cosmic Flow" data-artist="Stellar Sound"
                        data-cover="https://images.unsplash.com/photo-1470225620780-dba8ba36b745?w=300&h=300&fit=crop&crop=center"
                        data-src="https://www.soundhelix.com/examples/mp3/SoundHelix-Song-3.mp3">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M8 5v14l11-7z" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Bottom Music Player -->
        <div id="bottomPlayer"
            class="fixed bottom-0 left-0 right-0 bg-gray-800 border-t border-gray-700 px-4 py-3 transform transition-transform duration-300 player-hidden z-50">
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
                            <div id="progressBar" class="bg-orange-500 h-1 rounded-full progress-bar"
                                style="width: 0%"></div>
                        </div>
                        <span id="totalTime" class="text-xs text-gray-400 w-10">0:00</span>
                    </div>
                </div>

                <!-- Volume and Additional Controls -->
                <div class="flex items-center gap-3 flex-1 justify-end">
                    <button class="text-gray-400 hover:text-white transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                            </path>
                        </svg>
                    </button>
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M3 9v6h4l5 5V4L7 9H3zm13.5 3c0-1.77-1.02-3.29-2.5-4.03v8.05c1.48-.73 2.5-2.25 2.5-4.02zM14 3.23v2.06c2.89.86 5 3.54 5 6.71s-2.11 5.85-5 6.71v2.06c4.01-.91 7-4.49 7-8.77s-2.99-7.86-7-8.77z" />
                        </svg>
                        <input type="range" id="volumeSlider" min="0" max="100" value="50"
                            class="w-24 h-1 bg-gray-600 rounded-lg appearance-none cursor-pointer volume-slider">
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

    </div>
</section>
