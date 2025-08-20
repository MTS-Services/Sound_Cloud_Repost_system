<?php

namespace App\Livewire\User\Notification;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;
use App\Models\CustomNotification;
use App\Models\CustomNotificationDeleted;
use App\Models\CustomNotificationStatus;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use App\Models\User;

class NotificationList extends Component
{
    use WithPagination;

    public $filter = 'all';
    public $perPage = 10;
    public $currentUserId;
    public $currentUserType;

    protected $queryString = [
        'filter' => ['except' => 'all'],
    ];

    public function mount()
    {
        // Use auth()->user() for consistency and to ensure the user object is available
        $user = Auth::user();
        if ($user) {
            $this->currentUserId = $user->id;
            $this->currentUserType = get_class($user);
        } else {
            // Handle case where user is not authenticated
            abort(403, 'Unauthorized');
        }
    }

    #[On('filter-changed')]
    public function updateFilter($filter)
    {
        $this->filter = $filter;
        $this->resetPage();
    }

    #[On('notification-updated')]
    #[On('notification-deleted')]
    public function refreshList()
    {
        // Component will automatically re-render
    }

    #[On('mark-all-as-read')]
    public function markAllAsRead()
    {
        $notifications = $this->getNotificationsQuery()->get();

        foreach ($notifications as $notification) {
            CustomNotificationStatus::updateOrCreate([
                'notification_id' => $notification->id,
                'user_id' => $this->currentUserId,
                'user_type' => $this->currentUserType,
            ], [
                'read_at' => now()
            ]);
        }

        $this->dispatch('notifications-updated');
    }

    #[On('clear-all-notifications')]
    public function clearAll()
    {
        // To properly clear all notifications, we should get their IDs and then delete them.
        // Direct deletion on the query builder might not be ideal if relationships need to be handled.
        $this->getNotificationsQuery()->pluck('id')->each(function ($id) {
            CustomNotificationDeleted::create([
                'notification_id' => $id,
                'user_id' => $this->currentUserId,
                'user_type' => $this->currentUserType,
            ]);;
        });

        $this->dispatch('notifications-updated');
        $this->resetPage();
    }

    public function getNotificationsQuery()
    {
        $query = CustomNotification::with(['statuses' => function ($q) {
            $q->where('user_id', $this->currentUserId)
                ->where('user_type', $this->currentUserType);
        }])->whereDoesntHave('deleteds', function ($q) {
            $q->where('user_id', $this->currentUserId)
                ->where('user_type', $this->currentUserType);
        });

        $query->where(function ($query) {
            // Main query: Get notifications for the current user (private) OR public notifications
            $query->where(function ($q) {
                // Condition one for private messages
                $q->where('receiver_id', $this->currentUserId)
                    ->where('receiver_type', $this->currentUserType);
            })
                ->orWhere(function ($q) {
                    // Condition two for public messages
                    $q->where('receiver_id', null)
                        ->where('type', CustomNotification::TYPE_USER);
                });
        });

        // Apply filter
        if ($this->filter === 'unread') {
            $query->whereDoesntHave('statuses', function ($q) {
                $q->where('user_id', $this->currentUserId)
                    ->where('user_type', $this->currentUserType)
                    ->whereNotNull('read_at');
            });
        } elseif ($this->filter === 'read') {
            $query->whereHas('statuses', function ($q) {
                $q->where('user_id', $this->currentUserId)
                    ->where('user_type', $this->currentUserType)
                    ->whereNotNull('read_at');
            });
        }

        return $query->latest();
    }

    public function getNotificationsProperty()
    {
        return $this->getNotificationsQuery()->paginate($this->perPage);
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

    public function getReadCountProperty()
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
            ->whereDoesntHave('deleteds', function ($q) {
                $q->where('user_id', $this->currentUserId)
                    ->where('user_type', $this->currentUserType);
            })
            ->whereHas('statuses', function ($q) {
                $q->where('user_id', $this->currentUserId)
                    ->where('user_type', $this->currentUserType)
                    ->whereNotNull('read_at');
            })
            ->count();
    }

    public function getTotalCountProperty()
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
            ->whereDoesntHave('deleteds', function ($q) {
                $q->where('user_id', $this->currentUserId)
                    ->where('user_type', $this->currentUserType);
            })
            ->count();
    }

    public function render()
    {
        return view('livewire.user.notification.notification-list', [
            'notifications' => $this->notifications,
            'unreadCount' => $this->unreadCount,
            'readCount' => $this->readCount,
            'totalCount' => $this->totalCount ?? 0,
        ]);
    }
}
