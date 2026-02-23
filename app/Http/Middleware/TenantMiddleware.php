<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TenantMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // ✅ बहु-टेनेन्ट पहिचानको लागि यहाँ तपाईंको तर्क राख्नुहोस्।
        // उदाहरण: उपडोमेन वा हेडरबाट टेनन्ट पत्ता लगाउने, डाटाबेस कनेक्सन स्विच गर्ने, आदि।
        // अहिलेको लागि, हामी केवल अनुरोधलाई अगाडि बढाउँछौं र लग गर्छौं।

        Log::info('TenantMiddleware: Request is being processed', [
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'user' => $request->user()?->id ?? 'guest'
        ]);

        // तपाईंको टेनन्ट पहिचान तर्क यहाँ राख्न सकिन्छ।

        return $next($request);
    }
}
