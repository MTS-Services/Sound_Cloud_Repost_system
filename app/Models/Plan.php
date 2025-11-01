<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Plan extends BaseModel
{
    protected $fillable = [
        'name',
        'tag',
        'status',
        'notes',
        'monthly_price',

        'created_by',
        'updated_by',
        'deleted_by'
    ];



    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->appends = array_merge(parent::getAppends(), [

            'status_label',
            'status_color',
            'status_btn_label',
            'status_btn_color',
            'tag_label',
            'yearly_price',
            'yearly_save_price',
            'yearly_save_percentage',
        ]);
    }

    public const TAG_FREE = null;
    public const TAG_MOST_POPULAR = 1;
    public const TAG_PRO = 2;

    public static function getTagList(): array
    {
        return [
            self::TAG_FREE => 'Free',
            self::TAG_MOST_POPULAR => 'Most popular',
            self::TAG_PRO => 'Pro plan',
        ];
    }
    public function getTagLabelAttribute()
    {
        return self::getTagList()[$this->tag] ?? 'Null';
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
        return $this->status == self::STATUS_ACTIVE ? 'badge-success' : 'badge-error';
    }

    public function getStatusBtnLabelAttribute()
    {
        return $this->status == self::STATUS_ACTIVE ? self::statusList()[self::STATUS_BANNED] : self::statusList()[self::STATUS_ACTIVE];
    }

    public function getStatusBtnColorAttribute()
    {
        return $this->status == self::STATUS_ACTIVE ? 'btn-error' : 'btn-success';
    }

    public function featureRelations(): MorphMany
    {
        return $this->morphMany(FeatureRelation::class, 'package');
    }

    public function features(): HasManyThrough
    {
        return $this->hasManyThrough(
            Feature::class,
            FeatureRelation::class,
            'package_id',
            'id',
            'id',
            'feature_id'
        );
    }

    // In Plan.php model
    public static function getYearlySavePercentage(): float|int
    {
        return ApplicationSetting::where('key', 'plan_yearly_save_persentage')->first()?->value ?? 0;
    }

    public function getYearlySavePercentageAttribute(): float|int
    {
        return self::getYearlySavePercentage();
    }

    public function getYearlyPriceAttribute(): float|int
    {

        return ceil(($this->monthly_price * 12) * (100 - self::getYearlySavePercentage()) / 100);
    }
    public function getYearlySavePriceAttribute(): float|int
    {
        return ($this->monthly_price * 12) - $this->yearly_price;
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function scopeInactive(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_BANNED);
    }
}
