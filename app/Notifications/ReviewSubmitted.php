<?php

namespace App\Notifications;

use App\Models\Review;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReviewSubmitted extends Notification implements ShouldQueue
{
    use Queueable;

    protected $review;

    public function __construct(Review $review)
    {
        $this->review = $review;
    }

    public function via($notifiable)
    {
        return ['database', 'mail']; // दुवै माध्यम
    }

    public function toMail($notifiable)
    {
        $type = $this->review->type ?? 'review';
        $subject = $type === 'platform' ? 'नयाँ प्लेटफर्म समीक्षा' : 'नयाँ होस्टल समीक्षा';

        return (new MailMessage)
            ->subject($subject)
            ->greeting('नमस्ते, ' . $notifiable->name)
            ->line($this->review->name . ' ले नयाँ समीक्षा पेश गरेका छन्।')
            ->line('रेटिङ: ' . $this->review->rating . '/5')
            ->line('टिप्पणी: ' . $this->review->comment)
            ->action('हेर्नुहोस्', route('admin.reviews.show', $this->review->id))
            ->line('धन्यवाद!');
    }

    public function toArray($notifiable)
    {
        // यदि notifiable को role admin छ भने admin.url, नभए owner.url (यदि owner role छ)
        $url = $notifiable->hasRole('admin')
            ? route('admin.reviews.show', $this->review->id)
            : route('owner.reviews.show', $this->review->id);

        return [
            'review_id' => $this->review->id,
            'name' => $this->review->name,
            'rating' => $this->review->rating,
            'comment' => $this->review->comment,
            'type' => $this->review->type ?? 'review',
            'message' => $this->review->name . ' ले नयाँ समीक्षा पेश गरे।',
            'url' => $url,
        ];
    }
}
