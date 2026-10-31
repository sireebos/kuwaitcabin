<?php

namespace App\Repositories;

use App\Models\Booking;
use Carbon\Carbon;

class BookingRepository extends BaseRepository
{
    public function getModel(): string
    {
        return Booking::class;
    }

    public function getAvailableRooms($checkInDate, $checkOutDate, $propertyId)
    {
        return \DB::table('rooms')
            ->where('property_id', $propertyId)
            ->whereNotIn('id', function ($query) use ($checkInDate, $checkOutDate) {
                $query->select('room_id')
                    ->from('room_inventory')
                    ->whereBetween('date', [$checkInDate, $checkOutDate->subDay()])
                    ->where('status', '!=', 'available');
            })
            ->get();
    }

    public function getBookingsByProperty($propertyId, $status = null)
    {
        $query = $this->model->where('property_id', $propertyId);
        if ($status) {
            $query->where('status', $status);
        }
        return $query->with(['guest', 'room', 'payments'])->paginate(15);
    }

    public function getBookingsByPropertyAndDate($propertyId, $date, $status = null)
    {
        $query = $this->model->where('property_id', $propertyId)
            ->whereDate('created_at', $date);
        if ($status) {
            $query->where('status', $status);
        }
        return $query->with(['guest', 'room', 'payments'])->orderBy('created_at', 'desc')->paginate(15);
    }

    public function getUpcomingCheckIns($propertyId, $days = 7)
    {
        $startDate = Carbon::now();
        $endDate = Carbon::now()->addDays($days);
        
        return $this->model
            ->where('property_id', $propertyId)
            ->whereBetween('check_in_date', [$startDate, $endDate])
            ->where('status', '!=', 'cancelled')
            ->with(['guest', 'room'])
            ->get();
    }

    public function getOccupiedRooms($propertyId)
    {
        return $this->model
            ->where('property_id', $propertyId)
            ->where('status', 'checked_in')
            ->with(['room', 'guest'])
            ->get();
    }
}

