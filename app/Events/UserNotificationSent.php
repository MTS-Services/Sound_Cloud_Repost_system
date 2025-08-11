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

class UserNotificationSent implements ShouldBroadcast, ShouldQueue
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $title;
    public $message;
    public $userId;

    public function __construct(string $title, string $message, $userId = null)
    {
        $this->title = $title;
        $this->message = $message;
        $this->userId = $userId;
    }


    public function broadcastOn()
    {
        if ($this->userId) {
            return new PrivateChannel('user.' . $this->userId);
        }
        return new Channel('users');
    }
    public function broadcastAs()
    {
        return 'notification.sent';
    }

    public function broadcastWith()
    {
        return [
            'title' => $this->title,
            'message' => $this->message,
            'timestamp' => timeFormatHuman(now()),
        ];
    }
}
