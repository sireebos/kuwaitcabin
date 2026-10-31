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
            <?php echo e(__('Room Details')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="flex justify-between items-start mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Room <?php echo e($room->room_number); ?></h1>
                <p class="text-gray-600 mt-2"><?php echo e($room->roomType->name); ?></p>
            </div>
            <div class="flex gap-2">
                <a href="<?php echo e(route('properties.rooms.edit', [$property, $room])); ?>" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Edit
                </a>
                <form method="POST" action="<?php echo e(route('properties.rooms.destroy', [$property, $room])); ?>" style="display:inline;">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700" onclick="return confirm('Are you sure?')">
                        Delete
                    </button>
                </form>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-6 mb-6">
            <div>
                <h3 class="text-sm font-semibold text-gray-700 mb-2">Floor</h3>
                <p class="text-lg text-gray-900"><?php echo e($room->floor); ?></p>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-gray-700 mb-2">Wing</h3>
                <p class="text-lg text-gray-900"><?php echo e($room->wing ?? 'N/A'); ?></p>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-gray-700 mb-2">Status</h3>
                <span class="px-3 py-1 rounded-full text-sm font-semibold
                    <?php if($room->status === 'available'): ?> bg-green-100 text-green-800
                    <?php elseif($room->status === 'occupied'): ?> bg-blue-100 text-blue-800
                    <?php elseif($room->status === 'maintenance'): ?> bg-yellow-100 text-yellow-800
                    <?php else: ?> bg-red-100 text-red-800
                    <?php endif; ?>">
                    <?php echo e(ucfirst($room->status)); ?>

                </span>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-gray-700 mb-2">Room Type</h3>
                <p class="text-lg text-gray-900"><?php echo e($room->roomType->name); ?></p>
            </div>
        </div>

        <?php if($room->notes): ?>
            <div class="mb-6">
                <h3 class="text-sm font-semibold text-gray-700 mb-2">Notes</h3>
                <p class="text-gray-900"><?php echo e($room->notes); ?></p>
            </div>
        <?php endif; ?>
    </div>

    <?php if($room->bookings->count() > 0): ?>
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Recent Bookings</h2>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-100 border-b">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Guest</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Check-in</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Check-out</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $room->bookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $booking): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm text-gray-900"><?php echo e($booking->guest->full_name); ?></td>
                                <td class="px-6 py-4 text-sm text-gray-900"><?php echo e($booking->check_in_date->format('M d, Y')); ?></td>
                                <td class="px-6 py-4 text-sm text-gray-900"><?php echo e($booking->check_out_date->format('M d, Y')); ?></td>
                                <td class="px-6 py-4 text-sm">
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold
                                        <?php if($booking->status === 'confirmed'): ?> bg-green-100 text-green-800
                                        <?php elseif($booking->status === 'checked_in'): ?> bg-blue-100 text-blue-800
                                        <?php elseif($booking->status === 'checked_out'): ?> bg-gray-100 text-gray-800
                                        <?php else: ?> bg-yellow-100 text-yellow-800
                                        <?php endif; ?>">
                                        <?php echo e(ucfirst(str_replace('_', ' ', $booking->status))); ?>

                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>

    <div class="mt-6">
        <a href="<?php echo e(route('properties.rooms.index', $property)); ?>" class="text-blue-600 hover:text-blue-800">
            ← Back to Rooms
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

<?php /**PATH /home/kuwaitcabinhotel/sales-portal.kuwaitcabinhotel.com/resources/views/rooms/show.blade.php ENDPATH**/ ?>