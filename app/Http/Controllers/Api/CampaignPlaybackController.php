<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CampaignPlaybackController extends Controller
{
    /**
     * Track campaign playback without triggering Livewire re-render
     */
    public function trackPlayback(Request $request)
    {
        $validated = $request->validate([
            'campaignId' => 'required|string',
            'actualPlayTime' => 'required|numeric|min:0',
            'isEligible' => 'required|boolean',
            'action' => 'required|string|in:play,pause,progress,eligible,finish',
        ]);

        $campaignId = $validated['campaignId'];
        $actualPlayTime = $validated['actualPlayTime'];
        $isEligible = $validated['isEligible'];
        $action = $validated['action'];

        // Get or create tracking data in session
        $trackingData = session()->get('campaign_playback_tracking', []);

        if (!isset($trackingData[$campaignId])) {
            $trackingData[$campaignId] = [
                'campaign_id' => $campaignId,
                'actual_play_time' => 0,
                'is_eligible' => false,
                'play_started_at' => now()->toDateTimeString(),
                'last_updated_at' => now()->toDateTimeString(),
                'actions' => [],
            ];
        }

        // Update tracking data
        $trackingData[$campaignId]['actual_play_time'] = $actualPlayTime;
        $trackingData[$campaignId]['is_eligible'] = $isEligible;
        $trackingData[$campaignId]['last_updated_at'] = now()->toDateTimeString();
        $trackingData[$campaignId]['actions'][] = [
            'action' => $action,
            'time' => $actualPlayTime,
            'timestamp' => now()->toDateTimeString(),
        ];

        // Save to session
        session()->put('campaign_playback_tracking', $trackingData);

        Log::info('Playback tracking updated via API', [
            'campaign_id' => $campaignId,
            'actual_play_time' => $actualPlayTime,
            'is_eligible' => $isEligible,
            'action' => $action,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Tracking updated successfully',
            'data' => [
                'campaignId' => $campaignId,
                'actualPlayTime' => $actualPlayTime,
                'isEligible' => $isEligible,
            ]
        ]);
    }
}