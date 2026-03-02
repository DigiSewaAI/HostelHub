<?php

namespace App\Jobs;

use App\Models\Student;
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

        // Get all active students whose birthday is today (month and day match)
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

            $hostelId = $birthdayStudent->hostel_id;
            if (!$hostelId) {
                Log::warning("⚠️ Birthday student ID {$birthdayStudent->id} has no hostel_id. Skipping.");
                continue;
            }

            // Get all active students in the same hostel, excluding the birthday student
            $recipients = Student::where('hostel_id', $hostelId)
                ->where('status', 'active')
                ->where('id', '!=', $birthdayStudent->id)
                ->with('user')
                ->get();

            Log::info("👥 Found {$recipients->count()} potential recipients in hostel {$hostelId}.");

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

                    // Send notification
                    $user->notify(new \App\Notifications\BirthdayNotification($birthdayStudent));
                    Log::info("✅ Birthday notification sent to user {$user->id} (student {$recipient->id}) for student {$birthdayStudent->id}.");
                }
            }

            Log::info("🎉 Finished processing for birthday student ID {$birthdayStudent->id}.");
        }
    }
}
