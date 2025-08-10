<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\NotificationSent;
use App\Events\PrivateMessageSent;
use App\Events\SayHi;

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

    public function sayHi()
    {
        event(new SayHi('Hello from Laravel!'));
        return redirect()->back()->with('success', 'Notification sent successfully!');
    }

    public function sendPrivateMessage(Request $request)
    {
        // Assuming you have a way to get the sender's name (e.g., from the authenticated user).
        // $senderName = auth()->user()->name;
        $message = $request->input('message');
        $recipientId = $request->input('recipient_id');

        // Dispatch the new event with the data.
        // The event will handle building the channel and payload for us.
        event(new PrivateMessageSent($message, $recipientId));

        return response()->json(['status' => 'Message sent!']);
    }
}
