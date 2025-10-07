<?php
namespace App\Jobs;

use App\Models\User;
use App\Services\SoundCloud\FollowerAnalyzer;
use App\Services\SoundCloud\SoundCloudService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class UpdateRealFollowers implements ShouldQueue
{
    use Queueable;

    protected $user;

    /**
     * Create a new job instance.
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     */
    public function handle(FollowerAnalyzer $followerAnalyzer, SoundCloudService $soundCloudService): void
    {

        $followers = $soundCloudService->getAuthUserFollowers($this->user);
        Log::info("Successfully synced real followers for user {$this->user->urn}.");
        sleep(5);
        $followerAnalyzer->syncUserRealFollowers($followers, $this->user);
        Log::info("Successfully synced real followers for user {$this->user->urn}.");

    }
}
