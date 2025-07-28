<section>

    <x-slot name="page_slug">campaigns</x-slot>

    <div class="p-6">
        <div class="flex justify-between items-start mb-5">
            <div>
                <h1 class="text-xl text-black dark:text-gray-100 font-bold">{{ __('My Campaigns') }}</h1>
            </div>
            <button wire:click="toggleCampaignsModal" x-on:click="showCampaignsModal = true"
                class="bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white px-4 py-2 rounded-xl flex items-center gap-3 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                <x-lucide-plus class="w-5 h-5" />
                {{ __('New Campaign') }}
            </button>
        </div>

        <div class="mb-8">
            <div class="border-b border-gray-200 dark:border-gray-700">
                <nav class="-mb-px flex space-x-8">
                    <button
                        class="tab-button @if ($activeMainTab === 'all') active border-b-2 border-orange-500 text-orange-600 @else border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 @endif py-3 px-2 text-sm font-semibold transition-all duration-200"
                        wire:click="setActiveTab('all')">
                        {{ __('All Campaigns') }}
                    </button>
                    <button
                        class="tab-button @if ($activeMainTab === 'active') active border-b-2 border-orange-500 text-orange-600 @else border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 @endif py-3 px-2 text-sm font-semibold transition-all duration-200"
                        wire:click="setActiveTab('active')">
                        {{ __('Active') }}
                    </button>
                    <button
                        class="tab-button @if ($activeMainTab === 'completed') active border-b-2 border-orange-500 text-orange-600 @else border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 @endif py-3 px-2 text-sm font-semibold transition-all duration-200"
                        wire:click="setActiveTab('completed')">
                        {{ __('Completed') }}
                    </button>
                </nav>
            </div>
        </div>

        <div class="space-y-6" id="campaigns-list">

            @forelse ($campaigns as $campaign)
                <div class=" rounded-lg border border-orange-600 overflow-hidden">
                    <div class="p-6 sm:p-8">
                        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                            <div class="flex flex-col sm:flex-row sm:items-start gap-4">
                                <img src="{{ soundcloud_image($campaign->music?->artwork_url) }}"
                                    alt="{{ $campaign->music?->title }}" class="w-20 h-20 rounded-lg mx-auto sm:mx-0">
                                <div class="flex-1">
                                    <div class="flex flex-col sm:flex-row sm:items-center sm:space-x-3 mb-2">
                                        <h3
                                            class=" text-black dark:text-gray-100 font-semibold text-lg text-center sm:text-left">
                                            {{ $campaign->title }}
                                        </h3>
                                        <span
                                            class="badge badge-soft {{ $campaign->status_color }} rounded-full">{{ $campaign->status_label }}</span>
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
                                            </svg><span>{{ __('Created') }}
                                                {{ $campaign->start_date_formatted }}</span>
                                        </div>
                                        <div class="flex items-center space-x-1 justify-center sm:justify-start">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-clock w-4 h-4">
                                                <circle cx="12" cy="12" r="10"></circle>
                                                <polyline points="12 6 12 12 16 14"></polyline>
                                            </svg><span>{{ __('Expires') }} {{ $campaign->end_date_formatted }}</span>
                                        </div>
                                    </div>
                                    <div class="mb-4">
                                        <div class="flex items-center justify-between text-sm mb-2"><span
                                                class="text-slate-400">{{ __('Budget used:') }}
                                                {{ $campaign->credits_spent }} /
                                                {{ $campaign->budget_credits }}
                                                {{ __('credits') }}</span><span class="text-orange-500 font-medium">
                                                {{ $campaign->budget_credits > 0 ? number_format(($campaign->credits_spent / $campaign->budget_credits) * 100, 2) : 0 }}%</span>
                                        </div>
                                        <div class="w-full bg-orange-600/20 rounded-full h-2">
                                            <div class="bg-gradient-to-r from-orange-500 to-orange-600 h-2 rounded-full transition-all duration-300"
                                                style="width: {{ $campaign->budget_credits > 0 ? ($campaign->credits_spent / $campaign->budget_credits) * 100 : 0 }}%;">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="flex flex-col lg:flex-column sm:items-center sm:space-x-2 gap-2">

                                {{-- Add Credits Button --}}
                                <button wire:click="openAddCreditModal({{ $campaign->id }})"
                                    class="bg-slate-700 hover:bg-slate-600 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                                    {{ __('Add Credits') }}
                                </button>


                                <div x-data="{ open: false }"
                                    class="relative text-left bg-slate-700 hover:bg-slate-600 text-white rounded-lg transition-colors flex justify-center">
                                    <button @click="open = !open"
                                        class="p-2 hover:bg-slate-600 text-white px-3 aline-center py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-offset-2 bg-slate-700 dark:bg-gray-700 left-4">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="lucide lucide-more-horizontal text-white w-5 h-5 text-gray-700 dark:text-gray-200"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <circle cx="12" cy="12" r="1"></circle>
                                            <circle cx="19" cy="12" r="1"></circle>
                                            <circle cx="5" cy="12" r="1"></circle>
                                        </svg>
                                    </button>

                                    <div x-show="open" @click.outside="open = false" x-transition x-cloak
                                        class="absolute right-11 mt-5 w-44 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-md shadow-lg z-50 overflow-hidden">
                                        <ul class="p-0 text-sm text-gray-700 dark:text-gray-200">

                                            <li>
                                                <button wire:click="openEditCampaignModal({{ $campaign->id }})"
                                                    @click="open = false"
                                                    class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 w-full">{{ __('Edit') }}</button>
                                            </li>

                                            {{-- cancel Button --}}
                                            <li>
                                                <button wire:click="openCancelWarningModal({{ $campaign->id }})"
                                                    @click="open = false"
                                                    class="block px-4 py-2 text-red-600 hover:bg-red-100  dark:hover:bg-red-950/50 w-full">{{ __('Cancel') }}</button>
                                            </li>

                                        </ul>
                                    </div>

                                </div>
                                <button wire:click="openViewDetailsModal({{ $campaign->id }})"
                                    class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg font-medium transition-colors flex items-center justify-center space-x-2">
                                    <x-lucide-eye class="w-5 h-5" />
                                    <span>{{ __('View Details') }}</span>
                                </button>

                            </div>
                        </div>
                        <div class="divider bg-orange-600/30 h-auto"></div>
                        <div class="grid grid-cols-3 gap-6">
                            <div class="text-center">
                                <div class="flex items-center justify-center mb-2"><svg
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide  lucide-trending-up w-5 h-5 text-orange-500 mr-2">
                                        <polyline points="22 7 13.5 15.5 8.5 10.5 2 17"></polyline>
                                        <polyline points="16 7 22 7 22 13"></polyline>
                                    </svg><span
                                        class="text-2xl font-bold   text-black dark:text-gray-100">{{ $campaign->completed_reposts ?? 0 }}</span>
                                </div>
                                <p class="text-slate-400 text-sm">{{ __('Reposts') }}</p>
                            </div>
                            <div class="text-center">
                                <div class="flex items-center justify-center mb-2"><svg
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-eye w-5 h-5 text-blue-500 mr-2">
                                        <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"></path>
                                        <circle cx="12" cy="12" r="3"></circle>
                                    </svg><span
                                        class="text-2xl font-bold text-black dark:text-gray-100">{{ $campaign->playback_count ?? 0 }}</span>
                                </div>
                                <p class="text-slate-400 text-sm">{{ __('Plays') }}</p>
                            </div>
                            <div class="text-center">
                                <div class="flex items-center justify-center mb-2"><svg
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-trending-up w-5 h-5 text-green-500 mr-2">
                                        <polyline points="22 7 13.5 15.5 8.5 10.5 2 17"></polyline>
                                        <polyline points="16 7 22 7 22 13"></polyline>
                                    </svg><span
                                        class="text-2xl font-bold text-black dark:text-gray-100">{{ $campaign->music?->likes_count ?? 0 }}</span>
                                </div>
                                <p class="text-slate-400 text-sm">{{ __('Likes') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                @if ($activeMainTab === 'all')
                    <div
                        class="flex flex-col items-center justify-center py-20 text-center bg-white dark:bg-gray-800 rounded-2xl shadow-lg">
                        <div
                            class="w-20 h-20 bg-gradient-to-br from-orange-100 to-orange-200 dark:from-orange-900/20 dark:to-orange-800/20 rounded-full flex items-center justify-center mb-6">
                            <x-lucide-megaphone class="w-10 h-10 text-orange-600 dark:text-orange-400" />
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 dark:text-gray-100 mb-3">
                            {{ __('No active campaigns found') }}
                        </h3>
                        <p class="text-gray-500 dark:text-gray-400 mb-6 max-w-md">
                            {{ __('Looks like there are no active campaigns right now. Why not start a new one and watch it grow?') }}
                        </p>
                        <button wire:click="toggleCampaignsModal" x-on:click="showCampaignsModal = true"
                            class="bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white px-6 py-3 rounded-xl flex items-center gap-2 transition-all duration-300 shadow-lg hover:shadow-xl">
                            <x-lucide-plus class="w-5 h-5" />
                            {{ __('Create Your First Campaign') }}
                        </button>
                    </div>
                @elseif ($activeMainTab === 'active')
                    <div
                        class="flex flex-col items-center justify-center py-20 text-center bg-white dark:bg-gray-800 rounded-2xl shadow-lg">
                        <div
                            class="w-20 h-20 bg-gradient-to-br from-orange-100 to-orange-200 dark:from-orange-900/20 dark:to-orange-800/20 rounded-full flex items-center justify-center mb-6">
                            <x-lucide-megaphone class="w-10 h-10 text-orange-600 dark:text-orange-400" />
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 dark:text-gray-100 mb-3">
                            {{ __('No active campaigns found') }}
                        </h3>
                        <p class="text-gray-500 dark:text-gray-400 mb-6 max-w-md">
                            {{ __("You haven't started any active campaigns yet. You can create new campaigns or view active campaigns.") }}
                        </p>
                    </div>
                @elseif ($activeMainTab === 'completed')
                    <div
                        class="flex flex-col items-center justify-center py-20 text-center bg-white dark:bg-gray-800 rounded-2xl shadow-lg">
                        <div
                            class="w-20 h-20 bg-gradient-to-br from-orange-100 to-orange-200 dark:from-orange-900/20 dark:to-orange-800/20 rounded-full flex items-center justify-center mb-6">
                            <x-lucide-megaphone class="w-10 h-10 text-orange-600 dark:text-orange-400" />
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 dark:text-gray-100 mb-3">
                            {{ __('Oops! No completed campaigns yet.') }}
                        </h3>
                        <p class="text-gray-500 dark:text-gray-400 mb-6 max-w-md">
                            {{ __('It looks like there are no completed campaigns at the moment. Start a campaign today and track your progress!') }}
                        </p>
                    </div>
                @endif
            @endforelse
        </div>
    </div>

    {{-- ================================ Modals ================================ --}}

    {{-- Choose a track or playlist Modal --}}
    <div x-data="{ showCampaignsModal: @entangle('showCampaignsModal').live }" x-show="showCampaignsModal" x-cloak
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
        class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4 z-50">

        <div
            class="w-full max-w-3xl mx-auto rounded-2xl shadow-2xl bg-white dark:bg-slate-800 border border-gray-200 dark:border-gray-700 flex flex-col max-h-[80vh] overflow-hidden">
            <div
                class="flex justify-between items-center p-6 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-orange-50 to-orange-100 dark:from-orange-900/20 dark:to-orange-800/20">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-orange-500 rounded-xl flex items-center justify-center">
                        <x-lucide-music class="w-5 h-5 text-white" />
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ __('Choose a track or playlist') }}
                    </h2>
                </div>
                <button x-on:click="showCampaignsModal = false"
                    class="w-10 h-10 rounded-xl bg-white dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-all duration-200 flex items-center justify-center border border-gray-200 dark:border-gray-600">
                    <x-lucide-x class="w-5 h-5" />
                </button>
            </div>

            @if ($showCampaignsModal)
                <div class="flex border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                    <button wire:click="selectModalTab('tracks')"
                        class="flex-1 py-4 px-6 text-center font-semibold text-base transition-all duration-300 ease-in-out border-b-2 hover:bg-white dark:hover:bg-gray-700 {{ $activeModalTab === 'tracks' ? 'border-orange-500 text-orange-600 bg-white dark:bg-gray-700' : 'border-transparent text-gray-600 dark:text-gray-400' }}">
                        <div class="flex items-center justify-center gap-2">
                            <x-lucide-music class="w-4 h-4" />
                            {{ __('Tracks') }}
                        </div>
                    </button>
                    <button wire:click="selectModalTab('playlists')"
                        class="flex-1 py-4 px-6 text-center font-semibold text-base transition-all duration-300 ease-in-out border-b-2 hover:bg-white dark:hover:bg-gray-700 {{ $activeModalTab === 'playlists' ? 'border-orange-500 text-orange-600 bg-white dark:bg-gray-700' : 'border-transparent text-gray-600 dark:text-gray-400' }}">
                        <div class="flex items-center justify-center gap-2">
                            <x-lucide-list-music class="w-4 h-4" />
                            {{ __('Playlists') }}
                        </div>
                    </button>
                </div>

                <div class="flex-grow overflow-y-auto p-6">
                    @if ($activeModalTab === 'tracks')
                        <div class="space-y-3">
                            @forelse ($tracks as $track)
                                <div wire:click="toggleSubmitModal('track', {{ $track->id }})"
                                    class="p-4 flex items-center space-x-4 cursor-pointer hover:bg-gray-50 dark:hover:bg-slate-700 rounded-xl transition-all duration-200 border border-transparent hover:border-orange-200 dark:hover:border-orange-800 group">
                                    <div class="flex-shrink-0">
                                        <img class="h-14 w-14 rounded-xl object-cover shadow-md"
                                            src="{{ soundcloud_image($track->artwork_url) }}"
                                            alt="{{ $track->title }}" />
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p
                                            class="text-base font-semibold text-gray-900 dark:text-white truncate group-hover:text-orange-600 dark:group-hover:text-orange-400 transition-colors">
                                            {{ $track->title }}
                                        </p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 truncate">
                                            {{ __('by') }}
                                            <strong
                                                class="text-orange-600 dark:text-orange-400">{{ $track->author_username }}</strong>
                                            <span class="ml-2 text-xs text-gray-400">{{ $track->genre }}</span>
                                        </p>
                                        <span
                                            class="bg-gray-100 dark:bg-slate-600 text-xs px-3 py-1 rounded-full text-gray-700 dark:text-gray-300 mt-2 font-mono flex items-start justify-center w-fit gap-3">
                                            <x-lucide-audio-lines class="w-4 h-4" /> {{ $track->playback_count }}</span>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <x-lucide-chevron-right
                                            class="w-5 h-5 text-gray-400 group-hover:text-orange-500 transition-colors" />
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-16 text-gray-500 dark:text-gray-400">
                                    <div
                                        class="w-16 h-16 bg-orange-100 dark:bg-orange-900/20 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <x-lucide-music class="w-8 h-8 text-orange-500" />
                                    </div>
                                    <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                        {{ __('No playlists found') }}
                                    </h3>
                                    <p class="text-gray-500 dark:text-gray-400">
                                        {{ __('Add one to get started with campaigns.') }}
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
                                            src="{{ soundcloud_image($playlist->artwork_url) }}"
                                            alt="{{ $playlist->title }}" />
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
                                        <x-lucide-chevron-right
                                            class="w-5 h-5 text-gray-400 group-hover:text-orange-500 transition-colors" />
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-16 text-gray-500 dark:text-gray-400">
                                    <div
                                        class="w-16 h-16 bg-orange-100 dark:bg-orange-900/20 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <x-lucide-list-music class="w-8 h-8 text-orange-500" />
                                    </div>
                                    <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                        {{ __('No playlists found') }}
                                    </h3>
                                    <p class="text-gray-500 dark:text-gray-400">
                                        {{ __('Add one to get started with campaigns.') }}
                                    </p>
                                </div>
                            @endforelse
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>

    {{-- Low Credit Warning Modal --}}
    <div x-data="{ showLowCreditWarningModal: @entangle('showLowCreditWarningModal').live }" x-show="showLowCreditWarningModal" x-cloak
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
        class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4 z-50">

        <div
            class="w-full max-w-md mx-auto rounded-2xl shadow-2xl bg-white dark:bg-slate-800 border border-gray-200 dark:border-gray-700 flex flex-col overflow-hidden">
            <div
                class="flex justify-between items-center p-6 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-red-50 to-red-100 dark:from-red-900/20 dark:to-red-800/20">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-red-500 rounded-xl flex items-center justify-center">
                        <i data-lucide="triangle-alert" class="w-5 h-5 text-white"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ __('Low Credit Warning') }}
                    </h2>
                </div>
                <button x-on:click="showLowCreditWarningModal = false"
                    class="w-10 h-10 rounded-xl bg-white dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-all duration-200 flex items-center justify-center border border-gray-200 dark:border-gray-600">
                    <x-lucide-x class="w-5 h-5" />
                </button>
            </div>

            <div class="p-6 text-center">
                <div
                    class="w-20 h-20 bg-red-100 dark:bg-red-900/20 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i data-lucide="wallet" class="w-10 h-10 text-red-600 dark:text-red-400"></i>
                </div>
                <p class="text-lg text-gray-700 dark:text-gray-300 mb-4">
                    {{ __('You need a minimum of 50 credits to create a campaign.') }}
                </p>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">
                    {{ __('Please add more credits to your account to proceed with campaign creation.') }}
                </p>
                <a href="{{ route('user.add-credits') }}" wire:navigate
                    class="inline-flex items-center justify-center w-full bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white font-bold py-3 px-6 rounded-xl transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                    <i data-lucide="plus" class="w-5 h-5 inline mr-2"></i>
                    {{ __('Buy Credits Now') }}
                </a>
            </div>
        </div>
    </div>

    {{-- Create campaign (submit) Modal --}}
    <div x-data="{ showSubmitModal: @entangle('showSubmitModal').live }" x-show="showSubmitModal" x-cloak
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
        class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4 z-50">

        <div
            class="w-full max-w-4xl mx-auto rounded-2xl shadow-2xl bg-white dark:bg-slate-800 border border-gray-200 dark:border-gray-700 flex flex-col max-h-[85vh] overflow-hidden">
            <div
                class="flex justify-between items-center p-6 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-orange-50 to-orange-100 dark:from-orange-900/20 dark:to-orange-800/20">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-orange-500 rounded-xl flex items-center justify-center">
                        <i data-lucide="audio-lines" class="w-5 h-5 text-white"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ __('Create a campaign') }}
                    </h2>
                </div>
                <button x-on:click="showSubmitModal = false"
                    class="w-10 h-10 rounded-xl bg-white dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-all duration-200 flex items-center justify-center border border-gray-200 dark:border-gray-600">
                    <x-lucide-x class="w-5 h-5" />
                </button>
            </div>

            <div class="flex-grow overflow-y-auto p-6">
                <form wire:submit.prevent="submitCampaign" class="space-y-6">
                    @if ($activeModalTab === 'playlists')
                        <div
                            class="bg-gray-50 dark:bg-gray-900/50 rounded-xl p-6 border border-gray-200 dark:border-gray-700">
                            <h4 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                                <x-lucide-list-music class="w-5 h-5 text-orange-500" />
                                {{ __('Select a track from your playlist') }}
                            </h4>
                            <div
                                class="max-h-60 overflow-y-auto border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-800">
                                @forelse ($playlistTracks as $track)
                                    @if (is_array($track) &&
                                            isset($track['id']) &&
                                            isset($track['title']) &&
                                            isset($track['user']) &&
                                            is_array($track['user']) &&
                                            isset($track['user']['username']))
                                        <div wire:click="$set('musicId', '{{ $track['id'] }}')"
                                            class="flex items-center space-x-4 cursor-pointer hover:bg-gray-50 dark:hover:bg-slate-700 p-4 transition-all duration-200 border-b border-gray-100 dark:border-gray-700 last:border-b-0 @if ($musicId == $track['id']) bg-orange-50 dark:bg-orange-900/30 border-l-4 border-l-orange-500 @endif">
                                            <div class="flex-shrink-0">
                                                <img class="h-12 w-12 rounded-lg object-cover shadow-sm"
                                                    src="{{ soundcloud_image($track['artwork_url']) }}"
                                                    alt="{{ $track['title'] }}"
                                                    onerror="this.src='{{ asset('frontend/user/image/music-notes.jpg') }}'" />
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p
                                                    class="text-sm font-semibold text-gray-900 dark:text-white truncate">
                                                    {{ $track['title'] }}
                                                </p>
                                                <p class="text-sm text-gray-500 dark:text-gray-400 truncate">
                                                    {{ __('by') }} <strong
                                                        class="text-orange-600 dark:text-orange-400">{{ $track['user']['username'] }}</strong>
                                                    @if (isset($track['genre']))
                                                        <span
                                                            class="ml-2 text-xs text-gray-400">{{ $track['genre'] }}</span>
                                                    @endif
                                                </p>
                                            </div>
                                            @if ($musicId == $track['id'])
                                                <div class="flex-shrink-0">
                                                    <i data-lucide="check-circle" class="w-5 h-5 text-orange-500"></i>
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                @empty
                                    <div class="text-center py-12 text-gray-500 dark:text-gray-400">
                                        <i data-lucide="music-off" class="w-12 h-12 mx-auto mb-3 text-gray-400"></i>
                                        <p class="font-medium">{{ __('No tracks found in this playlist.') }}</p>
                                    </div>
                                @endforelse
                            </div>
                            @error('musicId')
                                <div class="mt-2 flex items-center gap-2 text-red-600 dark:text-red-400 text-sm">
                                    <i data-lucide="alert-circle" class="w-4 h-4"></i>
                                    <span>{{ $message }}</span>
                                </div>
                            @enderror
                        </div>
                    @endif

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label for="campaign_title"
                                class="text-sm font-semibold text-gray-700 dark:text-gray-300 flex items-center gap-2">
                                <i data-lucide="type" class="w-4 h-4 text-orange-500"></i>
                                {{ __('Campaign name') }}
                            </label>
                            <input type="text" id="campaign_title" wire:model.live="title"
                                class="w-full rounded-xl border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-3 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-200"
                                placeholder="{{ __('Enter campaign name') }}">
                            @error('title')
                                <div class="flex items-center gap-2 text-red-600 dark:text-red-400 text-sm">
                                    <i data-lucide="alert-circle" class="w-4 h-4"></i>
                                    <span>{{ $message }}</span>
                                </div>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="campaign_end_date"
                                class="text-sm font-semibold text-gray-700 dark:text-gray-300 flex items-center gap-2">
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

                    <div class="space-y-2">
                        <label for="campaign_description"
                            class="text-sm font-semibold text-gray-700 dark:text-gray-300 flex items-center gap-2">
                            <i data-lucide="file-text" class="w-4 h-4 text-orange-500"></i>
                            {{ __('Campaign description') }}
                        </label>
                        <textarea id="campaign_description" wire:model.live="description" rows="4"
                            class="w-full rounded-xl border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-3 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-200 resize-none"
                            placeholder="{{ __('Describe your campaign goals and target audience...') }}"></textarea>
                        @error('description')
                            <div class="flex items-center gap-2 text-red-600 dark:text-red-400 text-sm">
                                <i data-lucide="alert-circle" class="w-4 h-4"></i>
                                <span>{{ $message }}</span>
                            </div>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label for="campaign_cost_per_repost"
                                class="text-sm font-semibold text-gray-700 dark:text-gray-300 flex items-center gap-2">
                                <i data-lucide="coins" class="w-4 h-4 text-orange-500"></i>
                                {{ __('Cost per repost (credits)') }}
                            </label>
                            <input type="number" id="campaign_cost_per_repost" wire:model.live="costPerRepost"
                                min="1"
                                class="w-full rounded-xl border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-3 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-200"
                                placeholder="{{ __('Enter cost per repost') }}">
                            @error('costPerRepost')
                                <div class="flex items-center gap-2 text-red-600 dark:text-red-400 text-sm">
                                    <i data-lucide="alert-circle" class="w-4 h-4"></i>
                                    <span>{{ $message }}</span>
                                </div>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="campaign_target_reposts"
                                class="text-sm font-semibold text-gray-700 dark:text-gray-300 flex items-center gap-2">
                                <i data-lucide="target" class="w-4 h-4 text-orange-500"></i>
                                {{ __('Campaign target repost count') }}
                            </label>
                            <input type="number" id="campaign_target_reposts" wire:model.live="targetReposts"
                                min="1"
                                class="w-full rounded-xl border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-3 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-200"
                                placeholder="{{ __('Enter target reposts') }}">
                            @error('targetReposts')
                                <div class="flex items-center gap-2 text-red-600 dark:text-red-400 text-sm">
                                    <i data-lucide="alert-circle" class="w-4 h-4"></i>
                                    <span>{{ $message }}</span>
                                </div>
                            @enderror
                        </div>
                    </div>

                    {{-- Budget Warning Display --}}
                    @if ($showBudgetWarning)
                        <div
                            class="bg-gradient-to-r from-red-50 to-red-100 dark:from-red-900/20 dark:to-red-800/20 rounded-xl p-4 border border-red-200 dark:border-red-800">
                            <div class="flex items-center gap-3">
                                <i data-lucide="alert-triangle" class="w-5 h-5 text-red-600 dark:text-red-400"></i>
                                <div>
                                    <p class="text-sm font-semibold text-red-900 dark:text-red-100">
                                        {{ __('Budget Warning') }}
                                    </p>
                                    <p class="text-sm text-red-600 dark:text-red-400">
                                        {{ $budgetWarningMessage }}
                                    </p>
                                </div>
                                @if (str_contains($budgetWarningMessage, 'need') && str_contains($budgetWarningMessage, 'more credits'))
                                    <a href="{{ route('user.add-credits') }}" wire:navigate
                                        class="ml-auto bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded-lg text-xs font-medium transition-colors">
                                        {{ __('Buy Credits') }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endif

                    {{-- Budget Display --}}
                    @if ($costPerRepost && $targetReposts && $costPerRepost > 0 && $targetReposts > 0 && !$showBudgetWarning)
                        <div
                            class="bg-gradient-to-r from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 rounded-xl p-4 border border-blue-200 dark:border-blue-800">
                            <div class="flex items-center gap-3">
                                <i data-lucide="calculator" class="w-5 h-5 text-blue-600 dark:text-blue-400"></i>
                                <div>
                                    <p class="text-sm font-semibold text-blue-900 dark:text-blue-100">
                                        {{ __('Estimated campaign cost') }}
                                    </p>
                                    <p class="text-lg font-bold text-blue-600 dark:text-blue-400">
                                        {{ number_format($costPerRepost * $targetReposts) }} {{ __('credits') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                        <button type="submit"
                            class="w-full transition-all duration-300 flex items-center justify-center gap-3 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 font-bold py-4 px-6 rounded-xl {{ $canSubmit ? 'bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white' : 'bg-gray-300 dark:bg-gray-600 text-gray-500 dark:text-gray-400 cursor-not-allowed' }}"
                            wire:loading.attr="disabled" @if (!$canSubmit) disabled @endif>
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
                            <span wire:loading.remove wire:target="submitCampaign">
                                {{ __('Create Campaign') }}
                            </span>
                            <span wire:loading wire:target="submitCampaign">
                                {{ __('Creating...') }}
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Add Credit Modal --}}
    <div x-data="{ showAddCreditModal: @entangle('showAddCreditModal').live }" x-show="showAddCreditModal" x-cloak
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
        class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4 z-50">

        <div
            class="w-full max-w-xl mx-auto rounded-2xl shadow-2xl bg-white dark:bg-slate-800 border border-gray-200 dark:border-gray-700 flex flex-col overflow-hidden">
            <div
                class="flex justify-between items-center p-6 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-orange-50 to-orange-100 dark:from-orange-900/20 dark:to-orange-800/20">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-orange-500 rounded-xl flex items-center justify-center">
                        <i data-lucide="wallet" class="w-5 h-5 text-white"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ __('Add Credits to Campaign') }}
                    </h2>
                </div>
                <button x-on:click="showAddCreditModal = false"
                    class="w-10 h-10 rounded-xl bg-white dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-all duration-200 flex items-center justify-center border border-gray-200 dark:border-gray-600">
                    <x-lucide-x class="w-5 h-5" />
                </button>
            </div>

            <div class="flex-grow overflow-y-auto p-6">
                <form wire:submit.prevent="addCreditsToCampaign" class="space-y-6">
                    <div class="space-y-2">
                        <label for="add_credit_cost_per_repost"
                            class="text-sm font-semibold text-gray-700 dark:text-gray-300 flex items-center gap-2">
                            <i data-lucide="coins" class="w-4 h-4 text-orange-500"></i>
                            {{ __('New Cost per Repost (credits)') }}
                        </label>
                        <input type="number" id="add_credit_cost_per_repost"
                            wire:model.live="addCreditCostPerRepost" min="1"
                            class="w-full rounded-xl border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-3 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-200"
                            placeholder="{{ __('Enter new cost per repost') }}">
                        @error('addCreditCostPerRepost')
                            <div class="flex items-center gap-2 text-red-600 dark:text-red-400 text-sm">
                                <i data-lucide="alert-circle" class="w-4 h-4"></i>
                                <span>{{ $message }}</span>
                            </div>
                        @enderror
                    </div>

                    {{-- Budget Warning Display --}}
                    @if ($showBudgetWarning)
                        <div
                            class="bg-gradient-to-r from-red-50 to-red-100 dark:from-red-900/20 dark:to-red-800/20 rounded-xl p-4 border border-red-200 dark:border-red-800">
                            <div class="flex items-center gap-3 flex-col">
                                <div class="flex items-center gap-3">
                                    <x-lucide-alert-triangle class="w-5 h-5 text-red-600 dark:text-red-400" />
                                    <div>
                                        <p class="text-sm font-semibold text-red-900 dark:text-red-100">
                                            {{ __('Budget Warning') }}
                                        </p>
                                        <p class="text-sm text-red-600 dark:text-red-400">
                                            {{ $budgetWarningMessage }}
                                        </p>
                                    </div>
                                </div>
                                @if (str_contains($budgetWarningMessage, 'need') && str_contains($budgetWarningMessage, 'more credits'))
                                    <a href="{{ route('user.add-credits') }}" wire:navigate
                                        class="ml-auto bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded-lg text-xs font-medium transition-colors">
                                        {{ __('Buy Credits') }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endif

                    {{-- Budget Display --}}
                    @if (
                        $addCreditCostPerRepost &&
                            $addCreditTargetReposts &&
                            $addCreditCostPerRepost > 0 &&
                            $addCreditTargetReposts > 0 &&
                            !$showBudgetWarning)
                        <div
                            class="bg-gradient-to-r from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 rounded-xl p-4 border border-blue-200 dark:border-blue-800">
                            <div class="flex items-center gap-3">
                                <i data-lucide="calculator" class="w-5 h-5 text-blue-600 dark:text-blue-400"></i>
                                <div>
                                    <p class="text-sm font-semibold text-blue-900 dark:text-blue-100">
                                        {{ __('New Total Campaign Budget') }}
                                    </p>
                                    <p class="text-lg font-bold text-blue-600 dark:text-blue-400">
                                        {{ number_format($addCreditCostPerRepost * $addCreditTargetReposts) }}
                                        {{ __('credits') }}
                                    </p>
                                    @if ($addCreditCreditsNeeded > 0)
                                        <p class="text-sm text-blue-600 dark:text-blue-400">
                                            {{ __('Additional credits needed:') }}
                                            {{ number_format($addCreditCreditsNeeded) }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                        <button type="submit"
                            class="w-full transition-all duration-300 flex items-center justify-center gap-3 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 font-bold py-4 px-6 rounded-xl {{ $canSubmit ? 'bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white' : 'bg-gray-300 dark:bg-gray-600 text-gray-500 dark:text-gray-400 cursor-not-allowed' }}"
                            wire:loading.attr="disabled" @if (!$canSubmit) disabled @endif>
                            <i data-lucide="plus" class="w-4 h-4"></i>
                            <span wire:loading.remove wire:target="addCreditsToCampaign">
                                {{ __('Add Credits') }}
                            </span>
                            <span wire:loading wire:target="addCreditsToCampaign">
                                {{ __('Adding...') }}
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Edit Campaign Modal --}}
    <div x-data="{ showEditCampaignModal: @entangle('showEditCampaignModal').live }" x-show="showEditCampaignModal" x-cloak
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
        class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4 z-50">

        <div
            class="w-full max-w-4xl mx-auto rounded-2xl shadow-2xl bg-white dark:bg-slate-800 border border-gray-200 dark:border-gray-700 flex flex-col max-h-[85vh] overflow-hidden">
            <div
                class="flex justify-between items-center p-6 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-orange-50 to-orange-100 dark:from-orange-900/20 dark:to-orange-800/20">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-orange-500 rounded-xl flex items-center justify-center">
                        <i data-lucide="edit" class="w-5 h-5 text-white"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ __('Edit Campaign') }}
                    </h2>
                </div>
                <button x-on:click="showEditCampaignModal = false"
                    class="w-10 h-10 rounded-xl bg-white dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-all duration-200 flex items-center justify-center border border-gray-200 dark:border-gray-600">
                    <x-lucide-x class="w-5 h-5" />
                </button>
            </div>

            <div class="flex-grow overflow-y-auto p-6">
                <form wire:submit.prevent="updateCampaign" class="space-y-6">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label for="edit_campaign_title"
                                class="text-sm font-semibold text-gray-700 dark:text-gray-300 flex items-center gap-2">
                                <i data-lucide="type" class="w-4 h-4 text-orange-500"></i>
                                {{ __('Campaign name') }}
                            </label>
                            <input type="text" id="edit_campaign_title" wire:model.live="editTitle"
                                class="w-full rounded-xl border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-3 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-200"
                                placeholder="{{ __('Enter campaign name') }}">
                            @error('editTitle')
                                <div class="flex items-center gap-2 text-red-600 dark:text-red-400 text-sm">
                                    <i data-lucide="alert-circle" class="w-4 h-4"></i>
                                    <span>{{ $message }}</span>
                                </div>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="edit_campaign_end_date"
                                class="text-sm font-semibold text-gray-700 dark:text-gray-300 flex items-center gap-2">
                                <i data-lucide="calendar" class="w-4 h-4 text-orange-500"></i>
                                {{ __('Campaign expiration date') }}
                            </label>
                            <input type="date" id="edit_campaign_end_date" wire:model.live="editEndDate"
                                class="w-full rounded-xl border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-3 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-200">
                            @error('editEndDate')
                                <div class="flex items-center gap-2 text-red-600 dark:text-red-400 text-sm">
                                    <i data-lucide="alert-circle" class="w-4 h-4"></i>
                                    <span>{{ $message }}</span>
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label for="edit_campaign_description"
                            class="text-sm font-semibold text-gray-700 dark:text-gray-300 flex items-center gap-2">
                            <i data-lucide="file-text" class="w-4 h-4 text-orange-500"></i>
                            {{ __('Campaign description') }}
                        </label>
                        <textarea id="edit_campaign_description" wire:model.live="editDescription" rows="4"
                            class="w-full rounded-xl border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-3 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-200 resize-none"
                            placeholder="{{ __('Describe your campaign goals and target audience...') }}"></textarea>
                        @error('editDescription')
                            <div class="flex items-center gap-2 text-red-600 dark:text-red-400 text-sm">
                                <i data-lucide="alert-circle" class="w-4 h-4"></i>
                                <span>{{ $message }}</span>
                            </div>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label for="edit_campaign_cost_per_repost"
                                class="text-sm font-semibold text-gray-700 dark:text-gray-300 flex items-center gap-2">
                                <i data-lucide="coins" class="w-4 h-4 text-orange-500"></i>
                                {{ __('Cost per repost (credits)') }}
                            </label>
                            <input type="number" id="edit_campaign_cost_per_repost"
                                wire:model.live="editCostPerRepost" min="1"
                                class="w-full rounded-xl border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-3 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-200"
                                placeholder="{{ __('Enter cost per repost') }}">
                            @error('editCostPerRepost')
                                <div class="flex items-center gap-2 text-red-600 dark:text-red-400 text-sm">
                                    <i data-lucide="alert-circle" class="w-4 h-4"></i>
                                    <span>{{ $message }}</span>
                                </div>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="edit_campaign_target_reposts"
                                class="text-sm font-semibold text-gray-700 dark:text-gray-300 flex items-center gap-2">
                                <i data-lucide="target" class="w-4 h-4 text-orange-500"></i>
                                {{ __('Campaign target repost count') }}
                            </label>
                            <input type="number" id="edit_campaign_target_reposts"
                                wire:model.live="editTargetReposts" min="1"
                                class="w-full rounded-xl border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-3 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-200"
                                placeholder="{{ __('Enter target reposts') }}">
                            @error('editTargetReposts')
                                <div class="flex items-center gap-2 text-red-600 dark:text-red-400 text-sm">
                                    <i data-lucide="alert-circle" class="w-4 h-4"></i>
                                    <span>{{ $message }}</span>
                                </div>
                            @enderror
                        </div>
                    </div>

                    {{-- Budget Warning Display --}}
                    @if ($showBudgetWarning)
                        <div
                            class="bg-gradient-to-r from-red-50 to-red-100 dark:from-red-900/20 dark:to-red-800/20 rounded-xl p-4 border border-red-200 dark:border-red-800">
                            <div class="flex items-center gap-3">
                                <i data-lucide="alert-triangle" class="w-5 h-5 text-red-600 dark:text-red-400"></i>
                                <div>
                                    <p class="text-sm font-semibold text-red-900 dark:text-red-100">
                                        {{ __('Budget Warning') }}
                                    </p>
                                    <p class="text-sm text-red-600 dark:text-red-400">
                                        {{ $budgetWarningMessage }}
                                    </p>
                                </div>
                                @if (str_contains($budgetWarningMessage, 'need') && str_contains($budgetWarningMessage, 'more credits'))
                                    <a href="{{ route('user.add-credits') }}" wire:navigate
                                        class="ml-auto bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded-lg text-xs font-medium transition-colors">
                                        {{ __('Buy Credits') }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endif

                    {{-- Budget Display --}}
                    @if ($editCostPerRepost && $editTargetReposts && $editCostPerRepost > 0 && $editTargetReposts > 0 && !$showBudgetWarning)
                        <div
                            class="bg-gradient-to-r from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 rounded-xl p-4 border border-blue-200 dark:border-blue-800">
                            <div class="flex items-center gap-3">
                                <i data-lucide="calculator" class="w-5 h-5 text-blue-600 dark:text-blue-400"></i>
                                <div>
                                    <p class="text-sm font-semibold text-blue-900 dark:text-blue-100">
                                        {{ __('New Total Campaign Budget') }}
                                    </p>
                                    <p class="text-lg font-bold text-blue-600 dark:text-blue-400">
                                        {{ number_format($editCostPerRepost * $editTargetReposts) }}
                                        {{ __('credits') }}
                                    </p>
                                    @if ($editOriginalBudget && $editCostPerRepost * $editTargetReposts > $editOriginalBudget)
                                        <p class="text-sm text-blue-600 dark:text-blue-400">
                                            {{ __('Additional credits needed:') }}
                                            {{ number_format($editCostPerRepost * $editTargetReposts - $editOriginalBudget) }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                        <button type="submit"
                            class="w-full transition-all duration-300 flex items-center justify-center gap-3 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 font-bold py-4 px-6 rounded-xl {{ $canSubmit ? 'bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white' : 'bg-gray-300 dark:bg-gray-600 text-gray-500 dark:text-gray-400 cursor-not-allowed' }}"
                            wire:loading.attr="disabled" @if (!$canSubmit) disabled @endif>
                            <span wire:loading.remove wire:target="updateCampaign">
                                <i data-lucide="save" class="w-5 h-5"></i>
                            </span>
                            <span wire:loading wire:target="updateCampaign">
                                <svg class="animate-spin w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                            </span>
                            <span wire:loading.remove wire:target="updateCampaign">
                                {{ __('Save Changes') }}
                            </span>
                            <span wire:loading wire:target="updateCampaign">{{ __('Saving...') }}</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Cancel Warning Modal --}}
    <div x-data="{ showCancelWarningModal: @entangle('showCancelWarningModal').live }" x-show="showCancelWarningModal" x-cloak
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
        class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4 z-50">

        <div
            class="w-full max-w-lg mx-auto rounded-2xl shadow-2xl bg-white dark:bg-slate-800 border border-gray-200 dark:border-gray-700 flex flex-col overflow-hidden">
            <div
                class="flex justify-between items-center p-6 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-red-50 to-red-100 dark:from-red-900/20 dark:to-red-800/20">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-red-500 rounded-xl flex items-center justify-center">
                        <x-lucide-alert-triangle class="w-5 h-5 text-white" />
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ __('Confirm Cancellation') }}
                    </h2>
                </div>
                <button x-on:click="showCancelWarningModal = false"
                    class="w-10 h-10 rounded-xl bg-white dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-all duration-200 flex items-center justify-center border border-gray-200 dark:border-gray-600">
                    <x-lucide-x class="w-5 h-5" />
                </button>
            </div>

            <div class="p-6 text-center">
                <div
                    class="w-20 h-20 bg-red-100 dark:bg-red-900/20 rounded-full flex items-center justify-center mx-auto mb-6">
                    <x-lucide-trash-2 class="w-10 h-10 text-red-600 dark:text-red-400" />
                </div>
                <p class="text-lg text-gray-700 dark:text-gray-300 mb-4">
                    {{ __('Are you sure you want to cancel this campaign? You will not be able to recover it.') }}
                </p>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">
                    {{ __('If you cancel this campaign, you will receive a 50% refund of the remaining budget: :amount credits.', ['amount' => number_format($refundAmount)]) }}
                </p>
                <div class="flex justify-center gap-4">
                    <button type="button" x-on:click="showCancelWarningModal = false"
                        class="flex-1 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 font-bold py-3 px-6 rounded-xl transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        {{ __('Keep Campaign') }}
                    </button>
                    <button wire:click="cancelCampaign"
                        class="flex-1 bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white font-bold py-3 px-6 rounded-xl transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5"
                        wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="cancelCampaign">{{ __('Confirm') }}</span>
                        <span wire:loading wire:target="cancelCampaign">{{ __('Cancelling...') }}</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Already Cancel Warning Modal --}}
    <div x-data="{ showAlreadyCancelledModal: @entangle('showAlreadyCancelledModal').live }" x-show="showAlreadyCancelledModal" x-cloak
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
        class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4 z-50">

        <div
            class="w-full max-w-lg mx-auto rounded-2xl shadow-2xl bg-white dark:bg-slate-800 border border-gray-200 dark:border-gray-700 flex flex-col overflow-hidden">
            <div
                class="flex justify-between items-center p-6 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-red-50 to-red-100 dark:from-red-900/20 dark:to-red-800/20">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-red-500 rounded-xl flex items-center justify-center">
                        <x-lucide-alert-triangle class="w-5 h-5 text-white" />
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ __('Campaign Cancelled') }}
                    </h2>
                </div>
                <button x-on:click="showAlreadyCancelledModal = false"
                    class="w-10 h-10 rounded-xl bg-white dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-all duration-200 flex items-center justify-center border border-gray-200 dark:border-gray-600">
                    <x-lucide-x class="w-5 h-5" />
                </button>
            </div>

            <div class="p-6 text-center">
                <div
                    class="w-20 h-20 bg-red-100 dark:bg-red-900/20 rounded-full flex items-center justify-center mx-auto mb-6">
                    <x-lucide-alert-triangle class="w-10 h-10 text-red-600 dark:text-red-400" />
                </div>
                <p class="text-lg text-gray-700 dark:text-gray-300 mb-4">
                    {{ __('This campaign has already been cancelled.') }}
                </p>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">
                    {{ __('This campaign has already been cancelled. You cannot make any changes to this campaign.') }}
                </p>
            </div>
        </div>
    </div>

    {{-- Details Modal --}}
    <div x-data="{ showDetailsModal: @entangle('showDetailsModal').live }" x-show="showDetailsModal" x-cloak
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
        class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4 z-50">

        <div
            class="w-full max-w-5xl mx-auto rounded-2xl shadow-2xl bg-white dark:bg-slate-800 border border-gray-200 dark:border-gray-700 flex flex-col overflow-hidden">

            @if ($showDetailsModal)
                <div
                    class="flex justify-between items-center p-6 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-red-50 to-red-100 dark:from-red-900/20 dark:to-red-800/20">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-orange-500 rounded-xl flex items-center justify-center">
                            <x-lucide-info class="w-5 h-5 text-white" />
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                            {{ __('Campaign Details') }}
                        </h2>
                    </div>
                    <button wire:click="closeViewDetailsModal()"
                        class="w-10 h-10 rounded-xl bg-white dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-all duration-200 flex items-center justify-center border border-gray-200 dark:border-gray-600">
                        <x-lucide-x class="w-5 h-5" />
                    </button>
                </div>

                <div class="p-6 text-center max-h-[80vh] overflow-y-auto">
                    <div class="flex flex-col md:flex-row gap-6">
                        <div class="w-full md:w-1/3 max-w-44 rounded-xl shadow-lg overflow-hidden">
                            <img class="w-full h-full object-cover"
                                src="{{ soundcloud_image($campaign->music?->artwork_url) }}">
                        </div>

                        <div class="flex-1">
                            <h2 class="text-3xl font-bold mb-2 text-gray-900 dark:text-white">
                                {{ $campaign->title ?? $campaign->music?->title }}</h2>
                            <p class="text-orange-500 mb-2">by {{ $campaign->user?->name ?? 'Unknown' }}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                                Genre: <span
                                    class="text-black dark:text-white">{{ $campaign->music?->genre ?? 'Unknown' }}</span>
                            </p>
                            <p class="text-gray-700 dark:text-gray-300 text-sm mb-4">
                                {{ $campaign->description ?? 'No description provided' }}
                            </p>

                            <div class="bg-gray-200 dark:bg-gray-800 p-4 rounded-lg">
                                <p class="text-gray-600 dark:text-gray-400 text-sm mb-2">Repost Progress:</p>
                                <div class="w-full h-3 bg-gray-300 dark:bg-gray-700 rounded-full">
                                    <div class="h-3 bg-orange-500 rounded-full"
                                        style="width: {{ ($campaign->completed_reposts / $campaign->target_reposts) * 100 }}%">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Campaign Stats -->
                    <div class="mt-10 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                        <div class="bg-gray-100 dark:bg-slate-700 p-5 rounded-lg shadow">
                            <h4 class="text-gray-600 dark:text-gray-400 text-sm">Target Reposts</h4>
                            <p class="text-xl font-bold text-black dark:text-white">{{ $campaign->target_reposts }}
                            </p>
                        </div>
                        <div class="bg-gray-100 dark:bg-slate-700 p-5 rounded-lg shadow">
                            <h4 class="text-gray-600 dark:text-gray-400 text-sm">Completed Reposts</h4>
                            <p class="text-xl font-bold text-black dark:text-white">
                                {{ $campaign->completed_reposts }}
                            </p>
                        </div>
                        <div class="bg-gray-100 dark:bg-slate-700 p-5 rounded-lg shadow">
                            <h4 class="text-gray-600 dark:text-gray-400 text-sm">Playback Count</h4>
                            <p class="text-xl font-bold text-black dark:text-white">{{ $campaign->playback_count }}
                            </p>
                        </div>
                        <div class="bg-gray-100 dark:bg-slate-700 p-5 rounded-lg shadow">
                            <h4 class="text-gray-600 dark:text-gray-400 text-sm">Budget</h4>
                            <p class="text-xl font-bold text-black dark:text-white">
                                {{ number_format($campaign->budget) }}</p>
                        </div>
                        <div class="bg-gray-100 dark:bg-slate-700 p-5 rounded-lg shadow">
                            <h4 class="text-gray-600 dark:text-gray-400 text-sm">Credits Spent</h4>
                            <p class="text-xl font-bold text-black dark:text-white">
                                {{ number_format($campaign->credits_spent) }}</p>
                        </div>
                        <div class="bg-gray-100 dark:bg-slate-700 p-5 rounded-lg shadow">
                            <h4 class="text-gray-600 dark:text-gray-400 text-sm">Cost Per Repost</h4>
                            <p class="text-xl font-bold text-black dark:text-white">
                                {{ number_format($campaign->cost_per_repost) }}</p>
                        </div>
                    </div>

                    <!-- Campaign Info -->
                    <div class="mt-10 bg-gray-100 dark:bg-slate-700 p-6 rounded-xl shadow">
                        <h3 class="text-xl font-bold mb-4 text-gray-900 dark:text-white">Campaign Settings</h3>
                        <div
                            class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 text-sm text-gray-700 dark:text-gray-300">
                            <p><span class="font-medium text-black dark:text-white">Status:</span>
                                {{ $campaign->status_label }}</p>
                            <p><span class="font-medium text-black dark:text-white">Start Date:</span>
                                {{ $campaign->start_date_formatted }}</p>
                            <p><span class="font-medium text-black dark:text-white">End Date:</span>
                                {{ $campaign->end_date_formatted }}</p>
                            <p><span class="font-medium text-black dark:text-white">Featured:</span>
                                {{ $campaign->feature_label }}</p>
                        </div>
                    </div>
                    <div
                        class="mt-10 w-full mx-auto bg-gray-100 dark:bg-gradient-to-r dark:from-zinc-700 dark:to-zinc-900 p-4 rounded-lg">
                        <x-sound-cloud.sound-cloud-player :track="$campaign->music" :visual="true" :height="166" />
                    </div>
                </div>
            @endif
        </div>
    </div>
</section>
