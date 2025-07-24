<?php

namespace Database\Seeders;

use App\Models\Credit;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CreditSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Credit::create([
            'name' => 'Credits',
            'price' => 100,
            'credits' => 100,
        ]);
        Credit::create([
            'name' => 'Credits1',
            'price' => 1000,
            'credits' => 1000,
        ]);
        Credit::create([
            'name' => 'Credits2',
            'price' => 2000,
            'credits' => 2000,
        ]);
    }
}
