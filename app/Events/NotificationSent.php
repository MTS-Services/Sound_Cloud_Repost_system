<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NotificationSent implements ShouldBroadcast, ShouldQueue
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $userId;

    public function __construct($message, $userId = null)
    {
        $this->message = $message;
        $this->userId = $userId;
    }


    public function broadcastOn()
    {
        // For private user-specific notifications
        if ($this->userId) {
            return new PrivateChannel('user.' . $this->userId);
        }

        // For public notifications
        return new Channel('notifications');
    }

    public function broadcastAs()
    {
        return 'notification.sent';
    }

    public function broadcastWith()
    {
        return [
            'message' => $this->message,
            'timestamp' => now()->toISOString(),
        ];
    }
}
