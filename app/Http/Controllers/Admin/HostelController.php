<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreHostelRequest;
use App\Http\Requests\UpdateHostelRequest;
use App\Models\Hostel;
use App\Models\User;
use App\Models\Organization;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class HostelController extends Controller
{
    /**
     * Display a listing of hostels.
     */
    public function index(Request $request)
    {
        // ✅ SECURITY FIX: Authorization check - ADMIN ONLY
        $user = auth()->user();
        if (!$user->hasRole('admin')) {
            abort(403, 'तपाईंसँग यो सूची हेर्ने अनुमति छैन');
        }

        // ✅ NEW: Advanced filtering with request parameters
        $query = Hostel::with(['owner', 'organization', 'organization.currentSubscription.plan'])
            ->withCount(['rooms', 'students']);

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = '%' . addcslashes($request->search, '%_') . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', $searchTerm)
                    ->orWhere('address', 'like', $searchTerm)
                    ->orWhere('city', 'like', $searchTerm)
                    ->orWhere('contact_phone', 'like', $searchTerm)
                    ->orWhereHas('owner', function ($q) use ($searchTerm) {
                        $q->where('name', 'like', $searchTerm);
                    })
                    ->orWhereHas('organization', function ($q) use ($searchTerm) {
                        $q->where('name', 'like', $searchTerm);
                    });
            });
        }

        // Status filter
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }

        // Publish status filter
        if ($request->has('publish_status') && !empty($request->publish_status)) {
            if ($request->publish_status === 'published') {
                $query->where('is_published', true);
            } else {
                $query->where('is_published', false);
            }
        }

        // Organization filter
        if ($request->has('organization_id') && !empty($request->organization_id)) {
            $query->where('organization_id', $request->organization_id);
        }

        // Sort functionality
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $hostels = $query->paginate(10)->withQueryString();

        // ✅ FIX: Ensure counts are accurate
        foreach ($hostels as $hostel) {
            if ($hostel->total_rooms == 0 && $hostel->rooms_count > 0) {
                $hostel->total_rooms = $hostel->rooms_count;
            }
            if ($hostel->available_rooms == 0) {
                $availableCount = $hostel->rooms()->where('status', 'available')->count();
                $hostel->available_rooms = $availableCount;
            }
        }

        // Statistics for admin dashboard
        $totalHostels = Hostel::count();
        $activeHostels = Hostel::where('status', 'active')->count();
        $publishedHostels = Hostel::where('is_published', true)->count();
        $organizationsWithHostels = Organization::has('hostels')->count();
        $organizations = Organization::all(); // For filter dropdown

        return view('admin.hostels.index', compact(
            'hostels',
            'totalHostels',
            'activeHostels',
            'publishedHostels',
            'organizationsWithHostels',
            'organizations',
            'request'
        ));
    }

    // ✅ FIXED: Bulk operations method with proper response
    public function bulkOperations(Request $request)
    {
        // ✅ SECURITY FIX: Authorization check
        $user = auth()->user();
        if (!$user->hasRole('admin')) {
            return response()->json([
                'success' => false,
                'message' => 'तपाईंसँग यो कार्य गर्ने अनुमति छैन'
            ], 403);
        }

        $request->validate([
            'action' => 'required|in:publish,unpublish,activate,deactivate,delete',
            'hostel_ids' => 'required|array',
            'hostel_ids.*' => 'exists:hostels,id'
        ]);

        DB::beginTransaction();

        try {
            $hostels = Hostel::whereIn('id', $request->hostel_ids)->get();
            $successCount = 0;
            $errors = [];

            foreach ($hostels as $hostel) {
                try {
                    switch ($request->action) {
                        case 'publish':
                            if (!$hostel->is_published) {
                                $hostel->update([
                                    'is_published' => true,
                                    'published_at' => now()
                                ]);
                                $successCount++;
                            }
                            break;

                        case 'unpublish':
                            if ($hostel->is_published) {
                                $hostel->update(['is_published' => false]);
                                $successCount++;
                            }
                            break;

                        case 'activate':
                            if ($hostel->status !== 'active') {
                                $hostel->update(['status' => 'active']);
                                $successCount++;
                            }
                            break;

                        case 'deactivate':
                            if ($hostel->status !== 'inactive') {
                                $hostel->update(['status' => 'inactive']);
                                $successCount++;
                            }
                            break;

                        case 'delete':
                            // Check if hostel can be deleted
                            if ($hostel->rooms()->count() > 0 || $hostel->students()->count() > 0) {
                                $errors[] = "होस्टल '{$hostel->name}' मेटाउन सकिँदैन किनभने यसको कोठा वा विद्यार्थीहरू छन्।";
                                // ✅ FIXED: Replace 'continue' with 'continue 2' for PHP 8.3
                                continue 2;
                            }

                            // Delete image if exists
                            if ($hostel->image) {
                                Storage::disk('public')->delete($hostel->image);
                            }

                            $hostel->delete();
                            $successCount++;
                            break;
                    }
                } catch (\Exception $e) {
                    $errors[] = "होस्टल '{$hostel->name}' मा त्रुटि: " . $e->getMessage();
                }
            }

            DB::commit();

            $message = "{$successCount} होस्टल सफलतापूर्वक अद्यावधिक गरियो";
            if (!empty($errors)) {
                $message .= " (" . count($errors) . " त्रुटिहरू)";
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'errors' => $errors
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'बल्क अपरेसन असफल: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for creating a new hostel.
     */
    public function create()
    {
        // ✅ SECURITY FIX: Authorization check
        $user = auth()->user();
        if (!$user->hasRole('admin')) {
            abort(403, 'तपाईंसँग यो सिर्जना गर्ने अनुमति छैन');
        }

        $organizations = Organization::with('currentSubscription')->get();
        // FIX: Use Spatie roles instead of role column
        $managers = User::role('hostel_manager')->get();

        return view('admin.hostels.create', compact('managers', 'organizations'));
    }

    /**
     * Store a newly created hostel in storage.
     */
    public function store(StoreHostelRequest $request)
    {
        // ✅ SECURITY FIX: Authorization check
        $user = auth()->user();
        if (!$user->hasRole('admin')) {
            abort(403, 'तपाईंसँग यो सिर्जना गर्ने अनुमति छैन');
        }

        DB::beginTransaction();

        try {
            $validated = $request->validated();

            // FIX: Clean the name before processing (remove any existing "होस्टेल")
            $cleanName = $this->cleanHostelName($validated['name']);
            $validated['name'] = $cleanName;

            // Check organization subscription limits
            $organization = Organization::find($request->organization_id);
            if ($organization) {
                $subscription = $organization->currentSubscription();
                if ($subscription) {
                    $currentHostelsCount = $organization->hostels()->count();
                    $maxAllowedHostels = $subscription->plan->max_hostels + ($subscription->extra_hostels ?? 0);

                    if ($currentHostelsCount >= $maxAllowedHostels) {
                        return back()->with(
                            'error',
                            "यो संस्थाको सदस्यता योजनाले {$maxAllowedHostels} होस्टेल मात्र सपोर्ट गर्छ। (हाल {$currentHostelsCount} होस्टेल छन्)"
                        );
                    }
                } else {
                    return back()->with('error', 'यो संस्थाको कुनै सक्रिय सदस्यता छैन।');
                }
            }

            // FIX: Use cleaned name for slug generation
            $validated['slug'] = $this->generateUniqueSlug($cleanName);

            // ✅ NEW: Handle branding toggle for new hostels
            $validated['show_hostelhub_branding'] = $request->has('show_hostelhub_branding');

            // Handle facilities
            if ($request->has('facilities') && !empty($request->facilities)) {
                $validated['facilities'] = json_encode(explode(',', $request->facilities));
            } else {
                $validated['facilities'] = null;
            }

            // ✅ SECURITY FIX: Enhanced file upload security
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $originalName = $image->getClientOriginalName();
                $safeName = preg_replace('/[^a-zA-Z0-9\-\._]/', '', $originalName);
                $imagePath = $image->storeAs('hostels', time() . '_' . $safeName, 'public');
                $validated['image'] = $imagePath;
            }

            // Set default status if not provided
            if (!isset($validated['status'])) {
                $validated['status'] = 'active';
            }

            $hostel = Hostel::create($validated);

            DB::commit();

            return redirect()->route('admin.hostels.index')
                ->with('success', 'होस्टल सफलतापूर्वक थपियो');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'होस्टल सिर्जना गर्दा त्रुटि: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified hostel.
     */
    public function show(Hostel $hostel)
    {
        // ✅ SECURITY FIX: Authorization check
        $user = auth()->user();
        if (!$user->hasRole('admin')) {
            abort(403, 'तपाईंसँग यो हेर्ने अनुमति छैन');
        }

        $hostel->load([
            'owner',
            'organization',
            'rooms',  // FIX: Ensure rooms relationship is loaded
            'students.user',
            'organization.currentSubscription.plan'
        ]);

        // FIX: Use dynamic counts from relationships instead of database columns
        $occupiedRooms = $hostel->rooms->where('status', 'occupied')->count();
        $availableRooms = $hostel->rooms->where('status', 'available')->count();
        $totalRooms = $hostel->rooms->count();
        $totalStudents = $hostel->students->count();

        return view('admin.hostels.show', compact(
            'hostel',
            'occupiedRooms',
            'availableRooms',
            'totalRooms',
            'totalStudents'
        ));
    }

    /**
     * Show the form for editing the specified hostel.
     */
    public function edit(Hostel $hostel)
    {
        // ✅ SECURITY FIX: Authorization check
        $user = auth()->user();
        if (!$user->hasRole('admin')) {
            abort(403, 'तपाईंसँग यो सम्पादन गर्ने अनुमति छैन');
        }

        $organizations = Organization::with('currentSubscription')->get();
        // FIX: Use Spatie roles instead of role column
        $managers = User::role('hostel_manager')->get();

        $hostel->load('organization');

        return view('admin.hostels.edit', compact('hostel', 'managers', 'organizations'));
    }

    /**
     * Update the specified hostel in storage.
     */
    public function update(UpdateHostelRequest $request, Hostel $hostel)
    {
        // ✅ SECURITY FIX: Authorization check
        $user = auth()->user();
        if (!$user->hasRole('admin')) {
            abort(403, 'तपाईंसँग यो अद्यावधिक गर्ने अनुमति छैन');
        }

        DB::beginTransaction();

        try {
            $validated = $request->validated();

            // ✅ NEW: Handle branding toggle
            $validated['show_hostelhub_branding'] = $request->has('show_hostelhub_branding');

            // FIX: Clean the name before processing (remove any existing "होस्टेल")
            if ($request->has('name')) {
                $cleanName = $this->cleanHostelName($validated['name']);
                $validated['name'] = $cleanName;
            }

            // Handle facilities
            if ($request->has('facilities') && !empty($request->facilities)) {
                $validated['facilities'] = json_encode(explode(',', $request->facilities));
            } else {
                $validated['facilities'] = null;
            }

            // Handle remove_image checkbox
            if ($request->has('remove_image') && $request->remove_image == '1') {
                // Delete old image if exists
                if ($hostel->image) {
                    Storage::disk('public')->delete($hostel->image);
                }
                $validated['image'] = null;
            }

            // ✅ SECURITY FIX: Enhanced file upload security
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($hostel->image) {
                    Storage::disk('public')->delete($hostel->image);
                }

                // Save new image with secure naming
                $image = $request->file('image');
                $originalName = $image->getClientOriginalName();
                $safeName = preg_replace('/[^a-zA-Z0-9\-\._]/', '', $originalName);
                $imagePath = $image->storeAs('hostels', time() . '_' . $safeName, 'public');
                $validated['image'] = $imagePath;
            }

            // Update slug if name changed
            if ($request->has('name') && $request->name != $hostel->name) {
                $validated['slug'] = $this->generateUniqueSlug($cleanName, $hostel->id);
            }

            $hostel->update($validated);

            DB::commit();

            return redirect()->route('admin.hostels.index')
                ->with('success', 'होस्टल सफलतापूर्वक अद्यावधिक गरियो');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'होस्टल अद्यावधिक गर्दा त्रुटि: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified hostel from storage.
     */
    public function destroy(Hostel $hostel)
    {
        // ✅ SECURITY FIX: Authorization check
        $user = auth()->user();
        if (!$user->hasRole('admin')) {
            abort(403, 'तपाईंसँग यो मेटाउने अनुमति छैन');
        }

        DB::beginTransaction();

        try {
            // Prevent deletion if hostel has rooms
            if ($hostel->rooms()->count() > 0) {
                return redirect()->back()
                    ->with('error', 'यो होस्टलमा कोठाहरू छन्। पहिले सबै कोठाहरू हटाउनुहोस्।');
            }

            // Prevent deletion if hostel has students
            if ($hostel->students()->count() > 0) {
                return redirect()->back()
                    ->with('error', 'यो होस्टलमा विद्यार्थीहरू छन्। पहिले सबै विद्यार्थीहरू हटाउनुहोस्।');
            }

            // Delete image if exists
            if ($hostel->image) {
                Storage::disk('public')->delete($hostel->image);
            }

            $hostel->delete();

            DB::commit();

            return redirect()->route('admin.hostels.index')
                ->with('success', 'होस्टल सफलतापूर्वक मेटाइयो');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'होस्टल मेटाउँदा त्रुटि: ' . $e->getMessage());
        }
    }

    /**
     * Update hostel room availability.
     */
    public function updateAvailability(Request $request, Hostel $hostel)
    {
        // ✅ SECURITY FIX: Authorization check
        $user = auth()->user();
        if (!$user->hasRole('admin')) {
            abort(403, 'तपाईंसँग यो अद्यावधिक गर्ने अनुमति छैन');
        }

        $request->validate([
            'available_rooms' => 'required|integer|min:0|max:' . $hostel->total_rooms
        ]);

        $hostel->update(['available_rooms' => $request->available_rooms]);

        return back()->with('success', 'उपलब्ध कोठाहरू अद्यावधिक गरियो');
    }

    /**
     * Toggle hostel status (active/inactive)
     */
    public function toggleStatus(Hostel $hostel)
    {
        // ✅ SECURITY FIX: Authorization check
        $user = auth()->user();
        if (!$user->hasRole('admin')) {
            abort(403, 'तपाईंसँग यो स्थिति परिवर्तन गर्ने अनुमति छैन');
        }

        $newStatus = $hostel->status == 'active' ? 'inactive' : 'active';
        $hostel->update(['status' => $newStatus]);

        $statusText = $newStatus == 'active' ? 'सक्रिय' : 'निष्क्रिय';

        return back()->with('success', "होस्टल {$statusText} गरियो।");
    }

    /**
     * Generate unique slug for hostel
     */
    private function generateUniqueSlug($name, $excludeId = null)
    {
        // FIX: Use the original name without any modifications
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $count = 1;

        $query = Hostel::where('slug', $slug);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        while ($query->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;

            $query = Hostel::where('slug', $slug);

            if ($excludeId) {
                $query->where('id', '!=', $excludeId);
            }
        }

        return $slug;
    }

    /**
     * Clean hostel name by removing any automatic "होस्टेल" additions
     */
    private function cleanHostelName($name)
    {
        // FIX: Remove any existing "होस्टेल" word from the name
        $cleanName = trim($name);

        // Remove "होस्टेल" from the end if it exists
        $cleanName = preg_replace('/\s+होस्टेल$/u', '', $cleanName);

        // Also remove from beginning (though unlikely)
        $cleanName = preg_replace('/^होस्टेल\s+/u', '', $cleanName);

        return trim($cleanName);
    }

    /**
     * Fix existing hostel names and slugs (One-time use)
     */
    public function fixExistingHostelNames()
    {
        // ✅ SECURITY FIX: Authorization check
        $user = auth()->user();
        if (!$user->hasRole('admin')) {
            abort(403, 'तपाईंसँग यो कार्य गर्ने अनुमति छैन');
        }

        DB::beginTransaction();

        try {
            $hostels = Hostel::all();
            $updatedCount = 0;

            foreach ($hostels as $hostel) {
                $oldName = $hostel->name;
                $cleanName = $this->cleanHostelName($oldName);

                // Only update if name was changed
                if ($oldName !== $cleanName) {
                    $newSlug = $this->generateUniqueSlug($cleanName, $hostel->id);

                    $hostel->update([
                        'name' => $cleanName,
                        'slug' => $newSlug
                    ]);

                    $updatedCount++;
                }
            }

            DB::commit();

            return redirect()->route('admin.hostels.index')
                ->with('success', "{$updatedCount} होस्टलहरूको नाम सफलतापूर्वक सच्याइयो");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'होस्टल नाम सच्याउँदा त्रुटि: ' . $e->getMessage());
        }
    }

    /**
     * Get hostels by organization
     */
    public function byOrganization(Organization $organization)
    {
        // ✅ SECURITY FIX: Authorization check
        $user = auth()->user();
        if (!$user->hasRole('admin')) {
            abort(403, 'तपाईंसँग यो हेर्ने अनुमति छैन');
        }

        $hostels = Hostel::where('organization_id', $organization->id)
            ->with(['owner', 'organization.currentSubscription.plan'])
            ->withCount(['rooms', 'students'])
            ->latest()
            ->paginate(10);

        return view('admin.hostels.organization', compact('hostels', 'organization'));
    }

    /**
     * Admin hostel statistics
     */
    public function statistics()
    {
        // ✅ SECURITY FIX: Authorization check
        $user = auth()->user();
        if (!$user->hasRole('admin')) {
            abort(403, 'तपाईंसँग यो तथ्याङ्क हेर्ने अनुमति छैन');
        }

        $totalHostels = Hostel::count();
        $activeHostels = Hostel::where('status', 'active')->count();
        $inactiveHostels = Hostel::where('status', 'inactive')->count();

        $hostelsByOrganization = Organization::withCount('hostels')
            ->has('hostels')
            ->orderBy('hostels_count', 'desc')
            ->limit(10)
            ->get();

        $recentHostels = Hostel::with('organization')
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.hostels.statistics', compact(
            'totalHostels',
            'activeHostels',
            'inactiveHostels',
            'hostelsByOrganization',
            'recentHostels'
        ));
    }

    /**
     * Bulk action for hostels
     */
    public function bulkAction(Request $request)
    {
        // ✅ SECURITY FIX: Authorization check
        $user = auth()->user();
        if (!$user->hasRole('admin')) {
            abort(403, 'तपाईंसँग यो कार्य गर्ने अनुमति छैन');
        }

        $request->validate([
            'action' => 'required|in:activate,deactivate,delete',
            'hostel_ids' => 'required|array',
            'hostel_ids.*' => 'exists:hostels,id'
        ]);

        DB::beginTransaction();

        try {
            $hostels = Hostel::whereIn('id', $request->hostel_ids)->get();

            switch ($request->action) {
                case 'activate':
                    foreach ($hostels as $hostel) {
                        $hostel->update(['status' => 'active']);
                    }
                    $message = 'चयन गरिएका होस्टलहरू सक्रिय गरियो।';
                    break;

                case 'deactivate':
                    foreach ($hostels as $hostel) {
                        $hostel->update(['status' => 'inactive']);
                    }
                    $message = 'चयन गरिएका होस्टलहरू निष्क्रिय गरियो।';
                    break;

                case 'delete':
                    foreach ($hostels as $hostel) {
                        // Check if hostel can be deleted
                        if ($hostel->rooms()->count() > 0 || $hostel->students()->count() > 0) {
                            return back()->with(
                                'error',
                                "होस्टल '{$hostel->name}' मेटाउन सकिँदैन किनभने यसको कोठा वा विद्यार्थीहरू छन्।"
                            );
                        }

                        // Delete image if exists
                        if ($hostel->image) {
                            Storage::disk('public')->delete($hostel->image);
                        }

                        $hostel->delete();
                    }
                    $message = 'चयन गरिएका होस्टलहरू मेटाइयो।';
                    break;
            }

            DB::commit();

            return back()->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'बल्क एक्सन गर्दा त्रुटि: ' . $e->getMessage());
        }
    }

    /**
     * Publish the specified hostel.
     */
    public function publish(Hostel $hostel)
    {
        // ✅ SECURITY FIX: Authorization check
        $user = auth()->user();
        if (!$user->hasRole('admin')) {
            abort(403, 'तपाईंसँग यो प्रकाशन गर्ने अनुमति छैन');
        }

        DB::beginTransaction();

        try {
            // Generate slug if not exists
            if (!$hostel->slug) {
                $hostel->slug = $this->generateUniqueSlug($hostel->name, $hostel->id);
            }

            $hostel->update([
                'is_published' => true,
                'published_at' => now(),
            ]);

            DB::commit();

            return redirect()->route('admin.hostels.index')
                ->with('success', 'होस्टल सफलतापूर्वक प्रकाशित गरियो');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'होस्टल प्रकाशन गर्दा त्रुटि: ' . $e->getMessage());
        }
    }

    /**
     * Unpublish the specified hostel.
     */
    public function unpublish(Hostel $hostel)
    {
        // ✅ SECURITY FIX: Authorization check
        $user = auth()->user();
        if (!$user->hasRole('admin')) {
            abort(403, 'तपाईंसँग यो अप्रकाशन गर्ने अनुमति छैन');
        }

        $hostel->update([
            'is_published' => false,
        ]);

        return redirect()->route('admin.hostels.index')
            ->with('success', 'होस्टल सफलतापूर्वक अप्रकाशित गरियो');
    }

    /**
     * ✅ NEW: Search hostels functionality with security fixes
     */
    public function search(Request $request)
    {
        // ✅ SECURITY FIX: Authorization check
        $user = auth()->user();
        if (!$user->hasRole('admin')) {
            abort(403, 'तपाईंसँग यो खोज गर्ने अनुमति छैन');
        }

        $request->validate([
            'search' => 'required|string|min:2'
        ], [
            'search.required' => 'खोज शब्द आवश्यक छ',
            'search.min' => 'खोज शब्द कम्तिमा २ अक्षरको हुनुपर्छ'
        ]);

        $query = $request->input('search');

        // ✅ SECURITY FIX: SQL Injection prevention in search
        $safeQuery = '%' . addcslashes($query, '%_') . '%';

        $hostels = Hostel::where('name', 'like', $safeQuery)
            ->orWhere('address', 'like', $safeQuery)
            ->orWhere('slug', 'like', $safeQuery)
            ->orWhereHas('owner', function ($q) use ($safeQuery) {
                $q->where('name', 'like', $safeQuery);
            })
            ->orWhereHas('organization', function ($q) use ($safeQuery) {
                $q->where('name', 'like', $safeQuery);
            })
            ->with(['owner', 'organization', 'organization.currentSubscription.plan'])
            ->withCount(['rooms', 'students'])
            ->latest()
            ->paginate(10);

        $totalHostels = Hostel::count();
        $activeHostels = Hostel::where('status', 'active')->count();
        $organizationsWithHostels = Organization::has('hostels')->count();

        return view('admin.hostels.index', compact(
            'hostels',
            'totalHostels',
            'activeHostels',
            'organizationsWithHostels'
        ));
    }
}
