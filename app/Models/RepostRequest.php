<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RepostRequest extends BaseModel
{
    protected $fillable = [
        'sort_order',
        'requester_urn',
        'target_user_urn',
        'campaign_id',
        'track_urn',
        'credits_spent',
        'status',
        'rejection_reason',
        'requested_at',
        'expired_at',
        'responded_at',
        'completed_at',
        'request_receiveable',

        'creater_id',
        'updater_id',
        'deleter_id',
        'creater_type',
        'updater_type',
        'deleter_type',
    ];

    /* =#=#=#=#=#=#=#=#=#=#==#=#=#=#= =#=#=#=#=#=#=#=#=#=#==#=#=#=#=
                Start of RELATIONSHIPS
     =#=#=#=#=#=#=#=#=#=#==#=#=#=#= =#=#=#=#=#=#=#=#=#=#==#=#=#=#= */

    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requester_urn', 'urn');
    }

    public function targetUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'target_user_urn', 'urn');
    }

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    public function track(): BelongsTo
    {
        return $this->belongsTo(Track::class, 'track_urn', 'urn','soundcloud_urn');
    }

    public function reposts(): HasMany
    {
        return $this->hasMany(Repost::class);
    }

    public function responses(): HasMany
    {
        return $this->hasMany(RepostRequest::class, 'target_user_urn', 'urn');
    }

    public function creditTransactions(): HasMany
    {
        return $this->hasMany(CreditTransaction::class);
    }
    public function directReposts()
    {
        return $this->where('campaign_id', null);
    }


    /* =#=#=#=#=#=#=#=#=#=#==#=#=#=#= =#=#=#=#=#=#=#=#=#=#==#=#=#=#=
                End of RELATIONSHIPS
     =#=#=#=#=#=#=#=#=#=#==#=#=#=#= =#=#=#=#=#=#=#=#=#=#==#=#=#=#= */


    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->appends = array_merge(parent::getAppends(), [
            'status_label',
            'status_color',
            'status_btn_label',
            'repost_at_formatted',
            'pending_to_declined',
        ]);
    }

    public const STATUS_PENDING = 0;
    public const STATUS_APPROVED = 1;
    public const STATUS_DECLINE = 2;
    public const STATUS_EXPIRED = 3;
    public const STATUS_CANCELLED = 5;

    public static function getStatusList(): array
    {
        return [
            self::STATUS_PENDING => 'Pending',
            self::STATUS_APPROVED => 'Approved',
            self::STATUS_DECLINE => 'Decline',
            self::STATUS_EXPIRED => 'Expired',
            self::STATUS_CANCELLED => 'Cancelled',
        ];
    }

    public function getStatusLabelAttribute()
    {
        return self::getStatusList()[$this->status] ?? 'Unknown';
    }
    public function getStatusBtnLabelAttribute()
    {
        return self::getStatusList()[$this->status];
    }
    public function getStatusColorAttribute()
    {
        return [
            self::STATUS_PENDING => 'badge-warning',
            self::STATUS_APPROVED => 'badge-success',
            self::STATUS_DECLINE => 'badge-error',
            self::STATUS_EXPIRED => 'badge-error',
        ] [$this->status] ?? 'badge-warning';
    }
    // pending thakle decline button show korbe
    public function getPendingToDeclinedAttribute()
    {
        return $this->status === self::STATUS_PENDING ? 'Decline' : $this->getStatusLabelAttribute();
    }
    // date time format
    public function getRepostAtFormattedAttribute()
    {
        return timeFormat($this->reposted_at);
    }

    // Status Scopes
    public function scopeApproved($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }
    public function scopeDeclined($query)
    {
        return $query->where('status', self::STATUS_DECLINE);
    }
    public function scopeExpired($query)
    {
        return $query->where('status', self::STATUS_EXPIRED);
    }
}
