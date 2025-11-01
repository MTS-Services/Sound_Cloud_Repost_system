<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Feature extends BaseModel
{
    use SoftDeletes;

    protected $fillable = [
        'sort_order',
        'name',
        'type',
        'key',
        'note',

        'created_by',
        'updated_by',
        'deleted_by'
    ];


    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->appends = array_merge(parent::getAppends(), [

            'features_name',
            'type_name',
            'feature_values',
        ]);
    }


    public const STATUS_ACTIVE = 1;
    public const STATUS_BANNED = 0;

    public static function statusList(): array
    {
        return [
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_BANNED => 'Inactive',
        ];
    }

    public function getStatusLabelAttribute()
    {
        return self::statusList()[$this->status];
    }

    public function getStatusColorAttribute()
    {
        return $this->status == self::STATUS_ACTIVE
            ? 'badge-success'
            : 'badge-error';
    }
    public function getStatusBtnLabelAttribute()
    {
        return $this->status == self::STATUS_ACTIVE ? self::statusList()[self::STATUS_BANNED] : self::statusList();
    }
    public function getStatusBtnColorAttribute()
    {
        return $this->status == self::STATUS_ACTIVE
            ? 'btn-error'
            : 'btn-success';
    }
    public function getStatusBtnClassAttribute()
    {
        return $this->status == self::STATUS_ACTIVE
            ? 'btn btn-error'
            : 'btn btn-success';
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
    public const KEY_DIRECT_REQUESTS = 1;
    public const KEY_SIMULTANEOUS_CAMPAIGNS = 2;
    public const KEY_MULTI_ACCOUNT_PROMOTION = 3;
    public const KEY_CAMPAIGN_TARGETING = 4;
    public const KEY_FEATURED_CAMPAIGN_PRIORITY = 5;
    public const KEY_CAMPAIGN_RATING_AND_ANALYTICS = 6;
    public const KEY_GROWTH_ANALYTICS = 7;
    public const KEY_COMMUNITY_SUPPORT_AND_NETWORKING = 8;
    public const KEY_COLLABORATION_HUB = 9;
    public const KEY_SUPPORT_LEVEL = 10;



    public static function getFeaturedNames(): array
    {
        return [
            self::KEY_DIRECT_REQUESTS => 'Direct Requests',
            self::KEY_SIMULTANEOUS_CAMPAIGNS => 'Simultaneous Campaigns',
            self::KEY_MULTI_ACCOUNT_PROMOTION => 'Multi Account Promotion',
            self::KEY_CAMPAIGN_TARGETING => 'Campaign Targeting',
            self::KEY_FEATURED_CAMPAIGN_PRIORITY => 'Featured Campaign Priority',
            self::KEY_CAMPAIGN_RATING_AND_ANALYTICS => 'Campaign Rating & Analytics',
            self::KEY_GROWTH_ANALYTICS => 'Growth Analytics',
            self::KEY_COMMUNITY_SUPPORT_AND_NETWORKING => 'Community Support & Networking',
            self::KEY_COLLABORATION_HUB => 'Collaboration Hub',
            self::KEY_SUPPORT_LEVEL => 'Support Level',
        ];
    }

    public static function getFeatureValues(): array
    {
        return [
            self::KEY_DIRECT_REQUESTS => [20, 100],
            self::KEY_SIMULTANEOUS_CAMPAIGNS => [2, 10],
            self::KEY_MULTI_ACCOUNT_PROMOTION => ['1 account', 'Unlimited'],
            self::KEY_CAMPAIGN_TARGETING => ['True', 'False'],
            self::KEY_FEATURED_CAMPAIGN_PRIORITY => ['True', 'False'],
            self::KEY_CAMPAIGN_RATING_AND_ANALYTICS => ['Basic', 'Advanced'],
            self::KEY_GROWTH_ANALYTICS => ['True', 'False'],
            self::KEY_COMMUNITY_SUPPORT_AND_NETWORKING => ['True', 'False'],
            self::KEY_COLLABORATION_HUB => ['True', 'False'],
            self::KEY_SUPPORT_LEVEL => ['Community Support', 'Priority Support'],
        ];
    }


    public function getFeaturesNameAttribute(): string // Renamed method
    {
        return self::getFeaturedNames()[$this->key];
    }
    public function getFeatureValuesAttribute()
    {
        $values = self::getFeatureValues()[$this->key] ?? [];
        return $values;
    }


    // In Plan.php model


    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function scopeInactive(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_BANNED);
    }

    public function featureRelations(): HasMany
    {
        return $this->hasMany(FeatureRelation::class, 'feature_id', 'id');
    }

    public function relationValue($plan_id)
    {
        return $this->featureRelations()->where('package_type', Plan::class)->where('package_id', $plan_id)->first();
    }

}
