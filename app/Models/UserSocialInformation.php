<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Contracts\Database\Eloquent\Builder;

class UserSocialInformation extends BaseModel
{
    protected $table = 'user_social_informations';
    protected $fillable = [
        'user_urn',
        'instagram',
        'twitter',
        'facebook',
        'youtube',
        'tiktok',
        'spotify',

        'created_by',
        'updated_by',
        'deleted_by',
    ];

    /* =#=#=#=#=#=#=#=#=#=#==#=#=#=#= =#=#=#=#=#=#=#=#=#=#==#=#=#=#=
                Start of RELATIONSHIPS
     =#=#=#=#=#=#=#=#=#=#==#=#=#=#= =#=#=#=#=#=#=#=#=#=#==#=#=#=#= */

    public function user()
    {
        return $this->belongsTo(User::class, 'user_urn', 'urn');
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

    public function scopeSelf(Builder $query): Builder
    {
        return $query->where('user_urn', user()->urn);
    }
}
