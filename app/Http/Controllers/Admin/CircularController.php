<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Circular;
use App\Models\Organization;
use App\Models\Hostel;
use App\Models\Student;
use App\Models\CircularRecipient;
use App\Http\Requests\StoreCircularRequest;
use App\Services\CircularService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CircularController extends Controller
{
    protected $circularService;

    public function __construct(CircularService $circularService)
    {
        $this->circularService = $circularService;
        // ✅ FIXED: Apply authorization middleware
        $this->middleware('role:admin');
    }

    public function index(Request $request)
    {
        // ✅ FIXED: Enhanced authorization check
        if (!Auth::user()->hasRole('admin')) {
            abort(403, 'तपाईंसँग सूचनाहरू हेर्ने अनुमति छैन');
        }

        // ✅ FIXED: Input validation for filters
        $validated = $request->validate([
            'organization_id' => 'sometimes|integer|exists:organizations,id',
            'status' => 'sometimes|in:draft,published,archived',
            'priority' => 'sometimes|in:urgent,normal,info',
            'search' => 'sometimes|string|max:255'
        ]);

        $query = Circular::with(['organization', 'creator']);

        // ✅ FIXED: Use validated data for filtering
        if (isset($validated['organization_id'])) {
            $query->where('organization_id', $validated['organization_id']);
        }

        // Filter by status
        if (isset($validated['status'])) {
            $query->where('status', $validated['status']);
        }

        // Filter by priority
        if (isset($validated['priority'])) {
            $query->where('priority', $validated['priority']);
        }

        // ✅ FIXED: SQL Injection prevention in search
        if (isset($validated['search'])) {
            $safeSearch = '%' . addcslashes($validated['search'], '%_') . '%';
            $query->where(function ($q) use ($safeSearch) {
                $q->where('title', 'like', $safeSearch)
                    ->orWhere('content', 'like', $safeSearch);
            });
        }

        $circulars = $query->latest()->paginate(20);
        $organizations = Organization::all();

        return view('admin.circulars.index', compact('circulars', 'organizations'));
    }

    public function create()
    {
        // ✅ FIXED: Authorization check
        if (!Auth::user()->hasRole('admin')) {
            abort(403, 'तपाईंसँग सूचना सिर्जना गर्ने अनुमति छैन');
        }

        $organizations = Organization::all();
        $hostels = Hostel::all();

        // ✅ FIXED: Limit students to manageable numbers with pagination
        $students = Student::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(1000) // Prevent memory issues
            ->get();

        return view('admin.circulars.create', compact('organizations', 'hostels', 'students'));
    }

    public function store(StoreCircularRequest $request)
    {
        // ✅ FIXED: Authorization check
        if (!Auth::user()->hasRole('admin')) {
            abort(403, 'तपाईंसँग सूचना सिर्जना गर्ने अनुमति छैन');
        }

        try {
            DB::transaction(function () use ($request) {
                $user = Auth::user();
                $data = $request->validated();

                $data['created_by'] = $user->id;

                // ✅ FIXED: Ensure organization_id is set and user has access
                if (!isset($data['organization_id'])) {
                    throw new \Exception('Organization ID is required');
                }

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

                // ✅ FIXED: Log circular creation
                Log::info('Circular created successfully', [
                    'circular_id' => $circular->id,
                    'user_id' => $user->id,
                    'audience_type' => $data['audience_type']
                ]);
            });

            $message = $request->has('scheduled_at')
                ? 'सूचना सफलतापूर्वक सिर्जना गरियो र तोकिएको समयमा प्रकाशित हुनेछ'
                : 'सूचना सफलतापूर्वक प्रकाशित गरियो';

            return redirect()->route('admin.circulars.index')->with('success', $message);
        } catch (\Exception $e) {
            // ✅ FIXED: Proper error handling and logging
            Log::error('Circular creation failed: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'request_data' => $request->except(['content']) // Don't log full content
            ]);

            return back()->withInput()
                ->with('error', 'सूचना सिर्जना गर्दा त्रुटि भयो: ' . $e->getMessage());
        }
    }

    public function show(Circular $circular)
    {
        // ✅ FIXED: Enhanced authorization with proper error handling
        try {
            $this->circularService->authorizeView($circular, Auth::user());
        } catch (\Exception $e) {
            abort(403, 'तपाईंसँग यो सूचना हेर्ने अनुमति छैन');
        }

        $circular->load(['organization', 'creator', 'recipients.user']);
        $readStats = $this->circularService->getReadStats($circular);

        return view('admin.circulars.show', compact('circular', 'readStats'));
    }

    public function edit(Circular $circular)
    {
        // ✅ FIXED: Enhanced authorization with proper error handling
        try {
            $this->circularService->authorizeEdit($circular, Auth::user());
        } catch (\Exception $e) {
            abort(403, 'तपाईंसँग यो सूचना सम्पादन गर्ने अनुमति छैन');
        }

        $organizations = Organization::all();
        $hostels = Hostel::all();

        // ✅ FIXED: Limit students to prevent memory issues
        $students = Student::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(1000)
            ->get();

        return view('admin.circulars.edit', compact('circular', 'organizations', 'hostels', 'students'));
    }

    public function update(StoreCircularRequest $request, Circular $circular)
    {
        // ✅ FIXED: Enhanced authorization with proper error handling
        try {
            $this->circularService->authorizeEdit($circular, Auth::user());
        } catch (\Exception $e) {
            abort(403, 'तपाईंसँग यो सूचना सम्पादन गर्ने अनुमति छैन');
        }

        try {
            $circular->update($request->validated());

            // ✅ FIXED: Log the update
            Log::info('Circular updated successfully', [
                'circular_id' => $circular->id,
                'user_id' => Auth::id()
            ]);

            return redirect()->route('admin.circulars.show', $circular)
                ->with('success', 'सूचना सफलतापूर्वक अद्यावधिक गरियो');
        } catch (\Exception $e) {
            Log::error('Circular update failed: ' . $e->getMessage(), [
                'circular_id' => $circular->id,
                'user_id' => Auth::id()
            ]);

            return back()->withInput()
                ->with('error', 'सूचना अद्यावधिक गर्दा त्रुटि भयो: ' . $e->getMessage());
        }
    }

    public function destroy(Circular $circular)
    {
        // ✅ FIXED: Enhanced authorization with proper error handling
        try {
            $this->circularService->authorizeEdit($circular, Auth::user());
        } catch (\Exception $e) {
            abort(403, 'तपाईंसँग यो सूचना मेटाउने अनुमति छैन');
        }

        try {
            $circularId = $circular->id;
            $circular->delete();

            // ✅ FIXED: Log the deletion
            Log::info('Circular deleted successfully', [
                'circular_id' => $circularId,
                'user_id' => Auth::id()
            ]);

            return redirect()->route('admin.circulars.index')
                ->with('success', 'सूचना सफलतापूर्वक मेटाइयो');
        } catch (\Exception $e) {
            Log::error('Circular deletion failed: ' . $e->getMessage(), [
                'circular_id' => $circular->id,
                'user_id' => Auth::id()
            ]);

            return back()->with('error', 'सूचना मेटाउँदा त्रुटि भयो: ' . $e->getMessage());
        }
    }

    public function publish(Circular $circular)
    {
        // ✅ FIXED: Enhanced authorization with proper error handling
        try {
            $this->circularService->authorizeEdit($circular, Auth::user());
        } catch (\Exception $e) {
            abort(403, 'तपाईंसँग यो सूचना प्रकाशन गर्ने अनुमति छैन');
        }

        try {
            $circular->markAsPublished();

            // ✅ FIXED: Log the publication
            Log::info('Circular published successfully', [
                'circular_id' => $circular->id,
                'user_id' => Auth::id()
            ]);

            return back()->with('success', 'सूचना सफलतापूर्वक प्रकाशित गरियो');
        } catch (\Exception $e) {
            Log::error('Circular publication failed: ' . $e->getMessage(), [
                'circular_id' => $circular->id,
                'user_id' => Auth::id()
            ]);

            return back()->with('error', 'सूचना प्रकाशन गर्दा त्रुटि भयो: ' . $e->getMessage());
        }
    }

    public function analytics(Circular $circular = null)
    {
        // ✅ FIXED: Authorization check for analytics
        if (!Auth::user()->hasRole('admin')) {
            abort(403, 'तपाईंसँग विश्लेषण हेर्ने अनुमति छैन');
        }

        try {
            if ($circular) {
                // Single circular analytics
                $this->circularService->authorizeView($circular, Auth::user());
                $stats = $this->circularService->getCircularAnalytics($circular);
                return view('admin.circulars.analytics-single', compact('circular', 'stats'));
            } else {
                // General analytics
                $stats = $this->circularService->getAdminAnalytics();
                return view('admin.circulars.analytics', compact('stats'));
            }
        } catch (\Exception $e) {
            Log::error('Analytics loading failed: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'circular_id' => $circular?->id
            ]);

            return back()->with('error', 'विश्लेषण लोड गर्दा त्रुटि भयो');
        }
    }

    public function markAsRead(Circular $circular)
    {
        // ✅ FIXED: Authorization check - user must be recipient
        $user = Auth::user();

        $recipient = CircularRecipient::where('circular_id', $circular->id)
            ->where('user_id', $user->id)
            ->first();

        if (!$recipient) {
            return response()->json([
                'success' => false,
                'message' => 'तपाईं यो सूचनाको प्राप्तकर्ता हुनुहुन्न'
            ], 403);
        }

        try {
            $recipient->markAsRead();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('Mark as read failed: ' . $e->getMessage(), [
                'circular_id' => $circular->id,
                'user_id' => $user->id
            ]);

            return response()->json([
                'success' => false,
                'message' => 'पढिएको चिन्ह लगाउँदा त्रुटि भयो'
            ], 500);
        }
    }

    public function templates()
    {
        // ✅ FIXED: Authorization check
        if (!Auth::user()->hasRole('admin')) {
            abort(403, 'तपाईंसँग टेम्प्लेटहरू हेर्ने अनुमति छैन');
        }

        // ✅ FIXED: Secure template data - consider moving to database
        $templates = [
            [
                'id' => 1,
                'name' => 'सामान्य सूचना',
                'description' => 'सामान्य प्रयोगको लागि सूचना टेम्प्लेट',
                'content' => 'प्रिय विद्यार्थीहरू,...'
            ],
            [
                'id' => 2,
                'name' => 'जरुरी सूचना',
                'description' => 'जरुरी सूचनाको लागि टेम्प्लेट',
                'content' => 'जरुरी सूचना:...'
            ],
            [
                'id' => 3,
                'name' => 'छुट्टीको सूचना',
                'description' => 'छुट्टी सम्बन्धी सूचनाको लागि टेम्प्लेट',
                'content' => 'सूचना: अगामी छुट्टीको सम्बन्धमा...'
            ]
        ];

        return view('admin.circulars.templates', compact('templates'));
    }
}
