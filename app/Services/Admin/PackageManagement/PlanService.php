<?php

namespace App\Services\Admin\PackageManagement;

use App\Models\Feature;
use App\Models\FeatureRelation;
use App\Models\Plan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class PlanService
{
    /**
     * Get all plans with sorting.
     */
    public function getPlans(string $orderBy = 'sort_order', string $order = 'asc')
    {
        return Plan::orderBy($orderBy, $order)->latest();
    }

    /**
     * Get a single plan by encrypted ID.
     */
    public function getPlan(string $encryptedId): Plan
    {
        return Plan::findOrFail(decrypt($encryptedId));
    }
    public function getDeletedPlan(string $encryptedId): Plan
    {
        return Plan::onlyTrashed()->findOrFail(decrypt($encryptedId));
    }
    /**
     * Create a new plan.
     */


    public function createPlan(array $data): Plan
    {
        return DB::transaction(function () use ($data) {

            $features = $data['features'] ?? [];
            $featureValues = $data['feature_values'] ?? [];
            $categoryIds = $data['feature_category_ids'] ?? [];

            $data['created_by'] = admin()->id;

            $plan = Plan::create($data);

            foreach ($features as $featureId) {

                FeatureRelation::create([
                    'package_id'          => $plan->id,
                    'package_type'        => Plan::class,
                    'feature_category_id' => $categoryIds[$featureId] ?? null,
                    'feature_id'          => $featureId,
                    'value'               => $featureValues[$featureId] ?? null,
                    'created_by'          => admin()->id,
                ]);
            }


            return $plan;
        });
    }


    /**
     * Update an existing plan.
     */
    public function updatePlan(Plan $plan, array $data): Plan
    {
        return DB::transaction(function () use ($plan, $data) {
            $features = $data['features'] ?? [];
            $featureValues = $data['feature_values'] ?? [];
            $categoryIds = $data['feature_category_ids'] ?? [];

            $data['updated_by'] = admin()->id;

            // Update plan fields
            $plan->update($data);



            // Re-create feature relations
            foreach ($features as $featureId) {
                FeatureRelation::create([
                    'package_id'          => $plan->id,
                    'package_type'        => Plan::class,
                    'feature_id' => $featureId,
                    'feature_category_id' => $categoryIds[$featureId] ?? null,
                    'value' => $featureValues[$featureId] ?? '',
                    'updated_by' => admin()->id,
                ]);
            }

            return $plan;
        });
    }


    /**
     * Soft delete a plan.
     */
    public function delete(Plan $plan): void
    {
        $plan->update(['deleted_by' => admin()->id]);
        $plan->delete();
    }


    /**
     * Restore a soft-deleted plan.
     */
    public function restore(string $encryptedId): void
    {
        $plan = $this->getDeletedPlan($encryptedId);
        $plan->update(['updated_by' => admin()->id]);
        $plan->restore();
    }

    /**
     * Permanently delete a plan.
     */
    public function permanentDelete(string $encryptedId): void
    {
        $plan = $this->getDeletedPlan($encryptedId);
        $plan->forceDelete();
    }

    /**
     * Toggle plan status (active/inactive).
     */
    public function toggleStatus(Plan $plan): void
    {
        $plan->update([
            'status' => !$plan->status,
            'updated_by' => admin()->id,
        ]);
    }
}
