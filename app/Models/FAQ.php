<?php

namespace App\Models;

use App\Models\BaseModel;

class Faq extends BaseModel
{
    //


    protected $fillable = [
        'sort_order',
        'question',
        'description',
        'key',
        'status',

        'created_by',
        'updated_by',
        'deleted_by',
    ];

    /* =#=#=#=#=#=#=#=#=#=#==#=#=#=#= =#=#=#=#=#=#=#=#=#=#==#=#=#=#=
                Start of RELATIONSHIPS
     =#=#=#=#=#=#=#=#=#=#==#=#=#=#= =#=#=#=#=#=#=#=#=#=#==#=#=#=#= */

    //

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
        return $this->status == self::STATUS_ACTIVE ? 'badge-success' : '';
    }

    public function getStatusBtnLabelAttribute()
    {
        return $this->status == self::STATUS_ACTIVE ? self::statusList()[self::STATUS_INACTIVE] : self::statusList();
    }

    public function getStatusBtnColorAttribute()
    {
        return $this->status == self::STATUS_ACTIVE ? 'btn-error' : 'btn-success';
    }
    public function getStatusBtnClassAttribute()
    {
        return $this->status == self::STATUS_INACTIVE ? 'btn-error' : 'btn-primary';
    }

    public const KEY_CAMPAIGN = 0;
    public const KEY_REPOST = 1;
    public const KEY_DIRECT_REPOST = 2;

    public static function keyLists()
    {
        return [
            self::KEY_CAMPAIGN => "Campaign",
            self::KEY_REPOST => "Repost",
            self::KEY_DIRECT_REPOST => "Direct Repost",
        ];
    }



    public function scopeFaqBy($query, $userId)
    {
        return $query->where('created_by', $userId);
    }
}
