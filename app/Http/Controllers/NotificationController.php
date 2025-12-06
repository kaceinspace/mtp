<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Display notifications page.
     */
    public function index(Request $request)
    {
        $filter = $request->get('filter', 'all');

        $notifications = Notification::forUser(auth()->id())
            ->with(['relatedUser', 'project'])
            ->when($filter === 'unread', fn($q) => $q->unread())
            ->when($filter === 'read', fn($q) => $q->read())
            ->latest()
            ->paginate(20);

        $unreadCount = NotificationService::getUnreadCount(auth()->id());

        return view('pages.notifications.index', compact('notifications', 'unreadCount', 'filter'));
    }

    /**
     * Get recent notifications for dropdown (AJAX).
     */
    public function recent()
    {
        $notifications = Notification::forUser(auth()->id())
            ->with(['relatedUser', 'project'])
            ->latest()
            ->take(10)
            ->get();

        $unreadCount = NotificationService::getUnreadCount(auth()->id());

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $unreadCount,
        ]);
    }

    /**
     * Mark single notification as read.
     */
    public function markAsRead(Notification $notification)
    {
        // Ensure user owns this notification
        if ($notification->user_id !== auth()->id()) {
            abort(403);
        }

        $notification->markAsRead();

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Notification marked as read',
            ]);
        }

        return redirect()->back()->with('success', 'Notification marked as read');
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllAsRead()
    {
        NotificationService::markAllAsRead(auth()->id());

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'All notifications marked as read',
            ]);
        }

        return redirect()->back()->with('success', 'All notifications marked as read');
    }

    /**
     * Delete a notification.
     */
    public function destroy(Notification $notification)
    {
        // Ensure user owns this notification
        if ($notification->user_id !== auth()->id()) {
            abort(403);
        }

        $notification->delete();

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Notification deleted',
            ]);
        }

        return redirect()->back()->with('success', 'Notification deleted');
    }

    /**
     * Get unread count (AJAX).
     */
    public function unreadCount()
    {
        return response()->json([
            'count' => NotificationService::getUnreadCount(auth()->id()),
        ]);
    }
}

