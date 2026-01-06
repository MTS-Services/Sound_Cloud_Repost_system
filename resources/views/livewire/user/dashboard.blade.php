<div x-data="{
    chartData: {{ Js::from($this->getChartData()) }},
    performanceChart: null,
    genreBreakdown: {{ Js::from($genreBreakdown) }},
    genreChart: null,

    initPerformanceChart() {
        const ctx = document.getElementById('campaignChart');
        if (!ctx) return;
        this.performanceChart = new Chart(ctx.getContext('2d'), {
            type: 'line',
            data: {
                labels: this.chartData.length > 0 ? this.chartData.map(item => {
                    const date = new Date(item.date);
                    return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
                }) : ['No Data'],
                datasets: [{
                    label: 'Followers',
                    data: this.chartData.length > 0 ? this.chartData.map((item) => item.total_followers || 0) : [0],
                    borderColor: '#f5540b',
                    backgroundColor: 'rgba(245, 158, 11, 0.1)',
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#f5540b',
                    pointBorderColor: '#fff',
                    pointHoverBackgroundColor: '#fff',
                    pointHoverBorderColor: '#f5540b',
                }, {
                    label: 'Repost Reach',
                    data: this.chartData.length > 0 ? this.chartData.map(item => item.repost_reach || 0) : [0],
                    borderColor: '#8b5cf6',
                    backgroundColor: 'rgba(139, 92, 246, 0.1)',
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#8b5cf6',
                    pointBorderColor: '#fff',
                    pointHoverBackgroundColor: '#fff',
                    pointHoverBorderColor: '#8b5cf6',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        {{-- beginAtZero: true, --}}
                        ticks: {
                            color: '#94a3b8',
                            font: {
                                size: 10
                            }
                        },
                        grid: {
                            drawBorder: false,
                        },
                    },
                    x: {
                        ticks: {
                            color: '#94a3b8',
                            font: {
                                size: 10
                            }
                        },
                        grid: {
                            drawBorder: false,
                        },
                    }
                },
                plugins: {
                    legend: {
                        position: 'top',
                        align: 'center',
                        labels: {
                            {{-- color: '#e2e8f0', --}}
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
                interaction: {
                    intersect: false,
                    mode: 'index',
                },
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
    init() {
        // Initialize charts after DOM is ready
        this.$nextTick(() => {
            if (typeof Chart !== 'undefined') {
                this.initPerformanceChart();
                this.initGenreChart();
            } else {
                // Wait for Chart.js to load
                const checkChart = () => {
                    if (typeof Chart !== 'undefined') {
                        this.initPerformanceChart();
                        this.initGenreChart();
                    } else {
                        checkChart();
                    }
                };
                checkChart();
            }
        });
    },
    
    resetCharts()
    {
        if (this.performanceChart) {
            this.performanceChart.destroy();
        }
        if (this.genreChart) {
            this.genreChart.destroy();
        }
        
        this.$nextTick(() => {
            this.init();
        });
    },
}"

@reset-charts.window="resetCharts()" x-cloak
    >
    <x-slot name="page_slug">dashboard</x-slot>

    <div id="content-dashboard" class="page-content py-2 px-2">
        <div
            class="tablet:px-2 px-0.5 py-4 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 sm:gap-0 w-full">
            <!-- Left: Title and subtitle -->
            <div class="w-full sm:w-auto">
                <h2 class="text-2xl text-black dark:text-white font-semibold mb-2">Dashboard</h2>
                <p class="dark:text-slate-300 text-gray-600">
                    Welcome back!
                    <span class="font-semibold">{{ auth()->user()->name ?? '' }}</span>.
                    Here's an overview of your RepostChain activity.
                </p>
            </div>
            <!-- Right: Buttons group -->
            <div class="flex flex-col lg:flex-row gap-3 sm:gap-2 w-full sm:w-auto">
                <!-- Earn Credits -->
                <x-gbutton variant="secondary" wire:navigate href="{{ route('user.cm.campaigns') }}">
                    <span>💰</span>{{ __('Earn Credits') }}
                </x-gbutton>

                <!-- Submit Track -->
                <x-gbutton variant="primary" wire:click="toggleCampaignsModal">
                    <span>
                        <x-lucide-plus class="inline-block text-center h-5 w-5 text-white mr-1" />
                    </span>{{ __('Start a new campaign') }}
                </x-gbutton>
                {{-- <x-gbutton variant="primary" wire:click="toggleCampaignsModal" class="mb-2">
                    <span>
                        <x-lucide-plus class="w-5 h-5 mr-1 lg:w-4 lg:h-4 xl:w-5 xl:h-5" />
                    </span>
                    <span class="text-base lg:text-sm xl:text-base">
                        {{ __('Start a new campaign') }}
                    </span>
                </x-gbutton> --}}
            </div>

        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
            <div
                class="bg-white dark:bg-slate-800 rounded-lg shadow-sm p-6 hover:-translate-y-2 transition-all duration-500 ease-in-out">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-gray-500 dark:text-white text-sm font-medium">Available Credits</h3>
                    <div class="p-2 rounded-lg bg-yellow-500/20 text-yellow-500">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="lucide lucide-zap w-5 h-5">
                            <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon>
                        </svg>
                    </div>
                </div>
                <div class="space-y-2">
                    <p class="text-2xl font-bold text-slate-700 dark:text-white">{{ number_format(userCredits(), 2) }}
                    </p>
                    @if ($creditPercentage >= 0)
                        <p class="text-sm flex items-center space-x-1 text-green-400">
                            <span>+{{ number_format($creditPercentage, 2) }}% from last week</span>
                        </p>
                    @elseif($creditPercentage < 0)
                        <p class="text-sm flex items-center space-x-1 text-red-400">
                            <span>{{ number_format($creditPercentage, 2) }}% from last week</span>
                        </p>
                    @else
                        <p class="text-sm flex items-center space-x-1 text-gray-400">
                            <span>0.00% from last week</span>
                        </p>
                    @endif
                </div>
            </div>

            <div
                class="bg-white dark:bg-slate-800 rounded-lg shadow-sm p-6 hover:-translate-y-2 transition-all duration-500 ease-in-out">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-gray-500 dark:text-white text-sm font-medium">Active Campaigns</h3>
                    <div class="p-2 rounded-lg bg-blue-500/20 text-blue-500">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="lucide lucide-music2 w-5 h-5">
                            <circle cx="8" cy="18" r="4"></circle>
                            <path d="M12 18V2l7 4"></path>
                        </svg>
                    </div>
                </div>
                <div class="space-y-2">
                    <p class="text-2xl font-bold text-slate-700 dark:text-white">{{ number_format($totalCams, 2) }}</p>
                    @if ($campaignPercentage >= 0)
                        <p class="text-sm flex items-center space-x-1 text-green-400">
                            <span>+{{ number_format($campaignPercentage, 2) }}% from last week</span>
                        </p>
                    @elseif($campaignPercentage < 0)
                        <p class="text-sm flex items-center space-x-1 text-red-400">
                            <span>{{ number_format($campaignPercentage, 2) }}% from last week</span>
                        </p>
                    @else
                        <p class="text-sm flex items-center space-x-1 text-gray-400">
                            <span>0.00% from last week</span>
                        </p>
                    @endif
                </div>
            </div>

            <div
                class="bg-white dark:bg-slate-800 rounded-lg shadow-sm p-6 hover:-translate-y-2 transition-all duration-500 ease-in-out">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-gray-500 dark:text-white text-sm font-medium">Reposts Received</h3>
                    <div class="p-2 rounded-lg bg-green-500/20 text-green-500">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="lucide lucide-repeat2 w-5 h-5">
                            <path d="m2 9 3-3 3 3"></path>
                            <path d="M13 18H7a2 2 0 0 1-2-2V6"></path>
                            <path d="m22 15-3 3-3-3"></path>
                            <path d="M11 6h6a2 2 0 0 1 2 2v10"></path>
                        </svg>
                    </div>
                </div>
                <div class="space-y-2">
                    <p class="text-2xl font-bold text-slate-700 dark:text-white">{{ number_format($totalCount, 2) }}
                    </p>
                    @if ($repostRequestPercentage >= 0)
                        <p class="text-sm flex items-center space-x-1 text-green-400">
                            <span>+{{ number_format($repostRequestPercentage, 2) }}% from last week</span>
                        </p>
                    @elseif($repostRequestPercentage < 0)
                        <p class="text-sm flex items-center space-x-1 text-red-400">
                            <span>{{ number_format($repostRequestPercentage, 2) }}% from last week</span>
                        </p>
                    @else
                        <p class="text-sm flex items-center space-x-1 text-gray-400">
                            <span>0.00% from last week</span>
                        </p>
                    @endif
                </div>
            </div>

            <div
                class="bg-white dark:bg-slate-800 rounded-lg shadow-sm p-6 hover:-translate-y-2 transition-all duration-500 ease-in-out">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-gray-500 dark:text-white text-sm font-medium">Credibility Score</h3>
                    <div class="p-2 rounded-lg bg-purple-500/20 text-purple-500">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="lucide lucide-award w-5 h-5">
                            <circle cx="12" cy="8" r="6"></circle>
                            <path d="M15.477 12.89 17 22l-5-3-5 3 1.523-9.11"></path>
                        </svg>
                    </div>
                </div>
                <div class="space-y-2">
                    <p class="text-2xl font-bold text-slate-700 dark:text-white">
                        {{ $activities_score ? number_format($activities_score, 2) : '0.00' }}%</p>
                    <p
                        class="text-sm flex items-center space-x-1 {{ $activities_change_rate >= 0 ? 'text-green-400' : 'text-red-400' }}">
                        <span>{{ $activities_change_rate >= 0 ? '+' : '-' }}{{ $activities_change_rate ?? '0.00' }}%
                            from
                            last week</span>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="p-2">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 dark:text-white">
            <!-- Left Section -->
            <div class="lg:col-span-2 rounded-lg p-4 shadow-sm dark:bg-slate-800">
                <div class="flex flex-col sm:flex-row justify-between sm:items-center p-2">
                    <div>
                        <h2 class="dark:text-white text-lg font-semibold">Performance Overview</h2>
                        <p class="text-sm text-slate-400">Track the impact of your campaigns</p>
                    </div>
                    <a href="{{ route('user.analytics') }}" wire:navigate
                        class="text-orange-400 text-sm font-medium mt-4 sm:mt-0 hover:text-orange-300 transition-colors">
                        View all →
                    </a>
                </div>
                <div>
                    <canvas id="campaignChart" width="1000" style="max-height: 384px; width: 1000px;"
                        height="384"></canvas>
                </div>
            </div>

            <!-- Right Section -->
            <div class="rounded-lg p-4 sm:p-6 shadow-sm dark:bg-slate-800">
                <h3 class="dark:text-white text-lg font-semibold">Genre Distribution</h3>
                <p class="text-slate-400 text-sm mb-5">What your audience listens to</p>

                <div style="position: relative; width: 100%; max-width: 700px; margin: 0 auto;">
                    <canvas id="genreChart"
                        style="
                    width: 100% !important;
                    height: auto !important;
                    max-height: 300px !important;
                    vertical-align: baseline !important;
                    display: block;
                    "></canvas>
                </div>

                <div class="flex flex-wrap justify-center gap-2 text-xs mt-4">

                    @forelse($genreBreakdown as $index => $genre)
                        @php
                            $colors = ['#ff6b35', '#10b981', '#8b5cf6', '#f59e0b', '#ef4444'];
                            $color = $colors[$index % 5];
                        @endphp
                        <div class="flex items-center gap-2">
                            <span class="text-sm" style="color: {{ $color }};">{{ $genre['genre'] }}</span>
                        </div>
                    @empty
                        <div class="text-center text-gray-500 dark:text-gray-400 py-4">
                            <p>No genre data available yet.</p>
                        </div>
                    @endforelse

                    {{-- @foreach (user()->genres as $genre)
                            <div class="flex items-center gap-2">
                                <span class="text-slate-400">{{ $genre->genre }}</span>
                            </div>
                        @endforeach --}}
                </div>
            </div>

            <!-- Left Section -->
            <div class="lg:col-span-2 rounded-lg p-4 shadow-sm dark:bg-slate-800">
                <div class="flex items-center justify-between p-4">
                    <div>
                        <h3 class="text-lg font-semibold">Recent Tracks</h3>
                        <p class="text-slate-400 text-sm">Your latest submissions</p>
                    </div>
                    @if (isset($recentTracks))
                        @if ($recentTracks->count() > 0)
                            <a class="text-orange-500 hover:text-orange-400 text-sm font-medium" wire:navigate
                                href="{{ route('user.my-account', ['tab' => 'tracks']) }}">View all →</a>
                        @endif
                    @endif
                </div>

                <!-- Show recent tracks if exist -->
                <div class="space-y-4">

                    @if (isset($recentTracks))
                        @forelse ($recentTracks as $recentTrack)
                            <x-sound-cloud.sound-cloud-player :track="$recentTrack" :visual="false" />
                        @empty
                            <div class="text-center py-8">
                                <div class="w-16 h-16 mx-auto mb-4 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-slate-400"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path d="M5 12h14M12 5v14" />
                                    </svg>
                                </div>
                                <h4 class="font-medium mb-2">No upcoming campaigns scheduled</h4>
                                <p class="text-slate-400 text-sm mb-4">Submit a track to start a new campaign</p>
                                <x-gbutton variant="primary" wire:click="toggleCampaignsModal">
                                    <span><x-lucide-plus
                                            class="inline-block text-center h-4 w-4 text-white mr-1" /></span>
                                    Create Campaign
                                </x-gbutton>
                            </div>
                        @endforelse
                    @else
                        <div class="text-center py-8">
                            <div class="w-16 h-16 mx-auto mb-4 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-slate-400" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path d="M5 12h14M12 5v14" />
                                </svg>
                            </div>
                            <h4 class="font-medium mb-2">No upcoming campaigns scheduled</h4>
                            <p class="text-slate-400 text-sm mb-4">Submit a track to start a new campaign</p>
                            <x-gbutton variant="primary" wire:click="toggleCampaignsModal">
                                <span><x-lucide-plus class="inline-block text-center h-4 w-4 text-white mr-1" /></span>
                                Create Campaign
                            </x-gbutton>
                        </div>
                    @endif
                </div>
            </div>


            <!-- Right Section -->
            <div class="rounded-lg shadow-sm p-4 dark:bg-slate-800">
                <div class="flex items-center justify-between p-2">
                    <div>
                        <h3 class="text-lg font-semibold">Latest Repost Requests</h3>
                    </div>
                    @if (isset($repostRequests))

                        @if ($repostRequests->count() > 0)
                            <a class="text-orange-500 hover:text-orange-400 text-sm font-medium"
                                href="{{ route('user.reposts-request') }}">View all →</a>
                        @endif
                    @endif
                </div>
                @if (isset($repostRequests))
                    @forelse ($repostRequests as $request_)
                        <div class="space-y-4">
                            <div class="shadow-sm rounded-lg p-4">
                                <div class="flex justify-between text-sm mb-3">
                                    <div class="flex items-center space-x-2">
                                        <span class="text-orange-500 font-bold">#{{ $loop->iteration }}</span>
                                        <span
                                            class="text-sm max-w-[200px] truncate">{{ $request_?->music?->title }}</span>
                                    </div>
                                    <span class="text-slate-400">{{ $request_?->music?->genre }}</span>
                                </div>
                                <div class="flex items-start space-x-3 mb-3">
                                    <img src="{{ $request_?->requester?->avatar }}" class="w-8 h-8 rounded-full"
                                        alt="">
                                    <div class="flex-1">
                                        <h4 class="text-sm font-medium">{{ $request_?->requester?->name }}</h4>
                                    </div>
                                    <span
                                        class="text-orange-500 font-semibold text-sm">+{{ $request_->credits_spent ?? '0' }}
                                        credits</span>
                                </div>
                                <div class="flex space-x-2">
                                    <div class="flex-1">
                                        <x-gbutton variant="secondary" full-width="true"
                                            wire:click="declineRepost('{{ encrypt($request_->id) }}')">Decline</x-gbutton>
                                    </div>
                                    <div class="flex-1">
                                        <x-gbutton variant="primary" full-width="true"
                                            wire:click="confirmRepost('{{ $request_->id }}')">Repost</x-gbutton>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-gray-500 dark:text-gray-400 py-4">
                            <p>No repost requests available yet.</p>
                        </div>
                    @endforelse
                @else
                    <div class="text-center text-gray-500 dark:text-gray-400 py-4">
                        <p>No repost requests available yet.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Choose a track or playlist Modal --}}
    <div x-data="{ showCampaignsModal: @entangle('showCampaignsModal').live }" x-show="showCampaignsModal" x-cloak
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
        class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4 z-50">

        <div
            class="w-full max-w-3xl mx-auto rounded-2xl shadow-2xl bg-white dark:bg-slate-800 border border-gray-200 dark:border-gray-700 flex flex-col max-h-[80vh] overflow-hidden">
            <div
                class="flex justify-between items-center p-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-orange-50 to-orange-100 dark:from-orange-900/20 dark:to-orange-800/20">
                <div class="flex items-center gap-3">
                    <div>
                        @if (app_setting('favicon') && app_setting('favicon_dark'))
                            <img src="{{ storage_url(app_setting('favicon')) }}" alt="{{ config('app.name') }}"
                                class="w-12 dark:hidden" />
                            <img src="{{ storage_url(app_setting('favicon_dark')) }}" alt="{{ config('app.name') }}"
                                class="w-12 hidden dark:block" />
                        @else
                            <img src="{{ asset('assets/favicons/fav icon 1.svg') }}" alt="{{ config('app.name') }}"
                                class="w-12 dark:hidden" />
                            <img src="{{ asset('assets/favicons/fav icon 2 (1).svg') }}"
                                alt="{{ config('app.name') }}" class="w-12 hidden dark:block" />
                        @endif
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ __('Choose a track or playlist') }}
                    </h2>
                </div>
                <button x-on:click="showCampaignsModal = false"
                    class="cursor-pointer w-10 h-10 rounded-xl bg-white dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-all duration-200 flex items-center justify-center border border-gray-200 dark:border-gray-600">
                    <x-lucide-x class="w-5 h-5" />
                </button>
            </div>

            @if ($showCampaignsModal)
                <div class="flex border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                    <button wire:click="selectModalTab('tracks')"
                        class="cursor-pointer flex-1 py-4 px-6 text-center font-semibold text-base transition-all duration-300 ease-in-out border-b-2 hover:bg-white dark:hover:bg-gray-700 {{ $activeTab === 'tracks' ? 'border-orange-500 text-orange-600 bg-white dark:bg-gray-700' : 'border-transparent text-gray-600 dark:text-gray-400' }}">
                        <div class="flex items-center justify-center gap-2">
                            <x-lucide-music class="w-4 h-4" />
                            {{ __('Tracks') }}
                        </div>
                    </button>
                    <button wire:click="selectModalTab('playlists')"
                        class="cursor-pointer flex-1 py-4 px-6 text-center font-semibold text-base transition-all duration-300 ease-in-out border-b-2 hover:bg-white dark:hover:bg-gray-700 {{ $activeTab === 'playlists' ? 'border-orange-500 text-orange-600 bg-white dark:bg-gray-700' : 'border-transparent text-gray-600 dark:text-gray-400' }}">
                        <div class="flex items-center justify-center gap-2">
                            <x-lucide-list-music class="w-4 h-4" />
                            {{ __('Playlists') }}
                        </div>
                    </button>
                </div>
                <div class="flex-grow overflow-y-auto">
                    <div class="p-5 sticky top-0 bg-white dark:bg-slate-800">
                        <label for="track-link-search" class="text-xl font-semibold text-gray-700 dark:text-gray-200">
                            @if ($activeTab === 'tracks')
                                Paste a SoundCloud track link
                            @else
                                Paste a SoundCloud playlist link
                            @endif
                        </label>
                        <form wire:submit.prevent="searchSoundcloud">
                            <div class="flex w-full mt-2">
                                <input wire:model="searchQuery" type="text" id="track-link-search"
                                    placeholder="{{ $activeTab === 'tracks' ? 'Paste a SoundCloud track link' : 'Paste a SoundCloud playlist link' }}"
                                    class="flex-grow p-3 text-gray-700 dark:text-gray-200 bg-white dark:bg-slate-700 placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-0 focus:border-orange-500 transition-colors duration-200 border-2 border-gray-300 dark:border-gray-600 ">
                                <button type="submit"
                                    class="bg-orange-500 text-white p-3 w-14 flex items-center justify-center hover:bg-orange-600 transition-colors duration-200">

                                    <span wire:loading.remove wire:target="searchSoundcloud">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                        </svg>
                                    </span>

                                    <span wire:loading wire:target="searchSoundcloud">
                                        <!-- Loading Spinner -->
                                        <svg class="animate-spin h-5 w-5 text-white"
                                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10"
                                                stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor"
                                                d="M4 12a8 8 0 018-8v4l3-3-3-3v4a8 8 0 100 16v-4l-3 3 3 3v-4a8 8 0 01-8-8z">
                                            </path>
                                        </svg>
                                    </span>
                                </button>
                            </div>
                        </form>
                    </div>
                    <div class="p-4">
                        @if ($activeTab === 'tracks' || $playListTrackShow == true)
                            <div class="space-y-3" wire:loading.remove wire:target="searchSoundcloud">
                                @forelse ($tracks as $track_)
                                    <div wire:click="toggleSubmitModal('track', {{ $track_->id }})"
                                        class="p-2 flex items-center space-x-4 cursor-pointer hover:bg-gray-50 dark:hover:bg-slate-700 rounded-xl transition-all duration-200 border border-transparent hover:border-orange-200 dark:hover:border-orange-800 group">
                                        <div class="flex-shrink-0">
                                            <img class="h-14 w-14 rounded-xl object-cover shadow-md"
                                                src="{{ soundcloud_image($track_->artwork_url) }}"
                                                alt="{{ $track_->title }}" />
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p
                                                class="text-base font-semibold text-gray-900 dark:text-white truncate group-hover:text-orange-600 dark:group-hover:text-orange-400 transition-colors">
                                                {{ $track_->title }}
                                            </p>
                                            <p class="text-sm text-gray-500 dark:text-gray-400 truncate">
                                                {{ __('by') }}
                                                <strong
                                                    class="text-orange-600 dark:text-orange-400">{{ $track_->author_username }}</strong>
                                                <span class="ml-2 text-xs text-gray-400">{{ $track_->genre }}</span>
                                            </p>
                                            <span
                                                class="bg-gray-100 dark:bg-slate-600 text-xs px-3 py-1 rounded-full text-gray-700 dark:text-gray-300 mt-2 font-mono flex items-start justify-center w-fit gap-3">
                                                <x-lucide-audio-lines class="w-4 h-4" />
                                                {{ $track_->playback_count }}</span>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <x-lucide-chevron-right
                                                class="w-5 h-5 text-gray-400 group-hover:text-orange-500 transition-colors" />
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-16 text-gray-500 dark:text-gray-400">
                                        <div
                                            class="w-16 h-16 bg-orange-100 dark:bg-orange-900/20 rounded-full flex items-center justify-center mx-auto mb-4">
                                            <x-lucide-music class="w-8 h-8 text-orange-500" />
                                        </div>
                                        <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                            {{ __('No tracks found') }}
                                        </h3>
                                        <p class="text-gray-500 dark:text-gray-400">
                                            {{ __('Add one to get started with campaigns.') }}
                                        </p>
                                    </div>
                                @endforelse

                                {{-- Load More Button for Tracks --}}
                                @if ($hasMoreTracks)
                                    <div class="text-center mt-4">
                                        <button wire:click="loadMoreTracks" wire:loading.attr="disabled"
                                            class="bg-orange-500 text-white font-semibold px-3 py-1.5 text-sm rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 hover:bg-orange-600 transition-colors duration-200 disabled:bg-orange-300 disabled:cursor-not-allowed">
                                            <span wire:loading.remove wire:target="loadMoreTracks">
                                                Load More
                                            </span>
                                            <span wire:loading wire:target="loadMoreTracks">
                                                Loading...
                                            </span>
                                        </button>
                                    </div>
                                @endif
                            </div>

                            <div wire:loading wire:target="searchSoundcloud"
                                class="w-full flex justify-center items-center">
                                <div class="text-center py-16 text-orange-600">
                                    <div
                                        class="w-16 h-16 bg-orange-100 dark:bg-orange-900/20 rounded-full flex items-center justify-center mx-auto mb-4 animate-spin">
                                        <svg class="w-8 h-8 text-orange-500" xmlns="http://www.w3.org/2000/svg"
                                            fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10"
                                                stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor"
                                                d="M4 12a8 8 0 018-8v4l3-3-3-3v4a8 8 0 100 16v-4l-3 3 3 3v-4a8 8 0 01-8-8z" />
                                        </svg>
                                    </div>
                                    <p class="text-sm font-medium">Searching Track...</p>
                                </div>
                            </div>
                        @elseif($activeTab === 'playlists')
                            <div class="space-y-3" wire:loading.remove wire:target="searchSoundcloud">
                                @forelse ($playlists as $playlist_)
                                    <div wire:click="toggleSubmitModal('playlist', {{ $playlist_->id }})"
                                        class="p-4 flex items-center space-x-4 cursor-pointer hover:bg-gray-50 dark:hover:bg-slate-700 rounded-xl transition-all duration-200 border border-transparent hover:border-orange-200 dark:hover:border-orange-800 group">
                                        <div class="flex-shrink-0">
                                            <img class="h-14 w-14 rounded-xl object-cover shadow-md"
                                                src="{{ soundcloud_image($playlist_->artwork_url) }}"
                                                alt="{{ $playlist_->title }}" />
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p
                                                class="text-base font-semibold text-gray-900 dark:text-white truncate group-hover:text-orange-600 dark:group-hover:text-orange-400 transition-colors">
                                                {{ $playlist_->title }}
                                            </p>
                                            <p class="text-sm text-gray-500 dark:text-gray-400 truncate">
                                                {{ $playlist_->track_count }} {{ __('tracks') }}
                                            </p>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <x-lucide-chevron-right
                                                class="w-5 h-5 text-gray-400 group-hover:text-orange-500 transition-colors" />
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-16 text-gray-500 dark:text-gray-400">
                                        <div
                                            class="w-16 h-16 bg-orange-100 dark:bg-orange-900/20 rounded-full flex items-center justify-center mx-auto mb-4">
                                            <x-lucide-list-music class="w-8 h-8 text-orange-500" />
                                        </div>
                                        <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                            {{ __('No playlists found') }}
                                        </h3>
                                        <p class="text-gray-500 dark:text-gray-400">
                                            {{ __('Add one to get started with campaigns.') }}
                                        </p>
                                    </div>
                                @endforelse

                                {{-- Load More Button for Playlists --}}
                                @if ($hasMorePlaylists)
                                    <div class="text-center mt-4">
                                        <button wire:click="loadMorePlaylists" wire:loading.attr="disabled"
                                            class="bg-orange-500 text-white font-semibold px-3 py-1.5 text-sm rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 hover:bg-orange-600 transition-colors duration-200 disabled:bg-orange-300 disabled:cursor-not-allowed">
                                            <span wire:loading.remove wire:target="loadMorePlaylists">
                                                Load More
                                            </span>
                                            <span wire:loading wire:target="loadMorePlaylists">
                                                Loading...
                                            </span>
                                        </button>
                                    </div>
                                @endif
                            </div>

                            <div wire:loading wire:target="searchSoundcloud"
                                class="w-full flex justify-center items-center">
                                <div class="text-center py-16 text-orange-600">
                                    <div
                                        class="w-16 h-16 bg-orange-100 dark:bg-orange-900/20 rounded-full flex items-center justify-center mx-auto mb-4 animate-spin">
                                        <svg class="w-8 h-8 text-orange-500" xmlns="http://www.w3.org/2000/svg"
                                            fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10"
                                                stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor"
                                                d="M4 12a8 8 0 018-8v4l3-3-3-3v4a8 8 0 100 16v-4l-3 3 3 3v-4a8 8 0 01-8-8z" />
                                        </svg>
                                    </div>
                                    <p class="text-sm font-medium">Searching Playlist...</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
    {{-- Create campaign (submit) Modal --}}
    @include('backend.user.includes.campaign-create-modal')
    @include('backend.user.includes.direct-repost-confirmation-modal')

    <div x-data="{ showLowCreditWarningModal: @entangle('showLowCreditWarningModal').live }" x-show="showLowCreditWarningModal" x-cloak
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
        class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4 z-50">

        <div
            class="w-full max-w-md mx-auto rounded-2xl shadow-2xl bg-white dark:bg-slate-800 border border-gray-200 dark:border-gray-700 flex flex-col overflow-hidden">
            <div
                class="flex justify-between items-center p-6 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-red-50 to-red-100 dark:from-red-900/20 dark:to-red-800/20">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-red-500 rounded-xl flex items-center justify-center">
                        <x-lucide-triangle-alert class="w-5 h-5 text-white" />
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ __('Low Credit Warning') }}
                    </h2>
                </div>
                <button x-on:click="showLowCreditWarningModal = false"
                    class="w-10 h-10 rounded-xl bg-white dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-all duration-200 flex items-center justify-center border border-gray-200 dark:border-gray-600">
                    <x-lucide-x class="w-5 h-5" />
                </button>
            </div>

            <div class="p-6 text-center">
                <div
                    class="w-20 h-20 bg-red-100 dark:bg-red-900/20 rounded-full flex items-center justify-center mx-auto mb-6">
                    <x-lucide-wallet class="w-10 h-10 text-red-600 dark:text-red-400" />
                </div>
                <p class="text-lg text-gray-700 dark:text-gray-300 mb-4">
                    {{ __('You need a minimum of 50 credits to create a campaign.') }}
                </p>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">
                    {{ __('Please add more credits to your account to proceed with campaign creation.') }}
                </p>
                <x-gbutton :full-width="true" variant="primary" wire:navigate href="{{ route('user.add-credits') }}"
                    class="mb-2">{{ __('Buy Credits Now') }}</x-gbutton>
            </div>
        </div>
    </div>

    {{-- JavaScript for Chart --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-annotation@1.0.0"></script>

</div>

@once
    @push('js')
        <script>
            // Reset editor on Livewire navigation to pick up theme changes
            document.addEventListener('livewire:initialized', () => {
                setTimeout(() => {
                    window.dispatchEvent(new CustomEvent('reset-charts'));
                }, 500);
            });
        </script>
    @endpush
@endonce
