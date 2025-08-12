<?php

namespace App\Livewire\User\Notification;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\CustomNotification;
use App\Models\CustomNotificationStatus;
use App\Models\User;

class NotificationPanel extends Component
{
    public $showPanel = false;
    public $notifications;
    public $maxDisplay = 15;
    public $currentUserId;
    public $currentUserType;

    public function mount()
    {
        $this->currentUserId = user()->id;
        $this->currentUserType = User::class;
        $this->loadNotifications();
    }

    #[On('notification-updated')]
    #[On('notification-deleted')]
    public function refreshNotifications()
    {
        $this->loadNotifications();
    }

    public function loadNotifications()
    {
        $this->notifications = CustomNotification::with(['statuses' => function ($query) {
            $query->where('user_id', $this->currentUserId)
                ->where('user_type', $this->currentUserType);
        }])
            ->where(function ($query) {
                // Condition one for private messages
                $query->where('receiver_id', $this->currentUserId)
                    ->where('receiver_type', $this->currentUserType);
            })
            ->orWhere(function ($query) {
                // Condition two for public messages
                $query->where('receiver_id', null)
                    ->where('type', CustomNotification::TYPE_USER);
            })
            ->latest()
            ->take($this->maxDisplay)
            ->get();
    }

    public function togglePanel()
    {
        $this->showPanel = !$this->showPanel;
    }

    public function closePanel()
    {
        $this->showPanel = false;
    }

    public function getUnreadCountProperty()
    {
        return CustomNotification::where(function ($query) {
            $query->where(function ($q) {
                $q->where('receiver_id', $this->currentUserId)
                    ->where('receiver_type', $this->currentUserType);
            })
                ->orWhere(function ($q) {
                    $q->where('receiver_id', null)
                        ->where('type', CustomNotification::TYPE_USER);
                });
        })
            ->whereDoesntHave('statuses', function ($q) {
                $q->where('user_id', $this->currentUserId)
                    ->where('user_type', $this->currentUserType)
                    ->whereNotNull('read_at');
            })
            ->count();
    }

    public function markAllAsRead()
    {
        $notificationIds = $this->notifications->pluck('id');

        foreach ($notificationIds as $notificationId) {
            CustomNotificationStatus::updateOrCreate([
                'notification_id' => $notificationId,
                'user_id' => $this->currentUserId,
                'user_type' => $this->currentUserType,
            ], [
                'read_at' => now()
            ]);
        }

        $this->loadNotifications();
        $this->dispatch('notifications-updated');
    }

    public function render()
    {
        return view('livewire.user.notification.notification-panel');
    }
}
