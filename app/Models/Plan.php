<?php

namespace App\Models;

use App\Models\BaseModel;

class Plan extends BaseModel
{
    protected $fillable = [
        'name',
        'slug',
        'price_monthly',
        'price_monthly_yearly',
        'tag',
        'status',
        'notes',

        'created_by',
        'updated_by',
        'deleted_by'
    ];

    public function features()
    {
        return $this->morphMany(FeatureRelation::class, 'package');
    }

      public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->appends = array_merge(parent::getAppends(), [

            'status_label',
            'status_color',
            'status_btn_label',
            'status_btn_color',
            'tag_label',
        ]);
    }

    public const TAG_MOST_POPULAR = 1;
    public const TAG_PRO = 2;

    public static function getTagList(): array
    {
        return [
            self::TAG_MOST_POPULAR => 'Most popular',
            self::TAG_PRO => 'Pro plans',
        ];
    }
    public function getTagLabelAttribute()
    {
        return self::getTagList()[$this->tag] ?? 'Null';
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
}
