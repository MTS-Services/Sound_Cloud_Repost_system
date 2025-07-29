<?php

namespace App\Livewire\User\ProfileManagement;

use App\Models\Playlist;
use App\Models\Repost;
use App\Services\Admin\CreditManagement\CreditTransactionService;
use App\Models\User;
use App\Services\Admin\UserManagement\UserService;
use Livewire\Component;

class MyAccount extends Component
{
    public $user;
    public $tracks;
    public $playlists;
    public $reposts;
    public $transactions;
    public $activeTab = 'insights';

    protected $creditTransactionService;
    protected $userService;


    public function setActiveTab(string $tab): void
    {
        $this->activeTab = $tab;
    }

    public function getTracks(): void
    {
        $this->tracks = $this->creditTransactionService->getUserTracks();
    }

    public function getPlaylists(): void
    {
        $this->playlists = Playlist::where('user_urn', user()->urn)->get();
    }
   public function getRecentReposts(): void
{
    $this->reposts = Repost::where('track_owner_urn', user()->urn)->with(['campaign', 'request'])->orderByDesc('reposted_at')->get();
}
    public function getTransactions(): void
    {
        $this->transactions = $this->creditTransactionService->getUserTransactions();
    }

    public function getMyUser()
    {
        $this->user = $this->userService->getMyAccountUser();
    }

    public function mount(CreditTransactionService $creditTransactionService, UserService $userService): void
    {
        $this->userService = $userService;
        $this->creditTransactionService = $creditTransactionService;
        $this->getPlaylists();
        $this->getMyUser();
        $this->getTracks();
        $this->getRecentReposts();
        $this->getTransactions();
        // $this->user = User::where('urn', user()->urn)->with('userInfo')->first();

    }

    public function render()
    {
      
        return view('backend.user.profile-management.my-account');
    }
}
