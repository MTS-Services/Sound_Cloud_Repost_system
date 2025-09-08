<?php

namespace App\Services\Admin\PackageManagement;

use App\Models\Feature;

class FeatureSevice
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function getFeatures($orderBy = 'name', $order = 'asc')
    {
        $features = Feature::orderBy($orderBy, $order)->latest();

        return $features;
    }
    public function getFeature(string $encryptedId)
    {
        return Feature::findOrFail(decrypt($encryptedId));
    }
    public function createFeature(array $data)
    {
        $data['created_by'] = admin()->id;
        $data['key'] = $data['name'];
        $data['name'] = Feature::getFeaturedNames()[$data['name']];
        $feature = Feature::create($data);
        return $feature;
    }
    public function updateFeature(array $data, Feature $feature)
    {
        $data['updated_by'] = admin()->id;
        $data['key'] = $data['name'];
        $data['name'] = Feature::getFeaturedNames()[$data['name']];
        $feature->update($data);
        return $feature;
    }
    public function deleteFeature(string $encryptedId)
    {
        $feature = Feature::where('id', decrypt($encryptedId))->forceDelete();
        return $feature;
    }
    public function toggleStatus(Feature $feature): void
    {
        $feature->update( [
            'status' => !$feature->status,
            'updated_by' => admin()->id
        ]);
    }
}
