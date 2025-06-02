<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $superadmin = Admin::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@dev.com',
            'password' => 'superadmin@dev.com',
            'role_id' => 1,
        ]);

        $superadmin->assignRole($superadmin->role->name);
        $admin = Admin::create([
            'name' => 'Admin',
            'email' => 'admin@dev.com',
            'password' => 'admin@dev.com',
            'role_id' => 2, 
        ]);

        $admin->assignRole($admin->role->name);
        $test = Admin::create([
            'name' => 'Test Admin',  
            'email' => 'testadmin@dev.com',
            'password' => 'testadmin@dev.com',
            'role_id' => 2,
        ]);

        $test->assignRole($test->role->name);
    }
}
