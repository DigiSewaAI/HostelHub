<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
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
use App\Notifications\MaintenanceRequestNotification;
use App\Models\RoomIssue;
use App\Notifications\RoomIssueNotification;

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

    /**
     * 🔥 PERMANENT FIX: विद्यार्थी ड्यासबोर्ड (सबै विधिहरू प्रयोग गरेर)
     */
    public function dashboard()
    {
        try {
            $user = Auth::user();

            // ✅ PERMANENT FIX: 5 वटा तरिकाबाट student record पाउने
            $student = $this->findStudentRecord($user);

            // 🔥 CRITICAL FIX: यदि student छैन, वा status active छैन, वा hostel_id null छ भने → WELCOME PAGE
            if (!$student || $student->status !== 'active' || $student->hostel_id === null) {
                \Log::info('Student dashboard redirect: No active hostel assignment', [
                    'user_id' => $user->id,
                    'student_exists' => !is_null($student),
                    'status' => $student?->status,
                    'hostel_id' => $student?->hostel_id,
                ]);

                return redirect()->route('student.welcome')
                    ->with('error', 'तपाईंलाई कुनै होस्टेल असाइन गरिएको छैन।');
            }

            // ✅ यहाँ मात्र पूर्ण ड्यासबोर्ड डाटा लोड गर्ने (active + hostel assigned)
            return $this->loadFullDashboardData($user, $student);
        } catch (\Exception $e) {
            \Log::error('Student dashboard error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('student.welcome')
                ->with('error', 'ड्यासबोर्ड लोड गर्न असफल भयो।');
        }
    }

    /**
     * 🔥 HELPER: Student record पाउने 5 वटा तरिका
     */
    private function findStudentRecord($user)
    {
        // Method 1: Relationship बाट
        if (method_exists($user, 'student') && $user->student) {
            return $user->student;
        }

        // Method 2: Direct query (user_id)
        $student = \App\Models\Student::where('user_id', $user->id)->first();
        if ($student) {
            return $student;
        }

        // Method 3: Email द्वारा
        $student = \App\Models\Student::where('email', $user->email)->first();
        if ($student) {
            // Auto-fix: user_id सेट गर्ने
            $student->user_id = $user->id;
            $student->save();
            return $student;
        }

        // Method 4: Phone द्वारा
        if (!empty($user->phone)) {
            $student = \App\Models\Student::where('phone', $user->phone)->first();
            if ($student) {
                $student->user_id = $user->id;
                $student->save();
                return $student;
            }
        }

        // Method 5: Name द्वारा (partial match)
        $student = \App\Models\Student::where('name', 'LIKE', '%' . $user->name . '%')->first();
        if ($student) {
            $student->user_id = $user->id;
            $student->save();
            return $student;
        }

        return null;
    }

    /**
     * 🔥 HELPER: Student बिना dashboard देखाउने
     */
    private function showDashboardWithoutStudent($user)
    {
        // होस्टेल owner ले register गरेको student record खोज्ने
        $studentByEmail = \App\Models\Student::where('email', $user->email)->first();

        if ($studentByEmail) {
            // Student record छ तर user_id सेट छैन
            $studentByEmail->user_id = $user->id;
            $studentByEmail->save();

            // अब पूर्ण data लोड गर्ने
            return $this->loadFullDashboardData($user, $studentByEmail);
        }

        // Temporary student object बनाउने
        $tempStudent = (object)[
            'id' => null,
            'user_id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'hostel_id' => null,
            'room_id' => null,
            'is_temp' => true
        ];

        return view('student.dashboard', [
            'student' => $tempStudent,
            'hostel' => null,
            'room' => null,
            'todayMeal' => null,
            'galleryImages' => collect(),
            'notifications' => collect(),
            'upcomingEvents' => collect(),
            'lastPayment' => null,
            'paymentStatus' => 'Unpaid',
            'unreadCirculars' => 0,
            'recentStudentCirculars' => collect(),
            'urgentCirculars' => collect(),
            'importantCirculars' => collect(),
            'error' => 'विद्यार्थी प्रोफाइल लोड गर्न असफल भयो। होस्टेल व्यवस्थापकसँग सम्पर्क गर्नुहोस्।'
        ]);
    }

    /**
     * 🔥 HELPER: पूर्ण dashboard data लोड गर्ने
     */
    private function loadFullDashboardData($user, $student)
    {
        // Hostel लोड गर्ने
        $hostel = null;
        if ($student->hostel_id) {
            $hostel = \App\Models\Hostel::find($student->hostel_id);
        } else {
            // Emergency: User को hostel_id बाट खोज्ने
            if ($user->hostel_id) {
                $hostel = \App\Models\Hostel::find($user->hostel_id);
                if ($hostel) {
                    // Student record update गर्ने
                    $student->hostel_id = $hostel->id;
                    $student->save();
                }
            }
        }

        // Room लोड गर्ने
        $room = null;
        if ($student->room_id) {
            $room = \App\Models\Room::find($student->room_id);
        }

        // Today's meal
        $todayMeal = null;
        if ($hostel) {
            $currentDay = now()->format('l');
            $todayMeal = \App\Models\MealMenu::where('hostel_id', $hostel->id)
                ->where('day_of_week', $currentDay)
                ->first();
        }

        // Gallery images
        $galleryImages = \App\Models\Gallery::where('is_active', true)
            ->take(4)
            ->get();

        // Last successful payment (status = completed) ordered by payment_date
        $lastPayment = \App\Models\Payment::where('student_id', $student->id)
            ->where('status', \App\Models\Payment::STATUS_COMPLETED)   // use constant from Payment model
            ->latest('payment_date')
            ->first();

        $paymentStatus = 'unpaid';   // default: भुक्तानी भएको छैन
        $nextDueDate = null;
        $delayMonths = 0;

        if ($lastPayment) {
            // Calculate next due date = payment_date + 1 month
            $nextDueDate = \Carbon\Carbon::parse($lastPayment->payment_date)->addMonth();
            $today = \Carbon\Carbon::now();

            if ($today->gt($nextDueDate)) {
                $paymentStatus = 'overdue';
                // कति महिना बाँकी छ गन्ने
                $overdueCount = 0;
                $due = $nextDueDate->copy();
                while ($due->lt($today)) {
                    $overdueCount++;
                    $due->addMonth();
                }
                $delayMonths = $overdueCount;
            } else {
                $paymentStatus = 'paid';
                $delayMonths = 0;
            }
        }

        // Circular data
        $unreadCirculars = 0;
        $recentStudentCirculars = collect();
        $urgentCirculars = collect();
        $importantCirculars = collect();

        if (class_exists('App\Models\Circular') && class_exists('App\Models\CircularRecipient')) {
            try {
                $userId = $user->id;
                $circularIds = \App\Models\CircularRecipient::where('user_id', $userId)
                    ->pluck('circular_id');

                if ($circularIds->count() > 0) {
                    $unreadCirculars = \App\Models\CircularRecipient::where('user_id', $userId)
                        ->where('is_read', false)
                        ->count();

                    $recentStudentCirculars = \App\Models\Circular::whereIn('id', $circularIds)
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

                    $urgentCirculars = \App\Models\Circular::whereIn('id', $circularIds)
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
                }
            } catch (\Exception $e) {
                \Log::error('Circular data error: ' . $e->getMessage());
            }
        }

        // Other data
        $notifications = collect();
        $upcomingEvents = collect();

        // Dashboard view return गर्ने
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
            'delayMonths',
            'nextDueDate',
            'unreadCirculars',
            'recentStudentCirculars',
            'urgentCirculars',
            'importantCirculars'
        ));
    }

    /**
     * 🔥 HELPER: Error dashboard देखाउने
     */
    private function showErrorDashboard($user, $errorMessage)
    {
        if (!$user) {
            $user = Auth::user();
        }

        return view('student.dashboard', [
            'student' => (object)[
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email
            ],
            'hostel' => null,
            'room' => null,
            'todayMeal' => null,
            'error' => $errorMessage
        ]);
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

    /**
     * ✅ ULTIMATE FIX: विद्यार्थीको कोठा विवरण हेर्ने method
     */
    public function myRoom()
    {
        try {
            $user = Auth::user();
            $student = $user->student;

            if (!$student) {
                return redirect()->route('student.dashboard')
                    ->with('error', 'तपाईंको विद्यार्थी प्रोफाइल भेटिएन।');
            }

            // ✅ Get room with relationships - FIXED: image_url को सट्टा image प्रयोग गर्नुहोस्
            $room = Room::with([
                'hostel:id,name',
                'students' => function ($query) use ($student) {
                    $query->where('status', 'active')
                        ->with(['user' => function ($q) {
                            $q->select('id', 'name');
                        }]);
                },
                'galleries'
            ])->find($student->room_id);

            if (!$room) {
                return view('student.my-room-empty', compact('student'))
                    ->with('info', 'तपाईंलाई अहिले कुनै कोठा असाइन गरिएको छैन।');
            }

            // ✅ FIXED: Get roommates - Use existing columns only
            $roommates = $room->students()
                ->where('id', '!=', $student->id)
                ->where('status', 'active')
                ->select('id', 'name', 'image', 'status', 'room_id') // Only use existing columns
                ->with(['user' => function ($query) {
                    $query->select('id', 'name');
                }])
                ->get();

            // ✅ Calculate occupancy percentage
            $currentOccupancy = $room->current_occupancy ?? 0;
            $occupancyPercentage = $room->capacity > 0 ?
                ($currentOccupancy / $room->capacity) * 100 : 0;

            // ✅ Add calculated fields to room object
            $room->occupancy_percentage = round($occupancyPercentage);
            $room->available_beds_display = $room->available_beds ?? 0;

            // ✅ Get Nepali translations
            $room->nepali_status = $this->getNepaliRoomStatus($room->status ?? 'available');
            $room->nepali_type = $this->getNepaliRoomType($room->type ?? 'other');
            $room->gallery_category_nepali = $this->getNepaliGalleryCategory($room->gallery_category ?? '');

            // ✅ Set floor with safe fallback
            if (empty($room->floor)) {
                $room->floor = 'N/A';
            }

            // ✅ Get student info (for "You" badge) with safe fallback
            $student->image_url = $student->image_url ?? asset('images/default-user.png');

            return view('student.my-room', compact('room', 'roommates', 'student'));
        } catch (\Exception $e) {
            \Log::error('Student room view error: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'student_id' => $student->id ?? null,
                'error_trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('student.dashboard')
                ->with('error', 'कोठा विवरण लोड गर्न असफल भयो।');
        }
    }

    // ✅ Helper methods for Nepali translations
    private function getNepaliRoomStatus($status)
    {
        $statusMap = [
            'available' => 'उपलब्ध',
            'occupied' => 'व्यस्त',
            'partially_available' => 'आंशिक उपलब्ध',
            'maintenance' => 'मर्मतमा',
            'reserved' => 'आरक्षित',
        ];

        return $statusMap[$status] ?? $status;
    }

    /**
     * ✅ Helper method for Nepali room type
     */
    private function getNepaliRoomType($type)
    {
        $typeMap = [
            'single' => 'एक सिटर कोठा',
            'double' => 'दुई सिटर कोठा',
            'triple' => 'तीन सिटर कोठा',
            'quad' => 'चार सिटर कोठा',
            'dormitory' => 'डोर्मिटरी',
            'suite' => 'सुइट',
            '1 seater' => 'एक सिटर कोठा',
            '2 seater' => 'दुई सिटर कोठा',
            '3 seater' => 'तीन सिटर कोठा',
            '4 seater' => 'चार सिटर कोठा',
            'साझा कोठा' => 'साझा कोठा',
        ];

        return $typeMap[$type] ?? $type;
    }

    /**
     * ✅ Helper method for Nepali gallery category
     */
    private function getNepaliGalleryCategory($category)
    {
        $categoryMap = [
            'single_room' => 'एक सिटर कोठा',
            'double_room' => 'दुई सिटर कोठा',
            'triple_room' => 'तीन सिटर कोठा',
            'quad_room' => 'चार सिटर कोठा',
            'dormitory' => 'डोर्मिटरी',
            'common_areas' => 'सामान्य क्षेत्रहरू',
            'bathroom' => 'स्नानागार',
            'kitchen' => 'भान्सा',
            'garden' => 'बगैंचा',
            'reception' => 'रिसेप्सन',
            '1 seater' => '१ सिटर कोठा',
            '2 seater' => '२ सिटर कोठा',
            '3 seater' => '३ सिटर कोठा',
            '4 seater' => '४ सिटर कोठा',
            'साझा कोठा' => 'साझा कोठा',
        ];

        return $categoryMap[$category] ?? $category;
    }


    /**
     * ✅ FIXED: Report room issue with RoomIssue model and notification
     */
    public function reportRoomIssue(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'issue_type' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'priority' => 'required|in:low,medium,high,urgent'
        ]);

        try {
            $user = Auth::user();
            $student = $user->student;

            if (!$student) {
                return response()->json([
                    'success' => false,
                    'message' => 'विद्यार्थी खाता भेटिएन।'
                ]);
            }

            $room = $student->room;

            if (!$room) {
                return response()->json([
                    'success' => false,
                    'message' => 'तपाईंलाई कोठा असाइन गरिएको छैन।'
                ]);
            }

            // ✅ Create RoomIssue record using the model (only fillable fields)
            $roomIssue = RoomIssue::create([
                'student_id'   => $student->id,
                'hostel_id'    => $room->hostel_id,
                'room_id'      => $room->id,
                'issue_type'   => $validated['issue_type'],
                'description'  => $validated['description'],
                'priority'     => $validated['priority'],
                'status'       => 'pending',
                // 'image_url' is optional, not provided here
            ]);

            // ✅ Log for debugging
            \Log::info('ROOM_ISSUE_REPORT', $roomIssue->toArray());

            // ✅ Send notification to hostel owner using RoomIssueNotification
            try {
                $hostel = $room->hostel;
                if ($hostel && $hostel->owner) {
                    $hostel->owner->notify(new RoomIssueNotification($roomIssue));
                    \Log::info('RoomIssueNotification sent to owner: ' . $hostel->owner->email);
                } else {
                    \Log::warning('Hostel owner not found for room issue', [
                        'hostel_id' => $room->hostel_id,
                        'student_id' => $student->id
                    ]);
                }
            } catch (\Exception $e) {
                \Log::error('Failed to send RoomIssueNotification: ' . $e->getMessage());
            }

            return response()->json([
                'success' => true,
                'message' => 'तपाईंको समस्या सफलतापूर्वक रिपोर्ट गरियो!',
                'issue_id' => $roomIssue->id
            ]);
        } catch (\Exception $e) {
            \Log::error('Error in reportRoomIssue: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'रिपोर्ट गर्दा त्रुटि भयो: ' . $e->getMessage()
            ]);
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
