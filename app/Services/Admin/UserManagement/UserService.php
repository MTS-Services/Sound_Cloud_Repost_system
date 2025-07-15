<?php

namespace App\Services\Admin\UserManagement;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class UserService
{
    public function __construct()
    {
        
    }

    public function getUsers($orderBy = 'name', $order = 'asc')
    {
        $users = User::orderBy($orderBy, $order)->latest();
        return $users;
    }
    public function getuser(string $encryptedId): User | Collection
    {
        $user = User::where('id', decrypt($encryptedId))->first();
        return $user;
    }
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
