<?php

namespace App\Livewire\User;

use App\Events\UserNotificationSent;
use App\Models\CustomNotification;
use App\Models\Track;
use App\Models\User;
use App\Models\UserGenre;
use App\Services\SoundCloud\SoundCloudService; // Import the service
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Livewire\Component;
use Livewire\WithFileUploads;
use Exception; // It's good practice to import this

class TrackSubmit extends Component
{
    use WithFileUploads;

    // Remove the unused baseUrl property
    // protected string $baseUrl = 'https://api.soundcloud.com';

    public $track = [
        'title' => '',
        'asset_data' => null,
        'artwork_data' => null,
        'permalink' => '',
        'sharing' => 'public',
        'embeddable_by' => 'all',
        'purchase_url' => '',
        'description' => '',
        'genre' => '',
        'tag_list' => '',
        'label_name' => '',
        'release' => '',
        'release_date' => '',
        'streamable' => true,
        'downloadable' => true,
        'license' => 'no-rights-reserved',
        'commentable' => true,
        'isrc' => '',
    ];

    public $genres;
    public $licenses;
    public $embeddableByOptions;

    // Dependency injection to automatically get an instance of your service
    protected SoundCloudService $soundCloudService;

    public function boot(SoundCloudService $soundCloudService)
    {
        $this->soundCloudService = $soundCloudService;
    }

    public function mount()
    {
        // Your mount method remains unchanged
        $this->genres = array_keys(AllGenres());
        $this->licenses = [
            'no-rights-reserved' => 'No Rights Reserved',
            'all-rights-reserved' => 'All Rights Reserved',
            'cc-by' => 'CC BY',
            'cc-by-nc' => 'CC BY-NC',
            'cc-by-nd' => 'CC BY-ND',
            'cc-by-sa' => 'CC BY-SA',
            'cc-by-nc-nd' => 'CC BY-NC-ND',
            'cc-by-nc-sa' => 'CC BY-NC-SA',
        ];
        $this->embeddableByOptions = [
            'all' => 'All',
            'me' => 'Me',
            'none' => 'None',
        ];
        $this->track['license'] = 'no-rights-reserved';
        $this->track['embeddable_by'] = 'all';
    }

    public function rules()
    {
        // Your rules remain unchanged
        return [
            'track.title' => 'required|string|max:255',
            'track.asset_data' => 'required|file|mimes:mp3,wav,flac,m4a,mp4,mov',
            'track.artwork_data' => 'nullable|image|mimes:png,jpg,jpeg,gif',
            'track.permalink' => 'nullable|string|max:255',
            'track.sharing' => 'required|string|in:public,private',
            'track.embeddable_by' => 'nullable|string|in:all,me,none',
            'track.purchase_url' => 'nullable|string|url',
            'track.description' => 'nullable|string',
            'track.genre' => 'nullable|string|max:100',
            'track.tag_list' => 'nullable|string|max:500',
            'track.label_name' => 'nullable|string|max:255',
            'track.release' => 'nullable|string|max:255',
            'track.release_date' => 'nullable|date',
            'track.streamable' => 'nullable|boolean',
            'track.downloadable' => 'nullable|boolean',
            'track.license' => 'nullable|string|in:no-rights-reserved,all-rights-reserved,cc-by,cc-by-nc,cc-by-nd,cc-by-sa,cc-by-nc-nd,cc-by-nc-sa',
            'track.commentable' => 'nullable|boolean',
            'track.isrc' => 'nullable|string',
        ];
    }

    public function messages()
    {
        // Your messages remain unchanged
        return [
            'track.title.required' => 'The track title is required.',
            'track.asset_data.required' => 'Please upload an audio or video file for your track.',
            'track.asset_data.mimes' => 'The track must be one of the following types: MP3, WAV, FLAC, M4A, MP4, or MOV.',
            'track.artwork_data.image' => 'The track artwork must be an image.',
            'track.artwork_data.mimes' => 'The track artwork must be one of the following types: PNG, JPG, JPEG, or GIF.',
        ];
    }

    public function updated($field)
    {
        if (!in_array($field, ['track.asset_data', 'track.artwork_data'])) {
            $this->validateOnly($field);
        }
    }

    public function submit()
    {
        $this->validate();

        try {
            // Use the service to handle the upload.
            // The service will take care of the token refresh automatically.
            $responseTrack = $this->soundCloudService->uploadTrack(user(), $this->track);

            // After a successful API call, delete the temporary files.
            if ($this->track['asset_data']) {
                $this->track['asset_data']->delete();
            }
            if ($this->track['artwork_data']) {
                $this->track['artwork_data']->delete();
            }

            // The rest of the logic remains the same.
            DB::transaction(function () use ($responseTrack) {
                $track = Track::create([
                    'user_urn' => user()->urn,
                    'kind' => $responseTrack['kind'],
                    'soundcloud_track_id' => $responseTrack['id'],
                    'urn' => $responseTrack['urn'],
                    'duration' => $responseTrack['duration'],
                    'commentable' => $responseTrack['commentable'],
                    'comment_count' => $responseTrack['comment_count'],
                    'sharing' => $responseTrack['sharing'],
                    'tag_list' => $responseTrack['tag_list'],
                    'streamable' => $responseTrack['streamable'],
                    'embeddable_by' => $responseTrack['embeddable_by'],
                    'purchase_url' => $responseTrack['purchase_url'],
                    'purchase_title' => $responseTrack['purchase_title'],
                    'genre' => $responseTrack['genre'],
                    'title' => $responseTrack['title'],
                    'description' => $responseTrack['description'],
                    'label_name' => $responseTrack['label_name'],
                    'release' => $responseTrack['release'],
                    'key_signature' => $responseTrack['key_signature'],
                    'isrc' => $responseTrack['isrc'],
                    'bpm' => $responseTrack['bpm'],
                    'release_year' => $responseTrack['release_year'],
                    'release_month' => $responseTrack['release_month'],
                    'release_day' => $responseTrack['release_day'],
                    'license' => $responseTrack['license'],
                    'uri' => $responseTrack['uri'],
                    'permalink_url' => $responseTrack['permalink_url'],
                    'artwork_url' => $responseTrack['artwork_url'],
                    'stream_url' => $responseTrack['stream_url'],
                    'download_url' => $responseTrack['download_url'],
                    'waveform_url' => $responseTrack['waveform_url'],
                    'available_country_codes' => $responseTrack['available_country_codes'],
                    'secret_uri' => $responseTrack['secret_uri'],
                    'user_favorite' => $responseTrack['user_favorite'],
                    'user_playback_count' => $responseTrack['user_playback_count'] ?? 0,
                    'playback_count' => $responseTrack['playback_count'] ?? 0,
                    'download_count' => $responseTrack['download_count'] ?? 0,
                    'favoritings_count' => $responseTrack['favoritings_count'] ?? 0,
                    'reposts_count' => $responseTrack['reposts_count'] ?? 0,
                    'downloadable' => $responseTrack['downloadable'],
                    'access' => $responseTrack['access'],
                    'policy' => $responseTrack['policy'],
                    'monetization_model' => $responseTrack['monetization_model'],
                    'metadata_artist' => $responseTrack['metadata_artist'],
                    'created_at_soundcloud' => $responseTrack['created_at'],
                    'author_username' => $responseTrack['user']['username'],
                    'author_soundcloud_id' => $responseTrack['user']['id'],
                    'author_soundcloud_urn' => $responseTrack['user']['urn'],
                    'author_soundcloud_kind' => $responseTrack['user']['kind'],
                    'author_soundcloud_permalink_url' => $responseTrack['user']['permalink_url'],
                    'author_soundcloud_permalink' => $responseTrack['user']['permalink'],
                    'author_soundcloud_uri' => $responseTrack['user']['uri'],
                    'last_sync_at' => now(),
                ]);

                $notification = CustomNotification::create([
                    'receiver_id' => user()->id,
                    'receiver_type' => User::class,
                    'type' => CustomNotification::TYPE_USER,
                    'message_data' => [
                        'title' => 'Track Submitted',
                        'message' => 'A new track has been submitted.',
                        'description' => "Your track has been successfully uploaded to SoundCloud. Track Title: {$track->title}. If this track is not visible on SoundCloud, it may have been deleted or removed by SoundCloud due to a potential copyright infringement. Please review your SoundCloud account notifications for details. If you possess write permissions for this track or are its rightful owner, we recommend contacting SoundCloud support for further assistance.",
                        'url' => route('user.pm.my-account') . '?tab=tracks',
                        'icon' => 'audio-lines',
                        'additional_data' => [
                            'Track Title' => $track->title,
                            'Description' => $track->description ?? 'No description provided.',
                            'Track Artist' => $track->author_username,
                            'Track Link' => $track->permalink_url,
                        ]
                    ]
                ]);
                broadcast(new UserNotificationSent($notification));
            });

            session()->flash('message', 'Track submitted successfully!');
            $this->reset();
            return $this->redirect(route('user.pm.my-account') . '?tab=tracks', navigate: true);
        } catch (Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.user.track-submit');
    }
}

// Don't forget to remove the `baseUrl` property from your Livewire component as it is no longer needed.