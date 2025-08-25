<?php

namespace App\Livewire\User\ProfileManagement;

use App\Models\CreditTransaction;
use App\Models\Playlist;
use App\Models\Repost;
use App\Models\Track;
use App\Services\Admin\CreditManagement\CreditTransactionService;
use App\Services\Admin\UserManagement\UserService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class MyAccount extends Component
{
    use WithPagination;

    protected string $baseUrl = 'https://api.soundcloud.com';

    // UI state
    #[Url(as: 'tab', except: 'insights')]
    public string $activeTab = 'insights';

    public bool $showEditProfileModal = false;

    // Playlist detail state
    #[Url(as: 'selectedPlaylistId')]
    public ?int $selectedPlaylistId = null;

    public bool $showPlaylistTracks = false;

    // Independent pagination page numbers in query string
    #[Url(as: 'tracksPage')]
    public ?int $tracksPage = 1;

    #[Url(as: 'playlistsPage')]
    public ?int $playlistsPage = 1;

    #[Url(as: 'playlistTracksPage')]
    public ?int $playlistTracksPage = 1;

    public $user_urn = null;
    // Dependencies (non-serializable) — keep private
    private UserService $userService;
    private CreditTransactionService $creditTransactionService;

    // Livewire v3: boot runs on every request (initial + subsequent)
    public function boot(UserService $userService, CreditTransactionService $creditTransactionService): void
    {
        $this->userService = $userService;
        $this->creditTransactionService = $creditTransactionService;
    }

    public function mount($user_urn = null): void
    {
        $this->soundecloudTracks();
        $this->user_urn = $user_urn ?? user()->urn;

        Log::info('MyAccount mount', ['user_urn' => $this->user_urn]);
        // If a playlist is in the URL, ensure we land on the right tab/view
        if ($this->selectedPlaylistId) {
            $this->activeTab = 'playlists';
            $this->showPlaylistTracks = true;
        }
    }

    public function setActiveTab(string $tab): void
    {
        $this->activeTab = $tab;

        if ($tab !== 'playlists') {
            $this->resetPlaylistView();
        }

        // Reset the relevant pager when switching tabs
        if ($tab === 'tracks') {
            $this->resetPage('tracksPage');
        } elseif ($tab === 'playlists') {
            $this->resetPage('playlistsPage');
        }
    }

    public function selectPlaylist(int $playlistId): void
    {
        $exists = Playlist::where('id', $playlistId)
            ->where('user_urn', $this->user_urn)
            ->exists();

        if ($exists) {
            $this->selectedPlaylistId = $playlistId;
            $this->showPlaylistTracks = true;
            $this->resetPage('playlistTracksPage');
        }
    }

    public function backToPlaylists(): void
    {
        $this->resetPlaylistView();
    }

    private function resetPlaylistView(): void
    {
        $this->selectedPlaylistId = null;
        $this->showPlaylistTracks = false;
        $this->resetPage('playlistTracksPage');
    }

    public function profileUpdated($payload = null): void
    {
        $this->showEditProfileModal = true;
    }

    public function closeEditProfileModal(): void
    {
        $this->showEditProfileModal = false;
    }

    public function soundecloudTracks()
    {
        $httpClient = Http::withHeaders([
            'Authorization' => 'OAuth ' . user()->token,
        ]);

        $response = $httpClient->get("{$this->baseUrl}/me/tracks");

        if ($response->failed()) {
            Log::error('SoundCloud API failed', ['response' => $response->body()]);
            return;
        }

        $tracks = $response->json();

        foreach ($tracks as $track) {
            Track::updateOrCreate(
                [
                    'soundcloud_track_id' => $track['id'], // unique check
                ],
                [
                    'user_urn'                 => user()->urn,
                    'kind'                     => $track['kind'] ?? null,
                    'urn'                      => $track['urn'] ?? null,
                    'duration'                 => $track['duration'] ?? 0,
                    'commentable'              => $track['commentable'] ?? false,
                    'comment_count'            => $track['comment_count'] ?? 0,
                    'sharing'                  => $track['sharing'] ?? null,
                    'tag_list'                 => $track['tag_list'] ?? null,
                    'streamable'               => $track['streamable'] ?? false,
                    'embeddable_by'            => $track['embeddable_by'] ?? null,
                    'purchase_url'             => $track['purchase_url'] ?? null,
                    'purchase_title'           => $track['purchase_title'] ?? null,
                    'genre'                    => $track['genre'] ?? null,
                    'title'                    => $track['title'] ?? null,
                    'description'              => $track['description'] ?? null,
                    'label_name'               => $track['label_name'] ?? null,
                    'release'                  => $track['release'] ?? null,
                    'key_signature'            => $track['key_signature'] ?? null,
                    'isrc'                     => $track['isrc'] ?? null,
                    'bpm'                      => $track['bpm'] ?? null,
                    'release_year'             => $track['release_year'] ?? null,
                    'release_month'            => $track['release_month'] ?? null,
                    'release_day'              => $track['release_day'] ?? null,
                    'license'                  => $track['license'] ?? null,
                    'uri'                      => $track['uri'] ?? null,
                    'permalink_url'            => $track['permalink_url'] ?? null,
                    'artwork_url'              => $track['artwork_url'] ?? null,
                    'stream_url'               => $track['stream_url'] ?? null,
                    'download_url'             => $track['download_url'] ?? null,
                    'waveform_url'             => $track['waveform_url'] ?? null,
                    'available_country_codes'  => $track['available_country_codes'] ?? null,
                    'secret_uri'               => $track['secret_uri'] ?? null,
                    'user_favorite'            => $track['user_favorite'] ?? false,
                    'user_playback_count'      => $track['user_playback_count'] ?? 0,
                    'playback_count'           => $track['playback_count'] ?? 0,
                    'download_count'           => $track['download_count'] ?? 0,
                    'favoritings_count'        => $track['favoritings_count'] ?? 0,
                    'reposts_count'            => $track['reposts_count'] ?? 0,
                    'downloadable'             => $track['downloadable'] ?? false,
                    'access'                   => $track['access'] ?? null,
                    'policy'                   => $track['policy'] ?? null,
                    'monetization_model'       => $track['monetization_model'] ?? null,
                    'metadata_artist'          => $track['metadata_artist'] ?? null,
                    'created_at_soundcloud'    => isset($track['created_at']) ? \Carbon\Carbon::parse($track['created_at']) : null,
                    'type'                     => $track['type'] ?? null,

                    // Author info
                    'author_username'          => $track['user']['username'] ?? null,
                    'author_soundcloud_id'     => $track['user']['id'] ?? null,
                    'author_soundcloud_urn'    => $track['user']['urn'] ?? null,
                    'author_soundcloud_kind'   => $track['user']['kind'] ?? null,
                    'author_soundcloud_permalink_url' => $track['user']['permalink_url'] ?? null,
                    'author_soundcloud_permalink'     => $track['user']['permalink'] ?? null,
                    'author_soundcloud_uri'    => $track['user']['uri'] ?? null,

                    'last_sync_at'             => now(),
                ]
            );
        }

        Log::info('SoundCloud tracks synced successfully');
    }

    public function render()
    {
        Log::info('MyAccount render', ['user_urn' => $this->user_urn]);
        $user = $this->userService->getUser(encrypt($this->user_urn), 'urn');

        // Tracks pagination
        $tracks = Track::where('user_urn', $this->user_urn)
            ->latest('created_at')
            ->paginate(6, ['*'], 'tracksPage', $this->tracksPage);

        // Playlists pagination
        $playlists = Playlist::where('user_urn', $this->user_urn)
            ->latest('created_at')
            ->paginate(8, ['*'], 'playlistsPage', $this->playlistsPage);

        // Playlist detail + tracks pagination
        $selectedPlaylist = null;
        $playlistTracks = null;

        if ($this->showPlaylistTracks && $this->selectedPlaylistId) {
            $selectedPlaylist = Playlist::where('id', $this->selectedPlaylistId)
                ->where('user_urn', $this->user_urn)
                ->first();

            if ($selectedPlaylist) {
                // Make sure you have a proper relationship method `tracks()` on Playlist
                $playlistTracks = $selectedPlaylist->tracks()
                    ->latest('created_at')
                    ->paginate(6, ['*'], 'playlistTracksPage', $this->playlistTracksPage);
            }
        }

        // Recent reposts (not paginated here)
        $reposts = Repost::with(['campaign.music', 'request.track'])
            ->where('reposter_urn', $this->user_urn)->where(function ($query) {
                $query->whereNotNull('campaign_id')->orWhereNotNull('repost_request_id');
            })
            ->orderByDesc('reposted_at')
            ->take(10)
            ->get()
            ->map(function ($repost) {
                $source = $repost->campaign?->music ?? $repost->request?->track;
                $repost->source = $source;
                $repost->source_id = $repost->campaign?->id ?? $repost->request?->id;
                $repost->source_type = $repost->campaign ? '📢 From Campaign' : ($repost->request ? '🤝 From Request' : '');
                return $repost;
            });

        // Transactions (top 10)
        $transactions = $this->creditTransactionService->getUserTransactions()
            ->where('status', CreditTransaction::STATUS_SUCCEEDED)
            ->sortByDesc('created_at')
            ->take(10);

        return view('backend.user.profile-management.my-account', [
            'user' => $user,
            'tracks' => $tracks,
            'playlists' => $playlists,
            'selectedPlaylist' => $selectedPlaylist,
            'playlistTracks' => $playlistTracks,
            'reposts' => $reposts,
            'transactions' => $transactions,
            // Also pass down flags used in Blade
            'showPlaylistTracks' => $this->showPlaylistTracks,
        ]);
    }
}
