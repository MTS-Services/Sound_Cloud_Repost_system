<?php

namespace Database\Seeders;


use App\Models\FaqCategory;



use Illuminate\Database\Seeder;

class FaqCategorySeeder extends Seeder
{

    /**
     * Run the database seeds.
     */
    public function run(): void
    {


        FaqCategory::create([
            'name' => 'Campaign',
            'slug' => 1,

        ]);

        FaqCategory::create([
            'name' => 'Direct Repost Request',
            'slug' => 2,

        ]);
    }
}
