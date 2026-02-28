<?php
// app/Helpers/NotificationHelper.php

if (!function_exists('getNotificationAvatar')) {
    function getNotificationAvatar($sender, $type = 'system')
    {
        if (!$sender) {
            return asset('images/logo.png'); // प्रणालीको लोगो
        }

        if ($sender instanceof \App\Models\User) {
            if ($sender->isStudent()) {  // तपाईंको User मा role हेर्ने method छ भने
                $student = $sender->student; // मानौं student relation छ
                return $student && $student->image ? asset('storage/' . $student->image) : asset('images/default-avatar.png');
            }
            if ($sender->isHostelManager()) {
                $hostel = $sender->hostels()->where('is_published', true)->first(); // मुख्य होस्टल
                if ($hostel && $hostel->logo_url) {
                    return $hostel->logo_url;
                }
                return asset('images/default-hostel.png');
            }
        }
        return asset('images/logo.png'); // fallback
    }
}
