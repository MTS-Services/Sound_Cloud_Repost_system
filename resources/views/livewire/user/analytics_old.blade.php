<div x-data="{ showGrowthTips: @entangle('showGrowthTips').live, showFilters: @entangle('showFilters').live }">
    <x-slot name="page_slug">analytics</x-slot>
    <div id="root" class="#">
        <div>
            <div class="border-b dark:bg-gray-900 mb-6">
                <div class="px-4 sm:px-6 lg:px-8">
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center py-6 gap-4">
                        <div>
                            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">
                                Analytics Dashboard
                            </h1>
                            <p class="text-gray-500 dark:text-gray-400 mt-1 text-sm sm:text-base">
                                Track your music performance and grow your audience
                            </p>
                        </div>

                        <div class="flex flex-wrap sm:flex-nowrap items-center gap-3">
                            <x-gbutton variant="outline" class="hover:!bg-[#ff6b35] hover:text-white"
                                x-on:click="showGrowthTips = !showGrowthTips, showFilters = false">
                                <x-heroicon-o-light-bulb class="w-5 h-5 mr-2" />
                                Growth Tips
                            </x-gbutton>
                            <button x-on:click="showFilters = !showFilters, showGrowthTips = false"
                                class="inline-flex items-center px-4 py-2 border border-[#2a3441] rounded-lg text-sm font-medium text-gray-400 hover:bg-[#2a3441]  transition-colors w-full sm:w-auto justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="lucide lucide-filter h-4 w-4 mr-2">
                                    <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
                                </svg>
                                Filters
                            </button>

                            <select
                                class="px-6 py-2 rounded-lg border border-[#2a3441] bg-white dark:bg-[#101828] text-black dark:text-white text-sm font-medium focus:ring-2 focus:ring-[#ff6b35] focus:border-[#ff6b35] w-full sm:w-auto">
                                <option value="7d">Last 7 days</option>
                                <option value="30d">Last 30 days</option>
                                <option value="90d">Last 90 days</option>
                                <option value="1y">Last year</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Growth Tips --}}

            <div class="rounded-xl shadow-sm border border-[#2a3441] p-6 mb-6 mt-6" x-show="showGrowthTips" x-cloak
                x-transition:enter="transition ease-out duration-300" x-transition:enter-start="transform opacity-0"
                x-transition:enter-end="transform opacity-100" x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="transform opacity-100" x-transition:leave-end="transform opacity-0">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center gap-2">
                        <x-heroicon-s-light-bulb class="w-6 h-6 text-[#ff6b35]" />
                        <div>
                            <h3 class="text-lg font-semibold dark:text-white text-gray-900">Growth Tips for Artists</h3>
                            <p class="text-sm text-gray-400">Personalized recommendations to boost your
                                music career</p>
                        </div>
                    </div>
                    <button class="p-2 hover:bg-[#2a3441] rounded-lg transition-colors"
                        x-on:click="showGrowthTips = false">
                        <x-lucide-x class="w-6 h-6 text-gray-400" />
                    </button>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                    <div
                        class="dark:bg-[#2a3441] rounded-lg p-5 shadow-sm border border-[#3a4551] hover:shadow-md transition-shadow">
                        <div class="flex items-start">
                            <div class="p-2 rounded-lg bg-[#ff6b35] text-white mr-4 flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-target h-5 w-5">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <circle cx="12" cy="12" r="6"></circle>
                                    <circle cx="12" cy="12" r="2"></circle>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-semibold dark:text-white text-black mb-2">Optimize Your Release Timing
                                </h4>
                                <p class="text-sm text-gray-400 mb-3">Your tracks perform 40% better when
                                    released on Fridays. Your audience is most active between 6-8 PM.</p>
                                <div class="dark:bg-[#3a4551] rounded-lg p-3">
                                    <p class="text-sm font-medium text-[#ff6b35]">💡 Action Step:</p>
                                    <p class="text-sm text-gray-400 mt-1">Schedule your next release for
                                        Friday at 6 PM and promote it 2 days in advance on social media.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div
                        class="dark:bg-[#2a3441] rounded-lg p-5 shadow-sm border border-[#3a4551] hover:shadow-md transition-shadow">
                        <div class="flex items-start">
                            <div class="p-2 rounded-lg bg-[#ff6b35] text-white mr-4 flex-shrink-0"><svg
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-users h-5 w-5">
                                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="9" cy="7" r="4"></circle>
                                    <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                </svg></div>
                            <div class="flex-1">
                                <h4 class="font-semibold dark:text-white text-black mb-2">Boost Your Electronic Tracks
                                </h4>
                                <p class="text-sm text-gray-400 mb-3">Your Electronic genre tracks have the
                                    highest engagement rate (94%). Focus more content in this style.</p>
                                <div class="dark:bg-[#3a4551] rounded-lg p-3">
                                    <p class="text-sm font-medium text-[#ff6b35]">💡 Action Step:</p>
                                    <p class="text-sm text-gray-400 mt-1">Create 2-3 more Electronic tracks
                                        this month and cross-promote them with your existing hits.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div
                        class="dark:bg-[#2a3441] rounded-lg p-5 shadow-sm border border-[#3a4551] hover:shadow-md transition-shadow">
                        <div class="flex items-start">
                            <div class="p-2 rounded-lg bg-[#ff6b35] text-white mr-4 flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="lucide lucide-music h-5 w-5">
                                    <path d="M9 18V5l12-2v13"></path>
                                    <circle cx="6" cy="18" r="3"></circle>
                                    <circle cx="18" cy="16" r="3"></circle>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-semibold dark:text-white text-gray-900 mb-2">Leverage Your Top
                                    Performer
                                </h4>
                                <p class="text-sm text-gray-400 mb-3">"Midnight Vibes" is your breakout hit
                                    with 45K+ streams. Use its success to promote other tracks.</p>
                                <div class="dark:bg-[#3a4551] rounded-lg p-3">
                                    <p class="text-sm font-medium text-[#ff6b35]">💡 Action Step:</p>
                                    <p class="text-sm text-gray-400 mt-1">Create a remix or acoustic
                                        version of "Midnight Vibes" and mention it in your other track
                                        descriptions.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div
                        class="dark:bg-[#2a3441] rounded-lg p-5 shadow-sm border border-[#3a4551] hover:shadow-md transition-shadow">
                        <div class="flex items-start">
                            <div class="p-2 rounded-lg bg-[#ff6b35] text-white mr-4 flex-shrink-0"><svg
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="lucide lucide-trending-up h-5 w-5">
                                    <polyline points="22 7 13.5 15.5 8.5 10.5 2 17"></polyline>
                                    <polyline points="16 7 22 7 22 13"></polyline>
                                </svg></div>
                            <div class="flex-1">
                                <h4 class="font-semibold dark:text-white text-black mb-2">Improve Underperforming
                                    Tracks
                                </h4>
                                <p class="text-sm text-gray-400 mb-3">"Golden Hour" and "Neon Nights" are
                                    losing momentum. They need fresh promotion strategies.</p>
                                <div class="dark:bg-[#3a4551] rounded-lg p-3">
                                    <p class="text-sm font-medium text-[#ff6b35]">💡 Action Step:</p>
                                    <p class="text-sm text-gray-400 mt-1">Create behind-the-scenes content
                                        for these tracks and collaborate with other artists for remixes.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-6 p-4 dark:bg-[#2a3441] rounded-lg border border-[#3a4551]">
                    <div class="flex items-center justify-between">
                        <div>
                            <h4 class="font-semibold text-black dark:text-white">Want More Personalized Tips?</h4>
                            <p class="text-sm text-gray-400">Get AI-powered recommendations based on your
                                specific performance data</p>
                        </div><button
                            class="px-4 py-2 bg-[#ff6b35] text-white rounded-lg text-sm font-medium hover:bg-[#ff8c42] transition-colors">Get
                            Premium Tips</button>
                    </div>
                </div>
            </div>

            {{-- Filters  --}}
            <div class="dark:bg-[#0f1419] rounded-xl shadow-sm border border-[#2a3441] p-6 mb-6" x-show="showFilters"
                x-cloak x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="transform opacity-0" x-transition:enter-end="transform opacity-100"
                x-transition:leave="transition ease-in duration-200" x-transition:leave-start="transform opacity-100"
                x-transition:leave-end="transform opacity-0">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-white">Filters</h3>
                    <button class="p-2 hover:bg-[#2a3441] rounded-lg transition-colors"
                        x-on:click="showFilters = false">
                        <x-lucide-x class="w-6 h-6 text-gray-400" />
                    </button>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="flex items-center text-sm font-medium text-gray-400 mb-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round"
                                class="lucide lucide-tag h-4 w-4 mr-2">
                                <path
                                    d="M12.586 2.586A2 2 0 0 0 11.172 2H4a2 2 0 0 0-2 2v7.172a2 2 0 0 0 .586 1.414l8.704 8.704a2.426 2.426 0 0 0 3.42 0l6.58-6.58a2.426 2.426 0 0 0 0-3.42z">
                                </path>
                                <circle cx="7.5" cy="7.5" r=".5" fill="currentColor"></circle>
                            </svg>
                            Genre
                        </label>
                        <div class="space-y-2">
                            <label class="flex items-center">
                                <input type="checkbox" name="genre"
                                    class="checkbox border-orange-600 bg-orange-50 checked:border-orange-500 checked:bg-orange-50 checked:text-orange-600 rounded-full w-5 h-5"
                                    value="Electronic">
                                <span class="ml-2 text-sm text-gray-400 capitalize">Electronic</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="genre"
                                    class="checkbox border-orange-600 bg-orange-50 checked:border-orange-500 checked:bg-orange-50 checked:text-orange-600 rounded-full w-5 h-5"
                                    value="Hip Hop">
                                <span class="ml-2 text-sm text-gray-400 capitalize">Hip
                                    Hop</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="genre"
                                    class="checkbox border-orange-600 bg-orange-50 checked:border-orange-500 checked:bg-orange-50 checked:text-orange-600 rounded-full w-5 h-5"
                                    value="Pop">
                                <span class="ml-2 text-sm text-gray-400 capitalize">Pop</span></label><label
                                class="flex items-center">
                                <input type="checkbox" name="genre"
                                    class="checkbox border-orange-600 bg-orange-50 checked:border-orange-500 checked:bg-orange-50 checked:text-orange-600 rounded-full w-5 h-5"
                                    value="Rock">
                                <span class="ml-2 text-sm text-gray-400 capitalize">Rock</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="genre"
                                    class="checkbox border-orange-600 bg-orange-50 checked:border-orange-500 checked:bg-orange-50 checked:text-orange-600 rounded-full w-5 h-5"
                                    value="R&B">
                                <span class="ml-2 text-sm text-gray-400 capitalize">R&B</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="genre"
                                    class="checkbox border-orange-600 bg-orange-50 checked:border-orange-500 checked:bg-orange-50 checked:text-orange-600 rounded-full w-5 h-5"
                                    value="Indie">
                                <span class="ml-2 text-sm text-gray-400 ">Indie</span>
                            </label>
                        </div>
                    </div>
                    <div>
                        <label class="flex items-center text-sm font-medium text-gray-400 mb-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round"
                                class="lucide lucide-calendar h-4 w-4 mr-2">
                                <path d="M8 2v4"></path>
                                <path d="M16 2v4"></path>
                                <rect width="18" height="18" x="3" y="4" rx="2"></rect>
                                <path d="M3 10h18"></path>
                            </svg>
                            Date Range
                        </label>
                        <div class="space-y-2">
                            <input type="date"
                                class="w-full rounded-lg border-[#2a3441] dark:bg-[#0f1419] text-sm text-gray-400 focus:ring-2 focus:ring-[#ff6b35] focus:border-[#ff6b35]"
                                value="2024-01-01">
                            <input type="date"
                                class="w-full rounded-lg border-[#2a3441] dark:bg-[#0f1419] text-sm text-gray-400 focus:ring-2 focus:ring-[#ff6b35] focus:border-[#ff6b35]"
                                value="2024-12-31">
                        </div>
                    </div>
                    <div>
                        <label class="flex items-center text-sm font-medium text-gray-400 mb-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round"
                                class="lucide lucide-tag h-4 w-4 mr-2">
                                <path
                                    d="M12.586 2.586A2 2 0 0 0 11.172 2H4a2 2 0 0 0-2 2v7.172a2 2 0 0 0 .586 1.414l8.704 8.704a2.426 2.426 0 0 0 3.42 0l6.58-6.58a2.426 2.426 0 0 0 0-3.42z">
                                </path>
                                <circle cx="7.5" cy="7.5" r=".5" fill="currentColor"></circle>
                            </svg>
                            Campaign Type
                        </label>
                        <div class="space-y-2">
                            <label class="flex items-center">
                                <input type="checkbox"
                                    class="checkbox border-orange-600 bg-orange-50 checked:border-orange-500 checked:bg-orange-50 checked:text-orange-600 rounded-full w-5 h-5">
                                <span class="ml-2 text-sm text-gray-400">Premium Promotion</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox"
                                    class="checkbox border-orange-600 bg-orange-50 checked:border-orange-500 checked:bg-orange-50 checked:text-orange-600 rounded-full w-5 h-5">
                                <span class="ml-2 text-sm text-gray-400">Social Boost</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox"
                                    class="checkbox border-orange-600 bg-orange-50 checked:border-orange-500 checked:bg-orange-50 checked:text-orange-600 rounded-full w-5 h-5">
                                <span class="ml-2 text-sm text-gray-400">Radio Push</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox"
                                    class="checkbox border-orange-600 bg-orange-50 checked:border-orange-500 checked:bg-orange-50 checked:text-orange-600 rounded-full w-5 h-5">
                                <span class="ml-2 text-sm text-gray-400">Playlist Placement</span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end space-x-3 mt-6 pt-4 border-t border-[#2a3441]">
                    <button
                        class="px-4 py-2 border border-[#2a3441] rounded-lg text-sm font-medium text-gray-400 hover:bg-[#2a3441] transition-colors">Reset</button>
                    <button
                        class="px-4 py-2 bg-[#ff6b35] text-white rounded-lg text-sm font-medium hover:bg-[#ff8c42] transition-colors">Apply
                        Filters</button>
                </div>
            </div>

            <div class="#">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div
                        class=" rounded-xl shadow-sm border border-[#2a3441] p-6 hover:shadow-md transition-shadow duration-200">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-3 rounded-lg bg-[#ff6b35] text-white"><svg
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="lucide lucide-play h-6 w-6">
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
                            <div class="p-3 rounded-lg bg-[#ff6b35] text-white"><svg
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="lucide lucide-heart h-6 w-6">
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
                    <div class="flex flex-wrap justify-center  gap-x-6 gap-y-2 text-sm mb-4 items-center">
                        <div class="flex items-end">
                            <div class="w-3 h-3 bg-[#ff6b35] rounded-full mr-2"></div>
                            <span class="text-gray-300">Streams</span>
                        </div>
                        <div class="flex items-end">
                            <div class="w-3 h-3 bg-[#10b981] rounded-full mr-2"></div>
                            <span class="text-gray-300">Likes</span>
                        </div>
                        <div class="flex items-end">
                            <div class="w-3 h-3 bg-[#8b5cf6] rounded-full mr-2"></div>
                            <span class="text-gray-300">Reposts</span>
                        </div>
                        <div class="flex items-end">
                            <div class="w-3 h-3 bg-[#f59e0b] rounded-full mr-2"></div>
                            <span class="text-gray-300">Comments</span>
                        </div>
                    </div>

                    <!-- Chart -->
                    <div class="relative overflow-x-auto" style="height: 250px;">
                        <canvas id="performanceChart"></canvas>
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
                                                class="text-sm font-medium text-black dark:text-white  truncate group-hover:text-[#ff6b35] transition-colors">
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
                                <div class="relative flex justify-center" style="height: 200px;">
                                    <canvas id="genreChart"></canvas>
                                </div>
                                <div class="space-y-2">
                                    <div
                                        class="flex items-center justify-between p-2 rounded-lg hover:bg-[#2a3441] transition-colors cursor-pointer">
                                        <div class="flex items-center">
                                            <div class="w-3 h-3 rounded-full mr-3 border border-[#2a3441]"
                                                style="background-color: rgb(255, 107, 53);"></div><span
                                                class="text-sm font-medium text-gray-500 ">Electronic</span>
                                        </div><span
                                            class="text-sm font-bold text-gray-600 dark:text-white">35.0%</span>
                                    </div>
                                    <div
                                        class="flex items-center justify-between p-2 rounded-lg hover:bg-[#2a3441] transition-colors cursor-pointer">
                                        <div class="flex items-center">
                                            <div class="w-3 h-3 rounded-full mr-3 border border-[#2a3441]"
                                                style="background-color: rgb(16, 185, 129);"></div><span
                                                class="text-sm font-medium text-gray-600">Hip Hop</span>
                                        </div><span
                                            class="text-sm font-bold text-gray-600 dark:text-white">28.0%</span>
                                    </div>
                                    <div
                                        class="flex items-center justify-between p-2 rounded-lg hover:bg-[#2a3441] transition-colors cursor-pointer">
                                        <div class="flex items-center">
                                            <div class="w-3 h-3 rounded-full mr-3 border border-[#2a3441]"
                                                style="background-color: rgb(139, 92, 246);"></div><span
                                                class="text-sm font-medium text-gray-500">Pop</span>
                                        </div><span
                                            class="text-sm font-bold text-gray-600 dark:text-white">20.0%</span>
                                    </div>
                                    <div
                                        class="flex items-center justify-between p-2 rounded-lg hover:bg-[#2a3441] transition-colors cursor-pointer">
                                        <div class="flex items-center">
                                            <div class="w-3 h-3 rounded-full mr-3 border border-[#2a3441]"
                                                style="background-color: rgb(245, 158, 11);"></div><span
                                                class="text-sm font-medium text-gray-500">Indie</span>
                                        </div><span
                                            class="text-sm font-bold text-gray-600 dark:text-white">12.0%</span>
                                    </div>
                                    <div
                                        class="flex items-center justify-between p-2 rounded-lg hover:bg-[#2a3441] transition-colors cursor-pointer">
                                        <div class="flex items-center">
                                            <div class="w-3 h-3 rounded-full mr-3 border border-[#2a3441]"
                                                style="background-color: rgb(239, 68, 68);"></div><span
                                                class="text-sm font-medium text-gray-500">R&amp;B</span>
                                        </div><span class="text-sm font-bold text-gray-600 dark:text-white">5.0%</span>
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
                                                <div class="text-sm font-medium text-gray-600 dark:text-white">Midnight
                                                    Vibes</div>
                                                <div class="text-sm text-gray-400">Electronic • You</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-gray-600 dark:text-white">45,632</div>
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
                                            <div class="text-sm font-bold text-gray-600 dark:text-white">94</div>
                                            <div class="ml-2 w-16 bg-[#2a3441] rounded-full h-2">
                                                <div class="bg-gradient-to-r from-[#ff6b35] to-[#ff8c42] h-2 rounded-full transition-all duration-300"
                                                    style="width: 94%;"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-white">
                                        15,632
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-white">2,847
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-600 dark:text-white">2024-01-15</div>
                                    </td>
                                </tr>
                                <tr class="hover:bg-[#1a2332] transition-colors cursor-pointer">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div
                                                class="w-2 h-8 rounded-full mr-3 bg-gradient-to-b from-[#ff6b35] to-[#ff8c42]">
                                            </div>
                                            <div>
                                                <div class="text-sm font-medium text-gray-600 dark:text-white">Urban
                                                    Dreams</div>
                                                <div class="text-sm text-gray-600">Hip Hop • You</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-gray-600 dark:text-white">38,921</div>
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
                                            <div class="text-sm font-bold text-gray-600 dark:text-white">87</div>
                                            <div class="ml-2 w-16 bg-[#2a3441] rounded-full h-2">
                                                <div class="bg-gradient-to-r from-[#ff6b35] to-[#ff8c42] h-2 rounded-full transition-all duration-300"
                                                    style="width: 87%;"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-white">
                                        12,458
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-white">
                                        1,923
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-600 dark:text-white">2024-02-03</div>
                                    </td>
                                </tr>
                                <tr class="hover:bg-[#1a2332] transition-colors cursor-pointer">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div
                                                class="w-2 h-8 rounded-full mr-3 bg-gradient-to-b from-[#ff6b35] to-[#ff8c42]">
                                            </div>
                                            <div>
                                                <div class="text-sm font-medium text-gray-600 dark:text-white">Sunset
                                                    Boulevard</div>
                                                <div class="text-sm text-gray-400">Indie • You</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-gray-600 dark:text-white">32,154</div>
                                        <div class="text-xs text-gray-600 dark:text-white">streams</div>
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
                                            <div class="text-sm font-bold text-gray-600 dark:text-white">82</div>
                                            <div class="ml-2 w-16 bg-[#2a3441] rounded-full h-2">
                                                <div class="bg-gradient-to-r from-[#ff6b35] to-[#ff8c42] h-2 rounded-full transition-all duration-300"
                                                    style="width: 82%;"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-white">
                                        9,876
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-white">
                                        1,654
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-600 dark:text-white">2024-01-28</div>
                                    </td>
                                </tr>
                                <tr class="hover:bg-[#1a2332] transition-colors cursor-pointer">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="w-2 h-8 rounded-full mr-3 bg-[#2a3441]"></div>
                                            <div>
                                                <div class="text-sm font-medium text-gray-600 dark:text-white">
                                                    Electric
                                                    Soul</div>
                                                <div class="text-sm text-gray-400">Electronic • You</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-gray-600 dark:text-white">28,743</div>
                                        <div class="text-xs text-gray-600 dark:text-white">streams</div>
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
                                            <div class="text-sm font-bold text-gray-600 dark:text-white">78</div>
                                            <div class="ml-2 w-16 bg-[#2a3441] rounded-full h-2">
                                                <div class="bg-gradient-to-r from-[#ff6b35] to-[#ff8c42] h-2 rounded-full transition-all duration-300"
                                                    style="width: 78%;"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-white">
                                        8,765
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-white">
                                        1,432
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-600 dark:text-white">2024-02-12</div>
                                    </td>
                                </tr>
                                <tr class="hover:bg-[#1a2332] transition-colors cursor-pointer">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="w-2 h-8 rounded-full mr-3 bg-[#2a3441]"></div>
                                            <div>
                                                <div class="text-sm font-medium text-gray-600 dark:text-white">Golden
                                                    Hour
                                                </div>
                                                <div class="text-sm text-gray-400">Pop • You</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-gray-600 dark:text-white">24,891</div>
                                        <div class="text-xs text-gray-600 dark:text-white">streams</div>
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
                                            <div class="text-sm font-bold text-gray-600 dark:text-white">74</div>
                                            <div class="ml-2 w-16 bg-[#2a3441] rounded-full h-2">
                                                <div class="bg-gradient-to-r from-[#ff6b35] to-[#ff8c42] h-2 rounded-full transition-all duration-300"
                                                    style="width: 74%;"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-white">
                                        7,654
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-white">
                                        1,287
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-600 dark:text-white">2024-01-08</div>
                                    </td>
                                </tr>
                                <tr class="hover:bg-[#1a2332] transition-colors cursor-pointer">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="w-2 h-8 rounded-full mr-3 bg-[#2a3441]"></div>
                                            <div>
                                                <div class="text-sm font-medium text-gray-600 dark:text-white">Bass
                                                    Drop
                                                </div>
                                                <div class="text-sm text-gray-400">Hip Hop • You</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-gray-600 dark:text-white">21,567</div>
                                        <div class="text-xs text-gray-600 dark:text-white">streams</div>
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
                                            <div class="text-sm font-bold text-gray-600 dark:text-white">71</div>
                                            <div class="ml-2 w-16 bg-[#2a3441] rounded-full h-2">
                                                <div class="bg-gradient-to-r from-[#ff6b35] to-[#ff8c42] h-2 rounded-full transition-all duration-300"
                                                    style="width: 71%;"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-white">
                                        6,543
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-white">
                                        1,156
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-600 dark:text-white">2024-02-20</div>
                                    </td>
                                </tr>
                                <tr class="hover:bg-[#1a2332] transition-colors cursor-pointer">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="w-2 h-8 rounded-full mr-3 bg-[#2a3441]"></div>
                                            <div>
                                                <div class="text-sm font-medium text-gray-600 dark:text-white">
                                                    Acoustic
                                                    Dreams</div>
                                                <div class="text-sm text-gray-400">Indie • You</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-gray-600 dark:text-white">18,432</div>
                                        <div class="text-xs text-gray-600 dark:text-white">streams</div>
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
                                            <div class="text-sm font-bold text-gray-600 dark:text-white">68</div>
                                            <div class="ml-2 w-16 bg-[#2a3441] rounded-full h-2">
                                                <div class="bg-gradient-to-r from-[#ff6b35] to-[#ff8c42] h-2 rounded-full transition-all duration-300"
                                                    style="width: 68%;"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-white">
                                        5,432
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-white">987
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-600 dark:text-white">2024-01-22</div>
                                    </td>
                                </tr>
                                <tr class="hover:bg-[#1a2332] transition-colors cursor-pointer">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="w-2 h-8 rounded-full mr-3 bg-[#2a3441]"></div>
                                            <div>
                                                <div class="text-sm font-medium text-gray-600 dark:text-white">Neon
                                                    Nights
                                                </div>
                                                <div class="text-sm text-gray-400">Electronic • You</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-gray-600 dark:text-white">15,298</div>
                                        <div class="text-xs text-gray-600 dark:text-white">streams</div>
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
                                            <div class="text-sm font-bold text-gray-600 dark:text-white">65</div>
                                            <div class="ml-2 w-16 bg-[#2a3441] rounded-full h-2">
                                                <div class="bg-gradient-to-r from-[#ff6b35] to-[#ff8c42] h-2 rounded-full transition-all duration-300"
                                                    style="width: 65%;"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-white">
                                        4,321
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-white">876
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-600 dark:text-white">2024-01-05</div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('js')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('livewire:initialized', function() {
                const performanceCtx = document.getElementById('performanceChart').getContext('2d');
                const performanceChart = new Chart(performanceCtx, {
                    type: 'line',
                    data: {
                        labels: ['Jan 1', 'Jan 8', 'Jan 15', 'Jan 22', 'Jan 29', 'Feb 5', 'Feb 12'],
                        datasets: [{
                                label: 'Streams',
                                data: [6500, 7200, 7000, 8500, 9500, 12000, 11000],
                                borderColor: '#ff6b35',
                                backgroundColor: 'rgba(255, 107, 53, 0.1)',
                                tension: 0.4,
                                fill: true,
                                pointBackgroundColor: '#ff6b35',
                                pointBorderColor: '#fff',
                                pointHoverBackgroundColor: '#fff',
                                pointHoverBorderColor: '#ff6b35',
                            },
                            {
                                label: 'Likes',
                                data: [2000, 2200, 2100, 2500, 2800, 3200, 3100],
                                borderColor: '#10b981',
                                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                                tension: 0.4,
                                fill: true,
                                pointBackgroundColor: '#10b981',
                                pointBorderColor: '#fff',
                                pointHoverBackgroundColor: '#fff',
                                pointHoverBorderColor: '#10b981',
                            },
                            {
                                label: 'Reposts',
                                data: [500, 600, 550, 700, 800, 900, 850],
                                borderColor: '#8b5cf6',
                                backgroundColor: 'rgba(139, 92, 246, 0.1)',
                                tension: 0.4,
                                fill: true,
                                pointBackgroundColor: '#8b5cf6',
                                pointBorderColor: '#fff',
                                pointHoverBackgroundColor: '#fff',
                                pointHoverBorderColor: '#8b5cf6',
                            },
                            {
                                label: 'Comments',
                                data: [100, 120, 110, 150, 180, 220, 210],
                                borderColor: '#f59e0b',
                                backgroundColor: 'rgba(245, 158, 11, 0.1)',
                                tension: 0.4,
                                fill: true,
                                pointBackgroundColor: '#f59e0b',
                                pointBorderColor: '#fff',
                                pointHoverBackgroundColor: '#fff',
                                pointHoverBorderColor: '#f59e0b',
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            x: {
                                grid: {
                                    color: '#2a3441'
                                },
                                ticks: {
                                    color: '#9ca3af'
                                }
                            },
                            y: {
                                grid: {
                                    color: '#2a3441'
                                },
                                ticks: {
                                    color: '#9ca3af'
                                }
                            }
                        }
                    }
                });

                // Genre Chart
                const genreCtx = document.getElementById('genreChart').getContext('2d');
                const genreChart = new Chart(genreCtx, {
                    type: 'pie',
                    data: {
                        labels: ['Electronic', 'Hip Hop', 'Pop', 'Indie', 'R&B'],
                        datasets: [{
                            data: [35, 28, 20, 12, 5],
                            backgroundColor: [
                                '#ff6b35',
                                '#10b981',
                                '#8b5cf6',
                                '#f59e0b',
                                '#ef4444'
                            ],
                            borderColor: '#1f2937', // Matches dark card background
                            borderWidth: 2,
                            // hoverOffset: 8
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        let label = context.label || '';
                                        if (label) {
                                            label += ': ';
                                        }
                                        if (context.parsed !== null) {
                                            label += context.parsed + '%';
                                        }
                                        return label;
                                    }
                                }
                            }
                        },
                        // Add these settings to modify hover behavior
                        interaction: {
                            mode: 'nearest',
                            intersect: false
                        },
                        // Disable the hover scaling animation
                        animation: {
                            animateScale: false,
                            animateRotate: true
                        },
                        // Customize hover appearance
                        onHover: (event, chartElement) => {
                            event.native.target.style.cursor = chartElement[0] ? 'pointer' : 'default';
                        },
                        // Modify element hover behavior
                        elements: {
                            arc: {
                                hoverBackgroundColor: function(context) {
                                    // Get the original color and make it lighter
                                    const index = context.dataIndex;
                                    const colors = [
                                        '#ff8c65', // Lighter Electronic
                                        '#34d399', // Lighter Hip Hop
                                        '#a78bfa', // Lighter Pop
                                        '#fbbf24', // Lighter Indie
                                        '#f87171' // Lighter R&B
                                    ];
                                    return colors[index];
                                },
                                hoverBorderColor: '#1f2937',
                                hoverBorderWidth: 2,
                                // Disable the offset on hover
                                hoverOffset: 0
                            }
                        }
                    }
                });
            });
        </script>
    @endpush

</div>
