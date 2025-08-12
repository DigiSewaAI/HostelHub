<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class PaymentVerified
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // यूजर लगइन गरेको छ कि जाँच गर्ने
        if (!$user) {
            return redirect('/login')->with('error', 'कृपया लगइन गर्नुहोस्।');
        }

        // यूजरले भुक्तानी गरेको छ कि जाँच गर्ने
        // (तपाईंको यूजर मोडेलमा payment_verified फिल्ड छ भने)
        if (!$user->payment_verified) {
            return redirect('/payment')->with('error', 'कृपया भुक्तानी पूरा गर्नुहोस्।');
        }

        return $next($request);
    }
}
