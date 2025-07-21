<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FeatureCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('feature_categories')->insert([
            [
                'name' => 'Core Features'
            ],
            [
                'name' => 'Campaign Features'
            ],
            [
                'name' => 'Other Features'
            ],
        ]);
    }
}
