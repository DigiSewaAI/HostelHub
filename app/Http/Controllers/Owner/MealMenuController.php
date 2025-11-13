<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Hostel;
use App\Models\MealMenu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class MealMenuController extends Controller
{
    // ✅ ENHANCED: Authorization helper methods
    private function authorizeMealMenuAccess(MealMenu $mealMenu = null)
    {
        $user = Auth::user();

        if ($user->hasRole('hostel_manager')) {
            if (!$user->hostel_id) {
                abort(403, 'तपाईंसँग कुनै होस्टल सम्बन्धित छैन');
            }

            if ($mealMenu && $mealMenu->hostel_id != $user->hostel_id) {
                abort(403, 'तपाईंसँग यो खानाको मेनु एक्सेस गर्ने अनुमति छैन');
            }
        }

        // Students cannot perform management operations
        if ($user->hasRole('student') && in_array(request()->route()->getActionMethod(), ['create', 'store', 'edit', 'update', 'destroy'])) {
            abort(403, 'तपाईंसँग खानाको मेनु व्यवस्थापन गर्ने अनुमति छैन');
        }

        return true;
    }

    // ✅ ENHANCED: Data scoping for students
    private function scopeForStudent($query)
    {
        $user = Auth::user();

        if ($user->hasRole('student')) {
            return $query->where('is_active', true);
        }

        return $query;
    }

    public function index()
    {
        $user = auth()->user();
        $hostelId = $user->hostel_id;

        // ✅ ENHANCED: Authorization
        $this->authorizeMealMenuAccess();

        // ✅ ENHANCED: Data scoping based on role
        $mealMenus = MealMenu::with('hostel')
            ->where('hostel_id', $hostelId)
            ->when($user->hasRole('student'), function ($query) {
                return $query->where('is_active', true);
            })
            ->latest()
            ->get();

        return view('owner.meal-menus.index', compact('mealMenus'));
    }

    public function create()
    {
        $user = auth()->user();

        // ✅ ENHANCED: Authorization - only owners can create
        if ($user->hasRole('student')) {
            abort(403, 'तपाईंसँग खानाको मेनु सिर्जना गर्ने अनुमति छैन');
        }

        $this->authorizeMealMenuAccess();

        $hostelId = $user->hostel_id;

        $hostels = Hostel::where('id', $hostelId)->get();
        $days = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
        $mealTypes = ['breakfast', 'lunch', 'dinner'];

        return view('owner.meal-menus.create', compact('hostels', 'days', 'mealTypes'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        // ✅ ENHANCED: Authorization - only owners can store
        if ($user->hasRole('student')) {
            abort(403, 'तपाईंसँग खानाको मेनु सिर्जना गर्ने अनुमति छैन');
        }

        $this->authorizeMealMenuAccess();

        $hostelId = $user->hostel_id;

        $request->validate([
            'meal_type' => 'required|in:breakfast,lunch,dinner',
            'day_of_week' => 'required|string',
            'items' => 'required|array',
            'items.*' => 'required|string',
            'image' => 'nullable|image|max:2048',
            'description' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        // Set owner's hostel_id automatically
        $data = $request->only(['meal_type', 'day_of_week', 'items', 'description']);
        $data['hostel_id'] = $hostelId;
        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('meals', 'public');
        }

        MealMenu::create($data);

        return redirect()->route('owner.meal-menus.index')
            ->with('success', 'खानाको योजना सफलतापूर्वक थपियो!');
    }

    public function show(MealMenu $mealMenu)
    {
        $user = auth()->user();

        // ✅ ENHANCED: Authorization
        $this->authorizeMealMenuAccess($mealMenu);

        // Additional check for students - they can only view active meal menus
        if ($user->hasRole('student') && !$mealMenu->is_active) {
            abort(403, 'यो खानाको मेनु हाल उपलब्ध छैन');
        }

        return view('owner.meal-menus.show', compact('mealMenu'));
    }

    public function edit(MealMenu $mealMenu)
    {
        $user = auth()->user();

        // ✅ ENHANCED: Authorization - only owners can edit
        if ($user->hasRole('student')) {
            abort(403, 'तपाईंसँग खानाको मेनु सम्पादन गर्ने अनुमति छैन');
        }

        $this->authorizeMealMenuAccess($mealMenu);

        $hostelId = $user->hostel_id;

        $hostels = Hostel::where('id', $hostelId)->get();
        $days = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
        $mealTypes = ['breakfast', 'lunch', 'dinner'];

        return view('owner.meal-menus.edit', compact('mealMenu', 'hostels', 'days', 'mealTypes'));
    }

    public function update(Request $request, MealMenu $mealMenu)
    {
        $user = auth()->user();

        // ✅ ENHANCED: Authorization - only owners can update
        if ($user->hasRole('student')) {
            abort(403, 'तपाईंसँग खानाको मेनु अपडेट गर्ने अनुमति छैन');
        }

        $this->authorizeMealMenuAccess($mealMenu);

        $hostelId = $user->hostel_id;

        $request->validate([
            'meal_type' => 'required|in:breakfast,lunch,dinner',
            'day_of_week' => 'required|string',
            'items' => 'required|array',
            'items.*' => 'required|string',
            'image' => 'nullable|image|max:2048',
            'description' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        $data = $request->only(['meal_type', 'day_of_week', 'items', 'description']);
        $data['hostel_id'] = $hostelId; // Ensure hostel_id remains the same
        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            if ($mealMenu->image) {
                Storage::disk('public')->delete($mealMenu->image);
            }
            $data['image'] = $request->file('image')->store('meals', 'public');
        }

        $mealMenu->update($data);

        return redirect()->route('owner.meal-menus.index')
            ->with('success', 'खानाको योजना सफलतापूर्वक अपडेट गरियो!');
    }

    public function destroy(MealMenu $mealMenu)
    {
        $user = auth()->user();

        // ✅ ENHANCED: Authorization - only owners can delete
        if ($user->hasRole('student')) {
            abort(403, 'तपाईंसँग खानाको मेनु हटाउने अनुमति छैन');
        }

        $this->authorizeMealMenuAccess($mealMenu);

        if ($mealMenu->image) {
            Storage::disk('public')->delete($mealMenu->image);
        }
        $mealMenu->delete();

        return redirect()->route('owner.meal-menus.index')
            ->with('success', 'खानाको योजना हटाइयो।');
    }

    /**
     * Check if the user has permission to access the meal menu
     */
    private function checkOwnership(MealMenu $mealMenu)
    {
        $user = auth()->user();

        // ✅ ENHANCED: Role-based ownership check
        if ($user->hasRole('student')) {
            // Students can only access active meal menus from their hostel
            if ($mealMenu->hostel_id != $user->hostel_id || !$mealMenu->is_active) {
                abort(403, 'तपाईंसँग यो मेनु एक्सेस गर्ने अनुमति छैन');
            }
        } elseif ($user->hasRole('hostel_manager')) {
            // Owners can only access meal menus from their hostel
            if ($mealMenu->hostel_id != $user->hostel_id) {
                abort(403, 'तपाईंसँग यो मेनु एक्सेस गर्ने अनुमति छैन');
            }
        } else {
            abort(403, 'तपाईंसँग यो मेनु एक्सेस गर्ने अनुमति छैन');
        }

        return true;
    }

    /**
     * Toggle active status of meal menu (for owners only)
     */
    public function toggleActive(MealMenu $mealMenu)
    {
        $user = auth()->user();

        // ✅ ENHANCED: Authorization - only owners can toggle active status
        if ($user->hasRole('student')) {
            abort(403, 'तपाईंसँग मेनु स्टेटस परिवर्तन गर्ने अनुमति छैन');
        }

        $this->authorizeMealMenuAccess($mealMenu);

        $mealMenu->update(['is_active' => !$mealMenu->is_active]);

        return back()->with('success', $mealMenu->is_active ?
            'खानाको मेनु सक्रिय गरियो' :
            'खानाको मेनु निष्क्रिय गरियो');
    }

    /**
     * Display meal menu for students (public view)
     */
    public function publicIndex()
    {
        $user = auth()->user();
        $hostelId = $user->hostel_id;

        // ✅ ENHANCED: Students can view public meal menus
        $mealMenus = MealMenu::with('hostel')
            ->where('hostel_id', $hostelId)
            ->where('is_active', true)
            ->orderBy('day_of_week')
            ->orderBy('meal_type')
            ->get();

        // Group by day for better display
        $groupedMealMenus = $mealMenus->groupBy('day_of_week');

        return view('owner.meal-menus.public-index', compact('groupedMealMenus'));
    }
}
