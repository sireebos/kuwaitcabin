<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Run Migrations - Installation Wizard</title>
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
                    <span class="text-sm font-semibold text-gray-700">Step 4 of 6</span>
                    <span class="text-sm font-semibold text-gray-700">Run Migrations</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-blue-600 h-2 rounded-full" style="width: 66.67%"></div>
                </div>
            </div>

            <!-- Content -->
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Database Migrations</h2>

                <p class="text-gray-700 mb-6">
                    The system will now create all necessary database tables and structures. This process is automated and will take a few moments.
                </p>

                <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6">
                    <h3 class="font-semibold text-blue-900 mb-2">Tables to be created:</h3>
                    <ul class="list-disc list-inside text-blue-800 text-sm space-y-1">
                        <li>Users & Roles</li>
                        <li>Properties & Settings</li>
                        <li>Rooms & Room Types</li>
                        <li>Guests & Bookings</li>
                        <li>Restaurant Tables & Menus</li>
                        <li>Orders & Order Items</li>
                        <li>Inventory & Stock</li>
                        <li>Payments & Folios</li>
                    </ul>
                </div>

                <!-- Migration Status -->
                <div id="migrationStatus" class="p-4 bg-gray-50 border border-gray-300 rounded-lg mb-6 hidden">
                    <div class="flex items-center mb-2">
                        <div class="animate-spin h-5 w-5 text-blue-600 mr-3"></div>
                        <span class="text-gray-700 font-semibold">Running migrations...</span>
                    </div>
                    <div id="migrationOutput" class="text-sm text-gray-600 font-mono mt-2"></div>
                </div>

                <div id="migrationSuccess" class="p-4 bg-green-50 border border-green-500 rounded-lg mb-6 hidden">
                    <p class="text-green-800 font-semibold">✅ Migrations completed successfully!</p>
                </div>

                <div id="migrationError" class="p-4 bg-red-50 border border-red-500 rounded-lg mb-6 hidden">
                    <p class="text-red-800 font-semibold">❌ Migration failed</p>
                    <p id="errorMessage" class="text-red-700 text-sm mt-2"></p>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex gap-4">
                <a href="{{ route('installation.environment') }}" class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-3 px-4 rounded-lg text-center transition">
                    ← Back
                </a>
                <button onclick="runMigrations()" id="runBtn" class="flex-1 bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-4 rounded-lg transition">
                    ▶️ Run Migrations
                </button>
                <a href="{{ route('installation.admin') }}" id="nextBtn" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg text-center transition hidden">
                    Next: Admin User →
                </a>
            </div>
        </div>
    </div>

    <script>
        function runMigrations() {
            document.getElementById('runBtn').disabled = true;
            document.getElementById('migrationStatus').classList.remove('hidden');

            fetch('{{ route("installation.runMigrations") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(r => r.json())
            .then(result => {
                document.getElementById('migrationStatus').classList.add('hidden');
                if (result.success) {
                    document.getElementById('migrationSuccess').classList.remove('hidden');
                    document.getElementById('runBtn').classList.add('hidden');
                    document.getElementById('nextBtn').classList.remove('hidden');
                } else {
                    document.getElementById('migrationError').classList.remove('hidden');
                    document.getElementById('errorMessage').textContent = result.message;
                    document.getElementById('runBtn').disabled = false;
                }
            })
            .catch(err => {
                document.getElementById('migrationStatus').classList.add('hidden');
                document.getElementById('migrationError').classList.remove('hidden');
                document.getElementById('errorMessage').textContent = err.message;
                document.getElementById('runBtn').disabled = false;
            });
        }
    </script>
</body>
</html>

