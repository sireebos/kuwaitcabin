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
            <?php echo e(__('Bar POS')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

<?php if($errors->any()): ?>
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        <strong>Error:</strong>
        <ul>
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li><?php echo e($error); ?></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>
<?php endif; ?>

<?php if(session('error')): ?>
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        <?php echo e(session('error')); ?>

    </div>
<?php endif; ?>

<div class="grid grid-cols-1 lg:grid-cols-4 gap-4 h-screen overflow-hidden">
    <!-- Drinks Inventory -->
    <div class="lg:col-span-2 overflow-y-auto bg-gray-50">
        <div class="p-4">
            <h2 class="text-2xl font-bold mb-4">Available Drinks</h2>

            <!-- Search Bar -->
            <div class="mb-4">
                <input 
                    type="text" 
                    id="drinkSearch" 
                    placeholder="Search drinks..." 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                    onkeyup="filterDrinks()"
                >
            </div>

            <!-- Drinks Grid -->
            <div class="grid grid-cols-2 gap-3" id="drinksContainer">
                <?php $__currentLoopData = $drinks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $drink): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if($drink->quantity > 0): ?>
                        <div class="drink-item bg-white rounded-lg shadow p-3 cursor-pointer hover:shadow-lg transition <?php if($drink->quantity <= $drink->reorder_level): ?> border-2 border-yellow-400 <?php endif; ?>" data-drink-name="<?php echo e($drink->name); ?>" data-drink-id="<?php echo e($drink->id); ?>" onclick="addToOrder(<?php echo e($drink->id); ?>, '<?php echo e(addslashes($drink->name)); ?>', <?php echo e($drink->unit_cost); ?>, <?php echo e($drink->quantity); ?>)">
                            <p class="font-semibold text-sm"><?php echo e($drink->name); ?></p>
                            <p class="text-xs text-gray-600 mb-2">Stock: <?php echo e($drink->quantity); ?> <?php echo e($drink->unit); ?></p>
                            <?php if($drink->quantity <= $drink->reorder_level): ?>
                                <p class="text-xs text-yellow-600 font-semibold">⚠️ Low Stock</p>
                            <?php endif; ?>
                            <p class="font-bold text-blue-600">₦<?php echo e(number_format($drink->unit_cost ?? 0, 2)); ?></p>
                        </div>
                    <?php else: ?>
                        <div class="drink-item bg-gray-200 rounded-lg shadow p-3 opacity-50 cursor-not-allowed" data-drink-name="<?php echo e($drink->name); ?>" data-drink-id="<?php echo e($drink->id); ?>">
                            <p class="font-semibold text-sm"><?php echo e($drink->name); ?></p>
                            <p class="text-xs text-gray-600 mb-2">Stock: <?php echo e($drink->quantity); ?> <?php echo e($drink->unit); ?></p>
                            <p class="font-bold text-gray-600">₦<?php echo e(number_format($drink->unit_cost ?? 0, 2)); ?></p>
                            <p class="text-xs text-red-600 mt-1">Out of Stock</p>
                        </div>
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>

    <!-- Order Summary & Payment -->
    <div class="lg:col-span-2 bg-white rounded-lg shadow flex flex-col overflow-hidden">
        <div class="p-4 border-b bg-blue-50">
            <h3 class="text-xl font-bold">Current Order</h3>
        </div>

        <!-- Order Items -->
        <div id="orderItems" class="flex-1 overflow-y-auto p-4">
            <p class="text-gray-500 text-center py-8">No items added</p>
        </div>

        <!-- Order Totals & Controls -->
        <div class="border-t p-4 bg-gray-50 space-y-4">
            <!-- Customer Name -->
            <div>
                <label class="block text-sm font-semibold mb-2">Customer/Table Name:</label>
                <input type="text" id="customerName" placeholder="Enter name or table name" class="w-full px-3 py-2 border rounded" required>
            </div>

            <div class="space-y-2">
                <div class="flex justify-between text-sm">
                    <span>Subtotal:</span>
                    <span id="subtotal">₦0.00</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span>Discount:</span>
                    <div class="flex gap-2">
                        <input type="number" id="discountAmount" placeholder="0.00" class="w-20 px-2 py-1 border rounded text-sm" onchange="updateTotals()">
                        <span id="discountDisplay">₦0.00</span>
                    </div>
                </div>
                <div class="flex justify-between text-sm">
                    <span id="taxLabel">Tax (<?php echo e($property->tax_rate ?? 10); ?>%):</span>
                    <span id="tax">₦0.00</span>
                </div>
                <div class="flex justify-between font-bold text-lg border-t pt-2">
                    <span>Total:</span>
                    <span id="total">₦0.00</span>
                </div>
            </div>

            <!-- Order Type Selection -->
            <div>
                <label class="block text-sm font-semibold mb-2">Order Type:</label>
                <select id="orderType" onchange="toggleTableSelection()" class="w-full px-3 py-2 border rounded">
                    <option value="takeaway">Takeaway</option>
                    <option value="dine_in">Dine In</option>
                    <option value="room_service">Room Service</option>
                </select>
            </div>

            <!-- Table Selection (for dine_in) -->
            <div id="tableSelection" style="display: none;">
                <label class="block text-sm font-semibold mb-2">Table Number:</label>
                <div class="flex gap-2">
                    <input type="number" id="tableNumber" placeholder="Enter table number" class="flex-1 px-3 py-2 border rounded" min="1">
                    <button type="button" onclick="clearTableNumber()" class="bg-gray-300 text-gray-700 px-3 py-2 rounded hover:bg-gray-400 font-semibold">Clear</button>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="grid grid-cols-2 gap-2">
                <button onclick="submitOrder()" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 font-semibold">
                    Submit Order
                </button>
                <button onclick="clearOrder()" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 font-semibold">
                    Clear
                </button>
            </div>

            <!-- Reopen Previous Order Button -->
            <button onclick="showUnpaidOrders()" class="w-full bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 font-semibold">
                📋 Reopen Previous Order
            </button>
        </div>
    </div>
</div>

<script>
let orderItems = [];
let originalOrderItemIds = []; // Track IDs of items from the original order
let originalItemQuantities = {}; // Track original quantities of items for detecting changes
const propertyId = <?php echo e($property->id); ?>;
const taxRate = <?php echo e($property->tax_rate ?? 10); ?> / 100; // Tax rate as decimal (e.g., 0.1 for 10%)
const existingOrder = <?php echo json_encode($order ?? null, 15, 512) ?>;
const isExistingOrder = !!existingOrder && !!existingOrder.id; // Explicitly check if it's an existing order

console.log('Existing Order:', existingOrder);
console.log('isExistingOrder:', isExistingOrder);
console.log('Has items:', existingOrder && existingOrder.items ? existingOrder.items.length : 0);

// Load existing order items if reopening
if (isExistingOrder && existingOrder.items && existingOrder.items.length > 0) {
    console.log('=== LOADING EXISTING ORDER ITEMS ===');
    console.log('Number of items:', existingOrder.items.length);
    existingOrder.items.forEach((item, index) => {
        console.log(`\nItem ${index}:`, item);
        console.log('inventory_item_id (raw):', item.inventory_item_id, 'type:', typeof item.inventory_item_id);
        console.log('inventoryItem:', item.inventoryItem);
        
        const itemId = parseInt(item.inventory_item_id, 10); // Convert to integer for consistency
        console.log('inventory_item_id (parsed):', itemId, 'type:', typeof itemId);
        
        const itemName = item.inventoryItem?.name || 'Unknown Item';
        const itemPrice = parseFloat(item.unit_price);
        const itemQty = item.quantity;
        
        console.log('Processing: id=', itemId, 'name=', itemName, 'price=', itemPrice, 'qty=', itemQty);
        
        orderItems.push({
            id: itemId,
            name: itemName,
            price: itemPrice,
            quantity: itemQty,
            stock: 999 // Set high stock since we're reopening
        });
        originalOrderItemIds.push(itemId); // Track original item IDs as integers
        originalItemQuantities[itemId] = itemQty; // Store original quantity for change detection
        console.log('Added to originalOrderItemIds:', itemId, 'with original qty:', itemQty);
        console.log('originalOrderItemIds is now:', originalOrderItemIds);
    });
    console.log('\n=== LOADED ALL ITEMS ===');
    console.log('Final originalOrderItemIds:', originalOrderItemIds);
    console.log('Final orderItems:', orderItems);
    document.getElementById('customerName').value = existingOrder.customer_name || '';
} else {
    console.log('=== NO EXISTING ORDER ITEMS TO LOAD ===');
    console.log('isExistingOrder:', isExistingOrder);
    console.log('existingOrder:', existingOrder);
    console.log('existingOrder.items:', existingOrder?.items);
}

console.log('=== INITIALIZATION COMPLETE ===');
console.log('Initial orderItems:', orderItems);
console.log('Initial originalOrderItemIds:', originalOrderItemIds);

function filterDrinks() {
    const searchTerm = document.getElementById('drinkSearch').value.toLowerCase();
    const drinks = document.querySelectorAll('.drink-item');
    let visibleCount = 0;
    
    drinks.forEach(drink => {
        const drinkName = drink.getAttribute('data-drink-name').toLowerCase();
        if (drinkName.includes(searchTerm)) {
            drink.style.display = 'block';
            visibleCount++;
        } else {
            drink.style.display = 'none';
        }
    });
    
    // Show "No drinks found" message if needed
    let noResultsDiv = document.getElementById('noResults');
    if (visibleCount === 0 && searchTerm !== '') {
        if (!noResultsDiv) {
            const container = document.getElementById('drinksContainer');
            noResultsDiv = document.createElement('div');
            noResultsDiv.id = 'noResults';
            noResultsDiv.className = 'col-span-2 text-center text-gray-500 py-8';
            noResultsDiv.textContent = 'No drinks found matching your search';
            container.parentNode.insertBefore(noResultsDiv, container.nextSibling);
        } else {
            noResultsDiv.style.display = 'block';
        }
    } else if (noResultsDiv) {
        noResultsDiv.style.display = 'none';
    }
}

function addToOrder(itemId, itemName, price, availableStock) {
    console.log('=== ADD TO ORDER ===');
    console.log('itemId (raw):', itemId, 'type:', typeof itemId);
    
    // Ensure itemId is an integer for consistent comparison
    itemId = parseInt(itemId, 10);
    console.log('itemId (parsed):', itemId, 'type:', typeof itemId);
    console.log('itemName:', itemName, 'price:', price);
    console.log('isExistingOrder:', isExistingOrder);
    
    const existing = orderItems.find(item => item.id === itemId);
    const currentQuantity = existing ? existing.quantity : 0;

    // Check if adding one more would exceed available stock
    if (currentQuantity >= availableStock) {
        alert(`Cannot add more ${itemName}. Only ${availableStock} in stock.`);
        return;
    }

    if (existing) {
        console.log('Item already in order, incrementing quantity from', existing.quantity, 'to', existing.quantity + 1);
        existing.quantity++;
    } else {
        console.log('New item, adding to order with id:', itemId, 'type:', typeof itemId);
        orderItems.push({ id: itemId, name: itemName, price: parseFloat(price), quantity: 1, stock: availableStock });
    }
    
    console.log('=== AFTER ADDING ===');
    console.log('orderItems:', orderItems);
    console.log('originalOrderItemIds:', originalOrderItemIds);
    orderItems.forEach(item => {
        const isOriginal = originalOrderItemIds.includes(item.id);
        console.log(`- ${item.name} (id=${item.id}, type=${typeof item.id}): isOriginal=${isOriginal}`);
    });
    
    updateOrderDisplay();
}

function removeFromOrder(itemId) {
    console.log('=== REMOVE FROM ORDER ===');
    console.log('itemId:', itemId);
    console.log('isExistingOrder:', isExistingOrder);
    
    // Check if this item ID was in the original order
    const isOriginalItem = originalOrderItemIds.includes(itemId);
    console.log('isOriginalItem:', isOriginalItem);
    
    // If it's an original item in a reopened order, we need to handle deletion differently
    if (isOriginalItem && isExistingOrder) {
        console.log('Removing original item from existing order');
        if (confirm('Remove this item from the order?')) {
            removeExistingOrderItem(existingOrder.id, itemId);
        }
    } else {
        // For new items, just remove from the array
        console.log('Removing new item from array');
        orderItems = orderItems.filter(item => item.id !== itemId);
        updateOrderDisplay();
    }
}

function updateOrderDisplay() {
    const itemsDiv = document.getElementById('orderItems');
    if (orderItems.length === 0) {
        itemsDiv.innerHTML = '<p class="text-gray-500 text-center py-8">No items added</p>';
    } else {
        itemsDiv.innerHTML = orderItems.map(item => `
            <div class="flex justify-between items-center mb-3 pb-3 border-b">
                <div class="flex-1">
                    <p class="font-semibold">${item.name}</p>
                    <p class="text-sm text-gray-600">₦${item.price.toFixed(2)} x ${item.quantity}</p>
                </div>
                <div class="text-right">
                    <p class="font-semibold">₦${(item.price * item.quantity).toFixed(2)}</p>
                    <button onclick="removeFromOrder(${item.id})" class="text-red-600 hover:text-red-800 text-sm">Remove</button>
                </div>
            </div>
        `).join('');
    }
    updateTotals();
}

function updateTotals() {
    const subtotal = orderItems.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    const discount = parseFloat(document.getElementById('discountAmount').value) || 0;
    const tax = (subtotal - discount) * taxRate;
    const total = subtotal - discount + tax;

    document.getElementById('subtotal').textContent = '₦' + subtotal.toFixed(2);
    document.getElementById('discountDisplay').textContent = '₦' + discount.toFixed(2);
    document.getElementById('tax').textContent = '₦' + tax.toFixed(2);
    document.getElementById('total').textContent = '₦' + total.toFixed(2);
}

function toggleTableSelection() {
    const tableSelection = document.getElementById('tableSelection');
    const orderType = document.getElementById('orderType').value;
    tableSelection.style.display = orderType === 'dine_in' ? 'block' : 'none';
}

function clearTableNumber() {
    document.getElementById('tableNumber').value = '';
}

function submitOrder() {
    console.log('=== SUBMIT ORDER ===');
    console.log('isExistingOrder:', isExistingOrder);
    console.log('existingOrder:', existingOrder);
    
    if (orderItems.length === 0) {
        alert('Please add items to the order');
        return;
    }

    const customerName = document.getElementById('customerName').value.trim();
    if (!customerName) {
        alert('Please enter customer/table name');
        return;
    }

    const orderType = document.getElementById('orderType').value;
    const tableNumber = orderType === 'dine_in' ? document.getElementById('tableNumber').value : null;

    if (orderType === 'dine_in' && !tableNumber) {
        alert('Please enter a table number');
        return;
    }

    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Create a hidden form and submit it
    const form = document.createElement('form');
    form.method = 'POST';
    
    // If we have an existing order, add items to it. Otherwise, create a new order
    if (isExistingOrder) {
        console.log('ADDING TO EXISTING ORDER ID:', existingOrder.id);
        form.action = '/properties/' + propertyId + '/bar-orders/' + existingOrder.id + '/add-items';
    } else {
        console.log('CREATING NEW ORDER');
        form.action = '/properties/' + propertyId + '/bar-orders/create-with-items';
    }

    const tokenInput = document.createElement('input');
    tokenInput.type = 'hidden';
    tokenInput.name = '_token';
    tokenInput.value = csrfToken;
    form.appendChild(tokenInput);

    // Only add customer/type/table info if creating a NEW order
    if (!isExistingOrder) {
        const customerInput = document.createElement('input');
        customerInput.type = 'hidden';
        customerInput.name = 'customer_name';
        customerInput.value = customerName;
        form.appendChild(customerInput);

        const typeInput = document.createElement('input');
        typeInput.type = 'hidden';
        typeInput.name = 'type';
        typeInput.value = orderType;
        form.appendChild(typeInput);

        const tableInput = document.createElement('input');
        tableInput.type = 'hidden';
        tableInput.name = 'table_number';
        tableInput.value = tableNumber || '';
        form.appendChild(tableInput);
    }

    const itemsInput = document.createElement('input');
    itemsInput.type = 'hidden';
    itemsInput.name = 'items';
    
    // If adding to existing order, send items that are either NEW or have CHANGED QUANTITY
    if (isExistingOrder) {
        console.log('=== FILTERING ITEMS FOR EXISTING ORDER ===');
        console.log('originalOrderItemIds:', originalOrderItemIds);
        console.log('originalItemQuantities:', originalItemQuantities);
        console.log('orderItems:', orderItems);
        
        // Get items that are either:
        // 1. New items (not in originalOrderItemIds)
        // 2. Existing items with changed quantity
        const itemsToSubmit = [];
        orderItems.forEach(item => {
            const isNewItem = !originalOrderItemIds.includes(item.id);
            const originalQty = originalItemQuantities[item.id];
            const hasQtyChanged = originalQty && item.quantity !== originalQty;
            
            console.log(`Checking ${item.name} (id=${item.id}): isNewItem=${isNewItem}, hasQtyChanged=${hasQtyChanged} (original=${originalQty}, current=${item.quantity})`);
            
            if (isNewItem) {
                console.log(`  → NEW ITEM, adding to submit list`);
                itemsToSubmit.push(item);
            } else if (hasQtyChanged) {
                console.log(`  → QUANTITY CHANGED, adding to submit list`);
                itemsToSubmit.push(item);
            } else {
                console.log(`  → No changes, skipping`);
            }
        });
        
        console.log('Items to submit after filter:', itemsToSubmit);
        
        if (itemsToSubmit.length === 0) {
            alert('No new items or quantity changes. Please add drinks or modify quantities.');
            return;
        }
        itemsInput.value = JSON.stringify(itemsToSubmit);
    } else {
        console.log('Creating new order with all items');
        itemsInput.value = JSON.stringify(orderItems);
    }
    form.appendChild(itemsInput);

    console.log('Form action:', form.action);
    console.log('Items to submit:', itemsInput.value);
    document.body.appendChild(form);
    form.submit();
}

function clearOrder() {
    if (confirm('Clear all items from order?')) {
        orderItems = [];
        updateOrderDisplay();
    }
}

function showUnpaidOrders() {
    fetch('/properties/' + propertyId + '/bar-orders/unpaid')
        .then(response => response.json())
        .then(data => {
            if (data.success && data.orders.length > 0) {
                let html = '<div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">';
                html += '<div class="bg-white rounded-lg shadow-lg p-6 max-w-md w-full max-h-96 overflow-y-auto">';
                html += '<h2 class="text-xl font-bold mb-4">Previous Orders</h2>';
                html += '<div class="space-y-2">';

                data.orders.forEach(order => {
                    html += `<div class="border rounded p-3 hover:bg-gray-50 cursor-pointer" onclick="reopenOrder(${order.id})">
                        <div class="font-semibold">${order.customer_name}</div>
                        <div class="text-sm text-gray-600">Order #${order.order_number}</div>
                        <div class="text-sm text-gray-600">Items: ${order.items_count} | Time: ${order.created_at}</div>
                        <div class="font-bold text-blue-600">₦${parseFloat(order.total).toFixed(2)}</div>
                    </div>`;
                });

                html += '</div>';
                html += '<button onclick="closeModal()" class="mt-4 w-full bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400">Close</button>';
                html += '</div></div>';

                document.body.insertAdjacentHTML('beforeend', html);
            } else {
                alert('No previous orders found');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error loading orders');
        });
}

function reopenOrder(orderId) {
    window.location.href = '/properties/' + propertyId + '/bar-orders/' + orderId + '/reopen';
}

function closeModal() {
    const modal = document.querySelector('.fixed.inset-0');
    if (modal) modal.remove();
}

function removeExistingOrderItem(orderId, inventoryItemId) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    fetch(`/properties/${propertyId}/bar-orders/${orderId}/remove-item/${inventoryItemId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Remove from orderItems array
            orderItems = orderItems.filter(item => item.id !== inventoryItemId);
            updateOrderDisplay();
            alert('Item removed from order');
        } else {
            alert('Error removing item: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error removing item');
    });
}

// Initialize
toggleTableSelection();
if (existingOrder) {
    updateOrderDisplay();
}

// Final debugging summary
console.log('%c=== FINAL DEBUG SUMMARY ===', 'background: #222; color: #bada55; font-size: 14px; font-weight: bold;');
console.log('isExistingOrder:', isExistingOrder);
console.log('existingOrder.id:', existingOrder?.id);
console.log('Total items in originalOrderItemIds:', originalOrderItemIds.length);
console.log('originalOrderItemIds values:', originalOrderItemIds);
console.log('originalOrderItemIds types:', originalOrderItemIds.map(id => typeof id));
console.log('Total items in orderItems:', orderItems.length);
console.log('orderItems:', orderItems);
if (orderItems.length > 0) {
    console.log('First item ID:', orderItems[0].id, 'Type:', typeof orderItems[0].id);
}
</script>
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

<?php /**PATH /home/kuwaitcabinhotel/sales-portal.kuwaitcabinhotel.com/resources/views/bar-orders/pos.blade.php ENDPATH**/ ?>