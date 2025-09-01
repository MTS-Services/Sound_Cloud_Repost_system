<div>
    <x-slot name="page_slug">analytics</x-slot>

    <div id="root" class="#">
        <div>
            <div class="border-b sticky top-0 z-10  dark:bg-gray-900">
                <div class="px-4 sm:px-6 lg:px-8">
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center py-6 gap-4">

                        <!-- Title -->
                        <div>
                            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">
                                Analytics Dashboard
                            </h1>
                            <p class="text-gray-500 dark:text-gray-400 mt-1 text-sm sm:text-base">
                                Track your music performance and grow your audience
                            </p>
                        </div>

                        <!-- Actions -->
                        <div class="flex flex-wrap sm:flex-nowrap items-center gap-3">
                            <!-- Growth Tips -->
                            <button
                                class="inline-flex items-center px-4 py-2 border border-[#ff6b35] rounded-lg text-sm font-medium text-[#ff6b35] hover:bg-[#ff6b35]  transition-colors w-full sm:w-auto justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="lucide lucide-lightbulb h-4 w-4 mr-2">
                                    <path
                                        d="M15 14c.2-1 .7-1.7 1.5-2.5 1-.9 1.5-2.2 1.5-3.5A6 6 0 0 0 6 8c0 1 .2 2.2 1.5 3.5.7.7 1.3 1.5 1.5 2.5">
                                    </path>
                                    <path d="M9 18h6"></path>
                                    <path d="M10 22h4"></path>
                                </svg>
                                Growth Tips
                            </button>

                            <!-- Filters -->
                            <button
                                class="inline-flex items-center px-4 py-2 border border-[#2a3441] rounded-lg text-sm font-medium text-gray-500 hover:bg-[#2a3441]  transition-colors w-full sm:w-auto justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="lucide lucide-filter h-4 w-4 mr-2">
                                    <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
                                </svg>
                                Filters
                            </button>

                            <!-- Select -->
                            <select
                                class="px-3 py-2 rounded-lg border border-[#2a3441] text-sm font-medium focus:ring-2 focus:ring-[#ff6b35] focus:border-[#ff6b35] w-full sm:w-auto">
                                <option value="7d">Last 7 days</option>
                                <option value="30d">Last 30 days</option>
                                <option value="90d">Last 90 days</option>
                                <option value="1y">Last year</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class=" mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div
                        class=" rounded-xl shadow-sm border border-[#2a3441] p-6 hover:shadow-md transition-shadow duration-200">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-3 rounded-lg bg-[#ff6b35] text-white"><svg xmlns="http://www.w3.org/2000/svg"
                                    width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-play h-6 w-6">
                                    <polygon points="5 3 19 12 5 21 5 3"></polygon>
                                </svg></div>
                            <div class="text-right">
                                <div class="inline-flex items-center text-sm font-medium text-green-400"><svg
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-trending-up h-4 w-4 mr-1">
                                        <polyline points="22 7 13.5 15.5 8.5 10.5 2 17"></polyline>
                                        <polyline points="16 7 22 7 22 13"></polyline>
                                    </svg>18.4%</div>
                            </div>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-400 mb-1">Total Streams</p>
                            <p class="text-2xl font-bold  dark:text-white text-gray">247,832</p>
                        </div>
                        <div class="mt-3 h-1 bg-gradient-to-r from-[#ff6b35] to-[#ff8c42] rounded-full"></div>
                    </div>
                    <div
                        class=" rounded-xl shadow-sm border border-[#2a3441] p-6 hover:shadow-md transition-shadow duration-200">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-3 rounded-lg bg-[#ff6b35] text-white"><svg xmlns="http://www.w3.org/2000/svg"
                                    width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-heart h-6 w-6">
                                    <path
                                        d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z">
                                    </path>
                                </svg></div>
                            <div class="text-right">
                                <div class="inline-flex items-center text-sm font-medium text-green-400"><svg
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-trending-up h-4 w-4 mr-1">
                                        <polyline points="22 7 13.5 15.5 8.5 10.5 2 17"></polyline>
                                        <polyline points="16 7 22 7 22 13"></polyline>
                                    </svg>23.1%</div>
                            </div>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-400 mb-1">Total Likes</p>
                            <p class="text-2xl font-bold dark:text-white text-gray">94,382</p>
                        </div>
                        <div class="mt-3 h-1 bg-gradient-to-r from-[#ff6b35] to-[#ff8c42] rounded-full"></div>
                    </div>
                    <div
                        class=" rounded-xl shadow-sm border border-[#2a3441] p-6 hover:shadow-md transition-shadow duration-200">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-3 rounded-lg bg-[#ff6b35] text-white"><svg
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="lucide lucide-share2 h-6 w-6">
                                    <circle cx="18" cy="5" r="3"></circle>
                                    <circle cx="6" cy="12" r="3"></circle>
                                    <circle cx="18" cy="19" r="3"></circle>
                                    <line x1="8.59" x2="15.42" y1="13.51" y2="17.49"></line>
                                    <line x1="15.41" x2="8.59" y1="6.51" y2="10.49"></line>
                                </svg></div>
                            <div class="text-right">
                                <div class="inline-flex items-center text-sm font-medium text-green-400"><svg
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-trending-up h-4 w-4 mr-1">
                                        <polyline points="22 7 13.5 15.5 8.5 10.5 2 17"></polyline>
                                        <polyline points="16 7 22 7 22 13"></polyline>
                                    </svg>8.7%</div>
                            </div>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-400 mb-1">Total Reposts</p>
                            <p class="text-2xl font-bold dark:text-white text-gray">18,749</p>
                        </div>
                        <div class="mt-3 h-1 bg-gradient-to-r from-[#ff6b35] to-[#ff8c42] rounded-full"></div>
                    </div>
                    <div
                        class=" rounded-xl shadow-sm border border-[#2a3441] p-6 hover:shadow-md transition-shadow duration-200">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-3 rounded-lg bg-[#ff6b35] text-white"><svg
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="lucide lucide-trending-up h-6 w-6">
                                    <polyline points="22 7 13.5 15.5 8.5 10.5 2 17"></polyline>
                                    <polyline points="16 7 22 7 22 13"></polyline>
                                </svg></div>
                            <div class="text-right">
                                <div class="inline-flex items-center text-sm font-medium text-green-400"><svg
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-trending-up h-4 w-4 mr-1">
                                        <polyline points="22 7 13.5 15.5 8.5 10.5 2 17"></polyline>
                                        <polyline points="16 7 22 7 22 13"></polyline>
                                    </svg>5.2%</div>
                            </div>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-400 mb-1">Avg. Engagement Rate</p>
                            <p class="text-2xl font-bold text-dark dark:text-white">7.3%</p>
                        </div>
                        <div
                            class="mt-3
                                h-1 bg-gradient-to-r from-[#ff6b35] to-[#ff8c42] rounded-full">
                        </div>
                    </div>
                </div>
                <div class="rounded-xl shadow-sm border border-[#2a3441] p-4 sm:p-6 mb-8">
                    <!-- Header -->
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4 sm:mb-6 gap-3">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Performance Overview</h3>
                            <p class="text-sm text-gray-400 mt-1">Track your music's growth over time</p>
                        </div>
                        <div class="flex items-center space-x-2 text-sm text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M8 2v4"></path>
                                <path d="M16 2v4"></path>
                                <rect width="18" height="18" x="3" y="4" rx="2"></rect>
                                <path d="M3 10h18"></path>
                            </svg>
                            <span>Last 30 days</span>
                        </div>
                    </div>

                    <!-- Legend -->
                    <div class="flex flex-wrap justify-center sm:justify-start gap-x-6 gap-y-2 text-sm mb-4">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-[#ff6b35] rounded-full mr-2"></div>
                            <span class="text-gray-300">Streams</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-[#10b981] rounded-full mr-2"></div>
                            <span class="text-gray-300">Likes</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-[#8b5cf6] rounded-full mr-2"></div>
                            <span class="text-gray-300">Reposts</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-[#f59e0b] rounded-full mr-2"></div>
                            <span class="text-gray-300">Comments</span>
                        </div>
                    </div>

                    <!-- Chart -->
                    <div class="relative overflow-x-auto">
                        <div class="min-w-[600px]">
                            <!-- Your full SVG chart -->
                            <!-- keep your <svg> exactly as it is -->
                            <svg width="100%" height="200" viewBox="0 0 600 200" class="w-full">
                                <!-- defs, grid lines, paths, circles remain unchanged -->
                                <!-- ... -->
                            </svg>

                            <!-- X-axis labels -->
                            <div class="flex justify-between mt-2 text-xs text-gray-400 px-6 sm:px-10">
                                <span>Jan 1</span>
                                <span>Jan 8</span>
                                <span>Jan 15</span>
                                <span>Jan 22</span>
                                <span>Jan 29</span>
                                <span>Feb 5</span>
                                <span>Feb 12</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
                    <div>
                        <div class=" rounded-xl shadow-sm border border-[#2a3441] p-6">
                            <h3 class="text-lg font-semibold text-dark dark:text-white mb-6">Top Performing Tracks</h3>
                            <div class="space-y-4">
                                <div class="group cursor-pointer">
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="flex-1 min-w-0">
                                            <p
                                                class="text-sm font-medium text-black dark:text-white truncate group-hover:text-[#ff6b35] transition-colors">
                                                Midnight Vibes</p>
                                            <p class="text-xs text-gray-400 truncate">You</p>
                                        </div>
                                        <div class="flex items-center ml-4"><span
                                                class="text-xs text-black dark:text-white font-medium">45,632</span><span
                                                class="text-xs text-gray-400 ml-1">streams</span></div>
                                    </div>
                                    <div class="w-full bg-gray-700 rounded-full h-2 mt-2">
                                        <div class="h-2 rounded-full transition-all duration-300"
                                            style="width: 100%; background: linear-gradient(90deg, rgb(255, 107, 53), rgba(255, 107, 53, 0.8));">
                                        </div>
                                    </div>
                                </div>
                                <div class="group cursor-pointer">
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="flex-1 min-w-0">
                                            <p
                                                class="text-sm font-medium text-black dark:text-white truncate group-hover:text-[#ff6b35] transition-colors">
                                                Urban Dreams</p>
                                            <p class="text-xs text-gray-400 truncate">You</p>
                                        </div>
                                        <div class="flex items-center ml-4"><span
                                                class="text-xs text-black dark:text-white font-medium">38,921</span><span
                                                class="text-xs text-gray-400 ml-1">streams</span></div>
                                    </div>
                                    <div class="w-full bg-gray-700 rounded-full h-2 mt-2">
                                        <div class="h-2 rounded-full transition-all duration-300"
                                            style="width: 85.2932%; background: linear-gradient(90deg, rgb(16, 185, 129), rgba(16, 185, 129, 0.8));">
                                        </div>
                                    </div>
                                </div>
                                <div class="group cursor-pointer">
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="flex-1 min-w-0">
                                            <p
                                                class="text-sm font-medium text-black dark:text-white truncate group-hover:text-[#ff6b35] transition-colors">
                                                Sunset Boulevard</p>
                                            <p class="text-xs text-gray-400 truncate">You</p>
                                        </div>
                                        <div class="flex items-center ml-4"><span
                                                class="text-xs text-black dark:text-white font-medium">32,154</span><span
                                                class="text-xs text-gray-400 ml-1">streams</span></div>
                                    </div>
                                    <div class="w-full bg-gray-700 rounded-full h-2 mt-2">
                                        <div class="h-2 rounded-full transition-all duration-300"
                                            style="width: 70.4637%; background: linear-gradient(90deg, rgb(139, 92, 246), rgba(139, 92, 246, 0.8));">
                                        </div>
                                    </div>
                                </div>
                                <div class="group cursor-pointer">
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="flex-1 min-w-0">
                                            <p
                                                class="text-sm font-medium text-black dark:text-white truncate group-hover:text-[#ff6b35] transition-colors">
                                                Electric Soul</p>
                                            <p class="text-xs text-gray-400 truncate">You</p>
                                        </div>
                                        <div class="flex items-center ml-4"><span
                                                class="text-xs text-black  font-medium">28,743</span><span
                                                class="text-xs text-gray-400 ml-1">streams</span></div>
                                    </div>
                                    <div class="w-full bg-gray-700 rounded-full h-2 mt-2">
                                        <div class="h-2 rounded-full transition-all duration-300"
                                            style="width: 62.9887%; background: linear-gradient(90deg, rgb(245, 158, 11), rgba(245, 158, 11, 0.8));">
                                        </div>
                                    </div>
                                </div>
                                <div class="group cursor-pointer">
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="flex-1 min-w-0">
                                            <p
                                                class="text-sm font-medium text-black dark:text-white truncate group-hover:text-[#ff6b35] transition-colors">
                                                class="text-sm font-medium  truncate group-hover:text-[#ff6b35] transition-colors">
                                                Golden Hour</p>
                                            <p class="text-xs text-gray-400 truncate">You</p>
                                        </div>
                                        <div class="flex items-center ml-4"><span
                                                class="text-xs text-black dark:text-white font-medium">24,891</span><span
                                                class="text-xs text-gray-400 ml-1">streams</span></div>
                                    </div>
                                    <div class="w-full bg-gray-700 rounded-full h-2 mt-2">
                                        <div class="h-2 rounded-full transition-all duration-300"
                                            style="width: 54.5472%; background: linear-gradient(90deg, rgb(239, 68, 68), rgba(239, 68, 68, 0.8));">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class=" rounded-xl shadow-sm border border-[#2a3441] p-6">
                            <h3 class="text-lg font-semibold dark:text-white bg-dark mb-6">Genre Performance</h3>
                            <div class="space-y-4">
                                <div class="relative flex justify-center"><svg width="200" height="200"
                                        viewBox="0 0 100 100" class="transform -rotate-90">
                                        <path d="M 50 50 L 50 10 A 40 40 0 0 1 82.3606797749979 73.51141009169892 Z"
                                            fill="#ff6b35"
                                            class="transition-all duration-300 cursor-pointer opacity-100"></path>
                                        <path
                                            d="M 50 50 L 82.3606797749979 73.51141009169892 A 40 40 0 0 1 20.841254903143533 77.38188423714755 Z"
                                            fill="#10b981"
                                            class="transition-all duration-300 cursor-pointer opacity-100"></path>
                                        <path
                                            d="M 50 50 L 20.841254903143533 77.38188423714755 A 40 40 0 0 1 14.947732798245461 30.729853035931384 Z"
                                            fill="#8b5cf6"
                                            class="transition-all duration-300 cursor-pointer opacity-100"></path>
                                        <path
                                            d="M 50 50 L 14.947732798245461 30.729853035931384 A 40 40 0 0 1 37.639320225002095 11.957739348193861 Z"
                                            fill="#f59e0b"
                                            class="transition-all duration-300 cursor-pointer opacity-100"></path>
                                        <path
                                            d="M 50 50 L 37.639320225002095 11.957739348193861 A 40 40 0 0 1 49.99999999999999 10 Z"
                                            fill="#ef4444"
                                            class="transition-all duration-300 cursor-pointer opacity-100"></path>
                                    </svg></div>
                                <div class="space-y-2">
                                    <div
                                        class="flex items-center justify-between p-2 rounded-lg hover:bg-[#2a3441] transition-colors cursor-pointer">
                                        <div class="flex items-center">
                                            <div class="w-3 h-3 rounded-full mr-3 border border-[#2a3441]"
                                                style="background-color: rgb(255, 107, 53);"></div><span
                                                class="text-sm font-medium text-gray-500 ">Electronic</span>
                                        </div><span class="text-sm font-bold text-black dark:text-white">35.0%</span>
                                    </div>
                                    <div
                                        class="flex items-center justify-between p-2 rounded-lg hover:bg-[#2a3441] transition-colors cursor-pointer">
                                        <div class="flex items-center">
                                            <div class="w-3 h-3 rounded-full mr-3 border border-[#2a3441]"
                                                style="background-color: rgb(16, 185, 129);"></div><span
                                                class="text-sm font-medium text-gray-500">Hip Hop</span>
                                        </div><span class="text-sm font-bold text-black dark:text-white">28.0%</span>
                                    </div>
                                    <div
                                        class="flex items-center justify-between p-2 rounded-lg hover:bg-[#2a3441] transition-colors cursor-pointer">
                                        <div class="flex items-center">
                                            <div class="w-3 h-3 rounded-full mr-3 border border-[#2a3441]"
                                                style="background-color: rgb(139, 92, 246);"></div><span
                                                class="text-sm font-medium text-gray-500">Pop</span>
                                        </div><span class="text-sm font-bold text-black dark:text-white">20.0%</span>
                                    </div>
                                    <div
                                        class="flex items-center justify-between p-2 rounded-lg hover:bg-[#2a3441] transition-colors cursor-pointer">
                                        <div class="flex items-center">
                                            <div class="w-3 h-3 rounded-full mr-3 border border-[#2a3441]"
                                                style="background-color: rgb(245, 158, 11);"></div><span
                                                class="text-sm font-medium text-gray-500">Indie</span>
                                        </div><span class="text-sm font-bold text-black dark:text-white">12.0%</span>
                                    </div>
                                    <div
                                        class="flex items-center justify-between p-2 rounded-lg hover:bg-[#2a3441] transition-colors cursor-pointer">
                                        <div class="flex items-center">
                                            <div class="w-3 h-3 rounded-full mr-3 border border-[#2a3441]"
                                                style="background-color: rgb(239, 68, 68);"></div><span
                                                class="text-sm font-medium text-gray-500">R&amp;B</span>
                                        </div><span class="text-sm font-bold text-black dark:text-white">5.0%</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="space-y-6">
                        <div class="bg-gradient-to-r from-[#ff6b35] to-[#ff8c42] rounded-xl p-6 text-white">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-orange-100 text-sm">This Month</p>
                                    <p class="text-2xl font-bold">+34.2%</p>
                                    <p class="text-orange-100 text-sm">Total Growth</p>
                                </div><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="lucide lucide-trending-up h-8 w-8 text-orange-100">
                                    <polyline points="22 7 13.5 15.5 8.5 10.5 2 17"></polyline>
                                    <polyline points="16 7 22 7 22 13"></polyline>
                                </svg>
                            </div>
                        </div>
                        <div class=" rounded-xl shadow-sm border border-[#2a3441] p-6">
                            <h4 class="font-semibold text-black dark:text-white mb-4">Recent Achievements</h4>
                            <div class="space-y-3">
                                <div class="flex items-center">
                                    <div class="w-2 h-2 bg-[#ff6b35] rounded-full mr-3"></div><span
                                        class="text-sm text-gray-400">Reached 10K streams on "Midnight Vibes"</span>
                                </div>
                                <div class="flex items-center">
                                    <div class="w-2 h-2 bg-[#ff6b35] rounded-full mr-3"></div><span
                                        class="text-sm text-gray-400">+500 new followers this week</span>
                                </div>
                                <div class="flex items-center">
                                    <div class="w-2 h-2 bg-[#ff6b35] rounded-full mr-3"></div><span
                                        class="text-sm text-gray-400">Featured in 3 new playlists</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class=" rounded-xl shadow-sm border border-[#2a3441]">
                    <div class="p-6 border-b border-[#2a3441]">
                        <h3 class="text-lg font-semibold text-black dark:text-white">Your Tracks Performance</h3>
                        <p class="text-sm text-gray-400 mt-1">Detailed analytics for all your released tracks</p>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead class="bg-[#2a3441] border-b border-[#3a4551]">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-[#3a4551] transition-colors">
                                        <div class="flex items-center">Track Name<svg
                                                xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-chevron-up h-4 w-4 opacity-30">
                                                <path d="m18 15-6-6-6 6"></path>
                                            </svg></div>
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-[#3a4551] transition-colors">
                                        <div class="flex items-center">Streams<svg xmlns="http://www.w3.org/2000/svg"
                                                width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round"
                                                class="lucide lucide-chevron-up h-4 w-4 opacity-30">
                                                <path d="m18 15-6-6-6 6"></path>
                                            </svg></div>
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-[#3a4551] transition-colors">
                                        <div class="flex items-center">Stream Growth<svg
                                                xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-chevron-up h-4 w-4 opacity-30">
                                                <path d="m18 15-6-6-6 6"></path>
                                            </svg></div>
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-[#3a4551] transition-colors">
                                        <div class="flex items-center">Engagement<svg
                                                xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-chevron-down h-4 w-4">
                                                <path d="m6 9 6 6 6-6"></path>
                                            </svg></div>
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-[#3a4551] transition-colors">
                                        <div class="flex items-center">Likes<svg xmlns="http://www.w3.org/2000/svg"
                                                width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round"
                                                class="lucide lucide-chevron-up h-4 w-4 opacity-30">
                                                <path d="m18 15-6-6-6 6"></path>
                                            </svg></div>
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-[#3a4551] transition-colors">
                                        <div class="flex items-center">Reposts<svg xmlns="http://www.w3.org/2000/svg"
                                                width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round"
                                                class="lucide lucide-chevron-up h-4 w-4 opacity-30">
                                                <path d="m18 15-6-6-6 6"></path>
                                            </svg></div>
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-[#3a4551] transition-colors">
                                        <div class="flex items-center">Released<svg xmlns="http://www.w3.org/2000/svg"
                                                width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round"
                                                class="lucide lucide-chevron-up h-4 w-4 opacity-30">
                                                <path d="m18 15-6-6-6 6"></path>
                                            </svg></div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class=" divide-y divide-[#2a3441]">
                                <tr class="hover:bg-[#1a2332] transition-colors cursor-pointer">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div
                                                class="w-2 h-8 rounded-full mr-3 bg-gradient-to-b from-[#ff6b35] to-[#ff8c42]">
                                            </div>
                                            <div>
                                                <div class="text-sm font-medium text-black dark:text-white">Midnight
                                                    Vibes</div>
                                                <div class="text-sm text-gray-400">Electronic • You</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-black dark:text-white">45,632</div>
                                        <div class="text-xs text-gray-400">streams</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="inline-flex items-center text-sm font-medium text-green-400"><svg
                                                xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-trending-up h-4 w-4 mr-1">
                                                <polyline points="22 7 13.5 15.5 8.5 10.5 2 17"></polyline>
                                                <polyline points="16 7 22 7 22 13"></polyline>
                                            </svg>28.4%</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="text-sm font-bold text-black dark:text-white">94</div>
                                            <div class="ml-2 w-16 bg-[#2a3441] rounded-full h-2">
                                                <div class="bg-gradient-to-r from-[#ff6b35] to-[#ff8c42] h-2 rounded-full transition-all duration-300"
                                                    style="width: 94%;"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-black dark:text-white">15,632
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-black dark:text-white">2,847
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-black dark:text-white">2024-01-15</div>
                                    </td>
                                </tr>
                                <tr class="hover:bg-[#1a2332] transition-colors cursor-pointer">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div
                                                class="w-2 h-8 rounded-full mr-3 bg-gradient-to-b from-[#ff6b35] to-[#ff8c42]">
                                            </div>
                                            <div>
                                                <div class="text-sm font-medium text-black dark:text-white">Urban
                                                    Dreams</div>
                                                <div class="text-sm text-gray-400">Hip Hop • You</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-black dark:text-white">38,921</div>
                                        <div class="text-xs text-gray-400">streams</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="inline-flex items-center text-sm font-medium text-green-400"><svg
                                                xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-trending-up h-4 w-4 mr-1">
                                                <polyline points="22 7 13.5 15.5 8.5 10.5 2 17"></polyline>
                                                <polyline points="16 7 22 7 22 13"></polyline>
                                            </svg>22.1%</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="text-sm font-bold text-black dark:text-white">87</div>
                                            <div class="ml-2 w-16 bg-[#2a3441] rounded-full h-2">
                                                <div class="bg-gradient-to-r from-[#ff6b35] to-[#ff8c42] h-2 rounded-full transition-all duration-300"
                                                    style="width: 87%;"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-black dark:text-white">12,458
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-black dark:text-white">1,923
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-black dark:text-white">2024-02-03</div>
                                    </td>
                                </tr>
                                <tr class="hover:bg-[#1a2332] transition-colors cursor-pointer">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div
                                                class="w-2 h-8 rounded-full mr-3 bg-gradient-to-b from-[#ff6b35] to-[#ff8c42]">
                                            </div>
                                            <div>
                                                <div class="text-sm font-medium text-black dark:text-white">Sunset
                                                    Boulevard</div>
                                                <div class="text-sm text-gray-400">Indie • You</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-black dark:text-white">32,154</div>
                                        <div class="text-xs text-black dark:text-white">streams</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="inline-flex items-center text-sm font-medium text-green-400"><svg
                                                xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-trending-up h-4 w-4 mr-1">
                                                <polyline points="22 7 13.5 15.5 8.5 10.5 2 17"></polyline>
                                                <polyline points="16 7 22 7 22 13"></polyline>
                                            </svg>15.8%</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="text-sm font-bold text-black dark:text-white">82</div>
                                            <div class="ml-2 w-16 bg-[#2a3441] rounded-full h-2">
                                                <div class="bg-gradient-to-r from-[#ff6b35] to-[#ff8c42] h-2 rounded-full transition-all duration-300"
                                                    style="width: 82%;"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-black dark:text-white">9,876
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-black dark:text-white">1,654
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-black dark:text-white">2024-01-28</div>
                                    </td>
                                </tr>
                                <tr class="hover:bg-[#1a2332] transition-colors cursor-pointer">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="w-2 h-8 rounded-full mr-3 bg-[#2a3441]"></div>
                                            <div>
                                                <div class="text-sm font-medium text-black dark:text-white">Electric
                                                    Soul</div>
                                                <div class="text-sm text-gray-400">Electronic • You</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-black dark:text-white">28,743</div>
                                        <div class="text-xs text-black dark:text-white">streams</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="inline-flex items-center text-sm font-medium text-green-400"><svg
                                                xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-trending-up h-4 w-4 mr-1">
                                                <polyline points="22 7 13.5 15.5 8.5 10.5 2 17"></polyline>
                                                <polyline points="16 7 22 7 22 13"></polyline>
                                            </svg>19.3%</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="text-sm font-bold text-black dark:text-white">78</div>
                                            <div class="ml-2 w-16 bg-[#2a3441] rounded-full h-2">
                                                <div class="bg-gradient-to-r from-[#ff6b35] to-[#ff8c42] h-2 rounded-full transition-all duration-300"
                                                    style="width: 78%;"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-black dark:text-white">8,765
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-black dark:text-white">1,432
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-black dark:text-white">2024-02-12</div>
                                    </td>
                                </tr>
                                <tr class="hover:bg-[#1a2332] transition-colors cursor-pointer">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="w-2 h-8 rounded-full mr-3 bg-[#2a3441]"></div>
                                            <div>
                                                <div class="text-sm font-medium text-black dark:text-white">Golden Hour
                                                </div>
                                                <div class="text-sm text-gray-400">Pop • You</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-black dark:text-white">24,891</div>
                                        <div class="text-xs text-black dark:text-white">streams</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="inline-flex items-center text-sm font-medium text-red-400"><svg
                                                xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-trending-down h-4 w-4 mr-1">
                                                <polyline points="22 17 13.5 8.5 8.5 13.5 2 7"></polyline>
                                                <polyline points="16 17 22 17 22 11"></polyline>
                                            </svg>3.2%</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="text-sm font-bold text-black dark:text-white">74</div>
                                            <div class="ml-2 w-16 bg-[#2a3441] rounded-full h-2">
                                                <div class="bg-gradient-to-r from-[#ff6b35] to-[#ff8c42] h-2 rounded-full transition-all duration-300"
                                                    style="width: 74%;"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-black dark:text-white">7,654
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-black dark:text-white">1,287
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-black dark:text-white">2024-01-08</div>
                                    </td>
                                </tr>
                                <tr class="hover:bg-[#1a2332] transition-colors cursor-pointer">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="w-2 h-8 rounded-full mr-3 bg-[#2a3441]"></div>
                                            <div>
                                                <div class="text-sm font-medium text-black dark:text-white">Bass Drop
                                                </div>
                                                <div class="text-sm text-gray-400">Hip Hop • You</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-black dark:text-white">21,567</div>
                                        <div class="text-xs text-black dark:text-white">streams</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="inline-flex items-center text-sm font-medium text-green-400"><svg
                                                xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-trending-up h-4 w-4 mr-1">
                                                <polyline points="22 7 13.5 15.5 8.5 10.5 2 17"></polyline>
                                                <polyline points="16 7 22 7 22 13"></polyline>
                                            </svg>11.7%</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="text-sm font-bold text-black dark:text-white">71</div>
                                            <div class="ml-2 w-16 bg-[#2a3441] rounded-full h-2">
                                                <div class="bg-gradient-to-r from-[#ff6b35] to-[#ff8c42] h-2 rounded-full transition-all duration-300"
                                                    style="width: 71%;"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-black dark:text-white">6,543
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-black dark:text-white">1,156
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-black dark:text-white">2024-02-20</div>
                                    </td>
                                </tr>
                                <tr class="hover:bg-[#1a2332] transition-colors cursor-pointer">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="w-2 h-8 rounded-full mr-3 bg-[#2a3441]"></div>
                                            <div>
                                                <div class="text-sm font-medium text-black dark:text-white">Acoustic
                                                    Dreams</div>
                                                <div class="text-sm text-gray-400">Indie • You</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-black dark:text-white">18,432</div>
                                        <div class="text-xs text-black dark:text-white">streams</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="inline-flex items-center text-sm font-medium text-green-400"><svg
                                                xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-trending-up h-4 w-4 mr-1">
                                                <polyline points="22 7 13.5 15.5 8.5 10.5 2 17"></polyline>
                                                <polyline points="16 7 22 7 22 13"></polyline>
                                            </svg>7.4%</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="text-sm font-bold text-black dark:text-white">68</div>
                                            <div class="ml-2 w-16 bg-[#2a3441] rounded-full h-2">
                                                <div class="bg-gradient-to-r from-[#ff6b35] to-[#ff8c42] h-2 rounded-full transition-all duration-300"
                                                    style="width: 68%;"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-black dark:text-white">5,432
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-black dark:text-white">987</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-black dark:text-white">2024-01-22</div>
                                    </td>
                                </tr>
                                <tr class="hover:bg-[#1a2332] transition-colors cursor-pointer">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="w-2 h-8 rounded-full mr-3 bg-[#2a3441]"></div>
                                            <div>
                                                <div class="text-sm font-medium text-black dark:text-white">Neon Nights
                                                </div>
                                                <div class="text-sm text-gray-400">Electronic • You</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-black dark:text-white">15,298</div>
                                        <div class="text-xs text-black dark:text-white">streams</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="inline-flex items-center text-sm font-medium text-red-400"><svg
                                                xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-trending-down h-4 w-4 mr-1">
                                                <polyline points="22 17 13.5 8.5 8.5 13.5 2 7"></polyline>
                                                <polyline points="16 17 22 17 22 11"></polyline>
                                            </svg>8.1%</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="text-sm font-bold text-black dark:text-white">65</div>
                                            <div class="ml-2 w-16 bg-[#2a3441] rounded-full h-2">
                                                <div class="bg-gradient-to-r from-[#ff6b35] to-[#ff8c42] h-2 rounded-full transition-all duration-300"
                                                    style="width: 65%;"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-black dark:text-white">4,321
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-black dark:text-white">876</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-black dark:text-white">2024-01-05</div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


</div>
