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
        return Plan::find(decrypt($encryptedId));
    }
}
