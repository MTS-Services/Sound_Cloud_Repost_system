<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Builder;

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
            'status_label',
            'status_color',
            'status_btn_label',
            'status_btn_color',
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
        return $this->status == self::STATUS_ACTIVE
            ? 'badge-success'
            : 'badge-error';
    }

    public function getStatusBtnLabelAttribute()
    {
        return $this->status == self::STATUS_ACTIVE
            ? self::statusList()[self::STATUS_INACTIVE]
            : self::statusList()[self::STATUS_ACTIVE];
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


    public function scopeFaqBy($query, $userId)
    {
        return $query->where('created_by', $userId);
    }

    public function faqCategory()
    {
        return $this->belongsTo(FaqCategory::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }
    public function scopeInactive(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_INACTIVE);
    }
}
