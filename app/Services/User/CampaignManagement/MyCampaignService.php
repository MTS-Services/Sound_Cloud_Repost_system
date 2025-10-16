<?php

namespace App\Services\User\CampaignManagement;

use App\Models\Campaign;
use App\Models\Track;
use Illuminate\Database\Eloquent\Collection;

class MyCampaignService
{
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
    public function createTrackCampaign(array $data)
    {
        $data['user_urn'] = user()->urn;
        $data['created_id'] = user()->id;
        $data['music_type'] = Track::class;
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

    public function thisMonthCampaignsCount()
    {
        return Campaign::self()->open()->whereMonth('created_at', now()->month)->count();
    }
}
