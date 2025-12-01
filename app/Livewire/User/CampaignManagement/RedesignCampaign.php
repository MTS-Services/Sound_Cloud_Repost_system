<?php

namespace App\Livewire\User\CampaignManagement;

use App\Models\Campaign as ModelsCampaign;
use App\Models\Playlist;
use App\Models\Track;
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

    protected $queryString = [
        'selectedGenres' => ['except' => []],
    ];

    public array $suggestedTags = [];

    #[Url(keep: true)]
    public string $trackType = 'all';

    public array $totalCounts = [
        "recommendedPro" => 0,
        "recommended" => 0,
        "all" => 0,
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

    public function updated($property)
    {
        $this->dispatch('reset-initialize'); //@file-input-reset.window="init()"
        Log::info('Reset initialize');
    }

    public function updatedTrackType()
    {
        // Reset to page 1 when filter changes
        $this->resetPage($this->activeMainTab . 'Page');
    }

    public function updatedSelectedGenres()
    {
        // Reset to page 1 when genres change
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
        $this->resetPage($this->activeMainTab . 'Page');
    }

    // private function applyMusicTypeFilter($query)
    // {
    //     if ($this->trackType === Track::class) {
    //         $query->where('music_type', Track::class);
    //     } elseif ($this->trackType === Playlist::class) {
    //         $query->where('music_type', Playlist::class);
    //     }
    //     // 'all' doesn't apply any filter
    // }

    // private function applyUserGenreFilter($query)
    // {
    //     $query->where(function ($q) {
    //         $q->whereIn('target_genre', $this->selectedGenres)
    //             ->orWhere('target_genre', 'anyGenre');
    //     });
    // }

    // #[On('refreshCampaigns')]
    // public function fetchCampaigns()
    // {
    //     $explicitSelection = !empty($this->selectedGenres) && $this->selectedGenres !== ['all'];
    //     $explicitCleared   = $this->selectedGenres === ['all'];
    //     $userDefaultGenres = user()->genres->pluck('genre')->toArray();

    //     // Base query for all counts
    //     $baseQuery = ModelsCampaign::whereRaw('(budget_credits - credits_spent) >= ?', [user()->repost_price])
    //         ->whereHas('music', fn($q) => $q->whereNotNull('permalink_url'))
    //         ->withoutSelf()
    //         ->open();

    //     // --- ALL tab count
    //     $allCountQuery = clone $baseQuery;
    //     if ($this->activeMainTab === 'all') {
    //         if ($explicitSelection) {
    //             $this->applyUserGenreFilter($allCountQuery);
    //         }
    //         if ($this->trackType !== 'all') {
    //             $this->applyMusicTypeFilter($allCountQuery);
    //         }
    //     }
    //     $allCount = $allCountQuery->count();

    //     // --- RECOMMENDED PRO count
    //     $recommendedProQuery = clone $baseQuery;
    //     $recommendedProQuery->whereHas('user', fn($q) => $q->isPro());

    //     if ($this->activeMainTab === 'recommendedPro') {
    //         if ($explicitSelection) {
    //             $this->applyUserGenreFilter($recommendedProQuery);
    //         }
    //         if ($this->trackType !== 'all') {
    //             $this->applyMusicTypeFilter($recommendedProQuery);
    //         }
    //     }
    //     $recommendedProCount = $recommendedProQuery->count();

    //     // --- RECOMMENDED count
    //     $recommendedCountQuery = clone $baseQuery;

    //     if ($this->activeMainTab === 'recommended') {
    //         if ($explicitSelection) {
    //             $this->applyUserGenreFilter($recommendedCountQuery);
    //         } elseif (!$explicitCleared && !empty($userDefaultGenres)) {
    //             $recommendedCountQuery->where(function ($q) use ($userDefaultGenres) {
    //                 $q->whereIn('target_genre', $userDefaultGenres)
    //                     ->orWhere('target_genre', 'anyGenre');
    //             });
    //         }

    //         if ($this->trackType !== 'all') {
    //             $this->applyMusicTypeFilter($recommendedCountQuery);
    //         }
    //     } elseif (!$explicitSelection && !empty($userDefaultGenres)) {
    //         $recommendedCountQuery->where(function ($q) use ($userDefaultGenres) {
    //             $q->whereIn('target_genre', $userDefaultGenres)
    //                 ->orWhere('target_genre', 'anyGenre');
    //         });
    //     }

    //     $recommendedCount = $recommendedCountQuery->count();

    //     $this->totalCounts = [
    //         'all'            => $allCount,
    //         'recommendedPro' => $recommendedProCount,
    //         'recommended'    => $recommendedCount,
    //     ];

    //     // --- Pagination query
    //     $query = ModelsCampaign::whereRaw('(budget_credits - credits_spent) >= ?', [user()->repost_price])
    //         ->with(['music', 'user', 'reposts', 'user.starredUsers'])
    //         ->whereHas('music', fn($q) => $q->whereNotNull('permalink_url'))
    //         ->withoutSelf()
    //         ->open();

    //     // Apply filters based on active tab
    //     switch ($this->activeMainTab) {
    //         case 'recommendedPro':
    //             $query->whereHas('user', fn($q) => $q->isPro());
    //             if ($explicitSelection) {
    //                 $this->applyUserGenreFilter($query);
    //             }
    //             break;

    //         case 'recommended':
    //             if ($explicitSelection) {
    //                 $this->applyUserGenreFilter($query);
    //             } elseif (!$explicitCleared && !empty($userDefaultGenres)) {
    //                 $query->where(function ($q) use ($userDefaultGenres) {
    //                     $q->whereIn('target_genre', $userDefaultGenres)
    //                         ->orWhere('target_genre', 'anyGenre');
    //                 });
    //             }
    //             break;

    //         case 'all':
    //         default:
    //             if ($explicitSelection) {
    //                 $this->applyUserGenreFilter($query);
    //             }
    //             break;
    //     }

    //     // Apply music type filter to active tab
    //     if ($this->trackType !== 'all') {
    //         $this->applyMusicTypeFilter($query);
    //     }

    //     // Apply search filter
    //     if (!empty($this->search)) {
    //         $query->whereHas('music', function ($q) {
    //             $q->where('title', 'like', '%' . $this->search . '%')
    //                 ->orWhere('description', 'like', '%' . $this->search . '%')
    //                 ->orWhere('tag_list', 'like', '%' . $this->search . '%');
    //         });
    //     }

    //     return $query->latest()->paginate(10, ['*'], $this->activeMainTab . 'Page');
    // }

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
                    $query->whereIn('target_genre', $this->selectedGenres)
                        ->orWhere('target_genre', 'anyGenre');
                })
            )
            ->withoutSelf()
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
                    $query->whereIn('target_genre', $this->selectedGenres)
                        ->orWhere('target_genre', 'anyGenre');
                })
            )
            ->withoutSelf()
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
                    $query->whereIn('target_genre', $this->selectedGenres)
                        ->orWhere('target_genre', 'anyGenre');
                });
            } elseif (!$explicitCleared && !empty($userDefaultGenres)) {
                $recommendedCountQuery->where(function ($query) use ($userDefaultGenres) {
                    $query->whereIn('target_genre', $userDefaultGenres)
                        ->orWhere('target_genre', 'anyGenre');
                });
            }
        } else {
            if (!empty($userDefaultGenres)) {
                $recommendedCountQuery->where(function ($query) use ($userDefaultGenres) {
                    $query->whereIn('target_genre', $userDefaultGenres)
                        ->orWhere('target_genre', 'anyGenre');
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
                        $subQuery->whereIn('target_genre', $this->selectedGenres)
                            ->orWhere('target_genre', 'anyGenre');
                    });
                } elseif (!$explicitCleared && !empty($userDefaultGenres)) {
                    $query->where(function ($subQuery) use ($userDefaultGenres) {
                        $subQuery->whereIn('target_genre', $userDefaultGenres)
                            ->orWhere('target_genre', 'anyGenre');
                    });
                }
                break;

            case 'all':
            default:
                $query->when($explicitSelection, fn($q) => $q->where(function ($subQuery) {
                    $subQuery->whereIn('target_genre', $this->selectedGenres)
                        ->orWhere('target_genre', 'anyGenre');
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
}
