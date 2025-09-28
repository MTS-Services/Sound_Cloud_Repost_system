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
            (function() {
                var iframe = document.getElementById('sc-player-{{ $track->id }}');
                if (!iframe) {
                    console.warn(
                        'SoundCloud iframe with ID sc-player-{{ $track->id }} not found. Cannot initialize widget.');
                    return;
                }

                // Function to load the API script if needed, then initialize the widget
                function loadSoundCloudApiAndInit() {
                    // Check if the global SC object and Widget class are available
                    if (typeof SC === 'undefined' || !SC.Widget) {
                        // API not loaded: load the script
                        var script = document.createElement('script');
                        script.src = 'https://w.soundcloud.com/player/api.js';
                        script.async = true;

                        // Prevent adding the script multiple times if this component is rendered several times
                        if (!document.querySelector('script[src="https://w.soundcloud.com/player/api.js"]')) {
                            document.body.appendChild(script);
                        }

                        // We rely on the main playlist script for full initialization,
                        // but keep this fallback for non-playlist pages if necessary.
                        script.onload = function() {
                            console.log('SoundCloud Widget API loaded dynamically (from component).');
                            // initSoundCloudWidget(); // Removed: Prevent duplicate initialization of listeners
                        };
                        script.onerror = function() {
                            console.error('Failed to load SoundCloud Widget API.');
                        };
                    } else {
                        // API already loaded: only initialize if this is the first player, otherwise rely on the main playlist controller
                        // This component's direct initialization is now redundant for playlist control.
                        // initSoundCloudWidget(); // Removed: Prevent duplicate initialization of listeners
                    }
                }

                // Note: The post-message event binding is now typically handled by the main playlist controller
                // to avoid conflicting event handlers, but we will keep the postMessage logic here
                // in case this player is used outside the main playlist structure.
                function initSoundCloudWidget() {
                    // Final check before trying to initialize
                    if (typeof SC === 'undefined' || !SC.Widget) {
                        console.error('SC.Widget is not available (component level).');
                        return;
                    }

                    var widget = SC.Widget(iframe);
                    var trackData = @json($track);

                    // Helper function to send post messages
                    const postMessage = (type) => {
                        window.parent.postMessage({
                            type: type,
                            track: trackData,
                            iframeId: 'sc-player-{{ $track->id }}'
                        }, '*'); // Using '*' for origin allows communication regardless of hosting domain
                        console.log(`SoundCloud event: ${type} sent for: ${trackData.title}`);
                    };

                    widget.bind(SC.Widget.Events.PLAY, function() { postMessage('soundcloud-play'); });
                    widget.bind(SC.Widget.Events.PAUSE, function() { postMessage('soundcloud-pause'); });
                    widget.bind(SC.Widget.Events.FINISH, function() { postMessage('soundcloud-finish'); });
                    widget.bind(SC.Widget.Events.READY, function() { console.log(`SoundCloud Widget ready for track: ${trackData.title} (component level).`); });
                }
                
                // For a true playlist, we only need the API loaded. The main JS handles control.
                // However, we call this to ensure the events are broadcast even if the main playlist controller is not running.
                loadSoundCloudApiAndInit();
                
                // If the API is already loaded, we can attempt to initialize for event broadcasting.
                if (typeof SC !== 'undefined' && SC.Widget) {
                    initSoundCloudWidget();
                }

            })();
        </script>
    @endpush
@else
    <p>SoundCloud track data not available for embed.</p>
@endif
