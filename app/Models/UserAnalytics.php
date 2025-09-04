<?php

namespace App\Models;

use App\Models\BaseModel;

class UserAnalytics extends BaseModel
{
    protected $fillable = [
        'user_urn',
        'response_user_urn',
        'source_id',
        'source_type',
        'date',
        'genre',
        'total_plays',
        'total_followes',
        'total_likes',
        'total_reposts',
        'total_comments',
        'total_views',

        'creater_id',
        'updater_id',
        'deleter_id',
        'creater_type',
        'updater_type',
        'deleter_type',
    ];

    protected $hidden = [
        'creater_id',
        'updater_id',
        'deleter_id',
        'creater_type',
        'updater_type',
        'deleter_type',
    ];

    protected $casts = [
        'total_plays' => 'integer',
        'total_followes' => 'integer',
        'total_likes' => 'integer',
        'total_reposts' => 'integer',
        'total_comments' => 'integer',
        'total_views' => 'integer',
        'creater_id' => 'integer',
        'updater_id' => 'integer',
        'deleter_id' => 'integer',
        'date' => 'date',
    ];

    /* =#=#=#=#=#=#=#=#=#=#==#=#=#=#= =#=#=#=#=#=#=#=#=#=#==#=#=#=#=
                Start of RELATIONSHIPS
     =#=#=#=#=#=#=#=#=#=#==#=#=#=#= =#=#=#=#=#=#=#=#=#=#==#=#=#=#= */

    public function user()
    {
        return $this->belongsTo(User::class, 'user_urn', 'urn');
    }

    public function source()
    {
        return $this->morphTo();
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
