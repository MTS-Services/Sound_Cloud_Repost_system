<?php

namespace App\Services\Admin\PackageManagement;

use App\Models\Feature;
use App\Models\FeatureRelation;
use App\Models\Plan;
use App\Models\UserPlan;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class UserPlanService
{
    public function getUserPlans(): Builder
    {
        return UserPlan::with(['user', 'plan', 'order'])->latest();
    }

    public function getUserPlan(string $user_urn): UserPlan
    {
        return UserPlan::with(['user', 'plan', 'order'])->where('user_urn', $user_urn)->first();
    }
    public function getUserActivePlan(string $user_urn): UserPlan|null
    {
        return UserPlan::with(['user', 'plan', 'order'])->active()->where('user_urn', $user_urn)->first();
    }

    public function createUserPlan(array $data): UserPlan
    {
        return UserPlan::create($data);
    }
}
