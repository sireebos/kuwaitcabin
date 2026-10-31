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
            <?php echo e(__('Room Type Details')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

<div class="max-w-6xl mx-auto">
    <!-- Header -->
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold"><?php echo e($roomType->name); ?></h1>
            <p class="text-gray-600"><?php echo e($roomType->description); ?></p>
        </div>
        <div class="flex gap-2">
            <a href="<?php echo e(route('properties.room-types.edit', [$property, $roomType])); ?>" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Edit
            </a>
            <a href="<?php echo e(route('properties.room-types.index', $property)); ?>" class="bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400">
                Back
            </a>
        </div>
    </div>

    <!-- Status Badge -->
    <div class="mb-6">
        <span class="px-4 py-2 rounded-full text-sm font-semibold <?php echo e($roomType->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'); ?>">
            <?php echo e($roomType->is_active ? 'Active' : 'Inactive'); ?>

        </span>
    </div>

    <!-- Statistics -->
    <div class="grid grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-gray-600 text-sm font-medium">Capacity</div>
            <div class="text-3xl font-bold text-blue-600"><?php echo e($roomType->capacity); ?></div>
            <div class="text-xs text-gray-500">guests</div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-gray-600 text-sm font-medium">Beds</div>
            <div class="text-3xl font-bold text-green-600"><?php echo e($roomType->beds); ?></div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-gray-600 text-sm font-medium">Base Price</div>
            <div class="text-3xl font-bold text-purple-600">₦<?php echo e(number_format($roomType->base_price, 2)); ?></div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-gray-600 text-sm font-medium">Total Rooms</div>
            <div class="text-3xl font-bold text-orange-600"><?php echo e($roomType->total_rooms); ?></div>
        </div>
    </div>

    <!-- Room Type Information -->
    <div class="grid grid-cols-2 gap-6 mb-6">
        <!-- Basic Information -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4">Basic Information</h3>
            <div class="space-y-3">
                <div>
                    <span class="text-gray-600 text-sm">Room Type Name</span>
                    <p class="font-medium"><?php echo e($roomType->name); ?></p>
                </div>
                <div>
                    <span class="text-gray-600 text-sm">Slug</span>
                    <p class="font-medium"><?php echo e($roomType->slug); ?></p>
                </div>
                <div>
                    <span class="text-gray-600 text-sm">Property</span>
                    <p class="font-medium">
                        <a href="<?php echo e(route('properties.show', $property)); ?>" class="text-blue-600 hover:underline"><?php echo e($property->name); ?></a>
                    </p>
                </div>
            </div>
        </div>

        <!-- Pricing Information -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4">Pricing Information</h3>
            <div class="space-y-3">
                <div>
                    <span class="text-gray-600 text-sm">Base Price</span>
                    <p class="font-medium"><?php echo e($property->currency); ?> <?php echo e(number_format($roomType->base_price, 2)); ?></p>
                </div>
                <div>
                    <span class="text-gray-600 text-sm">Total Rooms</span>
                    <p class="font-medium"><?php echo e($roomType->total_rooms); ?></p>
                </div>
                <div>
                    <span class="text-gray-600 text-sm">Rate Plans</span>
                    <p class="font-medium"><?php echo e($roomType->ratePlans->count()); ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Room Details -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h3 class="text-lg font-semibold mb-4">Room Details</h3>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <span class="text-gray-600 text-sm">Capacity</span>
                <p class="font-medium"><?php echo e($roomType->capacity); ?> guests</p>
            </div>
            <div>
                <span class="text-gray-600 text-sm">Number of Beds</span>
                <p class="font-medium"><?php echo e($roomType->beds); ?></p>
            </div>
        </div>
    </div>

    <!-- Amenities -->
    <?php if($roomType->amenities && count($roomType->amenities) > 0): ?>
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h3 class="text-lg font-semibold mb-4">Amenities</h3>
            <div class="grid grid-cols-3 gap-3">
                <?php $__currentLoopData = $roomType->amenities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $amenity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="flex items-center">
                        <span class="text-green-600 mr-2">✓</span>
                        <span><?php echo e($amenity); ?></span>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    <?php endif; ?>

    <!-- Timestamps -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold mb-4">Timestamps</h3>
        <div class="grid grid-cols-2 gap-4 text-sm text-gray-600">
            <div>
                <span>Created:</span>
                <p><?php echo e($roomType->created_at->format('M d, Y H:i')); ?></p>
            </div>
            <div>
                <span>Last Updated:</span>
                <p><?php echo e($roomType->updated_at->format('M d, Y H:i')); ?></p>
            </div>
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

<?php /**PATH /home/kuwaitcabinhotel/sales-portal.kuwaitcabinhotel.com/resources/views/room-types/show.blade.php ENDPATH**/ ?>