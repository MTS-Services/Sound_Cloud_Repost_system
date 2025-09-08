<div x-data="{
    showGrowthTips: @entangle('showGrowthTips').live,
    showFilters: @entangle('showFilters').live,

    // Optimistic UI properties
    selectedFilter: '{{ $filter }}',

    // Initialize dataCache with the initial data from Livewire
    dataCache: {{ Js::from($dataCache) }}, // Initialize the cache with existing cache
    displayedData: null,

    // Alpine method to handle the filter change instantly
    changeFilter(newFilter) {
        // Create cache key
        let cacheKey = newFilter;
        if (newFilter === 'date_range') {
            cacheKey = newFilter + '_' + $wire.startDate + '_' + $wire.endDate;
        }

        // Optimistic UI Update: Check if we have a cached version
        if (this.dataCache[cacheKey]) {
            this.displayedData = this.dataCache[cacheKey];
            console.log('Using cached data for filter:', newFilter);
        } else {
            // Show loading state while we wait
            this.displayedData = {
                streams: 'Loading...',
                likes: 'Loading...',
                reposts: 'Loading...',
                avgEngagementRate: 'Loading...'
            };
        }

        // Update the Alpine property
        this.selectedFilter = newFilter;

        // Dispatch the filter change to Livewire
        $wire.set('filter', newFilter);
    },

    // A Livewire hook to update our Alpine data when the server response arrives
    init() {
        // Set initial displayedData with the current Livewire data
        this.displayedData = $wire.data;
        console.log('Initial data:', this.displayedData);

        this.$watch('$wire.data', (newData) => {
            // This runs after the server request is complete
            this.displayedData = newData;
            // Update the dataCache
            this.dataCache = $wire.dataCache;
        });

        this.$watch('$wire.dataCache', (newCache) => {
            this.dataCache = newCache;
        });
    }
}">
    <x-slot name="page_slug">analytics</x-slot>

    <div class="border-b border-gray-200 dark:border-gray-700 mb-6">
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
                    <x-gbutton variant="outline"
                        class="hover:!bg-[#ff6b35] hover:text-white bg-white border-gray-300 dark:border-gray-600 w-full"
                        x-bind:class="showGrowthTips
                            ?
                            '!bg-[#ff6b35] !text-white' :
                            '!bg-white dark:!bg-gray-800 !text-gray-700 dark:!text-gray-300'"
                        x-on:click="showGrowthTips = !showGrowthTips, showFilters = false">
                        <x-heroicon-o-light-bulb class="w-5 h-5 mr-2" />
                        Growth Tips
                    </x-gbutton>
                    <button x-on:click="showFilters = !showFilters, showGrowthTips = false"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors w-full sm:w-auto justify-center"
                        x-bind:class="showFilters ? '!bg-[#ff6b35] !text-white' : ''">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="lucide lucide-filter h-4 w-4 mr-2">
                            <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
                        </svg>
                        Filters
                    </button>

                    <select wire:model.live="filter"
                        class="px-6 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white text-sm font-medium focus:ring-2 focus:ring-[#ff6b35] focus:border-[#ff6b35] w-full sm:w-auto">
                        <option value="daily">Today</option>
                        <option value="last_week" selected>Last 7 Days</option>
                        <option value="last_month">Last 30 Days</option>
                        <option value="last_90_days">Last 90 Days</option>
                        <option value="last_year">Last Year</option>
                        <option value="date_range">Custom Range</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    {{-- Custom Date Range --}}
    @if ($filter === 'date_range')
        <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Custom Date Range</h3>
            <div class="flex flex-col sm:flex-row gap-4">
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Start Date</label>
                    <input type="date" wire:model.live="startDate"
                        class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-sm text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-[#ff6b35] focus:border-[#ff6b35]">
                </div>
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">End Date</label>
                    <input type="date" wire:model.live="endDate"
                        class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-sm text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-[#ff6b35] focus:border-[#ff6b35]">
                </div>
            </div>
            @error('dateRange')
                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>
    @endif

    {{-- Growth Tips --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6 mt-6"
        x-show="showGrowthTips" x-cloak x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="transform opacity-0" x-transition:enter-end="transform opacity-100"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="transform opacity-100"
        x-transition:leave-end="transform opacity-0">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-2">
                <x-heroicon-s-light-bulb class="w-6 h-6 text-[#ff6b35]" />
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Growth Tips for Artists</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Personalized recommendations to boost
                        your music career</p>
                </div>
            </div>
            <button class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors"
                x-on:click="showGrowthTips = false">
                <x-lucide-x class="w-6 h-6 text-gray-400" />
            </button>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
            <div
                class="bg-gray-50 dark:bg-gray-700 rounded-lg p-5 shadow-sm border border-gray-100 dark:border-gray-600 hover:shadow-md transition-shadow">
                <div class="flex items-start">
                    <div class="p-2 rounded-lg bg-[#ff6b35] text-white mr-4 flex-shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="lucide lucide-target h-5 w-5">
                            <circle cx="12" cy="12" r="10"></circle>
                            <circle cx="12" cy="12" r="6"></circle>
                            <circle cx="12" cy="12" r="2"></circle>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h4 class="font-semibold text-gray-900 dark:text-white mb-2">Optimize Your Release
                            Timing
                        </h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">Your tracks perform 40% better
                            when released on Fridays. Your audience is most active between 6-8 PM.</p>
                        <div class="bg-gray-100 dark:bg-gray-600 rounded-lg p-3">
                            <p class="text-sm font-medium text-[#ff6b35]">💡 Action Step:</p>
                            <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">Schedule your next release
                                for Friday at 6 PM and promote it 2 days in advance on social media.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div
                class="bg-gray-50 dark:bg-gray-700 rounded-lg p-5 shadow-sm border border-gray-100 dark:border-gray-600 hover:shadow-md transition-shadow">
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
                        <h4 class="font-semibold text-gray-900 dark:text-white mb-2">Boost Your Electronic
                            Tracks
                        </h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">Your Electronic genre tracks
                            have the highest engagement rate. Focus more content in this style.</p>
                        <div class="bg-gray-100 dark:bg-gray-600 rounded-lg p-3">
                            <p class="text-sm font-medium text-[#ff6b35]">💡 Action Step:</p>
                            <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">Create 2-3 more Electronic
                                tracks this month and cross-promote them with your existing hits.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div
                class="bg-gray-50 dark:bg-gray-700 rounded-lg p-5 shadow-sm border border-gray-100 dark:border-gray-600 hover:shadow-md transition-shadow">
                <div class="flex items-start">
                    <div class="p-2 rounded-lg bg-[#ff6b35] text-white mr-4 flex-shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="lucide lucide-music h-5 w-5">
                            <path d="M9 18V5l12-2v13"></path>
                            <circle cx="6" cy="18" r="3"></circle>
                            <circle cx="18" cy="16" r="3"></circle>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h4 class="font-semibold text-gray-900 dark:text-white mb-2">Leverage Your Top
                            Performer
                        </h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">Your top track is gaining
                            momentum. Use its success to promote other tracks.</p>
                        <div class="bg-gray-100 dark:bg-gray-600 rounded-lg p-3">
                            <p class="text-sm font-medium text-[#ff6b35]">💡 Action Step:</p>
                            <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">Create a remix or acoustic
                                version and mention it in your other track descriptions.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div
                class="bg-gray-50 dark:bg-gray-700 rounded-lg p-5 shadow-sm border border-gray-100 dark:border-gray-600 hover:shadow-md transition-shadow">
                <div class="flex items-start">
                    <div class="p-2 rounded-lg bg-[#ff6b35] text-white mr-4 flex-shrink-0"><svg
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="lucide lucide-trending-up h-5 w-5">
                            <polyline points="22 7 13.5 15.5 8.5 10.5 2 17"></polyline>
                            <polyline points="16 7 22 7 22 13"></polyline>
                        </svg></div>
                    <div class="flex-1">
                        <h4 class="font-semibold text-gray-900 dark:text-white mb-2">Improve Underperforming
                            Tracks
                        </h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">Some tracks need fresh
                            promotion strategies to regain momentum.</p>
                        <div class="bg-gray-100 dark:bg-gray-600 rounded-lg p-3">
                            <p class="text-sm font-medium text-[#ff6b35]">💡 Action Step:</p>
                            <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">Create behind-the-scenes
                                content and collaborate with other artists for remixes.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-100 dark:border-gray-600">
            <div class="flex items-center justify-between">
                <div>
                    <h4 class="font-semibold text-gray-900 dark:text-white">Want More Personalized Tips?</h4>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Get AI-powered recommendations based on
                        your specific performance data</p>
                </div><button
                    class="px-4 py-2 bg-[#ff6b35] text-white rounded-lg text-sm font-medium hover:bg-[#ff8c42] transition-colors">Get
                    Premium Tips</button>
            </div>
        </div>
    </div>

    {{-- Filters  --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6"
        x-show="showFilters" x-cloak x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="transform opacity-0" x-transition:enter-end="transform opacity-100"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="transform opacity-100"
        x-transition:leave-end="transform opacity-0">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Filters</h3>
            <button class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors"
                x-on:click="showFilters = false">
                <x-lucide-x class="w-6 h-6 text-gray-400" />
            </button>
        </div>
        <div class="flex flex-wrap space-y-4">
            <div class="flex-1 md:flex-1/5">
                <label class="flex items-center text-sm font-medium text-gray-600 dark:text-gray-400 mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="lucide lucide-tag h-4 w-4 mr-2">
                        <path
                            d="M12.586 2.586A2 2 0 0 0 11.172 2H4a2 2 0 0 0-2 2v7.172a2 2 0 0 0 .586 1.414l8.704 8.704a2.426 2.426 0 0 0 3.42 0l6.58-6.58a2.426 2.426 0 0 0 0-3.42z">
                        </path>
                        <circle cx="7.5" cy="7.5" r=".5" fill="currentColor"></circle>
                    </svg>
                    Genre
                </label>
                <div class="space-y-2">
                    @foreach (['Electronic', 'Hip Hop', 'Pop', 'Rock', 'R&B', 'Indie'] as $genre)
                        <label class="flex items-center">
                            <input type="checkbox" name="genre" wire:model="selectedGenres"
                                value="{{ $genre }}"
                                class="checkbox border-orange-600 bg-transparent checked:border-orange-500 checked:bg-transparent checked:text-orange-600 rounded-full w-5 h-5">
                            <span
                                class="ml-2 text-sm text-gray-700 dark:text-gray-300 capitalize">{{ $genre }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
            <div class="flex-1 md:flex-3/5 flex items-center justify-start flex-col">
                <label class="flex items-center text-sm font-medium text-gray-600 dark:text-gray-400 mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="lucide lucide-calendar h-4 w-4 mr-2">
                        <path d="M8 2v4"></path>
                        <path d="M16 2v4"></path>
                        <rect width="18" height="18" x="3" y="4" rx="2"></rect>
                        <path d="M3 10h18"></path>
                    </svg>
                    Date Range
                </label>
                <div class="space-y-2">
                    <input type="date" wire:model="startDate"
                        class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-sm text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-[#ff6b35] focus:border-[#ff6b35]">
                    <input type="date" wire:model="endDate"
                        class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-sm text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-[#ff6b35] focus:border-[#ff6b35]">
                </div>
            </div>
            <div class="flex-1 md:flex-1/5">
                <label class="flex items-center text-sm font-medium text-gray-600 dark:text-gray-400 mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="lucide lucide-tag h-4 w-4 mr-2">
                        <path
                            d="M12.586 2.586A2 2 0 0 0 11.172 2H4a2 2 0 0 0-2 2v7.172a2 2 0 0 0 .586 1.414l8.704 8.704a2.426 2.426 0 0 0 3.42 0l6.58-6.58a2.426 2.426 0 0 0 0-3.42z">
                        </path>
                        <circle cx="7.5" cy="7.5" r=".5" fill="currentColor"></circle>
                    </svg>
                    Campaign Type
                </label>
                <div class="space-y-2">
                    @foreach (['Premium Promotion', 'Social Boost', 'Radio Push', 'Playlist Placement'] as $campaign)
                        <label class="flex items-center">
                            <input type="checkbox" wire:model="selectedCampaignTypes" value="{{ $campaign }}"
                                class="checkbox border-orange-600 bg-transparent checked:border-orange-500 checked:bg-transparent checked:text-orange-600 rounded-full w-5 h-5">
                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">{{ $campaign }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="flex justify-end space-x-3 mt-6 pt-4 border-t border-gray-200 dark:border-gray-600">
            <button wire:click="resetFilters"
                class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">Reset</button>
            <button wire:click="applyFilters"
                class="px-4 py-2 bg-[#ff6b35] text-white rounded-lg text-sm font-medium hover:bg-[#ff8c42] transition-colors">Apply
                Filters</button>
        </div>
    </div>

    {{-- Analytics Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Streams Card -->
        <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 rounded-lg bg-[#ff6b35] text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="lucide lucide-play h-6 w-6">
                        <polygon points="5 3 19 12 5 21 5 3"></polygon>
                    </svg>
                </div>
                <div class="text-right">
                    @if (isset($data['streams_change']))
                        <div
                            class="inline-flex items-center text-sm font-medium {{ $this->getChangeClass($data['streams_change']) }}">
                            @if ($this->getChangeIcon($data['streams_change']) === 'trending-up')
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" class="mr-1">
                                    <polyline points="22 7 13.5 15.5 8.5 10.5 2 17"></polyline>
                                    <polyline points="16 7 22 7 22 13"></polyline>
                                </svg>
                            @elseif($this->getChangeIcon($data['streams_change']) === 'trending-down')
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" class="mr-1">
                                    <polyline points="22 17 13.5 8.5 8.5 13.5 2 7"></polyline>
                                    <polyline points="16 17 22 17 22 11"></polyline>
                                </svg>
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" class="mr-1">
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                </svg>
                            @endif
                            {{ number_format(abs($data['streams_change']), 1) }}%
                        </div>
                    @endif
                </div>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Total Streams</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white" x-text="displayedData?.streams || '-'">
                    {{ $data['streams'] ?? '-' }}
                </p>
            </div>
            <div class="mt-3 h-1 bg-gradient-to-r from-[#ff6b35] to-[#ff8c42] rounded-full"></div>
        </div>

        <!-- Total Likes Card -->
        <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 rounded-lg bg-[#ff6b35] text-white">
                    <x-heroicon-s-heart class="w-6 h-6" />
                </div>
                <div class="text-right">
                    @if (isset($data['likes_change']))
                        <div
                            class="inline-flex items-center text-sm font-medium {{ $this->getChangeClass($data['likes_change']) }}">
                            @if ($this->getChangeIcon($data['likes_change']) === 'trending-up')
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" class="mr-1">
                                    <polyline points="22 7 13.5 15.5 8.5 10.5 2 17"></polyline>
                                    <polyline points="16 7 22 7 22 13"></polyline>
                                </svg>
                            @elseif($this->getChangeIcon($data['likes_change']) === 'trending-down')
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" class="mr-1">
                                    <polyline points="22 17 13.5 8.5 8.5 13.5 2 7"></polyline>
                                    <polyline points="16 17 22 17 22 11"></polyline>
                                </svg>
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" class="mr-1">
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                </svg>
                            @endif
                            {{ number_format(abs($data['likes_change']), 1) }}%
                        </div>
                    @endif
                </div>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Total Likes</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white" x-text="displayedData?.likes || '-'">
                    {{ $data['likes'] ?? '-' }}
                </p>
            </div>
            <div class="mt-3 h-1 bg-gradient-to-r from-[#ff6b35] to-[#ff8c42] rounded-full"></div>
        </div>

        <!-- Total Reposts Card -->
        <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 rounded-lg bg-[#ff6b35] text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="lucide lucide-share2 h-6 w-6">
                        <circle cx="18" cy="5" r="3"></circle>
                        <circle cx="6" cy="12" r="3"></circle>
                        <circle cx="18" cy="19" r="3"></circle>
                        <line x1="8.59" x2="15.42" y1="13.51" y2="17.49"></line>
                        <line x1="15.41" x2="8.59" y1="6.51" y2="10.49"></line>
                    </svg>
                </div>
                <div class="text-right">
                    @if (isset($data['reposts_change']))
                        <div
                            class="inline-flex items-center text-sm font-medium {{ $this->getChangeClass($data['reposts_change']) }}">
                            @if ($this->getChangeIcon($data['reposts_change']) === 'trending-up')
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" class="mr-1">
                                    <polyline points="22 7 13.5 15.5 8.5 10.5 2 17"></polyline>
                                    <polyline points="16 7 22 7 22 13"></polyline>
                                </svg>
                            @elseif($this->getChangeIcon($data['reposts_change']) === 'trending-down')
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" class="mr-1">
                                    <polyline points="22 17 13.5 8.5 8.5 13.5 2 7"></polyline>
                                    <polyline points="16 17 22 17 22 11"></polyline>
                                </svg>
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" class="mr-1">
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                </svg>
                            @endif
                            {{ number_format(abs($data['reposts_change']), 1) }}%
                        </div>
                    @endif
                </div>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Total Reposts</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white" x-text="displayedData?.reposts || '-'">
                    {{ $data['reposts'] ?? '-' }}
                </p>
            </div>
            <div class="mt-3 h-1 bg-gradient-to-r from-[#ff6b35] to-[#ff8c42] rounded-full"></div>
        </div>

        <!-- Engagement Rate Card -->
        <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 rounded-lg bg-[#ff6b35] text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="lucide lucide-trending-up h-6 w-6">
                        <polyline points="22 7 13.5 15.5 8.5 10.5 2 17"></polyline>
                        <polyline points="16 7 22 7 22 13"></polyline>
                    </svg>
                </div>
                <div class="text-right">
                    @if (isset($data['engagement_change']))
                        <div
                            class="inline-flex items-center text-sm font-medium {{ $this->getChangeClass($data['engagement_change']) }}">
                            @if ($this->getChangeIcon($data['engagement_change']) === 'trending-up')
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" class="mr-1">
                                    <polyline points="22 7 13.5 15.5 8.5 10.5 2 17"></polyline>
                                    <polyline points="16 7 22 7 22 13"></polyline>
                                </svg>
                            @elseif($this->getChangeIcon($data['engagement_change']) === 'trending-down')
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" class="mr-1">
                                    <polyline points="22 17 13.5 8.5 8.5 13.5 2 7"></polyline>
                                    <polyline points="16 17 22 17 22 11"></polyline>
                                </svg>
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" class="mr-1">
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                </svg>
                            @endif
                            {{ number_format(abs($data['engagement_change']), 1) }}%
                        </div>
                    @endif
                </div>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Avg. Engagement Rate</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white"
                    x-text="(displayedData?.avgEngagementRate !== undefined ? displayedData.avgEngagementRate + '%' : '-')">
                    {{ $data['avgEngagementRate'] ?? '-' }}%
                </p>
            </div>
            <div class="mt-3 h-1 bg-gradient-to-r from-[#ff6b35] to-[#ff8c42] rounded-full"></div>
        </div>
    </div>

    {{-- Performance overview chart --}}
    <div
        class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 sm:p-6 mb-8">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4 sm:mb-6 gap-3">
            <div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Performance Overview</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Track your music's growth over time</p>
            </div>
            <div class="flex items-center space-x-2 text-sm text-gray-500 dark:text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M8 2v4"></path>
                    <path d="M16 2v4"></path>
                    <rect width="18" height="18" x="3" y="4" rx="2"></rect>
                    <path d="M3 10h18"></path>
                </svg>
                <span>{{ $this->getFilterText() }}</span>
            </div>
        </div>

        <!-- Legend -->
        <div class="flex flex-wrap justify-center gap-x-6 gap-y-2 text-sm mb-4 items-center">
            <div class="flex items-end">
                <div class="w-3 h-3 bg-[#E9E294] rounded-full mr-2"></div>
                <span class="text-gray-600 dark:text-gray-300">Views</span>
            </div>
            <div class="flex items-end">
                <div class="w-3 h-3 bg-[#ff6b35] rounded-full mr-2"></div>
                <span class="text-gray-600 dark:text-gray-300">Streams</span>
            </div>
            <div class="flex items-end">
                <div class="w-3 h-3 bg-[#10b981] rounded-full mr-2"></div>
                <span class="text-gray-600 dark:text-gray-300">Likes</span>
            </div>
            <div class="flex items-end">
                <div class="w-3 h-3 bg-[#8b5cf6] rounded-full mr-2"></div>
                <span class="text-gray-600 dark:text-gray-300">Reposts</span>
            </div>
            <div class="flex items-end">
                <div class="w-3 h-3 bg-[#f59e0b] rounded-full mr-2"></div>
                <span class="text-gray-600 dark:text-gray-300">Comments</span>
            </div>
        </div>

        <!-- Chart -->
        <div class="relative overflow-x-auto" style="height: 250px;">
            <canvas id="performanceChart"></canvas>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        <!-- Top Performing Tracks -->
        <div>
            <div
                class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Top Performing Tracks</h3>
                <div class="space-y-4">
                    @forelse($topTracks as $index => $track)
                        <div class="group cursor-pointer">
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex-1 min-w-0">
                                    <p
                                        class="text-sm font-medium text-gray-900 dark:text-white truncate group-hover:text-[#ff6b35] transition-colors">
                                        Track {{ $index + 1 }}
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 truncate">You</p>
                                </div>
                                <div class="flex items-center ml-4">
                                    <span
                                        class="text-xs text-gray-900 dark:text-white font-medium">{{ number_format($track['streams']) }}</span>
                                    <span class="text-xs text-gray-500 dark:text-gray-400 ml-1">streams</span>
                                </div>
                            </div>
                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2 mt-2">
                                @php
                                    $maxStreams = $topTracks[0]['streams'] ?? 1;
                                    $percentage = $maxStreams > 0 ? ($track['streams'] / $maxStreams) * 100 : 0;
                                    $colors = ['#ff6b35', '#10b981', '#8b5cf6', '#f59e0b', '#ef4444'];
                                    $color = $colors[$index % 5];
                                @endphp
                                <div class="h-2 rounded-full transition-all duration-300"
                                    style="width: {{ $percentage }}%; background: linear-gradient(90deg, {{ $color }}, {{ $color }}cc);">
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-gray-500 dark:text-gray-400 py-8">
                            <p>No track data available yet.</p>
                            <p class="text-sm mt-2">Upload some tracks to see performance analytics!</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Genre Performance -->
        <div>
            <div
                class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Genre Performance</h3>
                <div class="space-y-4">
                    <div class="relative flex justify-center" style="height: 200px;">
                        <canvas id="genreChart"></canvas>
                    </div>
                    <div class="space-y-2">
                        @forelse($genreBreakdown as $index => $genre)
                            @php
                                $colors = ['#ff6b35', '#10b981', '#8b5cf6', '#f59e0b', '#ef4444'];
                                $color = $colors[$index % 5];
                            @endphp
                            <div
                                class="flex items-center justify-between p-2 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors cursor-pointer">
                                <div class="flex items-center">
                                    <div class="w-3 h-3 rounded-full mr-3 border border-gray-200 dark:border-gray-600"
                                        style="background-color: {{ $color }};"></div>
                                    <span
                                        class="text-sm font-medium text-gray-600 dark:text-gray-300">{{ $genre['genre'] }}</span>
                                </div>
                                <span
                                    class="text-sm font-bold text-gray-900 dark:text-white">{{ $genre['percentage'] }}%</span>
                            </div>
                        @empty
                            <div class="text-center text-gray-500 dark:text-gray-400 py-8">
                                <p>No genre data available yet.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="space-y-6">
            <div class="bg-gradient-to-r from-[#ff6b35] to-[#ff8c42] rounded-xl p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-orange-100 text-sm">{{ $this->getFilterText() }}</p>
                        @php
                            $totalGrowth =
                                ($data['streams_change'] ?? 0) +
                                ($data['likes_change'] ?? 0) +
                                ($data['reposts_change'] ?? 0);
                            $avgGrowth = $totalGrowth / 3;
                        @endphp
                        <p class="text-2xl font-bold">
                            {{ $avgGrowth > 0 ? '+' : '' }}{{ number_format($avgGrowth, 1) }}%</p>
                        <p class="text-orange-100 text-sm">Average Growth</p>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="lucide lucide-trending-up h-8 w-8 text-orange-100">
                        <polyline points="22 7 13.5 15.5 8.5 10.5 2 17"></polyline>
                        <polyline points="16 7 22 7 22 13"></polyline>
                    </svg>
                </div>
            </div>

            <div
                class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h4 class="font-semibold text-gray-900 dark:text-white mb-4">Recent Achievements</h4>
                <div class="space-y-3">
                    @if (isset($data['detailed']) && !empty($data['detailed']))
                        @if (($data['detailed']['total_views']['current_total'] ?? 0) > 10000)
                            <div class="flex items-center">
                                <div class="w-2 h-2 bg-[#ff6b35] rounded-full mr-3"></div>
                                <span class="text-sm text-gray-600 dark:text-gray-400">Reached
                                    {{ number_format($data['detailed']['total_views']['current_total']) }} total
                                    streams!</span>
                            </div>
                        @endif
                        @if (($data['streams_change'] ?? 0) > 10)
                            <div class="flex items-center">
                                <div class="w-2 h-2 bg-[#ff6b35] rounded-full mr-3"></div>
                                <span
                                    class="text-sm text-gray-600 dark:text-gray-400">{{ number_format($data['streams_change'], 1) }}%
                                    growth in streams this period</span>
                            </div>
                        @endif
                        @if (($data['likes_change'] ?? 0) > 15)
                            <div class="flex items-center">
                                <div class="w-2 h-2 bg-[#ff6b35] rounded-full mr-3"></div>
                                <span class="text-sm text-gray-600 dark:text-gray-400">Great engagement with
                                    {{ number_format($data['likes_change'], 1) }}% more likes</span>
                            </div>
                        @endif
                    @else
                        <div class="flex items-center">
                            <div class="w-2 h-2 bg-[#ff6b35] rounded-full mr-3"></div>
                            <span class="text-sm text-gray-600 dark:text-gray-400">Keep creating to unlock
                                achievements!</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Track Performance Table -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Your Tracks Performance</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Detailed analytics for all your released tracks
            </p>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                    <tr>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                            <div class="flex items-center">Track Name
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="lucide lucide-chevron-up h-4 w-4 opacity-30">
                                    <path d="m18 15-6-6-6 6"></path>
                                </svg>
                            </div>
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                            <div class="flex items-center">Reposts
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="lucide lucide-chevron-up h-4 w-4 opacity-30">
                                    <path d="m18 15-6-6-6 6"></path>
                                </svg>
                            </div>
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                            <div class="flex items-center">Released
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="lucide lucide-chevron-up h-4 w-4 opacity-30">
                                    <path d="m18 15-6-6-6 6"></path>
                                </svg>
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($this->getTrackPerformanceData() as $track)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors cursor-pointer">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div
                                        class="w-2 h-8 rounded-full mr-3 bg-gradient-to-b from-[#ff6b35] to-[#ff8c42]">
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $track['name'] }}</div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ $track['genre'] }} •
                                            You</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-bold text-gray-900 dark:text-white">
                                    {{ number_format($track['streams']) }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">streams</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div
                                    class="inline-flex items-center text-sm font-medium {{ $track['stream_growth'] > 0 ? 'text-green-400' : ($track['stream_growth'] < 0 ? 'text-red-400' : 'text-gray-500') }}">
                                    @if ($track['stream_growth'] > 0)
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            class="lucide lucide-trending-up h-4 w-4 mr-1">
                                            <polyline points="22 7 13.5 15.5 8.5 10.5 2 17"></polyline>
                                            <polyline points="16 7 22 7 22 13"></polyline>
                                        </svg>
                                    @elseif($track['stream_growth'] < 0)
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            class="lucide lucide-trending-down h-4 w-4 mr-1">
                                            <polyline points="22 17 13.5 8.5 8.5 13.5 2 7"></polyline>
                                            <polyline points="16 17 22 17 22 11"></polyline>
                                        </svg>
                                    @else
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            class="lucide lucide-minus h-4 w-4 mr-1">
                                            <line x1="5" y1="12" x2="19" y2="12">
                                            </line>
                                        </svg>
                                    @endif
                                    {{ number_format(abs($track['stream_growth']), 1) }}%
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="text-sm font-bold text-gray-900 dark:text-white">
                                        {{ $track['engagement'] }}</div>
                                    <div class="ml-2 w-16 bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                        <div class="bg-gradient-to-r from-[#ff6b35] to-[#ff8c42] h-2 rounded-full transition-all duration-300"
                                            style="width: {{ $track['engagement'] }}%;"></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                {{ number_format($track['likes']) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                {{ number_format($track['reposts']) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-white">{{ $track['released'] }}</div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="text-gray-500 dark:text-gray-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 mb-4"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 19V6l12-2v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-2c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-2" />
                                    </svg>
                                    <p class="text-lg font-medium">No tracks found</p>
                                    <p class="text-sm mt-2">Upload your first track to start tracking performance!</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @push('js')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            /* livewire initialized not working  use livewire init() hook as like in alpine x-data init() method. 
             * not working list after chnage filter or other livewire action.
             * Livewire.on('init', () => { ... });
             * document.addEventListener('livewire:load', () => { ... });
             * document.addEventListener('livewire:init', () => { ... });
             * window.addEventListener('livewire:initialized', () => { ... });
             * Livewire.hook('init', () => { ... });
             *  document.addEventListener("livewire:navigated", () => { ... });
             *  Livewire.hook('message.processed', (message, component) => { ... });
             * 
             * 
             * 
             * Fixed issue. chart not updating or not initialized properly after filter change.
             */

            // document.addEventListener('livewire:init', () => {

                console.log('Livewire initialized');
                // Get chart data from Livewire
                const chartData = @js($this->getChartData());
                const genreBreakdown = @js($genreBreakdown);

                // Performance Chart
                const performanceCtx = document.getElementById('performanceChart').getContext('2d');
                const performanceChart = new Chart(performanceCtx, {
                    type: 'line',
                    data: {
                        labels: chartData.length > 0 ? chartData.map(item => {
                            const date = new Date(item.date);
                            return date.toLocaleDateString('en-US', {
                                month: 'short',
                                day: 'numeric'
                            });
                        }) : ['No Data'],
                        datasets: [{
                                label: 'Views',
                                data: chartData.length > 0 ? chartData.map(item => item.total_views || 0) :
                                    [0],
                                borderColor: '#E9E294',
                                backgroundColor: 'rgba(255, 107, 53, 0.1)',
                                tension: 0.4,
                                fill: true,
                                pointBackgroundColor: '#E9E294',
                                pointBorderColor: '#fff',
                                pointHoverBackgroundColor: '#fff',
                                pointHoverBorderColor: '#ff6b35',
                            },
                            {
                                label: 'Streams',
                                data: chartData.length > 0 ? chartData.map(item => item.total_plays || 0) :
                                    [0],
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
                                data: chartData.length > 0 ? chartData.map(item => item.total_likes || 0) :
                                    [0],
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
                                data: chartData.length > 0 ? chartData.map(item => item.total_reposts ||
                                    0) : [0],
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
                                data: chartData.length > 0 ? chartData.map(item => item.total_comments ||
                                    0) : [0],
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
                                    color: '#374151'
                                },
                                ticks: {
                                    color: '#9ca3af'
                                }
                            },
                            y: {
                                grid: {
                                    color: '#374151'
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
                        labels: genreBreakdown.length > 0 ? genreBreakdown.map(item => item.genre) : [
                            'No Data'
                        ],
                        datasets: [{
                            data: genreBreakdown.length > 0 ? genreBreakdown.map(item => item
                                .percentage) : [100],
                            backgroundColor: genreBreakdown.length > 0 ? [
                                '#ff6b35',
                                '#10b981',
                                '#8b5cf6',
                                '#f59e0b',
                                '#ef4444'
                            ].slice(0, genreBreakdown.length) : ['#9ca3af'],
                            borderColor: '#1f2937',
                            borderWidth: 2,
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
                        interaction: {
                            mode: 'nearest',
                            intersect: false
                        },
                        animation: {
                            animateScale: false,
                            animateRotate: true
                        },
                        onHover: (event, chartElement) => {
                            event.native.target.style.cursor = chartElement[0] ? 'pointer' : 'default';
                        },
                        elements: {
                            arc: {
                                hoverBackgroundColor: function(context) {
                                    const index = context.dataIndex;
                                    const colors = [
                                        '#ff8c65',
                                        '#34d399',
                                        '#a78bfa',
                                        '#fbbf24',
                                        '#f87171'
                                    ];
                                    return colors[index] || '#9ca3af';
                                },
                                hoverBorderColor: '#1f2937',
                                hoverBorderWidth: 2,
                                hoverOffset: 0
                            }
                        }
                    }
                });

                // Listen for Livewire updates to refresh charts
                Livewire.on('dataUpdated', () => {
                    // Update charts with new data
                    const newChartData = @js($this->getChartData());
                    const newGenreData = @js($genreBreakdown);

                    // Update performance chart
                    performanceChart.data.labels = newChartData.length > 0 ? newChartData.map(item => {
                        const date = new Date(item.date);
                        return date.toLocaleDateString('en-US', {
                            month: 'short',
                            day: 'numeric'
                        });
                    }) : ['No Data'];

                    performanceChart.data.datasets.forEach((dataset, index) => {
                        const metrics = ['total_views', 'total_likes', 'total_reposts',
                            'total_comments'
                        ];
                        dataset.data = newChartData.length > 0 ? newChartData.map(item => item[metrics[
                                index]] ||
                            0) : [0];
                    });

                    performanceChart.update();

                    // Update genre chart
                    genreChart.data.labels = newGenreData.length > 0 ? newGenreData.map(item => item.genre) : [
                        'No Data'
                    ];
                    genreChart.data.datasets[0].data = newGenreData.length > 0 ? newGenreData.map(item => item
                        .percentage) : [100];
                    genreChart.update();
                });
            // });
        </script>
    @endpush

</div>
