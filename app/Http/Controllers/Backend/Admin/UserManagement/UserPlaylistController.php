<?php

namespace App\Http\Controllers\Backend\Admin\UserManagement;

use App\Http\Controllers\Controller;
use App\Http\Traits\AuditRelationTraits;
use App\Models\Playlist;
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
    public function index(Request $request , string $id)
    {
        
        if ($request->ajax()) {
            $query = $this->userPlaylistService-> getUserPlaylists();
            return DataTables::eloquent($query)
                ->editColumn('user_urn', function ($playlist) {
                    return $playlist->user?->name;
                })
                // ->editColumn('soundcloud_id', function ($playlist) {
                //     return $playlist->soundcloud?->name;
                // })
                ->editColumn('creater_id', fn($playlist) => $this->creater_name($playlist))
                ->editColumn('created_at', fn($playlist) => $playlist->created_at_formatted)
                ->editColumn('action', function ($playlist) {
                    $menuItems = $this->menuItems($playlist);
                    return view('components.action-buttons', compact('menuItems'))->render();
                })
                ->rawColumns([  'user_urn',
                'action', 'creater_id', 'created_at',])
                ->make(true);
        }
        return view('backend.admin.user-management.playlist.playlist');
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
     public function trash(Request $request)
     {
         if ($request->ajax()) {
            $query = $this->userPlaylistService->getUserPlaylists()->onlyTrashed();
            return DataTables::eloquent($query)
                 ->editColumn('user_urn', function ($playlist) {
                    return $playlist->user?->name;
                })
                ->editColumn('deleter_id', fn($user) => $this->deleter_name($user))
                ->editColumn('deleted_at', fn($user) => $user->deleted_at_formatted)
                ->editColumn('action', fn($user) => view('components.action-buttons', ['menuItems' => $this->trashedMenuItems($user)])->render())
                ->rawColumns(['action','deleted_at', 'deleter_id'])
                ->make(true);
        }
        return view('backend.admin.user-management.playlist.trash');
    }

    protected function trashedMenuItems($model): array
    {
        return [
            [
                'routeName' => 'um.playlist.restore',
                'params' => [encrypt($model->id)],
                'label' => 'Restore',
                'permissions' => ['permission-restore']
            ],
            [
                'routeName' => 'um.playlist.permanent-delete',
                'params' => [encrypt($model->id)],
                'label' => 'Permanent Delete',
                'p-delete' => true,
                'permissions' => ['permission-permanent-delete']
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
        $data['user_urn'] = $data->user?->name;
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
            $playlist = $this->userPlaylistService->getUserPlaylist($id);
            $this->userPlaylistService->delete($playlist);
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
