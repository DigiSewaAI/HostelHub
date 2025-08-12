<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class Localize
{
    public function handle(Request $request, Closure $next): Response
    {
        // URL बाट भाषा परामिति प्राप्त गर्ने (जस्तै: /en/dashboard)
        $locale = $request->segment(1);

        // यदि वैध भाषा हो भने सेट गर्ने
        if (in_array($locale, ['en', 'ne'])) {
            App::setLocale($locale);
            Session::put('locale', $locale);
        }
        // यदि सेसनमा भाषा छ भने
        elseif (Session::has('locale')) {
            App::setLocale(Session::get('locale'));
        }
        // डिफल्ट भाषा
        else {
            App::setLocale('en');
        }

        return $next($request);
    }
}
