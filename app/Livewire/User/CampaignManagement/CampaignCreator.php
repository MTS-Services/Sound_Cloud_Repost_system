<?php

namespace App\Livewire\User\CampaignManagement;

use App\Jobs\NotificationMailSent;
use App\Models\Campaign;
use App\Models\CreditTransaction;
use App\Models\Feature;
use App\Models\Playlist;
use App\Models\Track;
use App\Services\PlaylistService;
use App\Services\SoundCloud\SoundCloudService;
use App\Services\TrackService;
use App\Services\User\CampaignManagement\MyCampaignService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Component;

class CampaignCreator extends Component
{
    #[Locked]
    protected string $baseUrl = 'https://api.soundcloud.com';

    // Properties for track/playlist selection
    public $tracks = [];
    public $playlists = null;
    public $playlistTracks = [];
    public $activeTab = 'tracks';
    public $searchQuery = '';
    public $allTracks;
    public $allPlaylists;
    public $allPlaylistTracks;
    public $selectedPlaylistId = null;
    public $playListTrackShow = false;

    // Properties for "Load More"
    public $tracksPage = 1;
    public $playlistsPage = 1;
    public $perPage = 4;
    public $hasMoreTracks = false;
    public $hasMorePlaylists = false;

    // Campaign creation properties
    public $track = null;
    public $credit = 50;
    public $totalCredit = 50;
    public $commentable = true;
    public $likeable = true;
    public $proFeatureEnabled = false;
    public $proFeatureValue = 0;
    public $maxFollower = 1000;
    public $followersLimit = 0;
    public $maxRepostLast24h = 0;
    public $maxRepostsPerDay = 0;
    public $targetGenre = 'anyGenre';
    public $maxFollowerEnabled = false;
    public $repostPerDayEnabled = false;

    public $musicId = null;
    public $musicType = null;
    public $title = null;
    public $description = null;
    public $playlistId = null;

    // Modal states - handled by Alpine
    public $showCampaignsModal = false;
    public $showSubmitModal = false;
    public $showLowCreditWarningModal = false;

    protected ?TrackService $trackService = null;
    protected ?PlaylistService $playlistService = null;
    protected ?SoundCloudService $soundCloudService = null;
    protected ?MyCampaignService $myCampaignService = null;

    public function boot(
        TrackService $trackService,
        PlaylistService $playlistService,
        SoundCloudService $soundCloudService,
        MyCampaignService $myCampaignService
    ) {
        $this->trackService = $trackService;
        $this->playlistService = $playlistService;
        $this->soundCloudService = $soundCloudService;
        $this->myCampaignService = $myCampaignService;
    }

    public function mount()
    {
        $this->soundCloudService->refreshUserTokenIfNeeded(user());
        $this->calculateFollowersLimit();
    }

    public function updated($propertyName)
    {
        if (in_array($propertyName, ['credit', 'likeable', 'commentable'])) {
            $this->calculateFollowersLimit();
        }
    }

    public function calculateFollowersLimit()
    {
        $this->followersLimit = ($this->credit - ($this->likeable ? 2 : 0) - ($this->commentable ? 2 : 0)) * 100;
    }

    protected function creditRules()
    {
        return [
            'credit' => [
                'required',
                'integer',
                'min:50',
                function ($attribute, $value, $fail) {
                    if ($value > userCredits()) {
                        $fail('The credit is not available.');
                    }
                },
            ],
        ];
    }

    #[On('open-campaign-modal')]
    public function openCampaignModal()
    {
        if (!is_email_verified()) {
            $this->dispatch('alert', type: 'error', message: 'Please verify your email to create a campaign.');
            return;
        }

        if ($this->myCampaignService->thisMonthCampaignsCount() >= (int) userFeatures()[Feature::KEY_SIMULTANEOUS_CAMPAIGNS]) {
            $this->dispatch('alert', type: 'error', message: 'You have reached the maximum number of campaigns for this month.');
            return;
        }

        $this->reset([
            'tracks',
            'playlists',
            'playlistTracks',
            'searchQuery',
            'selectedPlaylistId',
            'playListTrackShow',
            'track',
            'credit',
            'totalCredit',
            'commentable',
            'likeable',
            'proFeatureEnabled',
            'proFeatureValue',
            'maxFollower',
            'maxRepostLast24h',
            'maxRepostsPerDay',
            'targetGenre',
            'maxFollowerEnabled',
            'repostPerDayEnabled',
            'musicId',
            'musicType',
            'title',
            'description',
            'playlistId',
        ]);

        $this->activeTab = 'tracks';
        $this->credit = 50;
        $this->totalCredit = 50;
        $this->commentable = true;
        $this->likeable = true;
        $this->maxFollower = 1000;
        $this->calculateFollowersLimit();

        $this->dispatch('campaign-modal-opened');
        $this->selectModalTab('tracks');
    }

    public function selectModalTab($tab = 'tracks')
    {
        $this->activeTab = $tab;

        if ($tab === 'tracks') {
            $this->fetchTracks();
        } elseif ($tab === 'playlists') {
            $this->playListTrackShow = false;
            $this->allPlaylistTracks = collect();
            $this->tracks = collect();
            $this->selectedPlaylistId = null;
            $this->fetchPlaylists();
        }

        $this->reset(['searchQuery']);
    }

    public function fetchTracks()
    {
        try {
            $this->soundCloudService->syncUserTracks(user(), []);

            $this->allTracks = Track::where('user_urn', user()->urn)
                ->latest()
                ->get();

            $this->tracksPage = 1;
            $this->tracks = $this->allTracks->take($this->perPage);
            $this->hasMoreTracks = $this->allTracks->count() > $this->perPage;
        } catch (\Exception $e) {
            $this->tracks = collect();
            $this->allTracks = collect();
            $this->hasMoreTracks = false;
            $this->dispatch('alert', type: 'error', message: 'Failed to load tracks: ' . $e->getMessage());
        }
    }

    public function fetchPlaylists()
    {
        try {
            $this->soundCloudService->syncUserPlaylists(user());

            $this->allPlaylists = Playlist::where('user_urn', user()->urn)
                ->latest()
                ->get();

            $this->playlistsPage = 1;
            $this->playlists = $this->allPlaylists->take($this->perPage);
            $this->hasMorePlaylists = $this->allPlaylists->count() > $this->perPage;
        } catch (\Exception $e) {
            $this->playlists = collect();
            $this->allPlaylists = collect();
            $this->hasMorePlaylists = false;
            $this->dispatch('alert', type: 'error', message: 'Failed to load playlists: ' . $e->getMessage());
        }
    }

    public function loadMoreTracks()
    {
        $this->tracksPage++;

        if ($this->playListTrackShow && $this->selectedPlaylistId) {
            $startIndex = ($this->tracksPage - 1) * $this->perPage;
            $newTracks = $this->allPlaylistTracks->slice($startIndex, $this->perPage);
            $this->tracks = $this->tracks->concat($newTracks);
            $this->hasMoreTracks = $newTracks->count() === $this->perPage;
        } else {
            $startIndex = ($this->tracksPage - 1) * $this->perPage;
            $newTracks = $this->allTracks->slice($startIndex, $this->perPage);
            $this->tracks = $this->tracks->concat($newTracks);
            $this->hasMoreTracks = $newTracks->count() === $this->perPage;
        }
    }

    public function loadMorePlaylists()
    {
        $this->playlistsPage++;
        $startIndex = ($this->playlistsPage - 1) * $this->perPage;
        $newPlaylists = $this->allPlaylists->slice($startIndex, $this->perPage);
        $this->playlists = $this->playlists->concat($newPlaylists);
        $this->hasMorePlaylists = $newPlaylists->count() === $this->perPage;
    }

    public function showPlaylistTracks($playlistId)
    {
        $this->selectedPlaylistId = $playlistId;
        $playlist = Playlist::with('tracks')->find($playlistId);

        if ($playlist) {
            $this->allPlaylistTracks = $playlist->tracks;
            $this->tracks = $this->allPlaylistTracks->take($this->perPage);
            $this->hasMoreTracks = $this->allPlaylistTracks->count() > $this->perPage;
        } else {
            $this->resetTrackCollections();
        }

        $this->activeTab = 'tracks';
        $this->playListTrackShow = true;
        $this->tracksPage = 1;
        $this->searchQuery = '';
    }

    private function resetTrackCollections(): void
    {
        $this->tracks = collect();
        $this->allTracks = collect();
        $this->hasMoreTracks = false;
    }

    public function searchSoundcloud()
    {
        if (empty($this->searchQuery)) {
            if ($this->playListTrackShow && $this->activeTab === 'tracks') {
                $this->allPlaylistTracks = Playlist::findOrFail($this->selectedPlaylistId)->tracks()->get();
                $this->tracks = $this->allPlaylistTracks->take($this->perPage);
            } else {
                if ($this->activeTab == 'tracks') {
                    $this->allTracks = Track::where('user_urn', user()->urn)->get();
                    $this->tracks = $this->allTracks->take($this->perPage);
                }
                if ($this->activeTab == 'playlists') {
                    $this->allPlaylists = Playlist::where('user_urn', user()->urn)->get();
                    $this->playlists = $this->allPlaylists->take($this->perPage);
                }
            }
            return;
        }

        if (preg_match('/^https?:\/\/(www\.)?soundcloud\.com\/[a-zA-Z0-9\-_]+(\/[a-zA-Z0-9\-_]+)*(\/)?(\?.*)?$/i', $this->searchQuery)) {
            if (proUser()) {
                $this->resolveSoundcloudUrl();
            } else {
                $this->dispatch('alert', type: 'error', message: 'Please upgrade to a Pro User to use this feature.');
            }
        } else {
            $this->performLocalSearch();
        }
    }

    protected function performLocalSearch()
    {
        if ($this->playListTrackShow && $this->activeTab === 'tracks') {
            $this->allPlaylistTracks = Playlist::findOrFail($this->selectedPlaylistId)->tracks()
                ->where(function ($query) {
                    $query->where('permalink_url', 'like', '%' . $this->searchQuery . '%')
                        ->orWhere('title', 'like', '%' . $this->searchQuery . '%');
                })
                ->get();
            $this->tracks = $this->allPlaylistTracks->take($this->perPage);
        } else {
            if ($this->activeTab === 'tracks') {
                $this->allTracks = Track::where('user_urn', user()->urn)
                    ->where(function ($query) {
                        $query->where('permalink_url', 'like', '%' . $this->searchQuery . '%')
                            ->orWhere('title', 'like', '%' . $this->searchQuery . '%');
                    })
                    ->get();
                $this->tracks = $this->allTracks->take($this->perPage);
            } elseif ($this->activeTab === 'playlists') {
                $this->allPlaylists = Playlist::where('user_urn', user()->urn)
                    ->where(function ($query) {
                        $query->where('permalink_url', 'like', '%' . $this->searchQuery . '%')
                            ->orWhere('title', 'like', '%' . $this->searchQuery . '%');
                    })
                    ->get();
                $this->playlists = $this->allPlaylists->take($this->perPage);
            }
        }
    }

    protected function resolveSoundcloudUrl()
    {
        $baseUrl = strtok($this->searchQuery, '?');

        if ($this->playListTrackShow && $this->activeTab === 'tracks') {
            $tracksFromDb = Playlist::findOrFail($this->selectedPlaylistId)->tracks()
                ->whereRaw("SUBSTRING_INDEX(permalink_url, '?', 1) = ?", [$baseUrl])
                ->get();
            if ($tracksFromDb->isNotEmpty()) {
                $this->allPlaylistTracks = $tracksFromDb;
                $this->tracks = $this->allPlaylistTracks->take($this->perPage);
                $this->hasMoreTracks = $this->tracks->count() === $this->perPage;
                return;
            }
        } else {
            if ($this->activeTab == 'tracks') {
                $tracksFromDb = Track::whereRaw("SUBSTRING_INDEX(permalink_url, '?', 1) = ?", [$baseUrl])->get();
                if ($tracksFromDb->isNotEmpty()) {
                    $this->allTracks = $tracksFromDb;
                    $this->tracks = $this->allTracks->take($this->perPage);
                    $this->hasMoreTracks = $this->tracks->count() === $this->perPage;
                    return;
                }
            }

            if ($this->activeTab == 'playlists') {
                $playlistsFromDb = Playlist::whereRaw("SUBSTRING_INDEX(permalink_url, '?', 1) = ?", [$baseUrl])->get();
                if ($playlistsFromDb->isNotEmpty()) {
                    $this->allPlaylists = $playlistsFromDb;
                    $this->playlists = $this->allPlaylists->take($this->perPage);
                    $this->hasMorePlaylists = $this->playlists->count() === $this->perPage;
                    return;
                }
            }
        }

        $resolvedData = $this->soundCloudService->makeResolveApiRequest($this->searchQuery, 'Failed to resolve SoundCloud URL');
        if ($resolvedData) {
            $urn = $resolvedData['urn'];
            if ($this->activeTab === 'playlists' && isset($resolvedData['tracks']) && count($resolvedData['tracks']) > 0) {
                $this->soundCloudService->unknownPlaylistAdd($resolvedData);
            } elseif ($this->activeTab === 'tracks' && !isset($resolvedData['tracks'])) {
                $this->soundCloudService->unknownTrackAdd($resolvedData);
            }
            $this->processSearchData($urn);
        } else {
            $this->dispatch('alert', type: 'error', message: 'Could not resolve the SoundCloud link. Please check the URL.');
        }
    }

    protected function processSearchData($urn)
    {
        if ($this->playListTrackShow && $this->activeTab === 'tracks') {
            $tracksFromDb = Playlist::findOrFail($this->selectedPlaylistId)->tracks()->where('soundcloud_urn', $urn)->get();
            if ($tracksFromDb->isNotEmpty()) {
                $this->allPlaylistTracks = $tracksFromDb;
                $this->tracks = $this->allPlaylistTracks->take($this->perPage);
                $this->hasMoreTracks = $this->tracks->count() === $this->perPage;
            }
        } else {
            if ($this->activeTab == 'tracks') {
                $tracksFromDb = Track::where('urn', $urn)->get();
                if ($tracksFromDb->isNotEmpty()) {
                    $this->allTracks = $tracksFromDb;
                    $this->tracks = $this->allTracks->take($this->perPage);
                    $this->hasMoreTracks = $this->tracks->count() === $this->perPage;
                }
            }

            if ($this->activeTab == 'playlists') {
                $playlistsFromDb = Playlist::where('soundcloud_urn', $urn)->get();
                if ($playlistsFromDb->isNotEmpty()) {
                    $this->allPlaylists = $playlistsFromDb;
                    $this->playlists = $this->allPlaylists->take($this->perPage);
                    $this->hasMorePlaylists = $this->playlists->count() === $this->perPage;
                }
            }
        }
    }

    public function selectTrackOrPlaylist($type, $id)
    {
        if (userCredits() < 50) {
            $this->dispatch('show-low-credit-modal');
            return;
        }

        try {
            if ($type === 'track') {
                $this->track = Track::findOrFail($id);
                if (!$this->track->urn || !$this->track->title) {
                    throw new \Exception('Track data is incomplete');
                }
                $this->musicId = $this->track->id;
                $this->musicType = Track::class;
                $this->title = $this->track->title . ' Campaign';
            } elseif ($type === 'playlist') {
                $playlist = Playlist::findOrFail($id);
                if (!$playlist->title) {
                    throw new \Exception('Playlist data is incomplete');
                }
                $this->playlistId = $id;
                $this->title = $playlist->title . ' Campaign';
                $this->musicType = Playlist::class;
                $this->fetchPlaylistTracks();
                $this->musicId = null;
            }

            $musicId = $this->musicType === Track::class ? $this->musicId : $this->playlistId;
            $exists = Campaign::where('music_id', $musicId)
                ->where('music_type', $this->musicType)
                ->open()->exists();

            if ($exists) {
                $this->dispatch('alert', type: 'error', message: 'You already have an active campaign for this track. Please end or close it before creating a new one.');
                return;
            }

            $this->dispatch('show-submit-modal');
        } catch (\Exception $e) {
            $this->dispatch('alert', type: 'error', message: 'Failed to load content: ' . $e->getMessage());
            Log::error('Select track/playlist error: ' . $e->getMessage(), [
                'type' => $type,
                'id' => $id,
                'user_urn' => user()->urn ?? 'unknown'
            ]);
        }
    }

    protected function fetchPlaylistTracks()
    {
        if (!$this->playlistId) {
            $this->playlistTracks = [];
            return;
        }

        try {
            $playlist = Playlist::findOrFail($this->playlistId);
            if (!$playlist->soundcloud_urn) {
                $this->playlistTracks = [];
                return;
            }

            $response = Http::timeout(30)
                ->withHeaders(['Authorization' => 'OAuth ' . user()->token])
                ->get('https://api.soundcloud.com/playlists/' . $playlist->soundcloud_urn . '/tracks');

            if ($response->successful()) {
                $tracks = $response->json();
                if (is_array($tracks)) {
                    $this->playlistTracks = collect($tracks)->filter(function ($track) {
                        return is_array($track) && isset($track['urn'], $track['title'], $track['user']['username']);
                    })->values()->toArray();
                }
            }
        } catch (\Exception $e) {
            $this->playlistTracks = [];
            Log::error('Playlist tracks fetch error: ' . $e->getMessage());
        }
    }

    public function profeature($isChecked)
    {
        if (!proUser()) {
            $this->dispatch('alert', type: 'error', message: 'You need to be a pro user to use this feature');
            return;
        }

        if (($this->credit * 2) > userCredits()) {
            $this->proFeatureEnabled = $isChecked ? true : false;
            $this->proFeatureValue = $isChecked ? 1 : 0;
        } else {
            $this->proFeatureEnabled = $isChecked ? false : true;
            $this->proFeatureValue = $isChecked ? 0 : 1;
        }
    }

    public function createCampaign()
    {
        $this->validate($this->creditRules());

        try {
            $musicId = $this->musicType === Track::class ? $this->musicId : $this->playlistId;

            DB::transaction(function () use ($musicId) {
                $proFeatureEnabled = $this->proFeatureEnabled && proUser() ? 1 : 0;

                $campaign = Campaign::create([
                    'music_id' => $musicId,
                    'music_type' => $this->musicType,
                    'title' => $this->title,
                    'description' => $this->description,
                    'budget_credits' => $this->credit,
                    'user_urn' => user()->urn,
                    'status' => Campaign::STATUS_OPEN,
                    'max_followers' => $this->maxFollowerEnabled ? $this->maxFollower : null,
                    'creater_id' => user()->id,
                    'creater_type' => get_class(user()),
                    'commentable' => $this->commentable ? 1 : 0,
                    'likeable' => $this->likeable ? 1 : 0,
                    'pro_feature' => $proFeatureEnabled,
                    'momentum_price' => $proFeatureEnabled == 1 ? $this->credit / 2 : 0,
                    'max_repost_last_24_h' => $this->maxRepostLast24h,
                    'max_repost_per_day' => $this->repostPerDayEnabled ? $this->maxRepostsPerDay : 0,
                    'target_genre' => $this->targetGenre,
                ]);

                CreditTransaction::create([
                    'receiver_urn' => user()->urn,
                    'calculation_type' => CreditTransaction::CALCULATION_TYPE_CREDIT,
                    'source_id' => $campaign->id,
                    'source_type' => Campaign::class,
                    'transaction_type' => CreditTransaction::TYPE_SPEND,
                    'status' => CreditTransaction::STATUS_SUCCEEDED,
                    'credits' => ($campaign->budget_credits + $campaign->momentum_price),
                    'description' => 'Spent on campaign creation',
                    'metadata' => [
                        'campaign_id' => $campaign->id,
                        'music_id' => $this->musicId,
                        'music_type' => $this->musicType,
                        'start_date' => now(),
                    ],
                    'created_id' => user()->id,
                    'creater_type' => get_class(user())
                ]);
            });

            $this->dispatch('alert', type: 'success', message: 'Campaign created successfully!');
            $this->dispatch('campaign-created');
            $this->dispatch('close-all-modals');

            $this->reset();
        } catch (\Exception $e) {
            $this->dispatch('alert', type: 'error', message: 'Failed to create campaign: ' . $e->getMessage());
            Log::error('Campaign creation error: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.user.campaign-management.campaign-creator');
    }
}
