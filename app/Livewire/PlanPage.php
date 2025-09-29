<?php

namespace App\Livewire;

use App\Models\Order;
use App\Models\Plan;
use App\Models\User;
use App\Services\Admin\OrderManagement\OrderService;
use App\Services\Admin\PackageManagement\PlanService;
use App\Services\Admin\PackageManagement\UserPlanService;
use App\Services\User\UserSettingsService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('backend.user.layouts.app', ['title' => 'Plan Page', 'page_slug' => 'plan-page'])]
class PlanPage extends Component
{

    public $isYearly = false;
    public Collection $plans;

    protected PlanService $planService;
    protected OrderService $orderService;
    protected UserPlanService $userPlanService;
    protected UserSettingsService $userSettingsService;
    public function boot(
        PlanService $planService,
        OrderService $orderService,
        UserPlanService $userPlanService,
        UserSettingsService $userSettingsService
    ) {
        $this->planService = $planService;
        $this->orderService = $orderService;
        $this->orderService = $orderService;
        $this->userPlanService = $userPlanService;
        $this->userSettingsService = $userSettingsService;
    }

    public function subscribe(string $plan_id)
    {
        try {
            $order = DB::transaction(function () use ($plan_id) {
                $plan = $this->planService->getPlan($plan_id);
                // $this->userSettingsService->createOrUpdate(user()->urn, ['auto_boost' => 1]);

                $data['source_id'] = $plan->id;
                $data['source_type'] = Plan::class;
                $data['type'] = Order::TYPE_PLAN;
                $data['user_urn'] = user()->urn;
                $data['creater_id'] = user()->id;
                $data['creater_type'] = User::class;
                $data['plan_id'] = $plan->id;

                $activeUserPlan = $this->userPlanService->getUserActivePlan(user()->urn);

                if ($activeUserPlan && $activeUserPlan->plan?->price > $plan->price) {

                    $this->dispatch('alert', type: 'error', message: 'You have already subscribed to a plan with higher price. Cannot upgrade a lower price plan.');

                    return null; // Return null here
                } elseif ($activeUserPlan && $activeUserPlan->plan?->price < $plan->price) {
                    $data['amount'] = $this->isYearly
                        ? $plan->yearly_price - $activeUserPlan->plan->yearly_price
                        : $plan->monthly_price - $activeUserPlan->plan->monthly_price;
                    $data['notes'] = "Plan upgrade from " . $activeUserPlan->plan->name . " to " . $plan->name;
                    $data['start_date'] = $activeUserPlan->start_date;
                    $data['end_date'] = $activeUserPlan->end_date;
                    $data['duration'] = $activeUserPlan->duration;
                } else {
                    $data['amount'] = $this->isYearly ? $plan->yearly_price : $plan->monthly_price;
                    $data['notes'] = "Plan subscription for " . $plan->name;
                    $data['start_date'] = now();
                    $data['end_date'] = $this->isYearly ? now()->addYear() : now()->addMonth();
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
        $this->plans = $this->planService->getPlans('monthly_price', 'asc')->with('featureRelations.feature')->active()->get();
    }

    public function render()
    {
        if (Auth::guard('web')->check()) {
            $this->redirect(route('user.dashboard'), true);
        }
        return view('livewire.plan-page');
    }
}
