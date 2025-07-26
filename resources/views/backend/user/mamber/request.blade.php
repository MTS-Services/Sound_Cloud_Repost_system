<x-user::layout>
    <x-slot name="page_slug">request</x-slot>
    
   <div class="max-w-3xl mx-auto bg-navy min-h-screen px-4 sm:px-6 lg:px-8 dark:bg-gray-700">
        <!-- Latest Repost Requests Section -->
        <div class="p-4 sm:p-6">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h1 class="text-lg sm:text-xl font-semibold text-black dark:text-white mb-1">Latest Repost Requests</h1>
                    <p class="text-black dark:text-white text-xs sm:text-sm">Earn credits by reposting tracks</p>
                </div>
                <a href="#" class="text-orange-500 text-xs sm:text-sm font-medium focus:border-orange-500 transition-colors">View all →</a>
            </div>

            <!-- Repost Request Card -->
            <div class="bg-navy-dark rounded-lg p-4 mb-6 sm:mb-8 border border-slate-700">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-15 h-15 sm:w-10 sm:h-10 bg-gray-600 rounded-full flex items-center justify-center overflow-hidden flex-shrink-0">
                            <img src="{{ asset('frontend/user/image/pexels-photo-1040881.jpeg') }}" alt="Profile" class="w-full h-full object-cover">
                        </div>
                        <div class="min-w-0">
                            <h3 class="text-black dark:text-white font-medium text-sm sm:text-base truncate">The Beginning</h3>
                            <p class="text-gray-400 dark:text-white text-xs sm:text-sm">by carvalor</p>
                        </div>
                    </div>
                    <span class="text-orange-500 font-medium text-sm sm:text-base whitespace-nowrap ml-2">+7 credits</span>
                </div>

                <div class="flex space-x-2 sm:space-x-3">
                    <button class="flex-1 bg-gray-600 hover:bg-gray-400 text-white dark:text-white py-2 px-3 sm:px-4 rounded-lg font-medium text-sm sm:text-base transition-colors duration-200">
                        Decline
                    </button>
                    <button class="flex-1 bg-orange hover:bg-orange-600 bg-orange-500 text-white dark:text-white py-2 px-3 sm:px-4 rounded-lg font-medium text-sm sm:text-base transition-colors duration-200">
                        Repost
                    </button>
                </div>
            </div>

            <!-- Trending Section -->
            <div>
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center space-x-2">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-orange-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z" clip-rule="evenodd"></path>
                        </svg>
                        <h2 class="text-base sm:text-lg font-semibold text-black dark:text-white">Trending</h2>
                    </div>
                    <a href="#" class="text-orange text-xs sm:text-sm font-medium text-orange-500 hover:text-orange-400 transition-colors">View charts</a>
                </div>

                <!-- Trending List -->
                <div class="space-y-2 sm:space-y-3">
                    <div class="flex items-center justify-between py-2 hover:bg-slate-800 rounded-lg px-2 transition-colors duration-200">
                        <div class="flex items-center space-x-3 min-w-0 flex-1">
                            <span class="text-orange-500 font-bold text-base sm:text-lg flex-shrink-0">#1</span>
                            <div class="min-w-0 flex-1">
                                <h4 class="text-black dark:text-white font-medium text-sm sm:text-base truncate">Why Do I?</h4>
                            </div>
                        </div>
                        <span class="focus:border-orange-500 dark:text-white text-xs sm:text-sm ml-2 flex-shrink-0">EMMAAG</span>
                    </div>

                    <div class="flex items-center justify-between py-2 hover:bg-slate-800 rounded-lg px-2 transition-colors duration-200">
                        <div class="flex items-center space-x-3 min-w-0 flex-1">
                            <span class="dark:text-gray-500 font-bold text-base sm:text-lg flex-shrink-0">#2</span>
                            <div class="min-w-0 flex-1">
                                <h4 class="text-black dark:text-white font-medium text-sm sm:text-base truncate">The Strength Of Love</h4>
                            </div>
                        </div>
                        <span class="text-gray-400 dark:text-white text-xs sm:text-sm ml-2 flex-shrink-0">Constellation Lyra</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-user::layout>