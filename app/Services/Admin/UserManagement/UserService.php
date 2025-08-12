<?php

namespace App\Services\Admin\UserManagement;

use App\Http\Traits\FileManagementTrait;
use App\Models\CreditTransaction;
use App\Models\CustomNotification;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class UserService
{
    use FileManagementTrait;

    public function getUsers($orderBy = 'name', $order = 'asc')
    {
        return User::orderBy($orderBy, $order)->latest();
    }
    public function getUser(string $value, string $field = 'id'): User | Collection
    {
        return User::where($field, decrypt($value))->with('userInfo')->first();
    }
    public function getMyAccountUser(): User | Collection
    {
        return User::where('urn', user()->urn)->with('userInfo')->first();
    }
    public function getDeletedUser(string $encryptedId): User | Collection
    {
        return User::onlyTrashed()->findOrFail(decrypt($encryptedId));
    }

    public function createUser(array $data, $file = null): User
    {
        return DB::transaction(function () use ($data, $file) {
            if ($file) {
                $data['image'] = $this->handleFileUpload($file,  'admins', $data['name']);
            }
            $data['created_by'] = admin()->id;
            $user = User::create($data);
            return $user;
        });
    }

    public function updateUser(User $user, array $data, $file = null): User
    {
        return DB::transaction(function () use ($user, $data, $file) {
            if ($file) {
                $data['image'] = $this->handleFileUpload($file,  'users', $data['name']);
                $this->fileDelete($user->image);
            }
            $data['updater_id'] = admin()->id;
            $data['updater_type'] = get_class(admin());
            $user->update($data);
            return $user;
        });
    }

    public function delete(User $user): void
    {
        $user->update(['deleter_id' => admin()->id, 'deleter_type' => get_class(admin())]);
        $user->delete();
    }

    public function restore(string $encryptedId): void
    {
        $user = $this->getDeletedUser($encryptedId);
        $user->update(
            ['updater_id' => admin()->id],
            ['updater_type' => get_class(admin())]
        );
        $user->restore();
    }

    public function permanentDelete(string $encryptedId): void
    {
        $user = $this->getDeletedUser($encryptedId);
        $user->forceDelete();
    }

    public function toggleStatus(User $user): void
    {
        $user->update([
            'status' => !$user->status,
            'updater_id' => admin()->id,
            'updater_type' => get_class(admin())
        ]);
    }

    public function addCredit(User $user, array $data)
    {
        return DB::transaction(function () use ($user, $data) {
            $credit['transaction_type'] = CreditTransaction::TYPE_MANUAL;
            $credit['calculation_type'] = CreditTransaction::CALCULATION_TYPE_DEBIT;
            $credit['receiver_urn'] = $user->urn;
            $credit['credits'] = $data['credit'];
            $credit['amount'] = 0;
            $credit['status'] = 'succeeded';
            $credit['creater_id'] = admin()->id;
            $credit['creater_type'] = get_class(admin());
            $credit['description'] = $data['description'] ?? 'Manual credit addition by ' . admin()->name;
            $credit['source_id'] = admin()->id;
            $credit['source_type'] = get_class(admin());
            CreditTransaction::create($credit);

            return CustomNotification::create([
                'type' => CustomNotification::TYPE_USER,
                'sender_id' => admin()->id,
                'sender_type' => get_class(admin()),
                'receiver_id' => $user->id,
                'receiver_type' => User::class,
                'message_data' => [
                    'title' => 'Credit Added',
                    'message' => 'Credit added successfully!',
                    'description' => 'You have received ' . $data['credit'] . ' credits from ' . admin()->name,
                    'icon' => 'currency-dollar',
                    'additional_data' => []
                ]
            ]);
        });
    }
}
