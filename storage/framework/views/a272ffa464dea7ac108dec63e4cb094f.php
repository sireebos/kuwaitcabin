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
                🔔 <?php echo e(__('Notifications')); ?>

            </h2>
            <?php if($unreadNotifications->count() > 0): ?>
            <form action="<?php echo e(route('notifications.mark-all-read', $property)); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 transition">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Mark All as Read
                </button>
            </form>
            <?php endif; ?>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <?php if(session('success')): ?>
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <?php echo e(session('success')); ?>

                </div>
            <?php endif; ?>

            <?php if(session('error')): ?>
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <?php echo e(session('error')); ?>

                </div>
            <?php endif; ?>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <?php if($notifications->isEmpty()): ?>
                    <div class="p-12 text-center">
                        <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No Notifications</h3>
                        <p class="text-gray-500">You don't have any notifications yet.</p>
                    </div>
                <?php else: ?>
                    <div class="divide-y divide-gray-200">
                        <?php $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $data = $notification->data;
                                $isUnread = is_null($notification->read_at);
                                $isBooking = ($data['type'] ?? '') === 'new_booking_alert';
                            ?>
                            <div class="p-6 <?php echo e($isUnread ? 'bg-blue-50' : 'bg-white'); ?> hover:bg-gray-50 transition">
                                <div class="flex items-start gap-4">
                                    <!-- Icon -->
                                    <div class="flex-shrink-0">
                                        <?php if($isBooking): ?>
                                            <span class="inline-flex items-center justify-center w-12 h-12 bg-blue-100 text-blue-600 rounded-full">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                            </span>
                                        <?php else: ?>
                                            <span class="inline-flex items-center justify-center w-12 h-12 bg-gray-100 text-gray-600 rounded-full">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            </span>
                                        <?php endif; ?>
                                    </div>

                                    <!-- Content -->
                                    <div class="flex-1 min-w-0">
                                        <?php if($isBooking): ?>
                                            <div class="flex items-center gap-2 mb-2">
                                                <h3 class="text-lg font-semibold text-gray-900">
                                                    New Booking: <?php echo e($data['booking_reference'] ?? 'N/A'); ?>

                                                </h3>
                                                <?php if($isUnread): ?>
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                        New
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                            
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                                <div>
                                                    <p class="text-sm text-gray-500">Guest</p>
                                                    <p class="font-medium text-gray-900"><?php echo e($data['guest_name'] ?? 'N/A'); ?></p>
                                                    <p class="text-sm text-gray-600"><?php echo e($data['guest_email'] ?? ''); ?></p>
                                                    <p class="text-sm text-gray-600"><?php echo e($data['guest_phone'] ?? ''); ?></p>
                                                </div>
                                                <div>
                                                    <p class="text-sm text-gray-500">Room</p>
                                                    <p class="font-medium text-gray-900">Room <?php echo e($data['room_number'] ?? 'N/A'); ?> (<?php echo e($data['room_type'] ?? ''); ?>)</p>
                                                    <p class="text-sm text-gray-600">
                                                        <?php echo e(isset($data['check_in_date']) ? \Carbon\Carbon::parse($data['check_in_date'])->format('M j, Y') : ''); ?> - 
                                                        <?php echo e(isset($data['check_out_date']) ? \Carbon\Carbon::parse($data['check_out_date'])->format('M j, Y') : ''); ?>

                                                    </p>
                                                    <p class="text-sm text-gray-600"><?php echo e($data['number_of_nights'] ?? 0); ?> nights • <?php echo e($data['number_of_guests'] ?? 0); ?> guests</p>
                                                </div>
                                            </div>
                                            
                                            <div class="flex items-center gap-4 mb-4">
                                                <div class="text-xl font-bold text-green-600">
                                                    <?php echo e($data['currency'] ?? '₦'); ?> <?php echo e(number_format($data['total_amount'] ?? 0, 2)); ?>

                                                </div>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                    <?php echo e(($data['status'] ?? '') === 'confirmed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'); ?>">
                                                    <?php echo e(ucfirst($data['status'] ?? 'pending')); ?>

                                                </span>
                                                <span class="text-sm text-gray-500">
                                                    Source: <?php echo e(ucfirst($data['booking_source'] ?? 'website')); ?>

                                                </span>
                                            </div>

                                            <!-- Action Buttons -->
                                            <div class="flex flex-wrap items-center gap-3">
                                                <?php if(isset($data['booking_id'])): ?>
                                                    <a href="<?php echo e(route('properties.bookings.show', [$property, $data['booking_id']])); ?>" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                        </svg>
                                                        View Booking
                                                    </a>
                                                    
                                                    <form action="<?php echo e(route('bookings.notify-email', [$property, $data['booking_id']])); ?>" method="POST" class="inline">
                                                        <?php echo csrf_field(); ?>
                                                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700 transition">
                                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                                            </svg>
                                                            Send Email
                                                        </button>
                                                    </form>
                                                    
                                                    <?php if($property->phone): ?>
                                                        <a href="<?php echo e(route('bookings.notify-whatsapp', [$property, $data['booking_id']])); ?>" target="_blank" class="inline-flex items-center px-4 py-2 bg-emerald-500 text-white text-sm font-medium rounded-md hover:bg-emerald-600 transition">
                                                            <svg class="w-4 h-4 mr-1" viewBox="0 0 24 24" fill="currentColor">
                                                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                                                            </svg>
                                                            WhatsApp
                                                        </a>
                                                    <?php else: ?>
                                                        <span class="inline-flex items-center px-4 py-2 bg-gray-300 text-gray-600 text-sm font-medium rounded-md cursor-not-allowed" title="Add phone number in property settings to enable WhatsApp">
                                                            <svg class="w-4 h-4 mr-1" viewBox="0 0 24 24" fill="currentColor">
                                                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                                                            </svg>
                                                            WhatsApp (No Phone)
                                                        </span>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            </div>
                                        <?php else: ?>
                                            <h3 class="text-lg font-semibold text-gray-900 mb-2"><?php echo e($data['title'] ?? 'Notification'); ?></h3>
                                            <p class="text-gray-600"><?php echo e($data['message'] ?? ''); ?></p>
                                        <?php endif; ?>

                                        <!-- Time & Actions -->
                                        <div class="flex items-center justify-between mt-4 pt-4 border-t border-gray-200">
                                            <span class="text-sm text-gray-500">
                                                <?php echo e($notification->created_at->diffForHumans()); ?> • <?php echo e($notification->created_at->format('M j, Y g:i A')); ?>

                                            </span>
                                            <div class="flex items-center gap-2">
                                                <?php if($isUnread): ?>
                                                    <form action="<?php echo e(route('notifications.mark-read', [$property, $notification->id])); ?>" method="POST" class="inline">
                                                        <?php echo csrf_field(); ?>
                                                        <button type="submit" class="text-sm text-blue-600 hover:text-blue-800">Mark as read</button>
                                                    </form>
                                                <?php endif; ?>
                                                <form action="<?php echo e(route('notifications.destroy', [$property, $notification->id])); ?>" method="POST" class="inline" onsubmit="return confirm('Delete this notification?')">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('DELETE'); ?>
                                                    <button type="submit" class="text-sm text-red-600 hover:text-red-800">Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>

                    <!-- Pagination -->
                    <div class="px-6 py-4 border-t">
                        <?php echo e($notifications->links()); ?>

                    </div>
                <?php endif; ?>
            </div>
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
<?php /**PATH /home/kuwaitcabinhotel/sales-portal.kuwaitcabinhotel.com/resources/views/notifications/index.blade.php ENDPATH**/ ?>