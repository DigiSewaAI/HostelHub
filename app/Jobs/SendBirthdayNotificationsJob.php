<?php

namespace App\Jobs;

use App\Models\Student;
use App\Models\User;
use App\Notifications\BirthdayNotification;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendBirthdayNotificationsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        Log::info('🚀 SendBirthdayNotificationsJob started at ' . now());
        $today = Carbon::today();

        // Get all active students whose birthday is today
        $birthdayStudents = Student::whereRaw('MONTH(dob) = ? AND DAY(dob) = ?', [$today->month, $today->day])
            ->where('status', 'active')
            ->with('hostel')
            ->get();

        Log::info('📊 Birthday students found: ' . $birthdayStudents->count());

        if ($birthdayStudents->isEmpty()) {
            Log::info('❌ No birthday students today.');
            return;
        }

        foreach ($birthdayStudents as $birthdayStudent) {
            Log::info("🎂 Processing birthday student ID: {$birthdayStudent->id}, Name: {$birthdayStudent->name}");

            $hostel = $birthdayStudent->hostel;
            if (!$hostel) {
                Log::warning("⚠️ Birthday student ID {$birthdayStudent->id} has no hostel. Skipping.");
                continue;
            }

            // --- Send notifications to other students in the same hostel ---
            $this->notifyOtherStudents($birthdayStudent, $hostel, $today);

            // --- Send notification to the hostel owner (via owner_id) ---
            $this->notifyHostelOwner($birthdayStudent, $hostel, $today);
        }

        Log::info('🏁 SendBirthdayNotificationsJob completed at ' . now());
    }

    /**
     * Send birthday notifications to other students in the same hostel
     */
    private function notifyOtherStudents($birthdayStudent, $hostel, $today)
    {
        $recipients = Student::where('hostel_id', $hostel->id)
            ->where('status', 'active')
            ->where('id', '!=', $birthdayStudent->id)
            ->with('user')
            ->get();

        Log::info("👥 Found {$recipients->count()} potential student recipients in hostel {$hostel->id}.");

        foreach ($recipients->chunk(100) as $chunk) {
            foreach ($chunk as $recipient) {
                $user = $recipient->user;
                if (!$user) {
                    Log::warning("⚠️ Recipient student ID {$recipient->id} has no linked user. Skipping.");
                    continue;
                }

                // Check for duplicate notification today
                $alreadySent = $user->notifications()
                    ->where('type', 'App\Notifications\BirthdayNotification')
                    ->whereDate('created_at', $today)
                    ->where('data->student_id', $birthdayStudent->id)
                    ->exists();

                if ($alreadySent) {
                    Log::info("⏭️ Notification already sent to user {$user->id} for student {$birthdayStudent->id}. Skipping.");
                    continue;
                }

                $user->notify(new BirthdayNotification($birthdayStudent));
                Log::info("✅ Birthday notification sent to user {$user->id} (student {$recipient->id}) for student {$birthdayStudent->id}.");
            }
        }
    }

    /**
     * Send birthday notification to the hostel owner
     */
    private function notifyHostelOwner($birthdayStudent, $hostel, $today)
    {
        if (!$hostel->owner_id) {
            Log::warning("⚠️ Hostel ID {$hostel->id} has no owner_id. Skipping owner notification.");
            return;
        }

        $owner = User::find($hostel->owner_id);
        if (!$owner) {
            Log::warning("⚠️ Hostel ID {$hostel->id} has owner_id = {$hostel->owner_id} but user not found.");
            return;
        }

        // Check for duplicate notification today
        $alreadySent = $owner->notifications()
            ->where('type', 'App\Notifications\BirthdayNotification')
            ->whereDate('created_at', $today)
            ->where('data->student_id', $birthdayStudent->id)
            ->exists();

        if ($alreadySent) {
            Log::info("⏭️ Notification already sent to owner {$owner->id} for student {$birthdayStudent->id}. Skipping.");
            return;
        }

        $owner->notify(new BirthdayNotification($birthdayStudent));
        Log::info("✅ Birthday notification sent to owner {$owner->id} for student {$birthdayStudent->id}.");
    }
}
