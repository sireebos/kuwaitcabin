<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve(['property' => $property] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
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
                <?php echo e(__('Dashboard')); ?> - <?php echo e($property->name); ?>

            </h2>
            <?php if(auth()->user()->isAdmin()): ?>
            <a href="<?php echo e(route('settings.edit', $property)); ?>" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 text-sm font-medium flex items-center gap-2">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" />
                </svg>
                Hotel Settings
            </a>
            <?php endif; ?>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Hotel Branding Card (Admin Only) -->
            <?php if(auth()->user()->isAdmin()): ?>
            <div class="mb-8 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg shadow-md border border-blue-200 p-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        
                        <div>
                            <h3 class="text-lg font-bold text-gray-800"><?php echo e($property->display_name ?? $property->name); ?></h3>
                            <p class="text-sm text-gray-600"><?php echo e($property->city); ?>, <?php echo e($property->country); ?></p>
                            <p class="text-xs text-gray-500 mt-1">📧 <?php echo e($property->email); ?> | 📞 <?php echo e($property->phone); ?></p>
                        </div>
                    </div>
                    <a href="<?php echo e(route('settings.edit', $property)); ?>" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 font-medium transition flex items-center gap-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" />
                        </svg>
                        Edit Settings
                    </a>
                </div>
            </div>
            <?php endif; ?>

            <!-- Management Quick Access Section (Admin & Receptionist) -->
            <?php if(auth()->user()->isAdmin() || auth()->user()->hasPermission('manage_bookings')): ?>
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Quick Management</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
                    <!-- Room Availability Card -->
                    <a href="<?php echo e(route('properties.bookings.create', $property)); ?>" class="bg-white rounded-lg shadow-lg hover:shadow-xl transition transform hover:scale-105 cursor-pointer border-l-4 border-cyan-500">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-bold text-gray-800">Room Status</h3>
                                <svg class="w-8 h-8 text-cyan-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10.5 1.5H3.75A2.25 2.25 0 001.5 3.75v12.5A2.25 2.25 0 003.75 18.5h12.5a2.25 2.25 0 002.25-2.25V9.5m-15-6h12m-12 3h12m-12 3h6M10 10.5a3 3 0 100-6 3 3 0 000 6z"/>
                                </svg>
                            </div>
                            <p class="text-gray-600 text-sm mb-4">Check available and occupied rooms</p>
                            <div class="flex items-center justify-between">
                                <div>
                                    <div class="text-sm"><span class="text-green-600 font-bold"><?php echo e($availableRooms ?? 0); ?></span> Available</div>
                                    <div class="text-sm"><span class="text-red-600 font-bold"><?php echo e($occupiedRooms ?? 0); ?></span> Occupied</div>
                                </div>
                                <span class="text-cyan-500 font-medium text-sm">View →</span>
                            </div>
                        </div>
                    </a>

                    <!-- Manage Rooms Card -->
                    <a href="<?php echo e(route('properties.rooms.index', $property)); ?>" class="bg-white rounded-lg shadow-lg hover:shadow-xl transition transform hover:scale-105 cursor-pointer border-l-4 border-blue-500">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-bold text-gray-800">Manage Rooms</h3>
                                <svg class="w-8 h-8 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10.5 1.5H3.75A2.25 2.25 0 001.5 3.75v12.5A2.25 2.25 0 003.75 18.5h12.5a2.25 2.25 0 002.25-2.25V9.5m-15-6h12m-12 3h12m-12 3h6M10 10.5a3 3 0 100-6 3 3 0 000 6z"/>
                                </svg>
                            </div>
                            <p class="text-gray-600 text-sm mb-4">Add, edit, or manage individual rooms and their prices</p>
                            <div class="flex items-center justify-between">
                                <span class="text-2xl font-bold text-blue-600"><?php echo e($totalRooms); ?></span>
                                <span class="text-blue-500 font-medium text-sm">Total Rooms →</span>
                            </div>
                        </div>
                    </a>

                    <!-- Manage Room Types Card -->
                    <a href="<?php echo e(route('properties.room-types.index', $property)); ?>" class="bg-white rounded-lg shadow-lg hover:shadow-xl transition transform hover:scale-105 cursor-pointer border-l-4 border-green-500">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-bold text-gray-800">Room Types</h3>
                                <svg class="w-8 h-8 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <p class="text-gray-600 text-sm mb-4">Create and manage room types with base prices</p>
                            <div class="flex items-center justify-between">
                                <span class="text-2xl font-bold text-green-600"><?php echo e($roomTypes->count() ?? 0); ?></span>
                                <span class="text-green-500 font-medium text-sm">Room Types →</span>
                            </div>
                        </div>
                    </a>

                    <!-- Hotel Settings Card -->
                    <a href="<?php echo e(route('settings.edit', $property)); ?>" class="bg-white rounded-lg shadow-lg hover:shadow-xl transition transform hover:scale-105 cursor-pointer border-l-4 border-purple-500">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-bold text-gray-800">Hotel Settings</h3>
                                <svg class="w-8 h-8 text-purple-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <p class="text-gray-600 text-sm mb-4">Change hotel name and upload logo</p>
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-semibold text-purple-600"><?php echo e($property->display_name ?? $property->name); ?></span>
                                <span class="text-purple-500 font-medium text-sm">Settings →</span>
                            </div>
                        </div>
                    </a>

                    <!-- Daily Summary Report Card -->
                    <a href="<?php echo e(route('dashboard.daily-sales-summary', $property)); ?>" class="bg-white rounded-lg shadow-lg hover:shadow-xl transition transform hover:scale-105 cursor-pointer border-l-4 border-indigo-500">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-bold text-gray-800">Daily Summary</h3>
                                <svg class="w-8 h-8 text-indigo-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/>
                                </svg>
                            </div>
                            <p class="text-gray-600 text-sm mb-4">View sales, bookings, and inventory summary</p>
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-semibold text-indigo-600">Today's Report</span>
                                <span class="text-indigo-500 font-medium text-sm">View →</span>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            <?php endif; ?>

            <!-- Key Metrics with Large Icons -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <?php if(auth()->user()->isAdmin()): ?>
                <div class="bg-white rounded-lg shadow-lg hover:shadow-xl transition transform hover:scale-105 cursor-pointer">
                    <div class="bg-gradient-to-br from-blue-400 to-blue-600 p-8 rounded-t-lg flex items-center justify-center">
                        <svg class="w-24 h-24 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.5 1.5H3.75A2.25 2.25 0 001.5 3.75v12.5A2.25 2.25 0 003.75 18.5h12.5a2.25 2.25 0 002.25-2.25V9.5m-15-6h12m-12 3h12m-12 3h6M10 10.5a3 3 0 100-6 3 3 0 000 6z"/>
                        </svg>
                    </div>
                    <div class="p-6 text-center">
                        <h3 class="text-gray-600 text-sm font-medium mb-2">Occupancy Rate</h3>
                        <p class="text-5xl font-bold text-blue-600"><?php echo e(number_format($occupancyRate, 1)); ?>%</p>
                        <p class="text-gray-500 text-xs mt-3"><?php echo e($occupiedRooms); ?> of <?php echo e($totalRooms); ?> rooms</p>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-lg hover:shadow-xl transition transform hover:scale-105 cursor-pointer">
                    <div class="bg-gradient-to-br from-green-400 to-green-600 p-8 rounded-t-lg flex items-center justify-center">
                        <svg class="w-24 h-24 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M12 2a1 1 0 011 1v6h6a1 1 0 110 2h-6v6a1 1 0 11-2 0v-6H5a1 1 0 110-2h6V3a1 1 0 011-1z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="p-6 text-center">
                        <h3 class="text-gray-600 text-sm font-medium mb-2">Today's Check-ins</h3>
                        <p class="text-5xl font-bold text-green-600"><?php echo e($todayCheckIns); ?></p>
                        <p class="text-gray-500 text-xs mt-3">Expected arrivals</p>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-lg hover:shadow-xl transition transform hover:scale-105 cursor-pointer">
                    <div class="bg-gradient-to-br from-orange-400 to-orange-600 p-8 rounded-t-lg flex items-center justify-center">
                        <svg class="w-24 h-24 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="p-6 text-center">
                        <h3 class="text-gray-600 text-sm font-medium mb-2">Today's Check-outs</h3>
                        <p class="text-5xl font-bold text-orange-600"><?php echo e($todayCheckOuts); ?></p>
                        <p class="text-gray-500 text-xs mt-3">Expected departures</p>
                    </div>
                </div>
                <?php endif; ?>

                <?php if(auth()->user()->isAdmin()): ?>
                <div class="bg-white rounded-lg shadow-lg hover:shadow-xl transition transform hover:scale-105 cursor-pointer">
                    <div class="bg-gradient-to-br from-purple-400 to-purple-600 p-8 rounded-t-lg flex items-center justify-center">
                        <svg class="w-24 h-24 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M8.16 2.75a.75.75 0 00-1.32 0l-.478 1.435a.75.75 0 01-.578.578l-1.435.478a.75.75 0 000 1.32l1.435.478c.29.097.52.288.578.578l.478 1.435a.75.75 0 001.32 0l.478-1.435c.097-.29.288-.52.578-.578l1.435-.478a.75.75 0 000-1.32l-1.435-.478a.75.75 0 01-.578-.578l-.478-1.435zM16.5 12.75a.75.75 0 00-1.32 0l-.478 1.435a.75.75 0 01-.578.578l-1.435.478a.75.75 0 000 1.32l1.435.478c.29.097.52.288.578.578l.478 1.435a.75.75 0 001.32 0l.478-1.435c.097-.29.288-.52.578-.578l1.435-.478a.75.75 0 000-1.32l-1.435-.478a.75.75 0 01-.578-.578l-.478-1.435z"/>
                        </svg>
                    </div>
                    <div class="p-6 text-center">
                        <h3 class="text-gray-600 text-sm font-medium mb-2">Today's Revenue</h3>
                        <p class="text-5xl font-bold text-purple-600">₦<?php echo e(number_format($todayRevenue, 2)); ?></p>
                        <p class="text-gray-500 text-xs mt-3">Hotel & Restaurant</p>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <!-- Quick Navigation with Large Icons in Rows -->
            <h2 class="text-3xl font-bold text-gray-800 mb-8">Quick Access</h2>

            <!-- Hotel Management Section - Visible to Admin, Manager, Receptionist -->
            <?php if(auth()->user()->isAdmin() || auth()->user()->hasPermission('manage_bookings') || auth()->user()->hasPermission('checkin_checkout')): ?>
            <div class="mb-12">
                <h3 class="text-2xl font-bold text-gray-700 mb-6 flex items-center">
                    <svg class="w-8 h-8 text-indigo-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10.5 1.5H3.75A2.25 2.25 0 001.5 3.75v12.5A2.25 2.25 0 003.75 18.5h12.5a2.25 2.25 0 002.25-2.25V9.5m-15-6h12m-12 3h12m-12 3h6M10 10.5a3 3 0 100-6 3 3 0 000 6z"/>
                    </svg>
                    Hotel Management
                </h3>
                <div class="flex flex-wrap gap-6">
                    <!-- Manage Bookings -->
                    <a href="<?php echo e(route('properties.bookings.index', $property)); ?>" class="group">
                        <div class="bg-white rounded-xl shadow-lg hover:shadow-2xl transition transform hover:scale-105 overflow-hidden w-48">
                            <div class="bg-gradient-to-br from-indigo-400 to-indigo-600 p-6 flex items-center justify-center h-48 group-hover:from-indigo-500 group-hover:to-indigo-700">
                                <svg class="w-32 h-32 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M6 2a1 1 0 00-1 1v2H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V7a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v2H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"/>
                                </svg>
                            </div>
                            <div class="p-4 text-center">
                                <p class="font-bold text-lg text-gray-800">📅 Manage Bookings</p>
                            </div>
                        </div>
                    </a>

                    <!-- Check-in Guests -->
                    

                    <!-- Check-out Guests -->
                    <a href="<?php echo e(route('checkout.index', $property)); ?>" class="group">
                        <div class="bg-white rounded-xl shadow-lg hover:shadow-2xl transition transform hover:scale-105 overflow-hidden w-48">
                            <div class="bg-gradient-to-br from-red-400 to-red-600 p-6 flex items-center justify-center h-48 group-hover:from-red-500 group-hover:to-red-700">
                                <svg class="w-32 h-32 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="p-4 text-center">
                                <p class="font-bold text-lg text-gray-800">✓ Check Guests Status</p>
                            </div>
                        </div>
                    </a>

                    <!-- Folio Management -->
                    <a href="<?php echo e(route('properties.bookings.index', $property)); ?>" class="group">
                        <div class="bg-white rounded-xl shadow-lg hover:shadow-2xl transition transform hover:scale-105 overflow-hidden w-48">
                            <div class="bg-gradient-to-br from-yellow-400 to-yellow-600 p-6 flex items-center justify-center h-48 group-hover:from-yellow-500 group-hover:to-yellow-700">
                                <svg class="w-32 h-32 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"/>
                                </svg>
                            </div>
                            <div class="p-4 text-center">
                                <p class="font-bold text-lg text-gray-800">💳 Folio</p>
                            </div>
                        </div>
                    </a>

                    <!-- Manage Rooms -->
                    <a href="<?php echo e(route('properties.rooms.index', $property)); ?>" class="group">
                        <div class="bg-white rounded-xl shadow-lg hover:shadow-2xl transition transform hover:scale-105 overflow-hidden w-48">
                            <div class="bg-gradient-to-br from-slate-400 to-slate-600 p-6 flex items-center justify-center h-48 group-hover:from-slate-500 group-hover:to-slate-700">
                                <svg class="w-32 h-32 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10.5 1.5H3.75A2.25 2.25 0 001.5 3.75v12.5A2.25 2.25 0 003.75 18.5h12.5a2.25 2.25 0 002.25-2.25V9.5m-15-6h12m-12 3h12m-12 3h6M10 10.5a3 3 0 100-6 3 3 0 000 6z"/>
                                </svg>
                            </div>
                            <div class="p-4 text-center">
                                <p class="font-bold text-lg text-gray-800">🏠 Manage Rooms</p>
                            </div>
                        </div>
                    </a>

                    <!-- Manage Guests -->
                    <a href="<?php echo e(route('properties.guests.index', $property)); ?>" class="group">
                        <div class="bg-white rounded-xl shadow-lg hover:shadow-2xl transition transform hover:scale-105 overflow-hidden w-48">
                            <div class="bg-gradient-to-br from-violet-400 to-violet-600 p-6 flex items-center justify-center h-48 group-hover:from-violet-500 group-hover:to-violet-700">
                                <svg class="w-32 h-32 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"/>
                                </svg>
                            </div>
                            <div class="p-4 text-center">
                                <p class="font-bold text-lg text-gray-800">👥 Manage Guests</p>
                            </div>
                        </div>
                    </a>

                    <!-- Daily Summary Report -->
                    <a href="<?php echo e(route('admin.daily-summary', $property)); ?>" class="group">
                        <div class="bg-white rounded-xl shadow-lg hover:shadow-2xl transition transform hover:scale-105 overflow-hidden w-48">
                            <div class="bg-gradient-to-br from-teal-400 to-teal-600 p-6 flex items-center justify-center h-48 group-hover:from-teal-500 group-hover:to-teal-700">
                                <svg class="w-32 h-32 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/>
                                </svg>
                            </div>
                            <div class="p-4 text-center">
                                <p class="font-bold text-lg text-gray-800">📊 Daily Summary</p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            <?php endif; ?>

            <!-- Restaurant & Bar Section - Visible to Admin, Manager, Chef, Waiter -->
            <?php if(auth()->user()->isAdmin() || auth()->user()->hasPermission('manage_orders') || auth()->user()->hasPermission('view_menus') || auth()->user()->hasPermission('create_orders')): ?>
            <div class="mb-12">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-2xl font-bold text-gray-700 flex items-center">
                        <svg class="w-8 h-8 text-orange-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                            <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 1 1 0 000 2H4zm-.293 1.293a1 1 0 011.414 0L8 7.414l2.879-2.879a1 1 0 111.414 1.414L9.414 8l2.879 2.879a1 1 0 11-1.414 1.414L8 9.414l-2.879 2.879a1 1 0 01-1.414-1.414L6.586 8 3.707 5.121a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                        Restaurant
                    </h3>
                    <a href="<?php echo e(route('dashboard.daily-sales-summary', $property)); ?>" class="bg-orange-600 text-white px-4 py-2 rounded-lg hover:bg-orange-700 text-sm font-medium transition">
                        Daily Summary
                    </a>
                </div>
                <div class="flex flex-wrap gap-6">
                    <!-- POS System -->
                    <a href="<?php echo e(route('orders.pos', $property)); ?>" class="group">
                        <div class="bg-white rounded-xl shadow-lg hover:shadow-2xl transition transform hover:scale-105 overflow-hidden w-48">
                            <div class="bg-gradient-to-br from-orange-400 to-orange-600 p-6 flex items-center justify-center h-48 group-hover:from-orange-500 group-hover:to-orange-700">
                                <svg class="w-32 h-32 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 6H6.28l-.31-1.243A1 1 0 005 4H3z"/>
                                    <path d="M16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"/>
                                </svg>
                            </div>
                            <div class="p-4 text-center">
                                <p class="font-bold text-lg text-gray-800">🍽️ POS System</p>
                            </div>
                        </div>
                    </a>

                    <!-- Manage Orders -->
                    <a href="<?php echo e(route('properties.orders.index', $property)); ?>" class="group">
                        <div class="bg-white rounded-xl shadow-lg hover:shadow-2xl transition transform hover:scale-105 overflow-hidden w-48">
                            <div class="bg-gradient-to-br from-pink-400 to-pink-600 p-6 flex items-center justify-center h-48 group-hover:from-pink-500 group-hover:to-pink-700">
                                <svg class="w-32 h-32 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 6H6.28l-.31-1.243A1 1 0 005 4H3z"/>
                                    <path d="M16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"/>
                                </svg>
                            </div>
                            <div class="p-4 text-center">
                                <p class="font-bold text-lg text-gray-800">📋 Manage Orders</p>
                            </div>
                        </div>
                    </a>

                    <?php if(auth()->user()->isAdmin()): ?>
                    <!-- Menu Items -->
                    <a href="<?php echo e(route('properties.menus.index', $property)); ?>" class="group">
                        <div class="bg-white rounded-xl shadow-lg hover:shadow-2xl transition transform hover:scale-105 overflow-hidden w-48">
                            <div class="bg-gradient-to-br from-red-400 to-red-600 p-6 flex items-center justify-center h-48 group-hover:from-red-500 group-hover:to-red-700">
                                <svg class="w-32 h-32 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2 4a1 1 0 011-1h6a1 1 0 011 1v12a1 1 0 01-1 1H3a1 1 0 01-1-1V4z"/>
                                    <path d="M13 2a1 1 0 00-1 1v12a2 2 0 002 2h2a2 2 0 002-2V3a1 1 0 10-2 0v12h-2V3a1 1 0 00-1-1z"/>
                                </svg>
                            </div>
                            <div class="p-4 text-center">
                                <p class="font-bold text-lg text-gray-800">📖 Menu Items</p>
                            </div>
                        </div>
                    </a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Bar Section - Visible to Admin, Manager, Bar Manager, Bartender -->
            <?php if(auth()->user()->isAdmin() || auth()->user()->hasPermission('manage_bar_orders') || auth()->user()->hasPermission('create_bar_orders')): ?>
            <div class="mb-12">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-2xl font-bold text-gray-700 flex items-center">
                        <svg class="w-8 h-8 text-purple-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h1a1 1 0 001-1v-6a1 1 0 00-1-1h-1z"/>
                        </svg>
                        Bar
                    </h3>
                    <a href="<?php echo e(route('dashboard.daily-sales-summary', $property)); ?>" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 text-sm font-medium transition">
                        Daily Summary
                    </a>
                </div>
                <div class="flex flex-wrap gap-6">
                    <!-- Bar POS System -->
                    <a href="<?php echo e(route('properties.bar-orders.pos', $property)); ?>" class="group">
                        <div class="bg-white rounded-xl shadow-lg hover:shadow-2xl transition transform hover:scale-105 overflow-hidden w-48">
                            <div class="bg-gradient-to-br from-purple-400 to-purple-600 p-6 flex items-center justify-center h-48 group-hover:from-purple-500 group-hover:to-purple-700">
                                <svg class="w-32 h-32 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 6H6.28l-.31-1.243A1 1 0 005 4H3z"/>
                                    <path d="M16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"/>
                                </svg>
                            </div>
                            <div class="p-4 text-center">
                                <p class="font-bold text-lg text-gray-800">🍸 Bar POS</p>
                            </div>
                        </div>
                    </a>

                    <!-- Manage Bar Orders -->
                    <a href="<?php echo e(route('properties.bar-orders.index', $property)); ?>" class="group">
                        <div class="bg-white rounded-xl shadow-lg hover:shadow-2xl transition transform hover:scale-105 overflow-hidden w-48">
                            <div class="bg-gradient-to-br from-indigo-400 to-indigo-600 p-6 flex items-center justify-center h-48 group-hover:from-indigo-500 group-hover:to-indigo-700">
                                <svg class="w-32 h-32 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 6H6.28l-.31-1.243A1 1 0 005 4H3z"/>
                                    <path d="M16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"/>
                                </svg>
                            </div>
                            <div class="p-4 text-center">
                                <p class="font-bold text-lg text-gray-800">🍹 Bar Orders</p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            <?php endif; ?>

            <!-- Operations Section - Visible to Admin, Manager -->
            <?php if(auth()->user()->isAdmin() || auth()->user()->hasPermission('manage_inventory') || auth()->user()->hasPermission('view_reports')): ?>
            <div class="mb-12">
                <h3 class="text-2xl font-bold text-gray-700 mb-6 flex items-center">
                    <svg class="w-8 h-8 text-teal-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/>
                    </svg>
                    Operations
                </h3>
                <div class="flex flex-wrap gap-6">
                    <!-- Inventory -->
                    <a href="<?php echo e(route('properties.inventory.index', $property)); ?>" class="group">
                        <div class="bg-white rounded-xl shadow-lg hover:shadow-2xl transition transform hover:scale-105 overflow-hidden w-48">
                            <div class="bg-gradient-to-br from-teal-400 to-teal-600 p-6 flex items-center justify-center h-48 group-hover:from-teal-500 group-hover:to-teal-700">
                                <svg class="w-32 h-32 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"/>
                                </svg>
                            </div>
                            <div class="p-4 text-center">
                                <p class="font-bold text-lg text-gray-800">📦 Inventory</p>
                            </div>
                        </div>
                    </a>

                    <!-- Sales Report -->
                    <a href="<?php echo e(route('reports.sales', $property)); ?>" class="group">
                        <div class="bg-white rounded-xl shadow-lg hover:shadow-2xl transition transform hover:scale-105 overflow-hidden w-48">
                            <div class="bg-gradient-to-br from-cyan-400 to-cyan-600 p-6 flex items-center justify-center h-48 group-hover:from-cyan-500 group-hover:to-cyan-700">
                                <svg class="w-32 h-32 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/>
                                </svg>
                            </div>
                            <div class="p-4 text-center">
                                <p class="font-bold text-lg text-gray-800">💰 Sales Report</p>
                            </div>
                        </div>
                    </a>

                    <!-- Occupancy Report -->
                    <a href="<?php echo e(route('reports.occupancy', $property)); ?>" class="group">
                        <div class="bg-white rounded-xl shadow-lg hover:shadow-2xl transition transform hover:scale-105 overflow-hidden w-48">
                            <div class="bg-gradient-to-br from-blue-400 to-blue-600 p-6 flex items-center justify-center h-48 group-hover:from-blue-500 group-hover:to-blue-700">
                                <svg class="w-32 h-32 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/>
                                </svg>
                            </div>
                            <div class="p-4 text-center">
                                <p class="font-bold text-lg text-gray-800">📊 Occupancy Report</p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            <?php endif; ?>

            <!-- Admin Settings Section - Visible to Admin only -->
            <?php if(auth()->user()->isAdmin()): ?>
            <div class="mb-12">
                <h3 class="text-2xl font-bold text-gray-700 mb-6 flex items-center">
                    <svg class="w-8 h-8 text-gray-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/>
                    </svg>
                    Admin Settings
                </h3>
                <div class="flex flex-wrap gap-6">
                    <!-- Custom Fields - Guests -->
                    <a href="<?php echo e(route('properties.custom-fields.index', [$property, 'guest'])); ?>" class="group">
                        <div class="bg-white rounded-xl shadow-lg hover:shadow-2xl transition transform hover:scale-105 overflow-hidden w-48">
                            <div class="bg-gradient-to-br from-amber-400 to-amber-600 p-6 flex items-center justify-center h-48 group-hover:from-amber-500 group-hover:to-amber-700">
                                <svg class="w-32 h-32 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M5.5 13a3.5 3.5 0 01-.369-6.98 4 4 0 117.753-1.3A4.5 4.5 0 1113.5 13H11V9.413l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 9.414V13H5.5z"/>
                                </svg>
                            </div>
                            <div class="p-4 text-center">
                                <p class="font-bold text-lg text-gray-800">⚙️ Guest Fields</p>
                            </div>
                        </div>
                    </a>

                    <!-- Custom Fields - Inventory -->
                    <a href="<?php echo e(route('properties.custom-fields.index', [$property, 'inventory'])); ?>" class="group">
                        <div class="bg-white rounded-xl shadow-lg hover:shadow-2xl transition transform hover:scale-105 overflow-hidden w-48">
                            <div class="bg-gradient-to-br from-lime-400 to-lime-600 p-6 flex items-center justify-center h-48 group-hover:from-lime-500 group-hover:to-lime-700">
                                <svg class="w-32 h-32 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M5.5 13a3.5 3.5 0 01-.369-6.98 4 4 0 117.753-1.3A4.5 4.5 0 1113.5 13H11V9.413l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 9.414V13H5.5z"/>
                                </svg>
                            </div>
                            <div class="p-4 text-center">
                                <p class="font-bold text-lg text-gray-800">⚙️ Inventory Fields</p>
                            </div>
                        </div>
                    </a>

                    <!-- Daily Summary -->
                    <a href="<?php echo e(route('admin.daily-summary', $property)); ?>" class="group">
                        <div class="bg-white rounded-xl shadow-lg hover:shadow-2xl transition transform hover:scale-105 overflow-hidden w-48">
                            <div class="bg-gradient-to-br from-rose-400 to-rose-600 p-6 flex items-center justify-center h-48 group-hover:from-rose-500 group-hover:to-rose-700">
                                <svg class="w-32 h-32 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h1a1 1 0 001-1v-6a1 1 0 00-1-1h-1z"/>
                                </svg>
                            </div>
                            <div class="p-4 text-center">
                                <p class="font-bold text-lg text-gray-800">📊 Daily Summary</p>
                            </div>
                        </div>
                    </a>

                    <!-- Food Cost Report -->
                    <a href="<?php echo e(route('reports.food-cost', $property)); ?>" class="group">
                        <div class="bg-white rounded-xl shadow-lg hover:shadow-2xl transition transform hover:scale-105 overflow-hidden w-48">
                            <div class="bg-gradient-to-br from-orange-400 to-orange-600 p-6 flex items-center justify-center h-48 group-hover:from-orange-500 group-hover:to-orange-700">
                                <svg class="w-32 h-32 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z M4 5a2 2 0 012-2 1 1 0 000 2H4zm12 0h-2a1 1 0 100 2 2 2 0 012-2zM5 9a1 1 0 100-2 1 1 0 000 2zm5 0a1 1 0 100-2 1 1 0 000 2zm5 0a1 1 0 100-2 1 1 0 000 2zM5 13a1 1 0 100-2 1 1 0 000 2zm5 0a1 1 0 100-2 1 1 0 000 2zm5 0a1 1 0 100-2 1 1 0 000 2z"/>
                                </svg>
                            </div>
                            <div class="p-4 text-center">
                                <p class="font-bold text-lg text-gray-800">🍽️ Food Cost</p>
                            </div>
                        </div>
                    </a>

                    <!-- Stock Usage Report -->
                    <a href="<?php echo e(route('reports.stock-usage', $property)); ?>" class="group">
                        <div class="bg-white rounded-xl shadow-lg hover:shadow-2xl transition transform hover:scale-105 overflow-hidden w-48">
                            <div class="bg-gradient-to-br from-purple-400 to-purple-600 p-6 flex items-center justify-center h-48 group-hover:from-purple-500 group-hover:to-purple-700">
                                <svg class="w-32 h-32 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"/>
                                </svg>
                            </div>
                            <div class="p-4 text-center">
                                <p class="font-bold text-lg text-gray-800">📦 Stock Usage</p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            <?php endif; ?>

            <!-- Pending Actions -->
            

            <!-- Upcoming Check-ins -->
            


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
<?php /**PATH /home/kuwaitcabinhotel/sales-portal.kuwaitcabinhotel.com/resources/views/dashboard.blade.php ENDPATH**/ ?>