<?php

namespace App\Livewire\User\MemberManagement;

use App\Models\Playlist;
use App\Models\RepostRequest;
use App\Models\Track;
use App\Models\User;
use App\Models\UserInformation;
use Livewire\Component;

class Member extends Component
{
    // Remove the typed property declaration - use dependency injection instead

    // page slug
    public $page_slug = 'members';
    // Properties for filtering and search
    public $search = '';
    public $genreFilter = '';
    public $costFilter = '';

    // Modal properties
    public $showModal = false;
    public $showRepostsModal = false;
    public $activeTab = 'tracks';
    public $selectedUserId = null;
    public $selectedPlaylistId = null;
    public $selectedTrackId = null;

    // Data properties
    public $users;
    public $user;
    public $user_urn;
    public $userinfo;
    public $playlists;
    public $playlist;
    public $tracks;
    public $track;
    public $credits_spent;

    protected $listeners = ['refreshData' => 'loadData'];

    public function mount()
    {
        $this->loadData();
        $this->page_slug = 'members';
    }

    public function loadData()
    {
        // Load users (excluding current user)
        $query = User::where('id', '!=', user()->id);

        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        $this->users = $query->get();

        // Load current user info
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

    public function openRepostsModal($trackId = null, $playlistId = null)
    {
        $this->reset(['track', 'playlist']);
        $this->selectedTrackId = $trackId;
      
        if ($trackId) {
            $this->track = Track::findOrFail($trackId);
        } elseif ($playlistId) {
            $this->playlist = Playlist::findOrFail($playlistId);
        }
        $this->showRepostsModal = !$this->showRepostsModal;
    }

    public function closeRepostModal()
    {
        $this->reset(['track', 'playlist', 'selectedTrackId', 'selectedPlaylistId','showRepostsModal']);
    }

    public function openModal($userId)
    {
        $this->selectedUserId = $userId;
        $this->showModal = true;
        $this->activeTab = 'tracks';
        $this->selectedPlaylistId = null;
        $this->selectedTrackId = null;
        $this->user = User::findOrFail($userId);
        $this->user_urn = $this->user->urn;
        $this->tracks = Track::where('user_urn', user()->urn)->get();
    }

    public function closeModal()
    {
        $this->reset(['showModal', 'selectedUserId', 'selectedPlaylistId', 'selectedTrackId','activeTab', 'tracks','playlists']);
    }

    public function setActiveTab($tab)
    {
        $this->reset(['selectedPlaylistId', 'selectedTrackId']);
        $this->activeTab = $tab;

        if ($tab === 'playlists') {
            $this->playlists = Playlist::where('user_urn', user()->urn)->get();
        }
    }

    public function confirmRepost()
    {
        try {
            if ($this->selectedPlaylistId) {
                $playlist = Playlist::findOrFail($this->selectedPlaylistId);

                // Check authorization
                if ($playlist->user_urn !== user()->urn) {
                    session()->flash('error', 'Unauthorized action.');
                    return;
                }

                $playlist->update(['confirmed' => true]);

                session()->flash('success', 'Repost confirmed successfully!');
            } elseif ($this->selectedTrackId) {
                $track = Track::findOrFail($this->selectedTrackId);

                // Check authorization
                if ($track->user_urn !== user()->urn) {
                    session()->flash('error', 'Unauthorized action.');
                    return;
                }

                // Add your track repost logic here
                // For example: $track->update(['repost_confirmed' => true]);

                session()->flash('success', 'Track repost confirmed successfully!');
            } else {
                session()->flash('error', 'Please select a track or playlist first.');
                return;
            }

            $this->closeRepostModal();
            $this->closeModal();
            $this->loadData();
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to confirm repost. Please try again.');
        }
    }
    public function createRepostsRequest($request){
        try{
            $repostRequest = new RepostRequest();
            $repostRequest->requester_urn = user()->urn;
            $repostRequest->target_user_urn = $this->user->urn;
            if($request == 'tracks'){
                $repostRequest->track_urn = $this->track->urn;
            }elseif($request == 'playlists'){
                $repostRequest->track_urn = $this->playlist->urn;
            }
            $repostRequest->credits_spent = repostPrice($this->user);
            $repostRequest->save();
            $this->closeRepostModal();
            $this->closeModal();
            $this->loadData();
            session()->flash('success', 'Repost request sent successfully!');
        }
        catch(\Exception $e){
            session()->flash('error', 'Failed to send repost request. Please try again.');
        }
    }

    public function render()
    {
        return view('livewire.user.member-management.member');
    }
}
