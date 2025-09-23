<?php

namespace App\Jobs;

use App\Models\User;
use App\Services\SoundCloud\SoundCloudService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable; // Import Throwable for more general exception catching in failed()

class SyncUserJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The user instance.
     *
     * @var User
     */
    protected User $user;

    /**
     * The SoundCloud user data (e.g., from Socialite).
     *
     * @var object
     */
    protected object $soundCloudUser;

    /**
     * Create a new job instance.
     *
     * @param User $user The user model to sync.
     * @param object $soundCloudUser The SoundCloud user data from Socialite.
     * @return void
     */
    public function __construct(User $user, object $soundCloudUser)
    {
        $this->user = $user;
        $this->soundCloudUser = $soundCloudUser;
    }

    /**
     * Execute the job.
     *
     * @param SoundCloudService $soundCloudService Automatically resolved by Laravel's service container.
     * @return void
     */
    public function handle(SoundCloudService $soundCloudService): void
    {
        Log::info('SyncUserJob started for user ID: ' . $this->user->id . ', URN: ' . $this->user->urn . ', on login: ' . now());
        DB::transaction(function () use ($soundCloudService) {
            Log::info('Start User Information Sync');
            $soundCloudService->syncUserInformation($this->user, $this->soundCloudUser);
            Log::info('Start User Tracks Sync');
            $soundCloudService->syncUserTracks($this->user, []);
            Log::info('Start User Playlists Sync');
            $soundCloudService->syncUserPlaylists($this->user);
            Log::info('Start User Products and Subscriptions Sync');
            $soundCloudService->syncUserProductsAndSubscriptions($this->user, $this->soundCloudUser);
            // You might want to update a 'last_synced_at' timestamp on the User model here
            $this->user->update(['last_synced_at' => now()]);
        });
    }

    /**
     * Handle a job failure.
     *
     * @param Throwable $exception
     * @return void
     */
    public function failed(Throwable $exception): void
    {
        Log::error('SyncUserJob failed', [
            'user_id' => $this->user->id,
            'user_urn' => $this->user->urn, // Assuming 'urn' is a unique identifier
            'exception' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString(),
        ]);

        // Optionally, notify admin or user
        // Mail::to('admin@example.com')->send(new JobFailedNotification($this->user, $exception));
    }
}
