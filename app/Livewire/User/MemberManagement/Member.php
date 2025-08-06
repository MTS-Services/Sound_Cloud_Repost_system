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
use InvalidArgumentException;
use Livewire\Component;

class Member extends Component
{
    public $page_slug = 'members';

    public $search = '';
    public $genreFilter = '';
    public $costFilter = '';

    public $showModal = false;
    public $showRepostsModal = false;
    public $showPlaylistTracksModal = false;
    public $activeTab = 'tracks';
    public $selectedUserUrn = null;
    public $selectedPlaylistId = null;
    public $selectedTrackId = null;

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

    // For "Load more" tracks
    public $trackLimit = 4;
    public $allTracks = [];

    // For "Load more" playlists
    public $playlistLimit = 4;
    public $allPlaylists = [];

    protected $listeners = ['refreshData' => 'loadData'];

    protected UserService $userService;
    protected TrackService $trackService;
    protected PlaylistService $playlistService;

    public function boot(TrackService $trackService)
    {
        $this->trackService = $trackService;
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

    public function updatedSearch() { $this->loadData(); }
    public function updatedGenreFilter() { $this->loadData(); }
    public function updatedCostFilter() { $this->loadData(); }

    public function openModal($userUrn)
    {
        $this->reset([
            'showModal', 'user', 'selectedPlaylistId', 'selectedTrackId',
            'activeTab', 'tracks', 'playlists', 'trackLimit', 'playlistLimit'
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
            'showModal', 'user', 'selectedUserUrn',
            'selectedPlaylistId', 'selectedTrackId',
            'activeTab', 'tracks', 'playlists', 'trackLimit', 'playlistLimit'
        ]);
    }

    public function setActiveTab($tab)
    {
        $this->reset(['selectedPlaylistId', 'selectedTrackId']);
        $this->activeTab = $tab;

        if ($tab === 'tracks') {
            $this->tracks = $this->allTracks->take($this->trackLimit);
        }

        if ($tab === 'playlists') {
            $this->playlists = $this->allPlaylists->take($this->playlistLimit);
        }
    }

    public function openRepostsModal($trackId)
    {
        $this->reset(['track']);
        $this->selectedTrackId = $trackId;
        $this->track = Track::findOrFail($trackId);
        $this->showRepostsModal = true;
    }

    public function closeRepostModal()
    {
        $this->reset(['track', 'selectedTrackId', 'selectedPlaylistId', 'showRepostsModal']);
    }

    public function openPlaylistTracksModal($playlistId)
    {
        $this->showPlaylistTracksModal = true;
        $this->selectedPlaylistId = $playlistId;
        $this->selectedTrackId = null;
        $this->playlistTracks = Playlist::findOrFail($this->selectedPlaylistId)->tracks()->get();
    }

    public function closePlaylistTracksModal()
    {
        $this->reset(['selectedPlaylistId', 'playlistTracks']);
        $this->showPlaylistTracksModal = false;
    }

    public function createRepostsRequest()
    {
        try {
            if (!$this->user) throw new Exception('Target user not found');

            $targetUrn = $this->track?->urn;
            if (!$targetUrn) throw new Exception('Target content not found');

            $amount = repostPrice($this->user);

            DB::transaction(function () use ($targetUrn, $amount) {
                $repostRequest = new RepostRequest();
                $repostRequest->requester_urn = user()->urn;
                $repostRequest->target_user_urn = $this->user->urn;
                $repostRequest->track_urn = $targetUrn;
                $repostRequest->credits_spent = $amount;
                $repostRequest->save();

                $creditTransaction = new CreditTransaction();
                $creditTransaction->receiver_urn = user()->urn;
                $creditTransaction->sender_urn = $this->user->urn;
                $creditTransaction->transaction_type = CreditTransaction::TYPE_SPEND;
                $creditTransaction->calculation_type = CreditTransaction::CALCULATION_TYPE_CREDIT;
                $creditTransaction->source_id = $repostRequest->id;
                $creditTransaction->source_type = RepostRequest::class;
                $creditTransaction->amount = 0;
                $creditTransaction->credits = $amount;
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
        return view('livewire.user.member-management.member');
    }
}
