<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomNotification;
use App\Models\CustomNotificationStatus;
use App\Models\NotificationStatus;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class NotificationController extends Controller
{
    /**
     * Display all notifications page with filtering and custom pagination
     */
    public function index(Request $request): View
    {
        $filterType = $request->get('filter', 'all');
        $perPage = min($request->get('per_page', 20), 50);

        // Get base query
        $query = $this->getNotificationsQuery();

        // Apply filters
        $query = $this->applyFilters($query, $filterType);

        // Get filtered notifications with pagination
        $notifications = $query->paginate($perPage);

        // Preserve filter in pagination links
        $notifications->appends($request->all());

        $unreadCount = $this->getNotificationsQuery()
            ->whereDoesntHave('statuses', function ($query) {
                $query->where('user_id', admin()->id)
                    ->where('user_type', get_class(admin()));
            })
            ->count();

        return view('backend.admin.notifications.all', compact('notifications', 'filterType', 'unreadCount'));
    }

    /**
     * Show notification details page
     */
    public function show($id)
    {
        $notification = CustomNotification::with(['statuses' => function ($query) {
            $query->where('user_id', admin()->id)
                ->where('user_type', get_class(admin()));
        }])
            ->where('id', $id)
            ->where(function ($query) {
                // Private notifications for this admin
                $query->where('receiver_id', admin()->id)
                    ->where('receiver_type', get_class(admin()));
            })
            ->orWhere(function ($query) use ($id) {
                // Public notifications for all admins
                $query->where('id', $id)
                    ->where('receiver_id', null)
                    ->where('type', CustomNotification::TYPE_ADMIN);
            })
            ->first();

        if (!$notification) {
            return redirect()->route('admin.notifications.index')
                ->with('error', 'Notification not found or access denied');
        }

        // Mark as read if not already
        $existingStatus = CustomNotificationStatus::where([
            'notification_id' => $id,
            'user_id' => admin()->id,
            'user_type' => get_class(admin())
        ])->first();

        if (!$existingStatus) {
            CustomNotificationStatus::create([
                'notification_id' => $id,
                'user_id' => admin()->id,
                'user_type' => get_class(admin()),
                'read_at' => now()
            ]);
        }

        return view('backend.admin.notifications.details', compact('notification'));
    }

    /**
     * Apply filters to the notifications query
     */
    private function applyFilters($query, string $filterType)
    {
        switch ($filterType) {
            case 'read':
                return $query->whereHas('statuses', function ($q) {
                    $q->where('user_id', admin()->id)
                        ->where('user_type', get_class(admin()));
                });

            case 'unread':
                return $query->whereDoesntHave('statuses', function ($q) {
                    $q->where('user_id', admin()->id)
                        ->where('user_type', get_class(admin()));
                });

            case 'private':
                return $query->where('receiver_id', admin()->id)
                    ->where('receiver_type', get_class(admin()));

            case 'public':
                return $query->where('receiver_id', null)
                    ->where('type', CustomNotification::TYPE_ADMIN);

            default:
                return $query; // All notifications
        }
    }

    /**
     * Get notifications for API/AJAX requests
     */
    public function getNotifications(Request $request): JsonResponse
    {
        $perPage = min($request->get('per_page', 15), 50);
        $page = $request->get('page', 1);

        $notifications = $this->getNotificationsQuery()
            ->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'success' => true,
            'data' => $notifications->items(),
            'meta' => [
                'current_page' => $notifications->currentPage(),
                'last_page' => $notifications->lastPage(),
                'per_page' => $notifications->perPage(),
                'total' => $notifications->total(),
                'from' => $notifications->firstItem(),
                'to' => $notifications->lastItem(),
            ]
        ]);
    }

    /**
     * Mark a single notification as read
     */
    public function markAsRead(Request $request)
    {
        $notificationId = $request->get('notification_id');

        $notification = CustomNotification::find($notificationId);

        if (!$notification) {
            return redirect()->back()->with('error', 'Notification not found');
        }

        // Check if already marked as read
        $existingStatus = CustomNotificationStatus::where([
            'notification_id' => $notificationId,
            'user_id' => admin()->id,
            'user_type' => get_class(admin())
        ])->first();

        if (!$existingStatus) {
            CustomNotificationStatus::create([
                'notification_id' => $notificationId,
                'user_id' => admin()->id,
                'user_type' => get_class(admin()),
                'read_at' => now()
            ]);
        }

        return redirect()->back()->with('success', 'Notification marked as read');
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        $notifications = $this->getNotificationsQuery()
            ->whereDoesntHave('statuses', function ($query) {
                $query->where('user_id', admin()->id)
                    ->where('user_type', get_class(admin()));
            })
            ->get();

        $statusData = [];
        foreach ($notifications as $notification) {
            $statusData[] = [
                'notification_id' => $notification->id,
                'user_id' => admin()->id,
                'user_type' => get_class(admin()),
                'read_at' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ];
        }

        if (!empty($statusData)) {
            CustomNotificationStatus::insert($statusData);
        }

        $message = count($statusData) > 0
            ? count($statusData) . ' notifications marked as read'
            : 'All notifications are already read';

        return redirect()->back()->with('success', $message);
    }

    /**
     * Delete a notification
     */
    public function destroy(Request $request)
    {
        $notificationId = $request->get('notification_id');

        $notification = CustomNotification::find($notificationId);

        if (!$notification) {
            return redirect()->back()->with('error', 'Notification not found');
        }

        // Only allow deletion if it's a private notification for this admin
        if (
            $notification->receiver_id !== admin()->id ||
            $notification->receiver_type !== get_class(admin())
        ) {
            return redirect()->back()->with('error', 'Unauthorized to delete this notification');
        }

        $notification->delete();

        return redirect()->back()->with('success', 'Notification deleted successfully');
    }

    /**
     * Get unread notifications count
     */
    public function getUnreadCount(): JsonResponse
    {
        $count = $this->getNotificationsQuery()
            ->whereDoesntHave('statuses', function ($query) {
                $query->where('user_id', admin()->id)
                    ->where('user_type', get_class(admin()));
            })
            ->count();

        return response()->json([
            'success' => true,
            'count' => $count
        ]);
    }

    /**
     * Common query for notifications
     */
    private function getNotificationsQuery()
    {
        return CustomNotification::with(['statuses' => function ($query) {
            $query->where('user_id', admin()->id)
                ->where('user_type', get_class(admin()));
        }])
            ->where(function ($query) {
                // Private notifications for this admin
                $query->where('receiver_id', admin()->id)
                    ->where('receiver_type', get_class(admin()));
            })
            ->orWhere(function ($query) {
                // Public notifications for all admins
                $query->where('receiver_id', null)
                    ->where('type', CustomNotification::TYPE_ADMIN);
            })
            ->latest();
    }
}
