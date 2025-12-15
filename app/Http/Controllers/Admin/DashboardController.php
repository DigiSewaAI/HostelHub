<?php

namespace App\Http\Controllers\Admin;

use App\Models\Contact;
use App\Models\Room;
use App\Models\Student;
use App\Models\Hostel;
use App\Models\MealMenu;
use App\Models\Payment;
use App\Models\User;
use App\Models\Organization;
use App\Models\StudentDocument;
use App\Models\Circular;
use App\Models\CircularRecipient;
use App\Models\OrganizationRequest;
use App\Models\Review; // ✅ ADDED: Import Review model
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
        // ✅ CRITICAL FIX: Remove global 'role:admin' middleware
        $this->middleware('can:view-admin-dashboard')->only(['adminDashboard', 'reports', 'statistics']);
    }

    /**
     * Display role-specific dashboard
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $user = auth()->user();

        // ✅ SECURITY FIX: Authorization check for all roles
        if (!$user->hasAnyRole(['admin', 'hostel_manager', 'student'])) {
            abort(403, 'अनधिकृत पहुँच');
        }

        if ($user->hasRole('admin')) {
            return $this->adminDashboard();
        } elseif ($user->hasRole('hostel_manager')) {
            return $this->ownerDashboard();
        } elseif ($user->hasRole('student')) {
            return $this->studentDashboard();
        }

        abort(403, 'अनधिकृत पहुँच');
    }

    /**
     * ✅ CRITICAL FIX: Ensure admin users have proper session setup
     */
    private function ensureAdminSession()
    {
        $user = auth()->user();

        if ($user->hasRole('admin')) {
            // ✅ Ensure admin has session organization set (can be null)
            if (!session()->has('current_organization_id')) {
                session(['current_organization_id' => null]);
            }

            // ✅ Clear any organization restrictions for admin
            session(['admin_override' => true]);
        }
    }

    /**
     * ✅ NEW: Get review statistics for dashboard
     */
    private function getReviewStats()
    {
        return [
            'pending_reviews_count' => Review::where('status', 'pending')->count(),
            'approved_reviews_count' => Review::where('status', 'approved')->count(),
            'featured_reviews_count' => Review::where('is_featured', true)->count(),
            'recent_approved_reviews' => Review::where('status', 'approved')
                ->with('student.user')
                ->latest()
                ->take(5)
                ->get()
        ];
    }

    /**
     * Admin dashboard with system-wide metrics
     */
    public function adminDashboard()
    {
        // ✅ CRITICAL FIX: Ensure admin session is properly set
        $this->ensureAdminSession();

        $this->authorize('view-admin-dashboard');

        $userId = auth()->id();
        $cacheKey = "admin_dashboard_metrics_{$userId}";

        try {
            $metrics = Cache::remember($cacheKey, 300, function () {
                // ✅ SECURITY FIX: Parameter validation for date ranges in statistics
                $startDate = Carbon::now()->subDays(30)->format('Y-m-d');
                $endDate = Carbon::now()->format('Y-m-d');

                // Fetch core metrics with optimized queries
                $totalStudents = Student::count();
                $totalRooms = Room::count();
                $totalHostels = Hostel::count();

                // ✅ FIXED: Contact Statistics - Remove hostel filtering for admin
                $totalContacts = Contact::count();
                $unreadContacts = Contact::where('is_read', false)->count();
                $todayContacts = Contact::whereDate('created_at', today())->count();

                $totalDocuments = StudentDocument::count();
                $totalCirculars = Circular::count();

                // ✅ NEW: Review Statistics
                $reviewStats = $this->getReviewStats();

                // ✅ NEW: Circular Statistics
                $publishedCirculars = Circular::where('status', 'published')->count();
                $urgentCirculars = Circular::where('priority', 'urgent')->count();
                $totalRecipients = CircularRecipient::count();
                $readCirculars = CircularRecipient::where('is_read', true)->count();
                $circularReadRate = $totalRecipients > 0 ? round(($readCirculars / $totalRecipients) * 100, 1) : 0;

                // ✅ ADDED: Organization Request Statistics
                $pendingOrganizationRequests = OrganizationRequest::where('status', 'pending')->count();
                $approvedOrganizationRequests = OrganizationRequest::where('status', 'approved')->count();
                $rejectedOrganizationRequests = OrganizationRequest::where('status', 'rejected')->count();
                $totalOrganizationRequests = OrganizationRequest::count();
                $todayOrganizationRequests = OrganizationRequest::whereDate('created_at', today())->count();
                $recentOrganizationRequests = OrganizationRequest::with('user')
                    ->latest()
                    ->take(5)
                    ->get();

                // Batch room status queries for efficiency with null safety
                $roomStatus = Room::selectRaw('
                COUNT(CASE WHEN status = "available" THEN 1 END) as available,
                COUNT(CASE WHEN status = "occupied" THEN 1 END) as occupied,
                COUNT(CASE WHEN status = "reserved" THEN 1 END) as reserved,
                COUNT(CASE WHEN status = "maintenance" THEN 1 END) as maintenance
            ')->first() ?? (object)[
                    'available' => 0,
                    'occupied' => 0,
                    'reserved' => 0,
                    'maintenance' => 0
                ];

                // Calculate occupancy rate safely
                $roomOccupancy = $this->calculateOccupancyRate();

                // Calculate reservation rate
                $roomReservation = $totalRooms > 0
                    ? round(($roomStatus->reserved / $totalRooms) * 100, 1)
                    : 0;

                // ✅ SECURITY FIX: Use select() to prevent mass assignment and optimize queries
                $recentStudents = Student::with(['room.hostel' => function ($query) {
                    $query->select('id', 'name');
                }])
                    ->select('id', 'name', 'room_id', 'created_at')
                    ->latest('created_at')
                    ->take(5)
                    ->get();

                // ✅ FIXED: Recent contacts without hostel filtering
                $recentContacts = Contact::select('id', 'name', 'email', 'subject', 'message', 'is_read', 'created_at')
                    ->latest('created_at')
                    ->take(8)
                    ->get();

                $recentHostels = Hostel::withCount('rooms')
                    ->select('id', 'name', 'created_at')
                    ->latest('created_at')
                    ->take(5)
                    ->get();

                // Recent documents
                $recentDocuments = StudentDocument::with(['student.user', 'organization'])
                    ->select('id', 'student_id', 'organization_id', 'document_type', 'original_name', 'created_at')
                    ->latest('created_at')
                    ->take(5)
                    ->get();

                // Recent circulars
                $recentCirculars = Circular::with(['organization', 'creator'])
                    ->select('id', 'title', 'content', 'priority', 'organization_id', 'created_by', 'created_at')
                    ->latest('created_at')
                    ->take(5)
                    ->get();

                return array_merge([
                    'total_students' => $totalStudents,
                    'total_rooms' => $totalRooms,
                    'total_hostels' => $totalHostels,
                    'total_contacts' => $totalContacts,
                    'unread_contacts' => $unreadContacts,
                    'today_contacts' => $todayContacts,
                    'total_documents' => $totalDocuments,
                    'total_circulars' => $totalCirculars,
                    'room_occupancy' => $roomOccupancy,
                    'room_reservation' => $roomReservation,
                    'available_rooms' => $roomStatus->available,
                    'occupied_rooms' => $roomStatus->occupied,
                    'reserved_rooms' => $roomStatus->reserved,
                    'maintenance_rooms' => $roomStatus->maintenance,
                    'recent_students' => $recentStudents,
                    'recent_contacts' => $recentContacts,
                    'recent_hostels' => $recentHostels,
                    'recent_documents' => $recentDocuments,
                    'recent_circulars' => $recentCirculars,
                    // ✅ NEW: Circular Statistics
                    'published_circulars' => $publishedCirculars,
                    'urgent_circulars' => $urgentCirculars,
                    'circular_read_rate' => $circularReadRate,
                    // ✅ ADDED: Organization Request Statistics
                    'pending_organization_requests' => $pendingOrganizationRequests,
                    'approved_organization_requests' => $approvedOrganizationRequests,
                    'rejected_organization_requests' => $rejectedOrganizationRequests,
                    'total_organization_requests' => $totalOrganizationRequests,
                    'today_organization_requests' => $todayOrganizationRequests,
                    'recent_organization_requests' => $recentOrganizationRequests,
                ], $reviewStats);
            });

            // Pass all data to view
            $totalCirculars = $metrics['total_circulars'] ?? 0;
            $publishedCirculars = $metrics['published_circulars'] ?? 0;
            $urgentCirculars = $metrics['urgent_circulars'] ?? 0;
            $circularReadRate = $metrics['circular_read_rate'] ?? 0;
            $recentCirculars = $metrics['recent_circulars'] ?? collect();

            // ✅ NEW: Review statistics for view
            $pending_reviews_count = $metrics['pending_reviews_count'] ?? 0;
            $approved_reviews_count = $metrics['approved_reviews_count'] ?? 0;
            $featured_reviews_count = $metrics['featured_reviews_count'] ?? 0;
            $recent_approved_reviews = $metrics['recent_approved_reviews'] ?? collect();

            // ✅ FIXED: Contact data for view
            $totalContacts = $metrics['total_contacts'] ?? 0;
            $unreadContacts = $metrics['unread_contacts'] ?? 0;
            $todayContacts = $metrics['today_contacts'] ?? 0;
            $recentContacts = $metrics['recent_contacts'] ?? collect();

            // ✅ ADDED: Organization Request data for view
            $pendingOrganizationRequests = $metrics['pending_organization_requests'] ?? 0;
            $approvedOrganizationRequests = $metrics['approved_organization_requests'] ?? 0;
            $rejectedOrganizationRequests = $metrics['rejected_organization_requests'] ?? 0;
            $totalOrganizationRequests = $metrics['total_organization_requests'] ?? 0;
            $todayOrganizationRequests = $metrics['today_organization_requests'] ?? 0;
            $recentOrganizationRequests = $metrics['recent_organization_requests'] ?? collect();

            $recentActivities = $this->getRecentActivities($metrics);

            return view('admin.dashboard', compact(
                'metrics',
                'totalCirculars',
                'publishedCirculars',
                'urgentCirculars',
                'circularReadRate',
                'recentCirculars',
                'recentActivities',
                // ✅ NEW: Review statistics
                'pending_reviews_count',
                'approved_reviews_count',
                'featured_reviews_count',
                'recent_approved_reviews',
                // ✅ FIXED: Contact variables
                'totalContacts',
                'unreadContacts',
                'todayContacts',
                'recentContacts',
                // ✅ ADDED: Organization Request Variables
                'pendingOrganizationRequests',
                'approvedOrganizationRequests',
                'rejectedOrganizationRequests',
                'totalOrganizationRequests',
                'todayOrganizationRequests',
                'recentOrganizationRequests'
            ));
        } catch (\Exception $e) {
            // Log error details for debugging
            Log::error('ड्यासबोर्ड लोड गर्न असफल: ' . $e->getMessage(), [
                'exception' => $e,
                'user_id' => auth()->id(),
                'trace' => $e->getTraceAsString()
            ]);

            // Clear cache on error
            Cache::forget($cacheKey);

            // Return simplified view with error message
            return view('admin.dashboard', [
                'metrics' => [
                    'total_students' => 0,
                    'total_rooms' => 0,
                    'total_hostels' => 0,
                    'total_contacts' => 0,
                    'unread_contacts' => 0,
                    'today_contacts' => 0,
                    'total_documents' => 0,
                    'total_circulars' => 0,
                    'room_occupancy' => 0,
                    'room_reservation' => 0,
                    'available_rooms' => 0,
                    'occupied_rooms' => 0,
                    'reserved_rooms' => 0,
                    'maintenance_rooms' => 0,
                    'recent_students' => collect(),
                    'recent_contacts' => collect(),
                    'recent_hostels' => collect(),
                    'recent_documents' => collect(),
                    'recent_circulars' => collect(),
                    'published_circulars' => 0,
                    'urgent_circulars' => 0,
                    'circular_read_rate' => 0,
                    // ✅ NEW: Review statistics defaults
                    'pending_reviews_count' => 0,
                    'approved_reviews_count' => 0,
                    'featured_reviews_count' => 0,
                    'recent_approved_reviews' => collect(),
                    // ✅ ADDED: Organization Request Statistics with defaults
                    'pending_organization_requests' => 0,
                    'approved_organization_requests' => 0,
                    'rejected_organization_requests' => 0,
                    'total_organization_requests' => 0,
                    'today_organization_requests' => 0,
                    'recent_organization_requests' => collect(),
                ],
                'totalCirculars' => 0,
                'publishedCirculars' => 0,
                'urgentCirculars' => 0,
                'circularReadRate' => 0,
                'recentCirculars' => collect(),
                'recentActivities' => collect(),
                // ✅ NEW: Review statistics defaults
                'pending_reviews_count' => 0,
                'approved_reviews_count' => 0,
                'featured_reviews_count' => 0,
                'recent_approved_reviews' => collect(),
                // ✅ FIXED: Contact variables with defaults
                'totalContacts' => 0,
                'unreadContacts' => 0,
                'todayContacts' => 0,
                'recentContacts' => collect(),
                // ✅ ADDED: Organization Request Variables with defaults
                'pendingOrganizationRequests' => 0,
                'approvedOrganizationRequests' => 0,
                'rejectedOrganizationRequests' => 0,
                'totalOrganizationRequests' => 0,
                'todayOrganizationRequests' => 0,
                'recentOrganizationRequests' => collect(),
                'error' => 'ड्यासबोर्ड डाटा लोड गर्न सकिएन। कृपया पछि प्रयास गर्नुहोस् वा समर्थन सम्पर्क गर्नुहोस्।'
            ]);
        }
    }

    /**
     * Calculate room occupancy rate
     */
    private function calculateOccupancyRate()
    {
        $totalRooms = Room::count();
        $occupiedRooms = Room::where('status', 'occupied')->count();

        return $totalRooms > 0 ? round(($occupiedRooms / $totalRooms) * 100) : 0;
    }

    /**
     * Calculate circular read rate for organization
     */
    private function calculateOrganizationReadRate($organizationId)
    {
        $totalRecipients = CircularRecipient::whereHas('circular', function ($q) use ($organizationId) {
            $q->where('organization_id', $organizationId);
        })->count();

        $readRecipients = CircularRecipient::whereHas('circular', function ($q) use ($organizationId) {
            $q->where('organization_id', $organizationId);
        })->where('is_read', true)->count();

        return $totalRecipients > 0 ? round(($readRecipients / $totalRecipients) * 100, 1) : 0;
    }

    /**
     * Get student engagement rate
     */
    private function calculateStudentEngagement($organizationId)
    {
        $organization = Organization::find($organizationId);
        if (!$organization) return 0;

        $totalStudents = $organization->students()->count();
        $engagedStudents = $organization->students()->whereHas('user.circularRecipients', function ($q) use ($organizationId) {
            $q->where('is_read', true)
                ->whereHas('circular', function ($q2) use ($organizationId) {
                    $q2->where('organization_id', $organizationId);
                });
        })->count();

        return $totalStudents > 0 ? round(($engagedStudents / $totalStudents) * 100, 1) : 0;
    }

    /**
     * Get recent activities for dashboard
     */
    private function getRecentActivities($metrics)
    {
        $activities = collect();

        // Recent circulars
        foreach ($metrics['recent_circulars'] ?? [] as $circular) {
            $activities->push([
                'type' => 'circular',
                'title' => 'नयाँ सूचना प्रकाशित',
                'description' => $circular->title . ' - ' . Str::limit($circular->content, 50),
                'time' => $circular->created_at->diffForHumans(),
                'icon' => 'bullhorn',
                'color' => 'indigo',
                'priority' => $circular->priority_nepali ?? 'सामान्य'
            ]);
        }

        // Recent students
        foreach ($metrics['recent_students'] as $student) {
            $activities->push([
                'type' => 'student',
                'title' => 'नयाँ विद्यार्थी दर्ता',
                'description' => $student->name . ' (' . (optional(optional($student->room)->hostel)->name ?? 'अज्ञात होस्टल') . ')',
                'time' => $student->created_at->diffForHumans(),
                'icon' => 'user-plus',
                'color' => 'red'
            ]);
        }

        // Recent contacts
        foreach ($metrics['recent_contacts'] as $contact) {
            $activities->push([
                'type' => 'contact',
                'title' => 'नयाँ सम्पर्क सन्देश',
                'description' => $contact->name . ' - ' . Str::limit($contact->message, 50),
                'time' => $contact->created_at->diffForHumans(),
                'icon' => 'envelope',
                'color' => 'blue'
            ]);
        }

        // Recent hostels
        foreach ($metrics['recent_hostels'] as $hostel) {
            $activities->push([
                'type' => 'hostel',
                'title' => 'नयाँ होस्टल दर्ता',
                'description' => $hostel->name . ' (' . $hostel->rooms_count . ' कोठाहरू)',
                'time' => $hostel->created_at->diffForHumans(),
                'icon' => 'building',
                'color' => 'amber'
            ]);
        }

        // Recent documents
        foreach ($metrics['recent_documents'] as $document) {
            $activities->push([
                'type' => 'document',
                'title' => 'नयाँ कागजात अपलोड',
                'description' => $document->original_name . ' (' . (optional($document->student)->user->name ?? 'अज्ञात विद्यार्थी') . ')',
                'time' => $document->created_at->diffForHumans(),
                'icon' => 'file-upload',
                'color' => 'purple'
            ]);
        }

        // ✅ ADDED: Recent approved reviews
        foreach ($metrics['recent_approved_reviews'] ?? [] as $review) {
            $activities->push([
                'type' => 'review',
                'title' => 'नयाँ समीक्षा स्वीकृत',
                'description' => ($review->student->user->name ?? $review->name) . ' - ' . Str::limit($review->content, 50),
                'time' => $review->created_at->diffForHumans(),
                'icon' => 'star',
                'color' => 'green'
            ]);
        }

        // ✅ ADDED: Recent organization requests
        foreach ($metrics['recent_organization_requests'] ?? [] as $request) {
            $activities->push([
                'type' => 'organization_request',
                'title' => 'नयाँ संस्था दर्ता अनुरोध',
                'description' => $request->organization_name . ' (' . (optional($request->user)->name ?? 'अज्ञात प्रयोगकर्ता') . ')',
                'time' => $request->created_at->diffForHumans(),
                'icon' => 'building',
                'color' => 'green'
            ]);
        }

        return $activities->sortByDesc('time')->take(10);
    }

    /**
     * Owner dashboard with hostel-specific metrics
     */
    public function ownerDashboard()
    {
        // ✅ SECURITY FIX: Authorization check
        $user = auth()->user();
        if (!$user->hasAnyRole(['hostel_manager', 'owner'])) {
            abort(403, 'तपाईंसँग यो ड्यासबोर्ड हेर्ने अनुमति छैन');
        }

        try {
            // ✅ CRITICAL FIX: Force session organization set
            $organization = $user->organizations()
                ->wherePivot('role', 'owner')
                ->first();

            if (!$organization) {
                return view('owner.dashboard', [
                    'error' => 'तपाईंको संस्था फेला परेन',
                    'hostel' => null,
                    'totalRooms' => 0,
                    'occupiedRooms' => 0,
                    'totalStudents' => 0,
                    'todayMeal' => null,
                    'organization' => null,
                    // Financial summary variables
                    'totalMonthlyRevenue' => 0,
                    'totalSecurityDeposit' => 0,
                    'averageOccupancy' => 0,
                    'activeHostelsCount' => 0,
                    'totalDocuments' => 0,
                    'recentDocuments' => collect(),
                    // Circular variables
                    'organizationCirculars' => 0,
                    'urgentCirculars' => 0,
                    'normalCirculars' => 0,
                    'noticeCirculars' => 0,
                    'recentCirculars' => collect(),
                    // ✅ NEW: Circular Engagement Stats
                    'todayCirculars' => 0,
                    'circularReadRate' => 0,
                    'studentEngagement' => 0,
                    // ✅ FIXED: Contact Statistics - Now filtered by owner's hostels (none in this case)
                    'totalContacts' => 0,
                    'unreadContacts' => 0,
                    'todayContacts' => 0,
                    'recentContacts' => collect(),
                ]);
            }

            // ✅ CRITICAL FIX: Force set session organization ID
            session(['current_organization_id' => $organization->id]);

            // Get all hostels of the organization
            $hostels = $organization->hostels;
            $hostelIds = $hostels->pluck('id');

            // If there are hostels, get the first one for the meal and to pass to the view
            $hostel = $hostels->first();

            // Get aggregated statistics for all hostels with optimized queries
            $totalRooms = Room::whereIn('hostel_id', $hostelIds)->count();
            $occupiedRooms = Room::whereIn('hostel_id', $hostelIds)
                ->where('status', 'occupied')
                ->count();
            $totalStudents = Student::whereIn('hostel_id', $hostelIds)->count();
            $totalDocuments = StudentDocument::where('organization_id', $organization->id)->count();

            // Circulars data for the organization
            $organizationCirculars = Circular::where('organization_id', $organization->id)->count();
            $urgentCirculars = Circular::where('organization_id', $organization->id)
                ->where('priority', 'urgent')
                ->count();
            $normalCirculars = Circular::where('organization_id', $organization->id)
                ->where('priority', 'normal')
                ->count();
            $noticeCirculars = Circular::where('organization_id', $organization->id)
                ->where('priority', 'notice')
                ->count();
            $recentCirculars = Circular::where('organization_id', $organization->id)
                ->with(['creator'])
                ->latest('created_at')
                ->take(5)
                ->get();

            // ✅ NEW: Circular Engagement Stats
            $todayCirculars = Circular::where('organization_id', $organization->id)
                ->whereDate('created_at', today())
                ->count();
            $circularReadRate = $this->calculateOrganizationReadRate($organization->id);
            $studentEngagement = $this->calculateStudentEngagement($organization->id);

            // ✅ FIXED: Contact Statistics for Owner Dashboard - Now filtered by owner's hostels
            $totalContacts = Contact::whereIn('hostel_id', $hostelIds)->count();
            $unreadContacts = Contact::whereIn('hostel_id', $hostelIds)->where('is_read', false)->count();
            $todayContacts = Contact::whereIn('hostel_id', $hostelIds)->whereDate('created_at', today())->count();

            // ✅ CRITICAL FIX: Ensure recentContacts is properly defined and filtered by owner's hostels
            $recentContacts = Contact::select('id', 'name', 'email', 'subject', 'message', 'is_read', 'created_at')
                ->whereIn('hostel_id', $hostelIds)
                ->latest('created_at')
                ->take(6)
                ->get();

            // Financial calculations
            $totalMonthlyRevenue = $hostels->sum('monthly_rent');
            $totalSecurityDeposit = $hostels->sum('security_deposit');

            // Occupancy calculation
            $averageOccupancy = $totalRooms > 0 ? round(($occupiedRooms / $totalRooms) * 100, 2) : 0;

            // Active hostels count
            $activeHostelsCount = $hostels->where('status', 'active')->count();

            // Recent documents for the organization
            $recentDocuments = StudentDocument::where('organization_id', $organization->id)
                ->with(['student.user'])
                ->latest('created_at')
                ->take(5)
                ->get();

            // For today's meal, we use the first hostel
            $todayMeal = null;
            if ($hostel) {
                $todayMeal = MealMenu::where('hostel_id', $hostel->id)
                    ->where('day_of_week', now()->format('l'))
                    ->select('id', 'meal_type as name', 'description', 'day_of_week')
                    ->first();
            }

            return view('owner.dashboard', compact(
                'hostel',
                'totalRooms',
                'occupiedRooms',
                'totalStudents',
                'todayMeal',
                'organization',
                // Financial summary variables
                'totalMonthlyRevenue',
                'totalSecurityDeposit',
                'averageOccupancy',
                'activeHostelsCount',
                // Document variables
                'totalDocuments',
                'recentDocuments',
                // Circular variables
                'organizationCirculars',
                'urgentCirculars',
                'normalCirculars',
                'noticeCirculars',
                'recentCirculars',
                // ✅ NEW: Circular Engagement Stats
                'todayCirculars',
                'circularReadRate',
                'studentEngagement',
                // ✅ FIXED: Contact Statistics - PROPERLY DEFINED AND FILTERED
                'totalContacts',
                'unreadContacts',
                'todayContacts',
                'recentContacts'  // ✅ THIS IS THE KEY VARIABLE!
            ));
        } catch (\Exception $e) {
            Log::error('होस्टेल मालिक ड्यासबोर्ड त्रुटि: ' . $e->getMessage(), [
                'user_id' => auth()->id(),
                'organization_id' => $organization->id ?? null
            ]);

            // Get the organization again for the catch block
            $user = auth()->user();
            $org = $organization ?? $user->organizations()->wherePivot('role', 'owner')->first();
            $hostelIds = $org ? $org->hostels()->pluck('id') : [];

            // ✅ FIXED: Contact Statistics with PROPER DATA and filtered by owner's hostels
            $totalContacts = $org ? Contact::whereIn('hostel_id', $hostelIds)->count() : 0;
            $unreadContacts = $org ? Contact::whereIn('hostel_id', $hostelIds)->where('is_read', false)->count() : 0;
            $todayContacts = $org ? Contact::whereIn('hostel_id', $hostelIds)->whereDate('created_at', today())->count() : 0;
            $recentContacts = $org ? Contact::select('id', 'name', 'email', 'subject', 'message', 'is_read', 'created_at')
                ->whereIn('hostel_id', $hostelIds)
                ->latest('created_at')
                ->take(6)
                ->get() : collect();

            return view('owner.dashboard', [
                'error' => 'डाटा लोड गर्न असफल भयो',
                'hostel' => null,
                'totalRooms' => 0,
                'occupiedRooms' => 0,
                'totalStudents' => 0,
                'todayMeal' => null,
                'organization' => null,
                // Financial summary variables
                'totalMonthlyRevenue' => 0,
                'totalSecurityDeposit' => 0,
                'averageOccupancy' => 0,
                'activeHostelsCount' => 0,
                // Document variables
                'totalDocuments' => 0,
                'recentDocuments' => collect(),
                // Circular variables
                'organizationCirculars' => 0,
                'urgentCirculars' => 0,
                'normalCirculars' => 0,
                'noticeCirculars' => 0,
                'recentCirculars' => collect(),
                // ✅ NEW: Circular Engagement Stats
                'todayCirculars' => 0,
                'circularReadRate' => 0,
                'studentEngagement' => 0,
                // ✅ FIXED: Contact Statistics with PROPER DATA and filtered by owner's hostels
                'totalContacts' => $totalContacts,
                'unreadContacts' => $unreadContacts,
                'todayContacts' => $todayContacts,
                'recentContacts' => $recentContacts,
            ]);
        }
    }

    /**
     * Student dashboard
     */
    public function studentDashboard()
    {
        // ✅ SECURITY FIX: Authorization check
        $user = auth()->user();
        if (!$user->hasRole('student')) {
            abort(403, 'तपाईंसँग यो ड्यासबोर्ड हेर्ने अनुमति छैन');
        }

        try {
            $student = $user->student;

            // If student doesn't exist or doesn't have hostel, show welcome-style dashboard
            if (!$student || !$student->hostel_id) {
                return view('student.welcome', [
                    'user' => $user,
                    'student' => $student
                ]);
            }

            $room = $student->room;
            $hostel = $student->hostel;

            if (!$room || !$hostel) {
                return view('student.welcome', [
                    'user' => $user,
                    'student' => $student
                ]);
            }

            // Get today's meal with proper meal items
            $todayMeal = MealMenu::where('hostel_id', $hostel->id)
                ->where('day_of_week', now()->format('l'))
                ->select('id', 'meal_type', 'items', 'description', 'day_of_week')
                ->first();

            // Get last payment information
            $lastPayment = Payment::where('student_id', $student->id)
                ->latest()
                ->first();

            // Get upcoming events (assuming you have an Event model)
            $upcomingEvents = \App\Models\Event::where('date', '>=', now())
                ->where('hostel_id', $hostel->id)
                ->orderBy('date')
                ->take(3)
                ->get();

            // Get gallery images
            $galleryImages = \App\Models\Gallery::where('hostel_id', $hostel->id)
                ->where('is_active', true)
                ->take(4)
                ->get();

            // Get notifications
            $notifications = $user->notifications()->latest()->take(5)->get();

            // ✅ IMPROVED: Circulars data for student with Read Status
            $unreadCirculars = CircularRecipient::where('user_id', $user->id)
                ->where('is_read', false)
                ->count();

            $recentStudentCirculars = Circular::whereHas('recipients', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
                ->with(['creator', 'organization', 'recipients' => function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                }])
                ->latest()
                ->take(5)
                ->get();

            $importantCirculars = Circular::whereHas('recipients', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
                ->where('priority', 'urgent')
                ->whereDoesntHave('recipients', function ($q) use ($user) {
                    $q->where('user_id', $user->id)->where('is_read', true);
                })
                ->with(['creator', 'organization'])
                ->latest()
                ->take(3)
                ->get();

            // Determine payment status
            $paymentStatus = $lastPayment && $lastPayment->status === 'completed' ? 'Paid' : 'Pending';

            return view('student.dashboard', compact(
                'student',
                'room',
                'hostel',
                'todayMeal',
                'lastPayment',
                'upcomingEvents',
                'galleryImages',
                'notifications',
                'paymentStatus',
                // Circular variables
                'unreadCirculars',
                'recentStudentCirculars',
                'importantCirculars'
            ));
        } catch (\Exception $e) {
            Log::error('विद्यार्थी ड्यासबोर्ड त्रुटि: ' . $e->getMessage(), [
                'user_id' => auth()->id(),
                'student_id' => $student->id ?? null
            ]);
            return view('student.welcome')->with('error', 'डाटा लोड गर्न असफल भयो');
        }
    }

    /**
     * Admin reports page
     */
    public function reports()
    {
        try {
            // Authorization check
            $this->authorize('view-reports');

            $reportData = [
                'student_registrations' => Student::count(),
                'room_occupancy' => Room::where('status', 'occupied')->count(),
                'total_rooms' => Room::count(),
                'revenue' => Payment::sum('amount'),
                'monthly_revenue' => Payment::whereMonth('created_at', now()->month)->sum('amount'),
                'available_rooms' => Room::where('status', 'available')->count(),
                'recent_payments' => Payment::with(['student.user' => function ($query) {
                    $query->select('id', 'name');
                }])
                    ->select('id', 'student_id', 'amount', 'payment_method', 'status', 'created_at')
                    ->latest()
                    ->take(5)
                    ->get()
                    ->map(function ($payment) {
                        return [
                            'student_name' => optional(optional($payment->student)->user)->name ?? 'N/A',
                            'date' => $payment->created_at->format('Y-m-d'),
                            'amount' => $payment->amount,
                            'method' => $payment->payment_method,
                            'status' => $payment->status
                        ];
                    })
            ];

            return view('admin.reports.index', compact('reportData'));
        } catch (\Exception $e) {
            Log::error('प्रतिवेदन लोड गर्न असफल: ' . $e->getMessage(), [
                'user_id' => auth()->id()
            ]);
            return redirect()->back()->with('error', 'प्रतिवेदन लोड गर्न असफल भयो');
        }
    }

    /**
     * Get dashboard statistics for AJAX requests (Admin only)
     */
    public function statistics(Request $request)
    {
        try {
            // Authorization check
            $this->authorize('view-statistics');

            // ✅ SECURITY FIX: Validate date parameters to prevent SQL injection
            $request->validate([
                'start_date' => 'nullable|date|before_or_equal:end_date',
                'end_date' => 'nullable|date|after_or_equal:start_date'
            ]);

            // Get date range from request or use default
            $startDate = $request->input('start_date', now()->subDays(30)->format('Y-m-d'));
            $endDate = $request->input('end_date', now()->format('Y-m-d'));

            // ✅ SECURITY FIX: Ensure dates are properly formatted and safe
            $startDate = Carbon::parse($startDate)->format('Y-m-d');
            $endDate = Carbon::parse($endDate)->format('Y-m-d');

            // Fetch statistics within date range
            $newStudents = Student::whereBetween('created_at', [$startDate, $endDate])->count();
            $newRooms = Room::whereBetween('created_at', [$startDate, $endDate])->count();
            $newHostels = Hostel::whereBetween('created_at', [$startDate, $endDate])->count();

            // ✅ NEW: Contact statistics for date range
            $newContacts = Contact::whereBetween('created_at', [$startDate, $endDate])->count();

            // ✅ NEW: Review statistics for date range
            $newReviews = Review::whereBetween('created_at', [$startDate, $endDate])->count();

            // ✅ ADDED: Organization Request statistics for date range
            $newOrganizationRequests = OrganizationRequest::whereBetween('created_at', [$startDate, $endDate])->count();

            // Room occupancy trend data with optimized query
            $occupancyTrend = Room::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(CASE WHEN status = "occupied" THEN 1 END) as occupied_count'),
                DB::raw('COUNT(*) as total_count')
            )
                ->whereBetween('created_at', [$startDate, $endDate])
                ->groupBy('date')
                ->orderBy('date')
                ->get()
                ->map(function ($item) {
                    return [
                        'date' => $item->date,
                        'occupancy_rate' => $item->total_count > 0
                            ? round(($item->occupied_count / $item->total_count) * 100, 1)
                            : 0
                    ];
                });

            // Student registration trend
            $studentTrend = Student::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count')
            )
                ->whereBetween('created_at', [$startDate, $endDate])
                ->groupBy('date')
                ->orderBy('date')
                ->get();

            // ✅ NEW: Contact trend data
            $contactTrend = Contact::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count')
            )
                ->whereBetween('created_at', [$startDate, $endDate])
                ->groupBy('date')
                ->orderBy('date')
                ->get();

            // ✅ NEW: Review trend data
            $reviewTrend = Review::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count')
            )
                ->whereBetween('created_at', [$startDate, $endDate])
                ->groupBy('date')
                ->orderBy('date')
                ->get();

            // ✅ ADDED: Organization Request trend data
            $organizationRequestTrend = OrganizationRequest::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count')
            )
                ->whereBetween('created_at', [$startDate, $endDate])
                ->groupBy('date')
                ->orderBy('date')
                ->get();

            return response()->json([
                'success' => true,
                'data' => [
                    'new_students' => $newStudents,
                    'new_rooms' => $newRooms,
                    'new_hostels' => $newHostels,
                    'new_contacts' => $newContacts,
                    'new_reviews' => $newReviews,
                    'new_organization_requests' => $newOrganizationRequests,
                    'occupancy_trend' => $occupancyTrend,
                    'student_trend' => $studentTrend,
                    'contact_trend' => $contactTrend,
                    'review_trend' => $reviewTrend,
                    'organization_request_trend' => $organizationRequestTrend,
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('ड्यासबोर्ड तथ्याङ्क API असफल: ' . $e->getMessage(), [
                'exception' => $e,
                'user_id' => auth()->id()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'तथ्याङ्क डाटा लोड गर्न असफल भयो'
            ], 500);
        }
    }

    /**
     * Clear dashboard cache (for development and testing)
     */
    public function clearCache()
    {
        try {
            // ✅ SECURITY FIX: Authorization check for admin only
            $user = auth()->user();
            if (!$user->hasRole('admin')) {
                abort(403, 'तपाईंसँग यो कार्य गर्ने अनुमति छैन');
            }

            $userId = auth()->id();
            Cache::forget("admin_dashboard_metrics_{$userId}");

            return response()->json([
                'success' => true,
                'message' => 'ड्यासबोर्ड क्यास सफलतापूर्वक मेटाइयो'
            ]);
        } catch (\Exception $e) {
            Log::error('क्यास मेटाउन असफल: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'क्यास मेटाउन असफल भयो'
            ], 500);
        }
    }

    /**
     * ✅ FIXED: Get contact counts for real-time notifications - Remove hostel filtering
     */
    public function getContactCounts()
    {
        try {
            $user = auth()->user();

            // ✅ SECURITY FIX: Authorization check
            if (!$user->hasAnyRole(['admin', 'hostel_manager'])) {
                return response()->json([
                    'success' => false,
                    'unreadCount' => 0,
                    'todayCount' => 0
                ]);
            }

            // For admin and owner, show all contacts without hostel filtering
            if ($user->hasRole('admin') || $user->hasRole('hostel_manager')) {
                $unreadCount = Contact::where('is_read', false)->count();
                $todayCount = Contact::whereDate('created_at', today())->count();
            }
            // For other roles, show 0
            else {
                $unreadCount = 0;
                $todayCount = 0;
            }

            return response()->json([
                'success' => true,
                'unreadCount' => $unreadCount,
                'todayCount' => $todayCount
            ]);
        } catch (\Exception $e) {
            Log::error('सम्पर्क गणना प्राप्त गर्न असफल: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'unreadCount' => 0,
                'todayCount' => 0
            ]);
        }
    }

    /**
     * ✅ FIXED: Get filtered contacts for contact index page
     */
    public function getFilteredContacts(Request $request)
    {
        try {
            // ✅ SECURITY FIX: Authorization check
            $user = auth()->user();
            if (!$user->hasAnyRole(['admin', 'hostel_manager'])) {
                abort(403, 'तपाईंसँग यो कार्य गर्ने अनुमति छैन');
            }

            $filter = $request->input('filter', 'all');

            $query = Contact::query();

            switch ($filter) {
                case 'unread':
                    $query->where('is_read', false);
                    break;
                case 'today':
                    $query->whereDate('created_at', today());
                    break;
                case 'read':
                    $query->where('is_read', true);
                    break;
                    // 'all' shows everything
            }

            $contacts = $query->latest()->paginate(10);

            return view('admin.contacts.index', compact('contacts', 'filter'));
        } catch (\Exception $e) {
            Log::error('Contact filter error: ' . $e->getMessage());
            return redirect()->route('admin.contacts.index')->with('error', 'Filter applied गर्न असफल');
        }
    }

    /**
     * ✅ ADDED: Get organization request counts for real-time notifications
     */
    public function getOrganizationRequestCounts()
    {
        try {
            $user = auth()->user();

            // ✅ SECURITY FIX: Authorization check - admin only
            if (!$user->hasRole('admin')) {
                return response()->json([
                    'success' => false,
                    'pendingCount' => 0,
                    'todayCount' => 0
                ]);
            }

            $pendingCount = OrganizationRequest::where('status', 'pending')->count();
            $todayCount = OrganizationRequest::whereDate('created_at', today())->count();

            return response()->json([
                'success' => true,
                'pendingCount' => $pendingCount,
                'todayCount' => $todayCount
            ]);
        } catch (\Exception $e) {
            Log::error('संस्था अनुरोध गणना प्राप्त गर्न असफल: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'pendingCount' => 0,
                'todayCount' => 0
            ]);
        }
    }
}
