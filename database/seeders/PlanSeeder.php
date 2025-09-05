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
                'name' => 'Free Plan',
                'monthly_price' => 0,
                'tag' => null,
                'notes' => 'Free forever',
            ],
            [
                'name' => 'Premium Plan',
                'monthly_price' => 25,
                'tag' => Plan::TAG_MOST_POPULAR,
                'notes' => 'All-inclusive premium features',
            ]
        ];

        foreach ($plans as $key => $planData) {
            $plan = Plan::create([
                ...$planData
            ]);

            $features = Feature::all();

            foreach ($features as $feature) {
                FeatureRelation::create([
                    'package_id' => $plan->id,
                    'package_type' => Plan::class,
                    'feature_id' => $feature->id,
                    'value' => Feature::getFeatureValues()[$feature->key][$key],
                ]);
            }
        }
    }
}
