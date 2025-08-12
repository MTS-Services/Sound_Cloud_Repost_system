<?php

namespace App\Livewire\User\Notification;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;
use App\Models\CustomNotification;
use App\Models\CustomNotificationStatus;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use App\Models\User;

class NotificationList extends Component
{
    use WithPagination;

    public $filter = 'all';
    public $search = '';
    public $sort = 'newest';
    public $perPage = 10;
    public $currentUserId;
    public $currentUserType;

    protected $queryString = [
        'filter' => ['except' => 'all'],
        'search' => ['except' => ''],
        'sort' => ['except' => 'newest'],
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

    #[On('search-changed')]
    public function updateSearch($search)
    {
        $this->search = $search;
        $this->resetPage();
    }

    #[On('sort-changed')]
    public function updateSort($sort)
    {
        $this->sort = $sort;
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
            CustomNotification::find($id)?->delete();
        });

        $this->dispatch('notifications-updated');
        $this->resetPage();
    }

    public function getNotificationsQuery()
    {
        $query = CustomNotification::with(['statuses' => function ($q) {
            $q->where('user_id', $this->currentUserId)
                ->where('user_type', $this->currentUserType);
        }]);

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

        // Apply search
        if ($this->search) {
            $query->where(function ($q) {
                // Use `->` and a standard LIKE query to search within JSON string values
                $q->where('message_data->title', 'like', '%' . $this->search . '%')
                    ->orWhere('message_data->message', 'like', '%' . $this->search . '%')
                    ->orWhere('type', 'like', '%' . $this->search . '%');
            });
        }

        // Apply sorting
        switch ($this->sort) {
            case 'oldest':
                $query->oldest();
                break;
            case 'unread':
                // Join is necessary for sorting by unread status
                $query->leftJoin('custom_notification_statuses', function ($join) {
                    $join->on('custom_notifications.id', '=', 'custom_notification_statuses.notification_id')
                        ->where('custom_notification_statuses.user_id', $this->currentUserId)
                        ->where('custom_notification_statuses.user_type', $this->currentUserType);
                })
                    ->orderByRaw('custom_notification_statuses.read_at IS NULL DESC')
                    ->orderBy('custom_notifications.created_at', 'desc')
                    ->select('custom_notifications.*');
                break;
            case 'type':
                $query->orderBy('type')->latest();
                break;
            default:
                $query->latest();
                break;
        }

        return $query;
    }

    public function getNotificationsProperty()
    {
        return $this->getNotificationsQuery()->paginate($this->perPage);
    }

    // ADDED: The `where` and `orWhere` clauses are now nested correctly within a main `where` to ensure all conditions are applied correctly.
    // The previous implementation was applying `whereDoesntHave` and `whereHas` after the `orWhere`, which would not apply to public notifications.
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
            ->whereHas('statuses', function ($q) {
                $q->where('user_id', $this->currentUserId)
                    ->where('user_type', $this->currentUserType)
                    ->whereNotNull('read_at');
            })
            ->count();
    }

    public function getTotalCountProperty()
    {
        // This is a direct count of all notifications relevant to the user, with both private and public conditions.
        return CustomNotification::where(function ($query) {
            $query->where('receiver_id', $this->currentUserId)
                ->where('receiver_type', $this->currentUserType);
        })
            ->orWhere(function ($query) {
                $query->where('receiver_id', null)
                    ->where('type', CustomNotification::TYPE_USER);
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
