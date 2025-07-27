<section>
    <x-slot name="page_slug">campaign-feed</x-slot>

    <!-- Header Section -->
    <div class=" w-full  mt-6">
        <!-- Header Tabs & Button -->
        <div class="flex items-center justify-between px-4 pt-3 border-b border-b-gray-200">
            {{-- <div class="flex items-center space-x-6">
                <div class="relative text-black border-b-2 font-medium border-orange-500 ">Recommended Pro <sup
                        class="text-red-500 text-xs">55</sup></div>
                <div class=" text-gray-500 pb-1">Recommended <sup class="text-red-500 text-xs">136</sup></div>
                <div class="text-gray-500">All <sup class="text-red-500 text-xs">855</sup></div>
            </div> --}}
            <div x-data="{ showInput: false }" class="flex items-center space-x-2 text-gray-600">
                <div @click="showInput = !showInput"
                    :class="!showInput ? 'flex items-center space-x-2 cursor-pointer' : 'hidden'">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21 21l-4.35-4.35M17 10a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <span>Search by tag</span>
                </div>

                <div x-show="showInput" class="relative ">
                    <input type="text" placeholder="Search by tag "
                        class="border py-2 border-red-500 pl-7 pr-2  rounded focus:outline-none focus:ring-1 focus:ring-red-400"
                        @click.outside="showInput = false" autofocus />
                    <svg class="w-4 h-4 absolute left-2 top-3 text-gray-500" fill="none" stroke="currentColor"
                        stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21 21l-4.35-4.35M17 10a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </div>
            <button class="bg-orange-600 text-white px-5 py-2 mb-2 rounded hover:bg-orange-700 transition">
                Start a new campaign
            </button>
        </div>

        <!-- Filter & Search Bar -->
        {{-- <div class="flex flex-wrap items-center px-4 py-3 gap-3">
            <div
                class="bg-red-100 text-orange-600 px-3 py-2 rounded flex items-center space-x-1 cursor-pointer hover:bg-red-100">
                <span>Filter by track type /all</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                </svg>
            </div>

            <div
                class="bg-red-100 text-orange-600 px-3 py-2 rounded flex items-center space-x-1 cursor-pointer hover:bg-red-100">
                <svg width="24" height="24" viewBox="0 0 64 64" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M16 48C16 50.2091 17.7909 52 20 52C22.2091 52 24 50.2091 24 48V24H40V40C40 42.2091 41.7909 44 44 44C46.2091 44 48 42.2091 48 40V16C48 14.8954 47.1046 14 46 14H24C22.8954 14 22 14.8954 22 16V40C20.938 39.359 19.5646 39 18 39C13.5817 39 10 42.5817 10 47C10 51.4183 13.5817 55 18 55C22.4183 55 26 51.4183 26 47V24H44V40C44 41.1046 43.1046 42 42 42C40.8954 42 40 41.1046 40 40V22H24V48C24 49.1046 23.1046 50 22 50C20.8954 50 20 49.1046 20 48H16Z"
                        fill="#F44336" />
                </svg>

                <span>Filter by genre /5</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                </svg>
            </div>

            <div x-data="{ showInput: false }" class="flex items-center space-x-2 text-gray-600 cursor-pointer"
                @click="showInput = !showInput">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M21 21l-4.35-4.35M17 10a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <span x-show="!showInput">Search by tag</span>
                <input x-show="showInput" type="text" placeholder="Enter tag..."
                    class="border px-2 py-1 text-sm rounded focus:outline-none" @click.stop>
            </div>
        </div> --}}
    </div>
    <div class="container mx-auto px-4 py-6">


        <!-- Featured Campaign Section -->
        @if (count($featuredCampaigns) > 0)
            <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Featured campaigns</h2>

            <div
                class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm ">
                @foreach ($featuredCampaigns as $campaign)
                    <div class="flex flex-col lg:flex-row">
                        <!-- Left Column - Track Info -->
                        <div
                            class="w-full lg:w-1/2  border-b lg:border-b-0 lg:border-r border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700">
                            <div class="flex flex-col md:flex-row gap-4">
                                <!-- Album Art -->
                                {{-- <div class="relative w-full md:w-48  h-48 flex-shrink-0">
                            <img src="{{ asset('default_img/other.png') }}" alt="Album Cover for Sinphony by Terrik"
                                class="w-full h-full object-cover rounded" />

                        </div> --}}



                                <!-- Track Details -->
                                <div class="flex-1 flex flex-col justify-between p-2 relative">
                                    <x-sound-cloud.sound-cloud-player :track="$campaign->music" :height="166"
                                        :visual="false" />
                                    <div
                                        class="absolute top-2 left-2 bg-cyan-600 text-white text-xs font-semibold px-2 py-0.5 rounded shadow z-10 tracking-wide">
                                        FEATURED
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column - Campaign Info -->
                        <div class="w-full lg:w-1/2 p-4">
                            <div class="flex flex-col h-full justify-between">
                                <!-- Avatar + Title + Icon -->
                                <div
                                    class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-4">
                                   <div class="flex items-center gap-3">
                                        <img class="w-14 h-14 rounded-full object-cover"
                                            src="{{ auth_storage_url($campaign?->music?->user?->avatar) }}" alt="Audio Cure avatar">
                                        <div x-data="{ open: false }" class="relative inline-block text-left">
                                            <!-- Trigger -->
                                            <div @click="open = !open" class="flex items-center gap-1 cursor-pointer">
                                                <span class="text-slate-700 dark:text-gray-300 font-medium">{{ $campaign?->music?->user?->name }}</span>
                                                <svg class="w-4 h-4 text-gray-600 dark:text-gray-400" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                </svg>
                                            </div>
                                             <svg class="w-5 h-5 text-gray-400 dark:text-gray-500 mt-1" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                    d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.783-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z">
                                                </path>
                                            </svg>

                                            <!-- Dropdown Menu -->
                                            <div x-show="open" @click.outside="open = false" x-transition
                                                class="absolute -right-13 mt-2 w-56 z-50 shadow-lg bg-gray-900 gr text-white text-sm p-2 space-y-2">
                                                <a href="#"
                                                    class="block hover:bg-gray-800 px-3 py-1 rounded">Visit
                                                    SoundCloud Profile</a>
                                                <a href="#"
                                                    class="block hover:bg-gray-800 px-3 py-1 rounded">Visit
                                                    RepostExchange Profile</a>
                                                <button
                                                    class="block w-full text-left hover:bg-gray-800 px-3 py-1 rounded">Hide
                                                    all content from this member</button>
                                                <button
                                                    class="block w-full text-left hover:bg-gray-800 px-3 py-1 rounded">Hide
                                                    this track</button>
                                            </div>
                                        </div>

                                    </div>

                                    <!-- Stats and Repost Button -->
                                    <div class="flex items-center gap-4 sm:gap-8">
                                        <div
                                            class="flex flex-col items-center sm:items-start text-gray-600 dark:text-gray-400">
                                            <div class="flex items-center gap-1.5">
                                                <svg width="26" height="18" viewBox="0 0 26 18" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <rect x="1" y="1" width="24" height="16" rx="3"
                                                        fill="none" stroke="currentColor" stroke-width="2" />
                                                    <circle cx="8" cy="9" r="3" fill="none"
                                                        stroke="currentColor" stroke-width="2" />
                                                </svg>
                                                <span class="text-sm sm:text-base">103</span>
                                            </div>
                                            <span class="text-xs text-gray-500 dark:text-gray-500 mt-1">REMAINING</span>
                                        </div>

                                        <button
                                            class="flex items-center gap-2 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 py-2 px-3 sm:px-5 sm:pl-8 rounded-md shadow-sm text-sm sm:text-base transition-colors">
                                            <svg width="26" height="18" viewBox="0 0 26 18" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <rect x="1" y="1" width="24" height="16" rx="3"
                                                    fill="none" stroke="currentColor" stroke-width="2" />
                                                <circle cx="8" cy="9" r="3" fill="none"
                                                    stroke="currentColor" stroke-width="2" />
                                            </svg>
                                            <span>1 Repost</span>
                                        </button>
                                    </div>
                                </div>

                                <!-- Genre Badge -->
                                <div class="mt-auto">
                                    <span
                                        class="inline-block bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm font-medium px-3 py-1.5 rounded-md shadow-sm">
                                        Dance & EDM
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        @if (count($campaigns) > 0)
            <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200 my-4">Recommended Campaigns</h2>

            <div
                class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm ">
                @foreach ($campaigns as $campaign)
                    <div class="flex flex-col lg:flex-row">
                        <!-- Left Column - Track Info -->
                        <div
                            class="w-full lg:w-1/2  border-b lg:border-b-0 lg:border-r border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700">
                            <div class="flex flex-col md:flex-row gap-4">
                                <!-- Album Art -->
                                {{-- <div class="relative w-full md:w-48  h-48 flex-shrink-0">
                            <img src="{{ asset('default_img/other.png') }}" alt="Album Cover for Sinphony by Terrik"
                                class="w-full h-full object-cover rounded" />
                            <div
                                class="absolute top-2 left-2 bg-cyan-600 text-white text-xs font-semibold px-2 py-0.5 rounded shadow z-10 tracking-wide">
                                FEATURED
                            </div>
                        </div> --}}

                                <!-- Track Details -->
                                <div class="flex-1 flex flex-col justify-between p-2">
                                    <x-sound-cloud.sound-cloud-player :track="$campaign->music" :height="166"
                                        :visual="false" />
                                </div>
                            </div>
                        </div>

                        <!-- Right Column - Campaign Info -->
                        <div class="w-full lg:w-1/2 p-4">
                            <div class="flex flex-col h-full justify-between">
                                <!-- Avatar + Title + Icon -->
                                <div
                                class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-4">
                                <div class="flex items-center gap-3">
                                    <img class="w-14 h-14 rounded-full object-cover"
                                        src="{{ asset('default_img/other.png') }}" alt="Audio Cure avatar">
                                    <div x-data="{ open: false }" class="relative inline-block text-left">
                                        <!-- Trigger -->
                                        <div @click="open = !open" class="flex items-center gap-1 cursor-pointer">
                                            <span class="text-slate-700 dark:text-gray-300 font-medium">Audio
                                                Cure</span>
                                            <svg class="w-4 h-4 text-gray-600 dark:text-gray-400" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </div>
                                         <svg class="w-5 h-5 text-gray-400 dark:text-gray-500 mt-1" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                    d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.783-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z">
                                                </path>
                                            </svg>

                                        <!-- Dropdown Menu -->
                                        <div x-show="open" @click.outside="open = false" x-transition
                                            class="absolute -right-13 mt-2 w-56 z-50 shadow-lg bg-gray-900 gr text-white text-sm p-2 space-y-2">
                                            <a href="#" class="block hover:bg-gray-800 px-3 py-1 rounded">Visit
                                                SoundCloud Profile</a>
                                            <a href="#" class="block hover:bg-gray-800 px-3 py-1 rounded">Visit
                                                RepostExchange Profile</a>
                                            <button
                                                class="block w-full text-left hover:bg-gray-800 px-3 py-1 rounded">Hide
                                                all content from this member</button>
                                            <button
                                                class="block w-full text-left hover:bg-gray-800 px-3 py-1 rounded">Hide
                                                this track</button>
                                        </div>
                                    </div>

                                </div>

                                <!-- Stats and Repost Button -->
                                <div class="flex items-center gap-4 sm:gap-8">
                                    <div
                                        class="flex flex-col items-center sm:items-start text-gray-600 dark:text-gray-400">
                                        <div class="flex items-center gap-1.5">
                                            <svg width="26" height="18" viewBox="0 0 26 18" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <rect x="1" y="1" width="24" height="16" rx="3"
                                                    fill="none" stroke="currentColor" stroke-width="2" />
                                                <circle cx="8" cy="9" r="3" fill="none"
                                                    stroke="currentColor" stroke-width="2" />
                                            </svg>
                                            <span class="text-sm sm:text-base">103</span>
                                        </div>
                                        <span class="text-xs text-gray-500 dark:text-gray-500 mt-1">REMAINING</span>
                                    </div>

                                    <button
                                        class="flex items-center gap-2 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 py-2 px-3 sm:px-5 sm:pl-8 rounded-md shadow-sm text-sm sm:text-base transition-colors">
                                        <svg width="26" height="18" viewBox="0 0 26 18" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <rect x="1" y="1" width="24" height="16" rx="3"
                                                fill="none" stroke="currentColor" stroke-width="2" />
                                            <circle cx="8" cy="9" r="3" fill="none"
                                                stroke="currentColor" stroke-width="2" />
                                        </svg>
                                        <span>1 Repost</span>
                                    </button>
                                </div>
                            </div>


                                <!-- Genre Badge -->
                                <div class="mt-auto">
                                    <span
                                        class="inline-block bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm font-medium px-3 py-1.5 rounded-md shadow-sm">
                                        Dance & EDM
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
        @if (count($campaigns) == 0 && count($featuredCampaigns) == 0)
            <div class="text-center text-gray-500 dark:text-gray-400 mt-6">
                <p>No campaigns available at the moment.</p>
            </div>
        @endif
    </div>
</section>









{{-- <div class="flex justify-between items-start">
                                <div class="flex items-start gap-3">
                                    <!-- Play Button -->
                                    <button
                                        class="w-11 h-11 bg-orange-500 hover:bg-orange-600 text-white rounded-full flex items-center justify-center shadow transition flex-shrink-0 mt-1"
                                        aria-label="Play/Pause" type="button">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-0.5"
                                            fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M4.018 15.394C3.42 15.76 3 15.303 3 14.65V5.35C3 4.697 3.42 4.24 4.018 4.606l8.638 4.65c.596.32.596.868 0 1.188l-8.638 4.95z" />
                                        </svg>
                                    </button>

                                    <!-- Artist & Track Info -->
                                    <div>
                                        <a href="#"
                                            class="text-sm text-gray-500 dark:text-gray-400 hover:text-black dark:hover:text-white underline">Terrik</a>
                                        <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100 leading-tight">
                                            Sinphony</h3>
                                    </div>
                                </div>

                                <!-- Social Actions -->
                                <div class="flex flex-col items-end text-gray-500 dark:text-gray-400 text-sm">
                                    <!-- SoundCloud Logo -->
                                    <a href="#" class="mb-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-12 hover:text-orange-600"
                                            viewBox="0 0 100 14">
                                            <path class="logo__path" fill="currentColor"
                                                d="M10.517 3.742c-.323 0-.49.363-.49.582 0 0-.244 3.591-.244 4.641 0 1.602.15 2.621.15 2.621 0 .222.261.401.584.401.321 0 .519-.179.519-.401 0 0 .398-1.038.398-2.639 0-1.837-.153-4.127-.284-4.592-.112-.395-.313-.613-.633-.613zm-1.996.268c-.323 0-.49.363-.49.582 0 0-.244 3.322-.244 4.372 0 1.602.119 2.621.119 2.621 0 .222.26.401.584.401.321 0 .581-.179.581-.401 0 0 .081-1.007.081-2.608 0-1.837-.206-4.386-.206-4.386 0-.218-.104-.581-.425-.581zm-2.021 1.729c-.324 0-.49.362-.49.582 0 0-.272 1.594-.272 2.644 0 1.602.179 2.559.179 2.559 0 .222.229.463.552.463.321 0 .519-.241.519-.463 0 0 .19-.944.19-2.546 0-1.837-.253-2.657-.253-2.657 0-.22-.104-.582-.425-.582zm-2.046-.358c-.323 0-.49.363-.49.582 0 0-.162 1.92-.162 2.97 0 1.602.069 2.496.069 2.496 0 .222.26.557.584.557.321 0 .581-.304.581-.526 0 0 .143-.936.143-2.538 0-1.837-.206-2.96-.206-2.96 0-.218-.198-.581-.519-.581zm-2.169 1.482c-.272 0-.232.218-.232.218v3.982s-.04.335.232.335c.351 0 .716-.832.716-2.348 0-1.245-.436-2.187-.716-2.187zm18.715-.976c-.289 0-.567.042-.832.116-.417-2.266-2.806-3.989-5.263-3.989-1.127 0-2.095.705-2.931 1.316v8.16s0 .484.5.484h8.526c1.655 0 3-1.55 3-3.155 0-1.607-1.346-2.932-3-2.932zm10.17.857c-1.077-.253-1.368-.389-1.368-.815 0-.3.242-.611.97-.611.621 0 1.106.253 1.542.699l.981-.951c-.641-.669-1.417-1.067-2.474-1.067-1.339 0-2.425.757-2.425 1.99 0 1.338.873 1.736 2.124 2.026 1.281.291 1.513.486 1.513.923 0 .514-.379.738-1.184.738-.65 0-1.26-.223-1.736-.777l-.98.873c.514.757 1.504 1.232 2.639 1.232 1.853 0 2.668-.873 2.668-2.163 0-1.477-1.193-1.845-2.27-2.097zm6.803-2.745c-1.853 0-2.949 1.435-2.949 3.502s1.096 3.501 2.949 3.501c1.852 0 2.949-1.434 2.949-3.501s-1.096-3.502-2.949-3.502zm0 5.655c-1.097 0-1.553-.941-1.553-2.153 0-1.213.456-2.153 1.553-2.153 1.096 0 1.551.94 1.551 2.153.001 1.213-.454 2.153-1.551 2.153zm8.939-1.736c0 1.086-.533 1.756-1.396 1.756-.864 0-1.388-.689-1.388-1.775v-3.897h-1.358v3.916c0 1.978 1.106 3.084 2.746 3.084 1.726 0 2.754-1.136 2.754-3.103v-3.897h-1.358v3.916zm8.142-.89l.019 1.485c-.087-.174-.31-.515-.475-.768l-2.703-3.692h-1.362v6.894h1.401v-2.988l-.02-1.484c.088.175.311.514.475.767l2.79 3.705h1.213v-6.894h-1.339v2.975zm5.895-2.923h-2.124v6.791h2.027c1.746 0 3.474-1.01 3.474-3.395 0-2.484-1.437-3.396-3.377-3.396zm-.097 5.472h-.67v-4.152h.719c1.436 0 2.028.688 2.028 2.076 0 1.242-.651 2.076-2.077 2.076zm7.909-4.229c.611 0 1 .271 1.242.737l1.26-.582c-.426-.883-1.202-1.503-2.483-1.503-1.775 0-3.016 1.435-3.016 3.502 0 2.143 1.191 3.501 2.968 3.501 1.232 0 2.047-.572 2.513-1.533l-1.145-.68c-.358.602-.718.864-1.329.864-1.019 0-1.611-.932-1.611-2.153-.001-1.261.583-2.153 1.601-2.153zm5.17-1.192h-1.359v6.791h4.083v-1.338h-2.724v-5.453zm6.396-.157c-1.854 0-2.949 1.435-2.949 3.502s1.095 3.501 2.949 3.501c1.853 0 2.95-1.434 2.95-3.501s-1.097-3.502-2.95-3.502zm0 5.655c-1.097 0-1.553-.941-1.553-2.153 0-1.213.456-2.153 1.553-2.153 1.095 0 1.55.94 1.55 2.153.001 1.213-.454 2.153-1.55 2.153zm8.557-1.736c0 1.086-.532 1.756-1.396 1.756-.864 0-1.388-.689-1.388-1.775v-3.794h-1.358v3.813c0 1.978 1.106 3.084 2.746 3.084 1.726 0 2.755-1.136 2.755-3.103v-3.794h-1.36v3.813zm5.449-3.907h-2.318v6.978h2.211c1.908 0 3.789-1.037 3.789-3.489 0-2.552-1.565-3.489-3.682-3.489zm-.108 5.623h-.729v-4.266h.783c1.565 0 2.21.706 2.21 2.133.001 1.276-.707 2.133-2.264 2.133z">
                                            </path>
                                        </svg>
                                    </a>

                                    <!-- Action Buttons -->
                                    <div class="flex items-center gap-1.5">
                                        <button
                                            class="hover:text-gray-900 dark:hover:text-gray-200 border p-1 rounded border-gray-300 dark:border-gray-600"
                                            aria-label="Like">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="1.8"
                                                viewBox="0 0 24 24">
                                                <path d="M6 6h15l-1.5 9h-13.5l-1.5-9z" />
                                                <circle cx="9" cy="20" r="1.2" />
                                                <circle cx="18" cy="20" r="1.2" />
                                            </svg>
                                        </button>
                                        <button
                                            class="flex items-center text-xs gap-1.5 border border-gray-300 dark:border-gray-600 rounded px-2.5 py-0.5 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-gray-200">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14">
                                                </path>
                                            </svg>
                                            <span>Share</span>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Waveform Visualization -->
                            <div class="relative w-full h-[65px] cursor-pointer group mt-3">
                                <div class="absolute inset-0 flex items-center gap-px overflow-hidden">
                                    @php
                                        $total_bars = 190;
                                        // Calculate played bars based on screenshot timestamp (53s / 198s)
                                        $played_percentage = 53 / (3 * 60 + 18);
                                        $played_bars = floor($total_bars * $played_percentage);
                                    @endphp
                                    @for ($i = 0; $i < $total_bars; $i++)
                                        <div class="w-[3px] {{ $i < $played_bars ? 'bg-orange-500' : 'bg-gray-400 dark:bg-gray-600' }}"
                                            style="height: {{ rand(15, 95) }}%"></div>
                                    @endfor
                                </div>

                                <!-- Time Indicators -->
                                <div class="absolute inset-0 w-full h-full">
                                    <span
                                        class="absolute left-2 top-2 bg-black/70 text-white text-xs px-1.5 py-0.5 rounded-sm font-mono">0:53</span>
                                    <span
                                        class="absolute right-2 bottom-0 text-white text-xs font-mono mix-blend-difference">3:18</span>

                                    <!-- User Avatars on Waveform -->
                                    <img src="https://i.pravatar.cc/16?u=user1"
                                        class="absolute w-4 h-4 rounded-full border border-white"
                                        style="left: 32%; bottom: 6px;">
                                    <img src="https://i.pravatar.cc/16?u=user2"
                                        class="absolute w-4 h-4 rounded-full border border-white"
                                        style="left: 45%; bottom: 12px;">
                                    <img src="https://i.pravatar.cc/16?u=user3"
                                        class="absolute w-4 h-4 rounded-full border border-white"
                                        style="left: 51%; bottom: 4px;">
                                    <img src="https://i.pravatar.cc/16?u=user4"
                                        class="absolute w-4 h-4 rounded-full border border-white"
                                        style="left: 68%; bottom: 10px;">
                                    <img src="https://i.pravatar.cc/16?u=user5"
                                        class="absolute w-4 h-4 rounded-full border border-white"
                                        style="left: 76%; bottom: 8px;">
                                    <img src="https://i.pravatar.cc/16?u=user6"
                                        class="absolute w-4 h-4 rounded-full border border-white"
                                        style="left: 80%; bottom: 14px;">
                                    <img src="https://i.pravatar.cc/16?u=user7"
                                        class="absolute w-4 h-4 rounded-full border border-white"
                                        style="left: 92%; bottom: 10px;">

                                    <!-- SoundCloud Logo -->
                                    <div class="absolute top-1 right-2 text-white">
                                        <svg role="img" viewBox="0 0 24 24" class="w-9 h-9 opacity-60"
                                            fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                            <title>SoundCloud</title>
                                            <path
                                                d="M7.243 17.332c0 1.47-1.19 2.668-2.657 2.668S1.93 18.802 1.93 17.332s1.19-2.668 2.656-2.668c1.467 0 2.657 1.2 2.657 2.668zm0-10.668V0H0v10.664h1.93v-3.996c0-2.58 2.11-4.668 4.71-4.668h.602zm1.205 10.668c0 1.47-1.19 2.668-2.657 2.668S3.134 18.802 3.134 17.332s1.19-2.668 2.657-2.668c1.466 0 2.657 1.2 2.657 2.668zm1.21-10.668V4h-1.21v2.664h1.21zm1.205 10.668c0 1.47-1.19 2.668-2.657 2.668s-2.657-1.198-2.657-2.668s1.19-2.668 2.657-2.668c1.467 0 2.657 1.2 2.657 2.668zm1.21-10.668V4h-1.21v2.664h1.21zm1.205 10.668c0 1.47-1.19 2.668-2.657 2.668s-2.657-1.198-2.657-2.668s1.19-2.668 2.657-2.668c1.467 0 2.657 1.2 2.657 2.668zm1.208-10.668V4h-1.208v2.664h1.208zm1.206 10.668c0 1.47-1.19 2.668-2.656 2.668s-2.657-1.198-2.657-2.668s1.19-2.668 2.657-2.668c1.466 0 2.656 1.2 2.656 2.668zm1.208-10.668V4h-1.208v2.664h1.208zm3.02 0V4h-1.207v2.664h1.207zm-1.812 14.664V12h1.207v9.332h5.428V12h1.206v9.332H24V12h-1.18v10.668h-4.812z" />
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <!-- Footer Links -->
                            <div
                                class="flex justify-between items-center text-xs text-gray-500 dark:text-gray-400 mt-2">
                                <a href="#" class="hover:underline">Privacy policy</a>
                                <span class="flex items-center gap-1.5">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z">
                                        </path>
                                    </svg>
                                    123
                                </span>
                            </div> --}}
