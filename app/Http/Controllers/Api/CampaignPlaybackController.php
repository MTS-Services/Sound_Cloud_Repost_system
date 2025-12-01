<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CampaignPlaybackController extends Controller
{
    /**
     * Track campaign playback without triggering Livewire re-render
     * Optimized for low memory usage
     */
    public function trackPlayback(Request $request)
    {
        $validated = $request->validate([
            'campaignId' => 'required|string',
            'actualPlayTime' => 'required|numeric|min:0|max:1000',
            'isEligible' => 'required|boolean',
            'reposted' => 'nullable|boolean',
            'action' => 'required|string|in:play,pause,progress,eligible,finish,sync,repost',
        ]);

        $campaignId = $validated['campaignId'];
        $actualPlayTime = round($validated['actualPlayTime'], 2);
        $isEligible = $validated['isEligible'];
        $reposted = $validated['reposted'] ?? false;
        $action = $validated['action'];

        // Get tracking data from session
        $trackingData = session()->get('campaign_playback_tracking', []);

        // Initialize or update campaign data
        if (!isset($trackingData[$campaignId])) {
            $trackingData[$campaignId] = [
                'campaign_id' => $campaignId,
                'actual_play_time' => $actualPlayTime,
                'is_eligible' => $isEligible,
                'reposted' => $reposted,
                'play_started_at' => now()->toDateTimeString(),
                'last_updated_at' => now()->toDateTimeString(),
            ];
        } else {
            // Only update if new data is more recent
            if ($actualPlayTime >= $trackingData[$campaignId]['actual_play_time']) {
                $trackingData[$campaignId]['actual_play_time'] = $actualPlayTime;
                $trackingData[$campaignId]['is_eligible'] = $isEligible;
                $trackingData[$campaignId]['reposted'] = $reposted;
                $trackingData[$campaignId]['last_updated_at'] = now()->toDateTimeString();
            }
        }

        // Cleanup old campaigns (keep only last 50)
        if (count($trackingData) > 50) {
            // Sort by last_updated_at and keep only recent 50
            uasort($trackingData, function ($a, $b) {
                return strtotime($b['last_updated_at']) - strtotime($a['last_updated_at']);
            });
            $trackingData = array_slice($trackingData, 0, 50, true);
        }

        // Save to session
        session()->put('campaign_playback_tracking', $trackingData);

        // Log only important actions to reduce I/O
        if (in_array($action, ['eligible', 'finish', 'repost'])) {
            Log::info("Campaign {$action}", [
                'campaign_id' => $campaignId,
                'play_time' => $actualPlayTime,
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'campaignId' => $campaignId,
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
        session()->forget('campaign_playback_tracking');

        return response()->json([
            'success' => true,
            'message' => 'Tracking cleared'
        ]);
    }

    /**
     * Get current tracking data (optional endpoint for debugging)
     */
    public function getTrackingData(Request $request)
    {
        $trackingData = session()->get('campaign_playback_tracking', []);

        return response()->json([
            'success' => true,
            'data' => $trackingData,
            'count' => count($trackingData)
        ]);
    }
}
