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
        Admin::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@dev.com',
            'password' => 'superadmin@dev.com',
        ]);
        Admin::create([
            'name' => 'Admin',
            'email' => 'admin@dev.com',
            'password' => 'admin@dev.com',
        ]);
    }
}
