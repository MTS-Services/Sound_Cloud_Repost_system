<?php

namespace App\Services\Admin\PackageManagement;

use App\Models\Feature;
use App\Models\FeatureRelation;
use App\Models\Order;
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

    // public function createUserPlan(array $data): UserPlan
    // {
    //     return UserPlan::create($data);
    // }

    public function createUserPlan(array $data)
    {
        // Get the order to extract plan information
        $order = Order::find($data['order_id']);

        if (!$order) {
            throw new \Exception('Order not found');
        }

        // Extract plan_id from the order's source relationship
        $planId = $order->source_type === Plan::class ? $order->source_id : null;

        if (!$planId) {
            throw new \Exception('This order is not for a plan subscription');
        }

        return UserPlan::create([
            'user_urn' => $data['user_urn'],
            'plan_id' => $planId, // Use the source_id from order
            'order_id' => $data['order_id'],
            'start_date' => $data['start_date'] ?? now(),
            'end_date' => $data['end_date'] ?? now()->addMonth(),
            'price' => $data['price'],
            'status' => $data['status'] ?? UserPlan::STATUS_PENDING,
            'notes' => $data['notes'] ?? null,
            'duration' => $data['duration'],
            'billing_cycle' => $data['billing_cycle'] ?? 'monthly',
            'auto_renew' => $data['auto_renew'] ?? true,
            'next_billing_date' => $data['next_billing_date'] ?? ($data['end_date'] ?? now()->addMonth()),
            'creater_id' => $data['creater_id'],
            'creater_type' => $data['creater_type'],
        ]);
    }
}
