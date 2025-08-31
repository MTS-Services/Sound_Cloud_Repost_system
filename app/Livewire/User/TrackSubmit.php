<?php

namespace App\Livewire\User;

use App\Events\UserNotificationSent;
use App\Models\CustomNotification;
use App\Models\Track;
use App\Models\User;
use App\Models\UserGenre;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use App\Services\SoundCloud\SoundCloudService;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Http\Client\RequestException;

class TrackSubmit extends Component
{
    use WithFileUploads;

    protected SoundCloudService $soundCloudService;

    public function boot(SoundCloudService $soundCloudService)
    {
        $this->soundCloudService = $soundCloudService;
    }

    // Base Url
    protected string $baseUrl = 'https://api.soundcloud.com';

    // The form fields to match the image requirements
    public $track = [
        'title' => '',
        'asset_data' => null,
        'artwork_data' => null,
        'permalink' => '',
        'sharing' => 'public', // Default to public
        'embeddable_by' => 'all', // Set default to 'all' based on documentation
        'purchase_url' => '',
        'description' => '',
        'genre' => '',
        'tag_list' => '',
        'label_name' => '',
        'release' => '', // This is the new input field
        'release_date' => '',
        'streamable' => true,
        'downloadable' => true,
        'license' => 'no-rights-reserved', // Set default based on documentation
        'commentable' => true,
        'isrc' => '',
    ];

    // Public properties to hold the select options
    public $genres;
    public $licenses;
    public $embeddableByOptions;

    public function mount()
    {

        $this->genres = array_keys(AllGenres());

        // Licenses matching the API documentation exactly
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

        // Embeddable options matching the API documentation
        $this->embeddableByOptions = [
            'all' => 'All',
            'me' => 'Me',
            'none' => 'None',
        ];

        // Set default values from the options
        $this->track['license'] = 'no-rights-reserved';
        $this->track['embeddable_by'] = 'all';
    }

    public function rules()
    {
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
        return [
            'track.title.required' => 'The track title is required.',
            'track.title.string' => 'The track title must be a string.',
            'track.title.max' => 'The track title may not be greater than 255 characters.',

            'track.asset_data.required' => 'Please upload an audio or video file for your track.',
            'track.asset_data.file' => 'The uploaded track file must be a valid file.',
            'track.asset_data.mimes' => 'The track must be one of the following types: MP3, WAV, FLAC, M4A, MP4, or MOV.',

            'track.artwork_data.image' => 'The track artwork must be an image.',
            'track.artwork_data.mimes' => 'The track artwork must be one of the following types: PNG, JPG, JPEG, or GIF.',

            'track.permalink.string' => 'The track link must be a string.',
            'track.permalink.max' => 'The track link may not be greater than 255 characters.',

            'track.sharing.required' => 'Please select a privacy option for your track.',
            'track.sharing.string' => 'The privacy option must be a string.',
            'track.sharing.in' => 'The selected privacy option is invalid.',

            'track.embeddable_by.string' => 'The embeddable option must be a string.',
            'track.embeddable_by.in' => 'The selected embeddable option is invalid.',

            'track.purchase_url.string' => 'The purchase URL must be a string.',
            'track.purchase_url.url' => 'Please enter a valid URL for the purchase link.',

            'track.description.string' => 'The track description must be a string.',

            'track.genre.string' => 'The genre must be a string.',
            'track.genre.max' => 'The genre may not be greater than 100 characters.',

            'track.tag_list.string' => 'The tags must be a string.',
            'track.tag_list.max' => 'The tags may not be greater than 500 characters.',

            'track.label_name.string' => 'The label name must be a string.',
            'track.label_name.max' => 'The label name may not be greater than 255 characters.',

            'track.release.string' => 'The release title must be a string.',
            'track.release.max' => 'The release title may not be greater than 255 characters.',

            'track.release_date.date' => 'The release date is not a valid date.',

            'track.streamable.boolean' => 'The streamable option must be true or false.',

            'track.downloadable.boolean' => 'The downloadable option must be true or false.',

            'track.license.string' => 'The license must be a string.',
            'track.license.in' => 'The selected license is invalid.',

            'track.commentable.boolean' => 'The commentable option must be true or false.',

            'track.isrc.string' => 'The ISRC must be a string.',
        ];
    }

    /**
     * Updated method is now modified to NOT validate file fields.
     */
    public function updated($field)
    {
        if (! in_array($field, ['track.asset_data', 'track.artwork_data'])) {
            $this->validateOnly($field);
        }
    }

    public function submit()
    {

        $this->validate();

        try {

            $responseTrack = $this->soundCloudService->uploadTrack(user(), $this->track);
            if ($this->track['asset_data']) {
                $this->track['asset_data']->delete();
            }
            if ($this->track['artwork_data']) {
                $this->track['artwork_data']->delete();
            }

            DB::transaction(function () use ($responseTrack) {
                $track =  Track::create([
                    'user_urn' => user()->urn,
                    'kind' =>  $responseTrack['kind'],
                    'soundcloud_track_id' =>  $responseTrack['id'],
                    'urn' =>  $responseTrack['urn'],
                    'duration' =>  $responseTrack['duration'],
                    'commentable' =>  $responseTrack['commentable'],
                    'comment_count' =>  $responseTrack['comment_count'],
                    'sharing' =>  $responseTrack['sharing'],
                    'tag_list' =>  $responseTrack['tag_list'],
                    'streamable' =>  $responseTrack['streamable'],
                    'embeddable_by' =>  $responseTrack['embeddable_by'],
                    'purchase_url' =>  $responseTrack['purchase_url'],
                    'purchase_title' =>  $responseTrack['purchase_title'],
                    'genre' =>  $responseTrack['genre'],
                    'title' =>  $responseTrack['title'],
                    'description' =>  $responseTrack['description'],
                    'label_name' =>  $responseTrack['label_name'],
                    'release' =>  $responseTrack['release'],
                    'key_signature' =>  $responseTrack['key_signature'],
                    'isrc' =>  $responseTrack['isrc'],
                    'bpm' =>  $responseTrack['bpm'],
                    'release_year' =>  $responseTrack['release_year'],
                    'release_month' =>  $responseTrack['release_month'],
                    'release_day' =>  $responseTrack['release_day'],
                    'license' =>  $responseTrack['license'],
                    'uri' =>  $responseTrack['uri'],
                    'permalink_url' =>  $responseTrack['permalink_url'],
                    'artwork_url' =>  $responseTrack['artwork_url'],
                    'stream_url' =>  $responseTrack['stream_url'],
                    'download_url' =>  $responseTrack['download_url'],
                    'waveform_url' =>  $responseTrack['waveform_url'],
                    'available_country_codes' =>  $responseTrack['available_country_codes'],
                    'secret_uri' =>  $responseTrack['secret_uri'],
                    'user_favorite' =>  $responseTrack['user_favorite'],
                    'user_playback_count' =>  $responseTrack['user_playback_count'] === null ? 0 : $responseTrack['user_playback_count'],
                    'playback_count' =>  $responseTrack['playback_count'] === null ? 0 : $responseTrack['playback_count'],
                    'download_count' =>  $responseTrack['download_count'] === null ? 0 : $responseTrack['download_count'],
                    'favoritings_count' =>  $responseTrack['favoritings_count'] === null ? 0 : $responseTrack['favoritings_count'],
                    'reposts_count' =>  $responseTrack['reposts_count'] === null ? 0 : $responseTrack['reposts_count'],
                    'downloadable' =>  $responseTrack['downloadable'],
                    'access' =>  $responseTrack['access'],
                    'policy' =>  $responseTrack['policy'],
                    'monetization_model' =>  $responseTrack['monetization_model'],
                    'metadata_artist' =>  $responseTrack['metadata_artist'],
                    'created_at_soundcloud' =>  $responseTrack['created_at'],

                    'author_username' =>  $responseTrack['user']['username'],
                    'author_soundcloud_id' =>  $responseTrack['user']['id'],
                    'author_soundcloud_urn' =>  $responseTrack['user']['urn'],
                    'author_soundcloud_kind' =>  $responseTrack['user']['kind'],
                    'author_soundcloud_permalink_url' =>  $responseTrack['user']['permalink_url'],
                    'author_soundcloud_permalink' =>  $responseTrack['user']['permalink'],
                    'author_soundcloud_uri' =>  $responseTrack['user']['uri'],

                    'last_sync_at' => now(),
                ]);
                $notification = CustomNotification::create([
                    'receiver_id' => user()->id,
                    'receiver_type' => User::class,
                    'type' => CustomNotification::TYPE_USER,
                    'url' => route('user.my-account') . '?tab=tracks',
                    'message_data' => [
                        'title' => 'Track Submitted',
                        'message' => 'A new track has been submitted.',
                        'description' => "Your track has been successfully uploaded to SoundCloud. Track Title: {$track->title}. If this track is not visible on SoundCloud, it may have been deleted or removed by SoundCloud due to a potential copyright infringement. Please review your SoundCloud account notifications for details. If you possess write permissions for this track or are its rightful owner, we recommend contacting SoundCloud support for further assistance.",
                        'icon' => 'audio-lines',
                        'additional_data' => [
                            'Track Title' => $track->title,
                            'Description' => $track->description ?? 'No description provided.',
                            'Track Artist' => $track->author_username,
                        ]
                    ]
                ]);
                broadcast(new UserNotificationSent($notification));
            });

           $this->dispatch('alert', type:'success', message: 'Track submitted successfully!');

            $this->reset();
            return $this->redirect(route('user.my-account') . '?tab=tracks', navigate: true);
        } catch (RequestException $e) {
            logger()->error('SoundCloud API Error: ' . $e->getMessage(), ['response_body' => $e->response->body()]);

            $statusCode = $e->response->status();
            $responseBody = $e->response->json();
            $errorMessage = 'An error occurred while submitting the track. Please try again.';

            // Check for specific client-side errors (400) first
            if ($statusCode === 400 && isset($responseBody['errors']) && is_array($responseBody['errors']) && !empty($responseBody['errors'])) {
                $apiErrorMessage = $responseBody['errors'][0]['error_message'] ?? '';
                $lowerCaseApiErrorMessage = strtolower($apiErrorMessage);

                if (str_contains($lowerCaseApiErrorMessage, 'uid has already been taken')) {
                    $errorMessage = 'Track title or link already taken. Please use a different one.';
                } elseif (str_contains($lowerCaseApiErrorMessage, 'permalink must contain only lower case letters and numbers')) {
                    $errorMessage = 'Invalid link format. Use lowercase letters, numbers, hyphens, or underscores.';
                } else {
                    $errorMessage = $apiErrorMessage;
                }
            } elseif ($statusCode >= 500) {
                $errorMessage = 'An internal server error occurred on SoundCloud\'s side. Please try again in a few minutes.';
            }

            session()->flash('error', $errorMessage);
        } catch (\Exception $e) {
            logger()->error('General Submission Error: ' . $e->getMessage());
            $this->dispatch('alert', type:'error', message:'An unexpected error occurred. Please try again.');
        }
    }

    public function render()
    {
        return view('livewire.user.track-submit');
    }
}
