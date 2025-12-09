<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Services\SoundCloud\FollowerAnalyzer;
use App\Services\SoundCloud\SoundCloudService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\AuthBaseModel;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Notifications\Notifiable;
use App\Http\Traits\UserModificationTrait;
use Carbon\Carbon;

class User extends AuthBaseModel implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    // use HasFactory, Notifiable, UserModificationTrait;
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'sort_order',
        'soundcloud_id',
        'soundcloud_permalink_url',
        'name',
        'nickname',
        'avatar',
        'token',
        'email_token',
        'refresh_token',
        'expires_in',
        'last_synced_at',
        'status',
        'banned_at',
        'bander_id',
        'ban_reason',
        'urn',
        'email',
        'last_seen_at',

        'real_followers',
        'real_followers_percentage',

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
            'last_synced_at' => 'datetime',
            'urn' => 'string',
            'last_seen_at' => 'datetime',
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
        return $this->hasMany(CreditTransaction::class, 'receiver_urn', 'urn')->where('calculation_type', CreditTransaction::CALCULATION_TYPE_DEBIT)->succeeded();
    }
    public function creditTransactions(): HasMany
    {
        return $this->hasMany(CreditTransaction::class, 'receiver_urn', 'urn')->where('calculation_type', CreditTransaction::CALCULATION_TYPE_CREDIT)->succeeded();
    }
    public function succedDebitTransactions()
    {
        return $this->debitTransactions();
    }

    public function succedCreditTransactions()
    {
        return $this->creditTransactions();
    }

    public function analytics(): HasMany
    {
        return $this->hasMany(UserAnalytics::class, 'user_urn', 'urn');
    }

    public function genres(): HasMany
    {
        return $this->hasMany(UserGenre::class, 'user_urn', 'urn');
    }
    public function userPlans(): HasMany
    {
        return $this->hasMany(UserPlan::class, 'user_urn', 'urn');
    }

    public function userSettings(): HasOne
    {
        return $this->hasOne(UserSetting::class, 'user_urn', 'urn');
    }
    public function starredUsers(): HasMany
    {
        return $this->hasMany(StarredUser::class, 'starred_user_urn', 'urn');
    }

    /* =#=#=#=#=#=#=#=#=#=#==#=#=#=#= =#=#=#=#=#=#=#=#=#=#==#=#=#=#=
                End of RELATIONSHIPS
     =#=#=#=#=#=#=#=#=#=#==#=#=#=#= =#=#=#=#=#=#=#=#=#=#==#=#=#=#= */

    // Helper methods
    public function isSoundCloudConnected(): bool
    {
        return !is_null($this->token) && !is_null($this->refresh_token);
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
            'is_pro',
            'repost_price',
            'banned_at_formatted',
            'banned_by'
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
        return isset($this->status) ? self::statusList()[$this->status] : 'Unknown';
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
        return soundcloud_image($this->image);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function activePlan()
    {
        return $this->userPlans()->where('status', UserPlan::STATUS_ACTIVE)->whereDate('end_date', '>=', now())->first();
    }

    public function getIsProAttribute(): bool
    {
        $activePlan = $this->activePlan();
        return ($activePlan && $activePlan->monthly_price != 0) ? true : false;
    }

    public function scopeIsPro()
    {
        return $this->is_pro == true;
    }


    public function isOnline(): bool
    {
        return $this->last_seen_at !== null && $this->last_seen_at->gt(now()->subMinutes(2));
    }

    public function offlineStatus(): string
    {
        return (!$this->isOnline() && $this->last_seen_at !== null && $this->last_seen_at->diffInMinutes(now()) < 60) ? round($this->last_seen_at->diffInMinutes(now())) . ' min' : 'Offline';
    }
    public function responseRate(): float
    {
        $resetAt = $this->userSettings?->response_rate_reset;
        $query = $this->responses()->whereNotNull('requested_at');

        if ($resetAt) {
            $query->where('requested_at', '>=', $resetAt);
        }

        $totalRequests = $query->count();

        if ($totalRequests === 0) {
            return 100;
        }

        // Clone the query to count responses within 24 hours
        $respondedWithin24Hours = (clone $query)
            ->whereNotNull('responded_at')
            ->whereRaw('TIMESTAMPDIFF(HOUR, requested_at, responded_at) <= 24')
            ->count();

        // Calculate percentage
        return round(($respondedWithin24Hours / $totalRequests) * 100, 2);
    }

    public function getRepostPriceAttribute()
    {
        return $this->real_followers && ceil($this->real_followers / 100) > 0 ? ceil($this->real_followers / 100) : 1;
    }

    public function canResetResponseRate(): bool
    {
        $resetAt = $this->userSettings?->response_rate_reset;

        if (!$resetAt) {
            return true;
        }
        return !Carbon::parse($resetAt)->greaterThan(now()->subMonth(1));
    }

    public function getBannedAtFormattedAttribute()
    {
        return $this->banned_at ? timeFormat($this->banned_at) : 'N/A';
    }
    public function getBannedByAttribute()
    {
        if (!$this->bander_id) {
            return 'System';
        }

        $admin = Admin::find($this->bander_id);
        return $admin ? $admin->name : 'System';
    }
}
