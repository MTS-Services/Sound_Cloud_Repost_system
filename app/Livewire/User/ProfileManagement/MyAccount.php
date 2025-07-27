<?php

namespace App\Livewire\User\ProfileManagement;

use App\Models\User;
use Livewire\Component;

class MyAccount extends Component
{
   public $user=null;

    public function mount(){
        $this->user = User::where('urn',user()->urn)->with('userInfo')->first();
    }
    public function render()
    {
        return view('backend.user.profile-management.my-account');
    }
}
