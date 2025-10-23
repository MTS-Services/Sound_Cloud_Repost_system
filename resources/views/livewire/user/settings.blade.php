<section>
    <x-slot name="page_slug">settings</x-slot>
    {{-- error check --}}

    <style>
        /* Custom styles for the active tab indicator */
        .tab-indicator {
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 100%;
            height: 2px;
            background-color: orange;
            /* Blue color for the active tab */
            transition: transform 0.3s ease-in-out;
        }
        
        /* Hide cloaked elements until Alpine shows them */
        [x-cloak] {
            display: none !important;
        }
    </style>

    <div x-data="{ open: true, activeTab: @entangle('activeTab').live, isGenreDropdownOpen: false }" class="#">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg max-w-8xl mx-auto overflow-hidden ">
            <div class="border-b border-gray-200 dark:border-gray-700 ">
                <div
                    class="flex overflow-x-auto no-scrollbar text-gray-600 dark:text-gray-300 font-medium text-xs sm:text-sm">
                    <button wire:click="setActiveTab('profile')"
                        class="py-3 px-4 sm:py-4 sm:px-6 whitespace-nowrap relative tab-link focus:outline-none transition-colors duration-200 ease-in-out {{ $activeTab === 'profile' ? 'border-b-2 border-orange-500 text-orange-500' : 'hover:bg-gray-50 dark:hover:bg-gray-600' }}">
                        Edit profile
                        <span class="tab-indicator {{ $activeTab === 'profile' ? '' : 'hidden' }}"></span>
                    </button>

                    <button wire:click="setActiveTab('notifications')"
                        class="py-3 px-4 sm:py-4 sm:px-6 whitespace-nowrap relative tab-link focus:outline-none transition-colors duration-200 ease-in-out {{ $activeTab === 'notifications' ? 'border-b-2 border-orange-500 text-orange-500' : 'hover:bg-gray-50 dark:hover:bg-gray-600' }}">
                        Notifications & alerts
                        <span class="tab-indicator {{ $activeTab === 'notifications' ? '' : 'hidden' }}"></span>
                    </button>

                    <button wire:click="setActiveTab('settings')"
                        class="py-3 px-4 sm:py-4 sm:px-6 whitespace-nowrap relative tab-link focus:outline-none transition-colors duration-200 ease-in-out {{ $activeTab === 'settings' ? 'border-b-2 border-orange-500 text-orange-500' : 'hover:bg-gray-50 dark:hover:bg-gray-600' }}">
                        Settings
                        <span class="tab-indicator {{ $activeTab === 'settings' ? '' : 'hidden' }}"></span>
                    </button>

                    <button wire:click="setActiveTab('credit')"
                        class="py-3 px-4 sm:py-4 sm:px-6 whitespace-nowrap relative tab-link focus:outline-none transition-colors duration-200 ease-in-out {{ $activeTab === 'credit' ? 'border-b-2 border-orange-500 text-orange-500' : 'hover:bg-gray-50 dark:hover:bg-gray-600' }}">
                        Credit history
                        <span class="tab-indicator {{ $activeTab === 'credit' ? '' : 'hidden' }}"></span>
                    </button>

                    <button wire:click="setActiveTab('invoices')"
                        class="py-3 px-4 sm:py-4 sm:px-6 whitespace-nowrap relative tab-link focus:outline-none transition-colors duration-200 ease-in-out {{ $activeTab === 'invoices' ? 'border-b-2 border-orange-500 text-orange-500' : 'hover:bg-gray-50 dark:hover:bg-gray-600' }}">
                        Invoices
                        <span class="tab-indicator {{ $activeTab === 'invoices' ? '' : 'hidden' }}"></span>
                    </button>
                </div>
            </div>


            <div x-show="activeTab === 'profile'" x-cloak>
                <!-- Edit Profile -->
                <div class="p-3">
                    <div x-data="{ open: true }" x-show="open"
                        class="mb-6 gap-4 relative overflow-hidden bg-gradient-to-br from-orange-500 via-orange-400 to-amber-400 p-4 rounded-xl shadow-lg">

                        <!-- Decorative circles -->
                        <div class="absolute top-0 right-0 w-24 h-24 bg-white/10 rounded-full -mr-12 -mt-12"></div>
                        <div class="absolute bottom-0 left-0 w-20 h-20 bg-white/10 rounded-full -ml-10 -mb-10"></div>

                        <!-- Main content -->
                        <div class="relative flex items-center gap-3">
                            <!-- Icon -->
                            <div
                                class="flex-shrink-0 w-9 h-9 bg-white/20 backdrop-blur-sm rounded-lg flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="lucide lucide-lightbulb w-4 h-4 text-white">
                                    <path
                                        d="M15 14c.2-1 .7-1.7 1.5-2.5 1-.9 1.5-2.2 1.5-3.5A6 6 0 0 0 6 8c0 1 .2 2.2 1.5 3.5.7.7 1.3 1.5 1.5 2.5">
                                    </path>
                                    <path d="M9 18h6"></path>
                                    <path d="M10 22h4"></path>
                                </svg>
                            </div>

                            <!-- Text and close button -->
                            <div class="flex-1">
                                <div class="flex items-center justify-between gap-2">
                                    <div>
                                        <h4 class="font-bold text-white mb-0.5 text-sm">Quick Tip</h4>
                                        <p class="text-white/95 text-xs leading-relaxed">
                                            Customise your genres to personalise your Repostchaine experience.
                                        </p>
                                    </div>
                                    <button @click="open = false"
                                        class="flex-shrink-0 p-1 text-white/80 hover:text-white hover:bg-white/20 rounded-lg transition-all">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="lucide lucide-x w-4 h-4">
                                            <path d="M18 6 6 18"></path>
                                            <path d="m6 6 12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <form wire:submit.prevent="saveProfile">
                    <div class="w-full ">
                        <!-- Email -->
                        <div class=" p-3">
                            <!-- Wrapper with gradient background -->
                            <div
                                class="bg-gradient-to-br from-gray-50 to-white p-4 rounded-xl border border-gray-100 shadow-sm relative overflow-hidden">
                                <!-- Decorative circles -->
                                <div class="absolute top-0 right-0 w-24 h-24 bg-white/10 rounded-full -mr-12 -mt-12">
                                </div>
                                <div class="absolute bottom-0 left-0 w-20 h-20 bg-white/10 rounded-full -ml-10 -mb-10">
                                </div>

                                <!-- Content -->
                                <div class="relative">
                                    <!-- Label -->
                                    <label for="email" class="block text-sm font-bold text-gray-900 mb-1.5">
                                        Email Address
                                    </label>

                                    <!-- Input -->
                                    <input type="email" id="email" wire:model="email"
                                        class="w-full px-3 py-2.5 bg-white border-2 border-gray-200 rounded-lg text-gray-900 text-sm focus:outline-none focus:ring-4 focus:ring-orange-500/20 focus:border-orange-500 transition-all placeholder:text-gray-400"
                                        placeholder="your.email@example.com">

                                    <!-- Error / Verification Message -->
                                    @if (!user()->email_verified_at)
                                        <div class="flex items-center gap-2 mt-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-white/90"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M12 9v2m0 4h.01M21 12A9 9 0 1 1 3 12a9 9 0 0 1 18 0Z" />
                                            </svg>
                                            <p class="text-xs text-white/90">
                                                Email not verified, please verify your email.
                                            </p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Genres -->
                        <div class=" p-3" x-data="{ isGenreDropdownOpen: false }">
                            <!-- Gray Gradient Wrapper -->
                            <div
                                class="bg-gradient-to-br from-gray-50 to-white p-4 rounded-xl border border-gray-100 shadow-sm">
                                <!-- Decorative circles -->
                                <div class="absolute top-0 right-0 w-24 h-24 bg-white/10 rounded-full -mr-12 -mt-12">
                                </div>
                                <div class="absolute bottom-0 left-0 w-20 h-20 bg-white/10 rounded-full -ml-10 -mb-10">
                                </div>

                                <!-- Content -->
                                <div class="relative space-y-3">
                                    <!-- Header -->
                                    <div class="border-b border-white/30 pb-2">
                                        <h3 class="block text-sm font-bold text-gray-900 mb-1.5">Your Music Genres</h3>
                                    </div>

                                    <!-- Selected Genres + Input -->
                                    <div class="relative">
                                        <div
                                            class="flex flex-wrap gap-2 p-3 bg-white border-2 border-gray-200 rounded-lg min-h-[48px] focus-within:ring-4 focus-within:ring-orange-500/20 focus-within:border-orange-500 transition-all">

                                            <!-- Selected genres -->
                                            @foreach ($selectedGenres as $genre)
                                                <span
                                                    class="group inline-flex items-center gap-1.5 px-2.5 py-1.5 bg-gradient-to-br from-orange-500 to-orange-600 text-white rounded-lg text-xs font-semibold shadow-md hover:shadow-lg hover:scale-105 transition-all">
                                                    {{ $genre }}
                                                    <button type="button"
                                                        wire:click="removeGenre('{{ $genre }}')"
                                                        class="p-0.5 hover:bg-white/20 rounded-md transition-all">
                                                        <x-lucide-x class="w-3 h-3 text-white" />
                                                    </button>
                                                </span>
                                            @endforeach

                                            <!-- Input -->
                                            <input type="text" wire:model.live="searchTerm" x-ref="searchInput"
                                                x-on:click="isGenreDropdownOpen = true"
                                                x-on:input="isGenreDropdownOpen = true"
                                                placeholder="{{ count($selectedGenres) < 5 ? 'Search genres...' : '' }}"
                                                class="flex-1 outline-none border-0 text-xs font-medium 
                                                focus:outline-none focus:ring-0 bg-transparent text-black placeholder:text-gray-600 min-w-[100px]"
                                                @if (count($selectedGenres) >= $maxGenres) disabled @endif>

                                            <!-- Search icon -->
                                            <svg class="w-4 h-4 text-black mt-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                            </svg>
                                        </div>

                                        <!-- Genre Limit or Count Errors -->
                                        @if ($genreLimitError)
                                            <div class="flex items-center mt-2 text-white text-sm">
                                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                                You can only choose up to {{ $maxGenres }} genres.
                                            </div>
                                        @elseif ($genreCountError)
                                            <div class="flex items-center mt-2 text-white text-sm">
                                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                                Please select at least {{ $minGenres }} genres.
                                            </div>
                                        @endif

                                        <!-- Dropdown -->
                                        <div x-show="isGenreDropdownOpen" @click.outside="isGenreDropdownOpen = false"
                                            x-transition:enter="transition ease-out duration-100"
                                            x-transition:enter-start="opacity-0 scale-95"
                                            x-transition:enter-end="opacity-100 scale-100"
                                            x-transition:leave="transition ease-in duration-75"
                                            x-transition:leave-start="opacity-100 scale-100"
                                            x-transition:leave-end="opacity-0 scale-95"
                                            class="absolute z-10 w-full mt-2 bg-white/95 backdrop-blur-md border border-white/30 rounded-lg shadow-lg max-h-60 overflow-y-auto">

                                            @if (count($this->filteredGenres) > 0)
                                                @foreach ($this->filteredGenres as $genre)
                                                    <button type="button"
                                                        wire:click="addGenre('{{ $genre }}')"
                                                        class="w-full text-left px-4 py-2 text-sm text-gray-800 hover:bg-orange-100 focus:bg-orange-100 focus:outline-none"
                                                        @if (count($selectedGenres) >= $maxGenres) disabled @endif>
                                                        {{ $genre }}
                                                    </button>
                                                @endforeach
                                            @else
                                                <div class="px-4 py-2 text-sm text-gray-500">
                                                    @if (empty($searchTerm))
                                                        @if (count($selectedGenres) >= count($availableGenres))
                                                            All genres selected
                                                        @else
                                                            No more genres available
                                                        @endif
                                                    @else
                                                        No genres found matching "{{ $searchTerm }}"
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Artist link -->
                        <div class=" p-3">
                            <div
                                class="bg-gradient-to-br from-gray-50 to-white p-4 rounded-xl border border-gray-100 shadow-sm">
                                <div class="flex items-center justify-between mb-6 pb-6 border-b border-gray-200">
                                    <h3 class="dark:text-white text-lg font-bold text-gray-900">Social Accounts</h3>
                                </div>
                                <!-- Header -->
                                <div class="mb-3">
                                    <p class="text-sm text-gray-600 dark:text-gray-300">
                                        Connect your social accounts (will be promoted after someone reposts one of your
                                        tracks if you have a Pro Plan)
                                    </p>
                                </div>

                                <!-- Social Inputs Grid -->
                                <div class="grid grid-cols-2 gap-2.5">
                                    <!-- Instagram -->
                                    <div
                                        class="group relative bg-white rounded-lg border-2 border-gray-200 overflow-hidden hover:border-pink-300 transition-all">
                                        <div
                                            class="absolute left-0 top-0 bottom-0 w-10 bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center">
                                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z">
                                                </path>
                                            </svg>
                                        </div>
                                        <input type="text" placeholder="@username" wire:model="instagram_username"
                                            class="w-full pl-12 pr-3 py-2.5 bg-transparent focus:outline-none border-0 text-gray-900 placeholder:text-gray-400 font-medium text-xs">
                                    </div>



                                    <!-- Twitter -->
                                    <div
                                        class="group relative bg-white rounded-lg border-2 border-gray-200 dark:border-gray-700 overflow-hidden hover:border-blue-300 transition-all">
                                        <div
                                            class="absolute left-0 top-0 bottom-0 w-10 bg-blue-400 flex items-center justify-center">
                                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z">
                                                </path>
                                            </svg>
                                        </div>
                                        <input type="text" placeholder="@username" wire:model="twitter_username"
                                            class="w-full pl-12 pr-3 py-2.5 bg-transparent border-0 focus:outline-none text-gray-900 placeholder:text-gray-400 font-medium text-xs">
                                    </div>

                                    <!-- Facebook -->
                                    <div
                                        class="group relative bg-white rounded-lg border-2 border-gray-200 dark:border-gray-700 overflow-hidden hover:border-blue-600 transition-all">
                                        <div
                                            class="absolute left-0 top-0 bottom-0 w-10 bg-blue-600 flex items-center justify-center">
                                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z">
                                                </path>
                                            </svg>
                                        </div>
                                        <input type="text" placeholder="/pagelink" wire:model="facebook_username"
                                            class="w-full pl-12 pr-3 py-2.5 bg-transparent focus:outline-none text-gray-900 placeholder:text-gray-400 font-medium text-xs border-0">
                                    </div>

                                    <!-- YouTube -->
                                    <div
                                        class="group relative bg-white rounded-lg border-2 border-gray-200 dark:border-gray-700 overflow-hidden hover:border-red-400 transition-all">
                                        <div
                                            class="absolute left-0 top-0 bottom-0 w-10 bg-red-600 flex items-center justify-center">
                                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z">
                                                </path>
                                            </svg>
                                        </div>
                                        <input type="text" placeholder="Channel ID"
                                            wire:model="youtube_channel_id"
                                            class="w-full pl-12 pr-3 py-2.5 bg-transparent border-0 focus:outline-none text-gray-900 placeholder:text-gray-400 font-medium text-xs">
                                    </div>

                                    <!-- TikTok -->
                                    <div
                                        class="group relative bg-white rounded-lg border-2 border-gray-200 dark:border-gray-700 overflow-hidden hover:border-black transition-all">
                                        <div
                                            class="absolute left-0 top-0 bottom-0 w-10 bg-black flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-music w-4 h-4 text-white">
                                                <path d="M9 18V5l12-2v13"></path>
                                                <circle cx="6" cy="18" r="3"></circle>
                                                <circle cx="18" cy="16" r="3"></circle>
                                            </svg>
                                        </div>
                                        <input type="text" placeholder="@username" wire:model="tiktok_username"
                                            class="w-full pl-12 pr-3 py-2.5 bg-transparent border-0 focus:outline-none text-gray-900 placeholder:text-gray-400 font-medium text-xs">
                                    </div>



                                    <!-- Spotify (full width) -->
                                    <div
                                        class="group relative bg-white rounded-lg border-2 border-gray-200 dark:border-gray-700 overflow-hidden hover:border-green-400 transition-all col-span-2">
                                        <div
                                            class="absolute left-0 top-0 bottom-0 w-10 bg-green-500 flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-music w-4 h-4 text-white">
                                                <path d="M9 18V5l12-2v13"></path>
                                                <circle cx="6" cy="18" r="3"></circle>
                                                <circle cx="18" cy="16" r="3"></circle>
                                            </svg>
                                        </div>
                                        <input type="text"
                                            placeholder="Artist link (https://open.spotify.com/artist/...)"
                                            wire:model="spotify_artist_link"
                                            class="w-full pl-12 pr-3 py-2.5 bg-transparent focus:outline-none text-gray-900 placeholder:text-gray-400 font-medium text-xs border-0">
                                    </div>
                                </div>
                            </div>


                        </div>

                    </div>
                    <div class="mt-8 flex justify-end space-x-4 border-t border-gray-200 dark:border-gray-700 p-4">
                        <x-gbutton variant="secondary" type="button"
                            onclick="window.history.back()">Cancel</x-gbutton>

                        <x-gbutton type="submit" variant="primary">
                            <span wire:loading.remove wire:target="saveProfile">Save Profile</span>
                            <span wire:loading wire:target="saveProfile" class="flex items-center">
                                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline-block"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0
                    0 5.373 0 12h4zm2 5.291A7.962
                    7.962 0 014 12H0c0 3.042 1.135
                    5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                                Saving...
                            </span>
                        </x-gbutton>
                    </div>
                </form>
                <!-- Small content below buttons -->
                <div
                    class="border-t border-gray-300 dark:border-gray-600 mt-4 p-3 text-sm text-gray-500 dark:text-gray-400">
                    Looking to delete your account?<a href="javascript:void(0)" wire:click="deleteConfirmation"
                        class="font-semibold hover:underline">Click
                        <span class="text-red-500"> here</a>.
                </div>
            </div>
        </div>
        <!-- Notifications -->

        <!-- Header -->
        <div x-show="activeTab === 'notifications'" class="mb-8">
            <div class="p-6">
                <h2 class="dark:text-white text-2xl font-bold text-gray-900 mb-2">
                    Notifications &amp; Alerts
                </h2>
                <p class="text-gray-800 mb-8">
                    Manage your email and notification preferences.
                </p>
                <!-- Alerts Section -->
                <div class="dark:bg-gray-800 from-gray-50 to-white p-8 rounded-2xl border border-gray-100 shadow-sm">
                    <!-- Title Bar -->
                    <div class="flex items-center justify-between mb-6 pb-6 border-b border-gray-200">
                        <h3 class="dark:text-white text-lg font-bold text-gray-900">Alerts</h3>
                        <span class="text-sm font-semibold text-gray-600">Email</span>
                    </div>


                    <form wire:submit.prevent="notificationUpdate">
                        @foreach ($this->alerts as $key => $alert)
                            <div
                                class="flex items-center justify-between p-4 rounded-xl bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                <span
                                    class="text-sm font-medium text-gray-900 dark:text-white">{{ $alert['name'] }}</span>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" wire:model="{{ $alert['email_key'] }}"
                                        {{ !user()->email_verified_at ? 'disabled' : '' }} class="sr-only peer">
                                    <div
                                        class="w-11 h-6 bg-gray-300 rounded-full peer peer-checked:bg-orange-500 transition-all">
                                    </div>
                                    <div
                                        class="absolute left-0.5 top-0.5 w-5 h-5 bg-white rounded-full shadow-sm transform transition-transform peer-checked:translate-x-5">
                                    </div>
                                </label>
                            </div>
                        @endforeach

                        <div class="mt-8 flex justify-end space-x-4 border-t border-gray-200 dark:border-gray-700 p-4">
                            <x-gbutton variant="secondary" type="button"
                                onclick="window.history.back()">Cancel</x-gbutton>

                            <x-gbutton type="submit" variant="primary">
                                <span wire:loading.remove wire:target="saveProfile">Save Profile</span>
                                <span wire:loading wire:target="saveProfile" class="flex items-center">
                                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline-block"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10"
                                            stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0
                    0 5.373 0 12h4zm2 5.291A7.962
                    7.962 0 014 12H0c0 3.042 1.135
                    5.824 3 7.938l3-2.647z">
                                        </path>
                                    </svg>
                                    Saving...
                                </span>
                            </x-gbutton>
                        </div>

                    </form>
                </div>
            </div>
        </div>

        <div x-show="activeTab === 'settings'" x-cloak class="p-6">
            <div class="mb-8">
                <h2 class="dark:text-white text-2xl font-bold text-gray-900 mb-2">Settings</h2>
                <p class="text-gray-800">Manage your account preferences and features.</p>
            </div>

            <form wire:submit.prevent="settingsUpdate" method="POST">
                @csrf
                <div class="space-y-8">
                    <!-- My Requests -->
                    <div class=" from-gray-50 to-white p-8 rounded-2xl border border-gray-100 shadow-sm">
                        <h3 class="dark:text-white text-lg font-bold text-gray-900 mb-6">My requests</h3>

                        <div class="space-y-1">
                            <!-- Accept Direct Repost Requests -->
                            <div
                                class="flex items-center justify-between p-4 rounded-xl bg-white hover:bg-gray-50/50 transition-all group">
                                <div>
                                    <p class="text-gray-700 dark:text-gray-900">Accept Direct repost requests</p>
                                    @if (!user()->email_verified_at)
                                        <p class="text-sm text-red-500">You must confirm your email address to
                                            accept
                                            direct repost requests</p>
                                    @endif
                                </div>
                                {{-- <div class="flex items-center gap-4">
                                        <label class="flex items-center gap-2 cursor-pointer">
                                            <input type="radio" name="acceptRequests" wire:model="accept_repost"
                                                {{ user()->email_verified_at ? '' : 'disabled' }}
                                                class="w-5 h-5 text-orange-500 {{ user()->email_verified_at ? '' : 'cursor-not-allowed' }} border-gray-300 focus:ring-2 focus:ring-orange-500/20"
                                                checked>
                                            <span class="text-sm font-medium text-gray-700">Yes</span>
                                        </label>
                                        <label class="flex items-center gap-2 cursor-pointer">
                                            <input type="radio" name="acceptRequests" wire:model="accept_repost"
                                                {{ user()->email_verified_at ? '' : 'disabled' }}
                                                class="w-5 h-5 text-orange-500  border-gray-300 focus:ring-2 focus:ring-orange-500/20 {{ user()->email_verified_at ? '' : 'cursor-not-allowed' }}">
                                            <span class="text-sm font-medium text-gray-700">No</span>
                                        </label>
                                    </div> --}}
                                <div class="flex items-center gap-6">
                                    <label class="flex items-center gap-1">
                                        <input type="radio" name="acceptRequests" wire:model="accept_repost"
                                            value="1" {{ user()->email_verified_at ? '' : 'disabled' }}
                                            class="text-orange-500 focus:ring-orange-500 {{ user()->email_verified_at ? '' : 'cursor-not-allowed' }}">
                                        <span class="text-gray-600 dark:text-white">Yes</span>
                                    </label>
                                    <label class="flex items-center gap-1">
                                        <input type="radio" name="acceptRequests" wire:model="accept_repost"
                                            value="0" {{ user()->email_verified_at ? '' : 'disabled' }}
                                            class="text-orange-500 focus:ring-orange-500 {{ user()->email_verified_at ? '' : 'cursor-not-allowed' }}">
                                        <span class="text-gray-600 dark:text-white">No</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Block Non-Matching Genres -->
                            <div
                                class="flex items-center justify-between p-4 rounded-xl bg-gray-50/30 hover:bg-gray-50/50 transition-all group">
                                <span
                                    class="text-sm font-medium text-gray-700 group-hover:text-gray-900 transition-colors">
                                    Block direct repost requests for tracks which do not match my profile
                                    genres
                                </span>
                                <div class="flex items-center gap-4">
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="radio" name="blockNonMatchingGenres"
                                            class="w-5 h-5 text-orange-500 border-gray-300 focus:ring-2 focus:ring-orange-500/20">
                                        <span class="text-sm font-medium text-gray-700">Yes</span>
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="radio" name="blockNonMatchingGenres"
                                            class="w-5 h-5 text-orange-500 border-gray-300 focus:ring-2 focus:ring-orange-500/20"
                                            checked>
                                        <span class="text-sm font-medium text-gray-700">No</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Features -->
                    <div
                        class="dark:bg-gray-800 from-gray-50 to-white p-8 rounded-2xl border border-gray-100 shadow-sm">
                        <h3 class="text-lg font-bold text-gray-900 mb-6 dark:text-white">Additional
                            features</h3>

                        <div class="space-y-1">
                            <!-- Auto Free Boost -->
                            <div
                                class="flex items-center justify-between p-4 rounded-xl bg-white hover:bg-gray-50/50 transition-all group">
                                <div class="flex items-center gap-2">
                                    <span
                                        class="text-sm font-medium text-gray-700 group-hover:text-gray-900 transition-colors">
                                        Auto Free Boost
                                    </span>
                                    <span class="text-xs text-gray-400">(i)</span>
                                </div>
                                <div class="flex items-center gap-4">
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="radio" name="autoFreeBoost"
                                            class="w-5 h-5 text-orange-500 border-gray-300 focus:ring-2 focus:ring-orange-500/20"
                                            checked>
                                        <span class="text-sm font-medium text-gray-700">Yes</span>
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="radio" name="autoFreeBoost"
                                            class="w-5 h-5 text-orange-500 border-gray-300 focus:ring-2 focus:ring-orange-500/20">
                                        <span class="text-sm font-medium text-gray-700">No</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Enable Reactions -->
                            <div
                                class="flex items-center justify-between p-4 rounded-xl bg-gray-50/30 hover:bg-gray-50/50 transition-all group">
                                <span
                                    class="text-sm font-medium text-gray-700 group-hover:text-gray-900 transition-colors">
                                    Enable Reactions
                                </span>
                                <div class="flex items-center gap-4">
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="radio" name="enableReactions"
                                            class="w-5 h-5 text-orange-500 border-gray-300 focus:ring-2 focus:ring-orange-500/20"
                                            checked>
                                        <span class="text-sm font-medium text-gray-700">Yes</span>
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="radio" name="enableReactions"
                                            class="w-5 h-5 text-orange-500 border-gray-300 focus:ring-2 focus:ring-orange-500/20">
                                        <span class="text-sm font-medium text-gray-700">No</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Subscription -->
                    <div
                        class="dake:bg-gray-800 from-gray-50 to-white p-8 rounded-2xl border border-gray-100 shadow-sm">
                        <h1 class="text-lg font-bold text-gray-900 mb-4 dark:text-white">Subscription</h1>
                        <div class="mflex items-center gap-3">
                            <p class="text-sm text-gray-600">{{ userPlanName() }} Plan <a wire:navigate
                                    href="{{ route('user.plans') }}"
                                    class="text-sm font-semibold text-orange-600 hover:text-orange-700 transition-colors">Change</a>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Footer Buttons -->
                <div class="mt-8 flex justify-end space-x-4 border-t border-gray-200 dark:border-gray-700 p-4">
                    <x-gbutton variant="secondary" type="button" onclick="window.history.back()">Cancel</x-gbutton>

                    <x-gbutton type="submit" variant="primary">
                        <span wire:loading.remove wire:target="saveProfile">Save Profile</span>
                        <span wire:loading wire:target="saveProfile" class="flex items-center">
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline-block"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                    stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0
                    0 5.373 0 12h4zm2 5.291A7.962
                    7.962 0 014 12H0c0 3.042 1.135
                    5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            Saving...
                        </span>
                    </x-gbutton>
                </div>
            </form>
        </div>

        <!-- Credit History Table -->
        <div x-show="activeTab === 'credit'" class="w-full" x-transition>
            <!-- Card Table -->
            <div class="p-6">
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-2 dark:text-white">Credit History</h2>
                    <p class="text-gray-600">Track your credit transactions and balance changes.</p>
                </div>
                <div
                    class="bg-gradient-to-br from-gray-50 to-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-gray-200 bg-white">
                                    <th class="px-8 py-5 text-left text-sm font-bold text-gray-900">Date</th>
                                    <th class="px-8 py-5 text-left text-sm font-bold text-gray-900">Description
                                    </th>
                                    <th class="px-8 py-5 text-center text-sm font-bold text-gray-900">Type</th>
                                    <th class="px-8 py-5 text-center text-sm font-bold text-gray-900">Credits</th>
                                    <th class="px-8 py-5 text-right text-sm font-bold text-gray-900">Balance</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($credits as $credit)
                                    <tr
                                        class="border-b border-gray-100 hover:bg-gray-50/50 transition-all group bg-white">
                                        <td class="px-8 py-5"><span
                                                class="text-sm text-gray-700 font-medium">{{ $credit->created_at }}</span>
                                        </td>
                                        <td class="px-8 py-5"><span
                                                class="text-sm text-gray-700">{{ $credit->description }}</span>
                                        </td>
                                        <td class="px-8 py-5">
                                            <div class="flex items-center justify-center"><span
                                                    class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-bold border {{ $credit->calculation_type == App\Models\CreditTransaction::CALCULATION_TYPE_CREDIT ? 'bg-red-100 text-red-700 bg-gradient-to-br from-red-100 to-rose-100 border-red-200' : 'bg-green-100 text-green-700 bg-gradient-to-br from-green-100 to-emerald-100 border-green-200' }}">{{ $credit->calculation_type_name }}</span>
                                            </div>
                                        </td>
                                        <td class="px-8 py-5">
                                            <div class="flex items-center justify-center gap-2">
                                                <div
                                                    class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg {{ $credit->calculation_type == App\Models\CreditTransaction::CALCULATION_TYPE_CREDIT ? 'bg-red-50 border border-red-200' : 'bg-green-50 border border-green-200' }}">
                                                    <div
                                                        class="w-5 h-5 rounded flex items-center justify-center flex-shrink-0  {{ $credit->calculation_type == App\Models\CreditTransaction::CALCULATION_TYPE_CREDIT ? 'bg-gradient-to-br from-red-500 to-red-600' : 'bg-gradient-to-br from-green-500 to-green-600' }}">
                                                        <div class="w-2.5 h-2.5 rounded-sm border-2 border-white">
                                                        </div>
                                                    </div><span
                                                        class="text-sm font-bold {{ $credit->calculation_type == App\Models\CreditTransaction::CALCULATION_TYPE_CREDIT ? 'text-red-500' : 'text-green-500' }}">{{ $credit->credits }}</span>

                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-8 py-5 text-right"><span
                                                class="text-sm font-semibold text-gray-900">{{ $credit->balance }}</span>
                                            <br>
                                            <span>Credits</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr class="">
                                        <td class="px-5 p-3 text-gray-700 dark:text-white whitespace-nowrap">
                                            No Credit found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!--invoices Section -->
        <div x-show="activeTab === 'invoices'" class="overflow-x-auto w-full " x-transition>
            <!-- Card Table -->
            <div class="p-6">
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-2 dark:text-white">Invoices</h2>
                    <p class="text-gray-600">View and download your transaction history.</p>
                </div>
                <div
                    class="dark:bg-gray-800 from-gray-50 to-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full ">
                            <thead>
                                <tr class="border-b border-gray-200 bg-white">
                                    <th class="px-8 py-5 text-left text-sm font-bold text-gray-900">Date</th>
                                    <th class="px-8 py-5 text-left text-sm font-bold text-gray-900">Description
                                    </th>
                                    <th class="px-8 py-5 text-left text-sm font-bold text-gray-900">Source</th>
                                    <th class="px-8 py-5 text-right text-sm font-bold text-gray-900">Total</th>
                                    <th class="px-8 py-5 text-center text-sm font-bold text-gray-900">Invoice</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse ($payments as $payment)
                                    <tr
                                        class="border-b border-gray-100 hover:bg-gray-50/50 transition-all group bg-white">
                                        <td class="px-8 py-5"><span
                                                class="text-sm text-gray-700 font-medium">{{ $payment->created_at_formatted }}</span>
                                        </td>
                                        <td class="px-8 py-5"><span
                                                class="text-sm text-gray-700">{{ $payment->notes ?? 'N/A' }}</span>
                                        </td>
                                        <td class="px-5 p-3 text-gray-700   whitespace-nowrap">
                                            @if ($payment->order->type == App\Models\Order::TYPE_PLAN)
                                                {{ $payment->order->source->name ?? 'N/A' }} Plan Subscription
                                            @else
                                                <div class="flex items-center gap-1">
                                                    <span
                                                        class="flex items-center gap-1 font-semibold text-orange-500">

                                                        <div
                                                            class="w-6 h-6 rounded bg-gradient-to-br from-orange-500 to-orange-600 flex items-center justify-center flex-shrink-0">
                                                            <div class="w-3 h-3 rounded-sm border-2 border-white">
                                                            </div>
                                                        </div>
                                                        {{ number_format($payment->order->credits) }}
                                                    </span>
                                                    <span class="text-sm text-gray-600">Credits</span>
                                                </div>
                                            @endif
                                        </td>

                                        <td class="px-8 py-5 text-right">
                                            <div class="flex items-center justify-end gap-2">
                                                <span class="text-sm font-semibold text-black leading-none">
                                                    {{ ($payment->amount ?? '0.00') . ' ' . $payment->currency }}
                                                </span>
                                            </div>
                                        </td>
                                        <td class="px-8 py-5">
                                            <div class="flex items-center justify-center">
                                                <button wire:click="downloadInvoice({{ $payment->id }})"
                                                    class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-all group-hover:scale-110">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                        height="24" viewBox="0 0 24 24" fill="none"
                                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        class="lucide lucide-download w-5 h-5">
                                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                                        <polyline points="7 10 12 15 17 10"></polyline>
                                                        <line x1="12" x2="12" y1="15"
                                                            y2="3"></line>
                                                    </svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr class="">
                                        <td class="px-5 p-3 text-gray-700 dark:text-white whitespace-nowrap">
                                            No transactions found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>

                            {{-- Second Row --}}
                            {{-- <tbody>
                                    @forelse ($credits as $credit)
                                        <tr
                                            class="border-b border-gray-100 hover:bg-gray-50/50 transition-all group bg-white">
                                            <td class="px-8 py-5"><span
                                                    class="text-sm text-gray-700 font-medium">{{ $payment->created_at_formatted }}</span>
                                            </td>
                                            <td class="px-8 py-5"><span
                                                    class="text-sm text-gray-700">{{ $payment->notes ?? 'N/A' }}</span>
                                            </td>

                                            <td class="px-8 py-5"><span class="text-sm text-gray-700">{{ $payment->order->source->name ?? 'N/A' }}   </span><span
                                                    class="text-sm text-gray-600">Credits</span></td>

                                            <td class="px-5 p-3 text-gray-700 dark:text-white whitespace-nowrap">
                                                @if ($payment->order->type == App\Models\Order::TYPE_PLAN)
                                                    {{ $payment->order->source->name ?? 'N/A' }} Plan Subscription
                                                @else
                                                    <div class="flex items-center gap-1">
                                                        <span
                                                            class="flex items-center gap-1 font-semibold text-orange-500">
                                                            <svg class="w-8 h-" width="26" height="18"
                                                                viewBox="0 0 26 18" fill="none"
                                                                xmlns="http://www.w3.org/2000/svg">
                                                                <rect x="1" y="1" width="24" height="16"
                                                                    rx="3" fill="none"
                                                                    stroke="currentColor" stroke-width="2" />
                                                                <circle cx="8" cy="9" r="3"
                                                                    fill="none" stroke="currentColor"
                                                                    stroke-width="2" />
                                                            </svg>
                                                            {{ number_format($payment->order->credits) }}
                                                        </span>
                                                        <span>Credits</span>
                                                    </div>
                                                @endif
                                            </td>
                                            <td
                                                class="px-5 p-3 text-gray-700 dark:text-gray-100 whitespace-nowrap font-semibold">
                                                {{ ($payment->amount ?? '0.00') . ' ' . $payment->currency }}
                                            </td>

                                            <td class="px-8 py-5">
                                                <div class="flex items-center gap-2">
                                                    <div
                                                        class="w-6 h-6 rounded bg-gradient-to-br from-orange-500 to-orange-600 flex items-center justify-center flex-shrink-0">
                                                        <div class="w-3 h-3 rounded-sm border-2 border-white"></div>
                                                    </div>
                                                    <span
                                                        class="text-sm font-semibold text-orange-600">{{ ($payment->amount ?? '0.00') . ' ' . $payment->currency }}</span>
                                                </div>
                                            </td>

                                            <td class="px-8 py-5 text-right"><span
                                                    class="text-sm font-semibold text-gray-900">{{ $credit->total }}</span>
                                            </td>
                                            <td class="px-8 py-5">
                                                <div class="flex items-center justify-center">
                                                    <button
                                                        class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-all group-hover:scale-110">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                            height="24" viewBox="0 0 24 24" fill="none"
                                                            stroke="currentColor" stroke-width="2"
                                                            stroke-linecap="round" stroke-linejoin="round"
                                                            class="lucide lucide-download w-5 h-5">
                                                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                                            <polyline points="7 10 12 15 17 10"></polyline>
                                                            <line x1="12" x2="12" y1="15"
                                                                y2="3"></line>
                                                        </svg>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr class="">
                                            <td class="px-5 p-3 text-gray-700 dark:text-white whitespace-nowrap">
                                                No Credit found.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody> --}}
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    <div x-data="{ confirmModal: @entangle('confirmModal').live }" x-show="confirmModal" x-cloak x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4 z-50">

        <div
            class="w-full max-w-96 mx-auto rounded-2xl shadow-2xl bg-white dark:bg-slate-800 border border-gray-200 dark:border-gray-700 flex flex-col max-h-[80vh] overflow-hidden">

            {{-- HEADER --}}
            <div
                class="flex justify-between items-center p-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-orange-50 to-orange-100 dark:from-orange-900/20 dark:to-orange-800/20">
                <div class="flex items-center gap-3">
                    <div>
                        @if (app_setting('favicon') && app_setting('favicon_dark'))
                            <img src="{{ storage_url(app_setting('favicon')) }}" alt="{{ config('app.name') }}"
                                class="w-12 dark:hidden" />
                            <img src="{{ storage_url(app_setting('favicon_dark')) }}" alt="{{ config('app.name') }}"
                                class="w-12 hidden dark:block" />
                        @else
                            <img src="{{ asset('assets/favicons/fav icon 1.svg') }}" alt="{{ config('app.name') }}"
                                class="w-12 dark:hidden" />
                            <img src="{{ asset('assets/favicons/fav icon 2 (1).svg') }}"
                                alt="{{ config('app.name') }}" class="w-12 hidden dark:block" />
                        @endif
                    </div>
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">
                        {{ __('Delete Your Account') }}
                    </h2>
                </div>
                <button x-on:click="confirmModal = false"
                    class="w-10 h-10 rounded-xl bg-white dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-all duration-200 flex items-center justify-center border border-gray-200 dark:border-gray-600">
                    <x-heroicon-s-x-mark class="w-5 h-5" />
                </button>
            </div>

            @if ($confirmModal)
                <div class=" p-6">
                    <p class="text-gray-600 dark:text-gray-300 mb-6 text-center">
                        Are you sure you want to delete your account?
                    </p>

                    <div class="flex justify-center">
                        <x-gbutton wire:click="deleteAccount" variant="primary">Yes, Delete Account</x-gbutton>
                    </div>
                </div>
            @endif
        </div>
    </div>
</section>
