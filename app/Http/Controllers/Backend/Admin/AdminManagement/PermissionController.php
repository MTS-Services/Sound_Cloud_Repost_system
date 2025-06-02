<?php

namespace App\Http\Controllers\Backend\Admin\AdminManagement;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminManagement\PermissionRequest;
use App\Http\Traits\AuditRelationTraits;
use App\Services\Admin\AdminManagement\PermissionService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class PermissionController extends Controller implements HasMiddleware
{

    use AuditRelationTraits;

    protected function redirectIndex(): RedirectResponse
    {
        return redirect()->route('am.permission.index');
    }

    protected function redirectTrashed(): RedirectResponse
    {
        return redirect()->route('am.permission.trash');
    }

    protected PermissionService $permissionService;

    public function __construct(PermissionService $permissionService)
    {
        $this->permissionService = $permissionService;
    }

    public static function middleware(): array
    {
        return [
            'auth:admin', // Applies 'auth:admin' to all methods

            // Permission middlewares using the Middleware class
            new Middleware('permission:permisison-list', only: ['index']),
            new Middleware('permission:permisison-details', only: ['show']),
            new Middleware('permission:permisison-create', only: ['create', 'store']),
            new Middleware('permission:permisison-edit', only: ['edit', 'update']),
            new Middleware('permission:permisison-delete', only: ['destroy']),
            new Middleware('permission:permisison-trash', only: ['trash']),
            new Middleware('permission:permisison-restore', only: ['restore']),
            new Middleware('permission:permisison-permanent-delete', only: ['permanentDelete']),
            //add more permissions if needed
        ];
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = $this->permissionService->getPermissions();
            return DataTables::eloquent($query)
                ->editColumn('created_by', function ($permission) {
                    return $this->creater_name($permission);
                })
                ->editColumn('created_at', function ($permission) {
                    return $permission->created_at_formatted;
                })
                ->editColumn('action', function ($permission) {
                    $menuItems = $this->menuItems($permission);
                    return view('components.action-buttons', compact('menuItems'))->render();
                })
                ->rawColumns(['created_by', 'created_at', 'action'])
                ->make(true);
        }
        return view('backend.admin.admin-management.permission.index');
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
                'routeName' => 'am.permission.edit',
                'params' => [encrypt($model->id)],
                'label' => 'Edit',
                'permissions' => ['permission-edit']
            ],

            [
                'routeName' => 'am.permission.destroy',
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
    public function create()
    {
        return view('backend.admin.admin-management.permission.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PermissionRequest $request)
    {
        try {
            $validated = $request->validated();
            $this->permissionService->createPermission($validated);
            session()->flash('success', "Permission created successfully");
        } catch (\Throwable $e) {
            session()->flash('Permission creation failed');
            throw $e;
        }
        return $this->redirectIndex();
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        $data = $this->permissionService->getPermission($id);
        $data['creater_name'] = $this->creater_name($data);
        $data['updater_name'] = $this->updater_name($data);
        return response()->json($data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data['permission'] = $this->permissionService->getPermission($id);
        return view('backend.admin.admin-management.permission.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PermissionRequest $request, string $id)
    {
        try {
            $permission = $this->permissionService->getPermission($id);
            $validated = $request->validated();
            $this->permissionService->updatePermission($permission, $validated);
            session()->flash('success', "Permission updated successfully");
        } catch (\Throwable $e) {
            session()->flash('Permission update failed');
            throw $e;
        }
        return $this->redirectIndex();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $permission = $this->permissionService->delete($id);
            session()->flash('success', "Permission deleted successfully");
        } catch (\Throwable $e) {
            session()->flash('Permission delete failed');
            throw $e;
        }
        return $this->redirectIndex();
    }

    public function trash(Request $request)
    {

        if ($request->ajax()) {
            $query = $this->permissionService->getPermissions()->onlyTrashed();
            return DataTables::eloquent($query)
                ->editColumn('action', function ($permission) {
                    $menuItems = $this->trashedMenuItems($permission);
                    return view('components.action-buttons', compact('menuItems'))->render();
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('backend.admin.admin-management.permission.trash');
    }

    protected function trashedMenuItems($model): array
    {
        return [
            [
                'routeName' => 'am.permission.restore',
                'params' => [encrypt($model->id)],
                'label' => 'Restore',
                'permissions' => ['role-restore']
            ],
            [
                'routeName' => 'am.permission.permanent-delete',
                'params' => [encrypt($model->id)],
                'label' => 'Permanent Delete',
                'p-delete' => true,
                'permissions' => ['role-permanent-delete']
            ]

        ];
    }

    public function restore(string $id): RedirectResponse
    {
        try {
            $this->permissionService->restore($id);
            session()->flash('success', "Permission restored successfully");
        } catch (\Throwable $e) {
            session()->flash('Permission restore failed');
            throw $e;
        }
        return $this->redirectTrashed();
    }

    public function permanentDelete(string $id): RedirectResponse
    {
        try {
            $this->permissionService->permanentDelete($id);
            session()->flash('success', "Permission permanently deleted successfully");
        } catch (\Throwable $e) {
            session()->flash('Permission permanent delete failed');
            throw $e;
        }
        return $this->redirectTrashed();
    }
}
