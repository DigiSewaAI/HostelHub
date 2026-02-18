<?php

namespace App\Notifications;

use App\Models\Review;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;

class NewReviewNotification extends Notification
{
    use Queueable;

    protected $review;

    public function __construct(Review $review)
    {
        $this->review = $review;
    }

    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'title'      => 'नयाँ समीक्षा',
            'message'    => "{$this->review->name} ले नयाँ समीक्षा पेश गरेका छन्।",
            'review_id'  => $this->review->id,
            'hostel_id'  => $this->review->hostel_id,
            'student_id' => $this->review->student_id,
            'rating'     => $this->review->rating,
            'type'       => 'review',
            'url'        => route('owner.reviews.show', $this->review->id),
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'title'   => 'नयाँ समीक्षा',
            'message' => "{$this->review->name} ले नयाँ समीक्षा पेश गरेका छन्।",
            'type'    => 'review',
        ]);
    }
}
