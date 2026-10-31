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
            <?php echo e(__('Booking History')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

<div class="mb-6">
    <h1 class="text-2xl font-bold">Completed Bookings History</h1>
    <p class="text-gray-600 mt-2">View all checked-out guests and their booking details</p>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-100 border-b">
            <tr>
                <th class="px-6 py-3 text-left text-sm font-semibold">Guest Name</th>
                <th class="px-6 py-3 text-left text-sm font-semibold">Room</th>
                <th class="px-6 py-3 text-left text-sm font-semibold">Check-in</th>
                <th class="px-6 py-3 text-left text-sm font-semibold">Check-out</th>
                <th class="px-6 py-3 text-left text-sm font-semibold">Nights</th>
                <th class="px-6 py-3 text-left text-sm font-semibold">Final Amount</th>
                <th class="px-6 py-3 text-left text-sm font-semibold">Paid</th>
                <th class="px-6 py-3 text-left text-sm font-semibold">Balance</th>
                <th class="px-6 py-3 text-left text-sm font-semibold">Checked Out</th>
                <th class="px-6 py-3 text-left text-sm font-semibold">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $history; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $booking): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm font-semibold"><?php echo e($booking->guest->full_name); ?></td>
                    <td class="px-6 py-4 text-sm"><?php echo e($booking->room->room_number ?? 'N/A'); ?></td>
                    <td class="px-6 py-4 text-sm"><?php echo e($booking->check_in_date->format('M d, Y')); ?></td>
                    <td class="px-6 py-4 text-sm"><?php echo e($booking->check_out_date->format('M d, Y')); ?></td>
                    <td class="px-6 py-4 text-sm text-center"><?php echo e($booking->number_of_nights); ?></td>
                    <td class="px-6 py-4 text-sm font-semibold">
                        <?php echo e($booking->currency); ?><?php echo e(number_format($booking->final_amount ?? $booking->total_amount, 2)); ?>

                    </td>
                    <td class="px-6 py-4 text-sm text-green-600 font-semibold">
                        <?php echo e($booking->currency); ?><?php echo e(number_format($booking->payments_received, 2)); ?>

                    </td>
                    <td class="px-6 py-4 text-sm">
                        <span class="<?php if($booking->balance > 0): ?> text-red-600 <?php else: ?> text-green-600 <?php endif; ?> font-semibold">
                            <?php echo e($booking->currency); ?><?php echo e(number_format($booking->balance, 2)); ?>

                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm"><?php echo e($booking->checked_out_at->format('M d, Y H:i')); ?></td>
                    <td class="px-6 py-4 text-sm">
                        <a href="<?php echo e(route('booking-history.show', [$property, $booking])); ?>" class="text-blue-600 hover:text-blue-800 font-semibold">
                            View Details
                        </a>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="10" class="px-6 py-4 text-center text-gray-500">
                        No booking history available
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Pagination -->
<div class="mt-6">
    <?php echo e($history->links()); ?>

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

<?php /**PATH /home/kuwaitcabinhotel/sales-portal.kuwaitcabinhotel.com/resources/views/booking-history/index.blade.php ENDPATH**/ ?>