<?php

namespace App\Livewire\User;

use App\Jobs\TrackViewCount;
use App\Models\CreditTransaction;
use App\Models\Playlist;
use App\Models\Repost;
use App\Models\Track;
use App\Models\User;
use App\Models\UserSocialInformation;
use App\Services\Admin\CreditManagement\CreditTransactionService;
use App\Services\Admin\UserManagement\UserService;
use App\Services\PlaylistService;
use App\Services\SoundCloud\FollowerAnalyzer;
use App\Services\SoundCloud\SoundCloudService;
use App\Services\TrackService;
use App\Services\User\AnalyticsService;
use Carbon\Carbon;
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


    public $follower_growth;
    public $repost_effieciency;
    public $activities_score;
    public $chart_data;

    // Livewire v3: boot runs on every request (initial + subsequent)
    public function boot(UserService $userService, CreditTransactionService $creditTransactionService, TrackService $trackService, SoundCloudService $soundCloudService, PlaylistService $playlistService, FollowerAnalyzer $followerAnalyzer, AnalyticsService $analyticsService): void
    {
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
        $user = $user_name ? User::where('name', $user_name)->first() : user();
        $this->soundCloudService->refreshUserTokenIfNeeded(user());
        $this->getAnalyticsData($user);

        $this->activeTab = request()->query('tab', $this->activeTab);

        $userUrn = $user->urn ?? user()->urn;
        $this->user_urn = $userUrn;
        if ($this->selectedPlaylistId) {
            $this->activeTab = 'playlists';
            $this->showPlaylistTracks = true;
        }
        $this->socialLinks();
    }


    public function getAnalyticsData($user)
    {
        $analytics_data = $this->analyticsService->getAnalyticsData(filter: 'last_month', ownerUserUrn: $user->urn);

        $follower_growth = $analytics_data['overall_metrics']['total_followers']['change_avg_percent'];
        $this->follower_growth = $follower_growth >= 0 ? $follower_growth : 0;
        $repost_effieciency = $analytics_data['overall_metrics']['total_reposts']['change_avg_percent'];
        $this->repost_effieciency = $repost_effieciency >= 0 ? $repost_effieciency : 0;

        $activities_analytics = $this->analyticsService->getAnalyticsData(filter: 'last_month', actUserUrn: $user->urn);

        $following_analytics = $activities_analytics['overall_metrics']['total_followers']['change_avg_percent'];
        $following_analytics = $following_analytics >= 0 ? $following_analytics : 0;
        $repost_analytics = $activities_analytics['overall_metrics']['total_reposts']['change_avg_percent'];
        $repost_analytics = $repost_analytics >= 0 ? $repost_analytics : 0;
        $like_activity = $activities_analytics['overall_metrics']['total_likes']['change_avg_percent'];
        $like_activity = $like_activity >= 0 ? $like_activity : 0;
        $comment_activity = $activities_analytics['overall_metrics']['total_comments']['change_avg_percent'];
        $comment_activity = $comment_activity >= 0 ? $comment_activity : 0;
        $play_activity = $activities_analytics['overall_metrics']['total_plays']['change_avg_percent'];
        $play_activity = $play_activity >= 0 ? $play_activity : 0;
        $view_activity = $activities_analytics['overall_metrics']['total_views']['change_avg_percent'];
        $view_activity = $view_activity >= 0 ? $view_activity : 0;

        $activities_score = ($following_analytics + $repost_analytics + $like_activity + $comment_activity + $play_activity + $view_activity) / 6;
        $this->activities_score = $activities_score >= 0 ? $activities_score : 0;

        $this->chart_data = $this->analyticsService->getChartData(filter: 'current_year', actUserUrn: $user->urn);
        // dd($this->chart_data);
    }

    public function updated()
    {
    }

    public function updatedActiveTab()
    {
        return $this->redirect(route('user.my-account.user', $this->user_urn) . '?tab=' . $this->activeTab, navigate: true);
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
        $this->soundCloudService->syncUserTracks(user(), []);
        // SyncedTracks::dispatch(user()->urn);
        // return back()->with('success', 'Track sync started in background. Please check later.');
    }
    public function syncPlaylists()
    {
        $this->soundCloudService->syncUserPlaylists(user());
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

        // View Count
        Bus::chain([
            new TrackViewCount($tracks, user()->urn, 'track'),
            new TrackViewCount($playlists, user()->urn, 'playlist'),
        ])->dispatch();


        return view('livewire.user.my-account', [
            'user' => $user,
            'tracks' => $tracks,
            'playlists' => $playlists,
            'selectedPlaylist' => $selectedPlaylist,
            'playlistTracks' => $playlistTracks,
            'reposts' => $reposts,
            'transactions' => $transactions,
            'showPlaylistTracks' => $this->showPlaylistTracks,
        ]);
    }
}
