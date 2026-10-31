<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

        <title><?php echo e(config('app.name', 'Laravel')); ?></title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
            <div class="mb-6">
                <?php
                    $debugProperty = $property ?? null;
                    if (!$debugProperty) {
                        $debugProperty = \App\Models\Property::first();
                    }
                ?>
                <?php if($debugProperty): ?>
                    <a href="/" class="flex items-center gap-2 hover:opacity-80 transition">
                        <?php if($debugProperty->logo_path): ?>
                            <img src="<?php echo e(asset($debugProperty->logo_path)); ?>" alt="Hotel Logo" class="h-10 w-auto object-contain" loading="lazy">
                        <?php else: ?>
                            <span class="text-2xl">🏨</span>
                        <?php endif; ?>
                        <span class="text-lg font-semibold text-gray-800 hidden sm:inline"><?php echo e($debugProperty->display_name ?? $debugProperty->name); ?></span>
                    </a>
                <?php else: ?>
                    <a href="/" class="text-lg font-semibold text-gray-800 hover:text-gray-600 transition">
                        🏨 Hotel Management
                    </a>
                <?php endif; ?>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
                <?php echo e($slot); ?>

            </div>
        </div>
    </body>
</html>
 <?php /**PATH /home/kuwaitcabinhotel/sales-portal.kuwaitcabinhotel.com/resources/views/layouts/guest.blade.php ENDPATH**/ ?>