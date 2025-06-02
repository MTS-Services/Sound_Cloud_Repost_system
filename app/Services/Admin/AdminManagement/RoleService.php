<?php

namespace App\Services\Admin\AdminManagement;

use App\Models\Role;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class RoleService
{
    protected PermissionService $permissionService;
    public function __construct(PermissionService $permissionService)
    {
        $this->permissionService = $permissionService;
    }
    public function getRoles($orderBy = 'name', $order = 'asc')
    {
        return Role::orderBy($orderBy, $order)->latest();
    }

    public function getRole(string $encryptedId): Role | Collection
    {
        return Role::findOrFail(decrypt($encryptedId));
    }

    public function getDeletedRole(string $encryptedId): Role | Collection
    {
        return Role::onlyTrashed()->findOrFail(decrypt($encryptedId));
    }

    public function createRole(array $data): Role
    {
        return DB::transaction(function () use ($data) {
            $data['created_by'] = admin()->id;
            $data['guard_name'] = 'admin';
            $role = Role::create($data);
            if (isset($data['permissions'])) {
                $permissions = $this->permissionService->getPermissions()->whereIn('id', $data['permissions'])->pluck('name')->toArray();
                $role->givePermissionTo($permissions);
            }

            return $role;
        });
    }

    public function updateRole(Role $role, array $data): Role
    {
        return DB::transaction(function () use ($role, $data) {
            $data['updated_by'] = admin()->id;
            $data['guard_name'] = 'admin';
            $role->update($data);
            if (isset($data['permissions'])) {
                $permissions = $this->permissionService->getPermissions()->whereIn('id', $data['permissions'])->pluck('name')->toArray();
                $role->syncPermissions($permissions);
            }
            return $role;
        });
    }


    public function delete(string $encryptedId): Role
    {
        $role = $this->getRole($encryptedId);
        $role->update(['deleted_by' => admin()->id]);
        $role->delete();
        return $role;
    }

    public function restore(string $encryptedId): Role
    {
        $role = $this->getDeletedRole($encryptedId);
        $role->update(['updated_by' => admin()->id]);
        $role->restore();
        return $role;
    }
    public function permanentDelete(string $encryptedId): Role
    {
        $role = $this->getDeletedRole($encryptedId);
        $role->update(['updated_by' => admin()->id]);
        $role->restore();
        return $role;
    }
}
