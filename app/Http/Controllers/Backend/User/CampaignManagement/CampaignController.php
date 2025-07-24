<?php

namespace App\Http\Controllers\Backend\User\CampaignManagement;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\CreditTransaction;
use App\Models\Playlist;
use App\Models\Repost;
use App\Models\Track;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class CampaignController extends Controller
{

    protected string $baseUrl = 'https://api.soundcloud.com';

    public function __construct()
    {
        //
    }

    public function campaignFeed()
    {
        $data['campaigns'] = Campaign::with(['music'])->get();
        return view('backend.user.campaign-feed', $data);
    }


    public function repost(string $id)
    {
        try {            
            $campaignId = decrypt($id);
            $currentUserUrn = user()->urn;

            // Check if the current user owns the campaign
            if (Campaign::where('id', $campaignId)->where('user_urn', $currentUserUrn)->exists()) {
                return redirect()->back()->with('error', 'You cannot repost your own campaign.');
            }

            // Check if the user has already reposted this specific campaign
            if (Repost::where('reposter_urn', $currentUserUrn)
                ->where('campaign_id', $campaignId)
                ->exists()
            ) {
                return redirect()->back()->with('error', 'You have already reposted this campaign.');
            }

            // Find the campaign and eager load its music and the music's user
            $campaign = Campaign::with('music.user')->findOrFail($campaignId);

            // Ensure music is associated with the campaign
            if (!$campaign->music) {
                return redirect()->back()->with('error', 'Track or Playlist not found for this campaign.');
            }

            $soundcloudRepostId = null;

            // Prepare HTTP client with authorization header
            $httpClient = Http::withHeaders([
                'Authorization' => 'OAuth ' . user()->token,
            ]);

            // Determine the SoundCloud API endpoint based on music type
            if ($campaign->music_type == Track::class) {
                $response = $httpClient->post("{$this->baseUrl}/reposts/tracks/{$campaign->music->urn}");
            } elseif ($campaign->music_type == Playlist::class) {
                $response = $httpClient->post("{$this->baseUrl}/reposts/playlists/{$campaign->music->urn}");
            } else {
                return redirect()->back()->with('error', 'Invalid music type specified for the campaign.');
            }

            
            if ($response->successful()) {
                // If SoundCloud returns a repost ID, capture it (example, adjust based on actual SoundCloud API response)
                // $soundcloudRepostId = $response->json('id');

                DB::transaction(function () use ($campaign, $currentUserUrn, $soundcloudRepostId) {
                    
                    $trackOwnerUrn = $campaign->music->user?->urn ?? $campaign->user_urn;
                    $trackOwnerName = $campaign->music->user?->name; 
                    $creditsPerRepost = $campaign->credits_per_repost;

                    // Create the Repost record
                    $repost = Repost::create([
                        'reposter_urn' => $currentUserUrn,
                        'track_owner_urn' => $trackOwnerUrn,
                        'campaign_id' => $campaign->id,
                        'soundcloud_repost_id' => $soundcloudRepostId,
                        'reposted_at' => now(),
                        'credits_earned' => $creditsPerRepost,
                    ]);

                    // Update the Campaign record using atomic increments
                    $campaign->increment('completed_reposts');
                    $campaign->increment('credits_spent', $creditsPerRepost);

                    // Create the CreditTransaction record
                    CreditTransaction::create([
                        'receiver_urn' => $currentUserUrn,
                        'sender_urn' => $trackOwnerUrn,
                        'calculation_type' => CreditTransaction::CALCULATION_TYPE_DEBIT,
                        'source_id' => $campaign->id,
                        'source_type' => Campaign::class,
                        'transaction_type' => CreditTransaction::TYPE_EARN,
                        'amount' => 0,
                        'credits' => $creditsPerRepost,
                        'description' => "Repost of campaign '{$campaign->title}' by {$trackOwnerName}. " .
                            "Reposted by {$currentUserUrn} with Repost ID: {$repost->id}.",
                        'metadata' => [
                            'repost_id' => $repost->id,
                            'campaign_id' => $campaign->id,
                            'soundcloud_repost_id' => $soundcloudRepostId,
                        ]
                    ]);
                });

                return redirect()->back()->with('success', 'Campaign music reposted successfully.');
            } else {
                // Log the error response from SoundCloud for debugging
                Log::error("SoundCloud Repost Failed: " . $response->body(), [
                    'campaign_id' => $campaignId,
                    'user_urn' => $currentUserUrn,
                    'status' => $response->status(),
                ]);
                return redirect()->back()->with('error', 'Failed to repost campaign music to SoundCloud. Please try again.');
            }
        } catch (Throwable $e) {           
            Log::error("Error in repost method: " . $e->getMessage(), [
                'exception' => $e,
                'campaign_id_input' => $id,
                'user_urn' => user()->urn ?? 'N/A',
            ]);
            return redirect()->back()->with('error', 'An unexpected error occurred. Please try again later.');
        }
    }
}
