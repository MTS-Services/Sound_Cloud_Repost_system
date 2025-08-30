<x-user::layout>
    @push('css')
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
        <style>
           #bottom-player {
                position: fixed;
                bottom: 0;
                left: 0;
                width: 100%;
                background-color: #1a1a1a;
                color: #fff;
                padding: 12px 20px;
                box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.3);
                z-index: 1000;
                display: flex;
                align-items: center;
                transition: transform 0.3s ease;
            }

            #bottom-player.hidden {
                transform: translateY(100%);
            }

            /* Update progress bar styles */
            .progress-bar {
                height: 4px;
                background-color: #333;
                border-radius: 2px;
                cursor: pointer;
                transition: height 0.2s;
            }

            .progress-bar:hover {
                height: 6px;
            }

            .progress {
                height: 100%;
                background-color: #ff5500;
                border-radius: 2px;
                transition: width 0.1s linear;
            }

            /* Update controls */
            .controls button {
                background: none;
                border: none;
                color: #ccc;
                font-size: 1.2rem;
                margin: 0 8px;
                cursor: pointer;
                transition: color 0.2s;
                width: 32px;
                height: 32px;
                border-radius: 50%;
                display: inline-flex;
                align-items: center;
                justify-content: center;
            }

            .controls button:hover {
                background-color: rgba(255, 255, 255, 0.1);
                color: #fff;
            }

            #play-pause-button {
                background-color: #ff5500;
                color: white;
                width: 40px;
                height: 40px;
            }

            #play-pause-button:hover {
                background-color: #e04a00;
            }

            /* Track info */
            .track-info {
                min-width: 200px;
                max-width: 250px;
                margin-right: 20px;
            }

            .track-info img {
                width: 50px;
                height: 50px;
                border-radius: 4px;
                object-fit: cover;
            }

            /* Volume control */
            .volume-control {
                margin-left: 20px;
                min-width: 120px;
            }

            .volume-slider {
                height: 4px;
                background-color: #333;
                border-radius: 2px;
                cursor: pointer;
                margin-left: 8px;
            }

            .volume {
                height: 100%;
                background-color: #ff5500;
                border-radius: 2px;
                width: 100%;
            }

            /* Action buttons */
            .action-buttons {
                margin-left: 20px;
            }

            .action-buttons button {
                background: rgba(255, 255, 255, 0.1);
                border: none;
                color: #fff;
                padding: 6px 12px;
                border-radius: 4px;
                margin-left: 8px;
                cursor: pointer;
                font-size: 0.8rem;
                transition: background-color 0.2s;
            }

            .action-buttons button:hover {
                background: rgba(255, 255, 255, 0.2);
            }
        </style>


    @endpush




    <x-slot name="page_slug">promote</x-slot>

    <section class="min-h-screen bg-white dark:bg-gray-900 p-8" x-data="{ activeTab: 'tracks' }">
        <div class="max-w-4xl mx-auto">
            <h1 class="text-2xl font-bold mb-6 dark:text-gray-100">Promote Dashboard</h1>

            <!-- Tabs -->
            <div class="flex bg-gray-100 dark:bg-gray-700 rounded-md overflow-hidden mb-6">
                <button @click="activeTab = 'tracks'"
                    :class="activeTab === 'tracks' ? 'bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-100' :
                        'text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-600'"
                    class="flex-1 py-3 px-4 text-sm font-medium border-r border-gray-200 dark:border-gray-600 focus:outline-none">
                    Tracks
                </button>
                <button @click="activeTab = 'playlists'"
                    :class="activeTab === 'playlists' ? 'bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-100' :
                        'text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-600'"
                    class="flex-1 py-3 px-4 text-sm font-medium focus:outline-none">
                    Playlists
                </button>
            </div>

            <!-- Track List -->
            <div class="space-y-4" x-show="activeTab === 'tracks'">
                @foreach ($tracks as $track)
                    <!-- Track Item -->
                    {{-- <div
                        class="flex items-center bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm">
                        <img src="{{ $track->artwork_url ?? 'https://via.placeholder.com/64' }}"
                            alt="{{ $track->title }}" class="rounded-md" width="150" height="100" />
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

                    <x-sound-cloud.sound-cloud-player :track="$track" :height="166" :visual="false" />
                @endforeach
            </div>

            <!-- Playlist List -->
            <div class="space-y-4" x-show="activeTab === 'playlists'">
                <!-- Example Playlist Item -->
                <div
                    class="flex items-center bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-4 shadow-sm">
                    <img src="https://via.placeholder.com/64" alt="Playlist Cover" class="rounded-md mr-4" />
                    <div class="flex-1">
                        <h2 class="text-lg font-semibold dark:text-gray-100">Chill Vibes</h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">curated by <strong>herman
                                hedrick</strong></p>
                        <span
                            class="inline-block bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 text-xs px-2 py-1 rounded-full">
                            Chillout
                        </span>
                    </div>
                </div>
            </div>

            <!-- Load More Button -->
            <div class="text-center mt-8">
                <button class="text-red-500 text-sm font-medium hover:underline focus:outline-none">
                    Load more
                </button>
            </div>
        </div>
    </section>


    {{-- The Sticky Bottom Player --}}
    <div id="bottom-player" class="hidden">
    <div class="track-info">
        <img id="player-artwork" src="https://placehold.co/50x50/333/fff?text=No+Track" alt="Track Artwork">
        <div class="track-details">
            <div id="player-title" class="track-title">No Track Loaded</div>
            <div id="player-artist" class="artist-name"></div>
        </div>
    </div>

    <div class="controls">
        <button id="prev-button" title="Previous"><i class="fas fa-step-backward"></i></button>
        <button id="play-pause-button" title="Play/Pause"><i class="fas fa-play"></i></button>
        <button id="next-button" title="Next"><i class="fas fa-step-forward"></i></button>
    </div>

    <div class="progress-container">
        <div class="time" id="current-time">0:00</div>
        <div class="progress-bar" id="progress-bar">
            <div class="progress" id="progress"></div>
        </div>
        <div class="time" id="total-time">0:00</div>
    </div>

    <div class="volume-control">
        <i class="fas fa-volume-up"></i>
        <div class="volume-slider" id="volume-bar">
            <div class="volume" id="volume"></div>
        </div>
    </div>

    <div class="action-buttons">
        <button id="like-button" title="Like"><i class="far fa-heart"></i></button>
        <button id="share-button" title="Share"><i class="fas fa-share-alt"></i></button>
    </div>
</div>

    @push('js')
        <script>
            // IMPORTANT: Replace with your actual SoundCloud Client ID
            const SOUNDCLOUD_CLIENT_ID =
                "{{ config('services.soundcloud.client_id') }}"; // <--- GET THIS FROM SOUNDCLOUD DEVELOPERS

            // --- Player Class Definition (Same as before) ---
            class SoundCloudBottomPlayer {
                constructor(clientId) { // Removed initialTrackData from constructor
                    this.clientId = clientId;
                    this.audio = new Audio();
                    this.currentTrack = null;
                    this.isPlaying = false;
                    this.currentPlayingIframeId = null; // To track which iframe is playing

                    // DOM elements
                    this.playerElement = document.getElementById('bottom-player');
                    this.playerArtwork = document.getElementById('player-artwork');
                    this.playerTitle = document.getElementById('player-title');
                    this.playerArtist = document.getElementById('player-artist');
                    this.prevButton = document.getElementById('prev-button');
                    this.playPauseButton = document.getElementById('play-pause-button');
                    this.nextButton = document.getElementById('next-button');
                    this.currentTimeSpan = document.getElementById('current-time');
                    this.totalTimeSpan = document.getElementById('total-time');
                    this.progressBar = document.getElementById('progress-bar');
                    this.progress = document.getElementById('progress');
                    this.volumeBar = document.getElementById('volume-bar');
                    this.volume = document.getElementById('volume');

                    this.initEventListeners();
                    this.audio.volume = 0.7; // Default volume
                    this.updateVolumeBar();
                }

                // --- Utility Functions ---
                formatTime(seconds) {
                    const minutes = Math.floor(seconds / 60);
                    const remainingSeconds = Math.floor(seconds % 60);
                    return `${minutes}:${remainingSeconds < 10 ? '0' : ''}${remainingSeconds}`;
                }

                updateProgressBar() {
                    if (this.audio.duration) {
                        const percentage = (this.audio.currentTime / this.audio.duration) * 100;
                        this.progress.style.width = `${percentage}%`;
                        this.currentTimeSpan.textContent = this.formatTime(this.audio.currentTime);
                    }
                }

                updateVolumeBar() {
                    const percentage = this.audio.volume * 100;
                    this.volume.style.width = `${percentage}%`;
                }

                // --- Core Player Functions ---
                loadTrack(trackData, autoplayInBottomPlayer = true, sourceIframeId = null) {
                    if (!trackData || !trackData.stream_url) {
                        console.error('Invalid track data provided to player.');
                        return;
                    }

                    // If a track from an iframe is loaded, pause all other iframes
                    if (sourceIframeId && window.SC && window.SC.Widget) {
                        document.querySelectorAll('iframe[id^="sc-player-"]').forEach(iframe => {
                            if (iframe.id !== sourceIframeId) {
                                const widget = window.SC.Widget(iframe);
                                if (widget) {
                                    widget.pause();
                                }
                            }
                        });
                    }

                    this.currentTrack = trackData;
                    this.audio.src = `${trackData.stream_url}?client_id=${this.clientId}`;
                    this.currentPlayingIframeId = sourceIframeId; // Store which iframe triggered it

                    this.playerArtwork.src = trackData.artwork_url ||
                        `https://placehold.co/50x50/333/fff?text=${trackData.title.substring(0,1)}`;
                    this.playerTitle.textContent = trackData.title;
                    this.playerArtist.textContent = trackData.author_username;

                    this.playerElement.classList.remove('hidden'); // Show the player

                    // Reset progress and time
                    this.progress.style.width = '0%';
                    this.currentTimeSpan.textContent = '0:00';
                    this.totalTimeSpan.textContent = '0:00';

                    // Load audio to get duration
                    this.audio.load();
                    this.audio.addEventListener('loadedmetadata', () => {
                        this.totalTimeSpan.textContent = this.formatTime(this.audio.duration);
                    }, {
                        once: true
                    });

                    if (autoplayInBottomPlayer) {
                        this.playTrack();
                    } else {
                        this.playPauseButton.innerHTML = '<i class="fas fa-play"></i>';
                        this.isPlaying = false;
                    }

                    this.currentIframeWidget = sourceIframeId ? window.SC.Widget(document.getElementById(sourceIframeId)) : null;
                }

                syncWithIframe(play = true) {
                    if (this.currentIframeWidget) {
                        play ? this.currentIframeWidget.play() : this.currentIframeWidget.pause();
                    }
                }

                playTrack() {
                    this.audio.play().then(() => {
                        this.isPlaying = true;
                        this.playPauseButton.innerHTML = '<i class="fas fa-pause"></i>';
                        this.syncWithIframe(true); // Sync with iframe
                    }).catch(error => {
                        console.error("Error playing audio:", error);
                        this.isPlaying = false;
                        this.playPauseButton.innerHTML = '<i class="fas fa-play"></i>';
                    });
                }

                pauseTrack() {
                    this.audio.pause();
                    this.isPlaying = false;
                    this.playPauseButton.innerHTML = '<i class="fas fa-play"></i>';
                    this.syncWithIframe(false); // Sync with iframe
                }

                togglePlayPause() {
                    if (this.isPlaying) {
                        this.pauseTrack();
                    } else {
                        this.playTrack();
                    }
                }

                // --- Event Listeners Initialization ---
                initEventListeners() {
                    this.playPauseButton.addEventListener('click', () => this.togglePlayPause());
                    this.audio.addEventListener('timeupdate', () => this.updateProgressBar());
                    this.progressBar.addEventListener('click', (e) => {
                        const rect = this.progressBar.getBoundingClientRect();
                        const clickX = e.clientX - rect.left;
                        const percentage = clickX / rect.width;
                        this.audio.currentTime = this.audio.duration * percentage;
                    });
                    this.volumeBar.addEventListener('click', (e) => {
                        const rect = this.volumeBar.getBoundingClientRect();
                        const clickX = e.clientX - rect.left;
                        const percentage = clickX / rect.width;
                        this.audio.volume = percentage;
                        this.updateVolumeBar();
                    });

                    // Placeholder for playlist navigation
                    this.prevButton.addEventListener('click', () => {
                        console.log('Previous track (implement playlist logic)');
                    });
                    this.nextButton.addEventListener('click', () => {
                        console.log('Next track (implement playlist logic)');
                    });
                }
            }

            // --- Global Initialization ---
            let bottomPlayerInstance; // Declare a global variable for the player instance

            document.addEventListener('livewire:navigated', function() {
                // Initialize the bottom player. It will be hidden until a track is loaded.
                bottomPlayerInstance = new SoundCloudBottomPlayer(SOUNDCLOUD_CLIENT_ID);

                // Listen for messages from iframes (your SoundCloud embeds)
                window.addEventListener('message', (event) => {
                    if (event.origin !== window.location.origin) return;

                    const data = event.data;
                    if (!data || !data.track) return;

                    console.log('Received message:', data.type, data.track.title);

                    switch (data.type) {
                        case 'soundcloud-play':
                            bottomPlayerInstance.loadTrack(data.track, true, data.iframeId);
                            break;

                        case 'soundcloud-pause':
                            if (bottomPlayerInstance.currentTrack &&
                                bottomPlayerInstance.currentTrack.id === data.track.id) {
                                bottomPlayerInstance.pauseTrack();
                            }
                            break;

                        case 'soundcloud-finish':
                            if (bottomPlayerInstance.currentTrack &&
                                bottomPlayerInstance.currentTrack.id === data.track.id) {
                                // Auto-play next track if available
                                bottomPlayerInstance.playNextTrack();
                            }
                            break;
                    }
                });
            });
        </script>
    @endpush

</x-user::layout>
