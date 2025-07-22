<?php

namespace App\Http\Controllers\Backend\Admin\UserManagement;

use App\Http\Controllers\Controller;
use App\Http\Traits\AuditRelationTraits;
use App\Services\Admin\Usermanagement\UserPlaylistService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Yajra\DataTables\Facades\DataTables;

class UserPlaylistController extends Controller implements HasMiddleware
{
    use AuditRelationTraits;

    protected function redirectIndex(): RedirectResponse
    {
        return redirect()->route('um.playlist.index');
    }

    protected function redirectTrashed(): RedirectResponse
    {
        return redirect()->route('um.playlist.trash');
    }

    protected UserPlaylistService $userPlaylistService;

    public function __construct(UserPlaylistService $userPlaylistService)
    {
        $this->userPlaylistService = $userPlaylistService;
    }

    public static function middleware(): array
    {

        return [
            'auth:admin', // Applies 'auth:admin' to all methods

            // Permission middlewares using the Middleware class
            new Middleware('permission:permission-list', only: ['index']),
            new Middleware('permission:permission-details', only: ['show']),
            new Middleware('permission:permission-create', only: ['create', 'store']),
            new Middleware('permission:permission-edit', only: ['edit', 'update']),
            new Middleware('permission:permission-delete', only: ['destroy']),
            new Middleware('permission:permission-trash', only: ['trash']),
            new Middleware('permission:permission-restore', only: ['restore']),
            new Middleware('permission:permission-permanent-delete', only: ['permanentDelete']),
            //add more permissions if needed
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        if ($request->ajax()) {
            $query = $this->userPlaylistService->getUserPlaylists();
            return DataTables::eloquent($query)
                ->editColumn('user_urn', function ($playlist) {
                    return $playlist->user?->name;
                })
                ->editColumn('creater_id', fn($user) => $this->creater_name($user))
                ->editColumn('created_at', fn($user) => $user->created_at_formatted)
                ->editColumn('action', function ($playlist) {
                    $menuItems = $this->menuItems($playlist);
                    return view('components.action-buttons', compact('menuItems'))->render();
                })
                ->rawColumns(['action', 'creater_id', 'created_at', 'user_urn'])
                ->make(true);
        }
        return view('backend.admin.user-management.playlist.index');
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
                'routeName' => 'um.playlist.destroy',
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
        $data = $this->userPlaylistService->getUserPlaylist($id);
        $data['creater_name'] = $this->creater_name($data);
        $data['updater_name'] = $this->updater_name($data);
        return response()->json($data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): View
    {
        $data['service'] = $this->userPlaylistService->getUserPlaylist($id);
        return view('view file url...', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            // $validated = $request->validated();
            //
            session()->flash('success', "Service updated successfully");
        } catch (\Throwable $e) {
            session()->flash('Service update failed');
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
            //
            session()->flash('success', "Service deleted successfully");
        } catch (\Throwable $e) {
            session()->flash('Service delete failed');
            throw $e;
        }
        return $this->redirectIndex();
    }

    public function trash(Request $request)
    {
        if ($request->ajax()) {
            $query = $this->userPlaylistService->getUserPlaylists()->onlyTrashed();
            return DataTables::eloquent($query)
                ->editColumn('deleted_by', function ($admin) {
                    return $this->deleter_name($admin);
                })
                ->editColumn('deleted_at', function ($admin) {
                    return $admin->deleted_at_formatted;
                })
                ->editColumn('action', function ($permission) {
                    $menuItems = $this->trashedMenuItems($permission);
                    return view('components.action-buttons', compact('menuItems'))->render();
                })
                ->rawColumns(['deleted_by', 'deleted_at', 'action'])
                ->make(true);
        }
        return view('backend.admin.user-management.playlist.trash');
    }

    protected function trashedMenuItems($model): array
    {
        return [
            [
                'routeName' => '',
                'params' => [encrypt($model->id)],
                'label' => 'Restore',
                'permissions' => ['permission-restore']
            ],
            [
                'routeName' => '',
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
            $this->userPlaylistService->restore($id);
            session()->flash('success', "Service restored successfully");
        } catch (\Throwable $e) {
            session()->flash('Service restore failed');
            throw $e;
        }
        return $this->redirectTrashed();
    }

    public function permanentDelete(string $id): RedirectResponse
    {
        try {
            $this->userPlaylistService->permanentDelete($id);
            session()->flash('success', "Service permanently deleted successfully");
        } catch (\Throwable $e) {
            session()->flash('Service permanent delete failed');
            throw $e;
        }
        return $this->redirectTrashed();
    }
}
