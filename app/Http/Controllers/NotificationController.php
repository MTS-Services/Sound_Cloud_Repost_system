<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CustomNotification;
use App\Models\CustomNotificationStatus;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = Auth::user();
        $perPage = $request->get('per_page', 10);
        $search = $request->get('search');
        $filter = $request->get('filter', 'all'); // all, read, unread

        // Get notifications for the current user
        $query = CustomNotification::with(['sender', 'statuses' => function($q) use ($user) {
            $q->where('user_id', $user->id)->where('user_type', get_class($user));
        }])
        ->where(function($q) use ($user) {
            // Public notifications (no specific receiver)
            $q->whereNull('receiver_id')
              ->whereNull('receiver_type')
              // Or notifications specifically for this user
              ->orWhere(function($subQ) use ($user) {
                  $subQ->where('receiver_id', $user->id)
                       ->where('receiver_type', get_class($user));
              });
        })
        ->orderBy('created_at', 'desc');

        // Apply search filter
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->whereJsonContains('message_data->title', $search)
                  ->orWhereJsonContains('message_data->message', $search);
            });
        }

        // Apply read/unread filter
        if ($filter === 'read') {
            $query->whereHas('statuses', function($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->where('user_type', get_class($user))
                  ->whereNotNull('read_at');
            });
        } elseif ($filter === 'unread') {
            $query->where(function($q) use ($user) {
                $q->whereDoesntHave('statuses', function($subQ) use ($user) {
                    $subQ->where('user_id', $user->id)
                         ->where('user_type', get_class($user));
                })->orWhereHas('statuses', function($subQ) use ($user) {
                    $subQ->where('user_id', $user->id)
                         ->where('user_type', get_class($user))
                         ->whereNull('read_at');
                });
            });
        }

        $notifications = $query->paginate($perPage);

        // Format notifications for frontend
        $formattedNotifications = $notifications->getCollection()->map(function($notification) use ($user) {
            $status = $notification->statuses->first();
            return [
                'id' => $notification->id,
                'title' => $notification->message_data['title'] ?? 'Notification',
                'message' => $notification->message_data['message'] ?? '',
                'icon' => $notification->message_data['icon'] ?? 'bell',
                'url' => $notification->url,
                'is_read' => $status && $status->read_at ? true : false,
                'created_at' => $notification->created_at->diffForHumans(),
                'created_at_formatted' => $notification->created_at->format('M j, Y g:i A'),
            ];
        });

        return response()->json([
            'success' => true,
            'notifications' => $formattedNotifications,
            'pagination' => [
                'current_page' => $notifications->currentPage(),
                'last_page' => $notifications->lastPage(),
                'per_page' => $notifications->perPage(),
                'total' => $notifications->total(),
                'has_more' => $notifications->hasMorePages(),
            ]
        ]);
    }

    public function markAsRead(Request $request, $notificationId): JsonResponse
    {
        $user = Auth::user();
        
        $notification = CustomNotification::find($notificationId);
        if (!$notification) {
            return response()->json(['success' => false, 'message' => 'Notification not found'], 404);
        }

        // Check if status already exists
        $status = CustomNotificationStatus::where('notification_id', $notificationId)
            ->where('user_id', $user->id)
            ->where('user_type', get_class($user))
            ->first();

        if ($status) {
            $status->update(['read_at' => now()]);
        } else {
            CustomNotificationStatus::create([
                'notification_id' => $notificationId,
                'user_id' => $user->id,
                'user_type' => get_class($user),
                'read_at' => now(),
            ]);
        }

        return response()->json(['success' => true]);
    }

    public function markAsUnread(Request $request, $notificationId): JsonResponse
    {
        $user = Auth::user();
        
        CustomNotificationStatus::where('notification_id', $notificationId)
            ->where('user_id', $user->id)
            ->where('user_type', get_class($user))
            ->update(['read_at' => null]);

        return response()->json(['success' => true]);
    }

    public function markAllAsRead(Request $request): JsonResponse
    {
        $user = Auth::user();
        
        // Get all notifications for this user
        $notifications = CustomNotification::where(function($q) use ($user) {
            $q->whereNull('receiver_id')
              ->whereNull('receiver_type')
              ->orWhere(function($subQ) use ($user) {
                  $subQ->where('receiver_id', $user->id)
                       ->where('receiver_type', get_class($user));
              });
        })->get();

        foreach ($notifications as $notification) {
            CustomNotificationStatus::updateOrCreate([
                'notification_id' => $notification->id,
                'user_id' => $user->id,
                'user_type' => get_class($user),
            ], [
                'read_at' => now(),
            ]);
        }

        return response()->json(['success' => true]);
    }

    public function getUnreadCount(): JsonResponse
    {
        $user = Auth::user();
        
        $unreadCount = CustomNotification::where(function($q) use ($user) {
            $q->whereNull('receiver_id')
              ->whereNull('receiver_type')
              ->orWhere(function($subQ) use ($user) {
                  $subQ->where('receiver_id', $user->id)
                       ->where('receiver_type', get_class($user));
              });
        })
        ->where(function($q) use ($user) {
            $q->whereDoesntHave('statuses', function($subQ) use ($user) {
                $subQ->where('user_id', $user->id)
                     ->where('user_type', get_class($user));
            })->orWhereHas('statuses', function($subQ) use ($user) {
                $subQ->where('user_id', $user->id)
                     ->where('user_type', get_class($user))
                     ->whereNull('read_at');
            });
        })
        ->count();

        return response()->json(['unread_count' => $unreadCount]);
    }

    public function delete(Request $request, $notificationId): JsonResponse
    {
        $user = Auth::user();
        
        $notification = CustomNotification::find($notificationId);
        if (!$notification) {
            return response()->json(['success' => false, 'message' => 'Notification not found'], 404);
        }

        // Delete the notification status for this user
        CustomNotificationStatus::where('notification_id', $notificationId)
            ->where('user_id', $user->id)
            ->where('user_type', get_class($user))
            ->delete();

        return response()->json(['success' => true]);
    }
}
