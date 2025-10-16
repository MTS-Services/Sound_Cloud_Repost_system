<?php

namespace App\Jobs;

use App\Events\UserNotificationSent;
use App\Models\CreditTransaction;
use App\Models\CustomNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Models\RepostRequest;

class RepostRequestExpire implements ShouldQueue
{
    use Queueable;

    private $repostRequests;

    /**
     * Create a new job instance.
     */
    public function __construct($repostRequests)
    {
        $this->repostRequests = $repostRequests;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $delaySeconds = 0;
        foreach ($this->repostRequests as $repostRequest) {
            $repostRequest->update(['status' => RepostRequest::STATUS_EXPIRED]);
            $transaction_credits = $repostRequest->credits_spent + ($repostRequest->likeable ? 2 : 0) + ($repostRequest->commentable ? 2 : 0);

            CreditTransaction::create([
                'receiver_urn' => $repostRequest->requester_urn,
                'sender_urn' => null,
                'transaction_type' => CreditTransaction::TYPE_SPEND,
                'calculation_type' => CreditTransaction::CALCULATION_TYPE_DEBIT,
                'source_id' => $repostRequest->id,
                'source_type' => RepostRequest::class,
                'amount' => 0,
                'credits' => $transaction_credits,
                'description' => "Your repost request has expired.",
                'metadata' => [
                    'request_type' => 'track',
                    'target_urn' => $repostRequest->target_user_urn,
                    'track_titele' => $repostRequest->track?->title,
                ],
                'status' => CreditTransaction::STATUS_SUCCEEDED,
            ]);


            $requesterNotification = CustomNotification::create([
                'receiver_id' => $repostRequest->requester?->id,
                'receiver_type' => get_class($repostRequest->requester),
                'type' => CustomNotification::TYPE_USER,
                'url' => route('user.reposts-request'),
                'message_data' => [
                    'title' => 'Your repost request has expired.',
                    'message' => 'Your repost request has expired.',
                    'description' => 'Your repost request has expired that you sent to ' . $repostRequest->targetUser?->name . ' for the track "' . $repostRequest->track?->title . '".',
                    'icon' => 'info',
                    'additional_data' => [
                        'Request Sent To' => $repostRequest->targetUser?->name,
                        'Track Title' => $repostRequest->track?->title,
                        'Track Artist' => $repostRequest->track?->artist,
                        'Request Sent At' => $repostRequest->requested_at,
                        'Request Expired At' => $repostRequest->expired_at,
                    ],
                ],
            ]);
            $targetUserNotification = CustomNotification::create([
                'receiver_id' => $repostRequest->targetUser?->id,
                'receiver_type' => get_class($repostRequest->targetUser),
                'type' => CustomNotification::TYPE_USER,
                'url' => route('user.reposts-request'),
                'message_data' => [
                    'title' => 'A repost request has expired.',
                    'message' => 'A repost request has expired.',
                    'description' => 'A repost request has expired that you received from ' . $repostRequest->requester?->name . ' for the track "' . $repostRequest->track?->title . '".',
                    'icon' => 'info',
                    'additional_data' => [
                        'Request Sent From' => $repostRequest->requester?->name,
                        'Track Title' => $repostRequest->track?->title,
                        'Track Artist' => $repostRequest->track?->artist,
                        'Request Sent At' => $repostRequest->requested_at,
                        'Request Expired At' => $repostRequest->expired_at,
                    ],
                ],
            ]);
            broadcast(new UserNotificationSent($requesterNotification));
            broadcast(new UserNotificationSent($targetUserNotification));
            $requesterEmailPermission = hasEmailSentPermission('em_repost_expired', $repostRequest->requester_urn);
            $targetUserEmailPermission = hasEmailSentPermission('em_repost_expired', $repostRequest->target_user_urn);
            if ($repostRequest && $requesterEmailPermission) {
                $datas = [
                    [
                        'email' => $repostRequest->requester?->email,
                        'subject' => 'Your repost request has expired.',
                        'title' => 'Dear ' . $repostRequest->requester?->name . ',',
                        'body' => 'Your repost request has expired that you sent to ' . $repostRequest->targetUser?->name . ' for the track "' . $repostRequest->track?->title . '".',
                        'url' => route('user.reposts-request'),
                    ],
                ];
                NotificationMailSent::dispatch($datas)->delay(now()->addSeconds($delaySeconds));
                $delaySeconds += 10;
            }
            if ($repostRequest && $targetUserEmailPermission) {
                $datas = [
                    [
                        'email' => $repostRequest->requester?->email,
                        'subject' => 'A repost request has expired.',
                        'title' => 'Dear ' . $repostRequest->requester?->name . ',',
                        'body' => 'A repost request has expired that you received from ' . $repostRequest->requester?->name . ' for the track "' . $repostRequest->track?->title . '".',
                        'url' => route('user.reposts-request'),
                    ],
                ];
                NotificationMailSent::dispatch($datas)->delay(now()->addSeconds($delaySeconds));
                $delaySeconds += 10;
            }
        }
    }
}
