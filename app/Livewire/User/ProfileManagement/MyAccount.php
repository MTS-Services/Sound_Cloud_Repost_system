<?php

namespace App\Livewire\User\ProfileManagement;

use App\Models\Credit;
use App\Models\CreditTransaction;
use App\Models\Track;
use App\Models\User;
use Livewire\Component;

class MyAccount extends Component
{
    public $user;
    public $tracks;
    public $transactions;

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

    public function getTransactions()
    {
       try {
            $this->transactions = CreditTransaction::all();
        } catch (\Exception $e) {
            $this->transactions = collect();
            session()->flash('error', 'Failed to load transactions: ' . $e->getMessage());
        }
    }
    public function mount()
    {
        $this->getTracks();
        $this->getTransactions();
        $this->user = User::where('urn', user()->urn)->with('userInfo')->first();
    }
    public function render()
    {
        return view('backend.user.profile-management.my-account');
    }
}
