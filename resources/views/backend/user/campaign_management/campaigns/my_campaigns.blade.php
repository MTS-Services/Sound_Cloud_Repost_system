<section x-data="{ showCampaignsModal: false, showSubmitModal: false }">

    <x-slot name="page_slug">campains</x-slot>

    <div class="p-6">
        <div class="flex justify-between items-start mb-8">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">{{ __('My Campaigns') }}</h1>
                <p class="text-gray-600 dark:text-gray-400">Track the performance of your submitted tracks</p>
            </div>
            <button wire:click="toggleCampaignsModal" x-on:click="showCampaignsModal = true"
                class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors">
                <i data-lucide="plus" class="w-5 h-5"></i>
                {{ __('New Campaign') }}
            </button>
        </div>

        <div class="mb-8">
            <div class="border-b border-gray-200 dark:border-gray-700">
                <nav class="-mb-px flex space-x-8">
                    <button id="main-tab-all"
                        class="tab-button active border-b-2 border-orange-500 py-2 px-1 text-sm font-medium text-orange-600 translateY(-1px)"
                        data-tab="all">
                        {{ __('All Campaigns') }}
                    </button>
                    <button id="main-tab-active"
                        class="tab-button border-b-2 border-transparent py-2 px-1 text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300"
                        data-tab="Active">
                        {{ __('Active') }}
                    </button>
                    <button id="main-tab-completed"
                        class="tab-button border-b-2 border-transparent py-2 px-1 text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300"
                        data-tab="Completed">
                        {{ __('Completed') }}
                    </button>
                </nav>
            </div>
        </div>

        <div class="space-y-6" id="campaigns-list">
            @forelse ($campaigns as $campaign)
                <div class="campaign-card bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6"
                    data-status="{{ $campaign->status_label }}">
                    <div class="flex justify-center gap-5">
                        <div class="w-48 h-32">
                            <div class="w-full h-full rounded-lg overflow-hidden flex-shrink-0 relative">
                                {{-- Assuming artwork_url is available or a fallback --}}
                                <img src="{{ $campaign->artwork_url ?? asset('frontend/user/image/music-notes.jpg') }}"
                                    alt="{{ $campaign->title ?? 'Campaign Album Cover' }}"
                                    class="w-full h-full object-cover bg-gradient-to-br from-yellow-400 via-red-500 to-pink-500">
                                <button
                                    class="playPauseBtn absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white text-gray-800 rounded-full p-2 hover:bg-gray-200 transition-colors">
                                    <svg class="playIcon w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M8 5v14l11-7z" />
                                    </svg>
                                    <svg class="pauseIcon w-4 h-4 hidden" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex items-start gap-4">
                                    <div>
                                        <div class="flex items-center gap-3 mb-2">
                                            <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100">
                                                {{ $campaign->title }}
                                            </h3>
                                            <span
                                                class="badge {{ $campaign->status_color }} text-white text-xs font-medium px-2.5 py-0.5 rounded-full">{{ $campaign->status_label }}</span>
                                        </div>
                                        <div class="flex items-center gap-4 text-sm text-gray-500 dark:text-gray-400">
                                            <div class="flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                    </path>
                                                </svg>
                                                {{ __('Created: ') }}{{ $campaign->start_date_formatted }}
                                            </div>
                                            <div class="flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z">
                                                    </path>
                                                </svg>
                                                {{ __('Expires: ') }}{{ $campaign->end_date_formatted }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm text-gray-600 mb-2">
                                        {{ __('Budget used:') }}
                                        {{ $campaign->completed_reposts ?? 0 }}/{{ $campaign->total_credits_budget ?? 0 }}
                                        {{ __('credits') }}</p>
                                    <div class="w-32 bg-gray-200 rounded-full h-2">
                                        <div class="bg-orange-500 h-2 rounded-full"
                                            style="width: {{ $campaign->total_credits_budget > 0 ? ($campaign->completed_reposts / $campaign->total_credits_budget) * 100 : 0 }}%">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="grid grid-cols-3 gap-6 mt-5">
                                <div class="bg-white dark:bg-gray-700 rounded-lg shadow-sm py-2 text-center">
                                    <div class="flex items-center justify-center mb-2">
                                        <i data-lucide="refresh-ccw" class="w-5 h-5 text-pink-500"></i>
                                    </div>
                                    <div class="text-2xl font-bold text-gray-900 dark:text-white">
                                        {{ $campaign->reposts_count ?? 0 }}</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-300">{{ __('Reposts') }}</div>
                                </div>
                                <div class="bg-white dark:bg-gray-700 rounded-lg shadow-sm py-2 text-center">
                                    <div class="flex items-center justify-center mb-2">
                                        <i data-lucide="eye" class="w-5 h-5 text-blue-500"></i>
                                    </div>
                                    <div class="text-2xl font-bold text-gray-900 dark:text-white">
                                        {{ $campaign->plays_count ?? 0 }}</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-300">{{ __('Plays') }}</div>
                                </div>
                                <div class="bg-white dark:bg-gray-700 rounded-lg shadow-sm py-2 text-center">
                                    <div class="flex items-center justify-center mb-2">
                                        <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                                            </path>
                                        </svg>
                                    </div>
                                    <div class="text-2xl font-bold text-gray-900 dark:text-white">
                                        {{ $campaign->likes_count ?? 0 }}</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-300">{{ __('Likes') }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="w-52 flex flex-col justify-end">
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
            @empty
                <div class="flex flex-col items-center justify-center py-20 text-center">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-2">
                        {{ __('No campaigns found') }}
                    </h3>
                    <p class="text-gray-500 mb-4">
                        {{ __("You haven't started any campaigns yet. Click the 'New Campaign' button to create your first one!") }}
                    </p>
                </div>
            @endforelse
        </div>

    </div>

    {{-- ================================ Modals ================================ --}}

    <div x-data="{ showCampaignsModal: @entangle('showCampaignsModal').live }" x-show="showCampaignsModal" x-cloak
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-90"
        x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-90"
        class="fixed inset-0 bg-black/50 flex items-center justify-center p-4 z-50">

        <div
            class="w-full max-w-2xl mx-auto rounded-lg shadow-xl bg-white dark:bg-slate-800 p-6 flex flex-col max-h-[70vh]">
            <div class="flex justify-between items-center mb-5">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white text-center flex-grow">
                    Choose a track or playlist
                </h2>
                <button x-on:click="showCampaignsModal = false"
                    class="btn btn-sm btn-circle bg-orange-500 hover:bg-orange-600 text-white hover:text-gray-200"
                    id="close_campaigns_modal">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="lucide lucide-x-icon lucide-x">
                        <path d="M18 6 6 18" />
                        <path d="m6 6 12 12" />
                    </svg>
                </button>
            </div>

            @if ($showCampaignsModal)
                <div class="flex mb-6">
                    <button wire:click="selectModalTab('tracks')"
                        class="flex-1 py-3 px-1 text-center font-semibold text-base transition-all duration-300 ease-in-out border-b-2 hover:text-orange-700 focus:outline-none {{ $activeModalTab === 'tracks' ? 'border-b-orange-500 text-orange-600' : 'border-transparent text-gray-600' }}">
                        Tracks
                    </button>
                    <button wire:click="selectModalTab('playlists')"
                        class="flex-1 py-3 px-1 text-center font-semibold text-base transition-all duration-300 ease-in-out border-b-2 hover:text-orange-700 focus:outline-none {{ $activeModalTab === 'playlists' ? 'border-b-orange-500 text-orange-600' : 'border-transparent text-gray-600' }}">
                        Playlists
                    </button>
                </div>

                <div class="flex-grow overflow-y-auto pr-2 -mr-2">
                    @if ($activeModalTab === 'tracks')
                        <div>
                            @forelse ($tracks as $track)
                                {{-- @dd($track) --}}
                                <div wire:click="toggleSubmitModal('track', {{ $track->id }})"
                                    class="py-3 flex items-center space-x-4 cursor-pointer hover:bg-gray-50 dark:hover:bg-slate-700 rounded-lg px-2 transition-colors duration-200">
                                    <div class="flex-shrink-0">
                                        <img class="h-12 w-12 rounded-lg object-cover"
                                            src="{{ $track->artwork_url }}" alt="{{ $track->title }}" />
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate dark:text-white">
                                            {{ $track->title }}
                                        </p>
                                        <p class="text-sm text-gray-500 truncate dark:text-gray-400">
                                            by
                                            <strong class="text-orange-600">{{ $track->author_username }}</strong>
                                            <span class="ml-1 text-xs text-gray-400">{{ $track->genre }}</span>
                                        </p>
                                        <span
                                            class="inline-block bg-gray-200 dark:bg-slate-700 text-xs px-2 py-1 rounded-full text-gray-700 dark:text-gray-300 mt-1">{{ $track->isrc }}</span>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-10 text-gray-500 dark:text-gray-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-10 w-10 text-orange-400"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 17v-2a4 4 0 00-4-4H5m14 0h-1a4 4 0 00-4 4v2M12 7h.01M12 12h.01M12 17h.01" />
                                    </svg>
                                    <p class="mt-3">No tracks found. Try uploading one first.</p>
                                </div>
                            @endforelse
                        </div>
                    @elseif($activeModalTab === 'playlists')
                        <div>

                            @forelse ($playlists as $playlist)
                                <div wire:click="toggleSubmitModal('playlist', {{ $playlist->id }})"
                                    class="py-3 flex items-center space-x-4 cursor-pointer hover:bg-gray-50 dark:hover:bg-slate-700 rounded-lg px-2  transition-colors duration-200">
                                    <div class="flex-shrink-0">
                                        <img class="h-12 w-12 rounded-lg object-cover"
                                            src="{{ $playlist->artwork_url }}" alt="{{ $playlist->title }}" />
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate dark:text-white">
                                            {{ $playlist->title }}
                                        </p>
                                        <p class="text-sm text-gray-500 truncate dark:text-gray-400">
                                            {{ $playlist->track_count }} tracks
                                        </p>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-10 text-gray-500 dark:text-gray-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-10 w-10 text-orange-400"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                                    </svg>
                                    <p class="mt-3">No playlists found. Add one to get started.</p>
                                </div>
                            @endforelse

                        </div>
                    @endif
                </div>
            @endif
        </div>


        <div x-data="{ showSubmitModal: @entangle('showSubmitModal').live }" x-show="showSubmitModal" x-cloak
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-90"
            x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-90"
            class="fixed inset-0 bg-black/50 flex items-center justify-center p-4 z-50">

            <div
                class="w-full max-w-2xl mx-auto rounded-lg shadow-xl bg-white dark:bg-slate-800 p-6 flex flex-col max-h-[70vh]">
                <div class="flex justify-between items-center mb-5">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white text-center flex-grow">
                        Submit Modal
                    </h2>
                    <button x-on:click="showSubmitModal = false"
                        class="btn btn-sm btn-circle bg-orange-500 hover:bg-orange-600 text-white hover:text-gray-200"
                        id="close_campaigns_modal">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="lucide lucide-x-icon lucide-x">
                            <path d="M18 6 6 18" />
                            <path d="m6 6 12 12" />
                        </svg>
                    </button>
                </div>


            </div>
        </div>

</section>
