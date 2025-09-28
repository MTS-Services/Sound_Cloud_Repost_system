<?php

namespace App\Livewire\User;

use App\Jobs\TrackViewCount;
use App\Livewire\User\RepostRequest as RepostRequestComponent;
use App\Models\Campaign as ModelsCampaign;
use App\Models\CreditTransaction;
use App\Models\Playlist;
use App\Models\RepostRequest;
use App\Models\Track;
use App\Models\User;
use App\Services\Admin\CreditManagement\CreditTransactionService;
use App\Services\SoundCloud\FollowerAnalyzer;
use App\Services\SoundCloud\SoundCloudService;
use App\Services\User\CampaignManagement\MyCampaignService;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Throwable;
use App\Models\Feature;
use App\Models\Repost;
use App\Services\User\AnalyticsService;
use App\Services\User\Mamber\RepostRequestService;
use Illuminate\Validation\ValidationException;

use function PHPSTORM_META\type;

class Dashboard extends Component
{
    protected $soundcloudApiUrl = 'https://api.soundcloud.com';

    public $total_credits;
    public $totalCount;
    public $repostRequests;
    public $recentTracks;
    public $totalCams;
    public $creditPercentage;
    public $campaignPercentage;
    public $repostRequestPercentage;

    // Campaign create modal
    public $showCampaignsModal = false;
    public $showSubmitModal = false;
    public $momentumEnabled = false;
    public $maxFollowerEnabled = false;
    public $repostPerDayEnabled = false;
    public bool $showAddCreditModal = false;
    public bool $showEditCampaignModal = false;
    public bool $showCancelWarningModal = false;
    public bool $showLowCreditWarningModal = false;
    public bool $showRepostConfirmationModal = false;

    public $tracks = null;
    public $playlists = null;
    public $playlistTracks = [];
    public $activeTab = 'tracks';

    public $track = null;
    public $credit = 50;
    public $totalCredit = 50;
    public $commentable = true;
    public $likeable = true;
    public $proFeatureEnabled = false;
    public $proFeatureValue = 0;
    public $maxFollower = 100;
    public $followersLimit = 0;
    public $maxRepostLast24h = 0;
    public $maxRepostsPerDay = 0;
    public $targetGenre = 'anyGenre';
    public $user = null;
    public $allTracks;
    public $allPlaylists;
    public $showOptions = false;

    public $playlistLimit = 4;
    public $playlistTrackLimit = 4;
    public $trackLimit = 4;

    public $musicId = null;
    public $musicType = null;
    public $title = null;
    public $description = null;
    public $playlistId = null;

    // Properties for track type filtering
    public $selectedTrackTypes = [];
    public $selectedTrackType = 'all';
    public $genres = [];
    public $showTrackTypes = false;

    // Properties to track budget warnings and validation
    public $showBudgetWarning = false;
    public $budgetWarningMessage = '';
    public $canSubmit = false;

    // Properties for Add Credit functionality
    public $addCreditCampaignId = null;
    public int $addCreditCostPerRepost;
    public $addCreditCurrentBudget = null;
    public $addCreditTargetReposts = null;
    public $addCreditCreditsNeeded = 0;

    // Properties for Edit functionality
    public $editingCampaignId = null;
    public $editTitle = null;
    public $editDescription = null;
    public $editEndDate = null;
    public $editTargetReposts = null;
    public int $editCostPerRepost;
    public $editOriginalBudget = null;

    public $userFollowerAnalysis = [];
    public $followerPercentage = 0;

    // Search functionality properties - FIXED: Added missing property
    public $searchQuery = '';
    public $isSearching = false;
    public $searchResults = [];

    public $tracksPage = 1;
    public $playlistsPage = 1;
    public $perPage = 4;
    public $hasMoreTracks = false;
    public $hasMorePlaylists = false;
    public $playListTrackShow = false;
    public $allPlaylistTracks = null;
    public $selectedPlaylistId = null;

    // Confirmation Repost
    public $request = null;
    public bool $liked = false;
    public string $commented = '';
    public bool $followed = true;
    public bool $alreadyFollowing = false;

    public array $genreBreakdown = [];
    public array $userGenres = [];

    // Search configuration
    private const MAX_SEARCH_LENGTH = 255;
    private const MIN_SEARCH_LENGTH = 2;
    private const SEARCH_FIELDS = [
        'tracks' => ['title', 'permalink_url', 'description'],
        'playlists' => ['title', 'permalink_url', 'description']
    ];

    protected function rules()
    {
        $rules = [
            'credit' => [
                'required',
                'integer',
                'min:50',
                function ($attribute, $value, $fail) {
                    if ($value > userCredits()) {
                        $fail('The credit is not available.');
                    }
                },
            ],
            'searchQuery' => [
                'nullable',
                'string',
                'max:' . self::MAX_SEARCH_LENGTH,
                'min:' . self::MIN_SEARCH_LENGTH
            ],
        ];

        return $rules;
    }

    protected function messages()
    {
        return [
            'credit.required' => 'Minimum credit is 100.',
            'maxFollower.required' => 'Max follower is required.',
            'searchQuery.max' => 'Search query is too long (maximum ' . self::MAX_SEARCH_LENGTH . ' characters).',
            'searchQuery.min' => 'Search query must be at least ' . self::MIN_SEARCH_LENGTH . ' characters.',
        ];
    }

    protected CreditTransactionService $creditTransactionService;
    protected SoundCloudService $soundCloudService;
    protected MyCampaignService $myCampaignService;
    protected AnalyticsService $analyticsService;
    protected RepostRequestService $repostRequestService;

    protected FollowerAnalyzer $followerAnalyzer;

    public function boot(CreditTransactionService $creditTransactionService, SoundCloudService $soundCloudService, MyCampaignService $myCampaignService, AnalyticsService $analyticsService, RepostRequestService $repostRequestService, FollowerAnalyzer $followerAnalyzer)
    {
        $this->creditTransactionService = $creditTransactionService;
        $this->soundCloudService = $soundCloudService;
        $this->myCampaignService = $myCampaignService;
        $this->analyticsService = $analyticsService;
        $this->repostRequestService = $repostRequestService;
        $this->followerAnalyzer = $followerAnalyzer;
    }

    public function mount()
    {
        $this->soundCloudService->refreshUserTokenIfNeeded(user());

        // $this->userFollowerAnalysis = $this->followerAnalyzer->getQuickStats($this->soundCloudService->getAuthUserFollowers());

        // $lastWeekFollowerPercentage = $this->followerAnalyzer->getQuickStats($this->soundCloudService->getAuthUserFollowers(), 'last_week');
        // $currentWeekFollowerPercentage = $this->followerAnalyzer->getQuickStats($this->soundCloudService->getAuthUserFollowers(), 'this_week');
        // $lastWeek = $lastWeekFollowerPercentage['averageCredibilityScore'];
        // $currentWeek = $currentWeekFollowerPercentage['averageCredibilityScore'];

        // if ($lastWeek > 0) {
        //     $this->followerPercentage = (($currentWeek - $lastWeek) / $lastWeek) * 100;
        // } else {
        //     $this->followerPercentage = 0;
        // }
        $this->loadDashboardData();
        $this->calculateFollowersLimit();
        $this->userGenres = user()->genres->pluck('genre')->toArray();
        $this->genreBreakdown = $this->analyticsService->getGenreBreakdown('last_month', null, null, $this->userGenres);
    }


    public function updated($propertyName)
    {
        $this->soundCloudService->refreshUserTokenIfNeeded(user());
        if (in_array($propertyName, ['credit', 'likeable', 'commentable'])) {
            $this->calculateFollowersLimit();
        }
    }

    public function calculateFollowersLimit()
    {
        $this->followersLimit = ($this->credit - ($this->likeable ? 2 : 0) - ($this->commentable ? 2 : 0)) * 100;
    }

    public function loadDashboardData()
    {
        $this->total_credits = $this->creditTransactionService->getUserTotalCredits();

        $this->totalCount = Repost::where('reposter_urn', user()->urn)->count();

        $this->repostRequests = RepostRequest::where('target_user_urn', user()->urn)->where('status', RepostRequest::STATUS_PENDING)
            ->where('expired_at', '>', now())
            ->with(['music', 'requester'])
            ->latest()
            ->take(5)
            ->get();

        $this->recentTracks = Track::where('user_urn', user()->urn)->orderBy('created_at_soundcloud', 'desc')->latest()->take(5)->get();

        $this->totalCams = ModelsCampaign::where('user_urn', user()->urn)
            ->orWhere('status', [ModelsCampaign::STATUS_COMPLETED, ModelsCampaign::STATUS_OPEN])
            ->count();

        // Available Credit
        $userId = user()->urn;
        $this->creditPercentage = $this->creditTransactionService->getWeeklyChangeByCredit($userId);

        // Campaign Percentage
        $this->campaignPercentage = $this->creditTransactionService->getWeeklyCampaignChange($userId);

        // Repost Request Percentage
        $this->repostRequestPercentage = $this->creditTransactionService->getWeeklyRepostRequestChange($userId);

        Bus::chain([
            new TrackViewCount($this->repostRequests, user()->urn, 'request'),
            new TrackViewCount($this->recentTracks, user()->urn, 'track'),
        ])->dispatch();
    }

    public function selectModalTab($tab = 'tracks')
    {
        // $this->searchQuery = '';
        $this->reset(['searchQuery', 'tracks']);
        $this->activeTab = $tab;

        if ($tab === 'tracks') {
            $this->fetchTracks();
        } elseif ($tab === 'playlists') {
            $this->playListTrackShow = false;
            $this->allPlaylistTracks = collect();
            $this->tracks = collect();
            $this->selectedPlaylistId = null;
            $this->fetchPlaylists();
        }
    }

    // public function fetchTracks()
    // {
    //     try {
    //         $this->soundCloudService->syncSelfTracks([]);

    //         $this->tracksPage = 1;
    //         $this->tracks = Track::where('user_urn', user()->urn)
    //             ->latest()
    //             ->take($this->perPage)
    //             ->get();
    //         $this->hasMoreTracks = $this->tracks->count() === $this->perPage;
    //     } catch (\Exception $e) {
    //         $this->tracks = collect();
    //         $this->dispatch('alert', type: 'error', message: 'Failed to load tracks: ' . $e->getMessage());
    //     }
    // }

    // public function loadMoreTracks()
    // {
    //     $this->tracksPage++;
    //     $newTracks = Track::where('user_urn', user()->urn)
    //         ->latest()
    //         ->skip(($this->tracksPage - 1) * $this->perPage)
    //         ->take($this->perPage)
    //         ->get();

    //     $this->tracks = $this->tracks->concat($newTracks);
    //     $this->hasMoreTracks = $newTracks->count() === $this->perPage;
    // }

    // public function fetchPlaylists()
    // {
    //     try {
    //         $this->soundCloudService->syncSelfPlaylists();

    //         $this->playlistsPage = 1;
    //         $this->playlists = Playlist::where('user_urn', user()->urn)
    //             ->latest()
    //             ->take($this->perPage)
    //             ->get();
    //         $this->hasMorePlaylists = $this->playlists->count() === $this->perPage;
    //     } catch (\Exception $e) {
    //         $this->playlists = collect();
    //         $this->dispatch('alert', type: 'error', message: 'Failed to load playlists: ' . $e->getMessage());
    //     }
    // }

    // public function loadMorePlaylists()
    // {
    //     $this->playlistsPage++;
    //     $newPlaylists = Playlist::where('user_urn', user()->urn)
    //         ->latest()
    //         ->skip(($this->playlistsPage - 1) * $this->perPage)
    //         ->take($this->perPage)
    //         ->get();

    //     $this->playlists = $this->playlists->concat($newPlaylists);
    //     $this->hasMorePlaylists = $newPlaylists->count() === $this->perPage;
    // }


    // Replace the existing loadMoreTracks and loadMorePlaylists methods in Campaign component

    public function loadMoreTracks()
    {
        $this->tracksPage++;

        if ($this->playListTrackShow && $this->selectedPlaylistId) {
            // For playlist tracks
            $startIndex = ($this->tracksPage - 1) * $this->perPage;
            $newTracks = $this->allPlaylistTracks->slice($startIndex, $this->perPage);
            $this->tracks = $this->tracks->concat($newTracks);
            $this->hasMoreTracks = $newTracks->count() === $this->perPage;
        } else {
            // For regular tracks
            $startIndex = ($this->tracksPage - 1) * $this->perPage;
            $newTracks = $this->allTracks->slice($startIndex, $this->perPage);
            $this->tracks = $this->tracks->concat($newTracks);
            $this->hasMoreTracks = $newTracks->count() === $this->perPage;
        }
    }

    public function loadMorePlaylists()
    {
        $this->playlistsPage++;
        $startIndex = ($this->playlistsPage - 1) * $this->perPage;
        $newPlaylists = $this->allPlaylists->slice($startIndex, $this->perPage);
        $this->playlists = $this->playlists->concat($newPlaylists);
        $this->hasMorePlaylists = $newPlaylists->count() === $this->perPage;
    }

    // Also update fetchTracks method to properly set allTracks
    public function fetchTracks()
    {
        try {
            $this->soundCloudService->syncUserTracks(user(), []);

            // Get all tracks first
            $this->allTracks = Track::where('user_urn', user()->urn)
                ->latest()
                ->get();

            $this->tracksPage = 1;
            $this->tracks = $this->allTracks->take($this->perPage);
            $this->hasMoreTracks = $this->allTracks->count() > $this->perPage;
        } catch (\Exception $e) {
            $this->tracks = collect();
            $this->allTracks = collect();
            $this->hasMoreTracks = false;
            $this->dispatch('alert', type: 'error', message: 'Failed to load tracks: ' . $e->getMessage());
        }
    }

    // Also update fetchPlaylists method to properly set allPlaylists
    public function fetchPlaylists()
    {
        try {
            $this->soundCloudService->syncUserPlaylists(user());

            $this->allPlaylists = Playlist::where('user_urn', user()->urn)
                ->latest()
                ->get();

            $this->playlistsPage = 1;
            $this->playlists = $this->allPlaylists->take($this->perPage);
            $this->hasMorePlaylists = $this->allPlaylists->count() > $this->perPage;
        } catch (\Exception $e) {
            $this->playlists = collect();
            $this->allPlaylists = collect();
            $this->hasMorePlaylists = false;
            $this->dispatch('alert', type: 'error', message: 'Failed to load playlists: ' . $e->getMessage());
        }
    }

    public function fetchPlaylistTracks()
    {
        if (!$this->playlistId) {
            $this->playlistTracks = [];
            return;
        }

        try {
            $playlist = Playlist::findOrFail($this->playlistId);

            if (!$playlist->soundcloud_urn) {
                $this->playlistTracks = [];
                $this->dispatch('alert', type: 'error', message: 'Playlist SoundCloud URN is missing.');
                return;
            }

            $response = Http::timeout(30)
                ->withHeaders([
                    'Authorization' => 'OAuth ' . user()->token,
                ])
                ->get('https://api.soundcloud.com/playlists/' . $playlist->soundcloud_urn . '/tracks');

            if ($response->successful()) {
                $tracks = $response->json();

                if (is_array($tracks)) {
                    $this->playlistTracks = collect($tracks)->filter(function ($track) {
                        return is_array($track) &&
                            isset($track['urn']) &&
                            isset($track['title']) &&
                            isset($track['user']) &&
                            is_array($track['user']) &&
                            isset($track['user']['username']);
                    })->values()->toArray();
                } else {
                    $this->playlistTracks = [];
                }
            } else {
                $this->playlistTracks = [];
                $this->dispatch('alert', type: 'error', message: 'Failed to load playlist tracks from SoundCloud: ' . $response->status());
            }
        } catch (\Exception $e) {
            $this->playlistTracks = [];
            $this->dispatch('alert', type: 'error', message: 'Failed to fetch playlist tracks: ' . $e->getMessage());
            Log::error('Playlist tracks fetch error: ' . $e->getMessage(), [
                'playlist_id' => $this->playlistId,
                'user_urn' => user()->urn ?? 'unknown'
            ]);
        }
    }

    /**
     * Enhanced search functionality with security, validation, and performance improvements
     *
     * @return void
     */
    public function searchSoundcloud()
    {
        try {
            $this->isSearching = true;

            // Reset search results
            $this->searchResults = [];

            // Handle empty search query - reset to default content
            if (empty($this->searchQuery)) {
                $this->resetSearchToDefault();
                return;
            }

            // Validate search query
            // $this->validateSearchQuery();

            // Check if it's a SoundCloud URL
            if ($this->isSoundCloudUrl($this->searchQuery)) {
                if (proUser()) {
                    $this->resolveSoundcloudUrl();
                } else {
                    $this->dispatch('alert', type: 'error', message: 'Please upgrade to a Pro User to use this feature.');
                }
                return;
            }

            // Perform text-based search
            $this->performTextSearch();
        } catch (\Exception $e) {
            $this->dispatch('alert', type: 'error', message: 'Search failed. Please try again.');
            Log::error('Search failed: ' . $e->getMessage(), [
                'search_query' => $this->searchQuery,
                'user_urn' => user()->urn ?? 'unknown',
                'stack_trace' => $e->getTraceAsString()
            ]);
        } finally {
            $this->isSearching = false;
        }
    }

    private function validateSearchQuery(): void
    {
        $this->validate([
            'searchQuery' => [
                'required',
                'string',
                'max:' . self::MAX_SEARCH_LENGTH,
                'min:' . self::MIN_SEARCH_LENGTH,
                // Prevent potential XSS and SQL injection patterns
                function ($attribute, $value, $fail) {
                    if (preg_match('/<[^>]*>|javascript:|data:|vbscript:/i', $value)) {
                        $fail('Invalid characters detected in search query.');
                    }
                }
            ]
        ]);
    }

    private function isSoundCloudUrl(string $query): bool
    {
        return preg_match('/^https?:\/\/(www\.)?soundcloud\.com\/[a-zA-Z0-9\-_]+(\/[a-zA-Z0-9\-_]+)*(\/)?(\?.*)?$/i', $query);
    }

    private function resetSearchToDefault(): void
    {
        try {
            if ($this->playListTrackShow && $this->activeTab === 'tracks' && $this->selectedPlaylistId) {
                // Show playlist tracks
                $this->allPlaylistTracks = Playlist::findOrFail($this->selectedPlaylistId)
                    ->tracks()
                    ->take($this->playlistTrackLimit)
                    ->get();
                $this->tracks = $this->allPlaylistTracks;
            } else {
                // Show regular tracks or playlists
                if ($this->activeTab === 'tracks') {
                    $this->allTracks = Track::where('user_urn', user()->urn)
                        ->latest()
                        ->take($this->trackLimit)
                        ->get();
                    $this->tracks = $this->allTracks;
                } elseif ($this->activeTab === 'playlists') {
                    $this->allPlaylists = Playlist::where('user_urn', user()->urn)
                        ->latest()
                        ->take($this->playlistLimit)
                        ->get();
                    $this->playlists = $this->allPlaylists;
                }
            }
        } catch (\Exception $e) {
            Log::error('Failed to reset search to default: ' . $e->getMessage(), [
                'user_urn' => user()->urn ?? 'unknown',
                'active_tab' => $this->activeTab
            ]);
        }
    }

    /**
     * Perform text-based search with optimized queries
     *
     * @return void
     */
    private function performTextSearch(): void
    {
        $sanitizedQuery = $this->sanitizeSearchQuery($this->searchQuery);

        if ($this->playListTrackShow && $this->activeTab === 'tracks' && $this->selectedPlaylistId) {
            // Search within playlist tracks
            $this->searchPlaylistTracks($sanitizedQuery);
        } else {
            // Search regular tracks or playlists
            if ($this->activeTab === 'tracks') {
                $this->searchUserTracks($sanitizedQuery);
            } elseif ($this->activeTab === 'playlists') {
                $this->searchUserPlaylists($sanitizedQuery);
            }
        }
    }

    /**
     * Sanitize search query to prevent SQL injection and improve search quality
     *
     * @param string $query
     * @return string
     */
    private function sanitizeSearchQuery(string $query): string
    {
        // Trim whitespace
        $query = trim($query);

        // Escape special LIKE characters but preserve user intent
        $query = str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $query);

        // Remove extra spaces
        $query = preg_replace('/\s+/', ' ', $query);

        return $query;
    }

    /**
     * Search within playlist tracks
     *
     * @param string $sanitizedQuery
     * @return void
     */
    private function searchPlaylistTracks(string $sanitizedQuery): void
    {
        $this->allPlaylistTracks = Playlist::findOrFail($this->selectedPlaylistId)
            ->tracks()
            ->where(function ($query) use ($sanitizedQuery) {
                $query->where('title', 'LIKE', "%{$sanitizedQuery}%")
                    ->orWhere('permalink_url', 'LIKE', "%{$sanitizedQuery}%")
                    ->orWhere('description', 'LIKE', "%{$sanitizedQuery}%");
            })
            ->take($this->playlistTrackLimit)
            ->get();

        $this->tracks = $this->allPlaylistTracks;

        $this->searchResults = [
            'type' => 'playlist_tracks',
            'count' => $this->allPlaylistTracks->count(),
            'query' => $this->searchQuery
        ];
    }

    /**
     * Search user's tracks with optimized query
     *
     * @param string $sanitizedQuery
     * @return void
     */
    private function searchUserTracks(string $sanitizedQuery): void
    {
        $this->allTracks = Track::where('user_urn', user()->urn)
            ->where(function ($query) use ($sanitizedQuery) {
                foreach (self::SEARCH_FIELDS['tracks'] as $field) {
                    $query->orWhere($field, 'LIKE', "%{$sanitizedQuery}%");
                }
            })
            ->latest()
            ->take($this->trackLimit)
            ->get();

        $this->tracks = $this->allTracks;

        $this->searchResults = [
            'type' => 'tracks',
            'count' => $this->allTracks->count(),
            'query' => $this->searchQuery
        ];
    }

    /**
     * Search user's playlists with optimized query
     *
     * @param string $sanitizedQuery
     * @return void
     */
    private function searchUserPlaylists(string $sanitizedQuery): void
    {
        $this->allPlaylists = Playlist::where('user_urn', user()->urn)
            ->where(function ($query) use ($sanitizedQuery) {
                foreach (self::SEARCH_FIELDS['playlists'] as $field) {
                    $query->orWhere($field, 'LIKE', "%{$sanitizedQuery}%");
                }
            })
            ->latest()
            ->take($this->playlistLimit)
            ->get();

        $this->playlists = $this->allPlaylists;

        $this->searchResults = [
            'type' => 'playlists',
            'count' => $this->allPlaylists->count(),
            'query' => $this->searchQuery
        ];
    }

    /**
     * Clear search and reset to default view
     *
     * @return void
     */
    public function clearSearch(): void
    {
        $this->searchQuery = '';
        $this->searchResults = [];
        $this->resetSearchToDefault();
        $this->dispatch('searchCleared');
    }

    /**
     * Get search statistics for display
     *
     * @return array
     */
    public function getSearchStats(): array
    {
        if (empty($this->searchResults)) {
            return [];
        }

        return [
            'query' => $this->searchResults['query'],
            'type' => $this->searchResults['type'],
            'count' => $this->searchResults['count'],
            'hasResults' => $this->searchResults['count'] > 0
        ];
    }

    public function toggleCampaignsModal()
    {
        if (!is_email_verified()) {
            $this->dispatch('alert', type: 'error', message: 'Please verify your email to create a campaign.');
            return;
        }
        $this->reset([
            'title',
            'description',
            'showAddCreditModal',
            'addCreditCampaignId',
            'addCreditCostPerRepost',
            'addCreditCurrentBudget',
            'addCreditTargetReposts',
            'addCreditCreditsNeeded',
            'showEditCampaignModal',
            'editTitle',
            'editingCampaignId',
            'editDescription',
            'editTargetReposts',
            'editCostPerRepost',
            'editOriginalBudget',
            'showCancelWarningModal',
        ]);

        if ($this->myCampaignService->thisMonthCampaignsCount() >= (int) userFeatures()[Feature::KEY_SIMULTANEOUS_CAMPAIGNS]) {
            return $this->dispatch('alert', type: 'error', message: 'You have reached the maximum number of campaigns for this month.');
        }

        $this->activeTab = 'tracks';
        $this->tracks = collect();
        $this->playlists = collect();
        $this->playlistTracks = [];

        $this->showCampaignsModal = !$this->showCampaignsModal;

        if ($this->showCampaignsModal) {
            $this->selectModalTab('tracks');
        }
    }

    public function toggleSubmitModal($type, $id)
    {
        $this->reset([
            'title',
            'description',
            'showAddCreditModal',
            'addCreditCampaignId',
            'addCreditCostPerRepost',
            'addCreditCurrentBudget',
            'addCreditTargetReposts',
            'addCreditCreditsNeeded',
            'showEditCampaignModal',
            'editingCampaignId',
            'editTitle',
            'editDescription',
            'editEndDate',
            'editTargetReposts',
            'editCostPerRepost',
            'editOriginalBudget',
            'showCancelWarningModal',
        ]);

        if (userCredits() < 50) {
            $this->showLowCreditWarningModal = true;
            $this->showSubmitModal = false;
            return;
        } else {
            $this->showLowCreditWarningModal = false;
        }

        $this->showSubmitModal = true;

        try {
            if ($type === 'track') {
                $this->track = Track::findOrFail($id);
                if (!$this->track->urn || !$this->track->title) {
                    throw new \Exception('Track data is incomplete');
                }
                $this->musicId = $this->track->id;
                $this->musicType = Track::class;
                $this->title = $this->track->title . ' Campaign';
            } elseif ($type === 'playlist') {
                $playlist = Playlist::findOrFail($id);

                if (!$playlist->title) {
                    throw new \Exception('Playlist data is incomplete');
                }

                $this->playlistId = $id;
                $this->title = $playlist->title . ' Campaign';
                $this->musicType = Playlist::class;

                $this->fetchPlaylistTracks();
                $this->musicId = null;
            }
        } catch (\Exception $e) {
            $this->dispatch('alert', type: 'error', message: 'Failed to load content: ' . $e->getMessage());
            $this->showSubmitModal = false;
            $this->showCampaignsModal = true;

            Log::error('Toggle submit modal error: ' . $e->getMessage(), [
                'type' => $type,
                'id' => $id,
                'user_urn' => user()->urn ?? 'unknown'
            ]);
        }
    }

    public function profeature($isChecked)
    {
        if (!proUser()) {
            return $this->dispatch('alert', type: 'error', message: 'You need to be a pro user to use this feature');
            ;
        } elseif (($this->credit * 1.5) > userCredits()) {
            $this->proFeatureEnabled = $isChecked ? true : false;
            $this->proFeatureValue = $isChecked ? 1 : 0;
        } else {
            $this->proFeatureEnabled = $isChecked ? false : true;
            $this->proFeatureValue = $isChecked ? 0 : 1;
        }
    }

    public function createCampaign()
    {
        $this->validate();

        try {
            $musicId = $this->musicType === Track::class ? $this->musicId : $this->playlistId;
            DB::transaction(function () use ($musicId) {
                $commentable = $this->commentable ? 1 : 0;
                $likeable = $this->likeable ? 1 : 0;
                $proFeatureEnabled = $this->proFeatureEnabled && proUser() ? 1 : 0;
                $campaign = ModelsCampaign::create([
                    'music_id' => $musicId,
                    'music_type' => $this->musicType,
                    'title' => $this->title,
                    'description' => $this->description,
                    'budget_credits' => $this->credit,
                    'user_urn' => user()->urn,
                    'status' => ModelsCampaign::STATUS_OPEN,
                    'max_followers' => $this->maxFollowerEnabled ? $this->maxFollower : 100,
                    'creater_id' => user()->id,
                    'creater_type' => get_class(user()),
                    'commentable' => $commentable,
                    'likeable' => $likeable,
                    'pro_feature' => $proFeatureEnabled,
                    'momentum_price' => $proFeatureEnabled == 1 ? $this->credit / 2 : 0,
                    'max_repost_per_day' => $this->repostPerDayEnabled ? $this->maxRepostsPerDay : 0,
                    'max_repost_per_day' => $this->maxRepostsPerDay,
                    'target_genre' => $this->targetGenre,
                ]);

                CreditTransaction::create([
                    'receiver_urn' => user()->urn,
                    'calculation_type' => CreditTransaction::CALCULATION_TYPE_CREDIT,
                    'source_id' => $campaign->id,
                    'source_type' => ModelsCampaign::class,
                    'transaction_type' => CreditTransaction::TYPE_SPEND,
                    'status' => CreditTransaction::STATUS_SUCCEEDED,
                    'credits' => ($campaign->budget_credits + $campaign->momentum_price),
                    'description' => 'Spent on campaign creation',
                    'metadata' => [
                        'campaign_id' => $campaign->id,
                        'music_id' => $this->musicId,
                        'music_type' => $this->musicType,
                        'start_date' => now(),
                    ],
                    'created_id' => user()->id,
                    'creater_type' => get_class(user())
                ]);
            });

            $this->reset([
                'musicId',
                'title',
                'description',
                'playlistId',
                'playlistTracks',
                'activeTab',
                'tracks',
                'track',
                'playlists',
                'maxFollower',
                'showBudgetWarning',
                'budgetWarningMessage',
                'canSubmit',
                'commentable',
                'likeable',
                'proFeatureEnabled',
                'maxRepostLast24h',
                'maxRepostsPerDay',
                'targetGenre',
                'searchQuery',
                'searchResults',
            ]);
            $this->dispatch('alert', type: 'success', message: 'Campaign created successfully!');
            $this->dispatch('campaignCreated');

            $this->showCampaignsModal = false;
            $this->showSubmitModal = false;
            $this->resetValidation();
            $this->resetErrorBag();
        } catch (\Exception $e) {
            $this->dispatch('alert', type: 'error', message: 'Failed to create Campaign: ' . $e->getMessage());

            Log::error('Campaign creation error: ' . $e->getMessage(), [
                'music_id' => $this->musicId,
                'user_urn' => user()->urn ?? 'unknown',
                'title' => $this->title,
                'credit' => $this->credit,
            ]);
        }
    }

    protected function resolveSoundcloudUrl()
    {
        if ($this->playListTrackShow == true && $this->activeTab === 'tracks') {
            $baseUrl = strtok($this->searchQuery, '?');
            $tracksFromDb = Playlist::findOrFail($this->selectedPlaylistId)->tracks()
                ->whereRaw("SUBSTRING_INDEX(permalink_url, '?', 1) = ?", [$baseUrl])
                ->get();
            if ($tracksFromDb->isNotEmpty()) {
                $this->allPlaylistTracks = $tracksFromDb;
                $this->tracks = $this->allPlaylistTracks->take($this->playlistTrackLimit);
                $this->hasMoreTracks = $this->tracks->count() === $this->trackLimit;
                return;
            }
        } else {
            if ($this->activeTab == 'tracks') {
                $baseUrl = strtok($this->searchQuery, '?');
                $tracksFromDb = Track::whereRaw("SUBSTRING_INDEX(permalink_url, '?', 1) = ?", [$baseUrl])
                    ->get();

                if ($tracksFromDb->isNotEmpty()) {
                    Log::info('Resolving Soundcloud URL. Step 8. on if activeTab == tracks on else if playListTrackShow == true && activeTab === tracks && tracksFromDb->isNotEmpty()');
                    $this->activeTab = 'tracks';
                    $this->allTracks = $tracksFromDb;
                    $this->tracks = $this->allTracks->take($this->trackLimit);
                    $this->hasMoreTracks = $this->tracks->count() === $this->trackLimit;
                    return;
                }
            }

            if ($this->activeTab == 'playlists') {
                $baseUrl = strtok($this->searchQuery, '?');
                $playlistsFromDb = Playlist::whereRaw("SUBSTRING_INDEX(permalink_url, '?', 1) = ?", [$baseUrl])
                    ->get();

                if ($playlistsFromDb->isNotEmpty()) {
                    $this->activeTab = 'playlists';
                    $this->allPlaylists = $playlistsFromDb;
                    $this->playlists = $this->allPlaylists->take($this->playlistLimit);
                    $this->hasMorePlaylists = $this->playlists->count() === $this->playlistLimit;
                    return;
                }
            }
        }

        Log::info('Resolving Soundcloud URL. Step 13. end of if playListTrackShow == true && activeTab === tracks');
        $response = null;
        $response = Http::withToken(user()->token)->get("https://api.soundcloud.com/resolve?url=" . $this->searchQuery);
        if ($response->successful()) {
            $resolvedData = $response->json();
            $urn = $resolvedData['urn'];
            if ($this->activeTab === 'playlists') {
                if (isset($resolvedData['tracks']) && count($resolvedData['tracks']) > 0) {
                    // $this->soundCloudService->unknownPlaylistAdd($resolvedData);
                    Log::info('Resolved SoundCloud URL: ' . "Successfully resolved SoundCloud URL: " . $this->searchQuery);
                } else {
                    $this->dispatch('alert', type: 'error', message: 'Could not resolve the SoundCloud link. Please check the Playlist URL.');
                }
            } elseif ($this->activeTab === 'tracks') {
                if (!isset($resolvedData['tracks'])) {
                    // $this->soundCloudService->unknownTrackAdd($resolvedData);
                    Log::info('Resolved SoundCloud URL: ' . "Successfully resolved SoundCloud URL: " . $this->searchQuery);
                } else {
                    $this->dispatch('alert', type: 'error', message: 'Could not resolve the SoundCloud link. Please check the Track URL.');
                }
            }
            $this->processSearchData($urn);
            Log::info('Resolved SoundCloud URL: ' . "Successfully resolved SoundCloud URL: " . $this->searchQuery);
        } else {
            Log::info('Resolving Soundcloud URL. Step 24. end of if playListTrackShow == true && activeTab === tracks and response is not successful');
            if ($this->playListTrackShow == true && $this->activeTab === 'tracks') {
                $this->allPlaylistTracks = collect();
                $this->tracks = collect();
            } else {
                if ($this->activeTab === 'tracks') {
                    $this->allTracks = collect();
                    $this->tracks = collect();
                } elseif ($this->activeTab === 'playlists') {
                    $this->allPlaylists = collect();
                    $this->playlists = collect();
                }
            }
            $this->dispatch('alert', type: 'error', message: 'Could not resolve the SoundCloud link. Please check the URL.');
        }
    }

    protected function processSearchData($urn)
    {
        if ($this->playListTrackShow == true && $this->activeTab === 'tracks') {
            $tracksFromDb = Playlist::findOrFail($this->selectedPlaylistId)->tracks()
                ->where('soundcloud_urn', $urn)
                ->get();

            if ($tracksFromDb->isNotEmpty()) {
                $this->allPlaylistTracks = $tracksFromDb;
                $this->tracks = $this->allPlaylistTracks->take($this->playlistTrackLimit);
                $this->hasMoreTracks = $this->tracks->count() === $this->trackLimit;
                return;
            }
        } else {
            if ($this->activeTab == 'tracks') {
                $tracksFromDb = Track::where('urn', $urn)
                    ->get();

                if ($tracksFromDb->isNotEmpty()) {
                    $this->activeTab = 'tracks';
                    $this->allTracks = $tracksFromDb;
                    $this->tracks = $this->allTracks->take($this->trackLimit);
                    $this->hasMoreTracks = $this->tracks->count() === $this->trackLimit;
                    return;
                }
            }

            if ($this->activeTab == 'playlists') {
                $playlistsFromDb = Playlist::where('soundcloud_urn', $urn)
                    ->get();

                if ($playlistsFromDb->isNotEmpty()) {
                    $this->activeTab = 'playlists';
                    $this->allPlaylists = $playlistsFromDb;
                    $this->playlists = $this->allPlaylists->take($this->playlistLimit);
                    $this->hasMorePlaylists = $this->playlists->count() === $this->playlistLimit;
                    return;
                }
            }
        }
    }

    // public function showPlaylistTracks($playlistId)
    // {
    //     $this->selectedPlaylistId = $playlistId;
    //     $playlist = Playlist::with('tracks')->find($playlistId);
    //     if ($playlist) {
    //         $this->allTracks = $playlist->tracks;
    //         $this->tracks = $this->allTracks->take($this->trackLimit);
    //         $this->hasMoreTracks = $this->tracks->count() === $this->trackLimit;
    //     } else {
    //         $this->tracks = collect();
    //         $this->allTracks = collect();
    //         $this->hasMoreTracks = false;
    //     }
    //     $this->activeTab = 'tracks';
    //     $this->playListTrackShow = true;

    //     $this->searchQuery = '';
    // }
    public function showPlaylistTracks($playlistId)
    {
        $this->selectedPlaylistId = $playlistId;
        $playlist = Playlist::with('tracks')->find($playlistId);

        if ($playlist) {
            $this->allPlaylistTracks = $playlist->tracks;
            $this->tracks = $this->allPlaylistTracks->take($this->perPage);
            $this->hasMoreTracks = $this->allPlaylistTracks->count() > $this->perPage;
        } else {
            $this->tracks = collect();
            $this->allTracks = collect();
            $this->hasMoreTracks = false;
        }

        $this->activeTab = 'tracks';
        $this->playListTrackShow = true;
        $this->tracksPage = 1;
        $this->searchQuery = '';
    }

    public function directRepost($encryptedRequestId)
    {
        try {
            $requestId = decrypt($encryptedRequestId);

            $component = new RepostRequestComponent();
            $component->repost($requestId);

            // Refresh data after successful repost
            $this->loadDashboardData();
            $this->dispatch('alert', type: 'success', message: 'Repost request sent successfully.');
        } catch (Throwable $e) {
            Log::error("Error sending repost request: " . $e->getMessage(), [
                'exception' => $e,
                'encrypted_request_id' => $encryptedRequestId,
                'user_urn' => user()->urn ?? 'N/A',
            ]);

            $this->dispatch('alert', type: 'error', message: 'Failed to send repost request. Please try again.');
        }
    }
    public function confirmRepost($requestId)
    {
        $this->showRepostConfirmationModal = true;
        $this->request = RepostRequest::findOrFail($requestId)->load('music', 'requester');
        // $response = $this->soundCloudService->getAuthUserFollowers($this->request->requester);
        // if ($response->isNotEmpty()) {
        //     $already_following = $response->where('urn', user()->urn)->first();
        //     if ($already_following !== null) {
        //         Log::info('Repost request Page:- Already following');
        //         $this->followed = false;
        //         $this->alreadyFollowing = true;
        //     }
        // }
    }
    public function repost($requestId)
    {
        $result = $this->repostRequestService->handleRepost($requestId, $this->commented, $this->liked, $this->followed);

        if ($result['status'] === 'success') {
            $this->loadDashboardData();
            $this->dispatch('alert', type: 'success', message: 'Repost request sent successfully.');
        }
        $this->showRepostConfirmationModal = false;

        $this->dispatch('alert', type: $result['status'], message: $result['message']);
    }

    public function declineRepost($encryptedRequestId)
    {
        try {
            $requestId = decrypt($encryptedRequestId);

            $component = new RepostRequestComponent();
            $component->declineRepostRequest($requestId);

            // Refresh data after successful decline
            $this->loadDashboardData();

            $this->dispatch('alert', type: 'success', message: 'Repost request declined successfully.');
        } catch (Throwable $e) {
            Log::error("Error declining repost request: " . $e->getMessage(), [
                'exception' => $e,
                'encrypted_request_id' => $encryptedRequestId,
                'user_urn' => user()->urn ?? 'N/A',
            ]);
            $this->dispatch('alert', type: 'error', message: 'Failed to decline repost request. Please try again.');
        }
    }

    // Analytics data
    public function getChartData(): array
    {
        try {
            return $this->analyticsService->getChartData(
                'last_month',
                null,
                [],
                null,
                null
            );
        } catch (Throwable $e) {
            Log::error('Chart data loading failed', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function render()
    {
        return view('livewire.user.dashboard');
    }
}
