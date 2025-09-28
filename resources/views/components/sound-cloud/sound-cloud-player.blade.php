@if ($track && isset($track->permalink_url) && isset($track->id))
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

                        script.onload = function() {
                            console.log('SoundCloud Widget API loaded dynamically.');
                            initSoundCloudWidget();
                        };
                        script.onerror = function() {
                            console.error('Failed to load SoundCloud Widget API.');
                        };
                    } else {
                        // API already loaded: just initialize widget
                        initSoundCloudWidget();
                    }
                }

                function initSoundCloudWidget() {
                    // Final check before trying to initialize
                    if (typeof SC === 'undefined' || !SC.Widget) {
                        console.error('SC.Widget is not available.');
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

                    widget.bind(SC.Widget.Events.PLAY, function() {
                        postMessage('soundcloud-play');
                    });

                    widget.bind(SC.Widget.Events.PAUSE, function() {
                        postMessage('soundcloud-pause');
                    });

                    widget.bind(SC.Widget.Events.FINISH, function() {
                        postMessage('soundcloud-finish');
                    });

                    widget.bind(SC.Widget.Events.READY, function() {
                        console.log(`SoundCloud Widget ready for track: ${trackData.title}`);
                    });
                }

                // Start the process
                loadSoundCloudApiAndInit();

            })();
        </script>
    @endpush
@else
    <p>SoundCloud track data not available for embed.</p>
@endif
