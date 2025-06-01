<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleHasPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permisions = Permission::count();

        for ($i = 1; $i <= $permisions; $i++) {
            DB::table('role_has_permissions')->insert([
                'permission_id' => $i,
                'role_id' => 1,
            ]);
            DB::table('role_has_permissions')->insert([
                'permission_id' => $i,
                'role_id' => 2,
            ]);
        }
    }
}
