<?php

namespace App\Models;

use App\Models\BaseModel;

class FeatureRelation extends BaseModel
{
    protected $fillable = [
        'package_id',
        'package_type',
        'feature_id',
        'value',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    public function package()
    {
        return $this->morphTo();
    }

    public function feature()
    {
        return $this->belongsTo(Feature::class);
    }
}
