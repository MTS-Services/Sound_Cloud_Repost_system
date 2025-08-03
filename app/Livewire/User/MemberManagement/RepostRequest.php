<?php

namespace App\Livewire\User\MemberManagement;

use App\Models\RepostRequest as ModelsRepostRequest;
use App\Models\Repost;
use App\Models\Track;
use App\Models\Playlist;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Throwable;

class RepostRequest extends Component
{
    public $repostRequests;
    public $track;
    public $activeMainTab = 'pending'; // Default tab

    #[Locked]
    protected string $baseUrl = 'https://api.soundcloud.com';
    public $playingRequests = [];
    public $playStartTimes = [];
    public $playTimes = [];
    public $playedRequests = [];
    public $repostedRequests = [];
    public $playCount = false;

    // Listeners for browser events
    protected $listeners = [
        'audioPlay' => 'handleAudioPlay',
        'audioPause' => 'handleAudioPause',
        'audioTimeUpdate' => 'handleAudioTimeUpdate',
        'audioEnded' => 'handleAudioEnded'
    ];

    public function mount()
    {
        $this->dataLoad();

        // Initialize tracking arrays
        foreach ($this->repostRequests as $request) {
            $this->playTimes[$request->id] = 0;
        }
    }

    /**
     * Handle audio play event
     */
    public function handleAudioPlay($requestId)
    {
        $this->playingRequests[$requestId] = true;
        $this->playStartTimes[$requestId] = now()->timestamp;
    }

    /**
     * Handle audio pause event
     */
    public function handleAudioPause($requestId)
    {
        $this->updatePlayTime($requestId);
        unset($this->playingRequests[$requestId]);
        unset($this->playStartTimes[$requestId]);
    }

    /**
     * Handle audio time update event
     */
    public function handleAudioTimeUpdate($requestId, $currentTime)
    {
        if ($currentTime >= 5 && !in_array($requestId, $this->playedRequests)) {
            $this->playedRequests[] = $requestId;
            $this->dispatch('requestPlayedEnough', $requestId);
        }
    }

    /**
     * Handle audio ended event
     */
    public function handleAudioEnded($requestId)
    {
        $this->handleAudioPause($requestId);
    }

    /**
     * Update play time for a request
     */
    private function updatePlayTime($requestId)
    {
        if (isset($this->playStartTimes[$requestId])) {
            $playDuration = now()->timestamp - $this->playStartTimes[$requestId];
            $this->playTimes[$requestId] = ($this->playTimes[$requestId] ?? 0) + $playDuration;

            if ($this->playTimes[$requestId] >= 5 && !in_array($requestId, $this->playedRequests)) {
                $this->playedRequests[] = $requestId;
                $this->dispatch('requestPlayedEnough', $requestId);
            }
        }
    }

    /**
     * Polling method to update play times for currently playing requests
     */
    public function updatePlayingTimes()
    {
        foreach ($this->playingRequests as $requestId => $isPlaying) {
            if ($isPlaying && isset($this->playStartTimes[$requestId])) {
                $playDuration = now()->timestamp - $this->playStartTimes[$requestId];
                $totalPlayTime = ($this->playTimes[$requestId] ?? 0) + $playDuration;

                if ($totalPlayTime >= 5 && !in_array($requestId, $this->playedRequests)) {
                    $this->playedRequests[] = $requestId;
                    $this->dispatch('requestPlayedEnough', $requestId);
                }
            }
        }
    }

    /**
     * Start playing a request manually
     */
    public function startPlaying($requestId)
    {
        $this->handleAudioPlay($requestId);
    }

    /**
     * Stop playing a request manually
     */
    public function stopPlaying($requestId)
    {
        $this->handleAudioPause($requestId);
    }

    /**
     * Simulate audio progress (for testing without actual audio)
     */
    public function simulateAudioProgress($requestId, $seconds = 1)
    {
        if (!isset($this->playTimes[$requestId])) {
            $this->playTimes[$requestId] = 0;
        }

        $this->playTimes[$requestId] += $seconds;

        if ($this->playTimes[$requestId] >= 5 && !in_array($requestId, $this->playedRequests)) {
            $this->playedRequests[] = $requestId;
            session()->flash('success', 'Request marked as played for 5+ seconds!');
        }
    }

    /**
     * Check if request can be reposted
     */
    public function canRepost($requestId): bool
    {
        $request = $this->repostRequests->find($requestId);

        if (!$request) {
            return false;
        }

        // Can only repost pending requests
        if ($request->status != ModelsRepostRequest::STATUS_PENDING) {
            return false;
        }

        // Check if already reposted
        if (in_array($requestId, $this->repostedRequests)) {
            return false;
        }

        // Must have played for 5+ seconds
        $canRepost = in_array($requestId, $this->playedRequests);

        if ($canRepost && !$this->playCount) {
            // $request->increment('playback_count');
            $this->playCount = true;
        }

        return $canRepost;
    }

    /**
     * Check if request is currently playing
     */
    public function isPlaying($requestId): bool
    {
        return isset($this->playingRequests[$requestId]) && $this->playingRequests[$requestId] === true;
    }

    /**
     * Get total play time for a request
     */
    public function getPlayTime($requestId): int
    {
        $baseTime = $this->playTimes[$requestId] ?? 0;

        if ($this->isPlaying($requestId) && isset($this->playStartTimes[$requestId])) {
            $currentSessionTime = now()->timestamp - $this->playStartTimes[$requestId];
            return $baseTime + $currentSessionTime;
        }

        return $baseTime;
    }

    /**
     * Get remaining time until repost is enabled
     */
    public function getRemainingTime($requestId): int
    {
        $playTime = $this->getPlayTime($requestId);
        return max(0, 5 - $playTime);
    }

    /**
     * Handle repost action
     */
    public function repost($requestId)
    {
        try {
            if (!$this->canRepost($requestId)) {
                session()->flash('error', 'You cannot repost this request. Please play it for at least 5 seconds first.');
                return;
            }

            $currentUserUrn = user()->urn;

            // Check if the user has already reposted this specific request
            if (
                Repost::where('reposter_urn', $currentUserUrn)
                ->where('repost_request_id', $requestId)
                ->exists()
            ) {
                session()->flash('error', 'You have already reposted this request.');
                return;
            }

            // Find the request and load its track
            $request = ModelsRepostRequest::findOrFail($requestId)->load('track');

            // Ensure track is associated with the request
            if (!$request->track) {
                session()->flash('error', 'Track not found for this request.');
                return;
            }

            $soundcloudRepostId = null;

            // Prepare HTTP client with authorization header
            $httpClient = Http::withHeaders([
                'Authorization' => 'OAuth ' . user()->token,
            ]);

            // Repost the track to SoundCloud
            $response = $httpClient->post("{$this->baseUrl}/reposts/playlists/{$request->track_urn}");

            if ($response->successful()) {
                // If SoundCloud returns a repost ID, capture it
                $soundcloudRepostId = $response->json('id');

                // Create repost record
                Repost::create([
                    'reposter_urn' => $currentUserUrn,
                    'repost_request_id' => $requestId,
                    'campaign_id' => $request->campaign_id,
                    'music_id' => $request->track->id,
                    'music_type' => Track::class,
                    'soundcloud_repost_id' => $soundcloudRepostId,
                    // Add other necessary fields based on your Repost model
                ]);

                // Mark request as completed
                $request->update([
                    'status' => ModelsRepostRequest::STATUS_COMPLETED,
                    'completed_at' => now(),
                    'responded_at' => now(),
                ]);

                // Mark as reposted in component
                $this->repostedRequests[] = $requestId;

                session()->flash('success', 'Request reposted successfully.');
            } else {
                // Log the error response from SoundCloud for debugging
                Log::error("SoundCloud Repost Failed: " . $response->body(), [
                    'request_id' => $requestId,
                    'user_urn' => $currentUserUrn,
                    'status' => $response->status(),
                ]);
                session()->flash('error', 'Failed to repost to SoundCloud. Please try again.');
            }
        } catch (Throwable $e) {
            Log::error("Error in repost method: " . $e->getMessage(), [
                'exception' => $e,
                'request_id' => $requestId,
                'user_urn' => user()->urn ?? 'N/A',
            ]);
            session()->flash('error', 'An unexpected error occurred. Please try again later.');
            return;
        }
    }

    public function declineRepostRequest($requestId)
    {
        try {
            $request = ModelsRepostRequest::findOrFail($requestId);
            $request->update([
                'status' => ModelsRepostRequest::STATUS_DECLINE,
                'rejection_reason' => 'Declined by user',
                'responded_at' => now(),
            ]);
            $this->dataLoad();
            session()->flash('success', 'Repost request declined successfully.');
        } catch (Throwable $e) {
            Log::error("Error declining repost request: " . $e->getMessage(), [
                'exception' => $e,
                'request_id' => $requestId,
                'user_urn' => user()->urn ?? 'N/A',
            ]);
            session()->flash('error', 'Failed to decline repost request. Please try again.');
        }
    }
    public function setActiveTab($tab)
    {
        $this->activeMainTab = $tab;
        $this->dataLoad();
    }
    public function dataLoad()
    {
        $query = ModelsRepostRequest::with(['track', 'targetUser'])->where('requester_urn', user()->urn);

        switch ($this->activeMainTab) {
            case 'pending':
                $query->where('status', ModelsRepostRequest::STATUS_PENDING);
                break;
            case 'approved':
                $query->where('status', ModelsRepostRequest::STATUS_APPROVED);
                break;
            case 'declined':
                $query->where('status', ModelsRepostRequest::STATUS_DECLINE);
                break;
            case 'expired':
                $query->where('status', ModelsRepostRequest::STATUS_EXPIRED);
                break;
            case 'completed':
                $query->where('status', ModelsRepostRequest::STATUS_COMPLETED);
                break;
        }

        // Order by created_at desc and paginate
        return $this->repostRequests = $query->orderBy('sort_order', 'asc')->take(10)->get();
    }

    public function render()
    {
        return view('livewire.user.member-management.repost-request');
    }
}
