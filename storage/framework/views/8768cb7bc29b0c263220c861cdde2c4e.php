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
                <i class="fas fa-cog mr-2"></i><?php echo e(__('System Information')); ?>

            </h2>
            <span class="px-4 py-2 bg-blue-100 text-blue-800 rounded-lg font-semibold">
                Version <?php echo e($systemInfo['app_version']); ?>

            </span>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="max-w-7xl mx-auto">
        <?php if(session('success')): ?>
            <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                <i class="fas fa-check-circle mr-2"></i><?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>

        <?php if(session('error')): ?>
            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                <i class="fas fa-exclamation-circle mr-2"></i><?php echo e(session('error')); ?>

            </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- System Information -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">
                    <i class="fas fa-server mr-2 text-blue-500"></i>System Information
                </h3>
                <div class="space-y-3">
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-gray-600">Application Name</span>
                        <span class="font-semibold"><?php echo e($systemInfo['app_name']); ?></span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-gray-600">Version</span>
                        <span class="font-semibold text-blue-600"><?php echo e($systemInfo['app_version']); ?></span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-gray-600">Release Date</span>
                        <span class="font-semibold"><?php echo e($systemInfo['version_date']); ?></span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-gray-600">PHP Version</span>
                        <span class="font-semibold"><?php echo e($systemInfo['php_version']); ?></span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-gray-600">Laravel Version</span>
                        <span class="font-semibold"><?php echo e($systemInfo['laravel_version']); ?></span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-gray-600">Server OS</span>
                        <span class="font-semibold"><?php echo e($systemInfo['server_os']); ?></span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-gray-600">Web Server</span>
                        <span class="font-semibold text-sm"><?php echo e($systemInfo['server_software']); ?></span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-gray-600">Timezone</span>
                        <span class="font-semibold"><?php echo e($systemInfo['timezone']); ?></span>
                    </div>
                    <div class="flex justify-between py-2">
                        <span class="text-gray-600">Debug Mode</span>
                        <span class="px-2 py-1 rounded text-xs font-semibold <?php echo e($systemInfo['debug_mode'] === 'Enabled' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800'); ?>">
                            <?php echo e($systemInfo['debug_mode']); ?>

                        </span>
                    </div>
                </div>
            </div>

            <!-- Database Information -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">
                    <i class="fas fa-database mr-2 text-green-500"></i>Database Information
                </h3>
                <div class="space-y-3">
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-gray-600">Database Driver</span>
                        <span class="font-semibold uppercase"><?php echo e($systemInfo['database_driver']); ?></span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-gray-600">Database Version</span>
                        <span class="font-semibold"><?php echo e($systemInfo['database_version']); ?></span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-gray-600">Total Tables</span>
                        <span class="font-semibold"><?php echo e($databaseInfo['total_tables']); ?></span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-gray-600">Total Users</span>
                        <span class="font-semibold"><?php echo e($databaseInfo['total_users']); ?></span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-gray-600">Total Properties</span>
                        <span class="font-semibold"><?php echo e($databaseInfo['total_properties']); ?></span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-gray-600">Total Bookings</span>
                        <span class="font-semibold"><?php echo e($databaseInfo['total_bookings']); ?></span>
                    </div>
                    <div class="flex justify-between py-2">
                        <span class="text-gray-600">Total Guests</span>
                        <span class="font-semibold"><?php echo e($databaseInfo['total_guests']); ?></span>
                    </div>
                </div>
            </div>

            <!-- Storage & Cache -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">
                    <i class="fas fa-hdd mr-2 text-purple-500"></i>Storage & Cache
                </h3>
                <div class="space-y-3">
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-gray-600">Total Disk Space</span>
                        <span class="font-semibold"><?php echo e($diskInfo['total_space']); ?></span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-gray-600">Free Space</span>
                        <span class="font-semibold text-green-600"><?php echo e($diskInfo['free_space']); ?></span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-gray-600">Used Space</span>
                        <span class="font-semibold text-orange-600"><?php echo e($diskInfo['used_space']); ?></span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-gray-600">Storage Writable</span>
                        <span class="px-2 py-1 rounded text-xs font-semibold <?php echo e($diskInfo['storage_writable'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'); ?>">
                            <?php echo e($diskInfo['storage_writable'] ? 'Yes' : 'No'); ?>

                        </span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-gray-600">Cache Driver</span>
                        <span class="font-semibold uppercase"><?php echo e($systemInfo['cache_driver']); ?></span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-gray-600">Session Driver</span>
                        <span class="font-semibold uppercase"><?php echo e($systemInfo['session_driver']); ?></span>
                    </div>
                    <div class="flex justify-between py-2">
                        <span class="text-gray-600">Queue Driver</span>
                        <span class="font-semibold uppercase"><?php echo e($systemInfo['queue_driver']); ?></span>
                    </div>
                </div>

                <div class="mt-4 pt-4 border-t space-y-2">
                    <form action="<?php echo e(route('system.clear-cache')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="w-full bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg font-medium">
                            <i class="fas fa-broom mr-2"></i>Clear All Caches
                        </button>
                    </form>
                    <form action="<?php echo e(route('system.run-migrations')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="w-full bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg font-medium"
                                onclick="return confirm('Are you sure you want to run migrations? This will modify your database.')">
                            <i class="fas fa-database mr-2"></i>Run Migrations
                        </button>
                    </form>
                </div>
            </div>

            <!-- Pending Migrations -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">
                    <i class="fas fa-exchange-alt mr-2 text-orange-500"></i>Database Migrations
                </h3>
                
                <?php if(count($pendingMigrations) > 0): ?>
                    <div class="mb-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <p class="text-yellow-800 text-sm">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            <?php echo e(count($pendingMigrations)); ?> pending migration(s) found
                        </p>
                    </div>
                    <div class="max-h-48 overflow-y-auto mb-4">
                        <ul class="space-y-2">
                            <?php $__currentLoopData = $pendingMigrations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $migration): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li class="text-sm text-gray-600 bg-gray-50 p-2 rounded">
                                    <i class="fas fa-file-code mr-2 text-orange-500"></i><?php echo e($migration); ?>

                                </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                    <form action="<?php echo e(route('system.run-migrations')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="w-full bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg font-medium"
                                onclick="return confirm('Are you sure you want to run the pending migrations?')">
                            <i class="fas fa-play mr-2"></i>Run Pending Migrations
                        </button>
                    </form>
                <?php else: ?>
                    <div class="p-4 bg-green-50 border border-green-200 rounded-lg text-center">
                        <i class="fas fa-check-circle text-green-500 text-3xl mb-2"></i>
                        <p class="text-green-800">All migrations are up to date!</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- System Update Section -->
        <div class="mt-6 bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">
                <i class="fas fa-cloud-upload-alt mr-2 text-indigo-500"></i>System Update
            </h3>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div>
                    <p class="text-gray-600 mb-4">
                        Upload a system update package (.zip) to update the application. The update will:
                    </p>
                    <ul class="list-disc list-inside text-sm text-gray-600 space-y-1 mb-4">
                        <li>Backup your current configuration files</li>
                        <li>Extract and apply new/updated files</li>
                        <li>Run database migrations (optional)</li>
                        <li>Clear all application caches</li>
                        <li>Keep your database data intact</li>
                    </ul>
                    
                    <div class="p-3 bg-yellow-50 border border-yellow-200 rounded-lg mb-4">
                        <p class="text-yellow-800 text-sm">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            <strong>Important:</strong> Always backup your database before updating!
                        </p>
                    </div>

                    <form action="<?php echo e(route('system.update')); ?>" method="POST" enctype="multipart/form-data" id="updateForm">
                        <?php echo csrf_field(); ?>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Update Package (.zip)</label>
                            <input type="file" name="update_file" accept=".zip" required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                        </div>
                        
                        <div class="mb-4">
                            <label class="flex items-center">
                                <input type="checkbox" name="run_migrations" value="1" checked
                                       class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                <span class="ml-2 text-sm text-gray-700">Run database migrations after update</span>
                            </label>
                        </div>

                        <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-3 rounded-lg font-medium"
                                onclick="return confirm('Are you sure you want to update the system? This action cannot be undone.')">
                            <i class="fas fa-upload mr-2"></i>Upload & Install Update
                        </button>
                    </form>
                </div>

                <!-- Update History -->
                <div>
                    <h4 class="font-semibold text-gray-800 mb-3">
                        <i class="fas fa-history mr-2"></i>Update History
                    </h4>
                    
                    <?php if(count($updateHistory) > 0): ?>
                        <div class="max-h-80 overflow-y-auto">
                            <div class="space-y-3">
                                <?php $__currentLoopData = $updateHistory; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $update): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="p-3 bg-gray-50 rounded-lg border border-gray-200">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <span class="font-semibold text-indigo-600">v<?php echo e($update['version'] ?? 'Unknown'); ?></span>
                                                <p class="text-sm text-gray-600 mt-1"><?php echo e($update['description'] ?? 'No description'); ?></p>
                                            </div>
                                            <span class="text-xs text-gray-500">
                                                <?php echo e(\Carbon\Carbon::parse($update['date'])->format('M d, Y H:i')); ?>

                                            </span>
                                        </div>
                                        <div class="mt-2 flex gap-2 text-xs">
                                            <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded">
                                                <?php echo e($update['files_updated'] ?? 0); ?> files
                                            </span>
                                            <?php if($update['migrations_run'] ?? false): ?>
                                                <span class="px-2 py-1 bg-green-100 text-green-800 rounded">
                                                    Migrations ✓
                                                </span>
                                            <?php endif; ?>
                                            <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded">
                                                by <?php echo e($update['user'] ?? 'System'); ?>

                                            </span>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="p-4 bg-gray-50 border border-gray-200 rounded-lg text-center">
                            <i class="fas fa-inbox text-gray-400 text-3xl mb-2"></i>
                            <p class="text-gray-500">No update history available</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Update Package Format Info -->
        <div class="mt-6 bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">
                <i class="fas fa-info-circle mr-2 text-blue-500"></i>Update Package Format
            </h3>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div>
                    <p class="text-gray-600 mb-3">
                        Create a ZIP file containing the files to update. The folder structure should match your application:
                    </p>
                    <pre class="bg-gray-900 text-white p-4 rounded-lg text-sm overflow-x-auto">
update-package.zip
├── update-manifest.json (optional)
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       └── NewController.php
│   └── Models/
│       └── UpdatedModel.php
├── database/
│   └── migrations/
│       └── 2026_01_26_new_migration.php
├── resources/
│   └── views/
│       └── updated-view.blade.php
└── routes/
    └── web.php</pre>
                </div>
                <div>
                    <p class="text-gray-600 mb-3">
                        Optional <code class="bg-gray-100 px-1 rounded">update-manifest.json</code> format:
                    </p>
                    <pre class="bg-gray-900 text-white p-4 rounded-lg text-sm overflow-x-auto">
{
    "version": "1.1.0",
    "description": "Bug fixes and new features",
    "release_date": "2026-01-26",
    "notes": [
        "Fixed booking validation",
        "Added new reports"
    ]
}</pre>
                    <div class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                        <p class="text-blue-800 text-sm">
                            <i class="fas fa-shield-alt mr-2"></i>
                            <strong>Protected files:</strong> .env, storage/, vendor/, node_modules/ are never overwritten.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('updateForm').addEventListener('submit', function() {
            const btn = this.querySelector('button[type="submit"]');
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Updating... Please wait';
        });
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
<?php /**PATH /home/kuwaitcabinhotel/sales-portal.kuwaitcabinhotel.com/resources/views/system/info.blade.php ENDPATH**/ ?>