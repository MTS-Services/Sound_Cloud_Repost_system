<?php

namespace App\Livewire\User;

use App\Jobs\TrackViewCount;
use App\Models\CreditTransaction;
use App\Models\Playlist;
use App\Models\Repost;
use App\Models\Track;
use App\Models\User;
use App\Models\UserSocialInformation;
use App\Models\UserAnalytics;
use App\Services\Admin\CreditManagement\CreditTransactionService;
use App\Services\Admin\UserManagement\UserService;
use App\Services\PlaylistService;
use App\Services\SoundCloud\FollowerAnalyzer;
use App\Services\SoundCloud\SoundCloudService;
use App\Services\TrackService;
use App\Services\User\AnalyticsService;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class MyAccount extends Component
{
    use WithPagination;

    protected string $baseUrl = 'https://api.soundcloud.com';

    // UI state
    #[Url(as: 'tab', except: 'insights')]
    public string $activeTab = 'insights';

    public bool $showEditProfileModal = false;

    // Playlist detail state
    #[Url(as: 'selectedPlaylistId')]
    public ?int $selectedPlaylistId = null;

    public bool $showPlaylistTracks = false;

    // Independent pagination page numbers in query string
    #[Url(as: 'tracksPage')]
    public ?int $tracksPage = 1;

    #[Url(as: 'playlistsPage')]
    public ?int $playlistsPage = 1;

    #[Url(as: 'playlistTracksPage')]
    public ?int $playlistTracksPage = 1;

    public $user_urn = null;

    // NEW: Track whether play count should be recorded
    public bool $shouldRecordPlayCount = false;

    // Dependencies (non-serializable) — keep private
    private UserService $userService;
    private CreditTransactionService $creditTransactionService;
    private TrackService $trackService;
    private PlaylistService $playlistService;
    private SoundCloudService $soundCloudService;
    private FollowerAnalyzer $followerAnalyzer;
    private AnalyticsService $analyticsService;

    public $userFollowerAnalysis = [];
    public $followerGrowth = 0;

    // Livewire v3: boot runs on every request (initial + subsequent)
    public function boot(
        UserService $userService,
        CreditTransactionService $creditTransactionService,
        TrackService $trackService,
        SoundCloudService $soundCloudService,
        PlaylistService $playlistService,
        FollowerAnalyzer $followerAnalyzer,
        AnalyticsService $analyticsService
    ): void {
        $this->userService = $userService;
        $this->creditTransactionService = $creditTransactionService;
        $this->trackService = $trackService;
        $this->soundCloudService = $soundCloudService;
        $this->playlistService = $playlistService;
        $this->followerAnalyzer = $followerAnalyzer;
        $this->analyticsService = $analyticsService;
    }

    public function mount($user_name = null): void
    {
        Log::info('MyAccount mount. step 1');
        $user = $user_name ? User::where('name', $user_name)->first() : user();
        Log::info('MyAccount mount. step 2');

        // NEW: Set play count recording flag based on $user_name parameter
        $this->shouldRecordPlayCount = $user_name !== null;

        $this->soundCloudService->refreshUserTokenIfNeeded(user());
        Log::info('MyAccount mount. step 3');
        $followers = $this->soundCloudService->getAuthUserFollowers();
        Log::info('MyAccount mount. step 4');
        $this->userFollowerAnalysis = $this->followerAnalyzer->getQuickStats($followers);
        Log::info('MyAccount mount. step 5');

        Log::info('MyAccount mount. step 6');
        $currentWeekStats = $this->followerAnalyzer->getQuickStats($followers, 'this_month');
        Log::info('MyAccount mount. step 7');
        $lastWeekStats = $this->followerAnalyzer->getQuickStats($followers, 'last_month');
        Log::info('MyAccount mount. step 8');
        $analyze = $this->followerAnalyzer->syncUserRealFollowers($followers, $user);
        Log::info('MyAccount mount. step 9');

        $currentWeekFollowers = $currentWeekStats['totalFollowers'];
        Log::info('MyAccount mount. step 10');
        $lastWeekFollowers = $lastWeekStats['totalFollowers'];
        Log::info('MyAccount mount. step 11');
        if ($lastWeekFollowers > 0) {
            Log::info('MyAccount mount. step 12');
            $this->followerGrowth = ((($currentWeekFollowers - $lastWeekFollowers) / $lastWeekFollowers) * 100) > 0 ? ((($currentWeekFollowers - $lastWeekFollowers) / $lastWeekFollowers) * 100) : 0;
        } else {
            Log::info('MyAccount mount. step 13');
            $this->followerGrowth = 0; // Avoid division by zero
        }
        Log::info('MyAccount mount. step 14');
        $this->activeTab = request()->query('tab', $this->activeTab);
        Log::info('MyAccount mount. step 15');

        $userUrn = $user->urn ?? user()->urn;
        Log::info('MyAccount mount. step 16');
        $this->user_urn = $userUrn;
        Log::info('MyAccount mount. step 17');
        Log::info('MyAccount mount', ['user_urn' => $this->user_urn, 'should_record_play_count' => $this->shouldRecordPlayCount]);
        Log::info('MyAccount mount. step 18');
        if ($this->selectedPlaylistId) {
            Log::info('MyAccount mount. step 19');
            $this->activeTab = 'playlists';
            $this->showPlaylistTracks = true;
        }
        Log::info('MyAccount mount. step 20');
        $this->socialLinks();
    }

    public function updated()
    {
        $this->soundCloudService->refreshUserTokenIfNeeded(user());
    }

    public function updatedActiveTab()
    {
        return $this->redirect(route('user.my-account', $this->user_urn) . '?tab=' . $this->activeTab, navigate: true);
    }

    public function setActiveTab(string $tab): void
    {
        $this->activeTab = $tab;
    }

    public function selectPlaylist(int $playlistId): void
    {
        $exists = Playlist::where('id', $playlistId)
            ->where('user_urn', $this->user_urn)
            ->exists();

        if ($exists) {
            $this->selectedPlaylistId = $playlistId;
            $this->showPlaylistTracks = true;
            $this->resetPage('playlistTracksPage');
        }
    }

    public function backToPlaylists(): void
    {
        $this->resetPlaylistView();
    }

    private function resetPlaylistView(): void
    {
        $this->selectedPlaylistId = null;
        $this->showPlaylistTracks = false;
        $this->resetPage('playlistTracksPage');
    }

    public function profileUpdated($payload = null): void
    {
        $this->showEditProfileModal = true;
    }

    public function closeEditProfileModal(): void
    {
        $this->showEditProfileModal = false;
    }

    public function syncTracks()
    {
        $this->soundCloudService->syncSelfTracks([]);
    }

    public function syncPlaylists()
    {
        $this->soundCloudService->syncSelfPlaylists();
    }

    // NEW: Play count analytics methods
    /**
     * Handle track play event and record analytics using existing AnalyticsService
     * Only records if $shouldRecordPlayCount is true (when $user_name was provided)
     * Each user is only counted once per track (ever, not just recently)
     */
    public function handleTrackPlay($trackId, $playlistId = null)
    {
        // Early return if play count recording is disabled
        if (!$this->shouldRecordPlayCount) {
            Log::info("Play count recording disabled - user_name was null in mount");
            return;
        }

        try {
            // Get the track
            $track = Track::findOrFail($trackId);

            // Check if this is the track owner's own account - don't record if same user
            if ($track->user_urn === user()->urn) {
                Log::info("Track play skipped - user playing their own track: {$trackId}");
                return;
            }

            // Check if this user has EVER played this track before (not time-limited)
            $existingPlay = UserAnalytics::where('owner_user_urn', $track->user_urn)
                ->where('act_user_urn', user()->urn)
                ->where('source_id', $trackId)
                ->where('source_type', Track::class)
                ->where('type', UserAnalytics::TYPE_PLAY)
                ->exists();

            if ($existingPlay) {
                Log::info("Track play skipped - user has already been counted for this track: " . user()->urn . " for track: {$trackId}");
                return;
            }

            // Get actionable (playlist) if provided
            $actionable = null;
            if ($playlistId) {
                $actionable = Playlist::findOrFail($playlistId);
            }

            // Use existing recordAnalytics method with TYPE_PLAY
            $response = $this->analyticsService->recordAnalytics(
                source: $track,
                actionable: $actionable,
                type: UserAnalytics::TYPE_PLAY,
                genre: $track->genre ?? 'anyGenre',
                actUserUrn: user()->urn
            );

            // Only increment if analytics was recorded successfully
            if ($response != false && $response != null) {
                $track->increment('playback_count');

                Log::info("Play count recorded for track: {$trackId} by visitor: " . user()->urn . " (first time for this user)");

                // Dispatch browser event to update UI with new play count
                $this->dispatch('play-count-recorded', [
                    'trackId' => $trackId,
                    'newPlayCount' => $track->fresh()->playback_count
                ]);
            }
        } catch (\Exception $e) {
            Log::error("Failed to handle track play: " . $e->getMessage(), [
                'track_id' => $trackId,
                'playlist_id' => $playlistId,
                'visitor_urn' => user()->urn,
                'should_record' => $this->shouldRecordPlayCount,
                'exception' => $e
            ]);
        }
    }

    /**
     * Handle playlist track play from playlist detail view
     */
    public function playPlaylistTrack($trackId)
    {
        if ($this->selectedPlaylistId) {
            $this->handleTrackPlay($trackId, $this->selectedPlaylistId);
        } else {
            $this->handleTrackPlay($trackId);
        }
    }

    /**
     * Handle regular track play from tracks tab
     */
    public function playTrack($trackId)
    {
        $this->handleTrackPlay($trackId);
    }

    // Add these listeners to your existing listeners array
    protected $listeners = [
        'track-played' => 'handleTrackPlay',
        'playlist-track-played' => 'playPlaylistTrack',
    ];

    public $instagram = null;
    public $twitter = null;
    public $tiktok = null;
    public $facebook = null;
    public $youtube = null;
    public $spotify = null;
    public $socialLink = [];

    public function socialLinks()
    {
        $social_link = UserSocialInformation::where('user_urn', $this->user_urn)->first();
        $this->socialLink = $social_link;
        $this->instagram = $social_link->instagram ?? '';
        $this->twitter = $social_link->twitter ?? '';
        $this->tiktok = $social_link->tiktok ?? '';
        $this->facebook = $social_link->facebook ?? '';
        $this->youtube = $social_link->youtube ?? '';
        $this->spotify = $social_link->spotify ?? '';
    }

    public function render()
    {
        Log::info('MyAccount render', ['user_urn' => $this->user_urn]);
        $user = $this->userService->getUser(encrypt($this->user_urn), 'urn');

        // Tracks pagination
        $tracks = Track::where('user_urn', $this->user_urn)
            ->latest('created_at')
            ->paginate(6, ['*'], 'tracksPage', $this->tracksPage);

        // Playlists pagination
        $playlists = Playlist::where('user_urn', $this->user_urn)
            ->latest('created_at')
            ->paginate(8, ['*'], 'playlistsPage', $this->playlistsPage);

        // Playlist detail + tracks pagination
        $selectedPlaylist = null;
        $playlistTracks = null;

        if ($this->showPlaylistTracks && $this->selectedPlaylistId) {
            $selectedPlaylist = Playlist::where('id', $this->selectedPlaylistId)
                ->where('user_urn', $this->user_urn)
                ->first();

            if ($selectedPlaylist) {
                $playlistTracks = $selectedPlaylist->tracks()
                    ->latest('created_at')
                    ->paginate(6, ['*'], 'playlistTracksPage', $this->playlistTracksPage);
            }
        }

        // Recent reposts (not paginated here)
        $reposts = Repost::with(['campaign.music', 'request.music'])
            ->where('reposter_urn', $this->user_urn)->where(function ($query) {
                $query->whereNotNull('campaign_id')->orWhereNotNull('repost_request_id');
            })
            ->orderByDesc('reposted_at')
            ->take(10)
            ->get()
            ->map(function ($repost) {
                $source = $repost->campaign?->music ?? $repost->request?->music;
                $repost->source = $source;
                $repost->source_id = $repost->campaign?->id ?? $repost->request?->id;
                $repost->source_type = $repost->campaign ? '📢 From Campaign' : ($repost->request ? '🤝 From Request' : '');
                return $repost;
            });

        // Transactions (top 10)
        $transactions = $this->creditTransactionService->getUserTransactions()
            ->where('status', CreditTransaction::STATUS_SUCCEEDED)
            ->sortByDesc('created_at')
            ->take(10);

        // View Count Tracking
        Bus::chain([
            new TrackViewCount($tracks, user()->urn, 'track'),
            new TrackViewCount($playlists, user()->urn, 'playlist'),
        ])->dispatch();

        // Only record view analytics if viewing someone else's account AND play count recording is enabled
        if ($this->user_urn !== user()->urn && $this->shouldRecordPlayCount) {
            // Record view analytics for tracks using existing service
            foreach ($tracks as $track) {
                $this->analyticsService->recordAnalytics(
                    source: $track,
                    actionable: null,
                    type: UserAnalytics::TYPE_VIEW,
                    genre: $track->genre ?? 'anyGenre'
                );
            }

            // Record view analytics for playlists using existing service  
            foreach ($playlists as $playlist) {
                $this->analyticsService->recordAnalytics(
                    source: $playlist,
                    actionable: null,
                    type: UserAnalytics::TYPE_VIEW,
                    genre: 'anyGenre'
                );
            }
        }

        return view('livewire.user.my-account', [
            'user' => $user,
            'tracks' => $tracks,
            'playlists' => $playlists,
            'selectedPlaylist' => $selectedPlaylist,
            'playlistTracks' => $playlistTracks,
            'reposts' => $reposts,
            'transactions' => $transactions,
            'showPlaylistTracks' => $this->showPlaylistTracks,
            'isOwnAccount' => $this->user_urn === user()->urn,
            'shouldRecordPlayCount' => $this->shouldRecordPlayCount, // NEW: Pass to template
        ]);
    }
}
