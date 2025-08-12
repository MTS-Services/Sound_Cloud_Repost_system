<?php

namespace App\Services\Admin\UserManagement;

use App\Http\Traits\FileManagementTrait;

use App\Models\UserPlan;
use Illuminate\Database\Eloquent\Collection;

class UserPlanService
{
    use FileManagementTrait;

    public function getUserPlans($orderBy = 'sort_order', $order = 'asc')
    {
        return UserPlan::orderBy($orderBy, $order)->latest();
    }
    public function getUserplan(string $encryptedId): UserPlan | Collection
    {
        return UserPlan::findOrFail(decrypt($encryptedId));
        
    }
    public function getDeletedUserplane(string $encryptedId): UserPlan | Collection
    {
        return UserPlan::onlyTrashed()->findOrFail(decrypt($encryptedId));
    }

    public function delete(UserPlan $user): void
    {
        $user->update(['deleter_id' => admin()->id, 'deleter_type' => get_class(admin())]);
        $user->delete();
    }

    
    public function toggleStatus(UserPlan $user): void
    {
        $user->update([
            'status' => !$user->status,
            'updater_id' => admin()->id,
            'updater_type' => get_class(admin())
        ]);
    }

    // public function restore(string $encryptedId): void
    // {
    //     $user = $this->getDeletedUserplan($encryptedId);
    //     $user->update(['updater_id' => admin()->id],
    //         ['updater_type' => get_class(admin())]);
    //     $user->restore();
    // }

    // public function permanentDelete(string $encryptedId): void
    // {
    //     $user = $this->getDeletedUserplane($encryptedId);
    //     $user->forceDelete();
    // }

}