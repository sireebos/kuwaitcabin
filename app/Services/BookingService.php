<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\BookingHistory;
use App\Models\Folio;
use App\Models\RoomInventory;
use App\Repositories\BookingRepository;
use App\Events\BookingCreated;
use Carbon\Carbon;
use Illuminate\Support\Str;
use DB;

class BookingService
{
    public function __construct(private BookingRepository $repository)
    {
    }

    public function createBooking(array $data): Booking
    {
        return DB::transaction(function () use ($data) {
            $data['booking_reference'] = $this->generateBookingReference();
            $data['number_of_nights'] = Carbon::parse($data['check_out_date'])
                ->diffInDays(Carbon::parse($data['check_in_date']));

            $booking = $this->repository->create($data);

            // Create folio with room charges (ensure positive values)
            $folio = Folio::create([
                'booking_id' => $booking->id,
                'room_charges' => abs($booking->subtotal),
                'total_charges' => abs($booking->total_amount),
                'balance' => abs($booking->total_amount),
            ]);

            // Reserve room inventory
            $this->reserveRoomInventory($booking);

            // Dispatch booking created event
            BookingCreated::dispatch($booking);

            return $booking;
        });
    }

    public function confirmBooking(Booking $booking): bool
    {
        return DB::transaction(function () use ($booking) {
            $booking->update(['status' => 'confirmed']);
            return true;
        });
    }

    public function checkIn(Booking $booking, $roomId): bool
    {
        return DB::transaction(function () use ($booking, $roomId) {
            $booking->update([
                'status' => 'checked_in',
                'room_id' => $roomId
            ]);
            return true;
        });
    }

    public function checkOut(Booking $booking): bool
    {
        return DB::transaction(function () use ($booking) {
            $booking->update([
                'status' => 'checked_out',
                'check_out_date' => now()->format('Y-m-d'), // Update to actual checkout date
            ]);

            // Release room inventory for remaining dates (makes room available again)
            $this->releaseRoomInventory($booking);

            // Create booking history record
            $folio = $booking->folio;
            BookingHistory::create([
                'booking_id' => $booking->id,
                'property_id' => $booking->property_id,
                'guest_id' => $booking->guest_id,
                'room_id' => $booking->room_id,
                'booking_reference' => $booking->booking_reference,
                'check_in_date' => $booking->check_in_date,
                'check_out_date' => now()->format('Y-m-d'), // Actual checkout date
                'number_of_guests' => $booking->number_of_guests,
                'number_of_nights' => $booking->number_of_nights,
                'room_rate' => $booking->room_rate,
                'subtotal' => $booking->subtotal,
                'tax' => $booking->tax,
                'total_amount' => $booking->total_amount,
                'final_amount' => $folio->total_charges ?? $booking->total_amount,
                'payments_received' => $folio->payments_received ?? 0,
                'balance' => $folio->balance ?? 0,
                'currency' => $booking->property->currency ?? '₦',
                'special_requests' => $booking->special_requests,
                'checked_out_at' => now(),
            ]);

            return true;
        });
    }

    private function reserveRoomInventory(Booking $booking): void
    {
        $startDate = Carbon::parse($booking->check_in_date)->startOfDay();
        $endDate = Carbon::parse($booking->check_out_date)->startOfDay();

        while ($startDate < $endDate) {
            $dateString = $startDate->format('Y-m-d');

            // Always create a new unique record for each booking date
            try {
                RoomInventory::create([
                    'room_id' => $booking->room_id,
                    'date' => $dateString,
                    'status' => 'reserved',
                    'price' => $booking->room_rate
                ]);
            } catch (\Exception $e) {
                // If record already exists, update it
                RoomInventory::where('room_id', $booking->room_id)
                    ->where('date', $dateString)
                    ->update([
                        'status' => 'reserved',
                        'price' => $booking->room_rate
                    ]);
            }

            $startDate->addDay();
        }
    }

    public function releaseRoomInventory(Booking $booking): void
    {
        // Release all room inventory records for this booking
        // This makes the room available for new bookings from today onwards
        $today = Carbon::today()->format('Y-m-d');
        
        // Delete inventory records from today onwards (future dates)
        RoomInventory::where('room_id', $booking->room_id)
            ->where('date', '>=', $today)
            ->where('status', 'reserved')
            ->delete();
    }

    private function generateBookingReference(): string
    {
        return 'BK' . strtoupper(Str::random(8)) . date('Ymd');
    }
}

