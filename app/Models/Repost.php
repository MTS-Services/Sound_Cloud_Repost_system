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
        'reposted_at',

        'creater_id',
        'updater_id',
        'deleter_id',
        'creater_type',
        'updater_type',
        'deleter_type',
    ];

    protected $casts = [
        'reposted_at' => 'datetime',
        'credits_earned' => 'float',
    ];

    /* =#=#=#=#=#=#=#=#=#=#==#=#=#=#= =#=#=#=#=#=#=#=#=#=#==#=#=#=#=
                Start of RELATIONSHIPS
     =#=#=#=#=#=#=#=#=#=#==#=#=#=#= =#=#=#=#=#=#=#=#=#=#==#=#=#=#= */

    public function request(): BelongsTo
    {
        return $this->belongsTo(RepostRequest::class, 'repost_request_id', 'id');
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
        return $this->belongsTo(Campaign::class, 'campaign_id', 'id');
    }
    public function track()
{
    return $this->belongsTo(Track::class, 'track_id');
}

    /* =#=#=#=#=#=#=#=#=#=#==#=#=#=#= =#=#=#=#=#=#=#=#=#=#==#=#=#=#=
                End of RELATIONSHIPS
     =#=#=#=#=#=#=#=#=#=#==#=#=#=#= =#=#=#=#=#=#=#=#=#=#==#=#=#=#= */

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->appends = array_merge(parent::getAppends(), [
            //
        ]);
    }

}
