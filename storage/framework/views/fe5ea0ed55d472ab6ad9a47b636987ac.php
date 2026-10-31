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
            <?php echo e(__('Home Page Setup')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <div class="max-w-4xl mx-auto bg-white rounded-lg shadow p-6">
        <?php if($errors->any()): ?>
            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                <strong>Error:</strong>
                <ul>
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if(session('success')): ?>
            <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>

        <form method="POST" action="<?php echo e(route('settings.updateSetup', $property)); ?>" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            <!-- Home Page Title -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Home Page Title</label>
                <input type="text" name="home_title" value="<?php echo e(old('home_title', $property->home_title)); ?>" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 <?php $__errorArgs = ['home_title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                       placeholder="Welcome to Our Hotel">
                <?php $__errorArgs = ['home_title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <!-- Home Page Description -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Home Page Description</label>
                <textarea name="home_description" rows="4" 
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 <?php $__errorArgs = ['home_description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                          placeholder="Describe your hotel and its features..."><?php echo e(old('home_description', $property->home_description)); ?></textarea>
                <?php $__errorArgs = ['home_description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <!-- Home Page Image -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Home Page Image</label>
                <?php if($property->home_image_path): ?>
                    <div class="mb-3">
                        <img src="<?php echo e(asset('storage/' . $property->home_image_path)); ?>" alt="Home Image" class="h-40 rounded-lg">
                    </div>
                <?php endif; ?>
                <input type="file" name="home_image" accept="image/*"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 <?php $__errorArgs = ['home_image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                <p class="text-gray-500 text-sm mt-1">Max size: 5MB (JPEG, PNG, GIF)</p>
                <?php $__errorArgs = ['home_image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <!-- Home Image Link -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Home Image Link (Optional)</label>
                <input type="url" name="home_image_link" value="<?php echo e(old('home_image_link', $property->home_image_link)); ?>" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 <?php $__errorArgs = ['home_image_link'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                       placeholder="https://example.com">
                <p class="text-gray-500 text-sm mt-1">URL to open when image is clicked</p>
                <?php $__errorArgs = ['home_image_link'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <!-- Features Enabled -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Enabled Features</label>
                <div class="space-y-2">
                    <label class="flex items-center">
                        <input type="checkbox" name="features_enabled[]" value="bookings" 
                               <?php if(in_array('bookings', json_decode($property->features_enabled ?? '[]', true))): ?> checked <?php endif; ?>
                               class="rounded border-gray-300">
                        <span class="ml-2">Show Bookings</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" name="features_enabled[]" value="rooms" 
                               <?php if(in_array('rooms', json_decode($property->features_enabled ?? '[]', true))): ?> checked <?php endif; ?>
                               class="rounded border-gray-300">
                        <span class="ml-2">Show Rooms</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" name="features_enabled[]" value="restaurant" 
                               <?php if(in_array('restaurant', json_decode($property->features_enabled ?? '[]', true))): ?> checked <?php endif; ?>
                               class="rounded border-gray-300">
                        <span class="ml-2">Show Restaurant</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" name="features_enabled[]" value="bar" 
                               <?php if(in_array('bar', json_decode($property->features_enabled ?? '[]', true))): ?> checked <?php endif; ?>
                               class="rounded border-gray-300">
                        <span class="ml-2">Show Bar</span>
                    </label>
                </div>
            </div>

            <div class="flex gap-4">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                    Save Configuration
                </button>
                <a href="<?php echo e(route('properties.show', $property)); ?>" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-400">
                    Cancel
                </a>
            </div>
        </form>
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

<?php /**PATH /home/kuwaitcabinhotel/sales-portal.kuwaitcabinhotel.com/resources/views/settings/setup.blade.php ENDPATH**/ ?>