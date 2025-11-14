<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;
use App\Models\Hostel;
use App\Models\Room;
use App\Models\MealMenu;
use App\Models\Gallery;
use App\Models\Review;
use App\Models\Payment;
use App\Models\Circular;
use App\Models\CircularRecipient;
use App\Models\Event;
use Illuminate\Support\Facades\Log;

class StudentController extends Controller
{
    public function index()
    {
        $students = [
            ['id' => 1, 'name' => 'Student One'],
            ['id' => 2, 'name' => 'Student Two']
        ];

        return view('students.index', compact('students'));
    }

    public function myStudents()
    {
        $students = [
            ['id' => Auth::id(), 'name' => 'My Student']
        ];

        return view('students.my', compact('students'));
    }

    // PERMANENT FIX: Dashboard method with COMPLETELY FIXED circular queries
    public function dashboard()
    {
        try {
            $user = Auth::user();
            $student = $user->student;

            if (!$student || !$student->hostel_id) {
                return view('student.welcome');
            }

            $hostel = $student->hostel;
            $room = $student->room;

            // Today's meal
            $currentDay = now()->format('l');
            $todayMeal = MealMenu::where('hostel_id', $hostel->id)
                ->where('day_of_week', $currentDay)
                ->first();

            // Gallery images
            $galleryImages = Gallery::where('is_active', true)
                ->take(4)
                ->get();

            // Last payment
            $lastPayment = Payment::where('student_id', $student->id)
                ->latest()
                ->first();

            $paymentStatus = 'Unpaid';
            if ($lastPayment) {
                $paymentStatus = $lastPayment->status == 'paid' ? 'Paid' : 'Unpaid';
            }

            // ✅ COMPLETELY FIXED: Circular Data Logic
            $unreadCirculars = 0;
            $recentStudentCirculars = collect();
            $urgentCirculars = collect();
            $importantCirculars = collect();

            // ✅ CRITICAL FIX: Proper circular query for student with better error handling
            if (class_exists('App\Models\Circular') && class_exists('App\Models\CircularRecipient')) {

                try {
                    // Get user ID for recipient lookup
                    $userId = $user->id;

                    // Get circular IDs where student is recipient
                    $circularIds = CircularRecipient::where('user_id', $userId)
                        ->pluck('circular_id');

                    if ($circularIds->count() > 0) {
                        // Unread count - FIXED query
                        $unreadCirculars = CircularRecipient::where('user_id', $userId)
                            ->where('is_read', false)
                            ->count();

                        // Recent circulars - FIXED query with proper scopes
                        $recentStudentCirculars = Circular::whereIn('id', $circularIds)
                            ->where('status', 'published')
                            ->where(function ($query) {
                                $query->whereNull('published_at')
                                    ->orWhere('published_at', '<=', now());
                            })
                            ->where(function ($query) {
                                $query->whereNull('expires_at')
                                    ->orWhere('expires_at', '>', now());
                            })
                            ->with(['creator', 'organization'])
                            ->latest()
                            ->take(5)
                            ->get();

                        // Urgent circulars - FIXED query
                        $urgentCirculars = Circular::whereIn('id', $circularIds)
                            ->where('status', 'published')
                            ->where('priority', 'urgent')
                            ->where(function ($query) {
                                $query->whereNull('published_at')
                                    ->orWhere('published_at', '<=', now());
                            })
                            ->where(function ($query) {
                                $query->whereNull('expires_at')
                                    ->orWhere('expires_at', '>', now());
                            })
                            ->with(['creator', 'organization'])
                            ->latest()
                            ->take(3)
                            ->get();

                        $importantCirculars = $urgentCirculars;
                    } else {
                        // No circulars found for this student
                        $unreadCirculars = 0;
                        $recentStudentCirculars = collect();
                        $urgentCirculars = collect();
                        $importantCirculars = collect();
                    }
                } catch (\Exception $e) {
                    \Log::error('Circular data fetching error in student dashboard: ' . $e->getMessage(), [
                        'user_id' => $user->id,
                        'student_id' => $student->id
                    ]);

                    // Set default empty values on error
                    $unreadCirculars = 0;
                    $recentStudentCirculars = collect();
                    $urgentCirculars = collect();
                    $importantCirculars = collect();
                }
            } else {
                // Circular models don't exist
                $unreadCirculars = 0;
                $recentStudentCirculars = collect();
                $urgentCirculars = collect();
                $importantCirculars = collect();
            }

            // Other data
            $notifications = collect();
            $upcomingEvents = collect();

            return view('student.dashboard', compact(
                'student',
                'hostel',
                'room',
                'todayMeal',
                'galleryImages',
                'notifications',
                'upcomingEvents',
                'lastPayment',
                'paymentStatus',
                'unreadCirculars',
                'recentStudentCirculars',
                'urgentCirculars',
                'importantCirculars'
            ));
        } catch (\Exception $e) {
            \Log::error('Student dashboard error: ' . $e->getMessage(), [
                'user_id' => auth()->id(),
                'student_id' => $student->id ?? null
            ]);

            return view('student.welcome')->with('error', 'डाटा लोड गर्न असफल भयो');
        }
    }

    // Student profile for student role - ONLY ONE PROFILE METHOD
    public function profile()
    {
        $user = Auth::user();
        $student = $user->student;

        if (!$student) {
            return redirect()->route('student.dashboard')->with('error', 'विद्यार्थी प्रोफाइल फेला परेन');
        }

        return view('student.profile', compact('student'));
    }

    // Student meal menus
    public function mealMenus()
    {
        $user = Auth::user();
        $student = $user->student;

        if (!$student || !$student->hostel_id) {
            return redirect()->route('student.dashboard')->with('error', 'विद्यार्थी वा हस्टेल फेला परेन');
        }

        $mealMenus = MealMenu::where('hostel_id', $student->hostel_id)->get();

        return view('student.meal-menus', compact('student', 'mealMenus'));
    }

    // Show specific meal menu
    public function showMealMenu(MealMenu $mealMenu)
    {
        $user = Auth::user();
        $student = $user->student;

        if (!$student || $mealMenu->hostel_id != $student->hostel_id) {
            abort(403, 'तपाईंसँग यो मेनु हेर्ने अनुमति छैन');
        }

        return view('student.meal-menu-show', compact('student', 'mealMenu'));
    }

    // Update student profile
    public function updateProfile(Request $request)
    {
        $student = auth()->user()->student;

        if (!$student) {
            return redirect()->route('student.dashboard')->with('error', 'विद्यार्थी प्रोफाइल फेला परेन');
        }

        $validated = $request->validate([
            'phone' => 'required|string|max:15',
            'address' => 'required|string|max:255',
            'guardian_name' => 'required|string|max:255',
            'guardian_phone' => 'required|string|max:15',
            'guardian_relation' => 'required|string|max:50',
            'guardian_address' => 'required|string'
        ]);

        $student->update($validated);

        return redirect()->route('student.profile')
            ->with('success', 'तपाईंको प्रोफाइल सफलतापूर्वक अद्यावधिक गरियो');
    }

    // ✅ ADDED: New methods for the routes (only using existing models)
    public function gallery()
    {
        $user = Auth::user();
        $student = $user->student;

        if (!$student || !$student->hostel_id) {
            return redirect()->route('student.dashboard')->with('error', 'विद्यार्थी वा हस्टेल फेला परेन');
        }

        $galleryImages = Gallery::where('is_active', true)
            ->paginate(12);

        return view('student.gallery', compact('galleryImages', 'student'));
    }

    public function reviews()
    {
        $user = Auth::user();
        $student = $user->student;

        if (!$student || !$student->hostel_id) {
            return redirect()->route('student.dashboard')->with('error', 'विद्यार्थी वा हस्टेल फेला परेन');
        }

        // Check if Review model exists and has the required columns
        if (class_exists('App\Models\Review')) {
            $reviews = \App\Models\Review::where('hostel_id', $student->hostel_id)
                ->where('status', 'approved')
                ->with('student.user')
                ->latest()
                ->paginate(10);
        } else {
            $reviews = collect();
        }

        return view('student.reviews', compact('reviews', 'student'));
    }

    public function events()
    {
        $user = Auth::user();
        $student = $user->student;

        if (!$student || !$student->hostel_id) {
            return redirect()->route('student.dashboard')->with('error', 'विद्यार्थी वा हस्टेल फेला परेन');
        }

        // Check if Event model exists
        if (class_exists('App\Models\Event')) {
            $events = \App\Models\Event::where('event_date', '>=', now())
                ->orderBy('event_date')
                ->paginate(10);
        } else {
            $events = collect();
        }

        return view('student.events', compact('events', 'student'));
    }

    public function notifications()
    {
        $user = Auth::user();

        // Simple notifications implementation
        $notifications = [];

        return view('student.notifications', compact('notifications'));
    }

    public function submitMaintenance(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|in:low,medium,high,urgent',
        ]);

        $student = Auth::user()->student;

        if (!$student || !$student->hostel_id) {
            return redirect()->back()->with('error', 'विद्यार्थी वा हस्टेल फेला परेन');
        }

        // Check if MaintenanceRequest model exists
        if (class_exists('App\Models\MaintenanceRequest')) {
            \App\Models\MaintenanceRequest::create([
                'student_id' => $student->id,
                'hostel_id' => $student->hostel_id,
                'room_id' => $student->room_id,
                'title' => $request->title,
                'description' => $request->description,
                'priority' => $request->priority,
                'status' => 'pending'
            ]);

            return redirect()->back()->with('success', 'मर्मतको अनुरोध सफलतापूर्वक पेश गरियो');
        } else {
            return redirect()->back()->with('error', 'मर्मत सेवा अहिले उपलब्ध छैन');
        }
    }

    // ✅ NEWLY ADDED: Method to view circulars for students
    public function circulars()
    {
        $user = Auth::user();
        $student = $user->student;

        if (!$student) {
            return redirect()->route('student.dashboard')->with('error', 'विद्यार्थी प्रोफाइल फेला परेन');
        }

        // Check if circular models exist
        if (!class_exists('App\Models\Circular') || !class_exists('App\Models\CircularRecipient')) {
            return redirect()->route('student.dashboard')->with('error', 'सर्कुलर सेवा अहिले उपलब्ध छैन');
        }

        try {
            // Get circular IDs where student is recipient
            $circularIds = CircularRecipient::where('user_id', $user->id)
                ->pluck('circular_id');

            $circulars = collect();

            if ($circularIds->count() > 0) {
                $circulars = Circular::whereIn('id', $circularIds)
                    ->where('status', 'published')
                    ->where(function ($query) {
                        $query->whereNull('published_at')
                            ->orWhere('published_at', '<=', now());
                    })
                    ->where(function ($query) {
                        $query->whereNull('expires_at')
                            ->orWhere('expires_at', '>', now());
                    })
                    ->with(['creator', 'organization'])
                    ->latest()
                    ->paginate(10);
            }

            return view('student.circulars', compact('circulars', 'student'));
        } catch (\Exception $e) {
            \Log::error('Student circulars list error: ' . $e->getMessage(), [
                'user_id' => $user->id
            ]);

            return redirect()->route('student.dashboard')->with('error', 'सर्कुलरहरू लोड गर्न असफल भयो');
        }
    }

    // ✅ NEWLY ADDED: Method to mark circular as read
    public function markCircularAsRead($circularId)
    {
        try {
            $user = Auth::user();

            $recipient = CircularRecipient::where('user_id', $user->id)
                ->where('circular_id', $circularId)
                ->first();

            if ($recipient && !$recipient->is_read) {
                $recipient->update([
                    'is_read' => true,
                    'read_at' => now()
                ]);

                return response()->json(['success' => true, 'message' => 'सर्कुलर पढिएको रूपमा चिन्हित गरियो']);
            }

            return response()->json(['success' => false, 'message' => 'सर्कुलर फेला परेन वा पहिले नै पढिसकियो']);
        } catch (\Exception $e) {
            \Log::error('Mark circular as read error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'अपरेसन असफल भयो'], 500);
        }
    }
}
