<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RepostPlaybackController extends Controller
{
    /**
     * Track repost request playback without triggering Livewire re-render
     * Optimized for low memory usage
     */
    public function trackPlayback(Request $request)
    {
        $validated = $request->validate([
            'requestId' => 'required|string',
            'actualPlayTime' => 'required|numeric|min:0|max:1000',
            'isEligible' => 'required|boolean',
            'reposted' => 'nullable|boolean',
            'action' => 'required|string|in:play,pause,progress,eligible,finish,sync,repost',
        ]);

        $requestId = $validated['requestId'];
        $actualPlayTime = round($validated['actualPlayTime'], 2);
        $isEligible = $validated['isEligible'];
        $reposted = $validated['reposted'] ?? false;
        $action = $validated['action'];

        // Get tracking data from session
        $trackingData = session()->get('repost_request_playback_tracking', []);

        // Initialize or update request data
        if (!isset($trackingData[$requestId])) {
            $trackingData[$requestId] = [
                'request_id' => $requestId,
                'actual_play_time' => $actualPlayTime,
                'is_eligible' => $isEligible,
                'reposted' => $reposted,
                'play_started_at' => now()->toDateTimeString(),
                'last_updated_at' => now()->toDateTimeString(),
            ];
        } else {
            // Only update if new data is more recent
            if ($actualPlayTime >= $trackingData[$requestId]['actual_play_time']) {
                $trackingData[$requestId]['actual_play_time'] = $actualPlayTime;
                $trackingData[$requestId]['is_eligible'] = $isEligible;
                $trackingData[$requestId]['reposted'] = $reposted;
                $trackingData[$requestId]['last_updated_at'] = now()->toDateTimeString();
            }
        }

        // Cleanup old requests (keep only last 50)
        if (count($trackingData) > 50) {
            // Sort by last_updated_at and keep only recent 50
            uasort($trackingData, function ($a, $b) {
                return strtotime($b['last_updated_at']) - strtotime($a['last_updated_at']);
            });
            $trackingData = array_slice($trackingData, 0, 50, true);
        }

        // Save to session
        session()->put('repost_request_playback_tracking', $trackingData);

        // Log only important actions to reduce I/O
        if (in_array($action, ['eligible', 'finish', 'repost'])) {
            Log::info("Repost request {$action}", [
                'request_id' => $requestId,
                'play_time' => $actualPlayTime,
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'requestId' => $requestId,
                'actualPlayTime' => $actualPlayTime,
                'isEligible' => $isEligible,
            ]
        ]);
    }

    /**
     * Clear all tracking data from session
     */
    public function clearTracking(Request $request)
    {
        session()->forget('repost_request_playback_tracking');

        return response()->json([
            'success' => true,
            'message' => 'Repost request tracking cleared'
        ]);
    }

    /**
     * Get current tracking data (optional endpoint for debugging)
     */
    public function getTrackingData(Request $request)
    {
        $trackingData = session()->get('repost_request_playback_tracking', []);

        return response()->json([
            'success' => true,
            'data' => $trackingData,
            'count' => count($trackingData)
        ]);
    }
}
