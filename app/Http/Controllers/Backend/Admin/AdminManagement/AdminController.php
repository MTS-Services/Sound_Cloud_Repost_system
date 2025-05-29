<?php

namespace App\Http\Controllers\Backend\Admin\AdminManagement;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Admin\AdminManagement\AdminRequest;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Yajra\DataTables\Facades\DataTables;

class AdminController extends Controller implements HasMiddleware
{

    public static function middleware(): array
    {
        return [
            'auth:admin', // Applies 'auth:admin' to all methods

            // Permission middlewares using the Middleware class
            // new Middleware('permission:admin-list', only: ['index']),
            // new Middleware('permission:admin-details', only: ['show']),
            // new Middleware('permission:admin-create', only: ['create', 'store']),
            // new Middleware('permission:admin-edit', only: ['edit', 'update']),
            // new Middleware('permission:admin-delete', only: ['destroy']),
            // new Middleware('permission:admin-status', only: ['status']),
            // new Middleware('permission:admin-recycle-bin', only: ['recycleBin']),
            // new Middleware('permission:admin-restore', only: ['restore']),
            // new Middleware('permission:admin-permanent-delete', only: ['permanentDelete']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Admin::orderBy('name', 'asc')
            ->latest();
        if ($request->ajax()) {
            return DataTables::eloquent($query)
                ->editColumn('action', function ($admin) {
                    $menuItems = $this->menuItems($admin);
                    return view('components.action-buttons', compact('menuItems'))->render();
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('backend.admin.admin-management.admin.index');
    }

    protected function menuItems($model): array
    {
        return [
            [
                'routeName' => 'javascript:void(0)',
                'data-id' => encrypt($model->id),
                'className' => 'view',
                'label' => 'Details',
                'permissions' => ['admin-list', 'admin-delete', 'admin-status']
            ],
            [
                'routeName' => 'am.admin.edit',
                'params' => [encrypt($model->id)],
                'label' => 'Edit',
                'permissions' => ['admin-edit']
            ],

            [
                'routeName' => 'am.admin.destroy',
                'params' => [encrypt($model->id)],
                'label' => 'Delete',
                'delete' => true,
                'permissions' => ['admin-delete']
            ]

        ];
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.admin.admin-management.admin.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AdminRequest $request)
    {
        $validated = $request->validated();
        Admin::create($validated);
        session()->flash('success', 'Admin created successfully');
        return redirect()->route('am.admin.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $admin = Admin::findOrFail(decrypt($id));
        return view('backend.admin.admin-management.admin.edit', compact('admin'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AdminRequest $request, string $id)
    {
        $admin = Admin::findOrFail(decrypt($id));
        $validated = $request->validated();
        $validated['password'] = isset($validated['password']) ? $validated['password'] : $admin->password;
        $admin->update($validated);
        session()->flash('success', 'Admin updated successfully');
        return redirect()->route('am.admin.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $admin = Admin::findOrFail(decrypt($id));
        $admin->delete();
        session()->flash('success', 'Admin deleted successfully');
        return redirect()->route('am.admin.index');
    }

    public function trash(Request $request)
    {
        if ($request->ajax()) {
            $query = Admin::orderBy('name', 'asc')->onlyTrashed();
            return DataTables::eloquent($query)
                ->editColumn('action', function ($admin) {
                    $menuItems = $this->trashedMenuItems($admin);
                    return view('components.action-buttons', compact('menuItems'))->render();
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('backend.admin.admin-management.admin.trash');
    }

    protected function trashedMenuItems($model): array
    {
        return [
            [
                'routeName' => 'am.admin.restore',
                'params' => [encrypt($model->id)],
                'label' => 'Restore',
                'permissions' => ['admin-restore']
            ],
            [
                'routeName' => 'am.admin.permanent-delete',
                'params' => [encrypt($model->id)],
                'label' => 'Permanent Delete',
                'p-delete' => true,
                'permissions' => ['admin-permanent-delete']
            ]

        ];
    }

    public function restore(string $id)
    {
        $admin = Admin::onlyTrashed()->findOrFail(decrypt($id));
        $admin->restore();
        session()->flash('success', 'Admin restored successfully');
        return redirect()->route('am.admin.trash');
    }

    public function permanentDelete(string $id)
    {
        $admin = Admin::onlyTrashed()->findOrFail(decrypt($id));
        $admin->forceDelete();
        session()->flash('success', 'Admin permanently deleted successfully');
        return redirect()->route('am.admin.trash');
    }
}
