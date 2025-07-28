<?php

namespace App\Livewire\User\MemberManagement;

use App\Models\Playlist;
use App\Models\Track;
use App\Models\User;
use App\Models\UserInformation;
use Livewire\Component;

class Member extends Component
{
    // page slug
    public $page_slug = 'members';
    // Properties for filtering and search
    public $search = '';
    public $genreFilter = '';
    public $costFilter = '';
    
    // Modal properties
    public $showModal = false;
    public $activeTab = 'tracks';
    public $selectedUserId = null;
    public $selectedPlaylistId = null;
    public $selectedTrackId = null;
    
    // Data properties
    public $users;
    public $userinfo;
    public $playlists;
    public $tracks;

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
        
        // Load current user's playlists and tracks
        $this->playlists = Playlist::where('user_urn', user()->urn ?? '')->get();
        $this->tracks = Track::where('user_urn', user()->urn ?? '')->get();
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

    public function openModal($userId)
    {
        $this->selectedUserId = $userId;
        $this->showModal = true;
        $this->activeTab = 'tracks';
        $this->selectedPlaylistId = null;
        $this->selectedTrackId = null;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->selectedUserId = null;
        $this->selectedPlaylistId = null;
        $this->selectedTrackId = null;
        $this->activeTab = 'tracks';
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
        $this->selectedPlaylistId = null;
        $this->selectedTrackId = null;
    }

    public function selectTrack($trackId)
    {
        $this->selectedTrackId = $trackId;
        $this->selectedPlaylistId = null;
    }

    public function selectPlaylist($playlistId)
    {
        $this->selectedPlaylistId = $playlistId;
        $this->selectedTrackId = null;
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

            $this->closeModal();
            $this->loadData();
            
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to confirm repost. Please try again.');
        }
    }

    public function render()
    {
        // return view('backend.user.member-management.members');
        return view('livewire.user.member-management.member');
    }
}