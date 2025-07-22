<x-user::layout>

    <x-slot name="page_slug">profile</x-slot>
    <div id="content-profile" class="page-content">
        <!-- Header -->
        <div class="flex justify-between items-start mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">My Profile</h1>
                <p class="text-gray-600">Manage your account and view your activity</p>
            </div>
            <a href="#"
                class="flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                <i data-lucide="edit" class="w-4 h-4"></i>
                <span class="text-gray-700">Edit Profile</span>
            </a>
        </div>
        <!-- Profile Card -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between">
                <div class="flex items-center space-x-4 mb-4 lg:mb-0">
                    <div class="relative">
                        <img src="{{ user()->avatar }}" alt="{{ user()->name }}" class="w-20 h-20 rounded-full">
                        <div class="absolute bottom-0 right-0 w-6 h-6 bg-green-500 rounded-full border-2 border-white">
                        </div>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">{{ user()->name }}</h2>
                        {{-- <p class="text-gray-600">Email Not Provided from SoundCloud Profile... </p> --}}
                        <div class="flex items-center space-x-4 mt-2 text-sm text-gray-600">
                            <div class="flex items-center">
                                <i data-lucide="calendar" class="w-4 h-4 mr-1"></i>
                                {{ __('Joined') }} {{ user()->created_at->diffForHumans() }}
                            </div>
                            <div class="flex items-center">
                                <i data-lucide="users" class="w-4 h-4 mr-1"></i>
                                {{ $user->followers_count }} {{ __('Followers') }}
                            </div>
                            <a href="{{ $user->soundcloud_permalink_url}}" target="_blank" class="text-orange-600 hover:underline">SoundCloud Profile</a>
                        </div>
                        <div class="flex space-x-2 mt-4">
                            <span class="bg-orange-600 text-white px-3 py-1 rounded-full text-xs">Top
                                Reposter</span>
                            <span class="bg-blue-500 text-white px-3 py-1 rounded-full text-xs">Quality
                                Artist</span>
                            <span class="bg-green-500 text-white px-3 py-1 rounded-full text-xs">Verified</span>
                        </div>
                    </div>
                </div>

                <!-- Credibility Score -->
                <div class="text-center">
                    <div class="relative w-24 h-24 mx-auto">
                        <svg class="progress-circle w-24 h-24" viewBox="0 0 36 36">
                            <path class="text-gray-200" stroke="currentColor" stroke-width="2" fill="none"
                                d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831">
                            </path>
                            <path class="text-orange-600" stroke="currentColor" stroke-width="2" fill="none"
                                stroke-dasharray="87, 100" stroke-dashoffset="0"
                                d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831">
                            </path>
                        </svg>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <span class="text-xl font-bold text-orange-600">87%</span>
                        </div>
                    </div>
                    <div class="mt-2">
                        <div class="font-semibold text-gray-900">Credibility Score</div>
                        <a href="#" class="text-orange-600 text-sm hover:underline">Show details ></a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabs -->
        <div class="bg-white rounded-lg shadow-sm mb-6">
            <div class="shadow-sm">
                <nav class="flex space-x-8 px-6">
                    <a href="#" class="tab-btn py-4 px-1 border-b-2 border-orange-600 text-orange-600 font-medium"
                        data-tab="overview">Overview</a>
                    <a href="#"
                        class="tab-btn py-4 px-1 border-b-2 border-transparent text-gray-500 hover:text-gray-700"
                        data-tab="transactions">Credit Transactions</a>
                    <a href="#"
                        class="tab-btn py-4 px-1 border-b-2 border-transparent text-gray-500 hover:text-gray-700"
                        data-tab="history">Repost History</a>
                </nav>
            </div>

            <!-- Tab Content -->
            <div id="overview" class="tab-pane p-6 block">
                <!-- Statistics Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div
                        class="bg-orange-50 p-6 shadow-sm rounded-lg hover:-translate-y-2 transition-all duration-500 ease-in-out">
                        <div class="flex items-center">
                            <div class="bg-orange-100 p-3 rounded-lg mr-4">
                                <i data-lucide="music" class="w-6 h-6 text-orange-600"></i>
                            </div>
                            <div>
                                <div class="text-2xl font-bold text-gray-900">2</div>
                                <div class="text-gray-600">Track Submissions</div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-blue-50 p-6 rounded-lg hover:-translate-y-2 transition-all duration-500 ease-in-out">
                        <div class="flex items-center">
                            <div class="bg-blue-100 p-3 shadow-sm rounded-lg mr-4">
                                <i data-lucide="refresh-cw" class="w-6 h-6 text-blue-600"></i>
                            </div>
                            <div>
                                <div class="text-2xl font-bold text-gray-900">18</div>
                                <div class="text-gray-600">Reposts Given</div>
                            </div>
                        </div>
                    </div>

                    <div
                        class="bg-green-50 p-6 shadow-sm rounded-lg hover:-translate-y-2 transition-all duration-500 ease-in-out">
                        <div class="flex items-center">
                            <div class="bg-green-100 p-3 rounded-lg mr-4">
                                <i data-lucide="users" class="w-6 h-6 text-green-600"></i>
                            </div>
                            <div>
                                <div class="text-2xl font-bold text-gray-900">42</div>
                                <div class="text-gray-600">Reposts Received</div>
                            </div>
                        </div>
                    </div>

                    <div
                        class="bg-yellow-50 p-6 shadow-sm rounded-lg hover:-translate-y-2 transition-all duration-500 ease-in-out">
                        <div class="flex items-center">
                            <div class="bg-yellow-100 p-3 rounded-lg mr-4">
                                <i data-lucide="trending-up" class="w-6 h-6 text-yellow-600"></i>
                            </div>
                            <div>
                                <div class="text-2xl font-bold text-gray-900">95</div>
                                <div class="text-gray-600">Total Credits Earned</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Your Genres -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Your Genres</h3>
                    <div class="flex flex-wrap gap-3">
                        <span class="bg-orange-100 text-orange-800 px-4 py-2 rounded-full">Electronic</span>
                        <span class="bg-purple-100 text-purple-800 px-4 py-2 rounded-full">Hip-hop</span>
                        <span class="bg-red-100 text-red-800 px-4 py-2 rounded-full">Indie</span>
                    </div>
                </div>

                <!-- Achievements -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Achievements</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div class="shadow-sm rounded-lg p-4 hover:shadow-md transition-shadow dark:bg-gray-800">
                            <div class="flex items-center mb-3">
                                <div class="bg-orange-100 p-2 rounded-lg mr-3">
                                    <i data-lucide="star" class="w-5 h-5 text-orange-600"></i>
                                </div>
                                <div>
                                    <div class="font-semibold text-gray-900 dark:text-gray-100">Rising Star</div>
                                    <div class="text-sm text-gray-600 dark:text-gray-400">Completed 10 reposts</div>
                                </div>
                            </div>
                        </div>

                        <div class="shadow-sm rounded-lg p-4 hover:shadow-md transition-shadow dark:bg-gray-800">
                            <div class="flex items-center mb-3">
                                <div class="bg-blue-100 p-2 rounded-lg mr-3">
                                    <i data-lucide="refresh-cw" class="w-5 h-5 text-blue-600"></i>
                                </div>
                                <div>
                                    <div class="font-semibold text-gray-900 dark:text-gray-100">Repost Champion</div>
                                    <div class="text-sm text-gray-600 dark:text-gray-400">Reposted 5 different genres</div>
                                </div>
                            </div>
                        </div>

                        <div class="shadow-sm rounded-lg p-4 hover:shadow-md transition-shadow opacity-100 dark:bg-gray-800">
                            <div class="flex items-center mb-3">
                                <div class="bg-gray-100 p-2 rounded-lg mr-3">
                                    <i data-lucide="music" class="w-5 h-5 text-gray-600"></i>
                                </div>
                                <div>
                                    <div class="font-semibold text-gray-900 dark:text-gray-100">Track Master</div>
                                    <div class="text-sm text-gray-600 dark:text-gray-400">Submit 5 tracks (2/5)</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="transactions" class="tab-pane p-6 hidden">
                <div class="text-center py-12">
                    <i data-lucide="credit-card" class="w-12 h-12 text-gray-400 dark:text-gray-500 mx-auto mb-4"></i>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">Credit Transactions</h3>
                    <p class="text-gray-600 dark:text-gray-400">Your credit transaction history will appear here.</p>
                </div>
            </div>

            <div id="history" class="tab-pane p-6 hidden">
                <div class="text-center py-12">
                    <i data-lucide="history" class="w-12 h-12 text-gray-400 dark:text-gray-500 mx-auto mb-4"></i>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">Repost History</h3>
                    <p class="text-gray-600 dark:text-gray-400">Your repost history will appear here.</p>
                </div>
            </div>
        </div>
    </div>
    @push('js')
        <script>
            // Tab functionality (only for profile page)
            const tabBtns = document.querySelectorAll('.tab-btn');
            const tabContents = document.querySelectorAll('.tab-pane');

            tabBtns.forEach(btn => {
                btn.addEventListener('click', () => {
                    const tabId = btn.getAttribute('data-tab');

                    // Update tab buttons
                    tabBtns.forEach(b => {
                        b.classList.remove('border-orange-600', 'text-orange-600');
                        b.classList.add('border-transparent', 'text-gray-500');
                    });
                    btn.classList.remove('border-transparent', 'text-gray-500');
                    btn.classList.add('border-orange-600', 'text-orange-600');

                    // Update tab content
                    tabContents.forEach(content => {
                        content.classList.add('hidden');
                    });
                    document.getElementById(tabId).classList.remove('hidden');
                });
            });

            // // Animate progress circle on load
            // window.addEventListener('load', () => {
            //     const progressCircle = document.querySelector('.progress-circle path:last-child');
            //     if (progressCircle) {
            //         progressCircle.style.strokeDashoffset = '13';
            //         setTimeout(() => {
            //             progressCircle.style.strokeDashoffset = '0';
            //         }, 500);
            //     }
            // });
        </script>
    @endpush
</x-user::layout>
