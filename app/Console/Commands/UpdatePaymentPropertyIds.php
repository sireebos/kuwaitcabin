<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Payment;
use App\Models\Booking;

class UpdatePaymentPropertyIds extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-payment-property-ids';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update existing payments with property_id from their bookings';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $payments = Payment::whereNull('property_id')->get();

        if ($payments->isEmpty()) {
            $this->info('No payments without property_id found.');
            return;
        }

        $this->info("Found {$payments->count()} payments to update.");

        foreach ($payments as $payment) {
            $booking = $payment->booking;
            if ($booking) {
                $payment->update(['property_id' => $booking->property_id]);
                $this->line("Updated payment {$payment->id} with property_id {$booking->property_id}");
            }
        }

        $this->info('All payments updated successfully!');
    }
}
