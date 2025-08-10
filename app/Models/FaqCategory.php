<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Builder;

class FaqCategory extends BaseModel
{
    //

    protected $fillable = [
    'sort_order',
     'name',
     'slug',
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
    return $this->status == self::STATUS_ACTIVE 
        ? 'badge-success' 
        : 'badge-error';
}
    public function getStatusBtnLabelAttribute()
    {
        return $this->status == self::STATUS_ACTIVE ? self::statusList()[self::STATUS_INACTIVE] : self::statusList();
    }

 

    public function faqs()
    {
        return $this->hasMany(Faq::class);
    }

    public function scopeFaqCategoryBy($query, $userId)
    {
        return $query->where('created_by', $userId);
    }

    public function scopeActive(Builder $query) : Builder
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function scopeInactive(Builder $query) : Builder
    {
        return $query->where('status', self::STATUS_INACTIVE);
    }
}
