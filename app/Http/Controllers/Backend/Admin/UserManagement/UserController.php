<?php

namespace App\Http\Controllers\Backend\Admin\UserManagement;

use App\Events\AdminNotificationSent;
use App\Events\UserNotificationSent;
use App\Http\Controllers\Controller;
use App\Http\Traits\AuditRelationTraits;
use App\Models\CreditTransaction;
use App\Models\CustomNotification;
use App\Models\Order;
use App\Models\Payment;

use App\Models\Playlist;
use App\Models\User;
use App\Services\Admin\CreditManagement\CreditService;
use App\Services\Admin\OrderManagement\PaymentService;
use App\Services\Admin\UserManagement\UserService;
use App\Services\PlaylistService;
use App\Services\TrackService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;


class UserController extends Controller implements HasMiddleware
{

    use AuditRelationTraits;

    protected UserService $userService;
    protected PlaylistService $playlistService;
    protected TrackService $trackService;
    protected CreditService $creditService;
    protected PaymentService $paymentService;

    protected function redirectIndex(): RedirectResponse
    {
        return redirect()->route('um.user.index');
    }

    protected function redirectTrashed(): RedirectResponse
    {
        return redirect()->route('um.user.trash');
    }



    public function __construct(UserService $userService, PlaylistService $playlistService, TrackService $trackService, CreditService $creditService, PaymentService $paymentService)
    {
        $this->userService = $userService;
        $this->playlistService = $playlistService;
        $this->trackService = $trackService;
        $this->creditService = $creditService;
        $this->paymentService = $paymentService;
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
                ->addColumn('profile_link', fn($user) => "<a class='text-blue-500 bg-blue-300' href='{$user->soundcloud_permalink_url}' target='_blank'>Profile</a>")
                ->editColumn('creater_id', fn($user) => $this->creater_name($user))
                ->editColumn('created_at', fn($user) => $user->created_at_formatted)
                ->editColumn('action', fn($user) => view('components.action-buttons', ['menuItems' => $this->menuItems($user)])->render())
                ->rawColumns(['action', 'status', 'created_at', 'creater_id', 'profile_link'])
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
                'label' => 'Details',
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
                'routeName' => 'cm.campaign.index',
                'params' => ['user_urn' => encrypt($model->urn)],
                'label' => 'Campaigns',
                'permissions' => ['campaign-list']
            ],
            [
                'routeName' => 'rrm.request.index',
                'params' => ['requester_urn' => encrypt($model->urn)],
                'label' => 'Repost Requests',
                'permissions' => ['repost-request-list']
            ],
            [
                'routeName' => 'rm.repost.index',
                'params' => ['reposter_urn' => encrypt($model->urn)],
                'label' => 'Reposts',
                'permissions' => ['repost-list']
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
        $data['userinfo'] = $data['user']->userInfo;
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
    public function destroy(string $id)
    {
        try {
            $user = $this->userService->getUser($id);

            $this->userService->delete($user);
            session()->flash('success', 'User deleted successfully!');
        } catch (\Throwable $e) {
            session()->flash('error', 'User delete failed!');
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
                ->editColumn('deleter_id', function ($user) {
                    return $this->deleter_name($user);
                })
                ->editColumn('deleted_at', function ($user) {
                    return $user->deleted_at_formatted;
                })
                ->editColumn('action', function ($user) {
                    $menuItems = $this->trashedMenuItems($user);
                    return view('components.action-buttons', compact('menuItems'))->render();
                })
                ->rawColumns(['deleter_id', 'status', 'deleted_at', 'action'])
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
                'permissions' => ['user-restore']
            ],
            [
                'routeName' => 'um.user.permanent-delete',
                'params' => [encrypt($model->id)],
                'label' => 'Permanent Delete',
                'p-delete' => true,
                'permissions' => ['user-permanent-delete']
            ]

        ];
    }

    public function restore(string $id)
    {
        try {
            $this->userService->restore($id);
            session()->flash('success', "User restored successfully");
        } catch (\Throwable $e) {
            session()->flash('User restore failed');
            throw $e;
        }
        return $this->redirectTrashed();
    }

    public function permanentDelete(string $id)
    {
        try {
            $this->userService->permanentDelete($id);
            session()->flash('success', "User permanently deleted successfully");
        } catch (\Throwable $e) {
            session()->flash('User permanent delete failed');
            throw $e;
        }
        return $this->redirectTrashed();
    }

    public function playlist(Request $request)
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
                'routeName' => 'um.user.playlist.details',
                'params' => encrypt($model->id),
                'label' => ' Details',
                'permissions' => ['permissions-list']
            ],


            [
                'routeName' => 'um.user.playlist.track-list',
                ['user' => $model->urn],
                'params' => [encrypt($model->soundcloud_urn), encrypt($model->id)],
                'label' => 'Tracklist',
                'edit' => true,
                'permissions' => ['permission-tracklist']
            ],
        ];
    }

    //palylest track


    public function playlistDetail($id)
    {
        $data['playlists'] = Playlist::with('user')->find(decrypt($id));
        return view('backend.admin.user-management.playlists.details', $data);
    }


    public function playlistTracks(Request $request, $playlistUrn)
    {
        $palaylistUrn = $playlistUrn;
        if ($request->ajax()) {
            $playlist = $this->playlistService->getPlaylist($playlistUrn)->load(['tracks', 'user']);
            $query = $playlist->tracks();
            return DataTables::eloquent($query)
                ->editColumn('user_name', function ($tracklist) {
                    return $tracklist->user?->name;
                })
                ->editColumn('creater_id', fn($tracklist) => $this->creater_name($tracklist))
                ->editColumn('created_at', fn($tracklist) => $tracklist->created_at_formatted)
                ->editColumn('action', function ($playlist) {
                    $menuItems = $this->playlistTrackMenuItems($playlist);
                    return view('components.action-buttons', compact('menuItems'))->render();
                })
                ->rawColumns(['user_name', 'action', 'created_at', 'creater_id'])->make(true);
        }
        return view('backend.admin.user-management.playlists.playlist_track', compact('palaylistUrn'));
    }
    public function playlistTrackMenuItems($model): array
    {
        return [
            [
                'routeName' => 'um.user.tracklist.detail',
                'params' => [encrypt($model->urn), encrypt($model->id)],
                'label' => ' details',
                'permissions' => ['Detail-list']
            ],
        ];
    }

    public function trackMenuItems($model): array
    {
        return [
            [
                'routeName' => 'um.user.tracklist.detail',
                'params' => encrypt($model->id),
                'label' => ' details',
                'permissions' => ['Detail-list']
            ],
        ];
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
                ->rawColumns(['action', 'creater_id', 'created_at', 'user_urn', 'release_month'])
                ->make(true);
        }
        return view('backend.admin.user-management.tracklist.index');
    }

    protected function tracklistMenuItems($model): array
    {
        return [
            [
                'routeName' => 'um.user.tracklist.detail',
                'params' => [encrypt($model->urn), encrypt($model->id)],
                'label' => ' Details',
                'permissions' => ['Detail-list']
            ],


        ];
    }

    public function tracklistDetail($trackUrn)
    {
        $data['tracklists'] = $this->trackService->getTrack($trackUrn, 'urn')->load(['user']);
        return view('backend.admin.user-management.tracklist.details', $data);
    }
    public function playlistShow(string $soudcloud_urn,)
    {
        $data = $this->playlistService->getPlaylist($soudcloud_urn, 'soundcloud_urn',);
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


        $data['receiver_urn'] = $user->urn;
        $data['sender_urn'] = null;
        $data['description'] = 'Credit added by system';
        $data['transaction_type'] = CreditTransaction::TYPE_MANUAL;
        $data['calculation_type'] = CreditTransaction::CALCULATION_TYPE_DEBIT;
        $data['credits'] = $data['credit'];
        $data['amount'] = 0;
        $data['payment_status'] = Payment::STATUS_SUCCEEDED;
        $data['receiver_urn'] = $user->urn;
        $data['creater_id'] = admin()->id;
        $data['creater_type'] = get_class(admin());
        $data['source_id'] = admin()->id;
        $data['source_type'] = get_class(admin());
        $data['order_type'] = Order::TYPE_CREDIT;
        $data['paid_by'] = admin()->name;
        $data['email_address'] = null;
        $data['post_code'] = null;
        $data['address'] = null;
        $data['reference'] = null;
        $data['payment_method'] = null;
        $data['payment_gateway'] = Payment::PAYMENT_GATEWAY_UNKNOWN;
        $data['payment_provider_id'] = null;
        $data['currency'] = 'USD';
        $data['exchange_rate'] = null;
        $data['payment_intent_id'] = null;
        $data['receipt_url'] = null;
        $data['failure_reason'] = null;
        $data['metadata'] = null;
        $data['processed_at'] = now();
        try {
            DB::transaction(function () use ($user, $data) {
                $this->creditService->addCredit($data);

                $userNotification = CustomNotification::create([
                    'type' => CustomNotification::TYPE_USER,
                    'sender_id' => admin()->id,
                    'sender_type' => get_class(admin()),
                    'receiver_id' => $user->id,
                    'receiver_type' => User::class,
                    'message_data' => [
                        'title' => 'Credit Added',
                        'message' => 'Credit added successfully!',
                        'description' => 'You have received ' . $data['credit'] . ' credits from ' . admin()->name,
                        'icon' => 'banknote',
                        'additional_data' => []
                    ]
                ]);
                $userNotification = CustomNotification::create([
                    'type' => CustomNotification::TYPE_ADMIN,
                    'sender_id' => admin()->id,
                    'sender_type' => get_class(admin()),
                    'receiver_id' => null,
                    'receiver_type' => null,
                    'message_data' => [
                        'title' => 'Sended Credit',
                        'message' => 'Credit added successfully! to ' . $user->name,
                        'description' => 'You have sent ' . $data['credit'] . ' credits to ' . $user->name,
                        'icon' => 'banknote',
                        'additional_data' => []
                    ]
                ]);
                broadcast(new AdminNotificationSent($userNotification));
                broadcast(new UserNotificationSent($userNotification));
                //  broadcast(new UserNotificationSent($notification));
                session()->flash('success', 'Credit added successfully.');
            });
        } catch (\Throwable $th) {
            session()->flash('error', 'Error adding credit.');
            Log::info("message:" . $th->getMessage());
            throw $th;
        }
        return redirect()->route('um.user.index');
    }
}
