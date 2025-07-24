<?php

namespace App\Http\Controllers\Backend\User\CampaignManagement;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CampaignManagement\CampaignRequest;
use App\Http\Traits\AuditRelationTraits;
use App\Models\Campaign;
use App\Models\Track;
use App\Services\Admin\CampaignManagement\CampaignService;
use App\Services\Admin\TrackService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class MyCampaignController extends Controller
{
    use AuditRelationTraits;

    protected function redirectIndex(): RedirectResponse
    {
        return redirect()->route('user.cm.campaigns.index');
    }

    protected CampaignService $campaignService;
    protected TrackService $trackService;

    public function __construct(CampaignService $campaignService, TrackService $trackService)
    {
        $this->campaignService = $campaignService;
        $this->trackService = $trackService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // $data['campaigns'] = $this->campaignService->getCampaigns()->active_completed()->get();
        $data['campaigns'] = $this->campaignService->getCampaigns()->where('user_urn', user()->urn)->get();
        $data['tracks'] = $this->trackService->getTracks()->where('user_urn', user()->urn)->get();


        return view('backend.user.campaign_management.campaigns.campaigns', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request, string $track_id): View
    {
        $data['track'] = $this->trackService->getTrack($track_id);
        return view('backend.user.campaign_management.campaigns.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CampaignRequest $request)
    {
        try {
            $validated = $request->validated();
            $this->campaignService->createTrackCampaign($validated);
            session()->flash('success', "Campaign created successfully");
        } catch (\Throwable $e) {
            session()->flash('Campaign creation failed');
            throw $e;
        }
        return $this->redirectIndex();
    }

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
