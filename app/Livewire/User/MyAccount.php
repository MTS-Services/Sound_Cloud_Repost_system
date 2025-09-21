<?php

namespace App\Livewire\User;

use App\Jobs\TrackViewCount;
use App\Models\CreditTransaction;
use App\Models\Playlist;
use App\Models\Repost;
use App\Models\Track;
use App\Models\UserSocialInformation;
use App\Services\Admin\CreditManagement\CreditTransactionService;
use App\Services\Admin\UserManagement\UserService;
use App\Services\PlaylistService;
use App\Services\SoundCloud\FollowerAnalyzer;
use App\Services\SoundCloud\SoundCloudService;
use App\Services\TrackService;
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
    // #[Url(as: 'tab', except: 'insights')]
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
    // Dependencies (non-serializable) — keep private
    private UserService $userService;
    private CreditTransactionService $creditTransactionService;
    private TrackService $trackService;
    private PlaylistService $playlistService;
    private SoundCloudService $soundCloudService;
    private FollowerAnalyzer $followerAnalyzer;

    public $userFollowerAnalysis = [];

    public $followerGrowth = 0;

    // Livewire v3: boot runs on every request (initial + subsequent)
    public function boot(UserService $userService, CreditTransactionService $creditTransactionService, TrackService $trackService, SoundCloudService $soundCloudService, PlaylistService $playlistService, FollowerAnalyzer $followerAnalyzer): void
    {
        $this->userService = $userService;
        $this->creditTransactionService = $creditTransactionService;
        $this->trackService = $trackService;
        $this->soundCloudService = $soundCloudService;
        $this->playlistService = $playlistService;
        $this->followerAnalyzer = $followerAnalyzer;
    }

    public function mount($user_urn = null): void
    {
        $this->soundCloudService->refreshUserTokenIfNeeded(user());
        $followers = $this->soundCloudService->getAuthUserFollowers();
        $this->userFollowerAnalysis = $this->followerAnalyzer->getQuickStats($followers);

        $currentWeekStats = $this->followerAnalyzer->getQuickStats($followers, 'this_month');
        $lastWeekStats = $this->followerAnalyzer->getQuickStats($followers, 'last_month');

        $currentWeekFollowers = $currentWeekStats['totalFollowers'];
        $lastWeekFollowers = $lastWeekStats['totalFollowers'];

        if ($lastWeekFollowers > 0) {
            $this->followerGrowth = ((($currentWeekFollowers - $lastWeekFollowers) / $lastWeekFollowers) * 100) > 0 ? ((($currentWeekFollowers - $lastWeekFollowers) / $lastWeekFollowers) * 100) : 0;
        } else {
            $this->followerGrowth = 0; // Avoid division by zero
        }

        $this->activeTab = request()->query('tab', $this->activeTab);

        $this->user_urn = $user_urn ?? user()->urn;

        Log::info('MyAccount mount', ['user_urn' => $this->user_urn]);
        // If a playlist is in the URL, ensure we land on the right tab/view
        if ($this->selectedPlaylistId) {
            $this->activeTab = 'playlists';
            $this->showPlaylistTracks = true;
        }
        $this->socialLinks();
    }

    public function updated()
    {
        $this->soundCloudService->refreshUserTokenIfNeeded(user());
    }

    public function updatingActiveTab()
    {
        return $this->redirect(route('user.my-account') . '?tab=' . $this->activeTab, navigate: true);
    }

    // public function setActiveTab(string $tab): void
    // {
    //     $this->activeTab = $tab;

    //     if ($tab !== 'playlists') {
    //         $this->resetPlaylistView();
    //     }

    //     // Reset the relevant pager when switching tabs
    //     if ($tab === 'tracks') {
    //         $this->syncTracks();
    //         $this->resetPage('tracksPage');
    //     } elseif ($tab === 'playlists') {
    //         $this->syncPlaylists();
    //         $this->resetPage('playlistsPage');
    //     }
    // }

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
        // SyncedTracks::dispatch(user()->urn);
        // return back()->with('success', 'Track sync started in background. Please check later.');
    }
    public function syncPlaylists()
    {
        $this->soundCloudService->syncSelfPlaylists();
        // SyncedPlaylists::dispatch(user()->urn);
        // return back()->with('success', 'Playlist sync started in background.');
    }
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
                // Make sure you have a proper relationship method `tracks()` on Playlist
                $playlistTracks = $selectedPlaylist->tracks()
                    ->latest('created_at')
                    ->paginate(6, ['*'], 'playlistTracksPage', $this->playlistTracksPage);
            }
        }

        // Recent reposts (not paginated here)
        $reposts = Repost::with(['campaign.music', 'request.track'])
            ->where('reposter_urn', $this->user_urn)->where(function ($query) {
                $query->whereNotNull('campaign_id')->orWhereNotNull('repost_request_id');
            })
            ->orderByDesc('reposted_at')
            ->take(10)
            ->get()
            ->map(function ($repost) {
                $source = $repost->campaign?->music ?? $repost->request?->track;
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

        $tracksData = $tracks;

        // View Count
        Bus::dispatch(new TrackViewCount($tracksData, user()->urn, 'track'));

        return view('livewire.user.my-account', [
            'user' => $user,
            'tracks' => $tracks,
            'playlists' => $playlists,
            'selectedPlaylist' => $selectedPlaylist,
            'playlistTracks' => $playlistTracks,
            'reposts' => $reposts,
            'transactions' => $transactions,
            // Also pass down flags used in Blade
            'showPlaylistTracks' => $this->showPlaylistTracks,
        ]);
    }
}
