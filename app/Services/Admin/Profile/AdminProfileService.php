<?php

namespace App\Services\Admin\Profile;

use App\Models\Admin;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Http\Traits\FileManagementTrait;
use Illuminate\Database\Eloquent\Collection;

class AdminProfileService
{
    use FileManagementTrait;
     public function getAdminProfile(string $encryptedId): Admin | Collection
    {
        return Admin::findOrFail(decrypt($encryptedId));
    }
    public function updateProfile(Admin $admin, array $data, $file = null): Admin
    {
        return DB::transaction(function () use ($admin, $data, $file) {
            // Handle image upload
            if ($file) {
                $data['image'] = $this->handleFileUpload($file, 'profiles', $data['name']);
                $this->fileDelete($admin->image);
            }
            if (!empty($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            } else {
                unset($data['password']);
            }

            // Add updated_by field
            $data['updated_by'] = admin()->id;

            $admin->update($data);

            return $admin;
        });
    }
}
