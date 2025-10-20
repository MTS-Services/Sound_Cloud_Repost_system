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
                <div class="p-6">
                    {{-- <div x-data="{ open: true }" x-show="open"
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
                            <x-lucide-x class="w-5 h-5" />
                        </button>
                    </div> --}}

                    <div x-data="{ open: true }" x-show="open"
                        class="mb-4 m-4 relative overflow-hidden bg-gradient-to-br from-orange-500 via-orange-400 to-amber-400 p-3 rounded-xl shadow-lg">
                        <div class="absolute top-0 right-0 w-24 h-24 bg-white/10 rounded-full -mr-12 -mt-12">
                        </div>
                        <div class="absolute bottom-0 left-0 w-20 h-20 bg-white/10 rounded-full -ml-10 -mb-10">
                        </div>
                        <div class="relative flex items-center gap-3">
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
                            <div class="flex-1">
                                <div class="flex items-center justify-between gap-4">
                                    <div>
                                        <h4 class="font-bold text-white mb-0.5 text-sm">Quick Tip</h4>
                                        <p class="text-white/95 text-xs leading-relaxed">Customise your genres to
                                            personalise your Repostchaine experience.</p>
                                    </div><button @click="open = false"
                                        class="flex-shrink-0 p-1 text-white/80 hover:text-white hover:bg-white/20 rounded-lg transition-all"><svg
                                            xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="lucide lucide-x w-4 h-4">
                                            <path d="M18 6 6 18"></path>
                                            <path d="m6 6 12 12"></path>
                                        </svg></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <form wire:submit.prevent="saveProfile">
                        <div class="w-full ">
                            <!-- Email -->
                            {{-- <div class="mb-6">
                                <label for="email"
                                    class="block text-sm font-semibold text-gray-700 dark:text-white mb-1">Email
                                    Address</label>
                                <input type="email" id="email" wire:model="email"
                                    class="mt-1 block max-w-md w-full rounded-md bg-gray-50 dark:bg-gray-700 dark:text-gray-300 border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm outline-none">
                                @if (!user()->email_verified_at)
                                    <div class="flex items-center gap-1">
                                        <p class="mt-1 text-xs text-red-500">
                                            Email not verified, please verify your email.
                                        </p>
                                    </div>
                                @endif
                            </div> --}}

                            <div
                                class="bg-gradient-to-br from-gray-50 to-white p-4 m-4 rounded-xl border border-gray-100 shadow-sm">
                                <label class="block text-sm font-bold text-gray-900 mb-1.5">Email Address</label>
                                <input type="email"
                                    class="w-full px-3 py-2.5 bg-white border-2 border-gray-200 rounded-lg text-gray-900 text-sm focus:outline-none focus:ring-4 focus:ring-orange-500/20 focus:border-orange-500 transition-all placeholder:text-gray-400"
                                    placeholder="your.email@example.com" value="dilip.udaya1219@gmail.com">
                                @if (!user()->email_verified_at)
                                    <div class="flex items-center gap-1">
                                        <p class="mt-1 text-xs text-red-500">
                                            Email not verified, please verify your email.
                                        </p>
                                    </div>
                                @endif
                            </div>

                            <!-- Genres -->
                            <div class="space-y-4">
                                <!-- Header -->

                                {{-- <div class="border-b border-gray-200 dark:border-gray-700 pb-2">
                                    <h3 class="text-sm font-semibold text-gray-700 dark:text-white">Your Music Genres
                                    </h3>
                                </div> --}}

                                <div
                                    class="bg-gradient-to-br from-gray-50 to-white m-4 p-4 rounded-xl border border-gray-100 shadow-sm">
                                    <label class="block text-sm font-bold text-gray-900 mb-1.5">
                                        Your Music Genres
                                    </label>

                                    <div class="relative">
                                        <div
                                            class="flex flex-wrap gap-2 p-3 bg-white border-2 border-gray-200 rounded-lg min-h-[48px]
                                            focus-within:ring-4 focus-within:ring-orange-500/20 focus-within:border-orange-500 transition-all">
                                            <!-- Tag: Hip-hop & Rap -->
                                            <span
                                                class="group inline-flex items-center gap-1.5 px-2.5 py-1.5 bg-gradient-to-br from-orange-500 to-orange-600
                                            text-white rounded-lg text-xs font-semibold shadow-md hover:shadow-lg hover:scale-105 transition-all">
                                                Hip-hop & Rap
                                                <button class="p-0.5 hover:bg-white/20 rounded-md transition-all">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                        height="24" viewBox="0 0 24 24" fill="none"
                                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round" class="lucide lucide-x w-3 h-3">
                                                        <path d="M18 6 6 18" />
                                                        <path d="m6 6 12 12" />
                                                    </svg>
                                                </button>
                                            </span>

                                            <!-- Tag: Pop -->
                                            <span
                                                class="group inline-flex items-center gap-1.5 px-2.5 py-1.5 bg-gradient-to-br from-orange-500 to-orange-600
                                            text-white rounded-lg text-xs font-semibold shadow-md hover:shadow-lg hover:scale-105 transition-all">
                                                Pop
                                                <button class="p-0.5 hover:bg-white/20 rounded-md transition-all">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                        height="24" viewBox="0 0 24 24" fill="none"
                                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round" class="lucide lucide-x w-3 h-3">
                                                        <path d="M18 6 6 18" />
                                                        <path d="m6 6 12 12" />
                                                    </svg>
                                                </button>
                                            </span>

                                            <!-- Tag: Rock -->
                                            <span
                                                class="group inline-flex items-center gap-1.5 px-2.5 py-1.5 bg-gradient-to-br from-orange-500 to-orange-600
                                            text-white rounded-lg text-xs font-semibold shadow-md hover:shadow-lg hover:scale-105 transition-all">
                                                Rock
                                                <button class="p-0.5 hover:bg-white/20 rounded-md transition-all">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                        height="24" viewBox="0 0 24 24" fill="none"
                                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round" class="lucide lucide-x w-3 h-3">
                                                        <path d="M18 6 6 18" />
                                                        <path d="m6 6 12 12" />
                                                    </svg>
                                                </button>
                                            </span>

                                            <!-- Input Field -->
                                            <input type="#"
                                                class="flex-1 min-w-[140px] outline-none text-gray-900 placeholder:text-gray-400
                                            text-xs font-medium"
                                                placeholder="Type and press Enter..." value="" />
                                        </div>

                                        <!-- Search Icon -->
                                        <div class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-search w-4 h-4 text-gray-400">
                                                <circle cx="11" cy="11" r="8" />
                                                <path d="m21 21-4.3-4.3" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                                <!-- Selected Genres Tags -->
                            </div>


                            <div
                                class="bg-gradient-to-br from-gray-50 to-white p-4 m-4 rounded-xl border border-gray-100 shadow-sm">
                                <!-- Section Header -->
                                <div class="mb-3">
                                    <label class="block text-sm font-bold text-gray-900 mb-0.5">
                                        Social Media Accounts
                                    </label>
                                    <p class="text-xs text-gray-600 leading-relaxed">
                                        Connect your social profiles to get promoted when someone reposts your tracks
                                        (Pro Plan feature)
                                    </p>
                                </div>

                                <!-- Grid Container -->
                                <div class="grid grid-cols-2 gap-2.5">
                                    <!-- Instagram -->
                                    <div
                                        class="group relative bg-white rounded-lg border-2 border-gray-200 overflow-hidden hover:border-pink-300 transition-all">
                                        <div
                                            class="absolute left-0 top-0 bottom-0 w-10 bg-gradient-to-br from-pink-500 to-rose-600 flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-instagram w-4 h-4 text-white">
                                                <rect width="20" height="20" x="2" y="2" rx="5"
                                                    ry="5"></rect>
                                                <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
                                                <line x1="17.5" x2="17.51" y1="6.5" y2="6.5">
                                                </line>
                                            </svg>
                                        </div>
                                        <input type="#"
                                            class="w-full pl-12 pr-3 py-2.5 bg-transparent focus:outline-none text-gray-900 placeholder:text-gray-400 font-medium text-xs"
                                            placeholder="@username" wire:model="instagram_username"
                                            value="{{ $instagram_username }}">


                                    </div>
                                    @error('instagram_username')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror


                                    <!-- Twitter -->
                                    <div
                                        class="group relative bg-white rounded-lg border-2 border-gray-200 overflow-hidden hover:border-blue-300 transition-all">
                                        <div
                                            class="absolute left-0 top-0 bottom-0 w-10 bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-twitter w-4 h-4 text-white">
                                                <path
                                                    d="M22 4s-.7 2.1-2 3.4c1.6 10-9.4 17.3-18 11.6 2.2.1 4.4-.6 6-2C3 15.5.5 9.6 3 5c2.2 2.6 5.6 4.1 9 4-.9-4.2 4-6.6 7-3.8 1.1 0 3-1.2 3-1.2z">
                                                </path>
                                            </svg>
                                        </div>
                                        <input type="#"
                                            class="w-full pl-12 pr-3 py-2.5 bg-transparent focus:outline-none text-gray-900 placeholder:text-gray-400 font-medium text-xs"
                                            placeholder="@username" wire:model="twitter_username"
                                            value="{{ $twitter_username }}">
                                    </div>
                                    @error('twitter_username')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror

                                    <!-- Facebook -->
                                    <div
                                        class="group relative bg-white rounded-lg border-2 border-gray-200 overflow-hidden hover:border-blue-400 transition-all">
                                        <div
                                            class="absolute left-0 top-0 bottom-0 w-10 bg-gradient-to-br from-blue-600 to-blue-800 flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-facebook w-4 h-4 text-white">
                                                <path
                                                    d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z">
                                                </path>
                                            </svg>
                                        </div>
                                        <input type="#"
                                            class="w-full pl-12 pr-3 py-2.5 bg-transparent focus:outline-none text-gray-900 placeholder:text-gray-400 font-medium text-xs"
                                            placeholder="pagelink" wire:model="facebook_username"
                                            {{ $facebook_username }}>
                                    </div>
                                    @error('facebook_username')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror

                                    <!-- YouTube -->
                                    <div
                                        class="group relative bg-white rounded-lg border-2 border-gray-200 overflow-hidden hover:border-red-300 transition-all">
                                        <div
                                            class="absolute left-0 top-0 bottom-0 w-10 bg-gradient-to-br from-red-600 to-red-700 flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-youtube w-4 h-4 text-white">
                                                <path
                                                    d="M2.5 17a24.12 24.12 0 0 1 0-10 2 2 0 0 1 1.4-1.4 49.56 49.56 0 0 1 16.2 0A2 2 0 0 1 21.5 7a24.12 24.12 0 0 1 0 10 2 2 0 0 1-1.4 1.4 49.55 49.55 0 0 1-16.2 0A2 2 0 0 1 2.5 17">
                                                </path>
                                                <path d="m10 15 5-3-5-3z"></path>
                                            </svg>
                                        </div>
                                        <input type="#"
                                            class="w-full pl-12 pr-3 py-2.5 bg-transparent focus:outline-none text-gray-900 placeholder:text-gray-400 font-medium text-xs"
                                            placeholder="Channel ID" value="{{ $youtube_channel_id }}"
                                            wire:model="youtube_channel_id">
                                    </div>
                                    @error('youtube_channel_id')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                    <!-- SoundCloud -->
                                    <div
                                        class="group relative bg-white rounded-lg border-2 border-gray-200 overflow-hidden hover:border-gray-400 transition-all">
                                        <div
                                            class="absolute left-0 top-0 bottom-0 w-10 bg-gradient-to-br from-gray-800 to-black flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-music w-4 h-4 text-white">
                                                <path d="M9 18V5l12-2v13"></path>
                                                <circle cx="6" cy="18" r="3"></circle>
                                                <circle cx="18" cy="16" r="3"></circle>
                                            </svg>
                                        </div>
                                        <input type="#"
                                            class="w-full pl-12 pr-3 py-2.5 bg-transparent focus:outline-none text-gray-900 placeholder:text-gray-400 font-medium text-xs"
                                            placeholder="@username" wire:model="soundcloud_username">
                                    </div>
                                    @error('soundcloud_username')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror

                                    <!-- Spotify (Full width) -->
                                    <div
                                        class="group relative bg-white rounded-lg border-2 border-gray-200 overflow-hidden hover:border-green-300 transition-all col-span-2">
                                        <div
                                            class="absolute left-0 top-0 bottom-0 w-10 bg-gradient-to-br from-green-500 to-green-700 flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-music w-4 h-4 text-white">
                                                <path d="M9 18V5l12-2v13"></path>
                                                <circle cx="6" cy="18" r="3"></circle>
                                                <circle cx="18" cy="16" r="3"></circle>
                                            </svg>
                                        </div>
                                        <input type="#"
                                            class="w-full pl-12 pr-3 py-2.5 bg-transparent focus:outline-none text-gray-900 placeholder:text-gray-400 font-medium text-xs"
                                            wire:model="spotify_artist_link" value="{{ $spotify_artist_link }}"
                                            placeholder="Artist link (https://open.spotify.com/artist/...)">
                                    </div>
                                    @error('spotify_artist_link')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                        </div>
                        <div
                            class="mt-8 flex justify-end space-x-4 border-t border-gray-200 dark:border-gray-700 pt-4">
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
                        class="border-t border-gray-300 dark:border-gray-600 mt-4 pt-2 text-sm text-gray-500 dark:text-gray-400">
                        Looking to delete your account?<a href="javascript:void(0)" wire:click="deleteConfirmation"
                            class="font-semibold hover:underline">Click
                            <span class="text-red-500"> here</a>.
                    </div>
                </div>
            </div>
            <!-- Header -->
            <div x-show="activeTab === 'notifications'" class="mb-8">
                <div class="p-6">
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">
                        Notifications &amp; Alerts
                    </h2>
                    <p class="text-gray-600 mb-8">
                        Manage your email and notification preferences.
                    </p>
                    <!-- Alerts Section -->
                    <div
                        class="bg-gradient-to-br from-gray-50 to-white p-8 rounded-2xl border border-gray-100 shadow-sm">
                        <!-- Title Bar -->
                        <div class="flex items-center justify-between mb-6 pb-6 border-b border-gray-200">
                            <h3 class="text-lg font-bold text-gray-900">Alerts</h3>
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

                            <div
                                class="mt-8 flex justify-end space-x-4 border-t border-gray-200 dark:border-gray-700 pt-4">
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
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Settings</h2>
                    <p class="text-gray-600">Manage your account preferences and features.</p>
                </div>

                <form wire:submit.prevent="settingsUpdate" method="POST">
                    @csrf
                    <div class="space-y-8">
                        <!-- My Requests -->
                        <div
                            class="bg-gradient-to-br from-gray-50 to-white p-8 rounded-2xl border border-gray-100 shadow-sm">
                            <h3 class="text-lg font-bold text-gray-900 mb-6">My requests</h3>

                            <div class="space-y-1">
                                <!-- Accept Direct Repost Requests -->
                                <div
                                    class="flex items-center justify-between p-4 rounded-xl bg-white hover:bg-gray-50/50 transition-all group">
                                    {{-- <span
                                    class="text-sm font-medium text-gray-700 group-hover:text-gray-900 transition-colors">
                                    Accept Direct repost requests
                                </span>
                                @if (!user()->email_verified_at)
                                        <p class="text-sm text-red-500">You must confirm your email address to accept
                                            direct repost requests</p>
                                    @endif --}}
                                    <div>
                                        <p class="text-gray-700 dark:text-white">Accept Direct repost requests</p>
                                        @if (!user()->email_verified_at)
                                            <p class="text-sm text-red-500">You must confirm your email address to
                                                accept
                                                direct repost requests</p>
                                        @endif
                                    </div>

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
                                    {{-- <div class="flex items-center gap-4">
                                        <label class="flex items-center gap-2 cursor-pointer">
                                            <input type="radio" name="acceptRequests"
                                                wire:model="accept_repost"
                                                {{ user()->email_verified_at ? '' : 'disabled' }}
                                                class="w-5 h-5 text-orange-500 {{ user()->email_verified_at ? '' : 'cursor-not-allowed' }} border-gray-300 focus:ring-2 focus:ring-orange-500/20"
                                                checked>
                                            <span class="text-sm font-medium text-gray-700">Yes</span>
                                        </label>
                                        <label class="flex items-center gap-2 cursor-pointer">
                                            <input type="radio" name="acceptRequests"
                                                wire:model="accept_repost"
                                                {{ user()->email_verified_at ? '' : 'disabled' }}
                                                class="w-5 h-5 text-orange-500  border-gray-300 focus:ring-2 focus:ring-orange-500/20 {{ user()->email_verified_at ? '' : 'cursor-not-allowed' }}">
                                            <span class="text-sm font-medium text-gray-700">No</span>
                                        </label>
                                    </div> --}}
                                </div>

                                <!-- Block Non-Matching Genres -->
                                <div
                                    class="flex items-center justify-between p-4 rounded-xl bg-gray-50/30 hover:bg-gray-50/50 transition-all group">
                                    <span
                                        class="text-sm font-medium text-gray-700 group-hover:text-gray-900 transition-colors">
                                        Block direct repost requests for tracks which do not match my profile genres
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
                            class="bg-gradient-to-br from-gray-50 to-white p-8 rounded-2xl border border-gray-100 shadow-sm">
                            <h3 class="text-lg font-bold text-gray-900 mb-6">Additional features</h3>

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
                            class="bg-gradient-to-br from-gray-50 to-white p-8 rounded-2xl border border-gray-100 shadow-sm">
                            <h1 class="text-lg font-bold text-gray-900 mb-4">Subscription</h1>
                            <div class="mflex items-center gap-3">
                                <p class="text-sm text-gray-600">{{ userPlanName() }} Plan <a wire:navigate
                                        href="{{ route('user.plans') }}"
                                        class="text-sm font-semibold text-orange-600 hover:text-orange-700 transition-colors">Change</a>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Footer Buttons -->
                    <div class="mt-8 flex justify-end space-x-4 border-t border-gray-200 dark:border-gray-700 pt-4">
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



            <!-- Credit History Table -->
            <div x-show="activeTab === 'credit'" class="w-full p-6" x-transition>
                <!-- Card Table -->
                <div class="p-6">
                    <div class="mb-8">
                        <h2 class="text-2xl font-bold text-gray-900 mb-2">Credit History</h2>
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
                                    {{-- @forelse ($credits as $credit)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-600 transition">
                                            <td class="px-5 p-3 text-gray-700 dark:text-gray-100 whitespace-nowrap">
                                                {{ $credit->created_at_formatted }}
                                            </td>
                                            <td class="px-5 p-3 text-gray-700 dark:text-gray-100 whitespace-nowrap">
                                                {{ $credit->description }}
                                            </td>
                                            <td class="px-5 p-3 text-gray-700 dark:text-gray-100 whitespace-nowrap">
                                                <span class="badge badge-soft {{ $credit->calculation_type_color }}">
                                                    {{ $credit->calculation_type_name }}
                                                </span>
                                            </td>
                                            <td
                                                class="px-5 p-3 {{ $credit->calculation_type == App\Models\CreditTransaction::CALCULATION_TYPE_CREDIT ? 'text-red-500' : 'text-green-500' }} font-semibold flex items-center gap-1 whitespace-nowrap">
                                                <svg class="w-8 h-" width="26" height="18"
                                                    viewBox="0 0 26 18" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <rect x="1" y="1" width="24" height="16" rx="3"
                                                        fill="none" stroke="currentColor" stroke-width="2" />
                                                    <circle cx="8" cy="9" r="3" fill="none"
                                                        stroke="currentColor" stroke-width="2" />
                                                </svg>
                                                {{ ($credit->calculation_type == App\Models\CreditTransaction::CALCULATION_TYPE_CREDIT ? '-' : '+') . number_format($credit->credits) }}
                                            </td>
                                            <td
                                                class="px-5 p-3 text-gray-800 dark:text-white font-medium whitespace-nowrap">
                                                {{ number_format($credit->balance) }} credits
                                            </td>
                                        </tr>
                                    @empty
                                    @endforelse --}}


                                    @forelse ($credits as $credit)
                                        <tr
                                            class="border-b border-gray-100 hover:bg-gray-50/50 transition-all group bg-white">
                                            <td class="px-8 py-5"><span
                                                    class="text-sm text-gray-700 font-medium">{{ $credit->created_at_formatted }}</span>
                                            </td>
                                            <td class="px-8 py-5"><span
                                                    class="text-sm text-gray-700">{{ $credit->description }}</span>
                                            </td>
                                            <td class="px-8 py-5">
                                                <div class="flex items-center justify-center"><span
                                                        class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-bold border {{ $credit->calculation_type == App\Models\CreditTransaction::CALCULATION_TYPE_CREDIT ? 'bg-red-100 text-red-700 bg-gradient-to-br from-red-100 to-rose-100 border-red-200' : 'bg-red-100 text-red-700 border-red-200' }}">{{ $credit->transaction_type_name }}</span>
                                                </div>
                                            </td>
                                            <td class="px-8 py-5">
                                                <div class="flex items-center justify-center gap-2">
                                                    <div
                                                        class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-red-50 border border-red-200">
                                                        <div
                                                            class="w-5 h-5 rounded flex items-center justify-center flex-shrink-0 bg-gradient-to-br from-red-500 to-red-600">
                                                            <div class="w-2.5 h-2.5 rounded-sm border-2 border-white">
                                                            </div>
                                                        </div><span
                                                            class="text-sm font-bold text-red-700">{{ number_format($credit->amount) }}</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-8 py-5 text-right"><span
                                                    class="text-sm font-semibold text-gray-900">{{ number_format($credit->balance) }}</span>
                                            </td>
                                        </tr>
                                        <tr
                                            class="border-b border-gray-100 hover:bg-gray-50/50 transition-all group bg-gray-50/30">
                                            <td class="px-8 py-5"><span class="text-sm text-gray-700 font-medium">14
                                                    Oct,
                                                    2025 14:21 PM</span></td>
                                            <td class="px-8 py-5"><span class="text-sm text-gray-700">Your repost
                                                    request
                                                    has expired.</span></td>
                                            <td class="px-8 py-5">
                                                {{-- <div class="flex items-center justify-center"><span
                                                        class="inline-flex items-center gap-1 px-3 py-1.5 bg-gradient-to-br from-emerald-100 to-teal-100 text-emerald-700 rounded-lg text-xs font-bold border border-emerald-200">{{ $credit->transaction_type_name }}</span>
                                                </div> --}}
                                                <div class="flex items-center justify-center"><span
                                                        class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-bold border {{ $credit->calculation_type == App\Models\CreditTransaction::CALCULATION_TYPE_CREDIT ? 'bg-red-100 text-red-700 bg-gradient-to-br from-red-100 to-rose-100 border-red-200' : 'bg-green-100 text-green-700 border-emerald-200' }}">{{ $credit->transaction_type_name }}</span>
                                                </div>
                                            </td>
                                            <td class="px-8 py-5">
                                                <div class="flex items-center justify-center gap-2">
                                                    <div
                                                        class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-emerald-50 border border-emerald-200">
                                                        <div
                                                            class="w-5 h-5 rounded flex items-center justify-center flex-shrink-0 bg-gradient-to-br from-emerald-500 to-emerald-600">
                                                            <div class="w-2.5 h-2.5 rounded-sm border-2 border-white">
                                                            </div>
                                                        </div><span
                                                            class="text-sm font-bold text-emerald-700">{{ $credit->amount }}</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-8 py-5 text-right"><span
                                                    class="text-sm font-semibold text-gray-900">{{ $credit->balance }}</span>
                                            </td>
                                        </tr>
                                    @empty
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
                        <h2 class="text-2xl font-bold text-gray-900 mb-2">Invoices</h2>
                        <p class="text-gray-600">View and download your transaction history.</p>
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
                                        <th class="px-8 py-5 text-left text-sm font-bold text-gray-900">Source</th>
                                        <th class="px-8 py-5 text-right text-sm font-bold text-gray-900">Total</th>
                                        <th class="px-8 py-5 text-center text-sm font-bold text-gray-900">Invoice</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                    @forelse ($payments as $payment)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-600 transition">
                                            <td class="px-5 p-3 text-gray-700 dark:text-white whitespace-nowrap">
                                                {{ $payment->created_at_formatted }}
                                            </td>
                                            <td class="px-5 p-3 text-gray-700 dark:text-white whitespace-nowrap">
                                                {{ $payment->notes ?? 'N/A' }}
                                            </td>
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
                                            <td class="px-5 p-3 text-gray-800 font-medium whitespace-nowrap">
                                                <a href="javascript:void(0);"
                                                    wire:click="downloadInvoice({{ $payment->id }})"
                                                    class="text-blue-600 hover:underline">
                                                    <x-lucide-file-down class="w-6 h-6" />
                                                </a>
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
                                <tbody>

                                    <tr
                                        class="border-b border-gray-100 hover:bg-gray-50/50 transition-all group bg-white">
                                        <td class="px-8 py-5"><span class="text-sm text-gray-700 font-medium">04 Oct,
                                                2025 09:33 AM</span></td>
                                        <td class="px-8 py-5"><span class="text-sm text-gray-700">Plan subscription
                                                for Premium Plan</span></td>
                                        {{-- <td class="px-8 py-5"><span class="text-sm text-gray-700">{{ $credit->amount }}</span></td>
                                                <span class="text-sm text-gray-600">Credits</span> --}}
                                        <td class="px-8 py-5">
                                            <div class="flex items-center gap-2">
                                                <div
                                                    class="w-6 h-6 rounded bg-gradient-to-br from-orange-500 to-orange-600 flex items-center justify-center flex-shrink-0">
                                                    <div class="w-3 h-3 rounded-sm border-2 border-white"></div>
                                                </div>
                                                <span
                                                    class="text-sm font-semibold text-orange-600">{{ $credit->amount }}</span>
                                                <span class="text-sm text-gray-600">Credits</span>
                                            </div>
                                        </td>

                                        <td class="px-8 py-5 text-right"><span
                                                class="text-sm font-semibold text-gray-900">{{ number_format($payment->order->credits) }}</span>
                                        </td>
                                        <td class="px-8 py-5">
                                            <div class="flex items-center justify-center">
                                                <button
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
                                    <tr
                                        class="border-b border-gray-100 hover:bg-gray-50/50 transition-all group bg-gray-50/30">
                                        <td class="px-8 py-5"><span class="text-sm text-gray-700 font-medium">04 Oct,
                                                2025 09:01 AM</span></td>
                                        <td class="px-8 py-5"><span class="text-sm text-gray-700">Credit added by
                                                system</span></td>
                                        <td class="px-8 py-5">
                                            <div class="flex items-center gap-2">
                                                <div
                                                    class="w-6 h-6 rounded bg-gradient-to-br from-orange-500 to-orange-600 flex items-center justify-center flex-shrink-0">
                                                    <div class="w-3 h-3 rounded-sm border-2 border-white"></div>
                                                </div>
                                                <span
                                                    class="text-sm font-semibold text-orange-600">{{ $credit->amount }}</span>
                                                <span class="text-sm text-gray-600">Credits</span>
                                            </div>
                                        </td>
                                        <td class="px-8 py-5 text-right"><span
                                                class="text-sm font-semibold text-gray-900">{{ number_format($payment->order->credits) }}</span>
                                        </td>
                                        <td class="px-8 py-5">
                                            <div class="flex items-center justify-center">
                                                <button
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
                                </tbody>
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
