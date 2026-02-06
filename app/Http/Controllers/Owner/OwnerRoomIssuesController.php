<?php

namespace App\Http\Controllers\Owner;

use App\Models\RoomIssue;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class OwnerRoomIssuesController extends Controller
{
    /**
     * Constructor - Apply owner middleware
     */
    public function __construct()
    {
        $this->middleware(['auth', 'role:owner,hostel_manager']);
    }

    /**
     * Get hostel IDs for the authenticated owner
     */
    private function getOwnerHostelIds()
    {
        $user = Auth::user();

        // Get all organizations where user is owner
        $organizations = $user->organizations()
            ->wherePivot('role', 'owner')
            ->get();

        if ($organizations->isEmpty()) {
            return collect();
        }

        // Get all hostel IDs from all organizations
        $hostelIds = collect();
        foreach ($organizations as $organization) {
            $hostelIds = $hostelIds->merge($organization->hostels()->pluck('id'));
        }

        return $hostelIds->unique();
    }

    /**
     * Display a listing of room issues
     */
    public function index(Request $request)
    {
        try {
            $user = Auth::user();

            // Get owner's hostel IDs
            $hostelIds = $this->getOwnerHostelIds();

            if ($hostelIds->isEmpty()) {
                return view('owner.room_issues.index', [
                    'issues' => collect(),
                    'message' => 'तपाईंको होस्टेल फेला परेन',
                    'stats' => [
                        'total' => 0,
                        'pending' => 0,
                        'resolved' => 0,
                        'high_priority' => 0,
                        'today' => 0
                    ],
                    'hostels' => collect()
                ]);
            }

            // Build query with owner's hostel filter
            $query = RoomIssue::whereIn('hostel_id', $hostelIds)
                ->with(['hostel', 'room', 'student.user'])
                ->latest();

            // Apply filters using when() for cleaner code
            $query->when($request->filled('status') && $request->status != 'all', function ($q) use ($request) {
                $q->where('status', $request->status);
            });

            $query->when($request->filled('priority') && $request->priority != 'all', function ($q) use ($request) {
                $q->where('priority', $request->priority);
            });

            $query->when($request->filled('hostel_id') && $request->hostel_id != 'all', function ($q) use ($request) {
                $q->where('hostel_id', $request->hostel_id);
            });

            // Get paginated results
            $issues = $query->paginate(15)->withQueryString();

            // Calculate statistics
            $stats = [
                'total' => RoomIssue::whereIn('hostel_id', $hostelIds)->count(),
                'pending' => RoomIssue::whereIn('hostel_id', $hostelIds)
                    ->where('status', 'pending')->count(),
                'resolved' => RoomIssue::whereIn('hostel_id', $hostelIds)
                    ->where('status', 'resolved')->count(),
                'high_priority' => RoomIssue::whereIn('hostel_id', $hostelIds)
                    ->where('priority', 'high')->count(),
                'today' => RoomIssue::whereIn('hostel_id', $hostelIds)
                    ->whereDate('created_at', today())->count()
            ];

            // Get hostels for filter dropdown
            $hostels = \App\Models\Hostel::whereIn('id', $hostelIds)
                ->select('id', 'name')
                ->get();

            return view('owner.room_issues.index', compact('issues', 'stats', 'hostels'));
        } catch (\Exception $e) {
            Log::error('रूम समस्या लोड गर्दा त्रुटि: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'error' => $e
            ]);

            return view('owner.room_issues.index', [
                'issues' => collect(),
                'stats' => [
                    'total' => 0,
                    'pending' => 0,
                    'resolved' => 0,
                    'high_priority' => 0,
                    'today' => 0
                ],
                'hostels' => collect(),
                'error' => 'डाटा लोड गर्न असफल भयो। कृपया पछि प्रयास गर्नुहोस्।'
            ]);
        }
    }

    /**
     * Show specific room issue
     */
    public function show($id)
    {
        try {
            $user = Auth::user();

            // Get owner's organization
            $organization = $user->organizations()
                ->wherePivot('role', 'owner')
                ->first();

            if (!$organization) {
                return redirect()->route('owner.room-issues.index')
                    ->with('error', 'तपाईंको संस्था फेला परेन।');
            }

            $hostelIds = $organization->hostels()->pluck('id');

            // यो तरिकाले issue लिनुहोस्:
            $issue = RoomIssue::whereIn('hostel_id', $hostelIds)
                ->with(['hostel', 'room', 'student.user'])
                ->find($id);

            if (!$issue) {
                return redirect()->route('owner.room-issues.index')
                    ->with('error', 'रूम समस्या फेला परेन वा पहुँच छैन।');
            }

            return view('owner.room_issues.show', compact('issue'));
        } catch (\Exception $e) {
            return redirect()->route('owner.room-issues.index')
                ->with('error', 'त्रुटि: ' . $e->getMessage());
        }
    }

    /**
     * Update room issue status
     */
    public function update(Request $request, $id)
    {
        try {
            $user = Auth::user();

            // Get owner's organization
            $organization = $user->organizations()
                ->wherePivot('role', 'owner')
                ->first();

            if (!$organization) {
                return redirect()->back()
                    ->with('error', 'तपाईंसँग यो समस्या अद्यावधिक गर्ने अनुमति छैन');
            }

            $hostelIds = $organization->hostels()->pluck('id');

            $issue = RoomIssue::whereIn('hostel_id', $hostelIds)->find($id);

            if (!$issue) {
                return redirect()->back()
                    ->with('error', 'रूम समस्या फेला परेन वा पहुँच छैन।');
            }

            $validated = $request->validate([
                'status' => 'required|in:pending,processing,resolved,closed',
                'resolution_notes' => 'nullable|string|max:1000',
                'assigned_to' => 'nullable|string|max:255'
            ]);

            // Simple update
            $issue->update([
                'status' => $validated['status'],
                'resolution_notes' => $validated['resolution_notes'] ?? null,
                'assigned_to' => $validated['assigned_to'] ?? null,
            ]);

            return redirect()->back()
                ->with('success', 'समस्या स्थिति सफलतापूर्वक अद्यावधिक गरियो।');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'त्रुटि: ' . $e->getMessage());
        }
    }

    /**
     * Delete a room issue
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        try {
            $user = Auth::user();

            // Get owner's organization and hostels
            $organization = $user->organizations()
                ->wherePivot('role', 'owner')
                ->first();

            if (!$organization) {
                abort(403, 'तपाईंसँग यो समस्या मेटाउने अनुमति छैन');
            }

            $hostelIds = $organization->hostels()->pluck('id');

            $issue = RoomIssue::whereIn('hostel_id', $hostelIds)->findOrFail($id);
            $issue->delete();

            // Log activity
            Log::info('रूम समस्या मेटाइयो', [
                'issue_id' => $issue->id,
                'user_id' => $user->id
            ]);

            return redirect()->route('owner.room-issues.index')
                ->with('success', 'समस्या सफलतापूर्वक मेटाइयो।');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('owner.room-issues.index')
                ->with('error', 'रूम समस्या फेला परेन वा तपाईंसँग पहुँच छैन।');
        } catch (\Exception $e) {
            Log::error('रूम समस्या मेटाउँदा त्रुटि: ' . $e->getMessage(), [
                'issue_id' => $id,
                'user_id' => Auth::id()
            ]);

            return redirect()->route('owner.room-issues.index')
                ->with('error', 'समस्या मेटाउन असफल भयो।');
        }
    }

    /**
     * Get room issues statistics for dashboard
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStats()
    {
        try {
            $user = Auth::user();

            // Get owner's organization
            $organization = $user->organizations()
                ->wherePivot('role', 'owner')
                ->first();

            if (!$organization) {
                return response()->json([
                    'total' => 0,
                    'pending' => 0,
                    'today' => 0
                ]);
            }

            $hostelIds = $organization->hostels()->pluck('id');

            $stats = [
                'total' => RoomIssue::whereIn('hostel_id', $hostelIds)->count(),
                'pending' => RoomIssue::whereIn('hostel_id', $hostelIds)
                    ->where('status', 'pending')->count(),
                'today' => RoomIssue::whereIn('hostel_id', $hostelIds)
                    ->whereDate('created_at', today())->count()
            ];

            return response()->json($stats);
        } catch (\Exception $e) {
            Log::error('रूम समस्या तथ्याङ्क लोड गर्दा त्रुटि: ' . $e->getMessage());

            return response()->json([
                'total' => 0,
                'pending' => 0,
                'today' => 0
            ]);
        }
    }
}
