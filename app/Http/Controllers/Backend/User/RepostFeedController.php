<?php

namespace App\Http\Controllers\Backend\User;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Track;
use App\Services\Admin\TrackService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class RepostFeedController extends Controller
{
    protected TrackService $trackService;

    public function __construct(TrackService $trackService)
    {
        $this->trackService = $trackService;
    }
    public function repostFeed()
    {
        $data['tracks'] = Track::with(['user', 'campaign'])
            ->whereHas('campaign')
            ->get();
        return view('backend.user.repost-feed', $data);
    }

    public function repost(Track $track)
    {
        $data['track'] = $track;
        dd($data);
        Http::post('https://api.soundcloud.com/reposts/tracks/');
    }
}
