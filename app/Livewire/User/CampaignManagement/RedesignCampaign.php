<?php

namespace App\Livewire\User\CampaignManagement;

use App\Models\Campaign as ModelsCampaign;
use App\Models\Playlist;
use App\Models\Track;
use App\Models\UserAnalytics;
use Livewire\Attributes\Url;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;
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
    use WithPagination;

    #[Url]
    public string $search = '';

    #[Url]
    public string $activeMainTab = 'recommendedPro';

    #[Url(keep: true)]
    public array $selectedGenres = [];

    public array $suggestedTags = [];

    #[Url(keep: true)]
    public string $trackType = 'all';

    public array $totalCounts = [
        "recommendedPro" => 0,
        "recommended" => 0,
        "all" => 0,
    ];

    public bool $showCampaignCreator = false;
    public bool $pendingToggle = false;

    protected ?CampaignService $campaignService = null;
    protected ?TrackService $trackService = null;
    protected ?PlaylistService $playlistService = null;
    protected ?SoundCloudService $soundCloudService = null;
    protected ?AnalyticsService $analyticsService = null;
    protected ?MyCampaignService $myCampaignService = null;
    protected ?UserSettingsService $userSettingsService = null;
    protected ?StarredUserService $starredUserService = null;

    public function boot(
        CampaignService $campaignService,
        TrackService $trackService,
        PlaylistService $playlistService,
        SoundCloudService $soundCloudService,
        AnalyticsService $analyticsService,
        MyCampaignService $myCampaignService,
        UserSettingsService $userSettingsService,
        StarredUserService $starredUserService
    ): void {
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

        // Clear session tracking on fresh page load only
        if (!request()->hasHeader('X-Livewire')) {
            session()->forget('campaign_playback_tracking');
            Log::info('Campaign tracking cleared on fresh page load');
        }

        if (session()->get('repostedIds')) {
            session()->forget('repostedIds');
        }
    }

    public function render()
    {
        // Set default genres for recommended tab
        if ($this->activeMainTab === 'all' || $this->activeMainTab === 'recommendedPro') {
            $this->selectedGenres = !empty($this->selectedGenres) && $this->selectedGenres !== ['all']
                ? $this->selectedGenres
                : [];
        } else {
            $this->selectedGenres = !empty($this->selectedGenres)
                ? $this->selectedGenres
                : user()->genres->pluck('genre')->toArray();
        }

        $campaigns = $this->fetchCampaigns();

        return view('livewire.user.campaign-management.redesign-campaign', [
            'campaigns' => $campaigns,
        ]);
    }


    public function updatedTrackType()
    {
        // Reset to page 1 when filter changes
        $this->resetPage($this->activeMainTab . 'Page');
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
        $this->resetPage($this->activeMainTab . 'Page');
    }

    public function toggleGenre(string $genre): void
    {
        // Prevent double-click stacking
        if ($this->pendingToggle ?? false) {
            return;
        }

        $this->pendingToggle = true;

        try {
            $key = array_search($genre, $this->selectedGenres, true);

            if ($key !== false) {
                // Remove genre
                unset($this->selectedGenres[$key]);
                $this->selectedGenres = array_values($this->selectedGenres);
            } else {
                // Add genre
                $this->selectedGenres[] = $genre;
                // Remove 'all' if it exists when adding specific genres
                $this->selectedGenres = array_diff($this->selectedGenres, ['all']);
            }

            // If empty, set to 'all'
            if (empty($this->selectedGenres)) {
                $this->selectedGenres = ['all'];
            }

            // Reset to page 1
            $this->resetPage($this->activeMainTab . 'Page');

            // Dispatch reset event
            $this->dispatch('reset-widget-initiallized');
        } finally {
            $this->pendingToggle = false;
        }
    }

    public function updatedSearch(): void
    {
        $this->getAllTags();
        $this->resetPage($this->activeMainTab . 'Page');
        $this->dispatch('reset-widget-initiallized');
    }

    private function applyMusicTypeFilter($query)
    {
        if (!empty($this->trackType)) {
            if ($this->trackType === 'Track') {
                $query->where('music_type', Track::class);
            } else if ($this->trackType === 'Playlist') {
                $query->where('music_type', Playlist::class);
            }
        }
    }

    #[On('refreshCampaigns')]
    public function fetchCampaigns()
    {
        $this->soundCloudService->refreshUserTokenIfNeeded(user());
        $explicitSelection = !empty($this->selectedGenres) && $this->selectedGenres !== ['all'];
        $explicitCleared = $this->selectedGenres === ['all'];
        $userDefaultGenres = user()->genres->pluck('genre')->toArray();
        $repostedIds = session()->get('repostedIds', []);

        // ALL tab count - campaigns with 'anyGenre' should be included in all filters
        $allCountQuery = ModelsCampaign::whereRaw('(budget_credits - credits_spent) >= ?', user()->repost_price)
            ->whereHas('music', fn($q) => $q->whereNotNull('permalink_url'))
            ->when(
                $explicitSelection && $this->activeMainTab === 'all',
                fn($q) => $q->where(function ($query) {
                    $query->whereIn('target_genre', $this->selectedGenres);
                    // ->orWhere('target_genre', 'anyGenre');
                })
            )
            ->withoutSelf()
            ->withoutBannedUsers()
            ->open();

        // RepostedIds return array of campaign ids
        if (!empty($repostedIds)) {
            $allCountQuery->where(function ($query) use ($repostedIds) {
                // Show campaigns included in session override
                if (!empty($repostedIds)) {
                    $query->whereIn('id', $repostedIds);
                }

                // Or show campaigns that user has NOT reposted
                $query->orWhereDoesntHave('reposts', function ($q) {
                    $q->where('reposter_urn', user()->urn);
                });
            });
        } else {
            $allCountQuery->whereDoesntHave('reposts', function ($query) {
                $query->where('reposter_urn', user()->urn);
            });
        }

        if ($this->activeMainTab === 'all' && $this->trackType !== 'all') {
            $this->applyMusicTypeFilter($allCountQuery);
        }
        $allCount = $allCountQuery->count();

        // RECOMMENDED PRO tab count
        $recommendedProQuery = ModelsCampaign::whereRaw('(budget_credits - credits_spent) >= ?', user()->repost_price)
            ->whereHas('user', fn($q) => $q->isPro())
            ->whereHas('music', fn($q) => $q->whereNotNull('permalink_url'))
            ->when(
                $explicitSelection && $this->activeMainTab === 'recommendedPro',
                fn($q) => $q->where(function ($query) {
                    $query->whereIn('target_genre', $this->selectedGenres);
                    // ->orWhere('target_genre', 'anyGenre');
                })
            )
            ->withoutSelf()
            ->withoutBannedUsers()
            ->open();

        if (!empty($repostedIds)) {
            $recommendedProQuery->where(function ($query) use ($repostedIds) {
                // Show campaigns included in session override
                if (!empty($repostedIds)) {
                    $query->whereIn('id', $repostedIds);
                }

                // Or show campaigns that user has NOT reposted
                $query->orWhereDoesntHave('reposts', function ($q) {
                    $q->where('reposter_urn', user()->urn);
                });
            });
        } else {
            $recommendedProQuery->whereDoesntHave('reposts', function ($query) {
                $query->where('reposter_urn', user()->urn);
            });
        }

        if ($this->activeMainTab === 'recommendedPro' && $this->trackType !== 'all') {
            $this->applyMusicTypeFilter($recommendedProQuery);
        }
        $recommendedProCount = $recommendedProQuery->count();

        // RECOMMENDED tab count
        $recommendedCountQuery = ModelsCampaign::whereRaw('(budget_credits - credits_spent) >= ?', user()->repost_price)
            ->whereHas('music', fn($q) => $q->whereNotNull('permalink_url'))
            ->withoutSelf()
            ->withoutBannedUsers()
            ->open();

        if (!empty($repostedIds)) {
            $recommendedCountQuery->where(function ($query) use ($repostedIds) {
                // Show campaigns included in session override
                if (!empty($repostedIds)) {
                    $query->whereIn('id', $repostedIds);
                }

                // Or show campaigns that user has NOT reposted
                $query->orWhereDoesntHave('reposts', function ($q) {
                    $q->where('reposter_urn', user()->urn);
                });
            });
        } else {
            $recommendedCountQuery->whereDoesntHave('reposts', function ($query) {
                $query->where('reposter_urn', user()->urn);
            });
        }

        if ($this->activeMainTab === 'recommended') {
            if ($explicitSelection) {
                $recommendedCountQuery->where(function ($query) {
                    $query->whereIn('target_genre', $this->selectedGenres);
                    // ->orWhere('target_genre', 'anyGenre');
                });
            } elseif (!$explicitCleared && !empty($userDefaultGenres)) {
                $recommendedCountQuery->where(function ($query) use ($userDefaultGenres) {
                    $query->whereIn('target_genre', $userDefaultGenres);
                    // ->orWhere('target_genre', 'anyGenre');
                });
            }
        } else {
            if (!empty($userDefaultGenres)) {
                $recommendedCountQuery->where(function ($query) use ($userDefaultGenres) {
                    $query->whereIn('target_genre', $userDefaultGenres);
                    // ->orWhere('target_genre', 'anyGenre');
                });
            }
        }

        if ($this->activeMainTab === 'recommended' && $this->trackType !== 'all') {
            $this->applyMusicTypeFilter($recommendedCountQuery);
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
            ->withoutBannedUsers()
            ->open();
        if (!empty($repostedIds)) {
            $query->where(function ($que) use ($repostedIds) {
                // Show campaigns included in session override
                if (!empty($repostedIds)) {
                    $que->whereIn('id', $repostedIds);
                }

                // Or show campaigns that user has NOT reposted
                $que->orWhereDoesntHave('reposts', function ($q) {
                    $q->where('reposter_urn', user()->urn);
                });
            });
        } else {
            $query->whereDoesntHave('reposts', function ($q) {
                $q->where('reposter_urn', user()->urn);
            });
        }

        switch ($this->activeMainTab) {
            case 'recommendedPro':
                $query->whereHas('user', fn($q) => $q->isPro())
                    ->when($explicitSelection, fn($q) => $q->where(function ($subQuery) {
                        $subQuery->whereIn('target_genre', $this->selectedGenres);
                        // ->orWhere('target_genre', 'anyGenre');
                    }));
                break;

            case 'recommended':
                if ($explicitSelection) {
                    $query->where(function ($subQuery) {
                        $subQuery->whereIn('target_genre', $this->selectedGenres);
                        // ->orWhere('target_genre', 'anyGenre');
                    });
                } elseif (!$explicitCleared && !empty($userDefaultGenres)) {
                    $query->where(function ($subQuery) use ($userDefaultGenres) {
                        $subQuery->whereIn('target_genre', $userDefaultGenres);
                        // ->orWhere('target_genre', 'anyGenre');
                    });
                }
                break;

            case 'all':
            default:
                $query->when($explicitSelection, fn($q) => $q->where(function ($subQuery) {
                    $subQuery->whereIn('target_genre', $this->selectedGenres);
                    // ->orWhere('target_genre', 'anyGenre');
                }));
                break;
        }

        if ($this->trackType !== 'all') {
            $this->applyMusicTypeFilter($query);
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

        return $query->latest()->paginate(10, ['*'], $this->activeMainTab . 'Page');
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
            $this->dispatch('alert', type: 'error', message: 'An error occurred. Please try again.');
        }
    }

    #[On('confirmRepost')]
    public function confirmRepost($campaignId)
    {
        try {

            if (empty($campaignId)) {
                $this->dispatch('alert', type: 'error', message: 'Campaign ID is required.');
                return;
            }
            $this->dispatch('open-repost-modal', campaignId: encrypt($campaignId));
            $this->dispatch('callRepostAction', campaignId: encrypt($campaignId));

            // Log::info('Repost confirmed', [
            //     'campaign_id' => $campaignId,
            //     'user_urn' => user()->urn,
            // ]);

            // $this->dispatch('alert', type: 'success', message: 'Track reposted successfully!');
            // $this->dispatch('repost-success', campaignId: $campaignId);

            // // Refresh campaigns to update UI
            $this->dispatch('refreshCampaigns');
        } catch (\Exception $e) {
            Log::error('Error confirming repost: ' . $e->getMessage(), [
                'campaign_id' => $campaignId,
                'user_urn' => user()->urn,
                'exception' => $e,
            ]);
            $this->dispatch('alert', type: 'error', message: 'Failed to repost. Please try again.');
        }
    }

    #[On('updatePlayCount')]
    public function updatePlayCount($campaignId)
    {
        dd($campaignId);
        $campaign = $this->campaignService->getCampaign(encrypt($campaignId));
        $campaign->load('music');
        $music = $campaign->music;
        dd($campaign, $music);
        $response = $this->analyticsService->recordAnalytics(source: $this->track, actionable: $campaign, type: UserAnalytics::TYPE_PLAY, genre: $campaign->target_genre);
        if ($response != false || $response != null) {
            $campaign->increment('playback_count');
        }
    }
}
