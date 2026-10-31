<?php

namespace App\Listeners;

use App\Events\OrderStatusUpdated;
use App\Models\AuditLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogOrderStatusChange implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(OrderStatusUpdated $event): void
    {
        AuditLog::create([
            'user_id' => auth()->id(),
            'property_id' => $event->order->property_id,
            'action' => 'order_status_updated',
            'model_type' => 'Order',
            'model_id' => $event->order->id,
            'changes' => [
                'status' => [
                    'from' => $event->previousStatus,
                    'to' => $event->order->status,
                ],
            ],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        \Log::info('Order status changed', [
            'order_id' => $event->order->id,
            'from' => $event->previousStatus,
            'to' => $event->order->status,
        ]);
    }
}

