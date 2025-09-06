<?php

namespace App\Livewire\User;

use App\Events\UserNotificationSent;
use App\Models\CreditTransaction;
use App\Models\CustomNotification;
use App\Models\Playlist;
use App\Models\RepostRequest;
use App\Models\Track;
use App\Models\User;
use App\Models\UserGenre;
use App\Models\UserInformation;
use App\Models\UserSetting;
use App\Services\PlaylistService;
use App\Services\TrackService;
use App\Services\User\Mamber\RepostRequestService;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Feature;

class Member extends Component
{
    use WithPagination;

    protected string $baseUrl = 'https://api.soundcloud.com';

    public ?int $perPage = 9;
    public string $page_slug = 'members';
    public string $search = '';
    public string $genreFilter = '';
    public string $costFilter = '';
    public bool $showModal = false;
    public bool $showLowCreditWarningModal = false;
    public bool $playListTrackShow = false;
    public bool $showRepostsModal = false;
    public string $activeTab = 'tracks';
    public ?string $selectedUserUrn = null;
    public ?int $selectedPlaylistId = null;
    public ?int $selectedTrackId = null;
    public string $searchQuery = '';
    public $creditSpent = 0;

    public string $description = '';
    public bool $commentable = false;
    public bool $likeable = false;
    public bool $following = true;

    // block_mismatch_genre
    public ?bool $blockMismatchGenre = null;
    public $userMismatchGenre = null;
    public ?User $user = null;
    public ?string $user_urn = null;
    public ?UserInformation $userinfo = null;

    public Collection $playlists;
    public Collection $tracks;
    public ?Track $track = null;

    public int $trackLimit = 4;
    public Collection $allTracks;
    public int $playlistLimit = 4;
    public Collection $allPlaylists;
    public int $playlistTrackLimit = 4;

    public Collection $genres;
    public Collection $trackTypes;

    private $soundcloudClientId;
    private string $soundcloudApiUrl = 'https://api-v2.soundcloud.com';

    protected $listeners = ['refreshData' => 'render'];

    protected TrackService $trackService;
    protected PlaylistService $playlistService;

    protected RepostRequestService $repostRequestService;

    public function boot(TrackService $trackService, PlaylistService $playlistService, RepostRequestService $repostRequestService)
    {
        $this->trackService = $trackService;
        $this->playlistService = $playlistService;
        $this->repostRequestService = $repostRequestService;
        $this->soundcloudClientId = config('services.soundcloud.client_id');
    }

    public function rules()
    {
        return [
            'description' => 'nullable|string|max:200',
            'commentable' => 'nullable',
            'likeable' => 'nullable',
        ];
    }
    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
        
        $this->creditSpent = repostPrice($this->user)
            + ($this->likeable ? 2 : 0)
            + ($this->commentable ? 2 : 0);
            
        if (userCredits() < $this->creditSpent) {
            $this->addError('credits', 'Your credits are not enough.');
            return;
        }
    }

    public function mount()
    {
        $this->genres = $this->getAvailableGenres();
        $this->userinfo = user()->userInfo;
        // $this->creditSpent = repostPrice($this->user) + ($this->likeable ? 2 : 0) + ($this->commentable ? 2 : 0);
    }

    private function getAvailableGenres(): Collection
    {
        return Track::where('user_urn', '!=', user()->urn)
            ->distinct()
            ->pluck('genre')
            ->filter()
            ->values();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedGenreFilter()
    {
        $this->resetPage();
    }

    public function updatedCostFilter()
    {
        $this->resetPage();
    }

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
        // if (filter_var($this->searchQuery, FILTER_VALIDATE_URL) && preg_match('/^https?:\/\/(www\.)?soundcloud\.com\//', $this->searchQuery)) {
        $this->performLocalSearch();
    }
    public $allPlaylistTracks;

    private function performLocalSearch()
    {
        if ($this->activeTab === 'tracks' && $this->playListTrackShow) {
            $this->allPlaylistTracks = Playlist::findOrFail($this->selectedPlaylistId)->tracks()
                ->where(function ($query) {
                    $query->where('permalink_url', $this->searchQuery)
                        ->orWhere('title', 'like', '%' . $this->searchQuery . '%');
                })
                ->get();
            $this->tracks = $this->allPlaylistTracks->with('user')->take($this->perPage);
        }
        if ($this->activeTab === 'tracks') {
            $query = Track::self()
                ->where(function ($q) {
                    $q->where('title', 'like', '%' . $this->searchQuery . '%')
                        ->orWhere('permalink_url', 'like', '%' . $this->searchQuery . '%');
                });

            $this->allTracks = $query->with('user')->get();
            if ($this->allTracks->isEmpty() && preg_match('/^https?:\/\/(www\.)?soundcloud\.com\//', $this->searchQuery)) {
                $this->resolveSoundcloudUrl();
            }
            $this->tracks = $this->allTracks->take($this->trackLimit);
        } elseif ($this->activeTab === 'playlists') {
            $query = Playlist::self()
                ->where(function ($q) {
                    $q->where('title', 'like', '%' . $this->searchQuery . '%')
                        ->orWhere('permalink_url', 'like', '%' . $this->searchQuery . '%');
                });

            $this->allPlaylists = $query->get();
            if ($this->allPlaylists->isEmpty() && preg_match('/^https?:\/\/(www\.)?soundcloud\.com\//', $this->searchQuery)) {
                $this->resolveSoundcloudUrl();
            }
            $this->playlists = $this->allPlaylists->take($this->playlistLimit);
        }
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
        $response = Http::get("{$this->soundcloudApiUrl}/resolve", [
            'url' => $this->searchQuery,
            'client_id' => $this->soundcloudClientId,
        ]);

        if ($response->successful()) {
            $this->processResolvedData($response->json());
        } else {
            $this->resetCollections();
            $this->dispatch('alert', type: 'error', message: 'Could not resolve the SoundCloud link. Please check the URL.');
        }
    }

    protected function processResolvedData(array $data)
    {
        if ($this->activeTab === 'tracks') {
            if ($data['kind'] === 'track') {
                $localTrack = Track::self()->where('urn', $data['urn'])->first();
                $this->allTracks = $localTrack ? collect([$localTrack]) : collect();
                $this->tracks = $this->allTracks->take($this->trackLimit);
            } elseif ($data['kind'] === 'user') {
                $this->fetchUserTracks($data['id']);
            } else {
                $this->resetCollections();
                $this->dispatch('alert', type: 'error', message: 'The provided URL is not a track or user profile.');
            }
        } elseif ($this->activeTab === 'playlists') {
            if ($data['kind'] === 'playlist') {
                $localPlaylist = Playlist::self()->where('soundcloud_urn', $data['urn'])->first();
                $this->allPlaylists = $localPlaylist ? collect([$localPlaylist]) : collect();
                $this->playlists = $this->allPlaylists->take($this->playlistLimit);
            } else {
                $this->resetCollections();
                $this->dispatch('alert', type: 'error', message: 'The provided URL is not a playlist.');
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
        $this->reset([
            'showModal',
            'user',
            'selectedPlaylistId',
            'selectedTrackId',
            'activeTab',
            'tracks',
            'playlists',
            'trackLimit',
            'playlistLimit',
            'searchQuery',
            'playListTrackShow',
        ]);
        // if ($this->repostRequestService->thisMonthDirectRequestCount() >= (int) userFeatures()[Feature::KEY_DIRECT_REQUESTS]) {
        //     return $this->dispatch('alert', type: 'error', message: 'You have reached your direct request limit for this month.');
        // }
        $this->selectedUserUrn = $userUrn;
        $this->user = User::with('userInfo')->where('urn', $this->selectedUserUrn)->first();
        if (userCredits() < repostPrice($this->user)) {
            $this->showLowCreditWarningModal = true;
            $this->showModal = false;
            return;
        }
        if ($this->user->request_receiveable) {
            $this->showModal = true;
            $this->activeTab = 'tracks';

            $this->user_urn = $this->user->urn;
            $this->allTracks = Track::self()->get();
            $this->tracks = $this->allTracks->take($this->trackLimit);
            $this->allPlaylists = Playlist::self()->get();
            $this->playlists = $this->allPlaylists->take($this->playlistLimit);
        } else {
            return redirect()->back()->with('error', 'User is Not Request Receiveable');
        }
    }

    public function closeModal()
    {
        $this->reset([
            'showModal',
            'user',
            'selectedUserUrn',
            'selectedPlaylistId',
            'selectedTrackId',
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
        $this->reset(['selectedPlaylistId', 'selectedTrackId', 'searchQuery', 'playListTrackShow']);
        $this->activeTab = $tab;
        $this->searchSoundcloud();
    }

    public function openRepostsModal(int $trackId)
    {
        $this->blockMismatchGenre = UserSetting::where('user_urn', $this->user->urn)->value('block_mismatch_genre');
        // dd($this->blockMismatchGenre);
        $trackGenre = $this->trackService->getTrack(encrypt($trackId))->genre;
        $this->userMismatchGenre = UserGenre::where('user_urn', $this->user->urn)->where('genre', $trackGenre)->first();
        // dd($trackGenre, $this->userMismatchGenre);
        $this->reset(['track']);
        $this->selectedTrackId = $trackId;
        $this->track = Track::find($trackId);
        $this->showRepostsModal = true;
    }

    public function closeRepostModal()
    {
        $this->reset(['track', 'selectedTrackId', 'selectedPlaylistId', 'showRepostsModal']);
    }

    public function createRepostsRequest()
    {
        $requester = user();

        if (!$this->user || !$this->track) {
            $this->dispatch('alert', type: 'error', message: 'Target user or content not found.');
            return;
        }
        $httpClient = Http::withHeaders([
            'Authorization' => 'OAuth ' . user()->token,
        ]);
        $follow_response = null;
        if ($this->following) {
            $follow_response = $httpClient->put("{$this->baseUrl}/me/followings/{$this->user->urn}");
            if (!$follow_response->successful()) {
                $this->dispatch('alert', type: 'error', message: 'Failed to follow user.');
                return;
            } elseif ($follow_response->successful()) {
                $this->following = 1;
            }
        }
        try {
            $credit_spent = repostPrice($this->user);
            $transaction_credits = repostPrice($this->user) + ($this->likeable ? 2 : 0) + ($this->commentable ? 2 : 0);

            DB::transaction(function () use ($requester, $credit_spent, $transaction_credits) {
                $repostRequest = RepostRequest::create([
                    'requester_urn' => $requester->urn,
                    'target_user_urn' => $this->user->urn,
                    'track_urn' => $this->track->urn,
                    'credits_spent' => $credit_spent,
                    'description' => $this->description,
                    'likeable' => $this->likeable,
                    'commentable' => $this->commentable,
                    'following' => $this->following,
                    'expired_at' => now()->addHours(24),
                ]);

                $creditTransaction = CreditTransaction::create([
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
                        'target_urn' => $this->track->urn,
                    ],
                    'status' => CreditTransaction::STATUS_SUCCEEDED,
                ]);

                $requesterNotification = CustomNotification::create([
                    'receiver_id' => $requester->id,
                    'receiver_type' => get_class($requester),
                    'type' => CustomNotification::TYPE_USER,
                    'url' => route('user.reposts-request'),
                    'message_data' => [
                        'title' => 'Repost Request Sent',
                        'message' => 'You have sent a repost request to ' . $this->user->name,
                        'description' => 'You have sent a repost request to ' . $this->user->name . ' for the track "' . $this->track->title . '".',
                        'icon' => 'music',
                        'additional_data' => [
                            'Request Sent To' => $this->user->name,
                            'Track Title' => $this->track->title,
                            'Track Artist' => $this->track?->user?->name ?? $requester->name,
                            'Credits Spent' => $credit_spent,
                        ],
                    ],
                ]);
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
                        'description' => 'You have received a repost request from ' . $requester->name . ' for the track "' . $this->track->title . '".',
                        'icon' => 'music',
                        'additional_data' => [
                            'Request Received From' => $requester->name,
                            'Track Title' => $this->track->title,
                            'Track Artist' => $this->track?->user?->name ?? $requester->name,
                            'Credits Offered' => $credit_spent,
                        ]
                    ]
                ]);

                broadcast(new UserNotificationSent($requesterNotification));
                broadcast(new UserNotificationSent($targetUserNotification));
            });
            sleep(1);
            $this->closeRepostModal();
            $this->closeModal();
            $this->mount();
            $this->dispatch('alert', type: 'success', message: 'Repost request sent successfully!');
        } catch (InvalidArgumentException $e) {
            Log::info('Repost request failed', ['error' => $e->getMessage()]);
            $this->dispatch('alert', type: 'error', message: $e->getMessage());
        } catch (Exception $e) {
            Log::info('Repost request failed', ['error' => $e->getMessage()]);
            $this->dispatch('alert', type: 'error', message: 'Failed to send repost request. Please try again.');
            logger()->error('Repost request failed', ['error' => $e->getMessage()]);
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
        $query = User::where('urn', '!=', user()->urn)
            ->with('userInfo', 'genres')->active();

        if ($this->genreFilter) {
            $query->whereHas('tracks', function ($q) {
                $q->where('genre', 'like', '%' . $this->genreFilter . '%');
            });
        }

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhereHas('userInfo', function ($q2) {
                        $q2->where('soundcloud_uri', 'like', '%' . $this->search . '%');
                    });
            });
        }

        $users = $query->paginate($this->perPage);

        if ($this->costFilter) {
            $collection = $users->getCollection()->each(function ($user) {
                $user->repost_cost = repostPrice($user);
            });

            if ($this->costFilter === 'low_to_high') {
                $sortedCollection = $collection->sortBy('repost_cost');
            } else {
                $sortedCollection = $collection->sortByDesc('repost_cost');
            }

            $users->setCollection($sortedCollection->values());
        }

        return view('livewire.user.member', [
            'users' => $users,
        ]);
    }
}
