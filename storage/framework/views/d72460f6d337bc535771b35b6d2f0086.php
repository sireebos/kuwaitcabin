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
            <?php echo e(__('Guest Folio')); ?>

        </h2>
     <?php $__env->endSlot(); ?>
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Folio Details -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-2xl font-bold mb-4">Folio #<?php echo e($folio->id); ?></h2>
            <div class="grid grid-cols-2 gap-4 mb-6">
                <div>
                    <p class="text-gray-600 text-sm">Guest</p>
                    <p class="font-semibold"><?php echo e($booking->guest->full_name); ?></p>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Room</p>
                    <p class="font-semibold"><?php echo e($booking->room->room_number ?? 'N/A'); ?></p>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Check-in</p>
                    <p class="font-semibold"><?php echo e($booking->check_in_date); ?></p>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Check-out</p>
                    <p class="font-semibold"><?php echo e($booking->check_out_date); ?></p>
                </div>
            </div>

            <h3 class="text-lg font-bold mb-3">Charges</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 text-left">Description</th>
                            <th class="px-4 py-2 text-left">Type</th>
                            <th class="px-4 py-2 text-right">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $folio->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="border-b">
                                <td class="px-4 py-2"><?php echo e($item->description); ?></td>
                                <td class="px-4 py-2">
                                    <span class="px-2 py-1 rounded text-xs font-semibold bg-blue-100 text-blue-800">
                                        <?php echo e(ucfirst($item->type)); ?>

                                    </span>
                                </td>
                                <td class="px-4 py-2 text-right">₦<?php echo e(number_format(abs($item->amount), 2)); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="3" class="px-4 py-2 text-center text-gray-500">No charges</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Summary -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold mb-4">Summary</h3>
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span>Room Charges:</span>
                    <span>₦<?php echo e(number_format($folio->room_charges ?? 0, 2)); ?></span>
                </div>
                <div class="flex justify-between">
                    <span>Restaurant Charges:</span>
                    <span>₦<?php echo e(number_format($folio->restaurant_charges ?? 0, 2)); ?></span>
                </div>
                <div class="flex justify-between">
                    <span>Other Charges:</span>
                    <span>₦<?php echo e(number_format($folio->other_charges ?? 0, 2)); ?></span>
                </div>
                <div class="border-t pt-3 flex justify-between font-bold">
                    <span>Total Charges:</span>
                    <span>₦<?php echo e(number_format($folio->total_charges ?? 0, 2)); ?></span>
                </div>
                <div class="flex justify-between">
                    <span>Payments Received:</span>
                    <span>₦<?php echo e(number_format($folio->payments_received ?? 0, 2)); ?></span>
                </div>
                <div class="border-t pt-3 flex justify-between font-bold text-lg">
                    <span>Balance Due:</span>
                    <?php
                        $balance = ($folio->total_charges ?? 0) - ($folio->payments_received ?? 0);
                    ?>
                    <span class="<?php if($balance > 0): ?> text-red-600 <?php elseif($balance < 0): ?> text-blue-600 <?php else: ?> text-green-600 <?php endif; ?>">
                        ₦<?php echo e(number_format($balance, 2)); ?>

                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="bg-white rounded-lg shadow p-6 h-fit">
        <h2 class="text-lg font-bold mb-4">Actions</h2>

        <a href="<?php echo e(route('folio.print', [$property, $booking])); ?>" class="block w-full bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-center mb-2">
            Print Folio
        </a>

        <a href="<?php echo e(route('properties.bookings.show', [$property, $booking])); ?>" class="block w-full bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400 text-center">
            Back to Booking
        </a>
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

<?php /**PATH /home/kuwaitcabinhotel/sales-portal.kuwaitcabinhotel.com/resources/views/folio/show.blade.php ENDPATH**/ ?>