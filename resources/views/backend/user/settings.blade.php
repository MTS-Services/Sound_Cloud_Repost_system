<x-user::layout>
    <x-slot name="page_slug">settings</x-slot>

    <style>
        /* Custom styles for the active tab indicator */
        .tab-indicator {
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 100%;
            height: 2px;
            background-color: #4a90e2;
            /* Blue color for the active tab */
            transition: transform 0.3s ease-in-out;
        }

        /* Hide cloaked elements until Alpine shows them */
        [x-cloak] {
            display: none !important;
        }
    </style>

    <div x-data="{ open: true, activeTab: 'edit' }" class="p-4 sm:p-6 lg:p-8">
        <div x-show="open" x-transition.opacity.duration.300ms
            class=" top-0  mb-8 max-w-8xl mx-auto  bg-yellow-300 border-l-4 border-yellow-500 text-black p-4 shadow-sm flex items-center justify-center z-50"
            role="alert">
            <div class="flex flex-col items-center text-center">
                <p class="text-sm">
                    Please confirm your email address to unlock core platform features.
                    <a href="#" class="font-semibold text-orange-600 hover:underline">Confirm email</a>
                </p>
            </div>
        </div>


        <div class="bg-white rounded-xl shadow-lg max-w-8xl mx-auto overflow-hidden">
            <div class="border-b border-gray-200">
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

            <div class="p-6 sm:p-8 lg:p-10">
                <!-- Edit Profile -->
                <div x-show="activeTab === 'edit'" x-cloak>
                    <div x-data="{ open: true }" x-show="open"
                        class="bg-blue-50 border-l-4 border-orange-600 p-4 rounded-md mb-6 flex items-center gap-4">
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
                            <span class="font-bold text-gray-800">Quick Tip</span>
                        </div>


                        <!-- Description -->
                        <p class="text-sm text-gray-700 flex-1">
                            Customise your genres to personalise your RepostExchange experience.
                        </p>

                        <!-- Close button -->
                        <button class="transition-colors text-gray-500 hover:text-gray-700 flex-shrink-0"
                            @click="open = false">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>




                    <form>
                        <!-- Email -->
                        <div class="mb-6">
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" id="email" value="dev.mafiuz@gmail.com"
                                class="mt-1 block max-w-md w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                            <p class="mt-1 text-xs text-red-500">
                                Email not verified.
                                <a href="#" class="font-semibold hover:underline">Resend confirmation email</a>
                            </p>
                        </div>


                        <!-- Genres -->
                        <div class="mb-6">
                            <label for="genres" class="block text-sm font-medium text-gray-700 mb-1">Genres</label>
                            <div class="relative mt-1 w-[70%]">
                                <div id="genre-tags-container"
                                    class="flex flex-wrap items-center gap-2 p-2 rounded-md border border-gray-300 shadow-sm min-h-[38px]">
                                    <span
                                        class="genre-tag bg-gray-200 text-gray-700 rounded-full px-3 py-1 text-sm font-medium flex items-center">
                                        Techno
                                        <button type="button"
                                            class="ml-1 text-gray-500 hover:text-gray-900 focus:outline-none transition-colors">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </span>
                                    <span
                                        class="genre-tag bg-gray-200 text-gray-700 rounded-full px-3 py-1 text-sm font-medium flex items-center">
                                        Classical
                                        <button type="button"
                                            class="ml-1 text-gray-500 hover:text-gray-900 focus:outline-none transition-colors">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </span>
                                    <span
                                        class="genre-tag bg-gray-200 text-gray-700 rounded-full px-3 py-1 text-sm font-medium flex items-center">
                                        Country
                                        <button type="button"
                                            class="ml-1 text-gray-500 hover:text-gray-900 focus:outline-none transition-colors">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </span>
                                    <span
                                        class="genre-tag bg-gray-200 text-gray-700 rounded-full px-3 py-1 text-sm font-medium flex items-center">
                                        Malates
                                        <button type="button"
                                            class="ml-1 text-gray-500 hover:text-gray-900 focus:outline-none transition-colors">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </span>
                                </div>
                                <input type="text" placeholder="Search"
                                    class="absolute inset-0 z-0 opacity-0 w-full cursor-pointer">
                                <div class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>



                        <!-- Artist link -->
                        <div class="p-4 sm:p-8 bg-white dark:bg-gray-900 font-sans text-gray-800 dark:text-gray-200">

                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">
                                Connect social accounts (will be promoted after someone reposts one of your tracks if
                                you have a Pro Plan)
                            </p>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">

                                <!-- Instagram -->
                                <div class="flex items-center p-4 bg-gray-100 dark:bg-gray-800 rounded-lg w-[70%]">
                                    <div class="text-2xl mr-4 text-pink-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="currentColor"
                                            viewBox="0 0 24 24">
                                            <path
                                                d="M12 2.163c3.204 0 3.584.012 4.85.07 1.17.054 1.97.24 2.43.414a4.92 4.92 0 011.77 1.145 4.92 4.92 0 011.145 1.77c.174.46.36 1.26.414 2.43.058 1.266.07 1.645.07 4.85s-.012 3.584-.07 4.85c-.054 1.17-.24 1.97-.414 2.43a4.92 4.92 0 01-1.145 1.77 4.92 4.92 0 01-1.77 1.145c-.46.174-1.26.36-2.43.414-1.266.058-1.645.07-4.85.07s-3.584-.012-4.85-.07c-1.17-.054-1.97-.24-2.43-.414a4.92 4.92 0 01-1.77-1.145 4.92 4.92 0 01-1.145-1.77c-.174-.46-.36-1.26-.414-2.43-.058-1.266-.07-1.645-.07-4.85s.012-3.584.07-4.85c.054-1.17.24-1.97.414-2.43a4.92 4.92 0 011.145-1.77 4.92 4.92 0 011.77-1.145c.46-.174 1.26-.36 2.43-.414 1.266-.058 1.645-.07 4.85-.07zM12 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zm0 10.162a3.999 3.999 0 110-7.998 3.999 3.999 0 010 7.998zM18.406 4.594a1.44 1.44 0 11-2.88 0 1.44 1.44 0 012.88 0z" />
                                        </svg>
                                    </div>
                                    <input type="text" placeholder="@username"
                                        class="flex-grow bg-transparent border-none focus:outline-none placeholder-gray-500 dark:placeholder-gray-400" />
                                </div>

                                <!-- Twitter -->
                                <div class="flex items-center p-4 bg-gray-100 dark:bg-gray-800 rounded-lg w-[70%]">
                                    <div class="text-2xl mr-4 text-blue-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="currentColor"
                                            viewBox="0 0 24 24">
                                            <path
                                                d="M23 3a10.9 10.9 0 01-3.14 1.53A4.48 4.48 0 0022.4.36a9.09 9.09 0 01-2.88 1.1A4.52 4.52 0 0016.88 0c-2.5 0-4.52 2.03-4.52 4.53 0 .35.04.7.11 1.03C7.69 5.48 4.07 3.57 1.64.72a4.52 4.52 0 00-.61 2.28c0 1.57.8 2.96 2.02 3.77a4.52 4.52 0 01-2.05-.56v.06c0 2.2 1.57 4.03 3.64 4.45a4.51 4.51 0 01-2.04.08c.57 1.78 2.23 3.08 4.2 3.12A9.04 9.04 0 010 19.54 12.75 12.75 0 006.92 21c8.3 0 12.84-6.9 12.84-12.88 0-.2 0-.39-.01-.58A9.2 9.2 0 0023 3z" />
                                        </svg>
                                    </div>
                                    <input type="text" placeholder="@username"
                                        class="flex-grow bg-transparent border-none focus:outline-none placeholder-gray-500 dark:placeholder-gray-400" />
                                </div>

                                <!-- Facebook -->
                                <div class="flex items-center p-4 bg-gray-100 dark:bg-gray-800 rounded-lg w-[70%]">
                                    <div class="text-2xl mr-4 text-blue-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="currentColor"
                                            viewBox="0 0 24 24">
                                            <path
                                                d="M22.675 0h-21.35C.596 0 0 .593 0 1.326v21.348C0 23.406.596 24 1.326 24H12v-9.294H9.294v-3.622H12V8.412c0-2.675 1.632-4.132 4.01-4.132 1.137 0 2.115.084 2.399.122v2.78l-1.647.001c-1.292 0-1.543.615-1.543 1.517v1.992h3.086l-.402 3.622H15.22V24h7.455c.73 0 1.325-.594 1.325-1.326V1.326C24 .593 23.405 0 22.675 0z" />
                                        </svg>
                                    </div>
                                    <input type="text" placeholder="/pagelink"
                                        class="flex-grow bg-transparent border-none focus:outline-none placeholder-gray-500 dark:placeholder-gray-400" />
                                </div>

                                <!-- YouTube -->
                                <div class="flex items-center p-4 bg-gray-100 dark:bg-gray-800 rounded-lg w-[70%]">
                                    <div class="text-2xl mr-4 text-red-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="currentColor"
                                            viewBox="0 0 24 24">
                                            <path
                                                d="M19.615 3.184c-.33-.33-.71-.515-1.16-.567C16.364 2.5 12 2.5 12 2.5s-4.364 0-6.455.117c-.45.052-.83.237-1.16.567-.33.33-.515.71-.567 1.16C3.5 5.636 3.5 10 3.5 10s0 4.364.117 6.455c.052.45.237.83.567 1.16.33.33.71.515 1.16.567 2.091.117 6.455.117 6.455.117s4.364 0 6.455-.117c.45-.052.83-.237 1.16-.567.33-.33.515-.71.567-1.16.117-2.091.117-6.455.117-6.455s0-4.364-.117-6.455c-.052-.45-.237-.83-.567-1.16zM9.75 14.548V5.452l6.545 4.548-6.545 4.548z" />
                                        </svg>
                                    </div>
                                    <input type="text" placeholder="Channel ID"
                                        class="flex-grow bg-transparent border-none focus:outline-none placeholder-gray-500 dark:placeholder-gray-400" />
                                </div>

                                <!-- TikTok -->
                                <div
                                    class="flex items-center p-4 bg-gray-100 dark:bg-gray-800 rounded-lg  w-[70%]">
                                    <div class="text-2xl mr-4 text-black dark:text-white">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="currentColor"
                                            viewBox="0 0 24 24">
                                            <path
                                                d="M12.528 2.344c.217 0 .433-.025.644-.074v6.31c-.19-.03-.385-.05-.58-.05-2.93 0-5.303 2.373-5.303 5.303s2.373 5.303 5.303 5.303c2.786 0 5.082-2.13 5.28-4.868h-2.125a3.16 3.16 0 01-3.155 2.094c-1.736 0-3.148-1.412-3.148-3.148s1.412-3.148 3.148-3.148z" />
                                        </svg>
                                    </div>
                                    <input type="text" placeholder="@username"
                                        class="flex-grow bg-transparent border-none focus:outline-none placeholder-gray-500 dark:placeholder-gray-400" />
                                </div>

                                <!-- Spotify -->
                                <div
                                    class="flex items-center p-4 bg-gray-100 dark:bg-gray-800 rounded-lg sm:col-span-2 w-[70%]">
                                    <div class="text-2xl mr-4 text-green-500">
                                        <i class="fab fa-spotify"></i>
                                    </div>
                                    <input type="text"
                                        placeholder="Artist link (i.e. https://open.spotify.com/artist/...)"
                                        class="flex-grow bg-transparent border-none focus:outline-none placeholder-gray-500 dark:placeholder-gray-400" />
                                </div>

                            </div>
                        </div>

                
                </form>

                <div class="mt-8 flex justify-end space-x-4">
                    <button type="button"
                        class="px-6 py-2 border border-gray-300 rounded-md text-orange-500 font-medium hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-6 py-2 border border-transparent rounded-md shadow-sm text-white font-medium bg-gray-500 hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                        Save Profile
                    </button>
                </div>
                 
                

                    <!-- Small content below buttons -->
                    <div
                        class="border-t border-gray-300 dark:border-gray-600 mt-4 pt-2 text-sm text-gray-500 dark:text-gray-400">
                        Looking to delete your account?<a href="#" class="font-semibold hover:underline">Click
                            <span class="text-red-500"> here</a>.
                    </div>
                </div>
                
            </div>
       


        <!-- Notifications -->
        <div x-show="activeTab === 'notifications'" class="tab-content" x-cloak>
            <h2 class="text-2xl font-bold text-gray-800">Notifications & Alerts</h2>
            <p class="mt-4 text-gray-600">Content for notifications and alerts will go here.</p>
        </div>

        <!-- Settings -->
        <div x-show="activeTab === 'settings'" class="tab-content" x-cloak>
            <h2 class="text-2xl font-bold text-gray-800">Settings</h2>
            <p class="mt-4 text-gray-600">Content for general settings will go here.</p>
        </div>

        <!-- Credit -->
        <div x-show="activeTab === 'credit'" class="tab-content" x-cloak>
            <h2 class="text-2xl font-bold text-gray-800">Credit History</h2>
            <p class="mt-4 text-gray-600">Content for credit history will go here.</p>
        </div>

        <!-- Invoices -->
        <div x-show="activeTab === 'invoices'" class="tab-content" x-cloak>
            <h2 class="text-2xl font-bold text-gray-800">Invoices</h2>
            <p class="mt-4 text-gray-600">Content for invoices will go here.</p>
        </div>



        <!-- Notifications Section -->
        <div x-show="activeTab === 'notifications'"
            class="max-w-8xl w-full bg-white rounded-lg shadow-lg p-6 md:p-8 mt-6">

            <!-- Top "Alerts" Section -->
            <div class="border-b border-gray-200 pb-4 mb-4">
                <h1 class="text-xl font-semibold text-gray-800">Alerts</h1>
            </div>

            <!-- Push Notification Disabled Warning -->
            <div class="flex items-start bg-light-orange border-l-4 border-primary-orange p-4 mb-6 rounded">
                <svg class="h-6 w-6 text-primary-orange bg-blue-400 rounded-full mr-3 mt-1 flex-shrink-0"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0
                    11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="text-sm font-medium text-dark-gray">
                    Your push notifications are disabled because you haven't installed the mobile app
                </span>
            </div>

            <!-- Alerts Table Headers -->
            <div
                class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-4 pb-2 border-b border-gray-200 text-gray-500 font-semibold text-sm uppercase">
                <div class="col-span-2 md:col-span-1"></div>
                <div class="text-right">Email</div>
                <div class="text-right">Push</div>
            </div>

            <!-- Alerts List -->
            <div x-data="{
                alerts: [
                    { id: 1, name: 'New Repost Requests', email: true, push: false },
                    { id: 2, name: 'Repost Requests Accepted', email: true, push: false },
                    { id: 3, name: 'Repost Requests Declined', email: true, push: false },
                    { id: 4, name: 'Repost Requests Expired', email: true, push: false },
                    { id: 5, name: 'Campaign Summary & finished alert', email: true, push: false },
                    { id: 6, name: 'Free Boost Award', email: true, push: false },
                    { id: 7, name: 'Feedback Campaign Events', email: true, push: false },
                    { id: 8, name: 'Feedback Rated', email: true, push: false },
                    { id: 9, name: 'Referrals', email: true, push: false },
                    { id: 10, name: 'Reputation Changes', email: true, push: false },
                    { id: 11, name: 'Account inactivity Warning', email: true, push: false },
                    { id: 12, name: 'Marketing Communications', email: false, push: false },
                    { id: 13, name: 'Chart Entry', email: false, push: false },
                    { id: 14, name: 'Chart Entry', email: false, push: false },
                    { id: 15, name: 'Mystery Box Draw', email: true, push: false },
                    { id: 16, name: 'Discussions', email: true, push: false },
                    { id: 17, name: 'Competitions', email: true, push: false }
                ],
                saveProfile() {
                    console.log('Profile data to be saved:', this.alerts);
                    alert('Profile saved! Check the console for the data.');
                }
            }">

                <template x-for="alert in alerts" :key="alert.id">
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4 items-center py-3 border-b border-gray-100">
                        <div class="col-span-2 md:col-span-1 text-sm text-gray-800" x-text="alert.name"></div>
                        <div class="flex justify-end md:justify-end">
                            <input type="checkbox" x-model="alert.email"
                                class="form-checkbox h-5 w-5 rounded border-gray-300 text-primary-orange focus:ring-primary-orange">
                        </div>
                        <div class="flex justify-end md:justify-end">
                            <input type="checkbox" x-model="alert.push"
                                class="form-checkbox h-5 w-5 rounded border-gray-300 text-gray-400 focus:ring-gray-400">
                        </div>
                    </div>
                </template>

                <!-- Action Buttons -->
                <div
                    class="mt-8 flex flex-col md:flex-row justify-end items-center space-y-4 md:space-y-0 md:space-x-4">
                    <button
                        class="w-full md:w-auto px-6 py-2 text-orange-500 font-medium rounded-md hover:bg-gray-100 transition duration-150">
                        Cancel
                    </button>
                    <button @click="saveProfile()"
                        class="w-full md:w-auto px-6 py-2 bg-orange-500 text-white font-medium rounded-md shadow-sm hover:bg-orange-600 transition duration-150">
                        Save Profile
                    </button>
                </div>
            </div>
        </div>
        <!-- Notifications Settings -->
        <!-- Settings Section -->
        <div x-show="activeTab === 'settings'" x-cloak>
            <div class="w-full max-w-8xl bg-white rounded-lg shadow-lg p-6 md:p-8">
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
                                <input type="radio" name="acceptRequests" x-model="acceptRequests" value="true"
                                    class="text-orange-500">
                                <span class="text-gray-600">Yes</span>
                            </label>
                            <label class="flex items-center gap-1">
                                <input type="radio" name="acceptRequests" x-model="acceptRequests" value="false"
                                    checked class="text-orange-500">
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
                                <input type="radio" name="blockRequests" x-model="blockRequests" value="true"
                                    class="text-orange-500">
                                <span class="text-gray-600">Yes</span>
                            </label>
                            <label class="flex items-center gap-1">
                                <input type="radio" name="blockRequests" x-model="blockRequests" value="false"
                                    checked class="text-orange-500">
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
                                <input type="radio" name="mysteryBox" x-model="mysteryBox" value="true" checked
                                    class="text-orange-500">
                                <span class="text-gray-600">Yes</span>
                            </label>
                            <label class="flex items-center gap-1">
                                <input type="radio" name="mysteryBox" x-model="mysteryBox" value="false"
                                    class="text-orange-500">
                                <span class="text-gray-600">No</span>
                            </label>
                        </div>
                    </div>

                    <!-- Auto Boost -->
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-2">
                        <div>
                            <p class="text-gray-700">Auto Free Boost <span class="text-gray-400 text-sm">(i)</span>
                            </p>
                        </div>
                        <div class="flex items-center gap-6">
                            <label class="flex items-center gap-1">
                                <input type="radio" name="autoBoost" x-model="autoBoost" value="true"
                                    class="text-orange-500">
                                <span class="text-gray-600">Yes</span>
                            </label>
                            <label class="flex items-center gap-1">
                                <input type="radio" name="autoBoost" x-model="autoBoost" value="false" checked
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
                                <input type="radio" name="reactions" x-model="reactions" value="true" checked
                                    class="text-orange-500">
                                <span class="text-gray-600">Yes</span>
                            </label>
                            <label class="flex items-center gap-1">
                                <input type="radio" name="reactions" x-model="reactions" value="false"
                                    class="text-orange-500">
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

                <div class="flex justify-end gap-4 mt-4 md:mt-0">
                    <button
                        class="px-4 py-2 rounded-md border border-gray-300 text-gray-700 hover:bg-gray-100">Cancel</button>
                    <button class="px-4 py-2 rounded-md bg-orange-500 text-white hover:bg-orange-600">Save
                        Profile</button>
                </div>

            </div>
        </div>

        <!-- Credit History Table -->
        <div x-show="activeTab === 'credit'" class="overflow-x-auto w-full mt-4" x-transition>
            <table class="min-w-full border border-gray-200 text-sm text-left">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="px-4 py-2 font-medium text-gray-600">Date</th>
                        <th class="px-4 py-2 font-medium text-gray-600">Description</th>
                        <th class="px-4 py-2 font-medium text-gray-600">Credits</th>
                        <th class="px-4 py-2 font-medium text-gray-600">Balance</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-gray-700 whitespace-nowrap">
                            25 Aug 2025 08:41AM
                        </td>
                        <td class="px-4 py-3 text-gray-700 whitespace-nowrap">
                            Free credits on sign up
                        </td>
                        <td class="px-4 py-3 text-green-600 font-medium flex items-center gap-1 whitespace-nowrap">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3-.895 3-2-1.343-2-3-2z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 14c-3.866 0-7 1.343-7 3v2h14v-2c0-1.657-3.134-3-7-3z" />
                            </svg>
                            +30
                        </td>
                        <td class="px-4 py-3 text-gray-700 font-medium whitespace-nowrap">30</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!--invoices Section -->
        <div x-show="activeTab === 'invoices'" class="overflow-x-auto w-full mt-4" x-transition>
            <table class="min-w-full border border-gray-200 text-sm text-left">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="px-4 py-2 font-medium text-gray-600">Date</th>
                        <th class="px-4 py-2 font-medium text-gray-600">Description</th>
                        <th class="px-4 py-2 font-medium text-gray-600">Total</th>
                        <th class="px-4 py-2 font-medium text-gray-600">invoice</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-gray-700 whitespace-nowrap">
                            25 Aug 2025 08:41AM
                        </td>
                        <td class="px-4 py-3 text-blue-600 whitespace-nowrap">
                            Free credits on sign up
                        </td>
                        <td class="px-4 py-3 text-green-600 font-medium flex items-center gap-1 whitespace-nowrap">

                            830
                        </td>
                        <td class="px-4 py-3 text-gray-700 font-medium whitespace-nowrap">30</td>
                    </tr>
                </tbody>
            </table>
        </div>
    
    </div>

    </div>
 </div>
    </div>
    </div>
    </div>
    </div>
</x-user::layout>
