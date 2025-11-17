<?php
// RedesignCampaign.php - Livewire Component

namespace App\Livewire\User\CampaignManagement;

use App\Models\Campaign as ModelsCampaign;
use App\Models\Playlist;
use App\Models\Track;
use Illuminate\Pagination\LengthAwarePaginator;
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

    //  #[Url(except: [])] 
    #[Url(keep: true)]
    public array $selectedGenres = [];

    public array $suggestedTags = [];

    #[Url(keep: true)]
    public string $trackType = 'all';
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
        $campaigns = $this->fetchCampaigns();

        return view('livewire.user.campaign-management.redesign-campaign', [
            'campaigns' => $campaigns,
            'trackingData' => [], // No longer needed as we use localStorage
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

        // Reset to page 1 when filter changes
        $this->resetPage($this->activeMainTab . 'Page');
    }

    public function updatedSearch(): void
    {
        $this->getAllTags();
    }
    // #[On('refreshCampaigns')]
    // public function fetchCampaigns()
    // {
    //     $explicitSelection = !empty($this->selectedGenres) && $this->selectedGenres !== ['all'];
    //     $explicitCleared = $this->selectedGenres === ['all'];
    //     $userDefaultGenres = user()->genres->pluck('genre')->toArray();

    //     // ALL tab count - campaigns with 'anyGenre' should be included in all filters
    //     $allCount = ModelsCampaign::whereRaw('(budget_credits - credits_spent) >= ?', user()->repost_price)
    //         ->whereHas('music', fn($q) => $q->whereNotNull('permalink_url'))
    //         ->when(
    //             $explicitSelection && $this->activeMainTab === 'all',
    //             fn($q) => $q->where(function ($query) {
    //                 $query->whereIn('target_genre', $this->selectedGenres)
    //                     ->orWhere('target_genre', 'anyGenre');
    //             })
    //         )
    //         ->withoutSelf()
    //         ->open()
    //         ->count();

    //     // RECOMMENDED PRO tab count
    //     $recommendedProCount = ModelsCampaign::whereRaw('(budget_credits - credits_spent) >= ?', user()->repost_price)
    //         ->whereHas('user', fn($q) => $q->isPro())
    //         ->whereHas('music', fn($q) => $q->whereNotNull('permalink_url'))
    //         ->when(
    //             $explicitSelection && $this->activeMainTab === 'recommendedPro',
    //             fn($q) => $q->where(function ($query) {
    //                 $query->whereIn('target_genre', $this->selectedGenres)
    //                     ->orWhere('target_genre', 'anyGenre');
    //             })
    //         )
    //         ->withoutSelf()
    //         ->open()
    //         ->count();

    //     // RECOMMENDED tab count
    //     $recommendedCountQuery = ModelsCampaign::whereRaw('(budget_credits - credits_spent) >= ?', user()->repost_price)
    //         ->whereHas('music', fn($q) => $q->whereNotNull('permalink_url'))
    //         ->withoutSelf()
    //         ->open();

    //     if ($this->activeMainTab === 'recommended') {
    //         if ($explicitSelection) {
    //             $recommendedCountQuery->where(function ($query) {
    //                 $query->whereIn('target_genre', $this->selectedGenres)
    //                     ->orWhere('target_genre', 'anyGenre');
    //             });
    //         } elseif (!$explicitCleared && !empty($userDefaultGenres)) {
    //             $recommendedCountQuery->where(function ($query) use ($userDefaultGenres) {
    //                 $query->whereIn('target_genre', $userDefaultGenres)
    //                     ->orWhere('target_genre', 'anyGenre');
    //             });
    //         }
    //     } else {
    //         if (!empty($userDefaultGenres)) {
    //             $recommendedCountQuery->where(function ($query) use ($userDefaultGenres) {
    //                 $query->whereIn('target_genre', $userDefaultGenres)
    //                     ->orWhere('target_genre', 'anyGenre');
    //             });
    //         }
    //     }

    //     $recommendedCount = $recommendedCountQuery->count();

    //     $this->totalCounts = [
    //         'recommendedPro' => $recommendedProCount,
    //         'recommended'    => $recommendedCount,
    //         'all'            => $allCount,
    //     ];

    //     // Build query for pagination
    //     $query = ModelsCampaign::whereRaw('(budget_credits - credits_spent) >= ?', user()->repost_price)
    //         ->with(['music', 'user', 'reposts', 'user.starredUsers'])
    //         ->whereHas('music', fn($q) => $q->whereNotNull('permalink_url'))
    //         ->withoutSelf()
    //         ->open();

    //     switch ($this->activeMainTab) {
    //         case 'recommendedPro':
    //             $query->whereHas('user', fn($q) => $q->isPro())
    //                 ->when($explicitSelection, fn($q) => $q->where(function ($subQuery) {
    //                     $subQuery->whereIn('target_genre', $this->selectedGenres)
    //                         ->orWhere('target_genre', 'anyGenre');
    //                 }));
    //             break;

    //         case 'recommended':
    //             if ($explicitSelection) {
    //                 $query->where(function ($subQuery) {
    //                     $subQuery->whereIn('target_genre', $this->selectedGenres)
    //                         ->orWhere('target_genre', 'anyGenre');
    //                 });
    //             } elseif (!$explicitCleared && !empty($userDefaultGenres)) {
    //                 $query->where(function ($subQuery) use ($userDefaultGenres) {
    //                     $subQuery->whereIn('target_genre', $userDefaultGenres)
    //                         ->orWhere('target_genre', 'anyGenre');
    //                 });
    //             }
    //             break;

    //         case 'all':
    //         default:
    //             $query->when($explicitSelection, fn($q) => $q->where(function ($subQuery) {
    //                 $subQuery->whereIn('target_genre', $this->selectedGenres)
    //                     ->orWhere('target_genre', 'anyGenre');
    //             }));
    //             break;
    //     }

    //     if (!empty($this->search)) {
    //         $query->whereHas('music', function ($q) {
    //             $q->where('title', 'like', '%' . $this->search . '%')
    //                 ->orWhere('description', 'like', '%' . $this->search . '%');
    //         });
    //     }

    //     if (!empty($this->selectedTags) && $this->selectedTags != 'all') {
    //         $query->whereHas('music', function ($q) {
    //             $q->where(function ($tagQuery) {
    //                 foreach ($this->selectedTags as $tag) {
    //                     $tagQuery->orWhere('tag_list', 'LIKE', "%$tag%");
    //                 }
    //             });
    //         });
    //     }

    //     if (!empty($this->searchMusicType) && $this->searchMusicType != 'all') {
    //         $query->where('music_type', 'like', "%{$this->searchMusicType}%");
    //     }

    //     return $query->latest()->paginate(10, ['*'], $this->activeMainTab . 'Page');
    // }

    private function applyMusicTypeFilter($query)
    {
        // dd($this->activeMainTab);
        if (!empty($this->trackType)) {
            if ($this->trackType === 'Track') {
                $query->where('music_type', Track::class);
            } else if ($this->trackType === 'Playlist') {
                $query->where('music_type', Playlist::class);
            }
        }
    }

    private function applyUserGenreFilter($query)
    {
        $query->where(function ($q) {
            $q->whereIn('target_genre', $this->selectedGenres)
                ->orWhere('target_genre', 'anyGenre');
        });
    }

    #[On('refreshCampaigns')]
    public function fetchCampaigns()
    {
        $explicitSelection = !empty($this->selectedGenres) && $this->selectedGenres !== ['all'];
        $explicitCleared   = $this->selectedGenres === ['all'];
        $userDefaultGenres = user()->genres->pluck('genre')->toArray();

        // --- ALL tab count (never default genre filter)
        $allCountQuery = ModelsCampaign::whereRaw('(budget_credits - credits_spent) >= ?', user()->repost_price)
            ->whereHas('music', fn($q) => $q->whereNotNull('permalink_url'))
            ->withoutSelf()
            ->open();
        if ($this->activeMainTab === 'all') {
            if ($explicitSelection) {
                $this->applyUserGenreFilter($allCountQuery);
            }
            if ($this->trackType !== 'all') {
                $this->applyMusicTypeFilter($allCountQuery);
            }
        }
        $allCount = $allCountQuery->count();

        // --- RECOMMENDED PRO count (never default genre filter)
        $recommendedProQuery = ModelsCampaign::whereRaw('(budget_credits - credits_spent) >= ?', user()->repost_price)
            ->whereHas('user', fn($q) => $q->isPro())
            ->whereHas('music', fn($q) => $q->whereNotNull('permalink_url'))
            ->withoutSelf()
            ->open();
        if ($this->activeMainTab === 'recommendedPro') {
            if ($explicitSelection) {
                $this->applyUserGenreFilter($recommendedProQuery);
            }
            $this->applyMusicTypeFilter($recommendedProQuery);
        }
        $recommendedProCount = $recommendedProQuery->count();

        // --- RECOMMENDED count (always applies default genres, but user selection overrides)
        $recommendedCountQuery = ModelsCampaign::whereRaw('(budget_credits - credits_spent) >= ?', user()->repost_price)
            ->whereHas('music', fn($q) => $q->whereNotNull('permalink_url'))
            ->withoutSelf()
            ->open();

        if ($this->activeMainTab === 'recommended' && $explicitSelection) {
            $this->applyUserGenreFilter($recommendedCountQuery);
            $this->applyMusicTypeFilter($recommendedCountQuery); // <-- apply music type filter here too
        } elseif (!$explicitSelection && !empty($userDefaultGenres)) {
            $recommendedCountQuery->where(function ($q) use ($userDefaultGenres) {
                $q->whereIn('target_genre', $userDefaultGenres)
                    ->orWhere('target_genre', 'anyGenre');
            });
            // music type filter only affects active tab, not default recommended counts
            if ($this->activeMainTab === 'recommended') {
                $this->applyMusicTypeFilter($recommendedCountQuery);
            }
        }
        $recommendedCount = $recommendedCountQuery->count();

        $this->totalCounts = [
            'all'            => $allCount,
            'recommendedPro' => $recommendedProCount,
            'recommended'    => $recommendedCount,
        ];

        /*
        |--------------------------------------------------------------------------
        | 2. Pagination query
        |--------------------------------------------------------------------------
        */
        $query = ModelsCampaign::whereRaw('(budget_credits - credits_spent) >= ?', user()->repost_price)
            ->with(['music', 'user', 'reposts', 'user.starredUsers'])
            ->whereHas('music', fn($q) => $q->whereNotNull('permalink_url'))
            ->withoutSelf()
            ->open();

        if ($explicitSelection) {
            $this->applyUserGenreFilter($query);
        } elseif ($this->activeMainTab === 'recommended' && !empty($userDefaultGenres)) {
            $query->where(function ($q) use ($userDefaultGenres) {
                $q->whereIn('target_genre', $userDefaultGenres)
                    ->orWhere('target_genre', 'anyGenre');
            });
        }

        // --- Active tab music type filter
        if ($this->trackType !== 'all') {
            $this->applyMusicTypeFilter($query);
        }

        // --- Search filters
        if (!empty($this->search)) {
            $query->whereHas('music', function ($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                    ->orWhere('description', 'like', '%' . $this->search . '%');
            });
        }

        if (!empty($this->selectedTags) && $this->selectedTags !== 'all') {
            $query->whereHas('music', function ($q) {
                $q->where(function ($tagQuery) {
                    foreach ($this->selectedTags as $tag) {
                        $tagQuery->orWhere('tag_list', 'LIKE', "%{$tag}%");
                    }
                });
            });
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
            $this->dispatch('alert', type: 'error', message: 'An error occurred while updating star mark status. Please try again later.');
        }
    }

    #[On('confirmRepost')]
    public function confirmRepost($campaignId)
    {
        // Get tracking from localStorage via JavaScript (client-side)
        // This method now only handles the actual repost logic

        $campaignId = (string) $campaignId;

        // TODO: Implement your actual repost logic here
        // Example: $this->campaignService->repostCampaign($campaignId, user()->urn);

        Log::info('Repost confirmed', [
            'campaign_id' => $campaignId,
            'user_urn' => user()->urn,
        ]);

        // Dispatch success message
        $this->dispatch('alert', type: 'success', message: 'Track reposted successfully!');

        // Dispatch browser event to update Alpine.js state
        $this->dispatch('repost-success', campaignId: $campaignId);
    }
}
