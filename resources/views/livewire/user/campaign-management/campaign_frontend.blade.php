<div>
    <x-slot name="page_slug">campaign-feed</x-slot>
    <!-- Dashboard Section -->
    <section class="flex flex-col lg:flex-row gap-6 p-6">

        <!-- LEFT SIDE -->
        <div class="w-full">
            <!-- Header Section -->
            <div class="w-full mt-4 relative">
                <div
                    class="flex flex-col-reverse 2xl:flex-row justify-between border-b border-gray-200 dark:border-gray-700 gap-2">

                    <!-- Tabs -->
                    <nav class="-mb-px flex space-x-8">
                        <button class="border-b-2 border-orange-500 text-orange-600 py-3 px-2 font-semibold">
                            Recommended Pro <span class="text-xs ml-2 text-orange-500">12</span>
                        </button>
                        <button
                            class="border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 py-3 px-2 font-semibold">
                            Recommended <span class="text-xs ml-2 text-orange-500">8</span>
                        </button>
                        <button
                            class="border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 py-3 px-2 font-semibold">
                            All <span class="text-xs ml-2 text-orange-500">25</span>
                        </button>
                    </nav>

                    <!-- Start Campaign & Toggle Stats -->
                    <div class="flex justify-between items-center">
                        <x-gabutton variant="primary" class="mb-2">
                            <span class="mr-1">＋</span> Start a new campaign
                        </x-gabutton>
                        <button id="toggleStatsBtn"
                            class="flex items-center gap-1 text-sm text-orange-500 ml-4 2xl:hidden">
                            <span id="toggleText">Show Stats</span>
                            <svg xmlns="http://www.w3.org/2000/svg" id="toggleIcon" class="w-4 h-4" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="m6 9 6 6 6-6" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Stats Panel -->
            <div id="statsPanel"
                class="hidden my-6 bg-white dark:bg-gray-800 p-4 rounded-xl shadow border border-gray-200 dark:border-gray-700">
                <div class="max-w-screen-xl h-full mx-auto flex flex-col gap-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 xl:grid-cols-2 gap-4">

                        <div
                            class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow hover:shadow-lg transition-transform transform hover:-translate-y-1">
                            <div class="flex items-center gap-2"><span>💰</span><span
                                    class="text-sm font-medium text-gray-500 dark:text-gray-400">Earnings per
                                    Repost</span></div>
                            <div class="text-xl font-semibold mt-3">$5.00</div>
                        </div>

                        <div
                            class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow hover:shadow-lg transition-transform transform hover:-translate-y-1">
                            <div class="flex items-center gap-2 font-medium"><span>📈</span><span
                                    class="text-sm font-medium text-gray-500 dark:text-gray-400">Daily repost
                                    limit</span></div>
                            <div class="text-xl font-semibold mt-3" id="repost-limit-value">8 / 20</div>
                            <div class="mt-2 bg-gray-200 dark:bg-gray-700 h-1.5 rounded-full overflow-hidden">
                                <div id="repost-progress" class="bg-orange-500 h-full transition-all duration-500"
                                    data-value="8" data-max="20" style="width: 0%;"></div>
                            </div>
                        </div>

                        <div
                            class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow hover:shadow-lg transition-transform transform hover:-translate-y-1">
                            <div class="flex items-center gap-2 font-medium"><span>📊</span><span
                                    class="text-sm font-medium text-gray-500 dark:text-gray-400">Response Rate</span>
                            </div>
                            <div class="flex justify-between items-center mt-3">
                                <div class="text-xl font-semibold">87%</div>
                                <button class="text-sm font-semibold text-orange-500 hover:underline">Reset</button>
                            </div>
                            <div class="mt-2 bg-gray-200 dark:bg-gray-700 h-1.5 rounded-full overflow-hidden">
                                <div class="bg-orange-500 h-full" style="width: 87%;"></div>
                            </div>
                        </div>

                        <div
                            class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow hover:shadow-lg transition-transform transform hover:-translate-y-1">
                            <div class="flex items-center gap-2"><span>🔔</span><span
                                    class="text-sm font-medium text-gray-500 dark:text-gray-400">Pending Direct
                                    Requests</span></div>
                            <div class="text-xl font-semibold mt-3">4 / 25</div>
                            <a href="#"
                                class="inline-block mt-2 text-sm font-semibold text-orange-500 hover:underline">Get
                                higher limit</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Campaign Cards -->
            <div class="mt-8 space-y-4">
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-md shadow-sm">
                    <div class="flex flex-col lg:flex-row">
                        <div class="w-full lg:w-1/2 bg-gray-100 dark:bg-gray-700 p-4">
                            <div
                                class="h-40 bg-gray-300 dark:bg-gray-600 rounded flex items-center justify-center text-gray-600 dark:text-gray-300">
                                🎵 SoundCloud Player Placeholder</div>
                        </div>

                        <!-- Right Details -->
                        <div class="w-full lg:w-1/2 p-4 flex flex-col justify-between">
                            <div class="flex flex-wrap items-center justify-between mb-3 gap-4">

                                <!-- Left: Avatar + Artist Info -->
                                <div class="flex flex-1 min-w-[200px] gap-3 items-center">
                                    <img src="https://placehold.co/60x60" class="w-14 h-14 rounded-full" alt="Avatar">
                                    <div class="flex flex-col space-y-2">
                                        <div class="flex items-center gap-2">
                                            <span class="font-semibold dark:text-white">Artist Name</span>
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="w-6 h-6 text-gray-600 dark:text-gray-400" fill="none"
                                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <path d="m6 9 6 6 6-6" />
                                            </svg>
                                        </div>
                                        <span class="flex items-center text-gray-600 dark:text-gray-400">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-star-icon lucide-star">
                                                <path
                                                    d="M11.525 2.295a.53.53 0 0 1 .95 0l2.31 4.679a2.123 2.123 0 0 0 1.595 1.16l5.166.756a.53.53 0 0 1 .294.904l-3.736 3.638a2.123 2.123 0 0 0-.611 1.878l.882 5.14a.53.53 0 0 1-.771.56l-4.618-2.428a2.122 2.122 0 0 0-1.973 0L6.396 21.01a.53.53 0 0 1-.77-.56l.881-5.139a2.122 2.122 0 0 0-.611-1.879L2.16 9.795a.53.53 0 0 1 .294-.906l5.165-.755a2.122 2.122 0 0 0 1.597-1.16z" />
                                            </svg>
                                        </span>
                                    </div>
                                </div>

                                <!-- Middle: Remaining Stats -->
                                <div
                                    class="flex flex-col items-center sm:items-start text-gray-600 dark:text-gray-400 flex-none">
                                    <div class="flex items-center gap-1.5">
                                        <svg width="26" height="18" viewBox="0 0 26 18" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <rect x="1" y="1" width="24" height="16" rx="3"
                                                fill="none" stroke="currentColor" stroke-width="2" />
                                            <circle cx="8" cy="9" r="3" fill="none"
                                                stroke="currentColor" stroke-width="2" />
                                        </svg>
                                        <span class="text-sm sm:text-base">12</span>
                                    </div>
                                    <span class="text-xs text-gray-500 dark:text-gray-500 mt-1">REMAINING</span>
                                </div>

                                <!-- Right: Repost Button -->
                                <div class="w-full sm:w-auto flex justify-end">
                                    <button
                                        class="bg-orange-600 text-white px-4 py-2 rounded-md text-md font-medium hover:bg-orange-700 flex items-center gap-2 w-full sm:w-auto justify-center">
                                        <svg class="w-6 h-6" viewBox="0 0 26 18" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <rect x="1" y="1" width="24" height="16" rx="3"
                                                fill="none" stroke="currentColor" stroke-width="2" />
                                            <circle cx="8" cy="9" r="3" fill="none"
                                                stroke="currentColor" stroke-width="2" />
                                        </svg>
                                        <span>10 Repost</span>
                                    </button>
                                </div>
                            </div>

                            <!-- Bottom: Genre Tag -->
                            <div>
                                <span
                                    class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm px-3 py-1.5 rounded-md">Genre:
                                    Pop</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        // Stats toggle
        document.getElementById('toggleStatsBtn').addEventListener('click', () => {
            const stats = document.getElementById('statsPanel');
            const text = document.getElementById('toggleText');
            stats.classList.toggle('hidden');
            text.textContent = stats.classList.contains('hidden') ? 'Show Stats' : 'Hide Stats';
        });

        // Animate repost progress
        document.addEventListener('DOMContentLoaded', () => {
            const progressBar = document.getElementById('repost-progress');
            const current = parseInt(progressBar.dataset.value, 10);
            const max = parseInt(progressBar.dataset.max, 10);
            if (!isNaN(current) && !isNaN(max)) {
                const percentage = (current / max) * 100;
                setTimeout(() => {
                    progressBar.style.width = percentage + '%';
                }, 200);
            }
        });
    </script>
</div>
