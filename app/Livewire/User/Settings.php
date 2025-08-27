<?php

namespace App\Livewire\User;

use App\Models\CreditTransaction;
use App\Models\User;
use App\Models\UserGenre;
use App\Models\UserSetting;
use App\Models\UserSocialInformation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class Settings extends Component
{
    public $credits = [];
    public $user_infos = [];
    public $email = '';
    // Genre Filter Properties
    public $selectedGenres = [];
    public $searchTerm = '';
    public $isGenreDropdownOpen = false;
    public $maxGenres = 5;
    public $minGenres = 1;

    // Social Media Properties
    public $instagram_username = '';
    public $twitter_username = '';
    public $facebook_username = '';
    public $youtube_channel_id = '';
    public $tiktok_username = '';
    public $spotify_artist_link = '';

    // Validation error state
    public $genreLimitError = false;
    public $genreCountError = false;

    public $availableGenres = [];

    public $confirmModal = false;

    protected $rules = [
        'selectedGenres' => 'array|max:5|min:1',

        'instagram_username' => 'nullable|string|max:30|regex:/^[A-Za-z0-9._]+$/',
        'twitter_username'   => 'nullable|string|max:30|regex:/^[A-Za-z0-9_]+$/',
        'facebook_username'  => 'nullable|string|max:50|regex:/^[A-Za-z0-9.]+$/',
        'youtube_channel_id' => 'nullable|string|max:100|regex:/^[A-Za-z0-9_-]+$/',
        'tiktok_username'    => 'nullable|string|max:30|regex:/^[A-Za-z0-9._]+$/',
        'spotify_artist_link' => 'nullable|string|max:100|url',
    ];
    protected $messages = [
        'selectedGenres.required' => 'Please select at least 1 genre.',
        'selectedGenres.max'      => 'You can select up to 5 genres only.',

        'instagram_username.regex' => 'Instagram username may only contain letters, numbers, dots (.) and underscores (_).',
        'instagram_username.max'   => 'Instagram username cannot be longer than 30 characters.',

        'twitter_username.regex' => 'Twitter username may only contain letters, numbers, and underscores (_).',
        'twitter_username.max'   => 'Twitter username cannot be longer than 15 characters.',

        'facebook_username.regex' => 'Facebook username may only contain letters, numbers, and dots (.).',
        'facebook_username.max'   => 'Facebook username cannot be longer than 50 characters.',

        'youtube_channel_id.regex' => 'YouTube channel ID may only contain letters, numbers, dashes (-), and underscores (_).',
        'youtube_channel_id.max'   => 'YouTube channel ID cannot be longer than 100 characters.',

        'tiktok_username.regex' => 'TikTok username may only contain letters, numbers, dots (.) and underscores (_).',
        'tiktok_username.max'   => 'TikTok username cannot be longer than 24 characters.',

        'spotify_artist_link.url' => 'Spotify artist ID must be a valid URL.',
        'spotify_artist_link.max'   => 'Spotify artist ID cannot be longer than 50 characters.',
    ];


    public function mount()
    {
        $this->availableGenres = AllGenres();
        $this->selectedGenres = UserGenre::where('user_urn', user()->urn)->pluck('genre')->toArray();
        $this->credits = CreditTransaction::where('receiver_urn', user()->urn)->get();
        $this->loadSettings();
        $this->loadUserInfo();
    }
    ####################### Notification Settings Start ############################
    // Email alerts
    public $em_new_repost = false;
    public $em_repost_accepted = false;
    public $em_repost_declined = false;
    public $em_repost_expired = false;
    public $em_campaign_summary = false;
    public $em_free_boost = false;
    public $em_feedback_campaign = false;
    public $em_feedback_rated = false;
    public $em_referrals = false;
    public $em_reputation = false;
    public $em_inactivity_warn = false;
    public $em_marketing = false;
    public $em_chart_entry = false;
    public $em_mystery_box = false;
    public $em_discussions = false;
    public $em_competitions = false;

    // Push notifications
    public $ps_new_repost = false;
    public $ps_repost_accepted = false;
    public $ps_repost_declined = false;
    public $ps_repost_expired = false;
    public $ps_campaign_summary = false;
    public $ps_free_boost = false;
    public $ps_feedback_campaign = false;
    public $ps_feedback_rated = false;
    public $ps_referrals = false;
    public $ps_reputation = false;
    public $ps_inactivity_warn = false;
    public $ps_marketing = false;
    public $ps_chart_entry = false;
    public $ps_mystery_box = false;
    public $ps_discussions = false;
    public $ps_competitions = false;

    // My Requests
    public $accept_repost = false;
    public $block_mismatch_genre = false;

    // Additional Features
    public $opt_mystery_box = false;
    public $auto_boost = false;
    public $enable_react = true;

    // Subscription
    public $sub_plan = 'Free Forever Plan';

    public function loadSettings()
    {
        $userUrn = user()->urn;
        $settings = UserSetting::where('user_urn', $userUrn)->first();

        if ($settings) {
            // Email alerts
            $this->em_new_repost = $settings->em_new_repost ? true : false;
            $this->em_repost_accepted = $settings->em_repost_accepted ? true : false;
            $this->em_repost_declined = $settings->em_repost_declined ? true : false;
            $this->em_repost_expired = $settings->em_repost_expired ? true : false;
            $this->em_campaign_summary = $settings->em_campaign_summary ? true : false;
            $this->em_free_boost = $settings->em_free_boost ? true : false;
            $this->em_feedback_campaign = $settings->em_feedback_campaign ? true : false;
            $this->em_feedback_rated = $settings->em_feedback_rated ? true : false;
            $this->em_referrals = $settings->em_referrals ? true : false;
            $this->em_reputation = $settings->em_reputation ? true : false;
            $this->em_inactivity_warn = $settings->em_inactivity_warn ? true : false;
            $this->em_marketing = $settings->em_marketing ? true : false;
            $this->em_chart_entry = $settings->em_chart_entry ? true : false;
            $this->em_mystery_box = $settings->em_mystery_box ? true : false;
            $this->em_discussions = $settings->em_discussions ? true : false;
            $this->em_competitions = $settings->em_competitions ? true : false;

            // Push notifications
            $this->ps_new_repost = $settings->ps_new_repost ? true : false;
            $this->ps_repost_accepted = $settings->ps_repost_accepted ? true : false;
            $this->ps_repost_declined = $settings->ps_repost_declined ? true : false;
            $this->ps_repost_expired = $settings->ps_repost_expired ? true : false;
            $this->ps_campaign_summary = $settings->ps_campaign_summary ? true : false;
            $this->ps_free_boost = $settings->ps_free_boost ? true : false;
            $this->ps_feedback_campaign = $settings->ps_feedback_campaign ? true : false;
            $this->ps_feedback_rated = $settings->ps_feedback_rated ? true : false;
            $this->ps_referrals = $settings->ps_referrals ? true : false;
            $this->ps_reputation = $settings->ps_reputation ? true : false;
            $this->ps_inactivity_warn = $settings->ps_inactivity_warn ? true : false;
            $this->ps_marketing = $settings->ps_marketing ? true : false;
            $this->ps_chart_entry = $settings->ps_chart_entry ? true : false;
            $this->ps_mystery_box = $settings->ps_mystery_box ? true : false;
            $this->ps_discussions = $settings->ps_discussions ? true : false;
            $this->ps_competitions = $settings->ps_competitions ? true : false;

            // My Requests
            $this->accept_repost = $settings->accept_repost;
            $this->block_mismatch_genre = $settings->block_mismatch_genre;

            // Additional Features
            $this->opt_mystery_box = $settings->opt_mystery_box;
            $this->auto_boost = $settings->auto_boost;
            $this->enable_react = $settings->enable_react;
        }
    }

    public function createOrUpdate()
    {
        try {
            $userUrn = user()->urn;

            $data = [
                'user_urn' => $userUrn,
                // Email alerts
                'em_new_repost' => $this->em_new_repost ? 1 : 0,
                'em_repost_accepted' => $this->em_repost_accepted ? 1 : 0,
                'em_repost_declined' => $this->em_repost_declined ? 1 : 0,
                'em_repost_expired' => $this->em_repost_expired ? 1 : 0,
                'em_campaign_summary' => $this->em_campaign_summary ? 1 : 0,
                'em_free_boost' => $this->em_free_boost ? 1 : 0,
                'em_feedback_campaign' => $this->em_feedback_campaign ? 1 : 0,
                'em_feedback_rated' => $this->em_feedback_rated ? 1 : 0,
                'em_referrals' => $this->em_referrals ? 1 : 0,
                'em_reputation' => $this->em_reputation ? 1 : 0,
                'em_inactivity_warn' => $this->em_inactivity_warn ? 1 : 0,
                'em_marketing' => $this->em_marketing ? 1 : 0,
                'em_chart_entry' => $this->em_chart_entry ? 1 : 0,
                'em_mystery_box' => $this->em_mystery_box ? 1 : 0,
                'em_discussions' => $this->em_discussions ? 1 : 0,
                'em_competitions' => $this->em_competitions ? 1 : 0,

                // Push notifications
                'ps_new_repost' => $this->ps_new_repost ? 1 : 0,
                'ps_repost_accepted' => $this->ps_repost_accepted ? 1 : 0,
                'ps_repost_declined' => $this->ps_repost_declined ? 1 : 0,
                'ps_repost_expired' => $this->ps_repost_expired ? 1 : 0,
                'ps_campaign_summary' => $this->ps_campaign_summary ? 1 : 0,
                'ps_free_boost' => $this->ps_free_boost ? 1 : 0,
                'ps_feedback_campaign' => $this->ps_feedback_campaign ? 1 : 0,
                'ps_feedback_rated' => $this->ps_feedback_rated ? 1 : 0,
                'ps_referrals' => $this->ps_referrals ? 1 : 0,
                'ps_reputation' => $this->ps_reputation ? 1 : 0,
                'ps_inactivity_warn' => $this->ps_inactivity_warn ? 1 : 0,
                'ps_marketing' => $this->ps_marketing ? 1 : 0,
                'ps_chart_entry' => $this->ps_chart_entry ? 1 : 0,
                'ps_mystery_box' => $this->ps_mystery_box ? 1 : 0,
                'ps_discussions' => $this->ps_discussions ? 1 : 0,
                'ps_competitions' => $this->ps_competitions ? 1 : 0,

                // My Requests
                'accept_repost' => $this->accept_repost ? 1 : 0,
                'block_mismatch_genre' => $this->block_mismatch_genre ? 1 : 0,

                // Additional Features
                'opt_mystery_box' => $this->opt_mystery_box ? 1 : 0,
                'auto_boost' => $this->auto_boost ? 1 : 0,
                'enable_react' => $this->enable_react ? 1 : 0,
            ];

            UserSetting::updateOrCreate(
                ['user_urn' => $userUrn],
                $data
            );
            $this->reset();
            $this->loadSettings();

            session()->flash('message', 'Settings updated successfully!');
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function getAlertsProperty()
    {
        return [
            ['id' => 1, 'name' => 'New Repost Requests', 'email_key' => 'em_new_repost', 'push_key' => 'ps_new_repost'],
            ['id' => 2, 'name' => 'Repost Requests Accepted', 'email_key' => 'em_repost_accepted', 'push_key' => 'ps_repost_accepted'],
            ['id' => 3, 'name' => 'Repost Requests Declined', 'email_key' => 'em_repost_declined', 'push_key' => 'ps_repost_declined'],
            ['id' => 4, 'name' => 'Repost Requests Expired', 'email_key' => 'em_repost_expired', 'push_key' => 'ps_repost_expired'],
            ['id' => 5, 'name' => 'Campaign Summary & finished alert', 'email_key' => 'em_campaign_summary', 'push_key' => 'ps_campaign_summary'],
            ['id' => 6, 'name' => 'Free Boost Award', 'email_key' => 'em_free_boost', 'push_key' => 'ps_free_boost'],
            ['id' => 7, 'name' => 'Feedback Campaign Events', 'email_key' => 'em_feedback_campaign', 'push_key' => 'ps_feedback_campaign'],
            ['id' => 8, 'name' => 'Feedback Rated', 'email_key' => 'em_feedback_rated', 'push_key' => 'ps_feedback_rated'],
            ['id' => 9, 'name' => 'Referrals', 'email_key' => 'em_referrals', 'push_key' => 'ps_referrals'],
            ['id' => 10, 'name' => 'Reputation Changes', 'email_key' => 'em_reputation', 'push_key' => 'ps_reputation'],
            ['id' => 11, 'name' => 'Account inactivity Warning', 'email_key' => 'em_inactivity_warn', 'push_key' => 'ps_inactivity_warn'],
            ['id' => 12, 'name' => 'Marketing Communications', 'email_key' => 'em_marketing', 'push_key' => 'ps_marketing'],
            ['id' => 13, 'name' => 'Chart Entry', 'email_key' => 'em_chart_entry', 'push_key' => 'ps_chart_entry'],
            ['id' => 14, 'name' => 'Mystery Box Draw', 'email_key' => 'em_mystery_box', 'push_key' => 'ps_mystery_box'],
            ['id' => 15, 'name' => 'Discussions', 'email_key' => 'em_discussions', 'push_key' => 'ps_discussions'],
            ['id' => 16, 'name' => 'Competitions', 'email_key' => 'em_competitions', 'push_key' => 'ps_competitions']
        ];
    }
    ####################### Notification Settings End ############################

    public function loadUserInfo()
    {
        $socialInfos = UserSocialInformation::where('user_urn', user()->urn)->first();

        $this->email = User::where('urn', user()->urn)->first()->email;

        if ($socialInfos) {
            $this->instagram_username = $socialInfos->instagram ?? '';
            $this->twitter_username = $socialInfos->twitter ?? '';
            $this->tiktok_username = $socialInfos->tiktok ?? '';
            $this->facebook_username = $socialInfos->facebook ?? '';
            $this->youtube_channel_id = $socialInfos->youtube ?? '';
            $this->spotify_artist_link = $socialInfos->spotify ?? '';
        }
    }
    public function addGenre($genre)
    {
        if (count($this->selectedGenres) >= $this->maxGenres) {
            $this->genreLimitError = true;
            return;
        }
        $this->genreCountError = false;
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
        if (count($this->selectedGenres) < $this->minGenres) {
            $this->genreCountError = true;
            $this->isGenreDropdownOpen = true;
            return;
        }
        $this->genreCountError = false;
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

        $social_info = UserSocialInformation::firstOrCreate(
            ['user_urn' => user()->urn],
            ['creater_id' => user()->id, 'creater_type' => get_class(user())]
        );

        try {
            DB::transaction(function () use ($social_info) {
                User::where('urn', user()->urn)->update(['email' => $this->email]);
                UserGenre::where('user_urn', user()->urn)->delete();

                $genres = collect($this->selectedGenres)->map(fn($genre) => [
                    'user_urn' => user()->urn,
                    'genre' => $genre,
                    'creater_id' => user()->id,
                    'creater_type' => get_class(user()),
                ])->toArray();

                UserGenre::insert($genres);

                $social_info->update([
                    'instagram' => $this->instagram_username,
                    'twitter'   => $this->twitter_username,
                    'facebook'  => $this->facebook_username,
                    'youtube'   => $this->youtube_channel_id,
                    'tiktok'    => $this->tiktok_username,
                    'spotify'   => $this->spotify_artist_link,
                ]);
            });

            $this->reset(['selectedGenres', 'instagram_username', 'twitter_username', 'facebook_username', 'youtube_channel_id', 'tiktok_username', 'spotify_artist_link']);
            $this->mount();

            session()->flash('success', 'Profile updated successfully!');
        } catch (\Throwable $e) {
            Log::error($e->getMessage());
            session()->flash('error', 'Profile update failed!');
        }
    }

    public function deleteConfirmation()
    {
        $this->confirmModal = true;
    }

    public function deleteAccount()
    {
        try {
            User::where('urn', user()->urn)->delete();
            return redirect()->route('f.landing')->with('success', 'Account deleted successfully!');
        } catch (\Throwable $e) {
            Log::error($e->getMessage());
            session()->flash('error', 'Account delete failed!');
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
