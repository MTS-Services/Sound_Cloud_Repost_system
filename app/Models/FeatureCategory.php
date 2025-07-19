<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class FeatureCategory extends BaseModel
{
    use SoftDeletes;

    protected $fillable = [
        'sort_order',
        'name',

        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function features():HasMany
    {
        return $this->hasMany(Feature::class);
    }
}
