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
        $query = CustomNotification::with(['statuses' => function ($q) {
            $q->forCurrentUser();
        }])
            // Use a single, outer `where` closure to group the private and public conditions.
            // This ensures the `whereDoesntHave` clause is applied to both.
            ->where(function ($query) {
                $query->where(function ($q) {
                    // Condition for private messages, intended for the current user
                    $q->where('receiver_id', $this->currentUserId)
                        ->where('receiver_type', $this->currentUserType);
                })
                    ->orWhere(function ($q) {
                        // Condition for public messages, intended for all users of this type
                        $q->where('receiver_id', null)
                            ->where('type', CustomNotification::TYPE_USER);
                    });
            })
            // Add a new clause to skip notifications that have been deleted by the current user
            // This now correctly checks if the notification DOES NOT have a 'deleteds' relationship entry
            // for the specific user and user type across both private and public notifications.
            ->whereDoesntHave('deleteds', function ($q) {
                $q->where('user_id', $this->currentUserId)
                    ->where('user_type', $this->currentUserType);
            });

        // Finalize the query by ordering and limiting the results
        $this->notifications = $query->latest()
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
