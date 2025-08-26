<?php

namespace App\Livewire\User;

use App\Models\CreditTransaction;
use App\Models\User;
use App\Models\UserGenre;
use App\Models\UserInformation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class Settings extends Component
{
    public $credits = [];
    // Genre Filter Properties
    public $selectedGenres = [];
    public $searchTerm = '';
    public $isGenreDropdownOpen = true;
    public $maxGenres = 5;

    // Social Media Properties
    public $instagram_username = '';
    public $twitter_username = '';
    public $facebook_username = '';
    public $youtube_channel_id = '';
    public $tiktok_username = '';
    public $spotify_artist_link = '';

    // Validation error state
    public $genreLimitError = false;

    public $availableGenres = [];

    protected $rules = [
        'selectedGenres' => 'array|max:5',
        'instagram_username' => 'nullable|string|max:255',
        'twitter_username' => 'nullable|string|max:255',
        'facebook_username' => 'nullable|string|max:255',
        'youtube_channel_id' => 'nullable|string|max:255',
        'tiktok_username' => 'nullable|string|max:255',
        'spotify_artist_link' => 'nullable|string|max:255',
    ];

    public function mount()
    {
        $this->dataLoad();
    }
    public function dataLoad()
    {
        $this->availableGenres = AllGenres();
        $this->selectedGenres = UserGenre::where('user_urn', user()->urn)->pluck('genre')->toArray();
        $this->credits = CreditTransaction::where('receiver_urn', user()->urn)->credit()->get();
    }

    public function addGenre($genre)
    {
        if (count($this->selectedGenres) >= $this->maxGenres) {
            $this->genreLimitError = true;
            return;
        }

        if (!in_array($genre, $this->selectedGenres)) {
            $this->selectedGenres[] = $genre;
            $this->genreLimitError = false;
        }

        $this->searchTerm = '';
        $this->isGenreDropdownOpen = false;
    }

    public function removeGenre($genre)
    {
        $this->selectedGenres = array_values(array_filter($this->selectedGenres, function ($selected) use ($genre) {
            return $selected !== $genre;
        }));
        $this->genreLimitError = false;
    }

    public function getFilteredGenresProperty()
    {
        if (empty($this->searchTerm)) {
            return array_filter(AllGenres(), function ($genre) {
                return !in_array($genre, $this->selectedGenres);
            });
        }

        return array_filter(AllGenres(), function ($genre) {
            return !in_array($genre, $this->selectedGenres) &&
                stripos($genre, $this->searchTerm) !== false;
        });
    }

    public function saveProfile()
    {
        $this->validate();

        $user_info = UserInformation::firstOrCreate(
            ['user_urn' => user()->urn],
            ['creater_id' => user()->id, 'creater_type' => get_class(user())]
        );

        try {
            DB::transaction(function () use ($user_info) {
                UserGenre::where('user_urn', user()->urn)->delete();

                $genres = collect($this->selectedGenres)->map(fn($genre) => [
                    'user_urn' => user()->urn,
                    'genre' => $genre,
                    'creater_id' => user()->id,
                    'creater_type' => get_class(user()),
                ])->toArray();

                UserGenre::insert($genres);

                $user_info->update([
                    'instagram' => $this->instagram_username,
                    'twitter'   => $this->twitter_username,
                    'facebook'  => $this->facebook_username,
                    'youtube'   => $this->youtube_channel_id,
                    'tiktok'    => $this->tiktok_username,
                    'spotify'   => $this->spotify_artist_link,
                ]);
            });

            $this->reset(['selectedGenres', 'instagram_username', 'twitter_username', 'facebook_username', 'youtube_channel_id', 'tiktok_username', 'spotify_artist_link']);
            $this->dataLoad();

            session()->flash('success', 'Profile updated successfully!');
        } catch (\Throwable $e) {
            Log::error($e->getMessage());
            session()->flash('error', 'Profile update failed!');
        }
    }


    public function cancel()
    {
        // Reset form or redirect
        $this->reset();
        $this->mount(); // Re-initialize with default values
    }

    public function render()
    {
        return view('livewire.user.settings');
    }
}
