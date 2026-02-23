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
        $user = auth()->user();

        // Base query: published circulars जुन यो user को recipients मा छ
        $query = Circular::published()
            ->whereHas('recipients', function ($q) use ($user) {
                $q->where('user_id', $user->id);
                // ✅ deleted_at हटाइयो
            })
            ->with(['organization', 'hostel', 'recipients' => function ($q) use ($user) {
                $q->where('user_id', $user->id);
            }]);

        // Filters
        if ($request->filled('read_status')) {
            $isRead = $request->read_status === 'read';
            $query->whereHas('recipients', function ($q) use ($user, $isRead) {
                $q->where('user_id', $user->id)
                    ->where('is_read', $isRead);
            });
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('content', 'like', '%' . $request->search . '%');
            });
        }

        // Pagination
        $circulars = $query->orderBy('published_at', 'desc')
            ->paginate(15);

        // ✅ Statistics सही गर्ने - same query conditions प्रयोग गर्ने
        $baseStatsQuery = Circular::published()
            ->whereHas('recipients', function ($q) use ($user) {
                $q->where('user_id', $user->id);
                // ✅ deleted_at हटाइयो
            });

        $stats = [
            'total' => $baseStatsQuery->count(),
            'read' => (clone $baseStatsQuery)
                ->whereHas('recipients', function ($q) use ($user) {
                    $q->where('user_id', $user->id)
                        ->where('is_read', true);
                })->count(),
            'unread' => (clone $baseStatsQuery)
                ->whereHas('recipients', function ($q) use ($user) {
                    $q->where('user_id', $user->id)
                        ->where('is_read', false);
                })->count(),
            'urgent_count' => (clone $baseStatsQuery)
                ->where('priority', 'urgent')
                ->whereHas('recipients', function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                })->count(),
        ];

        return view('student.circulars.index', compact('circulars', 'stats'));
    }

    /**
     * Display the specified circular and mark as read.
     */
    public function show(Circular $circular)
    {
        $user = auth()->user();

        // Check if user has access to this circular
        $recipient = $circular->recipients()
            ->where('user_id', $user->id)
            // ✅ deleted_at हटाइयो
            ->first();

        if (!$recipient) {
            abort(403, 'तपाईंसँग यो सूचना हेर्ने अनुमति छैन।');
        }

        // Mark as read if not already read
        if (!$recipient->is_read) {
            $recipient->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
        }

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
