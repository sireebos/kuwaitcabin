<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

<!-- Quick Stats with Icons -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Occupancy Card -->
    <div class="bg-white rounded-lg shadow-lg hover:shadow-xl transition transform hover:scale-105 cursor-pointer">
        <div class="bg-gradient-to-br from-blue-400 to-blue-600 p-4 rounded-t-lg flex items-center justify-center">
            <svg class="w-12 h-12 text-white" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10.5 1.5H3.75A2.25 2.25 0 001.5 3.75v12.5A2.25 2.25 0 003.75 18.5h12.5a2.25 2.25 0 002.25-2.25V9.5m-15-6h12m-12 3h12m-12 3h6M10 10.5a3 3 0 100-6 3 3 0 000 6z"/>
            </svg>
        </div>
        <div class="p-4 text-center">
            <h3 class="text-gray-600 text-xs font-medium mb-1">Occupancy Rate</h3>
            <p class="text-xl sm:text-2xl font-bold text-blue-600 break-words">{{ number_format($occupancyRate, 1) }}%</p>
            <p class="text-gray-500 text-xs mt-2">{{ $occupiedRooms }} of {{ $totalRooms }} rooms</p>
        </div>
    </div>

    <!-- Today's Check-ins -->
    <div class="bg-white rounded-lg shadow-lg hover:shadow-xl transition transform hover:scale-105 cursor-pointer">
        <div class="bg-gradient-to-br from-green-400 to-green-600 p-4 rounded-t-lg flex items-center justify-center">
            <svg class="w-12 h-12 text-white" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M12 2a1 1 0 011 1v6h6a1 1 0 110 2h-6v6a1 1 0 11-2 0v-6H5a1 1 0 110-2h6V3a1 1 0 011-1z" clip-rule="evenodd"/>
            </svg>
        </div>
        <div class="p-4 text-center">
            <h3 class="text-gray-600 text-xs font-medium mb-1">Today's Check-ins</h3>
            <p class="text-xl sm:text-2xl font-bold text-green-600 break-words">{{ $todayCheckIns }}</p>
            <p class="text-gray-500 text-xs mt-2">Expected arrivals</p>
        </div>
    </div>

    <!-- Today's Check-outs -->
    <div class="bg-white rounded-lg shadow-lg hover:shadow-xl transition transform hover:scale-105 cursor-pointer">
        <div class="bg-gradient-to-br from-orange-400 to-orange-600 p-4 rounded-t-lg flex items-center justify-center">
            <svg class="w-12 h-12 text-white" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/>
            </svg>
        </div>
        <div class="p-4 text-center">
            <h3 class="text-gray-600 text-xs font-medium mb-1">Today's Check-outs</h3>
            <p class="text-xl sm:text-2xl font-bold text-orange-600 break-words">{{ $todayCheckOuts }}</p>
            <p class="text-gray-500 text-xs mt-2">Expected departures</p>
        </div>
    </div>
</div>

<!-- Quick Navigation Menu with Large Icons in Rows -->
<div class="mb-8">
    <h2 class="text-3xl font-bold text-gray-800 mb-8">Quick Access</h2>

    <!-- Hotel Management Section - Visible to Admin, Manager, Receptionist -->
    @if(auth()->user()->isAdmin() || auth()->user()->hasPermission('manage_bookings') || auth()->user()->hasPermission('checkin_checkout'))
    <div class="mb-12">
        <h3 class="text-2xl font-bold text-gray-700 mb-6 flex items-center">
            <svg class="w-8 h-8 text-indigo-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10.5 1.5H3.75A2.25 2.25 0 001.5 3.75v12.5A2.25 2.25 0 003.75 18.5h12.5a2.25 2.25 0 002.25-2.25V9.5m-15-6h12m-12 3h12m-12 3h6M10 10.5a3 3 0 100-6 3 3 0 000 6z"/>
            </svg>
            Hotel Management
        </h3>
        <div class="flex flex-wrap gap-4">
            <!-- Manage Bookings -->
            <a href="{{ route('properties.bookings.index', $property) }}" class="group flex-1 min-w-max">
                <div class="bg-white rounded-xl shadow-lg hover:shadow-2xl transition transform hover:scale-105 overflow-hidden w-40">
                    <div class="bg-gradient-to-br from-indigo-400 to-indigo-600 p-4 flex items-center justify-center h-32 group-hover:from-indigo-500 group-hover:to-indigo-700">
                        <svg class="w-16 h-16 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M6 2a1 1 0 00-1 1v2H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V7a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v2H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"/>
                        </svg>
                    </div>
                    <div class="p-3 text-center">
                        <p class="font-bold text-sm text-gray-800">📅 Manage Bookings</p>
                    </div>
                </div>
            </a>

            <!-- Check-in Guests -->
            {{-- <a href="{{ route('checkin.index', $property) }}" class="group flex-1 min-w-max">
                <div class="bg-white rounded-xl shadow-lg hover:shadow-2xl transition transform hover:scale-105 overflow-hidden w-40">
                    <div class="bg-gradient-to-br from-green-400 to-green-600 p-4 flex items-center justify-center h-32 group-hover:from-green-500 group-hover:to-green-700">
                        <svg class="w-16 h-16 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M12 2a1 1 0 011 1v6h6a1 1 0 110 2h-6v6a1 1 0 11-2 0v-6H5a1 1 0 110-2h6V3a1 1 0 011-1z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="p-3 text-center">
                        <p class="font-bold text-sm text-gray-800"> Check-in Guests</p>
                    </div>
                </div>
            </a> --}}

            <!-- Check-out Guests -->
            <a href="{{ route('checkout.index', $property) }}" class="group flex-1 min-w-max">
                <div class="bg-white rounded-xl shadow-lg hover:shadow-2xl transition transform hover:scale-105 overflow-hidden w-40">
                    <div class="bg-gradient-to-br from-red-400 to-red-600 p-4 flex items-center justify-center h-32 group-hover:from-red-500 group-hover:to-red-700">
                        <svg class="w-16 h-16 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="p-3 text-center">
                        <p class="font-bold text-sm text-gray-800">✓ Check Guests Status</p>
                    </div>
                </div>
            </a>

            <!-- Folio Management -->
            <a href="{{ route('properties.bookings.index', $property) }}" class="group flex-1 min-w-max">
                <div class="bg-white rounded-xl shadow-lg hover:shadow-2xl transition transform hover:scale-105 overflow-hidden w-40">
                    <div class="bg-gradient-to-br from-yellow-400 to-yellow-600 p-4 flex items-center justify-center h-32 group-hover:from-yellow-500 group-hover:to-yellow-700">
                        <svg class="w-16 h-16 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"/>
                        </svg>
                    </div>
                    <div class="p-3 text-center">
                        <p class="font-bold text-sm text-gray-800">💳 Folio</p>
                    </div>
                </div>
            </a>
        </div>
    </div>
    @endif

    <!-- Restaurant & Bar Section - Visible to Admin, Manager, Chef, Waiter -->
    @if(auth()->user()->isAdmin() || auth()->user()->hasPermission('manage_orders') || auth()->user()->hasPermission('view_menus') || auth()->user()->hasPermission('create_orders'))
    <div class="mb-12">
        <h3 class="text-2xl font-bold text-gray-700 mb-6 flex items-center">
            <svg class="w-8 h-8 text-orange-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 1 1 0 000 2H4zm-.293 1.293a1 1 0 011.414 0L8 7.414l2.879-2.879a1 1 0 111.414 1.414L9.414 8l2.879 2.879a1 1 0 11-1.414 1.414L8 9.414l-2.879 2.879a1 1 0 01-1.414-1.414L6.586 8 3.707 5.121a1 1 0 010-1.414z" clip-rule="evenodd"/>
            </svg>
            Restaurant & Bar
        </h3>
        <div class="flex flex-wrap gap-4">
            <!-- POS System -->
            <a href="{{ route('orders.pos', $property) }}" class="group flex-1 min-w-max">
                <div class="bg-white rounded-xl shadow-lg hover:shadow-2xl transition transform hover:scale-105 overflow-hidden w-40">
                    <div class="bg-gradient-to-br from-orange-400 to-orange-600 p-4 flex items-center justify-center h-32 group-hover:from-orange-500 group-hover:to-orange-700">
                        <svg class="w-16 h-16 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 6H6.28l-.31-1.243A1 1 0 005 4H3z"/>
                            <path d="M16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"/>
                        </svg>
                    </div>
                    <div class="p-3 text-center">
                        <p class="font-bold text-sm text-gray-800">🍽️ POS System</p>
                    </div>
                </div>
            </a>

            <!-- Manage Orders -->
            <a href="{{ route('properties.orders.index', $property) }}" class="group flex-1 min-w-max">
                <div class="bg-white rounded-xl shadow-lg hover:shadow-2xl transition transform hover:scale-105 overflow-hidden w-40">
                    <div class="bg-gradient-to-br from-pink-400 to-pink-600 p-4 flex items-center justify-center h-32 group-hover:from-pink-500 group-hover:to-pink-700">
                        <svg class="w-16 h-16 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 6H6.28l-.31-1.243A1 1 0 005 4H3z"/>
                            <path d="M16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"/>
                        </svg>
                    </div>
                    <div class="p-3 text-center">
                        <p class="font-bold text-sm text-gray-800">📋 Manage Orders</p>
                    </div>
                </div>
            </a>

            <!-- Menu Items -->
            <a href="{{ route('properties.menus.index', $property) }}" class="group flex-1 min-w-max">
                <div class="bg-white rounded-xl shadow-lg hover:shadow-2xl transition transform hover:scale-105 overflow-hidden w-40">
                    <div class="bg-gradient-to-br from-red-400 to-red-600 p-4 flex items-center justify-center h-32 group-hover:from-red-500 group-hover:to-red-700">
                        <svg class="w-16 h-16 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 4a1 1 0 011-1h6a1 1 0 011 1v12a1 1 0 01-1 1H3a1 1 0 01-1-1V4z"/>
                            <path d="M13 2a1 1 0 00-1 1v12a2 2 0 002 2h2a2 2 0 002-2V3a1 1 0 10-2 0v12h-2V3a1 1 0 00-1-1z"/>
                        </svg>
                    </div>
                    <div class="p-3 text-center">
                        <p class="font-bold text-sm text-gray-800">📖 Menu Items</p>
                    </div>
                </div>
            </a>

            <!-- Reservations -->
            <a href="{{ route('properties.bookings.index', $property) }}" class="group flex-1 min-w-max">
                <div class="bg-white rounded-xl shadow-lg hover:shadow-2xl transition transform hover:scale-105 overflow-hidden w-40">
                    <div class="bg-gradient-to-br from-purple-400 to-purple-600 p-4 flex items-center justify-center h-32 group-hover:from-purple-500 group-hover:to-purple-700">
                        <svg class="w-16 h-16 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v2H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V7a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v2H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="p-3 text-center">
                        <p class="font-bold text-sm text-gray-800">📅 Reservations</p>
                    </div>
                </div>
            </a>
        </div>
    </div>
    @endif

    <!-- Operations Section - Visible to Admin, Manager -->
    @if(auth()->user()->isAdmin() || auth()->user()->hasPermission('manage_inventory') || auth()->user()->hasPermission('view_reports'))
    <div class="mb-12">
        <h3 class="text-2xl font-bold text-gray-700 mb-6 flex items-center">
            <svg class="w-8 h-8 text-teal-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/>
            </svg>
            Operations
        </h3>
        <div class="flex flex-wrap gap-4">
            <!-- Inventory -->
            <a href="{{ route('properties.inventory.index', $property) }}" class="group flex-1 min-w-max">
                <div class="bg-white rounded-xl shadow-lg hover:shadow-2xl transition transform hover:scale-105 overflow-hidden w-40">
                    <div class="bg-gradient-to-br from-teal-400 to-teal-600 p-4 flex items-center justify-center h-32 group-hover:from-teal-500 group-hover:to-teal-700">
                        <svg class="w-16 h-16 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"/>
                        </svg>
                    </div>
                    <div class="p-3 text-center">
                        <p class="font-bold text-sm text-gray-800">📦 Inventory</p>
                    </div>
                </div>
            </a>

            <!-- Sales Report -->
            <a href="{{ route('reports.daily-sales', $property) }}" class="group flex-1 min-w-max">
                <div class="bg-white rounded-xl shadow-lg hover:shadow-2xl transition transform hover:scale-105 overflow-hidden w-40">
                    <div class="bg-gradient-to-br from-cyan-400 to-cyan-600 p-4 flex items-center justify-center h-32 group-hover:from-cyan-500 group-hover:to-cyan-700">
                        <svg class="w-16 h-16 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/>
                        </svg>
                    </div>
                    <div class="p-3 text-center">
                        <p class="font-bold text-sm text-gray-800">💰 Sales Report</p>
                    </div>
                </div>
            </a>

            <!-- Occupancy Report -->
            <a href="{{ route('dashboard', $property) }}" class="group flex-1 min-w-max">
                <div class="bg-white rounded-xl shadow-lg hover:shadow-2xl transition transform hover:scale-105 overflow-hidden w-40">
                    <div class="bg-gradient-to-br from-blue-400 to-blue-600 p-4 flex items-center justify-center h-32 group-hover:from-blue-500 group-hover:to-blue-700">
                        <svg class="w-16 h-16 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/>
                        </svg>
                    </div>
                    <div class="p-3 text-center">
                        <p class="font-bold text-sm text-gray-800">📊 Occupancy Report</p>
                    </div>
                </div>
            </a>

            <!-- Staff Management -->
            <a href="{{ route('properties.bookings.index', $property) }}" class="group flex-1 min-w-max">
                <div class="bg-white rounded-xl shadow-lg hover:shadow-2xl transition transform hover:scale-105 overflow-hidden w-40">
                    <div class="bg-gradient-to-br from-lime-400 to-lime-600 p-4 flex items-center justify-center h-32 group-hover:from-lime-500 group-hover:to-lime-700">
                        <svg class="w-16 h-16 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM9 6a3 3 0 11-6 0 3 3 0 016 0zM9 6a3 3 0 11-6 0 3 3 0 016 0zM9 6a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path d="M17 6a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <div class="p-3 text-center">
                        <p class="font-bold text-sm text-gray-800">👥 Staff</p>
                    </div>
                </div>
            </a>
        </div>
    </div>
    @endif

    <!-- Admin Settings Section - Visible to Admin Only -->
    @if(auth()->user()->isAdmin())
    <div class="mb-12">
        <h3 class="text-2xl font-bold text-gray-700 mb-6 flex items-center">
            <svg class="w-8 h-8 text-red-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/>
            </svg>
            ⚙️ Admin Settings
        </h3>
        <div class="flex flex-wrap gap-4">
            <!-- Manage Users -->
            <a href="{{ route('properties.users.index', $property) }}" class="group flex-1 min-w-max">
                <div class="bg-white rounded-xl shadow-lg hover:shadow-2xl transition transform hover:scale-105 overflow-hidden w-40">
                    <div class="bg-gradient-to-br from-red-400 to-red-600 p-4 flex items-center justify-center h-32 group-hover:from-red-500 group-hover:to-red-700">
                        <svg class="w-16 h-16 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"/>
                        </svg>
                    </div>
                    <div class="p-3 text-center">
                        <p class="font-bold text-sm text-gray-800">👤 Manage Users</p>
                    </div>
                </div>
            </a>

            <!-- Sales Report -->
            <a href="{{ route('reports.daily-sales', $property) }}" class="group flex-1 min-w-max">
                <div class="bg-white rounded-xl shadow-lg hover:shadow-2xl transition transform hover:scale-105 overflow-hidden w-40">
                    <div class="bg-gradient-to-br from-green-400 to-green-600 p-4 flex items-center justify-center h-32 group-hover:from-green-500 group-hover:to-green-700">
                        <svg class="w-16 h-16 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/>
                        </svg>
                    </div>
                    <div class="p-3 text-center">
                        <p class="font-bold text-sm text-gray-800">💵 Sales Report</p>
                    </div>
                </div>
            </a>

            <!-- Bookings Report -->
            <a href="{{ route('reports.daily-bookings', $property) }}" class="group flex-1 min-w-max">
                <div class="bg-white rounded-xl shadow-lg hover:shadow-2xl transition transform hover:scale-105 overflow-hidden w-40">
                    <div class="bg-gradient-to-br from-indigo-400 to-indigo-600 p-4 flex items-center justify-center h-32 group-hover:from-indigo-500 group-hover:to-indigo-700">
                        <svg class="w-16 h-16 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v2H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V7a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v2H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="p-3 text-center">
                        <p class="font-bold text-sm text-gray-800">📋 Bookings Report</p>
                    </div>
                </div>
            </a>

            <!-- Guest Fields -->
            <a href="{{ route('dashboard', $property) }}" class="group flex-1 min-w-max">
                <div class="bg-white rounded-xl shadow-lg hover:shadow-2xl transition transform hover:scale-105 overflow-hidden w-40">
                    <div class="bg-gradient-to-br from-blue-400 to-blue-600 p-4 flex items-center justify-center h-32 group-hover:from-blue-500 group-hover:to-blue-700">
                        <svg class="w-16 h-16 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v2h8v-2zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-2a4 4 0 00-8 0v2h8z"/>
                        </svg>
                    </div>
                    <div class="p-3 text-center">
                        <p class="font-bold text-sm text-gray-800">⚙️ Guest Fields</p>
                    </div>
                </div>
            </a>

            <!-- Inventory Fields -->
            <a href="{{ route('dashboard', $property) }}" class="group flex-1 min-w-max">
                <div class="bg-white rounded-xl shadow-lg hover:shadow-2xl transition transform hover:scale-105 overflow-hidden w-40">
                    <div class="bg-gradient-to-br from-purple-400 to-purple-600 p-4 flex items-center justify-center h-32 group-hover:from-purple-500 group-hover:to-purple-700">
                        <svg class="w-16 h-16 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"/>
                        </svg>
                    </div>
                    <div class="p-3 text-center">
                        <p class="font-bold text-sm text-gray-800">⚙️ Inventory Fields</p>
                    </div>
                </div>
            </a>
        </div>
    </div>
    @endif
</div>

<!-- Revenue Summary and Upcoming Check-ins -->
@if(auth()->user()->isAdmin() || auth()->user()->hasPermission('manage_bookings') || auth()->user()->hasPermission('checkin_checkout'))
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Revenue Summary -->
    <div class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition">
        <div class="flex items-center mb-6">
            <svg class="w-6 h-6 text-green-600 mr-3 font-bold" fill="currentColor" viewBox="0 0 20 20">
                <path d="M8.16 2.75a.75.75 0 00-1.32 0l-.478 1.435a.75.75 0 01-.578.578l-1.435.478a.75.75 0 000 1.32l1.435.478c.29.097.52.288.578.578l.478 1.435a.75.75 0 001.32 0l.478-1.435c.097-.29.288-.52.578-.578l1.435-.478a.75.75 0 000-1.32l-1.435-.478a.75.75 0 01-.578-.578l-.478-1.435zM16.5 12.75a.75.75 0 00-1.32 0l-.478 1.435a.75.75 0 01-.578.578l-1.435.478a.75.75 0 000 1.32l1.435.478c.29.097.52.288.578.578l.478 1.435a.75.75 0 001.32 0l.478-1.435c.097-.29.288-.52.578-.578l1.435-.478a.75.75 0 000-1.32l-1.435-.478a.75.75 0 01-.578-.578l-.478-1.435z"/>
            </svg>
            <h3 class="text-lg font-bold text-gray-800">Revenue Summary</h3>
        </div>
        <div class="space-y-4">
            <div class="flex justify-between items-center p-3 bg-blue-50 rounded">
                <span class="font-medium text-gray-700">Today's Hotel Revenue:</span>
                <span class="font-bold text-lg text-blue-600">₦{{ number_format($todayRevenue, 2) }}</span>
            </div>
            <div class="flex justify-between items-center p-3 bg-orange-50 rounded">
                <span class="font-medium text-gray-700">Today's Restaurant Revenue:</span>
                <span class="font-bold text-lg text-orange-600">₦{{ number_format($todayOrdersRevenue, 2) }}</span>
            </div>
            <div class="flex justify-between items-center p-3 bg-purple-50 rounded border-2 border-purple-200">
                <span class="font-bold text-gray-800">Month's Total Revenue:</span>
                <span class="font-bold text-xl text-purple-600">₦{{ number_format($monthRevenue, 2) }}</span>
            </div>
        </div>
    </div>

    <!-- Upcoming Check-ins -->
    <div class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition">
        <div class="flex items-center mb-6">
            <svg class="w-6 h-6 text-blue-600 mr-3 font-bold" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10.5 1.5H3.75A2.25 2.25 0 001.5 3.75v12.5A2.25 2.25 0 003.75 18.5h12.5a2.25 2.25 0 002.25-2.25V9.5m-15-6h12m-12 3h12m-12 3h6M10 10.5a3 3 0 100-6 3 3 0 000 6z"/>
            </svg>
            <h3 class="text-lg font-bold text-gray-800">Upcoming Check-ins</h3>
        </div>
        <div class="space-y-3">
            @forelse ($upcomingCheckIns as $booking)
                <div class="border-l-4 border-blue-500 pl-4 py-3 bg-blue-50 rounded">
                    <p class="font-bold text-gray-800">{{ $booking->guest->full_name }}</p>
                    <p class="text-sm text-gray-600 mt-1">
                        <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v2H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V7a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v2H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                        </svg>
                        {{ $booking->check_in_date }}
                    </p>
                </div>
            @empty
                <p class="text-gray-500 text-center py-4">No upcoming check-ins</p>
            @endforelse
        </div>
    </div>
</div>
@endif
</x-app-layout>

