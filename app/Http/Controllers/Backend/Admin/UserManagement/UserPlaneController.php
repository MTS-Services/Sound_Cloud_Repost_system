<?php

namespace App\Http\Controllers\Backend\Admin\UserManagement;

use App\Http\Controllers\Controller;
use App\Http\Traits\AuditRelationTraits;
use App\Models\User;
use App\Models\UserPlan;
use App\Services\Admin\UserManagement\UserPlanService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Yajra\DataTables\Facades\DataTables;


class UserPlaneController extends Controller implements HasMiddleware
{
    use AuditRelationTraits;
      protected UserPlanService $userPlanService;

    public function __construct(UserPlanService $userPlanService)
    {
        $this->userPlanService = $userPlanService;
    }

    protected function redirectIndex(): RedirectResponse
    {
        return redirect()->route('um.user-plan.index');
    }

    protected function redirectTrashed(): RedirectResponse
    {
        return redirect()->route('um.user-plan.trash');
    }

  
    
    public static function middleware(): array
    {
        return [
            'auth:admin', // Applies 'auth:admin' to all methods

            // Permission middlewares using the Middleware class
            new Middleware('permission:user-plane-list', only: ['index']),
            new Middleware('permission:user-plane-details', only: ['show']),
            new Middleware('permission:user-plane-create', only: ['create', 'store']),
            new Middleware('permission:user-plane-delete', only: ['destroy']),
            new Middleware('permission:user-plane-status', only: ['status']),
 
            //add more permissions if needed
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
          

        if ($request->ajax()) {
          $query = $this->userPlanService->getUserPlans()->with(['user', 'plan', 'order']);
            return DataTables::eloquent($query)
            ->editColumn('status', function ($userPlan) {
                return "<span class='badge badge-soft {$userPlan->status_color}'>{$userPlan->status_label}</span>";
            })
                 ->editColumn('created_by', function ($userPlan) {
                    return $this->creater_name($userPlan);
                })
                ->editColumn('created_at', function ($userPlan) {
                    return $userPlan->created_at_formatted;
                })
                ->editColumn('start_date', function ($userPlan) {
                    return $userPlan->start_date_formatted ?? 'N/A';
                })
                ->editColumn('end_date', function ($userPlan) {
                    return $userPlan->end_date_formatted ?? 'N/A';
                })
                ->editColumn('action', function ($service) {
                    $menuItems = $this->menuItems($service);
                    return view('components.action-buttons', compact('menuItems'))->render();
                })
                ->rawColumns(['status','created_by', 'created_at', 'action'])
                ->make(true);
        }
        return view('backend.admin.user-management.user-plane.index');
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
                'routeName' => 'um.user-plan.status',
                'params' => [encrypt($model->id)],
                'label' => $model->status ? 'Deactivate' : 'Activate',
                'permissions' => ['userPlane-status']
            ],
            [
                'routeName' => '',
                'params' => [encrypt($model->id)],
                'label' => 'Edit',
                'permissions' => ['permission-edit']
            ],

            [
                'routeName' => 'um.user-plan.destroy',
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
        //
        return view('view file url ...');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         try {
            // $validated = $request->validated();
            //
            session()->flash('success', "Service created successfully");
        } catch (\Throwable $e) {
            session()->flash('Service creation failed');
            throw $e;
        }
        return $this->redirectIndex();
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        $data = $this->userPlanService->getUserPlan($id)->load(['user', 'plan', 'order']);
        $data['user_name'] = $data->user->name;
        $data['order_number'] = $data->order->order_id;
        $data['plan_name'] = $data->plan->name;
        $data['creater_name'] = $this->creater_name($data);
        $data['updater_name'] = $this->updater_name($data);
        return response()->json($data);
    }

    public function status(Request $request, string $id)
    {
         $user_plan = UserPlan::findOrFail(decrypt($id));
        $user_plan->update(['status' => !$user_plan->status, 'updated_by' => admin()->id]);
        session()->flash('success', 'User plan status updated successfully!');
        return redirect()->route('um.user-plan.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    // public function edit(string $id): View
    // {
    //     //$data['service'] = $this->serviceName->getService($id);
    //     return view('view file url...', $data);
    // }

    /**
     * Update the specified resource in storage.
     */
    // public function update(Request $request, string $id)
    // {
    //      try {
    //         // $validated = $request->validated();
    //         //
    //         session()->flash('success', "Service updated successfully");
    //     } catch (\Throwable $e) {
    //         session()->flash('Service update failed');
    //         throw $e;
    //     }
    //     return $this->redirectIndex();
    // }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
         try {
            $user_plan = UserPlan::findOrFail(decrypt($id));
            $user_plan->update(['deleted_by' => admin()->id]);
            $user_plan->delete();
            session()->flash('success', "User plan deleted successfully");
        } catch (\Throwable $e) {
            session()->flash('User plan delete failed');
            throw $e;
        }
        return $this->redirectIndex();
    }

    // public function trash(Request $request)
    // {
    //     if ($request->ajax()) {
    //         $query = $this->serviceName->getPermissions()->onlyTrashed();
    //         return DataTables::eloquent($query)
    //             ->editColumn('deleted_by', function ($admin) {
    //                 return $this->deleter_name($admin);
    //             })
    //             ->editColumn('deleted_at', function ($admin) {
    //                 return $admin->deleted_at_formatted;
    //             })
    //             ->editColumn('action', function ($permission) {
    //                 $menuItems = $this->trashedMenuItems($permission);
    //                 return view('components.action-buttons', compact('menuItems'))->render();
    //             })
    //             ->rawColumns(['deleted_by', 'deleted_at', 'action'])
    //             ->make(true);
    //     }
    //     return view('view blade file url...');
    // }

    // protected function trashedMenuItems($model): array
    // {
    //     return [
    //         [
    //             'routeName' => '',
    //             'params' => [encrypt($model->id)],
    //             'label' => 'Restore',
    //             'permissions' => ['permission-restore']
    //         ],
    //         [
    //             'routeName' => '',
    //             'params' => [encrypt($model->id)],
    //             'label' => 'Permanent Delete',
    //             'p-delete' => true,
    //             'permissions' => ['permission-permanent-delete']
    //         ]

    //     ];
    // }

    //  public function restore(string $id): RedirectResponse
    // {
    //     try {
    //         $this->serviceName->restore($id);
    //         session()->flash('success', "Service restored successfully");
    //     } catch (\Throwable $e) {
    //         session()->flash('Service restore failed');
    //         throw $e;
    //     }
    //     return $this->redirectTrashed();
    // }

    // public function permanentDelete(string $id): RedirectResponse
    // {
    //     try {
    //         $this->serviceName->permanentDelete($id);
    //         session()->flash('success', "Service permanently deleted successfully");
    //     } catch (\Throwable $e) {
    //         session()->flash('Service permanent delete failed');
    //         throw $e;
    //     }
    //     return $this->redirectTrashed();
    // }
}
