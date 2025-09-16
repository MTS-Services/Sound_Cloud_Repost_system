<?php

namespace App\Livewire\User;

use App\Jobs\NotificationMailSent;
use App\Models\Campaign;
use App\Models\Playlist;
use App\Models\Repost;
use App\Models\Track;
use App\Services\SoundCloud\SoundCloudService;
use App\Services\User\AnalyticsService;
use App\Services\User\CampaignManagement\CampaignService;
use Livewire\Component;
use Illuminate\Support\Facades\Log;
use Livewire\WithPagination;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Http;
use Livewire\Attributes\Locked;
use Throwable;

class Chart extends Component
{
    use WithPagination;

    protected AnalyticsService $analyticsService;
    protected ?CampaignService $campaignService = null;
    protected ?SoundCloudService $soundCloudService = null;
    #[Locked]
    protected string $baseUrl = 'https://api.soundcloud.com';

    public $tracksPerPage = 20;
    public $activeTab = 'listView';
    public $playing = null;
    // public $topTracks = [];

    public function boot(AnalyticsService $analyticsService, CampaignService $campaignService, SoundCloudService $soundCloudService)
    {
        $this->analyticsService = $analyticsService;
        $this->campaignService = $campaignService;
        $this->soundCloudService = $soundCloudService;
    }

    public function refresh()
    {
        $this->getTopTrackData();
    }

    public function getTopTrackData(): LengthAwarePaginator
    {
        // try {
        return $this->analyticsService->getPaginatedTrackAnalytics(
            filter: 'last_week',
            dateRange: null,
            genres: [],
            perPage: $this->tracksPerPage,
            page: $this->getPage(),
            actionType: Campaign::class
        );
        // } catch (\Exception $e) {
        //     Log::error('Paginated track data loading failed', ['error' => $e->getMessage()]);
        //     return new LengthAwarePaginator([], 0, $this->tracksPerPage, $this->getPage());
        // }
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function baseValidation($encryptedCampaignId, $encryptedTrackUrn)
    {
        $this->soundCloudService->ensureSoundCloudConnection(user());
        $this->soundCloudService->refreshUserTokenIfNeeded(user());
        if ($this->campaignService->getCampaigns()->where('id', decrypt($encryptedCampaignId))->where('user_urn', user()->urn)->exists()) {
            $this->dispatch('alert', type: 'error', message: 'You cannot act on your own campaign.');
            return null;
        }
        $campaign = $this->campaignService->getCampaign($encryptedCampaignId);
        $campaign->load('music.user');
        if (decrypt($encryptedTrackUrn) != $campaign->music->urn) {
            $this->dispatch('alert', type: 'error', message: 'Something went wrong. Please try again.');
            return null;
        }
        return $campaign;
    }

    public function likeTrack($encryptedCampaignId, $encryptedTrackUrn)
    {
        try {
            $campaign = $this->baseValidation($encryptedCampaignId, $encryptedTrackUrn);
            if (!$campaign) {
                return;
            }

            $soundcloudRepostId = null;

            $httpClient = Http::withHeaders([
                'Authorization' => 'OAuth ' . user()->token,
            ]);
            $response = null;

            switch ($campaign->music_type) {
                case Track::class:
                    $response = $httpClient->post("{$this->baseUrl}/likes/tracks/{$campaign->music->urn}");
                    break;
                case Playlist::class:
                    $response = $httpClient->post("{$this->baseUrl}/likes/playlists/{$campaign->music->urn}");
                    break;
                default:
                    $this->dispatch('alert', type: 'error', message: 'Something went wrong. Please try again.');
                    return;
            }           
            if ($response->successful()) {
                $soundcloudRepostId = $campaign->music->soundcloud_track_id;
                $this->campaignService->likeCampaign($campaign, user());
                $this->dispatch('alert', type: 'success', message: 'Like successful.');
            } else {
                Log::error("SoundCloud Repost Failed: " . $response->body());
                $this->dispatch('alert', type: 'error', message: 'Something went wrong. Please try again.');
            }
        } catch (Throwable $e) {
            Log::error("Error in repost method: " . $e->getMessage(), [
                'exception' => $e,
                'campaign_id_input' => decrypt($encryptedCampaignId),
                'user_urn' => user()->urn ?? 'N/A',
            ]);
            $this->dispatch('alert', type: 'error', message: 'An unexpected error occurred. Please try again later.');
            return;
        }
    }
    public function repostTrack($encryptedCampaignId, $encryptedTrackUrn)
    {
        try {
            $campaign = $this->baseValidation($encryptedCampaignId, $encryptedTrackUrn);

            if (!$campaign) {
                return;
            }

            if (Repost::where('reposter_urn', user()->urn)->where('campaign_id', $campaign->id)->exists()) {
                $this->dispatch('alert', type: 'error', message: 'You have already reposted this campaign.');
                return;
            }

            $soundcloudRepostId = null;

            $httpClient = Http::withHeaders([
                'Authorization' => 'OAuth ' . user()->token,
            ]);
            $response = null;

            switch ($campaign->music_type) {
                case Track::class:
                    $response = $httpClient->post("{$this->baseUrl}/reposts/tracks/{$campaign->music->urn}");
                    break;
                case Playlist::class:
                    $response = $httpClient->post("{$this->baseUrl}/reposts/playlists/{$campaign->music->urn}");
                    break;
                default:
                    $this->dispatch('alert', type: 'error', message: 'Something went wrong. Please try again.');
                    return;
            }
            $data = [
                'likeable' => false,
                'comment' => false,
                'follow' => false
            ];
            if ($response->successful()) {
                $repostEmailPermission = hasEmailSentPermission('em_repost_accepted', $campaign->user->urn);
                if ($repostEmailPermission) {
                    $datas = [
                        [
                            'email' => $campaign->user->email,
                            'subject' => 'Repost Notification',
                            'title' => 'Dear ' . $campaign->user->name,
                            'body' => 'Your ' . $campaign->title . 'campaign has been reposted successfully.',
                        ],
                    ];
                    NotificationMailSent::dispatch($datas);
                }
                $soundcloudRepostId = $campaign->music->soundcloud_track_id;
                $this->campaignService->syncReposts($campaign, user(), $soundcloudRepostId, $data);
                $this->dispatch('alert', type: 'success', message: 'Campaign music reposted successfully.');
            } else {
                Log::error("SoundCloud Repost Failed: " . $response->body());
                $this->dispatch('alert', type: 'error', message: 'Something went wrong. Please try again.');
            }
        } catch (Throwable $e) {
            Log::error("Error in repost method: " . $e->getMessage(), [
                'exception' => $e,
                'campaign_id_input' => decrypt($encryptedCampaignId),
                'user_urn' => user()->urn ?? 'N/A',
            ]);
            $this->dispatch('alert', type: 'error', message: 'An unexpected error occurred. Please try again later.');
            return;
        }
    }
    // // Music Functionality
    // public $songs = [];
    // public $currentIndex = null;
    // public $isPlaying = false;

    // public function mount()
    // {
    //     $this->songs = [
    //         ['title' => 'Feel Alone', 'artist' => 'Dilip Wannigamage', 'duration' => '2:19'],
    //         ['title' => 'Midnight Dreams', 'artist' => 'Luna Artist', 'duration' => '3:45'],
    //         ['title' => 'Ocean Waves', 'artist' => 'Nature Sounds', 'duration' => '4:12'],
    //         ['title' => 'City Lights', 'artist' => 'Urban Beats', 'duration' => '3:28'],
    //     ];
    // }

    // public function playSong($index)
    // {
    //     $this->currentIndex = $index;
    //     $this->isPlaying = true;
    // }

    // public function togglePlay()
    // {
    //     if ($this->currentIndex === null) {
    //         $this->playSong(0);
    //     } else {
    //         $this->isPlaying = !$this->isPlaying;
    //     }
    // }

    // public function nextSong()
    // {
    //     if ($this->currentIndex === null) {
    //         $this->playSong(0);
    //         return;
    //     }

    //     $this->currentIndex = ($this->currentIndex + 1) % count($this->songs);
    //     $this->isPlaying = true;
    // }

    // public function prevSong()
    // {
    //     if ($this->currentIndex === null) {
    //         $this->playSong(0);
    //         return;
    //     }

    //     $this->currentIndex = ($this->currentIndex - 1 + count($this->songs)) % count($this->songs);
    //     $this->isPlaying = true;
    // }


    // public $currentSong = null;
    // public $isPlaying = false;
    // public $currentIndex = 0;
    // public $currentTime = 0;
    // public $duration = 0;
    // public $volume = 70;
    // public $progressPercent = 0;

    // public $songs = [
    //     [
    //         'id' => 1,
    //         'title' => 'Feel Alone',
    //         'artist' => 'Dilip Wannigamage',
    //         'duration' => '2:19'
    //     ],
    //     [
    //         'id' => 2,
    //         'title' => 'Midnight Dreams',
    //         'artist' => 'Luna Artist',
    //         'duration' => '3:45'
    //     ],
    //     [
    //         'id' => 3,
    //         'title' => 'Ocean Waves',
    //         'artist' => 'Nature Sounds',
    //         'duration' => '4:12'
    //     ],
    //     [
    //         'id' => 4,
    //         'title' => 'City Lights',
    //         'artist' => 'Urban Beats',
    //         'duration' => '3:28'
    //     ],
    //     [
    //         'id' => 5,
    //         'title' => 'Peaceful Mind',
    //         'artist' => 'Meditation Music',
    //         'duration' => '5:30'
    //     ],
    //     [
    //         'id' => 6,
    //         'title' => 'Summer Vibes',
    //         'artist' => 'Chill Collective',
    //         'duration' => '3:55'
    //     ]
    // ];

    // public function mount()
    // {
    //     // Initialize with first song data but not playing
    //     $this->currentSong = $this->songs[0];
    //     $this->duration = $this->parseTime($this->currentSong['duration']);
    // }

    // public function playSong($index)
    // {
    //     $this->currentIndex = $index;
    //     $this->currentSong = $this->songs[$index];
    //     $this->isPlaying = true;
    //     $this->currentTime = 0;
    //     $this->progressPercent = 0;
    //     $this->duration = $this->parseTime($this->currentSong['duration']);

    //     $this->dispatch('song-changed', [
    //         'song' => $this->currentSong,
    //         'isPlaying' => $this->isPlaying
    //     ]);
    // }

    // public function togglePlay()
    // {
    //     if (!$this->currentSong) {
    //         $this->playSong(0);
    //         return;
    //     }

    //     $this->isPlaying = !$this->isPlaying;

    //     $this->dispatch('play-toggled', [
    //         'isPlaying' => $this->isPlaying
    //     ]);
    // }

    // public function previousSong()
    // {
    //     $newIndex = $this->currentIndex > 0 ? $this->currentIndex - 1 : count($this->songs) - 1;
    //     $this->playSong($newIndex);
    // }

    // public function nextSong()
    // {
    //     $newIndex = $this->currentIndex < count($this->songs) - 1 ? $this->currentIndex + 1 : 0;
    //     $this->playSong($newIndex);
    // }

    // public function seekTo($percent)
    // {
    //     $this->progressPercent = max(0, min(100, $percent));
    //     $this->currentTime = floor(($this->duration * $this->progressPercent) / 100);

    //     $this->dispatch('seek-to', [
    //         'percent' => $this->progressPercent,
    //         'currentTime' => $this->currentTime
    //     ]);
    // }

    // public function setVolume($volume)
    // {
    //     $this->volume = max(0, min(100, $volume));

    //     $this->dispatch('volume-changed', [
    //         'volume' => $this->volume
    //     ]);
    // }

    // public function updateProgress()
    // {
    //     if ($this->isPlaying && $this->duration > 0) {
    //         $this->currentTime++;
    //         $this->progressPercent = ($this->currentTime / $this->duration) * 100;

    //         if ($this->currentTime >= $this->duration) {
    //             $this->nextSong();
    //         }
    //     }
    // }

    // private function parseTime($timeString)
    // {
    //     [$minutes, $seconds] = explode(':', $timeString);
    //     return (int)$minutes * 60 + (int)$seconds;
    // }

    // public function formatTime($seconds)
    // {
    //     $mins = floor($seconds / 60);
    //     $secs = $seconds % 60;
    //     return sprintf('%d:%02d', $mins, $secs);
    // }










    // public $currentSong = null;
    // public $isPlaying = false;
    // public $currentIndex = 0;
    // public $currentTime = 0;
    // public $duration = 0;
    // public $volume = 70;
    // public $progressPercent = 0;

    // public $songs = [
    //     [
    //         'id' => 1,
    //         'title' => 'Feel Alone',
    //         'artist' => 'Dilip Wannigamage',
    //         'duration' => '2:19',
    //         'url' => 'https://www.soundjay.com/misc/sounds/bell-ringing-05.mp3'
    //     ],
    //     [
    //         'id' => 2,
    //         'title' => 'Midnight Dreams',
    //         'artist' => 'Luna Artist',
    //         'duration' => '3:45',
    //         'url' => 'https://www.soundjay.com/buttons/sounds/button-09.mp3'
    //     ],
    //     [
    //         'id' => 3,
    //         'title' => 'Ocean Waves',
    //         'artist' => 'Nature Sounds',
    //         'duration' => '4:12',
    //         'url' => 'https://www.soundjay.com/buttons/sounds/button-10.mp3'
    //     ],
    //     [
    //         'id' => 4,
    //         'title' => 'City Lights',
    //         'artist' => 'Urban Beats',
    //         'duration' => '3:28',
    //         'url' => 'https://www.soundjay.com/buttons/sounds/button-3.mp3'
    //     ],
    //     [
    //         'id' => 5,
    //         'title' => 'Peaceful Mind',
    //         'artist' => 'Meditation Music',
    //         'duration' => '5:30',
    //         'url' => 'https://www.soundjay.com/buttons/sounds/button-4.mp3'
    //     ],
    //     [
    //         'id' => 6,
    //         'title' => 'Summer Vibes',
    //         'artist' => 'Chill Collective',
    //         'duration' => '3:55',
    //         'url' => 'https://www.soundjay.com/buttons/sounds/button-5.mp3'
    //     ]
    // ];

    // public function mount()
    // {
    //     // Initialize with first song data but not playing
    //     $this->currentSong = $this->songs[0];
    //     $this->duration = $this->parseTime($this->currentSong['duration']);
    // }

    // public function playSong($index)
    // {
    //     $this->currentIndex = $index;
    //     $this->currentSong = $this->songs[$index];
    //     $this->isPlaying = true;
    //     $this->currentTime = 0;
    //     $this->progressPercent = 0;
    //     $this->duration = $this->parseTime($this->currentSong['duration']);

    //     $this->dispatch('song-changed', [
    //         'song' => $this->currentSong,
    //         'isPlaying' => $this->isPlaying
    //     ]);
    // }

    // public function togglePlay()
    // {
    //     if (!$this->currentSong) {
    //         $this->playSong(0);
    //         return;
    //     }

    //     $this->isPlaying = !$this->isPlaying;

    //     $this->dispatch('play-toggled', [
    //         'isPlaying' => $this->isPlaying
    //     ]);
    // }

    // public function previousSong()
    // {
    //     $newIndex = $this->currentIndex > 0 ? $this->currentIndex - 1 : count($this->songs) - 1;
    //     $this->playSong($newIndex);
    // }

    // public function nextSong()
    // {
    //     $newIndex = $this->currentIndex < count($this->songs) - 1 ? $this->currentIndex + 1 : 0;
    //     $this->playSong($newIndex);
    // }

    // public function seekTo($percent)
    // {
    //     $this->progressPercent = max(0, min(100, $percent));
    //     $this->currentTime = floor(($this->duration * $this->progressPercent) / 100);

    //     $this->dispatch('seek-to', [
    //         'percent' => $this->progressPercent,
    //         'currentTime' => $this->currentTime
    //     ]);
    // }

    // public function setVolume($volume)
    // {
    //     $this->volume = max(0, min(100, $volume));

    //     $this->dispatch('volume-changed', [
    //         'volume' => $this->volume
    //     ]);
    // }

    // public function updateProgress()
    // {
    //     if ($this->isPlaying && $this->duration > 0) {
    //         $this->currentTime++;
    //         $this->progressPercent = ($this->currentTime / $this->duration) * 100;

    //         if ($this->currentTime >= $this->duration) {
    //             $this->nextSong();
    //         }
    //     }
    // }

    // private function parseTime($timeString)
    // {
    //     [$minutes, $seconds] = explode(':', $timeString);
    //     return (int)$minutes * 60 + (int)$seconds;
    // }

    // public function formatTime($seconds)
    // {
    //     $mins = floor($seconds / 60);
    //     $secs = $seconds % 60;
    //     return sprintf('%d:%02d', $mins, $secs);
    // }


    public function render()
    {

        $paginated = $this->getTopTrackData();

        // Get the collection from paginator
        $items = $paginated->getCollection();

        // Map over items to calculate engagement score and rate
        $itemsWithMetrics = $items->map(function ($track) {
            $totalViews = $track['metrics']['total_views']['current_total'];
            $totalPlays = $track['metrics']['total_plays']['current_total'];
            $totalReposts = $track['metrics']['total_reposts']['current_total'];
            $totalLikes = $track['metrics']['total_likes']['current_total'];
            $totalComments = $track['metrics']['total_comments']['current_total'];
            $totalFollowers = $track['metrics']['total_followers']['current_total'];
            $track['repost'] = false;
            $repost = Repost::where('reposter_urn', user()->urn)->where('campaign_id', $track['actionable_details']['id'])->exists();
            if ($repost) {
                $track['repost'] = true;
            }


            $totalEngagements = $totalLikes + $totalComments + $totalReposts + $totalPlays + $totalFollowers;

            // Engagement % (capped at 100)
            $engagementRate = min(100, ($totalEngagements / max(1, $totalViews)) * 100);

            // Engagement Score (0–10 scale)
            $engagementScore = round(($engagementRate / 100) * 10, 1);
            $track['getMusicSrc'] = $this->soundCloudService->getMusicSrc($track['track_details']['urn']);


            // Add score and rate to track array
            $track['engagement_score'] = $engagementScore;
            $track['engagement_rate'] = round($engagementRate, 2); // optional rounding

            return $track;
        });

        // Sort by engagement score descending
        $sorted = $itemsWithMetrics->sortByDesc('engagement_score');

        // Update paginator collection
        $paginated->setCollection($sorted);
        return view(
            'livewire.user.chart',
            [
                'topTracks' => $paginated
            ]
        );
    }
}
