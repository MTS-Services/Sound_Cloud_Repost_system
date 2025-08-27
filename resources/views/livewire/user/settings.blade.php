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

    <div x-data="{ open: true, activeTab: 'edit', isGenreDropdownOpen: false }" class="">
        @if (!user()->email_verified_at)
            <div x-show="open" x-transition.opacity.duration.300ms
                class=" top-0  mb-8 max-w-8xl mx-auto  bg-gray-50 dark:bg-gray-800 border-l-4 border-orange-500 text-black dark:text-white  p-4 shadow-sm flex items-center justify-center z-50 rounded-md"
                role="alert">
                <div class="flex flex-col items-center text-center">
                    <p class="text-sm text-gray-600 dark:text-gray-300">
                        Please confirm your email address to unlock core platform features.
                        <a href="{{ route('user.email.resend.verification') }}" class="font-semibold text-orange-600 hover:underline">Confirm email</a>
                    </p>
                    <button><x-lucide-x class="absolute top-4 right-4 w-4 h-4" @click="open = false" /></button>
                </div>
            </div>
        @endif


        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg max-w-8xl mx-auto overflow-hidden">
            <div class="border-b border-gray-200 dark:border-gray-700">
                <div class="flex text-gray-600 font-medium">
                    <button @click="activeTab = 'edit'"
                        :class="{ 'border-b-2 border-orange-500 text-orange-500': activeTab === 'edit', 'hover:bg-gray-50': activeTab !== 'edit' }"
                        class="py-4 px-6 relative tab-link focus:outline-none transition-colors duration-200 ease-in-out">
                        Edit profile
                        <span :class="{ 'hidden': activeTab !== 'edit' }" class="tab-indicator"></span>
                    </button>
                    <button @click="activeTab = 'notifications'"
                        :class="{
                            'border-b-2 border-orange-500 text-orange-500': activeTab === 'notifications',
                            'hover:bg-gray-50': activeTab !== 'notifications'
                        }"
                        class="py-4 px-6 relative tab-link focus:outline-none transition-colors duration-200 ease-in-out">
                        Notifications & alerts
                        <span :class="{ 'hidden': activeTab !== 'notifications' }" class="tab-indicator"></span>
                    </button>
                    <button @click="activeTab = 'settings'"
                        :class="{
                            'border-b-2 border-orange-500 text-orange-500': activeTab === 'settings',
                            'hover:bg-gray-50 text-gray-600': activeTab !== 'settings'
                        }"
                        class="py-4 px-6 relative tab-link focus:outline-none transition-colors duration-200 ease-in-out">
                        Settings
                        <span :class="{ 'hidden': activeTab !== 'settings' }" class="tab-indicator"></span>
                    </button>
                    <button @click="activeTab = (activeTab === 'credit' ? '' : 'credit')"
                        :class="{
                            'border-b-2 border-orange-500 text-orange-500': activeTab === 'credit',
                            'hover:bg-gray-50': activeTab !== 'credit'
                        }"
                        class="py-4 px-6 relative tab-link focus:outline-none transition-colors duration-200 ease-in-out">
                        Credit history
                        <span :class="{ 'hidden': activeTab !== 'credit' }" class="tab-indicator"></span>
                    </button>
                    <button @click="activeTab = (activeTab === 'invoices' ? '' : 'invoices')"
                        :class="{
                            'border-b-2 border-orange-500 text-orange-500': activeTab === 'invoices',
                            'hover:bg-gray-50': activeTab !== 'invoices'
                        }"
                        class="py-4 px-6 relative tab-link focus:outline-none transition-colors duration-200 ease-in-out">
                        Invoices
                        <span :class="{ 'hidden': activeTab !== 'invoices' }" class="tab-indicator"></span>
                    </button>
                </div>
            </div>

            <div x-show="activeTab === 'edit'" x-cloak>
                <!-- Edit Profile -->
                <div class="p-6">
                    <div x-data="{ open: true }" x-show="open"
                        class="bg-blue-50 dark:bg-gray-700 border-l-4 border-l-orange-500 p-4 rounded-md mb-6 flex items-center gap-4">
                        <!-- Icon + Quick Tip -->
                        <div class="flex items-center gap-2 flex-shrink-0">
                            <!-- Light bulb icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-400" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 2a7 7 0 00-7 7c0 3.866 3.134 7 7 7s7-3.134 7-7a7 7 0 00-7-7zM12 14v4m-4-4h8" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 18h0v1m0 2h0v1" />
                            </svg>
                            <span class="font-bold text-gray-800 dark:text-gray-200">Quick Tip</span>
                        </div>


                        <!-- Description -->
                        <p class="text-sm text-gray-700 dark:text-gray-300 flex-1">
                            Customise your genres to personalise your Repostchaine experience.
                        </p>

                        <!-- Close button -->
                        <button
                            class="transition-colors text-gray-500 dark:text-gray-400 hover:text-gray-700 flex-shrink-0"
                            @click="open = false">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    @if (session()->has('success'))
                        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 2000)" x-show="show" x-transition
                            class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mt-4 mb-4"
                            role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @elseif (session()->has('error'))
                        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show" x-transition
                            class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mt-4 mb-4"
                            role="alert">
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif
                    <form wire:submit.prevent="saveProfile">
                        <div class="w-full lg:w-1/2">
                            <!-- Email -->
                            <div class="mb-6">
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                <input type="email" id="email" wire:model="email"
                                    class="mt-1 block max-w-md w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm outline-none">
                                @if (!user()->email_verified_at)
                                    <p class="mt-1 text-xs text-red-500">
                                        Email not verified.
                                        <a wire:navigate href="#" class="font-semibold hover:underline">Resend
                                            confirmation
                                            email</a>
                                    </p>
                                @endif
                            </div>

                            <!-- Genres -->
                            <div class="space-y-4">
                                <!-- Header -->
                                <div class="border-b border-gray-200 pb-2">
                                    <h3 class="text-sm font-medium text-gray-700">Genres</h3>
                                </div>

                                <!-- Selected Genres Tags -->
                                <div class="relative">
                                    <div
                                        class="flex flex-wrap gap-2 mb-2 min-h-[40px] items-center border border-gray-300 rounded px-3 py-2">
                                        @foreach ($selectedGenres as $genre)
                                            <span
                                                class="inline-flex items-center gap-1 px-2 py-1 text-sm bg-gray-100 text-gray-700 rounded border">
                                                {{ $genre }}
                                                <button type="button" wire:click="removeGenre('{{ $genre }}')"
                                                    class="text-gray-500 hover:text-gray-700 text-sm font-bold ml-1">
                                                    <x-lucide-x class="w-4 h-4" />
                                                </button>
                                            </span>
                                        @endforeach

                                        <!-- Search Input -->
                                        <input type="text" wire:model.live="searchTerm" x-ref="searchInput"
                                            x-on:click="isGenreDropdownOpen = true"
                                            x-on:input="isGenreDropdownOpen = true"
                                            placeholder="{{ count($selectedGenres) === 0 ? 'Search genres...' : '' }}"
                                            class="flex-1 outline-none focus:ring-0 border-none text-sm text-gray-700 placeholder-gray-400 min-w-[100px]"
                                            @if (count($selectedGenres) >= $maxGenres) disabled @endif>

                                        <!-- Search Icon -->
                                        <svg class="w-4 h-4 text-gray-400 ml-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                        </svg>
                                    </div>

                                    <!-- Genre Limit Error -->
                                    @if ($genreLimitError)
                                        <div class="flex items-center mt-2 text-orange-600">
                                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                            <span class="text-sm">You can only choose up to {{ $maxGenres }} genres
                                                for your profile</span>

                                        </div>
                                    @elseif ($genreCountError)
                                        <div class="flex items-center mt-2 text-orange-600">
                                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                            <span class="text-sm">Please select at least {{ $minGenres }}
                                                genres</span>
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
                                        class="absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded-md shadow-lg max-h-60 overflow-y-auto">

                                        @if (count($this->filteredGenres) > 0)
                                            @foreach ($this->filteredGenres as $genre)
                                                <button type="button" wire:click="addGenre('{{ $genre }}')"
                                                    class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:bg-gray-100 focus:outline-none"
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


                            <!-- Artist link -->
                            <div class="bg-white dark:bg-gray-900 font-sans text-gray-800 dark:text-gray-200">

                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                                    Connect social accounts (will be promoted after someone reposts one of your tracks
                                    if
                                    you have a Pro Plan)
                                </p>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <!-- Instagram -->
                                    <div>
                                        <div
                                            class="flex items-center px-2 rounded-md shadow-sm border border-gray-200 shadow-sm border border-gray-200">
                                            <div
                                                class="w-6 h-6 bg-gradient-to-br from-purple-500 to-pink-500 rounded-md flex items-center justify-center">
                                                <svg class="w-4 h-4 text-white" fill="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path
                                                        d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" />
                                                </svg>
                                            </div>
                                            <span class="text-gray-600 ms-3">@</span>
                                            <input type="text" placeholder="username"
                                                wire:model="instagram_username" value="{{ $instagram_username }}"
                                                class="flex-grow bg-transparent border-none focus:outline-none focus:ring-0 placeholder-gray-500 dark:placeholder-gray-400 ps-0" />
                                        </div>
                                        @error('instagram_username')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror

                                    </div>
                                    <!-- Twitter -->
                                    <div>
                                        <div
                                            class="flex items-center px-2 rounded-md shadow-sm border border-gray-200">
                                            <div
                                                class="w-6 h-6 bg-blue-400 rounded-md flex items-center justify-center">
                                                <svg class="w-4 h-4 text-white" fill="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path
                                                        d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z" />
                                                </svg>
                                            </div>
                                            <span class="text-gray-600 ms-3">@</span>
                                            <input type="text" placeholder="username"
                                                wire:model="twitter_username" {{ $twitter_username }}
                                                class="flex-grow bg-transparent border-none focus:outline-none focus:ring-0 placeholder-gray-500 dark:placeholder-gray-400 ps-0" />
                                        </div>
                                        @error('twitter_username')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Facebook -->
                                    <div>
                                        <div
                                            class="flex items-center px-2 rounded-md shadow-sm border border-gray-200">
                                            <div
                                                class="w-6 h-6 bg-blue-600 rounded-md flex items-center justify-center">
                                                <svg class="w-4 h-4 text-white" fill="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path
                                                        d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                                                </svg>
                                            </div>
                                            <span class="text-gray-600 ms-3">/</span>
                                            <input type="text" placeholder="pagelink"
                                                wire:model="facebook_username" {{ $facebook_username }}
                                                class="flex-grow bg-transparent border-none focus:outline-none focus:ring-0 placeholder-gray-500 dark:placeholder-gray-400 ps-0" />
                                        </div>
                                        @error('facebook_username')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- YouTube -->
                                    <div>
                                        <div
                                            class="flex items-center px-2 rounded-md shadow-sm border border-gray-200">
                                            <div
                                                class="w-6 h-6 bg-red-600 rounded-md flex items-center justify-center">
                                                <svg class="w-4 h-4 text-white" fill="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path
                                                        d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z" />
                                                </svg>
                                            </div>
                                            <input type="text" placeholder="Channel ID"
                                                value="{{ $youtube_channel_id }}" wire:model="youtube_channel_id"
                                                class="flex-grow bg-transparent border-none focus:outline-none focus:ring-0 placeholder-gray-500 dark:placeholder-gray-400" />
                                        </div>
                                        @error('youtube_channel_id')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <!-- TikTok -->
                                    <div>
                                        <div
                                            class="flex items-center px-2 rounded-md shadow-sm border border-gray-200">
                                            <div class="w-6 h-6 bg-black rounded-md flex items-center justify-center">
                                                <svg class="w-4 h-4 text-white" fill="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path
                                                        d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z" />
                                                </svg>
                                            </div>
                                            <span class="text-gray-600 ms-3">@</span>
                                            <input type="text" placeholder="username" wire:model="tiktok_username"
                                                value="{{ $tiktok_username }}"
                                                class="flex-grow bg-transparent border-none focus:outline-none focus:ring-0 placeholder-gray-500 dark:placeholder-gray-400 ps-0" />
                                        </div>
                                        @error('tiktok_username')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <!-- Spotify -->
                                <div>
                                    <div
                                        class="flex items-center px-2 rounded-md shadow-sm border border-gray-200 mt-3">
                                        <div class="w-6 h-6 bg-green-500 rounded-md flex items-center justify-center">
                                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    d="M12 0C5.4 0 0 5.4 0 12s5.4 12 12 12 12-5.4 12-12S18.66 0 12 0zm5.521 17.34c-.24.359-.66.48-1.021.24-2.82-1.74-6.36-2.101-10.561-1.141-.418.122-.779-.179-.899-.539-.12-.421.18-.78.54-.9 4.56-1.021 8.52-.6 11.64 1.32.42.18.479.659.301 1.02zm1.44-3.3c-.301.42-.841.6-1.262.3-3.239-1.98-8.159-2.58-11.939-1.38-.479.12-1.02-.12-1.14-.6-.12-.48.12-1.021.6-1.141C9.6 9.9 15 10.561 18.72 12.84c.361.181.54.78.241 1.2zm.12-3.36C15.24 8.4 8.82 8.16 5.16 9.301c-.6.179-1.2-.181-1.38-.721-.18-.601.18-1.2.72-1.381 4.26-1.26 11.28-1.02 15.721 1.621.539.3.719 1.02.419 1.56-.299.421-1.02.599-1.559.3z" />
                                            </svg>
                                        </div>
                                        <input type="text" wire:model="spotify_artist_link"
                                            value="{{ $spotify_artist_link }}"
                                            placeholder="Artist link (https://open.spotify.com/artist/...)"
                                            class="flex-grow bg-transparent border-none focus:outline-none focus:ring-0 placeholder-gray-500 dark:placeholder-gray-400" />
                                    </div>
                                    @error('spotify_artist_link')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="mt-8 flex justify-end space-x-4  border-t border-gray-200 pt-4">
                            <x-gbutton variant="secondary">Cancel</x-gbutton>
                            <x-gbutton type="submit" variant="primary">Save Profile</x-gbutton>
                        </div>
                    </form>




                    <!-- Small content below buttons -->
                    <div
                        class="border-t border-gray-300 dark:border-gray-600 mt-4 pt-2 text-sm text-gray-500 dark:text-gray-400">
                        Looking to delete your account?<a href="javascript:void(0)" wire:click="deleteConfirmation"
                            class="font-semibold hover:underline">Click
                            <span class="text-red-500"> here</a>.
                    </div>
                </div>
            </div>
            <!-- Notifications -->
            <div x-show="activeTab === 'notifications'" class="max-w-8xl w-full bg-white rounded-lg shadow-lg p-6">
                <h2 class="text-2xl font-bold text-gray-800">Notifications & Alerts</h2>
                <p class="mt-4 text-gray-600">Manage your email and push notification preferences.</p>

                @if (session()->has('message'))
                    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 2000)" x-show="show" x-transition
                        class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mt-4 mb-4"
                        role="alert">
                        <span class="block sm:inline">{{ session('message') }}</span>
                    </div>
                @elseif (session()->has('error'))
                    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show" x-transition
                        class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mt-4 mb-4"
                        role="alert">
                        <span class="block sm:inline">{{ session('error') }}</span>
                    </div>
                @endif


                <div class="flex justify-between items-center border-b border-gray-200 pb-4 mt-6">
                    <!-- Top "Alerts" Section -->
                    <div class="w-full">
                        <h1 class="text-xl font-semibold text-gray-800">Alerts</h1>
                    </div>
                    <!-- Alerts Table Headers -->
                    <div class="min-w-28 flex justify-between items-center">
                        <div class="">Email</div>
                        <div class="">Push</div>
                    </div>
                </div>

                <!-- Push Notification Disabled Warning -->
                <div
                    class="flex items-start bg-light-orange border-l-4 border-l-orange-400 p-3 rounded-md shadow-md mb-2">
                    <svg class="h-6 w-6 text-white bg-orange-400 rounded-full mr-3 mt-1 flex-shrink-0" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0
        11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="text-sm font-medium text-dark-gray">
                        Your push notifications are disabled because you haven't installed the mobile app
                    </span>
                </div>

                <!-- Alerts List -->
                <form wire:submit.prevent="createOrUpdate">
                    @foreach ($this->alerts as $alert)
                        <div class="flex items-center py-3">
                            <div class="w-full text-sm text-gray-800">{{ $alert['name'] }}</div>
                            <div class="min-w-28 flex justify-between items-center">
                                <div class="">
                                    <input type="checkbox" wire:model="{{ $alert['email_key'] }}"
                                        class="form-checkbox h-5 w-5 rounded border-gray-300 text-primary-orange focus:ring-primary-orange">
                                </div>
                                <div class="">
                                    {{-- @dd($em_feedback_rated) --}}
                                    <input type="checkbox" wire:model="{{ $alert['push_key'] }}"
                                        class="form-checkbox h-5 w-5 rounded border-gray-300 text-gray-400 focus:ring-gray-400">
                                </div>
                            </div>
                        </div>
                    @endforeach
                    <!-- Action Buttons -->
                    <div class="mt-8 flex justify-end space-x-4 border-t border-gray-200 pt-4">
                        <x-gbutton type="button" variant="secondary" wire:click="loadSettings">Cancel</x-gbutton>
                        <x-gbutton type="submit" variant="primary">Save Profile</x-gbutton>
                    </div>
                </form>
            </div>
            <!-- Settings Section -->
            <div x-show="activeTab === 'settings'" x-cloak>
                <div class="w-full max-w-8xl bg-white rounded-lg shadow-lg p-6 ">
                    <!-- Section: My Requests -->
                    <div class="border-b border-gray-200 pb-4 mb-6">
                        <h1 class="text-xl font-semibold text-gray-800">My requests</h1>
                    </div>

                    <div class="space-y-6">
                        <!-- Accept Direct Requests -->
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-2">
                            <div>
                                <p class="text-gray-700">Accept Direct repost requests</p>
                                <p class="text-sm text-red-500">You must confirm your email address to accept direct
                                    repost
                                    requests</p>
                            </div>
                            <div class="flex items-center gap-6">
                                <label class="flex items-center gap-1">
                                    <input type="radio" name="acceptRequests" value="true"
                                        class="text-orange-500">
                                    <span class="text-gray-600">Yes</span>
                                </label>
                                <label class="flex items-center gap-1">
                                    <input type="radio" name="acceptRequests" value="false" checked
                                        class="text-orange-500">
                                    <span class="text-gray-600">No</span>
                                </label>
                            </div>
                        </div>

                        <!-- Block Requests -->
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-2">
                            <div>
                                <p class="text-gray-700">Block direct repost requests for tracks which do not match my
                                    profile genres</p>
                            </div>
                            <div class="flex items-center gap-6">
                                <label class="flex items-center gap-1">
                                    <input type="radio" name="blockRequests" value="true"
                                        class="text-orange-500">
                                    <span class="text-gray-600">Yes</span>
                                </label>
                                <label class="flex items-center gap-1">
                                    <input type="radio" name="blockRequests" value="false" checked
                                        class="text-orange-500">
                                    <span class="text-gray-600">No</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Features -->
                    <div class="border-b border-gray-200 py-6 mb-6 mt-6">
                        <h1 class="text-xl font-semibold text-gray-800">Additional features</h1>
                    </div>

                    <div class="space-y-6">
                        <!-- Mystery Box -->
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-2">
                            <p class="text-gray-700">Opt In to Mystery Box Draws</p>
                            <div class="flex items-center gap-6">
                                <label class="flex items-center gap-1">
                                    <input type="radio" name="mysteryBox" value="true" checked
                                        class="text-orange-500">
                                    <span class="text-gray-600">Yes</span>
                                </label>
                                <label class="flex items-center gap-1">
                                    <input type="radio" name="mysteryBox" value="false" class="text-orange-500">
                                    <span class="text-gray-600">No</span>
                                </label>
                            </div>
                        </div>

                        <!-- Auto Boost -->
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-2">
                            <div>
                                <p class="text-gray-700">Auto Free Boost <span
                                        class="text-gray-400 text-sm">(i)</span>
                                </p>
                            </div>
                            <div class="flex items-center gap-6">
                                <label class="flex items-center gap-1">
                                    <input type="radio" name="autoBoost" value="true" class="text-orange-500">
                                    <span class="text-gray-600">Yes</span>
                                </label>
                                <label class="flex items-center gap-1">
                                    <input type="radio" name="autoBoost" value="false" checked
                                        class="text-orange-500">
                                    <span class="text-gray-600">No</span>
                                </label>
                            </div>
                        </div>

                        <!-- Reactions -->
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-2">
                            <p class="text-gray-700">Enable Reactions</p>
                            <div class="flex items-center gap-6">
                                <label class="flex items-center gap-1">
                                    <input type="radio" name="reactions" value="true" checked
                                        class="text-orange-500">
                                    <span class="text-gray-600">Yes</span>
                                </label>
                                <label class="flex items-center gap-1">
                                    <input type="radio" name="reactions" value="false" class="text-orange-500">
                                    <span class="text-gray-600">No</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Subscription -->
                    <div class="border-b border-gray-200 py-6 mb-6 mt-6">
                        <h1 class="text-xl font-semibold text-gray-800">Subscription</h1>
                        <div class="mt-2 text-sm text-gray-600">
                            <p class="text-gray-700">Free Forever Plan <span
                                    class="text-orange-500 cursor-pointer">Change</span></p>
                        </div>
                    </div>

                    <div class="mt-8 flex justify-end space-x-4">
                        <x-gbutton variant="secondary">Cancel</x-gbutton>
                        <x-gbutton type="submit" variant="primary">Save Profile</x-gbutton>
                    </div>

                </div>
            </div>

            <!-- Credit History Table -->
            <div x-show="activeTab === 'credit'" class="overflow-x-auto w-full p-6" x-transition>
                {{-- <!-- Header -->
                <div class="flex items-center gap-3 mb-6">
                    <div class="p-2 bg-blue-100 text-blue-600 rounded-xl">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3-.895 3-2-1.343-2-3-2z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 14c-3.866 0-7 1.343-7 3v2h14v-2c0-1.657-3.134-3-7-3z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">Credit History</h2>
                        <p class="text-gray-500 text-sm">Track your earned and spent credits</p>
                    </div>
                </div> --}}

                <!-- Card Table -->
                <div class="overflow-hidden rounded-lg border border-gray-200 shadow-sm">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-5 p-3 font-medium text-gray-600">Date</th>
                                <th class="px-5 p-3 font-medium text-gray-600">Description</th>
                                <th class="px-5 p-3 font-medium text-gray-600">Credits</th>
                                <th class="px-5 p-3 font-medium text-gray-600">Balance</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($credits as $credit)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-5 p-3 text-gray-700 whitespace-nowrap">
                                        {{ $credit->created_at_formatted }}
                                    </td>
                                    <td class="px-5 p-3 text-gray-700 whitespace-nowrap">
                                        {{ $credit->description }}
                                    </td>
                                    <td
                                        class="px-5 p-3 text-orange-500 font-semibold flex items-center gap-1 whitespace-nowrap">
                                        <svg class="w-8 h-" width="26" height="18" viewBox="0 0 26 18"
                                            fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <rect x="1" y="1" width="24" height="16" rx="3"
                                                fill="none" stroke="currentColor" stroke-width="2" />
                                            <circle cx="8" cy="9" r="3" fill="none"
                                                stroke="currentColor" stroke-width="2" />
                                        </svg>
                                        {{ $credit->credits }}
                                    </td>
                                    <td class="px-5 p-3 text-gray-800 font-medium whitespace-nowrap">
                                        {{ $credit->amount }}
                                    </td>
                                </tr>
                            @empty
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!--invoices Section -->
            <div x-show="activeTab === 'invoices'" class="overflow-x-auto w-full p-6" x-transition>
                <!-- Header -->
                {{-- <div class="flex items-center gap-3 mb-6">
                    <div class="p-2 bg-blue-100 text-blue-600 rounded-xl">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3-.895 3-2-1.343-2-3-2z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 14c-3.866 0-7 1.343-7 3v2h14v-2c0-1.657-3.134-3-7-3z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">Invoices</h2>
                        <p class="text-gray-500 text-sm"></p>
                    </div>
                </div> --}}

                <!-- Card Table -->
                <div class="overflow-hidden rounded-lg border border-gray-200 shadow-sm">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-5 p-3 font-medium text-gray-600">Date</th>
                                <th class="px-5 p-3 font-medium text-gray-600">Description</th>
                                <th class="px-5 p-3 font-medium text-gray-600">Total</th>
                                <th class="px-5 p-3 font-medium text-gray-600">Invoice</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            {{-- <tr class="hover:bg-gray-50 transition">
                                <td class="px-5 p-3 text-gray-700 whitespace-nowrap">
                                    25 Aug 2025 08:41 AM
                                </td>
                                <td class="px-5 p-3 text-gray-700 whitespace-nowrap">
                                    Free credits on sign up
                                </td>
                                <td
                                    class="px-5 p-3 text-orange-500 font-semibold flex items-center gap-1 whitespace-nowrap">
                                    <svg class="w-8 h-" width="26" height="18" viewBox="0 0 26 18"
                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <rect x="1" y="1" width="24" height="16" rx="3"
                                            fill="none" stroke="currentColor" stroke-width="2" />
                                        <circle cx="8" cy="9" r="3" fill="none"
                                            stroke="currentColor" stroke-width="2" />
                                    </svg>
                                    +30
                                </td>
                                <td class="px-5 p-3 text-gray-800 font-medium whitespace-nowrap">
                                    30
                                </td>
                            </tr> --}}
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-5 p-3 text-gray-700 whitespace-nowrap">
                                    No transactions found.
                                </td>
                            </tr>
                        </tbody>
                    </table>
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
                    <div class="w-7 h-7 md:w-8 md:h-8 bg-orange-500 rounded-lg flex items-center justify-center">
                        <span class="text-slate-800 dark:text-white font-bold text-md md:text-lg">R</span>
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
