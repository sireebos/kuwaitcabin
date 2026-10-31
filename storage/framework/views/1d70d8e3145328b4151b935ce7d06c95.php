<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Bar Order Receipt #<?php echo e($order->order_number); ?></title>
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
            <h1><?php echo e($property->display_name ?? $property->name); ?></h1>
            <p><?php echo e($property->address); ?></p>
            <p><?php echo e($property->phone); ?></p>
            <p><?php echo e($property->email); ?></p>
        </div>

        <div class="order-info">
            <p><strong>Order #:</strong> <?php echo e($order->order_number); ?></p>
            <p><strong>Customer/Table:</strong> <?php echo e($order->customer_name ?? 'N/A'); ?></p>
            <p><strong>Order Type:</strong> <?php echo e(ucfirst(str_replace('_', ' ', $order->type))); ?></p>
            <p><strong>Date:</strong> <?php echo e($order->created_at?->format('M d, Y H:i') ?? 'N/A'); ?></p>
            <p><strong>Bartender:</strong> <?php echo e($order->user?->name ?? 'N/A'); ?></p>
        </div>

        <div class="items">
            <div class="item">
                <span class="item-name"><strong>Item</strong></span>
                <span class="item-qty"><strong>Qty</strong></span>
                <span class="item-price"><strong>Price</strong></span>
            </div>
            <?php $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="item">
                    <span class="item-name"><?php echo e($item->inventoryItem?->name ?? 'N/A'); ?></span>
                    <span class="item-qty"><?php echo e($item->quantity); ?></span>
                    <span class="item-price"><?php echo e(number_format($item->quantity * $item->unit_price, 2)); ?></span>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <div class="totals">
            <div class="total-row">
                <span>Subtotal:</span>
                <span><?php echo e(number_format($order->subtotal, 2)); ?></span>
            </div>
            <?php if($order->discount > 0): ?>
                <div class="total-row" style="color: red;">
                    <span>Discount:</span>
                    <span>-<?php echo e(number_format($order->discount, 2)); ?></span>
                </div>
            <?php endif; ?>
            <div class="total-row">
                <span>Tax (<?php echo e($property->tax_rate ?? 10); ?>%):</span>
                <span><?php echo e(number_format($order->tax, 2)); ?></span>
            </div>
            <div class="total-row total-amount">
                <span>TOTAL:</span>
                <span><?php echo e(number_format($order->total, 2)); ?></span>
            </div>
        </div>

        <?php if($order->status === 'closed'): ?>
            <div class="status">
                <strong>Status:</strong> PAID<br>
                Payment Method: 
                <?php if($order->payment_method === 'cash'): ?>
                    Cash
                <?php elseif($order->payment_method === 'card'): ?>
                    Card
                <?php elseif($order->payment_method === 'mobile_money'): ?>
                    Mobile Money
                <?php else: ?>
                    N/A
                <?php endif; ?>
                <br>
                Paid At: <?php echo e($order->paid_at?->format('M d, Y H:i') ?? 'N/A'); ?>

            </div>
        <?php else: ?>
            <div class="status">
                <strong>Status:</strong> <?php echo e(strtoupper($order->status)); ?>

            </div>
        <?php endif; ?>

        <div class="footer">
            <p>Thank you for your business!</p>
            <p><?php echo e(now()->format('M d, Y H:i:s')); ?></p>
        </div>
    </div>

    <div class="no-print" style="text-align: center; margin-top: 20px;">
        <button onclick="window.print()" style="padding: 10px 20px; font-size: 16px; cursor: pointer; background: #007bff; color: white; border: none; border-radius: 4px;">Print Receipt</button>
        <button onclick="window.history.back()" style="padding: 10px 20px; font-size: 16px; cursor: pointer; background: #6c757d; color: white; border: none; border-radius: 4px; margin-left: 10px;">Back</button>
    </div>
</body>
</html>
<?php /**PATH /home/kuwaitcabinhotel/sales-portal.kuwaitcabinhotel.com/resources/views/bar-orders/receipt.blade.php ENDPATH**/ ?>