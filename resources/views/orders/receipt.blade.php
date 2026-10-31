<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Order Receipt #{{ $order->order_number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 400px;
            margin: 0 auto;
            padding: 20px;
            background: white;
        }
        .receipt {
            border: 1px solid #ddd;
            padding: 20px;
            text-align: center;
        }
        .header {
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .header p {
            margin: 5px 0;
            font-size: 12px;
        }
        .order-info {
            text-align: left;
            margin: 15px 0;
            font-size: 12px;
        }
        .order-info p {
            margin: 3px 0;
        }
        .items {
            margin: 15px 0;
            border-top: 1px solid #ddd;
            border-bottom: 1px solid #ddd;
            padding: 10px 0;
            text-align: left;
        }
        .item {
            display: flex;
            justify-content: space-between;
            font-size: 12px;
            margin: 5px 0;
        }
        .item-name {
            flex: 1;
        }
        .item-qty {
            width: 40px;
            text-align: center;
        }
        .item-price {
            width: 60px;
            text-align: right;
        }
        .totals {
            margin: 15px 0;
            text-align: right;
            font-size: 12px;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            margin: 5px 0;
        }
        .total-amount {
            font-weight: bold;
            font-size: 16px;
            border-top: 2px solid #000;
            padding-top: 5px;
        }
        .footer {
            margin-top: 20px;
            font-size: 11px;
            text-align: center;
            color: #666;
        }
        .status {
            margin: 10px 0;
            padding: 5px;
            background: #f0f0f0;
            border-radius: 3px;
            font-size: 12px;
        }
        @media print {
            body {
                margin: 0;
                padding: 0;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="receipt">
        <div class="header">
            <h1>{{ $property->display_name ?? $property->name }}</h1>
            <p>{{ $property->address }}</p>
            <p>{{ $property->phone }}</p>
        </div>

        <div class="order-info">
            <p><strong>Order #:</strong> {{ $order->order_number }}</p>
            <p><strong>Date:</strong> {{ $order->created_at->format('M d, Y H:i') }}</p>
            <p><strong>Type:</strong> {{ ucfirst(str_replace('_', ' ', $order->type)) }}</p>
            @if ($order->table)
                <p><strong>Table:</strong> {{ $order->table->table_number }}</p>
            @endif
            <p><strong>Status:</strong> {{ ucfirst($order->status) }}</p>
        </div>

        <div class="items">
            @foreach ($order->items as $item)
                <div class="item">
                    <div class="item-name">
                        <strong>{{ $item->menuItem->name }}</strong>
                        @if ($item->modifiers)
                            <div style="font-size: 10px; color: #666;">
                                @foreach ($item->modifiers as $modifier)
                                    <div>+ {{ $modifier }}</div>
                                @endforeach
                            </div>
                        @endif
                        @if ($item->special_instructions)
                            <div style="font-size: 10px; color: #666;">Note: {{ $item->special_instructions }}</div>
                        @endif
                    </div>
                    <div class="item-qty">{{ $item->quantity }}</div>
                    <div class="item-price">₦{{ number_format($item->getLineTotal(), 2) }}</div>
                </div>
            @endforeach
        </div>

        <div class="totals">
            <div class="total-row">
                <span>Subtotal:</span>
                <span>₦{{ number_format($order->subtotal, 2) }}</span>
            </div>
            @if ($order->discount > 0)
                <div class="total-row">
                    <span>Discount:</span>
                    <span>-₦{{ number_format($order->discount, 2) }}</span>
                </div>
            @endif
            <div class="total-row">
                <span>Tax (10%):</span>
                <span>₦{{ number_format($order->tax, 2) }}</span>
            </div>
            <div class="total-row total-amount">
                <span>TOTAL:</span>
                <span>₦{{ number_format($order->total, 2) }}</span>
            </div>
        </div>

        <div class="footer">
            <p>Thank you for your order!</p>
            <p>{{ now()->format('M d, Y H:i:s') }}</p>
        </div>
    </div>

    <div class="no-print" style="text-align: center; margin-top: 20px;">
        <button onclick="window.print()" style="padding: 10px 20px; background: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer;">
            Print Receipt
        </button>
        <button onclick="window.close()" style="padding: 10px 20px; background: #6c757d; color: white; border: none; border-radius: 5px; cursor: pointer; margin-left: 10px;">
            Close
        </button>
    </div>
</body>
</html>

