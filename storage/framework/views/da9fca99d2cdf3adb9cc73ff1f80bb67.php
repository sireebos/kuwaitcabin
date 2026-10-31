<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page Not Found | Kuwait Cabin Hotel</title>
    <?php if(file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot'))): ?>
        <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <?php else: ?>
        <script src="https://cdn.tailwindcss.com"></script>
    <?php endif; ?>
    <style>
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        @keyframes pulse-ring {
            0% { box-shadow: 0 0 0 0 rgba(79, 70, 229, 0.7); }
            70% { box-shadow: 0 0 0 20px rgba(79, 70, 229, 0); }
            100% { box-shadow: 0 0 0 0 rgba(79, 70, 229, 0); }
        }
        @keyframes slide-up {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .float-animation {
            animation: float 3s ease-in-out infinite;
        }
        .pulse-ring {
            animation: pulse-ring 2s infinite;
        }
        .slide-up {
            animation: slide-up 0.6s ease-out forwards;
        }
        .slide-up-delayed-1 { animation-delay: 0.1s; }
        .slide-up-delayed-2 { animation-delay: 0.2s; }
        .slide-up-delayed-3 { animation-delay: 0.3s; }
        .slide-up-delayed-4 { animation-delay: 0.4s; }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-100 min-h-screen flex items-center justify-center overflow-hidden">
    <!-- Decorative background elements -->
    <div class="absolute top-10 left-10 w-72 h-72 bg-indigo-200 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-pulse"></div>
    <div class="absolute bottom-10 right-10 w-72 h-72 bg-purple-200 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-pulse"></div>
    
    <div class="max-w-md w-full mx-auto px-6 relative z-10">
        <div class="bg-white rounded-2xl shadow-2xl p-8 text-center border border-indigo-50">
            <!-- 404 Icon with animation -->
            <div class="mb-6">
                <div class="relative inline-block mb-4">
                    <div class="absolute inset-0 bg-indigo-600 rounded-full opacity-10 pulse-ring"></div>
                    <div class="text-7xl font-black text-indigo-600 float-animation select-none">404</div>
                </div>
                <div class="text-2xl font-bold text-gray-800 slide-up slide-up-delayed-1">Page Not Found</div>
                <div class="text-sm text-indigo-600 font-semibold mt-2 slide-up slide-up-delayed-2">Oops! Something went wrong</div>
            </div>

            <!-- Error Message -->
            <p class="text-gray-600 mb-6 slide-up slide-up-delayed-2">
                Sorry, the page you're looking for doesn't exist. This could be because:
            </p>

            <!-- Reasons List -->
            <ul class="text-left text-gray-600 mb-8 space-y-3 text-sm slide-up slide-up-delayed-3">
                <li class="flex items-start hover:translate-x-1 transition-transform">
                    <span class="text-indigo-600 mr-3 font-bold">✓</span>
                    <span>The property ID doesn't exist in the system</span>
                </li>
                <li class="flex items-start hover:translate-x-1 transition-transform">
                    <span class="text-indigo-600 mr-3 font-bold">✓</span>
                    <span>The URL may have been typed incorrectly</span>
                </li>
                <li class="flex items-start hover:translate-x-1 transition-transform">
                    <span class="text-indigo-600 mr-3 font-bold">✓</span>
                    <span>The page may have been moved or deleted</span>
                </li>
            </ul>

            <!-- Action Buttons -->
            <div class="space-y-3 slide-up slide-up-delayed-4">
                <?php if(auth()->guard()->check()): ?>
                    <a href="<?php echo e(route('dashboard', ['property' => auth()->user()->property_id ?? 1])); ?>" 
                       class="block w-full bg-indigo-600 hover:bg-indigo-700 active:scale-95 text-white font-semibold py-3 px-4 rounded-lg transition duration-200 transform hover:shadow-lg">
                        Go to Dashboard
                    </a>
                    <?php if(auth()->user()->isAdmin()): ?>
                    <a href="<?php echo e(route('properties.index')); ?>" 
                       class="block w-full bg-gray-200 hover:bg-gray-300 active:scale-95 text-gray-800 font-semibold py-3 px-4 rounded-lg transition duration-200 transform hover:shadow-lg">
                        View All Properties
                    </a>
                    <?php endif; ?>
                <?php else: ?>
                    <a href="<?php echo e(route('login')); ?>" 
                       class="block w-full bg-indigo-600 hover:bg-indigo-700 active:scale-95 text-white font-semibold py-3 px-4 rounded-lg transition duration-200 transform hover:shadow-lg">
                        Go to Login
                    </a>
                    <a href="/" 
                       class="block w-full bg-gray-200 hover:bg-gray-300 active:scale-95 text-gray-800 font-semibold py-3 px-4 rounded-lg transition duration-200 transform hover:shadow-lg">
                        Go to Home
                    </a>
                <?php endif; ?>
            </div>

            <!-- Footer -->
            <p class="text-gray-500 text-xs mt-8 slide-up slide-up-delayed-4">
                If you believe this is an error, please contact support.<br>
                <span class="text-indigo-600 font-medium">Kuwait Cabin Hotel</span>
            </p>
        </div>
    </div>
</body>
</html><?php /**PATH /home/kuwaitcabinhotel/sales-portal.kuwaitcabinhotel.com/resources/views/errors/404.blade.php ENDPATH**/ ?>