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

    // StudentController.php मा dashboard() method यसरी सरल बनाउनुहोस्:

    public function dashboard()
    {
        try {
            $user = Auth::user();
            $student = $user->student;

            // ✅ CRITICAL FIX: सबै विद्यार्थीलाई dashboard देखाउने
            // यदि विद्यार्थी प्रोफाइल छैन भने dashboard नै देखाउने तर message सहित
            if (!$student) {
                return view('student.dashboard', [
                    'student' => (object)[
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'hostel_id' => null,
                        'room_id' => null
                    ],
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
                    'error' => 'विद्यार्थी प्रोफाइल फेला परेन। होस्टेल व्यवस्थापकसँग सम्पर्क गर्नुहोस्।'
                ]);
            }

            // ✅ विद्यार्थी छ भने सबै data लिने
            $hostel = $student->hostel;
            $room = $student->room;

            // Today's meal (यदि hostel छ भने)
            $todayMeal = null;
            if ($hostel) {
                $currentDay = now()->format('l');
                $todayMeal = MealMenu::where('hostel_id', $hostel->id)
                    ->where('day_of_week', $currentDay)
                    ->first();
            }

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

            // Circular data
            $unreadCirculars = 0;
            $recentStudentCirculars = collect();
            $urgentCirculars = collect();
            $importantCirculars = collect();

            if (class_exists('App\Models\Circular') && class_exists('App\Models\CircularRecipient')) {
                try {
                    $userId = $user->id;
                    $circularIds = CircularRecipient::where('user_id', $userId)
                        ->pluck('circular_id');

                    if ($circularIds->count() > 0) {
                        $unreadCirculars = CircularRecipient::where('user_id', $userId)
                            ->where('is_read', false)
                            ->count();

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
                    }
                } catch (\Exception $e) {
                    \Log::error('Circular data fetching error: ' . $e->getMessage());
                }
            }

            // Other data
            $notifications = collect();
            $upcomingEvents = collect();

            // ✅ सबै विद्यार्थीलाई dashboard देखाउने
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
            \Log::error('Student dashboard error: ' . $e->getMessage());

            // Error भएमा पनि dashboard नै देखाउने
            $user = Auth::user();
            return view('student.dashboard', [
                'student' => (object)[
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email
                ],
                'hostel' => null,
                'room' => null,
                'todayMeal' => null,
                'error' => 'डाटा लोड गर्न असफल भयो'
            ]);
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
     * ✅ FIXED: Report room issue with multiple fallback options
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

            // Prepare report data
            $reportData = [
                'student_id' => $student->id,
                'student_name' => $student->name,
                'room_id' => $room->id,
                'room_number' => $room->room_number,
                'hostel_id' => $room->hostel_id,
                'hostel_name' => $room->hostel->name ?? 'N/A',
                'issue_type' => $validated['issue_type'],
                'description' => $validated['description'],
                'priority' => $validated['priority'],
                'status' => 'pending',
                'reported_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ];

            $savedToDb = false;
            $tableName = '';

            // Try to save in room_issues table (NEW TABLE)
            if (\Schema::hasTable('room_issues')) {
                try {
                    \DB::table('room_issues')->insert($reportData);
                    $savedToDb = true;
                    $tableName = 'room_issues';
                } catch (\Exception $e) {
                    \Log::error('Room issues table error: ' . $e->getMessage());
                }
            }

            // Try to save in maintenance_requests table (EXISTING TABLE)
            if (!$savedToDb && \Schema::hasTable('maintenance_requests')) {
                try {
                    \DB::table('maintenance_requests')->insert($reportData);
                    $savedToDb = true;
                    $tableName = 'maintenance_requests';
                } catch (\Exception $e) {
                    \Log::error('Maintenance request table error: ' . $e->getMessage());
                }
            }

            // Save to log file (ALWAYS WORKS)
            $logMessage = sprintf(
                "[%s] Room Issue Report:\nStudent: %s (ID: %s)\nRoom: %s, Hostel: %s\nIssue Type: %s\nPriority: %s\nDescription: %s\n\n",
                now()->format('Y-m-d H:i:s'),
                $student->name,
                $student->id,
                $room->room_number,
                $room->hostel->name ?? 'N/A',
                $validated['issue_type'],
                $validated['priority'],
                $validated['description']
            );

            // Save to Laravel log
            \Log::info('ROOM_ISSUE_REPORT', $reportData);

            // Save to custom log file
            file_put_contents(storage_path('logs/room_issues.log'), $logMessage, FILE_APPEND);

            // Send notification to hostel owner
            $this->notifyHostelOwner($student, $room, $validated);

            $message = $savedToDb
                ? 'तपाईंको समस्या सफलतापूर्वक रिपोर्ट गरियो! (सुरक्षित गरियो: ' . $tableName . ' मा)'
                : 'तपाईंको समस्या लग गरियो। व्यवस्थापकलाई सूचना गरियो।';

            return response()->json([
                'success' => true,
                'message' => $message,
                'saved_to_db' => $savedToDb
            ]);
        } catch (\Exception $e) {
            \Log::error('Error in reportRoomIssue: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'रिपोर्ट गर्दा त्रुटि भयो: ' . $e->getMessage()
            ]);
        }
    }

    // New method to notify hostel owner
    private function notifyHostelOwner($student, $room, $data)
    {
        try {
            // Get hostel owner
            $hostel = $room->hostel;

            if ($hostel && $hostel->owner) {
                // Create notification in database
                \DB::table('notifications')->insert([
                    'user_id' => $hostel->owner->id,
                    'type' => 'room_issue',
                    'title' => 'नयाँ कोठा समस्या रिपोर्ट',
                    'message' => "विद्यार्थी: {$student->name}\nकोठा: {$room->room_number}\nसमस्या: {$data['issue_type']}",
                    'data' => json_encode([
                        'student_id' => $student->id,
                        'room_id' => $room->id,
                        'issue_type' => $data['issue_type'],
                        'priority' => $data['priority']
                    ]),
                    'read' => false,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                \Log::info('Notification sent to hostel owner: ' . $hostel->owner->email);
            }
        } catch (\Exception $e) {
            \Log::error('Failed to send notification: ' . $e->getMessage());
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
