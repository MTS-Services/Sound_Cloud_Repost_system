<?php

namespace Database\Seeders;

use App\Models\Feature;
use App\Models\FeatureRelation;
use App\Models\Plan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PlanSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Basic Plan',
                'yearly_price_save' => 20,
                'tag' => null,
                'notes' => 'Entry level features',
            ],
            [
                'name' => 'Pro Plan',
                'yearly_price_save' => 20,
                'tag' => 1,
                'notes' => 'Professional tools and reports',
            ],
            [
                'name' => 'Enterprise Plan',
               'yearly_price_save' => 30,
                'tag' => 2,
                'notes' => 'Best for companies and teams',
            ],
        ];

        foreach ($plans as $planData) {
            $plan = Plan::create([
                ...$planData,
                'slug' => Str::slug($planData['name']),
            ]);

            // Attach some features
            $features = Feature::inRandomOrder()->limit(3)->get();

            foreach ($features as $feature) {
                FeatureRelation::create([
                    'package_id' => $plan->id,
                    'package_type' => Plan::class,
                    'feature_id' => $feature->id,
                    'value' => $feature->type === 1 ? '1' : 'Unlimited', // if boolean type, set "1"
                ]);
            }
        }
    }
}
