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
     * Display all notifications page
     */
    public function index(Request $request): View
    {
        $notifications = $this->getNotificationsQuery()
            ->paginate(20)
            ->withQueryString();

        return view('backend.admin.all-notifications', compact('notifications'));
    }

    /**
     * Get notifications for API/AJAX requests
     */
    public function getNotifications(Request $request): JsonResponse
    {
        $perPage = min($request->get('per_page', 15), 50); // Max 50 per page
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
    public function markAsRead(Request $request): JsonResponse
    {
        $notificationId = $request->get('notification_id');
        
        $notification = CustomNotification::find($notificationId);
        
        if (!$notification) {
            return response()->json([
                'success' => false,
                'message' => 'Notification not found'
            ], 404);
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

        return response()->json([
            'success' => true,
            'message' => 'Notification marked as read'
        ]);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead(): JsonResponse
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

        return response()->json([
            'success' => true,
            'message' => 'All notifications marked as read',
            'count' => count($statusData)
        ]);
    }

    /**
     * Delete a notification
     */
    public function destroy(Request $request): JsonResponse
    {
        $notificationId = $request->get('notification_id');
        
        $notification = CustomNotification::find($notificationId);
        
        if (!$notification) {
            return response()->json([
                'success' => false,
                'message' => 'Notification not found'
            ], 404);
        }

        // Only allow deletion if it's a private notification for this admin
        if ($notification->receiver_id !== admin()->id || 
            $notification->receiver_type !== get_class(admin())) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized to delete this notification'
            ], 403);
        }

        $notification->delete();

        return response()->json([
            'success' => true,
            'message' => 'Notification deleted successfully'
        ]);
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