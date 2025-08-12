<?php

namespace Database\Factories;

use App\Models\Admin;
use App\Models\CustomNotification;
use App\Models\CustomNotificationStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

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
        // Randomly determine the type of receiver: 'public', 'user', or 'admin'
        // We check if users and admins exist to avoid errors.
        $hasUsers = User::count() > 0;
        $hasAdmins = Admin::count() > 0;

        $options = ['public'];
        if ($hasUsers) {
            $options[] = 'user';
        }
        if ($hasAdmins) {
            $options[] = 'admin';
        }

        $receiverTypeChoice = $this->faker->randomElement($options);

        $receiverId = null;
        $receiverType = null;
        $type = null;

        switch ($receiverTypeChoice) {
            case 'user':
                $user = User::inRandomOrder()->first();
                if ($user) {
                    $receiverId = $user->id;
                    $receiverType = User::class;
                    $type = CustomNotification::TYPE_USER;
                }
                break;

            case 'admin':
                $admin = Admin::inRandomOrder()->first();
                if ($admin) {
                    $receiverId = $admin->id;
                    $receiverType = Admin::class;
                    $type = CustomNotification::TYPE_ADMIN;
                }
                break;

            case 'public':
            default:
                $receiverId = null;
                $receiverType = null;
                // For a public notification, the `type` can be for either a user or an admin, so we choose randomly.
                $type = $this->faker->randomElement([CustomNotification::TYPE_USER, CustomNotification::TYPE_ADMIN]);
                break;
        }

        return [
            'receiver_id' => $receiverId,
            'receiver_type' => $receiverType,
            'type' => $type,
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
