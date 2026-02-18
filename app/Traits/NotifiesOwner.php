<?php

namespace App\Traits;

use Illuminate\Notifications\Notification;

trait NotifiesOwner
{
    /**
     * Notify the owner of the instance.
     *
     * @param  mixed  $instance           Model instance (Booking, Student, Review, RoomIssue)
     * @param  string  $notificationClass Notification class to send
     * @return void
     */
    public function notifyOwner($instance, string $notificationClass)
    {
        $owner = null;

        // If instance has a hostel
        if (method_exists($instance, 'hostel') && $instance->hostel) {
            $owner = $instance->hostel->owner;
        }
        // If instance has a student relation
        elseif (method_exists($instance, 'student') && $instance->student) {
            $owner = $instance->student->hostel?->owner;
        }

        if ($owner) {
            $owner->notify(new $notificationClass($instance));
        }
    }
}
