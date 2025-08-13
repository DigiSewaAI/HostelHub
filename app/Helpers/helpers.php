<?php

// YouTube ID निकाल्ने हेल्पर फंक्शन
if (!function_exists('getYoutubeId')) {
    /**
     * Extract YouTube video ID from any YouTube URL
     * 
     * @param string $url YouTube URL (e.g., https://youtu.be/dQw4w9WgXcQ)
     * @return string|null 11-character YouTube ID or null if invalid
     */
    function getYoutubeId(string $url): ?string
    {
        $pattern = '/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/';
        preg_match($pattern, $url, $matches);
        return $matches[1] ?? null;
    }
}

// अन्य हेल्पर फंक्शनहरू यहाँ थप्न सक्नुहुन्छ
// if (!function_exists('nepaliDate')) { ... }
