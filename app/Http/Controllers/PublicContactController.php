<?php

namespace App\Http\Controllers;

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
        return view('contact');
    }

    /**
     * Submit गरिएको contact message सुरक्षित गर्ने
     */
    public function store(ContactRequest $request): RedirectResponse
    {
        // 1. Contact डाटाबेसमा सुरक्षित गर्ने
        $contact = Contact::create($request->validated());

        // 2. एडमिनलाई इमेल पठाउने
        try {
            // config/mail.php मा परिभाषित admin_address प्रयोग गर्दै
            $adminEmail = config('mail.admin_address', 'info@hostelhub.com');

            Mail::to($adminEmail)->send(
                new ContactFormSubmitted($contact)
            );
        } catch (\Exception $e) {
            Log::error('Contact form email failed to send: ' . $e->getMessage());
            // Email नपठाउन सके पनि contact सुरक्षित हुन्छ
        }

        return back()->with('success', 'धन्यवाद! हामी चाँडै सम्पर्क गर्छौं।');
    }
}
