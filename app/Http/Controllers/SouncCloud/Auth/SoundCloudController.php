<?php

namespace App\Http\Controllers\SouncCloud\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\SoundCloud\SoundCloudAuthRequest;
use App\Jobs\SyncUserJob;
use App\Models\Playlist;
use App\Models\Product;
use App\Models\Subscription;
use App\Models\Track;
use App\Models\User;
use App\Models\UserCredits;
use App\Models\UserInformation;
use App\Services\SoundCloud\SoundCloudService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;
use App\Services\SoundCloud\FollowerAnalyzer;


use Throwable;

class SoundCloudController extends Controller
{
    public function __construct(protected SoundCloudService $soundCloudService, protected FollowerAnalyzer $followerAnalyzer)
    {
    }

    public function redirect(): RedirectResponse
    {
        try {
            return Socialite::driver('soundcloud')->redirect();
        } catch (\Exception $e) {
            Log::error('SoundCloud redirect error', [
                'error' => $e->getMessage(),
            ]);

            return redirect()->route('f.landing')
                ->with('error', 'Unable to connect to SoundCloud. Please try again.');
        }
    }

    public function callback(SoundCloudAuthRequest $request): RedirectResponse
    {

        // Check for error from SoundCloud
        if ($request->has('error')) {
            return redirect()->route('f.landing')
                ->with('error', 'SoundCloud authentication was cancelled or failed.');
        }

        try {
            $soundCloudUser = Socialite::driver('soundcloud')->user();
            // Find or create user
            $user = $this->findOrCreateUser($soundCloudUser);

            // SyncUserJob::dispatch($user, $soundCloudUser);

            $this->syncUser($user, $soundCloudUser);

            Auth::guard('web')->login($user, true);

            $this->followerAnalyzer->syncUserRealFollowers($this->soundCloudService->getAuthUserFollowers(), $user);

            return redirect()->intended(route('user.my-account', absolute: false))
                ->with('success', 'Successfully connected to SoundCloud!');
        } catch (\Exception $e) {
            Log::error('SoundCloud callback error', [
                'error' => $e->getMessage(),
                'user_id' => Auth::guard('web')->id(),
            ]);

            return redirect()->route('f.landing')
                ->with('error', 'Failed to authenticate with SoundCloud. Please try again.');
        }
    }

    public function disconnect(): RedirectResponse
    {
        $user = Auth::guard('web')->user();

        if (!$user) {
            return redirect()->route('f.landing');
        }

        try {
            // Clear SoundCloud data
            $user->update([
                // 'soundcloud_id' => null,
                // 'nickname' => null,
                // 'avatar' => null,
                // 'soundcloud_permalink_url' => null,
                // 'soundcloud_followings_count' => 0,
                // 'soundcloud_followers_count' => 0,
                'token' => null,
                'refresh_token' => null,
                'expires_in' => null,
            ]);

            // Optionally, deactivate all tracks
            $user->soundcloudTracks()->update(['is_active' => false]);

            return redirect()->route('profile')
                ->with('success', 'Successfully disconnected from SoundCloud.');
        } catch (\Exception $e) {
            Log::error('SoundCloud disconnect error', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);

            return redirect()->route('profile')
                ->with('error', 'Failed to disconnect from SoundCloud.');
        }
    }

    public function syncUser(User $user, $soundCloudUser)
    {
        try {
            DB::transaction(function () use ($user, $soundCloudUser) {
                Log::info('SoundCloud sync started for syncUserTracks');
                $this->soundCloudService->syncUserTracks($user, []);
                Log::info('SoundCloud sync started for syncUserProductsAndSubscriptions');
                $this->soundCloudService->syncUserProductsAndSubscriptions($user, $soundCloudUser);
                Log::info('SoundCloud sync started for syncUserPlaylists');
                $this->soundCloudService->syncUserPlaylists($user);
                Log::info('SoundCloud sync started for syncUserInformation');
                $this->soundCloudService->syncUserInformation($user, $soundCloudUser);
                Log::info('SoundCloud sync started for analyzeFollowers');
            });
        } catch (Throwable $e) {
            Log::error('SoundCloud sync error', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    protected function findOrCreateUser($soundCloudUser): User
    {

        try {
            // $user = User::where('soundcloud_id', $soundCloudUser->getId())->first();
            // if ($user) {
            //     $user->update([
            //         'name' => $soundCloudUser->getName(),
            //         'nickname' => $soundCloudUser->getNickname(),
            //         'avatar' => $soundCloudUser->getAvatar(),
            //         'token' => $soundCloudUser->token,
            //         'refresh_token' => $soundCloudUser->refreshToken,
            //         'last_synced_at' => now(),
            //         'urn' => $soundCloudUser->user['urn']
            //     ]);
            // } else {
            //     $user = User::create([
            //         'soundcloud_id' => $soundCloudUser->getId(),
            //         'name' => $soundCloudUser->getName(),
            //         'nickname' => $soundCloudUser->getNickname(),
            //         'avatar' => $soundCloudUser->getAvatar(),
            //         'token' => $soundCloudUser->token,
            //         'refresh_token' => $soundCloudUser->refreshToken,
            //         'expires_in' => $soundCloudUser->expiresIn,
            //         'last_synced_at' => now(),
            //         'urn' => $soundCloudUser->user['urn']
            //     ]);

            //     UserCredits::create([
            //         'user_urn' => $user->urn,
            //         'amount' => 30,
            //         'type' => 'bonus',
            //         'source' => 'soundcloud_sync',
            //     ]);
            // }

            // return $user;
            return User::updateOrCreate(
                ['soundcloud_id' => $soundCloudUser->getId()],
                [
                    'name' => $soundCloudUser->getName(),
                    'nickname' => $soundCloudUser->getNickname(),
                    'avatar' => $soundCloudUser->getAvatar(),
                    'token' => $soundCloudUser->token,
                    'soundcloud_permalink_url' => $soundCloudUser->getRaw()['permalink_url'],
                    'refresh_token' => $soundCloudUser->refreshToken,
                    'expires_in' => $soundCloudUser->expiresIn,
                    'last_synced_at' => now(),
                    'urn' => $soundCloudUser->user['urn']
                ]
            );
        } catch (Throwable $e) {
            Log::error('SoundCloud find or create user error', [
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }
}
