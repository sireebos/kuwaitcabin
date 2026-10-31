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
            <?php echo e(__('Guest Check-out')); ?>

        </h2>
     <?php $__env->endSlot(); ?>
<div class="mb-6">
    <h1 class="text-2xl font-bold">Guest Check-out Management</h1>
    <p class="text-gray-600 mt-2">Manage guest check-outs and view checked-out guests</p>
</div>

<!-- Filter Tabs -->
<div class="mb-6 flex gap-4">
    <a href="<?php echo e(route('checkout.index', $property)); ?>" class="px-4 py-2 rounded-lg font-semibold <?php if(request('status') !== 'checked_out'): ?> bg-blue-600 text-white <?php else: ?> bg-gray-200 text-gray-700 <?php endif; ?>">
        Checked-in Guests
    </a>
    <a href="<?php echo e(route('checkout.index', ['property' => $property, 'status' => 'checked_out'])); ?>" class="px-4 py-2 rounded-lg font-semibold <?php if(request('status') === 'checked_out'): ?> bg-blue-600 text-white <?php else: ?> bg-gray-200 text-gray-700 <?php endif; ?>">
        Checked-out Guests
    </a>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-100 border-b">
            <tr>
                <th class="px-6 py-3 text-left text-sm font-semibold">Guest</th>
                <th class="px-6 py-3 text-left text-sm font-semibold">Room</th>
                <th class="px-6 py-3 text-left text-sm font-semibold">Check-in</th>
                <th class="px-6 py-3 text-left text-sm font-semibold">Check-out</th>
                <th class="px-6 py-3 text-left text-sm font-semibold">Status</th>
                <th class="px-6 py-3 text-left text-sm font-semibold">Balance</th>
                <th class="px-6 py-3 text-left text-sm font-semibold">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $occupiedRooms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $booking): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm"><?php echo e($booking->guest->full_name); ?></td>
                    <td class="px-6 py-4 text-sm"><?php echo e($booking->room->room_number); ?></td>
                    <td class="px-6 py-4 text-sm"><?php echo e($booking->check_in_date); ?></td>
                    <td class="px-6 py-4 text-sm"><?php echo e($booking->check_out_date); ?></td>
                    <td class="px-6 py-4 text-sm">
                        <?php if($booking->status === 'checked_in'): ?>
                            <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold">✓ Checked-in</span>
                        <?php elseif($booking->status === 'checked_out'): ?>
                            <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-semibold">✓ Checked-out</span>
                        <?php else: ?>
                            <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-semibold"><?php echo e(ucfirst($booking->status)); ?></span>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4 text-sm">
                        <span class="<?php if(($booking->folio->balance ?? 0) > 0): ?> text-red-600 <?php else: ?> text-green-600 <?php endif; ?> font-semibold">
                            ₦<?php echo e(number_format($booking->folio->balance ?? 0, 2)); ?>

                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm">
                        <a href="<?php echo e(route('checkout.show', [$property, $booking])); ?>" class="text-blue-600 hover:text-blue-800">View</a>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                        <?php if(request('status') === 'checked_out'): ?>
                            No checked-out guests
                        <?php else: ?>
                            No occupied rooms
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Pagination -->
<div class="mt-6">
    <?php echo e($occupiedRooms->links()); ?>

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

<?php /**PATH /home/kuwaitcabinhotel/sales-portal.kuwaitcabinhotel.com/resources/views/checkout/index.blade.php ENDPATH**/ ?>