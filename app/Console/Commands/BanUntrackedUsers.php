<?php

namespace App\Console\Commands;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class BanUntrackedUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:ban-untracked-users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Ban users who have no activity in the last 7 days';

    /**
     * Execute the console command.
     */

    public function handle()
    {
        // 
    }
}
