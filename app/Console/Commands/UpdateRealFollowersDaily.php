<?php

namespace App\Console\Commands;

use App\Jobs\UpdateRealFollowers;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;


class UpdateRealFollowersDaily extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-real-followers-daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $users = User::active()->get();
        Log::info('Updating real followers for ' . count($users) . ' users', ['users' => $users]);

        foreach ($users as $user) {
            UpdateRealFollowers::dispatch($user)->delay(now()->addMinutes(2));
            sleep(5);
        }

    }
}
