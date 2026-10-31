<?php

namespace App\Notifications;

use App\Models\InventoryItem;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LowStockAlert extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public InventoryItem $item)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Low Stock Alert: ' . $this->item->name)
            ->greeting('Low Stock Alert!')
            ->line('The inventory item "' . $this->item->name . '" is running low.')
            ->line('Current Quantity: ' . $this->item->quantity . ' ' . $this->item->unit)
            ->line('Reorder Level: ' . $this->item->reorder_level . ' ' . $this->item->unit)
            ->action('View Inventory', url('/properties/' . $this->item->property_id . '/inventory'))
            ->line('Please reorder this item as soon as possible.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'item_id' => $this->item->id,
            'item_name' => $this->item->name,
            'current_quantity' => $this->item->quantity,
            'reorder_level' => $this->item->reorder_level,
            'unit' => $this->item->unit,
            'type' => 'low_stock_alert',
        ];
    }
}
