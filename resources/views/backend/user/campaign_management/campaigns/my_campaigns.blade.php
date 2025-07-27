<section x-data="{ showCampaignsModal: false, showSubmitModal: false }">

    <x-slot name="page_slug">campaigns</x-slot>

    <div class="p-6">
        <!-- Header Section -->
        <div class="flex justify-between items-start mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-3">{{ __('My Campaigns') }}</h1>
                <p class="text-gray-600 dark:text-gray-400 text-lg">Track the performance of your submitted tracks</p>
            </div>
            <button wire:click="toggleCampaignsModal" x-on:click="showCampaignsModal = true"
                class="bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white px-6 py-3 rounded-xl flex items-center gap-3 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                <i data-lucide="plus" class="w-5 h-5"></i>
                {{ __('New Campaign') }}
            </button>
        </div>

        <!-- Tabs Section -->
        <div class="mb-8">
            <div class="border-b border-gray-200 dark:border-gray-700">
                <nav class="-mb-px flex space-x-8">
                    <button id="main-tab-all"
                        class="tab-button @if ($activeMainTab === 'all') active border-b-2 border-orange-500 text-orange-600 @else border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 @endif py-3 px-2 text-sm font-semibold transition-all duration-200"
                        wire:click="$set('activeMainTab', 'all')">
                        {{ __('All Campaigns') }}
                    </button>
                    <button id="main-tab-active"
                        class="tab-button @if ($activeMainTab === 'Active') active border-b-2 border-orange-500 text-orange-600 @else border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 @endif py-3 px-2 text-sm font-semibold transition-all duration-200"
                        wire:click="$set('activeMainTab', 'Active')">
                        {{ __('Active') }}
                    </button>
                    <button id="main-tab-completed"
                        class="tab-button @if ($activeMainTab === 'Completed') active border-b-2 border-orange-500 text-orange-600 @else border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 @endif py-3 px-2 text-sm font-semibold transition-all duration-200"
                        wire:click="$set('activeMainTab', 'Completed')">
                        {{ __('Completed') }}
                    </button>
                </nav>
            </div>
        </div>

        <!-- Campaigns List -->
        <div class="space-y-6" id="campaigns-list">
            @forelse ($campaigns->where('status_label', $activeMainTab === 'all' ? true : $activeMainTab) as $campaign)
                {{-- <div class="campaign-card bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6 hover:shadow-xl transition-all duration-300"
                    data-status="{{ $campaign->status_label }}">
                    <div class="flex justify-center gap-6">
                        <!-- Album Cover -->
                        <div class="w-48 h-32 flex-shrink-0">
                            <div class="w-full h-full rounded-xl overflow-hidden relative group">
                                <img src="{{ $campaign->artwork_url ?? asset('frontend/user/image/music-notes.jpg') }}"
                                    alt="{{ $campaign->title ?? 'Campaign Album Cover' }}"
                                    class="w-full h-full object-cover bg-gradient-to-br from-yellow-400 via-red-500 to-pink-500 transition-transform duration-300 group-hover:scale-105">
                                <button
                                    class="playPauseBtn absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white/90 backdrop-blur-sm text-gray-800 rounded-full p-3 hover:bg-white transition-all duration-300 opacity-0 group-hover:opacity-100 shadow-lg">
                                    <svg class="playIcon w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M8 5v14l11-7z" />
                                    </svg>
                                    <svg class="pauseIcon w-5 h-5 hidden" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Campaign Details -->
                        <div class="flex-1">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex-1">
                                    <div class="flex items-center gap-3 mb-3">
                                        <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100">
                                            {{ $campaign->title }}
                                        </h3>
                                        <span
                                            class="badge {{ $campaign->status_color }} text-white text-xs font-semibold px-3 py-1 rounded-full shadow-sm">{{ $campaign->status_label }}</span>
                                    </div>
                                    <div class="flex items-center gap-6 text-sm text-gray-500 dark:text-gray-400">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                </path>
                                            </svg>
                                            <span
                                                class="font-medium">{{ __('Created: ') }}</span>{{ $campaign->start_date_formatted }}
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z">
                                                </path>
                                            </svg>
                                            <span
                                                class="font-medium">{{ __('Expires: ') }}</span>{{ $campaign->end_date_formatted }}
                                        </div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm text-gray-600 dark:text-gray-400 font-medium mb-2">
                                        {{ __('Budget used:') }}
                                        <span
                                            class="text-gray-900 dark:text-white font-bold">{{ $campaign->completed_reposts ?? 0 }}/{{ $campaign->total_credits_budget ?? 0 }}</span>
                                        {{ __('credits') }}
                                    </p>
                                    <div class="w-32 bg-gray-200 dark:bg-gray-600 rounded-full h-3 overflow-hidden">
                                        <div class="bg-gradient-to-r from-orange-500 to-orange-600 h-3 rounded-full transition-all duration-500"
                                            style="width: {{ $campaign->total_credits_budget > 0 ? ($campaign->completed_reposts / $campaign->total_credits_budget) * 100 : 0 }}%">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Statistics -->
                            <div class="grid grid-cols-3 gap-6 mt-6">
                                <div
                                    class="bg-gradient-to-br from-pink-50 to-pink-100 dark:from-pink-900/20 dark:to-pink-800/20 rounded-xl p-4 text-center border border-pink-200 dark:border-pink-800">
                                    <div class="flex items-center justify-center mb-2">
                                        <i data-lucide="refresh-ccw"
                                            class="w-6 h-6 text-pink-600 dark:text-pink-400"></i>
                                    </div>
                                    <div class="text-2xl font-bold text-gray-900 dark:text-white mb-1">
                                        {{ $campaign->reposts_count ?? 0 }}
                                    </div>
                                    <div class="text-sm font-medium text-gray-600 dark:text-gray-300">
                                        {{ __('Reposts') }}</div>
                                </div>

                                <div
                                    class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 rounded-xl p-4 text-center border border-blue-200 dark:border-blue-800">
                                    <div class="flex items-center justify-center mb-2">
                                        <i data-lucide="eye" class="w-6 h-6 text-blue-600 dark:text-blue-400"></i>
                                    </div>
                                    <div class="text-2xl font-bold text-gray-900 dark:text-white mb-1">
                                        {{ $campaign->plays_count ?? 0 }}
                                    </div>
                                    <div class="text-sm font-medium text-gray-600 dark:text-gray-300">
                                        {{ __('Plays') }}</div>
                                </div>

                                <div
                                    class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 rounded-xl p-4 text-center border border-green-200 dark:border-green-800">
                                    <div class="flex items-center justify-center mb-2">
                                        <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                                            </path>
                                        </svg>
                                    </div>
                                    <div class="text-2xl font-bold text-gray-900 dark:text-white mb-1">
                                        {{ $campaign->likes_count ?? 0 }}
                                    </div>
                                    <div class="text-sm font-medium text-gray-600 dark:text-gray-300">
                                        {{ __('Likes') }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="w-52 flex flex-col justify-end">
                            <div class="flex flex-col items-center gap-3">
                                <a href="#"
                                    class="w-full bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white px-4 py-3 rounded-xl flex items-center justify-center gap-2 transition-all duration-300 shadow-md hover:shadow-lg text-sm font-semibold">
                                    <i data-lucide="chart-no-axes-column" class="w-4 h-4"></i>
                                    {{ __('View Details') }}
                                </a>
                                <a href="#"
                                    class="w-full text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white px-4 py-3 rounded-xl flex items-center justify-center gap-2 transition-all duration-300 border-2 border-gray-300 dark:border-gray-600 hover:border-orange-500 dark:hover:border-orange-500 text-sm font-semibold bg-white dark:bg-gray-800 hover:bg-orange-50 dark:hover:bg-orange-900/20">
                                    <i data-lucide="plus" class="w-4 h-4"></i>
                                    {{ __('Add Credits') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div> --}}

                <div class=" rounded-lg border border-slate-700 overflow-hidden">
                    <div class="p-6 sm:p-8">
                        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                            <div class="flex flex-col sm:flex-row sm:items-start gap-4"><img
                                    src="https://images.pexels.com/photos/1540338/pexels-photo-1540338.jpeg?auto=compress&amp;cs=tinysrgb&amp;w=300&amp;h=300&amp;fit=crop"
                                    alt="Sexy - Fashion - Promo" class="w-20 h-20 rounded-lg mx-auto sm:mx-0">
                                <div class="flex-1">
                                    <div class="flex flex-col sm:flex-row sm:items-center sm:space-x-3 mb-2">
                                        <h3 class=" text-black dark:text-gray-100 font-semibold text-lg text-center sm:text-left">
                                            {{ $campaign->title }}
                                        </h3>
                                        <span
                                            class="px-2 py-1 rounded-full text-xs font-medium bg-green-500/20 text-green-400 text-center sm:text-left">Active</span>
                                    </div>
                                    <div
                                        class="flex flex-col sm:flex-row sm:items-center sm:space-x-6 text-sm text-slate-400 mb-4 space-y-2 sm:space-y-0">
                                        <div class="flex items-center space-x-1 justify-center sm:justify-start">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-calendar w-4 h-4">
                                                <path d="M8 2v4"></path>
                                                <path d="M16 2v4"></path>
                                                <rect width="18" height="18" x="3" y="4" rx="2">
                                                </rect>
                                                <path d="M3 10h18"></path>
                                            </svg><span>Created Apr 10, 2025</span>
                                        </div>
                                        <div class="flex items-center space-x-1 justify-center sm:justify-start">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-clock w-4 h-4">
                                                <circle cx="12" cy="12" r="10"></circle>
                                                <polyline points="12 6 12 12 16 14"></polyline>
                                            </svg><span>Expires Apr 17, 2025</span>
                                        </div>
                                    </div>
                                    <div class="mb-4">
                                        <div class="flex items-center justify-between text-sm mb-2"><span
                                                class="text-slate-400">Budget used: 14/20 credits</span><span
                                                class="text-orange-500 font-medium">70%</span></div>
                                        <div class="w-full bg-slate-700 rounded-full h-2">
                                            <div class="bg-gradient-to-r from-orange-500 to-orange-600 h-2 rounded-full transition-all duration-300"
                                                style="width: 70%;"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="flex flex-col lg:flex-column sm:items-center sm:space-x-2 gap-2">

                                <button
                                    class="bg-slate-700 hover:bg-slate-600 text-white px-4 py-2 rounded-lg font-medium transition-colors">Add
                                    Credits</button>
                                <button
                                    class="bg-slate-700 hover:bg-slate-600 text-white px-3 py-2 rounded-lg transition-colors"><svg
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-more-horizontal w-4 h-4">
                                        <circle cx="12" cy="12" r="1"></circle>
                                        <circle cx="19" cy="12" r="1"></circle>
                                        <circle cx="5" cy="12" r="1"></circle>
                                    </svg>
                                </button>
                                <button
                                    class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg font-medium transition-colors flex items-center justify-center space-x-2"><svg
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-eye w-4 h-4">
                                        <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"></path>
                                        <circle cx="12" cy="12" r="3"></circle>
                                    </svg><span>View Details</span>
                                </button>

                            </div>
                        </div>
                        <div class="grid grid-cols-3 gap-6 mt-6 pt-6 border-t border-slate-700">
                            <div class="text-center">
                                <div class="flex items-center justify-center mb-2"><svg
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide  lucide-trending-up w-5 h-5 text-orange-500 mr-2">
                                        <polyline points="22 7 13.5 15.5 8.5 10.5 2 17"></polyline>
                                        <polyline points="16 7 22 7 22 13"></polyline>
                                    </svg><span class="text-2xl font-bold   text-black dark:text-gray-100">24</span>
                                </div>
                                <p class="text-slate-400 text-sm">Reposts</p>
                            </div>
                            <div class="text-center">
                                <div class="flex items-center justify-center mb-2"><svg
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-eye w-5 h-5 text-blue-500 mr-2">
                                        <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"></path>
                                        <circle cx="12" cy="12" r="3"></circle>
                                    </svg><span class="text-2xl font-bold text-black dark:text-gray-100">342</span>
                                </div>
                                <p class="text-slate-400 text-sm">Plays</p>
                            </div>
                            <div class="text-center">
                                <div class="flex items-center justify-center mb-2"><svg
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-trending-up w-5 h-5 text-green-500 mr-2">
                                        <polyline points="22 7 13.5 15.5 8.5 10.5 2 17"></polyline>
                                        <polyline points="16 7 22 7 22 13"></polyline>
                                    </svg><span class="text-2xl font-bold text-black dark:text-gray-100">38</span>
                                </div>
                                <p class="text-slate-400 text-sm">Likes</p>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div
                    class="flex flex-col items-center justify-center py-20 text-center bg-white dark:bg-gray-800 rounded-2xl shadow-lg">
                    <div
                        class="w-20 h-20 bg-gradient-to-br from-orange-100 to-orange-200 dark:from-orange-900/20 dark:to-orange-800/20 rounded-full flex items-center justify-center mb-6">
                        <i data-lucide="megaphone" class="w-10 h-10 text-orange-600 dark:text-orange-400"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 dark:text-gray-100 mb-3">
                        {{ __('No campaigns found') }}
                    </h3>
                    <p class="text-gray-500 dark:text-gray-400 mb-6 max-w-md">
                        {{ __("You haven't started any campaigns yet. Click the 'New Campaign' button to create your first one!") }}
                    </p>
                    <button wire:click="toggleCampaignsModal" x-on:click="showCampaignsModal = true"
                        class="bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white px-6 py-3 rounded-xl flex items-center gap-2 transition-all duration-300 shadow-lg hover:shadow-xl">
                        <i data-lucide="plus" class="w-5 h-5"></i>
                        {{ __('Create Your First Campaign') }}
                    </button>
                </div>
            @endforelse
        </div>
    </div>

    {{-- ================================ Enhanced Modals ================================ --}}

    <!-- Track/Playlist Selection Modal -->
    <div x-data="{ showCampaignsModal: @entangle('showCampaignsModal').live }" x-show="showCampaignsModal" x-cloak
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
        class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4 z-50">

        <div
            class="w-full max-w-3xl mx-auto rounded-2xl shadow-2xl bg-white dark:bg-slate-800 border border-gray-200 dark:border-gray-700 flex flex-col max-h-[80vh] overflow-hidden">

            <!-- Modal Header -->
            <div
                class="flex justify-between items-center p-6 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-orange-50 to-orange-100 dark:from-orange-900/20 dark:to-orange-800/20">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-orange-500 rounded-xl flex items-center justify-center">
                        <i data-lucide="music" class="w-5 h-5 text-white"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ __('Choose a track or playlist') }}
                    </h2>
                </div>
                <button x-on:click="showCampaignsModal = false"
                    class="w-10 h-10 rounded-xl bg-white dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-all duration-200 flex items-center justify-center border border-gray-200 dark:border-gray-600">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            @if ($showCampaignsModal)
                <!-- Modal Tabs -->
                <div class="flex border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                    <button wire:click="selectModalTab('tracks')"
                        class="flex-1 py-4 px-6 text-center font-semibold text-base transition-all duration-300 ease-in-out border-b-2 hover:bg-white dark:hover:bg-gray-700 {{ $activeModalTab === 'tracks' ? 'border-orange-500 text-orange-600 bg-white dark:bg-gray-700' : 'border-transparent text-gray-600 dark:text-gray-400' }}">
                        <div class="flex items-center justify-center gap-2">
                            <i data-lucide="music" class="w-4 h-4"></i>
                            {{ __('Tracks') }}
                        </div>
                    </button>
                    <button wire:click="selectModalTab('playlists')"
                        class="flex-1 py-4 px-6 text-center font-semibold text-base transition-all duration-300 ease-in-out border-b-2 hover:bg-white dark:hover:bg-gray-700 {{ $activeModalTab === 'playlists' ? 'border-orange-500 text-orange-600 bg-white dark:bg-gray-700' : 'border-transparent text-gray-600 dark:text-gray-400' }}">
                        <div class="flex items-center justify-center gap-2">
                            <i data-lucide="list-music" class="w-4 h-4"></i>
                            {{ __('Playlists') }}
                        </div>
                    </button>
                </div>

                <!-- Modal Content -->
                <div class="flex-grow overflow-y-auto p-6">
                    @if ($activeModalTab === 'tracks')
                        <div class="space-y-3">
                            @forelse ($tracks as $track)
                                <div wire:click="toggleSubmitModal('track', {{ $track->id }})"
                                    class="p-4 flex items-center space-x-4 cursor-pointer hover:bg-gray-50 dark:hover:bg-slate-700 rounded-xl transition-all duration-200 border border-transparent hover:border-orange-200 dark:hover:border-orange-800 group">
                                    <div class="flex-shrink-0">
                                        <img class="h-14 w-14 rounded-xl object-cover shadow-md"
                                            src="{{ $track->artwork_url }}" alt="{{ $track->title }}" />
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p
                                            class="text-base font-semibold text-gray-900 dark:text-white truncate group-hover:text-orange-600 dark:group-hover:text-orange-400 transition-colors">
                                            {{ $track->title }}
                                        </p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 truncate">
                                            by
                                            <strong
                                                class="text-orange-600 dark:text-orange-400">{{ $track->author_username }}</strong>
                                            <span class="ml-2 text-xs text-gray-400">{{ $track->genre }}</span>
                                        </p>
                                        <span
                                            class="inline-block bg-gray-100 dark:bg-slate-600 text-xs px-3 py-1 rounded-full text-gray-700 dark:text-gray-300 mt-2 font-mono">{{ $track->isrc }}</span>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <i data-lucide="chevron-right"
                                            class="w-5 h-5 text-gray-400 group-hover:text-orange-500 transition-colors"></i>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-16 text-gray-500 dark:text-gray-400">
                                    <div
                                        class="w-16 h-16 bg-orange-100 dark:bg-orange-900/20 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <i data-lucide="music" class="w-8 h-8 text-orange-500"></i>
                                    </div>
                                    <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-2">No tracks
                                        found</h3>
                                    <p class="text-gray-500 dark:text-gray-400">Try uploading one first to get started.
                                    </p>
                                </div>
                            @endforelse
                        </div>
                    @elseif($activeModalTab === 'playlists')
                        <div class="space-y-3">
                            @forelse ($playlists as $playlist)
                                <div wire:click="toggleSubmitModal('playlist', {{ $playlist->id }})"
                                    class="p-4 flex items-center space-x-4 cursor-pointer hover:bg-gray-50 dark:hover:bg-slate-700 rounded-xl transition-all duration-200 border border-transparent hover:border-orange-200 dark:hover:border-orange-800 group">
                                    <div class="flex-shrink-0">
                                        <img class="h-14 w-14 rounded-xl object-cover shadow-md"
                                            src="{{ $playlist->artwork_url }}" alt="{{ $playlist->title }}" />
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p
                                            class="text-base font-semibold text-gray-900 dark:text-white truncate group-hover:text-orange-600 dark:group-hover:text-orange-400 transition-colors">
                                            {{ $playlist->title }}
                                        </p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 truncate">
                                            {{ $playlist->track_count }} {{ __('tracks') }}
                                        </p>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <i data-lucide="chevron-right"
                                            class="w-5 h-5 text-gray-400 group-hover:text-orange-500 transition-colors"></i>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-16 text-gray-500 dark:text-gray-400">
                                    <div
                                        class="w-16 h-16 bg-orange-100 dark:bg-orange-900/20 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <i data-lucide="list-music" class="w-8 h-8 text-orange-500"></i>
                                    </div>
                                    <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-2">No
                                        playlists found</h3>
                                    <p class="text-gray-500 dark:text-gray-400">Add one to get started with campaigns.
                                    </p>
                                </div>
                            @endforelse
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>

    <!-- Campaign Creation Modal -->
    <div x-data="{ showSubmitModal: @entangle('showSubmitModal').live }" x-show="showSubmitModal" x-cloak
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
        class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4 z-50">

        <div
            class="w-full max-w-4xl mx-auto rounded-2xl shadow-2xl bg-white dark:bg-slate-800 border border-gray-200 dark:border-gray-700 flex flex-col max-h-[85vh] overflow-hidden">

            <!-- Modal Header -->
            <div
                class="flex justify-between items-center p-6 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-orange-50 to-orange-100 dark:from-orange-900/20 dark:to-orange-800/20">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-orange-500 rounded-xl flex items-center justify-center">
                        <i data-lucide="megaphone" class="w-5 h-5 text-white"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ __('Create a campaign') }}
                    </h2>
                </div>
                <button x-on:click="showSubmitModal = false"
                    class="w-10 h-10 rounded-xl bg-white dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-all duration-200 flex items-center justify-center border border-gray-200 dark:border-gray-600">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <!-- Modal Content -->
            <div class="flex-grow overflow-y-auto p-6">
                <form wire:submit.prevent="submitCampaign" class="space-y-6">

                    @if ($activeModalTab === 'playlists')
                        <!-- Playlist Track Selection -->
                        <div
                            class="bg-gray-50 dark:bg-gray-900/50 rounded-xl p-6 border border-gray-200 dark:border-gray-700">
                            <h4 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                                <i data-lucide="list-music" class="w-5 h-5 text-orange-500"></i>
                                {{ __('Select a track from your playlist') }}
                            </h4>
                            <div
                                class="max-h-60 overflow-y-auto border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-800">
                                @forelse ($playlistTracks as $track)
                                    @if (is_array($track) &&
                                            isset($track['urn']) &&
                                            isset($track['title']) &&
                                            isset($track['user']) &&
                                            is_array($track['user']) &&
                                            isset($track['user']['username']))
                                        <div wire:click="$set('trackUrn', '{{ $track['urn'] }}')"
                                            class="flex items-center space-x-4 cursor-pointer hover:bg-gray-50 dark:hover:bg-slate-700 p-4 transition-all duration-200 border-b border-gray-100 dark:border-gray-700 last:border-b-0 @if ($trackUrn == $track['urn']) bg-orange-50 dark:bg-orange-900/30 border-l-4 border-l-orange-500 @endif">
                                            <div class="flex-shrink-0">
                                                <img class="h-12 w-12 rounded-lg object-cover shadow-sm"
                                                    src="{{ $track['artwork_url'] ?? asset('frontend/user/image/music-notes.jpg') }}"
                                                    alt="{{ $track['title'] }}"
                                                    onerror="this.src='{{ asset('frontend/user/image/music-notes.jpg') }}'" />
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p
                                                    class="text-sm font-semibold text-gray-900 dark:text-white truncate">
                                                    {{ $track['title'] }}
                                                </p>
                                                <p class="text-sm text-gray-500 dark:text-gray-400 truncate">
                                                    by <strong
                                                        class="text-orange-600 dark:text-orange-400">{{ $track['user']['username'] }}</strong>
                                                    @if (isset($track['genre']))
                                                        <span
                                                            class="ml-2 text-xs text-gray-400">{{ $track['genre'] }}</span>
                                                    @endif
                                                </p>
                                            </div>
                                            @if ($trackUrn == $track['urn'])
                                                <div class="flex-shrink-0">
                                                    <i data-lucide="check-circle" class="w-5 h-5 text-orange-500"></i>
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                @empty
                                    <div class="text-center py-12 text-gray-500 dark:text-gray-400">
                                        <i data-lucide="music-off" class="w-12 h-12 mx-auto mb-3 text-gray-400"></i>
                                        <p class="font-medium">No tracks found in this playlist.</p>
                                    </div>
                                @endforelse
                            </div>
                            @error('trackUrn')
                                <div class="mt-2 flex items-center gap-2 text-red-600 dark:text-red-400 text-sm">
                                    <i data-lucide="alert-circle" class="w-4 h-4"></i>
                                    <span>{{ $message }}</span>
                                </div>
                            @enderror
                        </div>
                    @endif

                    <!-- Campaign Details Form -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Campaign Name -->
                        <div class="space-y-2">
                            <label for="campaign_title"
                                class="block text-sm font-semibold text-gray-700 dark:text-gray-300 flex items-center gap-2">
                                <i data-lucide="type" class="w-4 h-4 text-orange-500"></i>
                                {{ __('Campaign name') }}
                            </label>
                            <input type="text" id="campaign_title" wire:model.live="title"
                                class="w-full rounded-xl border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-3 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-200"
                                placeholder="Enter campaign name">
                            @error('title')
                                <div class="flex items-center gap-2 text-red-600 dark:text-red-400 text-sm">
                                    <i data-lucide="alert-circle" class="w-4 h-4"></i>
                                    <span>{{ $message }}</span>
                                </div>
                            @enderror
                        </div>

                        <!-- Expiration Date -->
                        <div class="space-y-2">
                            <label for="campaign_end_date"
                                class="block text-sm font-semibold text-gray-700 dark:text-gray-300 flex items-center gap-2">
                                <i data-lucide="calendar" class="w-4 h-4 text-orange-500"></i>
                                {{ __('Campaign expiration date') }}
                            </label>
                            <input type="date" id="campaign_end_date" wire:model.live="endDate"
                                class="w-full rounded-xl border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-3 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-200">
                            @error('endDate')
                                <div class="flex items-center gap-2 text-red-600 dark:text-red-400 text-sm">
                                    <i data-lucide="alert-circle" class="w-4 h-4"></i>
                                    <span>{{ $message }}</span>
                                </div>
                            @enderror
                        </div>
                    </div>

                    <!-- Campaign Description -->
                    <div class="space-y-2">
                        <label for="campaign_description"
                            class="block text-sm font-semibold text-gray-700 dark:text-gray-300 flex items-center gap-2">
                            <i data-lucide="file-text" class="w-4 h-4 text-orange-500"></i>
                            {{ __('Campaign description') }}
                        </label>
                        <textarea id="campaign_description" wire:model.live="description" rows="4"
                            class="w-full rounded-xl border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-3 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-200 resize-none"
                            placeholder="Describe your campaign goals and target audience..."></textarea>
                        @error('description')
                            <div class="flex items-center gap-2 text-red-600 dark:text-red-400 text-sm">
                                <i data-lucide="alert-circle" class="w-4 h-4"></i>
                                <span>{{ $message }}</span>
                            </div>
                        @enderror
                    </div>

                    <!-- Budget and Target -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Total Budget -->
                        <div class="space-y-2">
                            <label for="campaign_total_budget"
                                class="block text-sm font-semibold text-gray-700 dark:text-gray-300 flex items-center gap-2">
                                <i data-lucide="coins" class="w-4 h-4 text-orange-500"></i>
                                {{ __('Campaign total budget (credits)') }}
                            </label>
                            <input type="number" id="campaign_total_budget" wire:model.live="totalBudget"
                                min="1"
                                class="w-full rounded-xl border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-3 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-200"
                                placeholder="Enter budget amount">
                            @error('totalBudget')
                                <div class="flex items-center gap-2 text-red-600 dark:text-red-400 text-sm">
                                    <i data-lucide="alert-circle" class="w-4 h-4"></i>
                                    <span>{{ $message }}</span>
                                </div>
                            @enderror
                        </div>

                        <!-- Target Reposts -->
                        <div class="space-y-2">
                            <label for="campaign_target_reposts"
                                class="block text-sm font-semibold text-gray-700 dark:text-gray-300 flex items-center gap-2">
                                <i data-lucide="target" class="w-4 h-4 text-orange-500"></i>
                                {{ __('Campaign target repost count') }}
                            </label>
                            <input type="number" id="campaign_target_reposts" wire:model.live="targetReposts"
                                min="1"
                                class="w-full rounded-xl border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-3 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-200"
                                placeholder="Enter target reposts">
                            @error('targetReposts')
                                <div class="flex items-center gap-2 text-red-600 dark:text-red-400 text-sm">
                                    <i data-lucide="alert-circle" class="w-4 h-4"></i>
                                    <span>{{ $message }}</span>
                                </div>
                            @enderror
                        </div>
                    </div>

                    <!-- Cost per Repost Display -->
                    @if ($totalBudget && $targetReposts && $totalBudget > 0 && $targetReposts > 0)
                        <div
                            class="bg-gradient-to-r from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 rounded-xl p-4 border border-blue-200 dark:border-blue-800">
                            <div class="flex items-center gap-3">
                                <i data-lucide="calculator" class="w-5 h-5 text-blue-600 dark:text-blue-400"></i>
                                <div>
                                    <p class="text-sm font-semibold text-blue-900 dark:text-blue-100">Cost per repost
                                    </p>
                                    <p class="text-lg font-bold text-blue-600 dark:text-blue-400">
                                        {{ number_format($totalBudget / $targetReposts, 2) }} credits
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Submit Button -->
                    <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                        <button type="submit"
                            class="w-full bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white font-bold py-4 px-6 rounded-xl transition-all duration-300 flex items-center justify-center gap-3 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none"
                            wire:loading.attr="disabled">
                            <span wire:loading.remove wire:target="submitCampaign">
                                <i data-lucide="rocket" class="w-5 h-5"></i>
                            </span>
                            <span wire:loading wire:target="submitCampaign">
                                <svg class="animate-spin w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                            </span>
                            <span wire:loading.remove wire:target="submitCampaign">{{ __('Create Campaign') }}</span>
                            <span wire:loading wire:target="submitCampaign">{{ __('Creating...') }}</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
