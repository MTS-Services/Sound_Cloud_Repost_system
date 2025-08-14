<?php

namespace App\Livewire\User;

use App\Models\Track;
use Illuminate\Support\Facades\Http;
use Livewire\Component;
use Livewire\WithFileUploads;

class TrackSubmit extends Component
{
    use WithFileUploads;

    // Base Url
    protected string $baseUrl = 'https://api.soundcloud.com';

    // The form fields to match the image requirements
    public $track = [
        'title' => '',
        'asset_data' => null,
        'artwork_data' => null,
        'permalink' => '',
        'sharing' => 'public', // Default to public
        'embeddable_by' => '',
        'purchase_url' => '',
        'description' => '',
        'genre' => '',
        'tag_list' => '',
        'label_name' => '',
        'release' => '',
        'release_date' => '',
        'streamable' => true,
        'downloadable' => true,
        'license' => '--',
        'commentable' => true,
        'isrc' => '',
    ];

    // Public properties to hold the select options
    public $genres;
    public $licenses;
    public $embeddableByOptions;

    public function mount()
    {
        $this->genres = [
            'Electronic',
            'Dance',
            'Hip Hop & Rap',
            'Pop',
            'R&B & Soul',
            'Rock',
            'Ambient',
            'Classical',
            'Country',
            'Disco',
            'Dubstep',
            'Folk',
            'House',
            'Jazz',
            'Latin',
            'Metal',
            'Piano',
            'Reggae',
            'Techno',
            'Trance',
        ];

        $this->licenses = [
            '' => 'No License',
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

        // Set default values from the options
        $this->track['license'] = ''; // Default to "No License"
        $this->track['embeddable_by'] = 'all'; // Default to "All"
    }

    public function rules()
    {
        return [
            'track.title' => 'required|string|max:255',
            'track.asset_data' => 'required|file|mimes:mp3,wav,flac,m4a,mp4,mov|max:250000', // 250MB
            'track.artwork_data' => 'nullable|image|mimes:png,jpg,jpeg,gif|max:5000', // 5MB
            'track.permalink' => 'nullable|string|max:255',
            'track.sharing' => 'required|string|in:public,private',
            'track.embeddable_by' => 'nullable|string',
            'track.purchase_url' => 'nullable|string|url',
            'track.description' => 'nullable|string',
            'track.genre' => 'nullable|string|max:100',
            'track.tag_list' => 'nullable|string|max:500',
            'track.label_name' => 'nullable|string|max:255',
            'track.release' => 'nullable|string|max:255',
            'track.release_date' => 'nullable|date',
            'track.streamable' => 'nullable|boolean',
            'track.downloadable' => 'nullable|boolean',
            'track.license' => 'nullable|string',
            'track.commentable' => 'nullable|boolean',
            'track.isrc' => 'nullable|string',
        ];
    }

    public function updated($field)
    {
        $this->validateOnly($field);
    }

    public function submit()
    {
        $this->validate();

        // Log the validated data for debugging
        logger()->info('Validated data: ', $this->track);

        try {
            $httpClient = Http::withHeaders([
                'Authorization' => 'OAuth ' . user()->token,
            ])->attach(
                'track[asset_data]',
                file_get_contents($this->track['asset_data']->getRealPath()),
                $this->track['asset_data']->getClientOriginalName()
            );

            if ($this->track['artwork_data']) {
                $httpClient->attach(
                    'track[artwork_data]',
                    file_get_contents($this->track['artwork_data']->getRealPath()),
                    $this->track['artwork_data']->getClientOriginalName()
                );
            }

            $requestBody = collect($this->track)->except(['asset_data', 'artwork_data'])->toArray();

            if (empty($requestBody['permalink'])) {
                unset($requestBody['permalink']);
            }

            $response = $httpClient->post($this->baseUrl . '/tracks', [
                'track' => $requestBody
            ]);

            $response->throw();

            session()->flash('message', 'Track submitted successfully!');
            $this->reset();
            return redirect()->route('user.dashboard');
        } catch (\Illuminate\Http\Client\RequestException $e) {
            logger()->error('SoundCloud API Error: ' . $e->getMessage(), ['response_body' => $e->response->body()]);
            session()->flash('error', 'Failed to submit track: ' . $e->response->json('errors.0.message', 'Unknown API error.'));
        } catch (\Exception $e) {
            logger()->error('General Submission Error: ' . $e->getMessage());
            session()->flash('error', 'An unexpected error occurred. Please try again.');
        }
    }

    public function render()
    {
        return view('livewire.user.track-submit');
    }
}
