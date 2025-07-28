<?php

namespace Database\Seeders;

use App\Models\Campaign;
use App\Models\Order;
use App\Models\Track;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first();

        if (!$user) {
            $user = User::factory()->create();
        }
        
        Order::create([
    'user_urn' => 'urn:sc:users:1001',
    'credits' => 101,
    'amount' => 100,
          
            
            
           
        ]);
    }
}
