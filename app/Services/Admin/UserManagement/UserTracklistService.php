<?php

namespace App\Services\Admin\UserManagement;

use App\Http\Traits\FileManagementTrait;
use App\Models\Track;
use Illuminate\Database\Eloquent\Collection;

class UserTracklistService
{
    use FileManagementTrait;

    public function getUsertracklists($orderBy = 'sort_order', $order = 'asc')
    {
        return Track::orderBy($orderBy, $order)->latest();
    }
    public function getUsertracklist(string $encryptedId): Track | Collection
    {
        return Track::findOrFail(decrypt($encryptedId));
    }
    public function getDeletedUsertracklist(string $encryptedId): Track | Collection
    {
        return Track::onlyTrashed()->findOrFail(decrypt($encryptedId));
    }

    public function delete(Track $user): void
    {
        $user->update(['deleter_id' => admin()->id, 'deleter_type' => get_class(admin())]);
        $user->delete();
    }

    public function restore(string $encryptedId): void
    {
        $user = $this->getDeletedUsertracklist($encryptedId);
        $user->update(['updater_id' => admin()->id],
            ['updater_type' => get_class(admin())]);
        $user->restore();
    }

    public function permanentDelete(string $encryptedId): void
    {
        $user = $this->getDeletedUsertracklist($encryptedId);
        $user->forceDelete();
    }

}
