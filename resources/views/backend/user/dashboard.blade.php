<x-user::layout>
    <x-slot name="page_slug">dashboard</x-slot>
    <!-- Dashboard Content (Default) -->
    <div id="content-dashboard" class="page-content py-2 px-2 ">
        <div class="px-2  flex justify-between items-center">
            <div class="">
                <h2 class="text-2xl text-black dark:text-white font-semibold  mb-2">Dashboard</h2>
                <p class="dark:text-slate-300 text-gray-600">Welcome back!  <span class="font-semibold">{{ auth()->user()->name ?? '' }}</span> . Here's an overview of your RepostChain activity.</p>
            </div>
            <div class="flex gap-2">
                <div
                    class="flex items-center gap-2 mt-4  bg-slate-700 hover:bg-slate-600  dark:bg-slate-800 dark:hover:bg-slate-700  text-white dark:text-gray-200 px-2 py-2 rounded-lg font-medium transition-colors cursor-pointer">
                    <span>💰
                        <a href="#" class="hover:underline text-white dark:text-gray-200">
                            {{ __('Earn Credits') }}
                        </a>
                    </span>
                </div>

                <div
                    class="flex items-center gap-2 mt-4 bg-orange-600 text-white py-2 px-2 rounded-md hover:bg-orange-700">
                    <span class=""><i data-lucide="music"
                            class="inline-block   text-center  h-5 w-6  text-purple-800"></i></span>
                    <a href="#">{{ __('Submit Track') }}</a>
                </div>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8 py-6">
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
                    <p class="text-2xl  font-bold text-slate-700 dark:text-white">117</p>
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
</x-user::layout>
