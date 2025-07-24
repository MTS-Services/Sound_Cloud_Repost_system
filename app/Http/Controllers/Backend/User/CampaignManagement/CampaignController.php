<?php

namespace App\Http\Controllers\Backend\User\CampaignManagement;

use App\Http\Controllers\Controller;
use App\Models\Track;
use App\Services\Admin\TrackService;
use Illuminate\Support\Facades\Http;

class CampaignController extends Controller
{
    protected TrackService $trackService;


     protected string $baseUrl = 'https://api.soundcloud.com';

    public function __construct(TrackService $trackService)
    {
        $this->trackService = $trackService;
    }
    public function campaingFeed()
    {
        $data['tracks'] = Track::with(['user', 'campaign'])
            ->whereHas('campaign')
            ->get();
        return view('backend.user.campaingt-feed', $data);
    }

    public function campaing(string $id)
    {

        $track = Track::findOrFail(decrypt($id));
        dd($track);
        $response = Http::withHeaders([
            'Authorization' => 'OAuth ' . user()->token,
        ])->post("{$this->baseUrl}/reposts/tracks/{$track->urn}");
        if ($response->successful()) {
            dd('success', $response->json());
            return redirect()->back()->with('success', 'Track reposted successfully.');
        } else {
            dd( 'error', $response->json());
            return redirect()->back()->with('error', 'Failed to repost track.');
        }
    }
}
