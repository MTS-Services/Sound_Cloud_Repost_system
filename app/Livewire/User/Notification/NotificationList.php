<?php

namespace App\Livewire\User\Notification;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;
use App\Models\CustomNotification;
use App\Models\CustomNotificationStatus;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;

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
        $this->currentUserId = Auth::id();
        $this->currentUserType = Auth::user() ? get_class(Auth::user()) : null;
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
        $this->getNotificationsQuery()->delete();
        
        $this->dispatch('notifications-updated');
        $this->resetPage();
    }

    public function getNotificationsQuery()
    {
        $query = CustomNotification::with(['statuses' => function($q) {
                $q->where('user_id', $this->currentUserId)
                  ->where('user_type', $this->currentUserType);
            }])
            ->where('receiver_id', $this->currentUserId)
            ->where('receiver_type', $this->currentUserType);

        // Apply filter
        if ($this->filter === 'unread') {
            $query->whereDoesntHave('statuses', function($q) {
                $q->where('user_id', $this->currentUserId)
                  ->where('user_type', $this->currentUserType)
                  ->whereNotNull('read_at');
            });
        } elseif ($this->filter === 'read') {
            $query->whereHas('statuses', function($q) {
                $q->where('user_id', $this->currentUserId)
                  ->where('user_type', $this->currentUserType)
                  ->whereNotNull('read_at');
            });
        }

        // Apply search
        if ($this->search) {
            $query->where(function ($q) {
                $q->whereJsonContains('message_data->title', $this->search)
                  ->orWhereJsonContains('message_data->message', $this->search)
                  ->orWhere('type', 'like', '%' . $this->search . '%');
            });
        }

        // Apply sorting
        switch ($this->sort) {
            case 'oldest':
                $query->oldest();
                break;
            case 'unread':
                $query->leftJoin('custom_notification_statuses', function($join) {
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

    public function getUnreadCountProperty()
    {
        return CustomNotification::where('receiver_id', $this->currentUserId)
            ->where('receiver_type', $this->currentUserType)
            ->whereDoesntHave('statuses', function($query) {
                $query->where('user_id', $this->currentUserId)
                      ->where('user_type', $this->currentUserType)
                      ->whereNotNull('read_at');
            })
            ->count();
    }

    public function getReadCountProperty()
    {
        return CustomNotification::where('receiver_id', $this->currentUserId)
            ->where('receiver_type', $this->currentUserType)
            ->whereHas('statuses', function($query) {
                $query->where('user_id', $this->currentUserId)
                      ->where('user_type', $this->currentUserType)
                      ->whereNotNull('read_at');
            })
            ->count();
    }

    public function getTotalCountProperty()
    {
        return CustomNotification::where('receiver_id', $this->currentUserId)
            ->where('receiver_type', $this->currentUserType)
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