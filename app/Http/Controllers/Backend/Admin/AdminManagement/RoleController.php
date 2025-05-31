<?php

namespace App\Http\Controllers\Backend\Admin\AdminManagement;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminManagement\RoleRequest;
use App\Services\Admin\AdminManagement\PermissionService;
use App\Services\Admin\AdminManagement\RoleService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class RoleController extends Controller implements HasMiddleware
{
    protected function redirectIndex(): RedirectResponse
    {
        return redirect()->route('am.role.index');
    }

    protected function redirectTrashed(): RedirectResponse
    {
        return redirect()->route('am.role.trash');
    }

    protected RoleService $roleService;
    protected PermissionService $permissionService;

    public function __construct(RoleService $roleService, PermissionService $permissionService)
    {
        $this->roleService = $roleService;
        $this->permissionService = $permissionService;
    }

    public static function middleware(): array
    {
        return [
            'auth:admin', // Applies 'auth:admin' to all methods

            // Permission middlewares using the Middleware class
            new Middleware('permission:role-list', only: ['index']),
            new Middleware('permission:role-details', only: ['show']),
            new Middleware('permission:role-create', only: ['create', 'store']),
            new Middleware('permission:role-edit', only: ['edit', 'update']),
            new Middleware('permission:role-delete', only: ['destroy']),
            new Middleware('permission:role-trash', only: ['trash']),
            new Middleware('permission:role-restore', only: ['restore']),
            new Middleware('permission:role-permanent-delete', only: ['permanentDelete']),
            //add more permissions if needed
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = $this->roleService->getRoles();
            return DataTables::eloquent($query)
                ->editColumn('action', function ($role) {
                    $menuItems = $this->menuItems($role);
                    return view('components.action-buttons', compact('menuItems'))->render();
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('backend.admin.admin-management.role.index');
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
                'routeName' => 'am.role.edit',
                'params' => [encrypt($model->id)],
                'label' => 'Edit',
                'permissions' => ['permission-edit']
            ],

            [
                'routeName' => 'am.role.destroy',
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
        $permissions = $this->permissionService->getPermissions('prefix')->select(['id', 'name', 'prefix'])->get();
        $data['groupedPermissions'] = $permissions->groupBy(function ($permission) {
            return $permission->prefix;
        });
        return view('backend.admin.admin-management.role.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RoleRequest $request)
    {
        try {
            $validated = $request->validated();
            $this->roleService->createRole($validated);
            session()->flash('success', "Role created successfully");
        } catch (\Throwable $e) {
            session()->flash('Role creation failed');
            throw $e;
        }
        return $this->redirectIndex();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id) {}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = [];
        if (decrypt($id) == 1) {
            session()->flash('error', 'Cannot edit Super Admin!');
            return $this->redirectIndex();
        }
        try {
            $role = $this->roleService->getRole($id);
            $data['role'] = $role->load(['permissions:id,name,prefix']);
            $data['permissions'] = $this->permissionService->getPermissions('prefix')->select(['id', 'name', 'prefix'])->get();
            $data['groupedPermissions'] = $data['permissions']->groupBy('prefix');
        } catch (\Throwable $e) {
            session()->flash('error', 'Something went wrong, please try again!');
            throw $e;
        }
        return view('backend.admin.admin-management.role.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RoleRequest $request, string $id)
    {
        if (decrypt($id) == 1) {
            session()->flash('error', 'Cannot update Super Admin!');
            return $this->redirectIndex();
        }
        try {
            $role = $this->roleService->getRole($id);
            $this->roleService->updateRole($role, $request->validated());
            session()->flash('success', 'Role updated successfully!');
        } catch (\Throwable $e) {
            session()->flash('error', 'Role update failed!');
            throw $e;
        }
        return $this->redirectIndex();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (decrypt($id) == 1) {
            session()->flash('error', 'Cannot delete Super Admin!');
            return $this->redirectIndex();
        }
        try {
            $this->roleService->delete($id);
            session()->flash('success', 'Role deleted successfully!');
        } catch (\Throwable $e) {
            session()->flash('Role delete failed');
            throw $e;
        }
        return $this->redirectIndex();
    }

    public function trash(Request $request)
    {
        if ($request->ajax()) {
            $query = $this->roleService->getRoles()->onlyTrashed();
            return DataTables::eloquent($query)
                ->editColumn('action', function ($permission) {
                    $menuItems = $this->trashedMenuItems($permission);
                    return view('components.action-buttons', compact('menuItems'))->render();
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('backend.admin.admin-management.role.trash');
    }

    protected function trashedMenuItems($model): array
    {
        return [
            [
                'routeName' => 'am.role.restore',
                'params' => [encrypt($model->id)],
                'label' => 'Restore',
                'permissions' => ['role-restore']
            ],
            [
                'routeName' => 'am.role.permanent-delete',
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
            $this->roleService->restore($id);
            session()->flash('success', "Role restored successfully");
        } catch (\Throwable $e) {
            session()->flash('Role restore failed');
            throw $e;
        }
        return $this->redirectTrashed();
    }

    public function permanentDelete(string $id): RedirectResponse
    {
        try {
            $this->roleService->permanentDelete($id);
            session()->flash('success', "Role permanently deleted successfully");
        } catch (\Throwable $e) {
            session()->flash('Role permanent delete failed');
            throw $e;
        }
        return $this->redirectTrashed();
    }
}
