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

    /**
     * Create a new job instance.
     */
    public function __construct(User $user, object $soundCloudUser)
    {
        $this->user = $user;
        $this->soundCloudUser = $soundCloudUser;
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
            // Sync user information (lightweight operation)
            $soundCloudService->syncUserInformation($this->user, $this->soundCloudUser);

            // Sync tracks (can be heavy, wrapped individually)
            $soundCloudService->syncUserTracks($this->user, []);

            // Sync playlists (can be heavy, wrapped individually)
            $soundCloudService->syncUserPlaylists($this->user);

            // Sync products and subscriptions
            $soundCloudService->syncUserProductsAndSubscriptions($this->user, $this->soundCloudUser);

            // Sync followers
            $soundCloudService->syncUserRealFollowers($this->user);

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
