<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\AuthBaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class User extends AuthBaseModel
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable , SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'sort_order',
        'soundcloud_id',
        'name',
        'nickname',
        'avatar',
        'token',
        'refresh_token',
        'expires_in',
        'status',
        'last_sync_at',

        'creater_id',
        'updater_id',
        'deleter_id',

        'creater_type',
        'updater_type',
        'deleter_type',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'token',
        'refresh_token',
        'remember_token',
    ];


    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'expires_in' => 'integer',
            'email_verified_at' => 'datetime',
            'last_sync_at' => 'datetime',
        ];
    }

    // ================================  Relationships ===================================

    public function userInfo()
    {
        return $this->hasOne(UserInformation::class);
    }

    public function soundcloudTracks()
    {
        return $this->hasMany(SoundcloudTrack::class);
    }

    // Helper methods
    public function isSoundCloudConnected(): bool
    {
        return !empty($this->token);
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

    public const STATUS_ACTIVE = 1;
    public const STATUS_INACTIVE = 2;

    // status list
    public static function getStatusList(): array
    {
        return [
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_INACTIVE => 'Inactive',
        ];
    }
    public function getStatusLabelAttribute(): string
    {
        return self::getStatusList()[$this->status];
    }


}
