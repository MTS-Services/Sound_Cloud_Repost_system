<?php

namespace App\Models;

use App\Models\BaseModel;
use App\Models\Soundcloud;

class UserFollowers extends BaseModel
{
    protected $fillable = [
        'sort_order',
        'user_urn',
        'avatar_url',
        'soundcloud_urn',
        'soundcloud_kind',
        'permalink_url',
        'uri',
        'username',
        'permalink',
        'first_name',
        'last_name',
        'full_name',
    ];

    protected $casts = [
        'id' => 'integer',
        'sort_order' => 'integer',
        'user_urn' => 'integer',
        'avatar_url' => '',
        'soundcloud_id' => 'integer',
        'soundcloud_urn' => 'string',
        'soundcloud_kind' => 'string',
        'permalink_url' => 'string',
        'uri' => 'string',
        'username' => 'string',
        'permalink' => 'string',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
