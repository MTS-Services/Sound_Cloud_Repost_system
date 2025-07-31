<?php

namespace App\Http\Controllers\Backend\Admin\RepostManagement;

use App\Http\Controllers\Controller;
use App\Http\Traits\AuditRelationTraits;
use App\Services\Admin\RepostManagement\RepostTrackingService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Yajra\DataTables\Facades\DataTables;


class RepostController extends Controller implements HasMiddleware
{
    use AuditRelationTraits;

    protected function redirectIndex(): RedirectResponse
    {
        return redirect()->route('index route');
    }

    protected function redirectTrashed(): RedirectResponse
    {
        return redirect()->route('trash route');
    }

    protected RepostTrackingService $RepostTrackingService;

    public function __construct(RepostTrackingService $RepostTrackingService)
    {
        $this->RepostTrackingService = $RepostTrackingService;
    }
    
    public static function middleware(): array
    {
        return [
            'auth:admin', // Applies 'auth:admin' to all methods

            // Permission middlewares using the Middleware class
            new Middleware('permission:repost-list', only: ['index']),
            new Middleware('permission:repost-details', only: ['show']),
            new Middleware('permission:repost-create', only: ['create', 'store']),
            new Middleware('permission:repost-edit', only: ['edit', 'update']),
            new Middleware('permission:repost-delete', only: ['destroy']),
            new Middleware('permission:repost-trash', only: ['trash']),
            new Middleware('permission:repost-restore', only: ['restore']),
            new Middleware('permission:repost-permanent-delete', only: ['permanentDelete']),
            //add more permissions if needed
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = $this->RepostTrackingService->getReposts();
            return DataTables::eloquent($query)
              ->editColumn('name', function ($repost) {
                    return $repost->reposter->name ;
                })
                // ->editColumn('track_owner_urn', function ($repost) {
                //     return $repost->trackOwner->name;
                // })
                ->editColumn('title', function ($repost) {
                    return $repost->campaign->title ?? '' ;
                })

                 ->editColumn('created_by', function ($repost) {
                    return $this->creater_name($repost);
                })
                ->editColumn('created_at', function ($repost) {
                    return $repost->created_at_formatted;
                })
                ->editColumn('action', function ($repost) {
                    $menuItems = $this->menuItems($repost);
                    return view('components.action-buttons', compact('menuItems'))->render();
                })
                ->rawColumns([ 'name','title','created_by', 'created_at', 'action'])
                ->make(true);
        }
        return view('backend.admin.repost-management.repost.index');
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
            // [
            //     'routeName' => '',
            //     'params' => [encrypt($model->id)],
            //     'label' => 'Edit',
            //     'permissions' => ['permission-edit']
            // ],

            // [
            //     'routeName' => '',
            //     'params' => [encrypt($model->id)],
            //     'label' => 'Delete',
            //     'delete' => true,
            //     'permissions' => ['permission-delete']
            // ]

        ];
    }

   
}
