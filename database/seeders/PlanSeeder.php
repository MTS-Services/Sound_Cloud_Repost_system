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
                'name' => 'Free Forever',
                'monthly_price' => 0,
                'yearly_price_save' => 0,
                'tag' => null,
                'notes' => 'Free plan',
            ],
            [
                'name' => 'Artist',
                'monthly_price' => 10,
                'yearly_price_save' => 20,
                'tag' => null,
                'notes' => 'Perfect for individual creators',
            ],
            [
                'name' => 'Network',
                'monthly_price' => 20,
                'yearly_price_save' => 25,
                'tag' => 1,
                'notes' => 'For small teams and collaborations',
            ],
            [
                'name' => 'Promoter',
                'monthly_price' => 30,
                'yearly_price_save' => 30,
                'tag' => 2,
                'notes' => 'Promote content effectively',
            ],
            [
                'name' => 'Ultimate',
                'monthly_price' => 50,
                'yearly_price_save' => 50,
                'tag' => 3,
                'notes' => 'All-inclusive premium features',
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
                    'value' => $feature->type === 1 ? '1' : 'Unlimited',
                ]);
            }
        }
    }
}
