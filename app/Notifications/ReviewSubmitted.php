<?php

namespace App\Notifications;

use App\Models\Review;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;

class ReviewSubmitted extends Notification
{
    use Queueable;

    protected $review;

    public function __construct(Review $review)
    {
        $this->review = $review;
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('नयाँ समीक्षा प्राप्त भयो - HostelHub')
            ->greeting('नमस्ते ' . $notifiable->name . '!')
            ->line('एक नयाँ समीक्षा प्राप्त भएको छ।')
            ->line('समीक्षा गर्ने: ' . $this->review->name)
            ->action('समीक्षा हेर्नुहोस्', route('admin.reviews.show', $this->review))
            ->line('धन्यवाद!');
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'नयाँ समीक्षा प्राप्त भयो',
            'message' => $this->review->name . ' ले नयाँ समीक्षा गरेका छन्',
            'url' => route('admin.reviews.show', $this->review),
            'type' => 'review_submitted'
        ];
    }
}
