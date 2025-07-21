<?php

namespace App\Services\Admin\PackageManagement;

use App\Models\FeatureCategory;

class FeatureCategorySevice
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function getFeatureCategories($orderBy = 'name', $order = 'asc')
    {
        $feature_categories = FeatureCategory::orderBy($orderBy, $order)->latest();

        return $feature_categories;
    }
    public function getFeatureCategory(string $encryptedId)
    {
        return FeatureCategory::where('id', decrypt($encryptedId))->first();
    }
    public function createFeatureCategory(array $data)
    {
        $data['created_by'] = admin()->id;
        $feature_categories = FeatureCategory::create($data);
        return $feature_categories;
    }
    public function updateFeatureCategory(array $data, FeatureCategory $featureCategory): FeatureCategory
    {
        $data['updated_by'] = admin()->id;
        $featureCategory->update($data);
        return $featureCategory;
    }

    public function deleteFeatureCategory(string $encryptedId)
    {
        $feature_categories = FeatureCategory::where('id', decrypt($encryptedId))->forceDelete();
        return $feature_categories;
    }
}
