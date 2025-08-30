<?php

namespace App\Livewire\User\Notification;

use Livewire\Component;
use App\Models\CustomNotification;
use App\Models\CustomNotificationDeleted;
use App\Models\CustomNotificationStatus;
use App\Models\User;
use App\Services\User\NotificationService;
use Illuminate\Support\Facades\Auth;

class NotificationShow extends Component
{
    public CustomNotification $customNotification;
    public $currentUserId;
    public $currentUserType;
    public $isRead = false;

    protected $notificationService;

    public function boot(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function mount(string $encryptedId)
    {
        $this->currentUserId = user()->id;
        $this->currentUserType = User::class;

        $notification = CustomNotification::where('id', decrypt($encryptedId))
            ->where(function ($query) {
                $query->where(function ($q) {
                    $q->where('receiver_id', $this->currentUserId)
                        ->where('receiver_type', $this->currentUserType);
                })
                    ->orWhere(function ($q) {
                        $q->where('receiver_id', null)
                            ->where('type', CustomNotification::TYPE_USER);
                    });
            })
            ->first();
        if (!$notification) {
            return $this->redirect(back()->with('error', 'Notification not found.'), navigate: true);
        }
        $this->customNotification = $notification;

        // Load the notification with statuses
        $this->customNotification->load(['statuses' => function ($query) {
            $query->where('user_id', $this->currentUserId)
                ->where('user_type', $this->currentUserType);
        }]);

        // Check if read
        $this->checkReadStatus();

        // Auto-mark as read when viewing
        $this->markAsRead();
    }

    public function checkReadStatus()
    {
        $status = CustomNotificationStatus::where('notification_id', $this->customNotification->id)
            ->where('user_id', $this->currentUserId)
            ->where('user_type', $this->currentUserType)
            ->first();

        $this->isRead = $status && $status->read_at;
    }

    public function markAsRead()
    {
        if (!$this->isRead) {
            $this->notificationService->markAsRead($this->customNotification, Auth::user());
            $this->isRead = true;
            $this->dispatch('notification-updated', $this->customNotification->id);
        }
    }

    public function toggleRead()
    {
        if ($this->isRead) {
            $this->notificationService->markAsUnread($this->customNotification, Auth::user());
            $this->isRead = false;
        } else {
            $this->notificationService->markAsRead($this->customNotification, Auth::user());
            $this->isRead = true;
        }

        $this->dispatch('notification-updated', $this->customNotification->id);
    }

    public function deleteNotification()
    {
        CustomNotificationDeleted::create([
            'notification_id' => $this->customNotification->id,
            'user_id' => $this->currentUserId,
            'user_type' => $this->currentUserType,
        ]);
        $this->dispatch('notification-deleted', $this->customNotification->id);
        $this->dispatch('alert', 'success', 'Notification deleted successfully.');

        return $this->redirect(route('user.notifications.index'), navigate: true);
    }

    public function getNotificationTitle()
    {
        return $this->customNotification->message_data['title'] ?? 'Notification';
    }

    public function getNotificationMessage()
    {
        return $this->customNotification->message_data['message'] ?? 'No message content available.';
    }
    public function getNotificationDescription()
    {
        return $this->customNotification->message_data['description'] ?? 'No description content available.';
    }

    public function getNotificationIcon()
    {
        return $this->customNotification->message_data['icon'] ?? 'home';
    }

    public function getTypeLabel()
    {
        return $this->customNotification->receiver_id === null ? 'Public' : 'Private';
    }

    public function getTypeColor()
    {
        return $this->customNotification->type === CustomNotification::TYPE_ADMIN
            ? 'bg-gradient-to-br from-red-100 to-red-200 text-red-700 dark:from-red-900/30 dark:to-red-800/30 dark:text-red-400'
            : 'bg-gradient-to-br from-blue-100 to-blue-200 text-blue-700 dark:from-blue-900/30 dark:to-blue-800/30 dark:text-blue-400';
    }

    public function render()
    {
        return view('livewire.user.notification.notification-show');
    }
}
