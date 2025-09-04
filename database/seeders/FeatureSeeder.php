<?php

namespace Database\Seeders;

use App\Models\Feature;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FeatureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [];

        foreach (Feature::getFeaturedNames() as $key => $name) {
            $data[] = [
                'name' => $name,
                'key' => $key,
                'type' => Feature::getFeatureValues()[$key][0] == 'True' ? Feature::TYPE_BOOLEAN : Feature::TYPE_STRING,
            ];
        }
        DB::table('features')->insert($data);
    }
}
