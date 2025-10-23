<?php

namespace App\Jobs;

use App\Models\Campaign;
use App\Services\User\AnalyticsService;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;
use Illuminate\Support\Facades\Cache;

class TopPerformanceSourceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $startDate;
    public $endDate;
    public $period;

    /**
     * Create a new job instance.
     */
    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->period = [
            'start' => $this->startDate,
            'end' => $this->endDate,
        ];
    }

    /**
     * Execute the job.
     */
    public function handle(AnalyticsService $analyticsService): void
    {
        Log::info('Starting TopPerformanceSourceJob', [
            'period' => $this->period,
            'attempts' => $this->attempts()
        ]);
        $sources = $analyticsService->getTopSources(
            filter: 'date_range',
            dateRange: $this->period,
            actionableType: Campaign::class
        );

        Cache::put('top_20_sources_cache', [
            'period' => $this->period,
            'analytics' => $sources,
        ],  2592000); // cache for 30 days
    }

    public function failed(Throwable $exception): void
    {
        Log::error('Failed to get top performance sources', [
            'exception' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString(),
            'attempts' => $this->attempts(),
            'period' => $this->period
        ]);
    }
}
