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
            <?php echo e(__('Daily Bookings Report')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <div class="max-w-7xl mx-auto">
        <!-- Filters -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <form method="GET" action="<?php echo e(route('reports.daily-bookings', $property)); ?>" class="grid grid-cols-1 md:grid-cols-6 gap-4">
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
                    <a href="<?php echo e(route('reports.daily-bookings-pdf', $property)); ?>?<?php echo e(request()->getQueryString()); ?>" class="flex-1 bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 text-center">
                        📄 PDF
                    </a>
                </div>
            </form>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-gray-600 text-sm">Total Bookings</p>
                <p class="text-3xl font-bold text-blue-600"><?php echo e($totalBookings); ?></p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-gray-600 text-sm">Total Revenue</p>
                <p class="text-3xl font-bold text-green-600">₦<?php echo e(number_format(abs($totalRevenue), 2)); ?></p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-gray-600 text-sm">Average Booking</p>
                <p class="text-3xl font-bold text-purple-600">₦<?php echo e(number_format(abs($totalBookings > 0 ? $totalRevenue / $totalBookings : 0), 2)); ?></p>
            </div>
        </div>

        <!-- Bookings by User -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h3 class="text-lg font-bold mb-4">Bookings by User</h3>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-100 border-b">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">User</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Bookings</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Total Revenue</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Average</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $bookingsByUser; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $booking): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm"><?php echo e($booking->name); ?></td>
                                <td class="px-6 py-4 text-sm"><?php echo e($booking->booking_count); ?></td>
                                <td class="px-6 py-4 text-sm font-semibold">₦<?php echo e(number_format(abs($booking->total_revenue), 2)); ?></td>
                                <td class="px-6 py-4 text-sm">₦<?php echo e(number_format(abs($booking->total_revenue / $booking->booking_count), 2)); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-gray-500">No booking data</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Detailed Bookings -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold mb-4">Detailed Bookings</h3>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-100 border-b">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Date</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">User</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Guest</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Room</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Check-in</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Check-out</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Amount</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $bookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $booking): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm"><?php echo e($booking->created_at->format('M d, Y')); ?></td>
                                <td class="px-6 py-4 text-sm"><?php echo e($booking->user?->name ?? 'N/A'); ?></td>
                                <td class="px-6 py-4 text-sm"><?php echo e($booking->guest?->full_name ?? 'N/A'); ?></td>
                                <td class="px-6 py-4 text-sm"><?php echo e($booking->room?->room_number ?? 'N/A'); ?></td>
                                <td class="px-6 py-4 text-sm"><?php echo e($booking->check_in_date); ?></td>
                                <td class="px-6 py-4 text-sm"><?php echo e($booking->check_out_date); ?></td>
                                <td class="px-6 py-4 text-sm font-semibold">₦<?php echo e(number_format(abs($booking->total_amount), 2)); ?></td>
                                <td class="px-6 py-4 text-sm">
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold
                                        <?php if($booking->status === 'confirmed'): ?> bg-green-100 text-green-800
                                        <?php elseif($booking->status === 'pending'): ?> bg-yellow-100 text-yellow-800
                                        <?php elseif($booking->status === 'checked_in'): ?> bg-blue-100 text-blue-800
                                        <?php elseif($booking->status === 'checked_out'): ?> bg-gray-100 text-gray-800
                                        <?php else: ?> bg-red-100 text-red-800
                                        <?php endif; ?>">
                                        <?php echo e(ucfirst(str_replace('_', ' ', $booking->status))); ?>

                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="8" class="px-6 py-4 text-center text-gray-500">No bookings found</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                <?php echo e($bookings->links()); ?>

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

<?php /**PATH /home/kuwaitcabinhotel/sales-portal.kuwaitcabinhotel.com/resources/views/reports/bookings.blade.php ENDPATH**/ ?>