<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePaymentRequest;
use App\Http\Requests\UpdatePaymentRequest;
use App\Models\Payment;
use App\Models\Student;
use App\Models\Hostel;
use App\Models\Room;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with(['student', 'hostel', 'room'])
                    ->latest()
                    ->paginate(10);

        return view('admin.payments.index', compact('payments'));
    }

    public function create()
    {
        $students = Student::all();
        $hostels = Hostel::all();
        $rooms = Room::where('status', 'occupied')->get();

        return view('admin.payments.create', compact('students', 'hostels', 'rooms'));
    }

    public function store(StorePaymentRequest $request)
    {
        Payment::create($request->validated());

        return redirect()->route('payments.index')
            ->with('success', 'भुक्तानी सफलतापूर्वक दर्ता गरियो');
    }

    public function show(Payment $payment)
    {
        return view('admin.payments.show', compact('payment'));
    }

    public function edit(Payment $payment)
    {
        $students = Student::all();
        $hostels = Hostel::all();
        $rooms = Room::all();

        return view('admin.payments.edit', compact('payment', 'students', 'hostels', 'rooms'));
    }

    public function update(UpdatePaymentRequest $request, Payment $payment)
    {
        $payment->update($request->validated());

        return redirect()->route('payments.index')
            ->with('success', 'भुक्तानी विवरण सफलतापूर्वक अद्यावधिक गरियो');
    }

    public function destroy(Payment $payment)
    {
        $payment->delete();

        return redirect()->route('payments.index')
            ->with('success', 'भुक्तानी रेकर्ड सफलतापूर्वक मेटाइयो');
    }

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
            $payment->update([
                'status' => 'completed',
                'transaction_id' => $result->idx
            ]);

            return response()->json(['success' => true]);
        }

        return response()->json(['error' => 'Payment verification failed']);
    }
}
