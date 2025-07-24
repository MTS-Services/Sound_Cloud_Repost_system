<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Repost extends BaseModel
{
    protected $fillable = [
        'sort_order',
        'repost_request_id',
        'reposter_urn',
        'track_owner_urn',
        'campaign_id',
        'soundcloud_repost_id',
        'credits_earned',
        'service_fee',
        'net_credits',
        'reposted_at',
        'is_verified',

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

     public function request(): BelongsTo
    {
        return $this->belongsTo(RepostRequest::class);
    }
    public function reposter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reposter_urn', 'urn');
    }

    public function trackOwner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'track_owner_urn', 'urn');
    }

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }
    
    /* =#=#=#=#=#=#=#=#=#=#==#=#=#=#= =#=#=#=#=#=#=#=#=#=#==#=#=#=#=
                End of RELATIONSHIPS
     =#=#=#=#=#=#=#=#=#=#==#=#=#=#= =#=#=#=#=#=#=#=#=#=#==#=#=#=#= */

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->appends = array_merge(parent::getAppends(), [
            'is_verified_label',
        ]);
    }

    public const IS_VERIFIED_NO = 0;
    public const IS_VERIFIED_YES = 1;

    public static function getIsVerifiedList(): array
    {
        return [
            self::IS_VERIFIED_NO => 'No',
            self::IS_VERIFIED_YES => 'Yes',
        ];
    }

    public function getIsVerifiedLabelAttribute()
    {
        return self::getIsVerifiedList()[$this->is_verified];
    }
}
