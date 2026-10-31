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
            <?php echo e(__('Guest Management')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

<div class="mb-6 flex justify-between items-center">
    <h1 class="text-2xl font-bold">Guests</h1>
    <a href="<?php echo e(route('properties.guests.create', $property)); ?>" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
        + Register Guest
    </a>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-100 border-b">
            <tr>
                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Name</th>
                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Email</th>
                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Phone</th>
                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Address</th>
                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Bookings</th>
                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $guests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $guest): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm text-gray-900"><?php echo e($guest->full_name); ?></td>
                    <td class="px-6 py-4 text-sm text-gray-900"><?php echo e($guest->email); ?></td>
                    <td class="px-6 py-4 text-sm text-gray-900"><?php echo e($guest->phone); ?></td>
                    <td class="px-6 py-4 text-sm text-gray-900"><?php echo e($guest->address ?? '-'); ?></td>
                    <td class="px-6 py-4 text-sm text-gray-900"><?php echo e($guest->bookings->count()); ?></td>
                    <td class="px-6 py-4 text-sm">
                        <a href="<?php echo e(route('properties.guests.show', [$property, $guest])); ?>" class="text-blue-600 hover:text-blue-800 mr-3">View</a>
                        <a href="<?php echo e(route('properties.guests.edit', [$property, $guest])); ?>" class="text-green-600 hover:text-green-800 mr-3">Edit</a>
                        <form method="POST" action="<?php echo e(route('properties.guests.destroy', [$property, $guest])); ?>" style="display:inline;">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="text-red-600 hover:text-red-800" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">No guests found</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<div class="mt-6">
    <?php echo e($guests->links()); ?>

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

<?php /**PATH /home/kuwaitcabinhotel/sales-portal.kuwaitcabinhotel.com/resources/views/guests/index.blade.php ENDPATH**/ ?>