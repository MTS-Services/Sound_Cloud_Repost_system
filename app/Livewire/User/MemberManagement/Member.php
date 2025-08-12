<?php

namespace App\Livewire\User\MemberManagement;

use App\Models\CreditTransaction;
use App\Models\Playlist;
use App\Models\RepostRequest;
use App\Models\Track;
use App\Models\User;
use App\Models\UserInformation;
use App\Services\PlaylistService;
use App\Services\TrackService;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use InvalidArgumentException;
use Livewire\Component;
use Livewire\WithPagination;

class Member extends Component
{
    use WithPagination;

    public ?int $perPage = 9;
    public string $page_slug = 'members';
    public string $search = '';
    public string $genreFilter = '';
    public string $costFilter = '';
    public bool $showModal = false;
    public bool $playListTrackShow = false;
    public bool $showRepostsModal = false;
    public string $activeTab = 'tracks';
    public ?string $selectedUserUrn = null;
    public ?int $selectedPlaylistId = null;
    public ?int $selectedTrackId = null;
    public string $searchQuery = '';

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

    private string $soundcloudClientId;
    private string $soundcloudApiUrl = 'https://api-v2.soundcloud.com';

    protected $listeners = ['refreshData' => 'render'];

    protected TrackService $trackService;
    protected PlaylistService $playlistService;

    public function boot(TrackService $trackService, PlaylistService $playlistService)
    {
        $this->trackService = $trackService;
        $this->playlistService = $playlistService;
        $this->soundcloudClientId = config('services.soundcloud.client_id');
    }

    public function mount()
    {
        $this->genres = $this->getAvailableGenres();
        $this->userinfo = user()->userInfo;
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
            $this->tracks = $this->allPlaylistTracks->take($this->perPage);
        }
        if ($this->activeTab === 'tracks') {
            $query = Track::self()
                ->where(function ($q) {
                    $q->where('title', 'like', '%' . $this->searchQuery . '%')
                        ->orWhere('permalink_url', 'like', '%' . $this->searchQuery . '%');
                });

            $this->allTracks = $query->get();
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
            session()->flash('error', 'Could not resolve the SoundCloud link. Please check the URL.');
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
                session()->flash('error', 'The provided URL is not a track or user profile.');
            }
        } elseif ($this->activeTab === 'playlists') {
            if ($data['kind'] === 'playlist') {
                $localPlaylist = Playlist::self()->where('soundcloud_urn', $data['urn'])->first();
                $this->allPlaylists = $localPlaylist ? collect([$localPlaylist]) : collect();
                $this->playlists = $this->allPlaylists->take($this->playlistLimit);
            } else {
                $this->resetCollections();
                session()->flash('error', 'The provided URL is not a playlist.');
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
        $this->selectedUserUrn = $userUrn;
        $this->showModal = true;
        $this->activeTab = 'tracks';
        $this->user = User::with('userInfo')->where('urn', $this->selectedUserUrn)->first();
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
            session()->flash('error', 'Target user or content not found.');
            return;
        }

        try {
            $amount = repostPrice($this->user);

            DB::transaction(function () use ($requester, $amount) {
                $repostRequest = RepostRequest::create([
                    'requester_urn' => $requester->urn,
                    'target_user_urn' => $this->user->urn,
                    'track_urn' => $this->track->urn,
                    'credits_spent' => $amount,
                ]);

                CreditTransaction::create([
                    'receiver_urn' => $requester->urn,
                    'sender_urn' => $this->user->urn,
                    'transaction_type' => CreditTransaction::TYPE_SPEND,
                    'calculation_type' => CreditTransaction::CALCULATION_TYPE_CREDIT,
                    'source_id' => $repostRequest->id,
                    'source_type' => RepostRequest::class,
                    'amount' => 0,
                    'credits' => $amount,
                    'description' => "Repost request for track by " . $requester->name,
                    'metadata' => [
                        'request_type' => 'track',
                        'target_urn' => $this->track->urn,
                    ],
                    'status' => 'succeeded',
                ]);
            });

            $this->closeRepostModal();
            $this->closeModal();
            session()->flash('success', 'Repost request sent successfully!');
        } catch (InvalidArgumentException $e) {
            session()->flash('error', $e->getMessage());
        } catch (Exception $e) {
            session()->flash('error', 'Failed to send repost request. Please try again.');
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
            ->with('userInfo');

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

        return view('livewire.user.member-management.member', [
            'users' => $users,
        ]);
    }
}
