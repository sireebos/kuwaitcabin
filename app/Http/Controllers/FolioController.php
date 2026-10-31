<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Folio;
use App\Models\FolioItem;
use App\Models\Payment;
use App\Models\Property;
use Illuminate\Http\Request;

class FolioController extends Controller
{
    public function show(Property $property, Booking $booking)
    {
        $folio = $booking->folio;
        $folio->load('items');
        
        return view('folio.show', compact('property', 'booking', 'folio'));
    }

    public function addCharge(Request $request, Property $property, Booking $booking)
    {
        $validated = $request->validate([
            'description' => 'required|string',
            'type' => 'required|in:room,restaurant,bar,service,other',
            'amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $folio = $booking->folio;
        
        FolioItem::create([
            'folio_id' => $folio->id,
            'description' => $validated['description'],
            'type' => $validated['type'],
            'amount' => $validated['amount'],
            'notes' => $validated['notes'] ?? null,
        ]);

        $folio->total_charges += $validated['amount'];
        $folio->updateBalance();

        return redirect()->back()->with('success', 'Charge added to folio');
    }

    public function recordPayment(Request $request, Property $property, Booking $booking)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|in:credit_card,debit_card,cash,bank_transfer,check',
            'currency' => 'nullable|string|max:10',
        ]);

        $folio = $booking->folio;
        $folio->payments_received = ($folio->payments_received ?? 0) + $validated['amount'];
        $folio->balance = ($folio->total_charges ?? 0) - $folio->payments_received;
        $folio->save();

        // Create payment record with currency
        Payment::create([
            'booking_id' => $booking->id,
            'property_id' => $property->id,
            'amount' => $validated['amount'],
            'payment_method' => $validated['payment_method'],
            'currency' => $validated['currency'] ?? $property->currency ?? '₦',
            'status' => 'completed',
            'transaction_id' => 'TXN-' . time() . '-' . $booking->id,
        ]);

        return redirect()->back()->with('success', 'Payment of ₦' . number_format($validated['amount'], 2) . ' recorded successfully');
    }

    public function print(Property $property, Booking $booking)
    {
        $folio = $booking->folio;
        $folio->load('items');
        
        return view('folio.print', compact('property', 'booking', 'folio'));
    }
}

