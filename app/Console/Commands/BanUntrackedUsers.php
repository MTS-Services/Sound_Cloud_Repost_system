<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Bus;
use App\Jobs\BanUntrackedUsersJob;
use App\Services\SoundCloud\SoundCloudService;

class BanUntrackedUsers extends Command
{
    protected $signature = 'app:ban-untracked-users';
    protected $description = 'Ban users who have no activity in the last 7 days';

    public function handle()
    {
        Bus::dispatch(new BanUntrackedUsersJob(app(SoundCloudService::class)));
        $this->info('BanUntrackedUsersJob dispatched successfully.');
    }
}
