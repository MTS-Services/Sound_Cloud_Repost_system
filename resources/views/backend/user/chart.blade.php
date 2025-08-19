<x-user::layout>
    <x-slot name="page_slug">chart</x-slot>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class=" flex items-center justify-center p-6">

        <div class="w-full">
            <!-- Header -->
            <h2 class="text-2xl font-bold  dark:text-white text-gray-800 mb-6">Performance Charts</h2>
            <p class="dark:text-gray-400 text-gray-800 mb-8">Track your campaign performance with detailed analytics and
                insights</p>

            <!-- Stats -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="dark:bg-gray-800  p-8 rounded-sm shadow">
                    <h3 class="text-sm font-medium dark:bg-gray-800 dark:text-gray-300">Total Campaigns</h3>
                    <p class="text-2xl font-bold text-gray-800 dark:text-gray-300">24</p>
                    <p class="text-sm text-green-600">+12% from last month</p>
                </div>
                <div class="dark:bg-gray-800 p-8 rounded-sm shadow">
                    <h3 class="text-sm font-medium dark:bg-gray-800 dark:text-gray-300">Total Reach</h3>
                    <p class="text-2xl font-bold text-gray-800 dark:text-gray-300">45.2K</p>
                    <p class="text-sm text-green-600">+8% from last month</p>
                </div>
                <div class="dark:bg-gray-800 p-8 rounded-sm shadow">
                    <h3 class="text-sm font-medium dark:bg-gray-800 dark:text-gray-300">Engagement Rate</h3>
                    <p class="text-2xl font-bold text-gray-800 dark:text-gray-300">3.4%</p>
                    <p class="text-sm text-red-600">-2% from last month</p>
                </div>
                <div class="dark:bg-gray-800 p-8 rounded-sm shadow">
                    <h3 class="text-sm font-medium dark:bg-gray-800 dark:text-gray-300">Conversion Rate</h3>
                    <p class="text-2xl font-bold text-gray-800 dark:text-gray-300">2.1%</p>
                    <p class="text-sm text-green-600">+5% from last month</p>
                </div>
            </div>

            <!-- Charts -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Campaign Performance -->
                <div class="dark:bg-gray-800 p-6 rounded-sm shadow">
                    <h3 class="text-lg font-semibold dark:bg-gray-800 dark:text-gray-300 mb-4">Campaign Performance</h3>
                    <canvas id="campaignChart"></canvas>
                </div>

                <!-- Engagement Trends -->
                <div class="dark:bg-gray-800 p-6 rounded-sm shadow">
                    <h3 class="text-lg font-semibold dark:bg-gray-800 dark:text-gray-300 mb-4">Engagement Trends</h3>
                    <canvas id="engagementChart"></canvas>
                </div>

                <!-- Traffic Sources -->
                <div class="dark:bg-gray-800 p-10 rounded-sm shadow">
                    <h3 class="text-lg font-semibold dark:bg-gray-800 dark:text-gray-300 mb-4">Traffic Sources</h3>
                    <canvas id="trafficChart"></canvas>
                </div>

                <!-- Revenue Growth -->
                <div class="dark:bg-gray-800 p-6 rounded-sm shadow">
                    <h3 class="text-lg font-semibold dark:bg-gray-800 dark:text-gray-300 mb-4">Revenue Growth</h3>
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
                    responsive: true
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
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true
                }
            });
        </script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    </div>

    </html>
</x-user::layout>
