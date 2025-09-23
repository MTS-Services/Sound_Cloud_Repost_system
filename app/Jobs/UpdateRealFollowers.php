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

    /**
     * Create a new job instance.
     */

    protected FollowerAnalyzer $followerAnalyzer;
    protected SoundCloudService $soundCloudService;
    protected $users;


    public function __construct($users, FollowerAnalyzer $followerAnalyzer = new FollowerAnalyzer(), SoundCloudService $soundCloudService = new SoundCloudService())
    {
        $this->followerAnalyzer = $followerAnalyzer;
        $this->soundCloudService = $soundCloudService;
        $this->users = $users;
    }


    /**
     * Execute the job.
     */
    public function handle(): void
    {
        foreach ($this->users as $user) {
            $followers = $this->soundCloudService->getAuthUserFollowers($user);
            $this->followerAnalyzer->syncUserRealFollowers($followers, $user);
            sleep(5);
        }
    }
}
