<?php

namespace App\Http\Controllers\Backend\Admin\UserManagement;

use App\Http\Controllers\Controller;
use App\Http\Traits\AuditRelationTraits;
use App\Models\Playlist;
use App\Services\Admin\UserManagement\UserService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller implements HasMiddleware
{

    use AuditRelationTraits;
    protected function redirectIndex(): RedirectResponse
    {
        return redirect()->route('um.user.index');
    }

    protected function redirectTrashed(): RedirectResponse
    {
        return redirect()->route('um.user.trash');
    }

    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public static function middleware(): array
    {
        return [
            'auth:admin', // Applies 'auth:admin' to all methods

            // Permission middlewares using the Middleware class
            new Middleware('permission:user-list', only: ['index']),
            new Middleware('permission:user-details', only: ['show']),
            new Middleware('permission:user-delete', only: ['destroy']),
            new Middleware('permission:user-trash', only: ['trash']),
            new Middleware('permission:user-restore', only: ['restore']),
            new Middleware('permission:user-permanent-delete', only: ['permanentDelete']),
            new Middleware('permission:user-status', only: ['status']),
            //add more permissions if needed
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = $this->userService->getUsers();
            return DataTables::eloquent($query)
                ->editColumn('status', fn($user) => "<span class='badge badge-soft {$user->status_color}'>{$user->status_label}</span>")
                ->editColumn('creater_id', fn($user) => $this->creater_name($user))
                ->editColumn('created_at', fn($user) => $user->created_at_formatted)
                ->editColumn('action', fn($user) => view('components.action-buttons', ['menuItems' => $this->menuItems($user)])->render())
                ->rawColumns(['action', 'status', 'created_at', 'creater_id'])
                ->make(true);
        }
        return view('backend.admin.user-management.user.index');
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
                'routeName' => 'um.user.playlist',
                ['user' => $model->urn],
                'params' => [encrypt($model->urn)],
                'label' => 'Playlist',
                'edit' => true,
                'permissions' => ['permission-playlist']
            ],
            //  [
            //     'routeName' => 'um.user.tracklist',
            //     'params' => [encrypt($model->id)],
            //     'label' => 'Tracklist',
            //     'edit' => true,
            //     'permissions' => ['permission-tracklist']
            // ],
            [
                'routeName' => 'um.user.status',
                'params' => [encrypt($model->id)],
                'label' => $model->status_btn_label,
                'className' => $model->status_btn_color,
                'permissions' => ['permission-status']
            ],
            [
                'routeName' => 'um.user.destroy',
                'params' => [encrypt($model->id)],
                'label' => 'Delete',
                'delete' => true,
                'permissions' => ['permission-delete']
            ]

        ];
    }

    public function playlist(Request $request, $id)
    {
        if ($request->ajax()) {
            $query = Playlist::where('user_urn', decrypt($id))->get();

            return DataTables::eloquent($query)
                ->editColumn('user_id', fn($playlist) => $this->creater_name($playlist))
                ->editColumn('user_urn', fn($playlist) => $playlist->user?->name)
                ->editColumn('soundcloud_id', fn($playlist) => $playlist->soundcloud?->name)
                ->editColumn('status', fn($playlist) => "<span class='badge badge-soft {$playlist->status_color}'>{$playlist->status_label}</span>")
                ->editColumn('creater_id', fn($playlist) => $this->creater_name($playlist))
                ->editColumn('created_at', fn($playlist) => $playlist->created_at_formatted)
                ->editColumn('action', fn($playlist) => view('components.action-buttons', ['menuItems' => $this->playlistmenuItems($playlist)])->render())
                ->rawColumns(['user_urn', 'soundcloud_id', 'action', 'status', 'created_at', 'creater_id'])
                ->make(true);
        }
        return view('backend.admin.user-management.playlist.playlist');
    }

    public function playlistmenuItems($model): array
    {
        return [
            [
            ],
        ];
    }
    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        $data = $this->userService->getUser($id)->load(['userInfo']);
        $data['creater_name'] = $this->creater_name($data);
        $data['updater_name'] = $this->updater_name($data);
        return response()->json($data);
    }

    /**
     * Show the form for editing the specified resource.
     */

    public function destroy(string $id)
    {
        try {
            $user = $this->userService->getUser($id);
            $this->userService->delete($user);
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
            $query = $this->userService->getUsers()->onlyTrashed();
            return DataTables::eloquent($query)
                ->editColumn('status', fn($user) => "<span class='badge badge-soft {$user->status_color}'>{$user->status_label}</span>")
                ->editColumn('deleter_id', fn($user) => $this->deleter_name($user))
                ->editColumn('deleted_at', fn($user) => $user->deleted_at_formatted)
                ->editColumn('action', fn($user) => view('components.action-buttons', ['menuItems' => $this->trashedMenuItems($user)])->render())
                ->rawColumns(['action', 'status', 'deleted_at', 'deleter_id'])
                ->make(true);
        }
        return view('backend.admin.user-management.user.trash');
    }

    protected function trashedMenuItems($model): array
    {
        return [
            [
                'routeName' => 'um.user.restore',
                'params' => [encrypt($model->id)],
                'label' => 'Restore',
                'permissions' => ['permission-restore']
            ],
            [
                'routeName' => 'um.user.permanent-delete',
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
            $this->userService->restore($id);
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
            $this->userService->permanentDelete($id);
            session()->flash('success', "Service permanently deleted successfully");
        } catch (\Throwable $e) {
            session()->flash('Service permanent delete failed');
            throw $e;
        }
        return $this->redirectTrashed();
    }
    public function status(string $id)
    {
        $user = $this->userService->getUser($id);
        $this->userService->toggleStatus($user);
        session()->flash('success', 'User status updated successfully!');
        return redirect()->back();
    }
}
