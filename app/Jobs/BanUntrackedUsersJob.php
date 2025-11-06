<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class BanUntrackedUsersJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Find users who have NO tracks on SoundCloud
        // Assuming you have a relation like ->tracks() or ->soundcloud_tracks()
        $users = User::whereNull('banned_at')
            ->where('status', User::STATUS_ACTIVE)
            ->get();

        foreach ($users as $user) {
            $user->update([
                'banned_at' => now(),
                'bander_id' => 1, // optional: system user/admin ID
            ]);
        }

        Log::info("{$users->count()} users banned because they had no SoundCloud tracks.");
    }
}
