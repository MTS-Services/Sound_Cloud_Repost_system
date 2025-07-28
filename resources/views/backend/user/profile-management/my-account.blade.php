<section>
    {{-- @dd($transactions) --}}
    <x-slot name="page_slug">my-account</x-slot>
    <section class="flex-1 overflow-auto">
        <div class="min-h-screen bg-white dark:bg-slate-900">
            <!-- Header Section -->
            <div class="bg-gradient-to-r from-gray-100 to-gray-200 dark:from-slate-800 dark:to-slate-700 relative">
                <div class="absolute inset-0 dark:bg-black bg-opacity-10 dark:bg-opacity-30"></div>
                <div class="relative p-4 sm:p-6">
                    <div class="flex flex-col sm:flex-row items-start space-y-4 sm:space-y-0 sm:space-x-6">
                        <!-- Avatar -->
                        <div class="flex-shrink-0 mx-auto sm:mx-0">
                            <img src="{{ auth_storage_url(user()->avatar) }}" alt="{{ user()->name ?? 'name' }}"
                                class="w-24 h-24 sm:w-32 sm:h-32 rounded-full border-4 border-white dark:border-gray-300 shadow-lg">
                        </div>

                        <!-- User Info -->
                        <div class="flex-1 text-center w-full sm:text-left ml-0 sm:ml-4">
                            <div
                                class="flex flex-col sm:flex-row sm:items-center space-y-2 sm:space-y-0 sm:space-x-4 mb-2">
                                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">
                                    {{ user()->name ?? 'name' }}</h1>
                                <span
                                    class="px-3 py-1 bg-orange-500 dark:bg-orange-400 text-white dark:text-gray-900 text-sm font-medium rounded-full self-center">
                                    NETWORK RANK
                                </span>
                            </div>
                            <p class="text-lg sm:text-xl text-gray-600 dark:text-slate-200 mb-4">
                                {{ $user->userInfo->followers_count ?? '150' }} Followers</p>
                            <div
                                class="flex flex-col xs:flex-row items-center space-y-2 xs:space-y-0 xs:space-x-4 sm:space-x-6 text-gray-500 dark:text-slate-300 text-xs sm:text-sm">
                                <div class="flex items-center space-x-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                        stroke="#5c6b80   " stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <rect x="3" y="5" width="18" height="16" rx="2" />
                                        <path d="M16 3v4M8 3v4M3 9h18" />
                                    </svg>

                                    <span>MEMBER SINCE {{ $user->created_at->diffForHumans() }}</span>
                                </div>
                                <div class="flex items-center space-x-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="w-3 h-3 sm:w-4 sm:h-4">
                                        <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path>
                                        <circle cx="12" cy="10" r="3"></circle>
                                    </svg>
                                    <span>{{ $user->userInfo->city ?? 'City' }},
                                        {{ $user->userInfo->country ?? 'Country' }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div
                            class="flex flex-col sm:flex-row sm:items-center space-y-2 sm:space-y-0 sm:space-x-3 w-full sm:w-auto">
                            <a href="{{ $user->userInfo->soundcloud_permalink_url ?? '#' }}"
                                class="bg-gray-300 hover:bg-gray-400 dark:bg-slate-700 dark:hover:bg-slate-600 text-gray-900 dark:text-white px-3 py-1 sm:px-4 sm:py-2 rounded-lg transition-colors flex items-center justify-center space-x-2 text-sm sm:text-base">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="w-3 h-3 sm:w-4 sm:h-4">
                                    <path d="M15 3h6v6"></path>
                                    <path d="M10 14 21 3"></path>
                                    <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                                </svg>
                                <span>Visit on SoundCloud</span>
                            </a>
                            <button
                                class="bg-orange-500 hover:bg-orange-600 text-white px-3 py-1 sm:px-4 sm:py-2 rounded-lg transition-colors flex items-center justify-center space-x-2 text-sm sm:text-base">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="w-3 h-3 sm:w-4 sm:h-4">
                                    <path d="M12 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                    <path d="M18.375 2.625a2.121 2.121 0 1 1 3 3L12 15l-4 1 1-4Z"></path>
                                </svg>
                                <span>Edit</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-4 sm:p-6">
                <div class="grid grid-cols-1 lg:grid-cols-4 gap-4 sm:gap-6">
                    <!-- Sidebar -->
                    <div class="lg:col-span-1 space-y-4 sm:space-y-6">
                        <div
                            class="bg-gray-100 dark:bg-slate-800 rounded-lg p-4 sm:p-6 border border-gray-200 dark:border-slate-700">
                            <h3 class="text-gray-900 dark:text-white font-semibold mb-3 sm:mb-4">About</h3>
                            <p class="text-gray-600 dark:text-slate-300 text-xs sm:text-sm mb-3 sm:mb-4">
                                {{ $user->userInfo->description ?? 'description' }}
                            </p>
                            <p class="text-gray-500 dark:text-slate-400 text-xs">Bio from <span
                                    class="text-orange-500 dark:text-orange-400">SOUNDCLOUD</span></p>
                        </div>

                        <div
                            class="bg-gray-100 dark:bg-slate-800 rounded-lg p-4 sm:p-6 border border-gray-200 dark:border-slate-700">
                            <h3 class="text-gray-900 dark:text-white font-semibold mb-3 sm:mb-4">Genres</h3>
                            <div class="grid grid-cols-2 sm:grid-cols-1 gap-2">
                                <div class="bg-gray-200 dark:bg-slate-700 px-3 py-2 rounded-lg">
                                    <span class="text-gray-600 dark:text-slate-300 text-xs sm:text-sm">Hip-Hop</span>
                                </div>
                                <div class="bg-gray-200 dark:bg-slate-700 px-3 py-2 rounded-lg">
                                    <span class="text-gray-600 dark:text-slate-300 text-xs sm:text-sm">Pop</span>
                                </div>
                                <div class="bg-gray-200 dark:bg-slate-700 px-3 py-2 rounded-lg">
                                    <span class="text-gray-600 dark:text-slate-300 text-xs sm:text-sm">Electronic</span>
                                </div>
                            </div>
                        </div>

                        <div
                            class="bg-gray-100 dark:bg-slate-800 rounded-lg p-4 sm:p-6 border border-gray-200 dark:border-slate-700">
                            <h3 class="text-gray-900 dark:text-white font-semibold mb-3 sm:mb-4">Badges Awarded</h3>
                            <div class="grid grid-cols-2 gap-2 sm:gap-3">
                                <div class="bg-gray-200 dark:bg-slate-700 rounded-lg p-3 sm:p-4 text-center">
                                    <div class="text-xl sm:text-2xl mb-1 sm:mb-2">👥</div>
                                    <h4 class="text-gray-900 dark:text-white font-medium text-xs">Quality Followers</h4>
                                </div>
                                <div class="bg-gray-200 dark:bg-slate-700 rounded-lg p-3 sm:p-4 text-center">
                                    <div class="text-xl sm:text-2xl mb-1 sm:mb-2">⭐</div>
                                    <h4 class="text-gray-900 dark:text-white font-medium text-xs">Top Follower</h4>
                                </div>
                                <div class="bg-gray-200 dark:bg-slate-700 rounded-lg p-3 sm:p-4 text-center">
                                    <div class="text-xl sm:text-2xl mb-1 sm:mb-2">📅</div>
                                    <h4 class="text-gray-900 dark:text-white font-medium text-xs">Be Da Regular</h4>
                                </div>
                                <div class="bg-gray-200 dark:bg-slate-700 rounded-lg p-3 sm:p-4 text-center">
                                    <div class="text-xl sm:text-2xl mb-1 sm:mb-2">⚡</div>
                                    <h4 class="text-gray-900 dark:text-white font-medium text-xs">Power User</h4>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Main Content -->
                    <div class="lg:col-span-3 space-y-4 sm:space-y-6">
                        <div x-data="{ activeTab: @entangle('activeTab') }">
                            <!-- Tab Navigation -->
                            <div
                                class="flex overflow-x-auto pb-1 sm:pb-0 space-x-4 sm:space-x-8 border-b border-gray-200 dark:border-slate-700">
                                <button type="button"
                                    :class="{
                                        'text-orange-500 border-b-2 border-orange-500 dark:text-orange-400 dark:border-orange-400': activeTab === 'insights',
                                        'text-gray-500 border-transparent dark:text-slate-400': activeTab !== 'insights'
                                    }"
                                    @click="activeTab = 'insights'"
                                    class="tab-btn pb-3 sm:pb-4 px-1 text-xs sm:text-sm font-medium transition-colors">
                                    Insights
                                </button>
                                <button type="button"
                                    :class="{
                                        'text-orange-500 border-b-2 border-orange-500 dark:text-orange-400 dark:border-orange-400': activeTab === 'tracks',
                                        'text-gray-500 border-transparent dark:text-slate-400': activeTab !== 'tracks'
                                    }"
                                    @click="activeTab = 'tracks'"
                                    class="tab-btn pb-3 sm:pb-4 px-1 text-xs sm:text-sm font-medium transition-colors">
                                    Tracks
                                </button>
                                <button type="button"
                                    :class="{
                                        'text-orange-500 border-b-2 border-orange-500 dark:text-orange-400 dark:border-orange-400': activeTab === 'playlists',
                                        'text-gray-500 border-transparent dark:text-slate-400': activeTab !== 'playlists'
                                    }"
                                    @click="activeTab = 'playlists'"
                                    class="tab-btn pb-3 sm:pb-4 px-1 text-xs sm:text-sm font-medium transition-colors">
                                    Playlists
                                </button>
                                <button type="button"
                                    :class="{
                                        'text-orange-500 border-b-2 border-orange-500 dark:text-orange-400 dark:border-orange-400': activeTab === 'reposts',
                                        'text-gray-500 border-transparent dark:text-slate-400': activeTab !== 'reposts'
                                    }"
                                    @click="activeTab = 'reposts'"
                                    class="tab-btn pb-3 sm:pb-4 px-1 text-xs sm:text-sm font-medium transition-colors">
                                    Recent reposts
                                </button>
                                <button type="button"
                                    :class="{
                                        'text-orange-500 border-b-2 border-orange-500 dark:text-orange-400 dark:border-orange-400': activeTab === 'starred',
                                        'text-gray-500 border-transparent dark:text-slate-400': activeTab !== 'starred'
                                    }"
                                    @click="activeTab = 'starred'"
                                    class="tab-btn pb-3 sm:pb-4 px-1 text-xs sm:text-sm font-medium transition-colors">
                                    Starred members
                                </button>
                                <button type="button"
                                    :class="{
                                        'text-orange-500 border-b-2 border-orange-500 dark:text-orange-400 dark:border-orange-400': activeTab === 'transaction',
                                        'text-gray-500 border-transparent dark:text-slate-400': activeTab !== 'transaction'
                                    }"
                                    @click="activeTab = 'transaction'"
                                    class="tab-btn pb-3 sm:pb-4 px-1 text-xs sm:text-sm font-medium transition-colors">
                                    Transaction
                                </button>
                            </div>

                            <!-- Tab Panels -->
                            <div>
                                <!-- Credibility Insights -->
                                <div x-show="activeTab === 'insights'" class="tab-panel mt-4" x-transition>
                                    <div
                                        class="bg-gray-100 dark:bg-slate-800 rounded-lg p-4 sm:p-6 border border-gray-200 dark:border-slate-700">
                                        <div
                                            class="flex flex-col sm:flex-row sm:items-center justify-between mb-4 sm:mb-6">
                                            <h3
                                                class="text-gray-900 dark:text-white text-base sm:text-lg font-semibold mb-2 sm:mb-0">
                                                Credibility Insights</h3>
                                            <div class="flex items-center space-x-2">
                                                <div class="w-2 h-2 sm:w-3 sm:h-3 bg-green-500 rounded-full"></div>
                                                <span
                                                    class="text-green-600 dark:text-green-400 text-xs sm:text-sm font-medium">92%
                                                    Real Followers</span>
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-6">
                                            <div>
                                                <div class="flex items-center justify-between mb-1 sm:mb-2">
                                                    <span
                                                        class="text-gray-600 dark:text-slate-300 text-xs sm:text-sm">Follower
                                                        Growth</span>
                                                    <span
                                                        class="text-gray-900 dark:text-white font-semibold text-xs sm:text-sm">78%</span>
                                                </div>
                                                <div
                                                    class="w-full bg-gray-300 dark:bg-slate-700 rounded-full h-1 sm:h-2">
                                                    <div class="bg-orange-500 h-1 sm:h-2 rounded-full transition-all duration-300"
                                                        style="width: 78%;"></div>
                                                </div>
                                            </div>

                                            <div>
                                                <div class="flex items-center justify-between mb-1 sm:mb-2">
                                                    <span
                                                        class="text-gray-600 dark:text-slate-300 text-xs sm:text-sm">Repost
                                                        Efficiency</span>
                                                    <span
                                                        class="text-gray-900 dark:text-white font-semibold text-xs sm:text-sm">78%</span>
                                                </div>
                                                <div
                                                    class="w-full bg-gray-300 dark:bg-slate-700 rounded-full h-1 sm:h-2">
                                                    <div class="bg-green-500 h-1 sm:h-2 rounded-full transition-all duration-300"
                                                        style="width: 78%;"></div>
                                                </div>
                                                <p class="text-gray-500 dark:text-slate-400 text-xxs sm:text-xs mt-1">
                                                    Above
                                                    average
                                                    for your followers range</p>
                                            </div>

                                            <div>
                                                <div class="flex items-center justify-between mb-1 sm:mb-2">
                                                    <span
                                                        class="text-gray-600 dark:text-slate-300 text-xs sm:text-sm">Activity
                                                        Score</span>
                                                    <span
                                                        class="text-gray-900 dark:text-white font-semibold text-xs sm:text-sm">85%</span>
                                                </div>
                                                <div
                                                    class="w-full bg-gray-300 dark:bg-slate-700 rounded-full h-1 sm:h-2">
                                                    <div class="bg-blue-500 h-1 sm:h-2 rounded-full transition-all duration-300"
                                                        style="width: 85%;"></div>
                                                </div>
                                            </div>

                                            <div class="md:col-span-3 mt-3 sm:mt-4">
                                                <h4
                                                    class="text-gray-900 dark:text-white font-medium mb-2 sm:mb-3 text-sm sm:text-base">
                                                    Activity Score</h4>
                                                <div class="grid grid-cols-10 gap-1">
                                                    <div class="w-2 h-2 sm:w-3 sm:h-3 rounded-sm bg-orange-500"></div>
                                                    <div class="w-2 h-2 sm:w-3 sm:h-3 rounded-sm bg-orange-500"></div>
                                                    <div
                                                        class="w-2 h-2 sm:w-3 sm:h-3 rounded-sm bg-gray-300 dark:bg-slate-700">
                                                    </div>
                                                    <div class="w-2 h-2 sm:w-3 sm:h-3 rounded-sm bg-orange-600"></div>
                                                    <div class="w-2 h-2 sm:w-3 sm:h-3 rounded-sm bg-orange-500"></div>
                                                    <div
                                                        class="w-2 h-2 sm:w-3 sm:h-3 rounded-sm bg-gray-300 dark:bg-slate-700">
                                                    </div>
                                                    <div class="w-2 h-2 sm:w-3 sm:h-3 rounded-sm bg-orange-600"></div>
                                                    <div class="w-2 h-2 sm:w-3 sm:h-3 rounded-sm bg-orange-600"></div>
                                                    <div class="w-2 h-2 sm:w-3 sm:h-3 rounded-sm bg-orange-600"></div>
                                                    <div class="w-2 h-2 sm:w-3 sm:h-3 rounded-sm bg-orange-500"></div>
                                                    <!-- More activity dots... -->
                                                    <div class="w-2 h-2 sm:w-3 sm:h-3 rounded-sm bg-orange-600"></div>
                                                    <div
                                                        class="w-2 h-2 sm:w-3 sm:h-3 rounded-sm bg-gray-300 dark:bg-slate-700">
                                                    </div>
                                                    <div class="w-2 h-2 sm:w-3 sm:h-3 rounded-sm bg-orange-600"></div>
                                                    <div
                                                        class="w-2 h-2 sm:w-3 sm:h-3 rounded-sm bg-gray-300 dark:bg-slate-700">
                                                    </div>
                                                    <div class="w-2 h-2 sm:w-3 sm:h-3 rounded-sm bg-orange-600"></div>
                                                    <div class="w-2 h-2 sm:w-3 sm:h-3 rounded-sm bg-orange-600"></div>
                                                    <div class="w-2 h-2 sm:w-3 sm:h-3 rounded-sm bg-orange-600"></div>
                                                    <div class="w-2 h-2 sm:w-3 sm:h-3 rounded-sm bg-orange-600"></div>
                                                    <div
                                                        class="w-2 h-2 sm:w-3 sm:h-3 rounded-sm bg-gray-300 dark:bg-slate-700">
                                                    </div>
                                                    <div
                                                        class="w-2 h-2 sm:w-3 sm:h-3 rounded-sm bg-gray-300 dark:bg-slate-700">
                                                    </div>
                                                    <!-- Continue with remaining dots -->
                                                    <div
                                                        class="w-2 h-2 sm:w-3 sm:h-3 rounded-sm bg-gray-300 dark:bg-slate-700">
                                                    </div>
                                                    <div
                                                        class="w-2 h-2 sm:w-3 sm:h-3 rounded-sm bg-gray-300 dark:bg-slate-700">
                                                    </div>
                                                    <div
                                                        class="w-2 h-2 sm:w-3 sm:h-3 rounded-sm bg-gray-300 dark:bg-slate-700">
                                                    </div>
                                                    <div
                                                        class="w-2 h-2 sm:w-3 sm:h-3 rounded-sm bg-gray-300 dark:bg-slate-700">
                                                    </div>
                                                    <div
                                                        class="w-2 h-2 sm:w-3 sm:h-3 rounded-sm bg-gray-300 dark:bg-slate-700">
                                                    </div>
                                                    <div class="w-2 h-2 sm:w-3 sm:h-3 rounded-sm bg-orange-500"></div>
                                                    <div class="w-2 h-2 sm:w-3 sm:h-3 rounded-sm bg-orange-500"></div>
                                                    <div
                                                        class="w-2 h-2 sm:w-3 sm:h-3 rounded-sm bg-gray-300 dark:bg-slate-700">
                                                    </div>
                                                    <div
                                                        class="w-2 h-2 sm:w-3 sm:h-3 rounded-sm bg-gray-300 dark:bg-slate-700">
                                                    </div>
                                                    <div class="w-2 h-2 sm:w-3 sm:h-3 rounded-sm bg-orange-600"></div>
                                                    <!-- Final row -->
                                                    <div
                                                        class="w-2 h-2 sm:w-3 sm:h-3 rounded-sm bg-gray-300 dark:bg-slate-700">
                                                    </div>
                                                    <div
                                                        class="w-2 h-2 sm:w-3 sm:h-3 rounded-sm bg-gray-300 dark:bg-slate-700">
                                                    </div>
                                                    <div class="w-2 h-2 sm:w-3 sm:h-3 rounded-sm bg-orange-600"></div>
                                                    <div class="w-2 h-2 sm:w-3 sm:h-3 rounded-sm bg-orange-600"></div>
                                                    <div class="w-2 h-2 sm:w-3 sm:h-3 rounded-sm bg-orange-500"></div>
                                                    <div
                                                        class="w-2 h-2 sm:w-3 sm:h-3 rounded-sm bg-gray-300 dark:bg-slate-700">
                                                    </div>
                                                    <div class="w-2 h-2 sm:w-3 sm:h-3 rounded-sm bg-orange-600"></div>
                                                    <div class="w-2 h-2 sm:w-3 sm:h-3 rounded-sm bg-orange-500"></div>
                                                    <div class="w-2 h-2 sm:w-3 sm:h-3 rounded-sm bg-orange-500"></div>
                                                    <div class="w-2 h-2 sm:w-3 sm:h-3 rounded-sm bg-orange-500"></div>
                                                    <!-- Last 10 -->
                                                    <div
                                                        class="w-2 h-2 sm:w-3 sm:h-3 rounded-sm bg-gray-300 dark:bg-slate-700">
                                                    </div>
                                                    <div class="w-2 h-2 sm:w-3 sm:h-3 rounded-sm bg-orange-600"></div>
                                                    <div
                                                        class="w-2 h-2 sm:w-3 sm:h-3 rounded-sm bg-gray-300 dark:bg-slate-700">
                                                    </div>
                                                    <div class="w-2 h-2 sm:w-3 sm:h-3 rounded-sm bg-orange-500"></div>
                                                    <div
                                                        class="w-2 h-2 sm:w-3 sm:h-3 rounded-sm bg-gray-300 dark:bg-slate-700">
                                                    </div>
                                                    <div class="w-2 h-2 sm:w-3 sm:h-3 rounded-sm bg-orange-600"></div>
                                                    <div class="w-2 h-2 sm:w-3 sm:h-3 rounded-sm bg-orange-600"></div>
                                                    <div class="w-2 h-2 sm:w-3 sm:h-3 rounded-sm bg-orange-600"></div>
                                                    <div class="w-2 h-2 sm:w-3 sm:h-3 rounded-sm bg-orange-600"></div>
                                                    <div
                                                        class="w-2 h-2 sm:w-3 sm:h-3 rounded-sm bg-gray-300 dark:bg-slate-700">
                                                    </div>
                                                </div>
                                                <div
                                                    class="flex items-center justify-between text-xxs sm:text-xs text-gray-500 dark:text-slate-400 mt-1 sm:mt-2">
                                                    <span>Last 5 weeks</span>
                                                    <div class="flex items-center space-x-1 sm:space-x-2">
                                                        <span>Now</span>
                                                        <div class="flex items-center space-x-1">
                                                            <span class="hidden sm:inline">Low</span>
                                                            <div
                                                                class="w-1 h-1 sm:w-2 sm:h-2 bg-gray-300 dark:bg-slate-700 rounded-sm">
                                                            </div>
                                                            <div
                                                                class="w-1 h-1 sm:w-2 sm:h-2 bg-orange-600 rounded-sm">
                                                            </div>
                                                            <div
                                                                class="w-1 h-1 sm:w-2 sm:h-2 bg-orange-500 rounded-sm">
                                                            </div>
                                                            <span class="hidden sm:inline">High</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Tracks Tab -->
                                <div class="tab-panel mt-4" x-show="activeTab === 'tracks'" x-transition>

                                    <div
                                        class="flex flex-col sm:flex-row sm:items-center justify-between mb-4 sm:mb-6">
                                        <h2
                                            class="text-gray-900 dark:text-white text-lg sm:text-xl font-semibold mb-2 sm:mb-0">
                                            New & popular tracks from <span
                                                class="text-orange-500 dark:text-orange-400 hover:text-orange-400/90 dark:hover:text-orange-300">{{ user()->name }}</span>
                                        </h2>
                                        <a href="#"
                                            class="text-orange-500 dark:text-orange-400 hover:text-orange-400/90 dark:hover:text-orange-300 text-xs sm:text-sm flex items-center space-x-1">
                                            <span>Show all tracks on Soundcloud</span>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-external-link w-4 h-4">
                                                <path d="M15 3h6v6"></path>
                                                <path d="M10 14 21 3"></path>
                                                <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6">
                                                </path>
                                            </svg>
                                        </a>
                                    </div>

                                    <div class="space-y-3 sm:space-y-4">
                                        <!-- Track Card 1 -->
                                        @foreach ($tracks as $track)
                                            <div
                                                class="bg-gray-100 dark:bg-slate-800 rounded-lg p-3 sm:p-4 border border-gray-200 dark:border-slate-700">
                                                <div id="soundcloud-player-{{ $track->id }}"
                                                        data-campaign-id="{{ $track->id }}" wire:ignore>
                                                        <x-sound-cloud.sound-cloud-player :track="$track"
                                                             :visual="false" />
                                                    </div>
                                            </div>
                                        @endforeach
                                        <!-- Track Card 2 -->
                                        {{-- <div
                                            class="bg-gray-100 dark:bg-slate-800 rounded-lg p-3 sm:p-4 border border-gray-200 dark:border-slate-700">
                                            <div
                                                class="flex flex-col xs:flex-row xs:items-center space-y-3 xs:space-y-0 xs:space-x-3 sm:space-x-4">
                                                <div class="relative flex-shrink-0 mx-auto xs:mx-0">
                                                    <img src="https://images.pexels.com/photos/1763075/pexels-photo-1763075.jpeg?auto=compress&cs=tinysrgb&w=300&h=300&fit=crop"
                                                        alt="Drop - To - Me" class="w-16 h-16 rounded-lg">
                                                    <button
                                                        class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-50 rounded-lg opacity-0 hover:opacity-100 transition-opacity">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                            height="24" fill="currentColor" stroke="currentColor"
                                                            stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            class="w-6 h-6 text-orange-500 dark:text-orange-400">
                                                            <polygon points="5 3 19 12 5 21 5 3"></polygon>
                                                        </svg>
                                                    </button>
                                                </div>
                                                <div class="flex-1 min-w-0 ml-0 sm:ml-4">
                                                    <h4
                                                        class="text-gray-900 dark:text-white font-medium mb-1 truncate">
                                                        Drop - To - Me</h4>
                                                    <p
                                                        class="text-gray-500 dark:text-slate-400 text-xs sm:text-sm mb-2">
                                                        Bhathiya Udara</p>
                                                    <div class="flex items-center space-x-1 h-6 sm:h-8 mb-2">
                                                        <div class="bg-gray-300 dark:bg-slate-600 flex-1"
                                                            style="height: 16px; border-radius: 1px;"></div>
                                                        <div class="bg-gray-300 dark:bg-slate-600 flex-1"
                                                            style="height: 21px; border-radius: 1px;"></div>
                                                        <div class="bg-gray-300 dark:bg-slate-600 flex-1"
                                                            style="height: 10px; border-radius: 1px;"></div>
                                                        <div class="bg-gray-300 dark:bg-slate-600 flex-1"
                                                            style="height: 9px; border-radius: 1px;"></div>
                                                    </div>
                                                </div>
                                                <div
                                                    class="flex items-center justify-between xs:justify-end space-x-2 sm:space-x-4">
                                                    <button
                                                        class="text-gray-500 dark:text-slate-400 hover:text-orange-500 dark:hover:text-orange-400">
                                                        <span class="text-xs sm:text-sm">♡ 187</span>
                                                    </button>
                                                    <button
                                                        class="text-gray-500 dark:text-slate-400 hover:text-orange-500 dark:hover:text-orange-400">
                                                        <span class="text-xs sm:text-sm">🔄 68</span>
                                                    </button>
                                                    <button
                                                        class="text-orange-500 dark:text-orange-400 hover:text-orange-400 dark:hover:text-orange-500 text-xs sm:text-sm whitespace-nowrap">Share</button>
                                                </div>
                                            </div>
                                        </div> --}}
                                    </div>
                                </div>
                                <!-- Playlists Tab -->
                                <div class="tab-panel mt-4" x-show="activeTab === 'playlists'" x-transition>
                                    <div
                                        class="flex flex-col sm:flex-row sm:items-center justify-between mb-4 sm:mb-6">
                                        <h2
                                            class="text-gray-900 dark:text-white text-lg sm:text-xl font-semibold mb-2 sm:mb-0">
                                            New & popular playlists from <span
                                                class="text-orange-500 dark:text-orange-400 hover:text-orange-400/90 dark:hover:text-orange-300">{{ user()->name }}</span>
                                        </h2>
                                        <a href="#"
                                            class="text-orange-500 dark:text-orange-400 hover:text-orange-400/90 dark:hover:text-orange-300 text-xs sm:text-sm flex items-center space-x-1">
                                            <span>Show all tracks on Soundcloud</span>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-external-link w-4 h-4">
                                                <path d="M15 3h6v6"></path>
                                                <path d="M10 14 21 3"></path>
                                                <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6">
                                                </path>
                                            </svg>
                                        </a>
                                    </div>

                                </div>
                                <!-- Reposts Tab -->
                                <div class="tab-panel mt-4" x-show="activeTab === 'reposts'" x-transition>
                                    <div
                                        class="text-gray-900 dark:text-white py-4 sm:py-6 text-center text-base sm:text-lg">
                                        No recent reposts available yet.</div>
                                </div>
                                <!-- Starred Tab -->
                                <div class="tab-panel mt-4" x-show="activeTab === 'starred'" x-transition>
                                    <div
                                        class="text-gray-900 dark:text-white py-4 sm:py-6 text-center text-base sm:text-lg">
                                        No starred members found.</div>
                                </div>

                                <!-- Transaction Tab -->
                                <div class="tab-panel mt-4" x-show="activeTab === 'transaction'" x-transition>
                                    <div class="w-full overflow-x-auto">
                                        <table
                                            class="min-w-[900px] w-full table-fixed text-sm text-left divide-y divide-gray-200 dark:divide-gray-700">
                                            <thead
                                                class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-white text-xs sm:text-sm">
                                                <tr>
                                                    <th class="w-10 px-2 py-3">ID</th>
                                                    <th class="w-28 px-2 py-3">Reciver</th>
                                                    <th class="w-20 px-2 py-3">Amount</th>
                                                    <th class="w-20 px-2 py-3">Credit</th>
                                                    <th class="w-24 px-2 py-3">Type</th>
                                                    {{-- <th class="w-28 px-2 py-3">Metadata</th> --}}
                                                    <th class="w-20 px-2 py-3">Status</th>
                                                </tr>
                                            </thead>
                                            @foreach ($transactions as $transaction )
                                                
                                           
                                            <tbody class="bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-200">
                                                <tr class="border-b border-gray-200 dark:border-gray-700">
                                                    <td class="px-2 py-2">{{ $transaction->id}}</td>
                                                    <td class="px-2 py-2">{{ $transaction->receiver_urn}}</td>
                                                    <td class="px-2 py-2">{{ $transaction->amount}}</td>
                                                    <td class="px-2 py-2">{{ $transaction->credits}}</td>
                                                    <td class="px-2 py-2">{{ $transaction->transaction_type}}</td>
                                                    {{-- <td class="px-2 py-2">{{ $transaction->metadata}}</td> --}}
                                                    <td class="px-2 py-2 text-green-600 font-semibold">{{ $transaction->status}}</td>
                                                </tr>
                                               
                                            </tbody>
                                             @endforeach
                                        </table>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </section>

    {{-- TAB FUNCTIONALITY --}}
    @push('js')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Initialize SoundCloud Widget API integration with Livewire
                function initializeSoundCloudWidgets() {
                    if (typeof SC === 'undefined') {
                        setTimeout(initializeSoundCloudWidgets, 500);
                        return;
                    }

                    const playerContainers = document.querySelectorAll('[id^="soundcloud-player-"]');

                    playerContainers.forEach(container => {
                        const campaignId = container.dataset.campaignId;
                        const iframe = container.querySelector('iframe');

                        if (iframe && campaignId) {
                            const widget = SC.Widget(iframe);

                            // Track play events and call Livewire methods
                            widget.bind(SC.Widget.Events.PLAY, () => {
                                @this.call('handleAudioPlay', campaignId);
                            });

                            widget.bind(SC.Widget.Events.PAUSE, () => {
                                @this.call('handleAudioPause', campaignId);
                            });

                            widget.bind(SC.Widget.Events.FINISH, () => {
                                @this.call('handleAudioEnded', campaignId);
                            });

                            // Track position updates
                            widget.bind(SC.Widget.Events.PLAY_PROGRESS, (data) => {
                                const currentTime = data.currentPosition / 1000;
                                @this.call('handleAudioTimeUpdate', campaignId, currentTime);
                            });
                        }
                    });
                }

                // Initialize widgets
                initializeSoundCloudWidgets();
            });
        </script>
    @endpush
</section>
