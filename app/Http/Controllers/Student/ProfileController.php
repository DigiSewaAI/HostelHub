<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\Student;
use App\Models\User;

class ProfileController extends Controller
{
    // ✅ ENHANCED: Student authorization for profile access
    private function authorizeProfileAccess()
    {
        $user = Auth::user();

        // ✅ ADDED: Ensure user is a student
        if (!$user->hasRole('student')) {
            abort(403, 'तपाईंसँग प्रोफाइल व्यवस्थापन गर्ने अनुमति छैन');
        }

        $student = $user->student;
        if (!$student) {
            abort(403, 'विद्यार्थी प्रोफाइल फेला परेन');
        }

        return true;
    }

    // ✅ ENHANCED: Helper method to get authorized student
    private function getAuthorizedStudent()
    {
        $user = Auth::user();

        // ✅ ENHANCED: Authorization check
        $this->authorizeProfileAccess();

        $student = $user->student;
        if (!$student) {
            abort(403, 'विद्यार्थी प्रोफाइल फेला परेन');
        }

        return $student;
    }

    /**
     * विद्यार्थीको प्रोफाइल देखाउनुहोस्
     */
    public function show()
    {
        $user = Auth::user();
        $student = $this->getAuthorizedStudent();

        // ✅ ENHANCED: Load relationships for complete profile view
        $student->load(['user', 'hostel', 'room']);

        return view('student.profile.show', compact('student'));
    }

    /**
     * प्रोफाइल सम्पादन गर्ने फारम देखाउनुहोस्
     */
    public function edit()
    {
        $user = Auth::user();
        $student = $this->getAuthorizedStudent();

        $student->load(['user', 'hostel']);

        return view('student.profile.edit', compact('student'));
    }

    /**
     * प्रोफाइल अपडेट गर्नुहोस्
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        $student = $this->getAuthorizedStudent();

        $request->validate([
            // User fields
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id)
            ],
            'phone' => 'nullable|string|max:15',

            // Student fields
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'blood_group' => 'nullable|string|max:5',
            'nationality' => 'nullable|string|max:100',
            'permanent_address' => 'nullable|string|max:500',
            'temporary_address' => 'nullable|string|max:500',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:15',
            'emergency_contact_relation' => 'nullable|string|max:100',

            // Profile picture
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            // Update user data
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
            ]);

            // Handle profile picture upload
            $profilePicturePath = $student->profile_picture;
            if ($request->hasFile('profile_picture')) {
                // Delete old profile picture if exists
                if ($student->profile_picture && Storage::disk('public')->exists($student->profile_picture)) {
                    Storage::disk('public')->delete($student->profile_picture);
                }

                $profilePicturePath = $request->file('profile_picture')->store('student_profiles', 'public');
            }

            // Update student data
            $student->update([
                'date_of_birth' => $request->date_of_birth,
                'gender' => $request->gender,
                'blood_group' => $request->blood_group,
                'nationality' => $request->nationality,
                'permanent_address' => $request->permanent_address,
                'temporary_address' => $request->temporary_address,
                'emergency_contact_name' => $request->emergency_contact_name,
                'emergency_contact_phone' => $request->emergency_contact_phone,
                'emergency_contact_relation' => $request->emergency_contact_relation,
                'profile_picture' => $profilePicturePath,
            ]);

            return redirect()->route('student.profile.show')
                ->with('success', 'प्रोफाइल सफलतापूर्वक अपडेट गरियो!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'प्रोफाइल अपडेट गर्दा त्रुटि भयो: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * पासवर्ड परिवर्तन गर्ने फारम देखाउनुहोस्
     */
    public function changePassword()
    {
        $user = Auth::user();
        $this->getAuthorizedStudent();

        return view('student.profile.change-password');
    }

    /**
     * पासवर्ड अपडेट गर्नुहोस्
     */
    public function updatePassword(Request $request)
    {
        $user = Auth::user();
        $this->getAuthorizedStudent();

        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed|different:current_password',
        ]);

        // Verify current password
        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()
                ->with('error', 'हालको पासवर्ड गलत छ।')
                ->withInput();
        }

        try {
            $user->update([
                'password' => Hash::make($request->new_password)
            ]);

            return redirect()->route('student.profile.show')
                ->with('success', 'पासवर्ड सफलतापूर्वक परिवर्तन गरियो!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'पासवर्ड परिवर्तन गर्दा त्रुटि भयो: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * प्रोफाइल चित्र मेटाउनुहोस्
     */
    public function removeProfilePicture()
    {
        $user = Auth::user();
        $student = $this->getAuthorizedStudent();

        try {
            if ($student->profile_picture && Storage::disk('public')->exists($student->profile_picture)) {
                Storage::disk('public')->delete($student->profile_picture);
            }

            $student->update(['profile_picture' => null]);

            return redirect()->route('student.profile.edit')
                ->with('success', 'प्रोफाइल चित्र सफलतापूर्वक मेटाइयो!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'प्रोफाइल चित्र मेटाउँदा त्रुटि भयो: ' . $e->getMessage());
        }
    }

    /**
     * प्रोफाइल डाटा निर्यात गर्नुहोस् (PDF वा JSON)
     */
    public function export(Request $request)
    {
        $user = Auth::user();
        $student = $this->getAuthorizedStudent();

        $student->load(['user', 'hostel', 'room']);

        $format = $request->get('format', 'json');

        if ($format === 'pdf') {
            // TODO: Implement PDF export
            return response()->json(['message' => 'PDF export feature coming soon']);
        }

        // Default JSON export
        $profileData = [
            'personal_info' => [
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'date_of_birth' => $student->date_of_birth,
                'gender' => $student->gender,
                'blood_group' => $student->blood_group,
                'nationality' => $student->nationality,
            ],
            'address_info' => [
                'permanent_address' => $student->permanent_address,
                'temporary_address' => $student->temporary_address,
            ],
            'emergency_contact' => [
                'name' => $student->emergency_contact_name,
                'phone' => $student->emergency_contact_phone,
                'relation' => $student->emergency_contact_relation,
            ],
            'academic_info' => [
                'hostel' => $student->hostel ? $student->hostel->name : null,
                'room' => $student->room ? $student->room->room_number : null,
                'student_id' => $student->id,
                'registration_date' => $student->created_at->format('Y-m-d'),
            ]
        ];

        return response()->json($profileData);
    }

    /**
     * प्रोफाइल अवस्थिति जाँच गर्नुहोस् (API)
     */
    public function checkProfileCompletion()
    {
        $user = Auth::user();
        $student = $this->getAuthorizedStudent();

        $completionScore = 0;
        $totalFields = 0;
        $completedFields = 0;
        $missingFields = [];

        // Check user fields
        $userFields = ['name', 'email', 'phone'];
        foreach ($userFields as $field) {
            $totalFields++;
            if (!empty($user->$field)) {
                $completedFields++;
            } else {
                $missingFields[] = $field;
            }
        }

        // Check student fields
        $studentFields = [
            'date_of_birth',
            'gender',
            'blood_group',
            'nationality',
            'permanent_address',
            'temporary_address',
            'emergency_contact_name',
            'emergency_contact_phone',
            'emergency_contact_relation'
        ];

        foreach ($studentFields as $field) {
            $totalFields++;
            if (!empty($student->$field)) {
                $completedFields++;
            } else {
                $missingFields[] = $field;
            }
        }

        $completionScore = $totalFields > 0 ? round(($completedFields / $totalFields) * 100, 2) : 0;

        return response()->json([
            'completion_score' => $completionScore,
            'completed_fields' => $completedFields,
            'total_fields' => $totalFields,
            'missing_fields' => $missingFields,
            'profile_status' => $completionScore >= 80 ? 'complete' : ($completionScore >= 50 ? 'partial' : 'incomplete')
        ]);
    }

    /**
     * प्रोफाइल सक्रियता टग्गल गर्नुहोस्
     */
    public function toggleActiveStatus(Request $request)
    {
        $user = Auth::user();
        $student = $this->getAuthorizedStudent();

        $request->validate([
            'is_active' => 'required|boolean'
        ]);

        try {
            $student->update([
                'is_active' => $request->is_active
            ]);

            $status = $request->is_active ? 'सक्रिय' : 'निष्क्रिय';

            return response()->json([
                'success' => true,
                'message' => "प्रोफाइल {$status} गरियो",
                'is_active' => $student->is_active
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'स्थिति परिवर्तन गर्दा त्रुटि भयो: ' . $e->getMessage()
            ], 500);
        }
    }
}
