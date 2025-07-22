<?php

namespace App\Http\Controllers\SouncCloud\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\SoundCloud\SoundCloudAuthRequest;
use App\Models\Playlist;
use App\Models\Product;
use App\Models\Subscription;
use App\Models\Track;
use App\Models\User;
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
use Throwable;

class SoundCloudController extends Controller
{
    public function __construct(protected SoundCloudService $soundCloudService) {}

    public function redirect(): RedirectResponse
    {
        try {
            return Socialite::driver('soundcloud')->redirect();
        } catch (\Exception $e) {
            Log::error('SoundCloud redirect error', [
                'error' => $e->getMessage(),
            ]);

            return redirect()->route('login')
                ->with('error', 'Unable to connect to SoundCloud. Please try again.');
        }
    }

    public function callback(SoundCloudAuthRequest $request): RedirectResponse
    {
        // Check for error from SoundCloud
        if ($request->has('error')) {
            return redirect()->route('login')
                ->with('error', 'SoundCloud authentication was cancelled or failed.');
        }

        try {
            $soundCloudUser = Socialite::driver('soundcloud')->user();

            // Find or create user
            $user = $this->findOrCreateUser($soundCloudUser);

            // Sync user tracks
            $this->soundCloudService->syncUserTracks($user);
           
            //user followings
             $this->soundCloudService->syncUserFollowers($user);

            Auth::guard('web')->login($user, true);

            return redirect()->route('user.dashboard')
                ->with('success', 'Successfully connected to SoundCloud!');
        } catch (\Exception $e) {
            Log::error('SoundCloud callback error', [
                'error' => $e->getMessage(),
                'user_id' => Auth::guard('web')->id(),
            ]);

            return redirect()->route('login')
                ->with('error', 'Failed to authenticate with SoundCloud. Please try again.');
        }
    }

    public function disconnect(): RedirectResponse
    {
        $user = Auth::guard('web')->user();

        if (!$user) {
            return redirect()->route('login');
        }

        try {
            // Clear SoundCloud data
            $user->update([
                'soundcloud_id' => null,
                'nickname' => null,
                'avatar' => null,
                'soundcloud_followings_count' => 0,
                'soundcloud_followers_count' => 0,
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
                $this->soundCloudService->syncUserTracks($user);
                $this->syncUserProductsAndSubscriptions($user, $soundCloudUser);
                $this->soundCloudService->updateUserPlaylists($user);
                $this->soundCloudService->syncUserInformation($user, $soundCloudUser);
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
            return User::updateOrCreate(
                ['soundcloud_id' => $soundCloudUser->getId()],
                [
                    'name' => $soundCloudUser->getName(),
                    'nickname' => $soundCloudUser->getNickname(),
                    'avatar' => $soundCloudUser->getAvatar(),
                    'token' => $soundCloudUser->token,
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

    /**
     * Syncs products and user subscriptions based on SoundCloud user data.
     */
    protected function syncUserProductsAndSubscriptions(User $user, $soundCloudUser): void
    {
        // Clear existing subscriptions for the user to sync fresh ones
        // This assumes you want to overwrite previous subscriptions with current data
        Subscription::where('user_urn', $user->urn)->delete();

        if (isset($soundCloudUser->user['subscriptions']) && is_array($soundCloudUser->user['subscriptions'])) {
            foreach ($soundCloudUser->user['subscriptions'] as $subscriptionData) {
                $productDetails = $subscriptionData['product'] ?? null;

                if ($productDetails && isset($productDetails['id']) && isset($productDetails['name'])) {
                    $product = Product::updateOrCreate(
                        ['product_id' => $productDetails['id']],
                        ['name' => $productDetails['name']]
                    );

                    // Create the user's subscription record
                    Subscription::create([
                        'user_urn' => $user->urn,
                        'product_id' => $product->id,
                    ]);
                } else {
                    Log::warning('SoundCloud subscription found without complete product data.', [
                        'soundcloud_id' => $soundCloudUser->getId(),
                        'subscription_data' => $subscriptionData,
                    ]);
                }
            }
        } else {
            Log::info('SoundCloud user has no subscriptions array or it is not an array.', [
                'soundcloud_id' => $soundCloudUser->getId(),
            ]);
        }
    }
}
