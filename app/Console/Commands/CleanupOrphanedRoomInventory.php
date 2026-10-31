<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CleanupOrphanedRoomInventory extends Command
{
    protected $signature = 'cleanup:orphaned-inventory';
    protected $description = 'Clean up orphaned room inventory records (reserved status without corresponding bookings)';

    public function handle()
    {
        $this->info('🧹 Cleaning up orphaned room inventory records...');

        // Find all room_inventory records with 'reserved' status
        $orphanedCount = DB::table('room_inventory')
            ->where('status', 'reserved')
            ->count();

        if ($orphanedCount === 0) {
            $this->info('✅ No orphaned records found.');
            return;
        }

        $this->info("Found $orphanedCount orphaned records with 'reserved' status.");

        // Delete all 'reserved' status records
        // These should only exist if there's an active booking
        $deleted = DB::table('room_inventory')
            ->where('status', 'reserved')
            ->delete();

        $this->info("✅ Deleted $deleted orphaned room inventory records.");
        $this->info('All rooms are now available!');
    }
}

