<?php

namespace Database\Seeders;


use App\Models\Faq;

use App\Models\User;

use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $user = User::first();

        // if (!$user) {
        //     $user = User::factory()->create();
        // }

        Faq::create([
            'faq_category_id' => 1,
            'question' => 'What is Campaign?',
            'description' => "Campaign is a feature where you can create a campaign and share your music with other users. You can also search for other campaigns and join them.",

        ]);

        Faq::create([
            'faq_category_id' => 2,
            'question' => 'What is Direct Repost Request?',
            'description' => "Direct Repost Request is a feature where you can request to repost your music on another campaign. You can also search for other campaigns and request to repost your music on them.",

        ]);
    }
}
