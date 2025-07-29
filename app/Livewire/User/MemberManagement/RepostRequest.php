<?php

namespace App\Livewire\User\MemberManagement;

use App\Models\RepostRequest as ModelsRepostRequest;
use Livewire\Component;

class RepostRequest extends Component
{
    public $repostRequests;
    public $track;
    public function mount()
    {
        $this->dataLoad();
    }
    
    public function dataLoad()
    {
        $this->repostRequests = ModelsRepostRequest::where('requester_urn', user()->urn)->with('track','requester', 'campaign')->get();
    }
    public function render()
    {
        return view('livewire.user.member-management.repost-request');
    }
}