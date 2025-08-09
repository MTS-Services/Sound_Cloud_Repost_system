<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\NotificationSent;

class TestController extends Controller
{
    public function sendNotification(Request $request)
    {
        $message = $request->input('message', 'Hello from Laravel!');
        $userId = $request->input('user_id'); // Optional for private notifica
        // Broadcast the event
        broadcast(new NotificationSent($message, $userId));

        return redirect()->back()->with('success', 'Notification sent successfully!');
    }
}
