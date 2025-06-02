<?php

namespace App\Services\Socialite;

use Laravel\Socialite\Two\AbstractProvider;
use Laravel\Socialite\Two\ProviderInterface;
use Laravel\Socialite\Two\User;

class SoundCloudProvider extends AbstractProvider implements ProviderInterface
{
    protected $scopes = ['non-expiring'];
    protected $scopeSeparator = ' ';

    protected function getAuthUrl($state): string
    {
        return $this->buildAuthUrlFromBase('https://soundcloud.com/connect', $state);
    }

    protected function getTokenUrl(): string
    {
        return 'https://api.soundcloud.com/oauth2/token';
    }

    protected function getUserByToken($token): array
    {
        $response = $this->getHttpClient()->get('https://api.soundcloud.com/me', [
            'headers' => [
                'Authorization' => 'OAuth ' . $token,
            ],
        ]);

        return json_decode($response->getBody(), true);
    }

    protected function mapUserToObject(array $user): User
    {
        return (new User)->setRaw($user)->map([
            'id' => $user['id'],
            'nickname' => $user['username'],
            'name' => $user['full_name'] ?? $user['username'],
            'email' => $user['email'] ?? null,
            'avatar' => $user['avatar_url'],
        ]);
    }

    protected function getTokenFields($code): array
    {
        return array_merge(parent::getTokenFields($code), [
            'grant_type' => 'authorization_code',
        ]);
    }
}
