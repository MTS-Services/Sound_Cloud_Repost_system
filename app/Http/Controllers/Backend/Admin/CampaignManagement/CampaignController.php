<?php

namespace App\Http\Controllers\Backend\Admin\CampaignManagement;

use App\Models\Campaign;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\Http\Traits\AuditRelationTraits;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;
use App\Services\User\CampaignManagement\CampaignService;

class CampaignController extends Controller implements HasMiddleware
{
    use AuditRelationTraits;

    protected function redirectIndex(): RedirectResponse
    {
        return redirect()->route('cm.campaign.index');
    }

    protected function redirectTrashed(): RedirectResponse
    {
        return redirect()->route('cm.campaign.trash');
    }

    protected CampaignService $campaignService;

    public function __construct(CampaignService $campaignService)
    {
        $this->campaignService = $campaignService;
    }

    public static function middleware(): array
    {
        return [
            'auth:admin', // Applies 'auth:admin' to all methods

            // Permission middlewares using the Middleware class
            new Middleware('permission:campaign-list', only: ['index']),
            new Middleware('permission:campaign-details', only: ['show']),
            //add more permissions if needed
        ];
    }

   
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = $this->campaignService->getCampaigns();
            if($request->has('user_urn')){
                $query->where('user_urn', decrypt($request->user_urn));
            }
            return DataTables::eloquent($query)
                ->editColumn('user_urn', function ($campaign) {
                    return $campaign->user->name;
                })
                ->editColumn('start_date', function ($campaign) {
                    return $campaign->start_date_formatted;
                })
                ->editColumn('end_date', function ($campaign) {
                    return $campaign->end_date_formatted;
                })
                ->editColumn('status', fn($campaign) => "<span class='badge badge-soft {$campaign->status_color}'>{$campaign->status_label}</span>")
                ->editColumn('created_by', function ($campaign) {
                    return $this->creater_name($campaign);
                })
                ->editColumn('created_at', function ($campaign) {
                    return $campaign->created_at_formatted;
                })
                ->editColumn('action', function ($campaign) {
                    $menuItems = $this->menuItems($campaign);
                    return view('components.action-buttons', compact('menuItems'))->render();
                })
                ->rawColumns(['action', 'feature_category_id', 'created_by', 'created_at', 'status', 'start_date', 'end_date'])
                ->make(true);
        }
        return view('backend.admin.campaign_management.campaigns.index');
    }

     public function detail($id)
    {

        $data['campaigns']= Campaign::where('id',decrypt($id))->first();
        
        return view('backend.admin.campaign_management.campaigns.detail',$data);
    }

    protected function menuItems($model): array
    {
        return [
            [
                'routeName' => 'cm.campaign.detail',
                'params' => encrypt($model->id),
                'label' => ' Details',
                'permissions' => ['campaign-detail']
            ],
           

        ];
    }

    /**
     * Show the form for creating a new resource.
     */
    // public function create(): View
    // {
    //     //
    //     return view('view file url ...');
    // }

    /**
     * Store a newly created resource in storage.
     */
    // public function store(Request $request)
    // {
    //      try {
    //         // $validated = $request->validated();
    //         //
    //         session()->flash('success', "Service created successfully");
    //     } catch (\Throwable $e) {
    //         session()->flash('Service creation failed');
    //         throw $e;
    //     }
    //     return $this->redirectIndex();
    // }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        $data = $this->campaignService->getCampaign($id);
        $data['user_urn'] = $data->user->name;
        $data['auto_approve_label'] = $data->auto_approve_label;
        $data['start_date'] = $data->start_date_formatted;
        $data['end_date'] = $data->end_date_formatted;
        $data['creater_name'] = $this->creater_name($data);
        $data['updater_name'] = $this->updater_name($data);
        return response()->json($data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    // public function edit(string $id): View
    // {
    //     $data['campaign'] = $this->campaignService->getCampaign($id);
    //     return view('backend.admin.campaign_management.campaigns.edit', $data);
    // }

    /**
     * Update the specified resource in storage.
     */
    // public function update(Request $request, string $id)
    // {
    //      try {
    //         // $validated = $request->validated();
    //         //
    //         session()->flash('success', "Service updated successfully");
    //     } catch (\Throwable $e) {
    //         session()->flash('Service update failed');
    //         throw $e;
    //     }
    //     return $this->redirectIndex();
    // }

    /**
     * Remove the specified resource from storage.
     */
    // public function destroy(string $id)
    // {
    //      try {
    //         //
    //         session()->flash('success', "Service deleted successfully");
    //     } catch (\Throwable $e) {
    //         session()->flash('Service delete failed');
    //         throw $e;
    //     }
    //     return $this->redirectIndex();
    // }

    // public function trash(Request $request)
    // {
    //     if ($request->ajax()) {
    //         $query = $this->campaignService->getCampaigns()->onlyTrashed();
    //         return DataTables::eloquent($query)
    //             ->editColumn('deleted_by', function ($campaign) {
    //                 return $this->deleter_name($campaign);
    //             })
    //             ->editColumn('deleted_at', function ($campaign) {
    //                 return $campaign->deleted_at_formatted;
    //             })
    //             ->editColumn('action', function ($permission) {
    //                 $menuItems = $this->trashedMenuItems($permission);
    //                 return view('components.action-buttons', compact('menuItems'))->render();
    //             })
    //             ->rawColumns(['deleted_by', 'deleted_at', 'action'])
    //             ->make(true);
    //     }
    //     return view('backend.admin.campaign_management.campaign.trash');
    // }

    // protected function trashedMenuItems($model): array
    // {
    //     return [
    //         [
    //             'routeName' => '',
    //             'params' => [encrypt($model->id)],
    //             'label' => 'Restore',
    //             'permissions' => ['permission-restore']
    //         ],
    //         [
    //             'routeName' => '',
    //             'params' => [encrypt($model->id)],
    //             'label' => 'Permanent Delete',
    //             'p-delete' => true,
    //             'permissions' => ['permission-permanent-delete']
    //         ]

    //     ];
    // }

    //  public function restore(string $id): RedirectResponse
    // {
    //     try {
    //         $this->campaignService->restore($id);
    //         session()->flash('success', "Service restored successfully");
    //     } catch (\Throwable $e) {
    //         session()->flash('Service restore failed');
    //         throw $e;
    //     }
    //     return $this->redirectTrashed();
    // }

    // public function permanentDelete(string $id): RedirectResponse
    // {
    //     try {
    //         $this->campaignService->permanentDelete($id);
    //         session()->flash('success', "Service permanently deleted successfully");
    //     } catch (\Throwable $e) {
    //         session()->flash('Service permanent delete failed');
    //         throw $e;
    //     }
    //     return $this->redirectTrashed();
    // }
}
