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
            <?php echo e(__('Checkout')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="grid grid-cols-2 gap-6 mb-6">
            <div>
                <h3 class="text-gray-600 text-sm font-semibold mb-2">Guest Name</h3>
                <p class="text-2xl font-bold"><?php echo e($booking->guest->full_name); ?></p>
            </div>
            <div>
                <h3 class="text-gray-600 text-sm font-semibold mb-2">Room</h3>
                <p class="text-2xl font-bold"><?php echo e($booking->room->room_number); ?></p>
            </div>
            <div>
                <h3 class="text-gray-600 text-sm font-semibold mb-2">Check-in Date</h3>
                <p><?php echo e($booking->check_in_date); ?></p>
            </div>
            <div>
                <h3 class="text-gray-600 text-sm font-semibold mb-2">Check-out Date</h3>
                <p><?php echo e($booking->check_out_date); ?></p>
            </div>
        </div>

        <hr class="my-6">

        <h3 class="text-lg font-bold mb-4">Folio Summary</h3>
        <div class="overflow-x-auto mb-6">
            <table class="w-full">
                <thead class="bg-gray-100 border-b">
                    <tr>
                        <th class="px-4 py-2 text-left text-sm font-semibold">Description</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $booking->folio->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="border-b">
                            <td class="px-4 py-2 text-sm"><?php echo e($item->description); ?></td>
                            <td class="px-4 py-2 text-sm text-right">₦<?php echo e(number_format($item->amount, 2)); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="2" class="px-4 py-2 text-center text-gray-500">No charges</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <hr class="my-6">

        <!-- Payment Details -->
        <h3 class="text-lg font-bold mb-4">Payment Details</h3>
        <div class="overflow-x-auto mb-6">
            <table class="w-full text-sm">
                <thead class="bg-gray-100 border-b">
                    <tr>
                        <th class="px-4 py-2 text-left">Payment Method</th>
                        <th class="px-4 py-2 text-left">Amount</th>
                        <th class="px-4 py-2 text-left">Currency</th>
                        <th class="px-4 py-2 text-left">Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $booking->payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="border-b">
                            <td class="px-4 py-2"><?php echo e(ucfirst(str_replace('_', ' ', $payment->payment_method))); ?></td>
                            <td class="px-4 py-2 font-semibold"><?php echo e(number_format($payment->amount, 2)); ?></td>
                            <td class="px-4 py-2"><?php echo e($payment->currency ?? '₦'); ?></td>
                            <td class="px-4 py-2"><?php echo e($payment->created_at->format('M d, Y H:i')); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="4" class="px-4 py-2 text-center text-gray-500">No payments recorded</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <hr class="my-6">

        <div class="flex justify-end mb-6">
            <div class="w-64">
                <div class="flex justify-between mb-2">
                    <span>Subtotal:</span>
                    <span><?php echo e($property->currency ?? '₦'); ?><?php echo e(number_format($booking->folio->items->sum('amount'), 2)); ?></span>
                </div>
                <div class="flex justify-between mb-2">
                    <span>Payments:</span>
                    <span class="text-green-600">-<?php echo e($property->currency ?? '₦'); ?><?php echo e(number_format($booking->payments->sum('amount'), 2)); ?></span>
                </div>
                <div class="flex justify-between text-lg font-bold border-t pt-2">
                    <span>Balance Due:</span>
                    <span class="<?php if(($booking->folio->balance ?? 0) > 0): ?> text-red-600 <?php else: ?> text-green-600 <?php endif; ?>">
                        <?php echo e($property->currency ?? '₦'); ?><?php echo e(number_format($booking->folio->balance ?? 0, 2)); ?>

                    </span>
                </div>
            </div>
        </div>

        <div class="flex gap-2">
            <form method="POST" action="<?php echo e(route('checkout.checkout', [$property, $booking])); ?>" class="flex-1" id="checkoutForm">
                <?php echo csrf_field(); ?>
                <button type="submit" class="w-full bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 disabled:bg-gray-400 disabled:cursor-not-allowed transition-colors" id="checkoutBtn">
                    Complete Checkout
                </button>
            </form>
            <a href="<?php echo e(route('checkout.index', $property)); ?>" class="flex-1 bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400 text-center">
                Back
            </a>
        </div>

        <script>
            document.getElementById('checkoutForm').addEventListener('submit', function(e) {
                const btn = document.getElementById('checkoutBtn');
                btn.disabled = true;
                btn.textContent = 'Processing...';
            });
        </script>
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

<?php /**PATH /home/kuwaitcabinhotel/sales-portal.kuwaitcabinhotel.com/resources/views/checkout/show.blade.php ENDPATH**/ ?>