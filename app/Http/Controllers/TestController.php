<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\UserNotificationSent;
use App\Models\CustomNotification;
use App\Models\User;

class TestController extends Controller
{
    public function sendNotification(Request $request)
    {
        $userId = $request->input('user_id') ? $request->input('user_id') : null;
        $message = $request->input('message', 'Hello from Laravel!');
        $description = $request->input('description', 'This is a ' . ($userId ? 'private' : 'public') . ' notification.');

        $notification = CustomNotification::create([
            'type' => CustomNotification::TYPE_USER,
            'receiver_id' => $userId,
            'receiver_type' => $userId ? User::class : null,
            'message_data' => [
                'title' => $userId ? 'Private Notification' : 'Public Notification',
                'message' => $message,
                'description' => $description,
                'url' => null,
                'additional_data' => [],
                'icon' => 'envelope',
            ],
        ]);

        broadcast(new UserNotificationSent($notification));

        return redirect()->back()->with('success', 'Notification sent successfully!');
    }
}
