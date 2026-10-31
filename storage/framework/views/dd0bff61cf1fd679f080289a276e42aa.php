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
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <?php echo e(__('Daily Sales Report')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <div class="max-w-7xl mx-auto">
        <!-- Filters -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <form method="GET" action="<?php echo e(route('reports.daily-sales', $property)); ?>" class="grid grid-cols-1 md:grid-cols-6 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Start Date</label>
                    <input type="date" name="start_date" value="<?php echo e($startDate->format('Y-m-d')); ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">End Date</label>
                    <input type="date" name="end_date" value="<?php echo e($endDate->format('Y-m-d')); ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Sort By</label>
                    <select name="sort_by" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        <option value="date" <?php if($sortBy === 'date'): echo 'selected'; endif; ?>>Date</option>
                        <option value="amount" <?php if($sortBy === 'amount'): echo 'selected'; endif; ?>>Amount</option>
                        <option value="user" <?php if($sortBy === 'user'): echo 'selected'; endif; ?>>User</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Order</label>
                    <select name="sort_order" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        <option value="desc" <?php if($sortOrder === 'desc'): echo 'selected'; endif; ?>>Descending</option>
                        <option value="asc" <?php if($sortOrder === 'asc'): echo 'selected'; endif; ?>>Ascending</option>
                    </select>
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit" class="flex-1 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                        Filter
                    </button>
                    <a href="<?php echo e(route('reports.daily-sales-pdf', $property)); ?>?<?php echo e(request()->getQueryString()); ?>" class="flex-1 bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 text-center">
                        📄 PDF
                    </a>
                </div>
            </form>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-gray-600 text-sm">Total Sales</p>
                <p class="text-3xl font-bold text-blue-600">₦<?php echo e(number_format(abs($totalSales), 2)); ?></p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-gray-600 text-sm">Total Transactions</p>
                <p class="text-3xl font-bold text-green-600"><?php echo e($payments->total() + $orders->count()); ?></p>
            </div>
        </div>

        <!-- Sales by User -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h3 class="text-lg font-bold mb-4">Sales by User</h3>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-100 border-b">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">User</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Transactions</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Total Sales</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Average</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            // Combine booking payments and restaurant orders by user
                            $userSales = [];

                            // Add booking payments
                            foreach($salesByUser as $sale) {
                                if (!isset($userSales[$sale->name])) {
                                    $userSales[$sale->name] = [
                                        'transactions' => 0,
                                        'total' => 0
                                    ];
                                }
                                $userSales[$sale->name]['transactions'] += $sale->transaction_count;
                                $userSales[$sale->name]['total'] += $sale->total;
                            }

                            // Add restaurant orders
                            foreach($orders as $order) {
                                $userName = $order->user?->name ?? 'N/A';
                                if (!isset($userSales[$userName])) {
                                    $userSales[$userName] = [
                                        'transactions' => 0,
                                        'total' => 0
                                    ];
                                }
                                $userSales[$userName]['transactions'] += 1;
                                $userSales[$userName]['total'] += $order->total;
                            }

                            // Sort by total descending
                            uasort($userSales, function($a, $b) {
                                return $b['total'] <=> $a['total'];
                            });
                        ?>

                        <?php $__empty_1 = true; $__currentLoopData = $userSales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $userName => $sale): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm"><?php echo e($userName); ?></td>
                                <td class="px-6 py-4 text-sm"><?php echo e($sale['transactions']); ?></td>
                                <td class="px-6 py-4 text-sm font-semibold">₦<?php echo e(number_format(abs($sale['total']), 2)); ?></td>
                                <td class="px-6 py-4 text-sm">₦<?php echo e(number_format(abs($sale['total'] / $sale['transactions']), 2)); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-gray-500">No sales data</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Detailed Transactions -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold mb-4">Detailed Transactions</h3>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-100 border-b">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Date</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Type</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">User</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Guest</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Amount</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Method</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm"><?php echo e($payment->created_at->format('M d, Y H:i')); ?></td>
                                <td class="px-6 py-4 text-sm"><span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs font-semibold">Booking</span></td>
                                <td class="px-6 py-4 text-sm"><?php echo e($payment->booking?->user?->name ?? 'N/A'); ?></td>
                                <td class="px-6 py-4 text-sm"><?php echo e($payment->booking?->guest?->full_name ?? 'N/A'); ?></td>
                                <td class="px-6 py-4 text-sm font-semibold">₦<?php echo e(number_format(abs($payment->amount), 2)); ?></td>
                                <td class="px-6 py-4 text-sm"><?php echo e(ucfirst(str_replace('_', ' ', $payment->payment_method))); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <?php if($orders->isEmpty()): ?>
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">No transactions found</td>
                                </tr>
                            <?php endif; ?>
                        <?php endif; ?>

                        <?php $__empty_1 = true; $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm"><?php echo e($order->paid_at->format('M d, Y H:i')); ?></td>
                                <td class="px-6 py-4 text-sm"><span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs font-semibold">Restaurant</span></td>
                                <td class="px-6 py-4 text-sm"><?php echo e($order->user?->name ?? 'N/A'); ?></td>
                                <td class="px-6 py-4 text-sm">Order #<?php echo e($order->order_number); ?></td>
                                <td class="px-6 py-4 text-sm font-semibold">₦<?php echo e(number_format($order->total, 2)); ?></td>
                                <td class="px-6 py-4 text-sm"><?php echo e(ucfirst(str_replace('_', ' ', $order->payment_method))); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                <?php echo e($payments->links()); ?>

            </div>
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

<?php /**PATH /home/kuwaitcabinhotel/sales-portal.kuwaitcabinhotel.com/resources/views/reports/sales.blade.php ENDPATH**/ ?>