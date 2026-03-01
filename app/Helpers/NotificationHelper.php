<?php

if (!function_exists('getNotificationAvatar')) {
    /**
     * Get avatar URL for notification based on sender user.
     *
     * @param  \App\Models\User|null  $sender
     * @param  string                 $type (optional) – भविष्यको लागि मात्र
     * @return string
     */
    function getNotificationAvatar($sender, $type = 'system'): string
    {
        // यदि sender मान्य छैन भने डिफल्ट लोगो
        if (!$sender || !($sender instanceof \App\Models\User)) {
            $fallback = asset('images/logo.png');
            \Log::debug('getNotificationAvatar: Invalid sender', ['fallback' => $fallback]);
            return $fallback;
        }

        try {
            // 1. Student (विद्यार्थी) को अवतार
            if (method_exists($sender, 'isStudent') && $sender->isStudent()) {
                $student = null;
                if (method_exists($sender, 'getStudent')) {
                    $student = $sender->getStudent();
                } elseif (method_exists($sender, 'student') && $sender->student) {
                    $student = $sender->student;
                }

                if ($student && !empty($student->image)) {
                    $url = asset('storage/' . $student->image);
                    \Log::debug('getNotificationAvatar: Student avatar', [
                        'user_id' => $sender->id,
                        'url' => $url
                    ]);
                    return $url;
                }

                \Log::debug('getNotificationAvatar: Student but no image', [
                    'user_id' => $sender->id
                ]);
            }

            // 2. Hostel Owner/Manager को लोगो
            if (method_exists($sender, 'isHostelManager') && $sender->isHostelManager()) {
                if (method_exists($sender, 'hostels')) {
                    $hostel = $sender->hostels()->first(); // कुनै पनि होस्टेल (status नहेरी)
                    if ($hostel && !empty($hostel->logo_path)) {
                        $url = asset('storage/' . $hostel->logo_path);
                        \Log::debug('getNotificationAvatar: Hostel logo', [
                            'user_id' => $sender->id,
                            'hostel_id' => $hostel->id,
                            'url' => $url
                        ]);
                        return $url;
                    }

                    \Log::debug('getNotificationAvatar: Hostel manager but no logo', [
                        'user_id' => $sender->id,
                        'hostel_exists' => $hostel ? 'yes' : 'no'
                    ]);
                } else {
                    \Log::debug('getNotificationAvatar: Hostel manager but hostels() method missing', [
                        'user_id' => $sender->id
                    ]);
                }
            }

            // 3. User को आफ्नै profile photo (यदि छ भने)
            if (!empty($sender->profile_photo_path)) {
                $url = asset('storage/' . $sender->profile_photo_path);
                \Log::debug('getNotificationAvatar: User profile photo', [
                    'user_id' => $sender->id,
                    'url' => $url
                ]);
                return $url;
            }
        } catch (\Exception $e) {
            \Log::error('getNotificationAvatar error: ' . $e->getMessage(), [
                'user_id' => $sender->id ?? null,
                'trace' => $e->getTraceAsString()
            ]);
        }

        // 4. अन्तिम फलब्याक – डिफल्ट लोगो
        $fallback = asset('images/logo.png');
        \Log::debug('getNotificationAvatar: Final fallback', [
            'user_id' => $sender->id ?? null,
            'fallback' => $fallback
        ]);
        return $fallback;
    }
}
