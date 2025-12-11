<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\Hostel;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    /**
     * Get contacts for owner's organization
     */
    private function getOwnerContacts()
    {
        $user = Auth::user();
        $organization = $user->organizations()->first();

        if ($organization) {
            $hostelIds = $organization->hostels()->pluck('id');

            // Debug information added as per instructions
            $contacts = Contact::whereIn('hostel_id', $hostelIds)->get();

            // If no contacts found, check all contacts for debugging
            if ($contacts->isEmpty()) {
                Log::info('No contacts found for hostels: ' . $hostelIds->implode(', '));

                // Check all contacts in database for debugging
                $allContacts = Contact::all();
                Log::info('Total contacts in database: ' . $allContacts->count());

                foreach ($allContacts as $contact) {
                    Log::info('Contact ' . $contact->id . ': Hostel ID = ' . $contact->hostel_id . ', Name = ' . $contact->name);
                }
            }

            return Contact::whereIn('hostel_id', $hostelIds);
        }

        return Contact::where('id', 0); // No organization, return empty
    }

    /**
     * Display a listing of contact form submissions.
     */
    public function index(Request $request)
    {
        // EMERGENCY FIX: Temporarily show all contacts for debugging
        if ($request->has('debug')) {
            $contacts = Contact::with(['hostel', 'room'])->paginate(10);

            return view('owner.contacts.index', [
                'contacts' => $contacts,
                'filter' => 'all',
                'unreadCount' => Contact::where('is_read', false)->count(),
                'todayCount' => Contact::whereDate('created_at', today())->count(),
                'repliedCount' => Contact::where('status', 'replied')->count(),
            ]);
        }

        $filter = $request->input('filter', 'all');
        $search = $request->input('search');

        // Debug: Log owner information
        $user = Auth::user();
        Log::info('Owner Contacts - User: ' . $user->id . ', Name: ' . $user->name);

        $organization = $user->organizations()->first();

        if ($organization) {
            Log::info('Organization found: ' . $organization->name);
            $hostelIds = $organization->hostels()->pluck('id');
            Log::info('Hostel IDs: ' . $hostelIds->implode(', '));

            // Debug: Check total contacts
            $totalContacts = Contact::whereIn('hostel_id', $hostelIds)->count();
            Log::info('Total contacts for owner: ' . $totalContacts);
        } else {
            Log::warning('No organization found for owner: ' . $user->id);
        }

        $query = $this->getOwnerContacts()->with(['hostel', 'room']);

        // Apply search filter
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

        // Apply status filters
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

        // Calculate counts for statistics
        $unreadCount = $this->getOwnerContacts()->where('is_read', false)->count();
        $todayCount = $this->getOwnerContacts()->whereDate('created_at', today())->count();
        $repliedCount = $this->getOwnerContacts()->where('status', 'replied')->count();

        return view('owner.contacts.index', compact('contacts', 'filter', 'unreadCount', 'todayCount', 'repliedCount'));
    }

    /**
     * Show the form for creating a new contact.
     */
    public function create()
    {
        // Get hostels for current owner's organization
        $user = Auth::user();
        $organization = $user->organizations()->first();

        $hostels = $organization ? $organization->hostels()->get() : collect();
        $rooms = $organization ? Room::whereIn('hostel_id', $hostels->pluck('id'))->get() : collect();

        return view('owner.contacts.create', compact('hostels', 'rooms'));
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

        $user = Auth::user();
        $organization = $user->organizations()->first();

        $contactData = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'subject' => $request->subject,
            'message' => $request->message,
            'status' => 'pending',
            'is_read' => false,
        ];

        // Add hostel_id from owner's organization if not provided
        if ($request->hostel_id) {
            $contactData['hostel_id'] = $request->hostel_id;
        } elseif ($organization) {
            $hostel = $organization->hostels()->first();
            if ($hostel) {
                $contactData['hostel_id'] = $hostel->id;
            }
        }

        if ($request->room_id) {
            $contactData['room_id'] = $request->room_id;
        }

        Contact::create($contactData);

        return redirect()->route('owner.contacts.index')
            ->with('success', 'सन्देश सफलतापूर्वक पठाइयो!');
    }

    /**
     * Display specific contact information.
     */
    public function show($id)
    {
        $contact = $this->getOwnerContacts()->with(['hostel', 'room'])->findOrFail($id);

        // Mark as read when viewing
        if (!$contact->is_read) {
            $contact->update(['is_read' => true, 'status' => 'read']);
        }

        return view('owner.contacts.show', compact('contact'));
    }

    /**
     * Show the form for editing the specified contact.
     */
    public function edit($id)
    {
        $contact = $this->getOwnerContacts()->with(['hostel', 'room'])->findOrFail($id);

        $user = Auth::user();
        $organization = $user->organizations()->first();

        $hostels = $organization ? $organization->hostels()->get() : collect();
        $rooms = $organization ? Room::whereIn('hostel_id', $hostels->pluck('id'))->get() : collect();

        return view('owner.contacts.edit', compact('contact', 'hostels', 'rooms'));
    }

    /**
     * Update the specified contact.
     */
    public function update(Request $request, $id)
    {
        $contact = $this->getOwnerContacts()->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'hostel_id' => 'nullable|exists:hostels,id',
            'room_id' => 'nullable|exists:rooms,id',
            'status' => 'required|in:pending,read,replied',
        ]);

        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'subject' => $request->subject,
            'message' => $request->message,
            'status' => $request->status,
        ];

        if ($request->hostel_id) {
            $updateData['hostel_id'] = $request->hostel_id;
        }

        if ($request->room_id) {
            $updateData['room_id'] = $request->room_id;
        }

        // Update is_read based on status
        if ($request->status == 'read') {
            $updateData['is_read'] = true;
        } elseif ($request->status == 'pending') {
            $updateData['is_read'] = false;
        }

        $contact->update($updateData);

        return redirect()->route('owner.contacts.index')
            ->with('success', 'सन्देश सफलतापूर्वक अपडेट गरियो!');
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

            $contact = $this->getOwnerContacts()->findOrFail($id);

            if ($request->status == 'read') {
                $contact->update([
                    'is_read' => true,
                    'status' => 'read'
                ]);
                $message = 'सन्देश पढिएको चिन्ह लगाइयो';
            } else {
                $contact->update([
                    'is_read' => false,
                    'status' => 'pending'
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
            $contact = $this->getOwnerContacts()->findOrFail($id);
            $contact->delete();

            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'सन्देश सफलतापूर्वक मेटाइयो!'
                ]);
            }

            return redirect()->route('owner.contacts.index')
                ->with('success', 'सन्देश सफलतापूर्वक मेटाइयो!');
        } catch (\Exception $e) {
            Log::error('Contact deletion failed: ' . $e->getMessage());

            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'सन्देश मेटाउन असफल भयो'
                ], 500);
            }

            return redirect()->route('owner.contacts.index')
                ->with('error', 'सन्देश मेटाउन असफल भयो');
        }
    }

    /**
     * Bulk delete contact information.
     */
    public function bulkDestroy(Request $request)
    {
        $ids = $request->input('ids');

        if (!$ids) {
            return redirect()->back()
                ->with('error', 'कुनै सन्देश चयन गरिएन!');
        }

        $validIds = array_filter($ids, 'is_numeric');
        $this->getOwnerContacts()->whereIn('id', $validIds)->delete();

        return redirect()->route('owner.contacts.index')
            ->with('success', 'चयन गरिएका सन्देशहरू सफलतापूर्वक मेटाइयो!');
    }

    /**
     * Search contact information.
     */
    public function search(Request $request)
    {
        $query = $request->input('search');

        $safeQuery = '%' . addcslashes($query, '%_') . '%';

        $searchQuery = $this->getOwnerContacts()
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

        $unreadCount = $this->getOwnerContacts()->where('is_read', false)->count();
        $todayCount = $this->getOwnerContacts()->whereDate('created_at', today())->count();
        $repliedCount = $this->getOwnerContacts()->where('status', 'replied')->count();

        return view('owner.contacts.index', compact('contacts', 'unreadCount', 'todayCount', 'repliedCount'));
    }

    /**
     * Export contact information to CSV.
     */
    public function exportCSV()
    {
        $fileName = 'contacts_' . date('Y-m-d') . '.csv';
        $contacts = $this->getOwnerContacts()->with(['hostel', 'room'])->get();

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
    public function markAsRead($id)
    {
        try {
            $contact = $this->getOwnerContacts()->findOrFail($id);
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
    public function markAsUnread($id)
    {
        try {
            $contact = $this->getOwnerContacts()->findOrFail($id);
            $contact->update([
                'is_read' => false,
                'status' => 'pending'
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
            return redirect()->back()->with('error', 'कुनै सन्देश चयन गरिएन!');
        }

        $validIds = array_filter($ids, 'is_numeric');

        try {
            switch ($action) {
                case 'mark-read':
                    $this->getOwnerContacts()->whereIn('id', $validIds)
                        ->update(['is_read' => true, 'status' => 'read']);
                    $message = 'चयन गरिएका सन्देशहरू पढिएको चिन्ह लगाइयो';
                    break;
                case 'mark-unread':
                    $this->getOwnerContacts()->whereIn('id', $validIds)
                        ->update(['is_read' => false, 'status' => 'pending']);
                    $message = 'चयन गरिएका सन्देशहरू नपढिएको चिन्ह लगाइयो';
                    break;
                case 'delete':
                    $this->getOwnerContacts()->whereIn('id', $validIds)->delete();
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
