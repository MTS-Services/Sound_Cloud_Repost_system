@if ($track && isset($track->permalink_url) && isset($track->id))
    <div class="soundcloud-embed-container" data-track-id="{{ $track->id }}">
        <iframe id="sc-player-{{ $track->id }}" width="100%" height="{{ $height }}"
            style="border-top: 2px solid #ff5500;" scrolling="no" frameborder="no" allow="autoplay"
            src="{{ $getEmbedSrc() }}">
        </iframe>
    </div>
@else
    <p>SoundCloud track data not available for embed.</p>
@endif
