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
            <?php echo e(__('Bookings')); ?>

        </h2>
     <?php $__env->endSlot(); ?>
<div class="mb-6 flex justify-between items-center">
    <h1 class="text-2xl font-bold">Bookings</h1>
    <div class="flex gap-2">
        <?php if(auth()->user()->isAdmin()): ?>
            <button id="deleteSelectedBtn" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 hidden" onclick="deleteSelectedBookings()">
                Delete Selected
            </button>
        <?php endif; ?>
        <a href="<?php echo e(route('properties.bookings.create', $property)); ?>" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            New Booking
        </a>
    </div>
</div>

<!-- Date Filter -->
<div class="mb-6 bg-white rounded-lg shadow p-4">
    <form method="GET" action="<?php echo e(route('properties.bookings.index', $property)); ?>" class="space-y-4">
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
            <a href="<?php echo e(route('properties.bookings.index', $property)); ?>" class="bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400 text-sm font-medium">
                Reset
            </a>
        </div>
    </form>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-100 border-b">
            <tr>
                <th class="px-6 py-3 text-left text-sm font-semibold">
                    <input type="checkbox" id="selectAllCheckbox" onchange="toggleSelectAll(this)" class="w-4 h-4">
                </th>
                <th class="px-6 py-3 text-left text-sm font-semibold">Reference</th>
                <th class="px-6 py-3 text-left text-sm font-semibold">Guest</th>
                <th class="px-6 py-3 text-left text-sm font-semibold">Room</th>
                <th class="px-6 py-3 text-left text-sm font-semibold">Check-in</th>
                <th class="px-6 py-3 text-left text-sm font-semibold">Check-out</th>
                <th class="px-6 py-3 text-left text-sm font-semibold">Status</th>
                <th class="px-6 py-3 text-left text-sm font-semibold">Date Created</th>
                <th class="px-6 py-3 text-left text-sm font-semibold">Total</th>
                <th class="px-6 py-3 text-left text-sm font-semibold">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $bookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $booking): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm">
                        <input type="checkbox" class="booking-checkbox w-4 h-4" value="<?php echo e($booking->id); ?>" onchange="updateDeleteButton()">
                    </td>
                    <td class="px-6 py-4 text-sm"><?php echo e($booking->booking_reference); ?></td>
                    <td class="px-6 py-4 text-sm"><?php echo e($booking->guest->full_name); ?></td>
                    <td class="px-6 py-4 text-sm"><?php echo e($booking->room->room_number ?? 'N/A'); ?></td>
                    <td class="px-6 py-4 text-sm"><?php echo e($booking->check_in_date); ?></td>
                    <td class="px-6 py-4 text-sm"><?php echo e($booking->check_out_date); ?></td>
                    <td class="px-6 py-4 text-sm">
                        <span class="px-3 py-1 rounded-full text-xs font-semibold
                            <?php if($booking->status === 'pending'): ?> bg-yellow-100 text-yellow-800
                            <?php elseif($booking->status === 'confirmed'): ?> bg-blue-100 text-blue-800
                            <?php elseif($booking->status === 'checked_in'): ?> bg-green-100 text-green-800
                            <?php elseif($booking->status === 'checked_out'): ?> bg-gray-100 text-gray-800
                            <?php else: ?> bg-red-100 text-red-800
                            <?php endif; ?>">
                            <?php echo e(ucfirst(str_replace('_', ' ', $booking->status))); ?>

                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm"><?php echo e($booking->created_at->format('M d, Y H:i')); ?></td>
                    <td class="px-6 py-4 text-sm">₦<?php echo e(number_format($booking->total_amount, 2)); ?></td>
                    <td class="px-6 py-4 text-sm">
                        <a href="<?php echo e(route('properties.bookings.show', [$property, $booking])); ?>" class="text-blue-600 hover:text-blue-800">View</a>
                        <?php if($booking->status === 'pending'): ?>
                            <form method="POST" action="<?php echo e(route('bookings.confirm', [$property, $booking])); ?>" class="inline">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="text-green-600 hover:text-green-800 ml-2">Confirm</button>
                            </form>
                        <?php endif; ?>
                        <?php if(auth()->user()->isAdmin()): ?>
                            <form method="POST" action="<?php echo e(route('properties.bookings.destroy', [$property, $booking])); ?>" class="inline" onsubmit="return confirm('Are you sure you want to delete this booking? This action cannot be undone.');">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="text-red-600 hover:text-red-800 ml-2">Delete</button>
                            </form>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="9" class="px-6 py-4 text-center text-gray-500">No bookings found</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script>
function toggleSelectAll(checkbox) {
    const bookingCheckboxes = document.querySelectorAll('.booking-checkbox');
    bookingCheckboxes.forEach(cb => {
        cb.checked = checkbox.checked;
    });
    updateDeleteButton();
}

function updateDeleteButton() {
    const bookingCheckboxes = document.querySelectorAll('.booking-checkbox');
    const selectedCount = Array.from(bookingCheckboxes).filter(cb => cb.checked).length;
    const deleteBtn = document.getElementById('deleteSelectedBtn');

    if (selectedCount > 0) {
        deleteBtn.classList.remove('hidden');
        deleteBtn.textContent = `Delete Selected (${selectedCount})`;
    } else {
        deleteBtn.classList.add('hidden');
    }
}

function deleteSelectedBookings() {
    const bookingCheckboxes = document.querySelectorAll('.booking-checkbox:checked');
    const selectedIds = Array.from(bookingCheckboxes).map(cb => cb.value);

    if (selectedIds.length === 0) {
        alert('Please select at least one booking to delete');
        return;
    }

    if (!confirm(`Are you sure you want to delete ${selectedIds.length} booking(s)? This action cannot be undone.`)) {
        return;
    }

    // Create a form and submit it
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '<?php echo e(route("bookings.deleteMultiple", $property)); ?>';

    // Add CSRF token
    const csrfToken = document.querySelector('input[name="_token"]');
    if (csrfToken) {
        form.appendChild(csrfToken.cloneNode(true));
    }

    // Add booking IDs
    selectedIds.forEach(id => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'booking_ids[]';
        input.value = id;
        form.appendChild(input);
    });

    document.body.appendChild(form);
    form.submit();
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

<?php /**PATH /home/kuwaitcabinhotel/sales-portal.kuwaitcabinhotel.com/resources/views/bookings/index.blade.php ENDPATH**/ ?>