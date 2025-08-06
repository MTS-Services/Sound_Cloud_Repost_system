<?php

namespace App\Http\Controllers\Backend\Admin\PackageManagement;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PackageManagement\PlanRequest;
use App\Http\Traits\AuditRelationTraits;
use App\Models\FeatureCategory;
use App\Models\Plan;
use App\Services\Admin\PackageManagement\FeatureCategorySevice;
use App\Services\Admin\PackageManagement\PlanService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Yajra\DataTables\Facades\DataTables;

class PlanController extends Controller implements HasMiddleware
{
    use AuditRelationTraits;

    protected function redirectIndex(): RedirectResponse
    {
        return redirect()->route('pm.plan.index');
    }

    protected function redirectTrashed(): RedirectResponse
    {
        return redirect()->route('pm.plan.trash');
    }

    protected PlanService $planService;
    protected FeatureCategorySevice $featureCategoryService;

    public function __construct(PlanService $planService, FeatureCategorySevice $featureCategoryService)
    {
        $this->planService = $planService;
        $this->featureCategoryService = $featureCategoryService;
    }

    public static function middleware(): array
    {
        return [
            'auth:admin', // Applies 'auth:admin' to all methods

            // Permission middlewares using the Middleware class
            new Middleware('permission:plan-list', only: ['index']),
            new Middleware('permission:plan-create', only: ['create', 'store']),
            new Middleware('permission:plan-edit', only: ['edit', 'update']),
            new Middleware('permission:plan-delete', only: ['destroy']),
            new Middleware('permission:plan-status', only: ['status']),
            new Middleware('permission:plan-trash', only: ['trash']),
            new Middleware('permission:plan-restore', only: ['restore']),
            new Middleware('permission:plan-permanent-delete', only: ['permanentDelete']),
            //add more permissions if needed
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = $this->planService->getPlans();
            return DataTables::eloquent($query)
                ->editColumn('status', fn($user) => "<span class='badge badge-soft {$user->status_color}'>{$user->status_label}</span>")
                ->editColumn('tag', function ($feature) {
                    return $feature->tag_label;
                })
                ->editColumn('created_by', function ($feature) {
                    return $this->creater_name($feature);
                })
                ->editColumn('created_at', function ($feature) {
                    return $feature->created_at_formatted;
                })
                ->editColumn('action', function ($feature) {
                    $menuItems = $this->menuItems($feature);
                    return view('components.action-buttons', compact('menuItems'))->render();
                })
                ->rawColumns(['action', 'created_by', 'created_at', 'status'])
                ->make(true);
        }
        return view('backend.admin.package_management.plans.index');
    }


    protected function menuItems($model): array
    {
        return [
            [
                'routeName' => 'javascript:void(0)',
                'data-id' => encrypt($model->id),
                'className' => 'view',
                'label' => 'Details',
                'permissions' => ['permission-list', 'permission-delete', 'permission-status']
            ],
            [
                'routeName' => 'pm.plan.edit',
                'params' => [encrypt($model->id)],
                'label' => 'Edit',
                'permissions' => ['permission-edit']
            ],
            [
                'routeName' => 'pm.plan.status',
                'params' => [encrypt($model->id)],
                'label' => $model->status_btn_label,
                'permissions' => ['permission-status']
            ],
            [
                'routeName' => 'pm.plan.destroy',
                'params' => [encrypt($model->id)],
                'label' => 'Delete',
                'delete' => true,
                'permissions' => ['permission-delete']
            ]

        ];
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $data['feature_categories'] = $this->featureCategoryService->getFeatureCategories()->with('features')->get();
        return view('backend.admin.package_management.plans.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PlanRequest $request)
    {


        try {
            $validated = $request->validated();
            $this->planService->createPlan($validated);
            session()->flash('success', "Plan created successfully");
        } catch (\Throwable $e) {
            session()->flash('Plan creation failed');
            throw $e;
        }
        return $this->redirectIndex();
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        $data = $this->planService->getPlan($id);
        $data['creater_name'] = $this->creater_name($data);
        $data['updater_name'] = $this->updater_name($data);
        return response()->json($data);
    }

    // /**
    //  * Show the form for editing the specified resource.
    //  */
    public function edit(string $encryptedId): View
    {
        $data['plan'] = $this->planService->getPlan($encryptedId);

        $data['featureCategories'] = $this->featureCategoryService->getFeatureCategories()->with('features')->get();
        $data['planFeatures'] = $data['plan']->features->pluck('pivot.value', 'id')->toArray();
        $data['planFeatureCategoryIds'] = $data['plan']->features->pluck('pivot.feature_category_id', 'id')->toArray();

        return view('backend.admin.package_management.plans.edit', $data);
    }

    // /**
    //  * Update the specified resource in storage.
    //  */
    public function update(PlanRequest $request, string $encryptedId)
    {
        try {
            $plan = $this->planService->getPlan($encryptedId);

            $validated = $request->validated();
            $this->planService->updatePlan($plan, $validated);
            session()->flash('success', "Plan updated successfully");
        } catch (\Throwable $e) {
            session()->flash('Plan update failed');
            throw $e;
        }
        return $this->redirectIndex();
    }

    // /**
    //  * Remove the specified resource from storage.
    //  */
    public function destroy(string $id)
    {
        try {
            $plan = $this->planService->getPlan($id);
            $this->planService->delete($plan);
            session()->flash('success', "Plan deleted successfully");
        } catch (\Throwable $e) {
            session()->flash('Plan delete failed');
            throw $e;
        }
        return $this->redirectIndex();
    }

    public function trash(Request $request)
    {
        if ($request->ajax()) {
            $query = $this->planService->getPlans()->onlyTrashed();
            return DataTables::eloquent($query)
                ->editColumn('status', fn($user) => "<span class='badge badge-soft {$user->status_color}'>{$user->status_label}</span>")
                ->editColumn('tag', function ($feature) {
                    return $feature->tag_label;
                })
                ->editColumn('deleted_by', function ($plan) {
                    return $this->deleter_name($plan);
                })
                ->editColumn('deleted_at', function ($plan) {
                    return $plan->deleted_at_formatted;
                })
                ->editColumn('action', function ($permission) {
                    $menuItems = $this->trashedMenuItems($permission);
                    return view('components.action-buttons', compact('menuItems'))->render();
                })
                ->rawColumns(['status','tag','deleted_by', 'deleted_at', 'action'])
                ->make(true);
        }
        return view('backend.admin.package_management.plans.trash');
    }

    protected function trashedMenuItems($model): array
    {
        return [
            [
                'routeName' => 'pm.plan.restore',
                'params' => [encrypt($model->id)],
                'label' => 'Restore',
                'permissions' => ['permission-restore']
            ],
            [
                'routeName' => 'pm.plan.permanent-delete',
                'params' => [encrypt($model->id)],
                'label' => 'Permanent Delete',
                'p-delete' => true,
                'permissions' => ['permission-permanent-delete']
            ]

        ];
    }

    public function restore(string $id): RedirectResponse
    {
        try {
            $this->planService->restore($id);
            session()->flash('success', "Plan restored successfully");
        } catch (\Throwable $e) {
            session()->flash('Plan restore failed');
            throw $e;
        }
        return $this->redirectTrashed();
    }

    public function permanentDelete(string $id): RedirectResponse
    {
        try {
            $this->planService->permanentDelete($id);
            session()->flash('success', "Plan permanently deleted successfully");
        } catch (\Throwable $e) {
            session()->flash('Plan permanent delete failed');
            throw $e;
        }
        return $this->redirectTrashed();
    }
    public function status(string $id): RedirectResponse
    {
        $data = $this->planService->getPlan($id);
        $this->planService->toggleStatus($data);
        session()->flash('success', 'Plan status updated successfully!');
        return $this->redirectIndex();
    }
}
