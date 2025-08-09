<?php

namespace App\Http\Controllers\Backend\Admin\RepostManagement;

use App\Http\Controllers\Controller;
use App\Http\Traits\AuditRelationTraits;
use App\Models\Repost;
use App\Services\Admin\RepostManagement\RepostService;
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

    protected RepostService $repostService;

    public function __construct(RepostService $repostService)
    {
        $this->repostService = $repostService;
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
            $query = $this->repostService->getReposts()->with('reposter', 'trackOwner', 'campaign');
            if($request->has('reposter_urn')){
                $query->where('reposter_urn', decrypt($request->reposter_urn));
            }
            return DataTables::eloquent($query)
                ->editColumn('requester_name', function ($repost) {
                    return $repost->trackOwner->name;
                })
                ->editColumn('reposter_name', function ($repost) {
                    return $repost->reposter->name ?? '';
                })
                ->editColumn('reposte_at_format', function ($repost) {
                    return $repost->repost_at_formatted;
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
                ->rawColumns(['requester_name', 'reposter_name','reposte_at_format', 'created_by', 'created_at', 'action'])
                ->make(true);
        }
        return view('backend.admin.repost-management.repost.index');
    }

    protected function menuItems($model): array
    {
        return [

            [
                'routeName' => 'rm.repost.detail',
                'params' => [encrypt($model->id)],
                'label' => ' Details',
                'permissions' => ['repost-detail']
            ],
          


        ];
    }
  public function detail($id)
  {
      $data['reposts'] = Repost::where('id', decrypt($id))->first();
      return view('backend.admin.repost-management.repost.detail',$data);
  }
    public function show($id)
    {
        $data = $this->repostService->getRepost($id);
        $data['name'] = $data->reposter->name;
        $data['title'] = $data->campaign->title ?? '';

        // remove the following line
        // $data['updater_name'] = $this->updater_name($data);
        return response()->json($data);

    }


}
