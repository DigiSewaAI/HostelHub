<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RedirectStudentWithoutHostel
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if ($user && $user->hasRole('student')) {
            $student = $user->student; // User model बाट student लिने

            // यदि student छैन, वा status active छैन, वा hostel_id null छ भने
            if (!$student || $student->status !== 'active' || $student->hostel_id === null) {
                Log::warning('RedirectStudentWithoutHostel: पहुँच अस्वीकृत → welcome मा पठाइयो', [
                    'user_id' => $user->id,
                    'student_exists' => !is_null($student),
                    'status' => $student?->status,
                    'hostel_id' => $student?->hostel_id,
                ]);

                return redirect()->route('student.welcome');
            }
        }

        return $next($request);
    }
}
