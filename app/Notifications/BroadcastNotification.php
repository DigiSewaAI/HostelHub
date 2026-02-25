<?php

namespace App\Notifications;

use App\Models\BroadcastMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BroadcastNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $broadcast;

    /**
     * Create a new notification instance.
     */
    public function __construct(BroadcastMessage $broadcast)
    {
        $this->broadcast = $broadcast;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'broadcast_id' => $this->broadcast->id,
            'subject' => $this->broadcast->subject,
            'body' => $this->broadcast->body,
            'sender_id' => $this->broadcast->sender_id,
            'sender_name' => $this->broadcast->sender->name ?? 'अज्ञात',
            'url' => route('network.messages.index'),
            'icon' => 'bullhorn',
            'type' => 'broadcast',
        ];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('नयाँ ब्रोडकास्ट: ' . $this->broadcast->subject)
                    ->greeting('नमस्ते ' . $notifiable->name)
                    ->line($this->broadcast->body)
                    ->action('इनबक्स हेर्नुहोस्', route('network.messages.index'))
                    ->line('यो सन्देश HostelHub प्रणालीबाट पठाइएको हो।');
    }
}