<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Display a listing of all notifications (paginated).
     */
    public function index()
    {
        $user = Auth::user();
        $notifications = $user->notifications()->paginate(20);

        return view('owner.notifications.index', compact('notifications'));
    }

    /**
     * Mark all unread notifications as read.
     */
    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();

        return redirect()->back()->with('success', 'सबै सूचनाहरू पढिसकेको रूपमा चिन्हित गरियो।');
    }

    /**
     * Get unread count as JSON (for AJAX polling / badge update).
     */
    public function unreadCount()
    {
        $count = auth()->user()->unreadNotifications()->count();

        return response()->json(['count' => $count]);
    }

    /**
     * Mark a single notification as read (called via AJAX before redirect).
     */
    public function markAsRead($id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        if (request()->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'सूचना पढिसकेको रूपमा चिन्हित गरियो।');
    }

    /**
     * Return the 5 most recent notifications as JSON (for dropdown refresh).
     */
    public function recent()
    {
        $notifications = auth()->user()->notifications()
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($notification) {
                return [
                    'id'          => $notification->id,
                    'data'        => $notification->data,
                    'read_at'     => $notification->read_at,
                    'created_at'  => $notification->created_at->diffForHumans(),
                ];
            });

        return response()->json($notifications);
    }
}
