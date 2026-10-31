<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Configuration - Installation Wizard</title>
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
                    <span class="text-sm font-semibold text-gray-700">Step 2 of 6</span>
                    <span class="text-sm font-semibold text-gray-700">Database Configuration</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-blue-600 h-2 rounded-full" style="width: 33.33%"></div>
                </div>
            </div>

            <!-- Content -->
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Database Configuration</h2>

                <!-- Database Type Selection -->
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-3">Select Database Type:</label>
                    <div class="space-y-3">
                        <label class="flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-blue-500 transition" onclick="selectDatabase('mysql')">
                            <input type="radio" name="driver" value="mysql" checked class="w-4 h-4 text-blue-600">
                            <span class="ml-3">
                                <span class="font-semibold text-gray-900">MySQL</span>
                                <p class="text-sm text-gray-600">Recommended for production</p>
                            </span>
                        </label>
                        <label class="flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-blue-500 transition" onclick="selectDatabase('sqlite')">
                            <input type="radio" name="driver" value="sqlite" class="w-4 h-4 text-blue-600">
                            <span class="ml-3">
                                <span class="font-semibold text-gray-900">SQLite</span>
                                <p class="text-sm text-gray-600">File-based database (simpler setup)</p>
                            </span>
                        </label>
                    </div>
                </div>

                <!-- MySQL Configuration -->
                <div id="mysqlConfig" class="space-y-4 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Host</label>
                        <input type="text" id="host" value="localhost" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Port</label>
                        <input type="number" id="port" value="3306" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Database Name</label>
                        <input type="text" id="database" placeholder="hotel_management" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                        <input type="text" id="username" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <input type="password" id="password" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                <!-- SQLite Configuration -->
                <div id="sqliteConfig" class="hidden space-y-4 mb-6">
                    <p class="text-gray-600 text-sm">SQLite will create a database file automatically.</p>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Database File Path</label>
                        <input type="text" id="sqliteDatabase" value="database/hotel.sqlite" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                <!-- Test Connection Button -->
                <button onclick="testConnection()" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg transition mb-4">
                    🔗 Test Connection
                </button>
                <div id="connectionStatus" class="hidden p-4 rounded-lg mb-4"></div>
            </div>

            <!-- Buttons -->
            <div class="flex gap-4">
                <a href="{{ route('installation.welcome') }}" class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-3 px-4 rounded-lg text-center transition">
                    ← Back
                </a>
                <a href="{{ route('installation.environment') }}" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg text-center transition">
                    Next: Environment →
                </a>
            </div>
        </div>
    </div>

    <script>
        function selectDatabase(type) {
            document.getElementById('mysqlConfig').classList.toggle('hidden', type !== 'mysql');
            document.getElementById('sqliteConfig').classList.toggle('hidden', type !== 'sqlite');
        }

        function testConnection() {
            const driver = document.querySelector('input[name="driver"]:checked').value;
            const data = { driver };

            if (driver === 'mysql') {
                data.host = document.getElementById('host').value;
                data.port = document.getElementById('port').value;
                data.database = document.getElementById('database').value;
                data.username = document.getElementById('username').value;
                data.password = document.getElementById('password').value;
            } else {
                data.database = document.getElementById('sqliteDatabase').value;
            }

            fetch('{{ route("installation.testConnection") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(data)
            })
            .then(r => r.json())
            .then(result => {
                const status = document.getElementById('connectionStatus');
                status.classList.remove('hidden');
                if (result.success) {
                    status.className = 'p-4 rounded-lg bg-green-50 border border-green-500 text-green-800';
                    status.innerHTML = '✅ ' + result.message;
                } else {
                    status.className = 'p-4 rounded-lg bg-red-50 border border-red-500 text-red-800';
                    status.innerHTML = '❌ ' + result.message;
                }
            });
        }
    </script>
</body>
</html>

