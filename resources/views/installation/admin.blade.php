<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Admin User - Installation Wizard</title>
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
                    <span class="text-sm font-semibold text-gray-700">Step 5 of 6</span>
                    <span class="text-sm font-semibold text-gray-700">Create Admin User</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-blue-600 h-2 rounded-full" style="width: 83.33%"></div>
                </div>
            </div>

            <!-- Content -->
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Create Administrator Account</h2>

                <p class="text-gray-700 mb-6">
                    Create the first administrator account. This account will have full access to the system.
                </p>

                <form id="adminForm" class="space-y-4 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                        <input type="text" id="name" name="name" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="John Doe">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                        <input type="email" id="email" name="email" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="admin@hotel.com">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <input type="password" id="password" name="password" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Minimum 8 characters">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Confirm password">
                    </div>
                </form>

                <div id="adminSuccess" class="p-4 bg-green-50 border border-green-500 rounded-lg mb-6 hidden">
                    <p class="text-green-800 font-semibold">✅ Admin user created successfully!</p>
                </div>

                <div id="adminError" class="p-4 bg-red-50 border border-red-500 rounded-lg mb-6 hidden">
                    <p class="text-red-800 font-semibold">❌ Error creating admin</p>
                    <p id="errorMessage" class="text-red-700 text-sm mt-2"></p>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex gap-4">
                <a href="{{ route('installation.migrations') }}" class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-3 px-4 rounded-lg text-center transition">
                    ← Back
                </a>
                <button onclick="createAdmin()" id="createBtn" class="flex-1 bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-4 rounded-lg transition">
                    ✓ Create Admin
                </button>
                <a href="{{ route('installation.complete') }}" id="nextBtn" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg text-center transition hidden">
                    Next: Complete →
                </a>
            </div>
        </div>
    </div>

    <script>
        function createAdmin() {
            const formData = new FormData(document.getElementById('adminForm'));
            const data = Object.fromEntries(formData);

            document.getElementById('createBtn').disabled = true;

            fetch('{{ route("installation.createAdmin") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(data)
            })
            .then(r => r.json())
            .then(result => {
                if (result.success) {
                    document.getElementById('adminForm').classList.add('hidden');
                    document.getElementById('adminSuccess').classList.remove('hidden');
                    document.getElementById('createBtn').classList.add('hidden');
                    document.getElementById('nextBtn').classList.remove('hidden');
                } else {
                    document.getElementById('adminError').classList.remove('hidden');
                    document.getElementById('errorMessage').textContent = result.message;
                    document.getElementById('createBtn').disabled = false;
                }
            })
            .catch(err => {
                document.getElementById('adminError').classList.remove('hidden');
                document.getElementById('errorMessage').textContent = err.message;
                document.getElementById('createBtn').disabled = false;
            });
        }
    </script>
</body>
</html>

