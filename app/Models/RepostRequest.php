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
        return $this->belongsTo(Track::class, 'track_urn', 'urn');
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

    /* =#=#=#=#=#=#=#=#=#=#==#=#=#=#= =#=#=#=#=#=#=#=#=#=#==#=#=#=#=
                End of RELATIONSHIPS
     =#=#=#=#=#=#=#=#=#=#==#=#=#=#= =#=#=#=#=#=#=#=#=#=#==#=#=#=#= */


    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->appends = array_merge(parent::getAppends(), [
            'status_label',
        ]);
    }

    public const STATUS_PENDING = '0';
    public const STATUS_APPROVED = '1';
    public const STATUS_REJECTED = '2';
    public const STATUS_EXPIRED = '3';
    public const STATUS_COMPLETED = '4';

    public static function getStatusList(): array
    {
        return [
            self::STATUS_PENDING => 'Pending',
            self::STATUS_APPROVED => 'Approved',
            self::STATUS_REJECTED => 'Rejected',
            self::STATUS_EXPIRED => 'Expired',
            self::STATUS_COMPLETED => 'Completed',
        ];
    }

    public function getStatusLabelAttribute()
    {
        return self::getStatusList()[$this->status];
    }
}
