<?php

namespace App\Services\Admin\PackageManagement;

use App\Models\Plan;

class PlanService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }
    public function getPlans($orderBy = 'name', $order = 'asc')
    {
        return Plan::orderBy($orderBy, $order)->latest();
    }
    public function getPlan(string $encryptedId)
    {
        return Plan::findOrFail(decrypt($encryptedId));
    }
    public function createPlan(array $data)
    {
        $data['created_by'] = admin()->id;
        $plan = Plan::create($data);
        return $plan;
    }
    public function updatePlan(array $data, Plan $plan)
    {
        $data['updated_by'] = admin()->id;
        $plan->update($data);
        return $plan;
    }
    public function deletePlan(string $encryptedId)
    {
        $plan = $this->getPlan($encryptedId);
        $plan['deleted_by'] = admin()->id;
        $plan->delete();
        return $plan;
    }
}
