<?php
namespace App\Jobs;

use App\Models\User;
use App\Services\SoundCloud\FollowerAnalyzer;
use App\Services\SoundCloud\SoundCloudService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class UpdateRealFollowers implements ShouldQueue
{
    use Queueable;

    protected $users;

    /**
     * Create a new job instance.
     */
    public function __construct($users)
    {
        $this->users = $users;
    }

    /**
     * Execute the job.
     */
    public function handle(FollowerAnalyzer $followerAnalyzer, SoundCloudService $soundCloudService): void
    {
        foreach ($this->users as $user) {
            $followers = $soundCloudService->getAuthUserFollowers($user);
            $followerAnalyzer->syncUserRealFollowers($followers, $user);
            sleep(5);
        }
    }
}
