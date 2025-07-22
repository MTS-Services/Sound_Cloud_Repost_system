<x-user::layout>

    <x-slot name="page_slug">campains</x-slot>

    <div class="p-6">
        <!-- Header -->
        <div class="flex justify-between items-start mb-8">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-2">{{ __('My Campaigns') }}</h1>
                <p class="text-gray-600 dark:text-gray-400">Track the performance of your submitted tracks') }}</p>
            </div>
            <button
                class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                {{ __('New Campaign') }}
            </button>
        </div>

        <!-- Tabs -->
        <div class="mb-8">
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex space-x-8">
                    <button
                        class="tab-button active border-b-2 border-orange-500 py-2 px-1 text-sm font-medium text-orange-600 dark:text-orange-400"
                        data-tab="all">
                        {{ __('All Campaigns') }}
                    </button>
                    <button
                        class="tab-button border-b-2 border-transparent py-2 px-1 text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-200 dark:hover:border-gray-600"
                        data-tab="active">
                        {{ __('Active') }}
                    </button>
                    <button
                        class="tab-button border-b-2 border-transparent py-2 px-1 text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-200 dark:hover:border-gray-600"
                        data-tab="completed">
                        {{ __('Completed') }}
                    </button>
                </nav>
            </div>
        </div>

        <!-- Campaign Cards -->
        <div class="space-y-6">
            <!-- Summer Vibes Campaign -->
            <div class="campaign-card bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6" data-status="active">
                <div class="flex justify-center gap-5">
                    <div class="w-48 h-32"> 
                        <div class="w-full h-full rounded-lg overflow-hidden flex-shrink-0">
                            <img src="{{ asset('frontend/user/image/music-notes.jpg') }}" alt="Summer Vibes Album Cover"
                                class="w-full h-full object-cover bg-gradient-to-br from-yellow-400 via-red-500 to-pink-500">
                        </div>
                    </div>
                    <div class="w-full">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex items-start gap-4">
                                <div>
                                    <div class="flex items-center gap-3 mb-2">
                                        <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100">{{ __('Summer Vibes') }}</h3>
                                        <span
                                            class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full">{{ __('Active') }}</span>
                                    </div>
                                    <div class="flex items-center gap-4 text-sm text-gray-500 dark:text-gray-400">
                                        <div class="flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                </path>
                                            </svg>
                                            {{ __('Created 10/04/2025') }}
                                        </div>
                                        <div class="flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            {{ __('Expires 17/04/2025') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-gray-600 mb-2">{{ __('Budget used: 14/20 credits') }}</p>
                                <div class="w-32 bg-gray-200 rounded-full h-2">
                                    <div class="bg-orange-500 h-2 rounded-full" style="width: 70%"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Stats -->
                        <div class="grid grid-cols-3 gap-6 mt-5">
                            <div class="bg-white rounded-lg shadow-sm py-2 text-center">
                                <div class="flex items-center justify-center mb-2">
                                    <i data-lucide="refresh-ccw" class="w-5 h-5 text-pink-500"></i>
                                </div>
                                <div class="text-2xl font-bold text-gray-900">24</div>
                                <div class="text-sm text-gray-500">{{ __('Reposts') }}</div>
                            </div>
                            <div class="bg-white rounded-lg shadow-sm py-2 text-center">
                                <div class="flex items-center justify-center mb-2">
                                    <i data-lucide="eye" class="w-5 h-5 text-blue-500"></i>
                                </div>
                                <div class="text-2xl font-bold text-gray-900">342</div>
                                <div class="text-sm text-gray-500">{{ __('Plays') }}</div>
                            </div>
                            <div class="bg-white rounded-lg shadow-sm py-2 text-center">
                                <div class="flex items-center justify-center mb-2">
                                    <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                                        </path>
                                    </svg>
                                </div>
                                <div class="text-2xl font-bold text-gray-900">38</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ __('Likes') }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="w-52 flex flex-col justify-end">
                        <!-- Actions -->
                        <div class="flex flex-col items-center gap-3">
                            <a href="#"
                                class="w-full bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors text-sm">
                                <i data-lucide="chart-no-axes-column" class="w-4 h-4"></i>
                                {{ __('View Details') }}
                            </a>
                            <a href="#"
                                class="w-full text-gray-600 hover:text-gray-800 px-4 py-2 rounded-lg flex items-center gap-2 transition-colors border border-gray-300 text-sm">
                                <span class="">
                                    <i data-lucide="plus"
                                        class="w-5 h-5 inline-block border border-gray-300  text-center rounded-full p-1 tex-white"></i>
                                </span>
                                {{ __('Add Credits') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="campaign-card bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6" data-status="completed">
                <div class="flex justify-center gap-5">
                    <div class="w-48 h-32"> 
                        <div class="w-full h-full rounded-lg overflow-hidden flex-shrink-0">
                            <img src="{{ asset('frontend/user/image/music-notes.jpg') }}"
                                alt="Summer Vibes Album Cover"
                                class="w-full h-full object-cover bg-gradient-to-br from-yellow-400 via-red-500 to-pink-500">
                        </div>
                    </div>
                    <div class="w-full">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex items-start gap-4">
                                <div>
                                    <div class="flex items-center gap-3 mb-2">
                                        <h3 class="text-xl font-semibold text-gray-900">Summer Vibes</h3>
                                        <span
                                            class="bg-orange-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full">Complete</span>
                                    </div>
                                    <div class="flex items-center gap-4 text-sm text-gray-500 dark:text-gray-400">
                                        <div class="flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                </path>
                                            </svg>
                                            Created 10/04/2025
                                        </div>
                                        <div class="flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            {{ __('Expires 17/04/2025') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-gray-600 mb-2">{{ __('Budget used: 14/20 credits') }}</p>
                                <div class="w-32 bg-gray-200 rounded-full h-2">
                                    <div class="bg-orange-500 h-2 rounded-full" style="width: 70%"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Stats -->
                        <div class="grid grid-cols-3 gap-6 mt-5">
                            <div class="bg-white rounded-lg shadow-sm py-2 text-center">
                                <div class="flex items-center justify-center mb-2">
                                    <i data-lucide="refresh-ccw" class="w-5 h-5 text-pink-500"></i>
                                </div>
                                <div class="text-2xl font-bold text-gray-900">24</div>
                                <div class="text-sm text-gray-500">{{ __('Reposts') }}</div>
                            </div>
                            <div class="bg-white rounded-lg shadow-sm py-2 text-center">
                                <div class="flex items-center justify-center mb-2">
                                    <i data-lucide="eye" class="w-5 h-5 text-blue-500"></i>
                                </div>
                                <div class="text-2xl font-bold text-gray-900">220</div>
                                <div class="text-sm text-gray-500">{{ __('Plays') }}</div>
                            </div>
                            <div class="bg-white rounded-lg shadow-sm py-2 text-center">
                                <div class="flex items-center justify-center mb-2">
                                    <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                                        </path>
                                    </svg>
                                </div>
                                <div class="text-2xl font-bold text-gray-900">25</div>
                                <div class="text-sm text-gray-500">{{ __('Likes') }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="w-52  flex flex-col justify-end">
                        <!-- Actions -->
                        <div class="flex flex-col items-center gap-3">
                            <a href="#"
                                class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                    </path>
                                </svg>
                                {{ __('View Details') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
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
            const buttons = document.querySelectorAll('button');
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
</x-user::layout>
