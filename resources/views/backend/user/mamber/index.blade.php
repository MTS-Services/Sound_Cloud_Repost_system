<x-user::layout>

    <x-slot name="page_slug">mamber</x-slot>

 <div class="container mx-auto px-4 py-8 max-w-8xl">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-2xl md:text-3xl font-bold mb-2 dark:text-white">Browse Members</h1>
            <p class="text-text-gray text-sm md:text-base dark:text-white">Search, filter or browse the list of recommended members that can repost your music.</p>
        </div>

        <!-- Search and Filters -->
        <div class="mb-8 flex flex-col lg:flex-row gap-4">
            <!-- Search Input -->
            <div class="flex-1 relative">
                <svg class="absolute left-4 top-1/2 transform -translate-y-1/2 w-5 h-5 text-text-gray" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <input type="text" placeholder="Name or sub-genre" class="w-full bg-card-blue border border-gray-600 rounded-lg pl-10 pr-4 py-3 text-white dark:text-white dark:bg-gray-900 placeholder-text-gray focus:outline-none focus:border-orange-500">
            </div>

            <!-- Filter Buttons -->
            <div class="flex flex-col sm:flex-row gap-4">
                <button class="bg-card-blue border border-gray-600 rounded-lg px-4 py-3 text-text-gray  dark:text-white  hover:border-orange-500 transition-colors flex items-center gap-2 min-w-[160px] justify-between">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                    </svg>
                    Filter by track
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>

                <select class="bg-card-blue border border-gray-600 dark:bg-gray-900  dark:text-white rounded-lg px-4 py-3 text-text-gray hover:border-orange-500 transition-colors min-w-[160px] focus:outline-none focus:border-orange-500">
                    <option class="dark:text-white">Filter by genre</option>
                    <option class="dark:text-white">Electronic</option>
                    <option class="dark:text-white">Hip-Hop</option>
                    <option class="dark:text-white">Pop</option>
                    <option class="dark:text-white">Rock</option>
                </select>

                <select class="bg-card-blue border border-gray-600 dark:text-white dark:bg-gray-900 rounded-lg px-4 py-3 text-text-gray hover:border-orange-500 transition-colors min-w-[160px] focus:outline-none focus:border-orange-500">
                    <option class="dark:text-white">Filter by cost</option>
                    <option class="dark:text-white">Low to High</option>
                    <option  class="dark:text-white">High to Low</option>
                </select>
            </div>
        </div>

        <!-- Member Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 xl:grid-cols-3 gap-6">
            <!-- Card 1 -->
            <div class="bg-card-blue rounded-lg p-6 border border-gray-600">
                <!-- Profile Header -->
                <div class="flex items-center gap-3 mb-6">
                    <div class="relative">
                        <img src="/placeholder.svg?height=50&width=50" alt="mivf" class="w-12 h-12 rounded-full">
                        <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-500 rounded-full border-2 border-card-blue"></div>
                    </div>
                    <div>
                        <h3 class="font-semibold text-lg dark:text-white">mivf</h3>
                        <p class="text-text-gray text-sm dark:text-white">Member since Nov 2020</p>
                    </div>
                    <div class="ml-auto flex items-center gap-1 text-orange-500">
                        <svg class="w-5 h-5 mr-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 01.82-.38z" clip-rule="evenodd"></path>
                </svg>
                        <span class="text-sm font-medium dark:text-white">1,112</span>
                    </div>
                </div>

                <!-- Genre Tags -->
                <div class="flex flex-wrap gap-2 mb-4">
                    <span class="bg-gray-600 text-white  text-xs px-2 py-1 rounded">Ambient</span>
                    <span class="bg-gray-600 text-white text-xs px-2 py-1 rounded">Dubstep</span>
                    <span class="bg-gray-600 text-white text-xs px-2 py-1 rounded">Electronic</span>
                    <span class="bg-gray-600 text-white text-xs px-2 py-1 rounded">Techno</span>
                </div>

                <!-- Repost Price -->
                <div class="flex flex-wrap gap-2 mb-4 ">
                    <p class="text-text-gray text-sm mb-1 dark:text-white">Repost price:</p>
              
                    <p class="  dark:text-white">12 Credits</p>
                </div>

                <!-- Stats -->
                <div class="grid grid-cols-3 gap-4 mb-6">
                    <div class="text-center">
                        <p class="text-text-gray text-xs mb-1 dark:text-white">Credibility</p>
                        <p class="text-green-400 font-bold ">83%</p>
                    </div>
                    <div class="text-center">
                        <p class="text-text-gray text-xs mb-1 dark:text-white">Efficiency</p>
                        <p class="text-orange-500 font-bold ">92%</p>
                    </div>
                    <div class="text-center">
                        <p class="text-text-gray text-xs mb-1 dark:text-white">Total Reposts</p>
                        <p class="text-black font-bold dark:text-white">156</p>
                    </div>
                </div>

                <!-- Request Button -->
                <button class="w-full bg-orange-500 hover:bg-orange-600 text-white font-semibold py-3 rounded-lg transition-colors dark:text-white">
                    Request
                </button>
            </div>

            <!-- Card 2 -->
            <div class="bg-card-blue rounded-lg p-6 border border-gray-600">
                <!-- Profile Header -->
                <div class="flex items-center gap-3 mb-6">
                    <div class="relative">
                        <img src="/placeholder.svg?height=50&width=50" alt="INDYLegends" class="w-12 h-12 rounded-full">
                        <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-500 rounded-full border-2 border-card-blue"></div>
                    </div>
                    <div>
                        <h3 class="font-semibold text-lg dark:text-white">INDYLegends</h3>
                        <p class="text-text-gray text-sm dark:text-white">Member since Nov 2020</p>
                    </div>
                    <div class="ml-auto flex items-center gap-1 text-orange-500">
                      <svg class="w-5 h-5 mr-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 01.82-.38z" clip-rule="evenodd"></path>
                </svg>
                        <span class="text-sm font-medium dark:text-white">2,388</span>
                    </div>
                </div>

                <!-- Genre Tags -->
                <div class="flex flex-wrap gap-2 mb-4">
                    <span class="bg-gray-600 text-white text-xs px-2 py-1 rounded">Hip-Hop & Rap</span>
                    <span class="bg-gray-600 text-white text-xs px-2 py-1 rounded">Indie</span>
                    <span class="bg-gray-600 text-white text-xs px-2 py-1 rounded">Pop</span>
                    <span class="bg-gray-600 text-white text-xs px-2 py-1 rounded">R&B & Soul</span>
                </div>

                <!-- Repost Price -->
                <div class="mb-4">
                    <p class="text-text-gray text-sm mb-1 dark:text-white">Repost price:</p>
                    <p class="text-xl font-bold dark:text-white">24 Credits</p>
                </div>

                <!-- Stats -->
                <div class="grid grid-cols-3 gap-4 mb-6">
                    <div class="text-center">
                        <p class="text-text-gray text-xs mb-1 dark:text-white">Credibility</p>
                        <p class="text-green-400 font-bold ">76%</p>
                    </div>
                    <div class="text-center">
                        <p class="text-text-gray text-xs mb-1 dark:text-white">Efficiency</p>
                        <p class="text-orange-500 font-bold ">72%</p>
                    </div>
                    <div class="text-center">
                        <p class="text-text-gray text-xs mb-1 dark:text-white">Total Reposts</p>
                        <p class="text-black font-bold dark:text-white">183</p>
                    </div>
                </div>

                <!-- Request Button -->
                <button class="w-full bg-orange-500 hover:bg-orange-600 text-white font-semibold py-3 rounded-lg transition-colors dark:text-white">
                    Request
                </button>
            </div>

            <!-- Card 3 -->
            <div class="bg-card-blue rounded-lg p-6 border border-gray-600">
                <!-- Profile Header -->
                <div class="flex items-center gap-3 mb-6">
                    <div class="relative">
                        <img src="/placeholder.svg?height=50&width=50" alt="slicktheyoungmc" class="w-12 h-12 rounded-full">
                        <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-500 rounded-full border-2 border-card-blue"></div>
                    </div>
                    <div>
                        <h3 class="font-semibold text-lg dark:text-white">slicktheyoungmc</h3>
                        <p class="text-text-gray text-sm dark:text-white">Member since Nov 2020</p>
                    </div>
                    <div class="ml-auto flex items-center gap-1 text-orange-500">
                        <svg class="w-5 h-5 mr-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 01.82-.38z" clip-rule="evenodd"></path>
                </svg>
                        <span class="text-sm font-medium dark:text-white">2,600</span>
                    </div>
                </div>

                <!-- Genre Tags -->
                <div class="flex flex-wrap gap-2 mb-4">
                    <span class="bg-gray-600 text-white text-xs px-2 py-1 rounded">Hip-Hop</span>
                    <span class="bg-gray-600 text-white text-xs px-2 py-1 rounded">Alternative Rock</span>
                    <span class="bg-gray-600 text-white text-xs px-2 py-1 rounded">Indie</span>
                </div>

                <!-- Repost Price -->
                <div class="mb-4">
                    <p class="text-text-gray text-sm mb-1 dark:text-white">Repost price:</p>
                    <p class="text-xl font-bold dark:text-white">26 Cgrayits</p>
                </div>

                <!-- Stats -->
                <div class="grid grid-cols-3 gap-4 mb-6">
                    <div class="text-center">
                        <p class="text-text-gray text-xs mb-1 dark:text-white">Credibility</p>
                        <p class="text-green-400 font-bold">91%</p>
                    </div>
                    <div class="text-center">
                        <p class="text-text-gray text-xs mb-1 dark:text-white">Efficiency</p>
                        <p class="text-orange-500 font-bold">84%</p>
                    </div>
                    <div class="text-center">
                        <p class="text-text-gray text-xs mb-1 dark:text-white">Total Reposts</p>
                        <p class="text-black font-bold dark:text-white">215</p>
                    </div>
                </div>

                <!-- Request Button -->
                <button class="w-full bg-orange-500 hover:bg-orange-600 text-white font-semibold py-3 rounded-lg transition-colors">
                    Request
                </button>
            </div>
        </div>
    </div>

</x-user::layout>