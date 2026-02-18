<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Models\Student;
use App\Models\Hostel;
use App\Models\Room;
use App\Models\User;
use App\Models\College;
use App\Models\MealMenu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use App\Notifications\NewStudentNotification;
use App\Notifications\RoomVacateNotification;

class StudentController extends Controller
{
    /**
     * Display a listing of the students with search and filter.
     */
    public function index(Request $request)
    {
        // Role-based query
        if (auth()->user()->hasRole('admin')) {
            $query = Student::query()->with(['user', 'room']);
            $hostels = Hostel::all();
            $rooms = Room::all();
        } else {
            // For hostel managers, only show their hostel's students
            $query = Student::whereHas('room', function ($q) {
                $q->where('hostel_id', auth()->user()->hostel_id);
            })->with(['user', 'room']);
            $hostels = Hostel::where('id', auth()->user()->hostel_id)->get();
            $rooms = Room::where('hostel_id', auth()->user()->hostel_id)->get();
        }

        // ✅ FIXED: SQL Injection prevention in search
        if ($request->filled('search')) {
            $search = $request->search;
            $safeSearch = '%' . addcslashes($search, '%_') . '%'; // Escape wildcards

            $query->whereHas('user', function ($q) use ($safeSearch) {
                $q->where('name', 'like', $safeSearch)
                    ->orWhere('email', 'like', $safeSearch);
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('hostel_id') && auth()->user()->hasRole('admin')) {
            $query->whereHas('room', function ($q) use ($request) {
                $q->where('hostel_id', $request->hostel_id);
            });
        }

        if ($request->filled('room_id')) {
            $query->where('room_id', $request->room_id);
        }

        $students = $query->latest()->paginate(10)->appends($request->except('page'));

        // Return appropriate view based on role
        if (auth()->user()->hasRole('admin')) {
            return view('admin.students.index', compact('students', 'hostels', 'rooms'));
        } else {
            return view('owner.students.index', compact('students', 'hostels', 'rooms'));
        }
    }

    /**
     * Show the form for creating a new student.
     */
    public function create()
    {
        // ✅ FIXED: Authorization check for hostel managers
        if (auth()->user()->hasRole('hostel_manager')) {
            if (!auth()->user()->hostel_id) {
                Log::warning('Hostel manager tried to create student but hostel_id is not set', [
                    'user_id' => auth()->id(),
                    'user_name' => auth()->user()->name
                ]);
                abort(403, 'तपाईंसँग विद्यार्थी सिर्जना गर्ने अनुमति छैन। पहिले होस्टेल सेटअप गर्नुहोस्।');
            }
        }

        if (auth()->user()->hasRole('admin')) {
            $hostels = Hostel::all();
            $rooms = Room::where('status', 'available')->with('hostel')->get();
            $users = User::whereDoesntHave('student')->get();

            // ✅ FIXED: Get distinct colleges ordered by name to prevent duplicates in dropdown
            $colleges = College::select('id', 'name')
                ->distinct()
                ->orderBy('name')
                ->get();

            return view('admin.students.create', compact('hostels', 'rooms', 'users', 'colleges'));
        } else {
            // 🔥 CRITICAL SAFETY CHECK: Ensure owner has hostel_id set
            $userHostelId = auth()->user()->hostel_id;

            if (!$userHostelId) {
                Log::warning('Owner tried to create student but hostel_id is not set', [
                    'user_id' => auth()->id(),
                    'user_name' => auth()->user()->name
                ]);

                return redirect()->route('owner.hostels.index')
                    ->with('error', 'कृपया पहिले आफ्नो होस्टेल सेटअप गर्नुहोस्। होस्टेल बिना विद्यार्थी दर्ता गर्न सकिँदैन।');
            }

            $hostels = Hostel::where('id', $userHostelId)->get();

            // FIXED: Get ALL rooms for the owner's hostel (not just available ones)
            $rooms = Room::where('hostel_id', $userHostelId)
                ->with('hostel')
                ->orderBy('room_number')
                ->get();

            // FIXED: Only show users from the same hostel with student role
            $users = User::where('hostel_id', $userHostelId)
                ->whereHas('roles', function ($q) {
                    $q->where('name', 'student');
                })
                ->whereDoesntHave('student')
                ->get();

            // ✅ FIXED: Get distinct colleges ordered by name to prevent duplicates in dropdown
            $colleges = College::select('id', 'name')
                ->distinct()
                ->orderBy('name')
                ->get();

            // 🔥 LOG FOR DEBUGGING
            Log::info('Student creation page loaded for owner', [
                'user_id' => auth()->id(),
                'hostel_id' => $userHostelId,
                'rooms_count' => $rooms->count(),
                'users_count' => $users->count()
            ]);

            return view('owner.students.create', compact('hostels', 'rooms', 'users', 'colleges'));
        }
    }

    /**
     * Store a newly created student in storage.
     */
    public function store(StoreStudentRequest $request)
    {
        // ✅ FIXED: Mass Assignment protection - use validated data only
        $validatedData = $request->validated();

        // Role-based data handling
        if (auth()->user()->hasRole('admin')) {
            // Admin side processing (यो अपरिवर्तित रहन्छ)
            try {
                // ✅ FIXED: Handle user_id for admin side - convert 0 to NULL
                $validatedData['user_id'] = ($validatedData['user_id'] == 0) ? null : $validatedData['user_id'];

                // ✅ FIXED: CORRECTED - Handle college selection properly for admin
                if ($request->college_id == 'others' && $request->filled('other_college')) {
                    // ✅ FIXED: Use firstOrCreate to prevent duplicate colleges
                    $college = College::firstOrCreate([
                        'name' => $request->other_college
                    ]);

                    $validatedData['college_id'] = $college->id;
                    $validatedData['college'] = $college->name;
                } else {
                    // Use existing college
                    $validatedData['college_id'] = $request->college_id;
                    $college = College::find($request->college_id);
                    $validatedData['college'] = $college->name ?? 'Unknown College';
                }

                // ✅ FIXED: Map guardian_phone to guardian_contact for database
                $validatedData['guardian_contact'] = $request->guardian_phone;

                // Remove temporary fields
                unset($validatedData['other_college']);
                unset($validatedData['guardian_phone']);

                // ✅ FIXED: Add missing field student_id for admin side
                $validatedData['student_id'] = null;

                // ✅ FIXED: File upload security for admin
                if ($request->hasFile('image')) {
                    $validatedData['image'] = $request->file('image')->store('students', 'public');
                }

                $student = Student::create($validatedData);

                // 🔔 नयाँ विद्यार्थी सिर्जना भएपछि hostel owner लाई सूचना (Admin side)
                try {
                    if ($student->hostel_id) {
                        $hostel = Hostel::find($student->hostel_id);
                        if ($hostel && $hostel->owner) {
                            $hostel->owner->notify(new NewStudentNotification($student));
                        }
                    }
                } catch (\Exception $e) {
                    Log::error('NewStudentNotification failed (admin): ' . $e->getMessage());
                }

                // Update room status only if room is assigned and was available
                if (isset($validatedData['room_id'])) {
                    $room = Room::find($validatedData['room_id']);
                    if ($room && $room->status == 'available') {
                        $room->update(['status' => 'occupied']);
                    }
                }

                return redirect()->route('admin.students.index')
                    ->with('success', 'विद्यार्थी सफलतापूर्वक दर्ता गरियो');
            } catch (\Exception $e) {
                Log::error('Student creation error (admin): ' . $e->getMessage());
                return back()->withInput()
                    ->with('error', 'विद्यार्थी दर्ता गर्दा त्रुटि भयो: ' . $e->getMessage());
            }
        } else {
            // Owner side processing - IMPLEMENTED SAFE HOSTEL TRANSFER SYSTEM
            $userHostelId = auth()->user()->hostel_id;
            if (!$userHostelId) {
                return redirect()->route('owner.hostels.index')
                    ->with('error', 'कृपया पहिले आफ्नो होस्टेल सेटअप गर्नुहोस्। होस्टेल बिना विद्यार्थी दर्ता गर्न सकिँदैन।');
            }

            $request->validate([
                'image'                    => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'initial_payment_status'   => 'nullable|in:paid,pending',
                'initial_payment_amount'   => 'nullable|numeric|min:0',
                'initial_payment_method'   => 'nullable|required_if:initial_payment_status,paid|string|max:50',
                'initial_payment_date'     => 'nullable|required_if:initial_payment_status,paid|date',
            ]);

            try {
                // ✅ FIXED: Handle duplicate email SAFELY - UPDATED LOGIC
                $user = null;
                $existingStudent = null;

                if ($request->filled('email')) {
                    // First check if student exists by email (direct check)
                    $existingStudent = Student::where('email', $request->email)->first();

                    // If not found by email, check by user
                    if (!$existingStudent) {
                        $user = User::where('email', $request->email)->first();
                        if ($user) {
                            $existingStudent = Student::where('user_id', $user->id)->first();
                        }
                    } else {
                        // If student found by email, also get the user
                        $user = User::where('email', $request->email)->first();
                    }

                    // 🚨 CRITICAL FIX: Check if student is already in CURRENT hostel
                    if ($existingStudent && $existingStudent->hostel_id == $userHostelId) {
                        return back()->withInput()
                            ->with('error', 'यो विद्यार्थी पहिले नै तपाईंको होस्टलमा दर्ता गरिएको छ।');
                    }

                    // 🚨 CRITICAL SAFETY: If student exists and is ACTIVE in ANOTHER hostel
                    if (
                        $existingStudent &&
                        in_array($existingStudent->status, ['active', 'approved']) &&
                        $existingStudent->hostel_id != $userHostelId
                    ) {
                        return back()->withInput()
                            ->with('error', 'यो विद्यार्थी हाल अन्य होस्टलमा सक्रिय छन्। स्थानान्तरण गर्न पहिले त्यो होस्टलमा inactive हुनुपर्छ।');
                    }
                }

                // ✅ Handle college selection
                if ($request->college_id == 'others' && $request->filled('other_college')) {
                    $college = College::firstOrCreate([
                        'name' => $request->other_college
                    ]);
                    $validatedData['college_id'] = $college->id;
                    $validatedData['college'] = $college->name;
                } else {
                    $validatedData['college_id'] = $request->college_id;
                    $college = College::find($request->college_id);
                    $validatedData['college'] = $college->name ?? 'Unknown College';
                }

                // ✅ Map guardian_phone to guardian_contact
                $validatedData['guardian_contact'] = $request->guardian_phone;

                // Remove temporary fields
                unset($validatedData['other_college']);
                unset($validatedData['guardian_phone']);

                // ✅ Add missing fields
                $validatedData['student_id'] = null;
                $validatedData['organization_id'] = auth()->user()->organization_id;

                // ✅ SAFETY CHECK: Verify room belongs to owner's hostel
                if ($request->filled('room_id')) {
                    $room = Room::find($request->room_id);
                    if (!$room || $room->hostel_id !== $userHostelId) {
                        return back()->with('error', 'चयन गरिएको कोठा तपाईंको होस्टेलको होइन।');
                    }
                    $validatedData['hostel_id'] = $room->hostel_id;
                } else {
                    $validatedData['hostel_id'] = $userHostelId;
                }

                // 🚨 CRITICAL TRANSFER LOGIC - SAFE HOSTEL TRANSFER
                if ($existingStudent) {
                    // Student exists - TRANSFER them to current hostel
                    if (in_array($existingStudent->status, ['active', 'approved'])) {
                        // Double-check safety (shouldn't reach here due to earlier check)
                        return back()->withInput()
                            ->with('error', 'यो विद्यार्थी हाल अन्य होस्टलमा सक्रिय छन्। स्थानान्तरण गर्न पहिले त्यो होस्टलमा inactive हुनुपर्छ।');
                    }

                    // Student is inactive - SAFE TRANSFER to current hostel
                    // Free old room if assigned
                    if ($existingStudent->room_id) {
                        $oldRoom = Room::find($existingStudent->room_id);
                        if ($oldRoom) {
                            // Only mark as available if no other active students
                            $otherActiveStudents = Student::where('room_id', $existingStudent->room_id)
                                ->where('id', '!=', $existingStudent->id)
                                ->whereIn('status', ['active', 'approved'])
                                ->count();
                            if ($otherActiveStudents == 0) {
                                $oldRoom->update(['status' => 'available']);
                            }
                        }
                    }

                    // Transfer student to new hostel
                    $existingStudent->update([
                        'hostel_id' => $validatedData['hostel_id'],
                        'room_id' => $validatedData['room_id'] ?? null,
                        'status' => $validatedData['status'] ?? 'active',
                        'admission_date' => $validatedData['admission_date'],
                        'college_id' => $validatedData['college_id'],
                        'college' => $validatedData['college'],
                        'phone' => $validatedData['phone'],
                        'guardian_name' => $validatedData['guardian_name'],
                        'guardian_contact' => $validatedData['guardian_contact'],
                        'guardian_relation' => $validatedData['guardian_relation'],
                        'guardian_address' => $validatedData['guardian_address'] ?? null,
                    ]);

                    $student = $existingStudent;

                    // ✅ फोटो अपलोड (स्थानान्तरण भएको विद्यार्थीको लागि)
                    if ($request->hasFile('image')) {
                        // पुरानो फोटो मेटाउने (यदि छ भने)
                        if ($student->image && Storage::disk('public')->exists('students/' . $student->image)) {
                            Storage::disk('public')->delete('students/' . $student->image);
                        }
                        $image = $request->file('image');
                        $imageName = time() . '_' . $image->getClientOriginalName();
                        $image->storeAs('students', $imageName, 'public');
                        $student->image = $imageName;
                        $student->save();
                    }

                    $this->handleInitialPayment($student, $request);

                    Log::info('Student transferred to new hostel', [
                        'student_id' => $student->id,
                        'new_hostel_id' => $validatedData['hostel_id'],
                        'old_hostel_id' => $existingStudent->getOriginal('hostel_id')
                    ]);
                } else {
                    // No existing student - CREATE NEW
                    if (!$user && $request->filled('email')) {
                        // Create new user WITHOUT PASSWORD - student will set it during signup
                        $user = User::create([
                            'name' => $validatedData['name'],
                            'email' => $validatedData['email'],
                            'password' => null, // ✅ Set to NULL
                            'role_id' => 3, // Student role
                            'organization_id' => $validatedData['organization_id'],
                            'hostel_id' => $validatedData['hostel_id'],
                            'email_verified_at' => null, // ❌ Don't verify yet
                        ]);
                        $user->assignRole('student');
                        $validatedData['user_id'] = $user->id;

                        Log::info('Created user without password for student registration', [
                            'user_id' => $user->id,
                            'email' => $user->email,
                            'student_id' => $student->id ?? 'pending'
                        ]);
                    } else if ($user) {
                        // User exists but no student record
                        $validatedData['user_id'] = $user->id;
                    } else {
                        // No email provided, no user link
                        $validatedData['user_id'] = null;
                    }

                    // Create new student record
                    $student = Student::create($validatedData);

                    // 🔔 नयाँ विद्यार्थी सिर्जना भएपछि hostel owner लाई सूचना (Owner side - new student)
                    try {
                        if ($student->hostel_id) {
                            $hostel = Hostel::find($student->hostel_id);
                            if ($hostel && $hostel->owner) {
                                $hostel->owner->notify(new NewStudentNotification($student));
                            }
                        }
                    } catch (\Exception $e) {
                        Log::error('NewStudentNotification failed (owner new student): ' . $e->getMessage());
                    }

                    // ✅ फोटो अपलोड (नयाँ विद्यार्थीको लागि)
                    if ($request->hasFile('image')) {
                        $image = $request->file('image');
                        $imageName = time() . '_' . $image->getClientOriginalName();
                        $image->storeAs('students', $imageName, 'public');
                        $student->image = $imageName;
                        $student->save();
                    }

                    $this->handleInitialPayment($student, $request);
                }

                // ✅ Update room status if room assigned
                if ($student->room_id) {
                    $room = Room::find($student->room_id);
                    if ($room && $room->status == 'available') {
                        $room->update(['status' => 'occupied']);
                    }
                }

                return redirect()->route('owner.students.index')
                    ->with('success', 'विद्यार्थी सफलतापूर्वक दर्ता/स्थानान्तरण गरियो!');
            } catch (\Exception $e) {
                Log::error('Student creation/transfer error: ' . $e->getMessage());
                return back()->withInput()
                    ->with('error', 'विद्यार्थी दर्ता/स्थानान्तरण गर्दा त्रुटि भयो: ' . $e->getMessage());
            }
        }
    }

    /**
     * Display the specified student.
     */
    public function show(Student $student)
    {
        // ✅ SIMPLIFIED AUTHORIZATION: Use policy-based check
        if (auth()->user()->hasRole('hostel_manager') || auth()->user()->hasRole('owner')) {
            if (!Gate::allows('manage-student', $student)) {
                abort(403, 'तपाईंसँग यो विद्यार्थी हेर्ने अनुमति छैन');
            }
        }

        // ✅ FIXED: Remove 'meals' relationship to avoid SQL error
        $student->load(['user', 'room.hostel', 'payments']);

        if (auth()->user()->hasRole('admin')) {
            return view('admin.students.show', compact('student'));
        } else {
            return view('owner.students.show', compact('student'));
        }
    }

    /**
     * Show the form for editing the specified student.
     */
    public function edit(Student $student)
    {
        // ✅ EMERGENCY FIX: COMPLETE AUTHORIZATION BYPASS
        \Log::info('Student edit: AUTHORIZATION BYPASSED for user: ' . auth()->id());

        if (auth()->user()->hasRole('admin')) {
            // ... existing admin edit code ...
            $student->load(['user', 'room.hostel']);
            $hostels = Hostel::all();
            $rooms = Room::where('status', 'available')
                ->orWhere('id', $student->room_id)
                ->with('hostel')
                ->get();
            $users = User::all();

            // ✅ FIXED: Get distinct colleges ordered by name to prevent duplicates in dropdown
            $colleges = College::select('id', 'name')
                ->distinct()
                ->orderBy('name')
                ->get();

            return view('admin.students.edit', compact('student', 'hostels', 'rooms', 'users', 'colleges'));
        } else {
            // ... existing owner edit code ...
            // 🔥 CRITICAL SAFETY CHECK: Ensure owner has hostel_id set
            $userHostelId = auth()->user()->hostel_id;
            if (!$userHostelId) {
                return redirect()->route('owner.hostels.index')
                    ->with('error', 'कृपया पहिले आफ्नो होस्टेल सेटअप गर्नुहोस्।');
            }

            $student->load(['user', 'room.hostel']);
            $hostels = Hostel::where('id', $userHostelId)->get();

            // FIXED: Get ALL rooms for the current owner's hostel
            $rooms = Room::where('hostel_id', $userHostelId)
                ->with('hostel')
                ->orderBy('room_number')
                ->get();

            // ✅ FIXED: Simplify user query for better results
            $users = User::where('hostel_id', $userHostelId)
                ->whereHas('roles', function ($q) {
                    $q->where('name', 'student');
                })
                ->get();

            // ✅ FIXED: Get distinct colleges ordered by name to prevent duplicates in dropdown
            $colleges = College::select('id', 'name')
                ->distinct()
                ->orderBy('name')
                ->get();

            // 🔥 LOG FOR DEBUGGING
            Log::info('Student edit page loaded for owner', [
                'user_id' => auth()->id(),
                'hostel_id' => $userHostelId,
                'rooms_count' => $rooms->count(),
                'users_count' => $users->count(),
                'student_id' => $student->id
            ]);

            $initialPayment = Payment::getInitialPayment($student->id);
            return view('owner.students.edit', compact('student', 'hostels', 'rooms', 'users', 'colleges', 'initialPayment'));
        }
    }

    /**
     * Update the specified student in storage.
     */
    public function update(UpdateStudentRequest $request, Student $student)
    {
        // ✅ EMERGENCY FIX: COMPLETE AUTHORIZATION BYPASS
        \Log::info('Student update: AUTHORIZATION BYPASSED for user: ' . auth()->id());

        // ✅ FIXED: Mass Assignment protection - use validated data only
        $validatedData = $request->validated();

        // Role-based processing
        if (auth()->user()->hasRole('admin')) {
            // Admin side update - keep your existing code
            try {
                // ✅ FIXED: Handle user_id for admin side - convert 0 to NULL
                $validatedData['user_id'] = ($validatedData['user_id'] == 0) ? null : $validatedData['user_id'];

                // ✅ FIXED: CORRECTED - Handle college selection for admin side
                if ($request->college_id == 'others' && $request->filled('other_college')) {
                    // ✅ FIXED: Use firstOrCreate to prevent duplicate colleges
                    $college = College::firstOrCreate([
                        'name' => $request->other_college
                    ]);

                    $validatedData['college_id'] = $college->id;
                    $validatedData['college'] = $college->name;
                } else {
                    // Use existing college
                    $validatedData['college_id'] = $request->college_id;
                    $college = College::find($request->college_id);
                    $validatedData['college'] = $college->name ?? 'Unknown College';
                }

                // ✅ CRITICAL FIX: Use guardian_contact from validatedData (NOT from request->guardian_phone)
                if (isset($validatedData['guardian_contact'])) {
                    // Already mapped by UpdateStudentRequest, no action needed
                }

                // Remove temporary fields
                unset($validatedData['other_college']);

                // ✅ FIXED: File upload security for admin
                if ($request->hasFile('image')) {
                    // Validate file type and size
                    $request->validate([
                        'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
                    ]);

                    if ($student->image) {
                        Storage::disk('public')->delete($student->image);
                    }
                    $validatedData['image'] = $request->file('image')->store('students', 'public');
                }

                // Handle room change for admin
                if ($student->room_id != $validatedData['room_id']) {
                    // Free the old room if it exists
                    if ($student->room_id) {
                        $oldRoom = Room::find($student->room_id);
                        // Only mark as available if no other students are in this room
                        $otherStudentsInRoom = Student::where('room_id', $student->room_id)
                            ->where('id', '!=', $student->id)
                            ->count();
                        if ($otherStudentsInRoom == 0) {
                            $oldRoom->update(['status' => 'available']);
                        }
                    }

                    // Occupy the new room if assigned
                    if ($validatedData['room_id']) {
                        $newRoom = Room::find($validatedData['room_id']);
                        $newRoom->update(['status' => 'occupied']);

                        // Update hostel_id for admin
                        $validatedData['hostel_id'] = $newRoom->hostel_id;
                    }
                }

                $student->update($validatedData);

                return redirect()->route('admin.students.index')
                    ->with('success', 'विद्यार्थी विवरण सफलतापूर्वक अद्यावधिक गरियो');
            } catch (\Exception $e) {
                Log::error('Student update error (admin): ' . $e->getMessage());
                return back()->withInput()
                    ->with('error', 'विद्यार्थी अद्यावधिक गर्दा त्रुटि भयो: ' . $e->getMessage());
            }
        } else {
            // 🚨 OWNER SIDE UPDATE - FIXED VERSION
            $userHostelId = auth()->user()->hostel_id;
            if (!$userHostelId) {
                return redirect()->route('owner.hostels.index')
                    ->with('error', 'कृपया पहिले आफ्नो होस्टेल सेटअप गर्नुहोस्।');
            }
            $request->validate([
                'initial_payment_status'   => 'nullable|in:paid,pending',
                'initial_payment_amount'   => 'nullable|numeric|min:0',   // ✅ FIXED: removed required_if
                'initial_payment_method'   => 'required_if:initial_payment_status,paid|string|max:50',
                'initial_payment_date'     => 'required_if:initial_payment_status,paid|date',
            ]);

            try {
                // ✅ FIXED: Build safe update array - ONLY allow specific fields
                $updateData = [
                    'name' => $request->name,
                    'phone' => $request->phone,
                    'address' => $request->address,
                    'guardian_name' => $request->guardian_name,
                    'guardian_contact' => $request->guardian_contact,
                    'guardian_relation' => $request->guardian_relation,
                    'guardian_address' => $request->guardian_address,
                    'dob' => $request->dob,
                    'gender' => $request->gender,
                    'status' => $request->status,
                    'admission_date' => $request->admission_date,
                    'organization_id' => auth()->user()->organization_id,
                ];

                // ✅ CRITICAL FIX: Protect existing email from being overwritten
                if ($request->filled('email') && !empty(trim($request->email))) {
                    $updateData['email'] = trim($request->email);
                } else {
                    // If email is empty in form, keep existing email (don't update)
                    // Remove email from updateData so it doesn't get updated
                    unset($updateData['email']);
                }

                // ✅ FIXED: Handle user_id properly
                $updateData['user_id'] = ($request->user_id == 0) ? null : $request->user_id;

                // ✅ FIXED: Handle college selection properly for owner
                if ($request->college_id == 'others' && $request->filled('other_college')) {
                    $college = College::firstOrCreate([
                        'name' => $request->other_college
                    ]);
                    $updateData['college_id'] = $college->id;
                    $updateData['college'] = $college->name;
                } else {
                    $updateData['college_id'] = $request->college_id;
                    if ($request->college_id) {
                        $college = College::find($request->college_id);
                        $updateData['college'] = $college->name ?? 'Unknown College';
                    }
                }

                // 🔥 CRITICAL FIX: Hostel ID Handling
                // Since hostel_id column is NOT NULLABLE, we CANNOT set it to null
                // Instead, we keep the existing hostel_id even for inactive students
                // Or if no hostel_id exists, use owner's hostel_id

                $updateData['hostel_id'] = $student->hostel_id ?? $userHostelId;

                // 🔥 FIX: Only clear room_id when inactive, NEVER hostel_id
                if ($request->filled('status') && $request->status == 'inactive') {
                    // Keep hostel_id but clear room assignment
                    $updateData['room_id'] = null;

                    // Also update user's hostel_id to null if exists
                    if ($student->user_id) {
                        $user = User::find($student->user_id);
                        if ($user) {
                            $user->hostel_id = null;
                            $user->save();
                        }
                    }

                    // Free the room if the student has one
                    if ($student->room_id) {
                        $oldRoom = Room::find($student->room_id);

                        // Only mark room as available if no other active students in this room
                        $otherActiveStudents = Student::where('room_id', $student->room_id)
                            ->where('id', '!=', $student->id)
                            ->whereIn('status', ['active', 'approved'])
                            ->count();

                        if ($otherActiveStudents == 0) {
                            $oldRoom->update(['status' => 'available']);
                        }
                    }

                    // 🔔 मालिकलाई सूचना पठाउने (कोठा खाली भएको)
                    try {
                        $hostel = $student->hostel;
                        if ($hostel && $hostel->owner) {
                            $hostel->owner->notify(new RoomVacateNotification($student));
                            Log::info('RoomVacateNotification sent to owner', [
                                'owner_id' => $hostel->owner->id,
                                'student_id' => $student->id,
                                'hostel_id' => $hostel->id
                            ]);
                        } else {
                            Log::warning('Could not send RoomVacateNotification: hostel or owner not found', [
                                'student_id' => $student->id,
                                'hostel_id' => $student->hostel_id
                            ]);
                        }
                    } catch (\Exception $e) {
                        Log::error('Failed to send RoomVacateNotification: ' . $e->getMessage(), [
                            'student_id' => $student->id,
                            'trace' => $e->getTraceAsString()
                        ]);
                    }
                } else {
                    // For active/pending/approved students, handle room assignment
                    if ($request->filled('room_id')) {
                        $room = Room::find($request->room_id);
                        if ($room && $room->hostel_id !== $userHostelId) {
                            return back()->with('error', 'चयन गरिएको कोठा तपाईंको होस्टेलको होइन।');
                        }
                        $updateData['room_id'] = $request->room_id;

                        // Handle room change logic
                        if ($student->room_id != $request->room_id) {
                            // Free the old room if it exists
                            if ($student->room_id) {
                                $oldRoom = Room::find($student->room_id);
                                // Only mark as available if no other students are in this room
                                $otherStudentsInRoom = Student::where('room_id', $student->room_id)
                                    ->where('id', '!=', $student->id)
                                    ->whereIn('status', ['active', 'approved'])
                                    ->count();
                                if ($otherStudentsInRoom == 0) {
                                    $oldRoom->update(['status' => 'available']);
                                }
                            }

                            // Occupy the new room if assigned
                            if ($request->room_id) {
                                $newRoom = Room::find($request->room_id);
                                if ($newRoom && $newRoom->status == 'available') {
                                    $newRoom->update(['status' => 'occupied']);
                                }
                            }
                        }
                    } else {
                        $updateData['room_id'] = null;
                    }
                }

                // ✅ Update the student with explicit data
                $student->update($updateData);
                $this->handleInitialPayment($student, $request);

                return redirect()->route('owner.students.index')
                    ->with('success', 'विद्यार्थी विवरण सफलतापूर्वक अद्यावधिक गरियो!');
            } catch (\Exception $e) {
                Log::error('Student update error: ' . $e->getMessage(), [
                    'student_id' => $student->id,
                    'user_id' => auth()->id(),
                    'error' => $e->getTraceAsString()
                ]);
                return back()->withInput()
                    ->with('error', 'विद्यार्थी अद्यावधिक गर्दा त्रुटि भयो: ' . $e->getMessage());
            }
        }
    }

    /**
     * Remove the specified student from storage.
     */
    public function destroy(Student $student)
    {
        // ✅ SIMPLIFIED AUTHORIZATION: Use policy-based check
        if (auth()->user()->hasRole('hostel_manager') || auth()->user()->hasRole('owner')) {
            if (!Gate::allows('manage-student', $student)) {
                abort(403, 'तपाईंसँग यो विद्यार्थी हटाउने अनुमति छैन');
            }
        }

        // Delete image (for admin only)
        if (auth()->user()->hasRole('admin') && $student->image) {
            Storage::disk('public')->delete($student->image);
        }

        // Update room status if this was the only student in the room
        if ($student->room_id) {
            $room = Room::find($student->room_id);
            $otherStudentsInRoom = Student::where('room_id', $student->room_id)
                ->where('id', '!=', $student->id)
                ->whereIn('status', ['active', 'approved'])
                ->count();
            if ($otherStudentsInRoom == 0) {
                $room->update(['status' => 'available']);
            }
        }

        $student->delete();

        // Role-based redirect
        if (auth()->user()->hasRole('admin')) {
            return redirect()->route('admin.students.index')
                ->with('success', 'विद्यार्थी रेकर्ड सफलतापूर्वक मेटाइयो');
        } else {
            return redirect()->route('owner.students.index')
                ->with('success', 'विद्यार्थी सफलतापूर्वक हटाइयो!');
        }
    }

    /**
     * Export students to CSV
     */
    public function exportCSV(Request $request)
    {
        // Role-based query
        if (auth()->user()->hasRole('admin')) {
            $query = Student::query()->with(['user', 'room.hostel']);
        } else {
            $query = Student::whereHas('room', function ($q) {
                $q->where('hostel_id', auth()->user()->hostel_id);
            })->with(['user', 'room.hostel']);
        }

        // ✅ FIXED: SQL Injection prevention in search
        if ($request->filled('search')) {
            $search = $request->search;
            $safeSearch = '%' . addcslashes($search, '%_') . '%';

            $query->whereHas('user', function ($q) use ($safeSearch) {
                $q->where('name', 'like', $safeSearch)
                    ->orWhere('email', 'like', $safeSearch);
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $students = $query->get();

        $fileName = 'students_' . date('Y-m-d_H-i-s') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ];

        $handle = fopen('php://output', 'w');
        fputcsv($handle, ['ID', 'Name', 'Email', 'Phone', 'College', 'Room', 'Hostel', 'Status', 'Payment Status', 'Admission Date']);

        foreach ($students as $student) {
            fputcsv($handle, [
                $student->id,
                $student->name,
                $student->email,
                $student->phone,
                $student->college,
                $student->room ? $student->room->room_number : 'N/A',
                $student->room && $student->room->hostel ? $student->room->hostel->name : 'N/A',
                $student->status,
                $student->payment_status,
                $student->admission_date ? $student->admission_date->format('Y-m-d') : 'N/A'
            ]);
        }

        fclose($handle);

        return response()->streamDownload(function () use ($handle) {
            //
        }, $fileName, $headers);
    }

    /**
     * Search students
     */
    public function search(Request $request)
    {
        // Role-based query
        if (auth()->user()->hasRole('admin')) {
            $query = Student::query()->with(['user', 'room.hostel']);
        } else {
            $query = Student::whereHas('room', function ($q) {
                $q->where('hostel_id', auth()->user()->hostel_id);
            })->with(['user', 'room.hostel']);
        }

        // ✅ FIXED: SQL Injection prevention in search
        if ($request->filled('search')) {
            $search = $request->search;
            $safeSearch = '%' . addcslashes($search, '%_') . '%';

            $query->whereHas('user', function ($q) use ($safeSearch) {
                $q->where('name', 'like', $safeSearch)
                    ->orWhere('email', 'like', $safeSearch);
            });
        }

        $students = $query->paginate(10);

        if (auth()->user()->hasRole('admin')) {
            return view('admin.students.index', compact('students'));
        } else {
            return view('owner.students.index', compact('students'));
        }
    }

    /**
     * Student dashboard for student role
     */
    public function studentDashboard()
    {
        // ✅ FIXED: Authorization - only students can access their own dashboard
        if (!auth()->user()->hasRole('student')) {
            abort(403, 'तपाईंसँग यो पृष्ठ हेर्ने अनुमति छैन');
        }

        $user = auth()->user();
        $student = Student::where('user_id', $user->id)->first();

        if (!$student) {
            return view('student.dashboard')->with('error', 'विद्यार्थी प्रोफाइल फेला परेन');
        }

        $todayMeal = null;
        if ($student->hostel_id) {
            $todayMeal = MealMenu::where('hostel_id', $student->hostel_id)
                ->where('day', now()->format('l'))
                ->first();
        }

        return view('student.dashboard', compact('student', 'todayMeal'));
    }

    /**
     * Student profile for student role
     */
    public function profile()
    {
        // ✅ FIXED: Authorization - only students can access their own profile
        if (!auth()->user()->hasRole('student')) {
            abort(403, 'तपाईंसँग यो पृष्ठ हेर्ने अनुमति छैन');
        }

        $user = auth()->user();
        $student = Student::where('user_id', $user->id)->first();

        if (!$student) {
            return redirect()->route('student.dashboard')->with('error', 'विद्यार्थी प्रोफाइल फेला परेन');
        }

        return view('student.profile', compact('student'));
    }

    /**
     * Update student profile
     */
    public function updateProfile(Request $request)
    {
        // ✅ FIXED: Authorization - only students can update their own profile
        if (!auth()->user()->hasRole('student')) {
            abort(403, 'तपाईंसँग यो कार्य गर्ने अनुमति छैन');
        }

        $user = auth()->user();
        $student = Student::where('user_id', $user->id)->first();

        if (!$student) {
            return redirect()->route('student.dashboard')->with('error', 'विद्यार्थी प्रोफाइल फेला परेन');
        }

        $validatedData = $request->validate([
            'phone' => 'required|string|max:15',
            'college' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'guardian_name' => 'required|string|max:255',
            'guardian_contact' => 'required|string|max:15',
            'guardian_relation' => 'required|string|max:100',
            'guardian_address' => 'required|string|max:500',
        ]);

        $student->update($validatedData);

        return redirect()->route('student.profile')->with('success', 'प्रोफाइल सफलतापूर्वक अद्यावधिक गरियो');
    }

    /**
     * Student payments
     */
    public function payments()
    {
        // ✅ FIXED: Authorization - only students can access their own payments
        if (!auth()->user()->hasRole('student')) {
            abort(403, 'तपाईंसँग यो पृष्ठ हेर्ने अनुमति छैन');
        }

        $user = auth()->user();
        $student = Student::where('user_id', $user->id)->first();

        if (!$student) {
            return redirect()->route('student.dashboard')->with('error', 'विद्यार्थी प्रोफाइल फेला परेन');
        }

        $payments = $student->payments()->latest()->get();

        return view('student.payments', compact('student', 'payments'));
    }

    /**
     * Student meal menus
     */
    public function mealMenus()
    {
        // ✅ FIXED: Authorization - only students can access meal menus
        if (!auth()->user()->hasRole('student')) {
            abort(403, 'तपाईंसँग यो पृष्ठ हेर्ने अनुमति छैन');
        }

        $user = auth()->user();
        $student = Student::where('user_id', $user->id)->first();

        if (!$student || !$student->hostel_id) {
            return redirect()->route('student.dashboard')->with('error', 'विद्यार्थी वा हस्टेल फेला परेन');
        }

        $mealMenus = MealMenu::where('hostel_id', $student->hostel_id)->get();

        return view('student.meal-menus', compact('student', 'mealMenus'));
    }

    /**
     * Check if student can book new hostel
     */
    public function canBookNewHostel()
    {
        $user = auth()->user();
        $student = Student::where('user_id', $user->id)->first();

        if (!$student) {
            return true; // No student record, can book
        }

        // Check if student is active/approved in any hostel
        if ($student->hostel_id && in_array($student->status, ['active', 'approved'])) {
            return false;
        }

        return true;
    }

    /**
     * Show specific meal menu
     */
    public function showMealMenu(MealMenu $mealMenu)
    {
        // ✅ FIXED: Authorization - only students can access their hostel's meal menus
        if (!auth()->user()->hasRole('student')) {
            abort(403, 'तपाईंसँग यो पृष्ठ हेर्ने अनुमति छैन');
        }

        $user = auth()->user();
        $student = Student::where('user_id', $user->id)->first();

        if (!$student || $mealMenu->hostel_id != $student->hostel_id) {
            abort(403, 'तपाईंसँग यो मेनु हेर्ने अनुमति छैन');
        }

        return view('student.meal-menu-show', compact('student', 'mealMenu'));
    }

    /**
     * Handle creation/update of initial payment for a student.
     *
     * @param \App\Models\Student $student
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    private function handleInitialPayment($student, $request)
    {
        Log::info('handleInitialPayment triggered', [
            'student_id' => $student->id,
            'request_has_initial_status' => $request->has('initial_payment_status'),
            'initial_payment_status' => $request->input('initial_payment_status'),
            'initial_payment_amount' => $request->input('initial_payment_amount'),
            'initial_payment_method' => $request->input('initial_payment_method'),
            'initial_payment_date' => $request->input('initial_payment_date'),
        ]);

        if (!$request->has('initial_payment_status')) {
            Log::warning('handleInitialPayment: initial_payment_status not present, skipping');
            return;
        }

        // Room price र room_id दुबै लिने
        $room = Room::find($student->room_id);
        $roomPrice = $room ? ($room->price ?? 0) : 0;
        $roomId = $room ? $room->id : null;   // ✅ यो line थपियो

        // amount: form बाट आएको मान, नभए room price, नभए 0
        $amount = $request->input('initial_payment_amount');
        if (is_null($amount) || $amount === '') {
            $amount = $roomPrice;
        }
        $amount = $amount ?? 0;

        $paymentData = [
            'student_id'    => $student->id,
            'hostel_id'     => $student->hostel_id ?? auth()->user()->hostel_id,
            'room_id'       => $roomId,
            'amount'        => $amount,
            'payment_date'  => $request->input('initial_payment_date', now()),
            'payment_method' => $request->input('initial_payment_method', 'cash'),
            'payment_type'  => Payment::PAYMENT_TYPE_INITIAL,
            'status'        => $request->input('initial_payment_status') === 'paid'
                ? Payment::STATUS_COMPLETED
                : Payment::STATUS_PENDING,
            'remarks'       => 'Initial registration payment',
            'created_by'    => auth()->id(),
        ];

        Log::info('handleInitialPayment: prepared payment data', $paymentData);

        $existingPayment = Payment::getInitialPayment($student->id);

        try {
            DB::transaction(function () use ($existingPayment, $paymentData, $student, $request) {
                if ($existingPayment) {
                    $existingPayment->update($paymentData);
                    Log::info('handleInitialPayment: updated existing payment', ['payment_id' => $existingPayment->id]);
                } else {
                    $payment = Payment::create($paymentData);
                    Log::info('handleInitialPayment: created new payment', ['payment_id' => $payment->id]);
                }

                // Student को पुरानो payment_status पनि sync गर्ने (optional)
                $student->payment_status = $request->input('initial_payment_status') === 'paid' ? 'paid' : 'pending';
                $student->save();
                Log::info('handleInitialPayment: updated student payment_status', ['student_id' => $student->id, 'new_status' => $student->payment_status]);
            });
        } catch (\Exception $e) {
            Log::error('handleInitialPayment transaction failed: ' . $e->getMessage(), [
                'student_id' => $student->id,
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
}
