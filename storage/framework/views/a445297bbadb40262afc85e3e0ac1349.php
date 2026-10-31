<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
     <?php $__env->slot('header', null, []); ?> 
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Daily Summary - <?php echo e(\Carbon\Carbon::parse($date)->format('M d, Y')); ?>

            </h2>
            <div class="flex gap-2">
                <button onclick="window.print()" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 text-sm font-medium print:hidden">
                    🖨️ Print
                </button>
                <a href="<?php echo e(route('dashboard', $property)); ?>" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 text-sm font-medium print:hidden">
                    Back to Dashboard
                </a>
            </div>
        </div>
     <?php $__env->endSlot(); ?>

    <style media="print">
        @page {
            size: A4;
            margin: 0.5cm;
        }

        body {
            margin: 0;
            padding: 0;
        }

        .print\:hidden {
            display: none !important;
        }

        .bg-white {
            background-color: white !important;
            border: 1px solid #e5e7eb;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            border: 1px solid #d1d5db;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f3f4f6 !important;
            font-weight: bold;
        }

        .page-break {
            page-break-after: always;
        }
    </style>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Print Header -->
            <div class="hidden print:block mb-8 text-center border-b-2 pb-4">
                <h1 class="text-2xl font-bold"><?php echo e($property->display_name ?? $property->name); ?></h1>
                <p class="text-gray-600">Daily Summary Report</p>
                <p class="text-gray-600"><?php echo e(\Carbon\Carbon::parse($date)->format('l, F d, Y')); ?></p>
                <p class="text-sm text-gray-500 mt-2">Printed on: <?php echo e(now()->format('M d, Y H:i')); ?></p>
            </div>

            <!-- Filter Section -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-8 print:hidden">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Filter Daily Summary</h3>
                <form method="GET" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Date</label>
                            <input type="date" name="date" value="<?php echo e($date); ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Filter by User</label>
                            <?php if(auth()->user()->isAdmin()): ?>
                            <select name="user_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                <option value="">All Users</option>
                                <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($user->id); ?>" <?php echo e($filterUser == $user->id ? 'selected' : ''); ?>>
                                        <?php echo e($user->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php else: ?>
                            <select name="user_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" disabled>
                                <option value="<?php echo e(auth()->user()->id); ?>" selected>
                                    <?php echo e(auth()->user()->name); ?>

                                </option>
                            </select>
                            <input type="hidden" name="user_id" value="<?php echo e(auth()->user()->id); ?>">
                            <?php endif; ?>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Filter by Status</label>
                            <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                <option value="" <?php echo e(!$filterStatus ? 'selected' : ''); ?>>All Status</option>
                                <option value="pending" <?php echo e($filterStatus === 'pending' ? 'selected' : ''); ?>>Pending</option>
                                <option value="completed" <?php echo e($filterStatus === 'completed' ? 'selected' : ''); ?>>Completed</option>
                                <option value="failed" <?php echo e($filterStatus === 'failed' ? 'selected' : ''); ?>>Failed</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex gap-3">
                        <button type="submit" class="bg-blue-600 text-white px-8 py-2 rounded-lg hover:bg-blue-700 font-medium transition">
                            Apply Filters
                        </button>
                        <a href="<?php echo e(route('admin.daily-summary', $property)); ?>" class="bg-gray-400 text-white px-8 py-2 rounded-lg hover:bg-gray-500 font-medium transition">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-gray-600 text-sm font-semibold mb-2">Total Bookings</h3>
                    <p class="text-4xl font-bold text-blue-600">₦<?php echo e(number_format($totalPayments, 2)); ?></p>
                    <p class="text-gray-500 text-xs mt-2"><?php echo e($payments->count()); ?> transactions</p>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-gray-600 text-sm font-semibold mb-2">Total Restaurant Sales</h3>
                    <p class="text-4xl font-bold text-green-600">₦<?php echo e(number_format($totalOrders, 2)); ?></p>
                    <p class="text-gray-500 text-xs mt-2"><?php echo e($orders->count()); ?> orders</p>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-gray-600 text-sm font-semibold mb-2">Grand Total</h3>
                    <p class="text-4xl font-bold text-purple-600">₦<?php echo e(number_format($grandTotal, 2)); ?></p>
                    <p class="text-gray-500 text-xs mt-2">All transactions</p>
                </div>
            </div>

            <!-- Bookings Table -->
            <div class="bg-white rounded-lg shadow mb-8">
                <div class="p-6 border-b">
                    <h3 class="text-lg font-bold">Booking Payments (<?php echo e($payments->count()); ?>)</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-100 border-b">
                            <tr>
                                <th class="px-6 py-3 text-left text-sm font-semibold">Booking Ref</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold">Guest</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold">Amount</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold">Method</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold">Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-6 py-4 text-sm font-semibold"><?php echo e($payment->booking->booking_reference ?? 'N/A'); ?></td>
                                    <td class="px-6 py-4 text-sm"><?php echo e($payment->booking->guest->full_name ?? 'N/A'); ?></td>
                                    <td class="px-6 py-4 text-sm font-semibold text-green-600">₦<?php echo e(number_format($payment->amount, 2)); ?></td>
                                    <td class="px-6 py-4 text-sm"><?php echo e(ucfirst($payment->payment_method)); ?></td>
                                    <td class="px-6 py-4 text-sm"><?php echo e($payment->created_at->format('H:i')); ?></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">No booking payments today</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Orders Table -->
            <div class="bg-white rounded-lg shadow mb-8">
                <div class="p-6 border-b">
                    <h3 class="text-lg font-bold">Restaurant Orders (<?php echo e($orders->count()); ?>)</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-100 border-b">
                            <tr>
                                <th class="px-6 py-3 text-left text-sm font-semibold">Order #</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold">User</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold">Amount</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold">Method</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold">Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-6 py-4 text-sm font-semibold"><?php echo e($order->order_number); ?></td>
                                    <td class="px-6 py-4 text-sm"><?php echo e($order->user?->name ?? 'N/A'); ?></td>
                                    <td class="px-6 py-4 text-sm font-semibold text-green-600">₦<?php echo e(number_format($order->total, 2)); ?></td>
                                    <td class="px-6 py-4 text-sm"><?php echo e(ucfirst(str_replace('_', ' ', $order->payment_method))); ?></td>
                                    <td class="px-6 py-4 text-sm"><?php echo e($order->paid_at->format('H:i')); ?></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">No restaurant orders today</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Low Stock Items -->
            <?php if($lowStockItems->count() > 0): ?>
            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b">
                    <h3 class="text-lg font-bold text-red-600">⚠️ Low Stock Items (<?php echo e($lowStockItems->count()); ?>)</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-100 border-b">
                            <tr>
                                <th class="px-6 py-3 text-left text-sm font-semibold">Item Name</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold">Current Stock</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold">Reorder Level</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold">Unit</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $lowStockItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-6 py-4 text-sm font-semibold"><?php echo e($item->name); ?></td>
                                    <td class="px-6 py-4 text-sm"><?php echo e($item->quantity); ?></td>
                                    <td class="px-6 py-4 text-sm"><?php echo e($item->reorder_level); ?></td>
                                    <td class="px-6 py-4 text-sm"><?php echo e($item->unit); ?></td>
                                    <td class="px-6 py-4 text-sm">
                                        <span class="px-2 py-1 bg-red-100 text-red-800 rounded text-xs font-semibold">Low Stock</span>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>

<?php /**PATH /home/kuwaitcabinhotel/sales-portal.kuwaitcabinhotel.com/resources/views/admin/daily-summary.blade.php ENDPATH**/ ?>