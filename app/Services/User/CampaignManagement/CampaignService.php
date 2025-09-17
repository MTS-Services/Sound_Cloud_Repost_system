<?php

namespace App\Services\User\CampaignManagement;

use App\Events\UserNotificationSent;
use App\Jobs\NotificationMailSent;
use App\Models\Campaign;
use App\Models\CreditTransaction;
use App\Models\CustomNotification;
use App\Models\Repost;
use App\Models\UserAnalytics;
use App\Services\User\AnalyticsService;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class CampaignService
{
    protected AnalyticsService $analyticsService;
    public function __construct(AnalyticsService $analyticsService)
    {
        $this->analyticsService = $analyticsService;
    }

    public function getCampaigns($orderBy = 'created_at', $order = 'desc')
    {
        return Campaign::orderBy($orderBy, $order)->latest();
    }
    public function getCampaign(string $encryptedId)
    {
        return Campaign::findOrFail(decrypt($encryptedId));
    }

    public function syncReposts($campaign, $reposter, $soundcloudRepostId, $likeCommentAbleData = [])
    {
        try {

            DB::transaction(function () use ($campaign, $reposter, $soundcloudRepostId, $likeCommentAbleData) {

                $trackOwnerUrn = $campaign->music->user?->urn ?? $campaign->user_urn;
                $trackOwnerName = $campaign->music->user?->name ?? $campaign->user?->name;
                $totalCredits = repostPrice() + ($likeCommentAbleData['comment'] ? 2 : 0) + ($likeCommentAbleData['likeable'] ? 2 : 0);

                // Create the Repost record
                $repost = Repost::updateOrCreate(
                    [
                        'reposter_urn' => $reposter->urn,
                        'track_owner_urn' => $trackOwnerUrn,
                        'campaign_id' => $campaign->id,
                        'soundcloud_repost_id' => $soundcloudRepostId,
                    ],
                    [
                        'reposted_at' => now(),
                        'credits_earned' => $totalCredits,
                    ]
                );

                // Update the Campaign record using atomic increments
                $campaign->increment('completed_reposts');
                $campaign->increment('credits_spent', (float) $totalCredits);
                $repostEmailPermission = hasEmailSentPermission('em_repost_accepted', $campaign->user->urn);
                if ($repostEmailPermission && ($campaign->budget_credits <= $campaign->credits_spent)) {
                    $datas = [
                        [
                            'email' => $campaign->user->email,
                            'subject' => 'Repost Budget Reached',
                            'title' => 'Dear ' . $campaign->user->name,
                            'body' => 'Your repost budget has been reached.',
                        ],
                    ];
                    NotificationMailSent::dispatch($datas);
                }

                if ($repost != null) {
                    $response = $this->analyticsService->recordAnalytics($campaign->music, $campaign, UserAnalytics::TYPE_REPOST, $campaign->target_genre);
                }

                if ($likeCommentAbleData['comment']) {
                    Log::info("likeCommentAbleData", $likeCommentAbleData);
                    $response = $this->analyticsService->recordAnalytics($campaign->music, $campaign, UserAnalytics::TYPE_COMMENT, $campaign->target_genre);
                    if ($response != false || $response != null) {
                        $campaign->increment('comment_count');
                        // $repost->increment('comment_count');
                    }
                }
                if ($likeCommentAbleData['likeable']) {
                    $response = $this->analyticsService->recordAnalytics($campaign->music, $campaign, UserAnalytics::TYPE_LIKE, $campaign->target_genre);
                    if ($response != false || $response != null) {
                        $campaign->increment('like_count');
                        $repost->increment('like_count');
                    }
                }
                if ($likeCommentAbleData['follow']) {
                    $response = $this->analyticsService->recordAnalytics($campaign->music, $campaign, UserAnalytics::TYPE_FOLLOW, $campaign->target_genre);
                    if ($response != false || $response != null) {
                        $campaign->increment('followowers_count');
                        $repost->increment('followowers_count');
                    }
                }
                if ($campaign->budget_credits == $campaign->credits_spent) {
                    $campaign->update(['status' => Campaign::STATUS_COMPLETED]);
                }

                // Create the CreditTransaction record
                $transaction = CreditTransaction::create([
                    'receiver_urn' => $reposter->urn,
                    'sender_urn' => $trackOwnerUrn,
                    'calculation_type' => CreditTransaction::CALCULATION_TYPE_DEBIT,
                    'source_id' => $campaign->id,
                    'source_type' => Campaign::class,
                    'status' => CreditTransaction::STATUS_SUCCEEDED,
                    'transaction_type' => CreditTransaction::TYPE_EARN,
                    'amount' => 0,
                    'credits' => (float) $totalCredits,
                    'description' => "Repost of campaign '{$campaign->title}' by {$trackOwnerName}. " .
                        "Reposted by {$reposter->name} with Repost ID: {$repost->id}.",
                    'metadata' => [
                        'repost_id' => $repost->id,
                        'campaign_id' => $campaign->id,
                        'soundcloud_repost_id' => $soundcloudRepostId,
                    ]
                ]);
                $reposterNotification = CustomNotification::create([
                    'receiver_id' => $reposter->id,
                    'receiver_type' => get_class($reposter),
                    'type' => CustomNotification::TYPE_USER,
                    'url' => route('user.my-account') . '?tab=reposts',
                    'message_data' => [
                        'title' => "Repost successful",
                        'message' => "You've been reposted on a campaign",
                        'description' => "You've been reposted on a campaign by {$trackOwnerName}.",
                        'icon' => 'music',
                        'additional_data' => [
                            'Track Title' => $campaign->music->title,
                            'Track Artist' => $trackOwnerName,
                            'Earned Credits' => (float) repostPrice($reposter),
                        ]
                    ]
                ]);

                $ownerNotificaion = CustomNotification::create([
                    'receiver_id' => $campaign?->user?->id,
                    'receiver_type' => get_class($campaign?->user),
                    'type' => CustomNotification::TYPE_USER,
                    'message_data' => [
                        'title' => "Repost successful",
                        'message' => "Your campaign has been reposted",
                        'description' => "Your campaign has been reposted by {$reposter->name}.",
                        'icon' => 'music',
                        'additional_data' => [
                            'Track Title' => $campaign->music->title,
                            'Track Artist' => $trackOwnerName,
                            'Spent Credits' => (float) repostPrice($reposter),
                        ]
                    ]
                ]);

                broadcast(new UserNotificationSent($reposterNotification));
                broadcast(new UserNotificationSent($ownerNotificaion));
            });
            return true;
        } catch (Throwable $e) {
            throw $e;
        }
    }

    public function likeCampaign($campaign, $reposter = null)
    {
        try {
            DB::transaction(function () use ($campaign, $reposter) {

                if ($reposter == null) {
                    $reposter = user();
                }
                $trackOwnerName = $campaign->music->user?->name;

                $response = $this->analyticsService->recordAnalytics($campaign->music, $campaign, UserAnalytics::TYPE_LIKE, $campaign->target_genre);
                if ($response != false || $response != null) {
                    $campaign->increment('like_count');
                }

                $ownerNotificaion = CustomNotification::create([
                    'receiver_id' => $campaign?->user?->id,
                    'receiver_type' => get_class($campaign?->user),
                    'type' => CustomNotification::TYPE_USER,
                    'message_data' => [
                        'title' => "New Like on your campaign",
                        'message' => "Your campaign has been liked",
                        'description' => "Your campaign has been liked by {$reposter->name}.",
                        'icon' => 'music',
                        'additional_data' => [
                            'Track Title' => $campaign->music->title,
                            'Track Artist' => $trackOwnerName,
                        ]
                    ]
                ]);

                broadcast(new UserNotificationSent($ownerNotificaion));
            });
            return true;
        } catch (Throwable $e) {
            throw $e;
        }
    }
    public function repostTrack($campaign, $soundcloudRepostId, $reposter = null)
    {
        try {
            DB::transaction(function () use ($campaign, $soundcloudRepostId, $reposter) {


                if ($reposter == null) {
                    $reposter = user();
                }
                $trackOwnerUrn = $campaign->music->user?->urn ?? $campaign->user_urn;
                $trackOwnerName = $campaign->music->user?->name ?? $campaign->user?->name;

                $repost = Repost::create([
                    'reposter_urn' => $reposter->urn,
                    'track_owner_urn' => $trackOwnerUrn,
                    'campaign_id' => $campaign->id,
                    'soundcloud_repost_id' => $soundcloudRepostId,
                    'reposted_at' => now(),
                    'credits_earned' => 0,
                ]);

                $response = $this->analyticsService->recordAnalytics($campaign->music, $campaign, UserAnalytics::TYPE_REPOST, $campaign->target_genre);
                if ($response != false || $response != null) {
                    $campaign->increment('completed_reposts');
                }

                $reposterNotification = CustomNotification::create([
                    'receiver_id' => $reposter->id,
                    'receiver_type' => get_class($reposter),
                    'type' => CustomNotification::TYPE_USER,
                    'url' => route('user.my-account') . '?tab=reposts',
                    'message_data' => [
                        'title' => "Repost successful",
                        'message' => "You've been reposted on a campaign",
                        'description' => "You've been reposted on a campaign by {$trackOwnerName}.",
                        'icon' => 'music',
                        'additional_data' => [
                            'Track Title' => $campaign->music->title,
                            'Track Artist' => $trackOwnerName,
                        ]
                    ]
                ]);

                $ownerNotificaion = CustomNotification::create([
                    'receiver_id' => $campaign?->user?->id,
                    'receiver_type' => get_class($campaign?->user),
                    'type' => CustomNotification::TYPE_USER,
                    'message_data' => [
                        'title' => "Repost successful",
                        'message' => "Your campaign has been reposted",
                        'description' => "Your campaign has been reposted by {$reposter->name}.",
                        'icon' => 'music',
                        'additional_data' => [
                            'Track Title' => $campaign->music->title,
                            'Track Artist' => $trackOwnerName,
                        ]
                    ]
                ]);

                broadcast(new UserNotificationSent($reposterNotification));
                broadcast(new UserNotificationSent($ownerNotificaion));
            });
            return true;
        } catch (Throwable $e) {
            throw $e;
        }
    }

    public function alreadyReposted($trackOwnerUrn, $campaignId = null, $requestId = null, $reposter = null): bool
    {
        if ($reposter == null) {
            $reposter = user();
        }

        $query = Repost::where('reposter_urn', $reposter->urn)->where('track_owner_urn', $trackOwnerUrn);

        if ($campaignId) {
            $query->where('campaign_id', $campaignId);
        }

        if ($requestId) {
            $query->where('request_id', $requestId);
        }

        if ($query->exists()) {
            return true;
        }

        return false;
    }
}
