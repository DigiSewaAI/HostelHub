<?php

namespace App\Notifications;

use App\Models\MaintenanceRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;

class MaintenanceRequestNotification extends Notification
{
    use Queueable;

    protected $maintenanceRequest;

    public function __construct(MaintenanceRequest $maintenanceRequest)
    {
        $this->maintenanceRequest = $maintenanceRequest;
    }

    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    public function toDatabase($notifiable)
    {
        $studentName = $this->maintenanceRequest->student->name ?? 'कोही';
        return [
            'title'      => 'मर्मत अनुरोध',
            'message'    => "{$studentName} ले {$this->maintenanceRequest->room->room_number} कोठाको लागि मर्मत अनुरोध गरेका छन्।",
            'request_id' => $this->maintenanceRequest->id,
            'hostel_id'  => $this->maintenanceRequest->room->hostel_id,
            'room_id'    => $this->maintenanceRequest->room_id,
            'issue'      => $this->maintenanceRequest->issue,
            'type'       => 'maintenance',
            'url'        => route('owner.maintenance.show', $this->maintenanceRequest->id),
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'title'   => 'मर्मत अनुरोध',
            'message' => "नयाँ मर्मत अनुरोध प्राप्त भएको छ।",
            'type'    => 'maintenance',
        ]);
    }
}
