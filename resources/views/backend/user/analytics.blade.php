<x-user::layout>
    <x-slot name="page_slug">analytics</x-slot>

    <div>

        <div class="mx-auto space-y-8">
            <div>
                <h1 class="text-3xl font-bold dark:text-white text-gray-800">Analytics Dashboard</h1>
                <p class="text-gray-500">Comprehensive insights into your campaign performance and audience engagement
                </p>
            </div>

            <div class="flex items-center space-x-4">
                <button class="px-4 py-2 bg-orange-500 text-white rounded-lg font-medium">Last 7 days</button>
                <button class="bg-white text-gray-700 font-medium px-4 py-2 rounded-lg text-sm custom-shadow">Last 30
                    days</button>
                <button class="bg-white text-gray-700 font-medium px-4 py-2 rounded-lg text-sm custom-shadow">Last 90
                    days</button>
                <button class="bg-white text-gray-700 font-medium px-4 py-2 rounded-lg text-sm custom-shadow">Custom
                    Range</button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                <div class="dark:bg-gray-800 bg-white p-6 rounded-lg custom-shadow">
                    <div class="flex justify-between items-start mb-4">
                        <h2 class="dark:text-white text-gray-800 font-medium">Total Impressions</h2>
                        <span class="material-icons text-gray-400">info</span>
                    </div>
                    <div class="flex flex-col space-y-1">
                        <p class="text-4xl font-bold text-gray-800 dark:text-gray-300">127.5K</p>
                        <div class="flex items-center text-green-500 text-sm">
                            <span class="material-icons text-base">arrow_upward</span>
                            <span>15.3% <span class="text-gray-500">vs last period</span></span>
                        </div>
                    </div>
                </div>

                <div class="dark:bg-gray-800 bg-white p-6 rounded-lg custom-shadow">
                    <div class="flex justify-between items-start mb-4">
                        <h2 class="dark:text-white text-gray-800 font-medium">Click-through Rate</h2>
                        <span class="material-icons text-orange-500">trending_up</span>
                    </div>
                    <div class="flex flex-col space-y-1">
                        <p class="text-4xl font-bold text-gray-800 dark:text-gray-300">4.2%</p>
                        <div class="flex items-center text-green-500 text-sm">
                            <span class="material-icons text-base">arrow_upward</span>
                            <span>2.1% <span class="text-gray-500">vs last period</span></span>
                        </div>
                    </div>
                </div>

                <div class="dark:bg-gray-800 bg-white p-6 rounded-lg custom-shadow">
                    <div class="flex justify-between items-start mb-4">
                        <h2 class="text-gray-800 dark:text-gray-300 font-medium">Cost Per Click</h2>
                        <span class="text-green-500 font-bold">$</span>
                    </div>
                    <div class="flex flex-col space-y-1">
                        <p class="text-4xl font-bold text-gray-800 dark:text-gray-300">$0.85</p>
                        <div class="flex items-center text-red-500 text-sm">
                            <span class="material-icons text-base">arrow_downward</span>
                            <span>5.2% <span class="text-gray-500">vs last period</span></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="dark:bg-gray-800 bg-white p-6 rounded-lg custom-shadow">
                    <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-300 mb-6">Top Performing Campaigns
                    </h2>
                    <div class="space-y-6">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="font-medium text-gray-800 dark:text-gray-300">Summer Music Festival</p>
                                <p class="text-sm text-gray-500">Active • 12 days remaining</p>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-gray-800 dark:text-gray-300">15.2K</p>
                                <p class="text-sm text-gray-500">impressions</p>
                            </div>
                        </div>
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="font-medium text-gray-800 dark:text-gray-300">New Album Launch</p>
                                <p class="text-sm text-gray-500">Active • 8 days remaining</p>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-gray-800 dark:text-gray-300">12.8K</p>
                                <p class="text-sm text-gray-500">impressions</p>
                            </div>
                        </div>
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="font-medium text-gray-800 dark:text-gray-300">Artist Collaboration</p>
                                <p class="text-sm text-gray-500">Completed</p>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-gray-800 dark:text-gray-300">9.4K</p>
                                <p class="text-sm text-gray-500">impressions</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="dark:bg-gray-800 bg-white p-6 rounded-lg custom-shadow">
                    <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-300 mb-6">Audience Demographics</h2>
                    <div class="space-y-6">
                        <div>
                            <div class="flex justify-between items-center text-gray-800 dark:text-gray-300 mb-1">
                                <p>18-24 years</p>
                                <p class="font-bold text-gray-800 dark:text-gray-300">35%</p>
                            </div>
                            <div class="bg-gray-200 rounded-full h-2">
                                <div class="bg-orange-500 rounded-full h-full" style="width: 35%;"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between items-center text-gray-800 dark:text-gray-300 mb-1">
                                <p>25-34 years</p>
                                <p class="font-bold">28%</p>
                            </div>
                            <div class="bg-gray-200 rounded-full h-2">
                                <div class="bg-orange-500 rounded-full h-full" style="width: 28%;"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between items-center text-gray-800 dark:text-gray-300 mb-1">
                                <p>35-44 years</p>
                                <p class="font-bold">22%</p>
                            </div>
                            <div class="bg-gray-200 rounded-full h-2">
                                <div class="bg-orange-500 rounded-full h-full" style="width: 22%;"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between items-center text-gray-800 dark:text-gray-300 mb-1">
                                <p>45+ years</p>
                                <p class="font-bold">15%</p>
                            </div>
                            <div class="bg-gray-200 rounded-full h-2">
                                <div class="bg-orange-500 rounded-full h-full" style="width: 15%;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class= "dark:bg-gray-800 bg-white  p-6 rounded-lg custom-shadow">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-300 mb-6">Recent Activity</h2>
                <div class="space-y-4">
                    <div class="flex items-center space-x-4 p-4 rounded-lg dark:bg-gray-800 bg-white">
                        <span class="material-icons text-orange-500 bg-orange-100 rounded-full p-2">campaign</span>
                        <div>
                            <p class="font-medium text-gray-800 dark:text-gray-300">New campaign "Holiday Special"
                                launched</p>
                            <p class="text-sm text-gray-500">2 hours ago</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4 p-4 rounded-lg dark:bg-gray-800 bg-white">
                        <span class="material-icons text-blue-500 bg-blue-100 rounded-full p-2">insights</span>
                        <div>
                            <p class="font-medium text-gray-800 dark:text-gray-300">Campaign performance report
                                generated</p>
                            <p class="text-sm text-gray-500">5 hours ago</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4 p-4 rounded-lg dark:bg-gray-800 bg-white">
                        <span class="material-icons text-green-500 bg-green-100 rounded-full p-2">check_circle</span>
                        <div>
                            <p class="font-medium text-gray-800 dark:text-gray-300">Campaign "Summer Vibes" completed
                                successfully</p>
                            <p class="text-sm text-gray-500">1 day ago</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

</x-user::layout>
