<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('API Documentation') }}
        </h2>
    </x-slot>

    <div class="max-w-6xl mx-auto">
        <!-- API Overview -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">🔌 Room Booking API</h1>
                    <p class="text-gray-600 mt-2">Use these endpoints to integrate room booking into your main website</p>
                </div>
                <div class="text-right">
                    <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium">API v1</span>
                </div>
            </div>

            <!-- Base URL -->
            <div class="bg-gray-50 rounded-lg p-4 mb-6">
                <h3 class="font-semibold text-gray-700 mb-2">Base URL</h3>
                <div class="flex items-center gap-2">
                    <code class="bg-gray-800 text-white px-4 py-2 rounded font-mono text-sm flex-1">{{ url('/api/v1') }}</code>
                    <button onclick="copyToClipboard('{{ url('/api/v1') }}')" class="bg-blue-600 text-white px-3 py-2 rounded hover:bg-blue-700 text-sm">
                        📋 Copy
                    </button>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                <a href="#properties" class="bg-blue-50 hover:bg-blue-100 rounded-lg p-4 text-center transition">
                    <span class="text-2xl">🏨</span>
                    <p class="font-medium text-blue-800 mt-2">Properties</p>
                </a>
                <a href="#room-types" class="bg-purple-50 hover:bg-purple-100 rounded-lg p-4 text-center transition">
                    <span class="text-2xl">🛏️</span>
                    <p class="font-medium text-purple-800 mt-2">Room Types</p>
                </a>
                <a href="#availability" class="bg-green-50 hover:bg-green-100 rounded-lg p-4 text-center transition">
                    <span class="text-2xl">📅</span>
                    <p class="font-medium text-green-800 mt-2">Availability</p>
                </a>
                <a href="#booking" class="bg-orange-50 hover:bg-orange-100 rounded-lg p-4 text-center transition">
                    <span class="text-2xl">✅</span>
                    <p class="font-medium text-orange-800 mt-2">Booking</p>
                </a>
            </div>
        </div>

        <!-- Properties Section -->
        <div id="properties" class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
                <span class="text-2xl">🏨</span> Properties (Hotels)
            </h2>

            <!-- Get All Properties -->
            <div class="border rounded-lg mb-4">
                <div class="bg-gray-50 px-4 py-3 border-b flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <span class="bg-green-500 text-white text-xs font-bold px-2 py-1 rounded">GET</span>
                        <code class="font-mono text-sm">/properties</code>
                    </div>
                    <span class="text-gray-500 text-sm">List all active properties</span>
                </div>
                <div class="p-4">
                    <p class="text-gray-600 mb-3">Returns a list of all active hotel properties available for booking.</p>
                    <div class="bg-gray-800 rounded-lg p-4 overflow-x-auto">
                        <code class="text-white font-mono text-sm whitespace-pre">{
  "success": true,
  "properties": [
    {
      "id": {{ $property->id }},
      "name": "{{ $property->name }}",
      "slug": "{{ $property->slug }}",
      "description": "{{ Str::limit($property->description, 50) }}",
      "address": "{{ $property->address }}",
      "city": "{{ $property->city }}",
      "currency": "{{ $property->currency }}",
      "tax_rate": {{ $property->tax_rate ?? 0 }}
    }
  ],
  "count": 1
}</code>
                    </div>
                </div>
            </div>

            <!-- Get Single Property -->
            <div class="border rounded-lg">
                <div class="bg-gray-50 px-4 py-3 border-b flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <span class="bg-green-500 text-white text-xs font-bold px-2 py-1 rounded">GET</span>
                        <code class="font-mono text-sm">/properties/{id}</code>
                    </div>
                    <span class="text-gray-500 text-sm">Get single property details</span>
                </div>
                <div class="p-4">
                    <p class="text-gray-600 mb-3">Returns detailed information about a specific property including room types.</p>
                    <div class="bg-blue-50 rounded p-3 mb-3">
                        <strong>Example:</strong> <code class="bg-blue-100 px-2 py-1 rounded">GET {{ url('/api/v1/properties/' . $property->id) }}</code>
                    </div>
                </div>
            </div>
        </div>

        <!-- Room Types Section -->
        <div id="room-types" class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
                <span class="text-2xl">🛏️</span> Room Types & Rooms
            </h2>

            <!-- Get Room Types -->
            <div class="border rounded-lg mb-4">
                <div class="bg-gray-50 px-4 py-3 border-b flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <span class="bg-green-500 text-white text-xs font-bold px-2 py-1 rounded">GET</span>
                        <code class="font-mono text-sm">/properties/{id}/room-types</code>
                    </div>
                    <span class="text-gray-500 text-sm">List room types for a property</span>
                </div>
                <div class="p-4">
                    <p class="text-gray-600 mb-3">Returns all active room types for a property with capacity, amenities, and pricing.</p>
                    <div class="bg-blue-50 rounded p-3 mb-3">
                        <strong>Example:</strong> <code class="bg-blue-100 px-2 py-1 rounded">GET {{ url('/api/v1/properties/' . $property->id . '/room-types') }}</code>
                    </div>
                    <div class="bg-gray-800 rounded-lg p-4 overflow-x-auto">
                        <code class="text-white font-mono text-sm whitespace-pre">{
  "success": true,
  "room_types": [
    {
      "id": 1,
      "name": "Standard Room",
      "slug": "standard-room",
      "description": "Comfortable room with basic amenities",
      "capacity": 2,
      "beds": 1,
      "base_price": 15000.00,
      "amenities": ["WiFi", "TV", "AC"],
      "available_rooms_count": 5
    }
  ],
  "count": 1
}</code>
                    </div>
                </div>
            </div>

            <!-- Get All Rooms -->
            <div class="border rounded-lg">
                <div class="bg-gray-50 px-4 py-3 border-b flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <span class="bg-green-500 text-white text-xs font-bold px-2 py-1 rounded">GET</span>
                        <code class="font-mono text-sm">/properties/{id}/rooms</code>
                    </div>
                    <span class="text-gray-500 text-sm">List all rooms for a property</span>
                </div>
                <div class="p-4">
                    <p class="text-gray-600 mb-3">Returns all rooms for a property with their current status and room type details.</p>
                    <div class="bg-blue-50 rounded p-3">
                        <strong>Example:</strong> <code class="bg-blue-100 px-2 py-1 rounded">GET {{ url('/api/v1/properties/' . $property->id . '/rooms') }}</code>
                    </div>
                </div>
            </div>
        </div>

        <!-- Availability Section -->
        <div id="availability" class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
                <span class="text-2xl">📅</span> Check Availability
            </h2>

            <div class="border rounded-lg">
                <div class="bg-gray-50 px-4 py-3 border-b flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <span class="bg-yellow-500 text-white text-xs font-bold px-2 py-1 rounded">POST</span>
                        <code class="font-mono text-sm">/rooms/availability</code>
                    </div>
                    <span class="text-gray-500 text-sm">Search available rooms</span>
                </div>
                <div class="p-4">
                    <p class="text-gray-600 mb-3">Search for available rooms based on dates, guest count, and room type. Returns rooms with calculated pricing.</p>
                    
                    <!-- Request Body -->
                    <h4 class="font-semibold text-gray-700 mb-2">Request Body</h4>
                    <div class="bg-gray-800 rounded-lg p-4 overflow-x-auto mb-4">
                        <code class="text-white font-mono text-sm whitespace-pre">{
  "property_id": {{ $property->id }},
  "check_in_date": "{{ now()->addDays(7)->format('Y-m-d') }}",
  "check_out_date": "{{ now()->addDays(10)->format('Y-m-d') }}",
  "guests": 2,
  "room_type_id": 1  // optional
}</code>
                    </div>

                    <!-- Parameters Table -->
                    <h4 class="font-semibold text-gray-700 mb-2">Parameters</h4>
                    <table class="w-full text-sm mb-4">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="text-left px-3 py-2">Parameter</th>
                                <th class="text-left px-3 py-2">Type</th>
                                <th class="text-left px-3 py-2">Required</th>
                                <th class="text-left px-3 py-2">Description</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="border-t">
                                <td class="px-3 py-2"><code>property_id</code></td>
                                <td class="px-3 py-2">integer</td>
                                <td class="px-3 py-2"><span class="text-red-600">Yes</span></td>
                                <td class="px-3 py-2">The hotel property ID</td>
                            </tr>
                            <tr class="border-t bg-gray-50">
                                <td class="px-3 py-2"><code>check_in_date</code></td>
                                <td class="px-3 py-2">date</td>
                                <td class="px-3 py-2"><span class="text-red-600">Yes</span></td>
                                <td class="px-3 py-2">Check-in date (YYYY-MM-DD)</td>
                            </tr>
                            <tr class="border-t">
                                <td class="px-3 py-2"><code>check_out_date</code></td>
                                <td class="px-3 py-2">date</td>
                                <td class="px-3 py-2"><span class="text-red-600">Yes</span></td>
                                <td class="px-3 py-2">Check-out date (YYYY-MM-DD)</td>
                            </tr>
                            <tr class="border-t bg-gray-50">
                                <td class="px-3 py-2"><code>guests</code></td>
                                <td class="px-3 py-2">integer</td>
                                <td class="px-3 py-2"><span class="text-gray-500">No</span></td>
                                <td class="px-3 py-2">Number of guests (filters by capacity)</td>
                            </tr>
                            <tr class="border-t">
                                <td class="px-3 py-2"><code>room_type_id</code></td>
                                <td class="px-3 py-2">integer</td>
                                <td class="px-3 py-2"><span class="text-gray-500">No</span></td>
                                <td class="px-3 py-2">Filter by specific room type</td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- Response -->
                    <h4 class="font-semibold text-gray-700 mb-2">Response</h4>
                    <div class="bg-gray-800 rounded-lg p-4 overflow-x-auto">
                        <code class="text-white font-mono text-sm whitespace-pre">{
  "success": true,
  "search_params": {
    "property_id": {{ $property->id }},
    "property_name": "{{ $property->name }}",
    "check_in_date": "{{ now()->addDays(7)->format('Y-m-d') }}",
    "check_out_date": "{{ now()->addDays(10)->format('Y-m-d') }}",
    "nights": 3
  },
  "available_rooms": [
    {
      "id": 1,
      "room_number": "101",
      "floor": 1,
      "price_per_night": 15000.00,
      "nights": 3,
      "subtotal": 45000.00,
      "tax": {{ 45000 * (($property->tax_rate ?? 0) / 100) }},
      "total_amount": {{ 45000 + (45000 * (($property->tax_rate ?? 0) / 100)) }},
      "currency": "{{ $property->currency ?? '₦' }}",
      "room_type": {
        "id": 1,
        "name": "Standard Room",
        "capacity": 2,
        "beds": 1,
        "amenities": ["WiFi", "TV", "AC"]
      }
    }
  ],
  "count": 1
}</code>
                    </div>
                </div>
            </div>
        </div>

        <!-- Booking Section -->
        <div id="booking" class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
                <span class="text-2xl">✅</span> Create Booking
            </h2>

            <div class="border rounded-lg mb-4">
                <div class="bg-gray-50 px-4 py-3 border-b flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <span class="bg-yellow-500 text-white text-xs font-bold px-2 py-1 rounded">POST</span>
                        <code class="font-mono text-sm">/rooms/book</code>
                    </div>
                    <span class="text-gray-500 text-sm">Create a new booking</span>
                </div>
                <div class="p-4">
                    <p class="text-gray-600 mb-3">Create a new room booking with guest information. The booking is created with "pending" status.</p>
                    
                    <!-- Request Body -->
                    <h4 class="font-semibold text-gray-700 mb-2">Request Body</h4>
                    <div class="bg-gray-800 rounded-lg p-4 overflow-x-auto mb-4">
                        <code class="text-white font-mono text-sm whitespace-pre">{
  "property_id": {{ $property->id }},
  "room_id": 1,
  "check_in_date": "{{ now()->addDays(7)->format('Y-m-d') }}",
  "check_out_date": "{{ now()->addDays(10)->format('Y-m-d') }}",
  "number_of_guests": 2,
  "guest": {
    "first_name": "John",
    "last_name": "Doe",
    "email": "john.doe@example.com",
    "phone": "+234 801 234 5678",
    "address": "123 Main Street",
    "city": "Lagos",
    "country": "Nigeria"
  },
  "special_requests": "Late check-in requested",
  "booking_source": "website"
}</code>
                    </div>

                    <!-- Response -->
                    <h4 class="font-semibold text-gray-700 mb-2">Success Response (201)</h4>
                    <div class="bg-gray-800 rounded-lg p-4 overflow-x-auto">
                        <code class="text-white font-mono text-sm whitespace-pre">{
  "success": true,
  "message": "Booking created successfully",
  "booking": {
    "id": 1,
    "booking_reference": "BKABCD123420260126",
    "status": "pending",
    "check_in_date": "{{ now()->addDays(7)->format('Y-m-d') }}",
    "check_out_date": "{{ now()->addDays(10)->format('Y-m-d') }}",
    "number_of_guests": 2,
    "number_of_nights": 3,
    "room_rate": 15000.00,
    "subtotal": 45000.00,
    "tax": {{ 45000 * (($property->tax_rate ?? 0) / 100) }},
    "total_amount": {{ 45000 + (45000 * (($property->tax_rate ?? 0) / 100)) }},
    "currency": "{{ $property->currency ?? '₦' }}",
    "guest": {
      "id": 1,
      "name": "John Doe",
      "email": "john.doe@example.com",
      "phone": "+234 801 234 5678"
    },
    "room": {
      "id": 1,
      "room_number": "101",
      "room_type": "Standard Room"
    }
  }
}</code>
                    </div>
                </div>
            </div>
        </div>

        <!-- Booking Status & Cancellation -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
                <span class="text-2xl">🔍</span> Booking Status & Cancellation
            </h2>

            <!-- Check Booking Status -->
            <div class="border rounded-lg mb-4">
                <div class="bg-gray-50 px-4 py-3 border-b flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <span class="bg-yellow-500 text-white text-xs font-bold px-2 py-1 rounded">POST</span>
                        <code class="font-mono text-sm">/bookings/status</code>
                    </div>
                    <span class="text-gray-500 text-sm">Check booking status</span>
                </div>
                <div class="p-4">
                    <p class="text-gray-600 mb-3">Allow guests to check their booking status using booking reference and email.</p>
                    <div class="bg-gray-800 rounded-lg p-4 overflow-x-auto">
                        <code class="text-white font-mono text-sm whitespace-pre">{
  "booking_reference": "BKABCD123420260126",
  "email": "john.doe@example.com"
}</code>
                    </div>
                </div>
            </div>

            <!-- Cancel Booking -->
            <div class="border rounded-lg">
                <div class="bg-gray-50 px-4 py-3 border-b flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <span class="bg-yellow-500 text-white text-xs font-bold px-2 py-1 rounded">POST</span>
                        <code class="font-mono text-sm">/bookings/cancel</code>
                    </div>
                    <span class="text-gray-500 text-sm">Cancel a booking</span>
                </div>
                <div class="p-4">
                    <p class="text-gray-600 mb-3">Cancel a booking (only pending or confirmed bookings can be cancelled).</p>
                    <div class="bg-gray-800 rounded-lg p-4 overflow-x-auto">
                        <code class="text-white font-mono text-sm whitespace-pre">{
  "booking_reference": "BKABCD123420260126",
  "email": "john.doe@example.com",
  "reason": "Change of plans"
}</code>
                    </div>
                </div>
            </div>
        </div>

        <!-- API Test Tool -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
                <span class="text-2xl">🧪</span> Quick API Test
            </h2>

            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Select Endpoint</label>
                    <select id="testEndpoint" class="w-full border rounded-lg px-4 py-2" onchange="updateTestUrl()">
                        <option value="properties">GET /properties</option>
                        <option value="properties/{{ $property->id }}">GET /properties/{{ $property->id }}</option>
                        <option value="properties/{{ $property->id }}/room-types">GET /properties/{{ $property->id }}/room-types</option>
                        <option value="properties/{{ $property->id }}/rooms">GET /properties/{{ $property->id }}/rooms</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Full URL</label>
                    <div class="flex gap-2">
                        <input type="text" id="testUrl" readonly class="w-full border rounded-lg px-4 py-2 bg-gray-50 font-mono text-sm" value="{{ url('/api/v1/properties') }}">
                        <button onclick="testApi()" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 whitespace-nowrap">
                            🚀 Test
                        </button>
                    </div>
                </div>
            </div>

            <div id="testResult" class="mt-4 hidden">
                <label class="block text-sm font-medium text-gray-700 mb-2">Response</label>
                <pre id="testOutput" class="bg-gray-800 text-white rounded-lg p-4 overflow-x-auto text-sm max-h-96 overflow-y-auto"></pre>
            </div>
        </div>

        <!-- Back Button -->
        <div class="flex justify-between">
            <a href="{{ route('settings.edit', $property) }}" class="bg-gray-600 text-white px-6 py-2 rounded-lg hover:bg-gray-700">
                ← Back to Settings
            </a>
            <button onclick="window.print()" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                🖨️ Print Documentation
            </button>
        </div>
    </div>

    <script>
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => {
                alert('Copied to clipboard!');
            });
        }

        function updateTestUrl() {
            const endpoint = document.getElementById('testEndpoint').value;
            document.getElementById('testUrl').value = '{{ url('/api/v1') }}/' + endpoint;
        }

        async function testApi() {
            const url = document.getElementById('testUrl').value;
            const resultDiv = document.getElementById('testResult');
            const outputPre = document.getElementById('testOutput');

            resultDiv.classList.remove('hidden');
            outputPre.textContent = 'Loading...';

            try {
                const response = await fetch(url, {
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                });
                const data = await response.json();
                outputPre.textContent = JSON.stringify(data, null, 2);
            } catch (error) {
                outputPre.textContent = 'Error: ' + error.message;
            }
        }
    </script>

    <style>
        @media print {
            nav, button, #testResult, .no-print { display: none !important; }
            .shadow { box-shadow: none !important; }
        }
    </style>
</x-app-layout>
