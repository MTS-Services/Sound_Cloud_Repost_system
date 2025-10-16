<?php

namespace App\Jobs;

use App\Models\User;
use App\Services\SoundCloud\FollowerAnalyzer;
use App\Services\SoundCloud\SoundCloudService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class UpdateRealFollowers implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function handle(FollowerAnalyzer $followerAnalyzer, SoundCloudService $soundCloudService): void
    {
        Log::info("Starting real follower sync for user: {$this->user->urn}");

        try {
            $followers = $soundCloudService->getAuthUserFollowers($this->user);
            $followerAnalyzer->syncUserRealFollowers($followers, $this->user);

            Log::info("Successfully synced real followers for user: {$this->user->urn}");
        } catch (\Throwable $e) {
            Log::error("Failed to sync followers for user: {$this->user->urn}", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            $this->fail($e);
        }
    }
}
