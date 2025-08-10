<?php

namespace App\Livewire\User\MemberManagement;

use App\Models\CreditTransaction;
use App\Models\Playlist;
use App\Models\RepostRequest;
use App\Models\Track;
use App\Models\User;
use App\Models\UserInformation;
use App\Services\Admin\UserManagement\UserService;
use App\Services\PlaylistService;
use App\Services\TrackService;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use InvalidArgumentException;
use Livewire\Component;

class Member extends Component
{
    public $page_slug = 'members';
    public $search = '';
    public $genreFilter = '';
    public $costFilter = '';
    public $showModal = false;
     public $playListTrackShow = false;
    public $showRepostsModal = false;
    public $showPlaylistTracksModal = false;
    public $activeTab = 'tracks';
    public $selectedUserUrn = null;
    public $selectedPlaylistId = null;
    public $selectedTrackId = null;
    public $searchQuery = '';
    public $users;
    public $user;
    public $user_urn;
    public $userinfo;
    public $playlists;
    public $playlistTracks;
    public $playlistTrack;
    public $tracks;
    public $track;
    public $credits_spent;
    public $trackLimit = 4;
    public $allTracks;
    public $playlistLimit = 4;
    public $allPlaylists;
    public $allPlaylistTracks;
    public $playlistTrackLimit = 4;

    // Assuming you have your SoundCloud client ID configured somewhere
    private $soundcloudClientId = 'YOUR_SOUNDCLOUD_CLIENT_ID';
    private $soundcloudApiUrl = 'https://api-v2.soundcloud.com';

    protected $listeners = ['refreshData' => 'loadData'];

    protected UserService $userService;
    protected TrackService $trackService;
    protected PlaylistService $playlistService;

    public function boot(TrackService $trackService, PlaylistService $playlistService)
    {
        $this->trackService = $trackService;
        $this->playlistService = $playlistService;
    }

    public function mount()
    {
        $this->loadData();
        $this->page_slug = 'members';
    }

    public function loadData()
    {
        $query = User::where('urn', '!=', user()->urn);
        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }
        $this->users = $query->get();
        $this->userinfo = UserInformation::where('user_urn', user()->urn)->first();
    }

    public function updatedSearch()
    {
        $this->loadData();
    }
    public function updatedGenreFilter()
    {
        $this->loadData();
    }
    public function updatedCostFilter()
    {
        $this->loadData();
    }

    public function updatedSearchQuery()
    {
        $this->searchSoundcloud();
    }

    public function searchSoundcloud()
    {
        // If the search query is empty, reset to local data
        if (empty($this->searchQuery)) {
            if ($this->playListTrackShow == true && $this->activeTab === 'tracks') {
                $this->allPlaylistTracks = Playlist::findOrFail($this->selectedPlaylistId)->tracks()->get();
                $this->tracks = $this->allPlaylistTracks->take($this->playlistTrackLimit);
            } else {
                if ($this->activeTab == 'tracks') {
                    $this->allTracks = Track::where('user_urn', user()->urn)->get();
                    $this->tracks = $this->allTracks->take($this->trackLimit);
                }
                if ($this->activeTab == 'playlists') {
                    $this->allPlaylists = Playlist::where('user_urn', user()->urn)->get();
                    $this->playlists = $this->allPlaylists->take($this->playlistLimit);
                }
            }
            return;
        }

        // Check if the query is a SoundCloud permalink URL
        if (preg_match('/^https?:\/\/(www\.)?soundcloud\.com\//', $this->searchQuery)) {
            $this->resolveSoundcloudUrl();
        } else {
            // If not a URL, perform a text-based "as-like" search on the local database
            if ($this->playListTrackShow == true && $this->activeTab === 'tracks') {
                $this->allPlaylistTracks = Playlist::findOrFail($this->selectedPlaylistId)->tracks()
                    ->where(function ($query) {
                        $query->where('permalink_url', 'like', '%' . $this->searchQuery . '%')
                            ->orWhere('title', 'like', '%' . $this->searchQuery . '%'); // Added title search
                    })
                    ->get();
                $this->tracks = $this->allPlaylistTracks->take($this->playlistTrackLimit);
            } else {
                if ($this->activeTab === 'tracks') {
                    $this->allTracks = Track::where('user_urn', user()->urn)
                        ->where(function ($query) {
                            $query->where('permalink_url', 'like', '%' . $this->searchQuery . '%')
                                ->orWhere('title', 'like', '%' . $this->searchQuery . '%'); // Added title search
                        })
                        ->get();
                    $this->tracks = $this->allTracks->take($this->trackLimit);
                } elseif ($this->activeTab === 'playlists') {
                    $this->allPlaylists = Playlist::where('user_urn', user()->urn)
                        ->where(function ($query) {
                            $query->where('permalink_url', 'like', '%' . $this->searchQuery . '%')
                                ->orWhere('title', 'like', '%' . $this->searchQuery . '%'); // Added title search
                        })
                        ->get();
                    $this->playlists = $this->allPlaylists->take($this->playlistLimit);
                }
            }
        }
    }

    // Resolves a SoundCloud URL to find the corresponding track or playlist
    protected function resolveSoundcloudUrl()
    {
        // Search the local database for matching permalink URLs first
        if ($this->playListTrackShow == true && $this->activeTab === 'tracks') {
            $tracksFromDb = Playlist::findOrFail($this->selectedPlaylistId)->tracks()
                ->where('permalink_url', $this->searchQuery)
                ->get();
            if ($tracksFromDb->isNotEmpty()) {
                $this->allPlaylistTracks = $tracksFromDb;
                $this->tracks = $this->allPlaylistTracks->take($this->playlistTrackLimit);
                return;
            }
        } else {
            if ($this->activeTab == 'tracks') {
                $tracksFromDb = Track::where('user_urn', user()->urn)
                    ->where('permalink_url', $this->searchQuery)
                    ->get();
                if ($tracksFromDb->isNotEmpty()) {
                    $this->activeTab = 'tracks';
                    $this->allTracks = $tracksFromDb;
                    $this->tracks = $this->allTracks->take($this->trackLimit);
                    return;
                }
            }

            if ($this->activeTab == 'playlists') {
                $playlistsFromDb = Playlist::where('user_urn', user()->urn)
                    ->where('permalink_url', $this->searchQuery)
                    ->get();

                if ($playlistsFromDb->isNotEmpty()) {
                    $this->activeTab = 'playlists';
                    $this->allPlaylists = $playlistsFromDb;
                    $this->playlists = $this->allPlaylists->take($this->playlistLimit);
                    return;
                }
            }
        }

        // If not found locally, use SoundCloud's API to resolve the URL
        $response = Http::get("{$this->soundcloudApiUrl}/resolve", [
            'url' => $this->searchQuery,
            'client_id' => $this->soundcloudClientId,
        ]);

        if ($response->successful()) {
            $resolvedData = $response->json();
            $this->processResolvedData($resolvedData);
        } else {
            $this->allTracks = collect();
            $this->tracks = collect();
            session()->flash('error', 'Could not resolve the SoundCloud link. Please check the URL.');
        }
    }

    // Processes the data returned from the SoundCloud API
    protected function processResolvedData($data)
    {
        switch ($data['kind']) {
            case 'track':
                $this->activeTab = 'tracks';
                if ($this->playListTrackShow && $this->selectedPlaylistId) {
                    $playlistTracks = Playlist::findOrFail($this->selectedPlaylistId)->tracks()->get();
                    // Check if the resolved track exists in the current playlist
                    $this->allPlaylistTracks = $playlistTracks->filter(function ($track) use ($data) {
                        return $track->urn === $data['urn'];
                    });
                    $this->tracks = $this->allPlaylistTracks->take($this->trackLimit);
                } else {
                    $this->allTracks = collect([$data]);
                    $this->tracks = $this->allTracks->take($this->trackLimit);
                }
                break;
            case 'user':
                $this->activeTab = 'tracks';
                $this->fetchUserTracks($data['id']);
                break;
            default:
                $this->allTracks = collect();
                $this->tracks = collect();
                session()->flash('error', 'The provided URL is not a track or playlist.');
                break;
        }
    }

    public function showPlaylistTracks($playlistId)
    {
        $this->selectedPlaylistId = $playlistId;
        $playlist = Playlist::with('tracks')->find($playlistId);
        if ($playlist) {
            $this->allTracks = $playlist->tracks;
            $this->tracks = $this->allTracks->take($this->trackLimit);
        } else {
            $this->tracks = collect();
        }
        $this->activeTab = 'tracks';
        $this->playListTrackShow = true;
    }
    protected function fetchUserTracks($userId)
    {
        $response = Http::get("{$this->soundcloudApiUrl}/users/{$userId}/tracks", [
            'client_id' => $this->soundcloudClientId,
            // The API returns all tracks by default, so we don't need a limit here unless we want to paginate.
        ]);

        if ($response->successful()) {
            $this->allTracks = collect($response->json());
            $this->tracks = $this->allTracks->take($this->trackLimit);
        } else {
            $this->allTracks = collect();
            $this->tracks = collect();
        }
    }


    public function openModal($userUrn)
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
            'playListTrackShow'
        ]);
        $this->selectedUserUrn = $userUrn;
        $this->showModal = true;
        $this->activeTab = 'tracks';
        $this->user = User::where('urn', $this->selectedUserUrn)->with('userInfo')->first();
        $this->user_urn = $this->user->urn;
        $this->allTracks = Track::where('user_urn', user()->urn)->get();
        $this->tracks = $this->allTracks->take($this->trackLimit);
        $this->allPlaylists = Playlist::where('user_urn', user()->urn)->get();
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

     public function setActiveTab($tab)
    {
        $this->reset(['selectedPlaylistId', 'selectedTrackId', 'searchQuery']);
        $this->activeTab = $tab;
        $this->searchSoundcloud();
    }

    public function openRepostsModal($trackId)
    {
        $this->reset(['track']);
        $this->selectedTrackId = $trackId;
        $trackData = Track::find($trackId);
        if ($trackData) {
            $this->track = $trackData;
        } else {
        }
        $this->showRepostsModal = true;
    }

    public function closeRepostModal()
    {
        $this->reset(['track', 'selectedTrackId', 'selectedPlaylistId', 'showRepostsModal']);
    }

    public function closePlaylistTracksModal()
    {
        $this->reset(['selectedPlaylistId', 'playlistTracks']);
        // $this->showPlaylistTracksModal = false;
    }

    public function createRepostsRequest()
    {
        try {
            // Validate required objects exist
            if (!$this->user) {
                throw new Exception('Target user not found');
            }

            $targetUrn = null;
            if ($this->track) {
                $targetUrn = $this->track->urn;
            } else {
                throw new Exception('Target content not found');
            }

            $amount = repostPrice($this->user);

            DB::transaction(function () use ($targetUrn, $amount) {
                // Create repost request
                $repostRequest = new RepostRequest();
                $repostRequest->requester_urn = user()->urn;
                $repostRequest->target_user_urn = $this->user->urn;
                $repostRequest->track_urn = $targetUrn;

                $repostRequest->credits_spent = $amount;
                $repostRequest->save();

                // Create credit transaction
                $creditTransaction = new CreditTransaction();
                $creditTransaction->receiver_urn = user()->urn;
                $creditTransaction->sender_urn = $this->user->urn;
                $creditTransaction->transaction_type = CreditTransaction::TYPE_SPEND;
                $creditTransaction->calculation_type = CreditTransaction::CALCULATION_TYPE_CREDIT;
                $creditTransaction->source_id = $repostRequest->id;
                $creditTransaction->source_type = RepostRequest::class;
                $creditTransaction->amount = 0;
                $creditTransaction->credits = $amount; // Assuming credits is the same as amount
                $creditTransaction->description = "Repost request for track by " . user()->name;
                $creditTransaction->metadata = [
                    'request_type' => 'track',
                    'target_urn' => $targetUrn,
                ];
                $creditTransaction->status = 'succeeded';
                $creditTransaction->save();
            });
            $this->closeRepostModal();
            $this->closePlaylistTracksModal();
            $this->closeModal();
            $this->loadData();
            $this->reset(['track', 'user']);
            session()->flash('success', 'Repost request sent successfully!');
        } catch (InvalidArgumentException $e) {
            session()->flash('error', $e->getMessage());
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to send repost request. Please try again.');
            // Log the actual error for debugging
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
    public function loadMorePlaylistTracks()
    {
        $this->playlistTrackLimit += 4;
        $this->playlistTracks = $this->allPlaylistTracks->take($this->playlistTrackLimit);
    }

    public function render()
    {
        return view('livewire.user.member-management.member');
    }
}
