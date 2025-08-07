<?php

namespace App\Livewire\User\PackageManagement;

use App\Models\Plan;
use App\Services\Admin\PackageManagement\FeatureCategorySevice;
use App\Services\Admin\PackageManagement\PlanService;
use Livewire\Component;

class Pricing extends Component
{
    public $pricing;
    public $plans;
    public $featureCategories;

    protected PlanService $planService;
    protected FeatureCategorySevice $FeatureCategorySevice;

    public function boot(PlanService $planService, FeatureCategorySevice $FeatureCategorySevice)
    {
        $this->planService = $planService;
        $this->FeatureCategorySevice = $FeatureCategorySevice;
    }

    public function pricing()
    {
        $data['plans'] = $this->planService->getPlans()->with('featureRelations')->get();
        $data['featureCategories'] = $this->FeatureCategorySevice->getFeatureCategories()->get();
        return $data;
    }

    public function mount()
    {
        $this->pricing = $this->pricing();
        $this->plans = $this->pricing['plans'];
        $this->featureCategories = $this->pricing['featureCategories'];
    }

    public function render()
    {
        // dd($this->pricing);
        return view('livewire.user.package-management.pricing');
    }
}
