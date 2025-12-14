<?php

namespace App\Livewire\User;

use App\Models\Order;
use App\Models\Plan;
use App\Models\User;
use App\Services\Admin\OrderManagement\OrderService;
use App\Services\Admin\PackageManagement\PlanService;
use App\Services\Admin\PackageManagement\UserPlanService;
use App\Services\SoundCloud\SoundCloudService;
use App\Services\User\UserSettingsService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class Plans extends Component
{
    public $isYearly = false;
    public Collection $plans;

    protected PlanService $planService;
    protected OrderService $orderService;
    protected UserPlanService $userPlanService;
    protected UserSettingsService $userSettingsService;
    protected SoundCloudService $soundCloudService;

    public function boot(
        PlanService $planService,
        OrderService $orderService,
        UserPlanService $userPlanService,
        UserSettingsService $userSettingsService,
        SoundCloudService $soundCloudService
    ) {
        $this->planService = $planService;
        $this->orderService = $orderService;
        $this->userPlanService = $userPlanService;
        $this->userSettingsService = $userSettingsService;
        $this->soundCloudService = $soundCloudService;
    }

    public function subscribe(string $plan_id)
    {
        try {
            $order = DB::transaction(function () use ($plan_id) {
                $plan = $this->planService->getPlan($plan_id);
                $this->userSettingsService->createOrUpdate(user()->urn, ['auto_boost' => 1]);

                $data['source_id'] = $plan->id;
                $data['source_type'] = Plan::class;
                $data['type'] = Order::TYPE_PLAN;
                $data['user_urn'] = user()->urn;
                $data['creater_id'] = user()->id;
                $data['creater_type'] = User::class;

                $activeUserPlan = $this->userPlanService->getUserActivePlan(user()->urn);

                if ($activeUserPlan && $activeUserPlan->plan?->monthly_price > $plan->monthly_price) {
                    $this->dispatch('alert', type: 'error', message: 'You have already subscribed to a plan with higher price. Cannot downgrade to a lower price plan.');
                    return null;
                } elseif ($activeUserPlan && $activeUserPlan->plan?->monthly_price < $plan->monthly_price) {
                    // Calculate prorated amount for upgrade
                    $data['amount'] = $this->isYearly
                        ? $plan->yearly_price - $activeUserPlan->plan->yearly_price
                        : $plan->monthly_price - $activeUserPlan->plan->monthly_price;
                    $data['notes'] = "Plan upgrade from " . $activeUserPlan->plan->name . " to " . $plan->name;
                    $data['start_date'] = $activeUserPlan->start_date;
                    $data['end_date'] = $activeUserPlan->end_date;
                    $data['duration'] = $activeUserPlan->duration;
                    $billingCycle = $activeUserPlan->billing_cycle;
                } else {
                    // New subscription
                    $data['amount'] = $this->isYearly ? $plan->yearly_price : $plan->monthly_price;
                    $data['notes'] = "Plan subscription for " . $plan->name;
                    $data['start_date'] = now();

                    if ($this->isYearly) {
                        $data['end_date'] = now()->addYear();
                        $billingCycle = 'yearly';
                    } else {
                        $data['end_date'] = now()->addMonth();
                        $billingCycle = 'monthly';
                    }

                    $data['duration'] = now()->diffInDays($data['end_date']);
                }

                $order = $this->orderService->createOrder($data);

                // Create or update UserPlan
                $data['order_id'] = $order->id;
                $data['price'] = $data['amount'];
                $data['billing_cycle'] = $billingCycle;
                $data['auto_renew'] = true; // Enable auto-renewal by default
                $data['next_billing_date'] = $data['end_date'];

                if ($activeUserPlan && $activeUserPlan->plan?->monthly_price < $plan->monthly_price) {
                    // Update existing plan for upgrade
                    $activeUserPlan->update([
                        'plan_id' => $plan->id,
                        'order_id' => $order->id,
                        'price' => $data['price'],
                        'billing_cycle' => $billingCycle,
                        'status' => \App\Models\UserPlan::STATUS_PENDING,
                        'notes' => $data['notes'],
                    ]);
                } else {
                    // Create new plan
                    $this->userPlanService->createUserPlan($data);
                }

                return $order;
            });

            if ($order) {
                return $this->redirect(
                    route('user.payment.method', encrypt($order->id)),
                    navigate: true
                );
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            $this->dispatch('alert', type: 'error', message: 'An error occurred. Please try again.');
        }
    }

    public function mount()
    {
        $this->plans = $this->planService->getPlans('monthly_price', 'asc')
            ->with('featureRelations.feature')
            ->active()
            ->get();
    }
    public function updated()
    {
        $this->soundCloudService->refreshUserTokenIfNeeded(user());
    }

    public function render()
    {
        return view('livewire.user.plans');
    }
}
