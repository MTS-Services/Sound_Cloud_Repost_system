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

    public const CAMPAIGN_KEY = 'Campaign';
    public const REPOST_KEY = 'Repost';
    public const DRECT_REPOST_KEY = 'Direct Repost';


}
