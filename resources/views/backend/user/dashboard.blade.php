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
                        <a href="#" class="hover:underline text-white dark:text-gray-200 text-base sm:text-sm">
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

    {{-- <div class="bg-slate-900 antialiased">

        <main class="p-4 sm:p-6 lg:p-2">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 w-full max-w-8xl mx-auto">

                <div class="lg:col-span-2 bg-slate-800 rounded-lg p-4 sm:p-6 border border-slate-700">
                    <div class="flex flex-col sm:flex-row justify-between sm:items-center mb-6">
                        <div>
                            <h2 class="text-white font-bold text-2xl md:text-3xl">Performance Overview</h2>
                            <p class="text-sm text-slate-400 mt-1">Track the impact of your campaigns</p>
                        </div>
                        <a href="#"
                            class="text-orange-400 text-sm font-medium mt-4 sm:mt-0 hover:text-orange-300 transition-colors">View
                            all →</a>
                    </div>

                    <div class="h-80 sm:h-96">
                        <canvas id="campaignChart"></canvas>
                    </div>
                </div>

                <div class="bg-slate-800 rounded-lg p-4 sm:p-6 border border-slate-700">
                    <h3 class="text-white text-xl font-semibold mb-2">Genre Distribution</h3>
                    <p class="text-slate-400 text-sm mb-6">What your audience listens to</p>

                    <div class="h-80 sm:h-96 flex flex-col justify-between">
                        <div class="flex-grow flex items-center justify-center my-4">
                            <div
                                class="bg-slate-700/50 rounded-full w-36 h-36 sm:w-44 sm:h-44 flex items-center justify-center">
                                <p class="text-slate-500 text-sm">Pie Chart</p>
                            </div>
                        </div>

                        <div class="flex flex-wrap justify-center gap-x-4 gap-y-2 text-xs">
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
        <script src="https://cdn.tailwindcss.com"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

    </div> --}}

    <div class="bg-slate-900 antialiased">

        <main class="sm:p-6 p-2 lg:-mb-4">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-2  ">

                <div class="lg:col-span-2 bg-slate-800 rounded-lg p-2 border border-slate-700">
                    <div class="flex flex-col sm:flex-row justify-between sm:items-center ">
                        <div>
                            <h2 class="text-white ">Performance Overview</h2>
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

                <div class="bg-slate-800 rounded-lg p-4 sm:p-6 border border-slate-700 lg:ml-5">
                    <h3 class="text-white">Genre Distribution</h3>
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
        <script src="https://cdn.tailwindcss.com"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3  p-2">
        <div
            class="lg:col-span-2 bg-slate-800 rounded-lg lg-px-4 lg-py-6 lg-ml-6 border border-slate-700 sm:ml-4 sm:mr-3 lg:mt-2">
            <div class="flex items-center justify-between lg-mb-6 p-2">
                <div>
                    <h3 class="text-white text-lg font-semibold">Recent Tracks</h3>
                    <p class="text-slate-400 text-sm">Your latest submissions</p>
                </div>
                <a class="text-orange-500 hover:text-orange-400 text-sm font-medium" href="/my-campaigns"
                    data-discover="true">View all →</a>
            </div>
            <div class="space-y-4">
                <div
                    class="bg-slate-800 rounded-lg p-4 border border-slate-700 hover:border-slate-600 transition-colors">
                    <div class="flex items-start space-x-4">
                        <div class="relative">
                            <img src="https://images.pexels.com/photos/1540338/pexels-photo-1540338.jpeg?auto=compress&amp;cs=tinysrgb&amp;w=300&amp;h=300&amp;fit=crop"
                                alt="Sexy - Fashion - Promo" class="w-16 h-16 rounded-lg">
                            <button
                                class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-50 rounded-lg opacity-0 hover:opacity-100 transition-opacity">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="white" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="lucide lucide-play w-6 h-6 text-white">
                                    <polygon points="5 3 19 12 5 21 5 3"></polygon>
                                </svg>
                            </button>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between mb-2">
                                <div>
                                    <h4 class="text-white font-medium text-sm truncate">Sexy - Fashion - Promo</h4>
                                    <p class="text-slate-400 text-xs">Bhathiya Udara</p>
                                </div>
                                <span class="text-slate-400 text-xs">#2459</span>
                            </div>
                            <div class="mb-3">
                                <div class="flex items-center space-x-1 h-8">
                                    <div class="bg-slate-600 flex-1" style="height: 22.3117px; border-radius: 1px;">
                                    </div>
                                    <div class="bg-slate-600 flex-1" style="height: 28.6123px; border-radius: 1px;">
                                    </div>
                                    <div class="bg-slate-600 flex-1" style="height: 18.0402px; border-radius: 1px;">
                                    </div>
                                    <div class="bg-slate-600 flex-1" style="height: 25.9434px; border-radius: 1px;">
                                    </div>
                                    <div class="bg-slate-600 flex-1" style="height: 20.6919px; border-radius: 1px;">
                                    </div>
                                    <div class="bg-slate-600 flex-1" style="height: 23.922px; border-radius: 1px;">
                                    </div>
                                    <div class="bg-slate-600 flex-1" style="height: 30.7236px; border-radius: 1px;">
                                    </div>
                                    <div class="bg-slate-600 flex-1" style="height: 20.4744px; border-radius: 1px;">
                                    </div>
                                    <div class="bg-slate-600 flex-1" style="height: 24.3519px; border-radius: 1px;">
                                    </div>
                                    <div class="bg-slate-600 flex-1" style="height: 22.2901px; border-radius: 1px;">
                                    </div>
                                    <div class="bg-slate-600 flex-1" style="height: 27.6417px; border-radius: 1px;">
                                    </div>
                                    <div class="bg-slate-600 flex-1" style="height: 20.3312px; border-radius: 1px;">
                                    </div>
                                    <div class="bg-slate-600 flex-1" style="height: 11.5121px; border-radius: 1px;">
                                    </div>
                                    <div class="bg-slate-600 flex-1" style="height: 18.8367px; border-radius: 1px;">
                                    </div>
                                    <div class="bg-slate-600 flex-1" style="height: 21px; border-radius: 1px;"></div>
                                    <div class="bg-slate-600 flex-1" style="height: 20.8976px; border-radius: 1px;">
                                    </div>
                                    <div class="bg-slate-600 flex-1" style="height: 29.1842px; border-radius: 1px;">
                                    </div>
                                    <div class="bg-slate-600 flex-1" style="height: 10.8375px; border-radius: 1px;">
                                    </div>
                                    <div class="bg-slate-600 flex-1" style="height: 17.5647px; border-radius: 1px;">
                                    </div>
                                    <div class="bg-slate-600 flex-1" style="height: 12.3174px; border-radius: 1px;">
                                    </div>
                                    <div class="bg-slate-600 flex-1" style="height: 27.9848px; border-radius: 1px;">
                                    </div>
                                    <div class="bg-slate-600 flex-1" style="height: 16.9691px; border-radius: 1px;">
                                    </div>
                                    <div class="bg-slate-600 flex-1" style="height: 15.727px; border-radius: 1px;">
                                    </div>
                                    <div class="bg-slate-600 flex-1" style="height: 17.9482px; border-radius: 1px;">
                                    </div>
                                    <div class="bg-slate-600 flex-1" style="height: 8.35407px; border-radius: 1px;">
                                    </div>
                                    <div class="bg-slate-600 flex-1" style="height: 23.8516px; border-radius: 1px;">
                                    </div>
                                    <div class="bg-slate-600 flex-1" style="height: 16.882px; border-radius: 1px;">
                                    </div>
                                    <div class="bg-slate-600 flex-1" style="height: 18.4832px; border-radius: 1px;">
                                    </div>
                                    <div class="bg-slate-600 flex-1" style="height: 10.5783px; border-radius: 1px;">
                                    </div>
                                    <div class="bg-slate-600 flex-1" style="height: 22.4815px; border-radius: 1px;">
                                    </div>
                                    <div class="bg-slate-600 flex-1" style="height: 20.3273px; border-radius: 1px;">
                                    </div>
                                    <div class="bg-slate-600 flex-1" style="height: 9.01349px; border-radius: 1px;">
                                    </div>
                                    <div class="bg-slate-600 flex-1" style="height: 13.2725px; border-radius: 1px;">
                                    </div>
                                    <div class="bg-slate-600 flex-1" style="height: 26.0454px; border-radius: 1px;">
                                    </div>
                                    <div class="bg-slate-600 flex-1" style="height: 20.7522px; border-radius: 1px;">
                                    </div>
                                    <div class="bg-slate-600 flex-1" style="height: 18.0821px; border-radius: 1px;">
                                    </div>
                                    <div class="bg-slate-600 flex-1" style="height: 15.2882px; border-radius: 1px;">
                                    </div>
                                    <div class="bg-slate-600 flex-1" style="height: 17.9154px; border-radius: 1px;">
                                    </div>
                                    <div class="bg-slate-600 flex-1" style="height: 13.2295px; border-radius: 1px;">
                                    </div>
                                    <div class="bg-slate-600 flex-1" style="height: 12.9555px; border-radius: 1px;">
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center justify-between text-slate-400 text-xs">
                                <div class="flex items-center space-x-4">
                                    <div class="flex items-center space-x-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            class="lucide lucide-heart w-3 h-3">
                                            <path
                                                d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z">
                                            </path>
                                        </svg>
                                        <span>187</span>
                                    </div>
                                    <div class="flex items-center space-x-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            class="lucide lucide-repeat2 w-3 h-3">
                                            <path d="m2 9 3-3 3 3"></path>
                                            <path d="M13 18H7a2 2 0 0 1-2-2V6"></path>
                                            <path d="m22 15-3 3-3-3"></path>
                                            <path d="M11 6h6a2 2 0 0 1 2 2v10"></path>
                                        </svg>
                                        <span>24</span>
                                    </div>
                                    <div class="flex items-center space-x-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            class="lucide lucide-bar-chart3 w-3 h-3">
                                            <path d="M3 3v18h18"></path>
                                            <path d="M18 17V9"></path>
                                            <path d="M13 17V5"></path>
                                            <path d="M8 17v-3"></path>
                                        </svg>
                                        <span>5</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div
                    class="bg-slate-800 rounded-lg p-4 border border-slate-700 hover:border-slate-600 transition-colors">
                    <div class="flex items-start space-x-4">
                        <div class="relative">
                            <img src="https://images.pexels.com/photos/1763075/pexels-photo-1763075.jpeg?auto=compress&amp;cs=tinysrgb&amp;w=300&amp;h=300&amp;fit=crop"
                                alt="Drop - To - Me" class="w-16 h-16 rounded-lg">
                            <button
                                class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-50 rounded-lg opacity-0 hover:opacity-100 transition-opacity">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="white" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="lucide lucide-play w-6 h-6 text-white">
                                    <polygon points="5 3 19 12 5 21 5 3"></polygon>
                                </svg>
                            </button>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between mb-2">
                                <div>
                                    <h4 class="text-white font-medium text-sm truncate">Drop - To - Me</h4>
                                    <p class="text-slate-400 text-xs">Bhathiya Udara</p>
                                </div>
                                <span class="text-slate-400 text-xs">#1830</span>
                            </div>
                            <div class="mb-3">
                                <div class="flex items-center space-x-1 h-8">
                                    <div class="bg-slate-600 flex-1" style="height: 20.5618px; border-radius: 1px;">
                                    </div>
                                    <div class="bg-slate-600 flex-1" style="height: 29.3579px; border-radius: 1px;">
                                    </div>
                                    <div class="bg-slate-600 flex-1" style="height: 13.5293px; border-radius: 1px;">
                                    </div>
                                    <div class="bg-slate-600 flex-1" style="height: 21.4214px; border-radius: 1px;">
                                    </div>
                                    <div class="bg-slate-600 flex-1" style="height: 21.475px; border-radius: 1px;">
                                    </div>
                                    <div class="bg-slate-600 flex-1" style="height: 25.0576px; border-radius: 1px;">
                                    </div>
                                    <div class="bg-slate-600 flex-1" style="height: 8.1078px; border-radius: 1px;">
                                    </div>
                                    <div class="bg-slate-600 flex-1" style="height: 31.2167px; border-radius: 1px;">
                                    </div>
                                    <div class="bg-slate-600 flex-1" style="height: 25.4533px; border-radius: 1px;">
                                    </div>
                                    <div class="bg-slate-600 flex-1" style="height: 12.0083px; border-radius: 1px;">
                                    </div>
                                    <div class="bg-slate-600 flex-1" style="height: 22.2339px; border-radius: 1px;">
                                    </div>
                                    <div class="bg-slate-600 flex-1" style="height: 17.0701px; border-radius: 1px;">
                                    </div>
                                    <div class="bg-slate-600 flex-1" style="height: 10.9305px; border-radius: 1px;">
                                    </div>
                                    <div class="bg-slate-600 flex-1" style="height: 10.1455px; border-radius: 1px;">
                                    </div>
                                    <div class="bg-slate-600 flex-1" style="height: 13.6297px; border-radius: 1px;">
                                    </div>
                                    <div class="bg-slate-600 flex-1" style="height: 24.4275px; border-radius: 1px;">
                                    </div>
                                    <div class="bg-slate-600 flex-1" style="height: 19.7989px; border-radius: 1px;">
                                    </div>
                                    <div class="bg-slate-600 flex-1" style="height: 10.9648px; border-radius: 1px;">
                                    </div>
                                    <div class="bg-slate-600 flex-1" style="height: 30.6645px; border-radius: 1px;">
                                    </div>
                                    <div class="bg-slate-600 flex-1" style="height: 8.72391px; border-radius: 1px;">
                                    </div>
                                    <div class="bg-slate-600 flex-1" style="height: 11.9103px; border-radius: 1px;">
                                    </div>
                                    <div class="bg-slate-600 flex-1" style="height: 30.5156px; border-radius: 1px;">
                                    </div>
                                    <div class="bg-slate-600 flex-1" style="height: 17.3482px; border-radius: 1px;">
                                    </div>
                                    <div class="bg-slate-600 flex-1" style="height: 19.5226px; border-radius: 1px;">
                                    </div>
                                    <div class="bg-slate-600 flex-1" style="height: 8.23273px; border-radius: 1px;">
                                    </div>
                                    <div class="bg-slate-600 flex-1" style="height: 17.2379px; border-radius: 1px;">
                                    </div>
                                    <div class="bg-slate-600 flex-1" style="height: 10.0951px; border-radius: 1px;">
                                    </div>
                                    <div class="bg-slate-600 flex-1" style="height: 23.0695px; border-radius: 1px;">
                                    </div>
                                    <div class="bg-slate-600 flex-1" style="height: 14.7922px; border-radius: 1px;">
                                    </div>
                                    <div class="bg-slate-600 flex-1" style="height: 27.124px; border-radius: 1px;">
                                    </div>
                                    <div class="bg-slate-600 flex-1" style="height: 28.1033px; border-radius: 1px;">
                                    </div>
                                    <div class="bg-slate-600 flex-1" style="height: 13.6675px; border-radius: 1px;">
                                    </div>
                                    <div class="bg-slate-600 flex-1" style="height: 24.5406px; border-radius: 1px;">
                                    </div>
                                    <div class="bg-slate-600 flex-1" style="height: 29.3883px; border-radius: 1px;">
                                    </div>
                                    <div class="bg-slate-600 flex-1" style="height: 18.7526px; border-radius: 1px;">
                                    </div>
                                    <div class="bg-slate-600 flex-1" style="height: 28.1611px; border-radius: 1px;">
                                    </div>
                                    <div class="bg-slate-600 flex-1" style="height: 18.2592px; border-radius: 1px;">
                                    </div>
                                    <div class="bg-slate-600 flex-1" style="height: 15.3251px; border-radius: 1px;">
                                    </div>
                                    <div class="bg-slate-600 flex-1" style="height: 18.8668px; border-radius: 1px;">
                                    </div>
                                    <div class="bg-slate-600 flex-1" style="height: 11.6687px; border-radius: 1px;">
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center justify-between text-slate-400 text-xs">
                                <div class="flex items-center space-x-4">
                                    <div class="flex items-center space-x-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            class="lucide lucide-heart w-3 h-3">
                                            <path
                                                d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z">
                                            </path>
                                        </svg>
                                        <span>142</span>
                                    </div>
                                    <div class="flex items-center space-x-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            class="lucide lucide-repeat2 w-3 h-3">
                                            <path d="m2 9 3-3 3 3"></path>
                                            <path d="M13 18H7a2 2 0 0 1-2-2V6"></path>
                                            <path d="m22 15-3 3-3-3"></path>
                                            <path d="M11 6h6a2 2 0 0 1 2 2v10"></path>
                                        </svg>
                                        <span>18</span>
                                    </div>
                                    <div class="flex items-center space-x-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            class="lucide lucide-bar-chart3 w-3 h-3">
                                            <path d="M3 3v18h18"></path>
                                            <path d="M18 17V9"></path>
                                            <path d="M13 17V5"></path>
                                            <path d="M8 17v-3"></path>
                                        </svg>
                                        <span>5</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-center py-8 border-t border-slate-700 lg-mt-6">
                <div class="w-16 h-16 bg-slate-700 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="lucide lucide-plus w-8 h-8 text-slate-400">
                        <path d="M5 12h14"></path>
                        <path d="M12 5v14"></path>
                    </svg>
                </div>
                <h4 class="text-white font-medium mb-2">No upcoming campaigns scheduled</h4>
                <p class="text-slate-400 text-sm mb-4">Submit a track to start a new campaign</p>
                <button
                    class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg font-medium transition-colors">Create
                    Campaign</button>
            </div>
        </div>
        <div
            class="bg-slate-800 rounded-lg lg-px-6 lg-py-8 lg-mr-8 lg-ml-0 border border-slate-700 sm:ml-4 sm:mr-3 mt-2">
            <div class="flex items-center justify-between lg-mb-6 p-2">
                <div>
                    <h3 class="text-white text-lg font-semibold">Latest Repost Requests</h3>
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
                            <h4 class="text-white font-medium text-sm">The Beginning</h4>
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
                        <span class="text-white font-medium">Trending</span>
                    </div>
                    <a class="text-orange-500 hover:text-orange-400 text-sm" href="/charts" data-discover="true">View
                        charts</a>
                </div>
                <div class="space-y-2">
                    <div class="flex items-center justify-between text-sm">
                        <div class="flex items-center space-x-2">
                            <span class="text-orange-500 font-bold">#1</span>
                            <span class="text-white">Why Do I?</span>
                        </div>
                        <span class="text-slate-400">EMMAAG</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <div class="flex items-center space-x-2">
                            <span class="text-slate-400 font-bold">#2</span>
                            <span class="text-white">The Strength Of Love</span>
                        </div>
                        <span class="text-slate-400">Constellation Lyra</span>
                    </div>
                </div>
            </div>
        </div>
    </div>




</x-user::layout>
