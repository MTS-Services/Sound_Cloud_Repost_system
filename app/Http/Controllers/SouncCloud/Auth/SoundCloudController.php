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
use App\Services\SoundCloud\FollowerAnalyzer;
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

use Throwable;

class SoundCloudController extends Controller
{
    protected SoundCloudService $soundCloudService;

    public function __construct(SoundCloudService $soundCloudService)
    {
        $this->soundCloudService = $soundCloudService;
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
            dd((array) $soundCloudUser);

            $this->notArtistRedirect(soundCloudUser: $soundCloudUser);

            // Find or create user
            $user = $this->findOrCreateUser($soundCloudUser);

            // SyncUserJob::dispatch($user, $soundCloudUser);

            Auth::guard('web')->login($user, true);
            $this->syncUser($user, $soundCloudUser);

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
            Auth::guard('web')->logout();
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
            Log::info('SoundCloud sync started for syncUserInformation');
            $this->soundCloudService->syncUserInformation($user, $soundCloudUser);

            Log::info('SoundCloud sync completed for syncUserInformation');
            Log::info('SoundCloud sync started for syncUserJob');
            SyncUserJob::dispatch($user, $soundCloudUser, user()->id)->delay(Carbon::now()->addSeconds(5));
            // dispatch(new SyncUserJob(user: $user, soundCloudUser: $soundCloudUser, authUserId: user()->id));
            Log::info('SoundCloud sync completed for syncUserJob');
            // DB::transaction(function () use ($user, $soundCloudUser) {
            //     $this->soundCloudService->syncUserInformation($user, $soundCloudUser);
            //     $this->soundCloudService->syncUserTracks($user, []);
            //     $this->soundCloudService->syncUserPlaylists($user);
            //     $this->soundCloudService->syncUserProductsAndSubscriptions($user, $soundCloudUser);
            //     $this->soundCloudService->syncUserRealFollowers($user);
            // });
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
                    'urn' => $soundCloudUser->user['urn'],
                    'status' => User::STATUS_ACTIVE
                ]
            );
        } catch (Throwable $e) {
            Log::error('SoundCloud find or create user error', [
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    private function notArtistRedirect($soundCloudUser)
    {
        dd((array) $soundCloudUser['user']);
        if ($soundCloudUser['track_count'] < 0) {
            return redirect()->route('f.landing')
                ->with('error', 'This platform is for artists only! Your account is not an artist account. Please try to login with a real artist account.');
        }
    }
}
