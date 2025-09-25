<section>
    @props([
        'earnings',
        'dailyRepostCurrent',
        'dailyRepostMax',
        'responseRate',
        'pendingRequests',
        'requestLimit',
        'credits',
        'campaigns',
        'campaignLimit',
    ])

    <style>
        .prograss-fill {
            width: 0%;
        }
    </style>

    <div class="max-w-screen-xl max-h-screen mx-auto flex flex-col gap-y-4">
        <!-- Stats Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 lg:col-span-2">

            <!-- Stat Card -->
            <div
                class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow hover:shadow-lg transition-transform transform hover:-translate-y-1">
                <div class="flex items-center gap-2">
                    <span><x-lucide-bar-chart-2 class="w-6 h-6 text-orange-500" /></span>
                    <span class="text-sm font-medium text-[#718096] dark:text-gray-100">Earnings per Repost</span>
                </div>
                <div class="text-xl font-semibold mt-3 dark:text-gray-100">{{ $earnings }}</div>
            </div>

            <!-- Repost Limit -->
            <div
                class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow hover:shadow-lg transition-transform transform hover:-translate-y-1">
                <div class="flex items-center gap-2 font-medium">
                    <span><x-lucide-gauge-circle class="w-6 h-6 text-orange-500" /></span>
                    <span class="text-sm font-medium text-[#718096] dark:text-gray-100">Daily repost limit</span>
                </div>
                <div class="text-xl font-semibold mt-3 dark:text-gray-100" id="repost-limit-value">
                    {{ $dailyRepostCurrent }}/{{ $dailyRepostMax }}</div>
                <div class="mt-2 bg-[#e2e8f0] dark:bg-gray-700 h-1.5 rounded-full overflow-hidden">
                    <div id="repost-progress" class="prograss-fill bg-orange-500 h-full transition-all duration-500"
                        data-value="{{ $dailyRepostCurrent }}" data-max="{{ $dailyRepostMax }}"></div>
                </div>
            </div>

            <!-- Response Rate -->
            <div
                class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow hover:shadow-lg transition-transform transform hover:-translate-y-1">
                <div class="flex items-center gap-2 font-medium">
                    <span><x-lucide-percent-circle class="w-6 h-6 text-orange-500" /></span>
                    <span class="text-sm font-medium text-[#718096] dark:text-gray-100">Response Rate</span>
                </div>
                <div class="flex justify-between items-center mt-3">
                    <div class="text-xl font-semibold dark:text-gray-100">{{ $responseRate }}%</div>
                    <a href="#" class="text-sm font-semibold text-orange-500 hover:underline">Reset</a>
                </div>
                <div class="mt-2 bg-[#e2e8f0] dark:bg-gray-700 dark:text-gray-700 h-1.5 rounded-full overflow-hidden">
                    <div class="bg-orange-500 h-full" style="width: {{ $responseRate }}%"></div>
                </div>
            </div>

            <!-- Pending Requests -->
            <div
                class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow hover:shadow-lg transition-transform transform hover:-translate-y-1">
                <div class="flex items-center gap-2">
                    <span><x-lucide-bell class="w-6 h-6 text-orange-500" /></span>
                    <span class="text-sm font-medium text-[#718096] dark:text-gray-100">Pending Direct Requests</span>
                </div>
                <div class="text-xl font-semibold mt-3 dark:text-gray-100">{{ $pendingRequests }} / {{ $requestLimit }}
                </div>
                <a href="#" class="inline-block mt-2 text-sm font-semibold text-orange-500 hover:underline">Get
                    higher limit</a>
            </div>

        </div>

        <!-- Promotion Stats -->
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow flex flex-col gap-4">
            <h3 class="text-xl font-semibold dark:text-gray-100">Promotion Stats</h3>

            <div class="flex flex-col sm:flex-row justify-around text-center gap-4">
                <div class="flex-1">
                    <div class="flex items-center justify-center gap-2">
                        <span><x-lucide-credit-card class="w-6 h-6 text-orange-500" /></span>
                        <div class="text-xl font-semibold dark:text-gray-100">{{ $credits }}</div>
                    </div>
                    <div class="text-sm font-medium text-[#718096] dark:text-gray-100">Available Credits</div>
                </div>
                <div class="flex-1">
                    <div class="flex items-center justify-center gap-2">
                        <span><x-lucide-signal class="w-6 h-6 text-orange-500" /></span>
                        <div class="text-xl font-semibold dark:text-gray-100">{{ $campaigns }}/{{ $campaignLimit }}
                        </div>
                    </div>
                    <div class="text-sm font-medium text-[#718096] dark:text-gray-100">Campaign Limit</div>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <button
                    class="flex items-center justify-center gap-2 text-sm font-semibold py-2 border border-[#e2e8f0] dark:border-gray-600 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-50 transition">
                    <span><x-lucide-shopping-cart class="w-6 h-6 text-orange-500" /></span>
                    Buy more credits
                </button>
                <button
                    class="flex items-center justify-center gap-2 text-sm font-semibold py-2 bg-orange-500 text-white rounded-lg hover:opacity-90 transition">
                    <span><x-lucide-rocket class="w-6 h-6 text-white" /></span>
                    Upgrade plan
                </button>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow min-h-56">
            <h2 class="text-xl font-semibold dark:text-gray-50 mb-2">About Direct Requests</h2>
            <p class="text-sm text-[#718096] dark:text-gray-100">
                Lorem, ipsum dolor sit amet consectetur adipisicing elit. Dolorum quibusdam, inventore, quidem numquam
                voluptatibus rem quod beatae vitae repellendus totam odit nostrum velit, in perferendis ut harum minus
                id molestias debitis! Minus architecto provident reprehenderit tempora, quas consequuntur, voluptas
                aliquid earum tenetur autem sint. Maiores velit inventore exercitationem quam iure.
            </p>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const progressBar = document.getElementById('repost-progress');

            if (progressBar) {
                const currentValue = parseInt(progressBar.getAttribute('data-value'), 10);
                const maxValue = parseInt(progressBar.getAttribute('data-max'), 10);

                if (!isNaN(currentValue) && !isNaN(maxValue) && maxValue > 0) {
                    const percentage = (currentValue / maxValue) * 100;
                    progressBar.style.width = '0%';
                    setTimeout(() => {
                        progressBar.style.width = percentage + '%';
                    }, 100);
                }
            }
        });
    </script>

</section>
