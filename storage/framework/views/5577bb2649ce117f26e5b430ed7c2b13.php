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
                <?php echo e(__('Daily Sales Summary')); ?> - <?php echo e($property->name); ?>

            </h2>
            <a href="<?php echo e(route('dashboard', $property)); ?>" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 text-sm font-medium">
                Back to Dashboard
            </a>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Filter Section -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Filter Sales & Bookings</h3>
                <form method="GET" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Date</label>
                            <input type="date" name="date" value="<?php echo e($date); ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Filter by User</label>
                            <?php if(auth()->user()->isAdmin()): ?>
                            <select name="user_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                <option value="">All Users</option>
                                <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($user->id); ?>" <?php echo e($filterUser == $user->id ? 'selected' : ''); ?>>
                                        <?php echo e($user->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php else: ?>
                            <select name="user_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" disabled>
                                <option value="<?php echo e(auth()->user()->id); ?>" selected>
                                    <?php echo e(auth()->user()->name); ?>

                                </option>
                            </select>
                            <input type="hidden" name="user_id" value="<?php echo e(auth()->user()->id); ?>">
                            <?php endif; ?>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Filter by Type</label>
                            <select name="type" id="typeFilter" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                <option value="" <?php echo e(!$filterType ? 'selected' : ''); ?>>All Types</option>
                                <option value="sales" <?php echo e($filterType === 'sales' ? 'selected' : ''); ?>>Sales Only</option>
                                <option value="bookings" <?php echo e($filterType === 'bookings' ? 'selected' : ''); ?>>Bookings Only</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Filter by Status</label>
                            <select name="status" id="statusFilter" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                <option value="" <?php echo e(!$filterStatus ? 'selected' : ''); ?>>All Status</option>
                                <option value="pending" <?php echo e($filterStatus === 'pending' ? 'selected' : ''); ?>>Pending</option>
                                <option value="confirmed" <?php echo e($filterStatus === 'confirmed' ? 'selected' : ''); ?>>Confirmed</option>
                                <option value="completed" <?php echo e($filterStatus === 'completed' ? 'selected' : ''); ?>>Completed</option>
                                <option value="closed" <?php echo e($filterStatus === 'closed' ? 'selected' : ''); ?>>Closed</option>
                                <option value="preparing" <?php echo e($filterStatus === 'preparing' ? 'selected' : ''); ?>>Preparing</option>
                                <option value="ready" <?php echo e($filterStatus === 'ready' ? 'selected' : ''); ?>>Ready</option>
                                <option value="checked_in" <?php echo e($filterStatus === 'checked_in' ? 'selected' : ''); ?>>Checked In</option>
                                <option value="checked_out" <?php echo e($filterStatus === 'checked_out' ? 'selected' : ''); ?>>Checked Out</option>
                                <option value="cancelled" <?php echo e($filterStatus === 'cancelled' ? 'selected' : ''); ?>>Cancelled</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex gap-3">
                        <button type="submit" class="bg-blue-600 text-white px-8 py-2 rounded-lg hover:bg-blue-700 font-medium transition">
                            Apply Filters
                        </button>
                        <a href="<?php echo e(route('dashboard.daily-sales-summary', $property)); ?>" class="bg-gray-400 text-white px-8 py-2 rounded-lg hover:bg-gray-500 font-medium transition">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Total Sales -->
                <?php if(!$filterType || $filterType === 'sales'): ?>
                <div class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-lg shadow-md p-6 border-l-4 border-orange-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Total Sales</p>
                            <p class="text-3xl font-bold text-orange-600 mt-2">₦<?php echo e(number_format($totalSalesAmount, 2)); ?></p>
                            <p class="text-xs text-gray-500 mt-1"><?php echo e($orders->count()); ?> orders</p>
                        </div>
                        <svg class="w-12 h-12 text-orange-300" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 6H6.28l-.31-1.243A1 1 0 005 4H3z"/>
                        </svg>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Total Bookings -->
                <?php if(!$filterType || $filterType === 'bookings'): ?>
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg shadow-md p-6 border-l-4 border-blue-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Total Bookings</p>
                            <p class="text-3xl font-bold text-blue-600 mt-2">₦<?php echo e(number_format($totalBookingsAmount, 2)); ?></p>
                            <p class="text-xs text-gray-500 mt-1"><?php echo e($bookings->count()); ?> bookings</p>
                        </div>
                        <svg class="w-12 h-12 text-blue-300" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"/>
                        </svg>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Grand Total -->
                <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-lg shadow-md p-6 border-l-4 border-green-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Grand Total</p>
                            <p class="text-3xl font-bold text-green-600 mt-2">₦<?php echo e(number_format($totalAmount, 2)); ?></p>
                            <p class="text-xs text-gray-500 mt-1"><?php echo e($orders->count() + $bookings->count()); ?> transactions</p>
                        </div>
                        <svg class="w-12 h-12 text-green-300" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M8.16 5.314l4.897-4.897a1 1 0 011.414 0l7.071 7.071a1 1 0 01-1.414 1.414L13.757 7.757V13a1 1 0 11-2 0V7.757L2.586 17.928a1 1 0 01-1.414-1.414l7.071-7.071a1 1 0 010-1.414l4.897-4.897a1 1 0 00-1.414-1.414L8.16 5.314z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Tabs for Sales and Bookings -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="flex border-b border-gray-200">
                    <?php if(!$filterType || $filterType === 'sales'): ?>
                    <button onclick="switchTab('sales')" id="salesTab" class="flex-1 px-6 py-4 font-semibold text-blue-600 border-b-2 border-blue-600 cursor-pointer hover:bg-gray-50">
                        Sales (<?php echo e($orders->count()); ?>)
                    </button>
                    <?php endif; ?>

                    <?php if(!$filterType || $filterType === 'bookings'): ?>
                    <button onclick="switchTab('bookings')" id="bookingsTab" class="flex-1 px-6 py-4 font-semibold <?php echo e(!$filterType || $filterType === 'bookings' ? 'text-gray-600 border-b-2 border-transparent' : 'text-blue-600 border-b-2 border-blue-600'); ?> cursor-pointer hover:bg-gray-50">
                        Bookings (<?php echo e($bookings->count()); ?>)
                    </button>
                    <?php endif; ?>
                </div>

                <!-- Sales Tab Content -->
                <div id="salesContent" class="overflow-x-auto <?php echo e($filterType === 'bookings' ? 'hidden' : ''); ?>">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-100 border-b">
                            <tr>
                                <th class="px-6 py-3 text-left font-semibold">Order ID</th>
                                <th class="px-6 py-3 text-left font-semibold">User</th>
                                <th class="px-6 py-3 text-left font-semibold">Items</th>
                                <th class="px-6 py-3 text-left font-semibold">Amount</th>
                                <th class="px-6 py-3 text-left font-semibold">Type</th>
                                <th class="px-6 py-3 text-left font-semibold">Status</th>
                                <th class="px-6 py-3 text-left font-semibold">Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-6 py-3 font-semibold text-blue-600">#<?php echo e($order->id); ?></td>
                                <td class="px-6 py-3"><?php echo e($order->user?->name ?? 'N/A'); ?></td>
                                <td class="px-6 py-3"><?php echo e($order->items->count()); ?> items</td>
                                <td class="px-6 py-3 font-semibold text-green-600">₦<?php echo e(number_format($order->total, 2)); ?></td>
                                <td class="px-6 py-3">
                                    <span class="px-2 py-1 rounded text-xs font-semibold <?php echo e($order->order_type === 'restaurant' ? 'bg-orange-100 text-orange-800' : 'bg-purple-100 text-purple-800'); ?>">
                                        <?php echo e(ucfirst($order->order_type)); ?>

                                    </span>
                                </td>
                                <td class="px-6 py-3">
                                    <span class="px-2 py-1 rounded text-xs font-semibold 
                                        <?php if($order->status === 'pending'): ?> bg-yellow-100 text-yellow-800
                                        <?php elseif($order->status === 'preparing'): ?> bg-blue-100 text-blue-800
                                        <?php elseif($order->status === 'ready'): ?> bg-green-100 text-green-800
                                        <?php elseif($order->status === 'closed'): ?> bg-gray-100 text-gray-800
                                        <?php else: ?> bg-gray-100 text-gray-800
                                        <?php endif; ?>">
                                        <?php echo e(ucfirst($order->status)); ?>

                                    </span>
                                </td>
                                <td class="px-6 py-3 text-gray-600"><?php echo e($order->created_at->format('H:i')); ?></td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="7" class="px-6 py-4 text-center text-gray-500">No sales for this date</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Bookings Tab Content -->
                <div id="bookingsContent" class="overflow-x-auto <?php echo e($filterType === 'sales' ? 'hidden' : (!$filterType ? 'hidden' : '')); ?>">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-100 border-b">
                            <tr>
                                <th class="px-6 py-3 text-left font-semibold">Booking ID</th>
                                <th class="px-6 py-3 text-left font-semibold">Guest</th>
                                <th class="px-6 py-3 text-left font-semibold">User</th>
                                <th class="px-6 py-3 text-left font-semibold">Room</th>
                                <th class="px-6 py-3 text-left font-semibold">Amount</th>
                                <th class="px-6 py-3 text-left font-semibold">Status</th>
                                <th class="px-6 py-3 text-left font-semibold">Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $bookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $booking): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-6 py-3 font-semibold text-blue-600">#<?php echo e($booking->id); ?></td>
                                <td class="px-6 py-3"><?php echo e($booking->guest?->full_name ?? 'N/A'); ?></td>
                                <td class="px-6 py-3"><?php echo e($booking->user?->name ?? 'N/A'); ?></td>
                                <td class="px-6 py-3"><?php echo e($booking->room?->room_number ?? 'N/A'); ?></td>
                                <td class="px-6 py-3 font-semibold text-green-600">₦<?php echo e(number_format($booking->total_amount, 2)); ?></td>
                                <td class="px-6 py-3">
                                    <span class="px-2 py-1 rounded text-xs font-semibold 
                                        <?php if($booking->status === 'pending'): ?> bg-yellow-100 text-yellow-800
                                        <?php elseif($booking->status === 'confirmed'): ?> bg-blue-100 text-blue-800
                                        <?php elseif($booking->status === 'checked_in'): ?> bg-green-100 text-green-800
                                        <?php elseif($booking->status === 'checked_out'): ?> bg-gray-100 text-gray-800
                                        <?php else: ?> bg-red-100 text-red-800
                                        <?php endif; ?>">
                                        <?php echo e(ucfirst(str_replace('_', ' ', $booking->status))); ?>

                                    </span>
                                </td>
                                <td class="px-6 py-3 text-gray-600"><?php echo e($booking->created_at->format('H:i')); ?></td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="7" class="px-6 py-4 text-center text-gray-500">No bookings for this date</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        function switchTab(tab) {
            const salesContent = document.getElementById('salesContent');
            const bookingsContent = document.getElementById('bookingsContent');
            const salesTab = document.getElementById('salesTab');
            const bookingsTab = document.getElementById('bookingsTab');

            if (tab === 'sales') {
                salesContent.classList.remove('hidden');
                bookingsContent.classList.add('hidden');
                salesTab.classList.add('border-b-2', 'border-blue-600', 'text-blue-600');
                salesTab.classList.remove('border-transparent', 'text-gray-600');
                bookingsTab.classList.remove('border-b-2', 'border-blue-600', 'text-blue-600');
                bookingsTab.classList.add('border-transparent', 'text-gray-600');
            } else {
                bookingsContent.classList.remove('hidden');
                salesContent.classList.add('hidden');
                bookingsTab.classList.add('border-b-2', 'border-blue-600', 'text-blue-600');
                bookingsTab.classList.remove('border-transparent', 'text-gray-600');
                salesTab.classList.remove('border-b-2', 'border-blue-600', 'text-blue-600');
                salesTab.classList.add('border-transparent', 'text-gray-600');
            }
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

<?php /**PATH /home/kuwaitcabinhotel/sales-portal.kuwaitcabinhotel.com/resources/views/reports/daily-sales-summary.blade.php ENDPATH**/ ?>