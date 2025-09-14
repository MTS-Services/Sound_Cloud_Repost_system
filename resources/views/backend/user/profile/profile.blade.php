<x-user::layout>
    <x-slot name="page_slug">profile</x-slot>
    <div id="content-profile" class="page-content">
        <!-- Header -->
        <div class="flex justify-between items-start mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-2">My Profile</h1>
                <p class="text-gray-600 dark:text-gray-300">Manage your account and view your activity</p>
            </div>
        </div>

        <!-- Profile Card -->
        <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm p-6 mb-6 border border-gray-100 dark:border-gray-700">
            <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between">
                <div class="flex items-center space-x-4 mb-4 lg:mb-0">
                    <div class="relative">
                        <img src="{{ auth_storage_url($user->avatar) }}" alt="{{ $user->name }}"
                            class="w-20 h-20 rounded-full ring-2 ring-orange-500/30 dark:ring-orange-400/30">
                        <div
                            class="absolute bottom-0 right-0 w-6 h-6 bg-green-500 rounded-full border-2 border-white dark:border-gray-900">
                        </div>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100">{{ $user->name }}</h2>
                        <div class="flex items-center space-x-4 mt-2 text-sm text-gray-600 dark:text-gray-300">
                            <div class="flex items-center">
                                <i data-lucide="calendar" class="w-4 h-4 mr-1 text-gray-500 dark:text-gray-400"></i>
                                {{ __('Joined') }} {{ $user->created_at->diffForHumans() }}
                            </div>
                            <div class="flex items-center">
                                <i data-lucide="users" class="w-4 h-4 mr-1 text-gray-500 dark:text-gray-400"></i>
                                {{ $user->followers_count ?? 0 }} {{ __('Followers') }}
                            </div>
                            <a href="{{ $user->soundcloud_permalink_url ?? '#' }}" target="_blank"
                                class="text-orange-600 dark:text-orange-400 hover:underline flex items-center">
                                <i data-lucide="music" class="w-4 h-4 mr-1"></i>
                                SoundCloud Profile
                            </a>
                        </div>

                        <!-- Badges -->
                        <div class="flex flex-wrap gap-2 mt-4">
                            <span
                                class="bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-300 px-3 py-1 rounded-full text-xs font-medium">
                                Top Reposter
                            </span>
                            <span
                                class="bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300 px-3 py-1 rounded-full text-xs font-medium">
                                Quality Artist
                            </span>

                            @if ($user->email_verified_at)
                                <span
                                    class="flex items-center gap-1 bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300 px-3 py-1 rounded-full text-xs font-medium">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2l4-4m5.121-2.879a3 3 0 010 4.242l-8.486 8.486a3 3 0 01-4.242 0l-4.243-4.243a3 3 0 010-4.242l8.486-8.486a3 3 0 014.242 0l4.243 4.243z" />
                                    </svg>
                                    Verified
                                </span>
                            @else
                                <span
                                    class="flex items-center gap-1 bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300 px-3 py-1 rounded-full text-xs font-medium">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                    Unverified
                                </span>
                            @endif
                            {{-- @if (!user()->email_verified_at && user()->urn == $user->user_urn)
                                <form x-data="{ loading: false }" x-ref="form" method="POST"
                                    action="{{ route('user.email.resend.verification') }}"
                                    @submit.prevent="loading = true; $refs.submitButton.disabled = true; $refs.form.submit();">
                                    @csrf
                                    <button type="submit" x-ref="submitButton" :disabled="loading"
                                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1 rounded-full text-xs font-medium transition duration-300 dark:bg-indigo-500 dark:hover:bg-indigo-600">
                                        <template x-if="!loading">
                                            <span>Resend Verification Email</span>
                                        </template>
                                        <template x-if="loading">
                                            <span>Sending...</span>
                                        </template>
                                    </button>
                                </form>
                                <div x-show="loading" class="text-center mt-2 hidden" x-transition>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="animate-spin h-5 w-5 text-indigo-500"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                        <circle cx="12" cy="12" r="10" stroke="currentColor"
                                            stroke-width="4" class="opacity-25"></circle>
                                        <path fill="currentColor" d="M4 12a8 8 0 0116 0" class="opacity-75"></path>
                                    </svg>
                                </div>
                            @endif --}}
                        </div>
                    </div>
                </div>

                <!-- Credibility Score -->
                <div class="text-center">
                    <div class="relative w-24 h-24 mx-auto">
                        <svg class="progress-circle w-24 h-24" viewBox="0 0 36 36">
                            <path class="text-gray-200 dark:text-gray-700" stroke="currentColor" stroke-width="2"
                                fill="none"
                                d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831">
                            </path>
                            <path class="text-orange-500 dark:text-orange-400" stroke="currentColor" stroke-width="2"
                                fill="none" stroke-dasharray="87, 100" stroke-dashoffset="0"
                                d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831">
                            </path>
                        </svg>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <span class="text-xl font-bold text-orange-600 dark:text-orange-400">87%</span>
                        </div>
                    </div>
                    <div class="mt-2">
                        <div class="font-semibold text-gray-900 dark:text-gray-100">Credibility Score</div>
                        <a href="#" class="text-orange-600 dark:text-orange-400 text-sm hover:underline">Show
                            details ></a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabs -->
        <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm mb-6 border border-gray-100 dark:border-gray-700">
            <div class="shadow-sm">
                <nav class="flex space-x-8 px-6">
                    <a href="#"
                        class="tab-btn py-4 px-1 border-b-2 border-orange-500 text-orange-600 dark:text-orange-400 font-medium"
                        data-tab="overview">Overview</a>
                </nav>
            </div>

            <!-- Tab Content -->
            <div id="overview" class="tab-pane p-6 block">
                <!-- Statistics Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div
                        class="bg-gradient-to-br from-orange-50 to-orange-100 dark:from-orange-900/20 dark:to-orange-900/30 p-6 shadow-sm rounded-lg hover:-translate-y-2 transition-all duration-500 ease-in-out ">
                        <div class="flex items-center">
                            <div class="bg-orange-100 dark:bg-orange-800 p-3 rounded-lg mr-4">
                                <x-lucide-music class="w-6 h-6 text-orange-600 dark:text-orange-400"></x-lucide-music>
                            </div>
                            <div>
                                <div class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $tracks }}
                                </div>
                                <div class="text-gray-600 dark:text-gray-300">Track Submissions</div>
                            </div>
                        </div>
                    </div>
                    <div
                        class="bg-green-50 dark:bg-gray-800 p-6 shadow-sm rounded-lg hover:-translate-y-2 transition-all duration-500 ease-in-out ">
                        <div class="flex items-center">
                            <div class="bg-green-100 dark:bg-green-800 p-3 rounded-lg mr-4">
                                <x-lucide-users class="w-6 h-6 text-green-600 dark:text-green-400"></x-lucide-users>
                            </div>
                            <div>
                                <div class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                                    {{ $received_repostRequests }}</div>
                                <div class="text-gray-600 dark:text-gray-300">Reposts Received</div>
                            </div>
                        </div>
                    </div>

                    <div
                        class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-900/30 p-6 rounded-lg hover:-translate-y-2 transition-all duration-500 ease-in-out ">
                        <div class="flex items-center">
                            <div class="bg-blue-100 dark:bg-blue-800 p-3 rounded-lg mr-4">
                                <x-lucide-refresh-cw
                                    class="w-6 h-6 text-blue-600 dark:text-blue-400"></x-lucide-refresh-cw>
                            </div>
                            <div>
                                <div class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                                    {{ $gevened_repostRequests }}</div>
                                <div class="text-gray-600 dark:text-gray-300">Reposts Given</div>
                            </div>
                        </div>
                    </div>



                    <div
                        class="bg-yellow-50  dark:bg-yellow-800  p-6 shadow-sm rounded-lg hover:-translate-y-2 transition-all duration-500 ease-in-out">
                        <div class="flex items-center">
                            <div class="bg-yellow-100 dark:bg-yellow-600 p-3 rounded-lg mr-4">
                                <x-lucide-trending-up
                                    class="w-6 h-6 text-yellow-600 dark:text-yellow-400"></x-lucide-trending-up>
                            </div>
                            <div>
                                <div class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                                    {{ $total_erned_credits }}</div>
                                <div class="text-gray-600 dark:text-gray-300">Total Credits Earned</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Your Genres -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Your Genres</h3>
                    <div class="flex flex-wrap gap-3">
                        @foreach ($user->genres as $index => $genre)
                            @php
                                $colors = [
                                    'bg-orange-100 text-orange-800 dark:bg-orange-900/20 dark:text-gray-300',
                                    'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300',
                                    'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300',
                                    'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300',
                                    'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300',
                                ];
                                $colorClass = $colors[$index % count($colors)];
                            @endphp
                            <span class="{{ $colorClass }} px-4 py-2 rounded-full text-sm font-medium">
                                {{ $genre->genre }}
                            </span>
                        @endforeach
                    </div>
                </div>

                <!-- Achievements -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Achievements</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div
                            class="shadow-sm rounded-lg p-4 hover:shadow-md transition-shadow bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700">
                            <div class="flex items-center mb-3">
                                <div class="bg-orange-100 dark:bg-orange-900/30 p-2 rounded-lg mr-3">
                                    <i data-lucide="star" class="w-5 h-5 text-orange-600 dark:text-orange-400"></i>
                                </div>
                                <div>
                                    <div class="font-semibold text-gray-900 dark:text-gray-100">Rising Star</div>
                                    <div class="text-sm text-gray-600 dark:text-gray-300">Completed
                                        {{ $completed_reposts }} reposts</div>
                                </div>
                            </div>
                        </div>

                        <div
                            class="shadow-sm rounded-lg p-4 hover:shadow-md transition-shadow bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700">
                            <div class="flex items-center mb-3">
                                <div class="bg-blue-100 dark:bg-blue-900/30 p-2 rounded-lg mr-3">
                                    <i data-lucide="refresh-cw" class="w-5 h-5 text-blue-600 dark:text-blue-400"></i>
                                </div>
                                <div>
                                    <div class="font-semibold text-gray-900 dark:text-gray-100">Repost Champion</div>
                                    <div class="text-sm text-gray-600 dark:text-gray-300">Reposted
                                        {{ $reposted_genres }} different genres</div>
                                </div>
                            </div>
                        </div>

                        <div
                            class="shadow-sm rounded-lg p-4 hover:shadow-md transition-shadow bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700">
                            <div class="flex items-center mb-3">
                                <div class="bg-gray-100 dark:bg-gray-700 p-2 rounded-lg mr-3">
                                    <i data-lucide="music" class="w-5 h-5 text-gray-600 dark:text-gray-300"></i>
                                </div>
                                <div>
                                    <div class="font-semibold text-gray-900 dark:text-gray-100">Track Master</div>
                                    {{-- <div class="text-sm text-gray-600 dark:text-gray-300">Submit 5 tracks (2/5)</div> --}}
                                    <div class="text-sm text-gray-600 dark:text-gray-300">Submit tracks
                                        {{ $tracks_today }}/{{ $tracks }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('js')
        <script>
            const tabBtns = document.querySelectorAll('.tab-btn');
            const tabContents = document.querySelectorAll('.tab-pane');

            tabBtns.forEach(btn => {
                btn.addEventListener('click', () => {
                    const tabId = btn.getAttribute('data-tab');

                    tabBtns.forEach(b => {
                        b.classList.remove('border-orange-500', 'text-orange-600',
                            'dark:text-orange-400');
                        b.classList.add('border-transparent', 'text-gray-500', 'dark:text-gray-400');
                    });
                    btn.classList.remove('border-transparent', 'text-gray-500', 'dark:text-gray-400');
                    btn.classList.add('border-orange-500', 'text-orange-600', 'dark:text-orange-400');

                    tabContents.forEach(content => {
                        content.classList.add('hidden');
                    });
                    document.getElementById(tabId).classList.remove('hidden');
                });
            });
        </script>
    @endpush
</x-user::layout>
