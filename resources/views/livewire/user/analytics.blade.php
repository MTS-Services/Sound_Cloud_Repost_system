<div>
    <x-slot name="page_slug">analytics</x-slot>

    <!-- Loading Overlay -->
    {{-- <div x-data="{ isLoading: @entangle('isLoading') }" x-show="isLoading" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-white/80 dark:bg-gray-900/80 backdrop-blur-sm z-50 flex items-center justify-center"
        style="display: none;">
        <div class="text-center">
            <div
                class="inline-flex items-center px-6 py-3 font-medium leading-6 text-sm shadow rounded-lg text-white bg-[#ff6b35] hover:bg-[#ff8c42] transition ease-in-out duration-150">
                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg"
                    fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                        stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                    </path>
                </svg>
                Refreshing Analytics...
            </div>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Please wait while we update your data</p>
        </div>
    </div> --}}

    <div x-data="{
        showGrowthTips: @entangle('showGrowthTips').live,
        showFilters: @entangle('showFilters').live,
        selectedFilter: '{{ $filter }}',
        dataCache: {{ Js::from($dataCache) }},
        displayedData: null,
        chartData: {{ Js::from($this->getChartData()) }},
        genreBreakdown: {{ Js::from($genreBreakdown) }},
        isLoading: @entangle('isLoading'),
    
        // Chart instances
        performanceChart: null,
        genreChart: null,
    
        changeFilter(newFilter) {
            let cacheKey = newFilter;
            if (newFilter === 'date_range') {
                cacheKey = newFilter + '_' + $wire.startDate + '_' + $wire.endDate;
            }
    
            if (this.dataCache[cacheKey]) {
                this.displayedData = this.dataCache[cacheKey];
            } else {
                this.displayedData = {
                    overall_metrics: {
                        total_plays: { current_total: 'Loading...', change_rate: null },
                        total_likes: { current_total: 'Loading...', change_rate: null },
                        total_reposts: { current_total: 'Loading...', change_rate: null },
                        total_comments: { current_total: 'Loading...', change_rate: null },
                        total_views: { current_total: 'Loading...', change_rate: null },
                        total_followers: { current_total: 'Loading...', change_rate: null },
                    },
                };
            }
    
            this.selectedFilter = newFilter;
            $wire.set('filter', newFilter);
        },
    
        // Main chart initialization function
        initializeCharts() {
            this.destroyExistingCharts();
    
            // Wait for next tick to ensure DOM is ready
            this.$nextTick(() => {
                if (typeof Chart !== 'undefined') {
                    this.initPerformanceChart();
                    this.initGenreChart();
                } else {
                    this.waitForChart();
                }
            });
        },
    
        // Helper function to wait for Chart.js to load
        waitForChart() {
            const checkChart = () => {
                if (typeof Chart !== 'undefined') {
                    this.initPerformanceChart();
                    this.initGenreChart();
                } else {
                    setTimeout(checkChart, 100);
                }
            };
            checkChart();
        },
    
        // Destroy existing charts to prevent memory leaks
        destroyExistingCharts() {
            if (this.performanceChart instanceof Chart) {
                this.performanceChart.destroy();
                this.performanceChart = null;
            }
            if (this.genreChart instanceof Chart) {
                this.genreChart.destroy();
                this.genreChart = null;
            }
        },
    
        initPerformanceChart() {
            const ctx = document.getElementById('performanceChart');
            if (!ctx) return;
    
            this.performanceChart = new Chart(ctx.getContext('2d'), {
                type: 'line',
                data: {
                    labels: this.chartData.length > 0 ? this.chartData.map((item) => {
                        const date = new Date(item.date);
                        return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
                    }) : ['No Data'],
                    datasets: [{
                        label: 'Views',
                        data: this.chartData.length > 0 ? this.chartData.map((item) => item.total_views || 0) : [0],
                        borderColor: '#E9E294',
                        backgroundColor: 'rgba(233, 226, 148, 0.1)',
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: '#E9E294',
                        pointBorderColor: '#fff',
                        pointHoverBackgroundColor: '#fff',
                        pointHoverBorderColor: '#E9E294',
                    }, {
                        label: 'Streams',
                        data: this.chartData.length > 0 ? this.chartData.map((item) => item.total_plays || 0) : [0],
                        borderColor: '#ff6b35',
                        backgroundColor: 'rgba(255, 107, 53, 0.1)',
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: '#ff6b35',
                        pointBorderColor: '#fff',
                        pointHoverBackgroundColor: '#fff',
                        pointHoverBorderColor: '#ff6b35',
                    }, {
                        label: 'Likes',
                        data: this.chartData.length > 0 ? this.chartData.map((item) => item.total_likes || 0) : [0],
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: '#10b981',
                        pointBorderColor: '#fff',
                        pointHoverBackgroundColor: '#fff',
                        pointHoverBorderColor: '#10b981',
                    }, {
                        label: 'Reposts',
                        data: this.chartData.length > 0 ? this.chartData.map((item) => item.total_reposts || 0) : [0],
                        borderColor: '#8b5cf6',
                        backgroundColor: 'rgba(139, 92, 246, 0.1)',
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: '#8b5cf6',
                        pointBorderColor: '#fff',
                        pointHoverBackgroundColor: '#fff',
                        pointHoverBorderColor: '#8b5cf6',
                    }, {
                        label: 'Comments',
                        data: this.chartData.length > 0 ? this.chartData.map((item) => item.total_comments || 0) : [0],
                        borderColor: '#f59e0b',
                        backgroundColor: 'rgba(245, 158, 11, 0.1)',
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: '#f59e0b',
                        pointBorderColor: '#fff',
                        pointHoverBackgroundColor: '#fff',
                        pointHoverBorderColor: '#f59e0b',
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                            align: 'center',
                            labels: {
                                boxWidth: 12,
                                font: {
                                    size: 12
                                }
                            }
                        },
                        tooltip: {
                            backgroundColor: '#0f172a',
                            titleColor: '#ffffff',
                            bodyColor: '#cbd5e1',
                            borderColor: '#334155',
                            borderWidth: 1,
                            padding: 12,
                            cornerRadius: 8,
                        }
                    },
                    scales: {
                        x: {
                            grid: { color: '#374151' },
                            ticks: { color: '#9ca3af' }
                        },
                        y: {
                            grid: { color: '#374151' },
                            ticks: { color: '#9ca3af' }
                        }
                    }
                }
            });
        },
    
        initGenreChart() {
            const ctx = document.getElementById('genreChart');
            if (!ctx) return;
    
            // Check if there's any data with a percentage greater than 0
            const hasData = this.genreBreakdown.some(item => item.percentage > 0);
    
            const displayedGenres = hasData ? this.genreBreakdown.filter(item => item.percentage > 0) : [{ genre: 'No Data', percentage: 100 }];
    
            this.genreChart = new Chart(ctx.getContext('2d'), {
                type: 'pie',
                data: {
                    labels: displayedGenres.map(item => item.genre),
                    datasets: [{
                        data: displayedGenres.map(item => item.percentage),
                        backgroundColor: hasData ? ['#ff6b35', '#10b981', '#8b5cf6', '#f59e0b', '#ef4444'].slice(0, displayedGenres.length) : ['#9ca3af'],
                        borderColor: '#1f2937',
                        borderWidth: 2,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return (context.label || '') + ': ' + (context.parsed || 0) + '%';
                                }
                            }
                        }
                    }
                }
            });
        },
    
        updateCharts() {
            if (this.performanceChart) {
                this.performanceChart.data.labels = this.chartData.length > 0 ? this.chartData.map((item) => {
                    const date = new Date(item.date);
                    return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
                }) : ['No Data'];
    
                const metrics = ['total_views', 'total_plays', 'total_likes', 'total_reposts', 'total_comments'];
                this.performanceChart.data.datasets.forEach((dataset, index) => {
                    dataset.data = this.chartData.length > 0 ?
                        this.chartData.map((item) => item[metrics[index]] || 0) : [0];
                });
    
                this.performanceChart.update();
            }
    
            if (this.genreChart) {
                this.genreChart.data.labels = this.genreBreakdown.length > 0 ?
                    this.genreBreakdown.map((item) => item.genre) : ['No Data'];
                this.genreChart.data.datasets[0].data = this.genreBreakdown.length > 0 ?
                    this.genreBreakdown.map((item) => item.percentage) : [100];
                this.genreChart.update();
            }
        },
    
        // Main initialization function that can be called by various events
        setupCharts() {
            this.displayedData = $wire.data;
            this.initializeCharts();
        },
    
        // Handle page refresh animations
        handlePageRefresh() {
            // Add a slight fade effect during refresh
            document.body.style.transition = 'opacity 0.2s ease-in-out';
            document.body.style.opacity = '0.8';
    
            setTimeout(() => {
                document.body.style.opacity = '1';
            }, 300);
        },
    
        init() {
            // Initial setup
            this.setupCharts();
            console.log('Genre Breakdown:', this.genreBreakdown);
    
            // Watch for data changes
            this.$watch('$wire.data', (newData, oldData) => {
                if (JSON.stringify(newData) !== JSON.stringify(oldData)) {
                    this.displayedData = newData;
                    this.dataCache = $wire.dataCache;
                }
            });
    
            // Listen for loading start
            Livewire.on('startLoading', () => {
                this.isLoading = true;
            });
    
            // Listen for complete refresh
            Livewire.on('completeRefresh', () => {
                this.handlePageRefresh();
                this.chartData = $wire.getChartData();
                this.genreBreakdown = $wire.genreBreakdown;
    
                this.$nextTick(() => {
                    this.setupCharts();
                });
            });
    
            // Listen for data updates
            Livewire.on('initialized', () => {
                this.chartData = $wire.getChartData();
                this.genreBreakdown = $wire.genreBreakdown;
    
                this.$nextTick(() => {
                    if (this.performanceChart) {
                        this.updateCharts();
                    } else {
                        this.initializeCharts();
                    }
                });
            });
        }
    }">
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
                            class="hover:!bg-[#ff6b35] hover:!text-white bg-white border-gray-300 dark:border-gray-600 w-full"
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
                            class="px-6 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white text-sm font-medium focus:ring-2 focus:ring-[#ff6b35] focus:border-[#ff6b35] w-full sm:w-auto transition-all duration-200"
                            x-bind:disabled="isLoading">
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

            {{-- Growth Tips Content (keeping original design) --}}
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
                            <h4 class="font-semibold text-gray-900 dark:text-white mb-2">Optimize Your Release Timing
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
                            <h4 class="font-semibold text-gray-900 dark:text-white mb-2">Leverage Your Top Performer
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
                        <div class="p-2 rounded-lg bg-[#ff6b35] text-white mr-4 flex-shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round"
                                class="lucide lucide-trending-up h-5 w-5">
                                <polyline points="22 7 13.5 15.5 8.5 10.5 2 17"></polyline>
                                <polyline points="16 7 22 7 22 13"></polyline>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-semibold text-gray-900 dark:text-white mb-2">Improve Underperforming Tracks
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
                    </div>
                    <button
                        class="px-4 py-2 bg-[#ff6b35] text-white rounded-lg text-sm font-medium hover:bg-[#ff8c42] transition-colors">
                        Get Premium Tips
                    </button>
                </div>
            </div>
        </div>

        {{-- Filters --}}
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
                <div class="flex-1">
                    <label class="flex items-center text-sm font-medium text-gray-600 dark:text-gray-400 mb-3">
                        <x-lucide-tags class="w-4 h-4 mr-2" />
                        Genre
                    </label>
                    <div class="space-y-2 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-1 lg:grid-cols-2">
                        <label class="flex items-center">
                            <input type="checkbox" name="genre" wire:model="selectedGenres" value="Any Genre"
                                class="checkbox border-orange-600 bg-transparent checked:border-orange-500 checked:bg-transparent checked:text-orange-600 rounded-full w-5 h-5"
                                x-bind:disabled="isLoading">
                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300 capitalize">Any Genre</span>
                        </label>
                        @foreach ($userGenres as $genre)
                            <label class="flex items-center">
                                <input type="checkbox" name="genre" wire:model="selectedGenres"
                                    value="{{ $genre }}"
                                    class="checkbox border-orange-600 bg-transparent checked:border-orange-500 checked:bg-transparent checked:text-orange-600 rounded-full w-5 h-5"
                                    x-bind:disabled="isLoading">
                                <span
                                    class="ml-2 text-sm text-gray-700 dark:text-gray-300 capitalize">{{ $genre }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
                <div class="flex-1 flex items-start justify-start flex-col">
                    <label class="flex items-center text-sm font-medium text-gray-600 dark:text-gray-400 mb-3">
                        <x-lucide-calendar-clock class="w-4 h-4 mr-2" />
                        Date Range
                    </label>
                    <div class="flex gap-4 flex-wrap sm:flex-nowrap w-full">
                        <label for="start-date" class="flex-1">
                            <span class="label text-sm">Start Date</span>
                            <input type="date" wire:model="startDate"
                                class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-sm text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-[#ff6b35] focus:border-[#ff6b35] transition-all duration-200"
                                x-bind:disabled="isLoading">
                        </label>
                        <label for="end-date" class="flex-1">
                            <span class="label text-sm">End Date</span>
                            <input type="date" wire:model="endDate"
                                class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-sm text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-[#ff6b35] focus:border-[#ff6b35] transition-all duration-200"
                                x-bind:disabled="isLoading">
                        </label>
                    </div>
                    @error('dateRange')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            <div class="flex justify-end space-x-3 mt-6 pt-4 border-t border-gray-200 dark:border-gray-600">
                <button wire:click="resetFilters"
                    class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors"
                    x-bind:disabled="isLoading">
                    <span x-show="!isLoading">Reset</span>
                    <span x-show="isLoading" class="flex items-center">
                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                        Resetting...
                    </span>
                </button>
                <button wire:click="applyFilters"
                    class="px-4 py-2 bg-[#ff6b35] text-white rounded-lg text-sm font-medium hover:bg-[#ff8c42] transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                    x-bind:disabled="isLoading">
                    <span x-show="!isLoading">Apply Filters</span>
                    <span x-show="isLoading" class="flex items-center">
                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                        Applying...
                    </span>
                </button>
            </div>
        </div>

        {{-- Analytics Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8"
            x-transition:enter="transition ease-out duration-500"
            x-transition:enter-start="transform opacity-0 translate-y-4"
            x-transition:enter-end="transform opacity-100 translate-y-0">
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
                    <p class="text-2xl font-bold text-gray-900 dark:text-white"
                        x-text="displayedData?.streams || '-'">
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
                        <x-heroicon-s-arrow-path-rounded-square class="w-6 h-6" />
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
                    <p class="text-2xl font-bold text-gray-900 dark:text-white"
                        x-text="displayedData?.reposts || '-'">
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
                    <p class="text-2xl font-bold text-gray-900 dark:text-white" {{-- x-text="(displayedData?.avgEngagementRate !== undefined ? displayedData.avgEngagementRate + '%' : '-')"> --}}>
                        {{ min($data['avgEngagementRate'], 100) ?? '-' }}%
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
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Top Performing Tracks or
                        Playlists</h3>
                    <div class="space-y-4">
                        @forelse($topSources as $source)
                            <div class="group">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="flex-1 min-w-0">
                                        <p
                                            class="text-sm font-medium text-gray-900 dark:text-white truncate group-hover:text-[#ff6b35] transition-colors">
                                            {{ $source['source']['title'] }}
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 truncate capitalize">
                                            {{ $source['source_type'] == App\Models\Track::class ? 'Track' : 'Playlist' }}
                                        </p>
                                    </div>
                                    <div class="flex items-center ml-4">
                                        <span
                                            class="text-xs text-gray-900 dark:text-white font-medium">{{ number_shorten($source['streams']) }}</span>
                                        <span class="text-xs text-gray-500 dark:text-gray-400 ml-1">streams</span>
                                    </div>
                                </div>
                                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2 mt-2">
                                    <div class="h-2 rounded-full transition-all duration-300"
                                        style="width: {{ $source['engagement_rate'] }}%; background: linear-gradient(90deg, #ff6b35, #ff6b35cc);">
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
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Genre Performance</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $this->getFilterText() }}</p>
                    </div>
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
                            <p class="text-2xl font-bold">
                                {{ $data['growth']['avgGrowth'] > 0 ? '+' : '' }}{{ number_format($data['growth']['avgGrowth'], 1) }}%
                            </p>
                            <p class="text-orange-100 text-sm">Average Growth</p>
                        </div>
                        @if ($this->getChangeIcon($data['growth']['avgGrowth']) === 'trending-up')
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round"
                                class="lucide lucide-trending-up h-10 w-10 text-orange-100">
                                <polyline points="22 7 13.5 15.5 8.5 10.5 2 17"></polyline>
                                <polyline points="16 7 22 7 22 13"></polyline>
                            </svg>
                        @elseif($this->getChangeIcon($data['growth']['avgGrowth']) === 'trending-down')
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round"
                                class="lucide lucide-trending-up h-10 w-10 text-orange-100">
                                <polyline points="22 17 13.5 8.5 8.5 13.5 2 7"></polyline>
                                <polyline points="16 17 22 17 22 11"></polyline>
                            </svg>
                        @else
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round"
                                class="mr-1 h-10 w-10 text-orange-100">
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                            </svg>
                        @endif
                    </div>
                </div>

                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="font-semibold text-gray-900 dark:text-white">Recent Achievements</h4>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $this->getFilterText() }}</p>
                    </div>
                    <div class="space-y-3">
                        @if (isset($data['detailed']) && !empty($data['detailed']))
                            @php
                                $anyAchievement =
                                    $this->getChangeIcon(
                                        $data['detailed']['overall_metrics']['total_views']['change_value'],
                                    ) === 'trending-up' ||
                                    $this->getChangeIcon(
                                        $data['detailed']['overall_metrics']['total_plays']['change_value'],
                                    ) === 'trending-up' ||
                                    $this->getChangeIcon(
                                        $data['detailed']['overall_metrics']['total_likes']['change_value'],
                                    ) === 'trending-up' ||
                                    $this->getChangeIcon(
                                        $data['detailed']['overall_metrics']['total_comments']['change_value'],
                                    ) === 'trending-up';
                            @endphp
                            @if ($anyAchievement)
                                @if ($this->getChangeIcon($data['detailed']['overall_metrics']['total_views']['change_value']) === 'trending-up')
                                    <div class="flex items-center">
                                        <div class="w-2 h-2 bg-[#ff6b35] rounded-full mr-3"></div>
                                        <span class="text-sm text-gray-600 dark:text-gray-400">Reached
                                            {{ number_shorten($data['detailed']['overall_metrics']['total_views']['current_total']) }}
                                            total
                                            views!</span>
                                    </div>
                                @endif
                                @if ($this->getChangeIcon($data['detailed']['overall_metrics']['total_plays']['change_value']) === 'trending-up')
                                    <div class="flex items-center">
                                        <div class="w-2 h-2 bg-[#ff6b35] rounded-full mr-3"></div>
                                        <span
                                            class="text-sm text-gray-600 dark:text-gray-400">{{ number_format($data['detailed']['overall_metrics']['total_plays']['change_value'], 1) }}%
                                            growth in streams this period</span>
                                    </div>
                                @endif
                                @if ($this->getChangeIcon($data['detailed']['overall_metrics']['total_likes']['change_value']) === 'trending-up')
                                    <div class="flex items-center">
                                        <div class="w-2 h-2 bg-[#ff6b35] rounded-full mr-3"></div>
                                        <span class="text-sm text-gray-600 dark:text-gray-400">Great engagement with
                                            {{ number_format($data['detailed']['overall_metrics']['total_likes']['change_value'], 1) }}%
                                            more likes</span>
                                    </div>
                                @endif
                                @if ($this->getChangeIcon($data['detailed']['overall_metrics']['total_comments']['change_value']) === 'trending-up')
                                    <div class="flex items-center">
                                        <div class="w-2 h-2 bg-[#ff6b35] rounded-full mr-3"></div>
                                        <span
                                            class="text-sm text-gray-600 dark:text-gray-400">{{ number_format($data['detailed']['overall_metrics']['total_comments']['change_value'], 1) }}%
                                            growth in comments</span>
                                    </div>
                                @endif
                            @else
                                <div class="flex items-center">
                                    <div class="w-2 h-2 bg-[#ff6b35] rounded-full mr-3"></div>
                                    <span class="text-sm text-gray-600 dark:text-gray-400">No achievements
                                        unlocked yet!</span>
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

        <!-- Track Performance Table with Pagination -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Your Tracks Performance</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Detailed analytics for all your released
                    tracks and playlists over <span
                        class="font-medium text-gray-900 dark:text-white">{{ $this->getFilterText() }} period.</span>
                </p>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                        <tr>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase tracking-wider hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                                <p class="flex items-center">Track Name</p>
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase tracking-wider hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                                <p class="flex items-center">Streams</p>
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase tracking-wider hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                                <p class="flex items-center">Stream Growth</p>
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase tracking-wider hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                                <p class="flex items-center">Engagement</p>
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase tracking-wider hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                                <p class="flex items-center">Likes</p>
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase tracking-wider hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                                <p class="flex items-center">Reposts</p>
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase tracking-wider hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                                <p class="flex items-center">Released</p>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($paginatedSources as $source)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div
                                            class="w-2 h-8 rounded-full mr-3 bg-gradient-to-b from-[#ff6b35] to-[#ff8c42]">
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $source['source_details']->title ?? 'Unknown Track' }}
                                            </div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ $source['source_details']->genre ?? 'Unknown' }} •
                                                <span
                                                    class="capitalize text-xs text-gray-500 dark:text-gray-400">{{ $source['source_type'] == App\Models\Track::class ? 'Track' : 'Playlist' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-bold text-gray-900 dark:text-white">
                                        {{ number_shorten($source['metrics']['total_views']['current_total']) }}
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">streams</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $changeRate = $source['metrics']['total_views']['change_rate'];
                                        $changeClass = $this->getChangeClass($changeRate);
                                        $changeIcon = $this->getChangeIcon($changeRate);
                                    @endphp
                                    <div class="inline-flex items-center text-sm font-medium {{ $changeClass }}">
                                        @if ($changeIcon === 'trending-up')
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-trending-up h-4 w-4 mr-1">
                                                <polyline points="22 7 13.5 15.5 8.5 10.5 2 17"></polyline>
                                                <polyline points="16 7 22 7 22 13"></polyline>
                                            </svg>
                                        @elseif($changeIcon === 'trending-down')
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
                                        {{ number_format(abs($changeRate), 1) }}%
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="text-sm font-bold text-gray-900 dark:text-white">
                                            {{ number_format($source['engagement_rate'], 2) }}%
                                        </div>
                                        <div class="ml-2 w-16 bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                            <div class="bg-gradient-to-r from-[#ff6b35] to-[#ff8c42] h-2 rounded-full transition-all duration-300 max-w-full"
                                                style="width: {{ $source['engagement_rate'] }}%;"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    {{ number_shorten($source['metrics']['total_likes']['current_total']) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    {{ number_shorten($source['metrics']['total_reposts']['current_total']) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 dark:text-white">
                                        {{ Carbon\Carbon::parse($source['source_type'] == App\Models\Track::class ? $source['source_details']->created_at_soundcloud : $source['source_details']->soundcloud_created_at)->format('d M, Y h:i A') ?? 'Unknown' }}
                                    </div>
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
                                        <p class="text-lg font-medium">No tracks or playlists found</p>
                                        <p class="text-sm mt-2">Upload your first track or playlist to start tracking
                                            performance!
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($paginatedSources instanceof \Illuminate\Pagination\LengthAwarePaginator && $paginatedSources->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center text-sm text-gray-500 dark:text-gray-400">
                            Showing {{ $paginatedSources->firstItem() ?? 0 }} to
                            {{ $paginatedSources->lastItem() ?? 0 }}
                            of {{ $paginatedSources->total() }} source
                        </div>
                        <div class="flex items-center space-x-2">
                            @if ($paginatedSources->onFirstPage())
                                <span
                                    class="px-3 py-2 text-sm text-gray-400 dark:text-gray-500 cursor-not-allowed">Previous</span>
                            @else
                                <button wire:click="previousPage('{{ $pageName }}')"
                                    class="px-3 py-2 text-sm text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                                    Previous
                                </button>
                            @endif
                            <div class="flex items-center space-x-1">
                                @php
                                    $start = max(1, $paginatedSources->currentPage() - 2);
                                    $end = min($paginatedSources->lastPage(), $paginatedSources->currentPage() + 2);
                                @endphp

                                @if ($start > 1)
                                    <button wire:click="gotoPage(1, '{{ $pageName }}')"
                                        class="px-3 py-2 text-sm text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                                        1
                                    </button>
                                    @if ($start > 2)
                                        <span class="px-2 text-gray-400">...</span>
                                    @endif
                                @endif

                                @for ($page = $start; $page <= $end; $page++)
                                    @if ($page == $paginatedSources->currentPage())
                                        <span
                                            class="px-3 py-2 text-sm bg-[#ff6b35] text-white rounded-lg">{{ $page }}</span>
                                    @else
                                        <button wire:click="gotoPage({{ $page }}, '{{ $pageName }}')"
                                            class="px-3 py-2 text-sm text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                                            {{ $page }}
                                        </button>
                                    @endif
                                @endfor

                                @if ($end < $paginatedSources->lastPage())
                                    @if ($end < $paginatedSources->lastPage() - 1)
                                        <span class="px-2 text-gray-400">...</span>
                                    @endif
                                    <button wire:click="gotoPage({{ $paginatedSources->lastPage() }}, '{{ $pageName }}')"
                                        class="px-3 py-2 text-sm text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                                        {{ $paginatedSources->lastPage() }}
                                    </button>
                                @endif
                            </div>

                            @if ($paginatedSources->hasMorePages())
                                <button wire:click="nextPage('{{ $pageName }}')"
                                    class="px-3 py-2 text-sm text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                                    Next
                                </button>
                            @else
                                <span
                                    class="px-3 py-2 text-sm text-gray-400 dark:text-gray-500 cursor-not-allowed">Next</span>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>

        @push('js')
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

            <script>
                function initializeAnalyticsCharts() {
                    const alpineComponent = document.querySelector('[x-data]').__x?.$data;
                    if (alpineComponent && typeof alpineComponent.setupCharts === 'function') {
                        alpineComponent.setupCharts();
                    }
                }
                document.addEventListener('livewire:navigated', initializeAnalyticsCharts);
                document.addEventListener('livewire:load', initializeAnalyticsCharts);
                document.addEventListener('livewire:initialized', initializeAnalyticsCharts);
                document.addEventListener('DOMContentLoaded', initializeAnalyticsCharts);
                document.addEventListener('turbo:load', initializeAnalyticsCharts);
                document.addEventListener('turbo:render', initializeAnalyticsCharts);
            </script>
        @endpush

    </div>
</div>
