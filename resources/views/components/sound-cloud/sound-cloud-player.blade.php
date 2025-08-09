{{-- @if ($track && $track->permalink_url)
    <div class="soundcloud-embed-container">
        <iframe width="100%" height="{{ $height }}" scrolling="no" frameborder="no" allow="autoplay"
            src="{{ $getEmbedSrc() }}">
        </iframe>
        <div
            style="font-size: 10px; color: #cccccc;line-break: anywhere;word-break: normal;overflow: hidden;white-space: nowrap;text-overflow: ellipsis; font-family: Interstate,Lucida Grande,Lucida Sans Unicode,Lucida Sans,Garuda,Verdana,Tahoma,sans-serif;font-weight: 100;">
            @if (isset($track->author_soundcloud_permalink_url) && isset($track->author_username))
                <a href="{{ $track->author_soundcloud_permalink_url }}" title="{{ $track->author_username }}"
                    target="_blank" style="color: #cccccc; text-decoration: none;">
                    {{ $track->author_username }}
                </a>
            @endif
            @if (isset($track->title) && isset($track->permalink_url))
                ·
                <a href="{{ $track->permalink_url }}" title="{{ $track->title }}" target="_blank"
                    style="color: #cccccc; text-decoration: none;">
                    {{ $track->title }}
                </a>
            @endif
        </div>
    </div>
@else
    <p>SoundCloud track data not available.</p>
@endif --}}


@if ($track && isset($track->permalink_url) && isset($track->id))
    <div class="soundcloud-embed-container" data-track-id="{{ $track->id }}">
        <iframe id="sc-player-{{ $track->id }}" {{-- Unique ID for each iframe --}} width="100%" height="{{ $height }}"
            style="border-top: 2px solid #ff5500;" {{-- This will be 166 when visual is false --}} scrolling="no" frameborder="no" allow="autoplay"
            {{-- This attribute is correctly present --}} src="{{ $getEmbedSrc() }}"> {{-- This will include &visual=false --}}
        </iframe style="pointer-events: none;">
        {{-- <div
            style="font-size: 10px; color: #cccccc;line-break: anywhere;word-break: normal;overflow: hidden;white-space: nowrap;text-overflow: ellipsis; font-family: Interstate,Lucida Grande,Lucida Sans Unicode,Lucida Sans,Garuda,Verdana,Tahoma,sans-serif;font-weight: 100;">
            @if (isset($track->author_soundcloud_permalink_url) && isset($track->author_username))
                <a href="{{ $track->author_soundcloud_permalink_url }}" title="{{ $track->author_username }}"
                    target="_blank" style="color: #cccccc; text-decoration: none;">
                    {{ $track->author_username }}
                </a>
            @endif
            @if (isset($track->title) && isset($track->permalink_url))
                ·
                <a href="{{ $track->permalink_url }}" title="{{ $track->title }}" target="_blank"
                    style="color: #cccccc; text-decoration: none;">
                    {{ $track->title }}
                </a>
            @endif
        </div> --}}
    </div>

    {{-- Script to initialize SoundCloud Widget API and send messages --}}
    @push('js')
        <script>
            (function() {
                var iframe = document.getElementById('sc-player-{{ $track->id }}');
                if (!iframe) {
                    console.warn('SoundCloud iframe with ID sc-player-{{ $track->id }} not found.');
                    return;
                }

                // Load SoundCloud Widget API only if it hasn't been loaded yet
                if (typeof SC === 'undefined' || !SC.Widget) {
                    var script = document.createElement('script');
                    script.src = 'https://w.soundcloud.com/player/api.js';
                    script.async = true; // Load asynchronously
                    document.body.appendChild(script);

                    script.onload = function() {
                        console.log('SoundCloud Widget API loaded.');
                        initSoundCloudWidget();
                    };
                    script.onerror = function() {
                        console.error('Failed to load SoundCloud Widget API.');
                    };
                } else {
                    // API already loaded, just initialize widget
                    initSoundCloudWidget();
                }

                function initSoundCloudWidget() {
                    var widget = SC.Widget(iframe);
                    var trackData = @json($track); // Pass full track data from Laravel to JS

                    widget.bind(SC.Widget.Events.PLAY, function() {
                        // Send a message to the parent window when this iframe starts playing
                        window.parent.postMessage({
                            type: 'soundcloud-play',
                            track: trackData,
                            iframeId: 'sc-player-{{ $track->id }}' // Identify which iframe started playing
                        }, window.location.origin); // Specify origin for security
                        console.log('iframe play event sent for:', trackData.title);
                    });

                    widget.bind(SC.Widget.Events.PAUSE, function() {
                        // Send a message when this iframe is paused
                        window.parent.postMessage({
                            type: 'soundcloud-pause',
                            track: trackData,
                            iframeId: 'sc-player-{{ $track->id }}'
                        }, window.location.origin);
                        console.log('iframe pause event sent for:', trackData.title);
                    });

                    widget.bind(SC.Widget.Events.FINISH, function() {
                        // Send a message when this iframe finishes playing
                        window.parent.postMessage({
                            type: 'soundcloud-finish',
                            track: trackData,
                            iframeId: 'sc-player-{{ $track->id }}'
                        }, window.location.origin);
                        console.log('iframe finish event sent for:', trackData.title);
                    });
                }
            })();
        </script>
    @endpush
@else
    <p>SoundCloud track data not available for embed.</p>
@endif





{{-- @if ($track && $track->permalink_url && $track->id)
    <div class="soundcloud-embed-container" data-track-id="{{ $track->id }}">
        <iframe id="sc-player-{{ $track->id }}" //-- Unique ID for each iframe --// width="100%" height="{{ $height }}"
            scrolling="no" frameborder="no" allow="autoplay" src="{{ $getEmbedSrc() }}">
        </iframe>
        <div
            style="font-size: 10px; color: #cccccc;line-break: anywhere;word-break: normal;overflow: hidden;white-space: nowrap;text-overflow: ellipsis; font-family: Interstate,Lucida Grande,Lucida Sans Unicode,Lucida Sans,Garuda,Verdana,Tahoma,sans-serif;font-weight: 100;">
            @if (isset($track->author_soundcloud_permalink_url) && isset($track->author_username))
                <a href="{{ $track->author_soundcloud_permalink_url }}" title="{{ $track->author_username }}"
                    target="_blank" style="color: #cccccc; text-decoration: none;">
                    {{ $track->author_username }}
                </a>
            @endif
            @if (isset($track->title) && isset($track->permalink_url))
                ·
                <a href="{{ $track->permalink_url }}" title="{{ $track->title }}" target="_blank"
                    style="color: #cccccc; text-decoration: none;">
                    {{ $track->title }}
                </a>
            @endif
        </div>
    </div>

    //-- Script to initialize SoundCloud Widget API and send messages --//
    @push('js')
        <script>
            (function() {
                var iframe = document.getElementById('sc-player-{{ $track->id }}');
                if (iframe && typeof SC === 'undefined') {
                    // Load SoundCloud Widget API only once
                    var script = document.createElement('script');
                    script.src = 'https://w.soundcloud.com/player/api.js';
                    document.body.appendChild(script);
                    script.onload = function() {
                        initSoundCloudWidget();
                    };
                } else if (iframe && typeof SC !== 'undefined') {
                    // API already loaded, just initialize widget
                    initSoundCloudWidget();
                }

                function initSoundCloudWidget() {
                    var widget = SC.Widget(iframe);
                    var trackData = @json($track); // Pass full track data

                    widget.bind(SC.Widget.Events.PLAY, function() {
                        // Send a message to the parent window when this iframe starts playing
                        window.parent.postMessage({
                            type: 'soundcloud-play',
                            track: trackData,
                            iframeId: 'sc-player-{{ $track->id }}' // Identify which iframe started playing
                        }, window.location.origin); // Ensure security by specifying origin
                    });

                    widget.bind(SC.Widget.Events.PAUSE, function() {
                        // Optionally send pause event if needed for bottom player to reflect
                        window.parent.postMessage({
                            type: 'soundcloud-pause',
                            track: trackData,
                            iframeId: 'sc-player-{{ $track->id }}'
                        }, window.location.origin);
                    });

                    widget.bind(SC.Widget.Events.FINISH, function() {
                        // Optionally send finish event
                        window.parent.postMessage({
                            type: 'soundcloud-finish',
                            track: trackData,
                            iframeId: 'sc-player-{{ $track->id }}'
                        }, window.location.origin);
                    });

                    // You can store widget instances if you need to control them later
                    // window.soundCloudWidgets = window.soundCloudWidgets || {};
                    // window.soundCloudWidgets['{{ $track->id }}'] = widget;
                }
            })();
        </script>


    @endpush
@else
    <p>SoundCloud track data not available for embed.</p>
@endif --}}
