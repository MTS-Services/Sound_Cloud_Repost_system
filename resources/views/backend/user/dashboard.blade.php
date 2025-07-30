<x-user::layout>
    <x-slot name="page_slug">dashboard</x-slot>
    <!-- Dashboard Content (Default) -->
    <div id="content-dashboard" class="page-content py-2 px-2 ">
        <div
            class="px-2 py-4 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 sm:gap-0 w-full">
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
            <div class="flex flex-col sm:flex-row gap-3 sm:gap-2 w-full sm:w-auto">
                <!-- Earn Credits -->
                <div
                    class="flex items-center gap-2 bg-slate-700 hover:bg-slate-600 dark:bg-slate-800 dark:hover:bg-slate-700 text-white dark:text-gray-200 px-3 py-2 rounded-lg font-medium transition-colors cursor-pointer w-full sm:w-auto justify-center">
                    <span class="flex items-center gap-1 text-base sm:text-sm">
                        💰
                        <a href="{{ route('user.cm.my-campaigns') }}" wire:navigate
                            class="hover:underline text-white dark:text-gray-200 text-base sm:text-sm">
                            {{ __('Earn Credits') }}
                        </a>
                    </span>
                </div>
                <!-- Submit Track -->
                <div
                    class="flex items-center gap-2 bg-orange-600 text-white py-2 px-3 rounded-md hover:bg-orange-700 w-full sm:w-auto justify-center">
                    <span class="flex items-center">
                        <i data-lucide="music" class="inline-block text-center h-5 w-6 text-purple-800"></i>
                    </span>
                    <a href="#" class="text-base sm:text-sm">{{ __('Submit Track') }}</a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div
                class="bg-white  dark:bg-slate-800 rounded-lg shadow-sm p-6 hover:-translate-y-2 transition-all duration-500 ease-in-out">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-slate-400 text-sm font-medium">Available Credits</h3>
                    <div class="p-2 rounded-lg bg-yellow-500/20 text-yellow-500"><svg xmlns="http://www.w3.org/2000/svg"
                            width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-zap w-5 h-5">
                            <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon>
                        </svg></div>
                </div>
                <div class="space-y-2">
                    <p class="text-2xl  font-bold text-slate-700 dark:text-white">{{ $total_credits }}</p>
                    <p class="text-sm flex items-center space-x-1 text-green-400"><span>+12% from last week</span></p>
                </div>
            </div>

            <div
                class="bg-white dark:bg-slate-800 rounded-lg shadow-sm p-6 hover:-translate-y-2 transition-all duration-500 ease-in-out">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-slate-400 text-sm font-medium">Active Campaigns</h3>
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
                    <p class="text-2xl  font-bold text-slate-700 dark:text-white">1</p>
                    <p class="text-sm flex items-center space-x-1 text-green-400"><span>+0% from last week</span></p>
                </div>
            </div>

            <div
                class="bg-white dark:bg-slate-800 rounded-lg shadow-sm p-6 hover:-translate-y-2 transition-all duration-500 ease-in-out">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-slate-400 text-sm font-medium">Reposts Received</h3>
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
                    <p class="text-2xl  font-bold text-slate-700 dark:text-white">152</p>
                    <p class="text-sm flex items-center space-x-1 text-green-400"><span>+8.5% from last week</span></p>
                </div>
            </div>

            <div
                class="bg-white dark:bg-slate-800 rounded-lg shadow-sm p-6 hover:-translate-y-2 transition-all duration-500 ease-in-out">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-slate-400 text-sm font-medium">Credibility Score</h3>
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
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-2  ">

                <div class="lg:col-span-2  rounded-lg p-2 border border-slate-700">
                    <div class="flex flex-col sm:flex-row justify-between sm:items-center p-2">
                        <div>
                            <h2 class=" dark:text-white dark:text-black text-lg font-semibold ">
                                Performance Overview</h2>
                            <p class="text-sm text-slate-400 ">Track the impact of your campaigns</p>
                        </div>
                        <a href="#"
                            class="text-orange-400 text-sm font-medium mt-4 sm:mt-0 hover:text-orange-300 transition-colors">View
                            all →</a>
                    </div>

                    <div class="h-80 sm:h-96">
                        <canvas id="campaignChart"
                            style="display: block; box-sizing: border-box; height: 384px; width: 961px;"
                            width="961" height="384"></canvas>
                    </div>
                </div>

                <div class=" rounded-lg p-4 sm:p-6 border border-slate-700 lg:ml-5">
                    <h3 class="dark:text-white dark:text-black text-lg font-semibold">Genre Distribution</h3>
                    <p class="text-slate-400 text-sm mb-2">What your audience listens to</p>

                    <div class="h-60 sm:h-96 flex flex-col justify-between">
                        <div class="flex-grow flex items-center justify-center my-4">
                            <div
                                class="bg-slate-700/50 rounded-full w-36 h-36 sm:w-40 sm:h-40 flex items-center justify-center">
                                <p class="text-slate-500 text-sm">
                                    <img src="
                                    https://imgs.search.brave.com/2rHUZ109YlFZLs4tiya8jxlxjLsE_WEUoUMpvFfZANQ/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9tLm1l/ZGlhLWFtYXpvbi5j/b20vaW1hZ2VzL0kv/NzFRTnBFbDhjckwu/anBn"
                                        alt="">
                                </p>
                            </div>
                        </div>

                        <div class="flex flex-wrap justify-center gap-x-2 gap-y-2 text-xs">
                            <div class="flex items-center gap-2">
                                <span class="w-3 h-3 rounded-full bg-violet-500"></span>
                                <span class="text-slate-300">Electronic</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="w-3 h-3 rounded-full bg-orange-500"></span>
                                <span class="text-slate-300">Hip-Hop</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="w-3 h-3 rounded-full bg-blue-500"></span>
                                <span class="text-slate-300">Pop</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="w-3 h-3 rounded-full bg-green-500"></span>
                                <span class="text-slate-300">R&B</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="w-3 h-3 rounded-full bg-yellow-500"></span>
                                <span class="text-slate-300">Rock</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 p-2 dark:text-white">
        <div class="lg:col-span-2  rounded-lg lg-px-4 lg-py-6 lg-ml-6 border border-slate-700 m-4 my-2">
            <div class="flex items-center justify-between lg-mb-6 p-4">
                <div>
                    <h3 class="dark:text-white dark:text-black  text-lg font-semibold">Recent Tracks</h3>
                    <p class="text-slate-400 text-sm">Your latest submissions</p>
                </div>
                <a class="text-orange-500 hover:text-orange-400 text-sm font-medium" href="{{ route('user.reposts-request') }}"
                    data-discover="true">View all →</a>
            </div>
            <div class="space-y-4">
                @foreach ($repostRequests as $repostRequest)
                    <div class="  rounded-lg p-4 border border-slate-700 hover:border-slate-600 transition-colors m-2">
                        <div id="soundcloud-player-{{ $repostRequest->id }}"
                            data-request-id="{{ $repostRequest->id }}" wire:ignore>
                            <x-sound-cloud.sound-cloud-player :track="$repostRequest->track" :visual="false" />
                        </div>
                    </div>
                @endforeach

            </div>
            <div class="text-center py-8 border-t border-slate-700 lg-mt-6">
                <div
                    class="w-16 h-16 dark:text-white dark:text-black  rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="lucide lucide-plus w-8 h-8 text-slate-400">
                        <path d="M5 12h14"></path>
                        <path d="M12 5v14"></path>
                    </svg>
                </div>
                <h4 class="text-white font-medium mb-2">No upcoming campaigns scheduled</h4>
                <p class="text-slate-400 text-sm mb-4">Submit a track to start a new campaign</p>

                <a href="{{ route('user.cm.my-campaigns') }}" wire:navigate
                    class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg font-medium transition-colors">Create
                    Campaign</a>
            </div>
        </div>
        <div
            class=" rounded-lg  border border-slate-700 lg:ml-8 ml-0 lg:mr-6 mr-6 lg:p-4 p-4 lg:mt-3 mt-3 lg:mb-2 mb-2">
            <div class="flex items-center justify-between lg-mb-6 p-2">
                <div>
                    <h3 class=" text-lg font-semibold dark:text-white">Latest Repost Requests
                    </h3>
                    <p class="text-slate-400 text-sm">Earn credits by reposting tracks</p>
                </div>
                <a class="text-orange-500 hover:text-orange-400 text-sm font-medium" href="/requests"
                    data-discover="true">View all →</a>
            </div>
            <div class="space-y-4">
                <div class="border border-slate-700 rounded-lg p-4">
                    <div class="flex items-start space-x-3 mb-3">
                        <img src="https://images.pexels.com/photos/1040881/pexels-photo-1040881.jpeg?auto=compress&amp;cs=tinysrgb&amp;w=150&amp;h=150&amp;fit=crop"
                            alt="carlvalor" class="w-8 h-8 rounded-full">
                        <div class="flex-1 min-w-0">
                            <h4 class="text-slate-400 text-sm font-medium text-sm">The Beginning</h4>
                            <p class="text-slate-400 text-xs">by carlvalor</p>
                        </div>
                        <span class="text-orange-500 font-semibold text-sm">+7 credits</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <button
                            class="flex-1 bg-slate-600 hover:bg-slate-500 text-white text-sm py-2 rounded-lg transition-colors">Decline</button>
                        <button
                            class="flex-1 bg-orange-500 hover:bg-orange-600 text-white text-sm py-2 rounded-lg font-medium transition-colors">Repost</button>
                    </div>
                </div>
            </div>
            <div class="mt-6 pt-6 border-t border-slate-700 p-2">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center space-x-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="lucide lucide-trending-up w-5 h-5 text-orange-500">
                            <polyline points="22 7 13.5 15.5 8.5 10.5 2 17"></polyline>
                            <polyline points="16 7 22 7 22 13"></polyline>
                        </svg>
                        <span class="text-slate-400 text-sm font-medium">Trending</span>
                    </div>
                    <a class="text-orange-500 hover:text-orange-400 text-sm" href="/charts" data-discover="true">View
                        charts</a>
                </div>
                <div class="space-y-2">
                    <div class="flex items-center justify-between text-sm">
                        <div class="flex items-center space-x-2">
                            <span class="text-orange-500 font-bold">#1</span>
                            <span class="text-slate-400 text-sm">Why Do I?</span>
                        </div>
                        <span class="text-slate-400">EMMAAG</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <div class="flex items-center space-x-2">
                            <span class="text-slate-400 font-bold">#2</span>
                            <span class="text-slate-400 text-sm">The Strength Of Love</span>
                        </div>
                        <span class="text-slate-400">Constellation Lyra</span>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-annotation@1.0.0"></script>

    <script>
        const ctx = document.getElementById('campaignChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Performance',
                    data: [950, 1700, 2300, 2850, 2700, 3800],
                    borderColor: '#f97316', // Orange-500
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
                    borderColor: '#22c55e', // Green-500
                    backgroundColor: 'transparent',
                    pointBackgroundColor: '#22c55e',
                    borderWidth: 2,
                    pointRadius: 5,
                    tension: 0.4,
                }]
            },
            options: {
                responsive: true, // This is key for responsiveness
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            color: '#94a3b8', // slate-400
                            font: {
                                size: 10
                            }
                        },
                        grid: {
                            color: '#334155', // slate-700
                            drawBorder: false,
                        },
                    },
                    x: {
                        ticks: {
                            color: '#94a3b8', // slate-400
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
                            color: '#e2e8f0', // slate-200
                            boxWidth: 12,
                            font: {
                                size: 12
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: '#0f172a', // slate-900
                        titleColor: '#ffffff',
                        bodyColor: '#cbd5e1', // slate-300
                        borderColor: '#334155', // slate-700
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
    </script>
</x-user::layout>
