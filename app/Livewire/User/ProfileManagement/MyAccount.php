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
    public $showEditProfileModal = false;

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
        $this->reposts = Repost::with([
            'campaign.music',
            'request.track',
        ])
            ->where('reposter_urn', user()->urn)
            ->orderByDesc('reposted_at')
            ->take(10)
            ->get()
            ->map(function ($repost) {
                $source = $repost->campaign?->music ?? $repost->request?->track;
                $repost->source = $source;
                $repost->source_id = $repost->campaign?->id ?? $repost->request?->id;
                $repost->source_type = $repost->campaign ? '📢 From Campaign' : ($repost->request ? '🤝 From Request' : '');
                return $repost;
            });
    }
    public function getTransactions(): void
    {
        $this->transactions = $this->creditTransactionService->getUserTransactions()->where('status', 'succeeded')
            ->sortByDesc('created_at')
            ->take(10);
    }

    public function getMyUser()
    {
        $this->user = $this->userService->getMyAccountUser();
    }

    public function profileUpdated($propertyName)
    {
        $this->showEditProfileModal = true;
        
    }

    public function loadAll()
    {
        $this->getPlaylists();
        $this->getMyUser();
        $this->getTracks();
        $this->getRecentReposts();
        $this->getTransactions();
    }

    public function mount(CreditTransactionService $creditTransactionService, UserService $userService): void
    {
        $this->userService = $userService;
        $this->creditTransactionService = $creditTransactionService;
        $this->loadAll();
    }

    public function render()
    {

        return view('backend.user.profile-management.my-account');
    }
}
