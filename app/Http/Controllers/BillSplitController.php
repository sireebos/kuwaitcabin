<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\Order;
use App\Models\BillSplit;
use Illuminate\Http\Request;

class BillSplitController extends Controller
{
    public function index(Property $property, Order $order)
    {
        $order->load('billSplits');
        return view('orders.bill-split', compact('property', 'order'));
    }

    public function store(Request $request, Property $property, Order $order)
    {
        $validated = $request->validate([
            'guest_name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'nullable|string',
        ]);

        BillSplit::create([
            'order_id' => $order->id,
            'guest_name' => $validated['guest_name'],
            'amount' => $validated['amount'],
            'payment_method' => $validated['payment_method'],
            'status' => 'pending',
        ]);

        return redirect()->back()->with('success', 'Bill split added successfully');
    }

    public function destroy(Property $property, Order $order, BillSplit $billSplit)
    {
        $billSplit->delete();
        return redirect()->back()->with('success', 'Bill split deleted successfully');
    }

    public function updateStatus(Request $request, Property $property, Order $order, BillSplit $billSplit)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,paid,cancelled',
        ]);

        $billSplit->update(['status' => $validated['status']]);
        return redirect()->back()->with('success', 'Bill split status updated');
    }
}
