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
            <?php echo e(__('Bar Orders')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Bar Orders</h1>
        <a href="<?php echo e(route('properties.bar-orders.pos', $property)); ?>" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            + New Bar Order
        </a>
    </div>

    <!-- Date Filter -->
    <div class="mb-6 bg-white rounded-lg shadow p-4">
        <form method="GET" action="<?php echo e(route('properties.bar-orders.index', $property)); ?>" class="space-y-4">
            <div class="flex items-center gap-4 flex-wrap">
                <div class="flex items-center gap-2">
                    <label for="date" class="text-sm font-medium text-gray-700">Single Date:</label>
                    <input type="date" id="date" name="date" value="<?php echo e(request('date', $date)); ?>" class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="text-gray-500">OR</div>

                <div class="flex items-center gap-2">
                    <label for="start_date" class="text-sm font-medium text-gray-700">Date Range:</label>
                    <input type="date" id="start_date" name="start_date" value="<?php echo e(request('start_date')); ?>" class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="From">
                    <span class="text-gray-500">to</span>
                    <input type="date" id="end_date" name="end_date" value="<?php echo e(request('end_date')); ?>" class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="To">
                </div>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm font-medium">
                    Filter
                </button>
                <a href="<?php echo e(route('properties.bar-orders.index', $property)); ?>" class="bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400 text-sm font-medium">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <?php if($orders->count() > 0): ?>
        <!-- Bulk Delete Section (Admin Only) -->
        <?php if(auth()->user()->isAdmin()): ?>
            <div id="bulkDeleteSection" class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg hidden">
                <div class="flex justify-between items-center">
                    <span class="text-sm font-semibold text-gray-700">
                        <span id="selectedCount">0</span> order(s) selected
                    </span>
                    <button onclick="bulkDelete()" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                        Delete Selected
                    </button>
                </div>
            </div>
        <?php endif; ?>

        <div class="overflow-x-auto bg-white rounded-lg shadow">
            <table class="w-full">
                <thead class="bg-gray-100 border-b">
                    <tr>
                        <?php if(auth()->user()->isAdmin()): ?>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">
                                <input type="checkbox" id="selectAll" onchange="toggleSelectAll()" class="rounded">
                            </th>
                        <?php endif; ?>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Order ID</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Customer</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Type</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Items</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Total</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Status</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Date</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Bartender</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr class="border-b hover:bg-gray-50 order-row">
                            <?php if(auth()->user()->isAdmin()): ?>
                                <td class="px-6 py-4 text-sm">
                                    <input type="checkbox" class="order-checkbox rounded" value="<?php echo e($order->id); ?>" onchange="updateBulkDeleteUI()">
                                </td>
                            <?php endif; ?>
                            <td class="px-6 py-4 text-sm font-semibold"><?php echo e($order->order_number); ?></td>
                            <td class="px-6 py-4 text-sm font-semibold"><?php echo e($order->customer_name ?? 'N/A'); ?></td>
                            <td class="px-6 py-4 text-sm">
                                <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded text-xs font-semibold">
                                    <?php echo e(ucfirst(str_replace('_', ' ', $order->type))); ?>

                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm"><?php echo e($order->items->count()); ?></td>
                            <td class="px-6 py-4 text-sm font-semibold">₦<?php echo e(number_format($order->total, 2)); ?></td>
                            <td class="px-6 py-4 text-sm">
                                <span class="px-2 py-1 rounded text-xs font-semibold
                                    <?php if($order->status === 'pending'): ?> bg-yellow-100 text-yellow-800
                                    <?php elseif($order->status === 'preparing'): ?> bg-blue-100 text-blue-800
                                    <?php elseif($order->status === 'ready'): ?> bg-green-100 text-green-800
                                    <?php elseif($order->status === 'served'): ?> bg-purple-100 text-purple-800
                                    <?php else: ?> bg-gray-100 text-gray-800
                                    <?php endif; ?>
                                ">
                                    <?php echo e(ucfirst($order->status)); ?>

                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm"><?php echo e($order->created_at->format('M d, Y H:i')); ?></td>
                            <td class="px-6 py-4 text-sm"><?php echo e($order->user?->name ?? 'N/A'); ?></td>
                            <td class="px-6 py-4 text-sm">
                                <div class="flex gap-2">
                                    <a href="<?php echo e(route('properties.bar-orders.show', [$property, $order])); ?>" class="px-3 py-1 bg-blue-100 text-blue-700 rounded hover:bg-blue-200 font-semibold text-xs">
                                        View
                                    </a>
                                    <?php if($order->status !== 'closed'): ?>
                                        <a href="<?php echo e(route('properties.bar-orders.continue', [$property, $order])); ?>" class="px-3 py-1 bg-green-100 text-green-700 rounded hover:bg-green-200 font-semibold text-xs">
                                            Reopen
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            <?php echo e($orders->links()); ?>

        </div>
    <?php else: ?>
        <div class="bg-gray-100 rounded-lg p-8 text-center">
            <p class="text-gray-600">No bar orders found.</p>
            <a href="<?php echo e(route('properties.bar-orders.pos', $property)); ?>" class="mt-4 inline-block bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Create First Order
            </a>
        </div>
    <?php endif; ?>
        </div>
        </div>
    </div>

    <?php if(auth()->user()->isAdmin()): ?>
    <script>
        const propertyId = <?php echo e($property->id); ?>;

        function toggleSelectAll() {
            const selectAll = document.getElementById('selectAll');
            const checkboxes = document.querySelectorAll('.order-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = selectAll.checked;
            });
            updateBulkDeleteUI();
        }

        function updateBulkDeleteUI() {
            const checkboxes = document.querySelectorAll('.order-checkbox');
            const selectedCount = Array.from(checkboxes).filter(cb => cb.checked).length;
            const bulkDeleteSection = document.getElementById('bulkDeleteSection');
            const selectedCountSpan = document.getElementById('selectedCount');

            selectedCountSpan.textContent = selectedCount;

            if (selectedCount > 0) {
                bulkDeleteSection.classList.remove('hidden');
            } else {
                bulkDeleteSection.classList.add('hidden');
            }

            // Update select all checkbox state
            const selectAll = document.getElementById('selectAll');
            selectAll.checked = selectedCount === checkboxes.length && checkboxes.length > 0;
        }

        function bulkDelete() {
            const checkboxes = document.querySelectorAll('.order-checkbox:checked');
            const selectedIds = Array.from(checkboxes).map(cb => cb.value);

            if (selectedIds.length === 0) {
                alert('Please select at least one order to delete');
                return;
            }

            if (!confirm(`Are you sure you want to delete ${selectedIds.length} order(s)? This action cannot be undone.`)) {
                return;
            }

            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch(`/properties/${propertyId}/bar-orders/bulk-delete`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify({ ids: selectedIds })
            })
            .then(response => {
                if (response.ok) {
                    location.reload();
                } else {
                    alert('Error deleting orders. Please try again.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error deleting orders: ' + error.message);
            });
        }
    </script>
    <?php endif; ?>
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

<?php /**PATH /home/kuwaitcabinhotel/sales-portal.kuwaitcabinhotel.com/resources/views/bar-orders/index.blade.php ENDPATH**/ ?>