<x-user::layout>
    <x-slot name="page_slug">dashboard</x-slot>
    <!-- Dashboard Content (Default) -->

    <div id="content-dashboard" class="page-content py-2 px-2 ">
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
                <div
                    class="flex items-center gap-2 bg-slate-700 hover:bg-slate-600 dark:bg-slate-800 dark:hover:bg-slate-700 text-white dark:text-gray-200 px-3 py-2 rounded-lg font-medium transition-colors cursor-pointer w-full sm:w-auto justify-center">
                    <span class="flex items-center gap-1 text-base sm:text-sm">
                        💰
                        <a href="{{ route('user.cm.campaigns') }}" wire:navigate
                            class="hover:underline text-white dark:text-gray-200 text-base sm:text-xs lg:text-base">
                            {{ __('Earn Credits') }}
                        </a>
                    </span>
                </div>
                <!-- Submit Track -->
                <div
                    class="flex items-center gap-2 bg-orange-600 text-white rounded-md hover:bg-orange-700 w-full sm:w-auto justify-center py-2 px-3">
                    <a href="{{ route('user.track.submit') }}" class="text-base sm:text-xs lg:text-base  "
                        wire:navigate>
                        <span>
                            <x-lucide-music class="inline-block text-center h-4 w-4 text-purple-800" />
                        </span>
                        {{ __('Submit Track') }}</a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
            <div
                class="bg-white  dark:bg-slate-800 rounded-lg shadow-sm p-6 hover:-translate-y-2 transition-all duration-500 ease-in-out">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-gray-500 dark:text-white text-sm font-medium">Available Credits</h3>
                    <div class="p-2 rounded-lg bg-yellow-500/20 text-yellow-500"><svg xmlns="http://www.w3.org/2000/svg"
                            width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-zap w-5 h-5">
                            <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon>
                        </svg></div>
                </div>
                <div class="space-y-2">
                    <p class="text-2xl font-bold text-slate-700 dark:text-white">{{ userCredits() }}</p>

                    @if ($creditPercentage >= 0)
                        <p class="text-sm flex items-center space-x-1 text-green-400">
                            <span>+{{ $creditPercentage }}% from last week</span>
                        </p>
                    @elseif($creditPercentage < 0)
                        <p class="text-sm flex items-center space-x-1 text-red-400">
                            <span>{{ $creditPercentage }}% from last week</span>
                        </p>
                    @else
                        <p class="text-sm flex items-center space-x-1 text-gray-400">
                            <span>0% from last week</span>
                        </p>
                    @endif
                </div>

            </div>

            <div
                class="bg-white dark:bg-slate-800 rounded-lg shadow-sm p-6 hover:-translate-y-2 transition-all duration-500 ease-in-out">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-gray-500 dark:text-white text-sm font-medium">Active Campaigns</h3>
                    <div class="p-2 rounded-lg bg-blue-500/20 text-blue-500"><svg xmlns="http://www.w3.org/2000/svg"
                            width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-music2 w-5 h-5">
                            <circle cx="8" cy="18" r="4"></circle>
                            <path d="M12 18V2l7 4"></path>
                        </svg>
                    </div>
                </div>
                <div class="space-y-2">
                    <p class="text-2xl  font-bold text-slate-700 dark:text-white">{{ $totalCams }}</p>
                    @if ($campaignPercentage >= 0)
                        <p class="text-sm flex items-center space-x-1 text-green-400">
                            <span>+{{ $campaignPercentage }}% from last week</span>
                        </p>
                    @elseif($campaignPercentage < 0)
                        <p class="text-sm flex items-center space-x-1 text-red-400">
                            <span>{{ $campaignPercentage }}% from last week</span>
                        </p>
                    @else
                        <p class="text-sm flex items-center space-x-1 text-gray-400">
                            <span>0% from last week</span>
                        </p>
                    @endif
                </div>
            </div>

            <div
                class="bg-white dark:bg-slate-800 rounded-lg shadow-sm p-6 hover:-translate-y-2 transition-all duration-500 ease-in-out">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-gray-500 dark:text-white text-sm font-medium">Reposts Received</h3>
                    <div class="p-2 rounded-lg bg-green-500/20 text-green-500"><svg xmlns="http://www.w3.org/2000/svg"
                            width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-repeat2 w-5 h-5">
                            <path d="m2 9 3-3 3 3"></path>
                            <path d="M13 18H7a2 2 0 0 1-2-2V6"></path>
                            <path d="m22 15-3 3-3-3"></path>
                            <path d="M11 6h6a2 2 0 0 1 2 2v10"></path>
                        </svg></div>
                </div>
                <div class="space-y-2">
                    <p class="text-2xl  font-bold text-slate-700 dark:text-white">{{ $totalCount }}</p>
                  @if ($repostRequestPercentage >= 0)
                      <p class="text-sm flex items-center space-x-1 text-green-400">
                          <span>+{{ $repostRequestPercentage }}% from last week</span>
                      </p>
                  @elseif($repostRequestPercentage < 0)
                      <p class="text-sm flex items-center space-x-1 text-red-400">
                          <span>{{ $repostRequestPercentage }}% from last week</span>
                      </p>
                  @else
                      <p class="text-sm flex items-center space-x-1 text-gray-400">
                          <span>0% from last week</span>
                      </p>
                  @endif
                </div>
            </div>

            <div
                class="bg-white dark:bg-slate-800 rounded-lg shadow-sm p-6 hover:-translate-y-2 transition-all duration-500 ease-in-out">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-gray-500 dark:text-white text-sm font-medium">Credibility Score</h3>
                    <div class="p-2 rounded-lg bg-purple-500/20 text-purple-500"><svg xmlns="http://www.w3.org/2000/svg"
                            width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-award w-5 h-5">
                            <circle cx="12" cy="8" r="6"></circle>
                            <path d="M15.477 12.89 17 22l-5-3-5 3 1.523-9.11"></path>
                        </svg></div>
                </div>
                <div class="space-y-2">
                    <p class="text-2xl  font-bold text-slate-700 dark:text-white">82%</p>
                    <p class="text-sm flex items-center space-x-1 text-green-400"><span>+3% from last week</span></p>
                </div>
            </div>
        </div>
    </div>


    <div class=" p-2">

        <main class="#">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-2">
                <!-- Left Section -->
                <div class="lg:col-span-2 rounded-lg p-4 border border-slate-700">
                    <div class="flex flex-col sm:flex-row justify-between sm:items-center p-2">
                        <div>
                            <h2 class="dark:text-white text-lg font-semibold">Performance Overview</h2>
                            <p class="text-sm text-slate-400">Track the impact of your campaigns</p>
                        </div>
                        <a href="#"
                            class="text-orange-400 text-sm font-medium mt-4 sm:mt-0 hover:text-orange-300 transition-colors">
                            View all →
                        </a>
                    </div>
                    <div class="h-80 sm:h-96">
                        <canvas id="campaignChart" width="961" height="384"></canvas>
                    </div>
                </div>

                <!-- Right Section -->
                <div class="rounded-lg p-4 sm:p-6 border border-slate-700">
                    <h3 class="dark:text-white text-lg font-semibold">Genre Distribution</h3>
                    <p class="text-slate-400 text-sm mb-2">What your audience listens to</p>
                    <div class="h-60 sm:h-96 flex flex-col justify-between">
                        <div class="flex-grow flex items-center justify-center my-4">
                            <div
                                class="bg-slate-700/50 rounded-full w-36 h-36 sm:w-40 sm:h-40 flex items-center justify-center">
                                <img src="https://imgs.search.brave.com/2rHUZ109YlFZLs4tiya8jxlxjLsE_WEUoUMpvFfZANQ/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9tLm1l/ZGlhLWFtYXpvbi5j/b20vaW1hZ2VzL0kv/NzFRTnBFbDhjckwu/anBn"
                                    alt="">
                            </div>
                        </div>
                        <div class="flex flex-wrap justify-center gap-x-2 gap-y-2 text-xs">
                            @foreach (user()->genres as $genre)
                                <div class="flex items-center gap-2"><span
                                        class="text-slate-400">{{ $genre->genre }}</span></div>
                            @endforeach


                        </div>
                    </div>
                </div>
            </div>

            <!-- Second Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-2 mt-4 dark:text-white">
                <!-- Left Section -->
                <div class="lg:col-span-2 rounded-lg p-4 border border-slate-700">
                    <div class="flex items-center justify-between p-4">
                        <div>
                            <h3 class="text-lg font-semibold">Recent Tracks</h3>
                            <p class="text-slate-400 text-sm">Your latest submissions</p>
                        </div>
                        <a class="text-orange-500 hover:text-orange-400 text-sm font-medium"
                            href="{{ route('user.reposts-request') }}">View all →</a>
                    </div>
                    <div class="space-y-4">
                        @foreach ($repostRequests as $repostRequest)
                            <div
                                class="rounded-lg p-4 border border-slate-700 hover:border-slate-600 transition-colors">
                                <div id="soundcloud-player-{{ $repostRequest->id }}" wire:ignore>
                                    <x-sound-cloud.sound-cloud-player :track="$repostRequest->track" :visual="false" />
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="text-center py-8 border-t border-slate-700">
                        <div class="w-16 h-16 mx-auto mb-4 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-slate-400" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path d="M5 12h14M12 5v14" />
                            </svg>
                        </div>
                        <h4 class="font-medium mb-2">No upcoming campaigns scheduled</h4>
                        <p class="text-slate-400 text-sm mb-4">Submit a track to start a new campaign</p>
                        <a href="{{ route('user.cm.my-campaigns') }}"
                            class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                            Create Campaign
                        </a>
                    </div>
                </div>

                <!-- Right Section -->
                <div class="rounded-lg border border-slate-700 p-4">
                    <div class="flex items-center justify-between p-2">
                        <div>
                            <h3 class="text-lg font-semibold">Latest Repost Requests</h3>
                            <p class="text-slate-400 text-sm">{{ $totalCount }} requests</p>
                        </div>
                        @if ($repostRequests->count() > 0)
                            <a class="text-orange-500 hover:text-orange-400 text-sm font-medium"
                                href="{{ route('user.reposts-request') }}">View all
                                →</a>
                        @endif
                    </div>
                    @foreach ($repostRequests as $request)
                        <div class="space-y-4">
                            <div class="border border-slate-700 rounded-lg p-4">
                                <div class="flex items-start space-x-3 mb-3">
                                    <img src="https://images.pexels.com/photos/1040881/pexels-photo-1040881.jpeg"
                                        class="w-8 h-8 rounded-full" alt="">
                                    <div class="flex-1">
                                        <h4 class="text-sm font-medium">{{ $request?->requester?->name }}</h4>
                                        <p class="text-slate-400 text-xs">by {{ $request?->requester?->email }}</p>
                                    </div>
                                    <span class="text-orange-500 font-semibold text-sm">+{{ $request->credits_spent }}
                                        credits</span>
                                </div>
                                <div class="flex space-x-2">
                                    <button
                                        class="flex-1 bg-slate-600 hover:bg-slate-500 text-white text-sm py-2 rounded-lg">Decline</button>
                                    <button
                                        class="flex-1 bg-orange-500 hover:bg-orange-600 text-white text-sm py-2 rounded-lg">Repost</button>
                                </div>
                            </div>
                        </div>
                        <div class="mt-6 pt-6 border-t border-slate-700 p-2">
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center space-x-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-orange-500"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <polyline points="22 7 13.5 15.5 8.5 10.5 2 17" />
                                        <polyline points="16 7 22 7 22 13" />
                                    </svg>
                                    <span class="text-sm font-medium">Trending</span>
                                </div>
                                <a class="text-orange-500 hover:text-orange-400 text-sm" href="/charts">View
                                    charts</a>
                            </div>
                            <div class="space-y-2">
                                <div class="flex justify-between text-sm">
                                    <div class="flex items-center space-x-2">
                                        <span class="text-orange-500 font-bold">#1</span>
                                        <span class="text-sm">Why Do I?</span>
                                    </div>
                                    <span class="text-slate-400">{{ $request?->track?->embeddable_by }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <div class="flex items-center space-x-2">
                                        <span class="text-slate-400 font-bold">#2</span>
                                        <span class="text-slate-400 text-sm">The Strength Of Love</span>
                                    </div>
                                    <span class="text-slate-400">Constellation Lyra</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

        </main>
    </div>


    @push('js')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-annotation@1.0.0"></script>

        <script>
            // Define a function to create the chart
            function createCampaignChart() {
                const ctx = document.getElementById('campaignChart');

                // Check if the canvas element exists before trying to create a chart
                if (ctx) {
                    new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                            datasets: [{
                                label: 'Performance',
                                data: [950, 1700, 2300, 2850, 2700, 3800],
                                borderColor: '#f97316',
                                backgroundColor: 'rgba(249, 115, 22, 0.1)',
                                pointBackgroundColor: '#f97316',
                                pointBorderColor: '#ffffff',
                                pointHoverRadius: 7,
                                pointHoverBorderWidth: 2,
                                pointRadius: 5,
                                borderWidth: 2.5,
                                tension: 0.4,
                                fill: true,
                            }, {
                                label: 'Baseline',
                                data: [100, 150, 120, 180, 250, 200],
                                borderColor: '#22c55e',
                                backgroundColor: 'transparent',
                                pointBackgroundColor: '#22c55e',
                                borderWidth: 2,
                                pointRadius: 5,
                                tension: 0.4,
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        color: '#94a3b8',
                                        font: {
                                            size: 10
                                        }
                                    },
                                    grid: {
                                        color: '#334155',
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
                                        display: false,
                                    },
                                }
                            },
                            plugins: {
                                legend: {
                                    position: 'top',
                                    align: 'end',
                                    labels: {
                                        color: '#e2e8f0',
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
                }
            }

            // Listen for the livewire:navigated event to re-initialize the chart
            document.addEventListener('livewire:navigated', () => {
                createCampaignChart();
            });

            // Also call the function on the initial page load
            createCampaignChart();
        </script>
    @endpush
</x-user::layout>
