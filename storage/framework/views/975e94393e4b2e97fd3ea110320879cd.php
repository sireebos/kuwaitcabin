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
        <div class="flex items-center gap-2">
            <a href="<?php echo e(route('settings.edit', $property)); ?>" class="text-gray-500 hover:text-gray-700">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                📧 <?php echo e(__('Email Settings')); ?>

            </h2>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="max-w-4xl mx-auto">
        <?php if(session('success')): ?>
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>

        <?php if(session('error')): ?>
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                <?php echo e(session('error')); ?>

            </div>
        <?php endif; ?>

        <!-- Email Settings Form -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="px-6 py-4 bg-gradient-to-r from-blue-500 to-blue-600 text-white">
                <h3 class="text-lg font-semibold">SMTP Configuration</h3>
                <p class="text-blue-100 text-sm">Configure your email server settings for sending booking notifications</p>
            </div>

            <form method="POST" action="<?php echo e(route('settings.email.update', $property)); ?>" class="p-6 space-y-6">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>

                <!-- Enable/Disable -->
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <div>
                        <label class="font-semibold text-gray-700">Enable Email Notifications</label>
                        <p class="text-sm text-gray-500">Send email alerts when new bookings arrive</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="email_notifications_enabled" value="1" class="sr-only peer" <?php echo e($property->email_notifications_enabled ? 'checked' : ''); ?>>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                    </label>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- SMTP Host -->
                    <div>
                        <label for="smtp_host" class="block text-sm font-semibold text-gray-700 mb-2">
                            SMTP Host <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="smtp_host" name="smtp_host" 
                            value="<?php echo e(old('smtp_host', $property->smtp_host)); ?>" 
                            placeholder="smtp.gmail.com"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 <?php $__errorArgs = ['smtp_host'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <?php $__errorArgs = ['smtp_host'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-600 text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- SMTP Port -->
                    <div>
                        <label for="smtp_port" class="block text-sm font-semibold text-gray-700 mb-2">
                            SMTP Port <span class="text-red-500">*</span>
                        </label>
                        <input type="number" id="smtp_port" name="smtp_port" 
                            value="<?php echo e(old('smtp_port', $property->smtp_port ?? 587)); ?>" 
                            placeholder="587"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 <?php $__errorArgs = ['smtp_port'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <?php $__errorArgs = ['smtp_port'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-600 text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        <p class="text-xs text-gray-500 mt-1">Common ports: 587 (TLS), 465 (SSL), 25 (No encryption)</p>
                    </div>

                    <!-- SMTP Username -->
                    <div>
                        <label for="smtp_username" class="block text-sm font-semibold text-gray-700 mb-2">
                            SMTP Username
                        </label>
                        <input type="text" id="smtp_username" name="smtp_username" 
                            value="<?php echo e(old('smtp_username', $property->smtp_username)); ?>" 
                            placeholder="your-email@gmail.com"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 <?php $__errorArgs = ['smtp_username'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <?php $__errorArgs = ['smtp_username'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-600 text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- SMTP Password -->
                    <div>
                        <label for="smtp_password" class="block text-sm font-semibold text-gray-700 mb-2">
                            SMTP Password
                        </label>
                        <input type="password" id="smtp_password" name="smtp_password" 
                            placeholder="<?php echo e($property->smtp_password ? '••••••••' : 'Enter password'); ?>"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 <?php $__errorArgs = ['smtp_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <?php $__errorArgs = ['smtp_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-600 text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        <p class="text-xs text-gray-500 mt-1">Leave blank to keep existing password</p>
                    </div>

                    <!-- Encryption -->
                    <div>
                        <label for="smtp_encryption" class="block text-sm font-semibold text-gray-700 mb-2">
                            Encryption
                        </label>
                        <select id="smtp_encryption" name="smtp_encryption" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="tls" <?php echo e(old('smtp_encryption', $property->smtp_encryption) == 'tls' ? 'selected' : ''); ?>>TLS (Recommended)</option>
                            <option value="ssl" <?php echo e(old('smtp_encryption', $property->smtp_encryption) == 'ssl' ? 'selected' : ''); ?>>SSL</option>
                            <option value="none" <?php echo e(old('smtp_encryption', $property->smtp_encryption) == null ? 'selected' : ''); ?>>None</option>
                        </select>
                    </div>

                    <!-- From Address -->
                    <div>
                        <label for="mail_from_address" class="block text-sm font-semibold text-gray-700 mb-2">
                            From Email Address
                        </label>
                        <input type="email" id="mail_from_address" name="mail_from_address" 
                            value="<?php echo e(old('mail_from_address', $property->mail_from_address ?? $property->email)); ?>" 
                            placeholder="noreply@yourhotel.com"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 <?php $__errorArgs = ['mail_from_address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <?php $__errorArgs = ['mail_from_address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-600 text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- From Name -->
                    <div class="md:col-span-2">
                        <label for="mail_from_name" class="block text-sm font-semibold text-gray-700 mb-2">
                            From Name
                        </label>
                        <input type="text" id="mail_from_name" name="mail_from_name" 
                            value="<?php echo e(old('mail_from_name', $property->mail_from_name ?? $property->name)); ?>" 
                            placeholder="<?php echo e($property->name); ?>"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 <?php $__errorArgs = ['mail_from_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <?php $__errorArgs = ['mail_from_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-600 text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                <div class="flex justify-end pt-4 border-t">
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Save Settings
                    </button>
                </div>
            </form>
        </div>

        <!-- Test Email -->
        <div class="mt-6 bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="px-6 py-4 bg-gradient-to-r from-green-500 to-green-600 text-white">
                <h3 class="text-lg font-semibold">Test Email Configuration</h3>
                <p class="text-green-100 text-sm">Send a test email to verify your settings are working</p>
            </div>

            <form method="POST" action="<?php echo e(route('settings.email.test', $property)); ?>" class="p-6">
                <?php echo csrf_field(); ?>
                <div class="flex gap-4">
                    <div class="flex-1">
                        <input type="email" name="test_email" 
                            value="<?php echo e(auth()->user()->email); ?>"
                            placeholder="Enter email to test"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                            required>
                    </div>
                    <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        Send Test Email
                    </button>
                </div>
            </form>
        </div>

        <!-- Help Section -->
        <div class="mt-6 bg-yellow-50 border border-yellow-200 rounded-lg p-6">
            <h4 class="font-semibold text-yellow-800 mb-3">📖 Common Email Provider Settings</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div class="bg-white p-4 rounded-lg border">
                    <h5 class="font-semibold text-gray-800 mb-2">Gmail</h5>
                    <ul class="space-y-1 text-gray-600">
                        <li><strong>Host:</strong> smtp.gmail.com</li>
                        <li><strong>Port:</strong> 587</li>
                        <li><strong>Encryption:</strong> TLS</li>
                        <li><strong>Password:</strong> <a href="https://myaccount.google.com/apppasswords" target="_blank" class="text-blue-600 hover:underline">Use App Password</a></li>
                    </ul>
                </div>
                <div class="bg-white p-4 rounded-lg border">
                    <h5 class="font-semibold text-gray-800 mb-2">Outlook / Office 365</h5>
                    <ul class="space-y-1 text-gray-600">
                        <li><strong>Host:</strong> smtp.office365.com</li>
                        <li><strong>Port:</strong> 587</li>
                        <li><strong>Encryption:</strong> TLS</li>
                    </ul>
                </div>
                <div class="bg-white p-4 rounded-lg border">
                    <h5 class="font-semibold text-gray-800 mb-2">Yahoo Mail</h5>
                    <ul class="space-y-1 text-gray-600">
                        <li><strong>Host:</strong> smtp.mail.yahoo.com</li>
                        <li><strong>Port:</strong> 587</li>
                        <li><strong>Encryption:</strong> TLS</li>
                    </ul>
                </div>
                <div class="bg-white p-4 rounded-lg border">
                    <h5 class="font-semibold text-gray-800 mb-2">Custom / cPanel</h5>
                    <ul class="space-y-1 text-gray-600">
                        <li><strong>Host:</strong> mail.yourdomain.com</li>
                        <li><strong>Port:</strong> 465 or 587</li>
                        <li><strong>Encryption:</strong> SSL or TLS</li>
                    </ul>
                </div>
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
<?php /**PATH /home/kuwaitcabinhotel/sales-portal.kuwaitcabinhotel.com/resources/views/settings/email.blade.php ENDPATH**/ ?>