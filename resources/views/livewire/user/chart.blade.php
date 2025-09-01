<div>
    <x-slot name="page_slug">chart</x-slot>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class=" flex items-center justify-center p-6">

        <div class="w-full">
            <!-- Header -->
            <h2 class="text-2xl font-bold  dark:text-white text-gray-800 mb-6">Performance Charts</h2>
            <p class="dark:text-gray-400 text-gray-800 mb-8">Track your campaign performance with detailed analytics
                and
                insights</p>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="dark:bg-gray-800 rounded-lg p-6 border border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium dark:bg-gray-800 dark:text-gray-300">Total Campaigns</p>
                            <p class="text-2xl font-bold text-gray-800 dark:text-gray-300">24</p>
                        </div>
                        <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-orange-500">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M19.114 5.636a9 9 0 0 1 0 12.728M16.463 8.288a5.25 5.25 0 0 1 0 7.424M6.75 8.25l4.72-4.72a.75.75 0 0 1 1.28.53v15.88a.75.75 0 0 1-1.28.53l-4.72-4.72H4.51c-.88 0-1.704-.507-1.938-1.354A9.009 9.009 0 0 1 2.25 12c0-.83.112-1.633.322-2.396C2.806 8.756 3.63 8.25 4.51 8.25H6.75Z" />
                            </svg>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center">
                        <span class="text-green-500 text-sm font-medium">+12%</span>
                        <span class="text-gray-500 text-sm ml-2">from last month</span>
                    </div>
                </div>

                <div class="dark:bg-gray-800 rounded-lg p-6 border border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium dark:bg-gray-800 dark:text-gray-300">Total Reach</p>
                            <p class="text-2xl font-bold text-gray-800 dark:text-gray-300">45.2K</p>
                        </div>
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-blue-500">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            </svg>

                        </div>
                    </div>
                    <div class="mt-4 flex items-center">
                        <span class="text-green-500 text-sm font-medium">+8%</span>
                        <span class="text-gray-500 text-sm ml-2">from last month</span>
                    </div>
                </div>

                <div class="dark:bg-gray-800 rounded-lg p-6 border border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium dark:bg-gray-800 dark:text-gray-300">Engagement Rate</p>
                            <p class="text-2xl font-bold text-gray-800 dark:text-gray-300">3.4%</p>
                        </div>
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-green-500">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                            </svg>

                        </div>
                    </div>
                    <div class="mt-4 flex items-center">
                        <span class="text-red-500 text-sm font-medium">-2%</span>
                        <span class="text-gray-500 text-sm ml-2">from last month</span>
                    </div>
                </div>

                <div class="dark:bg-gray-800 rounded-lg p-6 border border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium dark:bg-gray-800 dark:text-gray-300">Conversion Rate</p>
                            <p class="text-2xl font-bold text-gray-800 dark:text-gray-300">2.1%</p>
                        </div>
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-purple-500">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" />
                            </svg>

                        </div>
                    </div>
                    <div class="mt-4 flex items-center">
                        <span class="text-green-500 text-sm font-medium">+5%</span>
                        <span class="text-gray-500 text-sm ml-2">from last month</span>
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Campaign Performance -->
                <div class="dark:bg-gray-800 px-6 py-3 rounded-sm shadow">
                    <h3 class="text-lg font-semibold dark:bg-gray-800 dark:text-gray-300 mb-2">Campaign Performance
                    </h3>
                    <canvas id="campaignChart"></canvas>
                </div>

                <!-- Engagement Trends -->
                <div class="dark:bg-gray-800 px-6 py-3 rounded-sm shadow">
                    <h3 class="text-lg font-semibold dark:bg-gray-800 dark:text-gray-300 mb-2">Engagement Trends
                    </h3>
                    <canvas id="engagementChart"></canvas>
                </div>

                <!-- Traffic Sources -->
                <div class="dark:bg-gray-800 px-6 py-3 rounded-sm shadow">
                    <h3 class="text-lg font-semibold dark:bg-gray-800 dark:text-gray-300 mb-1">
                        Traffic Sources
                    </h3>
                    <div class="#">
                        <canvas id="trafficChart"></canvas>
                    </div>
                </div>

                <!-- Revenue Growth -->
                <div class="dark:bg-gray-800 px-6 py-3 rounded-sm shadow">
                    <h3 class="text-lg font-semibold dark:bg-gray-800 dark:text-gray-300 mb-2">Revenue Growth</h3>
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>

        </div>

        <script>
            // Campaign Performance (Line Chart)
            new Chart(document.getElementById('campaignChart'), {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                    datasets: [{
                        label: 'Performance',
                        data: [12, 20, 3, 6, 5, 7],
                        borderColor: '#f97316',
                        backgroundColor: 'rgba(249, 115, 22, 0.2)',
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true
                }
            });

            // Engagement Trends (Bar Chart)
            new Chart(document.getElementById('engagementChart'), {
                type: 'bar',
                data: {
                    labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                    datasets: [{
                        label: 'Engagement',
                        data: [60, 55, 80, 78, 52, 50, 40],
                        backgroundColor: '#f97316'
                    }]
                },
                options: {
                    responsive: true
                }
            });

            // Traffic Sources (Doughnut Chart)
            new Chart(document.getElementById('trafficChart'), {
                type: 'doughnut',
                data: {
                    labels: ['Direct', 'Social', 'Email', 'Search'],
                    datasets: [{
                        data: [30, 25, 20, 25],
                        backgroundColor: ['#f97316', '#3b82f6', '#22c55e', '#facc15']
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });

            // Revenue Growth (Line Chart)
            new Chart(document.getElementById('revenueChart'), {
                type: 'line',
                data: {
                    labels: ['Q1', 'Q2', 'Q3', 'Q4'],
                    datasets: [{
                        label: 'Revenue',
                        data: [10000, 15000, 12000, 18000],
                        borderColor: '#22c55e',
                        backgroundColor: 'rgba(34, 197, 94, 0.2)',
                        fill: false,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true
                }
            });
        </script>


    </div>
</div>
