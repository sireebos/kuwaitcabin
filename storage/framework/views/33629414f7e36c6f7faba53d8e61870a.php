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
            <?php echo e(__('Inventory Management')); ?>

        </h2>
     <?php $__env->endSlot(); ?>
<div class="mb-6 flex justify-between items-center">
    <h1 class="text-2xl font-bold">Inventory Items</h1>
    <a href="<?php echo e(route('properties.inventory.create', $property)); ?>" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
        Add Item
    </a>
</div>

<!-- Low Stock Alert -->
<?php if($lowStockItems->count() > 0): ?>
    <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
        <p class="font-bold">⚠️ Low Stock Alert</p>
        <p><?php echo e($lowStockItems->count()); ?> items are below reorder level</p>
    </div>
<?php endif; ?>

<!-- Bulk Delete Section (Admin Only) -->
<?php if(auth()->user()->isAdmin()): ?>
    <div id="bulkDeleteSection" class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg hidden">
        <div class="flex justify-between items-center">
            <span class="text-sm font-semibold text-gray-700">
                <span id="selectedCount">0</span> item(s) selected
            </span>
            <button onclick="bulkDelete()" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                Delete Selected
            </button>
        </div>
    </div>
<?php endif; ?>

<div class="bg-white rounded-lg shadow">
    <div class="overflow-x-auto">
        <table class="w-full min-w-[900px]">
        <thead class="bg-gray-100 border-b">
            <tr>
                <?php if(auth()->user()->isAdmin()): ?>
                    <th class="px-6 py-3 text-left text-sm font-semibold">
                        <input type="checkbox" id="selectAll" onchange="toggleSelectAll()" class="rounded">
                    </th>
                <?php endif; ?>
                <th class="px-6 py-3 text-left text-sm font-semibold">SKU</th>
                <th class="px-6 py-3 text-left text-sm font-semibold">Name</th>
                <th class="px-6 py-3 text-left text-sm font-semibold">Type</th>
                <th class="px-6 py-3 text-left text-sm font-semibold">Quantity</th>
                <th class="px-6 py-3 text-left text-sm font-semibold">Unit</th>
                <th class="px-6 py-3 text-left text-sm font-semibold">Reorder Level</th>
                <th class="px-6 py-3 text-left text-sm font-semibold">Unit Cost</th>
                <th class="px-6 py-3 text-left text-sm font-semibold">Status</th>
                <th class="px-6 py-3 text-left text-sm font-semibold">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="border-b hover:bg-gray-50 <?php if($item->quantity <= $item->reorder_level): ?> bg-red-50 <?php endif; ?> item-row">
                    <?php if(auth()->user()->isAdmin()): ?>
                        <td class="px-6 py-4 text-sm">
                            <input type="checkbox" class="item-checkbox rounded" value="<?php echo e($item->id); ?>" onchange="updateBulkDeleteUI()">
                        </td>
                    <?php endif; ?>
                    <td class="px-6 py-4 text-sm"><?php echo e($item->sku); ?></td>
                    <td class="px-6 py-4 text-sm"><?php echo e($item->name); ?></td>
                    <td class="px-6 py-4 text-sm">
                        <span class="px-2 py-1 rounded text-xs font-semibold
                            <?php if($item->type === 'ingredient'): ?> bg-blue-100 text-blue-800
                            <?php elseif($item->type === 'beverage'): ?> bg-green-100 text-green-800
                            <?php else: ?> bg-gray-100 text-gray-800
                            <?php endif; ?>">
                            <?php echo e(ucfirst($item->type)); ?>

                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm font-semibold"><?php echo e($item->quantity); ?></td>
                    <td class="px-6 py-4 text-sm"><?php echo e($item->unit); ?></td>
                    <td class="px-6 py-4 text-sm"><?php echo e($item->reorder_level); ?></td>
                    <td class="px-6 py-4 text-sm">₦<?php echo e(number_format($item->unit_cost, 2)); ?></td>
                    <td class="px-6 py-4 text-sm">
                        <?php if($item->quantity <= $item->reorder_level): ?>
                            <span class="px-2 py-1 rounded text-xs font-semibold bg-red-100 text-red-800">Low Stock</span>
                        <?php else: ?>
                            <span class="px-2 py-1 rounded text-xs font-semibold bg-green-100 text-green-800">In Stock</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4 text-sm">
                        <div class="flex gap-2">
                            <a href="<?php echo e(route('properties.inventory.edit', [$property, $item])); ?>" class="text-blue-600 hover:text-blue-800">Edit</a>
                            <button onclick="openTopUpModal(<?php echo e($item->id); ?>, '<?php echo e($item->name); ?>')" class="text-green-600 hover:text-green-800">Top Up</button>
                        </div>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="<?php echo e(auth()->user()->isAdmin() ? 10 : 9); ?>" class="px-6 py-4 text-center text-gray-500">No inventory items found</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    </div>
</div>

<?php if(auth()->user()->isAdmin()): ?>
    <script>
        const propertyId = <?php echo e($property->id); ?>;

        function toggleSelectAll() {
            const selectAll = document.getElementById('selectAll');
            const checkboxes = document.querySelectorAll('.item-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = selectAll.checked;
            });
            updateBulkDeleteUI();
        }

        function updateBulkDeleteUI() {
            const checkboxes = document.querySelectorAll('.item-checkbox');
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
            const checkboxes = document.querySelectorAll('.item-checkbox:checked');
            const selectedIds = Array.from(checkboxes).map(cb => cb.value);

            if (selectedIds.length === 0) {
                alert('Please select at least one item to delete');
                return;
            }

            if (!confirm(`Are you sure you want to delete ${selectedIds.length} inventory item(s)? This action cannot be undone.`)) {
                return;
            }

            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch(`/properties/${propertyId}/inventory/bulk-delete`, {
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
                    alert('Error deleting items. Please try again.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error deleting items: ' + error.message);
            });
        }

        function openTopUpModal(itemId, itemName) {
            document.getElementById('topUpItemId').value = itemId;
            document.getElementById('topUpItemName').textContent = itemName;
            document.getElementById('topUpQuantity').value = '';
            document.getElementById('topUpModal').classList.remove('hidden');
        }

        function closeTopUpModal() {
            document.getElementById('topUpModal').classList.add('hidden');
        }

        function submitTopUp() {
            const itemId = document.getElementById('topUpItemId').value;
            const quantity = document.getElementById('topUpQuantity').value;

            if (!quantity || quantity <= 0) {
                alert('Please enter a valid quantity');
                return;
            }

            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch(`/properties/<?php echo e($property->id); ?>/inventory/${itemId}/adjustment`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    quantity: parseFloat(quantity),
                    notes: 'Top up inventory',
                    top_up: true
                })
            })
            .then(response => {
                if (response.ok) {
                    location.reload();
                } else {
                    alert('Error updating inventory. Please try again.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error updating inventory: ' + error.message);
            });
        }
    </script>

    <!-- Top Up Modal -->
    <div id="topUpModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-lg p-6 w-96">
            <h3 class="text-lg font-bold mb-4">Top Up Inventory</h3>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Item Name</label>
                <p id="topUpItemName" class="text-gray-800 font-semibold"></p>
            </div>

            <div class="mb-6">
                <label for="topUpQuantity" class="block text-sm font-medium text-gray-700 mb-2">Quantity to Add</label>
                <input type="number" id="topUpQuantity" step="0.01" min="0" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500" placeholder="Enter quantity">
            </div>

            <input type="hidden" id="topUpItemId">

            <div class="flex gap-3">
                <button onclick="submitTopUp()" class="flex-1 bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 font-medium">
                    Top Up
                </button>
                <button onclick="closeTopUpModal()" class="flex-1 bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400 font-medium">
                    Cancel
                </button>
            </div>
        </div>
    </div>
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

<?php /**PATH /home/kuwaitcabinhotel/sales-portal.kuwaitcabinhotel.com/resources/views/inventory/index.blade.php ENDPATH**/ ?>