<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class HostelOwner
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // यूजर लगइन गरेको छ कि जाँच गर्ने
        if (!$user) {
            return redirect('/login')->with('error', 'कृपया लगइन गर्नुहोस्।');
        }

        // यूजरले होस्टल मालिकको रूपमा पहुँच छ कि जाँच गर्ने
        if ($user->role->name !== 'admin' && $user->role->name !== 'manager') {
            return redirect('/')->with('error', 'तपाईंसँग यो पृष्ठ हेर्ने अनुमति छैन।');
        }

        return $next($request);
    }
}
