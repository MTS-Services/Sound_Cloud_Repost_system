<?php

namespace App\Services\Admin\PackageManagement;

use App\Models\Credit;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class CreditService
{
    public function getCredits($orderBy = 'name', $order = 'asc')
    {
        return Credit::orderBy($orderBy, $order)->latest();
    }
    public function getCredit(string $encryptedId): Credit | Collection
    {
        return Credit::findOrFail(decrypt($encryptedId));
    }
    public function getDeletedCredit(string $encryptedId): Credit | Collection
    {
        return Credit::onlyTrashed()->findOrFail(decrypt($encryptedId));
    }

    public function createCredit(array $data): Credit
    {
        return DB::transaction(function () use ($data) {
            $data['created_by'] = admin()->id;
            $credit = Credit::create($data);
            return $credit;
        });
    }

    public function updateCredit(Credit $credit, array $data): Credit
    {
        return DB::transaction(function () use ($credit, $data) {
            $data['updated_by'] = admin()->id;
            
            $credit->update($data);
            return $credit;
        });
    }

    public function delete(Credit $credit): void
    {
        $credit->update(['deleted_by' => admin()->id]);
        $credit->delete();
    }

    public function restore(string $encryptedId): void
    {
        $credit = $this->getDeletedCredit($encryptedId);
        $credit->update(['updated_by' => admin()->id]);
        $credit->restore();
    }

    public function permanentDelete(string $encryptedId): void
    {
        $credit = $this->getDeletedCredit($encryptedId);
        $credit->forceDelete();
    }

    public function toggleStatus(Credit $credit): void
    {
        $credit->update( [
            'status' => !$credit->status,
            'updated_by' => admin()->id
        ]);
    }
}
