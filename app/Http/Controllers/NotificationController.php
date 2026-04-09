<?php

namespace App\Http\Controllers;

use App\Models\UserNotification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Full notifications page — /notifications
     */
    public function index()
    {
        $notifications = UserNotification::forUser(auth()->id())
            ->latest()
            ->paginate(20);

        // Mark all as read when user opens the full page
        UserNotification::forUser(auth()->id())
            ->unread()
            ->update(['read_at' => now()]);

        return view('notifications.index', compact('notifications'));
    }

    /**
     * Mark a single notification as read (called from dropdown click).
     * Returns a redirect back or JSON for AJAX use.
     */
    public function markRead(UserNotification $notification)
    {
        abort_unless($notification->user_id === auth()->id(), 403);

        $notification->markAsRead();

        if ($notification->url) {
            return redirect($notification->url);
        }

        return back();
    }

    /**
     * Mark all notifications as read — called from the "Mark all read" button.
     */
    public function markAllRead()
    {
        UserNotification::forUser(auth()->id())
            ->unread()
            ->update(['read_at' => now()]);

        return back()->with('success', 'All notifications marked as read.');
    }
}