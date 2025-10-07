<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePaymentRequest;
use App\Http\Requests\UpdatePaymentRequest;
use App\Models\Payment;
use App\Models\Student;
use App\Models\Hostel;
use App\Models\Room;
use App\Models\Booking;
use App\Models\Organization;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use PDF;

class PaymentController extends Controller
{
    public function index()
    {
        $organizationId = session('selected_organization_id');

        if (Auth::user()->hasRole('admin')) {
            $payments = Payment::with(['organization', 'user', 'booking'])
                ->latest()
                ->paginate(10);
        } else {
            $payments = Payment::where('organization_id', $organizationId)
                ->with(['user', 'booking'])
                ->latest()
                ->paginate(10);
        }

        return view('admin.payments.index', compact('payments'));
    }

    public function create()
    {
        $organizationId = session('selected_organization_id');
        $students = Student::where('organization_id', $organizationId)->get();
        $hostels = Hostel::where('organization_id', $organizationId)->get();
        $rooms = Room::where('organization_id', $organizationId)->where('status', 'occupied')->get();

        return view('admin.payments.create', compact('students', 'hostels', 'rooms'));
    }

    public function store(StorePaymentRequest $request)
    {
        $organizationId = session('selected_organization_id');

        $validated = $request->validated();
        $validated['organization_id'] = $organizationId;
        $validated['user_id'] = Auth::id();

        Payment::create($validated);

        return redirect()->route('payments.index')
            ->with('success', 'भुक्तानी सफलतापूर्वक दर्ता गरियो');
    }

    public function show(Payment $payment)
    {
        // Authorization check
        $this->authorizePaymentAccess($payment);

        $payment->load(['organization', 'user', 'booking.student.user']);

        return view('admin.payments.show', compact('payment'));
    }

    public function edit(Payment $payment)
    {
        // Authorization check
        $this->authorizePaymentAccess($payment);

        $organizationId = session('selected_organization_id');
        $students = Student::where('organization_id', $organizationId)->get();
        $hostels = Hostel::where('organization_id', $organizationId)->get();
        $rooms = Room::where('organization_id', $organizationId)->get();

        return view('admin.payments.edit', compact('payment', 'students', 'hostels', 'rooms'));
    }

    public function update(UpdatePaymentRequest $request, Payment $payment)
    {
        // Authorization check
        $this->authorizePaymentAccess($payment);

        $payment->update($request->validated());

        return redirect()->route('payments.index')
            ->with('success', 'भुक्तानी विवरण सफलतापूर्वक अद्यावधिक गरियो');
    }

    public function destroy(Payment $payment)
    {
        // Authorization check
        $this->authorizePaymentAccess($payment);

        $payment->delete();

        return redirect()->route('payments.index')
            ->with('success', 'भुक्तानी रेकर्ड सफलतापूर्वक मेटाइयो');
    }

    /**
     * Show payment report for owner's organization
     */
    public function ownerReport(Request $request)
    {
        $user = Auth::user();

        if (!$user->hasRole('hostel_manager')) {
            abort(403, 'तपाईंसँग यो रिपोर्ट हेर्ने अनुमति छैन');
        }

        $organizationId = session('selected_organization_id');

        // Get payments for owner's organization
        $payments = Payment::where('organization_id', $organizationId)
            ->with(['user', 'booking'])
            ->latest()
            ->paginate(10);

        // Statistics
        $totalRevenue = Payment::where('organization_id', $organizationId)
            ->where('status', 'completed')
            ->sum('amount');

        $pendingTransfers = Payment::where('organization_id', $organizationId)
            ->where('payment_method', 'bank_transfer')
            ->where('status', 'pending')
            ->count();

        return view('owner.payments.report', compact('payments', 'totalRevenue', 'pendingTransfers'));
    }

    /**
     * Create manual cash payment for owner
     */
    public function createManualPayment(Request $request)
    {
        $user = Auth::user();

        if (!$user->hasRole('hostel_manager')) {
            abort(403, 'तपाईंसँग म्यानुअल भुक्तानी थप्ने अनुमति छैन');
        }

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:1',
            'payment_date' => 'required|date',
            'purpose' => 'required|in:subscription,booking,extra_hostel'
        ]);

        $organizationId = session('selected_organization_id');

        try {
            DB::beginTransaction();

            Payment::create([
                'organization_id' => $organizationId,
                'user_id' => $request->user_id,
                'amount' => $request->amount,
                'payment_method' => 'cash',
                'purpose' => $request->purpose,
                'payment_date' => $request->payment_date,
                'status' => 'completed',
                'verified_by' => $user->id,
                'verified_at' => now(),
                'metadata' => [
                    'manual_payment' => true,
                    'created_by' => $user->id,
                    'created_at' => now()->toISOString()
                ]
            ]);

            DB::commit();

            return redirect()->route('owner.payments.report')->with('success', 'म्यानुअल भुक्तानी सफलतापूर्वक दर्ता गरियो!');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Manual payment creation failed: ' . $e->getMessage());

            return back()
                ->withInput()
                ->with('error', 'म्यानुअल भुक्तानी दर्ता गर्न असफल। कृपया पुनः प्रयास गर्नुहोस्।');
        }
    }

    /**
     * Approve bank transfer payment for owner
     */
    public function approveBankTransfer(Request $request, Payment $payment)
    {
        $user = Auth::user();

        if (!$user->hasRole('hostel_manager')) {
            abort(403, 'तपाईंसँग भुक्तानी स्वीकृत गर्ने अनुमति छैन');
        }

        // Check if payment belongs to owner's organization
        if ($payment->organization_id != session('selected_organization_id')) {
            abort(403, 'तपाईंले यो भुक्तानी स्वीकृत गर्न पाउनुहुन्न।');
        }

        if ($payment->payment_method !== 'bank_transfer' || $payment->status !== 'pending') {
            return redirect()->back()->with('error', 'अमान्य भुक्तानी वा भुक्तानी अवस्था।');
        }

        try {
            DB::beginTransaction();

            $payment->update([
                'status' => 'completed',
                'verified_by' => $user->id,
                'verified_at' => now(),
                'metadata' => array_merge($payment->metadata ?? [], [
                    'approved_by' => $user->id,
                    'approved_at' => now()->toISOString(),
                    'approval_remarks' => $request->input('remarks', '')
                ])
            ]);

            // Handle successful payment
            $this->handleSuccessfulPayment($payment);

            DB::commit();

            return redirect()->back()->with('success', 'बैंक हस्तान्तरण भुक्तानी सफलतापूर्वक स्वीकृत गरियो।');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Bank Transfer Approval Failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'भुक्तानी स्वीकृत गर्न असफल: ' . $e->getMessage());
        }
    }

    /**
     * Reject bank transfer payment for owner
     */
    public function rejectBankTransfer(Request $request, Payment $payment)
    {
        $user = Auth::user();

        if (!$user->hasRole('hostel_manager')) {
            abort(403, 'तपाईंसँग भुक्तानी अस्वीकृत गर्ने अनुमति छैन');
        }

        // Check if payment belongs to owner's organization
        if ($payment->organization_id != session('selected_organization_id')) {
            abort(403, 'तपाईंले यो भुक्तानी अस्वीकृत गर्न पाउनुहुन्न।');
        }

        if ($payment->payment_method !== 'bank_transfer' || $payment->status !== 'pending') {
            return redirect()->back()->with('error', 'अमान्य भुक्तानी वा भुक्तानी अवस्था।');
        }

        try {
            $payment->update([
                'status' => 'failed',
                'metadata' => array_merge($payment->metadata ?? [], [
                    'rejected_by' => $user->id,
                    'rejected_at' => now()->toISOString(),
                    'rejection_reason' => $request->input('reason', '')
                ])
            ]);

            return redirect()->back()->with('success', 'बैंक हस्तान्तरण भुक्तानी सफलतापूर्वक अस्वीकृत गरियो।');
        } catch (\Exception $e) {
            Log::error('Bank Transfer Rejection Failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'भुक्तानी अस्वीकृत गर्न असफल: ' . $e->getMessage());
        }
    }

    /**
     * View bank transfer proof
     */
    public function viewProof(Payment $payment)
    {
        if ($payment->payment_method !== 'bank_transfer') {
            abort(404);
        }

        // Authorization check
        $this->authorizePaymentAccess($payment);

        $screenshotPath = $payment->metadata['screenshot_path'] ?? null;

        if (!$screenshotPath || !Storage::disk('public')->exists($screenshotPath)) {
            abort(404);
        }

        return response()->file(Storage::disk('public')->path($screenshotPath));
    }

    /**
     * Show checkout page
     */
    public function checkout(Request $request)
    {
        $organizationId = session('selected_organization_id');
        $amount = $request->input('amount', 0);
        $purpose = $request->input('purpose', 'booking');
        $bookingId = $request->input('booking_id');
        $planId = $request->input('plan_id');

        return view('payment.checkout', compact('amount', 'purpose', 'bookingId', 'planId'));
    }

    /**
     * Initiate eSewa payment
     */
    public function payWithEsewa(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'purchase_type' => 'required|in:subscription,booking,extra_hostel',
            'payment_id' => 'required|exists:payments,id'
        ]);

        $payment = Payment::findOrFail($request->payment_id);

        // Authorization check
        if ($payment->organization_id != session('selected_organization_id')) {
            return back()->with('error', 'तपाईंले यो भुक्तानी गर्न पाउनुहुन्न।');
        }

        $amount = $request->amount;
        $transaction_uuid = uniqid();
        $product_code = config('services.esewa.merchant_code', 'EPAYTEST');
        $success_url = route('payment.esewa.callback');
        $failure_url = route('payment.failure', $payment->id);
        $signed_field_names = 'total_amount,transaction_uuid,product_code';
        $secret_key = config('services.esewa.secret_key');

        // Create signature
        $data = $amount . ',' . $transaction_uuid . ',' . $product_code;
        $hash = base64_encode(hash_hmac('sha256', $data, $secret_key, true));

        // Update payment with eSewa details
        $payment->update([
            'transaction_id' => $transaction_uuid,
            'payment_method' => 'esewa',
            'metadata' => array_merge($payment->metadata ?? [], [
                'esewa_initiate' => [
                    'amount' => $amount,
                    'product_code' => $product_code,
                    'success_url' => $success_url,
                    'failure_url' => $failure_url
                ]
            ])
        ]);

        return view('payment.esewa_form', compact('amount', 'transaction_uuid', 'product_code', 'success_url', 'failure_url', 'signed_field_names', 'hash'));
    }

    /**
     * eSewa payment verification callback
     */
    public function verifyEsewaPayment(Request $request)
    {
        Log::info('eSewa Callback Received:', $request->all());

        try {
            $transaction_uuid = $request->input('transaction_uuid');
            $transaction_code = $request->input('transaction_code');
            $status = $request->input('status');
            $total_amount = $request->input('total_amount');

            // Find payment by transaction ID
            $payment = Payment::where('transaction_id', $transaction_uuid)->first();

            if (!$payment) {
                Log::error('eSewa Payment not found:', ['transaction_uuid' => $transaction_uuid]);
                return redirect()->route('payment.failure')
                    ->with('error', 'भुक्तानी रेकर्ड भेटिएन।');
            }

            // Verify payment with eSewa
            $verification_url = config('services.esewa.verify_url');
            $verification_data = [
                'amt' => $total_amount,
                'rid' => $transaction_code,
                'pid' => $transaction_uuid,
                'scd' => config('services.esewa.merchant_code')
            ];

            $response = Http::post($verification_url, $verification_data);

            if ($response->successful() && str_contains($response->body(), 'Success')) {
                // Payment successful
                $payment->update([
                    'status' => 'completed',
                    'transaction_id' => $transaction_code,
                    'verified_at' => now(),
                    'metadata' => array_merge($payment->metadata ?? [], [
                        'esewa_response' => $request->all(),
                        'verification_response' => $response->body()
                    ])
                ]);

                // Handle successful payment
                $this->handleSuccessfulPayment($payment);

                return redirect()->route('payment.success', $payment->id)
                    ->with('success', 'भुक्तानी सफल भयो! धन्यवाद।');
            } else {
                // Payment verification failed
                $payment->update([
                    'status' => 'failed',
                    'metadata' => array_merge($payment->metadata ?? [], [
                        'esewa_response' => $request->all(),
                        'verification_response' => $response->body()
                    ])
                ]);

                return redirect()->route('payment.failure', $payment->id)
                    ->with('error', 'भुक्तानी सत्यापन असफल भयो।');
            }
        } catch (\Exception $e) {
            Log::error('eSewa Callback Error: ' . $e->getMessage());
            return redirect()->route('payment.failure')
                ->with('error', 'भुक्तानी प्रक्रियामा त्रुटि: ' . $e->getMessage());
        }
    }

    /**
     * Initiate Khalti payment
     */
    public function payWithKhalti(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'purchase_type' => 'required|in:subscription,booking,extra_hostel',
            'payment_id' => 'required|exists:payments,id'
        ]);

        $payment = Payment::findOrFail($request->payment_id);

        // Authorization check
        if ($payment->organization_id != session('selected_organization_id')) {
            return back()->with('error', 'तपाईंले यो भुक्तानी गर्न पाउनुहुन्न।');
        }

        $payload = [
            'return_url' => route('payment.khalti.callback'),
            'website_url' => config('app.url'),
            'amount' => $request->amount * 100, // Convert to paisa
            'purchase_order_id' => 'PO_' . $payment->id . '_' . time(),
            'purchase_order_name' => $this->getPurchaseOrderName($request->purchase_type),
            'customer_info' => [
                'name' => Auth::user()->name,
                'email' => Auth::user()->email,
                'phone' => Auth::user()->phone ?? ''
            ]
        ];

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Key ' . config('services.khalti.live_secret_key')
            ])->post('https://khalti.com/api/v2/epayment/initiate/', $payload);

            if ($response->successful()) {
                $responseData = $response->json();

                // Update payment with transaction ID
                $payment->update([
                    'transaction_id' => $responseData['pidx'],
                    'payment_method' => 'khalti',
                    'metadata' => array_merge($payment->metadata ?? [], [
                        'khalti_initiate' => $responseData,
                        'payload' => $payload
                    ])
                ]);

                return redirect($responseData['payment_url']);
            } else {
                Log::error('Khalti Initiation Failed:', $response->json());
                return back()->with('error', 'भुक्तानी सुरु गर्न असफल। फेरि प्रयास गर्नुहोस्।');
            }
        } catch (\Exception $e) {
            Log::error('Khalti Initiation Error: ' . $e->getMessage());
            return back()->with('error', 'भुक्तानी प्रक्रियामा त्रुटि: ' . $e->getMessage());
        }
    }

    /**
     * Khalti payment verification callback
     */
    public function verifyKhaltiPayment(Request $request)
    {
        Log::info('Khalti Callback Received:', $request->all());

        try {
            $pidx = $request->input('pidx');
            $transactionId = $request->input('transaction_id');
            $amount = $request->input('amount') / 100; // Convert from paisa to rupees

            // Verify payment with Khalti
            $response = Http::withHeaders([
                'Authorization' => 'Key ' . config('services.khalti.live_secret_key')
            ])->post('https://khalti.com/api/v2/epayment/lookup/', [
                'pidx' => $pidx
            ]);

            if ($response->successful()) {
                $paymentData = $response->json();

                // Find payment by transaction ID
                $payment = Payment::where('transaction_id', $pidx)->first();

                if ($payment) {
                    if ($paymentData['status'] == 'Completed') {
                        // Payment successful
                        $payment->update([
                            'status' => 'completed',
                            'verified_at' => now(),
                            'metadata' => array_merge($payment->metadata ?? [], [
                                'khalti_response' => $paymentData,
                                'callback_data' => $request->all()
                            ])
                        ]);

                        // Handle successful payment
                        $this->handleSuccessfulPayment($payment);

                        return redirect()->route('payment.success', $payment->id)
                            ->with('success', 'भुक्तानी सफल भयो! धन्यवाद।');
                    } else {
                        // Payment failed
                        $payment->update([
                            'status' => 'failed',
                            'metadata' => array_merge($payment->metadata ?? [], [
                                'khalti_response' => $paymentData,
                                'callback_data' => $request->all()
                            ])
                        ]);

                        return redirect()->route('payment.failure', $payment->id)
                            ->with('error', 'भुक्तानी असफल भयो।');
                    }
                } else {
                    Log::error('Payment not found for transaction:', ['pidx' => $pidx]);
                    return redirect()->route('payment.failure')
                        ->with('error', 'भुक्तानी रेकर्ड भेटिएन।');
                }
            }

            // Payment verification failed
            return redirect()->route('payment.failure')
                ->with('error', 'भुक्तानी सत्यापन असफल भयो।');
        } catch (\Exception $e) {
            Log::error('Khalti Callback Error: ' . $e->getMessage());
            return redirect()->route('payment.failure')
                ->with('error', 'भुक्तानी प्रक्रियामा त्रुटि: ' . $e->getMessage());
        }
    }

    /**
     * Show bank transfer form
     */
    public function bankTransferRequest(Request $request)
    {
        $amount = $request->input('amount', 0);
        $purpose = $request->input('purpose', 'booking');
        $bookingId = $request->input('booking_id');
        $planId = $request->input('plan_id');

        $bankDetails = [
            'account_number' => config('services.bank.account_number'),
            'account_name' => config('services.bank.account_name'),
            'bank_name' => config('services.bank.name'),
            'branch' => config('services.bank.branch', '')
        ];

        return view('payment.bank_form', compact('amount', 'purpose', 'bookingId', 'planId', 'bankDetails'));
    }

    /**
     * Store bank transfer request
     */
    public function storeBankTransfer(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'purpose' => 'required|in:subscription,booking,extra_hostel',
            'bank_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:255',
            'transaction_id' => 'required|string|max:255',
            'transaction_date' => 'required|date',
            'screenshot' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB max
        ]);

        try {
            // Store screenshot
            $screenshotPath = $request->file('screenshot')->store('bank_screenshots', 'public');

            // Create payment record
            $payment = Payment::create([
                'organization_id' => session('selected_organization_id'),
                'user_id' => Auth::id(),
                'amount' => $request->amount,
                'payment_method' => 'bank_transfer',
                'purpose' => $request->purpose,
                'transaction_id' => $request->transaction_id,
                'status' => 'pending',
                'payment_date' => $request->transaction_date,
                'metadata' => [
                    'bank_name' => $request->bank_name,
                    'account_number' => $request->account_number,
                    'screenshot_path' => $screenshotPath,
                    'submitted_at' => now()->toISOString()
                ]
            ]);

            // Associate with booking if provided
            if ($request->booking_id) {
                $payment->update(['booking_id' => $request->booking_id]);
            }

            return redirect()->route('payment.success', $payment->id)
                ->with('success', 'तपाईंको बैंक हस्तान्तरण विवरण सफलतापूर्वक पेश गरियो। प्रशासकले यसलाई स्वीकृत गर्नेछ।');
        } catch (\Exception $e) {
            Log::error('Bank Transfer Store Error: ' . $e->getMessage());
            return back()->withInput()->with('error', 'बैंक हस्तान्तरण विवरण सेभ गर्न असफल: ' . $e->getMessage());
        }
    }

    /**
     * Handle successful payment
     */
    private function handleSuccessfulPayment(Payment $payment)
    {
        DB::beginTransaction();

        try {
            switch ($payment->purpose) {
                case 'booking':
                    // Update booking status
                    if ($payment->booking_id) {
                        $booking = Booking::find($payment->booking_id);
                        if ($booking) {
                            $booking->update([
                                'payment_status' => 'paid',
                                'status' => 'approved' // Auto-approve after payment
                            ]);
                        }
                    }
                    break;

                case 'subscription':
                    // Activate subscription
                    $metadata = $payment->metadata;
                    $planId = $metadata['plan_id'] ?? null;

                    if ($planId) {
                        $organization = $payment->organization;
                        $currentSubscription = $organization->currentSubscription();

                        if ($currentSubscription) {
                            // Upgrade existing subscription
                            $currentSubscription->update([
                                'plan_id' => $planId,
                                'status' => 'active',
                                'ends_at' => now()->addMonth(),
                                'renews_at' => now()->addMonth()
                            ]);
                        } else {
                            // Create new subscription
                            Subscription::create([
                                'organization_id' => $organization->id,
                                'plan_id' => $planId,
                                'status' => 'active',
                                'ends_at' => now()->addMonth(),
                                'renews_at' => now()->addMonth()
                            ]);
                        }
                    }
                    break;

                case 'extra_hostel':
                    // Add extra hostels to subscription
                    $metadata = $payment->metadata;
                    $quantity = $metadata['quantity'] ?? 0;
                    $subscriptionId = $metadata['subscription_id'] ?? null;

                    if ($subscriptionId && $quantity > 0) {
                        $subscription = Subscription::find($subscriptionId);
                        if ($subscription) {
                            $subscription->update([
                                'extra_hostels' => ($subscription->extra_hostels ?? 0) + $quantity
                            ]);
                        }
                    }
                    break;
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Handle Successful Payment Error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Payment success page
     */
    public function paymentSuccess($paymentId)
    {
        $payment = Payment::with(['organization', 'user', 'booking'])->findOrFail($paymentId);

        // Authorization check
        $this->authorizePaymentAccess($payment);

        return view('payments.success', compact('payment'));
    }

    /**
     * Payment failure page
     */
    public function paymentFailure($paymentId = null)
    {
        $payment = null;
        if ($paymentId) {
            $payment = Payment::find($paymentId);
            if ($payment) {
                $this->authorizePaymentAccess($payment);
            }
        }

        return view('payments.failure', compact('payment'));
    }

    /**
     * Verify payment status
     */
    public function verifyPayment($paymentId)
    {
        try {
            $payment = Payment::findOrFail($paymentId);

            // Authorization check
            $this->authorizePaymentAccess($payment);

            if ($payment->status === 'completed') {
                return response()->json([
                    'success' => true,
                    'payment' => $payment,
                    'message' => 'भुक्तानी सत्यापित भयो'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'भुक्तानी अझै सत्यापित भएको छैन'
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'भुक्तानी सत्यापन असफल: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get purchase order name based on type
     */
    private function getPurchaseOrderName($purchaseType)
    {
        $names = [
            'subscription' => 'सदस्यता योजना',
            'booking' => 'कोठा बुकिंग',
            'extra_hostel' => 'अतिरिक्त होस्टल'
        ];

        return $names[$purchaseType] ?? 'सामान्य भुक्तानी';
    }

    /**
     * Authorization check for payment access
     */
    private function authorizePaymentAccess(Payment $payment)
    {
        $user = Auth::user();

        if ($user->hasRole('admin')) {
            return true;
        }

        if ($user->hasRole('hostel_manager')) {
            if ($payment->organization_id != session('selected_organization_id')) {
                abort(403, 'तपाईंले यो भुक्तानी हेर्न पाउनुहुन्न।');
            }
        }

        if ($user->hasRole('student')) {
            if ($payment->user_id != $user->id) {
                abort(403, 'तपाईंले यो भुक्तानी हेर्न पाउनुहुन्न।');
            }
        }

        return true;
    }

    /**
     * Student payment history
     */
    public function studentPayments()
    {
        $payments = Payment::where('user_id', Auth::id())
            ->with(['organization', 'booking.room.hostel'])
            ->latest()
            ->paginate(10);

        return view('student.payments.index', compact('payments'));
    }

    /**
     * Show payment receipt
     */
    public function showReceipt($paymentId)
    {
        $payment = Payment::with(['organization', 'user', 'booking'])->findOrFail($paymentId);

        // Authorization check
        $this->authorizePaymentAccess($payment);

        return view('payment.receipt', compact('payment'));
    }

    /**
     * Download payment receipt as PDF
     */
    public function downloadReceipt($paymentId)
    {
        $payment = Payment::with(['organization', 'user', 'booking'])->findOrFail($paymentId);

        // Authorization check
        $this->authorizePaymentAccess($payment);

        $data = [
            'payment' => $payment,
            'organization' => $payment->organization,
            'user' => $payment->user,
            'booking' => $payment->booking
        ];

        $pdf = PDF::loadView('payment.receipt-pdf', $data);

        return $pdf->download('भुक्तानी-रसिद-' . $payment->id . '.pdf');
    }
}
