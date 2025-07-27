<x-user::layout>

    <x-slot name="page_slug">campains</x-slot>

    <main class=" bg-white dark:bg-gray-900 font-sans p-4 sm:p-6">
        <div class="max-w-8xl mx-auto">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-6">
                <h1 class="text-2xl text-black dark:text-gray-100 font-bold">My Campaigns</h1>
                <button class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded">+ New Campaign</button>
            </div>
            <p class="text-gray-400 mb-6 text-sm sm:text-base">Track the performance of your submitted tracks</p>
            <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-6 border-b pb-3 mb-6">
                <button id="tab-all" onclick="switchTab('all')"
                    class="text-orange-500 border-b-2 border-orange-500 pb-1 w-full sm:w-auto text-center">All
                    Campaigns</button>
                <button id="tab-active" onclick="switchTab('active')"
                    class="text-gray-400 hover:text-gray-300 w-full sm:w-auto text-center">Active</button>
                <button id="tab-completed" onclick="switchTab('completed')"
                    class="text-gray-400 hover:text-gray-300 w-full sm:w-auto text-center">Completed</button>
            </div>

            <!-- Active Campaign -->

            <!-- All Campaigns Section -->

            <div id="section-all">
                <div class="space-y-6">
                    <div class=" rounded-lg border border-slate-700 overflow-hidden">
                        <div class="p-6 sm:p-8">
                            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                                <div class="flex flex-col sm:flex-row sm:items-start gap-4"><img
                                        src="https://images.pexels.com/photos/1540338/pexels-photo-1540338.jpeg?auto=compress&amp;cs=tinysrgb&amp;w=300&amp;h=300&amp;fit=crop"
                                        alt="Sexy - Fashion - Promo" class="w-20 h-20 rounded-lg mx-auto sm:mx-0">
                                    <div class="flex-1">
                                        <div class="flex flex-col sm:flex-row sm:items-center sm:space-x-3 mb-2">
                                            <h3
                                                class=" text-black dark:text-gray-100 font-semibold text-lg text-center sm:text-left">
                                                Sexy -
                                                Fashion
                                                - Promo</h3>
                                            <span
                                                class="px-2 py-1 rounded-full text-xs font-medium bg-green-500/20 text-green-400 text-center sm:text-left">Active</span>
                                        </div>
                                        <div
                                            class="flex flex-col sm:flex-row sm:items-center sm:space-x-6 text-sm text-slate-400 mb-4 space-y-2 sm:space-y-0">
                                            <div class="flex items-center space-x-1 justify-center sm:justify-start">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="lucide lucide-calendar w-4 h-4">
                                                    <path d="M8 2v4"></path>
                                                    <path d="M16 2v4"></path>
                                                    <rect width="18" height="18" x="3" y="4" rx="2">
                                                    </rect>
                                                    <path d="M3 10h18"></path>
                                                </svg><span>Created Apr 10, 2025</span>
                                            </div>
                                            <div class="flex items-center space-x-1 justify-center sm:justify-start">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="lucide lucide-clock w-4 h-4">
                                                    <circle cx="12" cy="12" r="10"></circle>
                                                    <polyline points="12 6 12 12 16 14"></polyline>
                                                </svg><span>Expires Apr 17, 2025</span>
                                            </div>
                                        </div>
                                        <div class="mb-4">
                                            <div class="flex items-center justify-between text-sm mb-2"><span
                                                    class="text-slate-400">Budget used: 14/20 credits</span><span
                                                    class="text-orange-500 font-medium">70%</span></div>
                                            <div class="w-full bg-slate-700 rounded-full h-2">
                                                <div class="bg-gradient-to-r from-orange-500 to-orange-600 h-2 rounded-full transition-all duration-300"
                                                    style="width: 70%;"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex flex-col lg:flex-row sm:items-center sm:space-x-2 gap-2">
                                    <button
                                        class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg font-medium transition-colors flex items-center justify-center space-x-2"><svg
                                            xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="lucide lucide-eye w-4 h-4">
                                            <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"></path>
                                            <circle cx="12" cy="12" r="3"></circle>
                                        </svg><span>View Details</span></button><button
                                        class="bg-slate-700 hover:bg-slate-600 text-white px-3 py-2 rounded-lg transition-colors"><svg
                                            xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="lucide lucide-more-horizontal w-4 h-4">
                                            <circle cx="12" cy="12" r="1"></circle>
                                            <circle cx="19" cy="12" r="1"></circle>
                                            <circle cx="5" cy="12" r="1"></circle>
                                        </svg></button><button
                                        class="bg-slate-700 hover:bg-slate-600 text-white px-4 py-2 rounded-lg font-medium transition-colors">Add
                                        Credits</button>
                                </div>
                            </div>
                            <div class="grid grid-cols-3 gap-6 mt-6 pt-6 border-t border-slate-700">
                                <div class="text-center">
                                    <div class="flex items-center justify-center mb-2"><svg
                                            xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            class="lucide  lucide-trending-up w-5 h-5 text-orange-500 mr-2">
                                            <polyline points="22 7 13.5 15.5 8.5 10.5 2 17"></polyline>
                                            <polyline points="16 7 22 7 22 13"></polyline>
                                        </svg><span
                                            class="text-2xl font-bold   text-black dark:text-gray-100">24</span>
                                    </div>
                                    <p class="text-slate-400 text-sm">Reposts</p>
                                </div>
                                <div class="text-center">
                                    <div class="flex items-center justify-center mb-2"><svg
                                            xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            class="lucide lucide-eye w-5 h-5 text-blue-500 mr-2">
                                            <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"></path>
                                            <circle cx="12" cy="12" r="3"></circle>
                                        </svg><span class="text-2xl font-bold text-black dark:text-gray-100">342</span>
                                    </div>
                                    <p class="text-slate-400 text-sm">Plays</p>
                                </div>
                                <div class="text-center">
                                    <div class="flex items-center justify-center mb-2"><svg
                                            xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            class="lucide lucide-trending-up w-5 h-5 text-green-500 mr-2">
                                            <polyline points="22 7 13.5 15.5 8.5 10.5 2 17"></polyline>
                                            <polyline points="16 7 22 7 22 13"></polyline>
                                        </svg><span class="text-2xl font-bold text-black dark:text-gray-100">38</span>
                                    </div>
                                    <p class="text-slate-400 text-sm">Likes</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class=" rounded-lg border border-slate-700 overflow-hidden">
                        <div class="p-6 sm:p-6">
                            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                                <div class="flex flex-col sm:flex-row sm:items-start gap-4"><img
                                        src="https://images.pexels.com/photos/1763075/pexels-photo-1763075.jpeg?auto=compress&amp;cs=tinysrgb&amp;w=300&amp;h=300&amp;fit=crop"
                                        alt="Drop - To - Me" class="w-20 h-20 rounded-lg mx-auto sm:mx-0">
                                    <div class="flex-1">
                                        <div class="flex flex-col sm:flex-row sm:items-center sm:space-x-3 mb-2">
                                            <h3
                                                class=" text-black dark:text-gray-100 font-semibold text-lg text-center sm:text-left">
                                                Drop - To
                                                -
                                                Me</h3><span
                                                class="px-2 py-1 rounded-full text-xs font-medium bg-green-500/20 text-green-400 text-center sm:text-left">Completed</span>
                                        </div>
                                        <div
                                            class="flex flex-col sm:flex-row sm:items-center sm:space-x-6 text-sm text-slate-400 mb-4 space-y-2 sm:space-y-0">
                                            <div class="flex items-center space-x-1 justify-center sm:justify-start">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="lucide lucide-calendar w-4 h-4">
                                                    <path d="M8 2v4"></path>
                                                    <path d="M16 2v4"></path>
                                                    <rect width="18" height="18" x="3" y="4" rx="2">
                                                    </rect>
                                                    <path d="M3 10h18"></path>
                                                </svg><span>Created Mar 15, 2025</span>
                                            </div>
                                            <div class="flex items-center space-x-1 justify-center sm:justify-start">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="lucide lucide-clock w-4 h-4">
                                                    <circle cx="12" cy="12" r="10"></circle>
                                                    <polyline points="12 6 12 12 16 14"></polyline>
                                                </svg><span>Expires Mar 22, 2025</span>
                                            </div>
                                        </div>
                                        <div class="mb-4">
                                            <div class="flex items-center justify-between text-sm mb-2"><span
                                                    class="text-slate-400">Budget used: 15/15 credits</span><span
                                                    class="text-orange-500 font-medium">100%</span></div>
                                            <div class="w-full bg-slate-700 rounded-full h-2">
                                                <div class="bg-gradient-to-r from-orange-500 to-orange-600 h-2 rounded-full transition-all duration-300"
                                                    style="width: 100%;"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex flex-col lg:flex-row sm:items-center sm:space-x-2 gap-2"><button
                                        class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg font-medium transition-colors flex items-center justify-center space-x-2"><svg
                                            xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            class="lucide lucide-eye w-4 h-4">
                                            <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"></path>
                                            <circle cx="12" cy="12" r="3"></circle>
                                        </svg><span>View Details</span></button><button
                                        class="bg-slate-700 hover:bg-slate-600 text-white px-3 py-2 rounded-lg transition-colors"><svg
                                            xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            class="lucide lucide-more-horizontal w-4 h-4">
                                            <circle cx="12" cy="12" r="1"></circle>
                                            <circle cx="19" cy="12" r="1"></circle>
                                            <circle cx="5" cy="12" r="1"></circle>
                                        </svg></button></div>
                            </div>
                            <div class="grid grid-cols-3 gap-6 mt-6 pt-6 border-t border-slate-700">
                                <div class="text-center">
                                    <div class="flex items-center justify-center mb-2"><svg
                                            xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            class="lucide lucide-trending-up w-5 h-5 text-orange-500 mr-2">
                                            <polyline points="22 7 13.5 15.5 8.5 10.5 2 17"></polyline>
                                            <polyline points="16 7 22 7 22 13"></polyline>
                                        </svg><span class="text-2xl font-bold text-black dark:text-gray-100">18</span>
                                    </div>
                                    <p class="text-slate-400 text-sm">Reposts</p>
                                </div>
                                <div class="text-center">
                                    <div class="flex items-center justify-center mb-2"><svg
                                            xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            class="lucide lucide-eye w-5 h-5 text-blue-500 mr-2">
                                            <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"></path>
                                            <circle cx="12" cy="12" r="3"></circle>
                                        </svg><span class="text-2xl font-bold text-black dark:text-gray-100">215</span>
                                    </div>
                                    <p class="text-slate-400 text-sm">Plays</p>
                                </div>
                                <div class="text-center">
                                    <div class="flex items-center justify-center mb-2"><svg
                                            xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            class="lucide lucide-trending-up w-5 h-5 text-green-500 mr-2">
                                            <polyline points="22 7 13.5 15.5 8.5 10.5 2 17"></polyline>
                                            <polyline points="16 7 22 7 22 13"></polyline>
                                        </svg><span class="text-2xl font-bold text-black dark:text-gray-100">27</span>
                                    </div>
                                    <p class="text-slate-400 text-sm">Likes</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Active Campaigns Section -->
            <div id="section-active" class="hidden">
                <!-- Only Active Campaign Block -->
                <!-- Completed Campaign -->
                <div class="space-y-6">
                    <div class=" rounded-lg border border-slate-700 overflow-hidden">
                        <div class="p-4 sm:p-6">
                            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                                <div class="flex flex-col sm:flex-row sm:items-start gap-4">
                                    <img src="https://images.pexels.com/photos/1540338/pexels-photo-1540338.jpeg?auto=compress&amp;cs=tinysrgb&amp;w=300&amp;h=300&amp;fit=crop"
                                        alt="Sexy - Fashion - Promo" class="w-20 h-20 rounded-lg mx-auto sm:mx-0">
                                    <div class="flex-1">
                                        <div class="flex flex-col sm:flex-row sm:items-center sm:space-x-3 mb-2">
                                            <h3
                                                class="text-black dark:text-gray-100 font-semibold text-lg text-center sm:text-left">
                                                Sexy -
                                                Fashion - Promo</h3>
                                            <span
                                                class="px-2 py-1 rounded-full text-xs font-medium bg-green-500/20 text-green-400 text-center sm:text-left">Active</span>
                                        </div>
                                        <div
                                            class="flex flex-col sm:flex-row sm:items-center sm:space-x-6 text-sm text-slate-400 mb-4 space-y-2 sm:space-y-0">
                                            <div class="flex items-center space-x-1 justify-center sm:justify-start">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="lucide lucide-calendar w-4 h-4">
                                                    <path d="M8 2v4"></path>
                                                    <path d="M16 2v4"></path>
                                                    <rect width="18" height="18" x="3" y="4" rx="2">
                                                    </rect>
                                                    <path d="M3 10h18"></path>
                                                </svg><span>Created Apr 10, 2025</span>
                                            </div>
                                            <div class="flex items-center space-x-1 justify-center sm:justify-start">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="lucide lucide-clock w-4 h-4">
                                                    <circle cx="12" cy="12" r="10"></circle>
                                                    <polyline points="12 6 12 12 16 14"></polyline>
                                                </svg><span>Expires Apr 17, 2025</span>
                                            </div>
                                        </div>
                                        <div class="mb-4">
                                            <div class="flex items-center justify-between text-sm mb-2"><span
                                                    class="text-slate-400">Budget used: 14/20 credits</span><span
                                                    class="text-orange-500 font-medium">70%</span></div>
                                            <div class="w-full bg-slate-700 rounded-full h-2">
                                                <div class="bg-gradient-to-r from-orange-500 to-orange-600 h-2 rounded-full transition-all duration-300"
                                                    style="width: 70%;"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex flex-col lg:flex-row sm:items-center sm:space-x-2 gap-2"><button
                                        class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg font-medium transition-colors flex items-center justify-center space-x-2"><svg
                                            xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            class="lucide lucide-eye w-4 h-4">
                                            <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"></path>
                                            <circle cx="12" cy="12" r="3"></circle>
                                        </svg><span>View Details</span></button><button
                                        class="bg-slate-700 hover:bg-slate-600 text-white px-3 py-2 rounded-lg transition-colors flex justify-center"><svg
                                            xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            class="lucide lucide-more-horizontal w-4 h-4">
                                            <circle cx="12" cy="12" r="1"></circle>
                                            <circle cx="19" cy="12" r="1"></circle>
                                            <circle cx="5" cy="12" r="1"></circle>
                                        </svg></button><button
                                        class="bg-slate-700 hover:bg-slate-600 text-white px-4 py-2 rounded-lg font-medium transition-colors">Add
                                        Credits</button></div>
                            </div>
                            <div class="grid grid-cols-3 gap-6 mt-6 pt-6 border-t border-slate-700">
                                <div class="text-center">
                                    <div class="flex items-center justify-center mb-2"><svg
                                            xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            class="lucide lucide-trending-up w-5 h-5 text-orange-500 mr-2">
                                            <polyline points="22 7 13.5 15.5 8.5 10.5 2 17"></polyline>
                                            <polyline points="16 7 22 7 22 13"></polyline>
                                        </svg><span class="text-2xl font-bold text-black dark:text-gray-100">24</span>
                                    </div>
                                    <p class="text-slate-400 text-sm">Reposts</p>
                                </div>
                                <div class="text-center">
                                    <div class="flex items-center justify-center mb-2"><svg
                                            xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            class="lucide lucide-eye w-5 h-5 text-blue-500 mr-2">
                                            <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"></path>
                                            <circle cx="12" cy="12" r="3"></circle>
                                        </svg><span class="text-2xl font-bold text-black dark:text-gray-100">342</span>
                                    </div>
                                    <p class="text-slate-400 text-sm">Plays</p>
                                </div>
                                <div class="text-center">
                                    <div class="flex items-center justify-center mb-2"><svg
                                            xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            class="lucide lucide-trending-up w-5 h-5 text-green-500 mr-2">
                                            <polyline points="22 7 13.5 15.5 8.5 10.5 2 17"></polyline>
                                            <polyline points="16 7 22 7 22 13"></polyline>
                                        </svg><span class="text-2xl font-bold text-black dark:text-gray-100">38</span>
                                    </div>
                                    <p class="text-slate-400 text-sm">Likes</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Completed Campaigns Section -->
            <div id="section-completed" class="hidden">
                <!-- Only Completed Campaign Block -->
                <div class="space-y-6">
                    <div class=" rounded-lg border border-slate-700 overflow-hidden">
                        <div class="p-6 sm:p-8">
                            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                                <div class="flex flex-col sm:flex-row sm:items-start gap-4"><img
                                        src="https://images.pexels.com/photos/1763075/pexels-photo-1763075.jpeg?auto=compress&amp;cs=tinysrgb&amp;w=300&amp;h=300&amp;fit=crop"
                                        alt="Drop - To - Me" class="w-20 h-20 rounded-lg mx-auto sm:mx-0">
                                    <div class="flex-1">
                                        <div class="flex flex-col sm:flex-row sm:items-center sm:space-x-3 mb-2">
                                            <h3
                                                class="text-black dark:text-gray-100 font-semibold text-lg text-center sm:text-left">
                                                Drop - To -
                                                Me</h3><span
                                                class="px-2 py-1 rounded-full text-xs font-medium bg-green-500/20 text-green-400 text-center sm:text-left">Completed</span>
                                        </div>
                                        <div
                                            class="flex flex-col sm:flex-row sm:items-center sm:space-x-6 text-sm text-slate-400 mb-4 space-y-2 sm:space-y-0">
                                            <div class="flex items-center space-x-1 justify-center sm:justify-start">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="lucide lucide-calendar w-4 h-4">
                                                    <path d="M8 2v4"></path>
                                                    <path d="M16 2v4"></path>
                                                    <rect width="18" height="18" x="3" y="4" rx="2">
                                                    </rect>
                                                    <path d="M3 10h18"></path>
                                                </svg><span>Created Mar 15, 2025</span>
                                            </div>
                                            <div class="flex items-center space-x-1 justify-center sm:justify-start">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="lucide lucide-clock w-4 h-4">
                                                    <circle cx="12" cy="12" r="10"></circle>
                                                    <polyline points="12 6 12 12 16 14"></polyline>
                                                </svg><span>Expires Mar 22, 2025</span>
                                            </div>
                                        </div>
                                        <div class="mb-4">
                                            <div class="flex items-center justify-between text-sm mb-2"><span
                                                    class="text-slate-400">Budget used: 15/15 credits</span><span
                                                    class="text-orange-500 font-medium">100%</span></div>
                                            <div class="w-full bg-slate-700 rounded-full h-2">
                                                <div class="bg-gradient-to-r from-orange-500 to-orange-600 h-2 rounded-full transition-all duration-300"
                                                    style="width: 100%;"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex flex-col lg:flex-row sm:items-center sm:space-x-2 gap-2"><button
                                        class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg font-medium transition-colors flex items-center justify-center space-x-2"><svg
                                            xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            class="lucide lucide-eye w-4 h-4">
                                            <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"></path>
                                            <circle cx="12" cy="12" r="3"></circle>
                                        </svg><span>View Details</span></button><button
                                        class="bg-slate-700 hover:bg-slate-600 text-white px-3 py-2 rounded-lg transition-colors flex justify-center"><svg
                                            xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            class="lucide lucide-more-horizontal w-4 h-4">
                                            <circle cx="12" cy="12" r="1"></circle>
                                            <circle cx="19" cy="12" r="1"></circle>
                                            <circle cx="5" cy="12" r="1"></circle>
                                        </svg></button></div>
                            </div>
                            <div class="grid grid-cols-3 gap-6 mt-6 pt-6 border-t border-slate-700">
                                <div class="text-center">
                                    <div class="flex items-center justify-center mb-2"><svg
                                            xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            class="lucide lucide-trending-up w-5 h-5 text-orange-500 mr-2">
                                            <polyline points="22 7 13.5 15.5 8.5 10.5 2 17"></polyline>
                                            <polyline points="16 7 22 7 22 13"></polyline>
                                        </svg><span class="text-2xl font-bold text-black dark:text-gray-100">18</span>
                                    </div>
                                    <p class="text-slate-400 text-sm text-black black:text-white">Reposts</p>
                                </div>
                                <div class="text-center">
                                    <div class="flex items-center justify-center mb-2"><svg
                                            xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            class="lucide lucide-eye w-5 h-5 text-blue-500 mr-2">
                                            <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"></path>
                                            <circle cx="12" cy="12" r="3"></circle>
                                        </svg><span class="text-2xl font-bold text-black dark:text-gray-100">215</span>
                                    </div>
                                    <p class="text-slate-400 text-sm">Plays</p>
                                </div>
                                <div class="text-center">
                                    <div class="flex items-center justify-center mb-2"><svg
                                            xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            class="lucide lucide-trending-up w-5 h-5 text-green-500 mr-2">
                                            <polyline points="22 7 13.5 15.5 8.5 10.5 2 17"></polyline>
                                            <polyline points="16 7 22 7 22 13"></polyline>
                                        </svg><span class="text-2xl font-bold text-black dark:text-gray-100">27</span>
                                    </div>
                                    <p class="text-slate-400 text-sm">Likes</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </main>
    {{-- Audio player --}}
    {{-- @include('backend.user.campaign_management.includes.audio_player') --}}

    <script>
        function selectTrack(el) {
            // Get data from clicked track
            const id = el.dataset.id;
            const title = el.dataset.title;
            const author = el.dataset.author;
            const genre = el.dataset.genre;
            const isrc = el.dataset.isrc;
            const image = el.dataset.image;

            // Populate the campaign_create_modal
            // document.getElementById('selected_track_id').href =
            //     `/Campaign-management/campaigns/create?track_id=${encodeURIComponent(id)}`;

            const link = document.getElementById('selected_track_id');
            if (link) {
                const template = link.dataset.urlTemplate;
                link.href = template.replace('__ID__', id);
            }

            document.getElementById('selected_track_image').src = image;
            document.getElementById('selected_track_title').textContent = title;
            document.getElementById('selected_track_author').textContent = `by ${author} • ${genre}`;

            // Open the new modal and close the old one
            document.getElementById('campaign_create_modal').showModal();
            document.getElementById('close_campaigns_modal').click();
        }

        // Tab functionality
        function switchTab(tab) {
            const tracksTab = document.getElementById('tracks-tab');
            const playlistsTab = document.getElementById('playlists-tab');
            const tracksContent = document.getElementById('tracks-content');
            const playlistsContent = document.getElementById('playlists-content');

            if (tab === 'tracks') {
                tracksTab.classList.add('border-red-500', 'text-red-600');
                tracksTab.classList.remove('border-transparent', 'text-gray-500');
                playlistsTab.classList.add('border-transparent', 'text-gray-500');
                playlistsTab.classList.remove('border-red-500', 'text-red-600');
                tracksContent.classList.remove('hidden');
                playlistsContent.classList.add('hidden');
            } else {
                playlistsTab.classList.add('border-red-500', 'text-red-600');
                playlistsTab.classList.remove('border-transparent', 'text-gray-500');
                tracksTab.classList.add('border-transparent', 'text-gray-500');
                tracksTab.classList.remove('border-red-500', 'text-red-600');
                playlistsContent.classList.remove('hidden');
                tracksContent.classList.add('hidden');
            }
        }
        // Tab functionality
        document.addEventListener('DOMContentLoaded', function() {
            const tabButtons = document.querySelectorAll('.tab-button');
            const campaignCards = document.querySelectorAll('.campaign-card');

            tabButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const targetTab = this.getAttribute('data-tab');

                    // Update active tab
                    tabButtons.forEach(btn => {
                        btn.classList.remove('active', 'border-orange-500',
                            'text-orange-600');
                        btn.classList.add('border-transparent', 'text-gray-500');
                    });

                    this.classList.add('active', 'border-orange-500', 'text-orange-600');
                    this.classList.remove('border-transparent', 'text-gray-500');

                    // Filter campaigns
                    campaignCards.forEach(card => {
                        const cardStatus = card.getAttribute('data-status');

                        if (targetTab === 'all') {
                            card.style.display = 'block';
                        } else if (targetTab === cardStatus) {
                            card.style.display = 'block';
                        } else {
                            card.style.display = 'none';
                        }
                    });
                });
            });

            // Add hover effects to buttons
            //  select data-button data-attribute
            const buttons = document.querySelectorAll('.tab-button');
            buttons.forEach(button => {
                button.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-1px)';
                });

                button.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                });
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const $audio = $('#audioPlayer');
            const $playPauseBtn = $('.playPauseBtn');
            const $playIcon = $('.playIcon');
            const $pauseIcon = $('.pauseIcon');
            const $progressBar = $('#progressBar');
            const $progressContainer = $('#progressContainer');
            const $progressHandle = $('#progressHandle');
            const $currentTimeEl = $('#currentTime');
            const $durationEl = $('#duration');
            const $prevBtn = $('#prevBtn');
            const $nextBtn = $('#nextBtn');

            // Helper: Format time
            function formatTime(seconds) {
                const mins = Math.floor(seconds / 60);
                const secs = Math.floor(seconds % 60);
                return `${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
            }

            // Play/Pause
            $playPauseBtn.on('click', function() {
                const audio = $audio[0];
                if (audio.paused) {
                    audio.play();
                } else {
                    audio.pause();
                }
            });

            // Update play/pause icon
            $audio.on('play', function() {
                $playIcon.addClass('hidden');
                $pauseIcon.removeClass('hidden');
            });

            $audio.on('pause', function() {
                $playIcon.removeClass('hidden');
                $pauseIcon.addClass('hidden');
            });

            // Update progress bar
            $audio.on('timeupdate', function() {
                const audio = this;
                if (audio.duration) {
                    const progress = (audio.currentTime / audio.duration) * 100;
                    $progressBar.css('width', progress + '%');
                    $currentTimeEl.text(formatTime(audio.currentTime));
                }
            });

            // Show duration when metadata loads
            $audio.on('loadedmetadata', function() {
                $durationEl.text(formatTime(this.duration));
            });

            // Seek functionality
            $progressContainer.on('click', function(e) {
                const audio = $audio[0];
                const rect = this.getBoundingClientRect();
                const clickX = e.clientX - rect.left;
                const width = rect.width;
                const clickRatio = clickX / width;

                if (audio.duration) {
                    audio.currentTime = clickRatio * audio.duration;
                }
            });

            // Show/Hide progress handle on hover
            $progressContainer.on('mouseenter', function() {
                $progressHandle.removeClass('opacity-0');
            });

            $progressContainer.on('mouseleave', function() {
                $progressHandle.addClass('opacity-0');
            });

            // Previous Button
            $prevBtn.on('click', function() {
                $audio[0].currentTime = 0;
            });

            // Next Button (placeholder)
            $nextBtn.on('click', function() {
                const audio = $audio[0];
                audio.currentTime = audio.duration || 0;
            });

            // Audio ended
            $audio.on('ended', function() {
                $playIcon.removeClass('hidden');
                $pauseIcon.addClass('hidden');
                $progressBar.css('width', '0%');
                this.currentTime = 0;
            });
        });
    </script>

    <script>
        function switchTab(tab) {
            const allTabs = ["all", "active", "completed"];

            allTabs.forEach(t => {
                const btn = document.getElementById(`tab-${t}`);
                const section = document.getElementById(`section-${t}`);

                if (t === tab) {
                    btn.classList.add("text-orange-500", "border-b-2", "border-orange-500");
                    btn.classList.remove("text-gray-400");
                    section.classList.remove("hidden");
                } else {
                    btn.classList.remove("text-orange-500", "border-b-2", "border-orange-500");
                    btn.classList.add("text-gray-400");
                    section.classList.add("hidden");
                }
            });
        }
    </script>

</x-user::layout>
