<?php

namespace App\Services\User\Mamber;

use App\Jobs\NotificationMailSent;
use App\Models\CreditTransaction;
use App\Models\Playlist;
use App\Models\Repost;
use App\Models\RepostRequest;
use App\Models\Track;
use App\Services\SoundCloud\SoundCloudService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class RepostRequestService
{
    protected SoundCloudService $soundCloudService;

    protected string $baseUrl = 'https://api.soundcloud.com';

    public function __construct(SoundCloudService $soundCloudService)
    {
        $this->soundCloudService = $soundCloudService;
    }

    // public function getReportRequests($orderBy = 'sort_order', $order = 'asc')
    // {
    //     $reportRequests = RepostRequest::orderBy($orderBy, $order)->latest();
    //     return $reportRequests;
    // }
    // public function getReportRequest(string $encryptedId): RepostRequest|Collection
    // {
    //     $reportRequests = RepostRequest::findOrFail(decrypt($encryptedId));
    //     return $reportRequests;
    // }
    // public function createTrackReportRequest(array $data)
    // {
    //     $data['user_urn'] = user()->urn;
    //     $data['track_urn'] = $data['track_urn'];
    //     $data['created_id'] = user()->id;
    //     $data['music_type'] = Track::class;
    //     $reportRequests = RepostRequest::create($data);
    //     return $reportRequests;
    // }


    public function thisMonthDirectRequestCount()
    {
        return RepostRequest::self()->whereMonth('created_at', now()->month)->whereNull('campaign_id')->count();
    }

    public function handleRepost(int $requestId, string $commented, bool $liked, bool $followed): array
    {
        $this->soundCloudService->refreshUserTokenIfNeeded(user());

        try {
            $currentUserUrn = user()->urn;

            if (
                Repost::where('reposter_urn', $currentUserUrn)
                ->where('repost_request_id', $requestId)
                ->exists()
            ) {
                return ['status' => 'error', 'message' => 'You have already reposted this request.'];
            }

            $request = RepostRequest::findOrFail($requestId)->load('music', 'requester');
            if ($request->music_type == Track::class) {
                $musicUrn = $request->music->urn;
            } elseif ($request->music_type == Playlist::class) {
                $musicUrn = $request->music->soundcloud_urn;
            }

            if (!$request->music) {
                return ['status' => 'error', 'message' => 'Music not found for this request.'];
            }

            $httpClient = Http::withHeaders([
                'Authorization' => 'OAuth ' . user()->token,
            ]);

            $commentSoundcloud = [
                'comment' => [
                    'body' => $commented,
                    'timestamp' => time()
                ]
            ];

            // Send SoundCloud actions
            if ($request->music_type == Track::class) {
                $response = $httpClient->post("{$this->baseUrl}/reposts/tracks/{$musicUrn}");
                $comment_response = $commented ? $httpClient->post("{$this->baseUrl}/tracks/{$musicUrn}/comments", $commentSoundcloud) : null;
                $like_response = $liked ? $httpClient->post("{$this->baseUrl}/likes/tracks/{$musicUrn}") : null;
            } elseif ($request->music_type == Playlist::class) {
                $response = $httpClient->post("{$this->baseUrl}/reposts/playlists/{$musicUrn}");
                $comment_response = $commented ? $httpClient->post("{$this->baseUrl}/playlists/{$musicUrn}/comments", $commentSoundcloud) : null;
                $like_response = $liked ? $httpClient->post("{$this->baseUrl}/likes/playlists/{$musicUrn}") : null;
            }

            $follow_response = $followed ? $httpClient->put("{$this->baseUrl}/me/followings/{$request->requester_urn}") : null;

            if (!$response->successful()) {
                Log::error("SoundCloud Repost Failed: " . $response->body(), [
                    'request_id' => $requestId,
                    'user_urn' => $currentUserUrn,
                    'status' => $response->status(),
                ]);
                return ['status' => 'error', 'message' => 'Failed to repost to SoundCloud. Please try again.'];
            }

            // Optional: Handle non-fatal comment/like/follow failures
            if ($comment_response && !$comment_response->successful()) {
                Log::warning('Comment failed for repost request: ' . $requestId);
            }

            if ($like_response && !$like_response->successful()) {
                Log::warning('Like failed for repost request: ' . $requestId);
            }

            if ($follow_response && !$follow_response->successful()) {
                Log::warning('Follow failed for repost request: ' . $requestId);
            }

            $soundcloudRepostId = $response->json('id');

            DB::transaction(function () use (
                $requestId,
                $request,
                $currentUserUrn,
                $soundcloudRepostId,
                $commented,
                $liked
            ) {
                $trackOwnerUrn = $request->music->user?->urn ?? $request->user?->urn;
                $trackOwnerName = $request->music->user?->name ?? $request->user?->name;

                $repost = Repost::create([
                    'reposter_urn' => $currentUserUrn,
                    'repost_request_id' => $requestId,
                    'campaign_id' => $request->campaign_id,
                    'music_id' => $request->music_id,
                    'music_type' => $request->music_type,
                    'soundcloud_repost_id' => $soundcloudRepostId,
                    'track_owner_urn' => $trackOwnerUrn,
                    'reposted_at' => now(),
                    'credits_earned' => (float) user()->repost_price,
                ]);

                $request->update([
                    'status' => RepostRequest::STATUS_APPROVED,
                    'completed_at' => now(),
                    'responded_at' => now(),
                ]);

                CreditTransaction::create([
                    'receiver_urn' => $currentUserUrn,
                    'sender_urn' => $request->user?->urn,
                    'calculation_type' => CreditTransaction::CALCULATION_TYPE_DEBIT,
                    'source_id' => $request->id,
                    'source_type' => RepostRequest::class,
                    'status' => CreditTransaction::STATUS_SUCCEEDED,
                    'transaction_type' => CreditTransaction::TYPE_EARN,
                    'amount' => 0,
                    'credits' => (float) repostPrice(user()->repost_price, $commented, $liked),
                    'description' => "Repost From Direct Request",
                    'metadata' => [
                        'repost_id' => $repost->id,
                        'repost_request_id' => $request->id,
                        'soundcloud_repost_id' => $soundcloudRepostId,
                    ]
                ]);
            });

            // Send email notification
            if (hasEmailSentPermission('em_repost_accepted', $request->requester_urn)) {
                NotificationMailSent::dispatch([[
                    'email' => $request->requester->email,
                    'subject' => 'Repost Request Accepted',
                    'title' => 'Dear ' . $request->requester->name,
                    'body' => 'Your request for repost has been accepted. Please login to your Repostchain account to listen to the music.',
                ]]);
            }

            return ['status' => 'success', 'message' => 'Request reposted successfully.'];
        } catch (Throwable $e) {
            Log::error("Error in RepostService@handleRepost: " . $e->getMessage(), [
                'exception' => $e,
                'request_id' => $requestId,
                'user_urn' => user()->urn ?? 'N/A',
            ]);

            return ['status' => 'error', 'message' => 'An unexpected error occurred. Please try again later.'];
        }
    }
}
