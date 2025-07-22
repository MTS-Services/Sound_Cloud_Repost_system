<?php

namespace App\Jobs;

use App\Models\User;
use App\Services\SoundCloud\SoundCloudService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class SyncUserJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;
    protected $soundCloudUser;
    protected SoundCloudService $soundCloudService;

    public function __construct(User $user, $soundCloudUser, SoundCloudService $soundCloudService)
    {
        $this->user = $user;
        $this->soundCloudUser = $soundCloudUser;
        $this->soundCloudService = $soundCloudService;
    }

    public function handle(): void
    {
        DB::transaction(function () {
            $this->soundCloudService->syncUserTracks($this->user);
            $this->soundCloudService->syncUserProductsAndSubscriptions($this->user, $this->soundCloudUser);
            $this->soundCloudService->updateUserPlaylists($this->user);
            $this->soundCloudService->syncUserInformation($this->user, $this->soundCloudUser);
        });
    }

    public function failed(): void
    {
        //
    }
}
