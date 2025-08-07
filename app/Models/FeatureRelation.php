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
        'feature_category_id',
        
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
    public function featureCategory()
    {
        return $this->belongsTo(FeatureCategory::class);
    }
}
