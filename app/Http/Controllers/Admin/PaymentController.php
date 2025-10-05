<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use App\Models\Payment;
use App\Models\Student;
use App\Models\Hostel;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PaymentsExport;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    /**
     * Get data based on user role
     */
    private function getDataByRole()
    {
        $user = Auth::user();

        if ($user->hasRole('admin')) {
            return Payment::with(['student', 'hostel'])->latest();
        } elseif ($user->hasRole('hostel_manager')) {
            // FIX: Get the user's organization ID from session
            $organizationId = session('current_organization_id');

            return Payment::whereHas('hostel', function ($query) use ($organizationId) {
                $query->where('organization_id', $organizationId);
            })->with(['student', 'hostel'])->latest();
        } elseif ($user->hasRole('student')) {
            return Payment::where('student_id', $user->id)
                ->with('hostel')
                ->latest();
        }

        abort(403, 'Unauthorized action.');
    }

    /**
     * Check if user has permission to access a specific payment
     */
    private function checkPaymentPermission(Payment $payment)
    {
        $user = Auth::user();

        if ($user->hasRole('admin')) {
            return true;
        } elseif ($user->hasRole('hostel_manager')) {
            // FIX: Check if payment belongs to user's organization
            $organizationId = session('current_organization_id');
            return $payment->hostel && $payment->hostel->organization_id == $organizationId;
        } elseif ($user->hasRole('student') && $payment->student_id == $user->id) {
            return true;
        }

        abort(403, 'तपाईंसँग यो भुक्तानी हेर्ने अनुमति छैन');
    }

    /**
     * Display a listing of the payments.
     */
    public function index()
    {
        $payments = $this->getDataByRole()->paginate(10);

        // Return appropriate view based on role
        if (Auth::user()->hasRole('admin')) {
            return view('admin.payments.index', compact('payments'));
        } elseif (Auth::user()->hasRole('hostel_manager')) {
            return view('owner.payments.index', compact('payments'));
        } elseif (Auth::user()->hasRole('student')) {
            return view('student.payments.index', compact('payments'));
        }
    }

    /**
     * Show the form for creating a new payment.
     */
    public function create()
    {
        // Only admin and owner can create payments
        if (!Auth::user()->hasAnyRole(['admin', 'hostel_manager'])) {
            abort(403, 'तपाईंसँग भुक्तानी थप्ने अनुमति छैन');
        }

        $user = Auth::user();

        if ($user->hasRole('admin')) {
            $students = Student::where('status', 'active')->get();
            $hostels = Hostel::all();
            return view('admin.payments.create', compact('students', 'hostels'));
        } elseif ($user->hasRole('hostel_manager')) {
            // FIX: Get students from user's organization
            $organizationId = session('current_organization_id');
            $students = Student::whereHas('room.hostel', function ($query) use ($organizationId) {
                $query->where('organization_id', $organizationId);
            })->where('status', 'active')->get();

            return view('owner.payments.create', compact('students'));
        }
    }

    /**
     * Store a newly created payment in storage.
     */
    public function store(Request $request)
    {
        // Only admin and owner can create payments
        if (!Auth::user()->hasAnyRole(['admin', 'hostel_manager'])) {
            abort(403, 'तपाईंसँग भुक्तानी थप्ने अनुमति छैन');
        }

        $user = Auth::user();
        $validationRules = [
            'student_id' => 'required|exists:students,id',
            'amount' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
            'payment_method' => 'required|string|max:50',
            'status' => 'required|in:pending,completed,failed',
            'notes' => 'nullable|string|max:500',
        ];

        // Add hostel_id validation for admin
        if ($user->hasRole('admin')) {
            $validationRules['hostel_id'] = 'required|exists:hostels,id';
        }

        $request->validate($validationRules);

        // For owners, ensure student belongs to their organization
        if ($user->hasRole('hostel_manager')) {
            $organizationId = session('current_organization_id');
            $student = Student::where('id', $request->student_id)
                ->whereHas('room.hostel', function ($query) use ($organizationId) {
                    $query->where('organization_id', $organizationId);
                })->first();

            if (!$student) {
                return back()
                    ->withInput()
                    ->with('error', 'तपाईंसँग यो विद्यार्थीको लागि भुक्तानी थप्ने अनुमति छैन');
            }
        }

        try {
            DB::beginTransaction();

            $paymentData = [
                'student_id' => $request->student_id,
                'amount' => $request->amount,
                'payment_date' => $request->payment_date,
                'payment_method' => $request->payment_method,
                'status' => $request->status,
                'notes' => $request->notes,
                'created_by' => $user->id,
            ];

            // Set hostel_id based on role
            if ($user->hasRole('admin')) {
                $paymentData['hostel_id'] = $request->hostel_id;
            } elseif ($user->hasRole('hostel_manager')) {
                // FIX: Get hostel_id from student's room
                $student = Student::find($request->student_id);
                if ($student && $student->room) {
                    $paymentData['hostel_id'] = $student->room->hostel_id;
                } else {
                    throw new \Exception('Student does not have an assigned room');
                }
            }

            Payment::create($paymentData);

            DB::commit();

            $route = $user->hasRole('admin') ? 'admin.payments.index' : 'owner.payments.index';
            return redirect()
                ->route($route)
                ->with('success', 'भुक्तानी सफलतापूर्वक थपियो!');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Payment creation failed: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());

            return back()
                ->withInput()
                ->with('error', 'भुक्तानी दर्ता गर्न असफल। कृपया पुनः प्रयास गर्नुहोस्।');
        }
    }

    /**
     * Display the specified payment.
     */
    public function show(Payment $payment)
    {
        $this->checkPaymentPermission($payment);
        $payment->load(['student', 'hostel', 'createdBy', 'updatedBy']);

        // Return appropriate view based on role
        if (Auth::user()->hasRole('admin')) {
            return view('admin.payments.show', compact('payment'));
        } elseif (Auth::user()->hasRole('hostel_manager')) {
            return view('owner.payments.show', compact('payment'));
        } elseif (Auth::user()->hasRole('student')) {
            return view('student.payments.show', compact('payment'));
        }
    }

    /**
     * Search payments
     */
    public function search(Request $request)
    {
        $user = Auth::user();
        $search = $request->input('search');

        $payments = $this->getDataByRole()
            ->where(function ($query) use ($search) {
                $query->where('amount', 'like', "%{$search}%")
                    ->orWhere('payment_method', 'like', "%{$search}%")
                    ->orWhere('status', 'like', "%{$search}%")
                    ->orWhereHas('student', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            })
            ->paginate(10);

        // Return appropriate view based on role
        if (Auth::user()->hasRole('admin')) {
            return view('admin.payments.index', compact('payments'));
        } elseif (Auth::user()->hasRole('hostel_manager')) {
            return view('owner.payments.index', compact('payments'));
        } elseif (Auth::user()->hasRole('student')) {
            return view('student.payments.index', compact('payments'));
        }
    }

    /**
     * Show the form for editing the specified payment.
     */
    public function edit(Payment $payment)
    {
        $this->checkPaymentPermission($payment);

        // Students can't edit payments
        if (Auth::user()->hasRole('student')) {
            abort(403, 'तपाईंसँग भुक्तानी सम्पादन गर्ने अनुमति छैन');
        }

        $user = Auth::user();

        if ($user->hasRole('admin')) {
            $students = Student::where('status', 'active')->get();
            $hostels = Hostel::all();
            return view('admin.payments.edit', compact('payment', 'students', 'hostels'));
        } elseif ($user->hasRole('hostel_manager')) {
            // FIX: Get students from user's organization
            $organizationId = session('current_organization_id');
            $students = Student::whereHas('room.hostel', function ($query) use ($organizationId) {
                $query->where('organization_id', $organizationId);
            })->where('status', 'active')->get();

            return view('owner.payments.edit', compact('payment', 'students'));
        }
    }

    /**
     * Update the specified payment in storage.
     */
    public function update(Request $request, Payment $payment)
    {
        $this->checkPaymentPermission($payment);

        // Students can't update payments
        if (Auth::user()->hasRole('student')) {
            abort(403, 'तपाईंसँग भुक्तानी अद्यावधिक गर्ने अनुमति छैन');
        }

        $user = Auth::user();
        $validationRules = [
            'student_id' => 'required|exists:students,id',
            'amount' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
            'payment_method' => 'required|string|max:50',
            'status' => 'required|in:pending,completed,failed',
            'notes' => 'nullable|string|max:500',
        ];

        // Add hostel_id validation for admin
        if ($user->hasRole('admin')) {
            $validationRules['hostel_id'] = 'required|exists:hostels,id';
        }

        $request->validate($validationRules);

        // For owners, ensure student belongs to their organization
        if ($user->hasRole('hostel_manager')) {
            $organizationId = session('current_organization_id');
            $student = Student::where('id', $request->student_id)
                ->whereHas('room.hostel', function ($query) use ($organizationId) {
                    $query->where('organization_id', $organizationId);
                })->first();

            if (!$student) {
                return back()
                    ->withInput()
                    ->with('error', 'तपाईंसँग यो विद्यार्थीको लागि भुक्तानी सम्पादन गर्ने अनुमति छैन');
            }
        }

        try {
            DB::beginTransaction();

            $paymentData = [
                'student_id' => $request->student_id,
                'amount' => $request->amount,
                'payment_date' => $request->payment_date,
                'payment_method' => $request->payment_method,
                'status' => $request->status,
                'notes' => $request->notes,
                'updated_by' => $user->id,
            ];

            // Set hostel_id based on role
            if ($user->hasRole('admin')) {
                $paymentData['hostel_id'] = $request->hostel_id;
            }

            $payment->update($paymentData);

            DB::commit();

            $route = $user->hasRole('admin') ? 'admin.payments.index' : 'owner.payments.index';
            return redirect()
                ->route($route)
                ->with('success', 'भुक्तानी सफलतापूर्वक अद्यावधिक गरियो!');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Payment update failed: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());

            return back()
                ->withInput()
                ->with('error', 'भुक्तानी अद्यावधिक गर्न असफल। कृपया पुनः प्रयास गर्नुहोस्।');
        }
    }

    /**
     * Update payment status
     */
    public function updateStatus(Request $request, Payment $payment)
    {
        $this->checkPaymentPermission($payment);

        $request->validate([
            'status' => 'required|in:pending,completed,failed'
        ]);

        try {
            $payment->update([
                'status' => $request->status,
                'updated_by' => Auth::id()
            ]);

            return redirect()->back()->with('success', 'भुक्तानी स्थिति सफलतापूर्वक अद्यावधिक गरियो!');
        } catch (\Exception $e) {
            Log::error('Payment status update failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'भुक्तानी स्थिति अद्यावधिक गर्न असफल।');
        }
    }

    /**
     * Generate payment report (Admin only)
     */
    public function report(Request $request)
    {
        if (!Auth::user()->hasRole('admin')) {
            abort(403, 'तपाईंसँग रिपोर्ट हेर्ने अनुमति छैन');
        }

        // Get date range from request or default to last 30 days
        $startDate = $request->input('start_date', now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));

        // Get payments within date range
        $payments = Payment::with(['student', 'hostel'])
            ->whereBetween('payment_date', [$startDate, $endDate])
            ->orderBy('payment_date', 'desc')
            ->get();

        // Calculate summary data
        $totalAmount = $payments->sum('amount');
        $completedCount = $payments->where('status', 'completed')->count();
        $pendingCount = $payments->where('status', 'pending')->count();

        return view('admin.payments.report', compact(
            'payments',
            'totalAmount',
            'completedCount',
            'pendingCount',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Remove the specified payment from storage.
     */
    public function destroy(Payment $payment)
    {
        $this->checkPaymentPermission($payment);

        // Students can't delete payments
        if (Auth::user()->hasRole('student')) {
            abort(403, 'तपाईंसँग भुक्तानी हटाउने अनुमति छैन');
        }

        try {
            $payment->delete();

            $route = Auth::user()->hasRole('admin') ? 'admin.payments.index' : 'owner.payments.index';
            return redirect()
                ->route($route)
                ->with('success', 'भुक्तानी सफलतापूर्वक हटाइयो!');
        } catch (\Exception $e) {
            Log::error('Payment deletion failed: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());

            $route = Auth::user()->hasRole('admin') ? 'admin.payments.index' : 'owner.payments.index';
            return redirect()
                ->route($route)
                ->with('error', 'भुक्तानी हटाउन असफल।');
        }
    }

    /**
     * Export payments to Excel (Admin only)
     */
    public function export()
    {
        if (!Auth::user()->hasRole('admin')) {
            abort(403, 'तपाईंसँग निर्यात गर्ने अनुमति छैन');
        }

        try {
            return Excel::download(new PaymentsExport, 'payments-' . now()->format('Y-m-d') . '.xlsx');
        } catch (\Exception $e) {
            Log::error('Payment export failed: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());

            return redirect()
                ->route('admin.payments.index')
                ->with('error', 'भुक्तानी निर्यात गर्न असफल। कृपया पुनः प्रयास गर्नुहोस्।');
        }
    }

    /**
     * Khalti callback route
     */
    public function khaltiCallback(Request $request)
    {
        // Khalti payment verification logic
        $token = $request->token;
        $amount = $request->amount;

        $args = http_build_query([
            'token' => $token,
            'amount'  => $amount
        ]);

        $url = "https://khalti.com/api/v2/payment/verify/";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $args);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $headers = ['Authorization: Key test_secret_key_XXXXXX'];
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);
        curl_close($ch);

        $result = json_decode($response);

        if (isset($result->idx)) {
            // Payment successful
            $payment = Payment::find($request->payment_id);
            if ($payment) {
                $payment->update([
                    'status' => 'completed',
                    'transaction_id' => $result->idx
                ]);

                return response()->json(['success' => true]);
            }
        }

        return response()->json(['error' => 'भुक्तानी प्रमाणीकरण असफल']);
    }
}
