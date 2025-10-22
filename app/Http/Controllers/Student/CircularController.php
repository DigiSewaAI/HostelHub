<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Circular;
use App\Models\CircularRecipient;
use App\Services\CircularService;
use Illuminate\Http\Request;

class CircularController extends Controller
{
    protected $circularService;

    public function __construct(CircularService $circularService)
    {
        $this->circularService = $circularService;
    }

    public function index(Request $request)
    {
        $user = auth()->user();

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
        $user = auth()->user();

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
        $user = auth()->user();

        $recipient = CircularRecipient::where('circular_id', $circular->id)
            ->where('user_id', $user->id)
            ->first();

        if ($recipient) {
            $recipient->markAsRead();
        }

        return response()->json(['success' => true]);
    }
}
