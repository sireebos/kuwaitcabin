<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Access Forbidden</title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>
<body class="bg-gradient-to-br from-yellow-50 to-orange-100 min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full mx-auto px-6">
        <div class="bg-white rounded-lg shadow-xl p-8 text-center">
            <!-- 403 Icon -->
            <div class="mb-6">
                <div class="text-6xl font-bold text-yellow-600 mb-2">403</div>
                <div class="text-2xl font-semibold text-gray-800">Access Forbidden</div>
            </div>

            <!-- Error Message -->
            <p class="text-gray-600 mb-8">
                You don't have permission to access this resource. Please contact your administrator if you believe this is an error.
            </p>

            <!-- Action Buttons -->
            <div class="space-y-3">
                <?php if(auth()->guard()->check()): ?>
                    <a href="<?php echo e(route('dashboard', ['property' => auth()->user()->property_id ?? 1])); ?>" 
                       class="block w-full bg-yellow-600 hover:bg-yellow-700 text-white font-semibold py-3 px-4 rounded-lg transition duration-200">
                        Go to Dashboard
                    </a>
                    <a href="javascript:history.back()" 
                       class="block w-full bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-3 px-4 rounded-lg transition duration-200">
                        Go Back
                    </a>
                <?php else: ?>
                    <a href="<?php echo e(route('login')); ?>" 
                       class="block w-full bg-yellow-600 hover:bg-yellow-700 text-white font-semibold py-3 px-4 rounded-lg transition duration-200">
                        Go to Login
                    </a>
                <?php endif; ?>
            </div>

            <!-- Footer -->
            <p class="text-gray-500 text-xs mt-8">
                If you need assistance, please contact support.
            </p>
        </div>
    </div>
</body>
</html>

<?php /**PATH /home/kuwaitcabinhotel/sales-portal.kuwaitcabinhotel.com/resources/views/errors/403.blade.php ENDPATH**/ ?>