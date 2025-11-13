<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hostel;
use App\Models\MealMenu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class MealMenuController extends Controller
{
    /**
     * Get meal menus based on user role
     */
    private function getDataByRole()
    {
        $user = Auth::user();

        if ($user->hasRole('admin')) {
            // Admin can see all meal menus
            return MealMenu::with('hostel')->latest()->get();
        } elseif ($user->hasRole('hostel_manager')) {
            // Owner can only see their hostel's meal menus
            return MealMenu::with('hostel')
                ->where('hostel_id', $user->hostel_id)
                ->latest()
                ->get();
        } elseif ($user->hasRole('student')) {
            // Student can only see their hostel's meal menus
            return MealMenu::with('hostel')
                ->where('hostel_id', $user->hostel_id)
                ->where('is_active', true)
                ->latest()
                ->get();
        }

        return collect();
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // ✅ FIXED: Authorization check for all roles
        $user = Auth::user();

        if (!$user->hasAnyRole(['admin', 'hostel_manager', 'student'])) {
            abort(403, 'तपाईंसँग खानाको योजना हेर्ने अनुमति छैन');
        }

        $mealMenus = $this->getDataByRole();

        // Return appropriate view based on role
        if ($user->hasRole('student')) {
            return view('student.meal-menus.index', compact('mealMenus'));
        }

        return view('admin.meal-menus.index', compact('mealMenus'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // ✅ FIXED: Enhanced authorization check
        $user = Auth::user();

        if ($user->hasRole('student')) {
            abort(403, 'तपाईंसँग खानाको योजना सिर्जना गर्ने अनुमति छैन');
        }

        // Additional check for hostel managers
        if ($user->hasRole('hostel_manager') && !$user->hostel_id) {
            return redirect()->route('owner.hostels.index')
                ->with('error', 'कृपया पहिले आफ्नो होस्टेल सेटअप गर्नुहोस्।');
        }

        $hostels = Hostel::all();
        $days = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
        $mealTypes = ['breakfast', 'lunch', 'dinner'];

        // If user is owner, only show their hostel
        if ($user->hasRole('hostel_manager')) {
            $hostels = Hostel::where('id', $user->hostel_id)->get();

            // ✅ FIXED: Safety check if no hostel found
            if ($hostels->isEmpty()) {
                return redirect()->route('owner.hostels.index')
                    ->with('error', 'कृपया पहिले आफ्नो होस्टेल सेटअप गर्नुहोस्।');
            }
        }

        return view('admin.meal-menus.create', compact('hostels', 'days', 'mealTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // ✅ FIXED: Enhanced authorization check
        $user = Auth::user();

        if ($user->hasRole('student')) {
            abort(403, 'तपाईंसँग खानाको योजना सिर्जना गर्ने अनुमति छैन');
        }

        // ✅ FIXED: Enhanced validation with proper rules
        $validatedData = $request->validate([
            'hostel_id' => [
                'required',
                'exists:hostels,id',
                function ($attribute, $value, $fail) use ($user) {
                    // ✅ FIXED: Hostel managers can only create for their own hostel
                    if ($user->hasRole('hostel_manager') && $value != $user->hostel_id) {
                        $fail('तपाईंले आफ्नो होस्टेलको लागि मात्र योजना सिर्जना गर्न सक्नुहुन्छ।');
                    }
                }
            ],
            'meal_type' => 'required|in:breakfast,lunch,dinner',
            'day_of_week' => [
                'required',
                'string',
                Rule::in(['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'])
            ],
            'items' => 'required|array|min:1|max:20', // ✅ FIXED: Limit array size
            'items.*' => 'required|string|max:500', // ✅ FIXED: Limit item length
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120', // ✅ FIXED: Strict file validation
            'description' => 'nullable|string|max:1000',
            'is_active' => 'sometimes|boolean'
        ]);

        try {
            // ✅ FIXED: If user is owner, ensure they can only create for their hostel
            if ($user->hasRole('hostel_manager')) {
                $validatedData['hostel_id'] = $user->hostel_id;
            }

            $data = [
                'hostel_id' => $validatedData['hostel_id'],
                'meal_type' => $validatedData['meal_type'],
                'day_of_week' => $validatedData['day_of_week'],
                'items' => $validatedData['items'],
                'description' => $validatedData['description'] ?? null,
                'is_active' => $request->boolean('is_active', true)
            ];

            // ✅ FIXED: Secure file upload with proper error handling
            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image')->store('meals', 'public');

                // ✅ FIXED: Verify file was stored successfully
                if (!$data['image']) {
                    throw new \Exception('फाइल अपलोड गर्दा त्रुटि भयो');
                }
            }

            $mealMenu = MealMenu::create($data);

            // ✅ FIXED: Log the creation
            Log::info('Meal menu created successfully', [
                'meal_menu_id' => $mealMenu->id,
                'user_id' => $user->id,
                'hostel_id' => $validatedData['hostel_id']
            ]);

            return redirect()->route('meal-menus.index')
                ->with('success', 'खानाको योजना सफलतापूर्वक थपियो!');
        } catch (\Exception $e) {
            // ✅ FIXED: Proper error handling
            Log::error('Meal menu creation failed: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'hostel_id' => $request->hostel_id
            ]);

            return back()->withInput()
                ->with('error', 'खानाको योजना सिर्जना गर्दा त्रुटि भयो: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(MealMenu $mealMenu)
    {
        // ✅ FIXED: Enhanced authorization with proper error handling
        try {
            $this->checkOwnership($mealMenu);
        } catch (\Exception $e) {
            abort(403, $e->getMessage());
        }

        // ✅ FIXED: Additional check for students - only active menus
        $user = Auth::user();
        if ($user->hasRole('student') && !$mealMenu->is_active) {
            abort(403, 'यो खानाको योजना हाल उपलब्ध छैन');
        }

        if ($user->hasRole('student')) {
            return view('student.meal-menus.show', compact('mealMenu'));
        }

        return view('admin.meal-menus.show', compact('mealMenu'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MealMenu $mealMenu)
    {
        // ✅ FIXED: Enhanced authorization check
        $user = Auth::user();

        if ($user->hasRole('student')) {
            abort(403, 'तपाईंसँग खानाको योजना सम्पादन गर्ने अनुमति छैन');
        }

        // ✅ FIXED: Enhanced authorization with proper error handling
        try {
            $this->checkOwnership($mealMenu);
        } catch (\Exception $e) {
            abort(403, $e->getMessage());
        }

        $hostels = Hostel::all();
        $days = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
        $mealTypes = ['breakfast', 'lunch', 'dinner'];

        // If user is owner, only show their hostel
        if ($user->hasRole('hostel_manager')) {
            $hostels = Hostel::where('id', $user->hostel_id)->get();
        }

        return view('admin.meal-menus.edit', compact('mealMenu', 'hostels', 'days', 'mealTypes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MealMenu $mealMenu)
    {
        // ✅ FIXED: Enhanced authorization check
        $user = Auth::user();

        if ($user->hasRole('student')) {
            abort(403, 'तपाईंसँग खानाको योजना अपडेट गर्ने अनुमति छैन');
        }

        // ✅ FIXED: Enhanced authorization with proper error handling
        try {
            $this->checkOwnership($mealMenu);
        } catch (\Exception $e) {
            abort(403, $e->getMessage());
        }

        // ✅ FIXED: Enhanced validation with proper rules
        $validatedData = $request->validate([
            'hostel_id' => [
                'required',
                'exists:hostels,id',
                function ($attribute, $value, $fail) use ($user, $mealMenu) {
                    // ✅ FIXED: Hostel managers can only update their own hostel's menus
                    if ($user->hasRole('hostel_manager') && $value != $user->hostel_id) {
                        $fail('तपाईंले आफ्नो होस्टेलको लागि मात्र योजना सम्पादन गर्न सक्नुहुन्छ।');
                    }

                    // Additional check to prevent changing hostel ownership
                    if ($mealMenu->hostel_id != $value) {
                        $fail('होस्टेल परिवर्तन गर्न अनुमति छैन।');
                    }
                }
            ],
            'meal_type' => 'required|in:breakfast,lunch,dinner',
            'day_of_week' => [
                'required',
                'string',
                Rule::in(['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'])
            ],
            'items' => 'required|array|min:1|max:20',
            'items.*' => 'required|string|max:500',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'description' => 'nullable|string|max:1000',
            'is_active' => 'sometimes|boolean'
        ]);

        try {
            // ✅ FIXED: If user is owner, ensure they can only update for their hostel
            if ($user->hasRole('hostel_manager')) {
                $validatedData['hostel_id'] = $user->hostel_id;
            }

            $data = [
                'hostel_id' => $validatedData['hostel_id'],
                'meal_type' => $validatedData['meal_type'],
                'day_of_week' => $validatedData['day_of_week'],
                'items' => $validatedData['items'],
                'description' => $validatedData['description'] ?? null,
                'is_active' => $request->boolean('is_active', $mealMenu->is_active)
            ];

            // ✅ FIXED: Secure file upload with proper cleanup
            if ($request->hasFile('image')) {
                // Store new image first
                $newImagePath = $request->file('image')->store('meals', 'public');

                if ($newImagePath) {
                    $data['image'] = $newImagePath;

                    // Delete old image after successful upload
                    if ($mealMenu->image) {
                        Storage::disk('public')->delete($mealMenu->image);
                    }
                }
            }

            $mealMenu->update($data);

            // ✅ FIXED: Log the update
            Log::info('Meal menu updated successfully', [
                'meal_menu_id' => $mealMenu->id,
                'user_id' => $user->id
            ]);

            return redirect()->route('meal-menus.index')
                ->with('success', 'खानाको योजना सफलतापूर्वक अपडेट गरियो!');
        } catch (\Exception $e) {
            Log::error('Meal menu update failed: ' . $e->getMessage(), [
                'meal_menu_id' => $mealMenu->id,
                'user_id' => $user->id
            ]);

            return back()->withInput()
                ->with('error', 'खानाको योजना अपडेट गर्दा त्रुटि भयो: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MealMenu $mealMenu)
    {
        // ✅ FIXED: Enhanced authorization check
        $user = Auth::user();

        if ($user->hasRole('student')) {
            abort(403, 'तपाईंसँग खानाको योजना मेटाउने अनुमति छैन');
        }

        // ✅ FIXED: Enhanced authorization with proper error handling
        try {
            $this->checkOwnership($mealMenu);
        } catch (\Exception $e) {
            abort(403, $e->getMessage());
        }

        try {
            $mealMenuId = $mealMenu->id;
            $imagePath = $mealMenu->image;

            $mealMenu->delete();

            // ✅ FIXED: Delete image after successful deletion
            if ($imagePath) {
                Storage::disk('public')->delete($imagePath);
            }

            // ✅ FIXED: Log the deletion
            Log::info('Meal menu deleted successfully', [
                'meal_menu_id' => $mealMenuId,
                'user_id' => $user->id
            ]);

            return redirect()->route('meal-menus.index')
                ->with('success', 'खानाको योजना हटाइयो।');
        } catch (\Exception $e) {
            Log::error('Meal menu deletion failed: ' . $e->getMessage(), [
                'meal_menu_id' => $mealMenu->id,
                'user_id' => $user->id
            ]);

            return back()->with('error', 'खानाको योजना हटाउँदा त्रुटि भयो: ' . $e->getMessage());
        }
    }

    /**
     * Check if the user has permission to access the meal menu
     */
    private function checkOwnership(MealMenu $mealMenu)
    {
        $user = Auth::user();

        // Admin can access all meal menus
        if ($user->hasRole('admin')) {
            return true;
        }

        // Owner and Student can only access their hostel's meal menus
        if ($user->hasRole('hostel_manager') || $user->hasRole('student')) {
            // ✅ FIXED: Enhanced check for hostel managers without hostel_id
            if (!$user->hostel_id) {
                throw new \Exception('तपाईंको होस्टेल सेटअप गरिएको छैन। कृपया प्रशासकसँग सम्पर्क गर्नुहोस्।');
            }

            if ($mealMenu->hostel_id != $user->hostel_id) {
                throw new \Exception('तपाईंसँग यो मेनु एक्सेस गर्ने अनुमति छैन');
            }
        }

        return true;
    }
}
