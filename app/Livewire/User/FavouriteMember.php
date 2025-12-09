<?php

namespace App\Livewire\User;

use App\Models\Playlist;
use App\Models\StarredUser;
use App\Models\User;
use App\Models\Track;
use App\Models\UserGenre;
use App\Models\UserSetting;
use App\Models\RepostRequest;
use App\Models\CreditTransaction;
use App\Models\CustomNotification;
use App\Events\UserNotificationSent;
use App\Jobs\NotificationMailSent;
use App\Services\PlaylistService;
use App\Services\SoundCloud\SoundCloudService;
use App\Services\TrackService;
use Livewire\Attributes\Url;
use Livewire\Component;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;
use Exception;

class FavouriteMember extends Component
{
    // Properties for favorite members
    public $favouriteUsers = [];

    #[Url]
    public $starred = '';

    // Modal states
    public $showModal = false;
    public $showRepostsModal = false;
    public $showLowCreditWarningModal = false;

    // Selected user and music
    public $selectedUserUrn;
    public $user;
    public $user_urn;
    public $music;
    public $selectedMusicId;
    public $selectedPlaylistId;

    // Tabs and search
    public $activeTab = 'tracks';
    public $searchQuery = '';
    public $playListTrackShow = false;

    // Collections
    public $tracks = [];
    public $playlists = [];
    public $allTracks = [];
    public $allPlaylists = [];
    public $allPlaylistTracks = [];

    // Pagination limits
    public $trackLimit = 10;
    public $playlistLimit = 10;
    public $playlistTrackLimit = 10;
    public $perPage = 10;

    // Request form fields
    public $description = '';
    public $following = false;
    public $alreadyFollowing = false;
    public $commentable = false;
    public $likeable = true;
    public $blockMismatchGenre = false;
    public $userMismatchGenre = null;

    // Services
    protected $soundCloudService;
    protected $playlistService;
    protected $trackService;
    protected $baseUrl;

    protected $rules = [
        'description' => 'nullable|string|max:200',
        'following' => 'boolean',
        'commentable' => 'boolean',
        'likeable' => 'boolean',
    ];

    protected $messages = [
        'description.max' => 'Personal message cannot exceed 200 characters.',
    ];

    public function boot(
        SoundCloudService $soundCloudService,
        PlaylistService $playlistService,
        TrackService $trackService
    ) {
        $this->soundCloudService = $soundCloudService;
        $this->playlistService = $playlistService;
        $this->trackService = $trackService;
        $this->baseUrl = config('services.soundcloud.api_url');
    }

    public function mount()
    {
        if ($this->starred === 'favourite') {
            $this->loadYourFavouriteMembers();
        } elseif ($this->starred === 'favourited') {
            $this->loadFavouriteMembers();
        }
        $this->soundCloudService->refreshUserTokenIfNeeded(user());
    }



    public function loadYourFavouriteMembers()
    {
        $this->favouriteUsers = StarredUser::with('following.genres')
            ->where('follower_urn', user()->urn)
            ->get();
    }

    public function loadFavouriteMembers()
    {
        $this->favouriteUsers = StarredUser::with('follower.genres')
            ->where('starred_user_urn', user()->urn)
            ->get();
    }

    // Search functionality
    public function updatedSearchQuery()
    {
        $this->searchSoundcloud();
    }

    public function searchSoundcloud()
    {
        if (empty($this->searchQuery)) {
            $this->resetSearchData();
            return;
        }
        $this->performLocalSearch();
    }

    private function performLocalSearch()
    {
        if ($this->activeTab === 'tracks' && $this->playListTrackShow && $this->selectedPlaylistId) {
            $this->searchPlaylistTracks();
        } elseif ($this->activeTab === 'tracks') {
            $this->searchTracks();
        } elseif ($this->activeTab === 'playlists') {
            $this->searchPlaylists();
        }
    }

    private function searchPlaylistTracks()
    {
        $this->allPlaylistTracks = Playlist::findOrFail($this->selectedPlaylistId)
            ->tracks()
            ->where(function ($query) {
                $query->where('permalink_url', $this->searchQuery)
                    ->orWhere('title', 'like', '%' . $this->searchQuery . '%');
            })
            ->get();

        $this->tracks = $this->allPlaylistTracks->take($this->perPage);
    }

    private function searchTracks()
    {
        $query = Track::self()
            ->where(function ($q) {
                $q->where('title', 'like', '%' . $this->searchQuery . '%')
                    ->orWhere('permalink_url', 'like', '%' . $this->searchQuery . '%');
            });

        $this->allTracks = $query->with('user')->get();

        if ($this->allTracks->isEmpty() && $this->isSoundCloudUrl($this->searchQuery)) {
            $this->resolveSoundcloudUrl();
        }

        $this->tracks = $this->allTracks->take($this->trackLimit);
    }

    private function searchPlaylists()
    {
        $query = Playlist::self()
            ->where(function ($q) {
                $q->where('title', 'like', '%' . $this->searchQuery . '%')
                    ->orWhere('permalink_url', 'like', '%' . $this->searchQuery . '%');
            });

        $this->allPlaylists = $query->get();

        if ($this->allPlaylists->isEmpty() && $this->isSoundCloudUrl($this->searchQuery)) {
            $this->resolveSoundcloudUrl();
        }

        $this->playlists = $this->allPlaylists->take($this->playlistLimit);
    }

    private function isSoundCloudUrl($url)
    {
        return preg_match('/^https?:\/\/(www\.)?soundcloud\.com\/[a-zA-Z0-9\-_]+(\/[a-zA-Z0-9\-_]+)*(\/)?(\?.*)?$/i', $url);
    }

    private function resetSearchData()
    {
        if ($this->activeTab === 'tracks') {
            if ($this->playListTrackShow && $this->selectedPlaylistId) {
                $this->allTracks = Playlist::findOrFail($this->selectedPlaylistId)->tracks()->get();
            } else {
                $this->allTracks = Track::self()->get();
            }
            $this->tracks = $this->allTracks->take($this->trackLimit);
        } elseif ($this->activeTab === 'playlists') {
            $this->allPlaylists = Playlist::self()->get();
            $this->playlists = $this->allPlaylists->take($this->playlistLimit);
        }
    }

    protected function resolveSoundcloudUrl()
    {
        try {
            $resolvedData = $this->soundCloudService->makeResolveApiRequest(
                $this->searchQuery,
                'Failed to resolve SoundCloud URL'
            );

            if (!isset($resolvedData) || $resolvedData == null) {
                $this->dispatch('alert', type: 'error', message: 'Could not resolve the SoundCloud link. Please check the URL.');
                return;
            }

            $urn = $resolvedData['urn'];

            if ($this->activeTab === 'playlists') {
                $this->resolvePlaylist($resolvedData, $urn);
            } elseif ($this->activeTab === 'tracks') {
                $this->resolveTrack($resolvedData, $urn);
            }

            $this->processSearchData($urn);
            Log::info('Successfully resolved SoundCloud URL: ' . $this->searchQuery);
        } catch (Exception $e) {
            Log::error('Failed to resolve SoundCloud URL', ['error' => $e->getMessage()]);
            $this->dispatch('alert', type: 'error', message: 'Could not resolve the SoundCloud link. Please check the URL.');
        }
    }

    private function resolvePlaylist($resolvedData, $urn)
    {
        if (isset($resolvedData['tracks']) && count($resolvedData['tracks']) > 0) {
            $this->soundCloudService->unknownPlaylistAdd($resolvedData);
            Log::info('Playlist resolved successfully: ' . $urn);
        } else {
            $this->dispatch('alert', type: 'error', message: 'Could not resolve the SoundCloud link. Please check the Playlist URL.');
        }
    }

    private function resolveTrack($resolvedData, $urn)
    {
        if (!isset($resolvedData['tracks'])) {
            $this->soundCloudService->unknownTrackAdd($resolvedData);
            Log::info('Track resolved successfully: ' . $urn);
        } else {
            $this->dispatch('alert', type: 'error', message: 'Could not resolve the SoundCloud link. Please check the Track URL.');
        }
    }

    protected function processSearchData($urn)
    {
        if ($this->playListTrackShow && $this->activeTab === 'tracks' && $this->selectedPlaylistId) {
            $tracksFromDb = Playlist::findOrFail($this->selectedPlaylistId)
                ->tracks()
                ->where('soundcloud_urn', $urn)
                ->get();

            if ($tracksFromDb->isNotEmpty()) {
                $this->allPlaylistTracks = $tracksFromDb;
                $this->tracks = $this->allPlaylistTracks->take($this->playlistTrackLimit);
                return;
            }
        }

        if ($this->activeTab === 'tracks') {
            $tracksFromDb = Track::where('urn', $urn)->get();

            if ($tracksFromDb->isNotEmpty()) {
                $this->allTracks = $tracksFromDb;
                $this->tracks = $this->allTracks->take($this->trackLimit);
                return;
            }
        }

        if ($this->activeTab === 'playlists') {
            $playlistsFromDb = Playlist::where('soundcloud_urn', $urn)->get();

            if ($playlistsFromDb->isNotEmpty()) {
                $this->allPlaylists = $playlistsFromDb;
                $this->playlists = $this->allPlaylists->take($this->playlistLimit);
                return;
            }
        }
    }

    protected function resetCollections()
    {
        $this->allTracks = collect();
        $this->tracks = collect();
        $this->allPlaylists = collect();
        $this->playlists = collect();
    }

    public function showPlaylistTracks(int $playlistId)
    {
        $this->reset(['searchQuery', 'playlistTrackLimit']);
        $this->selectedPlaylistId = $playlistId;

        $playlist = Playlist::with('tracks')->find($playlistId);
        $this->allTracks = $playlist ? $playlist->tracks : collect();
        $this->tracks = $this->allTracks->take($this->trackLimit);
        $this->activeTab = 'tracks';
        $this->playListTrackShow = true;
    }

    public function openModal(string $userUrn)
    {
        if (!is_email_verified()) {
            $this->dispatch('alert', type: 'error', message: 'Please verify your email to send a request.');
            return;
        }

        $this->soundCloudService->syncUserTracks(user(), []);
        $this->soundCloudService->syncUserPlaylists(user());

        $this->reset([
            'showModal',
            'user',
            'selectedPlaylistId',
            'selectedMusicId',
            'activeTab',
            'tracks',
            'playlists',
            'trackLimit',
            'playlistLimit',
            'searchQuery',
            'playListTrackShow',
        ]);

        $this->selectedUserUrn = $userUrn;
        $this->user = User::with('userInfo')->where('urn', $this->selectedUserUrn)->first();

        if (!$this->user) {
            $this->dispatch('alert', type: 'error', message: 'User not found.');
            return;
        }

        if (userCredits() < $this->user->repost_price || userCredits() < 50) {
            $this->showLowCreditWarningModal = true;
            $this->showModal = false;
            return;
        }

        if (!requestReceiveable($this->user->urn)) {
            $this->dispatch('alert', type: 'error', message: 'User is not accepting requests at this time.');
            return;
        }

        $this->showModal = true;
        $this->activeTab = 'tracks';
        $this->user_urn = $this->user->urn;

        $this->allTracks = Track::self()->get();
        $this->tracks = $this->allTracks->take($this->trackLimit);
        $this->allPlaylists = Playlist::self()->get();
        $this->playlists = $this->allPlaylists->take($this->playlistLimit);
    }

    public function closeModal()
    {
        $this->reset([
            'showModal',
            'user',
            'selectedUserUrn',
            'selectedPlaylistId',
            'selectedMusicId',
            'activeTab',
            'tracks',
            'playlists',
            'trackLimit',
            'playlistLimit',
            'searchQuery',
            'playListTrackShow'
        ]);
    }

    public function setActiveTab(string $tab)
    {
        $this->reset(['selectedPlaylistId', 'selectedMusicId', 'searchQuery', 'playListTrackShow']);
        $this->activeTab = $tab;
        $this->searchSoundcloud();
    }

    public function openRepostsModal(string $type, int $musicId)
    {
        if (!$this->user) {
            $this->dispatch('alert', type: 'error', message: 'Please select a user first.');
            return;
        }

        $this->blockMismatchGenre = UserSetting::where('user_urn', $this->user->urn)
            ->value('block_mismatch_genre') ?? false;

        $this->reset(['music']);
        $this->selectedMusicId = $musicId;

        if ($type === 'playlist') {
            $playlist = $this->playlistService->getPlaylist(encrypt($musicId), 'id');
            $playlistGenre = $playlist->genre;
            $this->userMismatchGenre = UserGenre::where('user_urn', $this->user->urn)
                ->where('genre', $playlistGenre)
                ->first();
            $this->music = $playlist;
        } elseif ($type === 'track') {
            $track = $this->trackService->getTrack(encrypt($musicId));
            $trackGenre = $track->genre;
            $this->userMismatchGenre = UserGenre::where('user_urn', $this->user->urn)
                ->where('genre', $trackGenre)
                ->first();
            $this->music = $track;
        }

        $this->showRepostsModal = true;
        $this->checkIfAlreadyFollowing();
    }

    private function checkIfAlreadyFollowing()
    {
        try {
            $httpClient = Http::withHeaders([
                'Authorization' => 'OAuth ' . user()->token,
            ]);
            $userUrn = $this->user->urn;
            $checkResponse = $httpClient->get("{$this->baseUrl}/me/followings/{$userUrn}");

            if ($checkResponse->getStatusCode() === 200) {
                $this->following = false;
                $this->alreadyFollowing = true;
            }
        } catch (Exception $e) {
            Log::error('Failed to check following status', ['error' => $e->getMessage()]);
        }
    }

    public function closeRepostModal()
    {
        $this->reset(['music', 'selectedMusicId', 'selectedPlaylistId', 'showRepostsModal']);
    }

    public function createRepostsRequest()
    {
        $this->validate();

        // Validate credits
        $totalCredits = repostPrice($this->user->repost_price, $this->commentable, $this->likeable);

        if (userCredits() < $totalCredits) {
            $this->addError('credits', 'Insufficient credits to send this request.');
            return;
        }

        $this->soundCloudService->ensureSoundCloudConnection(user());
        $requester = user();

        if (!$this->user || !$this->music) {
            $this->dispatch('alert', type: 'error', message: 'Target user or content not found.');
            return;
        }

        // Handle following
        if ($this->following && !$this->alreadyFollowing) {
            if (!$this->followUser()) {
                return;
            }
        }

        try {
            $credit_spent = $this->user->repost_price;
            $transaction_credits = repostPrice($this->user->repost_price, $this->commentable, $this->likeable);

            DB::transaction(function () use ($requester, $credit_spent, $transaction_credits) {
                $repostRequest = RepostRequest::create([
                    'requester_urn' => $requester->urn,
                    'target_user_urn' => $this->user->urn,
                    'music_id' => $this->music->id,
                    'music_type' => get_class($this->music),
                    'credits_spent' => $credit_spent,
                    'description' => $this->description,
                    'likeable' => $this->likeable,
                    'commentable' => $this->commentable,
                    'following' => $this->following,
                    'requested_at' => now(),
                    'expired_at' => now()->addDay(),
                ]);

                CreditTransaction::create([
                    'receiver_urn' => $requester->urn,
                    'sender_urn' => $this->user->urn,
                    'transaction_type' => CreditTransaction::TYPE_SPEND,
                    'calculation_type' => CreditTransaction::CALCULATION_TYPE_CREDIT,
                    'source_id' => $repostRequest->id,
                    'source_type' => RepostRequest::class,
                    'amount' => 0,
                    'credits' => $transaction_credits,
                    'description' => "Repost request for track by " . $requester->name,
                    'metadata' => [
                        'request_type' => 'track',
                        'target_urn' => get_class($this->music) == Track::class
                            ? $this->music->urn
                            : $this->music->soundcloud_urn,
                    ],
                    'status' => CreditTransaction::STATUS_SUCCEEDED,
                ]);

                $this->sendNotifications($requester, $repostRequest, $credit_spent);
            });

            sleep(1);
            $this->closeRepostModal();
            $this->closeModal();
            $this->reset();
            $this->dispatch('alert', type: 'success', message: 'Repost request sent successfully!');
        } catch (InvalidArgumentException $e) {
            Log::error('Repost request failed', ['error' => $e->getMessage()]);
            $this->dispatch('alert', type: 'error', message: $e->getMessage());
        } catch (Exception $e) {
            Log::error('Repost request failed', ['error' => $e->getMessage()]);
            $this->dispatch('alert', type: 'error', message: 'Failed to send repost request. Please try again.');
        }
    }

    private function followUser()
    {
        try {
            $httpClient = Http::withHeaders([
                'Authorization' => 'OAuth ' . user()->token,
            ]);

            $follow_response = $httpClient->put("{$this->baseUrl}/me/followings/{$this->user->urn}");

            if (!$follow_response->successful()) {
                $this->dispatch('alert', type: 'error', message: 'Failed to follow user.');
                return false;
            }

            Log::info('Successfully followed user: ' . $this->user->urn);
            return true;
        } catch (Exception $e) {
            Log::error('Failed to follow user', ['error' => $e->getMessage()]);
            $this->dispatch('alert', type: 'error', message: 'Failed to follow user.');
            return false;
        }
    }

    private function sendNotifications($requester, $repostRequest, $credit_spent)
    {
        $targetUserNotification = CustomNotification::create([
            'sender_id' => $requester->id,
            'sender_type' => get_class($requester),
            'receiver_id' => $this->user->id,
            'receiver_type' => get_class($this->user),
            'type' => CustomNotification::TYPE_USER,
            'url' => route('user.reposts-request'),
            'message_data' => [
                'title' => 'Repost Request Received',
                'message' => 'You have received a repost request from ' . $requester->name,
                'description' => 'You have received a repost request from ' . $requester->name . ' for the track "' . $this->music->title . '".',
                'icon' => 'music',
                'additional_data' => [
                    'Request Received From' => $requester->name,
                    'Track Title' => $this->music->title,
                    'Track Artist' => $this->music?->user?->name ?? $requester->name,
                    'Credits Offered' => $credit_spent,
                ]
            ]
        ]);

        broadcast(new UserNotificationSent($targetUserNotification));

        $repostEmailPermission = hasEmailSentPermission('em_new_repost', $this->user->urn);

        if ($repostEmailPermission) {
            $emailData = [
                [
                    'email' => $this->user->email,
                    'subject' => 'Repost Request Received',
                    'title' => 'Dear ' . $this->user->name,
                    'body' => 'You have received a repost request from ' . $requester->name . ' for the track "' . $this->music->title . '".',
                    'url' => route('user.reposts-request'),
                ],
            ];
            NotificationMailSent::dispatch($emailData);
        }
    }

    public function loadMoreTracks()
    {
        $this->trackLimit += 4;
        $this->tracks = $this->allTracks->take($this->trackLimit);
    }

    public function loadMorePlaylists()
    {
        $this->playlistLimit += 4;
        $this->playlists = $this->allPlaylists->take($this->playlistLimit);
    }

    public function render()
    {
        return view('livewire.user.favourite-member');
    }
}
