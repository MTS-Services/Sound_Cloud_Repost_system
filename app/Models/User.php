<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\AuthBaseModel;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
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
        'sort_order',
        'soundcloud_id',
        'name',
        'nickname',
        'avatar',
        'token',
        'refresh_token',
        'expires_in',
        'last_sync_at',
        'status',
        'urn',
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
            'urn' => 'string',
        ];
    }

    /* =#=#=#=#=#=#=#=#=#=#==#=#=#=#= =#=#=#=#=#=#=#=#=#=#==#=#=#=#=
                Start of RELATIONSHIPS
     =#=#=#=#=#=#=#=#=#=#==#=#=#=#= =#=#=#=#=#=#=#=#=#=#==#=#=#=#= */

    public function userInfo(): HasOne
    {
        return $this->hasOne(UserInformation::class, 'user_urn', 'urn');
    }

    public function tracks(): HasMany
    {
        return $this->hasMany(Track::class, 'user_urn', 'urn');
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class, 'user_urn', 'urn');
    }

    public function features(): HasMany
    {
        return $this->hasMany(Feature::class, 'user_urn', 'urn');
    }

    public function playlists(): HasMany
    {
        return $this->hasMany(Playlist::class, 'user_urn', 'urn');
    }

    public function campaigns(): HasMany
    {
        return $this->hasMany(Campaign::class, 'user_urn', 'urn');
    }

    public function requests(): HasMany
    {
        return $this->hasMany(RepostRequest::class, 'requester_urn', 'urn');
    }

    public function responses(): HasMany
    {
        return $this->hasMany(RepostRequest::class, 'target_user_urn', 'urn');
    }

    public function reposts(): HasMany
    {
        return $this->hasMany(Repost::class, 'reposter_urn', 'urn');
    }

    public function trackOwners(): HasMany
    {
        return $this->hasMany(Repost::class, 'track_owner_urn', 'urn');
    }

    public function sendTransactions(): HasMany
    {
        return $this->hasMany(CreditTransaction::class, 'sender_urn', 'urn');
    }

    public function receiveTransactions(): HasMany
    {
        return $this->hasMany(CreditTransaction::class, 'receiver_urn', 'urn');
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(CreditTransaction::class, 'receiver_urn', 'urn');
    }
    public function debitTransactions(): HasMany
    {
        return $this->hasMany(CreditTransaction::class, 'receiver_urn', 'urn')->where('calculation_type', CreditTransaction::CALCULATION_TYPE_DEBIT)->where('status', 'succeeded');
    }
    public function creditTransactions(): HasMany
    {
        return $this->hasMany(CreditTransaction::class, 'receiver_urn', 'urn')->where('calculation_type', CreditTransaction::CALCULATION_TYPE_CREDIT)->where('status', 'succeeded');
    }
    public function succedDebitTransactions(): HasMany
    {
        return $this->hasMany(CreditTransaction::class, 'receiver_urn', 'urn')
            ->where('calculation_type', CreditTransaction::CALCULATION_TYPE_DEBIT)
            ->where('status', 'succeeded');
    }

    public function succedCreditTransactions(): HasMany
    {
        return $this->hasMany(CreditTransaction::class, 'receiver_urn', 'urn')
            ->where('calculation_type', CreditTransaction::CALCULATION_TYPE_CREDIT)
            ->where('status', 'succeeded');
    }



    /* =#=#=#=#=#=#=#=#=#=#==#=#=#=#= =#=#=#=#=#=#=#=#=#=#==#=#=#=#=
                End of RELATIONSHIPS
     =#=#=#=#=#=#=#=#=#=#==#=#=#=#= =#=#=#=#=#=#=#=#=#=#==#=#=#=#= */

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
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->appends = array_merge(parent::getAppends(), [

            'status_label',
            'status_color',
            'status_btn_label',
            'status_btn_color',

            'modified_image',
        ]);
    }

    public const STATUS_ACTIVE = 1;
    public const STATUS_INACTIVE = 0;

    public static function statusList(): array
    {
        return [
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_INACTIVE => 'Inactive',
        ];
    }
    public function getStatusLabelAttribute()
    {
        return self::statusList()[$this->status];
    }

    public function getStatusColorAttribute()
    {
        return $this->status == self::STATUS_ACTIVE ? 'badge-success' : 'badge-error';
    }

    public function getStatusBtnLabelAttribute()
    {
        return $this->status == self::STATUS_ACTIVE ? self::statusList()[self::STATUS_INACTIVE] : self::statusList()[self::STATUS_ACTIVE];
    }

    public function getStatusBtnColorAttribute()
    {
        return $this->status == self::STATUS_ACTIVE ? 'btn-error' : 'btn-success';
    }
    public function getModifiedImageAttribute()
    {
        return auth_storage_url($this->image);
    }
}
