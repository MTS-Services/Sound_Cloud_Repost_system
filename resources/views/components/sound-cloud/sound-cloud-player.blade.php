@if ($track && isset($track->permalink_url) && isset($track->id))
    {{-- data-track-id is crucial for the playlist script to find the iframe ID --}}
    <div class="soundcloud-embed-container" data-track-id="{{ $track->id }}">
        <iframe id="sc-player-{{ $track->id }}" width="100%" height="{{ $height }}"
            style="border-top: 2px solid #ff5500;" scrolling="no" frameborder="no" allow="autoplay"
            src="{{ $getEmbedSrc() }}">
        </iframe>
    </div>

    @push('js')
        <script>
            // Simplified component-level script - most logic moved to global playlist controller
            (function() {
                var trackId = '{{ $track->id }}';
                var iframeId = 'sc-player-' + trackId;
                
                console.log('SoundCloud component initialized for track:', trackId);
                
                // The global playlist controller will handle this iframe
                // We just need to ensure the API is loaded
                function ensureSoundCloudAPI() {
                    if (typeof SC === 'undefined' || !SC.Widget) {
                        // Check if the global controller exists and let it handle API loading
                        if (!window.SoundCloudPlaylistController) {
                            console.log('Loading SoundCloud API for component:', trackId);
                            var script = document.createElement('script');
                            script.src = 'https://w.soundcloud.com/player/api.js';
                            script.async = true;
                            
                            if (!document.querySelector('script[src="https://w.soundcloud.com/player/api.js"]')) {
                                document.head.appendChild(script);
                            }
                            
                            script.onload = function() {
                                console.log('SoundCloud API loaded for component:', trackId);
                            };
                            
                            script.onerror = function() {
                                console.error('Failed to load SoundCloud API for component:', trackId);
                            };
                        }
                    }
                }
                
                // Simple initialization - the playlist controller will do the heavy lifting
                ensureSoundCloudAPI();
                
                // Optional: Listen for playlist events specific to this track
                window.addEventListener('soundcloud-playlist-event', function(event) {
                    var detail = event.detail;
                    if (detail.track && detail.track.iframeId === iframeId) {
                        console.log('Playlist event for track ' + trackId + ':', detail.type);
                        
                        // You can add custom logic here for UI updates
                        // For example, highlighting the current playing track
                        var container = document.querySelector('[data-track-id="' + trackId + '"]');
                        if (container) {
                            if (detail.type === 'soundcloud-play') {
                                container.classList.add('playing');
                            } else if (detail.type === 'soundcloud-pause' || detail.type === 'soundcloud-finish') {
                                container.classList.remove('playing');
                            }
                        }
                    }
                });

            })();
        </script>
    @endpush
@else
    <p>SoundCloud track data not available for embed.</p>
@endif