<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notification;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = auth()->user()->notifications()
            ->orderBy('created_at', 'desc')
            ->paginate(50);

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => auth()->user()->notifications()->where('read_at', null)->count()
        ]);
    }

    public function markAsRead(Request $request, Notification $notification)
    {
        if ($notification->user_id !== auth()->id()) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }

        $notification->update(['read_at' => now()]);

        return response()->json([
            'message' => 'Notification marked as read',
            'notification' => $notification
        ]);
    }

    public function markAllAsRead(Request $request)
    {
        auth()->user()->notifications()
            ->where('read_at', null)
            ->update(['read_at' => now()]);

        return response()->json([
            'message' => 'All notifications marked as read'
        ]);
    }

    public function delete(Request $request, Notification $notification)
    {
        if ($notification->user_id !== auth()->id()) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }

        $notification->delete();

        return response()->json([
            'message' => 'Notification deleted successfully'
        ]);
    }

    public function clearAll(Request $request)
    {
        auth()->user()->notifications()->delete();

        return response()->json([
            'message' => 'All notifications cleared'
        ]);
    }

    public function getUnreadCount()
    {
        $count = auth()->user()->notifications()
            ->where('read_at', null)
            ->count();

        return response()->json([
            'unread_count' => $count
        ]);
    }

    public function getRecentNotifications()
    {
        $notifications = auth()->user()->notifications()
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return response()->json([
            'notifications' => $notifications
        ]);
    }

    public function getNotificationsByType(Request $request)
    {
        $request->validate([
            'type' => 'required|string'
        ]);

        $notifications = auth()->user()->notifications()
            ->where('type', $request->type)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'notifications' => $notifications,
            'type' => $request->type
        ]);
    }
}