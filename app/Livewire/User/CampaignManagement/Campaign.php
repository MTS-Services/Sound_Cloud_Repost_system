<?php

namespace App\Livewire\User\CampaignManagement;

use App\Services\User\CampaignManagement\CampaignService;
use Livewire\Component;

class Campaign extends Component
{
    protected CampaignService $campaignService;
    public $featuredCampaigns;
    public $campaigns;

    // Track which campaigns are currently playing
    public $playingCampaigns = [];

    // Track play start times
    public $playStartTimes = [];

    // Track total play time for each campaign
    public $playTimes = [];

    // Track which campaigns have been played for 5+ seconds
    public $playedCampaigns = [];

    // Track which campaigns have been reposted
    public $repostedCampaigns = [];

    // Listeners for browser events
    protected $listeners = [
        'audioPlay' => 'handleAudioPlay',
        'audioPause' => 'handleAudioPause',
        'audioTimeUpdate' => 'handleAudioTimeUpdate',
        'audioEnded' => 'handleAudioEnded'
    ];

    public function mount(CampaignService $campaignService)
    {
        $this->campaignService = $campaignService;
        $allowed_target_credits = repostPrice(user());

        $this->featuredCampaigns = $this->campaignService->getCampaigns()
            // ->where('cost_per_repost', $allowed_target_credits)
            // ->featured()
            // ->withoutSelf()
            ->with(['music.user.userInfo'])
            ->get();

        $this->campaigns = $this->campaignService->getCampaigns()
            // ->where('cost_per_repost', $allowed_target_credits)
            // ->notFeatured()
            // ->withoutSelf()
            ->with(['music.user.userInfo'])
            ->get();

        // Initialize tracking arrays
        foreach ($this->featuredCampaigns as $campaign) {
            $this->playTimes[$campaign->id] = 0;
        }

        foreach ($this->campaigns as $campaign) {
            $this->playTimes[$campaign->id] = 0;
        }
    }

    /**
     * Handle audio play event
     */
    public function handleAudioPlay($campaignId)
    {
        $this->playingCampaigns[$campaignId] = true;
        $this->playStartTimes[$campaignId] = now()->timestamp;
    }

    /**
     * Handle audio pause event
     */
    public function handleAudioPause($campaignId)
    {
        $this->updatePlayTime($campaignId);
        unset($this->playingCampaigns[$campaignId]);
        unset($this->playStartTimes[$campaignId]);
    }

    /**
     * Handle audio time update event
     */
    public function handleAudioTimeUpdate($campaignId, $currentTime)
    {
        if ($currentTime >= 5 && !in_array($campaignId, $this->playedCampaigns)) {
            $this->playedCampaigns[] = $campaignId;
            $this->dispatch('campaignPlayedEnough', $campaignId);
        }
    }

    /**
     * Handle audio ended event
     */
    public function handleAudioEnded($campaignId)
    {
        $this->handleAudioPause($campaignId);
    }

    /**
     * Update play time for a campaign
     */
    private function updatePlayTime($campaignId)
    {
        if (isset($this->playStartTimes[$campaignId])) {
            $playDuration = now()->timestamp - $this->playStartTimes[$campaignId];
            $this->playTimes[$campaignId] = ($this->playTimes[$campaignId] ?? 0) + $playDuration;

            if ($this->playTimes[$campaignId] >= 5 && !in_array($campaignId, $this->playedCampaigns)) {
                $this->playedCampaigns[] = $campaignId;
                $this->dispatch('campaignPlayedEnough', $campaignId);
            }
        }
    }

    /**
     * Polling method to update play times for currently playing campaigns
     */
    public function updatePlayingTimes()
    {
        foreach ($this->playingCampaigns as $campaignId => $isPlaying) {
            if ($isPlaying && isset($this->playStartTimes[$campaignId])) {
                $playDuration = now()->timestamp - $this->playStartTimes[$campaignId];
                $totalPlayTime = ($this->playTimes[$campaignId] ?? 0) + $playDuration;

                if ($totalPlayTime >= 5 && !in_array($campaignId, $this->playedCampaigns)) {
                    $this->playedCampaigns[] = $campaignId;
                    $this->dispatch('campaignPlayedEnough', $campaignId);
                }
            }
        }
    }

    /**
     * Start playing a campaign manually
     */
    public function startPlaying($campaignId)
    {
        $campaign = $this->campaignService->getCampaign(encrypt($campaignId));
        $campaign->increment('playback_count');
        $this->handleAudioPlay($campaignId);
    }

    /**
     * Stop playing a campaign manually
     */
    public function stopPlaying($campaignId)
    {
        $this->handleAudioPause($campaignId);
    }

    /**
     * Simulate audio progress (for testing without actual audio)
     */
    public function simulateAudioProgress($campaignId, $seconds = 1)
    {
        if (!isset($this->playTimes[$campaignId])) {
            $this->playTimes[$campaignId] = 0;
        }

        $this->playTimes[$campaignId] += $seconds;

        if ($this->playTimes[$campaignId] >= 5 && !in_array($campaignId, $this->playedCampaigns)) {
            $this->playedCampaigns[] = $campaignId;
            session()->flash('success', 'Campaign marked as played for 5+ seconds!');
        }
    }

    /**
     * Check if campaign can be reposted
     */
    public function canRepost($campaignId): bool
    {
        return in_array($campaignId, $this->playedCampaigns) &&
            !in_array($campaignId, $this->repostedCampaigns);
    }

    /**
     * Check if campaign is currently playing
     */
    public function isPlaying($campaignId): bool
    {
        return isset($this->playingCampaigns[$campaignId]) && $this->playingCampaigns[$campaignId] === true;
    }

    /**
     * Get total play time for a campaign
     */
    public function getPlayTime($campaignId): int
    {
        $baseTime = $this->playTimes[$campaignId] ?? 0;

        if ($this->isPlaying($campaignId) && isset($this->playStartTimes[$campaignId])) {
            $currentSessionTime = now()->timestamp - $this->playStartTimes[$campaignId];
            return $baseTime + $currentSessionTime;
        }

        return $baseTime;
    }

    /**
     * Get remaining time until repost is enabled
     */
    public function getRemainingTime($campaignId): int
    {
        $playTime = $this->getPlayTime($campaignId);
        return max(0, 5 - $playTime);
    }

    /**
     * Handle repost action
     */
    public function repost($campaignId)
    {
        // Check if user can repost
        if (!$this->canRepost($campaignId)) {
            session()->flash('error', 'Please listen to the track for at least 5 seconds before reposting.');
            return;
        }

        try {
            // Add your repost logic here
            // Example: $this->campaignService->repost($campaignId);

            // Mark as reposted
            $this->repostedCampaigns[] = $campaignId;

            session()->flash('success', 'Track reposted successfully!');

        } catch (\Exception $e) {
            session()->flash('error', 'Failed to repost track. Please try again.');
        }
    }

    public function render()
    {
        return view('backend.user.campaign_management.campaigns.campaign');
    }
}
