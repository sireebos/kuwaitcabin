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
                <?php echo e(__('User Management')); ?>

            </h2>
            <a href="<?php echo e(route('properties.users.create', $property)); ?>" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                + Add User
            </a>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="max-w-7xl mx-auto">
        <?php if(session('success')): ?>
            <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-100 border-b">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Name</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Email</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Phone</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Role</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Status</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Bookings</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm"><?php echo e($user->name); ?></td>
                            <td class="px-6 py-4 text-sm"><?php echo e($user->email); ?></td>
                            <td class="px-6 py-4 text-sm"><?php echo e($user->phone ?? 'N/A'); ?></td>
                            <td class="px-6 py-4 text-sm">
                                <div class="flex flex-wrap gap-1">
                                    <?php $__empty_2 = true; $__currentLoopData = $user->roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                                        <span class="px-2 py-1 rounded-full text-xs font-semibold
                                            <?php if($role->name === 'admin'): ?> bg-red-100 text-red-800
                                            <?php elseif($role->name === 'manager'): ?> bg-blue-100 text-blue-800
                                            <?php elseif($role->name === 'receptionist'): ?> bg-green-100 text-green-800
                                            <?php elseif($role->name === 'bartender' || $role->name === 'bar_manager'): ?> bg-purple-100 text-purple-800
                                            <?php elseif($role->name === 'waiter' || $role->name === 'chef'): ?> bg-orange-100 text-orange-800
                                            <?php else: ?> bg-gray-100 text-gray-800
                                            <?php endif; ?>">
                                            <?php echo e($role->display_name); ?>

                                        </span>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                                        <span class="text-gray-400">No roles</span>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold
                                    <?php if($user->status === 'active'): ?> bg-green-100 text-green-800
                                    <?php else: ?> bg-red-100 text-red-800
                                    <?php endif; ?>">
                                    <?php echo e(ucfirst($user->status)); ?>

                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm"><?php echo e($user->bookings()->count()); ?></td>
                            <td class="px-6 py-4 text-sm space-x-2">
                                <a href="<?php echo e(route('properties.users.edit', [$property, $user])); ?>" class="text-blue-600 hover:text-blue-800">Edit</a>
                                <form method="POST" action="<?php echo e(route('properties.users.destroy', [$property, $user])); ?>" class="inline" onsubmit="return confirm('Are you sure?')">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="text-red-600 hover:text-red-800">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-gray-500">No users found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            <?php echo e($users->links()); ?>

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

<?php /**PATH /home/kuwaitcabinhotel/sales-portal.kuwaitcabinhotel.com/resources/views/users/index.blade.php ENDPATH**/ ?>