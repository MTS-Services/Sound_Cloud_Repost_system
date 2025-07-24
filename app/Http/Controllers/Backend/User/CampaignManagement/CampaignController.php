<?php

namespace App\Http\Controllers\Backend\User\CampaignManagement;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\CreditTransaction;
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
        $response = Http::withHeaders([
            'Authorization' => 'OAuth ' . user()->token,
        ])->post("{$this->baseUrl}/reposts/tracks/{$campaign->music->urn}");
        if ($response->successful()) {
            DB::transaction(function () use ($campaign, $response) {

                $trackOwnerUrn = $campaign->music?->user?->urn ?? $campaign->user_urn;
                $trackOwnerName = $campaign->music?->user?->name;
                $reposterUrn = user()->urn;
                $creditsPerRepost = $campaign->credits_per_repost;

                // Create the Repost record
                $repost = Repost::create([
                    'campaign_id' => $campaign->id,
                    'reposter_urn' => $reposterUrn,
                    'track_owner_urn' => $trackOwnerUrn,
                    'soundcloud_repost_id' => null,
                    'is_verified' => Repost::IS_VERIFIED_NO,
                    'reposted_at' => now(),
                    'credits_earned' => $creditsPerRepost,
                    'net_credits' => $creditsPerRepost,
                ]);

                // Update the Campaign record
                $campaign->increment('completed_reposts');
                $campaign->increment('credits_spent', $creditsPerRepost);

                // Create the CreditTransaction record
                CreditTransaction::create([
                    'receiver_urn' => $reposterUrn,
                    'sender_urn' => $trackOwnerUrn,
                    'calculation_type' => CreditTransaction::CALCULATION_TYPE_DEBIT,
                    'source_id' => $campaign->id,
                    'source_type' => Campaign::class,
                    'transaction_type' => CreditTransaction::TYPE_EARN,
                    'amount' => 0,
                    'credits' => $creditsPerRepost,
                    'description' => "Repost of campaign '{$campaign->title}' by {$trackOwnerName}. " .
                        "Reposted by {$reposterUrn} with Repost ID: {$repost->id}.",
                    'metadata' => [
                        'repost_id' => $repost->id,
                    ]
                ]);
            });
            return redirect()->back()->with('success', 'Track reposted successfully.');
        } else {
            return redirect()->back()->with('error', 'Failed to repost track.');
        }
    }
}
