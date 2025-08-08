<?php

namespace App\View\Components\SoundCloud;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SoundCloudPlayer extends Component
{
    public $track;
    public $height;
    public $autoPlay;
    public $color;
    public $hideRelated;
    public $showComments;
    public $showUser;
    public $showReposts;
    public $showTeaser;
    public $visual; // This controls the player style (compact vs. large visual)

    /**
     * Create a new component instance.
     *
     * @param object $track The SoundCloud track data object.
     * @param int $height Height of the player. Automatically adjusted if visual changes.
     * @param bool $autoPlay Whether the player should auto-play (default: false).
     * @param string $color Player color (hex code, default: '#ff5500').
     * @param bool $hideRelated Whether to hide related tracks (default: false).
     * @param bool $showComments Whether to show comments (default: true).
     * @param bool $showUser Whether to show user info (default: true).
     * @param bool $showReposts Whether to show reposts (default: false).
     * @param bool $showTeaser Whether to show the teaser (default: true).
     * @param bool $visual Whether to show the visual player (true for large, false for compact).
     * @return void
     *
     */
    public function __construct(
        $track,
        $height = null, // Set to null to allow default based on visual
        $autoPlay = false,
        $color = '#ff5500',
        $hideRelated = false,
        $showComments = true,
        $showUser = true,
        $showReposts = false,
        $showTeaser = true,
        $visual = false // Default to compact player (visual=false)
    ) {
        $this->track = $track;
        $this->autoPlay = $autoPlay;
        $this->color = $color;
        $this->hideRelated = $hideRelated;
        $this->showComments = $showComments;
        $this->showUser = $showUser;
        $this->showReposts = $showReposts;
        $this->showTeaser = $showTeaser;
        $this->visual = $visual;

        // Set default height based on visual mode if not explicitly provided
        if (is_null($height)) {
            $this->height = $this->visual ? 300 : 166;
        } else {
            $this->height = $height;
        }
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.sound-cloud.sound-cloud-player');
    }

    /**
     * Generate the SoundCloud embed URL.
     *
     * @return string|null
     */
    public function getEmbedSrc()
    {
        if (!isset($this->track->uri)) {
            return null;
        }

        $soundcloudApiUrl = $this->track->uri;

        $params = [
            'url' => $soundcloudApiUrl,
            'color' => str_replace('#', '%23', $this->color), // URL encode #
            'auto_play' => $this->autoPlay ? 'true' : 'false',
            'hide_related' => $this->hideRelated ? 'true' : 'false',
            'show_comments' => $this->showComments ? 'true' : 'false',
            'show_user' => $this->showUser ? 'true' : 'false',
            'show_reposts' => $this->showReposts ? 'true' : 'false',
            // 'show_teaser' => $this->showTeaser ? 'true' : 'false',
            // 'visual' => $this->visual ? 'true' : 'false',
            'show_teaser' => 'false',
            'visual' => 'false',
        ];

        // Ensure 'url' parameter is URL-encoded separately
        $queryParams = [];
        foreach ($params as $key => $value) {
            if ($key === 'url') {
                $queryParams[] = $key . '=' . urlencode($value);
            } else {
                $queryParams[] = $key . '=' . $value;
            }
        }

        return "https://w.soundcloud.com/player/?" . implode('&', $queryParams);
    }
}
