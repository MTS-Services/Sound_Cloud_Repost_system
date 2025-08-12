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
          'question' => 'What is Soundcloud?',
          'description' => 'Soundcloud is a social music platform ',
          
        ]);

        Faq::create([
          'faq_category_id' => 2,
          'question' => ' Soundcloud?',
          'description' => ' a social music platform ',
         
        ]);
    }
}
