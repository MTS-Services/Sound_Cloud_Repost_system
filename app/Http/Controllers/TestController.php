<?php

namespace App\Http\Controllers;

use App\Events\AdminNotificationSent;
use Illuminate\Http\Request;
use App\Events\UserNotificationSent;
use App\Models\Admin;
use App\Models\CustomNotification;
use App\Models\User;

class TestController extends Controller
{
    public function sendNotification(Request $request)
    {
        // Get input values from the request
        $userId = $request->input('user_id');
        $sendTo = $request->input('send_to', 'users');
        $message = $request->input('message', 'Hello from Laravel!');
        $description = $request->input('description', 'This is a notification.');

        $receiverId = null;
        $receiverType = null;
        $type = null;

        switch ($sendTo) {
            case 'users':
                $type = CustomNotification::TYPE_USER;
                $receiverType = User::class;
                if ($userId) {
                    $user = User::find($userId);
                    if (!$user) {
                        return redirect()->back()->with('error', 'User not found.');
                    }
                    $receiverId = $user->id;
                }
                break;

            case 'admins':
                $type = CustomNotification::TYPE_ADMIN;
                $receiverType = Admin::class;
                if ($userId) {
                    $admin = Admin::find($userId);
                    if (!$admin) {
                        return redirect()->back()->with('error', 'Admin not found.');
                    }
                    $receiverId = $admin->id;
                }
                break;
            default:
                $type = CustomNotification::TYPE_USER;
                $receiverType = null;
                $receiverId = null;
                break;
        }

        $title = $request->input('title', ($receiverId ? 'Private Notification' : 'Public Notification'));

        // Create the notification record in the database
        $notification = CustomNotification::create([
            'type' => $type,
            'receiver_id' => $receiverId,
            'receiver_type' => $receiverType,
            'message_data' => [
                'title' => $title,
                'icon' => 'bell-ring',
                'message' => $message,
                'description' => $description,
                'url' => null,
                'additional_data' => [
                    'userId' => $userId,
                    'sendTo' => $sendTo,
                ],
            ],
        ]);

        if ($sendTo === 'users') {
            broadcast(new UserNotificationSent($notification));
        }
        if ($sendTo === 'admins') {
            broadcast(new AdminNotificationSent($notification));
        }
        return redirect()->back()->with('success', 'Notification sent successfully!');
    }
}
