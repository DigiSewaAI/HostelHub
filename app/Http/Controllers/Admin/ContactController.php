<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\Hostel;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContactController extends Controller
{
    /**
     * Get data based on user role
     */
    private function getDataByRole()
    {
        if (Auth::user()->hasRole('owner')) {
            return Contact::where('hostel_id', Auth::user()->hostel_id);
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

        // ✅ Apply search
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%")
                    ->orWhere('subject', 'like', "%$search%")
                    ->orWhere('message', 'like', "%$search%")
                    ->orWhereHas('room', function ($q) use ($search) {
                        $q->where('room_number', 'like', "%$search%");
                    })
                    ->orWhereHas('hostel', function ($q) use ($search) {
                        $q->where('name', 'like', "%$search%");
                    });
            });
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

        $contactData = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'subject' => $request->subject,
            'message' => $request->message,
            'status' => 'नयाँ',
            'is_read' => false,
        ];

        // ✅ FIXED: Always add hostel_id and room_id if provided
        if ($request->has('hostel_id') && $request->hostel_id) {
            $contactData['hostel_id'] = $request->hostel_id;
        }

        if ($request->has('room_id') && $request->room_id) {
            $contactData['room_id'] = $request->room_id;
        }

        // ✅ FIXED: Add hostel_id for owners automatically
        if (Auth::user()->hasRole('owner')) {
            $contactData['hostel_id'] = Auth::user()->hostel_id;
            $contactData['status'] = 'pending'; // Use English status for owners
        }

        Contact::create($contactData);

        return redirect()->route('admin.contacts.index')
            ->with('success', Auth::user()->hasRole('owner') ? 'सन्देश सफलतापूर्वक पठाइयो!' : 'सम्पर्क सफलतापूर्वक थपियो!');
    }

    /**
     * Display specific contact information.
     */
    public function show($id)
    {
        $contact = $this->getDataByRole()->with(['hostel', 'room'])->findOrFail($id);
        return view('admin.contacts.show', compact('contact'));
    }

    /**
     * Show the form for editing the specified contact.
     */
    public function edit($id)
    {
        $contact = $this->getDataByRole()->with(['hostel', 'room'])->findOrFail($id);
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

        // Owners can only update status
        if (Auth::user()->hasRole('owner')) {
            $request->validate([
                'status' => 'required|in:pending,read,replied',
            ]);

            $contact->update($request->only('status'));

            return redirect()->route('admin.contacts.index')
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

        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'subject' => $request->subject,
            'message' => $request->message,
            'status' => $request->status,
        ];

        // ✅ FIXED: Always update hostel_id and room_id if provided
        if ($request->has('hostel_id')) {
            $updateData['hostel_id'] = $request->hostel_id;
        }
        if ($request->has('room_id')) {
            $updateData['room_id'] = $request->room_id;
        }

        // Update is_read based on status
        if ($request->status == 'read' || $request->status == 'पढियो') {
            $updateData['is_read'] = true;
        } elseif ($request->status == 'नयाँ' || $request->status == 'pending') {
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
        $contact = $this->getDataByRole()->findOrFail($id);
        $contact->delete();

        return redirect()->route('admin.contacts.index')
            ->with('success', Auth::user()->hasRole('owner') ? 'सन्देश सफलतापूर्वक हटाइयो!' : 'सम्पर्क जानकारी सफलतापूर्वक मेटाइयो!');
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

        // For owners, only allow deleting their hostel's contacts
        if (Auth::user()->hasRole('owner')) {
            Contact::where('hostel_id', Auth::user()->hostel_id)
                ->whereIn('id', $ids)
                ->delete();
        } else {
            Contact::whereIn('id', $ids)->delete();
        }

        return redirect()->route('admin.contacts.index')
            ->with('success', 'चयन गरिएका सम्पर्क जानकारीहरू सफलतापूर्वक मेटाइयो!');
    }

    /**
     * Search contact information.
     */
    public function search(Request $request)
    {
        $query = $request->input('search');

        $searchQuery = $this->getDataByRole()
            ->with(['hostel', 'room'])
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%$query%")
                    ->orWhere('email', 'like', "%$query%")
                    ->orWhere('subject', 'like', "%$query%")
                    ->orWhere('message', 'like', "%$query%")
                    ->orWhereHas('room', function ($q) use ($query) {
                        $q->where('room_number', 'like', "%$query%");
                    })
                    ->orWhereHas('hostel', function ($q) use ($query) {
                        $q->where('name', 'like', "%$query%");
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

        try {
            switch ($action) {
                case 'mark-read':
                    Contact::whereIn('id', $ids)->update(['is_read' => true, 'status' => 'read']);
                    $message = 'चयन गरिएका सन्देशहरू पढिएको चिन्ह लगाइयो';
                    break;
                case 'mark-unread':
                    Contact::whereIn('id', $ids)->update(['is_read' => false, 'status' => 'unread']);
                    $message = 'चयन गरिएका सन्देशहरू नपढिएको चिन्ह लगाइयो';
                    break;
                case 'delete':
                    Contact::whereIn('id', $ids)->delete();
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
