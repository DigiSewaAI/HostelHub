<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\Hostel;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class ContactController extends Controller
{
    /**
     * Get data based on user role
     */
    private function getDataByRole()
    {
        $user = Auth::user();

        if ($user->hasRole('owner') || $user->hasRole('hostel_manager')) {
            // For owners, get contacts from their organization's hostels
            $organization = $user->organizations()->first();
            if ($organization) {
                $hostelIds = $organization->hostels()->pluck('id');
                return Contact::whereIn('hostel_id', $hostelIds);
            }
            return Contact::where('id', 0); // No organization, return empty
        }

        return Contact::query();
    }

    /**
     * Display a listing of contact form submissions.
     */
    public function index(Request $request)
    {
        $filter = $request->input('filter', 'all');
        $search = $request->input('search');

        $query = $this->getDataByRole()->with(['hostel', 'room']);

        // ✅ SECURITY FIX: Use parameter binding for search
        if ($search) {
            $safeSearch = '%' . addcslashes($search, '%_') . '%';
            $query->where(function ($q) use ($safeSearch) {
                $q->where('name', 'like', $safeSearch)
                    ->orWhere('email', 'like', $safeSearch)
                    ->orWhere('subject', 'like', $safeSearch)
                    ->orWhere('message', 'like', $safeSearch)
                    ->orWhereHas('room', function ($q) use ($safeSearch) {
                        $q->where('room_number', 'like', $safeSearch);
                    })
                    ->orWhereHas('hostel', function ($q) use ($safeSearch) {
                        $q->where('name', 'like', $safeSearch);
                    });
            });
        }

        // ✅ Apply filters
        switch ($filter) {
            case 'unread':
                $query->where('is_read', false);
                break;
            case 'today':
                $query->whereDate('created_at', today());
                break;
            case 'read':
                $query->where('is_read', true);
                break;
        }

        $contacts = $query->latest()->paginate(10);

        // ✅ Calculate counts for statistics
        $unreadCount = $this->getDataByRole()->where('is_read', false)->count();
        $todayCount = $this->getDataByRole()->whereDate('created_at', today())->count();
        $repliedCount = $this->getDataByRole()->where('status', 'replied')->count();

        return view('admin.contacts.index', compact('contacts', 'filter', 'unreadCount', 'todayCount', 'repliedCount'));
    }

    /**
     * Show the form for creating a new contact.
     */
    public function create()
    {
        // ✅ ADDED: Get hostels and rooms for dropdowns
        $hostels = Hostel::all();
        $rooms = Room::all();

        return view('admin.contacts.create', compact('hostels', 'rooms'));
    }

    /**
     * Store a newly created contact.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'hostel_id' => 'nullable|exists:hostels,id',
            'room_id' => 'nullable|exists:rooms,id',
        ], [
            'name.required' => 'नाम अनिवार्य छ।',
            'email.required' => 'इमेल अनिवार्य छ।',
            'email.email' => 'कृपया वैध इमेल ठेगाना प्रविष्ट गर्नुहोस्।',
            'subject.required' => 'विषय अनिवार्य छ।',
            'message.required' => 'सन्देश अनिवार्य छ।',
        ]);

        // ✅ SECURITY FIX: Use only validated data
        $validated = $request->only(['name', 'email', 'phone', 'subject', 'message', 'hostel_id', 'room_id']);

        $contactData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'subject' => $validated['subject'],
            'message' => $validated['message'],
            'status' => 'नयाँ',
            'is_read' => false,
        ];

        // ✅ FIXED: Always add hostel_id and room_id if provided
        if (!empty($validated['hostel_id'])) {
            $contactData['hostel_id'] = $validated['hostel_id'];
        }

        if (!empty($validated['room_id'])) {
            $contactData['room_id'] = $validated['room_id'];
        }

        // ✅ FIXED: Add hostel_id for owners automatically
        $user = Auth::user();
        if ($user->hasRole('owner') || $user->hasRole('hostel_manager')) {
            $organization = $user->organizations()->first();
            if ($organization) {
                $hostel = $organization->hostels()->first();
                if ($hostel) {
                    $contactData['hostel_id'] = $hostel->id;
                }
            }
            $contactData['status'] = 'pending'; // Use English status for owners
        }

        Contact::create($contactData);

        // ✅ FIXED: Redirect based on user role
        $route = ($user->hasRole('owner') || $user->hasRole('hostel_manager')) ? 'owner.contacts.index' : 'admin.contacts.index';

        return redirect()->route($route)
            ->with('success', ($user->hasRole('owner') || $user->hasRole('hostel_manager')) ? 'सन्देश सफलतापूर्वक पठाइयो!' : 'सम्पर्क सफलतापूर्वक थपियो!');
    }

    /**
     * Display specific contact information.
     */
    public function show($id)
    {
        $contact = $this->getDataByRole()->with(['hostel', 'room'])->findOrFail($id);

        // ✅ SECURITY FIX: Explicit ownership verification
        $user = Auth::user();
        if (($user->hasRole('owner') || $user->hasRole('hostel_manager')) && !$this->isUserContact($contact)) {
            abort(403, 'तपाईंसँग यो सन्देश हेर्ने अनुमति छैन');
        }

        return view('admin.contacts.show', compact('contact'));
    }

    /**
     * Show the form for editing the specified contact.
     */
    public function edit($id)
    {
        $contact = $this->getDataByRole()->with(['hostel', 'room'])->findOrFail($id);

        // ✅ SECURITY FIX: Explicit ownership verification
        $user = Auth::user();
        if (($user->hasRole('owner') || $user->hasRole('hostel_manager')) && !$this->isUserContact($contact)) {
            abort(403, 'तपाईंसँग यो सन्देश सम्पादन गर्ने अनुमति छैन');
        }

        $hostels = Hostel::all();
        $rooms = Room::all();

        return view('admin.contacts.edit', compact('contact', 'hostels', 'rooms'));
    }

    /**
     * Update the specified contact.
     */
    public function update(Request $request, $id)
    {
        $contact = $this->getDataByRole()->findOrFail($id);

        // ✅ SECURITY FIX: Explicit ownership verification
        $user = Auth::user();
        if (($user->hasRole('owner') || $user->hasRole('hostel_manager')) && !$this->isUserContact($contact)) {
            abort(403, 'तपाईंसँग यो सन्देश अपडेट गर्ने अनुमति छैन');
        }

        // Owners can only update status
        if ($user->hasRole('owner') || $user->hasRole('hostel_manager')) {
            $request->validate([
                'status' => 'required|in:pending,read,replied',
            ]);

            $contact->update($request->only('status'));

            $route = ($user->hasRole('owner') || $user->hasRole('hostel_manager')) ? 'owner.contacts.index' : 'admin.contacts.index';

            return redirect()->route($route)
                ->with('success', 'सन्देश स्थिति सफलतापूर्वक अद्यावधिक गरियो!');
        }

        // Admin can update all fields
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'hostel_id' => 'nullable|exists:hostels,id',
            'room_id' => 'nullable|exists:rooms,id',
            'status' => 'required|in:नयाँ,पढियो,जवाफ दिइयो,pending,read,replied',
        ], [
            'name.required' => 'नाम अनिवार्य छ।',
            'email.required' => 'इमेल अनिवार्य छ।',
            'email.email' => 'कृपया वैध इमेल ठेगाना प्रविष्ट गर्नुहोस्।',
            'subject.required' => 'विषय अनिवार्य छ।',
            'message.required' => 'सन्देश अनिवार्य छ।',
        ]);

        // ✅ SECURITY FIX: Use only validated data
        $validated = $request->only(['name', 'email', 'phone', 'subject', 'message', 'hostel_id', 'room_id', 'status']);

        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'subject' => $validated['subject'],
            'message' => $validated['message'],
            'status' => $validated['status'],
        ];

        // ✅ FIXED: Always update hostel_id and room_id if provided
        if (!empty($validated['hostel_id'])) {
            $updateData['hostel_id'] = $validated['hostel_id'];
        }
        if (!empty($validated['room_id'])) {
            $updateData['room_id'] = $validated['room_id'];
        }

        // Update is_read based on status
        if ($validated['status'] == 'read' || $validated['status'] == 'पढियो') {
            $updateData['is_read'] = true;
        } elseif ($validated['status'] == 'नयाँ' || $validated['status'] == 'pending') {
            $updateData['is_read'] = false;
        }

        $contact->update($updateData);

        return redirect()->route('admin.contacts.index')
            ->with('success', 'सम्पर्क सफलतापूर्वक अपडेट गरियो!');
    }

    /**
     * Update contact status (Quick update for read/unread).
     */
    public function updateStatus(Request $request, $id)
    {
        try {
            $request->validate([
                'status' => 'required|in:read,unread'
            ]);

            $contact = $this->getDataByRole()->findOrFail($id);

            // ✅ SECURITY FIX: Explicit ownership verification
            $user = Auth::user();
            if (($user->hasRole('owner') || $user->hasRole('hostel_manager')) && !$this->isUserContact($contact)) {
                abort(403, 'तपाईंसँग यो सन्देश अपडेट गर्ने अनुमति छैन');
            }

            if ($request->status == 'read') {
                $contact->update([
                    'is_read' => true,
                    'status' => 'read'
                ]);
                $message = 'सन्देश पढिएको चिन्ह लगाइयो';
            } else {
                $contact->update([
                    'is_read' => false,
                    'status' => 'unread'
                ]);
                $message = 'सन्देश नपढिएको चिन्ह लगाइयो';
            }

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => $message
                ]);
            }

            return redirect()->back()->with('success', $message);
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'अपडेट गर्न असफल: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'अपडेट गर्न असफल: ' . $e->getMessage());
        }
    }

    /**
     * Remove contact information.
     */
    public function destroy($id)
    {
        try {
            $contact = $this->getDataByRole()->findOrFail($id);

            // ✅ SECURITY FIX: Explicit ownership verification
            $user = Auth::user();
            if (($user->hasRole('owner') || $user->hasRole('hostel_manager')) && !$this->isUserContact($contact)) {
                abort(403, 'तपाईंसँग यो सन्देश हटाउने अनुमति छैन');
            }

            $contact->delete();

            // ✅ FIXED: Redirect based on user role with proper success message
            $route = ($user->hasRole('owner') || $user->hasRole('hostel_manager')) ? 'owner.contacts.index' : 'admin.contacts.index';
            $message = ($user->hasRole('owner') || $user->hasRole('hostel_manager')) ? 'सन्देश सफलतापूर्वक मेटाइयो!' : 'सम्पर्क जानकारी सफलतापूर्वक मेटाइयो!';

            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $message
                ]);
            }

            return redirect()->route($route)->with('success', $message);
        } catch (\Exception $e) {
            Log::error('Contact deletion failed: ' . $e->getMessage());

            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'सन्देश मेटाउन असफल भयो'
                ], 500);
            }

            $route = (Auth::user()->hasRole('owner') || Auth::user()->hasRole('hostel_manager')) ? 'owner.contacts.index' : 'admin.contacts.index';
            return redirect()->route($route)->with('error', 'सन्देश मेटाउन असफल भयो');
        }
    }

    /**
     * Check if contact belongs to user's organization
     */
    private function isUserContact($contact)
    {
        $user = Auth::user();

        if ($user->hasRole('owner') || $user->hasRole('hostel_manager')) {
            $organization = $user->organizations()->first();
            if ($organization && $contact->hostel_id) {
                $hostelIds = $organization->hostels()->pluck('id');
                return $hostelIds->contains($contact->hostel_id);
            }
            return false;
        }

        return true; // Admin can access all contacts
    }

    /**
     * Bulk delete contact information.
     */
    public function bulkDestroy(Request $request)
    {
        $ids = $request->input('ids');

        if (!$ids) {
            return redirect()->back()
                ->with('error', 'कुनै सम्पर्क जानकारी चयन गरिएन!');
        }

        // ✅ SECURITY FIX: Validate IDs are integers
        $validIds = array_filter($ids, 'is_numeric');

        $user = Auth::user();

        // For owners, only allow deleting their organization's contacts
        if ($user->hasRole('owner') || $user->hasRole('hostel_manager')) {
            $organization = $user->organizations()->first();
            if ($organization) {
                $hostelIds = $organization->hostels()->pluck('id');
                Contact::whereIn('hostel_id', $hostelIds)
                    ->whereIn('id', $validIds)
                    ->delete();
            }
        } else {
            Contact::whereIn('id', $validIds)->delete();
        }

        $route = ($user->hasRole('owner') || $user->hasRole('hostel_manager')) ? 'owner.contacts.index' : 'admin.contacts.index';

        return redirect()->route($route)
            ->with('success', 'चयन गरिएका सम्पर्क जानकारीहरू सफलतापूर्वक मेटाइयो!');
    }

    /**
     * Search contact information.
     */
    public function search(Request $request)
    {
        $query = $request->input('search');

        // ✅ SECURITY FIX: Use parameter binding for search
        $safeQuery = '%' . addcslashes($query, '%_') . '%';

        $searchQuery = $this->getDataByRole()
            ->with(['hostel', 'room'])
            ->where(function ($q) use ($safeQuery) {
                $q->where('name', 'like', $safeQuery)
                    ->orWhere('email', 'like', $safeQuery)
                    ->orWhere('subject', 'like', $safeQuery)
                    ->orWhere('message', 'like', $safeQuery)
                    ->orWhereHas('room', function ($q) use ($safeQuery) {
                        $q->where('room_number', 'like', $safeQuery);
                    })
                    ->orWhereHas('hostel', function ($q) use ($safeQuery) {
                        $q->where('name', 'like', $safeQuery);
                    });
            });

        $contacts = $searchQuery->latest()->paginate(10);

        $unreadCount = $this->getDataByRole()->where('is_read', false)->count();
        $todayCount = $this->getDataByRole()->whereDate('created_at', today())->count();
        $repliedCount = $this->getDataByRole()->where('status', 'replied')->count();

        return view('admin.contacts.index', compact('contacts', 'unreadCount', 'todayCount', 'repliedCount'));
    }

    /**
     * Get filtered contacts for contact index page
     */
    public function getFilteredContacts(Request $request)
    {
        try {
            $filter = $request->input('filter', 'all');

            $query = $this->getDataByRole()->with(['hostel', 'room']);

            switch ($filter) {
                case 'unread':
                    $query->where('is_read', false);
                    break;
                case 'today':
                    $query->whereDate('created_at', today());
                    break;
                case 'read':
                    $query->where('is_read', true);
                    break;
                    // 'all' shows everything
            }

            $contacts = $query->latest()->paginate(10);

            $unreadCount = $this->getDataByRole()->where('is_read', false)->count();
            $todayCount = $this->getDataByRole()->whereDate('created_at', today())->count();
            $repliedCount = $this->getDataByRole()->where('status', 'replied')->count();

            return view('admin.contacts.index', compact('contacts', 'filter', 'unreadCount', 'todayCount', 'repliedCount'));
        } catch (\Exception $e) {
            \Log::error('Contact filter error: ' . $e->getMessage());
            return redirect()->route('admin.contacts.index')->with('error', 'Filter applied गर्न असफल');
        }
    }

    /**
     * Export contact information to CSV.
     */
    public function exportCSV()
    {
        $fileName = 'contacts_' . date('Y-m-d') . '.csv';
        $contacts = $this->getDataByRole()->with(['hostel', 'room'])->get();

        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $columns = array('नाम', 'इमेल', 'फोन', 'विषय', 'सन्देश', 'होस्टल', 'कोठा', 'स्थिति', 'सिर्जना मिति');

        $callback = function () use ($contacts, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($contacts as $contact) {
                $row = [
                    'नाम' => $contact->name,
                    'इमेल' => $contact->email,
                    'फोन' => $contact->phone ?? '—',
                    'विषय' => $contact->subject,
                    'सन्देश' => $contact->message,
                    'होस्टल' => $contact->hostel->name ?? '—',
                    'कोठा' => $contact->room->room_number ?? '—',
                    'स्थिति' => $contact->status,
                    'सिर्जना मिति' => $contact->created_at->format('Y-m-d H:i:s')
                ];

                fputcsv($file, $row);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Mark contact as read
     */
    public function markAsRead(Contact $contact)
    {
        try {
            // ✅ SECURITY FIX: Explicit ownership verification
            $user = Auth::user();
            if (($user->hasRole('owner') || $user->hasRole('hostel_manager')) && !$this->isUserContact($contact)) {
                abort(403, 'तपाईंसँग यो सन्देश अपडेट गर्ने अनुमति छैन');
            }

            $contact->update([
                'is_read' => true,
                'status' => 'read'
            ]);

            return redirect()->back()->with('success', 'सन्देश पढिएको चिन्ह लगाइयो');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'अपडेट गर्न असफल: ' . $e->getMessage());
        }
    }

    /**
     * Mark contact as unread
     */
    public function markAsUnread(Contact $contact)
    {
        try {
            // ✅ SECURITY FIX: Explicit ownership verification
            $user = Auth::user();
            if (($user->hasRole('owner') || $user->hasRole('hostel_manager')) && !$this->isUserContact($contact)) {
                abort(403, 'तपाईंसँग यो सन्देश अपडेट गर्ने अनुमति छैन');
            }

            $contact->update([
                'is_read' => false,
                'status' => 'unread'
            ]);

            return redirect()->back()->with('success', 'सन्देश नपढिएको चिन्ह लगाइयो');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'अपडेट गर्न असफल: ' . $e->getMessage());
        }
    }

    /**
     * Bulk action for contacts
     */
    public function bulkAction(Request $request)
    {
        $ids = $request->input('ids');
        $action = $request->input('action');

        if (!$ids) {
            return redirect()->back()->with('error', 'कुनै सम्पर्क चयन गरिएन!');
        }

        // ✅ SECURITY FIX: Validate IDs are integers
        $validIds = array_filter($ids, 'is_numeric');

        try {
            $user = Auth::user();

            switch ($action) {
                case 'mark-read':
                    // For owners, only update their organization's contacts
                    if ($user->hasRole('owner') || $user->hasRole('hostel_manager')) {
                        $organization = $user->organizations()->first();
                        if ($organization) {
                            $hostelIds = $organization->hostels()->pluck('id');
                            Contact::whereIn('hostel_id', $hostelIds)
                                ->whereIn('id', $validIds)
                                ->update(['is_read' => true, 'status' => 'read']);
                        }
                    } else {
                        Contact::whereIn('id', $validIds)->update(['is_read' => true, 'status' => 'read']);
                    }
                    $message = 'चयन गरिएका सन्देशहरू पढिएको चिन्ह लगाइयो';
                    break;
                case 'mark-unread':
                    // For owners, only update their organization's contacts
                    if ($user->hasRole('owner') || $user->hasRole('hostel_manager')) {
                        $organization = $user->organizations()->first();
                        if ($organization) {
                            $hostelIds = $organization->hostels()->pluck('id');
                            Contact::whereIn('hostel_id', $hostelIds)
                                ->whereIn('id', $validIds)
                                ->update(['is_read' => false, 'status' => 'unread']);
                        }
                    } else {
                        Contact::whereIn('id', $validIds)->update(['is_read' => false, 'status' => 'unread']);
                    }
                    $message = 'चयन गरिएका सन्देशहरू नपढिएको चिन्ह लगाइयो';
                    break;
                case 'delete':
                    // For owners, only delete their organization's contacts
                    if ($user->hasRole('owner') || $user->hasRole('hostel_manager')) {
                        $organization = $user->organizations()->first();
                        if ($organization) {
                            $hostelIds = $organization->hostels()->pluck('id');
                            Contact::whereIn('hostel_id', $hostelIds)
                                ->whereIn('id', $validIds)
                                ->delete();
                        }
                    } else {
                        Contact::whereIn('id', $validIds)->delete();
                    }
                    $message = 'चयन गरिएका सन्देशहरू मेटाइयो';
                    break;
                default:
                    return redirect()->back()->with('error', 'अमान्य कार्य');
            }

            return redirect()->back()->with('success', $message);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'कार्य असफल: ' . $e->getMessage());
        }
    }
}
