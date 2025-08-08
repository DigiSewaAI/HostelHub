<?php

namespace Database\Seeders;

use App\Models\Review;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample testimonials
        Review::create([
            'name' => 'राम श्रेष्ठ',
            'position' => 'प्रबन्धक, काठमाडौं होस्टल',
            'content' => 'HostelHub ले हाम्रो होस्टल व्यवस्थापनलाई पूर्ण रूपमा बदल्यो। सबै काम सजिलो र व्यवस्थित रूपमा गर्न सकिन्छ।',
            'initials' => 'RS',
            'type' => 'testimonial',
            'status' => 'active',
            'rating' => 5
        ]);

        Review::create([
            'name' => 'सुनीता पौडेल',
            'position' => 'विद्यार्थी, काठमाडौं विश्वविद्यालय',
            'content' => 'मलाई यस होस्टलमा बस्न राम्रो अनुभव भयो। सफा, सुरक्षित र आरामदायक। HostelHub ले बुकिङ्ग प्रक्रिया सजिलो बनायो।',
            'initials' => 'SP',
            'type' => 'testimonial',
            'status' => 'active',
            'rating' => 4
        ]);

        Review::create([
            'name' => 'हरि शर्मा',
            'position' => 'विद्यार्थी, पोखरा विश्वविद्यालय',
            'content' => 'होस्टलमा खाना राम्रो छ, रूममेटहरू साथ दिन्छन्। HostelHub ले बुकिङ्ग प्रक्रिया सजिलो बनायो।',
            'initials' => 'HS',
            'type' => 'testimonial',
            'status' => 'active',
            'rating' => 5
        ]);

        Review::create([
            'name' => 'सीता अधिकारी',
            'position' => 'विद्यार्थी, पुर्वाञ्चल विश्वविद्यालय',
            'content' => 'मलाई HostelHub मा बुकिङ्ग गर्नु धेरै सजिलो लाग्यो। होस्टल र कोठाको विवरण स्पष्ट रूपमा देखिन्छ।',
            'initials' => 'SA',
            'type' => 'testimonial',
            'status' => 'active',
            'rating' => 5
        ]);

        Review::create([
            'name' => 'गोपाल यादव',
            'position' => 'अभिभावक',
            'content' => 'मेरो छोरा होस्टलमा राम्रोसँग बसिरहेको छ। HostelHub ले मलाई उसको स्थिति र भुक्तानी जानकारी दिन्छ।',
            'initials' => 'GY',
            'type' => 'testimonial',
            'status' => 'active',
            'rating' => 4
        ]);
    }
}
