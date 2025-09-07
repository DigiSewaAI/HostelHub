<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    /**
     * Display a listing of contact form submissions.
     */
    public function index()
    {
        $submissions = Contact::latest()->paginate(10);
        return view('admin.contacts.index', compact('submissions'));
    }

    /**
     * Show the form for creating a new contact.
     */
    public function create()
    {
        return view('admin.contacts.create');
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
        ], [
            'name.required' => 'नाम अनिवार्य छ।',
            'email.required' => 'इमेल अनिवार्य छ।',
            'email.email' => 'कृपया वैध इमेल ठेगाना प्रविष्ट गर्नुहोस्।',
            'subject.required' => 'विषय अनिवार्य छ।',
            'message.required' => 'सन्देश अनिवार्य छ।',
        ]);

        Contact::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'subject' => $request->subject,
            'message' => $request->message,
            'status' => 'नयाँ'
        ]);

        return redirect()->route('admin.contacts.index')
            ->with('success', 'सम्पर्क सफलतापूर्वक थपियो!');
    }

    /**
     * Display specific contact information.
     */
    public function show($id)
    {
        $submission = Contact::findOrFail($id);
        return view('admin.contacts.show', compact('submission'));
    }

    /**
     * Show the form for editing the specified contact.
     */
    public function edit($id)
    {
        $contact = Contact::findOrFail($id);
        return view('admin.contacts.edit', compact('contact'));
    }

    /**
     * Update the specified contact.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ], [
            'name.required' => 'नाम अनिवार्य छ।',
            'email.required' => 'इमेल अनिवार्य छ।',
            'email.email' => 'कृपया वैध इमेल ठेगाना प्रविष्ट गर्नुहोस्।',
            'subject.required' => 'विषय अनिवार्य छ।',
            'message.required' => 'सन्देश अनिवार्य छ।',
        ]);

        $contact = Contact::findOrFail($id);
        $contact->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'subject' => $request->subject,
            'message' => $request->message,
        ]);

        return redirect()->route('admin.contacts.index')
            ->with('success', 'सम्पर्क सफलतापूर्वक अपडेट गरियो!');
    }

    /**
     * Remove contact information.
     */
    public function destroy($id)
    {
        $submission = Contact::findOrFail($id);
        $submission->delete();

        return redirect()->route('admin.contacts.index')
            ->with('success', 'सम्पर्क जानकारी सफलतापूर्वक मेटाइयो!');
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

        Contact::whereIn('id', $ids)->delete();

        return redirect()->route('admin.contacts.index')
            ->with('success', 'चयन गरिएका सम्पर्क जानकारीहरू सफलतापूर्वक मेटाइयो!');
    }

    /**
     * Search contact information.
     */
    public function search(Request $request)
    {
        $query = $request->input('search');

        $submissions = Contact::where('name', 'like', "%$query%")
            ->orWhere('email', 'like', "%$query%")
            ->orWhere('subject', 'like', "%$query%")
            ->latest()
            ->paginate(10);

        return view('admin.contacts.index', compact('submissions'));
    }

    /**
     * Update contact status.
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:नयाँ,पढियो,जवाफ दिइयो'
        ], [
            'status.required' => 'स्थिति अनिवार्य छ।',
            'status.in' => 'अमान्य स्थिति चयन गरिएको छ।'
        ]);

        $submission = Contact::findOrFail($id);
        $submission->update(['status' => $request->status]);

        return redirect()->back()
            ->with('success', 'सम्पर्क जानकारीको स्थिति सफलतापूर्वक अपडेट गरियो!');
    }

    /**
     * Export contact information to CSV.
     */
    public function exportCSV()
    {
        $fileName = 'contacts_' . date('Y-m-d') . '.csv';
        $contacts = Contact::all();

        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $columns = array('नाम', 'इमेल', 'फोन', 'विषय', 'सन्देश', 'स्थिति', 'सिर्जना मिति');

        $callback = function () use ($contacts, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($contacts as $contact) {
                $row['नाम']  = $contact->name;
                $row['इमेल']    = $contact->email;
                $row['फोन']  = $contact->phone;
                $row['विषय']  = $contact->subject;
                $row['सन्देश']  = $contact->message;
                $row['स्थिति']  = $contact->status;
                $row['सिर्जना मिति']  = $contact->created_at->format('Y-m-d H:i:s');

                fputcsv($file, array($row['नाम'], $row['इमेल'], $row['फोन'], $row['विषय'], $row['सन्देश'], $row['स्थिति'], $row['सिर्जना मिति']));
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
