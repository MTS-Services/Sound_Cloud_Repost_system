<x-user::layout>

    <x-slot name="page_slug">campains</x-slot>

    <div class="p-6">
        <!-- You can open the modal using ID.showModal() method -->
        {{-- <button class="btn" onclick="my_modal_3.showModal()">open modal</button> --}}
        <dialog id="campaign_create_modal" class="modal">
            <div class="modal-box top-1/4 left-1/2 -translate-x-1/2 min-h-[60vh]">
                <form method="dialog">
                    <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2"
                        id="close_campaign_modal">✕</button>
                </form>
                <!-- Modal Header -->
                <h2 class="text-xl font-medium text-gray-900 text-center mb-6">Start a New Campaign</h2>
                <div class="p-6 pb-4 flex justify-between items-center">
                    <p class="text-md font-medium text-gray-900">Selected Track</p>
                    <button
                        onclick="campaigns_modal.showModal(), document.getElementById('close_campaign_modal').click()"
                        class="text-red-500 font-medium text-md hover:text-red-600 transition-colors">
                        Edit
                    </button>
                </div>
                <!-- Track List -->
                <div id="tracks-content" class="px-6 pb-6">
                    <!-- Track 1 -->
                    <a id="selected_track_id"
                        data-url-template="{{ route('cm.campaigns.create', '__ID__') }}" href="#">
                        {{-- <div
                            class="flex items-center space-x-3 p-3 hover:bg-gray-50 rounded-lg cursor-pointer transition-colors shadow-sm">
                            <img src="{{ asset('frontend/user/image/music-notes.jpg') }}" alt="Album cover"
                                class="w-12 h-12 rounded object-cover">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm text-gray-500 truncate">Ambient + herman hedrick♡</p>
                                <p class="text-sm font-medium text-gray-900 truncate">Ab To Forever</p>
                            </div>
                        </div> --}}
                        <div id="selected_track_display" class="flex items-center space-x-3 p-3 bg-gray-100 rounded-lg">
                            <img id="selected_track_image" src="" class="w-12 h-12 rounded object-cover" />
                            <div>
                                <p id="selected_track_author" class="text-sm text-gray-500"></p>
                                <p id="selected_track_title" class="text-sm font-medium text-gray-900"></p>
                            </div>
                        </div>

                    </a>
                    <div class="mt-6">
                        {{-- Budget after info ludide icon right --}}
                        <h6 class="text-md font-medium text-gray-900 mb-2 after:content-['\1F4B0'] after:ml-2 ">Set
                            budget</h6>
                        <p class="text-sm text-gray-300 text-center">You need 50 credits to get a campaign reach
                            estimate</p>
                        <div class="flex items-center justify-center mt-2">
                            <span class="text-gray-500"><i data-lucide="banknote"
                                    class="w-10 h-10 inline mr-2"></i></span>
                            <span class="text-3xl font-medium text-gray-500">50</span>
                        </div>
                    </div>

                    <!-- Load More Button -->
                    <div class="text-center mt-6">
                        <button
                            class="w-full bg-red-500 text-white px-6 py-3 rounded-lg hover:bg-red-600 transition-colors font-medium">
                            Buy Credits
                        </button>
                    </div>
                </div>

                <!-- Playlists Content (Hidden by default) -->
                <div id="playlists-content" class="px-6 pb-6 hidden">
                    <div class="text-center text-gray-500 py-8">
                        <p>No playlists available</p>
                    </div>
                </div>
            </div>
        </dialog>
        <dialog id="campaigns_modal" class="modal">
            <div class="modal-box top-1/4 left-1/2 -translate-x-1/2 min-h-[60vh]">
                <form method="dialog">
                    <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2"
                        id="close_campaigns_modal">✕</button>
                </form>
                <!-- Modal Header -->
                <div class="p-6 pb-4">
                    <h2 class="text-xl font-medium text-gray-900 text-center mb-6">Choose a track or playlist</h2>

                    <!-- Tabs -->
                    <div class="flex border-b-2 border-gray-200 mb-6">
                        <button onclick="switchTab('tracks')" id="tracks-tab"
                            class="flex-1 py-2 px-1 text-center font-medium text-sm border-b-2 border-red-500 text-red-600">
                            Tracks
                        </button>
                        <button onclick="switchTab('playlists')" id="playlists-tab"
                            class="flex-1 py-2 px-1 text-center font-medium text-sm border-b-2 border-transparent text-gray-500 hover:text-gray-700">
                            Playlists
                        </button>
                    </div>

                    <!-- Promotional Message -->
                    <div class="text-center text-sm text-gray-600 mb-6">
                        <p>Want to promote a track from another account?</p>
                        <p>Get the <span class="text-red-500 font-medium">Network Plan</span></p>
                    </div>
                </div>
                <!-- Track List -->
                <div id="tracks-content" class="px-6 pb-6">

                    @foreach ($tracks as $track)
                        <!-- Track Item -->
                        {{-- <div onclick="campaign_create_modal.showModal(), document.getElementById('close_campaigns_modal').click()"
                            class="flex items-center bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm mt-4">
                            <img src="{{ asset('frontend/user/image/pexels-photo-1540338.jpeg') }}"
                                alt="{{ $track->title }}" class="rounded-md w-36 h-32" />
                            <div class="flex-1 p-4 ">
                                <h2 class="text-lg font-semibold dark:text-gray-100">{{ $track->title }}</h2>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">
                                    by <strong>{{ $track->author_username }}</strong>
                                    <span class="ml-1 text-xs">{{ $track->genre }}</span>
                                </p>
                                <span
                                    class="inline-block bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 text-xs px-2 py-1 rounded-full">
                                    {{ $track->isrc }}
                                </span>
                            </div>
                        </div> --}}
                        <div onclick="selectTrack(this)" data-id="{{ $track->id }}" data-title="{{ $track->title }}"
                            data-author="{{ $track->author_username }}" data-genre="{{ $track->genre }}"
                            data-isrc="{{ $track->isrc }}"
                            data-image="{{ asset('frontend/user/image/pexels-photo-1040881.jpeg') }}"
                            class="flex items-center bg-white border rounded-lg shadow-sm mt-4 cursor-pointer hover:bg-gray-50">
                            <img src="{{ asset('frontend/user/image/pexels-photo-1040881.jpeg') }}"
                                alt="{{ $track->title }}" class="rounded-md w-36 h-32" />
                            <div class="flex-1 p-4">
                                <h2 class="text-lg font-semibold">{{ $track->title }}</h2>
                                <p class="text-sm text-gray-600 mb-1">
                                    by <strong>{{ $track->author_username }}</strong>
                                    <span class="ml-1 text-xs">{{ $track->genre }}</span>
                                </p>
                                <span class="inline-block bg-gray-200 text-xs px-2 py-1 rounded-full">
                                    {{ $track->isrc }}
                                </span>
                            </div>
                        </div>
                    @endforeach

                    <!-- Load More Button -->
                    <div class="text-center mt-6">
                        <button class="text-red-500 font-medium text-sm hover:text-red-600 transition-colors">
                            Load more
                        </button>
                    </div>
                </div>

                <!-- Playlists Content (Hidden by default) -->
                <div id="playlists-content" class="px-6 pb-6 hidden">
                    <div class="text-center text-gray-500 py-8">
                        <p>No playlists available</p>
                    </div>
                </div>
            </div>
        </dialog>
        <!-- Header -->
        <div class="flex justify-between items-start mb-8">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ __('My Campaigns') }}</h1>
                <p class="text-gray-600">Track the performance of your submitted tracks</p>
            </div>
            <button onclick="campaigns_modal.showModal()"
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
                        class="tab-button active border-b-2 border-orange-500 py-2 px-1 text-sm font-medium text-orange-600 translateY(-1px)"
                        data-tab="all">
                        {{ __('All Campaigns') }}
                    </button>
                    <button
                        class="tab-button border-b-2 border-transparent py-2 px-1 text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300"
                        data-tab="Active">
                        {{ __('Active') }}
                    </button>
                    <button
                        class="tab-button border-b-2 border-transparent py-2 px-1 text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300"
                        data-tab="Completed">
                        {{ __('Completed') }}
                    </button>
                </nav>
            </div>
        </div>

        <!-- Campaign Cards -->
        <div class="space-y-6">
            <!-- Summer Vibes Campaign -->
            @foreach ($campaigns as $campaign)
                <div class="campaign-card bg-white rounded-lg shadow-sm border border-gray-200 p-6"
                    data-status="{{ $campaign->status_label }}">
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
                                            <h3 class="text-xl font-semibold text-gray-900">{{ $campaign->title }}
                                            </h3>
                                            <span
                                                class="badge {{ $campaign->status_color }} text-white text-xs font-medium px-2.5 py-0.5 rounded-full">{{ $campaign->status_label }}</span>
                                        </div>
                                        <div class="flex items-center gap-4 text-sm text-gray-500">
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
                                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                {{ __('Expires: ') }}{{ $campaign->end_date_formatted }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm text-gray-600 mb-2">
                                        {{ __('Budget used:') }}{{ $campaign->completed_reposts }}/{{ $campaign->total_credits_budget }}
                                        {{ __('credits') }}</p>
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
                                    <div class="text-sm text-gray-500">{{ __('Likes') }}</div>
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
            @endforeach
        </div>
    </div>

    <script>
        function selectTrack(el) {
            // Get data from clicked track
            const id = el.dataset.id;
            const title = el.dataset.title;
            const author = el.dataset.author;
            const genre = el.dataset.genre;
            const isrc = el.dataset.isrc;
            const image = el.dataset.image;

            // Populate the campaign_create_modal
            // document.getElementById('selected_track_id').href =
            //     `/Campaign-management/campaigns/create?track_id=${encodeURIComponent(id)}`;

            const link = document.getElementById('selected_track_id');
            if (link) {
                const template = link.dataset.urlTemplate;
                link.href = template.replace('__ID__', id);
            }

            document.getElementById('selected_track_image').src = image;
            document.getElementById('selected_track_title').textContent = title;
            document.getElementById('selected_track_author').textContent = `by ${author} • ${genre}`;

            // Open the new modal and close the old one
            document.getElementById('campaign_create_modal').showModal();
            document.getElementById('close_campaigns_modal').click();
        }

        // Tab functionality
        function switchTab(tab) {
            const tracksTab = document.getElementById('tracks-tab');
            const playlistsTab = document.getElementById('playlists-tab');
            const tracksContent = document.getElementById('tracks-content');
            const playlistsContent = document.getElementById('playlists-content');

            if (tab === 'tracks') {
                tracksTab.classList.add('border-red-500', 'text-red-600');
                tracksTab.classList.remove('border-transparent', 'text-gray-500');
                playlistsTab.classList.add('border-transparent', 'text-gray-500');
                playlistsTab.classList.remove('border-red-500', 'text-red-600');
                tracksContent.classList.remove('hidden');
                playlistsContent.classList.add('hidden');
            } else {
                playlistsTab.classList.add('border-red-500', 'text-red-600');
                playlistsTab.classList.remove('border-transparent', 'text-gray-500');
                tracksTab.classList.add('border-transparent', 'text-gray-500');
                tracksTab.classList.remove('border-red-500', 'text-red-600');
                playlistsContent.classList.remove('hidden');
                tracksContent.classList.add('hidden');
            }
        }
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
            //  select data-button data-attribute
            const buttons = document.querySelectorAll('.tab-button');
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
