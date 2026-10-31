<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Environment Setup - Installation Wizard</title>
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
                    <span class="text-sm font-semibold text-gray-700">Step 3 of 6</span>
                    <span class="text-sm font-semibold text-gray-700">Environment Setup</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-blue-600 h-2 rounded-full" style="width: 50%"></div>
                </div>
            </div>

            <!-- Content -->
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Environment Configuration</h2>

                <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6">
                    <p class="text-blue-900 text-sm">
                        The following environment variables have been configured automatically. Review and adjust if needed.
                    </p>
                </div>

                <div class="space-y-4 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Application Name</label>
                        <input type="text" value="Hotel Management System" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Application URL</label>
                        <input type="url" placeholder="https://yourdomain.com" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Application Environment</label>
                        <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="production">Production</option>
                            <option value="staging">Staging</option>
                            <option value="development">Development</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Mail Driver</label>
                        <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="smtp">SMTP</option>
                            <option value="mailgun">Mailgun</option>
                            <option value="sendgrid">SendGrid</option>
                            <option value="log">Log (Development)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Image Storage Path</label>
                        <input type="text" value="storage/app/public" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <p class="text-gray-600 text-xs mt-1">Where uploaded images will be stored</p>
                    </div>
                </div>

                <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 mb-6">
                    <p class="text-yellow-900 text-sm">
                        ⚠️ Make sure your server has write permissions to the storage directory.
                    </p>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex gap-4">
                <a href="{{ route('installation.database') }}" class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-3 px-4 rounded-lg text-center transition">
                    ← Back
                </a>
                <a href="{{ route('installation.migrations') }}" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg text-center transition">
                    Next: Migrations →
                </a>
            </div>
        </div>
    </div>
</body>
</html>

