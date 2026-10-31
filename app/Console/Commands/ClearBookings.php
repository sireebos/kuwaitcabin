<?php

namespace App\Console\Commands;

use App\Models\Booking;
use App\Models\BookingHistory;
use App\Models\Payment;
use App\Models\Folio;
use App\Models\FolioItem;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ClearBookings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bookings:clear {--force : Skip confirmation}';

    /**
     * The description of the console command.
     *
     * @var string
     */
    protected $description = 'Clear all booking records from the database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (!$this->option('force')) {
            $this->warn('⚠️  WARNING: This will delete ALL booking records!');
            $this->warn('This includes:');
            $this->warn('  - All bookings');
            $this->warn('  - All payments');
            $this->warn('  - All folios');
            $this->warn('  - All folio items');
            $this->warn('  - All booking history');
            $this->newLine();

            if (!$this->confirm('Are you sure you want to continue?')) {
                $this->info('❌ Operation cancelled.');
                return;
            }

            if (!$this->confirm('This action cannot be undone. Are you absolutely sure?')) {
                $this->info('❌ Operation cancelled.');
                return;
            }
        }

        try {
            $this->info('🗑️  Clearing all booking records...');

            DB::transaction(function () {
                // Count records before deletion
                $bookingCount = Booking::count();
                $paymentCount = Payment::count();
                $folioItemCount = FolioItem::count();
                $folioCount = Folio::count();
                $historyCount = BookingHistory::count();

                // Delete in correct order (respecting foreign keys)
                $this->info('  Deleting folio items...');
                FolioItem::truncate();

                $this->info('  Deleting folios...');
                Folio::truncate();

                $this->info('  Deleting payments...');
                Payment::truncate();

                $this->info('  Deleting bookings...');
                Booking::truncate();

                $this->info('  Deleting booking history...');
                BookingHistory::truncate();

                // Reset auto-increment
                DB::statement('DELETE FROM sqlite_sequence WHERE name="bookings"');
                DB::statement('DELETE FROM sqlite_sequence WHERE name="payments"');
                DB::statement('DELETE FROM sqlite_sequence WHERE name="folios"');
                DB::statement('DELETE FROM sqlite_sequence WHERE name="folio_items"');
                DB::statement('DELETE FROM sqlite_sequence WHERE name="booking_history"');

                $this->newLine();
                $this->info('✅ Successfully cleared all booking records:');
                $this->info("   - Deleted $bookingCount bookings");
                $this->info("   - Deleted $paymentCount payments");
                $this->info("   - Deleted $folioItemCount folio items");
                $this->info("   - Deleted $folioCount folios");
                $this->info("   - Deleted $historyCount booking history records");
            });

            $this->newLine();
            $this->info('🎉 All booking records have been cleared!');

        } catch (\Exception $e) {
            $this->error('❌ Error clearing bookings: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }
}

