<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Circular;
use App\Models\CircularRecipient;
use App\Services\CircularService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CircularController extends Controller
{
    protected $circularService;

    public function __construct(CircularService $circularService)
    {
        $this->circularService = $circularService;
    }

    // ✅ Student authorization helper (can be replaced with Policy later)
    private function authorizeCircularAccess(Circular $circular = null)
    {
        $user = Auth::user();

        if (!$user->hasRole('student')) {
            abort(403, 'तपाईंसँग यो सर्कुलर हेर्ने अनुमति छैन');
        }

        if ($circular) {
            $hasAccess = CircularRecipient::where('circular_id', $circular->id)
                ->where('user_id', $user->id)
                ->exists();

            if (!$hasAccess) {
                abort(403, 'तपाईंसँग यो सर्कुलर हेर्ने अनुमति छैन');
            }
        }

        return true;
    }

    /**
     * Display a listing of circulars for the student.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        $this->authorizeCircularAccess();

        // Get circular IDs where user is recipient
        $circularIds = CircularRecipient::where('user_id', $user->id)
            ->pluck('circular_id');

        // Build query with eager loading to avoid N+1
        $query = Circular::whereIn('id', $circularIds)
            ->published()
            ->active()
            ->with(['creator', 'organization'])

            // ✅ FIXED: Eager load recipients with filter for current user only
            ->with(['recipients' => function ($q) use ($user) {
                $q->where('user_id', $user->id);
            }]);

        // Filter by read status
        if ($request->has('read_status')) {
            $readStatus = $request->read_status === 'read';
            $readCircularIds = CircularRecipient::where('user_id', $user->id)
                ->where('is_read', $readStatus)
                ->pluck('circular_id');
            $query->whereIn('id', $readCircularIds);
        }

        // Search
        if ($request->has('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('title', 'like', '%' . $searchTerm . '%')
                    ->orWhere('content', 'like', '%' . $searchTerm . '%');
            });
        }

        $circulars = $query->latest()->paginate(15);

        return view('student.circulars.index', compact('circulars'));
    }

    /**
     * Display the specified circular.
     */
    public function show(Circular $circular)
    {
        $user = Auth::user();

        $this->authorizeCircularAccess($circular);

        // Fetch recipient record (already authorized)
        $recipient = CircularRecipient::where('circular_id', $circular->id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        // Mark as read if not already
        if (!$recipient->is_read) {
            $recipient->markAsRead();
        }

        // Load relationships for view
        $circular->load(['creator', 'organization']);

        return view('student.circulars.show', compact('circular', 'recipient'));
    }

    /**
     * Mark a circular as read via AJAX.
     */
    public function markAsRead(Circular $circular)
    {
        $user = Auth::user();

        $this->authorizeCircularAccess($circular);

        $recipient = CircularRecipient::where('circular_id', $circular->id)
            ->where('user_id', $user->id)
            ->first();

        if ($recipient && !$recipient->is_read) {
            $recipient->markAsRead();
        }

        return response()->json(['success' => true]);
    }

    /**
     * Get circular statistics for the student.
     */
    public function getStatistics()
    {
        $user = Auth::user();

        $this->authorizeCircularAccess();

        $totalCirculars = CircularRecipient::where('user_id', $user->id)->count();
        $unreadCirculars = CircularRecipient::where('user_id', $user->id)
            ->where('is_read', false)
            ->count();
        $readCirculars = CircularRecipient::where('user_id', $user->id)
            ->where('is_read', true)
            ->count();

        return response()->json([
            'total_circulars' => $totalCirculars,
            'unread_circulars' => $unreadCirculars,
            'read_circulars' => $readCirculars,
            'unread_percentage' => $totalCirculars > 0 ? round(($unreadCirculars / $totalCirculars) * 100, 2) : 0
        ]);
    }

    /**
     * Mark all circulars as read for the student.
     */
    public function markAllAsRead()
    {
        $user = Auth::user();

        $this->authorizeCircularAccess();

        $updated = CircularRecipient::where('user_id', $user->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json([
            'success' => true,
            'message' => "{$updated} वटा सर्कुलरहरू पढिएको रूपमा चिन्ह लगाइयो",
            'marked_count' => $updated
        ]);
    }

    /**
     * Get unread circular count for notifications.
     */
    public function getUnreadCount()
    {
        $user = Auth::user();

        $this->authorizeCircularAccess();

        $unreadCount = CircularRecipient::where('user_id', $user->id)
            ->where('is_read', false)
            ->count();

        return response()->json([
            'unread_count' => $unreadCount
        ]);
    }

    /**
     * Filter circulars by priority.
     */
    public function filterByPriority(Request $request, $priority)
    {
        $user = Auth::user();

        $this->authorizeCircularAccess();

        $validPriorities = ['urgent', 'normal', 'info'];
        if (!in_array($priority, $validPriorities)) {
            abort(400, 'अमान्य प्राथमिकता प्रकार');
        }

        $circularIds = CircularRecipient::where('user_id', $user->id)
            ->pluck('circular_id');

        $circulars = Circular::whereIn('id', $circularIds)
            ->where('priority', $priority)
            ->published()
            ->active()
            ->with(['creator', 'organization'])
            // ✅ Eager load recipients with filter
            ->with(['recipients' => function ($q) use ($user) {
                $q->where('user_id', $user->id);
            }])
            ->latest()
            ->paginate(15);

        return view('student.circulars.index', compact('circulars'))
            ->with('current_priority', $priority);
    }
}
