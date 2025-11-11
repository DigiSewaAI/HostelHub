<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContactRequest;
use App\Mail\ContactFormSubmitted;
use App\Models\Contact;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class PublicContactController extends Controller
{
    /**
     * Contact form प्रदर्शन गर्ने
     */
    public function index(): \Illuminate\View\View
    {
        return view('frontend.contact');
    }

    /**
     * Submit गरिएको contact message सुरक्षित गर्ने
     */
    public function store(ContactRequest $request): RedirectResponse
    {
        // Room booking को लागि subject automatically set गर्ने
        $validatedData = $request->validated();

        if ($request->has('room_type') && $request->has('hostel')) {
            $validatedData['subject'] = "Room Booking Inquiry - " . $request->room_type . " Room at " . $request->hostel;
        } else {
            $validatedData['subject'] = "General Inquiry";
        }

        // 1. Contact डाटाबेसमा सुरक्षित गर्ने
        $contact = Contact::create($validatedData);

        // 2. एडमिनलाई इमेल पठाउने
        try {
            // config/mail.php मा परिभाषित admin_address प्रयोग गर्दै
            $adminEmail = config('mail.admin_address', 'info@hostelhub.com');

            // 🚨 IMPORTANT: Contact object लाई array मा convert गर्ने
            $contactData = [
                'name' => $contact->name,
                'email' => $contact->email,
                'subject' => $contact->subject,
                'message' => $contact->message,
                'phone' => $contact->phone ?? 'उपलब्ध छैन',
                'created_at' => $contact->created_at,
            ];

            // 🚨 CORRECT: Array पठाउने
            Mail::to($adminEmail)->send(
                new ContactFormSubmitted($contactData)  // $contactData (array) पठाउने
            );
        } catch (\Exception $e) {
            Log::error('Contact form email failed to send: ' . $e->getMessage());
        }

        return redirect()->back()->with('success', 'तपाईंको सन्देश सफलतापूर्वक पठाइयो! हामी चाँडै नै तपाईंसँग सम्पर्क गर्नेछौं।');
    }
}
