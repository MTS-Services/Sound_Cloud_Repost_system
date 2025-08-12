<?php

namespace Database\Factories;

use App\Models\Admin;
use App\Models\CustomNotification;
use App\Models\CustomNotificationStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CustomNotificationStatus>
 */
class CustomNotificationStatusFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CustomNotificationStatus::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $userModels = [
            User::class, 
            Admin::class,
        ];
        
        $userModelClass = $this->faker->randomElement($userModels);

        $user = $userModelClass::inRandomOrder()->first();

        $notification = CustomNotification::inRandomOrder()->first();

        if (!$user) {
            throw new \Exception('No users or admins found. Please seed the users and admins tables before running this factory.');
        }

        if (!$notification) {
            throw new \Exception('No custom notifications found. Please seed the custom_notifications table before running this factory.');
        }

        return [
            'user_id' => $user->id,
            'user_type' => get_class($user),
            'notification_id' => $notification->id,
            'read_at' => now(),
        ];
    }
    
    /**
     * Indicate that the notification status is unread.
     *
     * @return static
     */
    public function unread(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'read_at' => null,
            ];
        });
    }
}
