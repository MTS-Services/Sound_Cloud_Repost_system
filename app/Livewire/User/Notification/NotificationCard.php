<?php

namespace App\Livewire\User\Notification;

use Livewire\Component;
use App\Models\CustomNotification;
use App\Models\CustomNotificationDeleted;
use App\Models\CustomNotificationStatus;
use Illuminate\Support\Facades\Auth;

class NotificationCard extends Component
{
    public CustomNotification $notification;
    public $showActions = true;
    public $compact = false;
    public $currentUserId;
    public $currentUserType;


    public function mount(CustomNotification $notification, $showActions = true, $compact = false)
    {
        $this->notification = $notification;
        $this->showActions = $showActions;
        $this->compact = $compact;
        $this->currentUserId = Auth::id();
        $this->currentUserType = Auth::user() ? get_class(Auth::user()) : null;
    }

    public function toggleRead()
    {
        $status = $this->getOrCreateStatus();

        if ($status->read_at) {
            $status->update(['read_at' => null]);
        } else {
            $status->update(['read_at' => now()]);
        }

        $this->dispatch('notification-updated', $this->notification->id);
    }

    public function markAsRead()
    {
        $status = $this->getOrCreateStatus();

        if (!$status->read_at) {
            $status->update(['read_at' => now()]);
            $this->dispatch('notification-updated', $this->notification->id);
        }
    }

    public function deleteNotification()
    {
        CustomNotificationDeleted::create([
            'notification_id' => $this->notification->id,
            'user_id' => $this->currentUserId,
            'user_type' => $this->currentUserType,
        ]);
        $this->dispatch('notification-deleted', $this->notification->id);
    }

    public function openDetail()
    {
        $this->markAsRead();
        // if ($this->notification->url) {
        //     return $this->redirect($this->notification->url, navigate: true);
        // }
        return $this->redirect(route('user.notifications.show', encrypt($this->notification->id)), navigate: true);
    }

    private function getOrCreateStatus()
    {
        return CustomNotificationStatus::firstOrCreate([
            'notification_id' => $this->notification->id,
            'user_id' => $this->currentUserId,
            'user_type' => $this->currentUserType,
        ]);
    }

    public function getIsReadProperty()
    {
        $status = CustomNotificationStatus::where('notification_id', $this->notification->id)
            ->where('user_id', $this->currentUserId)
            ->where('user_type', $this->currentUserType)
            ->first();

        return $status && $status->read_at;
    }

    public function getTypeColor()
    {
        $colors = [
            0 => 'bg-gradient-to-br from-blue-100 to-blue-200 text-blue-700 dark:from-blue-900/30 dark:to-blue-800/30 dark:text-blue-400', // User
            1 => 'bg-gradient-to-br from-red-100 to-red-200 text-red-700 dark:from-red-900/30 dark:to-red-800/30 dark:text-red-400', // Admin
        ];

        return $colors[$this->notification->type] ?? $colors[0];
    }

    public function getNotificationTitle()
    {
        return $this->notification->message_data['title'] ?? 'Notification';
    }

    public function getNotificationMessage()
    {
        return $this->notification->message_data['message'] ?? 'No message content';
    }

    public function getNotificationIcon()
    {
        return $this->notification->message_data['icon'] ?? 'home';
    }

    public function getNotificationTime()
    {
        return $this->notification->created_at->diffForHumans();
    }

    public function getTypeLabel()
    {
        return $this->notification->receiver_id === null  ? 'Public' : 'Private';
    }

    public function render()
    {
        return view('livewire.user.notification.notification-card');
    }
}
