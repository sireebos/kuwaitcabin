<?php

namespace App\Http\Controllers;

use App\Models\BookingHistory;
use App\Models\Property;
use Illuminate\Http\Request;

class BookingHistoryController extends Controller
{
    public function index(Property $property)
    {
        $history = $property->bookingHistory()
            ->with(['guest', 'room'])
            ->orderBy('checked_out_at', 'desc')
            ->paginate(15);

        return view('booking-history.index', compact('property', 'history'));
    }

    public function show(Property $property, BookingHistory $bookingHistory)
    {
        $bookingHistory->load(['guest', 'room', 'property']);
        return view('booking-history.show', compact('property', 'bookingHistory'));
    }

    public function export(Property $property, BookingHistory $bookingHistory)
    {
        // Export booking history as PDF or CSV
        return view('booking-history.export', compact('property', 'bookingHistory'));
    }
}

