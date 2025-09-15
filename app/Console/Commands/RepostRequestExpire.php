<?php

namespace App\Console\Commands;

use App\Models\RepostRequest;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;

class RepostRequestExpire extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:repost-request-expire';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Repost Request Expire Checking Command';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $expireRequests = RepostRequest::query()->whereNotNull('track_urn')->whereTime('expired_at', '<=', now())->get();
        if ($expireRequests->count() == 0) {
            $this->info('No Repost Request Expire');
            Log::info('No Repost Request Expire');
            return;
        } else {
            $this->info($expireRequests->count() . ' Repost Request Expire');
            Log::info($expireRequests->count() . ' Repost Request Expire');
            Bus::dispatch(new \App\Jobs\RepostRequestExpire($expireRequests));
        }

    }
}
