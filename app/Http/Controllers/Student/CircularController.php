<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Circular;
use App\Models\CircularRecipient;
use App\Services\CircularService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // ✅ ADDED

class CircularController extends Controller
{
    protected $circularService;

    public function __construct(CircularService $circularService)
    {
        $this->circularService = $circularService;
    }

    // ✅ ENHANCED: Student authorization for circular access
    private function authorizeCircularAccess(Circular $circular = null)
    {
        $user = Auth::user();

        // ✅ ADDED: Ensure user is a student
        if (!$user->hasRole('student')) {
            abort(403, 'तपाईंसँग यो सर्कुलर हेर्ने अनुमति छैन');
        }

        // ✅ ADDED: Check specific circular access
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

    public function index(Request $request)
    {
        $user = Auth::user();

        // ✅ ENHANCED: Authorization check
        $this->authorizeCircularAccess();

        $circularIds = CircularRecipient::where('user_id', $user->id)
            ->pluck('circular_id');

        $query = Circular::whereIn('id', $circularIds)
            ->published()
            ->active()
            ->with(['creator', 'organization']);

        // Filter by read status
        if ($request->has('read_status')) {
            $readCircularIds = CircularRecipient::where('user_id', $user->id)
                ->where('is_read', $request->read_status === 'read')
                ->pluck('circular_id');
            $query->whereIn('id', $readCircularIds);
        }

        // Search
        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $circulars = $query->latest()->paginate(15);

        return view('student.circulars.index', compact('circulars'));
    }

    public function show(Circular $circular)
    {
        $user = Auth::user();

        // ✅ ENHANCED: Authorization check
        $this->authorizeCircularAccess($circular);

        // Verify the student has access to this circular
        $recipient = CircularRecipient::where('circular_id', $circular->id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        // Mark as read
        if (!$recipient->is_read) {
            $recipient->markAsRead();
        }

        return view('student.circulars.show', compact('circular', 'recipient'));
    }

    public function markAsRead(Circular $circular)
    {
        $user = Auth::user();

        // ✅ ENHANCED: Authorization check
        $this->authorizeCircularAccess($circular);

        $recipient = CircularRecipient::where('circular_id', $circular->id)
            ->where('user_id', $user->id)
            ->first();

        if ($recipient) {
            $recipient->markAsRead();
        }

        return response()->json(['success' => true]);
    }

    // ✅ ADDED: New method to get circular statistics
    public function getStatistics()
    {
        $user = Auth::user();

        // ✅ ENHANCED: Authorization check
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

    // ✅ ADDED: Method to mark all circulars as read
    public function markAllAsRead()
    {
        $user = Auth::user();

        // ✅ ENHANCED: Authorization check
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

    // ✅ ADDED: Method to get unread circulars count for notifications
    public function getUnreadCount()
    {
        $user = Auth::user();

        // ✅ ENHANCED: Authorization check
        $this->authorizeCircularAccess();

        $unreadCount = CircularRecipient::where('user_id', $user->id)
            ->where('is_read', false)
            ->count();

        return response()->json([
            'unread_count' => $unreadCount
        ]);
    }

    // ✅ ADDED: Method to filter circulars by priority
    public function filterByPriority(Request $request, $priority)
    {
        $user = Auth::user();

        // ✅ ENHANCED: Authorization check
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
            ->latest()
            ->paginate(15);

        return view('student.circulars.index', compact('circulars'))
            ->with('current_priority', $priority);
    }
}
