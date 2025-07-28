<?php

namespace App\Livewire\User\CampaignManagement;

use App\Models\CreditTransaction;
use App\Models\Playlist;
use App\Models\Repost;
use App\Models\Track;
use App\Services\User\CampaignManagement\CampaignService;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Throwable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class Campaign extends Component
{
    protected ?CampaignService $campaignService = null;
    public $featuredCampaigns;
    public $campaigns;

    #[Locked]
    protected string $baseUrl = 'https://api.soundcloud.com';

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

    public $playcount = false;

    // Listeners for browser events
    protected $listeners = [
        'audioPlay' => 'handleAudioPlay',
        'audioPause' => 'handleAudioPause',
        'audioTimeUpdate' => 'handleAudioTimeUpdate',
        'audioEnded' => 'handleAudioEnded'
    ];
    public function boot(CampaignService $campaignService)
    {
        $this->campaignService = $campaignService;
        $allowed_target_credits = repostPrice(user());
        $this->featuredCampaigns = $this->campaignService->getCampaigns()
            // ->where('cost_per_repost', $allowed_target_credits)
            // ->featured()
            // ->withoutSelf()
            ->with(['music.user.userInfo', 'reposts']) // Keep 'reposts' if you need it for other purposes, otherwise it can be removed
            ->whereDoesntHave('reposts', function ($query) { // Use whereDoesntHave
                $query->where('reposter_urn', user()->urn);
            })
            ->get();
        $this->campaigns = $this->campaignService->getCampaigns()
            // ->where('cost_per_repost', $allowed_target_credits)
            // ->notFeatured()
            // ->withoutSelf()
            ->with(['music.user.userInfo', 'reposts']) // Keep 'reposts' if you need it for other purposes, otherwise it can be removed
            ->whereDoesntHave('reposts', function ($query) { // Use whereDoesntHave
                $query->where('reposter_urn', user()->urn);
            })
            ->get();
    }


    public function mount()
    {
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
        $canRepost = in_array($campaignId, $this->playedCampaigns) &&
            !in_array($campaignId, $this->repostedCampaigns);

        if ($canRepost && !$this->playcount) {
            $campaign = $this->campaignService->getCampaign(encrypt($campaignId));
            $campaign->increment('playback_count');
            $this->playcount = true; // Set playcount to true if repost is allowed
        }
        return $canRepost;
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
        try {
            if (!$this->canRepost($campaignId)) {
                session()->flash('error', 'You cannot repost this campaign. Please play it for at least 5 seconds first.');
                return;
            }
            $currentUserUrn = user()->urn;

            // Check if the current user owns the campaign
            if ($this->campaignService->getCampaigns()->where('id', $campaignId)->where('user_urn', $currentUserUrn)->exists()) {
                session()->flash('error', 'You cannot repost your own campaign.');
                return;
            }

            // Check if the user has already reposted this specific campaign
            if (
                Repost::where('reposter_urn', $currentUserUrn)
                    ->where('campaign_id', $campaignId)
                    ->exists()
            ) {
                session()->flash('error', 'You have already reposted this campaign.');
                return;
            }

            // Find the campaign and eager load its music and the music's user
            // $campaign = Campaign::with('music.user')->findOrFail($campaignId);
            $campaign = $this->campaignService->getCampaign(encrypt($campaignId))->load('music.user.userInfo');

            // Ensure music is associated with the campaign
            if (!$campaign->music) {
                session()->flash('error', 'Track or Playlist not found for this campaign.');
                return;
            }

            $soundcloudRepostId = null;

            // Prepare HTTP client with authorization header
            $httpClient = Http::withHeaders([
                'Authorization' => 'OAuth ' . user()->token,
            ]);

            // Determine the SoundCloud API endpoint based on music type
            switch ($campaign->music_type) {
                case Track::class:
                    $response = $httpClient->post("{$this->baseUrl}/reposts/tracks/{$campaign->music->urn}");
                    break;
                case Playlist::class:
                    $response = $httpClient->post("{$this->baseUrl}/reposts/playlists/{$campaign->music->urn}");
                    break;
                default:
                    session()->flash('error', 'Invalid music type specified for the campaign.');
                    return;
            }


            if ($response->successful()) {
                // If SoundCloud returns a repost ID, capture it (example, adjust based on actual SoundCloud API response)
                $soundcloudRepostId = $response->json('id');
                $this->campaignService->syncReposts($campaign, $currentUserUrn, $soundcloudRepostId);
                session()->flash('success', 'Campaign music reposted successfully.');
            } else {
                // Log the error response from SoundCloud for debugging
                Log::error("SoundCloud Repost Failed: " . $response->body(), [
                    'campaign_id' => $campaignId,
                    'user_urn' => $currentUserUrn,
                    'status' => $response->status(),
                ]);
                session()->flash('error', 'Failed to repost campaign music to SoundCloud. Please try again.');


            }

        } catch (Throwable $e) {
            Log::error("Error in repost method: " . $e->getMessage(), [
                'exception' => $e,
                'campaign_id_input' => $campaignId,
                'user_urn' => user()->urn ?? 'N/A',
            ]);
            session()->flash('error', 'An unexpected error occurred. Please try again later.');
            return;
        }
    }

    public function render()
    {
        return view('backend.user.campaign_management.campaigns.campaign');
    }
}
