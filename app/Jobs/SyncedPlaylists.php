<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Services\PlaylistService;
use App\Services\SoundCloud\SoundCloudService;

class SyncedPlaylists implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $userUrn;

    public function __construct($userUrn)
    {
        $this->userUrn = $userUrn;
    }

    public function handle(SoundCloudService $soundCloudService, PlaylistService $playlistService)
    {
        $user = User::find($this->userUrn);
        if (! $user) {
            return;
        }

        $soundCloudService->ensureSoundCloudConnection($user);
        $soundCloudService->refreshUserTokenIfNeeded($user);

        $httpClient = Http::withHeaders([
            'Authorization' => 'OAuth ' . $user->token,
        ]);

        $response = $httpClient->get("https://api.soundcloud.com/me/playlists");

        if ($response->failed()) {
            Log::error('SoundCloud API request failed for playlists (User: ' . $user->urn . ')');
            return;
        }

        $playlists = $response->json();
        $playlistService->UpdateOrCreateSoundCloudPlaylistTrack($playlists);

        Log::info("✅ SoundCloud playlists synced successfully for user {$user->urn}");
    }
}

