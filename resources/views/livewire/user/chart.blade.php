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
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-trophy w-8 h-8">
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
                                <h1 class="text-3xl md:text-4xl font-bold mb-2 text-gray-900 dark:text-white">Weekly Top
                                    {{ $topTracks->count() }} / 20 Chart</h1>
                                <div class="flex items-center gap-4 text-gray-500 dark:text-gray-300">
                                    <div class="flex items-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
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
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
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
                                    stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-share2 w-4 h-4">
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
                        <h2 class="text-xl font-bold text-gray-800 dark:text-white">{{ $topTracks->count() }} Tracks
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
                                stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-list w-4 h-4">
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
                                stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-grid3x3 w-4 h-4">
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
                <div x-show="activeTab === 'compactView'" class="transition-all duration-500 opacity-100 scale-100">
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
                                            class="font-semibold text-gray-900 dark:text-white truncate cursor-pointer hover:text-orange-400 transition-colors block">
                                            {{ $track['track_details']->title }}
                                        </a>
                                        <a href="{{ route('user.my-account', $track['track_details']->user->urn) }}"
                                            class="text-sm text-gray-600 dark:text-gray-400 truncate hover:text-orange-400 transition-colors block">
                                            {{ $track['track_details']->user->name }}</a>
                                    </div>
                                </div>

                                <div
                                    class="col-span-2 flex items-center justify-center cursor-pointer hover:text-orange-400 transition-colors">
                                    <span class="font-bold text-orange-400">10/10</span>
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
                                        class="font-bold text-black dark:text-white text-lg mb-1 cursor-pointer hover:text-orange-400 transition-colors truncate">
                                        {{ $track['track_details']->title }}</a>
                                    <a href="{{ route('user.my-account', $track['track_details']->user->urn) }}"
                                        class="text-gray-600 dark:text-gray-300 text-sm mb-2 truncate">
                                        {{ $track['track_details']->user?->name }}</a>
                                    <span
                                        class="inline-block bg-gray-300 dark:bg-gray-700 px-2 py-1 rounded-full text-xs text-gray-700 dark:text-gray-300 border border-gray-300 dark:border-gray-600">
                                        {{ $track['track_details']->genre }}
                                    </span>
                                </div>
                                <div class="mb-4">
                                    <div
                                        class="text-center cursor-pointer hover:bg-gray-300 dark:hover:bg-gray-700 rounded-lg p-2 transition-colors">
                                        <div class="text-xl font-bold text-orange-400">10/10</div>
                                        <div class="text-xs text-gray-800 dark:text-gray-400">Engagement Score</div>
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
                                        class="flex items-center justify-center w-10 h-10 rounded-full transition-all duration-200 bg-gray-700 text-gray-300 hover:bg-green-500 hover:text-white hover:shadow-lg border border-gray-600"><svg
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
                        {{-- <div
                            class="bg-gray-200 dark:bg-gray-800 rounded-2xl p-4 border transition-all duration-300 hover:scale-105 hover:shadow-xl border-orange-300 dark:border-orange-500 shadow-xl          shadow-orange-500/20">
                            <div class="relative mb-4">
                                <div
                                    class="absolute -top-2 -right-2 w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold z-10 bg-gradient-to-br from-orange-400 to-orange-600 text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-crown w-4 h-4">
                                        <path d="m2 4 3 12h14l3-12-6 7-4-7-4 7-6-7zm3 16h14"></path>
                                    </svg>
                                </div>
                                <div class="relative group cursor-pointer"><img
                                        src="https://images.pexels.com/photos/1763075/pexels-photo-1763075.jpeg?auto=compress&amp;cs=tinysrgb&amp;w=300"
                                        alt="Midnight Dreams"
                                        class="w-full aspect-square rounded-xl object-cover transition-transform duration-300 group-hover:scale-105">
                                    <div
                                        class="absolute inset-0 bg-gray-950 bg-gray-950/0 group-hover:bg-gray-950/30 rounded-xl transition-all duration-300 flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            class="lucide lucide-external-link w-6 h-6 text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                            <path d="M15 3h6v6"></path>
                                            <path d="M10 14 21 3"></path>
                                            <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6">
                                            </path>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-4">
                                <h3
                                    class="font-bold text-black dark:text-white text-lg mb-1 cursor-pointer hover:text-orange-400 transition-colors truncate">
                                    Midnight Dreams</h3>
                                <p class="text-gray-600 dark:text-gray-300 text-sm mb-2 truncate">Luna Waves</p><span
                                    class="inline-block bg-gray-300 dark:bg-gray-700 px-2 py-1 rounded-full text-xs text-gray-700 dark:text-gray-300 border border-gray-300 dark:border-gray-600">Electronic</span>
                            </div>
                            <div class="mb-4">
                                <div
                                    class="text-center cursor-pointer hover:bg-gray-300 dark:hover:bg-gray-700 rounded-lg p-2 transition-colors">
                                    <div class="text-xl font-bold text-orange-400">10/10</div>
                                    <div class="text-xs text-gray-800 dark:text-gray-400">Engagement Score</div>
                                </div>
                            </div>
                            <div class="grid grid-cols-3 gap-2 mb-4 text-xs text-gray-800 dark:text-gray-400">
                                <div class="text-center">
                                    <div class="font-semibold text-black dark:text-white">3.2K</div>
                                    <div>Reach</div>
                                </div>
                                <div class="text-center">
                                    <div class="font-semibold text-black dark:text-white">1.3K</div>
                                    <div>Reposts</div>
                                </div>
                                <div class="text-center">
                                    <div class="font-semibold text-black dark:text-white">15.4K</div>
                                    <div>Plays</div>
                                </div>
                            </div>
                            <div class="flex items-center justify-center gap-2"><button
                                    class="flex items-center justify-center w-10 h-10 rounded-full transition-all duration-200 bg-gray-700 text-gray-300 hover:bg-orange-500 hover:text-white hover:shadow-lg border border-gray-600"><svg
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-play w-4 h-4 ml-0.5">
                                        <polygon points="5 3 19 12 5 21 5 3"></polygon>
                                    </svg></button><button
                                    class="flex items-center justify-center w-10 h-10 rounded-full transition-all duration-200 bg-gray-700 text-gray-300 hover:bg-red-500 hover:text-white hover:shadow-lg border border-gray-600"><svg
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-heart w-4 h-4 ">
                                        <path
                                            d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z">
                                        </path>
                                    </svg></button><button
                                    class="flex items-center justify-center w-10 h-10 rounded-full transition-all duration-200 bg-gray-700 text-gray-300 hover:bg-green-500 hover:text-white hover:shadow-lg border border-gray-600"><svg
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-rotate-ccw w-4 h-4">
                                        <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"></path>
                                        <path d="M3 3v5h5"></path>
                                    </svg></button></div>
                        </div>
                        <div
                            class="bg-gray-200 dark:bg-gray-800 rounded-2xl p-4 border transition-all duration-300 hover:scale-105 hover:shadow-xl border-gray-500 shadow-lg shadow-gray-500/20">
                            <div class="relative mb-4">
                                <div
                                    class="absolute -top-2 -right-2 w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold z-10 bg-gradient-to-br from-gray-400 to-gray-600 text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-crown w-4 h-4">
                                        <path d="m2 4 3 12h14l3-12-6 7-4-7-4 7-6-7zm3 16h14"></path>
                                    </svg>
                                </div>
                                <div class="relative group cursor-pointer"><img
                                        src="https://images.pexels.com/photos/1190297/pexels-photo-1190297.jpeg?auto=compress&amp;cs=tinysrgb&amp;w=300"
                                        alt="Urban Pulse"
                                        class="w-full aspect-square rounded-xl object-cover transition-transform duration-300 group-hover:scale-105">
                                    <div
                                        class="absolute inset-0 bg-gray-950 bg-gray-950/0 group-hover:bg-gray-950/30 rounded-xl transition-all duration-300 flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            class="lucide lucide-external-link w-6 h-6 text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                            <path d="M15 3h6v6"></path>
                                            <path d="M10 14 21 3"></path>
                                            <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6">
                                            </path>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-4">
                                <h3 class="grid grid-cols-3 gap-2 mb-4 text-xs text-gray-800 dark:text-gray-400">
                                    Urban Pulse</h3>
                                <p class="text-gray-600 dark:text-gray-300 text-sm mb-2 truncate">Metro Vibes</p><span
                                    class="inline-block bg-gray-300 dark:bg-gray-700 px-2 py-1 rounded-full text-xs text-gray-600 dark:text-gray-300 border border-gray-300 dark:border-gray-600">Hip
                                    Hop</span>
                            </div>
                            <div class="mb-4">
                                <div
                                    class="text-center cursor-pointer hover:bg-gray-300 dark:hover:bg-gray-700 rounded-lg p-2 transition-colors">
                                    <div class="text-xl font-bold text-orange-400">9.4/10</div>
                                    <div class="text-xs text-gray-800 dark:text-gray-400">Engagement Score</div>
                                </div>
                            </div>
                            <div class="grid grid-cols-3 gap-2 mb-4 text-xs text-gray-800 dark:text-gray-400">
                                <div class="text-center">
                                    <div class="font-semibold text-black dark:text-white">2.9K</div>
                                    <div>Reach</div>
                                </div>
                                <div class="text-center">
                                    <div class="font-semibold text-black dark:text-white">1.2K</div>
                                    <div>Reposts</div>
                                </div>
                                <div class="text-center">
                                    <div class="font-semibold text-black dark:text-white">13.8K</div>
                                    <div>Plays</div>
                                </div>
                            </div>
                            <div class="flex items-center justify-center gap-2"><button
                                    class="flex items-center justify-center w-10 h-10 rounded-full transition-all duration-200 bg-gray-700 text-gray-300 hover:bg-orange-500 hover:text-white hover:shadow-lg border border-gray-600"><svg
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-play w-4 h-4 ml-0.5">
                                        <polygon points="5 3 19 12 5 21 5 3"></polygon>
                                    </svg></button><button
                                    class="flex items-center justify-center w-10 h-10 rounded-full transition-all duration-200 bg-red-500 text-white shadow-lg hover:bg-red-600"><svg
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-heart w-4 h-4 fill-current">
                                        <path
                                            d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z">
                                        </path>
                                    </svg></button><button
                                    class="flex items-center justify-center w-10 h-10 rounded-full transition-all duration-200 bg-gray-700 text-gray-300 hover:bg-green-500 hover:text-white hover:shadow-lg border border-gray-600"><svg
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-rotate-ccw w-4 h-4">
                                        <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"></path>
                                        <path d="M3 3v5h5"></path>
                                    </svg></button></div>
                        </div>
                        <div
                            class="bg-gray-200 dark:bg-gray-800 rounded-2xl p-4 border transition-all duration-300 hover:scale-105 hover:shadow-xl border-orange-300 dark:border-orange-500 shadow-xl shadow-orange-500/20">
                            <div class="relative mb-4">
                                <div
                                    class="absolute -top-2 -right-2 w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold z-10 bg-gradient-to-br from-orange-400 to-orange-600 text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-crown w-4 h-4">
                                        <path d="m2 4 3 12h14l3-12-6 7-4-7-4 7-6-7zm3 16h14"></path>
                                    </svg>
                                </div>
                                <div class="relative group cursor-pointer"><img
                                        src="https://images.pexels.com/photos/1763075/pexels-photo-1763075.jpeg?auto=compress&amp;cs=tinysrgb&amp;w=300"
                                        alt="Midnight Dreams"
                                        class="w-full aspect-square rounded-xl object-cover transition-transform duration-300 group-hover:scale-105">
                                    <div
                                        class="absolute inset-0 bg-gray-950 bg-gray-950/0 group-hover:bg-gray-950/30 rounded-xl transition-all duration-300 flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            class="lucide lucide-external-link w-6 h-6 text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                            <path d="M15 3h6v6"></path>
                                            <path d="M10 14 21 3"></path>
                                            <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6">
                                            </path>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-4">
                                <h3
                                    class="font-bold text-black dark:text-white text-lg mb-1 cursor-pointer hover:text-orange-400 transition-colors truncate">
                                    Midnight Dreams</h3>
                                <p class="text-gray-600 dark:text-gray-300 text-sm mb-2 truncate">Luna Waves</p><span
                                    class="inline-block bg-gray-300 dark:bg-gray-700 px-2 py-1 rounded-full text-xs text-gray-700 dark:text-gray-300 border border-gray-300 dark:border-gray-600">Electronic</span>
                            </div>
                            <div class="mb-4">
                                <div
                                    class="text-center cursor-pointer hover:bg-gray-300 dark:hover:bg-gray-700 rounded-lg p-2 transition-colors">
                                    <div class="text-xl font-bold text-orange-400">10/10</div>
                                    <div class="text-xs text-gray-800 dark:text-gray-400">Engagement Score</div>
                                </div>
                            </div>
                            <div class="grid grid-cols-3 gap-2 mb-4 text-xs text-gray-800 dark:text-gray-400">
                                <div class="text-center">
                                    <div class="font-semibold text-black dark:text-white">3.2K</div>
                                    <div>Reach</div>
                                </div>
                                <div class="text-center">
                                    <div class="font-semibold text-black dark:text-white">1.3K</div>
                                    <div>Reposts</div>
                                </div>
                                <div class="text-center">
                                    <div class="font-semibold text-black dark:text-white">15.4K</div>
                                    <div>Plays</div>
                                </div>
                            </div>
                            <div class="flex items-center justify-center gap-2">
                                <button
                                    class="flex items-center justify-center w-10 h-10 rounded-full transition-all duration-200 bg-gray-700 text-gray-300 hover:bg-orange-500 hover:text-white hover:shadow-lg border border-gray-600"><svg
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-play w-4 h-4 ml-0.5">
                                        <polygon points="5 3 19 12 5 21 5 3"></polygon>
                                    </svg>
                                </button>
                                <button
                                    class="flex items-center justify-center w-10 h-10 rounded-full transition-all duration-200 bg-gray-700 text-gray-300 hover:bg-red-500 hover:text-white hover:shadow-lg border border-gray-600"><svg
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-heart w-4 h-4 ">
                                        <path
                                            d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z">
                                        </path>
                                    </svg>
                                </button>
                                <button
                                    class="flex items-center justify-center w-10 h-10 rounded-full transition-all duration-200 bg-green-500 text-white shadow-lg hover:bg-green-600"><svg
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-rotate-ccw w-4 h-4">
                                        <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"></path>
                                        <path d="M3 3v5h5"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div
                            class="bg-gray-200 dark:bg-gray-800 rounded-2xl p-4 border transition-all duration-300 hover:scale-105 hover:shadow-xl border-gray-300 dark:border-gray-700 shadow-lg shadow-gray-700/20">
                            <div class="relative mb-4">
                                <div
                                    class="absolute -top-2 -right-2 w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold z-10 bg-gray-700 text-gray-300">
                                    4</div>
                                <div class="relative group cursor-pointer"><img
                                        src="https://images.pexels.com/photos/1629236/pexels-photo-1629236.jpeg?auto=compress&amp;cs=tinysrgb&amp;w=300"
                                        alt="Neon Nights"
                                        class="w-full aspect-square rounded-xl object-cover transition-transform duration-300 group-hover:scale-105">
                                    <div
                                        class="absolute inset-0 bg-gray-950 bg-gray-950/0 group-hover:bg-gray-950/30 rounded-xl transition-all duration-300 flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            class="lucide lucide-external-link w-6 h-6 text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                            <path d="M15 3h6v6"></path>
                                            <path d="M10 14 21 3"></path>
                                            <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6">
                                            </path>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-4">
                                <h3 class="grid grid-cols-3 gap-2 mb-4 text-xs text-gray-800 dark:text-gray-400">
                                    Neon Nights</h3>
                                <p class="text-gray-600 dark:text-gray-300 text-sm mb-2 truncate">Synth Paradise</p>
                                <span
                                    class="inline-block bg-gray-300 dark:bg-gray-700 px-2 py-1 rounded-full text-xs text-gray-600 dark:text-gray-300 border border-gray-300 dark:border-gray-600">Synthwave</span>
                            </div>
                            <div class="mb-4">
                                <div
                                    class="text-center cursor-pointer hover:bg-gray-300 dark:hover:bg-gray-700 rounded-lg p-2 transition-colors">
                                    <div class="text-xl font-bold text-orange-400">8.3/10</div>
                                    <div class="text-xs text-gray-800 dark:text-gray-400">Engagement Score</div>
                                </div>
                            </div>
                            <div class="grid grid-cols-3 gap-2 mb-4 text-xs text-gray-800 dark:text-gray-400">
                                <div class="text-center">
                                    <div class="font-semibold text-black dark:text-white">2.3K</div>
                                    <div>Reach</div>
                                </div>
                                <div class="text-center">
                                    <div class="font-semibold text-black dark:text-white">920</div>
                                    <div>Reposts</div>
                                </div>
                                <div class="text-center">
                                    <div class="font-semibold text-black dark:text-white">10.9K</div>
                                    <div>Plays</div>
                                </div>
                            </div>
                            <div class="flex items-center justify-center gap-2"><button
                                    class="flex items-center justify-center w-10 h-10 rounded-full transition-all duration-200 bg-gray-700 text-gray-300 hover:bg-orange-500 hover:text-white hover:shadow-lg border border-gray-600"><svg
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-play w-4 h-4 ml-0.5">
                                        <polygon points="5 3 19 12 5 21 5 3"></polygon>
                                    </svg></button><button
                                    class="flex items-center justify-center w-10 h-10 rounded-full transition-all duration-200 bg-gray-700 text-gray-300 hover:bg-red-500 hover:text-white hover:shadow-lg border border-gray-600"><svg
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-heart w-4 h-4 ">
                                        <path
                                            d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z">
                                        </path>
                                    </svg></button><button
                                    class="flex items-center justify-center w-10 h-10 rounded-full transition-all duration-200 bg-gray-700 text-gray-300 hover:bg-green-500 hover:text-white hover:shadow-lg border border-gray-600"><svg
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-rotate-ccw w-4 h-4">
                                        <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"></path>
                                        <path d="M3 3v5h5"></path>
                                    </svg></button></div>
                        </div> --}}
                    </div>
                </div>
                {{-- List view --}}
                <div x-show="activeTab === 'listView'" class="transition-all duration-500 opacity-100 scale-100">
                    <div class="space-y-3">

                        @forelse ($topTracks as $track)
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
                                    <div class="relative group cursor-pointer"><img
                                            src="{{ soundcloud_image($track['track_details']->artwork_url) }}"
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
                                                    class="font-bold text-black dark:text-white truncate text-lg hover:text-orange-400 transition-colors cursor-pointer">
                                                    {{ $track['track_details']->title }}
                                                </a>
                                                <a href="{{ route('user.my-account', $track['track_details']->user->urn) }}"
                                                    class="text-gray-600 dark:text-gray-300 truncate">
                                                    {{ $track['track_details']->user->name }}</a>
                                                <span
                                                    class="inline-block mt-1 bg-gray-300 dark:bg-gray-700 px-2 py-1 rounded-full text-xs text-gray-600 dark:text-gray-700 border border-gray-300 dark:border-gray-600">{{ $track['track_details']->genre }}</span>
                                            </div>
                                            <div class="text-right cursor-pointer">
                                                <div class="text-lg font-bold text-black dark:text-white">10/10</div>
                                                <div class="text-xs text-gray-800 dark:text-gray-400">score</div>
                                            </div>
                                        </div>
                                        <div class="flex flex-wrap gap-1 mt-2">
                                            @if ($track['action_details']->is_featured)
                                                <span
                                                    class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium border bg-orange-900 text-orange-300 border-orange-700"><svg
                                                        xmlns="http://www.w3.org/2000/svg" width="24"
                                                        height="24" viewBox="0 0 24 24" fill="none"
                                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round" class="lucide lucide-award w-3 h-3">
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
                                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round" class="lucide lucide-star w-3 h-3">
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
                                                <span>Engagement</span><span>100%</span>
                                            </div>
                                            <div class="w-full bg-gray-700 rounded-full h-2">
                                                <div class="h-full rounded-full transition-all duration-500 bg-gradient-to-r from-orange-400 to-orange-600"
                                                    style="width: 100%;"></div>
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
                                                    class="flex items-center justify-center w-10 h-10 rounded-full transition-all duration-200 bg-gray-700 text-gray-300 hover:bg-orange-500 hover:text-white hover:shadow-lg border border-gray-300 dark:border-gray-600"
                                                    title="Play">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                        height="24" viewBox="0 0 24 24" fill="none"
                                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round"
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
                                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round" class="lucide lucide-heart w-4 h-4 ">
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
                                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round"
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
                                    <div class="mt-4 text-sm text-gray-600 dark:text-gray-400">No content found</div>
                                </div>
                            </div>
                        @endforelse

                        {{-- <div
                            class="relative border rounded-2xl p-4 transition-all duration-300 hover:shadow-xl hover:scale-[1.02] bg-gradient-to-r from-gray-200 to-gray-200 dark:from-gray-900 dark:to-gray-800 border-orange-500 shadow-xl shadow-orange-500/20">
                            <div class="absolute -top-2 -right-2 z-10">
                                <div
                                    class="w-8 h-8 rounded-full flex items-center justify-center bg-gradient-to-br from-orange-400 to-orange-600 text-white shadow-lg">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-crown w-4 h-4">
                                        <path d="m2 4 3 12h14l3-12-6 7-4-7-4 7-6-7zm3 16h14"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="flex items-center gap-4">
                                <div
                                    class="w-12 h-12 rounded-full flex items-center justify-center text-xl font-bold bg-gradient-to-br from-orange-400 to-orange-600 text-white shadow-lg text-2xl">
                                    1</div>
                                <div class="relative group cursor-pointer"><img
                                        src="https://images.pexels.com/photos/1763075/pexels-photo-1763075.jpeg?auto=compress&amp;cs=tinysrgb&amp;w=300"
                                        alt="Midnight Dreams"
                                        class="rounded-xl object-cover transition-transform duration-300 group-hover:scale-105 w-20 h-20">
                                    <div
                                        class="absolute inset-0 bg-gray-950 bg-gray-950/0 group-hover:bg-gray-950/30 rounded-xl transition-all duration-300">
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-start justify-between gap-2">
                                        <div class="flex-1 min-w-0 cursor-pointer">
                                            <h3 class="font-bold text-black dark:text-white truncate text-lg">Midnight
                                                Dreams</h3>
                                            <p class="text-gray-600 dark:text-gray-300 truncate">Luna Waves</p><span
                                                class="inline-block mt-1 bg-gray-300 dark:bg-gray-700 px-2 py-1 rounded-full text-xs text-gray-600 dark:text-gray-700 border border-gray-300 dark:border-gray-600">Electronic</span>
                                        </div>
                                        <div class="text-right cursor-pointer">
                                            <div class="text-lg font-bold text-black dark:text-white">10/10</div>
                                            <div class="text-xs text-gray-800 dark:text-gray-400">score</div>
                                        </div>
                                    </div>
                                    <div class="flex flex-wrap gap-1 mt-2"><span
                                            class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium border bg-orange-900 text-orange-300 border-orange-700"><svg
                                                xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-award w-3 h-3">
                                                <circle cx="12" cy="8" r="6"></circle>
                                                <path d="M15.477 12.89 17 22l-5-3-5 3 1.523-9.11"></path>
                                            </svg>Featured</span><span
                                            class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium border bg-blue-900 text-blue-300 border-blue-700"><svg
                                                xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-star w-3 h-3">
                                                <polygon
                                                    points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                                                </polygon>
                                            </svg>Pro Artist</span></div>
                                    <div class="mt-3 mb-3">
                                        <div
                                            class="flex items-center justify-between text-xs text-gray-800 dark:text-gray-400 mb-1">
                                            <span>Engagement</span><span>100%</span>
                                        </div>
                                        <div class="w-full bg-gray-700 rounded-full h-2">
                                            <div class="h-full rounded-full transition-all duration-500 bg-gradient-to-r from-orange-400 to-orange-600"
                                                style="width: 100%;"></div>
                                        </div>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-4 text-xs text-gray-800 dark:text-gray-400">
                                            <span>1.3K
                                                reposts</span><span>3.2K reach</span><span>15.4K plays</span>
                                        </div>
                                        <div class="flex items-center gap-2"><button
                                                class="flex items-center justify-center w-10 h-10 rounded-full transition-all duration-200 bg-gray-700 text-gray-300 hover:bg-orange-500 hover:text-white hover:shadow-lg border border-gray-300 dark:border-gray-600"
                                                title="Play"><svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                    height="24" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round" class="lucide lucide-play w-4 h-4 ml-0.5">
                                                    <polygon points="5 3 19 12 5 21 5 3"></polygon>
                                                </svg></button><button
                                                class="flex items-center justify-center w-10 h-10 rounded-full transition-all duration-200 bg-gray-700 text-gray-300 hover:bg-red-500 hover:text-white hover:shadow-lg border border-gray-300 dark:border-gray-600"
                                                title="Like"><svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                    height="24" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round" class="lucide lucide-heart w-4 h-4 ">
                                                    <path
                                                        d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z">
                                                    </path>
                                                </svg></button><button
                                                class="flex items-center justify-center w-10 h-10 rounded-full transition-all duration-200 bg-gray-700 text-gray-300 hover:bg-green-500 hover:text-white hover:shadow-lg border border-gray-300 dark:border-gray-600"
                                                title="Repost"><svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                    height="24" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round" class="lucide lucide-rotate-ccw w-4 h-4">
                                                    <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"></path>
                                                    <path d="M3 3v5h5"></path>
                                                </svg></button></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div
                            class="relative border rounded-2xl p-4 transition-all duration-300 hover:shadow-xl hover:scale-[1.02] bg-gradient-to-r from-gray-200 to-gray-200 dark:from-gray-900 dark:to-gray-800 border-gray-600 dark:border-gray-500 shadow-lg shadow-gray-500/20">
                            <div class="absolute -top-2 -right-2 z-10">
                                <div
                                    class="w-8 h-8 rounded-full flex items-center justify-center bg-gradient-to-br from-gray-400 to-gray-600 text-white shadow-lg">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-crown w-4 h-4">
                                        <path d="m2 4 3 12h14l3-12-6 7-4-7-4 7-6-7zm3 16h14"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="flex items-center gap-4">
                                <div
                                    class="w-12 h-12 rounded-full flex items-center justify-center text-xl font-bold bg-gradient-to-br from-gray-400 to-gray-600 text-white shadow-lg text-2xl">
                                    2</div>
                                <div class="relative group cursor-pointer"><img
                                        src="https://images.pexels.com/photos/1190297/pexels-photo-1190297.jpeg?auto=compress&amp;cs=tinysrgb&amp;w=300"
                                        alt="Urban Pulse"
                                        class="rounded-xl object-cover transition-transform duration-300 group-hover:scale-105 w-20 h-20">
                                    <div
                                        class="absolute inset-0 bg-gray-950 bg-gray-950/0 group-hover:bg-gray-950/30 rounded-xl transition-all duration-300">
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-start justify-between gap-2">
                                        <div class="flex-1 min-w-0 cursor-pointer">
                                            <h3 class="font-bold text-black dark:text-white truncate text-lg">Urban
                                                Pulse</h3>
                                            <p class="text-gray-600 dark:text-gray-300 truncate">Metro Vibes</p>
                                            <span
                                                class="inline-block mt-1 bg-gray-300 dark:bg-gray-700 px-2 py-1 rounded-full text-xs text-gray-600 dark:text-gray-300 border border-gray-300 dark:border-gray-600">Hip
                                                Hop</span>
                                        </div>
                                        <div class="text-right cursor-pointer">
                                            <div class="text-lg font-bold text-black dark:text-white">9.4/10</div>
                                            <div class="text-xs text-gray-800 dark:text-gray-400">score</div>
                                        </div>
                                    </div>
                                    <div class="flex flex-wrap gap-1 mt-2"><span
                                            class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium border bg-blue-900 text-blue-300 border-blue-700"><svg
                                                xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-star w-3 h-3">
                                                <polygon
                                                    points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                                                </polygon>
                                            </svg>Pro Artist</span></div>
                                    <div class="mt-3 mb-3">
                                        <div
                                            class="flex items-center justify-between text-xs text-gray-800 dark:text-gray-400 mb-1">
                                            <span>Engagement</span><span>94%</span>
                                        </div>
                                        <div class="w-full bg-gray-700 rounded-full h-2">
                                            <div class="h-full rounded-full transition-all duration-500 bg-gradient-to-r from-gray-400 to-gray-500"
                                                style="width: 93.8071%;"></div>
                                        </div>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-4 text-xs text-gray-800 dark:text-gray-400">
                                            <span>1.2K
                                                reposts</span><span>2.9K reach</span><span>13.8K plays</span>
                                        </div>
                                        <div class="flex items-center gap-2"><button
                                                class="flex items-center justify-center w-10 h-10 rounded-full transition-all duration-200 bg-gray-700 text-gray-300 hover:bg-orange-500 hover:text-white hover:shadow-lg border border-gray-300 dark:border-gray-600"
                                                title="Play"><svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                    height="24" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round" class="lucide lucide-play w-4 h-4 ml-0.5">
                                                    <polygon points="5 3 19 12 5 21 5 3"></polygon>
                                                </svg></button><button
                                                class="flex items-center justify-center w-10 h-10 rounded-full transition-all duration-200 bg-red-500 text-white shadow-lg hover:bg-red-600"
                                                title="Unreach"><svg xmlns="http://www.w3.org/2000/svg"
                                                    width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    class="lucide lucide-heart w-4 h-4 fill-current">
                                                    <path
                                                        d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z">
                                                    </path>
                                                </svg></button><button
                                                class="flex items-center justify-center w-10 h-10 rounded-full transition-all duration-200 bg-gray-700 text-gray-300 hover:bg-green-500 hover:text-white hover:shadow-lg border border-gray-300 dark:border-gray-600"
                                                title="Repost"><svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                    height="24" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round" class="lucide lucide-rotate-ccw w-4 h-4">
                                                    <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"></path>
                                                    <path d="M3 3v5h5"></path>
                                                </svg></button></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div
                            class="relative border rounded-2xl p-4 transition-all duration-300 hover:shadow-xl hover:scale-[1.02] bg-gradient-to-r from-gray-200 dark:from-gray-900 to-gray-200 dark:to-gray-800 border-orange-400 shadow-lg shadow-orange-400/20">
                            <div class="absolute -top-2 -right-2 z-10">
                                <div
                                    class="w-8 h-8 rounded-full flex items-center justify-center bg-gradient-to-br from-orange-300 to-orange-500 text-white shadow-lg">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-crown w-4 h-4">
                                        <path d="m2 4 3 12h14l3-12-6 7-4-7-4 7-6-7zm3 16h14"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="flex items-center gap-4">
                                <div
                                    class="w-12 h-12 rounded-full flex items-center justify-center text-xl font-bold bg-gradient-to-br from-orange-300 to-orange-500 text-white shadow-lg">
                                    3</div>
                                <div class="relative group cursor-pointer"><img
                                        src="https://images.pexels.com/photos/2102587/pexels-photo-2102587.jpeg?auto=compress&amp;cs=tinysrgb&amp;w=300"
                                        alt="Cosmic Flow"
                                        class="rounded-xl object-cover transition-transform duration-300 group-hover:scale-105 w-20 h-20">
                                    <div
                                        class="absolute inset-0 bg-gray-950 bg-gray-950/0 group-hover:bg-gray-950/30 rounded-xl transition-all duration-300">
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-start justify-between gap-2">
                                        <div class="flex-1 min-w-0 cursor-pointer">
                                            <h3 class="font-bold text-black dark:text-white truncate text-lg">Cosmic
                                                Flow</h3>
                                            <p class="text-gray-600 dark:text-gray-300 truncate">Stellar Sound</p>
                                            <span
                                                class="inline-block mt-1 bg-gray-300 dark:bg-gray-700 px-2 py-1 rounded-full text-xs text-gray-600 dark:text-gray-300 border border-gray-300 dark:border-gray-600">Ambient</span>
                                        </div>
                                        <div class="text-right cursor-pointer">
                                            <div class="text-lg font-bold text-black dark:text-white">9.1/10</div>
                                            <div class="text-xs text-gray-800 dark:text-gray-400">score</div>
                                        </div>
                                    </div>
                                    <div class="flex flex-wrap gap-1 mt-2"><span
                                            class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium border bg-green-900 text-green-300 border-green-700"><svg
                                                xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-trending-up w-3 h-3">
                                                <polyline points="22 7 13.5 15.5 8.5 10.5 2 17"></polyline>
                                                <polyline points="16 7 22 7 22 13"></polyline>
                                            </svg>Rising Star</span></div>
                                    <div class="mt-3 mb-3">
                                        <div
                                            class="flex items-center justify-between text-xs text-gray-800 dark:text-gray-400 mb-1">
                                            <span>Engagement</span><span>91%</span>
                                        </div>
                                        <div class="w-full bg-gray-700 rounded-full h-2">
                                            <div class="h-full rounded-full transition-all duration-500 bg-gradient-to-r from-orange-300 to-orange-500"
                                                style="width: 90.5584%;"></div>
                                        </div>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-4 text-xs text-gray-800 dark:text-gray-400">
                                            <span>1.1K
                                                reposts</span><span>2.6K reach</span><span>12.3K plays</span>
                                        </div>
                                        <div class="flex items-center gap-2"><button
                                                class="flex items-center justify-center w-10 h-10 rounded-full transition-all duration-200 bg-gray-700 text-gray-300 hover:bg-orange-500 hover:text-white hover:shadow-lg border border-gray-300 dark:border-gray-600"
                                                title="Play"><svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                    height="24" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round" class="lucide lucide-play w-4 h-4 ml-0.5">
                                                    <polygon points="5 3 19 12 5 21 5 3"></polygon>
                                                </svg></button><button
                                                class="flex items-center justify-center w-10 h-10 rounded-full transition-all duration-200 bg-gray-700 text-gray-300 hover:bg-red-500 hover:text-white hover:shadow-lg border border-gray-300 dark:border-gray-600"
                                                title="Like"><svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                    height="24" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round" class="lucide lucide-heart w-4 h-4 ">
                                                    <path
                                                        d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z">
                                                    </path>
                                                </svg></button><button
                                                class="flex items-center justify-center w-10 h-10 rounded-full transition-all duration-200 bg-green-500 text-white shadow-lg hover:bg-green-600"
                                                title="Unrepost"><svg xmlns="http://www.w3.org/2000/svg"
                                                    width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round" class="lucide lucide-rotate-ccw w-4 h-4">
                                                    <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"></path>
                                                    <path d="M3 3v5h5"></path>
                                                </svg></button></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div
                            class="relative border rounded-2xl p-4 transition-all duration-300 hover:shadow-xl hover:scale-[1.02] bg-gray-200 dark:bg-gray-800 border-gray-300 dark:border-gray-700">
                            <div class="flex items-center gap-4">
                                <div
                                    class="w-12 h-12 rounded-full flex items-center justify-center text-xl font-bold bg-gray-700 text-gray-300 border border-gray-300 dark:border-gray-600 ">
                                    4</div>
                                <div class="relative group cursor-pointer"><img
                                        src="https://images.pexels.com/photos/1629236/pexels-photo-1629236.jpeg?auto=compress&amp;cs=tinysrgb&amp;w=300"
                                        alt="Neon Nights"
                                        class="rounded-xl object-cover transition-transform duration-300 group-hover:scale-105 w-16 h-16">
                                    <div
                                        class="absolute inset-0 bg-gray-950 bg-gray-950/0 group-hover:bg-gray-950/30 rounded-xl transition-all duration-300">
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-start justify-between gap-2">
                                        <div class="flex-1 min-w-0 cursor-pointer">
                                            <h3 class="font-bold text-black dark:text-white truncate text-base">Neon
                                                Nights</h3>
                                            <p class="text-gray-600 dark:text-gray-300 truncate">Synth Paradise</p>
                                            <span
                                                class="inline-block mt-1 bg-gray-300 dark:bg-gray-700 px-2 py-1 rounded-full text-xs text-gray-600 dark:text-gray-300 border border-gray-300 dark:border-gray-600">Synthwave</span>
                                        </div>
                                        <div class="text-right cursor-pointer">
                                            <div class="text-lg font-bold text-black dark:text-white">8.3/10</div>
                                            <div class="text-xs text-gray-800 dark:text-gray-400">score</div>
                                        </div>
                                    </div>
                                    <div class="mt-3 mb-3">
                                        <div
                                            class="flex items-center justify-between text-xs text-gray-800 dark:text-gray-400 mb-1">
                                            <span>Engagement</span><span>83%</span>
                                        </div>
                                        <div class="w-full bg-gray-700 rounded-full h-2">
                                            <div class="h-full rounded-full transition-all duration-500 bg-gradient-to-r from-orange-500 to-orange-600"
                                                style="width: 82.7411%;"></div>
                                        </div>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-4 text-xs text-gray-800 dark:text-gray-400">
                                            <span>920
                                                reposts</span><span>2.3K reach</span><span>10.9K plays</span>
                                        </div>
                                        <div class="flex items-center gap-2"><button
                                                class="flex items-center justify-center w-10 h-10 rounded-full transition-all duration-200 bg-gray-700 text-gray-300 hover:bg-orange-500 hover:text-white hover:shadow-lg border border-gray-300 dark:border-gray-600"
                                                title="Play"><svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                    height="24" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round" class="lucide lucide-play w-4 h-4 ml-0.5">
                                                    <polygon points="5 3 19 12 5 21 5 3"></polygon>
                                                </svg></button><button
                                                class="flex items-center justify-center w-10 h-10 rounded-full transition-all duration-200 bg-gray-700 text-gray-300 hover:bg-red-500 hover:text-white hover:shadow-lg border border-gray-300 dark:border-gray-600"
                                                title="Like"><svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                    height="24" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round" class="lucide lucide-heart w-4 h-4 ">
                                                    <path
                                                        d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z">
                                                    </path>
                                                </svg></button><button
                                                class="flex items-center justify-center w-10 h-10 rounded-full transition-all duration-200 bg-gray-700 text-gray-300 hover:bg-green-500 hover:text-white hover:shadow-lg border border-gray-300 dark:border-gray-600"
                                                title="Repost"><svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                    height="24" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round" class="lucide lucide-rotate-ccw w-4 h-4">
                                                    <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"></path>
                                                    <path d="M3 3v5h5"></path>
                                                </svg></button></div>
                                    </div>
                                </div>
                            </div>
                        </div> --}}
                    </div>
                </div>

                <div class="mt-12 text-center">
                    <div
                        class="bg-gray-200 dark:bg-gray-800 rounded-2xl p-8 shadow-lg border border-gray-200 dark:border-gray-700">
                        <h3 class="text-xl font-bold dark:text-white text-gray-800 mb-4">Want to see your track here?
                        </h3>
                        <p class="text-gray-500 dark:text-gray-300 mb-6">Join our repost campaigns and boost your
                            engagement to climb
                            the charts!</p>
                        <div class="flex flex-col sm:flex-row gap-3 justify-center">
                            <button
                                class="bg-orange-500 text-white px-6 py-3 rounded-xl font-semibold hover:bg-orange-600 transition-all duration-200 transform hover:scale-105">Start
                                Campaign
                            </button>
                            <button
                                class="border border-gray-300 dark:border-gray-600 text-gray-700 dark:bg-gray-700 dark:text-gray-300 px-6 py-3 rounded-xl font-semibold bg-gray-100 hover:bg-white dark:hover:bg-gray-700 transition-all duration-200">Learn
                                More
                            </button>
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
