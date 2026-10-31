<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

        <title><?php echo e(config('app.name', 'Laravel')); ?></title>

        <!-- Favicon -->
        <?php if(isset($property) && $property->favicon_path && file_exists(public_path($property->favicon_path))): ?>
            <link rel="icon" type="image/x-icon" href="<?php echo e(asset($property->favicon_path)); ?>">
        <?php else: ?>
            <link rel="icon" type="image/x-icon" href="<?php echo e(asset('favicon.ico')); ?>">
        <?php endif; ?>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            <?php
                // If property is not explicitly passed, try to get it from the route parameter
                $navProperty = $property ?? null;
                if (!$navProperty && request()->route('property')) {
                    try {
                        $navProperty = request()->route('property');
                    } catch (\Exception $e) {
                        // Route not bound
                    }
                }
            ?>
            <?php echo $__env->make('layouts.navigation', ['property' => $navProperty], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

            <!-- Page Heading -->
            <?php if(isset($header)): ?>
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        <?php echo e($header); ?>

                    </div>
                </header>
            <?php endif; ?>

            <!-- Page Content -->
            <main>
                <?php echo e($slot); ?>

            </main>
        </div>
    </body>
</html>
<?php /**PATH /home/kuwaitcabinhotel/sales-portal.kuwaitcabinhotel.com/resources/views/layouts/app.blade.php ENDPATH**/ ?>