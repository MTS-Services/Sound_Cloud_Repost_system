<?php

namespace App\Services\User\Mamber;


use App\Models\RepostRequest;
use App\Models\Track;
use Illuminate\Database\Eloquent\Collection;

class RepostRequestService
{
    // public function getReportRequests($orderBy = 'sort_order', $order = 'asc')
    // {
    //     $reportRequests = RepostRequest::orderBy($orderBy, $order)->latest();
    //     return $reportRequests;
    // }
    // public function getReportRequest(string $encryptedId): RepostRequest|Collection
    // {
    //     $reportRequests = RepostRequest::findOrFail(decrypt($encryptedId));
    //     return $reportRequests;
    // }
    // public function createTrackReportRequest(array $data)
    // {
    //     $data['user_urn'] = user()->urn;
    //     $data['track_urn'] = $data['track_urn'];
    //     $data['created_id'] = user()->id;
    //     $data['music_type'] = Track::class;
    //     $reportRequests = RepostRequest::create($data);
    //     return $reportRequests;
    // }
   
}
