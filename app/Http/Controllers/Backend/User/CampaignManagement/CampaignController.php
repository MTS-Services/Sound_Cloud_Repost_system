<?php

namespace App\Http\Controllers\Backend\User\CampaignManagement;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
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
    public function campaignFeed()
    {
        $data['campaigns'] = Campaign::with(['music'])->get();
        return view('backend.user.campaign-feed', $data);
    }

    public function repost(string $id)
    {
        $campaign = Campaign::findOrFail(decrypt($id));
        $campaign->load('music');
        dd($campaign);
        $track = Track::findOrFail(decrypt($id));
        $track->load('campaigns');
        dd($track);
        $response = Http::withHeaders([
            'Authorization' => 'OAuth ' . user()->token,
        ])->post("{$this->baseUrl}/reposts/tracks/{$track->urn}");
        if ($response->successful()) {
            dd('success', $response->json());
            return redirect()->back()->with('success', 'Track reposted successfully.');
        } else {
            dd('error', $response->json());
            return redirect()->back()->with('error', 'Failed to repost track.');
        }
    }
}
