<?php

namespace App\Http\Controllers\Backend\User\Mamber;

use App\Http\Controllers\Controller;
use App\Models\Credit;
use App\Models\Playlist;
use App\Models\Track;
use App\Models\User;
use App\Models\User_Follower;
use App\Models\UserFollowers;
use App\Models\UserInformation;
use App\Services\Admin\UserManagement\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MamberController extends Controller
{
    
    public function index()
    {
        $data['users'] = User::where('id', '!=', user()->id)->get();
        // $data['credets'] = Credit::where('id', Auth::id())->get();
        $data['userinfo'] = UserInformation::where('id', user()->id)->get();
        $data['playlists'] = Playlist::where('user_urn', user()->urn)->get();
        $data['tracks'] = Track::where('user_urn', user()->urn)->get();
        return view('backend.user.mamber.index', $data);
     }

  public function confirmRepost($id)
{
    $playlist = Playlist::findOrFail($id);

    $playlist->update(['confirmed' => true]);

    return back()->with('success', 'Repost confirmed!');
}

    public function request()
    {
        return view('backend.user.mamber.request');
    }
}
