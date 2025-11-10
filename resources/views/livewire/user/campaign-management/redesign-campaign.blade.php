<main>
    <x-slot name="page_slug">campaign-feed</x-slot>
    <x-slot name="title">Campaign Feed</x-slot>
    <!-- Dashboard Section -->
    <section class="flex flex-col lg:flex-row gap-6 p-6">
        <!-- LEFT SIDE -->
        <div class="w-full">
            <!-- Header Section -->
            <div class="w-full mt-4 relative">
                <div
                    class="flex flex-col-reverse 2xl:flex-row justify-between border-b border-gray-200 dark:border-gray-700 gap-2">

                    <nav class="-mb-px flex space-x-8">
                        <a href="{{ route('user.cm.campaigns2', ['tab' => 'recommendedPro']) }}" wire:navigate
                            class="border-b-2 border-orange-500 text-orange-600 py-3 px-2 font-semibold">
                            Recommended Pro <span class="text-xs ml-2 text-orange-500">12</span>
                        </a>
                        <a href="{{ route('user.cm.campaigns2', ['tab' => 'recommended']) }}" wire:navigate
                            class="border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 py-3 px-2 font-semibold">
                            Recommended <span class="text-xs ml-2 text-orange-500">8</span>
                        </a>
                        <a href="{{ route('user.cm.campaigns2', ['tab' => 'all']) }}" wire:navigate
                            class="border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 py-3 px-2 font-semibold">
                            All <span class="text-xs ml-2 text-orange-500">25</span>
                        </a>
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
            
            {{-- <div id="statsPanel"
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
            </div> --}}

            <!-- Campaign Cards -->
            @switch($activeTab)
                @case('all')
                    <livewire:user.campaign-management.all-campaign />
                @break

                @case('recommended')
                    <livewire:user.campaign-management.recommanded-campaign />
                @break

                @default
                    <livewire:user.campaign-management.recommanded-pro-campaign />
            @endswitch
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
    </script>
</main>
