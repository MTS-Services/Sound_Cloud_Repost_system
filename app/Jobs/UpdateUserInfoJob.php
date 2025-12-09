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

class UpdateUserInfoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 600;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        $this->onQueue('users');
    }

    /**
     * Execute the job.
     */
    public function handle(SoundCloudService $soundCloudService): void
    {
        Log::info('Starting UpdateUserInfoJob...');

        // Fetch all active, non-banned users with their info
        $users = User::whereNull('banned_at')
            ->where('status', User::STATUS_ACTIVE)
            ->with('userInfo')
            ->get();

        if ($users->isEmpty()) {
            Log::warning('No active users found. Job exiting.');
            return;
        }

        // Use first user's token for all requests (as per your logic)
        $firstUser = $users->first();

        $updateCount = 0;
        $failedCount = 0;

        foreach ($users as $user) {
            try {
                $this->updateSingleUser($user, $firstUser, $soundCloudService);
                $updateCount++;
            } catch (\Throwable $e) {
                $failedCount++;
                Log::error("Failed to update user {$user->urn}", [
                    'user_urn' => $user->urn,
                    'error' => $e->getMessage(),
                ]);
                // Continue processing other users
                continue;
            }
        }

        Log::info("UpdateUserInfoJob completed.", [
            'total_users' => $users->count(),
            'updated' => $updateCount,
            'failed' => $failedCount,
        ]);
    }

    /**
     * Update a single user's information
     *
     * @param User $user
     * @param User $firstUser
     * @param SoundCloudService $soundCloudService
     * @return void
     */
    protected function updateSingleUser(User $user, User $firstUser, SoundCloudService $soundCloudService): void
    {
        // Fetch user info from SoundCloud
        $responseUser = $soundCloudService->fetchUserInfo($user, $firstUser);

        if (!$responseUser) {
            throw new \Exception("Failed to fetch user info from SoundCloud");
        }

        Log::info("Updating user {$user->urn}");
        DB::transaction(function () use ($user, $responseUser) {
            // Update User model
            $user->update([
                'avatar' => $responseUser['avatar_url'] ?? $user->avatar,
                'nickname' => $responseUser['username'] ?? $user->nickname,
                'name' => $responseUser['full_name'] ?? $responseUser['username'] ?? $user->name,
                'soundcloud_permalink_url' => $responseUser['permalink_url'] ?? $user->soundcloud_permalink_url,
            ]);

            // Update or create UserInformation
            if ($user->userInfo) {
                $user->userInfo->update($this->prepareUserInfoData($responseUser));
            } else {
                $user->userInfo()->create(array_merge(
                    ['user_urn' => $user->urn],
                    $this->prepareUserInfoData($responseUser)
                ));
            }
        });
    }

    /**
     * Prepare user information data from SoundCloud response
     *
     * @param array $responseUser
     * @return array
     */
    protected function prepareUserInfoData(array $responseUser): array
    {
        return [
            'first_name' => $responseUser['first_name'] ?? null,
            'last_name' => $responseUser['last_name'] ?? null,
            'full_name' => $responseUser['full_name'] ?? null,
            'username' => $responseUser['username'] ?? null,
            'soundcloud_id' => $responseUser['id'] ?? null,
            'soundcloud_urn' => $responseUser['urn'] ?? null,
            'soundcloud_kind' => $responseUser['kind'] ?? null,
            'soundcloud_permalink_url' => $responseUser['permalink_url'] ?? null,
            'soundcloud_permalink' => $responseUser['permalink'] ?? null,
            'soundcloud_uri' => $responseUser['uri'] ?? null,
            'soundcloud_created_at' => isset($responseUser['created_at'])
                ? \Carbon\Carbon::parse($responseUser['created_at'])
                : null,
            'soundcloud_last_modified' => isset($responseUser['last_modified'])
                ? \Carbon\Carbon::parse($responseUser['last_modified'])
                : null,
            'description' => $responseUser['description'] ?? null,
            'country' => $responseUser['country'] ?? null,
            'city' => $responseUser['city'] ?? null,
            'track_count' => $responseUser['track_count'] ?? 0,
            'public_favorites_count' => $responseUser['public_favorites_count'] ?? 0,
            'reposts_count' => $responseUser['reposts_count'] ?? 0,
            'followers_count' => $responseUser['followers_count'] ?? 0,
            'following_count' => $responseUser['followings_count'] ?? 0,
            'plan' => $responseUser['plan'] ?? null,
            'myspace_name' => $responseUser['myspace_name'] ?? null,
            'discogs_name' => $responseUser['discogs_name'] ?? null,
            'website_title' => $responseUser['website_title'] ?? null,
            'website' => $responseUser['website'] ?? null,
            'playlist_count' => $responseUser['playlist_count'] ?? 0,
            'upload_seconds_left' => $responseUser['upload_seconds_left'] ?? 0,
        ];
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('UpdateUserInfoJob failed completely', [
            'exception' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString()
        ]);
    }
}
