<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class OwnerProfileMiddleware
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
        $user = $request->user();

        // यदि प्रयोगकर्ता लगइन छैन भने, पहिले लगइन गर्न पठाउनुहोस्।
        if (!$user) {
            return redirect()->route('login');
        }

        // केवल owner र hostel_manager लाई मात्र यो middleware आवश्यक हुन सक्छ।
        // तर तपाईंको रुटमा पहिले नै 'role:owner,hostel_manager' छ भने यहाँ दोहोर्याइरहनु पर्दैन।
        // यद्यपि, हामी सुरक्षाको लागि जाँच गर्न सक्छौं।

        // यहाँ owner को प्रोफाइल पूरा भएको छ कि छैन जाँच गर्नुहोस्।
        // उदाहरण: यदि प्रयोगकर्तासँग कुनै संगठन (Organization) छैन भने, उसलाई संगठन बनाउन पठाउनुहोस्।

        // मानौं, Organization model छ र user सँग organization को सम्बन्ध 'organization' छ।
        // यदि user को organization छैन भने organization बनाउन पठाउने।

        /*
        if ($user->hasRole('owner') && !$user->organization) {
            // संगठन बनाउने रुटको नाम यहाँ राख्नुहोस्।
            return redirect()->route('organization.create')
                ->with('warning', 'कृपया पहिले आफ्नो संगठनको विवरण भर्नुहोस्।');
        }
        */

        // वा, यदि Hostel model को प्रयोग गर्ने हो भने:
        /*
        if ($user->hasRole('owner') && $user->hostels()->count() == 0) {
            return redirect()->route('owner.hostels.create')
                ->with('warning', 'कृपया पहिले एउटा होस्टल सिर्जना गर्नुहोस्।');
        }
        */

        // अहिलेको लागि, हामी केवल अनुरोधलाई अगाडि बढाउँछौं र लग गर्छौं।
        // तपाईंले माथिको आवश्यकता अनुसार uncomment गरेर आफ्नो व्यवसायिक तर्क राख्न सक्नुहुन्छ।

        return $next($request);
    }
}
