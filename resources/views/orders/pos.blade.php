<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Point of Sale - Restaurant') }}
        </h2>
    </x-slot> 

@if ($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        <strong>Error:</strong>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if (session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        {{ session('error') }}
    </div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-4 gap-4 h-screen overflow-hidden">
    <!-- Menu Items -->
    <div class="lg:col-span-2 overflow-y-auto bg-gray-50">
        <div class="p-4">
            <h2 class="text-2xl font-bold mb-4">Menu</h2>

            <!-- Category Tabs -->
            <div class="flex gap-2 mb-4 overflow-x-auto pb-2">
                <button onclick="filterCategory('all')" class="category-btn px-4 py-2 rounded bg-blue-600 text-white whitespace-nowrap active">All</button>
                @foreach ($menus as $menu)
                    @foreach ($menu->categories as $category)
                        <button onclick="filterCategory('cat-{{ $category->id }}')" class="category-btn px-4 py-2 rounded bg-gray-300 text-gray-700 whitespace-nowrap hover:bg-gray-400">
                            {{ $category->name }}
                        </button>
                    @endforeach
                @endforeach
            </div>

            <!-- Menu Items Grid -->
            <div class="grid grid-cols-2 gap-3">
                @foreach ($menus as $menu)
                    @foreach ($menu->categories as $category)
                        @foreach ($category->items as $item)
                            @if ($item->is_available)
                                <div class="menu-item category-cat-{{ $category->id }} bg-white rounded-lg shadow p-3 cursor-pointer hover:shadow-lg transition" onclick="addToOrder({{ $item->id }}, '{{ addslashes($item->name) }}', {{ $item->price }})">
                                    <p class="font-semibold text-sm">{{ $item->name }}</p>
                                    <p class="text-xs text-gray-600 mb-2">{{ Str::limit($item->description, 40) }}</p>
                                    <p class="font-bold text-blue-600">₦{{ number_format($item->price, 2) }}</p>
                                </div>
                            @endif
                        @endforeach
                    @endforeach
                @endforeach
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
                    <span id="taxLabel">Tax ({{ $property->tax_rate ?? 10 }}%):</span>
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
                    <option value="dine_in">Dine In</option>
                    <option value="takeaway">Takeaway</option>
                    <option value="room_service">Room Service</option>
                </select>
            </div>

            <!-- Table Selection (for dine_in) -->
            <div id="tableSelection">
                <label class="block text-sm font-semibold mb-2">Table Number:</label>
                <div class="flex gap-2">
                    <input type="number" id="tableNumber" placeholder="Enter table number" class="flex-1 px-3 py-2 border rounded" min="1">
                    <button type="button" onclick="clearTableNumber()" class="bg-gray-300 text-gray-700 px-3 py-2 rounded hover:bg-gray-400 font-semibold">Clear</button>
                </div>
                <p class="text-xs text-gray-500 mt-1">Enter the table number manually</p>
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
        </div>
    </div>
</div>

<script>
let orderItems = [];
const propertyId = {{ $property->id }};
const taxRate = {{ $property->tax_rate ?? 10 }} / 100; // Tax rate as decimal (e.g., 0.1 for 10%)

function filterCategory(category) {
    document.querySelectorAll('.category-btn').forEach(btn => btn.classList.remove('bg-blue-600', 'text-white'));
    event.target.classList.add('bg-blue-600', 'text-white');

    document.querySelectorAll('.menu-item').forEach(item => {
        if (category === 'all' || item.classList.contains('category-' + category)) {
            item.style.display = 'block';
        } else {
            item.style.display = 'none';
        }
    });
}

function addToOrder(itemId, itemName, price) {
    const existing = orderItems.find(item => item.id === itemId);
    if (existing) {
        existing.quantity++;
    } else {
        orderItems.push({ id: itemId, name: itemName, price: parseFloat(price), quantity: 1 });
    }
    updateOrderDisplay();
}

function removeFromOrder(itemId) {
    orderItems = orderItems.filter(item => item.id !== itemId);
    updateOrderDisplay();
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
    if (orderItems.length === 0) {
        alert('Please add items to the order');
        return;
    }

    const orderType = document.getElementById('orderType').value;
    const tableNumber = orderType === 'dine_in' ? document.getElementById('tableNumber').value : null;

    if (orderType === 'dine_in' && !tableNumber) {
        alert('Please enter a table number');
        return;
    }

    // Get CSRF token from meta tag
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    console.log('Submitting order with items:', orderItems);
    console.log('Order type:', orderType);
    console.log('Table number:', tableNumber);
    console.log('Items JSON:', JSON.stringify(orderItems));

    // Create FormData to properly handle JSON
    const formData = new FormData();
    formData.append('_token', csrfToken);
    formData.append('type', orderType);
    formData.append('table_number', tableNumber || '');
    formData.append('items', JSON.stringify(orderItems));

    // Submit using fetch with redirect handling
    fetch('/properties/' + propertyId + '/orders/create-with-items', {
        method: 'POST',
        body: formData,
        redirect: 'follow'
    })
    .then(response => {
        console.log('Response status:', response.status);
        console.log('Response URL:', response.url);
        if (response.ok) {
            // Redirect to the final URL after following redirects
            window.location.href = response.url;
        } else {
            return response.text().then(text => {
                console.error('Error response:', text);
                alert('Error creating order. Please try again.');
            });
        }
    })
    .catch(error => {
        console.error('Fetch error:', error);
        alert('Error submitting order: ' + error.message);
    });
}

function clearOrder() {
    if (confirm('Clear all items from order?')) {
        orderItems = [];
        updateOrderDisplay();
    }
}

// Initialize
toggleTableSelection();
</script>
</x-app-layout>

