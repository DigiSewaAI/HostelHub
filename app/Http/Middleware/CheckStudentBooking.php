<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;

class CheckStudentBooking
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if ($user && $user->hasRole('student')) {
            $student = Student::where('user_id', $user->id)->first();

            if ($student) {
                // Check if student is active or approved in any hostel
                if ($student->hostel_id && in_array($student->status, ['active', 'approved'])) {
                    return redirect()->route('student.dashboard')
                        ->with(
                            'error',
                            'तपाईं हाल अर्को होस्टलमा सक्रिय हुनुहुन्छ। नयाँ बुकिङ गर्न पहिले हालको होस्टलबाट inactive हुनुपर्छ।'
                        );
                }
            }
        }

        return $next($request);
    }
}
