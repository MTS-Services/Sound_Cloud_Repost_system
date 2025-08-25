<?php

namespace App\Jobs;

use App\Models\User;
use App\Services\SoundCloud\SoundCloudService;
use App\Services\TrackService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;


class SyncedTracks implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    protected $userUrn;

    public function __construct($userUrn)
    {
        $this->userUrn = $userUrn;
    }

    public function handle(SoundCloudService $soundCloudService, TrackService $trackService)
    {
        $user =User::find($this->userUrn);
        if (! $user) {
            return;
        }

        $soundCloudService->ensureSoundCloudConnection($user);
        $soundCloudService->refreshUserTokenIfNeeded($user);

        $httpClient = Http::withHeaders([
            'Authorization' => 'OAuth ' . $user->token,
        ]);

        $response = $httpClient->get("https://api.soundcloud.com/me/tracks");

        if ($response->failed()) {
            Log::error('SoundCloud API request failed for user ' . $user->run);
            return;
        }

        $tracks = $response->json();
        foreach ($tracks as $track) {
            $trackService->UpdateOrCreateSoundCloudTrack($track);
        }

        Log::info("SoundCloud tracks synced successfully for user {$user->run}");
    }
}