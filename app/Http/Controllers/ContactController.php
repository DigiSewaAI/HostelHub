<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use App\Mail\ContactFormSubmitted;
use App\Models\Contact;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    /**
     * Show the contact form.
     */
    public function index(): \Illuminate\View\View
    {
        return view('contact.index');
    }

    /**
     * Store a newly submitted contact message.
     */
    public function store(ContactRequest $request): RedirectResponse
    {
        // 1. Contact डाटाबेसमा सुरक्षित गर्ने
        $contact = Contact::create($request->validated());

        // 2. एडमिनलाई इमेल पठाउने
        try {
            Mail::to(config('mail.admin_address'))->send(
                new ContactFormSubmitted($request->validated())
            );
        } catch (\Exception $e) {
            \Log::error('Email failed to send: ' . $e->getMessage());
            // Email नपठाउन सके पनि contact सुरक्षित हुन्छ
        }

        return back()->with('success', 'तपाईंको सन्देश सफलतापूर्वक पठाइयो!');
    }
}
