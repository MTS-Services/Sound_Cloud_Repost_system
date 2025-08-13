<?php

namespace App\Services\User\CampaignManagement;

use App\Models\Campaign;
use App\Models\CreditTransaction;
use App\Models\Repost;
use Illuminate\Support\Facades\DB;
use Throwable;

class CampaignService
{
    public function getCampaigns($orderBy = 'sort_order', $order = 'asc')
    {
        return Campaign::orderBy($orderBy, $order)->latest();
    }
    public function getCampaign(string $encryptedId)
    {
        return Campaign::findOrFail(decrypt($encryptedId));
    }

    public function syncReposts($campaign, $reposter, $soundcloudRepostId)
    {
        try {

            DB::transaction(function () use ($campaign, $reposter, $soundcloudRepostId) {

                $trackOwnerUrn = $campaign->music->user?->urn ?? $campaign->user_urn;
                $trackOwnerName = $campaign->music->user?->name;

                // Create the Repost record
                $repost = Repost::create([
                    'reposter_urn' => $reposter->urn,
                    'track_owner_urn' => $trackOwnerUrn,
                    'campaign_id' => $campaign->id,
                    'soundcloud_repost_id' => $soundcloudRepostId,
                    'reposted_at' => now(),
                    'credits_earned' => repostPrice($reposter),
                ]);

                // Update the Campaign record using atomic increments
                $campaign->increment('completed_reposts');
                $campaign->increment('credits_spent', (float) repostPrice($reposter));

                if ($campaign->budget_credits == $campaign->credits_spent) {
                    $campaign->update(['status' => Campaign::STATUS_COMPLETED]);
                }


                // Create the CreditTransaction record
                CreditTransaction::create([
                    'receiver_urn' => $reposter->urn,
                    'sender_urn' => $trackOwnerUrn,
                    'calculation_type' => CreditTransaction::CALCULATION_TYPE_DEBIT,
                    'source_id' => $campaign->id,
                    'source_type' => Campaign::class,
                    'status' => CreditTransaction::STATUS_SUCCEEDED,
                    'transaction_type' => CreditTransaction::TYPE_EARN,
                    'amount' => 0,
                    'credits' => (float) repostPrice($reposter),
                    'description' => "Repost of campaign '{$campaign->title}' by {$trackOwnerName}. " .
                        "Reposted by {$reposter->name} with Repost ID: {$repost->id}.",
                    'metadata' => [
                        'repost_id' => $repost->id,
                        'campaign_id' => $campaign->id,
                        'soundcloud_repost_id' => $soundcloudRepostId,
                    ]
                ]);
            });
            return true;
        } catch (Throwable $e) {
            throw $e;
        }
    }
}
