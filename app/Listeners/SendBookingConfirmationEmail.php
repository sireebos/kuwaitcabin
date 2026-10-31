<?php

namespace App\Listeners;

use App\Events\BookingConfirmed;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendBookingConfirmationEmail implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(BookingConfirmed $event): void
    {
        $booking = $event->booking;
        
        // Send confirmation email to guest
        Mail::send('emails.booking-confirmation', [
            'booking' => $booking,
            'guest' => $booking->guest,
        ], function ($message) use ($booking) {
            $message->to($booking->guest->email)
                    ->subject('Booking Confirmation - ' . $booking->booking_reference);
        });

        // Log the event
        \Log::info('Booking confirmation email sent', [
            'booking_id' => $booking->id,
            'guest_email' => $booking->guest->email,
        ]);
    }
}

