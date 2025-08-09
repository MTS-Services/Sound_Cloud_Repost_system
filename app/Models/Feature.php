<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Feature extends BaseModel
{
    use SoftDeletes;

    protected $fillable = [
        'sort_order',
        'feature_category_id',
        'name',
        'type',
        'key',

        'created_by',
        'updated_by',
        'deleted_by'
    ];

    public function featureCategory()
    {
        return $this->belongsTo(FeatureCategory::class, 'feature_category_id', 'id');
    }

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->appends = array_merge(parent::getAppends(), [

            'features_name',
            'type_name',
            'feature_values',
        ]);
    }

    /////////////////////////////////////
    ////////// Feature Types /////////////
    ///////////////////////////////////
    public const TYPE_STRING = 0;
    public const TYPE_BOOLEAN = 1;

    public static function getTypes(): array
    {
        return [
            self::TYPE_STRING => 'String',
            self::TYPE_BOOLEAN => 'Boolean',
        ];
    }
    public function getTypeNameAttribute(): string
    {
        return self::getTypes()[$this->type];
    }


    /////////////////////////////////////
    ////////// Feature Keys /////////////
    ///////////////////////////////////
    public const FEATURE_KEY_AUTOBOOST = 1;
    public const FEATURE_KEY_CAMPAIGN_TARGETING = 2;
    public const FEATURE_KEY_EXEMPT_FROM_INACTIVITY_DEDUCTION = 3;
    public const FEATURE_KEY_FEATURED_CAMPAIGNS = 4;
    public const FEATURE_KEY_FREE_BOOSTS_PER_CAMPAIGN = 5;
    public const FEATURE_KEY_MANAGED_CAMPAIGNS = 6;
    public const FEATURE_KEY_MAX_CAMPAIGN_BUDGET = 7;
    public const FEATURE_KEY_MONITOR_AND_REMOVE_REPOSTS = 8;
    public const FEATURE_KEY_OPEN_DIRECT_REQUESTS = 9;
    public const FEATURE_KEY_POWER_HOUR_MULTIPLIER = 10;
    public const FEATURE_KEY_PRIORITY_CAMPAIGN_VISIBILITY = 11;
    public const FEATURE_KEY_PRIORITY_DIRECT_REPOST_REQUESTS = 12;
    public const FEATURE_KEY_PROMOTE_MULTIPLE_SC_ACCOUNTS = 13;
    public const FEATURE_KEY_PROMOTE_OTHER_SOCIALS = 14;
    public const FEATURE_KEY_SIMULTANEOUS_CAMPAIGNS = 15;
    public const FEATURE_KEY_SITEWIDE_DISCOUNT = 16;
    public const FEATURE_KEY_SORT_CAMPAIGNS_BY_RATING = 17;
    public const FEATURE_KEY_SOUNDCLOUD_CHART_NOTIFIER = 18;
    public const FEATURE_KEY_SPONSORED_FOLLOW_CAMPAIGNS = 19;
    public const FEATURE_KEY_WAVEPLAYER_ARTWORK = 20;

    public static function getFeaturedNames(): array
    {
        return [
            self::FEATURE_KEY_AUTOBOOST => 'Autoboost',
            self::FEATURE_KEY_CAMPAIGN_TARGETING => 'Campaign Targeting',
            self::FEATURE_KEY_EXEMPT_FROM_INACTIVITY_DEDUCTION => 'Exempt From Inactivity Deduction',
            self::FEATURE_KEY_FEATURED_CAMPAIGNS => 'Featured Campaigns',
            self::FEATURE_KEY_FREE_BOOSTS_PER_CAMPAIGN => 'Free Boosts Per Campaign',
            self::FEATURE_KEY_MANAGED_CAMPAIGNS => 'Managed Campaigns',
            self::FEATURE_KEY_MAX_CAMPAIGN_BUDGET => 'Max Campaign Budget',
            self::FEATURE_KEY_MONITOR_AND_REMOVE_REPOSTS => 'Monitor & Remove Reposts',
            self::FEATURE_KEY_OPEN_DIRECT_REQUESTS => 'Open Direct Requests',
            self::FEATURE_KEY_POWER_HOUR_MULTIPLIER => 'Power Hour Multiplier',
            self::FEATURE_KEY_PRIORITY_CAMPAIGN_VISIBILITY => 'Priority Campaign Visibility',
            self::FEATURE_KEY_PRIORITY_DIRECT_REPOST_REQUESTS => 'Priority Direct Repost Requests',
            self::FEATURE_KEY_PROMOTE_MULTIPLE_SC_ACCOUNTS => 'Promote Multiple SC Accounts',
            self::FEATURE_KEY_PROMOTE_OTHER_SOCIALS => 'Promote Other Socials',
            self::FEATURE_KEY_SIMULTANEOUS_CAMPAIGNS => 'Simultaneous Campaigns',
            self::FEATURE_KEY_SITEWIDE_DISCOUNT => 'Sitewide Discount',
            self::FEATURE_KEY_SORT_CAMPAIGNS_BY_RATING => 'Sort Campaigns By Rating',
            self::FEATURE_KEY_SOUNDCLOUD_CHART_NOTIFIER => 'SoundCloud Chart Notifier',
            self::FEATURE_KEY_SPONSORED_FOLLOW_CAMPAIGNS => 'Sponsored Follow Campaigns',
            self::FEATURE_KEY_WAVEPLAYER_ARTWORK => 'Waveplayer Artwork',
        ];
    }

    public function getFeatureValues(): array
    {
        return [
                // Core Features
            self::FEATURE_KEY_OPEN_DIRECT_REQUESTS => ['25', '50', '100', '500', '1000'],
            self::FEATURE_KEY_SIMULTANEOUS_CAMPAIGNS => ['1', '2', '3', '10', '20'],
            self::FEATURE_KEY_FREE_BOOSTS_PER_CAMPAIGN => ['false', '5', '10', '15', '20'],
            self::FEATURE_KEY_PRIORITY_DIRECT_REPOST_REQUESTS => ['true', 'false'],
            self::FEATURE_KEY_PRIORITY_CAMPAIGN_VISIBILITY => ['true', 'false'],
            self::FEATURE_KEY_CAMPAIGN_TARGETING => ['true', 'false'],
            self::FEATURE_KEY_AUTOBOOST => ['true', 'false'],
            self::FEATURE_KEY_SOUNDCLOUD_CHART_NOTIFIER => ['true', 'false'],
            self::FEATURE_KEY_POWER_HOUR_MULTIPLIER => ['1x', '1.5x', '2x', '2x', '2x'],
            self::FEATURE_KEY_PROMOTE_MULTIPLE_SC_ACCOUNTS => ['false', '3/month', '15/month', 'Unlimited'],
            self::FEATURE_KEY_SITEWIDE_DISCOUNT => ['10%', 'false', '20%'],

                // Campaign Features
            self::FEATURE_KEY_FEATURED_CAMPAIGNS => ['false', '1', '3', '5'],
            self::FEATURE_KEY_MAX_CAMPAIGN_BUDGET => ['1000', '2500', '5000', '10000', '25000'],
            self::FEATURE_KEY_SORT_CAMPAIGNS_BY_RATING => ['true', 'false'],
            self::FEATURE_KEY_SPONSORED_FOLLOW_CAMPAIGNS => ['true', 'false'],
            self::FEATURE_KEY_MANAGED_CAMPAIGNS => ['true', 'false'],

                // Other Features
            self::FEATURE_KEY_WAVEPLAYER_ARTWORK => ['true', 'false'],
            self::FEATURE_KEY_MONITOR_AND_REMOVE_REPOSTS => ['true', 'false'],
            self::FEATURE_KEY_PROMOTE_OTHER_SOCIALS => ['true', 'false'],
            self::FEATURE_KEY_EXEMPT_FROM_INACTIVITY_DEDUCTION => ['true', 'false'],
        ];
    }


    public function getFeaturesNameAttribute(): string // Renamed method
    {
        return self::getFeaturedNames()[$this->key];
    }
    public function getFeatureValuesAttribute()
    {
        $values = self::getFeatureValues()[$this->key] ?? [];

        return array_map(function ($val) {
            if ($val === true) {
                return 'true';
            } elseif ($val === false) {
                return 'false';
            }
            return $val;
        }, $values);
    }


    // In Plan.php model

}
