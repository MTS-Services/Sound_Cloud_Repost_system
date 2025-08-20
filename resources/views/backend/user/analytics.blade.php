<x-user::layout>
    <x-slot name="page_slug">analytics</x-slot>

    <div>

        <div class="mx-auto space-y-8">
            <div>
                <h1 class="text-3xl font-bold dark:text-white text-gray-800">Analytics Dashboard</h1>
                <p class="text-gray-500">Comprehensive insights into your campaign performance and audience engagement
                </p>
            </div>

            <div class="flex flex-wrap gap-2 sm:flex-nowrap sm:space-x-4">
                <button class="px-4 py-2 bg-orange-500 text-white rounded-lg font-medium w-full sm:w-auto">
                    Last 7 days
                </button>
                <button
                    class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg font-medium hover:bg-gray-200 w-full sm:w-auto">
                    Last 30 days
                </button>
                <button
                    class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg font-medium hover:bg-gray-200 w-full sm:w-auto">
                    Last 90 days
                </button>
                <button
                    class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg font-medium hover:bg-gray-200 w-full sm:w-auto">
                    Custom Range
                </button>
            </div>


            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                <div class="dark:bg-gray-800 bg-white p-6 rounded-lg custom-shadow border border-gray-200">
                    <div class="flex justify-between items-start mb-4">
                        <h2 class="dark:text-white text-gray-800 font-medium">Total Impressions</h2>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                            class="w-6 h-6 text-blue-500">
                            <path fill-rule="evenodd"
                                d="M3.28 2.22a.75.75 0 0 0-1.06 1.06l14.5 14.5a.75.75 0 1 0 1.06-1.06l-1.745-1.745a10.029 10.029 0 0 0 3.3-4.38 1.651 1.651 0 0 0 0-1.185A10.004 10.004 0 0 0 9.999 3a9.956 9.956 0 0 0-4.744 1.194L3.28 2.22ZM7.752 6.69l1.092 1.092a2.5 2.5 0 0 1 3.374 3.373l1.091 1.092a4 4 0 0 0-5.557-5.557Z"
                                clip-rule="evenodd" />
                            <path
                                d="m10.748 13.93 2.523 2.523a9.987 9.987 0 0 1-3.27.547c-4.258 0-7.894-2.66-9.337-6.41a1.651 1.651 0 0 1 0-1.186A10.007 10.007 0 0 1 2.839 6.02L6.07 9.252a4 4 0 0 0 4.678 4.678Z" />
                        </svg>
                    </div>
                    <div class="flex flex-col space-y-1">
                        <p class="text-4xl font-bold text-gray-800 dark:text-gray-300">127.5K</p>
                        <div class="flex items-center">
                            <span class="text-green-500 text-sm font-medium">↗ 15.3%</span>
                            <span class="text-gray-500 text-sm ml-2">vs last period</span>
                        </div>
                    </div>
                </div>

                <div class="dark:bg-gray-800 bg-white p-6 rounded-lg custom-shadow border border-gray-200">
                    <div class="flex justify-between items-start mb-4">
                        <h2 class="dark:text-white text-gray-800 font-medium">Click-through Rate</h2>
                        <span class="text-orange-500 font-bold">👆</span>
                    </div>
                    <div class="flex flex-col space-y-1">
                        <p class="text-4xl font-bold text-gray-800 dark:text-gray-300">4.2%</p>
                        <div class="flex items-center">
                            <span class="text-green-500 text-sm font-medium">↗ 2.3%</span>
                            <span class="text-gray-500 text-sm ml-2">vs last period</span>
                        </div>
                    </div>
                </div>

                <div class="dark:bg-gray-800 bg-white p-6 rounded-lg custom-shadow border border-gray-200">
                    <div class="flex justify-between items-start mb-4">
                        <h2 class="text-gray-800 dark:text-gray-300 font-medium">Cost Per Click</h2>
                        <span class="text-green-500 font-bold">$</span>
                    </div>
                    <div class="flex flex-col space-y-1">
                        <p class="text-4xl font-bold text-gray-800 dark:text-gray-300">$0.85</p>
                        <div class="flex items-center">
                            <span class="text-green-500 text-sm font-medium">↗ 5.3%</span>
                            <span class="text-gray-500 text-sm ml-2">vs last period</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="dark:bg-gray-800 bg-white p-6 rounded-lg custom-shadow border border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-300 mb-6">Top Performing Campaigns
                    </h2>
                    <div class="space-y-6">
                        <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 da rounded-lg">
                            <div>
                                <p class="font-medium text-gray-800 dark:text-gray-200">Summer Music Festival</p>
                                <p class="text-sm text-gray-300">Active • 12 days remaining</p>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-gray-800 dark:text-gray-200">15.2K</p>
                                <p class="text-sm text-gray-300">impressions</p>
                            </div>
                        </div>
                        <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <div>
                                <p class="font-medium text-gray-800 dark:text-gray-200">New Album Launch</p>
                                <p class="text-sm text-gray-300">Active • 8 days remaining</p>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-gray-800 dark:text-gray-200">12.8K</p>
                                <p class="text-sm text-gray-300">impressions</p>
                            </div>
                        </div>
                        <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <div>
                                <p class="font-medium text-gray-800 dark:text-gray-200">Artist Collaboration</p>
                                <p class="text-sm text-gray-300">Completed</p>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-gray-800 dark:text-gray-200">9.4K</p>
                                <p class="text-sm text-gray-300">impressions</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="dark:bg-gray-800 bg-white p-6 rounded-lg custom-shadow border border-gray-200">
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

            <div class= "mt-8 dark:bg-gray-800 bg-white p-6 rounded-lg p-6 border border-gray-200">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-300 mb-6">Recent Activity</h2>
                <div class="space-y-4">
                    <div class="flex items-center space-x-4 p-4 border-l-4 border-orange-500 bg-orange-50">
                        <div class="w-10 h-10 bg-orange-500 rounded-full flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-white">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M14.25 9.75v-4.5m0 4.5h4.5m-4.5 0 6-6m-3 18c-8.284 0-15-6.716-15-15V4.5A2.25 2.25 0 0 1 4.5 2.25h1.372c.516 0 .966.351 1.091.852l1.106 4.423c.11.44-.054.902-.417 1.173l-1.293.97a1.062 1.062 0 0 0-.38 1.21 12.035 12.035 0 0 0 7.143 7.143c.441.162.928-.004 1.21-.38l.97-1.293a1.125 1.125 0 0 1 1.173-.417l4.423 1.106c.5.125.852.575.852 1.091V19.5a2.25 2.25 0 0 1-2.25 2.25h-2.25Z" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <div class="font-medium text-gray-900">New campaign "Holiday Special" launched</div>
                            <div class="text-sm text-gray-500">2 hours ago</div>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4 p-4 border-l-4 border-blue-500 bg-blue-50">
                        <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-white">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25M9 16.5v.75m3-3v3M15 12v5.25m-4.5-15H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                            </svg>

                        </div>
                        <div class="flex-1">
                            <div class="font-medium text-gray-900">Campaign performance report generated</div>
                            <div class="text-sm text-gray-500">5 hours ago</div>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4 p-4 border-l-4 border-green-500 bg-green-50">
                        <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-white">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                            </svg>

                        </div>
                        <div class="flex-1">
                            <div class="font-medium text-gray-900">Campaign "Summer Vibes" completed successfully</div>
                            <div class="text-sm text-gray-500">1 day ago</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

</x-user::layout>
