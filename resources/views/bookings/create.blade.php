<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create New Booking') }}
        </h2>
    </x-slot>
<div class="max-w-4xl mx-auto bg-white rounded-lg shadow p-6">
    @if ($errors->any())
        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
            <h3 class="text-red-800 font-semibold mb-2">Validation Errors:</h3>
            <ul class="list-disc list-inside text-red-700 text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('properties.bookings.store', $property) }}">
        @csrf

        <!-- Step 1: Select Dates First -->
        <div class="mb-8 p-6 bg-blue-50 border-2 border-blue-200 rounded-lg">
            <h2 class="text-xl font-bold text-gray-800 mb-4">📅 Step 1: Select Check-in & Check-out Dates</h2>
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Check-in Date *</label>
                    <input type="date" id="check_in_date_input" name="check_in_date" value="{{ old('check_in_date', now()->format('Y-m-d')) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('check_in_date') border-red-500 @enderror">
                    @error('check_in_date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Check-out Date *</label>
                    <input type="date" id="check_out_date_input" name="check_out_date" value="{{ old('check_out_date', now()->addDay()->format('Y-m-d')) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('check_out_date') border-red-500 @enderror">
                    @error('check_out_date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            <button type="button" id="check-availability-btn" class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 font-medium">
                🔍 Check Room Availability
            </button>
        </div>



        <!-- Room Availability Display -->
        <div id="room-availability-section" class="mb-8 hidden">
            <h2 class="text-xl font-bold text-gray-800 mb-4">🏨 Step 2: Select Available Rooms</h2>
            <div id="availability-message" class="mb-4 p-4 rounded-lg border"></div>
            <div id="rooms-availability-grid" class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4"></div>
        </div>

        <!-- Guest Selection Tabs -->
        <div class="mb-6">
            <div class="flex gap-4 mb-4 border-b">
                <button type="button" class="guest-tab-btn active px-4 py-2 border-b-2 border-blue-600 text-blue-600 font-medium" data-tab="existing">
                    Select Existing Guest
                </button>
                <button type="button" class="guest-tab-btn px-4 py-2 border-b-2 border-transparent text-gray-600 font-medium" data-tab="new">
                    Register New Guest
                </button>
            </div>

            <!-- Existing Guest Tab -->
            <div id="existing-guest-tab" class="guest-tab-content">
                <label class="block text-sm font-medium text-gray-700 mb-2">Select Guest</label>
                <select name="guest_id" id="guest_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('guest_id') border-red-500 @enderror">
                    <option value="">-- Select a guest --</option>
                    @foreach ($guests as $guest)
                        <option value="{{ $guest->id }}" @selected(old('guest_id') == $guest->id)>
                            {{ $guest->full_name }} ({{ $guest->email }})
                        </option>
                    @endforeach
                </select>
                @error('guest_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- New Guest Tab -->
            <div id="new-guest-tab" class="guest-tab-content hidden">
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">First Name</label>
                            <input type="text" id="new_first_name" placeholder="First name" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Last Name</label>
                            <input type="text" id="new_last_name" placeholder="Last name" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input type="email" id="new_email" placeholder="Email address" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                        <input type="tel" id="new_phone" placeholder="Phone number" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Address (Optional)</label>
                        <input type="text" id="new_address" placeholder="Address" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                    <button type="button" id="create-guest-btn" class="w-full bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
                        Create Guest & Continue
                    </button>
                </div>
            </div>
        </div>

        <!-- Availability Notification -->
        <div id="availability-notification" class="mb-6 hidden p-4 rounded-lg border">
            <div id="notification-content"></div>
        </div>

        <!-- Multiple Rooms Selection (Hidden until dates are selected) -->
        <div id="rooms-selection-section" class="mb-6 hidden">
            <h2 class="text-xl font-bold text-gray-800 mb-4">🛏️ Step 3: Select Rooms</h2>
            <label class="block text-sm font-medium text-gray-700 mb-2">Select Rooms (You can select multiple rooms)</label>
            <div id="rooms-container" class="space-y-3 mb-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach ($property->rooms as $room)
                        <label class="flex items-center p-3 border rounded-lg cursor-pointer room-label hover:bg-blue-50" data-room-id="{{ $room->id }}">
                            <input type="checkbox" name="room_ids[]" value="{{ $room->id }}"
                                   data-price="{{ $room->getEffectivePrice() }}"
                                   data-room-number="{{ $room->room_number }}"
                                   data-room-type="{{ $room->roomType->name }}"
                                   class="room-checkbox w-4 h-4 text-blue-600 rounded">
                            <span class="ml-3">
                                <span class="font-medium">{{ $room->room_number }}</span>
                                <span class="text-gray-600 text-sm">- {{ $room->roomType->name }}</span>
                                <span class="text-blue-600 font-semibold ml-2">₦{{ number_format($room->getEffectivePrice(), 2) }}/night</span>
                            </span>
                            <span class="ml-auto text-xs font-semibold px-2 py-1 rounded room-status-badge bg-gray-100 text-gray-800">
                                Checking...
                            </span>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- Selected Rooms Summary -->
            <div id="selected-rooms-summary" class="bg-blue-50 border border-blue-200 rounded-lg p-4 hidden">
                <h3 class="font-semibold text-blue-900 mb-2">Selected Rooms:</h3>
                <div id="selected-rooms-list" class="space-y-1"></div>
            </div>

            @error('room_ids')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Number of Guests</label>
            <input type="number" name="number_of_guests" value="{{ old('number_of_guests', 1) }}" min="1" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('number_of_guests') border-red-500 @enderror">
            @error('number_of_guests')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Room Rates for Selected Rooms -->
        <div id="room-rates-container" class="mb-6 hidden">
            <label class="block text-sm font-medium text-gray-700 mb-2">Room Rates (per night)</label>
            <div id="rates-list" class="space-y-3"></div>
        </div>

        <!-- Default Room Rate (for single room) -->
        <div id="single-room-rate" class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Room Rate (per night)</label>
            <input type="number" name="room_rate" value="{{ old('room_rate') }}" step="0.01" min="0" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('room_rate') border-red-500 @enderror">
            @error('room_rate')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Special Requests</label>
            <textarea name="special_requests" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">{{ old('special_requests') }}</textarea>
        </div>

        <div class="flex gap-4">
            <button type="submit" id="submit-btn" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 disabled:bg-gray-400 disabled:cursor-not-allowed font-medium flex items-center gap-2">
                📅 Book Now
            </button>
            <a href="{{ route('properties.bookings.index', $property) }}" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-400">
                Cancel
            </a>
        </div>
    </form>
</div>

<script>
// Toast notification helper
function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    const bgColor = type === 'success' ? 'bg-green-500' : type === 'error' ? 'bg-red-500' : 'bg-blue-500';
    toast.className = `fixed top-4 right-4 ${bgColor} text-white px-6 py-3 rounded-lg shadow-lg z-50`;
    toast.textContent = message;
    document.body.appendChild(toast);

    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transition = 'opacity 0.3s';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

document.addEventListener('DOMContentLoaded', function() {
    const tabButtons = document.querySelectorAll('.guest-tab-btn');
    const tabContents = document.querySelectorAll('.guest-tab-content');
    const createGuestBtn = document.getElementById('create-guest-btn');
    const guestIdSelect = document.getElementById('guest_id');
    const checkAvailabilityBtn = document.getElementById('check-availability-btn');
    const checkInInput = document.getElementById('check_in_date_input');
    const checkOutInput = document.getElementById('check_out_date_input');
    const roomAvailabilitySection = document.getElementById('room-availability-section');
    const availabilityMessage = document.getElementById('availability-message');
    const roomsAvailabilityGrid = document.getElementById('rooms-availability-grid');
    const roomsSelectionSection = document.getElementById('rooms-selection-section');
    const roomCheckboxes = document.querySelectorAll('.room-checkbox');
    const selectedRoomsSummary = document.getElementById('selected-rooms-summary');
    const selectedRoomsList = document.getElementById('selected-rooms-list');
    const roomRatesContainer = document.getElementById('room-rates-container');
    const ratesList = document.getElementById('rates-list');
    const singleRoomRate = document.getElementById('single-room-rate');
    const submitBtn = document.getElementById('submit-btn');

    // Tab switching
    tabButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const tab = this.dataset.tab;
            tabButtons.forEach(b => b.classList.remove('active', 'border-blue-600', 'text-blue-600'));
            tabContents.forEach(c => c.classList.add('hidden'));
            this.classList.add('active', 'border-blue-600', 'text-blue-600');
            document.getElementById(tab + '-guest-tab').classList.remove('hidden');
        });
    });

    // Check availability button
    checkAvailabilityBtn.addEventListener('click', async function() {
        const checkInDate = checkInInput.value;
        const checkOutDate = checkOutInput.value;

        if (!checkInDate || !checkOutDate) {
            showToast('Please select both check-in and check-out dates', 'error');
            return;
        }

        if (new Date(checkInDate) >= new Date(checkOutDate)) {
            showToast('Check-out date must be after check-in date', 'error');
            return;
        }

        await loadRoomAvailability(checkInDate, checkOutDate);
    });

    async function loadRoomAvailability(checkInDate, checkOutDate) {
        try {
            const response = await fetch('{{ route("availability.available-rooms", $property) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                },
                body: JSON.stringify({
                    check_in_date: checkInDate,
                    check_out_date: checkOutDate
                })
            });

            const data = await response.json();

            if (data.success) {
                displayRoomAvailability(data.rooms);
                roomAvailabilitySection.classList.remove('hidden');
                roomsSelectionSection.classList.remove('hidden');
                updateRoomStatusBadges(data.rooms);
            } else {
                showToast('Error loading room availability', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            showToast('Error checking availability', 'error');
        }
    }

    function displayRoomAvailability(rooms) {
        const availableCount = rooms.filter(r => r.available).length;
        const totalCount = rooms.length;

        if (availableCount === 0) {
            availabilityMessage.className = 'mb-4 p-4 rounded-lg border bg-red-50 border-red-200';
            availabilityMessage.innerHTML = `<div class="text-red-800"><strong>❌ No Rooms Available</strong> for the selected dates. Please try different dates.</div>`;
        } else {
            availabilityMessage.className = 'mb-4 p-4 rounded-lg border bg-green-50 border-green-200';
            availabilityMessage.innerHTML = `<div class="text-green-800"><strong>✅ ${availableCount} of ${totalCount} rooms available</strong> for the selected dates.</div>`;
        }
    }

    function updateRoomStatusBadges(rooms) {
        rooms.forEach(room => {
            const roomLabel = document.querySelector(`[data-room-id="${room.id}"]`);
            if (roomLabel) {
                const badge = roomLabel.querySelector('.room-status-badge');
                const checkbox = roomLabel.querySelector('.room-checkbox');

                if (room.available) {
                    badge.textContent = '✅ Available';
                    badge.className = 'room-status-badge bg-green-100 text-green-800 text-xs font-semibold px-2 py-1 rounded';
                    checkbox.disabled = false;
                    roomLabel.classList.remove('opacity-60', 'cursor-not-allowed');
                } else {
                    badge.textContent = '❌ Booked';
                    badge.className = 'room-status-badge bg-red-100 text-red-800 text-xs font-semibold px-2 py-1 rounded';
                    checkbox.disabled = true;
                    roomLabel.classList.add('opacity-60', 'cursor-not-allowed');
                }
            }
        });
    }

    async function checkRoomAvailability() {
        const selectedRooms = Array.from(roomCheckboxes).filter(cb => cb.checked);
        const checkInDate = checkInInput.value;
        const checkOutDate = checkOutInput.value;

        if (selectedRooms.length === 0 || !checkInDate || !checkOutDate) {
            submitBtn.disabled = false;
            return;
        }

        try {
            const response = await fetch('{{ route("availability.check", $property) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                },
                body: JSON.stringify({
                    room_ids: selectedRooms.map(r => r.value),
                    check_in_date: checkInDate,
                    check_out_date: checkOutDate
                })
            });

            const data = await response.json();

            if (data.success && data.all_available) {
                submitBtn.disabled = false;
            } else {
                submitBtn.disabled = true;
            }
        } catch (error) {
            console.error('Error checking availability:', error);
        }
    }

    function updateSelectedRooms() {
        const selectedRooms = Array.from(roomCheckboxes).filter(cb => cb.checked);

        if (selectedRooms.length === 0) {
            selectedRoomsSummary.classList.add('hidden');
            roomRatesContainer.classList.add('hidden');
            singleRoomRate.classList.remove('hidden');
            ratesList.innerHTML = '';
            availabilityNotification.classList.add('hidden');
            return;
        }

        // Show selected rooms summary
        selectedRoomsSummary.classList.remove('hidden');
        selectedRoomsList.innerHTML = selectedRooms.map(room =>
            `<div class="text-sm text-blue-800">• ${room.dataset.roomNumber} - ${room.dataset.roomType} (₦${parseFloat(room.dataset.price).toLocaleString('en-NG', {minimumFractionDigits: 2})})</div>`
        ).join('');

        // Show room rates container if multiple rooms
        if (selectedRooms.length > 1) {
            singleRoomRate.classList.add('hidden');
            roomRatesContainer.classList.remove('hidden');

            ratesList.innerHTML = selectedRooms.map(room => `
                <div class="flex items-center gap-3">
                    <label class="text-sm font-medium text-gray-700 w-32">${room.dataset.roomNumber}:</label>
                    <input type="number" name="room_rates[${room.value}]" value="${parseFloat(room.dataset.price).toFixed(2)}"
                           step="0.01" min="0" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
            `).join('');
        } else {
            // Single room - use default room rate field
            singleRoomRate.classList.remove('hidden');
            roomRatesContainer.classList.add('hidden');
            const roomRateInput = document.querySelector('input[name="room_rate"]');
            roomRateInput.value = parseFloat(selectedRooms[0].dataset.price).toFixed(2);
            roomRateInput.style.backgroundColor = '#e8f5e9';
            setTimeout(() => {
                roomRateInput.style.backgroundColor = '';
            }, 2000);
        }
    }

    roomCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateSelectedRooms);
    });

    // Check availability when dates change
    checkInInput.addEventListener('change', checkRoomAvailability);
    checkOutInput.addEventListener('change', checkRoomAvailability);

    // Create guest inline
    createGuestBtn.addEventListener('click', async function() {
        const firstName = document.getElementById('new_first_name').value.trim();
        const lastName = document.getElementById('new_last_name').value.trim();
        const email = document.getElementById('new_email').value.trim();
        const phone = document.getElementById('new_phone').value.trim();
        const address = document.getElementById('new_address').value.trim();

        if (!firstName || !lastName || !email || !phone) {
            showToast('Please fill in all required fields (First Name, Last Name, Email, Phone)', 'error');
            return;
        }

        try {
            const response = await fetch('{{ route("guests.quickCreate", $property) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                },
                body: JSON.stringify({
                    first_name: firstName,
                    last_name: lastName,
                    email: email,
                    phone: phone,
                    address: address
                })
            });

            const data = await response.json();

            if (data.success) {
                // Add new guest to select dropdown
                const option = document.createElement('option');
                option.value = data.guest.id;
                option.textContent = `${data.guest.first_name} ${data.guest.last_name} (${data.guest.email})`;
                option.selected = true;
                guestIdSelect.appendChild(option);

                // Switch back to existing guest tab
                document.querySelector('[data-tab="existing"]').click();

                showToast('Guest created successfully!', 'success');
            } else {
                showToast('Error: ' + (data.message || 'Failed to create guest'), 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            showToast('An error occurred while creating the guest', 'error');
        }
    });

    // Add loading state to form submission
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        // Validate that dates are selected
        if (!checkInInput.value || !checkOutInput.value) {
            e.preventDefault();
            showToast('Please select both check-in and check-out dates', 'error');
            return false;
        }

        // Validate that a guest is selected
        if (!guestIdSelect.value) {
            e.preventDefault();
            showToast('Please select a guest', 'error');
            return false;
        }

        // Validate that at least one room is selected
        const selectedRooms = Array.from(roomCheckboxes).filter(cb => cb.checked);
        if (selectedRooms.length === 0) {
            e.preventDefault();
            showToast('Please select at least one room', 'error');
            return false;
        }

        console.log('Form submission data:', {
            check_in_date: checkInInput.value,
            check_out_date: checkOutInput.value,
            guest_id: guestIdSelect.value,
            room_ids: selectedRooms.map(r => r.value),
            number_of_guests: document.querySelector('input[name="number_of_guests"]').value
        });

        const submitBtn = document.getElementById('submit-btn');
        submitBtn.disabled = true;
        submitBtn.textContent = 'Creating Booking...';
    });
});
</script>
</x-app-layout>

