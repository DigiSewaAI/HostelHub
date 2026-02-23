<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Circular;
use App\Models\Organization;
use App\Models\Hostel;
use App\Models\Student;
use App\Models\CircularRecipient;
use App\Http\Requests\StoreCircularRequest;
use App\Http\Requests\UpdateCircularRequest; // ✅ Import UpdateCircularRequest
use App\Services\CircularService;
use App\Jobs\GenerateCircularRecipientsJob; // ✅ Import Job
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
        $this->middleware('role:admin');
    }

    public function index(Request $request)
    {
        if (!Auth::user()->hasRole('admin')) {
            abort(403, 'तपाईंसँग सूचनाहरू हेर्ने अनुमति छैन');
        }

        $validated = $request->validate([
            'organization_id' => 'sometimes|integer|exists:organizations,id',
            'status' => 'sometimes|in:draft,published,archived',
            'priority' => 'sometimes|in:urgent,normal,info',
            'search' => 'sometimes|string|max:255'
        ]);

        $query = Circular::with(['organization', 'creator']);

        if (isset($validated['organization_id'])) {
            $query->where('organization_id', $validated['organization_id']);
        }

        if (isset($validated['status'])) {
            $query->where('status', $validated['status']);
        }

        if (isset($validated['priority'])) {
            $query->where('priority', $validated['priority']);
        }

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
        if (!Auth::user()->hasRole('admin')) {
            abort(403, 'तपाईंसँग सूचना सिर्जना गर्ने अनुमति छैन');
        }

        $organizations = Organization::all();
        $hostels = Hostel::all();

        $students = Student::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(1000)
            ->get();

        return view('admin.circulars.create', compact('organizations', 'hostels', 'students'));
    }

    public function store(StoreCircularRequest $request)
    {
        if (!Auth::user()->hasRole('admin')) {
            abort(403, 'तपाईंसँग सूचना सिर्जना गर्ने अनुमति छैन');
        }

        try {
            DB::transaction(function () use ($request) {
                $user = Auth::user();
                $data = $request->validated();

                $data['created_by'] = $user->id;

                if (!isset($data['organization_id'])) {
                    throw new \Exception('Organization ID is required');
                }

                $circular = Circular::create($data);

                // ✅ Dispatch job instead of direct service call
                GenerateCircularRecipientsJob::dispatch($circular->id);

                if (!$circular->scheduled_at) {
                    $circular->markAsPublished();
                }

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
            Log::error('Circular creation failed: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'request_data' => $request->except(['content'])
            ]);

            return back()->withInput()
                ->with('error', 'सूचना सिर्जना गर्दा त्रुटि भयो: ' . $e->getMessage());
        }
    }

    public function show(Circular $circular)
    {
        // ✅ Use Policy instead of service
        $this->authorize('view', $circular);

        $circular->load(['organization', 'creator', 'recipients.user']);
        $readStats = $this->circularService->getReadStats($circular); // This can stay

        return view('admin.circulars.show', compact('circular', 'readStats'));
    }

    public function edit(Circular $circular)
    {
        // ✅ Use Policy
        $this->authorize('update', $circular);

        $organizations = Organization::all();
        $hostels = Hostel::all();

        $students = Student::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(1000)
            ->get();

        return view('admin.circulars.edit', compact('circular', 'organizations', 'hostels', 'students'));
    }

    public function update(UpdateCircularRequest $request, Circular $circular)
    {
        // ✅ Use Policy
        $this->authorize('update', $circular);

        try {
            // Capture old audience before update
            $oldAudience = [
                'audience_type' => $circular->audience_type,
                'target_audience' => $circular->target_audience,
                'organization_id' => $circular->organization_id,
            ];

            $data = $request->validated();

            // Handle scheduling logic if needed
            if (isset($data['scheduled_at']) && $data['scheduled_at'] > now()) {
                $data['status'] = 'draft';
                $data['published_at'] = null;
            } elseif ($circular->status === 'draft' && (!isset($data['scheduled_at']) || $data['scheduled_at'] <= now())) {
                $data['status'] = 'published';
                if (!$circular->published_at) {
                    $data['published_at'] = now();
                }
            }

            $circular->update($data);

            // Check if audience changed
            $newAudience = [
                'audience_type' => $circular->audience_type,
                'target_audience' => $circular->target_audience,
                'organization_id' => $circular->organization_id,
            ];

            if ($oldAudience != $newAudience) {
                // Delete existing recipients and dispatch job to regenerate
                $circular->recipients()->delete();
                GenerateCircularRecipientsJob::dispatch($circular->id);
            }

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
        // ✅ Use Policy
        $this->authorize('delete', $circular);

        try {
            $circularId = $circular->id;
            $circular->delete();

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
        // ✅ Use Policy (update)
        $this->authorize('update', $circular);

        try {
            $circular->markAsPublished();

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
        if (!Auth::user()->hasRole('admin')) {
            abort(403, 'तपाईंसँग विश्लेषण हेर्ने अनुमति छैन');
        }

        try {
            if ($circular) {
                // Single circular analytics
                $this->authorize('view', $circular);

                // ✅ Optimized analytics
                $stats = [
                    'total_recipients' => $circular->recipients()->count(),
                    'total_read' => $circular->recipients()->where('is_read', true)->count(),
                    'by_user_type' => $circular->recipients()
                        ->select('user_type')
                        ->selectRaw('COUNT(*) as total')
                        ->selectRaw('SUM(is_read) as read_count')
                        ->groupBy('user_type')
                        ->get(),
                    'engagement_rate' => $circular->recipients()->count() > 0 ?
                        round(($circular->recipients()->where('is_read', true)->count() / $circular->recipients()->count()) * 100, 1) : 0
                ];

                return view('admin.circulars.analytics-single', compact('circular', 'stats'));
            } else {
                // General analytics (could also be optimized, but keep as is for now)
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
        if (!Auth::user()->hasRole('admin')) {
            abort(403, 'तपाईंसँग टेम्प्लेटहरू हेर्ने अनुमति छैन');
        }

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
