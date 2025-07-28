<?php

namespace App\Livewire\User\ProfileManagement;

use App\Services\Admin\CreditManagement\CreditTransactionService;
use App\Models\User;
use App\Services\Admin\UserManagement\UserService;
use Livewire\Component;

class MyAccount extends Component
{
    public $user;
    public $tracks;
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
        $this->getMyUser();
        $this->getTracks();
        $this->getTransactions();
        // $this->user = User::where('urn', user()->urn)->with('userInfo')->first();

    }

    public function render()
    {
        return view('backend.user.profile-management.my-account');
    }
}
