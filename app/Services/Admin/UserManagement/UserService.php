<?php

namespace App\Services\Admin\UserManagement;

use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserService
{
    public function createAdmin(array $data, $file = null): User
    {
        return DB::transaction(function () use ($data, $file) {
            $data['creater_id'] = user()->id;
            $data['creater_type'] = get_class(user());
            $user = User::create($data);
            return $user;
        });
    }
}
