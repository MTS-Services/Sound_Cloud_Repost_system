<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PrivateMessageSent implements ShouldBroadcast
{
      // These traits give our event special powers.
    // 'Dispatchable' allows you to easily dispatch the event from your code.
    // 'InteractsWithSockets' helps with socket-based broadcasting.
    // 'SerializesModels' allows us to safely broadcast Eloquent models if needed.
    use Dispatchable, InteractsWithSockets, SerializesModels;

    // We'll declare public properties to hold the data we want to broadcast.
    // Making them public ensures they are automatically serialized and sent
    // with the event.
    public string $message;
    public string $timestamp;
    public int $recipientUserId;

    /**
     * This is the constructor. It's called when you create a new instance of this event.
     * @param string $message The content of the message.
     * @param string $senderName The name of the person who sent the message.
     * @param int $recipientUserId The ID of the user who should receive this notification.
     */
    public function __construct(string $message, int $recipientUserId)
    {
        // We set the event's properties using the values passed into the constructor.
        $this->message = $message;
        // We can also generate a timestamp right here when the event is created.
        $this->timestamp = now()->toDateTimeString();
        $this->recipientUserId = $recipientUserId;
    }

    /**
     * This method defines the channel(s) the event should be broadcast on.
     * For a private notification, we need to return a PrivateChannel instance.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        // The key part for a private notification is the 'PrivateChannel' class.
        // We're creating a channel name that is unique to a specific user.
        // We use string concatenation to build the channel name, like 'user.1', 'user.2', etc.
        // The front-end will need to listen for this exact channel name to receive the event.
        return [
            new PrivateChannel('user.' . $this->recipientUserId),
        ];
    }

    /**
     * This method specifies the name of the event as it will be received by the front-end.
     * It's good practice to use a custom name to avoid clashes.
     *
     * @return string
     */
    public function broadcastAs(): string
    {
        // This is the name the front-end JavaScript will use to listen for the event.
        // For example, `Echo.private(...).listen('.private-message.sent', ...)`
        return 'private-message.sent';
    }

    /**
     * This method defines the payload of data that will be broadcast with the event.
     * The front-end JavaScript will receive this data object.
     *
     * @return array<string, mixed>
     */
    public function broadcastWith(): array
    {
        // We return an associative array with the data we want to send.
        // These keys (e.g., 'message', 'sender', 'time') will be used on the front-end.
        return [
            'message' => $this->message,
            'time' => $this->timestamp,
        ];
    }
}
