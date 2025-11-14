<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Circular;
use App\Models\Hostel;
use App\Models\Student;
use App\Models\CircularRecipient;
use App\Http\Requests\StoreCircularRequest;
use App\Services\CircularService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CircularController extends Controller
{
    protected $circularService;

    public function __construct(CircularService $circularService)
    {
        $this->circularService = $circularService;
    }

    // ✅ FIXED: Owner authorization helper method
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

        try {
            DB::transaction(function () use ($request, $user) {
                $organization = $user->organizations()->first();

                if (!$organization) {
                    throw new \Exception('Organization not found');
                }

                $data = $request->validated();
                $data['organization_id'] = $organization->id;
                $data['created_by'] = $user->id;

                // ✅ CRITICAL FIX: Proper status and publishing logic
                if ($request->has('scheduled_at') && $request->scheduled_at > now()) {
                    $data['status'] = 'draft'; // Schedule for later
                } else {
                    $data['status'] = 'published';
                    $data['published_at'] = now(); // ✅ CRITICAL: Set published_at timestamp
                }

                // Create circular
                $circular = Circular::create($data);

                // ✅ FIXED: Create recipients with proper parameters
                $this->circularService->createRecipients(
                    $circular,
                    $data['audience_type'],
                    $data['target_audience'] ?? [],
                    $organization->id
                );

                // ✅ ENHANCED LOG for debugging
                \Log::info('Circular created successfully', [
                    'circular_id' => $circular->id,
                    'audience_type' => $data['audience_type'],
                    'organization_id' => $organization->id,
                    'status' => $circular->status,
                    'published_at' => $circular->published_at
                ]);
            });

            $message = $request->has('scheduled_at') && $request->scheduled_at > now()
                ? 'सूचना सफलतापूर्वक सिर्जना गरियो र तोकिएको समयमा प्रकाशित हुनेछ'
                : 'सूचना सफलतापूर्वक प्रकाशित गरियो';

            // ✅ FIXED: Proper form clearing with session flag
            return redirect()->route('owner.circulars.index')
                ->with('success', $message)
                ->with('clear_form', true); // Flag for JavaScript form reset

        } catch (\Exception $e) {
            \Log::error('Circular creation failed: ' . $e->getMessage(), [
                'input_data' => $request->except('password') // Don't log sensitive data
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'सूचना सिर्जना गर्दा त्रुटि भयो: ' . $e->getMessage());
        }
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

        $data = $request->validated();

        // ✅ FIXED: Update publishing logic during edit
        if ($request->has('scheduled_at') && $request->scheduled_at > now()) {
            $data['status'] = 'draft';
            $data['published_at'] = null; // Reset published_at if scheduled for future
        } else {
            $data['status'] = 'published';
            // Only set published_at if it's not already set
            if (!$circular->published_at) {
                $data['published_at'] = now();
            }
        }

        $circular->update($data);

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

        // ✅ FIXED: Ensure published_at is set when manually publishing
        $circular->update([
            'status' => 'published',
            'published_at' => now()
        ]);

        return back()->with('success', 'सूचना सफलतापूर्वक प्रकाशित गरियो');
    }

    // ✅ FIXED: General analytics for all circulars (without parameter)
    public function analytics()
    {
        $user = auth()->user();

        // ✅ ENHANCED: Owner authorization
        if ($user->hasRole('hostel_manager')) {
            $this->authorizeOwnerAccess();
        }

        $organization = $user->organizations()->first();

        $stats = [
            'total_circulars' => Circular::where('organization_id', $organization->id)->count(),
            'published_circulars' => Circular::where('organization_id', $organization->id)
                ->where('status', 'published')->count(),
            'student_count' => $organization->students()->count(),
            'total_read' => CircularRecipient::whereHas('circular', function ($query) use ($organization) {
                $query->where('organization_id', $organization->id);
            })->where('is_read', true)->count(),
            'total_recipients' => CircularRecipient::whereHas('circular', function ($query) use ($organization) {
                $query->where('organization_id', $organization->id);
            })->count(),
            'urgent_count' => Circular::where('organization_id', $organization->id)
                ->where('priority', 'urgent')->count(),
            'normal_count' => Circular::where('organization_id', $organization->id)
                ->where('priority', 'normal')->count(),
            'info_count' => Circular::where('organization_id', $organization->id)
                ->where('priority', 'info')->count(),
        ];

        $recentCirculars = Circular::with('recipients')
            ->where('organization_id', $organization->id)
            ->where('status', 'published')
            ->orderBy('published_at', 'desc')
            ->take(10)
            ->get();

        return view('owner.circulars.analytics', compact('stats', 'recentCirculars'));
    }

    // ✅ FIXED: Single circular analytics (with parameter)
    public function analyticsSingle(Circular $circular)
    {
        $user = auth()->user();

        // ✅ ENHANCED: Owner authorization
        if ($user->hasRole('hostel_manager')) {
            $this->authorizeOwnerAccess($circular->organization_id);
        }

        $this->circularService->authorizeView($circular, $user);

        $stats = [
            'total_recipients' => $circular->recipients()->count(),
            'total_read' => $circular->recipients()->where('is_read', true)->count(),
            'by_user_type' => $circular->recipients()
                ->select('user_type')
                ->selectRaw('COUNT(*) as total')
                ->selectRaw('SUM(is_read) as read_count')
                ->groupBy('user_type')
                ->get(),
            'engagement_rate' => $circular->recipients()->count() > 0 ?
                round(($circular->recipients()->where('is_read', true)->count() / $circular->recipients()->count()) * 100, 1) : 0
        ];

        return view('owner.circulars.analytics-single', compact('circular', 'stats'));
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

    // ✅ ADDED: Template management method (if needed for your routes)
    public function templates()
    {
        $user = auth()->user();

        // ✅ ENHANCED: Owner authorization
        if ($user->hasRole('hostel_manager')) {
            $this->authorizeOwnerAccess();
        }

        // Add your template logic here
        return view('owner.circulars.templates');
    }
}
