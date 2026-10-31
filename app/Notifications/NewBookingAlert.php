<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewBookingAlert extends Notification
{
    public function __construct(public Booking $booking)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $booking = $this->booking;
        $guest = $booking->guest;
        $room = $booking->room;
        $property = $booking->property;
        $currency = $property->currency ?? '₦';
        
        return (new MailMessage)
            ->subject('🔔 New Booking Alert: ' . $booking->booking_reference)
            ->greeting('New Booking Received!')
            ->line('A new booking has been placed from your website.')
            ->line('')
            ->line('**Booking Details:**')
            ->line('Reference: ' . $booking->booking_reference)
            ->line('Guest: ' . ($guest->full_name ?? $guest->first_name . ' ' . $guest->last_name))
            ->line('Email: ' . $guest->email)
            ->line('Phone: ' . $guest->phone)
            ->line('')
            ->line('**Room Details:**')
            ->line('Room: ' . $room->room_number . ' (' . ($room->roomType->name ?? 'N/A') . ')')
            ->line('Check-in: ' . $booking->check_in_date->format('D, M j, Y'))
            ->line('Check-out: ' . $booking->check_out_date->format('D, M j, Y'))
            ->line('Nights: ' . $booking->number_of_nights)
            ->line('Guests: ' . $booking->number_of_guests)
            ->line('')
            ->line('**Payment:**')
            ->line('Total Amount: ' . $currency . ' ' . number_format($booking->total_amount, 2))
            ->line('Status: ' . ucfirst($booking->status))
            ->line('')
            ->action('View Booking', url('/properties/' . $property->id . '/bookings/' . $booking->id))
            ->line('Please review and confirm this booking.');
    }

    public function toArray(object $notifiable): array
    {
        $booking = $this->booking;
        $guest = $booking->guest;
        $room = $booking->room;
        $property = $booking->property;
        
        return [
            'booking_id' => $booking->id,
            'booking_reference' => $booking->booking_reference,
            'property_id' => $property->id,
            'property_name' => $property->name,
            'guest_id' => $guest->id,
            'guest_name' => $guest->full_name ?? $guest->first_name . ' ' . $guest->last_name,
            'guest_email' => $guest->email,
            'guest_phone' => $guest->phone,
            'room_id' => $room->id,
            'room_number' => $room->room_number,
            'room_type' => $room->roomType->name ?? 'N/A',
            'check_in_date' => $booking->check_in_date->format('Y-m-d'),
            'check_out_date' => $booking->check_out_date->format('Y-m-d'),
            'number_of_nights' => $booking->number_of_nights,
            'number_of_guests' => $booking->number_of_guests,
            'total_amount' => $booking->total_amount,
            'currency' => $property->currency ?? '₦',
            'status' => $booking->status,
            'booking_source' => $booking->booking_source ?? 'website',
            'type' => 'new_booking_alert',
        ];
    }

    /**
     * Generate WhatsApp message for the booking
     */
    public static function getWhatsAppMessage(Booking $booking): string
    {
        $guest = $booking->guest;
        $room = $booking->room;
        $property = $booking->property;
        $currency = $property->currency ?? '₦';
        
        $message = "🔔 *NEW BOOKING ALERT*\n\n";
        $message .= "Reference: {$booking->booking_reference}\n\n";
        $message .= "*Guest:*\n";
        $message .= "Name: " . ($guest->full_name ?? $guest->first_name . ' ' . $guest->last_name) . "\n";
        $message .= "Email: {$guest->email}\n";
        $message .= "Phone: {$guest->phone}\n\n";
        $message .= "*Room:*\n";
        $message .= "Room: {$room->room_number} (" . ($room->roomType->name ?? 'N/A') . ")\n";
        $message .= "Check-in: " . $booking->check_in_date->format('D, M j, Y') . "\n";
        $message .= "Check-out: " . $booking->check_out_date->format('D, M j, Y') . "\n";
        $message .= "Nights: {$booking->number_of_nights}\n";
        $message .= "Guests: {$booking->number_of_guests}\n\n";
        $message .= "*Payment:*\n";
        $message .= "Total: {$currency} " . number_format($booking->total_amount, 2) . "\n";
        $message .= "Status: " . ucfirst($booking->status) . "\n\n";
        $message .= "Please review and confirm this booking.";
        
        return $message;
    }

    /**
     * Get WhatsApp URL for sending the message
     */
    public static function getWhatsAppUrl(string $phone, Booking $booking): string
    {
        $message = urlencode(self::getWhatsAppMessage($booking));
        // Clean phone number - remove any non-numeric characters except +
        $cleanPhone = preg_replace('/[^\d+]/', '', $phone);
        return "https://wa.me/{$cleanPhone}?text={$message}";
    }
}
