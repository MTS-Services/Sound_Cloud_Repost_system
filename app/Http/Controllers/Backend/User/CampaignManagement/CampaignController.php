<?php

namespace App\Http\Controllers\Backend\User\CampaignManagement;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Repost;
use App\Models\Track;
use App\Services\Admin\TrackService;
use Illuminate\Support\Facades\DB;
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
        $campaign->load(['music.user']);
        // dd($campaign);
        // $track = Track::findOrFail(decrypt($id));
        // $track->load('campaigns');
        // dd($track);
        $response = Http::withHeaders([
            'Authorization' => 'OAuth ' . user()->token,
        ])->post("{$this->baseUrl}/reposts/tracks/{$campaign->music->urn}");
        if ($response->successful()) {
            DB::transaction(function () use ($campaign, $response) {
                Repost::create([
                    'campaign_id' => $campaign->id,
                    'reposter_urn' => user()->urn,
                    'track_owner_urn' => $campaign->music?->user?->urn ?? $campaign->user_urn,
                    'soundcloud_repost_id' => $response->json()->id,
                    'is_verified' => Repost::IS_VERIFIED_NO,
                    'reposted_at' => now(),
                    'credits_earned' => $campaign->credits_per_repost,
                    'net_credits' => $campaign->credits_per_repost,
                ]);
            });
            return redirect()->back()->with('success', 'Track reposted successfully.');
        } else {            
            return redirect()->back()->with('error', 'Failed to repost track.');
        }
    }
}
