<x-user::layout>
    @push('css')
        <style>
            /* Your existing CSS for #bottom-player and its children */
            #bottom-player {
                position: fixed;
                bottom: 0;
                left: 0;
                width: 100%;
                background-color: #222;
                color: #ccc;
                padding: 10px 20px;
                box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.5);
                z-index: 1000;
                display: flex;
                align-items: center;
                justify-content: space-between;
            }

            #bottom-player .controls button {
                background: none;
                border: none;
                color: #eee;
                font-size: 1.5rem;
                margin: 0 10px;
                cursor: pointer;
                transition: color 0.2s;
            }

            #bottom-player .controls button:hover {
                color: #ff5500;
            }

            #bottom-player .track-info {
                display: flex;
                align-items: center;
                flex-grow: 1;
                margin-left: 20px;
            }

            #bottom-player .track-info img {
                width: 50px;
                height: 50px;
                border-radius: 5px;
                margin-right: 15px;
            }

            #bottom-player .progress-bar-container {
                flex-grow: 2;
                margin: 0 20px;
                display: flex;
                align-items: center;
            }

            #bottom-player .progress-bar {
                width: 100%;
                height: 5px;
                background-color: #555;
                border-radius: 2.5px;
                cursor: pointer;
                position: relative;
            }

            #bottom-player .progress {
                height: 100%;
                background-color: #ff5500;
                width: 0%;
                border-radius: 2.5px;
            }

            #bottom-player .time {
                font-size: 0.8rem;
                margin: 0 10px;
            }

            #bottom-player .volume-control {
                display: flex;
                align-items: center;
            }

            #bottom-player .volume-slider {
                width: 80px;
                height: 5px;
                background-color: #555;
                border-radius: 2.5px;
                cursor: pointer;
                margin-left: 10px;
            }

            #bottom-player .volume {
                height: 100%;
                background-color: #ff5500;
                width: 100%;
                border-radius: 2.5px;
            }

            #bottom-player .action-buttons button {
                background-color: #ff5500;
                color: white;
                padding: 5px 15px;
                border-radius: 5px;
                font-size: 0.9rem;
                margin-left: 15px;
                cursor: pointer;
                transition: background-color 0.2s;
            }

            #bottom-player .action-buttons button:hover {
                background-color: #e04a00;
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
            <div>
                <div id="player-title" class="font-bold text-sm">No Track Loaded</div>
                <div id="player-artist" class="text-xs text-gray-400"></div>
            </div>
        </div>

        <div class="controls flex items-center">
            <button id="prev-button"><i class="fas fa-backward"></i></button>
            <button id="play-pause-button"><i class="fas fa-play"></i></button>
            <button id="next-button"><i class="fas fa-forward"></i></button>
        </div>

        <div class="progress-bar-container">
            <span id="current-time" class="time">0:00</span>
            <div id="progress-bar" class="progress-bar">
                <div id="progress" class="progress"></div>
            </div>
            <span id="total-time" class="time">0:00</span>
        </div>

        <div class="volume-control flex items-center">
            <i class="fas fa-volume-up"></i>
            <div id="volume-bar" class="volume-slider">
                <div id="volume" class="volume"></div>
            </div>
        </div>

        <div class="action-buttons">
            <button>Repost</button>
            <button>Share</button>
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
                }

                playTrack() {
                    this.audio.play().then(() => {
                        this.isPlaying = true;
                        this.playPauseButton.innerHTML = '<i class="fas fa-pause"></i>';
                        // If we know which iframe started it, pause it.
                        // This creates a "transfer" of playback to the bottom player.
                        if (this.currentPlayingIframeId && window.SC && window.SC.Widget) {
                            const iframe = document.getElementById(this.currentPlayingIframeId);
                            if (iframe) {
                                window.SC.Widget(iframe).pause();
                            }
                        }
                    }).catch(error => {
                        console.error("Autoplay was prevented or error playing audio in bottom player:", error);
                        this.isPlaying = false;
                        this.playPauseButton.innerHTML = '<i class="fas fa-play"></i>';
                    });
                }

                pauseTrack() {
                    this.audio.pause();
                    this.isPlaying = false;
                    this.playPauseButton.innerHTML = '<i class="fas fa-play"></i>';
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

            document.addEventListener('DOMContentLoaded', () => {
                // Initialize the bottom player. It will be hidden until a track is loaded.
                bottomPlayerInstance = new SoundCloudBottomPlayer(SOUNDCLOUD_CLIENT_ID);

                // Listen for messages from iframes (your SoundCloud embeds)
                window.addEventListener('message', (event) => {
                    // Ensure the message is from a trusted origin (your own domain)
                    if (event.origin !== window.location.origin) {
                        // console.warn('Received message from untrusted origin:', event.origin);
                        return;
                    }

                    const data = event.data;

                    if (data.type === 'soundcloud-play' && data.track) {
                        console.log('Received play event from iframe:', data.iframeId, data.track.title);
                        // Load the track into the bottom player and auto-play it
                        bottomPlayerInstance.loadTrack(data.track, true, data.iframeId);
                    }
                    // You could also handle 'soundcloud-pause' if you want the bottom player to pause when an iframe pauses
                    // if (data.type === 'soundcloud-pause' && data.track && bottomPlayerInstance.currentTrack && bottomPlayerInstance.currentTrack.id === data.track.id) {
                    //     bottomPlayerInstance.pauseTrack();
                    // }
                });

                // Optional: If you want to initially load a track into the bottom player on page load
                // const initialTrackData = @json($initialTrack ?? null);
                // if (initialTrackData) {
                //     bottomPlayerInstance.loadTrack(initialTrackData, false); // Load but don't autoplay on page load
                // }
            });
        </script>
    @endpush

</x-user::layout>
