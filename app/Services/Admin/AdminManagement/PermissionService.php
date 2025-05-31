<?php

namespace App\Services\Admin\AdminManagement;

use App\Models\Permission;
use Illuminate\Database\Eloquent\Collection;

class PermissionService
{
    public function getPermissions($orderBy = 'name', $order = 'asc')
    {
        return Permission::orderBy($orderBy, $order)->latest();
    }

    public function getPermission($encryptedId): Permission | Collection
    {
        return Permission::findOrFail(decrypt($encryptedId));
    }
    public function getDeletedPermission($encryptedId): Permission | Collection
    {
        return Permission::onlyTrashed()->findOrFail(decrypt($encryptedId));
    }

    public function createPermission(array $data): Permission
    {
        $data['created_by'] = admin()->id;
        $data['guard_name'] = 'admin';
        return Permission::create($data);
    }

    public function updatePermission(Permission $permission, array $data): Permission
    {
        $data['updated_by'] = admin()->id;
        $data['guard_name'] = 'admin';
        $permission->update($data);
        return $permission;
    }

    public function delete(string $encryptedId): Permission
    {
        $permission = $this->getPermission($encryptedId);
        $permission->update(['deleted_by' => admin()->id]);
        $permission->delete();
        return $permission;
    }

    public function   restore(string $encryptedId): Permission
    {
        $permission = $this->getDeletedPermission($encryptedId);
        $permission->update(['updated_by' => admin()->id]);
        $permission->restore();
        return $permission;
    }

    public function permanentDelete(string $encryptedId): void
    {
        $permission = $this->getDeletedPermission($encryptedId);
        $permission->forceDelete();
    }
}
