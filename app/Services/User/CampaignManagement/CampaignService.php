<?php

namespace App\Services\User\CampaignManagement;

use App\Events\UserNotificationSent;
use App\Jobs\NotificationMailSent;
use App\Models\Campaign;
use App\Models\CreditTransaction;
use App\Models\CustomNotification;
use App\Models\Repost;
use App\Models\Track;
use App\Models\User;
use App\Models\UserAnalytics;
use App\Services\User\AnalyticsService;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class CampaignService
{
    protected AnalyticsService $analyticsService;
    public function __construct(AnalyticsService $analyticsService, protected Campaign $campaign)
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

                // Determine what actions were actually performed
                $likeable = $campaign->likeable && $likeCommentAbleData['likeable'] ? true : false;
                $commentable = $campaign->commentable && $likeCommentAbleData['comment'] ? true : false;

                // Check if these actions were affordable (passed from frontend checks)
                $canAffordLike = isset($likeCommentAbleData['canAffordLike'])
                    ? ($likeable && $likeCommentAbleData['canAffordLike'] == true ? true : false)
                    : $likeable; // Default to likeable if not set

                $canAffordComment = isset($likeCommentAbleData['canAffordComment'])
                    ? ($commentable && $likeCommentAbleData['canAffordComment'] == true ? true : false)
                    : $commentable; // Default to commentable if not set

                // Calculate total credits based on what was affordable
                $totalCredits = 0;
                if ($canAffordLike && $canAffordComment) {
                    $totalCredits = repostPrice(repost_price: $reposter->repost_price, commentend: true, liked: true);
                } elseif ($canAffordLike && !$canAffordComment) {
                    $totalCredits = repostPrice(repost_price: $reposter->repost_price, commentend: false, liked: true);
                } elseif (!$canAffordLike && $canAffordComment) {
                    $totalCredits = repostPrice(repost_price: $reposter->repost_price, commentend: true, liked: false);
                } else {
                    // Neither like nor comment affordable, just repost price
                    $totalCredits = repostPrice(repost_price: $reposter->repost_price, commentend: false, liked: false);
                }

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

                $reachedBudget = $campaign->budget_credits <= $campaign->credits_spent;

                if ($campaign->status == Campaign::STATUS_OPEN && $reachedBudget) {

                    $campaign->status = Campaign::STATUS_COMPLETED;
                    $campaign->save();

                    $repostEmailPermission = hasEmailSentPermission('em_repost_accepted', $campaign->user->urn);

                    if ($repostEmailPermission) {
                        $datas = [
                            [
                                'email' => $campaign->user->email,
                                'subject' => 'Repost Budget Reached',
                                'title' => 'Dear ' . $campaign->user->name,
                                'body' => 'Your repost budget has been reached. Your campaign has been completed. Please check your dashboard for more details.',
                            ],
                        ];
                        NotificationMailSent::dispatch($datas);
                    }
                }

                if ($repost != null) {
                    $response = $this->analyticsService->recordAnalytics($campaign->music, $campaign, UserAnalytics::TYPE_REPOST, $campaign->target_genre);
                }

                // Only record analytics for actions that were affordable
                if ($canAffordComment) {
                    $response = $this->analyticsService->recordAnalytics($campaign->music, $campaign, UserAnalytics::TYPE_COMMENT, $campaign->target_genre);
                    if ($response != false || $response != null) {
                        $campaign->increment('comment_count');
                    }
                }

                if ($canAffordLike) {
                    $response = $this->analyticsService->recordAnalytics($campaign->music, $campaign, UserAnalytics::TYPE_LIKE, $campaign->target_genre);
                    if ($response != false || $response != null) {
                        $campaign->increment('like_count');
                    }
                }

                if ($likeCommentAbleData['follow'] == true) {
                    $response = $this->analyticsService->recordAnalytics($campaign->music, $campaign, UserAnalytics::TYPE_FOLLOW, $campaign->target_genre);

                    if ($response != false || $response != null) {
                        $campaign->increment('followers_count');
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
                        'like_credited' => $canAffordLike,
                        'comment_credited' => $canAffordComment,
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
                            'Spent Credits' => (float) $totalCredits,
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
    public function repostSource($campaign, $soundcloudRepostId, $reposter = null, $repostIncreased = false)
    {
        try {
            DB::transaction(function () use ($campaign, $soundcloudRepostId, $reposter, $repostIncreased) {

                if ($reposter == null) {
                    $reposter = user();
                }
                $sourceOwnerUrn = $campaign->music->user?->urn ?? $campaign->user_urn;
                $sourceOwnerName = $campaign->music->user?->name ?? $campaign->user?->name;

                $repost = Repost::create([
                    'reposter_urn' => $reposter->urn,
                    'track_owner_urn' => $sourceOwnerUrn,
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
                        'description' => "You've been reposted on a campaign by {$sourceOwnerName}.",
                        'icon' => 'music',
                        'additional_data' => [
                            $campaign->music_type == Track::class ? 'Track' : 'Playlist' . ' Title' => $campaign->music->title,
                            $campaign->music_type == Track::class ? 'Track' : 'Playlist' . ' Artist' => $sourceOwnerName,
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
                            $campaign->music_type == Track::class ? 'Track' : 'Playlist' . ' Title' => $campaign->music->title,
                            $campaign->music_type == Track::class ? 'Track' : 'Playlist' . ' Artist' => $sourceOwnerName,
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

    /**
     * Complete expired campaigns and refund remaining credits
     * 
     * Campaigns are considered expired if:
     * - end_date has passed, OR
     * - end_date is null AND (created_at + 5 days) has passed
     */
    public function completeExpiredCampaigns(): int
    {
        $now = now();

        // Fetch all expired open campaigns with eager loading to prevent N+1
        $expiredCampaigns = Campaign::with(['user', 'music'])
            ->where('status', Campaign::STATUS_OPEN)
            ->where(function ($query) use ($now) {
                // Campaigns with end_date that has passed
                $query->where(function ($q) use ($now) {
                    $q->whereNotNull('end_date')
                        ->where('end_date', '<', $now);
                })
                    // OR campaigns without end_date where created_at + 5 days < now
                    ->orWhere(function ($q) use ($now) {
                        $q->whereNull('end_date')
                            ->whereRaw('DATE_ADD(created_at, INTERVAL 5 DAY) < ?', [$now]);
                    });
            })
            ->get();

        if ($expiredCampaigns->isEmpty()) {
            return 0;
        }

        // Process campaigns in chunks to avoid memory issues
        $processedCount = 0;

        foreach ($expiredCampaigns as $campaign) {
            try {
                DB::transaction(function () use ($campaign) {
                    $this->completeCampaignWithRefund($campaign);
                    Log::info("Campaign {$campaign->id} completed successfully");
                });

                $processedCount++;
            } catch (\Throwable $e) {
                Log::error("Failed to complete campaign {$campaign->id}", [
                    'campaign_id' => $campaign->id,
                    'error' => $e->getMessage()
                ]);
                // Continue processing other campaigns
                continue;
            }
        }

        return $processedCount;
    }

    /**
     * Complete a single campaign and process refund
     *
     * @param Campaign $campaign
     * @return void
     */
    protected function completeCampaignWithRefund(Campaign $campaign): void
    {
        $remainingCredits = $campaign->budget_credits - $campaign->credits_spent;

        // Update campaign status
        $campaign->update([
            'status' => Campaign::STATUS_COMPLETED,
            'refund_credits' => $remainingCredits,
            'end_date' => $campaign->end_date ? $campaign->end_date : now()
        ]);

        // Only create refund transaction if there are remaining credits
        if ($remainingCredits > 0) {
            CreditTransaction::create([
                'receiver_urn' => $campaign->user_urn,
                'calculation_type' => CreditTransaction::CALCULATION_TYPE_DEBIT,
                'source_id' => $campaign->id,
                'source_type' => Campaign::class,
                'status' => CreditTransaction::STATUS_SUCCEEDED,
                'transaction_type' => CreditTransaction::TYPE_REFUND,
                'amount' => 0,
                'credits' => (float) $remainingCredits,
                'description' => "Refund for expired campaign: {$campaign->title}",
                'metadata' => [
                    'campaign_id' => $campaign->id,
                    'reason' => 'campaign_expired',
                    'expired_at' => now()->toDateTimeString()
                ]
            ]);
        }

        // Send notification to campaign owner
        $this->sendCampaignCompletionNotification($campaign, $remainingCredits);
    }

    /**
     * Send notification to campaign owner about campaign completion
     *
     * @param Campaign $campaign
     * @param float $remainingCredits
     * @return void
     */
    protected function sendCampaignCompletionNotification(Campaign $campaign, float $remainingCredits): void
    {
        $musicType = $campaign->music_type == Track::class ? 'Track' : 'Playlist';

        $notification = CustomNotification::create([
            'receiver_id' => $campaign->user->id,
            'receiver_type' => User::class,
            'type' => CustomNotification::TYPE_USER,
            'message_data' => [
                'title' => 'Campaign Completed',
                'message' => 'Your campaign has expired and been completed',
                'description' => $remainingCredits > 0
                    ? "Your campaign '{$campaign->title}' has expired. You have been refunded {$remainingCredits} credits."
                    : "Your campaign '{$campaign->title}' has expired and completed successfully.",
                'icon' => 'music',
                'additional_data' => [
                    "{$musicType} Title" => $campaign->music->title,
                    'Completed Reposts' => $campaign->completed_reposts,
                    'Credits Spent' => (float) $campaign->credits_spent,
                    'Refunded Credits' => (float) $remainingCredits,
                    'Campaign Duration' => $campaign->created_at->diffForHumans($campaign->end_date ?? now(), true)
                ]
            ]
        ]);

        broadcast(new UserNotificationSent($notification));

        // Optional: Send email notification if user has email preferences enabled
        // Check if the column exists in your user_settings table
        // If 'em_campaign_summary' doesn't exist, use a different column or remove this
        try {
            $emailPermission = hasEmailSentPermission('em_campaign_summary', $campaign->user->urn);

            if ($emailPermission && $campaign->user->email) {
                NotificationMailSent::dispatch([[
                    'email' => $campaign->user->email,
                    'subject' => 'Campaign Completed - ' . $campaign->title,
                    'title' => 'Dear ' . $campaign->user->name,
                    'body' => "Your campaign '{$campaign->title}' has expired and been completed. " .
                        ($remainingCredits > 0
                            ? "You have been refunded {$remainingCredits} credits to your account."
                            : "Thank you for using our service!"),
                ]]);
            }
        } catch (\Exception $e) {
            // Log but don't fail if email permission check fails
            Log::warning("Could not check email permission for campaign completion", [
                'campaign_id' => $campaign->id,
                'user_urn' => $campaign->user->urn,
                'error' => $e->getMessage()
            ]);
        }
    }
}
