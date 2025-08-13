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

    public $title = null;
    public $asset_data = null;
    public $permalink = null;
    public $genre = null;
    public $description = '';
    public $tag_list = '';
    public static $artist = null;

    public function mount()
    {
        // 
    }

    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'asset_data' => 'required|file',
            'permalink' => 'required|string|max:255',
            'genre' => 'nullable|string|max:100',
            'tag_list' => 'nullable|string|max:500',
        ];
    }
    public function submit()
    {
        $this->validate();
        
        // Create the track data array
        $trackData = [
            'title' => $this->title,
            'permalink' => $this->permalink,
            'asset_data' => $this->asset_data,
            'genre' => $this->genre,
            'description' => $this->description,
            'tag_list' => $this->tag_list,
        ];
        // Create the HTTP client
        $httpClient = Http::withHeaders([
            'Authorization' => 'OAuth ' . user()->token,
        ]);
        // Make the request to the SoundCloud API
        $response = $httpClient->post($this->baseUrl . '/tracks', $trackData);
        // dd($httpClient, $response);
        // Check if the request was successful
        if ($response->successful()) {
            session()->flash('message', 'Track submitted successfully!');
            $this->reset();
        } else {
            session()->flash('error', 'Failed to submit track. Please try again.');
        }

        // Reset form after successful submission
        $this->reset();
    }

    public function render()
    {
        return view('livewire.user.track-submit');
    }
}
