<section>

    <x-slot name="page_slug">campains</x-slot>

    <div class="p-6">
        <div class="flex justify-between items-start mb-8">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">{{ __('My Campaigns') }}</h1>
                <p class="text-gray-600 dark:text-gray-400">Track the performance of your submitted tracks</p>
            </div>
            <button onclick="campaigns_modal.showModal()"
                class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                {{ __('New Campaign') }}
            </button>

             <button class="text-red-500/90">dsklfjkl</button>
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

   

    {{-- <div class="absolute inset-0 bg-black/50">
        <div class="flex flex-col items-center justify-center h-full">
            <div class="w-11/12 max-w-2xl mx-auto rounded-lg shadow-xl p-0 relative min-h-[60vh] bg-red-500/50">
                dsfl;''
            </div>
        </div>
    </div> --}}

    {{-- <dialog id="campaigns_modal" class="modal">
        <div
            class="modal-box w-11/12 max-w-2xl mx-auto rounded-lg shadow-xl p-0 relative min-h-[60vh] md:min-h-0 md:top-1/4 md:left-1/2 md:-translate-x-1/2">
            <form method="dialog">
                <button
                    class="btn btn-sm btn-circle btn-ghost absolute right-4 top-4 z-10 text-gray-500 hover:text-gray-700"
                    id="close_campaigns_modal">✕</button>
            </form>

            <div class="p-6 pb-4">
                <h2 class="text-2xl font-bold text-gray-900 text-center mb-6">Choose a track or playlist</h2>

                <div class="flex border-b border-gray-200 mb-6">
                    <button id="tab-tracks"
                        class="modal-tab-button flex-1 py-3 px-1 text-center font-semibold text-base transition-all duration-300 ease-in-out border-b-2 border-transparent text-gray-600 hover:text-red-600 focus:outline-none"
                        data-tab="tracks">
                        Tracks
                    </button>
                    <button id="tab-playlist"
                        class="modal-tab-button flex-1 py-3 px-1 text-center font-semibold text-base transition-all duration-300 ease-in-out border-b-2 border-transparent text-gray-600 hover:text-red-600 focus:outline-none"
                        data-tab="playlist">
                        Playlist
                    </button>
                </div>

                <div id="tab-content-tracks" class="tab-content p-2">
                    <div class="flex items-center justify-center h-48 bg-gray-50 rounded-md"
                        id="tracks-loading-spinner">
                        <span class="text-gray-400">Loading tracks...</span>
                    </div>
                    <div id="tracks-list-container">
                    </div>
                    <div class="hidden text-center text-gray-500 mt-4" id="tracks-not-found">
                        No tracks found.
                    </div>
                </div>

                <div id="tab-content-playlist" class="tab-content hidden p-2">
                    <div class="flex items-center justify-center h-48 bg-gray-50 rounded-md"
                        id="playlists-loading-spinner">
                        <span class="text-gray-400">Loading playlists...</span>
                    </div>
                    <div id="playlists-list-container">
                    </div>
                    <div class="hidden text-center text-gray-500 mt-4" id="playlists-not-found">
                        No playlists found.
                    </div>
                </div>
            </div>
        </div>
    </dialog> --}}

    {{-- New Campaign Submission Modal --}}
    <dialog id="campaign_submit_modal" class="modal">
        <div class="modal-box w-11/12 max-w-2xl mx-auto rounded-lg shadow-xl p-0 relative">
            <form method="dialog">
                <button
                    class="btn btn-sm btn-circle btn-ghost absolute right-4 top-4 z-10 text-gray-500 hover:text-gray-700"
                    id="close_campaign_submit_modal">✕</button>
            </form>

            <div class="p-6">
                <h2 class="text-2xl font-bold text-gray-900 text-center mb-6">{{ __('Create New Campaign') }}</h2>

                <form id="campaign-create-form" class="space-y-6">
                    @csrf {{-- Laravel CSRF token --}}

                    <div class="mb-4">
                        <label for="selected_media_info"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Selected:') }}</label>
                        <p id="selected_media_info"
                            class="mt-1 p-2 border border-gray-300 dark:border-gray-600 rounded-md bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white">
                            No track/playlist selected.
                        </p>
                        <input type="hidden" name="target_type" id="campaign_target_type">
                        <input type="hidden" name="target_id" id="campaign_target_id">
                    </div>

                    <div>
                        <label for="campaign_title"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Campaign Title') }}
                            <span class="text-red-500">*</span></label>
                        <input type="text" id="campaign_title" name="campaign_title" required
                            class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm p-2 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-orange-500 focus:border-orange-500">
                    </div>

                    <div>
                        <label for="total_budget"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Total Budget (Credits)') }}
                            <span class="text-red-500">*</span></label>
                        <input type="number" id="total_budget" name="total_budget" required min="0"
                            step="1"
                            class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm p-2 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-orange-500 focus:border-orange-500">
                    </div>

                    <div>
                        <label for="target_repost_count"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Target Repost Count') }}
                            <span class="text-red-500">*</span></label>
                        <input type="number" id="target_repost_count" name="target_repost_count" required
                            min="1" step="1"
                            class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm p-2 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-orange-500 focus:border-orange-500">
                    </div>

                    <div>
                        <label for="description"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Description (Optional)') }}</label>
                        <textarea id="description" name="description" rows="3"
                            class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm p-2 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-orange-500 focus:border-orange-500"></textarea>
                    </div>

                    <div>
                        <label for="expiration_date"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Expiration Date') }}
                            <span class="text-red-500">*</span></label>
                        <input type="date" id="expiration_date" name="expiration_date" required
                            class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm p-2 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-orange-500 focus:border-orange-500">
                    </div>

                    <div class="flex justify-end pt-4">
                        <button type="submit"
                            class="bg-orange-500 hover:bg-orange-600 text-white px-6 py-2 rounded-lg font-semibold transition-colors">
                            {{ __('Create Campaign') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </dialog>

    @push('js')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const campaignsModal = document.getElementById('campaigns_modal');
                const campaignSubmitModal = document.getElementById('campaign_submit_modal');
                const modalTabButtons = document.querySelectorAll('#campaigns_modal .modal-tab-button');
                const modalTabContents = document.querySelectorAll('#campaigns_modal .tab-content');

                const tracksListContainer = document.getElementById('tracks-list-container');
                const tracksLoadingSpinner = document.getElementById('tracks-loading-spinner');
                const tracksNotFoundMessage = document.getElementById('tracks-not-found');

                const playlistsListContainer = document.getElementById('playlists-list-container');
                const playlistsLoadingSpinner = document.getElementById('playlists-loading-spinner');
                const playlistsNotFoundMessage = document.getElementById('playlists-not-found');

                const mainTabButtons = document.querySelectorAll('nav.-mb-px .tab-button');
                const allCampaignCards = document.querySelectorAll('.campaign-card');

                // Form elements for the submission modal
                const selectedMediaInfo = document.getElementById('selected_media_info');
                const campaignTargetTypeInput = document.getElementById('campaign_target_type');
                const campaignTargetIdInput = document.getElementById('campaign_target_id');
                const campaignCreateForm = document.getElementById('campaign-create-form');

                const tracksRoute = "{{ route('user.cm.campaigns.tracks') }}";
                const playlistsRoute = "{{ route('user.cm.campaigns.playlists') }}";
                // Note the placeholder in the base route for playlist tracks
                const playlistTracksRouteBase =
                    "{{ route('user.cm.campaigns.playlist.tracks', ['playlistId' => ':playlistId']) }}";
                const storeCampaignRoute = "{{ route('user.cm.campaigns.store') }}";

                let currentActiveModalTab = 'tracks';

                // Function to set the active tab and trigger data fetch for the modal
                function setActiveModalTab(tabId) {
                    currentActiveModalTab = tabId.replace('tab-', '');

                    modalTabButtons.forEach(button => {
                        button.classList.remove('border-red-500', 'text-red-600');
                        button.classList.add('border-transparent', 'text-gray-600');
                    });
                    modalTabContents.forEach(content => {
                        content.classList.add('hidden');
                    });

                    const activeTabButton = document.getElementById(tabId);
                    activeTabButton.classList.remove('border-transparent', 'text-gray-600');
                    activeTabButton.classList.add('border-red-500', 'text-red-600');

                    const activeContentId = `tab-content-${currentActiveModalTab}`;
                    document.getElementById(activeContentId).classList.remove('hidden');

                    if (currentActiveModalTab === 'tracks') {
                        fetchTracks();
                    } else if (currentActiveModalTab === 'playlist') {
                        fetchPlaylists();
                    }
                }

                // Add click event listeners to tabs within the modal
                modalTabButtons.forEach(button => {
                    button.addEventListener('click', () => {
                        setActiveModalTab(button.id);
                    });
                });

                // Function to handle fetching and displaying tracks
                function fetchTracks() {
                    tracksLoadingSpinner.classList.remove('hidden');
                    tracksListContainer.innerHTML = ''; // Clear previous content
                    tracksNotFoundMessage.classList.add('hidden');

                    axios.post(tracksRoute)
                        .then(response => {
                            tracksLoadingSpinner.classList.add('hidden');
                            const tracks = response.data.tracks; // Access the 'tracks' key from the response
                            console.log('Fetched tracks:', tracks);

                            if (tracks && tracks.length > 0) {
                                let htmlContent = '<ul class="divide-y divide-gray-200 dark:divide-gray-700">';
                                tracks.forEach(track => {
                                    htmlContent += `
                                        <li class="py-3 flex items-center space-x-4">
                                            <div class="flex-shrink-0">
                                                <img class="h-12 w-12 rounded-lg object-cover" src="${track.artwork_url}" alt="${track.title || 'Track Image'}">
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-gray-900 truncate dark:text-white">${track.title || 'Untitled Track'}</p>
                                                <p class="text-sm text-gray-500 truncate dark:text-gray-400">by <strong>${track.author_username || 'Unknown Artist'}</strong> <span class="ml-1 text-xs">${track.genre || ''}</span></p>
                                                <span class="inline-block bg-gray-200 text-xs px-2 py-1 rounded-full">${track.isrc || 'N/A'}</span>
                                            </div>
                                            <div>
                                                <button class="bg-blue-500 hover:bg-blue-600 text-white text-xs px-3 py-1 rounded-full select-track-btn" data-id="${track.id}" data-title="${track.title}" data-type="track">Select</button>
                                            </div>
                                        </li>
                                    `;
                                });
                                htmlContent += '</ul>';
                                tracksListContainer.innerHTML = htmlContent;
                            } else {
                                tracksNotFoundMessage.classList.remove('hidden');
                            }
                        })
                        .catch(error => {
                            tracksLoadingSpinner.classList.add('hidden');
                            console.error('Error fetching tracks:', error);
                            tracksNotFoundMessage.classList.remove('hidden');
                            tracksListContainer.innerHTML =
                                `<p class="text-red-500 text-center mt-4">Error loading tracks. Please try again.</p>`;
                        });
                }

                // Function to handle fetching and displaying playlists
                function fetchPlaylists() {
                    playlistsLoadingSpinner.classList.remove('hidden');
                    playlistsListContainer.innerHTML = ''; // Clear previous content
                    playlistsNotFoundMessage.classList.add('hidden');

                    axios.post(playlistsRoute)
                        .then(response => {
                            playlistsLoadingSpinner.classList.add('hidden');
                            const playlists = response.data
                                .playlists; // Access the 'playlists' key from the response
                            console.log('Fetched playlists:', playlists);

                            if (playlists && playlists.length > 0) {
                                let htmlContent = '<ul class="divide-y divide-gray-200 dark:divide-gray-700">';
                                playlists.forEach(playlist => {
                                    htmlContent += `
                                        <li class="py-3 flex items-center space-x-4">
                                            <div class="flex-shrink-0">
                                                <img class="h-12 w-12 rounded-lg object-cover" src="${playlist.artwork_url || '{{ asset('frontend/user/image/music-notes.jpg') }}'}" alt="${playlist.title || 'Playlist Image'}">
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-gray-900 truncate dark:text-white">${playlist.title || 'Untitled Playlist'}</p>
                                                <p class="text-sm text-gray-500 truncate dark:text-gray-400">${playlist.track_count || 0} tracks</p>
                                            </div>
                                            <div>
                                                <button class="bg-blue-500 hover:bg-blue-600 text-white text-xs px-3 py-1 rounded-full select-playlist-btn" data-id="${playlist.id}" data-title="${playlist.title}" data-type="playlist">Select</button>
                                            </div>
                                        </li>
                                    `;
                                });
                                htmlContent += '</ul>';
                                playlistsListContainer.innerHTML = htmlContent;
                            } else {
                                playlistsNotFoundMessage.classList.remove('hidden');
                            }
                        })
                        .catch(error => {
                            playlistsLoadingSpinner.classList.add('hidden');
                            console.error('Error fetching playlists:', error);
                            playlistsNotFoundMessage.classList.remove('hidden');
                            playlistsListContainer.innerHTML =
                                `<p class="text-red-500 text-center mt-4">Error loading playlists. Please try again.</p>`;
                        });
                }

                // New function to fetch and display tracks for a selected playlist
                function fetchPlaylistTracks(playlistId, playlistTitle) {
                    const url = playlistTracksRouteBase.replace(':playlistId', playlistId);
                    campaignsModal.close(); // Close the initial selection modal

                    // Open the submit modal and show loading state or direct user
                    campaignSubmitModal.showModal();
                    selectedMediaInfo.textContent = `Loading tracks for playlist: ${playlistTitle}...`;
                    campaignTargetTypeInput.value = 'playlist'; // Set the type in the form
                    campaignTargetIdInput.value = playlistId; // Set the ID in the form

                    axios.post(url)
                        .then(response => {
                            const tracks = response.data.tracks;
                            console.log('Fetched playlist tracks:', tracks);
                            if (tracks && tracks.length > 0) {
                                let trackListHtml =
                                    `<p class="text-gray-700 dark:text-gray-300 mb-2">Tracks in "${playlistTitle}":</p><ul class="list-disc list-inside text-gray-600 dark:text-gray-400">`;
                                tracks.forEach(track => {
                                    trackListHtml +=
                                        `<li>${track.title || 'Untitled Track'} by ${track.author_username || 'Unknown Artist'}</li>`;
                                });
                                trackListHtml += '</ul>';
                                selectedMediaInfo.innerHTML =
                                    `Selected Playlist: <strong>${playlistTitle}</strong><br>` + trackListHtml;
                            } else {
                                selectedMediaInfo.innerHTML =
                                    `Selected Playlist: <strong>${playlistTitle}</strong><br><span class="text-red-500">No tracks found in this playlist.</span>`;
                            }
                        })
                        .catch(error => {
                            console.error('Error fetching playlist tracks:', error);
                            selectedMediaInfo.innerHTML =
                                `Selected Playlist: <strong>${playlistTitle}</strong><br><span class="text-red-500">Error loading playlist tracks.</span>`;
                        });
                }


                // Add an event listener to the "New Campaign" button to initialize the modal content
                document.querySelector('button[onclick="campaigns_modal.showModal()"]').addEventListener('click',
                    () => {
                        setActiveModalTab('tab-tracks'); // Default to tracks when modal opens
                    });

                // --- Main Page Tabs Filtering ---
                mainTabButtons.forEach(button => {
                    button.addEventListener('click', function() {
                        // Remove 'active' class and styles from all main tabs
                        mainTabButtons.forEach(btn => {
                            btn.classList.remove('active', 'border-orange-500',
                                'text-orange-600', 'translateY(-1px)');
                            btn.classList.add('border-transparent', 'text-gray-500',
                                'hover:text-gray-700', 'hover:border-gray-300');
                        });

                        // Add 'active' class and styles to the clicked tab
                        this.classList.add('active', 'border-orange-500', 'text-orange-600',
                            'translateY(-1px)');
                        this.classList.remove('border-transparent', 'text-gray-500',
                            'hover:text-gray-700', 'hover:border-gray-300');

                        const filterStatus = this.dataset.tab; // 'all', 'Active', 'Completed'

                        allCampaignCards.forEach(card => {
                            const campaignStatus = card.dataset.status;
                            if (filterStatus === 'all' || campaignStatus === filterStatus) {
                                card.classList.remove('hidden');
                            } else {
                                card.classList.add('hidden');
                            }
                        });
                    });
                });

                // Event delegation for select buttons in the initial modal (as they are added dynamically)
                campaignsModal.addEventListener('click', (event) => {
                    if (event.target.classList.contains('select-track-btn')) {
                        const trackId = event.target.dataset.id;
                        const trackTitle = event.target.dataset.title;
                        const targetType = event.target.dataset.type; // 'track'

                        campaignsModal.close(); // Close the first modal
                        campaignSubmitModal.showModal(); // Open the submission modal

                        selectedMediaInfo.textContent = `Selected Track: ${trackTitle}`;
                        campaignTargetTypeInput.value = targetType;
                        campaignTargetIdInput.value = trackId;

                    } else if (event.target.classList.contains('select-playlist-btn')) {
                        const playlistId = event.target.dataset.id;
                        const playlistTitle = event.target.dataset.title;
                        const targetType = event.target.dataset.type; // 'playlist'

                        // Instead of directly showing the submit modal, fetch playlist tracks first
                        fetchPlaylistTracks(playlistId, playlistTitle);
                    }
                });

                // Handle Campaign Submission
                campaignCreateForm.addEventListener('submit', function(event) {
                    event.preventDefault(); // Prevent default form submission

                    const formData = new FormData(this); // Get form data
                    const submitButton = this.querySelector('button[type="submit"]');
                    submitButton.disabled = true;
                    submitButton.textContent = 'Creating...';

                    axios.post(storeCampaignRoute, formData)
                        .then(response => {
                            console.log('Campaign created successfully:', response.data);
                            alert('Campaign created successfully!');
                            campaignSubmitModal.close();
                            // Optionally, refresh the page or update the campaigns list
                            window.location.reload();
                        })
                        .catch(error => {
                            console.error('Error creating campaign:', error.response ? error.response.data :
                                error.message);
                            const errorMessage = error.response && error.response.data && error.response
                                .data.message ?
                                error.response.data.message : 'An unexpected error occurred.';
                            alert('Error creating campaign: ' + errorMessage);

                            // Display validation errors if available
                            if (error.response && error.response.data && error.response.data.errors) {
                                let errors = '';
                                for (const key in error.response.data.errors) {
                                    errors += error.response.data.errors[key].join('\n') + '\n';
                                }
                                alert('Validation Errors:\n' + errors);
                            }
                        })
                        .finally(() => {
                            submitButton.disabled = false;
                            submitButton.textContent = 'Create Campaign';
                        });
                });
            });
        </script>
    @endpush

</section>
