<?php

namespace App\Console\Commands;

use App\Models\RoomIssue;
use Illuminate\Console\Command;
use Illuminate\Notifications\DatabaseNotification;

class UpdateRoomIssueNotificationsAvatar extends Command
{
    protected $signature = 'notifications:update-avatar';
    protected $description = 'Update existing room issue notifications with student avatar';

    public function handle()
    {
        $notifications = DatabaseNotification::where('type', 'App\Notifications\RoomIssueNotification')->get();

        $bar = $this->output->createProgressBar($notifications->count());
        $bar->start();

        $updatedCount = 0;
        foreach ($notifications as $notification) {
            $data = $notification->data;

            if (isset($data['avatar']) && !empty($data['avatar'])) {
                $bar->advance();
                continue;
            }

            if (!isset($data['issue_id'])) {
                $bar->advance();
                continue;
            }

            $roomIssue = RoomIssue::with('student')->find($data['issue_id']);
            if (!$roomIssue || !$roomIssue->student) {
                $bar->advance();
                continue;
            }

            $student = $roomIssue->student;
            $avatarUrl = $student->image ? asset('storage/' . $student->image) : null;

            $data['avatar'] = $avatarUrl;
            $notification->data = $data;
            $notification->save();

            $updatedCount++;
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("Total updated: $updatedCount notifications.");
    }
}
