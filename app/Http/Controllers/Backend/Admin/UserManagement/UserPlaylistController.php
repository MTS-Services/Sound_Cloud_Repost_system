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
        return view('backend.admin.user-management.playlists.playlist');
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

}
