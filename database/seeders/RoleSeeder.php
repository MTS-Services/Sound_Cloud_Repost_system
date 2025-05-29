<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            1 => 'Super Admin',
            2 => 'Admin',
        ];

        foreach ($roles as $roleId => $roleName) {
            Role::create(['id' => $roleId, 'name' => $roleName, 'guard_name' => 'admin']);
        }
    }
}
