<?php

namespace App\Console\Commands;

use App\Jobs\TopPerformanceSourceJob;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class TopPerformanceSourceWeeklyUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:top-performance-source-weekly-update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'The chart data will be updated every week automatically';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $this->info('Dispatching TopPerformanceSourceJob for getting current updated data...');

            $startDate = Carbon::now()->subDays(6)->startOfDay();
            $endDate = Carbon::now()->endOfDay();

            TopPerformanceSourceJob::dispatch($startDate, $endDate);

            $this->info('TopPerformanceSourceJob dispatched successfully.');
        } catch (\Exception $e) {
            $this->error('Failed to dispatch TopPerformanceSourceJob: ' . $e->getMessage());
            // Optionally, log the full exception stack trace
            Log::error('TopPerformanceSourceJob dispatch error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }
}
