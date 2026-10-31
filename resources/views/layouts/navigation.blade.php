<nav x-data="{ open: false }" class="bg-white border-b border-gray-100 shadow-sm">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo & Hotel Name -->
                <div class="shrink-0 flex items-center gap-3">
                    @php
                        $debugProperty = $property ?? null;
                        
                        // Try to get property from route parameter
                        if (!$debugProperty && request()->route('property')) {
                            try {
                                $debugProperty = request()->route('property');
                            } catch (\Exception $e) {}
                        }
                        
                        // Try to get property from session
                        if (!$debugProperty && session('current_property_id')) {
                            try {
                                $debugProperty = \App\Models\Property::find(session('current_property_id'));
                            } catch (\Exception $e) {}
                        }
                        
                        // Try to get first property for the user
                        if (!$debugProperty && auth()->check()) {
                            try {
                                if (auth()->user()->isAdmin()) {
                                    $debugProperty = \App\Models\Property::first();
                                } else {
                                    $debugProperty = auth()->user()->properties()->first();
                                }
                            } catch (\Exception $e) {}
                        }
                    @endphp
                    @if($debugProperty)
                        <a href="{{ route('dashboard', $debugProperty) }}" class="flex items-center gap-2 hover:opacity-80 transition">
                            @if($debugProperty->logo_path)
                                <img src="{{ asset($debugProperty->logo_path) }}" alt="Hotel Logo" class="h-10 w-auto object-contain" loading="lazy">
                            @else
                                <span class="text-2xl">🏨</span>
                            @endif
                            <span class="text-lg font-semibold text-gray-800 hidden lg:inline">{{ $debugProperty->display_name ?? $debugProperty->name }}</span>
                        </a>
                    @else
                        <a href="/" class="text-lg font-semibold text-gray-800 hover:text-gray-600 transition">
                            🏨 Hotel Management
                        </a>
                    @endif
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-1 sm:-my-px sm:ms-6 sm:flex items-center">
                    @if(isset($property))
                    
                    <!-- Dashboard -->
                    <x-nav-link :href="route('dashboard', $property)" :active="request()->routeIs('dashboard')" class="flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                        <span class="hidden lg:inline">{{ __('Dashboard') }}</span>
                    </x-nav-link>

                    <!-- Front Desk Dropdown -->
                    @if(auth()->user()->isAdmin() || auth()->user()->hasPermission('manage_bookings') || auth()->user()->hasPermission('checkin_checkout'))
                    <x-dropdown align="left" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 hover:text-gray-700 hover:bg-gray-50 focus:outline-none transition ease-in-out duration-150 {{ request()->routeIs('properties.bookings.*') || request()->routeIs('booking-history.*') || request()->routeIs('properties.guests.*') ? 'text-blue-600 bg-blue-50' : '' }}">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                <span>{{ __('Front Desk') }}</span>
                                <svg class="fill-current h-4 w-4 ml-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                            </button>
                        </x-slot>
                        <x-slot name="content">
                            <x-dropdown-link :href="route('properties.bookings.index', $property)" class="flex items-center gap-2">
                                <span>📅</span> {{ __('Bookings') }}
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('booking-history.index', $property)" class="flex items-center gap-2">
                                <span>📜</span> {{ __('Booking History') }}
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('properties.guests.index', $property)" class="flex items-center gap-2">
                                <span>👥</span> {{ __('Guests') }}
                            </x-dropdown-link>
                        </x-slot>
                    </x-dropdown>
                    @endif

                    <!-- POS Dropdown -->
                    @if(auth()->user()->isAdmin() || auth()->user()->hasPermission('create_orders') || auth()->user()->hasPermission('manage_orders') || auth()->user()->hasPermission('create_bar_orders') || auth()->user()->hasPermission('manage_bar_orders'))
                    <x-dropdown align="left" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 hover:text-gray-700 hover:bg-gray-50 focus:outline-none transition ease-in-out duration-150 {{ request()->routeIs('orders.pos') || request()->routeIs('properties.bar-orders.pos') ? 'text-green-600 bg-green-50' : '' }}">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                <span>{{ __('POS') }}</span>
                                <svg class="fill-current h-4 w-4 ml-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                            </button>
                        </x-slot>
                        <x-slot name="content">
                            @if(auth()->user()->isAdmin() || auth()->user()->hasPermission('create_orders') || auth()->user()->hasPermission('manage_orders'))
                            <x-dropdown-link :href="route('orders.pos', $property)" class="flex items-center gap-2">
                                <span>🍽️</span> {{ __('Restaurant POS') }}
                            </x-dropdown-link>
                            @endif
                            @if(auth()->user()->isAdmin() || auth()->user()->hasPermission('create_bar_orders') || auth()->user()->hasPermission('manage_bar_orders'))
                            <x-dropdown-link :href="route('properties.bar-orders.pos', $property)" class="flex items-center gap-2">
                                <span>🍸</span> {{ __('Bar POS') }}
                            </x-dropdown-link>
                            @endif
                        </x-slot>
                    </x-dropdown>
                    @endif

                    <!-- Inventory -->
                    @if(auth()->user()->isAdmin() || auth()->user()->hasPermission('manage_inventory'))
                    <x-nav-link :href="route('properties.inventory.index', $property)" :active="request()->routeIs('properties.inventory.*')" class="flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                        <span class="hidden lg:inline">{{ __('Inventory') }}</span>
                    </x-nav-link>
                    @endif

                    <!-- Reports Dropdown (Admin Only) -->
                    @if(auth()->user()->isAdmin())
                    <x-dropdown align="left" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 hover:text-gray-700 hover:bg-gray-50 focus:outline-none transition ease-in-out duration-150 {{ request()->routeIs('reports.*') ? 'text-purple-600 bg-purple-50' : '' }}">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                                <span>{{ __('Reports') }}</span>
                                <svg class="fill-current h-4 w-4 ml-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                            </button>
                        </x-slot>
                        <x-slot name="content">
                            <x-dropdown-link :href="route('reports.daily-sales', $property)" class="flex items-center gap-2">
                                <span>💰</span> {{ __('Sales Report') }}
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('reports.daily-bookings', $property)" class="flex items-center gap-2">
                                <span>📊</span> {{ __('Bookings Report') }}
                            </x-dropdown-link>
                        </x-slot>
                    </x-dropdown>
                    @endif

                    <!-- Settings Dropdown (Admin Only) -->
                    @if(auth()->user()->isAdmin())
                    <x-dropdown align="left" width="56">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 hover:text-gray-700 hover:bg-gray-50 focus:outline-none transition ease-in-out duration-150 {{ request()->routeIs('settings.*') || request()->routeIs('properties.rooms.*') || request()->routeIs('properties.room-types.*') || request()->routeIs('properties.users.*') ? 'text-orange-600 bg-orange-50' : '' }}">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                <span>{{ __('Settings') }}</span>
                                <svg class="fill-current h-4 w-4 ml-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                            </button>
                        </x-slot>
                        <x-slot name="content">
                            <div class="px-3 py-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">Property</div>
                            <x-dropdown-link :href="route('properties.index')" class="flex items-center gap-2">
                                <span>🏨</span> {{ __('All Properties') }}
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('settings.edit', $property)" class="flex items-center gap-2">
                                <span>⚙️</span> {{ __('Hotel Settings') }}
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('settings.branding', $property)" class="flex items-center gap-2">
                                <span>🎨</span> {{ __('Branding') }}
                            </x-dropdown-link>
                            
                            <hr class="my-2">
                            <div class="px-3 py-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">Rooms</div>
                            <x-dropdown-link :href="route('properties.rooms.index', $property)" class="flex items-center gap-2">
                                <span>🚪</span> {{ __('Rooms') }}
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('properties.room-types.index', $property)" class="flex items-center gap-2">
                                <span>🛏️</span> {{ __('Room Types') }}
                            </x-dropdown-link>
                            
                            <hr class="my-2">
                            <div class="px-3 py-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">Users</div>
                            <x-dropdown-link :href="route('properties.users.index', $property)" class="flex items-center gap-2">
                                <span>👤</span> {{ __('Staff Users') }}
                            </x-dropdown-link>
                            
                            <hr class="my-2">
                            <div class="px-3 py-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">Notifications</div>
                            <x-dropdown-link :href="route('settings.email', $property)" class="flex items-center gap-2">
                                <span>📧</span> {{ __('Email Settings') }}
                            </x-dropdown-link>
                            
                            <hr class="my-2">
                            <div class="px-3 py-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">Developer</div>
                            <x-dropdown-link :href="route('settings.api-documentation', $property)" class="flex items-center gap-2">
                                <span>🔌</span> {{ __('API Documentation') }}
                            </x-dropdown-link>
                            
                            <hr class="my-2">
                            <div class="px-3 py-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">System</div>
                            <x-dropdown-link :href="route('system.info')" class="flex items-center gap-2">
                                <span>💻</span> {{ __('System Info & Updates') }}
                            </x-dropdown-link>
                        </x-slot>
                    </x-dropdown>
                    @endif
                    
                    @endif
                </div>
            </div>

            <!-- Right Side -->
            <div class="hidden sm:flex sm:items-center sm:gap-2">
                <!-- Notification Bell -->
                @if(isset($property))
                @php
                    $notificationData = auth()->user()->unreadNotifications()->take(5)->get()->map(function($n) use ($property) {
                        return [
                            'id' => $n->id,
                            'type' => $n->data['type'] ?? 'notification',
                            'title' => isset($n->data['booking_reference']) ? 'New Booking: ' . $n->data['booking_reference'] : 'Notification',
                            'guest' => $n->data['guest_name'] ?? '',
                            'room' => $n->data['room_number'] ?? '',
                            'amount' => isset($n->data['total_amount']) ? ($n->data['currency'] ?? '₦') . number_format($n->data['total_amount'], 2) : '',
                            'time' => $n->created_at->diffForHumans(),
                            'booking_id' => $n->data['booking_id'] ?? null,
                            'property_id' => $n->data['property_id'] ?? $property->id
                        ];
                    });
                @endphp
                <div x-data="notificationBell({{ auth()->user()->unreadNotifications()->count() }}, {{ Js::from($notificationData) }}, '{{ route('notifications.unread-count', $property) }}')" 
                     x-init="startPolling()" 
                     class="relative">
                    <button @click="toggleDropdown()" class="relative inline-flex items-center p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-full focus:outline-none transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                        </svg>
                        <span x-show="unreadCount > 0" class="absolute -top-1 -right-1 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform bg-red-500 rounded-full" x-text="unreadCount > 99 ? '99+' : unreadCount"></span>
                    </button>
                    
                    <!-- Dropdown -->
                    <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="absolute right-0 mt-2 w-96 bg-white rounded-lg shadow-xl z-50 overflow-hidden" style="display: none;">
                        <div class="px-4 py-3 bg-gray-50 border-b flex justify-between items-center">
                            <h3 class="font-semibold text-gray-800">🔔 Notifications</h3>
                            <a href="{{ route('notifications.index', $property) }}" class="text-sm text-blue-600 hover:text-blue-800">View All</a>
                        </div>
                        
                        <div class="max-h-96 overflow-y-auto">
                            <template x-if="notifications.length === 0">
                                <div class="p-8 text-center text-gray-500">
                                    <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                    </svg>
                                    <p>No new notifications</p>
                                </div>
                            </template>
                            
                            <template x-for="notification in notifications" :key="notification.id">
                                <a :href="notification.booking_id ? '/properties/' + notification.property_id + '/bookings/' + notification.booking_id : '#'" class="block px-4 py-3 hover:bg-blue-50 border-b transition">
                                    <div class="flex items-start gap-3">
                                        <div class="flex-shrink-0">
                                            <span class="inline-flex items-center justify-center w-10 h-10 bg-blue-100 text-blue-600 rounded-full">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                            </span>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="font-medium text-gray-800" x-text="notification.title"></p>
                                            <p class="text-sm text-gray-600">
                                                <span x-text="notification.guest"></span>
                                                <span x-show="notification.room" class="text-gray-400">• Room <span x-text="notification.room"></span></span>
                                            </p>
                                            <div class="flex items-center gap-2 mt-1">
                                                <span class="text-sm font-semibold text-green-600" x-text="notification.amount"></span>
                                                <span class="text-xs text-gray-400" x-text="notification.time"></span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </template>
                        </div>
                        
                        <div x-show="unreadCount > 0" class="px-4 py-3 bg-gray-50 border-t">
                            <form action="{{ route('notifications.mark-all-read', $property) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-sm text-blue-600 hover:text-blue-800">Mark all as read</button>
                            </form>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Quick Actions (Admin Only) -->
                @if(isset($property) && auth()->user()->isAdmin())
                <a href="{{ route('properties.bookings.create', $property) }}" class="inline-flex items-center px-3 py-1.5 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    New Booking
                </a>
                @endif

                <!-- User Dropdown -->
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center mr-2">
                                <span class="text-sm font-medium text-gray-600">{{ substr(Auth::user()->name, 0, 1) }}</span>
                            </div>
                            <div class="hidden md:block text-left">
                                <div class="text-sm font-medium">{{ Auth::user()->name }}</div>
                                <div class="text-xs text-gray-400">{{ Auth::user()->role ?? 'Staff' }}</div>
                            </div>
                            <svg class="fill-current h-4 w-4 ml-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="px-4 py-2 border-b">
                            <div class="font-medium text-gray-800">{{ Auth::user()->name }}</div>
                            <div class="text-sm text-gray-500">{{ Auth::user()->email }}</div>
                        </div>
                        
                        <x-dropdown-link :href="route('profile.edit')" class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            {{ __('My Profile') }}
                        </x-dropdown-link>
                        
                        <x-dropdown-link :href="route('profile.edit') . '#update-password'" class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                            {{ __('Change Password') }}
                        </x-dropdown-link>

                        <hr class="my-1">
                        
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();" class="flex items-center gap-2 text-red-600 hover:bg-red-50">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1 bg-gray-50">
            @if(isset($property))
            
            <!-- Dashboard -->
            <x-responsive-nav-link :href="route('dashboard', $property)" :active="request()->routeIs('dashboard')" class="flex items-center gap-2">
                🏠 {{ __('Dashboard') }}
            </x-responsive-nav-link>

            <!-- Front Desk Section -->
            @if(auth()->user()->isAdmin() || auth()->user()->hasPermission('manage_bookings') || auth()->user()->hasPermission('checkin_checkout'))
            <div class="px-4 py-2 text-xs font-semibold text-gray-400 uppercase tracking-wider bg-gray-100">Front Desk</div>
            <x-responsive-nav-link :href="route('properties.bookings.index', $property)" :active="request()->routeIs('properties.bookings.*')" class="flex items-center gap-2">
                📅 {{ __('Bookings') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('booking-history.index', $property)" :active="request()->routeIs('booking-history.*')" class="flex items-center gap-2">
                📜 {{ __('History') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('properties.guests.index', $property)" :active="request()->routeIs('properties.guests.*')" class="flex items-center gap-2">
                👥 {{ __('Guests') }}
            </x-responsive-nav-link>
            @endif

            <!-- POS Section -->
            @if(auth()->user()->isAdmin() || auth()->user()->hasPermission('create_orders') || auth()->user()->hasPermission('manage_orders') || auth()->user()->hasPermission('create_bar_orders') || auth()->user()->hasPermission('manage_bar_orders'))
            <div class="px-4 py-2 text-xs font-semibold text-gray-400 uppercase tracking-wider bg-gray-100">Point of Sale</div>
            @if(auth()->user()->isAdmin() || auth()->user()->hasPermission('create_orders') || auth()->user()->hasPermission('manage_orders'))
            <x-responsive-nav-link :href="route('orders.pos', $property)" :active="request()->routeIs('orders.pos')" class="flex items-center gap-2">
                🍽️ {{ __('Restaurant POS') }}
            </x-responsive-nav-link>
            @endif
            @if(auth()->user()->isAdmin() || auth()->user()->hasPermission('create_bar_orders') || auth()->user()->hasPermission('manage_bar_orders'))
            <x-responsive-nav-link :href="route('properties.bar-orders.pos', $property)" :active="request()->routeIs('properties.bar-orders.pos')" class="flex items-center gap-2">
                🍸 {{ __('Bar POS') }}
            </x-responsive-nav-link>
            @endif
            @endif

            <!-- Inventory -->
            @if(auth()->user()->isAdmin() || auth()->user()->hasPermission('manage_inventory'))
            <x-responsive-nav-link :href="route('properties.inventory.index', $property)" :active="request()->routeIs('properties.inventory.*')" class="flex items-center gap-2">
                📦 {{ __('Inventory') }}
            </x-responsive-nav-link>
            @endif

            <!-- Admin Only Sections -->
            @if(auth()->user()->isAdmin())
            <div class="px-4 py-2 text-xs font-semibold text-gray-400 uppercase tracking-wider bg-gray-100">Reports</div>
            <x-responsive-nav-link :href="route('reports.daily-sales', $property)" :active="request()->routeIs('reports.daily-sales')" class="flex items-center gap-2">
                💰 {{ __('Sales Report') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('reports.daily-bookings', $property)" :active="request()->routeIs('reports.daily-bookings')" class="flex items-center gap-2">
                📊 {{ __('Bookings Report') }}
            </x-responsive-nav-link>

            <div class="px-4 py-2 text-xs font-semibold text-gray-400 uppercase tracking-wider bg-gray-100">Settings</div>
            <x-responsive-nav-link :href="route('properties.index')" class="flex items-center gap-2">
                🏨 {{ __('All Properties') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('settings.edit', $property)" :active="request()->routeIs('settings.edit')" class="flex items-center gap-2">
                ⚙️ {{ __('Hotel Settings') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('properties.rooms.index', $property)" :active="request()->routeIs('properties.rooms.*')" class="flex items-center gap-2">
                🚪 {{ __('Rooms') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('properties.room-types.index', $property)" :active="request()->routeIs('properties.room-types.*')" class="flex items-center gap-2">
                🛏️ {{ __('Room Types') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('properties.users.index', $property)" :active="request()->routeIs('properties.users.*')" class="flex items-center gap-2">
                👤 {{ __('Staff Users') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('settings.api-documentation', $property)" class="flex items-center gap-2">
                🔌 {{ __('API Docs') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('system.info')" class="flex items-center gap-2">
                💻 {{ __('System Info') }}
            </x-responsive-nav-link>
            @endif
            
            @endif
        </div>

        <!-- Responsive User Section -->
        <div class="pt-4 pb-1 border-t border-gray-200 bg-white">
            <div class="px-4 flex items-center">
                <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center mr-3">
                    <span class="text-lg font-medium text-gray-600">{{ substr(Auth::user()->name, 0, 1) }}</span>
                </div>
                <div>
                    <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')" class="flex items-center gap-2">
                    👤 {{ __('Profile') }}
                </x-responsive-nav-link>
                
                <x-responsive-nav-link :href="route('profile.edit') . '#update-password'" class="flex items-center gap-2">
                    🔑 {{ __('Change Password') }}
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();" class="flex items-center gap-2 text-red-600">
                        🚪 {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>

<!-- Notification Sound & Script -->
@if(isset($property))
<script>
// Notification Sound System
const NotificationSound = {
    audioContext: null,
    isReady: false,
    
    init() {
        // Initialize on any user interaction
        const events = ['click', 'touchstart', 'keydown'];
        const initHandler = () => {
            if (!this.audioContext) {
                try {
                    this.audioContext = new (window.AudioContext || window.webkitAudioContext)();
                    this.isReady = true;
                    console.log('🔊 Sound system ready');
                } catch(e) {
                    console.warn('AudioContext not supported');
                }
            }
            if (this.audioContext && this.audioContext.state === 'suspended') {
                this.audioContext.resume();
            }
        };
        
        events.forEach(event => {
            document.addEventListener(event, initHandler, { passive: true });
        });
    },
    
    play() {
        if (!this.audioContext) {
            console.log('Sound not initialized - click anywhere first');
            return;
        }
        
        if (this.audioContext.state === 'suspended') {
            this.audioContext.resume();
        }
        
        try {
            // Play a pleasant notification chime
            const now = this.audioContext.currentTime;
            
            // First tone
            this.playTone(880, now, 0.15);        // A5
            this.playTone(1108.73, now + 0.15, 0.15);  // C#6
            this.playTone(1318.51, now + 0.3, 0.3);    // E6
            
            console.log('🔔 Sound played!');
        } catch(e) {
            console.error('Sound play error:', e);
        }
    },
    
    playTone(frequency, startTime, duration) {
        const osc = this.audioContext.createOscillator();
        const gain = this.audioContext.createGain();
        
        osc.connect(gain);
        gain.connect(this.audioContext.destination);
        
        osc.frequency.value = frequency;
        osc.type = 'sine';
        
        gain.gain.setValueAtTime(0, startTime);
        gain.gain.linearRampToValueAtTime(0.4, startTime + 0.01);
        gain.gain.exponentialRampToValueAtTime(0.001, startTime + duration);
        
        osc.start(startTime);
        osc.stop(startTime + duration);
    },
    
    test() {
        if (!this.audioContext) {
            alert('Click anywhere on the page first to enable sound, then try again.');
            return;
        }
        this.play();
    }
};

// Initialize sound system
NotificationSound.init();

function notificationBell(initialCount, initialNotifications, pollUrl) {
    return {
        open: false,
        unreadCount: initialCount,
        notifications: initialNotifications,
        pollInterval: null,
        
        toggleDropdown() {
            this.open = !this.open;
        },
        
        startPolling() {
            // Poll every 15 seconds for new notifications
            this.pollInterval = setInterval(() => {
                this.checkForNewNotifications();
            }, 15000);
            
            // Request browser notification permission
            this.requestNotificationPermission();
        },
        
        async checkForNewNotifications() {
            try {
                const response = await fetch(pollUrl, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                if (response.ok) {
                    const data = await response.json();
                    const newCount = data.count;
                    
                    // If there are new notifications
                    if (newCount > this.unreadCount) {
                        console.log('🔔 New notification detected! Old:', this.unreadCount, 'New:', newCount);
                        
                        // Play sound
                        NotificationSound.play();
                        
                        // Show browser notification
                        this.showBrowserNotification(newCount - this.unreadCount);
                        
                        // Animate the bell
                        this.animateBell();
                        
                        // Update count
                        this.unreadCount = newCount;
                        
                        // Show modal alert (instead of auto-reload)
                        this.showModalAlert(newCount - this.unreadCount + 1);
                    }
                }
            } catch (error) {
                console.log('Notification check error:', error);
            }
        },
        
        animateBell() {
            const bell = this.$el.querySelector('svg');
            if (bell) {
                bell.classList.add('animate-bounce');
                setTimeout(() => bell.classList.remove('animate-bounce'), 2000);
            }
        },
        
        requestNotificationPermission() {
            if ('Notification' in window && Notification.permission === 'default') {
                Notification.requestPermission();
            }
        },
        
        showBrowserNotification(count) {
            if ('Notification' in window && Notification.permission === 'granted') {
                const notification = new Notification('🔔 New Booking Alert!', {
                    body: `You have ${count} new booking notification${count > 1 ? 's' : ''}`,
                    icon: '{{ $property->logo_path ? asset($property->logo_path) : "" }}',
                    tag: 'booking-notification',
                    requireInteraction: true
                });
                
                notification.onclick = () => {
                    window.focus();
                    notification.close();
                };
                
                setTimeout(() => notification.close(), 10000);
            }
        },
        
        showModalAlert(count) {
            // Show the modal
            const modal = document.getElementById('notification-modal');
            const countEl = document.getElementById('modal-notification-count');
            if (modal && countEl) {
                countEl.textContent = count;
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }
        },
        
        destroy() {
            if (this.pollInterval) clearInterval(this.pollInterval);
        }
    };
}

// Close notification modal
window.closeNotificationModal = function() {
    const modal = document.getElementById('notification-modal');
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
};

// View notifications from modal
window.viewNotifications = function() {
    window.closeNotificationModal();
    window.location.href = '{{ route("notifications.index", $property) }}';
};

// Test sound function - can call from console: testNotificationSound()
window.testNotificationSound = () => NotificationSound.test();

// Move modal to body on page load to avoid stacking context issues
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('notification-modal');
    if (modal) {
        document.body.appendChild(modal);
    }
});
</script>

<!-- Notification Alert Modal - Will be moved to body via JS -->
<div id="notification-modal" class="hidden fixed inset-0 items-center justify-center p-4" style="z-index: 99999 !important; position: fixed !important; background: transparent;">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full transform transition-all animate-bounce-in overflow-hidden" style="position: relative; z-index: 100000; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25), 0 0 0 1px rgba(0, 0, 0, 0.05);">
        <!-- Header with animation -->
        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-4 relative overflow-hidden">
            <div class="absolute inset-0 bg-white opacity-10 animate-pulse"></div>
            <div class="relative flex items-center gap-3">
                <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center animate-bounce">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-white">🔔 New Booking Alert!</h3>
                    <p class="text-blue-100 text-sm">You have a new booking from the website</p>
                </div>
            </div>
        </div>
        
        <!-- Content -->
        <div class="px-6 py-5">
            <div class="text-center mb-4">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-green-100 rounded-full mb-3">
                    <span class="text-3xl">📋</span>
                </div>
                <p class="text-gray-700 text-lg">
                    You have <span id="modal-notification-count" class="font-bold text-blue-600">1</span> new booking notification<span id="modal-plural">s</span>!
                </p>
                <p class="text-gray-500 text-sm mt-2">
                    A guest has just made a reservation through your website.
                </p>
            </div>
            
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 mb-4">
                <div class="flex items-start gap-2">
                    <span class="text-yellow-500">⚡</span>
                    <p class="text-sm text-yellow-800">
                        <strong>Quick Action:</strong> Review and confirm the booking to secure the reservation.
                    </p>
                </div>
            </div>
        </div>
        
        <!-- Actions -->
        <div class="px-6 py-4 bg-gray-50 border-t flex gap-3">
            <button onclick="closeNotificationModal()" class="flex-1 px-4 py-2.5 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 transition font-medium">
                Dismiss
            </button>
            <button onclick="viewNotifications()" class="flex-1 px-4 py-2.5 text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition font-medium flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                </svg>
                View Bookings
            </button>
        </div>
    </div>
</div>

<style>
@keyframes bellShake {
    0%, 100% { transform: rotate(0); }
    10%, 30%, 50%, 70%, 90% { transform: rotate(-10deg); }
    20%, 40%, 60%, 80% { transform: rotate(10deg); }
}
.animate-bell { animation: bellShake 0.5s ease-in-out; }

@keyframes bounce-in {
    0% { transform: scale(0.5); opacity: 0; }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); opacity: 1; }
}
.animate-bounce-in { animation: bounce-in 0.4s ease-out; }
</style>
@endif
