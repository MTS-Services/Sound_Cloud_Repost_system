<?php

namespace App\Http\Controllers\SouncCloud\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\SoundCloud\SoundCloudAuthRequest;
use App\Models\Product;
use App\Models\Subscription;
use App\Models\Track;
use App\Models\User;
use App\Models\UserInformation;
use App\Services\SoundCloud\SoundCloudService;
use Illuminate\Http\RedirectResponse;
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

            // $this->soundCloudService->updateUserProfile($user);

            // Login user
            Auth::guard('web')->login($user, true);

            // dd(Auth::guard('web')->check());

            return redirect()->route('user.dashboard')
                ->with('success', 'Successfully connected to SoundCloud!');
        } catch (\Exception $e) {
            Log::error('SoundCloud callback error', [
                'error' => $e->getMessage(),
                'user_urn' => Auth::guard('web')->id(),
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

    public function sync(): RedirectResponse
    {
        $user = Auth::guard('web')->user();

        if (!$user || !$user->isSoundCloudConnected()) {
            return redirect()->route('profile')
                ->with('error', 'Please connect to SoundCloud first.');
        }

        try {
            $syncedCount = $this->soundCloudService->syncUserTracks($user);
            $this->soundCloudService->updateUserProfile($user);

            return redirect()->route('user.dashboard')
                ->with('success', "Successfully synced {$syncedCount} new tracks from SoundCloud.");
        } catch (\Exception $e) {
            Log::error('SoundCloud sync error', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);

            return redirect()->route('dashboard')
                ->with('error', 'Failed to sync tracks from SoundCloud.');
        }
    }

    protected function findOrCreateUser($soundCloudUser): User
    {
        try {
            return DB::transaction(function () use ($soundCloudUser) {



                $user = User::updateOrCreate(
                    ['soundcloud_id' => $soundCloudUser->getId()],
                    [
                        'name' => $soundCloudUser->getName(),
                        'nickname' => $soundCloudUser->getNickname(),
                        'avatar' => $soundCloudUser->getAvatar(),
                        'token' => $soundCloudUser->token,
                        'refresh_token' => $soundCloudUser->refreshToken,
                        'expires_in' => $soundCloudUser->expiresIn,
                        'last_synced_at' => now(),
                        'user_urn' => $soundCloudUser->user['urn']
                    ]
                );

                UserInformation::updateOrCreate(
                    ['user_urn' => $user->urn],
                    [
                        'first_name' => $soundCloudUser->user['first_name'] ?? null,
                        'last_name' => $soundCloudUser->user['last_name'] ?? null,
                        'full_name' => $soundCloudUser->user['full_name'] ?? null,
                        'username' => $soundCloudUser->user['username'] ?? null,

                        'soundcloud_id' => $soundCloudUser->getId(),
                        'soundcloud_urn' => $soundCloudUser->user['urn'] ?? null,
                        'soundcloud_kind' => $soundCloudUser->user['kind'] ?? null,
                        'soundcloud_permalink_url' => $soundCloudUser->user['permalink_url'] ?? null,
                        'soundcloud_permalink' => $soundCloudUser->user['permalink'] ?? null,
                        'soundcloud_uri' => $soundCloudUser->user['uri'] ?? null,
                        'soundcloud_created_at' => $soundCloudUser->user['created_at'] ?? null,
                        'soundcloud_last_modified' => $soundCloudUser->user['last_modified'] ?? null,

                        'description' => $soundCloudUser->user['description'] ?? null,
                        'country' => $soundCloudUser->user['country'] ?? null,
                        'city' => $soundCloudUser->user['city'] ?? null,

                        'track_count' => $soundCloudUser->user['track_count'] ?? 0,
                        'public_favorites_count' => $soundCloudUser->user['public_favorites_count'] ?? 0,
                        'reposts_count' => $soundCloudUser->user['reposts_count'] ?? 0,
                        'followers_count' => $soundCloudUser->user['followers_count'] ?? 0,
                        'following_count' => $soundCloudUser->user['followings_count'] ?? 0,

                        'plan' => $soundCloudUser->user['plan'] ?? 'Free',
                        'myspace_name' => $soundCloudUser->user['myspace_name'] ?? null,
                        'discogs_name' => $soundCloudUser->user['discogs_name'] ?? null,
                        'website_title' => $soundCloudUser->user['website_title'] ?? null,
                        'website' => $soundCloudUser->user['website'] ?? null,

                        'online' => $soundCloudUser->user['online'] ?? false,
                        'comments_count' => $soundCloudUser->user['comments_count'] ?? 0,
                        'like_count' => $soundCloudUser->user['likes_count'] ?? 0,
                        'playlist_count' => $soundCloudUser->user['playlist_count'] ?? 0,
                        'private_playlist_count' => $soundCloudUser->user['private_playlists_count'] ?? 0,
                        'private_tracks_count' => $soundCloudUser->user['private_tracks_count'] ?? 0,

                        'primary_email_confirmed' => $soundCloudUser->user['primary_email_confirmed'] ?? false,
                        'local' => $soundCloudUser->user['locale'] ?? null,
                        'upload_seconds_left' => $soundCloudUser->user['upload_seconds_left'] ?? null,
                    ]
                );

                // Handle SoundCloud products and user subscriptions
                $this->syncUserProductsAndSubscriptions($user, $soundCloudUser);


                return $user;
            });
        } catch (Throwable $e) {
            Log::error('SoundCloud findOrCreateUser error', [
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
        $user->subscriptions()->delete();

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
