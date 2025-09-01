<div>
    <x-slot name="page_slug">chart</x-slot>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="py-8">
        <div class=" mx-auto px-4 sm:px-6 lg:px-8">
            <div
                class="bg-gradient-to-r dark:bg-gray-900 dark:to-gray-800/20  text-white p-6 sm:p-8 rounded-2xl mb-8 border border-gray-800">
                <div class=" mx-auto">
                    <!-- Header -->
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                        <div class="flex items-start sm:items-center gap-3">
                            <div class="bg-orange-500 p-3 rounded-xl"><svg xmlns="http://www.w3.org/2000/svg"
                                    width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-trophy w-8 h-8">
                                    <path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"></path>
                                    <path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"></path>
                                    <path d="M4 22h16"></path>
                                    <path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20.24 7 22"></path>
                                    <path d="M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20.24 17 22"></path>
                                    <path d="M18 2H6v7a6 6 0 0 0 12 0V2Z"></path>
                                </svg></div>
                            <div>
                                <h1
                                    class="text-2xl text-gray-900 dark:text-white sm:text-3xl md:text-4xl font-bold mb-1 sm:mb-2 ">
                                    Weekly Top 20 Chart
                                </h1>
                                <div
                                    class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-4 text-gray-400 text-sm sm:text-base">
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
                                        <span>Week of January 13, 2025</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="lucide lucide-clock w-4 h-4">
                                            <circle cx="12" cy="12" r="10"></circle>
                                            <polyline points="12 6 12 12 16 14"></polyline>
                                        </svg>
                                        <span>Updated 1/13/2025</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Buttons -->
                        <div class="flex flex-wrap gap-2 sm:gap-3 w-full sm:w-auto">
                            <button
                                class="flex items-center justify-center text-gray-400 gap-2 w-full sm:w-auto hover:bg-gray-700 px-4 py-2 rounded-xl transition-all duration-200 font-medium border border-gray-700">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="lucide lucide-refresh-cw w-4 h-4">
                                    <path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"></path>
                                    <path d="M21 3v5h-5"></path>
                                    <path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16"></path>
                                    <path d="M8 16H3v5"></path>
                                </svg>
                                <span class="hidden sm:inline dark:text-white text-gray-900">Refresh</span>
                            </button>
                            <button
                                class="flex items-center justify-center gap-2 w-full sm:w-auto bg-orange-500 hover:bg-orange-600 px-4 py-2 rounded-xl transition-all duration-200 font-medium">
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

                    <!-- Stats Grid -->
                    <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 dark:bg-gray-800 bg-white">
                        <div
                            class=" text-gray-600 bg-opacity-50 backdrop-blur-sm p-4 rounded-xl border border-gray-700 text-center sm:text-left">
                            <div class="text-2xl font-bold dark:text-white">20</div>
                            <div class="dark:text-gray-300 text-sm">Top Tracks</div>
                        </div>
                        <div
                            class=" text-gray-600 bg-opacity-50 backdrop-blur-sm p-4 rounded-xl border border-gray-700 text-center sm:text-left">
                            <div class="text-2xl font-bold dark:text-white">Weekly</div>
                            <div class="dark:text-gray-300 text-sm">Updates</div>
                        </div>
                        <div
                            class=" text-gray-600 bg-opacity-50 backdrop-blur-sm p-4 rounded-xl border border-gray-700 text-center sm:text-left">
                            <div class="text-2xl font-bold dark:text-white">Live</div>
                            <div class="dark:text-gray-300 text-sm">Engagement</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-4">
                    <h2 class="text-xl font-bold text-dark dark:text-white">20 Tracks</h2>
                </div>
                <div class="flex items-center gap-1 dark:bg-gray-800 rounded-xl p-1 border border-gray-700">
                    <button
                        class="flex items-center gap-2 px-3 py-2 rounded-lg transition-all duration-200 text-gray-400 hover:text-white hover:bg-gray-700"
                        title="List View"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-list w-4 h-4">
                            <line x1="8" x2="21" y1="6" y2="6"></line>
                            <line x1="8" x2="21" y1="12" y2="12"></line>
                            <line x1="8" x2="21" y1="18" y2="18"></line>
                            <line x1="3" x2="3.01" y1="6" y2="6"></line>
                            <line x1="3" x2="3.01" y1="12" y2="12"></line>
                            <line x1="3" x2="3.01" y1="18" y2="18"></line>
                        </svg><span class="hidden sm:inline text-sm font-medium">List View</span></button><button
                        class="flex items-center gap-2 px-3 py-2 rounded-lg transition-all duration-200 text-gray-400 hover:text-white hover:bg-gray-700"
                        title="Grid View"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-grid3x3 w-4 h-4">
                            <rect width="18" height="18" x="3" y="3" rx="2"></rect>
                            <path d="M3 9h18"></path>
                            <path d="M3 15h18"></path>
                            <path d="M9 3v18"></path>
                            <path d="M15 3v18"></path>
                        </svg><span class="hidden sm:inline text-sm font-medium">Grid View</span></button><button
                        class="flex items-center gap-2 px-3 py-2 rounded-lg transition-all duration-200 bg-orange-500 text-white shadow-lg"
                        title="Compact View"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-bar-chart3 w-4 h-4">
                            <path d="M3 3v18h18"></path>
                            <path d="M18 17V9"></path>
                            <path d="M13 17V5"></path>
                            <path d="M8 17v-3"></path>
                        </svg><span class="hidden sm:inline text-sm font-medium">Compact View</span></button>
                </div>
            </div>
            <div class="transition-all duration-500 opacity-100 scale-100">
                <div class="dark:bg-gray-800 rounded-2xl border dark:border-gray-700 overflow-hidden">
                    <!-- Header - Hidden on mobile, replaced with a simpler version -->
                    <div
                        class="hidden md:grid grid-cols-12 gap-4 p-4 dark:bg-gray-700 text-sm font-semibold text-gray-600 dark:text-gray-400 border-b dark:border-gray-600">
                        <div class="col-span-1">#</div>
                        <div class="col-span-4">Track</div>
                        <div class="col-span-2 text-center">Score</div>
                        <div class="col-span-2 text-center">Reach</div>
                        <div class="col-span-2 text-center">Reposts</div>
                        <div class="col-span-1 text-center">Actions</div>
                    </div>

                    <!-- Mobile Header -->
                    <div class="md:hidden bg-gray-700 p-3 border-b border-gray-600">
                        <h2 class="text-white font-semibold text-center">Top Tracks</h2>
                    </div>

                    <!-- Track 1 -->
                    <div
                        class="grid grid-cols-12 gap-4 p-4 hover:bg-gray-700 transition-colors duration-200 border-b border-gray-700">
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
                        <div class="col-span-4 md:col-span-4 flex items-center gap-3">
                            <div class="relative group cursor-pointer">
                                <img src="https://images.pexels.com/photos/1763075/pexels-photo-1763075.jpeg?auto=compress&amp;cs=tinysrgb&amp;w=300"
                                    alt="Midnight Dreams"
                                    class="w-12 h-12 rounded-lg object-cover transition-transform duration-300 group-hover:scale-105">
                                <div
                                    class="absolute inset-0  bg-opacity-0 group-hover:bg-opacity-30 rounded-lg transition-all duration-300 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-external-link w-3 h-3 text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                        <path d="M15 3h6v6"></path>
                                        <path d="M10 14 21 3"></path>
                                        <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="min-w-0 flex-1">
                                <h3
                                    class="font-semibold text-white truncate cursor-pointer hover:text-orange-400 transition-colors">
                                    Midnight Dreams</h3>
                                <p class="text-sm text-gray-400 truncate">Luna Waves</p>
                            </div>
                        </div>
                        <div
                            class="col-span-2 md:col-span-2 flex items-center justify-center cursor-pointer hover:text-orange-400 transition-colors">
                            <span class="font-bold text-orange-400">10/10</span>
                        </div>
                        <div class="hidden md:col-span-2 md:flex md:items-center md:justify-center">
                            <span class="text-gray-400">3.2K</span>
                        </div>
                        <div class="hidden md:col-span-2 md:flex md:items-center md:justify-center">
                            <span class="text-gray-400">1.3K</span>
                        </div>
                        <div class="col-span-5 md:col-span-1 flex items-center justify-center">
                            <div class="flex items-center gap-1">
                                <button
                                    class="flex items-center justify-center w-8 h-8 rounded-full transition-all duration-200 bg-gray-700 text-gray-300 hover:bg-orange-500 hover:text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-play w-3 h-3 ml-0.5">
                                        <polygon points="5 3 19 12 5 21 5 3"></polygon>
                                    </svg>
                                </button>
                                <button
                                    class="flex items-center justify-center w-8 h-8 rounded-full transition-all duration-200 bg-gray-700 text-gray-300 hover:bg-red-500 hover:text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-heart w-3 h-3 ">
                                        <path
                                            d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z">
                                        </path>
                                    </svg>
                                </button>
                                <button
                                    class="flex items-center justify-center w-8 h-8 rounded-full transition-all duration-200 bg-gray-700 text-gray-300 hover:bg-green-500 hover:text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-rotate-ccw w-3 h-3">
                                        <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"></path>
                                        <path d="M3 3v5h5"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <!-- Mobile Stats -->
                        <div class="md:hidden col-span-12 pt-2 flex justify-between text-xs text-gray-400">
                            <span>Reach: 3.2K</span>
                            <span>Reposts: 1.3K</span>
                        </div>
                    </div>

                    <!-- Track 2 -->
                    <div
                        class="grid grid-cols-12 gap-4 p-4 hover:bg-gray-700 transition-colors duration-200 border-b border-gray-700">
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
                        <div class="col-span-4 md:col-span-4 flex items-center gap-3">
                            <div class="relative group cursor-pointer">
                                <img src="https://images.pexels.com/photos/1190297/pexels-photo-1190297.jpeg?auto=compress&amp;cs=tinysrgb&amp;w=300"
                                    alt="Urban Pulse"
                                    class="w-12 h-12 rounded-lg object-cover transition-transform duration-300 group-hover:scale-105">
                                <div
                                    class="absolute inset-0  bg-opacity-0 group-hover:bg-opacity-30 rounded-lg transition-all duration-300 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-external-link w-3 h-3 text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                        <path d="M15 3h6v6"></path>
                                        <path d="M10 14 21 3"></path>
                                        <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="min-w-0 flex-1">
                                <h3
                                    class="font-semibold text-white truncate cursor-pointer hover:text-orange-400 transition-colors">
                                    Urban Pulse</h3>
                                <p class="text-sm text-gray-400 truncate">Metro Vibes</p>
                            </div>
                        </div>
                        <div
                            class="col-span-2 md:col-span-2 flex items-center justify-center cursor-pointer hover:text-orange-400 transition-colors">
                            <span class="font-bold text-orange-400">9.4/10</span>
                        </div>
                        <div class="hidden md:col-span-2 md:flex md:items-center md:justify-center">
                            <span class="text-gray-400">2.9K</span>
                        </div>
                        <div class="hidden md:col-span-2 md:flex md:items-center md:justify-center">
                            <span class="text-gray-400">1.2K</span>
                        </div>
                        <div class="col-span-5 md:col-span-1 flex items-center justify-center">
                            <div class="flex items-center gap-1">
                                <button
                                    class="flex items-center justify-center w-8 h-8 rounded-full transition-all duration-200 bg-gray-700 text-gray-300 hover:bg-orange-500 hover:text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-play w-3 h-3 ml-0.5">
                                        <polygon points="5 3 19 12 5 21 5 3"></polygon>
                                    </svg>
                                </button>
                                <button
                                    class="flex items-center justify-center w-8 h-8 rounded-full transition-all duration-200 bg-red-500 text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-heart w-3 h-3 fill-current">
                                        <path
                                            d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z">
                                        </path>
                                    </svg>
                                </button>
                                <button
                                    class="flex items-center justify-center w-8 h-8 rounded-full transition-all duration-200 bg-gray-700 text-gray-300 hover:bg-green-500 hover:text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-rotate-ccw w-3 h-3">
                                        <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"></path>
                                        <path d="M3 3v5h5"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <!-- Mobile Stats -->
                        <div class="md:hidden col-span-12 pt-2 flex justify-between text-xs text-gray-400">
                            <span>Reach: 2.9K</span>
                            <span>Reposts: 1.2K</span>
                        </div>
                    </div>

                    <!-- Track 3 -->
                    <div
                        class="grid grid-cols-12 gap-4 p-4 hover:bg-gray-700 transition-colors duration-200 border-b border-gray-700">
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
                        <div class="col-span-4 md:col-span-4 flex items-center gap-3">
                            <div class="relative group cursor-pointer">
                                <img src="https://images.pexels.com/photos/2102587/pexels-photo-2102587.jpeg?auto=compress&amp;cs=tinysrgb&amp;w=300"
                                    alt="Cosmic Flow"
                                    class="w-12 h-12 rounded-lg object-cover transition-transform duration-300 group-hover:scale-105">
                                <div
                                    class="absolute inset-0  bg-opacity-0 group-hover:bg-opacity-30 rounded-lg transition-all duration-300 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-external-link w-3 h-3 text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                        <path d="M15 3h6v6"></path>
                                        <path d="M10 14 21 3"></path>
                                        <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="min-w-0 flex-1">
                                <h3
                                    class="font-semibold text-white truncate cursor-pointer hover:text-orange-400 transition-colors">
                                    Cosmic Flow</h3>
                                <p class="text-sm text-gray-400 truncate">Stellar Sound</p>
                            </div>
                        </div>
                        <div
                            class="col-span-2 md:col-span-2 flex items-center justify-center cursor-pointer hover:text-orange-400 transition-colors">
                            <span class="font-bold text-orange-400">9.1/10</span>
                        </div>
                        <div class="hidden md:col-span-2 md:flex md:items-center md:justify-center">
                            <span class="text-gray-400">2.6K</span>
                        </div>
                        <div class="hidden md:col-span-2 md:flex md:items-center md:justify-center">
                            <span class="text-gray-400">1.1K</span>
                        </div>
                        <div class="col-span-5 md:col-span-1 flex items-center justify-center">
                            <div class="flex items-center gap-1">
                                <button
                                    class="flex items-center justify-center w-8 h-8 rounded-full transition-all duration-200 bg-gray-700 text-gray-300 hover:bg-orange-500 hover:text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-play w-3 h-3 ml-0.5">
                                        <polygon points="5 3 19 12 5 21 5 3"></polygon>
                                    </svg>
                                </button>
                                <button
                                    class="flex items-center justify-center w-8 h-8 rounded-full transition-all duration-200 bg-gray-700 text-gray-300 hover:bg-red-500 hover:text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-heart w-3 h-3 ">
                                        <path
                                            d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z">
                                        </path>
                                    </svg>
                                </button>
                                <button
                                    class="flex items-center justify-center w-8 h-8 rounded-full transition-all duration-200 bg-green-500 text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-rotate-ccw w-3 h-3">
                                        <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"></path>
                                        <path d="M3 3v5h5"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <!-- Mobile Stats -->
                        <div class="md:hidden col-span-12 pt-2 flex justify-between text-xs text-gray-400">
                            <span>Reach: 2.6K</span>
                            <span>Reposts: 1.1K</span>
                        </div>
                    </div>

                    <!-- Track 4 -->
                    <div
                        class="grid grid-cols-12 gap-4 p-4 hover:bg-gray-700 transition-colors duration-200 border-b border-gray-700">
                        <div class="col-span-1 flex items-center">
                            <div
                                class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold bg-gray-700 text-gray-300">
                                4</div>
                        </div>
                        <div class="col-span-4 md:col-span-4 flex items-center gap-3">
                            <div class="relative group cursor-pointer">
                                <img src="https://images.pexels.com/photos/1629236/pexels-photo-1629236.jpeg?auto=compress&amp;cs=tinysrgb&amp;w=300"
                                    alt="Neon Nights"
                                    class="w-12 h-12 rounded-lg object-cover transition-transform duration-300 group-hover:scale-105">
                                <div
                                    class="absolute inset-0  bg-opacity-0 group-hover:bg-opacity-30 rounded-lg transition-all duration-300 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-external-link w-3 h-3 text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                        <path d="M15 3h6v6"></path>
                                        <path d="M10 14 21 3"></path>
                                        <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="min-w-0 flex-1">
                                <h3
                                    class="font-semibold text-white truncate cursor-pointer hover:text-orange-400 transition-colors">
                                    Neon Nights</h3>
                                <p class="text-sm text-gray-400 truncate">Synth Paradise</p>
                            </div>
                        </div>
                        <div
                            class="col-span-2 md:col-span-2 flex items-center justify-center cursor-pointer hover:text-orange-400 transition-colors">
                            <span class="font-bold text-orange-400">8.3/10</span>
                        </div>
                        <div class="hidden md:col-span-2 md:flex md:items-center md:justify-center">
                            <span class="text-gray-400">2.3K</span>
                        </div>
                        <div class="hidden md:col-span-2 md:flex md:items-center md:justify-center">
                            <span class="text-gray-400">920</span>
                        </div>
                        <div class="col-span-5 md:col-span-1 flex items-center justify-center">
                            <div class="flex items-center gap-1">
                                <button
                                    class="flex items-center justify-center w-8 h-8 rounded-full transition-all duration-200 bg-gray-700 text-gray-300 hover:bg-orange-500 hover:text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-play w-3 h-3 ml-0.5">
                                        <polygon points="5 3 19 12 5 21 5 3"></polygon>
                                    </svg>
                                </button>
                                <button
                                    class="flex items-center justify-center w-8 h-8 rounded-full transition-all duration-200 bg-gray-700 text-gray-300 hover:bg-red-500 hover:text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-heart w-3 h-3 ">
                                        <path
                                            d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z">
                                        </path>
                                    </svg>
                                </button>
                                <button
                                    class="flex items-center justify-center w-8 h-8 rounded-full transition-all duration-200 bg-gray-700 text-gray-300 hover:bg-green-500 hover:text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-rotate-ccw w-3 h-3">
                                        <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"></path>
                                        <path d="M3 3v5h5"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <!-- Mobile Stats -->
                        <div class="md:hidden col-span-12 pt-2 flex justify-between text-xs text-gray-400">
                            <span>Reach: 2.3K</span>
                            <span>Reposts: 920</span>
                        </div>
                    </div>

                    <!-- Track 5 -->
                    <div
                        class="grid grid-cols-12 gap-4 p-4 hover:bg-gray-700 transition-colors duration-200 border-b border-gray-700">
                        <div class="col-span-1 flex items-center">
                            <div
                                class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold bg-gray-700 text-gray-300">
                                5</div>
                        </div>
                        <div class="col-span-4 md:col-span-4 flex items-center gap-3">
                            <div class="relative group cursor-pointer">
                                <img src="https://images.pexels.com/photos/1671325/pexels-photo-1671325.jpeg?auto=compress&amp;cs=tinysrgb&amp;w=300"
                                    alt="Ocean Breeze"
                                    class="w-12 h-12 rounded-lg object-cover transition-transform duration-300 group-hover:scale-105">
                                <div
                                    class="absolute inset-0  bg-opacity-0 group-hover:bg-opacity-30 rounded-lg transition-all duration-300 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-external-link w-3 h-3 text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                        <path d="M15 3h6v6"></path>
                                        <path d="M10 14 21 3"></path>
                                        <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="min-w-0 flex-1">
                                <h3
                                    class="font-semibold text-white truncate cursor-pointer hover:text-orange-400 transition-colors">
                                    Ocean Breeze</h3>
                                <p class="text-sm text-gray-400 truncate">Coastal Harmony</p>
                            </div>
                        </div>
                        <div
                            class="col-span-2 md:col-span-2 flex items-center justify-center cursor-pointer hover:text-orange-400 transition-colors">
                            <span class="font-bold text-orange-400">8/10</span>
                        </div>
                        <div class="hidden md:col-span-2 md:flex md:items-center md:justify-center">
                            <span class="text-gray-400">2.1K</span>
                        </div>
                        <div class="hidden md:col-span-2 md:flex md:items-center md:justify-center">
                            <span class="text-gray-400">850</span>
                        </div>
                        <div class="col-span-5 md:col-span-1 flex items-center justify-center">
                            <div class="flex items-center gap-1">
                                <button
                                    class="flex items-center justify-center w-8 h-8 rounded-full transition-all duration-200 bg-gray-700 text-gray-300 hover:bg-orange-500 hover:text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-play w-3 h-3 ml-0.5">
                                        <polygon points="5 3 19 12 5 21 5 3"></polygon>
                                    </svg>
                                </button>
                                <button
                                    class="flex items-center justify-center w-8 h-8 rounded-full transition-all duration-200 bg-red-500 text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-heart w-3 h-3 fill-current">
                                        <path
                                            d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z">
                                        </path>
                                    </svg>
                                </button>
                                <button
                                    class="flex items-center justify-center w-8 h-8 rounded-full transition-all duration-200 bg-green-500 text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-rotate-ccw w-3 h-3">
                                        <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"></path>
                                        <path d="M3 3v5h5"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <!-- Mobile Stats -->
                        <div class="md:hidden col-span-12 pt-2 flex justify-between text-xs text-gray-400">
                            <span>Reach: 2.1K</span>
                            <span>Reposts: 850</span>
                        </div>
                    </div>

                    <!-- Track 6 -->
                    <div
                        class="grid grid-cols-12 gap-4 p-4 hover:bg-gray-700 transition-colors duration-200 border-b border-gray-700">
                        <div class="col-span-1 flex items-center">
                            <div
                                class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold bg-gray-700 text-gray-300">
                                6</div>
                        </div>
                        <div class="col-span-4 md:col-span-4 flex items-center gap-3">
                            <div class="relative group cursor-pointer">
                                <img src="https://images.pexels.com/photos/1105666/pexels-photo-1105666.jpeg?auto=compress&amp;cs=tinysrgb&amp;w=300"
                                    alt="Digital Horizon"
                                    class="w-12 h-12 rounded-lg object-cover transition-transform duration-300 group-hover:scale-105">
                                <div
                                    class="absolute inset-0  bg-opacity-0 group-hover:bg-opacity-30 rounded-lg transition-all duration-300 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-external-link w-3 h-3 text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                        <path d="M15 3h6v6"></path>
                                        <path d="M10 14 21 3"></path>
                                        <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="min-w-0 flex-1">
                                <h3
                                    class="font-semibold text-white truncate cursor-pointer hover:text-orange-400 transition-colors">
                                    Digital Horizon</h3>
                                <p class="text-sm text-gray-400 truncate">TechnoMind</p>
                            </div>
                        </div>
                        <div
                            class="col-span-2 md:col-span-2 flex items-center justify-center cursor-pointer hover:text-orange-400 transition-colors">
                            <span class="font-bold text-orange-400">7.6/10</span>
                        </div>
                        <div class="hidden md:col-span-2 md:flex md:items-center md:justify-center">
                            <span class="text-gray-400">2.0K</span>
                        </div>
                        <div class="hidden md:col-span-2 md:flex md:items-center md:justify-center">
                            <span class="text-gray-400">780</span>
                        </div>
                        <div class="col-span-5 md:col-span-1 flex items-center justify-center">
                            <div class="flex items-center gap-1">
                                <button
                                    class="flex items-center justify-center w-8 h-8 rounded-full transition-all duration-200 bg-gray-700 text-gray-300 hover:bg-orange-500 hover:text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-play w-3 h-3 ml-0.5">
                                        <polygon points="5 3 19 12 5 21 5 3"></polygon>
                                    </svg>
                                </button>
                                <button
                                    class="flex items-center justify-center w-8 h-8 rounded-full transition-all duration-200 bg-gray-700 text-gray-300 hover:bg-red-500 hover:text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-heart w-3 h-3 ">
                                        <path
                                            d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z">
                                        </path>
                                    </svg>
                                </button>
                                <button
                                    class="flex items-center justify-center w-8 h-8 rounded-full transition-all duration-200 bg-gray-700 text-gray-300 hover:bg-green-500 hover:text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-rotate-ccw w-3 h-3">
                                        <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"></path>
                                        <path d="M3 3v5h5"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <!-- Mobile Stats -->
                        <div class="md:hidden col-span-12 pt-2 flex justify-between text-xs text-gray-400">
                            <span>Reach: 2.0K</span>
                            <span>Reposts: 780</span>
                        </div>
                    </div>

                    <!-- Track 7 -->
                    <div
                        class="grid grid-cols-12 gap-4 p-4 hover:bg-gray-700 transition-colors duration-200 border-b border-gray-700">
                        <div class="col-span-1 flex items-center">
                            <div
                                class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold bg-gray-700 text-gray-300">
                                7</div>
                        </div>
                        <div class="col-span-4 md:col-span-4 flex items-center gap-3">
                            <div class="relative group cursor-pointer">
                                <img src="https://images.pexels.com/photos/1699030/pexels-photo-1699030.jpeg?auto=compress&amp;cs=tinysrgb&amp;w=300"
                                    alt="Sunset Boulevard"
                                    class="w-12 h-12 rounded-lg object-cover transition-transform duration-300 group-hover:scale-105">
                                <div
                                    class="absolute inset-0  bg-opacity-0 group-hover:bg-opacity-30 rounded-lg transition-all duration-300 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-external-link w-3 h-3 text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                        <path d="M15 3h6v6"></path>
                                        <path d="M10 14 21 3"></path>
                                        <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="min-w-0 flex-1">
                                <h3
                                    class="font-semibold text-white truncate cursor-pointer hover:text-orange-400 transition-colors">
                                    Sunset Boulevard</h3>
                                <p class="text-sm text-gray-400 truncate">Golden Hour</p>
                            </div>
                        </div>
                        <div
                            class="col-span-2 md:col-span-2 flex items-center justify-center cursor-pointer hover:text-orange-400 transition-colors">
                            <span class="font-bold text-orange-400">7.3/10</span>
                        </div>
                        <div class="hidden md:col-span-2 md:flex md:items-center md:justify-center">
                            <span class="text-gray-400">1.9K</span>
                        </div>
                        <div class="hidden md:col-span-2 md:flex md:items-center md:justify-center">
                            <span class="text-gray-400">720</span>
                        </div>
                        <div class="col-span-5 md:col-span-1 flex items-center justify-center">
                            <div class="flex items-center gap-1">
                                <button
                                    class="flex items-center justify-center w-8 h-8 rounded-full transition-all duration-200 bg-gray-700 text-gray-300 hover:bg-orange-500 hover:text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-play w-3 h-3 ml-0.5">
                                        <polygon points="5 3 19 12 5 21 5 3"></polygon>
                                    </svg>
                                </button>
                                <button
                                    class="flex items-center justify-center w-8 h-8 rounded-full transition-all duration-200 bg-gray-700 text-gray-300 hover:bg-red-500 hover:text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-heart w-3 h-3 ">
                                        <path
                                            d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z">
                                        </path>
                                    </svg>
                                </button>
                                <button
                                    class="flex items-center justify-center w-8 h-8 rounded-full transition-all duration-200 bg-gray-700 text-gray-300 hover:bg-green-500 hover:text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-rotate-ccw w-3 h-3">
                                        <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"></path>
                                        <path d="M3 3v5h5"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <!-- Mobile Stats -->
                        <div class="md:hidden col-span-12 pt-2 flex justify-between text-xs text-gray-400">
                            <span>Reach: 1.9K</span>
                            <span>Reposts: 720</span>
                        </div>
                    </div>

                    <!-- Track 8 -->
                    <div
                        class="grid grid-cols-12 gap-4 p-4 hover:bg-gray-700 transition-colors duration-200 border-b border-gray-700">
                        <div class="col-span-1 flex items-center">
                            <div
                                class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold bg-gray-700 text-gray-300">
                                8</div>
                        </div>
                        <div class="col-span-4 md:col-span-4 flex items-center gap-3">
                            <div class="relative group cursor-pointer">
                                <img src="https://images.pexels.com/photos/1190295/pexels-photo-1190295.jpeg?auto=compress&amp;cs=tinysrgb&amp;w=300"
                                    alt="Thunder Road"
                                    class="w-12 h-12 rounded-lg object-cover transition-transform duration-300 group-hover:scale-105">
                                <div
                                    class="absolute inset-0  bg-opacity-0 group-hover:bg-opacity-30 rounded-lg transition-all duration-300 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-external-link w-3 h-3 text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                        <path d="M15 3h6v6"></path>
                                        <path d="M10 14 21 3"></path>
                                        <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="min-w-0 flex-1">
                                <h3
                                    class="font-semibold text-white truncate cursor-pointer hover:text-orange-400 transition-colors">
                                    Thunder Road</h3>
                                <p class="text-sm text-gray-400 truncate">Electric Storm</p>
                            </div>
                        </div>
                        <div
                            class="col-span-2 md:col-span-2 flex items-center justify-center cursor-pointer hover:text-orange-400 transition-colors">
                            <span class="font-bold text-orange-400">7.1/10</span>
                        </div>
                        <div class="hidden md:col-span-2 md:flex md:items-center md:justify-center">
                            <span class="text-gray-400">1.7K</span>
                        </div>
                        <div class="hidden md:col-span-2 md:flex md:items-center md:justify-center">
                            <span class="text-gray-400">680</span>
                        </div>
                        <div class="col-span-5 md:col-span-1 flex items-center justify-center">
                            <div class="flex items-center gap-1">
                                <button
                                    class="flex items-center justify-center w-8 h-8 rounded-full transition-all duration-200 bg-gray-700 text-gray-300 hover:bg-orange-500 hover:text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-play w-3 h-3 ml-0.5">
                                        <polygon points="5 3 19 12 5 21 5 3"></polygon>
                                    </svg>
                                </button>
                                <button
                                    class="flex items-center justify-center w-8 h-8 rounded-full transition-all duration-200 bg-red-500 text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-heart w-3 h-3 fill-current">
                                        <path
                                            d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z">
                                        </path>
                                    </svg>
                                </button>
                                <button
                                    class="flex items-center justify-center w-8 h-8 rounded-full transition-all duration-200 bg-gray-700 text-gray-300 hover:bg-green-500 hover:text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-rotate-ccw w-3 h-3">
                                        <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"></path>
                                        <path d="M3 3v5h5"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <!-- Mobile Stats -->
                        <div class="md:hidden col-span-12 pt-2 flex justify-between text-xs text-gray-400">
                            <span>Reach: 1.7K</span>
                            <span>Reposts: 680</span>
                        </div>
                    </div>

                    <!-- Track 9 -->
                    <div
                        class="grid grid-cols-12 gap-4 p-4 hover:bg-gray-700 transition-colors duration-200 border-b border-gray-700">
                        <div class="col-span-1 flex items-center">
                            <div
                                class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold bg-gray-700 text-gray-300">
                                9</div>
                        </div>
                        <div class="col-span-4 md:col-span-4 flex items-center gap-3">
                            <div class="relative group cursor-pointer">
                                <img src="https://images.pexels.com/photos/1047442/pexels-photo-1047442.jpeg?auto=compress&amp;cs=tinysrgb&amp;w=300"
                                    alt="Velvet Dreams"
                                    class="w-12 h-12 rounded-lg object-cover transition-transform duration-300 group-hover:scale-105">
                                <div
                                    class="absolute inset-0  bg-opacity-0 group-hover:bg-opacity-30 rounded-lg transition-all duration-300 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-external-link w-3 h-3 text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                        <path d="M15 3h6v6"></path>
                                        <path d="M10 14 21 3"></path>
                                        <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="min-w-0 flex-1">
                                <h3
                                    class="font-semibold text-white truncate cursor-pointer hover:text-orange-400 transition-colors">
                                    Velvet Dreams</h3>
                                <p class="text-sm text-gray-400 truncate">Smooth Operator</p>
                            </div>
                        </div>
                        <div
                            class="col-span-2 md:col-span-2 flex items-center justify-center cursor-pointer hover:text-orange-400 transition-colors">
                            <span class="font-bold text-orange-400">6.9/10</span>
                        </div>
                        <div class="hidden md:col-span-2 md:flex md:items-center md:justify-center">
                            <span class="text-gray-400">1.6K</span>
                        </div>
                        <div class="hidden md:col-span-2 md:flex md:items-center md:justify-center">
                            <span class="text-gray-400">640</span>
                        </div>
                        <div class="col-span-5 md:col-span-1 flex items-center justify-center">
                            <div class="flex items-center gap-1">
                                <button
                                    class="flex items-center justify-center w-8 h-8 rounded-full transition-all duration-200 bg-gray-700 text-gray-300 hover:bg-orange-500 hover:text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-play w-3 h-3 ml-0.5">
                                        <polygon points="5 3 19 12 5 21 5 3"></polygon>
                                    </svg>
                                </button>
                                <button
                                    class="flex items-center justify-center w-8 h-8 rounded-full transition-all duration-200 bg-gray-700 text-gray-300 hover:bg-red-500 hover:text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-heart w-3 h-3 ">
                                        <path
                                            d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z">
                                        </path>
                                    </svg>
                                </button>
                                <button
                                    class="flex items-center justify-center w-8 h-8 rounded-full transition-all duration-200 bg-gray-700 text-gray-300 hover:bg-green-500 hover:text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-rotate-ccw w-3 h-3">
                                        <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"></path>
                                        <path d="M3 3v5h5"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <!-- Mobile Stats -->
                        <div class="md:hidden col-span-12 pt-2 flex justify-between text-xs text-gray-400">
                            <span>Reach: 1.6K</span>
                            <span>Reposts: 640</span>
                        </div>
                    </div>

                    <!-- Track 10 -->
                    <div
                        class="grid grid-cols-12 gap-4 p-4 hover:bg-gray-700 transition-colors duration-200 border-b border-gray-700">
                        <div class="col-span-1 flex items-center">
                            <div
                                class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold bg-gray-700 text-gray-300">
                                10</div>
                        </div>
                        <div class="col-span-4 md:col-span-4 flex items-center gap-3">
                            <div class="relative group cursor-pointer">
                                <img src="https://images.pexels.com/photos/1540406/pexels-photo-1540406.jpeg?auto=compress&amp;cs=tinysrgb&amp;w=300"
                                    alt="Phoenix Rising"
                                    class="w-12 h-12 rounded-lg object-cover transition-transform duration-300 group-hover:scale-105">
                                <div
                                    class="absolute inset-0  bg-opacity-0 group-hover:bg-opacity-30 rounded-lg transition-all duration-300 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-external-link w-3 h-3 text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                        <path d="M15 3h6v6"></path>
                                        <path d="M10 14 21 3"></path>
                                        <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="min-w-0 flex-1">
                                <h3
                                    class="font-semibold text-white truncate cursor-pointer hover:text-orange-400 transition-colors">
                                    Phoenix Rising</h3>
                                <p class="text-sm text-gray-400 truncate">Flame Collective</p>
                            </div>
                        </div>
                        <div
                            class="col-span-2 md:col-span-2 flex items-center justify-center cursor-pointer hover:text-orange-400 transition-colors">
                            <span class="font-bold text-orange-400">6.6/10</span>
                        </div>
                        <div class="hidden md:col-span-2 md:flex md:items-center md:justify-center">
                            <span class="text-gray-400">1.5K</span>
                        </div>
                        <div class="hidden md:col-span-2 md:flex md:items-center md:justify-center">
                            <span class="text-gray-400">590</span>
                        </div>
                        <div class="col-span-5 md:col-span-1 flex items-center justify-center">
                            <div class="flex items-center gap-1">
                                <button
                                    class="flex items-center justify-center w-8 h-8 rounded-full transition-all duration-200 bg-gray-700 text-gray-300 hover:bg-orange-500 hover:text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-play w-3 h-3 ml-0.5">
                                        <polygon points="5 3 19 12 5 21 5 3"></polygon>
                                    </svg>
                                </button>
                                <button
                                    class="flex items-center justify-center w-8 h-8 rounded-full transition-all duration-200 bg-gray-700 text-gray-300 hover:bg-red-500 hover:text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-heart w-3 h-3 ">
                                        <path
                                            d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z">
                                        </path>
                                    </svg>
                                </button>
                                <button
                                    class="flex items-center justify-center w-8 h-8 rounded-full transition-all duration-200 bg-green-500 text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-rotate-ccw w-3 h-3">
                                        <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"></path>
                                        <path d="M3 3v5h5"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <!-- Mobile Stats -->
                        <div class="md:hidden col-span-12 pt-2 flex justify-between text-xs text-gray-400">
                            <span>Reach: 1.5K</span>
                            <span>Reposts: 590</span>
                        </div>
                    </div>

                    <!-- Track 11 -->
                    <div
                        class="grid grid-cols-12 gap-4 p-4 hover:bg-gray-700 transition-colors duration-200 border-b border-gray-700">
                        <div class="col-span-1 flex items-center">
                            <div
                                class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold bg-gray-700 text-gray-300">
                                11</div>
                        </div>
                        <div class="col-span-4 md:col-span-4 flex items-center gap-3">
                            <div class="relative group cursor-pointer">
                                <img src="https://images.pexels.com/photos/1037992/pexels-photo-1037992.jpeg?auto=compress&amp;cs=tinysrgb&amp;w=300"
                                    alt="Starlight Serenade"
                                    class="w-12 h-12 rounded-lg object-cover transition-transform duration-300 group-hover:scale-105">
                                <div
                                    class="absolute inset-0 bg-opacity-0 group-hover:bg-opacity-30 rounded-lg transition-all duration-300 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-external-link w-3 h-3 text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                        <path d="M15 3h6v6"></path>
                                        <path d="M10 14 21 3"></path>
                                        <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="min-w-0 flex-1">
                                <h3
                                    class="font-semibold text-white truncate cursor-pointer hover:text-orange-400 transition-colors">
                                    Starlight Serenade</h3>
                                <p class="text-sm text-gray-400 truncate">Night Sky Orchestra</p>
                            </div>
                        </div>
                        <div
                            class="col-span-2 md:col-span-2 flex items-center justify-center cursor-pointer hover:text-orange-400 transition-colors">
                            <span class="font-bold text-orange-400">6.3/10</span>
                        </div>
                        <div class="hidden md:col-span-2 md:flex md:items-center md:justify-center">
                            <span class="text-gray-400">1.4K</span>
                        </div>
                        <div class="hidden md:col-span-2 md:flex md:items-center md:justify-center">
                            <span class="text-gray-400">550</span>
                        </div>
                        <div class="col-span-5 md:col-span-1 flex items-center justify-center">
                            <div class="flex items-center gap-1">
                                <button
                                    class="flex items-center justify-center w-8 h-8 rounded-full transition-all duration-200 bg-gray-700 text-gray-300 hover:bg-orange-500 hover:text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-play w-3 h-3 ml-0.5">
                                        <polygon points="5 3 19 12 5 21 5 3"></polygon>
                                    </svg>
                                </button>
                                <button
                                    class="flex items-center justify-center w-8 h-8 rounded-full transition-all duration-200 bg-gray-700 text-gray-300 hover:bg-red-500 hover:text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-heart w-3 h-3 ">
                                        <path
                                            d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z">
                                        </path>
                                    </svg>
                                </button>
                                <button
                                    class="flex items-center justify-center w-8 h-8 rounded-full transition-all duration-200 bg-gray-700 text-gray-300 hover:bg-green-500 hover:text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-rotate-ccw w-3 h-3">
                                        <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"></path>
                                        <path d="M3 3v5h5"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <!-- Mobile Stats -->
                        <div class="md:hidden col-span-12 pt-2 flex justify-between text-xs text-gray-400">
                            <span>Reach: 1.4K</span>
                            <span>Reposts: 550</span>
                        </div>
                    </div>

                    <!-- Track 12 -->
                    <div
                        class="grid grid-cols-12 gap-4 p-4 hover:bg-gray-700 transition-colors duration-200 border-b border-gray-700">
                        <div class="col-span-1 flex items-center">
                            <div
                                class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold bg-gray-700 text-gray-300">
                                12</div>
                        </div>
                        <div class="col-span-4 md:col-span-4 flex items-center gap-3">
                            <div class="relative group cursor-pointer">
                                <img src="https://images.pexels.com/photos/1389429/pexels-photo-1389429.jpeg?auto=compress&amp;cs=tinysrgb&amp;w=300"
                                    alt="Bass Drop Revolution"
                                    class="w-12 h-12 rounded-lg object-cover transition-transform duration-300 group-hover:scale-105">
                                <div
                                    class="absolute inset-0  bg-opacity-0 group-hover:bg-opacity-30 rounded-lg transition-all duration-300 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-external-link w-3 h-3 text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                        <path d="M15 3h6v6"></path>
                                        <path d="M10 14 21 3"></path>
                                        <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="min-w-0 flex-1">
                                <h3
                                    class="font-semibold text-white truncate cursor-pointer hover:text-orange-400 transition-colors">
                                    Bass Drop Revolution</h3>
                                <p class="text-sm text-gray-400 truncate">Sub Frequency</p>
                            </div>
                        </div>
                        <div
                            class="col-span-2 md:col-span-2 flex items-center justify-center cursor-pointer hover:text-orange-400 transition-colors">
                            <span class="font-bold text-orange-400">6.1/10</span>
                        </div>
                        <div class="hidden md:col-span-2 md:flex md:items-center md:justify-center">
                            <span class="text-gray-400">1.3K</span>
                        </div>
                        <div class="hidden md:col-span-2 md:flex md:items-center md:justify-center">
                            <span class="text-gray-400">520</span>
                        </div>
                        <div class="col-span-5 md:col-span-1 flex items-center justify-center">
                            <div class="flex items-center gap-1">
                                <button
                                    class="flex items-center justify-center w-8 h-8 rounded-full transition-all duration-200 bg-gray-700 text-gray-300 hover:bg-orange-500 hover:text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-play w-3 h-3 ml-0.5">
                                        <polygon points="5 3 19 12 5 21 5 3"></polygon>
                                    </svg>
                                </button>
                                <button
                                    class="flex items-center justify-center w-8 h-8 rounded-full transition-all duration-200 bg-red-500 text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-heart w-3 h-3 fill-current">
                                        <path
                                            d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z">
                                        </path>
                                    </svg>
                                </button>
                                <button
                                    class="flex items-center justify-center w-8 h-8 rounded-full transition-all duration-200 bg-green-500 text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-rotate-ccw w-3 h-3">
                                        <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"></path>
                                        <path d="M3 3v5h5"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <!-- Mobile Stats -->
                        <div class="md:hidden col-span-12 pt-2 flex justify-between text-xs text-gray-400">
                            <span>Reach: 1.3K</span>
                            <span>Reposts: 520</span>
                        </div>
                    </div>

                    <!-- Track 13 -->
                    <div
                        class="grid grid-cols-12 gap-4 p-4 hover:bg-gray-700 transition-colors duration-200 border-b border-gray-700">
                        <div class="col-span-1 flex items-center">
                            <div
                                class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold bg-gray-700 text-gray-300">
                                13</div>
                        </div>
                        <div class="col-span-4 md:col-span-4 flex items-center gap-3">
                            <div class="relative group cursor-pointer">
                                <img src="https://images.pexels.com/photos/1386604/pexels-photo-1386604.jpeg?auto=compress&amp;cs=tinysrgb&amp;w=300"
                                    alt="Acoustic Memories"
                                    class="w-12 h-12 rounded-lg object-cover transition-transform duration-300 group-hover:scale-105">
                                <div
                                    class="absolute inset-0  bg-opacity-0 group-hover:bg-opacity-30 rounded-lg transition-all duration-300 flex items-center justify-center">
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
                                    class="font-semibold text-white truncate cursor-pointer hover:text-orange-400 transition-colors">
                                    Acoustic Memories</h3>
                                <p class="text-sm text-gray-400 truncate">String Theory</p>
                            </div>
                        </div>
                        <div
                            class="col-span-2 md:col-span-2 flex items-center justify-center cursor-pointer hover:text-orange-400 transition-colors">
                            <span class="font-bold text-orange-400">5.8/10</span>
                        </div>
                        <div class="hidden md:col-span-2 md:flex md:items-center md:justify-center">
                            <span class="text-gray-400">1.3K</span>
                        </div>
                        <div class="hidden md:col-span-2 md:flex md:items-center md:justify-center">
                            <span class="text-gray-400">480</span>
                        </div>
                        <div class="col-span-5 md:col-span-1 flex items-center justify-center">
                            <div class="flex items-center gap-1">
                                <button
                                    class="flex items-center justify-center w-8 h-8 rounded-full transition-all duration-200 bg-gray-700 text-gray-300 hover:bg-orange-500 hover:text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-play w-3 h-3 ml-0.5">
                                        <polygon points="5 3 19 12 5 21 5 3"></polygon>
                                    </svg>
                                </button>
                                <button
                                    class="flex items-center justify-center w-8 h-8 rounded-full transition-all duration-200 bg-gray-700 text-gray-300 hover:bg-red-500 hover:text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-heart w-3 h-3 ">
                                        <path
                                            d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z">
                                        </path>
                                    </svg>
                                </button>
                                <button
                                    class="flex items-center justify-center w-8 h-8 rounded-full transition-all duration-200 bg-gray-700 text-gray-300 hover:bg-green-500 hover:text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-rotate-ccw w-3 h-3">
                                        <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"></path>
                                        <path d="M3 3v5h5"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <!-- Mobile Stats -->
                        <div class="md:hidden col-span-12 pt-2 flex justify-between text-xs text-gray-400">
                            <span>Reach: 1.3K</span>
                            <span>Reposts: 480</span>
                        </div>
                    </div>

                    <!-- Track 14 -->
                    <div
                        class="grid grid-cols-12 gap-4 p-4 hover:bg-gray-700 transition-colors duration-200 border-b border-gray-700">
                        <div class="col-span-1 flex items-center">
                            <div
                                class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold bg-gray-700 text-gray-300">
                                14</div>
                        </div>
                        <div class="col-span-4 md:col-span-4 flex items-center gap-3">
                            <div class="relative group cursor-pointer">
                                <img src="https://images.pexels.com/photos/1644924/pexels-photo-1644924.jpeg?auto=compress&amp;cs=tinysrgb&amp;w=300"
                                    alt="Rhythm & Soul"
                                    class="w-12 h-12 rounded-lg object-cover transition-transform duration-300 group-hover:scale-105">
                                <div
                                    class="absolute inset-0 bg-opacity-0 group-hover:bg-opacity-30 rounded-lg transition-all duration-300 flex items-center justify-center">
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
                                    class="font-semibold text-white truncate cursor-pointer hover:text-orange-400 transition-colors">
                                    Rhythm & Soul</h3>
                                <p class="text-sm text-gray-400 truncate">Groove Machine</p>
                            </div>
                        </div>
                        <div
                            class="col-span-2 md:col-span-2 flex items-center justify-center cursor-pointer hover:text-orange-400 transition-colors">
                            <span class="font-bold text-orange-400">5.6/10</span>
                        </div>
                        <div class="hidden md:col-span-2 md:flex md:items-center md:justify-center">
                            <span class="text-gray-400">1.2K</span>
                        </div>
                        <div class="hidden md:col-span-2 md:flex md:items-center md:justify-center">
                            <span class="text-gray-400">450</span>
                        </div>
                        <div class="col-span-5 md:col-span-1 flex items-center justify-center">
                            <div class="flex items-center gap-1">
                                <button
                                    class="flex items-center justify-center w-8 h-8 rounded-full transition-all duration-200 bg-gray-700 text-gray-300 hover:bg-orange-500 hover:text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-play w-3 h-3 ml-0.5">
                                        <polygon points="5 3 19 12 5 21 5 3"></polygon>
                                    </svg>
                                </button>
                                <button
                                    class="flex items-center justify-center w-8 h-8 rounded-full transition-all duration-200 bg-gray-700 text-gray-300 hover:bg-red-500 hover:text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-heart w-3 h-3 ">
                                        <path
                                            d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z">
                                        </path>
                                    </svg>
                                </button>
                                <button
                                    class="flex items-center justify-center w-8 h-8 rounded-full transition-all duration-200 bg-gray-700 text-gray-300 hover:bg-green-500 hover:text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-rotate-ccw w-3 h-3">
                                        <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"></path>
                                        <path d="M3 3v5h5"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <!-- Mobile Stats -->
                        <div class="md:hidden col-span-12 pt-2 flex justify-between text-xs text-gray-400">
                            <span>Reach: 1.2K</span>
                            <span>Reposts: 450</span>
                        </div>
                    </div>

                    <!-- Track 15 -->
                    <div
                        class="grid grid-cols-12 gap-4 p-4 hover:bg-gray-700 transition-colors duration-200 border-b border-gray-700">
                        <div class="col-span-1 flex items-center">
                            <div
                                class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold bg-gray-700 text-gray-300">
                                15</div>
                        </div>
                        <div class="col-span-4 md:col-span-4 flex items-center gap-3">
                            <div class="relative group cursor-pointer">
                                <img src="https://images.pexels.com/photos/1105666/pexels-photo-1105666.jpeg?auto=compress&amp;cs=tinysrgb&amp;w=300"
                                    alt="Ethereal Voices"
                                    class="w-12 h-12 rounded-lg object-cover transition-transform duration-300 group-hover:scale-105">
                                <div
                                    class="absolute inset-0  bg-opacity-0 group-hover:bg-opacity-30 rounded-lg transition-all duration-300 flex items-center justify-center">
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
                                    class="font-semibold text-white truncate cursor-pointer hover:text-orange-400 transition-colors">
                                    Ethereal Voices</h3>
                                <p class="text-sm text-gray-400 truncate">Celestial Choir</p>
                            </div>
                        </div>
                        <div
                            class="col-span-2 md:col-span-2 flex items-center justify-center cursor-pointer hover:text-orange-400 transition-colors">
                            <span class="font-bold text-orange-400">5.4/10</span>
                        </div>
                        <div class="hidden md:col-span-2 md:flex md:items-center md:justify-center">
                            <span class="text-gray-400">1.1K</span>
                        </div>
                        <div class="hidden md:col-span-2 md:flex md:items-center md:justify-center">
                            <span class="text-gray-400">420</span>
                        </div>
                        <div class="col-span-5 md:col-span-1 flex items-center justify-center">
                            <div class="flex items-center gap-1">
                                <button
                                    class="flex items-center justify-center w-8 h-8 rounded-full transition-all duration-200 bg-gray-700 text-gray-300 hover:bg-orange-500 hover:text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-play w-3 h-3 ml-0.5">
                                        <polygon points="5 3 19 12 5 21 5 3"></polygon>
                                    </svg>
                                </button>
                                <button
                                    class="flex items-center justify-center w-8 h-8 rounded-full transition-all duration-200 bg-gray-700 text-gray-300 hover:bg-red-500 hover:text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-heart w-3 h-3 ">
                                        <path
                                            d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z">
                                        </path>
                                    </svg>
                                </button>
                                <button
                                    class="flex items-center justify-center w-8 h-8 rounded-full transition-all duration-200 bg-gray-700 text-gray-300 hover:bg-green-500 hover:text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-rotate-ccw w-3 h-3">
                                        <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"></path>
                                        <path d="M3 3v5h5"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <!-- Mobile Stats -->
                        <div class="md:hidden col-span-12 pt-2 flex justify-between text-xs text-gray-400">
                            <span>Reach: 1.1K</span>
                            <span>Reposts: 420</span>
                        </div>
                    </div>

                    <!-- Track 16 -->
                    <div
                        class="grid grid-cols-12 gap-4 p-4 hover:bg-gray-700 transition-colors duration-200 border-b border-gray-700">
                        <div class="col-span-1 flex items-center">
                            <div
                                class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold bg-gray-700 text-gray-300">
                                16</div>
                        </div>
                        <div class="col-span-4 md:col-span-4 flex items-center gap-3">
                            <div class="relative group cursor-pointer">
                                <img src="https://images.pexels.com/photos/1407322/pexels-photo-1407322.jpeg?auto=compress&amp;cs=tinysrgb&amp;w=300"
                                    alt="Metro Beats"
                                    class="w-12 h-12 rounded-lg object-cover transition-transform duration-300 group-hover:scale-105">
                                <div
                                    class="absolute inset-0  bg-opacity-0 group-hover:bg-opacity-30 rounded-lg transition-all duration-300 flex items-center justify-center">
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
                                    class="font-semibold text-white truncate cursor-pointer hover:text-orange-400 transition-colors">
                                    Metro Beats</h3>
                                <p class="text-sm text-gray-400 truncate">City Sounds</p>
                            </div>
                        </div>
                        <div
                            class="col-span-2 md:col-span-2 flex items-center justify-center cursor-pointer hover:text-orange-400 transition-colors">
                            <span class="font-bold text-orange-400">5.2/10</span>
                        </div>
                        <div class="hidden md:col-span-2 md:flex md:items-center md:justify-center">
                            <span class="text-gray-400">1.1K</span>
                        </div>
                        <div class="hidden md:col-span-2 md:flex md:items-center md:justify-center">
                            <span class="text-gray-400">390</span>
                        </div>
                        <div class="col-span-5 md:col-span-1 flex items-center justify-center">
                            <div class="flex items-center gap-1">
                                <button
                                    class="flex items-center justify-center w-8 h-8 rounded-full transition-all duration-200 bg-gray-700 text-gray-300 hover:bg-orange-500 hover:text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-play w-3 h-3 ml-0.5">
                                        <polygon points="5 3 19 12 5 21 5 3"></polygon>
                                    </svg>
                                </button>
                                <button
                                    class="flex items-center justify-center w-8 h-8 rounded-full transition-all duration-200 bg-gray-700 text-gray-300 hover:bg-red-500 hover:text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-heart w-3 h-3 ">
                                        <path
                                            d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z">
                                        </path>
                                    </svg>
                                </button>
                                <button
                                    class="flex items-center justify-center w-8 h-8 rounded-full transition-all duration-200 bg-gray-700 text-gray-300 hover:bg-green-500 hover:text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-rotate-ccw w-3 h-3">
                                        <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"></path>
                                        <path d="M3 3v5h5"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <!-- Mobile Stats -->
                        <div class="md:hidden col-span-12 pt-2 flex justify-between text-xs text-gray-400">
                            <span>Reach: 1.1K</span>
                            <span>Reposts: 390</span>
                        </div>
                    </div>

                    <!-- Track 17 -->
                    <div
                        class="grid grid-cols-12 gap-4 p-4 hover:bg-gray-700 transition-colors duration-200 border-b border-gray-700">
                        <div class="col-span-1 flex items-center">
                            <div
                                class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold bg-gray-700 text-gray-300">
                                17</div>
                        </div>
                        <div class="col-span-4 md:col-span-4 flex items-center gap-3">
                            <div class="relative group cursor-pointer">
                                <img src="https://images.pexels.com/photos/1761279/pexels-photo-1761279.jpeg?auto=compress&amp;cs=tinysrgb&amp;w=300"
                                    alt="Crystal Waters"
                                    class="w-12 h-12 rounded-lg object-cover transition-transform duration-300 group-hover:scale-105">
                                <div
                                    class="absolute inset-0 bg-opacity-0 group-hover:bg-opacity-30 rounded-lg transition-all duration-300 flex items-center justify-center">
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
                                    class="font-semibold text-white truncate cursor-pointer hover:text-orange-400 transition-colors">
                                    Crystal Waters</h3>
                                <p class="text-sm text-gray-400 truncate">Pure Resonance</p>
                            </div>
                        </div>
                        <div
                            class="col-span-2 md:col-span-2 flex items-center justify-center cursor-pointer hover:text-orange-400 transition-colors">
                            <span class="font-bold text-orange-400">4.9/10</span>
                        </div>
                        <div class="hidden md:col-span-2 md:flex md:items-center md:justify-center">
                            <span class="text-gray-400">980</span>
                        </div>
                        <div class="hidden md:col-span-2 md:flex md:items-center md:justify-center">
                            <span class="text-gray-400">360</span>
                        </div>
                        <div class="col-span-5 md:col-span-1 flex items-center justify-center">
                            <div class="flex items-center gap-1">
                                <button
                                    class="flex items-center justify-center w-8 h-8 rounded-full transition-all duration-200 bg-gray-700 text-gray-300 hover:bg-orange-500 hover:text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-play w-3 h-3 ml-0.5">
                                        <polygon points="5 3 19 12 5 21 5 3"></polygon>
                                    </svg>
                                </button>
                                <button
                                    class="flex items-center justify-center w-8 h-8 rounded-full transition-all duration-200 bg-gray-700 text-gray-300 hover:bg-red-500 hover:text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-heart w-3 h-3 ">
                                        <path
                                            d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z">
                                        </path>
                                    </svg>
                                </button>
                                <button
                                    class="flex items-center justify-center w-8 h-8 rounded-full transition-all duration-200 bg-gray-700 text-gray-300 hover:bg-green-500 hover:text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-rotate-ccw w-3 h-3">
                                        <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"></path>
                                        <path d="M3 3v5h5"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <!-- Mobile Stats -->
                        <div class="md:hidden col-span-12 pt-2 flex justify-between text-xs text-gray-400">
                            <span>Reach: 980</span>
                            <span>Reposts: 360</span>
                        </div>
                    </div>

                    <!-- Track 18 -->
                    <div
                        class="grid grid-cols-12 gap-4 p-4 hover:bg-gray-700 transition-colors duration-200 border-b border-gray-700">
                        <div class="col-span-1 flex items-center">
                            <div
                                class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold bg-gray-700 text-gray-300">
                                18</div>
                        </div>
                        <div class="col-span-4 md:col-span-4 flex items-center gap-3">
                            <div class="relative group cursor-pointer">
                                <img src="https://images.pexels.com/photos/1370704/pexels-photo-1370704.jpeg?auto=compress&amp;cs=tinysrgb&amp;w=300"
                                    alt="Neon Escape"
                                    class="w-12 h-12 rounded-lg object-cover transition-transform duration-300 group-hover:scale-105">
                                <div
                                    class="absolute inset-0 bg-opacity-0 group-hover:bg-opacity-30 rounded-lg transition-all duration-300 flex items-center justify-center">
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
                                    class="font-semibold text-white truncate cursor-pointer hover:text-orange-400 transition-colors">
                                    Neon Escape</h3>
                                <p class="text-sm text-gray-400 truncate">Retro Future</p>
                            </div>
                        </div>
                        <div
                            class="col-span-2 md:col-span-2 flex items-center justify-center cursor-pointer hover:text-orange-400 transition-colors">
                            <span class="font-bold text-orange-400">4.7/10</span>
                        </div>
                        <div class="hidden md:col-span-2 md:flex md:items-center md:justify-center">
                            <span class="text-gray-400">920</span>
                        </div>
                        <div class="hidden md:col-span-2 md:flex md:items-center md:justify-center">
                            <span class="text-gray-400">330</span>
                        </div>
                        <div class="col-span-5 md:col-span-1 flex items-center justify-center">
                            <div class="flex items-center gap-1">
                                <button
                                    class="flex items-center justify-center w-8 h-8 rounded-full transition-all duration-200 bg-gray-700 text-gray-300 hover:bg-orange-500 hover:text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-play w-3 h-3 ml-0.5">
                                        <polygon points="5 3 19 12 5 21 5 3"></polygon>
                                    </svg>
                                </button>
                                <button
                                    class="flex items-center justify-center w-8 h-8 rounded-full transition-all duration-200 bg-gray-700 text-gray-300 hover:bg-red-500 hover:text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-heart w-3 h-3 ">
                                        <path
                                            d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z">
                                        </path>
                                    </svg>
                                </button>
                                <button
                                    class="flex items-center justify-center w-8 h-8 rounded-full transition-all duration-200 bg-gray-700 text-gray-300 hover:bg-green-500 hover:text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-rotate-ccw w-3 h-3">
                                        <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"></path>
                                        <path d="M3 3v5h5"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <!-- Mobile Stats -->
                        <div class="md:hidden col-span-12 pt-2 flex justify-between text-xs text-gray-400">
                            <span>Reach: 920</span>
                            <span>Reposts: 330</span>
                        </div>
                    </div>

                    <!-- Track 19 -->
                    <div
                        class="grid grid-cols-12 gap-4 p-4 hover:bg-gray-700 transition-colors duration-200 border-b border-gray-700">
                        <div class="col-span-1 flex items-center">
                            <div
                                class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold bg-gray-700 text-gray-300">
                                19</div>
                        </div>
                        <div class="col-span-4 md:col-span-4 flex items-center gap-3">
                            <div class="relative group cursor-pointer">
                                <img src="https://images.pexels.com/photos/1172207/pexels-photo-1172207.jpeg?auto=compress&amp;cs=tinysrgb&amp;w=300"
                                    alt="Woodland Whispers"
                                    class="w-12 h-12 rounded-lg object-cover transition-transform duration-300 group-hover:scale-105">
                                <div
                                    class="absolute inset-0 bg-opacity-0 group-hover:bg-opacity-30 rounded-lg transition-all duration-300 flex items-center justify-center">
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
                                    class="font-semibold text-white truncate cursor-pointer hover:text-orange-400 transition-colors">
                                    Woodland Whispers</h3>
                                <p class="text-sm text-gray-400 truncate">Nature's Symphony</p>
                            </div>
                        </div>
                        <div
                            class="col-span-2 md:col-span-2 flex items-center justify-center cursor-pointer hover:text-orange-400 transition-colors">
                            <span class="font-bold text-orange-400">4.5/10</span>
                        </div>
                        <div class="hidden md:col-span-2 md:flex md:items-center md:justify-center">
                            <span class="text-gray-400">860</span>
                        </div>
                        <div class="hidden md:col-span-2 md:flex md:items-center md:justify-center">
                            <span class="text-gray-400">310</span>
                        </div>
                        <div class="col-span-5 md:col-span-1 flex items-center justify-center">
                            <div class="flex items-center gap-1">
                                <button
                                    class="flex items-center justify-center w-8 h-8 rounded-full transition-all duration-200 bg-gray-700 text-gray-300 hover:bg-orange-500 hover:text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-play w-3 h-3 ml-0.5">
                                        <polygon points="5 3 19 12 5 21 5 3"></polygon>
                                    </svg>
                                </button>
                                <button
                                    class="flex items-center justify-center w-8 h-8 rounded-full transition-all duration-200 bg-gray-700 text-gray-300 hover:bg-red-500 hover:text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-heart w-3 h-3 ">
                                        <path
                                            d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z">
                                        </path>
                                    </svg>
                                </button>
                                <button
                                    class="flex items-center justify-center w-8 h-8 rounded-full transition-all duration-200 bg-gray-700 text-gray-300 hover:bg-green-500 hover:text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-rotate-ccw w-3 h-3">
                                        <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"></path>
                                        <path d="M3 3v5h5"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <!-- Mobile Stats -->
                        <div class="md:hidden col-span-12 pt-2 flex justify-between text-xs text-gray-400">
                            <span>Reach: 860</span>
                            <span>Reposts: 310</span>
                        </div>
                    </div>

                    <!-- Track 20 -->
                    <div class="grid grid-cols-12 gap-4 p-4 hover:bg-gray-700 transition-colors duration-200">
                        <div class="col-span-1 flex items-center">
                            <div
                                class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold bg-gray-700 text-gray-300">
                                20</div>
                        </div>
                        <div class="col-span-4 md:col-span-4 flex items-center gap-3">
                            <div class="relative group cursor-pointer">
                                <img src="https://images.pexels.com/photos/1105666/pexels-photo-1105666.jpeg?auto=compress&amp;cs=tinysrgb&amp;w=300"
                                    alt="Electric Pulse"
                                    class="w-12 h-12 rounded-lg object-cover transition-transform duration-300 group-hover:scale-105">
                                <div
                                    class="absolute inset-0 bg-opacity-0 group-hover:bg-opacity-30 rounded-lg transition-all duration-300 flex items-center justify-center">
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
                                    class="font-semibold text-white truncate cursor-pointer hover:text-orange-400 transition-colors">
                                    Electric Pulse</h3>
                                <p class="text-sm text-gray-400 truncate">Voltage Drop</p>
                            </div>
                        </div>
                        <div
                            class="col-span-2 md:col-span-2 flex items-center justify-center cursor-pointer hover:text-orange-400 transition-colors">
                            <span class="font-bold text-orange-400">4.2/10</span>
                        </div>
                        <div class="hidden md:col-span-2 md:flex md:items-center md:justify-center">
                            <span class="text-gray-400">800</span>
                        </div>
                        <div class="hidden md:col-span-2 md:flex md:items-center md:justify-center">
                            <span class="text-gray-400">290</span>
                        </div>
                        <div class="col-span-5 md:col-span-1 flex items-center justify-center">
                            <div class="flex items-center gap-1">
                                <button
                                    class="flex items-center justify-center w-8 h-8 rounded-full transition-all duration-200 bg-gray-700 text-gray-300 hover:bg-orange-500 hover:text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-play w-3 h-3 ml-0.5">
                                        <polygon points="5 3 19 12 5 21 5 3"></polygon>
                                    </svg>
                                </button>
                                <button
                                    class="flex items-center justify-center w-8 h-8 rounded-full transition-all duration-200 bg-gray-700 text-gray-300 hover:bg-red-500 hover:text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-heart w-3 h-3 ">
                                        <path
                                            d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z">
                                        </path>
                                    </svg>
                                </button>
                                <button
                                    class="flex items-center justify-center w-8 h-8 rounded-full transition-all duration-200 bg-gray-700 text-gray-300 hover:bg-green-500 hover:text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-rotate-ccw w-3 h-3">
                                        <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"></path>
                                        <path d="M3 3v5h5"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <!-- Mobile Stats -->
                        <div class="md:hidden col-span-12 pt-2 flex justify-between text-xs text-gray-400">
                            <span>Reach: 800</span>
                            <span>Reposts: 290</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-12 text-center">
                <div class="dark:bg-gray-800 rounded-2xl p-8 shadow-lg border border-gray-700">
                    <h3 class="text-xl font-bold dark:text-white  mb-4">Want to see your track here?</h3>
                    <p class=" mb-6 dark:text-white">Join our repost campaigns and boost your engagement to climb the
                        charts!</p>
                    <div class="flex flex-col sm:flex-row gap-3 justify-center"><button
                            class="bg-orange-500 text-white px-6 py-3 rounded-xl font-semibold hover:bg-orange-600 transition-all duration-200 transform hover:scale-105">Start
                            Campaign</button><button
                            class="border border-gray-600 dark:text-white px-6 py-3 rounded-xl font-semibold hover:bg-gray-700 transition-all duration-200">Learn
                            More</button></div>
                </div>
            </div>

        </div>
    </div>

</div>
