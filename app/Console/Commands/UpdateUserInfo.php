<?php

namespace App\Console\Commands;

use App\Jobs\UpdateUserInfoJob;
use Illuminate\Console\Command;

class UpdateUserInfo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:update-info';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update user information from SoundCloud API';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Dispatching job to update user information...');

        try {
            UpdateUserInfoJob::dispatch();

            $this->info('Job dispatched to queue successfully.');

            return self::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Error dispatching job: ' . $e->getMessage());
            return self::FAILURE;
        }
    }
}
