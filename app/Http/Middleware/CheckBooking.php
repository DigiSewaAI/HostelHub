<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;

class CheckBooking
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if ($user && $user->hasRole('student')) {
            $student = Student::where('user_id', $user->id)->first();

            // If student record not found
            if (!$student) {
                return redirect()->route('student.bookings.index')
                    ->with('error', 'पहिले होस्टल बुक गर्नुहोस्।');
            }

            // If no hostel assigned OR status not active/approved
            if (!$student->hostel_id || !in_array($student->status, ['active', 'approved'])) {
                return redirect()->route('student.bookings.index')
                    ->with('error', 'तपाईं हाल कुनै होस्टलमा सक्रिय हुनुहुन्न। कृपया पहिले बुकिङ गर्नुहोस्।');
            }
        }

        return $next($request);
    }
}