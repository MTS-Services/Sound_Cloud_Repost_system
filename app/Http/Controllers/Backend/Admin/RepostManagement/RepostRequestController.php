<?php

namespace App\Http\Controllers\Backend\Admin\RepostManagement;

use App\Http\Controllers\Controller;
use App\Http\Traits\AuditRelationTraits;
use App\Models\RepostRequest;
use App\Services\Admin\RepostManagement\RepostRequestService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Yajra\DataTables\Facades\DataTables;

class RepostRequestController extends Controller implements HasMiddleware
{
    use AuditRelationTraits;

    protected $serviceName = RepostRequestService::class;

    protected function redirectIndex(): RedirectResponse
    {
        return redirect()->route('index route');
    }

    protected function redirectTrashed(): RedirectResponse
    {
        return redirect()->route('trash route');
    }

    protected RepostRequestService $RepostRequestService;

    public function __construct(RepostRequestService $RepostRequestService)
    {
        $this->RepostRequestService = $RepostRequestService;
    }

    public static function middleware(): array
    {
        return [
            'auth:admin', // Applies 'auth:admin' to all methods

            // Permission middlewares using the Middleware class
            new Middleware('permission:repostRequest-list', only: ['index']),
            new Middleware('permission:repostRequest-details', only: ['show']),
            new Middleware('permission:repostRequest-create', only: ['create', 'store']),
            new Middleware('permission:repostRequest-edit', only: ['edit', 'update']),
            new Middleware('permission:repostRequest-delete', only: ['destroy']),
            new Middleware('permission:repostRequest-trash', only: ['trash']),
            new Middleware('permission:repostRequest-restore', only: ['restore']),
            new Middleware('permission:repostRequest-permanent-delete', only: ['permanentDelete']),
            //add more permissions if needed
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = $this->RepostRequestService->getRepostRequests()->with('requester', 'campaign');
            return DataTables::eloquent($query)
                ->editColumn('name', function ($repost) {
                    return $repost->requester->name;
                })
                ->editColumn('title', function ($repost) {
                    return $repost->campaign->title ?? '';
                })
                ->editColumn('created_by', function ($admin) {

                    return $this->creater_name($admin);
                })
                ->editColumn('status', function ($repost) {
                    return "<span class='badge badge-soft {$repost->status_color}'>{$repost->status_label}</span>";
                })
                ->editColumn('created_at', function ($admin) {
                    return $admin->created_at_formatted;
                })
                ->editColumn('action', function ($service) {
                    $menuItems = $this->menuItems($service);
                    return view('components.action-buttons', compact('menuItems'))->render();
                })
                ->rawColumns(['name', 'title', 'status', 'created_by', 'created_at', 'action'])
                ->make(true);
        }
        return view('backend.admin.repost-management.repost-request.index');
    }

    protected function menuItems($model): array
    {
        return [

            [
                'routeName' => 'rrm.request.detail',
                'params' => [encrypt($model->id)],
                'label' => ' Details',
                'permissions' => ['req-detail']
            ],



        ];
    }

    public function detail($id)
    {
        $data['requests'] = RepostRequest::where('id', decrypt($id))->first();
        return view('backend.admin.repost-management.repost-request.detail', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function show($id)
    {
        $data = $this->RepostRequestService->getRepostRequest($id);
        $data['name'] = $data->requester->name;
        $data['title'] = $data->campaign->title ?? '';
        return response()->json($data);
    }
}
