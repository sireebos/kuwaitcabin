<?php

namespace App\Repositories;

use App\Models\Order;

class OrderRepository extends BaseRepository
{
    public function getModel(): string
    {
        return Order::class;
    }

    public function getOrdersByProperty($propertyId, $status = null, $orderType = 'restaurant')
    {
        $query = $this->model->where('property_id', $propertyId)
            ->where('order_type', $orderType);
        if ($status) {
            $query->where('status', $status);
        }
        return $query->with(['items', 'table', 'booking'])->paginate(15);
    }

    public function getOrdersByPropertyAndDate($propertyId, $date, $status = null, $orderType = 'restaurant')
    {
        $query = $this->model->where('property_id', $propertyId)
            ->where('order_type', $orderType)
            ->whereDate('created_at', $date);
        if ($status) {
            $query->where('status', $status);
        }
        return $query->with(['items', 'table', 'booking'])->orderBy('created_at', 'desc')->paginate(15);
    }

    public function getOrdersByTable($tableId)
    {
        return $this->model
            ->where('restaurant_table_id', $tableId)
            ->where('status', '!=', 'closed')
            ->with('items')
            ->get();
    }

    public function getOrdersByBooking($bookingId)
    {
        return $this->model
            ->where('booking_id', $bookingId)
            ->with(['items', 'table'])
            ->get();
    }

    public function getPendingOrders($propertyId)
    {
        return $this->model
            ->where('property_id', $propertyId)
            ->where('status', 'pending')
            ->with(['items', 'table'])
            ->get();
    }

    public function getPreparingOrders($propertyId)
    {
        return $this->model
            ->where('property_id', $propertyId)
            ->where('status', 'preparing')
            ->with(['items', 'table'])
            ->get();
    }
}

