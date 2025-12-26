<?php

namespace App\Livewire\User;

use App\Events\UserNotificationSent;
use App\Jobs\NotificationMailSent;
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
use App\Models\UserAnalytics;
use App\Services\SoundCloud\FollowerAnalyzer;
use App\Services\SoundCloud\SoundCloudService;
use App\Services\User\AnalyticsService;
use Illuminate\Http\Client\Request;
use Livewire\Attributes\Url;

class Member extends Component
{
    use WithPagination;

    protected string $baseUrl = 'https://api.soundcloud.com';

    public ?int $perPage = 9;
    public string $page_slug = 'members';
    public string $search = '';
    #[Url(as: 'genre', except: '')]
    public array $selectedGenres = [];
    public string $costFilter = '';
    public bool $showModal = false;
    public bool $showLowCreditWarningModal = false;
    public bool $playListTrackShow = false;
    public bool $showRepostsModal = false;
    public string $activeTab = 'tracks';
    public ?string $selectedUserUrn = null;
    public ?int $selectedPlaylistId = null;
    public ?int $selectedMusicId = null;
    public string $searchQuery = '';
    public $creditSpent = 0;

    public string $description = '';
    public bool $commentable = false;
    public bool $likeable = true;
    public bool $following = false;
    public bool $alreadyFollowing = false;

    // block_mismatch_genre
    public ?bool $blockMismatchGenre = null;
    public $userMismatchGenre = null;
    public ?User $user = null;
    public ?string $user_urn = null;
    public ?UserInformation $userinfo = null;

    public Collection $playlists;
    public Collection $tracks;
    public $music = null;

    public int $trackLimit = 4;
    public Collection $allTracks;
    public int $playlistLimit = 4;
    public Collection $allPlaylists;
    public int $playlistTrackLimit = 4;

    public array $genres = [];
    public Collection $trackTypes;

    private $soundcloudClientId;
    private string $soundcloudApiUrl = 'https://api-v2.soundcloud.com';

    protected $listeners = ['refreshData' => 'render'];

    protected TrackService $trackService;
    protected PlaylistService $playlistService;
    protected RepostRequestService $repostRequestService;
    protected SoundCloudService $soundCloudService;
    protected FollowerAnalyzer $followerAnalyzer;
    protected AnalyticsService $analyticsService;
    public function boot(TrackService $trackService, PlaylistService $playlistService, RepostRequestService $repostRequestService, SoundCloudService $soundCloudService, FollowerAnalyzer $followerAnalyzer, AnalyticsService $analyticsService)
    {
        $this->trackService = $trackService;
        $this->playlistService = $playlistService;
        $this->repostRequestService = $repostRequestService;
        $this->soundCloudService = $soundCloudService;
        $this->soundcloudClientId = config('services.soundcloud.client_id');
        $this->followerAnalyzer = $followerAnalyzer;
        $this->analyticsService = $analyticsService;
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
        $this->soundCloudService->refreshUserTokenIfNeeded(user());

        $this->validateOnly($propertyName);
        if ($propertyName === 'likeable' || $propertyName === 'commentable') {
            $this->creditSpent = repostPrice($this->user->repost_price, $this->commentable, $this->likeable);
        }
        // + ($this->likeable ? 2 : 0)
        // + ($this->commentable ? 2 : 0);

        if (userCredits() < $this->creditSpent) {
            $this->addError('credits', 'Your credits are not enough.');
            return;
        }
    }

    public function mount()
    {
        $this->genres = AllGenres();
        $this->userinfo = user()->userInfo;
        $this->costFilter = request()->get('cost', 'low_to_high');
    }
    public function updatedSearch()
    {
        $this->resetPage();
        // $this->navigatingAway();
    }
    public function navigatingAway()
    {
        $params = [];
        if (!empty($this->selectedGenres)) {
            $params['genre'] = $this->selectedGenres;
        }

        if (!empty($this->costFilter)) {
            $params['cost'] = $this->costFilter;
        }

        // if (!empty($this->search)) {
        //     $params['q'] = $this->search;
        // }

        return $this->redirect(route('user.members') . '?' . http_build_query($params), navigate: true);
    }

    public function filterBygenre($genre)
    {
        if (in_array($genre, $this->selectedGenres)) {
            $this->selectedGenres = array_diff($this->selectedGenres, [$genre]);
        } else {
            $this->selectedGenres[] = $genre;
        }
        $this->resetPage();
        $this->navigatingAway();
    }

    // public function updatedCostFilter()
    // {
    //     $this->resetPage();
    // }
    public function filterByCost($filterBy)
    {
        $this->costFilter = $filterBy;
        // $this->resetPage();
        $this->navigatingAway();
        // return $this->redirectIntended(route() . '?cost=' . $filterBy, navigate: true);
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
        // if (filter_var($this->searchQuery, FILTER_VALIDATE_URL) && preg_match('/^https?:\/\/(www\.)?soundcloud\.com\/[a-zA-Z0-9\-_]+(\/[a-zA-Z0-9\-_]+)*(\/)?(\?.*)?$/i', $this->searchQuery)) {
        $this->performLocalSearch();
    }
    public $allPlaylistTracks;

    public function getCredibilityScore(object $user)
    {
        // $userFollowerAnalysis = $this->followerAnalyzer->getQuickStats($this->soundCloudService->getAuthUserFollowers($user));
        // return $userFollowerAnalysis['averageCredibilityScore'];
    }

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
            if ($this->allTracks->isEmpty() && preg_match('/^https?:\/\/(www\.)?soundcloud\.com\/[a-zA-Z0-9\-_]+(\/[a-zA-Z0-9\-_]+)*(\/)?(\?.*)?$/i', $this->searchQuery)) {
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
            if ($this->allPlaylists->isEmpty() && preg_match('/^https?:\/\/(www\.)?soundcloud\.com\/[a-zA-Z0-9\-_]+(\/[a-zA-Z0-9\-_]+)*(\/)?(\?.*)?$/i', $this->searchQuery)) {
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
        $resolvedData = $this->soundCloudService->makeResolveApiRequest($this->searchQuery, 'Failed to resolve SoundCloud URL');
        if (isset($resolvedData) && $resolvedData != null) {
            $urn = $resolvedData['urn'];
            if ($this->activeTab === 'playlists') {
                if (isset($resolvedData['tracks']) && count($resolvedData['tracks']) > 0) {
                    $this->soundCloudService->unknownPlaylistAdd($resolvedData);
                    Log::info('Resolved SoundCloud URL: ' . "Successfully resolved SoundCloud URL: " . $this->searchQuery);
                } else {
                    $this->dispatch('alert', type: 'error', message: 'Could not resolve the SoundCloud link. Please check the Playlist URL.');
                }
            } elseif ($this->activeTab === 'tracks') {
                if (!isset($resolvedData['tracks'])) {
                    $this->soundCloudService->unknownTrackAdd($resolvedData);
                    Log::info('Resolved SoundCloud URL: ' . "Successfully resolved SoundCloud URL: " . $this->searchQuery);
                } else {
                    $this->dispatch('alert', type: 'error', message: 'Could not resolve the SoundCloud link. Please check the Track URL.');
                }
            }
            $this->processSearchData($urn);
            Log::info('Resolved SoundCloud URL: ' . "Successfully resolved SoundCloud URL: " . $this->searchQuery);
        } else {
            $this->dispatch('alert', type: 'error', message: 'Could not resolve the SoundCloud link. Please check the URL.');
        }
    }

    protected function processSearchData($urn)
    {
        if ($this->playListTrackShow == true && $this->activeTab === 'tracks') {
            $tracksFromDb = Playlist::findOrFail($this->selectedPlaylistId)->tracks()
                ->where('soundcloud_urn', $urn)
                ->get();

            if ($tracksFromDb->isNotEmpty()) {
                $this->allPlaylistTracks = $tracksFromDb;
                $this->tracks = $this->allPlaylistTracks->take($this->playlistTrackLimit);
                // $this->hasMoreTracks = $this->tracks->count() === $this->trackLimit;
                return;
            }
        } else {
            if ($this->activeTab == 'tracks') {
                $tracksFromDb = Track::where('urn', $urn)
                    ->get();

                if ($tracksFromDb->isNotEmpty()) {
                    $this->activeTab = 'tracks';
                    $this->allTracks = $tracksFromDb;
                    $this->tracks = $this->allTracks->take($this->trackLimit);
                    // $this->hasMoreTracks = $this->tracks->count() === $this->trackLimit;
                    return;
                }
            }

            if ($this->activeTab == 'playlists') {
                $playlistsFromDb = Playlist::where('soundcloud_urn', $urn)
                    ->get();

                if ($playlistsFromDb->isNotEmpty()) {
                    $this->activeTab = 'playlists';
                    $this->allPlaylists = $playlistsFromDb;
                    $this->playlists = $this->allPlaylists->take($this->playlistLimit);
                    // $this->hasMorePlaylists = $this->playlists->count() === $this->playlistLimit;
                    return;
                }
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

        $alreadyRequested = RepostRequest::where('requester_urn', user()->urn)->where('status', RepostRequest::STATUS_PENDING)->count();

        if(!proUser(user()->urn) && $alreadyRequested >= 20) {
            $this->dispatch('alert', type: 'error', message: 'You have reached the limit of 20 requests.');
        }
        if(proUser(user()->urn) && $alreadyRequested >= 100) {
            $this->dispatch('alert', type: 'error', message: 'You have reached the limit of 100 requests.');
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
        if (userCredits() < $this->user->repost_price || userCredits() < 50) {
            $this->showLowCreditWarningModal = true;
            $this->showModal = false;
            return;
        }
        if (requestReceiveable($this->user->urn)) {
            $this->showModal = true;
            $this->activeTab = 'tracks';

            $this->user_urn = $this->user->urn;
            $this->allTracks = Track::self()->get();
            $this->tracks = $this->allTracks->take($this->trackLimit);
            $this->allPlaylists = Playlist::self()->get();
            $this->playlists = $this->allPlaylists->take($this->playlistLimit);
        } else {
            $this->dispatch('alert', type: 'error', message: 'User is Not Request Receiveable');
        }
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
        $this->blockMismatchGenre = UserSetting::where('user_urn', $this->user->urn)->value('block_mismatch_genre');
        $this->reset(['music']);
        if ($type === 'playlist') {
            $playlist = $this->playlistService->getPlaylist(encrypt($musicId), 'id');
            $playlistGenre = $playlist->genre;
            $this->userMismatchGenre = UserGenre::where('user_urn', $this->user->urn)->where('genre', $playlistGenre)->first();
            $this->selectedMusicId = $musicId;
            $this->music = $playlist;
        } elseif ($type === 'track') {
            $trackGenre = $this->trackService->getTrack(encrypt($musicId))->genre;
            $this->userMismatchGenre = UserGenre::where('user_urn', $this->user->urn)->where('genre', $trackGenre)->first();

            $this->selectedMusicId = $musicId;
            $this->music = Track::find($musicId);
        }

        $this->showRepostsModal = true;
        $httpClient = Http::withHeaders([
            'Authorization' => 'OAuth ' . user()->token,
        ]);
        $userUrn = $this->user->urn;
        $checkResponse = $httpClient->get("{$this->baseUrl}/me/followings/{$userUrn}");

        if ($checkResponse->getStatusCode() === 200) {
            $this->following = false;
            $this->alreadyFollowing = true;
        }
    }

    public function closeRepostModal()
    {
        $this->reset(['music', 'selectedMusicId', 'selectedPlaylistId', 'showRepostsModal']);
    }

    public function createRepostsRequest()
    {
        $this->validate();
        // $this->soundCloudService->ensureSoundCloudConnection(user());
        $this->soundCloudService->refreshUserTokenIfNeeded(user());
        $requester = user();

        if (!$this->user || !$this->music) {
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
                Log::info('Member page-> Followed user: ' . $this->user->urn);
            }
        }
        try {
            // $credit_spent = repostPrice($this->user);
            // $transaction_credits = repostPrice($this->user) + ($this->likeable ? 2 : 0) + ($this->commentable ? 2 : 0);
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
                        'target_urn' => get_class($this->music) == Track::class ? $this->music->urn : $this->music->soundcloud_urn,
                    ],
                    'status' => CreditTransaction::STATUS_SUCCEEDED,
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
                if ($repostRequest && $creditTransaction && $repostEmailPermission) {
                    $datas = [
                        [
                            'email' => $this->user->email,
                            'subject' => 'Repost Request Received',
                            'title' => 'Dear ' . $this->user->name,
                            'body' => 'You have received a repost request from ' . $requester->name . ' for the track "' . $this->music->title . '".',
                            'url' => route('user.reposts-request'),
                        ],
                    ];
                    NotificationMailSent::dispatch($datas);
                }
            });
            sleep(1);
            $this->closeRepostModal();
            $this->closeModal();
            $this->reset();
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
        $query = User::where('urn', '!=', user()->urn)->where('banned_at', null)
            ->with(['userInfo', 'genres', 'tracks', 'reposts', 'playlists'])->active();
        if ($this->selectedGenres) {
            $query->whereHas('genres', function ($q) {
                $q->whereIn('genre', $this->selectedGenres);
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
            $collection = $users->getCollection();
            if ($this->costFilter === 'low_to_high') {
                $sortedCollection = $collection->sortBy('repost_price');
            } else {
                $sortedCollection = $collection->sortByDesc('repost_price');
            }

            $users->setCollection($sortedCollection->values());
        }

        return view('livewire.user.member', [
            'users' => $users,
        ]);
    }
}
