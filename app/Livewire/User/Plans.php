<?php

namespace App\Livewire\User;

use App\Models\Order;
use App\Models\Plan;
use App\Models\User;
use App\Services\Admin\OrderManagement\OrderService;
use App\Services\Admin\PackageManagement\FeatureCategorySevice;
use App\Services\Admin\PackageManagement\PlanService;
use App\Services\Admin\PackageManagement\UserPlanService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class Plans extends Component
{
    public $pricing;
    public $plans;
    public $featureCategories;
    public $yearly_plan = 0;

    protected PlanService $planService;
    protected FeatureCategorySevice $FeatureCategorySevice;
    protected OrderService $orderService;
    protected UserPlanService $userPlanService;

    public function boot(PlanService $planService, FeatureCategorySevice $FeatureCategorySevice, OrderService $orderService, UserPlanService $userPlanService)
    {
        $this->planService = $planService;
        $this->FeatureCategorySevice = $FeatureCategorySevice;
        $this->orderService = $orderService;
        $this->orderService = $orderService;
        $this->userPlanService = $userPlanService;
    }

    public function pricing()
    {
        $data['plans'] = $this->planService->getPlans('monthly_price', 'asc')->with('featureRelations')->get();
        $data['featureCategories'] = $this->FeatureCategorySevice->getFeatureCategories()->with('features')->get();
        return $data;
    }

    public function switchPlan()
    {
        $this->yearly_plan = $this->yearly_plan == 0 ? 1 : 0;
    }

    public function subscribe(string $plan_id)
    {
        try {
            $order = DB::transaction(function () use ($plan_id) {
                $plan = $this->planService->getPlan($plan_id);

                $data['source_id'] = $plan->id;
                $data['source_type'] = Plan::class;
                $data['type'] = Order::TYPE_PLAN;
                $data['user_urn'] = user()->urn;
                $data['creater_id'] = user()->id;
                $data['creater_type'] = User::class;
                $data['plan_id'] = $plan->id;

                $activeUserPlan = $this->userPlanService->getUserActivePlan(user()->urn);

                if ($activeUserPlan && $activeUserPlan->plan?->price > $plan->price) {

                    $this->dispatch('alert', 'error', 'You have already subscribed to a plan with higher price. Cannot upgrade a lower price plan.');

                    return null; // Return null here
                } elseif ($activeUserPlan && $activeUserPlan->plan?->price < $plan->price) {
                    $data['amount'] = $this->yearly_plan == 1
                        ? $plan->yearly_price - $activeUserPlan->plan->yearly_price
                        : $plan->monthly_price - $activeUserPlan->plan->monthly_price;
                    $data['notes'] = "Plan upgrade from " . $activeUserPlan->plan->name . " to " . $plan->name;
                    $data['start_date'] = $activeUserPlan->start_date;
                    $data['end_date'] = $activeUserPlan->end_date;
                    $data['duration'] = $activeUserPlan->duration;
                } else {
                    $data['amount'] = $this->yearly_plan == 1 ? $plan->yearly_price : $plan->monthly_price;
                    $data['notes'] = "Plan subscription for " . $plan->name;
                    $data['start_date'] = now();
                    $data['end_date'] = $this->yearly_plan == 1 ? now()->addYear() : now()->addMonth();
                    $data['duration'] = now()->diffInDays($data['end_date']);
                }

                $order = $this->orderService->createOrder($data);
                $data['order_id'] = $order->id;
                $data['price'] = $data['amount'];
                $this->userPlanService->createUserPlan($data);

                return $order; // Return the order to the outer scope
            });

            if ($order) {
                return $this->redirect(
                    route('user.payment.method', encrypt($order->id)),
                    navigate: true
                );
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            session()->flash('error', $e->getMessage());
        }
    }

    public function mount()
    {
        $this->pricing = $this->pricing();
        $this->plans = $this->pricing['plans'];
        $this->featureCategories = $this->pricing['featureCategories'];
    }

    public function render()
    {

        return view('livewire.user.plans');
    }
}
