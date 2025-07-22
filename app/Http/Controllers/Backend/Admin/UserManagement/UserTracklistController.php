<?php

namespace App\Http\Controllers\Backend\Admin\UserManagement;

use App\Http\Controllers\Controller;
use App\Http\Traits\AuditRelationTraits;
use App\Services\Admin\UserManagement\UserTracklistService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Yajra\DataTables\Facades\DataTables;

class UserTracklistController extends Controller implements HasMiddleware
{
    use AuditRelationTraits;

    protected function redirectIndex(): RedirectResponse
    {
        return redirect()->route('um.tracklist.index');
    }

    protected function redirectTrashed(): RedirectResponse
    {
        return redirect()->route('um.tracklist.trash');
    }

    protected UserTracklistService $userTracklistService;

    public function __construct(UserTracklistService $userTracklistService)
    {
        $this->userTracklistService = $userTracklistService;
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
            $query = $this->userTracklistService->getUserTracklists();
            return DataTables::eloquent($query)
                ->editColumn('user_urn', function ($tracklist) {
                    return $tracklist->user?->name;
                })
                ->editColumn('creater_id', fn($user) => $this->creater_name($user))
                ->editColumn('created_at', fn($user) => $user->created_at_formatted)
                ->editColumn('action', function ($tracklist) {
                    $menuItems = $this->menuItems($tracklist);
                    return view('components.action-buttons', compact('menuItems'))->render();
                })
                ->rawColumns(['action', 'creater_id', 'created_at', 'user_urn'])
                ->make(true);
        }
        return view('backend.admin.user-management.tracklist.index');
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
                'routeName' => 'um.tracklist.destroy',
                'params' => [encrypt($model->id)],
                'label' => 'Delete',
                'delete' => true,
                'permissions' => ['permission-delete']
            ]

        ];
    }
     public function trash(Request $request)
     {
         if ($request->ajax()) {
            $query = $this->userTracklistService->getUserTracklists()->onlyTrashed();
            return DataTables::eloquent($query)
                 ->editColumn('user_urn', function ($tracklist) {
                    return $tracklist->user?->name;
                })
                ->editColumn('deleter_id', fn($user) => $this->deleter_name($user))
                ->editColumn('deleted_at', fn($user) => $user->deleted_at_formatted)
                ->editColumn('action', fn($user) => view('components.action-buttons', ['menuItems' => $this->trashedMenuItems($user)])->render())
                ->rawColumns(['action','deleted_at', 'deleter_id'])
                ->make(true);
        }
        return view('backend.admin.user-management.tracklist.trash');
    }

    protected function trashedMenuItems($model): array
    {
        return [
            [
                'routeName' => 'um.tracklist.restore',
                'params' => [encrypt($model->id)],
                'label' => 'Restore',
                'permissions' => ['permission-restore']
            ],
            [
                'routeName' => 'um.tracklist.permanent-delete',
                'params' => [encrypt($model->id)],
                'label' => 'Permanent Delete',
                'p-delete' => true,
                'permissions' => ['permission-permanent-delete']
            ]

        ];
    }


    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        $data = $this->userTracklistService->getUserTracklist($id);
        $data['user_urn'] = $data->user?->name;
        $data['creater_name'] = $this->creater_name($data);
        $data['updater_name'] = $this->updater_name($data);
        return response()->json($data);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $tracklist = $this->userTracklistService->getUserTracklist($id);
            $this->userTracklistService->delete($tracklist);
            session()->flash('success', "Playlist deleted successfully");
        } catch (\Throwable $e) {
            session()->flash('Playlist delete failed');
            throw $e;
        }
        return $this->redirectIndex();
    }

    public function restore(string $id): RedirectResponse
    {
        try {
            $this->userTracklistService->restore($id);
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
            $this->userTracklistService->permanentDelete($id);
            session()->flash('success', "Service permanently deleted successfully");
        } catch (\Throwable $e) {
            session()->flash('Service permanent delete failed');
            throw $e;
        }
        return $this->redirectTrashed();
    }
}
