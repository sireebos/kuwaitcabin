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
            <?php echo e(__('Edit Property')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow p-6">
        <h1 class="text-2xl font-bold mb-6">Edit Property: <?php echo e($property->name); ?></h1>

        <form method="POST" action="<?php echo e(route('properties.update', $property)); ?>" class="space-y-6">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PATCH'); ?>

            <!-- Basic Information -->
            <div class="border-b pb-6">
                <h3 class="text-lg font-semibold mb-4">Basic Information</h3>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Property Name *</label>
                        <input type="text" name="name" value="<?php echo e(old('name', $property->name)); ?>" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Slug *</label>
                        <input type="text" name="slug" value="<?php echo e(old('slug', $property->slug)); ?>" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent <?php $__errorArgs = ['slug'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <?php $__errorArgs = ['slug'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea name="description" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"><?php echo e(old('description', $property->description)); ?></textarea>
                </div>
            </div>

            <!-- Address Information -->
            <div class="border-b pb-6">
                <h3 class="text-lg font-semibold mb-4">Address Information</h3>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Address *</label>
                    <input type="text" name="address" value="<?php echo e(old('address', $property->address)); ?>" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent <?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                    <?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="grid grid-cols-2 gap-4 mt-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">City *</label>
                        <input type="text" name="city" value="<?php echo e(old('city', $property->city)); ?>" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent <?php $__errorArgs = ['city'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <?php $__errorArgs = ['city'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">State *</label>
                        <input type="text" name="state" value="<?php echo e(old('state', $property->state)); ?>" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent <?php $__errorArgs = ['state'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <?php $__errorArgs = ['state'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 mt-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Postal Code *</label>
                        <input type="text" name="postal_code" value="<?php echo e(old('postal_code', $property->postal_code)); ?>" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent <?php $__errorArgs = ['postal_code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <?php $__errorArgs = ['postal_code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Country *</label>
                        <input type="text" name="country" value="<?php echo e(old('country', $property->country)); ?>" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent <?php $__errorArgs = ['country'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <?php $__errorArgs = ['country'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="border-b pb-6">
                <h3 class="text-lg font-semibold mb-4">Contact Information</h3>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Phone *</label>
                        <input type="tel" name="phone" value="<?php echo e(old('phone', $property->phone)); ?>" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                        <input type="email" name="email" value="<?php echo e(old('email', $property->email)); ?>" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Website (optional)</label>
                    <input type="text" name="website" value="<?php echo e(old('website', $property->website)); ?>" placeholder="https://example.com" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent <?php $__errorArgs = ['website'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                    <?php $__errorArgs = ['website'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            <!-- Property Settings -->
            <div class="border-b pb-6">
                <h3 class="text-lg font-semibold mb-4">Property Settings</h3>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Total Rooms *</label>
                        <input type="number" name="total_rooms" value="<?php echo e(old('total_rooms', $property->total_rooms)); ?>" required min="1" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent <?php $__errorArgs = ['total_rooms'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <?php $__errorArgs = ['total_rooms'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Currency *</label>
                        <select name="currency_select" id="currency_select" onchange="handleCurrencyChange()" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">-- Select Currency --</option>
                            <optgroup label="Popular Currencies">
                                <option value="$" <?php echo e(old('currency', $property->currency) == '$' ? 'selected' : ''); ?>>$ - US Dollar (USD)</option>
                                <option value="€" <?php echo e(old('currency', $property->currency) == '€' ? 'selected' : ''); ?>>€ - Euro (EUR)</option>
                                <option value="£" <?php echo e(old('currency', $property->currency) == '£' ? 'selected' : ''); ?>>£ - British Pound (GBP)</option>
                                <option value="₦" <?php echo e(old('currency', $property->currency) == '₦' ? 'selected' : ''); ?>>₦ - Nigerian Naira (NGN)</option>
                                <option value="KD" <?php echo e(old('currency', $property->currency) == 'KD' ? 'selected' : ''); ?>>KD - Kuwaiti Dinar (KWD)</option>
                            </optgroup>
                            <optgroup label="Middle East">
                                <option value="د.ك" <?php echo e(old('currency', $property->currency) == 'د.ك' ? 'selected' : ''); ?>>د.ك - Kuwaiti Dinar (Arabic)</option>
                                <option value="AED" <?php echo e(old('currency', $property->currency) == 'AED' ? 'selected' : ''); ?>>AED - UAE Dirham</option>
                                <option value="SAR" <?php echo e(old('currency', $property->currency) == 'SAR' ? 'selected' : ''); ?>>SAR - Saudi Riyal</option>
                                <option value="QAR" <?php echo e(old('currency', $property->currency) == 'QAR' ? 'selected' : ''); ?>>QAR - Qatari Riyal</option>
                                <option value="BHD" <?php echo e(old('currency', $property->currency) == 'BHD' ? 'selected' : ''); ?>>BHD - Bahraini Dinar</option>
                                <option value="OMR" <?php echo e(old('currency', $property->currency) == 'OMR' ? 'selected' : ''); ?>>OMR - Omani Rial</option>
                            </optgroup>
                            <optgroup label="Asia">
                                <option value="¥" <?php echo e(old('currency', $property->currency) == '¥' ? 'selected' : ''); ?>>¥ - Japanese Yen / Chinese Yuan</option>
                                <option value="₹" <?php echo e(old('currency', $property->currency) == '₹' ? 'selected' : ''); ?>>₹ - Indian Rupee (INR)</option>
                                <option value="₱" <?php echo e(old('currency', $property->currency) == '₱' ? 'selected' : ''); ?>>₱ - Philippine Peso (PHP)</option>
                                <option value="฿" <?php echo e(old('currency', $property->currency) == '฿' ? 'selected' : ''); ?>>฿ - Thai Baht (THB)</option>
                                <option value="₫" <?php echo e(old('currency', $property->currency) == '₫' ? 'selected' : ''); ?>>₫ - Vietnamese Dong (VND)</option>
                                <option value="RM" <?php echo e(old('currency', $property->currency) == 'RM' ? 'selected' : ''); ?>>RM - Malaysian Ringgit (MYR)</option>
                                <option value="S$" <?php echo e(old('currency', $property->currency) == 'S$' ? 'selected' : ''); ?>>S$ - Singapore Dollar (SGD)</option>
                            </optgroup>
                            <optgroup label="Africa">
                                <option value="R" <?php echo e(old('currency', $property->currency) == 'R' ? 'selected' : ''); ?>>R - South African Rand (ZAR)</option>
                                <option value="KSh" <?php echo e(old('currency', $property->currency) == 'KSh' ? 'selected' : ''); ?>>KSh - Kenyan Shilling (KES)</option>
                                <option value="GH₵" <?php echo e(old('currency', $property->currency) == 'GH₵' ? 'selected' : ''); ?>>GH₵ - Ghanaian Cedi (GHS)</option>
                                <option value="E£" <?php echo e(old('currency', $property->currency) == 'E£' ? 'selected' : ''); ?>>E£ - Egyptian Pound (EGP)</option>
                            </optgroup>
                            <optgroup label="Americas">
                                <option value="C$" <?php echo e(old('currency', $property->currency) == 'C$' ? 'selected' : ''); ?>>C$ - Canadian Dollar (CAD)</option>
                                <option value="A$" <?php echo e(old('currency', $property->currency) == 'A$' ? 'selected' : ''); ?>>A$ - Australian Dollar (AUD)</option>
                                <option value="R$" <?php echo e(old('currency', $property->currency) == 'R$' ? 'selected' : ''); ?>>R$ - Brazilian Real (BRL)</option>
                                <option value="MX$" <?php echo e(old('currency', $property->currency) == 'MX$' ? 'selected' : ''); ?>>MX$ - Mexican Peso (MXN)</option>
                            </optgroup>
                            <optgroup label="Europe">
                                <option value="CHF" <?php echo e(old('currency', $property->currency) == 'CHF' ? 'selected' : ''); ?>>CHF - Swiss Franc</option>
                                <option value="kr" <?php echo e(old('currency', $property->currency) == 'kr' ? 'selected' : ''); ?>>kr - Swedish/Norwegian Krona</option>
                                <option value="zł" <?php echo e(old('currency', $property->currency) == 'zł' ? 'selected' : ''); ?>>zł - Polish Zloty (PLN)</option>
                                <option value="₽" <?php echo e(old('currency', $property->currency) == '₽' ? 'selected' : ''); ?>>₽ - Russian Ruble (RUB)</option>
                                <option value="₺" <?php echo e(old('currency', $property->currency) == '₺' ? 'selected' : ''); ?>>₺ - Turkish Lira (TRY)</option>
                            </optgroup>
                            <option value="custom">✏️ Custom Currency...</option>
                        </select>
                        <input type="text" name="currency" id="currency_input" value="<?php echo e(old('currency', $property->currency)); ?>" required maxlength="10" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent mt-2 <?php $__errorArgs = ['currency'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" style="display: none;" placeholder="Enter custom currency symbol">
                        <?php $__errorArgs = ['currency'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        <p class="text-xs text-gray-500 mt-1">This symbol will be displayed with all prices</p>
                    </div>
                </div>

                <script>
                    function handleCurrencyChange() {
                        const select = document.getElementById('currency_select');
                        const input = document.getElementById('currency_input');
                        
                        if (select.value === 'custom') {
                            input.style.display = 'block';
                            input.value = '';
                            input.focus();
                        } else if (select.value) {
                            input.style.display = 'none';
                            input.value = select.value;
                        } else {
                            input.style.display = 'none';
                            input.value = '';
                        }
                    }
                    
                    // On page load, check if current currency matches any option
                    document.addEventListener('DOMContentLoaded', function() {
                        const select = document.getElementById('currency_select');
                        const input = document.getElementById('currency_input');
                        const currentCurrency = input.value;
                        
                        let found = false;
                        for (let option of select.options) {
                            if (option.value === currentCurrency && option.value !== 'custom') {
                                select.value = currentCurrency;
                                found = true;
                                break;
                            }
                        }
                        
                        if (!found && currentCurrency) {
                            select.value = 'custom';
                            input.style.display = 'block';
                        }
                    });
                </script>

                <div class="grid grid-cols-2 gap-4 mt-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Check-in Time (24h) *</label>
                        <input type="number" name="check_in_time" value="<?php echo e(old('check_in_time', $property->check_in_time)); ?>" required min="0" max="24" step="0.5" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent <?php $__errorArgs = ['check_in_time'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <?php $__errorArgs = ['check_in_time'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Check-out Time (24h) *</label>
                        <input type="number" name="check_out_time" value="<?php echo e(old('check_out_time', $property->check_out_time)); ?>" required min="0" max="24" step="0.5" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent <?php $__errorArgs = ['check_out_time'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <?php $__errorArgs = ['check_out_time'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                <div class="mt-4">
                    <label class="flex items-center">
                        <input type="checkbox" name="is_active" value="1" <?php echo e(old('is_active', $property->is_active) ? 'checked' : ''); ?> class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <span class="ml-2 text-sm text-gray-700">Active</span>
                    </label>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex gap-4">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                    Update Property
                </button>
                <a href="<?php echo e(route('properties.show', $property)); ?>" class="bg-gray-300 text-gray-700 px-6 py-2 rounded hover:bg-gray-400">
                    Cancel
                </a>
            </div>
        </form>
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

<?php /**PATH /home/kuwaitcabinhotel/sales-portal.kuwaitcabinhotel.com/resources/views/properties/edit.blade.php ENDPATH**/ ?>