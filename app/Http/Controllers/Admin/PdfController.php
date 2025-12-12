<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\Payment;
use App\Models\Student;
use App\Models\Hostel;
use App\Models\Organization;
use PDF;

class PdfController extends Controller
{
    /**
     * Generate Receipt PDF - SUPER SIMPLE VERSION
     */
    public function generateReceipt($id)
    {
        try {
            // ✅ SECURITY FIX: Authorization check at the beginning
            $user = Auth::user();
            if (!$user) {
                abort(403, 'तपाईं लगइन गर्नुपर्छ');
            }

            // ✅ SECURITY FIX: Input validation - ensure ID is numeric and positive
            if (!is_numeric($id) || $id <= 0) {
                abort(404, 'अमान्य भुक्तानी आईडी');
            }

            // Disable timeout
            set_time_limit(0);

            // ✅ SECURITY FIX: Use findOrFail with proper error handling
            $payment = Payment::findOrFail($id);

            // ✅ SECURITY FIX: Enhanced authorization check
            if (!$this->authorizePaymentAccess($payment, $user)) {
                abort(403, 'तपाईंसँग यो रसीद हेर्ने अनुमति छैन');
            }

            // ✅ SECURITY FIX: Eager load relationships to prevent N+1 queries
            $payment->load(['hostel', 'student.user']);

            // Get basic data from loaded relationships
            $hostel = $payment->hostel;
            $student = $payment->student;

            // ✅ SECURITY FIX: Validate essential data exists
            if (!$hostel || !$student) {
                abort(404, 'भुक्तानी सम्बन्धित डाटा फेला परेन');
            }

            // ✅ FIXED: Enhanced logo URL generation for DOMPDF compatibility
            $logo_url = null;
            $logo_path = null;
            if ($hostel->logo_path && Storage::disk('public')->exists($hostel->logo_path)) {
                // Get absolute file path for DOMPDF
                $absolutePath = storage_path('app/public/' . $hostel->logo_path);
                if (file_exists($absolutePath)) {
                    // Use file:// protocol for DOMPDF local file access
                    $logo_url = 'file://' . str_replace('\\', '/', $absolutePath);
                    $logo_path = $logo_url; // For compatibility with views
                }
            }

            // ✅ FIXED: Generate receipt number
            $receipt_number = 'REC-' . str_pad($payment->id, 6, '0', STR_PAD_LEFT);

            // Simple HTML with proper error handling
            $html = view('pdf.simple_receipt', [
                'payment' => $payment,
                'hostel' => $hostel,
                'student' => $student,
                'receipt_number' => $receipt_number,
                'logo_url' => $logo_url, // ✅ Added for universal solution
                'logo_path' => $logo_path, // ✅ Added for compatibility
                'logoUrl' => $logo_url, // ✅ Also add camelCase for compatibility
            ])->render();

            // Generate PDF with secure configuration
            $pdf = PDF::loadHTML($html)
                ->setPaper('a4', 'portrait')
                ->setOption('enable-local-file-access', true)
                ->setOption('isHtml5ParserEnabled', true)
                ->setOption('isRemoteEnabled', true);

            // ✅ SECURITY FIX: Sanitize filename to prevent directory traversal
            $safeFilename = 'receipt_' . preg_replace('/[^a-zA-Z0-9_-]/', '', $payment->id) . '.pdf';

            // Return PDF
            return $pdf->download($safeFilename);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            \Log::error('PDF Receipt - Payment not found: ' . $e->getMessage());
            abort(404, 'भुक्तानी फेला परेन');
        } catch (\Exception $e) {
            \Log::error('PDF Receipt Error: ' . $e->getMessage());
            return response()->json(['error' => 'PDF जनरेसन असफल भयो'], 500);
        }
    }

    /**
     * Generate Bill PDF - SUPER SIMPLE VERSION  
     */
    public function generateBill($id)
    {
        try {
            // ✅ SECURITY FIX: Authorization check at the beginning
            $user = Auth::user();
            if (!$user) {
                abort(403, 'तपाईं लगइन गर्नुपर्छ');
            }

            // ✅ SECURITY FIX: Input validation - ensure ID is numeric and positive
            if (!is_numeric($id) || $id <= 0) {
                abort(404, 'अमान्य भुक्तानी आईडी');
            }

            // Disable timeout
            set_time_limit(0);

            // ✅ SECURITY FIX: Use findOrFail with proper error handling
            $payment = Payment::findOrFail($id);

            // ✅ SECURITY FIX: Enhanced authorization check
            if (!$this->authorizePaymentAccess($payment, $user)) {
                abort(403, 'तपाईंसँग यो बिल हेर्ने अनुमति छैन');
            }

            // ✅ SECURITY FIX: Eager load relationships to prevent N+1 queries
            $payment->load(['hostel', 'student.user']);

            // Get basic data from loaded relationships
            $hostel = $payment->hostel;
            $student = $payment->student;

            // ✅ SECURITY FIX: Validate essential data exists
            if (!$hostel || !$student) {
                abort(404, 'भुक्तानी सम्बन्धित डाटा फेला परेन');
            }

            // ✅ FIXED: Enhanced logo URL generation for DOMPDF compatibility
            $logo_url = null;
            $logo_path = null;
            if ($hostel->logo_path && Storage::disk('public')->exists($hostel->logo_path)) {
                // Get absolute file path for DOMPDF
                $absolutePath = storage_path('app/public/' . $hostel->logo_path);
                if (file_exists($absolutePath)) {
                    // Use file:// protocol for DOMPDF local file access
                    $logo_url = 'file://' . str_replace('\\', '/', $absolutePath);
                    $logo_path = $logo_url; // For compatibility with views
                }
            }

            // ✅ FIXED: Generate bill number
            $bill_number = 'BILL-' . str_pad($payment->id, 6, '0', STR_PAD_LEFT);

            // Simple HTML with proper error handling
            $html = view('pdf.simple_bill', [
                'payment' => $payment,
                'hostel' => $hostel,
                'student' => $student,
                'bill_number' => $bill_number,
                'logo_url' => $logo_url, // ✅ Added for universal solution
                'logo_path' => $logo_path, // ✅ Added for compatibility
                'logoUrl' => $logo_url, // ✅ Also add camelCase for compatibility
            ])->render();

            // Generate PDF with secure configuration
            $pdf = PDF::loadHTML($html)
                ->setPaper('a4', 'portrait')
                ->setOption('enable-local-file-access', true)
                ->setOption('isHtml5ParserEnabled', true)
                ->setOption('isRemoteEnabled', true);

            // ✅ SECURITY FIX: Sanitize filename to prevent directory traversal
            $safeFilename = 'bill_' . preg_replace('/[^a-zA-Z0-9_-]/', '', $payment->id) . '.pdf';

            // Return PDF
            return $pdf->download($safeFilename);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            \Log::error('PDF Bill - Payment not found: ' . $e->getMessage());
            abort(404, 'भुक्तानी फेला परेन');
        } catch (\Exception $e) {
            \Log::error('PDF Bill Error: ' . $e->getMessage());
            return response()->json(['error' => 'PDF जनरेसन असफल भयो'], 500);
        }
    }

    /**
     * ✅ NEW: Enhanced authorization method for payment access
     */
    private function authorizePaymentAccess(Payment $payment, $user)
    {
        // Admin can access all payments
        if ($user->hasRole('admin')) {
            return true;
        }

        // Student can only access their own payments
        if ($user->hasRole('student')) {
            return $payment->student_id == $user->id;
        }

        // Hostel manager can access payments for their organization's hostels
        if ($user->hasRole('hostel_manager')) {
            $organization = $user->organizations()->wherePivot('role', 'owner')->first();

            if (!$organization) {
                return false;
            }

            $hostelIds = $organization->hostels->pluck('id');
            return $hostelIds->contains($payment->hostel_id);
        }

        return false;
    }

    /**
     * ✅ NEW: Generate hostel report PDF with security fixes
     */
    public function generateHostelReport($hostelId)
    {
        try {
            // ✅ SECURITY FIX: Authorization check
            $user = Auth::user();
            if (!$user->hasRole('admin')) {
                abort(403, 'तपाईंसँग यो रिपोर्ट जनरेट गर्ने अनुमति छैन');
            }

            // ✅ SECURITY FIX: Input validation
            if (!is_numeric($hostelId) || $hostelId <= 0) {
                abort(404, 'अमान्य होस्टल आईडी');
            }

            set_time_limit(0);

            // ✅ SECURITY FIX: Use findOrFail with proper relationships
            $hostel = Hostel::with(['rooms', 'students.user', 'organization'])
                ->findOrFail($hostelId);

            // ✅ FIXED: Add logo URL for the report if needed
            $logo_url = null;
            if ($hostel->logo_path && Storage::disk('public')->exists($hostel->logo_path)) {
                $absolutePath = storage_path('app/public/' . $hostel->logo_path);
                if (file_exists($absolutePath)) {
                    $logo_url = 'file://' . str_replace('\\', '/', $absolutePath);
                }
            }

            // Generate PDF
            $html = view('pdf.hostel_report', [
                'hostel' => $hostel,
                'logo_url' => $logo_url,
                'logo_path' => $logo_url,
            ])->render();

            $pdf = PDF::loadHTML($html)
                ->setPaper('a4', 'portrait')
                ->setOption('enable-local-file-access', true);

            $safeFilename = 'hostel_report_' . preg_replace('/[^a-zA-Z0-9_-]/', '', $hostel->id) . '.pdf';

            return $pdf->download($safeFilename);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            \Log::error('PDF Hostel Report - Hostel not found: ' . $e->getMessage());
            abort(404, 'होस्टल फेला परेन');
        } catch (\Exception $e) {
            \Log::error('PDF Hostel Report Error: ' . $e->getMessage());
            return response()->json(['error' => 'रिपोर्ट जनरेसन असफल भयो'], 500);
        }
    }

    /**
     * ✅ NEW: Generate student payment history PDF
     */
    public function generateStudentPaymentHistory($studentId)
    {
        try {
            // ✅ SECURITY FIX: Authorization check
            $user = Auth::user();
            if (!$user) {
                abort(403, 'तपाईं लगइन गर्नुपर्छ');
            }

            // ✅ SECURITY FIX: Input validation
            if (!is_numeric($studentId) || $studentId <= 0) {
                abort(404, 'अमान्य विद्यार्थी आईडी');
            }

            set_time_limit(0);

            // ✅ SECURITY FIX: Authorization for student access
            if ($user->hasRole('student') && $user->id != $studentId) {
                abort(403, 'तपाईंसँग अरू विद्यार्थीको भुक्तानी इतिहास हेर्ने अनुमति छैन');
            }

            // ✅ SECURITY FIX: Eager load with proper relationships
            $student = Student::with(['user', 'payments.hostel'])
                ->findOrFail($studentId);

            $payments = $student->payments()
                ->latest()
                ->get();

            // ✅ FIXED: Get logo URL from first payment's hostel if exists
            $logo_url = null;
            if ($payments->isNotEmpty() && $payments->first()->hostel) {
                $hostel = $payments->first()->hostel;
                if ($hostel->logo_path && Storage::disk('public')->exists($hostel->logo_path)) {
                    $absolutePath = storage_path('app/public/' . $hostel->logo_path);
                    if (file_exists($absolutePath)) {
                        $logo_url = 'file://' . str_replace('\\', '/', $absolutePath);
                    }
                }
            }

            // Generate PDF
            $html = view('pdf.student_payment_history', [
                'student' => $student,
                'payments' => $payments,
                'logo_url' => $logo_url,
                'logo_path' => $logo_url,
            ])->render();

            $pdf = PDF::loadHTML($html)
                ->setPaper('a4', 'portrait')
                ->setOption('enable-local-file-access', true);

            $safeFilename = 'payment_history_' . preg_replace('/[^a-zA-Z0-9_-]/', '', $student->id) . '.pdf';

            return $pdf->download($safeFilename);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            \Log::error('PDF Payment History - Student not found: ' . $e->getMessage());
            abort(404, 'विद्यार्थी फेला परेन');
        } catch (\Exception $e) {
            \Log::error('PDF Payment History Error: ' . $e->getMessage());
            return response()->json(['error' => 'भुक्तानी इतिहास जनरेसन असफल भयो'], 500);
        }
    }

    /**
     * ✅ NEW: Helper method to get logo URL for DOMPDF
     * This method can be used by all PDF generation methods
     */
    private function getLogoUrlForDompdf($hostel)
    {
        if (!$hostel || !$hostel->logo_path) {
            return null;
        }

        try {
            // Check if file exists in storage
            if (Storage::disk('public')->exists($hostel->logo_path)) {
                // Get absolute path
                $absolutePath = storage_path('app/public/' . $hostel->logo_path);

                // Verify file exists and is readable
                if (file_exists($absolutePath) && is_readable($absolutePath)) {
                    // Use file:// protocol for DOMPDF
                    return 'file://' . str_replace('\\', '/', $absolutePath);
                }
            }

            return null;
        } catch (\Exception $e) {
            \Log::warning('Failed to get logo URL for hostel ' . $hostel->id . ': ' . $e->getMessage());
            return null;
        }
    }
}
