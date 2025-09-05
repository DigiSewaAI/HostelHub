<?php

namespace App\Http\Controllers\Owner;

use App\Models\Contact;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // केवल आफ्नो होस्टलका सम्पर्क सन्देशहरू मात्र हेर्नुहोस्
        $contacts = Contact::where('hostel_id', auth()->user()->hostel_id)->get();
        return view('owner.contacts.index', compact('contacts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('owner.contacts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'nullable|string',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $data = $request->all();
        $data['hostel_id'] = auth()->user()->hostel_id;
        $data['status'] = 'pending'; // Default status

        Contact::create($data);

        return redirect()->route('owner.contacts.index')
            ->with('success', 'सन्देश सफलतापूर्वक पठाइयो!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Contact $contact)
    {
        // सुनिश्चित गर्नुहोस् कि यो सन्देश आफ्नो होस्टलको हो
        if ($contact->hostel_id != auth()->user()->hostel_id) {
            abort(403, 'तपाईंसँग यो सन्देश हेर्ने अनुमति छैन');
        }

        return view('owner.contacts.show', compact('contact'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Contact $contact)
    {
        // सुनिश्चित गर्नुहोस् कि यो सन्देश आफ्नो होस्टलको हो
        if ($contact->hostel_id != auth()->user()->hostel_id) {
            abort(403, 'तपाईंसँग यो सन्देश सम्पादन गर्ने अनुमति छैन');
        }

        return view('owner.contacts.edit', compact('contact'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Contact $contact)
    {
        // सुनिश्चित गर्नुहोस् कि यो सन्देश आफ्नो होस्टलको हो
        if ($contact->hostel_id != auth()->user()->hostel_id) {
            abort(403, 'तपाईंसँग यो सन्देश सम्पादन गर्ने अनुमति छैन');
        }

        $request->validate([
            'status' => 'required|in:pending,read,replied',
        ]);

        $contact->update($request->only('status'));

        return redirect()->route('owner.contacts.index')
            ->with('success', 'सन्देश स्थिति सफलतापूर्वक अद्यावधिक गरियो!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contact $contact)
    {
        // सुनिश्चित गर्नुहोस् कि यो सन्देश आफ्नो होस्टलको हो
        if ($contact->hostel_id != auth()->user()->hostel_id) {
            abort(403, 'तपाईंसँग यो सन्देश हटाउने अनुमति छैन');
        }

        $contact->delete();

        return redirect()->route('owner.contacts.index')
            ->with('success', 'सन्देश सफलतापूर्वक हटाइयो!');
    }
}
