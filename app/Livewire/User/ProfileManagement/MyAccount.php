<?php

namespace App\Livewire\User\ProfileManagement;

use App\Models\Track;
use App\Models\User;
use Livewire\Component;

class MyAccount extends Component
{
    public $user;
    public $tracks;

    public $activeTab = 'insights';  // Set default active tab

    public function setActiveTab($tab)
    {

        $this->activeTab = $tab;
    }


    public function getTracks()
    {

        try {
            $this->tracks = Track::where('user_urn', user()->urn)->get();
        } catch (\Exception $e) {
            $this->tracks = collect();
            session()->flash('error', 'Failed to load tracks: ' . $e->getMessage());
        }
    }


    public function mount()
    {
        $this->getTracks();
        $this->user = User::where('urn', user()->urn)->with('userInfo')->first();
    }
    public function render()
    {
        return view('backend.user.profile-management.my-account');
    }
}
