<?php

namespace App\Livewire\User\CampaignManagement;

use App\Models\Campaign as ModelsCampaign;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Url;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;
use App\Services\PlaylistService;
use App\Services\SoundCloud\SoundCloudService;
use App\Services\TrackService;
use App\Services\User\AnalyticsService;
use App\Services\User\CampaignManagement\CampaignService;
use App\Services\User\CampaignManagement\MyCampaignService;
use App\Services\User\StarredUserService;
use App\Services\User\UserSettingsService;

class RedesignCampaign extends Component
{
    #[Url]
    public string $search = '';

    #[Url]
    public string $activeMainTab = 'recommendedPro';

    public array $suggestedTags = [];
    public array $selectedGenres = [];
    public string $trackTypeFilter = 'all';
    public array $totalCounts = [
        "recommendedPro" => 12,
        "recommended" => 10,
        "all" => 50,
    ];

    public bool $showCampaignCreator = false;

    // Track playback tracking
    public array $trackPlaybackData = [];

    protected ?CampaignService $campaignService = null;
    protected ?TrackService $trackService = null;
    protected ?PlaylistService $playlistService = null;
    protected ?SoundCloudService $soundCloudService = null;
    protected ?AnalyticsService $analyticsService = null;
    protected ?MyCampaignService $myCampaignService = null;
    protected ?UserSettingsService $userSettingsService = null;
    protected ?StarredUserService $starredUserService = null;

    public function boot(CampaignService $campaignService, TrackService $trackService, PlaylistService $playlistService, SoundCloudService $soundCloudService, AnalyticsService $analyticsService, MyCampaignService $myCampaignService, UserSettingsService $userSettingsService, StarredUserService $starredUserService): void
    {
        $this->campaignService = $campaignService;
        $this->trackService = $trackService;
        $this->playlistService = $playlistService;
        $this->soundCloudService = $soundCloudService;
        $this->analyticsService = $analyticsService;
        $this->myCampaignService = $myCampaignService;
        $this->userSettingsService = $userSettingsService;
        $this->starredUserService = $starredUserService;
    }

    public function mount(): void
    {
        $this->activeMainTab = request()->query('tab', 'recommendedPro');
        
        // Initialize tracking data from session or create new
        $this->trackPlaybackData = session()->get('track_playback_data', []);
    }

    public function render()
    {
        if ($this->activeMainTab === 'all' || $this->activeMainTab === 'recommendedPro') {
            $this->selectedGenres = !empty($this->selectedGenres) && $this->selectedGenres !== ['all'] ? $this->selectedGenres : [];
        } else {
            $this->selectedGenres = !empty($this->selectedGenres) ? $this->selectedGenres : user()->genres->pluck('genre')->toArray();
        }
        $campaigns =  $this->fetchCampaigns();
        return view('livewire.user.campaign-management.redesign-campaign', [
            'campaigns' => $campaigns,
        ]);
    }

    #[Computed]
    public function userTracks(): Collection
    {
        return once(fn() => $this->trackService->getTracks()
            ->where('user_urn', user()->urn));
    }

    public function getAllTags(): void
    {
        if (empty($this->search)) {
            $this->suggestedTags = [];
            return;
        }

        $searchLower = strtolower($this->search);

        $this->suggestedTags = $this->trackService->getTracks()
            ->pluck('tag_list')
            ->filter()
            ->flatMap(fn($tags) => array_map('trim', explode(',', $tags)))
            ->unique()
            ->filter(fn($tag) => str_contains(strtolower($tag), $searchLower))
            ->take(10)
            ->values()
            ->all();
    }

    public function selectTag(string $tag): void
    {
        $this->search = $tag;
        $this->suggestedTags = [];
    }

    public function toggleGenre(string $genre): void
    {
        $key = array_search($genre, $this->selectedGenres, true);

        if ($key !== false) {
            unset($this->selectedGenres[$key]);
            $this->selectedGenres = array_values($this->selectedGenres);
        } else {
            $this->selectedGenres[] = $genre;
            $this->selectedGenres = array_diff($this->selectedGenres, ['all']);
        }

        if (empty($this->selectedGenres)) {
            $this->selectedGenres = ['all'];
        }
    }

    public function updatedSearch(): void
    {
        $this->getAllTags();
    }

    #[On('refreshCampaigns')]
    public function fetchCampaigns()
    {
        $explicitSelection = !empty($this->selectedGenres) && $this->selectedGenres !== ['all'];
        $explicitCleared = $this->selectedGenres === ['all'];
        $userDefaultGenres = user()->genres->pluck('genre')->toArray();

        // ALL tab count
        $allCount = ModelsCampaign::whereRaw('(budget_credits - credits_spent) >= ?', user()->repost_price)
            ->whereHas('music', fn($q) => $q->whereNotNull('permalink_url'))
            ->when(
                $explicitSelection && $this->activeMainTab === 'all',
                fn($q) => $q->whereIn('target_genre', $this->selectedGenres)
            )
            ->withoutSelf()
            ->open() 
            ->with(['music.user.userInfo', 'reposts', 'user.starredUsers'])
            ->count();

        // RECOMMENDED PRO tab count
        $recommendedProCount = ModelsCampaign::whereRaw('(budget_credits - credits_spent) >= ?', user()->repost_price)
            ->whereHas('user', fn($q) => $q->isPro())
            ->whereHas('music', fn($q) => $q->whereNotNull('permalink_url'))
            ->when(
                $explicitSelection && $this->activeMainTab === 'recommendedPro',
                fn($q) => $q->whereIn('target_genre', $this->selectedGenres)
            )
            ->withoutSelf()
            ->open()
            ->with(['music.user.userInfo', 'reposts', 'user.starredUsers'])
            ->count();

        // RECOMMENDED tab count
        $recommendedCountQuery = ModelsCampaign::whereRaw('(budget_credits - credits_spent) >= ?', user()->repost_price)
            ->whereHas('music', fn($q) => $q->whereNotNull('permalink_url'))
            ->withoutSelf()
            ->open()
            ->with(['music.user.userInfo', 'reposts', 'user.starredUsers']);

        if ($this->activeMainTab === 'recommended') {
            if ($explicitSelection) {
                $recommendedCountQuery->whereIn('target_genre', $this->selectedGenres);
            } elseif (!$explicitCleared && !empty($userDefaultGenres)) {
                $recommendedCountQuery->whereIn('target_genre', $userDefaultGenres);
            }
        } else {
            if (!empty($userDefaultGenres)) {
                $recommendedCountQuery->whereIn('target_genre', $userDefaultGenres);
            }
        }

        $recommendedCount = $recommendedCountQuery->count();

        $this->totalCounts = [
            'recommendedPro' => $recommendedProCount,
            'recommended'    => $recommendedCount,
            'all'            => $allCount,
        ];

        // Build query for pagination
        $query = ModelsCampaign::whereRaw('(budget_credits - credits_spent) >= ?', user()->repost_price)
            ->with(['music', 'user', 'reposts', 'user.starredUsers'])
            ->whereHas('music', fn($q) => $q->whereNotNull('permalink_url'))
            ->withoutSelf()
            ->open();

        switch ($this->activeMainTab) {
            case 'recommendedPro':
                $query->whereHas('user', fn($q) => $q->isPro())
                    ->when($explicitSelection, fn($q) => $q->whereIn('target_genre', $this->selectedGenres));
                break;

            case 'recommended':
                if ($explicitSelection) {
                    $query->whereIn('target_genre', $this->selectedGenres);
                } elseif (!$explicitCleared && !empty($userDefaultGenres)) {
                    $query->whereIn('target_genre', $userDefaultGenres);
                }
                break;

            case 'all':
            default:
                $query->when($explicitSelection, fn($q) => $q->whereIn('target_genre', $this->selectedGenres));
                break;
        }

        if (!empty($this->search)) {
            $query->whereHas('music', function ($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                    ->orWhere('description', 'like', '%' . $this->search . '%');
            });
        }

        if (!empty($this->selectedTags) && $this->selectedTags != 'all') {
            $query->whereHas('music', function ($q) {
                $q->where(function ($tagQuery) {
                    foreach ($this->selectedTags as $tag) {
                        $tagQuery->orWhere('tag_list', 'LIKE', "%$tag%");
                    }
                });
            });
        }

        if (!empty($this->searchMusicType) && $this->searchMusicType != 'all') {
            $query->where('music_type', 'like', "%{$this->searchMusicType}%");
        }

        return $query->paginate(10);
    }

    #[On('trackPlaybackUpdate')]
    public function updateTrackPlayback($campaignId, $action, $actualPlayTime = 0)
    {
        $campaignId = (string) $campaignId;
        
        if (!isset($this->trackPlaybackData[$campaignId])) {
            $this->trackPlaybackData[$campaignId] = [
                'played' => false,
                'actual_play_time' => 0,
                'total_play_time' => 0,
                'is_eligible' => false,
                'play_count' => 0,
                'last_action' => null,
                'created_at' => now()->timestamp,
            ];
        }

        $this->trackPlaybackData[$campaignId]['last_action'] = $action;
        
        if ($action === 'play') {
            $this->trackPlaybackData[$campaignId]['played'] = true;
            $this->trackPlaybackData[$campaignId]['play_count']++;
        }
        
        if ($action === 'progress' && $actualPlayTime > 0) {
            $this->trackPlaybackData[$campaignId]['actual_play_time'] = $actualPlayTime;
            $this->trackPlaybackData[$campaignId]['total_play_time'] = $actualPlayTime;
            
            // Check if eligible for repost (5 seconds)
            if ($actualPlayTime >= 5) {
                $this->trackPlaybackData[$campaignId]['is_eligible'] = true;
            }
        }

        // Save to session
        session()->put('track_playback_data', $this->trackPlaybackData);
        
        // Dispatch event to frontend to update button state
        $this->dispatch('playbackStateUpdated', [
            'campaignId' => $campaignId,
            'isEligible' => $this->trackPlaybackData[$campaignId]['is_eligible'],
            'actualPlayTime' => $this->trackPlaybackData[$campaignId]['actual_play_time'],
        ]);
    }

    #[On('clearTrackingData')]
    public function clearTrackingData()
    {
        $this->trackPlaybackData = [];
        session()->forget('track_playback_data');
        
        $this->dispatch('trackingDataCleared');
    }

    #[On('starMarkUser')]
    public function starMarkUser($userUrn)
    {
        try {
            $status = $this->starredUserService->toggleStarMark(user()->urn, $userUrn);
            if (!$status) {
                $this->dispatch('alert', type: 'error', message: 'You cannot star mark yourself.');
            }
        } catch (\Exception $e) {
            Log::error('Error in starMarkUser: ' . $e->getMessage(), [
                'user_urn' => user()->urn ?? 'N/A',
                'target_user_urn' => $userUrn ?? 'N/A',
                'exception' => $e,
            ]);
            $this->dispatch('alert', type: 'error', message: 'An error occurred while updating star mark status. Please try again later.');
        }
    }

    public function getTrackPlaybackStatus($campaignId)
    {
        return $this->trackPlaybackData[$campaignId] ?? null;
    }
}