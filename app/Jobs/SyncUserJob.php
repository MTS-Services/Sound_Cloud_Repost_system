<?php

namespace App\Jobs;

use App\Models\User;
use App\Services\SoundCloud\SoundCloudService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;

class SyncUserJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     */
    public $tries = 3;

    /**
     * The number of seconds to wait before retrying the job.
     */
    public $backoff = [60, 300, 900]; // 1 min, 5 min, 15 min

    /**
     * The maximum number of seconds the job can run.
     */
    public $timeout = 300; // 5 minutes

    protected User $user;
    protected object $soundCloudUser;
    protected $authUserId;

    /**
     * Create a new job instance.
     */
    public function __construct(User $user, object $soundCloudUser, $authUserId)
    {
        $this->user = $user;
        $this->soundCloudUser = $soundCloudUser;
        $this->authUserId = $authUserId;
    }

    /**
     * Execute the job.
     *
     * Laravel automatically resolves dependencies via dependency injection.
     */
    public function handle(SoundCloudService $soundCloudService): void
    {
        Log::info('Starting SyncUserJob', [
            'user_id' => $this->user->id,
            'user_urn' => $this->user->urn,
        ]);

        try {
            $authUser = User::find($this->authUserId);
            if (!$authUser) {
                Log::info('User not found, skipping syncUserJob');
                return;
            }


            if (!$authUser->expires_in || !$authUser->last_synced_at || !$authUser->isSoundCloudConnected()) {
                Log::info('User has no token, skipping syncUserJob', [
                    'auth_user_id' => $authUser->id,
                    'auth_user_urn' => $authUser->urn,
                ]);
                return;
            }

            $expirationTime = is_null($authUser->last_synced_at) ? null : $authUser->last_synced_at->addSeconds($authUser->expires_in);

            if ($expirationTime && $expirationTime->isPast()) {
                Log::info('User token expired, skipping syncUserJob', [
                    'auth_user_id' => $authUser->id,
                    'auth_user_urn' => $authUser->urn,
                ]);
                return;
            }

            if ($authUser->status === User::STATUS_INACTIVE) {
                Log::info('User is inactive, skipping syncUserJob', [
                    'auth_user_id' => $authUser->id,
                    'auth_user_urn' => $authUser->urn,
                ]);
                return;
            }

            Log::info('Authenticated user found', [
                'auth_user_id' => $authUser->id,
                'auth_user_urn' => $authUser->urn,
            ]);

            // Sync user information (lightweight operation)
            Log::info('Syncing user information Step 1');
            $soundCloudService->syncUserInformation($this->user, $this->soundCloudUser);
            Log::info('Syncing user information completed Step 1');

            // Sync tracks (can be heavy, wrapped individually)
            Log::info('Syncing user tracks Step 2');
            $soundCloudService->syncUserTracks(user: $this->user, tracksData: [], authUser: $authUser);
            Log::info('Syncing user tracks completed Step 2');

            // Sync playlists (can be heavy, wrapped individually)
            Log::info('Syncing user playlists Step 3');
            $soundCloudService->syncUserPlaylists(user: $this->user, authUser: $authUser);
            Log::info('Syncing user playlists completed Step 3');

            // Sync products and subscriptions
            Log::info('Syncing user products and subscriptions Step 4');
            $soundCloudService->syncUserProductsAndSubscriptions(user: $this->user, soundCloudUser: $this->soundCloudUser);
            Log::info('Syncing user products and subscriptions completed Step 4');

            // Sync followers
            Log::info('Syncing user followers Step 5');
            $soundCloudService->syncUserRealFollowers(user: $authUser);
            Log::info('Syncing user followers completed Step 5');

            Log::info('SyncUserJob completed successfully', [
                'user_id' => $this->user->id,
                'user_urn' => $this->user->urn,
            ]);

        } catch (\Exception $e) {
            Log::error('SyncUserJob encountered an error', [
                'user_id' => $this->user->id,
                'user_urn' => $this->user->urn,
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            // Re-throw to trigger job failure
            throw $e;
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(Throwable $exception): void
    {
        Log::error('SyncUserJob failed permanently', [
            'user_id' => $this->user->id,
            'user_urn' => $this->user->urn,
            'soundcloud_user_id' => $this->soundCloudUser->id ?? null,
            'exception' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString(),
            'attempts' => $this->attempts(),
        ]);

        // Optional: Send notification to admin or update user status
        // $this->user->update(['sync_status' => 'failed']);
    }
}
