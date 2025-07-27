@if ($track && $track->permalink_url)
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
@endif
