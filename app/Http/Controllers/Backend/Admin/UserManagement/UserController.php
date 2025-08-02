<?php

namespace App\Http\Controllers\Backend\Admin\UserManagement;

use App\Http\Controllers\Controller;
use App\Http\Traits\AuditRelationTraits;
use App\Models\Playlist;
use App\Models\User;
use App\Services\Admin\UserManagement\UserService;
use App\Services\PlaylistService;
use App\Services\TrackService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Yajra\DataTables\Facades\DataTables;


class UserController extends Controller implements HasMiddleware
{

    use AuditRelationTraits;

    protected UserService $userService;
    protected PlaylistService $playlistService;
    protected TrackService $trackService;

    protected function redirectIndex(): RedirectResponse
    {
        return redirect()->route('um.user.index');
    }

    protected function redirectTrashed(): RedirectResponse
    {
        return redirect()->route('um.user.trash');
    }



    public function __construct(UserService $userService, PlaylistService $playlistService, TrackService $trackService)
    {
        $this->userService = $userService;
        $this->playlistService = $playlistService;
        $this->trackService = $trackService;
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
                'routeName' => 'um.user.detail',
                'params' => encrypt($model->id),
                'label' => 'View Details',
                'permissions' => ['user-detail']
            ],
            // <button class="btn" onclick="add_credit_modal.showModal()">open modal</button>
            [
                'routeName' => 'javascript:void(0)',
                'label' => 'Add Credit',
                'data-id' => encrypt($model->urn),
                'className' => 'add-credit',
                'permissions' => ['permission-credit']
            ],

            [
                'routeName' => 'um.user.playlist',
                ['user' => $model->urn],
                'params' => [encrypt($model->urn)],
                'label' => 'Playlist',
                'edit' => true,
                'permissions' => ['permission-playlist']
            ],
            [
                'routeName' => 'um.user.tracklist',
                'params' => [encrypt($model->urn)],
                'label' => 'Tracklist',
                'edit' => true,
                'permissions' => ['permission-tracklist']
            ],
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

    public function detail(Request $request, string $id)
    {
        $data['user'] = $this->userService->getUser($id)->load(['userInfo']);
       
        return view('backend.admin.user-management.user.detail', $data);
    }
    public function show(Request $request, string $id)
    {
        $data = $this->userService->getUser($id)->load(['userInfo']);
        // dd($data);
        $data['creater_name'] = $this->creater_name($data);
        $data['updater_name'] = $this->updater_name($data);
        return response()->json($data);
    }
    public function status(Request $request, string $id)
    {
        $user = $this->userService->getUser($id);
        $this->userService->toggleStatus($user);
        session()->flash('success', 'User status updated successfully.');
        return $this->redirectIndex();
    }

    public function playlist(Request $request, $id)
    {
        if ($request->ajax()) {
            $query = $this->playlistService->getPlaylists()->with('user');
            return DataTables::eloquent($query)
                ->editColumn('user_name', function ($playlist) {
                    return $playlist->user?->name;
                })
                ->editColumn('release_month', function ($playlist) {
                    return $playlist->release_month_formatted;
                })
                ->editColumn('creater_id', fn($playlist) => $this->creater_name($playlist))
                ->editColumn('created_at', fn($playlist) => $playlist->created_at_formatted)
                ->editColumn('action', function ($playlist) {
                    $menuItems = $this->playlistmenuItems($playlist);
                    return view('components.action-buttons', compact('menuItems'))->render();
                })
                ->rawColumns(['user_name', 'release_month', 'action', 'creater_id', 'created_at',])->make(true);
        }
        return view('backend.admin.user-management.playlists.playlist');
    }

    public function playlistmenuItems($model): array
    {
        return [
            [
                'routeName' => 'javascript:void(0)',
                'data-id' => encrypt($model->soundcloud_urn),
                'className' => 'view',
                'label' => 'Details',
                'permissions' => ['permission-list', 'permission-delete', 'permission-status']
            ],
            [
                'routeName' => 'um.user.playlist.track-list',
                'params' => [encrypt($model->soundcloud_urn), encrypt($model->id)],
                'label' => 'Tracks',
                'permissions' => ['permission-tracklist']
            ]
        ];
    }
    public function playlistTracks(Request $request, $playlistUrn)
    {
        $palaylistUrn = $playlistUrn;
        if ($request->ajax()) {
            $query = $this->playlistService->getPlaylistTracks($playlistUrn);
            return DataTables::eloquent($query)
                ->editColumn('action', function ($playlist) {
                    $menuItems = $this->playlistmenuItems($playlist);
                    return view('components.action-buttons', compact('menuItems'))->render();
                })
                ->rawColumns(['action', 'created_at', 'title'])->make(true);
        }
        return view('backend.admin.user-management.playlists.playlist_track', compact('palaylistUrn'));
    }

    public function tracklist(Request $request)
    {

        if ($request->ajax()) {
            $query = $this->trackService->getTracks();
            return DataTables::eloquent($query)
                ->editColumn('release_month', function ($playlist) {
                    return $playlist->release_month_formatted;
                })
                ->editColumn('user_urn', function ($tracklist) {
                    return $tracklist->user?->name;
                })
                ->editColumn('creater_id', fn($user) => $this->creater_name($user))
                ->editColumn('created_at', fn($user) => $user->created_at_formatted)
                ->editColumn('action', function ($tracklist) {
                    $menuItems = $this->tracklistMenuItems($tracklist);
                    return view('components.action-buttons', compact('menuItems'))->render();
                })
                ->rawColumns(['action', 'creater_id', 'created_at', 'user_urn','release_month'])
                ->make(true);
        }
        return view('backend.admin.user-management.tracklist.index');
    }

    protected function tracklistMenuItems($model): array
    {
        return [
            [
                'routeName' => 'javascript:void(0)',
                'data-id' => encrypt($model->urn),
                'className' => 'view',
                'label' => 'Tracklist',
                'permissions' => ['permission-list']
            ],


        ];
    }
    public function playlistShow(string $soudcloud_urn)
    {
        $data = $this->playlistService->getPlaylist($soudcloud_urn, 'soundcloud_urn');
        $data['creater_name'] = $this->creater_name($data);
        $data['updater_name'] = $this->updater_name($data);
        return response()->json($data);
    }

    public function tracklistShow(string $urn)
    {
        $data = $this->trackService->getTrack($urn, 'urn');
        $data['creater_name'] = $this->creater_name($data);
        $data['updater_name'] = $this->updater_name($data);
        return response()->json($data);
    }

    public function addCredit(Request $request, string $user_urn)
    {
        $user = $this->userService->getUser($user_urn, 'urn');
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }
        $data = $request->validate([
            'credit' => 'required|numeric|min:1',
        ]);
        $this->userService->addCredit($user, $data);
        session()->flash('success', 'Credit added successfully.');
        return $this->redirectIndex();
    }
}
