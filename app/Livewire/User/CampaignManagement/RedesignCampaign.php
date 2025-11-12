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

    public function getAllTrackTypes(): void
    {
        // This method seems unused - consider removing if not needed
        // If needed, it's already optimized with the computed property
    }

    public function toggleGenre(string $genre): void
    {
        $key = array_search($genre, $this->selectedGenres, true);

        if ($key !== false) {
            unset($this->selectedGenres[$key]);
            $this->selectedGenres = array_values($this->selectedGenres);
        } else {
            $this->selectedGenres[] = $genre;
            // Remove 'all' when specific genre is selected
            $this->selectedGenres = array_diff($this->selectedGenres, ['all']);
        }

        // Reset to 'all' if no genres selected
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
        // Determine explicit selection and user default genres
        $explicitSelection = !empty($this->selectedGenres) && $this->selectedGenres !== ['all'];
        $explicitCleared = $this->selectedGenres === ['all'];
        $userDefaultGenres = user()->genres->pluck('genre')->toArray();

        // ---------- COUNTS: Each tab has independent filtering logic ----------

        // ALL tab count - only filtered if explicitly selected on ALL tab
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

        // RECOMMENDED PRO tab count - only filtered if explicitly selected on RECOMMENDED PRO tab
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

        // RECOMMENDED tab count - complex logic
        $recommendedCountQuery = ModelsCampaign::whereRaw('(budget_credits - credits_spent) >= ?', user()->repost_price)
            ->whereHas('music', fn($q) => $q->whereNotNull('permalink_url'))
            ->withoutSelf()
            ->open()
            ->with(['music.user.userInfo', 'reposts', 'user.starredUsers']);

        if ($this->activeMainTab === 'recommended') {
            // When on recommended tab
            if ($explicitSelection) {
                // User explicitly selected genres
                $recommendedCountQuery->whereIn('target_genre', $this->selectedGenres);
            } elseif (!$explicitCleared && !empty($userDefaultGenres)) {
                // No explicit selection and not cleared = apply user default genres
                $recommendedCountQuery->whereIn('target_genre', $userDefaultGenres);
            }
            // If explicitCleared, show all (no filter)
        } else {
            // When NOT on recommended tab, always show count with user default genres
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

        // ---------- PAGINATION: build the displayed list according to active tab ----------
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

        // Apply search filter
        if (!empty($this->search)) {
            $query->whereHas('music', function ($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                    ->orWhere('description', 'like', '%' . $this->search . '%');
            });
        }

        // Apply tag filters
        if (!empty($this->selectedTags) && $this->selectedTags != 'all') {
            $query->whereHas('music', function ($q) {
                $q->where(function ($tagQuery) {
                    foreach ($this->selectedTags as $tag) {
                        $tagQuery->orWhere('tag_list', 'LIKE', "%$tag%");
                    }
                });
            });
        }

        // Apply music type filter
        if (!empty($this->searchMusicType) && $this->searchMusicType != 'all') {
            $query->where('music_type', 'like', "%{$this->searchMusicType}%");
        }

        return $query->paginate(10);
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
}
