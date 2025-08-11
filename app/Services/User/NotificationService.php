<?php

namespace App\Services\User;

use App\Models\CustomNotification;
use App\Models\CustomNotificationStatus;
use Illuminate\Database\Eloquent\Model;

class NotificationService
{
    /**
     * Create a new notification
     */
    public function createNotification(
        Model $sender,
        Model $receiver,
        int $type,
        array $messageData,
        ?string $url = null
    ): CustomNotification {
        return CustomNotification::create([
            'sender_id' => $sender->id,
            'sender_type' => get_class($sender),
            'receiver_id' => $receiver->id,
            'receiver_type' => get_class($receiver),
            'type' => $type,
            'url' => $url,
            'message_data' => $messageData,
        ]);
    }

    /**
     * Mark notification as read for a specific user
     */
    public function markAsRead(CustomNotification $notification, Model $user): void
    {
        CustomNotificationStatus::updateOrCreate([
            'notification_id' => $notification->id,
            'user_id' => $user->id,
            'user_type' => get_class($user),
        ], [
            'read_at' => now()
        ]);
    }

    /**
     * Mark notification as unread for a specific user
     */
    public function markAsUnread(CustomNotification $notification, Model $user): void
    {
        CustomNotificationStatus::updateOrCreate([
            'notification_id' => $notification->id,
            'user_id' => $user->id,
            'user_type' => get_class($user),
        ], [
            'read_at' => null
        ]);
    }

    /**
     * Get unread count for a user
     */
    public function getUnreadCount(Model $user): int
    {
        return CustomNotification::where('receiver_id', $user->id)
            ->where('receiver_type', get_class($user))
            ->whereDoesntHave('statuses', function ($query) use ($user) {
                $query->where('user_id', $user->id)
                    ->where('user_type', get_class($user))
                    ->whereNotNull('read_at');
            })
            ->count();
    }

    /**
     * Get notifications for a user with read status
     */
    public function getUserNotifications(Model $user, int $limit = 10)
    {
        return CustomNotification::with(['statuses' => function ($query) use ($user) {
            $query->where('user_id', $user->id)
                ->where('user_type', get_class($user));
        }])
            ->where('receiver_id', $user->id)
            ->where('receiver_type', get_class($user))
            ->latest()
            ->limit($limit)
            ->get();
    }

    /**
     * Check if notification is read by user
     */
    public function isReadByUser(CustomNotification $notification, Model $user): bool
    {
        $status = CustomNotificationStatus::where('notification_id', $notification->id)
            ->where('user_id', $user->id)
            ->where('user_type', get_class($user))
            ->first();

        return $status && $status->read_at;
    }
}
