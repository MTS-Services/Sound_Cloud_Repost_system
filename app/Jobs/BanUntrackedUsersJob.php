<?php

namespace App\Jobs;

use App\Models\User;
use App\Services\SoundCloud\SoundCloudService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class BanUntrackedUsersJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    protected SoundCloudService $soundCloudService;

    public function __construct(SoundCloudService $soundCloudService)
    {
        $this->soundCloudService = $soundCloudService;
    }

    public function handle(): void
    {
        $users = User::whereNull('banned_at')
            ->where('status', User::STATUS_ACTIVE)
            ->get();

        $bannedCount = 0;

        $firstUser = $users->first();

        foreach ($users as $user) {
            $trackCount = $this->soundCloudService->soundCloudRealTracksCount($user, $firstUser);

            if ($trackCount === 0) {
                $user->update([
                    'banned_at' => now(),
                    'bander_id' => null, // system action
                ]);
                $bannedCount++;
            }
        }

        Log::info("{$bannedCount} users banned because they had no SoundCloud tracks.");
    }
}
