<?php

namespace App\Services\Admin\CampaignManagement;

use App\Models\Campaign;
use Illuminate\Database\Eloquent\Collection;

class CampaignService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function getCampaigns($orderBy = 'sort_order', $order = 'asc')
    {
        $campaigns = Campaign::orderBy($orderBy, $order)->latest();
        return $campaigns;
    }
    public function getCampaign(string $encryptedId): Campaign|Collection
    {
        $campaign = Campaign::findOrFail(decrypt($encryptedId));
        return $campaign;
    }
    public function createCampaign(array $data)
    {   
        $data['user_urn'] = user()->urn;
        $data['music_id'];
        $data['created_by'] = user()->id;
        $campaign = Campaign::create($data);
        return $campaign;
    }
    public function restore(int $encryptedId)
    {
        $campaign = Campaign::withTrashed()->find(decrypt($encryptedId));
        $campaign->restore();
    }
    public function permanentDelete(int $encryptedId)
    {
        $campaign = Campaign::find(decrypt($encryptedId));
        $campaign->forceDelete();
    }

}
