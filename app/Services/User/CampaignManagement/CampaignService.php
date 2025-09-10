<?php

namespace App\Services\User\CampaignManagement;

use App\Events\UserNotificationSent;
use App\Jobs\NotificationMailSent;
use App\Mail\NotificationMails;
use App\Models\Campaign;
use App\Models\CreditTransaction;
use App\Models\CustomNotification;
use App\Models\Repost;
use App\Services\User\AnalyticsService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
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
                $trackOwnerName = $campaign->music->user?->name;
                $totalCredits = repostPrice() + ($likeCommentAbleData['comment'] ? 2 : 0) + ($likeCommentAbleData['likeable'] ? 2 : 0);

                // Create the Repost record
                $repost = Repost::create([
                    'reposter_urn' => $reposter->urn,
                    'track_owner_urn' => $trackOwnerUrn,
                    'campaign_id' => $campaign->id,
                    'soundcloud_repost_id' => $soundcloudRepostId,
                    'reposted_at' => now(),
                    'credits_earned' => $totalCredits,
                ]);

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
                    // foreach ($datas as $mailData) {
                    //     Mail::to($mailData['email'])->send(new NotificationMails($mailData));
                    // }
                }


                $response = $this->analyticsService->updateAnalytics($campaign->music, $campaign, 'total_reposts', $campaign->target_genre);
                $response = $this->analyticsService->updateAnalytics($campaign->music, $campaign, 'total_comments', $campaign->target_genre);
                if ($response != false || $response != null) {
                    $campaign->increment('comment_count');
                    $repost->increment('comment_count');
                }
                $response = $this->analyticsService->updateAnalytics($campaign->music, $campaign, 'total_likes', $campaign->target_genre);
                if ($response != false || $response != null) {
                    $campaign->increment('like_count');
                    $repost->increment('like_count');
                }
                $response = $this->analyticsService->updateAnalytics($campaign->music, $campaign, 'total_followers', $campaign->target_genre);
                if ($response != false || $response != null) {
                    $campaign->increment('followowers_count');
                    $repost->increment('followowers_count');
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
}
