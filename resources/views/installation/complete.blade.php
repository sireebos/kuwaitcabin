<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Installation Complete - Hotel Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-2xl max-w-2xl w-full p-8">
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">🏨 Hotel Management System</h1>
                <p class="text-gray-600">Installation Wizard</p>
            </div>

            <!-- Progress Bar -->
            <div class="mb-8">
                <div class="flex justify-between mb-2">
                    <span class="text-sm font-semibold text-gray-700">Step 6 of 6</span>
                    <span class="text-sm font-semibold text-gray-700">Installation Complete</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-blue-600 h-2 rounded-full" style="width: 100%"></div>
                </div>
            </div>

            <!-- Success Message -->
            <div class="text-center mb-8">
                <div class="text-6xl mb-4">🎉</div>
                <h2 class="text-3xl font-bold text-green-600 mb-2">Installation Complete!</h2>
                <p class="text-gray-700 text-lg">Your Hotel Management System is ready to use.</p>
            </div>

            <!-- Checklist -->
            <div class="bg-green-50 border-l-4 border-green-500 p-6 mb-8 rounded">
                <h3 class="font-bold text-green-900 mb-4">✅ Setup Summary</h3>
                <ul class="space-y-2 text-green-800">
                    <li class="flex items-center"><span class="text-green-600 mr-2">✓</span> Database configured and connected</li>
                    <li class="flex items-center"><span class="text-green-600 mr-2">✓</span> All database tables created</li>
                    <li class="flex items-center"><span class="text-green-600 mr-2">✓</span> Administrator account created</li>
                    <li class="flex items-center"><span class="text-green-600 mr-2">✓</span> Environment configured</li>
                    <li class="flex items-center"><span class="text-green-600 mr-2">✓</span> Storage directories ready</li>
                </ul>
            </div>

            <!-- Next Steps -->
            <div class="bg-blue-50 border-l-4 border-blue-500 p-6 mb-8 rounded">
                <h3 class="font-bold text-blue-900 mb-4">📋 Next Steps</h3>
                <ol class="list-decimal list-inside space-y-2 text-blue-800">
                    <li>Log in with your admin credentials</li>
                    <li>Configure your hotel settings and branding</li>
                    <li>Set up rooms and room types</li>
                    <li>Create restaurant tables and menus</li>
                    <li>Add staff members and assign roles</li>
                    <li>Start managing bookings and orders</li>
                </ol>
            </div>

            <!-- Important Notes -->
            <div class="bg-yellow-50 border-l-4 border-yellow-500 p-6 mb-8 rounded">
                <h3 class="font-bold text-yellow-900 mb-4">⚠️ Important Notes</h3>
                <ul class="list-disc list-inside space-y-2 text-yellow-800 text-sm">
                    <li>Keep your admin credentials safe and secure</li>
                    <li>Regularly backup your database</li>
                    <li>Ensure SSL certificate is installed on your domain</li>
                    <li>Configure email settings for notifications</li>
                    <li>Set up automated backups</li>
                </ul>
            </div>

            <!-- Security Notice -->
            <div class="bg-green-50 border-l-4 border-green-500 p-6 mb-8 rounded">
                <h3 class="font-bold text-green-900 mb-2">🔒 Installation Wizard Locked</h3>
                <p class="text-green-800 text-sm">
                    The installation wizard has been automatically locked for security reasons.
                    You cannot access the installation pages again. If you need to reinstall,
                    please contact your system administrator.
                </p>
            </div>

            <!-- Buttons -->
            <div class="flex gap-4">
                <a href="/login" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg text-center transition">
                    🔐 Go to Login
                </a>
                <a href="/" class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-3 px-4 rounded-lg text-center transition">
                    🏠 Go to Home
                </a>
            </div>
        </div>
    </div>
</body>
</html>

