<?php

namespace App\Http\Controllers\Backend\User;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Track;
use App\Services\Admin\TrackService;
use Illuminate\Http\Request;

class RepostFeedController extends Controller
{
    protected TrackService $trackService;

    public function __construct(TrackService $trackService)
    {
        $this->trackService = $trackService;
    }
    public function repostFeed()
    {
        $data['tracks'] = Track::all();
        return view('backend.user.repost-feed', $data);
    }
}
