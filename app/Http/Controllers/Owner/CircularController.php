<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Circular;
use App\Models\Hostel;
use App\Models\Student;
use App\Models\CircularRecipient; // ✅ ADDED missing import
use App\Http\Requests\StoreCircularRequest;
use App\Services\CircularService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth; // ✅ ADDED for security

class CircularController extends Controller
{
    protected $circularService;

    public function __construct(CircularService $circularService)
    {
        $this->circularService = $circularService;
    }

    // ✅ ADDED: Owner authorization helper method
    private function authorizeOwnerAccess($organizationId = null)
    {
        $user = Auth::user();

        if ($user->hasRole('hostel_manager')) {
            if ($organizationId) {
                $userOrganization = $user->organizations()->first();
                if (!$userOrganization || $userOrganization->id != $organizationId) {
                    abort(403, 'तपाईंसँग यो संस्थाको डाटा एक्सेस गर्ने अनुमति छैन');
                }
            }

            // Additional hostel-based authorization
            if (!$user->hostel_id) {
                abort(403, 'तपाईंसँग कुनै होस्टल सम्बन्धित छैन');
            }
        }

        return true;
    }

    public function index(Request $request)
    {
        $user = auth()->user();

        // ✅ ENHANCED: Owner authorization
        if ($user->hasRole('hostel_manager')) {
            $this->authorizeOwnerAccess();
        }

        $organization = $user->organizations()->first();

        // ✅ ENHANCED: Data scoping for owners
        $query = Circular::with(['organization', 'creator'])
            ->where('organization_id', $organization->id);

        // Filter by status
        if ($request->has('status') && in_array($request->status, ['draft', 'published', 'archived'])) {
            $query->where('status', $request->status);
        }

        // Filter by priority
        if ($request->has('priority') && in_array($request->priority, ['urgent', 'normal', 'info'])) {
            $query->where('priority', $request->priority);
        }

        $circulars = $query->latest()->paginate(20);

        return view('owner.circulars.index', compact('circulars'));
    }

    public function create()
    {
        $user = auth()->user();

        // ✅ ENHANCED: Owner authorization
        if ($user->hasRole('hostel_manager')) {
            $this->authorizeOwnerAccess();
        }

        $organization = $user->organizations()->first();

        $hostels = $organization->hostels;
        $students = $organization->students()->with('user')->get();

        return view('owner.circulars.create', compact('hostels', 'students'));
    }

    public function store(StoreCircularRequest $request)
    {
        $user = auth()->user();

        // ✅ ENHANCED: Owner authorization
        if ($user->hasRole('hostel_manager')) {
            $this->authorizeOwnerAccess();
        }

        DB::transaction(function () use ($request) {
            $user = auth()->user();
            $organization = $user->organizations()->first();
            $data = $request->validated();

            $data['organization_id'] = $organization->id;
            $data['created_by'] = $user->id;

            // Create circular
            $circular = Circular::create($data);

            // Create recipients
            $this->circularService->createRecipients(
                $circular,
                $data['audience_type'],
                $data['target_audience'] ?? null
            );

            // Auto-publish if not scheduled
            if (!$circular->scheduled_at) {
                $circular->markAsPublished();
            }
        });

        $message = $request->has('scheduled_at')
            ? 'सूचना सफलतापूर्वक सिर्जना गरियो र तोकिएको समयमा प्रकाशित हुनेछ'
            : 'सूचना सफलतापूर्वक प्रकाशित गरियो';

        return redirect()->route('owner.circulars.index')->with('success', $message);
    }

    public function show(Circular $circular)
    {
        $user = auth()->user();

        // ✅ ENHANCED: Owner authorization
        if ($user->hasRole('hostel_manager')) {
            $this->authorizeOwnerAccess($circular->organization_id);
        }

        $this->circularService->authorizeView($circular, $user);

        $circular->load(['organization', 'creator', 'recipients.user']);
        $readStats = $this->circularService->getReadStats($circular);

        return view('owner.circulars.show', compact('circular', 'readStats'));
    }

    public function edit(Circular $circular)
    {
        $user = auth()->user();

        // ✅ ENHANCED: Owner authorization
        if ($user->hasRole('hostel_manager')) {
            $this->authorizeOwnerAccess($circular->organization_id);
        }

        $this->circularService->authorizeEdit($circular, $user);

        $organization = $user->organizations()->first();

        $hostels = $organization->hostels;
        $students = $organization->students()->with('user')->get();

        return view('owner.circulars.edit', compact('circular', 'hostels', 'students'));
    }

    public function update(StoreCircularRequest $request, Circular $circular)
    {
        $user = auth()->user();

        // ✅ ENHANCED: Owner authorization
        if ($user->hasRole('hostel_manager')) {
            $this->authorizeOwnerAccess($circular->organization_id);
        }

        $this->circularService->authorizeEdit($circular, $user);

        $circular->update($request->validated());

        return redirect()->route('owner.circulars.show', $circular)
            ->with('success', 'सूचना सफलतापूर्वक अद्यावधिक गरियो');
    }

    public function destroy(Circular $circular)
    {
        $user = auth()->user();

        // ✅ ENHANCED: Owner authorization
        if ($user->hasRole('hostel_manager')) {
            $this->authorizeOwnerAccess($circular->organization_id);
        }

        $this->circularService->authorizeEdit($circular, $user);

        $circular->delete();

        return redirect()->route('owner.circulars.index')
            ->with('success', 'सूचना सफलतापूर्वक मेटाइयो');
    }

    public function publish(Circular $circular)
    {
        $user = auth()->user();

        // ✅ ENHANCED: Owner authorization
        if ($user->hasRole('hostel_manager')) {
            $this->authorizeOwnerAccess($circular->organization_id);
        }

        $this->circularService->authorizeEdit($circular, $user);

        $circular->markAsPublished();

        return back()->with('success', 'सूचना सफलतापूर्वक प्रकाशित गरियो');
    }

    public function analytics(Circular $circular = null)
    {
        $user = auth()->user();

        // ✅ ENHANCED: Owner authorization
        if ($user->hasRole('hostel_manager')) {
            $this->authorizeOwnerAccess();
        }

        if ($circular) {
            // Single circular analytics
            $this->circularService->authorizeView($circular, $user);
            $stats = $this->circularService->getCircularAnalytics($circular);
            return view('owner.circulars.analytics-single', compact('circular', 'stats'));
        } else {
            // General analytics
            $stats = $this->circularService->getOrganizationAnalytics($user);
            return view('owner.circulars.analytics', compact('stats'));
        }
    }

    public function markAsRead(Circular $circular)
    {
        $user = auth()->user();

        // ✅ ENHANCED: Student authorization - students can only mark their own circulars as read
        if ($user->hasRole('student')) {
            $student = Student::where('user_id', $user->id)->first();
            if (!$student) {
                return response()->json(['success' => false, 'message' => 'विद्यार्थी रेकर्ड फेला परेन'], 403);
            }
        }

        $recipient = CircularRecipient::where('circular_id', $circular->id)
            ->where('user_id', $user->id)
            ->first();

        if ($recipient) {
            $recipient->markAsRead();
        }

        return response()->json(['success' => true]);
    }
}
