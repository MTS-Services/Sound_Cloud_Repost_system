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
        $message = $request->input('message', 'Hello from Laravel!');
        $userId = $request->input('user_id') ? $request->input('user_id') : null;

        CustomNotification::create([
            'type' => CustomNotification::TYPE_USER,
            'receiver_id' => $userId,
            'receiver_type' => $userId ? User::class : null,
            'message_data' => [
                'title' => 'Public Notification',
                'message' => $message,
                'icon' => 'envelope',
            ],
        ]);

        broadcast(new UserNotificationSent('Public Notification', $message, $userId));

        return redirect()->back()->with('success', 'Notification sent successfully!');
    }
}
