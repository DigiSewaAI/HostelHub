<?php

namespace App\Http\Controllers\Owner;

use App\Models\Payment;
use App\Models\Student;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // केवल आफ्नो होस्टलका भुक्तानीहरू मात्र हेर्नुहोस्
        $payments = Payment::where('hostel_id', auth()->user()->hostel_id)
            ->with('student')
            ->orderBy('payment_date', 'desc')
            ->get();

        return view('owner.payments.index', compact('payments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // केवल आफ्नो होस्टलका विद्यार्थीहरू मात्र
        $students = Student::where('hostel_id', auth()->user()->hostel_id)->get();
        return view('owner.payments.create', compact('students'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'amount' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
            'payment_method' => 'required|in:cash,bank_transfer,online',
            'status' => 'required|in:pending,completed,failed',
            'description' => 'nullable|string',
        ]);

        // सुनिश्चित गर्नुहोस् कि विद्यार्थी आफ्नो होस्टलको हो
        $student = Student::find($request->student_id);
        if ($student->hostel_id != auth()->user()->hostel_id) {
            abort(403, 'तपाईंसँग यो विद्यार्थीको लागि भुक्तानी थप्ने अनुमति छैन');
        }

        $data = $request->all();
        $data['hostel_id'] = auth()->user()->hostel_id;

        Payment::create($data);

        return redirect()->route('owner.payments.index')
            ->with('success', 'भुक्तानी सफलतापूर्वक थपियो!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Payment $payment)
    {
        // सुनिश्चित गर्नुहोस् कि यो भुक्तानी आफ्नो होस्टलको हो
        if ($payment->hostel_id != auth()->user()->hostel_id) {
            abort(403, 'तपाईंसँग यो भुक्तानी हेर्ने अनुमति छैन');
        }

        return view('owner.payments.show', compact('payment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Payment $payment)
    {
        // सुनिश्चित गर्नुहोस् कि यो भुक्तानी आफ्नो होस्टलको हो
        if ($payment->hostel_id != auth()->user()->hostel_id) {
            abort(403, 'तपाईंसँग यो भुक्तानी सम्पादन गर्ने अनुमति छैन');
        }

        $students = Student::where('hostel_id', auth()->user()->hostel_id)->get();
        return view('owner.payments.edit', compact('payment', 'students'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Payment $payment)
    {
        // सुनिश्चित गर्नुहोस् कि यो भुक्तानी आफ्नो होस्टलको हो
        if ($payment->hostel_id != auth()->user()->hostel_id) {
            abort(403, 'तपाईंसँग यो भुक्तानी सम्पादन गर्ने अनुमति छैन');
        }

        $request->validate([
            'student_id' => 'required|exists:students,id',
            'amount' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
            'payment_method' => 'required|in:cash,bank_transfer,online',
            'status' => 'required|in:pending,completed,failed',
            'description' => 'nullable|string',
        ]);

        // सुनिश्चित गर्नुहोस् कि विद्यार्थी आफ्नो होस्टलको हो
        $student = Student::find($request->student_id);
        if ($student->hostel_id != auth()->user()->hostel_id) {
            abort(403, 'तपाईंसँग यो विद्यार्थीको लागि भुक्तानी सम्पादन गर्ने अनुमति छैन');
        }

        $payment->update($request->all());

        return redirect()->route('owner.payments.index')
            ->with('success', 'भुक्तानी सफलतापूर्वक अद्यावधिक गरियो!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payment $payment)
    {
        // सुनिश्चित गर्नुहोस् कि यो भुक्तानी आफ्नो होस्टलको हो
        if ($payment->hostel_id != auth()->user()->hostel_id) {
            abort(403, 'तपाईंसँग यो भुक्तानी हटाउने अनुमति छैन');
        }

        $payment->delete();

        return redirect()->route('owner.payments.index')
            ->with('success', 'भुक्तानी सफलतापूर्वक हटाइयो!');
    }
}
