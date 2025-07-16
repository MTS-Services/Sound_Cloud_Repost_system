<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends BaseModel
{
    protected $fillable = [
        'sort_order',
        'product_id',
        'name',

        'creater_id',
        'updater_id',
        'deleter_id',
        'creater_type',
        'updater_type',
        'deleter_type',
    ];

    protected $casts = [
        'sort_order' => 'integer',

        'creater_id' => 'integer',
        'updater_id' => 'integer',
        'deleter_id' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
        'creater_type' => 'string',
        'updater_type' => 'string',
        'deleter_type' => 'string',
    ];

    /* %%%%%%%%% ######## ******** ======== RELATIONSHIPS  ======== ******** ######## %%%%%%%%% */

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }
}
