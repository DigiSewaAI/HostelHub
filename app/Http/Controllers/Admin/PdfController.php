<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\Payment;
use App\Models\Student;
use App\Models\Hostel;
use PDF;

class PdfController extends Controller
{
    /**
     * Generate Receipt PDF - SUPER SIMPLE VERSION
     */
    public function generateReceipt($id)
    {
        try {
            // Disable timeout
            set_time_limit(0);

            // Simple query
            $payment = Payment::find($id);
            if (!$payment) {
                return response()->json(['error' => 'Payment not found'], 404);
            }

            // Simple permission check
            $user = Auth::user();
            if ($user->hasRole('student') && $payment->student_id != $user->id) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            // Get basic data
            $hostel = Hostel::find($payment->hostel_id);
            $student = Student::find($payment->student_id);

            // Simple HTML
            $html = view('pdf.simple_receipt', [
                'payment' => $payment,
                'hostel' => $hostel,
                'student' => $student,
                'logoUrl' => $hostel && $hostel->logo_path ? Storage::disk('public')->url($hostel->logo_path) : null
            ])->render();

            // Generate PDF
            $pdf = PDF::loadHTML($html);

            // Return PDF
            return $pdf->download('receipt_' . $payment->id . '.pdf');
        } catch (\Exception $e) {
            \Log::error('PDF Receipt Error: ' . $e->getMessage());
            return response()->json(['error' => 'PDF generation failed'], 500);
        }
    }

    /**
     * Generate Bill PDF - SUPER SIMPLE VERSION  
     */
    public function generateBill($id)
    {
        try {
            // Disable timeout
            set_time_limit(0);

            // Simple query
            $payment = Payment::find($id);
            if (!$payment) {
                return response()->json(['error' => 'Payment not found'], 404);
            }

            // Simple permission check
            $user = Auth::user();
            if ($user->hasRole('student') && $payment->student_id != $user->id) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            // Get basic data
            $hostel = Hostel::find($payment->hostel_id);
            $student = Student::find($payment->student_id);

            // Simple HTML
            $html = view('pdf.simple_bill', [
                'payment' => $payment,
                'hostel' => $hostel,
                'student' => $student,
                'logoUrl' => $hostel && $hostel->logo_path ? Storage::disk('public')->url($hostel->logo_path) : null
            ])->render();

            // Generate PDF
            $pdf = PDF::loadHTML($html);

            // Return PDF
            return $pdf->download('bill_' . $payment->id . '.pdf');
        } catch (\Exception $e) {
            \Log::error('PDF Bill Error: ' . $e->getMessage());
            return response()->json(['error' => 'PDF generation failed'], 500);
        }
    }
}
