<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class Feature extends BaseModel
{
    use SoftDeletes;

    protected $fillable = [
        'sort_order',
        'category_id',
        'name',
        'key',
        'type',

        'created_by',
        'updated_by',
        'deleted_by'
    ];

    public function feature_category()
    {
        return $this->belongsTo(FeatureCategory::class, 'category_id');
    }

    /////////////////////////////////////
    ////////// Feature Types /////////////
    ///////////////////////////////////
    public const TYPE_STRING = 0;
    public const TYPE_BOOLEAN = 1;

    public static function types(): array
    {
        return [
            self::TYPE_STRING => 'string',
            self::TYPE_BOOLEAN => 'boolean'
        ];
    }

    /////////////////////////////////////
    ////////// Feature Keys /////////////
    ///////////////////////////////////
    public const FEATURE_KEY_AUTOBOOST                         = 1;
    public const FEATURE_KEY_CAMPAIGN_TARGETING                = 2;
    public const FEATURE_KEY_EXEMPT_FROM_INACTIVITY_DEDUCTION = 3;
    public const FEATURE_KEY_FEATURED_CAMPAIGNS                = 4;
    public const FEATURE_KEY_FREE_BOOSTS_PER_CAMPAIGN          = 5;
    public const FEATURE_KEY_MANAGED_CAMPAIGNS                 = 6;
    public const FEATURE_KEY_MAX_CAMPAIGN_BUDGET               = 7;
    public const FEATURE_KEY_MONITOR_AND_REMOVE_REPOSTS        = 8;
    public const FEATURE_KEY_OPEN_DIRECT_REQUESTS              = 9;
    public const FEATURE_KEY_POWER_HOUR_MULTIPLIER             = 10;
    public const FEATURE_KEY_PRIORITY_CAMPAIGN_VISIBILITY      = 11;
    public const FEATURE_KEY_PRIORITY_DIRECT_REPOST_REQUESTS   = 12;
    public const FEATURE_KEY_PROMOTE_MULTIPLE_SC_ACCOUNTS      = 13;
    public const FEATURE_KEY_PROMOTE_OTHER_SOCIALS             = 14;
    public const FEATURE_KEY_SIMULTANEOUS_CAMPAIGNS            = 15;
    public const FEATURE_KEY_SITEWIDE_DISCOUNT                 = 16;
    public const FEATURE_KEY_SORT_CAMPAIGNS_BY_RATING          = 17;
    public const FEATURE_KEY_SOUNDCLOUD_CHART_NOTIFIER         = 18;
    public const FEATURE_KEY_SPONSORED_FOLLOW_CAMPAIGNS        = 19;
    public const FEATURE_KEY_WAVEPLAYER_ARTWORK                = 20;

    public static function keys(): array
    {
        return [
            self::FEATURE_KEY_AUTOBOOST                         => 'autoboost',
            self::FEATURE_KEY_CAMPAIGN_TARGETING                => 'campaign_targeting',
            self::FEATURE_KEY_EXEMPT_FROM_INACTIVITY_DEDUCTION => 'exempt_from_inactivity_deduction',
            self::FEATURE_KEY_FEATURED_CAMPAIGNS                => 'featured_campaigns',
            self::FEATURE_KEY_FREE_BOOSTS_PER_CAMPAIGN          => 'free_boosts_per_campaign',
            self::FEATURE_KEY_MANAGED_CAMPAIGNS                 => 'managed_campaigns',
            self::FEATURE_KEY_MAX_CAMPAIGN_BUDGET               => 'max_campaign_budget',
            self::FEATURE_KEY_MONITOR_AND_REMOVE_REPOSTS        => 'monitor_and_remove_reposts',
            self::FEATURE_KEY_OPEN_DIRECT_REQUESTS              => 'open_direct_requests',
            self::FEATURE_KEY_POWER_HOUR_MULTIPLIER             => 'power_hour_multiplier',
            self::FEATURE_KEY_PRIORITY_CAMPAIGN_VISIBILITY      => 'priority_campaign_visibility',
            self::FEATURE_KEY_PRIORITY_DIRECT_REPOST_REQUESTS   => 'priority_direct_repost_requests',
            self::FEATURE_KEY_PROMOTE_MULTIPLE_SC_ACCOUNTS      => 'promote_multiple_sc_accounts',
            self::FEATURE_KEY_PROMOTE_OTHER_SOCIALS             => 'promote_other_socials',
            self::FEATURE_KEY_SIMULTANEOUS_CAMPAIGNS            => 'simultaneous_campaigns',
            self::FEATURE_KEY_SITEWIDE_DISCOUNT                 => 'sitewide_discount',
            self::FEATURE_KEY_SORT_CAMPAIGNS_BY_RATING          => 'sort_campaigns_by_rating',
            self::FEATURE_KEY_SOUNDCLOUD_CHART_NOTIFIER         => 'soundcloud_chart_notifier',
            self::FEATURE_KEY_SPONSORED_FOLLOW_CAMPAIGNS        => 'sponsored_follow_campaigns',
            self::FEATURE_KEY_WAVEPLAYER_ARTWORK                => 'waveplayer_artwork',
        ];
    }

}
