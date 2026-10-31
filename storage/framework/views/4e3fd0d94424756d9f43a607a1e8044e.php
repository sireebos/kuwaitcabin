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
            <?php echo e(__('Check-in Guest')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-2xl font-bold mb-6">Check-in Guest</h2>

        <div class="mb-6 p-4 bg-blue-50 rounded-lg">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-gray-600 text-sm">Guest Name</p>
                    <p class="font-semibold"><?php echo e($booking->guest->full_name); ?></p>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Booking Reference</p>
                    <p class="font-semibold"><?php echo e($booking->booking_reference); ?></p>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Check-in Date</p>
                    <p class="font-semibold"><?php echo e($booking->check_in_date); ?></p>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Number of Guests</p>
                    <p class="font-semibold"><?php echo e($booking->number_of_guests); ?></p>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Booked Room</p>
                    <p class="font-semibold"><?php echo e($booking->room->room_number ?? 'N/A'); ?></p>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Room Rate</p>
                    <p class="font-semibold">₦<?php echo e(number_format($booking->room_rate ?? 0, 2)); ?></p>
                </div>
            </div>
        </div>

        <form method="POST" action="<?php echo e(route('checkin.store', [$property, $booking])); ?>">
            <?php echo csrf_field(); ?>

            <div class="mb-4">
                <label for="room_id" class="block text-sm font-semibold text-gray-700 mb-2">Select Room *</label>
                <select id="room_id" name="room_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    <option value="">Choose a room...</option>
                    <?php $__currentLoopData = $availableRooms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $room): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($room->id); ?>" <?php if($room->id === $bookedRoomId): ?> selected <?php endif; ?>>
                            <?php echo e($room->room_number); ?> - <?php echo e($room->room_type); ?> (<?php echo e($room->capacity); ?> guests)
                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <?php $__errorArgs = ['room_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-600 text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="flex-1 bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700">
                    Check-in Guest
                </button>
                <a href="<?php echo e(route('properties.bookings.show', [$property, $booking])); ?>" class="flex-1 bg-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-400 text-center">
                    Cancel
                </a>
            </div>
        </form>
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

<?php /**PATH /home/kuwaitcabinhotel/sales-portal.kuwaitcabinhotel.com/resources/views/checkin/create.blade.php ENDPATH**/ ?>