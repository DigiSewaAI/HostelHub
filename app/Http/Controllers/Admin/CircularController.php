<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Circular;
use App\Models\Organization;
use App\Models\Hostel;
use App\Models\Student;
use App\Models\CircularRecipient;
use App\Http\Requests\StoreCircularRequest;
use App\Services\CircularService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CircularController extends Controller
{
    protected $circularService;

    public function __construct(CircularService $circularService)
    {
        $this->circularService = $circularService;
    }

    public function index(Request $request)
    {
        $query = Circular::with(['organization', 'creator']);

        if ($request->has('organization_id')) {
            $query->where('organization_id', $request->organization_id);
        }

        // Filter by status
        if ($request->has('status') && in_array($request->status, ['draft', 'published', 'archived'])) {
            $query->where('status', $request->status);
        }

        // Filter by priority
        if ($request->has('priority') && in_array($request->priority, ['urgent', 'normal', 'info'])) {
            $query->where('priority', $request->priority);
        }

        $circulars = $query->latest()->paginate(20);
        $organizations = Organization::all();

        return view('admin.circulars.index', compact('circulars', 'organizations'));
    }

    public function create()
    {
        $organizations = Organization::all();
        $hostels = Hostel::all();
        $students = Student::with('user')->get();

        return view('admin.circulars.create', compact('organizations', 'hostels', 'students'));
    }

    public function store(StoreCircularRequest $request)
    {
        DB::transaction(function () use ($request) {
            $user = auth()->user();
            $data = $request->validated();

            $data['created_by'] = $user->id;

            // Create circular
            $circular = Circular::create($data);

            // Create recipients
            $this->circularService->createRecipients(
                $circular,
                $data['audience_type'],
                $data['target_audience'] ?? null
            );

            // Auto-publish if not scheduled
            if (!$circular->scheduled_at) {
                $circular->markAsPublished();
            }
        });

        $message = $request->has('scheduled_at')
            ? 'सूचना सफलतापूर्वक सिर्जना गरियो र तोकिएको समयमा प्रकाशित हुनेछ'
            : 'सूचना सफलतापूर्वक प्रकाशित गरियो';

        return redirect()->route('admin.circulars.index')->with('success', $message);
    }

    public function show(Circular $circular)
    {
        $this->circularService->authorizeView($circular, auth()->user());

        $circular->load(['organization', 'creator', 'recipients.user']);
        $readStats = $this->circularService->getReadStats($circular);

        return view('admin.circulars.show', compact('circular', 'readStats'));
    }

    public function edit(Circular $circular)
    {
        $this->circularService->authorizeEdit($circular, auth()->user());

        $organizations = Organization::all();
        $hostels = Hostel::all();
        $students = Student::with('user')->get();

        return view('admin.circulars.edit', compact('circular', 'organizations', 'hostels', 'students'));
    }

    public function update(StoreCircularRequest $request, Circular $circular)
    {
        $this->circularService->authorizeEdit($circular, auth()->user());

        $circular->update($request->validated());

        return redirect()->route('admin.circulars.show', $circular)
            ->with('success', 'सूचना सफलतापूर्वक अद्यावधिक गरियो');
    }

    public function destroy(Circular $circular)
    {
        $this->circularService->authorizeEdit($circular, auth()->user());

        $circular->delete();

        return redirect()->route('admin.circulars.index')
            ->with('success', 'सूचना सफलतापूर्वक मेटाइयो');
    }

    public function publish(Circular $circular)
    {
        $this->circularService->authorizeEdit($circular, auth()->user());

        $circular->markAsPublished();

        return back()->with('success', 'सूचना सफलतापूर्वक प्रकाशित गरियो');
    }

    public function analytics(Circular $circular = null)
    {
        if ($circular) {
            // Single circular analytics
            $this->circularService->authorizeView($circular, auth()->user());
            $stats = $this->circularService->getCircularAnalytics($circular);
            return view('admin.circulars.analytics-single', compact('circular', 'stats'));
        } else {
            // General analytics
            $stats = $this->circularService->getAdminAnalytics();
            return view('admin.circulars.analytics', compact('stats'));
        }
    }

    public function markAsRead(Circular $circular)
    {
        $user = auth()->user();

        $recipient = CircularRecipient::where('circular_id', $circular->id)
            ->where('user_id', $user->id)
            ->first();

        if ($recipient) {
            $recipient->markAsRead();
        }

        return response()->json(['success' => true]);
    }

    public function templates()
    {
        // Template data (you can replace this with actual template data from database)
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
