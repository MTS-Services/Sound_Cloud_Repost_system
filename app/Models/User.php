<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\AuthBaseModel;
use Illuminate\Notifications\Notifiable;

class User extends AuthBaseModel
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'email_verified_at',
        'password',
        'status',
        'soundcloud_id',
        'soundcloud_username',
        'soundcloud_avatar',
        'soundcloud_track_count',
        'soundcloud_followings_count',
        'soundcloud_followers_count',
        'soundcloud_access_token',
        'soundcloud_refresh_token',
        'soundcloud_token_expires_at',
        'last_sync_at',
        'credits',

        'creater_id',
        'creater_type',
        'updater_id',
        'updater_type',
        'deleter_id',
        'deleter_type',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'soundcloud_access_token',
        'soundcloud_refresh_token',

    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'soundcloud_token_expires_at' => 'datetime',
            'last_sync_at' => 'datetime',
            'password' => 'hashed',

        ];
    }

    // Relationships
    public function soundcloudTracks()
    {
        return $this->hasMany(SoundcloudTrack::class);
    }

    // Helper methods
    public function isSoundCloudConnected(): bool
    {
        return !empty($this->soundcloud_access_token);
    }

    public function getSoundCloudAvatarAttribute($value): string
    {
        return $value ?: 'https://via.placeholder.com/150x150?text=No+Avatar';
    }

    public function needsTokenRefresh(): bool
    {
        if (!$this->soundcloud_token_expires_at) {
            return false;
        }

        return $this->soundcloud_token_expires_at->isPast();
    }

}
