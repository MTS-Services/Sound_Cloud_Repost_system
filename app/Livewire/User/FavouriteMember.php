<?php

namespace App\Livewire\User;

use App\Models\StarredUser;
use Livewire\Attributes\Url;

use Livewire\Component;

class FavouriteMember extends Component
{
    public $favouriteUsers = [];
    #[Url]
    public $starred = '';

    public function mount()
    {
        if ($this->starred === 'favourite') {
            $this->loadYourFavouriteMembers();
        }elseif ($this->starred === 'favourited') {
            $this->loadFavouriteMembers();
        }
    }
    public function loadYourFavouriteMembers()
    {
        $this->favouriteUsers = StarredUser::with('following.genres')->where('follower_urn', user()->urn)->get();
    }
    public function loadFavouriteMembers()
    {
        $this->favouriteUsers = StarredUser::with('follower.genres')->where('starred_user_urn', user()->urn)->get();
    }
    public function render()
    {
        return view('livewire.user.favourite-member');
    }
}
