<?php

namespace App\Livewire\User\CampaignManagement;

use App\Jobs\NotificationMailSent;
use App\Jobs\TrackViewCount;
use App\Models\Campaign as ModelsCampaign;
use App\Models\CreditTransaction;
use App\Models\Feature;
use App\Models\Playlist;
use App\Models\Repost;
use App\Models\RepostRequest;
use App\Models\Track;
use App\Models\User;
use App\Services\PlaylistService;
use App\Services\SoundCloud\SoundCloudService;
use App\Services\TrackService;
use App\Services\User\AnalyticsService;
use App\Services\User\CampaignManagement\CampaignService;
use App\Services\User\CampaignManagement\MyCampaignService;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Throwable;
use Illuminate\Support\Facades\Http;
use Illuminate\Database\Eloquent\Builder;
use App\Models\UserAnalytics;
use App\Models\UserPlan;
use App\Models\UserSetting;
use App\Services\User\StarredUserService;
use App\Services\User\UserSettingsService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Livewire\Attributes\On;

class Campaign extends Component
{
    use WithPagination;

    #[Locked]
    protected string $baseUrl = 'https://api.soundcloud.com';

    // Pagination URL parameters
    #[Url(as: 'recommended_proPage')]
    public ?int $recommended_proPage = 1;

    #[Url(as: 'recommendedPage')]
    public ?int $recommendedPage = 1;

    #[Url(as: 'allPage')]
    public ?int $allPage = 1;

    #[Url(as: 'recentPage')]
    public ?int $recentPage = 1;

    // Main tab state
    #[Url(as: 'tab', except: '')]
    public string $activeMainTab = 'recommended_pro';

    // Properties for filtering and search
    #[Url(as: 'q', except: '')]
    public string $search = '';

    #[Url(as: 'type', except: '')]
    public $searchMusicType = 'all';

    public int $todayRepost = 0;

    public $selectedTags = [];
    public $selecteTags = [];
    public $selectedGenre = [];
    public $suggestedTags = [];
    public $showSuggestions = false;
    public $showSelectedTags = false;
    public $isLoading = false;
    public $selectedTrackId;
    public $selectedPlaylistId = null;
    public $maxFollowerEnabled = false;
    public $repostPerDayEnabled = false;

    // Properties for track type filtering
    public $selectedTrackTypes = [];
    public $selectedTrackType = 'all';
    public $selectedGenres = [];
    public $showTrackTypes = false;

    //=========================================
    //  Music Play Control:
    //=========================================
    // Track which campaigns are currently playing
    public $playingCampaigns = [];

    // Track play start times
    public $playStartTimes = [];

    // Track total play time for each campaign
    public $playTimes = [];

    // Track which campaigns have been played for 5+ seconds
    public $playedCampaigns = [];
    public $playcount = false;
    // Listeners for browser events
    protected $listeners = [
        'audioPlay' => 'handleAudioPlay',
        'audioPause' => 'handleAudioPause',
        'audioTimeUpdate' => 'handleAudioTimeUpdate',
        'audioEnded' => 'handleAudioEnded',
    ];

    // Search and SoundCloud integration methods
    public $searchQuery = '';
    public $allTracks;
    public $users;
    public $allPlaylists;
    public $trackLimit = 4;
    public $playlistLimit = 4;
    public $playlistTrackLimit = 4;
    public $allPlaylistTracks;
    public $userinfo;
    private $soundcloudClientId = 'YOUR_SOUNDCLOUD_CLIENT_ID';
    private $soundcloudApiUrl = 'https://api-v2.soundcloud.com';
    public $playListTrackShow = false;

    // Track which campaigns have been reposted
    public $repostedCampaigns = [];

    // Constants
    private const ITEMS_PER_PAGE = 10;

    protected $queryString = [
        'selectedGenres',
        'recommended_proPage' => ['except' => 1],
        'recommendedPage' => ['except' => 1],
        'allPage' => ['except' => 1],
    ];

    ############################## Campaign Creation ##########################
    // Total number of campaigns
    public $totalCampaign;
    public $totalRecommended;
    public $totalRecommendedPro;

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
    public $maxFollower = 1000;
    public $followersLimit = 0;
    public $maxRepostLast24h = 0;
    public $maxRepostsPerDay = 0;
    public $targetGenre = 'anyGenre';
    public $user = null;
    public $showOptions = false;

    public $musicId = null;
    public $musicType = null;
    public $title = null;
    public $description = null;
    public $playlistId = null;

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

    // Properties for Delete functionality
    public $campaignToDeleteId = null;
    public $refundAmount = 0;

    // Properties to track budget warnings and validation
    public $showBudgetWarning = false;
    public $budgetWarningMessage = '';
    public $canSubmit = false;

    // Confirmation Repost
    public $totalRepostPrice = 0;
    public $campaign = null;
    public $liked = true;
    public $alreadyLiked = false;
    public $commented = null;
    public $followed = true;
    public $alreadyFollowing = false;
    public $availableRepostTime = null;

    public $showSubmitModal = false;
    public $showCampaignsModal = false;
    public bool $showAddCreditModal = false;
    public bool $showEditCampaignModal = false;
    public bool $showCancelWarningModal = false;
    public bool $showLowCreditWarningModal = false;
    public bool $showRepostConfirmationModal = false;
    ################################loadmore########################################

    // Properties for "Load More"
    public $tracksPage = 1;
    public $playlistsPage = 1;
    public $perPage = 4;
    public $hasMoreTracks = false;
    public $hasMorePlaylists = false;

    ############################## Campaign Creation ##########################

    protected ?CampaignService $campaignService = null;
    protected ?TrackService $trackService = null;
    protected ?PlaylistService $playlistService = null;
    protected ?SoundCloudService $soundCloudService = null;
    protected ?AnalyticsService $analyticsService = null;
    protected ?MyCampaignService $myCampaignService = null;
    protected ?UserSettingsService $userSettingsService = null;
    protected ?StarredUserService $starredUserService = null;

    public function boot(CampaignService $campaignService, TrackService $trackService, PlaylistService $playlistService, SoundCloudService $soundCloudService, AnalyticsService $analyticsService, MyCampaignService $myCampaignService, UserSettingsService $userSettingsService, StarredUserService $starredUserService)
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


    public function mount(Request $request)
    {

        if (session()->has('repostedId')) {
            session()->forget('repostedId');
        }

        $this->getAllTrackTypes();
        $this->totalCampaigns();
        $this->calculateFollowersLimit();
        if ($this->activeMainTab === 'all' || $this->activeMainTab === 'recommended_pro') {
            $this->selectedGenres = !empty($this->selectedGenres) && $this->selectedGenres !== ['all'] ? $this->selectedGenres : [];
        } else {
            $this->selectedGenres = !empty($this->selectedGenres) ? $this->selectedGenres : user()->genres->pluck('genre')->toArray();
        }
    }
    public function updated($propertyName)
    {
        $this->soundCloudService->refreshUserTokenIfNeeded(user());
        if (in_array($propertyName, ['credit', 'likeable', 'commentable'])) {
            $this->calculateFollowersLimit();
        }
        if (in_array($propertyName, ['activeMainTab', 'campaigns'])) {
            $this->dispatch('soundcloud-widgets-reinitialize');
        }
    }

    public function calculateFollowersLimit()
    {
        $this->followersLimit = ($this->credit - ($this->likeable ? 2 : 0) - ($this->commentable ? 2 : 0)) * 100;
    }

    public function navigatingAway(Request $request)
    {
        $params = [
            'tab' => $this->activeMainTab,
        ];

        if (!empty($this->selectedGenres)) {
            $params['selectedGenres'] = $this->selectedGenres;
        }

        if (!empty($this->search)) {
            $params['q'] = $this->search;
        }

        if (!empty($this->searchMusicType)) {
            $params['type'] = $this->searchMusicType;
        }

        return $this->redirect(route('user.cm.campaigns') . '?' . http_build_query($params), navigate: true);
    }

    // protected function rules()
    // {
    //     $rules = [
    //         'credit' => [
    //             'required',
    //             'integer',
    //             'min:50',
    //             function ($attribute, $value, $fail) {
    //                 if ($value > userCredits()) {
    //                     $fail('The credit is not available.');
    //                 }
    //             },
    //         ],
    //     ];

    //     return $rules;
    // }

    protected function messages()
    {
        return [
            'credit.required' => 'Minimum credit is 100.',
            'maxFollower.required' => 'Max follower is required.',
        ];
    }

    protected function creditRules()
    {
        return [
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
        ];
    }

    protected function commentedRules()
    {
        return [
            'commented' => [
                'nullable',
                'string',
                'min:5',
                function ($attribute, $value, $fail) {
                    if (!$value) return; // skip if null or empty

                    // Require at least 2 words
                    if (str_word_count(trim($value)) < 2) {
                        $fail('Your comment must contain at least two words.');
                    }

                    // Prevent repetitive letters: e.g., "aaaaaa", "bbbbbbb"
                    $plainValue = preg_replace('/\s+/', '', $value);
                    if (preg_match('/([a-zA-Z])\1{4,}/i', $plainValue)) {
                        $fail('Your comment looks like spam.');
                    }

                    // Disallow SoundCloud links (self-promo)
                    if (preg_match('/https?:\/\/(soundcloud\.com|snd\.sc)/i', $value)) {
                        $fail('Posting SoundCloud track links is not allowed.');
                    }

                    // Disallow other external links (optional)
                    if (preg_match('/https?:\/\/(?!soundcloud\.com|snd\.sc)[^\s]+/i', $value)) {
                        $fail('Posting external links is not allowed in comments.');
                    }

                    // Prevent excessive punctuation like !!! or ????
                    if (preg_match('/([!?.,])\1{3,}/', $value)) {
                        $fail('Please avoid excessive punctuation.');
                    }

                    // Prevent repeating same word multiple times
                    if (preg_match('/\b(\w+)\b(?:.*\b\1\b){3,}/i', $value)) {
                        $fail('Please avoid repeating the same word too many times.');
                    }

                    // Optional: check for common spammy words (like "check out my track")
                    $spamWords = ['check out', 'subscribe', 'follow me', 'free download', 'visit my profile'];
                    foreach ($spamWords as $spam) {
                        if (stripos($value, $spam) !== false) {
                            $fail('Your comment looks like self-promotion or spam.');
                        }
                    }
                },
            ],
        ];
    }


    /**
     * Set the active main tab and reset pagination for that tab
     */
    public function setActiveMainTab(string $tab): void
    {
        $this->reset();
        $this->activeMainTab = $tab;
        $this->navigatingAway(request());

        switch ($tab) {
            case 'recommended_pro':
                $this->resetPage('recommended_proPage');
                $this->{$tab . 'Page'} = 1;
                $this->selectedGenres = [];
                break;

            case 'recommended':
                $this->resetPage('recommendedPage');
                $this->{$tab . 'Page'} = 1;
                $this->selectedGenres = user()->genres->pluck('genre')->toArray() ?? [];
                break;

            case 'all':
                $this->resetPage('allPage');
                $this->{$tab . 'Page'} = 1;
                $this->selectedGenres = [];
                break;

            default:
                $this->resetPage('recommended_proPage');
                $this->selectedGenres = user()->genres->pluck('genre')->toArray() ?? [];
        }

        $this->totalCampaigns();
        $this->dispatch('soundcloud-widgets-reinitialize');
    }


    /**
     * Get the base campaigns query with common filters
     */
    // public $repostedId = null;
    private function getCampaignsQuery(): Builder
    {
        $allowedTargetCredits = user()->repost_price;
        $baseQuery = ModelsCampaign::whereRaw('(budget_credits - credits_spent) >= ?', [$allowedTargetCredits])
            ->withoutSelf()
            ->open()
            ->with(['music.user.userInfo', 'reposts', 'user.starredUsers']);

        if (session()->has('repostedId') && session()->get('repostedId') != null) {
            $baseQuery->where(function ($query) {
                $query->whereDoesntHave('reposts', function ($q) {
                    $q->where('reposter_urn', user()->urn)
                        ->where('campaign_id', '!=', session()->get('repostedId'));
                });
            });
        } else {
            $baseQuery->whereDoesntHave('reposts', function ($query) {
                $query->where('reposter_urn', user()->urn);
            });
        }

        // if (session()->has('repostedIds') && !empty(session()->get('repostedIds'))) {
        //     $repostedIds = session()->get('repostedIds');

        //     $baseQuery->where(function ($query) use ($repostedIds) {
        //         $query->whereDoesntHave('reposts', function ($q) use ($repostedIds) {
        //             $q->where('reposter_urn', user()->urn)
        //                 ->whereIn('campaign_id', $repostedIds);
        //         });
        //     });
        // } else {
        //     $baseQuery->whereDoesntHave('reposts', function ($query) {
        //         $query->where('reposter_urn', user()->urn);
        //     });
        // }

        $baseQuery->orderByRaw('CASE
            WHEN boosted_at >= ? THEN 0
            WHEN featured_at >= ? THEN 1
            ELSE 2
        END', [now()->subMinutes(15), now()->subHours(24)])
            ->orderBy('created_at', 'desc');
        return $baseQuery;
    }

    /**
     * Apply search and filter conditions to the query
     */
    private function applyFilters(Builder $query): Builder
    {
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

        // if ($this->activeMainTab != 'all' && $this->selectedGenres !== ['all']) {
        //     $this->selectedGenres = (!empty($this->selectedGenres) && $this->selectedGenres !== ['all']) ? $this->selectedGenres : user()->genres->pluck('genre')->toArray();
        // }
        if (!empty($this->selectedGenres) && $this->selectedGenres !== ['all']) {
            $query->whereHas('music', function ($q) {
                $q->whereIn('genre', $this->selectedGenres);
            });
        }



        if (!empty($this->searchMusicType) && $this->searchMusicType != 'all') {
            $query->where('music_type', 'like', "%{$this->searchMusicType}%");
        }

        return $query;
    }

    public function updatedSearch()
    {
        if (strlen($this->search) >= 0) {
            $this->loadTagSuggestions();
            $this->showSuggestions = true;
        } else {
            $this->suggestedTags = [];
            $this->showSuggestions = false;
        }

        // Reset pagination when search changes
        $this->resetPage($this->getActivePageName());
    }

    /**
     * Get the current active page parameter name
     */
    private function getActivePageName(): string
    {
        return match ($this->activeMainTab) {
            'recommended_pro' => 'recommended_proPage',
            'recommended' => 'recommendedPage',
            'all' => 'allPage',
            default => 'recommended_proPage'
        };
    }

    public function getAllTags()
    {
        $this->showSelectedTags = true;

        $this->suggestedTags = $this->trackService->getTracks()
            ->pluck('tag_list')
            ->flatten()
            ->map(function ($tagString) {
                return array_map('trim', explode(',', $tagString));
            })
            ->flatten()
            ->unique()
            ->filter(function ($tag) {
                return stripos($tag, $this->search) !== false && !in_array($tag, $this->selecteTags);
            })
            ->take(10)
            ->values()
            ->toArray();
    }

    public function loadTagSuggestions()
    {
        $this->suggestedTags = $this->campaignService->getCampaigns()
            ->with('music')
            ->get()
            ->pluck('music.tag_list')
            ->flatten()
            ->map(function ($tagString) {
                return array_map('trim', explode(',', $tagString));
            })
            ->flatten()
            ->unique()
            ->filter(function ($tag) {
                return stripos($tag, $this->search) !== false && !in_array($tag, $this->selectedTags);
            })
            ->take(10)
            ->values()
            ->toArray();

        $this->showSuggestions = !empty($this->suggestedTags);
    }

    public function selectTag($tag)
    {
        if (!in_array($tag, $this->selectedTags)) {
            $this->selectedTags[] = $tag;
            $this->search = $tag;
            $this->suggestedTags = [];
            $this->showSuggestions = false;
            $this->navigatingAway(request());
            $this->resetPage($this->getActivePageName());
        }
    }
    public function toggleGenre($genre)
    {
        $tab = $this->activeMainTab;
        $this->{$tab . 'Page'} = 1;
        if (in_array($genre, $this->selectedGenres)) {
            $this->selectedGenres = array_diff($this->selectedGenres, [$genre]);
            if ($this->selectedGenres == []) {
                $this->selectedGenres = array_diff($this->selectedGenres, [$genre]) == [] ? ['all'] : [];
            }
        } else {
            $this->selectedGenres[] = $genre;
        }
        $this->navigatingAway(request());
        $this->totalCampaigns();
    }

    public function filterByTrackType($Type)
    {
        $this->searchMusicType = $Type;
        $this->navigatingAway(request());

        $this->resetPage($this->getActivePageName());
    }

    public function removeTag($tag)
    {
        $key = array_search($tag, $this->selectedTags);
        if ($key !== false) {
            unset($this->selectedTags[$key]);
            $this->selectedTags = array_values($this->selectedTags);
            $this->resetPage($this->getActivePageName());
        }
    }

    public function hideSuggestions()
    {
        $this->showSuggestions = false;
    }

    public function getAllTrackTypes()
    {
        $this->selectedTrackTypes = $this->trackService->getTracks()
            ->where('user_urn', user()->urn)
            ->pluck('type')
            ->unique()
            ->values()
            ->toArray();
    }

    public function selectTrackType($type)
    {
        $this->selectedTrackType = $type;
        $this->resetPage($this->getActivePageName());
    }

    public function setactiveTab($tab)
    {
        $this->activeTab = $tab;
    }

    /**
     * Campaign Creation Methods
     */
    public function selectModalTab($tab = 'tracks')
    {
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

        $this->reset(['searchQuery']);
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

    public function toggleCampaignsModal()
    {
        if (!is_email_verified()) {
            $this->dispatch('alert', type: 'error', message: 'Please verify your email to create a campaign.');
            return;
        }
        $this->reset();

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
            'campaignToDeleteId',
            'refundAmount',
            'showBudgetWarning',
            'budgetWarningMessage',
            'canSubmit',
        ]);

        $this->user = User::where('urn', user()->urn)->first()->activePlan();

        if (userCredits() < 50) {
            $this->showLowCreditWarningModal = true;
            $this->showSubmitModal = false;
            return;
        } else {
            $this->showLowCreditWarningModal = false;
        }


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
            $musicId = $this->musicType === Track::class ? $this->musicId : $this->playlistId;
            $exists = ModelsCampaign::where('user_urn', user()->urn)
                ->where('music_id', $musicId)
                ->where('music_type', $this->musicType)
                ->open()->exists();
            if ($exists) {
                $this->dispatch('alert', type: 'error', message: 'You already have an active campaign for this track. Please end or close it before creating a new one.');
                return;
            }
            $this->showSubmitModal = true;
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
            return $this->dispatch('alert', type: 'error', message: 'You need to be a pro user to use this feature');;
        } elseif (($this->credit * 2) > userCredits()) {
            $this->proFeatureEnabled = $isChecked ? true : false;
            $this->proFeatureValue = $isChecked ? 1 : 0;
        } else {
            $this->proFeatureEnabled = $isChecked ? false : true;
            $this->proFeatureValue = $isChecked ? 0 : 1;
        }
    }

    public function createCampaign()
    {
        $this->validate($this->creditRules());

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
                    'max_followers' => $this->maxFollowerEnabled ? $this->maxFollower : null,
                    'creater_id' => user()->id,
                    'creater_type' => get_class(user()),
                    'commentable' => $commentable,
                    'likeable' => $likeable,
                    'pro_feature' => $proFeatureEnabled,
                    'momentum_price' => $proFeatureEnabled == 1 ? $this->credit / 2 : 0,
                    'max_repost_last_24_h' => $this->maxRepostLast24h,
                    'max_repost_per_day' => $this->repostPerDayEnabled ? $this->maxRepostsPerDay : 0,
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


            $this->dispatch('alert', type: 'success', message: 'Campaign created successfully!');

            // $this->dispatch('campaignCreated');

            $this->showCampaignsModal = false;
            $this->showSubmitModal = false;
            $this->reset();
            $this->resetValidation();
            $this->resetErrorBag();
        } catch (\Exception $e) {
            $this->dispatch('alert', type: 'error', message: 'Failed to create campaign: ' . $e->getMessage());

            Log::error('Campaign creation error: ' . $e->getMessage(), [
                'music_id' => $this->musicId,
                'user_urn' => user()->urn ?? 'unknown',
                'title' => $this->title,
                'total_budget' => $totalBudget ?? 0,
            ]);
        }
    }

    /**========================================
     * Audio Player Event Handlers
     =========================================*/
    public function handleAudioPlay($campaignId)
    {
        // Log::info('handleAudioPlay Campaign ID: ' . $campaignId);
        $this->playingCampaigns[$campaignId] = true;
        $this->playStartTimes[$campaignId] = now()->timestamp;
    }

    public function handleAudioPause($campaignId)
    {
        // Log::info('handleAudioPlay Campaign ID: ' . $campaignId);
        $this->updatePlayTime($campaignId);
        unset($this->playingCampaigns[$campaignId]);
        unset($this->playStartTimes[$campaignId]);
    }


    public function handleAudioTimeUpdate(int $campaignId, float $currentTime): void
    {
        if ($currentTime >= 5 && !in_array($campaignId, $this->playedCampaigns)) {
            $this->playedCampaigns[] = $campaignId;

            // FIX: Dispatch an event that the button's Alpine.js block can listen to
            // $this->dispatch('campaign-played-5s', id: $campaignId)->self();
        }
    }

    public function handleAudioEnded($campaignId)
    {
        $this->handleAudioPause($campaignId);
    }

    private function updatePlayTime($campaignId)
    {
        if (isset($this->playStartTimes[$campaignId])) {
            $playDuration = now()->timestamp - $this->playStartTimes[$campaignId];
            $this->playTimes[$campaignId] = ($this->playTimes[$campaignId] ?? 0) + $playDuration;

            if ($this->playTimes[$campaignId] >= 5 && !in_array($campaignId, $this->playedCampaigns)) {
                $this->playedCampaigns[] = $campaignId;
                // $this->dispatch('campaignPlayedEnough', $campaignId);
            }
        }
    }

    public function updatePlayingTimes()
    {
        foreach ($this->playingCampaigns as $campaignId => $isPlaying) {
            if ($isPlaying && isset($this->playStartTimes[$campaignId])) {
                $playDuration = now()->timestamp - $this->playStartTimes[$campaignId];
                $totalPlayTime = ($this->playTimes[$campaignId] ?? 0) + $playDuration;

                if ($totalPlayTime >= 5 && !in_array($campaignId, $this->playedCampaigns)) {
                    $this->playedCampaigns[] = $campaignId;
                    // $this->dispatch('campaignPlayedEnough', $campaignId);
                }
            }
        }
    }

    public function startPlaying($campaignId)
    {
        // Log::info('startPlaying campaignId: ' . $campaignId);
        // $this->reset([
        //     'playcount',
        //     'playedCampaigns',
        //     'repostedCampaigns',
        //     'campaign',
        //     'showRepostConfirmationModal',
        //     'commented',
        //     'liked',
        //     'followed',
        // ]);
        $this->reset();
        $this->handleAudioPlay($campaignId);
    }

    public function stopPlaying($campaignId)
    {
        // Log::info('stopPlaying campaignId: ' . $campaignId);
        $this->handleAudioPause($campaignId);
    }

    public function simulateAudioProgress($campaignId, $seconds = 1)
    {
        // Log::info('simulateAudioProgress campaignId: ' . $campaignId);
        if (!isset($this->playTimes[$campaignId])) {
            $this->playTimes[$campaignId] = 0;
        }

        $this->playTimes[$campaignId] += $seconds;

        if ($this->playTimes[$campaignId] >= 5 && !in_array($campaignId, $this->playedCampaigns)) {
            $this->playedCampaigns[] = $campaignId;
            $this->dispatch('alert', type: 'success', message: 'Campaign marked as played for 5+ seconds!');
        }
    }

    public function canRepost($campaignId): bool
    {
        // Log::info('canRepost campaignId: ' . $campaignId);
        $canRepost = in_array($campaignId, $this->playedCampaigns) &&
            !in_array($campaignId, $this->repostedCampaigns);

        if ($canRepost && !$this->playcount) {
            $campaign = $this->campaignService->getCampaign(encrypt($campaignId));

            if ($campaign->music_type == Track::class) {
                $this->reset('track');
                $this->track = $this->trackService->getTrack(encrypt($campaign->music_id));
            } elseif ($campaign->music_type == Playlist::class) {
                $this->reset('track');
                $playlist = Playlist::findOrFail($campaign->music_id);
                $this->track = $playlist;
            }

            $response = $this->analyticsService->recordAnalytics(source: $this->track, actionable: $campaign, type: UserAnalytics::TYPE_PLAY, genre: $campaign->target_genre);
            if ($response != false || $response != null) {
                $campaign->increment('playback_count');
            }

            $this->playcount = true;
            // $this->reset([
            //     'playcount',
            //     'playedCampaigns',
            //     'repostedCampaigns',
            //     'campaign',
            //     'showRepostConfirmationModal',
            //     'commented',
            //     'liked',
            //     'followed',
            // ]);
        }
        return $canRepost;
    }

    public function isPlaying($campaignId): bool
    {
        // Log::info('isPlaying campaignId: ' . $campaignId);
        return isset($this->playingCampaigns[$campaignId]) && $this->playingCampaigns[$campaignId] === true;
    }

    public function getPlayTime($campaignId): int
    {
        // Log::info('getPlayTime campaignId: ' . $campaignId);
        $baseTime = $this->playTimes[$campaignId] ?? 0;

        if ($this->isPlaying($campaignId) && isset($this->playStartTimes[$campaignId])) {
            $currentSessionTime = now()->timestamp - $this->playStartTimes[$campaignId];
            return $baseTime + $currentSessionTime;
        }

        return $baseTime;
    }

    public function getRemainingTime($campaignId): int
    {
        // Log::info('getRemainingTime campaignId: ' . $campaignId);
        $playTime = $this->getPlayTime($campaignId);
        return max(0, 5 - $playTime);
    }
    public function canRepost12Hours($userUrn)
    {
        $twelveHoursAgo = Carbon::now()->subHours(12);
        $reposts = Repost::where('reposter_urn', $userUrn)
            ->where('created_at', '>=', $twelveHoursAgo)
            ->orderBy('created_at', 'asc')
            ->take(10)
            ->get();

        if ($reposts->count() < 10) {
            return true;
        }

        $oldestRepostTime = $reposts->first()->created_at;

        // Store full time for later use
        $this->availableRepostTime = $oldestRepostTime->copy()->addHours(12);

        if (Carbon::now()->greaterThanOrEqualTo($this->availableRepostTime)) {
            return true;
        }

        return false;
    }

    public function confirmRepost(int $campaignId)
    {
        // $this->reset([
        //     "selectedTags",
        //     "selecteTags",
        //     "selectedGenre",
        //     "suggestedTags",
        //     "showSuggestions",
        //     "showSelectedTags",
        //     "isLoading",
        //     "selectedTrackId",
        //     // "selectedPlaylistId",
        //     "maxFollowerEnabled",
        //     "repostPerDayEnabled",
        //     "selectedTrackTypes",
        //     "selectedTrackType",
        //     "selectedGenres",
        //     "showTrackTypes",
        //     // "playingCampaigns",
        //     // "playStartTimes",
        //     // "playTimes",
        //     // "playedCampaigns",
        //     // "playcount",
        //     "searchQuery",
        //     "allTracks",
        //     "users",
        //     "trackLimit",
        //     "playlistLimit",
        //     "playlistTrackLimit",
        //     "allPlaylistTracks",
        //     "userinfo",
        //     "playListTrackShow",
        //     "tracks",
        //     "playlists",
        //     "playlistTracks",
        //     "track",
        //     "credit",
        //     "totalCredit",
        //     "commentable",
        //     "likeable",
        //     "proFeatureEnabled",
        //     "proFeatureValue",
        //     "maxFollower",
        //     "followersLimit",
        //     "maxRepostLast24h",
        //     "maxRepostsPerDay",
        //     "targetGenre",
        //     "user",
        //     "showOptions",
        //     "musicId",
        //     "musicType",
        //     "title",
        //     "description",
        //     "playlistId",
        //     "addCreditCampaignId",
        //     "addCreditCostPerRepost",
        //     "addCreditCurrentBudget",
        //     "addCreditTargetReposts",
        //     "addCreditCreditsNeeded",
        //     "editingCampaignId",
        //     "editTitle",
        //     "editDescription",
        //     "editEndDate",
        //     "editTargetReposts",
        //     "editCostPerRepost",
        //     "editOriginalBudget",
        //     "campaignToDeleteId",
        //     "refundAmount",
        //     "showBudgetWarning",
        //     "budgetWarningMessage",
        //     "canSubmit",
        //     "totalRepostPrice",
        //     "campaign",
        //     "liked",
        //     "alreadyLiked",
        //     "commented",
        //     "followed",
        //     "alreadyFollowing",
        //     "availableRepostTime",
        // ]);
        if ($this->todayRepost >= 20) {
            $endOfDay = Carbon::today()->addDay();
            $hoursLeft = round(now()->diffInHours($endOfDay));
            $this->dispatch('alert', type: 'error', message: "You have reached your 24 hour repost limit. You can repost again {$hoursLeft} hours later.");
            return;
        }
        if (!$this->canRepost12Hours(user()->urn)) {
            $now = Carbon::now();
            $availableTime = $this->availableRepostTime; // Store this in your canRepost12Hours function
            $diff = $now->diff($availableTime);

            $hoursLeft = $diff->h;
            $minutesLeft = $diff->i;

            $message = "You have reached your 12 hour repost limit. You can repost again in ";

            if ($hoursLeft > 0) {
                $message .= "{$hoursLeft} hour" . ($hoursLeft > 1 ? "s" : "");
            }

            if ($hoursLeft > 0 && $minutesLeft > 0) {
                $message .= " ";
            }

            if ($minutesLeft > 0) {
                $message .= "{$minutesLeft} minute" . ($minutesLeft > 1 ? "s" : "");
            }

            return $this->dispatch('alert', type: 'error', message: $message);
        }




        if (!$this->canRepost($campaignId)) {
            $this->dispatch('alert', type: 'error', message: 'You cannot repost this campaign. Please play it for at least 5 seconds first.');
            return;
        }
        $this->showRepostConfirmationModal = true;
        $this->campaign = $this->campaignService->getCampaign(encrypt($campaignId))->load('music.user.userInfo');

        // #### Don't Remove This comment Code It is needed for future use ####

        // if ($this->campaign->music) {
        //     if ($this->campaign->music_type == Track::class) {
        //         $favoriteData = $this->soundCloudService->fetchTracksFavorites($this->campaign->music);
        //         $searchUrn = user()->urn;
        //     } elseif ($this->campaign->music_type == Playlist::class) {
        //         $favoriteData = $this->soundCloudService->fetchPlaylistFavorites(user()->urn);
        //         $searchUrn = $this->campaign->music->soundcloud_urn;
        //     }
        //     $collection = collect($favoriteData['collection']);
        //     $found = $collection->first(function ($item) use ($searchUrn) {
        //         return isset($item['urn']) && $item['urn'] === $searchUrn;
        //     });
        //     if ($found) {
        //         $this->liked = false;
        //         $this->alreadyLiked = true;
        //     }
        // }

        // $response = $this->soundCloudService->getAuthUserFollowers($this->campaign->music->user);
        // if ($response->isNotEmpty()) {
        //     $already_following = $response->where('urn', user()->urn)->first();
        //     if ($already_following !== null) {
        //         Log::info('Repost request Page:- Already following');
        //         $this->followed = false;
        //         $this->alreadyFollowing = true;
        //     }
        // }

        $this->reset(['liked', 'alreadyLiked', 'commented', 'followed', 'alreadyFollowing']);
        $baseQuery = UserAnalytics::where('owner_user_urn', $this->campaign?->music?->user?->urn)
            ->where('act_user_urn', user()->urn);

        // $followAble = (clone $baseQuery)->followed()->first();
        $likeAble = (clone $baseQuery)->liked()->where('source_type', get_class($this->campaign?->music))
            ->where('source_id', $this->campaign?->music?->id)->first();

        if ($this->campaign->likeable == 0) {
            $this->liked = false;
        }
        if ($likeAble !== null) {
            $this->liked = false;
            $this->alreadyLiked = true;
        }

        if ($this->campaign->commentable == 0) {
            $this->commentable = false;
        }

        // if ($followAble !== null) {
        //     $this->followed = false;
        //     $this->alreadyFollowing = true;
        // }

        $httpClient = Http::withHeaders([
            'Authorization' => 'OAuth ' . user()->token,
        ]);
        $userUrn = $this->campaign->user?->urn;
        $checkResponse = $httpClient->get("{$this->baseUrl}/me/followings/{$userUrn}");

        if ($checkResponse->getStatusCode() === 200) {
            $this->followed = false;
            $this->alreadyFollowing = true;
        }
    }
    public function closeConfirmModal(): void
    {
        $this->reset([
            'campaign',
            'liked',
            'alreadyLiked',
            'commented',
            'followed',
            'alreadyFollowing',
            'availableRepostTime'
        ]);
        $this->showRepostConfirmationModal = false;
    }

    public function updatedactiveTab($tab)
    {
        $this->activeTab = $tab;
        $this->searchQuery = '';
        if ($tab === 'tracks') {
            $this->fetchTracks();
        } elseif ($tab === 'playlists') {
            $this->fetchPlaylists();
        }
    }

    public function searchSoundcloud()
    {
        if (empty($this->searchQuery)) {
            if ($this->playListTrackShow == true && $this->activeTab === 'tracks') {
                $this->allPlaylistTracks = Playlist::findOrFail($this->selectedPlaylistId)->tracks()->get();
                $this->tracks = $this->allPlaylistTracks->take($this->playlistTrackLimit);
            } else {
                if ($this->activeTab == 'tracks') {
                    $this->allTracks = Track::where('user_urn', user()->urn)->get();
                    $this->tracks = $this->allTracks->take($this->trackLimit);
                }
                if ($this->activeTab == 'playlists') {
                    $this->allPlaylists = Playlist::where('user_urn', user()->urn)->get();
                    $this->playlists = $this->allPlaylists->take($this->playlistLimit);
                }
            }
            return;
        }

        if (preg_match('/^https?:\/\/(www\.)?soundcloud\.com\/[a-zA-Z0-9\-_]+(\/[a-zA-Z0-9\-_]+)*(\/)?(\?.*)?$/i', $this->searchQuery)) {
            if (proUser()) {
                $this->resolveSoundcloudUrl();
            } else {
                return $this->dispatch('alert', type: 'error', message: 'Please upgrade to a Pro User to use this feature.');
            }
        } else {
            if ($this->playListTrackShow == true && $this->activeTab === 'tracks') {
                $this->allPlaylistTracks = Playlist::findOrFail($this->selectedPlaylistId)->tracks()
                    ->where(function ($query) {
                        $query->where('permalink_url', 'like', '%' . $this->searchQuery . '%')
                            ->orWhere('title', 'like', '%' . $this->searchQuery . '%');
                    })
                    ->get();
                $this->tracks = $this->allPlaylistTracks->take($this->playlistTrackLimit);
            } else {
                if ($this->activeTab === 'tracks') {
                    $this->allTracks = Track::where('user_urn', user()->urn)
                        ->where(function ($query) {
                            $query->where('permalink_url', 'like', '%' . $this->searchQuery . '%')
                                ->orWhere('title', 'like', '%' . $this->searchQuery . '%');
                        })
                        ->get();
                    $this->tracks = $this->allTracks->take($this->trackLimit);
                } elseif ($this->activeTab === 'playlists') {
                    $this->allPlaylists = Playlist::where('user_urn', user()->urn)
                        ->where(function ($query) {
                            $query->where('permalink_url', 'like', '%' . $this->searchQuery . '%')
                                ->orWhere('title', 'like', '%' . $this->searchQuery . '%');
                        })
                        ->get();
                    $this->playlists = $this->allPlaylists->take($this->playlistLimit);
                }
            }
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

        $resolvedData = $this->soundCloudService->makeResolveApiRequest($this->searchQuery, 'Failed to resolve SoundCloud URL');
        if (isset($resolvedData) && $resolvedData != null) {
            $urn = $resolvedData['urn'];
            if ($this->activeTab === 'playlists') {
                if (isset($resolvedData['tracks']) && count($resolvedData['tracks']) > 0) {
                    $this->soundCloudService->unknownPlaylistAdd($resolvedData);
                    Log::info('Resolved SoundCloud URL: ' . "Successfully resolved SoundCloud URL: " . $this->searchQuery);
                } else {
                    $this->dispatch('alert', type: 'error', message: 'Could not resolve the SoundCloud link. Please check the Playlist URL.');
                }
            } elseif ($this->activeTab === 'tracks') {
                if (!isset($resolvedData['tracks'])) {
                    $this->soundCloudService->unknownTrackAdd($resolvedData);
                    Log::info('Resolved SoundCloud URL: ' . "Successfully resolved SoundCloud URL: " . $this->searchQuery);
                } else {
                    $this->dispatch('alert', type: 'error', message: 'Could not resolve the SoundCloud link. Please check the Track URL.');
                }
            }
            $this->processSearchData($urn);
            Log::info('Resolved SoundCloud URL: ' . "Successfully resolved SoundCloud URL: " . $this->searchQuery);
        } else {
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

    public function showPlaylistTracks($playlistId)
    {
        $this->selectedPlaylistId = $playlistId;
        $playlist = Playlist::with('tracks')->find($playlistId);

        if ($playlist) {
            $this->allPlaylistTracks = $playlist->tracks;
            $this->tracks = $this->allPlaylistTracks->take($this->perPage);
            $this->hasMoreTracks = $this->allPlaylistTracks->count() > $this->perPage;
        } else {
            $this->resetTrackCollections();
        }

        $this->activeTab = 'tracks';
        $this->playListTrackShow = true;
        $this->tracksPage = 1;
        $this->searchQuery = '';
    }


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

    public function fetchPlaylists()
    {
        try {
            $this->soundCloudService->syncUserPlaylists(user());

            // Get all playlists first
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
    private function resetTrackCollections(): void
    {
        $this->tracks = collect();
        $this->allTracks = collect();
        $this->hasMoreTracks = false;
    }

    public function openRepostsModal($trackId)
    {
        $this->toggleSubmitModal('track', $trackId);
    }
    public function totalCampaigns()
    {
        if ($this->activeMainTab === 'all') {
            $this->totalCampaign = $this->getCampaignsQuery()
                ->whereHas('music', function ($query) {
                    if (!empty($this->selectedGenres) && $this->selectedGenres !== ['all']) {
                        $query->whereIn('genre', $this->selectedGenres);
                    }
                })->count();
        } else {
            $this->totalCampaign = $this->getCampaignsQuery()->count();
        }

        if ($this->activeMainTab === 'recommended_pro') {
            $query = $this->getCampaignsQuery()
                ->whereHas('user', function ($query) {
                    $query->isPro();
                });

            $query->whereHas('music', function ($query) {
                if (!empty($this->selectedGenres) && $this->selectedGenres !== ['all']) {
                    $query->whereIn('genre', $this->selectedGenres);
                }
            });

            $this->totalRecommendedPro = $query->count();
        } else {
            $this->totalRecommendedPro = $this->getCampaignsQuery()
                ->whereHas('user', function ($query) {
                    $query->isPro();
                })->count();
        }

        if ($this->activeMainTab === 'recommended') {
            $query = $this->getCampaignsQuery();
            if ($this->selectedGenres !== ['all']) {
                $query->whereHas('music', function ($query) {
                    $userGenres = !empty($this->selectedGenres) ? $this->selectedGenres : user()->genres->pluck('genre')->toArray();
                    $query->whereIn('genre', $userGenres);
                });
            }
            $this->totalRecommended = $query->count();
        } else {
            $this->totalRecommended = $this->getCampaignsQuery()
                ->whereHas('music', function ($query) {
                    $userGenres = user()->genres->pluck('genre')->toArray() ?? [];
                    $query->whereIn('genre', $userGenres);
                })->count();
        }
    }

    public function responseReset()
    {
        $responseAt = UserSetting::self()->value('response_rate_reset');
        if ($responseAt && Carbon::parse($responseAt)->greaterThan(now()->subDays(30))) {
            $this->dispatch('alert', type: 'error', message: 'You can only reset your response rate once every 30 days.');
            return;
        }
        $userUrn = user()->urn;
        $this->userSettingsService->createOrUpdate($userUrn, ['response_rate_reset' => now()]);
        $this->dispatch('alert', type: 'success', message: 'Your response rate has been reset.');
    }

    public function repost($campaignId)
    {
        $this->validate($this->commentedRules());
        try {
            if (!$this->canRepost($campaignId)) {
                $this->dispatch('alert', type: 'error', message: 'You cannot repost this campaign. Please play it for at least 5 seconds first.');
                return;
            }

            $currentUserUrn = user()->urn;

            if ($this->campaignService->getCampaigns()->where('id', $campaignId)->where('user_urn', $currentUserUrn)->exists()) {
                $this->dispatch('alert', type: 'error', message: 'You cannot repost your own campaign.');
                return;
            }

            if (Repost::where('reposter_urn', $currentUserUrn)->where('campaign_id', $campaignId)->exists()) {
                $this->dispatch('alert', type: 'error', message: 'You have already reposted this campaign.');
                return;
            }

            $campaign = $this->campaignService->getCampaign(encrypt($campaignId))->load('music.user.userInfo');
            switch ($campaign->music_type) {
                case Track::class:
                    $musicUrn = $campaign->music->urn;
                    break;
                case Playlist::class:
                    $musicUrn = $campaign->music->soundcloud_urn;
                    break;
            }

            if (!$campaign->music) {
                $this->dispatch('alert', type: 'error', message: 'Track or Playlist not found for this campaign.');
                return;
            }

            $soundcloudRepostId = null;

            $httpClient = Http::withHeaders([
                'Authorization' => 'OAuth ' . user()->token,
            ]);
            $commentSoundcloud = [
                'comment' => [
                    'body' => $this->commented,
                    'timestamp' => time()
                ]
            ];

            $response = null;
            $like_response = null;
            $comment_response = null;
            $follow_response = null;
            $increse_likes = false;
            $increse_reposts = false;

            switch ($campaign->music_type) {
                case Track::class:
                    $checkLiked = $this->soundCloudService->makeGetApiRequest(endpoint: '/tracks/' . $musicUrn, errorMessage: 'Failed to fetch track details');
                    $previous_likes = $checkLiked['collection']['favoritings_count'];
                    $previous_reposts = $checkLiked['collection']['reposts_count'];

                    $response = $httpClient->post("{$this->baseUrl}/reposts/tracks/{$musicUrn}");
                    Log::info('repost response for track urn:' . $musicUrn . 'response: ' . json_encode($response));

                    if ($this->commented) {
                        if ($campaign?->music?->commentable == true) {
                            $comment_response = $httpClient->post("{$this->baseUrl}/tracks/{$musicUrn}/comments", $commentSoundcloud);
                            Log::info('comment_response for track urn:' . $musicUrn . 'response: ' . json_encode($comment_response));
                        }
                    }
                    if ($this->liked) {
                        $like_response = $httpClient->post("{$this->baseUrl}/likes/tracks/{$musicUrn}");
                        Log::info('like_response for track urn:' . $musicUrn . 'response: ' . json_encode($like_response));
                    }
                    if ($this->followed) {
                        $follow_response = $httpClient->put("{$this->baseUrl}/me/followings/{$campaign->user?->urn}");
                        Log::info('follow_response for track urn:' . $musicUrn . 'response: ' . json_encode($follow_response));
                    }

                    $checkLiked = $this->soundCloudService->makeGetApiRequest(endpoint: '/tracks/' . $musicUrn, errorMessage: 'Failed to fetch track details');
                    $newLikes = $checkLiked['collection']['favoritings_count'];
                    $newReposts = $checkLiked['collection']['reposts_count'];
                    if ($newLikes > $previous_likes && $like_response != null) {
                        $increse_likes = true;
                    }
                    if ($newReposts > $previous_reposts) {
                        $increse_reposts = true;
                    }
                    break;

                case Playlist::class:
                    $checkLiked = $this->soundCloudService->makeGetApiRequest(endpoint: '/playlists/' . $musicUrn, errorMessage: 'Failed to fetch playlist details');
                    $previous_likes = $checkLiked['collection']['likes_count'];
                    $previous_reposts = $checkLiked['collection']['repost_count'];

                    $response = $httpClient->post("{$this->baseUrl}/reposts/playlists/{$musicUrn}");
                    Log::info('repost response for playlist urn:' . $musicUrn . 'response: ' . json_encode($response));

                    if ($this->liked) {
                        $like_response = $httpClient->post("{$this->baseUrl}/likes/playlists/{$musicUrn}");
                        Log::info('like_response for playlist urn:' . $musicUrn . 'response: ' . json_encode($like_response));
                    }
                    if ($this->commented) {
                        $comment_response = $httpClient->post("{$this->baseUrl}/playlists/{$musicUrn}/comments", $commentSoundcloud);
                        Log::info('comment_response for playlist urn:' . $musicUrn . 'response: ' . json_encode($comment_response));
                    }
                    if ($this->followed) {
                        $follow_response = $httpClient->put("{$this->baseUrl}/me/followings/{$campaign->music?->user?->urn}");
                        Log::info('follow_response for playlist urn:' . $musicUrn . 'response: ' . json_encode($follow_response));
                    }

                    $checkLiked = $this->soundCloudService->makeGetApiRequest(endpoint: '/playlists/' . $musicUrn, errorMessage: 'Failed to fetch playlist details');
                    $newLikes = $checkLiked['collection']['likes_count'];
                    $newReposts = $checkLiked['collection']['repost_count'];
                    if ($newLikes > $previous_likes && $like_response != null) {
                        $increse_likes = true;
                    }
                    if ($newReposts > $previous_reposts) {
                        $increse_reposts = true;
                    }
                    break;

                default:
                    $this->dispatch('alert', type: 'error', message: 'Invalid music type specified for the campaign.');
                    return;
            }

            if ($increse_reposts == false) {
                $this->dispatch('alert', type: 'error', message: 'You have already repost this ' . ($campaign->music_type == Track::class ? 'track' : 'playlist') . ' from your soundcloud');
                return;
            }

            if ($this->followed) {
                $userUrn = $campaign->user?->urn;
                $checkResponse = $httpClient->get("{$this->baseUrl}/me/followings/{$userUrn}");

                if ($checkResponse->getStatusCode() === 200) {
                    $alreadyFollowing = true;
                } else {
                    $alreadyFollowing = false;
                }

                if (!$alreadyFollowing) {
                    $follow_response = $httpClient->put("{$this->baseUrl}/me/followings/{$userUrn}");
                }
            }

            $data = [
                'likeable' => $like_response != null ? ($like_response->successful() && $increse_likes ? true : false) : false,
                'comment' => $comment_response != null ? ($comment_response->successful() ? true : false) : false,
                'follow' => $follow_response != null ? $follow_response->successful() : true,
            ];

            if ($response->successful()) {
                $repostEmailPermission = hasEmailSentPermission('em_repost_accepted', $campaign->user->urn);
                if ($repostEmailPermission) {
                    $datas = [
                        [
                            'email' => $campaign->user->email,
                            'subject' => 'Repost Notification',
                            'title' => 'Dear ' . $campaign->user->name,
                            'body' => 'Your ' . $campaign->title . ' has been reposted successfully.',
                        ],
                    ];
                    NotificationMailSent::dispatch($datas);
                }

                $soundcloudRepostId = $campaign->music->soundcloud_track_id;
                $this->campaignService->syncReposts($campaign, user(), $soundcloudRepostId, $data);

                $this->dispatch('alert', type: 'success', message: 'Campaign music reposted successfully.' . ($increse_likes ? '' : 'Liked not done due you have already liked this track.'));

                $this->reset([
                    'showRepostConfirmationModal',
                    'campaign',
                    'liked',
                    'alreadyLiked',
                    'commented',
                    'followed',
                    'alreadyFollowing',
                    'commentable',
                ]);
                // $this->reset();
                $this->navigatingAway(request());
                $this->repostedCampaigns[] = $campaignId;

                session()->put('repostedId', $campaignId);

                // $reposted = session()->get('repostedIds', []);
                // $reposted[] = $campaignId;
                // session()->put('repostedIds', $reposted);
            } else {
                Log::error("SoundCloud Repost Failed: " . $response->body(), [
                    'campaign_id' => $campaignId,
                    'user_urn' => $currentUserUrn,
                    'status' => $response->status(),
                ]);
                $this->dispatch('alert', type: 'error', message: 'Failed to repost campaign music to SoundCloud. Please try again.');
            }
        } catch (Throwable $e) {
            Log::error("Error in repost method: " . $e->getMessage(), [
                'exception' => $e,
                'campaign_id_input' => $campaignId,
                'user_urn' => user()->urn ?? 'N/A',
            ]);
            $this->dispatch('alert', type: 'error', message: 'An unexpected error occurred. Please try again later.');
            return;
        }
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

    public function render()
    {
        try {
            $user = User::withCount([
                'reposts as reposts_count_today' => function ($query) {
                    $query->whereBetween('created_at', [Carbon::today(), Carbon::tomorrow()]);
                },
                'campaigns' => function ($query) {
                    $query->open();
                },
                'requests' => function ($query) {
                    $query->pending();
                },
            ])->find(user()->id);
            $user->load('userInfo');

            $this->todayRepost = $user->reposts_count_today ?? 0;

            $data['dailyRepostCurrent'] = $this->todayRepost;
            $data['totalMyCampaign'] = $user->campaigns_count ?? 0;
            $data['pendingRequests'] = $user->requests_count ?? 0;

            $baseQuery = $this->getCampaignsQuery();
            $baseQuery = $this->applyFilters($baseQuery);

            $userFollowersCount = $user?->userInfo?->followers_count ?? 0;

            $baseQuery->where(function ($query) use ($userFollowersCount) {
                $query->whereNull('max_followers')
                    ->orWhere(function ($q) use ($userFollowersCount) {
                        $q->whereNotNull('max_followers')
                            ->where('max_followers', '>=', $userFollowersCount);
                    });
            });

            $campaigns = collect();
            switch ($this->activeMainTab) {
                case 'recommended_pro':
                    $baseQuery->whereHas('user', function ($query) {
                        $query->isPro();
                    });
                    // if ($this->selectedGenres !== ['all']) {
                    // $baseQuery->whereHas('music', function ($query) {
                    //     $userGenres = !empty($this->selectedGenres) ? $this->selectedGenres : user()->genres->pluck('genre')->toArray();
                    //     $query->whereIn('genre', $userGenres);
                    // });
                    // }
                    $baseQuery->whereHas('music', function ($query) {
                        if (!empty($this->selectedGenres) && $this->selectedGenres !== ['all']) {
                            $query->whereIn('genre', $this->selectedGenres);
                        }
                    });
                    $campaigns = $baseQuery->paginate(self::ITEMS_PER_PAGE, ['*'], 'recommended_proPage', $this->recommended_proPage);
                    break;

                case 'recommended':
                    if ($this->selectedGenres !== ['all']) {
                        $baseQuery->whereHas('music', function ($query) {
                            $userGenres = !empty($this->selectedGenres) ? $this->selectedGenres : user()->genres->pluck('genre')->toArray();
                            $query->whereIn('genre', $userGenres);
                        });
                    }
                    $campaigns = $baseQuery->paginate(self::ITEMS_PER_PAGE, ['*'], 'recommendedPage', $this->recommendedPage);
                    break;

                case 'all':
                    $campaigns = $baseQuery
                        ->whereHas('music', function ($query) {
                            if (!empty($this->selectedGenres) && $this->selectedGenres !== ['all']) {
                                $query->whereIn('genre', $this->selectedGenres);
                            }
                        })
                        ->paginate(self::ITEMS_PER_PAGE, ['*'], 'allPage', $this->allPage);
                    break;

                default:
                    $baseQuery->whereHas('user', function ($query) {
                        $query->isPro();
                    });
                    if ($this->selectedGenres !== ['all']) {
                        $baseQuery->whereHas('music', function ($query) {
                            $userGenres = !empty($this->selectedGenres) ? $this->selectedGenres : user()->genres->pluck('genre')->toArray();
                            $query->whereIn('genre', $userGenres);
                        });
                    }
                    $campaigns = $baseQuery->paginate(self::ITEMS_PER_PAGE, ['*'], 'recommended_proPage', $this->recommended_proPage);
                    break;
            }
            Bus::dispatch(new TrackViewCount($campaigns, user()->urn, 'campaign'));

            return view('livewire.user.campaign-management.campaign', [
                'campaigns' => $campaigns,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to load campaigns: ' . $e->getMessage(), [
                'user_urn' => user()->urn ?? 'unknown',
                'active_tab' => $this->activeMainTab,
                'exception' => $e
            ]);
            $campaigns = collect();
            return view('livewire.user.campaign-management.campaign', [
                'campaigns' => $campaigns
            ]);
        }
    }
}
