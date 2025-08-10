<?php

namespace Database\Factories;

use App\Models\Admin;
use App\Models\CustomNotification;
use App\Models\CustomNotificationStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CustomNotification>
 */
class CustomNotificationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'receiver_id' => User::inRandomOrder()->first()->id,
            'receiver_type' => User::class,
            'type' => CustomNotification::TYPE_USER,
            'message_data' => [
                'title' => $this->faker->sentence(3),
                'message' => $this->faker->paragraph(2),
                'icon' => 'home',
                'additional_data' => [
                    'key1' => 'value1',
                    'key2' => 'value2',
                ],
            ],
        ];
    }
}
